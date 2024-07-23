<?php

namespace App\Models;

class Project_payment_schedule_setup_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'project_payment_schedule_setup';
        parent::__construct($this->table);
        // parent::init_activity_log("milestone", "title", "project", "project_id", "", 0, 'milestone', 'id');
    }

    function get_details($options = array())
    {
        $project_payment_schedule_setup_table = $this->db->prefixTable('project_payment_schedule_setup');
        $where = "";

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $project_payment_schedule_setup_table.id=$id";
        }

        $project_id = $this->_get_clean_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $project_payment_schedule_setup_table.project_id=$project_id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $project_payment_schedule_setup_table.client_id=$client_id";
        }

        $is_auto_created = $this->_get_clean_value($options, "is_auto_created");
        if ($is_auto_created) {
            $where .= " AND $project_payment_schedule_setup_table.is_auto_created=$is_auto_created";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $project_payment_schedule_setup_table.status=$status";
        }

        $order_by = " ORDER BY $project_payment_schedule_setup_table.created_date ASC";

        $sql = "SELECT $project_payment_schedule_setup_table.*
        FROM $project_payment_schedule_setup_table WHERE $project_payment_schedule_setup_table.deleted=0 $where
        $order_by";
        return $this->db->query($sql);
    }
}
