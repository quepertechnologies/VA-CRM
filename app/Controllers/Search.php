<?php

namespace App\Controllers;

class Search extends Security_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->access_only_team_members();
    }

    public function index() {}

    function search_modal_form()
    {
        $search_fields = array(
            "task",
            "project"
        );

        if ($this->can_access_clients()) {
            $search_fields[] = "client";
        }

        if (get_setting("module_todo")) {
            $search_fields[] = "todo";
        }

        $search_fields_dropdown = array();
        foreach ($search_fields as $search_field) {
            $search_fields_dropdown[] = array("id" => $search_field, "text" => app_lang($search_field));
        }

        $view_data['search_fields_dropdown'] = json_encode($search_fields_dropdown);

        return $this->template->view("search/modal_form", $view_data);
    }

    function get_search_suggestion()
    {
        $search = $this->request->getPost("search");
        $search_field = $this->request->getPost("search_field");
        $is_mobile = $this->request->getPost("is_mobile");

        if (strlen($search) < 3) {
            echo json_encode(array());
            exit();
        }

        if ($search && $search_field) {
            $options = array();
            $result = array();

            if ($search_field == "task") { //task
                $options["show_assigned_tasks_only_user_id"] = $this->show_assigned_tasks_only_user_id();
                $result = $this->Tasks_model->get_search_suggestion($search, $options)->getResult();
            } else if ($search_field == "project") { //project
                if (!$this->can_manage_all_projects()) {
                    $options["user_id"] = $this->login_user->id;
                }
                $result = $this->Projects_model->get_search_suggestion($search, $options)->getResult();
            } else if ($search_field == "client") { //client
                if (!$this->can_access_clients()) {
                    app_redirect("forbidden");
                }
                //$options["show_own_clients_only_user_id"] = $this->show_own_clients_only_user_id();

                $this->init_permission_checker("client");
                //$options["client_groups"] = $this->allowed_client_groups;

                $options['location_ids'] = get_ltm_opl_id(false, ',');

                $result = $this->Clients_model->get_search_suggestion($search, $options)->getResult();
            } else if ($search_field == "todo" && get_setting("module_todo")) { //todo
                $result = $this->Todo_model->get_search_suggestion($search, $this->login_user->id)->getResult();
            }

            $result_array = array();
            foreach ($result as $value) {
                if ($search_field == "task") {
                    $result_array[] = array("value" => $value->id, "label" => app_lang("task") . " $value->id: " . $value->title);
                } else {
                    if ($search_field == 'client') {
                        $full_name = $this->get_client_full_name(0, $value);
                        $label = timeline_label($this->_get_client_type_label($value->account_type == '3' ? $value->partner_type : $value->account_type), 'font-size: 11px; position:absolute; right:10px; top: 5px;');
                        // $location_label = $this->get_location_label($value->location_id);
                        $is_lost = '';
                        if ($value->is_lead) {
                            if ($value->lead_status_id == 1) {
                                $is_lost = timeline_label('lead', 'font-size: 11px;') . ' ';
                            }
                            if ($value->lead_status_id == 2) {
                                $is_lost = timeline_label('prospect', 'font-size: 11px;') . ' ';
                            }
                            if ($value->lead_status_id == 3) {
                                $is_lost = timeline_label('cold_lead', 'font-size: 11px;') . ' ';
                            }
                        }
                        if ($value->deleted) {
                            $is_lost = timeline_label('lost', 'font-size: 11px;') . ' ';
                        }
                        $result_array[] = array("value" => $value->id, "label" => $is_lost . '<span style="' . ($is_mobile || strlen($full_name) > 30 ? 'width: 40%;' : '') . '"><b>' . $full_name . '</b></span><small> #' . $value->id . '</small>' . (strlen($full_name) > 40 || $is_mobile ? ' <br/> ' : " | ") . '<small>' . ($is_mobile && strlen($value->unique_id) > 40 ? substr($value->unique_id, 0, 37) . '...' : $value->unique_id) . '</small>' . $label . '<br/><small>' . $value->email . ($is_mobile ? '<br/>' : ' | ') . $value->phone_code . $value->phone . '</small><small style="position:absolute; right:10px;bottom:5px">' . $value->location_label . '</small>');
                    } else {
                        $result_array[] = array("value" => $value->id, "label" => $value->title);
                    }
                }
            }

            echo json_encode($result_array);
        }
    }

    private function _get_client_type_label($type = '')
    {
        $val = "";
        switch ($type) {
            case '1':
                $val = 'student';
                break;
            case '2':
                $val = 'migration_client';
                break;
            case '4':
                $val = 'organization';
                break;
            case 'institute':
                $val = 'institute';
                break;
            case 'subagent':
                $val = 'subagent';
                break;
            case 'superagent':
                $val = 'superagent';
                break;
            case 'referral':
                $val = 'referral';
                break;
        }
        return $val;
    }
}

/* End of file Search.php */
/* Location: ./app/controllers/Search.php */