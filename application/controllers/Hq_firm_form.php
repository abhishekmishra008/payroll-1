<?php

class Hq_firm_form extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('firm_model');
        $this->load->model('Firm_model');
        $this->load->model('email_sending_model');
        $this->load->model('customer_model');

        $this->load->model('Nas_modal');
        $this->load->model('DatatableModel');
        $this->load->model('HrBranchData_model');
    }

    public function index() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
            $user_id = $result1['user_id'];
        }
        $access_token = $this->Nas_modal->get_access_code($user_id);
        if ($access_token != false) {
            $data['access_token'] = $access_token;
        } else {
            $data['access_token'] = "";
        }
        $session_data = $this->session->userdata('login_session');
        $email_id = $session_data['user_id'];
        $data['email_id'] = $email_id;
        $parent_folder_id = $this->Nas_modal->get_parent_folder_id($firm_id);
        if ($parent_folder_id != false) {
            $data['parent_folder_id'] = substr($parent_folder_id, 14);
        } else {
            $data['parent_folder_id'] = "";
        }

        $proxy_url = $this->Nas_modal->get_proxy_url($user_id);
        if ($proxy_url != false) {
            $data['proxy_url'] = $proxy_url;
        } else {
            $data['proxy_url'] = "";
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
        $data['prev_title'] = "Add Branch";
        $data['page_title'] = "Add Branch";
        //var_dump($data);
        $this->load->view('human_resource/firm_form', $data);
    }

    public function get_firm_nas_folder() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
            $user_id = $result1['user_id'];
        }

        $access_token = $this->Nas_modal->get_access_code($user_id);
        if ($access_token != false) {
            $data['access_token'] = $access_token;
        } else {
            $data['access_token'] = "";
        }

        $parent_folder_id = $this->Nas_modal->get_parent_folder_id($firm_id);
        if ($parent_folder_id != false) {
            $data['parent_folder_id'] = substr($parent_folder_id, 14);
        } else {
            $data['parent_folder_id'] = "";
        }

        $proxy_url = $this->Nas_modal->get_proxy_url($user_id);
        if ($proxy_url != false) {
            $data['proxy_url'] = $proxy_url;
        } else {
            $data['proxy_url'] = "";
        }
    }

    public function insert_client_firm() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }

        $firm_id = $this->generate_firm_id();
        $ddl_firm_name_fetch = $this->input->post('ddl_firm_name_fetch');
        $firm_name = $this->input->post('firm_name');
        $folder_transaction_id = $this->input->post('file_transaction_id');
        $user_id = $this->input->post('user_id');
        $firm_activity_status = $this->input->post('firm_activity_status');
        $firm_address = $this->input->post('firm_address');
        $firm_email_id = $this->input->post('firm_email_id');
        $firm_type = $this->input->post('firm_type');
        $boss_id = $this->generate_boss_id();
        $boss_name = $this->input->post('boss_name');
        $boss_mobile_no = $this->input->post('boss_mobile_no');
        $boss_email_id = $this->input->post('boss_email_id');
        $firm_no_of_employee = $this->input->post('firm_no_of_employee');
        //$leave_approve_permitted = $this->input->post('leave_approve_permitted');
        $req_leave = $this->input->post('req_leave');
        $country = $this->input->post('country');
        $state = $this->input->post('state');
        $city = $this->input->post('city');
        $is_virtual = $this->input->post('firm_virtual_status'); // specify that firm is virtual or not
//        $is_emp = $this->input->post('is_emp');
        $joining_date = $this->input->post('joining_date');
        $designation = 'CA';
        $designation_name = 'Client Admin';
        $yearly_leave = $this->input->post('yearly_leave');
        $monthly_leaves = $this->input->post('monthly_leaves');
        $leave_type_year = $this->input->post('leave_type_year');
        $leave_apply_permission = $this->input->post('leave_apply_permission');
        $leave_cf = $this->input->post('leave_cf');
        $holiday_status = $this->input->post('holiday_status');
        $probation_period = $this->input->post('probation_period');
        $prob_date_first = $this->input->post('prob_date_first');
        $prob_date_second = $this->input->post('prob_date_second');
        $training_period = $this->input->post('training_period');
        $training_period_first = $this->input->post('training_period_first');
        $training_period_second = $this->input->post('training_period_second');
        $leave_type_permission = $this->input->post('leave_type_permission');
        $date = date('y-m-d h:i:s');
        $user_type = '3';
        $reporting_to = $this->input->post('reporting_to');
        $accural_month = $this->input->post('accural_month');

        $longitude_value = $this->input->post('longitude_value');
        $lattitude_value = $this->input->post('lattitude_value');
//
        $hq_user_id_query = $this->db->query("SELECT user_id FROM `user_header_all` WHERE `linked_with_boss_id`='$reporting_to'");
        if ($hq_user_id_query->num_rows() > 0) {
            $record_hq_user_id_query = $hq_user_id_query->row();
            $hq_user_id = $record_hq_user_id_query->user_id;
        } else {
            $record_hq_user_id_query = '';
            $hq_user_id = '';
        }


        $leave_type1 = $this->input->post('leave_type1');
        $numdays1 = $this->input->post('numofdays1');
        $CF1 = $this->input->post('CF1');
//        $request_before1 = $this->input->post('request_before1');
//        $approve_before1 = $this->input->post('approve_before1');
        $leave_type2 = $this->input->post('leave_type2');
        $numdays2 = $this->input->post('numofdays2');
        $CF2 = $this->input->post('CF2');
//        $request_before2 = $this->input->post('request_before2');
//        $approve_before2 = $this->input->post('approve_before2');
        $leave_type3 = $this->input->post('leave_type3');
        $numdays3 = $this->input->post('numofdays3');
        $CF3 = $this->input->post('CF3');
//        $request_before3 = $this->input->post('request_before3');
//        $approve_before3 = $this->input->post('approve_before3');
        $leave_type4 = $this->input->post('leave_type4');
        $numdays4 = $this->input->post('numofdays4');
        $CF4 = $this->input->post('CF4');
//        $request_before4 = $this->input->post('request_before4');
//        $approve_before4 = $this->input->post('approve_before4');
        $leave_type5 = $this->input->post('leave_type5');
        $numdays5 = $this->input->post('numofdays5');
        $CF5 = $this->input->post('CF5');
//        $request_before5 = $this->input->post('request_before5');
//        $approve_before5 = $this->input->post('approve_before5');
        $leave_type6 = $this->input->post('leave_type6');
        $numdays6 = $this->input->post('numofdays6');
        $CF6 = $this->input->post('CF6');
//        $request_before6 = $this->input->post('request_before6');
//        $approve_before6 = $this->input->post('approve_before6');
        $leave_type71 = $this->input->post('leave_type7');
        $numdays7 = $this->input->post('numofdays7');
        $CF7 = $this->input->post('CF7');
//        $request_before7 = $this->input->post('request_before7');
//        $approve_before7 = $this->input->post('approve_before7');

        $rand_no_emp = (rand(10, 1000));

        if (empty($numdays1)) {
            $numdays1 = 0;
        }if (empty($numdays2)) {
            $numdays2 = 0;
        }if (empty($numdays3)) {
            $numdays3 = 0;
        }if (empty($numdays4)) {
            $numdays4 = 0;
        }if (empty($numdays5)) {
            $numdays5 = 0;
        }if (empty($numdays6)) {
            $numdays6 = 0;
        }if (empty($numdays7)) {
            $numdays7 = 0;
        }
        $total_leaves = $numdays1 + $numdays2 + $numdays3 + $numdays4 + $numdays5 + $numdays6 + $numdays7;

        
        $email_check = $this->check_email_avalibility1($boss_email_id);
        $validte_firm_email = $this->firm_model->validate_firm_email($firm_email_id);
        $length_address = strlen($firm_address);
        /*if (empty(trim($ddl_firm_name_fetch))) {
            $response['id'] = 'ddl_firm_name_fetch';
            $response['error'] = 'Select HR Admin';
        } else */
        if (empty(trim($firm_name))) {
            $response['id'] = 'firm_name';
            $response['error'] = 'Enter Office Name';
        } else if (empty($firm_email_id)) {
            $response['id'] = 'firm_email_id';
            $response['error'] = 'Enter Valid Email Id';
        } else if (!filter_var($firm_email_id, FILTER_VALIDATE_EMAIL)) {
            $response['id'] = 'firm_email_id';
            $response['error'] = "Invalid Email Format";
        } elseif ($validte_firm_email == true) {
            $response['id'] = 'firm_email_id';
            $response['error'] = 'This Email Id Already Exits In The System';
        } else if (empty(trim($firm_address))) {
            $response['id'] = 'firm_address';
            $response['error'] = 'Enter Office Address';
        } else if ($length_address < 5) {
            $response['id'] = 'firm_address';
            $response['error'] = 'Entered Address Must Be Greater Than 5 Characters';
        } else if (empty(trim($boss_name))) {
            $response['id'] = 'boss_name';
            $response['error'] = 'Enter Office Head Name';
        } else if (empty($boss_mobile_no)) {
            $response['id'] = "boss_mobile_no";
            $response['error'] = 'Enter Office Head Mobile No';
        } elseif (!preg_match("/^\d{10}$/", $boss_mobile_no)) { // phone number is valid
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Ten Digit Mobile Number';
        } elseif (!preg_match("/^[6-9][0-9]{9}$/", $boss_mobile_no)) {
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Proper Digit Mobile Number';
        } else if (empty($boss_email_id)) {
            $response['id'] = "boss_email_id";
            $response['error'] = 'Enter Office Head Email Id';
        } else if (!filter_var($boss_email_id, FILTER_VALIDATE_EMAIL)) {
            $response['id'] = 'boss_email_id';
            $response['error'] = "Invalid Email Format";
        }elseif ($email_check==true) {
            $response['id'] = 'boss_email_id';
            $response['error'] = "This Email Id Already Exits In The System";
        } else if ($firm_no_of_employee == "") {
            $response['id'] = "firm_no_of_employee";
            $response['error'] = 'Enter No of Employee In Office';
        } else if (empty(trim($country))) {
            $response['id'] = "country";
            $response['error'] = 'Enter Country Name';
        } elseif (!preg_match("/^[a-zA-Z,. ]*\z/", $country)) {
            $response['id'] = 'country';
            $response['error'] = 'Only Character Is Allowed';
        } else if (empty(trim($state))) {
            $response['id'] = 'state';
            $response['error'] = "Enter State Name";
        } elseif (!preg_match("/^[a-zA-Z ]*\z/", $state)) {
            $response['id'] = 'state';
            $response['error'] = 'Only Character Is Allowed';
        } else if (empty(trim($city))) {
            $response['id'] = "city";
            $response['error'] = "Enter City Name";
        } elseif (!preg_match("/^[a-zA-Z,. ]*\z/", $city)) {
            $response['id'] = 'city';
            $response['error'] = 'Only Character Is Allowed';
        } elseif (empty(trim($longitude_value))) {
            $response['id'] = 'longitude_value';
            $response['error'] = 'Enter Longitude';
        } elseif (!preg_match("/^[0-9]+(\.[0-9]*)?$/", $longitude_value)) {
            $response['id'] = 'longitude_value';
            $response['error'] = 'Enter Proper Longitude';
        } elseif (empty(trim($lattitude_value))) {
            $response['id'] = 'lattitude_value';
            $response['error'] = 'Enter Proper Lattitude';
        } elseif (empty(trim($joining_date))) {
            $response['id'] = 'joining_date';
            $response['error'] = 'Enter Joining Date';
        } elseif (empty($yearly_leave)) {
            $response['id'] = 'yearly_leave';
            $response['error'] = 'Enter Total Numbers Of Leave Type';
        } elseif (empty($leave_type_year)) {
            $response['id'] = 'leave_type_year';
            $response['error'] = 'Select Year Type';
        } elseif (empty($leave_apply_permission)) {
            $response['id'] = 'leave_apply_permission';
            $response['error'] = 'Select Leave Apply Permission';
        } elseif (empty($leave_cf)) {
            $response['id'] = 'leave_cf';
            $response['error'] = 'Select Leave Carry Forward Period';
        }
elseif ($probation_period == '0' && $prob_date_first == '') {
            $response['id'] = 'prob_date_first';
            $response['error'] = 'Select Date Type';
            echo json_encode($response);
            exit;
        } elseif ($probation_period == '0' && $prob_date_second == '') {
            $response['id'] = 'prob_date_second';
            $response['error'] = 'Select Date Type';
            echo json_encode($response);
            exit;
        } elseif ($training_period == '0' && $training_period_first == '') {
            $response['id'] = 'training_period_first';
            $response['error'] = 'Select Date Type';
            echo json_encode($response);
            exit;
        } elseif ($training_period == '0' && $training_period_second == '') {
            $response['id'] = 'training_period_second';
            $response['error'] = 'Select Date Type';
            echo json_encode($response);
            exit;
        }		
		
		else if ($total_leaves != $yearly_leave) {
            $response['id'] = 'numofdays7';
            $response['error'] = 'Sum Of Leave Should Be Equal To Yearly Leaves';
        } elseif (empty(trim($numdays3))) {
            $response['id'] = 'numofdays3';
            $response['error'] = 'Please Enter And Filled This Value From Total Leaves';
        } elseif (empty($monthly_leaves)) {
            $response['id'] = 'monthly_leaves';
            $response['error'] = 'Enter Monthly Leave Type';
        } elseif ($probation_period == '0' && $prob_date_first == '') {
            $response['id'] = 'prob_date_first';
            $response['error'] = 'Select Date Type';
        } elseif ($probation_period == '0' && $prob_date_second == '') {
            $response['id'] = 'prob_date_second';
            $response['error'] = 'Select Date Type';
        } elseif ($training_period == '0' && $training_period_first == '') {
            $response['id'] = 'training_period_first';
            $response['error'] = 'Select Date Type';
        } elseif ($training_period == '0' && $training_period_second == '') {
            $response['id'] = 'training_period_second';
            $response['error'] = 'Select Date Type';
        } elseif (empty(trim($accural_month))) {
            $response['id'] = 'accural_month';
            $response['error'] = 'Select Month';
        } elseif ($CF2 == '') {
            $response['id'] = 'CF2';
            $response['error'] = 'Please Select';
        } elseif ($CF3 == '') {
            $response['id'] = 'CF3';
            $response['error'] = 'Please Select';
        } elseif ($leave_type4 != '' && empty(trim($numdays4))) {
            $response['id'] = 'numofdays4';
            $response['error'] = 'Please Enter Day';
        } elseif ($leave_type4 != '' && $CF4 == '') {
            $response['id'] = 'CF4';
            $response['error'] = 'Please Select';
        } elseif ($leave_type5 != '' && empty(trim($numdays5))) {
            $response['id'] = 'numofdays5';
            $response['error'] = 'Please Enter Day';
        } elseif ($leave_type5 != '' && $CF5 == '') {
            $response['id'] = 'CF5';
            $response['error'] = 'Please Select';
        } elseif ($leave_type6 != '' && empty(trim($numdays6))) {
            $response['id'] = 'numofdays6';
            $response['error'] = 'Please Enter Day';
        } elseif ($leave_type6 != '' && $CF6 == '') {
            $response['id'] = 'CF6';
            $response['error'] = 'Please Select';
        } elseif ($leave_type71 != '' && empty(trim($numdays7))) {
            $response['id'] = 'numofdays7';
            $response['error'] = 'Please Enter Day';
        } elseif ($leave_type71 != '' && $CF7 == '') {
            $response['id'] = 'CF7';
            $response['error'] = 'Please Select';
        } elseif (!preg_match("/^[0-9]+(\.[0-9]*)?$/", $lattitude_value)) {
            $response['id'] = 'lattitude_value';
            $response['error'] = 'Enter Proper Value';
        } else {

            if ($firm_activity_status == 'A') {
                $user_activity_status = '1';
            } else {
                $user_activity_status = '0';
            }
$user_activity_status = '1';
            if ($firm_activity_status == 'A') {
                $hr_activity_status = 'A';
            } else {
                $hr_activity_status = 'D';
            }
 $hr_activity_status = 'A';


            $holiday_count = $this->input->post('holiday_count');

            if ($holiday_count !== "") {
                $adata = array();

                $a = "";
                $view_data = array();
                for ($i = 1; $i <= ($holiday_count); $i++) {
                    $ddl_week_day = $this->input->post('ddl_week_day' . $i);
                    $ddl_day_type = $this->input->post('ddl_day_type' . $i);

                    if (empty($ddl_week_day)) {
                        $response['id'] = 'ddl_week_day' . $i;
                        $response['error'] = 'Please select Week Day Name';
                        echo json_encode($response);
                        exit;
                    } elseif (empty($ddl_day_type)) {
                        $response['id'] = 'ddl_day_type' . $i;
                        $response['error'] = 'Please Select Day';
                        echo json_encode($response);
                        exit;
                    }


                    if ($ddl_day_type != 'all') {
                        $day = $this->input->post('day' . $i);
                        if ($day == '0') {
                            $response['id'] = 'day' . $i;
                            $response['error'] = 'Please Select ';
                            echo json_encode($response);
                            exit;
                        } else {
                            $str = implode(":", $day);
                            $ddl_week_day = $ddl_week_day;
                        }
                    } else {
                        $str = "all";
                    }
                    $a .= $ddl_week_day . '#' . $str . ',';
                }
                $latest_srting = rtrim($a, ",");
            }





            $this->db->select('*');
            $this->db->from('partner_header_all');
            $this->db->where("boss_id='$reporting_to'");
//            echo $this->db->last_query();
            $result3 = $this->db->get();
            if ($result3->num_rows() > 0) {
                $result_emp = $result3->row();
                $get_boss_no_of_employee = $result_emp->firm_no_of_employee; // No of employees  can add in hq
                $get_sum_no_of_customer = $result_emp->firm_no_of_customers; // No of customer can add in hq
                $get_sum_no_of_offices = $result_emp->firm_no_of_permitted_offices; // No of permitted_offices can add in hq
            } else {
                $get_boss_no_of_employee = 0; // No of employees hq can add
                $get_sum_no_of_customer = 0;
            }

            $query_boss = $this->db->query("select boss_id from partner_header_all where firm_id='$ddl_firm_name_fetch'");
            $result = $query_boss->row();
            $boss_id11 = $result->boss_id;
//            echo $this->db->last_query();
//            exit;
            // $get_boss_no_of_employee_new = ($get_boss_no_of_employee * 1) - ($get_total_no_of_employee * 1);


            $validte_firm_email = $this->firm_model->validate_firm_email($firm_email_id);
            if ($validte_firm_email == true) {
                $response['id'] = 'firm_email_id';
                $response['error'] = 'This email id already exits in the system';
            } else {
                $session_user_id = $user_id;
                $user_id = $this->generate_user_id();
                $data = array(
                    'firm_id' => $firm_id,
                    'firm_name' => $firm_name,
                    'firm_activity_status' => $hr_activity_status,
//                      'firm_activity_status' => $firm_activity_status,
                    'firm_address' => $firm_address,
                    'firm_email_id' => $firm_email_id,
                    'firm_type' => $firm_type,
                    'boss_id' => $boss_id,
                    'boss_name' => $boss_name,
                    'boss_mobile_no' => $boss_mobile_no,
                    'boss_email_id' => $boss_email_id,
                    'reporting_to' => $boss_id11,
                    'firm_no_of_employee' => $firm_no_of_employee,
//                        'is_virtual' => $is_virtual,
                    'created_by' => $session_user_id,
                    'created_on' => $date,
                    'created_from' => $session_user_id,
                    'leave_type_fixed' => $leave_type_permission,
                    'accural_month' => $accural_month
//                        'week_holiday' => $latest_srting$accural_month = $this->input->post('accural_month');
//                        'folder_id' => $folder_transaction_id
                );
//                var_dump($data);
//                $type1 = $leave_type1 . ':' . $numdays1 . ':' . $CF1;

                if (empty($leave_type1 && $numdays1)) {
                    $type1 = '';
                } else {
                    $type1 = $leave_type1 . ':' . $numdays1 . ':' . $CF1;
                }

                if (empty($leave_type2 && $numdays2)) {
                    $type2 = '';
                } else {
                    $type2 = $leave_type2 . ':' . $numdays2 . ':' . $CF2;
                }
                if (empty($leave_type3 && $numdays3)) {
                    $type3 = '';
                } else {
                    $type3 = $leave_type3 . ':' . $numdays3 . ':' . $CF3;
                }
                if (empty($leave_type4 && $numdays4)) {
                    $type4 = '';
                } else {
                    $type4 = $leave_type4 . ':' . $numdays4 . ':' . $CF4;
                }
                if (empty($leave_type5 && $numdays5)) {
                    $type5 = '';
                } else {
                    $type5 = $leave_type5 . ':' . $numdays5 . ':' . $CF5;
                }
                if (empty($leave_type6 && $numdays6)) {
                    $type6 = '';
                } else {
                    $type6 = $leave_type6 . ':' . $numdays6 . ':' . $CF6;
                }
                if (empty($leave_type71 && $numdays7)) {
                    $type7 = '';
                } else {
                    $type7 = $leave_type71 . ':' . $numdays7 . ':' . $CF7;
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
                    'designation_id ' => $designation,
                    'boss_id' => $reporting_to
                );
//                var_dump($data_leave);
//                exit;

                $p_data_leave = $this->db->insert('leave_header_all', $data_leave);


                $data_designation = array(
                    'boss_id' => $boss_id,
                    'firm_id' => $firm_id,
                    'designation_id' => $designation,
                    'designation_name' => $designation_name,
                    'created_on' => $date,
                    'created_by' => $email_id,
                    'total_yearly_leaves' => $yearly_leave,
                    'total_monthly_leaves' => $monthly_leaves,
                    'holiday_consider_in_leave' => $holiday_status,
                    'carry_forward_period' => $leave_cf,
                    'request_leave_from' => $leave_apply_permission,
                    'year_type' => $leave_type_year
                );

//                    echo $this->db->last_query();
                $password = rand(100, 1000);

                //storing longitude and lattitude value
                $data_location = array('firm_id' => $firm_id,
                    'lattitude' => $lattitude_value,
                    'logitude' => $longitude_value,
                    'radius' => 200);

                $data_location_query = $this->db->insert("firm_location", $data_location);

                // sms function code
                $auth_key = "178904AVN94GK259e5e87b";
                $firm_no_of_permitted_offices = "";
                $no = $data['boss_mobile_no'];
                $trigger_by = "hq";
                //Insert client details into GST



                $get_user_id = $this->generate_user_id();
//                echo $get_user_id;
//                    $result = $this->firm_model->add_firm_modal($data, $user_type, $get_user_id, $star_rating, $task_approve, $task_sign, $user_activity_status, $email_id, $city, $state, $country, $is_emp, $password, $hq_user_id, $prob_date_first, $prob_date_second, $training_period_first, $training_period_second, $joining_date, $designation, $reporting_to, $create_task_assign_permit, $create_due_date_task, $create_service_permit, $generate_enq_permit, $employee_permission, $customer_permission, $template_permission, $knowledge_permission, $warning_configuration_permission);
                $result = $this->firm_model->add_firm_modal($data, $get_user_id, $boss_id, $user_type, $user_activity_status, $email_id, $password, $city, $state, $country, $hq_user_id, $prob_date_first, $prob_date_second, $training_period_first, $training_period_second, $joining_date, $designation, $reporting_to);
//                echo $this->db->last_query();
                if ($result == TRUE) {
                    $data_designation = $this->db->insert('designation_header_all', $data_designation);
//                        $this->db2 = $this->load->database('db2', TRUE);
//                        $qr_gst_insert = $this->db2->insert('customer_header_all', $data_gst);
//                        //                    email  function
//                        $email = $this->email_sending_model->sendEmail($user_type, $boss_name, $firm_name, $firm_no_of_permitted_offices, $firm_no_of_employee,  $firm_email_id, $password, $trigger_by);
//                        //                     sms function code
//                        $sms = $this->firm_model->sendSms($auth_key, $no, $user_type, $boss_name, $firm_name, $firm_no_of_permitted_offices, $firm_no_of_employee, $firm_email_id, $password, $trigger_by);

                    $response['id'] = $firm_id;
                    $response['status'] = true;
                } else if ($result == FALSE) {

                    $response['status'] = false;
                }
            }
        }
        echo json_encode($response);
    }
    
    function check_email_avalibility1($boss_email_id) {
        //check hereemail id is available in database or not
        
            $this->load->model("firm_model");
            if ($this->firm_model->is_email_available($boss_email_id)==true) {
                return true;
//                echo '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Email Is Already register</label>';
            } else {
                return false;
//                echo '<label class="text-success"><span class="glyphicon glyphicon-ok"></span>Valid Email Id</label>';
            }
        
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

    public function duedate_task_list($firm_id = '') {
        $firm_id = base64_decode($firm_id);
        if (empty($firm_id)) {
            $data['prev_title'] = "Due Date Task List";
            $data['page_title'] = "Due Date Task List";

            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
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


            $this->load->view('hq_admin/duedate_task_list', $data);
            return;
        }
        //$firm_id = $this->input->post('firm_id');
        //  echo $firm_id;
        $data['current_firm_id'] = $firm_id;
        $data['prev_title'] = "Due Date Task List";
        $data['page_title'] = "Due Date Task List";
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
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


        $this->load->helper('url');
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
        $result = $this->db->query("SELECT * FROM `partner_header_all` WHERE `firm_email_id`='$email_id'");
        if ($result->num_rows() > 0) {
            $record = $result->row();
            $reporting_to = $record->reporting_to;
            $query = $this->db->query("SELECT DISTINCT(`due_date_name`), `duration`, `instruction` , `is_repeat` FROM `due_date_header_all` WHERE  `boss_id`='$reporting_to'");
            $query2 = $this->db->query("SELECT DISTINCT(`task_name`) FROM `task_header_all` WHERE  `boss_id`='$reporting_to'");


            $array = array('boss_id' => $reporting_to, 'service_type_id' => '');
            $this->db->select(' DISTINCT(`service_name`) as service_name,service_id');
            $this->db->from('services_header_all');
            $this->db->where($array);
            $this->db->order_by("service_name", "ASC");

            $query4 = $this->db->get()->result();
            if ($query->num_rows() > 0 && $query2->num_rows() > 0 && $query4 != null) {
                $record = $query->result();
                $record2 = $query2->result();
                $data['result2'] = $record;
                $data['result'] = $record2;
                $data['service'] = $query4;
                $this->load->view('hq_admin/duedate_task_list', $data);
            } else if ($query->num_rows() > 0 && $query2->num_rows() == 0 && $query4 == null) {
                $record = $query->result();
                $data['result2'] = $record;
                $data['result'] = '';
                $data['service'] = '';
                $this->load->view('hq_admin/duedate_task_list', $data);
            } else if ($query->num_rows() == 0 && $query2->num_rows() > 0 && $query4 == null) {
                $record2 = $query2->result();
                $data['result'] = $record2;
                $data['result2'] = '';
                $data['service'] = '';
                $this->load->view('hq_admin/duedate_task_list', $data);
            } elseif ($query->num_rows() == 0 && $query2->num_rows() == 0 && $query4 != null) {
                $data['result2'] = '';
                $data['result'] = '';
                $data['service'] = $query4;
                $this->load->view('hq_admin/duedate_task_list', $data);
            }
        } else {
            $data['result'] = '';
            $data['result2'] = '';
            $this->load->view('hq_admin/duedate_task_list', $data);
        }
    }

    function generate_boss_id() {
        $boss_id = 'B_' . rand();
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where('boss_id', $boss_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return generate_boss_id();
        } else {
            return $boss_id;
        }
    }

    // dropdown list for firm form to get reporting to
    function get_reporting_to() {
        $response = array('code' => -1, 'status' => false, 'message' => '');
        $email_id = $this->input->post('user_id');
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where("firm_activity_status ='A' and firm_type='multiple' and firm_email_id='$email_id'");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $response['reporting_to'] = $result->boss_id;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            $this->load->helper('form');
            //            $this->load->view('hq_admin/firm_form', array('firm_email_id' => $data), true);
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    function generate_user_id() {

        $user_id = 'U_' . rand(100, 1000);
        $this->db->select('user_id');
        $this->db->where('user_id', $user_id);
        $this->db->get('user_header_all');
        if ($this->db->affected_rows() > 0) {
            return $this->generate_user_id();
        } else {
            //echo $user_id;
            return $user_id;
        }
    }

    function generate_firm_id() {
        $result = $this->db->query('SELECT firm_id FROM `partner_header_all` ORDER BY firm_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $firm_id = $data->firm_id;
            //generate user_id
            $firm_id = str_pad(++$firm_id, 5, '0', STR_PAD_LEFT);
            return $firm_id;
        } else {
            $firm_id = 'Firm_1001';
            return $firm_id;
        }
    }

    // function to show firm data
    function view_firm_data() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
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
        $data['prev_title'] = "View Office";
        $data['page_title'] = "View Office";
        $this->load->helper('url');
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
//        $result = $this->db->query("SELECT * FROM `partner_header_all` WHERE `boss_email_id`='$email_id'");
        $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");


        if ($result->num_rows() > 0) {
//        if ($this->db->affected_rows() > 0) {

            $record1 = $result->row();
            $boss_id = $record1->linked_with_boss_id;
            $user_type = $record1->user_type;
            $query = $this->db->query("SELECT * FROM `partner_header_all` WHERE `reporting_to`='$boss_id' and `firm_email_id`!='$email_id'");
//            echo $this->db->last_query();
            if ($query->num_rows() > 0) {
                $record1 = $query->row();
                $no_of_offices_permitted = $record1->firm_no_of_permitted_offices;
                $total_no_of_offices = $query->num_rows();
                $data['no_of_offices_permitted'] = $no_of_offices_permitted;
                $data['total_no_of_offices'] = $total_no_of_offices;
                $response['user_type'] = $user_type;
            }
            if ($query->num_rows() > 0) {
                $record = $query->result();
                $data['result'] = $record;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                $response['total_no_of_offices'] = $total_no_of_offices;
//                $data['no_of_offices_permitted'] = $no_of_offices_permitted;
                $data['user_type'] = $user_type;
                $this->load->view('human_resource/view_firm_data', $data);
            } else {
                $data['result'] = '';
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
//                $response['total_no_of_offices'] = $total_no_of_offices;
//                $data['no_of_offices_permitted'] = $no_of_offices_permitted;
                $this->load->view('human_resource/view_firm_data', $data);
            }
        } else {
            $data['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
//            var_dump($data);
            $this->load->view('human_resource/view_firm_data', $data);
        }
    }

    function view_firm_data_admin($firm_id = '') {

        $data['prev_title'] = " List Of Office";
        $data['page_title'] = "List Of Office";
        $this->load->helper('url');

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
        $result = $this->db->query("SELECT * FROM `partner_header_all`");

        if ($result->num_rows() > 0) {
            $record = $result->row();
            $firm_name = $record->firm_name;
            $data['firm_name'] = $firm_name;
            $query = $this->db->query("SELECT * FROM `partner_header_all` WHERE  `firm_email_id`!='$email_id'");
            if ($query->num_rows() > 0) {
                $record1 = $query->row();
                $total_no_of_offices = $query->num_rows();
            }

            if (!empty($firm_id)) {
                $query11 = $this->db->query("SELECT firm_id,reporting_to,firm_name FROM `partner_header_all` WHERE  `firm_id`='$firm_id'");
                $this->db->last_query();
                $result11 = $query11->row();
                $re_to = $result11->reporting_to;
                $firm_name1 = $result11->firm_name;
                $firm_id1 = $result11->firm_id;
                $result = $this->Firm_model->get_companies_hr($firm_id, $re_to);
            }
//            echo $this->db->last_query();
            if ($result == $query) {
                $data['record'] = "";
                $data['firm_name1'] = "";
                $data['firm_id1'] = "";
            } else {
                $data['record'] = $result;
                $data['firm_name1'] = $firm_name1;
                $data['firm_id1'] = $firm_id1;
            }

            if ($query->num_rows() > 0) {
                $record = $query->result();
//                $data['result'] = $record;
                $data['result'] = $result;
                $data['result1'] = $query;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
//                $response['total_no_of_offices'] = $total_no_of_offices;
//                 $data['no_of_offices_permitted'] = $no_of_offices_permitted;
                $this->load->view('admin/view_firm_data_admin', $data);
            } else {
//                $data['result'] = '';
                $data['result'] = $result;
                $data['result1'] = $query;
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
//                $response['total_no_of_offices'] = $total_no_of_offices;
//                 $data['no_of_offices_permitted'] = $no_of_offices_permitted;
                $this->load->view('admin/view_firm_data_admin', $data);
            }
        } else {
            $data['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
            $data['firm_name'] = '';
            $data['firm_name1'] = '';
            $data['firm_id1'] = '';
            $data['record'] = '';
//            var_dump($data);
            $this->load->view('admin/view_firm_data_admin', $data);
        }
    }

    public function get_firms() {

        $query_get_firm = $this->db->query("SELECT a.firm_id,a.firm_name FROM partner_header_all a, user_header_all b WHERE a.firm_id = b.firm_id AND b.user_type=2");

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
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function change_activity_status() {
        $firm_id = base64_decode($this->input->post('firm_id'));
        $status = $this->input->post('status');
        $result = $this->firm_model->hq_clients_activity_status($firm_id, $status);
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

    public function fetch_firm_data($firm_id = '') {

        $data['prev_title'] = "Branch Details";
        $data['page_title'] = "Branch Details";
        // echo $firm_id;
//        $data['email_id'] = $email_id;
        $query = $this->db->query("SELECT * from partner_header_all WHERE  firm_id = '$firm_id'");
//        echo $this->db->last_query();
        if ($query->num_rows() > 0) {
//            $query1 = $this->db->query("SELECT * from user_header_all WHERE firm_id ='$firm_id' AND `created_by`='$email_id'");
            $query1 = $this->db->query("SELECT * from user_header_all WHERE firm_id ='$firm_id'");
//                echo $this->db->last_query();
            if ($query1->num_rows() > 0) {
                $record = $query->row();
                $record2 = $query1->row();


                $query2 = $this->db->query("SELECT * from designation_header_all WHERE firm_id ='$firm_id' AND `designation_id`='CA'");

                if ($query2->num_rows() > 0) {
                    $record3 = $query2->row();
                }
                $quer_leave_data = $this->db->query("SELECT * from leave_header_all WHERE firm_id ='$firm_id' AND `designation_id`='CA'");
                if ($quer_leave_data->num_rows() > 0) {
                    $record4 = $quer_leave_data->row();
                }
                $data['result_firm_data'] = $record;
                $data['result_firm_data1'] = $record2;
                $data['result_desig_data'] = $record3;
                $data['result_leave_data'] = $record4;
            } else {
                $data['result_firm_data'] = '';
                $data['result_firm_data1'] = '';
                $data['result_desig_data'] = '';
                $data['result_leave_data'] = '';
            }
        } else {
            $result2 = $this->customer_model->get_boss_id();
            if ($result2 !== false) {
                $boss_id = $result2['reporting_to'];
            }
            $query_leave = $this->db->query("SELECT * from leave_header_all WHERE boss_id ='$boss_id' AND firm_id ='$firm_id' AND designation_id='CA'");
            echo $this->db->last_query();
            if ($query_leave->num_rows() > 0) {
                $record_leave = $query_leave->row();
                $data['result_leave_data'] = $record_leave;
            } else {
                $data['result_leave_data'] = '';
            }

            $query_desig = $this->db->query("SELECT * from designation_header_all WHERE firm_id ='$firm_id' AND designation_id='CA'");
            if ($query_desig->num_rows() > 0) {
                $record_desig = $query_desig->row();
                $data['result_desig_data'] = $record_desig;
            } else {
                $data['result_desig_data'] = '';
            }
        }
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id1 = $result1['firm_id'];
        }
        $query_jj = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id1'");
        if ($query_jj->num_rows() > 0) {

            $record = $query_jj->row();
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

//        $this->load->view('hq_admin/edit_firm_data', $data);
        $this->load->view('human_resource/edit_firm_data', $data);
    }

    public function fetch_firm_data_admin($firm_id = '') {

        $data['prev_title'] = "Edit Office";
        $data['page_title'] = "Edit Office";
        // echo $firm_id;
//        $data['email_id'] = $email_id;
        $query = $this->db->query("SELECT * from partner_header_all WHERE  firm_id = '$firm_id'");
//        echo $this->db->last_query();
        if ($query->num_rows() > 0) {
//            $query1 = $this->db->query("SELECT * from user_header_all WHERE firm_id ='$firm_id' AND `created_by`='$email_id'");
            $query1 = $this->db->query("SELECT * from user_header_all WHERE firm_id ='$firm_id'");
//                echo $this->db->last_query();
            if ($query1->num_rows() > 0) {
                $record = $query->row();
                $record2 = $query1->row();


                $query2 = $this->db->query("SELECT * from designation_header_all WHERE firm_id ='$firm_id' AND `designation_id`='CA'");

                if ($query2->num_rows() > 0) {
                    $record3 = $query2->row();
                }
                $quer_leave_data = $this->db->query("SELECT * from leave_header_all WHERE firm_id ='$firm_id' AND `designation_id`='CA'");
//                echo $this->db->last_query();
                if ($quer_leave_data->num_rows() > 0) {
                    $record4 = $quer_leave_data->row();
                }
                $data['result_firm_data'] = $record;
                $data['result_firm_data1'] = $record2;
                $data['result_desig_data'] = $record3;
                $data['result_leave_data'] = $record4;
            } else {
                $data['result_firm_data'] = '';
                $data['result_firm_data1'] = '';
                $data['result_desig_data'] = '';
                $data['result_leave_data'] = '';
            }
        } else {
            $result2 = $this->customer_model->get_boss_id();
            if ($result2 !== false) {
                $boss_id = $result2['reporting_to'];
            }
            $query_leave = $this->db->query("SELECT * from leave_header_all WHERE boss_id ='$boss_id' AND firm_id ='$firm_id' AND designation_id='CA'");
            echo $this->db->last_query();
            if ($query_leave->num_rows() > 0) {
                $record_leave = $query_leave->row();
                $data['result_leave_data'] = $record_leave;
            } else {
                $data['result_leave_data'] = '';
            }

            $query_desig = $this->db->query("SELECT * from designation_header_all WHERE firm_id ='$firm_id' AND designation_id='CA'");
            if ($query_desig->num_rows() > 0) {
                $record_desig = $query_desig->row();
                $data['result_desig_data'] = $record_desig;
            } else {
                $data['result_desig_data'] = '';
            }
        }

        $result_location = $this->db->query("select * from firm_location where firm_id='$firm_id'");
        if ($result_location->num_rows() > 0) {
            $record_location = $result_location->row();
            $data['result_location_data'] = $record_location;
        } else {
            $data['result_location_data'] = '';
        }

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id1 = $result1['firm_id'];
        }
        $query_jj = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id1'");
        if ($query_jj->num_rows() > 0) {

            $record = $query_jj->row();
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

//        $this->load->view('hq_admin/edit_firm_data', $data);
        $this->load->view('admin/edit_firm_data_branch', $data);
    }

    public function update_firm_data() {
        $firm_id = $this->input->post('firm_id');
        $firm_name = $this->input->post('firm_name');
        $firm_address = $this->input->post('firm_address');
        $firm_email_id = $this->input->post('firm_email_id');
        $boss_name = $this->input->post('boss_name');
        $boss_email_id = $this->input->post('boss_email_id');
        $boss_mobile_no = $this->input->post('boss_mobile_no');
        $firm_no_of_employee = $this->input->post('firm_no_of_employee');
        $country = $this->input->post('country');
        $state = $this->input->post('state');
        $city = $this->input->post('city');
        $is_emp = $this->input->post('is_emp');
        $longitude_value = $this->input->post('longitude_value');
        $lattitude_value = $this->input->post('lattitude_value');
        $date = date('y-m-d h:i:s');
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
        $data['email_id'] = $email_id;
        $due_date_creation_permitted = $this->input->post('due_date_creation_permitted');
        $task_creation_permitted = $this->input->post('task_creation_permitted');
        $joining_date = $this->input->post('joining_date');
        $yearly_leave = $this->input->post('yearly_leave');
        $monthly_leaves = $this->input->post('monthly_leaves');
        $leave_type_year = $this->input->post('leave_type_year');
        $leave_apply_permission = $this->input->post('leave_apply_permission');
        $leave_cf = $this->input->post('leave_cf');
        $holiday_status = $this->input->post('holiday_status');
        $prob_period = $this->input->post('probation_period');
        $prob_date_first = $this->input->post('prob_date_first');
        $prob_date_second = $this->input->post('prob_date_second');
        $train_period = $this->input->post('training_period');
        $training_period_first = $this->input->post('training_period_first');
        $training_period_second = $this->input->post('training_period_second');
        $probation_period = $this->input->post('probation_period');
        $training_period = $this->input->post('training_period');
        $star_rating = $this->input->post('star_rating');
        $task_approve = $this->input->post('task_approve');
        $task_sign = $this->input->post('task_sign');
        $accural_month = $this->input->post('accural_month');


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

        $create_task_assign_permit = $this->input->post('create_task_assign_permit');
        $create_due_date_task = $this->input->post('create_duedate_permit'); // assignemnet permission
        $create_service_permit = $this->input->post('create_service_permit') . ',1,' . '1';
        $generate_enq_permit = $this->input->post('generate_enq_permit');
        $create_task_assign_permit = $task_creation_permitted . ',1,' . '1' . ':' . $create_task_assign_permit . ',1,' . '1';
        $create_due_date_task = $due_date_creation_permitted . ',1,' . '1' . ':' . $create_due_date_task . ',1,' . '1';
        $employee_permission = '1' . ',1,' . '1';
        $customer_permission = '1' . ',1,' . '1';
        $template_permission = '1' . ',1,' . '1';
        $knowledge_permission = '1' . ',1,' . '1';
        $warning_configuration_permission = '1' . ',1,' . '1';

        $leave_type1 = $this->input->post('leave_type1');
        $numdays1 = $this->input->post('numofdays1');
        $request_before1 = $this->input->post('request_before1');
        $approve_before1 = $this->input->post('approve_before1');
        $leave_type2 = $this->input->post('leave_type2');
        $numdays2 = $this->input->post('numofdays2');
        $request_before2 = $this->input->post('request_before2');
        $approve_before2 = $this->input->post('approve_before2');
        $leave_type3 = $this->input->post('leave_type3');
        $numdays3 = $this->input->post('numofdays3');
        $request_before3 = $this->input->post('request_before3');
        $approve_before3 = $this->input->post('approve_before3');
        $leave_type4 = $this->input->post('leave_type4');
        $numdays4 = $this->input->post('numofdays4');
        $request_before4 = $this->input->post('request_before4');
        $approve_before4 = $this->input->post('approve_before4');
        $leave_type5 = $this->input->post('leave_type5');
        $numdays5 = $this->input->post('numofdays5');
        $request_before5 = $this->input->post('request_before5');
        $approve_before5 = $this->input->post('approve_before5');
        $leave_type6 = $this->input->post('leave_type6');
        $numdays6 = $this->input->post('numofdays6');
        $request_before6 = $this->input->post('request_before6');
        $approve_before6 = $this->input->post('approve_before6');
        $leave_type7 = $this->input->post('leave_type7');
        $numdays7 = $this->input->post('numofdays7');
        $request_before7 = $this->input->post('request_before7');
        $approve_before7 = $this->input->post('approve_before7');
        $CF1 = $this->input->post('CF1');
        $CF2 = $this->input->post('CF2');
        $CF3 = $this->input->post('CF3');
        $CF4 = $this->input->post('CF4');
        $CF5 = $this->input->post('CF5');
        $CF6 = $this->input->post('CF6');
        $CF7 = $this->input->post('CF7');
        $leave_type_permission = $this->input->post('leave_type_permission');

        $holiday_count = $this->input->post('holiday_count');


        if ($holiday_count !== "") {
            $adata = array();

            $a = "";
            $view_data = array();
            for ($i = 1; $i <= ($holiday_count); $i++) {
                $ddl_week_day = $this->input->post('ddl_week_day' . $i);
                $ddl_day_type = $this->input->post('ddl_day_type' . $i);

                if (empty($ddl_week_day)) {
                    $response['id'] = 'ddl_week_day' . $i;
                    $response['error'] = 'Please select Week Day Name';
                    echo json_encode($response);
                    exit;
                } elseif (empty($ddl_day_type)) {
                    $response['id'] = 'ddl_day_type' . $i;
                    $response['error'] = 'Please Select Day';
                    echo json_encode($response);
                    exit;
                }


                if ($ddl_day_type != 'all') {
                    $day = $this->input->post('day' . $i);
                    if ($day == '0') {
                        $response['id'] = 'day' . $i;
                        $response['error'] = 'Please Select ';
                        echo json_encode($response);
                        exit;
                    } else {
                        $str = implode(":", $day);
                        $ddl_week_day = $ddl_week_day;
                    }
                } else {
                    $str = "all";
                }
                $a .= $ddl_week_day . '#' . $str . ',';
            }
            $latest_srting = rtrim($a, ",");
        }



        if (empty($numdays1)) {
            $numdays1 = 0;
        }if (empty($numdays2)) {
            $numdays2 = 0;
        }if (empty($numdays3)) {
            $numdays3 = 0;
        }if (empty($numdays4)) {
            $numdays4 = 0;
        }if (empty($numdays5)) {
            $numdays5 = 0;
        }if (empty($numdays6)) {
            $numdays6 = 0;
        }if (empty($numdays7)) {
            $numdays7 = 0;
        }
        $total_leaves = $numdays1 + $numdays2 + $numdays3 + $numdays4 + $numdays5 + $numdays6 + $numdays7;


        if (empty($leave_type1 && $numdays1)) {
            $type1 = '';
        } else {
            $type1 = $leave_type1 . ':' . $numdays1 . ':' . $CF1;
        }

        if (empty($leave_type2 && $numdays2)) {
            $type2 = '';
        } else {
            $type2 = $leave_type2 . ':' . $numdays2 . ':' . $CF2;
        }
        if (empty($leave_type3 && $numdays3)) {
            $type3 = '';
        } else {
            $type3 = $leave_type3 . ':' . $numdays3 . ':' . $CF3;
        }
        if (empty($leave_type4 && $numdays4)) {
            $type4 = '';
        } else {
            $type4 = $leave_type4 . ':' . $numdays4 . ':' . $CF4;
        }
        if (empty($leave_type5 && $numdays5)) {
            $type5 = '';
        } else {
            $type5 = $leave_type5 . ':' . $numdays5 . ':' . $CF5;
        }
        if (empty($leave_type6 && $numdays6)) {
            $type6 = '';
        } else {
            $type6 = $leave_type6 . ':' . $numdays6 . ':' . $CF6;
        }
        if (empty($leave_type7 && $numdays7)) {
            $type7 = '';
        } else {
            $type7 = $leave_type7 . ':' . $numdays7 . ':' . $CF7;
        }

        $rand_no_emp = (rand(10, 1000));
        $length_address = strlen($firm_address);
         $email_check = $this->check_edit_email_avalibility($boss_email_id,$firm_id);
         $validte_firm_email = $this->firm_model->validate_firm_email_edit($firm_email_id,$firm_id);
        if (empty(trim($firm_name))) {
            $response['id'] = 'firm_name';
            $response['error'] = 'Enter Office Name';
            echo json_encode($response);
            exit;
        } else if (empty(trim($firm_address))) {
            $response['id'] = 'firm_address';
            $response['error'] = 'Enter Firm Address';
            echo json_encode($response);
            exit;
        } else if ($length_address < 5) {
            $response['id'] = 'firm_address';
            $response['error'] = 'Entered Address Must Be Greater Than 5 Characters';
            echo json_encode($response);
            exit;
        } else if (empty(trim($boss_name))) {
            $response['id'] = 'boss_name';
            $response['error'] = 'Enter Office Head Name';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[A-Za-z???????\s\ ]*$/", $boss_name)) {
            $response['id'] = 'boss_name';
            $response['error'] = 'Only Space is Allowed';
            echo json_encode($response);
            exit;
        } elseif ($validte_firm_email == true) {
            $response['id'] = 'firm_email_id';
            $response['error'] = 'This Email Id Already Exits In The System';
             echo json_encode($response);
            exit;
        } else if (empty($boss_mobile_no)) {
            $response['id'] = "boss_mobile_no";
            $response['error'] = 'Enter Office Head Mobile Number';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^\d{10}$/", $boss_mobile_no)) { // phone number is valid
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Ten Digit Mobile Number';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[6-9][0-9]{9}$/", $boss_mobile_no)) {
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Proper Digit Mobile Number';
            echo json_encode($response);
            exit;
        } else if (empty(trim($boss_email_id))) {
            $response['id'] = 'boss_email_id';
            $response['error'] = 'Enter Email Id';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $boss_email_id)) {
            $response['id'] = 'boss_email_id';
            $response['error'] = "Invalid Email Format";
            echo json_encode($response);
        } elseif ($email_check==true) {
            $response['id'] = 'boss_email_id';
            $response['error'] = "This Email Id Already Exits In The System";
            echo json_encode($response);
        }else if ($firm_no_of_employee == "") {
            $response['id'] = "firm_no_of_employee";
            $response['error'] = 'Enter No of Employee In Office';
            echo json_encode($response);
            exit;
        } else if (empty(trim($country))) {
            $response['id'] = "country";
            $response['error'] = 'Enter Country Name';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[a-zA-Z,. ]*\z/", $country)) {
            $response['id'] = 'country';
            $response['error'] = 'Only Character Is Allowed';
            echo json_encode($response);
            exit;
        } else if (empty(trim($state))) {
            $response['id'] = 'state';
            $response['error'] = "Enter State Name";
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[a-zA-Z ]*\z/", $state)) {
            $response['id'] = 'state';
            $response['error'] = 'Only character Is Allowed';
            echo json_encode($response);
            exit;
        } else if (empty(trim($city))) {
            $response['id'] = "city";
            $response['error'] = "Enter City Name";
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[a-zA-Z,. ]*\z/", $city)) {
            $response['id'] = 'city';
            $response['error'] = 'Only Character Is Allowed';
            echo json_encode($response);
            exit;
        } elseif (empty(trim($longitude_value))) {
            $response['id'] = 'longitude_value';
            $response['error'] = 'Enter Proper Longitude';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[0-9]+(\.[0-9]*)?$/", $longitude_value)) {
            $response['id'] = 'longitude_value';
            $response['error'] = 'Enter Proper Longitude';
            echo json_encode($response);
            exit;
        } elseif (empty(trim($lattitude_value))) {
            $response['id'] = 'lattitude_value';
            $response['error'] = 'Enter Proper Lattitude';
            echo json_encode($response);
            exit;
        } elseif (!preg_match("/^[0-9]+(\.[0-9]*)?$/", $lattitude_value)) {
            $response['id'] = 'lattitude_value';
            $response['error'] = 'Enter Proper Lattitude Value';
            echo json_encode($response);
            exit;
        } elseif (empty(trim($joining_date))) {
            $response['id'] = 'joining_date';
            $response['error'] = 'Enter Joining Date';
            echo json_encode($response);
            exit;
        } elseif (empty($yearly_leave)) {
            $response['id'] = 'yearly_leave';
            $response['error'] = 'Enter yearly Leave Type';
            echo json_encode($response);
            exit;
        } elseif (empty($monthly_leaves)) {
            $response['id'] = 'monthly_leaves';
            $response['error'] = 'Enter Monthly Leave Type';
            echo json_encode($response);
            exit;
        } elseif (empty($leave_type_year)) {
            $response['id'] = 'leave_type_year';
            $response['error'] = 'Select Year Type';
            echo json_encode($response);
            exit;
        } elseif (empty($leave_cf)) {
            $response['id'] = 'leave_cf';
            $response['error'] = 'Select Leave Carry Forward Period';
            echo json_encode($response);
            exit;
        } elseif ($probation_period == '0' && $prob_date_first == '') {
            $response['id'] = 'prob_date_first';
            $response['error'] = 'Select Date Type';
            echo json_encode($response);
            exit;
        } elseif ($probation_period == '0' && $prob_date_second == '') {
            $response['id'] = 'prob_date_second';
            $response['error'] = 'Select Date Type';
            echo json_encode($response);
            exit;
        } elseif ($training_period == '0' && $training_period_first == '') {
            $response['id'] = 'training_period_first';
            $response['error'] = 'Select Date Type';
            echo json_encode($response);
            exit;
        } elseif ($training_period == '0' && $training_period_second == '') {
            $response['id'] = 'training_period_second';
            $response['error'] = 'Select Date Type';
            echo json_encode($response);
            exit;
        } elseif (empty(trim($accural_month))) {
            $response['id'] = 'accural_month';
            $response['error'] = 'Select Month';
            echo json_encode($response);
            exit;
        }
       else if ($total_leaves != $yearly_leave) {
           $response['id'] = 'numofdays3';
            $response['error'] = 'Sum Of Leave Should Be Equal To Yearly Leaves';
            echo json_encode($response);
            exit;
        } 
		else if ($total_leaves != $yearly_leave) {
            $response['id'] = 'numofdays3';
            $response['error'] = 'Sum Of Leave Should Be Equal To Yearly Leaves';
            echo json_encode($response);
            exit;
        }
        else if ($total_leaves != $yearly_leave) {
            $response['id'] = 'numofdays7';
            $response['error'] = 'Sum Of Leave Should Be Equal To Yearly Leaves';
            echo json_encode($response);
            exit;
        } else {

            if ($total_leaves != $yearly_leave) {
                $response['id'] = 'numofdays7';
                $response['error'] = 'Sum Of Leave Should Be Equal To Yearly Leaves';
                echo json_encode($response);
            } else {
                $data_leave = array(
                    'type1' => $type1,
                    'type2' => $type2,
                    'type3' => $type3,
                    'type4' => $type4,
                    'type5' => $type5,
                    'type6' => $type6,
                    'type7' => $type7,
                );
//            var_dump($data_leave);
//            exit;
                $array1 = array('firm_id' => $firm_id, 'designation_id' => 'CA');
                $this->db->where($array1);
                $res1 = $this->db->update('leave_header_all', $data_leave);

                $data_designation = array(
                    'modified_on' => $date,
                    'total_yearly_leaves' => $yearly_leave,
                    'total_monthly_leaves' => $monthly_leaves,
                    'holiday_consider_in_leave' => $holiday_status,
                    'carry_forward_period' => $leave_cf,
                    'request_leave_from' => $leave_apply_permission,
                    'year_type' => $leave_type_year
                );
                $array_leave = array('firm_id' => $firm_id, 'designation_id' => 'CA');
                $this->db->where($array_leave);
                $res2 = $this->db->update('designation_header_all', $data_designation);


                $data = array(
                    'firm_name' => $firm_name,
                    'firm_address' => $firm_address,
                    'boss_name' => $boss_name,
                    'boss_email_id' => $boss_email_id,
                    'boss_mobile_no' => $boss_mobile_no,
                    'firm_no_of_employee' => $firm_no_of_employee,
//                    'due_date_creation_permitted' => $due_date_creation_permitted,
//                    'task_creation_permitted' => $task_creation_permitted,
//                    'leave_type_fixed' => $leave_type_permission,
                    'accural_month' => $accural_month,
                    'modified_on' => $date,
                    'modified_by' => $email_id,
//                    'week_holiday' => $latest_srting
                );
//                var_dump($data);

                $data_gst = array(
                    'customer_address' => $firm_address,
                    'customer_email_id' => $email_id,
                );
//            var_dump($data);
//            exit;
//            $string_version = implode(" ", $data);
//            echo $string_version;

                $array1 = array('firm_id' => $firm_id);
                $this->db->where($array1);
                $res = $this->db->update('partner_header_all', $data);
//                echo $this->db->last_query();


                $data_location = $this->db->query("Select * from firm_location where firm_id='$firm_id'");
                if ($this->db->affected_rows() <= 0) {
                    $data_insert_loc = array(
                        'firm_id' => $firm_id,
                        'lattitude' => $lattitude_value,
                        'logitude' => $longitude_value,
                        'radius' => 200);

                $data_location_query = $this->db->insert("firm_location", $data_insert_loc);
                    
                } else {
                    $data_update_location = array(
                        'lattitude' => $lattitude_value,
                        'logitude' => $longitude_value
                    );
                    $array_data = array('firm_id' => $firm_id);
                    $this->db->where($array_data);
                    $this->db->update('firm_location', $data_update_location);
                }



                $data1 = array(
                    'user_name' => $boss_name,
                    'is_employee' => $is_emp,
                    'mobile_no' => $boss_mobile_no,
                    'address' => $firm_address,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'modified_on' => $date,
                    'modified_by' => $email_id,
                    'date_of_joining' => $joining_date,
                    'probation_period_start_date' => $prob_date_first,
                    'probation_period_end_date' => $prob_date_second,
                    'training_period_start_date' => $training_period_first,
                    'training_period_end_date' => $training_period_second,
//                    'create_task_assignment' => $create_task_assign_permit,
//                    'leave_approve_permission' => 1,
//                    'create_due_date_permission' => $create_due_date_task,
//                    'create_service_permission' => $create_service_permit,
//                    'enquiry_generate_permission' => $generate_enq_permit,
//                    'employee_permission' => $employee_permission,
//                    'customer_permission' => $customer_permission,
//                    'template_store_permission' => $template_permission,
//                    'knowledge_store_permission' => $knowledge_permission,
//                    'user_star_rating' => $star_rating,
//                    'task_approve_permission' => $task_approve,
//                    'task_sign_permission' => $task_sign,
                );
//                print_r($data1);
                $array1 = array('email' => $firm_email_id, 'created_by' => $email_id);
                $this->db->where($array1);
                $res1 = $this->db->update('user_header_all', $data1);
//                echo $this->db->last_query();

                echo json_encode(array('status' => true));
//            }
            }
        }
    }
    
    function check_edit_email_avalibility($boss_email_id,$firm_id) {
        //check hereemail id is available in database or not
            if ($this->firm_model->is_edit_email_available($boss_email_id,$firm_id)==true) {
                return true;
//                echo '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Email Is Already register</label>';
            } else {
                return false;
//                echo '<label class="text-success"><span class="glyphicon glyphicon-ok"></span>Valid Email Id</label>';
            }
        
    }

    public function get_firm_data_edit($firm_id = '') {
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
        $query = $this->db->query("SELECT * from partner_header_all WHERE  firm_id = '$firm_id'");
        if ($query->num_rows() > 0) {

            $query1 = $this->db->query("SELECT * from user_header_all WHERE firm_id ='$firm_id' AND `created_by`='$email_id'");
            if ($query1->num_rows() > 0) {
                $record = $query->row();
                $record2 = $query1->row();
            } else {
                $record = "";
                $record2 = "";
            }
        }
        $data['firm_id'] = $firm_id;
        $data['record'] = $record;
        $data['record2'] = $record2;
        $data['prev_title'] = "Edit HR Admin";
        $data['page_title'] = "Edit HR Admin";
        $this->load->view('admin/edit_firm_sa', $data);
    }
    
  

    public function edit_firm() {
        $firm_name = $this->input->post('firm_name');
        $firm_id = $this->input->post('firm_id');
        $user_id = $this->input->post('user_id');
        $firm_activity_status = $this->input->post('firm_activity_status');
        $firm_address = $this->input->post('firm_address');
//        $firm_type = $this->input->post('firm_type');
//        $boss_name = $this->input->post('boss_name');
        $boss_mobile_no = $this->input->post('boss_mobile_no');
        $firm_email_id = $this->input->post('firm_email_id');
//        $boss_email_id = $this->input->post('boss_email_id');
//        $firm_no_of_employee = $this->input->post('firm_no_of_employee');
//        $firm_no_of_customers = $this->input->post('firm_no_of_customers');
//        $firm_no_of_permitted_offices = $this->input->post('firm_no_of_permitted_offices');


        $validte_firm_email = $this->firm_model->validate_firm_email_edit($firm_email_id,$firm_id);
        $country = $this->input->post('country');
        $state = $this->input->post('state');
        $city = $this->input->post('city');
        $date = date('y-m-d h:i:s');
        $length_address = strlen($firm_address);
        if (empty(trim($firm_name))) {
            $response['id'] = 'firm_name';
            $response['error'] = 'Enter HR Admin Name';
            echo json_encode($response);
        }elseif ($validte_firm_email == true) {
            $response['id'] = 'firm_email_id';
            $response['error'] = 'This Email Id Already Exits In The System';
             echo json_encode($response);
        } else if (empty(trim($firm_address))) {
            $response['id'] = 'firm_address';
            $response['error'] = 'Enter  Address';
            echo json_encode($response);
        } else if ($length_address < 5) {
            $response['id'] = 'firm_address';
            $response['error'] = 'Entered Address Must Be Greater Than 5 Characters';
            echo json_encode($response);
        } elseif (empty(trim($boss_mobile_no))) {
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter  Mobile Number';
            echo json_encode($response);
        } elseif (!preg_match("/^[6-9][0-9]{9}$/", $boss_mobile_no)) {
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Proper Digit Mobile Number';
            echo json_encode($response);
        } elseif (!preg_match("/^\d{10}$/", $boss_mobile_no)) {
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Ten Digit Mobile Number';
            echo json_encode($response);
        } elseif (empty(trim(($country)))) {
            $response['id'] = 'country';
            $response['error'] = 'Enter  Your Country';
            echo json_encode($response);
        } elseif (!preg_match("/^[a-zA-Z ]*\z/", $country)) {
            $response['id'] = 'country';
            $response['error'] = 'Only Character Is Allowed';
            echo json_encode($response);
        } elseif (empty(trim(($state)))) {
            $response['id'] = 'state';
            $response['error'] = 'Enter State Name';
            echo json_encode($response);
        } elseif (!preg_match("/^[a-zA-Z ]*\z/", $state)) {
            $response['id'] = 'state';
            $response['error'] = 'Only Character Is Allowed';
            echo json_encode($response);
        } elseif (empty(trim(($city)))) {
            $response['id'] = 'city';
            $response['error'] = 'Enter City Namw';
            echo json_encode($response);
        } elseif (!preg_match("/^[a-zA-Z,. ]*\z/", $city)) {
            $response['id'] = 'city';
            $response['error'] = 'Only Character Is Allowed';
            echo json_encode($response);
        } else {

            $update_data_partner = array(
                'firm_name' => $firm_name,
                'firm_activity_status' => $firm_activity_status,
                'firm_address' => $firm_address,
                'boss_mobile_no' => $boss_mobile_no,
                'modified_on' => $date,
                'modified_by' => $user_id,
            );

            $this->db->where('firm_id', $firm_id);
            $partner = $this->db->update('partner_header_all', $update_data_partner);
//            echo $this->db->last_query();
            $update_data_user = array(
                'user_name' => $firm_name,
                'mobile_no' => $boss_mobile_no,
                'activity_status' => $firm_activity_status,
                'address' => $firm_address,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'modified_on' => $date,
                'modified_by' => $user_id,
            );
            $this->db->where('firm_id', $firm_id);
            $user = $this->db->update('user_header_all', $update_data_user);
//            echo $this->db->last_query();
            if ($partner === TRUE && $user === TRUE) {
//            if ($user === TRUE) {
                $response['status'] = true;
            } else {
                $response['status'] = false;
            }echo json_encode($response);
        }
    }

}

?>
