<?php

namespace App\Models;

class Project_payment_schedule_setup_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'project_payment_schedule_setup';
        parent::__construct($this->table);
        // parent::init_activity_log("milestone", "title", "project", "project_id", "", 0, 'milestone', 'id');
    }

    function get_details($options = array())
    {
        $project_payment_schedule_setup_table = $this->db->prefixTable('project_payment_schedule_setup');
        $where = "";

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $project_payment_schedule_setup_table.id=$id";
        }

        $project_id = $this->_get_clean_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $project_payment_schedule_setup_table.project_id=$project_id";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $project_payment_schedule_setup_table.client_id=$client_id";
        }

        $is_auto_created = $this->_get_clean_value($options, "is_auto_created");
        if ($is_auto_created) {
            $where .= " AND $project_payment_schedule_setup_table.is_auto_created=$is_auto_created";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $project_payment_schedule_setup_table.status=$status";
        }

        $limit_offset = "";
        $limit = $this->_get_clean_value($options, "limit");
        if ($limit) {
            $skip = $this->_get_clean_value($options, "skip");
            $offset = $skip ? $skip : 0;
            $limit_offset = " LIMIT $limit OFFSET $offset ";
        }

        $available_order_by_list = array(
            "id" => $project_payment_schedule_setup_table . ".id",
            "invoice_date" => "DATE(" . $project_payment_schedule_setup_table . ".invoice_date)",
            "installment_name" => $project_payment_schedule_setup_table . ".installment_name",
            "sort" => $project_payment_schedule_setup_table . ".sort",
            "status" => $project_payment_schedule_setup_table . ".status",
        );

        $order_by = get_array_value($available_order_by_list, $this->_get_clean_value($options, "order_by"));

        $order = "";

        if ($order_by) {
            $order_dir = $this->_get_clean_value($options, "order_dir");
            $order = " ORDER BY $order_by $order_dir ";
        }

        $search_by = get_array_value($options, "search_by");
        if ($search_by) {
            $search_by = $this->db->escapeLikeString($search_by);

            $where .= " AND (";
            $where .= " $project_payment_schedule_setup_table.id LIKE '%$search_by%' ESCAPE '!' ";
            $where .= " OR $project_payment_schedule_setup_table.invoice_date LIKE '%$search_by%' ESCAPE '!' ";
            $where .= " OR $project_payment_schedule_setup_table.installment_name LIKE '%$search_by%' ESCAPE '!' ";
            $where .= " )";
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS $project_payment_schedule_setup_table.*
        FROM $project_payment_schedule_setup_table WHERE $project_payment_schedule_setup_table.deleted=0 $where
        $order $limit_offset";

        $raw_query = $this->db->query($sql);

        $total_rows = $this->db->query("SELECT FOUND_ROWS() as found_rows")->getRow();

        if ($limit) {
            return array(
                "data" => $raw_query->getResult(),
                "recordsTotal" => $total_rows->found_rows,
                "recordsFiltered" => $total_rows->found_rows,
            );
        } else {
            return $raw_query;
        }
    }
}
