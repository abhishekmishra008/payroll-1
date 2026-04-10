<?php

class SalaryInfoModel extends CI_Model {

    public function delete_salery_type($where) { //done by pooja
        try {
            $this->db->trans_start();
            $this->db->delete('m_salarytype', $where);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('info', "delete transaction Rollback");
                $result = FALSE;
            } else {
                $this->db->trans_commit();
                log_message('info', "delete transaction Commited");
                $result = TRUE;
            }
            $this->db->trans_complete();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            $result = FALSE;
        }
        return $result;
    }

    public function delete_dedu_type($where) { //done by pooja
        try {
            $this->db->trans_start();
            $this->db->delete('m_standarddeductions', $where);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('info', "delete transaction Rollback");
                $result = FALSE;
            } else {
                $this->db->trans_commit();
                log_message('info', "delete transaction Commited");
                $result = TRUE;
            }
            $this->db->trans_complete();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            $result = FALSE;
        }
        return $result;
    }

    public function get_sal_type_list() {
        $this->db->select('*');
        $this->db->from('m_salarytype');
//        $this->db->where($where);
        $query = $this->db->get();
        if ($query !== FALSE) {
            return $query;
        } else {
            return FALSE;
        }
    }public function getEmployeeSalary($where) {
        $this->db->select('*');
        $this->db->from('t_salarytype');
       $this->db->where($where);
        $query = $this->db->get();
        if ($query !== FALSE) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_ded_type_list() {
        $this->db->select('*');
        $this->db->from('m_standarddeductions');
//        $this->db->where($where);
        $query = $this->db->get();
        if ($query !== FALSE) {
            return $query;
        } else {
            return FALSE;
        }
    }

}
