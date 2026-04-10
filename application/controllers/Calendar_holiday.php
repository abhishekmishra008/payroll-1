<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_holiday extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->table = 'holiday_header_all';
        $this->load->model('Globalmodel', 'modeldb');
        $this->load->model('customer_model');
    }

    public function index() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $data_calendar = $this->modeldb->get_list($this->table);
        $calendar = array();


        foreach ($data_calendar as $key => $val) {
            $calendar[] = array(
                'id' => intval($val->id),
                'title' => $val->holiday_name,
                'description' => trim($val->description),
                'start' => date_format(date_create($val->holiday_date), "Y-m-d H:i:s"),
                'end' => date_format(date_create($val->end_date), "Y-m-d H:i:s"),
                'color' => $val->color,
                'holiday_image' => $val->holiday_image
            );
        }



        $data = array();
        $data['get_data'] = json_encode($calendar);
        $data['get_week_off'] = json_encode($week_off);
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

        $data['prev_title'] = "Calendar Holiday";
        $data['page_title'] = "Calendar Holiday";
        $this->load->view('hq_admin/calender_holiday', $data);
    }

    public function hq_holiday($firm_id = '') {
//        $result1 = $this->customer_model->get_firm_id();
//        if ($result1 !== false) {
//            $firm_id = $result1['firm_id'];
//        }

        $where = "FIND_IN_SET('" . $firm_id . "', holiday_applied_in)";
        $data_calendar = $this->modeldb->ca_get_list($this->table, $where);
        $calendar = array();

        foreach ($data_calendar as $key => $val) {
            $calendar[] = array(
                'id' => intval($val->id),
                'title' => $val->holiday_name,
                'description' => trim($val->description),
                'start' => date_format(date_create($val->holiday_date), "Y-m-d H:i:s"),
                'end' => date_format(date_create($val->end_date), "Y-m-d H:i:s"),
                'color' => $val->color,
                'holiday_image' => $val->holiday_image
            );
        }


        //var_dump($calendar);

        $data = array();
        $data['get_data'] = json_encode($calendar);

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
        $data['prev_title'] = "Calendar Holiday";
        $data['page_title'] = "Calendar Holiday";
        $this->load->view('hq_admin/calender_holiday', $data);
    }

    public function ca_calendar_holiday() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }

        $this->db->select('week_holiday,firm_id');
        $this->db->from('partner_header_all');
        $data = array('firm_id' => $firm_id);
        $this->db->where($data);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $week_holiday = $result->week_holiday;
        } else {
            $week_holiday = 'sun#all';
        }



        $where = "FIND_IN_SET('" . $firm_id . "', holiday_applied_in)";
        $data_calendar = $this->modeldb->ca_get_list($this->table, $where);
        $calendar = array();

        foreach ($data_calendar as $key => $val) {
            $holiday_image = $val->holiday_image;
            $calendar[] = array(
                'id' => intval($val->id),
                'title' => $val->holiday_name,
                'description' => trim($val->description),
                'start' => date_format(date_create($val->holiday_date), "Y-m-d H:i:s"),
                'end' => date_format(date_create($val->end_date), "Y-m-d H:i:s"),
                'color' => $val->color,
                'holiday_image' => $holiday_image,
            );
        }
        $week_off[] = array('week_day' => 'sun#all,sat#2:4');

        $data = array();
        $data['get_data'] = json_encode($calendar);
        $data['week_off'] = json_encode($week_off);
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

        $data['week_holiday'] = $week_holiday;
        $data['prev_title'] = "Calendar Holiday";
        $data['page_title'] = "Calendar Holiday";

        $this->load->view('client_admin/calendar_holiday', $data);
    }
	
	public function get_ddl_firm_name() {
		 $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
	
		//echo $user_id;
        $query_get_services = $this->db->query("select firm_name,firm_id,reporting_to,boss_id from partner_header_all ");
        if ($query_get_services->num_rows() > 0) {

            foreach ($query_get_services->result() as $row) {
				 $firm_name =$row->firm_name;
                $response['frim_data'][] = ['firm_name' => $row->firm_name, 'firm_id' => $row->firm_id, 'reporting_to' => $row->reporting_to, 'boss_id' => $row->boss_id];
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }
	
	

    public function save() {

        // var_dump($_POST);
        // $user_id = $this->input->post('user_id');
		$session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $query = $this->db->query("SELECT boss_id ,firm_id FROM `user_header_all` where `email`= '$user_id'");
		 $this->db->last_query();
        if ($query->num_rows() > 0) {

            $record = $query->row();
           //echo $boss_id = $record->linked_with_boss_id;
            $firm_id = $record->firm_id;
        }
		//echo $firm_id;
        $color = $this->input->post('color');
        $event_name = $this->input->post('holiday_name');
		$main_date = $this->input->post('main_date');
		$end_date = $this->input->post('end_date');
	  
		
		
		
        $ddl_firm_name = $this->input->post('ddl_firm_name_add');

      $result_data = $this->search_same_events($main_date);


        //$ddl_firm_name_add = implode(",", $ddl_firm_name);
        $event_id = $this->generate_event_id();
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] != '4') {
            $all_files = $_FILES['image_file']['name'];
            $type_of_file = pathinfo($all_files, PATHINFO_EXTENSION);
            $type_of_file_array = explode(',', $type_of_file);


            $allowed = array('jpg', 'jpeg', 'gif', 'png');
            $valid_file_type_result = array_diff($type_of_file_array, $allowed);

            if ($all_files == "") {
                $response['id'] = 'image_file';
                $response['error'] = 'Please Select file';
            } else if (count($valid_file_type_result) > 0) {
                $response['id'] = 'image_file';
                $response['error'] = 'Invalid file';
                //echo"Invalid file";
            } else {

                if (file_exists('./uploads/gallery/' . $all_files)) {

                    $all_image = date('dmYHis') . str_replace(" ", "", basename($all_files));
                } else {

                    $all_image = $all_files[$valid_file_type_result];
                }
            //    echo"" . $all_image;
              //  exit();
//                $count_of_valid_file_type_result = count($valid_file_type_result);

                $uploadPath = './uploads/gallery/';

                if (file_exists('./uploads/gallery/' . $all_files)) {

                    $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['image_file']['name']));
                    //  $tmp_name =date('dmY') .$_FILES['file_upload']['tmp_name'][$k];
                    move_uploaded_file($_FILES["image_file"]["tmp_name"], './uploads/gallery/' . $newfilename);
                } else {
                    $tmp_name = $_FILES['image_file']['tmp_name'];
                    move_uploaded_file($tmp_name, $uploadPath . $_FILES['image_file']['name']);
                }

                $data = array(
                    'holiday_id' => $event_id,
                    'holiday_applied_in' => $firm_id,
                    'holiday_name' => $event_name,
//                    'description' => $description,
                    'color' => $color,
                    'holiday_date' => $main_date,
                    'end_date' => $main_date,
//                    'start_date' => $start_date,
                  'end_date' => $end_date,
                    'holiday_image' => $all_image
                );

                $result = $this->db->insert('holiday_header_all', $data);
				//  $result11=$this->db->query("update holiday_header_all SET holiday_name='$event_name' where holiday_applied_in='$firm_id'");
			//	echo $this->db->last_query();

               // if ($this->db->affected_rows()>0) {
               if ($result == TRUE) {
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
    }
	
	public function update_event(){
		$session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
		  $query = $this->db->query("SELECT boss_id ,firm_id FROM `user_header_all` where `email`= '$user_id'");
		 $this->db->last_query();
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_id = $record->firm_id;
        }
		$query11 = $this->db->query("SELECT holiday_id ,holiday_date,holiday_applied_in FROM `holiday_header_all` where `holiday_applied_in`= '$firm_id'");
		 $this->db->last_query();
        if ($query11->num_rows() > 0) {

            $record = $query11->row();
            $firm_id = $record->holiday_applied_in;
            $holiday_date = $record->holiday_date;
            $holiday_id = $record->holiday_id;
        }
        $color = $this->input->post('color');
        $event_name = $this->input->post('holiday_name');
		$main_date = $this->input->post('main_date');
		$end_date = $this->input->post('end_date');
		//echo "update test";
		 if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] != '4') {
            $all_files = $_FILES['image_file']['name'];
            $type_of_file = pathinfo($all_files, PATHINFO_EXTENSION);
            $type_of_file_array = explode(',', $type_of_file);


            $allowed = array('jpg', 'jpeg', 'gif', 'png');
            $valid_file_type_result = array_diff($type_of_file_array, $allowed);

            if ($all_files == "") {
                $response['id'] = 'image_file';
                $response['error'] = 'Please Select file';
            } else if (count($valid_file_type_result) > 0) {
                $response['id'] = 'image_file';
                $response['error'] = 'Invalid file';
                //echo"Invalid file";
            } else {

                if (file_exists('./uploads/gallery/' . $all_files)) {

                    $all_image = date('dmYHis') . str_replace(" ", "", basename($all_files));
                } else {

                    $all_image = $all_files[$valid_file_type_result];
                }
            //    echo"" . $all_image;
              //  exit();
//                $count_of_valid_file_type_result = count($valid_file_type_result);

                $uploadPath = './uploads/gallery/';

                if (file_exists('./uploads/gallery/' . $all_files)) {

                    $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['image_file']['name']));
                    //  $tmp_name =date('dmY') .$_FILES['file_upload']['tmp_name'][$k];
                    move_uploaded_file($_FILES["image_file"]["tmp_name"], './uploads/gallery/' . $newfilename);
                } else {
                    $tmp_name = $_FILES['image_file']['tmp_name'];
                    move_uploaded_file($tmp_name, $uploadPath . $_FILES['image_file']['name']);
                }

                $data = array(
                   // 'holiday_id' => $event_id,
                    'holiday_applied_in' => $firm_id,
                    'holiday_name' => $event_name,
//                    'description' => $description,
                    'color' => $color,
                    'holiday_date' => $main_date,
                    'end_date' => $main_date,
//                    'start_date' => $start_date,
                  'end_date' => $end_date,
                    'holiday_image' => $all_image
                );

               
				  $result11=$this->db->query("update holiday_header_all SET holiday_name='$event_name',color='$color',holiday_date='$main_date' where  holiday_date='$main_date' AND holiday_id='$holiday_id'");
				echo $this->db->last_query();

                if ($this->db->affected_rows()>0) {
               //if ($result == TRUE) {
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
		
	}


	
    public function add_event_emp() {
//        var_dump($_POST);
        $user_id = $this->input->post('hdn_user_id');
        $query = $this->db->query("SELECT * FROM `user_header_all` where `created_by`= '$user_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $boss_id = $record->linked_with_boss_id;
        }

        $event_name = $this->input->post('event');
        $main_date = $this->input->post('main_date');
//        $start_date = $this->input->post('start_date');
//        $end_date = $this->input->post('end_date');
//        $description = $this->input->post('description');
        $result2 = $this->customer_model->get_firm_id();
        if ($result2 !== false) {
            $firm_id = $result2['firm_id'];
        }
        $color = $this->input->post('color');
        $files = $this->upload_image_hq($event_name);

//        $start_date_check = date('Y-m-d', strtotime($start_date));
//        $end_date_check = date('Y-m-d', strtotime($end_date));
//        $result_data = $this->search_same_event($start_date, $end_date);
        $result_data = $this->search_same_events($main_date);

        if (empty(trim($event_name))) {
            $response['id'] = 'event';
            $response['error'] = 'Please Enter Holiday Name';
        } elseif (empty($main_date)) {
            $response['id'] = 'main_date';
            $response['error'] = 'Please Select main Date of Holiday';
//        } elseif (empty($start_date)) {
//            $response['id'] = 'start_date';
//            $response['error'] = 'Please Select start Date of Event';
//        } elseif (empty($end_date)) {
//            $response['id'] = 'end_date';
//            $response['error'] = 'Please Select end Date of Event';
        } else if ($result_data !== false) {
            $response['id'] = 'end_date';
            $response['error'] = 'Holiday for this date is already exist';
//        } elseif (empty($description)) {
//            $response['id'] = 'description';
//            $response['error'] = 'Please Enter Quote or Description';
        } elseif (empty($files)) {
            $response['id'] = 'image_file';
            $response['error'] = 'Please Select Image File';
        } else {



            $event_id = $this->generate_event_id();



            $data = array(
                'holiday_id' => $event_id,
                'holiday_applied_in' => $firm_id,
                'boss_id' => $boss_id,
                'holiday_name' => $event_name,
//                    'description' => $description,
                'color' => $color,
                'holiday_date' => $main_date,
                'end_date' => $main_date,
//                    'start_date' => $start_date,
//                    'end_date' => $end_date,
                'holiday_image' => $files
            );
            $result = $this->db->insert('holiday_header_all', $data);

            if ($result == TRUE) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                //echo json_encode($response);
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
                //echo json_encode($response);
            }
        }

        echo json_encode($response);
    }

    public function delete() {
        $response = array();
        $calendar_id = $this->input->post('id');
        if (!empty($calendar_id)) {
            $where = ['id' => $calendar_id];
            $delete = $this->modeldb->delete($this->table, $where);

            if ($delete > 0) {
                $response['status'] = TRUE;
                $response['notif'] = 'Success delete calendar';
            } else {
                $response['status'] = FALSE;
                $response['notif'] = 'Server wrong, please save again';
            }
        } else {
            $response['status'] = FALSE;
            $response['notif'] = 'Data not found';
        }

        echo json_encode($response);
    }

    public function generate_event_id() {
        $result = $this->db->query('SELECT holiday_id FROM `holiday_header_all` ORDER BY holiday_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $event_id = $data->holiday_id;
            //generate user_id
            $event_id = str_pad( ++$event_id, 5, '0', STR_PAD_LEFT);
            return $event_id;
        } else {
            $event_id = 'Calendar_1001';
            return $event_id;
        }
    }

   /* public function get_ddl_firm_name() {
        $query_get_services = $this->db->query("select firm_name,firm_id,reporting_to,boss_id from partner_header_all ");
        if ($query_get_services->num_rows() > 0) {

            foreach ($query_get_services->result() as $row) {

                $response['frim_data'][] = ['firm_name' => $row->firm_name, 'firm_id' => $row->firm_id, 'reporting_to' => $row->reporting_to, 'boss_id' => $row->boss_id];
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }*/

    public function upload_multiple($holiday_name) {
//echo $ddl_due_date_name;
        $response = array();

        if (isset($_FILES['holiday_image']) && $_FILES['holiday_image']['error'] != '4') :
            $files = $_FILES;
            $count = count($_FILES['holiday_image']['name']); // count element
            for ($i = 0; $i < $count; $i++):
                $_FILES['holiday_image']['name'] = $files['holiday_image']['name'][$i];
                $_FILES['holiday_image']['type'] = $files['holiday_image']['type'][$i];
                $_FILES['holiday_image']['tmp_name'] = $files['holiday_image']['tmp_name'][$i];
                $_FILES['holiday_image']['error'] = $files['holiday_image']['error'][$i];
                $_FILES['holiday_image']['size'] = $files['holiday_image']['size'][$i];
                $config['upload_path'] = './uploads/';
                $target_path = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                //$config['allowed_types'] = 'pdf';
                $config['max_size'] = '500000';    //limit 10000=1 mb
                $config['remove_spaces'] = true;
                $config['overwrite'] = false;
//                $config['max_width'] = '1024'; // image max width
//                $config['max_height'] = '768';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                $fileName = $_FILES['holiday_image']['name'];
                $fileName = str_replace(' ', '_', $fileName);
                $fileName = preg_replace('/\s+/', '_', $fileName);
                $data = array('upload_data' => $this->upload->data());
                if (empty($fileName)) {
                    return false;
                } else {
                    $file = $this->upload->do_upload('holiday_image');
                    if (!$file) {
                        $error = array('upload_error' => $this->upload->display_errors());
                        $response['error'] = $files['holiday_image']['name'][$i] . ' ' . $error['upload_error'];
                        $response = "invalid";
                        return $response;
                    } else {
                        return $target_path . "/" . $fileName;
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
            endfor;
        endif;
    }
	
	public function search_same_events($main_date) {

        $result = $this->db->query("SELECT holiday_date FROM `holiday_header_all` WHERE holiday_date = '$main_date'");
        if ($result->num_rows() > 0) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    public function emp_calendar_holiday() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }

        $this->db->select('week_holiday,firm_id');
        $this->db->from('partner_header_all');
        $data = array('firm_id' => $firm_id);
        $this->db->where($data);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $week_holiday = $result->week_holiday;
        } else {
            $week_holiday = 'sun#all';
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


        $where = "FIND_IN_SET('" . $firm_id . "', holiday_applied_in)";
        $data_calendar = $this->modeldb->ca_get_list($this->table, $where);
        $calendar = array();

        foreach ($data_calendar as $key => $val) {
            $calendar[] = array(
                'id' => intval($val->id),
                'title' => $val->holiday_name,
                'description' => trim($val->description),
                'start' => date_format(date_create($val->holiday_date), "Y-m-d H:i:s"),
                'end' => date_format(date_create($val->end_date), "Y-m-d H:i:s"),
                'color' => $val->color,
                'holiday_image' => $val->holiday_image
            );
        }

        //var_dump($calendar);

        $data = array();
        $data['get_data'] = json_encode($calendar);
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


        $data['week_holiday'] = $week_holiday;
        $data['prev_title'] = "Calendar Holiday";
        $data['page_title'] = "Calendar Holiday";

        $this->load->view('employee/calendar_holiday', $data);
    }

}
