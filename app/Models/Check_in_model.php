<?php

namespace App\Models;

class Check_in_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'check_in';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $check_in_table = $this->db->prefixTable('check_in');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $check_in_table.id=$id";
        }
        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $check_in_table.client_id=$client_id";
        }
        $created_by = $this->_get_clean_value($options, "created_by");
        if ($created_by) {
            $where .= " AND $check_in_table.created_by=$created_by";
        }
        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            if ($status === 'Waiting') {
                $where .= " AND $check_in_table.status='Waiting'";
            } elseif ($status === 'Attending') {
                $where .= " AND $check_in_table.status='Attending'";
            } elseif ($status === 'Completed') {
                $where .= " AND $check_in_table.status='Completed'";
            } elseif ($status === 'Archived') {
                $where .= " AND $check_in_table.status='Archived'";
            }
        }
        $location_id = $this->_get_clean_value($options, "location_id");
        if ($location_id) {
            $where .= " AND $check_in_table.location_id='$location_id'";
        }
        $location_ids = $this->_get_clean_value($options, "location_ids");
        if ($location_ids) {
            $where .= " AND $check_in_table.location_id IN ($location_ids)";
        }
        $assignee = $this->_get_clean_value($options, "assignee");
        if ($assignee) {
            $where .= " AND $check_in_table.assignee=$assignee";
        }

        $sql = "SELECT $check_in_table.*
        FROM $check_in_table
        WHERE $check_in_table.deleted=0 $where
        ORDER BY $check_in_table.id ASC";
        return $this->db->query($sql);
    }
}
