<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Hr_CheckList_DocumentModel
 *
 * @author User
 */
class Hr_CheckList_DocumentModel extends CI_Model {

    public function create_checklist_item() {

        $this->db->insert("hr_checklist_transaction_all", $this);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update_checklist_item($where) {
        $result = $this->db->set($this)->where($where)->update("hr_checklist_transaction_all");
        if ($result > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_checklist_item($where) {
        $this->db->get("hr_checklist_transaction_all")->result();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

}
