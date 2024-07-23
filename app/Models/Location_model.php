<?php

namespace App\Models;

class Location_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'location';
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
        $location_table = $this->db->prefixTable('location');

        $query = '';

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $query .= " WHERE $location_table.id='$id'";
        }

        $title = $this->_get_clean_value($options, "title");
        if ($title) {
            $query .= " WHERE $location_table.title='$title'";
        }

        $sql = "SELECT * FROM $location_table" . ' ' . $query;

        $raw_query = $this->db->query($sql);

        // $total_rows = $this->db->query("SELECT FOUND_ROWS() as found_rows")->getRow();

        return $raw_query;
    }
}
