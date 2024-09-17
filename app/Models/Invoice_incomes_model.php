<?php

namespace App\Models;

class Invoice_incomes_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'invoice_incomes';
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
        $invoice_incomes_table = $this->db->prefixTable('invoice_incomes');
        $invoices_table = $this->db->prefixTable('invoices');
        $clients_table = $this->db->prefixTable('clients');
        $projects_table = $this->db->prefixTable('projects');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $invoice_incomes_table.id=$id";
        }
        $invoice_id = $this->_get_clean_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $invoice_incomes_table.invoice_id=$invoice_id";
        }
        $partner_id = $this->_get_clean_value($options, "partner_id");
        if ($partner_id) {
            $where .= " AND $invoice_incomes_table.partner_id=$partner_id";
        }
        $paid_by = $this->_get_clean_value($options, "paid_by");
        if ($paid_by) {
            $where .= " AND $invoice_incomes_table.paid_by=$paid_by";
        }
        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $invoice_incomes_table.status='$status'";
        }
        $only_status = $this->_get_clean_value($options, "only_status");
        if ($only_status) {
            $where .= " AND (FIND_IN_SET($invoice_incomes_table.status, '$only_status'))";
        }
        $location_ids = $this->_get_clean_value($options, "location_ids");
        if ($location_ids) {
            $where .= " AND (FIND_IN_SET($clients_table.location_id, '$location_ids'))";
        }
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($invoice_incomes_table.payment_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT $invoice_incomes_table.*,
        partners_table.currency_symbol, CONCAT(partners_table.first_name, ' ', partners_table.last_name) AS partner_full_name, partners_table.partner_type,
        $projects_table.id AS project_id, $projects_table.title AS project_title,
        $clients_table.id AS client_id, CONCAT($clients_table.first_name, ' ', $clients_table.last_name) AS client_full_name
        FROM $invoice_incomes_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_incomes_table.invoice_id
        LEFT JOIN $clients_table AS partners_table ON partners_table.id=$invoice_incomes_table.partner_id
        LEFT JOIN $projects_table ON $projects_table.id=$invoices_table.project_id
        LEFT JOIN $clients_table ON $clients_table.id=$projects_table.client_id
        WHERE $invoice_incomes_table.deleted=0 $where
        ORDER BY $invoice_incomes_table.id DESC";
        return $this->db->query($sql);
    }
}
