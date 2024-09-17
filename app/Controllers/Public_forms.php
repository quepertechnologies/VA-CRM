<?php

namespace App\Controllers;

class Public_forms extends App_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        show_404();
    }

    function check_in($location_id = 2)
    {
        $view_data['topbar'] = "includes/public/topbar";

        $view_data['left_menu'] = false;

        $view_data['embedded'] = false;

        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $view_data["countries_dropdown"] = $this->get_countries_dropdown([]);
        $view_data["visa_type_dropdown"] = $this->get_visa_type_dropdown();
        $location_label = $this->get_location_label($location_id);
        $location_label = ucwords(str_replace(array("(", ")"), '', strtolower($location_label)));

        $view_data["assignee"] = 1;
        $view_data["location_id"] = $location_id;
        $view_data["form_description"] = "Please complete this form to confirm your arrival at the <b>Visa Alliance " . ($location_label == '-' ? 'Office' : (str_contains($location_label, 'Office') ? $location_label : $location_label . ' Office')) . "</b>.";

        $view_data['label_column'] = "col-md-3";
        $view_data['field_column'] = "col-md-9";

        return $this->template->rander('check_in/public_form', $view_data);
    }

    function save_public_check_in()
    {
        $location_id = $this->request->getPost('location_id') ? $this->request->getPost('location_id') : 0;

        $this->validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required",
        ));

        $data = array(
            'location_id' => $location_id,
            "student_onshore" => $this->request->getPost('student_onshore'),
            "first_name" => $this->request->getPost('first_name'),
            "last_name" => $this->request->getPost('last_name'),
            "email" => $this->request->getPost('email'),
            "visa_type" => $this->request->getPost('visa_type'),
            "visa_2" => $this->request->getPost('visa_2'),
            "visa_expiry" => $this->request->getPost('visa_expiry'),
            "note" => $this->request->getPost('note'),
            "status" => $this->request->getPost('status'),
            "assignee" => $this->request->getPost('assignee') ? $this->request->getPost('assignee') : 1,
            'created_date' => get_current_utc_time(),
            'created_by' => $this->request->getPost('assignee') ? $this->request->getPost('assignee') : 1
        );

        $save_id = $this->Check_in_model->ci_save($data);

        if ($save_id) {
            $clientID = $this->Check_in_model->get_one($save_id);
            $client_id = 0;
            if ($clientID && $clientID->client_id) {
                $client_id = $clientID->client_id;
            } elseif (!$this->Users_model->is_email_exists($this->request->getPost('email'))) {
                $client_data = array(
                    "first_name" => $this->request->getPost('first_name'),
                    "last_name" => $this->request->getPost('last_name'),
                    'location_id' => $location_id,
                    'created_by_location_id' => $location_id,
                    "unique_id" => _gen_va_uid($this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name')),
                    "type" => 'person',
                    "account_type" => $this->request->getPost('account_type'),
                    "email" => $this->request->getPost('email'),
                    "phone_code" => $this->request->getPost('phone_code'),
                    "phone" => $this->request->getPost('phone'),
                    "created_date" => get_current_utc_time(),
                    "currency_symbol" => '',
                    "currency" => '',
                    "disable_online_payment" => 0,
                    "created_by" => 0,
                    'is_lead' => 1,
                    'current_lead_status' => 'check_in',
                    'source' => client_source('Office Check-In (Public Form)')
                );
                $client_data = clean_data($client_data);

                $client_id = $this->Clients_model->ci_save($client_data);

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

            if ($client_id) {
                $check_in_data = array('client_id' => $client_id);
                $this->Check_in_model->ci_save($check_in_data, $save_id);
            }

            echo json_encode(array("success" => true, 'id' => $save_id, 'isUpdate' => false, 'message' => "Form submitted successfully"));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    private function save_primary_contact($client_id = 0, $location_id = 0)
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

                save_custom_fields("client_contacts", $save_id, false, 'client');

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

    private function get_countries_dropdown($options)
    {
        $countries = $this->Countries_model->get_details($options)->getResult();

        return json_encode($countries);
    }

    private function get_visa_type_dropdown($first_label = 'Visa Types')
    {
        $dropdown_list = array(array("id" => "", "text" => "- " . $first_label . " -"));
        $options = array();
        $list = $this->Visa_model->get_details($options)->getResult();
        foreach ($list as $item) {
            $dropdown_list[] = array('id' => $item->title, 'text' => 'Subclass ' . $item->title);;
        }
        return json_encode($dropdown_list);
    }


    private function get_location_label($location_id = 0)
    {
        $label = "-";
        $location = $this->Location_model->get_one($location_id);
        if ($location) {
            $label = $location->title;
        }

        return $label;
    }
}

/* End of file Public.php */
/* Location: ./app/controllers/Public.php */