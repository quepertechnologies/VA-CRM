<?php

namespace App\Models;

class Ticket_comments_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'ticket_comments';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $tickets_table = $this->db->prefixTable('tickets');
        $ticket_comments_table = $this->db->prefixTable('ticket_comments');
        $users_table = $this->db->prefixTable('users');
        $where = "";
        $sort = "ASC";

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $ticket_comments_table.id=$id";
        }

        $ticket_id = $this->_get_clean_value($options, "ticket_id");
        if ($ticket_id) {
            $where .= " AND $ticket_comments_table.ticket_id=$ticket_id";
        }

        $fresh_desk_comment_id = $this->_get_clean_value($options, "fresh_desk_comment_id");
        if ($fresh_desk_comment_id) {
            $where .= " AND $ticket_comments_table.fresh_desk_comment_id=$fresh_desk_comment_id";
        }

        $sort_decending = $this->_get_clean_value($options, "sort_as_decending");
        if ($sort_decending) {
            $sort = "DESC";
        }

        $is_note = $this->_get_clean_value($options, "is_note");
        if (!is_null($is_note)) {
            $where .= " AND $ticket_comments_table.is_note=$is_note";
        }

        $sql = "SELECT $ticket_comments_table.*, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar, $users_table.user_type, $tickets_table.creator_name, $tickets_table.creator_email
        FROM $ticket_comments_table
        LEFT JOIN $users_table ON $users_table.id= $ticket_comments_table.created_by
        LEFT JOIN $tickets_table ON $tickets_table.id= $ticket_comments_table.ticket_id
        WHERE $ticket_comments_table.deleted=0 $where
        ORDER BY $ticket_comments_table.created_at $sort";

        return $this->db->query($sql);
    }
}
