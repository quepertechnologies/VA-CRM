<?php

namespace App\Models;

class Estimate_requests_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'estimate_requests';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $estimate_requests_table = $this->db->prefixTable('estimate_requests');
        $estimate_forms_table = $this->db->prefixTable('estimate_forms');
        $clients_table = $this->db->prefixTable('clients');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $estimate_requests_table.id=$id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $estimate_requests_table.client_id=$client_id";
        }

        $lead_id = $this->_get_clean_value($options, "lead_id");
        if ($lead_id) {
            $where .= " AND $estimate_requests_table.lead_id=$lead_id";
        }

        $assigned_to = $this->_get_clean_value($options, "assigned_to");
        if ($assigned_to) {
            $where .= " AND $estimate_requests_table.assigned_to=$assigned_to";
        }

        $is_archived = $this->_get_clean_value($options, "is_archived");
        if ($is_archived) {
            $where .= " AND $estimate_requests_table.is_archived='$is_archived'";
        } elseif ($is_archived == '0') {
            $where .= " AND $estimate_requests_table.is_archived='0'";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $estimate_requests_table.status='$status'";
        }

        $clients_only = $this->_get_clean_value($options, "clients_only");
        if ($clients_only) {
            $where .= " AND $estimate_requests_table.client_id IN(SELECT $clients_table.id FROM $clients_table WHERE $clients_table.deleted=0 AND $clients_table.is_lead=0)";
        }

        $sql = "SELECT $estimate_requests_table.*, $clients_table.company_name, $clients_table.first_name, $clients_table.last_name, $estimate_forms_table.title AS form_title, $clients_table.is_lead,
              CONCAT($users_table.first_name, ' ',$users_table.last_name) AS assigned_to_user, $users_table.image as assigned_to_avatar, $clients_table.is_lead 
        FROM $estimate_requests_table
        LEFT JOIN $clients_table ON $clients_table.id = $estimate_requests_table.client_id
        LEFT JOIN $users_table ON $users_table.id = $estimate_requests_table.assigned_to
        LEFT JOIN $estimate_forms_table ON $estimate_forms_table.id = $estimate_requests_table.estimate_form_id
        WHERE $estimate_requests_table.deleted=0 $where";

        return $this->db->query($sql);
    }
}
