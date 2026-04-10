<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/PHPExcel/PHPExcel.php");


class Employee extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Emp_model');
        $this->load->model('emp_model');
        $this->load->model('firm_model');
        $this->load->model('email_sending_model');
        $this->load->model('customer_model');
		$this->db2 = $this->load->database('db2', TRUE);
		$this->load->library('upload');
        $this->load->helper('dump_helper');
    }

    public function index() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $user_type = ($session_data['user_type']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `email`= '$email_id'");
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
        $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$user_id'");
        if ($query_sen_id->num_rows() > 0) {
            $res_sen_id = $query_sen_id->result();
            foreach ($res_sen_id as $row) {
                $user_id_emp = $row->user_id;
                $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$user_id_emp' AND status='1'");
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

        $data['firm_id'] = $firm_id;
        $data['prev_title'] = "Create Employee";
        $data['page_title'] = "Create Employee";
        $this->load->view('client_admin/create_employee', $data);
    }

    public function employee_hq() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $query = $this->db
                ->select('firm_logo, user_name')
                ->from('user_header_all')
                ->where('firm_id', $firm_id)
                ->get();
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
        $result22 = $this->db->query("SELECT firm_id FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result22->num_rows() > 0) {
            $record = $result22->row();
            $login_firm_id = $record->firm_id;
        }
        $hr_auth = $this->db->query("select hr_authority from user_header_all where firm_id='$login_firm_id' AND user_type='5'");
        if ($this->db->affected_rows() > 0) {
            $record1 = $hr_auth->row();
            $firm_id11 = $record1->hr_authority;
            $data['firm_name_sal'] = $firm_id11;
        }
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $firm_id = $record->firm_id;
            $user_id = $record->user_id;
            $user_type = $record->user_type;
        }
        $data['user_type'] = $user_type;
        $data['firm_id'] = $firm_id;
        $q11 = $this->db->query("SELECT `due_date_creation_permitted` from `partner_header_all` where `firm_id`='$firm_id'");
        if ($q11->num_rows() > 0) {
            $due_date = $q11->result();
            foreach ($due_date as $row3) {
                $due_date_creation_permitted = $row3->due_date_creation_permitted;
                $this->session->set_userdata("due_date_permission", $due_date_creation_permitted);
            }
        }
        $data['prev_title'] = "Create Employee";
        $data['page_title'] = "Create Employee";

        $this->load->view('human_resource/create_employee', $data);
    }

    public function get_leave_Details() {
        $firm_id = $this->input->post('firm_id');
        $query_fetch_leave_type = $this->db->query(" Select designation_header_all.total_yearly_leaves ,leave_header_all.type1,leave_header_all.type2,leave_header_all.type3,leave_header_all.type4,leave_header_all.type5,leave_header_all.type6,leave_header_all.type7 from leave_header_all  inner join designation_header_all where leave_header_all.firm_id='$firm_id' and leave_header_all.designation_id='CA'");
        $this->db->last_query();
        if ($query_fetch_leave_type->num_rows() > 0) {
            $result_leave_type = $query_fetch_leave_type->row();

            $type1 = $result_leave_type->type1;
            $type2 = $result_leave_type->type2;
            $type3 = $result_leave_type->type3;
            $type4 = $result_leave_type->type4;
            $type5 = $result_leave_type->type5;
            $type6 = $result_leave_type->type6;
            $type7 = $result_leave_type->type7;
            $total_yearly_leaves = $result_leave_type->total_yearly_leaves;
            $response['message'] = 'success';
            $response['type1'] = $type1;
            $response['type2'] = $type2;
            $response['type3'] = $type3;
            $response['type4'] = $type4;
            $response['type5'] = $type5;
            $response['type6'] = $type6;
            $response['type7'] = $type7;
            $response['total_yearly_leaves'] = $total_yearly_leaves;
        } else {
            $response['leave'] = '';
            $response['message'] = '';
            $response['type1'] = '';
            $response['type2'] = '';
            $response['type3'] = '';
            $response['type4'] = '';
            $response['type5'] = '';
            $response['type6'] = '';
            $response['type7'] = '';
            $response['total_yearly_leaves'] = '';
        }
        echo json_encode($response);
        //end bhava
    }

    public function get_leave1Details() {
        $user_id = $this->input->post('user_id');
        $q1 = $this->db->query("Select designation_id,firm_id from user_header_all where user_id ='$user_id'");
        if ($q1->num_rows() > 0) {
            $recss = $q1->row();
            $designation_id = $recss->designation_id;
            $firm_id = $recss->firm_id;
        }
        // echo  $this->db->last_query();
        $query_fetch_leave_type = $this->db->query(" Select designation_header_all.total_yearly_leaves ,leave_header_all.type1,leave_header_all.type2,leave_header_all.type3,leave_header_all.type4,leave_header_all.type5,leave_header_all.type6,leave_header_all.type7 from leave_header_all  inner join designation_header_all where leave_header_all.firm_id='$firm_id'");
        $this->db->last_query();
        if ($query_fetch_leave_type->num_rows() > 0) {
            $result_leave_type = $query_fetch_leave_type->row();

            $t1 = 'PL';
            $t2 = 'CL';
            $t3 = 'SL';

            $type1 = $result_leave_type->type1;
            $type2 = $result_leave_type->type2;
            $type3 = $result_leave_type->type3;
            $type4 = $result_leave_type->type4;
            $type5 = $result_leave_type->type5;
            $type6 = $result_leave_type->type6;
            $type7 = $result_leave_type->type7;
            $total_yearly_leaves = $result_leave_type->total_yearly_leaves;

            if($type1 != null && $type1 != ''){
                $t1 = $type1;
            }
           if($type2 != null && $type2 != ''){
                $t2 = $type2;
            }
           if($type3 != null && $type3 != ''){
                $t3 = $type3;
            }

            $response['message'] = 'success';
            $response['type1'] = $t1;
            $response['type2'] = $t2;
            $response['type3'] = $t3;
            $response['type4'] = $type4;
            $response['type5'] = $type5;
            $response['type6'] = $type6;
            $response['type7'] = $type7;
            $response['total_yearly_leaves'] = $total_yearly_leaves;
			$response['query'] = $this->db->last_query();
        } else {
            $response['leave'] = '';
            $response['message'] = '';
            $response['type1'] = 'PL';
            $response['type2'] = 'CL';
            $response['type3'] = 'SL';
            $response['type4'] = '';
            $response['type5'] = '';
            $response['type6'] = '';
            $response['type7'] = '';
            $response['total_yearly_leaves'] = '';
        }
        echo json_encode($response);
        //end bhava
    }

    public function update_employees_leaves() {
        // dd("abhishek mishra : update_employees_leaves");
        $user_id = $this->input->post('user_id');
		$query=$this->db->query("select * from user_header_all where user_id='$user_id'");
        $result=$query->row();
        $current_date = date('Y-m-d');
        $joinTimestamp = strtotime($result->date_of_joining);
        $currentTimestamp = strtotime($current_date);
        $diffInSeconds = $currentTimestamp - $joinTimestamp;
        $diffInDays = floor($diffInSeconds / (60 * 60 * 24));
        $user_id = $this->input->post_get('user_id');
        $type1 = $this->input->post_get('leave_type1');
        $type2 = $this->input->post_get('leave_type2');
        $type3 = $this->input->post_get('leave_type3');
        $type4 = $this->input->post_get('leave_type4');
        $type5 = $this->input->post_get('leave_type5');
        $type6 = $this->input->post_get('leave_type6');
        $type7 = $this->input->post_get('leave_type7');
        $numofdays1 = $this->input->post_get('numofdays1');
        $numofdays2 = $this->input->post_get('numofdays2');
        $numofdays3 = $this->input->post_get('numofdays3');
        $numofdays4 = $this->input->post_get('numofdays4');
        $numofdays5 = $this->input->post_get('numofdays5');
        $numofdays6 = $this->input->post_get('numofdays6');
        $numofdays7 = $this->input->post_get('numofdays7');
        $total_leaves = $this->input->post_get('total_leaves');
        $leave_balance = $this->input->post_get('leave_balance');
		$acc_per = $this->input->post_get('acc_per');
        $accrued_period = $this->input->post_get('accrued_period');
        $accr_leave_count = $this->input->post_get('accr_leave');
		$accured_type = $this->input->post_get('accrued_type') ?? 0;
        $balance_1 = $this->input->post_get('balance_leave_1') ?? 0;
        $balance_2 = $this->input->post_get('balance_leave_2') ?? 0;
        $balance_3 = $this->input->post_get('balance_leave_3') ?? 0;
        $balance_4 = $this->input->post_get('balance_leave_4') ?? 0;
        $balance_5 = $this->input->post_get('balance_leave_5') ?? 0;
        $balance_6 = $this->input->post_get('balance_leave_6') ?? 0;
        $balance_7 = $this->input->post_get('balance_leave_7') ?? 0;
        if($diffInDays > 180 ){
            if (($type1 == '' && $numofdays1 == '' && $balance_1 == '') && ($type2 == '' && $numofdays2 == '' && $balance_2 == '') && ($type3 == '' && $numofdays3 == '' && $balance_3 == '')) {
                $response['error'] = 'Please Enter PL/CL/SL No of Days';
                echo json_encode($response);
                return;
            }
        } else {
            if (($type2 == '' && $numofdays2 == ''  && $balance_2 == '')) {
                $response['error'] = 'Please Enter Leave Type and No of Days';
                echo json_encode($response);
                return;
            }
        }
        
        if (empty($numofdays1)) {
            $numofdays1 = 0;
        }if (empty($numofdays2)) {
            $numofdays2 = 0;
        }if (empty($numofdays3)) {
            $numofdays3 = 0;
        }if (empty($numofdays4)) {
            $numofdays4 = 0;
        }if (empty($numofdays5)) {
            $numofdays5 = 0;
        }if (empty($numofdays6)) {
            $numofdays6 = 0;
        }if (empty($numofdays7)) {
            $numofdays7 = 0;
        }

        $totalleaves = $numofdays1 + $numofdays2 + $numofdays3 + $numofdays4 + $numofdays5 + $numofdays6 + $numofdays7;
        if($total_leaves != $totalleaves) {
            $response['id'] = 'total_leaves';
            $response['error'] = 'Sum Of Leave Should Be Equal To Total Leaves';
            echo json_encode($response);
            return;
        }
        $CF1 = $this->input->post_get('CF1');
        $CF2 = $this->input->post_get('CF2');
        $CF3 = $this->input->post_get('CF3');
        $CF4 = $this->input->post_get('CF4');
        $CF5 = $this->input->post_get('CF5');
        $CF6 = $this->input->post_get('CF6');
        $CF7 = $this->input->post_get('CF7');

        if (empty($user_id)) {
            $response['id'] = 'emp_id_leave';
            $response['error'] = 'Please Select Employee';
        } elseif (empty($total_leaves)) {
            $response['id'] = 'total_leaves';
            $response['error'] = 'Enter Total Leaves';
        } elseif (empty($numofdays2)) {
            $response['id'] = 'numofdays2';
            $response['error'] = 'Enter No Of Days';
        } elseif ($type4 != '' && empty(trim($numofdays4))) {
            $response['id'] = 'numofdays4';
            $response['error'] = 'Please Enter Day';
        } elseif ($type4 != '' && $CF4 == '') {
            $response['id'] = 'CF4';
            $response['error'] = 'Please Select';
        } elseif ($type5 != '' && empty(trim($numofdays5))) {
            $response['id'] = 'numofdays5';
            $response['error'] = 'Please Enter Day';
        } elseif ($type5 != '' && $CF5 == '') {
            $response['id'] = 'CF5';
            $response['error'] = 'Please Select';
        } elseif ($type6 != '' && empty(trim($numofdays6))) {
            $response['id'] = 'numofdays6';
            $response['error'] = 'Please Enter Day';
        } elseif ($type6 != '' && $CF6 == '') {
            $response['id'] = 'CF6';
            $response['error'] = 'Please Select';
        } elseif ($type7 != '' && empty(trim($numofdays7))) {
            $response['id'] = 'numofdays7';
            $response['error'] = 'Please Enter Day';
        } elseif ($type7 != '' && $CF7 == '') {
            $response['id'] = 'CF7';
            $response['error'] = 'Please Select';
		} elseif ($accrued_period == 0 && $acc_per == 1) {
            $response['id'] = "accrued_period";
            $response['error'] = "Please Select";
		} elseif ($accured_type == 0 && $acc_per == 1) {
            $response['id'] = "accrued_type";
            $response['error'] = "Please Select";
		} elseif ($accr_leave_count == '' && $accr_leave_count == null && $accr_leave_count == 0 && $acc_per == 1) {
            $response['id'] = "accr_leave";
            $response['error'] = "Enter Value Greater than Zero.";
        } else {
            $query_joiningdate = $this->db->query("SELECT `date_of_joining`,`firm_id`,`designation_id` from `user_header_all` where user_id ='$user_id'");
            if ($query_joiningdate->num_rows() > 0) {
                $res = $query_joiningdate->row();
                $date_of_joining = $res->date_of_joining;
                $designation_id = $res->designation_id;
                $d = date("d-m-y", strtotime($date_of_joining));
                $joindate = strtotime($d);
                $current_date = date('Y-m-d');
                $joinTimestamp = strtotime($date_of_joining);
                $currentTimestamp = strtotime($current_date);
                $diffInSeconds = $currentTimestamp - $joinTimestamp;
                $diffInDays = floor($diffInSeconds / (60 * 60 * 24));

                $firm_id = $res->firm_id;
                $query = $this->db->query("(select partner_header_all.accural_month from partner_header_all where partner_header_all.firm_id='$firm_id')");
                if ($this->db->affected_rows() > 0) {
                    $resss = $query->row();
                    $accrual_month = $resss->accural_month;
                    $year = date("Y");
                    $y = ' 1' . '-' . $accrual_month . '-' . $year;
                    $e = strtotime($y);
                    $perivous_year = $year - 1;
                    $pyear = '1' . '-' . $accrual_month . '-' . $perivous_year;
                    $p_y = strtotime($pyear);
                    $date_of_joining_month = $date_of_joining;
                    $date_of_joining_mon = date("m-y", strtotime($accrual_month));
                } else {
                    $data['date_of_joining'] = '';
                    $data['firm_id'] = '';
                    $data['designation_id'] = '';
                }
            }
            if ($type1 != '') {
                $type_1 = $type1 . ':' . $numofdays1 . ':' . $CF1;
            } else {
                $type_1 = '';
            }
            if ($type2 != '') {
                $type_2 = $type2 . ':' . $numofdays2 . ':' . $CF2;
            } else {
                $type_2 = '';
            }
            if ($type3 != '') {
                $type_3 = $type3 . ':' . $numofdays3 . ':' . $CF3;
            } else {
                $type_3 = '';
            }
            if ($type4 != '') {
                $type_4 = $type4 . ':' . $numofdays4 . ':' . $CF4;
            } else {
                $type_4 = '';
            }
            if ($type5 != '') {
                $type_5 = $type5 . ':' . $numofdays5 . ':' . $CF5;
            } else {
                $type_5 = '';
            }
            if ($type6 != '') {
                $type_6 = $type6 . ':' . $numofdays6 . ':' . $CF6;
            } else {
                $type_6 = '';
            }
            if ($type7 != '') {
                $type_7 = $type7 . ':' . $numofdays7 . ':' . $CF7;
            } else {
                $type_7 = '';
            }
            
            if ($joindate > $p_y && $joindate < $e) {
                // dd("abhishek mishra : if ");
                $qur1 = $this->db->query("SELECT total_yearly_leaves from designation_header_all where designation_id = '$designation_id' AND firm_id='$firm_id'");
                $this->db->last_query();
                if ($qur1->num_rows() > 0) {
                    $recrd1 = $qur1->row();
                    $total_monthly_leaves = $recrd1->total_yearly_leaves;
                } else {
                    $total_monthly_leaves = "";
                }
                $type1;
                $percentage_type1 = ($numofdays1 / $total_monthly_leaves) * 100;
                $percentage_type1;
                $percentage_type2 = ($numofdays2 / $total_monthly_leaves) * 100;
                $percentage_type2;
                $percentage_type3 = ($numofdays3 / $total_monthly_leaves) * 100;
                $percentage_type4 = ($numofdays4 / $total_monthly_leaves) * 100;
                $percentage_type5 = ($numofdays5 / $total_monthly_leaves) * 100;
                $percentage_type6 = ($numofdays6 / $total_monthly_leaves) * 100;
                $percentage_type7 = ($numofdays7 / $total_monthly_leaves) * 100;
                $per_Array = array($percentage_type1, $percentage_type2, $percentage_type3, $percentage_type4, $percentage_type5, $percentage_type6, $percentage_type7);
                $per = $per_Array;
                $type1_blance = $per[0];
                $type2_blance = $per[1];
                $type3_blance = $per[2];
                $type4_blance = $per[3];
                $type5_blance = $per[4];
                $type6_blance = $per[5];
                $type7_blance = $per[6];
                
                if ($type1_blance != '') {
                    $valuetype1 = $total_monthly_leaves * ($type1_blance / 100);
                } else {
                    $valuetype1 = '';
                }
                if ($type2_blance != '') {
                    $valuetype2 = $total_monthly_leaves * ($type2_blance / 100);
                    $valuetype2;
                } else {
                    $valuetype2 = '';
                }
                if ($type3_blance != '') {
                    $valuetype3 = $total_monthly_leaves * ($type3_blance / 100);
                    $valuetype3;
                } else {
                    $valuetype3 = '';
                }
                if ($type4_blance != '') {
                    $valuetype4 = $total_monthly_leaves * ($type4_blance / 100);
                } else {
                    $valuetype4 = '';
                }
                if ($type5_blance != '') {
                    $valuetype5 = $total_monthly_leaves * ($type5_blance / 100);
                } else {
                    $valuetype5 = '';
                }
                if ($type6_blance != '') {
                    $valuetype6 = $total_monthly_leaves * ($type6_blance / 100);
                } else {
                    $valuetype6 = '';
                }
                if ($type7_blance != '') {
                    $valuetype7 = $total_monthly_leaves * ($type7_blance / 100);
                } else {
                    $valuetype7 = '';
                }
				if($acc_per == 1){
					$this->db->query("update user_header_all SET type1_balance='0' where user_id='$user_id'");
					$create_event = $this->function_create_event($accrued_period,$accr_leave_count,$user_id,$accured_type);
					$valuetype2 = '';
					$valuetype3 = '';
					$valuetype4 = '';
					$valuetype5 = '';
					$valuetype6 = '';
					$valuetype7 = '';
					$insert_blance_type = array("total_leaves" => $total_leaves,"accrued_leave" => $acc_per,"accure_from" => $accured_type,"accrued_period" => $accrued_period,
                                "count_leave_accrued" => $accr_leave_count, "type2_balance" => $valuetype2, "type3_balance" => $valuetype3, "type4_balance" => $valuetype4, "type5_balance" => $valuetype5,
                                "type6_balance" => $valuetype6, "type7_balance" => $valuetype7, "type1" => $type_1, "type2" => $type_2, "type3" => $type_3, "type4" => $type_4, "type5" => $type_5, "type6" => $type_6, "type7" => $type_7,"total_leave_available"=>$leave_balance);

				} else {
					$event_name="L_event_".$user_id;
					$this->db->query("DROP EVENT IF EXISTS ".$event_name."");
					$insert_blance_type = array("total_leaves" => $total_leaves,"accrued_leave" => $acc_per,"accure_from" => $accured_type,"accrued_period" => $accrued_period,
                        "count_leave_accrued" => $accr_leave_count,
                            "type1_balance" => $valuetype1, "type2_balance" => $valuetype2, "type3_balance" => $valuetype3, "type4_balance" => $valuetype4, "type5_balance" => $valuetype5,
                            "type6_balance" => $valuetype6, "type7_balance" => $valuetype7, "type1" => $type_1, "type2" => $type_2, "type3" => $type_3, "type4" => $type_4, "type5" => $type_5, "type6" => $type_6, "type7" => $type_7,"total_leave_available"=>$leave_balance);

				}
                if($diffInDays < 180) {
                    if (empty(($numofdays2))) {
                        $response['id'] = 'num of days 2';
                        $response['error'] = 'Enter No Of Days';
                    }
                    // dd("abhishek mishra : diffInDays < 180", $insert_blance_type);
                    $query = $this->db->update('user_header_all', $insert_blance_type);
                    $this->db->last_query();
                    if ($query !== FALSE) {
                        $response['message'] = 'success';
                        $response["status"] = true;
                        $response['body'] = 'Staff  Udated Successful';
                    } else {
                        $response['message'] = 'fail';
                        $response["status"] = false;
                        $response['body'] = 'Failed';
                    }
                } elseif ($diffInDays > 180) {
                    if(empty($numofdays1) && empty($numofdays2) && empty($numofdays3)) {
                        $response['message'] = 'Add all PL/CL/SL leave';
                        $response["status"] = false;
                        $response['body'] = 'Failed';
                    } else {
                        // dd("abhishek mishra : diffInDays > 180", $insert_blance_type);
                        $query = $this->db->update('user_header_all', $insert_blance_type);
                        $this->db->last_query();
                        if ($query !== FALSE) {
                            $response['message'] = 'success';
                            $response["status"] = true;
                            $response['body'] = 'Staff  Udated Successful';
                        } else {
                            $response['message'] = 'fail';
                            $response["status"] = false;
                            $response['body'] = 'Failed';
                        }
                    }
                } else {

                }
                
            } else {
				if($acc_per == 1){
                    // dd("else abhishek mishra : acc_per");
					$this->db->query("update user_header_all SET type1_balance='0' where user_id='$user_id'");
                        $create_event=$this->function_create_event($accrued_period,$accr_leave_count,$user_id,$accured_type);
                        $numofdays2 = '';
                        $numofdays3 = '';
                        $numofdays4 = '';
                        $numofdays5 = '';
                        $numofdays6 = '';
                        $numofdays7 = '';
                        $loan_data = array("accrued_leave" => $acc_per,"accrued_period" => $accrued_period,"accure_from" => $accured_type,
                                        "count_leave_accrued" => $accr_leave_count,"type1" => $type_1, "type2" => $type_2, "type3" => $type_3, "type4" => $type_4, "type5" => $type_5, "type6" => $type_6, "type7" => $type_7,
                                        "type1_balance" => $balance_1, "type2_balance" => $balance_2, "type3_balance" => $balance_3, "type4_balance" => $balance_4,
                                        "type5_balance" => $balance_5, "type6_balance" => $balance_6, "type7_balance" => $balance_7,"total_leaves" => $total_leaves
                    );
                    // dd("if ", $loan_data);
				} else {
					$event_name="L_event_".$user_id;
					$this->db->query("DROP EVENT IF EXISTS ".$event_name."");
					$loan_data = array("accrued_leave" => $acc_per,"accrued_period" => $accrued_period,"accure_from" => $accured_type,
                                    "count_leave_accrued" => $accr_leave_count,"type1" => $type_1, "type2" => $type_2, "type3" => $type_3, "type4" => $type_4, "type5" => $type_5, "type6" => $type_6, "type7" => $type_7,
                                    "type1_balance" => $balance_1, "type2_balance" => $balance_2, "type3_balance" => $balance_3, "type4_balance" => $balance_4,
                                    "type5_balance" => $balance_5, "type6_balance" => $balance_6, "type7_balance" => $balance_7,"total_leaves" => $total_leaves
                    );
                    // dd("else ", $loan_data);
				}
                $this->db->where('user_id', $user_id);
                $query = $this->db->update('user_header_all', $loan_data);
                $this->db->last_query();
                if ($query !== FALSE) {
                    $response['message'] = 'success';
                    $response["status"] = true;
                    $response['body'] = 'Staff  udated successfully';
                } else {
                    $response['message'] = 'fail';
                    $response["status"] = false;
                    $response['body'] = 'Failed';
                }
            }
        }
        echo json_encode($response);
    }

	public function getAllActiveEmployee()
	{
		$query=$this->db->query("select user_id from user_header_all where activity_status=1");
		if($this->db->affected_rows() > 0){
			$result=$query->result();
			foreach ($result as $row){
				$user_id=$row->user_id;
				$createEvent=$this->function_create_event(1,'1.75',$user_id);
			}
		}
    }
	public function function_create_event($accrued_period,$accr_leave_count,$user_id,$accured_type=null){

		//1=monthly,2=quarterly,3=half yearly,4=yearly,5=weekly
		//accured Type 1= Pl ,2= CL,3=SL

		$type = 'user_header_all.type1_balance';
		if($accured_type == 2){
			$type = 'user_header_all.type2_balance';
		}else if ($accured_type == 3){
			$type = 'user_header_all.type3_balance';
		}
		if($accrued_period == "5"){

			$event_name="L_event_".$user_id;
			$this->db->query("DROP EVENT IF EXISTS ".$event_name."");
			$sql="CREATE EVENT ".$event_name." ON SCHEDULE EVERY 1 WEEK STARTS NOW() 
			  ON 
			  COMPLETION NOT PRESERVE ENABLE 
			  DO  UPDATE user_header_all SET ".$type." =  ".$type." + ".$accr_leave_count." WHERE user_header_all.user_id='$user_id'";
			$result=$this->db->query($sql);
		} else if($accrued_period == "1"){

			$event_name="L_event_".$user_id;
			$this->db->query("DROP EVENT IF EXISTS ".$event_name."");
			  $sql="CREATE EVENT ".$event_name." ON SCHEDULE EVERY 1 MONTH STARTS NOW() 
			  ON 
			  COMPLETION NOT PRESERVE ENABLE 
			  DO  UPDATE user_header_all SET ".$type." =  ".$type." + ".$accr_leave_count." WHERE user_header_all.user_id='$user_id'";

			$result=$this->db->query($sql);
		}else if($accrued_period == "4"){

			$event_name="L_event_".$user_id;
			$this->db->query("DROP EVENT IF EXISTS ".$event_name."");
			  $sql="CREATE EVENT ".$event_name." ON SCHEDULE EVERY 1 YEAR STARTS NOW() 
			  ON 
			  COMPLETION NOT PRESERVE ENABLE 
			  DO  UPDATE user_header_all SET ".$type." =  ".$type." + ".$accr_leave_count." WHERE user_header_all.user_id='$user_id'";

			$result=$this->db->query($sql);
		}else if($accrued_period == "2"){

			$event_name="L_event_".$user_id;
			$this->db->query("DROP EVENT IF EXISTS ".$event_name."");
			  $sql="CREATE EVENT ".$event_name." ON SCHEDULE EVERY 1 QUARTER STARTS NOW() 
			  ON 
			  COMPLETION NOT PRESERVE ENABLE 
			  DO  UPDATE user_header_all SET ".$type." =  ".$type." + ".$accr_leave_count." WHERE user_header_all.user_id='$user_id'";

			$result=$this->db->query($sql);
		}

		return;
	}
    public function add_employee_hr() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
            $user_id = $result1['user_id'];
        }


        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and `user_id` = '$user_id'");
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
        $email_id = $this->session->userdata('login_session');
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
        }
        $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$user_id'");
        if ($query_sen_id->num_rows() > 0) {
            $res_sen_id = $query_sen_id->result();
            foreach ($res_sen_id as $row) {
                $user_id_emp = $row->user_id;
                $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$user_id_emp' AND status='1'");
                if ($query_find_leave->num_rows() > 0) {
                    $result_sen_id = $query_find_leave->row();
                    $leave_requested_on = $result_sen_id->leave_requested_on;
                    $leave_type = $result_sen_id->leave_type;
                    $response['leave'][] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
                    $data['leave_type'] = $response['leave'];
                    $data['leave_requested_on'] = $response['leave'];
                } else {

                    $leave_requested_on = '';
                    $leave_type = '';
                    $response['leave'] = '';
                }
            }
        } else {

        }


        $data['prev_title'] = "Create Employee";
        $data['page_title'] = "Create Employee";
        $this->load->view('human_resource/create_employee', $data);
    }

    function get_total_emp_leave($firm_id, $designation, $date_of_joining) {


        $query = $this->db->query("(select partner_header_all.accural_month from partner_header_all where partner_header_all.firm_id='$firm_id')");
//        echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $resss = $query->row();
            $accrual_month = $resss->accural_month;

            $date_of_joining_month = $date_of_joining;
            $date_of_joining_mon = date("m", strtotime($date_of_joining_month));
//          exit;
            $qur1 = $this->db->query("SELECT total_monthly_leaves from designation_header_all where designation_id = '$designation' AND firm_id='$firm_id'");
//             echo $this->db->last_query();
            if ($qur1->num_rows() > 0) {
                $recrd1 = $qur1->row();
                $total_monthly_leaves = $recrd1->total_monthly_leaves;
            } else {
                $total_monthly_leaves = "";
            }

            if ($accrual_month < $date_of_joining_mon) {
                $rem_mon1 = ($date_of_joining_mon - $accrual_month);
                $rem_mon = (12 - $rem_mon1) - 1;
            } else if ($accrual_month > $date_of_joining_mon) {
                $rem_mon = ($accrual_month - $date_of_joining_mon) - 1;
            } else {
                $rem_mon = 12;
            }
//             echo $rem_mon;
//            echo $total_monthly_leaves;
            $total_leave = $rem_mon * $total_monthly_leaves;

            return $total_leave;
        } else {
            return FALSE;
        }
    }

    public function generate_user_id() {
        $user_id = 'U_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('user_header_all');
        $this->db->where('user_id', $user_id);
        $this->db->get();
//        echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return $this->generate_user_id();
        } else {
            return $user_id;
        }
    }

    public function get_virtual_firm() {

        $firm_id = $this->input->post('firm_id');

        $result = $this->db->get_where('partner_header_all', array('firm_id' => $firm_id))->result();

        foreach ($result as $row) {

            $is_virtual = $row->is_virtual;
        }

        $respnse['is_virtual'] = $is_virtual;

        echo json_encode($respnse);
    }

//start by bhava

    function view_performance_allowance() {
        $user_id = $this->input->post_get("employee_id");

        $query = $this->db->query("SELECT * FROM `h_perfomanceallowance`  where `user_id` = '$user_id'");
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width: 100%;" id="data_table" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Performance Bonus</th>
                                            <th>Start Month</th>
                                            <th>Financial Year</th>
                                            <th>Date</th>
                                          <th>Action</th>
                                        </tr>
                                    </thead>';
            $result1 = $query->result();
            foreach ($result1 as $row) {


                $data .= '<tr>
                    <td>' . $row->id . '</td>
                     <td>' . $row->value . '</td>
                    <td>' . $row->Month . '</td>
                    <td>' . $row->FYear . '</td>
                    <td>' . $row->Date_of_PA . '</td>
                    <td><button type="button" class="btn btn-icon-only btn-link" onclick="delete_PA(' . $row->id . ')"><i class="fa fa-trash font-red" style="font-size:22px;"></i></button></td>
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

    function delete_pa() {
        $id = $this->input->post_get("id");
        $array = array("id" => $id);
        $dele_trans = $this->Emp_model->delete_pa($array);
        if ($dele_trans != FALSE) {
            $response["body"] = "Deleted successfully.";
            $response["status"] = 200;
        } else {
            $response["body"] = "Failed to delete.";
            $response["status"] = 201;
        }echo json_encode($response);
    }

    function view_staff_loan() {
        $user_id = $this->input->post_get("emp_id1");

        $firm_id = $this->input->post_get("firm_id_a1");

        $query = $this->db->query("SELECT   * FROM `h_staffloan` where `user_id` = '$user_id'");
//        $query = $this->db->query("Select pay_type from m_salarytype join t_salarytype where m_salarytype .firm_id ='$firm_id'");
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width: 100%;" id="data_table1" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Loan Details</th>
                                            <th>Loan Amount</th>
                                            <th>EMI Amount</th>
                                            <th>Ficinal Year</th>
                                            <th>From Month</th>
                                            <th>Total Month</th>
                                            <th>Sanction Date</th>
                                            <th>Action</th>
                                              </tr>
                                    </thead>';
            $result2 = $query->result();
            foreach ($result2 as $row) {



                $data .= '<tr>
                    <td>' . $row->Loan_id . '</td>
                    <td>' . $row->loan_detail . '</td>
                    <td>' . $row->amount . '</td>
                    <td>' . $row->EMI_amt . '</td>
                    <td>' . $row->Fyear . '</td>
                    <td>' . $row->from_month . '</td>
                    <td>' . $row->total_month . '</td>
                    <td>' . $row->sanction_date . '</td>
                  <td><button type="button" class="btn btn-link btn-md" onclick="edit_loan(' . $row->Loan_id . ' )"> <i class="fa fa-pencil" style="font-size:22px;"></i></button></td>
                    </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_data"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    function Update_Staffloan() {
        $loan_detail = $this->input->post_get('loan_detail');
        $amount = $this->input->post_get('amount');
        $EMI_amt = $this->input->post_get('EMI_amt');
        $from_month = $this->input->post_get('from_month');
        $Fyear = $this->input->post_get('Fyear');
        $total_month = $this->input->post_get('total_month');
        $sanction_date = $this->input->post_get('sanction_date');
        $status = 1;
        $loan_id = $this->input->post("Loan_id");
        $loan_data = array(
            "loan_detail" => $loan_detail, "amount" => $amount, "EMI_amt" => $EMI_amt, "from_month" => $from_month, "Fyear" => $Fyear,
            "total_month" => $total_month, "sanction_date" => $sanction_date, "status" => $status
        );
        $this->db->where('Loan_id', $loan_id);
        $query = $this->db->update('t_staffloan', $loan_data);
        if ($query !== FALSE) {
            $response['message'] = 'success';
            $response['body'] = 'Staff loan udated successfully.';
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'Failed';
        }
    }

    function get_loan_details() {
        $Loan_id = $this->input->post('Loan_id');
        $where = array('Loan_id' => $Loan_id);
        $get_sal = $this->Emp_model->get_loan($where);
        if ($get_sal !== FALSE) {
            $result = $get_sal->row();
            $response['result'] = $result;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    function view_salary_details() {
        $user_id = $this->input->post_get("emp_id2");
        $firm_id = $this->input->post_get("firm_id_a2");
//        $query = $this->db->query("SELECT * FROM `t_salarytype` where `user_id` = '$user_id'");

        $query = $this->db->query("SELECT h_salarytype.id, h_salarytype.value,m_salarytype.pay_type FROM h_salarytype INNER JOIN m_salarytype ON h_salarytype.user_id='$user_id'");
        $this->db->last_query();
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width: 100%;" id="data_table2" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th> Amount</th>

                                              </tr>
                                    </thead>';
            $result2 = $query->result();
            foreach ($result2 as $row) {
                $data .= '<tr>
                    <td>' . $row->id . '</td>
                    <td>' . $row->pay_type . '</td>
                    <td>' . $row->value . '</td>
</tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_data1"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    function delete_Salary() {
        $id = $this->input->post_get("id");
        $array = array("id" => $id);
        $dele_trans = $this->Emp_model->delete_sal($array);
        if ($dele_trans != FALSE) {
            $response["body"] = "Delete successfully.";
            $response["status"] = 200;
        } else {
            $response["body"] = "Failed to delete";
            $response["status"] = 201;
        }echo json_encode($response);
    }

    function delete_deduction() {
        $id = $this->input->post_get("id");
        $array = array("id" => $id);
        $dele_trans = $this->Emp_model->delete_deduction($array);
        if ($dele_trans != FALSE) {
            $response["body"] = "Delete successfully.";
            $response["status"] = 200;
        } else {
            $response["body"] = "Failed to delete";
            $response["status"] = 201;
        }echo json_encode($response);
    }

    function view_Deu() {
        $user_id = $this->input->post_get("emp_id3");
        $firm_id = $this->input->post_get("firm_id_a3");
//        echo 'uiyi'.$firm_id;
//        $query = $this->db->query("SELECT * FROM `t_standarddeductions` where `user_id` ='$user_id'");
        $query = $this->db->query("SELECT h_standarddeductions.id, h_standarddeductions.value,m_standarddeductions.deduction FROM h_standarddeductions INNER JOIN m_standarddeductions ON h_standarddeductions .user_id='$user_id'");

        $data = '';

        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width: 100%;" id="data_table3" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th> Amount</th>
                                              </tr>
                                    </thead>';
            $result2 = $query->result();
            foreach ($result2 as $row) {
                $data .= '<tr>
                    <td>' . $row->id . '</td>
                    <td>' . $row->deduction . '</td>
                    <td>' . $row->value . '</td>


                    </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_data3"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    function add() {
        $firm_id = $this->input->post('emp_firm');
        $user_id = $this->input->post('emp_id');
        $value = $this->input->post('value');
        $Month = $this->input->post('Month');
        $FYear = $this->input->post('Fyear');
        $Date_of_PA = $this->input->post('Date_of_PA');

        $data_controller = array(
            "firm_id" => $firm_id,
            "user_id" => $user_id,
            "value" => $value,
            "Month" => $Month,
            "FYear" => $FYear,
            "Date_of_PA" => $Date_of_PA
        );
        $query = $this->db->query("select * from t_perfomanceallowance where user_id= '$user_id'");

        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $value = $result->value;
            $Month = $result->Month;
            $FYear = $result->FYear;
            $Date_of_PA = $result->Date_of_PA;
            $data_controller1 = array(
                "firm_id" => $firm_id,
                "user_id" => $user_id,
                "value" => $value,
                "Month" => $Month,
                "FYear" => $FYear,
                "Date_of_PA" => $Date_of_PA
            );
//            var_dump($data_controller1);
            $query2 = $this->Emp_model->h_PA($data_controller1);

            if ($query2 == true) {
                $q3 = $this->db->query("Delete from t_perfomanceallowance where user_id= '$user_id'");

                if ($this->Emp_model->add_user($data_controller)) {
                    $response['empdata'] = $data_controller;
                    $response["status"] = true;
                    $response["body"] = "Update target";
                } else {
                    $response['empdata'] = $data_controller;
                    $response["status"] = false;
                    $response["body"] = "Failed to update target.";
                }
                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed to update target.";
            }
        } else {

            if ($this->Emp_model->add_user($data_controller)) {
                $response['empdata'] = $data_controller;
                $response["status"] = true;
                $response["body"] = "Update Target";
            } else {
                $response['empdata'] = $data_controller;
                $response["status"] = false;
                $response["body"] = "Failed To Update Target";
            }
            $response["query"] = $this->db->last_query();
        }echo json_encode($response);
    }

    public function add_loan() {
        $firm_id = $this->input->post_get('emp_firm');
        $user_id = $this->input->post_get('emp_id1');
        $loan_detail = $this->input->post_get('loan_detail');
        $amount = $this->input->post_get('amount');
        $EMI_amt = $this->input->post_get('EMI_amt');
        $from_month = $this->input->post_get('from_month');
        $Fyear = $this->input->post_get('Fyear2');
        $total_month = $this->input->post_get('total_month');
        $sanction_date = $this->input->post_get('sanction_date');
        $status = 1;
        $loan_data = array(
            "firm_id" => $firm_id, "user_id" => $user_id, "loan_detail" => $loan_detail, "amount" => $amount, "EMI_amt" => $EMI_amt, "from_month" => $from_month, "Fyear" => $Fyear,
            "total_month" => $total_month, "sanction_date" => $sanction_date, "status" => $status
        );

        $query = $this->db->query("select * from t_staffloan where user_id= '$user_id'");
        if ($this->db->affected_rows() > 0) {

            $result = $query->row();
            $firm_id1 = $result->firm_id;
            $user_id1 = $result->user_id;
            $loan_detail = $result->loan_detail;
            $amount = $result->amount;
            $EMI_amt = $result->EMI_amt;
            $from_month = $result->from_month;
            $Fyear = $result->Fyear;
            $total_month = $result->total_month;
            $sanction_date = $result->sanction_date;
            $status = $result->status;
            $loandata = array(
                "firm_id" => $firm_id1, "user_id" => $user_id1, "loan_detail" => $loan_detail, "amount" => $amount, "EMI_amt" => $EMI_amt, "from_month" => $from_month, "Fyear" => $Fyear,
                "total_month" => $total_month, "sanction_date" => $sanction_date, "status" => $status
            );
            $q2 = $this->Emp_model->h_loan($loandata);
            if ($q2 == true) {
                $q3 = $this->db->query("Delete from t_staffloan where user_id= '$user_id'");

                if ($this->Emp_model->add_loan($loan_data)) {
                    $response['loandata'] = $loan_data;
                    $response["status"] = true;
                    $response["body"] = "Update target.";
                    $response["body"] = "Update target.";
                } else {
                    $response['loandata'] = $loan_data;
                    $response["status"] = false;
                    $response["body"] = "Failed to update target.";
                }
//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed to update target.";
            }
        } else {

            if ($this->Emp_model->add_loan($loan_data)) {
                $response['loandata'] = $loan_data;
                $response["status"] = true;
                $response["body"] = "Update Target";
            } else {
                $response['loandata'] = $loan_data;
                $response["status"] = false;
                $response["body"] = "Failed To Update Target";
            }
        }
        $response["query"] = $this->db->last_query();
        echo json_encode($response);
    }

    public function add_loan_info() {
        $firm_id = $this->input->post_get('emp_firm');
        $user_id = $this->input->post_get('emp_id1');
        $loan_detail = $this->input->post_get('loan_detail');
        $amount = $this->input->post_get('amount');
        $EMI_amt = $this->input->post_get('EMI_amt');
        $from_month = $this->input->post_get('from_month');
        $Fyear = $this->input->post_get('Fyear2');
        $total_month = $this->input->post_get('total_month');
        $sanction_date = $this->input->post_get('sanction_date');
        $status = 1;


        if (empty(trim($loan_detail))) {
            $response['id'] = 'loan_detail';
            $response['error'] = 'Enter Loan Detail';
            echo json_encode($response);
        } elseif (empty($amount)) {
            $response['id'] = 'amount';
            $response['error'] = 'Enter The Amount';
            echo json_encode($response);
        } elseif (empty($EMI_amt)) {
            $response['id'] = 'EMI_amt';
            $response['error'] = 'Enter EMI Amount';
            echo json_encode($response);
        } elseif (empty($from_month)) {
            $response['id'] = 'from_month';
            $response['error'] = 'Select Month';
            echo json_encode($response);
        } elseif (empty($Fyear)) {
            $response['id'] = 'Fyear2';
            $response['error'] = 'Select Year';
            echo json_encode($response);
        } elseif (empty($total_month)) {
            $response['id'] = 'total_month';
            $response['error'] = 'Enter The Total Month';
            echo json_encode($response);
        } elseif (empty($sanction_date)) {
            $response['id'] = 'sanction_date';
            $response['error'] = 'Select Date';
            echo json_encode($response);
        } else {

            $loan_data = array(
                "firm_id" => $firm_id, "user_id" => $user_id, "loan_detail" => $loan_detail, "amount" => $amount, "EMI_amt" => $EMI_amt, "from_month" => $from_month, "Fyear" => $Fyear,
                "total_month" => $total_month, "sanction_date" => $sanction_date, "status" => $status
            );
            if ($this->Emp_model->add_loan($loan_data)) {
                $response['saldata'] = $loan_data;
                $response["status"] = true;
                $response["body"] = "Loan added successfully.";
            } else {
                $response['saldata'] = $loan_data;
                $response["status"] = false;
                $response["body"] = "Failed To Add Loan";
            } echo json_encode($response);
        }
    }

    //Table for display loan data
    public function get_loandetails_info() {
        $firm_name = $this->input->post("firm_name");
        $user_id = $this->input->post("user_id");
//        $query = $this->db->query("select id,user_id,payroll_id,value from t_salarytype where user_id='$user_id' and firm_id ='$firm_name'");

        $query = $this->db->query("select * from t_staffloan where user_id= '$user_id'");

        $data = '';
        $data .= '<table style="width: 100%;" id="loandata_table" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Loan Details</th>
                                            <th>Amount</th>
                                            <th>EMI Amount</th>
                                            <th>Start Month</th>
                                            <th>Financial Year</th>
                                            <th>Total Month</th>
                                            <th>Sanction Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>';
        if ($this->db->affected_rows() > 0) {

            $result1 = $query->result();
            foreach ($result1 as $row) {
                $btn_del = "<button type='button' onclick='delete_loan(\"" . $row->Loan_id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-trash'  style='font-size: 23px !important; color: #d43535!important;'> </i> </button>";
                $btn_edit = "<button type='button' onclick='edit_loandata_btn(\"" . $row->Loan_id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-pencil'  style='font-size: 23px !important; color: #337ab7!important;'> </i> </button>";
                $month = $row->from_month;
                if ($month == 1) {
                    $month1 = "January";
                } else if ($month == 2) {
                    $month1 = "February";
                } else if ($month == 3) {
                    $month1 = "March";
                } else if ($month == 4) {
                    $month1 = "April";
                } else if ($month == 5) {
                    $month1 = "May";
                } else if ($month == 6) {
                    $month1 = "June";
                } else if ($month == 7) {
                    $month1 = "July";
                } else if ($month == 8) {
                    $month1 = "August";
                } else if ($month == 9) {
                    $month1 = "September";
                } else if ($month == 10) {
                    $month1 = "October";
                } else if ($month == 11) {
                    $month1 = "November";
                } else if ($month == 12) {
                    $month1 = "December";
                }
                 $newcompletionDate1 = date("d-m-yy", strtotime($row->sanction_date));
                $data .= '<tr>
                    <td>' . $row->Loan_id . '</td>
                    <td>' . $row->loan_detail . '</td>
                    <td>' . $row->amount . '</td>
                    <td>' . $row->EMI_amt . '</td>
                    <td>' . $month1 . '</td>
                    <td>' . $row->Fyear . '</td>
                    <td>' . $row->total_month . '</td>
                    <td>' . $newcompletionDate1 . '</td>
                    <td>' . $btn_del . $btn_edit . '</td>
                    </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_sal"] = $data;
        } else {
            $response["result_sal"] = $data;
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    //Delete indidvidual loan

    public function delete_loan() {
        $id = $this->input->post_get("id");
        $result_sal_data = $this->db->query("select * from t_staffloan where Loan_id='$id'");
        if ($this->db->affected_rows() > 0) {
            $date = date("Y/m/d");
            $result = $result_sal_data->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $loan_detail = $result->loan_detail;
            $amount = $result->amount;
            $EMI_amt = $result->EMI_amt;
            $Fyear = $result->Fyear;
            $from_month = $result->from_month;
            $total_month = $result->total_month;
            $sanction_date = $result->sanction_date;
//            $date = $result->date;
            $loandata = array("firm_id" => $firm_id, "user_id" => $user_id, "loan_detail" => $loan_detail, "amount" => $amount, "EMI_amt" => $EMI_amt, "Fyear" => $Fyear, "from_month" => $from_month, "total_month" => $total_month, "sanction_date" => $sanction_date);
//            var_dump($salarydata);
//            exit;
            $q2 = $this->Emp_model->h_loan($loandata);
            if ($q2 == true) {
                $q3 = $this->db->query("Delete from t_staffloan where Loan_id= '$id'");
                if ($q3 == true) {
                    $response["status"] = 200;
                    $response["body"] = "Delete Successfully";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed To Delete Salary Type";
                }//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed To Delete";
            }
        } else {
            $response["body"] = "Failed To Update Target";
        }echo json_encode($response);
    }

    //get details of loan on form

    public function get_loanedit_details() {
//        echo"update";
        $id = $this->input->post('id');
        $where = array('Loan_id' => $id);
        $get_sal = $this->Emp_model->get_loandetails_list($where);
        if ($get_sal !== FALSE) {
            $result = $get_sal->row();
            $response['result'] = $result;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function UpadteLoanInfo() {
//       echo "update";
        $id = $this->input->post("id_loan");


        $result_sal_data = $this->db->query("select * from t_staffloan where Loan_id='$id'");
//        echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
//            $date = date("Y/m/d");
            $result = $result_sal_data->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $loan_detail = $result->loan_detail;
            $amount = $result->amount;
            $EMI_amt = $result->EMI_amt;
            $Fyear = $result->Fyear;
            $from_month = $result->from_month;
            $total_month = $result->total_month;
            $sanction_date = $result->sanction_date;
            $loandata = array("firm_id" => $firm_id, "user_id" => $user_id, "loan_detail" => $loan_detail, "amount" => $amount, "EMI_amt" => $EMI_amt, "FYear" => $Fyear, "from_month " => $from_month, "total_month " => $total_month, "sanction_date" => $sanction_date);
            $q2 = $this->Emp_model->h_loan($loandata);
            if ($q2 == true) {

                $q3 = $this->db->query("Delete from t_staffloan where Loan_id= '$id'");
                $emp_id1 = $this->input->post("emp_id1");
                $loan_detail1 = $this->input->post_get('loan_detail');
                $amount1 = $this->input->post_get('amount');
                $EMI_amt1 = $this->input->post_get('EMI_amt');
                $from_month1 = $this->input->post_get('from_month');
                $Fyear1 = $this->input->post_get('Fyear2');
                $total_month1 = $this->input->post_get('total_month');
                $sanction_date1 = $this->input->post_get('sanction_date');
                $loan_data = array(
                    "user_id" => $emp_id1,
                    "firm_id" => $firm_id,
                    "loan_detail" => $loan_detail1,
                    "amount" => $amount1,
                    "EMI_amt" => $EMI_amt1,
                    "Fyear" => $Fyear1,
                    "from_month" => $from_month1,
                    "total_month" => $total_month1,
                    "sanction_date" => $sanction_date1,
                );
//                var_dump($loan_data);

                if ($q3 == true) {
                    $q4 = $this->Emp_model->add_loan($loan_data);
                    $response["status"] = 200;
                    $response["body"] = "Update Successfully";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed to update loan details";
                }//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed To Update";
            }
        } else {
            $response["body"] = "Failed To Update Target";
        }echo json_encode($response);
    }

    public function add_salary() {
        $firm_id = $this->input->post('emp_firm');
        $user_id = $this->input->post('emp_id2');
        $payroll_id = $this->input->post('sal_options');
        $value = $this->input->post('value');
//        $ot_applicable_sts = $this->input->post('ot_applicable_sts');
//        $salary_calcu = $this->input->post('salary_calcu');


        $check_salary = $this->db->query("select payroll_id from t_salarytype where  user_id='$user_id' and payroll_id='$payroll_id'");
        if ($check_salary->num_rows() >= 1) {
            $response["status"] = 200;
            $response["body"] = "For this employee salary type is already Set";
            echo json_encode($response);
            exit;
        }

        if (empty(trim($payroll_id))) {
            $response['id'] = 'sal_options';
            $response['error'] = 'Select Salary Type';
            echo json_encode($response);
        } elseif (empty($value)) {
            $response['id'] = 'value_sal';
            $response['error'] = 'Enter the Amount';
            echo json_encode($response);
        } else {

            $salary_data = array(
                "firm_id" => $firm_id,
                "user_id" => $user_id,
                "payroll_id" => $payroll_id,
                "value" => $value
            );



            if ($this->Emp_model->add_salary($salary_data)) {
                $response['saldata'] = $salary_data;
                $response["status"] = true;
                $response["body"] = "Salary added successfully.";
            } else {
                $response['saldata'] = $salary_data;
                $response["status"] = false;
                $response["body"] = "Failed To Add Salary";
            } echo json_encode($response);
        }
    }

    public function add_salary_update() {
        $firm_id = $this->input->post('emp_firm');
        $user_id = $this->input->post('emp_id2');
        $payroll_id = $this->input->post('sal_options');
        $value11 = $this->input->post('value');
//        $ot_applicable_sts = $this->input->post('ot_applicable_sts');
//        $salary_calcu = $this->input->post('salary_calcu');

        if (empty(trim($payroll_id))) {
            $response['id'] = 'sal_options';
            $response['error'] = 'Select Salary Type';
            echo json_encode($response);
        } elseif (empty($value11)) {
            $response['id'] = 'value_sal';
            $response['error'] = 'Enter the Amount';
            echo json_encode($response);
        } else {

            $check_salary = $this->db->query("select * from t_salarytype where  user_id='$user_id' and payroll_id='$payroll_id'");
            if ($this->db->affected_rows() > 0) {
                $date = date("Y/m/d");
                $check_salary_data = $check_salary->row();
                $user_id = $check_salary_data->user_id;
                $firm_id = $check_salary_data->firm_id;
                $payroll_id = $check_salary_data->payroll_id;
                $value = $check_salary_data->value;
                $salarydata = array("firm_id" => $firm_id, "user_id" => $user_id, "payroll_id" => $payroll_id, "value" => $value, "date" => $date);

                $q2 = $this->Emp_model->h_salary($salarydata);
                if ($q2 == true) {

                    $q3 = $this->db->query("Delete from t_salarytype where user_id= '$user_id' and payroll_id='$payroll_id'");
                    $sal_update_data = array(
                        "value" => $value11,
                        "user_id" => $user_id,
                        "firm_id" => $firm_id,
                        "payroll_id" => $payroll_id,
                    );

                    if ($q3 == true) {
                        $q4 = $this->Emp_model->add_salary($sal_update_data);
//                        $this->db->query("update `user_header_all` set `overtime_applicable_sts`='$ot_applicable_sts',`salary_calculation`='$salary_calcu' where `user_id`='$user_id'");
                        $response["status"] = true;
                        $response["body"] = "Update successfully.";
                    } else {
                        $response["status"] = false;
                        $response["body"] = "Failed to update salary type.";
                    }//                $response["query"] = $this->db->last_query();
                } else {
                    $response["body"] = "Failed To Update";
                }echo json_encode($response);
            } else {
                $sal_update_data = array(
                    "value" => $value11,
                    "user_id" => $user_id,
                    "firm_id" => $firm_id,
                    "payroll_id" => $payroll_id,
                );
                if ($this->Emp_model->add_salary($sal_update_data)) {
//                    $this->db->query("update `user_header_all` set `overtime_applicable_sts`='$ot_applicable_sts' where `user_id`='$user_id'");
                    $response["status"] = true;
                    $response["body"] = "Update successfully.";
                } else {
                    $response["status"] = false;
                    $response["body"] = "Failed to update salary type.";
                }echo json_encode($response);
            }
        }
    }

    function get_sal_info() {
        $firm_name = $this->input->post("firm_name");
        $user_id = $this->input->post("user_id");
//        $query = $this->db->query("select id,user_id,payroll_id,value from t_salarytype where user_id='$user_id' and firm_id ='$firm_name'");
        $query = $this->db->query("select id,user_id,value,
             (select pay_type from m_salarytype where m_salarytype.earning_id=t_salarytype.payroll_id)as payroll_id
             from t_salarytype where user_id= '$user_id'");
        $data = '';
        $data .= '<table style="width: 100%;" id="sal_table" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Salary type</th>
                                            <th>Salary</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>';
        if ($this->db->affected_rows() > 0) {

            $result1 = $query->result();
            foreach ($result1 as $row) {
                $btn_del = "<button type='button' onclick='delete_saltype(\"" . $row->id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-trash'  style='font-size: 23px !important; color: #d43535!important;'> </i> </button>";
//                $btn_edit = "<button type='button' onclick='edit_saltype_btn(\"" . $row->id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-pencil'  style='font-size: 23px !important; color: #337ab7!important;'> </i> </button>";

                $data .= '<tr>
                    <td>' . $row->id . '</td>
                    <td>' . $row->payroll_id . '</td>
                    <td>' . $row->value . '</td>
                    <td>' . $btn_del . '</td>
                    </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_sal"] = $data;
        } else {
            $response["result_sal"] = $data;
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    function get_deduction_info() { //table for deduction details
        $firm_name = $this->input->post("firm_name");
        $user_id = $this->input->post("user_id");
//        $query = $this->db->query("select id,user_id,payroll_id,value from t_salarytype where user_id='$user_id' and firm_id ='$firm_name'");
        $query = $this->db->query("select id,user_id,value,
             (select deduction from m_standarddeductions where m_standarddeductions.deduction_id=t_standarddeductions.deduction_id)as deduction
             from t_standarddeductions where user_id= '$user_id'");
//         echo $this->db->last_query();
        $data = '';
        $data .= '<table style="width: 100%;" id="ded_table" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Deduction type</th>
                                            <th>Salary</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>';
        if ($this->db->affected_rows() > 0) {

            $result1 = $query->result();
            foreach ($result1 as $row) {
                $btn_del = "<button type='button' onclick='delete_dedtype(\"" . $row->id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-trash'  style='font-size: 23px !important; color: #d43535!important;'> </i> </button>";
//                $btn_edit = "<button type='button' onclick='edit_dedtype_btn(\"" . $row->id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-pencil'  style='font-size: 23px !important; color: #337ab7!important;'> </i> </button>";

                $data .= '<tr>
                    <td>' . $row->id . '</td>
                    <td>' . $row->deduction . '</td>
                    <td>' . $row->value . '</td>
                    <td>' . $btn_del . '</td>
                    </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_sal"] = $data;
        } else {
            $response["result_sal"] = $data;
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    public function delete_dedtype() {
        $id = $this->input->post_get("id");
        $result_sal_data = $this->db->query("select * from t_standarddeductions where id='$id'");
        if ($this->db->affected_rows() > 0) {
            $date = date("Y/m/d");
            $result = $result_sal_data->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $deduction_id = $result->deduction_id;
            $value = $result->value;
            $duedata = array("firm_id" => $firm_id, "user_id" => $user_id, "deduction_id" => $deduction_id, "value" => $value, "date" => $date);
            $q2 = $this->Emp_model->h_due_details($duedata);
            if ($q2 == true) {
                $q3 = $this->db->query("Delete from t_standarddeductions where id= '$id'");
                if ($q3 == true) {
                    $response["status"] = 200;
                    $response["body"] = "Delete successfully.";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed to delete deduction type.";
                }//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed To Delete";
            }
        } else {
            $response["body"] = "Failed To Update Target";
        }echo json_encode($response);
    }

    public function get_ded_details() {
//        echo"update";
        $id = $this->input->post('id');
        $where = array('id' => $id);
        $get_sal = $this->Emp_model->get_ded_type_list($where);
        if ($get_sal !== FALSE) {
            $result = $get_sal->row();
            $response['result'] = $result;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function delete_saltype() {
        $id = $this->input->post_get("id");
        $result_sal_data = $this->db->query("select * from t_salarytype where id='$id'");
        if ($this->db->affected_rows() > 0) {
            $date = date("Y/m/d");
            $result = $result_sal_data->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $payroll_id = $result->payroll_id;
            $value = $result->value;
//            $date = $result->date;
            $salarydata = array("firm_id" => $firm_id, "user_id" => $user_id, "payroll_id" => $payroll_id, "value" => $value, "date" => $date);
//            var_dump($salarydata);
//            exit;
            $q2 = $this->Emp_model->h_salary($salarydata);
            if ($q2 == true) {
                $q3 = $this->db->query("Delete from t_salarytype where id= '$id'");
                if ($q3 == true) {
                    $response["status"] = 200;
                    $response["body"] = "Delete successfully.";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed to delete salary type.";
                }//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed To Delete";
            }
        } else {
            $response["body"] = "Failed To Update Target";
        }echo json_encode($response);
    }

    public function get_sal_details() {
//        echo"update";
        $id = $this->input->post('id');
        $where = array('id' => $id);
        $get_sal = $this->Emp_model->get_sal_type_list($where);
        if ($get_sal !== FALSE) {
            $result = $get_sal->row();
            $response['result'] = $result;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function UpadteSalInfo() {
//       echo "update";
        $id = $this->input->post_get("id");
        $value11 = $this->input->post_get("value");

        $result_sal_data = $this->db->query("select * from t_salarytype where id='$id'");
        if ($this->db->affected_rows() > 0) {
            $date = date("Y/m/d");
            $result = $result_sal_data->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $payroll_id = $result->payroll_id;
            $value = $result->value;
            $salarydata = array("firm_id" => $firm_id, "user_id" => $user_id, "payroll_id" => $payroll_id, "value" => $value, "date" => $date);
            $q2 = $this->Emp_model->h_salary($salarydata);
            if ($q2 == true) {

                $q3 = $this->db->query("Delete from t_salarytype where id= '$id'");
                $sal_update_data = array(
                    "value" => $value11,
                    "user_id" => $user_id,
                    "firm_id" => $firm_id,
                    "payroll_id" => $payroll_id,
                );

                if ($q3 == true) {
                    $q4 = $this->Emp_model->add_salary($sal_update_data);
                    $response["status"] = 200;
                    $response["body"] = "Update Successfully";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed To Update Salary Type";
                }//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed To Update";
            }
        } else {
            $response["body"] = "Failed To Update Target";
        }echo json_encode($response);
    }

    public function UpadteDedInfo() {
//       echo "update";
        $id = $this->input->post_get("id1");
        $value11 = $this->input->post_get("value");

        $result_sal_data = $this->db->query("select * from t_standarddeductions where id='$id'");
        if ($this->db->affected_rows() > 0) {
            $date = date("Y/m/d");
            $result = $result_sal_data->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $deduction_id = $result->deduction_id;
            $value = $result->value;
            $duedata = array("firm_id" => $firm_id, "user_id" => $user_id, "deduction_id" => $deduction_id, "value" => $value, "date" => $date);
//            var_dump($duedata);exit;
            $q2 = $this->Emp_model->h_due_details($duedata);
            if ($q2 == true) {

                $q3 = $this->db->query("Delete from t_standarddeductions where id= '$id'");
                $due_data = array(
                    "value" => $value11,
                    "user_id" => $user_id,
                    "firm_id" => $firm_id,
                    "deduction_id" => $deduction_id,
                );

                if ($q3 == true) {
                    $q4 = $this->Emp_model->due_details($due_data);
                    $response["status"] = 200;
                    $response["body"] = "Update successfully";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed to update deduction type.";
                }//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed To Update";
            }
        } else {
            $response["body"] = "Failed To Update Target";
        }echo json_encode($response);
    }

    public function add_due() {
        $firm_id = $this->input->post_get('emp_firm');
        $user_id = $this->input->post_get('emp_id3');
        $deduction_id = $this->input->post_get('ded_options');
//        $value = $this->input->post_get('value');
        $deduction_check = $this->input->post_get('deduction_check');
        if ($deduction_check == 1) {
            $value = $this->input->post_get('value');
        } else {
            $value = $this->input->post_get('value_deduct_percentage');
        }




        $check_deduction = $this->db->query("select deduction_id from t_standarddeductions where  user_id='$user_id' and deduction_id='$deduction_id'");
        if ($check_deduction->num_rows() >= 1) {
            $response["status"] = 200;
            $response["body"] = "For this employee Deduction type is already Set";
            echo json_encode($response);
            exit;
        }



        if (empty(trim($user_id))) {
            $response['id'] = 'emp_id3';
            $response['error'] = 'Select Employee';
            echo json_encode($response);
            exit;
        } elseif (empty(trim($deduction_id))) {
            $response['id'] = 'ded_options';
            $response['error'] = 'Select Deduction Type';
            echo json_encode($response);
            exit;
        } elseif ($deduction_check == 1 && empty($value)) {
            $response['id'] = 'value_ded';
            $response['error'] = 'Enter The Amount';
            echo json_encode($response);
            exit;
        } elseif ($deduction_check == 2 && empty($value)) {
            $response['id'] = 'value_deduct_percentage1';
            $response['error'] = 'Enter The Percentage';
            echo json_encode($response);
            exit;
        } elseif ($deduction_check == 2 && !preg_match("/^(100(\.0{0,2})?|(\d|[1-9]\d)(\.\d{0,2})?)$/", $value)) {
            $response['id'] = 'value_deduct_percentage1';
            $response['error'] = 'Enter Proper Percentage In Between 0.1 To 100';
            echo json_encode($response);
//            exit;
        } else {
            $due_data = array(
                "firm_id" => $firm_id, "user_id" => $user_id, "deduction_id" => $deduction_id, "value" => $value, "deduction_type" => $deduction_check
            );
//            var_dump($due_data);
//            exit;
            if ($this->Emp_model->due_details($due_data)) {
                $response['saldata'] = $due_data;
                $response["status"] = true;
                $response["body"] = "Update Target";
            } else {
                $response['saldata'] = $due_data;
                $response["status"] = false;
                $response["body"] = "Failed To Update Target";
            } echo json_encode($response);
        }
    }

    public function add_due_update() {
        $firm_id = $this->input->post('emp_firm');
        $user_id = $this->input->post('emp_id3');
        $deduction_id = $this->input->post('ded_options');
//        $value11 = $this->input->post('value');

        $deduction_check = $this->input->post_get('deduction_check');
        if ($deduction_check == 1) {
            $value11 = $this->input->post_get('value');
        } else {
            $value11 = $this->input->post_get('value_deduct_percentage');
        }

        if ($deduction_check == 1 && empty($value11)) {
            $response['id'] = 'value_ded';
            $response['error'] = 'Enter The Amount';
            echo json_encode($response);
//            exit;
        } else if ($deduction_check == 2 && empty($value11)) {
            $response['id'] = 'value_deduct_percentage1';
            $response['error'] = 'Enter The Percentage';
            echo json_encode($response);
//            exit;
        } elseif ($deduction_check == 2 && !preg_match("/^(100(\.0{0,2})?|(\d|[1-9]\d)(\.\d{0,2})?)$/", $value11)) {
            $response['id'] = 'value_deduct_percentage1';
            $response['error'] = 'Enter proper percentage in between 0.1 to 100';
            echo json_encode($response);
//            exit;
        } else if (empty(trim($deduction_id))) {
            $response['id'] = 'ded_options';
            $response['error'] = 'Select Deduction Type';
            echo json_encode($response);
        }
//        elseif (empty($value11)) {
//            $response['id'] = 'ded_value';
//            $response['error'] = 'Enter the Amount';
//            echo json_encode($response);
//        }
        else {

            $check_deduction = $this->db->query("select * from t_standarddeductions where  user_id='$user_id' and deduction_id='$deduction_id'");
            if ($this->db->affected_rows() > 0) {
                $date = date("Y/m/d");
                $check_salary_data = $check_deduction->row();
                $user_id = $check_salary_data->user_id;
                $firm_id = $check_salary_data->firm_id;
                $deduction_id = $check_salary_data->deduction_id;
                $value = $check_salary_data->value;
                $deduction_type = $check_salary_data->deduction_type;
                $duedata = array("firm_id" => $firm_id, "user_id" => $user_id, "deduction_id" => $deduction_id, "value" => $value, "date" => $date, "deduction_type" => $deduction_type);
//            var_dump($duedata);
                $q2 = $this->Emp_model->h_due_details($duedata);
                if ($q2 == true) {

                    $q3 = $this->db->query("Delete from t_standarddeductions where user_id= '$user_id' and deduction_id='$deduction_id'");
                    $due_data = array(
                        "value" => $value11,
                        "user_id" => $user_id,
                        "firm_id" => $firm_id,
                        "deduction_id" => $deduction_id,
                        "deduction_type" => $deduction_type,
                    );

                    if ($q3 == true) {
                        $q4 = $this->Emp_model->due_details($due_data);
                        $response["status"] = true;
                        $response["body"] = "Update successfully";
                    } else {
                        $response["status"] = false;
                        $response["body"] = "Failed to update salary type.";
                    }
                } else {
                    $response["body"] = "Failed To Update";
                }echo json_encode($response);
            } else {
                $due_data = array(
                    "value" => $value11,
                    "user_id" => $user_id,
                    "firm_id" => $firm_id,
                    "deduction_id" => $deduction_id,
                    "deduction_type" => $deduction_type,
                );
                if ($this->Emp_model->due_details($due_data)) {
                    $response["status"] = true;
                    $response["body"] = "Updates successfully.";
                } else {
                    $response["status"] = false;
                    $response["body"] = "Failed to update salary type.";
                }echo json_encode($response);
            }
        }
    }

    //end by bhava
    public function add_emp_hq() {
        $user_id = $this->generate_user_id();
		$form_type = $this->input->post('firm_user_type');
		// $form_type = $this->input->post('firm_user_type');
        $user_name = $this->input->post('user_name');
        $firm_id = $this->input->post('firm_name');
        $mobile_no = $this->input->post('mobile_no');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $state = $this->input->post('state');
        $city = $this->input->post('city');
        $country = $this->input->post('country');
        $designation = $this->input->post('designation');
        $date_of_joining = $this->input->post('date_of_joining');
        $skills = $this->input->post('skill_set');
        $star_rating = $this->input->post('star_rating');
        $senior_emp = $this->input->post('senior_emp');
        $probation_period = $this->input->post('probation_period');
        $training_period = $this->input->post('training_period');
        $prob_date_first = $this->input->post('prob_date_first');
        $prob_date_second = $this->input->post('prob_date_second');
        $training_period_first = $this->input->post('training_period_first');
        $training_period_second = $this->input->post('training_period_second');
        $password = rand(100, 1000);
        //-- New code added for password encryption
        $hash_password = password_hash($password, PASSWORD_BCRYPT);
        $ot_applicable_sts = $this->input->post('ot_applicable_sts');
        $salary_calcu = $this->input->post('salary_calcu');
        $holiday_applible_sts = $this->input->post('holiday_applible_sts');
        $Paddress = $this->input->post('Paddress');
        $sandwichLeave = $this->input->post('sandwichLeave');
        $gpsoff = $this->input->post('gpsoff');

        //Newly add details in basic info
        $spouse_name = $this->input->post('fh_name');
        $date_of_birth = $this->input->post('dob');
        $gender = $this->input->post('gender');
        $uan_no = $this->input->post('uan');
        $pan_no = $this->input->post('pan');
        $dept_name = $this->input->post('dept_name');
        $account_no = $this->input->post('ac_no');
        $adhar_no = $this->input->post('adhar_no');
        $bank_name = $this->input->post('bank_name');

        $applicable_status = $this->input->post('attendance_employee');

        $work_hour_employee = $this->input->post('work_hour_employee');
        $fixed_time = $this->input->post('fixed_time');

        $work_hour_schedule = $this->input->post('work_hour_schedule');

        $week_days1 = $this->input->post('week_days1');
        $select_schedule_type1 = $this->input->post('select_schedule_type1');
        $select_fixed_hour1 = $this->input->post('select_fixed_hour1');

        $week_days2 = $this->input->post('week_days2');
        $select_schedule_type2 = $this->input->post('select_schedule_type2');
        $select_fixed_hour2 = $this->input->post('select_fixed_hour2');

        $week_days3 = $this->input->post('week_days3');
        $select_schedule_type3 = $this->input->post('select_schedule_type3');
        $select_fixed_hour3 = $this->input->post('select_fixed_hour3');

        $week_days4 = $this->input->post('week_days4');
        $select_schedule_type4 = $this->input->post('select_schedule_type4');
        $select_fixed_hour4 = $this->input->post('select_fixed_hour4');

        $week_days5 = $this->input->post('week_days5');
        $select_schedule_type5 = $this->input->post('select_schedule_type5');
        $select_fixed_hour5 = $this->input->post('select_fixed_hour5');

        $week_days6 = $this->input->post('week_days6');
        $select_schedule_type6 = $this->input->post('select_schedule_type6');
        $select_fixed_hour6 = $this->input->post('select_fixed_hour6');

        $week_days7 = $this->input->post('week_days7');
        $select_schedule_type7 = $this->input->post('select_schedule_type7');
        $select_fixed_hour7 = $this->input->post('select_fixed_hour7');

        $fixed_hour = $this->input->post('fixed_hour');
        $select_fixed_hour = $this->input->post('select_fixed_hour');

        if ($applicable_status == 2 || $applicable_status == 3 || $applicable_status == 4 || $applicable_status == 5) {
            $work_hour_employee = '';
            $fixed_time = '';
            $work_hour_schedule = '';
            $fixed_hour = '';
            $select_fixed_hour = '';
        }

        $dbFormat = date('HH:MM:SS');


        $leave_allow_permission = $this->input->post('l_a_permit');

        $allot_permited = $this->input->post('allot_permited');

        $generate_enq_permit = $this->input->post('generate_enq_permit');
        // $star_rating = $this->input->post('star_rating');
        $task_approve = $this->input->post('task_approve');
        $task_sign = $this->input->post('task_sign');

        // var_dump($work_on_service1);
        // var_dump($work_on_service);
        //task
        if (!isset($_POST['add_task'])) {
            $add_task = '0';
        } else {
            $add_task = '1';
        }

        if (!isset($_POST['updt_task'])) {
            $updt_task = '0';
        } else {
            $updt_task = '1';
        }


        if (!isset($_POST['del_task'])) {
            $del_task = '0';
        } else {
            $del_task = '1';
        }
        //task assignment
        if (!isset($_POST['add_task_assignment'])) {
            $add_task_assignment = '0';
        } else {
            $add_task_assignment = '1';
        }


        if (!isset($_POST['updt_task_assignment'])) {
            $updt_task_assignment = '0';
        } else {
            $updt_task_assignment = '1';
        }
        if (!isset($_POST['del_task_assignment'])) {
            $del_task_assignment = '0';
        } else {
            $del_task_assignment = '1';
        }
        //Due date
        if (!isset($_POST['add_due_date'])) {
            $add_due_date = '0';
        } else {
            $add_due_date = '1';
        }
        if (!isset($_POST['updt_due_date'])) {
            $updt_due_date = '0';
        } else {
            $updt_due_date = '1';
        }
        if (!isset($_POST['del_due_date'])) {
            $del_due_date = '0';
        } else {
            $del_due_date = '1';
        }
        //Due Date task Assignment
        if (!isset($_POST['add_due_date_task'])) {
            $add_due_date_task = '0';
        } else {
            $add_due_date_task = '1';
        }


        if (!isset($_POST['updt_due_date_task'])) {
            $updt_due_date_task = '0';
        } else {
            $updt_due_date_task = '1';
        }


        if (!isset($_POST['del_due_date_task'])) {
            $del_due_date_task = '0';
        } else {
            $del_due_date_task = '1';
        }
        //services
        if (!isset($_POST['add_service'])) {
            $add_service = '0';
        } else {
            $add_service = '1';
        }


        if (!isset($_POST['updt_service'])) {
            $updt_service = '0';
        } else {
            $updt_service = '1';
        }


        if (!isset($_POST['del_service'])) {
            $del_service = '0';
        } else {
            $del_service = '1';
        }

        //template store
        if (!isset($_POST['add_template_store'])) {
            $add_template_store = '0';
        } else {
            $add_template_store = '1';
        }


        if (!isset($_POST['updt_template_store'])) {
            $updt_template_store = '0';
        } else {
            $updt_template_store = '1';
        }


        if (!isset($_POST['del_template_store'])) {
            $del_template_store = '0';
        } else {
            $del_template_store = '1';
        }

        //Knowledge store
        if (!isset($_POST['add_knowledge_store'])) {
            $add_knowledge_store = '0';
        } else {
            $add_knowledge_store = '1';
        }


        if (!isset($_POST['updt_knowledge_store'])) {
            $updt_knowledge_store = '0';
        } else {
            $updt_knowledge_store = '1';
        }

        if (!isset($_POST['del_knowledge_store'])) {
            $del_knowledge_store = '0';
        } else {
            $del_knowledge_store = '1';
        }

        //employee
        if (!isset($_POST['add_employee'])) {
            $add_employee = '0';
        } else {
            $add_employee = '1';
        }


        if (!isset($_POST['updt_employee'])) {
            $updt_employee = '0';
        } else {
            $updt_employee = '1';
        }


        if (!isset($_POST['del_employee'])) {
            $del_employee = '0';
        } else {
            $del_employee = '1';
        }
        //customer
        if (!isset($_POST['add_customer'])) {
            $add_customer = '0';
        } else {
            $add_customer = '1';
        }


        if (!isset($_POST['updt_customer'])) {
            $updt_customer = '0';
        } else {
            $updt_customer = '1';
        }


        if (!isset($_POST['del_customer'])) {
            $del_customer = '0';
        } else {
            $del_customer = '1';
        }
        //configure warning
        if (!isset($_POST['add_warining'])) {
            $add_warining = '0';
        } else {
            $add_warining = '1';
        }


        if (!isset($_POST['updt_warning'])) {
            $updt_warning = '0';
        } else {
            $updt_warning = '1';
        }


        if (!isset($_POST['del_warning'])) {
            $del_warning = '0';
        } else {
            $del_warning = '1';
        }
        $emp_code=$this->input->post('emp_code');
        $result = $this->firm_model->get_firm_id();
        $length_address = strlen($address);
        $email_check = $this->check_email_avalibility1($email);
        // var_dump($firm_id);
        if (isset($firm_id)) {
            if (empty(trim($firm_id))) {
                $response['id'] = 'firm_name';
                $response['error'] = 'Select Office';
                echo json_encode($response);
                exit;
            }
        } else if ($result !== false) {
            // $firm_id = $result['firm_id'];
            //$boss_id_hq = $result['boss_id'];
            $firm_id = $result['firm_id'];
        }

        if (empty(trim($user_name))) {
            $response['id'] = 'user_name';
            $response['error'] = 'Enter User Name';
            echo json_encode($response);
            exit;
		}
		elseif(empty($form_type)){
           $response['id']='firm_user_type';
           $response['error']='Select Form Type';
           echo json_encode($response);
           exit;
       }
		elseif (empty($spouse_name)) {
            $response['id']='fh_name';
            $response['error']='Enter Father/Husband Name';
            echo json_encode($response);
            exit;
        }elseif(empty($date_of_birth)){
            $response['id']='dob';
            $response['error']="Enter Date Of Birth";
            echo json_encode($response);
            exit;
        }elseif(empty($gender)){
            $response['id']='gender';
            $response['error']='Select Gender';
            echo json_encode($response);
            exit;
        }

        else if (empty(trim($mobile_no))) {
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter Mobile Number';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^\d{10}$/", $mobile_no)) {
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter Ten Digit Mobile Number';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[6-9][0-9]{9}$/", $mobile_no)) {
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter Proper Digit Mobile Number';
            echo json_encode($response);
            exit;
        }elseif(empty($uan_no)){
            $response['id']='uan';
            $response['error']='Enter Uan Number';
            echo json_encode($response);
            exit;
        } elseif(empty($pan_no)){
            $response['id']='pan';
            $response['error']='Enter Pan Number';
            echo json_encode($response);
            exit;
        }elseif(empty($dept_name)){
            $response['id']='dept_name';
            $response['error']='Enter Department Name';
            echo json_encode($response);
            exit;
        }elseif(empty($account_no)){
            $response['id']='ac_no';
            $response['error']='Enter Account Number';
            echo json_encode($response);
            exit;
        }elseif(empty($adhar_no)){
            $response['id']='adhar_no';
            $response['error']='Enter Adhar Card Number';
            echo json_encode($response);
            exit;
        }elseif(empty($bank_name)){
            $response['id']='bank_name';
            $response['error']='Enter Bank Name';
            echo json_encode($response);
            exit;
        }elseif(empty($emp_code)){
            $response['id']='emp_code';
            $response['error']='Enter EMP Code';
            echo json_encode($response);
            exit;
        } elseif (empty(trim($email))) {
            $response['id'] = 'email';
            $response['error'] = 'Enter Email Id';
            echo json_encode($response);
            exit;
        }elseif (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) {
            $response['id'] = 'email';
            $response['error'] = "Invalid Email Format";
            echo json_encode($response);
        }
        // elseif ($email_check==true) {
        //     $response['id'] = 'email';
        //     $response['error'] = "Email Is Already Exist";
        //     echo json_encode($response);
        // } 
        else if (empty(trim($address))) {
            $response['id'] = 'address';
            $response['error'] = 'Enter Current Address';
            echo json_encode($response);
            exit;
        }else if (empty(trim($Paddress))) {
            $response['id'] = 'Paddress';
            $response['error'] = 'Enter Permanant Address';
            echo json_encode($response);
            exit;
        } else if ($length_address < 5) {
            $response['id'] = 'address';
            $response['error'] = 'Entered Address Must Be Greater Than 5 Characters';
            echo json_encode($response);
            exit;
        }
//        elseif (!preg_match("/^[a-zA-Z0-9''-., ]*\z/", $address)) {
//            $response['id'] = 'address';
//            $response['error'] = 'Only Character Is Allowed';
//            echo json_encode($response);
//            exit;
//        }
        else if (empty(trim($country))) {
            $response['id'] = 'country';
            $response['error'] = 'Enter country';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[a-zA-Z,. ]*\z/", $country)) {
            $response['id'] = 'country';
            $response['error'] = 'Only Character Is Allowed';
            echo json_encode($response);
            exit;
        } else if (empty(trim($state))) {
            $response['id'] = 'state';
            $response['error'] = 'Enter state';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[a-zA-Z ]*\z/", $state)) {
            $response['id'] = 'state';
            $response['error'] = 'Only Character Is Allowed';
            echo json_encode($response);
            exit;
        } else if (empty(trim($city))) {
            $response['id'] = 'city';
            $response['error'] = 'Enter city';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[a-zA-Z,. ]*\z/", $city)) {
            $response['id'] = 'city';
            $response['error'] = 'Only Character Is Allowed';
            echo json_encode($response);
            exit;
        } else if (empty(trim($designation))) {
            $response['id'] = 'designation';
            $response['error'] = 'Select Designation';
            echo json_encode($response);
            exit;
        } else if (empty(trim($date_of_joining))) {
            $response['id'] = 'date_of_joining';
            $response['error'] = 'Select Date';
            echo json_encode($response);
            exit;
        } else if (empty(trim($skills))) {
            $response['id'] = 'skill_set';
            $response['error'] = 'Enter Skill Set';
            echo json_encode($response);
            exit;
        } else if (empty(trim($senior_emp))) {
            $response['id'] = 'senior_emp';
            $response['error'] = 'Select Senior User';
            echo json_encode($response);
            exit;
        } else if (empty(trim($ot_applicable_sts))) {
            $response['id'] = 'ot_applicable_sts';
            $response['error'] = 'Please Select';
            echo json_encode($response);
            exit;
        } else if (empty(trim($salary_calcu))) {
            $response['id'] = 'salary_calcu';
            $response['error'] = 'Please Select';
            echo json_encode($response);
            exit;
        } else if (($probation_period == 0 && empty($prob_date_first)) || ($probation_period == "" && empty($prob_date_first))) {
            $response['id'] = 'prob_date_first';
            $response['error'] = 'Select Date';
            echo json_encode($response);
        } elseif (($probation_period == 0 && empty($prob_date_second)) || ($probation_period == "" && empty($prob_date_second))) {
            $response['id'] = 'prob_date_second';
            $response['error'] = 'Select Date';
            echo json_encode($response);
        } elseif (($training_period == 0 && empty($training_period_first) ) || ($training_period == "" && empty($training_period_first))) {
            $response['id'] = 'training_period_first';
            $response['error'] = 'Select Date';
            echo json_encode($response);
        } elseif (($training_period == 0 && empty($training_period_second)) || ($training_period == "" && empty($training_period_second))) {
            $response['id'] = 'training_period_second';
            $response['error'] = 'Select Date';
            echo json_encode($response);
        } else {
            $is_virtual = $this->db->select("is_virtual")->where("firm_id", $firm_id)->get("partner_header_all")->row();
			$is_virtual = 0;
            if ($is_virtual == '1') {
                $task_approve = $this->input->post('task_approve1');
                $task_sign = $this->input->post('task_sign1');
            }


            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $username = ($session_data['user_id']);
            } else {
                $username = $this->session->userdata('login_session');
            }
            if ($username === "") {
                $username = $this->session->userdata('login_session');
            }

            //var_dump($firm_id);
            $query_fetch_boss_id = $this->db
                ->select('boss_id, designation_id')
                ->from('user_header_all')
                ->where('firm_id', $firm_id)
                ->get();
            if ($query_fetch_boss_id->num_rows() > 0) {
                $record = $query_fetch_boss_id->row();
                $boss_id_hq = $record->boss_id;
                $designation_id = $record->designation_id;
            }

            $query_fetch_boss_id1 = $this->db
                    ->from('partner_header_all')
                    ->where('firm_id', $firm_id)
                    ->get();
            if ($query_fetch_boss_id1->num_rows() > 0) {
                $record = $query_fetch_boss_id1->row();
                $boss_id = $record->boss_id;
            }

            $work_on_service = $this->input->post('work_on_services');
            if ($work_on_service != '') {
                $count_array = count($work_on_service);
                $res_services = array();
                for ($j = 0; $j < $count_array; $j++) {

                    $res_services[] = $work_on_service[$j];
                }
                $work_on_service = implode(",", $res_services);
            } else {
                $work_on_service = '';
            }

            $work_on_service1 = $this->input->post('work_on_services1');
            if ($work_on_service1 != '') {
                $res_services1 = array();
                $count_array1 = count($work_on_service1);
                for ($j = 0; $j < $count_array1; $j++) {


                    $res_services1[] = $work_on_service1[$j];
                }

                $work_on_service1 = implode(",", $res_services1);
            } else {
                $work_on_service1 = '';
            }

            $firm_user_type = $this->input->post('firm_user_type');
            if ($firm_user_type == 5) {
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
                $result2 = $this->db
                    ->select('firm_id, designation_id, user_id')
                    ->from('user_header_all')
                    ->where('email', $email_id)
                    ->get();
                // echo $this->db->last_query();
                if ($result2->num_rows() > 0) {
                    $record = $result2->row();
                    $login_firm_id = $record->firm_id;
                    $user_id_login = $record->user_id;
                    $designation_id = $record->designation_id;
                }

                $hr_auth = $this->db
                        ->select('hr_authority')
                        ->from('user_header_all')
                        ->where('user_id', $user_id_login)
                        ->where('user_type', 5)
                        ->get();
                if ($this->db->affected_rows() > 0) {
                    $record1 = $hr_auth->row();
                    $firm_id = $record1->hr_authority;

                    $hr_boss_id = $this->db
                        ->select('boss_id,linked_with_boss_id')
                        ->from('user_header_all')
                        ->where('firm_id', $firm_id)
                        ->get();
                    $boss_id_hr = $hr_boss_id->row();
                    $boss_id = $boss_id_hr->boss_id;
                    $boss_id_hq = $boss_id_hr->linked_with_boss_id;
                }
            }
            //   $emp_code = (rand(10,100));
            $data = array(
                'user_id' => $user_id,
                'user_name' => $user_name,
					'form16Type' => 2,
                'mobile_no' => $mobile_no,
                'state' => $state,
                'city' => $city,
                'email' => $email,
                'address' => $address,
                'permanant_address' => $Paddress,
                'country' => $country,
                'firm_id' => $firm_id,
                'is_employee' => 1,
                'user_type' => 4,
                'designation_id' => $designation,
                'date_of_joining' => $date_of_joining,
                'skill_set' => $skills,
                'user_star_rating' => $star_rating,
                'created_by' => $username,
                'password' => $password,
                'hash_password' => $hash_password,
                'linked_with_boss_id' => $boss_id,
                'boss_id' => $boss_id_hq,
                'senior_user_id' => $senior_emp,
                'emp_code ' =>$emp_code ,
                'spouse_name' =>$spouse_name ,
                'department_name' =>$dept_name  ,
                'UAN_no' =>$uan_no ,
                'account_no' =>$account_no ,
                'gender' =>$gender ,
                'pan_no' =>$pan_no ,
                'date_of_birth' =>$date_of_birth ,
                'adhar_no' =>$adhar_no ,
                'bank_name' =>$bank_name ,
                'sandwich_leave_applicable' =>$sandwichLeave ,
                'gps_off_allow' =>$gpsoff ,
                //  'leave_approve_permission' => $leave_allow_permission,
                'probation_period_start_date' => $prob_date_first,
                'probation_period_end_date' => $prob_date_second,
                'training_period_start_date' => $training_period_first,
                'training_period_end_date' => $training_period_second,
                'create_task_assignment' => $add_task . ',' . $updt_task . ',' . $del_task . ':' . $add_task_assignment . ',' . $updt_task_assignment . ',' . $del_task_assignment,
//                'create_due_date_permission' => $add_due_date . ',' . $updt_due_date . ',' . $del_due_date . ':' . $add_due_date_task . ',' . $updt_due_date_task . ',' . $del_due_date_task,
//                'create_service_permission' => $add_service . ',' . $updt_service . ',' . $del_service,
//                'template_store_permission' => $add_template_store . ',' . $updt_template_store . ',' . $del_template_store,
//                'knowledge_store_permission' => $add_knowledge_store . ',' . $updt_knowledge_store . ',' . $del_knowledge_store,
//                'employee_permission' => $add_employee . ',' . $updt_employee . ',' . $del_employee,
//                'customer_permission' => $add_customer . ',' . $updt_customer . ',' . $del_customer,
//                'enquiry_generate_permission' => $generate_enq_permit,
//                'user_star_rating' => $star_rating,
//                'task_approve_permission' => $task_approve,
//                'task_sign_permission' => $task_sign,
//                'work_on_services' => $work_on_service,
//                'handle_web_services' => $work_on_service1,
//                'web_enquiry_handle_permission' => $allot_permited,
                'probation_period_start_date' => $prob_date_first,
                'probation_period_end_date' => $prob_date_second,
                'training_period_start_date' => $training_period_first,
                'training_period_end_date' => $training_period_second,
                //  'warning_conifg_permission' => $add_warining . ',' . $updt_warning . ',' . $del_warning,
                // 'activity_status' => 0,
                'activity_status' => 1,
                'survey_status' => "",
                'batch_id' => "",
                'survey_reject_time' => "",
                'overtime_applicable_sts' => $ot_applicable_sts,
                'salary_calculation' => $salary_calcu,
                'holiday_working_sts' => $holiday_applible_sts,
            );

            $result1 = $this->db
                ->select('firm_name')
                ->from('partner_header_all')
                ->where('firm_id', $firm_id)
                ->get();
            if ($result1->num_rows() > 0) {
                $data1 = $result1->row();
                $firm_name = $data1->firm_name;
            }

            // sms function code
            $auth_key = "178904AVN94GK259e5e87b";
            $firm_no_of_permitted_offices = "";
            $firm_no_of_employee = "";
            $firm_no_of_customers = "";
            $trigger_by = "client";
            $no = $data['mobile_no'];
            $boss_name = $data['user_name'];
            $firm_name = $firm_name;
            $firm_email_id = $data['email'];
            $user_type = $data['user_type'];

            $record = $this->emp_model->add_emp($data);
            if ($record == TRUE) {
                //email  function
                $email = $this->email_sending_model->sendEmail($user_type, $boss_name, $firm_name, $firm_no_of_permitted_offices, $firm_no_of_employee, $firm_no_of_customers, $firm_email_id, $password, $trigger_by);
                // sms function code
               // $this->firm_model->sendSms($auth_key, $no, $user_type, $boss_name, $firm_name, $firm_no_of_permitted_offices, $firm_no_of_employee, $firm_no_of_customers, $firm_email_id, $password, $trigger_by);
                // assign check list to new user
                $this->assign_checklist_to_user1();
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        }
    }

    function get_leave() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $user_type = ($session_data['user_type']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $firm_id = $record->firm_id;
            $data['user_type'] = $record->user_type;
        }
        $query = $this->db->query("SELECT designation_id , substring_index(substring_index(type1,':',-2),':',1)as type1 ,substring_index(substring_index(type2,':',-2),':',1)as type2,substring_index(substring_index(type3,':',-2),':',1)as type3,substring_index(substring_index(type4,':',-2),':',1)as type4,substring_index(substring_index(type5,':',-2),':',1)as type5,substring_index(substring_index(type6,':',-2),':',1) as type6,substring_index(substring_index(type7,':',-2),':',1)as type7 from `leave_header_all` where `firm_id`='$firm_id' ");
        $this->db->last_query();
        if ($query->num_rows() > 0) {
            $recordq = $query->row();
            $designation_id = $recordq->designation_id;
            $type1 = $recordq->type1;
            $type2 = $recordq->type2;
            $type3 = $recordq->type3;
            $type4 = $recordq->type4;
            $type5 = $recordq->type5;
            $type6 = $recordq->type6;
            $type7 = $recordq->type7;
            $data['designation_id'] = $recordq->designation_id;
        } else {
            $data['designation_id'] = '';
        }
        $query_desig = $this->db->query("Select total_yearly_leaves from designation_header_all where designation_id ='$designation_id'");
        $this->db->last_query();
        if ($query_desig->num_rows() > 0) {
            $record_des = $query_desig->row();
            $total_yearly_leaves = $record_des->total_yearly_leaves;
            $data['total_yearly_leaves'] = $record_des->total_yearly_leaves;
        } else {
            $data['total_yearly_leaves'] = '';
        }
        $type1;
        $total_yearly_leaves;
        $percentage_type1 = ($type1 / $total_yearly_leaves) * 100;
        $percentage_type1;
        $percentage_type2 = ($type2 / $total_yearly_leaves) * 100;
        $percentage_type2;
        $percentage_type3 = ($type3 / $total_yearly_leaves) * 100;
        $percentage_type4 = ($type4 / $total_yearly_leaves) * 100;
        $percentage_type5 = ($type5 / $total_yearly_leaves) * 100;
        $percentage_type6 = ($type6 / $total_yearly_leaves) * 100;
        $percentage_type7 = ($type7 / $total_yearly_leaves) * 100;
        $per_Array = array($percentage_type1, $percentage_type2, $percentage_type3, $percentage_type4, $percentage_type5, $percentage_type6, $percentage_type7);
        return $per_Array;
    }

    public function add_emp_attendance() {
//        echo $firm_name = $this->input->post('firm_name');
        $firm_name_sal_attnd = $this->input->post('firm_name_sal_attnd');
        $select_employee = $this->input->post('select_employee');
        $applicable_status = $this->input->post('attendance_employee');

        $work_hour_employee = $this->input->post('work_hour_employee');
        $fixed_time = $this->input->post('fixed_time');

        $work_hour_schedule = $this->input->post('work_hour_schedule');

        $week_days1 = $this->input->post('week_days1');
        $select_schedule_type1 = $this->input->post('select_schedule_type1');
        $select_fixed_hour1 = $this->input->post('select_fixed_hour1');

        $week_days2 = $this->input->post('week_days2');
        $select_schedule_type2 = $this->input->post('select_schedule_type2');
        $select_fixed_hour2 = $this->input->post('select_fixed_hour2');

        $week_days3 = $this->input->post('week_days3');
        $select_schedule_type3 = $this->input->post('select_schedule_type3');
        $select_fixed_hour3 = $this->input->post('select_fixed_hour3');

        $week_days4 = $this->input->post('week_days4');
        $select_schedule_type4 = $this->input->post('select_schedule_type4');
        $select_fixed_hour4 = $this->input->post('select_fixed_hour4');

        $week_days5 = $this->input->post('week_days5');
        $select_schedule_type5 = $this->input->post('select_schedule_type5');
        $select_fixed_hour5 = $this->input->post('select_fixed_hour5');

        $week_days6 = $this->input->post('week_days6');
        $select_schedule_type6 = $this->input->post('select_schedule_type6');
        $select_fixed_hour6 = $this->input->post('select_fixed_hour6');

        $week_days7 = $this->input->post('week_days7');
        $select_schedule_type7 = $this->input->post('select_schedule_type7');
        $select_fixed_hour7 = $this->input->post('select_fixed_hour7');

        $fixed_hour = $this->input->post('fixed_hour');
        $select_fixed_hour = $this->input->post('select_fixed_hour');

        if ($applicable_status == 2) {
            $work_hour_employee = '';
            $fixed_time = '';
            $work_hour_schedule = '';
            $fixed_hour = '';
            $select_fixed_hour = '';
        }

        if (($applicable_status == 1 && $work_hour_employee == 1 && empty($fixed_time)) || ($applicable_status == "" && empty($fixed_time))) {
            $response['id'] = 'fixed_time';
            $response['error'] = 'Enter Time';
            echo json_encode($response);
            exit;
        } elseif ($applicable_status == 1 && $work_hour_employee == 1 && $fixed_time > 24) {
            $response['id'] = 'fixed_time';
            $response['error'] = 'Please enter valid time between 24 Hrs';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 1 && empty($fixed_hour)) || ($applicable_status == "" && empty($fixed_time))) {
            $response['id'] = 'fixed_hour';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($week_days1))) {
            $response['id'] = 'week_days1';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($select_schedule_type1))) {
            $response['id'] = 'select_schedule_type1';
            $response['error'] = 'Select Type';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && $select_schedule_type1 == 1 && empty($select_fixed_hour1))) {
            $response['id'] = 'select_fixed_hour1';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($week_days2))) {
            $response['id'] = 'week_days2';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($select_schedule_type2))) {
            $response['id'] = 'select_schedule_type2';
            $response['error'] = 'Select Type';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && $select_schedule_type2 == 1 && empty($select_fixed_hour2))) {
            $response['id'] = 'select_fixed_hour2';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($week_days3))) {
            $response['id'] = 'week_days3';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($select_schedule_type3))) {
            $response['id'] = 'select_schedule_type3';
            $response['error'] = 'Select Type';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && $select_schedule_type3 == 1 && empty($select_fixed_hour3))) {
            $response['id'] = 'select_fixed_hour3';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($week_days4))) {
            $response['id'] = 'week_days4';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($select_schedule_type4))) {
            $response['id'] = 'select_schedule_type4';
            $response['error'] = 'Select Type';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && $select_schedule_type4 == 1 && empty($select_fixed_hour4))) {
            $response['id'] = 'select_fixed_hour4';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($week_days5))) {
            $response['id'] = 'week_days5';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($select_schedule_type5))) {
            $response['id'] = 'select_schedule_type5';
            $response['error'] = 'Select Type';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && $select_schedule_type5 == 1 && empty($select_fixed_hour5))) {
            $response['id'] = 'select_fixed_hour5';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($week_days6))) {
            $response['id'] = 'week_days6';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($select_schedule_type6))) {
            $response['id'] = 'select_schedule_type6';
            $response['error'] = 'Select Type';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && $select_schedule_type6 == 1 && empty($select_fixed_hour6))) {
            $response['id'] = 'select_fixed_hour6';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($week_days7))) {
            $response['id'] = 'week_days7';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && empty($select_schedule_type7))) {
            $response['id'] = 'select_schedule_type7';
            $response['error'] = 'Select Type';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 2 && $select_schedule_type7 == 1 && empty($select_fixed_hour7))) {
            $response['id'] = 'select_fixed_hour7';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif ((empty($select_employee))) {
            $response['id'] = 'select_employee';
            $response['error'] = 'Select Employee';
            echo json_encode($response);
            exit;
        } else {


            $data = array(
                'user_id' => $select_employee,
                'applicable_status' => $applicable_status,
                'work_hour_status' => $work_hour_employee,
                'fixed_time' => $fixed_time,
                'work_schedule_status' => $work_hour_schedule,
                'fixed_hour' => $fixed_hour,
//                'week_days' =>$week_days,
//                'week_type' =>$select_schedule_type,
//                'week_type_hour' =>$select_fixed_hour,
            );
//            var_dump($data);
//            exit;
            for ($i = 1; $i <= 7; $i++) {

                $week_days11 = $this->input->post('week_days' . $i);
                $select_schedule_type11 = $this->input->post('select_schedule_type' . $i);
                $select_fixed_hour11 = $this->input->post('select_fixed_hour' . $i);
                $data_work_schedule = array(
                    'firm_id' => $firm_name_sal_attnd,
                    'user_id' => $select_employee,
                    'day' => $week_days11,
                    'type' => $select_schedule_type11,
                    'fixed_hour' => $select_fixed_hour11
                );
//                var_dump($data_work_schedule);
                if ($work_hour_schedule == 2) {
                    $query = $this->db->insert("attendance_employee_applicable", $data_work_schedule);
                }
            }
            $record = $this->db->query("update user_header_all set `applicable_status` ='$applicable_status',`work_hour_status` ='$work_hour_employee',`fixed_time` ='$fixed_time',`work_schedule_status`='$work_hour_schedule',`fixed_hour`='$fixed_hour' where `user_id` ='$select_employee'");
            if ($record == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } echo json_encode($response);
    }

    public function add_attendance_employee() {
        $select_employee = $this->input->post('select_employee');
        $firm_name_check = $this->input->post('firm_name_sal_attnd');
        $applicable_status = $this->input->post('attendance_employee');

        $work_hour_employee = $this->input->post('work_hour_employee');
        $fixed_time = $this->input->post('fixed_time');

        $work_hour_schedule = $this->input->post('work_hour_schedule');
        $fixed_hour = $this->input->post('fixed_hour');

        $late_applicable_sts = $this->input->post('late_applicable_sts');
        $late_salary_count = $this->input->post('late_salary_count');
        $late_salary_days = $this->input->post('late_salary_days');
        $late_minute_time = $this->input->post('late_minute_time');

		$employee_firms = $this->input->post('employee_firms');

        //Outside and shift applicable sts
        $outside_punch_applicable = $this->input->post('outside_punch_applicable');
        $shift_applicable = $this->input->post('shift_applicable');
        if ($applicable_status == 2) {
            $work_hour_employee = 2;
            $outside_punch_applicable = 1;
            $shift_applicable = 2;
            $fixed_time = '';
            $work_hour_schedule = '';
            $fixed_hour = '';
            $late_salary_days = '';
            $late_salary_count = '';
            $late_minute_time = '';
            $late_applicable_sts = 0;
            $select_fixed_hour = '';
        }
        if ((empty($firm_name_check))) {
            $response['id'] = 'firm_name_sal_attnd';
            $response['error'] = 'Select Office';
            echo json_encode($response);
            exit;
        } elseif ((empty($select_employee))) {
            $response['id'] = 'select_employee';
            $response['error'] = 'Select Employee';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && empty(trim($outside_punch_applicable)))) {
            $response['id'] = 'outside_punch_applicable';
            $response['error'] = 'Please Select';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && empty(trim($shift_applicable)))) {
            $response['id'] = 'shift_applicable';
            $response['error'] = 'Please Select';
            echo json_encode($response);
            exit;
        }
//        elseif (($applicable_status == 1 && $work_hour_employee == 1 && empty(trim($fixed_time)))) {
//            $response['id'] = 'fixed_time';
//            $response['error'] = 'Enter time';
//            echo json_encode($response);
//            exit;
//        }
//        elseif (($applicable_status == 1 && $work_hour_schedule == 1 && empty(trim($fixed_hour)))) {
//            $response['id'] = 'fixed_hour';
//            $response['error'] = 'Enter Fixed Hour';
//            echo json_encode($response);
//            exit;
//        }
//        elseif (($applicable_status == 1 && $work_hour_schedule == 1 && $work_hour_employee == 1 && empty(trim($fixed_time)))) {
//            $response['id'] = 'fixed_time';
//            $response['error'] = 'Enter Fixed Time';
//            echo json_encode($response);
//            exit;
//        }
        elseif (($late_applicable_sts == 1 && empty(trim($late_salary_days)))) {
            $response['id'] = 'late_salary_days';
            $response['error'] = 'Please Select Type';
            echo json_encode($response);
            exit;
        } elseif (($late_applicable_sts == 1 && empty(trim($late_salary_count)))) {
            $response['id'] = 'late_salary_count';
            $response['error'] = 'Please Select Type';
            echo json_encode($response);
            exit;
        } elseif (($late_applicable_sts == 1 && empty(trim($late_minute_time)))) {
            $response['id'] = 'late_minute_time';
            $response['error'] = 'Please Enter Minute';
            echo json_encode($response);
            exit;
        } else {

            //Need to check attendance applicable is already entered or not
            $this->db->query("delete from attendance_employee_applicable where user_id='$select_employee'");

            if ($applicable_status == 1) {
                $cnt = 1;

//                    echo "jhjh";
                for ($j = 1; $j <= 7; $j++) {
                    $week_days = $this->input->post("week_days" . $j);
                    $select_schedule_type = $this->input->post("select_schedule_type" . $j);
                    $select_fixed_hour = $this->input->post("select_fixed_hour" . $j);
                    $fix_in_appli_sts = $this->input->post("fix_in_appli_sts" . $j);
                    $inapplicable_time = $this->input->post("inapplicable_time" . $j);
                    if ($select_schedule_type == 2) {
                        $fix_in_appli_sts = '';
                        $inapplicable_time = '';
                    } else {
                        $fix_in_appli_sts = $this->input->post("fix_in_appli_sts" . $j);
                        $inapplicable_time = $this->input->post("inapplicable_time" . $j);
                    }
                    $data = array(
                        'user_id' => $select_employee,
                        'firm_id' => $firm_name_check,
                        'day' => $week_days,
                        'type' => $select_schedule_type,
                        'fixed_hour' => $select_fixed_hour,
                        'in_time_applicable_sts' => $fix_in_appli_sts,
                        'in_fixed_time' => $inapplicable_time
                    );

                    $data_applicable2 = array(
                        'applicable_status' => $applicable_status,
                        'outside_punch_applicable' => $outside_punch_applicable,
                        'shift_applicable' => $shift_applicable,
                        'work_schedule_status' => $work_hour_schedule,
                        'work_hour_status' => $work_hour_employee,
                        'fixed_time' => $fixed_time,
                        'fixed_hour' => $fixed_hour,
                        'late_applicable_sts' => $late_applicable_sts,
                        'late_salary_cut_days' => $late_salary_days,
                        'late_salary_days_count' => $late_salary_count,
                        'late_minute_count' => $late_minute_time
                    );



                    $query = $this->db->insert('attendance_employee_applicable', $data);

                    if ($query == TRUE) {
                        $cnt++;
                    }
//
                }


                if ($cnt > 1) {
                    $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                    $this->db->where($array);
                    $this->db->update('user_header_all', $data_applicable2);
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                } echo json_encode($response);
            } else {
//                echo "Only applicable status";
                $data = array(
                    'user_id' => $select_employee,
                    'firm_id' => $firm_name_check,
                    'applicable_status' => $applicable_status,
                    'outside_punch_applicable' => $outside_punch_applicable,
                    'shift_applicable' => $shift_applicable,
                    'work_hour_status' => $work_hour_employee,
                    'work_schedule_status' => $work_hour_schedule,
                    'fixed_time' => $fixed_time,
                    'late_applicable_sts' => $late_applicable_sts,
                    'late_salary_cut_days' => $late_salary_days,
                    'late_salary_days_count' => $late_salary_count,
                    'late_minute_count' => $late_minute_time
                );
//                var_dump($data);



                $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                $this->db->where($array);
                $record = $this->db->update('user_header_all', $data);
                $this->db->query("delete from attendance_employee_applicable where user_id='$select_employee'");
                if ($record == TRUE) {

					if($employee_firms != '' && $employee_firms != null){

						$this->db->delete('employee_firm_master',array('user_id' => $select_employee));
						foreach($employee_firms as $row){
							$data = array(
									'user_id' => $select_employee,
									'firm_id' => $row
							);
							$this->db->insert('employee_firm_master',$data);
						}
					}

                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }echo json_encode($response);
            }
        }
    }

    public function add_attendance_employee1() {
        $select_employee = $this->input->post('select_employee');
        $firm_name_check = $this->input->post('firm_name_sal_attnd');
        $applicable_status = $this->input->post('attendance_employee');

        $work_hour_employee = $this->input->post('work_hour_employee');
        $fixed_time = $this->input->post('fixed_time');

        $work_hour_schedule = $this->input->post('work_hour_schedule');
        $fixed_hour = $this->input->post('fixed_hour');

        $late_applicable_sts = $this->input->post('late_applicable_sts');
        $late_salary_count = $this->input->post('late_salary_count');
        $late_salary_days = $this->input->post('late_salary_days');
        $late_minute_time = $this->input->post('late_minute_time');

        //Outside and shift applicable sts
        $outside_punch_applicable = $this->input->post('outside_punch_applicable');
        $shift_applicable = $this->input->post('shift_applicable');


        if ($applicable_status == 2) {
            $work_hour_employee = 2;
            $outside_punch_applicable = 1;
            $shift_applicable = 2;
            $fixed_time = '';
            $work_hour_schedule = '';
            $fixed_hour = '';
            $late_salary_days = '';
            $late_salary_count = '';
            $late_minute_time = '';
            $late_applicable_sts = 0;
            $select_fixed_hour = '';
        }

        if ((empty($firm_name_check))) {
            $response['id'] = 'firm_name_sal_attnd';
            $response['error'] = 'Select Office';
            echo json_encode($response);
            exit;
        } elseif ((empty($select_employee))) {
            $response['id'] = 'select_employee';
            $response['error'] = 'Select Employee';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && empty(trim($outside_punch_applicable)))) {
            $response['id'] = 'outside_punch_applicable';
            $response['error'] = 'Please Select';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && empty(trim($shift_applicable)))) {
            $response['id'] = 'shift_applicable';
            $response['error'] = 'Please Select';
            echo json_encode($response);
            exit;
        }
//        elseif (($applicable_status == 1 && $work_hour_employee == 1 && empty(trim($fixed_time)))) {
//            $response['id'] = 'fixed_time';
//            $response['error'] = 'Enter time';
//            echo json_encode($response);
//            exit;
//        }
        elseif (($applicable_status == 1 && $work_hour_schedule == 1 && empty(trim($fixed_hour)))) {
            $response['id'] = 'fixed_hour';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && $work_hour_schedule == 1 && $work_hour_employee == 1 && empty(trim($fixed_time)))) {
            $response['id'] = 'fixed_time';
            $response['error'] = 'Enter Fixed Time';
            echo json_encode($response);
            exit;
        } elseif (($late_applicable_sts == 1 && empty(trim($late_salary_days)))) {
            $response['id'] = 'late_salary_days';
            $response['error'] = 'Please Select Type';
            echo json_encode($response);
            exit;
        } elseif (($late_applicable_sts == 1 && empty(trim($late_salary_count)))) {
            $response['id'] = 'late_salary_count';
            $response['error'] = 'Please Select Type';
            echo json_encode($response);
            exit;
        } elseif (($late_applicable_sts == 1 && empty(trim($late_minute_time)))) {
            $response['id'] = 'late_minute_time';
            $response['error'] = 'Please Enter Minute';
            echo json_encode($response);
            exit;
        } else {

            //Need to check attendance applicable is already entered or not
            $this->db->query("delete from attendance_employee_applicable where user_id='$select_employee'");

            if ($applicable_status == 1) {
//                echo "attend";
                if ($work_hour_schedule == 2 && $work_hour_employee != '' && $late_applicable_sts !== '') {
                    $cnt = 1;

//                    echo "jhjh";
                    for ($j = 1; $j <= 7; $j++) {
                        $week_days = $this->input->post("week_days" . $j);
                        $select_schedule_type = $this->input->post("select_schedule_type" . $j);
                        $select_fixed_hour = $this->input->post("select_fixed_hour" . $j);
                        $fix_in_appli_sts = $this->input->post("fix_in_appli_sts" . $j);
                        $inapplicable_time = $this->input->post("inapplicable_time" . $j);
                        if ($select_schedule_type == 2) {
                            $fix_in_appli_sts = '';
                            $inapplicable_time = '';
                        } else {
                            $fix_in_appli_sts = $this->input->post("fix_in_appli_sts" . $j);
                            $inapplicable_time = $this->input->post("inapplicable_time" . $j);
                        }
                        $data = array(
                            'user_id' => $select_employee,
                            'firm_id' => $firm_name_check,
                            'day' => $week_days,
                            'type' => $select_schedule_type,
                            'fixed_hour' => $select_fixed_hour,
                            'in_time_applicable_sts' => $fix_in_appli_sts,
                            'in_fixed_time' => $inapplicable_time
                        );

                        $data_applicable2 = array(
                            'applicable_status' => $applicable_status,
                            'outside_punch_applicable' => $outside_punch_applicable,
                            'shift_applicable' => $shift_applicable,
                            'work_schedule_status' => $work_hour_schedule,
                            'work_hour_status' => $work_hour_employee,
                            'fixed_time' => $fixed_time,
                            'fixed_hour' => $fixed_hour,
                            'late_applicable_sts' => $late_applicable_sts,
                            'late_salary_cut_days' => $late_salary_days,
                            'late_salary_days_count' => $late_salary_count,
                            'late_minute_count' => $late_minute_time
                        );



                        $query = $this->db->insert('attendance_employee_applicable', $data);

                        if ($query == TRUE) {
                            $cnt++;
                        }
//
                    }


                    if ($cnt > 1) {
                        $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                        $this->db->where($array);
                        $this->db->update('user_header_all', $data_applicable2);
                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    } echo json_encode($response);
                } else if ($work_hour_schedule == 1 && $work_hour_employee != '' && $late_applicable_sts != '') {
//                    echo "condition wise";
                    $data_applicable3 = array(
                        'applicable_status' => $applicable_status,
                        'outside_punch_applicable' => $outside_punch_applicable,
                        'shift_applicable' => $shift_applicable,
                        'work_schedule_status' => $work_hour_schedule,
                        'work_hour_status' => $work_hour_employee,
                        'fixed_time' => $fixed_time,
                        'fixed_hour' => $fixed_hour,
                        'late_applicable_sts' => $late_applicable_sts,
                        'late_salary_cut_days' => $late_salary_days,
                        'late_salary_days_count' => $late_salary_count,
                        'late_minute_count' => $late_minute_time
                    );
                    $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                    $this->db->where($array);
                    $update_condition_wise = $this->db->update('user_header_all', $data_applicable3);
                    $this->db->query("delete from attendance_employee_applicable where user_id='$select_employee'");
                    if ($update_condition_wise == TRUE) {
                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    } echo json_encode($response);
                }
            } else {
//                echo "Only applicable status";
                $data = array(
                    'user_id' => $select_employee,
                    'firm_id' => $firm_name_check,
                    'applicable_status' => $applicable_status,
                    'outside_punch_applicable' => $outside_punch_applicable,
                    'shift_applicable' => $shift_applicable,
                    'work_hour_status' => $work_hour_employee,
                    'work_schedule_status' => $work_hour_schedule,
                    'fixed_time' => $fixed_time,
                    'late_applicable_sts' => $late_applicable_sts,
                    'late_salary_cut_days' => $late_salary_days,
                    'late_salary_days_count' => $late_salary_count,
                    'late_minute_count' => $late_minute_time
                );
//                var_dump($data);



                $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                $this->db->where($array);
                $record = $this->db->update('user_header_all', $data);
                $this->db->query("delete from attendance_employee_applicable where user_id='$select_employee'");
                if ($record == TRUE) {

                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }echo json_encode($response);
            }
        }
    }

    public function check_attendanceemp_exist() {//Function by rajashree
        $select_employee = $this->input->post("select_employee");
        $check_year = $this->db->query("select applicable_status from user_header_all where user_id='$select_employee'");
//        echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {

            $data_employee = $check_year->row();
            $data_attend_emp = $data_employee->applicable_status;
            if ($data_attend_emp == 1 || $data_attend_emp == 2) {
                $response['employee_attend_exist'] = $data_attend_emp;
                $response['message'] = 'success';
            } else {
                $response['employee_attend_exist'] = '';
                $response['message'] = 'fail';
            }
        } else {
            $response['message'] = 'no data';
        }echo json_encode($response);
    }

    public function add_emp_performance() {
        $select_employee = $this->input->post('select_employee1');
     $firm_name_sal = $this->input->post('emp_firm');
        $value = $this->input->post('value');
        $month = $this->input->post('Month');
        $years = $this->input->post('years');
        $date_of_PA = $this->input->post('Date_of_PA');
			 if (empty($firm_name_sal)) {
            $response['id'] = 'firm_name_sal';
            $response['error'] = 'Select Office';
            echo json_encode($response);

		}
        elseif ((empty($select_employee))) {
            $response['id'] = 'select_employee1';
            $response['error'] = 'Please Select Employee';
            echo json_encode($response);
        } elseif ((empty($value))) {
            $response['id'] = 'value';
            $response['error'] = 'Please Enter Valid Time Between 24 Hrs';
            echo json_encode($response);
        } elseif ((empty($month))) {
            $response['id'] = 'Month';
            $response['error'] = 'Enter Fixed Hour';
            echo json_encode($response);
        } elseif ((empty($years))) {
            $response['id'] = 'years';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
        } elseif ((empty($date_of_PA))) {
            $response['id'] = 'Date_of_PA';
            $response['error'] = 'Enter Day Name';
            echo json_encode($response);
        } else {

            $data_performance = array(
                "user_id" => $select_employee,
                "value" => $value,
                "Month" => $month,
                "FYear" => $years,
                "Date_of_PA" => $date_of_PA,
                "firm_id" => $firm_name_sal
            );
            $record = $this->db->insert('t_perfomanceallowance', $data_performance);
            if ($record == TRUE) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }echo json_encode($response);
    }

    public function get_employee_list() {
        $user_type = $this->input->post('user_type');
        if ($user_type == 2) {
            $firm_id = $this->input->post('firm_id');
        } else {
            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $email_id = ($session_data['user_id']);
				$loginuser_id = ($session_data['emp_id']);
            } else {
                $email_id = $this->session->userdata('login_session');
            }
            if ($email_id === "") {
                $email_id = $this->session->userdata('login_session');
            }
            $result2 = $this->db->query("SELECT firm_id FROM `user_header_all` WHERE `email`='$email_id'");
            if ($result2->num_rows() > 0) {
                $record = $result2->row();
               $login_firm_id = $record->firm_id;
            }
            $hr_auth = $this->db->query("select hr_authority from user_header_all where user_id='$loginuser_id' AND user_type='5'");
            if ($this->db->affected_rows() > 0) {
                $record1 = $hr_auth->row();
                $firm_id = $record1->hr_authority;
            }
        }
        $query_get_firm = $this->db->query("SELECT user_id,user_name FROM user_header_all where user_type=4 AND activity_status != 1 and firm_id ='$firm_id'");
//        echo $this->db->last_query();
        if ($query_get_firm->num_rows() > 0) {
            $record = $query_get_firm->result();
            $response['result'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['result'] = '';
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    function ddl_get_employee() {

        $result = $this->firm_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
            $boss_id = $result['boss_id'];
        }
        $response = array('code' => -1, 'status' => false, 'message' => '');
        $array = array('is_employee' => '1', 'firm_id' => $firm_id, 'linked_with_boss_id' => $boss_id, 'activity_status' => '1');
        $this->db->select('*');
        $this->db->from('user_header_all');
        $this->db->where($array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = array('user_name' => array(), 'user_id' => array(), 'boss_id' => array());
            foreach ($query->result() as $row) {
                $data['user_name'] = $row->user_name;
                $data['user_id'] = $row->user_id;
                $data['firm_id'] = $row->firm_id;
                $data['boss_id'] = $row->linked_with_boss_id;
                $response['emp_data'][] = ['user_name' => $row->user_name, 'user_id' => $row->user_id, 'firm_id' => $row->firm_id, 'boss_id' => $row->linked_with_boss_id];
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    function ddl_get_employee_hr() {

        $response = array('code' => -1, 'status' => false, 'message' => '');
        $result = $this->firm_model->get_firm_id();
        if ($result !== false) {
            $user_id = $result['user_id'];
        }

        $qrr = $this->db->query("select * from user_header_all where user_id='$user_id'");
        $ress = $qrr->row();
        $user_name = $ress->user_name;

        $hr_data = array('user_id' => $user_id, 'user_name' => $user_name);
        $response['hr_data'] = $hr_data;

        $firm_id = $this->input->post('firm_id');
        //$email_id = $this->input->post('email_id');

        $qr = $this->db->query("select linked_with_boss_id from user_header_all where firm_id='$firm_id'");
        $res = $qr->row();
        $boss_id = $res->linked_with_boss_id;


        $array = array('is_employee' => '1', 'firm_id' => $firm_id, 'linked_with_boss_id' => $boss_id, 'activity_status' => '1');
        $this->db->select('*');
        $this->db->from('user_header_all');
        $this->db->where($array);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = array('user_name' => array(), 'user_id' => array(), 'boss_id' => array());
            foreach ($query->result() as $row) {
                $data['user_name'] = $row->user_name;
                $data['user_id'] = $row->user_id;
                $data['firm_id'] = $row->firm_id;
                $data['boss_id'] = $row->linked_with_boss_id;
                $response['emp_data'][] = ['user_name' => $row->user_name, 'user_id' => $row->user_id, 'firm_id' => $row->firm_id, 'boss_id' => $row->linked_with_boss_id];
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_services() {
        $result = $this->firm_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
            $boss_id = $result['boss_id'];
        };

        $array = array('firm_id' => $firm_id, 'status' => 1, 'service_type_id' => '');
        $this->db->select('*');
        $this->db->from('services_header_all');
        $this->db->where($array);
        $this->db->order_by("service_name", "ASC");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data = array('service_name' => array(), 'service_id' => array());
            foreach ($query->result() as $row) {
                $data['service_name'] = $row->service_name;
                $data['service_id'] = $row->service_id;
                $response['service_data'][] = ['service_name' => $row->service_name, 'service_id' => $row->service_id];
            }

            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_services_hq() {
        $firm_id = $this->input->post('firm_id');
//var_dump($firm_id);
        $array = array('firm_id' => $firm_id, 'status' => 1, 'service_type_id' => '');
        $this->db->select('*');
        $this->db->from('services_header_all');
        $this->db->where($array);
        $this->db->order_by("service_name", "ASC");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data = array('service_name' => array(), 'service_id' => array());
            foreach ($query->result() as $row) {
                $data['service_name'] = $row->service_name;
                $data['service_id'] = $row->service_id;
                $response['service_data'][] = ['service_name' => $row->service_name, 'service_id' => $row->service_id];
            }

            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function ddl_get_employee_hq() {
        $user_type = $this->input->post('user_type');
        if ($user_type == 2) {
            $firm_id = $this->input->post('firm_id');
        } else {
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
            $result2 = $this->db->query("SELECT firm_id,user_id FROM `user_header_all` WHERE `email`='$email_id' and activity_status=1");
            if ($result2->num_rows() > 0) {
                $record = $result2->row();
                $login_firm_id = $record->firm_id;
                $login_user_id = $record->user_id;
            }

            $hr_auth = $this->db->query("select hr_authority from user_header_all where user_id='$login_user_id' AND user_type='5'");
//        echo $this->db->last_query();
            if ($this->db->affected_rows() > 0) {
                $record1 = $hr_auth->row();
                $firm_id = $record1->hr_authority;
            }
        }
       /* $query_fetch_boss_id = $this->db->query("SELECT * from user_header_all where firm_id='$firm_id'");
        if ($query_fetch_boss_id->num_rows() > 0) {
            $record = $query_fetch_boss_id->row();
            $boss_id = $record->linked_with_boss_id;
        }*/
        $response = array('code' => -1, 'status' => false, 'message' => '');
        $array = array('is_employee' => '1', 'firm_id' => $firm_id);
        $this->db->select('*');
        $this->db->from('user_header_all');
        $this->db->where($array);
        $query = $this->db->get();


        //code to get hr name in HR panel
        $result = $this->firm_model->get_firm_id();
        if ($result !== false) {
            $user_id = $result['user_id'];
        };
        $query_get_hr_name = $this->db->query("select user_name from user_header_all where user_id='$user_id'");
        if ($query_get_hr_name->num_rows() > 0) {
            $rec = $query_get_hr_name->row();
            $user_name = $rec->user_name;
            $hr_data = array('user_id' => $user_id, 'user_name' => $user_name);
            $response['hr_data'] = $hr_data;
        } else {
            $user_name = "";
            $response['hr_data'] = "";
        }
        //code to get hr name in HR panel END here
        //code to get hr name  in HQ Admin
        $result_get_hr_name = $this->emp_model->get_hr_name_in_hq($firm_id);
//        echo $this->db->last_query();
        if ($result_get_hr_name != FALSE) {
            $record_hr_name = $result_get_hr_name->row();
            $user_id_hr = $record_hr_name->user_id;
            $user_name_hr = $record_hr_name->user_name;
            $hr_data_hq = array('user_id' => $user_id_hr, 'user_name' => $user_name_hr);
            $response['hr_data_hq'] = $hr_data_hq;
        } else {
            $response['hr_data_hq'] = "";
        }
        //code to get hr name  in HQ Admin END HERE
        if ($query->num_rows() > 0) {
            $data = array('user_name' => array(), 'user_id' => array(), 'boss_id' => array());
            foreach ($query->result() as $row) {
                $data['user_name'] = $row->user_name;
                $data['user_id'] = $row->user_id;
                $data['firm_id'] = $row->firm_id;
                $data['boss_id'] = $row->linked_with_boss_id;
                $response['emp_data'][] = ['user_name' => $row->user_name, 'user_id' => $row->user_id, 'firm_id' => $row->firm_id, 'boss_id' => $row->linked_with_boss_id];
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $data = array('user_name' => array(), 'user_id' => array(), 'boss_id' => array(), 'user_type' => '4');
            foreach ($query->result() as $row) {
                $data['user_name'] = $row->user_name;
                $data['user_id'] = $row->user_id;
                $data['firm_id'] = $row->firm_id;
                $data['boss_id'] = $row->linked_with_boss_id;
                $response['emp_data'][] = ['user_name' => $row->user_name, 'user_id' => $row->user_id, 'firm_id' => $row->firm_id, 'boss_id' => $row->linked_with_boss_id];
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        }
//        else {
//            $array = array('firm_id' => $firm_id, 'linked_with_boss_id' => $boss_id, 'activity_status' => '1', 'user_type' => '3');
//            $this->db->select('*');
//            $this->db->from('user_header_all');
//            $this->db->where($array);
//            $query_ca = $this->db->get();
//            $row_ca = $query_ca->row();
//
////            echo $row_ca->user_name;
//
//            $row_ca->user_name;
//            $response['emp_data'][] = ['user_name' => $row_ca->user_name, 'user_id' => $row_ca->user_id, 'firm_id' => $row_ca->firm_id, 'boss_id' => $row_ca->linked_with_boss_id];
//
//            $response['message'] = 'success';
//            $response['code'] = 200;
//            $response['status'] = true;
//        }

        echo json_encode($response);
    }

    public function emp_view_employee() {
        $data['prev_title'] = "View Employee";
        $data['page_title'] = "View Employee";

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }

        $result = $this->emp_model->get_employee_new($firm_id);
        if ($result == false) {
            $data['record'] = "";
//            $this->load->view('client_admin/view_employee', $data);
        } else {
            $data['record'] = $result;
//            $this->load->view('client_admin/view_employee', $data);
        }
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
        $result21 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result21->num_rows() > 0) {
            $record = $result21->row();
            $user_id = $record->user_id;
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
            $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$user_id'");
            if ($result3->num_rows() > 0) {
                $record3 = $result3->row();
                $value_permit = $record->leave_approve_permission;
                $data['val'] = $value_permit;
            } else {
                $data['val'] = '';
            }
            $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$user_id'");
            if ($query_sen_id->num_rows() > 0) {
                $res_sen_id = $query_sen_id->result();
                foreach ($res_sen_id as $row) {
                    $user_id_emp = $row->user_id;
                    $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$user_id_emp' AND status='1'");
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
                $data['leave_type'] = "";
                $data['leave_requested_on'] = "";
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }
        $this->load->view('employee/view_employee', $data);
    }

    public function employee_fetch_emp_data($emp_id = '') {
        $data['prev_title'] = "Edit Employee";
        $data['page_title'] = "Edit Employee";

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id1 = $result1['firm_id'];
        }
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


        if ($query->num_rows() > 0) {

            $query1 = $this->db->query("SELECT `user_header_all`.`user_id`,`user_header_all`.`work_on_services`,`user_header_all`.`sandwich_leave_applicable`,`user_header_all`.`create_service_permission`,`user_header_all`.`enquiry_generate_permission`,`user_header_all`.`create_task_assignment`,
             `user_header_all`.`create_due_date_permission`,`user_header_all`.`leave_approve_permission`,`user_header_all`.`probation_period_start_date`,`user_header_all`.`probation_period_end_date`,`user_header_all`.`training_period_start_date`,`user_header_all`.`training_period_end_date`,`user_header_all`.`senior_user_id`,`user_header_all`.`user_type`,`user_header_all`.`is_employee`,`user_header_all`.`activity_status`,
             `user_header_all`.`firm_id`,`user_header_all`.`user_name`,`user_header_all`.`mobile_no`, `user_header_all`.`email`,`user_header_all`.`skill_set`,`user_header_all`.`address`,`user_header_all`.`city`,
             `user_header_all`.`state`,`user_header_all`.`country`,`user_header_all`.`date_of_joining`,`user_header_all`.`designation_id`,`designation_header_all`.`designation_name`
             FROM `user_header_all`
             INNER JOIN `designation_header_all` ON `designation_header_all`.`designation_id`=`user_header_all`.`designation_id`
             WHERE  user_id = '$emp_id'");
            if ($query1->num_rows() > 0) {
                $record = $query1->row();

                $senior_user_id = $record->senior_user_id;
                $query_senior_name = $this->db->query("select user_name from user_header_all where user_id='$senior_user_id'");
//                echo $this->db->last_query();
                if ($query_senior_name->num_rows() > 0) {
                    $record_senior_name = $query_senior_name->row();
                }

                $data['result_emp_data'] = $record;
                $data['result_senior_name'] = $record_senior_name;
            } else {
                $data['result_emp_data'] = '';
                $data['result_senior_name'] = '';
            }
        }
        $this->load->view('employee/edit_emp_data', $data);
    }

    public function view_employee() {
        $data['prev_title'] = "View Employee";
        $data['page_title'] = "View Employee";

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
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

        $users = $this->db->query("select * from user_header_all where email='$email_id'");
        $user_id1 = $users->row();
        $user_id = $user_id1->email;

        if ($user_id1->user_type == 4) {

            $emp_deatil = $this->db->query("select firm_id from partner_header_all where firm_id=(select firm_id from user_header_all where email='$user_id')")->row();

            $emp_deatils1 = $this->db->query("select * from partner_header_all where firm_id='$emp_deatil->firm_id'")->result();

            if (count($emp_deatils1) > 0) {

                foreach ($emp_deatils1 as $row2) {

                    $hq_id = $this->db->query("select * from user_header_all where user_type='3' and firm_id='" . $row2->firm_id . "' and linked_with_boss_id='" . $row2->boss_id . "'")->result();

                    if (count($hq_id) > 0) {
                        foreach ($hq_id as $row3) {

                            if ($row2->firm_id == $row3->firm_id) {
                                $user_id = $row3->email;
                            } else {

                            }
                        }
                    } else {

                    }
                }
            }
        }


        if ($user_id1->user_type == 3) {
            $ca_deatil = $this->db->query("select * from partner_header_all where firm_email_id='$user_id'")->result();

            if (count($ca_deatil) > 0) {

                foreach ($ca_deatil as $row2) {

                    $hq_id = $this->db->query("select * from user_header_all where user_type='3' and firm_id='" . $row2->firm_id . "'and linked_with_boss_id='" . $row2->reporting_to . "'")->result();

                    if (count($hq_id) > 0) {
                        foreach ($hq_id as $row3) {

                            if ($row2->firm_id != $row3->firm_id) {
                                $user_id = $row3->email;
//                                var_dump($user_id);
                            } else {

                            }
                        }
                    }
                }
            }
        }



        $result = $this->db->query("SELECT * FROM `partner_header_all` WHERE `firm_email_id`='$user_id'");

        if ($result->num_rows() > 0) {
            $record = $result->row();
            $reporting_to = $record->boss_id;
            $no_of_offices_permitted = $record->firm_no_of_employee;
            $query = $this->db->query("select linked_with_boss_id from user_header_all where user_type!=3 and  linked_with_boss_id in (select boss_id FROM `partner_header_all` WHERE  boss_id='$reporting_to' AND `firm_email_id`='$user_id')");

            $total_no_of_offices = $query->num_rows();
            $data['no_of_offices_permitted'] = $no_of_offices_permitted;
            $data['total_no_of_offices'] = $total_no_of_offices;
        } else {
            $data['no_of_offices_permitted'] = '';
            $data['total_no_of_offices'] = '';
        }


        $query = $this->db->query("SELECT `user_type`,`firm_logo`,`user_name` FROM `user_header_all` where `email`= '$email_id'");

        if ($query->num_rows() > 0) {

            $record = $query->row();
            $user_type = $record->user_type;
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
                $data['user_type'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
                $data['user_type'] = $user_type;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
            $data['user_type'] = "";
        }
        $result = $this->emp_model->get_employee();
        //var_dump($result->result());
        //var_dump($result->result());
        if ($result == false) {
            $data['record'] = "";
//            $this->load->view('client_admin/view_employee', $data);
        } else {
            $data['record'] = $result;
//            $this->load->view('client_admin/view_employee', $data);
        }
//        $email_id = $this->session->userdata('login_session');
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }

        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
        }

        $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$user_id'");
        if ($query_sen_id->num_rows() > 0) {
            $res_sen_id = $query_sen_id->result();
            foreach ($res_sen_id as $row) {
                $response['leave'] = array();
                $user_id_emp = $row->user_id;
                $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$user_id_emp' AND status='1'");
                if ($query_find_leave->num_rows() > 0) {
                    $result_sen_id = $query_find_leave->row();
                    $leave_requested_on = $result_sen_id->leave_requested_on;
                    $leave_type = $result_sen_id->leave_type;
                    array_push($response['leave'], array('leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type));
                } else {

                    $leave_requested_on = '';
                    $leave_type = '';
                    $response['leave'] = '';
                }
            }
            $data['leave_type'] = $response['leave'];
            $data['leave_requested_on'] = $response['leave'];
        } else {
            $data['leave_type'] = "";
            $data['leave_requested_on'] = "";
        }


        $this->load->view('client_admin/view_employee', $data);
    }

    public function view_employee_hr($firm_id = '') {
        // dd("abhishek mishra");
        $data['prev_title'] = "View Employee";
        $data['page_title'] = "View Employee";

        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $user_type = ($session_data['user_type']);
            $user_id = ($session_data['emp_id']);
        }
        $session = $user_type;
        if ($session == 5) {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $login_firm_id = $result['firm_id'];
            }
            $query_fetch = $this->db->query("Select hr_authority from user_header_all where user_id ='$user_id' and user_type='5'");
            if ($query_fetch->num_rows() > 0) {
                $hrauth = $query_fetch->row();
                $firm_id = $hrauth->hr_authority;
            }
        } else {
            $firm_id = $firm_id;
        }
        $result = $this->Emp_model->get_employee_hq($firm_id);
        if ($result == false) {
            $data['record'] = "";
        } else {
            $data['record'] = $result;
        }

        $result_firm_name_dd = $this->db->query("SELECT `firm_name`,`firm_id` FROM `partner_header_all` WHERE `firm_id`='$firm_id'");
        if ($result_firm_name_dd->num_rows() > 0) {
            $firm_result_dd = $result_firm_name_dd->row();
            $firm_name_dd = $firm_result_dd->firm_name;
            $firm_id_dd = $firm_result_dd->firm_id;
            $data['firm_name'] = $firm_name_dd;
            $data['firm_id'] = $firm_id;
        } else {
            $firm_name_dd = "";
            $firm_id_dd = "";

            $data['firm_name'] = "";
            $data['firm_id'] = "";
        }

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

        $query = $this->db->query("SELECT `user_type`,`firm_logo`,`user_name` FROM `user_header_all` where `email`= '$email_id'");

        if ($query->num_rows() > 0) {

            $record = $query->row();
            $user_type = $record->user_type;
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
                $data['user_type'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
                $data['user_type'] = $user_type;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
            $data['user_type'] = "";
        }
       /* $users = $this->db->query("select * from user_header_all where email='$email_id'");
        $user_id1 = $users->row();
        $user_id = $user_id1->email;

        $hq_deatil = $this->db->query("select * from user_header_all where email='$email_id'")->result();

        if (count($hq_deatil) > 0) {

            foreach ($hq_deatil as $row2) {
                if ($row2->user_type == 5) {
                    $hq_id = $this->db->query("select * from user_header_all where linked_with_boss_id='" . $row2->linked_with_boss_id . "'")->result();

                    if (count($hq_id) > 0) {
                        foreach ($hq_id as $row3) {
                            if ($row2->user_id != $row3->user_id) {
                                $user_id = $row3->email;
                                // var_dump($user_id);
                            } else {

                            }
                        }
                    }
                }
            }
        }*/




        $result = $this->db->query("SELECT * FROM `partner_header_all` WHERE `firm_id`='$firm_id'");
        if ($result->num_rows() > 0) {
            $record = $result->row();
            $reporting_to = $record->boss_id;
            $no_of_offices_permitted = $record->firm_no_of_employee;
            $query = $this->db->query("select linked_with_boss_id from user_header_all where linked_with_boss_id in (select boss_id FROM `partner_header_all` WHERE  reporting_to='$reporting_to' AND `firm_email_id`!='$user_id')");

            $total_no_of_offices = $query->num_rows();
            $data['no_of_offices_permitted'] = $no_of_offices_permitted;
            $data['total_no_of_offices'] = $total_no_of_offices;
        } else {
            $data['no_of_offices_permitted'] = '';
            $data['total_no_of_offices'] = '';
        }

        $this->load->view('human_resource/view_employee', $data);
    }

    function check_email_avalibility() {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Invalid Email Id</span></label>';
        } else {
            $this->load->model("Emp_model");
            if ($this->Emp_model->is_email_available($_POST["email"])) {
                echo '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Email Is Already register</label>';
            } else {
                echo '<label class="text-success"><span class="glyphicon glyphicon-ok"></span>Valid Email Id</label>';
            }
        }
    }

    public function change_activity_status() {
        $user_id = base64_decode($this->input->post('user_id'));


        $salarytype_query = $this->db->query("select user_id from t_salarytype where user_id='$user_id'");
//        echo $this->db->last_query();
        $applicable_sts_query = $this->db->query("select applicable_status,type1 from user_header_all where user_id='$user_id'");

        $result_sts = $applicable_sts_query->row();
        $applicable_sts = $result_sts->applicable_status;
        $type1 = $result_sts->type1;
//        echo $this->db->last_query();
		if($type1 == 1){
			if ($salarytype_query->num_rows() <= 0) {
				$response['message'] = 'For this employee salary  was not registered';
				$response['code'] = 201;
				echo json_encode($response);
				exit;
			} else if ($applicable_sts == 0) {
				$response['message'] = 'For this employee attendance was not registered';
				$response['code'] = 202;
				echo json_encode($response);
				exit;
			}else if ($type1 == "") {
				$response['message'] = 'Leave Configuaration is pending for this Employee';
				$response['code'] = 202;
				echo json_encode($response);
				exit;
			}
		}

        $status = $this->input->post('status');
        $result = $this->emp_model->activity_status($user_id, $status);


        if ($result === true) {
            $response['msg'] = $status;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function fetch_emp_data_hr($emp_id = '', $firm_id_emp = '') {
        // dd("abhishek mishra");
        $data['prev_title'] = "Edit Employee";
        $data['page_title'] = "Edit Employee";
        if ($firm_id_emp == '') {
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id_emp = $result1['firm_id'];
            }
        }
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

        $query = $this->db->query("SELECT `user_type`,`firm_logo`,`user_name` FROM `user_header_all` where `email`= '$email_id'");
        $employeeName = $this->db->query("SELECT uha.user_name FROM assets_management_mapping amm LEFT JOIN user_header_all uha ON uha.user_id=amm.user_id  WHERE amm.user_id='".$emp_id."'")->result();
        
        if($employeeName) {
            $employee = $employeeName[0]->user_name;
        } else {
            $employee = '';
        }
        
        $q11 = $this->db->query("SELECT `due_date_creation_permitted` from `partner_header_all` where `firm_id`='$firm_id_emp'");
        if ($q11->num_rows() > 0) {
            $due_date = $q11->result();
            foreach ($due_date as $row3) {
                $due_date_creation_permitted = $row3->due_date_creation_permitted;
                $this->session->set_userdata("due_date_permission", $due_date_creation_permitted);
            }
        }

        if ($query->num_rows() > 0) {
            $record = $query->row();
            $user_type = $record->user_type;
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
                $data['user_type'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
                $data['user_type'] = $user_type;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
            $data['user_type'] = "";
        }

        $q1 = $this->db->query("Select * From  user_header_all where `user_id`= '$emp_id'")->result();
        // dd($this->db->last_query());
        if (count($q1) > 0) {
            foreach ($q1 as $record) {
                $data['permission_rs'] = $record;
                $data['status'] = true;
            }
        } else {
            $data['permission_rs'] = "";
            $data['status'] = false;
        }
        $result_get_hr_name = $this->emp_model->get_hr_name_in_hq($firm_id_emp);
        if ($result_get_hr_name != FALSE) {
            $record_hr_name = $result_get_hr_name->row();
            $user_id_hr = $record_hr_name->user_id;
            $user_name_hr = $record_hr_name->user_name;
            $hr_data_hq = array('user_id' => $user_id_hr, 'user_name' => $user_name_hr);
            $data['hr_data_hq'] = $hr_data_hq;
        } else {
            $data['hr_data_hq'] = "";
        }
        $query_fetch_boss_id = $this->db->query("SELECT * from user_header_all where firm_id='$firm_id_emp'");
        if ($query_fetch_boss_id->num_rows() > 0) {
            $record = $query_fetch_boss_id->row();
            $boss_id = $record->linked_with_boss_id;
            $firm_id1 = $record->firm_id;
        }

        $array = array('is_employee' => '1', 'firm_id' => $firm_id1, 'linked_with_boss_id' => $boss_id, 'activity_status' => '1');
        $this->db->select('*');
        $this->db->from('user_header_all');
        $this->db->where($array);
        $query12 = $this->db->get();
        if ($query12->num_rows() > 0) {
            foreach ($query12->result() as $row) {
                $emp_data[] = ['user_name' => $row->user_name, 'user_id' => $row->user_id, 'firm_id' => $row->firm_id, 'boss_id' => $row->linked_with_boss_id];
            }
        }


        $query4 = $this->db->query("select designation_name,designation_id from designation_header_all where firm_id='$firm_id_emp' AND designation_id != 'CA'")->result();
        $all_designation=array();
        if ($query4 != null) {
            foreach ($query4 as $res1) {
                $designid[] = $res1->designation_id;
                $all_designation[$res1->designation_id] = $res1->designation_name;
            }
        }


        if ($query->num_rows() > 0) {
            // echo $this->db->last_query();
            //Fetching employee attendance Details
            $query_attendance_employee = $this->db->query("select * from attendance_employee_applicable where user_id= '$emp_id'");
            if ($query_attendance_employee->num_rows() > 0) {
                $record_day_time1 = $query_attendance_employee->result();
                // var_dump($record_day_time1);
                $data['result_emp_time'] = $record_day_time1;
            } else {
                $data['result_emp_time'] = '';
            }

            //Fetching employee Salary Details
            //Fetching Employee Performance
            $query_emp_performance = $this->db->query("select * from t_perfomanceallowance where user_id= '$emp_id'");
            if ($query_emp_performance->num_rows() > 0) {
                $record_emp_performance = $query_emp_performance->row();

                $data['result_emp_performance'] = $record_emp_performance;
            } else {
                $data['result_emp_performance'] = '';
            }

            //Fetching Employee loan Details
            $query_emp_loandata = $this->db->query("select * from t_staffloan where user_id= '$emp_id'");
            if ($query_emp_loandata->num_rows() > 0) {
                $record_emp_loandata = $query_emp_loandata->row();

                $data['result_emp_loandata'] = $record_emp_loandata;
            } else {
                $data['result_emp_loandata'] = '';
            }

            $query1 = $this->db->query("SELECT `user_header_all`.`user_id`,`user_header_all`.`activity_status`,`user_header_all`.`sandwich_leave_applicable`,`user_header_all`.`gps_off_allow`,`user_header_all`.`hr_authority`,`user_header_all`.`firm_id`,`user_header_all`.`work_on_services`,`user_header_all`.`create_service_permission`,`user_header_all`.`enquiry_generate_permission`,`user_header_all`.`create_task_assignment`,
             	`user_header_all`.`task_approve_permission`,`user_header_all`.`task_sign_permission`,`user_header_all`.`user_star_rating`,`user_header_all`.`handle_web_services`,`user_header_all`.`web_enquiry_handle_permission`,`user_header_all`.`create_due_date_permission`,`user_header_all`.`leave_approve_permission`,`user_header_all`.`probation_period_start_date`,`user_header_all`.`probation_period_end_date`,`user_header_all`.`training_period_start_date`,`user_header_all`.`training_period_end_date`,`user_header_all`.`senior_user_id`,`user_header_all`.`user_type`,`user_header_all`.`is_employee`,`user_header_all`.`activity_status`,
             `user_header_all`.`firm_id`,`user_header_all`.`user_name`,`user_header_all`.`emp_code`,`user_header_all`.`mobile_no`, `user_header_all`.`email`,`user_header_all`.`address`,`user_header_all`.`city`,`user_header_all`.`spouse_name`,`user_header_all`.`date_of_birth`,
             `user_header_all`.`gender`,`user_header_all`.`UAN_no`,`user_header_all`.`permanant_address`,`user_header_all`.`pan_no`,`user_header_all`.`department_name`,`user_header_all`.`account_no`,`user_header_all`.`adhar_no`,`user_header_all`.`bank_name`,
             `user_header_all`.`state`,`user_header_all`.`form16Type`,`user_header_all`.`country`,`user_header_all`.`overtime_applicable_sts`,`user_header_all`.`holiday_working_sts`,`user_header_all`.`salary_calculation`,`user_header_all`.`work_schedule_status`,`user_header_all`.`work_hour_status`,`user_header_all`.`date_of_joining`,`user_header_all`.`designation_id`,`user_header_all`.`skill_set`,`user_header_all`.`fixed_time`,`user_header_all`.`fixed_hour`,
             (select designation_name from designation_header_all where `designation_header_all`.`designation_id`=`user_header_all`.`designation_id`) as designation_name
             FROM `user_header_all`
            
             WHERE  user_id = '$emp_id'");

            if ($query1->num_rows() > 0) {
                $record = $query1->row();
                $firm_data = $record->firm_id;
                $designation_id[] = $record->designation_id;
                foreach ($designid as $designid) {
                    if (in_array($designid, $designation_id)) {
                        $design[] = '';
                    } else {
                        $design[] = $designid;
                    }
                }
                for ($d = 0; $d < count($design); $d++) {
                    $designation_data = $this->db->query("select designation_name,designation_id from designation_header_all where designation_id = '$design[$d]'")->result();
                    if ($designation_data != null) {
                        foreach ($designation_data as $designation_data1) {
                            $data['designation_id'][] = $designation_data1->designation_id;
                            $data['designation_name'][] = $designation_data1->designation_name;
                        }
                    } else {
                        $data['designation_id'][] = '';
                        $data['designation_name'][] = '';
                    }
                }
                $senior_user_id1[] = $record->senior_user_id;
                $emp_id = array();
                $senior_user_id = $record->senior_user_id;
                $query_senior_name = $this->db->query("select user_id,user_name from user_header_all where user_id='$senior_user_id'");

                if ($query_senior_name->num_rows() > 0) {
                    $record_senior_name = $query_senior_name->row();

                    // $data['result_emp_data'] = $record;
                    // $data['result_senior_name'] = $record_senior_name;
                    $data['result_senior_name'] = $record_senior_name;
                } else {
                    $data['result_senior_name'] = '';
                }
                $data['result_emp_data'] = $record;
            } else {
                $data['result_emp_data'] = '';
                $data['result_senior_name'] = '';
            }
        }




        // var_dump($data['result_senior_name'] = $record_senior_name);

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
        //$email_id = $this->session->userdata('login_session');
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
        }

        $query_sen_id = $this->db->query("SELECT user_id FROM `user_header_all` where `senior_user_id`= '$user_id'");
        if ($query_sen_id->num_rows() > 0) {
            $res_sen_id = $query_sen_id->result();
            foreach ($res_sen_id as $row) {
                $user_id_emp = $row->user_id;
                $query_find_leave = $this->db->query("SELECT distinct leave_requested_on,leave_type from leave_transaction_all where user_id='$user_id_emp' AND status='1'");
                if ($query_find_leave->num_rows() > 0) {
                    $result_sen_id = $query_find_leave->row();
                    $leave_requested_on = $result_sen_id->leave_requested_on;
                    $leave_type = $result_sen_id->leave_type;
                    $response['leave'] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
                } else {
                    $leave_requested_on = '';
                    $leave_type = '';
                    $response['leave'] = '';
                }
            }
        } else {
            $leave_requested_on = '';
            $leave_type = '';
            $response['leave'] = '';
        }
        // print_r($data['result_emp_data']);die;
        $data['firm_id_emp'] = $firm_id_emp;
        $data['all_designation'] = $all_designation;
        $data['leave_type'] = $response['leave'];
        $data['emp_firm'] = $firm_id_emp;
        $data['leave_requested_on'] = $response['leave'];
        $data['employee'] = $employee;
        $this->load->view('human_resource/hr_edit_emp_data', $data);
    }

    public function duedate_Name() {
        $firm_id = $this->input->post('firm_id');
        $query2 = $this->db->query("SELECT designation_name FROM `designation_header_all` WHERE firm_id='$firm_id'");

        if ($query2->num_rows() <= 0) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'unsuccess';
            $response['code'] = 200;
            $response['status'] = true;
        }

        echo json_encode($response);
    }

    public function edit_emp() {
        // dd("abhishek mishra");
        $user_id = $this->input->post('user_id');
        $user_name = $this->input->post('user_name');
		$form_type = $this->input->post('Vform_id');
        $mobile_no = $this->input->post('mobile_no');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $Paddress = $this->input->post('Paddress');
        $state = $this->input->post('state');
        $city = $this->input->post('city');
        $skills = $this->input->post('skill_set');
        $country = $this->input->post('country');
        $designation = $this->input->post('designation');
        $senior_emp = $this->input->post('senior_emp');
        $date_of_joining = $this->input->post('date_of_joining');
        $prob_date_first = $this->input->post('prob_date_first');
        $prob_date_second = $this->input->post('prob_date_second');
        $training_period_first = $this->input->post('training_period_first');
        $training_period_second = $this->input->post('training_period_second');
        $prob_period = $this->input->post('probation_period');
        $train_period = $this->input->post('training_period');
        $ot_applicable_sts = $this->input->post('ot_applicable_sts');
        $salary_calcu = $this->input->post('salary_calcu');
        $holiday_applible_sts = $this->input->post('holiday_applible_sts');
        $sandwichLeave = $this->input->post('sandwichLeave');
        $gpsoff = $this->input->post('gpsoff');

        //Newly added basic details

        $spouse_name = $this->input->post('fh_name');
        $date_of_birth = $this->input->post('dob');
        $gender = $this->input->post('gender');
        $uan_no = $this->input->post('uan');
        $pan_no = $this->input->post('pan');
        $dept_name = $this->input->post('department');
        $account_no = $this->input->post('ac_no');
        $adhar_no = $this->input->post('adhar_no');
        $bank_name = $this->input->post('bank_name');

//        $applicable_status = $this->input->post('attendance_employee');

        $work_hour_employee = $this->input->post('work_hour_employee');
        $fixed_time = $this->input->post('fixed_time');

        $work_hour_schedule = $this->input->post('work_hour_schedule');

        $week_days1 = $this->input->post('week_days1');
        $select_schedule_type1 = $this->input->post('select_schedule_type1');
        $select_fixed_hour1 = $this->input->post('select_fixed_hour1');

        $week_days2 = $this->input->post('week_days2');
        $select_schedule_type2 = $this->input->post('select_schedule_type2');
        $select_fixed_hour2 = $this->input->post('select_fixed_hour2');

        $week_days3 = $this->input->post('week_days3');
        $select_schedule_type3 = $this->input->post('select_schedule_type3');
        $select_fixed_hour3 = $this->input->post('select_fixed_hour3');

        $week_days4 = $this->input->post('week_days4');
        $select_schedule_type4 = $this->input->post('select_schedule_type4');
        $select_fixed_hour4 = $this->input->post('select_fixed_hour4');

        $week_days5 = $this->input->post('week_days5');
        $select_schedule_type5 = $this->input->post('select_schedule_type5');
        $select_fixed_hour5 = $this->input->post('select_fixed_hour5');

        $week_days6 = $this->input->post('week_days6');
        $select_schedule_type6 = $this->input->post('select_schedule_type6');
        $select_fixed_hour6 = $this->input->post('select_fixed_hour6');

        $week_days7 = $this->input->post('week_days7');
        $select_schedule_type7 = $this->input->post('select_schedule_type7');
        $select_fixed_hour7 = $this->input->post('select_fixed_hour7');

        $fixed_hour = $this->input->post('fixed_hour');
        $select_fixed_hour = $this->input->post('select_fixed_hour');

        $star_rating = $this->input->post('star_rating');

        $firm_id = $this->input->post('firm_url_id');

        $is_virtual = $this->db->select("is_virtual")->where("firm_id", $firm_id)->get("partner_header_all")->row();
        if ($prob_period === '1') {
            $prob_date_first = '';
            $prob_date_second = '';
        } else {
            $prob_date_first = $this->input->post('prob_date_first');
            $prob_date_second = $this->input->post('prob_date_second');
        }
        if ($train_period === '1') {
            $training_period_first = '';
            $training_period_second = '';
        } else {
            $training_period_first = $this->input->post('training_period_first');
            $training_period_second = $this->input->post('training_period_second');
        }

        $length_address = strlen($address);
        $email_check = $this->check_edit_email_avalibility($email,$user_id);

        if (empty(trim($user_name))) {
            $response['id'] = 'user_name';
            $response['error'] = 'Enter User Name';
            echo json_encode($response);
        }elseif(empty($spouse_name)){
            $response['id']='fh_name';
            $response['error']='Enter Father/Husband Name';
            echo json_encode($response);
        }elseif(empty($date_of_birth)){
            $response['id']='dob';
            $response['error']='Enter Date Of Birth';
            echo json_encode($response);
        }elseif(empty($gender)){
            $response['id']='gender';
            $response['error']='Select Gender';
            echo json_encode($response);
        }
        elseif (empty($mobile_no)) {
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter User Mobile No';
            echo json_encode($response);
        } elseif (!preg_match("/^\d{10}$/", $mobile_no)) { // phone number is valid
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter 10 Digit Mobile No.';
            echo json_encode($response);
        } 
        else if (empty(trim($email))) {
            $sql = 'SELECT email FROM user_header_all WHERE user_id = ?';
            $query = $this->db->query($sql, [$user_id]);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $email = $row->email;
            } else {
                $response['id'] = 'email';
                // $response['error'] = 'Email not found';
                echo json_encode($response);
                exit;
            }
        }
        elseif (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) {
            $response['id'] = 'email';
            $response['error'] = "Invalid Email Format";
            echo json_encode($response);
        }
        // elseif ($email_check==true) {
        //     $response['id'] = 'email';
        //     $response['error'] = "Email Is Already Exist";
        //     echo json_encode($response);
        // }
        elseif (empty($uan_no)) {
            $response['id']='uan';
            $response['error']="Enter Uan Number";
            echo json_encode($response);
                    }
        elseif(empty($pan_no)){
            $response['id']='pan';
            $response['error']="Enter Pan Number";
            echo json_encode($response);
        }elseif(empty($dept_name)){
            $response['id']='dept_name';
            $response['error']="Enter Department Name";
            echo json_encode($response);
        }elseif(empty($account_no)){
            $response['id']='ac_no';
            $response['error']="Enter Account Number";
            echo json_encode($response);

        }elseif(empty($adhar_no)){
            $response['id']='adhar_no';
            $response['error']="Enter Adhar Card Number";
            echo json_encode($response);
        }elseif(empty($bank_name)){
            $response['id']='bank_name';
            $response['error']="Enter Bank Name";
            echo json_encode($response);
        }

        elseif (empty(trim($address))) {
            $response['id'] = 'address';
            $response['error'] = 'Enter Current Address';
            echo json_encode($response);
        }elseif (empty(trim($Paddress))) {
            $response['id'] = 'Paddress';
            $response['error'] = 'Enter Permanant Address';
            echo json_encode($response);
        } else if ($length_address < 5) {
            $response['id'] = 'address';
            $response['error'] = 'Entered Address Must Be Greater Than 5 Characters';
            echo json_encode($response);
        } elseif (empty(trim($country))) {
            $response['id'] = 'country';
            $response['error'] = 'Enter HR Country';
            echo json_encode($response);
        } elseif (empty(trim($state))) {
            $response['id'] = 'state';
            $response['error'] = 'Enter HR State';
            echo json_encode($response);
        } elseif (empty(trim($city))) {
            $response['id'] = 'city';
            $response['error'] = 'Enter HR City';
            echo json_encode($response);
        } elseif (empty($date_of_joining)) {
            $response['id'] = 'date_of_joining';
            $response['error'] = 'Enter Date Of Joining';
            echo json_encode($response);
        } elseif (empty($ot_applicable_sts)) {
            $response['id'] = 'ot_applicable_sts';
            $response['error'] = 'Please Select';
            echo json_encode($response);
        } else {

            $data = array(
                'user_name' => $user_name,
				'form16Type' => 2,
                'mobile_no' => $mobile_no,
                'email' => $email,
                'state' => $state,
                'city' => $city,
                'address' => $address,
                'permanant_address' => $Paddress,
                'country' => $country,
                'skill_set' => $skills,
                'designation_id' => $designation,
                'senior_user_id' => $senior_emp,
                'date_of_joining' => $date_of_joining,
                'probation_period_start_date' => $prob_date_first,
                'probation_period_end_date' => $prob_date_second,
                'training_period_start_date' => $training_period_first,
                'training_period_end_date' => $training_period_second,
                'user_star_rating' => $star_rating,
                'spouse_name' =>$spouse_name ,
                'department_name' =>$dept_name  ,
                'UAN_no' =>$uan_no ,
                'account_no' =>$account_no ,
                'gender' =>$gender ,
                'pan_no' =>$pan_no ,
                'date_of_birth' =>$date_of_birth ,
                'adhar_no' =>$adhar_no ,
                'bank_name' =>$bank_name ,
                'sandwich_leave_applicable' =>$sandwichLeave ,
                'gps_off_allow' =>$gpsoff ,
                // 'applicable_status' => $applicable_status,
                // 'work_hour_status' => $work_hour_employee,
                // 'fixed_time' => $fixed_time,
                // 'work_schedule_status' => $work_hour_schedule,
                // 'fixed_hour' => $fixed_hour,
                'overtime_applicable_sts' => $ot_applicable_sts,
                'salary_calculation' => $salary_calcu,
                'holiday_working_sts' => $holiday_applible_sts,
            );

            $array = array('user_id' => $user_id);
            $this->db->where($array);
            $res = $this->db->update('user_header_all', $data);
            if ($res == TRUE) {
                if(date("Y-m-d")<=$prob_date_second)
                {
                    $this->setLeaveConfigurationWhenOnProbationEmp($user_id);
                }
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        }
    }

    function check_edit_email_avalibility($email,$user_id) {
        //check hereemail id is available in database or not
        $this->load->model("Emp_model");
        if ($this->Emp_model->is_edit_email_available($email,$user_id)==true) {
            return true;
            // echo '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Email Is Already register</label>';
        } else {
            return false;
            //  echo '<label class="text-success"><span class="glyphicon glyphicon-ok"></span>Valid Email Id</label>';
        }
    }

    public function edit_salary() {
        $salary = $this->input->post("value");
        $salary1 = $this->input->post("value1");
        $sal_options = $this->input->post("sal_options");
        $user_id = $this->input->post("user_id");
        $emp_firm = $this->input->post("emp_firm");

        $check_salary_data = $this->db->query("select * from t_salarytype where user_id='$user_id'");
        if ($check_salary_data->num_rows() <= 0) {
//            echo"insert";
            $data_sal = array('user_id' => $user_id, 'firm_id' => $emp_firm, 'value' => $salary, 'payroll_id' => $sal_options);
            $query = $this->db->insert("t_salarytype ", $data_sal);
            if ($query == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        } else {
//         echo"update";
            $data = array(
                'value' => $salary1,
                'payroll_id' => $sal_options
            );
            $array = array('user_id' => $user_id, 'firm_id' => $emp_firm);
            $this->db->where($array);
            $res = $this->db->update('t_salarytype', $data);


            if ($res == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        }
    }

    public function edit_add_performance() {
        $user_id = $this->input->post("user_id");
        $emp_firm = $this->input->post("emp_firm");
        $value = $this->input->post("value");
        $Month = $this->input->post("Month");
        $Month1 = $this->input->post("Month1");
        $years = $this->input->post("Fyear");
        $years1 = $this->input->post("Fyear1");
        $Date_of_PA = $this->input->post("Date_of_PA");
        $check_emp_perform_data = $this->db->query("select * from t_perfomanceallowance  where user_id='$user_id'");
        if ($check_emp_perform_data->num_rows() <= 0) {
            $data = array('user_id' => $user_id, 'firm_id' => $emp_firm, 'value' => $value, 'Month' => $Month, 'FYear' => $years, 'Date_of_PA' => $Date_of_PA);
            $query = $this->db->insert("t_perfomanceallowance ", $data);
            if ($query == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        } else {
            $data = array('user_id' => $user_id, 'firm_id' => $emp_firm, 'value' => $value, 'Month' => $Month1, 'FYear' => $years1, 'Date_of_PA' => $Date_of_PA);
            $array = array('user_id' => $user_id, 'firm_id' => $emp_firm);
            $this->db->where($array);
            $res = $this->db->update('t_perfomanceallowance', $data);
            if ($res == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        }
    }

    public function add_performance_info() {
        $firm_id = $this->input->post('emp_firm');
        $user_id = $this->input->post('emp_id');
        $value = $this->input->post('value');
        $Month = $this->input->post('Month');
        $FYear = $this->input->post('Fyear');
        $Date_of_PA = $this->input->post('Date_of_PA');

        if (empty(trim($value))) {
            $response['id'] = 'value_perform';
            $response['error'] = 'Enter Bonus';
            echo json_encode($response);
        } elseif (empty($Month)) {
            $response['id'] = 'Month';
            $response['error'] = 'Select Month';
            echo json_encode($response);
        } elseif (empty($FYear)) {
            $response['id'] = 'FYear';
            $response['error'] = 'Select Year';
            echo json_encode($response);
        } elseif (empty($Date_of_PA)) {
            $response['id'] = 'Date';
            $response['error'] = 'Select Date';
            echo json_encode($response);
        } else {

            $data_controller = array(
                "firm_id" => $firm_id,
                "user_id" => $user_id,
                "value" => $value,
                "Month" => $Month,
                "FYear" => $FYear,
                "Date_of_PA" => $Date_of_PA
            );
//            var_dump($data_controller);exit;

            if ($this->Emp_model->add_user($data_controller)) {
                $response['saldata'] = $data_controller;
                $response["status"] = true;
                $response["body"] = "Performance Added Successfully";
            } else {
                $response['saldata'] = $data_controller;
                $response["status"] = false;
                $response["body"] = "Failed To Add Performance";
            } echo json_encode($response);
        }
    }

    public function get_performance_details() { //function by rajashree
        $id = $this->input->post('id');
        $where = array('id' => $id);
        $get_perform = $this->Emp_model->get_performance_list($where);
        if ($get_perform !== FALSE) {
            $result = $get_perform->row();
            $response['result'] = $result;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_performance_info() { //get table data for performance allowance //function by rajashree
        $firm_name = $this->input->post("firm_name");
        $user_id = $this->input->post("user_id");
//        $query = $this->db->query("select id,user_id,payroll_id,value from t_salarytype where user_id='$user_id' and firm_id ='$firm_name'");
        $query = $this->db->query("select * from t_perfomanceallowance where user_id= '$user_id'");
//
        $data = '';
        $data .= '<table style="width: 100%;" id="performance_table" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Performance Bonus</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>';
        if ($this->db->affected_rows() > 0) {

            $result1 = $query->result();
            foreach ($result1 as $row) {
                $btn_del = "<button type='button' onclick='delete_performance(\"" . $row->id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-trash'  style='font-size: 23px !important; color: #d43535!important;'> </i> </button>";
                $btn_edit = "<button type='button' onclick='edit_performance_btn(\"" . $row->id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-pencil'  style='font-size: 23px !important; color: #337ab7!important;'> </i> </button>";

                $month = $row->Month;
                if ($month == 1) {
                    $month1 = "January";
                } else if ($month == 2) {
                    $month1 = "February";
                } else if ($month == 3) {
                    $month1 = "March";
                } else if ($month == 4) {
                    $month1 = "April";
                } else if ($month == 5) {
                    $month1 = "May";
                } else if ($month == 6) {
                    $month1 = "June";
                } else if ($month == 7) {
                    $month1 = "July";
                } else if ($month == 8) {
                    $month1 = "August";
                } else if ($month == 9) {
                    $month1 = "September";
                } else if ($month == 10) {
                    $month1 = "October";
                } else if ($month == 11) {
                    $month1 = "November";
                } else if ($month == 12) {
                    $month1 = "December";
                }

                $newcompletionDate1 = date("d-m-yy", strtotime($row->Date_of_PA));

                $data .= '<tr>
                    <td>' . $row->id . '</td>
                    <td>' . $row->value . '</td>
                    <td>' . $month1 . '</td>
                    <td>' . $row->FYear . '</td>
                    <td>' . $newcompletionDate1 . '</td>
                    <td>' . $btn_del . $btn_edit . '</td>
                    </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_sal"] = $data;
        } else {
            $response["result_sal"] = $data;
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    public function delete_performance() { //function by rajashree
        $id = $this->input->post_get("id");
        $result_sal_data = $this->db->query("select * from t_perfomanceallowance where id='$id'");
        if ($this->db->affected_rows() > 0) {
            $date = date("Y/m/d");
            $result = $result_sal_data->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $value = $result->value;
            $Month = $result->Month;
            $FYear = $result->FYear;
            $Date_of_PA = $result->Date_of_PA;
//            $date = $result->date;
            $data_controller1 = array("firm_id" => $firm_id, "user_id" => $user_id, "value" => $value, "Month" => $Month, "FYear" => $FYear, "Date_of_PA" => $Date_of_PA);
//            var_dump($salarydata);
//            exit;
            $q2 = $this->Emp_model->h_PA($data_controller1);
            if ($q2 == true) {
                $q3 = $this->db->query("Delete from t_perfomanceallowance where id= '$id'");
                if ($q3 == true) {
                    $response["status"] = 200;
                    $response["body"] = "Delete Successfully";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed To Delete Performance ";
                }//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed To Delete";
            }
        } else {
            $response["body"] = "Failed To Update Target";
        }echo json_encode($response);
    }

    public function UpadtePerformanceInfo() {
//       echo "update";
        $id = $this->input->post_get("id");
        $value11 = $this->input->post_get("value");
        $Month1 = $this->input->post_get("Month");
        $Fyear = $this->input->post_get("Fyear");
        $Date_of_PA = $this->input->post_get("Date_of_PA");

        $result_sal_data = $this->db->query("select * from t_perfomanceallowance where id='$id'");
        if ($this->db->affected_rows() > 0) {
//            $date = date("Y/m/d");
            $result = $result_sal_data->row();
            $firm_id = $result->firm_id;
            $user_id = $result->user_id;
            $value = $result->value;
            $Month = $result->Month;
            $FYear = $result->FYear;
            $Date_of_PA = $result->Date_of_PA;
            $data_controller1 = array("firm_id" => $firm_id, "user_id" => $user_id, "value" => $value, "Month" => $Month, "FYear" => $FYear, "Date_of_PA" => $Date_of_PA);
            $q2 = $this->Emp_model->h_PA($data_controller1);
            if ($q2 == true) {

                $q3 = $this->db->query("Delete from t_perfomanceallowance where id= '$id'");
                $data_controller = array(
                    "value" => $value11,
                    "user_id" => $user_id,
                    "firm_id" => $firm_id,
                    "Month" => $Month1,
                    "Fyear" => $Fyear,
                    "Date_of_PA" => $Date_of_PA,
                );
//                var_dump($data_controller);exit;

                if ($q3 == true) {
                    $q4 = $this->Emp_model->add_user($data_controller);
                    $response["status"] = 200;
                    $response["body"] = "Update Successfully";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed To Update Salary Type";
                }//                $response["query"] = $this->db->last_query();
            } else {
                $response["body"] = "Failed To Update";
            }
        } else {
            $response["body"] = "Failed To Update Target";
        }echo json_encode($response);
    }

    public function edit_add_staffloan() {
        $user_id = $this->input->post("user_id");
        $emp_firm = $this->input->post("emp_firm");
        $loan_detail = $this->input->post("loan_detail");
        $amount = $this->input->post("amount");
        $EMI_amt = $this->input->post("EMI_amt");
        $from_month = $this->input->post("from_month");
        $from_month1 = $this->input->post("from_month1");
        $Fyear = $this->input->post("Fyear");
        $total_month = $this->input->post("total_month");
        $sanction_date = $this->input->post("sanction_date");
        $check_emp_staffdata = $this->db->query("select * from t_staffloan  where user_id='$user_id'");
        if ($check_emp_staffdata->num_rows() <= 0) {
            $data = array('user_id' => $user_id, 'firm_id' => $emp_firm, 'loan_detail' => $loan_detail, 'amount' => $amount, 'EMI_amt' => $EMI_amt, 'from_month' => $from_month, 'FYear' => $Fyear, 'total_month' => $total_month, 'sanction_date' => $sanction_date, 'status' => '1');
            $query = $this->db->insert("t_staffloan ", $data);
            if ($query == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        } else {
            $data = array('user_id' => $user_id, 'firm_id' => $emp_firm, 'loan_detail' => $loan_detail, 'amount' => $amount, 'EMI_amt' => $EMI_amt, 'from_month' => $from_month1, 'FYear' => $Fyear, 'total_month' => $total_month, 'sanction_date' => $sanction_date, 'status' => '1');
            $array = array('user_id' => $user_id, 'firm_id' => $emp_firm);
            $this->db->where($array);
            $res = $this->db->update('t_staffloan', $data);
            if ($res == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        }
    }

    public function edit_add_duedetails() {
        $user_id = $this->input->post("user_id");
        $emp_firm = $this->input->post("emp_firm");
        $ded_options = $this->input->post("ded_options");
        $ded_options1 = $this->input->post("ded_options1");
        $value = $this->input->post("value");
        $check_emp_staffdata = $this->db->query("select * from t_standarddeductions  where user_id='$user_id'");
        if ($check_emp_staffdata->num_rows() <= 0) {
            $data = array('user_id' => $user_id, 'firm_id' => $emp_firm, 'deduction_id' => $ded_options, 'value' => $value);
            $query = $this->db->insert("t_standarddeductions ", $data);
            if ($query == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        } else {
            $data = array('user_id' => $user_id, 'firm_id' => $emp_firm, 'deduction_id' => $ded_options1, 'value' => $value);
            $array = array('user_id' => $user_id, 'firm_id' => $emp_firm);
            $this->db->where($array);
            $res = $this->db->update('t_standarddeductions', $data);
            if ($res == TRUE) {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        }
    }

    public function edit_attendance_employee() {
        $select_employee = $this->input->post('user_id');
        $firm_name_check = $this->input->post('emp_firm');
        $applicable_status = $this->input->post('attendance_employee');

        $work_hour_employee = $this->input->post('work_hour_employee');
        $fixed_time = $this->input->post('fixed_time');

        $work_hour_schedule = $this->input->post('work_hour_schedule');
        $fixed_hour = $this->input->post('fixed_hour');

        $late_applicable_sts = $this->input->post('late_applicable_sts');
        $late_salary_count = $this->input->post('late_salary_count');
        $late_salary_days = $this->input->post('late_salary_days');
        $late_minute_time = $this->input->post('late_minute_time');

        //Outside and shift applicable sts
        $outside_punch_applicable = $this->input->post('outside_punch_applicable');
        $shift_applicable = $this->input->post('shift_applicable');


		$employee_firms = $this->input->post('employee_firms');

        if ($applicable_status == 2) {
            $work_hour_employee = 2;
            $outside_punch_applicable = 1;
            $shift_applicable = 2;
            $fixed_time = '';
            $work_hour_schedule = '';
            $fixed_hour = '';
            $late_salary_count = '';
            $late_salary_days = '';
            $late_minute_time = '';
            $late_applicable_sts = 0;
            $select_fixed_hour = '';
        }
        if ((empty($firm_name_check))) {
            $response['id'] = 'firm_name_sal_attnd';
            $response['error'] = 'Select Firm';
            echo json_encode($response);
            exit;
        } elseif ((empty($select_employee))) {
            $response['id'] = 'select_employee';
            $response['error'] = 'Select Employee';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && empty(trim($outside_punch_applicable)))) {
            $response['id'] = 'outside_punch_applicable';
            $response['error'] = 'Please select';
            echo json_encode($response);
            exit;
        } elseif (($applicable_status == 1 && empty(trim($shift_applicable)))) {
            $response['id'] = 'shift_applicable';
            $response['error'] = 'Please select';
            echo json_encode($response);
            exit;
        }
//        elseif (($applicable_status == 1 && $work_hour_employee == 1 && empty(trim($fixed_time)))) {
//            $response['id'] = 'fixed_time';
//            $response['error'] = 'Enter time';
//            echo json_encode($response);
//            exit;
//        }
//        elseif (($applicable_status == 1 && $work_hour_schedule == 1 && empty(trim($fixed_hour)))) {
//            $response['id'] = 'fixed_hour';
//            $response['error'] = 'Enter Fixed Hour';
//            echo json_encode($response);
//            exit;
//        }
//        elseif (($applicable_status == 1 && $work_hour_schedule == 1 && $work_hour_employee == 1 && empty(trim($fixed_time)))) {
//            $response['id'] = 'fixed_time';
//            $response['error'] = 'Enter Fixed Time';
//            echo json_encode($response);
//            exit;
//        }
        elseif (($late_applicable_sts == 1 && empty(trim($late_salary_days)))) {
            $response['id'] = 'late_salary_days';
            $response['error'] = 'Please select type';
            echo json_encode($response);
            exit;
        } elseif (($late_applicable_sts == 1 && empty(trim($late_salary_count)))) {
            $response['id'] = 'late_salary_count';
            $response['error'] = 'Please select type';
            echo json_encode($response);
            exit;
        } elseif (($late_applicable_sts == 1 && empty(trim($late_minute_time)))) {
            $response['id'] = 'late_minute_time';
            $response['error'] = 'Please enter time';
            echo json_encode($response);
            exit;
        } else {

            if ($applicable_status != 1) { //applicable status = 2,3,4,5
                $check_attend_app_data = $this->db->query("select * from attendance_employee_applicable where user_id='$select_employee'");
//            echo $this->db->last_query();
                if ($check_attend_app_data->num_rows >= 0) {
                    $select_employee = $this->input->post('user_id');
                    $delete_applicable_data = $this->db->query("delete from attendance_employee_applicable where user_id='$select_employee'");
                    $data_applicable_insert = array(
                        'applicable_status' => $applicable_status,
                        'outside_punch_applicable' => $outside_punch_applicable,
                        'shift_applicable' => $shift_applicable,
                        'work_hour_status' => $work_hour_employee,
                        'fixed_time' => $fixed_time,
                        'fixed_hour' => $fixed_hour,
                        'work_schedule_status' => $work_hour_schedule,
                        'late_applicable_sts' => $late_applicable_sts,
                        'late_salary_cut_days' => $late_salary_days,
                        'late_salary_days_count' => $late_salary_count,
                        'late_minute_count' => $late_minute_time
                    );

                    $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                    $this->db->where($array);
                    $query_update = $this->db->update('user_header_all', $data_applicable_insert);
                } else {
                    $data_applicable_insert = array(
                        'applicable_status' => $applicable_status,
                        'outside_punch_applicable' => $outside_punch_applicable,
                        'shift_applicable' => $shift_applicable,
                        'work_hour_status' => $work_hour_employee,
                        'fixed_time' => $fixed_time,
                        'fixed_hour' => $fixed_hour,
                        'work_schedule_status' => $work_hour_schedule,
                        'late_applicable_sts' => $late_applicable_sts,
                        'late_salary_cut_days' => $late_salary_days,
                        'late_salary_days_count' => $late_salary_count,
                        'late_minute_count' => $late_minute_time
                    );

                    $select_employee = $this->input->post('user_id');
                    $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                    $this->db->where($array);
                    $query_update = $this->db->update('user_header_all', $data_applicable_insert);
                }
                if ($query_update == TRUE) {

                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
                echo json_encode($response);
            } else {
                $check_emp_attend_data = $this->db->query("select * from attendance_employee_applicable  where user_id='$select_employee'");
                if ($check_emp_attend_data->num_rows() <= 0) {
                    if ($work_hour_schedule == 2 && $late_applicable_sts != '') {
                        $cnt = 1;
                        for ($j = 1; $j <= 7; $j++) {
                            $week_days = $this->input->post("week_days" . $j);
                            $select_schedule_type = $this->input->post("select_schedule_type" . $j);
                            $select_fixed_hour = $this->input->post("select_fixed_hour" . $j);
                            $fix_in_appli_sts = $this->input->post("fix_in_appli_sts" . $j);
                            $inapplicable_time = $this->input->post("inapplicable_time" . $j);
                            if ($select_schedule_type == 2) {
                                $fix_in_appli_sts = '';
                                $inapplicable_time = '';
                            } else {
                                $fix_in_appli_sts = $this->input->post("fix_in_appli_sts" . $j);
                                $inapplicable_time = $this->input->post("inapplicable_time" . $j);
                            }
                            $data = array(
                                'user_id' => $select_employee,
                                'firm_id' => $firm_name_check,
                                'day' => $week_days,
                                'type' => $select_schedule_type,
                                'fixed_hour' => $select_fixed_hour,
                                'in_time_applicable_sts' => $fix_in_appli_sts,
                                'in_fixed_time' => $inapplicable_time
                            );
                            $work_hour_employee = 0;

                            $data_applicable2 = array(
                                'applicable_status' => $applicable_status,
                                'outside_punch_applicable' => $outside_punch_applicable,
                                'shift_applicable' => $shift_applicable,
                                'work_schedule_status' => $work_hour_schedule,
                                'work_hour_status' => $work_hour_employee,
                                'fixed_time' => $fixed_time,
                                'fixed_hour' => $fixed_hour,
                                'late_applicable_sts' => $late_applicable_sts,
                                'late_salary_cut_days' => $late_salary_days,
                                'late_salary_days_count' => $late_salary_count,
                                'late_minute_count' => $late_minute_time
                            );
//                            var_dump($data_applicable2);exit;
                            $query = $this->db->insert('attendance_employee_applicable', $data);

                            if ($query == TRUE) {
                                $cnt++;
                            }
                        }

                        if ($cnt > 1) {
                            $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                            $this->db->where($array);
                            $this->db->update('user_header_all', $data_applicable2);

                            $response['message'] = 'success';
                            $response['code'] = 200;
                            $response['status'] = true;
                        } else {
                            $response['message'] = 'No data to display';
                            $response['code'] = 204;
                            $response['status'] = false;
                        }
                        echo json_encode($response);
                    } else {
//                        echo "test2";
//                echo "yhgyhg";
                        $data = array(
                            'user_id' => $select_employee,
                            'firm_id' => $firm_name_check,
                            'applicable_status' => $applicable_status,
                            'outside_punch_applicable' => $outside_punch_applicable,
                            'shift_applicable' => $shift_applicable,
                            'work_hour_status' => $work_hour_employee,
                            'work_schedule_status' => $work_hour_schedule,
                            'fixed_time' => $fixed_time,
                            'fixed_hour' => $fixed_hour,
                            'late_applicable_sts' => $late_applicable_sts,
                            'late_salary_cut_days' => $late_salary_days,
                            'late_salary_days_count' => $late_salary_count,
                            'late_minute_count' => $late_minute_time
                        );
                        //                var_dump($data);
                        $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                        $this->db->where($array);
                        $record = $this->db->update('user_header_all', $data);
                        if ($record == TRUE) {

                            $response['message'] = 'success';
                            $response['code'] = 200;
                            $response['status'] = true;
                        } else {
                            $response['message'] = 'No data to display';
                            $response['code'] = 204;
                            $response['status'] = false;
                        }
                        echo json_encode($response);
                    }
                } else {

                    //                 echo"test3";
                    $applicable_status1 = $this->input->post('attendance_employee');

                    $outside_punch_applicable1 = $this->input->post('outside_punch_applicable');
                    $shift_applicable1 = $this->input->post('shift_applicable');

                    $work_hour_employee1 = $this->input->post('work_hour_employee');
                    $fixed_time1 = $this->input->post('fixed_time');

                    $work_hour_schedule1 = $this->input->post('work_hour_schedule');
                    $fixed_hour1 = $this->input->post('fixed_hour');

                    $late_applicable_sts1 = $this->input->post('late_applicable_sts');
                    $late_salary_days1 = $this->input->post('late_salary_days');
                    $late_salary_count1 = $this->input->post('late_salary_count');
                    $late_minute_time1 = $this->input->post('late_minute_time');



                    if ($applicable_status1 == 2) {
                        $work_hour_employee1 = 2;
                        $fixed_time1 = '';
                        $outside_punch_applicable1 = 1;
                        $shift_applicable1 = 2;
                        $work_hour_schedule1 = '';
                        $fixed_hour1 = '';
                        $late_applicable_sts1 = 0;
                        $late_salary_count1 = '';
                        $late_salary_days1 = '';
                        $late_salary_count1 = '';
                        $late_applicable_sts1 = '';
                        $late_minute_time1 = '';
                    //                $select_fixed_hour1 = '';
                    } else {
                        $work_hour_employee1 = '0';
                    }

                    $data_applicable = array(
                        'applicable_status' => $applicable_status1,
                        'outside_punch_applicable' => $outside_punch_applicable1,
                        'shift_applicable' => $shift_applicable1,
                        'work_hour_status' => $work_hour_employee1,
                        'fixed_time' => $fixed_time1,
                        'fixed_hour' => $fixed_hour1,
                        'work_schedule_status' => $work_hour_schedule1,
                        'late_applicable_sts' => $late_applicable_sts1,
                        'late_salary_cut_days' => $late_salary_days1,
                        'late_salary_days_count' => $late_salary_count1,
                        'late_minute_count' => $late_minute_time1,
                    );
                    //                    var_dump($data_applicable);exit;


                    $array = array('user_id' => $select_employee, 'firm_id' => $firm_name_check);
                    $this->db->where($array);
                    $res = $this->db->update('user_header_all', $data_applicable);



                    for ($i = 1; $i <= 7; $i++) {
                        $edit_id = $this->input->post("id" . $i);
                        $week_days = $this->input->post("week_days" . $i);
                        $select_schedule_type = $this->input->post("select_schedule_type" . $i);
                        $select_fixed_hour = $this->input->post("select_fixed_hour" . $i);
                        $fix_in_appli_sts = $this->input->post("fix_in_appli_sts" . $i);
                        $inapplicable_time = $this->input->post("inapplicable_time" . $i);
                        if ($select_schedule_type == 2) {
                            $fix_in_appli_sts = '';
                            $inapplicable_time = '';
                        } else {
                            $fix_in_appli_sts = $this->input->post("fix_in_appli_sts" . $i);
                            $inapplicable_time = $this->input->post("inapplicable_time" . $i);
                        }
                        $data_days = array(
                            'day' => $week_days,
                            'type' => $select_schedule_type,
                            'fixed_hour' => $select_fixed_hour,
                            'in_time_applicable_sts' => $fix_in_appli_sts,
                            'in_fixed_time' => $inapplicable_time
                        );
                        //                var_dump($data_days);
                        if ($work_hour_schedule == 2) {
                            $array = array('id' => $edit_id, 'user_id' => $select_employee);
                            $this->db->where($array);
                            $this->db->update('attendance_employee_applicable', $data_days);
                            //                echo $this->db->last_query();
                        }
                    }
                    if ($res == TRUE) {


						if($employee_firms != '' && $employee_firms != null){

							$this->db->delete('employee_firm_master',array('user_id' => $select_employee));
							foreach($employee_firms as $row){
								$data = array(
										'user_id' => $select_employee,
										'firm_id' => $row
								);

								$this->db->insert('employee_firm_master',$data);
							}
						}


                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                    echo json_encode($response);
                }
            }
        }
    }

    public function fetch_user_task() {
        $user_id = $this->input->post('user_id');

        $query1 = $this->db->query("SELECT `task_header_all`.`task_name`,`task_header_all`.`task_id`,`sub_task_header_all`.`sub_task_id`,`sub_task_header_all`.`sub_task_name`,`customer_task_allotment_all`.`customer_id`,`customer_task_allotment_all`.`task_assignment_name`,`customer_task_allotment_all`.`status`,`customer_task_allotment_all`.`completion_date`,`customer_header_all`.`customer_id`,`customer_header_all`.`customer_name`,`customer_task_allotment_all`.`alloted_to_emp_id`
                                        FROM `customer_task_allotment_all`
                                        INNER JOIN `task_header_all`
                                        ON `task_header_all`.`task_id`=`customer_task_allotment_all`.`task_id`
                                        INNER JOIN `sub_task_header_all`
                                        ON `sub_task_header_all`.`sub_task_id`=`customer_task_allotment_all`.`sub_task_id`
                                        INNER JOIN `customer_header_all`
                                        ON `customer_header_all`.`customer_id`=`customer_task_allotment_all`.`customer_id`WHERE  `customer_task_allotment_all`.`alloted_to_emp_id` = '$user_id'");
        if ($query1->num_rows() > 0) {
            $record = $query1->result();
            $response['result_subtask_data'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            $query_convener = $this->db->query("SELECT `task_header_all`.`task_name`,`task_header_all`.`task_id`,`customer_task_allotment_all`.`customer_id`,`customer_task_allotment_all`.`task_assignment_name`,`customer_task_allotment_all`.`status`,`customer_task_allotment_all`.`completion_date`,`customer_header_all`.`customer_id`,`customer_header_all`.`customer_name`,`customer_task_allotment_all`.`alloted_to_emp_id`
                                                FROM `customer_task_allotment_all`
                                                INNER JOIN `task_header_all` ON `task_header_all`.`task_id`=`customer_task_allotment_all`.`task_id`
                                                INNER JOIN `customer_header_all` ON `customer_header_all`.`customer_id`=`customer_task_allotment_all`.`customer_id`
                                                WHERE `customer_task_allotment_all`.`alloted_to_emp_id` = '$user_id' AND `customer_task_allotment_all`.`sub_task_id`=''");


            if ($query_convener->num_rows() > 0) {
                $record_con = $query_convener->result();
                $response['result_task_data'] = $record_con;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                $query_cust_subtask = $this->db->query("SELECT `custom_sub_task_header_all`.`customer_id`,`customer_header_all`.`customer_name`,`custom_sub_task_header_all`.`completion_date`,`custom_sub_task_header_all`.`status`,`customer_task_allotment_all`.`task_assignment_name`,`custom_sub_task_header_all`.`sub_task_name`
                                                        FROM `custom_sub_task_header_all`
                                                        INNER JOIN `customer_task_allotment_all`
                                                        ON `custom_sub_task_header_all`.`task_assignment_id`=`customer_task_allotment_all`.`task_assignment_id`
                                                        INNER JOIN `customer_header_all`
                                                        on `customer_header_all`.`customer_id`=`custom_sub_task_header_all`.`customer_id`
                                                        WHERE `custom_sub_task_header_all`.`alloted_to`='$user_id' AND `customer_task_allotment_all`.`sub_task_id`=''");
                if ($query_cust_subtask->num_rows() > 0) {
                    $query_task_Assi_name = $this->db->query("select `task_assignment_id` from `custom_sub_task_header_all` where `alloted_to`='$user_id'");
                    if ($query_task_Assi_name->num_rows() > 0) {
                        $record_taid = $query_task_Assi_name->row();
                        $task_assi_id = $record_taid->task_assignment_id;
                        $query_task_id = $this->db->query("select `task_id` from `customer_task_allotment_all` where `task_assignment_id`='$task_assi_id'");
                        if ($query_task_id->num_rows() > 0) {
                            $record_tid = $query_task_id->row();
                            $task_id = $record_tid->task_id;
                            $query_task_id = $this->db->query("select `task_name` from `task_header_all` where `task_id`='$task_id'");
                            if ($query_task_id->num_rows() > 0) {
                                $record_tname = $query_task_id->result();
                            }
                        }
                    }
                    $record_custsubtask = $query_cust_subtask->result();
                    $response['result_custsubtask_data'] = $record_custsubtask;
                    $response['result_t_name'] = $record_tname;
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                }
            }
        } else {
            $response['result_subtask_data'] = '';
            $response['result_task_data'] = '';
            $response['result_custsubtask_data'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function confirm_emp() {
        $user_id = $this->input->post('user_id');
        $date = date("Y-m-d H:i:s");
        $data = array(
            'confirmation_date' => $date,
        );

        $array = array('user_id' => $user_id);
        $this->db->where($array);
        $res = $this->db->update('user_header_all', $data);

        if ($res == TRUE) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {

        }
        echo json_encode($response);
    }

    public function check_designation_status() {
        $designation_id = $this->input->post('desig');

        $query = $this->db->query("SELECT request_leave_from from designation_header_all where designation_id='$designation_id'");
        if ($query->num_rows() > 0) {
            $record = $query->row();
            $request_leave_from = $record->request_leave_from;

            if ($request_leave_from == 2) {
                $response['message'] = 'probation';
                $response['code'] = 200;
                $response['status'] = true;
            } else if ($request_leave_from == 3) {
                $response['message'] = 'training';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function add_hr_hq() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id_for_created_on = ($session_data['user_id']);
        } else {
            $email_id_for_created_on = $this->session->userdata('login_session');
        }
        $senoior_emp_id = $this->input->post('senoior_emp_id');
        $query = $this->db->query("SELECT * from user_header_all where email='$senoior_emp_id'");
        $record = $query->row();
        $senior_emp = $record->user_id;

        $user_id = $this->generate_user_id();
        $user_name = $this->input->post('user_name');
        $firm_id_ca = $this->input->post('firm_name');


        // $count_firm = count((array)$firm_id_ca);
        //to fetch all leave info from CA// Code by Namrata
        $query_fetch_employee_info = $this->db->query("SELECT * from designation_header_all where firm_id='$firm_id_ca'");
        if ($query_fetch_employee_info->num_rows() > 0) {
            $record_leaves = $query_fetch_employee_info->row();
            $yearly_leave = $record_leaves->total_yearly_leaves;
            $monthly_leaves = $record_leaves->total_monthly_leaves;
            $holiday_status = $record_leaves->holiday_consider_in_leave;
            $leave_cf = $record_leaves->carry_forward_period;
            $leave_apply_permission = $record_leaves->request_leave_from;
            $leave_type_year = $record_leaves->year_type;
        }

        $query_fetch_user_info = $this->db->query("SELECT * from user_header_all where firm_id='$firm_id_ca'");
        if ($query_fetch_user_info->num_rows() > 0) {
            $record_user_info = $query_fetch_user_info->row();
            $date_of_joining = $record_user_info->date_of_joining;
            $prob_date_first = $record_user_info->probation_period_start_date;
            $prob_date_second = $record_user_info->probation_period_end_date;
            $training_period_first = $record_user_info->training_period_start_date;
            $training_period_second = $record_user_info->training_period_end_date;
        }
        $query_fetch_leave_info = $this->db->query("SELECT * from leave_header_all where firm_id='$firm_id_ca'");
        if ($query_fetch_leave_info->num_rows() > 0) {
            $record_leave_info = $query_fetch_leave_info->row();
            $type1 = $record_leave_info->type1;
            $type2 = $record_leave_info->type2;
            $type3 = $record_leave_info->type3;
            $type4 = $record_leave_info->type4;
            $type5 = $record_leave_info->type5;
            $type6 = $record_leave_info->type6;
            $type7 = $record_leave_info->type7;
        }
        $mobile_no = $this->input->post('mobile_no');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $state = $this->input->post('state');
        $city = $this->input->post('city');
        $country = $this->input->post('country');

        $password = rand(100, 1000);
		$length_address = strlen($address);
        $leave_allow_permission = $this->input->post('l_a_permit');
        $date = date('y-m-d h:i:s');
        $designation_name = "Human Resource";
        $designation_id = $this->generate_designation_id();

        $star_rating = '10';

        $email_check = $this->check_email_avalibility1($email);

        $result = $this->firm_model->get_firm_id();
		if(empty($firm_id_ca)){
			$response['id']='firm_name';
			$response['error']='Select Office';
			echo json_encode($response);
		}
        elseif (empty(trim($user_name))) {
            $response['id'] = 'user_name';
            $response['error'] = 'Enter HR Name';
            echo json_encode($response);
        } elseif (empty($mobile_no)) {
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter HR Mobile No';
            echo json_encode($response);
        } elseif (!preg_match("/^\d{10}$/", $mobile_no)) { // phone number is valid
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter 10 Digit Mobile No.';
            echo json_encode($response);
        } elseif (!preg_match("/^[6-9][0-9]{9}$/", $mobile_no)) { // phone number is valid
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter proper Mobile No.';
            echo json_encode($response);
        } elseif (empty($email)) {
            $response['id'] = 'email';
            $response['error'] = 'Enter Email Id';
            echo json_encode($response);
        } elseif (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) {
            $response['id'] = 'email';
            $response['error'] = "Invalid Email Format";
            echo json_encode($response);
        } 
        elseif ($email_check==true) {
            $response['id'] = 'email';
            $response['error'] = "Email Is Already Exist";
            echo json_encode($response);
        } 
        elseif (trim(empty($address))) {
            $response['id'] = 'address';
            $response['error'] = 'Enter Proper Address';
            echo json_encode($response);
        } elseif($length_address < 5){
			$response['id']='address';
			$response['error']='Entered Address Must Be Greater Than 5 Characters';
			echo json_encode($response);
		}elseif (trim(empty($country))) {
            $response['id'] = 'country';
            $response['error'] = 'Enter HR Country';
            echo json_encode($response);
        } elseif (trim(empty($state))) {
            $response['id'] = 'state';
            $response['error'] = 'Enter  HR State';
            echo json_encode($response);
        } elseif (trim(empty($city))) {
            $response['id'] = 'city';
            $response['error'] = 'Enter HR City';
            echo json_encode($response);
        } else {
            if ($result !== false) {
                $firm_id = $result['firm_id'];
                $boss_id_hq = $result['boss_id'];
                $session_coming = $this->session->userdata('login_session');
                $username = $session_coming['user_id'];
            }

            $query_fetch_boss_id = $this->db->query("SELECT * from user_header_all where firm_id='$firm_id'");
            if ($query_fetch_boss_id->num_rows() > 0) {
                $record = $query_fetch_boss_id->row();
                $boss_id = $record->linked_with_boss_id;
            }


            $data_leave = array(
                'type1' => $type1,
                'type2' => $type2,
                'type3' => $type3,
                'type4' => $type4,
                'type5' => $type5,
                'type6' => $type6,
                'type7' => $type7,
                'firm_id' => $firm_id,
                'created_on' => $date,
                'designation_id ' => $designation_id,
                'boss_id' => $boss_id_hq
            );
            $p_data_leave = $this->db->insert('leave_header_all', $data_leave);


            $data_designation = array(
                'boss_id' => $boss_id,
                'firm_id' => $firm_id,
                'designation_id' => $designation_id,
                'designation_name' => $designation_name,
                'created_on' => $date,
                'created_by' => $email_id_for_created_on,
                'total_yearly_leaves' => $yearly_leave,
                'total_monthly_leaves' => $monthly_leaves,
                'holiday_consider_in_leave' => $holiday_status,
                'carry_forward_period' => $leave_cf,
                'request_leave_from' => $leave_apply_permission,
                'year_type' => $leave_type_year
            );
            $data_designation = $this->db->insert('designation_header_all', $data_designation);
            
            $data = array(
                'user_id' => $user_id,
                'user_name' => $user_name,
                'mobile_no' => $mobile_no,
                'state' => $state,
                'city' => $city,
                'email' => $email,
                'address' => $address,
                'country' => $country,
                'firm_id' => $firm_id,
                'is_employee' => 0,
                'user_type' => 5,
                'designation_id' => $designation_id,
                'date_of_joining' => $date_of_joining,
                'created_by' => $username,
                'password' => $password,
                'linked_with_boss_id' => $boss_id,
                'boss_id' => $boss_id_hq,
                'senior_user_id' => $senior_emp,
                'leave_approve_permission' => $leave_allow_permission,
                'hr_authority' => $firm_id_ca,
                'user_star_rating' => $star_rating,
                'probation_period_start_date' => $prob_date_first,
                'probation_period_end_date' => $prob_date_second,
                'training_period_start_date' => $training_period_first,
                'training_period_end_date' => $training_period_second,
                'activity_status' => 0
            );
        //            var_dump($data);
        //            exit;


            $result1 = $this->db->query("SELECT firm_name from partner_header_all where firm_id='$firm_id'");
            if ($result1->num_rows() > 0) {
                $data1 = $result1->row();
                $firm_name = $data1->firm_name;
            }

            // sms function code
            $auth_key = "178904AVN94GK259e5e87b";
            $firm_no_of_permitted_offices = "";
            $firm_no_of_employee = "";
            $firm_no_of_customers = "";
            $trigger_by = "human_resource";
            $no = $data['mobile_no'];
            $boss_name = $data['user_name'];
            $firm_name = $firm_name;
            $firm_email_id = $data['email'];
            $user_type = $data['user_type'];

            $record = $this->emp_model->add_emp($data);
            if ($record == TRUE) {

                $this->db->set('hr_created', '1');
                $this->db->where('firm_id', $firm_id_ca);
                $this->db->update('partner_header_all');

                //email  function
                $email = $this->email_sending_model->sendEmail($user_type, $boss_name, $firm_name, $firm_no_of_permitted_offices, $firm_no_of_employee, $firm_no_of_customers, $firm_email_id, $password, $trigger_by);
                // sms function code
                $this->firm_model->sendSms($auth_key, $no, $user_type, $boss_name, $firm_name, $firm_no_of_permitted_offices, $firm_no_of_employee, $firm_no_of_customers, $firm_email_id, $password, $trigger_by);
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        }
    }

    function check_email_avalibility1($email) {
        //check hereemail id is available in database or not

            $this->load->model("Emp_model");
            if ($this->Emp_model->is_email_available($email)==true) {
                return true;
        //                echo '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Email Is Already register</label>';
            } else {
                return false;
        //                echo '<label class="text-success"><span class="glyphicon glyphicon-ok"></span>Valid Email Id</label>';
            }

    }

    public function generate_designation_id() {
        $designation_id = 'Desig_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('designation_header_all');
        $this->db->where('designation_id', $designation_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return generate_designation_id();
        } else {
            return $designation_id;
        }
    }

    function assign_checklist_to_user1() {

        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = $session_data['user_id'];
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $assign_check_list = $this->db->select(array("hr_authority", "assign_check_list"))
                        ->where(array("email" => $user_id))
                        ->get("user_header_all")->result();

        $hr_auth_list = explode(",", $assign_check_list[0]->hr_authority);

        $userlist = array();
        foreach ($hr_auth_list as $hr_user) {
            $firmusers_id = $this->db->select("user_id")
                            ->where(array("firm_id" => $hr_user))
                            ->get("user_header_all")->result();
            foreach ($firmusers_id as $id) {
                array_push($userlist, $id->user_id);
            }
        }


        if ($assign_check_list[0]->assign_check_list == "") {
            $assignuser_ck_list = array();
        } else {
            $assignuser_ck_list = explode(",", $assign_check_list[0]->assign_check_list);
        }
        // check where it was already exists if yes add into assign user check list
        foreach ($userlist as $hr) {
            if (!in_array($hr, $assignuser_ck_list)) {
                array_push($assignuser_ck_list, $hr);
            }
        }

        // update new assign user list
        $new_assign_check_list = implode(",", $assignuser_ck_list);

        $this->db->set(array("assign_check_list" => $new_assign_check_list))
                ->where((array("email" => $user_id)))
                ->update("user_header_all");
        // check list
        if ($this->db->affected_rows() > 0) {
            // echo json_encode(array("response" => "Check List is assign"));
        } else {
            //   echo json_encode(array("response" => "Faild Assign"));
        }
    }

    public function Allotment_generation() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = $session_data['user_id'];
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        //        var_dump($user_id);
        //        $data['user_email_id']=$user_id;

        $result = $this->db->query("select * from user_header_all where email='$user_id' ")->result();
        if (count($result) > 0) {
            foreach ($result as $row) {
                $allot_enquiry = $row->web_enquiry_handle_permission;
            }
            $response['allot_enquiry'] = $allot_enquiry;


            echo json_encode($response);
        }
    }

    public function get_ca_permission() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = $session_data['user_id'];
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $result = $this->db->query("select * from user_header_all where  created_by='$user_id' ")->result();
        $this->db->last_query();
        if (count($result) > 0) {
            foreach ($result as $row) {
                $employee_permission = $row->employee_permission;
            }
        //            var_dump($employee_permission[2]);

            $response['employee_permission'] = $employee_permission[2];
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //function to get selected user attendance data ---author-->akshay

    public function GetAttendanceInfo() {
        if (!empty($this->input->get_post('userId'))) {
        //            $this->load->model('Employee_login_model');
            $userId = $this->input->get_post('userId');
            $selectData = array('applicable_status');
            $whereData = array('user_id' => $userId);
            $resultData = $this->emp_model->GetAttendanceInfo($selectData, $whereData);
        //            $resultData = $this->Employee_login_model->GetAttendanceInfo($selectData, $whereData);

			$getEmployeefirm = $this->db->query('select firm_id from employee_firm_master where user_id = "'.$userId.'"')->result();
			$FirmData = array();
			if(count($getEmployeefirm) > 0){
				foreach($getEmployeefirm as $row){
					$FirmData[] = $row->firm_id;
				}
			}
            if ($resultData != false) {
                $response['status'] = 200;
                $response['body'] = $resultData;
				$response['firms'] = $FirmData;
            } else {
                $response['status'] = 202;
                $response['body'] = "no user found";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = 'Request Parameters Missing';
        }
        echo json_encode($response);
    }

	public function get_employee_leave_data(){
		$user_id = $this->input->post('user_id');
		$query=$this->db->query("select * from user_header_all where user_id='$user_id'");
		if($this->db->affected_rows() > 0){
			$result=$query->row();
            $current_date = date('Y-m-d');
            $joinTimestamp = strtotime($result->date_of_joining);
            $currentTimestamp = strtotime($current_date);
            $diffInSeconds = $currentTimestamp - $joinTimestamp;
            $diffInDays = floor($diffInSeconds / (60 * 60 * 24));
			$data=array();
			$data['total_leaves']=$result->total_leaves;
			$type1 = 'PL';
			$type2 = 'CL';
			$type3 = 'SL';
			if($result->type1 != '' && $result->type1 != null){
			    $type1 = $result->type1;
            }
            
			if($result->type2 != '' && $result->type2 != null){
			    $type2 = $result->type2;
            }
            if($result->type2 != '' && $result->type2 != null){
			    $type2 = $result->type2;
            }
			if($result->type3 != '' && $result->type3 != null){
			    $type3 = $result->type3;
            }
			$data['type1']=$type1;
			$data['type2']=$type2;
			$data['type3']=$type3;
			$data['type4']=$result->type4;
			$data['type5']=$result->type5;
			$data['type6']=$result->type6;
			$data['type7']=$result->type7;
			$data['type1_balance']=$result->type1_balance;
			$data['type2_balance']=$result->type2_balance;
			$data['type3_balance']=$result->type3_balance;
			$data['type4_balance']=$result->type4_balance;
			$data['type5_balance']=$result->type5_balance;
			$data['type6_balance']=$result->type6_balance;
			$data['type7_balance']=$result->type7_balance;
			$data['accrued_leave']=$result->accrued_leave;
			$data['accrued_period']=$result->accrued_period;
			$data['count_leave_accrued']=$result->count_leave_accrued;
			$data['total_leave_available']=$result->total_leave_available;
			$data['accure_from'] = $result->accure_from;
            $data['prob_end_status'] = 0;
            if(date("Y-m-d")<date('Y-m-d',strtotime($result->probation_period_end_date)))
            {
                $data['prob_end_status'] = 1;
            }
			$response['status'] = 200;
                $response['body'] = $data;

		}else{
			$response['status'] = 201;
            $response['body'] = "";
		}echo json_encode($response);
	}

	public function get_rmt_email_list(){
		$query=$this->db2->query("select distinct email from user_header_all");
		$data="<option value=''>Select email</option>";
		if($this->db2->affected_rows() > 0){
			$result=$query->result();
			foreach($result as $row){
				$option=$row->email;
				$data.="<option value='".$option."'>".$option."</option>";
			}
			$response['status'] = 200;
                $response['body'] = $data;
		}else{
		$response['status'] = 201;
                $response['body'] = "";
	}
		echo json_encode($response);
	}

	public function get_rmt_emp_info(){
		$email=$this->input->post('id');
		$query=$this->db2->query("select * from user_header_all where email='$email'");
		if($this->db2->affected_rows() > 0){
			$result=$query->result();
			$response['status'] = 200;
            $response['body'] = $result;
		}else{
		    $response['status'] = 201;
            $response['body'] = "";
	    }echo json_encode($response);
	}

	public function getEmployeeFirms(){
		$result1 = $this->customer_model->get_firm_id();
		if ($result1 !== false) {
			$firm_id = $result1['firm_id'];
		}
		$query = $this->db->query("select firm_id,firm_name from partner_header_all where firm_activity_status = 'A' and firm_id !='".$firm_id."' ");
		$data = "<option value=''>Select Firm</option>";

		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			foreach ($result as $row) {
				$data .= "<option value='" . $row->firm_id . "'>" . $row->firm_name . "</option>";
			}
			$response['status'] = 200;
			$response['body'] = $data;
		} else {
			$response['status'] = 200;
			$response['body'] = $data;
		}
		echo json_encode($response);

	}

    function setLeaveConfigurationWhenOnProbationEmp($user_id) {
        $leaveArray=array("accrued_leave" => 0,"accrued_period" => '',"accure_from" => '',
            "count_leave_accrued" => '',"type1" => '', "type2" => '', "type3" => '', "type4" => '', "type5" => '', "type6" => '', "type7" => '',
            "type1_balance" => '', "type2_balance" => '', "type3_balance" => '', "type4_balance" => '', "type5_balance" => '',
            "type6_balance" => '', "type7_balance" => '', "total_leaves" => ''
        );
        $this->db->where('user_id', $user_id);
        $query = $this->db->update('user_header_all', $leaveArray);
        return $query;

    }
    
    public function setLeaveConfigCron()
    {
        $userObject = $this->db->query("SELECT GROUP_CONCAT(user_id) AS userIDs FROM user_header_all WHERE probation_period_end_date != '0000-00-00 00:00:00' AND DATE(probation_period_end_date) = CURDATE()");
        $userData = $userObject->row();
        $query = false;
        if (!empty($userData->userIDs)) {
            $leaveArray = array(
                "accrued_leave" => 1,
                "accrued_period" => 1,
                "accure_from" => 1,
                "count_leave_accrued" => 1.5,
                "type1" => 'PL:10:',
                "type2" => 'CL:10:',
                "type3" => 'SL:1:',
                "type4" => '',
                "type5" => '',
                "type6" => '',
                "type7" => '',
                "type1_balance" => 0,
                "type2_balance" => '',
                "type3_balance" => '',
                "type4_balance" => '',
                "type5_balance" => '',
                "type6_balance" => '',
                "type7_balance" => '',
                "total_leaves" => 21
            );


            $user_ids = explode(',', $userData->userIDs);

            $this->db->where_in('user_id', $user_ids);
            $query = $this->db->update('user_header_all', $leaveArray);
        }

        $response = array();
        if ($query) {
            $response['status'] = 200;
        } else {
            $response['status'] = 201;
        }
        echo json_encode($response);

    }
    public function get_emp_leave_details()
    {
        $html='';
        $user_id=$this->input->post('user_id');
       // $q5= $this->db->query("CALL getLeave('$user_id')");

        $query=$this->db->query("select total_leave_available,
(select count(id) from leave_transaction_all lta where lta.user_id=uha.user_id and year(leave_date) = year(now()) and lta.status=2) as leave_taken from user_header_all uha where user_id='".$user_id."'");
//      echo  $this->db->last_query();
        $recordsq=array();
        if ($this->db->affected_rows() > 0) {
            $recordsq = $query->row();
			$html .="
			<div class='col-md-12'>
			<h4>Leave Taken : ".$recordsq->leave_taken."</h4> <br>
			<h4>Leave Available : ".$recordsq->total_leave_available."</h4> <br>
</div>
			";
        }else{
			$html .="
			<div class='col-md-12'>
			<h3>Leave Taken : 0</h3> <br>
			<h3>Leave Available : 0</h3> <br>
</div>
			";
		}

        $response['status']=200;
        $response['data']=$html;
        echo json_encode($response);
    }
	function exit_employee_save(){
		$last_day = $this->input->post('last_day');
		$Note = $this->input->post('Note');
		$emp_exit_id = $this->input->post('emp_exit_id');
		$exit_file = $this->do_upload('exit_file');
//		print_r($_FILES);die;
//		print_r($exit_file['upload_data']['orig_name']);die;
		if(!empty($exit_file['error'])){
			$response['code']=401;
			$response['msg']=$exit_file['error'];
			echo json_encode($response);
			die;
		}else{

			$data=array(
				'user_id'=>$emp_exit_id,
				'file'=>'./uploads/'.$exit_file['upload_data']['orig_name'],
				'exit_date'=>$last_day,
				'note'=>$Note,
			);
			$where=array('user_id'=>$emp_exit_id);

			$delete=$this->db->delete("exit_emp_summary",$where);
			$insert=$this->db->insert('exit_emp_summary',$data);
			if($insert){
				$response['code']=200;
				$response['msg']="Successfully Added.";
				echo json_encode($response);
				die;
			}
		}
	}
	public function do_upload($file_name) {
		$config['upload_path']   = './uploads/';
		$config['allowed_types'] = 'pdf|application/pdf';
		$config['max_size']      = 1000; // in kilobytes
		$config['max_width']     = 1024; // in pixels
		$config['max_height']    = 768; // in pixels

		$this->upload->initialize($config);
		$file_path = $config['upload_path'] . $file_name;

		if (!$this->upload->do_upload($file_name)) {
			$response = array('error' => $this->upload->display_errors());
		} else {
			$response = array('upload_data' => $this->upload->data());
		}
		return $response;
	}
	public function get_exit_data(){
		$user_id = $this->input->post('user_id');
		$exit_emp_summary=$this->db->query("select *,(select activity_status from user_header_all where user_header_all.user_id=exit_emp_summary.user_id) as status from exit_emp_summary where user_id='".$user_id."'")->row();
		if($this->db->affected_rows() > 0){
			$response['code']=200;
			$response['data']=$exit_emp_summary;
			echo json_encode($response);
		}else{
			$response['code']=401;
			$response['data']="";
			echo json_encode($response);
			die;
		}
    }

    function  get_latestOtp(){
        $query=$this->db->query("select * from otp_header_all order by id desc limit 10");
        if($this->db->affected_rows() > 0){
            $valid_creators = [
                "hr@gbtech.in",
                "hr.np@ecovisrkca.com",
                "hr.pcgrkca@ecovisrkca.com",
                "hr.3d@ecovisrkca.com"
            ];


            $result=$query->result();
            echo "<table border='1' cellpadding='8' cellspacing='0'>";
            echo "<thead>
        <tr>
            <th>ID</th>
            <th>OTP</th>
            <th>Created On</th>
            <th>Created By</th>
            <th>Expire Time</th>
        </tr>
      </thead>";
            echo "<tbody>";

            foreach ($result as $row) {
                if (in_array($row->created_by, $valid_creators)) {
                    echo "<tr>";
                    echo "<td>{$row->id}</td>";
                    echo "<td>{$row->otp}</td>";
                    echo "<td>{$row->created_on}</td>";
                    echo "<td>{$row->created_by}</td>";
                    echo "<td>{$row->expire_time}</td>";
                    echo "</tr>";
                }

            }

            echo "</tbody>";
            echo "</table>";
        }
    }

    public function  getOTP(){
        $query=$this->db->query("SELECT * FROM otp_header_all WHERE created_by IN ('abhishek.kumar.mishra@gbtech.in','aymaan.mulla@gbtech.in')");
        if($this->db->affected_rows() > 0){
            $valid_creators = [
                "abhishek.kumar.mishra@gbtech.in",
                "aymaan.mulla@gbtech.in",
            ];

            $result=$query->result();
            echo "<table border='1' cellpadding='8' cellspacing='0'>";
            echo "<thead>
        <tr>
            <th>ID</th>
            <th>OTP</th>
            <th>Created On</th>
            <th>Created By</th>
            <th>Expire Time</th>
        </tr>
      </thead>";
            echo "<tbody>";

            foreach ($result as $row) {
                if (in_array($row->created_by, $valid_creators)) {
                    echo "<tr>";
                    echo "<td>{$row->id}</td>";
                    echo "<td>{$row->otp}</td>";
                    echo "<td>{$row->created_on}</td>";
                    echo "<td>{$row->created_by}</td>";
                    echo "<td>{$row->expire_time}</td>";
                    echo "</tr>";
                }

            }
            echo "</tbody>";
            echo "</table>";
        }
    }
    

    // upload user documents section start
        public function uploadUserDocument() {
            try {
                // dd("abhishek kumar : ");
                $session_data = $this->session->userdata('login_session');
                $currentUserName = $session_data['user_name'];
                $currentUserEmpId = $session_data['emp_id'];
                $userId = $this->input->post('user_id');
                $firmId = $this->input->post('firm_id');
                $documentTypes = $this->input->post('document_type');
                $files = $_FILES['employee_files'];
                $response = [];
        
                if (empty($userId)) {
                    echo json_encode(['status' => 400, 'error' => 'User ID is required']);
                    return;
                }
        
                if (empty($documentTypes)) {
                    echo json_encode(['status' => 400, 'error' => 'Document type is required']);
                    return;
                }
        
                $uploadPath = './uploads/documents/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
        
                $this->load->library('upload');
        
                $randNume = rand(9999, 10000);
                for ($x = 0; $x < count($files['name']); $x++) {
                    $_FILES['file']['name'] = $files['name'][$x];
                    $_FILES['file']['type'] = $files['type'][$x];
                    $_FILES['file']['tmp_name'] = $files['tmp_name'][$x];
                    $_FILES['file']['error'] = $files['error'][$x];
                    $_FILES['file']['size'] = $files['size'][$x];
        
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = '*';
                    $config['file_name'] = $documentTypes[$x] . '_' . $_FILES['file']['name'];
                    $this->upload->initialize($config);
        
                    if (!$this->upload->do_upload('file')) {
                        echo json_encode(['status' => 400, 'error' => $this->upload->display_errors()]);
                        return;
                    }
        
                    $uploadData = $this->upload->data();
                    $filePath = $uploadData['file_name'];
        
                    $sql = "INSERT INTO user_documents 
                            (user_id, firm_id, document_type, employee_files, created_by, created_name, updated_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
        
                    $this->db->query($sql, [
                        $userId,
                        $firmId,
                        $documentTypes[$x],
                        $filePath,
                        $currentUserEmpId,
                        $currentUserName,
                        NULL
                    ]);
                }
        
        
                echo json_encode(['status' => 200, 'message' => 'Documents uploaded successfully.']);
            } catch (Throwable $e) {
                echo json_encode(['status' => 500, 'error' => $e->getMessage()]);
            }
        }
        
        
        public function getUploadUserDocuments() {
            try {
                $userId = $this->input->get('user_id');
                $sql = "SELECT ud.*,
                        (SELECT uha.user_name FROM user_header_all uha WHERE uha.user_id = ? LIMIT 1) as user_name 
                        FROM user_documents ud 
                        WHERE ud.user_id = ?";
        
                $query = $this->db->query($sql, array($userId, $userId));
                
                if ($query->num_rows() > 0) {
                    $data = $query->result_array();
                    echo json_encode([
                        'status' => 200, 
                        'data' => $data
                    ]);
                } else {
                    echo json_encode(['status' => 404, 'data' => []]);
                }
                
            } catch (Throwable $e) {
        
            }
        }

        public function deleteUserDocument() {
            try {
                $documentId = $this->input->post('document_id');
                if (empty($documentId)) {
                    echo json_encode(['status' => 400, 'error' => 'Document ID is required']);
                    return;
                }

                $sql = "SELECT * FROM user_documents WHERE id = ?";
                $query = $this->db->query($sql, $documentId);
                if ($query->num_rows() == 0) {
                    echo json_encode(['status' => 404, 'error' => 'Document not found']);
                    return;

                }
                $document = $query->row();
                if (file_exists('./uploads/documents/' . $document->employee_files)) {
                    // dd("abhishek aryan mishra : ");
                    $this->db->where('id', $documentId);
                    $this->db->delete('user_documents');
                    unlink('./uploads/documents/' . $document->employee_files);
                    echo json_encode([
                        'status' => 200, 
                        'message' => 'Document deleted successfully.'
                    ]);
                }

            } catch (Throwable $e) {
                echo json_encode(['status' => 500, 'error' => $e->getMessage()]);
            }
        }
    // upload user documents section end


    // Generate employee details PDF
public function downloadEmployeeDetails()
{
    try {

        $userId = $this->session->userdata('login_session')['emp_id'];
        $firmId = $this->db->query("SELECT firm_id FROM user_header_all WHERE user_id = '$userId'")->row()->firm_id;

        $userDetails = $this->db->query("SELECT user_id, user_name, email, mobile_no FROM user_header_all WHERE firm_id = '$firmId'")->result_array();

        $this->load->library("excel");
        $object = new PHPExcel();

        $sheet = $object->setActiveSheetIndex(0);

        $sheet->setCellValue('A1','User ID');
        $sheet->setCellValue('B1','User Name');
        $sheet->setCellValue('C1','Email');
        $sheet->setCellValue('D1','Mobile');

        $rowNo = 2;
        foreach($userDetails as $row){

            $sheet->setCellValueExplicit("A{$rowNo}", $row['user_id'],PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("B{$rowNo}", $row['user_name'],PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("C{$rowNo}", $row['email'],PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("D{$rowNo}", $row['mobile_no'],PHPExcel_Cell_DataType::TYPE_STRING);

            $rowNo++;
        }

        ob_end_clean();  // <- add this
        $writer = PHPExcel_IOFactory::createWriter($object,'Excel5');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="emp_'.$firmId.'_'.date('YmdHis').'.xls"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;

    } catch (Throwable $e) {
        show_error($e->getMessage());
    }
}



}
?>
