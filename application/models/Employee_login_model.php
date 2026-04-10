<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_login_model extends CI_Model {

    public function get_employee_data($id) {    //1 method
        $this->db->select('*');
        $this->db->from('customer_task_allotment_all');
        $this->db->join('customer_header_all', 'customer_task_allotment_all.customer_id = customer_header_all.customer_id');
        //  $this->db->join('task_header_all', 'customer_task_allotment_all.task_id = task_header_all.task_id');
//        $this->db->join('sub_task_header_all', 'customer_task_allotment_all.sub_task_id = sub_task_header_all.sub_task_id');
        $this->db->where("customer_task_allotment_all.id='$id'");
        $query = $this->db->get();
        //$query = $this->db->query("SELECT * FROM `customer_task_allotment_all` WHERE  id='$id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }
	
	
	public function updt_survey_rejection($data_for_update, $where_data) {
        $result = $this->db->set($data_for_update)
                ->where($where_data)
                ->update('user_header_all');

//        echo $this->db->last_query();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
	
    public function emp_customer_task_status($customer_id) {
        $data = array('modified_on' => date('Y-m-d H:i:s'), 'status' => '4');
        $this->db->where('customer_id', $customer_id);
        $this->db->update('customer_task_allotment_all', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_employee_question_ans_transaction($question_id, $employee_id) {
        $this->db->select('*');
        $this->db->from('employee_question_answer_transaction');
        $this->db->where("`question_id` = '$question_id' AND `survey_respondent_id`='$employee_id'");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_weightage($option_now) {

        $this->db->select('weightage');
        $this->db->from('options');
        $this->db->where("`option_id` = '$option_now'");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function insert_employee_question_ans_transaction($data) {
        $q = $this->db->insert('employee_question_answer_transaction', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updt_employee_question_ans_transaction($a_data, $where) {
        $this->db->set($a_data);
        $this->db->where($where);
        $this->db->update('employee_question_answer_transaction');
        if ($this->db->affected_rows() == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function updt_survey_submit($data, $where) {

        $this->db->set($data);
        $this->db->where($where);
        $this->db->update('employee_question_answer_transaction');
        if ($this->db->affected_rows() == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function updt_user_survey($user_id) {

        $a_data = array(
            'survey_status' => 0,
            'batch_id' => 0
        );
        $this->db->set($a_data);
        $this->db->where('user_id',$user_id);
        $this->db->update('user_header_all');
        if ($this->db->affected_rows() == TRUE) {
            return true;
        } else {
            return false;
        }
    }
    
    
    
      public function updt_customer_survey($cust_id) {
        $a_data = array(
            'survey_status' => 0,
            'batch_id' => '0'
        );
        $this->db->set($a_data);
        $this->db->where('customer_id',$cust_id);
        $this->db->update('customer_header_all');
        if ($this->db->affected_rows() == TRUE) {
            return true;
        } else {
            return false;
        }
    }
    
     public function remove_employee($batch_id_for_update_batch,$user_id) {
       
        $query_get_batch_from_user_header_all = $this->db->query("select batch_id from user_header_all where user_id='$user_id' and user_type='4'");
        if ($query_get_batch_from_user_header_all->num_rows() > 0) {
            foreach ($query_get_batch_from_user_header_all->result() as $row) {
                $all_batch_ids = $row->batch_id;
            }
        }

        $concat_batch_id_with_comma = ',' . $batch_id_for_update_batch;
        $all_batch_ids_finial = substr($all_batch_ids, 0);
        $all_batch_ids_finial_array = explode(",", $all_batch_ids_finial);
        $string_to_array_of_batch_id = explode(',', $concat_batch_id_with_comma);
        unset($string_to_array_of_batch_id[0]);

        $new_array_after_remove = array_diff($all_batch_ids_finial_array, $string_to_array_of_batch_id);
        $new_array_after_remove_finial = array_values($new_array_after_remove);
        $finial_string_after_removing_main_batch = implode(',', $new_array_after_remove_finial);
        $query_get_user_from_survey_information_all = $this->db->query("select emp_id from survey_batch_information_all where batch_id='$batch_id_for_update_batch'");
        if ($query_get_user_from_survey_information_all->num_rows() > 0) {
            foreach ($query_get_user_from_survey_information_all->result() as $row) {
                $all_emp_ids = $row->emp_id;
            }
        }
        $concat_user_id_with_comma = ',' . $user_id;
        $all_user_ids_finial = substr($all_emp_ids, 0);
        $all_user_ids_finial_array = explode(",", $all_user_ids_finial);
        $string_to_array_of_user_id = explode(',', $concat_user_id_with_comma);
        unset($string_to_array_of_batch_id[0]);

        $new_array_after_remove_of_user = array_diff($all_user_ids_finial_array, $string_to_array_of_user_id);
        $new_array_after_remove_finial_of_user = array_values($new_array_after_remove_of_user);
        $finial_string_after_removing_main_batch_of_user = implode(',', $new_array_after_remove_finial_of_user);
                
        
        $query_for_remove_employee_from_user_header_all = $this->db->query("update user_header_all set batch_id='$finial_string_after_removing_main_batch' where user_id='$user_id'");
        $query_for_remove_employee_from_survey_batch_information_all = $this->db->query("update survey_batch_information_all set emp_id='$finial_string_after_removing_main_batch_of_user',batch_status='5' where batch_id='$batch_id_for_update_batch'");
        if ($query_for_remove_employee_from_user_header_all && $query_for_remove_employee_from_survey_batch_information_all) {
            return true;
        } else {
            return FALSE;
        }
      
    }
    

}
?>


