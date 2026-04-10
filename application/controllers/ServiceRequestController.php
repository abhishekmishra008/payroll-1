<?php



class ServiceRequestController extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('Globalmodel');
        $this->load->model('MasterModel');
        $this->load->helper('dump_helper');
        $this->load->helper('eloqunt_helper');
    }

    public function index() {
        $data['prev_title'] = "Service Request";
        $data['page_title'] = "Service Request";
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);

        $this->load->view('human_resource/Service_request', $data);
    }

    public function MonthlyReport() {
        $data['prev_title'] = "Service Request";
        $data['page_title'] = "Service Request";
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);

        $this->load->view('human_resource/Monthly_reportgeneration', $data);
    }

    public function AddMissingPunch() {
        // dd("Abhishek mishra add missing punch");
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $shortaddress = $this->input->post_get('srtaddress1');
        $longtaddress = $this->input->post_get('longaddress1');
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        $punch_in_time = $this->input->post('punch_in_time');
        $punch_out_time = $this->input->post('punch_out_time');
        $reason_missing = $this->input->post('reason_missing');
        $date_selected = $this->input->post('date_selected');
        $get_hr = $this->db->query("select user_id from user_header_all where hr_authority='$firm_id'");
        // $get_hr = $this->db->query("select * from user_header_all where hr_authority='$firm_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $get_hr->row();
            $hr_user_id = $res->user_id;
            // $res = $get_hr->result();
            // $d = pluck($res,"user_id");
        } else {
            $hr_user_id = '';
        }

        $data = array(
            'user_id' => $user_id,
            'missing_punchin' => $punch_in_time,
            'missing_punchout' => $punch_out_time,
            'date' => $date_selected,
            'reason' => $reason_missing,
            'punch_regularised_status' => 1,
            'firm_id' => $firm_id,
            'activity_status' => 0,
            'shortinaddress' => $shortaddress,
            'longinaddress' => $longtaddress,
            'shortoutaddress' => $shortaddress,
            'longoutaddress' => $longtaddress
        );
        
        $check_alreday_login = $this->db->query("select date from employee_attendance_leave where date ='$date_selected' and user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $data_update = array(
                'missing_punchin' => $punch_in_time,
                'missing_punchout' => $punch_out_time,
                'punch_regularised_status' => 1,
                'activity_status' => 0,
                'shortoutaddress' => $shortaddress,
                'longoutaddress' => $longtaddress,
                'reason' => $reason_missing

            );
            $update = $this->db->update('employee_attendance_leave', $data_update, array('user_id' => $user_id, 'date' => $date_selected));
            if ($update !== FALSE) {
                // dd("update record");
                $response['message'] = 'success';
                $response['body'] = 'Request added successfully.';
            } else {
                // dd("not update record, because getting some error");
                $response['message'] = 'fail';
                $response['body'] = 'Something went wrong';
            }
        } else {
            $insert = $this->db->insert('employee_attendance_leave', $data);
            if ($insert !== FALSE) {
                $response['message'] = 'success';
                $response['body'] = 'Request added successfully.';
            } else {
                $response['message'] = 'fail';
                $response['body'] = 'Something went wrong';
            }
        }
        echo json_encode($response);
    }

    public function get_missing_punch_data()
    {
        $session_data = $this->session->userdata('login_session');

        $user_id = ($session_data['emp_id']);
        if (!is_null($this->input->post_get('emp_id'))) {
            $emp_id = $this->input->post_get('emp_id');
            $date = $this->input->post_get('date');
            $date = explode('-', $date);
            $get_data = $this->db->query("select distinct missing_punchin,missing_punchout,reason,activity_status,id,regular_status,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$emp_id' AND missing_punchin !=0 AND missing_punchout != 0 AND month(missing_punchin)='$date[1]' AND year(missing_punchin)='$date[0]' AND regular_status=0");

        }
//		else {
//			 $get_data = $this->db->query("select distinct missing_punchin,missing_punchout,reason,activity_status,id,regular_status,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$user_id' AND missing_punchin !=0 AND missing_punchout != 0 AND month(missing_punchin)='$date[1]' AND year(missing_punchin)='$date[0]' AND regular_status=0");
//		}


        $data = '';
        if ($this->db->affected_rows() > 0) {
            $res = $get_data->result();

            foreach ($res as $row) {
                if ($row->regular_status == 0) {
                    $status = '<span class="badge badge-info" >Requested </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $row->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $row->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                } else if ($row->regular_status == 1) {
                    $status = '<span class="badge badge-primary" >Approved </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                } else {
                    $status = '<span class="badge badge-danger" >Denied </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                }
                $data .= '<tr>
                    <td>' . $row->missing_punchin . '</td>
                    <td>' . $row->missing_punchout . '</td>
                    <td>' . $row->reason . '</td>
                    <td>' . $status . '</td>
					<td>' . $action . '</td>
					<td class="comment more" style="width: 300px;">' . $row->shortinaddress . '</td>
                    <td class="comment more" style="width: 300px;">' . $row->shortoutaddress . '</td>

                       </tr>';
            }


            $response['message'] = 'success';
            $response['result'] = $data;
            $response['status'] = $status;
        } else {
            $response['message'] = 'fail';
            $response['result'] = $data;
            $response['status'] = '';
        }
        echo json_encode($response);
    }


    function get_na_data()
    {

        $session_data = $this->session->userdata('login_session');

        $user_id = ($session_data['emp_id']);

        $emp_id = $this->input->post_get('emp_id');
        $date = $this->input->post_get('date');
        $data = '';
        $current_date = date('Y-m-d');
        $current_date1 = date('d');
        $current_date1 = (int)$current_date1;
        for ($i = 1; $i <= 31; $i++) {


            if ($i < 10) {
                $date1 = $date . '-0' . $i;
            } else {
                $date1 = $date . '-' . $i;
            }
            //($current_date1.'+'.$i);
            if ($i <= $current_date1) {


                //	var_dump($date1);
                $get_data = $this->db->query("select id from employee_attendance_leave where user_id='$emp_id' and  (DATE(punch_in)='$date1' or DATE(missing_punchin)='$date1') and (DATE(punch_in)<='$current_date' or DATE(missing_punchin)<='$current_date')")->row();

                if ($get_data == null) {
                    $leave_data = $this->db->query("select id from leave_transaction_all where user_id='$emp_id' and  DATE(leave_date)='$date1'  and DATE(leave_date)<='$current_date' ")->row();
                    if ($leave_data == null) {
                        $data .= '<tr>
                    <td>' . date_format(date_create($date1), "d-M-Y") . '</td>
                  

                       </tr>';
                    }
                }

            }
        }

        // echo $this->db->last_query();


        $response['message'] = 'success';
        $response['result'] = $data;


        echo json_encode($response);
    }

    function convertdate($date){
        $date = strtotime($date);
        $d = date('g:i', $date);
        $d1 = date('d', $date);
        $d = $d;
        return $d;
    }

    function get_full_att_data()
    {


        $session_data = $this->session->userdata('login_session');

        $user_id = ($session_data['emp_id']);

        $emp_id = $this->input->post_get('emp_id');
        $date = $this->input->post_get('date');
        $date = $this->input->post_get('date');
        $date = explode('-', $date);

        $get_data = $this->db->query("select distinct punch_in,punch_out,reason,activity_status,id,regular_status,punchin_lat,punchin_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$emp_id' AND missing_punchin =0 AND missing_punchout = 0 AND month(punch_in)='$date[1]' AND year(punch_in)='$date[0]'")->result();

        $get_out_data = $this->db->query("select distinct punch_in,punch_out,reason,activity_status,id,regular_status,punchin_lat,punchin_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$emp_id' AND missing_punchin =0 AND missing_punchout = 0 AND month(punch_in)='$date[1]' AND year(punch_in)='$date[0]' AND regular_status=0")->result();
        $missing_data = $this->db->query("select distinct missing_punchin,missing_punchout,reason,activity_status,id,regular_status,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$emp_id' AND missing_punchin !=0 AND missing_punchout != 0 AND month(missing_punchin)='$date[1]' AND year(missing_punchin)='$date[0]' AND regular_status=0")->result();
        $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
                                        `user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,
                                        `leave_transaction_all`.`leave_requested_on`, `leave_transaction_all`.`leave_date`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`approved_deny_by`='$user_id' AND `leave_transaction_all`.`user_id`='$emp_id' AND month(leave_requested_on)='$date[1]' AND year(leave_requested_on)='$date[0]' ORDER BY leave_date DESC")->result();
        //echo $this->db->last_query();
        $full_att = array();
        $data = '';
        $current_date = date('Y-m-d');
        if ($get_data != null) {

            foreach ($get_data as $row) {
                if ($row->regular_status == 0) {
                    $status = '<span class="badge badge-info" >Requested </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $row->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $row->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                } else if ($row->regular_status == 1) {
                    $status = '<span class="badge badge-primary" >Approved </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                } else {
                    $status = '<span class="badge badge-danger" >Denied </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                }


                $cdata[] = array(
                    'Type' => 'AT',
                    'punch_in' => $this->convertdate($row->punch_in),
                    'punch_out' => $this->convertdate($row->punch_out),
                    'location' => $row->shortinaddress,
                    'regular_status' => '',
                );


            }
            array_push($full_att, $cdata);

        }
        if ($get_out_data != null) {

            foreach ($get_out_data as $row) {
                if ($row->regular_status == 0) {
                    $status = '<span class="badge badge-info" >Requested </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $row->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $row->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                } else if ($row->regular_status == 1) {
                    $status = '<span class="badge badge-primary" >Approved </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                } else {
                    $status = '<span class="badge badge-danger" >Denied </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                }
                if ($this->check_location($row->punchin_lat, $row->punchin_long) == 0) {


                    $outdata[] = array(
                        'Type' => 'OUT',
                        'punch_in' => $this->convertdate($row->punch_in),
                        'punch_out' => $this->convertdate($row->punch_out),
                        'location' => $row->shortinaddress,
                        'regular_status' => $action,
                    );


                }
            }
            array_push($full_att, $outdata);


        }
        if ($missing_data != null) {

            foreach ($missing_data as $row) {
                if ($row->regular_status == 0) {
                    $status = '<span class="badge badge-info" >Requested </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $row->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $row->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                } else if ($row->regular_status == 1) {
                    $status = '<span class="badge badge-primary" >Approved </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                } else {
                    $status = '<span class="badge badge-danger" >Denied </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                }


                $missingdata[] = array(
                    'Type' => 'RA',
                    'punch_in' => $this->convertdate($row->missing_punchin),
                    'punch_out' => $this->convertdate($row->missing_punchout),
                    'location' => $row->shortinaddress,
                    'regular_status' => $action,
                );


            }
            array_push($full_att, $missingdata);


        }

        if ($fetch_data != null) {

            foreach ($fetch_data as $row) {
                $btn = '<button id="sample_1_new" class="btn-info btn-simple btn blue-hoki btn-outline sbold uppercase popovers"
                    data-toggle="modal" data-target="#view_leave_details" data-container="body"
                    data-placement="left" data-trigger="hover" data-content="See Details" data-original-title="" title=""
                     data-desig_id="' . $row->designation_id . '"
                    data-leave_type1="' . $row->leave_type . '"
                    data-emp_user_id="' . $row->user_id . '"
                    data-leave_id="' . $row->leave_id . '">
                    <i class="fa fa-eye"></i>
                    </button>';

                $leavdata[] = array(
                    'Type' => 'LR',
                    'punch_in' => $this->convertdate($row->leave_date),
                    'punch_out' => '',
                    'location' => '',
                    'regular_status' => $btn,
                );


            }
            array_push($full_att, $leavdata);

        }


        $main_array = array();
        if ($full_att != null) {
            foreach ($full_att as $row1) {

                for ($k = 0; $k < count($row1); $k++) {
                    $main_array = array(
                        'Type' => $row1[$k]['Type'],
                        'punch_in' => $row1[$k]['punch_in'],
                        'punch_out' => $row1[$k]['punch_out'],
                        'location' => $row1[$k]['location'],
                        'regular_status' => $row1[$k]['regular_status'],
                    );
                    $response['result'][] = $main_array;

                    $data .= '<tr>
		  <td>' . $row1[$k]['Type'] . '</td>';
                    if ($row1[$k]['punch_out'] != null && $row1[$k]['punch_out'] != '') {
                        $data .= '<td>' . $this->convertdate($row1[$k]['punch_in']) . '</td>';
                    } else {
                        $data .= '<td>--</td>';
                    }
                    if ($row1[$k]['punch_out'] != null && $row1[$k]['punch_out'] != '') {
                        $data .= '<td>' . $this->convertdate($row1[$k]['punch_out']) . '</td>';
                    } else {

                        $data .= '<td>' . $this->convertdate($row1[$k]['punch_in']) . '</td>';
                    }
                    $data .= '<td > <p <p class="show-read-more">' . $row1[$k]['location'] . '</p></td>
                    <td>' . $row1[$k]['regular_status'] . '</td>

                       </tr>';


                    /*  $data .= '<tr>
        <td>' . $row1[$k]['Type']. '</td>';
                    if($row1[$k]['punch_out']!=null && $row1[$k]['punch_out']!=''){
                    $data .= '<td>' .  date_format(date_create($row1[$k]['punch_in']), "H:i a"). '</td>';
                  } else {
                      $data .= '<td>--</td>';
                  }
                  if($row1[$k]['punch_out']!=null && $row1[$k]['punch_out']!=''){
                   $data .= '<td>' . date_format(date_create($row1[$k]['punch_out']), "H:i a") . '</td>';
                  }   else {
                       $data .= '<td>' . date_format(date_create($row1[$k]['punch_out']), "d-M-Y") . '</td>';
                  }
                   $data .='<td class="comment more" style="width: 300px;">' . $row1[$k]['location'] . '</td>
                  <td>' .$row1[$k]['regular_status'] . '</td>

                     </tr>';*/
                }
            }
        }

        $response['message'] = 'success';

        // $response['status'] = $status;


        echo json_encode($response);
    }

    function date_compare($element1, $element2)
    {
        $datetime1 = strtotime($element1['datetime']);
        $datetime2 = strtotime($element2['datetime']);
        return $datetime1 - $datetime2;
    }

    public function get_current_month_att()
    {
        $session_data = $this->session->userdata('login_session');

        $user_id = ($session_data['emp_id']);
        if (!is_null($this->input->post_get('emp_id'))) {
            $emp_id = $this->input->post_get('emp_id');
            $date = $this->input->post_get('date');
            $date = explode('-', $date);
            $get_data = $this->db->query("select distinct punch_in,punch_out,reason,activity_status,id,regular_status,punchin_lat,punchin_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$emp_id' AND missing_punchin =0 AND missing_punchout = 0 AND month(punch_in)='$date[1]' AND year(punch_in)='$date[0]'");

        } else {
            $get_data = $this->db->query("select distinct punch_in,punch_out,reason,activity_status,id,regular_status,punchin_lat,punchin_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$user_id' AND missing_punchin =0 AND missing_punchout = 0 AND month(punch_in)='$date[1]' AND year(punch_in)='$date[0]'");
        }


        $data = '';
        if ($this->db->affected_rows() > 0) {
            $res = $get_data->result();

            foreach ($res as $row) {
                if ($row->regular_status == 0) {
                    $status = '<span class="badge badge-info" >Requested </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $row->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $row->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                } else if ($row->regular_status == 1) {
                    $status = '<span class="badge badge-primary" >Approved </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                } else {
                    $status = '<span class="badge badge-danger" >Denied </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                }


                $data .= '<tr>
                    <td>' . $row->punch_in . '</td>
                    <td>' . $row->punch_out . '</td>
                                     
					<td class="comment more" style="width: 300px;">' . $row->shortinaddress . '</td>
                    <td class="comment more" style="width: 300px;">' . $row->shortoutaddress . '</td>

                       </tr>';

            }


            $response['message'] = 'success';
            $response['result'] = $data;
            $response['status'] = $status;
        } else {
            $response['message'] = 'fail';
            $response['result'] = $data;
            $response['status'] = '';
        }
        echo json_encode($response);
    }

    function get_outside_app()
    {


        $emp_id = $this->input->post_get('emp_id');
        $date = $this->input->post_get('date');

        $get_data = $this->db->query("select outside_punch_applicable from user_header_all where user_id='$emp_id' ")->row();


        if ($get_data != null) {
            if ($get_data->outside_punch_applicable == 1) {
                $response['message'] = 'success';

                $response['status'] = true;
            } else {
                $response['message'] = 'fail';

                $response['status'] = false;

            }


        } else {

        }
        echo json_encode($response);

    }


    public function get_current_out_month_att()
    {
        $session_data = $this->session->userdata('login_session');

        $user_id = ($session_data['emp_id']);
        if (!is_null($this->input->post_get('emp_id'))) {
            $emp_id = $this->input->post_get('emp_id');
            $date = $this->input->post_get('date');
            $date = explode('-', $date);
            $get_data = $this->db->query("select distinct punch_in,punch_out,reason,activity_status,id,regular_status,punchin_lat,punchin_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$emp_id' AND missing_punchin =0 AND missing_punchout = 0 AND month(punch_in)='$date[1]' AND year(punch_in)='$date[0]' AND regular_status=0");

        } else {
            $get_data = $this->db->query("select distinct punch_in,punch_out,reason,activity_status,id,regular_status,punchin_lat,punchin_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress from employee_attendance_leave where user_id='$user_id' AND missing_punchin =0 AND missing_punchout = 0 AND month(punch_in)='$date[1]' AND year(punch_in)='$date[0]' AND regular_status=0");
        }


        $data = '';
        if ($this->db->affected_rows() > 0) {
            $res = $get_data->result();

            foreach ($res as $row) {
                if ($row->regular_status == 0) {
                    $status = '<span class="badge badge-info" >Requested </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $row->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $row->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                } else if ($row->regular_status == 1) {
                    $status = '<span class="badge badge-primary" >Approved </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                } else {
                    $status = '<span class="badge badge-danger" >Denied </span>';
                    $action = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                }
                if ($this->check_location($row->punchin_lat, $row->punchin_long) == 0) {
                    $data .= '<tr>
                    <td>' . $row->punch_in . '</td>
                    <td>' . $row->punch_out . '</td>
                                 <td>' . $action . '</td>    
					<td class="comment more" >' . $row->shortinaddress . '</td>
                    <td class="comment more" >' . $row->shortoutaddress . '</td>

                       </tr>';
                }
            }


            $response['message'] = 'success';
            $response['result'] = $data;
            $response['status'] = $status;
        } else {
            $response['message'] = 'fail';
            $response['result'] = $data;
            $response['status'] = '';
        }
        echo json_encode($response);
    }

    public function check_location($latitude_live, $longitude_live) {
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        // print_r($firm_id);die;
        //get branch location

        $result = $this->db->query("select * from firm_location where firm_id='$firm_id'")->row();

        if ($result != null) {

            $firm_lattitude = $result->lattitude;
            $firm_longitude = $result->logitude;
            $radius = $result->radius;

            $distance = $this->twopoints_on_earth($firm_lattitude, $firm_longitude, $latitude_live, $longitude_live);
            $distance_in_meter = $distance * 1609.344;
            if ($distance_in_meter <= $radius) { //Inside Punch
                return 1;
            } else { //Out Side Punch
                return 0;
                $response['message'] = 'outside';
            }
        } else {
            return 0;
        }

    }

    public function twopoints_on_earth($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $long1 = deg2rad((float)$longitudeFrom);
        $long2 = deg2rad((float)$longitudeTo);
        $lat1 = deg2rad((float)$latitudeFrom);
        $lat2 = deg2rad((float)$latitudeTo);

        //Haversine Formula
        $dlong = $long2 - $long1;
        $dlati = $lat2 - $lat1;

        $val = pow(sin($dlati / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong / 2), 2);

        $res = 2 * asin(sqrt($val));

        $radius = 3958.756;

        return ($res * $radius);
    }

    public function get_missing_punch_data1() {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $date = $this->input->post('date');
        $createDate = new DateTime($date);
        $strip = $createDate->format('Y-m-d');
        $get_data = $this->db->query("select status from t_missing_punching where user_id='$user_id' and DATE(punch_in_time)='$strip'");
        if ($this->db->affected_rows() > 0) {
            $res = $get_data->result();
            $status = '';
            foreach ($res as $row) {
                if ($row->status == 0) {
                    $status = '<span class="badge badge-info" >Requested </span>';
                } else if ($row->status == 1) {
                    $status = '<span class="badge badge-primary" >Approve </span>';
                } else {
                    $status = '<span class="badge badge-danger" >Denied </span>';
                }
            }
            $response['message'] = 'success';
            $response['status'] = $status;
        } else {
            $response['message'] = 'fail';
            $response['status'] = '';
        }
        echo json_encode($response);
    }

    public function get_missing_punch_data_hr() {
        // dd("abhishek : get_missing_punch_data_hr");
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }

        if ($user_type == 5) {
            $qr = $this->db->query("SELECT hr_authority from user_header_all where user_id='$user_id'");
            if ($this->db->affected_rows() > 0) {
                $res = $qr->row();
                $firm_id = $res->hr_authority;
            } else {
                $firm_id = '';
            }
        }

        if (!is_null($this->input->post_get('emp_id'))) {
            $emp_id = $this->input->post_get('emp_id');
            $get_regular_data = $this->db->query("SELECT punch_in,punch_out,regular_status,id,user_id,punchin_lat,
                punchin_long,punchout_lat,punchout_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress
		from employee_attendance_leave where punch_regularised_status = 1 AND regular_status = 0 
		AND punch_in !=0 AND punch_out != 0 AND user_id='$emp_id' order by punch_in desc");


            $get_data = $this->db->query("SELECT missing_punchin,missing_punchout,reason,activity_status,id,user_id,punchin_lat,"
                . "punchin_long,punchout_lat,punchout_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress"
                . " from employee_attendance_leave where activity_status=0 AND missing_punchin !=0 AND missing_punchout != 0 AND user_id='$emp_id' order by missing_punchin desc");
            
        } else {
            $get_regular_data = $this->db->query("SELECT punch_in,punch_out,regular_status,id,user_id,punchin_lat,
                punchin_long,punchout_lat,punchout_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress
                from employee_attendance_leave where punch_regularised_status = 1 AND regular_status = 0
                AND punch_in !=0 AND punch_out != 0 AND firm_id='$firm_id' order by punch_in desc");

            $get_data = $this->db->query("SELECT missing_punchin,missing_punchout,reason,activity_status,id,user_id,punchin_lat,"
                . "punchin_long,punchout_lat,punchout_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress"
                . " from employee_attendance_leave where activity_status=0 AND missing_punchin !=0 AND missing_punchout != 0 AND firm_id='$firm_id' order by missing_punchin desc");

            // print_r($this->db->last_query()); die;
        }

        $data = '';
        
        if (count($get_data->result()) > 0 || count($get_regular_data->result()) > 0) {
            $res = $get_data->result();
            $res2 = $get_regular_data->result();
            if (count($get_data->result()) > 0) {
                foreach ($res as $row) {
                    $punchin_lat = $row->punchin_lat;
                    $punchin_long = $row->punchin_long;
                    $punchout_lat = $row->punchout_lat;
                    $punchout_long = $row->punchout_long;
                    if ($punchin_lat != '' && $punchout_lat == '') {
                        $loc_sts = 'Off(punchout)';
                    } else if ($punchin_lat == '' && $punchout_lat != '') {
                        $loc_sts = 'Off(punchin)';
                    } elseif ($punchin_lat != '' && $punchout_lat != '') {
                        $loc_sts = 'ON';
                    } else {
                        $loc_sts = 'Off';
                    }
                    if ($row->activity_status == 0) {
                        $status = '<span class="badge badge-info" >Requested </span>';
                        $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="approve_m_rqst(\'' . $row->id . '\',\'' . $row->user_id . '\',\'' . $row->missing_punchin . '\')" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="deny_m_rqst(' . $row->id . ')" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    } else if ($row->activity_status == 3) {
                        $status = '<span class="badge badge-primary" >Approve </span>';
                        $action = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                    } else {
                        $status = '<span class="badge badge-danger" >Denied </span>';
                        $action = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    }
                    $get_u_name = $this->db->query("select user_name,senior_user_id from user_header_all where user_id='$row->user_id'");
                    if ($this->db->affected_rows() > 0) {
                        $result = $get_u_name->row();
                        $user_name = $result->user_name;
                        $senior_user_id = $result->senior_user_id;
                    } else {
                        $user_name = '';
                    }
                    if ($user_type == 5) {
                        $data .= '<tr>
					 <td>' . $action . '</td>
                    <td>' . $user_name . '</td>
                    <td>' . $row->missing_punchin . '</td>
                    <td>' . $row->missing_punchout . '</td>
                    <td>' . $row->reason . '</td>
                    <td>' . "Missing Punch" . '</td>
                    <td>' . $status . '</td>
                    <td>' . $loc_sts . '</td>
                   
					<td>' . $row->shortinaddress . $row->longinaddress . '</td>
                    <td>' . $row->shortoutaddress . $row->longoutaddress . '</td>

                       </tr>';
                    } else {
                        if ($senior_user_id == $user_id) {
                            $data .= '<tr>
							 <td>' . $action . '</td>
                    <td>' . $user_name . '</td>
                    <td>' . $row->missing_punchin . '</td>
                    <td>' . $row->missing_punchout . '</td>
                    <td>' . $row->reason . '</td>
                    <td>' . "Missing Punch" . '</td>
                    <td>' . $status . '</td>
                    <td>' . $loc_sts . '</td>
                    
					<td>' . $row->shortinaddress . $row->longinaddress . '</td>
                    <td>' . $row->shortoutaddress . $row->longoutaddress . '</td>

                       </tr>';
                        }
                    }
                }
            }
            if (count($get_regular_data->result()) > 0) {
                foreach ($res2 as $row) {
                    if ($row->regular_status == 0) {
                        $status = '<span class="badge badge-info" >Requested </span>';
                        $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $row->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $row->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    } else if ($row->regular_status == 1) {
                        $status = '<span class="badge badge-primary" >Approve </span>';
                        $action = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                    } else {
                        $status = '<span class="badge badge-danger" >Denied </span>';
                        $action = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    }
                    $get_u_name = $this->db->query("select user_name,senior_user_id from user_header_all where user_id='$row->user_id'");
                    if ($this->db->affected_rows() > 0) {
                        $result = $get_u_name->row();
                        $user_name = $result->user_name;
                        $senior_user_id = $result->senior_user_id;
                    } else {
                        $user_name = '';
                    }
                    $punchin_lat = $row->punchin_lat;
                    $punchin_long = $row->punchin_long;
                    $punchout_lat = $row->punchout_lat;
                    $punchout_long = $row->punchout_long;
                    if ($punchin_lat != '' && $punchout_lat == '') {
                        $loc_sts = 'Off(punchout)';
                    } else if ($punchin_lat == '' && $punchout_lat != '') {
                        $loc_sts = 'Off(punchin)';
                    } elseif ($punchin_lat != '' && $punchout_lat != '') {
                        $loc_sts = 'ON';
                    } else {
                        $loc_sts = 'Off';
                    }
                    if ($user_type == 5) {
                        $data .= '<tr>
						  <td>' . $action . '</td>
                    <td>' . $user_name . '</td>
                    <td>' . $row->punch_in . '</td>
                    <td>' . $row->punch_out . '</td>
                    <td>' . "" . '</td>
                    <td>' . "Outside Punch" . '</td>
                    <td>' . $status . '</td>
                    <td>' . $loc_sts . '</td>
                   
					<td>' . $row->shortinaddress . $row->longinaddress . '</td>
                    <td>' . $row->shortoutaddress . $row->longoutaddress . '</td>

                       </tr>';
                    } else {

                        if ($senior_user_id == $user_id) {
                            $data .= '<tr>
							  <td>' . $action . '</td>
                    <td>' . $user_name . '</td>
                    <td>' . $row->punch_in . '</td>
                    <td>' . $row->punch_out . '</td>
                    <td>' . "" . '</td>
                    <td>' . "Outside Punch" . '</td>
                    <td>' . $status . '</td>
                    <td>' . $loc_sts . '</td>
                   
					<td>' . $row->shortinaddress . $row->longinaddress . '</td>
                    <td>' . $row->shortoutaddress . $row->longoutaddress . '</td>

                       </tr>';
                        }
                    }

                }
            }

            $response['message'] = 'success';
            $response['result'] = $data;
        } else {
            $response['message'] = 'fail';
            $response['result'] = $data;
        }
        echo json_encode($response);
    }
    
    function action_r_rq() { //accept or deny outside punch
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $this->db->set('regular_status', $status);
        $this->db->where('id', $id);
        $query = $this->db->update('employee_attendance_leave');
        if ($query != FALSE) {
            $response['message'] = 'success';
            if ($status == 1) {
                $response['body'] = 'Accepted successfully.';
            } else {
                $response['body'] = 'Denied successfully.';
            }
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'something went wrong';
        }
        echo json_encode($response);
    }

    public function deny_m_rqst() {
        $id = $this->input->post('id');
        $this->db->set('activity_status', '4');
        $this->db->set('regular_status', '2');
        $this->db->where('id', $id);
        $query = $this->db->update('employee_attendance_leave');
        if ($query != FALSE) {

            $response['message'] = 'success';
            $response['body'] = 'Denied successfully.';
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'something went wrong';
        }
        echo json_encode($response);
    }

    public function approve_m_rqst()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id_login = ($session_data['emp_id']);

        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id_login'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $date = $this->input->post('date');
        $day = date('l', strtotime($date));
        $check_holiday = $this->Globalmodel->check_Holiday($date, $user_id, $firm_id, $day);
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
        if ($check_holiday == FALSE) {
            $holiday = 0; //NO holiday
        } else if ($check_holiday == 1) {
            $holiday = 1; //Holiday
        } else {
            if ($day == 'Sunday') {
                $holiday = 1; //Holiday
            } else {
                $holiday = 0; //NO holiday
            }
        }
        $get_hr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id_login'");
        if ($this->db->affected_rows() > 0) {
            $res = $get_hr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }

        $get_data = $this->db->query("select missing_punchin,missing_punchout from employee_attendance_leave where id='$id'");
        if ($this->db->affected_rows() > 0) {
            $res = $get_data->row();
            $punchin = $res->missing_punchin;
            $punchout = $res->missing_punchout;
            $data = array(
                'punch_in' => $punchin,
                'punch_out' => $punchout,
                'activity_status' => 3,
                'is_holiday' => $holiday,
                'holiday_approval_status' => $holiday_approval_status,
                'regular_status' => 1
            );
            $result = $this->db->update('employee_attendance_leave', $data, array('id' => $id));
            if ($result != FALSE) {
                $response['message'] = 'success';
                $response['body'] = 'Approved & Attendance marked.';
            } else {
                $response['message'] = 'fail';
                $response['body'] = 'something went wrong';
            }
            echo json_encode($response);
        }
    }

    public function login_dates()
    {
        $userdate = $this->input->post('userdate');
        $query = $this->db->query("SELECT * FROM employee_attendance where DATE(intime)='$userdate' AND DATE(outtime)='$userdate'");
        if ($this->db->affected_rows() > 0) {
            $response['message'] = 'success';
        } else {
            $response['message'] = 'fail';
        }
        echo json_encode($response);
    }

    public function get_location_info()
    {
        $latitude = '18.8181216';
        $longitude = '73.2756982';
        $geolocation = $latitude . ',' . $longitude;
        $request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $geolocation . '&sensor=false';
        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);
        if (isset($json_decode->results[0])) {

            $response = array();
            foreach ($json_decode->results[0]->address_components as $addressComponet) {
                if (in_array('political', $addressComponet->types)) {
                    $response[] = $addressComponet->long_name;
                }
            }
            var_dump($response);

            if (isset($response[0])) {
                $first = $response[0];
            } else {
                $first = 'null';
            }
            if (isset($response[1])) {
                $second = $response[1];
            } else {
                $second = 'null';
            }
            if (isset($response[2])) {
                $third = $response[2];
            } else {
                $third = 'null';
            }
            if (isset($response[3])) {
                $fourth = $response[3];
            } else {
                $fourth = 'null';
            }
            if (isset($response[4])) {
                $fifth = $response[4];
            } else {
                $fifth = 'null';
            }

            if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null') {
                echo "<br/>Address:: " . $first;
                echo "<br/>City:: " . $second;
                echo "<br/>State:: " . $fourth;
                echo "<br/>Country:: " . $fifth;
            } else if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null') {
                echo "<br/>Address:: " . $first;
                echo "<br/>City:: " . $second;
                echo "<br/>State:: " . $third;
                echo "<br/>Country:: " . $fourth;
            } else if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null') {
                echo "<br/>City:: " . $first;
                echo "<br/>State:: " . $second;
                echo "<br/>Country:: " . $third;
            } else if ($first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') {
                echo "<br/>State:: " . $first;
                echo "<br/>Country:: " . $second;
            } else if ($first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') {
                echo "<br/>Country:: " . $first;
            }
        } else {

        }
    }

    public function get_overtimeData()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);

        $data = '';

        $result = array();
        if (!is_null($this->input->post_get('emp_id'))) {
            $emp_id = $this->input->post_get('emp_id');
            $query = $this->db->query("select id,date,punch_out,punch_in from employee_attendance_leave where punch_out !='0000-00-00 00:00:00' AND is_holiday='1' AND holiday_approval_status='0' AND user_id='$emp_id'");
            $this->db->last_query();
            if ($this->db->affected_rows() > 0) {
                $result1 = $query->result();
                foreach ($result1 as $row1) {


                    $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_overtime(\'' . $row1->id . '\',\'' . 'A' . '\')" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_overtime(\'' . $row1->id . '\',\'' . 'D' . '\')" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    $dt = new DateTime($row1->punch_in);
                    $dt1 = new DateTime($row1->punch_out);
                    $dt2 = new DateTime($row1->date);
                    $data .= '<tr>
                                <td>' . $J_user_name . '</td>
                                <td>' . $dt2->format('d-M-Y') . '</td>
                                <td>' . $dt->format('h:i:s A') . '</td>
                                <td>' . $dt1->format('h:i:s A') . '</td>
                                <td>' . "Present On holiday" . '</td>
                                <td>' . $action . '</td>
                                </tr>';

                    }
					$response['message'] = 'successfully found data';
            $response['data'] = $data;
                } else {
            $response['message'] = 'No Data Found';
			$response['data'] = $data;
        }

		} else {
            $result = $this->db->query("select user_id,user_name from user_header_all where senior_user_id='$user_id' and holiday_working_sts='3'")->result();
            if ($result != null) {

                foreach ($result as $row) {
                    $J_user_id = $row->user_id;
                    $J_user_name = $row->user_name;


                    $query = $this->db->query("select id,date,punch_out,punch_in from employee_attendance_leave where punch_out !='0000-00-00 00:00:00' AND is_holiday='1' AND holiday_approval_status='0' AND user_id='$J_user_id'");
                    $this->db->last_query();
                    if ($this->db->affected_rows() > 0) {
                        $result1 = $query->result();

                        foreach ($result1 as $row1) {

                            $action = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_overtime(\'' . $row1->id . '\',\'' . 'A' . '\')" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_overtime(\'' . $row1->id . '\',\'' . 'D' . '\')" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                            $dt = new DateTime($row1->punch_in);
                            $dt1 = new DateTime($row1->punch_out);
                            $dt2 = new DateTime($row1->date);
                            $data .= '<tr>
                                <td>' . $J_user_name . '</td>
                                <td>' . $dt2->format('d-M-Y') . '</td>
                                <td>' . $dt->format('h:i:s A') . '</td>
                                <td>' . $dt1->format('h:i:s A') . '</td>
                                <td>' . "Present On holiday" . '</td>
                                <td>' . $action . '</td>
                                </tr>';
                        }


                    }
                }
                $response['message'] = 'success';
                $response['data'] = $data;

            } else {
                $response['message'] = 'Fail';
                $response['data'] = $data;
            }
        }
        echo json_encode($response);
    }

    public function ActionOvertimeRqst()
    {
        $id = $this->input->post('id');
        $id2 = $this->input->post('id2');
        if ($id2 == 'A') {
            $this->db->set('holiday_approval_status', '1');
            $response['body'] = 'Request Accepted Successfully';
        } else {
            $this->db->set('holiday_approval_status', '2');
            $response['body'] = 'Request Denied Successfully';
        }
        $this->db->where('id', $id);
        $query = $this->db->update('employee_attendance_leave');
        if ($query != FALSE) {

            $response['message'] = 'success';
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'something went wrong';
        }
        echo json_encode($response);
    }

    public function get_current_month_data() {
        // dd("hello");
        $user_id = $this->input->post('user_id');
        $month1 = $this->input->post('month1');
        $year1 = $this->input->post('year1');

        $session_data = $this->session->userdata('login_session');

        $user_type = ($session_data['user_type']);
        if ($user_id == '' || $user_id=='Search by Employee') {
            $session_data = $this->session->userdata('login_session');
			$user_id = ($session_data['emp_id']);
        }

        $m = $month1;
        $y = $year1;
        $d = cal_days_in_month(CAL_GREGORIAN, $m, $y);
        $data = "";
        for ($i = 1; $i <= $d; $i++) {
            $get_data = $this->db->query("select * from employee_attendance_leave where user_id='$user_id' AND month(date)='$m' AND year(date)='$y' AND day(date)='$i'")->row();

            if ($this->db->affected_rows() > 0) {
                $punch_in = $this->convertdate($get_data->punch_in);
                $punch_out = $this->convertdate($get_data->punch_out);
                $location = $get_data->shortinaddress;
                $location1 = $get_data->shortoutaddress;
                if ($location == "" && ($get_data->punch_in) != "0000-00-00 00:00:00") {
                    $location = "GPS off";
                    $sts = "";
                }
                if ($location1 == "" && ($get_data->punch_out) != "0000-00-00 00:00:00") {
                    $location1 = "GPS off";
                    $sts = "";
                }
				
                if ($get_data->punch_in != 0 || $get_data->punch_out != 0) {
                    $status = "AT";
                    $btn = "";
                    $sts = "";
                }
                if ($get_data->punch_in != 0 && $get_data->punch_out == 0) {
                    $punch_out = "--:--";
                }
                if ($get_data->punchin_lat == 0 && $get_data->punchin_long == 0 && $get_data->leave_status != 1) {
                    $status = "OO";
                    if ($get_data->regular_status == 0) {
                        $sts = "R";
                        $btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    } else if ($get_data->regular_status == 1) {
                        $btn = "A";
                        $sts = "A";
                    } else {
                        $btn = "D";
                        $sts = "D";
                    }
                } else {
                    if ($get_data->punchin_lat != 0 && $get_data->punchin_long != 0) {
                        if (($this->check_location($get_data->punchin_lat, $get_data->punchin_long) == 0)) {
                            $status = "OO";
                            if ($get_data->regular_status == 0) {
                                // $status = '<span class="badge badge-info" >Requested </span>';
                                $sts = "R";
                                $btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
							   <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                            } else if ($get_data->regular_status == 1) {
                                $sts = "A";
                                $btn = "A";
                                //  $btn = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                            } else {
                                $sts = "D";
                                $btn = "D";
                                // $btn = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                            }
                        }
                    } else {
                        $session_data = $this->session->userdata('login_session');
                        $sen_id = ($session_data['emp_id']);
                        if ($sen_id == $user_id) {
                            $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
                                        `user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,`leave_transaction_all`.`id`,
                                        `leave_transaction_all`.`leave_requested_on`, `leave_transaction_all`.`leave_date`,`leave_transaction_all`.`status`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`user_id`='$user_id' AND month(leave_date)='$m' AND day(leave_date)='$i' AND year(leave_date)='$y' ORDER BY leave_date DESC")->row();
                            
                        } else {
                            $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
                                        `user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,`leave_transaction_all`.`id`,
                                        `leave_transaction_all`.`leave_requested_on`, `leave_transaction_all`.`leave_date`,`leave_transaction_all`.`status`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`approved_deny_by`='$sen_id' AND `leave_transaction_all`.`user_id`='$user_id' AND month(leave_date)='$m' AND day(leave_date)='$i' AND year(leave_date)='$y' ORDER BY leave_date DESC")->row();
                        }

                        if ($this->db->affected_rows() > 0) {
                            $punch_in = "--:--";
                            $location = "--:--";
                            $location1 = "--:--";
                            $punch_out = "--:--";
                            $status = "LR";
                            $btn = '<button id="sample_1_new" class="btn-info btn-simple btn blue-hoki btn-outline sbold uppercase popovers"
                                    data-toggle="modal" data-target="#view_leave_details" data-container="body"
                                    data-placement="left" data-trigger="hover" data-content="See Details" data-original-title="" title=""
                                    data-desig_id="' . $fetch_data->designation_id . '"
                                    data-leave_type1="' . $fetch_data->leave_type . '"
                                    data-emp_user_id="' . $fetch_data->user_id . '"
                                    data-leave_id="' . $fetch_data->leave_id . '">
                                    <i class="fa fa-eye"></i>
                                    </button>';

                            if ($fetch_data->status == '1') {
                                $sts = "R";
                                $approve = '<a class="btn btn-link btn-icon-only  " data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(\'' . $fetch_data->id . '\',\'' . $fetch_data->leave_id . '\');"><i class="fa fa-check font-green"></i></a>';
                                $deny = '<a class="btn btn-link btn-icon-only  " data-toggle="modal" title="Deny!" name="deny_leave" id="deny_leave" data-target="#mydenyModal" data-myvalue="' . $fetch_data->id . '"><i class="fa fa-close font-red"></i></a>';
                            } else if ($fetch_data->status == '2') {
                                $approve = "A";
                                $sts = "A";
                                $deny = "";
                                // $approve = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" ><i class="fa fa-check font-green"></i></a>';
                                //$deny = '<a class="btn btn-link btn-icon-only  " data-toggle="modal"title="Deny!" name="deny_leave" id="deny_leave" data-target="#mydenyModal" data-myvalue="'.$fetch_data->id.'"><i class="fa fa-close font-red"></i></a>';
                            } else if ($fetch_data->status == '3') {
                                $approve = "D";
                                $deny = "";
                                $sts = "A";
                                //  $approve = '<a class="btn btn-link btn-icon-only  " data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(\''.$fetch_data->id.'\',\''.$fetch_data->leave_id.'\');"><i class="fa fa-check font-green"></i></a>';
                                //   $deny = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close font-red"></i></a>';
                            } else {
                                $approve = "";
                                $deny = "";
                                $sts = "";
                                // $approve = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" ><i class="fa fa-check font-green"></i></a>';
                                // $deny = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close font-red"></i></a>';
                            }
                            $btn = $approve . "</br>" . $deny;
                        }

                    }
                }
                if ($get_data->missing_punchin != 0 and $get_data->missing_punchout != 0) {
                    $missing_punch_in = $this->convertdate($get_data->missing_punchin);
                    $missing_punch_out = $this->convertdate($get_data->missing_punchout);
                    $location = $get_data->shortinaddress;
                    $location1 = $get_data->shortoutaddress;
                    $status = "RA";
                    if ($get_data->regular_status == 0) {
                        $sts = "R";
                        //   $status = '<span class="badge badge-info" >Requested </span>';
                        $btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    } else if ($get_data->regular_status == 1) {
                        $sts = "A";
                        //  $status = '<span class="badge badge-primary" >Approved </span>';
                        $btn = "A";
                        //   $btn = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                    } else {
                        $sts = "D";
                        $btn = "D";
                        //  $status = '<span class="badge badge-danger" >Denied </span>';
                        // $btn = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    }
                }
            } else {
                $punch_in = "--:--";
                $punch_out = "--:--";
                $location = "--:--";
                $location1 = "--:--";
                $status = "NA";
                $btn = "";
                $sts = "";
            }

            $session_data = $this->session->userdata('login_session');
            $session_user_id = ($session_data['emp_id']);
            if ($user_id == $session_user_id) {
                $btn = $sts;
            }
            $data .= '<tr>';
            $data .= '<td>' . $i . '</td>';
            $data .= '<td>' . $status . '</td>';


            $data .= '<td> IN :' . $punch_in . '</td>';


            $data .= '<td>OUT :' . $punch_out . '</td>';

            $data .= '<td >  <p class="show-read-more"><b>IN:</b>' . $location . '</p><p class="show-read-more"><b>OUT:</b>' . $location1 . '</p></td>
								<td>' . $btn . '</td>
							</tr>';

        }
        //exit;
        $response['status'] = true;
        $response['data'] = $data;
        echo json_encode($response);
    }

    
    function generateMonthlyReport() {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $qr = $this->db->query("SELECT hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $select_type = $this->input->post('select_type');
        if ($select_type == "M") {
            $qr = $this->db->query("SELECT * from user_header_all where firm_id='$firm_id' AND user_type='4' AND activity_status='1'");
            if ($this->db->affected_rows() > 0) {
                $result = $qr->result();
                // <td>Week Off</td>
                // <td>Holidays</td>
                // <td>Total Leaves</td>
                // <td>Standard Gross Salary</td>
                // <td>Loss of Pay</td>
                // <td>Total Deduction</td>
                // <td>Net Payable Salary</td>
                $data = "<table class='table table-bordered' id='myTable'>
                            <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Month</td>
                                    <td>Total Days</td>
                                    <td>Total Working Days</td>
                                    <td>Total Present Days</td>
                                    <td>Total Absent Days</td>
                                    <td>Total Week Off Days</td>
                                    <td>No of Days Came in late </td>
                                    <td>No of Days Left Before shift time</td>
                                    <td>No of Days for outside punch</td>
                                    <td>No of regularize requests made</td>
                                </tr>
                            </thead>
                            <tbody>
                            ";
                foreach ($result as $row) {
                    $userid = $row->user_id;
                    $user_name = $row->user_name;
                    $email = $row->email;
                    $dateObj = DateTime::createFromFormat('!m', $month);
                    $a = $dateObj->format('F'); // March
                    // $qr1 = $this->db->query("select count(*) as cnt from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status IN (0,4,5) AND Year(date)='$year'")->row();
                    // $present_days = $qr1->cnt;
                    // $qr2 = $this->db->query("select count(*) as cnt_l from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status = 1 AND Year(date)='$year'")->row();
                    // $leave_days = $qr2->cnt_l;
                    // $qr11 = $this->db->query("select sum(amt) as type1 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
                    // $type1 = $qr11->type1;
                    // $qr17 = $this->db->query("select sum(amt) as type7 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 7 AND year='$year'")->row();
                    // $type7 = $qr17->type7;
                    // $qr13 = $this->db->query("select sum(amt) as type3 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 3 AND year='$year'")->row();
                    // $type3 = $qr13->type3;
                    // $qr12 = $this->db->query("select sum(amt) as type2 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 2 AND year='$year'")->row();
                    // $type2 = $qr12->type2;
                    // $qr14 = $this->db->query("select sum(amt) as type4 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 4 AND year='$year'")->row();
                    // $type4 = $qr14->type4;
                    // $qr16 = $this->db->query("select sum(amt) as type6 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 6 AND year='$year'")->row();
                    // $type6 = $qr16->type6;
                    // $qr18 = $this->db->query("select sum(amt) as type8 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 8 AND year='$year'")->row();
                    // $type8 = $qr18->type8;
                    // $qr15 = $this->db->query("select sum(amt) as type5 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 5 AND year='$year'")->row();
                    // $type5 = $qr15->type5;
                    // $salry = $type1 + $type3 + $type7;
                    // $deduction = $type2 + $type4 + $type6 + $type8;
                    // $this->load->model('MasterModel');
                    // $alternate_ids = $this->MasterModel->_rawQuery("SELECT alternate_id,user_id 
                    //                                                             FROM alternate_holiday_master  where alternate_id in  
                    //                                                             (select group_concat(alternate_id) as alternate_id  from holiday_master_all 
                    //                                                                 where firm_id='$firm_id' AND Month(date)='$month' AND category=0  
                    //                                                                 AND Year(date)='$year' 
                    //                                                                 and alternate_id=alternate_holiday_master.alternate_id) 
                    //                                                                 and user_id = '$userid' limit 1;
                    //             ");
                    // if ($alternate_ids->totalCount > 0) {
                    //     $alternate_id = $alternate_ids->data[0]->alternate_id;
                    //     $alter_sat = $this->db->query("SELECT count(*) as sat_cnt from holiday_master_all 
                    //                                                 where firm_id='$firm_id' AND Month(date)='$month' AND category=0 
                    //                                                 and is_alternate = 1  AND Year(date)='$year' 
                    //                                                 and alternate_id = '$alternate_id'
                    //                                             ")->row();
                    //     $sat_cnt = $alter_sat->sat_cnt;
                    // } else {
                    //     $sat_cnt = 0;
                    // }
                    // $sun_data = $this->db->query("select count(*) as sun_cnt from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=0  AND Year(date)='$year' and is_alternate = 0")->row();
                    // $sun_cnt = $sun_data->sun_cnt;
                    // $weekoff = ($sat_cnt + $sun_cnt);
                    // $qr3 = $this->db->query("select count(*) as holidays from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=1  AND Year(date)='$year'")->row();
                    // $holidays = $qr3->holidays;
                    // $stdamt = $this->db->query("select sum(std_amt) as std_amt from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
                    // $std_amt = $stdamt->std_amt;
                    // $Lop = $std_amt - $type1;
                    // $daysT = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    // // dd("DAYS", "daysT : " . $daysT, "weekoff : " . $weekoff, "holidays : " . $holidays, "present_days : " . $present_days);
                    // $AbsentDays = ($daysT - ($weekoff + $holidays)) - $present_days;
                    // $net_payable_sal = round($std_amt - ($deduction + $Lop), 2);
                    // if ($net_payable_sal < 0) {
                    //     $net_payable_sal = 0;
                    // } else {
                    //     $net_payable_sal = round($std_amt - ($deduction + $Lop), 2);
                    // }

                    // abhishek mishra
                    $totalWorkingDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $salaryQuery = $this->db->query("SELECT
                                                        SUM(CASE WHEN category = 1 THEN std_amt ELSE 0 END) AS 'Standerd Salary',
                                                        SUM(CASE WHEN category = 1 THEN amt ELSE 0 END) AS 'Basic Salary',
                                                        SUM(CASE WHEN category = 2 THEN amt ELSE 0 END) AS 'Professional Tax',
                                                        SUM(CASE WHEN category = 3 THEN amt ELSE 0 END) AS 'Performance Allowance',
                                                        SUM(CASE WHEN category = 4 THEN amt ELSE 0 END) AS 'Leave Deduction',
                                                        SUM(CASE WHEN category = 5 THEN amt ELSE 0 END) AS 'Income Tax',
                                                        SUM(CASE WHEN category = 6 THEN amt ELSE 0 END) AS 'Type Six',
                                                        SUM(CASE WHEN category = 7 THEN amt ELSE 0 END) AS 'Holiday OT Salary',
                                                        SUM(CASE WHEN category = 8 THEN amt ELSE 0 END) AS 'Sandwich Leave',
                                                        SUM(CASE WHEN category IN (1,3,7) THEN amt ELSE 0 END) AS 'Total Salary',
                                                        SUM(CASE WHEN category IN (2,4,6,8) THEN amt ELSE 0 END) AS 'Total Deduction',
                                                        (
                                                            SUM(CASE WHEN category = 1 THEN std_amt ELSE 0 END) - 
                                                            SUM(CASE WHEN category = 1 THEN amt ELSE 0 END)
                                                        ) AS 'Total Loss of Pay',
                                                        ROUND(
                                                            (
                                                                SUM(CASE WHEN category = 1 THEN std_amt ELSE 0 END) - 
                                                                (
                                                                    SUM(CASE WHEN category IN (2,4,6,8) THEN amt ELSE 0 END) + 
                                                                    (
                                                                        SUM(CASE WHEN category = 1 THEN std_amt ELSE 0 END) -
                                                                        SUM(CASE WHEN category = 1 THEN amt ELSE 0 END)
                                                                    )
                                                                )
                                                            ), 2
                                                        ) AS 'Net Payable Salary'
                                                    FROM t_salary_staff tss
                                                    WHERE user_id = '$userid'
                                                    AND month   = '$month'
                                                    AND year    = '$year';
                    ")->row();

                    $totalLateCame = $this->db->query("SELECT COUNT(CASE WHEN TIME(punch_in) > '10:00:00' THEN 1 END) AS late_days,
                                                            COUNT(CASE WHEN TIME(punch_out) > '18:30:00' THEN 1 END) AS early_leave_days,
                                                            COUNT(CASE WHEN punchin_lat IS NULL 
                                                                    AND punchout_long IS NULL THEN 1 END) AS outside_punch_count,
                                                            COUNT(CASE WHEN missing_punchin != '0000-00-00 00:00:00' 
                                                                    AND missing_punchout != '0000-00-00 00:00:00' 
                                                                    AND regular_status = 1 THEN 1 END) AS regularize_count
                                                        FROM employee_attendance_leave
                                                        WHERE user_id = '$userid'
                                                        AND firm_id = '$firm_id'
                                                        AND MONTH(punch_in) = '$month'
                                                        AND YEAR(punch_in) = '$year'
                                                        GROUP BY user_id;
                    ")->row();

                    $totalPresent = $this->db->query("SELECT
                                                        user_id,
                                                        SUM(CASE WHEN punch_in = '0000-00-00 00:00:00' AND punch_out = '0000-00-00 00:00:00' 
                                                            THEN 1 ELSE 0 END) AS total_absent_days,
                                                        SUM(CASE WHEN punch_in IS NOT NULL AND punch_in != '0000-00-00 00:00:00' 
                                                                AND punch_out != '0000-00-00 00:00:00'  
                                                                THEN 1 ELSE 0 END) AS total_present_days
                                                    FROM employee_attendance_leave
                                                    WHERE Year(date)='$year'
                                                    AND user_id='$userid'
                                                    AND Month(date)='$month'
                                                    GROUP BY user_id;
                    ")->row();

                    $totalSundaysWithHolidayInMonth = $this->db->query("SELECT
                                                                                SUM(CASE WHEN  category=0 AND is_alternate=0 THEN 1 ELSE 0 END) AS total_sundays,
                                                                                SUM(CASE WHEN category=1 AND is_alternate=0 THEN 1 ELSE 0 END) AS total_holidays
                                                                        FROM holiday_master_all 
                                                                        WHERE MONTH(date) = '$month' 
                                                                        AND YEAR(date)= '$year' 
                                                                        AND firm_id = '$firm_id';
                    ")->row();

                    $alternateSaturday = $this->db->query("SELECT ahm.alternate_id, ahm.user_id,
                                                                COUNT(hma.id) AS alertnate_saturday_count
                                                            FROM alternate_holiday_master ahm
                                                            JOIN holiday_master_all hma 
                                                                ON hma.alternate_id = ahm.alternate_id
                                                                AND hma.firm_id = '$firm_id'
                                                                AND MONTH(hma.date) = '$month'
                                                                AND YEAR(hma.date) = '$year'
                                                                AND hma.category = 0
                                                                AND hma.is_alternate = 1
                                                            WHERE ahm.user_id = '$user_id'
                                                            GROUP BY ahm.alternate_id, ahm.user_id
                                                            LIMIT 1
                    ")->row();

                    $totalLeaves = $this->db->query("SELECT 
                                                        SUM(
                                                            CASE 
                                                                WHEN MONTH(leave_date) = '$month'
                                                                    AND YEAR(leave_date) = '$year'
                                                                    AND status = 2 
                                                                    AND user_id = '$user_id'
                                                                THEN 1 ELSE 0 
                                                            END
                                                        ) AS total_leave_count
                                                    FROM leave_transaction_all;"
                    )->row();

                    if($salaryQuery->{'Net Payable Salary'} > 0) {
                        $netSalary = $salaryQuery->{'Net Payable Salary'};
                    } else {
                        $netSalary = 0;
                    }

                    if (isset($totalLeaves->total_leave_count) && $totalLeaves->total_leave_count != '') {
                        $totalApprovedLeave = $totalLeaves->total_leave_count;
                    } else {
                        $totalApprovedLeave = 0;
                    }

                    if (isset($alternateSaturday->alertnate_saturday_count) && $alternateSaturday->alertnate_saturday_count != '') {
                        $alternateSaturday = $alternateSaturday->alertnate_saturday_count;
                    } else {
                        $alternateSaturday = 0;
                    }

                    if (isset($totalSundaysWithHolidayInMonth->total_sundays)) {
                        $totalSundays = $totalSundaysWithHolidayInMonth->total_sundays;
                    } else {
                        $totalSundays = 0;
                    }

                    if (isset($totalSundaysWithHolidayInMonth->total_holidays)) {
                        $totalHolidays = $totalSundaysWithHolidayInMonth->total_holidays;
                    } else {
                        $totalHolidays = 0;
                    }

                    if ((isset($totalPresent->total_present_days) && $totalPresent->total_present_days != '')) {
                        $totalPresentDays = $totalPresent->total_present_days;
                    } else {
                        $totalPresentDays = 0;
                    }

                    if (isset($totalLateCame->late_days)) {
                        $late_days = $totalLateCame->late_days;
                    } else {
                        $late_days = 0;
                    }

                    if (isset($totalLateCame->early_leave_days)) {
                        $early_leave_days = $totalLateCame->early_leave_days;
                    } else {
                        $early_leave_days = 0;
                    }

                    if (isset($totalLateCame->regularize_count)) {
                        $regularize_count = $totalLateCame->regularize_count;
                    } else {
                        $regularize_count = 0;
                    }

                    if (isset($totalLateCame->outside_punch_count)) {
                        $outside_punch_count = $totalLateCame->outside_punch_count;
                    } else {
                        $outside_punch_count = 0;
                    }

                    $weekOffDays = ($alternateSaturday + $totalSundays);
                    // dd($totalWorkingDaysInMonth, $weekOffDays, $totalHolidays, $totalPresentDays, $totalApprovedLeave);
                    $totalAbsentDays = $totalWorkingDaysInMonth - ($weekOffDays + $totalHolidays + $totalPresentDays + $totalApprovedLeave);

                    // dd("ABHISHEK MISHRA", $late_days, $early_leave_days, $regularize_count, $outside_punch_count);
                    // <td>" . $weekoff . "</td>
                    // <td>" . $holidays . "</td>
                    // <td>" . $leave_days . "</td>
                    // <td>" . round($std_amt, 2) . "</td>
                    // <td>" . round($Lop, 2) . "</td>
                    // <td>" . round($deduction, 2) . "</td>
                    // <td>" . $net_payable_sal . "</td>
                    $data .= "<tr>
                                    <td>" . $user_name . "</td>
                                    <td>" . $email . "</td>
                                    <td>" . $a . "</td>
                                    <td>" . cal_days_in_month(CAL_GREGORIAN, $month, $year) . "</td>
                                    <td>" . ($totalWorkingDaysInMonth - ($alternateSaturday + $totalSundays + $totalHolidays)) . "</td>
                                    <td>" . $totalPresentDays . "</td>
                                    <td>" . $totalAbsentDays . "</td>
                                    <td>" . ($alternateSaturday + $totalSundays + $totalHolidays) . "</td>
                                    <td>" . $late_days . "</td>
                                    <td>" . $early_leave_days . "</td>
                                    <td>" . $outside_punch_count . "</td>
                                    <td>" . $regularize_count . "</td>
                                </tr>";
                }
                $data .= '</tbody></table>';
                $response['status'] = 200;
                $response['data'] = $data;
            } else {
                $response['status'] = 200;
                $response['data'] = "";
            }
        } else {
            $select_f_year = $this->input->post('select_f_year');
            $select_f_year1 = $select_f_year + 1;
            $qr = $this->db->query("select user_id,user_name,email from user_header_all where firm_id='$firm_id' AND user_type='4' AND activity_status='1'");
            // <td>Standard Gross Salary</td>
            // <td>Loss of Pay</td>
            // <td>Total Deduction</td>
            // <td>Net Payable Salary</td>
            $data = "<table class='table table-bordered' id='myTable'>
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Email</td>
                                <td>No of Days Came in late </td>
                                <td>No of Days Left Before shift time</td>
                                <td>No of Days for outside punch</td>
                                <td>No of regularize requests made</td>
                            </tr>
                        </thead>
                        <tbody>
                        ";
            if ($this->db->affected_rows() > 0) {
                $result = $qr->result();
                foreach ($result as $row) {
                    $user_id = $row->user_id;
                    $user_name = $row->user_name;
                    $email = $row->email;
                    $queryN = $this->db->query("SELECT sum(std_amt) as std_amt, sum(amt) as amt ,(sum(std_amt) - sum(amt)) as LOP from t_salary_staff where user_id='" . $user_id . "' and   category=1 and ((year = " . $select_f_year . " AND month >= 4) OR (year = " . $select_f_year1 . " AND month <= 3)) ;
					")->row();
                    $deduction = $this->db->query("SELECT ROUND(sum(amt)) as deductions from t_salary_staff where user_id='" . $user_id . "' and   category in (2,4,6,8) and ((year = " . $select_f_year . " AND month >= 4) OR (year = " . $select_f_year1 . " AND month <= 3)) ;
					")->row()->deductions;
                    $totalLateCame = $this->db->query("SELECT COUNT(CASE WHEN TIME(punch_in) > '09:30:00' THEN 1 END) AS late_days,
                                                        COUNT(CASE WHEN TIME(punch_out) > '18:30:00' THEN 1 END) AS early_leave_days
                                                                FROM employee_attendance_leave
                                                                WHERE user_id = '$userid'
                                                                AND MONTH(punch_in) = '$month'
                                                                AND YEAR(punch_in) = '$year'
                                                                GROUP BY user_id;")->row();
                    if (isset($totalLateCame->late_days)) {
                        $late_days = $totalLateCame->late_days;
                    } else {
                        $late_days = 0;
                    }

                    if (isset($totalLateCame->early_leave_days)) {
                        $early_leave_days = $totalLateCame->early_leave_days;
                    } else {
                        $early_leave_days = 0;
                    }
                    $totalRegularize = $this->db->query("SELECT COUNT(*) AS regularize_count
                                                            FROM employee_attendance_leave
                                                            WHERE user_id = '$userid'
                                                            AND MONTH(punch_in) = '$month'
                                                            AND YEAR(punch_in) = '$year'
                                                            AND missing_punchin != '0000-00-00 00:00:00'
                                                            AND missing_punchout != '0000-00-00 00:00:00'
                                                            and activity_status = 3
                                                            GROUP BY user_id;")->row();
                    if (isset($totalRegularize->regularize_count)) {
                        $regularize_count = $totalRegularize->regularize_count;
                    } else {
                        $regularize_count = 0;
                    }

                    $totalOutsidePunch = $this->db->query("SELECT COUNT(*) AS outside_punch_count
                                                            FROM employee_attendance_leave
                                                            WHERE user_id = '$userid'
                                                            AND MONTH(punch_in) = '$month'
                                                            AND YEAR(punch_in) = '$year'
                                                            AND (punchin_lat != 0 AND punchin_long != 0
                                                            AND activity_status = 0)
                                                            GROUP BY user_id;")->row();
                    if (isset($totalOutsidePunch->outside_punch_count)) {
                        $outside_punch_count = $totalOutsidePunch->outside_punch_count;
                    } else {
                        $outside_punch_count = 0;
                    }
                    $net_payable_sal = round($queryN->std_amt) - round($queryN->LOP) - $deduction;
                    // <td>" . round($queryN->std_amt) . "</td>
                    // <td>" . round($queryN->LOP) . "</td>
                    // <td>" . ($deduction) . "</td>
                    // <td>" . $net_payable_sal . "</td>
                    $data .= "<tr>
                                <td>" . $user_name . "</td>
                                <td>" . $email . "</td>
                                <td>" . $late_days . "</td>
                                <td>" . $early_leave_days . "</td>
                                <td>" . $regularize_count . "</td>
                                <td>" . $outside_punch_count . "</td>
                            </tr>";
                }
                $data .= '</tbody></table>';
                $response['status'] = 200;
                $response['data'] = $data;
            } else {
                $response['status'] = 200;
                $response['data'] = "";
            }
        }
        echo json_encode($response);
    }


    public function generateXls() {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        $year = $this->input->get('year');
        $month = $this->input->get('month');
        // create file name
        $fileName = 'data-' . time() . '.xlsx';
        // load excel library
        $this->load->library('excel');
        //$listInfo = $this->export->exportList();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Month');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Total Days');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Total Working Days');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Total Present Days');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Total Absent Days');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Week Off');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Holidays');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Total Leaves');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Standard Gross Salary');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Loss of Pay');
        $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Total Deduction');
        $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Income Tax');
        $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Net Payable Salary');

        $qr = $this->db->query("select * from user_header_all where firm_id='$firm_id' AND user_type='4'");
        if ($this->db->affected_rows() > 0) {
            $result = $qr->result();
            $rowCount = 2;
            foreach ($result as $row) {
                $userid = $row->user_id;
                $user_name = $row->user_name;
                $email = $row->email;

                $dateObj = DateTime::createFromFormat('!m', $month);
                $a = $dateObj->format('F'); // March
                //present days
                $qr1 = $this->db->query("select count(*) as cnt from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status IN (0,4,5) AND Year(date)='$year'")->row();
                $present_days = $qr1->cnt;
                //leave days
                $qr2 = $this->db->query("select count(*) as cnt_l from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status = 1 AND Year(date)='$year'")->row();
                $leave_days = $qr2->cnt_l;

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
                //Salary deduction type6
                $qr16 = $this->db->query("select sum(amt) as type6 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 6 AND year='$year'")->row();
                $type6 = $qr16->type6;
                //Salary deduction type8
                $qr18 = $this->db->query("select sum(amt) as type8 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 8 AND year='$year'")->row();
                $type8 = $qr18->type8;
                $qr2 = $this->db->query("select count(*) as weekoff from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=0  AND Year(date)='$year'")->row();
                $weekoff = $qr2->weekoff;

                //Income tax
                $qr15 = $this->db->query("select sum(amt) as type5 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 5 AND year='$year'")->row();
                $type5 = $qr15->type5;
                //holidays
                $qr3 = $this->db->query("select count(*) as holidays from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=1  AND Year(date)='$year'")->row();
                $holidays = $qr3->holidays;
                $salry = $type1 + $type3 + $type7;
                $deduction = $type2 + $type4 + $type6 + $type8;
                //Salary Lop
                $stdamt = $this->db->query("select sum(std_amt) as std_amt from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
                $std_amt = $stdamt->std_amt;
                $Lop = $std_amt - $type1;
                $totalT = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $AbsentDays = ($totalT - ($weekoff + $holidays)) - $present_days;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $user_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $email);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $a);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, cal_days_in_month(CAL_GREGORIAN, $month, $year));
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, ($totalT - ($weekoff + $holidays)));
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $present_days);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $AbsentDays);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $weekoff);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $holidays);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $leave_days);
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, round($std_amt, 2));
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, round($Lop, 2));
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, round($deduction, 2));
                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, round($type5, 2));
                $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, round($std_amt - ($deduction + $Lop + $type5), 2));
                $rowCount++;
            }
        }

        $filename = "MonthlyReport" . date("Y-m-d-H-i-s") . ".csv";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');

    }

    public function generateXls_data_old() {
        // dd("abhishek mishra : generate excel");
        $session_data = $this->session->userdata('login_session');
        $user_id = $this->input->get('user_id');
        if ($user_id == '' || $user_id=='Search by Employee') {
            $session_data = $this->session->userdata('login_session');
			$user_id = ($session_data['emp_id']);
        }
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
        $year = $this->input->get('year');
        $month = $this->input->get('month');
        // create file name
        $fileName = 'data-' . time() . '.xlsx';
        // load excel library
        $this->load->library('excel');
        //$listInfo = $this->export->exportList();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'DAY');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'TYPE');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'IN');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'OUT');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'LOCATION');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'ACTION');


        $m = $month;
        $y = $year;
        //print_r($month1);exit;
        //$m=date('m');
        //$y=date('Y');
        $d = cal_days_in_month(CAL_GREGORIAN, $m, $y);
        // print_r($d);exit;
        $data = "";
        $d1 = 2;
        for ($i = 1; $i <= $d; $i++) {
            $get_data = $this->db->query("select * from employee_attendance_leave where user_id='$user_id' AND month(date)='$m' AND year(date)='$y' AND day(date)='$i'")->row();
            
            if ($this->db->affected_rows() > 0) {
                //  echo $i;
                $punch_in = $this->convertdate($get_data->punch_in);
                $punch_out = $this->convertdate($get_data->punch_out);
                $location = $get_data->shortinaddress;
                $location1 = $get_data->shortoutaddress;

				if ($location == "" && ($get_data->punch_in) != "0000-00-00 00:00:00") {
					$location = "GPS off";
					$sts = "";
				}
				if ($location1 == "" && ($get_data->punch_out) != "0000-00-00 00:00:00") {
					$location1 = "GPS off";
					$sts = "";
				}
                if ($get_data->punch_in != 0 || $get_data->punch_out != 0) {
                    $status = "Attendance";
                    $btn = "";
                    $sts = "";
                }
                if ($get_data->punch_in != 0 && $get_data->punch_out == 0) {
                    $punch_out = "--:--";
                }
                if ($get_data->punchin_lat == 0 && $get_data->punchin_long == 0 && $get_data->leave_status != 1) {
                    $status = "Outside Office";
                    if ($get_data->regular_status == 0) {
                        // $status = '<span class="badge badge-info" >Requested </span>';
                        $sts = "Requested";
                        $btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    } else if ($get_data->regular_status == 1) {
                        // $status = '<span class="badge badge-primary" >Approved </span>';
                        $btn = "Approved";
                        $sts = "Approved";
                        // $btn = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                    } else {
                        $btn = "Denied";
                        $sts = "Denied";
                        // $status = '<span class="badge badge-danger" >Denied </span>';
                        // $btn = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    }
                } else {
                    if ($get_data->punchin_lat != 0 && $get_data->punchin_long != 0) {
                        if (($this->check_location($get_data->punchin_lat, $get_data->punchin_long) == 0)) {
                            $status = "Outside Office";
                            if ($get_data->regular_status == 0) {
                                // $status = '<span class="badge badge-info" >Requested </span>';
                                $sts = "Requested";
                                $btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
							   <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                            } else if ($get_data->regular_status == 1) {
                                $sts = "Approved";
                                // $status = '<span class="badge badge-primary" >Approved </span>';
                                $btn = "Approved";
                                //  $btn = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                            } else {
                                $sts = "Denied";
                                // $status = '<span class="badge badge-danger" >Denied </span>';
                                $btn = "Denied";
                                // $btn = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                            }
                        }
                    } else {
                        $session_data = $this->session->userdata('login_session');
                        $sen_id = ($session_data['emp_id']);
                        if ($sen_id == $user_id) {
                            $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
                                        `user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,`leave_transaction_all`.`id`,
                                        `leave_transaction_all`.`leave_requested_on`, `leave_transaction_all`.`leave_date`,`leave_transaction_all`.`status`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`user_id`='$user_id' AND month(leave_date)='$m' AND day(leave_date)='$i' AND year(leave_date)='$y' ORDER BY leave_date DESC")->row();
// var_dump($fetch_data);
                        } else {
                            $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
                                        `user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,`leave_transaction_all`.`id`,
                                        `leave_transaction_all`.`leave_requested_on`, `leave_transaction_all`.`leave_date`,`leave_transaction_all`.`status`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`approved_deny_by`='$sen_id' AND `leave_transaction_all`.`user_id`='$user_id' AND month(leave_date)='$m' AND day(leave_date)='$i' AND year(leave_date)='$y' ORDER BY leave_date DESC")->row();
// var_dump($fetch_data);
                        }

                        if ($this->db->affected_rows() > 0) {
                            $punch_in = "--:--";
                            $location = "--:--";
                            $location1 = "--:--";
                            $punch_out = "--:--";
                            $status = "Leave Requested";
                            $btn = '<button id="sample_1_new" class="btn-info btn-simple btn blue-hoki btn-outline sbold uppercase popovers"
                    data-toggle="modal" data-target="#view_leave_details" data-container="body"
                    data-placement="left" data-trigger="hover" data-content="See Details" data-original-title="" title=""
                     data-desig_id="' . $fetch_data->designation_id . '"
                    data-leave_type1="' . $fetch_data->leave_type . '"
                    data-emp_user_id="' . $fetch_data->user_id . '"
                    data-leave_id="' . $fetch_data->leave_id . '">
                    <i class="fa fa-eye"></i>
                    </button>';

                            if ($fetch_data->status == '1') {
                                $sts = "Requested";
                                $approve = '<a class="btn btn-link btn-icon-only  " data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(\'' . $fetch_data->id . '\',\'' . $fetch_data->leave_id . '\');"><i class="fa fa-check font-green"></i></a>';
                                $deny = '<a class="btn btn-link btn-icon-only  " data-toggle="modal" title="Deny!" name="deny_leave" id="deny_leave" data-target="#mydenyModal" data-myvalue="' . $fetch_data->id . '"><i class="fa fa-close font-red"></i></a>';
                            } else if ($fetch_data->status == '2') {
                                $approve = "A";
                                $sts = "A";
                                $deny = "";
                                // $approve = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" ><i class="fa fa-check font-green"></i></a>';
                                //$deny = '<a class="btn btn-link btn-icon-only  " data-toggle="modal"title="Deny!" name="deny_leave" id="deny_leave" data-target="#mydenyModal" data-myvalue="'.$fetch_data->id.'"><i class="fa fa-close font-red"></i></a>';
                            } else if ($fetch_data->status == '3') {
                                $approve = "D";
                                $deny = "";
                                $sts = "A";
                                //  $approve = '<a class="btn btn-link btn-icon-only  " data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(\''.$fetch_data->id.'\',\''.$fetch_data->leave_id.'\');"><i class="fa fa-check font-green"></i></a>';
                                //   $deny = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close font-red"></i></a>';
                            } else {
                                $approve = "";
                                $deny = "";
                                $sts = "";
                                // $approve = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" ><i class="fa fa-check font-green"></i></a>';
                                // $deny = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close font-red"></i></a>';
                            }
                            $btn = $approve . "</br>" . $deny;
                        }

                    }
                }
                if ($get_data->missing_punchin != 0 and $get_data->missing_punchout != 0) {
                    $punch_in = $this->convertdate($get_data->missing_punchin);
                    $punch_out = $this->convertdate($get_data->missing_punchout);
                    $location = $get_data->shortinaddress;
                    $location1 = $get_data->shortoutaddress;
                    $status = "Regularized Attendance";
                    if ($get_data->regular_status == 0) {
                        $sts = "Requested";
                        //   $status = '<span class="badge badge-info" >Requested </span>';
                        $btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    } else if ($get_data->regular_status == 1) {
                        $sts = "Approved";
                        //  $status = '<span class="badge badge-primary" >Approved </span>';
                        $btn = "Approved";
                        //   $btn = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                    } else {
                        $sts = "Denied";
                        $btn = "Denied";
                        //  $status = '<span class="badge badge-danger" >Denied </span>';
                        // $btn = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    }
                }
            } else {
                $punch_in = "--:--";
                $punch_out = "--:--";
                $location = "--:--";
                $location1 = "--:--";
                $status = "NA";
                $btn = "";
                $sts = "";
            }

            $session_data = $this->session->userdata('login_session');
            $session_user_id = ($session_data['emp_id']);
            if ($user_id == $session_user_id) {
                $btn = $sts;
            }
            $day = date('l', strtotime($year . '-' . $month . '-' . $i));
            $loc = "IN : " . $location . "-OUT : " . $location1;
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $d1, $i);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $d1, $day);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $d1, $status);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $d1, $punch_in);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $d1, $punch_out);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $d1, $loc);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $d1)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $d1, $sts);

            $d1++;

        }


        $filename = "MonthlyReport" . $month . "/" . $year . ".csv";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');

    }

    public function generateXls_data()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = $this->input->get('user_id');

        if ($user_id == '' || $user_id == 'Search by Employee') {
            $user_id = $session_data['emp_id'];
        }

        $month = $this->input->get('month');
        $year  = $this->input->get('year');

        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $headers = ['Date','DAY','TYPE','IN','OUT','LOCATION','ACTION'];
        $col = 'A';
        foreach ($headers as $head) {
            $objPHPExcel->getActiveSheet()->SetCellValue($col.'1', $head);
            $col++;
        }

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $row  = 2;

        for ($i = 1; $i <= $days; $i++) {

        
            $punch_in  = "--:--";
            $punch_out = "--:--";
            $location  = "--:--";
            $location1 = "--:--";
            $status    = "Holiday";
            $sts       = "";

            $leave = $this->db->query("
                SELECT status 
                FROM leave_transaction_all
                WHERE user_id='$user_id'
                AND DAY(leave_date)='$i'
                AND MONTH(leave_date)='$month'
                AND YEAR(leave_date)='$year'
                LIMIT 1
            ")->row();

            if ($leave) {

                if ($leave->status == 1) {
                    $status = "Leave Requested";
                    $sts    = "Requested";
                } elseif ($leave->status == 2) {
                    $status = "Leave Approved";
                    $sts    = "Approved";
                } elseif ($leave->status == 3) {
                    $status = "Leave Denied";
                    $sts    = "Denied";
                }

            } else {

                $att = $this->db->query("
                    SELECT *
                    FROM employee_attendance_leave
                    WHERE user_id='$user_id'
                    AND DAY(date)='$i'
                    AND MONTH(date)='$month'
                    AND YEAR(date)='$year'
                    LIMIT 1
                ")->row();
                if ($att) {

                    $status = "Attendance";

                    if ($att->punch_in && $att->punch_in != "0000-00-00 00:00:00") {
                        $punch_in = $this->convertdate($att->punch_in);
                    }

                    if ($att->punch_out && $att->punch_out != "0000-00-00 00:00:00") {
                        $punch_out = $this->convertdate($att->punch_out);
                    }

                    $location  = $att->shortinaddress ?: "GPS off";
                    $location1 = $att->shortoutaddress ?: "GPS off";

                    if ($att->missing_punchin!=0 && $att->missing_punchout!=0 && $att->punch_regularised_status==1) {

                        $status = "Regularized Attendance";

                        if ($att->regular_status == 0) {
                            $sts = "Requested";
                        } elseif ($att->regular_status == 1) {
                            $sts = "Approved";
                        } else {
                            $sts = "Denied";
                        }
                    }
                }
            }

            $dayName = date('l', strtotime("$year-$month-$i"));
            $locText = "IN : $location - OUT : $location1";

            $objPHPExcel->getActiveSheet()->SetCellValue("A$row", $i);
            $objPHPExcel->getActiveSheet()->SetCellValue("B$row", $dayName);
            $objPHPExcel->getActiveSheet()->SetCellValue("C$row", $status);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$row", $punch_in, PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->SetCellValue("E$row", $punch_out);
            $objPHPExcel->getActiveSheet()->SetCellValue("F$row", $locText);
            $objPHPExcel->getActiveSheet()->SetCellValue("G$row", $sts);

            $objPHPExcel->getActiveSheet()
                ->getStyle("F$row")
                ->getAlignment()
                ->setWrapText(true);

            $row++;
        }

        $filename = "MonthlyReport_{$month}_{$year}.csv";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $writer->save('php://output');
        exit;
    }
    
    function getMyleaves()
    {
        $user_id = $this->input->post('user_id');
        $query = $this->db->query("select * from leave_transaction_all where user_id='" . $user_id . "' order by id desc");
        $data = "";
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $action_btn = '<button class="btn btn-link" type="button" onclick="Cancel_leave(' . $row->id . ',\'' . date('Y-m-d', strtotime($row->leave_date)) . '\')">Cancel Leave</button>';
                if ($row->status == 1) {
                    $status = 'Requested';

                } else if ($row->status == 2) {
                    $status = 'Approved';
                } else if ($row->status == 3) {
                    $status = 'Denied';
                } else {
                    $action_btn = '<button class="btn btn-link" type="button" disabled>Canceled</button>';
                    $status = 'Canceled';
                }

                $data .= '
		<tr>
		
		<td>' . date('d-m-Y', strtotime($row->leave_date)) . '</td>
		<td>' . $status . '</td>
		<td>' . $action_btn . '</td>
</tr>
		';
            }
            $response['status'] = 200;
            $response['data'] = $data;

        } else {
            $response['status'] = 201;
            $response['data'] = $data;
        }
        echo json_encode($response);
    }


    function cancelLeave() {
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $user_id = ($session_data['emp_id']);
        $id = $this->input->post('id');
        $date = $this->input->post('date');
        $where = array(
            "id" => $id
        );
        $set = array(
            // "status" => 4
            "status" => 0
        );
        $table_name = 'leave_transaction_all';
        $this->db->where($where);
        $u1 = $this->db->update($table_name, $set);
        if ($u1) {
            $where2 = array(
                "user_id" => $user_id,
                "date" => $date
            );
            $set2 = array(
                "leave_status" => 0,
                "leave_id" => "",
            );
            $table_name2 = 'employee_attendance_leave';
            $this->db->where($where2);
            $this->db->update($table_name2, $set2);
            $response['status'] = 200;
        } else {
            $response['status'] = 201;
        }
        echo json_encode($response);

    }
    public function approve_all_reg_req() {
        $session_data = $this->session->userdata('login_session');
        $user_id_login = ($session_data['emp_id']);
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        else {
            $firm_id = '';
        }
        // $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id_login'");
        // if ($this->db->affected_rows() > 0) {
        //     $res = $qr->row();
        //     $firm_id = $res->hr_authority;
        // } else {
        //     $firm_id = '';
        // }
        $res_response=false;
        $get_regular_data = $this->db->query("SELECT punch_in,punch_out,regular_status,id,user_id,punchin_lat,
                punchin_long,punchout_lat,punchout_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress
        from employee_attendance_leave where punch_regularised_status = 1 AND regular_status = 0 
        AND punch_in !=0 AND punch_out != 0 AND firm_id='$firm_id' order by punch_in desc");

        $get_data = $this->db->query("select missing_punchin,missing_punchout,reason,activity_status,id,user_id,punchin_lat,"
            . "punchin_long,punchout_lat,punchout_long,shortinaddress,shortoutaddress,longinaddress,longoutaddress"
            . " from employee_attendance_leave where activity_status=0 AND missing_punchin !=0 AND missing_punchout != 0 AND firm_id='$firm_id' order by missing_punchin desc");

        // if (count($get_regular_data->result()) > 0) {
            $res = $get_data->result();
            $mainArray=array();
            foreach ($res as $row) {
                $id=$row->id;
                $user_id=$row->user_id;
                $date=$row->missing_punchin;
                $day = date('l', strtotime($date));
                $check_holiday = $this->Globalmodel->check_Holiday($date, $user_id,$firm_id,$day);
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
                if ($check_holiday == FALSE) {
                    $holiday = 0; //NO holiday
                } else if ($check_holiday == 1) {
                    $holiday = 1; //Holiday
                } else {
                    if ($day == 'Sunday') {
                        $holiday = 1; //Holiday
                    } else {
                        $holiday = 0; //NO holiday
                    }
                }
                $get_data = $this->db->query("select missing_punchin,missing_punchout from employee_attendance_leave where id='$id'");
                if ($this->db->affected_rows() > 0) {
                    $res = $get_data->row();
                    $punchin = $res->missing_punchin;
                    $punchout = $res->missing_punchout;
                    $data = array(
                        'punch_in' => $punchin,
                        'punch_out' => $punchout,
                        'activity_status' => 3,
                        'is_holiday' => $holiday,
                        'holiday_approval_status' => $holiday_approval_status,
                        'regular_status' => 1,
                        'id'=>$id
                    );
                    array_push($mainArray,$data);
                }
            }
            // print_r($mainArray);exit();
            if(count($mainArray)>0)
            {
                $res_response= $this->db->update_batch('employee_attendance_leave', $mainArray, 'id');
            }

        // }

        if (count($get_regular_data->result()) > 0) {
            $res2 = $get_regular_data->result();
            $mdata=array();
            foreach ($res2 as $row) {
                $id=$row->id;
                $status=1;
                $rdata=array('regular_status'=>$status,'id'=>$id);
                array_push($mdata,$rdata);
                if(count($mdata)>0)
                {
                    $res_response= $this->db->update_batch('employee_attendance_leave', $mdata, 'id');
                }
            }
        }

        if($res_response==true) {
            $response['status']=200;
            $response['body']="Request Accepted Successfully";

        } else {
            $response['status']=201;
            $response['body']="Request Not Accepted";
        }
        echo json_encode($response);
    }
}
