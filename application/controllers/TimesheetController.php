<?php

class TimesheetController extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('MasterModel');
        $this->load->model('Globalmodel');
        $this->db2 = $this->load->database('db2', TRUE);
    }

    public function index()
    {
        /*$session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id == "") {
            $email_id = $this->session->userdata('login_session');
        }*/
        //print_r($_SESSION);die;
        $this->load->view('time_sheet_view/schedule_task',array('page_title'=>'Daily Activity'));
    }
     public function schedule_task_new()
    {
        /*$session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id == "") {
            $email_id = $this->session->userdata('login_session');
        }*/
        //print_r($_SESSION);die;
        $this->load->view('time_sheet_view/schedule_task_new',array('page_title'=>'Daily Activity'));
    }



    public function task_project_report()
    {
        $this->load->view('time_sheet_view/task_project_report',array('page_title'=>'Task/Projects Reports'));
    }
    public function check_userOn_leave()
    {
        if($this->input->post('date')!=='' || $this->input->post('date')!==null)
        {
            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $user_id = ($session_data['emp_id']);
            } else {
                $user_id = $this->session->userdata('login_session');
            }
            if ($user_id == "") {
                $user_id = $this->session->userdata('login_session');
            }
            $date = date('Y-m-d',strtotime($this->input->post('date')));
            $checkLeaves = $this->Globalmodel->getData('*',
                array('user_id' => $user_id,'leave_date' => $date,'status' => 2),'leave_transaction_all');

            if(!empty($checkLeaves)){
                $response['status'] = 200;
                $response['body'] = date('d-M-Y',strtotime($checkLeaves->leave_date));
            }else{
                $response['status'] = 201;
                $response['body'] = 'Not on Leave';
            }
        }else{
            $response['status'] = 202;
            $response['body'] = 'Somthing went wrong';
        }
        echo json_encode($response);

    }

    public function check_taskTime_avail()
    {
        if($this->input->post('date')!=='' || $this->input->post('date')!==null)
        {
            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $user_id = ($session_data['emp_id']);
            }
            if($this->input->post('end_time')=='' || $this->input->post('end_time')==0)
            {
                $response['status'] = 202;
                $response['body'] = 'Not selected Time';
                echo json_encode($response);
                exit;
            }
            $date = date('Y-m-d',strtotime($this->input->post('date')));
            $str_time = $this->input->post('str_time');
            $end_time = $this->input->post('end_time');
            $employee_id=$this->input->post('employee_id');
            if($employee_id!="" && $employee_id!="Search by Employee")
            {
                $user_id=$employee_id;
            }

            if(strtotime($end_time) > strtotime($str_time)){
                //$checkdata = $this->MasterModel->_rawQuery('select * from employee_schedule_task where date ="'.$date.'" and user_id="'.$user_id.'"');
                $checkdata = $this->MasterModel->_rawQuery('select * from employee_schedule_task where date ="'.$date.'" and user_id="'.$user_id.'"
                and ((from_time > "'.$str_time.'" AND from_time < "'.$end_time.'") OR (to_time < "'.$str_time.'" AND to_time >= "'.$end_time.'"))');
                //---- OR code for check time availability of task on 2/2/2023---
                // and ((from_time <= "'.$str_time.'" AND to_time > "'.$str_time.'") OR (from_time < "'.$end_time.'" AND to_time >= "'.$end_time.'")) ');

                if($checkdata->totalCount>0){

                    /* foreach($checkdata->data as $val)
                     {
                         if(strtotime($end_time) < strtotime($val->from_time))
                         {
                             $response['status'] = 201;
                             $response['body'] = 'Not between date';
                             echo json_encode($response);
                             exit;
                         }
                     } */
                    $response['status'] = 201;
                    $response['body'] = 'Already Schedule Task At this Time,Please Select Another end Time';

                }else{
                    $response['status'] = 200;
                    $response['body'] = 'Empty row';
                }
            }else{
                $response['status'] = 201;
                $response['body'] = 'select end time after start time ';
            }

        }else{
            $response['status'] = 201;
            $response['body'] = 'somthing went wrong ';
        }

        echo json_encode($response);
    }

    public function add_new_task()
    {
        $session_data = $this->session->userdata('login_session');
        // print_r($session_data);die;
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $user_id = ($session_data['emp_id']);
            } else {
                $user_id = $this->session->userdata('login_session');
            }
            if ($user_id == "") {
                $user_id = $this->session->userdata('login_session');
            }

            if ($this->input->post('task_title') !== '' || $this->input->post('task_title')!==null) {
                $task_desc = $this->input->post('task_desc');
                $task_id = $this->input->post('task_id');
                $task_id1= $this->input->post('task_id1');
                $task_title = $this->input->post('task_title');
                $project_id = $this->input->post('project_name');
                $time_spand = $this->input->post('select_task_hours');
                $task_type = $this->input->post('taskType');
                $work_status = $this->input->post('taskStatus');
                $date = $this->input->post('select_date');
                $project_data=explode('-',$project_id);
                        $project_name='';
                        if(count($project_data)>1)
                        {
                            $project_id=$project_data[0];
                            $project_name=$project_data[1];
                        }

                $board_id = !empty($this->input->post('board_id')) ? $this->input->post('board_id') : '';

                // Check if item_id is provided, otherwise set it as empty
                $item_id = !empty($this->input->post('item_id')) ? $this->input->post('item_id') : '';

                if ($this->input->post('type') == 'add') {
                    $data = array(
                        'user_id' =>   $user_id,
                        'task_id'=>$task_id,
                        'project_name' => $project_name,
                        // 'task_desc' => $task_desc,
                        'time_spand' => $time_spand,
                        'date' => date('Y-m-d', strtotime($date)),
                        'board_id' => $board_id,

                        'item_id' => $item_id,
                        'project_id' => $project_id,
                        'activity_type' => $task_type,
                        'task_title' => $task_title,
                        'task_desc' => $task_desc,
                        'work_status' => $work_status
                    );

                    // Insert the task data into the database
                    $resultObject = $this->db->insert('employee_schedule_task', $data);

                    if ($resultObject) {
                        $response["status"] = 200;
                        $response["body"] = "Created successfully";
                    } else {
                        $response["status"] = 201;
                        $response["body"] = "Failed to create task";
                    }
                }else if ($this->input->post('type') == 'edit' || $this->input->post('task_id1')!="") {
                    $update_data=array('project_name' => $project_name,
                            // 'task_desc' => $task_desc,
                            'task_id'=>$task_id,
                            'user_id'=>$user_id,
                            'date'=>date('Y-m-d', strtotime($date)),
                            'time_spand' => $time_spand,

                            'board_id'=>$board_id,
                            'item_id'=>$item_id,
                            'project_id' => $project_id,
                            'activity_type'=>$task_type,
                            'task_title'=>$task_desc,
                            'work_status'=>$work_status,
                            'updated_at' => date('Y-m-d H:i:s'));

                    // print_r($update_data);exit();
                    $update = $this->db->update('employee_schedule_task', $update_data, array('id' => $task_id1));
                    if ($update) {
                        $response['status'] = 200;
                        $response['body'] = "Data Updated Successfully";
                    } else {
                        $response['status'] = 201;
                        $response['body'] = "Something Went Wrong";
                    }
                }

            } else {
                $response["status"] = 201;
                $response["body"] = "Task description is required";
            }
            echo json_encode($response);

    }

    // public function add_new_task()
    // {
    //     // print_r($this->input->post());exit();
    //     $session_data = $this->session->userdata('login_session');
    //     if (is_array($session_data)) {
    //         $data['session_data'] = $session_data;
    //         $email_id = ($session_data['emp_id']);
    //     } else {
    //         $email_id = $this->session->userdata('login_session');
    //     }
    //     if ($email_id == "") {
    //         $email_id = $this->session->userdata('login_session');
    //     }

    //     $employee_id=$this->input->post('employee_id_task');
    //     if($employee_id!="" && $employee_id!="Search by Employee")
    //     {
    //         $email_id=$employee_id;
    //     }

    //     if ($this->input->post('task_title') !== '' || $this->input->post('task_title')!==null) {
    //         $task_title=$this->input->post('task_title');
    //         $project_id = $this->input->post('project_name');
    //         $taskDesc = $this->input->post('task_desc');
    //         $date = $this->input->post('date');
    //         $from_time = $this->input->post('start_time');
    //         $task_id = $this->input->post('task_id1');
    //         $task_type=$this->input->post('taskType');
    //         $work_status=$this->input->post('taskStatus');
    //         $user_id = $this->input->post('time');
    //         $project_data=explode('-',$project_id);
    //         $project_name='';
    //         if(count($project_data)>1)
    //         {
    //             $project_id=$project_data[0];
    //             $project_name=$project_data[1];
    //         }

    //         if(isset($_POST['add_total_time']) && $this->input->post('add_total_time')!=='' && $this->input->post('add_total_time')!=='0')
    //         {
    //             /*-- END OLD CODE-----*/
    //             /*   $total_hour = $this->input->post('add_total_time');
    //                $datetime = date('Y-m-d', strtotime($date)).' '.$from_time;
    //                $plus = 60 * 60 * $total_hour;
    //                $to_time = strtotime($datetime) + $plus;
    //                $end_time = date('H:i:s', $to_time);
    //                $total_min= $total_hour * 60;
    //                $total_box= $total_min/15; //each box contain 15 min
    //                $width = $total_box * 101;*/
    //             /*-- END OLD CODE-----*/

    //             $end_time= $this->input->post('add_total_time');

    //             $date1 = date('Y-m-d', strtotime($date));
    //             $datetime1 = new DateTime($date1.' '. $from_time);
    //             $datetime2 = new DateTime($date1.' '. $end_time);
    //             $interval = $datetime1->diff($datetime2);
    //             $hour =  $interval->format('%h') > 0 ? ($interval->format('%h') < 10 ? "0" + $interval->format('%h') : $interval->format('%h')) : "00";
    //             $min =  $interval->format('%i') > 0 ? ($interval->format('%i') < 10 ? "0" + $interval->format('%i') : $interval->format('%i')) : "00";
    //             $total_hr = $hour . ":" . $min;

    //             $total_minutes = (strtotime($end_time) - strtotime($from_time)) / 60;
    //             $total_box= $total_minutes/15; //each box contain 15 min
    //             $calc_exactWidth = $total_box * 100;
    //             $calc_exactWidth1 = ($total_box-1) * 2;
    //             $width = $calc_exactWidth + $calc_exactWidth1;

    //         }else{
    //             $strt_time = strtotime($from_time);
    //             $end_time = date("h:i:s ", strtotime('+15 minutes', $strt_time));
    //             $width = 100;
    //             $total_hr = '00:15';
    //         }


    //         if($this->input->post('board_id')!==null || $this->input->post('board_id')!==''){

    //             $board_id = $this->input->post('board_id');
    //         }else{
    //             $board_id = '';
    //         }
    //         if($this->input->post('item_id')!==null || $this->input->post('item_id')!==''){

    //             $item_id = $this->input->post('item_id');
    //         }else{
    //             $item_id = '';
    //         }

    //         if ($this->input->post('type') == 'add') {
    //             $data = array('user_id' => $user_id,
    //                 'project_name' => $project_name,
    //                 'task_desc' => $taskDesc,
    //                 'from_time' => $from_time,
    //                 'to_time' => $end_time,
    //                 'date' => date('Y-m-d', strtotime($date)),
    //                 'total_hour' => $total_hr,
    //                 'width'=>$width,
    //                 'board_id'=>$board_id,
    //                 'item_id'=>$item_id,
    //                 'project_id' => $project_id,
    //                 'activity_type'=>$task_type,
    //                 'task_title'=>$task_title,
    //                 'work_status'=>$work_status
    //             );
    //             $resultObject = $this->MasterModel->_insert('employee_schedule_task', $data);
    //             if ($resultObject->status) {
    //                 $response["status"] = 200;
    //                 $response["body"] = "create successfully";
    //             } else {
    //                 $response["status"] = 201;
    //                 $response["body"] = "Failed";
    //             }
    //         } else if ($this->input->post('type') == 'edit' || $this->input->post('task_id1')!="") {
    //             $update_data=array('project_name' => $project_name,
    //                     'task_desc' => $taskDesc,
    //                     'user_id'=>$user_id,
    //                     'date'=>date('Y-m-d', strtotime($date)),
    //                     'from_time' => $from_time,
    //                     'to_time' => $end_time,
    //                     'total_hour' => $total_hr,
    //                     'width'=>$width,
    //                     'board_id'=>$board_id,
    //                     'item_id'=>$item_id,
    //                     'project_id' => $project_id,
    //                     'activity_type'=>$task_type,
    //                     'task_title'=>$task_title,
    //                     'work_status'=>$work_status);
    //             // print_r($update_data);exit();
    //             $update = $this->MasterModel->_update('employee_schedule_task', $update_data, array('id' => $task_id));
    //             if ($update->status) {
    //                 $response['status'] = 200;
    //                 $response['body'] = "Data Updated Successfully";
    //             } else {
    //                 $response['status'] = 201;
    //                 $response['body'] = "Something Went Wrong";
    //             }
    //         }
    //     } else {
    //         $response["status"] = 201;
    //         $response["body"] = "Failed";
    //     }
    //     echo json_encode($response);

    // }

    public function getTaskCalendarView()
    {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $employee_id=$this->input->post('employee_id');
            if($employee_id=="" || $employee_id=="Search by Employee")
            {
                $user_id = ($session_data['emp_id']);
            }else
            {$user_id=$employee_id;}

            $leaveArr = array();
            $alternate_satArr = array();
            $holidayArr = array();
            if($this->input->post('year')!=='')
            {
                $year = $this->input->post('year');
                $month = $this->input->post('month')+1;
                $date = $year.'-'.$month;
                $queryData = $this->MasterModel->_rawQuery('select * from employee_schedule_task where user_id="' . $user_id . '" and year(date) ="'.$year.'" and month(date)="'.$month.'"');
//              print_r($queryData);die;
                /*----For alternate saturdays-------*/
                $result = $this->customer_model->get_firm_id($user_id);
                if ($result !== false) {
                    $firm_id = $result['firm_id'];
                }
               $alternate_sat = $this->MasterModel->_rawQuery("select date from holiday_master_all where year(date) ='$year' and month(date)='$month' and firm_id='$firm_id' and is_alternate = 1 and
                 alternate_id in (select alternate_id from alternate_holiday_master where user_id = '$user_id')");
                if($alternate_sat->totalCount > 0)
                {
                    foreach($alternate_sat->data as $sat_date){
                        array_push($alternate_satArr,date('Y-m-d',strtotime($sat_date->date)));
                    }
                }
                $response['alternate_satArr'] = $alternate_satArr;
                /*----END code for alternate saturdays-------*/

                /*----Start code of Office Holidays---*/
                $holiday_data = $this->MasterModel->_rawQuery("select * from holiday_master_all where year(date) ='$year' and month(date)='$month' and firm_id='$firm_id' and is_alternate = 0 and category =1");
                if($holiday_data->totalCount >0)
                {
                    foreach($holiday_data->data as $holiday){
                        $data = array('holiday_date'=>date('Y-m-d',strtotime($holiday->date)),'holiday'=>'holiday','holiday_name'=>$holiday->holiday_name);
                        array_push($holidayArr,$data);
                      /* $dat1 = array('type_holiday'=>'holiday',
                                      'holiday_date'=>date('Y-m-d',strtotime($holiday->date)),
                                      'holiday_name'=>$holiday->holiday_name
                               );*/
                    }
                    $is_holiday =1;
                }else{
                    $is_holiday=0;

                }

                $response['holidayArr'] = $holidayArr;
                $response['is_holiday'] = $is_holiday;
                /*----END code of Office Holidays---*/

                $leave_data = $this->MasterModel->_rawQuery('select leave_date from leave_transaction_all where Month(leave_date) = "' . $month . '" and Year(leave_date) = "' . $year . '" and user_id="' . $user_id . '" and status = 2 ');
                if($leave_data->totalCount > 0)
                {
                    foreach($leave_data->data as $leave)
                    {
                        array_push($leaveArr,date('Y-m-d',strtotime($leave->leave_date)));
                    }

                }
                $response['leaveArr'] = $leaveArr;
                if ($queryData->totalCount > 0) {

                    $dataArr = array();
                    $cntDateTimeArr = array();
                    foreach ($queryData->data as $row) {
                        $time1 = strtotime($row->from_time);
                        $time2 = strtotime($row->to_time);
                        $count = 0;
                        for ($i = 0; $i < 37; $i++) {
                            if ($time1 == $time2) {
                                break;
                            }

                            $time1 = strtotime('+15 minutes', $time1);
                            $count++;
                        }
                        $row->count = $count;

                        $cntDateTimeArr[$row->date][] =$row->total_hour ;
                        array_push($dataArr,$row);
                    }


                    $result = $queryData->data;
                    $response['status'] = 200;
                    $response['body'] = $dataArr;
                    $response['total_timehr'] = $cntDateTimeArr;
                } else if (count($leaveArr) > 0)
                {
                    $response['status'] = 200;
                }else if(count($alternate_satArr)>0){
                    $response['status'] = 200;
                } else if(count($holidayArr)>0){
                    $response['status'] = 200;
                }else {
                    $response['status'] = 201;
                    $response['body'] = 'No Data Found';
                }

            }else{
                $queryData = $this->MasterModel->_rawQuery('select * from employee_schedule_task where user_id="' . $user_id . '"');
                $response['status'] = 201;
                $response['body'] = 'Required data missing';
            }
        } else {
            $response['status'] = 201;
            $response['body'] = 'Somthing Went wrong';
        }
        echo json_encode($response);
    }

    public function saveTotalTaskTime()
    {
        if ($this->input->post('totalTime') !== '') {
            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $user_id = ($session_data['emp_id']);
            }
            $task_id = $this->input->post('task_id');
            $total_time_per_task = $this->input->post('totalTime');
            $total_min = $this->input->post('min');

            $tasData = $this->MasterModel->_select('employee_schedule_task', array('id' => $task_id), '*', true);
            $strt_time = strtotime($tasData->data->from_time);
            $end_time = date("H:i:s ", strtotime('+' . $total_min . ' minutes', $strt_time));
            $total_width = $this->input->post('total_width');

            /*------ check Task time availabilty---------*/
            $date1 = date('Y-m-d',strtotime($tasData->data->date));
            $task_times = $this->MasterModel->_rawQuery('select * from employee_schedule_task where date="'.$date1.'" and user_id ="'.$user_id.'" and id!= "'.$task_id.'"');

            if($task_times->totalCount>0) {
                foreach ($task_times->data as $val) {
                    $dragEndTime = new DateTime($end_time);
                    $dragStartTime = new DateTime($tasData->data->from_time);
                    $startTime = new DateTime($val->from_time);
                    $toTime = new DateTime($val->to_time);

                    if ($dragStartTime < $startTime && $dragEndTime > $toTime) {
                        $response['status'] = 201;
                        $response['body'] = "Time can not be extend";
                        echo json_encode($response);
                        exit;
                    }else if ($dragEndTime > $startTime && $dragEndTime <= $toTime) {
                        $response['status'] = 201;
                        $response['body'] = "Time can not be extend";
                        echo json_encode($response);
                        exit;
                    }

                    /*if ($dragEndTime > $startTime && $dragEndTime <= $toTime) {
                         $response['status'] = 201;
                         $response['body'] = "Time can not be extend";
                         echo json_encode($response);
                         exit;
                     }else if ($dragEndTime > $toTime) {
                         $response['status'] = 201;
                         $response['body'] = 'Time can not be extend';
                         echo json_encode($response);
                         exit;
                     }*/

                }

                /*----Update Task time-----*/
                $data = array(
                    'total_hour' => $total_time_per_task,
                    'to_time' => $end_time,
                    'width'=>$total_width
                );

                $updateTaskTime = $this->MasterModel->_update('employee_schedule_task', $data, array('id' => $task_id));

                if ($updateTaskTime->status) {
                    $response['status'] = 200;
                    $response['body'] = $total_width;
                    $response['msg'] = "Updated Successfully";
                } else {
                    $response['status'] = 201;
                    $response['body'] = "Something Went Wrong";
                }
                /*----Update Task time-----*/

            }else{
                /*----Update Task time-----*/
                $data = array(
                    'total_hour' => $total_time_per_task,
                    'to_time' => $end_time,
                    'width'=>$total_width
                );

                $updateTaskTime = $this->MasterModel->_update('employee_schedule_task', $data, array('id' => $task_id));

                if ($updateTaskTime->status) {
                    $response['status'] = 200;
                    $response['body'] = $total_width;
                    $response['msg'] = "Updated Successfully";
                } else {
                    $response['status'] = 201;
                    $response['body'] = "Something Went Wrong";
                }
                /*----Update Task time-----*/
            }
            /*------ check Task time availabilty---------*/

        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function get_total_hours_time()
    {
        if($this->input->post('dates')!=='')
        {
            $datesArr = $this->input->post('dates');
            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $user_id = ($session_data['emp_id']);
                $user_type = ($session_data['user_type']);
            }
            $employee_id=$this->input->post('employee_id');
            if($employee_id!="" && $employee_id!="Search by Employee")
            {
                $user_id=$employee_id;
            }
            $html1 = '';
            $html = '';
             $result = $this->customer_model->get_firm_id($user_id);
                if ($result !== false) {
                    $firm_id = $result['firm_id'];
                }
               // $all_active_users=all_active_employee($firm_id);
                if ($user_type == 4) {
                    $all_active_users=$this->MasterModel->_rawQuery('select user_name,user_id from user_header_all u where user_id="'.$user_id.'" or senior_user_id="'.$user_id.'" and activity_status =1');

                } else {
                    $all_active_users=$this->MasterModel->_rawQuery('select user_name,user_id from user_header_all u where firm_id="'.$firm_id.'" and user_type in (4) and activity_status =1');
                }
            foreach ($all_active_users->data as $emp_row){
                $empuser=substr($emp_row->user_name, 0, 17);
                $html1 .='<div class="timeslot date1 active_class'.$emp_row->user_id.'"  id="active_class'.$emp_row->user_id.'">'.$empuser.'</div>';
            }

            // foreach($datesArr as $date){
            //     $date1 = trim(preg_replace('/\s*\([^)]*\)/', '', $date)); // Sun Nov 01 2020 00:00:00 GMT+0530
            //     $dt = DateTime::createFromFormat('D M d Y H:i:s O',$date1);
            //     $day = $dt->format('d');
            //     //print_r($day);
            //     $month = $dt->format('m');
            //     $month1 = $dt->format('M');
            //     $year = $dt->format('Y');
            //     $fullDate = $day.'-'.$month.'-'.$year;

            //     /*----For alternate saturdays-------*/
            //     date_default_timezone_set('Asia/Kolkata');
            //     $checkDate = $year.'-'.$month.'-'.$day;
            //     $result = $this->customer_model->get_firm_id($user_id);
            //     if ($result !== false) {
            //         $firm_id = $result['firm_id'];
            //     }
            //     $qrr = $this->db->query("select alternate_id  from holiday_master_all where date ='$checkDate' and firm_id='$firm_id' and is_alternate = 1");
            //     if ($this->db->affected_rows() > 0) {
            //         $res = $qrr->row();
            //         $alternate_id = $res->alternate_id;
            //         $checkUserAlt = $this->db->query('SELECT * FROM alternate_holiday_master where alternate_id ="'.$alternate_id.'" and user_id = "'.$user_id.'"')->result();

            //         if (count($checkUserAlt) > 0) {
            //             // If this user is configured for alternate saturdays
            //             $holiday = 1; //Holiday
            //         }else{
            //             $holiday = 0;
            //         }
            //     }else{
            //         $holiday = 'No';
            //     }
            //     /*----For alternate saturdays-------*/
            //     /*---For Holiday--*/
            //     $public_holiday = $this->db->query("select *  from holiday_master_all where date ='$checkDate' and firm_id='$firm_id' and is_alternate = 0 and category =1");
            //     if ($this->db->affected_rows() > 0) {
            //         $public_holiday = $qrr->row();
            //         $all_holiday = 1; //Holiday
            //     }else{
            //        $all_holiday = '0';
            //     }
            //     /*---For Holiday--*/


            //     $dataTime = $this->MasterModel->_rawQuery('SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`total_hour`))) as total_hr FROM employee_schedule_task where Month(date) = "' . $month . '" and Year(date) = "' . $year . '" and day(date) = "' . $day . '" and user_id="' . $user_id . '"');
            //     if($dataTime->totalCount>0)
            //     {
            //         foreach($dataTime->data as $val)
            //         {
            //             $new_date = $day.'-'.$month1.'-'.$year;
            //             $currentdate = date('d-M-Y');
            //             $day_name = $dt->format('D');
            //             $active = '';
            //             if ($new_date == $currentdate) {
            //                 $active .= 'active';
            //             } else if($day_name=='sun' || $day_name=='Sun' || $day_name=='SUN'){
            //                 $active .= 'is_sunday';
            //             } else if($holiday==1){
            //                 $active .= 'is_sunday';
            //             } else if($all_holiday==1){
            //                 $active .= 'is_sunday';
            //             }else{
            //                 $active .= '';
            //             }

            //             $is_holiday ='';
            //             if($day_name=='sun' || $day_name=='Sun' || $day_name=='SUN'){
            //                 $is_holiday .='is_holiday';
            //             }else if($holiday==1){
            //                 $is_holiday .='is_holiday';
            //             }else if($all_holiday==1){
            //                 $is_holiday .='is_holiday';
            //             }else{
            //                 $is_holiday .='';
            //             }

            //             $html1 .='<div class="timeslot date1 active_class'.$day.'  '.$active.'"  id="active_class'.$day.'">'.$day.'-'.$month1.'-'.$year.'</div>';
            //             if($val->total_hr=='' || $val->total_hr==null)
            //             {
            //                 $html .='<div class="timeslot date1 total_workinghours '.$is_holiday.'" id="t_'.$day.'_'.$month.'">-</div>';
            //             }else{
            //                 $html .='<div class="timeslot date1 total_workinghours '.$is_holiday.'" id="t_'.$day.'_'.$month.'">'.$val->total_hr.'</div>';
            //             }
            //         }

            //     }/*else{
			// 		$response['status']=201;
			// 		$response['body']='No Data Found';
			// 	}*/
            // }
            $response['status']=200;
            $response['date']=$html1;
            $response['total_hr']=$html;

        }else{
            $response['status']=201;
            $response['body']='Required data missing';
        }

        echo json_encode($response);

    }


    function deleteTask()
    {
        $id = $this->input->post('task_id');
        if ($id != '' && $id != null) {

            $Taskdate = $this->MasterModel->_select('employee_schedule_task', array('id' => $id),'date,user_id',true);
            if($Taskdate->totalCount >0)
            {
                $date = $Taskdate->data->date;
                $user_id = $Taskdate->data->user_id;
                $day = date('d',strtotime($date));
                $month = date('m',strtotime($date));
                $deleteTask = $this->MasterModel->_update('employee_schedule_task',array('status'=>2), array('id' => $id));
                if ($deleteTask->status) {
                    $response['status'] = 200;
                    $response['body'] = "Deleted Successfully";
                    $response['day'] = $day;
                    $response['month'] = $month;
                    $response['date'] = $date;
                    $response['user_id'] = $user_id;
                } else {
                    $response['status'] = 201;
                    $response['body'] = "Something Went Wrong";
                }
            } else {
                $response['status'] = 201;
                $response['body'] = "No data Found";
            }

        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function updateEditableContent()
    {
        if($this->input->post('content')!=='' && $this->input->post('task_id')!=='')
        {
            $content = $this->input->post('content');
            $task_id = $this->input->post('task_id');
            $flag = $this->input->post('flag');
            if($flag=='editableDescLi')
            {
                $data = array('task_desc'=>$content);

            }else if($flag=='editableTitleLi'){

                $data = array('task_title'=>$content);
            }
            $updateTaskTime = $this->MasterModel->_update('employee_schedule_task', $data, array('id' => $task_id));

            if ($updateTaskTime->status) {
                $response['status'] = 200;
                $response['body'] = "Updated Successfully";
            } else {
                $response['status'] = 201;
                $response['body'] = "Something Went Wrong";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    function get_board_list()
    {
        $session_data = $this->session->userdata('login_session');
        $employee_id=$this->input->post('employee_id');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $emp_id = ($session_data['emp_id']);
            if($employee_id!="" || $employee_id!="Search by Employee")
            {
                $payData=$this->db->query('select email from user_header_all where user_id="'.$employee_id.'" group by email');
                if($this->db->affected_rows()>0)
                {
                    $email_id=$payData->row()->email;
                }
                $emp_id=$employee_id;
            }

        }
        // $rmtUserdata = $this->MasterModel->_select('rmt.user_header_all',array('email'=>$email_id),'user_id',true);
        $rmtUserdata = $this->db2->query('select user_id from user_header_all where email="'.$email_id.'" limit 1');

        if($this->db2->affected_rows()>0)
        {
            $user_id = $rmtUserdata->row()->user_id;
            // $boards = $this->MasterModel->_rawQuery("select * from rmt.board_master_data  where status='1' and  board_id in (select board_id from rmt.board_employee_mapping where recent='1' and user_id='$user_id')order by id desc");
            $boards = $this->db2->query("select * from board_master_data  where status='1' and  board_id in (select board_id from board_employee_mapping where recent='1' and user_id='".$user_id."')order by id desc");

            if ($this->db2->affected_rows()>0) {
                $response['board_data'] = $boards->result();
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 201;
                $response['status'] = false;
            }
        } else {

            $response['message'] = 'Something Went wrong';
            $response['code'] = 202;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function get_item_list()
    {
        if($this->input->post('board_id')!==''){
            $board_id = $this->input->post('board_id');
            $item_list = $this->db2->query("select * from card_item_master_data  where status='1' and  board_id='$board_id' order by id desc");
            if($this->db2->affected_rows()>0)
            {
                $response['item_data'] = $item_list->result();
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            }else{

                $response['message'] = 'No data to display';
                $response['code'] = 201;
                $response['status'] = false;
            }
        }else{
            $response['message'] = 'Required Data missing';
            $response['code'] = 201;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function get_employeeTaskList()
    {
        $session_data = $this->session->userdata('login_session');
        $employee_id=$this->input->post('employee_id');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            if($employee_id=="" || $employee_id=="Search by Employee")
            {
                 $email_id = ($session_data['user_id']);
                 $emp_id = ($session_data['emp_id']);
            }
            else
            {
                $payData=$this->db->query('select email from user_header_all where user_id="'.$employee_id.'" group by email');
                if($this->db->affected_rows()>0)
                {
                    $email_id=$payData->row()->email;
                }
                else
                {
                    $email_id='';
                }
                $emp_id = $employee_id;
            }

            $rmtUserdata = $this->db2->query("select group_concat(user_id) as user_id from user_header_all where email='".$email_id."' group by email");
            if($this->db2->affected_rows() >0)
            {
                $user_ids = $rmtUserdata->row()->user_id;
                /*----Old Code----*/

               /* $empTaskData = $this->db2->query("select *,(select cm.name from card_item_master_data cm where cm.board_id=tp.board_id and cm.item_id = tp.item_id) as item_name,
(select role_name from board_role_master where id=tp.role_id) as role_name,
(select concat(sum(work_status),\"||\",sum(status)) from Payroll.employee_schedule_task where planned_work_id=tp.id group by planned_work_id) as timestatus
 from timesheet_planning_table tp where  find_in_set(employee_id, '".$user_ids."') and status=1");*/


                $empTaskData = $this->db2->query("select *,
 (case when tp.timesheet_task_type=0 then (select cm.name from card_item_master_data cm where cm.board_id=tp.board_id and cm.item_id = tp.item_id) else tp.description end) as item_name,
 (case when tp.timesheet_task_type=0 then (select role_name from board_role_master where id=tp.role_id) else tp.employee_role end) as role_name,
(select concat(sum(work_status),\"||\",sum(status)) from Payroll.employee_schedule_task where planned_work_id=tp.id group by planned_work_id) as timestatus
 from timesheet_planning_table tp where  find_in_set(employee_id, '".$user_ids."') and status=1");

//                print_r($this->db2->last_query());die;

                if($this->db2->affected_rows() >0) {
                    $data = $empTaskData->result();
                    $html = '';
                    foreach($data as $val)
                    {
                        $workedStatus=0;
                        if($val->timestatus!=null)
                        {
                            $timestatus=explode('||',$val->timestatus);

                            if($timestatus[0]==($timestatus[1]*2))
                            {
                                $workedStatus=1;
                            }
                        }
                        $style='background-color: #ffffff';
                        if($workedStatus==1)
                        {
                            $style='background-color: #caefc8';
                        }
                        $html .= ' <div class="car work_task" id="work_task_'.$val->id.'" work_task_id="'.$val->id.'" draggable="true" ondragstart="drag(event)" style="'.$style.'">
                                    <input type="hidden" name="work_task_id" id="work_task_id" value="'.$val->id.'">
                                      <ul>
                                         <li class="car-top"><span class="badge small">'.$val->role_name.'</span> <i>'.$val->planned_hour.'H</i></li>
                                         <li class="car-bottom" style="margin-top: 2px;"><h5>'.$val->item_name.'</h5></li>
                                     </ul>
                                   </div>';
                    }
                    $response['status']=200;
                    $response['body']=$html;

                }else{
                    $response['status']=201;
                    $response['body']='No Data Found';
                }
            }else{
                $response['status']=201;
                $response['body']='No User Data Found';
            }
        }else{
            $response['status']=201;
            $response['body']='Somthing went wrong';
        }
        echo json_encode($response);
    }

    public function add_drop_planned_work()
    {
        if($this->input->post('work_date')!=='' && $this->input->post('work_time')!=='' && $this->input->post('work_task_id')!=='')
        {
            $date = $this->input->post('work_date');
            $date1 = date('Y-m-d',strtotime($date));
            $month = date('m',strtotime($date));
            $year = date('Y',strtotime($date));
            $from_time = $this->input->post('work_time');
            $work_task_id = $this->input->post('work_task_id');
            $session_data = $this->session->userdata('login_session');
            $data['session_data'] = $session_data;
            $emp_id = ($session_data['emp_id']);
            $employee_id = $this->input->post('employee_id');
            if($employee_id!="" && $employee_id!="Search by Employee")
            {
                $emp_id=$employee_id;
            }

            /*-- Old Code --*/
//            $queryData = $this->db2->query('select *,(select cm.name from card_item_master_data cm where cm.board_id=tp.board_id and cm.item_id = tp.item_id) as item_name,(select role_name from board_role_master where id=tp.role_id) as role_name from timesheet_planning_table tp where tp.id="'.$work_task_id.'" limit 1')->row();

            $queryData = $this->db2->query('select *,
 (case when tp.timesheet_task_type=0 then (select cm.name from card_item_master_data cm where cm.board_id=tp.board_id and cm.item_id = tp.item_id) else tp.description end) as item_name,
 (case when tp.timesheet_task_type=0 then (select role_name from board_role_master where id=tp.role_id) else tp.employee_role end) as role_name from timesheet_planning_table tp where tp.id="'.$work_task_id.'" limit 1')->row();


            if(!empty($queryData))
            {
                //--Calculate End time, width, total hour
                /*$total_hour = $queryData->planned_hour;
                $datetime = date('Y-m-d', strtotime($date)).' '.$from_time;
                $plus = 60 * 60 * $total_hour;
                $to_time = strtotime($datetime) + $plus;
                $end_time = date('H:i:s', $to_time);*/

                //---Calculate Width
                /*$total_min= $total_hour * 60;
                $total_box= $total_min/15; //each box contain 15 min
                $width = $total_box * 101;*/

                //--Calculate total hour
                /*$hours = str_pad(floor($total_min /60),2,"0",STR_PAD_LEFT);
                $mins  = str_pad($total_min %60,2,"0",STR_PAD_LEFT);
                $total_hr = $hours.':'.$mins;*/

                $strt_time = strtotime($from_time);
                $end_time = date("h:i:s ", strtotime('+15 minutes', $strt_time));
                $width = 100;
                $total_hr = '00:15';

                $data = array('planned_work_id'=>$work_task_id,
                    'user_id'=>$emp_id,
                    'task_title'=>$queryData->role_name,
                    'task_desc'=>$queryData->item_name,
                    'date'=>$date1,
                    'from_time'=>$from_time,
                    'to_time'=>$end_time,
                    'total_hour'=>$total_hr,
                    'width'=>$width,
                    'role_id'=>$queryData->role_id,
                    'type'=>'planned_work',
                );

                $resultObject = $this->MasterModel->_insert('employee_schedule_task', $data);
                if ($resultObject->status) {
                    $response["status"] = 200;
                    $response["month"] = $month;
                    $response["year"] = $year; // double quotes
                    $response["width"] = $width;
                    $response["body"] = "create successfully";
                } else {
                    $response["status"] = 201;
                    $response["body"] = "Failed";
                }

            }else {
                $response["status"] = 201;
                $response["body"] = "Something Went wrong";
            }

        }else{
            $response["status"] = 201;
            $response["body"] = "Something Went wrong";
        }
        echo json_encode($response);
    }

    public function add_drop_schedule_work()
    {
        if($this->input->post('work_date')!=='' && $this->input->post('work_time')!=='')
        {
            $date = $this->input->post('work_date');
            $date1 = date('Y-m-d',strtotime($date));

            $month = date('m',strtotime($date));
            $year = date('Y',strtotime($date));
            $from_time = $this->input->post('work_time');
            if($this->input->post('schedule_task_id')!==0 || $this->input->post('schedule_task_id')!=='')
            {
                $schedule_task_id = $this->input->post('schedule_task_id');
            }else{
                $schedule_task_id = 0;
            }
            $session_data = $this->session->userdata('login_session');
            $data['session_data'] = $session_data;
            $emp_id = ($session_data['emp_id']);
            $queryData = $this->db->query('select * from employee_schedule_task where id="'.$schedule_task_id.'"  limit 1')->row();
            if(!empty($queryData))
            {
                /*-------Calculate End Time ---------*/
                $day = date('d',strtotime($queryData->date));
                $date2 = $date1.' '. $from_time;
                $hours = $queryData->total_hour;
                $d0 = strtotime(date('Y-m-d 00:00:00'));
                $d1 = strtotime($date1.$hours);
                $sumTime = strtotime($date2) + ($d1 - $d0);
                $to_time = date("H:i:s", $sumTime);

                $task_times1 = $this->MasterModel->_rawQuery('select * from employee_schedule_task where date="'.$date1.'" and user_id ="'.$emp_id.'" and id!="'.$schedule_task_id.'"  order by to_time DESC');

                if($task_times1->totalCount>0)
                {
                    foreach($task_times1->data as $val) {

                        $dragFrom_time = new DateTime($from_time);
                        $dragTo_time = new DateTime($to_time);
                        $schStartTime = new DateTime($val->from_time);
                        $schEndTime = new DateTime($val->to_time);

                        if(($dragFrom_time <= $schStartTime && $dragFrom_time > $schEndTime) || ($dragTo_time >$schStartTime && $dragTo_time <= $schEndTime)
                                || ($dragFrom_time <= $schStartTime && $dragTo_time > $schEndTime))
                        {
                            $response['status'] = 201;
                            $response['body'] = 'Task can not Drag n drop';
                            echo json_encode($response);
                            exit;

                        }else if($dragFrom_time > $schStartTime && $dragFrom_time < $schEndTime)
                        {
                            $response['status'] = 201;
                            $response['body'] = 'Task can not Drag n drop';
                            echo json_encode($response);
                            exit;
                        }
                            /*else if($dragTo_time >$schStartTime && $dragTo_time <= $schEndTime){
                             $response['status'] = 201;
                             $response['body'] = 'Task can not Drag n drop';
                             echo json_encode($response);
                             exit;
                         }else if($dragFrom_time <= $schStartTime && $dragTo_time > $schEndTime)
                         {
                             $response['status'] = 201;
                             $response['body'] = 'Task can not Drag n drop';
                             echo json_encode($response);
                             exit;
                         }*/

                    }//die;

                    $data = array(
                        'date'=>$date1,
                        'from_time'=>$from_time,
                        'to_time'=>$to_time,
                    );

                    $resultObject = $this->MasterModel->_update('employee_schedule_task', $data,array('id'=>$schedule_task_id));
                    if ($resultObject->status) {
                        $response["status"] = 200;
                        $response["month"] = $month;
                        $response["year"] = $year;
                        $response["day"] = $day;
                        $response["body"] = "Drag drop successfully";
                    } else {
                        $response["status"] = 201;
                        $response["body"] = "Failed";
                    }

                }else{
                    /*----Empty row drag n drop fun*/
                    $queryData = $this->db->query('select * from employee_schedule_task where id="'.$schedule_task_id.'"  limit 1')->row();
                    if(!empty($queryData))
                    {
                        /*-------Calculate End Time ---------*/
                        $day = date('d',strtotime($queryData->date));
                        $date2 = $date1.' '. $from_time;
                        $hours = $queryData->total_hour;
                        $d0 = strtotime(date('Y-m-d 00:00:00'));
                        $d1 = strtotime($date1.$hours);
                        $sumTime = strtotime($date2) + ($d1 - $d0);
                        $to_time = date("H:i:s", $sumTime);

                        $data = array(
                            'date'=>$date1,
                            'from_time'=>$from_time,
                            'to_time'=>$to_time,
                        );

                        $resultObject = $this->MasterModel->_update('employee_schedule_task', $data,array('id'=>$schedule_task_id));
                        if ($resultObject->status) {
                            $response["status"] = 200;
                            $response["month"] = $month;
                            $response["year"] = $year;
                            $response["day"] = $day;
                            $response["body"] = "Drag drop successfully";
                        } else {
                            $response["status"] = 201;
                            $response["body"] = "Failed";
                        }

                    }else {
                        $response["status"] = 201;
                        $response["body"] = "Something Went wrong. Please Try again";
                    }
                    /*----Empty row drag n drop fun*/
                }



                //$task_times = $this->MasterModel->_rawQuery('select * from employee_schedule_task where date="'.$date1.'" and user_id ="'.$emp_id.'" order by to_time DESC');
//             $task_times = $this->MasterModel->_rawQuery('select * from employee_schedule_task where date="'.$date1.'" and user_id ="'.$emp_id.'" and (((from_time <= "'.$from_time.'" AND from_time > "'.$to_time.'") || (to_time >"'.$from_time.'" AND to_time <= "'.$to_time.'")) || (from_time <= "'.$from_time.'" AND to_time > "'.$to_time.'")) order by to_time DESC');
//
//                if($task_times->totalCount>0)
//                {
//                    $response['status'] = 201;
//                    $response['body'] = 'Task can not Drag n drop';
//                    echo json_encode($response);
//                    exit;
//
//                    /*--------- OR Code----------*/
////                    foreach($task_times->data as $val)
////                    {
////                        $fromTime1 = new DateTime($from_time);
////                        $to_time1 = new DateTime($to_time);
////                        $startTime = new DateTime($val->from_time);
////                        $endTime = new DateTime($val->to_time);
////
////                       if ($fromTime1 < $startTime && $to_time1 > $endTime) {
////                            $response['status'] = 201;
////                            $response['body'] = 'Task can not Drag n drop';
////                            echo json_encode($response);
////                            exit;
////                        }else if($fromTime1 >= $startTime && $to_time1 <= $endTime){
////                            $response['status'] = 201;
////                            $response['body'] = 'Task can not Drag n drop';
////                            echo json_encode($response);
////                            exit;
////                        }
////                    }
////
////                   $data = array(
////                          'date'=>$date1,
////                          'from_time'=>$from_time,
////                          'to_time'=>$to_time,
////                       );
////
////                    $resultObject = $this->MasterModel->_update('employee_schedule_task', $data,array('id'=>$schedule_task_id));
////                      if ($resultObject->status) {
////                          $response["status"] = 200;
////                          $response["month"] = $month;
////                          $response["year"] = $year;
////                          $response["day"] = $day;
////                          $response["body"] = "Drag drop successfully";
////                      } else {
////                          $response["status"] = 201;
////                          $response["body"] = "Failed";
////                      }
//                    /*---------END OR Code----------*/
//
//                }else {
//                   // ----Empty row drag n drop fun
//                    $queryData = $this->db->query('select * from employee_schedule_task where id="'.$schedule_task_id.'"  limit 1')->row();
//                    if(!empty($queryData))
//                    {
//                        /*-------Calculate End Time ---------*/
//                        $day = date('d',strtotime($queryData->date));
//                        $date2 = $date1.' '. $from_time;
//                        $hours = $queryData->total_hour;
//                        $d0 = strtotime(date('Y-m-d 00:00:00'));
//                        $d1 = strtotime($date1.$hours);
//                        $sumTime = strtotime($date2) + ($d1 - $d0);
//                        $to_time = date("H:i:s", $sumTime);
//
//                        $data = array(
//                            'date'=>$date1,
//                            'from_time'=>$from_time,
//                            'to_time'=>$to_time,
//                        );
//
//                        $resultObject = $this->MasterModel->_update('employee_schedule_task', $data,array('id'=>$schedule_task_id));
//                        if ($resultObject->status) {
//                            $response["status"] = 200;
//                            $response["month"] = $month;
//                            $response["year"] = $year;
//                            $response["day"] = $day;
//                            $response["body"] = "Drag drop successfully";
//                        } else {
//                            $response["status"] = 201;
//                            $response["body"] = "Failed";
//                        }
//
//                    }else {
//                        $response["status"] = 201;
//                        $response["body"] = "Something Went wrong. Please Try again";
//                    }
//                 // --Empty row drag n drop fun
//                }
            }else{
                $response["status"] = 201;
                $response["body"] = "Something Went wrong";
            }
        }else{
            $response["status"] = 201;
            $response["body"] = "Something Went wrong";
        }
        echo json_encode($response);
    }
    public function update_work_status()
    {
        if($this->input->post()!=='' && $this->input->post()!=='')
        {
            $task_id = $this->input->post('task_id');
            $status = $this->input->post('status');
            $data = array('work_status'=>$status);
            $resultObject = $this->MasterModel->_update('employee_schedule_task',$data,array('id'=>$task_id));
            if ($resultObject->status) {
                $response["status"] = 200;
                $response["body"] = "status change successfully";
            } else {
                $response["status"] = 201;
                $response["body"] = "Failed";
            }
        }else{
            $response["status"] = 201;
            $response["body"] = "Somthing went wrong";
        }
        echo json_encode($response);
    }
    public function getScheduleTaskData()
    {
        if($this->input->post('id')!=='' && !is_null($this->input->post('id')))
        {
            $task_id = $this->input->post('id');
            $resultObject = $this->MasterModel->_select('employee_schedule_task',array('id'=>$task_id),'*');
            // print_r($resultObject);exit();
            if ($resultObject->totalCount>0) {
                $response["status"] = 200;
                $response["body"] = $resultObject->data;
            } else {
                $response["status"] = 201;
                $response["body"] = "Failed";
            }
        }else{
            $response["status"] = 201;
            $response["body"] = "Required parameter missing";
        }
        echo json_encode($response);
    }
    public function getFirmemployees()
    {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['emp_id']);
            $user_type = ($session_data['user_type']);
        }
        $result = $this->customer_model->get_firm_id($user_id);
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        $users=array();
        if ($user_type == 4) {
            $all_active_users=$this->MasterModel->_rawQuery('select user_name,user_id,(select group_concat(e.work_status,"@",e.date) from employee_schedule_task e where e.user_id=u.user_id and e.status=1) as work_status from user_header_all u where user_id="'.$user_id.'" or senior_user_id="'.$user_id.'" and activity_status =1');

        } else {
            $all_active_users=$this->MasterModel->_rawQuery('select user_name,user_id,(select group_concat(e.work_status,"@",e.date) from employee_schedule_task e where e.user_id=u.user_id and e.status=1) as work_status from user_header_all u where firm_id="'.$firm_id.'" and user_type in (4) and activity_status =1');
        }
       // $all_active_users=all_active_employee($firm_id);

         // print_r($all_active_users);exit();
         if($all_active_users->totalCount>0)
         {
            foreach($all_active_users->data as $row)
            {
                if($row->work_status!="" && $row->work_status!=null)
                {
                    $allTask=explode(',',$row->work_status);
                    $dateArr=array();

                    foreach($allTask as $trow)
                    {
                        $taskData=explode('@',$trow);
                        $dateArr[date('d-M-Y',strtotime($taskData[1]))][]=$taskData[0];
                    }

                    $row->totalTask=$dateArr;
                    // $pending=1;
                    // $row->pendingTask=count(array_filter($totalTask, function($value) use ($pending) {
                    //     return $value == $pending;
                    // }));
                    // $complete=2;
                    // $row->completeTask=count(array_filter($totalTask, function($value) use ($complete) {
                    //     return $value == $complete;
                    // }));
                }
                else
                {
                    $row->totalTask='';

                }
                array_push($users,$row);
            }
         }
         // print_r($users);exit();
       $response['status']=200;
       $response['result']=$users;
       echo json_encode($response);
    }

    /*--Code by Pratiksha Bckup----*/

public function getProjectList()
{
    $session_data = $this->session->userdata('login_session');

    $emp_id = $session_data['emp_id'];
    $email_id = $session_data['user_id'];

    // Fetch senior user's email
    $get_user_login = $this->db->query("
        SELECT email FROM user_header_all
        WHERE user_id = (
            SELECT senior_user_id FROM user_header_all
            WHERE user_id = ?
            LIMIT 1
        )
        LIMIT 1", [$emp_id])->row_array();

    $senior_user_email = !empty($get_user_login['email']) ? $get_user_login['email'] : null;

    if ($senior_user_email) {
        // Fetch user_id of senior user
        $rmtUserlogindata = $this->db2
            ->select('user_id')
            ->where('email', $senior_user_email)
            ->limit(1)
            ->get('user_header_all')
            ->row_array();

        $user_id = !empty($rmtUserlogindata['user_id']) ? $rmtUserlogindata['user_id'] : null;
        // $user_id =0;
    } else {
        $user_id = null;
    }

    // Default option
    $option = "<option value=''>Select Project</option>";

    if ($user_id) {
        // Fetch project list
        $getProjectsList = $this->db2->query("
            SELECT p.task_name, p.node_id
            FROM personal_task_all p
            WHERE (p.type_of_node = 3 AND p.activity_status = 1 AND p.current_status IN (1,2,4) AND p.created_by = ?)
            OR (p.node_id IN (
                SELECT r.node_id
                FROM personal_task_reference_table r
                WHERE r.user_id = ?
                AND r.node_id IN (
                    SELECT node_id
                    FROM personal_task_all
                    WHERE type_of_node = 3 AND activity_status = 1 AND current_status IN (1,2,4)
                )
            ))
            ORDER BY p.date_completion DESC", [$user_id, $user_id]);

        if ($getProjectsList->num_rows() > 0) {
            foreach ($getProjectsList->result() as $row) {
                $selected = ($row->node_id == '5496') ? 'selected' : '';
                $option .= "<option {$selected} value='{$row->node_id}-{$row->task_name}'>{$row->task_name}</option>";
            }
        }

    } else {

        $option .= "<option value='0'>Other Project</option>";
    }

    // Send response
    $response = [
        'status' => 200,
        'result' => $option
    ];

    echo json_encode($response);
}
 /*--End Code by Pratiksha Bckup----*/


    public function get_schedule_task_list()
    {
        if($this->input->post('user_id')!=='' && !is_null($this->input->post('user_id')))
        {
            $user_id = $this->input->post('user_id');
            $date = $this->input->post('date');
            $status = $this->input->post('status');
            $date=date('Y-m-d', strtotime($date));
            $where=array('user_id'=>$user_id,'date'=>$date,'status'=>1);
            if($status!=0 && $status!="")
            {
                $where['work_status']=$status;
            }
            $resultObject = $this->MasterModel->_select('employee_schedule_task',$where,'*',false);
            // print_r($resultObject);exit();
            if ($resultObject->totalCount>0) {
                $response["status"] = 200;
                $response["body"] = $resultObject->data;
            } else {
                $response["status"] = 201;
                $response["body"] = "Failed";
            }
        }else{
            $response["status"] = 201;
            $response["body"] = "Required parameter missing";
        }
        echo json_encode($response);
    }

    /*---New Code By shweta--*/
    public function myTaskListOption() {
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $emp_id = $session_data['emp_id'];
        // Get the email from user_header_all using emp_id

        $get_user_login = $this->db->query("
            SELECT email FROM user_header_all
            WHERE user_id = '$emp_id'")->row_array();

        // Check if the email is found
        if (isset($get_user_login['email'])) {
            $email = $get_user_login['email'];

            // Use the email in the query to get user_id
            $get_user_id = $this->db2->query("
                SELECT user_id FROM user_header_all
                WHERE email = '$email'")->result();

            // Assuming you need to extract user_id from $get_user_id result
            $user_id = $get_user_id[0]->user_id;  // Adjust based on your result structure
            // print_r($get_user_id);die;
        } else {
            $response['status'] = 201;
            $response['body'] = 'Email not found for the user';
            echo json_encode($response);
            return;
        }

        $queryData = $this->db2->query("
            SELECT p.task_name, p.node_id
            FROM personal_task_all p
            WHERE (p.type_of_node = 5 AND p.activity_status = 1 AND p.current_status IN (1,2,4) AND p.created_by = ?)
            OR (p.node_id IN (
                SELECT r.node_id
                FROM personal_task_reference_table r
                WHERE r.user_id = ?
                AND r.node_id IN (
                    SELECT node_id
                    FROM personal_task_all
                    WHERE type_of_node = 5 AND activity_status = 1 AND current_status IN (1,2,4)
                )
            ))
            ORDER BY p.date_completion DESC", [$user_id, $user_id]);

            // if ($getProjectsList->num_rows() > 0) {
            //     foreach ($getProjectsList->result() as $row) {
            //         $selected = ($row->node_id == '5496') ? 'selected' : '';
            //         $option .= "<option {$selected} value='{$row->node_id}-{$row->task_name}'>{$row->task_name}</option>";
            //     }
            // }

        if ($this->db2->affected_rows() > 0) {
            $data = $queryData->result();
            $option = '<option>Select Option</option>';
            foreach ($data as $val) {
                $option .= '<option value="'.$val->node_id.'">'.$val->task_name.'</option>';
            }
            $response['status'] = 200;
            $response['data'] = $option;
        } else {
            $response['status'] = 201;
            $response['body'] = 'No Data Found';
        }
        echo json_encode($response);
    }


    public function getProjectsByTask()
    {
        $task_id = $this->input->post('task_id');
        $option = "<option value=''>Select Project</option>";
        if($task_id!='' && !is_null($task_id))
        {
            $query = $this->MasterModel->_rawQuery("select * from rmt.personal_task_all p where p.node_id=(select parent_node_id from rmt.personal_task_all where node_id=$task_id and activity_status=1)
            and p.activity_status=1 and p.type_of_node=3 ORDER BY p.date_completion DESC");

             // $query = $this->MasterModel->_rawQuery("select * from rmt.personal_task_all p where p.node_id=(select parent_node_id from rmt.personal_task_all where node_id=$task_id and activity_status=1)
             // ORDER BY p.date_completion DESC");
       
            if($query->totalCount>0)
            {

                $task_name = $query->data[0]->task_name;
                $node_id = $query->data[0]->node_id;

                $selected ='selected';
                $option .= "<option {$selected} value='{$node_id}-{$task_name}'>{$task_name}</option>";

            }else{
                $option .= "<option value='0'>Other Project</option>";
            }
        }
        $response = [
            'status' => 200,
            'result' => $option
        ];

        echo json_encode($response);
    }

      /*---New Code By shweta--*/

}
