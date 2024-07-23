<?php

namespace App\Models;

class Account_types_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'account_types';
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
        $account_types = $this->db->prefixTable('account_types');

        $query = '';

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $query .= " WHERE $account_types.id='$id'";
        }

        $title = $this->_get_clean_value($options, "title");
        if ($title) {
            $query .= " WHERE $account_types.title='$title'";
        }

        $sql = "SELECT * FROM $account_types" . ' ' . $query;

        $raw_query = $this->db->query($sql);

        // $total_rows = $this->db->query("SELECT FOUND_ROWS() as found_rows")->getRow();

        return $raw_query;
    }
}
