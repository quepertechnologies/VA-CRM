<?php

namespace App\Models;

class Payment_methods_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'payment_methods';
        parent::__construct($this->table);
    }

    //define different types of payment gateway settings
    function get_settings($type = "")
    {
        $settings = array(
            "stripe" => array(
                array("name" => "pay_button_text", "text" => app_lang("pay_button_text"), "type" => "text", "default" => "Stripe"),
                array("name" => "secret_key", "text" => "Secret Key", "type" => "text", "default" => ""),
                array("name" => "publishable_key", "text" => "Publishable Key", "type" => "text", "default" => ""),
            ),
            "paypal_payments_standard" => array(
                array("name" => "pay_button_text", "text" => app_lang("pay_button_text"), "type" => "text", "default" => "PayPal Standard"),
                array("name" => "client_id", "text" => app_lang("google_client_id"), "type" => "text", "default" => ""),
                array("name" => "client_secret", "text" => app_lang("google_client_secret"), "type" => "text", "default" => ""),
                array("name" => "paypal_live", "text" => "Paypal Live", "type" => "boolean", "default" => "0")
            ),
            "paytm" => array(
                array("name" => "pay_button_text", "text" => app_lang("pay_button_text"), "type" => "text", "default" => "Paytm"),
                array("name" => "paytm_testing_environment", "text" => app_lang("testing_environment"), "type" => "boolean", "default" => "0"),
                array("name" => "merchant_id", "text" => "Merchant ID", "type" => "text", "default" => ""),
                array("name" => "secret_key", "text" => "Secret Key", "type" => "text", "default" => ""),
                array("name" => "merchant_website", "text" => "Merchant Website", "type" => "text", "default" => ""),
                array("name" => "industry_type", "text" => "Industry Type", "type" => "text", "default" => ""),
            ),
        );

        $settings = app_hooks()->apply_filters('app_filter_payment_method_settings', $settings);

        if ($type && get_array_value($settings, $type)) {
            return get_array_value($settings, $type);
        } else {
            return array();
        }
    }

    function get_one_with_settings($id = 0)
    {
        $info = $this->get_one($id);
        return $this->_merge_online_settings_with_default($info);
    }

    function get_oneline_payment_method($type)
    {
        $info = $this->get_one_where(array("deleted" => 0, "type" => $type, "online_payable" => 1));
        return $this->_merge_online_settings_with_default($info);
    }

    private function _merge_online_settings_with_default($info)
    {
        $settings = $this->get_settings($info->type);
        $settings_data = $info->settings ? @unserialize($info->settings) : array();

        if (!is_array($settings_data)) {
            $settings_data = array();
        }

        if (is_array($settings)) {
            foreach ($settings as $setting) {
                $setting_name = is_array($setting) ? get_array_value($setting, "name") : "";
                $info->$setting_name = get_array_value($settings_data, $setting_name);
                if (!$info->$setting_name) {
                    $info->$setting_name = get_array_value($setting, "default");
                }
            }
        }

        return $info;
    }

    function get_details($options = array())
    {
        $payment_methods_table = $this->db->prefixTable('payment_methods');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $payment_methods_table.id=$id";
        }

        $title = $this->_get_clean_value($options, "title");
        if ($title) {
            $where = " AND $payment_methods_table.title='$title'";
        }

        $online_payable = $this->_get_clean_value($options, "online_payable");
        if ($online_payable) {
            $where = " AND $payment_methods_table.online_payable=$online_payable";
        }

        $available_on_invoice = $this->_get_clean_value($options, "available_on_invoice");
        if ($available_on_invoice) {
            $where = " AND $payment_methods_table.available_on_invoice=$available_on_invoice";
        }

        $sql = "SELECT $payment_methods_table.*
        FROM $payment_methods_table
        WHERE $payment_methods_table.deleted=0 $where
        ORDER BY $payment_methods_table.sort ASC";
        return $this->db->query($sql);
    }

    function delete($id = 0, $undo = false)
    {

        $exists = $this->get_one_where($where = array("id" => $id));
        if ($exists->online_payable == 1) {
            //online payable types can't be deleted
            return false;
        } else {
            return parent::delete($id, $undo);
        }
    }

    function get_available_online_payment_methods()
    {

        $settings = $this->get_details(array("online_payable" => 1, "available_on_invoice" => 1))->getResult();

        $final_settings = array();
        foreach ($settings as $setting) {
            $final_settings[] = (array) $this->_merge_online_settings_with_default($setting);
        }
        return $final_settings;
    }
}
