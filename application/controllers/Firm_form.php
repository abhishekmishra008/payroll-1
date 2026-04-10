<?php

class Firm_form extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Firm_model');
        $this->load->model('email_sending_model');
        $this->load->model('customer_model');
        $this->load->model('Nas_modal');
    }

    public function index() {
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
        $data['prev_title'] = "Add HR Admin";
        $data['page_title'] = "Add HR Admin";
        $this->load->view('admin/firm_form', $data);
    }

    public function index_branch_firm() {//Office created for branch
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
        $data['prev_title'] = "Add Office";
        $data['page_title'] = "Add Office";
        $this->load->view('admin/branch_firm_form', $data);
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
        $firm_name = $this->input->post('firm_name');
        // $user_id = $this->input->post('user_id');
        $firm_activity_status = $this->input->post('firm_activity_status');
        $firm_address = $this->input->post('firm_address');
        $firm_email_id = $this->input->post('firm_email_id');
        $boss_id = $this->generate_boss_id();
        $boss_mobile_no = $this->input->post('boss_mobile_no');
        $country = $this->input->post('country');
        $state = $this->input->post('state');
        $city = $this->input->post('city');


        $date = date('y-m-d h:i:s');
        $reporting_to = $boss_id;

        $user_type = '2';


   $length_address = strlen($firm_address);
               $validte_firm_email = $this->Firm_model->validate_firm_email($firm_email_id);

        if (empty(trim($firm_name))) {
            $response['id'] = 'firm_name';
            $response['error'] = 'Enter HR Admin Name';
        } elseif (empty(trim($firm_email_id))) {
            $response['id'] = 'firm_email_id';
            $response['error'] = 'Enter HR Admin Email Id';
        } else if (!filter_var($firm_email_id, FILTER_VALIDATE_EMAIL)) {
            $response['id'] = 'firm_email_id';
            $response['error'] = "Invalid Email Format";
        } elseif ($validte_firm_email == true) {
                $response['id'] = 'firm_email_id';
                $response['error'] = 'This Email Id Already Exits In The System';
            }  
		
		elseif (empty(trim($firm_address))) {
            $response['id'] = 'firm_address';
            $response['error'] = 'Enter Address';
        }
	
		else if ($length_address < 5) {
            $response['id'] = 'firm_address';
            $response['error'] = 'Entered Address Must Be Greater Than 5 Characters';
        } 
		elseif (empty(trim($boss_mobile_no))) {
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Owner Mobile Number';
        } elseif (!preg_match("/^\d{10}$/", $boss_mobile_no)) {
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Ten Digit Mobile Number';
        } elseif (!preg_match("/^[6-9][0-9]{9}$/", $boss_mobile_no)) {
            $response['id'] = 'boss_mobile_no';
            $response['error'] = 'Enter Proper Digit Mobile Number';
        } elseif (empty(trim($country))) {
            $response['id'] = 'country';
            $response['error'] = 'Enter Country Name';
        } elseif (!preg_match("/^[a-zA-Z ]*\z/", $country)) {
            $response['id'] = 'country';
            $response['error'] = 'Only Character Is Allowed';
        } elseif (empty(trim($state))) {
            $response['id'] = 'state';
            $response['error'] = 'Enter State Name';
        } elseif (!preg_match("/^[a-zA-Z ]*\z/", $state)) {
            $response['id'] = 'state';
            $response['error'] = 'Only Character Is Allowed';
        } elseif (empty(trim($city))) {
            $response['id'] = 'city';
            $response['error'] = 'Enter City Name';
        } elseif (!preg_match("/^[a-zA-Z,. ]*\z/", $city)) {
            $response['id'] = 'city';
            $response['error'] = 'Only Character Is Allowed';
        } else {

            if ($firm_activity_status == 'A') {
                $user_activity_status = '1';
            } else {
                $user_activity_status = '0';
            }

			//else {
                //$session_user_id = $user_id;
                $get_user_id = $this->generate_user_id();
                $data = array(
//                    'user_id'=>$get_user_id,
                    'firm_id' => $firm_id,
                    'firm_name' => $firm_name,
                    'firm_activity_status' => $firm_activity_status,
                    'firm_address' => $firm_address,
                    'firm_email_id' => $firm_email_id,
//                    'boss_id' => $boss_id,
                    'boss_id' => $boss_id,
                    'boss_mobile_no' => $boss_mobile_no,
                    'reporting_to' => $reporting_to,
                    'created_by' => $email_id,
//                    'created_by' => '1',
                    'created_on' => $date,
                    'created_from' => $email_id,
                );



//                var_dump($data_location_query);
                $password = rand(100, 1000);

                // sms function code
                $auth_key = "178904AVN94GK259e5e87b";
                $no = $data['boss_mobile_no'];
                $trigger_by = "superuser";

                $result = $this->Firm_model->add_firm_modal_admin($data, $get_user_id, $user_type, $boss_id, $reporting_to, $user_activity_status, $email_id, $password, $city, $state, $country);
                //  echo $result."namrata";
                if ($result == TRUE) {

                    //email  function
//                    $email = $this->email_sending_model->sendEmail($user_type, $firm_name, $firm_email_id, $password, $trigger_by);
                    // sms function code
//                    $sms = $this->Firm_model->sendSms($auth_key, $no, $user_type,  $firm_name, $firm_email_id, $password, $trigger_by);
                    $response['status'] = true;
                } else if ($result == FALSE) {
                    $response['status'] = false;
                }
            //}
        }
        echo json_encode($response);
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

    function generate_user_id11() {
        $get_user_id = $this->Firm_model->selectusereId();
        if ($get_user_id != NULL) {
            $user_id = $get_user_id->user_id;
            $user_id = str_pad(++$user_id, 5, '0', STR_PAD_LEFT);
            return $user_id;
        } else {
            $user_id = 'U_1001';
            return $user_id;
        }
    }
    function generate_user_id() {

        $user_id = 'U_' . rand(100, 1000);
        $this->db->select('user_id');
        $this->db->where('user_id', $user_id);
        $this->db->get('user_header_all');
        if ($this->db->affected_rows() > 0) {
            return generate_user_id();
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
            $firm_id = str_pad( ++$firm_id, 5, '0', STR_PAD_LEFT);
            return $firm_id;
        } else {
            $firm_id = 'Firm_1001';
            return $firm_id;
        }
    }

    // function to show firm data
    function view_firm_data() {
        $data['prev_title'] = "HR Admin List";
        $data['page_title'] = "HR Admin List";
        $this->load->helper('url');
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

        $query = $this->db->query("SELECT * FROM `partner_header_all` WHERE `firm_type` != 'associate'"); // all HQ admin
        if ($query->num_rows() > 0) {
            $record = $query->result();
            foreach ($record as $row) {
                $firm_id = $row->firm_id;
                $boss_id = $row->boss_id;

                // num rows of Client admin count
                $query = $this->db->select('*');
                $query = $this->db->where("reporting_to='$boss_id' AND firm_activity_status='A' AND boss_id !='$boss_id'"); // firm_count
                $query = $this->db->get('partner_header_all');
                $client_admin_count = $query->num_rows();


                //total no of Employees in HQ firm count
                $query = $this->db->select('*');
                $query = $this->db->where("boss_id='$boss_id' AND is_employee='1' AND user_type='4'"); // Employee_count
                $query = $this->db->get('user_header_all');
                $employee_count = $query->num_rows();
                $for = "nas_status";
                $access_token_for_update = "";
                $check_nas_device = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update);

                $adata['all_count'][] = ['branch_count' => $client_admin_count, 'employee_count' => $employee_count, 'nas_status' => $check_nas_device];
            }

            $data['result1'] = $adata['all_count'];
            $data['result'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $data['result1'] = '';
            $data['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }


        $this->load->view('admin/view_firm_data', $data);
    }

    public function change_activity_status() {
        $firm_id = base64_decode($this->input->post('firm_id'));
        $status = $this->input->post('status');

        $result = $this->Firm_model->client_activity_status($firm_id, $status);

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

    public function get_access_token_php() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $firm_id = $result1['firm_id'];
        }
        if ($firm_id === '') {
            $firm_id = $this->session->userdata('firm_id');
        } else {
            $firm_id = '';
        }


        $for = "access_token";
        $access_token_for_update = '';
        $access_token = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update);

        if ($access_token != false) {
            $response['accees_token'] = $access_token;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['accees_token'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
        }
        echo json_encode($response);
    }

}

?>
