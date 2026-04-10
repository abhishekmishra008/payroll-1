<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class checklist_model extends CI_Model {

    public function insert_taskchecklist($data_file) {    //1 method
        $q = $this->db->insert(' task_checklist_transaction_all', $data_file);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //this function is for the delete checklist and action goes to employee_login delete_cjhecklist function
    public function delete_checklist($delete_data) {

        if ($delete_data !="") {
            $this->db->query("Delete FROM `task_checklist_transaction_all` WHERE `completed_on`='$delete_data'");
        } 
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>