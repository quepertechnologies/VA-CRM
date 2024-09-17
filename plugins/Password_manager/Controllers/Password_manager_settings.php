<?php

namespace Password_manager\Controllers;

class Password_manager_settings extends \App\Controllers\Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
    }

    /* load password manager setting view */

    function index() {
        $password_manager_tabs = array(
            "general",
            "email",
            "password_manager_credit_card",
            "password_manager_bank_account",
            "password_manager_software_license"
        );

        $password_manager_tabs_dropdown = array();
        foreach ($password_manager_tabs as $password_manager_tab) {
            $password_manager_tabs_dropdown[] = array("id" => $password_manager_tab, "text" => app_lang($password_manager_tab));
        }

        $view_data['password_manager_tabs_dropdown'] = json_encode($password_manager_tabs_dropdown);

        return $this->template->rander("Password_manager\Views\settings\index", $view_data);
    }

    /* save or update an password manager settings */

    function save() {
        $settings = array(
            "password_manager_tab_order",
        );

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting($setting, $value);
        }
        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

}

/* End of file Password_manager_settings.php */
/* Location: ./plugins/Password_manager/controllers/Password_manager_settings.php */