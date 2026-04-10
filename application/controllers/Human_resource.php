<?php

class Human_resource extends CI_controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('emp_model');
        $this->load->model('hr_model');
        $this->load->model('email_sending_model');
        $this->load->model('Nas_modal');
        $this->load->helper('dump_helper');
    }

    public function index() {
        if (isset($this->session->login_session)) {
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
            }
            $result2 = $this->hr_model->get_human_resource($firm_id);
            //var_dump($result2);
            if ($result2 !== false) {
                $re = $result2->row();
                $firm_id = $re->firm_id;
            }

            $firm_id_array = $this->get_firms_for_token();
            for ($i = 0; $i < count($firm_id_array); $i++) {
                $for = "token_status";
                $access_token = "";
                if ($token_status = $this->Nas_modal->get_hq_of_firm($firm_id_array[$i], $for, $access_token) == 1) {
                    $for = "refresh_token";
                    $access_token = $this->Nas_modal->genrate_access_token_from_refresh_token($refresh_token = $this->Nas_modal->get_hq_of_firm($firm_id_array[$i], $for, $access_token));
                    $for = "update_token";
                    $update_status = $this->Nas_modal->get_hq_of_firm($firm_id_array[$i], $for, $access_token);
                } else {
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
            $query_to_fetcth_event_date = $this->db->query("SELECT `create_at`,`end_date`,`holiday_id` FROM `holiday_header_all` where FIND_IN_SET(`holiday_applied_in`, '$firm_id')");
            if ($query_to_fetcth_event_date->num_rows() > 0) {

                $record_event = $query_to_fetcth_event_date->result();
                foreach ($record_event as $row) {
                    $start_date = $row->start_date;
                    $end_date = $row->end_date;
                    $today_date = date("Y/m/d");
                    //                $query_event = $this->db->query("SELECT * FROM `holiday_management_all` WHERE `start_date` >= '$start_date' AND `end_date` <= '$end_date' AND `firm_id`='$firm_id'");
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
            $query_event = $this->db->query("SELECT * from holiday_header_all where FIND_IN_SET(`holiday_applied_in`, '$firm_id')");
            if ($query_event->num_rows() > 0) {
                $record_data = $query_event->result();
                $data['result_event'] = $record_data;
                // var_dump($result);
            } else {
                $data['result_event'] = "";
            }


            $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
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
            $data['prev_title'] = "Dashboard";
            $data['page_title'] = "Dashboard";
            $data['firm_id'] = $firm_id;
            $this->load->view('human_resource/Dashboard', $data);
        } else {

            //            $data["is_cookie"] = "false";
            //            $this->load->view('admin_login', $data);
            redirect(base_url() . 'Login');
        }
    }

    function check_email_avalibility($email)
    {
        //check hereemail id is available in database or not

        $this->load->model("Emp_model");
        if ($this->Emp_model->is_email_available($email) == true) {
            return true;
            //                echo '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Email Is Already register</label>';
        } else {
            return false;
            //                echo '<label class="text-success"><span class="glyphicon glyphicon-ok"></span>Valid Email Id</label>';
        }
    }

    function Show_holiday_details($firm_id = '')
    {
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $user_type = ($session_data['user_type']);
        $user_id = ($session_data['emp_id']);
        if ($user_type == 5) {
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $loginfirm_id = $result1['firm_id'];
            }
            $hr_auth = $this->db->query("select hr_authority from user_header_all where user_id='$user_id' AND user_type='5'");
            if ($this->db->affected_rows() > 0) {
                $record1 = $hr_auth->row();
                $firm_id = $record1->hr_authority;
            }
        } else {
            $firm_id = $firm_id;
        }

        $check_year = $this->db->query("select date from holiday_master_all where firm_id='$firm_id'");
        if ($this->db->affected_rows() > 0) {

            $data_date = $check_year->row();
            $data_date1 = $data_date->date;
            $parts = explode('-', $data_date1);
            $date_year = $parts[0];
            $data['year_exist'] = $date_year;
        } else {
            $data['year_exist'] = '';
        }
        $data_holiday = $this->get_all_holidays();
        //        echo $this->db->last_query();
        if ($data_holiday !== FALSE) {
            $data['data_holiday'] = $data_holiday;
        } else {
            $data['data_holiday'] = '';
        }


        $data['prev_title'] = "Holiday Details";
        $data['page_title'] = "Holiday Details";

        $this->load->view('human_resource/HolidayDetails', $data);
    }

    public function check_year_exist()
    { //Function by rajashree
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $user_type = ($session_data['user_type']);
        $user_id = ($session_data['emp_id']);
        if ($user_type == 5) {
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $loginfirm_id = $result1['firm_id'];
            }
            $hr_auth = $this->db->query("select hr_authority from user_header_all where user_id='$user_id' AND user_type='5'");
            if ($this->db->affected_rows() > 0) {
                $record1 = $hr_auth->row();
                $firm_id = $record1->hr_authority;
            }
        } else {
            $firm_id = $firm_id;
        }

        $select_year = $this->input->post("select_year");
        $check_year = $this->db->query("select date from holiday_master_all where firm_id='$firm_id'");
        //        echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {

            $data_date = $check_year->row();
            $data_date1 = $data_date->date;
            $parts = explode('-', $data_date1);
            $date_year = $parts[0];
            if ($select_year == $date_year) {
                $response['year_exist'] = $date_year;
                $response['message'] = 'success';
            } else {
                $response['year_exist'] = '';
                $response['message'] = 'fail';
            }
        } else {
            $response['message'] = 'no data';
        }
        echo json_encode($response);
    }

    public function check_otherholiday()
    {
        $check_other_holiday = $this->db->query("select * from holiday_master_all where category='1'");
        if ($this->db->affected_rows() > 0) {

            $data_date = $check_other_holiday->row();
            $category = $data_date->category;

            $response['other_category'] = $category;
            $response['message'] = true;
        } else {
            $response['other_category'] = '';
            $response['message'] = false;
        }
        echo json_encode($response);
    }

    public function get_all_holidays()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        //get firm_id
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        $date = array();
        $query = $this->db->query("select * from holiday_master_all where firm_id='$firm_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $query->result();
            foreach ($res as $row) {
                $date[] = $row->date;
            }
            return $date;
            //            var_dump($date);
        } else {
            return false;
        }
    }

    public function get_single_holiday()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        //get firm_id
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        $query = $this->db->query("select date,firm_id,holiday_name from holiday_master_all where firm_id='$firm_id' and holiday_name!='0'");
        if ($this->db->affected_rows() > 0) {
            $res = $query->row();
            $firm_id = $res->firm_id;
            $holiday_name = $res->holiday_name;
            $date = $res->date;
            return $res;
        } else {
            return false;
        }
    }

    public function get_single_holiday1()
    { //function by rajashree
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        //get firm_id
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        date_default_timezone_set('Asia/Kolkata');
        $start_date = $this->input->post('start_date');
        $createDate = new DateTime($start_date);
        $strip = $createDate->format('Y-m-d');
        $query = $this->db->query("select date,firm_id,holiday_name from holiday_master_all where firm_id='$firm_id' and date='$strip' and category='1'");
        if ($this->db->affected_rows() > 0) {
            $res = $query->row();
            $response['data_holiday'] = $res;
            $response['message'] = 'success';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    function get_cusomizeday($year, $array_of_num, $day_num)
    {
        $dates_array = array();
        for ($i = 1; $i <= 12; $i++) {
            $firstday = new DateTime("$year-$i-$day_num 0:0:0");
            $first_w = $firstday->format('w'); // weekday of firstday
            $saturday1 = new DateTime;
            $saturday2 = new DateTime;
            $saturday3 = new DateTime;
            $saturday4 = new DateTime;
            $saturday5 = new DateTime;
            for ($j = 0; $j < count($array_of_num); $j++) {
                if ($array_of_num[$j] == 1) {
                    $p = 7;
                } else if ($array_of_num[$j] == 2) {
                    $p = 14;
                } else if ($array_of_num[$j] == 3) {
                    $p = 21;
                } else if ($array_of_num[$j] == 4) {
                    $p = 28;
                } else if ($array_of_num[$j] == 5) {
                    $p = 35;
                }
                $saturday1->setDate($year, $i, $p - $first_w); //14 number is for second sat
                $dates_array[] = $saturday1->format('Y-m-d');
            }
        }
        return $dates_array;
    }

    function add_holiday()
    { //function by rajashree
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        //get firm_id
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }

        $holiday_count = $this->input->post("holiday_count");
        $employee_list = $this->input->post('employee_list');
        $alternate_start_date = $this->input->post('alternate_start_date');

        $year = $this->input->post("select_year");
        $n = 0;
        $m = 0;
        $select_holiday = $this->input->post("select_holiday1");
        $all_check_holiday = $this->input->post("all_check_holiday1");
        $checkbox_valid = $this->input->post("checkbox1");
        if (empty(trim($year))) {
            $response['id'] = 'select_year';
            $response['error'] = 'Please select Year';
            echo json_encode($response);
            exit;
        } elseif ($select_holiday == '' && empty(trim($select_holiday))) {
            $response['id'] = 'select_holiday1';
            $response['error'] = 'Please select holiday';
            echo json_encode($response);
            exit;
        } elseif (($all_check_holiday == 2 && empty($checkbox_valid))) {
            $response['id'] = 'checkbox_valid';
            $response['error'] = 'Please select day';
            echo json_encode($response);
            exit;
        } else {

            for ($i1 = 1; $i1 <= $holiday_count; $i1++) {
                $all_check_holiday = $this->input->post("all_check_holiday" . $i1);
                $select_holiday = $this->input->post("select_holiday" . $i1);
                $array_of_num = $this->input->post("checkbox" . $i1);

                if ($all_check_holiday == 1) {
                    $year_data = $this->get_year($select_holiday);
                    $count_days = count($year_data);
                    $created_date = date('y-m-d h:i:s');



                    for ($i = 0; $i < $count_days; $i++) {
                        //$year = $this->input->post("select_year" . $i);

                        $date = $year_data[$i];
                        $data_holiday = array(
                            'firm_id' => $firm_id,
                            'date' => $date,
                            'category' => 0,
                            'holiday_name' => $select_holiday,
                            'created_on' => $created_date,
                            'created_by' => $user_id,
                            'status' => '1'
                        );
                        //                var_dump($data_holiday);
                        $record = $this->hr_model->add_holiday_data($data_holiday);
                        if ($record == TRUE) {
                            $n++;
                        }
                    }
                } else if ($all_check_holiday == 2) {

                    if ($select_holiday == 0) {
                        $day_num = 0;
                    } elseif ($select_holiday == 1) {
                        $day_num = 6;
                    } elseif ($select_holiday == 2) {
                        $day_num = 5;
                    } elseif ($select_holiday == 3) {
                        $day_num = 4;
                    } elseif ($select_holiday == 4) {
                        $day_num = 3;
                    } elseif ($select_holiday == 5) {
                        $day_num = 2;
                    } elseif ($select_holiday == 6) {
                        $day_num = 1;
                    }
                    $dates_array = $this->get_cusomizeday($year, $array_of_num, $day_num);
                    $data_unique = (array_unique($dates_array));
                    $count_unique = count($data_unique);
                    $created_date = date('y-m-d h:i:s');
                    for ($j = 0; $j < $count_unique; $j++) {
                        //$year = $this->input->post("select_year" . $j);
                        $date1 = $data_unique[$j];
                        $data_holiday = array(
                            'firm_id' => $firm_id,
                            'date' => $date1,
                            'category' => 0,
                            'holiday_name' => $select_holiday,
                            'created_on' => $created_date,
                            'created_by' => $user_id,
                            'status' => '1'
                        );
                        $record1 = $this->hr_model->add_holiday_data($data_holiday);
                        if ($record1 == TRUE) {
                            $m++;
                        }
                    }
                } else {

                    $weekDay = date("w", strtotime($alternate_start_date));

                    if ($select_holiday !== $weekDay) {
                        $response['status'] = false;
                        $response['id'] = "alternate_start_date";
                        $response['error'] = "Please Select Date as per Weekday selected";
                        echo json_encode($response);
                        exit();
                    }

                    $alternate_id = time();
                    $alternate_data = array();
                    foreach ($employee_list as $row) {
                        $alternate_d = array(
                            'alternate_id'  => $alternate_id,
                            'user_id'  => $row
                        );
                        array_push($alternate_data, $alternate_d);
                    }
                    $insertAlternate = $this->db->insert_batch('alternate_holiday_master', $alternate_data);

                    $AlternateDateRange = $this->getDatesFromRange($alternate_start_date, $year . '-12-31');

                    foreach ($AlternateDateRange as $drange) {
                        $data_holiday = array(
                            'firm_id' => $firm_id,
                            'date' => $drange,
                            'category' => 0,
                            'holiday_name' => $select_holiday,
                            'created_on' => date('Y-m-d H:i:s'),
                            'created_by' => $user_id,
                            'status' => '1',
                            'alternate_id' => $alternate_id,
                            'is_alternate' => 1
                        );
                        $record1 = $this->hr_model->add_holiday_data($data_holiday);
                        if ($record1 == TRUE) {
                            $m++;
                        }
                    }
                }
            }
        }
        if ($m > 0 || $n > 0) {

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

    function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {

        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P14D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        // Use loop to store date into array
        foreach ($period as $date) {
            if ($date->format($format) <= $end) {
                $array[] = $date->format($format);
            }
        }

        // Return the array elements
        return $array;
    }

    function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
    { //function by rajashree
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $dateArr = array();

        do {
            if (date("w", $startDate) != $weekdayNumber) {
                $startDate += (24 * 3600); // add 1 day
            }
        } while (date("w", $startDate) != $weekdayNumber);


        while ($startDate <= $endDate) {
            $dateArr[] = date('Y-m-d', $startDate);
            $startDate += (7 * 24 * 3600); // add 7 days
        }

        return ($dateArr);
    }

    function get_year($select_holiday)
    { //function by rajashree
        $year = $this->input->post("select_year");

        $dateArr = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', $select_holiday);
        //        var_dump($dateArr);
        return $dateArr;
    }

    function add_calender_holiday()
    { //function by rajashree
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        //get firm_id
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        date_default_timezone_set('Asia/Kolkata');
        //        $start_date = $this->input->post('start_date');
        //        $createDate = new DateTime($start_date);
        //        $strip = $createDate->format('Y-m-d');
        $holiday_name = $this->input->post("holiday_name");
        $created_date = date('y-m-d h:i:s');
        $holiday_date = $this->input->post("holiday_date");
        $query_select = $this->db->query("select * from holiday_master_all where firm_id='$firm_id' and date='$holiday_date'");
        //        echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) { //Update Holiday
            $result = $query_select->row();
            $date1 = date('y-m-d h:i:s');
            $data_update = array(
                'firm_id' => $firm_id,
                'date' => $holiday_date,
                'holiday_name' => $holiday_name,
                'created_on' => $date1,
                'category' => 1,
                'created_by' => $user_id,
                'status' => '1'
            );
            $this->db->where('date', $holiday_date);
            $query = $this->db->update('holiday_master_all', $data_update);

            if ($query === true) {

                $response['message'] = 'success';
                $response['code'] = 201;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
            }
            echo json_encode($response);
        } else { //Insert holiday
            if (empty(trim($holiday_name))) {
                $response['id'] = 'holiday_name';
                $response['error'] = 'Please Enter holiday';
                echo json_encode($response);
            } else {
                $data_holiday = array(
                    'firm_id' => $firm_id,
                    'holiday_name' => $holiday_name,
                    'date' => $holiday_date,
                    'created_on' => $created_date,
                    'category' => 1,
                    'created_by' => $user_id,
                    'status' => '1'
                );
                $record1 = $this->hr_model->add_holiday_data($data_holiday);
                if ($record1 == TRUE) {

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

    function checkFirmExists()
    {
        $firm_id = $this->input->post("firm_id");
        $this->db->select('user_name');
        $this->db->from('user_header_all');
        $this->db->where('hr_authority', $firm_id);
        $query = $this->db->get();
        //        if ($query != FALSE) {
        if ($query->num_rows() >= 1) {
            $response['message'] = 'success';
            $response['body'] = 'HR Is Already Created For This Office.';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function view_hr_function()
    {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }

        $query_of_view_hr = $this->hr_model->get_human_resource($firm_id);
        if ($query_of_view_hr !== FALSE) {
            $record1 = $query_of_view_hr->result();
            $record_row = $query_of_view_hr->row();
            $firms_under_hr = $record_row->hr_authority;
            $explode_firms = explode(", ", $firms_under_hr);
            $count = count($explode_firms);
            $final_firms = "";
            for ($i = 0; $i < $count; $i++) {
                $query_to_get_firm_name = $this->hr_model->get_firms($explode_firms[$i]);
                if ($query_to_get_firm_name !== FALSE) {
                    $recs = $query_to_get_firm_name->row();
                    $firm_name = $recs->firm_name;
                } else {
                    $firm_name = "";
                }
            }


            $data['record_hr'] = $record1;
        } else {
            $record1 = '';
            $data['record_hr'] = "";
        }
        $query = $this->db->query("SELECT `firm_logo`, `user_name` FROM `user_header_all` where `firm_id` = '$firm_id'");
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
        $data['prev_title'] = "List of Human Resources";
        $data['page_title'] = "List of Human Resources";


        $this->load->view('human_resource/ViewHumanResorces', $data);
    }

    public function change_activity_status()
    {
        $user_id = base64_decode($this->input->post('user_id'));

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

    public function ca_hr_policy()
    {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $query = $this->db->query("SELECT `firm_logo`, `user_name` FROM `user_header_all` where `firm_id` = '$firm_id'");
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
        $data['prev_title'] = "HR Policy";
        $data['page_title'] = "HR Policy";


        $this->load->view('client_admin/hr_policy', $data);
    }

    public function create_hr_page()
    {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $query = $this->db->query("SELECT `firm_logo`, `user_name` FROM `user_header_all` where `firm_id` = '$firm_id'");
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
        $data['prev_title'] = "Create Human Resources";
        $data['page_title'] = "Create Human Resources";


        $this->load->view('human_resource/CreateHumanResource', $data);
    }

    public function get_firm_name()
    {

        $seprate_batch_id = $this->input->post('seprate_batch_id');
        //echo $seprate_batch_id;

        $query_get_seprate_firm_id = $this->db->query("select firm_id from survey_batch_information_all where batch_id = '$seprate_batch_id'");

        $res12 = $query_get_seprate_firm_id->row();
        $get_firm_id = $res12->firm_id;
        $finial_get_firm_id = explode(", ", $get_firm_id);
        // print_r($finial_get_firm_id);
        $my_finial_count = count($finial_get_firm_id);
        //print_r($my_finial_count);
        // echo $my_finial_count;
        for ($a = 0; $a < $my_finial_count; $a++) {

            $query_get_firm_name = $this->db->query("select * from partner_header_all where firm_id = '$finial_get_firm_id[$a]'");
            if ($query_get_firm_name->num_rows() > 0) {
                foreach ($query_get_firm_name->result() as $row) {
                    //print_r($res123);

                    $response['my_firm_name'][] = ['firm_name' => $row->firm_name];
                }


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


        //$data['finial_firm_name'] = $get_firm_name_finial['my_firm_name'];
    }

    //This function is for the load question and their options

    public function load_questions_and_answers()
    {
        $output = '';
        $batch_id = $this->input->post('batch_id');
        $user_id = $this->input->post('employee_id');


        $query_get_template = $this->db->query("SELECT template_id, batch_name FROM `survey_batch_information_all` WHERE `batch_id` = '$batch_id'");
        foreach ($query_get_template->result() as $row) {
            $template_data = $row->template_id;
            $batch_name_for_display[] = $row->batch_name;
        }

        $query_get_template = $this->db->query("SELECT question_id, option_group_id FROM `template_header_all` WHERE `template_id` = '$template_data'");
        foreach ($query_get_template->result() as $row) {
            $question_data = $row->question_id;
            $option_data = $row->option_group_id;
        }
        $all_questions = explode(", ", $question_data);

        $question_count = count($all_questions);
        for ($i = 0; $i < $question_count; $i++) {

            $query_get_questions = $this->db->query("SELECT question_id, question FROM `question` WHERE `question_id` = '$all_questions[$i]'");

            foreach ($query_get_questions->result() as $row) {

                $my_all_question_data[] = ['question' => $row->question, 'question_id' => $row->question_id];
            }
        }
        $question_count_for_loop[] = $question_count;


        $query_get_opetions = $this->db->query("SELECT option_id, option_name FROM `options` WHERE `option_group_id` = '$option_data'");
        if ($query_get_opetions->num_rows() > 0) {
            foreach ($query_get_opetions->result() as $row) {
                $option_ids_count = $row->option_id;
                $my_all_option_data[] = ['option_name' => $row->option_name, 'option_id' => $row->option_id];
            }
        } else {
        }

        $count_of_options_data = $query_get_opetions->num_rows();

        //$count_of_options_data = count($my_all_option_data['option_data_all_for_employee']);
        $counter_of_all_option_data[] = $count_of_options_data;
        if ($question_count_for_loop == '') {
        } else {


            $question_part = [];
            for ($i = 0; $i < sizeof($question_count_for_loop); $i++) {
                for ($j = 0; $j < sizeof($all_questions); $j++) {
                    if ($question_count_for_loop[$i] > $j) {
                        $question_part[$i][$j] = $my_all_question_data[$j]['question'];
                    }
                }
                for ($k = 0; $k < sizeof($all_questions); $k++) {
                    if ($question_count_for_loop[$i] > $k) {
                        unset($all_questions[$k]);
                    }
                }
                $all_questions = array_values($all_questions);
            }

            $option_part = [];
            for ($i = 0; $i < sizeof($counter_of_all_option_data); $i++) {
                for ($j = 0; $j < sizeof($my_all_option_data); $j++) {
                    if ($counter_of_all_option_data[$i] > $j) {
                        $option_part[$i][$j] = $my_all_option_data[$j];
                    }
                }

                for ($l = 0; $l < sizeof($my_all_question_data); $l++) {
                    if ($counter_of_all_option_data[$i] > $l) {
                        unset($my_all_option_data[$l]);
                    }
                }
                //                                                        for($k=0;$k<3;$k++){
                //
                //                                                        }
                $my_all_option_data = array_values($my_all_option_data);
                //
            }


            //                                                print_r($question_part);
            //                                                echo count($question_part[0]);

            for ($z = 0; $z < count($question_count_for_loop); $z++) {



                $output .= '<div class="tab-pane" id="tab_<?php echo$z;
        ?>">
<div class="portlet-form">
    <div class="row">
        <div class="form-group">
            <div class="col-md-12">';


                //                                                        if ($all_questions !== "") {

                for ($i = 0; $i < sizeof($question_part[$z]); $i++) {
                    $questions_id = $question_part[$z][$i];
                    $questions = $question_part[$z][$i];

                    $output .= '<label>' . $questions . '</label><span class="required" aria-required="true"> * </span>
                <div class="input-group">';


                    if ($option_part !== "") {
                        for ($j = 0; $j < sizeof($option_part[$z]); $j++) {
                            $options_id = $option_part[$z][$j]['option_id'];
                            $options = $option_part[$z][$j]['option_name'];

                            $output .= '<label><input type="radio" name="' . $i . '" value="' . $batch_id . "->" . $questions_id . ":" . $options_id . '" id="" >' . $options . '</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp';
                        }
                    }

                    $output .= '</div>';
                }
                //                                                        }

                $output .= '</div>
        </div>
    </div>
</div>
</div>';
            }
            $response['content_of_display'] = $output;
            if ($output != '') {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                $response['content_of_display'] = $output;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }

    //This function is for the load employee name from view survey only submited survey display
    public function get_employee_name_for_view_question_and_answer()
    {

        $seprate_batch_id = $this->input->post('seprate_batch_id');

        $query_get_id_from_batch = $this->db->query("select emp_id_after_submit from survey_batch_information_all where batch_id='$seprate_batch_id'");
        $res = $query_get_id_from_batch->row();
        $get_emp_id = $res->emp_id_after_submit;

        $seprate_emp_id = explode(",", $get_emp_id);
        $response['batch_id'] = $seprate_batch_id;

        $count_of_emp_id = count($seprate_emp_id);
        //echo $count_of_emp_id;

        for ($m = 0; $m < $count_of_emp_id; $m++) {

            $query_get_employee = $this->db->query("select user_id,user_name,survey_reject_time from user_header_all where user_id='$seprate_emp_id[$m]'");
            if ($query_get_employee->num_rows() > 0) {
                foreach ($query_get_employee->result() as $row) {



                    $response['my_employee_name'][] = ['user_name' => $row->user_name, 'user_id' => $row->user_id, 'survey_reject_time' => $row->survey_reject_time];
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                }
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }

        echo json_encode($response);
    }

    // this function for the display employee data of view employee modal
    public function get_employee_name()
    {

        $seprate_batch_id = $this->input->post('seprate_batch_id');

        $query_get_id_from_batch = $this->db->query("select emp_id from survey_batch_information_all where batch_id='$seprate_batch_id'");
        $res = $query_get_id_from_batch->row();
        $get_emp_id = $res->emp_id;

        $seprate_emp_id = explode(",", $get_emp_id);
        $response['batch_id'] = $seprate_batch_id;

        $count_of_emp_id = count($seprate_emp_id);


        for ($m = 0; $m < $count_of_emp_id; $m++) {

            $query_get_employee = $this->db->query("select user_id,user_name,survey_reject_time from user_header_all where user_id='$seprate_emp_id[$m]'");
            if ($query_get_employee->num_rows() > 0) {
                foreach ($query_get_employee->result() as $row) {
                    $response['my_employee_name'][] = ['user_name' => $row->user_name, 'user_id' => $row->user_id, 'survey_reject_time' => $row->survey_reject_time];
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                    $response['status'] = true;
                }
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }

        echo json_encode($response);
    }

    //We are working on this edit module

    public function update_batch()
    {
        $batch_id = $this->input->post('batch_id');
        // echo $batch_id;
        $qry_ = $this->hr_model->delete_batch_modal($batch_id);
        $this->db->where('batch_id', $qry);
        $this->db->update('user_header_all', $data);

        if ($qry_delete !== false) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'fail';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //this function is for the delete batch from hr module akshay
    public function delete_batch()
    {
        $batch_id = $this->input->post('batch_id');
        // echo $batch_id;
        $qry_delete = $this->hr_model->delete_batch_modal($batch_id);

        if ($qry_delete !== false) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'fail';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
        //echo"akshay";
    }

    public function hr_designation($firm_id = '')
    {
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $user_type = ($session_data['user_type']);
        $user_id = ($session_data['emp_id']);
        if ($user_type == 5) {
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $loginfirm_id = $result1['firm_id'];
            }
            $hr_auth = $this->db->query("select hr_authority from user_header_all where user_id='$user_id' AND user_type='5'");
            if ($this->db->affected_rows() > 0) {
                $record1 = $hr_auth->row();
                $firm_id = $record1->hr_authority;
            }
        } else {
            $firm_id = $firm_id;
        }

        $query_view_designation = $this->db->query("SELECT * FROM `designation_header_all` where `firm_id`= '$firm_id'");
        if ($query_view_designation->num_rows() > 0) {
            $record = $query_view_designation->result();
            $data['result_view_designation'] = $record;
        } else {
            $data['result_view_designation'] = "";
        }
        $result_firm_name_dd = $this->db->query("SELECT `firm_name`,`firm_id` FROM `partner_header_all` WHERE `firm_id`='$firm_id'");
        if ($result_firm_name_dd->num_rows() > 0) {
            $firm_result_dd = $result_firm_name_dd->row();
            $firm_name_dd = $firm_result_dd->firm_name;
            $firm_id_dd = $firm_result_dd->firm_id;
            $data['firm_name'] = $firm_name_dd;
        } else {
            $firm_name_dd = "";
            $firm_id_dd = "";
            $data['firm_name'] = "";
        }


        //var_dump($firm_id);

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

        $query_get_name = $this->db->query("select * from user_header_all where email='$email_id'");
        $rec = $query_get_name->row();
        $user_name = $rec->user_name;
        $user_id = $rec->user_id;
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
        $data['prev_title'] = "View Designation";
        $data['page_title'] = "View Designation";


        $this->load->view('human_resource/hr_designation', $data);
    }

    public function hr_view_employee($firm_id = '') {
        $result = $this->emp_model->get_employee_hq($firm_id);
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
        } else {
            $firm_name_dd = "";
            $firm_id_dd = "";
            $data['firm_name'] = "";
        }
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
        $query_get_name = $this->db->query("select * from user_header_all where email='$email_id'");
        $rec = $query_get_name->row();
        $user_name = $rec->user_name;
        $user_id = $rec->user_id;
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
        $data['prev_title'] = "View Employee";
        $data['page_title'] = "View Employee";


        $this->load->view('human_resource/hr_view_employee', $data);
    }

    public function hr_holiday($firm_id = '')
    {

        $query_event = $this->db->query("SELECT * from holiday_header_all where FIND_IN_SET('" . $firm_id . "', `holiday_applied_in`)");
        if ($query_event->num_rows() > 0) {
            $record_data = $query_event->result();
            $data['fetched_firm_id'] = $firm_id;
            $data['result_event'] = $record_data;
            // var_dump($result);
        } else {
            $data['result_event'] = "";
            $data['fetched_firm_id'] = "";
        }

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
        $query_get_name = $this->db->query("select * from user_header_all where email='$email_id'");
        $rec = $query_get_name->row();
        $user_name = $rec->user_name;
        $user_id = $rec->user_id;
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
        $data['prev_title'] = "Survey";
        $data['page_title'] = "Survey";
        $this->load->view('human_resource/hr_holiday', $data);
    }

    public function hr_working_paper()
    {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }

        $parent_id = $this->Nas_modal->folder_creation($firm_id, 1);

        if ($parent_id != false) {
            $data['parent_id'] = $parent_id;
        } else {
            $data['parent_id'] = "";
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
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
            $user_id = $result1['user_id'];
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
        $query_get_name = $this->db->query("select * from user_header_all where email='$email_id'");
        $rec = $query_get_name->row();
        $user_name = $rec->user_name;
        $user_id = $rec->user_id;
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


        $data['prev_title'] = "Working Paper";
        $data['page_title'] = "Working Paper";
        $result_working_ppr = $this->hr_model->get_working_ppr($email_id);

        if ($result_working_ppr !== FALSE) {
            $record_working_ppr = $result_working_ppr->result();
            $data['record_working_ppr'] = $record_working_ppr;
        } else {
            $data['record_working_ppr'] = "";
        }
        $this->load->view('human_resource/hr_view_working_paper', $data);
    }

    public function get_ddl_firm_name()
    {
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
            // echo $this->db->last_query();
            // echo $this->db->last_query();
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

    public function hr_edit_fun($user_id_hr)
    {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
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

        $result_hr = $this->hr_model->get_hr_data($user_id_hr);
        if ($result_hr->num_rows() > 0) {
            $record_res = $result_hr->result();

            $record = $result_hr->row();
            $designation_id = $record->designation_id;

            $query_desig = $this->hr_model->get_designation_data($designation_id);
            $record_desig = $query_desig->row();

            $query_leave = $this->hr_model->get_leave_data($designation_id);
            $record_leave = $query_leave->row();
        } else {
            $record = "";
            $record_desig = "";
            $record_leave = "";
        }

        $data['record_res'] = $record;
        $data['record_desig'] = $record_desig;
        $data['result_leave_data'] = $record_leave;
        $data['prev_title'] = "Edit Human Resources";
        $data['page_title'] = "Edit Human Resources";


        $this->load->view('human_resource/EditHumanResourse', $data);
    }

    public function update_hr_data()
    {
        $user_id = $this->input->post('user_id');
        $user_name = $this->input->post('user_name');
        $firm_id_ca = $this->input->post('firm_name');
        $count_firm = count((array) $firm_id_ca);
        $mobile_no = $this->input->post('mobile_no');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $state = $this->input->post('state');
        $city = $this->input->post('city');
        $country = $this->input->post('country');

        $date = date('y-m-d h:i:s');
        $designation_id = $this->input->post('desig_id');
        $star_rating = $this->input->post('star_rating');

        $email_check = $this->check_edit_email_avalibility($email, $user_id);
        $length_address = strlen($address);


        if (empty($user_name)) {
            $response['id'] = 'user_name';
            $response['error'] = 'Enter HR Name';
            echo json_encode($response);
        } elseif (!preg_match("/^[A-Za-z�������\s\ ]*$/", $user_name)) {
            $response['id'] = 'user_name';
            $response['error'] = 'Only Space Is Allowed';
            echo json_encode($response);
        } elseif (empty($mobile_no)) {
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter User Mobile No';
            echo json_encode($response);
        } elseif (!preg_match("/^\d{10}$/", $mobile_no)) { // phone number is valid
            $response['id'] = 'mobile_no';
            $response['error'] = 'Enter 10 Digit Mobile No.';
            echo json_encode($response);
        } elseif (empty($email)) {
            $response['id'] = 'email';
            $response['error'] = 'Enter Email Id';
            echo json_encode($response);
        } elseif (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) {
            $response['id'] = 'email';
            $response['error'] = "Invalid Email Format";
            echo json_encode($response);
        } elseif ($email_check == true) {
            $response['id'] = 'email';
            $response['error'] = "Email Is Already Exist";
            echo json_encode($response);
        } elseif (empty($address)) {
            $response['id'] = 'address';
            $response['error'] = 'Enter Proper Address';
            echo json_encode($response);
        } elseif ($length_address < 5) {
            $response['id'] = 'address';
            $response['error'] = 'Entered Address Must Be Greater Than 5 Characters';
            echo json_encode($response);
        } elseif (empty($country)) {
            $response['id'] = 'country';
            $response['error'] = 'Enter HR Country';
            echo json_encode($response);
        } elseif (empty($state)) {
            $response['id'] = 'state';
            $response['error'] = 'Enter HR State';
            echo json_encode($response);
        } elseif (empty($city)) {
            $response['id'] = 'city';
            $response['error'] = 'Enter HR State';
            echo json_encode($response);
        } else {

            // data user_header_all
            $data1 = array(
                'user_name' => $user_name,
                'email' => $email,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'mobile_no' => $mobile_no,
                'country' => $country,
                'modified_on' => $date,
                'user_star_rating' => $star_rating,
                'hr_authority' => $firm_id_ca,
            );
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
            }
            $array1 = array('firm_id' => $firm_id, 'user_id' => $user_id);
            $this->db->where($array1);
            $res_emp = $this->db->update('user_header_all', $data1);
            if ($res_emp == TRUE) {
                $this->db->set('hr_created', '1');
                $this->db->where('firm_id', $firm_id_ca);
                $this->db->update('partner_header_all');

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

    function check_edit_email_avalibility($email, $user_id)
    {
        $this->load->model("Emp_model");
        if ($this->Emp_model->is_edit_email_available($email, $user_id) == true) {
            return true;
            //                echo '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Email Is Already register</label>';
        } else {
            return false;
            //                echo '<label class="text-success"><span class="glyphicon glyphicon-ok"></span>Valid Email Id</label>';
        }
    }













    public function get_template()
    {
        $result_filled_by = $this->db->query("SELECT  `template_id`, `template_name`  FROM `template_header_all`");
        if ($result_filled_by->num_rows() > 0) {
            $data = array('template_id' => array(), 'template_name' => array());
            foreach ($result_filled_by->result() as $row) {
                $response['temp_data'][] = ['template_id' => $row->template_id, 'template_name' => $row->template_name];
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

    public function get_firms_for_token()
    {
        $result1 = $this->customer_model->get_firm_id();
        $user_id = $result1['user_id'];
        $result_filled_by = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($result_filled_by->num_rows() > 0) {
            $result = $result_filled_by->row();
            $firm_ids = $result->hr_authority;
            return $arr = explode(",", $firm_ids);
        } else {
            return false;
        }
    }

    public function get_firms()
    {
        $result1 = $this->customer_model->get_firm_id();
        $user_id = $result1['user_id'];
        $result_filled_by = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($result_filled_by->num_rows() > 0) {
            $result = $result_filled_by->row();
            $firm_ids = $result->hr_authority;
            $arr = explode(",", $firm_ids);
            $cnt = count($arr);
            //            echo $cnt;
            for ($i = 0; $i < $cnt; $i++) {
                $firm_id = $arr[$i];
                //                echo "select * from partner_header_all where firm_id='$firm_id'";
                $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
                $res = $query_get_firm->row();
                $response['firm_data'][] = ['firm_id' => $res->firm_id, 'firm_name' => $res->firm_name];
            }

            //            print_r($response[ 'firm_data']);
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

    public function get_firms_for_hq()
    {
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

        $firm_id_for_not = $this->input->post('a1');


        $firm_id_for_not_finial = substr($firm_id_for_not, 1);

        $firm_id_for_not_finial_array = explode(':', $firm_id_for_not_finial);

        $result1 = $this->customer_model->get_firm_id();
        $user_id = $result1['user_id'];
        $firm_id_for_not_finial = explode(":", $firm_id_for_not);
        $counter_for_length_of_firms = count($firm_id_for_not_finial);


        //print_r($counter_for_length_of_firms);
        //  print_r( $firm_id_for_not_finial);
        $query_for_get_boss_id = $this->db->query("select boss_id from partner_header_all where firm_email_id='$email_id'");
        if ($query_for_get_boss_id->num_rows() > 0) {
            foreach ($query_for_get_boss_id->result() as $row) {
                $boos_id = $row->boss_id;
            }
        } else {
            $boos_id = "";
        }
        $sql = "";
        for ($i = 0; $i < count($firm_id_for_not_finial_array); $i++) {
            $sql .= "firm_id<>" . "'" . $firm_id_for_not_finial_array[$i] . "'" . " and ";
        }

        $sql_finial = substr($sql, 0, -4);

        //            $firm_id = $firm_id_for_not_finial_array[$i];
        //             echo "select * from partner_header_all where firm_id='$firm_id'";
        $query_get_firm = $this->db->query("select * from partner_header_all where (reporting_to='$boos_id')" . "and(" . $sql_finial . ")");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
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

    public function get_employee()
    {

        $firm_id = $this->input->post('firm_id');
        //print_r($firm_id);
        //echo $firm_id;
        $all_result = [];
        $firm_id_length = count($firm_id);

        for ($i = 0; $i < $firm_id_length; $i++) {

            $query_get_employee = $this->db->query("select firm_id,designation_id,designation_name from designation_header_all where firm_id='$firm_id[$i]]'");

            if ($query_get_employee->num_rows() > 0) {
                $data = array('firm_id' => array(), 'designation_name' => array());
                foreach ($query_get_employee->result() as $row) {
                    $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id' and batch_id='0'");
                    $res = $query_get_firm->row();
                    $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id, 'firm_name' => $res->firm_name];
                }
                // print_r($response);
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

    //this function is for the load reamingin firms after add anoter firms
    public function get_remain_firms()
    {

        $firm_id_for_not = $this->input->post('a1');
        //echo $firm_id_for_not;
        // $firm_id_for_not_finial = rtrim($firm_id_for_not,":");

        $result1 = $this->customer_model->get_firm_id();
        $user_id = $result1['user_id'];
        $firm_id_for_not_finial = explode(":", $firm_id_for_not);
        $counter_for_length_of_firms = count($firm_id_for_not_finial);
        //print_r($counter_for_length_of_firms);
        //  print_r( $firm_id_for_not_finial);

        $result_filled_by = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($result_filled_by->num_rows() > 0) {
            $result = $result_filled_by->row();
            $firm_ids = $result->hr_authority;
            $arr = explode(",", $firm_ids);
            $count_of_firm_id_for_not = strlen($firm_id_for_not);
            $array_of_firm_id_for_not = str_split($firm_id_for_not, $count_of_firm_id_for_not);
            $finial_array_of_firms = array_diff($arr, $firm_id_for_not_finial);
            //  print_r($finial_array_of_firms);
            $my_array = array_values($finial_array_of_firms);
            $cnt = count($my_array);
            // print_r($my_array);
            for ($i = 0; $i < $cnt; $i++) {
                $firm_id = $my_array[$i];
                //             echo "select * from partner_header_all where firm_id='$firm_id'";
                $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
                $res = $query_get_firm->row();
                $response['firm_data'][] = ['firm_id' => $res->firm_id, 'firm_name' => $res->firm_name];
            }

            //            print_r($response[ 'firm_data']);
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
        //
    }

    //this function is for the load remain firms of hq admin to send the warning
    public function get_remain_firms_for_hq()
    {
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

        $firm_id_for_not = $this->input->post('a1');


        $firm_id_for_not_finial = substr($firm_id_for_not, 1);

        $firm_id_for_not_finial_array = explode(':', $firm_id_for_not_finial);

        $result1 = $this->customer_model->get_firm_id();
        $user_id = $result1['user_id'];
        $firm_id_for_not_finial = explode(":", $firm_id_for_not);
        $counter_for_length_of_firms = count($firm_id_for_not_finial);


        //print_r($counter_for_length_of_firms);
        //  print_r( $firm_id_for_not_finial);
        $query_for_get_boss_id = $this->db->query("select boss_id from partner_header_all where firm_email_id='$email_id'");
        if ($query_for_get_boss_id->num_rows() > 0) {
            foreach ($query_for_get_boss_id->result() as $row) {
                $boos_id = $row->boss_id;
            }
        } else {
        }
        $sql = "";
        for ($i = 0; $i < count($firm_id_for_not_finial_array); $i++) {
            $sql .= "firm_id<>" . "'" . $firm_id_for_not_finial_array[$i] . "'" . " and ";
        }

        $sql_finial = substr($sql, 0, -4);

        //            $firm_id = $firm_id_for_not_finial_array[$i];
        //             echo "select * from partner_header_all where firm_id='$firm_id'";
        $query_get_firm = $this->db->query("select * from partner_header_all where (reporting_to='$boos_id')and (boss_id<>'$boos_id')" . "and(" . $sql_finial . ")");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }


        //            print_r($response[ 'firm_data']);
        $response['message'] = 'success';
        $response['code'] = 200;
        $response['status'] = true;

        echo json_encode($response);
        //
    }

    public function get_employee_demo()
    {
        $firm_id = $this->input->post('firm_id');
        //        $all_result = [];
        // echo $firm_id;
        if ($firm_id != '0') {

            //echo $firm_id_length;
            //echo $firm_id[$i];
            $query_get_employee = $this->db->query("select firm_id,designation_id,designation_name from designation_header_all where firm_id='$firm_id'");
            if ($query_get_employee->num_rows() > 0) {

                foreach ($query_get_employee->result() as $row) {
                    // $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id];
                    //$degination_id=$row->designation_id;
                    // $degination_name=$row->designation_name;
                    //$degination_id ;
                    //$degination_name;
                    $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
                    $res = $query_get_firm->row();
                    //$firmName=$res->firm_name;
                    //  echo $firmName;
                    $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id, 'firm_name' => $res->firm_name];
                    // print_r($response);
                    // $response['emp_data'][]=[$degination_id,$degination_name];
                }
                $query_get_employee_from_user_header_all = $this->db->query("select user_id,user_name,designation_id from user_header_all where firm_id='$firm_id' and user_type='4'");
                if ($query_get_employee_from_user_header_all->num_rows() > 0) {
                    $data1 = array('user_id' => array(), 'user_name' => array());
                    foreach ($query_get_employee_from_user_header_all->result() as $row) {
                        $response['emp_data_individual'][] = ['user_id' => $row->user_id, 'designation_id' => $row->designation_id, 'user_name' => $row->user_name];
                    }

                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {

                    $response['emp_data'][] = "";
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
        } else {
            //  echo"i am null";
            $response['emp_data'][] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_individual_employee()
    {
        $firm_id = $this->input->post('firm_id');
        if ($firm_id != '0') {



            $query_get_employee_from_user_header_all = $this->db->query("select user_id,user_name from user_header_all where firm_id='$firm_id' and user_type='4'");
            if ($query_get_employee_from_user_header_all->num_rows() > 0) {
                $data1 = array('user_id' => array(), 'user_name' => array());
                foreach ($query_get_employee_from_user_header_all->result() as $row) {
                    $response['emp_data_individual'][] = ['user_id' => $row->user_id, 'user_name' => $row->user_name];
                }
                //print_r($response);
                //            print_r($response);
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['emp_data_individual'][] = "";
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $response['emp_data_individual'][] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //This function is for the remove the employee from select batch
    public function remove_employee()
    {
        $batch_id = $this->input->post('batch_id');
        $user_id = $this->input->post('employee_id');
        $query_get_batch_from_user_header_all = $this->db->query("select batch_id from user_header_all where user_id='$user_id' and user_type='4'");
        if ($query_get_batch_from_user_header_all->num_rows() > 0) {
            foreach ($query_get_batch_from_user_header_all->result() as $row) {
                $all_batch_ids = $row->batch_id;
            }
        }

        $concat_batch_id_with_comma = ',' . $batch_id;
        $all_batch_ids_finial = substr($all_batch_ids, 0);
        $all_batch_ids_finial_array = explode(",", $all_batch_ids_finial);
        $string_to_array_of_batch_id = explode(',', $concat_batch_id_with_comma);
        unset($string_to_array_of_batch_id[0]);

        $new_array_after_remove = array_diff($all_batch_ids_finial_array, $string_to_array_of_batch_id);
        $new_array_after_remove_finial = array_values($new_array_after_remove);
        $finial_string_after_removing_main_batch = implode(',', $new_array_after_remove_finial);
        $query_get_user_from_survey_information_all = $this->db->query("select emp_id from survey_batch_information_all where batch_id='$batch_id'");
        if ($query_get_user_from_survey_information_all->num_rows() > 0) {
            foreach ($query_get_user_from_survey_information_all->result() as $row) {
                $all_emp_ids = $row->emp_id;
            }
        }
        $concat_user_id_with_comma = ',' . $user_id;
        $all_user_ids_finial = substr($all_emp_ids, 0);
        $all_user_ids_finial_array = explode(",", $all_user_ids_finial);
        $string_to_array_of_user_id = explode(',', $concat_user_id_with_comma);
        unset($string_to_array_of_batch_id[0]);

        $new_array_after_remove_of_user = array_diff($all_user_ids_finial_array, $string_to_array_of_user_id);
        $new_array_after_remove_finial_of_user = array_values($new_array_after_remove_of_user);
        $finial_string_after_removing_main_batch_of_user = implode(',', $new_array_after_remove_finial_of_user);
        $query_for_remove_employee_from_user_header_all = $this->db->query("update user_header_all set batch_id='$finial_string_after_removing_main_batch' where user_id='$user_id'");
        $query_for_remove_employee_from_survey_batch_information_all = $this->db->query("update survey_batch_information_all set emp_id='$finial_string_after_removing_main_batch_of_user' where batch_id='$batch_id'");
        if ($query_for_remove_employee_from_user_header_all && $query_for_remove_employee_from_survey_batch_information_all) {
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

    //this function for save the survey batch akshay
    public function save_survey()
    {
        $result1 = $this->customer_model->get_firm_id();
        $template_id = $this->input->post('template_id');
        $batch_name = $this->input->post('batch_name');

        // $ftemplate_id= implode(",", $template_id);

        $firm_id = $this->input->post('firms');
        $counter_for_show_error = $this->input->post('loop_counter_for_get_values');


        $designation_id = $this->input->post('designation_id');
        $employee_id = $this->input->post('employees');
        // print_r($employee_id);
        for ($j = 0; $j < $counter_for_show_error - 1; $j++) {
            if ($employee_id[$j] == '') {
                //echo"Employee is empty";
                $my_all_array_string1 = "";
            } else {
            }
        }
        $res_emp2 = array();
        $my_all_array_string1 = "";
        for ($x = 0; $x < count($employee_id); $x++) {
            for ($z = 0; $z < count($employee_id[$x]); $z++) {
                // echo $employee_id[$x][$z];
                $res_emp2[] = array_push($res_emp2, $employee_id[$x][$z]);
                $my_all_array_string1 .= $employee_id[$x][$z] . ',';
            }
        }

        //print_r($employee_id);
        //        $count_emp_id = count($employee_id);

        $string123 = substr($my_all_array_string1, 0, -1);
        // echo $string123;
        // print_r($res_emp2);
        $emp_id_n = explode(",", $string123);
        //print_r($emp_id_n);
        // exit();
        $count_firm = count($firm_id);
        $res_firm = array();
        for ($j = 0; $j < $count_firm; $j++) {

            $res_firm[] = $firm_id[$j];
        }
        $firm_id_n = implode(",", $firm_id);
        //echo $firm_id_n;
        $res_emp = array();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $result1['user_id'];
        //$fuser_id= implode(",", $user_id);
        $batch_id = $this->generate_batch_id();
        $created_on = date("Y-m-d");
        $batch_type = $this->input->post('batch_type');
        $diff = abs(strtotime($created_on) - strtotime($start_date));
        $no_of_users = 2;
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        //        $designation_id_length = count($designation_id);
        $employee_id_length = count($emp_id_n);
        //        $firm_id_length = count($firm_id);

        if ($days > 0) {
            $batch_status = 0;
        } else {
            $batch_status = 1;
        }

        if (empty(trim($batch_name))) {
            $response['id'] = 'batch_name';
            $response['error'] = 'Enter Batch Name';
        } elseif (empty(trim($template_id))) {
            $response['id'] = 'template_name';
            $response['error'] = 'Select Template';
        } elseif ($firm_id[$counter_for_show_error - 1] == "") {

            $response['id'] = 'firm_name' . $counter_for_show_error;
            $response['error'] = 'Select Office';
        } elseif ($string123 == "") {
            for ($x = 1; $x <= loop_counter_for_get_values; $x++) {
                $response['id'] = 'optiona' . $z;
                $response['error'] = 'Select Designation or Employee';
            }
        } elseif ($start_date == "") {
            $response['id'] = 'servey_start_date';
            $response['error'] = 'Select Survey Start Date';
        } elseif (empty($end_date)) {
            $response['id'] = 'servey_end_date';
            $response['error'] = 'Select Survey End Date';
        } else {
            $adata = 0;
            for ($j = 0; $j < $employee_id_length; $j++) {
                $query_send_email_to_emp = $this->db->query("select * from user_header_all  where user_id='$emp_id_n[$j]'");

                foreach ($query_send_email_to_emp->result() as $row) {
                    $batch_id_fetch = $row->batch_id;
                    $fetch_survey_status = $row->survey_status;
                    $fetch_survey_rejection_cunter = $row->survey_reject_time;
                    if ($batch_id_fetch == "") {
                        $batch_id_fetch_new = $batch_id;
                    } else {
                        $batch_id_fetch_new = $batch_id_fetch . "," . $batch_id;
                    }
                    if ($fetch_survey_status == "") {
                        $new_survey_status = $batch_id . ":1";
                    } else {
                        $new_survey_status = $fetch_survey_status . "," . $batch_id . ":1";
                    }

                    if ($fetch_survey_rejection_cunter == "") {
                        $new_survey_rejection_counter = $batch_id . ":0";
                    } else {
                        $new_survey_rejection_counter = $fetch_survey_rejection_cunter . "," . $batch_id . ":0";
                    }

                    //                    $batch_id_fetch_new = $batch_id_fetch . "," . $batch_id;
                    $data_for_send_email['emp_data_to_send_email'][] = ['user_id' => $row->user_id, 'user_name' => $row->user_name, 'email' => $row->email, 'mobile_no' => $row->mobile_no];
                }
                //                echo $batch_id_fetch_new;
                //                $query_update_survey_status_emp = $this->db->query("insert into user_header_all(batch_id) values('$batch_id') where user_id='$emp_id_n[$j]'");
                $query_insert_emp_id_emp = $this->db->query("update user_header_all set batch_id='$batch_id_fetch_new',survey_status='$new_survey_status',survey_reject_time='$new_survey_rejection_counter' where user_id='$emp_id_n[$j]'");
            }
            if ($query_insert_emp_id_emp === true) {
                $query_save_survey_batch = $this->db->query("insert into survey_batch_information_all(batch_id,firm_id,batch_name,template_id,no_of_user,created_on,"
                    . "created_by_user_id,batch_status,batch_end_date,batch_start_on,batch_completed_on,batch_type,emp_id)values('$batch_id','$firm_id_n','$batch_name','$template_id','$no_of_users','$created_on',"
                    . "'$user_id','$batch_status','$end_date','$start_date','$start_date','$batch_type','$string123')");
                $data_for_send_email_array = $data_for_send_email['emp_data_to_send_email'];
                $email_count = count($data_for_send_email_array);
                $auth_key = "178904AVN94GK259e5e87b";
                $message = "Hello Dear,Employee You Have Received Survey Realating to our company.You Must requried fill this Survey.To fill the Survey Login to your Dashboard ";
                for ($a = 0; $a < $email_count; $a++) {
                    $emp_name = $data_for_send_email_array[$a]['user_name'];
                    $emp_email = $data_for_send_email_array[$a]['email'];
                    $emp_mobile = $data_for_send_email_array[$a]['mobile_no'];
                    $sms = $this->email_sending_model->SendReminderSmstoemp($auth_key, $emp_mobile, $emp_name, $message);
                    $adata++;
                }
            } else {
            }


            if ($adata > 0) {
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

    public function generate_batch_id()
    {
        $batch_id = 'Bat_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('survey_batch_information_all');
        $this->db->where('batch_id', $batch_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return generate_batch_id();
        } else {
            return $batch_id;
        }
    }

    public function add_working_ppr()
    {
        $firm_id = $this->input->post('firm_name');
        $is_downloadable = $this->input->post('is_downloadable');
        $file = $this->input->post('fileName');
        $file1 = $this->input->post('image_file');

        $file_description = $this->input->post('file_description');
        $file_transaction_id = $this->input->post('file_trans_id');
        // $file = $this->upload_image($firm_id);


        $created_by = $this->session->userdata('login_session');
        $created_on = date("Y-m-d H:i:s");

        //        var_dump($_POST);
        //        var_dump($_FILES);
        if (empty($firm_id)) {
            $response['id'] = 'firm_name';
            $response['error'] = 'Select Office';
            echo json_encode($response);
            exit;
        } elseif (empty($file_description)) {
            $response['id'] = 'file_description';
            $response['error'] = 'Enter File Description';
            echo json_encode($response);
        } elseif ($file == "invalid") {
            $response['id'] = 'image_file';
            $response['error'] = 'Select File With Extension (pdf) And 5mb Size.';
            echo json_encode($response);
        } else {
            $count = count($firm_id);
            for ($i = 0; $i < $count; $i++) {
                $data = array(
                    'firm_id' => $firm_id[$i],
                    'is_downloadable' => $is_downloadable,
                    'uploaded_file' => $file,
                    'file_description' => $file_description,
                    'created_by' => $created_by,
                    'created_on' => $created_on,
                    'file_id' => $file_transaction_id
                );


                $result = $this->hr_model->insert_working_paper($data);

                if ($result === TRUE) {
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
    }

    public function upload_image($firm_id)
    {
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
            $config['upload_path'] = './uploads/hr/';
            $target_path = './uploads/hr/thumbs/';
            $config['allowed_types'] = 'pdf';
            //                $config['allowed_types'] = 'pdf';
            $config['max_size'] = '1000000';    //limit 10000=1 mb
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

    public function delete_working_file()
    {
        $id = $this->input->post("id");
        $result = $this->hr_model->delete_working_file($id);
        if ($result === TRUE) {
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

    public function get_workingppr_data()
    {
        $id = $this->input->post("work_id");
        $firm_id = $this->input->post("firm_id");
        $result_working_ppr = $this->hr_model->get_working_ppr_edit($id, $firm_id);
        if ($result_working_ppr->num_rows() > 0) {
            $record_working_ppr = $result_working_ppr->row();
            $data = '<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="icheck-inline">
                <label>Is Downloadable</label>
                <div class="input-group">
                    <label>';
            if ($record_working_ppr->is_downloadable == '1') {
                $data .= '<input type="radio" id="is_downloadable_edit" name="is_downloadable_edit" checked value="1"  data-checkbox="icheckbox_flat-grey"> yes </label> &nbsp;
                    <label>
                        <input type="radio" id="is_downloadable" name="is_downloadable" value="0" data-checkbox="icheckbox_flat-grey" > No </label>';
            } else {
                $data .= '<input type="radio" id="is_downloadable_edit" name="is_downloadable_edit"  value="1"  data-checkbox="icheckbox_flat-grey"> yes </label> &nbsp;
                    <label><input type="radio" id="is_downloadable" name="is_downloadable" checked value="0" data-checkbox="icheckbox_flat-grey" > No </label>';
            }

            $data .= ' </div>
            </div>
        </div>
        <div class="col-md-6">
            <label>File Description</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-globe"></i>
                </span>
                <textarea class="form-control" name="file_description_edit" onkeyup="remove_error()" id="file_description_edit" rows="3"> '
                . $record_working_ppr->file_description .
                '</textarea>   </div>
            <span class="required" id="file_description_error"></span>
        </div>
    </div>
</div>
</div>';
            $response['record_working_ppr'] = $data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['record_working_ppr'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function edit_working_ppr()
    {
        $firm_id = $this->input->post('firm_id');
        $work_id = $this->input->post('work_id');
        $is_downloadable = $this->input->post('is_downloadable_edit');
        $file_description = $this->input->post('file_description_edit');
        $update_on = date("Y-m-d H:i:s");

        if (empty($file_description)) {
            $response['id'] = 'file_description_edit';
            $response['error'] = 'Enter File Description';
        } else {
            $data = array(
                'is_downloadable' => $is_downloadable,
                'file_description' => $file_description,
                'updated_on' => $update_on,
            );
            $update_working_ppr = $this->hr_model->upadte_working_ppr($work_id, $firm_id, $data);
            if ($update_working_ppr === TRUE) {
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

    public function Leave_request_hr()
    {
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

        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $firm_id = $record->firm_id;
            $senior_id = $record->senior_user_id;
            $designation_id = $record->designation_id;
            $result3 = $this->db->query("SELECT user_name FROM `user_header_all` WHERE `user_id`='$senior_id'");
            if ($result3->num_rows() > 0) {
                $record_sen_name = $result3->row();
                $senior_name = $record_sen_name->user_name;
            } else {
                $senior_name = '';
            }
        }
        $query_fetch_leave = $this->db->query("SELECT * FROM `leave_transaction_all` WHERE `user_id`='$user_id'");
        if ($query_fetch_leave->num_rows() > 0) {
            $record_fetch_leave = $query_fetch_leave->result();
        } else {
            $record_fetch_leave = '';
        }
        $query_fetch_boss_id = $this->db->query("SELECT * FROM partner_header_all where firm_id='$firm_id' ");
        if ($query_fetch_boss_id->num_rows() > 0) {
            $record_fetch_boss_id = $query_fetch_boss_id->row();
            $boss_id = $record_fetch_boss_id->reporting_to;
        } else {
            $record_fetch_boss_id = '';
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


        $data['user_id'] = $user_id;
        $data['boss_id'] = $boss_id;
        $data['firm_id'] = $firm_id;
        $data['result'] = $record_fetch_leave;
        $data['senior_id'] = $senior_id;
        $data['senior_name'] = $senior_name;
        $data['designation_id'] = $designation_id;
        $data['prev_title'] = "Leave Request";
        $data['page_title'] = "Leave Request";
        $this->load->view('human_resource/leave_request', $data);
    }

    public function Leave_approve_hr()
    {
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

        $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,`user_header_all`.`senior_user_id`,
`user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,
`leave_transaction_all`.`leave_requested_on`
FROM `leave_transaction_all`
INNER JOIN `user_header_all`
ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
WHERE `user_header_all`.`senior_user_id`='$user_id' ORDER BY leave_date DESC");

        if ($fetch_data->num_rows() > 0) {
            $record_fetch = $fetch_data->result();
        } else {
            $record_fetch = '';
        }

        $data['record_fetch'] = $record_fetch;
        $data['prev_title'] = "Leave Approve";
        $data['page_title'] = "Leave Approve";


        $this->load->view('human_resource/leave_approve', $data);
    }


    public function getEmployeeList() {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        //get firm_id
        $firm_id = '';
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        }
        $option = '<option value="" selected>Select One</option>';
        if ($firm_id != '') {

            $result = $this->db->query('SELECT * FROM Payroll.user_header_all where firm_id = "' . $firm_id . '" and user_type = 4 and activity_status = 1')->result();
            if (count($result) > 0) {

                foreach ($result as $row) {
                    $option .= "<option value='" . $row->user_id . "'>" . $row->user_name . "</option>";
                }
            }
            $response['status'] = 200;
            $response['body'] = "Employee Found";
            $response['data'] = $option;
        } else {
            $response['status'] = 200;
            $response['data'] = $option;
            $response['body'] = "Firm ID Not Found";
        }
        echo json_encode($response);
    }


    // Start View Holiday and Special Days Page
        public function specialDays() {
            $user = $this->session->userdata('login_session');
            $data['prev_title'] = "Greeting";
            $data['page_title'] = "Greeting";
            if( empty($user)) {
                $data['user'] = '';
            } else {
                $data['user'] = $user;
            }
            $query = "SELECT firm_id FROM firm_location";
            $firmData = $this->db->query($query);
            if($firmData->num_rows() > 0) {
                $data['firms'] = $firmData->result();
            } else {
                $data['firms'] = '';
            }

            $this->load->view('human_resource/special_days', $data);
        }

        public function getHolidayEventList() {
            try {
                $user = $this->session->userdata('login_session');
                $draw    = intval($this->input->post('draw'));
                $type    = $this->input->post('type');
                $data    = [];
                if(empty($user)) {
                    throw new Exception("User not logged in.");
                } else {
                    $userType = $user['user_type'];
                }
                if($type == 'holiday-section') {
                    $query = $this->hr_model->getHolidayMasterList();
                    $data = $query->result();
                } else if($type == 'event-section') {
                    $query = $this->hr_model->getEventMasterList();
                    $data = $query->result();
                } else {
                    throw new Exception("Invalid type specified.");
                }
                
                echo json_encode([
                    'status' => 'success',
                    "draw" => $draw,
                    "recordsTotal" => count($data),
                    "recordsFiltered" => count($data),
                    "data" => $data,
                    "userType" => $userType
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'data' => []
                ]);
            }
        }

        public function sampleExcelDownload1() {
            try {
                $sheetSize = 100;
                $currentYear = date('Y');
                $currentMonth = date('m');
                $fileName = 'sample_holiday_excel.xlsx';
                $columns = ['Holiday Name', 'Holiday Color Code', 'Description', 'Holiday Day', 'Location Based Active Holidays', 'Holiday Start Date (YYYY-MM-DD)', 'Holiday End Date (YYYY-MM-DD)', 'Holiday Applied In (Firm Location)', 'Holiday Image'];
                $query = "SELECT firm_id FROM firm_location";
                $firmData = $this->db->query($query);
                if($firmData->num_rows() > 0) {
                    $firmIds = [];
                    foreach($firmData->result() as $firm) {
                        // $firmIds[] = "All Location";
                        $firmIds[] = $firm->firm_id;
                    }
                } else {
                    $firmIds = '';
                }

                $holidayIds = [
                    "$currentYear". '_' . '1', "$currentYear". '_' . '2', "$currentYear". '_' . '3', "$currentYear". '_' . '4', "$currentYear". '_' . '5', "$currentYear". '_' . '6', "$currentYear". '_' . '7', "$currentYear". '_' . '8', "$currentYear". '_' . '9', "$currentYear". '_' . '10', "$currentYear". '_' . '11', "$currentYear". '_' . '12', "$currentYear". '_' . '13', "$currentYear". '_' . '14', "$currentYear". '_' . '15', "$currentYear". '_' . '16', "$currentYear". '_' . '17', "$currentYear". '_' . '18', "$currentYear". '_' . '19', "$currentYear". '_' . '20', "$currentYear". '_' . '21', "$currentYear". '_' . '22', "$currentYear". '_'. 23
                ];

                $holidayData = [
                    ['New Year\'s Day', '#1ABC9C', 'New Year Celebration'],
                    ['Makar Sankranti / Pongal', '#F39C12', 'Harvest Festival'],
                    ['Republic Day', '#FF5733', 'Celebration of Republic Day'],
                    ['Maha Shivratri', '#8E44AD', 'Festival of Lord Shiva'],
                    ['Holi', '#E74C3C', 'Festival of Colors'],
                    ['Id-ul-Fitr*', '#27AE60', 'End of Ramadan'],
                    ['Mahavir Jayanti', '#16A085', 'Birth of Lord Mahavir'],
                    ['Good Friday', '#34495E', 'Christian Observance'],
                    ['Buddha Purnima', '#2ECC71', 'Birth of Lord Buddha'],
                    ['Id-ul-Zuha (Bakrid)*', '#229954', 'Festival of Sacrifice'],
                    ['Muharram*', '#7D3C98', 'Islamic New Year'],
                    ['Independence Day', '#33FF57', 'Celebration of Independence Day'],
                    ['Id-e-Milad*', '#1F618D', 'Birth of Prophet Muhammad'],
                    ['Ganesh Chaturthi', '#D68910', 'Birth of Lord Ganesha'],
                    ['Gandhi Jayanti', '#566573', 'Birth of Mahatma Gandhi'],
                    ['Dussehra (Vijayadashami)', '#CB4335', 'Victory of Good over Evil'],
                    ['Naraka Chaturdasi',  '#AF7AC5', 'Diwali Ritual Day'],
                    ['Diwali',  '#3357FF', 'Festival of Lights'],
                    ['Govardhan Puja',  '#F4D03F', 'Worship of Govardhan Hill'],
                    ['Bhai Dooj',  '#EC7063', 'Bond of Brother and Sister'],
                    ['Chhath Puja',  '#DC7633', 'Worship of Sun God'],
                    ['Guru Nanak Jayanti',  '#48C9B0', 'Birth of Guru Nanak Dev Ji'],
                    ['Christmas',  '#C0392B', 'Birth of Jesus Christ'],
                ];

                $holidayDays = [
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
                ];

                $activeAvalaible = [
                    'Show', 'Hide'
                ];

                $holidayNames = [];
                $holidayColorCodes = [];
                $holidayDescriptions = [];
                foreach($holidayData as $holiday) {
                    $holidayNames[] = $holiday[0];
                    $holidayColorCodes[] = $holiday[1];
                    $holidayDescriptions[] = $holiday[2];
                }

                $objPHPExcel = new PHPExcel();
                $sheet = $objPHPExcel->setActiveSheetIndex(0);
                $rowNumber = 2;
                $col = 'A';

                foreach ($columns as $columnName) {
                    $sheet->setCellValue($col.'1', $columnName);
                    $sheet->getStyle($col.'1')->getFont()->setBold(true);
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    $col++;
                }

                foreach ($holidayData as $data) {
                    $col = 'A';
                    foreach ($data as $value) {
                        $sheet->setCellValue($col.$rowNumber, $value);
                        $col++;
                    }
                    $this->setDropdown($sheet, 'A'.$rowNumber, $holidayNames);
                    $this->setDropdown($sheet, 'B'.$rowNumber, $holidayColorCodes);
                    $this->setDropdown($sheet, 'C'.$rowNumber, $holidayDescriptions);
                    $this->setDropdown($sheet, 'D'.$rowNumber, $holidayDays);
                    $this->setDropdown($sheet, 'E'.$rowNumber, $activeAvalaible);
                    $this->setDropdown($sheet, 'H'.$rowNumber, $firmIds);
                    $rowNumber++;
                }

                $fileName = "sample_holiday_excel_$currentYear.xlsx";

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$fileName.'"');
                header('Cache-Control: max-age=0');

                $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $writer->save('php://output');
                exit;
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'data' => []
                ]);
            }
        }

        public function sampleExcelDownload() {
            $id = $this->input->get('id');
            $filePath = '';
            if($id == 'holidayId') {
                $filePath = './uploads/sample_holiday_excel_2026.xlsx';
            } elseif($id == 'eventId') {
                $filePath = './uploads/sample_event_excel_2026.xlsx';
            }
            if(file_exists($filePath)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            } else {
                echo "Sample file not found.";
            }
        }

        public function sampleEventExcelDownload() {
            try {
                $id = $this->input->get('id');
                $currentYear = date('Y');
                $fileName = "sample_event_excel_$currentYear.xlsx";
                $columns = [
                    'Event Name',
                    'Event Type',
                    'Applied Location',
                    'Event Start Date (YYYY-MM-DD)',
                    'Event End Date (YYYY-MM-DD)',
                    'Event During Hours',
                    'Message'
                ];
                
                // Location dropdown values
                $locationQuery = $this->db->query("SELECT firm_id FROM firm_location");
                $appliedLocation = [];
                if ($locationQuery->num_rows() > 0) {
                    foreach ($locationQuery->result() as $firm) {
                        $appliedLocation[] = $firm->firm_id;
                    }
                }

                // Event type dropdown
                $query = $this->hr_model->getEventTypeList();
                $eventType = []; 
                if ($query->num_rows() > 0) {
                    $events = $query->result();
                    foreach($events as $event) {
                        $eventType[] = $event->type;
                    }
                } else {
                    $eventType = [];
                }
                $eventHours = [
                    'Full Day',
                    'Half Day - Morning',
                    'Half Day - Afternoon',
                    '2 Hours',
                    '3 Hours',
                    '4 Hours',
                    '5 Hours',
                ];

                $objPHPExcel = new PHPExcel();
                $sheet = $objPHPExcel->setActiveSheetIndex(0);
                $col = 'A';
                foreach ($columns as $columnName) {
                    $sheet->setCellValue($col.'1', $columnName);
                    $sheet->getStyle($col.'1')->getFont()->setBold(true);
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    $col++;
                }

                for ($row = 2; $row <= 101; $row++) {
                    $sheet->setCellValue('A'.$row, '');
                    $sheet->setCellValue('B'.$row, '');
                    $sheet->setCellValue('E'.$row, '');
                    $this->setDropdown($sheet, 'B'.$row, $eventType); // Event Type
                    $this->setDropdown($sheet, 'C'.$row, $appliedLocation); // Location
                    $this->setDropdown($sheet, 'F'.$row, $eventHours); // Event During Hours
                }

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$fileName.'"');
                header('Cache-Control: max-age=0');
                $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $writer->save('php://output');
                exit;
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }

        public function uploadHolidayEventExcel() {
            try {
                $type = $this->input->post('type');
                $user = $this->session->userdata('login_session');
                if(empty($user)) {
                    $response['status'] = 401;
                    $response['message'] = "Unauthorized: Please log in.";
                    echo json_encode($response);
                    return;
                }
                $file_mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                if(isset($_FILES['excel_input_file']['name']) && in_array($_FILES['excel_input_file']['type'], $file_mimes)) { 
                    $arr_file = explode('.', $_FILES['excel_input_file']['name']);
                    $extension = end($arr_file);
                    if('csv' == $extension) {
                        $reader = new PHPExcel_Reader_CSV();
                    } else {
                        $reader = new PHPExcel_Reader_Excel2007();
                    }
                    $objPHPExcel = $reader->load($_FILES['excel_input_file']['tmp_name']);
                    $sheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true ,true);
                    if($type == 'holiday') {
                        if(!empty($sheet)) {
                            foreach($sheet as $key => $row) {
                                if($key == 1) {
                                    continue; // Skip header row
                                }
                                
                                if($row['E'] === 'Show') {
                                    $row['E'] = 1;
                                } else if($row['E'] === 'Hide') {
                                    $row['E'] = 0;
                                }
                                $startDate = date('Y-m-d', strtotime($row['F']));
                                $endDate = date('Y-m-d', strtotime($row['G']));
                                // dd(array_keys($row), $row);
                                $holidayData = [
                                    'holiday_name' => $row['A'],
                                    'holiday_color_code' => $row['B'],
                                    'description' => $row['C'],
                                    'holiday_day' => $row['D'],
                                    'holiday_start_date' => $startDate,
                                    'holiday_end_date' => $endDate,
                                    'firm_id' => $row['H'] ?? NULL,
                                    'is_active' => $row['E'],
                                    'date' => $startDate,
                                    'category' => 1,
                                    'bulk_upload' => 1,
                                    'created_on' => date('Y-m-d H:i:s'),
                                ];
                                $this->db->insert('holiday_master_all', $holidayData);
                            }
                            $response['status'] = 200;
                            $response['message'] = "Holiday uploaded successfully.";
                            echo json_encode($response);
                            return;
                        } else {
                            $response['status'] = 400;
                            $response['message'] = "The uploaded file is empty.";
                            echo json_encode($response);
                            return;
                        }
                    } elseif ($type == 'event') {
                        if (!empty($sheet)) {
                            foreach ($sheet as $key => $row) {
                                if ($key == 1) {
                                    continue;
                                }
                                $eventData = [
                                    'event_details' => $row['A'], // Event Name
                                    'event_type'    => $row['B'], // Event Type
                                    'firm_id'       => $row['C'], // Applied Location
                                    'start_date'    => date('Y-m-d', strtotime($row['D'])),
                                    'end_date'      => date('Y-m-d', strtotime($row['E'])),
                                    'event_hour'    => $row['F'], // Event During Hours
                                    'event_note'    => $row['G'], // Message
                                    'user_id'       => $user['user_id'],
                                    'create_by'     => $user['user_id'],
                                    'created_on'    => date('Y-m-d H:i:s'),
                                ];
                                $this->db->insert('event_master', $eventData);
                            }
                            echo json_encode([
                                'status' => 200,
                                'message' => 'Event data uploaded successfully.'
                            ]);
                            return;
                        } else {
                            echo json_encode([
                                'status' => 400,
                                'message' => 'The uploaded file is empty.'
                            ]);
                            return;
                        }
                    }
                } else {
                    $response['status'] = 400;
                    $response['message'] = "Invalid file or no file uploaded.";
                    echo json_encode($response);
                    return;
                }
            } catch (Exception $e) {
                $response['status'] = 500;
                $response['message'] = "Error: " . $e->getMessage();
                echo json_encode($response);
                return;
            } 
        }

        function parseExcelDate($value) {
            if (is_numeric($value)) { // Case 1: Excel serial number
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value));
            }

            $d = DateTime::createFromFormat('Y-m-d', $value); // Case 2: YYYY-MM-DD
            if ($d !== false) {
                return $d->format('Y-m-d');
            }

            $d = DateTime::createFromFormat('d-m-Y', $value); // Case 3: DD-MM-YYYY
            if ($d !== false) {
                return $d->format('Y-m-d');
            }

            return null; // invalid date
        }


        public function editHolidayEventData() {
            try {
                $id = '';
                $user = $this->session->userdata('login_session');
                if (empty($user)) {
                    echo json_encode([
                        'status' => 401,
                        'message' => 'Unauthorized'
                    ]);
                    return;
                }
                $data = $this->input->post('payload');
                if (empty($data)) {
                    throw new Exception('Invalid payload data');
                }
                $section = $data['section'];
                if(empty($section)) {
                    throw new Exception('Invalid section');
                }
                if(isset($data['id']) && !empty($data['id'])) {
                    $id = $data['id'];
                }  
                $query = '';
                if ($section == 'singleHolidayUpdate') {
                    $holidayData = $this->db->get_where('holiday_master_all', ['id' => $id])->row();
                    $updateCreateHolidayData = [
                        'bulk_upload'         => 0,
                        'category'            => 1,
                        'firm_id'             => $data['firm_id'] ?? $holidayData->firm_id,
                        'updated_by'          => $user['emp_id'] ?? $holidayData->updated_by,
                        'date'                => $data['edit_holiday_date'] ?? $holidayData->date,
                        'holiday_day'         => $data['edit_holiday_day'] ?? $holidayData->holiday_day,
                        'holiday_name'        => $data['edit_holiday_name'] ?? $holidayData->holiday_name,
                        'holiday_end_date'    => $data['edit_holiday_date'] ?? $holidayData->holiday_end_date,
                        'holiday_start_date'  => $data['edit_holiday_date'] ?? $holidayData->holiday_start_date,
                        'description'         => $data['edit_holiday_description'] ?? $holidayData->description,
                        'is_active'           => $data['edit_holiday_active_location'] ?? $holidayData->is_active,
                        'holiday_color_code'  => $data['edit_holiday_color_code'] ?? $holidayData->holiday_color_code,
                    ];
                    $this->db->where('id', $id);
                    $query = $this->db->update('holiday_master_all', $updateCreateHolidayData);

                } elseif ($section == 'singleHolidayCreate') {
                    $createHolidayData = [
                        'bulk_upload'         => 0,
                        'category'            => 1,
                        'firm_id'             => $data['firm_id'],
                        'updated_by'          => $user['emp_id'],
                        'holiday_day'         => $data['holiday_day'],
                        'date'                => $data['holiday_date'],
                        'holiday_name'        => $data['holiday_name'],
                        'holiday_end_date'    => $data['holiday_date'],
                        'holiday_start_date'  => $data['holiday_date'],
                        'description'         => $data['holiday_description'],
                        'is_active'           => $data['holiday_active_location'],
                        'holiday_color_code'  => $data['holiday_color_code'],
                    ];
                    $query = $this->db->insert('holiday_master_all', $createHolidayData);
                    
                } elseif ($section == 'signleEventCreate') {
                    $createEventData = [
                        'event_details' => $data['event_details'],
                        'event_type'    => $data['event_type'],
                        'start_date'    => date('Y-m-d', strtotime($data['start_date'])),
                        'end_date'      => date('Y-m-d', strtotime($data['end_date'])),
                        'event_hour'    => $data['event_hour'],
                        'event_note'    => $data['event_note'],
                        'firm_id'       => $data['firm_id'],
                        'user_id'       => $user['emp_id'],
                        'create_by'     => $user['emp_id'],
                        'created_on'    => date('Y-m-d H:i:s'),
                        'bulk_event'    => 0
                    ];
                    $query = $this->db->insert('event_master', $createEventData);
                    
                } elseif ($section == 'signleEventUpdate') {
                    $eventData = $this->db->get_where('event_master', ['id' => $id])->row();
                    $updateEventData = [
                        'event_details' => $data['edit_event_details'] ?? $eventData->event_details,
                        'event_type'    => $data['event_type'] ?? $eventData->event_type,
                        'start_date'    => date('Y-m-d', strtotime($data['edit_start_date'])) ?? $eventData->start_date,
                        'end_date'      => date('Y-m-d', strtotime($data['edit_end_date'])) ?? $eventData->end_date,
                        'event_hour'    => $data['edit_event_hour'] ?? $eventData->event_hour,
                        'event_note'    => $data['edit_event_note'] ?? $eventData->event_note,
                        'firm_id'       => $data['edit_firm_id'] ?? $eventData->firm_id,
                        'bulk_event'    => 0
                    ];
                    $this->db->where('id', $id);
                    $query = $this->db->update('event_master', $updateEventData);

                } else {
                    throw new Exception("Invalid section specified.");
                }

                echo json_encode([
                    'status'  => $query ? 200 : 500,
                    'message' => ucfirst($section) . ' updated successfully'
                ]);
            } catch (Exception $e) {
                $response['status'] = 500;
                $response['message'] = "Error: " . $e->getMessage();
                echo json_encode($response);
                return;
            }
        }

        public function deleteRecord() {
            try {
                $id = $this->input->post('id');
                $type = $this->input->post('type');
                $user = $this->session->userdata('login_session');
                if(empty($user)) {
                    $response['status'] = 401;
                    $response['message'] = "Unauthorized: Please log in.";
                    echo json_encode($response);
                    return;
                }

                if($type == 'singleHolidayDelete') {
                    $query = $this->hr_model->deleteSingleHolidayData($id);

                } elseif($type == 'holidayBulkUpload') {
                    $query = $this->hr_model->deleteBulkHolidayData();

                } elseif($type == 'singleEventDelete') {
                    $query = $this->hr_model->deleteSingleEventData($id);
                    
                } elseif($type == 'eventBulkUpload') {
                    $query = $this->hr_model->deleteBulkEventData();

                }

                if($query) {
                    $response['status'] = 200;
                    $response['message'] = "Record deleted successfully.";
                } else {
                    $response['status'] = 500;
                    $response['message'] = "Failed to delete data.";
                }
                echo json_encode($response);

            } catch (Exception $e) {
                $response['status'] = 500;
                $response['message'] = "Error: " . $e->getMessage();
                echo json_encode($response);
                return;
            } 
        }

        public function setDropdown($sheet, $cell, $data) {
            $v = $sheet->getCell($cell)->getDataValidation();
            $v->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $v->setAllowBlank(true);
            $v->setShowDropDown(true);
            $v->setFormula1('"' . implode(',', $data) . '"');
        }

        public function setDataValidation($sheet, $cell) {
            $v = $sheet->getCell($cell)->getDataValidation();
            $v->setType(PHPExcel_Cell_DataValidation::TYPE_DATE);
            $v->setAllowBlank(true);
        }
    // End View Holiday and Special Days Page
}
