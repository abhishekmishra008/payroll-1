<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends CI_Model {
   public function insert_event($data) {    //1 method
        $q = $this->db->insert('holiday_management_all', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>