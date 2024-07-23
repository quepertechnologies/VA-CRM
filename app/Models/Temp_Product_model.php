<?php

namespace App\Models;

class Temp_Product_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'temp_products';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $temp_products_table = $this->db->prefixTable('temp_products');
        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $temp_products_table.id=$id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $temp_products_table.client_id=$client_id";
        }

        $user_id = $this->_get_clean_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $temp_products_table.user_id = $user_id";
        }

        $doc_check_list_item_id = $this->_get_clean_value($options, "doc_check_list_item_id");
        if ($doc_check_list_item_id) {
            $where .= " AND $temp_products_table.doc_check_list_item_id='$doc_check_list_item_id'";
        }

        $file_type = $this->_get_clean_value($options, "file_type");
        if ($file_type) {
            $where .= " AND $temp_products_table.file_type = '$file_type'";
        }

        $sql = "SELECT $temp_products_table.*
        FROM $temp_products_table
        WHERE $temp_products_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_title_suggestion($keyword = "")
    {
        $products_table = $this->db->prefixTable('temp_products');

        if ($keyword) {
            $keyword = $this->db->escapeLikeString($keyword);
        }

        $where = "";

        $sql = "SELECT $products_table.id, $products_table.product_name
        FROM $products_table
        WHERE $products_table.deleted=0  AND $products_table.product_name LIKE '%$keyword%' ESCAPE '!' $where
        LIMIT 10
        ";
        return $this->db->query($sql)->getResult();
    }
}
