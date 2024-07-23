<?php

namespace App\Models;

class Timeline_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'timeline';
        parent::__construct($this->table);
    }

    function delete_where($where = array())
    {
        if (count($where)) {
            return $this->db_builder->delete($where);
        }
    }

    function get_details($options = array())
    {
        $timeline_table = $this->db->prefixTable('timeline');

        $query = '';

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $query .= " WHERE $timeline_table.client_id='$client_id'";
        }

        $sql = "SELECT * FROM $timeline_table" . ' ' . $query;

        $raw_query = $this->db->query($sql);

        // $total_rows = $this->db->query("SELECT FOUND_ROWS() as found_rows")->getRow();

        return $raw_query;
    }
}
