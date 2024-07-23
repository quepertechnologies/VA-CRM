<?php

namespace App\Controllers;

class Check_in extends Security_Controller
{

    function __construct()
    {
        parent::__construct();

        //this module is accessible only to team members 
        $this->access_only_team_members();

        //we can set ip restiction to access this module. validate user access
        $this->check_allowed_ip();

        //initialize managerial permission
        $this->init_permission_checker("attendance");
    }

    //check ip restriction for none admin users
    private function check_allowed_ip()
    {
        if (!$this->login_user->is_admin) {
            $ip = get_real_ip();
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses");
            if ($allowed_ips) {
                $allowed_ip_array = array_map('trim', preg_split('/\R/', $allowed_ips));
                if (!in_array($ip, $allowed_ip_array)) {
                    app_redirect("forbidden");
                }
            }
        }
    }

    //only admin or assigend members can access/manage other member's attendance
    protected function access_only_allowed_members($user_id = 0)
    {
        if ($this->access_type !== "all") {
            if ($user_id === $this->login_user->id || !array_search($user_id, $this->allowed_members)) {
                app_redirect("forbidden");
            }
        }
    }

    //show attendance list view
    function index($tab = "")
    {
        $this->check_module_availability("module_attendance");

        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $view_data['tab'] = clean_data($tab); //selected tab
        //$view_data['account_types_filter_dropdown'] = $this->get_account_types_dropdown_for_filter();
        return $this->template->rander("check_in/index", $view_data);
    }

    //show add/edit attendance modal
    function modal_form()
    {
        $user_id = 0;

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $view_data['model_info'] = $this->Check_in_model->get_one($this->request->getPost('id'));
        $view_data['client_info'] = $this->Clients_model->get_one($view_data['model_info']->client_id);
        $view_data["countries_dropdown"] = $this->get_countries_dropdown([]);
        $view_data['account_types_filter_dropdown'] = $this->make_type_dropdown();
        // if ($view_data['model_info']->id) {
        //     $user_id = $view_data['model_info']->created_by;

        //     $this->access_only_allowed_members($user_id, true);
        // }

        if ($user_id) {
            //edit mode. show user's info
            $view_data['team_members_info'] = $this->Users_model->get_one($user_id);
        } else {
            //new add mode. show users dropdown
            //don't show none allowed members in dropdown
            if ($this->access_type === "all") {
                $where = array("user_type" => "staff");
            } else {
                if (!count($this->allowed_members)) {
                    app_redirect("forbidden");
                }
                $where = array("user_type" => "staff", "id !=" => $this->login_user->id, "where_in" => array("id" => $this->allowed_members));
            }

            $view_data['team_members_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);
        }

        $view_data['label_column'] = "col-md-3";
        $view_data['field_column'] = "col-md-9";
        $view_data["team_members_dropdown"] = $this->get_team_members_dropdown();
        $view_data['account_types_filter_dropdown'] = $this->make_type_dropdown();
        // $view_data["clients_dropdown"] = $this->get_clients_dropdown();
        $view_data["locations_dropdown"] = $this->make_locations_dropdown('-');
        $view_data["login_user"] = $this->login_user;

        return $this->template->view('check_in/modal_form', $view_data);
    }

    //show attendance note modal
    function note_modal_form($user_id = 0)
    {
        $this->validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $view_data["clock_out"] = $this->request->getPost("clock_out"); //trigger clockout after submit?
        $view_data["user_id"] = clean_data($user_id);

        $view_data['model_info'] = $this->Check_in_model->get_one($this->request->getPost('id'));
        return $this->template->view('check_in/note_modal_form', $view_data);
    }

    //add/edit attendance record
    function save()
    {
        $id = $this->request->getPost('id');

        $this->validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required",
        ));

        $data = array(
            'location_id' => $this->request->getPost('location_id') ? $this->request->getPost('location_id') : get_ltm_opl_id(),
            "first_name" => $this->request->getPost('first_name'),
            "last_name" => $this->request->getPost('last_name'),
            "email" => $this->request->getPost('email'),
            "note" => $this->request->getPost('note'),
            "status" => $this->request->getPost('status'),
            "assignee" => $this->request->getPost('assignee') ? $this->request->getPost('assignee') : 0,
            'created_date' => get_current_utc_time(),
            'created_by' => $this->login_user->id,
        );

        $save_id = $this->Check_in_model->ci_save($data, $id);
        if ($save_id == $id) {
            if ($save_id) {
                $clientID = $this->Check_in_model->get_one($save_id);
                $client_data = array(
                    "first_name" => $this->request->getPost('first_name'),
                    "last_name" => $this->request->getPost('last_name'),
                    'location_id' => $this->request->getPost('location_id') ? $this->request->getPost('location_id') : 0,
                    'created_by_location_id' => get_ltm_opl_id(),
                    "unique_id" => uniqid('VA' . date('-y-')),
                    "type" => 'person',
                    "phone_code" => $this->request->getPost('phone_code'),
                    "phone" => $this->request->getPost('phone'),
                    "account_type" => $this->request->getPost('account_type'),
                    "created_date" => get_current_utc_time(),
                    "currency_symbol" => '',
                    "currency" => '',
                    "disable_online_payment" => 0,
                    "created_by" => $this->login_user->id,
                    'is_lead' => 1,
                    'current_lead_status' => 'check_in',
                );
                $client_data = clean_data($client_data);
                if ($clientID) {
                    $client_id = $this->Clients_model->ci_save($client_data, $clientID->client_id);
                } else {
                    $client_id = $this->Clients_model->ci_save($client_data);
                }

                $location_id = $this->request->getPost('location_id') ? $this->request->getPost('location_id') : 0;
                if ($client_id) {
                    $check_in_data = array('client_id' => $client_id);
                    $this->Check_in_model->ci_save($check_in_data, $save_id);
                    $this->save_primary_contact($client_id, $location_id);
                }

                echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => $id ? true : false, 'message' => app_lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        } else {
            if ($save_id) {
                $clientID = $this->Check_in_model->get_one($save_id);
                if (!$this->Users_model->is_email_exists($this->request->getPost('email'))) {
                    $client_data = array(
                        "first_name" => $this->request->getPost('first_name'),
                        "last_name" => $this->request->getPost('last_name'),
                        'location_id' => $this->request->getPost('location_id') ? $this->request->getPost('location_id') : 0,
                        'created_by_location_id' => get_ltm_opl_id(),
                        "unique_id" => uniqid('VA' . date('-y-')),
                        "type" => 'person',
                        "account_type" => $this->request->getPost('account_type'),
                        "email" => $this->request->getPost('email'),
                        "phone_code" => $this->request->getPost('phone_code'),
                        "phone" => $this->request->getPost('phone'),
                        "created_date" => get_current_utc_time(),
                        "currency_symbol" => '',
                        "currency" => '',
                        "disable_online_payment" => 0,
                        "created_by" => $this->login_user->id,
                        'is_lead' => 1,
                        'current_lead_status' => 'check_in',
                    );
                    $client_data = clean_data($client_data);
                    if ($clientID) {
                        $client_id = $this->Clients_model->ci_save($client_data, $clientID->client_id);
                    } else {
                        $client_id = $this->Clients_model->ci_save($client_data);
                    }

                    $location_id = $this->request->getPost('location_id') ? $this->request->getPost('location_id') : 0;
                    if ($client_id) {
                        $check_in_data = array('client_id' => $client_id);
                        $this->Check_in_model->ci_save($check_in_data, $save_id);
                        $this->save_primary_contact($client_id, $location_id);
                        $timeline_data = array(
                            'client_id' => $client_id,
                            'title' => 'Account Created upon check in'
                        );
                        $this->Timeline_model->ci_save($timeline_data);
                    }
                }

                echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => $id ? true : false, 'message' => app_lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        }
    }

    function convert_to_lead($check_in_id = 0)
    {
        $check_in = $this->Check_in_model->get_one($check_in_id);
        if ($check_in) {
            $undo = $this->request->getPost('undo') ? true : false;
            if ($undo) {
                $lead_data = array('current_lead_status' => 'check_in', 'lead_status_id' => 0);
            } else {
                $lead_data = array('current_lead_status' => 'lead', 'lead_status_id' => 1);
            }
            $lead_id = $this->Clients_model->ci_save($lead_data, $check_in->client_id);
            if ($lead_id && $this->Check_in_model->delete($check_in_id, $undo)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($check_in_id), 'message' => app_lang('converted_to_lead')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    function save_primary_contact($client_id = 0, $location_id = 0)
    {
        if ($client_id) {
            $user_phone = "";
            $pwd = bin2hex(openssl_random_pseudo_bytes(4));
            if ($this->request->getPost('phone_code') && $this->request->getPost('phone')) {
                $user_phone = $this->request->getPost('phone_code') . '' . $this->request->getPost('phone');
            }
            $user_data = array(
                "client_id" => $client_id,
                "first_name" => $this->request->getPost('first_name'),
                "last_name" => $this->request->getPost('last_name'),
                'location_id' => $location_id,
                "email" => $this->request->getPost('email'),
                "password" => password_hash($pwd, PASSWORD_DEFAULT),
                "phone" => $user_phone,
                "job_title" => "",
                "note" => "Primary Contact (Auto Created on check in)",
                "created_at" => get_current_utc_time(),
                "gender" => "",
                "skype" => "",
            );

            //validate duplicate email address
            if (!$this->Users_model->is_email_exists($user_data["email"], 0, $client_id)) {

                $user_data['is_primary_contact'] = 1;

                $user_data = clean_data($user_data);

                $save_id = $this->Users_model->ci_save($user_data);
                $client_data = array('primary_contact_id' => $save_id);
                $this->Clients_model->ci_save($client_data, $client_id);

                save_custom_fields("client_contacts", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

                if (!is_server_localhost()) {
                    $email_template = $this->Email_templates_model->get_final_template("login_info"); //use default template since creating a new contact

                    $parser_data["SIGNATURE"] = $email_template->signature;
                    $parser_data["USER_FIRST_NAME"] = $user_data["first_name"];
                    $parser_data["USER_LAST_NAME"] = $user_data["last_name"];
                    $parser_data["USER_LOGIN_EMAIL"] = $user_data["email"];
                    $parser_data["USER_LOGIN_PASSWORD"] = $pwd;
                    $parser_data["DASHBOARD_URL"] = base_url();
                    $parser_data["LOGO_URL"] = get_logo_url();

                    $message = $this->parser->setData($parser_data)->renderString($email_template->message);
                    $subject = $this->parser->setData($parser_data)->renderString($email_template->subject);

                    send_app_mail($user_data["email"], $subject, $message);
                }
            }
        }
    }

    //edit attendance note
    function save_note()
    {
        $id = $this->request->getPost('id');

        $this->validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $data = array(
            "note" => $this->request->getPost('note')
        );


        $save_id = $this->Check_in_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => true, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    //clock in / clock out
    function log_time($user_id = 0)
    {
        $note = $this->request->getPost('note');

        if ($user_id && $user_id != $this->login_user->id) {
            //check if the login user has permission to clock in/out this user
            $this->access_only_allowed_members($user_id);
        }

        $this->Attendance_model->log_time($user_id ? $user_id : $this->login_user->id, $note);

        if ($user_id) {
            echo json_encode(array("success" => true, "data" => $this->_clock_in_out_row_data($user_id), 'id' => $user_id, 'message' => app_lang('record_saved'), "isUpdate" => true));
        } else if ($this->request->getPost("clock_out")) {
            echo json_encode(array("success" => true, "clock_widget" => clock_widget()));
        } else {
            return clock_widget();
        }
    }

    //delete/undo attendance record
    function delete()
    {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if ($this->access_type !== "all") {
            $info = $this->Check_in_model->get_one($id);
            $this->access_only_allowed_members($info->created_by);
        }

        if ($this->request->getPost('undo')) {
            if ($this->Check_in_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Check_in_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /* get clocked in members list */

    function waiting_list_data()
    {
        $user_id = $this->request->getPost('user_id');

        $options = array(
            'status' => "Waiting"
        );
        $options['location_ids'] = get_ltm_opl_id(false, ',');
        if ($user_id) {
            $options['assignee'] = $user_id;
        }
        $options = append_server_side_filtering_commmon_params($options);
        $list_data = $this->Check_in_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            // $result[] = $this->_make_all_row($data);
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* get all attendance of a given duration */

    function list_data($status = 'Attending')
    {
        $user_id = $this->request->getPost('user_id');

        $options = array();

        $options['location_ids'] = get_ltm_opl_id(false, ',');
        if ($user_id) {
            $options['assignee'] = $user_id;
        }
        if ($status !== 'All') {
            $options['status'] = $status;
        }
        $options = append_server_side_filtering_commmon_params($options);
        $list_data = $this->Check_in_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $status == 'All' ? true : false);
        }
        echo json_encode(array("data" => $result));
    }

    //load attendance attendance info view
    function attendance_info()
    {
        $this->check_module_availability("module_attendance");

        $view_data['user_id'] = $this->login_user->id;
        $view_data['show_clock_in_out'] = true;

        if ($this->request->isAJAX()) {
            return $this->template->view("team_members/attendance_info", $view_data);
        } else {
            $view_data['page_type'] = "full";
            return $this->template->rander("team_members/attendance_info", $view_data);
        }
    }

    //get a row of attendnace list
    private function _row_data($id)
    {
        // $options = array("id" => $id);
        $data = $this->Check_in_model->get_one($id);
        return $this->_make_row($data);
    }

    //prepare a row of attendance list
    private function _make_row($data, $highlight_status = false)
    {
        // $client_info = $this->Clients_model->get_one($data->client_id);
        $client_full_name = $this->get_client_full_name($data->client_id);
        $option_links = modal_anchor(get_uri("check_in/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_check_in'), "data-post-id" => $data->id, "data-reload-on-success" => true))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_check_in'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("check_in/delete"), "data-action" => "delete"));

        $convert_to_lead = js_anchor("<i data-feather='refresh-cw' class='icon-16'></i>", array('title' => app_lang('convert_to_lead'), "class" => "reload", "data-action-url" => get_uri("check_in/convert_to_lead/" . $data->id), "data-action" => "delete"));
        if ($this->access_type != "all") {
            //don't show options links for none admin user's own records
            if ($data->user_id === $this->login_user->id) {
                $option_links = "";
            }
        }

        //if the rich text editor is enabled, don't show the note as title
        $note_title = $data->note;
        if (get_setting('enable_rich_text_editor')) {
            $note_title = "";
        }

        $note_link = modal_anchor(get_uri("check_in/note_modal_form"), "<i data-feather='message-circle' class='icon-16'></i>", array("class" => "edit", "title" => app_lang("note"), "data-post-id" => $data->id));
        if ($data->note) {
            $note_link = modal_anchor(get_uri("check_in/note_modal_form"), "<i data-feather='message-circle' class='icon-16 icon-fill-secondary'></i>", array("class" => "edit", "title" => $note_title, "data-modal-title" => app_lang("note"), "data-post-id" => $data->id));
        }

        $status = $data->status;

        // if ($highlight_status) {
        //     if ($data->status == 'Waiting') {
        //         $status = $data->status;
        //     } elseif ($data->status == 'Completed') {
        //         $status = '<span class="text-success">' . $data->status . '</span>';
        //     } elseif ($data->status == 'Archived') {
        //         $status = '<span class="text-muted">' . $data->status . '</span>';
        //     } elseif ($data->status == 'Attending') {
        //         $status = '<span class="text-warning">' . $data->status . '</span>';
        //     }
        // }

        $branch = $this->get_location_label($data->location_id);

        $assignee = '-';
        if ($data->assignee) {
            $_assignee = $this->Users_model->get_one($data->assignee);
            if ($_assignee) {
                $assignee = get_team_member_profile_link($data->assignee, $_assignee->first_name . ' ' . $_assignee->last_name);
            }
        }

        $phone = '-';
        $phone = $this->Clients_model->get_one($data->client_id);
        if ($phone) {
            $phone =  $phone->email . '<br><small>' . $phone->phone_code . $phone->phone . '</small>';
        }

        $account_id = $this->Clients_model->get_one($data->client_id);
        $account_type = $this->get_account_label($account_id->account_type);

        return array(
            "<div>" . date('l', strtotime($data->created_date)) . "<br/><small>" . format_to_date($data->created_date) . "<br>" . format_to_time($data->created_date) . "</small></div>",
            anchor(get_uri("leads/view/" . $data->client_id), $client_full_name),
            $phone,
            $assignee,
            $account_type,
            $branch,
            $status,
            $note_link,
            $convert_to_lead,
            $option_links
        );
    }

    //prepare a row of attendance list
    private function _make_completed_row($data)
    {
        $image_url = get_avatar($data->created_by_avatar);
        $client_info = $this->Clients_model->get_one($data->client_id);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";
        $out_time = $data->out_time;
        if (!is_date_exists($out_time)) {
            $out_time = "";
        }

        $to_time = strtotime($data->out_time ? $data->out_time : "");
        if (!$out_time) {
            $to_time = strtotime($data->in_time ? $data->in_time : "");
        }
        $from_time = strtotime($data->in_time ? $data->in_time : "");

        $option_links = modal_anchor(get_uri("check_in/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_attendance'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_attendance'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("check_in/delete"), "data-action" => "delete"));

        if ($this->access_type != "all") {
            //don't show options links for none admin user's own records
            if ($data->user_id === $this->login_user->id) {
                $option_links = "";
            }
        }

        //if the rich text editor is enabled, don't show the note as title
        $note_title = $data->note;
        if (get_setting('enable_rich_text_editor')) {
            $note_title = "";
        }

        $note_link = modal_anchor(get_uri("check_in/note_modal_form"), "<i data-feather='message-circle' class='icon-16'></i>", array("class" => "edit text-muted", "title" => app_lang("note"), "data-post-id" => $data->id));
        if ($data->note) {
            $note_link = modal_anchor(get_uri("check_in/note_modal_form"), "<i data-feather='message-circle' class='icon-16 icon-fill-secondary'></i>", array("class" => "edit text-muted", "title" => $note_title, "data-modal-title" => app_lang("note"), "data-post-id" => $data->id));
        }

        if (isset($client_info) && $client_info->type === 'organization') {
            $name = $client_info->company_name;
        } else {
            $name = $client_info->first_name . ' ' . $client_info->last_name;
        }
        return array(
            "<div>" . date('l', strtotime($data->checked_at)) . "<br/><small>" . format_to_date($data->checked_at) . "</small></div>",
            format_to_time($data->checked_at),
            format_to_date($data->out_time),
            convert_seconds_to_time_format(abs($to_time - $from_time)),
            get_client_contact_profile_link($data->client_id, $name, array(), array('caption' => $client_info->unique_id, 'account_type' => $client_info->account_type)),
            "<div>" . ($data->client_account_type === 'agent' ? "Migration Agent" : ucfirst($data->client_account_type)) . "<br/><small>" . ucfirst($data->client_type) . "</small></div>",
            get_team_member_profile_link($data->user_id, $user),
            convert_seconds_to_time_format(abs($to_time - $from_time)),
            $data->status,
            $note_link,
            $option_links
        );
    }

    //prepare a row of attendance list
    private function _make_all_row($data)
    {
        $image_url = get_avatar($data->created_by_avatar);
        $client_info = $this->Clients_model->get_one($data->client_id);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";

        $option_links = modal_anchor(get_uri("check_in/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_attendance'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_attendance'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("check_in/delete"), "data-action" => "delete"));

        if ($this->access_type != "all") {
            //don't show options links for none admin user's own records
            if ($data->user_id === $this->login_user->id) {
                $option_links = "";
            }
        }

        //if the rich text editor is enabled, don't show the note as title
        $note_title = $data->note;
        if (get_setting('enable_rich_text_editor')) {
            $note_title = "";
        }

        $note_link = modal_anchor(get_uri("check_in/note_modal_form"), "<i data-feather='message-circle' class='icon-16'></i>", array("class" => "edit text-muted", "title" => app_lang("note"), "data-post-id" => $data->id));
        if ($data->note) {
            $note_link = modal_anchor(get_uri("check_in/note_modal_form"), "<i data-feather='message-circle' class='icon-16 icon-fill-secondary'></i>", array("class" => "edit text-muted", "title" => $note_title, "data-modal-title" => app_lang("note"), "data-post-id" => $data->id));
        }

        $name = $this->get_client_full_name(0, $client_info);

        return array(
            "<div>" . date('l', strtotime($data->checked_at)) . "<br/><small>" . format_to_date($data->checked_at) . "</small></div>",
            format_to_time($data->checked_at),
            get_client_contact_profile_link($data->client_id, $name, array(), array('caption' => $client_info->unique_id, 'account_type' => $client_info->account_type)),
            "<div>" . ($data->client_account_type === 'agent' ? "Migration Agent" : ucfirst($data->client_account_type)) . "<br/><small>" . ucfirst($data->client_type) . "</small></div>",
            get_team_member_profile_link($data->user_id, $user),
            $data->status,
            $note_link,
            $option_links
        );
    }

    //load the custom date view of attendance list 
    function attending()
    {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        //$view_data['account_types_filter_dropdown'] = $this->get_account_types_dropdown_for_filter();
        return $this->template->view("check_in/attending_list", $view_data);
    }

    //load the clocked in members list view of attendance list 
    function archived()
    {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        //$view_data['account_types_filter_dropdown'] = $this->get_account_types_dropdown_for_filter();
        return $this->template->view("check_in/archived_list", $view_data);
    }

    //load the clocked in members list view of attendance list 
    function waiting()
    {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        //$view_data['account_types_filter_dropdown'] = $this->get_account_types_dropdown_for_filter();
        return $this->template->view("check_in/waiting_list", $view_data);
    }

    private function _get_members_dropdown_list_for_filter()
    {
        //prepare the dropdown list of members
        //don't show none allowed members in dropdown
        $where = $this->_get_members_query_options();

        $members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);

        $members_dropdown = array(array("id" => "", "text" => "- " . app_lang("member") . " -"));
        foreach ($members as $id => $name) {
            $members_dropdown[] = array("id" => $id, "text" => $name);
        }

        return $members_dropdown;
    }

    //get members query options
    private function _get_members_query_options($type = "")
    {
        if ($this->access_type === "all") {
            $where = array("user_type" => "staff");
        } else {
            if (!count($this->allowed_members) && $type != "data") {
                $where = array("user_type" => "nothing"); //don't show any users in dropdown
            } else {
                //add login user in dropdown list
                $allowed_members = $this->allowed_members;
                $allowed_members[] = $this->login_user->id;

                $where = array("user_type" => "staff", "where_in" => ($type == "data") ? $allowed_members : array("id" => $allowed_members));
            }
        }

        return $where;
    }

    //load the custom date view of attendance list 
    function completed()
    {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        //$view_data['account_types_filter_dropdown'] = $this->get_account_types_dropdown_for_filter();
        return $this->template->view("check_in/completed_list", $view_data);
    }

    /* get all attendance of a given duration */

    function completed_list_data()
    {
        $user_id = $this->request->getPost('user_id');

        $options = array(
            'status' => "Completed"
        );
        $options['location_ids'] = get_ltm_opl_id(false, ',');
        if ($user_id) {
            $options['assignee'] = $user_id;
        }

        $options = append_server_side_filtering_commmon_params($options);
        $list_data = $this->Check_in_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            // $result[] = $this->_make_completed_row($data);
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    //load the attendance summary details tab
    function all()
    {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        //$view_data['account_types_filter_dropdown'] = $this->get_account_types_dropdown_for_filter();
        return $this->template->view("check_in/all_list", $view_data);
    }

    /* get data the attendance summary details tab */



    function all_list_data()
    {
        $user_id = $this->request->getPost('user_id');

        $options = array();
        $options['location_ids'] = get_ltm_opl_id(false, ',');
        if ($user_id) {
            $options['assignee'] = $user_id;
        }
        $options = append_server_side_filtering_commmon_params($options);
        $list_data = $this->Check_in_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            // $result[] = $this->_make_all_row($data);
            $result[] = $this->_make_row($data, true);
        }

        echo json_encode(array("data" => $result));
    }

    /* get clocked in members list */

    function archived_list_data()
    {
        $user_id = $this->request->getPost('user_id');

        $options = array(
            'status' => "Archived"
        );
        $options['location_ids'] = get_ltm_opl_id(false, ',');
        if ($user_id) {
            $options['assignee'] = $user_id;
        }
        $options = append_server_side_filtering_commmon_params($options);
        $list_data = $this->Check_in_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            // $result[] = $this->_make_all_row($data);
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //load the clock in / out tab view of attendance list 
    function clock_in_out()
    {
        return $this->template->view("check_in/clock_in_out");
    }

    /* get data the attendance clock In / Out tab */

    function clock_in_out_list_data()
    {
        $options = $this->_get_members_query_options("data");
        $list_data = $this->Attendance_model->get_clock_in_out_details_of_all_users($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_clock_in_out_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    private function _clock_in_out_row_data($user_id)
    {
        $options = array("id" => $user_id);
        $data = $this->Attendance_model->get_clock_in_out_details_of_all_users($options)->getRow();
        return $this->_make_clock_in_out_row($data);
    }

    private function _make_clock_in_out_row($data)
    {
        if (isset($data->attendance_id)) {
            $in_time = format_to_time($data->in_time);
            $in_datetime = format_to_datetime($data->in_time);
            $status = "<div class='mb15' title='$in_datetime'>" . app_lang('clock_started_at') . " : $in_time</div>";
            $view_data = modal_anchor(get_uri("check_in/note_modal_form/$data->id"), "<i data-feather='log-out' class='icon-16'></i> " . app_lang('clock_out'), array("class" => "btn btn-default", "title" => app_lang('clock_out'), "id" => "timecard-clock-out", "data-post-id" => $data->attendance_id, "data-post-clock_out" => 1, "data-post-id" => $data->id));
        } else {
            $status = "<div class='mb15'>" . app_lang('not_clocked_id_yet') . "</div>";
            $view_data = js_anchor("<i data-feather='log-in' class='icon-16'></i> " . app_lang('clock_in'), array('title' => app_lang('clock_in'), "class" => "btn btn-default spinning-btn", "data-action-url" => get_uri("check_in/log_time/$data->id"), "data-action" => "update", "data-inline-loader" => "1", "data-post-id" => $data->id));
        }

        $image_url = get_avatar($data->image);
        $user_avatar = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span>";

        return array(
            get_team_member_profile_link($data->id, $user_avatar . $data->member_name),
            $status,
            $view_data
        );
    }
}

/* End of file attendance.php */
/* Location: ./app/controllers/attendance.php */