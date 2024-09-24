<?php

namespace App\Controllers;

use Exception;

class Import extends Security_Controller
{
    protected $offset = 0; // process 500 queries per call
    protected $batch_size = 500; // process 500 queries per call
    protected $avoid = array(null, '', '-'); // skip these values
    protected $skip_first_row = TRUE; // skip first row

    function __construct()
    {
        parent::__construct();
    }

    function generate_missing_notes_client_links_file()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 

        $links = array();
        $count = array();
        $missing = 0;

        $clients = $this->Clients_model->get_details(array())->getResult();

        if ($clients) {
            foreach ($clients as $client) {
                $notes_count = $this->Notes_model->count_client_notes($client->id);
                if ((int)$notes_count == 0) {
                    $missing++;
                    $count[] = $notes_count;
                    $links[] = array('https://visaalliance.agentcisapp.com/app/#/contacts/u/' . ltrim($client->va_internal_id, 'VA') . '/notes-terms');
                }
            }
        }

        \Shuchkin\SimpleXLSXGen::fromArray($links)->saveAs($_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'missing_notes_client_links.xlsx');

        echo json_encode(array('success' => true, 'message' => 'Success', 'missing' => $missing, 'count' => implode(',', $count), 'links' => $links));
    }

    function generate_archived_client_links_file()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_archived_clients.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        // $list_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.xlsx';

        $json_file = $this->_read_json($source);
        if ($json_file) {
            $links = array();
            $count = 0;

            $clients = get_array_value($json_file, 'clients');

            if ($clients) {
                foreach ($clients as $client) {
                    $links[] = array(get_array_value($client, 'url'));
                    $count++;
                }
            }

            \Shuchkin\SimpleXLSXGen::fromArray($links)->saveAs($_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'archived_client_links.xlsx');

            echo json_encode(array('success' => true, 'message' => 'Success', 'count' => $count, 'links' => $links));
        }
    }

    function generate_incomplete_client_links_file()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 
        $source = get_source_url_of_file(array(
            "file_name" => 'rise_incomplete_clients.csv',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        // $list_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.xlsx';

        $csv_file = $this->_read_csv($source);
        if ($csv_file) {
            $links = array();
            $current_key = 0;
            $count = 0;
            while (($line = fgetcsv($csv_file)) !== FALSE) {
                if ($this->skip_first_row && $current_key == 0) {
                    $current_key++;
                    continue;
                }
                if (array_key_exists(0, $line)) {
                    $va_internal_id = $line[0];

                    $client_options = array(
                        'va_internal_id' => trim($va_internal_id)
                    );

                    $client = $this->Clients_model->get_details($client_options)->getRow();

                    if ($client) {
                        $project_options = array(
                            'client_id' => $client->id
                        );
                        $projects = $this->Timeline_model->get_details($project_options)->getResult();
                        if (!(count($projects) > 0)) {
                            $client_id = $this->_va_client_id($line[0]);
                            // $links[] = array("https://visaalliance.agentcisapp.com/app/#/contacts/u/" . $client_id . "/notes-terms");
                            $links[] = array("https://visaalliance.agentcisapp.com/app/#/contacts/u/" . $client_id . "/activities");
                            $count++;
                        }
                    }
                }
                $current_key++;
            }

            \Shuchkin\SimpleXLSXGen::fromArray($links)->saveAs($_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'incomplete_client_links.xlsx');

            $this->_finish_parsing($csv_file);

            echo json_encode(array('success' => true, 'message' => 'Success', 'count' => $count, 'links' => $links));
        }
    }

    function generate_bulk_client_links_file()
    {
        $source = get_source_url_of_file(array(
            "file_name" => 'client_report7bea6a0b-f737-44d5-bf8c-15f5cab6184a669a05c27dd566.52659325.csv',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        // $list_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.xlsx';

        $csv_file = $this->_read_csv($source);
        if ($csv_file) {
            $links = array();
            $current_key = 0;
            while (($line = fgetcsv($csv_file)) !== FALSE) {
                if ($this->skip_first_row && $current_key == 0) {
                    $current_key++;
                    continue;
                }
                if (array_key_exists(13, $line)) {
                    $client_id = $this->_va_client_id($line[13]);
                    $links[] = array("https://visaalliance.agentcisapp.com/app/#/contacts/u/" . $client_id . "/notes-terms");
                }
                $current_key++;
            }

            \Shuchkin\SimpleXLSXGen::fromArray($links)->saveAs($_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'client_notes_links.xlsx');
            $this->_finish_parsing($csv_file);

            echo json_encode(array('success' => true, 'message' => 'Success', 'links' => $links));
        }
    }

    function generate_bulk_application_links_file()
    {
        $source = get_source_url_of_file(array(
            "file_name" => 'application_reporte49be1f2-253e-4a9b-8463-bbef4030da97669a05f7eed25.csv',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        // $list_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.xlsx';

        $csv_file = $this->_read_csv($source);
        if ($csv_file) {
            $links = array();
            $current_key = 0;
            while (($line = fgetcsv($csv_file)) !== FALSE) {
                if ($this->skip_first_row && $current_key == 0) {
                    $current_key++;
                    continue;
                }
                if (array_key_exists(38, $line)) {
                    $client_id = $this->_va_client_id($line[38]);
                    $application_id = $line[18];

                    $links[] = array("https://visaalliance.agentcisapp.com/app#/contacts/u/" . $client_id . "/applications/" . $application_id);
                }
                $current_key++;
            }

            \Shuchkin\SimpleXLSXGen::fromArray($links)->saveAs($_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'application_links.xlsx');
            $this->_finish_parsing($csv_file);

            echo json_encode(array('success' => true, 'message' => 'Success', 'links' => $links));
        }
    }

    function generate_bulk_invoice_links_file()
    {
        $source = get_source_url_of_file(array(
            "file_name" => 'invoice_reporteef0392b-2f1c-4e84-8dde-3a4f4d90ee55669a060c76143.csv',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        // $list_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.xlsx';

        $csv_file = $this->_read_csv($source);
        if ($csv_file) {
            $links = array();
            $current_key = 0;
            while (($line = fgetcsv($csv_file)) !== FALSE) {
                if ($this->skip_first_row && $current_key == 0) {
                    $current_key++;
                    continue;
                }
                if (array_key_exists(11, $line)) {
                    $invoice_id = $line[11];

                    $links[] = array("https://visaalliance.agentcisapp.com/invoice/" . $invoice_id . "/show");
                }
                $current_key++;
            }

            \Shuchkin\SimpleXLSXGen::fromArray($links)->saveAs($_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'invoice_links.xlsx');

            $this->_finish_parsing($csv_file);

            echo json_encode(array('success' => true, 'message' => 'Success', 'links' => $links));
        }
    }

    function populate_task_comments()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds
        ini_set('memory_limit', '-1');
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $succeeded = array();
        $processed_ids = 0;
        $found_ids = 0;
        $not_found_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_task_comments_1.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'applications.log';

        $file = $this->_read_json($source);

        if ($file) {
            $tasks = get_array_value($file, 'tasks');

            $total_items = count($tasks);

            if ($tasks) {
                foreach ($tasks as $_task) {
                    if ($current_key >= $offset && $current_key < $stop_at_offset) {
                        $task            = get_array_value($_task, 'task')[0];

                        if ($task) {
                            $title       = get_array_value($task, 'title');
                            $client_url  = get_array_value($task, 'client_url');
                            $logs        = get_array_value($task, 'logs');
                            $internal_id = ltrim($client_url, "https://visaalliance.agentcisapp.com/app#/contacts/u/");
                            $client_id   = $this->_get_client_id('VA' . $internal_id);
                            $task_info   = $this->Tasks_model->get_details(array('client_id' => $client_id, 'title' => $title))->getRow();

                            if (count($logs) && $client_id && $task_info) {
                                foreach ($logs as $log) {
                                    $name           = get_array_value($log, 'name');
                                    $caption        = get_array_value($log, 'caption');
                                    $content        = get_array_value($log, 'content');
                                    $date           = get_array_value($log, 'date');

                                    $action         = 'created';
                                    $log_type       = 'task';
                                    $log_type_id    = $task_info->id;
                                    $log_for        = 'general_task';
                                    $log_for_id     = 0;
                                    $log_for2       = '';
                                    $log_for_id2    = 0;
                                    $log_for3       = 'milestone';
                                    $log_for_id3    = 0;

                                    $user_id = $this->Users_model->get_by_full_name($name);
                                    if (!$user_id) {
                                        $user_id = 0;
                                    }

                                    $caption = trim(str_replace($name, '', $caption));

                                    if ($content) {
                                        $_label = 'content';

                                        if (str_contains($caption, 'commented')) {
                                            $_label = 'comment';
                                            $log_type = 'task_comment';
                                            $log_for = 'project';
                                            $log_for2 = 'task';
                                            $log_for_id2 = $task_info->id;
                                            $log_type_id = $this->_project_comment(array(
                                                'created_by'           => $user_id,
                                                'created_at'           => $this->_f2d($date, "d/m/Y, h:i A"),
                                                'description'          => $content,
                                                'project_id'           => 0,
                                                'comment_id'           => 0,
                                                'task_id'              => $task_info->id,
                                                'file_id'              => 0,
                                                'milestone_id'         => 0,
                                                'customer_feedback_id' => 0,
                                                'files'                => serialize(array()),
                                                'deleted'              => 0
                                            ));
                                            $content = null;
                                        } elseif (str_contains($caption, 'description')) {
                                            $_label = 'description';
                                            $action = 'updated';
                                        } elseif (str_contains($caption, 'title')) {
                                            $_label = 'title';
                                            $action = 'updated';
                                        }

                                        if ($action == 'updated') {
                                            $content = serialize(array($_label => array('from' => "", 'to' => $content)));
                                        } else {
                                            $content = null;
                                        }
                                    }

                                    $activity_data = array(
                                        'created_at' => $this->_f2d($date, "d/m/Y, h:i A"),
                                        'created_by' => $user_id,
                                        'action' => $action,
                                        'log_type' => $log_type,
                                        'log_type_title' => $caption ? $caption : $task_info->title,
                                        'log_type_id' => $log_type_id,
                                        'changes' => $content,
                                        'log_for' => $log_for,
                                        'log_for_id' => $log_for_id,
                                        'log_for2' => $log_for2,
                                        'log_for_id2' => $log_for_id2,
                                        'log_for3' => $log_for3,
                                        'log_for_id3' => $log_for_id3
                                    );

                                    $processed_ids++;
                                    $success = $this->Activity_logs_model->ci_save($activity_data);

                                    if ($success) {
                                        $succeeded[] = $success;
                                    }
                                }
                            } else {
                                $not_found_ids++;
                            }
                        }
                        $found_ids++;
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'processed_ids' => $processed_ids,
            'found_ids' => $found_ids,
            'not_found_ids' => $not_found_ids,
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded),
            'succeeded_queries' => implode(',', $succeeded),
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_invoice_income_sharing()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 

        $succeeded = array();
        $invoices = $this->Invoices_model->get_details()->getResult();

        foreach ($invoices as $invoice) {
            if ($invoice->project_id) {
                $income_sharing_partners = $this->Project_partners_model->get_details(array('project_id' => $invoice->project_id, 'only_partner_types' => 'subagent,referral'))->getResult();

                if (count($income_sharing_partners)) {
                    $invoice_meta_info = $this->Invoices_model->get_invoice_total_meta($invoice->id);
                    $net_total_income = $invoice_meta_info->net_total_income; // net income after discount deduction
                    foreach ($income_sharing_partners as $partner) {

                        $shared_income = calc_per($net_total_income, $partner->commission);

                        if ($shared_income > 0) {
                            $default_tax_info = $this->Taxes_model->get_details(array('is_default' => true))->getRow();

                            $tax = 0;
                            if ($partner->partner_type == 'subagent') { // GST tax will only be given to subagents
                                $tax = calc_per($shared_income, $default_tax_info->percentage);
                            }

                            $income_sharing_data = array(
                                'invoice_id' => $invoice->id,
                                'partner_id' => $partner->partner_id,
                                'commission' => $partner->commission,
                                'amount' => $shared_income,
                                'tax' => $tax,
                                'status' => 'unpaid',
                                'created_date' => get_current_utc_time()
                            );

                            $success = $this->Invoice_incomes_model->ci_save($income_sharing_data);

                            if ($success) {
                                $succeeded[] = $success;
                            }
                        }
                    }
                }
            }
        }

        echo json_encode(array('success' => true, 'message' => 'Success', 'no_of_succeeded_queries' => count($succeeded), 'succeeded' => $succeeded));
    }

    function populate_invoice_schedule_id()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 

        $succeeded = array();

        $payment_schedules = $this->Project_payment_schedule_setup_model->get_details()->getResult();

        foreach ($payment_schedules as $schedule) {
            if ($schedule->invoice_id) {
                $invoice_data = array(
                    'schedule_id' => $schedule->id
                );

                $success = $this->Invoices_model->ci_save($invoice_data, $schedule->id);

                if ($success) {
                    $succeeded[] = $success;
                }
            }
        }

        echo json_encode(
            array(
                'success' => true,
                'message' => 'success',
                'succeeded' => implode(',', $succeeded),
                'no_of_succeeded' => count($succeeded)
            )
        );
    }

    function populate_applic_partner_type()
    {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        // $offset = $this->offset;
        $succeeded = array();

        $subagents = $this->Clients_model->get_details(array('partner_type' => 'subagent'))->getResult();

        if ($subagents) {
            foreach ($subagents as $subagent) {
                $applic_partners = $this->Project_partners_model->get_details(array('partner_id' => $subagent->id))->getResult();

                if ($applic_partners) {
                    foreach ($applic_partners as $partner) {
                        $partner_data = array(
                            'partner_type' => $subagent->partner_type
                        );

                        $success = $this->Project_partners_model->ci_save($partner_data, $partner->id);

                        if ($success) {
                            $succeeded[] = $success;
                        }
                    }
                }
            }
        }

        echo json_encode(array('status' => true, 'message' => 'success', 'succeeded_queries' => count($succeeded)));
    }

    function populate_default_clients_source()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 

        $succeeded = array();

        $list = array(
            'Search Engine (e.g., Google, Bing)',
            'Facebook',
            'Instagram',
            'X (Twitter)',
            'LinkedIn',
            'Pinterest',
            'Snapchat',
            'TikTok',
            'Reddit',
            'WhatsApp',
            'Quora',
            'WeChat',
            'Telegram',
            'Referral from a Friend or Family Member',
            'Online Advertisement',
            'Printed Advertisement (Magazine, Newspaper, Brochure)',
            'Event or Conference',
            'Educational Institution',
            'Employment Agency',
            'News Article or Blog',
        );
        foreach ($list as $source) {
            $success = $this->_source($source);
            if ($success) {
                $succeeded[] = $success;
            }
        }

        echo json_encode(
            array(
                'status' => true,
                'message' => 'success',
                'succeeded' => count($succeeded)
            )
        );
    }
    function populate_clients_source()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 

        $clients = $this->Clients_model->get_details(array('only_account_types' => '1,2,4'))->getResult();

        $succeeded = array();
        foreach ($clients as $client) {
            if ($client->source) {
                $client_data = array(
                    'source' => $this->_source($client->source)
                );

                $success = $this->Clients_model->ci_save($client_data, $client->id);

                if ($success) {
                    $succeeded[] = $success;
                }
            }
        }

        echo json_encode(
            array(
                'status' => true,
                'message' => 'success',
                'succeeded' => count($succeeded)
            )
        );
    }

    function populate_client_uid_for_od_directories()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 

        $clients = $this->Clients_model->get_details(array('only_account_types' => '4', 'leads_only' => false))->getResult();
        $succeeded = array();
        foreach ($clients as $client) {
            $created_date = new \DateTime($client->created_date);
            $name = $client->first_name . ' ' . $client->last_name;
            if ((int)$client->account_type == 4) {
                if($client->company_name == '')
                {
                   $name = $client->first_name . ' ' . $client->last_name;
                }
                else
                {
                   $name = $client->company_name;
                }
                
            }

            $client_data = array(
                'unique_id' => _gen_va_uid($name, date_format($created_date, 'dmY')),
                'company_name' => $name
            );

            $success = $this->Clients_model->ci_save($client_data, $client->id);

            if ($success) {
                $succeeded[] = $success;
            }
        }


        echo json_encode(
            array(
                'status' => true,
                'message' => 'success',
                'succeeded' => count($succeeded)
            )
        );
    }

    function populate_invoice_types()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 

        // $invoice_types = array(
        //     37 => 'general',
        //     38 => 'general',
        //     39 => 'gross_claim',
        //     40 => 'net_claim'
        // );

        $invoices = $this->Invoices_model->get_details(array('labels' => 40))->getResult();

        $succeeded = array();
        foreach ($invoices as $invoice) {
            $invoice_data = array(
                'invoice_type' => 'net_claim'
            );

            $success = $this->Invoices_model->ci_save($invoice_data, $invoice->id);

            if ($success) {
                $succeeded[] = $success;
            }
        }


        echo json_encode(
            array(
                'status' => true,
                'message' => 'success',
                'succeeded' => count($succeeded)
            )
        );
    }

    function populate_invoice_items()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $item_succeeded = array();
        $note_succeeded = array();
        $payment_succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $not_found = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_invoices.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'invoices.log';

        $file = $this->_read_json($source);

        if ($file) {
            $invoices_data = get_array_value($file, 'invoices');

            if ($invoices_data) {
                $total_items = count($invoices_data);

                foreach ($invoices_data as $key => $invoice_data) {

                    if ($current_key >= $offset && $current_key < $stop_at_offset) {
                        $va_invoice_id = ltrim(get_array_value($invoice_data, 'invoice_id'), '#');
                        $invoice_lines = get_array_value($invoice_data, 'lines')[0];
                        $invoice_note = get_array_value($invoice_data, 'note');
                        $invoice_payments = get_array_value($invoice_data, 'payments');

                        $invoice = $this->_trigger('Invoices_model')->get_details(array('va_invoice_id' => $va_invoice_id))->getRow();

                        if ($invoice_lines && count($invoice_lines) && $invoice) {
                            $first_line = get_array_value($invoice_lines, 'line_item')[0];
                            $first_line_entries_count = count(get_array_value($first_line, 'entries'));
                            if ($first_line_entries_count) {
                                $lines = get_array_value($invoice_lines, 'line_item');
                                for ($x = 0; $x < $first_line_entries_count; $x++) {
                                    $title = $this->_invoice_item_val($lines, $x, array('description'));
                                    $income_type = $this->_invoice_item_val($lines, $x, array('income type'));
                                    $tax = $this->_invoice_item_val($lines, $x, array('tax (%)'));
                                    $amount = $this->_invoice_item_val($lines, $x, array('amount', 'total fee'));
                                    $commission = $this->_invoice_item_val($lines, $x, array('commission percent'));

                                    if ($invoice->invoice_type == 'general') {
                                        if ($income_type && strtolower($income_type) == 'payables') {
                                            $income_type = 'payable';
                                        } elseif ($income_type && strtolower($income_type) == 'income') {
                                            $income_type = 'income';
                                        } else {
                                            $income_type = '';
                                        }
                                    } elseif ($invoice->invoice_type == 'gross_claim') {
                                        $income_type = 'income';
                                    } elseif ($invoice->invoice_type == 'net_claim') {
                                        $income_type = 'payable';
                                    } else {
                                        continue; // skip this iteration. Do not save this invoice item as the invoice type is unknown
                                    }

                                    $taxable = 0;
                                    if ($tax) {
                                        $tax = (int)explode("%", $tax)[0];
                                        if ($tax > 0) {
                                            $taxable = 1;
                                        }
                                    }

                                    $amount = $amount ? $this->_price($amount) : 0;
                                    $commission = $commission ? $this->_price($commission) : 0;

                                    $invoice_item_data = array(
                                        'title' => $title,
                                        'description' => '',
                                        'quantity' => 1,
                                        'unit_type' => '',
                                        'income_type' => $income_type,
                                        'commission' => $commission,
                                        'rate' => $amount,
                                        'total' => $amount,
                                        'sort' => $x,
                                        'invoice_id' => $invoice->id,
                                        'item_id' => 0,
                                        'schedule_id' => 0,
                                        'schedule_fee_id' => 0,
                                        'taxable' => $taxable
                                    );

                                    $success = $this->Invoice_items_model->ci_save($invoice_item_data);

                                    if ($success) {
                                        $item_succeeded[] = $success;
                                    }
                                }
                            }

                            if ($invoice_note) {
                                $invoice_data = array('note' => trim($invoice_note));
                                $success = $this->Invoices_model->ci_save($invoice_data, $invoice->id);

                                if ($success) {
                                    $note_succeeded[] = $success;
                                }
                            }

                            if ($invoice_payments && count($invoice_payments)) {
                                foreach ($invoice_payments as $payment) {
                                    $date = get_array_value($payment, 'date');
                                    $amount = get_array_value($payment, 'amount');
                                    $type = get_array_value($payment, 'payment_type');

                                    if (strtolower($date) != 'there is no payment done for this invoice') {
                                        $payment_date = get_current_utc_time();
                                        if ($date) {
                                            $payment_date = $this->_f2d($date, "d/m/Y");
                                        }

                                        $payment_amount = 0;
                                        if ($amount) {
                                            $payment_amount = $this->_price($amount);
                                        }

                                        $payment_method_id = 0;
                                        if ($type) {
                                            $payment_method_id = $this->_payment_method($type);
                                        }

                                        $payment_data = array(
                                            'amount' => $payment_amount,
                                            'payment_date' => $payment_date,
                                            'payment_method_id' => $payment_method_id,
                                            'note' => '(Extracted as is from AgentCIS)',
                                            'invoice_id' => $invoice->id,
                                            'created_by' => 0,
                                            'created_at' => $payment_date
                                        );

                                        $success = $this->_trigger('Invoice_payments_model')->ci_save($payment_data);

                                        if ($success) {
                                            $payment_succeeded[] = $success;
                                        }
                                    }
                                }
                            }
                            $processed_ids++;
                        } else {
                            $not_found++;
                        }
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'processed_ids' => $processed_ids,
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_item_queries' => count($item_succeeded),
            'no_of_payment_queries' => count($payment_succeeded),
            'no_of_note_queries' => count($note_succeeded),
            'not_found' => $not_found,
            'item_succeeded_queries' => implode(',', $item_succeeded),
            'payment_succeeded_queries' => implode(',', $payment_succeeded),
            'note_succeeded_queries' => implode(',', $note_succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_partners()
    {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $not_found = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_partners.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $file = $this->_read_json($source);

        if ($file) {
            $partners_data = get_array_value($file, 'partners');

            if ($partners_data) {
                $total_items = count($partners_data);

                foreach ($partners_data as $key => $partner) {

                    if ($current_key >= $offset && $current_key < $stop_at_offset) {
                        $email = get_array_value($partner, 'email');
                        $notes = get_array_value($partner, 'note');

                        $partner = $this->_trigger('Clients_model')->get_details(array('email' => $email, 'account_type' => '3'))->getRow();

                        if ($notes && count($notes) && $partner) {
                            foreach ($notes as $note) {
                                $title = get_array_value($note, 'title');
                                $content = get_array_value($note, 'content');
                                $date = get_array_value($note, 'date');

                                $note_data = array(
                                    'created_by' => 0,
                                    'created_at' => $this->_f2d($date, "d/m/Y"),
                                    'title' => $title,
                                    'description' => $content ? $content : "",
                                    'client_id' => $partner->id,
                                    'is_public' => 1
                                );

                                $success = $this->Notes_model->ci_save($note_data);

                                if ($success) {
                                    $succeeded[] = $success;
                                }
                            }
                            $processed_ids++;
                        } else {
                            $not_found++;
                        }
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded),
            'not_found' => $not_found
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_client_location_subagents()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $succeeded = array();
        $succeeded_locations = array();
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_agents.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $options = array();
        $locations = $this->Location_model->get_details($options)->getResult();

        foreach ($locations as $location) {
            $clients_options = array(
                'only_account_types' => '1,2,4',
                'location_id' => $location->id
            );
            $subagent_options = array(
                'location_id' => $location->id,
                'partner_type' => 'subagent'
            );

            $clients = $this->Clients_model->get_details($clients_options)->getResult();
            $subagent = $this->Clients_model->get_details($subagent_options)->getRow();
            $succeeded_locations[$location->title] = 0;

            if ($subagent) {
                foreach ($clients as $client) {
                    $client_data = array(
                        'partner_id' => $location->id == 2 ? $subagent->id : 4443
                    );

                    $success = $this->Clients_model->ci_save($client_data, $client->id);

                    if ($success) {
                        $succeeded_locations[$location->title]++;
                    }
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded),
            'succeeded_locations' => json_encode($succeeded_locations)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_archived_clients()
    {
        ini_set('max_execution_time', 600); //execute maximum 600 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_archived_clients.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $file = $this->_read_json($source);

        if ($file) {
            $clients = get_array_value($file, 'clients');
            if ($clients) {
                $total_items = count($clients);
                foreach ($clients as $client) {
                    $full_name = get_array_value($client, 'name');
                    $url = get_array_value($client, 'url');
                    $email = get_array_value($client, 'email');
                    $label = get_array_value($client, 'label');
                    $city = get_array_value($client, 'city');
                    $assignee = get_array_value($client, 'assignee');
                    $created_date = get_array_value($client, 'created_date');

                    $_assignee = null;
                    if (!$this->_isEmpty($assignee)) {
                        $_assignee = $this->Users_model->get_by_full_name($assignee, true);
                    }

                    $_city = '';
                    $_country = '';
                    if (!$this->_isEmpty($city)) {
                        $address = explode('\n', $city);
                        if (count($address) > 1) {
                            $_city = get_array_value($address, 0);
                            $_country = get_array_value($address, 1);
                        } elseif (count($address) == 1) {
                            $_city = get_array_value($address, 0);
                        }
                    }

                    $va_internal_id = ltrim($url, 'https://visaalliance.agentcisapp.com/app/#/contacts/u/');
                    $va_internal_id = rtrim($va_internal_id, '/activities');

                    $client_data = array_merge(
                        $this->_name($full_name),
                        array(
                            'unique_id' => $this->_uniqueId($full_name),
                            'va_internal_id' => $this->_isEmpty($va_internal_id) ? '' : 'VA' . $va_internal_id,
                            'location_id' => $_assignee && $_assignee->location_id ? $_assignee->location_id : 2,
                            'created_by_location_id' => $_assignee && $_assignee->location_id ? $_assignee->location_id : 2,
                            'type' => 'person',
                            'account_type' => 1,
                            'email' => $email,
                            'city' => $_city,
                            'country' => $_country,
                            'created_by' => $this->login_user->id,
                            'created_date' => $this->_f2d($created_date, 'd/m/Y'),
                            'assignee' => $_assignee && $_assignee->id ? $_assignee->id : 0,
                            'deleted' => 0,
                            'labels' => $this->_isEmpty($label) ? '' : $this->_label($label, 'client')
                        )
                    );

                    $success = $this->Clients_model->ci_save($client_data);

                    $processed_ids++;
                    if ($success) {
                        $succeeded[] = $success;
                    }
                }
            }
        }


        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_location_subagents()
    {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_agents.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $options = array();
        $locations = $this->Location_model->get_details($options)->getResult();

        foreach ($locations as $location) {
            $partner_data = array(
                'unique_id' => $this->_uniqueId($location->title . ' ' . 'Branch Office'),
                'location_id' => $location->id,
                'created_by_location_id' => 0,
                'partner_type' => 'subagent',
                'type' => 'person',
                'account_type' => 3,
                'first_name' => $location->title,
                'last_name' => 'Branch Office',
                'email' => 'admin.' . $this->_mutate(str_replace(' ', '.', str_replace(array("(", ")"), '', $location->title)), 'strtolower') . '@visaalliance.com',
                'created_by' => $this->login_user->id,
                'created_date' => $this->_gen('d'),
                'deleted' => 0,
                'labels' => $this->_label('Sub Agent', 'client')
            );

            $success = $this->Clients_model->ci_save($partner_data);

            if ($success) {
                $succeeded[] = $success;
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_agents()
    {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_agents.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $file = $this->_read_json($source);

        if ($file) {
            $partners_data = get_array_value($file, 'partners');

            if ($partners_data) {
                $total_items = count($partners_data);

                foreach ($partners_data as $key => $partner) {

                    if ($current_key >= $offset && $current_key < $stop_at_offset) {

                        $agent_name = get_array_value($partner, 'title');
                        $phone = get_array_value($partner, 'phone');
                        $email = get_array_value($partner, 'email');
                        $address = get_array_value($partner, 'address');
                        // $type = get_array_value($partner, 'type');
                        $commission = get_array_value($partner, 'commission');
                        $location = get_array_value($partner, 'location');
                        $notes = get_array_value($partner, 'notes');
                        $applications = get_array_value($partner, 'applications');

                        // $application_options = array(
                        //     'temp_applic_id' => $applic_id
                        // );
                        // $application = $this->Projects_model->get_details($application_options)->getRow();
                        if ($agent_name && $email) {
                            $agent_data = array_merge($this->_name($agent_name), $this->_phone($phone));
                            $agent_data['labels'] = $this->_label("Sub Agent", 'client');
                            $agent_data['account_type'] = 3;
                            $agent_data['type'] = 'person';
                            $agent_data['partner_type'] = 'subagent';
                            $agent_data['unique_id'] = $this->_uniqueId("VA" . date('-y-'));
                            $agent_data['created_by'] = $this->login_user->id;
                            $agent_data['created_date'] = get_current_utc_time();
                            // $agent_data['phone'] = $this->_isEmpty($phone) ? "" : $phone;
                            $agent_data['address'] = $this->_isEmpty($address) ? "" : $address;
                            $agent_data['com_percentage'] = (float)trim($this->_mutate($commission, 'str_replace', array('p' => '%',  'r' => '')));
                            $agent_data['location_id'] = get_ltm_opl_id(true);

                            $locations = $this->make_locations_dropdown('', false);

                            $_locations = explode(',', $location);
                            if (count($_locations) == 2) {
                                $location = trim($_locations[1]);
                            } else {
                                $location = trim($_locations[0]);
                            }
                            foreach ($locations as $location_id => $location_title) {
                                if (strtolower($location) == strtolower($location_title) || str_contains(strtolower($location_title), strtolower($location))) {
                                    $agent_data['location_id'] = $location_id;
                                    break;
                                }
                            }
                            $processed_ids++;

                            $success = $this->Clients_model->ci_save($agent_data);

                            if ($success) {
                                $succeeded[] = $success;

                                if ($notes) {
                                    $notes = get_array_value($notes[0], 'note');

                                    if ($notes) {
                                        foreach ($notes as $note) {
                                            $note = get_array_value($note, 'note');

                                            if ($note && count($note)) {
                                                $title = get_array_value($note[0], 'title');
                                                $content = get_array_value($note[0], 'content');
                                                $date = get_array_value($note[0], 'date');

                                                $note_data = array(
                                                    'created_by' => $this->login_user->id,
                                                    'created_at' => $this->_f2d($date, "d/m/Y"),
                                                    'title' => $title,
                                                    'description' => $content ? $content : "",
                                                    'client_id' => $success,
                                                    'is_public' => 1
                                                );

                                                $this->Notes_model->ci_save($note_data);
                                            }
                                        }
                                    }
                                }

                                if ($applications) {
                                    foreach ($applications as $link) {
                                        $temp_applic_id = get_array_value(explode('/', ltrim(get_array_value($link, 'url'), 'https://visaalliance.agentcisapp.com/app#/contacts/u/')), 2);

                                        $application_options = array(
                                            'temp_applic_id' => $temp_applic_id
                                        );
                                        $application = $this->_trigger('Projects_model')->get_details($application_options)->getRow();

                                        if ($application) {
                                            $project_member_data = array(
                                                'project_id' => $application->id,
                                                'partner_id' => $success,
                                                'full_name' => $agent_name,
                                                'commission' => $agent_data['com_percentage'],
                                                'created_date' => get_current_utc_time()
                                            );

                                            $this->_trigger('Project_partners_model')->ci_save($project_member_data);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_application_data()
    {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $internal_ids = array();
        $client_ids = array();
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'application_data.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'applications.log';

        $file = $this->_read_json($source);

        if ($file) {
            $links_data = get_array_value($file, 'links');

            if ($links_data) {
                $total_items = count($links_data);

                foreach ($links_data as $key => $link) {

                    if ($current_key >= $offset && $current_key < $stop_at_offset) {
                        $applic_id = get_array_value($link, 'application');
                        if ($applic_id) {

                            $stages = get_array_value($link, 'stages');
                            // $notes = get_array_value($link, 'notes');
                            // $payment_schedules = get_array_value($link, 'payment_schedules');

                            $application_options = array(
                                'temp_applic_id' => $applic_id
                            );
                            $application = $this->Projects_model->get_details($application_options)->getRow();

                            if ($stages && $application) {
                                $project_id = $application->id;
                                $_stages = get_array_value($stages[0], 'activities');
                                if ($_stages) {
                                    foreach ($_stages as $stage_key => $stage) {

                                        $sort = $stage_key + 1;
                                        $milestone_options = array(
                                            'project_id' => $project_id,
                                            'sort' => $sort
                                        );

                                        $milestone = $this->Milestones_model->get_details($milestone_options)->getRow();
                                        $activities = get_array_value($stage, 'activity');

                                        if ($activities && $milestone) {
                                            foreach ($activities as $activity) {
                                                $title = get_array_value($activity, 'title');
                                                $date = get_array_value($activity, 'date');

                                                if ($title && $date) {
                                                    $activity_data = array(
                                                        'created_at' => $this->_f2d($date, "d D, M Y h:i A"),
                                                        'created_by' => 0,
                                                        'action' => 'created',
                                                        'log_type' => 'activity',
                                                        'log_type_title' => $title,
                                                        'log_type_id' => 0,
                                                        'log_for' => 'project',
                                                        'log_for_id' => $project_id,
                                                        'log_for2' => '',
                                                        'log_for_id2' => 0,
                                                        'log_for3' => 'milestone',
                                                        'log_for_id3' => $milestone->id
                                                    );

                                                    $processed_ids++;
                                                    $success = $this->Activity_logs_model->ci_save($activity_data);

                                                    if ($success) {
                                                        $succeeded[] = $success;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'internal_ids' => implode(',', $internal_ids),
            'client_ids' => implode(',', $client_ids),
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_application_notes()
    {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $internal_ids = array();
        $client_ids = array();
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_applic_5.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'applications.log';

        $file = $this->_read_json($source);

        if ($file) {
            $links_data = get_array_value($file, 'links');

            if ($links_data) {
                $total_items = count($links_data);

                foreach ($links_data as $key => $link) {

                    if ($current_key >= $offset && $current_key < $stop_at_offset) {
                        $applic_id = get_array_value($link, 'application');
                        if ($applic_id) {

                            // $stages = get_array_value($link, 'stages');
                            $notes = get_array_value($link, 'notes');
                            // $payment_schedules = get_array_value($link, 'payment_schedules');

                            $application_options = array(
                                'temp_applic_id' => $applic_id
                            );
                            $application = $this->Projects_model->get_details($application_options)->getRow();

                            if ($notes && $application) {
                                $project_id = $application->id;
                                foreach ($notes as $note_key => $_note) {
                                    $note = get_array_value($_note, 'note');

                                    if ($note) {
                                        $title = get_array_value($note[0], 'title');
                                        $content = get_array_value($note[0], 'content');
                                        $date = get_array_value($note[0], 'date');

                                        if ($title && $content && $date) {

                                            $note_data = array(
                                                'created_by' => 0,
                                                'created_at' => $this->_f2d($date, "d/m/Y"),
                                                'title' => $title,
                                                'description' => $content,
                                                'is_public' => 1,
                                                'project_id' => $project_id
                                            );

                                            $processed_ids++;
                                            $success = $this->Notes_model->ci_save($note_data);

                                            if ($success) {
                                                $succeeded[] = $success;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'internal_ids' => implode(',', $internal_ids),
            'client_ids' => implode(',', $client_ids),
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_application_payment_schedule()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $internal_ids = array();
        $client_ids = array();
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_missing_payment_schedule.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'applications.log';

        $file = $this->_read_json($source);

        if ($file) {
            $links_data = get_array_value($file, 'links');

            if ($links_data) {
                $total_items = count($links_data);

                foreach ($links_data as $key => $link) {

                    if ($current_key >= $offset && $current_key < $stop_at_offset) {
                        $applic_id = get_array_value($link, 'application');
                        if ($applic_id) {

                            $payment_schedules = get_array_value($link, 'payment_schedules');

                            $application_options = array(
                                'temp_applic_id' => $applic_id
                            );
                            $application = $this->Projects_model->get_details($application_options)->getRow();

                            if ($payment_schedules && $application) {
                                $project_id = $application->id;
                                if (str_contains($source, 'missing')) {
                                    $old_payment_schedules = $this->Project_payment_schedule_setup_model->get_details(array('project_id' => $project_id))->getResult();

                                    foreach ($old_payment_schedules as $old_schedule) {
                                        $this->Project_payment_schedule_setup_model->delete($old_schedule->id);
                                    }
                                }
                                $_payment_schedule = get_array_value($payment_schedules, 0);
                                if ($_payment_schedule) {
                                    $schedules = get_array_value($_payment_schedule, 'schedule');

                                    if ($schedules) {
                                        foreach ($schedules as $schedule_key => $schedule) {

                                            if ($schedule) {
                                                $title = get_array_value($schedule, 'title');
                                                $date = get_array_value($schedule, 'date');
                                                $is_claimable = get_array_value($schedule, 'is_claimable');
                                                $fees = get_array_value($schedule, 'fees');
                                                // $total_fee = get_array_value($schedule, 'total_fee'); // we'll count the total_fee manually
                                                $discount = get_array_value($schedule, 'discount');
                                                // $invoicing_date = get_array_value($schedule, 'invoicing_date');
                                                $status = get_array_value($schedule, 'status');
                                                $action = get_array_value($schedule, 'action');
                                                $action_url = get_array_value($schedule, 'action_url');

                                                if ($title && $fees && $status) {

                                                    $installment_date = $this->_f2d($date, "d/m/Y");

                                                    $installment_fees = array();
                                                    $installment_total_amount = 0;

                                                    foreach ($fees as $key => $fee) {
                                                        $installment_fee = array();

                                                        $installment_fee['key'] = $key;
                                                        $installment_fee['fee_type'] = get_array_value($fee, 'fee_type');
                                                        $amount = $this->_price(get_array_value($fee, 'fee'));
                                                        $installment_total_amount += $amount;
                                                        $installment_fee['amount'] = $amount;

                                                        $installment_fees[] = $installment_fee;
                                                    }

                                                    $invoice_status = array(
                                                        'scheduled' => 0,
                                                        'invoiced' => 1,
                                                        'discontinued' => 2,
                                                        'invoice removed' => 3,
                                                        'pending' => 4,
                                                        'expired' => 5
                                                    );

                                                    $selected_status = $invoice_status[strtolower($status)];

                                                    if (!(is_numeric($selected_status) && $selected_status > -1)) {
                                                        $selected_status = 0;
                                                    }

                                                    $discount = $this->_price($discount);
                                                    $invoice_id = 0;

                                                    if ($action && $action_url && strtolower($action) == 'view invoice') {
                                                        $va_invoice_id = ltrim($action_url, 'https://visaalliance.agentcisapp.com/invoice/');
                                                        $va_invoice_id = rtrim($va_invoice_id, '/show');
                                                        $va_invoice_id = trim($va_invoice_id);

                                                        if ($va_invoice_id) {
                                                            $invoice_options = array(
                                                                'va_invoice_id' => $va_invoice_id
                                                            );

                                                            $invoice = $this->Invoices_model->get_details($invoice_options)->getRow();

                                                            if ($invoice) {
                                                                // var_dump($action_url, $invoice);
                                                                // exit();
                                                                $invoice_id = $invoice->id;
                                                            }
                                                        }
                                                    }

                                                    $schedule_data = array(
                                                        'project_id' => $project_id,
                                                        'client_id' => $application->client_id,
                                                        'invoice_id' => $invoice_id,
                                                        'installment_name' => $title,
                                                        'invoice_date' => $installment_date,
                                                        'due_date' => date_create($installment_date)->modify("+7 days")->format('Y-m-d'),
                                                        'created_date' => $installment_date,
                                                        'fees' => serialize($installment_fees),
                                                        'rows_count' => count($installment_fees),
                                                        'discount' => $discount,
                                                        'total_fee' => $installment_total_amount,
                                                        'net_fee' => $installment_total_amount - $discount,
                                                        'is_claimable' => $is_claimable == "Claimable" ? 1 : 0,
                                                        'is_auto_created' => 1,
                                                        'sort' => $schedule_key,
                                                        'status' => $selected_status,
                                                    );

                                                    $processed_ids++;
                                                    $success = $this->Project_payment_schedule_setup_model->ci_save($schedule_data);

                                                    if ($success) {
                                                        $succeeded[] = $success;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'internal_ids' => implode(',', $internal_ids),
            'client_ids' => implode(',', $client_ids),
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_application_active_state()
    {
        $offset = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'application_reporte49be1f2-253e-4a9b-8463-bbef4030da97669a05f7eed25.csv',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));
        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $file = $this->_read_csv($source);
        $data = array();
        $stop_at_offset = $offset + 500;
        $current_key = 0;
        $succeeded = array();
        while (($line = fgetcsv($file)) !== FALSE) {
            if ($this->skip_first_row && $current_key == 0) {
                $current_key++;
                continue;
            }
            if ($current_key >= $offset && $current_key < $stop_at_offset) {
                $item = array();
                $item['key'] = $current_key;
                $item['row_data'] = serialize($line);

                if (!$this->_isEmpty($line[15])) {
                    $stage_title = strtolower(trim($line[15]));
                    $temp_applic_id = strtolower(trim($line[18]));


                    $application_options = array(
                        'temp_applic_id' => $temp_applic_id
                    );
                    $application = $this->_trigger("Projects_model")->get_details($application_options)->getRow();

                    if ($application) {
                        $milestone_options = array('project_id' => $application->id);
                        $milestones = $this->_trigger("Milestones_model")->get_details($milestone_options)->getResult();
                        if ($milestones) {

                            $next_milestone_id = 0;
                            $next_milestone = null;
                            foreach ($milestones as $milestone) {
                                if (strtolower($milestone->title) == strtolower($stage_title)) {
                                    $next_milestone_id = $milestone->id;
                                    $next_milestone = $milestone;
                                    break;
                                }
                            }

                            if ($next_milestone_id && $next_milestone) {
                                foreach ($milestones as $milestone) {
                                    $data = array();
                                    if ((int)$milestone->id !== (int)$next_milestone_id) {
                                        if ((int)$milestone->sort > (int)$next_milestone->sort) {
                                            $data = array('is_current' => 0);
                                        } elseif ((int)$milestone->sort < (int)$next_milestone->sort) {
                                            $data = array('is_current' => 2);
                                        }
                                    } else {
                                        $data = array('is_current' => 1);
                                    }
                                    $success = $this->Milestones_model->ci_save($data, $milestone->id);
                                    if ($success) {
                                        $succeeded[] = $success;
                                    }
                                }
                            }
                            // $success = $this->_trigger("Clients_model")->ci_save($data, $client->id);

                        }
                    }
                }

                $data[] = $item;
            }
            $current_key++;
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $offset + 500,
            'succeeded_queries' => implode(',', $succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_client_subagents()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 
        $offset = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'client_report7bea6a0b-f737-44d5-bf8c-15f5cab6184a669a05c27dd566.52659325.csv',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));
        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $file = $this->_read_csv($source);
        $stop_at_offset = $offset + 500;
        $total_rows = $this->_file_total_rows($source);
        $current_key = 0;
        $succeeded = array();
        while (($line = fgetcsv($file)) !== FALSE) {
            if ($this->skip_first_row && $current_key == 0) {
                $current_key++;
                continue;
            }
            if ($current_key >= $offset && $current_key < $stop_at_offset) {
                if (!$this->_isEmpty($line[13])) {
                    $partner_full_name = strtolower(trim($line[27]));
                    $internal_id = strtolower(trim($line[13]));

                    $client_options = array('va_internal_id' => $internal_id);
                    $client = $this->_trigger("Clients_model")->get_details($client_options)->getRow();
                    if ($client && $client->account_type != 3) {
                        $client_partner = $this->_trigger("Clients_model")->get_by_full_name($partner_full_name, '3', false);
                        if ($client_partner) {
                            $data = array("partner_id" => $client_partner);
                            $success = $this->_trigger("Clients_model")->ci_save($data, $client->id);
                            if ($success) {
                                $succeeded[] = $success;
                            }
                        }
                    }
                }
            }
            $current_key++;
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $offset + $this->batch_size,
            'total_rows' => $total_rows,
            'no_of_succeeded_queries' => count($succeeded),
            'succeeded_queries' => implode(',', $succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_visa()
    {
        $offset = 4000;
        $source = get_source_url_of_file(array(
            "file_name" => 'client_report7bea6a0b-f737-44d5-bf8c-15f5cab6184a669a05c27dd566.52659325.csv',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));
        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $file = $this->_read_csv($source);
        $data = array();
        $stop_at_offset = $offset + 500;
        $current_key = 0;
        $succeeded = array();
        while (($line = fgetcsv($file)) !== FALSE) {
            if ($this->skip_first_row && $current_key == 0) {
                $current_key++;
                continue;
            }
            if ($current_key >= $offset && $current_key < $stop_at_offset) {
                $item = array();
                $item['key'] = $current_key;
                $item['row_data'] = serialize($line);

                if (!$this->_isEmpty($line[13]) && !$this->_isEmpty($line[12])) {
                    $full_name = strtolower(trim($line[0]));
                    $visa_type = strtolower(trim($line[12]));
                    $internal_id = strtolower(trim($line[13]));

                    $client_options = array('va_internal_id' => $internal_id);
                    $client = $this->_trigger("Clients_model")->get_details($client_options)->getRow();
                    if ($client && $client->account_type != 3) {
                        $data = array("visa_type" => $visa_type);

                        if (str_contains($full_name, 'pty ltd')) {
                            $data['account_type'] = 4;
                            $data['type'] = 'organization';
                        } else {
                            $data['type'] = 'person';
                            if (str_contains($visa_type, '500') || str_contains($visa_type, '485')) {
                                $data['account_type'] = 1;
                            } else {
                                $data['account_type'] = 2;
                            }
                        }

                        $success = $this->_trigger("Clients_model")->ci_save($data, $client->id);

                        if ($success) {
                            $succeeded[] = $success;
                        }
                    }
                }

                $data[] = $item;
            }
            $current_key++;
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $offset + $this->batch_size,
            'succeeded_queries' => implode(',', $succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_client_notes()
    {
        ini_set('max_execution_time', 600); //execute maximum 600 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $internal_ids = array();
        $client_ids = array();
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'run_results_clients_missing_notes_2.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $file = $this->_read_json($source);

        if ($file) {
            $links_data = get_array_value($file, 'links');

            if ($links_data) {
                $total_items = count($links_data);

                foreach ($links_data as $key => $link) {

                    if ($current_key >= $offset && $current_key < $stop_at_offset) {
                        $notes = get_array_value($link, 'notes');
                        if ($notes) {

                            foreach ($notes as $note) {
                                $title = get_array_value($note, 'title');
                                $content = get_array_value($note, 'content');
                                $date = get_array_value($note, 'date');
                                $client_link = get_array_value($note, 'link');

                                if ($client_link && $title && $date) {
                                    $internal_id = rtrim($client_link, '/notes-terms');
                                    $internal_id = ltrim($internal_id, 'https://visaalliance.agentcisapp.com/app/#/contacts/u/');

                                    if ($internal_id) {
                                        $client_id = $this->_get_client_id('VA' . $internal_id);

                                        if ($client_id) {
                                            $note_data = array(
                                                'created_by' => 0,
                                                'created_at' => $this->_f2d($date, "d/m/Y"),
                                                'title' => $title,
                                                'description' => $content ?? "",
                                                'is_public' => 1,
                                                'client_id' => $client_id
                                            );

                                            $processed_ids++;
                                            $success = $this->Notes_model->ci_save($note_data);

                                            if ($success) {
                                                $succeeded[] = $success;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'internal_ids' => implode(',', $internal_ids),
            'client_ids' => implode(',', $client_ids),
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function populate_activities()
    {
        ini_set('max_execution_time', 500); //execute maximum 500 seconds 
        $offset = $this->offset;
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        $internal_ids = array();
        $client_ids = array();
        $succeeded = array();
        $processed_ids = 0;
        $total_items = 0;
        $source = get_source_url_of_file(array(
            "file_name" => 'archived_clients_links.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $data_source = get_source_url_of_file(array(
            "file_name" => 'run_results_archived_clients_activities.json',
            "file_id" => 0,
            "service_type" => '',
        ), get_general_file_path("import", $this->login_user->id));

        $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';

        $file = $this->_read_json($source);
        $data = $this->_read_json($data_source);
        if ($file && $data) {
            $links = get_array_value($file, 'activities');
            $links_data = get_array_value($data, 'links');

            $total_items = count($links);

            if ($links && $links_data) {
                foreach ($links as $key => $link) {
                    if ($current_key >= $offset && $current_key < $stop_at_offset) {
                        if (array_key_exists($key, $links_data)) {
                            $internal_id = rtrim($link, '/activities');
                            $internal_id = ltrim($internal_id, 'https://visaalliance.agentcisapp.com/app/#/contacts/u/');
                            if ($internal_id) {
                                $internal_ids[] = $internal_id;
                                $client_id = $this->_get_client_id('VA' . $internal_id);
                                if ($client_id) {
                                    $client_ids[] = $client_id;
                                    $activities = get_array_value($links_data, $key);
                                    if ($activities) {
                                        $activities = get_array_value($activities, 'activities');
                                        if ($activities) {
                                            foreach ($activities as $page) {
                                                $_activities = get_array_value($page, 'activities');
                                                if ($_activities) {
                                                    foreach ($_activities as $activity) {
                                                        $title = get_array_value($activity, 'title');
                                                        $content = get_array_value($activity, 'content');

                                                        $can_save = FALSE;

                                                        if ($title) {
                                                            $timeline_data = array(
                                                                'client_id' => $client_id,
                                                                'title' => $title,
                                                                'created_by' => 0
                                                            );

                                                            if ($content) {
                                                                if (str_contains(strtolower($title), 'changed assignee') && count($content) == 8) {
                                                                    $from_name = get_array_value(get_array_value($content, 1), 'name');
                                                                    $from_email = get_array_value(get_array_value($content, 2), 'name');
                                                                    $to_name = get_array_value(get_array_value($content, 6), 'name');
                                                                    $to_email = get_array_value(get_array_value($content, 7), 'name');
                                                                    $date = get_array_value(get_array_value($content, 0), 'date');

                                                                    $caption = timeline_label('from') . $from_name . ' (' . $from_email . ') ' . timeline_label('to') . $to_name . ' (' . $to_email . ')';

                                                                    $timeline_data['caption'] = $caption;
                                                                    $timeline_data['created_at'] = $this->_f2d($date, "d M Y, h:i A");

                                                                    $can_save = TRUE;
                                                                } elseif (count($content) == 1) {
                                                                    $caption = get_array_value(get_array_value($content, 0), 'name');
                                                                    $date = get_array_value(get_array_value($content, 0), 'date');

                                                                    $caption = trim(preg_replace('/\s\s+/', ' ', $caption));

                                                                    $timeline_data['caption'] = $caption;
                                                                    $timeline_data['created_at'] = $this->_f2d($date, "d M Y, h:i A");

                                                                    $can_save = TRUE;
                                                                }
                                                            }

                                                            $processed_ids++;
                                                            if ($can_save) {
                                                                $success = $this->_trigger('Timeline_model')->ci_save($timeline_data);

                                                                if ($success) {
                                                                    $succeeded[] = $success;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $current_key++;
                }
            }
        }

        $log_data = array(
            'source' => $source,
            'log_source' => $log_source,
            'offset' => $offset,
            'next_offset' => $stop_at_offset,
            'succeeded_queries' => implode(',', $succeeded),
            'processed_ids' => $processed_ids,
            'internal_ids' => implode(',', $internal_ids),
            'client_ids' => implode(',', $client_ids),
            'total_items' => $total_items,
            'remaining_items' => $total_items - $stop_at_offset,
            'no_of_succeeded_queries' => count($succeeded)
        );

        $this->_log($log_source, $log_data);

        echo json_encode($log_data);
    }

    function modal_form()
    {
        $list = array(
            '' => '-',
            'clients' => 'Clients',
            'contracts' => 'Contracts',
            'applications' => 'Applications',
            'application_data' => 'Application Data',
            'application_stages' => 'Application Stages',
            'invoices' => 'Invoices',
            'calendar' => 'Calendar',
            'partners' => 'Partners',
            'product' => 'Products',
            'tasks' => 'Tasks',
        );

        ksort($list);
        $view_data['trigger_dropdown'] = $list;

        return $this->template->view('students/import/modal_form', $view_data);
    }

    function run()
    {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        try {
            $trigger = $this->request->getPost('trigger');
            $offset = (int)$this->request->getPost('offset');

            $succeeded = array();
            $failed = array();

            $source = '';
            $log_source = '';
            $parseables = array();
            $label_context = '';
            $t = '';
            $default_data = array();
            $append_data = array();
            $replace_empty = array();
            $eos = false;
            $eos_c = false;

            switch ($trigger) {
                case 'clients':
                    $source = get_source_url_of_file(array(
                        "file_name" => 'client_report7bea6a0b-f737-44d5-bf8c-15f5cab6184a669a05c27dd566.52659325.csv',
                        "file_id" => 0,
                        "service_type" => '',
                    ), get_general_file_path("import", $this->login_user->id));
                    $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'clients.log';
                    $parseables = $this->_client_parseables();
                    $label_context = 'client';
                    $t = 'Clients_model';
                    $default_data = array('unique_id' => 'u');
                    $append_data = array();
                    $replace_empty = array('created_date' => 'd');
                    $eos = array(
                        array(
                            't' => 'Users_model',
                            'd' => array('password' => 'p', 'created_at' => 'd'),
                            'a' => array('note' => "Primary Contact (Auto Created)", "is_primary_contact" => 1),
                            'c' => array('first_name', 'last_name', 'email', 'phone', 'job_title', 'location_id'),
                            'config' => array('mb' => array('email')),
                            'fk' => 'client_id',
                            'uos' => array(
                                array(
                                    't' => 'Clients_model',
                                    'fk' => 'primary_contact_id'
                                )
                            ),
                        ),
                        array(
                            't' => 'Timeline_model',
                            'd' => array(),
                            'a' => array('title' => "Visa Alliance (Bot)", "caption" => "Account created during data migration"),
                            'c' => array(),
                            'config' => array(),
                            'fk' => 'client_id',
                            'uos' => array(),
                        )
                    );
                    break;
                case 'partners':
                    $source = get_source_url_of_file(array(
                        "file_name" => 'partners.csv',
                        "file_id" => 0,
                        "service_type" => '',
                    ), get_general_file_path("import", $this->login_user->id));
                    $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'partners.log';
                    $parseables = $this->_partner_parseables();
                    $label_context = 'client';
                    $t = 'Clients_model';
                    $default_data = array('unique_id' => 'u', 'created_date' => 'd');
                    $append_data = array('partner_type' => 'institute', 'account_type' => 3);
                    $replace_empty = array();
                    $eos = array(
                        array(
                            't' => 'Users_model',
                            'd' => array('password' => 'p', 'created_at' => 'd'),
                            'a' => array('note' => "Primary Contact (Auto Created)", "is_primary_contact" => 1),
                            'c' => array('first_name', 'last_name', 'email', 'phone', 'job_title', 'location_id'),
                            'config' => array('mb' => array('email')),
                            'fk' => 'client_id',
                            'uos' => array(
                                array(
                                    't' => 'Clients_model',
                                    'fk' => 'primary_contact_id'
                                )
                            ),
                        ),
                        array(
                            't' => 'Timeline_model',
                            'd' => array(),
                            'a' => array('title' => "Visa Alliance (Bot)", "caption" => "Account created during data migration"),
                            'c' => array(),
                            'config' => array(),
                            'fk' => 'client_id',
                            'uos' => array(),
                        )
                    );

                    break;
                case 'product':
                    // $this->batch_size = 500;
                    $source = get_source_url_of_file(array(
                        "file_name" => 'products.csv',
                        "file_id" => 0,
                        "service_type" => '',
                    ), get_general_file_path("import", $this->login_user->id));
                    $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'products.log';
                    $parseables = $this->_temp_product_parseables();
                    $label_context = 'application';
                    $t = 'Temp_Product_model';
                    $default_data = array('created_date' => 'd');
                    $append_data = array();
                    $replace_empty = array();
                    $eos = array();

                    break;
                case 'applications':
                    $source = get_source_url_of_file(array(
                        "file_name" => 'application_reporte49be1f2-253e-4a9b-8463-bbef4030da97669a05f7eed25.csv',
                        "file_id" => 0,
                        "service_type" => '',
                    ), get_general_file_path("import", $this->login_user->id));
                    $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'applications.log';
                    $parseables = $this->_application_parseables();
                    $label_context = 'project';
                    $t = 'Projects_model';
                    $default_data = array();
                    $append_data = array('row_data' => "__ROW__");
                    $replace_empty = array('created_date' => 'd', 'created_by' => $this->login_user->id);
                    $eos = array();
                    $eos_c = array(
                        array(
                            "fn" => "_project_partners",
                            'ck' => 'partner_ids'
                        ),
                        array(
                            "fn" => "_project_member",
                            'ck' => 'created_by'
                        ),
                        array(
                            "fn" => "_project_workflow",
                            'ck' => 'workflow_id'
                        ),
                        array(
                            "fn" => "_project_doc_check_list",
                            'ck' => 'doc_check_list_id'
                        )
                    );
                    break;
                case 'tasks':
                    $source = get_source_url_of_file(array(
                        "file_name" => 'task_reporte7fe9d9e-4951-427c-a387-82d4ea5f2c8c669a0658427415.53145600.csv',
                        "file_id" => 0,
                        "service_type" => '',
                    ), get_general_file_path("import", $this->login_user->id));
                    $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'tasks.log';
                    $parseables = $this->_task_parseables();
                    $label_context = 'task';
                    $t = 'Tasks_model';
                    $default_data = array();
                    $append_data = array('sort' => "__SORT__");
                    $replace_empty = array('created_date' => 'd');
                    $eos = array();
                    $eos_c = array();
                    break;
                case 'invoices':
                    $source = get_source_url_of_file(array(
                        "file_name" => 'invoice_reporteef0392b-2f1c-4e84-8dde-3a4f4d90ee55669a060c76143.csv',
                        "file_id" => 0,
                        "service_type" => '',
                    ), get_general_file_path("import", $this->login_user->id));
                    $log_source = $_SERVER['DOCUMENT_ROOT'] . '/' . get_logs_file_path("import") . 'invoices.log';
                    $parseables = $this->_invoice_parseables();
                    $label_context = 'invoice';
                    $t = 'Invoices_model';
                    $default_data = array();
                    $append_data = array('type' => 'invoice', 'discount_amount_type' => 'fixed_amount', 'tax_id' => 1);
                    $replace_empty = array();
                    $eos = array();
                    $eos_c = array();
                    break;
                case 'application_data':
                    # code...
                    break;
                case 'contracts':
                    # code...
                    break;
                case 'calendar':
                    # code...
                    break;
            }

            $csv_file = $this->_read_csv($source);

            if ($csv_file) {

                $bulk_data = $this->_pair_rows($csv_file, $parseables, $offset, $label_context);

                foreach ($bulk_data as $data) {
                    $key = get_array_value($data, 'key');
                    $row_data = get_array_value($data, 'row_data');
                    unset($data['key']);
                    unset($data['row_data']);

                    foreach ($default_data as $default_date_key => $default_data_line) {
                        if ($default_data_line == 'u') {
                            $data[$default_date_key] = $this->_uniqueId("VA" . date('-y-'));
                        }
                        if ($default_data_line == 'd') {
                            $data[$default_date_key] = $this->_gen('d');
                        }
                    }

                    foreach ($append_data as  $append_data_line_key => $append_data_line_val) {
                        if (str_contains($append_data_line_val, '__RAND6__')) {
                            $data[$append_data_line_key] = $this->_mutate($append_data_line_val, 'str_replace', array('p' => '__RAND6__',  'r' => $this->_gen('r6')));
                        } elseif (str_contains($append_data_line_val, '__ROW__')) {
                            $data[$append_data_line_key] = $row_data;
                        } elseif (str_contains($append_data_line_val, '__SORT__')) {
                            $data[$append_data_line_key] = $key + 1;
                        } else {
                            $data[$append_data_line_key] = $append_data_line_val;
                        }
                    }

                    foreach ($replace_empty as $append_data_key => $replace_empty_line) {
                        if ($replace_empty_line == 'd' && !get_array_value($data, $append_data_key)) {
                            $data[$append_data_key] = $this->_gen('d');
                        }
                    }

                    $success = $this->_trigger($t)->ci_save($data);

                    if ($success) {
                        if ($eos) {
                            foreach ($eos as $eos_one) {
                                $this->_extend($success, $data, $eos_one);
                            }
                        }
                        if ($eos_c) {
                            foreach ($eos_c as $eos_c_one) {
                                $fn = get_array_value($eos_c_one, 'fn');
                                $ck = get_array_value($eos_c_one, 'ck');

                                $foreign_id = $success;
                                $context_id = get_array_value($data, $ck);

                                $this->$fn($foreign_id, $context_id);
                            }
                        }
                        $succeeded[] = $key;
                    } else {
                        $failed[] = $key;
                    }
                }

                $this->_finish_parsing($csv_file);
            }

            $no_of_succeeded_queries = count($succeeded);
            $no_of_failed_queries = count($failed);
            $no_of_parsed_items = $no_of_succeeded_queries + $no_of_failed_queries;
            $total_no_of_parsed_items = $offset + $no_of_parsed_items;
            $total_rows = $this->_file_total_rows($source);
            $remaining_rows = $total_rows - $total_no_of_parsed_items;

            $log_data = array(
                'source' => $source,
                'log_source' => $log_source,
                'trigger' => $trigger,
                'offset' => $offset,
                'next_offset' => $offset + $this->batch_size,
                'no_of_parsed_items' => $no_of_parsed_items,
                'total_no_of_parsed_items' => $total_no_of_parsed_items,
                'no_of_remaining_items' => $remaining_rows,
                'succeeded_queries' => implode(',', $succeeded),
                'failed_queries' => implode(',', $failed),
                'no_of_succeeded_queries' => $no_of_succeeded_queries,
                'no_of_failed_queries' => $no_of_failed_queries
            );

            $this->_log($log_source, $log_data);

            echo json_encode(array("success" => true, 'message' => app_lang('record_saved'), 'data' => $log_data));
        } catch (Exception $e) {
            echo json_encode(array("success" => false, 'message' => $e->getMessage() . ' on line: ' . $e->getLine()));
        }
    }

    private function _project_comment($data = array())
    {
        return $this->Project_comments_model->ci_save($data);
    }

    private function _project_partners($project_id = 0, $partner_id = 0)
    {
        if ($project_id && $partner_id) {
            $project_partner_data = array(
                'project_id' => $project_id,
                'partner_id' => $partner_id,
                'full_name'  => $this->get_client_full_name($partner_id),
                'commission' => "0",
                'created_date' => get_current_utc_time()
            );

            $this->Project_partners_model->ci_save($project_partner_data);
        }
    }

    private function _project_member($project_id = 0, $member_id = 0)
    {
        if ($project_id && $member_id) {
            $data = array(
                "project_id" => $project_id,
                "user_id" => $member_id,
                "is_leader" => 1
            );
            $this->Project_members_model->save_member($data);
        }
    }

    private function _project_workflow($project_id = 0, $workflow_id = 0)
    {
        if ($workflow_id) {
            $options = array('project_id' => $project_id);
            $milestones = $this->Milestones_model->get_details($options)->getResult();
            if (count($milestones)) {
                foreach ($milestones as $key => $milestone) {
                    $this->Milestones_model->delete($milestone->id);
                }
            }
            $workflow = $this->General_files_model->get_details(array("id" => $workflow_id))->getRow();
            if ($workflow) {
                $source_url = get_source_url_of_file(make_array_of_file($workflow), get_general_file_path("staff", $workflow->user_id));
                $content = file_get_contents($source_url);

                if ($content) {
                    $content = json_decode($content, true);

                    if ($content) {
                        $is_current = 1;
                        $sort = 1;
                        foreach ($content as $key => $value) {
                            $data = array(
                                // "due_date" => date_create()->modify("+3 month")->format("Y-m-d"),
                                "project_id" => $project_id,
                                "is_current" => $is_current,
                                'sort' => $sort,
                            );
                            $is_current = 0;
                            $sort++;

                            $doc_check_list = get_array_value($value, 'doc_check_list');
                            if ($doc_check_list) {
                                $data['is_doc_check_list'] = $doc_check_list;
                            } else {
                                $data['is_doc_check_list'] = 0;
                            }

                            $title = get_array_value($value, 'title');
                            if ($title) {
                                $data['title'] = $title;
                            } else {
                                $data['title'] = "N/A";
                            }

                            $description = get_array_value($value, 'description');
                            if ($description) {
                                $data['description'] = $description;
                            } else {
                                $data['description'] = "N/A";
                            }

                            $this->Milestones_model->ci_save($data);
                        }
                    }
                }
            }
        }
    }

    private function _project_doc_check_list($project_id = 0, $doc_check_list_id = 45)
    {
        $success = false;
        if ($doc_check_list_id && $project_id) {
            $options = array('project_id' => $project_id);
            $doc_check_list_data = $this->Project_doc_check_list_model->get_details($options)->getResult();
            if (count($doc_check_list_data)) {
                foreach ($doc_check_list_data as $key => $doc_check) {
                    $this->Project_doc_check_list_model->delete($doc_check->id);
                }
            }
            $doc_checklist_options = array('id' => $doc_check_list_id);
            $doc_check_list = $this->General_files_model->get_details($doc_checklist_options)->getRow();
            if ($doc_check_list) {
                $source_url = get_source_url_of_file(make_array_of_file($doc_check_list), get_general_file_path("staff", $doc_check_list->user_id));
                $content = file_get_contents($source_url);

                if ($content) {
                    $content = json_decode($content, true);

                    if ($content) {
                        foreach ($content as $key => $value) {
                            $data = array(
                                "project_id" => $project_id,
                                "milestone_id" => 0,
                                "file_id" => 0,
                                'created_date' => get_current_utc_time(),
                            );

                            $label = get_array_value($value, 'label');
                            if ($label) {
                                $data['label'] = $label;
                            } else {
                                $data['label'] = "N/A";
                            }

                            $required = get_array_value($value, 'required');
                            if ($required) {
                                $data['is_required'] = $required;
                            } else {
                                $data['is_required'] = false;
                            }

                            $this->Project_doc_check_list_model->ci_save($data);
                        }
                        $success = true;
                    }
                }
            }
        }
        return $success;
    }

    private function _extend($fk_id, $default_data, $config)
    {
        $t = get_array_value($config, 't'); // trigger model
        $d = get_array_value($config, 'd'); // default values
        $a = get_array_value($config, 'a'); // append this data
        $c = get_array_value($config, 'c'); // copy these values from the default data
        $fk = get_array_value($config, 'fk'); // Foreign key to link with the original trigger
        $uk = get_array_value($config, 'uk'); // Use this Foreign key to update the trigger
        $uos = get_array_value($config, 'uos'); // trigger another model on success

        $data = array();

        if ($fk) {
            $data[$fk] = $fk_id;
        }

        if ($c) {
            foreach ($c as $key => $val) {
                $data[$val] = get_array_value($default_data, $val);
            }
        }

        if ($a) {
            foreach ($a as $key => $val) {
                $data[$key] = $val;
            }
        }

        if ($d) {
            foreach ($d as $key => $val) {
                $data[$key] = $this->_gen($val);
            }
        }

        $data = array_map(function ($v) {
            return is_null($v) ? '' : $v;
        }, $data);

        $success = false;
        if ($uk) {
            $success = $this->_trigger($t)->ci_save($data, $uk);
        } else {
            $success = $this->_trigger($t)->ci_save($data);
        }

        if ($success) {
            if ($uos) {
                foreach ($uos as $t_uos) {
                    $t_uos['uk'] = $fk_id;
                    $this->_extend($success, $data, $t_uos);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    private function _pair_rows($file, $parseables, $offset, $label_context)
    {
        $data = array();
        $stop_at_offset = $offset + $this->batch_size;
        $current_key = 0;
        while (($line = fgetcsv($file)) !== FALSE) {
            if ($this->skip_first_row && $current_key == 0) {
                $current_key++;
                continue;
            }
            if ($current_key >= $offset && $current_key < $stop_at_offset) {
                $item = array();
                $item['key'] = $current_key;
                $item['row_data'] = serialize($line);

                foreach ($parseables as $parseable) {
                    $operation = get_array_value($parseable, 'operation');
                    $output = get_array_value($parseable, 'output');
                    $config = get_array_value($parseable, 'config');
                    $key = get_array_value($parseable, 'key');
                    list($operation_id, $parameter) = explode(':', $operation);

                    switch ($operation_id) {
                        case 'parse':
                            if ($this->_isEmpty($line[$key])) {
                                $item = $this->_empty($item, $output);
                            } else {
                                $item = $this->_parse($line[$key], $item, $parameter, $output, $config, $label_context);
                            }

                            break;

                        case 'match':
                            if ($this->_isEmpty($line[$key])) {
                                $item = $this->_empty($item, $output);
                            } else {
                                $item = $this->_match($line[$key], $item, $parameter, $output, $config);
                            }
                            break;

                        case 'find':
                            if ($this->_isEmpty($line[$key])) {
                                $item = $this->_empty($item, $output);
                            } else {
                                $item = $this->_find($line[$key], $item, $parameter, $output, $config);
                            }
                            break;

                        default:
                            if ($this->_isEmpty($line[$key])) {
                                $item = $this->_empty($item, $output);
                            } else {
                                $item = $this->_default($line[$key], $item, $parameter, $output, $config);
                            }
                            break;
                    }
                }

                $data[] = $item;
            }
            $current_key++;
        }

        return $data;
    }

    private function _read_csv($source = '')
    {
        $file = fopen($source, 'r');
        if ($file) {
            return $file;
        }
        return false;
    }

    private function _read_json($source = '')
    {
        if (!ini_get('allow_url_fopen')) {
            ini_set('allow_url_fopen', 1);
        }
        $file = file_get_contents($source);
        if ($file) {
            $data = json_decode($file, true);
            return $data;
        }
        return false;
    }

    private function _finish_parsing($file)
    {
        fclose($file);
    }

    private function _file_total_rows($source)
    {
        $fp = file($source, FILE_SKIP_EMPTY_LINES);
        return count($fp) - 1;
    }

    private function _default($v = '', $data = array(), $parameter = 't', $output = '', $config = null)
    {
        $mb = false;
        $mb_c = false;
        $ma = false;

        $v = trim($v);

        if ($config) {
            $mb = get_array_value($config, 'mb');
            $mb_c = get_array_value($config, 'mb_c');
            $ma = get_array_value($config, 'ma');
        }

        if ($mb) { // mutate before
            $mb_c = get_array_value($config, 'mb_c');
            if (is_array($mb) || is_object($mb)) {
                foreach ($mb as $o_mb) {
                    $v = $this->_mutate($v, $o_mb, $mb_c);
                }
            } else {
                $v = $this->_mutate($v, $mb, $mb_c);
            }
        }

        return $this->_output($v, $data, $output, $ma);
    }

    private function _parse($v = '', $data = array(), $parameter = 't', $output = '', $config = null, $label_context = '')
    {
        $mb = false;
        $ma = false;
        $mb_c = null;

        $v = trim($v);

        if ($config) {
            $mb = get_array_value($config, 'mb');
            $mb_c = get_array_value($config, 'mb_c');
            $ma = get_array_value($config, 'ma');
        }

        if ($mb) { // mutate before
            if (is_array($mb) || is_object($mb)) {
                foreach ($mb as $o_mb) {
                    $v = $this->_mutate($v, $o_mb, $mb_c);
                }
            } else {
                $v = $this->_mutate($v, $mb, $mb_c);
            }
        }

        switch ($parameter) {
            case 'n':

                $v = $this->_name($v, $output);

                break;
            case 'd':

                $separator = get_array_value($config, 's');
                $day = get_array_value($config, 'd');
                $month = get_array_value($config, 'm');
                $year = get_array_value($config, 'y');

                $v = explode($separator, $v);

                $date = new \DateTime();
                $date->setDate($v[$year], $v[$month], $v[$day]);

                $v = $date->format('Y-m-d');

                break;
            case 'p':

                $v = $this->_phone($v, $output);

                break;
            case 'l':

                $tags = explode(',', $v);
                $labels  = array();
                foreach ($tags as $tag) {
                    $label_id = $this->_label(trim($tag), $label_context);
                    if ($label_id) {
                        $labels[] = $label_id;
                    }
                }

                $v = implode(',', $labels);

                break;
        }

        return $this->_output($v, $data, $output, $ma);
    }

    private function _match($v = '', $data = array(), $parameter = 't', $output = '', $config = array())
    {
        $mb = false;
        $ma = false;
        $mb_c = false;

        if ($config) {
            $mb = get_array_value($config, 'mb');
            $mb_c = get_array_value($config, 'mb_c');
            $ma = get_array_value($config, 'ma');
        }

        if ($mb) { // mutate before
            if (is_array($mb) || is_object($mb)) {
                foreach ($mb as $o_mb) {
                    $v = $this->_mutate($v, $o_mb, $mb_c);
                }
            } else {
                $v = $this->_mutate($v, $mb, $mb_c);
            }
        }

        foreach ($config as $condition) {
            $on = get_array_value($condition, 'on');
            $set = get_array_value($condition, 'set');

            $match = array();
            if ($this->_mutate($on, 'strtolower') == $this->_mutate(trim($v), 'strtolower')) {
                foreach ($set as $output_key => $output_line) {
                    $match[$output_key] = $output_line;
                }
                return $this->_output($match, $data, $output, $ma);
            }
        }

        return $data;
    }

    private function _find($v = '', $data = array(), $parameter = 't', $output = '', $config = null)
    {
        $mb = false;
        $mb_c = false;
        $ma = false;

        $v = $this->_mutate($v, 'trim');

        if ($config) {
            $mb = get_array_value($config, 'mb');
            $mb_c = get_array_value($config, 'mb_c');
            $ma = get_array_value($config, 'ma');
        }

        if ($mb) { // mutate before
            if (is_array($mb) || is_object($mb)) {
                foreach ($mb as $o_mb) {
                    $v = $this->_mutate($v, $o_mb, $mb_c);
                }
            } else {
                $v = $this->_mutate($v, $mb, $mb_c);
            }
        }

        $find_model = get_array_value($config, 't'); // trigger modal
        $cbm = get_array_value($config, 'cbm'); // combine columns before matching
        $cbm_s = get_array_value($config, 'cbm_s'); // separator
        $c = get_array_value($config, 'c'); // column to find

        if (count($cbm) > 1 && $parameter != 'c') {
            $v = $this->_name($v);
        } elseif (count($cbm) == 1) {
            $v = array($cbm[0] => $v);
        }

        if ($parameter == 'c') {
            $v = explode(',', $v);
            $ids = array();
            foreach ($v as $o) {
                $a = array();
                if ($cbm_s) {
                    $a = array_merge($this->_name($o), $cbm_s);
                }

                $item = $this->$find_model->get_details($a)->getRow();
                if ($item) {
                    $item = json_decode(json_encode($item), true);
                    $ids[] = get_array_value($item, $c);
                }
            }
            return $this->_output(implode(',', $ids), $data, $output, $ma);
        } else {
            if ($cbm_s) {
                $v = array_merge($v, $cbm_s);
            }
            $item = $this->$find_model->get_details($v)->getRow();

            if ($item) {
                $item = json_decode(json_encode($item), true);
                return $this->_output(get_array_value($item, $c), $data, $output, $ma);
            }
        }


        $dv = 0;
        switch ($c) {
            case 'id':
                $dv = $this->login_user->id;
                break;

            case 'location_id':
                $dv = get_ltm_opl_id();
                break;
        }

        return $this->_output($dv, $data, $output, $ma); // saved by admin
    }

    private function _mutate($val = '', $id = '', $c = array())
    {
        switch ($id) {
            case 'trim':
                $val = trim($val);
                break;
            case 'ucfirst':
                $val = ucfirst($val);
                break;
            case 'lcfirst':
                $val = lcfirst($val);
                break;
            case 'ucwords':
                $val = ucwords($val);
                break;
            case 'strtolower':
                $val = strtolower($val);
                break;
            case 'str_replace':
                $p = get_array_value($c, 'p');
                $r = get_array_value($c, 'r');
                $val = str_replace($p, $r, $val);
                break;
            case 'substr':
                $o = get_array_value($c, 'o');
                $l = get_array_value($c, 'l');
                $val = substr($val, $o, $l);
                break;
        }

        return $val;
    }

    private function _output($v, $data, $output, $ma)
    {
        if ($v) {
            if (is_array($output) || is_object($output)) {
                foreach ($output as $output_line) {
                    if ($ma) { // mutate after
                        $data[$output_line] = $this->_mutate($v[$output_line], $ma);
                    } else {
                        $data[$output_line] = $v[$output_line];
                    }
                }
            } elseif (is_string($output)) {
                if ($ma) { // mutate after
                    $data[$output] = $this->_mutate($v, $ma);
                } else {
                    $data[$output] = $v;
                }
            }

            return $data;
        } else {
            return $this->_empty($data, $output);
        }
    }

    private function _log($source, $data)
    {
        $count = 0;
        $txt = '';
        foreach ($data as $key => $log) {
            $title = $this->_mutate(str_replace("_", " ", $key), 'ucfirst');
            if ($count == 0) {
                $txt .= "\n\n$title: $log\n";
            } else {
                $txt .= "$title: $log\n";
            }
            $count++;
        }
        if ($source && file_exists($source)) {
            file_put_contents($source, $txt, FILE_APPEND);
        }
    }

    private function _empty($data, $output)
    {
        if (is_array($output) || is_object($output)) {
            foreach ($output as $output_line) {
                $data[$output_line] = '';
            }
        } elseif (is_string($output)) {
            $data[$output] = '';
        }

        return $data;
    }

    private function _trigger($t)
    {
        return $this->$t;
    }

    private function _invoice_item_val($data = array(), $index = 0, $prop_type = array())
    {
        $val = '';
        foreach ($data as $item) {
            $title = strtolower(trim(get_array_value($item, 'title')));
            if (in_array($title, $prop_type)) {
                $entries = get_array_value($item, 'entries');
                $val = get_array_value(get_array_value($entries, $index), 'title');
                break;
            }
        }

        return $val;
    }

    private function _name($str = "", $output = array('first_name', 'last_name'))
    {
        $lastSpace = strrpos($str, ' ');
        $str1 = trim(substr($str, 0, $lastSpace));
        $str2 = trim(substr($str, $lastSpace));
        return array($output[0] => $str1, $output[1] => $str2);
    }

    private function _phone($str = "", $output = array('phone_code', 'phone'))
    {
        $str = str_replace(array(' ', '+', '-', '(', ')'), '', $str);

        $str1 = substr((string)(int)$str, 0, 2);
        $str2 = substr((string)(int)$str, 2);

        return array($output[0] => $str1, $output[1] => $str2);
    }

    private function _price($str = "")
    {
        return unformat_currency($str);
    }

    private function _payment_method($v = "", $available_on_invoice = 1)
    {
        $options = array('title' => $this->_mutate(trim($v), 'ucwords'));
        $payment_method = $this->_trigger('Payment_methods_model')->get_details($options)->getRow();
        if ($payment_method) {
            return $payment_method->id;
        } else {
            $data = array(
                'title' => $this->_mutate(trim($v), 'ucwords'),
                'type' => 'custom',
                'Description' => "Payment Method",
                'online_payable' => 0,
                'available_on_invoice' => $available_on_invoice,
                'minimum_payment_amount' => 0,
                'settings' => '',
                'sort' => 0
            );
            $success = $this->_trigger('Payment_methods_model')->ci_save($data);
            return $success;
        }
    }

    private function _label($v, $context)
    {
        return context_label($v, $context);
    }

    private function _source($v)
    {
        return client_source($v);
    }

    private function _get_client_id($internal_id = "")
    {
        $options = array('va_internal_id' => $internal_id);
        $client = $this->_trigger('Clients_model')->get_details($options)->getRow();

        if ($client) {
            return $client->id;
        } else {
            return FALSE;
        }
    }

    private function _va_client_id($str = "")
    {
        return substr(trim($str), 2);
    }

    private function _f2d($str = "", $f = "")
    {
        $date = date_create_from_format($f, $str);
        if ($date) {
            return date_format($date, "Y-m-d h:m:s");
        } else {
            return get_current_utc_time();
        }
    }

    private function _uniqueId($prefix = '')
    {
        return _gen_va_uid($prefix); // LENGTH = strlen(prefix) + 13
    }

    private function _gen($k)
    {
        $v = '';

        switch ($k) {
            case 'p':
                $v = password_hash(bin2hex(openssl_random_pseudo_bytes(4)), PASSWORD_DEFAULT);
                break;
            case 'c':
            case 'd':
                $v = get_current_utc_time();
                break;
            case 'r6':
                $v = rand(100000, 999999);
                break;
            case 'r12':
                $v = rand(100000000000, 999999999999);
                break;
        }

        return $v;
    }

    private function _color()
    {
        $colors = array(
            '#DC143C',
            '#B22222',
            '#FF6347',
            '#FFD700',
            '#BDB76B',
            '#DA70D6',
            '#663399',
            '#4B0082',
            '#7B68EE',
            '#32CD32',
            '#2E8B57',
            '#808000',
            '#20B2AA',
            '#40E0D0',
            '#1E90FF',
            '#000080',
            '#BC8F8F',
            '#CD853F',
            '#A9A9A9',
            '#2F4F4F',
            '#8A9A5B',
            '#4F7942',
            '#C9CC3F',
            '#40826D',
            '#FFBF00',
            '#673147',
            '#953553',
            '#7F00FF',
            '#722F37',
            '#8B8000',
            '#93C572',
        );

        $key = array_rand($colors);
        return $colors[$key];
    }

    private function _isEmpty($v)
    {
        return in_array($v, $this->avoid);
    }

    private function _client_parseables()
    {
        $parseables = array(
            array('key' => 0, 'operation' => 'parse:n', 'output' => array('first_name', 'last_name'), 'config' => array('ma' => 'ucwords')),
            array('key' => 1, 'operation' => 'default:t', 'output' => 'email', 'config' => array('mb' => 'strtolower')),
            array('key' => 2, 'operation' => 'parse:d', 'output' => 'date_of_birth', 'config' => array('s' => '/', 'd' => 0, 'm' => 1, 'y' => 2)),
            array('key' => 4, 'operation' => 'parse:p', 'output' => array('phone_code', 'phone')),
            array('key' => 6, 'operation' => 'default:t', 'output' => 'address'),
            array('key' => 7, 'operation' => 'default:t', 'output' => 'state'),
            array('key' => 8, 'operation' => 'default:t', 'output' => 'city'),
            array('key' => 9, 'operation' => 'default:t', 'output' => 'country', 'config' => array('ma' => 'ucfirst')),
            array('key' => 10, 'operation' => 'default:t', 'output' => 'country_of_passport', 'config' => array('ma' => 'ucfirst')),
            array('key' => 11, 'operation' => 'parse:d', 'output' => 'visa_expiry', 'config' => array('s' => '/', 'd' => 0, 'm' => 1, 'y' => 2)),
            array('key' => 13, 'operation' => 'default:t', 'output' => 'va_internal_id'),
            array('key' => 15, 'operation' => 'match:t', 'output' => array('is_lead', 'lead_status_id'), 'config' => array(array('on' => 'Client', 'set' => array('is_lead' => 0, 'lead_status_id' => 0)), array('on' => 'Prospect', 'set' => array('is_lead' => 1, 'lead_status_id' => 2)))),
            array('key' => 17, 'operation' => 'parse:d', 'output' => 'created_date', 'config' => array('s' => '/', 'd' => 0, 'm' => 1, 'y' => 2)),
            array('key' => 22, 'operation' => 'find:t', 'output' => 'location_id', 'config' => array('mb' => 'strtolower', 'c' => 'location_id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff'))),
            array('key' => 22, 'operation' => 'find:t', 'output' => 'owner_id', 'config' => array('mb' => 'strtolower', 'c' => 'id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff'))),
            array('key' => 22, 'operation' => 'find:t', 'output' => 'created_by', 'config' => array('mb' => 'strtolower', 'c' => 'id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff'))),
            array('key' => 24, 'operation' => 'find:t', 'output' => 'assignee', 'config' => array('mb' => 'strtolower', 'c' => 'id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff'))),
            array('key' => 26, 'operation' => 'find:t', 'output' => 'assignee_manager', 'config' => array('mb' => 'strtolower', 'c' => 'id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff'))),
            array('key' => 27, 'operation' => 'default:t', 'output' => 'sub_agent_name'),
            array('key' => 28, 'operation' => 'default:t', 'output' => 'source'),
            array('key' => 29, 'operation' => 'parse:l', 'output' => 'labels'),
            array('key' => 30, 'operation' => 'default:t', 'output' => 'secondary_email'),
            array('key' => 31, 'operation' => 'default:t', 'output' => 'marriage_status'),
            array('key' => 32, 'operation' => 'default:t', 'output' => 'preferred_intake'),
            array('key' => 35, 'operation' => 'default:t', 'output' => 'sec_applicant_email'),
            array('key' => 36, 'operation' => 'default:t', 'output' => 'sec_applicant_last_name'),
            array('key' => 37, 'operation' => 'default:t', 'output' => 'sec_applicant_first_name'),
            array('key' => 38, 'operation' => 'default:t', 'output' => 'main_applin_pass_exp_date'),
            array('key' => 39, 'operation' => 'default:t', 'output' => 'sec_applicant_phone'),
            array('key' => 40, 'operation' => 'default:t', 'output' => 'sec_applicant_dob'),
            array('key' => 41, 'operation' => 'default:t', 'output' => 'sec_applicant_passport_no')
        );

        return $parseables;
    }

    private function _partner_parseables()
    {
        $parseables = array(
            array('key' => 0, 'operation' => 'parse:n', 'output' => array("first_name", 'last_name')),
            array('key' => 1, 'operation' => 'default:t', 'output' => "email", 'config' => array('ma' => 'strtolower')),
            array('key' => 2, 'operation' => 'parse:p', 'output' => array('phone_code', "phone")),
            array('key' => 4, 'operation' => 'default:t', 'output' => 'address'),
            array('key' => 5, 'operation' => 'default:t', 'output' => 'city'),
            array('key' => 6, 'operation' => 'default:t', 'output' => 'state'),
            array('key' => 6, 'operation' => 'default:t', 'output' => 'state'),
            array('key' => 7, 'operation' => 'default:t', 'output' => 'zip'),
            array('key' => 8, 'operation' => 'default:t', 'output' => 'country'),
            array('key' => 9, 'operation' => 'default:t', 'output' => 'currency'),
        );

        return $parseables;
    }

    private function _temp_product_parseables()
    {
        $parseables = array(
            array('key' => 0, 'operation' => 'default:t', 'output' => 'product_name'),
            array('key' => 1, 'operation' => 'default:t', 'output' => 'product_category'),
            array('key' => 2, 'operation' => 'find:t', 'output' => 'partner_id', 'config' => array('mb' => 'strtolower', 'c' => 'id', 't' => 'Clients_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff'))),
            array('key' => 3, 'operation' => 'default:t', 'output' => 'fee', 'config' => array('mb' => array('str_replace', 'trim', 'floatval'), 'mb_c' => array('p' => array('AUD', ','), 'r' => ''))),
            array('key' => 4, 'operation' => 'default:t', 'output' => 'branches'),
            array('key' => 5, 'operation' => 'find:t', 'output' => 'workflow_id', 'config' => array('c' => 'id', 't' => 'General_files_model', 'cbm' => array('id'), 'cbm_s' => array())),
            array('key' => 6, 'operation' => 'default:t', 'output' => 'enrolled'),
            array('key' => 7, 'operation' => 'default:t', 'output' => 'in_progress'),
        );

        return $parseables;
    }

    private function _application_parseables()
    {
        $parseables = array(
            array('key' => 38, 'operation' => 'find:t', 'output' => 'client_id', 'config' => array('mb' => 'strtolower', 'c' => 'id', 't' => 'Clients_model', 'cbm' => array('va_internal_id'), 'cbm_s' => array())),
            array('key' => 10, 'operation' => 'find:t', 'output' => 'workflow_id', 'config' => array('c' => 'id', 't' => 'General_files_model', 'cbm' => array('description'), 'cbm_s' => array("file_type" => "workflow"))),
            array('key' => 10, 'operation' => 'find:t', 'output' => 'doc_check_list_id', 'config' => array('c' => 'id', 't' => 'General_files_model', 'cbm' => array('description'), 'cbm_s' => array("file_type" => "document_check_list"))),
            array('key' => 12, 'operation' => 'find:t', 'output' => 'partner_ids', 'config' => array('c' => 'client_id', 't' => 'Users_model', 'cbm' => array('email'), 'cbm_s' => array())),
            array('key' => 13, 'operation' => 'default:t', 'output' => 'title'),
            array('key' => 13, 'operation' => 'find:t', 'output' => 'temp_product_id', 'config' => array('mb' => 'strtolower', 'c' => 'id', 't' => 'Temp_Product_model', 'cbm' => array('product_name'), 'cbm_s' => array())),
            array('key' => 16, 'operation' => 'match:t', 'output' => array('status_id', 'status'), 'config' => array(array('on' => 'Completed', 'set' => array('status' => 'open', 'status_id' => 2)), array('on' => 'In Progress', 'set' => array('status' => 'open', 'status_id' => 1)), array('on' => 'Discontinued', 'set' => array('status' => 'open', 'status_id' => 4)))),
            array('key' => 17, 'operation' => 'default:t', 'output' => 'created_date', 'config' => array('mb' => array('substr', 'trim'), 'mb_c' => array('o' => 0, 'l' => 10))),
            array('key' => 18, 'operation' => 'default:t', 'output' => 'temp_applic_id'),
            array('key' => 20, 'operation' => 'default:t', 'output' => 'start_date', 'config' => array('mb' => array('substr', 'trim'), 'mb_c' => array('o' => 0, 'l' => 10))),
            array('key' => 21, 'operation' => 'default:t', 'output' => 'deadline', 'config' => array('mb' => array('substr', 'trim'), 'mb_c' => array('o' => 0, 'l' => 10))),
            array('key' => 22, 'operation' => 'match:t', 'output' => 'location_id', 'config' => array(array('on' => 'CORPORATE ADMISSION', 'set' => array('location_id' => 5)), array('on' => 'Head Office', 'set' => array('location_id' => 2)), array('on' => 'NEPAL', 'set' => array('location_id' => 3)), array('on' => 'Philippines', 'set' => array('location_id' => 6)), array('on' => 'Vietnam HANOI', 'set' => array('location_id' => 7)))),
            array('key' => 23, 'operation' => 'default:t', 'output' => 'disc_reason'),
            array('key' => 29, 'operation' => 'find:t', 'output' => 'created_by', 'config' => array('c' => 'id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff')))
        );

        return $parseables;
    }

    private function _task_parseables()
    {
        $parseables = array(
            array('key' => 0, 'operation' => 'parse:l', 'output' => 'labels'),
            array('key' => 1, 'operation' => 'default:t', 'output' => 'title'),
            array('key' => 2, 'operation' => 'find:t', 'output' => 'client_id', 'config' => array('c' => 'client_id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'client'))),
            array('key' => 5, 'operation' => 'default:t', 'output' => 'description'),
            array('key' => 6, 'operation' => 'match:t', 'output' => 'priority_id', 'config' => array(array('on' => 'Normal', 'set' => array('priority_id' => 1)), array('on' => 'High', 'set' => array('priority_id' => 2)), array('on' => 'Urgent', 'set' => array('priority_id' => 3)))),
            array('key' => 7, 'operation' => 'match:t', 'output' => array('status', 'status_id'), 'config' => array(array('on' => 'To Do', 'set' => array('status' => 'to_do', 'status_id' => 1)), array('on' => 'In Progress', 'set' => array('status' => 'in_progress', 'status_id' => 2)), array('on' => 'Completed', 'set' => array('status' => 'done', 'status_id' => 3)), array('on' => 'On Review', 'set' => array('status' => 'on_review', 'status_id' => 4)))),
            array('key' => 9, 'operation' => 'parse:d', 'output' => 'created_date', 'config' => array('s' => '/', 'd' => 0, 'm' => 1, 'y' => 2)),
            array('key' => 9, 'operation' => 'parse:d', 'output' => 'start_date', 'config' => array('s' => '/', 'd' => 0, 'm' => 1, 'y' => 2)),
            array('key' => 10, 'operation' => 'find:t', 'output' => 'assigned_to', 'config' => array('c' => 'id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff'))),
            array('key' => 11, 'operation' => 'parse:d', 'output' => 'deadline', 'config' => array('s' => '/', 'd' => 0, 'm' => 1, 'y' => 2)),
            array('key' => 13, 'operation' => 'find:c', 'output' => 'collaborators', 'config' => array('c' => 'id', 't' => 'Users_model', 'cbm' => array('first_name', 'last_name'), 'cbm_s' => array('user_type' => 'staff'))),
            array('key' => 14, 'operation' => 'default:t', 'output' => 'context', 'config' => array('mb' => 'strtolower')),
        );

        return $parseables;
    }

    private function _invoice_parseables()
    {
        $parseables = array(
            array('key' => 0, 'operation' => 'find:t', 'output' => 'client_id', 'config' => array('mb' => 'strtolower', 'c' => 'id', 't' => 'Clients_model', 'cbm' => array('va_internal_id'), 'cbm_s' => array())),
            array('key' => 5, 'operation' => 'default:t', 'output' => 'note'),
            array('key' => 9, 'operation' => 'parse:l', 'output' => 'labels'),
            array('key' => 10, 'operation' => 'parse:d', 'output' => 'due_date', 'config' => array('s' => '/', 'd' => 0, 'm' => 1, 'y' => 2)),
            array('key' => 11, 'operation' => 'default:t', 'output' => 'va_invoice_id'),
            array('key' => 12, 'operation' => 'parse:d', 'output' => 'bill_date', 'config' => array('s' => '/', 'd' => 0, 'm' => 1, 'y' => 2)),
            array('key' => 13, 'operation' => 'match:t', 'output' => 'status', 'config' => array(array('on' => 'Unpaid', 'set' => array('status' => 'not_paid')), array('on' => 'Paid', 'set' => array('status' => 'draft')))),
            array('key' => 14, 'operation' => 'default:t', 'output' => 'invoice_total'),
            array('key' => 15, 'operation' => 'default:t', 'output' => 'commission_amount'),
            array('key' => 16, 'operation' => 'default:t', 'output' => 'income_amount'),
            array('key' => 17, 'operation' => 'default:t', 'output' => 'discount_amount'),
            array('key' => 18, 'operation' => 'default:t', 'output' => 'income_sharing'),
            array('key' => 19, 'operation' => 'default:t', 'output' => 'other_payable'),
            array('key' => 20, 'operation' => 'default:t', 'output' => 'net_income'),
            array('key' => 21, 'operation' => 'default:t', 'output' => 'tax_received'),
            array('key' => 22, 'operation' => 'default:t', 'output' => 'paid_amount'),
            array('key' => 23, 'operation' => 'default:t', 'output' => 'due_amount'),
        );

        return $parseables;
    }
}

/* End of file Import.php */
/* Location: ./app/controllers/Import.php */