<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class warning_modal extends CI_Model {

    public function save_card($data) {
        $q = $this->db->insert('card_header_all', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function save_warning($data) {
        $q = $this->db->insert('warning_header_all', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
     public function update_warning($data,$where_data) {
         $this->db->set($data);
         $this->db->where($where_data);
         $this->db->update('warning_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    
    public function delete_warning_card($warning_card_id){
        $this->db->where('warning_id',$warning_card_id);
        $this->db->delete('warning_header_all');
        if($this->db->affected_rows()>0){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    public function update_card($where_data, $update_data) {
        $this->db->set($update_data);
        $this->db->where($where_data);
        $this->db->update('card_header_all');
        if ($this->db->affected_rows() == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function update_accept_revert($data, $where_con) {

        $this->db->set($data);
        $this->db->where($where_con);
        $this->db->update('send_warning_transaction');
        if ($this->db->affected_rows() == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function update_decline_revert($data, $where_con) {

        $this->db->set($data);
        $this->db->where($where_con);
        $this->db->update('send_warning_transaction');
        if ($this->db->affected_rows() == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function update_accept_warning($data, $where_data) {

        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('send_warning_transaction');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_send_revert($data, $where_con) {

        $this->db->set($data);
        $this->db->where($where_con);
        $this->db->update('send_warning_transaction');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_cancel_revert($data, $where_con) {

        $this->db->set($data);
        $this->db->where($where_con);
        $this->db->update('send_warning_transaction');
        if ($this->db->affected_rows() > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function save_send_warning($data) {
        $q = $this->db->insert('send_warning_transaction', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function delete_dispute_modal($where_data) {
        $this->db->where($where_data);
        $this->db->delete('send_warning_transaction');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function save_updated_data_dispute($data, $where_data) {

        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('send_warning_transaction');
        if ($this->db->affected_rows() == TRUE) {
            return true;
        } else {
            return false;
        }
    }

}

?>
