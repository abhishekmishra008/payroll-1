<?php

class DashboardController extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database(); // Ensure database is loaded
        $this->load->model('customer_model');
        $this->load->model('Globalmodel');
        $this->load->helper('eloqunt_helper');
        $this->load->helper('dump_helper');
        $this->load->helper('user_address_helper');
    }

    public function index() {
        $data['prev_title'] = "Calendar";
        $data['page_title'] = "Calendar";
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $boss_id = '';
        if ($user_id == '') {
            redirect(base_url());
        }
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `user_id`='$user_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $e_user_id = $record->user_id;
            $senior_id = $record->senior_user_id;
            $designation_id = $record->designation_id;
            $user_type = $record->user_type;
            if ($user_type == 5) {
                $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
                $res = $qr->row();
                $firm_id = $res->hr_authority;
            } else {
                $firm_id = $record->firm_id;
            }


            $qur1 = $this->db->query("SELECT COUNT(`user_id`) as count , `firm_id` FROM `user_header_all` WHERE `firm_id`='$firm_id' GROUP BY `firm_id`");
            if ($qur1->num_rows() > 0) {
                $record1 = $qur1->row();
                $cnt = $record1->count;
            }

            $query_fetch_boss_id = $this->db->query("SELECT * FROM partner_header_all where firm_id='$firm_id'");
            if ($query_fetch_boss_id->num_rows() > 0) {
                $record_fetch_boss_id = $query_fetch_boss_id->row();
                $boss_id = $record_fetch_boss_id->reporting_to;
            } else {
                $record_fetch_boss_id = '';
            }
            $qur2 = $this->db->query("SELECT senior_user_id FROM `user_header_all` WHERE `user_id`='$user_id' AND `firm_id`='$firm_id'");

            if ($this->db->affected_rows() > 0) {
                $record2 = $qur2->row();
                $senior_emp_id = $record2->senior_user_id;

                $qur3 = $this->db->query("SELECT user_name FROM `user_header_all` WHERE `user_id`='$senior_emp_id' ");
                if ($this->db->affected_rows() > 0) {
                    $record3 = $qur3->row();
                    $senior_name = $record3->user_name;
                } else {
                    $senior_name = "";
                }
            } else {
                $senior_emp_id = "";
                $senior_name = "";
            }
        }
        // echo $user_id;
        $query_fetch_leave = $this->db->query("SELECT * FROM `leave_transaction_all` WHERE `user_id`='$user_id'");
        if ($query_fetch_leave->num_rows() > 0) {
            $record_fetch_leave = $query_fetch_leave->result();
        } else {
            $record_fetch_leave = '';
        }

        $data_holiday = $this->get_all_holidays();
        if ($data_holiday !== FALSE) {
            $data['data_holiday'] = $data_holiday;
        } else {
            $data['data_holiday'] = '';
        }

        $data['user_id'] = $user_id;
        $data['boss_id'] = $boss_id;
        $data['firm_id'] = $firm_id;
        $data['result'] = $record_fetch_leave;
        $data['senior_id'] = $senior_emp_id;
        $data['senior_name'] = $senior_name;
        $data['designation_id'] = $designation_id;
        // $this->load->view('human_resource/Calendar_dashboard', $data); //old
        $this->load->view('human_resource/Calendar_new', $data); //change by pooja
    }

    public function userDashboard() {
        $data['prev_title'] = "Calendar";
        $data['page_title'] = "Calendar";
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $boss_id = '';
        if ($user_id == '') {
            redirect(base_url());
        }
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `user_id`='$user_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $e_user_id = $record->user_id;
            $senior_id = $record->senior_user_id;
            $designation_id = $record->designation_id;
            $user_type = $record->user_type;
            if ($user_type == 5) {
                $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
                $res = $qr->row();
                $firm_id = $res->hr_authority;
            } else {
                $firm_id = $record->firm_id;
            }


            $qur1 = $this->db->query("SELECT COUNT(`user_id`) as count , `firm_id` FROM `user_header_all` WHERE `firm_id`='$firm_id' GROUP BY `firm_id`");
            if ($qur1->num_rows() > 0) {
                $record1 = $qur1->row();
                $cnt = $record1->count;
            }

            $query_fetch_boss_id = $this->db->query("SELECT * FROM partner_header_all where firm_id='$firm_id'");
            if ($query_fetch_boss_id->num_rows() > 0) {
                $record_fetch_boss_id = $query_fetch_boss_id->row();
                $boss_id = $record_fetch_boss_id->reporting_to;
            } else {
                $record_fetch_boss_id = '';
            }
            $qur2 = $this->db->query("SELECT senior_user_id FROM `user_header_all` WHERE `user_id`='$user_id' AND `firm_id`='$firm_id'");

            if ($this->db->affected_rows() > 0) {
                $record2 = $qur2->row();
                $senior_emp_id = $record2->senior_user_id;

                $qur3 = $this->db->query("SELECT user_name FROM `user_header_all` WHERE `user_id`='$senior_emp_id' ");
                if ($this->db->affected_rows() > 0) {
                    $record3 = $qur3->row();
                    $senior_name = $record3->user_name;
                } else {
                    $senior_name = "";
                }
            } else {
                $senior_emp_id = "";
                $senior_name = "";
            }
        }
        // echo $user_id;
        $query_fetch_leave = $this->db->query("SELECT * FROM `leave_transaction_all` WHERE `user_id`='$user_id'");
        if ($query_fetch_leave->num_rows() > 0) {
            $record_fetch_leave = $query_fetch_leave->result();
        } else {
            $record_fetch_leave = '';
        }

        $data_holiday = $this->get_all_holidays();
        if ($data_holiday !== FALSE) {
            $data['data_holiday'] = $data_holiday;
        } else {
            $data['data_holiday'] = '';
        }

        $data['user_id'] = $user_id;
        $data['boss_id'] = $boss_id;
        $data['firm_id'] = $firm_id;
        $data['result'] = $record_fetch_leave;
        $data['senior_id'] = $senior_emp_id;
        $data['senior_name'] = $senior_name;
        $data['designation_id'] = $designation_id;
        // $this->load->view('human_resource/Calendar_dashboard', $data); //old
        $this->load->view('user_dashboard', $data); //change by pooja
    }

	public function calendarnew() {
		$data['prev_title'] = "Calendar";
		$data['page_title'] = "Calendar";
		$session_data = $this->session->userdata('login_session');
		$user_id = ($session_data['emp_id']);
		$boss_id = '';
		if ($user_id == '') {
			redirect(base_url());

		}
		$result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `user_id`='$user_id'");
		if ($result2->num_rows() > 0) {
			$record = $result2->row();
			$e_user_id = $record->user_id;

			$senior_id = $record->senior_user_id;
			$designation_id = $record->designation_id;
			$user_type = $record->user_type;
			if ($user_type == 5) {
				$qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
				$res = $qr->row();
				$firm_id = $res->hr_authority;
			} else {
				$firm_id = $record->firm_id;
			}


			$qur1 = $this->db->query("SELECT COUNT(`user_id`) as count , `firm_id` FROM `user_header_all` WHERE `firm_id`='$firm_id' GROUP BY `firm_id`");
			if ($qur1->num_rows() > 0) {
				$record1 = $qur1->row();
				$cnt = $record1->count;
			}

			$query_fetch_boss_id = $this->db->query("SELECT * FROM partner_header_all where firm_id='$firm_id'");
			if ($query_fetch_boss_id->num_rows() > 0) {
				$record_fetch_boss_id = $query_fetch_boss_id->row();
				$boss_id = $record_fetch_boss_id->reporting_to;
			} else {
				$record_fetch_boss_id = '';
			}
			$qur2 = $this->db->query("SELECT senior_user_id FROM `user_header_all` WHERE `user_id`='$user_id' AND `firm_id`='$firm_id'");

			if ($this->db->affected_rows() > 0) {
				$record2 = $qur2->row();
				$senior_emp_id = $record2->senior_user_id;

				$qur3 = $this->db->query("SELECT user_name FROM `user_header_all` WHERE `user_id`='$senior_emp_id' ");
				if ($this->db->affected_rows() > 0) {
					$record3 = $qur3->row();
					$senior_name = $record3->user_name;
				} else {
					$senior_name = "";
				}
			} else {
				$senior_emp_id = "";
				$senior_name = "";
			}
		}
//        echo $user_id;
		$query_fetch_leave = $this->db->query("SELECT * FROM `leave_transaction_all` WHERE `user_id`='$user_id'");
		if ($query_fetch_leave->num_rows() > 0) {
			$record_fetch_leave = $query_fetch_leave->result();
		} else {
			$record_fetch_leave = '';
		}
		$data_holiday = $this->get_all_holidays();
		if ($data_holiday !== FALSE) {
			$data['data_holiday'] = $data_holiday;
		} else {
			$data['data_holiday'] = '';
		}

		$data['user_id'] = $user_id;
		$data['boss_id'] = $boss_id;
		$data['firm_id'] = $firm_id;
		$data['result'] = $record_fetch_leave;
		$data['senior_id'] = $senior_emp_id;
		$data['senior_name'] = $senior_name;
		$data['designation_id'] = $designation_id;
		$this->load->view('human_resource/Calendar_new', $data);
	}

    public function get_junior_employees()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        $option = '<option>Search by Employee</option>';

        if ($user_type == 4) {
            $option .= '<option value=' . $user_id . '>My Calendar</option>';
            $query = $this->db->query("select user_name,user_id from user_header_all where senior_user_id='$user_id' and activity_status=1");

        } else {
            $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
            if ($this->db->affected_rows() > 0) {
                $res = $qr->row();
                $firm_id = $res->hr_authority;
            } else {
                $firm_id = '';
            }
            $query = $this->db->query("select user_name,user_id from user_header_all where firm_id='$firm_id' and activity_status=1");
        }

        // echo $this->db->last_query   ;
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();

            foreach ($result as $row) {
                $option .= '<option value=' . $row->user_id . '>' . $row->user_name . '</option>';
            }
            $response['data'] = $option;
            $response['message'] = 'success';
            $response['result'] = $result;
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371000; // in meters
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c; // returns distance in meters
    }

    public function check_location_old() {
        $latitude_live = $this->input->post('latitude_live');
        $longitude_live = $this->input->post('longitude_live');
        // dd($latitude_live, $longitude_live);
        $result1=array();
        $qrr = $this->db->query("select lattitude,logitude,radius from firm_location");
        $inloc=0;
        if ($this->db->affected_rows() > 0) {
            $result = $qrr->result();
            foreach ($result as $item) {
                $key = $item->lattitude . '_' . $item->logitude;
                if (!isset($unique[$key])) {
                    $unique[$key] = true;
                    $result1[] = $item;
                }
            }

            foreach ($result1 as $obj) {
                $distance = $this->haversineDistance($latitude_live, $longitude_live, $obj->lattitude, $obj->logitude);
                if ($distance <= $obj->radius) {
                    $inloc = 1;
                    break;
                }
            }
           if($inloc == 1){
               $response['message'] = 'inside';
           } else {
               $response['message'] = "outside";
           }echo json_encode($response);
        }
    }

    public function check_location() {
        $latitude_live = (float)$this->input->post('latitude_live');
        $longitude_live = (float)$this->input->post('longitude_live');
        // dd($latitude_live, $longitude_live);
        $result1 = [];
        $unique = [];
        $qrr = $this->db->query("select lattitude,logitude,radius from firm_location");
        $inloc = 0;
        if ($this->db->affected_rows() > 0) {
            $result = $qrr->result();
            foreach ($result as $item) {
                $key = "{$item->lattitude}_{$item->logitude}";
                if (!isset($unique[$key])) {
                    $unique[$key] = true;
                    $result1[] = $item;
                }
            }

            foreach ($result1 as $obj) {
                $distance = $this->haversineDistance($latitude_live, $longitude_live, (float)$obj->lattitude, (float)$obj->logitude);
                if ($distance <= $obj->radius) {
                    $inloc = 1;
                    break;
                }
            }
            // dd($inloc);
            $response['message'] = ($inloc == 1) ? 'inside' : 'outside';
            echo json_encode($response);
        }
    }

    public function check_location1() {
        $result = $this->customer_model->get_firm_id();
        $firm_id = '';
        $user_id = '';
        if ($result !== false) {
            $firm_id = $result['firm_id'];
            $user_id = $result['user_id'];
        }
        $latitude_live = $this->input->post('latitude_live');
        $longitude_live = $this->input->post('longitude_live');
        $startdate = $this->input->post_get('startdate');
        //get branch location
        $cnt = 0;
        $firms = array();
        $employeeFirms = $this->db->query('select * from employee_firm_master where user_id = "' . $user_id . '"')->result();
        if (count($employeeFirms) > 0) {
            $firms[] = $firm_id;
            foreach ($employeeFirms as $row) {
                $firms[] = $row->firm_id;
            }
        } else {
            $firms[] = $firm_id;
        }

        foreach ($firms as $f) {
            $qrr = $this->db->query("select * from firm_location where firm_id='$f'");
            if ($this->db->affected_rows() > 0) {
                $result = $qrr->row();
                $firm_lattitude = $result->lattitude;
                $firm_longitude = $result->logitude;
                $radius = $result->radius;

                $distance = $this->twopoints_on_earth($firm_lattitude, $firm_longitude, $latitude_live, $longitude_live);
                $response['data'] = $firm_lattitude . "," . $firm_longitude . "," . $latitude_live . "," . $longitude_live;
                $distance_in_meter = $distance * 1609.344;
                if ($distance_in_meter <= $radius) { //Inside Punch
                    $cnt++;
                }
            }
        }

        $response['firms'] = $firms;
        if ($cnt > 0) {
            $response['message'] = 'inside';
        } else {
            $response['message'] = "outside";
        }
        echo json_encode($response);
    }

    public function emp_login_mbl() {
        // dd("Abhishek mishra : this function is deprecated. Please use employee attendence instead.");
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $status = $this->input->post('status');
        $login_type = $this->input->post('login_type');
        $latitude_live = $this->input->post('latitude_live');
        $longitude_live = $this->input->post('longitude_live');
        $shortaddress = $this->input->post('shortaddress');
        $address = $this->input->post('address');
        date_default_timezone_set('Asia/Kolkata');
        $todaydate = date('Y-m-d H:i:s');
        $todaydate1 = date('Y-m-d');
        $day = date('l', strtotime($todaydate1));
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        $check_holiday = $this->Globalmodel->check_Holiday($todaydate1, $user_id, $firm_id, $day);
        $check_holiday_permission = $this->Globalmodel->check_Holiday_permission($user_id);
        if ($check_holiday_permission != FALSE) {
            if ($check_holiday_permission == 1) { //approval not required from senior
                $holiday_approval_status = 1;
            } else if ($check_holiday_permission == 2) { //not having permission to work on holiday
                $holiday_approval_status = 2;
            } else { //approval  required from senior
                $holiday_approval_status = 0;
            }
        } else {
            $holiday_approval_status = 0;
        }
        if ($check_holiday == 1) {
            $qrr = $this->db->query("select alternate_id from holiday_master_all where date='$todaydate1' and firm_id='$firm_id' and is_alternate = 1");
            if ($this->db->affected_rows() > 0) {
                $res = $qrr->row();
                $alternate_id = $res->alternate_id;
                $checkUserAlt = $this->db->query('SELECT * FROM alternate_holiday_master where alternate_id = "' . $alternate_id . '" and user_id = "' . $user_id . '"')->result();
                if (count($checkUserAlt) > 0) {
                    // If this user is configured for alternate saturdays
                    $holiday = 1; //Holiday
                } else {
                    $holiday = 0;
                }
            } else {
                $holiday = 1;
            }
        } else {
            if ($day == 'Sunday') {
                $holiday = 1; //Holiday
            } else {
                $holiday = 0; //NO holiday
            }
        }
        /* $createDate = new DateTime($todaydate);
          $todaydate = $createDate->format('Y-m-d'); 
        */

        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }

        //get branch location
        $qrr = $this->db->query("select * from firm_location where firm_id='$firm_id'");
        if ($this->db->affected_rows() > 0) {
            $result = $qrr->row();
            $firm_lattitude = $result->lattitude;
            $firm_longitude = $result->logitude;
            $radius = $result->radius;
        }
        
        $firms = array();
        $employeeFirms = $this->db->query('select * from employee_firm_master where user_id = "' . $user_id . '"')->result();
        if (count($employeeFirms) > 0) {
            $firms[] = $firm_id;
            foreach ($employeeFirms as $row) {
                $firms[] = $row->firm_id;
            }
        } else {
            $firms[] = $firm_id;
        }
        
        $cnt = 0;
        $punch_regularised_status = 0;
        $regular_status = 0;
        foreach ($firms as $f) {
            $qrr = $this->db->query("select * from firm_location where firm_id='$f'");
            if ($this->db->affected_rows() > 0) {
                $result = $qrr->row();
                $firm_lattitude = $result->lattitude;
                $firm_longitude = $result->logitude;
                $radius = $result->radius;
                $distance = $this->twopoints_on_earth($firm_lattitude, $firm_longitude, $latitude_live, $longitude_live);
                $distance_in_meter = $distance * 1609.344;
                if ($distance_in_meter <= $radius) { //Inside Punch
                    $punch_regularised_status = 2;
                    $regular_status = 0;
                    $cnt++;
                } else { //Out Side Punch
                    $punch_regularised_status = 1;
                    $query = $this->db->query("select outside_punch_applicable from user_header_all where user_id='$user_id'");
                    if ($this->db->affected_rows() > 0) {
                        $ress = $query->row();
                        $outside_punch_applicable = $ress->outside_punch_applicable;
                    }
                    if ($outside_punch_applicable == 1) {
                        $regular_status = 1;
                    } else {
                        $regular_status = 0;
                    }
                }
            }
        }
        
        if($cnt > 0){
            $punch_regularised_status = 2;
            $regular_status = 0;
        }

        $check_request_leave = $this->db->query("select date from employee_attendance_leave where date ='$todaydate1' AND `user_id`= '$user_id'");
        
        if ($this->db->affected_rows() > 0) {
            if ($status == 'login') {
                //where array
                $data = array(
                    'user_id' => $user_id,
                    'date' => $todaydate1,
                );
                //update array
                $arr = array('punch_in' => $todaydate, 'shortinaddress' => $shortaddress,
                    'longinaddress' => $address, 'punchin_lat' => $latitude_live, 'punchin_long' => $longitude_live, 'is_holiday' => $holiday,
                    'holiday_approval_status' => $holiday_approval_status, 'regular_status' => $regular_status,
                    'leave_status' => 0 , 'selectLoginType'=> $login_type);
                $this->db->set($arr);
                $this->db->where($data);
                $result = $this->db->update('employee_attendance_leave');
                //echo $this->db->last_query();
                if ($this->db->affected_rows() > 0) {
                    $query = $this->db->query("UPDATE `leave_transaction_all` SET `status`='4' where leave_date='$todaydate1' and user_id='$user_id'");
                    $response['message'] = 'success';
                    $response['body'] = 'Login Successful';
                } else {
                    $response['message'] = 'fail';
                    $response['body'] = 'Something went wrong';
                }
            } else {
                $data = array(
                    'user_id' => $user_id,
                    'punch_out' => 0,
                    'date' => $todaydate1,
                );
                $arr = array('punch_out' => $todaydate, 'punch_regularised_status' => $punch_regularised_status, 'punchout_lat' => $latitude_live,
                    'punchout_long' => $longitude_live, 'shortoutaddress' => $shortaddress,
                    'longoutaddress' => $address ,'selectLogoutType' => $login_type);
                $this->db->set($arr);
                $this->db->where($data);
                $result = $this->db->update('employee_attendance_leave');

                if ($this->db->affected_rows() > 0) {
                    $response['message'] = 'success';
                    $response['body'] = 'Logout Successful';
                } else {
                    $response['message'] = 'fail';
                    $response['body'] = 'Something went wrong';
                }
            }
        } else {
            if ($status == 'login') {
                $data = array(
                    'date' => $todaydate1,
                    'punch_in' => $todaydate,
                    'user_id' => $user_id,
                    'firm_id' => $firm_id,
                    'punchin_lat' => $latitude_live,
                    'leave_status' => 0,
                    'is_holiday' => $holiday,
                    'holiday_approval_status' => $holiday_approval_status,
                    'punchin_long' => $longitude_live,
                    'regular_status' => $regular_status,
                    'shortinaddress' => $shortaddress,
                    'longinaddress' => $address,
                    'selectLoginType'=> $login_type

                // 'mac_address_intime' => $mac,
                );
                $result = $this->db->insert('employee_attendance_leave', $data);
                if ($result != FALSE) {
                    $query = $this->db->query("UPDATE `leave_transaction_all` SET `status`='4' where leave_date='$todaydate1' and user_id='$user_id'");
                    $response['message'] = 'success';
                    $response['body'] = 'Login Successful';
                } else {

                    $response['message'] = 'fail';
                    $response['body'] = 'Something went wrong';
                }
            } else {
                $data = array(
                    'user_id' => $user_id,
                    'punch_out' => 0,
                    'date' => $todaydate1,
                );
                $arr = array('punch_out' => $todaydate, 'punch_regularised_status' => $punch_regularised_status, 'punchout_lat' => $latitude_live,
                    'punchout_long' => $longitude_live, 'shortoutaddress' => $shortaddress,
                    'longoutaddress' => $address ,'selectLogoutType' => $login_type);
                $this->db->set($arr);
                $this->db->where($data);
                $result = $this->db->update('employee_attendance_leave');
                if ($this->db->affected_rows() > 0) {
                    $response['message'] = 'success';
                    $response['body'] = 'Logout Successful';
                } else {

                    $response['message'] = 'fail';
                    $response['body'] = 'Something went wrong';
                }
            }
        }
        // dd("abhishek mishra : after if and else condition : ");
        echo json_encode($response);
    }

    public function twopoints_on_earth($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {
        $long1 = deg2rad($longitudeFrom);
        $long2 = deg2rad($longitudeTo);
        $lat1 = deg2rad($latitudeFrom);
        $lat2 = deg2rad($latitudeTo);

        //Haversine Formula
        $dlong = $long2 - $long1;
        $dlati = $lat2 - $lat1;

        $val = pow(sin($dlati / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong / 2), 2);

        $res = 2 * asin(sqrt($val));

        $radius = 3958.756;

        return ($res * $radius);
    }

    public function emp_login() {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $status = $this->input->post('status');
        date_default_timezone_set('Asia/Kolkata');
        $todaydate = date('Y-m-d H:i:s');
        $todaydate1 = date('Y-m-d');

        /* $createDate = new DateTime($todaydate);
          $todaydate = $createDate->format('Y-m-d'); */

        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        $query = $this->db->query("select outside_punch_applicable from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $ress = $query->row();
            $outside_punch_applicable = $ress->outside_punch_applicable;
        }
        if ($outside_punch_applicable == 1) {
            $regular_status = 1;
        } else {
            $regular_status = 0;
        }
        $check_request_leave = $this->db->query("select date from employee_attendance_leave where date ='$todaydate1' AND `user_id`= '$user_id'");
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {

            if ($status == 'login') {
                $data = array(
                    'user_id' => $user_id,
                    'date' => $todaydate1,
                    'regular_status' => $regular_status
                );
                $arr = array('punch_in' => $todaydate, 'leave_status' => 0);
                $this->db->set($arr);
                $this->db->where($data);
                $result = $this->db->update('employee_attendance_leave');
                if ($this->db->affected_rows() > 0) {
                    $query = $this->db->query("UPDATE `leave_transaction_all` SET `status`='4' where leave_date='$todaydate1' and user_id='$user_id'");
                    $response['message'] = 'success';
                    $response['body'] = 'Login Successful';
                } else {
                    $response['message'] = 'fail';
                    $response['body'] = 'Something went wrong';
                }
            } else {
                $data = array(
                    'user_id' => $user_id,
                    'punch_out' => 0,
                    'date' => $todaydate1
                );
                $arr = array('punch_out' => $todaydate, 'punch_regularised_status' => 1);
                $this->db->set($arr);
                $this->db->where($data);
                $result = $this->db->update('employee_attendance_leave');

                if ($result == true) {
                    $response['message'] = 'success';
                    $response['body'] = 'Logout Successful';
                } else {
                    $response['message'] = 'fail';
                    $response['body'] = 'Something went wrong';
                }
            }
        } else {
            if ($status == 'login') {
                $data = array(
                    'date' => $todaydate1,
                    'punch_in' => $todaydate,
                    'user_id' => $user_id,
                    'firm_id' => $firm_id,
                    'leave_status' => 0,
                    'regular_status' => $regular_status
//                'mac_address_intime' => $mac,.
                );
                $result = $this->db->insert('employee_attendance_leave', $data);
                if ($result != FALSE) {
                    $query = $this->db->query("UPDATE `leave_transaction_all` SET `status`='4' where leave_date='$todaydate1' and user_id='$user_id'");
                    $response['message'] = 'success';
                    $response['body'] = 'Login Successful';
                } else {

                    $response['message'] = 'fail';
                    $response['body'] = 'Something went wrong';
                }
            } else {

                $data = array(
                    'user_id' => $user_id,
                    'punch_out' => 0,
                    'date' => $todaydate1
                );
                $arr = array('punch_out' => $todaydate, 'punch_regularised_status' => 1);
                $this->db->set($arr);
                $this->db->where($data);
                $result = $this->db->update('employee_attendance_leave');
                if ($this->db->affected_rows() > 0) {
                    $response['message'] = 'success';
                    $response['body'] = 'Logout Successful';
                } else {

                    $response['message'] = 'fail';
                    $response['body'] = 'Something went wrong';
                }
            }
        }
        echo json_encode($response);
    }

    public function get_login_details() {
        $ip = get_ip_address();
        $ipAddress = file_get_contents("http://ip-api.com/json/$ip");
        $session_data = $this->session->userdata('login_session');
        date_default_timezone_set('Asia/Kolkata');
        $user_id = ($session_data['emp_id']);
        $todaydate = date('Y-m-d');
        $start_date = $this->input->post('start_date');
        $createDate = new DateTime($start_date);
        $strip = $createDate->format('Y-m-d');
        $query = $this->db->query("SELECT *,(select gps_off_allow from user_header_all where user_id='$user_id') as gps_off_allow  FROM employee_attendance_leave where DATE(date)='$strip' AND user_id='$user_id'");
    //    print_r($this->db->last_query());die;
        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            $intime = $result->punch_in;
            $outtime = $result->punch_out;
            $response['message'] = 'success';
            $response['gps_off_allow'] = $result->gps_off_allow;
            // if ($intime != 0 && $outtime != 0) {
            if ($intime != "0000-00-00 00:00:00" && 
                 $outtime != "0000-00-00 00:00:00") {
                $response['status'] = 'attendace_marked';
                $response['intime'] = $intime;
                $response['outtime'] = $outtime;
                $response['ipAddress'] = $ipAddress;
            // } else if ($intime != 0 && $outtime == 0) {
                 }else if ($intime != "0000-00-00 00:00:00" && 
                 $outtime == "0000-00-00 00:00:00") {
                $response['status'] = 'intime_marked';
                $response['intime'] = $intime;
                $response['ipAddress'] = $ipAddress;
            } else {
                $response['message'] = 'success';
                $response['status'] = 'not_marked';
            }
        } else {
            $qr = $this->db->query("select gps_off_allow from user_header_all where user_id='$user_id'");
            if ($this->db->affected_rows() > 0) {
                $result1 = $qr->row();
                $response['gps_off_allow'] = $result1->gps_off_allow;
            }
            if (strtotime($strip) == strtotime($todaydate)) {
                $response['message'] = 'success';
                $response['status'] = 'not_marked';
                echo json_encode($response);
                exit;
            }
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }


	public function get_login_detailsnew() {
        // dd("abhishek kumar mishra : get_login_detailsnew");
		$user_id = $this->input->post('emp_id');
		if ($user_id == '' || $user_id=='Search by Employee') {
			$session_data = $this->session->userdata('login_session');
			$user_id = ($session_data['emp_id']);
		} else {
			$user_id = $this->input->post('emp_id');
		}
        $hourTowork=$this->db->query("SELECT max(fixed_hour) as hourTowork FROM Payroll.attendance_employee_applicable where user_id='".$user_id."'")->row()->hourTowork;
		$todaydate = date('Y-m-d');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$strip=date('Y-m-d',strtotime($start_date));
		$end_date=date('Y-m-d',strtotime($end_date));
        $holiday_array=  $this->get_holiday_nameNew($strip,$end_date,$user_id);
		$activity_status = '';
		$regular_status = '';

        $events = $this->db->query("SELECT DATE(start_date) as event_date, event_details 
            FROM event_master 
            WHERE DATE(created_on) BETWEEN '$strip' AND '$end_date'"
        )->result_array();
        $event_array = [];
        if (!empty($events)) {
            foreach ($events as $ev) {
                $event_array[$ev['event_date']][] = $ev['event_details'];
            }
        }

		$response_all_date=array();
		$response_all_date['holiday']=$holiday_array;
		$response_all_date['event_array']=$event_array;
		$query = $this->db->query("SELECT punch_in,punch_out,date,regular_status,activity_status,reason,
(select status from leave_transaction_all lta where lta.user_id =eal.user_id and date(lta.leave_date) = eal.date LIMIT 1) as leave_status, selectLogoutType,selectLoginType
 FROM employee_attendance_leave eal where DATE(date) >='$strip' AND DATE(date) <='$end_date'   AND user_id='$user_id' order by date asc");

		if ($this->db->affected_rows() > 0) {
			$result1 = $query->result();
			foreach ($result1 as $result) {
				$intime = $result->punch_in;
				$outtime = $result->punch_out;
				$response=array();
				if ($intime != '0000-00-00 00:00:00' && $outtime != '0000-00-00 00:00:00') {
					$response['message'] = 'success';
					$response['status'] = 'attendace_marked';
					$response['intime'] = date('h:i', strtotime($intime));
                    if($outtime) {
                        $response['outtime'] = date('H:i', strtotime($outtime));
                    } else {
                        $response['outtime'] = date('h:i', strtotime($outtime));
                    }
				} else if ($intime != '0000-00-00 00:00:00' && $outtime == '0000-00-00 00:00:00') {
					$response['message'] = 'success';
					$response['status'] = 'intime_marked';
					$response['intime'] = date('h:i', strtotime($intime));
				} else {
					$response['message'] = 'success';
				}
				$response['leave_status'] = $result->leave_status;;
				$response['activity_status'] = $result->activity_status;;
				$response['regular_status'] = $result->regular_status;;
                $response['selectLoginType'] = $result->selectLoginType;;
				$response['selectLogoutType'] = $result->selectLogoutType;;
				$response['date'] = $result->date;
				$response['reason'] = $result->reason;
				array_push($response_all_date,$response);
			}
            $response['hourTowork']=$hourTowork;

		} else {
			$response_all_date['message'] = 'fail';
		}
        // dd($response_all_date);
		echo json_encode($response_all_date);
	}


    public function get_leave_details()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $todaydate = date('Y-m-d');
        $start_date = $this->input->post('start_date');
        $createDate = new DateTime($start_date);
        $strip = $createDate->format('Y-m-d');
        $query1 = $this->db->query("select status from leave_transaction_all where DATE(leave_date)='$strip' AND user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $result1 = $query1->row();
            $leave_status = $result1->status;
            if ($leave_status == 1) {
                $sts = 'Leave Requested';
            } elseif ($leave_status == 2) {
                $sts = 'Leave Approve';
            } else {
                $sts = 'Leave Denied';
            }
            $response['message'] = 'leave';
            $response['status'] = $sts;
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function get_login_details1() {
        $user_id = $this->input->post('emp_id');
        if ($user_id == '') {
            $session_data = $this->session->userdata('login_session');
            $user_id = ($session_data['emp_id']);
        } else {
            $user_id = $this->input->post('emp_id');
        }
        
        $todaydate = date('Y-m-d');
        $start_date = $this->input->post('start_date');
        $createDate = new DateTime($start_date);
        $strip = $createDate->format('Y-m-d');
        $activity_status = '';
        $regular_status = '';
        // dd("SELECT punch_in,punch_out FROM employee_attendance_leave where DATE(date)='$strip' AND user_id='$user_id' ");
        $query = $this->db->query("SELECT punch_in,punch_out FROM employee_attendance_leave where DATE(date)='$strip' AND user_id='$user_id' ");
        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            $intime = $result->punch_in;
            $outtime = $result->punch_out;
            if ($intime != '0000-00-00 00:00:00' && $outtime != '0000-00-00 00:00:00') {
                $response['message'] = 'success';
                $response['status'] = 'attendace_marked';
                $response['intime'] = $intime . ' AM';
                $response['outtime'] = $outtime . ' PM';

            } else if ($intime != '0000-00-00 00:00:00') {
                $response['message'] = 'success';
                $response['status'] = 'intime_marked';
                $response['intime'] = $intime . ' AM';

            } else {
                $response['message'] = '';
            }

            if($outtime != '0000-00-00 00:00:00') {
                $response['message'] = 'success';
                $response['status'] = 'attendace_marked';
                $response['intime'] = $intime . ' AM';
                $response['outtime'] = $outtime . ' PM';
            } else {
                $response['message'] = '';
            }

        } else {
            $response['message'] = 'Record not found';
        }
        
        $query1 = $this->db->query("SELECT leave_status FROM employee_attendance_leave where DATE(date)='$strip' AND user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $result1 = $query1->row();
            $leave_status = $result1->leave_status;
            $response['leave_status'] = $leave_status;
            $response['message'] = 'success';
        }

        $query11 = $this->db->query("SELECT activity_status FROM employee_attendance_leave where DATE(missing_punchout) != '0000-00-00 00:00:00' AND DATE(missing_punchin) != '0000-00-00 00:00:00' AND DATE(date)='$strip' AND user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $result1 = $query11->row();
            $activity_status = $result1->activity_status;
            if ($activity_status == '0') {
                $activity_status = 0;
            } else {
                $activity_status = $activity_status;
            }
            $response['activity_status'] = $activity_status;
            $response['message'] = 'success';
        }
        $query12 = $this->db->query("SELECT regular_status FROM employee_attendance_leave where DATE(date)='$strip' AND user_id='$user_id'  AND punch_regularised_status = 1 and punch_in !='0000-00-00 00:00:00'");
        if ($this->db->affected_rows() > 0) {
            $result1 = $query12->row();
            $regular_status = $result1->regular_status;


            $response['regular_status'] = $regular_status;
            $response['message'] = 'success';
        }
        // dd($response);
        echo json_encode($response);
    }

    public function get_all_holidays() {
        $session_data = $this->session->userdata('login_session');
        $user_type = ($session_data['user_type']);
        $user_id = ($session_data['emp_id']);
        $date = $this->input->post('date');
        if ($user_type == 5) {
            $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
            if ($this->db->affected_rows() > 0) {
                $res = $qr->row();
                $firm_id = $res->hr_authority;
            }
        } else {

            $result = $this->db->query("SELECT firm_id FROM `user_header_all` WHERE `user_id`='$user_id' and activity_status=1");
            // echo $this->db->last_query();die;
            if ($this->db->affected_rows() > 0) {
                $data = $result->row();
                // print_r($data); die;
               $firm_id = $data->firm_id;
                // var_dump($user_data);
            }
        }
        // echo $firm_id;die;
        $date = array();
        $query = $this->db->query("select * from holiday_master_all where firm_id='$firm_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $query->result();
            foreach ($res as $row) {
                $date[] = $row->date;
            }
            return $date;
            // var_dump($date);
        } else {
            return false;
        }
    }

    public function get_holiday_name() {
        $session_data = $this->session->userdata('login_session');
        $user_type = ($session_data['user_type']);
        $user_id = ($session_data['emp_id']);
        $date = $this->input->post('date');
        if ($user_type == 5) {
            $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
            if ($this->db->affected_rows() > 0) {
                $res = $qr->row();
                $firm_id = $res->hr_authority;
            }
        } else {

            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $firm_id = $result['firm_id'];
            }
        }

        $employee_id = $this->input->post('employee_id');
        if ($employee_id !== null && $employee_id != '') {
            $user_id = $employee_id;
        }

        $qrr = $this->db->query("select holiday_name,is_alternate,alternate_id from holiday_master_all where date='$date' and firm_id='$firm_id'");
        // echo $this->db->last_query();die;
        if ($this->db->affected_rows() > 0) {
            $res = $qrr->row();
            $hname = $res->holiday_name;
            $is_alternate = $res->is_alternate;
            $alternate_id = $res->alternate_id;
            if ($is_alternate == 1) {

                $checkUserAlt = $this->db->query('SELECT * FROM alternate_holiday_master where alternate_id = "' . $alternate_id . '" and user_id = "' . $user_id . '";')->result();
                if (count($checkUserAlt) > 0) {
                    if (is_numeric($hname) == TRUE) {
                        $holidayname = '';
                    } else {
                        $holidayname = $hname;
                    }
                    $response['h_name'] = $holidayname;
                    $response['message'] = 'success';
                } else {
                    $response['message'] = 'fail';
                }
            } else {
                if (is_numeric($hname) == TRUE) {
                    $holidayname = '';
                } else {
                    $holidayname = $hname;
                }
                $response['h_name'] = $holidayname;
                $response['message'] = 'success';
            }
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }


    public function get_holiday_nameNew($from_date,$to_date,$user_idN) {
        // dd("abhishek mishra : get_holiday_nameNew", $from_date,$to_date,$user_idN);
        $session_data = $this->session->userdata('login_session');
        $user_type = ($session_data['user_type']);
        $user_id = ($session_data['emp_id']);
        if(empty($session_data['user_id'])){
            $user_id = '';
        }
        if($user_type == 5) {
            $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
            if ($this->db->affected_rows() > 0) {
                $res = $qr->row();
                $firm_id = $res->hr_authority;
            }
        } else {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $firm_id = $result['firm_id'];
            }
        }
        if(empty($user_idN)){
            $employee_id = $this->input->post('employee_id');
            if ($employee_id !== null && $employee_id != '') {
                $user_id = $employee_id;
            }
        } else {
            $user_id = $user_idN;
        }
        $holiday_array=array();
        $qrr = $this->db->query("SELECT holiday_name,is_alternate,alternate_id,date FROM holiday_master_all WHERE date>='$from_date' and date<='$to_date'");
        if($this->db->affected_rows() > 0) {
            $result = $qrr->result();
            $restrictedUsers = ['U_128','U_161','U_872','U_859','U_508','U_107','U_813'];
            $restrictedHolidays = ['Maharastra day', 'Gudhi Padwa'];
            foreach($result as $res) {
                if (in_array($user_id, $restrictedUsers) && in_array($res->holiday_name, $restrictedHolidays)) {
                    continue; // ye holiday is user ko nahi dikhegi
                }
                $hname = $res->holiday_name;
                $is_alternate = $res->is_alternate;
                $alternate_id = $res->alternate_id;
                if ($is_alternate == 1) {
                    $checkUserAlt = $this->db->query('SELECT * FROM alternate_holiday_master where alternate_id = "' . $alternate_id . '" and user_id = "' . $user_id . '";')->result();
                    if (count($checkUserAlt) > 0) {
                        if (is_numeric($hname) == TRUE) {
                            $holidayname = '';
                        } else {
                            $holidayname = $hname;
                        }
                        $response['h_name'] = $holidayname;
                        $response['message'] = 'success';
                    } else {
                        $holidayname='-';
                        $response['message'] = 'fail';
                    }
                } else {
                    if (is_numeric($hname) == TRUE) {
                        $holidayname = '';
                    } else {
                        $holidayname = $hname;
                    }
                    $response['h_name'] = $holidayname;
                    $response['message'] = 'success';
                }
                if($holidayname != '-'){
                    $holiday_array[$res->date]=$holidayname;
                }
            }
            
        } else {
            $response['message'] = 'fail';
        }
        return $holiday_array;
    }



    public function emailShootIfUserNotLoginAfterNoon() {
        $today = date('Y-m-d');
        $sql = "SELECT uha.user_id, uha.user_name, uha.email,eal.punch_in
                FROM user_header_all uha
                LEFT JOIN (
                    SELECT user_id, punch_in 
                    FROM employee_attendance_leave 
                    WHERE DATE(date) = ?
                ) eal ON uha.user_id = eal.user_id
                WHERE uha.activity_status = 1  
                AND uha.user_type = 4 
                AND (eal.punch_in IS NULL OR eal.punch_in = '00:00:00')";
        $query = $this->db->query($sql, [$today]);
        $users = $query->result();
        if($users){
            foreach ($users as $user) {
                if(!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }
                $to = "{$user->email}";
                $subject = "Attendance Reminder: Please Punch In";
                $message = "Dear {$user->user_name},<br><br>You have not punched in today before 12 PM. Please mark your attendance as soon as possible.<br><br>With Regards,<br>HR Team";
                $this->Globalmodel->sendMail($to, $subject, $message);

                if (!$this->email->send()) {
                    log_message('error', 'Mail failed for user_id: ' . $user->user_id . ' | ' . $this->email->print_debugger());
                }
            }
        }
        // No need to assign $response if not used
    }


    // public function emailShootIfUserNotLoginAfterNoon() {
    //     $today = date('Y-m-d');
    //     $sql = "SELECT uha.user_id, uha.user_name, uha.email, eal.punch_in
    //             FROM user_header_all uha
    //             LEFT JOIN (
    //                 SELECT user_id, punch_in 
    //                 FROM employee_attendance_leave 
    //                 WHERE DATE(date) = ?
    //             ) eal ON uha.user_id = eal.user_id
    //             WHERE uha.activity_status = 1  
    //             AND uha.user_type = 4 
    //             AND (eal.punch_in IS NULL OR eal.punch_in = '00:00:00')
    //             AND uha.user_id = 'U_946'";

    //     $query = $this->db->query($sql, [$today]);
    //     $users = $query->result();

    //     if ($users) {
    //         $this->load->library('email');
    //         $from_email = 'noreply@gbtech.in';
    //         $config = array(
    //             'protocol'    => 'smtp',
    //             'smtp_host'   => 'smtp.gmail.com',
    //             'smtp_port'   => 587,
    //             'smtp_user'   => 'your_email@gmail.com',
    //             'smtp_pass'   => 'your_email_password',
    //             'mailtype'    => 'html',
    //             'charset'     => 'utf-8',
    //             'newline'     => "\r\n",
    //             'smtp_crypto' => 'ssl',
    //             'wordwrap'    => TRUE,
    //         );
    //         $this->email->initialize($config);

    //         foreach ($users as $user) {
    //             if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    //                 continue;
    //             }

    //             $this->email->from($from_email, 'RMT Team');
    //             $this->email->to($user->email);
    //             $this->email->subject('Attendance Reminder: Please Punch In');
    //             $this->email->message("Dear {$user->user_name},<br><br>You have not punched in today before 12 PM. Please mark your attendance as soon as possible.<br><br>Regards,<br>HR Team");

    //             if (!$this->email->send()) {
    //                 log_message('error', 'Mail failed for user_id: ' . $user->user_id . ' | ' . $this->email->print_debugger());
    //             }
    //         }
    //     }
    // }



    public function getActualPunchTime() {
        try {
            $date = $this->input->post('punch_date');
            $userId = $this->input->post('employee_id');
            if($userId !='' || !empty($userId) || trim($userId) != '') {
                $userId = $this->input->post('employee_id');
            } else {
                $sessionData = $this->session->userdata('login_session');
                $sql = "SELECT eal.punch_in, eal.punch_out FROM user_header_all uha
                            JOIN (
                                SELECT 
                                    user_id,
                                    date,
                                    punch_in
                                FROM 
                                    employee_attendance_leave 
                                WHERE 
                                    punch_in IS NOT NULL 
                                    AND date = ?
                                    AND user_id = ?
                            ) eal ON eal.user_id = uha.user_id
                            WHERE uha.activity_status=1";
                $query = $this->db->query($sql, array($date, $sessionData['emp_id']));

                if($query->num_rows() > 0) {
                    $response = $query->row();
                    echo json_encode($response);
                } else {
                    echo json_encode(array("error"=> "Punch date is not found"));
                }
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function export_users_excel() {
        $user = "SELECT eal.user_id, uha.user_name, uha.mobile_no FROM employee_attendance_leave eal
                    LEFT JOIN user_header_all uha ON uha.user_id = eal.user_id
                    WHERE eal.date=CURDATE() - INTERVAL 1 DAY and uha.activity_status=1
                    ORDER BY uha.user_name ASC;
                ";
        $user_data = $this->db->query($user)->result_array();
        $filename = 'Users_Export_' . date('d-m-Y_H-i-s') . '.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        // 3. Excel content banao (HTML table — Excel directly open kar leta hai)
        $output  = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ';
        $output .= 'xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $output .= '<head><meta charset="UTF-8"></head><body>';
        $output .= '<table border="1" style="border-collapse:collapse;">';
        // Header row
        $output .= '<tr style="background-color:#16a34a;color:#ffffff;font-weight:bold;text-align:center;">';
        $output .= '<th style="padding:8px;min-width:60px;">Sr. No.</th>';
        $output .= '<th style="padding:8px;min-width:100px;">User ID</th>';
        $output .= '<th style="padding:8px;min-width:180px;">User Name</th>';
        $output .= '<th style="padding:8px;min-width:140px;">Mobile No</th>';
        $output .= '</tr>';
        // Data rows
        $sr = 1;
        foreach ($user_data as $row) {
            $bg = ($sr % 2 === 0) ? '#f4f5f7' : '#ffffff';
            $output .= '<tr style="background-color:' . $bg . ';text-align:center;">';
            $output .= '<td style="padding:7px;">' . $sr++ . '</td>';
            $output .= '<td style="padding:7px;">' . htmlspecialchars($row['user_id'])   . '</td>';
            $output .= '<td style="padding:7px;text-align:left;">' . htmlspecialchars($row['user_name']) . '</td>';
            $output .= '<td style="padding:7px;">' . htmlspecialchars($row['mobile_no']) . '</td>';
            $output .= '</tr>';
        }
        // Total row
        $output .= '<tr style="background-color:#e5e7eb;font-weight:bold;text-align:center;">';
        $output .= '<td style="padding:7px;" colspan="3">Total Users</td>';
        $output .= '<td style="padding:7px;">' . count($user_data) . '</td>';
        $output .= '</tr>';
        $output .= '</table></body></html>';
        echo $output;
        exit;
    }
}


