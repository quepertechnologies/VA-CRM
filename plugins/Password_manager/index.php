<?php

//Prevent direct access
defined('PLUGINPATH') or exit('No direct script access allowed');

/*
  Plugin Name: Password Manager
  Description: Password manager for RISE CRM.
  Version: 1.2
  Requires at least: 2.8
  Author: SketchCode
  Author URL: https://codecanyon.net/user/sketchcode
 */

use App\Controllers\Security_Controller;

app_hooks()->add_filter('app_filter_staff_left_menu', function ($sidebar_menu) {
    $team_submenu = array();
    $team_submenu["password_manager"] = array("name" => "password_manager_passwords", "url" => "password_manager", "class" => "key");
    $team_submenu["categories"] = array("name" => "categories", "url" => "password_manager/categories", "class" => "menu");

    $sidebar_menu["password_manager"] = array(
        "name" => "password_manager",
        "url" => "password_manager",
        "class" => "key",
        "position" => 6,
        "submenu" => $team_submenu
    );

    return $sidebar_menu;
});

app_hooks()->add_filter('app_filter_client_left_menu', function ($sidebar_menu) {
    $sidebar_menu["password_manager"] = array(
        "name" => "password_manager",
        "url" => "password_manager",
        "class" => "key",
        "position" => 5
    );

    return $sidebar_menu;
});

app_hooks()->add_filter('app_filter_action_links_of_Password_manager', function ($action_links_array) {
    $action_links_array = array(
        anchor(get_uri("password_manager_settings"), app_lang("settings")),
        anchor(get_uri("password_manager"), app_lang("password_manager"))
    );

    return $action_links_array;
});

app_hooks()->add_filter('app_filter_admin_settings_menu', function ($settings_menu) {
    $settings_menu["plugins"][] = array("name" => "password_manager", "url" => "password_manager_settings");
    return $settings_menu;
});

//hook to add ajax tab in client details view
app_hooks()->add_filter('app_filter_client_details_ajax_tab', 'password_manager_details_view_ajax_tab');

if (!function_exists('password_manager_details_view_ajax_tab')) {

    function password_manager_details_view_ajax_tab($hook_tabs, $client_id = 0) {
        if (!$client_id) {
            return $hook_tabs;
        }

        $hook_tabs[] = array(
            "title" => app_lang('password_manager_passwords'),
            "url" => get_uri("password_manager/client_passwords/$client_id"),
            "target" => "tab-password_manager_client_view"
        );

        return $hook_tabs;
    }

}

//installation: install dependencies
register_installation_hook("Password_manager", function ($item_purchase_code) {
    include PLUGINPATH . "Password_manager/install/do_install.php";
});

//uninstallation: remove data from database
register_uninstallation_hook("Password_manager", function () {
    $dbprefix = get_db_prefix();
    $db = db_connect('default');

    $sql_query = "DROP TABLE `" . $dbprefix . "password_manager_categories`;";
    $db->query($sql_query);

    $sql_query = "DROP TABLE `" . $dbprefix . "password_manager_general`;";
    $db->query($sql_query);

    $sql_query = "DROP TABLE `" . $dbprefix . "password_manager_email`;";
    $db->query($sql_query);

    $sql_query = "DROP TABLE `" . $dbprefix . "password_manager_credit_card`;";
    $db->query($sql_query);

    $sql_query = "DROP TABLE `" . $dbprefix . "password_manager_bank_account`;";
    $db->query($sql_query);

    $sql_query = "DROP TABLE `" . $dbprefix . "password_manager_software_license`;";
    $db->query($sql_query);
});

//update plugin
use Password_manager\Controllers\Password_manager_Updates;

register_update_hook("Password_manager", function () {
    $update = new Password_manager_Updates();
    return $update->index();
});
