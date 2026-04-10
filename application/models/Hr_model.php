<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hr_model extends CI_Model {

    public function get_human_resource($firm_id) {
        $query = $this->db->query("select user_header_all.* ,partner_header_all.firm_name from user_header_all INNER join partner_header_all on partner_header_all.firm_id = user_header_all.hr_authority where user_header_all.user_type = 5 and user_header_all.firm_id='$firm_id'");
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
        $this->db->where('file_id', $id);
        $del_query = $this->db->delete('hr_working_paper');
		//echo $this->db->last_query();
        if ($del_query === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function upadte_working_ppr($work_id, $firm_id, $data) {
        $where = array('id' => $work_id, 'firm_id' => $firm_id);
        $this->db->where($where);
        $update_query = $this->db->update('hr_working_paper', $data);
        if ($update_query === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function delete_batch_modal($batch_id) {
        $concat_batch_id_with_comma = ',' . $batch_id;
        $string_to_array_of_batch_id = explode(',', $concat_batch_id_with_comma);
        unset($string_to_array_of_batch_id[0]);
        $array_of_batch_id = array_values($string_to_array_of_batch_id);
        $arrReturn = []; //Declare the array to be passed
        $this->db->select('batch_id,user_id');
        $this->db->from('user_header_all ');
        $this->db->where('batch_id<>"0"');
        $qu1 = $this->db->get();
        $result = $qu1->result_array();
        if (!empty($result)) {
            $arrReturn = $result;
        }
        $array_of_elements = [];
        for ($z = 0; $z < count($arrReturn); $z++) {
            $user_id_for_update = $arrReturn[$z]['user_id'];
            $seprate_batch_id = explode(',', $arrReturn[$z]['batch_id']);

            $valur_after_diff = array_diff($seprate_batch_id, $array_of_batch_id);
            $valur_after_diff_finial = array_values($valur_after_diff);
            $valur_after_diff_finial_string = implode(',', $valur_after_diff_finial);

            if ($valur_after_diff_finial_string == null) {
                $valur_after_diff_finial_string = 0;
            } else {
                $valur_after_diff_finial_string = implode(',', $valur_after_diff_finial);
            }
            $query_for_update = $this->db->query("update user_header_all set batch_id='$valur_after_diff_finial_string' where batch_id<>'0' and user_id='$user_id_for_update'");
        }
        $query_for_delete = $this->db->query("Delete FROM `survey_batch_information_all` WHERE `batch_id`='$batch_id'");
        if ($query_for_delete && $query_for_update) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
   
    public function add_holiday_data($data_holiday) {
        try {
            $this->db->trans_start();
            $this->db->insert('holiday_master_all', $data_holiday);

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

    public function getHolidayMasterList() {
        $query = $this->db->query("SELECT id, holiday_name, holiday_start_date, holiday_end_date, holiday_day, holiday_color_code,
                                    date,category,firm_id,description
                                    FROM holiday_master_all WHERE YEAR(holiday_start_date) = YEAR(CURDATE()) ORDER BY holiday_start_date ASC");
        // $query = $this->db->query("SELECT holiday_start_date FROM holiday_header_all LIMIT 1");
        return $query;
    }

    public function getEventTypeList() {
        $query = $this->db->query("SELECT id, type FROM event_type WHERE status = 1 ORDER BY type ASC");
        return $query;

    }

    public function getEventMasterList() {
        // $query = $this->db->query("SELECT * FROM event_master WHERE status = 1 AND start_date >= CURDATE() ORDER BY created_on ASC");
        $query = $this->db->query("SELECT * FROM event_master WHERE status = 1 AND YEAR(created_on) = YEAR(CURDATE()) ORDER BY created_on DESC");
        return $query;
    }

    public function deleteBulkHolidayData() {
        $query = $this->db->query("DELETE FROM holiday_master_all WHERE YEAR(holiday_start_date) = YEAR(CURDATE()) AND bulk_upload=1");
        return $query;
    }

    public function deleteSingleHolidayData($id) {
        $query = $this->db->query("DELETE FROM holiday_master_all WHERE id=$id");
        return $query;
    }

    public function deleteBulkEventData() {
        $query = $this->db->query("DELETE FROM event_master WHERE status=1");
        return $query;
    }

    public function deleteSingleEventData($id) {
        $query = $this->db->query("DELETE FROM event_master WHERE id=$id AND status=1");
        return $query;
    }

}
