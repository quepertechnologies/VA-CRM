<?php

namespace App\Controllers;

class Projects extends Security_Controller
{

    protected $Project_settings_model;
    protected $Checklist_items_model;
    protected $Likes_model;
    protected $Pin_comments_model;
    protected $File_category_model;
    protected $Task_priority_model;

    public function __construct()
    {
        parent::__construct();
        if ($this->has_all_projects_restricted_role()) {
            app_redirect("forbidden");
        }

        $this->Project_settings_model = model('App\Models\Project_settings_model');
        $this->Checklist_items_model = model('App\Models\Checklist_items_model');
        $this->Likes_model = model('App\Models\Likes_model');
        $this->Pin_comments_model = model('App\Models\Pin_comments_model');
        $this->File_category_model = model('App\Models\File_category_model');
        $this->Task_priority_model = model("App\Models\Task_priority_model");
    }

    private function can_delete_projects($project_id = 0)
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            }

            $can_delete_projects = get_array_value($this->login_user->permissions, "can_delete_projects");
            $can_delete_only_own_created_projects = get_array_value($this->login_user->permissions, "can_delete_only_own_created_projects");

            if ($can_delete_projects) {
                return true;
            }

            if ($project_id) {
                $project_info = $this->Projects_model->get_one($project_id);
                if ($can_delete_only_own_created_projects && $project_info->created_by === $this->login_user->id) {
                    return true;
                }
            } else if ($can_delete_only_own_created_projects) { //no project given and the user has partial access
                return true;
            }
        }
    }

    private function can_add_remove_project_members()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else {
                if (get_array_value($this->login_user->permissions, "show_assigned_tasks_only") !== "1") {
                    if ($this->can_manage_all_projects()) {
                        return true;
                    } else if (get_array_value($this->login_user->permissions, "can_add_remove_project_members") == "1") {
                        return true;
                    }
                }
            }
        }
    }

    private function can_create_milestones()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_create_milestones") == "1") {
                //check is user a project member
                return $this->is_user_a_project_member;
            }
        }
    }

    private function can_edit_milestones()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_edit_milestones") == "1") {
                //check is user a project member
                return $this->is_user_a_project_member;
            }
        }
    }

    private function can_delete_milestones()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_delete_milestones") == "1") {
                //check is user a project member
                return $this->is_user_a_project_member;
            }
        }
    }

    private function can_delete_files($uploaded_by = 0)
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_delete_files") == "1") {
                //check is user a project member
                return $this->is_user_a_project_member;
            }
        } else {
            if (get_setting("client_can_delete_own_files_in_project") && $this->login_user->id == $uploaded_by) {
                return true;
            }
        }
    }

    private function can_view_files()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            } else {
                //check is user a project member
                return $this->is_user_a_project_member;
            }
        } else {
            //check settings for client's project permission
            if (get_setting("client_can_view_project_files")) {
                return $this->is_clients_project;
            }
        }
    }

    private function can_add_files()
    {
        return false;
        // if ($this->login_user->user_type == "staff") {
        //     if ($this->can_manage_all_projects()) {
        //         return true;
        //     } else {
        //         //check is user a project member
        //         return $this->is_user_a_project_member;
        //     }
        // } else {
        //     //check settings for client's project permission
        //     if (get_setting("client_can_add_project_files")) {
        //         return $this->is_clients_project;
        //     }
        // }
    }

    private function can_comment_on_files()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            } else {
                //check is user a project member
                return $this->is_user_a_project_member;
            }
        } else {
            //check settings for client's project permission
            if (get_setting("client_can_comment_on_files")) {
                //even the settings allow to create/edit task, the client can only comment on their own project's files
                return $this->is_clients_project;
            }
        }
    }

    private function can_view_gantt()
    {
        //check gantt module
        if (get_setting("module_gantt")) {
            if ($this->login_user->user_type == "staff") {
                if ($this->can_manage_all_projects()) {
                    return true;
                } else {
                    //check is user a project member
                    return $this->is_user_a_project_member;
                }
            } else {
                //check settings for client's project permission
                if (get_setting("client_can_view_gantt")) {
                    //even the settings allow to view gantt, the client can only view on their own project's gantt
                    return $this->is_clients_project;
                }
            }
        }
    }

    /* load project view */


    /* upload a file */

    function upload_contract_file()
    {
        upload_file_to_temp();
    }

    /* check valid file for notes */

    function validate_contract_file()
    {
        return validate_post_file($this->request->getPost("file_name"));
    }

    function index()
    {
        app_redirect("projects/all_projects");
    }

    function all_projects($status_id = 0)
    {
        validate_numeric_value($status_id);
        $view_data['project_labels_dropdown'] = json_encode($this->make_labels_dropdown("project", "", true));

        $view_data["can_create_projects"] = $this->can_create_projects();

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);
        $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("projects", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data["selected_status_id"] = $status_id;
        $view_data['project_statuses'] = $this->Project_status_model->get_details()->getResult();

        if ($this->login_user->user_type === "staff") {
            $view_data["can_edit_projects"] = $this->can_edit_projects();
            $view_data["can_delete_projects"] = $this->can_delete_projects();

            return $this->template->rander("projects/index", $view_data);
        } else {
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            $route_prefix = $this->get_client_view_route($this->login_user->client_id);
            return $this->template->rander("$route_prefix/projects/index", $view_data);
        }
    }

    /* load project  add/edit modal */

    function modal_form()
    {
        $project_id = $this->request->getPost('id');
        $client_id = $this->request->getPost('client_id');

        if ($project_id) {
            if (!$this->can_edit_projects($project_id)) {
                app_redirect("forbidden");
            }
        } else {
            if (!$this->can_create_projects()) {
                app_redirect("forbidden");
            }
        }

        $view_data["client_id"] = $client_id;
        $view_data['model_info'] = $this->Projects_model->get_one($project_id);
        if ($client_id) {
            $view_data['model_info']->client_id = $client_id;
        }

        if (!$client_id && $view_data['model_info']->client_id) {
            $client_id = $view_data['model_info']->client_id;
        }

        //check if it's from estimate. if so, then prepare for project
        $estimate_id = $this->request->getPost('estimate_id');
        if ($estimate_id) {
            $view_data['model_info']->estimate_id = $estimate_id;
        }

        //check if it's from order. If so, then prepare for project
        $order_id = $this->request->getPost('order_id');
        if ($order_id) {
            $view_data['model_info']->order_id = $order_id;
        }

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("projects", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        $view_data['hide_clients_dropdown'] = false;

        if (!$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "client") && !get_array_value($this->login_user->permissions, "client_specific")) {
            //user can't access clients. don't show clients dropdown
            $view_data['clients_dropdown'] = array();
            $view_data['hide_clients_dropdown'] = true;
        } else {
            $view_data['clients_dropdown'] = $this->_get_clients_dropdown_with_permission();
        }

        if (is_dev_mode()) {
            $view_data['team_members_dropdown'] = $this->get_team_members_dropdown(false, 'Created By');
        }
        $view_data['workflows_dropdown'] = $this->make_workflow_dropdown();
        $view_data['doc_check_list_dropdown'] = $this->make_doc_check_list_dropdown();
        $view_data['partners_dropdown'] = $this->make_partners_dropdown('institute');
        $view_data['referrals_dropdown'] = $this->make_partners_dropdown('referral', true);
        $view_data['subagent_commission'] = '';
        if ($client_id) {
            $client_info = $this->Clients_model->get_one($client_id);

            if ($client_info && $client_info->partner_id) {
                $subagent = $this->Clients_model->get_one($client_info->partner_id);
                $partner_options = array(
                    'partner_id' => $client_info->partner_id
                );
                $project_partner = $this->Project_partners_model->get_details($partner_options)->getRow();

                if ($project_partner) {
                    $view_data['subagent_commission'] = $project_partner->commission;
                }

                if ($subagent) {
                    $view_data['subagent_full_name'] = $subagent->first_name . ' ' . $subagent->last_name;
                    $view_data['subagent'] = $subagent;
                }
            }
        }

        $referral_info = $this->Project_partners_model->get_details(array('project_id' => $project_id, 'partner_type' => 'referral'))->getRow();
        $view_data['referral_info'] = $referral_info;

        $view_data['label_suggestions'] = $this->make_labels_dropdown("project", $view_data['model_info']->labels);
        $view_data['statuses'] = $this->Project_status_model->get_details()->getResult();
        $view_data["can_edit_projects"] = $this->can_edit_projects();
        if ($project_id) {
            $project_partner_options = array('project_id' => $project_id, 'partner_type' => 'institute');
            $view_data["project_partners"] = $this->Project_partners_model->get_details($project_partner_options)->getResult();
        } else {
            $view_data["project_partners"] = array();
        }
        return $this->template->view('projects/modal_form', $view_data);
    }

    // get email modal form
    function project_deadline_modal_form()
    {
        // $this->access_only_allowed_members();

        $project_id = $this->request->getPost('project_id');

        if ($project_id) {
            $options = array("id" => $project_id);
            $project_info = $this->Projects_model->get_details($options)->getRow();
            $view_data['modal_info'] = $project_info;
            $view_data['project_id'] = $project_id;

            return $this->template->view('projects/project_deadline_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    // get activities modal form
    function activities_modal_form()
    {
        if (!is_dev_mode()) {
            app_redirect("forbidden");
        }

        $project_id = $this->request->getPost('project_id');
        $milestone_id = $this->request->getPost('milestone_id');

        if ($milestone_id) {
            $view_data['milestone_id'] = $milestone_id;
            $view_data['project_id'] = $project_id;
            $view_data['team_members_dropdown'] = $this->get_team_members_dropdown(false, 'Added By');

            return $this->template->view('projects/activity_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    function save_milestone_activity()
    {
        if (!$this->can_edit_milestones()) {
            app_redirect("forbidden");
        }

        $milestone_id = $this->request->getPost('milestone_id');
        $project_id = $this->request->getPost('project_id');
        $created_date = $this->request->getPost('created_date');
        $created_by = $this->request->getPost('created_by');
        $log_type = $this->request->getPost('log_type');
        $activity = $this->request->getPost('activity');

        $changes = null;
        if ($log_type == 'milestone') {
            $changes = array('status' => array('from' => 'Queued', 'to' => 'Completed'));
        }

        $data = array(
            'action' => $log_type == 'milestone' ? 'updated' : 'created',
            'created_at' => $created_date,
            'created_by' => $created_by ? $created_by : $this->login_user->id,
            'log_type' => $log_type,
            'log_type_title' => $activity,
            'log_type_id' => 0,
            'log_for' => 'project',
            'log_for_id' => $project_id,
            'log_for2' => '',
            'log_for_id2' => 0,
            'log_for3' => 'milestone',
            'log_for_id3' => $milestone_id,
            'changes' => $changes ? serialize($changes) : null
        );

        $success = $this->Activity_logs_model->ci_save($data);

        if ($success) {
            echo json_encode(array("success" => true, 'message' => app_lang('success')));
        } else {
            echo json_encode(array("success" => false, 'message' => 'Something went wrong'));
        }
    }


    function delete_milestone_activity()
    {
        $id = $this->request->getPost('id');
        $this->Activity_logs_model->delete_where(array('id' => $id));
        echo json_encode(array("success" => true, 'message' => app_lang('success')));
    }

    // get email modal form
    function deadline_modal_form()
    {
        // $this->access_only_allowed_members();

        $project_id = $this->request->getPost('project_id');
        $milestone_id = $this->request->getPost('milestone_id');

        if ($milestone_id) {
            $options = array("id" => $milestone_id);
            $milestone_info = $this->Milestones_model->get_details($options)->getRow();
            $view_data['model_info'] = $milestone_info;
            $view_data['project_id'] = $project_id;

            return $this->template->view('projects/deadline_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    // get email modal form
    function email_modal_form()
    {
        // $this->access_only_allowed_members();

        $project_id = $this->request->getPost('project_id');
        $milestone_id = $this->request->getPost('milestone_id');

        if ($project_id) {
            $options = array("id" => $project_id);
            $project_info = $this->Projects_model->get_details($options)->getRow();
            $view_data['project_info'] = $project_info;

            $contacts_options = array("client_id" => $project_info->client_id);
            $contacts = $this->Users_model->get_details($contacts_options)->getResult();

            $primary_contact_info = "";
            $contacts_dropdown = array();
            foreach ($contacts as $contact) {
                if ($contact->is_primary_contact) {
                    $primary_contact_info = $contact;
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name . " (" . app_lang("primary_contact") . ")";
                }
            }

            foreach ($contacts as $contact) {
                if (!$contact->is_primary_contact) {
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name;
                }
            }

            $view_data['contacts_dropdown'] = $contacts_dropdown;

            $template_data = $this->get_project_tasks_email_template(
                $project_id,
                $primary_contact_info ? $primary_contact_info->client_id : 0,
                "",
                $project_info,
                $primary_contact_info
            );
            $view_data['message'] = get_array_value($template_data, "message");
            $view_data['subject'] = get_array_value($template_data, "subject");
            $view_data['milestone_id'] = $milestone_id;

            return $this->template->view('projects/send_email_modal_form', $view_data);
        } else {
            show_404();
        }
    }


    function get_project_tasks_email_template($project_id = 0, $contact_id = 0, $return_type = "", $project_info = "", $contact_info = "")
    {
        // $this->access_only_allowed_members();

        validate_numeric_value($project_id);
        validate_numeric_value($contact_id);

        if (!$project_info) {
            $options = array("id" => $project_id);
            $project_info = $this->Projects_model->get_details($options)->getRow();
        }

        if (!$contact_info) {
            $contact_info = $this->Users_model->get_one($contact_id);
        }

        $tasks_options = array('project_id' => $project_id);
        $tasks = $this->Tasks_model->get_details($tasks_options)->getResult();
        //task reminders
        $email_template = $this->Email_templates_model->get_final_template("project_task_deadline_reminder", true);

        //get the deadline
        //all deadlines are same
        if (count($tasks)) {
            $task_deadline = reset($tasks); //get first user's tasks
            $task_deadline = get_array_value($task_deadline, 0); //first task
            if ($task_deadline) {
                $task_deadline = $task_deadline->id; //task id
                $task_deadline = $this->Tasks_model->get_one($task_deadline)->deadline;
                $parser_data["DEADLINE"] = format_to_date($task_deadline, false);
            }
        } else {
            $parser_data["DEADLINE"] = "N/A";
        }

        //prepare all tasks of this user
        $table = view("tasks/notification_multiple_tasks_table", array("tasks" => $tasks));

        $parser = \Config\Services::parser();

        $user_info = $this->Users_model->get_one($contact_info->id);
        $user_email_address = $user_info->email;
        $user_language = $user_info->language;

        $parser_data["RECIPIENTS_EMAIL_ADDRESS"] = $user_email_address;
        $parser_data["SIGNATURE"] = get_array_value($email_template, "signature_$user_language") ? get_array_value($email_template, "signature_$user_language") : get_array_value($email_template, "signature_default");

        $parser_data["TASKS_LIST"] = $table;
        $parser_data["APP_TITLE"] = get_setting("app_title");
        $message = get_array_value($email_template, "message_$user_language") ? get_array_value($email_template, "message_$user_language") : get_array_value($email_template, "message_default");
        $message = $parser->setData($parser_data)->renderString($message);
        // $parser_data["EVENT_TITLE"] = "$notification->user_name . " " . sprintf(app_lang("notification_" . $notification->event), $notification->to_user_name)";
        $parser_data["EVENT_TITLE"] = "Send Reminder";
        $subject = get_array_value($email_template, "subject_$user_language") ? get_array_value($email_template, "subject_$user_language") : get_array_value($email_template, "subject_default");
        $subject = $parser->setData($parser_data)->renderString($subject);


        if ($return_type == "json") {
            echo json_encode(array("success" => true, "message_view" => $message));
        } else {
            return array(
                "message" => $message,
                "subject" => $subject
            );
        }
    }

    function send_email()
    {
        // $this->access_only_allowed_members();

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $project_id = $this->request->getPost('id');
        $milestone_id = $this->request->getPost('milestone_id');

        $contact_id = $this->request->getPost('contact_id');
        $cc = $this->request->getPost('contract_cc');

        $custom_bcc = $this->request->getPost('contract_bcc');
        $subject = $this->request->getPost('subject');
        $message = decode_ajax_post_data($this->request->getPost('message'));

        $contact = $this->Users_model->get_one($contact_id);

        $default_bcc = get_setting('send_contract_bcc_to');
        $bcc_emails = "";

        if ($default_bcc && $custom_bcc) {
            $bcc_emails = $default_bcc . "," . $custom_bcc;
        } else if ($default_bcc) {
            $bcc_emails = $default_bcc;
        } else if ($custom_bcc) {
            $bcc_emails = $custom_bcc;
        }

        if (!is_server_localhost() && send_app_mail($contact->email, $subject, $message, array("cc" => $cc, "bcc" => $bcc_emails))) {
            // change email status
            // $status_data = array("status" => "sent", "last_email_sent_date" => get_my_local_time());
            // if ($this->Contracts_model->ci_save($status_data, $contract_id)) {
            // }
            $data = array(
                'project_id' => $project_id,
                'milestone_id' => $milestone_id,
                'created_by' => $this->login_user->id,
                'subject' => $subject,
                'send_to' => $contact->email,
                'send_to_name' => $contact->first_name . ' ' . $contact->last_name,
                'send_from' => get_setting("email_sent_from_address"),
                'created_date' => get_current_utc_time()
            );
            $this->Email_activity_model->ci_save($data);
            echo json_encode(array('success' => true, 'message' => app_lang("email_sent_message")));
        } else {
            echo json_encode(array('success' => false, 'message' => app_lang('error_occurred')));
        }
    }

    //get clients dropdown
    private function _get_clients_dropdown_with_permission()
    {
        $clients_dropdown = array();

        if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "client")) {
            $access_client = $this->get_access_info("client");
            $clients = $this->Clients_model->get_details(array("show_own_clients_only_user_id" => $this->show_own_clients_only_user_id(), "client_groups" => $access_client->allowed_client_groups))->getResult();
            foreach ($clients as $client) {
                $clients_dropdown[$client->id] = $client->account_type === 'organization' ? $client->company_name : $client->first_name . ' ' . $client->last_name;
            }
        }

        return $clients_dropdown;
    }

    /* insert or update a project */

    function save()
    {

        $id = $this->request->getPost('id');
        $_title = "";

        if ($id) {
            if (!$this->can_edit_projects($id)) {
                app_redirect("forbidden");
            }
        } else {
            if (!$this->can_create_projects()) {
                app_redirect("forbidden");
            }
        }

        $this->validate_submitted_data(array(
            "title" => "required"
        ));

        $estimate_id = $this->request->getPost('estimate_id');
        $add_new_title_to_library = $this->request->getPost('add_new_title_to_library');
        $title_id = $this->request->getPost('title_id');
        $status_id = $this->request->getPost('status_id');
        $order_id = $this->request->getPost('order_id');
        $workflow_id = $this->request->getPost('workflow_id');
        $doc_check_list_id = $this->request->getPost('doc_check_list_id');
        $project_type = $this->request->getPost('project_type');
        $client_id = $this->request->getPost('client_id') ? $this->request->getPost('client_id') : 0;
        $partner_client_id = $this->request->getPost('partner_client_id') ? $this->request->getPost('partner_client_id') : '';

        $client_info = $this->Clients_model->get_one($client_id);

        if ($add_new_title_to_library) {
            $_title = $this->request->getPost('title');
            $title_data = array(
                "product_name" => $_title,
                "product_category" => "Course",
                "partner_id" => 1,
                "fee" => "0.00",
                "branches" => "",
                "workflow_id" => 1,
                "enrolled" => 0,
                "in_progress" => 0,
                "created_date" => get_current_utc_time(),
                'deleted' => 0
            );
            $saved = $this->Temp_Product_model->ci_save($title_data);

            if ($saved) {
                $title_id = $saved;
            }
        } else {
            $_title = $this->request->getPost('title_text');
        }

        $data = array(
            "title_id" => $title_id,
            "title" => $_title,
            "description" => $this->request->getPost('description'),
            "client_id" => ($project_type === "internal_project") ? 0 : $client_id,
            "partner_client_id" => $partner_client_id,
            "start_date" => $this->request->getPost('start_date'),
            "deadline" => $this->request->getPost('deadline'),
            "project_type" => $project_type,
            "price" => unformat_currency($this->request->getPost('price')),
            "labels" => $this->request->getPost('labels'),
            "status_id" => $status_id ? $status_id : 1,
            "estimate_id" => $estimate_id,
            "order_id" => $order_id,
            "workflow_id" => $workflow_id ? $workflow_id : 0,
            "doc_check_list_id" => $doc_check_list_id ? $doc_check_list_id : 0,
            "partner_ids" => $this->request->getPost('partner_ids'),
            'location_id' => get_ltm_opl_id(),
            'company_name' => $this->get_client_full_name(0, $client_info)
        );

        if (!$id) {
            $created_date = $this->request->getPost('created_date');
            $created_by = $this->request->getPost('created_by');

            if ($created_by) {
                $created_by_user = $this->Users_model->get_one($created_by);
                if ($created_by_user) {
                    $data['location_id'] = $created_by_user->location_id > -1 ? $created_by_user->location_id : get_ltm_opl_id();
                }
            }

            $data["created_date"] = $created_date ? $created_date : get_current_utc_time();
            $data["created_by"] = $created_by ? $created_by : $this->login_user->id;
        }

        //created by client? overwrite the client id for safety
        if ($this->login_user->user_type === "client") {
            $data["client_id"] = $this->login_user->client_id;
        }

        $data = clean_data($data);

        //set null value after cleaning the data
        if (!$data["start_date"]) {
            $data["start_date"] = NULL;
        }

        if (!$data["deadline"]) {
            $data["deadline"] = NULL;
        }

        $save_id = $this->Projects_model->ci_save($data, $id);
        if ($save_id) {

            $_link = anchor(get_uri("projects/view/" . $save_id), $_title);
            $timeline_data = array(
                'client_id' => $data["client_id"],
                'title' => get_login_team_member_profile_link(),
                'caption' => $id ? timeline_label('updated') . $_link : timeline_label('created') . $_link
            );
            $this->Timeline_model->ci_save($timeline_data);

            // $this->Project_partners_model->delete_where(array('project_id' => $save_id));

            $partner_options = array('account_type' => 3, 'partner_type' => 'institute');
            $partners = $this->Clients_model->get_details($partner_options)->getResult();

            $partner_ids = [];

            if (count($partners) && $id) {
                foreach ($partners as $partner) {
                    if ($this->request->getPost('com_percentage_' . $partner->id)) {
                        $project_partner_data = array(
                            'project_id' => $save_id,
                            'partner_id' => $partner->id,
                            'full_name'  => $this->get_client_full_name(0, $partner),
                            'commission' => $this->request->getPost('com_percentage_' . $partner->id),
                            'created_date' => get_current_utc_time(),
                            'partner_type' => $partner->partner_type
                        );

                        $project_partner = $this->Project_partners_model->get_details(array('partner_id' => $partner->id))->getRow();

                        if ($project_partner) {
                            $this->Project_partners_model->ci_save($project_partner_data, $project_partner->id);
                        } else {
                            $this->Project_partners_model->ci_save($project_partner_data);
                        }

                        $partner_ids[] = $partner->id;
                    }
                }
            }
            $subagent_id = 0;
            $subagent_default_commission = 0;
            if ($client_info && $client_info->partner_id) {
                $subagent_id = $client_info->partner_id;

                $partner = $this->Clients_model->get_one($subagent_id);

                if ($partner) {
                    $subagent_default_commission = $partner->com_percentage ? $partner->com_percentage : 0;
                }
            }
            $subagent_commission = $this->request->getPost('subagent_commission');

            if ($subagent_id) {
                $project_partner_data = array(
                    'project_id' => $save_id,
                    'partner_id' => $subagent_id,
                    'full_name'  => $this->get_client_full_name($subagent_id),
                    'commission' => $subagent_commission ? $subagent_commission : $subagent_default_commission,
                    'created_date' => get_current_utc_time(),
                    'partner_type' => $partner->partner_type
                );

                if ($id) {
                    $project_partner = $this->Project_partners_model->get_details(array('partner_id' => $subagent_id, 'project_id' => $save_id))->getRow();
                    if ($project_partner) {
                        $this->Project_partners_model->ci_save($project_partner_data, $project_partner->id);
                    } else {
                        $this->Project_partners_model->ci_save($project_partner_data);
                    }
                } else {
                    $this->Project_partners_model->ci_save($project_partner_data);
                }

                $partner_ids[] = $partner->id;
            }

            $project_data = array('partner_ids' => implode(',', $partner_ids));
            $this->Projects_model->ci_save($project_data, $save_id);

            save_custom_fields("projects", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            //send notification
            if ($status_id == 2) {
                log_notification("project_completed", array("project_id" => $save_id));
            }

            $options = array('project_id' => $save_id);
            $milestones = $this->Milestones_model->get_details($options)->getResult();
            if ($workflow_id && count($milestones) == 0) {
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
                                    "project_id" => $save_id,
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

            if (!$id) {

                if ($this->login_user->user_type === "staff") {
                    //this is a new project and created by team members
                    //add default project member after project creation
                    $data = array(
                        "project_id" => $save_id,
                        "user_id" => $this->login_user->id,
                        "is_leader" => 1
                    );
                    $this->Project_members_model->save_member($data);
                }

                //created from estimate? save the project id
                if ($estimate_id) {
                    $data = array("project_id" => $save_id);
                    $this->Estimates_model->ci_save($data, $estimate_id);
                }

                //created from order? save the project id
                if ($order_id) {
                    $data = array("project_id" => $save_id);
                    $this->Orders_model->ci_save($data, $order_id);
                }

                $this->_handle_doc_check_list($doc_check_list_id, $save_id);

                log_notification("project_created", array("project_id" => $save_id));
            }
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }


    /* get list of milestones for filter */

    function get_milestones_for_filter()
    {
        $this->access_only_team_members();
        $milestones = array(array('id' => '', 'text' => '- ' . app_lang('milestone') . ' -'));
        $workflow_id = $this->request->getPost("workflow_id");
        if ($workflow_id) {
            $workflow = $this->General_files_model->get_details(array("id" => $workflow_id))->getRow();

            if ($workflow) {
                $source_url = get_source_url_of_file(make_array_of_file($workflow), get_general_file_path("staff", $workflow->user_id));
                $content = file_get_contents($source_url);
                $content = json_decode($content, true);
                if ($content) {
                    foreach ($content as $key => $value) {
                        $title = get_array_value($value, 'title');
                        if (!$title) {
                            $title = "N/A";
                        }
                        $milestones[]  = array('id' => $title, 'text' => $title);
                    }
                }
            }
        }

        echo json_encode($milestones);
    }

    /* Change Current Milestone */
    function previous_milestone($project_id = 0)
    {
        if (!$this->can_edit_milestones()) {
            app_redirect("forbidden");
        }

        $milestone_id = $this->get_current_milestone_id($project_id);
        $milestone_info = $this->Milestones_model->get_one($milestone_id);

        if ($milestone_info) {
            $options = array('project_id' => $project_id, 'is_current' => 2, 'sort' => $milestone_info->sort - 1);
            $previous_milestone_info = $this->Milestones_model->get_one_where($options);

            if ($previous_milestone_info) {
                $current_data = array(
                    'is_current' => 0,
                );
                $data = array(
                    'is_current' => 1,
                );

                $this->Milestones_model->ci_save($current_data, $milestone_info->id);
                $this->Milestones_model->ci_save($data, $previous_milestone_info->id);

                echo json_encode(array("success" => true, 'message' => app_lang('success')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function save_active_milestone_dev()
    {
        if (!$this->can_edit_milestones()) {
            app_redirect("forbidden");
        }

        $next_milestone_id = $this->request->getPost('milestone_id');

        $next_milestone = $this->Milestones_model->get_one($next_milestone_id);

        $milestone_options = array('project_id' => $next_milestone->project_id);
        $milestones = $this->Milestones_model->get_details($milestone_options)->getResult();

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
            $this->Milestones_model->ci_save($data, $milestone->id);
        }

        echo json_encode(array("success" => true, 'message' => app_lang('success')));
    }

    function project_next_stage_modal_form()
    {
        $project_id = $this->request->getPost('project_id');

        $view_data['project_id'] = $project_id;

        return $this->template->view('projects/project_next_stage_modal_form', $view_data);
    }

    function next_milestone()
    {
        $project_id = $this->request->getPost('project_id');
        $note = $this->request->getPost('note');

        if (!$this->can_edit_milestones()) {
            app_redirect("forbidden");
        }

        $milestone_id = $this->get_current_milestone_id($project_id);
        $milestone_info = $this->Milestones_model->get_one($milestone_id);

        if ($milestone_info) {

            // if ($milestone_info->is_doc_check_list) {
            //     $check_list_options = array('project_id' => $project_id);
            //     $check_list_items = $this->Project_doc_check_list_model->get_details($check_list_options)->getResult();
            //     if (count($check_list_items)) {
            //         foreach ($check_list_items as $list_item) {
            //             if ($list_item->is_required && $list_item->file_id == 0) {
            //                 echo json_encode(array('success' => false, 'message' => 'Unable to jump to the next stage. One or more documents are required to be uploaded<br><br><small>The Client will be automatically notified about the pending documents on a regular basis.</small>'));
            //                 return;
            //             }
            //         }
            //     }
            // }

            $options = array('project_id' => $project_id, 'milestone_id' => $milestone_info->id);
            $tasks = $this->Tasks_model->get_details($options)->getResult();

            $is_incomplete = false;
            // if (count($tasks)) {
            //     foreach ($tasks as $key => $task) {
            //         if ((int)$task->status_id != 3) {
            //             $is_incomplete = true;
            //             break;
            //         }
            //     }
            // }

            if (!$is_incomplete) {
                $next_sort = $milestone_info->sort;
                $next_sort++;
                $options = array('project_id' => $project_id, 'is_current' => 0, 'sort' => $next_sort);
                $next_milestone_info = $this->Milestones_model->get_one_where($options);

                if ($next_milestone_info) {
                    $current_data = array(
                        'is_current' => 2,
                    );
                    $data = array(
                        'is_current' => 1,
                    );

                    $this->Milestones_model->ci_save($current_data, $milestone_info->id);
                    $this->Milestones_model->ci_save($data, $next_milestone_info->id);
                }


                $note_data = array(
                    'created_by' => $this->login_user->id,
                    'created_at' => get_current_utc_time(),
                    'title' => 'Changed the stage to ' . $next_milestone_info->title,
                    'description' => $note,
                    'project_id' => $project_id,
                    'milestone_id' => $this->get_current_milestone_id($project_id),
                    'is_public' => 1
                );

                $this->Notes_model->ci_save($note_data);

                echo json_encode(array("success" => true, 'message' => app_lang('success')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* Show a modal to clone a project */

    function clone_project_modal_form()
    {

        $project_id = $this->request->getPost('id');

        if (!$this->can_create_projects()) {
            app_redirect("forbidden");
        }


        $view_data['model_info'] = $this->Projects_model->get_one($project_id);

        $view_data['clients_dropdown'] = $this->Clients_model->get_dropdown_list(array("company_name", 'first_name', 'last_name'), "id", array("is_lead" => 0));

        $view_data['workflows_dropdown'] = $this->make_workflow_dropdown();

        $view_data['label_suggestions'] = $this->make_labels_dropdown("project", $view_data['model_info']->labels);

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("projects", $view_data['model_info']->id, 1, "staff")->getResult(); //we have to keep this regarding as an admin user because non-admin user also can acquire the access to clone a project

        return $this->template->view('projects/clone_project_modal_form', $view_data);
    }

    /* create a new project from another project */

    function save_cloned_project()
    {

        ini_set('max_execution_time', 300); //300 seconds 

        $project_id = $this->request->getPost('project_id');
        $project_start_date = $this->request->getPost('start_date');

        if (!$this->can_create_projects()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "title" => "required"
        ));

        $copy_same_assignee_and_collaborators = $this->request->getPost("copy_same_assignee_and_collaborators");
        $copy_milestones = $this->request->getPost("copy_milestones");
        $change_the_milestone_dates_based_on_project_start_date = $this->request->getPost("change_the_milestone_dates_based_on_project_start_date");
        $move_all_tasks_to_to_do = $this->request->getPost("move_all_tasks_to_to_do");
        $copy_tasks_start_date_and_deadline = $this->request->getPost("copy_tasks_start_date_and_deadline");
        $change_the_tasks_start_date_and_deadline_based_on_project_start_date = $this->request->getPost("change_the_tasks_start_date_and_deadline_based_on_project_start_date");
        $project_type = $this->request->getPost('project_type');
        $client_id = $this->request->getPost('client_id') ? $this->request->getPost('client_id') : 0;

        $client_info = $this->Clients_model->get_one($client_id);

        //prepare new project data
        $now = get_current_utc_time();
        $data = array(
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description'),
            "client_id" => ($project_type === "internal_project") ? 0 : $client_id,
            "start_date" => $project_start_date,
            "deadline" => $this->request->getPost('deadline'),
            "project_type" => $project_type,
            "price" => unformat_currency($this->request->getPost('price')),
            "created_date" => $now,
            "created_by" => $this->login_user->id,
            "labels" => $this->request->getPost('labels'),
            "status_id" => 1,
            "workflow_id" => $this->request->getPost('workflow_id') ? $this->request->getPost('workflow_id') : 0,
            'company_name' => $client_info->account_type === 'organization' ? $client_info->company_name : $client_info->first_name . ' ' . $client_info->last_name

        );

        if (!$data["start_date"]) {
            $data["start_date"] = NULL;
        }

        if (!$data["deadline"]) {
            $data["deadline"] = NULL;
        }


        //add new project
        $new_project_id = $this->Projects_model->ci_save($data);

        //old project info
        $old_project_info = $this->Projects_model->get_one($project_id);

        //add milestones
        //when the new milestones will be created the ids will be different. so, we have to convert the milestone ids. 
        $milestones_array = array();

        if ($copy_milestones) {
            $milestones = $this->Milestones_model->get_all_where(array("project_id" => $project_id, "deleted" => 0))->getResult();
            foreach ($milestones as $milestone) {
                $old_milestone_id = $milestone->id;

                //prepare new milestone data. remove id from existing data
                $milestone->project_id = $new_project_id;
                $milestone_data = (array) $milestone;
                unset($milestone_data["id"]);

                //add new milestone and keep a relation with new id and old id
                $milestones_array[$old_milestone_id] = $this->Milestones_model->ci_save($milestone_data);
            }
        } else if ($change_the_milestone_dates_based_on_project_start_date && $old_project_info->start_date && $project_start_date) {
            $milestones = $this->Milestones_model->get_all_where(array("project_id" => $project_id, "deleted" => 0))->getResult();
            foreach ($milestones as $milestone) {
                $old_milestone_id = $milestone->id;

                //prepare new milestone data. remove id from existing data
                $milestone->project_id = $new_project_id;

                $old_project_start_date = $old_project_info->start_date;
                $old_milestone_due_date = $milestone->due_date;

                $milestone_due_date_day_diff = get_date_difference_in_days($old_milestone_due_date, $old_project_start_date);
                $milestone->due_date = add_period_to_date($project_start_date, $milestone_due_date_day_diff, "days");

                $milestone_data = (array) $milestone;
                unset($milestone_data["id"]);

                //add new milestone and keep a relation with new id and old id
                $milestones_array[$old_milestone_id] = $this->Milestones_model->ci_save($milestone_data);
            }
        }

        //we'll keep all new task ids vs old task ids. by this way, we'll add the checklist easily 
        $task_ids = array();

        //add tasks
        //first, save tasks whose are not sub tasks 
        $tasks = $this->Tasks_model->get_all_where(array("project_id" => $project_id, "deleted" => 0, "parent_task_id" => 0))->getResult();
        foreach ($tasks as $task) {
            $task_data = $this->_prepare_new_task_data_on_cloning_project($new_project_id, $milestones_array, $task, $copy_same_assignee_and_collaborators, $copy_tasks_start_date_and_deadline, $move_all_tasks_to_to_do, $change_the_tasks_start_date_and_deadline_based_on_project_start_date, $old_project_info, $project_start_date);

            //add new task
            $new_taks_id = $this->Tasks_model->ci_save($task_data);

            //bind old id with new
            $task_ids[$task->id] = $new_taks_id;

            //save custom fields of task
            $this->_save_custom_fields_on_cloning_project($task, $new_taks_id);
        }

        //secondly, save sub tasks
        $tasks = $this->Tasks_model->get_all_where(array("project_id" => $project_id, "deleted" => 0, "parent_task_id !=" => 0))->getResult();
        foreach ($tasks as $task) {
            $task_data = $this->_prepare_new_task_data_on_cloning_project($new_project_id, $milestones_array, $task, $copy_same_assignee_and_collaborators, $copy_tasks_start_date_and_deadline, $move_all_tasks_to_to_do, $change_the_tasks_start_date_and_deadline_based_on_project_start_date, $old_project_info, $project_start_date);
            //add parent task
            $task_data["parent_task_id"] = $task_ids[$task->parent_task_id];

            //add new task
            $new_taks_id = $this->Tasks_model->ci_save($task_data);

            //bind old id with new
            $task_ids[$task->id] = $new_taks_id;

            //save custom fields of task
            $this->_save_custom_fields_on_cloning_project($task, $new_taks_id);
        }

        //save task dependencies
        $tasks = $this->Tasks_model->get_all_tasks_where_have_dependency($project_id)->getResult();
        foreach ($tasks as $task) {
            if (array_key_exists($task->id, $task_ids)) {
                //save blocked by tasks 
                if ($task->blocked_by) {
                    //find the newly created tasks
                    $new_blocked_by_tasks = "";
                    $blocked_by_tasks_array = explode(',', $task->blocked_by);
                    foreach ($blocked_by_tasks_array as $blocked_by_task) {
                        if (array_key_exists($blocked_by_task, $task_ids)) {
                            if ($new_blocked_by_tasks) {
                                $new_blocked_by_tasks .= "," . $task_ids[$blocked_by_task];
                            } else {
                                $new_blocked_by_tasks = $task_ids[$blocked_by_task];
                            }
                        }
                    }

                    //update newly created task
                    if ($new_blocked_by_tasks) {
                        $blocked_by_task_data = array("blocked_by" => $new_blocked_by_tasks);
                        $this->Tasks_model->ci_save($blocked_by_task_data, $task_ids[$task->id]);
                    }
                }

                //save blocking tasks 
                if ($task->blocking) {
                    //find the newly created tasks
                    $new_blocking_tasks = "";
                    $blocking_tasks_array = explode(',', $task->blocking);
                    foreach ($blocking_tasks_array as $blocking_task) {
                        if (array_key_exists($blocking_task, $task_ids)) {
                            if ($new_blocking_tasks) {
                                $new_blocking_tasks .= "," . $task_ids[$blocking_task];
                            } else {
                                $new_blocking_tasks = $task_ids[$blocking_task];
                            }
                        }
                    }

                    //update newly created task
                    if ($new_blocking_tasks) {
                        $blocking_task_data = array("blocking" => $new_blocking_tasks);
                        $this->Tasks_model->ci_save($blocking_task_data, $task_ids[$task->id]);
                    }
                }
            }
        }

        //add project members
        $project_members = $this->Project_members_model->get_all_where(array("project_id" => $project_id, "deleted" => 0))->getResult();

        foreach ($project_members as $project_member) {
            //prepare new project member data. remove id from existing data
            $project_member->project_id = $new_project_id;
            $project_member_data = (array) $project_member;
            unset($project_member_data["id"]);

            $project_member_data["user_id"] = $project_member->user_id;

            $this->Project_members_model->save_member($project_member_data);
        }

        //add check lists
        $check_lists = $this->Checklist_items_model->get_all_checklist_of_project($project_id)->getResult();
        foreach ($check_lists as $list) {
            if (array_key_exists($list->task_id, $task_ids)) {
                $checklist_data = array(
                    "title" => $list->title,
                    "task_id" => $task_ids[$list->task_id],
                    "is_checked" => 0
                );

                $this->Checklist_items_model->ci_save($checklist_data);
            }
        }

        $project_settings = $this->Project_settings_model->get_details(array("project_id" => $project_id))->getResult();
        foreach ($project_settings as $project_setting) {
            $setting = $project_setting->setting_name;
            $value = $project_setting->setting_value;
            if (!$value) {
                $value = "";
            }

            $this->Project_settings_model->save_setting($new_project_id, $setting, $value);
        }

        if ($new_project_id) {
            //save custom fields of project
            save_custom_fields("projects", $new_project_id, 1, "staff"); //we have to keep this regarding as an admin user because non-admin user also can acquire the access to clone a project

            log_notification("project_created", array("project_id" => $new_project_id));

            echo json_encode(array("success" => true, 'id' => $new_project_id, 'message' => app_lang('project_cloned_successfully')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    private function _prepare_new_task_data_on_cloning_project($new_project_id, $milestones_array, $task, $copy_same_assignee_and_collaborators, $copy_tasks_start_date_and_deadline, $move_all_tasks_to_to_do, $change_the_tasks_start_date_and_deadline_based_on_project_start_date, $old_project_info, $project_start_date)
    {
        //prepare new task data. 
        $task->project_id = $new_project_id;
        $milestone_id = get_array_value($milestones_array, $task->milestone_id);
        $task->milestone_id = $milestone_id ? $milestone_id : "";
        $task->status = "to_do";

        if (!$copy_same_assignee_and_collaborators) {
            $task->assigned_to = "";
            $task->collaborators = "";
        }

        $task_data = (array) $task;
        unset($task_data["id"]); //remove id from existing data

        if ($move_all_tasks_to_to_do) {
            $task_data["status"] = "to_do";
            $task_data["status_id"] = 1;
        }

        if (!$copy_tasks_start_date_and_deadline && !$change_the_tasks_start_date_and_deadline_based_on_project_start_date) {
            $task->start_date = NULL;
            $task->deadline = NULL;
        } else if ($change_the_tasks_start_date_and_deadline_based_on_project_start_date && $old_project_info->start_date && $project_start_date) {
            $old_project_start_date = $old_project_info->start_date;
            $old_task_start_date = $task->start_date;
            $old_task_end_date = $task->deadline;

            if ($old_task_start_date) {
                $start_date_day_diff = get_date_difference_in_days($old_task_start_date, $old_project_start_date);
                $task_data["start_date"] = add_period_to_date($project_start_date, $start_date_day_diff, "days");
            } else {
                $task_data["start_date"] = NULL;
            }

            if ($old_task_end_date) {
                $end_date_day_diff = get_date_difference_in_days($old_task_end_date, $old_project_start_date);
                $task_data["deadline"] = add_period_to_date($project_start_date, $end_date_day_diff, "days");
            } else {
                $task_data["deadline"] = NULL;
            }
        }

        return $task_data;
    }

    private function _save_custom_fields_on_cloning_project($task, $new_taks_id)
    {
        $old_custom_fields = $this->Custom_field_values_model->get_all_where(array("related_to_type" => "tasks", "related_to_id" => $task->id, "deleted" => 0))->getResult();

        //prepare new custom fields data
        foreach ($old_custom_fields as $field) {
            $field->related_to_id = $new_taks_id;

            $fields_data = (array) $field;
            unset($fields_data["id"]); //remove id from existing data
            //save custom field
            $this->Custom_field_values_model->ci_save($fields_data);
        }
    }

    /* delete a project */

    function delete()
    {
        $id = $this->request->getPost('id');

        if (!$this->can_delete_projects($id)) {
            app_redirect("forbidden");
        }

        if ($this->Projects_model->delete_project_and_sub_items($id)) {
            log_notification("project_deleted", array("project_id" => $id));

            try {
                app_hooks()->do_action("app_hook_data_delete", array(
                    "id" => $id,
                    "table" => get_db_prefix() . "projects",
                    "table_without_prefix" => "projects",
                ));
            } catch (\Exception $ex) {
                log_message('error', '[ERROR] {exception}', ['exception' => $ex]);
            }

            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of projects, prepared for datatable  */

    function list_data()
    {
        $this->access_only_team_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);

        $status_ids = $this->request->getPost('status_id') ? implode(",", $this->request->getPost('status_id')) : "";

        $options = array(
            "status_ids" => $status_ids,
            "project_label" => $this->request->getPost("project_label"),
            "custom_fields" => $custom_fields,
            "start_date_from" => $this->request->getPost("start_date_from"),
            "start_date_to" => $this->request->getPost("start_date_to"),
            "deadline" => $this->request->getPost('deadline'),
            "partner_id" => $this->request->getPost('partner_id'),
            "workflow_id" => $this->request->getPost('workflow_id'),
            "location_ids" => get_ltm_opl_id(false, ','),
            "current_milestone_title" => $this->request->getPost('current_milestone_title'),
            "custom_field_filter" => $this->prepare_custom_field_filter_values("projects", $this->login_user->is_admin, $this->login_user->user_type)
        );

        // only admin/ the user has permission to manage all projects, can see all projects, other team mebers can see only their own projects.
        if (!$this->can_manage_all_projects()) {
            $options["user_id"] = $this->login_user->id;
        }

        $all_options = append_server_side_filtering_commmon_params($options);

        $result = $this->Projects_model->get_details($all_options);

        //by this, we can handel the server side or client side from the app table prams.
        if (get_array_value($all_options, "server_side")) {
            $list_data = get_array_value($result, "data");
        } else {
            $list_data = $result->getResult();
            $result = array();
        }

        $result_data = array();
        foreach ($list_data as $data) {
            $result_data[] = $this->_make_row($data, $custom_fields);
        }

        $result["data"] = $result_data;

        echo json_encode($result);
    }

    /* list of projects, prepared for data-table  */
    function projects_list_data_of_team_member($team_member_id = 0)
    {
        validate_numeric_value($team_member_id);
        $this->access_only_team_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);

        $status_ids = $this->request->getPost('status_id') ? implode(",", $this->request->getPost('status_id')) : "";

        $options = array(
            "status_ids" => $status_ids,
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("projects", $this->login_user->is_admin, $this->login_user->user_type)
        );

        //add can see all members projects but team members can see only ther own projects
        if (!$this->can_manage_all_projects() && $team_member_id != $this->login_user->id) {
            app_redirect("forbidden");
        }

        $options["custom_application_filter"] = $team_member_id;

        $list_data = $this->Projects_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    function projects_list_data_of_client($client_id = 0)
    {
        validate_numeric_value($client_id);

        $this->access_only_team_members_or_client_contact($client_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);

        $status_ids = $this->request->getPost('status_id') ? implode(",", $this->request->getPost('status_id')) : "";

        $options = array(
            // "client_id" => $client_id,
            "status_ids" => $status_ids,
            "project_label" => $this->request->getPost("project_label"),
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("projects", $this->login_user->is_admin, $this->login_user->user_type)
        );

        $client_info = $this->Clients_model->get_one($client_id);

        if ((int)$client_info->account_type == 3) {
            $list_data = $this->Projects_model->own_projects($client_id)->getResult();
            // var_dump($list_data);exit();
        } else {
            $options['client_id'] = $client_id;
            $list_data = $this->Projects_model->get_details($options)->getResult();
        }

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of project list  table */

    private function _row_data($id)
    {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields
        );

        $data = $this->Projects_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of project list table */

    private function _make_row($data, $custom_fields)
    {
        $client = null;
        if ($data->client_id) {
            $client = $this->Clients_model->get_one($data->client_id);
        }

        $progress_summary = $this->Projects_model->get_progress_summary($data->id);
        $progress = $data->status_id == 2 ? 100 : $progress_summary->progress;

        $class = "bg-primary";
        if ($progress == 100) {
            $class = "progress-bar-success";
        }

        $progress_bar = "<div class='progress' title='$progress%'>
            <div  class='progress-bar $class' role='progressbar' aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100' style='width: $progress%'>
            </div>
        </div>";
        $start_date = is_date_exists($data->start_date) ? format_to_date($data->start_date, false) : "-";
        $dateline = is_date_exists($data->deadline) ? format_to_date($data->deadline, false) : "-";

        //has deadline? change the color of date based on status
        if (is_date_exists($data->deadline)) {
            if ($progress !== 100 && $data->status_id == 1 && get_my_local_time("Y-m-d") > $data->deadline) {
                $dateline = "<span class='text-danger mr5'>" . $dateline . "</span> ";
            } else if ($progress !== 100 && $data->status_id == 1 && get_my_local_time("Y-m-d") == $data->deadline) {
                $dateline = "<span class='text-warning mr5'>" . $dateline . "</span> ";
            }
        }

        $title = anchor(get_uri("projects/view/" . $data->id), $data->title);
        if ($data->labels_list) {
            $project_labels = make_labels_view_data($data->labels_list, true);
            $title .= "<br />" . $project_labels;
        }

        $optoins = "";
        if ($this->can_edit_projects($data->id)) {
            $optoins .= modal_anchor(get_uri("projects/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_project'), "data-post-id" => $data->id));
        }

        if ($this->can_delete_projects($data->id)) {
            $optoins .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_project'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("projects/delete"), "data-action" => "delete-confirmation"));
        }

        $route_prefix = $this->get_client_view_route($data->client_id);
        $client_name = "-";
        if ($data->client_id) {
            $full_name = $this->get_client_full_name($data->client_id, $client);
            if ($full_name) {
                $client_name = anchor(get_uri("$route_prefix/view/" . $data->client_id), $full_name);
            }
        }

        $phone = "-";
        if ($client && $client->phone_code && $client->phone) {
            $phone = $client->phone_code . ' ' . $client->phone;
        }

        $assignee = '-';
        if ($client && $client->assignee) {
            $assignee_data = $this->Users_model->get_one($client->assignee);
            $assignee = get_team_member_profile_link($client->assignee, $assignee_data->first_name . ' ' . $assignee_data->last_name);
        }

        $workflow = '-';
        if ($data->workflow_id) {
            $workflow_data = $this->General_files_model->get_one($data->workflow_id);
            if ($workflow_data) {
                $workflow = $workflow_data->description ?? '-';
            }
        }

        $current_stage = "-";
        $milestone_id = $this->get_current_milestone_id($data->id);
        if ($milestone_id) {
            $milestone_data = $this->Milestones_model->get_one($milestone_id);
            if ($milestone_data) {
                $current_stage = $milestone_data->title;
            }
        }
        
        $lead_member = '-';
        $options = array("project_id" => $data->id);
        $lead_member = $this->Project_members_model->get_details($options)->getResult();
        $lead_member_link = '';
        foreach($lead_member as $lead_member_key)
        {
            if ($lead_member_key) {
                $lead_member_link .= get_team_member_profile_link($lead_member_key->user_id,$lead_member_key->member_name).'<br>';
            }
        }

        $partner = '-';
        $partner_info = $this->Project_partners_model->get_details(array('project_id' => $data->id, 'partner_type' => 'institute'))->getRow();
        if ($partner_info) {
            $partner = get_client_contact_profile_link($partner_info->partner_id, $this->get_client_full_name($partner_info->partner_id), array(), array('account_type' => 3));
        }

        $status = $this->_get_project_status_label($data->status_id);

        $row_data = array(
            anchor(get_uri("projects/view/" . $data->id), $data->id),
            $title,
            //'<small>Starts: ' . $start_date . '</small><br>' . $dateline,
            $client_name . '<br><small>' . $phone . '</small>',
            $lead_member_link,
            // $assignee,
            $workflow,
            $current_stage,
            $partner,
            //$status,
            $progress_bar
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = $optoins;

        return $row_data;
    }

    /* load project details view */
    function view($project_id = 0, $tab = "")
    {
        validate_numeric_value($project_id);
        $this->init_project_permission_checker($project_id);

        $view_data = $this->_get_project_info_data($project_id);

        // $access_info = $this->get_access_info("invoice");
        $view_data["show_invoice_info"] = (get_setting("module_invoice") && $this->can_view_invoices()) ? true : false;

        $expense_access_info = $this->get_access_info("expense");
        $view_data["show_expense_info"] = (get_setting("module_expense") && $expense_access_info->access_type == "all") ? true : false;

        // $access_contract = $this->get_access_info("contract");
        // $view_data["show_contract_info"] = (get_setting("module_contract") && $access_contract->access_type == "all") ? true : false;
        $view_data["show_contract_info"] = false;

        $view_data["show_note_info"] = (get_setting("module_note")) ? true : false;

        $view_data["show_timmer"] = get_setting("module_project_timesheet") ? true : false;

        $this->init_project_settings($project_id);
        $view_data["show_timesheet_info"] = $this->can_view_timesheet($project_id);

        $view_data["show_tasks"] = true;

        // $view_data["show_gantt_info"] = $this->can_view_gantt();
        // $view_data["show_milestone_info"] = $this->can_view_milestones();
        $view_data["show_gantt_info"] = false;
        $view_data["show_milestone_info"] = false;

        if ($this->login_user->user_type === "client") {
            $view_data["show_timmer"] = false;
            $view_data["show_tasks"] = $this->client_can_view_tasks();

            if (!get_setting("client_can_edit_projects")) {
                $view_data["show_actions_dropdown"] = false;
            }
        }

        $view_data["show_files"] = $this->can_view_files();

        $view_data["tab"] = clean_data($tab);

        $view_data["is_starred"] = strpos($view_data['project_info']->starred_by, ":" . $this->login_user->id . ":") ? true : false;

        $view_data['can_edit_timesheet_settings'] = $this->can_edit_timesheet_settings($project_id);
        $view_data['can_edit_slack_settings'] = $this->can_edit_slack_settings();
        $view_data["can_create_projects"] = $this->can_create_projects();
        $view_data["can_edit_projects"] = $this->can_edit_projects($project_id);

        $view_data["show_actions_dropdown"] = $view_data["can_create_projects"] || $view_data["can_edit_projects"];

        $ticket_access_info = $this->get_access_info("ticket");
        $view_data["show_ticket_info"] = (get_setting("module_ticket") && get_setting("project_reference_in_tickets") && $ticket_access_info->access_type == "all") ? true : false;

        $view_data["project_statuses"] = $this->Project_status_model->get_details()->getResult();
        $view_data["show_customer_feedback"] = $this->has_client_feedback_access_permission();

        return $this->template->rander("projects/details_view", $view_data);
    }

    private function can_edit_timesheet_settings($project_id)
    {
        $this->init_project_permission_checker($project_id);
        if ($project_id && $this->login_user->user_type === "staff" && $this->can_view_timesheet($project_id)) {
            return true;
        }
    }

    private function can_edit_slack_settings()
    {
        if ($this->login_user->user_type === "staff" && $this->can_create_projects()) {
            return true;
        }
    }

    /* prepare project info data for reuse */

    private function _get_project_info_data($project_id)
    {
        $options = array(
            "id" => $project_id,
            "client_id" => $this->login_user->client_id,
        );

        if (!$this->can_manage_all_projects()) {
            $options["user_id"] = $this->login_user->id;
        }

        $project_info = $this->Projects_model->get_details($options)->getRow();
        $view_data['project_info'] = $project_info;

        if ($project_info) {
            $view_data['project_info'] = $project_info;
            $timer = $this->Timesheets_model->get_timer_info($project_id, $this->login_user->id)->getRow();
            $user_has_any_timer_except_this_project = $this->Timesheets_model->user_has_any_timer_except_this_project($project_id, $this->login_user->id);

            if (isset($project_info->workflow_id)) {
                $workflow = $this->General_files_model->get_details(array("id" => $project_info->workflow_id))->getRow();
                $view_data["workflow"] = $workflow;
            }

            if (isset($project_info->doc_check_list_id)) {
                $doc_check_list = $this->General_files_model->get_details(array("id" => $project_info->doc_check_list_id))->getRow();
                $view_data["doc_check_list"] = $doc_check_list;
            }

            //disable the start timer button if the setting is disabled
            $view_data["disable_timer"] = false;
            if ($user_has_any_timer_except_this_project && !get_setting("users_can_start_multiple_timers_at_a_time")) {
                $view_data["disable_timer"] = true;
            }

            if ($timer) {
                $view_data['timer_status'] = "open";
            } else {
                $view_data['timer_status'] = "";
            }

            $progress_summary = $this->Projects_model->get_progress_summary($project_id);
            $view_data['project_progress'] = $project_info->status_id == 2 ? 100 : $progress_summary->progress;

            return $view_data;
        } else {
            show_404();
        }
    }

    function show_my_starred_projects()
    {
        $view_data["projects"] = $this->Projects_model->get_starred_projects($this->login_user->id)->getResult();
        return $this->template->view('projects/star/projects_list', $view_data);
    }

    /* load project overview section */

    function overview($project_id)
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();
        $this->init_project_permission_checker($project_id);

        $view_data = $this->_get_project_info_data($project_id);
        $options = array("project_id" => $project_id);
        $milestones = $this->Milestones_model->get_details($options)->getResult();
        $view_data["milestones"] = $milestones;
        $view_data["total_milestones"] = count($milestones);
        $milestone_id = $this->get_current_milestone_id($project_id);
        $view_data["current_milestone"] = $this->Milestones_model->get_one($milestone_id);
        $view_data["task_statuses"] = $this->Tasks_model->get_task_statistics(array("project_id" => $project_id))->task_statuses;
        $view_data["branch"] = $this->get_location_label($view_data["project_info"]->location_id);
        $view_data["remaining_check_list_count"] = $this->get_remaining_project_doc_check_list_count($project_id);

        // $project_fees_options = array('project_id' => $project_id);
        $view_data["project_fees"] = $this->_get_payment_schedule_overview_info($project_id);
        // $view_data = $this->_get_payment_schedule_overview_info($project_id, $view_data); // appends & return the payment schedule overview data to the provided 2nd variable

        $view_data['status_label'] = $this->_get_project_status_label($view_data['project_info']->status_id, 'h4');
        $view_data['project_id'] = $project_id;
        $offset = 0;
        $view_data['offset'] = $offset;
        $view_data['activity_logs_params'] = array("log_for" => "project", "log_for_id" => $project_id, "limit" => 20, "offset" => $offset);

        $view_data["can_add_remove_project_members"] = $this->can_add_remove_project_members();
        $view_data["can_access_clients"] = $this->can_access_clients(true);

        $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("projects", $project_id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();


        //count total worked hours
        $options = array("project_id" => $project_id);

        //get allowed member ids
        $members = $this->_get_members_to_manage_timesheet();
        if ($members != "all") {
            //if user has permission to access all members, query param is not required
            $options["allowed_members"] = $members;
        }

        $info = $this->Timesheets_model->count_total_time($options);
        $view_data["total_project_hours"] = to_decimal_format($info->timesheet_total / 60 / 60);

        return $this->template->view('projects/overview', $view_data);
    }

    /* add-remove start mark from project */

    function add_remove_star($project_id, $type = "add")
    {
        if ($project_id) {
            validate_numeric_value($project_id);

            if (get_setting("disable_access_favorite_project_option_for_clients") && $this->login_user->user_type == "client") {
                app_redirect("forbidden");
            }

            $view_data["project_id"] = $project_id;

            if ($type === "add") {
                $this->Projects_model->add_remove_star($project_id, $this->login_user->id, $type = "add");
                return $this->template->view('projects/star/starred', $view_data);
            } else {
                $this->Projects_model->add_remove_star($project_id, $this->login_user->id, $type = "remove");
                return $this->template->view('projects/star/not_starred', $view_data);
            }
        }
    }

    /* load project overview section */

    function overview_for_client($project_id)
    {
        validate_numeric_value($project_id);
        if ($this->login_user->user_type === "client") {
            $view_data = $this->_get_project_info_data($project_id);

            $view_data['project_id'] = $project_id;

            $offset = 0;
            $view_data['offset'] = $offset;
            $view_data['show_activity'] = false;
            $view_data['show_overview'] = false;
            $view_data['activity_logs_params'] = array();

            $this->init_project_permission_checker($project_id);
            $this->init_project_settings($project_id);
            $view_data["show_timesheet_info"] = $this->can_view_timesheet($project_id);

            $options = array("project_id" => $project_id);
            $timesheet_info = $this->Timesheets_model->count_total_time($options);
            $view_data["total_project_hours"] = to_decimal_format($timesheet_info->timesheet_total / 60 / 60);

            if (get_setting("client_can_view_overview")) {
                $view_data['show_overview'] = true;
                $view_data["task_statuses"] = $this->Tasks_model->get_task_statistics(array("project_id" => $project_id))->task_statuses;

                if (get_setting("client_can_view_activity")) {
                    $view_data['show_activity'] = true;
                    $view_data['activity_logs_params'] = array("log_for" => "project", "log_for_id" => $project_id, "limit" => 20, "offset" => $offset);
                }
            }

            $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("projects", $project_id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

            return $this->template->view('projects/overview_for_client', $view_data);
        }
    }

    /* load project members add/edit modal */

    function project_partner_modal_form()
    {
        $view_data['model_info'] = $this->Project_partners_model->get_one($this->request->getPost('id'));
        $project_id = $this->request->getPost('project_id') ? $this->request->getPost('project_id') : $view_data['model_info']->project_id;
        $this->init_project_permission_checker($project_id);

        if (!$this->can_add_remove_project_members()) {
            app_redirect("forbidden");
        }

        $view_data['project_id'] = $project_id;

        $view_data["view_type"] = $this->request->getPost("view_type");
        $view_data['known_referrals'] = $this->Clients_model->get_details(array('partner_type' => 'referral', 'account_type' => '3'), true);

        $partners_dropdown = array();
        $users = $this->Project_partners_model->get_rest_partners_for_a_project($project_id)->getResult();
        foreach ($users as $user) {
            $partners_dropdown[$user->id] = $user->member_name . ' (' . ucwords($user->partner_type) . ')';
        }

        $view_data["partners_dropdown"] = $partners_dropdown;

        return $this->template->view('projects/partners/modal_form', $view_data);
    }

    /* load project members add/edit modal */

    function project_member_modal_form()
    {
        $view_data['model_info'] = $this->Project_members_model->get_one($this->request->getPost('id'));
        $project_id = $this->request->getPost('project_id') ? $this->request->getPost('project_id') : $view_data['model_info']->project_id;
        $this->init_project_permission_checker($project_id);

        if (!$this->can_add_remove_project_members()) {
            app_redirect("forbidden");
        }

        $view_data['project_id'] = $project_id;

        $view_data["view_type"] = $this->request->getPost("view_type");

        $add_user_type = $this->request->getPost("add_user_type");

        $users_dropdown = array();
        if ($add_user_type == "client_contacts") {
            if (!$this->can_access_clients(true)) {
                app_redirect("forbidden");
            }

            $contacts = $this->Project_members_model->get_client_contacts_of_the_project_client($project_id)->getResult();
            foreach ($contacts as $contact) {
                $users_dropdown[$contact->id] = $contact->contact_name;
            }
        } else {
            $users = $this->Project_members_model->get_rest_team_members_for_a_project($project_id)->getResult();
            foreach ($users as $user) {
                $users_dropdown[$user->id] = $user->member_name;
            }
        }

        $view_data["users_dropdown"] = $users_dropdown;
        $view_data["add_user_type"] = $add_user_type;

        return $this->template->view('projects/project_members/modal_form', $view_data);
    }

    /* add a project members  */

    function save_project_deadline()
    {
        $project_id = $this->request->getPost('project_id');
        $deadline = $this->request->getPost('deadline');
        $start_date = $this->request->getPost('start_date');
        $note = $this->request->getPost('note');

        $this->init_project_permission_checker($project_id);

        if (is_dev_mode()) {
            $this->validate_submitted_data(array(
                'deadline' => 'required',
                'start_date' => 'required',
                'note' => 'required'
            ));
        } else {
            $this->validate_submitted_data(array(
                'deadline' => 'required',
                'note' => 'required'
            ));
        }

        $project_data = array('deadline' => $deadline);
        if (is_dev_mode()) {
            $project_data['start_date'] = $start_date;
        }

        $save_id = $this->Projects_model->ci_save($project_data, $project_id);

        $note_data = array(
            'created_by' => $this->login_user->id,
            'created_at' => get_current_utc_time(),
            'title' => 'Changed the deadline to ' . date_format(new \DateTime($deadline), 'l , F d Y'),
            'description' => $note,
            'project_id' => $project_id,
            'milestone_id' => $this->get_current_milestone_id($project_id),
            'is_public' => 1
        );

        $this->Notes_model->ci_save($note_data);

        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => 'success'));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function save_milestone_deadline()
    {
        $project_id = $this->request->getPost('project_id');
        $milestone_id = $this->request->getPost('id');
        $due_date = $this->request->getPost('due_date');
        $note = $this->request->getPost('note');

        $this->init_project_permission_checker($project_id);

        if (!$this->can_add_remove_project_members()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "required",
            'due_date' => 'required',
            'note' => 'required'
        ));

        $milestone_data = array('due_date' => $due_date);
        $save_id = $this->Milestones_model->ci_save($milestone_data, $milestone_id);

        $note_data = array(
            'created_by' => $this->login_user->id,
            'created_at' => get_current_utc_time(),
            'title' => 'Changed the deadline to ' . date_format(new \DateTime($due_date), 'l , F d Y'),
            'description' => $note,
            'project_id' => $project_id,
            'milestone_id' => $milestone_id,
            'is_public' => 1
        );

        $this->Notes_model->ci_save($note_data);

        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => 'success', 'due_date' => $due_date));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* add a project members  */

    function save_project_partner()
    {
        $project_id = $this->request->getPost('project_id');

        $this->init_project_permission_checker($project_id);

        if (!$this->can_add_remove_project_members()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "partner_id.*" => "required",
            // "commission.*" => "required"
        ));

        $partner_ids = $this->request->getPost('partner_id');
        $commissions = $this->request->getPost('commission');

        $save_ids = array();
        $already_exists = false;

        if ($partner_ids) {
            for ($x = 0; $x < count($partner_ids); $x++) {
                $partner_id = $partner_ids[$x];
                $commission = get_array_value($commissions, $x);
                if ($partner_id) {
                    $client_info = $this->Clients_model->get_one($partner_id);
                    $full_name = $this->get_client_full_name(0, $client_info);
                    $data = array(
                        "project_id" => $project_id,
                        "partner_id" => $partner_id,
                        "full_name" => $full_name,
                        'commission' => $commission ? $commission : 0,
                        'partner_type' => $client_info->partner_type,
                        'created_date' => get_current_utc_time(),
                        'deleted' => 0,
                    );

                    $save_id = $this->Project_partners_model->save_partner($data);

                    $this->_handle_partners_revenue($project_id);

                    if ($save_id && $save_id != "exists") {
                        $save_ids[] = $save_id;
                        // log_notification("project_member_added", array("project_id" => $project_id, "to_user_id" => $partner_id));
                    } else if ($save_id === "exists") {
                        $already_exists = true;
                    }
                }
            }
        }


        if (!count($save_ids) && $already_exists) {
            //this member already exists.
            echo json_encode(array("success" => true, 'id' => "exists"));
        } else if (count($save_ids)) {
            $project_member_row = array();
            foreach ($save_ids as $id) {
                $project_member_row[] = $this->_project_partner_row_data($id);
            }
            echo json_encode(array("success" => true, "data" => $project_member_row, 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* add a project members  */

    function save_project_member()
    {
        $project_id = $this->request->getPost('project_id');

        $this->init_project_permission_checker($project_id);

        if (!$this->can_add_remove_project_members()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "user_id.*" => "required"
        ));

        $user_ids = $this->request->getPost('user_id');

        $save_ids = array();
        $already_exists = false;

        if ($user_ids) {
            foreach ($user_ids as $user_id) {
                if ($user_id) {
                    $data = array(
                        "project_id" => $project_id,
                        "user_id" => $user_id
                    );

                    $save_id = $this->Project_members_model->save_member($data);
                    if ($save_id && $save_id != "exists") {
                        $save_ids[] = $save_id;
                        log_notification("project_member_added", array("project_id" => $project_id, "to_user_id" => $user_id));
                    } else if ($save_id === "exists") {
                        $already_exists = true;
                    }
                }
            }
        }


        if (!count($save_ids) && $already_exists) {
            //this member already exists.
            echo json_encode(array("success" => true, 'id' => "exists"));
        } else if (count($save_ids)) {
            $project_member_row = array();
            foreach ($save_ids as $id) {
                $project_member_row[] = $this->_project_member_row_data($id);
            }
            echo json_encode(array("success" => true, "data" => $project_member_row, 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete/undo a project members  */

    function delete_project_partner()
    {
        $id = $this->request->getPost('id');
        $project_partner_info = $this->Project_partners_model->get_one($id);

        $this->init_project_permission_checker($project_partner_info->project_id);
        if (!$this->can_add_remove_project_members()) {
            app_redirect("forbidden");
        }

        $this->_handle_partners_revenue($project_partner_info->project_id);

        if ($this->request->getPost('undo')) {
            if ($this->Project_partners_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_project_partner_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Project_partners_model->delete($id)) {

                // $project_member_info = $this->Project_members_model->get_one($id);

                // log_notification("project_member_deleted", array("project_id" => $project_member_info->project_id, "to_user_id" => $project_member_info->user_id));
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    function delete_project_member()
    {
        $id = $this->request->getPost('id');
        $project_member_info = $this->Project_members_model->get_one($id);

        $this->init_project_permission_checker($project_member_info->project_id);
        if (!$this->can_add_remove_project_members()) {
            app_redirect("forbidden");
        }


        if ($this->request->getPost('undo')) {
            if ($this->Project_members_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_project_member_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Project_members_model->delete($id)) {

                $project_member_info = $this->Project_members_model->get_one($id);

                log_notification("project_member_deleted", array("project_id" => $project_member_info->project_id, "to_user_id" => $project_member_info->user_id));
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of project members, prepared for datatable  */

    function project_partner_list_data($project_id = 0, $user_type = "")
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();
        $this->init_project_permission_checker($project_id);

        //show the message icon to client contacts list only if the user can send message to client. 
        $can_send_message_to_client = false;
        $client_message_users = get_setting("client_message_users");
        $client_message_users_array = explode(",", $client_message_users);
        if (in_array($this->login_user->id, $client_message_users_array)) {

            $can_send_message_to_client = true;
        }

        $options = array("project_id" => $project_id);
        $list_data = $this->Project_partners_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_project_partner_row($data, $can_send_message_to_client);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of project members, prepared for datatable  */

    function project_member_list_data($project_id = 0, $user_type = "")
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();
        $this->init_project_permission_checker($project_id);

        //show the message icon to client contacts list only if the user can send message to client. 
        $can_send_message_to_client = false;
        $client_message_users = get_setting("client_message_users");
        $client_message_users_array = explode(",", $client_message_users);
        if (in_array($this->login_user->id, $client_message_users_array)) {

            $can_send_message_to_client = true;
        }

        $options = array("project_id" => $project_id, "user_type" => $user_type, "show_user_wise" => true);
        $list_data = $this->Project_members_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_project_member_row($data, $can_send_message_to_client);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of project member list */

    private function _project_partner_row_data($id)
    {
        $options = array("id" => $id);
        $data = $this->Project_partners_model->get_details($options)->getRow();
        return $this->_make_project_partner_row($data);
    }

    /* return a row of project member list */

    private function _project_member_row_data($id)
    {
        $options = array("id" => $id);
        $data = $this->Project_members_model->get_details($options)->getRow();
        return $this->_make_project_member_row($data);
    }

    /* prepare a row of project member list */
    private function _make_project_partner_row($data, $can_send_message_to_client = false)
    {
        $member_image = "<span class='avatar avatar-sm'><img src='" . get_avatar('') . "' alt='...'></span> ";

        $member = get_client_contact_profile_link($data->partner_id, $member_image, array(), array('account_type' => 3));
        $member_name = get_client_contact_profile_link($data->partner_id, $this->get_client_full_name($data->partner_id), array("class" => "dark strong"), array('account_type' => 3));

        $link = "";

        //check message module availability and show message button
        if (get_setting("module_message") && ($this->login_user->client_id != $data->partner_id)) {
            $user_options = array('client_id' => $data->partner_id, 'is_primary_contact' => 1);
            $primary_contact = $this->Users_model->get_details($user_options)->getRow();
            if ($primary_contact) {
                $link = modal_anchor(get_uri("messages/modal_form/" . $primary_contact->id), "<i data-feather='mail' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('send_message')));
            }
        }

        //check message icon permission for client contacts
        if (!$can_send_message_to_client) {
            $link = "";
        }

        if ($this->can_add_remove_project_members()) {
            $delete_link = js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_member'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("projects/delete_project_partner"), "data-action" => "delete", "data-reload-on-success" => true));

            if (!$this->can_manage_all_projects() && ($this->login_user->client_id === $data->partner_id)) {
                $delete_link = "";
            }
            $link .= $delete_link;
        }

        $label = $this->_get_project_partner_type_pill($data->partner_id);

        $member = '<div class="d-flex"><div class="p-2 flex-shrink-1">' . $member . '</div><div class="p-2 w-100"><div>' . $member_name . ' ' . $label . '</div><label class="text-off">Commission: ' . $data->commission . '%</label></div></div>';

        return array($member, $link);
    }

    private function _make_project_payment_schedule_row($data)
    {
        $row_fee_type = '';
        $row_fees = '';
        // $claimable = '<span class="badge" style="background-color: #5DADE2;"> ' . app_lang('non_claimable') . '</span>';
        if ($data->fees) {
            $fees = unserialize($data->fees);
            if ($fees) {
                $fees = json_decode(json_encode($fees), true);
                $count = 1;
                foreach ($fees as $fee) {
                    // if (get_array_value($fee, 'is_claimable')) {
                    //     $claimable = '<span class="badge" style="background-color: #198754;"> ' . app_lang('claimable') . '</span>';
                    // }
                    $row_fee_type .= '<small>• ' . $fee['fee_type'] . '</small>';
                    $row_fees .= '<small>' . to_currency($fee['amount']) . '</small>';
                    if ($count < count($fees)) {
                        $row_fee_type .= '<br>';
                        $row_fees .= '<br>';
                    }
                    $count++;
                }
            }
        }

        $status = $this->_get_payment_installment_status_pill($data->status);

        $options = $this->_make_payment_schedule_options_dropdown($data);


        $claimable = '<span class="badge" style="background-color: #5DADE2;"> ' . app_lang('non_claimable') . '</span>';

        if ((int)$data->is_claimable) {
            $claimable = '<span class="badge" style="background-color: #198754;"> ' . app_lang('claimable') . '</span>';
        }

        $row_data = array(
            $data->sort,
            '<p>' . $data->installment_name . '<br>' . $claimable . '</p>',
            $row_fee_type,
            $row_fees,
            to_currency((float)$data->net_fee + (float)$data->discount),
            to_currency((float)$data->discount),
            format_to_date($data->invoice_date),
            $status,
            $options
        );

        return $row_data;
    }

    //prepare options dropdown for invoices list
    private function _make_payment_schedule_options_dropdown($data)
    {

        $preview_invoice = '';
        if ($data->invoice_id) {
            $preview_invoice = anchor_popup(get_uri("invoices/preview/" . $data->invoice_id . '/0'), "<i data-feather='eye' class='icon-16'></i> " . app_lang('preview'), array("class" => "dropdown-item", "title" => app_lang('view_invoice')));
        }

        $edit = '';
        $delete = '';
        $add_invoice = '';
        if ($data->status == 0 || is_dev_mode()) {
            $edit = '<li role="presentation">' . modal_anchor(get_uri("projects/schedule_modal_form"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit'), array("class" => "dropdown-item", "title" => app_lang('edit_schedule'), 'data-post-schedule_id' => $data->id, 'data-post-project_id' => $data->project_id, 'data-modal-lg' => true)) . '</li>';
            $delete = '<li role="presentation">' . js_anchor("<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array('title' => app_lang('delete_schedule'), "class" => "delete dropdown-item", "data-id" => $data->id, "data-action-url" => get_uri("projects/delete_schedule"), "data-action" => "delete-confirmation")) . '</li>';
            $add_invoice = '<li role="presentation">' . modal_anchor(get_uri("invoices/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_invoice'), array("class" => "dropdown-item", "title" => app_lang('add_invoice'), 'data-post-schedule_id' => $data->id, 'data-post-project_id' => $data->project_id)) . '</li>';
        }

        return $data->status != 0 && $preview_invoice == '' ? "<span class='p15 inline-block'></span>" : '<span class="dropdown inline-block">
                    <button class="btn btn-default dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
                        <i data-feather="tool" class="icon-16"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu">' . $preview_invoice . $add_invoice . $edit . $delete . '</ul>
                </span>';
    }
    /* prepare a row of project member list */

    private function _make_project_member_row($data, $can_send_message_to_client = false)
    {
        $member_image = "<span class='avatar avatar-sm'><img src='" . get_avatar($data->member_image) . "' alt='...'></span> ";

        if ($data->user_type == "staff") {
            $member = get_team_member_profile_link($data->user_id, $member_image);
            $member_name = get_team_member_profile_link($data->user_id, $data->member_name, array("class" => "dark strong"));
        } else {
            $member = get_client_contact_profile_link($data->user_id, $member_image);
            $member_name = get_client_contact_profile_link($data->user_id, $data->member_name, array("class" => "dark strong"));
        }

        $link = "";

        //check message module availability and show message button
        if (get_setting("module_message") && ($this->login_user->id != $data->user_id)) {
            $link = modal_anchor(get_uri("messages/modal_form/" . $data->user_id), "<i data-feather='mail' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('send_message')));
        }

        //check message icon permission for client contacts
        if (!$can_send_message_to_client && $data->user_type === "client") {
            $link = "";
        }


        if ($this->can_add_remove_project_members()) {
            $delete_link = js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_member'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("projects/delete_project_member"), "data-action" => "delete"));

            if (!$this->can_manage_all_projects() && ($this->login_user->id === $data->user_id)) {
                $delete_link = "";
            }
            $link .= $delete_link;
        }

        $member = '<div class="d-flex"><div class="p-2 flex-shrink-1">' . $member . '</div><div class="p-2 w-100"><div>' . $member_name . '</div><label class="text-off">' . $data->job_title . '</label></div></div>';

        return array($member, $link);
    }

    //stop timer note modal
    function stop_timer_modal_form($project_id)
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();

        if ($project_id) {
            $view_data["project_id"] = $project_id;
            $view_data["tasks_dropdown"] = $this->_get_timesheet_tasks_dropdown($project_id);

            $options = array(
                "project_id" => $project_id,
                "task_status_id" => 2,
                "assigned_to" => $this->login_user->id
            );

            $task_info = $this->Tasks_model->get_details($options)->getRow();

            $open_task_id = $this->request->getPost("task_id");

            $task_id = "";
            if ($open_task_id) {
                $task_id = $open_task_id;
            } else if ($task_info) {
                $task_id = $task_info->id;
            }

            $view_data["open_task_id"] = $open_task_id;
            $view_data["task_id"] = $task_id;

            return $this->template->view('projects/timesheets/stop_timer_modal_form', $view_data);
        }
    }

    private function _get_timesheet_tasks_dropdown($project_id, $return_json = false)
    {
        $tasks_dropdown = array("" => "-");
        $tasks_dropdown_json = array(array("id" => "", "text" => "- " . app_lang("task") . " -"));

        $show_assigned_tasks_only_user_id = $this->show_assigned_tasks_only_user_id();
        if (!$show_assigned_tasks_only_user_id) {
            $timesheet_manage_permission = get_array_value($this->login_user->permissions, "timesheet_manage_permission");
            if (!$timesheet_manage_permission || $timesheet_manage_permission === "own") {
                //show only own tasks when the permission is no/own
                $show_assigned_tasks_only_user_id = $this->login_user->id;
            }
        }

        $options = array(
            "project_id" => $project_id,
            "show_assigned_tasks_only_user_id" => $show_assigned_tasks_only_user_id
        );

        $tasks = $this->Tasks_model->get_details($options)->getResult();

        foreach ($tasks as $task) {
            $tasks_dropdown_json[] = array("id" => $task->id, "text" => $task->id . " - " . $task->title);
            $tasks_dropdown[$task->id] = $task->id . " - " . $task->title;
        }

        if ($return_json) {
            return json_encode($tasks_dropdown_json);
        } else {
            return $tasks_dropdown;
        }
    }

    /* start/stop project timer */

    function timer($project_id, $timer_status = "start")
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();
        $note = $this->request->getPost("note");
        $task_id = $this->request->getPost("task_id");

        if ($timer_status == 'start') {
            $project_info = $this->Projects_model->get_one($project_id);
            if ($project_info && !$project_info->start_date) {
                $project_data = array('start_date' => get_current_utc_time());
                $this->Projects_model->ci_save($project_data, $project_id);
            }
        }

        $data = array(
            "project_id" => $project_id,
            "user_id" => $this->login_user->id,
            "status" => $timer_status,
            "note" => $note ? $note : "",
            "task_id" => $task_id ? $task_id : 0,
        );

        $user_has_any_timer_except_this_project = $this->Timesheets_model->user_has_any_timer_except_this_project($project_id, $this->login_user->id);

        $user_has_any_open_timer_on_this_task = false;

        if ($task_id) {
            $user_has_any_open_timer_on_this_task = $this->Timesheets_model->user_has_any_open_timer_on_this_task($task_id, $this->login_user->id);
        }

        if ($timer_status == "start" && $user_has_any_timer_except_this_project && !get_setting("users_can_start_multiple_timers_at_a_time")) {
            app_redirect("forbidden");
        } else if ($timer_status == "start" && $user_has_any_open_timer_on_this_task) {
            app_redirect("forbidden");
        }

        $this->Timesheets_model->process_timer($data);
        if ($timer_status === "start") {
            if ($this->request->getPost("task_timer")) {
                echo modal_anchor(get_uri("projects/stop_timer_modal_form/" . $project_id), "<i data-feather='clock' class='icon-16'></i> " . app_lang('stop_timer'), array("class" => "btn btn-danger", "title" => app_lang('stop_timer'), "data-post-task_id" => $task_id));
            } else {
                $view_data = $this->_get_project_info_data($project_id);
                return $this->template->view('projects/project_timer', $view_data);
            }
        } else {
            echo json_encode(array("success" => true));
        }
    }

    /* load timesheets view for a project */

    function timesheets($project_id)
    {
        validate_numeric_value($project_id);

        $this->init_project_permission_checker($project_id);
        $this->init_project_settings($project_id); //since we'll check this permission project wise


        if (!$this->can_view_timesheet($project_id)) {
            app_redirect("forbidden");
        }

        $view_data['project_id'] = $project_id;

        //client can't add log or update settings
        $view_data['can_add_log'] = false;

        if ($this->login_user->user_type === "staff") {
            $view_data['can_add_log'] = true;
        }

        $view_data['project_members_dropdown'] = json_encode($this->_get_project_members_dropdown_list_for_filter($project_id));
        $view_data['tasks_dropdown'] = $this->_get_timesheet_tasks_dropdown($project_id, true);

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("timesheets", $this->login_user->is_admin, $this->login_user->user_type);
        $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("timesheets", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data["show_members_dropdown"] = true;
        $timesheet_access_info = $this->get_access_info("timesheet_manage_permission");
        $timesheet_access_type = $timesheet_access_info->access_type;

        if (!$timesheet_access_type || $timesheet_access_type === "own") {
            $view_data["show_members_dropdown"] = false;
        }

        return $this->template->view("projects/timesheets/index", $view_data);
    }

    /* prepare project members dropdown */

    private function _get_project_members_dropdown_list_for_filter($project_id)
    {

        $project_members = $this->Project_members_model->get_project_members_dropdown_list($project_id)->getResult();
        $project_members_dropdown = array(array("id" => "", "text" => "- " . app_lang("member") . " -"));
        foreach ($project_members as $member) {
            $project_members_dropdown[] = array("id" => $member->user_id, "text" => $member->member_name);
        }
        return $project_members_dropdown;
    }

    /* load timelog add/edit modal */

    function timelog_modal_form()
    {
        $this->access_only_team_members();
        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $model_info = $this->Timesheets_model->get_one($this->request->getPost('id'));
        $project_id = $this->request->getPost('project_id') ? $this->request->getPost('project_id') : $model_info->project_id;

        //set the login user as a default selected member
        if (!$model_info->user_id) {
            $model_info->user_id = $this->login_user->id;
        }

        //get related data
        $related_data = $this->_prepare_all_related_data_for_timelog($project_id);
        $show_porject_members_dropdown = get_array_value($related_data, "show_porject_members_dropdown");
        $view_data["tasks_dropdown"] = get_array_value($related_data, "tasks_dropdown");
        $view_data["project_members_dropdown"] = get_array_value($related_data, "project_members_dropdown");

        $view_data["model_info"] = $model_info;

        if ($model_info->id) {
            $show_porject_members_dropdown = false; //don't allow to edit the user on update.
        }

        $view_data["project_id"] = $project_id;
        $view_data['show_porject_members_dropdown'] = $show_porject_members_dropdown;
        $view_data["projects_dropdown"] = $this->_get_projects_dropdown();

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("timesheets", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        return $this->template->view('projects/timesheets/modal_form', $view_data);
    }

    private function _prepare_all_related_data_for_timelog($project_id = 0)
    {
        //we have to check if any defined project exists, then go through with the project id
        $show_porject_members_dropdown = false;
        if ($project_id) {
            $tasks_dropdown = $this->_get_timesheet_tasks_dropdown($project_id, true);

            //prepare members dropdown list
            $allowed_members = $this->_get_members_to_manage_timesheet();
            $project_members = "";

            if ($allowed_members === "all") {
                $project_members = $this->Project_members_model->get_project_members_dropdown_list($project_id)->getResult(); //get all members of this project
            } else {
                $project_members = $this->Project_members_model->get_project_members_dropdown_list($project_id, $allowed_members)->getResult();
            }

            $project_members_dropdown = array();
            if ($project_members) {
                foreach ($project_members as $member) {

                    if ($member->user_id !== $this->login_user->id) {
                        $show_porject_members_dropdown = true; //user can manage other users time.
                    }

                    $project_members_dropdown[] = array("id" => $member->user_id, "text" => $member->member_name);
                }
            }
        } else {
            //we have show an empty dropdown when there is no project_id defined
            $tasks_dropdown = json_encode(array(array("id" => "", "text" => "-")));
            $project_members_dropdown = array(array("id" => "", "text" => "-"));
            $show_porject_members_dropdown = true;
        }

        return array(
            "project_members_dropdown" => $project_members_dropdown,
            "tasks_dropdown" => $tasks_dropdown,
            "show_porject_members_dropdown" => $show_porject_members_dropdown
        );
    }

    function get_all_related_data_of_selected_project_for_timelog($project_id = "")
    {
        validate_numeric_value($project_id);
        if ($project_id) {
            $related_data = $this->_prepare_all_related_data_for_timelog($project_id);

            echo json_encode(array(
                "project_members_dropdown" => get_array_value($related_data, "project_members_dropdown"),
                "tasks_dropdown" => json_decode(get_array_value($related_data, "tasks_dropdown"))
            ));
        }
    }

    /* insert/update a timelog */

    function save_timelog()
    {
        $this->access_only_team_members();
        $id = $this->request->getPost('id');

        $start_date_time = "";
        $end_date_time = "";
        $hours = "";

        $start_time = $this->request->getPost('start_time');
        $end_time = $this->request->getPost('end_time');
        $note = $this->request->getPost("note");
        $task_id = $this->request->getPost("task_id");

        if ($start_time) {
            //start time and end time mode
            //convert to 24hrs time format
            if (get_setting("time_format") != "24_hours") {
                $start_time = convert_time_to_24hours_format($start_time);
                $end_time = convert_time_to_24hours_format($end_time);
            }

            //join date with time
            $start_date_time = $this->request->getPost('start_date') . " " . $start_time;
            $end_date_time = $this->request->getPost('end_date') . " " . $end_time;

            //add time offset
            $start_date_time = convert_date_local_to_utc($start_date_time);
            $end_date_time = convert_date_local_to_utc($end_date_time);
        } else {
            //date and hour mode
            $date = $this->request->getPost("date");
            $start_date_time = $date . " 00:00:00";
            $end_date_time = $date . " 00:00:00";

            //prepare hours
            $hours = convert_humanize_data_to_hours($this->request->getPost("hours"));
            if (!$hours) {
                echo json_encode(array("success" => false, 'message' => app_lang("hour_log_time_error_message")));
                return false;
            }
        }

        $project_id = $this->request->getPost('project_id');
        $data = array(
            "project_id" => $project_id,
            "start_time" => $start_date_time,
            "end_time" => $end_date_time,
            "note" => $note ? $note : "",
            "task_id" => $task_id ? $task_id : 0,
            "hours" => $hours
        );

        //save user_id only on insert and it will not be editable
        if (!$id) {
            //insert mode
            $data["user_id"] = $this->request->getPost('user_id') ? $this->request->getPost('user_id') : $this->login_user->id;
        }

        $this->check_timelog_update_permission($id, $project_id, get_array_value($data, "user_id"));

        $save_id = $this->Timesheets_model->ci_save($data, $id);
        if ($save_id) {

            save_custom_fields("timesheets", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_timesheet_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete/undo a timelog */

    function delete_timelog()
    {
        $this->access_only_team_members();

        $id = $this->request->getPost('id');

        $this->check_timelog_update_permission($id);

        if ($this->request->getPost('undo')) {
            if ($this->Timesheets_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_timesheet_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Timesheets_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    private function check_timelog_update_permission($log_id = null, $project_id = null, $user_id = null)
    {
        if ($log_id) {
            $info = $this->Timesheets_model->get_one($log_id);
            $user_id = $info->user_id;
        }

        if (!$log_id && $user_id === $this->login_user->id) { //adding own timelogs
            return true;
        }

        $members = $this->_get_members_to_manage_timesheet();

        if ($members === "all") {
            return true;
        } else if (is_array($members) && count($members) && in_array($user_id, $members)) {
            //permission: no / own / specific / specific_excluding_own
            $timesheet_manage_permission = get_array_value($this->login_user->permissions, "timesheet_manage_permission");

            if (!$timesheet_manage_permission && $log_id) { //permission: no
                app_redirect("forbidden");
            }

            if ($timesheet_manage_permission === "specific_excluding_own" && $log_id && $user_id === $this->login_user->id) { //permission: specific_excluding_own
                app_redirect("forbidden");
            }

            //permission: own / specific
            return true;
        } else if ($members === "own_project_members" || $members === "own_project_members_excluding_own") {
            if (!$project_id) { //there has $log_id or $project_id
                $project_id = $info->project_id;
            }

            if ($this->Project_members_model->is_user_a_project_member($project_id, $user_id) || $this->Project_members_model->is_user_a_project_member($project_id, $this->login_user->id)) { //check if the login user and timelog user is both on same project
                if ($members === "own_project_members") {
                    return true;
                } else if ($this->login_user->id !== $user_id) {
                    //can't edit own but can edit other user's of project
                    //no need to check own condition here for new timelogs since it's already checked before
                    return true;
                }
            }
        }

        app_redirect("forbidden");
    }

    /* list of timesheets, prepared for datatable  */

    function timesheet_list_data()
    {

        $project_id = $this->request->getPost("project_id");

        $this->init_project_permission_checker($project_id);
        $this->init_project_settings($project_id); //since we'll check this permission project wise

        if (!$this->can_view_timesheet($project_id, true)) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("timesheets", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "project_id" => $project_id,
            "status" => "none_open",
            "user_id" => $this->request->getPost("user_id"),
            "start_date" => $this->request->getPost("start_date"),
            "end_date" => $this->request->getPost("end_date"),
            "task_id" => $this->request->getPost("task_id"),
            "client_id" => $this->request->getPost("client_id"),
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("timesheets", $this->login_user->is_admin, $this->login_user->user_type)
        );

        //get allowed member ids
        $members = $this->_get_members_to_manage_timesheet();
        if ($members != "all" && $this->login_user->user_type == "staff") {
            //if user has permission to access all members, query param is not required
            //client can view all timesheet
            $options["allowed_members"] = $members;
        }

        $all_options = append_server_side_filtering_commmon_params($options);

        $result = $this->Timesheets_model->get_details($all_options);

        //by this, we can handel the server side or client side from the app table prams.
        if (get_array_value($all_options, "server_side")) {
            $list_data = get_array_value($result, "data");
        } else {
            $list_data = $result->getResult();
            $result = array();
        }

        $result_data = array();
        foreach ($list_data as $data) {
            $result_data[] = $this->_make_timesheet_row($data, $custom_fields);
        }

        $result["data"] = $result_data;

        echo json_encode($result);
    }

    /* return a row of timesheet list  table */

    private function _timesheet_row_data($id)
    {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("timesheets", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Timesheets_model->get_details($options)->getRow();
        return $this->_make_timesheet_row($data, $custom_fields);
    }

    /* prepare a row of timesheet list table */

    private function _make_timesheet_row($data, $custom_fields)
    {
        $image_url = get_avatar($data->logged_by_avatar);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->logged_by_user";

        $start_time = $data->start_time;
        $end_time = $data->end_time;
        $project_title = anchor(get_uri("projects/view/" . $data->project_id), $data->project_title);
        $task_title = modal_anchor(get_uri("tasks/view"), $data->task_title, array("title" => app_lang('task_info') . " #$data->task_id", "data-post-id" => $data->task_id, "data-modal-lg" => "1"));

        $client_name = "-";
        if ($data->timesheet_client_company_name) {
            $route_prefix = $this->get_client_view_route($data->timesheet_client_id);
            $client_name = anchor(get_uri("$route_prefix/view/" . $data->timesheet_client_id), $data->timesheet_client_company_name);
        }

        $duration = convert_seconds_to_time_format($data->hours ? (round(($data->hours * 60), 0) * 60) : (abs(strtotime($end_time) - strtotime($start_time))));

        $row_data = array(
            get_team_member_profile_link($data->user_id, $user),
            $project_title,
            $client_name,
            $task_title,
            $data->start_time,
            ($data->hours || get_setting("users_can_input_only_total_hours_instead_of_period")) ? format_to_date($data->start_time) : format_to_datetime($data->start_time),
            $data->end_time,
            $data->hours ? format_to_date($data->end_time) : format_to_datetime($data->end_time),
            $duration,
            to_decimal_format(convert_time_string_to_decimal($duration)),
            $data->note
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $options = modal_anchor(get_uri("projects/timelog_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_timelog'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_timelog'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("projects/delete_timelog"), "data-action" => "delete"));

        $timesheet_manage_permission = get_array_value($this->login_user->permissions, "timesheet_manage_permission");
        if ($data->user_id === $this->login_user->id && ($timesheet_manage_permission === "own_project_members_excluding_own" || $timesheet_manage_permission === "specific_excluding_own")) {
            $options = "";
        }

        $row_data[] = $options;

        return $row_data;
    }

    /* load timesheets summary view for a project */

    function timesheet_summary($project_id)
    {
        validate_numeric_value($project_id);

        $this->init_project_permission_checker($project_id);
        $this->init_project_settings($project_id); //since we'll check this permission project wise

        if (!$this->can_view_timesheet($project_id)) {
            app_redirect("forbidden");
        }



        $view_data['project_id'] = $project_id;

        $view_data['group_by_dropdown'] = json_encode(
            array(
                array("id" => "", "text" => "- " . app_lang("group_by") . " -"),
                array("id" => "member", "text" => app_lang("member")),
                array("id" => "task", "text" => app_lang("task"))
            )
        );

        $view_data['project_members_dropdown'] = json_encode($this->_get_project_members_dropdown_list_for_filter($project_id));
        $view_data['tasks_dropdown'] = $this->_get_timesheet_tasks_dropdown($project_id, true);
        $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("timesheets", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data["show_members_dropdown"] = true;
        $timesheet_access_info = $this->get_access_info("timesheet_manage_permission");
        $timesheet_access_type = $timesheet_access_info->access_type;

        if (!$timesheet_access_type || $timesheet_access_type === "own") {
            $view_data["show_members_dropdown"] = false;
        }

        return $this->template->view("projects/timesheets/summary_list", $view_data);
    }

    /* list of timesheets summary, prepared for datatable  */

    function timesheet_summary_list_data()
    {

        $project_id = $this->request->getPost("project_id");

        //client can't view all projects timesheet. project id is required.
        if (!$project_id) {
            $this->access_only_team_members();
        }

        if ($project_id) {
            $this->init_project_permission_checker($project_id);
            $this->init_project_settings($project_id); //since we'll check this permission project wise

            if (!$this->can_view_timesheet($project_id, true)) {
                app_redirect("forbidden");
            }
        }


        $group_by = $this->request->getPost("group_by");

        $options = array(
            "project_id" => $project_id,
            "status" => "none_open",
            "user_id" => $this->request->getPost("user_id"),
            "start_date" => $this->request->getPost("start_date"),
            "end_date" => $this->request->getPost("end_date"),
            "task_id" => $this->request->getPost("task_id"),
            "group_by" => $group_by,
            "client_id" => $this->request->getPost("client_id"),
            "custom_field_filter" => $this->prepare_custom_field_filter_values("timesheets", $this->login_user->is_admin, $this->login_user->user_type)
        );

        //get allowed member ids
        $members = $this->_get_members_to_manage_timesheet();
        if ($members != "all" && $this->login_user->user_type == "staff") {
            //if user has permission to access all members, query param is not required
            //client can view all timesheet
            $options["allowed_members"] = $members;
        }

        $list_data = $this->Timesheets_model->get_summary_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {


            $member = "-";
            $task_title = "-";

            if ($group_by != "task") {
                $image_url = get_avatar($data->logged_by_avatar);
                $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->logged_by_user";

                $member = get_team_member_profile_link($data->user_id, $user);
            }

            $project_title = anchor(get_uri("projects/view/" . $data->project_id), $data->project_title);

            if ($group_by != "member") {
                $task_title = modal_anchor(get_uri("tasks/view"), $data->task_title, array("title" => app_lang('task_info') . " #$data->task_id", "data-post-id" => $data->task_id, "data-modal-lg" => "1"));
                if (!$data->task_title) {
                    $task_title = app_lang("not_specified");
                }
            }


            $duration = convert_seconds_to_time_format(abs($data->total_duration));

            $client_name = "-";
            if ($data->timesheet_client_company_name) {
                $route_prefix = $this->get_client_view_route($data->timesheet_client_id);
                $client_name = anchor(get_uri("$route_prefix/view/" . $data->timesheet_client_id), $data->timesheet_client_company_name);
            }

            $result[] = array(
                $project_title,
                $client_name,
                $member,
                $task_title,
                $duration,
                to_decimal_format(convert_time_string_to_decimal($duration))
            );
        }
        echo json_encode(array("data" => $result));
    }

    /* get all projects list */

    private function _get_all_projects_dropdown_list()
    {
        $projects = $this->Projects_model->get_dropdown_list(array("title"));

        $projects_dropdown = array(array("id" => "", "text" => "- " . app_lang("project") . " -"));
        foreach ($projects as $id => $title) {
            $projects_dropdown[] = array("id" => $id, "text" => $title);
        }
        return $projects_dropdown;
    }

    /* get all projects list according to the login user */

    private function _get_all_projects_dropdown_list_for_timesheets_filter()
    {
        $options = array();

        if (!$this->can_manage_all_projects()) {
            $options["user_id"] = $this->login_user->id;
        }

        $projects = $this->Projects_model->get_details($options)->getResult();

        $projects_dropdown = array(array("id" => "", "text" => "- " . app_lang("project") . " -"));
        foreach ($projects as $project) {
            $projects_dropdown[] = array("id" => $project->id, "text" => $project->title);
        }

        return $projects_dropdown;
    }

    /* prepare dropdown list */

    private function _prepare_members_dropdown_for_timesheet_filter($members)
    {
        $where = array("user_type" => "staff");

        if ($members != "all" && is_array($members) && count($members)) {
            $where["where_in"] = array("id" => $members);
        }

        $users = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);

        $members_dropdown = array(array("id" => "", "text" => "- " . app_lang("member") . " -"));
        foreach ($users as $id => $name) {
            $members_dropdown[] = array("id" => $id, "text" => $name);
        }
        return $members_dropdown;
    }

    /* load all time sheets view  */

    function all_timesheets()
    {
        $this->access_only_team_members();
        $members = $this->_get_members_to_manage_timesheet();

        $view_data['members_dropdown'] = json_encode($this->_prepare_members_dropdown_for_timesheet_filter($members));
        $view_data['projects_dropdown'] = json_encode($this->_get_all_projects_dropdown_list_for_timesheets_filter());
        $view_data['clients_dropdown'] = json_encode($this->_get_clients_dropdown());

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("timesheets", $this->login_user->is_admin, $this->login_user->user_type);
        $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("timesheets", $this->login_user->is_admin, $this->login_user->user_type);

        return $this->template->rander("projects/timesheets/all_timesheets", $view_data);
    }

    /* load all timesheets summary view */

    function all_timesheet_summary()
    {
        $this->access_only_team_members();

        $members = $this->_get_members_to_manage_timesheet();

        $view_data['group_by_dropdown'] = json_encode(
            array(
                array("id" => "", "text" => "- " . app_lang("group_by") . " -"),
                array("id" => "member", "text" => app_lang("member")),
                array("id" => "project", "text" => app_lang("project")),
                array("id" => "task", "text" => app_lang("task"))
            )
        );

        $view_data['members_dropdown'] = json_encode($this->_prepare_members_dropdown_for_timesheet_filter($members));
        $view_data['projects_dropdown'] = json_encode($this->_get_all_projects_dropdown_list_for_timesheets_filter());
        $view_data['clients_dropdown'] = json_encode($this->_get_clients_dropdown());
        $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("timesheets", $this->login_user->is_admin, $this->login_user->user_type);

        return $this->template->view("projects/timesheets/all_summary_list", $view_data);
    }

    /* load milestones view */

    function milestones($project_id)
    {
        validate_numeric_value($project_id);
        $this->init_project_permission_checker($project_id);

        if (!$this->can_view_milestones()) {
            app_redirect("forbidden");
        }

        $view_data['project_id'] = $project_id;

        $view_data["can_create_milestones"] = $this->can_create_milestones();
        $view_data["can_edit_milestones"] = $this->can_edit_milestones();
        $view_data["can_delete_milestones"] = $this->can_delete_milestones();

        return $this->template->view("projects/milestones/index", $view_data);
    }

    /* load milestone add/edit modal */

    function milestone_modal_form()
    {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Milestones_model->get_one($this->request->getPost('id'));
        $project_id = $this->request->getPost('project_id') ? $this->request->getPost('project_id') : $view_data['model_info']->project_id;

        $this->init_project_permission_checker($project_id);

        if ($id) {
            if (!$this->can_edit_milestones()) {
                app_redirect("forbidden");
            }
        } else {
            if (!$this->can_create_milestones()) {
                app_redirect("forbidden");
            }
        }

        $view_data['project_id'] = $project_id;

        return $this->template->view('projects/milestones/modal_form', $view_data);
    }

    /* insert/update a milestone */

    function save_milestone()
    {

        $id = $this->request->getPost('id');
        $project_id = $this->request->getPost('project_id');

        $this->init_project_permission_checker($project_id);

        if ($id) {
            if (!$this->can_edit_milestones()) {
                app_redirect("forbidden");
            }
        } else {
            if (!$this->can_create_milestones()) {
                app_redirect("forbidden");
            }
        }

        $options = array('project_id' => $this->request->getPost('project_id'));
        $count = count($this->Milestones_model->get_details($options)->getResult());

        $milestone_id = $this->get_current_milestone_id($project_id);
        $is_current = $this->Milestones_model->get_one($milestone_id);

        $current_options = array('id' => $id);
        $current_milestone = $this->Milestones_model->get_details($current_options)->getRow();

        $is_current_value = 0;
        if (isset($current_milestone)) {
            $is_current_value = $current_milestone->is_current;
        } elseif ($is_current) {
            $is_current_value = 0;
        } else {
            $is_current_value = 1;
        }

        $data = array(
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description'),
            "project_id" => $this->request->getPost('project_id'),
            "due_date" => $this->request->getPost('due_date'),
            "is_current" => $is_current_value,
            'sort' => $current_milestone ? $current_milestone->sort : $count + 1
        );
        $save_id = $this->Milestones_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_milestone_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete/undo a milestone */

    function delete_milestone()
    {

        $id = $this->request->getPost('id');
        $info = $this->Milestones_model->get_one($id);
        $this->init_project_permission_checker($info->project_id);

        if (!$this->can_delete_milestones()) {
            app_redirect("forbidden");
        }

        if ($this->request->getPost('undo')) {
            if ($this->Milestones_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_milestone_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Milestones_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of milestones, prepared for datatable  */

    function milestones_list_data($project_id = 0)
    {
        validate_numeric_value($project_id);
        $this->init_project_permission_checker($project_id);

        $options = array("project_id" => $project_id);
        $list_data = $this->Milestones_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_milestone_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of milestone list  table */

    private function _milestone_row_data($id)
    {
        $data = $this->Milestones_model->get_one($id);
        $this->init_project_permission_checker($data->project_id);

        return $this->_make_milestone_row($data);
    }

    /* prepare a row of milestone list table */

    private function _make_milestone_row($data)
    {
        //calculate milestone progress
        $progress = $data->total_points ? round(($data->completed_points / $data->total_points) * 100) : 0;
        $class = "bg-primary";
        if ($progress == 100) {
            $class = "progress-bar-success";
        }

        $total_tasks = $data->total_tasks ? $data->total_tasks : 0;
        $completed_tasks = $data->completed_tasks ? $data->completed_tasks : 0;

        $progress_bar = "<div class='ml10 mr10 clearfix'><span class='float-start'>$completed_tasks/$total_tasks</span><span class='float-end'>$progress%</span></div><div class='progress mt0' title='$progress%'>
            <div  class='progress-bar $class' role='progressbar' aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100' style='width: $progress%'>
            </div>
        </div>";

        //define milesone color based on due date
        $due_date = date("L", strtotime($data->due_date));
        $label_class = "";
        if ($progress == 100) {
            $label_class = "bg-success";
        } else if ($progress !== 100 && get_my_local_time("Y-m-d") > $data->due_date) {
            $label_class = "bg-danger";
        } else if ($progress !== 100 && get_my_local_time("Y-m-d") == $data->due_date) {
            $label_class = "bg-warning";
        } else {
            $label_class = "bg-primary";
        }

        $day_or_year_name = "";
        if (date("Y", strtotime(get_current_utc_time())) === date("Y", strtotime($data->due_date))) {
            $day_or_year_name = app_lang(strtolower(date("l", strtotime($data->due_date)))); //get day name from language
        } else {
            $day_or_year_name = date("Y", strtotime($data->due_date)); //get current year
        }

        $month_name = app_lang(strtolower(date("F", strtotime($data->due_date)))); //get month name from language

        $due_date = "<div class='milestone float-start' title='" . format_to_date($data->due_date) . "'>
            <span class='badge $label_class'>" . $month_name . "</span>
            <h1>" . date("d", strtotime($data->due_date)) . "</h1>
            <span>" . $day_or_year_name . "</span>
            </div>
            ";

        $optoins = "";
        if ($this->can_edit_milestones()) {
            $optoins .= modal_anchor(get_uri("projects/milestone_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_milestone'), "data-post-id" => $data->id));
        }

        if ($this->can_delete_milestones()) {
            $optoins .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_milestone'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("projects/delete_milestone"), "data-action" => "delete"));
        }


        $title = "<div><b>" . $data->title . "</b></div>";
        if ($data->description) {
            $title .= "<div>" . nl2br($data->description) . "<div>";
        }

        return array(
            $data->due_date,
            $due_date,
            $title,
            $progress_bar,
            $optoins
        );
    }

    /* load comments view */

    function comments($project_id)
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();

        $options = array("project_id" => $project_id, "login_user_id" => $this->login_user->id);
        $view_data['comments'] = $this->Project_comments_model->get_details($options)->getResult();
        $view_data['project_id'] = $project_id;
        $view_data['milestone_id'] = $this->get_current_milestone_id($project_id);
        return $this->template->view("projects/comments/index", $view_data);
    }

    /* load comments view */

    function customer_feedback($project_id)
    {
        if ($this->login_user->user_type == "staff") {
            if (!$this->has_client_feedback_access_permission()) {
                app_redirect("forbidden");
            }
        }

        validate_numeric_value($project_id);
        $options = array("customer_feedback_id" => $project_id, "login_user_id" => $this->login_user->id); //customer feedback id and project id is same
        $view_data['comments'] = $this->Project_comments_model->get_details($options)->getResult();
        $view_data['customer_feedback_id'] = $project_id;
        $view_data['project_id'] = $project_id;
        $view_data['milestone_id'] = $this->get_current_milestone_id($project_id);
        return $this->template->view("projects/comments/index", $view_data);
    }

    /* save project comments */

    function save_comment()
    {
        $id = $this->request->getPost('id');

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "project_comment");

        $project_id = $this->request->getPost('project_id');
        $file_id = $this->request->getPost('file_id');
        $milestone_id = $this->request->getPost('milestone_id');
        $customer_feedback_id = $this->request->getPost('customer_feedback_id');
        $comment_id = $this->request->getPost('comment_id');
        $description = $this->request->getPost('description');

        if ($customer_feedback_id && $this->login_user->user_type == "staff") {
            if (!$this->has_client_feedback_access_permission()) {
                app_redirect("forbidden");
            }
        }

        $data = array(
            "created_by" => $this->login_user->id,
            "created_at" => get_current_utc_time(),
            "project_id" => $project_id,
            "file_id" => $file_id ? $file_id : 0,
            "milestone_id" => $milestone_id ? $milestone_id : 0,
            "task_id" => 0,
            "customer_feedback_id" => $customer_feedback_id ? $customer_feedback_id : 0,
            "comment_id" => $comment_id ? $comment_id : 0,
            "description" => $description
        );

        $data = clean_data($data);

        $data["files"] = $files_data; //don't clean serilized data

        $save_id = $this->Project_comments_model->save_comment($data, $id);
        if ($save_id) {
            $response_data = "";
            $options = array("id" => $save_id, "login_user_id" => $this->login_user->id);

            if ($this->request->getPost("reload_list")) {
                $view_data['comments'] = $this->Project_comments_model->get_details($options)->getResult();
                $response_data = $this->template->view("projects/comments/comment_list", $view_data);
            }
            echo json_encode(array("success" => true, "data" => $response_data, 'message' => app_lang('comment_submited')));

            $comment_info = $this->Project_comments_model->get_one($save_id);

            $notification_options = array("project_id" => $comment_info->project_id, "project_comment_id" => $save_id);

            if ($comment_info->file_id) { //file comment
                $notification_options["project_file_id"] = $comment_info->file_id;
                log_notification("project_file_commented", $notification_options);
            } else if ($comment_info->customer_feedback_id) {  //customer feedback comment
                if ($comment_id) {
                    log_notification("project_customer_feedback_replied", $notification_options);
                } else {
                    log_notification("project_customer_feedback_added", $notification_options);
                }
            } else {  //project comment
                if ($comment_id) {
                    log_notification("project_comment_replied", $notification_options);
                } else {
                    log_notification("project_comment_added", $notification_options);
                }
            }
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete_comment($id = 0)
    {

        if (!$id) {
            exit();
        }

        $comment_info = $this->Project_comments_model->get_one($id);

        //only admin and creator can delete the comment
        if (!($this->login_user->is_admin || $comment_info->created_by == $this->login_user->id)) {
            app_redirect("forbidden");
        }


        //delete the comment and files
        if ($this->Project_comments_model->delete($id) && $comment_info->files) {

            //delete the files
            $file_path = get_setting("timeline_file_path");
            $files = unserialize($comment_info->files);

            foreach ($files as $file) {
                delete_app_files($file_path, array($file));
            }
        }
    }

    /* load all replies of a comment */

    function view_comment_replies($comment_id)
    {
        validate_numeric_value($comment_id);
        $view_data['reply_list'] = $this->Project_comments_model->get_details(array("comment_id" => $comment_id))->getResult();
        return $this->template->view("projects/comments/reply_list", $view_data);
    }

    /* show comment reply form */

    function comment_reply_form($comment_id, $type = "project", $type_id = 0)
    {
        validate_numeric_value($comment_id);
        validate_numeric_value($type_id);

        $view_data['comment_id'] = $comment_id;

        if ($type === "project") {
            $view_data['project_id'] = $type_id;
        } else if ($type === "task") {
            $view_data['task_id'] = $type_id;
        } else if ($type === "file") {
            $view_data['file_id'] = $type_id;
        } else if ($type == "customer_feedback") {
            $view_data['project_id'] = $type_id;
        }
        return $this->template->view("projects/comments/reply_form", $view_data);
    }

    /* load files view */

    function files($project_id)
    {
        validate_numeric_value($project_id);

        $this->init_project_permission_checker($project_id);

        if (!$this->can_view_files()) {
            app_redirect("forbidden");
        }

        $view_data['can_add_files'] = $this->can_add_files();
        $options = array("project_id" => $project_id);
        $doc_check_list = $this->Project_doc_check_list_model->get_details($options)->getResult();

        $view_data['can_add_checklist'] = true;
        if ($doc_check_list) {
            $view_data['can_add_checklist'] = false;
        }

        $view_data['files'] = $this->Project_files_model->get_details($options)->getResult();
        $view_data['project_id'] = $project_id;

        $file_categories = $this->File_category_model->get_details()->getResult();
        $file_categories_dropdown = array(array("id" => "", "text" => "- " . app_lang("category") . " -"));

        if ($file_categories) {
            foreach ($file_categories as $file_category) {
                $file_categories_dropdown[] = array("id" => $file_category->id, "text" => $file_category->name);
            }
        }

        $view_data["doc_location_breadcrumbs"] = $this->_doc_location_breadcrumbs($project_id);
        $view_data["client_directory_path"] = $this->_client_directory_path($project_id);

        $view_data["file_categories_dropdown"] = json_encode($file_categories_dropdown);

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("project_files", $this->login_user->is_admin, $this->login_user->user_type);
        $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("project_files", $this->login_user->is_admin, $this->login_user->user_type);

        return $this->template->view("projects/files/index", $view_data);
    }

    function _doc_location_breadcrumbs($project_id = 0)
    {
        $breadcrumbs = array(
            "OneDrive",
            "Clients"
        );
        $project_info = $this->Projects_model->get_one($project_id);
        if ($project_info) {
            // $client_full_name = $this->get_client_full_name($project_info->client_id, null, '_');
            $client_path = "VA$project_info->client_id";
            $breadcrumbs[] = $client_path;
        }

        return $breadcrumbs;
    }

    function _client_directory_path($project_id = 0)
    {
        $dir_path = get_setting("ms_onedrive_base_directory_path");
        $project_info = $this->Projects_model->get_one($project_id);
        if ($project_info) {
            // $client_full_name = $this->get_client_full_name($project_info->client_id, null, '_');
            $client_path = "VA$project_info->client_id";
            $dir_path .= "/Clients/" . $client_path;
        }

        return $dir_path;
    }

    function view_file($file_id = 0)
    {
        validate_numeric_value($file_id);
        $file_info = $this->Project_files_model->get_details(array("id" => $file_id))->getRow();

        if ($file_info) {

            $this->init_project_permission_checker($file_info->project_id);

            if (!$this->can_view_files()) {
                app_redirect("forbidden");
            }

            $view_data['can_comment_on_files'] = $this->can_comment_on_files();

            $file_url = get_source_url_of_file(make_array_of_file($file_info), get_setting("project_file_path") . $file_info->project_id . "/");

            $view_data["file_url"] = $file_url;
            $view_data["is_image_file"] = is_image_file($file_info->file_name);
            $view_data["is_iframe_preview_available"] = is_iframe_preview_available($file_info->file_name) || ($file_info->file_id && $file_info->service_type == "onedrive");
            $view_data["is_google_preview_available"] = is_google_preview_available($file_info->file_name);
            $view_data["is_viewable_video_file"] = is_viewable_video_file($file_info->file_name);
            $view_data["is_google_drive_file"] = ($file_info->file_id && $file_info->service_type == "google") ? true : false;

            if ($view_data["is_iframe_preview_available"]) {
                $view_data["is_image_file"] = false;
            }

            $view_data["file_info"] = $file_info;
            $options = array("file_id" => $file_id, "login_user_id" => $this->login_user->id);
            $view_data['comments'] = $this->Project_comments_model->get_details($options)->getResult();
            $view_data['file_id'] = $file_id;
            $view_data['project_id'] = $file_info->project_id;
            $view_data['current_url'] = get_uri("projects/view_file/" . $file_id);
            return $this->template->view("projects/files/view", $view_data);
        } else {
            show_404();
        }
    }

    /* file upload modal */

    function file_modal_form()
    {
        $view_data['model_info'] = $this->Project_files_model->get_one($this->request->getPost('id'));
        $project_id = $this->request->getPost('project_id') ? $this->request->getPost('project_id') : $view_data['model_info']->project_id;

        if ($this->request->getPost('milestone_id')) {
            $view_data['milestone_id'] = $this->request->getPost('milestone_id');
        } else {
            $view_data['milestone_id'] = $this->get_current_milestone_id($project_id);
        }
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("project_files", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        $this->init_project_permission_checker($project_id);

        if (!$this->can_add_files()) {
            app_redirect("forbidden");
        }

        $view_data['project_id'] = $project_id;

        $view_data['doc_check_list_dropdown'] = $this->get_project_doc_check_list_item_dropdown($project_id);

        if ($this->request->getPost('full_check_list') == '1') {
            $doc_check_list = $this->get_project_doc_check_list_item_dropdown($project_id);
            $view_data['doc_check_list'] = $doc_check_list;
        } else {
            $view_data['doc_check_list_id'] = $this->request->getPost('doc_check_list_id');
            $view_data['doc_check_list_item'] = $this->request->getPost('doc_check_list_item');
        }

        $file_categories = $this->File_category_model->get_details()->getResult();
        $file_categories_dropdown = array("" => "-");

        if ($file_categories) {
            foreach ($file_categories as $file_category) {
                $file_categories_dropdown[$file_category->id] = $file_category->name;
            }
        }

        $view_data["file_categories_dropdown"] = $file_categories_dropdown;

        return $this->template->view('projects/files/modal_form', $view_data);
    }

    function checklist_modal_form()
    {
        $view_data['model_info'] = $this->Project_doc_check_list_model->get_one($this->request->getPost('id'));
        $project_id = $this->request->getPost('project_id') ? $this->request->getPost('project_id') : $view_data['model_info']->project_id;

        $view_data['project_id'] = $project_id;
        $view_data['doc_check_list_dropdown'] = $this->make_doc_check_list_dropdown($project_id);

        return $this->template->view('projects/files/checklist_modal_form', $view_data);
    }

    function save_doc_checklist()
    {
        $doc_check_list_id = $this->request->getPost('doc_check_list_id');
        $project_id = $this->request->getPost('project_id');

        $success = $this->_handle_doc_check_list($doc_check_list_id, $project_id);

        if ($success) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* save project file data and move temp file to parmanent file directory */

    function save_file()
    {
        $project_id = $this->request->getPost('project_id');
        $milestone_id = $this->request->getPost('milestone_id');
        $category_id = $this->request->getPost('category_id');

        $file_label = $this->request->getPost('description');
        if ($file_label) {
            $project_doc_check_list_item = $this->is_project_doc_check_list_item($this->request->getPost('description'));
            if ($project_doc_check_list_item) {
                $file_label = $project_doc_check_list_item->label;
            }
        }

        $this->init_project_permission_checker($project_id);

        if (!$this->can_add_files()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');

        $files = $this->request->getPost("files");
        $success = false;
        $now = get_current_utc_time();

        $target_path = getcwd() . "/" . get_setting("project_file_path") . $project_id . "/";

        if ($id) {
            $data = array(
                "description" => $file_label,
                "category_id" => $category_id ? $category_id : 0,
                'doc_check_list_item_id' => $project_doc_check_list_item ? $project_doc_check_list_item->id : 0
            );

            $success = $this->Project_files_model->ci_save($data, $id);

            if ($project_doc_check_list_item) {
                $check_list_data = array('file_id' => $id);
                $this->Project_doc_check_list_model->ci_save($check_list_data, $project_doc_check_list_item->id);
            }

            save_custom_fields("project_files", $success, $this->login_user->is_admin, $this->login_user->user_type);
        } else {
            //process the fiiles which has been uploaded by dropzone
            if ($files && get_array_value($files, 0)) {
                foreach ($files as $file) {
                    $file_name = $this->request->getPost('file_name_' . $file);
                    $file_info = move_temp_file($file_name, $target_path, "");
                    $project_doc_check_list_item = $this->is_project_doc_check_list_item($this->request->getPost('description_' . $file));
                    if ($file_info) {
                        // print_r($file_info);
                        $data = array(
                            "project_id" => $project_id,
                            "milestone_id" => $milestone_id,
                            "file_name" => get_array_value($file_info, 'file_name'),
                            "file_id" => get_array_value($file_info, 'file_id'),
                            "service_type" => get_array_value($file_info, 'service_type'),
                            "description" => $project_doc_check_list_item ? $project_doc_check_list_item->label : $this->request->getPost('description_' . $file),
                            "file_size" => $this->request->getPost('file_size_' . $file),
                            "created_at" => $now,
                            "uploaded_by" => $this->login_user->id,
                            "category_id" => $category_id ? $category_id : 0,
                            'doc_check_list_item_id' => $project_doc_check_list_item ? $project_doc_check_list_item->id : 0
                        );

                        $data = clean_data($data);

                        $success = $this->Project_files_model->ci_save($data);

                        if ($success && $project_doc_check_list_item) {
                            $check_list_data = array('file_id' => $success);
                            $this->Project_doc_check_list_model->ci_save($check_list_data, $project_doc_check_list_item->id);
                        }
                        save_custom_fields("project_files", $success, $this->login_user->is_admin, $this->login_user->user_type);
                        log_notification("project_file_added", array("project_id" => $project_id, "project_file_id" => $success));
                    } else {
                        $success = false;
                    }
                }
            }
            //process the files which has been submitted manually
            if ($_FILES) {
                $files = $_FILES['manualFiles'];
                if ($files && count($files) > 0) {
                    $description = $this->request->getPost('description');
                    foreach ($files["tmp_name"] as $key => $file) {
                        $temp_file = $file;
                        $file_name = $files["name"][$key];
                        $file_size = $files["size"][$key];

                        $file_info = move_temp_file($file_name, $target_path, "", $temp_file);

                        $project_doc_check_list_item = $this->is_project_doc_check_list_item(get_array_value($description, $key));

                        if ($file_info) {
                            $data = array(
                                "project_id" => $project_id,
                                "file_name" => get_array_value($file_info, 'file_name'),
                                "file_id" => get_array_value($file_info, 'file_id'),
                                "service_type" => get_array_value($file_info, 'service_type'),
                                "description" => $project_doc_check_list_item ? $project_doc_check_list_item->label : get_array_value($description, $key),
                                "file_size" => $file_size,
                                "created_at" => $now,
                                "uploaded_by" => $this->login_user->id,
                                'doc_check_list_item_id' => $project_doc_check_list_item ? $project_doc_check_list_item->id : 0
                            );
                            $success = $this->Project_files_model->ci_save($data);

                            if ($success && $project_doc_check_list_item) {
                                $check_list_data = array('file_id' => $success);
                                $this->Project_doc_check_list_model->ci_save($check_list_data, $project_doc_check_list_item->id);
                            }

                            save_custom_fields("project_files", $success, $this->login_user->is_admin, $this->login_user->user_type);
                            log_notification("project_file_added", array("project_id" => $project_id, "project_file_id" => $success));
                        }
                    }
                }
            }
        }

        if ($success) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* upload a post file */

    function upload_file()
    {
        upload_file_to_temp();
    }

    /* check valid file for project */

    function validate_project_file()
    {
        return validate_post_file($this->request->getPost("file_name"));
    }

    /* delete a file */

    function delete_file()
    {
        $id = $this->request->getPost('id');
        $info = $this->Project_files_model->get_one($id);

        $this->init_project_permission_checker($info->project_id);

        if (!$this->can_delete_files($info->uploaded_by)) {
            app_redirect("forbidden");
        }

        if ($this->Project_files_model->delete($id)) {
            $is_deleted = null;
            if ($info->service_type === 'onedrive' && $info->file_id) {
                $is_deleted = delete_ms_onedrive_item($info->file_id);
            } else {
                //delete the files
                $file_path = get_setting("project_file_path");
                delete_app_files($file_path . $info->project_id . "/", array(make_array_of_file($info)));
            }
            log_notification("project_file_deleted", array("project_id" => $info->project_id, "project_file_id" => $id));
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted'), 'is_deleted' => $is_deleted));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* download a file */

    function download_file($id)
    {

        $file_info = $this->Project_files_model->get_one($id);

        $this->init_project_permission_checker($file_info->project_id);
        if (!$this->can_view_files()) {
            app_redirect("forbidden");
        }

        //serilize the path
        $file_data = serialize(array(array("file_name" => $file_info->project_id . "/" . $file_info->file_name, "file_id" => $file_info->file_id, "service_type" => $file_info->service_type)));

        //delete the file
        return $this->download_app_files(get_setting("project_file_path"), $file_data);
    }

    /* download multiple files as zip */

    function download_multiple_files($files_ids = "")
    {

        if ($files_ids) {


            $files_ids_array = explode('-', $files_ids);

            $files = $this->Project_files_model->get_files($files_ids_array);

            if ($files) {
                $file_path_array = array();
                $project_id = 0;

                foreach ($files->getResult() as $file_info) {

                    //we have to check the permission for each file
                    //initialize the permission check only if the project id is different

                    if ($project_id != $file_info->project_id) {
                        $this->init_project_permission_checker($file_info->project_id);
                        $project_id = $file_info->project_id;
                    }

                    if (!$this->can_view_files()) {
                        app_redirect("forbidden");
                    }

                    $file_path_array[] = array("file_name" => $file_info->project_id . "/" . $file_info->file_name, "file_id" => $file_info->file_id, "service_type" => $file_info->service_type);
                }

                $serialized_file_data = serialize($file_path_array);

                return $this->download_app_files(get_setting("project_file_path"), $serialized_file_data);
            }
        }
    }

    /* download files by zip */

    function download_comment_files($id)
    {

        $info = $this->Project_comments_model->get_one($id);

        $this->init_project_permission_checker($info->project_id);
        if ($this->login_user->user_type == "client" && !$this->is_clients_project) {
            app_redirect("forbidden");
        } else if ($this->login_user->user_type == "user" && !$this->is_user_a_project_member) {
            app_redirect("forbidden");
        }

        return $this->download_app_files(get_setting("timeline_file_path"), $info->files);
    }

    /* list of files, prepared for datatable  */
    function check_list_data($project_id = 0)
    {
        $this->can_view_files();

        $options = array('project_id' => $project_id);
        $list_data = $this->Project_doc_check_list_model->get_details($options)->getResult();
        $result = array();

        // $project_info = $this->Projects_model->get_one($project_id);
        // $client_full_name = $this->get_client_full_name($project_info->client_id, null, '_');
        // $directories = get_client_drive_items($client_full_name . "_VA" . $project_info->client_id);
        foreach ($list_data as $data) {
            // $result[] = $this->_make_checklist_row($data, $project_id, $directories);
            $result[] = $this->_make_checklist_row($data);
        }

        echo json_encode(array("data" => $result));
    }


    function _make_checklist_row($data)
    {
        // $options = array('doc_check_list_item_id' => $data->id, 'project_id' => $project_id);
        // $files = $this->Project_files_model->get_details($options)->getResult();
        // $files_count = 0;
        // echo json_encode($directories);
        // if ($directories) {
        //     foreach ($directories as $dir) {
        //         $is_folder =  get_array_value($dir, 'folder');
        //         if ($is_folder && strtolower($data->label) == strtolower(get_array_value($dir, 'name'))) {
        //             $files_count = get_array_value($is_folder, 'childCount');
        //             break;
        //         }
        //     }
        // }

        // $options = modal_anchor(get_uri("projects/file_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> ", array("class" => "btn btn-default btn-sm", "title" => app_lang('add_file') . ": " . $data->label, "data-post-project_id" => $project_id, "data-post-doc_check_list_id" => $data->id, "data-post-doc_check_list_item" => $data->label));

        $od_ref = "<i data-feather='alert-circle' class='icon-16 text-secondary'></i> Not Synced";

        if ($data->od_ref == '0') {
            $od_ref =  "<i data-feather='x-circle' class='icon-16 text-danger'></i> Not Found";
        } elseif (is_string($data->od_ref) && strlen($data->od_ref) > 1) {
            $od_ref = "<i data-feather='check-circle' class='icon-16 text-success'></i> Found";
        }

        return array(
            $data->label,
            $od_ref,
            ajax_anchor(get_uri("projects/sync_single_checklist_documents"), "<i data-feather='refresh-cw' class='icon-16'></i>", array("class" => "btn btn-default", "title" =>  app_lang('sync_checklist_documents'), "data-post-checklist_id" => $data->id, "data-post-project_id" => $data->project_id, "id" => "sync_single_checklist_docs", "data-reload-on-success" => true))
        );
    }

    /* list of files, prepared for datatable  */

    function sync_message_modal_form()
    {
        $project_id = $this->request->getPost('project_id');

        $view_data['project_id'] = $project_id;
        return $this->template->view('projects/files/sync_message_modal_form', $view_data);
    }

    function sync_single_checklist_documents()
    {
        $checklist_id = $this->request->getPost('checklist_id');
        $project_id = $this->request->getPost('project_id');
        validate_numeric_value($project_id);
        validate_numeric_value($checklist_id);
        $this->init_project_permission_checker($project_id);

        if (!$this->can_view_files()) {
            app_redirect("forbidden");
        }

        $project_info = $this->Projects_model->get_one($project_id);

        $checklist = $this->Project_doc_check_list_model->get_one($checklist_id);

        $client_info = $this->Clients_model->get_one($project_info->client_id);

        $od_dir_path = $client_info->unique_id;

        if ($checklist) {
            $cl_title = explode(' ', $checklist->label);
            $od_directories = search_od_directory($od_dir_path, $cl_title[0]);
            // $od_directories = search_od_directory("Khadijah_VA2445", $cl_title[0]);
            if ($od_directories) {

                $mix_od_files = get_array_value($od_directories, 'value');

                $checklist_data = array(
                    'od_ref' => 0
                );
                foreach ($mix_od_files as $od_item) {
                    if (get_array_value($od_item, 'file')) {
                        $filename = pathinfo(get_array_value($od_item, 'name'), PATHINFO_FILENAME);
                        if (strtolower($filename) == strtolower($checklist->label)) {
                            $checklist_data['od_ref'] = get_array_value($od_item, 'webUrl');
                            break;
                        }
                    }
                }

                $this->Project_doc_check_list_model->ci_save($checklist_data, $checklist->id);
            } else {
                $checklist_data = array(
                    'od_ref' => 0
                );
                $this->Project_doc_check_list_model->ci_save($checklist_data, $checklist->id);
            }
            echo json_encode(array("success" => true, 'message' => 'Document synced successfully'));
        } else {
            echo json_encode(array("success" => false, 'message' => 'Unable to sync the checklist documents. Client directory not found. Please make sure the client directory is available on OneDrive'));
        }
    }

    function sync_checklist_documents()
    {
        ini_set('max_execution_time', 150); //execute maximum 150 seconds 
        $project_id = $this->request->getPost('project_id');
        validate_numeric_value($project_id);
        $this->init_project_permission_checker($project_id);

        if (!$this->can_view_files()) {
            app_redirect("forbidden");
        }

        $project_info = $this->Projects_model->get_one($project_id);
        // $client_full_name = $this->get_client_full_name($project_info->client_id, null, '_');

        $checklist_options = array(
            'project_id' => $project_id
        );
        $checklist_items = $this->Project_doc_check_list_model->get_details($checklist_options)->getResult();

        $client_info = $this->Clients_model->get_one($project_info->client_id);

        $od_dir_path = $client_info->unique_id;

        foreach ($checklist_items as $key => $checklist) {
            $cl_title = explode(' ', $checklist->label);
            if ($key) {
                sleep(0.3);
            }
            $od_directories = search_od_directory($od_dir_path, $cl_title[0]);
            // $od_directories = search_od_directory("Khadijah_VA2445", $cl_title[0]);
            if ($od_directories) {

                $mix_od_files = get_array_value($od_directories, 'value');

                $checklist_data = array(
                    'od_ref' => 0
                );
                foreach ($mix_od_files as $od_item) {
                    if (get_array_value($od_item, 'file')) {
                        $filename = pathinfo(get_array_value($od_item, 'name'), PATHINFO_FILENAME);
                        if (strtolower($filename) == strtolower($checklist->label)) {
                            $checklist_data['od_ref'] = get_array_value($od_item, 'webUrl');
                            break;
                        }
                    }
                }

                $this->Project_doc_check_list_model->ci_save($checklist_data, $checklist->id);
            } else {
                $checklist_data = array(
                    'od_ref' => 0
                );
                $this->Project_doc_check_list_model->ci_save($checklist_data, $checklist->id);
            }
        }
        echo json_encode(array("success" => true, 'message' => 'Document synced successfully'));
        // else {
        //     echo json_encode(array("success" => true, 'message' => 'Unable to sync the checklist documents. Client directory not found. Please make sure the client directory is available on OneDrive'));
        // }
    }

    /* list of files, prepared for datatable  */

    function files_list_data($project_id = 0)
    {
        validate_numeric_value($project_id);
        $this->init_project_permission_checker($project_id);

        if (!$this->can_view_files()) {
            app_redirect("forbidden");
        }

        $project_info = $this->Projects_model->get_one($project_id);
        $client_full_name = $this->get_client_full_name($project_info->client_id, null, '_');

        // $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("project_files", $this->login_user->is_admin, $this->login_user->user_type);

        // $options = array(
        //     "project_id" => $project_id,
        //     "category_id" => $this->request->getPost("category_id"),
        //     "custom_fields" => $custom_fields,
        //     "custom_field_filter" => $this->prepare_custom_field_filter_values("project_files", $this->login_user->is_admin, $this->login_user->user_type)
        // );

        // $list_data = $this->Project_files_model->get_details($options)->getResult();
        $list_data = get_client_drive_items($client_full_name . "_VA" . $project_info->client_id);
        $result = array();
        if ($list_data) {
            foreach ($list_data as $data) {
                $result[] = $this->_make_drive_item_row($data);
            }
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of file list table */

    private function _make_drive_item_row($data)
    {
        if (array_key_exists('folder', $data)) {
            $file_icon = 'folder';
        } else {
            $file_icon = get_file_icon(strtolower(pathinfo(get_array_value($data, 'name'), PATHINFO_EXTENSION)));
        }

        $description = "<div class='float-start text-wrap'>" .
            anchor(get_array_value($data, 'webUrl'), array_key_exists('folder', $data) ? $data['name'] : remove_file_prefix($data['name']), array('title' => "", 'target' => '_blank')) . '</div>';

        $_lmb = get_array_value($data, 'lastModifiedBy');
        $last_modified_by = "-";
        if ($_lmb) {
            $lmb_user = get_array_value($_lmb, 'user');
            if ($lmb_user) {
                $last_modified_by = get_array_value($lmb_user, 'displayName');
            }
        }
        $row_data = array(
            "<div data-feather='$file_icon' class='mr10 float-start'></div>" . $description,
            $last_modified_by,
            format_to_datetime($data['lastModifiedDateTime'])
        );

        $options = array_key_exists('folder', $data) ? "" : anchor(get_uri("projects/download_file/" . $data['id']), "<i data-feather='download-cloud' class='icon-16'></i>", array("title" => app_lang("download")));

        $row_data[] = $options;

        return $row_data;
    }

    private function _make_file_row($data, $custom_fields)
    {
        $file_icon = get_file_icon(strtolower(pathinfo($data->file_name, PATHINFO_EXTENSION)));

        $image_url = get_avatar($data->uploaded_by_user_image);
        $uploaded_by = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->uploaded_by_user_name";

        if ($data->uploaded_by_user_type == "staff") {
            $uploaded_by = get_team_member_profile_link($data->uploaded_by, $uploaded_by);
        } else {
            $uploaded_by = get_client_contact_profile_link($data->uploaded_by, $uploaded_by);
        }

        $description = "<div class='float-start text-wrap'>" .
            js_anchor(remove_file_prefix($data->file_name), array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "1", "data-url" => get_uri("projects/view_file/" . $data->id)));

        if ($data->description) {
            $description .= "<br /><span class='text-wrap'>" . $data->description . "</span></div>";
        } else {
            $description .= "</div>";
        }

        //show checkmark to download multiple files
        $checkmark = js_anchor("<span class='checkbox-blank mr15 float-start'></span>", array('title' => "", "class" => "", "data-id" => $data->id, "data-act" => "download-multiple-file-checkbox"));

        $row_data = array(
            $checkmark,
            "<div data-feather='$file_icon' class='mr10 float-start'></div>" . $description,
            $data->category_name ? $data->category_name : "-",
            convert_file_size($data->file_size),
            $uploaded_by,
            format_to_datetime($data->created_at)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $options = anchor(get_uri("projects/download_file/" . $data->id), "<i data-feather='download-cloud' class='icon-16'></i>", array("title" => app_lang("download")));
        if ($this->can_add_files()) {
            $options .= modal_anchor(get_uri("projects/file_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_files'), "data-post-id" => $data->id));
        }
        if ($this->can_delete_files($data->uploaded_by)) {
            $options .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_file'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("projects/delete_file"), "data-action" => "delete-confirmation"));
        }

        $row_data[] = $options;

        return $row_data;
    }

    /* load notes view */

    function notes($project_id)
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();
        $view_data['project_id'] = $project_id;
        return $this->template->view("projects/notes/index", $view_data);
    }

    /* load history view */

    function history($offset = 0, $log_for = "", $log_for_id = "", $log_type = "", $log_type_id = "")
    {
        if ($this->login_user->user_type !== "staff" && ($this->login_user->user_type == "client" && get_setting("client_can_view_activity") !== "1")) {
            app_redirect("forbidden");
        }

        $view_data['offset'] = $offset;
        $view_data['activity_logs_params'] = array("log_for" => $log_for, "log_for_id" => $log_for_id, "log_type" => $log_type, "log_type_id" => $log_type_id, "limit" => 20, "offset" => $offset);
        return $this->template->view("projects/history/index", $view_data);
    }

    /* load project members view */

    function members($project_id = 0)
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();
        $view_data['project_id'] = $project_id;
        return $this->template->view("projects/project_members/index", $view_data);
    }

    /* load payments tab  */

    function payments($project_id)
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();
        if ($project_id) {
            $view_data['project_info'] = $this->Projects_model->get_details(array("id" => $project_id))->getRow();
            $view_data['project_id'] = $project_id;
            return $this->template->view("projects/payments/index", $view_data);
        }
    }

    /* load invoices tab  */

    function invoices($project_id, $client_id = 0)
    {
        $this->access_only_team_members_or_client_contact($client_id);
        validate_numeric_value($project_id);
        if ($project_id) {
            $view_data['project_id'] = $project_id;
            $view_data['project_info'] = $this->Projects_model->get_details(array("id" => $project_id))->getRow();

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);
            $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("invoices", $this->login_user->is_admin, $this->login_user->user_type);

            $view_data["can_edit_invoices"] = $this->can_edit_invoices();

            return $this->template->view("projects/invoices/index", $view_data);
        }
    }

    /* load expenses tab  */

    function expenses($project_id)
    {
        validate_numeric_value($project_id);
        $this->access_only_team_members();
        if ($project_id) {
            $view_data['project_id'] = $project_id;

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);
            $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("expenses", $this->login_user->is_admin, $this->login_user->user_type);

            return $this->template->view("projects/expenses/index", $view_data);
        }
    }

    //save project status
    function change_status($project_id, $status_id)
    {
        if ($project_id && $this->can_edit_projects() && $status_id) {
            validate_numeric_value($project_id);
            validate_numeric_value($status_id);
            $status_data = array("status_id" => $status_id);
            $save_id = $this->Projects_model->ci_save($status_data, $project_id);

            //send notification
            if ($status_id == 2) {
                $milestone_options = array(
                    'project_id' => $project_id,
                );

                $milestones = $this->Milestones_model->get_details($milestone_options)->getResult();

                foreach ($milestones as $milestone) {
                    if ($milestone->is_current == 0 || $milestone->is_current == 1) {
                        $milestone_data = array(
                            'is_current' => 2
                        );

                        $this->Milestones_model->ci_save($milestone_data, $milestone->id);
                    }
                }

                log_notification("project_completed", array("project_id" => $save_id));
            }
        }
    }


    //save project status
    function mark_as_completed($project_id, $status_id)
    {
        if ($project_id && $this->can_edit_projects() && $status_id) {
            validate_numeric_value($project_id);
            validate_numeric_value($status_id);
            $status_data = array("status_id" => $status_id);
            $save_id = $this->Projects_model->ci_save($status_data, $project_id);

            //send notification
            if ($status_id == 2) {
                $milestone_options = array(
                    'project_id' => $project_id,
                );

                $milestones = $this->Milestones_model->get_details($milestone_options)->getResult();

                foreach ($milestones as $milestone) {
                    if ($milestone->is_current == 0 || $milestone->is_current == 1) {
                        $milestone_data = array(
                            'is_current' => 2
                        );

                        $this->Milestones_model->ci_save($milestone_data, $milestone->id);
                    }
                }

                log_notification("project_completed", array("project_id" => $save_id));
            }

            echo json_encode(array("success" => true, 'message' => "Application Updated"));
        } else {
            echo json_encode(array("success" => false, 'message' => "Unable to update application. Please try again"));
        }
    }

    /* load project settings modal */

    function settings_modal_form()
    {
        $project_id = $this->request->getPost('project_id');

        $can_edit_timesheet_settings = $this->can_edit_timesheet_settings($project_id);
        $can_edit_slack_settings = $this->can_edit_slack_settings();
        $can_create_projects = $this->can_create_projects();

        if (!$project_id || !($can_edit_timesheet_settings || $can_edit_slack_settings || $can_create_projects)) {
            app_redirect("forbidden");
        }

        $this->init_project_settings($project_id);

        $view_data['project_id'] = $project_id;
        $view_data['can_edit_timesheet_settings'] = $can_edit_timesheet_settings;
        $view_data['can_edit_slack_settings'] = $can_edit_slack_settings;
        $view_data["can_create_projects"] = $this->can_create_projects();

        $task_statuses_dropdown = array();
        $task_statuses = $this->Task_status_model->get_details()->getResult();
        foreach ($task_statuses as $task_status) {
            $task_statuses_dropdown[] = array("id" => $task_status->id, "text" => $task_status->key_name ? app_lang($task_status->key_name) : $task_status->title);
        }

        $view_data["task_statuses_dropdown"] = json_encode($task_statuses_dropdown);
        $view_data["project_info"] = $this->Projects_model->get_one($project_id);

        return $this->template->view('projects/settings/modal_form', $view_data);
    }

    /* save project settings */

    function save_settings()
    {
        $project_id = $this->request->getPost('project_id');

        $can_edit_timesheet_settings = $this->can_edit_timesheet_settings($project_id);
        $can_edit_slack_settings = $this->can_edit_slack_settings();
        $can_create_projects = $this->can_create_projects();

        if (!$project_id || !($can_edit_timesheet_settings || $can_edit_slack_settings || $can_create_projects)) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "project_id" => "required|numeric"
        ));

        $settings = array();
        if ($can_edit_timesheet_settings) {
            $settings[] = "client_can_view_timesheet";
        }

        if ($can_edit_slack_settings) {
            $settings[] = "project_enable_slack";
            $settings[] = "project_slack_webhook_url";
        }

        if ($can_create_projects) {
            $settings[] = "remove_task_statuses";
        }

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (!$value) {
                $value = "";
            }

            $this->Project_settings_model->save_setting($project_id, $setting, $value);
        }

        //send test message
        if ($can_edit_slack_settings && $this->request->getPost("send_a_test_message")) {
            helper('notifications');
            if (send_slack_notification("test_slack_notification", $this->login_user->id, 0, $this->request->getPost("project_slack_webhook_url"))) {
                echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('slack_notification_error_message')));
            }
        } else {
            echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
        }
    }

    /* get member suggestion with start typing '@' */

    function get_member_suggestion_to_mention()
    {

        $this->validate_submitted_data(array(
            "project_id" => "required|numeric"
        ));

        $project_id = $this->request->getPost("project_id");

        $project_members = $this->Project_members_model->get_project_members_dropdown_list($project_id, "", $this->can_access_clients(true))->getResult();
        $project_members_dropdown = array();
        foreach ($project_members as $member) {
            $project_members_dropdown[] = array("name" => $member->member_name, "content" => "@[" . $member->member_name . " :" . $member->user_id . "]");
        }

        if ($project_members_dropdown) {
            echo json_encode(array("success" => TRUE, "data" => $project_members_dropdown));
        } else {
            echo json_encode(array("success" => FALSE));
        }
    }

    //reset projects dropdown on changing of client 
    function get_projects_of_selected_client_for_filter()
    {
        $this->access_only_team_members();
        $client_id = $this->request->getPost("client_id");
        if ($client_id) {
            $projects = $this->Projects_model->get_all_where(array("client_id" => $client_id, "deleted" => 0), 0, 0, "title")->getResult();
            $projects_dropdown = array(array("id" => "", "text" => "- " . app_lang("project") . " -"));
            foreach ($projects as $project) {
                $projects_dropdown[] = array("id" => $project->id, "text" => $project->title);
            }
            echo json_encode($projects_dropdown);
        } else {
            //we have show all projects by de-selecting client
            echo json_encode($this->_get_all_projects_dropdown_list());
        }
    }

    //get clients dropdown
    private function _get_clients_dropdown()
    {
        $clients_dropdown = array(array("id" => "", "text" => "- " . app_lang("client") . " -"));
        $clients = $this->Clients_model->get_dropdown_list(array("company_name", 'first_name', 'last_name'), "id", array("is_lead" => 0));
        foreach ($clients as $key => $value) {
            $clients_dropdown[] = array("id" => $key, "text" => $value);
        }
        return $clients_dropdown;
    }

    //show timesheets chart
    function timesheet_chart($project_id = 0)
    {
        validate_numeric_value($project_id);
        $members = $this->_get_members_to_manage_timesheet();

        $view_data['members_dropdown'] = json_encode($this->_prepare_members_dropdown_for_timesheet_filter($members));
        $view_data['projects_dropdown'] = json_encode($this->_get_all_projects_dropdown_list_for_timesheets_filter());
        $view_data["project_id"] = $project_id;

        return $this->template->view("projects/timesheets/timesheet_chart", $view_data);
    }

    //timesheets chart data
    function timesheet_chart_data($project_id = 0)
    {
        if (!$project_id) {
            $project_id = $this->request->getPost("project_id");
        }

        validate_numeric_value($project_id);

        $this->init_project_permission_checker($project_id);
        $this->init_project_settings($project_id); //since we'll check this permission project wise

        if (!$this->can_view_timesheet($project_id, true)) {
            app_redirect("forbidden");
        }

        $timesheets = array();
        $timesheets_array = array();
        $ticks = array();

        $start_date = $this->request->getPost("start_date");
        $end_date = $this->request->getPost("end_date");
        $user_id = $this->request->getPost("user_id");

        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "user_id" => $user_id,
            "project_id" => $project_id
        );

        //get allowed member ids
        $members = $this->_get_members_to_manage_timesheet();
        if ($members != "all" && $this->login_user->user_type == "staff") {
            //if user has permission to access all members, query param is not required
            //client can view all timesheet
            $options["allowed_members"] = $members;
        }

        $timesheets_result = $this->Timesheets_model->get_timesheet_statistics($options)->timesheets_data;
        $timesheet_users_result = $this->Timesheets_model->get_timesheet_statistics($options)->timesheet_users_data;

        $user_result = array();
        foreach ($timesheet_users_result as $user) {
            $time = convert_seconds_to_time_format($user->total_sec);
            $user_result[] = "<div class='user-avatar avatar-30 avatar-circle' data-bs-toggle='tooltip' title='" . $user->user_name . " - " . $time . "'><img alt='' src='" . get_avatar($user->user_avatar) . "'></div>";
        }

        $days_of_month = date("t", strtotime($start_date));

        for ($i = 1; $i <= $days_of_month; $i++) {
            $timesheets[$i] = 0;
        }

        foreach ($timesheets_result as $value) {
            $timesheets[$value->day * 1] = $value->total_sec / 60 / 60;
        }

        foreach ($timesheets as $value) {
            $timesheets_array[] = $value;
        }

        for ($i = 1; $i <= $days_of_month; $i++) {
            $ticks[] = $i;
        }

        echo json_encode(array("timesheets" => $timesheets_array, "ticks" => $ticks, "timesheet_users_result" => $user_result));
    }

    function like_comment($comment_id = 0)
    {
        if ($comment_id) {
            validate_numeric_value($comment_id);
            $data = array(
                "project_comment_id" => $comment_id,
                "created_by" => $this->login_user->id
            );

            $existing = $this->Likes_model->get_one_where(array_merge($data, array("deleted" => 0)));
            if ($existing->id) {
                //liked already, unlike now
                $this->Likes_model->delete($existing->id);
            } else {
                //not liked, like now
                $data["created_at"] = get_current_utc_time();
                $this->Likes_model->ci_save($data);
            }

            $options = array("id" => $comment_id, "login_user_id" => $this->login_user->id);
            $comment = $this->Project_comments_model->get_details($options)->getRow();

            return $this->template->view("projects/comments/like_comment", array("comment" => $comment));
        }
    }

    /* load contracts tab  */

    function contracts($project_id)
    {
        $this->access_only_team_members();
        if ($project_id) {
            $view_data['project_id'] = $project_id;
            $view_data['project_info'] = $this->Projects_model->get_details(array("id" => $project_id))->getRow();

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("contracts", $this->login_user->is_admin, $this->login_user->user_type);
            $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("contracts", $this->login_user->is_admin, $this->login_user->user_type);

            return $this->template->view("projects/contracts/index", $view_data);
        }
    }

    // pin/unpin comments
    function pin_comment($comment_id = 0)
    {
        if ($comment_id) {
            $data = array(
                "project_comment_id" => $comment_id,
                "pinned_by" => $this->login_user->id
            );

            $existing = $this->Pin_comments_model->get_one_where(array_merge($data, array("deleted" => 0)));

            $save_id = "";
            if ($existing->id) {
                //pinned already, unpin now
                $save_id = $this->Pin_comments_model->delete($existing->id);
            } else {
                //not pinned, pin now
                $data["created_at"] = get_current_utc_time();
                $save_id = $this->Pin_comments_model->ci_save($data);
            }

            if ($save_id) {
                $options = array("id" => $save_id);
                $pinned_comments = $this->Pin_comments_model->get_details($options)->getResult();

                $status = "pinned";

                $save_data = $this->template->view("projects/comments/pinned_comments", array("pinned_comments" => $pinned_comments));
                echo json_encode(array("success" => true, "data" => $save_data, "status" => $status));
            } else {
                echo json_encode(array("success" => false));
            }
        }
    }

    /* load tickets tab  */

    function tickets($project_id)
    {
        $this->access_only_team_members();
        if ($project_id) {
            validate_numeric_value($project_id);
            $view_data['project_id'] = $project_id;

            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("tickets", $this->login_user->is_admin, $this->login_user->user_type);

            return $this->template->view("projects/tickets/index", $view_data);
        }
    }

    function file_category($project_id = 0)
    {
        $this->access_only_team_members();
        validate_numeric_value($project_id);
        $this->init_project_permission_checker($project_id);
        if (!$this->can_view_files()) {
            app_redirect("forbidden");
        }

        $view_data["project_id"] = $project_id;
        $view_data['can_add_files'] = $this->can_add_files();
        return $this->template->view("projects/files/category/index", $view_data);
    }

    function file_category_list_data($project_id = 0)
    {
        $this->access_only_team_members();
        validate_numeric_value($project_id);
        $this->init_project_permission_checker($project_id);
        if (!$this->can_view_files()) {
            app_redirect("forbidden");
        }

        $options = array("type" => "project");
        $list_data = $this->File_category_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_file_category_row($data, $project_id);
        }

        echo json_encode(array("data" => $result));
    }

    private function _file_category_row_data($id, $project_id = 0)
    {
        $options = array("id" => $id);
        $data = $this->File_category_model->get_details($options)->getRow();

        return $this->_make_file_category_row($data, $project_id);
    }

    private function _make_file_category_row($data, $project_id = 0)
    {
        $options = "";
        if ($this->can_add_files()) {
            $options .= modal_anchor(get_uri("projects/file_category_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_category'), "data-post-id" => $data->id, "data-post-project_id" => $project_id));
        }

        if ($this->can_delete_files()) {
            $options .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_category'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("projects/delete_file_category"), "data-action" => "delete", "data-post-project_id" => $project_id));
        }

        return array(
            $data->name,
            $options
        );
    }

    function file_category_modal_form()
    {
        $this->access_only_team_members();
        $project_id = $this->request->getPost('project_id');
        $this->init_project_permission_checker($project_id);
        if (!$this->can_add_files()) {
            app_redirect("forbidden");
        }

        $view_data['model_info'] = $this->File_category_model->get_one($this->request->getPost('id'));
        $view_data['project_id'] = $project_id;
        return $this->template->view('projects/files/category/modal_form', $view_data);
    }

    function save_file_category()
    {
        $this->access_only_team_members();
        $project_id = $this->request->getPost('project_id');
        $this->init_project_permission_checker($project_id);
        if (!$this->can_add_files()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost("id");

        $data = array(
            "name" => $this->request->getPost('name'),
            "type" => "project"
        );

        $save_id = $this->File_category_model->ci_save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_file_category_row_data($save_id, $project_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {

            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete_file_category()
    {
        $this->access_only_team_members();
        $project_id = $this->request->getPost('project_id');
        $this->init_project_permission_checker($project_id);
        if (!$this->can_delete_files()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');

        if ($this->request->getPost('undo')) {
            if ($this->File_category_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_file_category_row_data($id, $project_id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->File_category_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /* delete multiple files */

    function delete_multiple_files($files_ids = "")
    {

        if ($files_ids) {

            $files_ids_array = explode('-', $files_ids);
            $files = $this->Project_files_model->get_files($files_ids_array)->getResult();
            $is_success = true;
            $is_permission_success = true;
            $project_id = get_array_value($files, 0)->project_id;
            $this->init_project_permission_checker($project_id);

            foreach ($files as $file) {

                if (!$this->can_delete_files($file->uploaded_by)) {
                    $is_permission_success = false;
                    continue; //continue to the next file
                }

                if ($this->Project_files_model->delete($file->id)) {

                    //delete the files
                    $file_path = get_setting("project_file_path");
                    delete_app_files($file_path . $file->project_id . "/", array(make_array_of_file($file)));

                    log_notification("project_file_deleted", array("project_id" => $file->project_id, "project_file_id" => $file->id));
                } else {
                    $is_success = false;
                }
            }

            if ($is_success && $is_permission_success) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                if (!$is_permission_success) {
                    echo json_encode(array("success" => false, 'message' => app_lang('file_delete_permission_error_message')));
                } else {
                    echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
                }
            }
        }
    }

    private function has_client_feedback_access_permission()
    {
        if ($this->login_user->user_type != "client") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "client_feedback_access_permission") || $this->can_manage_all_projects()) {
                return true;
            }
        }
    }

    function show_my_open_timers()
    {
        $timers = $this->Timesheets_model->get_open_timers($this->login_user->id);
        $view_data["timers"] = $timers->getResult();
        return $this->template->view("projects/open_timers", $view_data);
    }

    function task_timesheet($task_id, $project_id)
    {
        validate_numeric_value($task_id);
        validate_numeric_value($project_id);

        $this->init_project_permission_checker($project_id);
        $this->init_project_settings($project_id);

        if (!$this->can_view_timesheet($project_id, true)) {
            app_redirect("forbidden");
        }
        $options = array(
            "project_id" => $project_id,
            "status" => "none_open",
            "task_id" => $task_id,
        );

        //get allowed member ids
        $members = $this->_get_members_to_manage_timesheet();
        if ($members != "all" && $this->login_user->user_type == "staff") {
            //if user has permission to access all members, query param is not required
            //client can view all timesheet
            $options["allowed_members"] = $members;
        }

        $view_data['task_timesheet'] = $this->Timesheets_model->get_details($options)->getResult();
        return $this->template->view("tasks/task_timesheet", $view_data);
    }

    //for old notifications, redirect to tasks/view

    function task_view($task_id = 0)
    {
        if ($task_id) {
            app_redirect("tasks/view/" . $task_id);
        }
    }

    function team_members_summary()
    {
        if (!$this->can_manage_all_projects()) {
            app_redirect("forbidden");
        }

        $view_data["project_status_text_info"] = get_project_status_text_info();
        $view_data["show_time_logged_data"] = get_setting("module_project_timesheet") ? 1 : 0;

        return $this->template->rander("projects/reports/team_members_summary", $view_data);
    }

    function team_members_summary_data()
    {
        if (!$this->can_manage_all_projects()) {
            app_redirect("forbidden");
        }
        $options = array(
            "start_date_from" => $this->request->getPost("start_date_from"),
            "start_date_to" => $this->request->getPost("start_date_to")
        );

        $list_data = $this->Projects_model->get_team_members_summary($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_team_members_summary_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_team_members_summary_row($data)
    {
        $image_url = get_avatar($data->image);
        $member = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->team_member_name";

        $duration = convert_seconds_to_time_format($data->total_secconds_worked);

        $row_data = array(
            get_team_member_profile_link($data->team_member_id, $member),
            $data->open_projects,
            $data->completed_projects,
            $data->hold_projects,
            $data->open_tasks,
            $data->completed_tasks,
            $duration,
            to_decimal_format(convert_time_string_to_decimal($duration)),
        );

        return $row_data;
    }

    function clients_summary()
    {
        if (!$this->can_manage_all_projects()) {
            app_redirect("forbidden");
        }
        $view_data["project_status_text_info"] = get_project_status_text_info();
        $view_data["show_time_logged_data"] = get_setting("module_project_timesheet") ? 1 : 0;

        return $this->template->view("projects/reports/clints_summary", $view_data);
    }

    function clients_summary_data()
    {
        if (!$this->can_manage_all_projects()) {
            app_redirect("forbidden");
        }
        $options = array(
            "start_date_from" => $this->request->getPost("start_date_from"),
            "start_date_to" => $this->request->getPost("start_date_to")
        );

        $list_data = $this->Projects_model->get_clients_summary($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_clients_summary_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    function project_payment_schedule_list_data($project_id)
    {
        $options = array(
            'project_id' => $project_id
        );

        $all_options = append_server_side_filtering_commmon_params($options);

        $result = $this->Project_payment_schedule_setup_model->get_details($all_options);

        //by this, we can handel the server side or client side from the app table prams.
        if (get_array_value($all_options, "server_side")) {
            $list_data = get_array_value($result, "data");
        } else {
            $list_data = $result->getResult();
            $result = array();
        }

        $result_data = array();
        foreach ($list_data as $data) {
            $result_data[] = $this->_make_project_payment_schedule_row($data);
        }

        $result["data"] = $result_data;

        echo json_encode($result);
    }

    function schedule_modal_form($schedule_id = 0)
    {

        $project_id = $this->request->getPost("project_id");
        $schedule_id = $this->request->getPost("schedule_id");

        if ($schedule_id) {
            $modal_info = $this->Project_payment_schedule_setup_model->get_one($schedule_id);

            if ($modal_info && $modal_info->fees) {
                $modal_info->fees = unserialize($modal_info->fees);
                $view_data['schedule_id'] = $modal_info->id;
                $view_data['modal_info'] = $modal_info;
            }
        }

        $project = $this->Projects_model->get_one($project_id);

        $view_data['project_title'] = $project ? $project->title : '-';

        if ($project) {
            $view_data['client_name'] = $this->get_client_full_name($project->client_id);
            $view_data['client_id'] = $project->client_id;
        }
        $view_data['project_id'] = $project_id;
        $view_data['institute'] = $this->Project_partners_model->get_details(array('project_id' => $project_id, 'partner_type' => 'institute'))->getRow();

        return $this->template->view('projects/schedule_modal_form', $view_data);
    }

    function project_fees_modal_form()
    {
        $project_id = $this->request->getPost("project_id");

        $options = array('project_id' => $project_id);
        $modal_info = $this->Project_fees_model->get_details($options)->getRow();

        $view_data = $this->_get_payment_schedule_info($project_id);

        if ($modal_info && $modal_info->fees) {
            $modal_info->fees = unserialize($modal_info->fees);
        }
        $view_data['modal_info'] = $modal_info;
        $view_data['project_id'] = $project_id;

        return $this->template->view('projects/project_fee_modal_form', $view_data);
    }

    function save_schedule()
    {
        $project_id = $this->request->getPost("project_id");
        $schedule_id = $this->request->getPost("schedule_id");
        $client_id = $this->request->getPost("client_id");
        $project_schedule_info = $this->Project_payment_schedule_setup_model->get_one($schedule_id);

        $rows_count = $this->request->getPost('rows_count');
        $discount = (float)$this->request->getPost('discount');

        $data = array(
            'project_id' => $project_id,
            'client_id' => $client_id,
            'rows_count' => $rows_count,
            'installment_name' => $this->request->getPost('installment_name'),
            'invoice_date' => $this->request->getPost('invoice_date'),
            'due_date' => $this->request->getPost('due_date'),
            'is_claimable' => 0,
            'discount' => $discount,
        );

        $fees = array();
        $total_fee = 0;

        if ($rows_count) {
            for ($x = 1; $x <= $rows_count; $x++) {
                $fee = array();

                $amount = (float)$this->request->getPost('amount_' . $x);
                $is_claimable = $this->request->getPost('is_claimable_' . $x);
                $is_taxable = $this->request->getPost('is_taxable_' . $x);
                $commission = $this->request->getPost('commission_' . $x);

                $fee['key'] = $x;
                $fee['fee_type'] = $this->request->getPost('fee_type_' . $x);
                $fee['amount'] = $amount;
                $fee['is_claimable'] = $is_claimable;
                $fee['is_taxable'] = $is_taxable;
                $fee['commission'] = $commission ? $commission : 0;

                if ($is_claimable == 1) {
                    $data['is_claimable'] = 1;
                }

                $total_fee += $amount;

                $fees[] = $fee;
            }
        }

        $data['fees'] = serialize($fees);
        $data['total_fee'] = (float)$total_fee;
        $net_fee = $total_fee - $discount;
        $data['net_fee'] = (float)$net_fee;

        $success = false;
        if ($project_schedule_info) {
            if (is_dev_mode()) {
                $status = $this->request->getPost('status');
                $data['status'] = $status ? $status : 0;
            }
            $success = $this->Project_payment_schedule_setup_model->ci_save($data, $project_schedule_info->id);
        } else {
            $data['created_date'] = get_current_utc_time();
            $data['is_auto_created'] = 0;
            if (is_dev_mode()) {
                $status = $this->request->getPost('status');
                $data['status'] = $status ? $status : 0;
            } else {
                $data['status'] = 0;
            }
            $options = array('project_id' => $project_id);
            $schedules = $this->Project_payment_schedule_setup_model->get_details($options)->getResult();

            $current_sort = max_attribute_in_array($schedules, 'sort');
            $data['sort'] = (int)$current_sort + 1;
            $success = $this->Project_payment_schedule_setup_model->ci_save($data);
        }

        // echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        if ($success) {
            echo json_encode(array("success" => true, 'id' => $success, 'message' => app_lang('success')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function save_project_sales_forecast()
    {
        $project_id = $this->request->getPost("project_id");
        $project_options = array('project_id' => $project_id);
        $project_fee_info = $this->Project_fees_model->get_details($project_options)->getRow();

        $revenue_discount = $this->request->getPost('revenue_discount');

        $data['revenue_discount'] =  $revenue_discount;

        $success = false;
        if ($project_fee_info) {
            $success = $this->Project_fees_model->ci_save($data, $project_fee_info->id);
        }

        if ($success) {
            $this->_handle_project_net_revenue($project_id);
            echo json_encode(array("success" => true, 'id' => $success, 'message' => app_lang('success')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function save_project_fees()
    {
        $project_id = $this->request->getPost("project_id");
        $project_options = array('project_id' => $project_id);
        $project_fee_info = $this->Project_fees_model->get_details($project_options)->getRow();
        $partners_info = $this->Project_partners_model->get_details($project_options)->getResult();

        $rows_count = (int)$this->request->getPost('rows_count');
        $discount = (float)$this->request->getPost('discount');
        $discount_installments = $this->request->getPost('discount_installments');
        $discount_total = $discount * (float)$discount_installments;

        $data = array(
            'project_id' => $project_id,
            'amount_title' => $this->request->getPost('amount_title'),
            'installments_title' => $this->request->getPost('installments_title'),
            'claimable_title' => $this->request->getPost('claimable_title'),
            'rows_count' => $rows_count,
            'fee_option_name' => $this->request->getPost('fee_option_name'),
            'country_of_residency' => $this->request->getPost('country_of_residency'),
            'installment_type' => $this->request->getPost('installment_type'),
            'installment_start_date' => $this->request->getPost('installment_start_date'),
            'discount' => $discount,
            'discount_installments' => $discount_installments,
            'discount_total' => $discount_total
        );

        $fees = array();
        $total_fee = 0;
        $partners = array();

        if ($rows_count) {
            for ($x = 1; $x <= $rows_count; $x++) {
                $fee = array();

                $amount = (float)$this->request->getPost('amount_' . $x);
                $installments = (int)$this->request->getPost('installments_' . $x);
                $row_total = $amount  * $installments;

                $fee['key'] = $x;
                $fee['fee_type'] = $this->request->getPost('fee_type_' . $x);
                $fee['amount'] = $amount;
                $fee['installments'] = $installments;
                $fee['claimable_installments'] = $this->request->getPost('claimable_installments_' . $x);
                $fee['taxable'] = $this->request->getPost('taxable_' . $x);
                $fee['row_total'] = (float)$row_total;

                $total_fee += (float)$row_total;

                $fees[] = $fee;
            }

            if ($partners_info && count($partners_info)) {
                foreach ($partners_info as $partner) {
                    $partner_total_revenue = 0;
                    $revenues = array();
                    for ($x = 1; $x <= $rows_count; $x++) {
                        if ((float)$this->request->getPost('claimable_installments_' . $x) > 0) {
                            $revenue = array();

                            $revenue['key'] = $x;
                            $revenue['fee_type'] = $this->request->getPost('fee_type_' . $x);
                            $revenue['amount'] = $this->request->getPost('amount_' . $x);
                            $revenue['installments'] = $this->request->getPost('installments_' . $x);
                            $revenue['claimable_installments'] = $this->request->getPost('claimable_installments_' . $x);

                            $row_total_revenue = (float)$this->request->getPost('amount_' . $x) * (float)$this->request->getPost('claimable_installments_' . $x);
                            $revenue['row_total'] = $row_total_revenue;

                            $revenues[] = $revenue;
                            $partner_total_revenue += $row_total_revenue;
                        }
                    }

                    $_partner = array();

                    $partner_revenue = ((float)$partner->commission / 100) * $partner_total_revenue;
                    $_partner['partner_id'] = $partner->partner_id;
                    $_partner['full_name'] = $partner->full_name;
                    $_partner['revenue'] = $partner_revenue;
                    $_partner['commission'] = $partner->commission;
                    $_partner['revenues'] = $revenues;

                    $partners[] = $_partner;
                }
            }
        }

        $revenue_discount = $project_fee_info && $project_fee_info->revenue_discount ? $project_fee_info->revenue_discount : 0;

        if ($this->request->getPost('first_invoice_date')) {
            $data['first_invoice_date'] = $this->request->getPost('first_invoice_date');
        }

        $data['fees'] = serialize($fees);
        $data['partners'] = serialize($partners);
        $data['total_fee'] = (float)$total_fee;
        $net_total = $total_fee - $discount_total;
        $data['net_total'] = (float)$net_total;

        $data['revenue_discount'] =  $revenue_discount;

        $success = false;
        if ($project_fee_info) {
            $success = $this->Project_fees_model->ci_save($data, $project_fee_info->id);
        } else {
            $data['created_date'] = get_current_utc_time();
            $success = $this->Project_fees_model->ci_save($data);
        }

        if ($success) {
            if ($this->request->getPost('first_invoice_date')) {
                $this->_handle_auto_payment_schedule($project_id);
            }
            $this->_handle_project_net_revenue($project_id);
            echo json_encode(array("success" => true, 'id' => $success, 'message' => app_lang('success')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function _handle_partners_revenue($project_id = 0)
    {
        if ($project_id) {
            $project_options = array('project_id' => $project_id);
            $project_fee_info = $this->Project_fees_model->get_details($project_options)->getRow();
            $partners_info = $this->Project_partners_model->get_details($project_options)->getResult();

            if ($partners_info && $project_fee_info) {
                $rows_count = (int)$project_fee_info->rows_count;

                $data = array();
                $total_fee = 0;
                $partners = array();

                if ($rows_count) {
                    $fees = json_decode(json_encode(unserialize($project_fee_info->fees)), true);
                    foreach ($fees as $fee) {
                        $amount = get_array_value($fee, 'amount');
                        $installments = get_array_value($fee, 'installments');
                        $row_total = (float)$amount * (float)$installments;
                        $total_fee += $row_total;
                    }

                    if ($partners_info && count($partners_info)) {
                        foreach ($partners_info as $partner) {
                            $partner_total_revenue = 0;
                            $revenues = array();
                            foreach ($fees as $key => $fee) {
                                $x = (int)$key + 1;
                                if ((int)get_array_value($fee, 'claimable_installments') > 0) {
                                    $revenue = array();

                                    $revenue['key'] = $x;
                                    $revenue['fee_type'] = get_array_value($fee, 'fee_type');
                                    $revenue['amount'] = get_array_value($fee, 'amount');
                                    $revenue['installments'] = get_array_value($fee, 'installments');
                                    $revenue['claimable_installments'] = get_array_value($fee, 'claimable_installments');

                                    $row_total_revenue = (float)get_array_value($fee, 'amount') * (float)get_array_value($fee, 'claimable_installments');
                                    $revenue['row_total'] = $row_total_revenue;

                                    $revenues[] = $revenue;
                                    $partner_total_revenue += $row_total_revenue;
                                }
                            }

                            $_partner = array();

                            $partner_revenue = ((float)$partner->commission / 100) * $partner_total_revenue;
                            $_partner['partner_id'] = $partner->partner_id;
                            $_partner['full_name'] = $partner->full_name;
                            $_partner['revenue'] = $partner_revenue;
                            $_partner['commission'] = $partner->commission;
                            $_partner['revenues'] = $revenues;

                            $partners[] = $_partner;
                        }
                    }
                }

                $revenue_discount = $project_fee_info && $project_fee_info->revenue_discount ? $project_fee_info->revenue_discount : 0;

                $data['partners'] = serialize($partners);
                $data['revenue_discount'] =  $revenue_discount;

                if ($project_fee_info) {
                    $success = $this->Project_fees_model->ci_save($data, $project_fee_info->id);
                    if ($success) {
                        $this->_handle_project_net_revenue($project_id);
                    }
                }
            }
        }
    }

    function delete_schedule()
    {
        $this->access_only_team_members();
        $project_id = $this->request->getPost('project_id');
        $this->init_project_permission_checker($project_id);

        $schedule_id = $this->request->getPost('id');

        if ($this->request->getPost('undo')) {
            if ($this->Project_payment_schedule_setup_model->delete($schedule_id, true)) {
                echo json_encode(array("success" => true, "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Project_payment_schedule_setup_model->delete($schedule_id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }


    /* prepare suggestion of invoice item */
    function get_project_title_suggestion()
    {
        $key = $this->request->getPost("q");
        $suggestion = array(array("id" => "+", "text" => "+ " . app_lang("create_new_title")));

        $items = $this->Temp_Product_model->get_title_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->id, "text" => $item->product_name);
        }

        // $suggestion[] = array("id" => "+", "text" => "+ " . app_lang("create_new_title"));

        echo json_encode($suggestion);
    }

    private function _handle_auto_payment_schedule($project_id = 0)
    {
        $project_fees_option = array('project_id' => $project_id);
        $project_fee_info = $this->Project_fees_model->get_details($project_fees_option)->getRow();
        $project_info = $this->Projects_model->get_one($project_id);
        $project_partner = $this->Project_partners_model->get_details(array('project_id' => $project_id, 'partner_type' => 'institute'))->getRow();

        if ($project_fee_info && $project_fee_info->first_invoice_date) {
            // $data = $this->_get_payment_schedule_info($project_id);

            // if (!get_array_value($data, 'is_payment_invoiced') && !get_array_value($data, 'is_auto_payment')) {
            if ($project_fee_info->fees) {
                $fees = unserialize($project_fee_info->fees);
                $installment_prefix = $this->_get_payment_installment_prefix($project_fee_info->installment_type);

                $default_gap_in_days = $this->_get_gap_btw_installments_in_days($project_fee_info->installment_type);
                $gap_in_days = 0;
                $installment_date = $project_fee_info->first_invoice_date;

                if ($fees) {
                    $fees = json_decode(json_encode($fees), true);

                    $max_installments = max_attribute_in_array($fees, 'installments');

                    for ($x = 1; $x <= $max_installments; $x++) {
                        $installment = array(
                            'project_id' => $project_id,
                            'client_id' => $project_info->client_id,
                            'installment_name' => $installment_prefix . ' ' . $x
                        );

                        $installment_fees = array();
                        $installment_total_amount = 0;
                        $installment_is_claimable = 0;

                        foreach ($fees as $fee) {
                            if ($x <= (int)get_array_value($fee, 'installments')) {
                                $installment_fee = array();
                                $installment_fee['key'] = get_array_value($fee, 'key');
                                $installment_fee['fee_type'] = get_array_value($fee, 'fee_type');
                                $installment_fee['is_taxable'] = get_array_value($fee, 'taxable');
                                $installment_fee['amount'] = (float)get_array_value($fee, 'amount');
                                $installment_fee['commission'] = $project_partner ? $project_partner->commission : 0;
                                if ((int)get_array_value($fee, 'claimable_installments') > 0 && $x <= (int)get_array_value($fee, 'claimable_installments')) {
                                    $installment_fee['is_claimable'] = 1;
                                    $installment_is_claimable = 1;
                                }
                                $installment_total_amount += (float)get_array_value($fee, 'amount');

                                $installment_fees[] = $installment_fee;
                            }
                        }

                        if ($x <= (int)$project_fee_info->discount_installments) {
                            $installment['discount'] = (float)$project_fee_info->discount;
                            $installment['net_fee'] = (float)$installment_total_amount - (float)$project_fee_info->discount;
                        } else {
                            $installment['net_fee'] = (float)$installment_total_amount;
                        }

                        $installment['is_claimable'] = $installment_is_claimable;
                        $installment['total_fee'] = (float)$installment_total_amount;
                        $installment['fees'] = serialize($installment_fees);
                        $installment['rows_count'] = count($installment_fees);
                        $installment['invoice_date'] = $installment_date;
                        $installment['due_date'] = $installment_date;
                        // $installment['due_date'] = date_create($installment_date)->modify("+7 days")->format('Y-m-d');
                        $installment['is_auto_created'] = 1;
                        $installment['sort'] = $x;
                        $installment['status'] = 0;
                        $installment['created_date'] = get_current_utc_time();

                        $this->Project_payment_schedule_setup_model->ci_save($installment);

                        $gap_in_days += $default_gap_in_days; // double the gap for next installment
                        $installment_date = date_create($project_fee_info->first_invoice_date)->modify("+" . $gap_in_days . " days")->format('Y-m-d');
                    }
                }
            }
            // }
        }
    }

    private function _get_payment_schedule_overview_info($project_id = 0)
    {
        $payment_options =  array('project_id' => $project_id);
        $payment_schedules = $this->Project_payment_schedule_setup_model->get_details($payment_options)->getResult();
        // $project_fees = $this->Project_fees_model->get_details($payment_options)->getRow();

        $scheduled_amount  = 0;
        $invoiced_amount   = 0;
        $pending_amount    = 0;
        $diff_amount       = 0;
        $total_fees        = 0;
        $total_claimable   = 0;
        $total_discount    = 0;
        $institute_revenue = 0;
        $subagent_revenue  = 0;
        $referral_revenue  = 0;
        $net_revenue       = 0;

        $institute = $this->Project_partners_model->get_details(array('project_id' => $project_id, 'partner_type' => 'institute'))->getRow();
        $subagents = $this->Project_partners_model->get_details(array('project_id' => $project_id, 'partner_type' => 'subagent'))->getResult();
        $referrals = $this->Project_partners_model->get_details(array('project_id' => $project_id, 'partner_type' => 'referral'))->getResult();

        // if ($payment_schedules && $project_fees) {
        if ($payment_schedules) {
            foreach ($payment_schedules as $ps) {
                if ((int)$ps->status == 0 || (int)$ps->status == 2 || (int)$ps->status == 5) { // scheduled payment
                    $scheduled_amount += $ps->net_fee + (is_dev_mode() ? (float)$ps->discount : 0);
                } elseif ((int)$ps->status == 1 || (int)$ps->status == 3) { // invoiced payment, even if the invoice is removed
                    $invoiced_amount += $ps->net_fee + (is_dev_mode() ? (float)$ps->discount : 0);
                } elseif ((int)$ps->status == 4) {
                    $pending_amount += $ps->net_fee;
                }

                $total_discount += (float)$ps->discount;

                $fees = unserialize($ps->fees);

                foreach ($fees as $fee) {
                    $fee = json_decode(json_encode($fee), true);

                    $amount       = (float)get_array_value($fee, 'amount');
                    $is_claimable = (float)get_array_value($fee, 'is_claimable');
                    $total_fees += (float)$amount;

                    if ($is_claimable && $institute) {
                        $claimable_commission = calc_per($amount, $institute->commission);
                        $total_claimable += $claimable_commission;

                        $institute_revenue += $amount - $claimable_commission; // deduct commission form the amount. The remaining amount goes to the institute.
                    } elseif ($institute) {
                        // Fee is not claimable. So the amount goes to the institute
                        $institute_revenue += $amount; // deduct commission form the amount. The remaining amount goes to the institute.
                    }
                }
            }
        }

        $net_income = $total_claimable - $total_discount;

        if ($subagents) {
            foreach ($subagents as $subagent) {
                $subagent_revenue += calc_per($net_income, $subagent->commission);
            }
        }

        if ($referrals) {
            foreach ($referrals as $referral) {
                $referral_revenue += calc_per($net_income, $referral->commission);
            }
        }

        $net_revenue = $net_income - ($subagent_revenue + $referral_revenue);

        // if ($append) {
        $append = new \stdClass();
        $append->scheduled_amount   = $scheduled_amount;
        $append->invoiced_amount    = $invoiced_amount;
        $append->pending_amount     = $pending_amount;
        $append->diff_amount        = $diff_amount;
        $append->total_fees         = $total_fees;
        $append->total_discount     = $total_discount;
        $append->total_claimable    = $total_claimable;
        $append->net_income         = $net_income;
        $append->institute_revenue  = $institute_revenue;
        $append->subagent_revenue   = $subagent_revenue;
        $append->referral_revenue   = $referral_revenue;
        $append->net_revenue        = $net_revenue;

        return $append;
        // }

        // $data['scheduled_amount'] = $scheduled_amount;
        // $data['invoiced_amount'] = $invoiced_amount;
        // $data['pending_amount'] = $pending_amount;
        // $data['diff_amount'] = $diff_amount;

        // return $data;
    }

    // private function _get_payment_schedule_overview_info($project_id = 0, $append = null)
    // {
    //     $payment_options =  array('project_id' => $project_id);
    //     $payment_schedules = $this->Project_payment_schedule_setup_model->get_details($payment_options)->getResult();
    //     $project_fees = $this->Project_fees_model->get_details($payment_options)->getRow();

    //     $scheduled_amount = 0;
    //     $invoiced_amount = 0;
    //     $pending_amount = 0;
    //     $diff_amount = 0;

    //     if ($payment_schedules && $project_fees) {
    //         foreach ($payment_schedules as $ps) {
    //             if ((int)$ps->status == 0 || (int)$ps->status == 2 || (int)$ps->status == 5) { // scheduled payment
    //                 $scheduled_amount += $ps->net_fee + (is_dev_mode() ? (float)$ps->discount : 0);
    //             } elseif ((int)$ps->status == 1 || (int)$ps->status == 3) { // invoiced payment, even if the invoice is removed
    //                 $invoiced_amount += $ps->net_fee + (is_dev_mode() ? (float)$ps->discount : 0);
    //             } elseif ((int)$ps->status == 4) {
    //                 $pending_amount += $ps->net_fee;
    //             }
    //         }

    //         $total_scheduled_payment = $scheduled_amount + $invoiced_amount;

    //         if ($pending_amount > 0) {
    //             $pending_total = (float)$project_fees->net_total - $pending_amount;
    //             $pending_amount = $pending_total - $total_scheduled_payment;
    //         } else {
    //             $pending_amount = (float)$project_fees->net_total - $total_scheduled_payment;
    //         }

    //         if ($pending_amount < 0) {
    //             $discount = (float)$project_fees->discount * (float)$project_fees->discount_installments;
    //             $diff_amount = $discount + $pending_amount;
    //             $pending_amount = 0;
    //         }
    //     }

    //     if ($append) {
    //         $append['scheduled_amount'] = $scheduled_amount;
    //         $append['invoiced_amount'] = $invoiced_amount;
    //         $append['pending_amount'] = $pending_amount;
    //         $append['diff_amount'] = $diff_amount;

    //         return $append;
    //     }

    //     $data['scheduled_amount'] = $scheduled_amount;
    //     $data['invoiced_amount'] = $invoiced_amount;
    //     $data['pending_amount'] = $pending_amount;
    //     $data['diff_amount'] = $diff_amount;

    //     return $data;
    // }

    private function _get_payment_schedule_info($project_id = 0)
    {
        $payment_setup_options =  array('project_id' => $project_id, 'status' => 1);
        $is_payment_invoiced = $this->Project_payment_schedule_setup_model->get_details($payment_setup_options)->getRow();

        $payment_schedule_options =  array('project_id' => $project_id);
        $is_payment_schedule = $this->Project_payment_schedule_setup_model->get_details($payment_schedule_options)->getRow();

        $payment_setup_options =  array('project_id' => $project_id, 'is_auto_created' => 1);
        $is_auto_payment = $this->Project_payment_schedule_setup_model->get_details($payment_setup_options)->getRow();

        $view_data['is_payment_schedule'] = $is_payment_schedule ? true : false;
        $view_data['is_payment_invoiced'] = $is_payment_invoiced ? true : false;
        $view_data['is_auto_payment'] = $is_auto_payment ? true : false;

        return $view_data;
    }

    private function _get_gap_btw_installments_in_days($installment_type = 'Full Fee')
    {
        $days = 30;

        switch ($installment_type) {
            case 'Full Fee':
            case 'Per Month':
                // default:
                $days = 30; // 1 Month
                break;
            case 'Installment':
                // default:
                $days = 30 * 4; // set to 4 Month(s) on AgentCIS
                break;
            case 'Per Year':
                $days = 365; // 1 Year
                break;
            case 'Per Term':
                $days = 7 * 10; // approx. 10 weeks in AU
                break;
            case 'Per Trimester':
                $days = 30 * 3; // 3 Months
                break;
            case 'Per Semester':
                $days = 30 * 6; // 6 Months
                break;
            case 'Per Week':
                $days = 7; // 1 Week
                break;
        }

        return $days;
    }

    private function _get_payment_installment_prefix($installment_type = 'Full Fee')
    {
        $prefix = 'Installment';

        switch ($installment_type) {
            case 'Full Fee':
                $prefix = 'Fee';
                break;
            case 'Per Month':
                $prefix = "Month";
                break;
            case 'Installment':
                $prefix = "Installment";
                break;
            case 'Per Year':
                $prefix = "Year";
                break;
            case 'Per Term':
                $prefix = "Term";
                break;
            case 'Per Trimester':
                $prefix = "Trimester";
                break;
            case 'Per Semester':
                $prefix = "Semester";
                break;
            case 'Per Week':
                $prefix = "Week";
                break;
            default:
                $prefix = "Installment";
                break;
        }

        return $prefix;
    }

    private function _get_payment_installment_status_pill($status = 0)
    {
        $label = $this->_get_payment_installment_status_label($status);

        $pill = '<span class="badge badge-warning">' . $label . '</span>';

        switch ($status) {
            case '0':
            case 0:
                $pill = '<span class="badge" style="background-color: #198754;">' . $label . '</span>';
                break;
            case '1':
            case 1:
                $pill = '<span class="badge" style="background-color: #0d6efd;">' . $label . '</span>';
                break;
            case '2':
            case 2:
                $pill = '<span class="badge" style="background-color: #dc3545;">' . $label . '</span>';
                break;
            case '3':
            case 3:
                $pill = '<span class="badge" style="background-color: #dc3545;">' . $label . '</span>';
                break;
            case '4':
            case 4:
                $pill = '<span class="badge" style="background-color: #8E44AD;">' . $label . '</span>';
                break;
            case '5':
            case 5:
                $pill = '<span class="badge" style="background-color: #566573;">' . $label . '</span>';
                break;
        }

        return $pill;
    }

    private function _get_payment_installment_status_label($status = 0)
    {
        $status_label = app_lang('scheduled');
        switch ($status) {
            case '0':
            case 0:
                $status_label = app_lang('scheduled');
                break;
            case '1':
            case 1:
                $status_label = app_lang('invoiced');
                break;
            case '2':
            case 2:
                $status_label = app_lang('discontinued');
                break;
            case '3':
            case 3:
                $status_label = app_lang('invoice_removed');
                break;
            case '4':
            case 4:
                $status_label = app_lang('pending');
                break;
            case '5':
            case 5:
                $status_label = app_lang('expired');
                break;
        }

        return $status_label;
    }

    private function _get_project_status_label($status_id = 0, $comp = 'strong')
    {

        $status = 'Open';

        if ($status_id == 1) {
            $status = "<$comp class=\"text-info\">• Open</$comp>";
        } elseif ($status_id == 2) {
            $status = "<$comp class=\"text-success\">• Completed</$comp>";
        } elseif ($status_id == 3) {
            $status = "<$comp class=\"text-warning\">• Hold</$comp>";
        } elseif ($status_id == 4) {
            $status = "<$comp class=\"text-danger\">• Canceled</$comp>";
        }

        return $status;
    }

    private function _get_project_partner_type($partner_id = 0)
    {
        $partner_type = '';
        if ($partner_id) {
            $partner = $this->Clients_model->get_one($partner_id);
            if ($partner && $partner->partner_type) {
                $partner_type = $partner->partner_type;
            }
        }
        return $partner_type;
    }

    private function _get_project_partner_type_pill($partner_id = 0)
    {
        $partner_type = $this->_get_project_partner_type($partner_id);

        if ($partner_type) {
            return "<span class='mt0 badge' style='background-color:#2C3E50;' title=" . app_lang($partner_type) . ">" . app_lang($partner_type) . "</span>";
        }
        return '';
    }

    private function _handle_project_net_revenue($project_id = 0)
    {
        if ($project_id) {
            $project_options = array('project_id' => $project_id);
            $project_fee_info = $this->Project_fees_model->get_details($project_options)->getRow();
            $project_partners = $this->Project_partners_model->get_details($project_options)->getResult();

            if ($project_fee_info) {
                $project_partners = json_decode(json_encode(unserialize($project_fee_info->partners)), true);
                $referral_revenue = 0;
                $institute_revenue = 0;
                $claimable_revenue = 0;
                $_partners = array();
                foreach ($project_partners as $partner) {
                    $partner_id = get_array_value($partner, 'partner_id');
                    $commission = get_array_value($partner, 'commission');
                    $partner_type = $this->_get_project_partner_type($partner_id);

                    if ($partner_type == 'institute') {
                        $_institute_revenue = ((float)$commission / 100) * (float)$project_fee_info->net_total;
                        $institute_revenue += $_institute_revenue;
                    }
                }
                $claimable_revenue = (float)$project_fee_info->net_total - $institute_revenue;
                foreach ($project_partners as $partner) {
                    $partner_id = get_array_value($partner, 'partner_id');
                    $commission = get_array_value($partner, 'commission');
                    $partner_type = $this->_get_project_partner_type($partner_id);

                    if ($partner_type == 'referral') {
                        $referral_revenue += ((float)$commission / 100) * (float)$claimable_revenue;
                        $partner['revenue'] = $referral_revenue;

                        $_partners[] = $partner;
                    } else {
                        $_partners[] = $partner;
                    }
                }

                $data['partners'] = serialize($_partners);
                if (is_dev_mode()) {
                    $institute_revenue = $this->request->getPost('institute_revenue') ? $this->request->getPost('institute_revenue') : $institute_revenue;
                    $referral_revenue =  $this->request->getPost('referral_revenue') ? $this->request->getPost('referral_revenue') : $referral_revenue;
                    $data['institute_revenue'] = $institute_revenue;
                    $data['referral_revenue'] = $referral_revenue;
                    $data['total_revenue'] = $institute_revenue + $referral_revenue - (float)$project_fee_info->revenue_discount;
                    $data['claimable_revenue'] = $claimable_revenue;
                    $data['net_revenue'] = ((float)$project_fee_info->net_total - (float)$project_fee_info->revenue_discount) - ($referral_revenue + $institute_revenue);
                } else {
                    $data['institute_revenue'] = $institute_revenue;
                    $data['referral_revenue'] = $referral_revenue;
                    $data['total_revenue'] = $institute_revenue + $referral_revenue - (float)$project_fee_info->revenue_discount;
                    $data['claimable_revenue'] = $claimable_revenue;
                    $data['net_revenue'] = ((float)$project_fee_info->net_total - (float)$project_fee_info->revenue_discount) - ((float)$referral_revenue + (float)$institute_revenue);
                }


                $this->Project_fees_model->ci_save($data, $project_fee_info->id);
            }
        }
    }

    private function _handle_doc_check_list($doc_check_list_id = 0, $project_id = 0)
    {
        $success = false;
        $options = array('project_id' => $project_id);
        $doc_check_list_data = $this->Project_doc_check_list_model->get_details($options)->getResult();
        if ($doc_check_list_id && count($doc_check_list_data) == 0) {
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

    private function _make_clients_summary_row($data)
    {
        $route_prefix = $this->get_client_view_route($data->client_id);
        $full_name = $this->get_client_full_name($data->client_id);
        $client_name = anchor(get_uri("$route_prefix/view/" . $data->client_id), $full_name);

        $duration = convert_seconds_to_time_format($data->total_secconds_worked);

        $row_data = array(
            $client_name,
            $data->open_projects ? $data->open_projects : 0,
            $data->completed_projects ? $data->completed_projects : 0,
            $data->hold_projects ? $data->hold_projects : 0,
            $data->open_tasks ? $data->open_tasks : 0,
            $data->completed_tasks ? $data->completed_tasks : 0,
            $duration,
            to_decimal_format(convert_time_string_to_decimal($duration)),
        );

        return $row_data;
    }

    private function client_can_view_tasks()
    {
        if ($this->login_user->user_type != "staff") {
            //check settings for client's project permission
            if (get_setting("client_can_view_tasks")) {
                //even the settings allow to create/edit task, the client can only create their own project's tasks
                return $this->is_clients_project;
            }
        }
    }
}

/* End of file projects.php */
/* Location: ./app/controllers/projects.php */