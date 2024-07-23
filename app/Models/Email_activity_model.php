<?php

namespace App\Models;

class Email_activity_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'email_activity_logs';
        parent::__construct($this->table);
        parent::init_activity_log("email", "subject", "project", "project_id", "", 0, 'milestone', 'milestone_id');
    }

    function get_details($options = array())
    {
        $email_activity_logs_table = $this->db->prefixTable('email_activity_logs');
        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $email_activity_logs_table.id=$id";
        }
        $project_id = $this->_get_clean_value($options, "project_id");
        if ($project_id) {
            $where = " AND $email_activity_logs_table.project_id=$project_id";
        }
        $milestone_id = $this->_get_clean_value($options, "milestone_id");
        if ($milestone_id) {
            $where = " AND $email_activity_logs_table.milestone_id=$milestone_id";
        }
        $created_by = $this->_get_clean_value($options, "created_by");
        if ($created_by) {
            $where = " AND $email_activity_logs_table.created_by=$created_by";
        }

        $sql = "SELECT $email_activity_logs_table.*
        FROM $email_activity_logs_table
        WHERE $email_activity_logs_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
