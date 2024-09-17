<?php

namespace App\Models;

class Office_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'offices';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $offices_table = $this->db->prefixTable('offices');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $offices_table.id=$id";
        }
        $user_id = $this->_get_clean_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $offices_table.user_id=$user_id";
        }
        $location_id = $this->_get_clean_value($options, "location_id");
        if ($location_id) {
            $where .= " AND $offices_table.location_id=$location_id";
        }
        $is_primary = $this->_get_clean_value($options, "is_primary");
        if ($is_primary) {
            $where .= " AND $offices_table.is_primary=$is_primary";
        }

        $sql = "SELECT $offices_table.*
        FROM $offices_table
        WHERE $offices_table.deleted=0 $where
        ORDER BY $offices_table.id ASC";
        return $this->db->query($sql);
    }

    function own_office_only_location_ids($user_id = 0)
    {
        $offices_table = $this->db->prefixTable('offices');

        $sql = "SELECT $offices_table.location_id
        FROM $offices_table
        WHERE $offices_table.deleted=0 AND $offices_table.user_id=$user_id
        ORDER BY $offices_table.id ASC";
        $result = $this->db->query($sql)->getResult();

        return array_map(function ($v) {
            return $v->location_id;
        }, $result);
    }
}
