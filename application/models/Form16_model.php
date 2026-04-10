<?php

class Form16_model extends CI_Model {
    function get_header() {
        $query = $this->db->query("SELECT distinct Sr_no_header,header FROM form_16b WHERE approval_required ='yes' and header !=''");
//             $query = $this->db->get('form_16b' );
        return $query;
    }

    function get_sub_details($Sr_no_header) {
        $this->db->select('*');
        $this->db->from('form_16b');
        $this->db->where('sub_detail !=', '');
        $this->db->where('approval_required !=', '');
        $this->db->where('Sr_no_header', $Sr_no_header);
        $query = $this->db->get();
        return $query;
    }
    
      public function add_user1($add_Data) {
        try {
        function get_header(){
            $query=  $this->db->query("SELECT  Distinct header, Sr_no_header  FROM form_16b WHERE approval_required ='yes' and header !=''");
//             $query = $this->db->get('form_16b' );
             return $query;
        }
        function get_sub_details($Sr_no_header){
//            $query=  $this->db->query("select * from form_16b where approval_required='yes'");
             $query = $this->db->get_where('form_16b', array('Sr_no_header' => $Sr_no_header,'sub_detail!='=>' '));
         $this->db->last_query();
             return $query;
        }
        }
        catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            $result = FALSE;
        }
        return $result;
      }
    
    
      public function add_user($add_Data){
            try {
            $this->db->trans_start();
            $this->db->insert('staff_form16b', $add_Data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('info', "insert user Transaction Rollback");
                $result = FALSE;
            } else {
                $this->db->trans_commit();
                log_message('info', "insert user Transaction Commited");
                $result = TRUE;
            }
            $this->db->trans_complete();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            $result = FALSE;
        }
        return $result;
    }

}
?>

