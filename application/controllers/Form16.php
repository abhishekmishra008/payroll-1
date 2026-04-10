<?php

//

class Form16 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Emp_model');
        $this->load->model('emp_model');
        $this->load->model('firm_model');
        $this->load->model('email_sending_model');
        $this->load->model('customer_model');
        $this->load->model('form16_model');
    }
	function form_16_creation(){
		 $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $emp_id = ($session_data['emp_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }
		 $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }
        //var_dump($this->session->userdata);
 $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }


        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $data['user_type'] = $record->user_type;
        }

//        echo $this->db->last_query();
        $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$emp_id'");
        if ($query_sen_id->num_rows() > 0) {
            $res_sen_id = $query_sen_id->result();
            foreach ($res_sen_id as $row) {
                $user_id_emp = $row->user_id;
                $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$emp_id' AND status='1'");
                if ($query_find_leave->num_rows() > 0) {
                    $result_sen_id = $query_find_leave->row();
                    $leave_requested_on = $result_sen_id->leave_requested_on;
                    $leave_type = $result_sen_id->leave_type;
                    $response['leave'][] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
                } else {

                    $leave_requested_on = '';
                    $leave_type = '';
                    $response['leave'] = '';
                }
            }
            $data['leave_type'] = $response['leave'];
            $data['leave_requested_on'] = $response['leave'];
        } else {

        }

//        $query1 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (9) or header IN (9) ");,10,11,12,13,14,15,16,17,18,19,20 SELECT SUM(amount) AS value_sum FROM staff_form16b where header=9 and sub_detail=16
        $query2 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (1, 2, 3,4,5,6,7,8) or header IN (1, 2, 3,4,5,6,7,8) ");
//        echo $this->db->last_query();

        $query1 = $this->db->query("Select form_16b.header, form_16b.sub_detail,sum(staff_form16b.amount)as amount,staff_form16b.comment,staff_form16b.approval_status
from form_16b inner join staff_form16b on staff_form16b.sub_detail = form_16b.id where staff_form16b.staff_id = '$emp_id' group by header,sub_detail");
        //echo $this->db->last_query();
        $query3 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (10,11,12,13,14,15,16,17,18,19,20) or header IN (10,11,12,13,14,15,16,17,18,19,20) ");

        if ($query2->num_rows() > 0) {
            $r2 = $query2->row();
//            var_dump($r2);
            $results = $query2->result();

            $Sr_no_header = $r2->Sr_no_header;
            $sub_detail = $r2->sub_detail;
            $data['sub_detail'] = $sub_detail;
            $data['results'] = $results;
            $data['Sr_no_header'] = $Sr_no_header;
        }
        if ($query1->num_rows() > 0) {
            $r1 = $query1->row();
            $result = $query1->result();
            //var_dump($result);

            $header = $r1->header;
            $sub_detail = $r1->sub_detail;
            $array = array_values($result);
            $dummy_array = array();
            $data['result'] = $result;
            $data['header'] = $header;

//            print_r(array_unique($array));
//            var_dump($array);
//            $duplicates = array_filter($array);
// var_dump($duplicates);
            //var_dump($data['result'] = $result);
            $data['header'] = $header;
            $staffq = $this->db->query("select * from staff_form16b ");
            if ($staffq->num_rows() > 0) {
                $output = $staffq->row();
                $header1 = $output->header;
                $sd = $output->sub_detail;

                $q_Add = $this->db->query("SELECT SUM(amount) AS amount FROM staff_form16b where header='$header1' and sub_detail='$sd'");
//                echo $this->db->last_query();
                if ($q_Add->num_rows() > 0) {
                    $qaresult = $q_Add->result();
                    $op = $q_Add->row();
                    $amount = $op->amount;
                    $data['res'] = $qaresult;
                    $data['amount'] = $amount;
                }
            }
        } else {
            $data['result'] = "";
            $data['header'] = "";
        }




        if ($query3->num_rows() > 0) {
            $r3 = $query3->row();
            $result3 = $query3->result();
//                   ECHO $this->db->last_query();
            $Sr_no_header = $r2->Sr_no_header;
            $sub_detail = $r2->sub_detail;
            $data['sub_detail'] = $sub_detail;
            $data['result3'] = $result3;
            $data['Sr_no_header'] = $Sr_no_header;
        }
        $data['prev_title'] = "Form-16";
        $data['page_title'] = "Form-16";

        $data['category'] = $this->form16_model->get_header()->result();
        //var_dump($data);
        $this->load->view('human_resource/form_16_creation', $data);
	}
	
	
	function form_16_submission(){
		 $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $emp_id = ($session_data['emp_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }
		 $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }
        //var_dump($this->session->userdata);
 $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }
		

        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $data['user_type'] = $record->user_type;
			$data['form_type'] = $record->form16Type;
        }

//        echo $this->db->last_query();
        $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$emp_id'");
        if ($query_sen_id->num_rows() > 0) {
            $res_sen_id = $query_sen_id->result();
            foreach ($res_sen_id as $row) {
                $user_id_emp = $row->user_id;
                $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$emp_id' AND status='1'");
                if ($query_find_leave->num_rows() > 0) {
                    $result_sen_id = $query_find_leave->row();
                    $leave_requested_on = $result_sen_id->leave_requested_on;
                    $leave_type = $result_sen_id->leave_type;
                    $response['leave'][] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
                } else {

                    $leave_requested_on = '';
                    $leave_type = '';
                    $response['leave'] = '';
                }
            }
            $data['leave_type'] = $response['leave'];
            $data['leave_requested_on'] = $response['leave'];
        } else {

        }

//        $query1 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (9) or header IN (9) ");,10,11,12,13,14,15,16,17,18,19,20 SELECT SUM(amount) AS value_sum FROM staff_form16b where header=9 and sub_detail=16
        $query2 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (1, 2, 3,4,5,6,7,8) or header IN (1, 2, 3,4,5,6,7,8) ");
//        echo $this->db->last_query();

        $query1 = $this->db->query("Select form_16b.header, form_16b.sub_detail,sum(staff_form16b.amount)as amount,staff_form16b.comment,staff_form16b.approval_status
from form_16b inner join staff_form16b on staff_form16b.sub_detail = form_16b.id where staff_form16b.staff_id = '$emp_id' group by header,sub_detail");
        //echo $this->db->last_query();
        $query3 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (10,11,12,13,14,15,16,17,18,19,20) or header IN (10,11,12,13,14,15,16,17,18,19,20) ");

        if ($query2->num_rows() > 0) {
            $r2 = $query2->row();
//            var_dump($r2);
            $results = $query2->result();

            $Sr_no_header = $r2->Sr_no_header;
            $sub_detail = $r2->sub_detail;
            $data['sub_detail'] = $sub_detail;
            $data['results'] = $results;
            $data['Sr_no_header'] = $Sr_no_header;
        }
        if ($query1->num_rows() > 0) {
            $r1 = $query1->row();
            $result = $query1->result();
            //var_dump($result);

            $header = $r1->header;
            $sub_detail = $r1->sub_detail;
            $array = array_values($result);
            $dummy_array = array();
            $data['result'] = $result;
            $data['header'] = $header;

//            print_r(array_unique($array));
//            var_dump($array);
//            $duplicates = array_filter($array);
// var_dump($duplicates);
            //var_dump($data['result'] = $result);
            $data['header'] = $header;
            $staffq = $this->db->query("select * from staff_form16b ");
            if ($staffq->num_rows() > 0) {
                $output = $staffq->row();
                $header1 = $output->header;
                $sd = $output->sub_detail;

                $q_Add = $this->db->query("SELECT SUM(amount) AS amount FROM staff_form16b where header='$header1' and sub_detail='$sd'");
//                echo $this->db->last_query();
                if ($q_Add->num_rows() > 0) {
                    $qaresult = $q_Add->result();
                    $op = $q_Add->row();
                    $amount = $op->amount;
                    $data['res'] = $qaresult;
                    $data['amount'] = $amount;
                }
            }
        } else {
            $data['result'] = "";
            $data['header'] = "";
        }




        if ($query3->num_rows() > 0) {
            $r3 = $query3->row();
            $result3 = $query3->result();
//                   ECHO $this->db->last_query();
            $Sr_no_header = $r2->Sr_no_header;
            $sub_detail = $r2->sub_detail;
            $data['sub_detail'] = $sub_detail;
            $data['result3'] = $result3;
            $data['Sr_no_header'] = $Sr_no_header;
        }
        $data['prev_title'] = "Form-16";
        $data['page_title'] = "Form-16";

        $data['category'] = $this->form16_model->get_header()->result();
        //var_dump($data);
        $this->load->view('human_resource/form_16_submission', $data);
	}
    function index() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $emp_id = ($session_data['emp_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }
		 $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }
        //var_dump($this->session->userdata);
 $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }


        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $data['user_type'] = $record->user_type;
        }

//        echo $this->db->last_query();
        $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$emp_id'");
        if ($query_sen_id->num_rows() > 0) {
            $res_sen_id = $query_sen_id->result();
            foreach ($res_sen_id as $row) {
                $user_id_emp = $row->user_id;
                $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$emp_id' AND status='1'");
                if ($query_find_leave->num_rows() > 0) {
                    $result_sen_id = $query_find_leave->row();
                    $leave_requested_on = $result_sen_id->leave_requested_on;
                    $leave_type = $result_sen_id->leave_type;
                    $response['leave'][] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
                } else {

                    $leave_requested_on = '';
                    $leave_type = '';
                    $response['leave'] = '';
                }
            }
            $data['leave_type'] = $response['leave'];
            $data['leave_requested_on'] = $response['leave'];
        } else {

        }

//        $query1 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (9) or header IN (9) ");,10,11,12,13,14,15,16,17,18,19,20 SELECT SUM(amount) AS value_sum FROM staff_form16b where header=9 and sub_detail=16
        $query2 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (1, 2, 3,4,5,6,7,8) or header IN (1, 2, 3,4,5,6,7,8) ");
//        echo $this->db->last_query();

        $query1 = $this->db->query("Select form_16b.header, form_16b.sub_detail,sum(staff_form16b.amount)as amount,staff_form16b.comment,staff_form16b.approval_status
from form_16b inner join staff_form16b on staff_form16b.sub_detail = form_16b.id where staff_form16b.staff_id = '$emp_id' group by header,sub_detail");
        //echo $this->db->last_query();
        $query3 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (10,11,12,13,14,15,16,17,18,19,20) or header IN (10,11,12,13,14,15,16,17,18,19,20) ");

        if ($query2->num_rows() > 0) {
            $r2 = $query2->row();
//            var_dump($r2);
            $results = $query2->result();

            $Sr_no_header = $r2->Sr_no_header;
            $sub_detail = $r2->sub_detail;
            $data['sub_detail'] = $sub_detail;
            $data['results'] = $results;
            $data['Sr_no_header'] = $Sr_no_header;
        }
        if ($query1->num_rows() > 0) {
            $r1 = $query1->row();
            $result = $query1->result();
            //var_dump($result);

            $header = $r1->header;
            $sub_detail = $r1->sub_detail;
            $array = array_values($result);
            $dummy_array = array();
            $data['result'] = $result;
            $data['header'] = $header;

//            print_r(array_unique($array));
//            var_dump($array);
//            $duplicates = array_filter($array);
// var_dump($duplicates);
            //var_dump($data['result'] = $result);
            $data['header'] = $header;
            $staffq = $this->db->query("select * from staff_form16b ");
            if ($staffq->num_rows() > 0) {
                $output = $staffq->row();
                $header1 = $output->header;
                $sd = $output->sub_detail;

                $q_Add = $this->db->query("SELECT SUM(amount) AS amount FROM staff_form16b where header='$header1' and sub_detail='$sd'");
//                echo $this->db->last_query();
                if ($q_Add->num_rows() > 0) {
                    $qaresult = $q_Add->result();
                    $op = $q_Add->row();
                    $amount = $op->amount;
                    $data['res'] = $qaresult;
                    $data['amount'] = $amount;
                }
            }
        } else {
            $data['result'] = "";
            $data['header'] = "";
        }




        if ($query3->num_rows() > 0) {
            $r3 = $query3->row();
            $result3 = $query3->result();
//                   ECHO $this->db->last_query();
            $Sr_no_header = $r2->Sr_no_header;
            $sub_detail = $r2->sub_detail;
            $data['sub_detail'] = $sub_detail;
            $data['result3'] = $result3;
            $data['Sr_no_header'] = $Sr_no_header;
        }
        $data['prev_title'] = "Form-16";
        $data['page_title'] = "Form-16";

        $data['category'] = $this->form16_model->get_header()->result();
        //var_dump($data);
        $this->load->view('human_resource/form16', $data);
    }
	
	public function form16input()
	{
		$data['prev_title'] = "Form-16 Input";
        $data['page_title'] = "Form-16 Input";
		 $this->load->view('human_resource/form16_inputs', $data);
	}
	public function form16new()
	{
		$result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $emp_id = ($session_data['emp_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }
		 $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }
        //var_dump($this->session->userdata);
 $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }


        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $data['user_type'] = $record->user_type;
        }

//        echo $this->db->last_query();
        $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$emp_id'");
        if ($query_sen_id->num_rows() > 0) {
            $res_sen_id = $query_sen_id->result();
            foreach ($res_sen_id as $row) {
                $user_id_emp = $row->user_id;
                $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$emp_id' AND status='1'");
                if ($query_find_leave->num_rows() > 0) {
                    $result_sen_id = $query_find_leave->row();
                    $leave_requested_on = $result_sen_id->leave_requested_on;
                    $leave_type = $result_sen_id->leave_type;
                    $response['leave'][] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
                } else {

                    $leave_requested_on = '';
                    $leave_type = '';
                    $response['leave'] = '';
                }
            }
            $data['leave_type'] = $response['leave'];
            $data['leave_requested_on'] = $response['leave'];
        } else {

        }

//        $query1 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (9) or header IN (9) ");,10,11,12,13,14,15,16,17,18,19,20 SELECT SUM(amount) AS value_sum FROM staff_form16b where header=9 and sub_detail=16
        $query2 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (1, 2, 3,4,5,6,7,8) or header IN (1, 2, 3,4,5,6,7,8) ");
//        echo $this->db->last_query();

        $query1 = $this->db->query("Select form_16b.header, form_16b.sub_detail,sum(staff_form16b.amount)as amount,staff_form16b.comment,staff_form16b.approval_status
from form_16b inner join staff_form16b on staff_form16b.sub_detail = form_16b.id where staff_form16b.staff_id = '$emp_id' group by header,sub_detail");
        //echo $this->db->last_query();
        $query3 = $this->db->query("Select * from `form_16b` where Sr_no_header IN (10,11,12,13,14,15,16,17,18,19,20) or header IN (10,11,12,13,14,15,16,17,18,19,20) ");

        if ($query2->num_rows() > 0) {
            $r2 = $query2->row();
//            var_dump($r2);
            $results = $query2->result();

            $Sr_no_header = $r2->Sr_no_header;
            $sub_detail = $r2->sub_detail;
            $data['sub_detail'] = $sub_detail;
            $data['results'] = $results;
            $data['Sr_no_header'] = $Sr_no_header;
        }
        if ($query1->num_rows() > 0) {
            $r1 = $query1->row();
            $result = $query1->result();
            //var_dump($result);

            $header = $r1->header;
            $sub_detail = $r1->sub_detail;
            $array = array_values($result);
            $dummy_array = array();
            $data['result'] = $result;
            $data['header'] = $header;

//            print_r(array_unique($array));
//            var_dump($array);
//            $duplicates = array_filter($array);
// var_dump($duplicates);
            //var_dump($data['result'] = $result);
            $data['header'] = $header;
            $staffq = $this->db->query("select * from staff_form16b ");
            if ($staffq->num_rows() > 0) {
                $output = $staffq->row();
                $header1 = $output->header;
                $sd = $output->sub_detail;

                $q_Add = $this->db->query("SELECT SUM(amount) AS amount FROM staff_form16b where header='$header1' and sub_detail='$sd'");
//                echo $this->db->last_query();
                if ($q_Add->num_rows() > 0) {
                    $qaresult = $q_Add->result();
                    $op = $q_Add->row();
                    $amount = $op->amount;
                    $data['res'] = $qaresult;
                    $data['amount'] = $amount;
                }
            }
        } else {
            $data['result'] = "";
            $data['header'] = "";
        }




        if ($query3->num_rows() > 0) {
            $r3 = $query3->row();
            $result3 = $query3->result();
//                   ECHO $this->db->last_query();
            $Sr_no_header = $r2->Sr_no_header;
            $sub_detail = $r2->sub_detail;
            $data['sub_detail'] = $sub_detail;
            $data['result3'] = $result3;
            $data['Sr_no_header'] = $Sr_no_header;
        }
        $data['prev_title'] = "Form-16";
        $data['page_title'] = "Form-16";

        $data['category'] = $this->form16_model->get_header()->result();
		 $this->load->view('human_resource/form16New', $data);
	}
	

    public function get_headers() {

        $query_get_header = $this->db->query("Select id,header  from `form_16b`");

        if ($query_get_header->num_rows() > 0) {
            $record = $query_get_header->result();
            $response['result'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    function get_detail() {
        $q_detail = $this->db->query("Select id,sub_detail from `form_16b`");
        if ($q_detail->num_rows() > 0) {
            $rec = $q_detail->result();
            $response['results'] = $rec;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['results'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    function add() {
        $plan_type = $this->input->post_get('plan_type');
        $header = $this->input->post_get('category');
        $sub_detail = $this->input->post_get('sub_category');
        $amount = $this->input->post_get('amount');
        $comment = $this->input->post_get('comment');
        $file_name = $this->upload_image();
       
        
        
        if ($file_name == NULL){
            $file_name='';
        }else{
            $file_name = $this->upload_image();
        }
        if(!empty($this->input->post_get('emp_id'))){
             $staff_id = $this->input->post_get('emp_id');
            
        }else{
           
             $session_data = $this->session->userdata('login_session');
              var_dump($session_data);
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $emp_id = ($session_data['emp_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        }
        $add_Data = array(
            "staff_id" => $staff_id, "plan_type" => $plan_type, "header" => $header, "sub_detail" => $sub_detail, "amount" => $amount, "comment" => $comment, "file_name" => $file_name, "approval_status" => 'pending'
        );
        if ($this->form16_model->add_user($add_Data)) {
            $response['empdata'] = $add_Data;
            $response["status"] = true;
            $response["body"] = "Insert Target";
        } else {
            $response['empdata'] = $data_controller;
            $response["status"] = false;
            $response["body"] = "Failed To Insert Target";
        }
        $response["query"] = $this->db->last_query();
        echo json_encode($response);
    }

    function get_sub_category() {
        $Sr_no_header = $this->input->post('Sr_no_header', TRUE);
        $data = $this->form16_model->get_sub_details($Sr_no_header)->result();
        echo json_encode($data);
    }

    public function view_form16_Details() {

        $user_id = $this->input->post_get("employee_id");

        $query = $this->db->query("Select Distinct form_16b.header, form_16b.sub_detail,staff_form16b.amount,staff_form16b.comment,staff_form16b.approval_status, staff_form16b.plan_type "
                . "from form_16b inner join staff_form16b on  staff_form16b.sub_detail = form_16b.id  where staff_form16b.staff_id  = '$user_id'");
        $this->db->last_query();
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width: 100%;" id="data_table" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <  <thead>
                                <tr>
                            <th>Header</th>
                            <th>Sub Details</th>
                            <th>Amount</th>
                            <th>Comment</th>
                            <th>Status</th>
                                </tr>
                            </thead>';
            $result1 = $query->result();
            foreach ($result1 as $row) {


                $data .= '<tr>
                    <td>' . $row->header . '</td>
                     <td>' . $row->sub_detail . '</td>
                    <td>' . $row->amount . '</td>
                    <td>' . $row->comment . '</td>
                    <td>' . $row->approval_status .' - '.$row->plan_type. '</td>
                    </tr>';
            }
            $data .= '  </table>';
            $response["status"] = 200;
            $response["result_data"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    public function upload_image() {
        $response = array();
//        var_dump($_FILES);
        if (isset($_FILES['file']) && $_FILES['file']['error'] != '4') :
            $files = $_FILES;
//            $count = count($_FILES['FileUpload1']['name']); // count element
//            for ($i = 0; $i < $count; $i++):
            $_FILES['file']['name'] = $files['file']['name'];
            $_FILES['file']['type'] = $files['file']['type'];
            $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
            $_FILES['file']['error'] = $files['file']['error'];
            $_FILES['file']['size'] = $files['file']['size'];
            $config['upload_path'] = './uploads/gallery/';
            $target_path = './uploads/gallery/';
            $config['allowed_types'] = 'mp4';
            $domainName = $_SERVER['HTTP_HOST'] . '/';
//                $config['allowed_types'] = 'pdf';
            $config['max_size'] = '1208925819614629174706176';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '800'; // image max width
            $config['max_height'] = '532';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = $_FILES['file']['name'];
            $base_url = base_url();
            $target_path = $base_url . 'uploads/gallery/' . $fileName;
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('video_file');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['file']['name'] . ' ' . $error['upload_error'];
                    $response = "invalid";
                    return $target_path;
                } else {
                    return $target_path;
                    // resize code
                    $path = $data['upload_data']['full_path'];
                    $q['name'] = $data['upload_data']['file_name'];
                    $configi['image_library'] = 'gd2';
                    $configi['source_image'] = $path;
                    $configi['new_image'] = $target_path;
                    $configi['create_thumb'] = TRUE;
                    $configi['maintain_ratio'] = TRUE;
                    $configi['width'] = 75; // new size
                    $configi['height'] = 50;
                    $this->load->library('image_lib');
                    $this->image_lib->clear();
                    $this->image_lib->initialize($configi);
                    $this->image_lib->resize();
                    $images[] = $fileName;
                }
            }
//            endfor;
        endif;
    }
    
    //Get Form16 details
public function form16_salary_details() {
        $employee_id = $this->input->post("employee_id");
        $year_id = $this->input->post("year_id");

        $get_salary_info = $this->db->query("select value from t_salarytype where user_id ='$employee_id'")->result();
        if ($this->db->affected_rows() > 0) {
            foreach ($get_salary_info as $row) {
                $salaayr[] = $row->value;
            }
        } else {
            $salaayr[] = 0;
        }
        $sala = array_sum($salaayr);


        $pst = date('m');
        if ($pst >= 4) {
            $y = date('Y');

            $dtt = $y . "-04-01";

            $pt = date('Y', strtotime('+1 year'));

            $ptt = $pt . "-03-31";
//            die;
        } else {
            $y = date('Y', strtotime('-1 year'));
            $dtt = $y . "-04-01";
            $pt = date('Y');
            $ptt = $pt . "-03-31";
        }

        $financial_year = $y . '-' . $pt;

        $dtt . ' ' . $ptt . "<br>";
        $date1 = strtotime($dtt);
        $date_past = strtotime($ptt);

        $date_current = date('yy-m-d');
        $date2 = strtotime($date_current);
        $date1 . "<br>" . $date2;





        $get_prequisite_info = $this->db->query("select * from t_form16 where user_id ='$employee_id' and fy_year='$year_id'")->result();
		if ($this->db->affected_rows() > 0) {
          $response['prequisite_info'] = $get_prequisite_info;
        }else{
			$get_prequisite_info=array();
			for($i=0;$i< 44;$i++)
			{
				$get_prequisite_info[$i]['value']=0;
				$get_prequisite_info[$i]['status']=0;
				$get_prequisite_info[$i]['file']=0;
				
			}
			$response['prequisite_info'] = $get_prequisite_info;
		}
//         echo $this->db->last_query();
        $get_year = $this->db->query("select fy_year from t_form16 where user_id ='$employee_id' and fy_year='$financial_year'")->row();
        if ($this->db->affected_rows() > 0) {
            $fyear = $get_year->fy_year;
        } else {
            $fyear = 0;
        }

        if ($fyear !== $financial_year) { 
            $years = '0';
            $diff = abs($date_past - $date1);
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

            $total_gross_sal = $sala * $months;
        } else {
            $years = '0';
            $diff = abs($date2 - $date1);
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

            if ($months == 0) {
                $total_gross_sal = $sala;
            } else {
                $total_gross_sal = $sala * $months;
            }
        } 
		$response['yearly_months'] = $months;
		$get_all_salaryDed_detail=$this->get_all_salaryDed_detail($year_id,$employee_id); //0=salary,1=pT,2=IT,3=PF
		$Gross_Salary=$get_all_salaryDed_detail[0];
		$Proffessional_tax=$get_all_salaryDed_detail[1];
		$Income_tax=$get_all_salaryDed_detail[2];
		$Provident_fund=$get_all_salaryDed_detail[3];
			$response['results'] = $Gross_Salary;
            $response['Proffessional_tax'] = $Proffessional_tax;
            $response['Income_tax'] = $Income_tax;
            $response['provident_value'] = $Provident_fund;

        $get_user_gender = $this->db->query("select gender from user_header_all where user_id ='$employee_id'")->row();
        $gender = $get_user_gender->gender;
        if ($gender == 1) {
            $genderdetail = 'Female';
        } else {
            $genderdetail = 'Male';
        }

        $t_form16_standardfigure = $this->db->query("select * from t_form16_standardfigure where type ='$gender'")->result();

        if ($this->db->affected_rows() > 0) {
           $response['form_16_configure'] = $t_form16_standardfigure;
        }else{
			$t_form16_standardfigure=array();
			for($i=0;$i< 44;$i++)
			{
				$response['form_16_configure'] = 0; 
			}
		}
	//get paid IT
	$yearExp=explode("-",$year_id);
	$year1=$yearExp[0];
	 $year2=$yearExp[1];
	 $curr_year=date('Y');
	 $curr_month=date('n');
	 $prevIT=0;
	 if($year1 == $curr_year || $year2 == $curr_year )
	 {
		 
		 $month_array=array(4,5,6,7,8,9,10,11,12,1,2,3);
		$mn_num=array_search($curr_month, $month_array);
		$prev_months_arr=array_slice($month_array,0, $mn_num);
		$month = "";
		for($k=0;$k< (count($prev_months_arr)-1);$k++)
		{
			$month .= $prev_months_arr[$k] . ",";
		}
			 $month=rtrim($month,",");
			if($month != "")
			{
				 $queryq=$this->db->query("select std_amt from t_salary_staff where user_id='$employee_id'  AND salary_type='Income Tax'  AND month IN($month) AND year IN($year1,$year2)")->result();
			
			if($this->db->affected_rows() > 0)
			{
				foreach($queryq as $row)
				{
				$prevIT +=$row->std_amt;
				}
			}else{
				$prevIT +=0;
			}
			}else{
				$prevIT +=0;
			}
			
		 
	 }else{
		 $queryq=$this->db->query("select std_amt from t_salary_staff where user_id='$employee_id'  AND salary_type='Income Tax' AND year='$year2' AND month IN(1,2,3)")->result();
			
			if($this->db->affected_rows() > 0)
			{
				foreach($queryq as $row)
				{
				$prevIT +=$row->std_amt;
				}
			}else{
				$prevIT +=0;
			}
			
			$queryqq=$this->db->query("select  std_amt from t_salary_staff where user_id='$employee_id'  AND salary_type='Income Tax' AND year='$year1' AND month NOT IN(1,2,3)")->result();
			
			if($this->db->affected_rows() > 0)
			{
			
				foreach($queryqq as $row)
				{
				$prevIT +=$row->std_amt;
				}
			}else{
				$prevIT +=0;
			}
	 }
		
		$response['paid_tax'] = round($prevIT);
        $provident_value = $this->db->query("select value from t_standarddeductions where deduction_id='2' and user_id='$employee_id'")->row();
		if ($this->db->affected_rows() > 0) {
          
        }else{
			$provident_value=0;
		}
        $get_deduction_info = $this->db->query("select value,deduction_id,"
                        . "(select deduction from m_standarddeductions where t_standarddeductions.deduction_id=m_standarddeductions.deduction_id) as dedcution_name"
                        . " from t_standarddeductions where user_id ='$employee_id' and deduction_id!='2'")->result();
		
        if ($this->db->affected_rows() > 0) {
            foreach ($get_deduction_info as $rw) {
                $deduction_amount_val[] = $rw->value;
                $deduction_amount_name[] = $rw->dedcution_name;
            }
			 $response['deduction_name'] = $deduction_amount_name;
            $response['deduction_amount'] = $deduction_amount_val;
        } else {
            $deduction_amount_val[] = 0;
            $deduction_amount_name[] = '';
			 $response['deduction_name'] = $deduction_amount_name;
            $response['deduction_amount'] = $deduction_amount_val;
        }

        if ($this->db->affected_rows() > 0) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }
	
	public function get_all_salaryDed_detail($year_id,$userid)
	{
		$explodeYear=explode('-',$year_id);
		$year1=$explodeYear[0];
		$year2=$explodeYear[1];
		$get_user_gender = $this->db->query("select date_of_joining from user_header_all where user_id ='$userid'")->row();
        $date_of_joining = $get_user_gender->date_of_joining;
		$time=strtotime($date_of_joining);
		$Joinmonth=date("m",$time);
		$Joinyear=date("Y",$time);
		$Currmonth=date("n");
		$Curryear=date("Y");
		$salary=0;
		$proffesion_tax=0;
		$Income_tax=0;
		$Provident_fund=0;
			//Gross Salary
			$query_get_curr_month_salary=$this->db->query("select sum(value)as monthsal from t_salarytype where user_id='$userid'")->row();
		
			if($this->db->affected_rows() > 0)
			{
				$Gross_sal=$query_get_curr_month_salary->monthsal;
			}else{
				$Gross_sal=0;
			}
			//Get Gross pF
			$querya=$this->db->query("select * from t_standarddeductions where user_id='$userid' AND deduction_id='2'")->row();
			if($this->db->affected_rows() > 0)
			{
				$value=$querya->value;
					if( $querya->deduction_type == 2)
					{
						
						$PF_val = ($Gross_Salary*$value)/100;
					}else{
						$PF_val = $value;
					}
			}else{
				$PF_val=0;
			}
			
			$querya1=$this->db->query("select * from t_standarddeductions where user_id='$userid' AND deduction_id='1'")->row();
			if($this->db->affected_rows() > 0)
			{
				$value=$querya1->value;
					if( $querya1->deduction_type == 2)
					{
						
						$PT_val = ($Gross_Salary*$value)/100;
					}else{
						$PT_val = $value;
					}
			}else{
				$PF_val=0;
				$PT_val =0;
			}
		
		$month_array=array(4,5,6,7,8,9,10,11,12,1,2,3);
		
		
		if($year1 == $Curryear || $year2 == $Curryear){ 
			$mn_num=array_search($Currmonth, $month_array);
			$prev_months_arr=array_slice($month_array,0, $mn_num);
			
			$next_months_arr=array_slice($month_array, $mn_num + 1);
			$query_get_curr_month_salary1=$this->db->query("select sum(amt)as monthsal from t_salary_staff where user_id='$userid' AND month='$Currmonth' AND category IN(1,3) AND year='$Curryear'")->row();
			
			if($query_get_curr_month_salary1->monthsal > 0)
			{
				$CurrMonthSal=$query_get_curr_month_salary1->monthsal;
				$currPT =$this->getPT($Currmonth,$Curryear,$userid);
				$proffesion_tax =$currPT;
				//$Income_tax +=$this->getIT($Currmonth,$Curryear,$userid);
				 $Provident_fund +=$this->getPF($Currmonth,$Curryear,$userid);
			}else{
				 $CurrMonthSal=$Gross_sal;
				$currPT =	$PT_val;
				$proffesion_tax =$currPT;
				//$Income_tax +=$this->getIT($Currmonth,$Curryear,$userid);
				 $Provident_fund +=$PF_val;				 
			}
				$salary += $CurrMonthSal;
				
			for($j=0;$j< count($prev_months_arr);$j++)
			{
				if($prev_months_arr[$j] == 1 || $prev_months_arr[$j] == 2 || $prev_months_arr[$j] == 3)
				{
					$year_slect=$year2;
				}else{
					$year_slect=$year1;
				}
				$salary +=$this->getPreviousSalary($prev_months_arr[$j],$year_slect,$userid);
				$proffesion_tax +=$this->getPT($prev_months_arr[$j],$year_slect,$userid);
				//$Income_tax +=$this->getIT($j,$year1,$userid);
				$Provident_fund +=$this->getPF($prev_months_arr[$j],$year_slect,$userid);
			}
			  $salary += count($next_months_arr)*$Gross_sal;
			 $proffesion_tax += count($next_months_arr)*$currPT;
			 $Provident_fund += count($next_months_arr)*$PF_val;
			}elseif(($year1 != $Curryear || $year2 != $Curryear)&&($Joinyear == $year1 || $Joinyear == $year2)){	
			$mn_num=array_search($Joinmonth, $month_array);
			$next_months_arr=array_slice($month_array, $mn_num + 1);
			$Join_monthsal=$this->db->query("select sum(amt)as monthsal from t_salary_staff where user_id='$userid' AND month='$Joinmonth' AND category IN(1,3) AND year='$Joinyear'")->row();
			if($this->db->affected_rows() > 0)
			{
				$JoinMonthSal=$Join_monthsal->monthsal;
				
			}else{
				$JoinMonthSal=0; 
			}
			
			for($j=0;$j< count($next_months_arr);$j++)
			{
			
				if($next_months_arr[$j] == 1 || $next_months_arr[$j] == 2 || $next_months_arr[$j] == 3)
				{
					$year_slect=$year2;
				}else{
					$year_slect=$year1;
				}
				$salary +=$this->getPreviousSalary($next_months_arr[$j],$year_slect,$userid);
				$proffesion_tax +=$this->getPT($next_months_arr[$j],$year_slect,$userid);
				//$Income_tax +=$this->getIT($j,$year1,$userid);
				$Provident_fund +=$this->getPF($next_months_arr[$j],$year_slect,$userid);
			}
			
			
			}else{
				for($j=0;$j< count($month_array);$j++)
				{
				if($month_array[$j] == 1 || $month_array[$j] == 2 || $month_array[$j] == 3)
				{
					$year_slect=$year2;
				}else{
					$year_slect=$year1;
				}
				$salary +=$this->getPreviousSalary($month_array[$j],$year_slect,$userid);
				$proffesion_tax +=$this->getPT($month_array[$j],$year_slect,$userid);
				//$Income_tax +=$this->getIT($j,$year1,$userid);
				$Provident_fund +=$this->getPF($month_array[$j],$year_slect,$userid);
				}
			}
		
			$array[]=round($salary);
			$array[]=round($proffesion_tax);
			$array[]=round($Income_tax);
			$array[]=round($Provident_fund);
			return $array;
			
	}
	public function getPF($i,$year_slect,$userid)
	{
		$query=$this->db->query("select amt from t_salary_staff where user_id='$userid' AND month='$i'AND year='$year_slect' AND salary_type='Provident Fund'")->row();
		if($this->db->affected_rows() > 0)
		{
			$PF =$query->amt;
		}else{
			$PF=0;
		}
		return $PF;
	}
	public function getIT($i,$year_slect,$userid)
	{
		$query=$this->db->query("select amt from t_salary_staff where user_id='$userid' AND month='$i'AND year='$year_slect' AND salary_type='Income Tax'")->row();
		if($this->db->affected_rows() > 0)
		{
			$IT =$query->amt;
		}else{
			$IT=0;
		}
		return $IT;
	}
	public function getPT($i,$year_slect,$userid)
	{
		$query=$this->db->query("select amt from t_salary_staff where user_id='$userid' AND month='$i'AND year='$year_slect' AND salary_type='Professional Tax'")->row();
		if($this->db->affected_rows() > 0)
		{
			$PT =$query->amt;
		}else{
			$PT=0;
		}
		return $PT;
	}
	
	public function getPreviousSalary($prev_months,$year_slect,$userid)
	{
		$query_get_curr_month_salary=$this->db->query("select amt from t_salary_staff where user_id='$userid' AND month='$prev_months'AND year='$year_slect' AND category IN(1,3)")->result();
		
		if($this->db->affected_rows() > 0)
		{
			
			$Salary=0;
			foreach($query_get_curr_month_salary as $row)
			{
				$Salary +=$row->amt;
			}
		}else{
			$Salary=0;
		}
		return $Salary;
	}
	public function addform16() //done by pooja
	{
		
		$result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
		$user_id = ($session_data['emp_id']);
		 $year = $this->input->post("select_year"); 
      $check_data=$this->check_data_form_16($user_id,$year);
	  if($check_data == true || $check_data ==1)
	  {
		$val1 = $this->input->post("gross2");
		$val_text1 = "Grossnum2";
		$val2 = $this->input->post("gross3");
		$val_text2 = "Grossnum3";
		$val3 = $this->input->post("la_text1");
		$val_text3 = "La_text1";
		$val4 = $this->input->post("la_text2");
		$val_text4 = "La_text2";
		$val5 = $this->input->post("la_num1");
		$val_text5 = "La_num1";
		$val6 = $this->input->post("la_num2");
		$val_text6 = "La_num2";
		$val7 = $this->input->post("add_inc");
		$val_text7 = "Add_inc";
		$val8 = $this->input->post("less_inc");
		$val_text8 = "Less_inc";
		$val9 = $this->input->post("ded1");
		$val_text9 = "Ded1";
		$val10 = $this->input->post("ded2");
		$val_text10 = "Ded2";
		$val11 = $this->input->post("gmt_text1");
		$val_text11 = "gmt_text1";
		$val12 = $this->input->post("gamt1");
		$val_text12 = "gamt1";
		$val13 = $this->input->post("gamt2");
		$val_text13 = "gamt2";
		$val14 = $this->input->post("gamt3");
		$val_text14 = "gamt3";
		$val15 = $this->input->post("gmt_text2");
		$val_text15 = "gmt_text2";
		$val16 = $this->input->post("gamt21");
		$val_text16 = "gamt21";
		$val17 = $this->input->post("gamt22");
		$val_text17 = "gamt22";
		$val18 = $this->input->post("gmat23");
		$val_text18 = "gmat23";
		$val19 = $this->input->post("gmt_text3");
		$val_text19 = "gmt_text3";
		$val20 = $this->input->post("gamt31");
		$val_text20 = "gamt31";
		$val21 = $this->input->post("gamt32");
		$val_text21 = "gamt32";
		$val22 = $this->input->post("gamt33");
		$val_text22 = "gamt33";
		$val23 = $this->input->post("sec21");
		$val_text23 = "sec21";
		$val24 = $this->input->post("sec22");
		$val_text24 = "sec22";
		$val25 = $this->input->post("sec23");
		$val_text25 = "sec23";
		$val26 = $this->input->post("sec31");
		$val_text26 = "sec31";
		$val27 = $this->input->post("sec32");
		$val_text27 = "sec32";
		$val28 = $this->input->post("sec33");
		$val_text28 = "sec33";
		$val29 = $this->input->post("secbt1");
		$val_text29 = "secbt1";
		$val30 = $this->input->post("secb11");
		$val_text30 = "secb11";
		$val31 = $this->input->post("secb12");
		$val_text31 = "secb12";
		$val32 = $this->input->post("secb13");
		$val_text32 = "secb13";
		$val33 = $this->input->post("secbt2");
		$val_text33 = "secbt2";
		$val34 = $this->input->post("secb21");
		$val_text34 = "secb21";
		$val35 = $this->input->post("secb22");
		$val_text35 = "secb22";
		$val36 = $this->input->post("secb23");
		$val_text36 = "secb23";
		$val37 = $this->input->post("secbt3");
		$val_text37 = "secbt3";
		$val38 = $this->input->post("secb31");
		$val_text38 = "secb31";
		$val39 = $this->input->post("secb32");
		$val_text39 = "secb32";
		$val40 = $this->input->post("secb33");
		$val_text40 = "secb33";
		$val41 = $this->input->post("sec41");
		$val_text41 = "sec41";
		$val42 = $this->input->post("sec42");
		$val_text42 = "sec42";
		$val43 = $this->input->post("sec17");
		$val_text43 = "sec17";
		$val44 = $this->input->post("sec19");
		$val_text44 = "sec19";
		$cn=1;
		
		
		$file_path = "uploads/";
		$file1= $this->upload_file($file_path,'myfile1');
		$file2= $this->upload_file($file_path,'myfile2');
		$file3= $this->upload_file($file_path,'myfile3');
		$file4= $this->upload_file($file_path,'myfile4');
		$file5= $this->upload_file($file_path,'myfile5');
		$file6= $this->upload_file($file_path,'myfile6');
		$file7= $this->upload_file($file_path,'myfile7');
		$file8= $this->upload_file($file_path,'myfile8');
		$file9= $this->upload_file($file_path,'myfile9');
		$file10= $file9;
		$file11= $file9;
		$file12= $file9;
		$file13= $this->upload_file($file_path,'myfile10');
		$file14= $file13;
		$file15= $file13;
		$file16= $file13;
		$file17= $this->upload_file($file_path,'myfile11');
		$file18= $file17;
		$file19= $file17;
		$file20= $file17;
		$file21= $this->upload_file($file_path,'myfile12');
		$file22= $file21;
		$file23= $file21;
		$file24= $file21;
		$file25= $this->upload_file($file_path,'myfile13');
		$file26= $file25;
		$file27= $file25;
		$file28= $file25;
		$file29= $this->upload_file($file_path,'myfile14');
		$file30= $file29;
		$file31= $file29;
		$file32= $file29;
		$file33= $this->upload_file($file_path,'myfile15');
		$file34= $file33;
		$file35= $file33;
		$file36= $file33;
		$file37= $this->upload_file($file_path,'myfile16');
		$file38= $file37;
		$file39= $file37;
		$file40= $file37;
		$file41= $this->upload_file($file_path,'myfile17');
		$file42= $file41;
		$file43= $this->upload_file($file_path,'myfile18');
		$file44= $this->upload_file($file_path,'myfile19');
		
		for($i=1;$i<=44;$i++)
		{
			
		$file=${'file' . $i};
		if ($file["status"] == 200) {
            $file_path .= $file["body"];
            $fileDetails = $file_path;
        } else {

            $fileDetails = '';
        }
			if(!isset(${'val' . $i}) || is_null(${'val' . $i})|| empty(${'val' . $i}))
				{
					$val='';
				}else{
					$val=${'val' . $i};
				}
			$data=array(
			'user_id'=>$user_id,
			'firm_id'=>$firm_id,
			'text'=>${'val_text' . $i},
			'value'=>$val,
			'file'=>$fileDetails,
			'fy_year'=>$year,
			'status'=>0,
		);
		$data_insert=$this->db->insert('t_form16',$data);
		if($data_insert == true)
		{
			$cn++;
		}
		}
		if($cn >1)
		{
		  $response['message'] = 'success';
		  $response['body'] = 'Data uploaded Successfully';
            
        } else {
            $response['message'] = 'No data to display';
			$response['body'] = 'Somthing went wrong';
        }
	  }else{
		 $response['message'] = 'No data to display';
			$response['body'] = 'Something went wrong'; 
	  }echo json_encode($response);
				
	}
	
	public function check_data_form_16($user_id,$year) //done by pooja
	{
		$query=$this->db->delete('t_form16', array('user_id' => $user_id,'fy_year'=> $year)); 
		if($query == true)
		{
			return true;
		}else{
			return 1;
		}
		 
	}
	
	public function get_pf()
	{
		$user_id = $this->input->post("user_id");
		$qr=$this->db->query("select value from t_standarddeductions where deduction_id='2' and user_id='$user_id'");
		if($this->db->affected_rows() >0)
		{
			$res=$qr->row();
			$response['text']="Providant Fund";
			$response['status']=200;
			$response['value']=$res->value;
		}else{
			$response['text']="";
			$response['value']="";
			$response['status']=204;
		}echo json_encode($response);

	}
	public function get_status()
	{ 
		$user_id = $this->input->post("user_id");
		$year_id = $this->input->post("year_id");
		
		$qr=$this->db->query("select status,file from t_form16 where user_id='$user_id' AND fy_year='$year_id'");
		$result='';
		if($this->db->affected_rows() >0)
		{
			$result=$qr->result();
			$response['code']=200;
			$response['result1']=$result;
		}else{
			$response['result1']=$result;
			$response['code']=204;
		}echo json_encode($response);

	}	
	
	function upload_file($upload_path,$id_name) {
        $img_id = strtotime(date("Y-m-d h:i:sa"));
        if (isset($_FILES[$id_name]) && $_FILES[$id_name]['error'] != '4') {
            $files = $_FILES;
            if (is_array($_FILES[$id_name]['name'])) {
                $count = count($_FILES[$id_name]['name']); // count element
            } else {
                $count = 1;
            }
            $_FILES[$id_name]['name'] = $img_id . $files[$id_name]['name'];

            $_FILES[$id_name]['type'] = $files[$id_name]['type'];
            $_FILES[$id_name]['tmp_name'] = $files[$id_name]['tmp_name'];
            $_FILES[$id_name]['error'] = $files[$id_name]['error'];
            $_FILES[$id_name]['size'] = $files[$id_name]['size'];
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = '*';
            $config['max_size'] = '500000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = preg_replace('/\s+/', '', str_replace(' ', '', $_FILES[$id_name]['name']));
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                $response['status'] = 203;
                $response['body'] = "file is empty";
                return false;
            } else {
                $file = $this->upload->do_upload($id_name);

                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['status'] = 203;
                    $response['body'] = $files[$id_name]['name'] . ' ' . $error['upload_error'];
                } else {
                    $response['status'] = 200;
                    $response['body'] = $fileName;
                }
                return $response;
            }
        }
    }
	
	public function get_employee()
	{
		$session_data = $this->session->userdata('login_session');
		//$data['session_data'] = $session_data;
		$emp_id = ($session_data['emp_id']);
		$query=$this->db->query("select hr_authority from user_header_all where user_id='$emp_id'")->row();
		if($this->db->affected_rows() > 0)
		{
			$option='<option value="">Select Employee</option>';
			$firm_id=$query->hr_authority;
			$query_emp=$this->db->query("select user_id,user_name from user_header_all where firm_id='$firm_id' and user_type='4' and activity_status='1'")->result();
			if(count($query_emp)>0)
			{
				foreach($query_emp  as $row)
				{
					$option .= '<option value="'.$row->user_id.'">'.$row->user_name.'</option>';
				}
			}else{
				$option='';
			}
			
			$response['status']=200;
			$response['option']=$option;
		}else{
			
			$response['option']=$option;
			$response['status']=204;
		}echo json_encode($response);
	}
	
	public function accept_file()
	{
		$file = $this->input->post("file");
	
		$update_qr=$this->db->query("update t_form16 set status='1' where file='$file'");
		if($this->db->affected_rows() > 0)
		{
			$response['status']=200;
		}else{
			$response['status']=204;
		}echo json_encode($response);
	}
	public function get_datastandard()
	{
		$session_data = $this->session->userdata('login_session');
		$userid = ($session_data['emp_id']);
		
		$get_user_gender = $this->db->query("select gender from user_header_all where user_id ='$userid'")->row();
        $gender = $get_user_gender->gender;
        if ($gender == 1) {
			$type=2;
            
        } else {
            $type=1;
        }
		$yearly_sal = $this->input->post("total_income");
		 if($yearly_sal < 0)
		 {
			 $response['status']=200;
			 $response['result_tax']=0;
		 }else{
			$query=$this->db->query("select * from t_form16_standardfigure where type='$type'")->result();
		
			$response=array();
			foreach($query as $row)
			{
				$max_value=$row->max_value;
				$min_value=$row->min_value;
				if($yearly_sal <= $max_value && $yearly_sal > $min_value)
				{
					$response['status']=200;
					$tax_percent=$row->tax_percent;
					$charges=$row->charges;
					$yearly_tax=(($yearly_sal-$min_value)*($tax_percent/100))+$charges;
					$response['result_tax']=round($yearly_tax);
				}
			} 
		 }
		echo json_encode($response);
	}
	
	public function get_taxes()
	{
		$userid = $this->input->post('emp_id');
		
		$get_user_gender = $this->db->query("select gender from user_header_all where user_id ='$userid'")->row();
        $gender = $get_user_gender->gender;
        if ($gender == 1) {
			$type=2;
            
        } else {
            $type=1;
        }
		$query=$this->db->query("select * from t_form16_standardfigure where type='$type'")->result();
		$data="";
		if(count($query) > 0)
		{
			foreach($query as $row)
			{
				$max_value=$row->max_value;
				$min_value=$row->min_value;
				$tax_percent=$row->tax_percent;
				$charges=$row->charges;
				
			$data .="<tr>
			<td>$min_value-$max_value</td>
			<td>((yearly salary - $min_value)*($tax_percent%))+$charges</td>
			</tr>";	
			}
		$response['status']=200;
		$response['data']=$data;
		}else{
			$response['status']=204;
		}echo json_encode($response);
	}
	
	public function addHeads(){
		$form_id=$this->input->post("form_id");
		$headNum=$this->input->post("headNum");
		$headName=$this->input->post("headName");
		$fixed_value=$this->input->post("fixed_value");
		$head_type=$this->input->post("head_type");
		$field_type=$this->input->post("field_type");
		$form_id=$this->input->post("form_id");
		
		$query=$this->db->query("select * from form16Heads where head_num='$headNum' OR head_name='$headName' AND type='$form_id' ");
		if($this->db->affected_rows() > 0){
				$response['status']=202;
				$response['body']="This Head Name Or Head Number is already Exists.";
				echo json_encode($response);
				exit;
		}else{
			$data=array(
			"head_num"=>$headNum,
			"head_name"=>$headName,
			"type"=>$form_id,
			"field_type"=>$field_type,
			"head_type"=>$head_type,
			"fixed_value"=>$fixed_value,
			"form_id"=>$form_id,
			);
			$insert=$this->db->insert("form16Heads",$data);
			if($insert == true){
				$response['status']=200;
				$response['body']="Added Successfully.";
			}else{
				$response['status']=202;
				$response['body']="Fail To Add.";
			}
		}echo json_encode($response);
	}
	public function addSubHeads(){
		$head_id=$this->input->post("head_id");
		
		$SubheadName=$this->input->post("SubheadName");
		$Subhead_type=$this->input->post("Subhead_type");
		$SubheadNum=$this->input->post("SubheadNum");
		$fixed_value=$this->input->post("Sfixed_value");
		
		$query=$this->db->query("select * from form16Sub_Heads where sub_head_num='$SubheadNum' 
		AND sub_head_name='$SubheadName' AND head_id='$head_id' ");
		if($this->db->affected_rows() > 0){
				$response['status']=202;
				$response['body']="This Sub Head Name Or Sub Head Number is already Exists.";
				echo json_encode($response);
				exit;
		}else{
			$data=array(
			"sub_head_num"=>$SubheadNum,
			"sub_head_name"=>$SubheadName,
			"head_id"=>$head_id,
			"head_type"=>$Subhead_type,
			"fixed_value"=>$fixed_value,
			
			);
			$insert=$this->db->insert("form16Sub_Heads",$data);
			if($insert == true){
				$response['status']=200;
				$response['body']="Added Successfully.";
			}else{
				$response['status']=202;
				$response['body']="Fail To Add.";
			}
		}
	}
	
	public function getAllHeads(){
		$form_id=$this->input->post("form_id");
	//	echo "select * from form16Heads where form_id='$form_id' AND field_type='2'";
		$query=$this->db->query("select * from form16Heads where form_id='$form_id' AND field_type='2'");
$data="<option value=''>Select Head</option>";	
	if($this->db->affected_rows()>0){
			$result=$query->result();
			
			foreach($result as $row){
				$data .="<option value='".$row->id."'>".$row->head_name."</option>";
			}
			$response['data']=$data;
			$response['status']=200;
		}else{
			$response['data']=$data;
			$response['status']=201;
			
		}echo json_encode($response);
	}
	
	public function get_form16(){
		$form_id=$this->input->post("form_id");
		$query=$this->db->query("select * from form16Heads where form_id='$form_id'");
		$data="<br><br><br><br><br><br><table class='table table-bordered ' ><thead><tr>
		<th>Text</th>
		<th>RS</th>
		<th>RS</th>
		</tr></thead><tbody>";
		if($this->db->affected_rows() > 0){
			$result=$query->result();
			foreach($result as $row){
				$field_type=$row->field_type;
				$id=$row->id;
				$data .="<tr>
				<td><b>".$row->head_num .". ".$row->head_name."</b></td>
				<td></td>
				<td></td></tr>
				";
				if($field_type == 2){
				$query1=$this->db->query("select * from form16Sub_Heads where head_id='$id'");	
				if($this->db->affected_rows() > 0){
					$res1=$query1->result();
					foreach($res1 as $row1){
						$data .="<tr>
				<td>".$row1->sub_head_num .". ".$row1->sub_head_name."</td>
				<td></td>
				<td></td></tr>";
					}
				}
				}else{
					
				}
				
			}
			$data.="</tbody></table>";
		$response['data']=$data;
			$response['status']=200;
		}else{
			$response['data']=$data;
			$response['status']=201;
			
		}echo json_encode($response);
	}
	public function get_form16Submission(){
		$form_id=$this->input->post("form_id");
		$session_data = $this->session->userdata('login_session');
		$userid = ($session_data['emp_id']);
		$query=$this->db->query("select * from form16Heads where form_id='$form_id'");
		$data="<br><br><br><br><br><br><table class='table table-bordered ' ><thead><tr>
		<th>Text</th>
		<th>RS</th>
		<th>File/Status</th>
		</tr></thead><tbody>";
		if($this->db->affected_rows() > 0){
			$result=$query->result();
			$k=1;
			$j=1;
			$year_id='2020-2021';
			$get_all_salaryDed_detail=$this->get_all_salaryDed_detail($year_id,$userid); //0=salary,1=pT,2=IT,3=PF
			$Gross_Salary=$get_all_salaryDed_detail[0];
			$Proffessional_tax=$get_all_salaryDed_detail[1];
			$Income_tax=$get_all_salaryDed_detail[2];
			$Provident_fund=$get_all_salaryDed_detail[3];
			
				
			foreach($result as $row){
				$field_type=$row->field_type;
				$input1='';
				$inputfile1='';
				//get input value
				$value_head='';
				$file='';
				$qr=$this->db->query("select * from Form16TransactionDetails where head_id='$row->id' AND user_id='$userid' AND form_id='$form_id'");
				if($this->db->affected_rows() > 0){
					$result=$qr->row();
					$value_head=$result->value;
					$file=$result->file;
				}
				if($field_type==1 && $row->head_type != '1'){
							$input1="<input type='text' id='head_value".$k."' name='head_value".$k."' value='".$value_head."'class='form-control' placeholder='Enter Amount'/>
							<input type='hidden' id='Fhead_id".$k."' name='Fhead_id".$k."' value='".$row->id."' class='form-control' placeholder='Enter Amount'/>
							<input type='hidden' id='headold_file".$k."' name='headold_file".$k."' value='".$file."' class='form-control' placeholder='Enter Amount'/>
							";
							
						}else{
							$input1='';
						}
			if($field_type==1 && $row->head_type != '1'){
							$inputfile1="<input type='file' id='head_file".$k."' name='head_file".$k."'class='form-control' placeholder='Enter Amount'/>
							
							";
							
						}else{
							$inputfile1="";
						}
				$id=$row->id;
				$data .="<tr>
				<td><b>".$row->head_num .". ".$row->head_name."</b></td>
				<td>".$input1."</td>
				<td>".$inputfile1."</td>
				
				</tr>
				";
				if($field_type == 2){
				$query1=$this->db->query("select * from form16Sub_Heads where head_id='$id'");	
				if($this->db->affected_rows() > 0){
					$res1=$query1->result();
					$input='';
					$inputfile='';
					foreach($res1 as $row1){
						$qr1=$this->db->query("select * from Form16TransactionDetails where subhead_id='$row1->id' AND user_id='$userid' AND form_id='$form_id'");
				if($this->db->affected_rows() > 0){
					$result1=$qr1->row();
					$value_sub_head=$result1->value;
					$sub_headfile=$result1->file;
				}else{
					$value_sub_head="";
					$sub_headfile="";
				}
				$id2=$row1->sub_head_num;
				
			
							
						if($row1->head_type != 1 && $row1->head_type != 3){
							$inputfile="<input type='file' id='Subhead_file".$j."' name='Subhead_file".$j."'class='form-control' placeholder='Enter Amount'/>";
							$input="<input type='text' value='".$value_sub_head."'id='Subhead_value".$j."' name='Subhead_value".$j."' class='form-control' placeholder='Enter Amount'/>
							<input type='hidden' id='head_id".$j."' name='head_id".$j."' value='".$row1->head_id."' class='form-control' placeholder='Enter Amount'/>
							<input type='hidden' id='subhead_id".$j."' name='subhead_id".$j."' value='".$row1->id."' class='form-control' placeholder='Enter Amount'/>
							<input type='hidden' id='subheadold_file".$j."' name='subheadold_file".$j."' value='".$sub_headfile."' class='form-control' placeholder='Enter Amount'/>
							";
						}else if($row1->head_type == 3){
							$inputfile="<input type='file' id='Subhead_file".$j."' name='Subhead_file".$j."'class='form-control' placeholder='Enter Amount'/>";
							$input="<input type='text' value='".$row1->fixed_value."'id='Subhead_value".$j."' name='Subhead_value".$j."' class='form-control' placeholder='Enter Amount' readonly/>";
						}else{
							$input="";
							$inputfile="";
						}
							
						
						$data .="<tr>
				<td>".$row1->sub_head_num .". ".$row1->sub_head_name."</td>
				<td>".$input."</td>
				<td>".$inputfile."</td>
				</tr>";
				$j++;
					}
				}
				}else{
					
				}
			$k++;	
			}
			$data.="</tbody></table>";
			$data.="
			<input type='hidden' name='countHead' id='countHead' value='".$k."'>
			<input type='hidden' name='countSubHead' id='countSubHead' value='".$j."'>
			";
		
		$response['data']=$data;
			$response['status']=200;
		}else{
			$response['data']=$data;
			$response['status']=201;
			
		}echo json_encode($response);
	}
	
	function Save_Form16(){
		$session_data = $this->session->userdata('login_session');
		$userid = ($session_data['emp_id']);
		$countHead=$this->input->post('countHead');
		$select_year=$this->input->post('select_year');
		$form_id=$this->input->post('Vform_id');
		$countSubHead=$this->input->post('countSubHead'); 
		$where=array("form_id"=>$form_id,"user_id"=>$userid);
		$delete=$this->db->delete("Form16TransactionDetails",$where);
		$a=0;
		 for($i=1;$i<$countHead;$i++){
			//head_value
			//Fhead_id
			//head_file
			$head_value=$this->input->post('head_value'.$i);
			$Fhead_id=$this->input->post('Fhead_id'.$i);
			$headold_file=$this->input->post('headold_file'.$i);
			$head_file=('head_file'.$i);
			$file=$this->upload_multiple_file('uploads/',$head_file);
			
			if($file['status'] == 200 && $file['body'] != ""){
				$fileU='uploads/'.$file['body'];
			}else{
				$fileU="";
			}
			
			if($fileU="" && $headold_file != ""){
				$fileU=$headold_file;
			}else{
				$fileU=$fileU;
			}
			$data=array(
			"form_id"=>$form_id,
			"user_id"=>$userid,
			"head_id"=>$Fhead_id,
			"year_id"=>$select_year,
			"value"=>$head_value,
			"file"=>$fileU,
			"status"=>0,
			);
			$insert=$this->db->insert("Form16TransactionDetails",$data);
			if($insert == true){
				$a++;
			}
		} 
		$t=0;
		for($j=1;$j<$countSubHead;$j++){
			$subhead_id=$this->input->post('subhead_id'.$j);
			//count query;
			
			//for($p=1;$p<=$count;$p++){
				$Subhead_value=$this->input->post('Subhead_value'.$j);
			$head_id=$this->input->post('head_id'.$j);
			
			
			$subheadold_file=$this->input->post('subheadold_file'.$i);
			$Subhead_file=('Subhead_file'.$j);
			$file=$this->upload_multiple_file('uploads/',$Subhead_file);
			
			if($file['status'] == 200 && $file['body'] != ""){
				$fileU1='uploads/'.$file['body'];
			}else{
				$fileU1="";
			}
			
			if($fileU1="" && $subheadold_file != ""){
				$fileU1=$subheadold_file;
			}else{
				$fileU1=$fileU1;
			}
			
				$data=array(
			"form_id"=>$form_id,
			"user_id"=>$userid,
			"head_id"=>$head_id,
			"subhead_id"=>$subhead_id,
			"year_id"=>$select_year,
			"value"=>$Subhead_value,
			"file"=>$fileU1,
			"status"=>0,
			//"value_number"=>$p,
			);
			$insert1=$this->db->insert("Form16TransactionDetails",$data);
			$j++;
			//}
			if($insert1 == true){
				$t++;
			}
		} 
		
		if($a>0 && $t>0){
			$response['body']="Successfully Submitted.";
			$response['status']=200;
		}else{
			$response['body']="Fail to Submit.";
			$response['status']=201;
			
		}echo json_encode($response);
		
	}
	
	 function upload_multiple_file($upload_path,$inputname) {
        if (isset($_FILES[$inputname]) && $_FILES[$inputname]['error'] != '4') {
			
            $files = $_FILES;
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = '*';
            $config['max_size'] = '50000000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;

            $this->load->library('upload', $config);

            if (is_array($_FILES[$inputname]['name'])) {
                $count = count($_FILES[$inputname]['name']); // count element
                $files = $_FILES['userfile'];
                $images = array();
				$inputname = $inputname . "[]";

                foreach ($files['name'] as $key => $image) {
                    $_FILES[$inputname]['name'] = $files['name'][$key];
                    $_FILES[$inputname]['type'] = $files['type'][$key];
                    $_FILES[$inputname]['tmp_name'] = $files['tmp_name'][$key];
                    $_FILES[$inputname]['error'] = $files['error'][$key];
                    $_FILES[$inputname]['size'] = $files['size'][$key];

                    $fileName = $image;

                    $images[] = $fileName;

                    $config['file_name'] = $fileName;

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload($inputname)) {
                        $this->upload->data();
                    } else {
                        return false;
                    }
                }

                return $images;
            } else {
                $this->upload->initialize($config);
                $_FILES[$inputname]['name'] = $files[$inputname]['name'];
                $_FILES[$inputname]['type'] = $files[$inputname]['type'];
                $_FILES[$inputname]['tmp_name'] = $files[$inputname]['tmp_name'];
                $_FILES[$inputname]['error'] = $files[$inputname]['error'];
                $_FILES[$inputname]['size'] = $files[$inputname]['size'];

                $fileName = preg_replace('/\s+/', '_', str_replace(' ', '_', $_FILES[$inputname]['name']));
                $data = array('upload_data' => $this->upload->data());
                if (empty($fileName)) {
                    $response['status'] = 203;
                    $response['body'] = "file is empty";
                    return false;
                } else {
                    $file = $this->upload->do_upload($inputname);
                    if (!$file) {
                        $error = array('upload_error' => $this->upload->display_errors());
                        $response['status'] = 204;
                        $response['body'] = $files[$inputname]['name'] . ' ' . $error['upload_error'];
                        return $response;
                    } else {
						$a=$this->upload->data();
                        $response['status'] = 200;
                        $response['body'] = $a['raw_name'].$a['file_ext'];
                        return $response;
                    }
                }
            }
        } else {
            $response['status'] = 200;
            $response['body'] = "";
            return $response;
        }
    }
	
	
	public function form16GetAll(){
		$form_id=$this->input->post("form_id");
		  $session_data = $this->session->userdata('login_session');
		  if($session_data['user_type']==5)
		  {
			  
			  $userid = $this->input->post("user_id");
		  }
		else {
			
			$userid = ($session_data['emp_id']);
		}
		$year_id = $this->input->post("year");
		//print_r($form_id);exit;
		//$form_id=1;
		
		$query=$this->db->query("select * from form16Heads where form_id='$form_id'");
		
		$data="<br><br><br><br><br><br><table class='table table-bordered ' ><thead><tr>
		<th><b>Details of Salary Paid and any other income and tax deducted</b></th>
		<th>RS</th>
		<th>RS</th>
		</tr></thead><tbody>";
		if($this->db->affected_rows() > 0){
			$result=$query->result();
			$k=1;
			$j=1;
			$year_id='2020-2021';
			//print_r($query);exit;
			
			if($form_id==2){
				
			$get_all_salaryDed_detail=$this->get_all_salaryDed_detail($year_id,$userid); //0=salary,1=pT,2=IT,3=PF
			$Gross_Salary=$get_all_salaryDed_detail[0];
			//print_r($get_all_salaryDed_detail);exit;
			$Proffessional_tax=$get_all_salaryDed_detail[1];
			$Income_tax=$get_all_salaryDed_detail[2];
			$Provident_fund=$get_all_salaryDed_detail[3];
			$amt2b=0;$total_b_c=0;$amt_1e=0;$amt6=0;$amt10=0;$amt11=0;$amt13=0;
			$total_3=0;$total=0;$total_4=0;$total_7=0;$total_8=0;$total_9=0;$total_14=0;
			foreach($result as $row){
				$field_type=$row->field_type;
				$input1='';
				$inputfile1='';
				//get input value
				$value_head='';
				$file='';
				$qr=$this->db->query("select * from Form16TransactionDetails where head_id='$row->id' AND user_id='$userid' AND form_id='$form_id' AND year_id='$year_id'");
				if($this->db->affected_rows() > 0){
					$result=$qr->row();
					$value_head=$result->value;
					$file=$result->file;
				}
				if($field_type==1 && $row->head_type != '1'){
							$input1="
							
							";
							
						}else{
							$input1='';
						}
			if($field_type==1 && $row->head_type != '1'){
							$inputfile1="<b>".$value_head."</b>";
							if($row->id == 6){$amt6=$value_head;}
							if($row->id == 10){$amt10=$value_head;}
							if($row->id == 11){$amt11=$value_head;}
							if($row->id == 13){$amt13=$value_head;}
						}else{
							$inputfile1="";
						}
						if($row->id == 3){
								$total_3 = (int)$total-(int)$amt2b;
								
								$inputfile1=$total_3;
								$input1="";
							}
							if($row->id == 4){
								$total_4 = (int)$total_3+(int)$amt_1e;
								
								$inputfile1="<b>".$total_4."</b>";
								$input1="";
							}
							if($row->id == 7){
								$total_7 = (int)$total_4+(int)$amt6;
								
								$inputfile1="<b>".$total_7."</b>";
								$input1="";
							}
							if($row->id == 8){
								$total_8 = $this->get_datastandard_data($form_id,$userid,$total_7);
								
								$inputfile1="<b>".$total_8."</b>";
								$input1="";
							}
							if($row->id == 9){
								$total_9 = $this->get_rebate($total_8);
								
								$inputfile1="<b>".$total_9."</b>";
								$input1="";
							}
							if($row->id == 12){
								$total_12 = (int)$total_8-(int)$total_9+(int)$amt10+(int)$amt11;
								
								$inputfile1="<b>".$total_12."</b>";
								$input1="";
							}
							if($row->id == 14){
								$total_14 = (int)$total_12-(int)$amt13;
								
								$inputfile1="<b>".$total_14."</b>";
								$input1="";
							}
				$id=$row->id;
				$data .="<tr>
				<td><b>".$row->head_num .". ".$row->head_name."</b></td>
				<td>".$input1."</td>
				<td>".$inputfile1."</td>
				
				</tr>
				";
				if($field_type == 2){
				$query1=$this->db->query("select * from form16Sub_Heads where head_id='$id'");	
				if($this->db->affected_rows() > 0){
					$res1=$query1->result();
					//print_r($res1);exit;
					$input='';
					$inputfile='';
					
					
					
					$result1="";
					foreach($res1 as $row1){
						
						$query2=$this->db->query("select * from Form16TransactionDetails where subhead_id='$row1->id' AND user_id='$userid' AND year_id='$year_id'");
				//print_r($row1->id);exit;
				//echo $row1->head_type;
				if($this->db->affected_rows() > 0){
					$result1=$query2->row();
					//print_r($result1);exit;
					$value_sub_head=$result1->value;
					$sub_headfile=$result1->file;
						
				}else{
					$value_sub_head="";
					$sub_headfile="";
				}
				
				$id2=$row1->sub_head_num;
					if($result1!=""){
					if($row1->head_type != 1){
							
							if($row1->sub_head_num=='e' && $row1->head_id==1)
							{
								$inputfile="<b>".$result1->value."</b>";
								$input="";
								$amt_1e=$result1->value;
							}
							else {
							$inputfile="";
							$input=$result1->value;
							$total_b_c=(int)$total_b_c+(int)$result1->value;}
							if($row1->sub_head_num=='b' && $row1->head_id==2){
								$inputfile=$result1->value;
								$input="";
								$amt2b=$result1->value;
							}
							
							
						}else{
							
							if($row1->sub_head_num=="a")
							{	
							$input=$Gross_Salary;
							$inputfile="";
							}
							else {
							$total=(int)$Gross_Salary+(int)$total_b_c;
							$input="";
							$inputfile="<b>".$total."</b>";}
							
						}		
					}
					else{
						if($row1->head_type != 1){
							
							if($row1->sub_head_num=='e' && $row1->head_id==1)
							{
								$inputfile=0;
								$input="";
								$amt_1e=0;
							}
							else {
							$inputfile="";
							$input=0;
							$total_b_c=0;}
							if($row1->sub_head_num=='b' && $row1->head_id==2){
								$inputfile=0;
								$input="";
								$amt2b=0;
							}
							
							
						}else{
							
							if($row1->sub_head_num=="a")
							{	
							$input=$Gross_Salary;
							$inputfile="";
							}
							else {
							$total=(int)$Gross_Salary+(int)$total_b_c;
							$input="";
							$inputfile="<b>".$total."</b>";}
							
						}		
					}
							
						
						$data .="<tr>
				<td>".$row1->sub_head_num .". ".$row1->sub_head_name."</td>
				<td>".$input."</td>
				<td>".$inputfile."</td>
				</tr>";
				$j++;
					}
				}
				}else{
					
				}
				$k++;	
				}
			
			}
			else {
				// old data
				$get_all_salaryDed_detail=$this->get_all_salaryDed_detail($year_id,$userid); //0=salary,1=pT,2=IT,3=PF
				$Gross_Salary=$get_all_salaryDed_detail[0];
				//print_r($get_all_salaryDed_detail);exit;
				$Proffessional_tax=$get_all_salaryDed_detail[1];
				$Income_tax=$get_all_salaryDed_detail[2];
				$Provident_fund=$get_all_salaryDed_detail[3];
				$amt2a=0;$amt2b=0;$amt2c=0;$amt2d=0;$amt2e=0;$amt1e=0;$amt1b=0;$amt1c=0;$amtle=0;$total1_d=0;$total_b_c=0;$amt_1e=0;$amt6=0;$amt10=0;$amt11=0;$amt13=0;
				$total_3=0;$total=0;$total_4=0;$total_7=0;$total_8=0;$total_9=0;$total_14=0;$total2_e=0;$total_17=0;$total_19=0;$total_20=0;$total_22=0;$total_23=0;
				$total_25=0;$total_26=0;$total_27=0;$total_28=0;$total_29=0;$total_30=0;$total_31=0;$total_32=0;$total_33=0;
				$amt10d=0;$amt10e=0;$amt10f=0;$amt10g=0;$amt10h=0;$amt10i=0;$amt10j=0;$amt10l=0;$amt18a=0;$amt18b=0;$amt18c=0;$amt29=0;$amt=30;
				foreach($result as $row){
				$field_type=$row->field_type;
				$input1='';
				$inputfile1='';
				//get input value
				$value_head='';
				$file='';
				$qr=$this->db->query("select * from Form16TransactionDetails where head_id='$row->id' AND user_id='$userid' AND form_id='$form_id' AND year_id='$year_id'");
				if($this->db->affected_rows() > 0){
					$result=$qr->row();
					$value_head=$result->value;
					$file=$result->file;
				}
				
				if($field_type==1 && $row->head_type != '1'){
					$qr=$this->db->query("select * from Form16TransactionDetails where head_id='$row->id' AND user_id='$userid' AND form_id='$form_id' AND year_id='$year_id'");
				if($this->db->affected_rows() > 0){
					$result=$qr->row();
					$value_head=$result->value;
					$file=$result->file;
				
					//echo $row->id;
					if($row->id == 29){
								
								$amt29=$result->value;
								$inputfile1="<b>".$amt29."</b>";
								$input1="";
								$total_29=$amt29;
							}if($row->id == 30){
								
								$amt30=$result->value;
								$inputfile1="<b>".$amt30."</b>";
								$input1="";
								$total_30=$amt30;
				} }
							
							
							
						}else{
							//echo $row->id;
							if($row->id == 17){
								$total_17 = (int)$total1_d-(int)$total2_e;
								
								$inputfile1="<b>".$total_17."</b>";
								$input1="";
							}else if($row->id == 19){
								$total_19 = (int)$amt18a+(int)$amt18b+(int)$amt18c;
								
								$inputfile1="<b>".$total_19."</b>";
								$input1="";
							}
							else if($row->id == 20){
								$total_20 = (int)$total_17 + (int)$amt1e - (int)$total_19;
								
								$inputfile1="<b>".$total_20."</b>";
								$input1="";
							}
							else if($row->id == 22){
								$total_22 = 0;
								
								$inputfile1="<b>".$total_22."</b>";
								$input1="";
							}
							else if($row->id == 23){
								$total_23 = (int)$total_20 + (int)$total_22; //(6+8)
								
								$inputfile1="<b>".$total_23."</b>";
								$input1="";
							}
							else if($row->id == 25){
								$total_25 = (int)$amt10d + (int)$amt10e + (int)$amt10f + (int)$amt10g + (int)$amt10h + (int)$amt10i + (int)$amt10j + (int)$amt10l; //10(d) +10e+ 10(f)+ 10(g)+ 10(h)+10(i)+10(j)+10(l)
								
								$inputfile1="<b>".$total_25."</b>";
								$input1="";
							}
							else if($row->id == 26){
								$total_26 = (int)$total_23-(int)$total_25; //9-11
								
								$inputfile1="<b>".$total_26."</b>";
								$input1="";
							}
							else if($row->id == 27){
								
								$total_27 = $this->get_datastandard_data($form_id,$userid,$total_26);//tax on total income
								
								$inputfile1="<b>".$total_27."</b>";
								$input1="";
							}
							else if($row->id == 28){
								
								$total_28 = $this->get_rebate($total_27);//rebate
								
								$inputfile1="<b>".$total_28."</b>";
								$input1="";
							}
							else if($row->id == 29){
								
								$total_29 = $amt29;//surcharge
								
								$inputfile1="<b>".$total_29."</b>";
								$input1="";
							}
							else if($row->id == 30){
								
								$total_30 = $amt30;//health
								
								$inputfile1="<b>".$total_30."</b>";
								$input1="";
							}
							else if($row->id == 31){
								
								$total_31 = (int)$total_27 + (int)$total_29 + (int)$total_30 - (int)$total_28;//Tax payable (13+15+16-14)
								
								$inputfile1="<b>".$total_31."</b>";
								$input1="";
							}
							else if($row->id == 32){
								
								$total_32 = 0;// Less: Relief under section 89 (attach details)
								
								$inputfile1="<b>".$total_32."</b>";
								$input1="";
							}
							else if($row->id == 33){
								
								$total_33 = $total_31 - $total_32;//Net tax payable (17-18)
								
								$inputfile1="<b>".$total_33."</b>";
								$input1="";
							}
							
							else {
								
							$input1='';
							$inputfile1="";
							}
						}
			
						//echo $row->id;
						
							
							// if($row->id == 14){
								// $total_14 = (int)$total_12-(int)$amt13;
								
								// $inputfile1="<b>".$total_14."</b>";
								// $input1="";
							// }
				$id=$row->id;
				$data .="<tr>
				<td><b>".$row->head_num .". ".$row->head_name."</b></td>
				<td>".$input1."</td>
				<td>".$inputfile1."</td>
				
				</tr>
				";
				if($field_type == 2){
				$query1=$this->db->query("select * from form16Sub_Heads where head_id='$id'");	
				if($this->db->affected_rows() > 0){
					$res1=$query1->result();
					//print_r($res1);exit;
					$input='';
					$inputfile='';
					
					
					
					$result1="";
					foreach($res1 as $row1){
						
						$query2=$this->db->query("select * from Form16TransactionDetails where subhead_id='$row1->id' AND user_id='$userid' AND year_id='$year_id'");
				//print_r($row1->id);exit;
				//echo $row1->head_type;
				if($this->db->affected_rows() > 0){
					$result1=$query2->row();
					//print_r($result1);exit;
					$value_sub_head=$result1->value;
					$sub_headfile=$result1->file;
						
				}else{
					$value_sub_head="";
					$sub_headfile="";
				}
				
				$id2=$row1->sub_head_num;
					if($result1!=""){
					if($row1->head_type != 1){
							
							
							$inputfile="";
							$input=$result1->value;
							$total_b_c=(int)$total_b_c+(int)$result1->value;
							//echo $row1->sub_head_num;
							if($row1->sub_head_num=="b" && $row1->head_id==15)
								 {
									 $amt1b=$result1->value;
									
								 }
								  if($row1->sub_head_num=="c" && $row1->head_id==15)
								 {
									 $amt1c=$result1->value;
									 
								 }
								 if($row1->sub_head_num=="e" && $row1->head_id==15)
								 {
									 $amt1e=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="a" && $row1->head_id==16)
								 {
									 $amt2a=$result1->value;
									 
								 }
								 if($row1->sub_head_num=="b" && $row1->head_id==16)
								 {
									 $amt2b=$result1->value;
									 
								 }
								 if($row1->sub_head_num=="c" && $row1->head_id==16)
								 {
									 $amt2c=$result1->value;
									 
								 }
								 if($row1->sub_head_num=="d" && $row1->head_id==16)
								 {
									 $amt2d=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="d" && $row1->head_id==24)
								 {
									 $amt10d=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="e" && $row1->head_id==24)
								 {
									 $amt10e=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="f" && $row1->head_id==24)
								 {
									 $amt10f=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="g" && $row1->head_id==24)
								 {
									 $amt10g=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="h" && $row1->head_id==24)
								 {
									 $amt10h=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="i" && $row1->head_id==24)
								 {
									 $amt10i=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="j" && $row1->head_id==24)
								 {
									 $amt10j=$result1->value;
									 
								 }
								  if($row1->sub_head_num=="l" && $row1->head_id==24)
								 {
									 $amt10l=$result1->value;
									 
								 }
								 if($row1->sub_head_num=="a" && $row1->head_id==18)
								 {
									 $amt18a=$result1->value;
									 
								 }
								 if($row1->sub_head_num=="b" && $row1->head_id==18)
								 {
									 $amt18b=$result1->value;
									 
								 }
								 if($row1->sub_head_num=="c" && $row1->head_id==18)
								 {
									 $amt18c=$result1->value;
									 
								 }
								 
							
						}else{
							
							if($row1->sub_head_num=="a" && $row1->head_id==1)
							{	
							$input=$Gross_Salary;
							$inputfile="";
							}
							 else {
								
								 if($row1->sub_head_num=="d")
								 {
									$total1_d=(int)$Gross_Salary+(int)$amt1b+(int)$amt1c;
									 $input="";
									$inputfile="<b>".$total1_d."</b>";
								 }
								
								 if($row1->sub_head_num=="e")
								 {
									 $amt2e=$result1->value;
									 $total2_e=(int)$amt2a+(int)$amt2b+(int)$amt2c+(int)$amt2d;
									 $input="";
									$inputfile="<b>".$total2_e."</b>";
								 }
								 
								// $total1_d=$total_b_c;
								
								 
							 
							 }
							 
							
						}		
					}
					else{
						if($row1->head_type != 1){
							
							if($row1->sub_head_num=='e' && $row1->head_id==1)
							{
								$inputfile=0;
								$input="";
								$amt_1e=0;
							}
							else {
							$inputfile="";
							$input=0;
							$total_b_c=0;}
							if($row1->sub_head_num=='b' && $row1->head_id==2){
								$inputfile=0;
								$input="";
								$amt2b=0;
							}
							
							
						}else{
							
							if($row1->sub_head_num=="a")
							{	
							$input=$Gross_Salary;
							$inputfile="";
							}
							else {
							$total=(int)$Gross_Salary+(int)$total_b_c;
							$input="";
							$inputfile="<b>".$total."</b>";}
							
						}		
					}
							
						
						$data .="<tr>
				<td>".$row1->sub_head_num .". ".$row1->sub_head_name."</td>
				<td>".$input."</td>
				<td>".$inputfile."</td>
				</tr>";
				$j++;
					}
				}
				}else{
					
				}
			$k++;	
			}
			
			
			}
			$data.="</tbody></table>";
			$data.="
			<input type='hidden' name='countHead' id='countHead' value='".$k."'>
			<input type='hidden' name='countSubHead' id='countSubHead' value='".$j."'>
			";
			
		
		$response['data']=$data;
			$response['status']=200;
		}else{
			$response['data']=$data;
			$response['status']=201;
			
		}
		echo json_encode($response);
	}
	public function get_datastandard_data($form_id,$user_id,$yearly_sal)
	{
	
		 
		$get_user_gender = $this->db->query("select gender from user_header_all where user_id ='$user_id'")->row();
        $gender = $get_user_gender->gender;
        if ($gender == 1){
			$type=2;
        } else {
            $type=1;
        }
		$yearly_tax_Final=0;
		 if($yearly_sal < 0)
		 {
			 $yearly_tax_Final=0;
		 }else{
			$query=$this->db->query("select * from t_form16_standardfigureNew where type='$type' and form_id='$form_id' ")->result();
		
			$response=array();
			foreach($query as $row)
			{
				$max_value=$row->max_value;
				$min_value=$row->min_value;
				if($yearly_sal <= $max_value && $yearly_sal > $min_value)
				{
					$response['status']=200;
					$tax_percent=$row->tax_percent;
					$charges=$row->charges;
					//echo $yearly_sal-$min_value;
					$yearly_tax=(($yearly_sal-$min_value)*($tax_percent/100))+$charges;
					$yearly_tax_Final=round($yearly_tax);
				}
			} 
		 }
		return $yearly_tax_Final;
	}
	public function get_rebate($tax_on_total_incom){
	
		if ($tax_on_total_incom < 12500) {
					$rebate = $tax_on_total_incom;
				} else if($tax_on_total_incom == 12500) {
					$rebate =  12500;
				}else{
					 $rebate =  0;
				}
			
			return $rebate;
	}
	public function create_Form16_pdf()
	{
		//print_r("data check");exit;
		//$pdf_data=$this->input->post('pdf_data');
		$result['customer_details']=$this->input->post('pdf_data');
		//print_r($pdf_data);exit;
		        // Get output html
		$this->load->view('human_resource/View_Download_pdf',$result);
	//	exit;
		 // $this->load->library('new_pdf/Pdf');
				  // $html_content = '<h3 align="center">Convert HTML to PDF in CodeIgniter using Dompdf</h3>';
				  // $html_content .= $pdf_data;
         // $this->pdf->loadHtml($html_content);
         // $this->pdf->render();
        // $this->pdf->stream("form16.pdf", array("Attachment" => 0));
		 
		 //$html = $pdf_data;
        
       
       // $this->load->library('new_pdf/Pdf'); // Load pdf library
        
        
        //$this->dompdf->loadHtml($html);// Load HTML content
        
      
        //$this->dompdf->setPaper('A4', 'landscape');  // (Optional) Setup the paper size and orientation
        
       
       // $this->dompdf->render(); // Render the HTML as PDF
        
       
       // $this->dompdf->stream("welcome.pdf", array("Attachment"=>0)); // Output the generated PDF (1 = download and 0 = preview)
	}
	
	
	
	
	
	
}
?>


