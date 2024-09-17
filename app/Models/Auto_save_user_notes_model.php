<?php

namespace App\Models;

class Auto_save_user_notes_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'auto_save_user_notes';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $auto_save_user_notes = $this->db->prefixTable('auto_save_user_notes');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $auto_save_user_notes.id=$id";
        }

        $user_id = $this->_get_clean_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $auto_save_user_notes.user_id=$user_id";
        }

        $sql = "SELECT $auto_save_user_notes.*
        FROM $auto_save_user_notes
        WHERE $auto_save_user_notes.deleted=0 $where
        ORDER BY $auto_save_user_notes.id DESC";
        return $this->db->query($sql);
    }
}
