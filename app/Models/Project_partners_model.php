<?php

namespace App\Models;

class Project_partners_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'project_partners';
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
        $project_partners = $this->db->prefixTable('project_partners');

        $query = '';

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $query .= " AND $project_partners.id='$id'";
        }

        $project_id = $this->_get_clean_value($options, "project_id");
        if ($project_id) {
            $query .= " AND $project_partners.project_id='$project_id'";
        }

        $partner_id = $this->_get_clean_value($options, "partner_id");
        if ($partner_id) {
            $query .= " AND $project_partners.partner_id='$partner_id'";
        }

        $sql = "SELECT $project_partners.* FROM $project_partners
        WHERE $project_partners.deleted=0 $query";

        $raw_query = $this->db->query($sql);

        // $total_rows = $this->db->query("SELECT FOUND_ROWS() as found_rows")->getRow();

        return $raw_query;
    }


    function get_rest_partners_for_a_project($project_id = 0)
    {
        $project_members_table = $this->db->prefixTable('project_partners');
        $users_table = $this->db->prefixTable('users');
        $clients_table = $this->db->prefixTable('clients');

        $sql = "SELECT $users_table.id, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS member_name
        FROM $users_table
        LEFT JOIN $project_members_table ON $project_members_table.partner_id=$users_table.client_id
        WHERE $users_table.user_type='client' AND $users_table.status='active' AND $users_table.deleted=0 AND $users_table.id NOT IN (SELECT $project_members_table.partner_id FROM $project_members_table WHERE $project_members_table.project_id='$project_id' AND deleted=0)
        AND $users_table.id IN (SELECT $clients_table.primary_contact_id FROM $clients_table WHERE $clients_table.account_type='3' AND deleted=0)
        ORDER BY $users_table.first_name ASC";

        return $this->db->query($sql);
    }


    function save_partner($data = array(), $id = 0)
    {
        $partner_id = $this->_get_clean_value($data, "partner_id");
        $project_id = $this->_get_clean_value($data, "project_id");
        $commission = $this->_get_clean_value($data, "commission");
        if (!$partner_id || !$project_id) {
            return false;
        }

        $exists = $this->get_one_where($where = array("partner_id" => $partner_id, "project_id" => $project_id));
        // print_r($exists);
        if ($exists->id && $exists->deleted == 0) {
            //already exists
            return "exists";
        } else if ($commission && $exists->id && $exists->deleted == 1) {
            //add new
            return parent::ci_save($data, $id);
        } else if ($exists->id && $exists->deleted == 1) {
            //undelete the record
            if (parent::delete($exists->id, true)) {
                return $exists->id;
            }
        } else {
            //add new
            return parent::ci_save($data, $id);
        }
    }
}
