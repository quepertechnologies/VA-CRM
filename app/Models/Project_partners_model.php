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

        $partner_type = $this->_get_clean_value($options, "partner_type");
        if ($partner_type) {
            $query .= " AND $project_partners.partner_type='$partner_type'";
        }

        $only_partner_types = $this->_get_clean_value($options, "only_partner_types");
        if ($only_partner_types) {
            $query .= " AND FIND_IN_SET($project_partners.partner_type, '$only_partner_types')";
        }

        $sql = "SELECT $project_partners.* FROM $project_partners
        WHERE $project_partners.deleted=0 $query";

        $raw_query = $this->db->query($sql);

        // $total_rows = $this->db->query("SELECT FOUND_ROWS() as found_rows")->getRow();

        return $raw_query;
    }

    function own_clients_only_client_id($partner_id = 0, $partner_type = "institute")
    {
        $projects_table = $this->db->prefixTable('projects');
        $project_partners_table = $this->db->prefixTable('project_partners');
        $clients_table = $this->db->prefixTable('clients');

        $sql = "SELECT $clients_table.id
        FROM $clients_table
        LEFT JOIN $projects_table ON $projects_table.client_id=$clients_table.id
        LEFT JOIN $project_partners_table ON $project_partners_table.project_id=$projects_table.id
        WHERE $project_partners_table.partner_type='$partner_type' AND $project_partners_table.partner_id=$partner_id AND $clients_table.deleted=0
        ORDER BY $clients_table.first_name ASC";

        return $this->db->query($sql);
    }

    function own_clients($partner_id = 0, $partner_type = "institute")
    {
        $labels_table = $this->db->prefixTable("labels");
        $projects_table = $this->db->prefixTable('projects');
        $project_partners_table = $this->db->prefixTable('project_partners');
        $clients_table = $this->db->prefixTable('clients');
        $labels_query = "(SELECT GROUP_CONCAT($labels_table.id, '--::--', $labels_table.title, '--::--', $labels_table.color, ':--::--:') FROM $labels_table WHERE FIND_IN_SET($labels_table.id, $clients_table.labels)) AS labels_list";

        $sql = "SELECT $clients_table.*, $labels_query, project_table.total_projects
        FROM $clients_table
        LEFT JOIN (SELECT id, client_id, COUNT(id) AS total_projects FROM $projects_table WHERE deleted=0 AND project_type='client_project' GROUP BY client_id) AS project_table ON project_table.client_id=$clients_table.id
        LEFT JOIN $project_partners_table ON $project_partners_table.project_id=project_table.id
        WHERE $project_partners_table.partner_id=$partner_id AND $clients_table.deleted=0
        ORDER BY $clients_table.first_name ASC";

        return $this->db->query($sql);
    }


    function get_rest_partners_for_a_project($project_id = 0)
    {
        $project_members_table = $this->db->prefixTable('project_partners');
        // $users_table = $this->db->prefixTable('users');
        $clients_table = $this->db->prefixTable('clients');

        $sql = "SELECT $clients_table.id, CONCAT($clients_table.first_name, ' ',$clients_table.last_name) AS member_name, $clients_table.partner_type
        FROM $clients_table
        LEFT JOIN $project_members_table ON $project_members_table.partner_id=$clients_table.id
        WHERE $clients_table.account_type='3' AND $clients_table.id NOT IN (SELECT $project_members_table.partner_id FROM $project_members_table WHERE $project_members_table.project_id='$project_id' AND deleted=0)
        ORDER BY $clients_table.first_name ASC";

        return $this->db->query($sql);
    }


    function save_partner($data = array(), $id = 0)
    {
        $partner_id = $this->_get_clean_value($data, "partner_id");
        $project_id = $this->_get_clean_value($data, "project_id");
        if (!$partner_id || !$project_id) {
            return false;
        }

        $exists = $this->get_one_where(array("partner_id" => $partner_id, "project_id" => $project_id));
        // print_r($exists);
        if ($exists->id && $exists->deleted == 0) {
            //already exists
            return "exists";
        } else if ($exists->id && $exists->deleted == 1) {
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
