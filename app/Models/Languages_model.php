<?php

namespace App\Models;

class Languages_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'languages';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $country_table = $this->db->prefixTable('languages');

        $query = '';

        if (array_key_exists('orderBy', $options) && array_key_exists('orderDir', $options)) {
            $query .= 'ORDER BY ' . $options['orderBy'] . ' ' . $options['orderDir'];
        }

        $sql = "SELECT * FROM $country_table" . ' ' . $query;

        $raw_query = $this->db->query($sql);

        // $total_rows = $this->db->query("SELECT FOUND_ROWS() as found_rows")->getRow();

        return $raw_query;
    }
}
