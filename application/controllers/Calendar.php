<?php

class Calendar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
    }
	public function get_location()
	{
		
		
		$latitude="18.9726493";
		$longitude="73.3699943";
		
		$geolocation = $latitude.','.$longitude;
echo $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false'; 
$file_contents = file_get_contents($request);
$json_decode = json_decode($file_contents);
if(isset($json_decode->results[0])) {
	
	
    $response = array();
    foreach($json_decode->results[0]->address_components as $addressComponet) {
        if(in_array('political', $addressComponet->types)) {
                $response[] = $addressComponet->long_name; 
        }
    }

    if(isset($response[0])){ $first  =  $response[0];  } else { $first  = 'null'; }
    if(isset($response[1])){ $second =  $response[1];  } else { $second = 'null'; } 
    if(isset($response[2])){ $third  =  $response[2];  } else { $third  = 'null'; }
    if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = 'null'; }
    if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = 'null'; }

    if( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null' ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$fourth;
        echo "<br/>Country:: ".$fifth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null'  ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$third;
        echo "<br/>Country:: ".$fourth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null' ) {
        echo "<br/>City:: ".$first;
        echo "<br/>State:: ".$second;
        echo "<br/>Country:: ".$third;
    }
    else if ( $first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>State:: ".$first;
        echo "<br/>Country:: ".$second;
    }
    else if ( $first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>Country:: ".$first;
    }
  }
	}
    public function hq_calendar($firm_id = '') {


        $query_event = $this->db->query("SELECT * from holiday_header_all where FIND_IN_SET('" . $firm_id . "', `holiday_applied_in`)");
        if ($query_event->num_rows() > 0) {
            $record_data = $query_event->result();
            $data['fetched_firm_id'] = $firm_id;
            $data['result_event'] = $record_data;
//            var_dump($result);
        } else {
            $data['result_event'] = "";
            $data['fetched_firm_id'] = "";
        }

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

        // $data['prev_title'] = "pre";
        $data['prev_title'] = "Calender";
        $data['page_title'] = "Calender";
        $this->load->view('hq_admin/Calendar', $data);
    }

    public function emp_calendar($firm_id = '') {
        $query_event = $this->db->query("SELECT * from holiday_header_all where FIND_IN_SET('" . $firm_id . "', `holiday_applied_in`)");
        if ($query_event->num_rows() > 0) {
            $record_data = $query_event->result();
            $data['fetched_firm_id'] = $firm_id;
            $data['result_event'] = $record_data;
//            var_dump($result);
        } else {
            $data['result_event'] = "";
            $data['fetched_firm_id'] = "";
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
//        $email_id = $this->session->userdata('login_session');
        $query = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
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

        // $data['prev_title'] = "pre";
        $data['prev_title'] = "Calendar";
        $data['page_title'] = "Calender";
        $this->load->view('employee/Calendar', $data);
    }

    public function ca_calendar() {
        $result2 = $this->customer_model->get_firm_id();
        if ($result2 !== false) {
            $firm_id = $result2['firm_id'];
        }
        $query_event = $this->db->query("SELECT * from holiday_header_all where FIND_IN_SET('" . $firm_id . "', `holiday_applied_in`)");
        if ($query_event->num_rows() > 0) {
            $record_data = $query_event->result();
            $data['result_event'] = $record_data;
//            var_dump($result);
        } else {
            $data['result_event'] = "";
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


        // $data['prev_title'] = "pre";
        $data['prev_title'] = "Calendar";
        $data['page_title'] = "Calender";
        $this->load->view('client_admin/Calendar', $data);
    }

    public function delete_event() {
        $event_id = $this->input->post('event_id');
        $firm_id = $this->input->post('firm_id');
        $qry = $this->db->query("SELECT * from holiday_header_all where FIND_IN_SET('" . $firm_id . "', `holiday_applied_in`) and holiday_id='$event_id'");
        if ($qry->num_rows() > 0) {
            $result = $qry->row();
            $firm_temp[] = $firm_id;
            $temp_data = $result->holiday_applied_in;
            $file_name = $result->holiday_image;

            if (strpos($temp_data, ',') !== false) {
                $temp_data . "comma found";
                $firm_data = $this->removeFromString($temp_data, $firm_id);
                $data = array(
                    'holiday_applied_in' => $firm_data,
                );
                $this->db->where("holiday_id='$event_id'");
                $qry_upd_cust = $this->db->update('holiday_header_all', $data);
                if ($this->db->affected_rows()) {
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            } else {
                $file_pointer = base_url() . '/uploads/gallery/' . $file_name;

//                if (!unlink($file_pointer)) {
                //echo $temp_data . "comma  not found";
                $data = array(
                    'holiday_applied_in' => $temp_data
                );

                $this->db->where("holiday_id='$event_id'");
                $query = $this->db->delete('holiday_header_all');
                if ($query == 1) {
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
//                } else {
                //echo $temp_data . "comma  not found";
                $data = array(
                    'holiday_applied_in' => $temp_data
                );

                $this->db->where("holiday_id='$event_id'");
                $query = $this->db->delete('holiday_header_all');
                if ($query == 1) {
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
//            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    function removeFromString($str, $item) {
        $parts = explode(',', $str);
        while (($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }
        return implode(',', $parts);
    }

    public function update_event() {
        $event_id = base64_decode($this->input->post('event_id1'));
        $event_name = $this->input->post('edit_event_name');
        $event_m_date = $this->input->post('edit_main_date');
//        $event_s_date = $this->input->post('edit_start_date');
//        $event_e_date = $this->input->post('edit_end_date');
//        $event_descri = $this->input->post('description_edit');
        $files = $this->edit_image();
        if ($files == '') {
            $query_update_event = $this->db->query("UPDATE `holiday_header_all` set `holiday_name`='$event_name',`holiday_date`='$event_m_date' WHERE `holiday_id`='$event_id'");
        } else {
            $query_update_event = $this->db->query("UPDATE `holiday_header_all` set `holiday_image` = '$files', `holiday_name`='$event_name',`holiday_date`='$event_m_date' WHERE `holiday_id`='$event_id'");
        }
        if ($this->db->affected_rows() > 0 && $files !== 'invalid') {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            echo json_encode($response);
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
            echo json_encode($response);
        }
    }

    public function edit_image() {
//echo $ddl_due_date_name;
        $response = array();
//        $user_id = $event_id; // session or user_id  
        if (isset($_FILES['image_file1']) && $_FILES['image_file1']['error'] != '4') :
            $files = $_FILES;
//            $count = count($_FILES['FileUpload1']['name']); // count element 
//            for ($i = 0; $i < $count; $i++):
            $_FILES['image_file1']['name'] = $files['image_file1']['name'];
            $_FILES['image_file1']['type'] = $files['image_file1']['type'];
            $_FILES['image_file1']['tmp_name'] = $files['image_file1']['tmp_name'];
            $_FILES['image_file1']['error'] = $files['image_file1']['error'];
            $_FILES['image_file1']['size'] = $files['image_file1']['size'];
            $config['upload_path'] = './uploads/';
            $target_path = 'uploads/';

            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|ppt|pptx';
//                $config['allowed_types'] = 'pdf';
            $config['max_size'] = '500000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '800'; // image max width 
            $config['max_height'] = '532';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = $_FILES['image_file1']['name'];
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('image_file1');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['image_file1']['name'] . ' ' . $error['upload_error'];
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
//            endfor;
        endif;
    }

    public function fetch_event_data() {
        $event_id = base64_decode($this->input->post('event_id'));
        $query3 = $this->db->query("SELECT * from `holiday_header_all` where `holiday_id`='$event_id'");

        if ($query3->num_rows() > 0) {
            $record = $query3->result();
            $response['result_event_data'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
//            var_dump($response);
        } else {
            echo 'else';
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function add_event() {
//        var_dump($_POST);
        $user_id = $this->input->post('hdn_user_id');
        $query = $this->db->query("SELECT * FROM `user_header_all` where `created_by`= '$user_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $boss_id = $record->linked_with_boss_id;
            $firm_id = $record->firm_id;
        }
        $event_id = $this->generate_event_id();
        $event_name = $this->input->post('event');
        $main_date = $this->input->post('main_date');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $description = $this->input->post('description');
        $color = $this->input->post('color');
        $files = $this->upload_image($firm_id);
        $result_data = $this->search_same_event($start_date, $end_date);
        if (empty(trim($event_name))) {
            $response['id'] = 'event';
            $response['error'] = 'Please Enter Holiday Name';
        } elseif (empty($main_date)) {
            $response['id'] = 'main_date';
            $response['error'] = 'Please Select main Date of Holiday';
        } elseif (empty($start_date)) {
            $response['id'] = 'start_date';
            $response['error'] = 'Please Select start Date of Event';
        } elseif (empty($end_date)) {
            $response['id'] = 'end_date';
            $response['error'] = 'Please Select end Date of Event';
        } else if ($result_data !== false) {
            $response['id'] = 'end_date';
            $response['error'] = 'event for for this start and end date is already crated';
        } elseif (empty(trim($description))) {
            $response['id'] = 'description';
            $response['error'] = 'Please Enter Quote or Description';
        } elseif (empty($files)) {
            $response['id'] = 'image_file';
            $response['error'] = 'Please Select Image File';
        } else {
            $data = array(
                'event_id' => $event_id,
                'firm_id' => $firm_id,
                'boss_id' => $boss_id,
                'event_name' => $event_name,
                'description' => $description,
                'main_date' => $main_date,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'upload_image' => $files
            );
            $result = $this->db->insert('holiday_management_all', $data);

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
        echo json_encode($response);
    }

    public function add_event_hq() {
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
        $ddl_firm_name = $this->input->post('ddl_firm_name');
        $color = $this->input->post('color');
        $files = $this->upload_image_hq($event_name);
//        $start_date_check = date('Y-m-d', strtotime($start_date));
//        $end_date_check = date('Y-m-d', strtotime($end_date));
//        $result_data = $this->search_same_event($start_date, $end_date);
        $result_data = $this->search_same_events($main_date);

        if (empty($ddl_firm_name)) {
            $response['id'] = 'ddl_firm_name';
            $response['error'] = 'Please Select Firm';
        } elseif (empty(trim($event_name))) {
            $response['id'] = 'event';
            $response['error'] = 'Please Enter Holiday Name';
        } elseif (empty($main_date)) {
            $response['id'] = 'main_date';
            $response['error'] = 'Please Select main Date of Holiday';
//        } elseif (empty($start_date)) {
//            $response['id'] = 'start_date';
//            $response['error'] = 'Please Select start Date of Event';
        } 
//        elseif (empty($end_date)) {
//            $response['id'] = 'end_date';
//            $response['error'] = 'Please Select end Date of Event';
//        } else if ($result_data !== false) {
//            $response['id'] = 'end_date';
//            $response['error'] = 'Holiday for this date is already exist';
////        } elseif (empty($description)) {
////            $response['id'] = 'description';
////            $response['error'] = 'Please Enter Quote or Description';
//        }
        elseif (empty($files)) {
            $response['id'] = 'image_file';
            $response['error'] = 'Please Select Image File';
        } else {

            $ddl_firm_name_add = implode(",", $ddl_firm_name);


            $event_id = $this->generate_event_id();

            $data = array(
                'holiday_id' => $event_id,
                'holiday_applied_in' => $ddl_firm_name_add,
                'holiday_name' => $event_name,
//                    'description' => $description,
                'color' => $color,
                'holiday_date' => $main_date,
                'end_date' => $main_date,
//                    'start_date' => $start_date,
//                    'end_date' => $end_date,
                'holiday_image' => $files
            );
            //print_r($data);
            $result = $this->db->insert('holiday_header_all', $data);

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
        echo json_encode($response);
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

    public function generate_event_id() {
        $result = $this->db->query('SELECT holiday_id FROM `holiday_header_all` ORDER BY holiday_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $event_id = $data->holiday_id;
            //generate user_id
            $event_id = str_pad( ++$event_id, 5, '0', STR_PAD_LEFT);
            return $event_id;
        } else {
            $event_id = 'holiday_1001';
            return $event_id;
        }
    }

    public function upload_image($firm_id) {
//echo $ddl_due_date_name;
        $response = array();
        $user_id = $firm_id; // session or user_id   
//        var_dump($_FILES);
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] != '4') :
            $files = $_FILES;
//            $count = count($_FILES['FileUpload1']['name']); // count element 
//            for ($i = 0; $i < $count; $i++):
            $_FILES['image_file']['name'] = $files['image_file']['name'];
            $_FILES['image_file']['type'] = $files['image_file']['type'];
            $_FILES['image_file']['tmp_name'] = $files['image_file']['tmp_name'];
            $_FILES['image_file']['error'] = $files['image_file']['error'];
            $_FILES['image_file']['size'] = $files['image_file']['size'];
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
            $fileName = $_FILES['image_file']['name'];
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('image_file');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['image_file']['name'] . ' ' . $error['upload_error'];
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

    public function upload_image_hq($enquiry_id) {
//echo $ddl_due_date_name;
        $response = array();
        $user_id = $enquiry_id; // session or user_id   
//        var_dump($_FILES);
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] != '4') :
            $files = $_FILES;
//            $count = count($_FILES['FileUpload1']['name']); // count element 
//            for ($i = 0; $i < $count; $i++):
            $_FILES['image_file']['name'] = $files['image_file']['name'];
            $_FILES['image_file']['type'] = $files['image_file']['type'];
            $_FILES['image_file']['tmp_name'] = $files['image_file']['tmp_name'];
            $_FILES['image_file']['error'] = $files['image_file']['error'];
            $_FILES['image_file']['size'] = $files['image_file']['size'];
            $config['upload_path'] = './uploads/gallery/';
            $target_path = './uploads/gallery/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|ppt|pptx';
//                $config['allowed_types'] = 'pdf';
            $config['max_size'] = '500000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '800'; // image max width 
            $config['max_height'] = '532';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = $_FILES['image_file']['name'];
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('image_file');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['image_file']['name'] . ' ' . $error['upload_error'];
                    $response = "invalid";
//                    $response = $target_path;
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

//    public function upload_image_hq($event_name) {
////echo $ddl_due_date_name;
//        $response = array();
//
//        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] != '4') :
//            $files = $_FILES;
//            $count = count($_FILES['image_file']['name']); // count element 
//            for ($i = 0; $i < $count; $i++):
//                $_FILES['image_file']['name'] = $files['image_file']['name'][$i];
//                $_FILES['image_file']['type'] = $files['image_file']['type'][$i];
//                $_FILES['image_file']['tmp_name'] = $files['image_file']['tmp_name'][$i];
//                $_FILES['image_file']['error'] = $files['image_file']['error'][$i];
//                $_FILES['image_file']['size'] = $files['image_file']['size'][$i];
//                $config['upload_path'] = './uploads/';
//                $target_path = 'uploads/';
//                $config['allowed_types'] = 'gif|jpg|png|jpeg';
//                //$config['allowed_types'] = 'pdf';
//                $config['max_size'] = '500000';    //limit 10000=1 mb
//                $config['remove_spaces'] = true;
//                $config['overwrite'] = false;
////                $config['max_width'] = '1024'; // image max width 
////                $config['max_height'] = '768';
//                $this->load->library('upload', $config);
//                $this->upload->initialize($config);
//                $fileName = $_FILES['image_file']['name'];
//                $data = array('upload_data' => $this->upload->data());
//                if (empty($fileName)) {
//                    return false;
//                } else {
//                    $file = $this->upload->do_upload('image_file');
//                    if (!$file) {
//                        $error = array('upload_error' => $this->upload->display_errors());
//                        $response['error'] = $files['image_file']['name'][$i] . ' ' . $error['upload_error'];
//                        $response = "invalid";
//                        return $response;
//                    } else {
//
//                        return $target_path . "/" . $fileName;
//                        // resize code
//                        $path = $data['upload_data']['full_path'];
//                        $q['name'] = $data['upload_data']['file_name'];
//                        $configi['image_library'] = 'gd2';
//                        $configi['source_image'] = $path;
//                        $configi['new_image'] = $target_path;
//                        $configi['create_thumb'] = TRUE;
//                        $configi['maintain_ratio'] = TRUE;
//                        $configi['width'] = 75; // new size
//                        $configi['height'] = 50;
//                        $this->load->library('image_lib');
//                        $this->image_lib->clear();
//                        $this->image_lib->initialize($configi);
//                        $this->image_lib->resize();
//                        $images[] = $fileName;
//                    }
//                }
//            endfor;
//        endif;
//    }

    public function search_same_event($start_date, $end_date) {

        $result = $this->db->query("SELECT start_date,end_date FROM `holiday_management_all` WHERE (start_date BETWEEN '$start_date' AND '$end_date') OR 
                                                (end_date BETWEEN '$start_date' AND '$end_date') OR 
                                                (start_date <= '$start_date' AND end_date >= '$end_date')");
        if ($result->num_rows() > 0) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    public function search_same_events($main_date) {

        $result = $this->db->query("SELECT holiday_date FROM `holiday_header_all` WHERE holiday_date = '$main_date'");
        if ($result->num_rows() > 0) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

}

?>
