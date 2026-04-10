<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('loginmodel');
        $this->load->model('email_sending_model');
        $this->load->helper('dump_helper');
        $this->db2 = $this->load->database('db2', true);
		date_default_timezone_set("Asia/kolkata");
    }

    public function index() {
        $this->load->helper('form');
        $this->load->view('admin_login');
        $this->load->helper('cookie');
    }

	public function otp_page()
	{
		 $this->load->view('otp_new');
	}
	public function login_api($email='')
	{
		$this->load->view('login_api');
	}
	public function login_from_rmt($user_id='')
	{
		if($user_id != '')
		{
		//$user_id = $this->input->post_get('user_id');

		$query=$this->db->query("select password from user_header_all where email='$user_id'");
		if($this->db->affected_rows() > 0)
		{
			$result=$query->row();
			$password=$result->password;
			$response['password']=$password;
			$response['msg']="success";

		}else{
			$response['msg']="Mail id not Exist in Payroll";
		}
		}else{
			$response['msg']="Fail";
		}echo json_encode($response);
	}

    public function verify_and_start_session() {
        $this->load->model('loginmodel');
        $user_id = $this->input->post_get('user_id');
        $password = $this->input->post_get('password');
        $result = $this->loginmodel->login_validation($user_id, $password);
        if ($result !== FALSE) {
             $language="";
            $resultObject=$this->db2->get_where("user_header_all", array('email' => $user_id, 'activity_status' => 1));
                if($resultObject->num_rows>0)
                {
                    $languageresult=$resultObject->row();
                    $language=$languageresult->language;
                }
            $user_type = $result['user_type'];
            $firm_logo = $result['firm_logo'];
            $user_name = $result['user_name'];
            $emp_id = $result['emp_id'];
            $this->setcookie($firm_logo, $user_name, $password, $user_id, 1);
            $activity_status = $result['activity_status'];
            $session_data = array(
                'user_id' => $user_id,
                'emp_id' => $emp_id,
                'user_name' => $user_name,
                'user_type' => $user_type,
                'activity_status' => $activity_status,
                'language'=>$language
            );
            $this->session->set_userdata('login_session', $session_data);
            $response["status"] = 200;
            $response["body"] = "done";
        } else {
            $response["status"] = 201;
            $response["body"] = "done";
        }
        echo json_encode($response);
    }
    
	public function rmt_login(){
		$user_id=$_GET['user_id1'];
		$password=$_GET['password1'];
		$link=$_GET['id'];
		 $this->load->model('loginmodel');
         $result = $this->loginmodel->login_validation($user_id, $password);
		   if ($result !== FALSE) {
            $language="";
            $resultObject=$this->db2->get_where("user_header_all", array('email' => $user_id, 'activity_status' => 1));
                if($resultObject->num_rows>0)
                {
                    $languageresult=$resultObject->row();
                    $language=$languageresult->language;
                }
            $user_type = $result['user_type'];
            $firm_logo = $result['firm_logo'];
            $user_name = $result['user_name'];
            $emp_id = $result['emp_id'];
            $this->setcookie($firm_logo, $user_name, $password, $user_id, 1);
            $activity_status = $result['activity_status'];
            $session_data = array(
                'user_id' => $user_id,
                'emp_id' => $emp_id,
                'user_name' => $user_name,
                'user_type' => $user_type,
                'activity_status' => $activity_status,
                'language'=>$language
            );
            $this->session->set_userdata('login_session', $session_data);
			if($link != ""){
				if($link == "p_service_request"){
					redirect(base_url('serviceRequest'));
				}else if($link == "p_run_payroll"){
					redirect(base_url('runpayroll'));
				}else if($link == "p_form16"){
					redirect(base_url('form_16_submission'));
				}else{
					redirect(base_url('calendar'));
				}
			}else{
				redirect(base_url('calendar'));
			}

        } else {
           redirect(base_url());
        }
	}
    public function admin_login() {
        $data = array();
        $user_id = $this->input->post('user_id');
        $password = $this->input->post('password');
        $pass = ($password);

        if ($this->form_validation->run('sign-in') == false) {
            $data['error'] = validation_errors();
            $this->load->view('admin_login', $data);
        } else {
            $this->load->model('loginmodel');
            $result = $this->loginmodel->login_validation($user_id, $pass);
            if ($result !== FALSE) {
                $language="";
                $resultObject=$this->db2->get_where("user_header_all", array('email' => $user_id, 'activity_status' => 1));
                if($resultObject->num_rows>0) {
                    $languageresult=$resultObject->row();
                    $language=$languageresult->language;
                }

                $user_type = $result['user_type'];
                $firm_id = $result['firm_id'];
                $firm_logo = $result['firm_logo'];
                $user_name = $result['user_name'];
                $emp_id = $result['emp_id'];
                if ($user_type == '1') {
                    $user_id = $result['user_id'];
                    $activity_status = $result['activity_status'];
                    $session_data = array(
                        'user_id' => $user_id,
                        'firm_id' => $firm_id,
                        'user_type' => $user_type,
                        'activity_status' => $activity_status
                    );
                } else if ($user_type == '5') {
                    $user_id = $result['user_id'];
                    $activity_status = $result['activity_status'];
                    $session_data = array(
                        'user_id' => $user_id,
                        'user_name' => $user_name,
                        'firm_id' => $firm_id,
                        'emp_id' => $emp_id,
                        'user_type' => $user_type,
                        'activity_status' => $activity_status
                    );
                } else if ($user_type == '2') {
                    $user_id = $result['user_id'];
                    $activity_status = $result['activity_status'];
                    $session_data = array(
                        'user_id' => $user_id,
                        'firm_id' => $firm_id,
                        'user_name' => $user_name,
                        'emp_id' => $emp_id,
                        'user_type' => $user_type,
                        'activity_status' => $activity_status
                    );
                } else if ($user_type == '4') {
                    $user_id = $result['user_id'];
                    $activity_status = $result['activity_status'];
                    $session_data = array(
                        'user_id' => $user_id,
                        'firm_id' => $firm_id,
                        'emp_id' => $emp_id,
                        'user_name' => $user_name,
                        'user_type' => $user_type,
                        'activity_status' => $activity_status
                    );
                } elseif ($user_type == 're_user') {    //recommandated user
                    $this->session->set_userdata('login_session', $user_id);
                    redirect(base_url() . 'Recommendation_Course');
                } elseif ($user_type == 'customer') {    //recommandated user
                    $this->session->set_userdata('login_session', array($user_id, $password));
                    $otp = rand(1000, 9999);
                    $this->session->set_userdata('otp_session', $otp);
                    redirect(base_url() . 'Customer_verification');
                } else {
                    $user_id = $result['user_id'];
                    $is_emp = $result['is_employee'];
                    $user_name = $result['user_name'];
                    $create_task_assignment = $result['create_task_assignment'];
                    $enquiry_generate_permission = $result['enquiry_generate_permission'];
                    $create_due_date_permission = $result['create_due_date_permission'];
                    $create_service_permission = $result['create_service_permission'];
                    $activity_status = $result['firm_activity_status'];
                    $hr_authority = $result['hr_authority'];
                    $project_permission = $result['project_permission'];
                    $session_data = array(
                        'user_id' => $user_id,
                        'firm_id' => $firm_id,
                        'is_employee' => $is_emp,
                        'emp_id' => $emp_id,
                        'user_name' => $user_name,
                        'create_service_permission' => $create_service_permission,
                        'enquiry_generate_permission' => $enquiry_generate_permission,
                        'create_due_date_permission' => $create_due_date_permission,
                        'create_task_assignment' => $create_task_assignment,
                        'hr_authority' => $hr_authority,
                        'project_permission' => $project_permission,
                        'user_type' => $user_type
                    );
                }

                $session_data['language']=$language;
                if ($this->input->post_get("isMobile") == '0') {
                    $this->setcookie($firm_logo, $user_name, $password, $user_id, 0);
                    if ($user_type == '1' && $activity_status == '1') {  //superadmin
                        $this->session->set_userdata('login_session', $session_data);
                        $this->customer_model->create_permission_session();
                        redirect(base_url() . 'show_firm');
                    } else if ($user_type == '2' && $activity_status == '1') { //hq
                        $this->session->set_userdata('login_session', $session_data);
                        $this->customer_model->create_permission_session();
                        redirect(base_url() . 'hq_show_firm');
                    } else if ($user_type == '3' && $activity_status == 'D') {  //Cleint admin
                        $data['success'] = true;
                        $data['error'] = 'Your Branch is deactivated kindly contact administrator';
                        $this->load->view('admin_login', $data);
                    } else if ($user_type == '4' && $activity_status == '1') {   // employee
                        $this->session->set_userdata('login_session', $session_data);
                        $this->customer_model->create_permission_session();
                        redirect(base_url() . 'calendar');
                    } else if ($user_type == '5' && $activity_status == '1') {  // hr
                        $this->session->set_userdata('login_session', $session_data);
                        $this->customer_model->create_permission_session();
                        redirect(base_url() . 'serviceRequest');
                    } else if ($activity_status == 'D') {
                        $data['success'] = true;
                        $data['error'] = 'Your Branch is deactivated kindly contact administrator';
                        $this->load->view('admin_login', $data);
                    } else {
                        if ($activity_status != 1) {
                            $data['success'] = true;
                            $data['error'] = 'Your ID is deactivated kindly contact administrator.';
                            $this->load->view('admin_login', $data);
                        } else {

                            $data['success'] = true;
                            $data['error'] = 'Username and Password is mis-matched.';
                            $this->load->view('admin_login', $data);
                        }
                    }
                } else {
                    $loginResult = false;
                    if ($user_type == '1' && $activity_status == '1') {  //superadmin
                        $this->session->set_userdata('login_session', $user_id);
                        $this->customer_model->create_permission_session();
                        $loginResult = TRUE;
                    } else if ($user_type == '2' && $activity_status == '1') { //hq
                        $this->session->set_userdata('login_session', $session_data);
                        $this->customer_model->create_permission_session();
                        $loginResult = TRUE;
                    } else if ($user_type == '3' && $activity_status == 'A') {  //Cleint admin
                        $data['success'] = true;
                        $data['error'] = 'Your Branch is deactivated kindly contact administrator';
                        $this->load->view('admin_login', $data);
                    } else if ($user_type == '4' && $activity_status == '1') {   // employee
                        $this->session->set_userdata('login_session', $session_data);
                        $this->customer_model->create_permission_session();
                        $loginResult = TRUE;
                    } else if ($user_type == '5' && $activity_status == '1') {  // hr
                        $this->session->set_userdata('login_session', $session_data);
                        $this->customer_model->create_permission_session();
                        $loginResult = TRUE;
                    } else if ($activity_status == 'D') {
                        $data['success'] = true;
                        $data['error'] = 'Your Branch is deactivated kindly contact administrator';
                        $this->load->view('admin_login', $data);
                    } else {
                        if ($activity_status != 1) {
                            $data['success'] = true;
                            $data['error'] = 'Your ID is deactivated kindly contact administrator.';
                            $this->load->view('admin_login', $data);
                        } else {

                            $data['success'] = true;
                            $data['error'] = 'Username and Password is mis-matched.';
                            $this->load->view('admin_login', $data);
                        }
                    }

                    if ($loginResult) {
                        $this->session->set_userdata('login_session', $session_data);
                        $this->load->model('RegisterModel');
                        $otp = $this->RegisterModel->CreateOtp();
                        $opt_data = array('otp' => $otp, 'created_on' => date("Y-m-d H:i:s"), 'created_by' => $user_id, 'expire_time' => date("Y-m-d H:i:s", strtotime('+15 minute')));
                        if ($this->RegisterModel->SaveOpt($opt_data, $user_id)) {
                            $sub = 'Otp Verification';
                            $msg = $otp . ' is your one time password sent by Payroll System . It is valid for 15 minutes.Do not share your Otp with anyone';
                            $mail=$this->RegisterModel->sendEmail($user_id, $sub, $msg);
                            redirect(base_url('otp/' . base64_encode($user_id) . '/' . base64_encode($password)));

                        } else {
                            $data['success'] = true;
                            $data['error'] = 'OTP Generation Problem';
                            $this->load->view('admin_login', $data);
                        }
                    }
                }
            } else {
                $data['success'] = true;
                $data['error'] = 'Username and Password is mis-matched.';
                $this->load->view('admin_login', $data);
            }
        }
    }

    // Logout from admin page
    public function setcookie($firm_logo, $user_name, $password, $user_id, $isvalid) {
        // $firm_logo = $user_id;
        $name = 'rmt_tool';
        $value = $firm_logo . "," . $user_name . "," . $password . "," . $user_id . "," . $isvalid;
        $expire = time() + (86400 * 30); //86400 = 1 day
        $path = '/';
        $secure = TRUE;

        setcookie($name, $value, $expire, $path);
    }

    public function admin_logout() {

		 unset($_COOKIE['rmt_tool']);
		  setcookie('rmt_tool', null, -1, '/');

		// $cookie_name_fetch = $this->input->cookie('rmt_tool');

        $this->session->sess_destroy();
        $data['reason'] = '';


		header("Location:https://rmt.ecovisrkca.com/");
       // $this->load->view('admin_login', $data);

    }

    public function otp($username, $password) {
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
        $data["username"] = $username;
        $data["password"] = $password;
        $this->load->view('otpPage.php', $data);
    }

    public function add_logo() {
        $result_data = $this->customer_model->get_firm_id();
        if ($result_data !== false) {
            $firm_id = $result_data['firm_id'];
        }
        $file_upload = $this->upload_image($firm_id);
        $data = array(
            'firm_logo' => $file_upload,
        );
        $this->db->where('firm_id', $firm_id);
        $this->db->update('user_header_all ', $data);
        if ($this->db->affected_rows() > 0 && $file_upload !== "invalid") {
            $response['status'] = true;
            $response['message'] = 'success';
            $response['code'] = 200;
        } else {
            $response['status'] = false;
            $response['message'] = 'No data to display';
            $response['code'] = 204;
        } echo json_encode($response);
    }

    public function upload_image($firm_id) {
        //echo $ddl_due_date_name;
        $response = array();
        $user_id = $firm_id; // session or user_id
        //        var_dump($_FILES);
        if (isset($_FILES['FileUpload1']) && $_FILES['FileUpload1']['error'] != '4') :
            $files = $_FILES;
            //            $count = count($_FILES['FileUpload1']['name']); // count element
            //            for ($i = 0; $i < $count; $i++):
            $_FILES['FileUpload1']['name'] = $files['FileUpload1']['name'];
            $_FILES['FileUpload1']['type'] = $files['FileUpload1']['type'];
            $_FILES['FileUpload1']['tmp_name'] = $files['FileUpload1']['tmp_name'];
            $_FILES['FileUpload1']['error'] = $files['FileUpload1']['error'];
            $_FILES['FileUpload1']['size'] = $files['FileUpload1']['size'];
            $config['upload_path'] = './uploads/gallery/';
            $target_path = './uploads/gallery/thumbs/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|ppt|pptx';
            //                $config['allowed_types'] = 'pdf';
            $config['max_size'] = '500000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '800'; // image max width
            $config['max_height'] = '532';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = $_FILES['FileUpload1']['name'];
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('FileUpload1');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['FileUpload1']['name'] . ' ' . $error['upload_error'];
                    $response = "invalid";
                    return $response;
                } else {
                    return $fileName;
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

    public function leave_allow_permit() {
        $email_id = $this->session->userdata('login_session');
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$user_id'");
            if ($result3->num_rows() > 0) {
                $record3 = $result3->row();
                $value_permit = $record->leave_approve_permission;
                $data['val'] = $value_permit;
            } else {
                $data['val'] = '';
            }
        }
        $this->load->view('employee/navigation', $data);
    }

    //function send forget password link
    public function forget_pass_link() {
        $email_id = $this->input->post("email_id_forget");
        $query_get_name = $this->db
            ->where('email', $email_id)
            ->get('user_header_all');
        if ($query_get_name->num_rows() > 0) {
            $record = $query_get_name->row();
            $user_name = $record->user_name;
            $email = $this->email_sending_model->forget_pass_mail($email_id, $user_name);
            if ($email == true) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $response['id'] = 'email_id_forget';
            $response['error'] = "Invalid email ID";
        }
        echo json_encode($response);
    }

    //function for reset password load page
    public function reset_password_fun() {
        $this->load->view('reset_password');
    }

    public function change_pass() {
        $email = 'poojalote123@gmail.com';
        $new_pass = $this->input->post("first_pass");
        $confirm_pass = $this->input->post("second_pass");
        if (empty($new_pass)) {
            $response['id'] = 'first_pass';
            $response['error'] = "Please enter new password";
            echo json_encode($response);
            exit;
        } elseif (empty($confirm_pass)) {
            $response['id'] = 'second_pass';
            $response['error'] = "Please enter confirm  password";
            echo json_encode($response);
            exit;
        } elseif ($new_pass != $confirm_pass) {
            $response['id'] = 'second_pass';
            $response['error'] = "Password Mismatch!";
            echo json_encode($response);
            exit;
        } else {
            $query_update_pass = $this->db->query("update user_header_all set password='$new_pass' where email='$email'");
            if ($query_update_pass === TRUE) {
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

    function checkUUID() {
        $uuid = $this->input->post_get("uuID");

        $user_id = $this->db->select('uuid')->where(array('uuid' => $uuid, "activity_status" => 1))->get("user_header_all")->row();

        if (is_null($user_id)) {
            $response["status"] = 201;
            $response["body"] = "invalid";
        } else {
            $response["status"] = 200;
            $response["body"] = "valid";
        }
        echo json_encode($response);
    }

    // Start ticket management login function from Payroll
        public function ticketLoginByPayroll1() {
            try {
                $email = strtolower(trim($this->input->post('email')));
                $firm  = trim($this->input->post('firm_id'));
                $rmtDb     = $this->db2; // rmt app DB
                $payrollDb = $this->db;  // payroll DB
                $token = bin2hex(random_bytes(32));
                if (empty($email)) {
                    echo json_encode([
                        'status' => false,
                        'message'=> 'Email required'
                    ]);
                    return;
                }

                $sql = "SELECT u.*, ph.menu_access
                        FROM rmt.user_header_all u
                        LEFT JOIN rmt.partner_header_all ph ON ph.firm_id = u.firm_id
                        WHERE u.email = ?
                        AND u.activity_status = 1";

                $params = [$email];
                if (!empty($firm)) {
                    $sql .= " AND u.firm_id = ?";
                    $params[] = $firm;
                }

                $userDetails = $rmtDb->query($sql, $params)->row();
                if ($userDetails) {
                    $this->session->set_userdata('login_session', [
                        'id'         => $userDetails->id,
                        'firm_id'    => $userDetails->firm_id,
                        'user_id'    => $userDetails->user_id,
                        'email'      => $userDetails->email,
                        'user_name'  => $userDetails->user_name,
                        'user_type'  => $userDetails->user_type,
                        'mobile_no'  => $userDetails->mobile_no,
                        'menu_access'=> $userDetails->menu_access,
                        'logged_in'  => true,
                        'source'     => 'payroll'
                    ]);

                    echo json_encode([
                        'status' => true,
                        'token'   => $token,
                        'type'   => 'existing_user',
                        'message'=> 'Login successful',
                        'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                    ]);
                    return;
                } else {
                    $sql1 = "SELECT menu_access FROM partner_header_all WHERE firm_id =?";
                    $menu = $rmtDb->query($sql1, $firm)->row();
                    $sql = "SELECT u.*
                            FROM user_header_all u
                            LEFT JOIN partner_header_all ph ON ph.firm_id = u.firm_id
                            WHERE u.email = ?
                            AND u.activity_status = 1";

                    $payrollUser = $payrollDb->query($sql, [$email])->row();
                    if (!$payrollUser) {
                        echo json_encode([
                            'status' => false,
                            'message'=> 'User not found'
                        ]);
                        return;
                    }
                    $rmtDb->trans_start();
                    $insertData = [
                        'firm_id'            => $firm,
                        'user_id'            => $payrollUser->user_id,
                        'email'              => $email,
                        'user_name'          => $payrollUser->user_name,
                        'user_type'          => $payrollUser->user_type,
                        'mobile_no'          => $payrollUser->mobile_no,
                        'password'           => $payrollUser->password,
                        'hash_password'      => password_hash($payrollUser->password, PASSWORD_BCRYPT),
                        'designation_id'     => $payrollUser->designation_id,
                        'senior_user_id'     => $payrollUser->senior_user_id ?? '',
                        'e_folder_id'        => $payrollUser->e_folder_id,
                        'linked_with_boss_id'=> $payrollUser->linked_with_boss_id,
                        'address'            => $payrollUser->address ?? '',
                        'city'               => $payrollUser->city ?? '',
                        'state'              => $payrollUser->state ?? '',
                        'country'            => $payrollUser->country ?? '',
                        'temp_permission'    => 1,
                        'is_employee'        => 1,
                        'activity_status'    => 1,
                        'engagement_status'  => 1,
                        'created_on'         => date('Y-m-d H:i:s'),
                        'date_of_joining'    => date('Y-m-d H:i:s')
                    ];

                    $rmtDb->insert('rmt.user_header_all', $insertData);
                    $newUserId = $rmtDb->insert_id();

                    $rmtDb->trans_complete();
                    if ($rmtDb->trans_status() === FALSE) {
                        echo json_encode([
                            'status' => false,
                            'message'=> 'Failed to create user'
                        ]);
                        return;
                    }

                    $newUser = $rmtDb->query(
                        "SELECT u.*, ph.menu_access
                        FROM rmt.user_header_all u
                        LEFT JOIN rmt.partner_header_all ph ON ph.firm_id = u.firm_id
                        WHERE u.id = ?", [$newUserId]
                    )->row();

                    $this->session->set_userdata('login_session', [
                        'id'         => $newUser->id,
                        'firm_id'    => $newUser->firm_id,
                        'user_id'    => $newUser->user_id,
                        'email'      => $newUser->email,
                        'user_name'  => $newUser->user_name,
                        'user_type'  => $newUser->user_type,
                        'mobile_no'  => $newUser->mobile_no,
                        'menu_access'=> $newUser->menu_access,
                        'logged_in'  => true,
                        'source'     => 'payroll'
                    ]);

                    echo json_encode([
                        'status' => true,
                        'token'  => $token,
                        'type'   => 'existing_user',
                        'message'=> 'Login successful',
                        'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => false,
                    'message'=> $e->getMessage()
                ]);
            }
        }

        public function ticketLoginByPayroll() {
            try {
                $rmtDb      = $this->db2;
                $payrollDb  = $this->db;
                $email      = strtolower(trim($this->input->post('email')));
                $firm       = trim($this->input->post('firm_id'));
                if(!$email) {
                    echo json_encode([
                        'status' => false,
                        'message'=> 'Email required'
                    ]);
                    return;
                }
                $user = $rmtDb->where(['email' => $email,'activity_status' => 1,'firm_id' => $firm])->get('user_header_all')->row();
                if(!$user) {
                    $payrollUser = $payrollDb->where(['email' => $email,'activity_status' => 1])->get('user_header_all')->row();
                    if (!$payrollUser) {
                        echo json_encode([
                            'status' => false,
                            'message'=> 'User not found in payroll'
                        ]);
                        return;
                    }
                    $rmtDb->insert('user_header_all', [
                        'firm_id'            => $firm,
                        'user_id'            => $payrollUser->user_id,
                        'email'              => $email,
                        'user_name'          => $payrollUser->user_name,
                        'user_type'          => $payrollUser->user_type,
                        'mobile_no'          => $payrollUser->mobile_no,
                        'password'           => $payrollUser->password,
                        'hash_password'      => password_hash($payrollUser->password, PASSWORD_BCRYPT),
                        'designation_id'     => $payrollUser->designation_id,
                        'senior_user_id'     => $payrollUser->senior_user_id ?? '',
                        'e_folder_id'        => $payrollUser->e_folder_id,
                        'linked_with_boss_id'=> $payrollUser->linked_with_boss_id,
                        'address'            => $payrollUser->address ?? '',
                        'city'               => $payrollUser->city ?? '',
                        'state'              => $payrollUser->state ?? '',
                        'country'            => $payrollUser->country ?? '',
                        'temp_permission'    => 1,
                        'is_employee'        => 1,
                        'activity_status'    => 1,
                        'engagement_status'  => 1,
                        'created_on'         => date('Y-m-d H:i:s'),
                        'date_of_joining'    => date('Y-m-d H:i:s')
                    ]);
                    $user = $rmtDb->where('email', $email)->where('firm_id', $firm)->get('user_header_all')->row();
                }
                $token = bin2hex(random_bytes(32)); // GENERATE SSO TOKEN
                $payrollDb->insert('ticket_sso_tokens', [
                    'user_id'    => $user->user_id,
                    'email'      => $user->email,
                    'firm_id'    => $firm,
                    'token'      => $token,
                    'expires_at' => date('Y-m-d H:i:s', strtotime('+3 minutes')),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                echo json_encode([
                    'status' => true,
                    'token'  => $token
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => false,
                    'message'=> $e->getMessage()
                ]);
            }
        }
    // End ticket management login function from Payroll

}
