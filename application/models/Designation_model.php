<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class designation_model extends CI_Model {

    public function add_designation($data) {    //1 method
        $q = $this->db->insert('designation_header_all', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
     public function add_leave_rqst($data) {    //1 method
        $q = $this->db->insert('leave_transaction_all', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function get_hr($firm_id)
    {   
        $query= $this->db->query("SELECT `designation_id` from `user_header_all` where `user_type`='5' and  FIND_IN_SET('" . $firm_id . "', `hr_authority`)");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }
    public function get_hr_designation($desig_id_hr)
    {
        $query= $this->db->query("SELECT * from `designation_header_all` where `designation_id`='$desig_id_hr'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }
    
    
    public function select()
 {
  $query= $this->db->query("SELECT * from `test`");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
 }
}


