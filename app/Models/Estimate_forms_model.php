<?php

namespace App\Models;

class Estimate_forms_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'estimate_forms';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $estimate_forms_table = $this->db->prefixTable('estimate_forms');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $estimate_forms_table.id=$id";
        }

        $location_ids = $this->_get_clean_value($options, "location_ids");
        if ($location_ids) {
            $where .= " AND $estimate_forms_table.location_id IN ($location_ids)";
        }


        $sql = "SELECT $estimate_forms_table.*
        FROM $estimate_forms_table
        WHERE $estimate_forms_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
