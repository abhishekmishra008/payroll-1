<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class hr_model extends CI_Model {

    public function get_human_resource($firm_id) {
        $query = $this->db->query("SELECT * from user_header_all where firm_id='$firm_id' AND user_type='5'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_firms($firm_id) {
        $query = $this->db->query("SELECT firm_name from partner_header_all where firm_id='$firm_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_hr_data($user_id) {
        $query = $this->db->query("SELECT * from user_header_all where user_id='$user_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_designation_data($designation_id) {
        $query = $this->db->query("SELECT * from designation_header_all where designation_id='$designation_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_leave_data($designation_id) {
        $query = $this->db->query("SELECT * from leave_header_all where designation_id='$designation_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function insert_working_paper($data) {
        $query = $this->db->insert('hr_working_paper', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_working_ppr($username) {
        $this->db->select('*');
        $this->db->from('hr_working_paper');
        $this->db->where('created_by', $username);
        $query = $this->db->get();
        // $query = $this->db->query("SELECT * from designation_header_all where designation_id='$designation_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_working_ppr_edit($id, $firm_id) {
        $array = array('id' => $id, 'firm_id' => $firm_id);
        $this->db->select('*');
        $this->db->from('hr_working_paper');
        $this->db->where($array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function delete_working_file($id) {
        $this->db->where('id', $id);
        $del_query = $this->db->delete('hr_working_paper');
        if ($del_query === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function upadte_working_ppr($work_id, $firm_id,$data) {
        $where = array('id' => $work_id, 'firm_id' => $firm_id);
        $this->db->where($where);
        $update_query = $this->db->update('hr_working_paper', $data);
        if ($update_query === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
