<?php

/**
 * Description of Hr_CheckList_Model
 *
 * @author User
 */
class Hr_CheckList_Model extends CI_Model {

    public function create_checklist_item() {

        $this->db->insert("hr_checklist_header_all", $this);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update_checklist_item($where) {
        $this->db->set($this);
        $this->db->where($where);
        $this->db->update("hr_checklist_header_all");
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_checklist_item($where) {
        $result = $this->db->where($where)->get("hr_checklist_header_all")->result();
        if (count($result) > 0) {
            return $result;
        } else {
            return array();
        }
    }

}
