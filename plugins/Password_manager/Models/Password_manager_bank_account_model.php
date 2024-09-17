<?php

namespace Password_manager\Models;

class Password_manager_bank_account_model extends \App\Models\Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'password_manager_bank_account';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $Password_manager_bank_account_table = $this->db->prefixTable('password_manager_bank_account');
        $Password_manager_categories_table = $this->db->prefixTable('password_manager_categories');
        $users_table = $this->db->prefixTable('users');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $Password_manager_bank_account_table.id=$id";
        }

        $is_admin = get_array_value($options, "is_admin");
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {

            //find passwords where share with the user and his/her team
            $team_ids = get_array_value($options, "team_ids");
            $team_search_sql = "";

            //searh for teams
            if ($team_ids) {
                $teams_array = explode(",", $team_ids);
                foreach ($teams_array as $team_id) {
                    $team_search_sql .= " OR (FIND_IN_SET('team:$team_id', $Password_manager_bank_account_table.share_with)) ";
                }
            }

            //searh for user
            $where .= " AND ($Password_manager_bank_account_table.created_by=$user_id 
                OR ($Password_manager_bank_account_table.created_by_client='1' AND $is_admin)
                OR $Password_manager_bank_account_table.share_with='all'
                    OR (FIND_IN_SET('member:$user_id', $Password_manager_bank_account_table.share_with))
                        $team_search_sql
                        )";
        }

        $client_id = get_array_value($options, "client_id");
        $contact_id = get_array_value($options, "contact_id");
        if ($client_id) {
            $where .= " AND (($Password_manager_bank_account_table.share_with_client='all' AND $Password_manager_bank_account_table.client_id=$client_id) OR FIND_IN_SET('contact:$contact_id', $Password_manager_bank_account_table.share_with_client) OR $Password_manager_bank_account_table.created_by=$contact_id)";
        }

        $client_id_filter = get_array_value($options, "client_id_filter");
        if ($client_id_filter) {
            $where .= " AND $Password_manager_bank_account_table.client_id=$client_id_filter";
        }

        $sql = "SELECT $Password_manager_bank_account_table.*, $Password_manager_categories_table.title AS category_title, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user
        FROM $Password_manager_bank_account_table
        LEFT JOIN $Password_manager_categories_table ON $Password_manager_categories_table.id=$Password_manager_bank_account_table.category_id
        LEFT JOIN $users_table ON $users_table.id=$Password_manager_bank_account_table.created_by
        WHERE $Password_manager_bank_account_table.deleted=0 $where";

        return $this->db->query($sql);
    }

}
