<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

    public function update($data, $firm_id) {    //1 method
        $q = $this->db->where('firm_id', $firm_id);
        $res = $this->db->update('partner_header_all', $data);
        if ($res == true) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateUserPermission($fetch_firm_id, $data) {
        $this->db->set($data);
        $this->db->where("firm_id = '$fetch_firm_id' AND user_type='3'");
        $this->db->update('user_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function viewPermissionDetails($firm_id) {
        $query = $this->db->select('*');
        $query = $this->db->where("firm_id='$firm_id' AND activity_status='1' AND user_type='3'");
        $query = $this->db->get('user_header_all');
        $num = $query->num_rows();
        // here you can do something with $query
        if ($num == 1) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function updateprofile($firm_id, $data) {
        $this->db->set($data);
        $this->db->where("firm_id", $firm_id);
        $this->db->update('partner_header_all');

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } elseif ($this->db->affected_rows() == 0) {
            return 0;
        } else {
            return FALSE;
        }
    }

    public function uploadlogo($firm_id, $user_id, $data1) {
        $where = array(
            'firm_id' => $firm_id,
            'user_id' => $user_id,
        );

        $this->db->where($where);
        $res = $this->db->update('user_header_all', $data1);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } elseif ($this->db->affected_rows() == 0) {
            return 0;
        } else {
            return FALSE;
        }
    }

}

?> 