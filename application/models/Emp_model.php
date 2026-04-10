<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class emp_model extends CI_Model {

    public function add_emp($data) {
        $q = $this->db->insert('user_header_all', $data);
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_firm_id() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        if ($user_id === "") {
            $user_id = $this->session->userdata('login_session');
        }
        $user_id = $this->session->userdata('login_session');
        $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$user_id'");
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $user_data = array(
                'user_id' => $data->user_id,
                'firm_id' => $data->firm_id,
                'boss_id' => $data->linked_with_boss_id,
            );
            return $user_data;
        } else {
            return FALSE;
        }
    }

    public function get_employee_hq($firm_id) {
        /* echo $firm_id; */
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        if ($user_id === "") {
            $user_id = $this->session->userdata('login_session');
        }
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where('firm_id', $firm_id);
        $query1 = $this->db->get();
        if ($query1->num_rows() > 0) {
            $result_hq_cust = $query1->row();
            $boss_id = $result_hq_cust->boss_id;


            $query = $this->db->query("SELECT `user_header_all`.`linked_with_boss_id`,`user_header_all`.`user_id`,`user_header_all`.`confirmation_date`,`user_header_all`.`user_type`,`user_header_all`.`is_employee`,`user_header_all`.`activity_status`,
             `user_header_all`.`firm_id`,eep.exit_date,`user_header_all`.`user_name`,`user_header_all`.`emp_code`,`user_header_all`.`mobile_no`, `user_header_all`.`email`,`user_header_all`.`address`,`user_header_all`.`city`,
             `user_header_all`.`state`,`user_header_all`.`country`,`user_header_all`.`date_of_joining`,`user_header_all`.`designation_id`,`designation_header_all`.`designation_name`
             FROM `user_header_all` LEFT JOIN `designation_header_all` ON `user_header_all`.`designation_id`=`designation_header_all`.`designation_id`
             left join exit_emp_summary eep on eep.user_id = user_header_all.user_id
             
             where `user_header_all`.`is_employee` = 1 AND `user_header_all`.`designation_id`!='CA' AND  `user_header_all`.firm_id='$firm_id'  order by activity_status desc");

            /* $query = $this->db->query("SELECT `user_header_all`.`user_id`,`user_header_all`.`user_type`,`user_header_all`.`is_employee`,`user_header_all`.`activity_status`,
              `user_header_all`.`firm_id`,`user_header_all`.`user_name`,`user_header_all`.`mobile_no`, `user_header_all`.`email`,`user_header_all`.`address`,`user_header_all`.`city`,
              `user_header_all`.`state`,`user_header_all`.`country`,`user_header_all`.`date_of_joining`,`user_header_all`.`designation_id`,`designation_header_all`.`designation_name`
              FROM `user_header_all` INNER JOIN `designation_header_all` ON `designation_header_all`.`designation_id`=`user_header_all`.`designation_id`
              where `user_header_all`.`is_employee` = 1 AND `user_header_all`.firm_id='$firm_id' AND `designation_header_all`.firm_id='$firm_id' AND `user_header_all`.linked_with_boss_id = '$boss_id'"); */
            if ($query->num_rows() > 0) {
                return $query;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function view_privateDuedateDat() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }

        $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $task_firm_id = $data->firm_id;
            $user_id = $data->user_id;

            $query = $this->db->query("	SELECT c.* , (SELECT due_date_task_name FROM `due_date_task_header_all` WHERE `due_date_task_id`=c.due_date_task_id ) as due_date_task_name,
			(SELECT user_name FROM `user_header_all` WHERE  `user_id` = c.alloted_to ) as user_name,
					(SELECT customer_name FROM `customer_header_all` WHERE  `customer_id` = c.customer_id ) as customer_name,
			(select help_file_attached from due_date_task_header_all where due_date_id=c.due_date_id and due_date_task_id=c.due_date_task_id) as help_file_attached
			FROM `customer_due_date_task_transaction_all` c  where c.alloted_to='$user_id' AND c.firm_id = '$task_firm_id' order by last_date_of_submission desc");
            //echo $this->db->last_query();
            if ($query->num_rows() > 0) {
                return $query;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function view_PublicDuedateDateTask() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }
        $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $task_firm_id = $data->firm_id;
            $user_id = $data->user_id;

//            echo "SELECT * FROM `customer_due_date_task_transaction_all` WHERE `alloted_to`!='$user_id' AND `firm_id` = '$task_firm_id'";

            $query = $this->db->query("	SELECT c.* , (SELECT due_date_task_name FROM `due_date_task_header_all` WHERE `due_date_task_id`=c.due_date_task_id ) as due_date_task_name,
			(SELECT user_name FROM `user_header_all` WHERE  `user_id` = c.alloted_to ) as user_name,
					(SELECT customer_name FROM `customer_header_all` WHERE  `customer_id` = c.customer_id ) as customer_name,
			(select help_file_attached from due_date_task_header_all where due_date_id=c.due_date_id and due_date_task_id=c.due_date_task_id) as help_file_attached
			FROM `customer_due_date_task_transaction_all` c  where (c.alloted_to!='$user_id' OR c.alloted_to='$user_id') AND c.firm_id = '$task_firm_id' order by last_date_of_submission desc");
            if ($query->num_rows() > 0) {
                return $query;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function get_employee() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        if ($user_id === "") {
            $user_id = $this->session->userdata('login_session');
        }
        $user_data = $this->db->query("select * from user_header_all where email='$user_id'");
        $user = $user_data->row();
        $user_Type = $user->user_type;
        if ($user_Type == 3) {

            $this->db->select('*');
            $this->db->from('partner_header_all');
            $this->db->where('firm_email_id', $user_id);
            $query1 = $this->db->get();
            if ($query1->num_rows() > 0) {
                $result_hq_cust = $query1->row();
                $firm_id = $result_hq_cust->firm_id;
                $boss_id = $result_hq_cust->boss_id;
//            $response = array('code' => -1, 'status' => false, 'message' => '');
//            $array = array('is_employee' => '1', 'firm_id' => $firm_id, 'linked_with_boss_id' => $boss_id, 'created_by' => $user_id);
//            $query = $this->db->select('*')->from('user_header_all')->where($array)->get();




                $query = $this->db->query("SELECT `user_header_all`.`linked_with_boss_id`,`user_header_all`.`user_id`,`user_header_all`.`confirmation_date`,`user_header_all`.`user_type`,`user_header_all`.`is_employee`,`user_header_all`.`activity_status`,
             `user_header_all`.`firm_id`,`user_header_all`.`user_name`,`user_header_all`.`mobile_no`, `user_header_all`.`email`,`user_header_all`.`address`,`user_header_all`.`city`,
             `user_header_all`.`state`,`user_header_all`.`country`,`user_header_all`.`date_of_joining`,`user_header_all`.`designation_id`,`designation_header_all`.`designation_name`
             FROM `user_header_all` LEFT JOIN `designation_header_all` ON `user_header_all`.`designation_id`=`designation_header_all`.`designation_id`
             where `user_header_all`.`is_employee` = 1 AND `user_header_all`.`designation_id`!='CA' AND  `user_header_all`.firm_id='$firm_id' AND `user_header_all`.linked_with_boss_id = '$boss_id'");


//echo $this->db->last_query();
            } else {
                return false;
            }
        } else {
            $this->db->select('*');
            $this->db->from('user_header_all');
            $this->db->where('created_by', $user_id);
            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                $result_hq_cust = $result->row();
                $firm_id = $result_hq_cust->firm_id;
                $designation_id = $result_hq_cust->designation_id;
                $query = $this->db->query("select u.*,d.designation_name from user_header_all u left join designation_header_all d on u.firm_id=d.firm_id where u.created_by='$user_id' and u.firm_id='$firm_id' and d.firm_id='$firm_id' and d.designation_id='$designation_id'");
            } else {
                return false;
            }
        }
        //echo $this->db->last_query();
//        var_dump($query); echo "vishu";
        //echo $a;
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    public function get_employee_new($firm_id) {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        if ($user_id === "") {
            $user_id = $this->session->userdata('login_session');
        }


//            $response = array('code' => -1, 'status' => false, 'message' => '');
//            $array = array('is_employee' => '1', 'firm_id' => $firm_id, 'linked_with_boss_id' => $boss_id, 'created_by' => $user_id);
//            $query = $this->db->select('*')->from('user_header_all')->where($array)->get();


        $query = $this->db->query("SELECT `user_header_all`.`user_id`,`user_header_all`.`confirmation_date`,`user_header_all`.`user_type`,`user_header_all`.`is_employee`,`user_header_all`.`activity_status`,
             `user_header_all`.`firm_id`,`user_header_all`.`user_name`,`user_header_all`.`mobile_no`, `user_header_all`.`email`,`user_header_all`.`address`,`user_header_all`.`city`,
             `user_header_all`.`state`,`user_header_all`.`country`,`user_header_all`.`date_of_joining`,`user_header_all`.`designation_id`,`designation_header_all`.`designation_name`
             FROM `user_header_all` INNER JOIN `designation_header_all` ON `designation_header_all`.`designation_id`=`user_header_all`.`designation_id`
                                                where `user_header_all`.`is_employee` = 1 AND `user_header_all`.firm_id='$firm_id' AND `designation_header_all`.firm_id='$firm_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    function is_email_available($email) {
        $this->db->where('email', $email);
        $query = $this->db->get("user_header_all");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
     function is_edit_email_available($email,$user_id) {
         $query = $this->db->query("select * from user_header_all where user_id!='$user_id' and email ='$email'");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    

    public function activity_status($user_id, $status) {
        if ($status == 'A') {
            $firm_activity_status = 'D';
            $user_activity_status = '0';
        } else {
            $firm_activity_status = 'A';
            $user_activity_status = '1';
        }
        $data = array('modified_on' => date('Y-m-d H:i:s'), 'modified_by' => $user_id);
        $this->db->set('activity_status', $user_activity_status);
        $this->db->where('user_id', $user_id);
        $this->db->update('user_header_all', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_hr_name_in_hq($firm_id) {
        $query = $this->db->query("SELECT * from `user_header_all` where `user_type`='5' and  FIND_IN_SET('" . $firm_id . "', `hr_authority`) and activity_status=1");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function getEmployeeTaskAssignment($employee_id, $firm_id) {
        $query = $this->db->query(" SELECT * FROM `customer_task_allotment_all` WHERE `alloted_to_emp_id`='$employee_id' AND `firm_id`='$firm_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function privateDueDateTask($employee_id, $firm_id) {
        //echo "SELECT * FROM `customer_due_date_task_transaction_all` WHERE `alloted_to`='$employee_id' AND `firm_id` = '$firm_id'";
        $query = $this->db->query("SELECT * FROM `customer_due_date_task_transaction_all` WHERE `alloted_to`='$employee_id' AND `firm_id` = '$firm_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function publicDueDateTask($employee_id, $firm_id) {
        //echo "SELECT * FROM `customer_due_date_task_transaction_all` WHERE `alloted_to`!='$employee_id' AND `firm_id` = '$firm_id'";
        $query = $this->db->query("SELECT * FROM `customer_due_date_task_transaction_all` WHERE `alloted_to`!='$employee_id' AND `firm_id` = '$firm_id'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function employeeConvenerTask($employee_id, $firm_id) {


        $query = $this->db->query("SELECT `task_header_all`.`task_name`,`user_header_all`.`user_name`,`sub_task_header_all`.`checklist_created`,
                                    `customer_task_allotment_all`.`task_assignment_id`,`customer_task_allotment_all`.`task_assignment_description`,
                                    `customer_task_allotment_all`.`task_assignment_name`,`customer_task_allotment_all` .`status`,
                                    `customer_task_allotment_all`.`task_id`,`sub_task_header_all`.`sub_task_name`,
                                    `sub_task_header_all`.`sub_task_id` ,`customer_task_allotment_all`.`completion_date`,
                                        `customer_header_all`.`customer_name` FROM `customer_task_allotment_all`
                                        INNER JOIN `customer_header_all` ON
                                        `customer_header_all`.`customer_id` = `customer_task_allotment_all`.`customer_id`
                                        INNER JOIN `task_header_all`
                                        ON `task_header_all`.`task_id`=`customer_task_allotment_all`.`task_id`
                                        INNER JOIN `sub_task_header_all`
                                        on `sub_task_header_all`.`sub_task_id`=`customer_task_allotment_all`.`sub_task_id`
                                        INNER JOIN `user_header_all` ON `user_header_all`.`user_id`=`customer_task_allotment_all`.`alloted_to_emp_id`
                                        where `customer_task_allotment_all`.`alloted_to_emp_id`='$employee_id' and `customer_task_allotment_all`.`sub_task_id`='' and `customer_task_allotment_all`.firm_id='$firm_id'");

        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function employeeTask($employee_id, $firm_id) {

        $query = $this->db->query("SELECT `task_header_all`.`task_name`,`user_header_all`.`user_name`,`sub_task_header_all`.`checklist_created`,
                                    `customer_task_allotment_all`.`task_assignment_id`,`customer_task_allotment_all`.`task_assignment_description`,
                                    `customer_task_allotment_all`.`task_assignment_name`,`customer_task_allotment_all` .`status`,
                                    `customer_task_allotment_all`.`task_id`,`sub_task_header_all`.`sub_task_name`,
                                    `sub_task_header_all`.`sub_task_id` ,`customer_task_allotment_all`.`completion_date`,
                                        `customer_header_all`.`customer_name` FROM `customer_task_allotment_all`
                                        INNER JOIN `customer_header_all` ON
                                        `customer_header_all`.`customer_id` = `customer_task_allotment_all`.`customer_id`
                                        INNER JOIN `task_header_all`
                                        ON `task_header_all`.`task_id`=`customer_task_allotment_all`.`task_id`
                                        INNER JOIN `sub_task_header_all`
                                        on `sub_task_header_all`.`sub_task_id`=`customer_task_allotment_all`.`sub_task_id`
                                        INNER JOIN `user_header_all` ON `user_header_all`.`user_id`=`customer_task_allotment_all`.`alloted_to_emp_id`
                                        where `customer_task_allotment_all`.`alloted_to_emp_id`='$employee_id' and `customer_task_allotment_all`.firm_id='$firm_id'");

        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    //bhava 
    public function get_pa($user_id) {
        $result = $this->db->query("SELECT * FROM `t_perfomanceallowance` where `user_id` = '$user_id'");
        if ($result !== 0) {
            $data = $result->result();
            var_dump($data);
            return $data;
        } else {
            return FALSE;
        }
    }

    public function add_user($data_controller) {
        try {
            $this->db->trans_start();
            $this->db->insert(' t_perfomanceallowance', $data_controller);

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

    public function h_PA($data_controller1) {
        try {
            $this->db->trans_start();
            $this->db->insert('h_perfomanceallowance', $data_controller1);

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

    public function add_loan($loan_data) {
        try {
            $this->db->trans_start();
            $this->db->insert('t_staffloan', $loan_data);

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

    public function h_loan($loandata) {
        try {
            $this->db->trans_start();
            $this->db->insert('h_staffloan', $loandata);

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

    public function add_salary($salary_data) {
        try {
            $this->db->trans_start();
            $this->db->insert('t_salarytype', $salary_data);

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

    public function h_salary($salarydata) {
        try {
            $this->db->trans_start();
            $this->db->insert('h_salarytype', $salarydata);

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

    public function h_due_details($duedata) {
        try {
            $this->db->trans_start();
            $this->db->insert('h_standarddeductions', $duedata);

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

    public function due_details($due_data) {
        try {
            $this->db->trans_start();
            $this->db->insert('t_standarddeductions', $due_data);

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

    public function get_loan($where) {
        $this->db->select('*');
        $this->db->from('t_staffloan');
        $this->db->where($where);
        $query = $this->db->get();
        if ($query !== FALSE) {
            return $query;
        } else {
            return FALSE;
        }
    }

    function delete_pa($where) {
        try {
            $this->db->trans_start();
            $this->db->delete('t_perfomanceallowance', $where);
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

    function delete_sal($where) {
        try {
            $this->db->trans_start();
            $this->db->delete('t_salarytype', $where);
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

    function delete_deduction($where) {
        try {
            $this->db->trans_start();
            $this->db->delete('t_standarddeductions', $where);
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

    public function delete_salery_type($where) { //done by pooja
        try {
            $this->db->trans_start();
            $this->db->delete('t_salarytype', $where);
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

    public function get_sal_type_list($where) {
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

    public function get_ded_type_list($where) {
        $this->db->select('*');
        $this->db->from('t_standarddeductions');
        $this->db->where($where);
        $query = $this->db->get();
        if ($query !== FALSE) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_performance_list($where) {
        $this->db->select('*');
        $this->db->from('t_perfomanceallowance');
        $this->db->where($where);
        $query = $this->db->get();
        if ($query !== FALSE) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function get_loandetails_list($where) {
        $this->db->select('*');
        $this->db->from('t_staffloan');
        $this->db->where($where);
        $query = $this->db->get();
        if ($query !== FALSE) {
            return $query;
        } else {
            return FALSE;
        }
    }

    public function GetAttendanceInfo($selectdData, $whereData) {
        $selectDataAdditional = array('user_id', 'applicable_status','outside_punch_applicable','shift_applicable', 'work_hour_status', 'fixed_time', 'work_schedule_status', 'fixed_hour','late_applicable_sts','late_salary_days_count','late_salary_cut_days','late_minute_count');
        $this->db->select($selectdData);
        $this->db->where($whereData);
        $result = $this->db->get('user_header_all');
        if ($result->num_rows() > 0) {
            $data = $result->result();
            if ($data[0]->applicable_status == 1) {
                $this->db->select($selectDataAdditional);
                $this->db->where($whereData);
                $result1 = $this->db->get('user_header_all');
                if ($result1->num_rows() > 0) {
                    $data1 = $result1->result();
                    if ($data1[0]->work_schedule_status == 1) {

                        return $f_data = ['normal_data' => $data1];
                    } else {
                        $this->db->select(array('*'));
                        $this->db->where($whereData);
                        $result2 = $this->db->get('attendance_employee_applicable');
                        if ($result2->num_rows() > 0) {
                            $data3 = $result2->result();
                            $f_data = ['normal_data' => $data1, 'additional_data' => $data3];
                            return $f_data;
                        } else {
                            
                        }
                    }
                }
            } else {
                return ['normal_data' => $result->result()];
            }
        } else {
            return false;
        }
    }

    function DeleteData($data) {
        $this->db->where($data);
        $this->db->delete('attendance_employee_applicable');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function check_applicable_data($select_employee, $firm_name_check) {
        $this->db->where($select_employee, $firm_name_check);
        $this->db->select('attendance_employee_applicable');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>
