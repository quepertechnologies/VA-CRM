<?php

namespace App\Models;

class Visa_model extends Crud_model
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
        $visa_table = $this->db->prefixTable('visa_types');

        $query = '';

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $query .= " WHERE $visa_table.id='$id'";
        }

        $title = $this->_get_clean_value($options, "title");
        if ($title) {
            $query .= " WHERE $visa_table.title='$title'";
        }

        $available_order_by_list = array(
            "id" => $visa_table . ".id",
            "title" => $visa_table . ".title",
        );

        $order_by = get_array_value($available_order_by_list, $this->_get_clean_value($options, "order_by"));

        $order = "";

        if ($order_by) {
            $order_dir = $this->_get_clean_value($options, "order_dir");
            $order = " ORDER BY $order_by $order_dir ";
        }

        $sql = "SELECT * FROM $visa_table" . ' ' . $query . ' ' . $order;

        $raw_query = $this->db->query($sql);

        // $total_rows = $this->db->query("SELECT FOUND_ROWS() as found_rows")->getRow();

        return $raw_query;
    }
}
