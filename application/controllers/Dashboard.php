<?php
	
	class 	Dashboard extends CI_Controller {
		
		public function __construct() {
			parent::__construct();
			$this->load->model('customer_model');
			$this->load->model('Nas_modal');
			$this->load->helper('dump_helper');
		}
		
		public function admin_dashboard() {
			$email_id = $this->session->userdata('login_session');
			$due_date_creation_permitted = $this->session->userdata('login_session');
			// dd($due_date_creation_permitted);
			$query = $this->db->query("SELECT count(user_id) as main_branch_count  from user_header_all where activity_status ='1' and created_by = '$email_id'");
			if ($query->num_rows() > 0) {
				$record_count = $query->result();
				
				foreach ($record_count as $row2) {
					$firm_count = $row2->main_branch_count;
				}
				$data['firm_count'] = $firm_count;
				} else {
				$data['firm_count'] = 0;
			}
			
			//dashboard show count for no of active users
			$nq = $this->db->query("SELECT `linked_with_boss_id` from `user_header_all` where email='$email_id'");
			if ($nq->num_rows() > 0) {
				$records = $nq->row();
				$linked_with_boss_id = $records->linked_with_boss_id;
			}
			
			
			
			
			$data['prev_title'] = "HR Admin List";
			$data['page_title'] = "HR Admin List";
			
			$this->load->view('admin/view_firm_data', $data);
		}
		public function check_access_token_is_expired_or_not_php(){
			$result1 = $this->customer_model->get_firm_id();
			if ($result1 !== false) {
				$firm_id = $result1['firm_id'];
				$user_id = $result1['user_id'];
			}
			
		}
		
		public function index_hq() {
			if (isset($this->session->login_session)) {
				$result1 = $this->customer_model->get_firm_id();
				if ($result1 !== false) {
					$firm_id = $result1['firm_id'];
					$user_id = $result1['user_id'];
				}
				
				$ac_token=$this->Nas_modal->check_access_token_is_expired_or_not($firm_id);
				
				if($ac_token===true){
					
					}else{
					
					$for = "refresh_token";
					$refresh_token = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update='');
					$access_token = $this->Nas_modal->genrate_access_token_from_refresh_token($refresh_token);
					$for = "update_token";
					$update_status = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token);
				}
				
				$email_id = $this->session->userdata('login_session');
				
				
				//dashboard show count for no of branch
				$q = $this->db->query("SELECT `reporting_to` from `partner_header_all` where `firm_email_id`='$email_id'");
				if ($q->num_rows() > 0) {
					
					$record = $q->row();
					$reporting_to = $record->reporting_to;
				}
				$query = $this->db->query("SELECT count(*) - (1) as `firm_count` from `partner_header_all` where `firm_activity_status` ='A' and `reporting_to` = '$reporting_to'");
				if ($query->num_rows() > 0) {
					$record_count = $query->result();
					foreach ($record_count as $row2) {
						$firm_count = $row2->firm_count;
					}
					$data1['firm_count'] = $firm_count;
				}
				
				$q11 = $this->db->query("SELECT `due_date_creation_permitted` from `partner_header_all` where `firm_id`='$firm_id'");
				if ($q11->num_rows() > 0) {
					$due_date = $q11->result();
					foreach ($due_date as $row3) {
						$due_date_creation_permitted = $row3->due_date_creation_permitted;
						//$this->load->library('session');
						$this->session->set_userdata("due_date_permission", $due_date_creation_permitted);
						//$due_date_creation_permitted = $this->session->userdata('due_date_creation_permitted');
					}
					//$data1['due_date_creation_permitted'] = $due_date_creation_permitted;
				}
				
				//end show count for no of branch
				//dashboard show count for no of active users
				$nq = $this->db->query("SELECT `linked_with_boss_id` from `user_header_all` WHERE `email` ='$email_id'");
				if ($nq->num_rows() > 0) {
					$records = $nq->row();
					$linked_with_boss_id = $records->linked_with_boss_id;
				}
				$qr = $this->db->query("SELECT count(*) as`emp_count` FROM `user_header_all` WHERE `boss_id` = '$linked_with_boss_id' and `user_type` = '4' and `activity_status` = '1'");
				if ($qr->num_rows() > 0) {
					$record_count = $qr->result();
					foreach ($record_count as $row3) {
						$emp_count = $row3->emp_count;
					}
					$data1['emp_count'] = $emp_count;
				}
				//dashboard show count for no of active users
				//dashboard show count for no of customer
				$cq = $this->db->query("SELECT count(*) as `customer_count` FROM `customer_header_all` WHERE `boss_id` = '$linked_with_boss_id' and `activity_status` = '1' ");
				
				if ($cq->num_rows() > 0) {
					$record_count = $cq->result();
					foreach ($record_count as $row4) {
						$customer_count = $row4->customer_count;
					}
					$data1['customer_count'] = $customer_count;
				}
				//end dashboard show count for no of customer
				
				
				
				$query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` WHERE `firm_id` = '$firm_id'");
				if ($query->num_rows() > 0) {
					$record = $query->row();
					$firm_logo = $record->firm_logo;
					$firm_name = $record->user_name;
					if ($firm_logo == "" && $firm_name == "") {
						$data1['logo'] = "";
						$data1['firm_name_nav'] = "";
						} else {
						$data1['logo'] = $firm_logo;
						$data1['firm_name_nav'] = $firm_name;
					}
					} else {
					$data1['logo'] = "";
					$data1['firm_name_nav'] = "";
				}
				
				
				
				$data1['prev_title'] = "Dashboard";
				$data1['page_title'] = "Dashboard";
				$data1['firm_id'] = $firm_id;
				
				// end of client graph
				$this->load->view('hq_admin/Dashboard', $data1);
				} else {
				redirect(base_url() . 'Login');
			}
		}
		
		public function get_firm_id_array() {
			$firm_id_array = array();
			$session_data = $this->session->userdata('login_session');
			if (is_array($session_data)) {
				$data['session_data'] = $session_data;
				$user_id = ($session_data['user_id']);
				} else {
				$user_id = $this->session->userdata('login_session');
			}
			
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
					
					foreach ($query_2->result() as $row) {
						array_push($firm_id_array, $row->firm_id);
					}
					return $firm_id_array;
					} else {
					return false;
				}
				} else {
				return false;
			}
		}
		
		public function get_firm() {
			$session_data = $this->session->userdata('login_session');
			if (is_array($session_data)) {
				$data['session_data'] = $session_data;
				$user_id = ($session_data['user_id']);
				} else {
				$user_id = $this->session->userdata('login_session');
			}
			
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
						$response['frim_data'][] = ['firm_name' => $row->firm_name, 'firm_id' => $row->firm_id, 'reporting_to' => $row->reporting_to, 'boss_id' => $row->boss_id];
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
		
		public function index_ca() {
			if (isset($this->session->login_session)) {
				$result1 = $this->customer_model->get_firm_id();
				
				if ($result1 !== false) {
					$firm_id = $result1['firm_id'];
				}
				
				
				$ac_token=$this->Nas_modal->check_access_token_is_expired_or_not($firm_id);
				//echo $this->db->last_query();
				
				if($ac_token===true){
					
					}else{
					
					$for = "refresh_token";
					$refresh_token = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update='');
					echo $refresh_token;
					$access_token = $this->Nas_modal->genrate_access_token_from_refresh_token($refresh_token);
					$for = "update_token";
					$update_status = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token);
				}
				
				
				$email_id = $this->session->userdata('login_session');
				//dashboard count for customers
				$cq = $this->db->query("SELECT firm_id from user_header_all where email='$email_id'");
				if ($cq->num_rows() > 0) {
					
					$record = $cq->row();
					$firm_id = $record->firm_id;
				}
				$cquery = $this->db->query("SELECT count(*)  as `firm_counts` from customer_header_all where firm_id ='$firm_id'");
				if ($cquery->num_rows() > 0) {
					$record_count = $cquery->result();
					
					foreach ($record_count as $row2) {
						
						$firm_counts = $row2->firm_counts;
					}
					$data['firm_counts'] = $firm_counts;
				}
				//end dashboard count for customers
				//dashboard count for active users
				$caquery = $this->db->query("SELECT count(*)  as `active_users` from user_header_all where firm_id ='$firm_id' and user_type='4' and activity_status='1'");
				if ($caquery->num_rows() > 0) {
				$record_count = $caquery->result();
				
				foreach ($record_count as $row5) {
				
				$active_users = $row5->active_users;
				}
				$data['active_users'] = $active_users;
				}
				//end dashboard count for active users
				//dashboard count for duedate
				$duequery = $this->db->query("SELECT count(*) as `duedate_counts` from due_date_header_all where firm_id='$firm_id' and status='1'");
				if ($duequery->num_rows() > 0) {
				$record_count = $duequery->result();
				
				foreach ($record_count as $row6) {
				$duedate_counts = $row6->duedate_counts;
				}
				$data['duedate_counts'] = $duedate_counts;
				}
				//end dashboard count for duedate
				//dashboard count for tasks
				$taskquery = $this->db->query("SELECT count(*) as `task_Counts` FROM `task_header_all`where `firm_id`='$firm_id' AND `status`='1'");
				if ($taskquery->num_rows() > 0) {
				$record_count = $taskquery->result();
				
				foreach ($record_count as $row7) {
				$task_Counts = $row7->task_Counts;
				}
				$data['task_Counts'] = $task_Counts;
				}
				
				//end dashboard count for task
				//for check due date permission
				$q11 = $this->db->query("SELECT `due_date_creation_permitted` from `partner_header_all` where `firm_id`='$firm_id'");
				if ($q11->num_rows() > 0) {
				$due_date = $q11->result();
				foreach ($due_date as $row3) {
				$due_date_creation_permitted = $row3->due_date_creation_permitted;
				$this->session->set_userdata("due_date_permission", $due_date_creation_permitted);
				}
				}
				
				
				$query_to_fetcth_event_date = $this->db->query("SELECT `create_at`,`holiday_date`,`end_date`,`holiday_id` FROM `holiday_header_all` where   FIND_IN_SET(`holiday_applied_in`, '$firm_id')");
				if ($query_to_fetcth_event_date->num_rows() > 0) {
				
				$record_event = $query_to_fetcth_event_date->result();
				foreach ($record_event as $row) {
				$start_date = $row->holiday_date;
				$end_date = $row->end_date;
				$today_date = date("Y/m/d");
				//                $query_event = $this->db->query("SELECT * FROM `holiday_management_all` WHERE `start_date` >= '$start_date' AND `end_date` <= '$end_date' AND `firm_id` = '$firm_id'");
				$query_event = $this->db->query("SELECT * from holiday_header_all where
				(create_at BETWEEN '$start_date' AND '$end_date') OR
				(end_date BETWEEN '$start_date' AND '$end_date') OR
				(create_at <= '$start_date' AND end_date >= '$end_date') AND  FIND_IN_SET(`holiday_applied_in`, '$firm_id')");
				
				
				$data = array('event_id' => array());
				if ($query_event->num_rows() > 0) {
				$record_data = $query_event->result();
				$data['result'] = $record_data;
				//            var_dump($result);
				} else {
				$data['result'] = "";
				}
				}
				} else {
				$data['result'] = "";
				}
				
				
				$query = $this->db->query("SELECT `firm_logo`, `user_name` FROM `user_header_all` where `firm_id` = '$firm_id'");
				if ($query->num_rows() > 0) {
				
				$record = $query->row();
				$firm_logo = $record->firm_logo;
				$firm_name = $record->user_name;
				if ($firm_logo == "" && $firm_name == "") {
				
				$data['logo'] = "";
				$data['firm_name_nav'] = "";
				$data['duedate_counts'] = "";
				$data['active_users'] = "";
				$data['firm_counts'] = "";
				$data['task_Counts'] = "";
				} else {
				$data['logo'] = $firm_logo;
				$data['firm_name_nav'] = $firm_name;
				$data['duedate_counts'] = $duedate_counts;
				$data['active_users'] = $active_users;
				$data['firm_counts'] = $firm_counts;
				$data['task_Counts'] = $task_Counts;
				}
				} else {
				$data['logo'] = "";
				$data['firm_name_nav'] = "";
				}
				$data['prev_title'] = "Dashboard";
				$data['page_title'] = "Dashboard";
				$data['firm_id'] = $firm_id;
				$this->load->view('client_admin/dashboard', $data);
				} else {
				//            $data["is_cookie"] = "false";
				//            $this->load->view('admin_login', $data);
				redirect(base_url() . 'Login');
				}
				}
				
				public function ca_all_graph() {
				
				$result1 = $this->customer_model->get_firm_id();
				$id1 = $result1['firm_id'];
				
				// var_dump($id1);
				$result = $this->db->query("select created_on from customer_task_allotment_all ")->result();
				//var_dump($result);
				$date = array();
				$taskcount = array();
				$task = array();
				$taskc = array();
				$taskcount1 = array();
				$taskcount2 = array();
				foreach ($result as $row) {
				$date[] = $row->created_on;
				}
				
				$r5 = "";
				$r6 = "";
				$firmid = array();
				$month = array();
				$data['w'] = array();
				$data['x'] = array();
				$data['y'] = array();
				$data['z'] = array();
				
				for ($i = 0; $i < count($date); $i++) {
				$r1 = $date[$i];
				$r2 = explode(' ', $r1);
				$r3 = $r2[0];
				$r4 = explode('-', $r3);
				$year = $r4[0];
				$month = $r4[1];
				$r5 .= $year . "-" . $month;
				$r6 .= $year . "-" . $month . ",";
				}
				
				$namrata = explode(',', $r6);
				//        var_dump($namrata);
				$month1 = array_values(array_unique($namrata));
				
				for ($i = 0; $i < count($month1) - 1; $i++) {
				
				$result1 = $this->db->query("select firm_id,count(task_assignment_id) as task_assignment_c,task_assignment_id,count(created_on) as created_on from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and firm_id='$id1'")->result();
				$result2 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='3' and firm_id='$id1'")->result();
				$result3 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count1  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='2' and firm_id='$id1'")->result();
				$result4 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count2  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='4' and firm_id='$id1'")->result();
				
				foreach ($result1 as $row1) {
				//$created_on=$row1->created_on;
				$firmid[] = $row1->firm_id;
				$task[] = $row1->task_assignment_id;
				$taskc[] = $row1->task_assignment_c;
				}
				//var_dump($taskc);
				$abcd1 = array();
				for ($a = 0; $a < sizeof($taskc); $a++) { //loop to convert string data into integer
				$abcd1[] = $taskc[$a];
				$aa1 = settype($abcd1[$a], "int");
				}
				//            var_dump($abcd1);
				
				foreach ($result2 as $row2) {
				
				$taskcount[] = $row2->task_assignment_count;
				}
				$abcd2 = array();
				for ($b = 0; $b < sizeof($taskcount); $b++) { //loop to convert string data into integer
				$abcd2[] = $taskcount[$b];
				$aa1 = settype($abcd2[$b], "int");
				}
				foreach ($result3 as $row3) {
				
				$taskcount1[] = $row3->task_assignment_count1;
				}
				$abcd3 = array();
				for ($c = 0; $c < sizeof($taskcount1); $c++) { //loop to convert string data into integer
				$abcd3[] = $taskcount1[$c];
				$aa1 = settype($abcd3[$c], "int");
				}
				foreach ($result4 as $row4) {
				
				$taskcount2[] = $row4->task_assignment_count2;
				}
				
				$abcd4 = array();
				for ($d = 0; $d < sizeof($taskcount2); $d++) { //loop to convert string data into integer
				$abcd4[] = $taskcount2[$d];
				$aa1 = settype($abcd4[$d], "int");
				}
				
				//var_dump($abcd);
				}
				$response['message'] = 'success';
				$response['code'] = 200;
				$response['status'] = true;
				$response['data'] = $abcd1;
				$response['months'] = $month1;
				$response['data1'] = $abcd2;
				$response['data2'] = $abcd3;
				$response['data3'] = $abcd4;
				
				//print_r($enquiry_id);
				echo json_encode($response);
				}
				
				public function ca_branch_all_graph() {
				$id2 = $this->input->post('firm_id');
				
				
				
				$result = $this->db->query("select created_on from customer_task_allotment_all ")->result();
				//var_dump($result);
				$date = array();
				$taskcount = array();
				$task = array();
				$taskc = array();
				$taskcount1 = array();
				$taskcount2 = array();
				foreach ($result as $row) {
				$date[] = $row->created_on;
				}
				
				$r5 = "";
				$r6 = "";
				$firmid = array();
				$month = array();
				$data['w'] = array();
				$data['x'] = array();
				$data['y'] = array();
				$data['z'] = array();
				
				for ($i = 0; $i < count($date); $i++) {
				$r1 = $date[$i];
				$r2 = explode(' ', $r1);
				$r3 = $r2[0];
				$r4 = explode('-', $r3);
				$year = $r4[0];
				$month = $r4[1];
				$r5 .= $year . "-" . $month;
				$r6 .= $year . "-" . $month . ",";
				}
				
				$namrata = explode(',', $r6);
				//        var_dump($namrata);
				$month1 = array_values(array_unique($namrata));
				
				for ($i = 0; $i < count($month1) - 1; $i++) {
				
				$result1 = $this->db->query("select firm_id,count(task_assignment_id) as task_assignment_c,task_assignment_id,count(created_on) as created_on from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and alloted_to_emp_id='$id2'")->result();
				$result2 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='3' and alloted_to_emp_id='$id2'")->result();
				$result3 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count1  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='2' and alloted_to_emp_id='$id2'")->result();
				$result4 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count2  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='4' and alloted_to_emp_id='$id2'")->result();
				
				foreach ($result1 as $row1) {
				//$created_on=$row1->created_on;
				$firmid[] = $row1->firm_id;
				$task[] = $row1->task_assignment_id;
				$taskc[] = $row1->task_assignment_c;
				}
				//                var_dump($taskc);
				$abcd1 = array();
				for ($a = 0; $a < sizeof($taskc); $a++) { //loop to convert string data into integer
				$abcd1[] = $taskc[$a];
				$aa1 = settype($abcd1[$a], "int");
				}
				//            var_dump($abcd1);
				
				foreach ($result2 as $row2) {
				
				$taskcount[] = $row2->task_assignment_count;
				}
				$abcd2 = array();
				for ($b = 0; $b < sizeof($taskcount); $b++) { //loop to convert string data into integer
				$abcd2[] = $taskcount[$b];
				$aa1 = settype($abcd2[$b], "int");
				}
				foreach ($result3 as $row3) {
				
				$taskcount1[] = $row3->task_assignment_count1;
				}
				$abcd3 = array();
				for ($c = 0; $c < sizeof($taskcount1); $c++) { //loop to convert string data into integer
				$abcd3[] = $taskcount1[$c];
				$aa1 = settype($abcd3[$c], "int");
				}
				foreach ($result4 as $row4) {
				
				$taskcount2[] = $row4->task_assignment_count2;
				}
				
				$abcd4 = array();
				for ($d = 0; $d < sizeof($taskcount2); $d++) { //loop to convert string data into integer
				$abcd4[] = $taskcount2[$d];
				$aa1 = settype($abcd4[$d], "int");
				}
				
				//var_dump($abcd);
				}
				
				$userdata1 = $this->db->query("SELECT user_id,user_name FROM `user_header_all` where user_type='4'  and is_employee='1'")->result();
				
				if (count($userdata1) > 0) {
				$data = array('empname' => array(), 'empid' => array());
				foreach ($userdata1 as $users) {
				
				$response['userdetails'][] = ['empid' => $users->user_id, 'empname' => $users->user_name];
				}
				
				$response['message'] = 'success';
				$response['code'] = 200;
				$response['status'] = true;
				} else {
				$response['message'] = 'fail';
				$response['code'] = 201;
				$response['status'] = false;
				}
				
				$response['message'] = 'success';
				$response['code'] = 200;
				$response['status'] = true;
				$response['emp1'] = $abcd1;
				$response['month1'] = $month1;
				$response['emp2'] = $abcd2;
				$response['emp3'] = $abcd3;
				$response['emp4'] = $abcd4;
				
				//print_r($enquiry_id);
				echo json_encode($response);
				}
				
				public function ca_dashboard_charts() {
				$email_id = $this->session->userdata('login_session');
				$cq = $this->db->query("SELECT firm_id from user_header_all where email='$email_id'");
				if ($cq->num_rows() > 0) {
				
				$record = $cq->row();
				$firm_id = $record->firm_id;
				}
				$query = $this->db->query("select created_on from enquiry_header_all where firm_id='$firm_id'");
				$date = array();
				if ($this->db->affected_rows() > 0) {
				$result = $query->result();
				foreach ($result as $row) {
				$date[] = $row->created_on;
				}
				$r6 = "";
				$r5 = "";
				for ($i = 0; $i < count($date); $i++) {
				$r1 = $date[$i];
				$r2 = explode(' ', $r1);
				$r3 = $r2[0];
				$r4 = explode('-', $r3);
				$year = $r4[0];
				$month = $r4[1];
				$r5 .= $year . "-" . $month;
				$r6 .= $year . "-" . $month . ",";
				}
				}
				
				$exp_mon = explode(',', $r6);
				$avrage_of_completed = array();
				$avrage_of_closed = array();
				$avrage_of_convreted = array();
				$sum_of_complted_converted = array();
				$month1 = array_values(array_unique($exp_mon));
				//  print_r($month1);
				for ($z = 0; $z < count($month1) - 1; $z++) {
				$query_for_get_data_for_charts_all = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%'");
				if ($query_for_get_data_for_charts_all->num_rows() > 0) {
				// $enquiry_id = array();
				foreach ($query_for_get_data_for_charts_all->result() as $row) {
				// $enquiry_id[] = $row->enquiry_id;
				$enquiry_id_count_all[] = $row->no_of_allot;
				}
				$abc0 = array();
				for ($xy = 0; $xy < sizeof($enquiry_id_count_all); $xy++) { //loop to convert string data into integer
				$abc0[] = $enquiry_id_count_all[$xy];
				$aa1 = settype($abc0[$xy], "int");
				}
				$query_for_get_data_for_charts_init = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='2'");
				if ($query_for_get_data_for_charts_init->num_rows() > 0) {
				// $enquiry_id = array();
				foreach ($query_for_get_data_for_charts_init->result() as $row) {
				// $enquiry_id[] = $row->enquiry_id;
				$enquiry_id_count_init[] = $row->no_of_allot;
				}
				$abci = array();
				for ($xyz = 0; $xyz < sizeof($enquiry_id_count_init); $xyz++) { //loop to convert string data into integer
				$abci[] = $enquiry_id_count_init[$xyz];
				$aa1 = settype($abci[$xyz], "int");
				}
				} else {
				
				}
				//print_r($abci);
				
				$query_for_get_data_for_charts_not_init = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='1'");
				if ($query_for_get_data_for_charts_not_init->num_rows() > 0) {
				// $enquiry_id = array();
				foreach ($query_for_get_data_for_charts_not_init->result() as $row) {
				// $enquiry_id[] = $row->enquiry_id;
				$enquiry_id_count_not_init[] = $row->no_of_allot;
				}
				$abcin = array();
				for ($wxyz = 0; $wxyz < sizeof($enquiry_id_count_not_init); $wxyz++) { //loop to convert string data into integer
				$abcin[] = $enquiry_id_count_not_init[$wxyz];
				$aa1 = settype($abcin[$wxyz], "int");
				}
				} else {
				
				}
				// print_r($abcin);
				
				$query_for_get_data_for_charts = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and allot_to<>''");
				if ($query_for_get_data_for_charts->num_rows() > 0) {
				$enquiry_id = array();
				foreach ($query_for_get_data_for_charts->result() as $row) {
				// $enquiry_id[] = $row->enquiry_id;
				$enquiry_id_count[] = $row->no_of_allot;
				}
				$abc = array();
				for ($o = 0; $o < sizeof($enquiry_id_count); $o++) { //loop to convert string data into integer
				$abc[] = $enquiry_id_count[$o];
				$aa1 = settype($abc[$o], "int");
				}
				} else {
				
				}
				
				$query_for_get_data_for_charts1 = $this->db->query("select DISTINCT count(enquiry_id) as no_of_allot1 from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='4'");
				if ($query_for_get_data_for_charts1->num_rows() > 0) {
				//$enquiry_id = array();
				foreach ($query_for_get_data_for_charts1->result() as $row) {
				// $enquiry_id[] = $row->enquiry_id;
				$enquiry_id_count_for_completed[] = $row->no_of_allot1;
				}
				$abc1 = array();
				for ($m = 0; $m < sizeof($enquiry_id_count_for_completed); $m++) { //loop to convert string data into integer
				$abc1[] = $enquiry_id_count_for_completed[$m];
				$aa1 = settype($abc1[$m], "int");
				}
				} else {
				
				}
				
				
				$query_for_get_data_for_charts1_for_failed = $this->db->query("select DISTINCT count(enquiry_id) as no_of_allot1 from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='5'");
				if ($query_for_get_data_for_charts1_for_failed->num_rows() > 0) {
				// $enquiry_id = array();
				foreach ($query_for_get_data_for_charts1_for_failed->result() as $row) {
				// $enquiry_id[] = $row->enquiry_id;
				$enquiry_id_count_for_failed[] = $row->no_of_allot1;
				}
				$abc2 = array();
				for ($n = 0; $n < sizeof($enquiry_id_count_for_failed); $n++) { //loop to convert string data into integer
				$abc2[] = $enquiry_id_count_for_failed[$n];
				$aa2 = settype($abc2[$n], "int");
				}
				} else {
				
				}
				
				
				
				$query_for_get_data_for_charts1_for_converted = $this->db->query("select DISTINCT count(enquiry_id) as no_of_allot1 from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='6'");
				if ($query_for_get_data_for_charts1_for_converted->num_rows() > 0) {
				$enquiry_id = array();
				foreach ($query_for_get_data_for_charts1_for_converted->result() as $row) {
				// $enquiry_id[] = $row->enquiry_id;
				$enquiry_id_count_for_converted[] = $row->no_of_allot1;
				}
				$abc3 = array();
				for ($p = 0; $p < sizeof($enquiry_id_count_for_converted); $p++) { //loop to convert string data into integer
				$abc3[] = $enquiry_id_count_for_converted[$p];
				$aa2 = settype($abc3[$p], "int");
				}
				} else {
				
				}
				
				$sum_of_complted_converted[$z] = $abc1[$z] + $abc3[$z];
				$avrage_of_convreted[$z] = round(($sum_of_complted_converted[$z] / $abc0[$z]) * 100);
				//                $sum_of_complted_converted[$z];
				//echo $abc1[$z]+$abc3[$z];
				$avrage_of_completed[$z] = round(($abc1[$z] / $abc0[$z]) * 100);
				$avrage_of_closed[$z] = round(($abc2[$z] / $abc0[$z]) * 100);
				$response['message'] = 'success';
				$response['code'] = 200;
				$response['status'] = true;
				} else {
				$response['message'] = 'No data to display';
				$response['code'] = 204;
				$response['status'] = false;
				}
				}
				
				
				
				//        print_r($abc1);
				//        print_r($abc3);
				////        print_r($sum_of_complted_converted);
				//        print_r($abc0);
				//        print_r($avrage_of_convreted);
				// $data = array(12, 15, 8);
				//        var_dump($abc1);
				//        $data3 = array(16, 9, 23);
				// $response['enquiry_id'] = $enquiry_id;
				//print_r($abc0);
				$response['enquiry_count'] = $enquiry_id_count;
				$response['datat'] = $abc0;
				$response['data'] = $abc;
				$response['months'] = $month1;
				$response['data1'] = $abc1;
				$response['data2'] = $abc2;
				$response['data3'] = $abc3;
				$response['datai'] = $abci;
				$response['datain'] = $abcin;
				$response['av_converted'] = $avrage_of_convreted;
				$response['av_completed'] = $avrage_of_completed;
				$response['av_closed'] = $avrage_of_closed;
				
				
				//print_r($enquiry_id);
				echo json_encode($response);
				}
				
				public function hq_branch_all_task_graph() {
				$id2 = $this->input->post('firm_id1');
				
				$result = $this->db->query("select created_on from customer_task_allotment_all ")->result();
				//var_dump($result);
				$date = array();
				$taskcount = array();
				$task = array();
				$taskc = array();
				$taskcount1 = array();
				$taskcount2 = array();
				foreach ($result as $row) {
				$date[] = $row->created_on;
				}
				
				$r5 = "";
				$r6 = "";
				$firmid = array();
				$month = array();
				$data['w'] = array();
				$data['x'] = array();
				$data['y'] = array();
				$data['z'] = array();
				
				for ($i = 0; $i < count($date); $i++) {
				$r1 = $date[$i];
				$r2 = explode(' ', $r1);
				$r3 = $r2[0];
				$r4 = explode('-', $r3);
				$year = $r4[0];
				$month = $r4[1];
				$r5 .= $year . "-" . $month;
				$r6 .= $year . "-" . $month . ",";
				}
				
				$namrata = explode(',', $r6);
				//        var_dump($namrata);
				$month1 = array_values(array_unique($namrata));
				
				for ($i = 0; $i < count($month1) - 1; $i++) {
				
				$result1 = $this->db->query("select firm_id,count(task_assignment_id) as task_assignment_c,task_assignment_id,count(created_on) as created_on from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and firm_id='$id2'")->result();
				$result2 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='3' and firm_id='$id2'")->result();
				$result3 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count1  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='2' and firm_id='$id2'")->result();
				$result4 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count2  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='4' and firm_id='$id2'")->result();
				
				foreach ($result1 as $row1) {
				//$created_on=$row1->created_on;
				$firmid[] = $row1->firm_id;
				$task[] = $row1->task_assignment_id;
				$taskc[] = $row1->task_assignment_c;
				}
				//                var_dump($taskc);
				$abcd1 = array();
				for ($a = 0; $a < sizeof($taskc); $a++) { //loop to convert string data into integer
				$abcd1[] = $taskc[$a];
				$aa1 = settype($abcd1[$a], "int");
				}
				//            var_dump($abcd1);
				
				foreach ($result2 as $row2) {
				
				$taskcount[] = $row2->task_assignment_count;
				}
				$abcd2 = array();
				for ($b = 0; $b < sizeof($taskcount); $b++) { //loop to convert string data into integer
				$abcd2[] = $taskcount[$b];
				$aa1 = settype($abcd2[$b], "int");
				}
				foreach ($result3 as $row3) {
				
				$taskcount1[] = $row3->task_assignment_count1;
				}
				$abcd3 = array();
				for ($c = 0; $c < sizeof($taskcount1); $c++) { //loop to convert string data into integer
				$abcd3[] = $taskcount1[$c];
				$aa1 = settype($abcd3[$c], "int");
				}
				foreach ($result4 as $row4) {
				
				$taskcount2[] = $row4->task_assignment_count2;
				}
				
				$abcd4 = array();
				for ($d = 0; $d < sizeof($taskcount2); $d++) { //loop to convert string data into integer
				$abcd4[] = $taskcount2[$d];
				$aa1 = settype($abcd4[$d], "int");
				}
				
				//var_dump($abcd);
				}
				
				
				
				$branchname = $this->db->query("SELECT firm_id,firm_name FROM `partner_header_all` ")->result();
				
				if (count($branchname) > 0) {
				$data = array('branchname' => array(), 'branchid' => array());
				foreach ($branchname as $branch) {
				
				$response['branchdetails'][] = ['branchid' => $branch->firm_id, 'branchname' => $branch->firm_name];
				}
				
				$response['message'] = 'success';
				$response['code'] = 200;
				$response['status'] = true;
				} else {
				$response['message'] = 'fail';
				$response['code'] = 201;
				$response['status'] = false;
				}
				
				
				
				$response['message'] = 'success';
				$response['code'] = 200;
				$response['status'] = true;
				$response['branch1'] = $abcd1;
				$response['month1'] = $month1;
				$response['branch2'] = $abcd2;
				$response['branch3'] = $abcd3;
				$response['branch4'] = $abcd4;
				
				//print_r($enquiry_id);
				echo json_encode($response);
				}
				
				public function getEmployeeData() {
				$firm_id = $this->input->post('firm_id');
				
				$userdata = $this->db->query("SELECT user_id,user_name FROM `user_header_all` where firm_id='$firm_id' and user_type='4' and is_employee='1'")->result();
				
				if (count($userdata) > 0) {
				$data = array('user_name' => array(), 'user_id' => array());
				foreach ($userdata as $user) {
				$data['user_name'] = $user->user_name;
				$data['user_id'] = $user->user_id;
				$response['userdetail'][] = ['employee_id' => $user->user_id, 'employee_name' => $user->user_name];
				}
				
				$response['message'] = 'success';
				$response['code'] = 200;
				$response['status'] = true;
				} else {
				$response['message'] = 'fail';
				$response['code'] = 201;
				$response['status'] = false;
				}
				
				
				echo json_encode($response);
				}
				
				public function hq_branch_emp_all_task_graph() {
				$firm_id1 = $this->input->post('firm_id1');
				//         var_dump($firm_id1);
				
				
				$result = $this->db->query("select created_on from customer_task_allotment_all ")->result();
				//var_dump($result);
				$date = array();
				$taskcount = array();
				$task = array();
				$taskc = array();
				$taskcount1 = array();
				$taskcount2 = array();
				foreach ($result as $row) {
				$date[] = $row->created_on;
				}
				
				$r5 = "";
				$r6 = "";
				$firmid = array();
				$month = array();
				$data['w'] = array();
				$data['x'] = array();
				$data['y'] = array();
				$data['z'] = array();
				
				for ($i = 0; $i < count($date); $i++) {
				$r1 = $date[$i];
				$r2 = explode(' ', $r1);
				$r3 = $r2[0];
				$r4 = explode('-', $r3);
				$year = $r4[0];
				$month = $r4[1];
				$r5 .= $year . "-" . $month;
				$r6 .= $year . "-" . $month . ",";
				}
				
				$namrata = explode(',', $r6);
				//        var_dump($namrata);
				$month1 = array_values(array_unique($namrata));
				
				for ($i = 0; $i < count($month1) - 1; $i++) {
				
				$result1 = $this->db->query("select firm_id,count(task_assignment_id) as task_assignment_c,task_assignment_id,count(created_on) as created_on from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and alloted_to_emp_id='$firm_id1'")->result();
				$result2 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='3' and alloted_to_emp_id='$firm_id1'")->result();
				$result3 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count1  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='2' and alloted_to_emp_id='$firm_id1'")->result();
				$result4 = $this->db->query(" select firm_id,count(task_assignment_id) as task_assignment_count2  from customer_task_allotment_all where created_on LIKE '$month1[$i]%' and sub_task_id= '' and status='4' and alloted_to_emp_id='$firm_id1'")->result();
				
				foreach ($result1 as $row1) {
				//$created_on=$row1->created_on;
				$firmid[] = $row1->firm_id;
				$task[] = $row1->task_assignment_id;
				$taskc[] = $row1->task_assignment_c;
				}
				//                var_dump($taskc);
				$abcd1 = array();
				for ($a = 0; $a < sizeof($taskc); $a++) { //loop to convert string data into integer
				$abcd1[] = $taskc[$a];
				$aa1 = settype($abcd1[$a], "int");
				}
				//            var_dump($abcd1);
				
				foreach ($result2 as $row2) {
				
				$taskcount[] = $row2->task_assignment_count;
				}
				$abcd2 = array();
				for ($b = 0; $b < sizeof($taskcount); $b++) { //loop to convert string data into integer
				$abcd2[] = $taskcount[$b];
				$aa1 = settype($abcd2[$b], "int");
				}
				foreach ($result3 as $row3) {
				
				$taskcount1[] = $row3->task_assignment_count1;
				}
				$abcd3 = array();
				for ($c = 0; $c < sizeof($taskcount1); $c++) { //loop to convert string data into integer
				$abcd3[] = $taskcount1[$c];
				$aa1 = settype($abcd3[$c], "int");
				}
				foreach ($result4 as $row4) {
				
				$taskcount2[] = $row4->task_assignment_count2;
				}
				
				$abcd4 = array();
				for ($d = 0; $d < sizeof($taskcount2); $d++) { //loop to convert string data into integer
				$abcd4[] = $taskcount2[$d];
				$aa1 = settype($abcd4[$d], "int");
				}
				
				//var_dump($abcd);
				}
				
				$response['message'] = 'success';
				$response['code'] = 200;
				$response['status'] = true;
				$response['data_e1'] = $abcd1;
				$response['month2'] = $month1;
				$response['data_e2'] = $abcd2;
				$response['data_e3'] = $abcd3;
				$response['data_e4'] = $abcd4;
				
				//print_r($enquiry_id);
				echo json_encode($response);
				}
				
				}
				
				?>
