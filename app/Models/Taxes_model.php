<?php

namespace App\Models;

class Taxes_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'taxes';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $taxes_table = $this->db->prefixTable('taxes');
        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $taxes_table.id=$id";
        }
        $is_default = $this->_get_clean_value($options, "is_default");
        if ($is_default) {
            $where .= " AND $taxes_table.is_default=1";
        }

        $sql = "SELECT $taxes_table.*
        FROM $taxes_table
        WHERE $taxes_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
