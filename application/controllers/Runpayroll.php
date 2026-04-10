<?php

class Runpayroll extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('htmltopdf_model');
//        $this->load->library('Pdf');
    }

    public function index()
    {
        $data['prev_title'] = "Run Payroll";
        $data['page_title'] = "Run Payroll";
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        //$data['customer_details'] = $this->htmltopdf_model->fetch_single_details($user_id);
        $this->load->view('human_resource/View_run_payroll', $data);
    }

    public function downlodpdf($user_id = '', $year = '', $month = '')
    {
        $data['prev_title'] = "Download PDf";
        $data['page_title'] = "Download PDf";
        $data['customer_details'] = $this->htmltopdf_model->fetch_single_details($user_id, $year, $month);
        $this->load->view('human_resource/View_Download_pdf', $data);
    }

//    public function details() {
//        $session_data = $this->session->userdata('login_session');
//        $user_id = ($session_data['emp_id']);
//        $data['customer_details'] = $this->htmltopdf_model->fetch_single_details($user_id);
//        $this->load->view('human_resource/htmltopdf', $data);
//    }

    public function pdfdetails()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        //Get firm name from partner header all
        $query_firm_name = $this->db->query("select firm_name from partner_header_all where firm_id='$firm_id'")->row();
        $firm_name = $query_firm_name->firm_name;


        $html_content = '<h3 align="center">' . $firm_name . '</h3>';
        $html_content .= $this->htmltopdf_model->fetch_single_details($user_id);
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("" . $user_id . ".pdf", array("Attachment" => 0));
    }

    public function get_user_attedace_data($user_id)
    {
        $qry = $this->db->query("select applicable_status,work_schedule_status,fixed_hour,work_hour_status,fixed_time,late_minute_count from user_header_all where user_id='$user_id'");
//      echo $this->db->last_query();die;
        if ($this->db->affected_rows() > 0) {
            $res = $qry->row();
           $applicable_status = $res->applicable_status;
            $work_schedule_status = $res->work_schedule_status;

            $late_minute_count = $res->late_minute_count;
            if ($applicable_status == 1) { //if attendance is applicable for employee
                if ($work_schedule_status == 1) { //if there is fixed hour on all day
                    $fixed_hour = $res->fixed_hour;
                    $work_hour_status = $res->work_hour_status;
                    if ($work_hour_status == 1) {
                        $fix_intime = $res->fixed_time;
                    } else {
                        $fix_intime = '';
                    }
                    $stats = 1; //applicable but fix hour
                    return array($stats, $fixed_hour, $fix_intime, $late_minute_count);
                } else if ($work_schedule_status == 2) { // if there are variable hour on all day
                    $qry1 = $this->db->query("SELECT * FROM attendance_employee_applicable where user_id='$user_id'");
                    if ($this->db->affected_rows() > 0) {
                        $result = $qry1->result();
                        $data_attendace_day = array();
                        $data_attendace_type = array();
                        $data_attendace_fixed_hour = array();
                        foreach ($result as $row) {
                            $data_attendace_day[] = $row->day;
                            $data_attendace_type[] = $row->type;
                            $in_time_applicable_sts[] = $row->in_time_applicable_sts;
                            $in_fixed_time[] = $row->in_fixed_time;
                            $data_attendace_fixed_hour[] = $row->fixed_hour;
                        }
                    }
                    $stats = 2; //applicable but variable hour
                    return array($stats, $data_attendace_day, $data_attendace_fixed_hour, $in_time_applicable_sts, $in_fixed_time, $late_minute_count);
                } else {
                    return FALSE; //if there no value in user_header_all table column= work_schedule_status
                }
            } else { // not applicable
                $fixed_hour = "08:30:00";
                $stats = 3; //Not applicable
                return array($stats, $fixed_hour);
            }
        } else {
            return FALSE;
        }
    }

    public function check_data_existornot($user_id, $month, $year)
    {
        $query = $this->db->query("select `user_id` from t_attendance_staff where user_id='$user_id' AND  MONTH(`date`)='$month' AND YEAR(`date`)='$year'");
        if ($this->db->affected_rows() > 0) {
            $qr_delete = $this->db->query("delete from t_attendance_staff where user_id='$user_id' AND  MONTH(`date`)='$month' AND YEAR(`date`)='$year'");
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function insert_att_data()
    {
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $runStatus = $this->input->post('runStatus');
        $leave_accept = $this->input->post('leave_accept');
        $leave_accept_reason = $this->input->post('leave_accept_reason');
        if ($user_type == 5) {

            $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
            if ($this->db->affected_rows() > 0) {
                $res = $qr->row();
                $firm_id = $res->hr_authority;
            }
            $user_id = $this->input->post('employee_id');
            if ($runStatus == 0) {
                $total_leave_cnt = $this->getEmployeeLeaveCount($user_id, $month, $year);
                if ($total_leave_cnt >= 8) {
                    $response['message'] = 'same';
                    $response['body'] = 1;
                    echo json_encode($response);
                    exit();
                }
            }
        } else {
            $total_leave_cnt = $this->getEmployeeLeaveCount($user_id, $month, $year);
            if ($total_leave_cnt >= 8) {
                $response['message'] = 'other';
                $response['body'] = 'Contact to HR to run your payroll';
                echo json_encode($response);
                exit();
            }
        }
        if($leave_accept_reason!="") {
            $leaveAcceptData = $this->storeLeaveAcceptData($user_id, $month, $year, $leave_accept, $leave_accept_reason);
        }
        $get_data_of_user = $this->get_user_attedace_data($user_id);

        if ($get_data_of_user != FALSE) {
            $statndard_hours = $get_data_of_user;
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'Attendace not available.';
            //error msg
        }


        $month_array = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
        $mn_num = array_search($month, $month_array);
        $prev_months_arr = array_slice($month_array, 0, $mn_num);
        $next_months_arr = array_slice($month_array, $mn_num + 1);

        $Yearlyincome_tax = $this->input->post('income_tax');
        if ($Yearlyincome_tax == '' || $Yearlyincome_tax == null) {
            $Yearlyincome_tax = 0;
        } else {
            $Yearlyincome_tax = $this->input->post('income_tax');
        }

        $prevIT = 0;

        for ($m = 0; $m < count($prev_months_arr); $m++) {
            $query = $this->db->query("select std_amt from t_salary_staff where user_id='$user_id' AND month='$prev_months_arr[$m]'AND year='$year' AND salary_type='Income Tax'")->row();


            if ($this->db->affected_rows() > 0) {
                $prevIT += $query->std_amt;
            } else {
                $prevIT += 0;
            }
        }
        $rem_income_tax = $Yearlyincome_tax - $prevIT;
        if (count($next_months_arr) > 0) {
            $income_tax = $rem_income_tax / ((count($next_months_arr)) + 1);
        } else {
            $income_tax = $rem_income_tax;
        }
        $income_tax = 0;


        //$year = date("Y");

        $num_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $a = 1;
        $late_minute_count = '';
        //$fixed_intime='';
        $check_data_exist = $this->check_data_existornot($user_id, $month, $year);
        // print_r($check_data_exist);exit();
        if ($check_data_exist == TRUE) {
//        	print_r($num_days);die;
            for ($d = 1; $d <= $num_days; $d++) {
                $time = mktime(12, 0, 0, $month, $d, $year);
                if (date('m', $time) == $month) {
                    $list = date('Y-m-d', $time); //month date
                    $day = date('l', $time); //month day
                }
//                print_r($statndard_hours[0]);die;
                if ($statndard_hours[0] == 2) {
                    $day_arr = $statndard_hours[1];
                    $time_arr = $statndard_hours[2];
                    $intime_applicable = $statndard_hours[3];
                    $fix_intime = $statndard_hours[4];
                    $late_minute_count = $statndard_hours[5];
                    for ($k = 0; $k <= 6; $k++) {
                        if ($day == $day_arr[$k]) {
                            $std_hrs = $time_arr[$k]; // variable hour for days
                            $app_sts = $intime_applicable[$k];
                            if ($app_sts == 1) {
                                $fixed_intime = $fix_intime[$k];
                            } else {
                                $fixed_intime = '';
                            }
                        }
                    }
                } else if ($statndard_hours[0] == 1) {
                    $std_hrs = $statndard_hours[1]; //if fixed hour for all days
                    $fixed_intime = $statndard_hours[2]; //fixed intime
                    $late_minute_count = $statndard_hours[3]; //fixed intime
                } else {
                    $std_hrs = $statndard_hours[1]; //not applicable
                    $fixed_intime = "00:00:00"; //fixed intime
                }
                $leave_status = '';
                $punch_regularised_status = '';
                $regular_status = '';
                $activity_status = '';
                $punch_in = '';
                $punch_out = '';
                $outside_punch_applicable = '';
                $punchintime = '';
                $is_holiday = '';
                $holiday_approval_status = '';
                $sandwich_leave_applicable = "";
                $qry = $this->db->query("select *
from employee_attendance_leave eal where user_id='$user_id' and date='$list'");
				/*if($list == '2024-07-09'){
					echo $this->db->last_query();die;
				}*/

                if ($this->db->affected_rows() > 0) {
                    $res = $qry->row();
                    $punch_in = $res->punch_in;
                    $punch_out = $res->punch_out;
                    $leave_status = $res->leave_status;
//                    $leave_status = $res->leave_status_1;
                    $activity_status = $res->activity_status;
                    $regular_status = $res->regular_status;
                    $is_holiday = $res->is_holiday;
                    $holiday_approval_status = $res->holiday_approval_status;
                    $punch_regularised_status = $res->punch_regularised_status;
//					echo $punch_in;die;
                    $dt = new DateTime($punch_in);
                    $dt1 = new DateTime($punch_out);
                    $time = strtotime($dt->format('H:i:s'));
                    $time1 = strtotime($dt1->format('H:i:s'));
                    $diff = $time1 - $time;

                    $sec_t = abs($diff);
                    $punchintime = date("H:i:s", strtotime($punch_in)); // output 11:45:45
//                   echo $punch_out;die;
                    if ($punch_out == 0 && $leave_status == 0) { //if punchout is no done by employee
//                    	echo 11;die;
						$work_hrs = "00:00:00";

                    } else {

                        $work_hrs = gmdate("H:i:s", $sec_t);
                    }

//                    echo $work_hrs;die;
                    $no_enry = 0;
                    //get outsidde punch applicable


                } else { // if there is no entry for date in table

                    $no_enry = 1;
                    if ($statndard_hours[0] == 3) {
                        $no_enry = 3; //not applicable
                        $work_hrs = "08:30:00";
                    } else {
                        $work_hrs = "00:00:00";
                    }

                }
				$qry1 = $this->db->query("select outside_punch_applicable,sandwich_leave_applicable from user_header_all where user_id='$user_id'");
				if ($this->db->affected_rows() > 0) {
					$result = $qry1->row();
					$outside_punch_applicable = $result->outside_punch_applicable;
					$sandwich_leave_applicable = $result->sandwich_leave_applicable;
				}
			                /*  echo $list;
                  echo "*";
                  echo $no_enry;
                  echo "<br>";  */
                //   echo $punchintime;

                $diff = strtotime($punchintime) - strtotime($fixed_intime);
                $different_between_time = abs($diff); //fixed and  punintime diff
                $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $late_minute_count);
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                //	echo "--";
                $time_seconds = $hours * 3600 + $minutes * 60 + $seconds; //late time
//echo "<br>";
                $today_date = date('Y-m-d');
                //get status
                $check_holiday = $this->check_holiday($day, $user_id, $firm_id, $list);
				/*if($list == "2024-09-07"){
					echo $check_holiday;die;
				}*/
                $final_wrk_hrs = $work_hrs;
                if (strtotime($list) > strtotime($today_date)) {
                    $status = 7; //date is greater so its is absent
                } else if ($check_holiday != FALSE) {
                    $final_wrk_hrs = $std_hrs;
                    if ($is_holiday == 1 && $holiday_approval_status == 1) {
                        $final_wrk_hrs_add = strtotime($std_hrs) + strtotime($work_hrs);
                        $sec_t1 = abs($final_wrk_hrs_add);
                        $final_wrk_hrs = gmdate("H:i:s", $sec_t1);
                        $status = 6;
                    } elseif ($check_holiday == 2) {
                        // Alternate Saturday check
                        $qrr = $this->db->query("select alternate_id from holiday_master_all where date='$list' and firm_id='$firm_id' and is_alternate = 1");
                        if ($this->db->affected_rows() > 0 && $leave_status != 2) {
                            $res = $qrr->row();
                            $alternate_id = $res->alternate_id;
                            $checkUserAlt = $this->db->query('SELECT * FROM alternate_holiday_master where alternate_id = "' . $alternate_id . '" and user_id = "' . $user_id . '"')->result();
                            if (count($checkUserAlt) > 0) {
                                // If this user is configured for alternate saturdays
                                $status = 2;
								$final_wrk_hrs = "08:30:00";
                            } else {
                                $status = 2; // Holiday
                            }
                        } else if ($leave_status == 2) {
                            $status = 1; //Leave
//							$work_hrs = "08:30:00";
							/*$userData=$this->db->query("select user_id,id,total_leave_available from user_header_all where user_id='".$user_id."'");
							if($this->db->affected_rows()>0)
							{
								$userData=$userData->row();
								if($userData->total_leave_available>=1)
								{
									$total_leave_available=$userData->total_leave_available-1;
									$this->db->query("UPDATE user_header_all SET total_leave_available='".$total_leave_available."' where id='".$userData->id."'");
								}
							}*/
                        } else {
                            $status = 2; //holiday
                            $work_hrs = "08:30:00";

                        }


                       /* if ($sandwich_leave_applicable == 1) {

                            $check_sandwich = $this->check_sandwich($day, $user_id, $firm_id, $list, $num_days);
                            if ($check_sandwich == true) {
                                $status = 8; //SANDWICH LEAVE
                            }

                        }*/

                    }


                    if ($final_wrk_hrs == "00:00:00") {
                        $final_wrk_hrs = "08:30:00";
                        $std_hrs = "08:30:00";//00:00:00
                    }
                } else if ($no_enry == 1 && $check_holiday == FALSE && $no_enry != 3) { //if there is no entry in table for date
                    $status = 3; //LOP
                } else if ($punch_in == 0 && $punch_out == 0 && $leave_status == 2) {
                    $status = 1; //LEAVE
                    $final_wrk_hrs = "08:30:00";

//					$work_hrs = "08:30:00";
                } else if ($punch_in == 0 && $punch_out == 0 && $leave_status == 1) {
                    //if employee requested leave and which not accepted as well as there is no punch in punch out of employee the its LOP
                    $status = 3;
                } else if ($punch_in != 0 && $punch_out == 0) {
                    //if employee has mark his punchin and forget to mark punchout then its LOP
                    $status = 3;
                } else if ($punch_in != 0 && $punch_out != 0 && $punch_regularised_status == 1 && $outside_punch_applicable != 1 && $regular_status != 1) {
                    //employee having outside punch/punchout and it is not accepted by HR then its LOP
                    $status = 3;
                } else if ($fixed_intime != 0 && $different_between_time > $time_seconds && strtotime($punchintime) > strtotime($fixed_intime)) { //if employee come late
                    $status = 5; //LATE
                } else {
                    $status = 0; //employee is present
                }


                $data = array("firm_id" => $firm_id, "user_id" => $user_id, "final_wrk_hrs" => $final_wrk_hrs, "date" => $list, "w_hours" => $work_hrs
                , "std_hours" => $std_hrs, "status" => $status);


                $insert_qr = $this->db->insert("t_attendance_staff", $data);
                if ($insert_qr == TRUE) {
                    $a++;
                }
                /* echo $fixed_intime;
                  echo "==";
                  echo $punchintime;
                  echo "==";
                  echo $list;
                  echo "*";
                  echo $status;
                  echo "==";
                  echo $std_hrs;
                  echo"==";
                  echo $work_hrs;
                  echo "<br>"; */
            }

			if ($sandwich_leave_applicable == 1) {

				$check_sandwich = $this->check_sandwichNew($user_id, $month, $year);


			}
//			echo json_encode($data);die;
            if ($a > 1) {
                $a = $this->insertdataSalary($user_id, $month, $year, $income_tax);
                $response['message'] = 'success';
                $response['body'] = 'Data uploaded successfully.';
            } else {
                $response['message'] = 'fail';
                $response['body'] = 'Something went wrong';
            }
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'Something went wrong';
        }
        echo json_encode($response);
    }

    function  check_sandwichNew($user_id, $month, $year){
			$getAllholidays=$this->db->query("select date,id from t_attendance_staff where user_id='$user_id' 
and month(date)='$month' and year(date)='$year' and status=2")->result();
//			print_r($getAllholidays);die;
    	foreach ($getAllholidays as $rr){
			$id=$rr->id;
			$id_previous=$id-1;
            $s_prev=0;
			$getAllholidays=$this->db->query("select status from t_attendance_staff where id='$id_previous'")->row();
            if($this->db->affected_rows() > 0){
                $s_prev=$getAllholidays->status;
            }
			$id_next=$id+1;
            $s_next=0;
            $getAllholidays1=$this->db->query("select status from t_attendance_staff where id='$id_next'")->row();
            if($this->db->affected_rows() > 0){
                $s_next=$getAllholidays1->status;
            }
			if(($s_prev == 8 || $s_prev == 1 || $s_prev== 3) && ($s_next == 8 || $s_next == 1 || $s_next== 3)){
				$where=array('id'=>$id);
				$this->db->where($where);
				$this->db->update('t_attendance_staff',array('status'=>8));
			}
		}
	}

    public function check_holiday($day, $user_id, $firm_id, $date)
    {
        $k = 0;
        $p = 0;
        $qry1 = $this->db->query("select date from holiday_master_all where firm_id='$firm_id' AND date='$date' ");
        if ($this->db->affected_rows() > 0) {

            $k++;
        }
        $qry = $this->db->query("select type from attendance_employee_applicable where user_id='$user_id' AND day='$day' AND type='2'");
        if ($this->db->affected_rows() > 0) {
            $res = $qry->row();
            $p++;
        }
        if ($k > 0 || $p > 0) {
            return 2;
        } else {
            return FALSE;
        }
    }

    public function check_sandwich($day, $user_id, $firm_id, $date, $num_days)
    {
    	//echo $date;
		$t_date=$date;
//		echo 11;die;
        //check previous day
        $next = $date;
		/*if($next == '2024-07-21'){
			echo $date;die;
		}*/
        $nextD = $day;
        $d = date('d', strtotime($date));
        $previos = 0;
        for ($i = $d; $i >= 1; $i--) {

            $is_holiday = $this->check_holiday($day, $user_id, $firm_id, $date);

            if ($is_holiday != 2) {
                //get previos date status...
                $query = $this->db->query("SELECT status FROM t_attendance_staff where user_id='$user_id' AND date='$date'");
				/*if($next == '2024-07-21'){
					echo $this->db->last_query();die;
				}*/
                if ($this->db->affected_rows() > 0) {
                    $res = $query->row();
                    $status = $res->status;
					/*if($next == '2024-07-21'){
						echo $status;die;
					}*/
                    if ($status == 1 || $status == 3 || $status == 8) {
                        $previos = 1;
                    }
                }
                break;

            }

            $date = date('Y-m-d', strtotime($date . ' -1 day'));
            $day = date('l', strtotime($date));

        }
//die;


        //check next day
        $d1 = date('d', strtotime($next));
        $leave_status = '';
        $punch_regularised_status = '';
        $regular_status = '';
        $activity_status = '';
        $punch_in = '';
        $punch_out = '';
        $outside_punch_applicable = '';
        $nextsts = 0;
        for ($j = $d1; $j <= $num_days; $j++) {
            $is_holiday = $this->check_holiday($nextD, $user_id, $firm_id, $next);

            if ($is_holiday != 2) {
                $qry = $this->db->query("select * from employee_attendance_leave where user_id='$user_id' and date='$next'");
                $this->db->last_query();

                if ($this->db->affected_rows() > 0) {

                    $res = $qry->row();
                    $punch_in = $res->punch_in;
                    $punch_out = $res->punch_out;
                    $leave_status = $res->leave_status;
                    $activity_status = $res->activity_status;
                    $regular_status = $res->regular_status;
                    $punch_regularised_status = $res->punch_regularised_status;
                }

                //get outsidde punch applicable
                $qry1 = $this->db->query("select outside_punch_applicable from user_header_all where user_id='$user_id'");
                if ($this->db->affected_rows() > 0) {
                    $result = $qry1->row();
                    $outside_punch_applicable = $result->outside_punch_applicable;
                }

                if ($punch_in == '' && $punch_out == '') {
                    $nextsts = 1;
                } else if ($punch_in == 0 && $punch_out == 0 && $leave_status == 2) {
                    $nextsts = 1; //LEAVE
                } else if ($punch_in == 0 && $punch_out == 0 && $leave_status == 1) {
                    //if employee requested leave and which not accepted as well as there is no punch in punch out of employee the its LOP
                    $nextsts = 1;
                } else if ($punch_in != 0 && $punch_out == 0) {
                    //if employee has mark his punchin and forget to mark punchout then its LOP
                    $nextsts = 1;
                } else if ($punch_in != 0 && $punch_out != 0 && $punch_regularised_status == 1 && $outside_punch_applicable != 1 && $regular_status != 1) {
                    //employee having outside punch/punchout and it is not accepted by HR then its LOP
                    $nextsts = 1;
                } else {
                    $nextsts = 0;
                }

                break;
            }
            $next = date('Y-m-d', strtotime($next . ' +1 day'));
            $nextD = date('l', strtotime($next));
        }
        /* echo $previos;
echo "=";
       echo $nextsts;
       echo "<br>";  */
        if ($previos == 1 && $nextsts == 1) {
            return true;
        } else {
            return false;
        }


    }

    public function getDataMonthly()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        if ($user_type == 5) {
            //emp_id
            $user_id = $this->input->post('emp_id');
        }
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $qr = $this->db->query("select *,
(select punch_in from employee_attendance_leave eal where eal.date=tas.date and eal.user_id='$user_id') as punch_in,
(select punch_out from employee_attendance_leave eal where eal.date=tas.date and eal.user_id='$user_id') as punch_out
 from t_attendance_staff tas where user_id='$user_id' AND MONTH(date)='$month' AND Year(date)=$year");
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $result = $qr->result();
            foreach ($result as $row) {
                if ($row->status == 1) {
                    $sts = 'Leave';
                } else if ($row->status == 2) {
                    $sts = 'Holiday';
                } else if ($row->status == 3) {
                    $sts = 'Loss Of Pay';
                } else if ($row->status == 4) {
                    $sts = 'Work From Home';
                } else if ($row->status == 5) {
                    $sts = 'Present(Late)';
                } else if ($row->status == 6) {
                    $sts = 'Holiday Overtime';
                } else if ($row->status == 7) {
                    $sts = 'Absent';
                } else if ($row->status == 8) {
                    $sts = 'Sandwich';
                } else {
                    $sts = 'Present';
                }
                $data .= '<tr>
				<td>' . $row->date . '</td>
				<td>' . date("H:i:s a",strtotime($row->punch_in)) . '</td>
				<td>' . date("H:i:s a",strtotime($row->punch_out)) . '</td>
				<td>' . $row->std_hours . '</td>
				<td>' . $row->w_hours . '</td>
				<td>' . $sts . '</td>
				</tr>';
            }
            $response['data'] = $data;
            $response['message'] = 'success';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function checkSalData($user_id, $month)
    {
        $qry = $this->db->query("select user_id from t_salary_staff where user_id='$user_id' AND month='$month'");
        if ($this->db->affected_rows() > 0) {
            $where = array('user_id' => $user_id, 'month' => $month);
            $qry1 = $this->db->delete('t_salary_staff', $where);
            if ($qry1 == TRUE) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function insertdataSalary($user_id, $month, $year, $income_tax)
    {
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }

        $qr = $this->customer_model->get_procedure_data($user_id, $month, $year);
        $data = array(
            'firm_id' => $firm_id,
            'user_id' => $user_id,
            'std_amt' => $income_tax,
            'amt' => $income_tax,
            'salary_type' => "Income Tax",
            'category' => 5,
            'month' => $month,
            'year' => $year,
        );
        $insert_income_tax = $this->db->insert('t_salary_staff', $data);
        if ($qr !== FALSE) {

            return true;
        } else {
            return FALSE;
        }
        /* } else {
          return FALSE;
          } */
    }

	public function getDataSalaryMonthly() {
		$session_data = $this->session->userdata('login_session');
		$user_id = ($session_data['emp_id']);
		$user_type = ($session_data['user_type']);
		if($user_type==5){
			$user_id = $this->input->post('emp_id');
		}
		$month = $this->input->post('month');
		$year = $this->input->post('year');

		// $queryata=$this->db->query('select * from t_attendance_staff where user_id="'.$user_id.'" and MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'"');
		// if($this->db->affected_rows()>0)
		// {
		//     print_r($queryata->result());exit();
		// }
		// exit();
		$total_leave_cnt=0;

		$employeeDetails=$this->db->query("select probation_period_end_date from  user_header_all where user_id='".$user_id."'");
		$empData=$employeeDetails->row();
		$probEndDate=date('Y-m-d',strtotime($empData->probation_period_end_date));
		$leaveAccStatus=0;
		$leaveAcceptData=$this->db->query("select * from t_leave_acceptance where user_id='".$user_id."' and month=".$month." and year=".$year." order by id desc");
		if($this->db->affected_rows()>0)
		{
			$leAccData=$leaveAcceptData->row();
			if($leAccData->status=="No")
			{
				$leaveAccStatus=1;
			}
		}
        // Convert probation end date to timestamp
        $probEndTimestamp = strtotime($probEndDate);

// Create a timestamp for the given month and year (last day of the given month)
        $givenDateTimestamp = strtotime("last day of $year-$month");
        if ($probEndTimestamp <= $givenDateTimestamp) {

//            echo "Probation period has ended.";
        } else {
//            echo "Probation period is still ongoing.";
            $basicSalary=0;
            $qr = $this->db->query("select * from t_salary_staff where user_id='$user_id' AND month='$month' AND category !=0 AND year=$year");
            $data = '';
            if ($this->db->affected_rows() > 0) {

                $result = $qr->result();
                $basicSalary=$result[0]->std_amt;
            }

            $resultObject=$this->db->query("select count(id) as leave_cnt from employee_attendance_leave where leave_status=2 and activity_status=0 and user_id='".$user_id."' and MONTH(date)=".$month." and YEAR(date)=".$year);
            if($this->db->affected_rows()>0)
            {
                $resultData=$resultObject->row();
                $total_leave_cnt=$resultData->leave_cnt;
            }
            // print_r($total_leave_cnt);exit();
            $monthDays=cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $perDaySalary=$basicSalary/$monthDays;
            $total_leave_amt=$perDaySalary*$total_leave_cnt;
            // print_r($total_leave_amt);exit();
          //  $resultInsert=$this->db->update('t_salary_staff',array('amt'=>$total_leave_amt),array('user_id'=>$user_id,'salary_type'=>'Leave Deduction','month'=>$month,'year'=>$year));
        }
		/*if((date('m', strtotime($probEndDate)) >= $month && date('Y', strtotime($probEndDate))>=$year) || $leaveAccStatus==1)
		{

		}*/
		// exit();
		$qr = $this->db->query("select * from t_salary_staff where user_id='$user_id' AND month='$month' AND category !=0 AND year=$year");
		$leave_deduction = $this->db->query("select amt from t_salary_staff where category=4 and user_id='$user_id' AND month='$month' AND category !=0 AND year=$year")->row()->amt;
		$sandwich_deduction = $this->db->query("select amt from t_salary_staff where category=8 and user_id='$user_id' AND month='$month' AND category !=0 AND year=$year")->row()->amt;
		//echo $this->db->last_query();die;
		$data = '';
		$lop=0;
		$cat_1_stand_amt=0;
		if ($this->db->affected_rows() > 0) {

			$result = $qr->result();

			foreach ($result as $row) {
				$category = $row->category;
				$typename = $row->salary_type;
				$std_amt = $row->std_amt;
				$amt = $row->amt;
				$sign='';
				if ($category == 1) {
					$sts = 'Salary';
					$sign='<i class="fa fa-plus" style="font-size:7px;color:green"></i> ';
				} else if ($category == 2) {
					$sts = 'Deduction';
					$sign='<i class="fa fa-minus" style="font-size:7px;color:red"></i> ';
				} else if ($category == 3) {
					$sts = 'Other Salary';
					$sign='<i class="fa fa-plus" style="font-size:7px;color:green"></i> ';
				} else if ($category == 4) {
					$sts = 'Other Deduction';
					$sign='<i class="fa fa-minus" style="font-size:7px;color:red"></i> ';
					 if(($probEndTimestamp >= $givenDateTimestamp) && $typename=="Leave Deduction")
					 {
                         $amt=0;
					 }
				} else if ($category == 5) {
					$sts = 'Income Tax';
					$sign='<i class="fa fa-minus" style="font-size:7px;color:red"></i> ';
				} else if ($category == 7) {
					$sts = 'Extra';
					$sign='<i class="fa fa-plus" style="font-size:7px;color:green"></i> ';
				} else if ($category == 8) {
					$sts = 'Sandwich Leave';
					$sign='<i class="fa fa-minus" style="font-size:7px;color:red"></i> ';
				}else if ($category == 9) {
					$sts = 'LOP Deduction';
					$sign='<i class="fa fa-minus" style="font-size:7px;color:red"></i> ';
				} else {
					$sts = 'None';
				}
				if($category == 1){
					 //$lop=$std_amt -	($amt + $leave_deduction+$sandwich_deduction);
                    $probEndTimestamp = strtotime($probEndDate);
                    $givenDateTimestamp = strtotime("last day of $year-$month");
                    if ($probEndTimestamp <= $givenDateTimestamp) {
//                        echo "Probation period has ended.";
                       // $lop=$std_amt -	($amt +$sandwich_deduction);
                        $lop=($sandwich_deduction);

                    }else {
//                        echo "Probation period is still ongoing.";
                        //amount lop
                        $l=$std_amt - $amt;
                        // echo  $l;die;
                        // echo $total_leave_amt;die;
                        // $lop = ($lop + $total_leave_amt);
                        // echo $lop;die;
                        $lop=$lop;

                    }
                   // $amt=$std_amt-$leave_deduction;
                    // $amt 
					$signlp='<i class="fa fa-minus" style="font-size:7px;color:red"></i> ';
				}
				if ($category == 9) {
					$lop += $row->amt;
					$amt=$lop;
					$sign='<i class="fa fa-minus" style="font-size:7px;color:red"></i> ';
				}
				if($amt==0){$sign="";}else{$sign;}
//				if($category == 1){ $amt=$signlp.round($lop);}else{$amt= 0;}
				//if($lop==0){$signlp="";}else{$signlp='<i class="fa fa-minus" style="font-size:7px;color:red"></i> ';}
				$data .= '<tr>
				<td>' . $typename . '</td>
				<td>' . round($std_amt) . '</td>
				<td>' .$sign . round($amt) . '</td>
			
				<td>' . $sts . '</td>
				</tr>';
			}
			$response['data'] = $data;
			$response['message'] = 'success';
		} else {
			$response['message'] = 'fail';
		}
		echo json_encode($response);
	}

    public function get_info_sal_rqst()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $qr = $this->db->query("select status from t_release_salary where user_id='$user_id' AND month='$month' AND year='$year'")->row();
        $status = 0;
        if ($this->db->affected_rows() > 0) {
            $status = $qr->status;

            $response['message'] = 'success';
            $response['status'] = $status;
        } else {
            $response['status'] = $status;
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function insert_sal_release_data()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        $status = 0;
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        if ($user_type == 5) {
            $user_id = $this->input->post('emp_id');
            $status = 1;
            $loginuser_id = ($session_data['emp_id']);
            $get_hr = $this->db->query("select hr_authority from user_header_all where user_id='$loginuser_id'");
            if ($this->db->affected_rows() > 0) {
                $res = $get_hr->row();
                $firm_id = $res->hr_authority;
            } else {
                $firm_id = '';
            }
        }
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $created_on = date('Y-m-d');
        $this->db->where('user_id', $user_id);
        $this->db->where('firm_id', $firm_id);
        $this->db->where('year', $year);
        $this->db->where('month', $month);
        $this->db->delete('t_release_salary');
        $data = array(
            'user_id' => $user_id,
            'month' => $month,
            'firm_id' => $firm_id,
            'year' => $year,
            'status' => $status,
            'created_on' => $created_on,
        );
        $query_insert = $this->db->insert('t_release_salary', $data);
        if ($query_insert == TRUE) {
            if($status==1)
            {
                $this->deductEmployeeLeave($user_id,$month,$year);
            }
            $check_exit_employee=$this->db->query("select exit_date from exit_emp_summary where user_id='".$user_id."'")->row();
			if($this->db->affected_rows() > 0){
				$where=array('user_id'=>$user_id);
				$this->db->where($where);
				$this->db->update('user_header_all',array('activity_status'=>0));
			}
            $response['message'] = 'success';
            $response['body'] = 'Salary Release Requested Successfully.';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function getDataSalaryRelease()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $curr_mon = date('n');
        /*$where = $curr_mon . ",";
        for ($i = 1; $i < 6; $i++) {
            $last_six_mon = date('n', strtotime("-$i month"));
            $where .= $last_six_mon . ",";
        }*/
//        $where1 = rtrim($where, ",");
        $qr = $this->db->query("select * from t_release_salary where  user_id='$user_id' order by id desc")->result();
        // echo $this->db->last_query();
        $data = '';
        if (count($qr) > 0) {
            foreach ($qr as $row) {
                $status = $row->status;
                $month = $row->month;
                $year = $row->year;
                $date = $row->created_on;
                $pointer = 'style="pointer-events: none"';
                $sal_slip = '<button title="Not Available!" class="btn btn-link " disabled><i class="fa fa-file-pdf-o" style="color:red" aria-hidden="true"></i></button>';
                if ($status == 0) {
                    $sts = '<span class="badge badge-primary">Requested</span>';

                } else if ($status == 1) {
                    $sts = '<span class="badge badge-success">Released</span>';
                    $sal_slip = '<button class="btn btn-link"><i class="fa fa-file-pdf-o" style="color:red" aria-hidden="true"></i></button>';
                    $pointer = '';
                } else {
                    $sts = '<span class="badge badge-danger">Hold</span>';
                }
                $dateObj = DateTime::createFromFormat('!m', $month);
                $monthName = $dateObj->format('F'); // March

                $data .= '<tr>
				<td>' . $monthName . '</td>
				<td>' . date("d-m-Y", strtotime($date)) . '</td>
				<td>' . $sts . '</td>';
                $data .= '<td><a href="' . base_url() . 'htmltopdf/' . $row->user_id . '/' . $year . '/' . $month . '" target="_blank">' . $sal_slip . '</a></td>				</tr>';
//                $data .= '<td><a href="' . base_url() . 'salary_report/' . $row->user_id . '/' . $year . '/' . $month . '" target="_blank">' . $sal_slip . '</a></td></tr>';

            }
            $response['data'] = $data;
            $response['message'] = 'success';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function getDataSalaryReleaseHr()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        $qr = $this->db->query("select * from t_release_salary where firm_id='$firm_id' and status='0' order by id desc")->result();
        $data = '';
        if (count($qr) > 0) {
            foreach ($qr as $row) {
                $status = $row->status;
                $user_id_emp = $row->user_id;
                $month = $row->month;
                $date = $row->created_on;

                $query_user_name = $this->db->query("select user_name from user_header_all where user_id='$user_id_emp'")->row();
                if ($this->db->affected_rows() > 0) {
                    $user_name = $query_user_name->user_name;
                } else {
                    $user_name = '';
                }
                $details = '<button class="btn btn-link btn-icon-only btn-default"data-toggle="modal" data-target="#viewdetail" onclick="get_usersaldata(\'' . $row->month . '\',\'' . $row->year . '\',\'' . $row->user_id . '\')"><i class="fa fa-eye" style="color:blue" aria-hidden="true"></i></button>';
                if ($status == 0) {
                    $sts = '<span class="badge badge-primary">Requested</span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Release" onclick="accept_rel_reqst(\'' . $row->id . '\',' . "1" . ')" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green"></i></button>'
                        . '<button type="button" data-toggle="tooltip" title="Hold" onclick="accept_rel_reqst(\'' . $row->id . '\',' . "2" . ')" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red"></i></button>';
                } else if ($status == 1) {
                    $sts = '<span class="badge badge-success">Released</span>';

                    $action = '<button type="button" data-toggle="tooltip" title="Release" onclick="" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green"></i></button>'
                        . '<button type="button" data-toggle="tooltip" title="Hold" onclick="accept_rel_reqst(\'' . $row->id . '\',' . "2" . ')" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red"></i></button>';
                } else {
                    $action = '<button type="button" data-toggle="tooltip" title="Release" onclick="accept_rel_reqst(\'' . $row->id . '\',' . "1" . ')"  class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green"></i></button>'
                        . '<button type="button" data-toggle="tooltip" title="Hold" onclick=""disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red"></i></button>';
                    $sts = '<span class="badge badge-danger">Hold</span>';
                }
                $dateObj = DateTime::createFromFormat('!m', $month);
                $monthName = $dateObj->format('F'); // March

                $data .= '<tr>
				<td>' . $user_name . '</td>
				<td>' . $monthName . '</td>
				<td>' . date("d-m-Y", strtotime($date)) . '</td>
				<td>' . $sts . '</td>
				<td>' . $details . '</td>
				<td></td>
				</tr>';
            }
            $response['data'] = $data;
            $response['count'] = count($qr);
            $response['message'] = 'success';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function getDataSalaryReleaseHrhistory()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        $qr = $this->db->query("select * from t_release_salary where firm_id='$firm_id' and status!='0' ORDER BY id DESC")->result();
        $data = '';
        if (count($qr) > 0) {
            foreach ($qr as $row) {
                $status = $row->status;
                $user_id_emp = $row->user_id;
                $month = $row->month;
                $year = $row->year;
                $date = $row->created_on;

                $query_user_name = $this->db->query("select user_name from user_header_all where user_id='$user_id_emp'")->row();
                if ($this->db->affected_rows() > 0) {
                    $user_name = $query_user_name->user_name;
                } else {
                    $user_name = '';
                }
                $details = '<button class="btn btn-link btn-icon-only btn-default"data-toggle="modal" data-target="#viewdetail" onclick="get_usersaldata(\'' . $row->month . '\',\'' . $row->year . '\',\'' . $row->user_id . '\')"><i class="fa fa-eye" style="color:blue" aria-hidden="true"></i></button>';
                $sal_slip = '<button title="Not Available!" class="btn btn-link " disabled><i class="fa fa-file-pdf-o" style="color:red" aria-hidden="true"></i></button>';
                if ($status == 0) {
                    $sts = '<span class="badge badge-primary">Requested</span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Release" onclick="accept_rel_reqst(\'' . $row->id . '\',' . "1" . ')" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green"></i></button>'
                        . '<button type="button" data-toggle="tooltip" title="Hold" onclick="accept_rel_reqst(\'' . $row->id . '\',' . "2" . ')" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red"></i></button>';
                } else if ($status == 1) {
                    $sts = '<span class="badge badge-success">Released</span>';
                    $sal_slip = '<button class="btn btn-link" ><i class="fa fa-file-pdf-o" style="color:red" aria-hidden="true"></i></button>';
                    $action = '<button type="button" data-toggle="tooltip" title="Release" onclick="" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green"></i></button>'
                        . '<button type="button" data-toggle="tooltip" title="Hold" onclick="accept_rel_reqst(\'' . $row->id . '\',' . "2" . ')" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red"></i></button>';
                } else {
                    $action = '<button type="button" data-toggle="tooltip" title="Release" onclick="accept_rel_reqst(\'' . $row->id . '\',' . "1" . ')"  class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green"></i></button>'
                        . '<button type="button" data-toggle="tooltip" title="Hold" onclick=""disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red"></i></button>';
                    $sts = '<span class="badge badge-danger">Hold</span>';
                }
                $dateObj = DateTime::createFromFormat('!m', $month);
                $monthName = $dateObj->format('F'); // March

                $data .= '<tr>
				<td>' . $user_name . '</td>
				<td>' . $monthName . '</td>
				<td>' . date("d-m-Y", strtotime($date)) . '</td>
				<td>' . $sts . '</td>
				 <td><a href="' . base_url() . 'htmltopdf/' . $row->user_id . '/' . $year . '/' . $month . '" target="_blank">' . $sal_slip . '</a></td>
				<td>' . $details . '</td>
				<td></td>
				</tr>';
            }
            $response['data'] = $data;
            $response['count'] = count($qr);
            $response['message'] = 'success';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function acceptRelReq()
    {
        $id = $this->input->post('id');
        $id2 = $this->input->post('id2');
        $query = $this->db->query("update t_release_salary set status=$id2 where id='$id'");
        if ($this->db->affected_rowS() > 0) {
            $response['message'] = 'success';
            if ($id2 == 1) {
                $response['body'] = 'Realeased SuccessFully.';
            } else {
                $response['body'] = 'Hold SuccessFully.';
            }
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function getDataSalarydata()
    {
        $month = $this->input->post('month');
        $userid = $this->input->post('userid');
        $year = $this->input->post('year');
        $data = '';
        $data1 = '';

        $qr = $this->db->query("select * from t_salary_staff where user_id='$userid' AND month='$month' AND year='$year'");
        if ($this->db->affected_rows() > 0) {

            $result = $qr->result();
            //present days
            $qr1 = $this->db->query("select count(*) as cnt from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status IN (0,4,5) AND Year(date)='$year'")->row();
            $present_days = $qr1->cnt;
            //leave days
            $qr2 = $this->db->query("select count(*) as cnt_l from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status = 1 AND Year(date)='$year'")->row();
            $leave_days = $qr2->cnt_l;

            $data1 .= '<div class="col-md-4"><p class="text-info"><b>Total Month Days: </b>' . cal_days_in_month(CAL_GREGORIAN, $month, $year) . '</p></div>';
            $data1 .= '<div class="col-md-4"><p class="text-info"><b>Total Present Days: </b>' . $present_days . '</p></div>';
            $data1 .= '<div class="col-md-4"><p class="text-info"><b>Total Leaves: </b>' . $leave_days . '</p></div>';
            //Salary Sum type1
            $qr11 = $this->db->query("select sum(amt) as type1 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
            $type1 = $qr11->type1;
            //Salary Sum type7
            $qr17 = $this->db->query("select sum(amt) as type7 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 7 AND year='$year'")->row();
            $type7 = $qr17->type7;
            //Salary Sum type3
            $qr13 = $this->db->query("select sum(amt) as type3 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 3 AND year='$year'")->row();
            $type3 = $qr13->type3;
            //Salary deduction type2
            $qr12 = $this->db->query("select sum(amt) as type2 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 2 AND year='$year'")->row();
            $type2 = $qr12->type2;
            //Salary deduction type4
            $qr14 = $this->db->query("select sum(amt) as type4 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 4 AND year='$year'")->row();
            $type4 = $qr14->type4;
            //Salary deduction type8
            $qr14 = $this->db->query("select sum(amt) as type8 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 8 AND year='$year'")->row();
            $type8 = $qr14->type8;
            //Salary deduction type6
            $qr16 = $this->db->query("select sum(amt) as type6 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 6 AND year='$year'")->row();
            $type6 = $qr16->type6;
            $salry = $type1 + $type3 + $type7;
            $deduction = $type2 + $type4 + $type6 + $type8;
            //Salary Lop
            $stdamt = $this->db->query("select sum(std_amt) as std_amt from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
            $std_amt = $stdamt->std_amt;
            $Lop = $std_amt - $type1;
            $data1 .= '<br>
			<div class="col-md-4"><p class="text-info"><b>Gross Salary: </b>' . round($std_amt, 2) . '</p></div>';
            $data1 .= '<div class="col-md-4"><p class="text-info"><b>Total Deduction: </b>' . round($deduction, 2) . '</p></div>';
            $data1 .= '<div class="col-md-4"><p class="text-info"><b>Loss of Pay: </b>' . round($Lop, 2) . '</p></div>';
            $data1 .= '<div class="col-md-4"><p class="text-info"><b>Net Payable Salary: </b>' . round(($salry - $deduction), 2) . '</p></div>';

            foreach ($result as $row) {

                $category = $row->category;
                $typename = $row->salary_type;
                $std_amt = $row->std_amt;
                $amt = $row->amt;
                if ($category == 1) {
                    $sts = 'Salary';
                } else if ($category == 2) {
                    $sts = 'Deduction';
                } else if ($category == 3) {
                    $sts = 'Other Salary';
                } else if ($category == 4) {
                    $sts = 'Other Deduction';
                } else if ($category == 5) {
                    $sts = 'Income Tax';
                } else if ($category == 7) {
                    $sts = 'Extra';
                } else if ($category == 8) {
                    $sts = 'Sandwich Leave';
                } else {
                    $sts = 'None';
                }

                if ($category == 1) {
                    $lop = $std_amt - $amt;
                } else {
                    $lop = 0;
                }

                if ($category == 0) {
                    $data .= '';
                } else if ($category != 5) {
                    $data .= '<tr>
				<td>' . $typename . '</td>
				<td>' . round($std_amt, 2) . '</td>
				<td>' . round($amt, 2) . '</td>
				<td>' . round($lop, 2) . '</td>
				<td>' . $sts . '</td>
				</tr>';
                }
            }

            $response['data'] = $data;
            $response['data1'] = $data1;
            $response['message'] = 'success';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function tax_calculation($month, $select_year)
    {
        $session_data = $this->session->userdata('login_session');
        $userid = ($session_data['emp_id']);
        $get_gross_salary = $this->db->query("select sum(value) as monthly_sal from t_salarytype where user_id='$userid'")->row();
        if ($this->db->affected_rows() > 0) {
            $total_sal = $get_gross_salary->monthly_sal;
            $yearly_sal = $total_sal * 12;
        } else {
            $yearly_sal = 0;
        }

        $get_user_gender = $this->db->query("select gender,date_of_joining from user_header_all where user_id ='$userid'")->row();
        $gender = $get_user_gender->gender;
        $date_of_joining = $get_user_gender->date_of_joining;
        if ($gender == 1) {
            $data = $this->get_datastandard(2, $yearly_sal);

        } else {
            $data = $this->get_datastandard(1, $yearly_sal);
        }
        //t_salary_staff
        $query_get_curr_month_salary = $this->db->query("select sum(std_amt)as monthsal from t_salary_staff where user_id='$userid' AND month='$month' AND category IN(1,3) AND year='$select_year'")->row();
        if ($this->db->affected_rows() > 0) {
            $CurrMonthSal = $query_get_curr_month_salary->monthsal;
        } else {
            $CurrMonthSal = 0;
        }


        if ($data != FALSE) {
            $tax_percent = $data[2];
            $charges = $data[3];

        }

        $time = strtotime($date_of_joining);
        $Joinmonth = date("m", $time);
        $Joinyear = date("Y", $time);
        $Currmonth = date("m");
        $Curryear = date("Y");
        if ($Joinyear < $Curryear) {
            $start_month = 4;
        } else if ($Joinyear == $Curryear && $Joinmonth > 3) {
            $start_month = $Joinmonth;
        } else {
            $start_month = 4;
        }
        $yearly_tax = (($yearly_sal) * ($tax_percent / 100)) + $charges;
        $month_array = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3);
        $monthly_sal = $yearly_sal / 12;
        $monthly_tax = $yearly_tax / 12;
        if ($month == 4 && $start_month == 4) {
            $tax_paid = 0;
            $CurrentMonthSal = $CurrMonthSal;
            $curr_M_tax = $yearly_tax / 12;

        } else {
            $mn_num = array_search($month, $month_array);
            $prev_months_arr = array_slice($month_array, 0, $mn_num);   // first part
            $next_months_arr = array_slice($month_array, $mn_num + 1); // second part
            $total_prevSal = 0;
            $tax_paid = 0;
            if (count($prev_months_arr) > 0) {
                for ($i = 0; $i < count($prev_months_arr); $i++) {
                    if ($month >= 4 && $month <= 12) {
                        $prev_months = $prev_months_arr[$i];
                        if ($prev_months == 1 || $prev_months == 2 || $prev_months == 3) {
                            $year_slect = $Curryear + 1;
                        } else {
                            $year_slect = $Curryear;
                        }

                    } else {
                        $prev_months = $prev_months_arr[$i];
                        if ($prev_months == 1 || $prev_months == 2 || $prev_months == 3) {
                            $year_slect = $Curryear;
                        } else {
                            $year_slect = $Curryear - 1;
                        }
                    }

                    $previousMonthSalaryCount = $this->getPreviousSalary($prev_months, $year_slect, $userid);
                    $total_prevSal += $previousMonthSalaryCount;
                    if ($previousMonthSalaryCount > 0) {
                        $tax_paid += $monthly_tax;
                    } else {
                        $tax_paid += 0;
                    }

                }
            }

            $rem_mon_cnt = count($next_months_arr);
            if (count($next_months_arr) > 0) {
                $rem_months = count($next_months_arr);
                $total_remSal = $monthly_sal * $rem_months;
            } else {
                $total_remSal = 0;
            }

            $total_salary = $total_prevSal + $total_remSal;
            $ttl_tax = (($total_salary) * ($tax_percent / 100)) + $charges;
            if ($rem_mon_cnt > 0) {
                $remTax = ($ttl_tax - $tax_paid) + $monthly_tax;
                $curr_M_tax = $remTax / $rem_mon_cnt;
            } else {
                $curr_M_tax = $monthly_tax;
            }
        }
        return $curr_M_tax;
    }


    public function get_datastandard($type, $yearly_sal)
    {
        $query = $this->db->query("select * from t_form16_standardfigure where type='$type'")->result();
        $array = array();
        foreach ($query as $row) {
            $max_value = $row->max_value;
            $min_value = $row->min_value;
            if ($yearly_sal <= $max_value && $yearly_sal > $min_value) {
                $array[] = $max_value;
                $array[] = $min_value;
                $array[] = $row->tax_percent;
                $array[] = $row->charges;
                return $array;
            }
        }
    }

    public function getPreviousSalary($prev_months, $year_slect, $userid)
    {
        $query_get_curr_month_salary = $this->db->query("select std_amt from t_salary_staff where user_id='$userid' AND month='$prev_months'AND year='$year_slect' AND category IN(1,3)")->result();
        if ($this->db->affected_rows() > 0) {
            $Salary = 0;
            foreach ($query_get_curr_month_salary as $row) {
                $Salary += $row->std_amt;
            }
        } else {
            $Salary = 0;
        }
        return $Salary;
    }


    public function get_month_list()
    {
        $session_data = $this->session->userdata('login_session');
        $userid = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        if ($user_type == 5) {
            $userid = $this->input->post("employee_id");
        }
        $year = $this->input->post("year");
        $get_user_gender = $this->db->query("select date_of_joining from user_header_all where user_id ='$userid'")->row();
        $date_of_joining = $get_user_gender->date_of_joining;

        $time = strtotime($date_of_joining);
        $Joinmonth = date("m", $time);
        $Joinyear = date("Y", $time);
        $Currmonth = date("n");
        $Curryear = date("Y");
        $query = $this->db->query("select month from t_release_salary where user_id='$userid' AND year='$year' AND status = '1'")->result();

        $month_arr = array();
        if (count($query) > 0) {
            foreach ($query as $row) {
                $month_arr[] = $row->month;
            }
        } else {
            $month_arr[] = '';
        }

        $month_array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        $filteredarray = '';

        if ($year == $Joinyear) {
            $mn_num = array_search($Joinmonth, $month_array);
            $next_months_arr = array_slice($month_array, $mn_num);
            $filteredarray = array_values(array_diff($next_months_arr, $month_arr));
        } else if ($year == "2020") {

            $month_array = array(4, 5, 6, 7, 8, 9, 10, 11, 12);
            $filteredarray = array_values(array_diff($month_array, $month_arr));
        } else {

            $filteredarray = array_values(array_diff($month_array, $month_arr));

        }
        if ($filteredarray == "") {
            $response['message'] = 'fail';
        } else {
            $response['message'] = 'success';
            $response['montharr'] = $filteredarray;
        }

        echo json_encode($response);
    }

    function getEmployeeLeaveCount($user_id, $month, $year)
    {
        $total_leave_cnt = 0;
        $resultObject = $this->db->query("select count(id) as leave_cnt from employee_attendance_leave where leave_status=2 and activity_status=0 and user_id='" . $user_id . "' and MONTH(date)=" . $month . " and YEAR(date)=" . $year);
        if ($this->db->affected_rows() > 0) {
            $resultData = $resultObject->row();
            $total_leave_cnt = $resultData->leave_cnt;
        }
        return $total_leave_cnt;
    }

    function storeLeaveAcceptData($user_id, $month, $year, $leave_accept, $leave_accept_reason)
    {
        $data=array('user_id'=>$user_id,'month'=>$month,'year'=>$year,'status'=>$leave_accept,'reason'=>$leave_accept_reason);
        $select=$this->db->query('select id from t_leave_acceptance where user_id="'.$user_id.'" and month="'.$month.'" and year="'.$year.'"');
        if($this->db->affected_rows()>0)
        {
            $delete=$this->db->query('delete from t_leave_acceptance where user_id="'.$user_id.'" and month="'.$month.'" and year="'.$year.'"');
        }
        $insert=$this->db->insert('t_leave_acceptance',$data);
        return $insert;
    }
    function deductEmployeeLeave111($user_id,$month,$year)
    {
        $queryData=$this->db->query('select count(id) as cnt from t_attendance_staff where user_id="'.$user_id.'" and MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status=1');
        if($this->db->affected_rows()>0)
        {
            $qData=$queryData->row()->cnt;
            for ($i=1; $i <=$qData ; $i++) {
                $userData=$this->db->query('select user_id,id,total_leave_available from user_header_all where user_id="'.$user_id.'"');
                if($this->db->affected_rows()>0)
                {
                    $userData=$userData->row();
                    if($userData->total_leave_available>=1)
                    {
                        $total_leave_available=$userData->total_leave_available-1;
                        $this->db->query("UPDATE `user_header_all` SET `total_leave_available`='".$total_leave_available."' where id='".$userData->id."'");
                    }
                }
            }
        }
    }
    function  deductEmployeeLeave($user_id,$month,$year){
//        $user_id='U_189',=7,$year=2025
       // $user_id='U_189';$month=5;$year=2025;
        //get leave type with their id assign to employee
        $querData = $this->db->query("
                SELECT 
                    SUBSTRING_INDEX(type1, ':', 1) AS '1',
                    SUBSTRING_INDEX(type2, ':', 1) AS '2',
                    SUBSTRING_INDEX(type3, ':', 1) AS '3',
                    SUBSTRING_INDEX(type4, ':', 1) AS '4',
                    SUBSTRING_INDEX(type5, ':', 1) AS '5',
                    SUBSTRING_INDEX(type6, ':', 1) AS '6',
                    SUBSTRING_INDEX(type7, ':', 1) AS '7',
                    type1_balance,
                    type2_balance,
                    type3_balance,
                    type4_balance,
                    type5_balance,
                    type6_balance,
                    type7_balance
                FROM user_header_all 
                WHERE user_id = '".$user_id."'
            ")->row_array();

        $types   = [];
        $balances = [];

        if (!empty($querData)) {
            foreach ($querData as $key => $val) {
                if (!empty($val)) {
                    // If it's balance column → push to balance array
                    if (strpos($key, 'balance') !== false) {
                        $balances[$key] = $val;
                    } else {
                        $types[$val] = $key;
                    }
                }
            }
        }
        //$types=  Array ( [PL] => 1 [EL] => 2 [SL] => 3 ) // types of leaves in the organization

        //get leaves of the employees which are accepted in the current month by their types.

        $query = $this->db->query("
            SELECT 
                COUNT(id) AS totalTypeLeave, 
                leave_type 
            FROM leave_transaction_all 
            WHERE user_id = '".$user_id."' 
              AND MONTH(leave_date) = ".$month."
              AND YEAR(leave_date) = ".$year." 
              AND status=2
            GROUP BY leave_type
        ");

        $result = $query->result_array();

        $typeWise = [];
        if (!empty($result)) {
            foreach ($result as $row) {
                $typeWise[$row['leave_type']] = $row['totalTypeLeave'];
            }
        }
        // $typeWise= Array ( [PL] => 1 ) // leaves taken in current month by employee

        //UPDATE individual employee's leaves by their types
        foreach ($types as $keyT=>$type){
            if(array_key_exists($keyT,$typeWise)){
                $leavesTakenbyEMP=$typeWise[$keyT];
                $m='type'.$type.'_balance';
                $bal=$balances[$m] - $leavesTakenbyEMP;
                if($bal < 0){
                    $bal ==0;
                }
                $arrUpdate[$m]=$bal;
            }
        }

        //UPDATE query
        $where=array('user_id'=>$user_id);
        $this->db->where($where);
        $query=$this->db->update('user_header_all',$arrUpdate);
    }
}
