<?php

namespace App\Models;

class Project_fees_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'project_fees';
        parent::__construct($this->table);
        // parent::init_activity_log("milestone", "title", "project", "project_id", "", 0, 'milestone', 'id');
    }

    function get_details($options = array())
    {
        $project_fees_table = $this->db->prefixTable('project_fees');
        $where = "";

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $project_fees_table.id=$id";
        }

        $project_id = $this->_get_clean_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $project_fees_table.project_id=$project_id";
        }

        $order_by = "ORDER BY $project_fees_table.id DESC";

        $sql = "SELECT $project_fees_table.*
        FROM $project_fees_table WHERE $project_fees_table.deleted=0 $where
        $order_by";
        return $this->db->query($sql);
    }
}
