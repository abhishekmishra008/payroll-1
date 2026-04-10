
<?php

class Hq_customer extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('Due_date_model');
        $this->load->model('firm_model');
        $this->load->model('task_allote_model');
        $this->load->model('email_sending_model');
        $this->load->library('form_validation');
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
        $data['prev_title'] = "Customer";
        $data['page_title'] = "Customer";
        $this->load->view('hq_admin/add_customer', $data);
    }

    public function create_customer() {
        $customer_id = $this->getCustomerId();
        $customer_name = $this->input->post('customer_name');
        $customer_address = $this->input->post('customer_address');
        $customer_city = $this->input->post('customer_city');
        $customer_state = $this->input->post('customer_state');
        $customer_country = $this->input->post('customer_country');
        $customer_no = $this->input->post('customer_contact_number');
        $customer_email = $this->input->post('customer_email_id');
        $active_status = $this->input->post('activity_status');
        $gst_no = $this->input->post('gst_no');
        $pincode = $this->input->post('pincode');
        $pan_no = $this->input->post('pan_no');
        $contact_person_name = $this->input->post('contact_person_name');
        $contact_person_no = $this->input->post('contact_person_number');
        $contact_person_email = $this->input->post('contact_person_email_id');
        $director_name = $this->input->post('director_name_din');
        $customer_type = $this->input->post('customer_type');
        $date = date('y-m-d h:i:s');
        $attached_due_date_id = $this->input->post('attached_due_date_id');
        $user_id = $this->input->post('user_id');
        $cust_count = $this->input->post('cust_count');
        $firm_id = $this->input->post('ddl_firm_name_fetch');
        $boss_id = $this->input->post('hdn_boss_id');

        if (empty($firm_id)) {
            $response['id'] = 'ddl_firm_name_fetch';
            $response['error'] = 'Please Select Firm Name';
        } elseif (empty(trim($customer_name))) {
            $response['id'] = 'customer_name';
            $response['error'] = 'Enter Proper Name';
        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $customer_name)) {
            $response['id'] = 'customer_name';
            $response['error'] = 'Enter  Customer Name';
        } elseif (empty($customer_type)) {
            $response['id'] = 'customer_type';
            $response['error'] = 'Enter Customer Type';
        } elseif (empty($customer_email)) {
            $response['id'] = 'customer_email_id';
            $response['error'] = 'Enter Customer Email Id';
        } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
            $response['id'] = 'customer_email_id';
            $response['error'] = "Invalid email format";
        } elseif (empty($customer_no)) {
            $response['id'] = 'customer_contact_number';
            $response['error'] = 'Enter Customer Contact No.';
        } elseif (!preg_match("/^\d{10}$/", $customer_no)) { // phone number is valid
            $response['id'] = 'customer_contact_number';
            $response['error'] = 'Enter Proper Mobile No.';
        } elseif (empty(trim($director_name))) {
            $response['id'] = 'director_name_din';
            $response['error'] = 'Enter Director Name';
        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $director_name)) {
            $response['id'] = 'director_name_din';
            $response['error'] = 'Enter  Customer Director Name';
        } elseif (empty(trim($customer_address))) {
            $response['id'] = 'customer_address';
            $response['error'] = 'Enter Customer Address';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $customer_address)) {
            $response['id'] = 'customer_address';
            $response['error'] = 'Enter Proper Address';
        } elseif (empty($pincode)) {
            $response['id'] = 'pincode';
            $response['error'] = 'Enter Pincode';
        } elseif (empty(trim($customer_city))) {
            $response['id'] = 'customer_city';
            $response['error'] = 'Enter Customer City';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $customer_city)) {
            $response['id'] = 'customer_city';
            $response['error'] = 'Enter Customer City';
        } elseif (empty(trim($customer_state))) {
            $response['id'] = 'customer_state';
            $response['error'] = 'Enter Customer State';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $customer_state)) {
            $response['id'] = 'customer_state';
            $response['error'] = 'Enter Customer State';
        } elseif (empty(trim($customer_country))) {
            $response['id'] = 'customer_country';
            $response['error'] = 'Enter Customer Country';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $customer_country)) {
            $response['id'] = 'customer_country';
            $response['error'] = 'Enter Customer Country';
        } elseif (empty($gst_no)) {
            $response['id'] = 'gst_no';
            $response['error'] = 'Enter GST No';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $pincode)) {
            $response['id'] = 'pincode';
            $response['error'] = 'Enter Proper Pincode';
        } elseif (empty($attached_due_date_id)) {
            $response['id'] = 'attached_due_date_id';
            $response['error'] = 'Please select due date';
        } else {
            if (!empty($attached_due_date_id)) {
                $attached_due_date_id = implode(",", $attached_due_date_id);
            } else {
                $attached_due_date_id = "";
            }

            if ($cust_count !== "") {
                // variable declaration
                $a = "";
                $b = "";
                $c = "";
                $d = "";
                $e = "";


                for ($i = 1; $i <= $cust_count; $i++) {

                    $e_name = $this->input->post('employee_name' . $i);
                    $e_mobile_no = $this->input->post('employee_mobile_number' . $i);
                    $e_email_id = $this->input->post('employee_email_id' . $i);
                    $e_designation = $this->input->post('employee_designation' . $i);
                    $e_visible = $this->input->post('employee_visible_to' . $i);
                    $default_contact = $this->input->post('employee_default');


                    if ((empty(trim($e_name)))) {
                        $response['error'] = "Please enter Proper Name";
                        $response['id'] = 'employee_name' . $i;
                        echo json_encode($response);
                        exit();
                    } elseif ((empty($e_mobile_no))) {
                        $response['error'] = "Please enter Proper Contact No";
                        $response['id'] = 'employee_mobile_number' . $i;
                        echo json_encode($response);
                        exit();
                    } elseif (!preg_match("/^\d{10}$/", $e_mobile_no)) { // phone number is valid
                        $response['id'] = 'employee_mobile_number' . $i;
                        $response['error'] = 'Enter Proper Mobile No.';
                        echo json_encode($response);
                        exit();
                    } elseif ($e_mobile_no == $customer_no) {
                        $response['error'] = "Please provide another Contact no";
                        $response['id'] = 'employee_mobile_number' . $i;
                        echo json_encode($response);
                        exit();
                    } elseif ((empty($e_email_id))) {
                        $response['error'] = "Please enter Email_id";
                        $response['id'] = 'employee_email_id' . $i;
                        echo json_encode($response);
                        exit();
                    } elseif (!filter_var($e_email_id, FILTER_VALIDATE_EMAIL)) {
                        $response['error'] = "Invalid email format";
                        $response['id'] = 'employee_email_id' . $i;
                        echo json_encode($response);
                        exit();
                    } elseif ($e_email_id == $customer_email) {
                        $response['error'] = "Please provide another email id";
                        $response['id'] = 'employee_email_id' . $i;
                        echo json_encode($response);
                        exit();
                    } elseif (trim(empty($e_designation))) {
                        $response['error'] = "Please enter Designation";
                        $response['id'] = 'employee_designation' . $i;
                        echo json_encode($response);
                        exit();
                    } elseif (empty(trim($e_visible))) {
                        $response['error'] = "Please enter Designation";
                        $response['id'] = 'employee_visible_to' . $i;
                        echo json_encode($response);
                        exit();
                    }


                    if ($e_visible == '1') {
                        $e_visible = '1';
                    } else {
                        $e_visible = '2';
                    }

                    if ($default_contact == $i) {
                        $default_contact = '1';
                    } else {
                        $default_contact = '2';
                    }


                    $a_data = array(
                        'customer_id' => $customer_id,
                        'customer_employee_id' => $this->getCustEmpId(),
                        'employee_name' => $e_name,
                        'employee_mobile_number' => $e_mobile_no,
                        'employee_email_id' => $e_email_id,
                        'employee_designation' => $e_designation,
                        'employee_visible_to' => $e_visible,
                        'default_contact ' => $default_contact
                    );

                    $q_cust_emp = $this->db->insert('customer_employee_detail_all', $a_data);
                }
            }


            $data = array(
                'customer_id' => $customer_id,
                'firm_id' => $firm_id,
                'boss_id' => $boss_id,
                'created_on' => $date,
                'customer_name' => $customer_name,
                'customer_address' => $customer_address,
                'customer_city' => $customer_city,
                'customer_state' => $customer_state,
                'customer_country' => $customer_country,
                'customer_contact_number' => $customer_no,
                'customer_email_id' => $customer_email,
                'activity_status' => $active_status,
                'gst_no' => $gst_no,
                'pincode' => $pincode,
                'pan_no' => $pan_no,
                'director_name_din' => $director_name,
                'customer_type' => $customer_type,
                'attached_due_date_id' => $attached_due_date_id,
            );
//storing customer data into gst database
            $password = mt_rand(100, 500);
            $user_type_gst = '2';
            $data_gst = array(
                'customer_id' => $customer_id,
                'user_type' => $user_type_gst,
                'firm_id' => $firm_id,
                'created_on' => $date,
                'customer_name' => $customer_name,
                'customer_address' => $customer_address,
                'customer_city' => $customer_city,
                'customer_state' => $customer_state,
                'customer_country' => $customer_country,
                'customer_contact_number' => $customer_no,
                'customer_email_id' => $customer_email,
                'password' => $password,
                'activity_status' => $active_status,
                'gst_no' => $gst_no,
                'pincode' => $pincode,
                'pan_no' => $pan_no,
            );
           // $otherdb = $this->load->database('db2', TRUE); //bhava
            //$qr_gst_insert = $otherdb->insert('customer_header_all', $data_gst);

            $validte_firm_email = $this->customer_model->validate_customer_email($customer_email, $firm_id);
            if ($validte_firm_email == TRUE) {
                $response['id'] = 'customer_email_id';
                $response['error'] = 'This email id already exits in the system';
            } else {
                $record = $this->customer_model->add_customer($data, $customer_id, $attached_due_date_id, $firm_id);
                $record1 = $this->customer_model->add_customer_gst($data_gst);
                if ($record == '1') {
                    // email function code
                    $email = $this->email_sending_model->CustomersendEmail($customer_email, $customer_no, $attached_due_date_id, $firm_id, $customer_name);
                    // sms function code
                    $auth_key = "178904AVN94GK259e5e87b";
                    $sms = $this->customer_model->sendSms($auth_key, $customer_no, $attached_due_date_id, $firm_id, $customer_name);
                    // sms function code
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
        }
        echo json_encode($response);
    }

    public function getCustEmpId() {
        $result = $this->db->query('SELECT customer_employee_id FROM `customer_employee_detail_all` ORDER BY customer_employee_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $customer_employee_id = $data->customer_employee_id;
//generate user_id
            $customer_employee_id = str_pad( ++$customer_employee_id, 5, '0', STR_PAD_LEFT);
            return $customer_employee_id;
        } else {
            $customer_employee_id = 'C_Emp_1001';
            return $customer_employee_id;
        }
    }

    public function getCustomerId() {
        $result = $this->db->query('SELECT customer_id FROM `customer_header_all` ORDER BY customer_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $customer_id = $data->customer_id;
//generate user_id
            $customer_id = str_pad( ++$customer_id, 5, '0', STR_PAD_LEFT);
            return $customer_id;
        } else {
            $customer_id = 'Cust_1001';
            return $customer_id;
        }
    }

    public function get_ddl_firm_name() {
        $user_id = $this->session->userdata('login_session');
        $response = array('code' => -1, 'status' => false, 'message' => '');
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where('firm_email_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result_hq_cust = $query->row();
            $get_boss_id = $result_hq_cust->boss_id;
            $this->db->select('*');
            $this->db->from('partner_header_all');
            $this->db->where("reporting_to = '$get_boss_id' AND firm_activity_status = 'A' AND firm_email_id!='$user_id'");
            $query_2 = $this->db->get();
            if ($query_2->num_rows() > 0) {
                $data = array('firm_name' => array(), 'firm_id' => array(), 'boss_id' => array());
                foreach ($query_2->result() as $row) {
                    $data['firm_name'] = $row->firm_name;
                    $data['firm_id'] = $row->firm_id;
                    $data['boss_id'] = $row->boss_id;
                    $response['firm_data'][] = ['firm_name' => $row->firm_name, 'firm_id' => $row->firm_id, 'reporting_to' => $row->reporting_to, 'boss_id' => $row->boss_id];
                }
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function duedate_Name() {

        $firm_id = $this->input->post('firm_id');

        $this->db->select('*');
        $this->db->from('due_date_header_all');
        $this->db->join('due_date_task_header_all', 'due_date_header_all.due_date_id = due_date_task_header_all.due_date_id');
        $this->db->where('due_date_header_all.firm_id', $firm_id);
        $this->db->group_by('due_date_header_all.due_date_id');
        $result = $this->db->get();
        //echo $this->db->last_query();
        if ($result->num_rows() == null) {
            //       $data = $result->result();
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'unsuccess';
            $response['code'] = 200;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function ddl_due_date() {
        $response = array('code' => -1, 'status' => false, 'message' => '');
        $firm_id = $this->input->post('firm_id');
        //var_dump($firm_id);
        if ($firm_id != FALSE) {
            $due_date = $this->Due_date_model->get_ddl_due_date($firm_id);
            //var_dump($due_date);
            $data = array('due_date_name' => array(), 'due_date_id' => array());
            if ($due_date != null) {
                foreach ($due_date as $row) {
                    $data['due_date_name'] = $row->due_date_name;
                    $data['due_date_id'] = $row->due_date_id;
                    $response['due_data'][] = ['due_date_name' => $row->due_date_name, 'due_date_id' => $row->due_date_id];
                }
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function customer_ddl_due_date() {
        $cust_id = base64_decode($this->input->post('cust_id'));
        $firm_id = base64_decode($this->input->post('firm_id'));
        $this->db->select('*');
        $this->db->from('customer_header_all');
        $this->db->where('customer_id', $cust_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $record = $query->row();
            $attached_due_dates = $record->attached_due_date_id;
//            if ($attached_due_dates == "") {
//                $result = $this->customer_model->get_firm_id();
//                if ($result != FALSE) {
//                    $firm_id = $result['firm_id'];
//                    $due_date = $this->due_date_model->get_ddl_due_date($firm_id);
//                    $data = array('due_date_name' => array(), 'due_date_id' => array());
//                    foreach ($due_date as $row) {
//                        $data['due_date_name'] = $row->due_date_name;
//                        $data['due_date_id'] = $row->due_date_id;
//                        $response['due_data'][] = ['due_date_name' => $row->due_date_name, 'due_date_id' => $row->due_date_id];
//                    }
//                    $response['message'] = 'success';
//                    $response['code'] = 200;
//                    $response['status'] = true;
//                } else {
//                    $response['message'] = 'No data to display';
//                    $response['code'] = 204;
//                    $response['status'] = false;
//                }
//            } else {
            $temp_attch_due_date = explode(',', $attached_due_dates);
            $firm_id = base64_decode($this->input->post('firm_id'));

            if ($firm_id != FALSE) {
                $due_date = $this->Due_date_model->get_ddl_due_date($firm_id);
                if ($due_date == NULL) {
                    $response['message'] = 'NoData';
                    $response['code'] = 202;
                    $response['status'] = false;
                } else {
                    $data = array('due_date_name' => array(), 'due_date_id' => array());
                    if (empty($data)) {
                        $n1[] = "not_due#aadesh";
                    } else {
                        foreach ($due_date as $row) {
                            $data['due_data_id'][] = $row->due_date_id;
                        }
                        $temp_due_data_id = $data['due_data_id'];


                        $arr1 = $temp_attch_due_date;
                        $arr2 = $temp_due_data_id;
                        $a1 = sizeof($arr1);
                        $a2 = sizeof($arr2);
                        $to_be_attach = "";
                        if ($a1 > $a2) {
                            foreach ($temp_attch_due_date as $a) {
                                if (in_array($a, $temp_due_data_id)) {
//                              $new["matched"][]=$a;
//                              echo "Match found";
                                } else {
                                    $to_be_attach .= $a . ",";
//                                echo $a;
//                              echo "Match not found";
                                }
                            }
                        } else if ($a2 >= $a1) {
                            foreach ($temp_due_data_id as $a) {
                                if (in_array($a, $temp_attch_due_date)) {
//                              $new["matched"][]=$a;
//                              echo "Match found";
                                } else {
//                              $new1["not_matched"][]=$a;
//                                echo $a;
                                    $to_be_attach .= $a . ",";
//                                $new1[]=$a;
//                              echo "Match not found";
                                }
                            }
                        }
                        $temp = rtrim($to_be_attach, ',');
                        $ty = strlen($temp);
                        $temp2 = substr_count($temp, ',');
//                $n1[]="";
                        if ($temp2 == 0) {
                            if ($ty == 0) {
                                $n1[] = "not_due#aadesh";
                            } else {
                                $result = $this->db->query("SELECT due_date_name FROM `due_date_header_all` WHERE `due_date_id`='$temp' and `firm_id`='$firm_id'");
                                if ($result->num_rows() > 0) {
                                    $record = $result->row();
                                    $due_date_name = $record->due_date_name;
                                    $n1[] = $temp . ":" . $due_date_name;
                                }
                            }
                        } else {
                            $imp_temp = explode(',', $temp);
                            for ($r = 0; $r < sizeof($imp_temp); $r++) {
                                $result = $this->db->query("SELECT due_date_name FROM `due_date_header_all` WHERE `due_date_id`='$imp_temp[$r]' and `firm_id`='$firm_id'");
                                if ($result->num_rows() > 0) {
                                    $record = $result->row();
                                    $due_date_name = $record->due_date_name;
                                    $n1[] = $imp_temp[$r] . ":" . $due_date_name;
                                }
                            }
                        }
                    }
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                    $response['due_date_to_be_attach'] = $n1;
                }
            } else {
                $response['message'] = 'fail';
                $response['code'] = 203;
                $response['status'] = false;
            }
//            }
        } else {
            $response['message'] = 'success';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

// VIEW CUSTOMER
    public function view_customer_data($selected_firm_id = '') {
		
        if (empty($selected_firm_id)) {
            $data['prev_title'] = "View Customer";
            $data['page_title'] = "View Customer";
			$data['due_date_creation_permitted'] ='';
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
            }
			
			
            $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
            if ($query->num_rows() > 0) {

                $record = $query->row();
                $firm_logo = $record->firm_logo;
                $firm_name1 = $record->user_name;
                if ($firm_logo == "" && $firm_name1 == "") {

                    $data['logo'] = "";
                    $data['firm_name_nav'] = "";
                } else {
                    $data['logo'] = $firm_logo;
                    $data['firm_name_nav'] = $firm_name1;
                }
            } else {
                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            }

            $data['result1'] = '';
            $data['selected_firm_name'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {
			
            $data['prev_title'] = "View Customer";
            $data['page_title'] = "View Customer";
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
            }
            $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
            if ($query->num_rows() > 0) {

                $record = $query->row();
                $firm_logo = $record->firm_logo;
                $firm_name1 = $record->user_name;
                if ($firm_logo == "" && $firm_name1 == "") {

                    $data['logo'] = "";
                    $data['firm_name_nav'] = "";
                } else {
                    $data['logo'] = $firm_logo;
                    $data['firm_name_nav'] = $firm_name1;
                }
            } else {
                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            }
		
			
            $email_id = $this->session->userdata('login_session');
            $result = $this->db->query("SELECT * FROM `partner_header_all` WHERE `firm_email_id`='$email_id' and due_date_creation_permitted='1'");
            if ($result->num_rows() > 0) {
                $record = $result->row();
                $boss_id = $record->boss_id;
//                echo "SELECT * FROM `partner_header_all` WHERE `reporting_to` = '$boss_id' AND firm_id ='$selected_firm_id'";
                
                $query = $this->db->query("SELECT * FROM `partner_header_all` WHERE `reporting_to` = '$boss_id' AND firm_id ='$selected_firm_id'");
				
                if ($query->num_rows() > 0) {
                    $firm_data = $query->row();
                    $s_firm_name = $firm_data->firm_name;
                    $due_date_creation_permitted = $firm_data->due_date_creation_permitted;

                    $this->db->select('customer_id,firm_id,boss_id,customer_type,created_on, customer_name,customer_address,customer_city,activity_status,attached_due_date_id');
                    $this->db->from('customer_header_all');
                    $this->db->where("firm_id='$selected_firm_id' AND activity_status='1'");
					
                    $query_cust = $this->db->get();
					
                    if ($query_cust->num_rows() > 0) {
                        $record_cust = $query_cust->result();
                        $data = array('task_count' => array(), 'customer_id' => array(), 'duedate_count' => array());
                        $total = 0;
                        foreach ($record_cust as $row) {
                            $customer_id = $row->customer_id;
                            $firm_id = $row->firm_id;
                            $boss_id = $row->boss_id;
                            $created_on = $row->created_on;
                            $customer_name = $row->customer_name;
                            $customer_address = $row->customer_address;
                            $customer_city = $row->customer_city;
                            $activity_status = $row->activity_status;
                            $attached_due_date_id = $row->attached_due_date_id;
                            $customer_type = $row->customer_type;
                            $result2 = $this->db->query("SELECT  DISTINCT `customer_id`,`task_assignment_id` FROM `customer_task_allotment_all` WHERE  customer_id='$customer_id'");
                            $total = $result2->num_rows();
                            if ($result2->num_rows() > 0) {
                                $record2 = $result2->row();
                            } else {
                                $record2 = 0;
                            }
                            // for due date count
                            $this->db->select('attached_due_date_id');
                            $this->db->from('customer_header_all');
                            $this->db->where("activity_status='1' and customer_id='$customer_id'");
                            $query_customer = $this->db->get();
                            if ($query_customer->num_rows() > 0) {
                                $customer_rs = $query_customer->row();
                                 $attach_due_date = $customer_rs->attached_due_date_id;
                                if ($attach_due_date === "") {
                                    $deu_count = 0;
                                } else {
                                    $temp_due = preg_split("/,/", $attach_due_date);
                                    $deu_count = count($temp_due);
                                }
                            } else {

                            }
                            $response['task_due_count'][] = ['customer_id' => $customer_id, 'customer_type' => $customer_type, 'task_count' => $total, 'duedate_count' => $deu_count, 'firm_id' => $firm_id, 'attached_due_date_id' => $attached_due_date_id, 'boss_id' => $boss_id, 'customer_name' => $customer_name, 'activity_status' => $activity_status, 'attached_due_date_id' => $attached_due_date_id,'due_date_creation_permitted'=> $due_date_creation_permitted];
                        }
                    } else {
                        $response['task_due_count'][] = '';
                    }
					$q11 = $this->db->query("SELECT `due_date_creation_permitted` from `partner_header_all` where `firm_id`='$selected_firm_id'");
					if ($q11->num_rows() > 0) {
					$due_date = $q11->row();
						 $due_date_creation_permitted = $due_date->due_date_creation_permitted;
					
					
					$data['due_date_creation_permitted'] = $due_date_creation_permitted;
                }
				else{
					$data['due_date_creation_permitted'] ='';
				}

                    $data['page_title'] = "Customer Form";
                    //$data['prev_title'] = "Customer Form";
                    $data['result1'] = $response['task_due_count'];
                    $data['logo'] = $firm_logo;
                    $data['firm_name_nav'] = $firm_name1;
                    $data['selected_firm_name'] = $s_firm_name;
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
					
                } else {
                    $data['result1'] = '';
                    $data['logo'] = $firm_logo;
                    $data['firm_name_nav'] = $firm_name1;
                    $data['selected_firm_name'] = $s_firm_name;
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
				
            } else {
                $data['result1'] = '';
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name1;
                $data['selected_firm_name'] = '';
                $data['selected_firm_name'] = '';
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        $this->load->view('hq_admin/view_customer', $data);
    }

    public function get_total_task_assignment() {
        $customer_id = base64_decode($this->input->post('hdn_cust_id'));

//     SELECT DISTINCT c_t_a_a.customer_id,c_t_a_a.task_assignment_id,c_t_a_a.task_assignment_description,c_t_a_a.task_assignment_name, c_t_a_a.task_id,t_h_a.task_name,c_t_a_a.completion_date, c_t_a_a.status, c_t_a_a.created_on
//                FROM customer_task_allotment_all  c_t_a_a
//                INNER JOIN  task_header_all  t_h_a
//                on c_t_a_a.task_id = t_h_a.task_id
//                INNER JOIN user_header_all  u_h_a
//                ON c_t_a_a.alloted_to_emp_id = u_h_a.user_id
//                WHERE c_t_a_a.customer_id='Cust_1001' AND c_t_a_a.sub_task_id = ''

        $this->db->distinct();
        $this->db->select('c_t_a_a.customer_id,c_h_a.customer_name,c_t_a_a.task_assignment_id,c_t_a_a.task_assignment_description,'
                . 'c_t_a_a.task_assignment_name, c_t_a_a.task_id,t_h_a.task_name,c_t_a_a.completion_date, c_t_a_a.status, c_t_a_a.created_on');
        $this->db->from('customer_task_allotment_all as c_t_a_a');
        $this->db->join('task_header_all as t_h_a', 'c_t_a_a.task_id = t_h_a.task_id');
        $this->db->join('customer_header_all as c_h_a', 'c_t_a_a.customer_id = c_h_a.customer_id');
        $this->db->join('user_header_all as u_h_a', 'c_t_a_a.alloted_to_emp_id = u_h_a.user_id');
        $this->db->where("c_t_a_a.customer_id = '$customer_id' AND c_t_a_a.sub_task_id = ''");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $total_task_assignment = $query->result();
            $response['result_total_task'] = $total_task_assignment;
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

    public function get_total_task() {

        $customer_id = ($this->input->post('total_task_cust_id'));

        $task_assign_id = ($this->input->post('task_assign_id'));

//        select c_t_a_a.id,c_t_a_a.customer_id,c_t_a_a.task_assignment_id,c_t_a_a.task_assignment_description,
//        c_t_a_a.task_assignment_name, c_t_a_a.task_id,t_h_a.task_name,c_t_a_a.sub_task_id,c_t_a_a.completion_date, c_t_a_a.status,c_h_a.firm_id,
//               c_t_a_a.created_on,c_t_a_a.alloted_to_emp_id,u_h_a.user_name
//        from customer_task_allotment_all  c_t_a_a
//        INNER join task_header_all  t_h_a
//        on c_t_a_a.task_id = t_h_a.task_id
//          INNER join user_header_all  u_h_a
//        on c_t_a_a.alloted_to_emp_id = u_h_a.user_id
//        where c_t_a_a.customer_id ='Cust_1003' AND task_assignment_id='$task_assign_id'

        $this->db->select('customer_id,customer_name');
        $this->db->from('customer_header_all');
        $this->db->where("customer_id='$customer_id'");
        $customer_query = $this->db->get();
        $rs_customer = $customer_query->row();
        $customer_id = $rs_customer->customer_id;
        $customer_name = $rs_customer->customer_name;

        $this->db->select('id,customer_id,task_assignment_id,task_assignment_description,task_assignment_name,'
                . 'task_id,sub_task_id ,completion_date, status,firm_id,'
                . ' created_on,alloted_to_emp_id');
        $this->db->from('customer_task_allotment_all');
        $this->db->where("customer_id='$customer_id' AND task_assignment_id='$task_assign_id'");
        $this->db->order_by("id", "DESC");
        $cust_task_allot_query = $this->db->get();

        if ($cust_task_allot_query->num_rows() > 0) {
            $rs_cust_task_allot = $cust_task_allot_query->result();
            foreach ($rs_cust_task_allot as $record) {
                $task_id = $record->task_id;
                $sub_task_id = $record->sub_task_id;
                $task_assignment_name = $record->task_assignment_name;
                $task_assignment_description = $record->task_assignment_description;
                $completion_date = $record->completion_date;
                $created_on = $record->created_on;
                $alloted_to_emp_id = $record->alloted_to_emp_id;
                $status = $record->status;
                $firm_id = $record->firm_id;
                $user_id = $record->alloted_to_emp_id;
                $id = $record->id;

                $this->db->select('user_name');
                $this->db->from('user_header_all');
                $this->db->where("user_id='$user_id'");
                $user_query = $this->db->get();
                if ($user_query->num_rows() > 0) {
                    $rs_user = $user_query->row();
                    $user_name = $rs_user->user_name;
                } else {
                    $user_name = "";
                }

                $this->db->select('task_name');
                $this->db->from('task_header_all');
                $this->db->where("task_id='$task_id'");
                $task_query = $this->db->get();
                if ($task_query->num_rows() > 0) {
                    $re_task = $task_query->row();
                    $task_name = $re_task->task_name;

                    $response['result_total_task'][] = ['task_id' => $task_id, 'sub_task_id' => $sub_task_id,
                        'id' => $id, 'task_name' => $task_name,
                        'customer_name' => $customer_name, 'task_assignment_name' => $task_assignment_name,
                        'task_assignment_description' => $task_assignment_description, 'completion_date' => $completion_date,
                        'created_on' => $created_on, 'alloted_to_emp_id' => $alloted_to_emp_id, 'status' => $status,
                        'firm_id' => $firm_id, 'user_name' => $user_name];


                    $sub_task_name = "";
                    $sub_task_name = "";
                    if ($sub_task_id != "") {
                        $result2 = $this->db->query("SELECT id, sub_task_name, sub_task_id, task_id FROM `sub_task_header_all` WHERE sub_task_id = '$sub_task_id' and task_id = '$task_id'");
                        if ($result2->num_rows() > 0) {
                            $record2 = $result2->row();
                            $sub_task_name = $record2->sub_task_name;
                            $sub_task_id = $record2->sub_task_id;
                            $task_id = $record2->task_id;
                            $id = $record2->id;
                        }
                        $response['sub_task_data'][] = ['task_id' => $task_id, 'sub_task_id' => $sub_task_id,
                            'sub_task_name' => $sub_task_name, 'id' => $id, 'task_name' => $task_name,
                            'customer_name' => $customer_name, 'task_assignment_name' => $task_assignment_name,
                            'task_assignment_description' => $task_assignment_description, 'completion_date' => $completion_date,
                            'created_on' => $created_on, 'alloted_to_emp_id' => $alloted_to_emp_id, 'status' => $status,
                            'firm_id' => $firm_id, 'user_name' => $user_name];
                    } else {

                    }
                } else {
                    $task_name = "";
                }
            }
        }

        $this->db->select('`id`, `customer_id`, `task_assignment_id`, `sub_task_name`, `custom_sub_task_id`,'
                . '`alloted_to`, `created_on`,`completion_date`,`status`, `created_by`,`firm_id`');
        $this->db->from('custom_sub_task_header_all');
        $this->db->where("customer_id='$customer_id' AND task_assignment_id='$task_assign_id'");
        $this->db->order_by("id", "DESC");
        $cust_sub_task_allot_query = $this->db->get();
        if ($cust_sub_task_allot_query->num_rows() > 0) {
            $rs_cust_sub_task = $cust_sub_task_allot_query->result();
            foreach ($rs_cust_sub_task as $custom_record) {
                $alloted_to = $custom_record->alloted_to;
                $id = $custom_record->id;
                $customer_id = $custom_record->customer_id;
                $task_assignment_id = $custom_record->task_assignment_id;
                $sub_task_name = $custom_record->sub_task_name;
                $custom_sub_task_id = $custom_record->custom_sub_task_id;
                $created_on = $custom_record->created_on;
                $completion_date = $custom_record->completion_date;
                $status = $custom_record->status;
                $created_by = $custom_record->created_by;
                $firm_id = $custom_record->firm_id;

                $this->db->select('user_name');
                $this->db->from('user_header_all');
                $this->db->where("user_id='$alloted_to'");

                $custom_user_query = $this->db->get();
                if ($custom_user_query->num_rows() > 0) {
                    $rs_custome_user = $custom_user_query->row();
                    $cust_user_name = $rs_custome_user->user_name;
                } else {
                    $cust_user_name = '';
                }
            }
            $response['custom_sub_task'][] = ['id' => $id, 'customer_id' => $customer_id, 'task_assignment_id' => $task_assignment_id,
                'sub_task_name' => $sub_task_name, 'custom_sub_task_id' => $custom_sub_task_id,
                'alloted_to' => $alloted_to, 'created_on' => $created_on, 'user_name' => $cust_user_name,
                'completion_date' => $completion_date, 'status' => $status,
                'created_by' => $created_by, 'firm_id' => $firm_id];
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

    public function get_complete_task() {
        $customer_id = $this->input->post('total_task_cust_id');
        $status = $this->input->post('ddl_status');
        $task_assign_id = $this->input->post('task_assign_id');

//           $this->db->distinct();
//            $this->db->select('c_t_a_a.id,c_t_a_a.customer_id,c_t_a_a.task_assignment_id,c_t_a_a.task_assignment_description,c_t_a_a.task_assignment_name,'
//                    . 'c_t_a_a.task_id,c_t_a_a.sub_task_id, c_t_a_a.alloted_to_emp_id, c_t_a_a.completion_date, c_t_a_a.status, c_t_a_a.created_on,'
//                    . 't_h_a.task_name,s_t_h_a.sub_task_name,u_h_a.user_name');
//            $this->db->from('customer_task_allotment_all as c_t_a_a');
//            $this->db->join('task_header_all as t_h_a', 'c_t_a_a.task_id = t_h_a.task_id');
//            $this->db->join('sub_task_header_all as s_t_h_a', 'c_t_a_a.sub_task_id = s_t_h_a.sub_task_id');
//            $this->db->join('user_header_all as u_h_a', 'c_t_a_a.alloted_to_emp_id = u_h_a.user_id');
//            $this->db->where("c_t_a_a.status = '$status' and c_t_a_a.customer_id = '$customer_id' and c_t_a_a.task_assignment_id = '$task_assign_id'");


        if ($status == '0') {
            $this->db->distinct();
            $this->db->select('customer_id,customer_name');
            $this->db->from('customer_header_all');
            $this->db->where("customer_id='$customer_id'");
            $customer_query = $this->db->get();
            $rs_customer = $customer_query->row();
            $customer_id = $rs_customer->customer_id;
            $customer_name = $rs_customer->customer_name;

            $this->db->distinct();
            $this->db->select('id,customer_id,task_assignment_id,task_assignment_description,task_assignment_name,'
                    . 'task_id,sub_task_id ,completion_date, status,firm_id,'
                    . ' created_on,alloted_to_emp_id');
            $this->db->from('customer_task_allotment_all');
            $this->db->where("sub_task_id != '' AND customer_id='$customer_id' AND task_assignment_id='$task_assign_id'");
            $this->db->order_by("id", "DESC");
            $cust_task_allot_query = $this->db->get();

            if ($cust_task_allot_query->num_rows() > 0) {
                $rs_cust_task_allot = $cust_task_allot_query->result();
                foreach ($rs_cust_task_allot as $record) {
                    $task_id = $record->task_id;
                    $sub_task_id = $record->sub_task_id;
                    $task_assignment_name = $record->task_assignment_name;
                    $task_assignment_description = $record->task_assignment_description;
                    $completion_date = $record->completion_date;
                    $created_on = $record->created_on;
                    $alloted_to_emp_id = $record->alloted_to_emp_id;
                    $status = $record->status;
                    $firm_id = $record->firm_id;
                    $user_id = $record->alloted_to_emp_id;
                    $id = $record->id;

                    $this->db->select('user_name');
                    $this->db->from('user_header_all');
                    $this->db->where("user_id='$user_id'");
                    $user_query = $this->db->get();
                    if ($user_query->num_rows() > 0) {
                        $rs_user = $user_query->row();
                        $user_name = $rs_user->user_name;
                    } else {
                        $user_name = "";
                    }

                    $this->db->select('task_name');
                    $this->db->from('task_header_all');
                    $this->db->where("task_id='$task_id'");
                    $task_query = $this->db->get();
                    if ($task_query->num_rows() > 0) {
                        $re_task = $task_query->row();
                        $task_name = $re_task->task_name;
                    } else {
                        $task_name = "";
                    }

                    $this->db->select('sub_task_name');
                    $this->db->from('sub_task_header_all');
                    $this->db->where("sub_task_id='$sub_task_id'");
                    $sub_task_query = $this->db->get();
                    if ($sub_task_query->num_rows() > 0) {
                        $re_sub_task = $sub_task_query->row();
                        $sub_task_name = $re_sub_task->sub_task_name;

                        $response['result_complete_task'][] = ['id' => $id, 'task_id' => $task_id, 'task_name' => $task_name,
                            'sub_task_id' => $sub_task_id, 'sub_task_id' => $sub_task_id, 'sub_task_name' => $sub_task_name,
                            'customer_name' => $customer_name, 'task_assignment_name' => $task_assignment_name,
                            'task_assignment_description' => $task_assignment_description, 'completion_date' => $completion_date,
                            'created_on' => $created_on, 'alloted_to_emp_id' => $alloted_to_emp_id, 'status' => $status,
                            'firm_id' => $firm_id, 'user_name' => $user_name];
                    } else {
                        $sub_task_name = "";
                    }

                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                }
            } else {
                $response['result'] = '';
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {

            $this->db->distinct();
            $this->db->select('customer_id,customer_name');
            $this->db->from('customer_header_all');
            $this->db->where("customer_id='$customer_id'");
            $customer_query = $this->db->get();
            $rs_customer = $customer_query->row();
            $customer_id = $rs_customer->customer_id;
            $customer_name = $rs_customer->customer_name;

            $this->db->distinct();
            $this->db->select('id,customer_id,task_assignment_id,task_assignment_description,task_assignment_name,'
                    . 'task_id,sub_task_id ,completion_date, status,firm_id,'
                    . ' created_on,alloted_to_emp_id');
            $this->db->from('customer_task_allotment_all');
            $this->db->where("sub_task_id != '' AND customer_id='$customer_id' AND task_assignment_id='$task_assign_id' AND status = '$status'");
            $this->db->order_by("id", "DESC");
            $cust_task_allot_query = $this->db->get();

            if ($cust_task_allot_query->num_rows() > 0) {
                $rs_cust_task_allot = $cust_task_allot_query->result();
                foreach ($rs_cust_task_allot as $record) {
                    $task_id = $record->task_id;
                    $sub_task_id = $record->sub_task_id;
                    $task_assignment_name = $record->task_assignment_name;
                    $task_assignment_description = $record->task_assignment_description;
                    $completion_date = $record->completion_date;
                    $created_on = $record->created_on;
                    $alloted_to_emp_id = $record->alloted_to_emp_id;
                    $status = $record->status;
                    $firm_id = $record->firm_id;
                    $user_id = $record->alloted_to_emp_id;
                    $id = $record->id;

                    $this->db->select('user_name');
                    $this->db->from('user_header_all');
                    $this->db->where("user_id='$user_id'");
                    $user_query = $this->db->get();
                    if ($user_query->num_rows() > 0) {
                        $rs_user = $user_query->row();
                        $user_name = $rs_user->user_name;
                    } else {
                        $user_name = "";
                    }

                    $this->db->select('task_name');
                    $this->db->from('task_header_all');
                    $this->db->where("task_id='$task_id'");
                    $task_query = $this->db->get();
                    if ($task_query->num_rows() > 0) {
                        $re_task = $task_query->row();
                        $task_name = $re_task->task_name;
                    } else {
                        $task_name = "";
                    }

                    $this->db->select('sub_task_name');
                    $this->db->from('sub_task_header_all');
                    $this->db->where("sub_task_id='$sub_task_id'");
                    $sub_task_query = $this->db->get();
                    if ($sub_task_query->num_rows() > 0) {
                        $re_sub_task = $sub_task_query->row();
                        $sub_task_name = $re_sub_task->sub_task_name;

                        $response['result_complete_task'][] = ['id' => $id, 'task_id' => $task_id, 'task_name' => $task_name,
                            'sub_task_id' => $sub_task_id, 'sub_task_id' => $sub_task_id, 'sub_task_name' => $sub_task_name,
                            'customer_name' => $customer_name, 'task_assignment_name' => $task_assignment_name,
                            'task_assignment_description' => $task_assignment_description, 'completion_date' => $completion_date,
                            'created_on' => $created_on, 'alloted_to_emp_id' => $alloted_to_emp_id, 'status' => $status,
                            'firm_id' => $firm_id, 'user_name' => $user_name];
                    } else {
                        $sub_task_name = "";
                    }
                }


                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['result'] = '';
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }

    public function get_ddl_customer() {
        $result = $this->firm_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
            $boss_id = $result['boss_id'];
        }
        $response = array('code' => -1, 'status' => false, 'message' => '');
        $this->db->select('*');
        $this->db->from('customer_header_all');
        $this->db->where("activity_status='1' and firm_id='$firm_id'");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = array('user_name' => array(), 'user_id' => array(), 'boss_id' => array());
            foreach ($query->result() as $row) {
                $data['customer_name'] = $row->customer_name;
                $data['customer_id'] = $row->customer_id;
                $data['firm_id'] = $row->firm_id;
                $data['boss_id'] = $row->boss_id;
                $response['customer_data'][] = ['customer_name' => $row->customer_name, 'customer_id' => $row->customer_id, 'firm_id' => $row->firm_id, 'boss_id' => $row->boss_id];
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

    function remove_due_date() {
        $cust_id = base64_decode($this->input->post('cust_id'));
        $firm_id = base64_decode($this->input->post('firm_id'));
        $due_date_id = $this->input->post('att_due_date');

        if ($due_date_id == '0') {
            $response['id'] = 'att_due_date';
            $response['error'] = 'Please select Due Date to attach';
            echo json_encode($response);
            exit();
        } else {
            $select = $this->input->post('select');
            if ($select == 'true') {
                $new_due_date_id = implode(',', $due_date_id);
            } else {
                $new_due_date_id = $due_date_id;
            }

            $result = $this->db->query("SELECT * FROM `customer_header_all` WHERE `customer_id`='$cust_id' AND firm_id='$firm_id'");
            if ($result->num_rows() !== 0) {
                $record = $result->row();
                $attached_due_date = $record->attached_due_date_id;
            }
            $attached_due_date = explode(',', $attached_due_date);
            $sort_due_date_id = explode(',', $new_due_date_id);
            $result = array_diff($attached_due_date, $sort_due_date_id);

            foreach ($sort_due_date_id as $value) {
                if (empty($result)) {
                    $result = '';
                    $data = array(
                        'status' => '5'
                    );
                    $this->db->where("customer_id='$cust_id' AND firm_id='$firm_id' AND due_date_id='$value'");
                    $query = $this->db->delete('customer_due_date_task_transaction_all');
                    if ($query == 1) {
                        $data = array(
                            'attached_due_date_id' => $result
                        );
                        $this->db->where("customer_id='$cust_id' AND firm_id='$firm_id'");
                        $qry_upd_cust = $this->db->update('customer_header_all', $data);

                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['message'] = 'false';
                        $response['code'] = 204;
                        $response['status'] = true;
                    }
                } else {
                    $result = implode(',', $result);
                    $data = array(
                        'status' => '5'
                    );
                    $this->db->where("customer_id='$cust_id' AND firm_id='$firm_id' AND due_date_id='$value'");
                    $query = $this->db->delete('customer_due_date_task_transaction_all');
                    if ($query == 1) {
                        $data = array(
                            'attached_due_date_id' => $result
                        );
                        $this->db->where("customer_id='$cust_id' AND firm_id='$firm_id'");
                        $qry_upd_cust = $this->db->update('customer_header_all', $data);

                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['message'] = 'false';
                        $response['code'] = 204;
                        $response['status'] = true;
                    }
                }
            }
        }
        echo json_encode($response);
    }

    function attched_due_date() {
        $cust_id = base64_decode($this->input->post('att_cust_id'));
        $firm_id = base64_decode($this->input->post('att_firm_id'));
        $due_date_id = $this->input->post('att_due_date');
        $select_attach = $this->input->post('select_attach');
        if ($due_date_id == '0') {
            $response['id'] = 'new_att_due_date';
            $response['error'] = 'Please select Due Date to attach';
            echo json_encode($response);
            exit();
        } else {
            if ($select_attach == 'true') {
                $new_due_date_id = implode(',', $due_date_id);
            } else {
                $new_due_date_id = $due_date_id;
            }

            $result = $this->customer_model->customer_attache_due_date($new_due_date_id, $cust_id, $firm_id);

            if ($result === '1') {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'false';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }

    function get_customer_due_date() {
        $cust_id = base64_decode($this->input->post('cust_id'));
        $result = $this->db->query("SELECT * FROM `customer_header_all` WHERE `customer_id`='$cust_id'");
        if ($result->num_rows() !== 0) {
            $record = $result->row();
            $attached_due_date = $record->attached_due_date_id;
        }
        $attached_due_date = explode(',', $attached_due_date);

        $data = array('due_date_id' => array(), 'due_date_name' => array());
        foreach ($attached_due_date as $value) {
            $result = $this->db->query("SELECT * FROM `due_date_header_all` WHERE `due_date_id`='$value'");
            if ($result->num_rows() !== 0) {
                $record = $result->row();
                $data['due_date_id'] = $record->due_date_id;
                $data['due_date_name'] = $record->due_date_name;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                $response['cust_due_date_data'][] = ['due_date_id' => $record->due_date_id, 'due_date_name' => $record->due_date_name];
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }

    public function change_customer_activity_status() {
        $cust_id = base64_decode($this->input->post('cust_id'));
        $status = $this->input->post('status');
        $result = $this->customer_model->customer_activity_status($cust_id, $status);
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

    public function edit_customer_details_view($cust_id) {
        $data['prev_title'] = "Edit Customer";
        $data['page_title'] = "Edit Customer";
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
        $record = $this->customer_model->get_customer_details($cust_id);
        $record2 = $this->customer_model->get_customer_contact_details($cust_id);


        if ($record !== false && $record2 !== false) {
            $data['record'] = $record->row();
            $data['contact_record'] = $record2->result();
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        $this->load->view('hq_admin/edit_customer', $data);
    }

    public function edit_customer() {
        $customer_id = $this->input->post('cust_id');
        $customer_name = $this->input->post('customer_name');
        $customer_address = $this->input->post('customer_address');
        $customer_city = $this->input->post('customer_city');
        $customer_state = $this->input->post('customer_state');
        $customer_country = $this->input->post('customer_country');
        $customer_no = $this->input->post('customer_contact_number');
        $pincode = $this->input->post('pincode');
        $pan_no = $this->input->post('pan_no');
        $contact_person_name = $this->input->post('contact_person_name');
        $contact_person_no = $this->input->post('contact_person_number');
        $contact_person_email = $this->input->post('contact_person_email_id');
        $director_name = $this->input->post('director_name_din');
        $user_id = $this->input->post('user_id');
        $cust_count = $this->input->post('cust_count');
        $modified_on = date('y-m-d h:i:s');
        $modified_by = $this->session->userdata('login_session');
        $customer_no = $this->input->post('customer_contact_number');
        $customer_email = $this->input->post('customer_email_id');
        $firm_id = $this->input->post('firm_id');


        if (empty(trim($customer_name))) {
            $response['id'] = 'customer_name';
            $response['error'] = 'Enter Proper Name';
        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $customer_name)) {
            $response['id'] = 'customer_name';
            $response['error'] = 'Enter  Customer Name';
        } elseif (empty($customer_no)) {
            $response['id'] = 'customer_contact_number';
            $response['error'] = 'Enter Customer Contact No.';
        } elseif (!preg_match("/^\d{10}$/", $customer_no)) { // phone number is valid
            $response['id'] = 'customer_contact_number';
            $response['error'] = 'Enter Proper Mobile No.';
        } elseif (empty(trim($director_name))) {
            $response['id'] = 'director_name_din';
            $response['error'] = 'Enter Director Name';
        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $director_name)) {
            $response['id'] = 'director_name_din';
            $response['error'] = 'Enter  Customer Director Name';
        } elseif (empty(trim($customer_address))) {
            $response['id'] = 'customer_address';
            $response['error'] = 'Enter Customer Address';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $customer_address)) {
            $response['id'] = 'customer_address';
            $response['error'] = 'Enter Proper Address';
        } elseif (empty($pincode)) {
            $response['id'] = 'pincode';
            $response['error'] = 'Enter Pincode';
        } elseif (empty(trim($customer_city))) {
            $response['id'] = 'customer_city';
            $response['error'] = 'Enter Customer City';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $customer_city)) {
            $response['id'] = 'customer_city';
            $response['error'] = 'Enter Customer City';
        } elseif (empty(trim($customer_state))) {
            $response['id'] = 'customer_state';
            $response['error'] = 'Enter Customer State';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $customer_state)) {
            $response['id'] = 'customer_state';
            $response['error'] = 'Enter Customer State';
        } elseif (empty(trim($customer_country))) {
            $response['id'] = 'customer_country';
            $response['error'] = 'Enter Customer Country';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $customer_country)) {
            $response['id'] = 'customer_country';
            $response['error'] = 'Enter Customer Country';
        } elseif (!preg_match("/^([-a-z0-9_ ])+$/i", $pincode)) {
            $response['id'] = 'pincode';
            $response['error'] = 'Enter Proper Pincode';
        } else {

            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $boss_id = $result['boss_id'];
            }


            if ($cust_count !== "") {
// variable declaration
                $a = "";
                $b = "";
                $c = "";
                $d = "";
                $e = "";

                $query = $this->customer_model->check_customer_employee_details($customer_id);
                if ($query == true) {
                    for ($i = 1; $i <= $cust_count; $i++) {
                        $e_name = $this->input->post('employee_name' . $i);
                        $e_mobile_no = $this->input->post('employee_mobile_number' . $i);
                        $e_email_id = $this->input->post('employee_email_id' . $i);
                        $e_designation = $this->input->post('employee_designation' . $i);
                        $e_visible = $this->input->post('employee_visible_to' . $i);
                        $default_contact = $this->input->post('employee_default');


                        if ((empty(trim($e_name)))) {
                            $response['error'] = "Please enter Proper Name";
                            $response['id'] = 'employee_name' . $i;
                            echo json_encode($response);
                            exit();
                        } elseif ((empty($e_mobile_no))) {
                            $response['error'] = "Please enter Proper Contact No";
                            $response['id'] = 'employee_mobile_number' . $i;
                            echo json_encode($response);
                            exit();
                        } elseif (!preg_match("/^\d{10}$/", $e_mobile_no)) { // phone number is valid
                            $response['id'] = 'employee_mobile_number' . $i;
                            $response['error'] = 'Enter Proper Mobile No.';
                            echo json_encode($response);
                            exit();
                        } elseif ($e_mobile_no == $customer_no) {
                            $response['error'] = "Please provide another Contact no";
                            $response['id'] = 'employee_mobile_number' . $i;
                            echo json_encode($response);
                            exit();
                        } elseif ((empty($e_email_id))) {
                            $response['error'] = "Please enter Email_id";
                            $response['id'] = 'employee_email_id' . $i;
                            echo json_encode($response);
                            exit();
                        } elseif (!filter_var($e_email_id, FILTER_VALIDATE_EMAIL)) {
                            $response['error'] = "Invalid email format";
                            $response['id'] = 'employee_email_id' . $i;
                            echo json_encode($response);
                            exit();
                        } elseif ($e_email_id == $customer_email) {
                            $response['error'] = "Please provide another email id";
                            $response['id'] = 'employee_email_id' . $i;
                            echo json_encode($response);
                            exit();
                        } elseif (empty(trim($e_designation))) {
                            $response['error'] = "Please enter Designation";
                            $response['id'] = 'employee_designation' . $i;
                            echo json_encode($response);
                            exit();
                        } elseif (empty(trim($e_visible))) {
                            $response['error'] = "Please enter Designation";
                            $response['id'] = 'employee_visible_to' . $i;
                            echo json_encode($response);
                            exit();
                        }

                        if ($e_visible == '1') {
                            $e_visible = '1';
                        } else {
                            $e_visible = '2';
                        }

                        if ($default_contact == $i) {
                            $default_contact = '1';
                        } else {
                            $default_contact = '2';
                        }

                        $a_data = array(
                            'customer_id' => $customer_id,
                            'customer_employee_id' => $this->getCustEmpId(),
                            'employee_name' => $e_name,
                            'employee_mobile_number' => $e_mobile_no,
                            'employee_email_id' => $e_email_id,
                            'employee_designation' => $e_designation,
                            'employee_visible_to' => $e_visible,
                            'default_contact' => $default_contact
                        );

                        $q_cust_emp = $this->db->insert('customer_employee_detail_all', $a_data);
                    }
                }
            }


            $data = array(
                'customer_id' => $customer_id,
                'firm_id' => $firm_id,
                'boss_id' => $boss_id,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'customer_name' => $customer_name,
                'customer_address' => $customer_address,
                'customer_city' => $customer_city,
                'customer_state' => $customer_state,
                'customer_country' => $customer_country,
                'pincode' => $pincode,
                'pan_no' => $pan_no,
                'director_name_din' => $director_name,
            );

            $data_gst = array(
                'customer_id' => $customer_id,
                'firm_id' => $firm_id,
                'customer_name' => $customer_name,
                'customer_address' => $customer_address,
                'customer_city' => $customer_city,
                'customer_state' => $customer_state,
                'customer_country' => $customer_country,
                'pincode' => $pincode,
                'pan_no' => $pan_no,
            );


            $record1 = $this->customer_model->update_customer_details_gst($data_gst, $customer_id);
            $record = $this->customer_model->update_customer_details($data, $customer_id);
            if ($record == '1') {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }

    public function get_cust_task_data() {
        $cust_task_id = $this->input->post('customer_task_id');
//        echo "select c_t_a_a.id, c_t_a_a.customer_id,c_t_a_a.task_assignment_id,c_t_a_a.task_assignment_description,"
//        . "c_t_a_a.task_assignment_name,c_t_a_a.task_id,c_t_a_a.sub_task_id,"
//        . " c_t_a_a.alloted_to_emp_id, c_t_a_a.completion_date, c_t_a_a.status,"
//        . " c_t_a_a.created_on,t_h_a.task_name,s_t_h_a.sub_task_name,u_h_a.user_name"
//        . "from customer_task_allotment_all as c_t_a_a "
//        . "INNER join task_header_all as t_h_a on c_t_a_a.task_id = t_h_a.task_id "
//        . "INNER  join sub_task_header_all as s_t_h_a on c_t_a_a.sub_task_id = s_t_h_a.sub_task_id "
//        . "INNER join user_header_all as u_h_a on c_t_a_a.alloted_to_emp_id = u_h_a.user_id "
//        . "where c_t_a_a.id='$cust_task_id'";


        $this->db->select('c_t_a_a.id,c_t_a_a.customer_id,c_t_a_a.task_assignment_id,c_t_a_a.task_assignment_description,c_t_a_a.task_assignment_name,'
                . 'c_t_a_a.task_id,c_t_a_a.sub_task_id, c_t_a_a.alloted_to_emp_id, c_t_a_a.completion_date, c_t_a_a.status, c_t_a_a.created_on,'
                . 't_h_a.task_name,s_t_h_a.sub_task_name,u_h_a.user_name ,u_h_a.user_id');
        $this->db->from('customer_task_allotment_all as c_t_a_a');
        $this->db->join('task_header_all as t_h_a', 'c_t_a_a.task_id = t_h_a.task_id');
        $this->db->join('sub_task_header_all as s_t_h_a', 'c_t_a_a.sub_task_id = s_t_h_a.sub_task_id');
        $this->db->join('user_header_all as u_h_a', 'c_t_a_a.alloted_to_emp_id = u_h_a.user_id');
        $this->db->where('c_t_a_a.id', $cust_task_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $record = $query->row();
//            var_dump($record);
            $response['cust_task'] = $record;
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

    //supriya code for popup


    public function get_custom_task_data() {
//    select c_s_t_h_a.`id`, c_s_t_h_a.`customer_id`, c_s_t_h_a.`task_assignment_id`,c_s_t_h_a. `sub_task_name`,c_s_t_h_a. `custom_sub_task_id`,
//                c_s_t_h_a.`alloted_to`, c_s_t_h_a.`created_on`, c_s_t_h_a.`completion_date`, c_s_t_h_a.`status`, u_h_a.user_name,
//                c_s_t_h_a.`created_by`
//         from custom_sub_task_header_all as c_s_t_h_a
//         join customer_task_allotment_all as c_t_a_a
//		 on c_s_t_h_a.task_assignment_id = c_t_a_a.task_assignment_id
//         join user_header_all as u_h_a
//		on c_t_a_a.alloted_to_emp_id = u_h_a.user_id
//         where c_s_t_h_a.id' ='2'

        $cust_task_id = $this->input->post('customer_task_id');
        $this->db->select('c_s_t_h_a.`id`, c_s_t_h_a.`customer_id`, c_s_t_h_a.`task_assignment_id`,c_s_t_h_a. `sub_task_name`,c_s_t_h_a. `custom_sub_task_id`, '
                . 'c_s_t_h_a.`alloted_to`, c_s_t_h_a.`created_on`, c_s_t_h_a.`completion_date`, c_s_t_h_a.`status`, u_h_a.user_name,'
                . 'c_s_t_h_a.`created_by`');
        $this->db->from('custom_sub_task_header_all as c_s_t_h_a');
        $this->db->join('customer_task_allotment_all as c_t_a_a', 'c_s_t_h_a.task_assignment_id = c_t_a_a.task_assignment_id');
        $this->db->join('user_header_all as u_h_a', 'c_s_t_h_a.alloted_to = u_h_a.user_id');
        $this->db->where('c_s_t_h_a.id', $cust_task_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $record = $query->row();
//            var_dump($record);
            $response['custom_sub_task'] = $record;
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

    public function GetCustomerDueDate() {
        $due_date_cust_id = base64_decode($this->input->post('customer_id'));
        $firm_id = base64_decode($this->input->post('firm_id'));

        $this->db->distinct();
        $this->db->select('c_d_d_t_t_a.due_date_id,d_d_h_a.due_date_name,d_d_h_a.duration,c_d_d_t_t_a.customer_id,c_h_a.customer_name,c_d_d_t_t_a.firm_id');
        $this->db->from('customer_due_date_task_transaction_all as c_d_d_t_t_a');
        $this->db->join('due_date_header_all as d_d_h_a', 'c_d_d_t_t_a.due_date_id = d_d_h_a.due_date_id');
        $this->db->join('customer_header_all as c_h_a', 'c_d_d_t_t_a.customer_id = c_h_a.customer_id');
        $this->db->where("c_d_d_t_t_a.customer_id='$due_date_cust_id' and c_d_d_t_t_a.firm_id='$firm_id'");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $record = $query->result();
            foreach ($record as $row) {
                $rs_due_date_id = $row->due_date_id;
                $this->db->distinct();
                $this->db->select('c_d_d_t_t_a.due_date_task_id,d_d_t_h_a.due_date_task_name,c_d_d_t_t_a.firm_id');
                $this->db->from('customer_due_date_task_transaction_all as c_d_d_t_t_a');
                $this->db->join('due_date_task_header_all as d_d_t_h_a', 'c_d_d_t_t_a.due_date_task_id = d_d_t_h_a.due_date_task_id');
                $this->db->where("c_d_d_t_t_a.customer_id = '$due_date_cust_id' and c_d_d_t_t_a.due_date_id='$rs_due_date_id' and c_d_d_t_t_a.firm_id='$firm_id'");

                $qry_due_task = $this->db->get();
                if ($qry_due_task->num_rows() > 0) {
                    $due_task_count = $qry_due_task->result();
                    $due_date_task_count = $qry_due_task->num_rows();
                } else {
                    $due_date_task_count = 0;
                }
                $response['due_date_data'][] = ['due_date_id' => $rs_due_date_id, 'due_date_name' => $row->due_date_name, 'firm_id' => $row->firm_id, 'duration' => $row->duration, 'customer_id' => $row->customer_id, 'customer_name' => $row->customer_name, 'due_date_task_count' => $due_date_task_count];
            }

            $response['due_date_record'] = $response['due_date_data'];
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

    public function GetCustomerDueDateTask() {
        $due_date_cust_id = ($this->input->post('customer_id'));
        $due_date_id = ($this->input->post('due_date_id'));
        $firm_id = ($this->input->post('firm_id'));

//      select c_d_d_t_t_a.due_date_task_id,d_d_t_h_a.due_date_task_name,d_d_t_h_a.last_date_submission,d_d_t_h_a.start_date,
//              d_d_t_h_a.extended_date,d_d_t_h_a.penalty_after,d_d_t_h_a.alloted_to,c_d_d_t_t_a.status,c_d_d_t_t_a.filled_by,
//              c_d_d_t_t_a.alloted_to,u_h_a.user_name FROM customer_due_date_task_transaction_all c_d_d_t_t_a JOIN due_date_task_header_all
//              as d_d_t_h_a on c_d_d_t_t_a.due_date_task_id = d_d_t_h_a.due_date_task_id JOIN user_header_all
//              as u_h_a on c_d_d_t_t_a.alloted_to = u_h_a.user_id
//              WHERE c_d_d_t_t_a.customer_id = 'Cust_1001' and
//              c_d_d_t_t_a.due_date_id='Due_973' and c_d_d_t_t_a.firm_id='Firm_1003' and and c_d_d_t_t_a.status !='5';


        $this->db->distinct();
        $this->db->select('customer_id,due_date_task_id,status,filled_by,alloted_to');
        $this->db->from('customer_due_date_task_transaction_all');
        $this->db->where("customer_id = '$due_date_cust_id' and due_date_id = '$due_date_id' and firm_id='$firm_id' and status!='5'");
        $qry_c_due_task = $this->db->get();
        if ($qry_c_due_task->num_rows() > 0) {
            $rs_c_due_task = $qry_c_due_task->result();
            foreach ($rs_c_due_task as $record) {
                $due_date_task_id = $record->due_date_task_id;
                $customer_id = $record->customer_id;


                $this->db->select('due_date_task_name,last_date_submission,start_date,'
                        . 'extended_date,penalty_after,');
                $this->db->from('due_date_task_header_all');
                $this->db->where("due_date_task_id = '$due_date_task_id'");
                $qry_due_task = $this->db->get();
                if ($qry_due_task->num_rows() > 0) {
                    $rs_due_task = $qry_due_task->row();
                    $due_date_task_name = $rs_due_task->due_date_task_name;
                    $last_date_submission = $rs_due_task->last_date_submission;
                    $start_date = $rs_due_task->start_date;
                    $extended_date = $rs_due_task->extended_date;
                    $penalty_after = $rs_due_task->penalty_after;
                    $status = $record->status;
                    $filled_by = $record->filled_by;
                    $alloted_to = $record->alloted_to;

                    $this->db->select('user_name');
                    $this->db->from('user_header_all');
                    $this->db->where("user_id = '$alloted_to'");
                    $query_user = $this->db->get();
                    if ($query_user->num_rows() > 0) {
                        $rs_user = $query_user->row();
                        $allote_user_name = $rs_user->user_name;
                    } else {
                        $allote_user_name = '';
                    }

                    $this->db->select('user_name');
                    $this->db->from('user_header_all');
                    $this->db->where("user_id = '$filled_by'");
                    $query_user_filled = $this->db->get();
                    if ($query_user_filled->num_rows() > 0) {
                        $rs_user_filled = $query_user_filled->row();
                        $filled_by_user_name = $rs_user_filled->user_name;
                    } else {
                        $filled_by_user_name = '';
                    }
                    $response['due_date_task_record'] [] = ['due_date_task_id' => $due_date_task_id, 'customer_id' => $customer_id,
                        'due_date_task_name' => $due_date_task_name, 'last_date_submission' => $last_date_submission,
                        'start_date' => $start_date, 'extended_date' => $extended_date,
                        'penalty_after' => $penalty_after, 'status' => $status,
                        'filled_by' => $filled_by, 'alloted_to' => $alloted_to, 'allote_user_name' => $allote_user_name, 'filled_by_user_name' => $filled_by_user_name];
                } else {
                    $response['due_date_task_record'] [] = '';
                }
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['due_date_task_record'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function CloseCustomerSubTask() {
        $id = base64_decode($this->input->post('id'));
        $firm_id = base64_decode($this->input->post('firm_id'));
        $modified_on = date('y-m-d h:i:s');
        $modified_by = $this->session->userdata('login_session');



        $data = array(
            'status' => '4',
            'modified_by' => $modified_by,
            'modified_on' => $modified_on,
            'firm_id' => $firm_id,
        );
        $this->db->where("id = '$id'");
        $query = $this->db->update('customer_task_allotment_all', $data);
        if ($this->db->affected_rows() > 0) {
            $response['message'] = 'success';
            $response ['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response
        );
    }

    public function CloseCustomerCustomSubTask() {
        $id = base64_decode($this->input->post('id'));
        $firm_id = base64_decode($this->input->post('firm_id'));
        $modified_on = date('y-m-d h:i:s');
        $modified_by = $this->session->userdata('login_session');

        $data = array(
            'status' => '4',
            'modified_by' => $modified_by,
            'modified_on' => $modified_on,
            'firm_id' => $firm_id,
        );
        $this->db->where("id = '$id'");
        $query = $this->db->update('custom_sub_task_header_all', $data);
        if ($this->db->affected_rows() > 0) {
            $response['message'] = 'success';
            $response ['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response
        );
    }

    public function edit_task_allotement() {
        $cust_task_id = $this->input->post('cust_task_id');
        $alloted_to_emp = $this->input->post('ddl_alloted_to');
        $completion_date = $this->input->post('edit_completion_date');
        $modified_on = date('y-m-d h:i:s');
        $status = '1';

        if (empty($alloted_to_emp)) {
            $data['reason'] = "Please select Employee";
            echo json_encode(array('error' => $data['reason']));
        } else if (empty($completion_date)) {
            $data['reason'] = "Enter completion Date";
            echo json_encode(array('error' => $data['reason']));
        } else {

            $data = array(
                'alloted_to_emp_id' => $alloted_to_emp,
                'completion_date' => $completion_date,
                'modified_on' => $modified_on,
//                'modified_by' => $modified_by,
                'status' => $status
            );

            $result = $this->task_allote_model->edit_customer_task_allote($data, $cust_task_id);
            if ($result == true) {
                echo json_encode(array('status' => true));
            } else {
                echo json_encode(array('status' => false));
            }
        }
    }

    public function edit_custom_task_allotement() {
        $cust_task_id = $this->input->post('c_cust_task_id');
        $alloted_to_emp = $this->input->post('c_ddl_alloted_to');
        $completion_date = $this->input->post('c_edit_completion_date');
        $modified_on = date('y-m-d h:i:s');
        $status = '1';

        if (empty($alloted_to_emp)) {
            $data['reason'] = "Please select Employee";
            echo json_encode(array('error' => $data[
                'reason']));
        } else if (empty($completion_date)) {
            $data['reason'] = "Enter completion Date";
            echo json_encode(array('error' => $data['reason']));
        } else {

            $data = array(
                'alloted_to' => $alloted_to_emp,
                'completion_date' => $completion_date,
                'modified_on' => $modified_on,
//                'modified_by' => $modified_by,
                'status' => $status
            );

            $result = $this->task_allote_model->edit_customer_custom_sub_task_allote($data, $cust_task_id);
            if ($result == true) {
                echo json_encode(array('status' => true));
            } else {
                echo json_encode(array('status' => false));
            }
        }
    }

    public function ddl_alloted_to() {
        $firm_id = $this->input->post('firm_id');

        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where('firm_id', $firm_id);
        $query1 = $this->db->get();
        if ($query1->num_rows() > 0) {
            $result_hq_cust = $query1->row();
            $firm_id = $result_hq_cust->firm_id;
            $response = array('code' => -1, 'status' => false, 'message' => '');
            $array = array('is_employee' => '1', 'firm_id' => $firm_id);
            $query = $this->db->select('*')->from('user_header_all')->where($array)->get();
            if ($this->db->affected_rows() > 0) {
                $data = array('user_name' => array(), 'user_id' => array());
                foreach ($query->result() as $row) {
                    $data['user_name'] = $row->user_name;
                    $data['user_id'] = $row->user_id;
                    $response[
                            'user_data'][] = ['user_name' => $row->user_name, 'user_id' => $row->user_id];
                }
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response
        );
    }

    public function getSendReminderData() {
        $customer_id = $this->input->post('customer_id');
        $this->db->select('c_e_d_a.`id`, c_e_d_a.`customer_id`, c_e_d_a.`customer_employee_id`, c_e_d_a.`employee_name`, c_e_d_a.`employee_mobile_number`, '
                . 'c_e_d_a.`employee_email_id`, c_e_d_a.`employee_designation`, c_e_d_a.`employee_visible_to`,'
                . ' c_e_d_a.`default_contact`, c_e_d_a.`sms_permitted`, c_e_d_a.`email_permitted`,c_h_a.customer_name');
        $this->db->from('customer_employee_detail_all as c_e_d_a');
        $this->db->join('customer_header_all as c_h_a', 'c_e_d_a.customer_id = c_h_a.customer_id');
        $this->db->where("c_e_d_a.customer_id = '$customer_id'");
        $customer_emp = $this->db->get();
        if ($customer_emp->num_rows() > 0) {
            foreach ($customer_emp->result() as $row) {
                $customer_id = $row->customer_id;
                $customer_employee_id = $row->customer_employee_id;
                $employee_name = $row->employee_name;
                $customer_name = $row->customer_name;
                $response['customer_emp_data'][] = ['customer_id' => $customer_id, 'customer_employee_id' => $customer_employee_id, 'employee_name' => $employee_name, 'customer_name' => $customer_name];
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

    public function getCustEmpData() {
        $cust_emp_id = $this->input->post('cust_emp_id');
        $this->db->select(' c_e_d_a.`customer_id`, c_e_d_a.`customer_employee_id`, c_e_d_a.`employee_name`, c_e_d_a.`employee_mobile_number`, '
                . 'c_e_d_a.`employee_email_id`, c_e_d_a.`employee_designation`, c_e_d_a.`employee_visible_to`,'
                . ' c_e_d_a.`default_contact`, c_e_d_a.`sms_permitted`, c_e_d_a.`email_permitted`,'
                . 'c_h_a.customer_name,c_h_a.firm_id,c_h_a.attached_due_date_id');
        $this->db->from('customer_employee_detail_all as c_e_d_a');
        $this->db->join('customer_header_all as c_h_a', 'c_e_d_a.customer_id = c_h_a.customer_id');
        $this->db->where("c_e_d_a.customer_employee_id = '$cust_emp_id'");
        $customer_emp = $this->db->get();
        if ($customer_emp->num_rows() > 0) {
            $rs_customer_emp = $customer_emp->row();
            $response['rs_customer_emp'] = $rs_customer_emp;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response
        );
    }

    public function SendbulkReminder() {
        $customer_id_count = $this->input->post('bulkreminder_customer'); //customer id in array
        $duedate_id = $this->input->post('selected_ddtask_value'); //duedate id
        $ddtask_name = $this->input->post('ddtask_name'); // duedate name
        $subject_sms = $this->input->post('subject_sms'); // subject for mail
        $message_sms = $this->input->post('message_sms'); // message for email/sms
        $bulk_sms = $this->input->post('bulk_sms'); // to check whether sms is checked
        $bulk_email = $this->input->post('bulk_email'); // to check whether email is checked
        print_r($_POST);
        var_dump($customer_id_count);
        echo $count_array = count($customer_id_count);
        exit();
        for ($j = 0; $j < $count_array; $j++) {
            $customer_id = $customer_id_count[$j];
            $this->db->select('*');
            $this->db->from('customer_employee_detail_all');
            $this->db->where("default_contact = '1' AND customer_id = '$customer_id'");
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $customer_contact_details = $query->row();
                $employee_name = $customer_contact_details->employee_name;
                $contact_no = $customer_contact_details->employee_mobile_number;
                $employee_email_id = $customer_contact_details->employee_email_id;
                $auth_key = "178904AVN94GK259e5e87b";
                $send_sms = $this->email_sending_model->SendReminderSms($auth_key, $contact_no, $employee_name, $message_sms);
//                $send_email = $this->email_sending_model->SendReminderEmail($employee_email_id, $employee_name, $subject_sms, $message_sms);

                if ($send_sms == true) {
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'nodata';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            } else {
                $response['message'] = 'nodata';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }

    public function SendReminder() {

        $customer_id = $this->input->post('s_r_customer_id');
        $due_task_name = $this->input->post('s_r_due_task_name');
        $customer_name = $this->input->post('s_r_customer_name');
        $customer_emp_id = $this->input->post('s_r_ddl_cust_emp');
        $email_id = $this->input->post('s_r_email_id');
        $customer_no = $this->input->post('s_r_contact_no');
        $subject = $this->input->post('s_r_subject');
        $message = $this->input->post('s_r_message');
        $check_list = $this->input->post('check_list');
        $firm_id = $this->input->post('s_r_firm_id');
        $attached_due_date_id = $this->input->post('s_r_attach_due_date_id');
        $check_list = $_POST['check_list'];

        if ($check_list == '0') {
            $response['id'] = 'check_list_email';
            $response['error'] = 'Please Select checkbox ';
            echo json_encode($response);
            exit();
        } else if (($check_list == 'SMS') && ($check_list == 'email')) {
            if (empty($customer_emp_id)) {
                $response['id'] = 's_r_ddl_cust_emp';
                $response['error'] = 'Please select contact person';
                echo json_encode($response);
                exit();
            } else if (empty($customer_no)) {
                $response['id'] = 's_r_contact_no';
                $response['error'] = 'Enter Customer Contact No.';
                echo json_encode($response);
                exit();
            } elseif (!preg_match("/^\d{10}$/", $customer_no)) { // phone number is valid
                $response['id'] = 's_r_contact_no';
                $response['error'] = 'Enter Proper Mobile No.';
                echo json_encode($response);
                exit();
            } else if (empty(trim($subject))) {
                $response['id'] = 's_r_subject';
                $response['error'] = "Please enter subject";
                echo json_encode($response);
                exit();
            } else if (empty(trim($message))) {
                $response['id'] = 's_r_message';
                $response['error'] = "Please enter message";
                echo json_encode($response);
                exit();
            } else {
                $auth_key = "178904AVN94GK259e5e87b";
                $send_sms = $this->email_sending_model->SendReminderSms($customer_id, $customer_name, $contact_no, $message);
                $send_email = $this->email_sending_model->SendReminderEmail($email_id, $customer_name, $subject, $message);

                if ($send_email == true) {
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'nodata';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
        } else if ($check_list == 'SMS') {
            if (empty($customer_emp_id)) {
                $response['id'] = 's_r_ddl_cust_emp';
                $response['error'] = 'Please select contact person';
                echo json_encode($response);
                exit();
            } else if (empty($customer_no)) {
                $response['id'] = 's_r_contact_no';
                $response['error'] = 'Enter Customer Contact No.';
                echo json_encode($response);
                exit();
            } elseif (!preg_match("/^\d{10}$/", $customer_no)) { // phone number is valid
                $response['id'] = 's_r_contact_no';
                $response['error'] = 'Enter Proper Mobile No.';
                echo json_encode($response);
                exit();
            } else if (empty(trim($message))) {
                $response['id'] = 's_r_message';
                $response['error'] = "Please enter message";
                echo json_encode($response);
                exit();
            } else {
                $auth_key = "178904AVN94GK259e5e87b";
                $send_sms = $this->email_sending_model->SendReminderSms($auth_key, $customer_no, $customer_name, $message);
                if ($send_sms == 0) {
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'nodata';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
        } else if ($check_list == 'email') {
            if (empty($customer_emp_id)) {
                $response['id'] = 's_r_ddl_cust_emp';
                $response['error'] = 'Please select contact person';
                echo json_encode($response);
                exit();
            } else if (empty(trim($subject))) {
                $response['id'] = 's_r_subject';
                $response['error'] = "Please enter subject";
                echo json_encode($response);
                exit();
            } else if (empty(trim($message))) {
                $response['id'] = 's_r_message';
                $response['error'] = "Please enter message";
                echo json_encode($response);
                exit();
            } else {
                $send_email = $this->email_sending_model->SendReminderEmail($email_id, $customer_name, $subject, $message);

                if ($send_email == true) {
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'nodata';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
        }
        echo json_encode($response);
    }

    public function TransferTaskAssignToArchive() {
        $customer_id = $this->input->post('customer_id');
        $task_assign_id = $this->input->post('task_assign_id');
        $modified_on = date('y-m-d h:i:s');
        $modified_by = $this->session->userdata('login_session');
        $data = array(
            'status' => '4',
            'modified_on' => $modified_on,
            'modified_by' => $modified_by
        );
        $this->db->where("customer_id = '$customer_id' AND task_assignment_id = '$task_assign_id'");
        $query = $this->db->update('customer_task_allotment_all', $data);
        if ($query == 1) {
            $response['message'] = 'success';
            $response ['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data';
            $response['code'] = 204;
            $response['status'] = false;
        }
    }

    public function get_all_assignment_data() {
        $customer_id = base64_decode($this->input->post('customer_id'));
        $firm_id = base64_decode($this->input->post('firm_id'));

        $this->db->distinct();
        $this->db->select('c_t_a_a.customer_id,c_h_a.customer_name,c_t_a_a.task_assignment_id,c_t_a_a.task_assignment_description,'
                . 'c_t_a_a.task_assignment_name, c_t_a_a.task_id,t_h_a.task_name,c_t_a_a.completion_date, c_t_a_a.status, c_t_a_a.created_on');
        $this->db->from('customer_task_allotment_all as c_t_a_a');
        $this->db->join('task_header_all as t_h_a', 'c_t_a_a.task_id = t_h_a.task_id');
        $this->db->join('customer_header_all as c_h_a', 'c_t_a_a.customer_id = c_h_a.customer_id');
        $this->db->join('user_header_all as u_h_a', 'c_t_a_a.alloted_to_emp_id = u_h_a.user_id');
        $this->db->where("c_t_a_a.customer_id = '$customer_id' AND c_t_a_a.sub_task_id = '' AND c_t_a_a.status !='4'");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $total_task_assignment = $query->result();
            $response['get_result_total_task'] = $total_task_assignment;
        } else {
            $response['get_result_total_task'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        $this->db->distinct();
        $this->db->select('c_d_d_t_t_a.due_date_id,c_d_d_t_t_a.due_date_task_id,d_d_t_h_a.due_date_task_name,c_d_d_t_t_a.firm_id');
        $this->db->from('customer_due_date_task_transaction_all as c_d_d_t_t_a');
        $this->db->join('due_date_task_header_all as d_d_t_h_a', 'c_d_d_t_t_a.due_date_task_id = d_d_t_h_a.due_date_task_id');
        $this->db->where("c_d_d_t_t_a.customer_id = '$customer_id' and c_d_d_t_t_a.firm_id='$firm_id' AND c_d_d_t_t_a.status !='4'");

        $qry_due_task = $this->db->get();
        if ($qry_due_task->num_rows() > 0) {
            $due_task_count = $qry_due_task->result();
            $due_date_task_count = $qry_due_task->num_rows();
            foreach ($due_task_count as $due_task) {
                $due_date_task_id = $due_task->due_date_task_id;
                $due_date_task_name = $due_task->due_date_task_name;
                $due_date_id = $due_task->due_date_id;
                $firm_id = $due_task->firm_id;
                $response['get_due_date_data'][] = ['due_date_id' => $due_date_id, 'due_date_task_id' => $due_date_task_id, 'due_date_task_name' => $due_date_task_name, 'firm_id' => $firm_id];
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['get_due_date_data'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function remove_customer_data() {
//          var_dump($_POST);exit();
        $customer_id = base64_decode($this->input->post('remve_customer_id'));
        $firm_id = base64_decode($this->input->post('remve_firm_id'));
        $due_date_all = $this->input->post('remve_due_date_all');
        $task_assing_all = $this->input->post('remve_task_assign_all');
        $due_date = $this->input->post('remve_due_date');
        $due_date_id = $this->input->post('remve_due_date_id');
        $task_assign = $this->input->post('remve_task_assign');



        if ($due_date != NULL && $task_assign != NULL) {
            foreach ($due_date as $value_due) {
                $data_due = array(
                    'status' => '4'
                );
                $this->db->where("customer_id='$customer_id' AND firm_id='$firm_id' AND due_date_task_id='$value_due'");
                $query = $this->db->update('customer_due_date_task_transaction_all', $data_due);
            }
            foreach ($task_assign as $value_task) {
                $data_task = array(
                    'status' => '4'
                );
                $this->db->where("customer_id='$customer_id' AND firm_id='$firm_id' AND task_assignment_id='$value_task'");
                $query = $this->db->update('customer_task_allotment_all', $data_task);
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else if ($task_assign != NULL) {
            foreach ($task_assign as $value_task) {
                $data_task = array(
                    'status' => '4'
                );
                $this->db->where("customer_id='$customer_id' AND firm_id='$firm_id' AND task_assignment_id='$value_task'");
                $query = $this->db->update('customer_task_allotment_all', $data_task);
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {

            foreach ($due_date as $value_due) {
                $data_due = array(
                    'status' => '4'
                );
                $this->db->where("customer_id='$customer_id' AND firm_id='$firm_id' AND due_date_task_id='$value_due'");
                $query = $this->db->update('customer_due_date_task_transaction_all', $data_due);
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        }
        echo json_encode($response);
    }

    public function remove_customer() {
        $customer_id = base64_decode($this->input->post('remve_customer_id'));
        $firm_id = base64_decode($this->input->post('remve_firm_id'));

        $result1 = $this->customer_model->removeCustomer($customer_id, $firm_id);
        if ($result1 == true) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'nodata';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

}

?>
