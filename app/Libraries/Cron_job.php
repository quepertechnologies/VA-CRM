<?php

namespace App\Libraries;

use App\Controllers\App_Controller;
use App\Libraries\Google_calendar_events;
use App\Libraries\Imap;
use App\Libraries\Outlook_imap;

class Cron_job
{
    private $today = null;
    private $current_time = null;
    private $ci = null;

    function run()
    {
        $this->today = get_today_date();
        $this->ci = new App_Controller();
        $this->current_time = strtotime(get_current_utc_time());

        $this->call_hourly_jobs();
        $this->call_daily_jobs();

        try {
            $this->run_imap();
        } catch (\Exception $e) {
            echo $e;
        }

        try {
            $this->get_google_calendar_events();
        } catch (\Exception $e) {
            echo $e;
        }

        try {
            $this->close_inactive_tickets();
        } catch (\Exception $e) {
            echo $e;
        }

        try {
            $this->move_leads();
        } catch (\Exception $e) {
            echo $e;
        }

        try {
            $this->update_labels_leads();
        } catch (\Exception $e) {
            echo $e;
        }

        // try {
        //     $this->manage_payment_schedule(); // schedule invoices if the date is reached
        // } catch (\Exception $e) {
        //     echo $e;
        // }

        // try {
        //     $this->manage_upcoming_payment_schedule(); // send email to the application owner
        // } catch (\Exception $e) {
        //     echo $e;
        // }

        try {
            $this->sync_fresh_desk_agents(); // sync fresh desk tickets
        } catch (\Exception $e) {
            echo $e;
        }

        try {
            $this->sync_fresh_desk_tickets(); // sync fresh desk tickets
        } catch (\Exception $e) {
            echo $e;
        }
    }

    private function sync_fresh_desk_tickets()
    {
        $tickets = $this->_fresh_desk_api('tickets?include=requester,stats');

        $fd_ticket_status = array(
            2 => 'open',
            3 => 'open',
            4 => 'open',
            5 => 'closed',
        );

        if ($tickets) {
            foreach ($tickets as $fresh_desk_ticket) {
                $ticket_id = null;

                $client_id = 0;
                $project_id = 0;
                $ticket_type_id = 1;
                $location_id = 0;
                $fresh_desk_ticket_id = get_array_value($fresh_desk_ticket, 'id');
                $title = get_array_value($fresh_desk_ticket, 'subject');
                $created_by = 1;
                $requested_by = 0;
                $created_at = date('Y-m-d H:i:s', strtotime(get_array_value($fresh_desk_ticket, 'created_at')));
                $status = 'new';
                $last_activity_at = date('Y-m-d H:i:s', strtotime(get_array_value($fresh_desk_ticket, 'updated_at')));
                $assigned_to = 1;
                $creator_name = '';
                $creator_email = '';
                $labels = array();
                $task_id = 0;
                $closed_at = '';
                $merged_with_ticket_id = '';

                $ticket = $this->ci->Tickets_model->get_details(array('fresh_desk_ticket_id' => $fresh_desk_ticket_id))->getRow();
                if ($ticket && $ticket->id) {
                    $ticket_id = $ticket->id;
                }

                $requester = get_array_value($fresh_desk_ticket, 'requester');
                if ($requester) {
                    $requester_email = get_array_value($requester, 'email');
                    $client = $this->ci->Clients_model->get_details(array('email' => $requester_email))->getRow();

                    if ($client && $client->id) {
                        $client_id = $client->id;
                    }
                }

                $ticket_type = get_array_value($fresh_desk_ticket, 'type');
                if ($ticket_type) {
                    $ticket_type_id = ticket_type($ticket_type);
                }

                $responder_id = get_array_value($fresh_desk_ticket, 'responder_id');
                if ($responder_id) {
                    $user = $this->ci->Users_model->get_details(array('fresh_desk_agent_id' => $responder_id))->getRow();
                    if ($user) {
                        $created_by = $user->id;
                        $assigned_to = $user->id;
                        $creator_name = $user->first_name . ' ' . $user->last_name;
                        $creator_email = $user->email;
                    }
                }

                $status_id = get_array_value($fresh_desk_ticket, 'status');
                if ($status_id) {
                    $status = $fd_ticket_status[$status_id];
                }

                $stats = get_array_value($fresh_desk_ticket, 'stats');
                if ($status_id == 5) { // the ticket is closed
                    $fd_ticket_closed_at = get_array_value($stats, 'closed_at');
                    if ($fd_ticket_closed_at) {
                        $closed_at = date('Y-m-d H:i:s', strtotime($fd_ticket_closed_at));
                    }
                }

                $tags = get_array_value($fresh_desk_ticket, 'tags');
                if ($tags) {
                    foreach ($tags as $tag) {
                        $labels[] = context_label($tag, 'ticket');
                    }
                }

                $ticket_data = array(
                    'client_id' => $client_id,
                    'project_id' => $project_id,
                    'ticket_type_id' => $ticket_type_id,
                    'location_id' => $location_id,
                    'fresh_desk_ticket_id' => $fresh_desk_ticket_id,
                    'title' => $title,
                    'created_by' => $created_by,
                    'requested_by' => $requested_by,
                    'created_at' => $created_at,
                    'status' => $status,
                    'last_activity_at' => $last_activity_at,
                    'assigned_to' => $assigned_to,
                    'creator_name' => $creator_name,
                    'creator_email' => $creator_email,
                    'labels' => implode(',', $labels),
                    'task_id' => $task_id,
                    'closed_at' => $closed_at,
                    'merged_with_ticket_id' => $merged_with_ticket_id
                );

                $success = $this->ci->Tickets_model->ci_save($ticket_data, $ticket_id);

                if ($success) {
                    $agent_responded_at = get_array_value($fresh_desk_ticket, 'agent_responded_at');
                    $requester_responded_at = get_array_value($fresh_desk_ticket, 'requester_responded_at');
                    $first_responded_at = get_array_value($fresh_desk_ticket, 'first_responded_at');

                    if ($agent_responded_at || $requester_responded_at || $first_responded_at) {
                        $comments = $this->_fresh_desk_api('tickets/' . $fresh_desk_ticket_id . '/conversations');
                        if ($comments) {
                            foreach ($comments as $comment) {
                                $ticket_comment_id = null;

                                $ticket_comment = $this->ci->Ticket_comments_model->get_details(array('fresh_desk_comment_id' => get_array_value($comment, 'id')))->getRow();
                                if ($ticket_comment) {
                                    $ticket_comment_id = $ticket_comment->id;
                                }

                                $comment_data = array(
                                    'ticket_id' => $success,
                                    'fresh_desk_comment_id' => get_array_value($comment, 'id'),
                                );

                                $created_by_user = $this->ci->Users_model->get_details(array('email' => get_array_value($comment, 'from_email')))->getRow();
                                if ($created_by_user) {
                                    $comment_data['created_by'] = $created_by_user->id;
                                } else {
                                    $comment_data['created_by'] = 0;
                                }

                                $comment_data['created_at'] = date('Y-m-d H:i:s', strtotime(get_array_value($comment, 'created_at')));
                                $comment_data['description'] = get_array_value($comment, 'body');
                                $comment_data['files'] = serialize(array());
                                $comment_data['is_note'] = $comment_data['created_by'] == 0 ? 1 : 0;

                                $this->ci->Ticket_comments_model->ci_save($comment_data, $ticket_comment_id);
                            }
                        }
                    }
                }
            }
        }
    }

    private function sync_fresh_desk_agents()
    {
        $result = $this->_fresh_desk_api('agents');

        if ($result) {
            foreach ($result as $fresh_desk_agent) {
                $agent_id = get_array_value($fresh_desk_agent, 'id');
                $contact = get_array_value($fresh_desk_agent, 'contact');
                $email = get_array_value($contact, 'email');

                $user = $this->ci->Users_model->get_details(array('email' => $email))->getRow();

                if ($user) {
                    $user_data = array(
                        'fresh_desk_agent_id' => $agent_id
                    );

                    $this->ci->Users_model->ci_save($user_data, $user->id);
                }
            }
        }
    }

    private function _fresh_desk_api($url_path = "")
    {
        $headers = array('Content-type: application/json');

        $api_key = get_setting('fresh_desk_api_key');
        $encoded_api_key = base64_encode($api_key);

        $headers[] = "Authorization: $encoded_api_key";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, get_setting('fresh_desk_domain') . $url_path);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch); // Close the connection

        if ($response) {
            return json_decode($response, true);
        }

        return false;
    }

    private function manage_upcoming_payment_schedule()
    {
        $options = array('status' => 0);
        $schedules = $this->ci->Project_payment_schedule_setup_model->get_details($options)->getResult();

        $project_ids = array();
        foreach ($schedules as $schedule) {
            $project_ids[] = $schedule->project_id;
        }

        $project_ids = array_unique($project_ids);

        foreach ($project_ids as $project_id) {
            $_schedules = array();
            $project = $this->ci->Projects_model->get_one($project_id);
            foreach ($schedules as $schedule) {
                if ($schedule->project_id == $project_id) {
                    $seconds_to_expire = strtotime($schedule->invoice_date) - time();
                    $three_days_before = 3 * 86400;
                    if (($seconds_to_expire <= $three_days_before) && is_null($schedule->notified_at)) {
                        $_schedules[] = $schedule;

                        $save_data = array(
                            'notified_at' => get_current_utc_time()
                        );
                        $this->ci->Project_payment_schedule_setup_model->ci_save($save_data, $schedule->id);
                    }
                }
            }

            if (count($_schedules)) {
                $template = $this->_get_project_payment_schedule_email_template($project->created_by, $_schedules);
                $this->_send_email(get_array_value($template, "message"), get_array_value($template, "subject"), $project_id, $project->created_by);
            }
        }
    }

    private function manage_payment_schedule()
    {
        $options = array('status' => 0);
        $schedules = $this->ci->Project_payment_schedule_setup_model->get_details($options)->getResult();

        foreach ($schedules as $schedule) {
            if (time() >= strtotime($schedule->invoice_date) && $schedule->status == 0) {
                $this->_create_client_invoice($schedule, $schedule->is_claimable);
                $this->_create_partner_invoice($schedule);
            }
        }
    }

    private function _send_email($message = '', $subject = '', $project_id = 0, $contact_id = 0)
    {
        $options = array('project_id' => $project_id, 'is_current' => 1);
        $current_milestone = $this->ci->Milestones_model->get_one_where($options);
        $milestone_id = 0;
        if ($current_milestone) {
            $milestone_id = $current_milestone->id;
        }
        $contact = $this->ci->Users_model->get_one($contact_id);

        $default_bcc = get_setting('send_contract_bcc_to');
        $bcc_emails = "";

        if ($default_bcc) {
            $bcc_emails = $default_bcc;
        }

        if (!is_server_localhost() && send_app_mail($contact->email, $subject, $message, array("cc" => '', "bcc" => $bcc_emails))) {
            $data = array(
                'project_id' => $project_id,
                'milestone_id' => $milestone_id,
                'created_by' => 0,
                'subject' => $subject,
                'send_to' => $contact->email,
                'send_to_name' => $contact->first_name . ' ' . $contact->last_name,
                'send_from' => get_setting("email_sent_from_address"),
                'created_date' => get_current_utc_time()
            );
            $this->ci->Email_activity_model->ci_save($data);
            // echo json_encode(array('success' => true, 'message' => app_lang("email_sent_message")));
        } else {
            // echo json_encode(array('success' => false, 'message' => app_lang('error_occurred')));
        }
    }

    private function _get_project_payment_schedule_email_template($user_id = 0, $schedules = [])
    {
        //task reminders
        $email_template = $this->ci->Email_templates_model->get_final_template("project_task_deadline_reminder", true);

        $parser_data["DEADLINE"] = "N/A";

        //prepare all schedules of this user
        $table = view("projects/notification_payment_schedule_table", array("schedules" => $schedules));

        $parser = \Config\Services::parser();

        $user_info = $this->ci->Users_model->get_one($user_id);
        $user_email_address = $user_info->email;
        $user_language = $user_info->language;

        $parser_data["RECIPIENTS_EMAIL_ADDRESS"] = $user_email_address;
        $parser_data["SIGNATURE"] = get_array_value($email_template, "signature_$user_language") ? get_array_value($email_template, "signature_$user_language") : get_array_value($email_template, "signature_default");

        $parser_data["TASKS_LIST"] = $table;
        $parser_data["APP_TITLE"] = get_setting("app_title");
        $message = get_array_value($email_template, "message_$user_language") ? get_array_value($email_template, "message_$user_language") : get_array_value($email_template, "message_default");
        $message = $parser->setData($parser_data)->renderString($message);
        $parser_data["EVENT_TITLE"] = "Upcoming Payment Schedule Reminder";
        $subject = get_array_value($email_template, "subject_$user_language") ? get_array_value($email_template, "subject_$user_language") : get_array_value($email_template, "subject_default");
        $subject = $parser->setData($parser_data)->renderString($subject);

        return array(
            "message" => $message,
            "subject" => $subject
        );
    }

    private function _create_client_invoice($schedule = null, $is_claimable = false)
    {
        if ($schedule) {
            $label_id = $this->_get_label_id_by_title("Claimable", '#52BE80');
            $invoice_data = array(
                "schedule_id" => $schedule->id,
                'client_id' => $schedule->client_id,
                'project_id' => $schedule->project_id,
                'bill_date' => $schedule->invoice_date,
                'due_date' => $schedule->due_date,
                'note' => 'Auto generated invoice',
                'labels' => $is_claimable ? $label_id : '',
                'status' => 'draft', // Must be draft if not paid. Status will be auto updated upon payment
                'recurring' => 0,
                'recurring_invoice_id' => 0,
                'repeat_every' => 0,
                'repeat_type' => 'months',
                'no_of_cycles' => 0,
                'no_of_cycles_completed' => 0,
                'discount_amount' => $schedule->discount,
                'discount_amount_type' => 'fixed_amount',
                'discount_type' => 'after_tax',
                'cancelled_by' => 0,
                'files' => serialize(array()),
                'company_id' => 1, // Default company is VA. Can be managed through settings
                'estimate_id' => 0,
                'main_invoice_id' => 0,
                'subscription_id' => 0,
                'invoice_total' => $schedule->net_fee,
                'invoice_subtotal' => $schedule->total_fee,
                'discount_total' => $schedule->discount,
                'tax' => 0,
                'tax2' => 0,
                'tax3' => 0
            );

            $invoice_id = $this->ci->Invoices_model->ci_save($invoice_data);

            if ($invoice_id) {
                $fees = unserialize($schedule->fees);
                $fees = json_decode(json_encode($fees), true); // Convert to associative array

                $sort = 1;
                foreach ($fees as $fee) {
                    $schedule_fee_id = get_array_value($fee, 'key');
                    if (!$schedule_fee_id) {
                        $schedule_fee_id = $sort;
                    }
                    $invoice_item_data = array(
                        'schedule_id' => $schedule->id,
                        'schedule_fee_id' => $schedule_fee_id,
                        'invoice_id' => $invoice_id,
                        'title' => get_array_value($fee, 'fee_type'),
                        'description' => '',
                        'quantity' => 1,
                        'unit_type' => '',
                        'rate' => get_array_value($fee, 'amount'),
                        'total' => get_array_value($fee, 'amount'),
                        'sort' => $sort,
                        'item_id' => 0,
                        'taxable' => 0,
                    );
                    $sort++;

                    $this->ci->Invoice_items_model->ci_save($invoice_item_data);
                }

                $schedule_data = array('status' => 1);
                $this->ci->Project_payment_schedule_setup_model->ci_save($schedule_data, $schedule->id);
            }

            return $invoice_id;
        }
    }

    private function _create_partner_invoice($schedule = null)
    {
        if ($schedule) {
            $partner_options = array('project_id' => $schedule->project_id);
            $partners = $this->ci->Project_partners_model->get_details($partner_options)->getResult();
            $label_id = $this->_get_label_id_by_title("Payable", '#EB984E');

            foreach ($partners as $partner) {
                $total = ((int)$partner->commission / 100) * $schedule->net_fee;
                $invoice_data = array(
                    'type' => 'credit_note',
                    'client_id' => $partner->partner_id,
                    'project_id' => $schedule->project_id,
                    'bill_date' => $schedule->invoice_date,
                    'due_date' => $schedule->due_date,
                    'note' => 'Auto generated credit note',
                    'labels' => $label_id,
                    'status' => 'draft', // Must be draft if not paid. Status will be auto updated upon payment
                    'recurring' => 0,
                    'recurring_invoice_id' => 0,
                    'repeat_every' => 0,
                    'repeat_type' => 'months',
                    'no_of_cycles' => 0,
                    'no_of_cycles_completed' => 0,
                    'discount_amount' => 0,
                    'discount_amount_type' => 'fixed_amount',
                    'discount_type' => 'after_tax',
                    'cancelled_by' => 0,
                    'files' => serialize(array()),
                    'company_id' => 1, // Default company is VA. Can be managed through settings
                    'estimate_id' => 0,
                    'main_invoice_id' => 0,
                    'subscription_id' => 0,
                    'invoice_total' => $total,
                    'invoice_subtotal' => $total,
                    'discount_total' => 0,
                    'tax' => 0,
                    'tax2' => 0,
                    'tax3' => 0
                );

                $invoice_id = $this->ci->Invoices_model->ci_save($invoice_data);

                if ($invoice_id) {
                    $fees = unserialize($schedule->fees);
                    $fees = json_decode(json_encode($fees), true); // Convert to associative array

                    $sort = 1;
                    foreach ($fees as $fee) {
                        $fee_total = ((int)$partner->commission / 100) * (int)get_array_value($fee, 'amount');
                        $invoice_item_data = array(
                            'invoice_id' => $invoice_id,
                            'title' => get_array_value($fee, 'fee_type'),
                            'description' => $partner->commission . '% of ' . to_currency((int)get_array_value($fee, 'amount') . ' fee'),
                            'quantity' => 1,
                            'unit_type' => '',
                            'rate' => $fee_total,
                            'total' => $fee_total,
                            'sort' => $sort,
                            'item_id' => 0,
                            'taxable' => 0,
                        );
                        $sort++;

                        $this->ci->Invoice_items_model->ci_save($invoice_item_data);
                    }
                }
            }
        }
    }

    private function _get_label_id_by_title($title = 'Claimable', $color = '#52BE80')
    {
        $options = array('title' => $title);
        $label = $this->ci->Labels_model->get_details($options)->getRow();

        if ($label) {
            return $label->id;
        } else {
            $data = array(
                'title' => $title,
                'color' => $color,
                'context' => 'invoice',
                'user_id' => 0,
            );

            $label_id = $this->ci->Labels_model->ci_save($data);

            return $label_id ? $label_id : 0;
        }
    }

    private function call_hourly_jobs()
    {
        //wait 1 hour for each call of following actions
        if ($this->_is_hourly_job_runnable()) {


            try {
                $this->create_recurring_invoices();
            } catch (\Exception $e) {
                echo $e;
            }

            try {
                $this->create_recurring_expenses();
            } catch (\Exception $e) {
                echo $e;
            }

            try {
                $this->send_invoice_due_pre_reminder();
            } catch (\Exception $e) {
                echo $e;
            }


            try {
                $this->send_invoice_due_after_reminder();
            } catch (\Exception $e) {
                echo $e;
            }


            try {
                $this->send_recurring_invoice_creation_reminder();
            } catch (\Exception $e) {
                echo $e;
            }


            try {
                $this->create_recurring_tasks();
            } catch (\Exception $e) {
                echo $e;
            }

            try {
                $this->send_task_reminder_notifications();
            } catch (\Exception $e) {
                echo $e;
            }

            try {
                $this->create_recurring_reminders();
            } catch (\Exception $e) {
                echo $e;
            }

            try {
                $this->create_subscription_invoices();
            } catch (\Exception $e) {
                echo $e;
            }

            $this->ci->Settings_model->save_setting("last_hourly_job_time", $this->current_time);
        }
    }

    private function move_leads()
    {
        $leads = $this->ci->Clients_model->get_details(array("leads_only" => 1))->getResult();

        foreach ($leads as $lead) {
            if (strtotime($lead->created_date) < strtotime('-7 day')) {
                $client_id = $lead->id;
                $data = array(
                    "lead_status_id" => 3,
                );
                $this->ci->Clients_model->ci_save($data, $client_id);
            }
        }
    }

    private function update_labels_leads()
    {
        $leads = $this->ci->Clients_model->get_details(array("leads_only" => 1))->getResult();

        foreach ($leads as $lead) {
            if (strtotime($lead->created_date) < strtotime('-7 day')) {
                $client_id = $lead->id;
                $data = array(
                    "labels" => 8,
                );
                $this->ci->Clients_model->ci_save($data, $client_id);
            } elseif (strtotime($lead->created_date) < strtotime('-5 day')) {
                $client_id = $lead->id;
                $data = array(
                    "labels" => 9,
                );
                $this->ci->Clients_model->ci_save($data, $client_id);
            } elseif (strtotime($lead->created_date) < strtotime('-3 day')) {
                $client_id = $lead->id;
                $data = array(
                    "labels" => 10,
                );
                $this->ci->Clients_model->ci_save($data, $client_id);
            } else {
                $client_id = $lead->id;
                $data = array(
                    "labels" => 11,
                );
                $this->ci->Clients_model->ci_save($data, $client_id);
            }
        }
    }

    private function _is_hourly_job_runnable()
    {
        $last_hourly_job_time = get_setting('last_hourly_job_time');
        if ($last_hourly_job_time == "" || ($this->current_time > ($last_hourly_job_time * 1 + 3600))) {
            return true;
        }
    }

    private function send_invoice_due_pre_reminder()
    {

        $reminder_date = get_setting("send_invoice_due_pre_reminder");
        $reminder_date2 = get_setting("send_invoice_due_pre_second_reminder");
        if (!($reminder_date || $reminder_date2)) {
            return false;
        }

        //prepare invoice due date accroding to the setting
        $reminder_due_date = $reminder_date ? add_period_to_date($this->today, $reminder_date, "days") : "";
        $reminder_due_date2 = $reminder_date2 ? add_period_to_date($this->today, $reminder_date2, "days") : "";

        $invoices = $this->ci->Invoices_model->get_details(array(
            "status" => "not_paid_and_partially_paid", //find all invoices which are not paid yet but due date not expired
            "reminder_due_date" => $reminder_due_date,
            "reminder_due_date2" => $reminder_due_date2,
            "exclude_due_reminder_date" => $this->today //don't find invoices which reminder already sent today
        ))->getResult();

        foreach ($invoices as $invoice) {
            log_notification("invoice_due_reminder_before_due_date", array("invoice_id" => $invoice->id), "0");
        }
    }

    private function send_invoice_due_after_reminder()
    {

        $reminder_date = get_setting("send_invoice_due_after_reminder");
        $reminder_date2 = get_setting("send_invoice_due_after_second_reminder");
        if (!($reminder_date || $reminder_date2)) {
            return false;
        }

        //prepare invoice due date accroding to the setting
        $reminder_due_date = $reminder_date ? subtract_period_from_date($this->today, $reminder_date, "days") : "";
        $reminder_due_date2 = $reminder_date2 ? subtract_period_from_date($this->today, $reminder_date2, "days") : "";

        $invoices = $this->ci->Invoices_model->get_details(array(
            "status" => "overdue", //find all invoices where due date has expired
            "reminder_due_date" => $reminder_due_date,
            "reminder_due_date2" => $reminder_due_date2,
            "exclude_due_reminder_date" => $this->today //don't find invoices which reminder already sent today
        ))->getResult();

        foreach ($invoices as $invoice) {
            log_notification("invoice_overdue_reminder", array("invoice_id" => $invoice->id), "0");
        }
    }

    private function send_recurring_invoice_creation_reminder()
    {

        $reminder_date = get_setting("send_recurring_invoice_reminder_before_creation");

        if ($reminder_date) {

            //prepare invoice due date accroding to the setting
            $start_date = add_period_to_date($this->today, get_setting("send_recurring_invoice_reminder_before_creation"), "days");

            $invoices = $this->ci->Invoices_model->get_details(array(
                "status" => "not_paid", //non-draft invoices
                "recurring" => 1,
                "next_recurring_start_date" => $start_date,
                "next_recurring_end_date" => $start_date, //both should be same
                "exclude_recurring_reminder_date" => $this->today //don't find invoices which reminder already sent today
            ))->getResult();

            foreach ($invoices as $invoice) {
                log_notification("recurring_invoice_creation_reminder", array("invoice_id" => $invoice->id), "0");
            }
        }
    }

    private function create_recurring_invoices()
    {
        $recurring_invoices = $this->ci->Invoices_model->get_renewable_invoices($this->today);
        if ($recurring_invoices->resultID->num_rows) {
            foreach ($recurring_invoices->getResult() as $invoice) {
                $this->_create_new_invoice($invoice);
            }
        }
    }

    //create new invoice from a recurring invoice 
    private function _create_new_invoice($invoice)
    {

        //don't update the next recurring date when updating invoice manually?
        //stop backdated recurring invoice creation.
        //check recurring invoice once/hour?
        //settings: send invoice to client


        $bill_date = $invoice->next_recurring_date;
        $diff_of_due_date = get_date_difference_in_days($invoice->due_date, $invoice->bill_date); //calculate the due date difference of the original invoice
        $due_date = add_period_to_date($bill_date, $diff_of_due_date, "days");

        $new_invoice_data = array(
            "client_id" => $invoice->client_id,
            "project_id" => $invoice->project_id,
            "bill_date" => $bill_date,
            "due_date" => $due_date,
            "note" => $invoice->note,
            "status" => "draft",
            "tax_id" => $invoice->tax_id,
            "tax_id2" => $invoice->tax_id2,
            "tax_id3" => $invoice->tax_id3,
            "recurring_invoice_id" => $invoice->id,
            "discount_amount" => $invoice->discount_amount,
            "discount_amount_type" => $invoice->discount_amount_type,
            "discount_type" => $invoice->discount_type,
            "company_id" => $invoice->company_id,
            "invoice_subtotal" => $invoice->invoice_subtotal,
            "invoice_total" => $invoice->invoice_total,
            "discount_total" => $invoice->discount_total,
            "tax" => $invoice->tax,
            "tax2" => $invoice->tax2,
            "tax3" => $invoice->tax3
        );

        //create new invoice
        $new_invoice_id = $this->ci->Invoices_model->ci_save($new_invoice_data);

        //create invoice items
        $items = $this->ci->Invoice_items_model->get_details(array("invoice_id" => $invoice->id))->getResult();
        foreach ($items as $item) {
            //create invoice items for new invoice
            $new_invoice_item_data = array(
                "title" => $item->title,
                "description" => $item->description,
                "quantity" => $item->quantity,
                "unit_type" => $item->unit_type,
                "rate" => $item->rate,
                "total" => $item->total,
                "taxable" => $item->taxable,
                "invoice_id" => $new_invoice_id,
            );
            $this->ci->Invoice_items_model->ci_save($new_invoice_item_data);
        }


        //update the main recurring invoice
        $no_of_cycles_completed = $invoice->no_of_cycles_completed + 1;
        $next_recurring_date = add_period_to_date($bill_date, $invoice->repeat_every, $invoice->repeat_type);

        $recurring_invoice_data = array(
            "next_recurring_date" => $next_recurring_date,
            "no_of_cycles_completed" => $no_of_cycles_completed
        );
        $this->ci->Invoices_model->ci_save($recurring_invoice_data, $invoice->id);

        //finally send notification
        log_notification("recurring_invoice_created_vai_cron_job", array("invoice_id" => $new_invoice_id), "0");
    }

    private function create_subscription_invoices()
    {
        $subscriptions = $this->ci->Subscriptions_model->get_renewable_subscriptions($this->today);
        if ($subscriptions->resultID->num_rows) {
            foreach ($subscriptions->getResult() as $subscription) {
                $this->_create_new_invoice_of_subscription($subscription);
            }
        }
    }

    //create new invoice from a subscription
    private function _create_new_invoice_of_subscription($subscription_info)
    {
        $invoice_id = create_invoice_from_subscription($subscription_info->id);

        //update the main recurring subscription
        $no_of_cycles_completed = $subscription_info->no_of_cycles_completed + 1;
        $next_recurring_date = add_period_to_date($subscription_info->next_recurring_date, $subscription_info->repeat_every, $subscription_info->repeat_type);

        $subscription_data = array(
            "next_recurring_date" => $next_recurring_date,
            "no_of_cycles_completed" => $no_of_cycles_completed
        );

        $this->ci->Subscriptions_model->ci_save($subscription_data, $subscription_info->id);

        //finally send notification
        log_notification("subscription_invoice_created_via_cron_job", array("invoice_id" => $invoice_id, "subscription_id" => $subscription_info->id), "0");
    }

    private function get_google_calendar_events()
    {
        $Google_calendar_events = new Google_calendar_events();
        $Google_calendar_events->get_google_calendar_events();
    }

    private function run_imap()
    {
        if (!$this->_is_imap_callable()) {
            return false;
        }

        if (!get_setting('imap_type') || get_setting('imap_type') === "general_imap") {
            $imap = new Imap();
            $imap->run_imap();
        } else {
            $imap = new Outlook_imap();
            $imap->run_imap();
        }

        $this->ci->Settings_model->save_setting("last_cron_job_time_of_imap", $this->current_time);
    }

    private function _is_imap_callable()
    {

        //check if settings is enabled and authorized
        if (!(get_setting("enable_email_piping") && get_setting("imap_authorized"))) {
            return false;
        }

        //wait 10 minutes for each check
        $last_cron_job_time_of_imap = get_setting('last_cron_job_time_of_imap');
        if ($last_cron_job_time_of_imap == "" || ($this->current_time > ($last_cron_job_time_of_imap * 1 + 600))) {
            return true;
        }
    }

    private function create_recurring_tasks()
    {

        if (!get_setting("enable_recurring_option_for_tasks")) {
            return false;
        }

        $date = $this->today;

        //if create recurring task before certain days setting is active,
        //add the days with today
        $create_recurring_tasks_before = get_setting("create_recurring_tasks_before");
        if ($create_recurring_tasks_before) {
            $date = add_period_to_date($date, $create_recurring_tasks_before, "days");
        }

        $recurring_tasks = $this->ci->Tasks_model->get_renewable_tasks($date);
        if ($recurring_tasks->resultID->num_rows) {
            foreach ($recurring_tasks->getResult() as $task) {
                $this->_create_new_task($task);
            }
        }
    }

    //create new task from a recurring task 
    private function _create_new_task($task)
    {

        //don't update the next recurring date when updating task manually
        //stop backdated recurring task creation.
        //check recurring task once/hour?

        $start_date = $task->next_recurring_date;
        $deadline = NULL;

        $context = $task->context;

        if ($task->deadline) {
            $task_start_date = $task->start_date ? $task->start_date : $task->created_date;
            $diff_of_deadline = get_date_difference_in_days($task->deadline, $task_start_date); //calculate the deadline difference of the original task
            $deadline = add_period_to_date($start_date, $diff_of_deadline, "days");
        }

        $new_task_data = array(
            "title" => $task->title,
            "description" => $task->description,
            "project_id" => $task->project_id,
            "milestone_id" => $task->milestone_id,
            "points" => $task->points,
            "status_id" => 1, //new tasks should be on ToDo
            "context" => $context,
            "client_id" => $task->client_id,
            "lead_id" => $task->lead_id,
            "invoice_id" => $task->invoice_id,
            "estimate_id" => $task->estimate_id,
            "order_id" => $task->order_id,
            "contract_id" => $task->contract_id,
            "proposal_id" => $task->proposal_id,
            "expense_id" => $task->expense_id,
            "subscription_id" => $task->subscription_id,
            "priority_id" => $task->priority_id,
            "labels" => $task->labels,
            "start_date" => $start_date,
            "deadline" => $deadline,
            "recurring_task_id" => $task->id,
            "assigned_to" => $task->assigned_to,
            "collaborators" => $task->collaborators,
            "created_date" => get_current_utc_time(),
            "activity_log_created_by_app" => true
        );

        $new_task_data["sort"] = $this->ci->Tasks_model->get_next_sort_value($task->project_id, $new_task_data["status_id"]);

        //create new task
        $new_task_id = $this->ci->Tasks_model->ci_save($new_task_data);

        //create checklist items
        $Checklist_items_model = model("App\Models\Checklist_items_model");
        $checklist_item_options = array("task_id" => $task->id);
        $checklist_items = $Checklist_items_model->get_details($checklist_item_options);
        if ($checklist_items->resultID->num_rows) {
            foreach ($checklist_items->getResult() as $item) {
                $checklist_item_data = array(
                    "title" => $item->title,
                    "is_checked" => $item->is_checked,
                    "task_id" => $new_task_id,
                    "sort" => $item->sort
                );

                $Checklist_items_model->ci_save($checklist_item_data);
            }
        }

        //create sub tasks
        $sub_tasks = $this->ci->Tasks_model->get_all_where(array("parent_task_id" => $task->id, "deleted" => 0))->getResult();
        foreach ($sub_tasks as $sub_task) {
            //prepare new sub task data
            $sub_task_data = (array) $sub_task;

            unset($sub_task_data["id"]);
            unset($sub_task_data["blocked_by"]);
            unset($sub_task_data["blocking"]);

            if ($task->start_date && $sub_task->start_date) {
                $sub_task_data['start_date'] = $start_date;
            } else {
                $sub_task_data['start_date'] = NULL;
            }

            $sub_task_data['status_id'] = 1;
            $sub_task_data['parent_task_id'] = $new_task_id;
            $sub_task_data['created_date'] = get_current_utc_time();
            $sub_task_data['deadline'] = NULL;

            $sub_task_data["sort"] = $this->ci->Tasks_model->get_next_sort_value(get_array_value($sub_task_data, "project_id"), $sub_task_data["status_id"]);

            $sub_task_save_id = $this->ci->Tasks_model->ci_save($sub_task_data);

            //create sub tasks checklist
            $checklist_items = $Checklist_items_model->get_all_where(array("task_id" => $sub_task->id, "deleted" => 0))->getResult();
            foreach ($checklist_items as $checklist_item) {
                //prepare new checklist data
                $checklist_item_data = (array) $checklist_item;
                unset($checklist_item_data["id"]);
                $checklist_item_data['task_id'] = $sub_task_save_id;

                $Checklist_items_model->ci_save($checklist_item_data);
            }
        }

        //update the main recurring task
        $no_of_cycles_completed = $task->no_of_cycles_completed + 1;
        $next_recurring_date = add_period_to_date($start_date, $task->repeat_every, $task->repeat_type);

        $recurring_task_data = array(
            "next_recurring_date" => $next_recurring_date,
            "no_of_cycles_completed" => $no_of_cycles_completed
        );
        $this->ci->Tasks_model->save_reminder_date($recurring_task_data, $task->id);

        //send notification
        if ($context === "project") {
            $notification_option = array("project_id" => $task->project_id, "task_id" => $new_task_id);
        } else {
            $context_id_key = $context . "_id";
            $context_id_value = $task->{$context . "_id"};

            $notification_option = array("$context_id_key" => $context_id_value, "task_id" => $new_task_id);
        }

        log_notification("recurring_task_created_via_cron_job", $notification_option, "0");
    }

    private function send_task_reminder_notifications()
    {
        $notification_option = array("notification_multiple_tasks" => true);
        log_notification("project_task_deadline_pre_reminder", $notification_option, "0");
        log_notification("project_task_deadline_overdue_reminder", $notification_option, "0");
        log_notification("project_task_reminder_on_the_day_of_deadline", $notification_option, "0");
    }

    private function close_inactive_tickets()
    {

        $inactive_ticket_closing_date = get_setting("inactive_ticket_closing_date");
        if (!($inactive_ticket_closing_date == "" || ($inactive_ticket_closing_date != $this->today))) {
            return false;
        }

        $auto_close_ticket_after_days = get_setting("auto_close_ticket_after");

        if ($auto_close_ticket_after_days) {
            //prepare last activity date accroding to the setting
            $last_activity_date = subtract_period_from_date($this->today, get_setting("auto_close_ticket_after"), "days");

            $tickets = $this->ci->Tickets_model->get_details(array(
                "status" => "open", //don't find closed tickets
                "last_activity_date_or_before" => $last_activity_date
            ))->getResult();

            foreach ($tickets as $ticket) {
                //make ticket closed
                $ticket_data = array(
                    "status" => "closed",
                    "closed_at" => get_current_utc_time()
                );

                $this->ci->Tickets_model->ci_save($ticket_data, $ticket->id);

                //send notification
                log_notification("ticket_closed", array("ticket_id" => $ticket->id), "0");
            }
        }

        $this->ci->Settings_model->save_setting("inactive_ticket_closing_date", $this->today);
    }

    private function create_recurring_expenses()
    {
        $recurring_expenses = $this->ci->Expenses_model->get_renewable_expenses($this->today);
        if ($recurring_expenses->resultID->num_rows) {
            foreach ($recurring_expenses->getResult() as $expense) {
                $this->_create_new_expense($expense);
            }
        }
    }

    //create new expense from a recurring expense 
    private function _create_new_expense($expense)
    {

        //don't update the next recurring date when updating expense manually?
        //stop backdated recurring expense creation.
        //check recurring expense once/hour?

        $expense_date = $expense->next_recurring_date;

        $new_expense_data = array(
            "title" => $expense->title,
            "expense_date" => $expense_date,
            "description" => $expense->description,
            "category_id" => $expense->category_id,
            "amount" => $expense->amount,
            "project_id" => $expense->project_id,
            "user_id" => $expense->user_id,
            "tax_id" => $expense->tax_id,
            "tax_id2" => $expense->tax_id2,
            "recurring_expense_id" => $expense->id
        );

        //create new expense
        $new_expense_id = $this->ci->Expenses_model->ci_save($new_expense_data);

        //update the main recurring expense
        $no_of_cycles_completed = $expense->no_of_cycles_completed + 1;
        $next_recurring_date = add_period_to_date($expense_date, $expense->repeat_every, $expense->repeat_type);

        $recurring_expense_data = array(
            "next_recurring_date" => $next_recurring_date,
            "no_of_cycles_completed" => $no_of_cycles_completed
        );

        $this->ci->Expenses_model->ci_save($recurring_expense_data, $expense->id);

        //finally send notification
        //log_notification("recurring_expense_created_vai_cron_job", array("expense_id" => $new_expense_id), "0");
    }

    private function create_recurring_reminders()
    {
        $options = array(
            "type" => "all",
            "recurring" => true,
            "reminder_status" => "new",
        );

        $recurring_reminders = $this->ci->Events_model->get_details($options)->getResult();
        foreach ($recurring_reminders as $reminder) {

            $now = get_my_local_time();
            $target_time = is_null($reminder->next_recurring_time) ? ($reminder->start_date . " " . $reminder->start_time) : $reminder->next_recurring_time;

            if ($target_time < $now && (!$reminder->no_of_cycles || $reminder->no_of_cycles_completed < $reminder->no_of_cycles)) {
                $data["next_recurring_time"] = add_period_to_date($target_time, $reminder->repeat_every, $reminder->repeat_type, "Y-m-d H:i:s");
                $data['no_of_cycles_completed'] = (int) $reminder->no_of_cycles_completed + 1;

                $this->ci->Events_model->ci_save($data, $reminder->id);
            }
        }
    }

    private function call_daily_jobs()
    {
        //wait 1 day for each call of following actions
        if ($this->_is_daily_job_runnable()) {

            try {
                $this->remove_old_session_data();
            } catch (\Exception $e) {
                echo $e;
            }

            $this->ci->Settings_model->save_setting("last_daily_job_time", $this->current_time);
        }
    }

    private function _is_daily_job_runnable()
    {
        $last_daily_job_time = get_setting('last_daily_job_time');
        if ($last_daily_job_time == "" || ($this->current_time > ($last_daily_job_time * 24 * 3600))) {
            return true;
        }
    }

    private function remove_old_session_data()
    {
        $Ci_sessions_model = model('App\Models\Ci_sessions_model');
        $last_weak_date = subtract_period_from_date($this->today, 7, "days");

        $Ci_sessions_model->delete_session_by_date($last_weak_date);
    }
}
