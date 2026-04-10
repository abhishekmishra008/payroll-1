<?php

class Excel_export_model extends CI_Model {

    function fetch_data() {
        $this->db->order_by("id", "DESC");
        $query = $this->db->get("user_header_all");
        return $query->result();
    }

}
