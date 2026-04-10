<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_job_model extends CI_Model {

    public function get_customer_task_allotment() {
        $query = $this->db->select('*');
        $query = $this->db->where("CURDATE() between `start_date` and `completion_date` and sub_task_id='' AND status='1'");
        $query = $this->db->get('customer_task_allotment_all');
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }
	
	public function chnage_survey_batch_status($data,$where_data){
		     
         $this->db->set($data);
         $this->db->where($where_data);
         $this->db->update('survey_batch_information_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	

}
?>

