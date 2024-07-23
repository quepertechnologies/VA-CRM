<?php

namespace App\Models;

class Project_doc_check_list_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'project_doc_check_list';
        parent::__construct($this->table);
        // parent::init_activity_log("milestone", "title", "project", "project_id", "", 0, 'milestone', 'id');
    }

    function get_details($options = array())
    {
        $project_doc_check_list_table = $this->db->prefixTable('project_doc_check_list');
        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $project_doc_check_list_table.id=$id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $project_doc_check_list_table.client_id=$client_id";
        }

        $project_id = $this->_get_clean_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $project_doc_check_list_table.project_id=$project_id";
        }

        $milestone_id = $this->_get_clean_value($options, "milestone_id");
        if ($milestone_id) {
            $where .= " AND $project_doc_check_list_table.milestone_id=$milestone_id";
        }

        $file_id = $this->_get_clean_value($options, "file_id");
        if ($file_id) {
            $where .= " AND $project_doc_check_list_table.file_id=$file_id";
        }

        $is_required = $this->_get_clean_value($options, "is_required");
        if ($is_required) {
            $where .= " AND $project_doc_check_list_table.is_required=$is_required";
        }

        $order_by = "ORDER BY $project_doc_check_list_table.id DESC";

        $sql = "SELECT $project_doc_check_list_table.*
        FROM $project_doc_check_list_table WHERE $project_doc_check_list_table.deleted=0 $where
        $order_by";
        return $this->db->query($sql);
    }
}
