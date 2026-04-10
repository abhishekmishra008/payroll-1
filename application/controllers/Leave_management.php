<?php

class Leave_management extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('firm_model');
        $this->load->model('designation_model');
		$this->load->model('Globalmodel');
        $this->load->helper('dump_helper');
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
        $data['prev_title'] = "Leave Management";
        $data['page_title'] = "Leave Management";
        $this->load->view('hq_admin/leave_management', $data);
    }

    public function leave_policy() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $email_id = ($session_data['user_id']);
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$user_id'");
            if ($result3->num_rows() > 0) {
                $record3 = $result3->row();
                $value_permit = $record3->leave_approve_permission;
                $data['val'] = $value_permit;
            } else {
                $data['val'] = '';
            }
        }

        $query_get_working_paper = $this->db->query("SELECT * from hr_working_paper where firm_id='$firm_id'");
        if ($query_get_working_paper->num_rows() > 0) {
            $record_working_ppr = $query_get_working_paper->result();
            $data['record_working_ppr'] = $record_working_ppr;
        } else {
            $data['record_working_ppr'] = "";
        }
        $query = $this->db->query("SELECT * FROM `user_header_all` where `firm_id`= '$firm_id' AND `email`='$email_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            $user_id = $record->user_id;
            $designation_id = $record->designation_id;
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

        $qur = $this->db->query("SELECT * from `designation_header_all` where `designation_id`='$designation_id'");
        // echo $this->db->last_query();
        //var_dump($qur);
        if ($this->db->affected_rows() > 0) {
            $record_desig = $qur->result();
        } else {
            $record_desig = "";
        }



        $qur1 = $this->db->query("SELECT * from `leave_header_all` where `designation_id`='$designation_id'");
        if ($qur1->num_rows() > 0) {
            $record_leave = $qur1->result();
        } else {
            $record_leave = "";
        }


        $qur2 = $this->db->query("SELECT COUNT(`user_id`) as 'user_cnt' FROM `leave_transaction_all` WHERE `user_id`='$user_id' AND `leave_pay_type`='0' GROUP BY `user_id`");
        if ($qur2->num_rows() > 0) {
            $recrd = $qur2->row();
            $with_pay_count = $recrd->user_cnt;
        } else {
            $with_pay_count = 0;
        }
        $data['record_desig'] = $record_desig;
        $data['with_pay_count'] = $with_pay_count;
        $data['record_leave'] = $record_leave;
        $data['prev_title'] = "Leave Policy";
        $data['page_title'] = "Leave Policy";
        $this->load->view('employee/leave_policy', $data);
    }

    public function leave_request() {
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $email_id = ($session_data['user_id']);
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' AND `email`='$email_id'");
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
        // $email_id = $this->session->userdata('login_session');
        // echo $email_id;
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $e_user_id = $record->user_id;
            $firm_id = $record->firm_id;
            $senior_id = $record->senior_user_id;
            $designation_id = $record->designation_id;


            $qur1 = $this->db->query("SELECT COUNT(`user_id`) as count , `firm_id` FROM `user_header_all` WHERE `firm_id`='$firm_id' GROUP BY `firm_id`");
            if ($qur1->num_rows() > 0) {
                $record1 = $qur1->row();
                $cnt = $record1->count;
            }
            $senior_emp_id = "";
            $senior_name = "";
            for ($i = 1; $i <= $cnt; $i++) {
                $qur2 = $this->db->query("SELECT user_name,senior_user_id FROM `user_header_all` WHERE `user_id`='$e_user_id' AND `firm_id`='$firm_id'");
                if ($qur2->num_rows() > 0) {
                    $record2 = $qur2->row();
                    $senior_id = $record2->senior_user_id;

                    // echo"SELECT * FROM `user_header_all` WHERE `user_id`='$senior_id' AND `firm_id`='$firm_id'";
                    $qur3 = $this->db->query("SELECT * FROM `user_header_all` WHERE `user_id`='$senior_id'");
                    if ($qur3->num_rows() > 0) {
                        $record3 = $qur3->row();
                        $leave_permission = $record3->leave_approve_permission;
                        if ($leave_permission == 0) {
                            $e_user_id = $record3->user_id;
                        } else {
                            $senior_emp_id = $record3->user_id;
                            $senior_name = $record3->user_name;
                        }
                    }
                }
            }

            $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$e_user_id'");
            if ($result3->num_rows() > 0) {
                $record3 = $result3->row();
                $value_permit = $record->leave_approve_permission;
                $data['val'] = $value_permit;
            } else {
                $data['val'] = '';
            }
        }
        // echo $user_id;
        $query_fetch_leave = $this->db->query("SELECT * FROM `leave_transaction_all` WHERE `user_id`='$e_user_id'");
        if ($query_fetch_leave->num_rows() > 0) {
            $record_fetch_leave = $query_fetch_leave->result();
        } else {
            $record_fetch_leave = '';
        }

        $data['user_id'] = $e_user_id;
        $data['firm_id'] = $firm_id;
        $data['result'] = $record_fetch_leave;
        $data['senior_id'] = $senior_emp_id;
        $data['senior_name'] = $senior_name;
        $data['designation_id'] = $designation_id;
        $data['prev_title'] = "Leave Request";
        $data['page_title'] = "Leave Request";
        $this->load->view('employee/leave_request', $data);
    }

    public function leave_approve() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $email_id = ($session_data['user_id']);
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
        $data['user_id'] = $user_id;
        $data['record_fetch'] = $record_fetch;
        $data['prev_title'] = "Leave Request";
        $data['page_title'] = "Leave Request";
        $this->load->view('employee/leave_approve', $data);
    }

    public function get_all_leave_requests() {
        // create by abhishek mishra
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        if(!is_null($this->input->post_get('emp_id'))){
            $date_str = $this->input->post_get('date') ? $this->input->post_get('date') : '';
            $date = explode('-', $date_str);
            $emp_id = $this->input->post_get('emp_id');
            if (count($date) >= 2 && !empty($emp_id)) {
                $month = isset($date[1]) ? $date[1] : '';
                $year = isset($date[0]) ? $date[0] : '';
                $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
                                        `user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,
                                        `leave_transaction_all`.`leave_requested_on`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`approved_deny_by`='$user_id' AND `leave_transaction_all`.`user_id`='$emp_id' AND month(leave_requested_on)='$month' AND year(leave_requested_on)='$year' ORDER BY leave_date DESC");
            } else {
                $fetch_data = false;
            }
        } else {

		}
        // create by abhishek mishra

        // this is old start
        // $session_data = $this->session->userdata('login_session');
        // $user_id = ($session_data['emp_id']);
        // $user_type = ($session_data['user_type']);
		// if(!is_null($this->input->post_get('emp_id'))){
		// 	$date= $this->input->post_get('date') ? $this->input->post_get('date') : '';
		// 	$date= explode('-',$date) ;
		// 	$emp_id=$this->input->post_get('emp_id');
		// 	        $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
        //                                 `user_header_all`.`senior_user_id`,
        //                                 `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,
        //                                 `leave_transaction_all`.`leave_requested_on`
        //                                 FROM `leave_transaction_all`
        //                                 INNER JOIN `user_header_all`
        //                                 ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
        //                                 WHERE `leave_transaction_all`.`approved_deny_by`='$user_id' AND `leave_transaction_all`.`user_id`='$emp_id' AND month(leave_requested_on)='$date[1]' AND year(leave_requested_on)='$date[0]' ORDER BY leave_date DESC");
		
		// } else {

		// }
        // this is old end

if($user_type == 5){
    $result1 = $this->customer_model->get_firm_id();
    if ($result1 !== false) {
        $firm_id = $result1['firm_id'];
    }
    $subqry=" SELECT `user_id` 
        FROM `user_header_all` 
        WHERE `firm_id` = '".$firm_id."'";
} else {
    $subqry=" SELECT `user_id` 
        FROM `user_header_all` 
        WHERE `senior_user_id` = '".$user_id."'";
}
        $fetch_data = $this->db->query("SELECT  
    `user_header_all`.`user_id`,
    `user_header_all`.`designation_id`,
    `user_header_all`.`user_name`,
    `user_header_all`.`senior_user_id`,
    `user_header_all`.`designation_id`,
    `leave_transaction_all`.`leave_type`,
    `leave_transaction_all`.`leave_id`,
    `leave_transaction_all`.`leave_requested_on`,
    `leave_transaction_all`.`leave_date`,
    `leave_transaction_all`.`status`,
    `leave_transaction_all`.`id`
FROM `leave_transaction_all` 
INNER JOIN `user_header_all`
    ON `user_header_all`.`user_id` = `leave_transaction_all`.`user_id`
WHERE 
    `leave_transaction_all`.`user_id` IN (
      ".$subqry."
    )
    AND `leave_transaction_all`.`status` != 4 
    AND `user_header_all`.`activity_status` = 1   
ORDER BY 
    FIELD(`leave_transaction_all`.`status`, 1, 2, 3, 4), 
    `leave_transaction_all`.`leave_date` DESC");

        // echo $this->db->last_query();die;
        //echo $this->db->last_query();
		$data = '';
        if ($fetch_data->num_rows() > 0) {
            $record_fetch = $fetch_data->result();

            foreach ($record_fetch as $row) {
				if ($row->status == '1') {
					$status = " <span class='label label-sm label-info'> Requested </span>";
				} else if ($row->status == '2') {
					$status = "<span class='label label-sm label-success'> Approved </span>";
				} else if ($row->status == '3') {
					$status = " <span class='label label-sm label-warning'> Denied </span>";
				} else {
					$status = "<span class='label label-sm label-danger'> Canceled </span>";
				}
                $approve = '<button type="button" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave"  onclick="approve_leave(\'' . $row->id . '\',\'' . $row->leave_id . '\');" class="btn btn-link btn-icon-only"><i class="fa fa-check font-green"></i></button>';
                $deny = '<button type="button" class="btn btn-link btn-icon-only" data-toggle="modal" title="Deny!" name="deny_leave" id="deny_leave" data-target="#myModal" data-myvalue="' . $row->id . '" data-myLeaveID="' . $row->leave_id . '"><i class="fa fa-close text-danger"></i></button>';

				// $approve = '<a class="btn btn-circle green btn-icon-only btn-default"  data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave('.$row->id.',\''.$row->leave_id.'\');"><i class="fa fa-check"></i></a>';
				// $deny = '<a class="btn btn-circle red btn-icon-only btn-default" data-toggle="modal"title="Deny!" name="deny_leave" id="deny_leave" data-target="#myModal" data-myvalue="'.$row->id.'" data-myLeaveID="'.$row->leave_id.'"><i class="fa fa-close"></i></a>';
                /*$btn = '<button id="sample_1_new" class="btn-info btn-simple btn blue-hoki btn-outline sbold uppercase popovers"
                    data-toggle="modal" data-target="#view_leave_details" data-container="body"
                    data-placement="left" data-trigger="hover" data-content="See Details" data-original-title="" title=""
                     data-desig_id="' . $row->designation_id . '"
                    data-leave_type1="' . $row->leave_type . '"
                    data-emp_user_id="' . $row->user_id . '"
                    data-leave_id="' . $row->leave_id . '">
                    <i class="fa fa-eye"></i>
                    </button>';*/
                $data .= '<tr>
                    <td>' . $row->user_name . '</td>
                    <td>' . date('d-m-Y',strtotime($row->leave_date)) . '</td>
                    <td>' . $status . '</td>
                    <td>' . $approve. $deny. '</td>

                       </tr>';
            }
            $response['message'] = 'success';
            $response['result'] = $data;
        } else {
            $response['message'] = 'fail';
            $response['result'] = $data;
        }echo json_encode($response);
    }

    public function leave_request_ca() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $email_id = $this->session->userdata('login_session');
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
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
//        $email_id = $this->session->userdata('login_session');
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
        $query_fetch_boss_id = $this->db->query("SELECT `reporting_to` FROM partner_header_all where firm_id='$firm_id'");
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
                    $response['leave'] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
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
        $this->load->view('client_admin/leave_request', $data);
    }

    public function leave_approve_ca() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $email_id = $this->session->userdata('login_session');
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
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
        //echo "SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,`user_header_all`.`senior_user_id`,
//                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,
//                                        `leave_transaction_all`.`leave_requested_on`
//                                        FROM `leave_transaction_all`
//                                        INNER JOIN `user_header_all`
//                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
//                                        WHERE `leave_transaction_all`.`approved_deny_by`='$user_id' ORDER BY leave_date DESC";
        $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,`user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,
                                        `leave_transaction_all`.`leave_requested_on`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`approved_deny_by`='$user_id' ORDER BY leave_date DESC");
        if ($fetch_data->num_rows() > 0) {
            $record_fetch = $fetch_data->result();
        } else {
            $record_fetch = '';
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
                    $response['leave'] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
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
        $data['record_fetch'] = $record_fetch;
        $data['prev_title'] = "Leave Request";
        $data['page_title'] = "Leave Request";
        $this->load->view('client_admin/leave_approve', $data);
    }

    public function leave_approve_hq($firm_id = '') {

        $email_id = $this->session->userdata('login_session');
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
//            $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$user_id'");
//            if ($result3->num_rows() > 0) {
//                $record3 = $result3->row();
//                $value_permit = $record->leave_approve_permission;
//                $data['val'] = $value_permit;
//            } else {
//                $data['val'] = '';
//            }
        }

        $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,`user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,
                                        `leave_transaction_all`.`leave_requested_on`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `user_header_all`.`senior_user_id`='$user_id' OR `leave_transaction_all`.`approved_deny_by`='$user_id' AND `user_header_all`.`firm_id`='$firm_id' ORDER BY leave_date DESC ");
        if ($fetch_data->num_rows() > 0) {
            $record_fetch = $fetch_data->result();
        } else {
            $record_fetch = '';
        }
        $data['user_id'] = $user_id;
        $data['record_fetch'] = $record_fetch;


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
        $data['prev_title'] = "Leave Request";
        $data['page_title'] = "Leave Request";
        $this->load->view('hq_admin/leave_approve', $data);
    }

    public function get_type_leave() {
        $designation_id = $this->input->post('designation_id');
        $user_id = $this->input->post('user_id');
        $today_date = date("Y-m-d H:i:s");
        $query = $this->db->query("SELECT * FROM `leave_header_all` WHERE `designation_id`= '$designation_id'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data['type1'] = $row->type1;
                $data['type2'] = $row->type2;
                $data['type3'] = $row->type3;
                $data['type4'] = $row->type4;
                $data['type5'] = $row->type5;
                $data['type6'] = $row->type6;
                $data['type7'] = $row->type7;
                $response['leave_type_data'][] = ['type1' => $row->type1, 'type2' => $row->type2, 'type3' => $row->type3,
                    'type4' => $row->type4, 'type5' => $row->type5, 'type6' => $row->type6, 'type7' => $row->type7,];
//                var_dump($response);
            }

            $qur1 = $this->db->query("SELECT * from designation_header_all where designation_id='$designation_id'");
            if ($qur1->num_rows() > 0) {
                $rec1 = $qur1->row();
                $leave_apply_after = $rec1->request_leave_from;

                if ($leave_apply_after == 1) {
                    $response['message'] = 'success';
                } elseif ($leave_apply_after == 2) {
//                    echo "SELECT * from user_header_all where user_id='$user_id'";
                    $qur2 = $this->db->query("SELECT * from user_header_all where user_id='$user_id'");
                    if ($qur2->num_rows() > 0) {
                        $rec2 = $qur2->row();
                        $prob_date_end = $rec2->probation_period_end_date;

                        if (strtotime($prob_date_end) > strtotime($today_date)) {
                            $response['message'] = 'after_prob';
                            $response['prob_date'] = $prob_date_end;
                        } else {
                            $response['message'] = 'success';
                        }
                    }
                } elseif ($leave_apply_after == 3) {
                    $qur3 = $this->db->query("SELECT * from user_header_all where user_id='$user_id'");
                    if ($qur3->num_rows() > 0) {
                        $rec3 = $qur3->row();
                        $training_date_end = $rec3->training_period_end_date;
                        if (strtotime($training_date_end) > strtotime($today_date)) {
                            $response['message'] = 'after_train';
                            $response['train_date'] = $training_date_end;
                        } else {
                            $response['message'] = 'success';
                        }
                    }
                } else {
                    $qur4 = $this->db->query("SELECT * from user_header_all where user_id='$user_id'");
                    if ($qur4->num_rows() > 0) {
                        $rec4 = $qur4->row();
                        $confirm_date = $rec4->confirmation_date;
                        if ($confirm_date != 0) {
                            $response['message'] = 'after_con';
                            $response['comp_date'] = $confirm_date;
                        } else {
                            $response['message'] = 'after_con';
                        }
                    }
                }
            }
            $qur1 = $this->db->query("SELECT total_yearly_leaves from designation_header_all where designation_id = '$designation_id' ");
            if ($qur1->num_rows() > 0) {
                $recrd1 = $qur1->row();
                $total_yearly_leaves = $recrd1->total_yearly_leaves;
            } else {
                $total_yearly_leaves = "";
            }


            $qur2 = $this->db->query("SELECT COUNT(`user_id`) as 'user_cnt' FROM `leave_transaction_all` WHERE `user_id`='$user_id' AND `leave_pay_type`='0' AND status= 2 GROUP BY `user_id`");
            if ($qur2->num_rows() > 0) {
                $recrd = $qur2->row();
                $with_pay_count = $recrd->user_cnt;
            } else {
                $with_pay_count = 0;
            }

            $remaining_with_pay_leaves = $total_yearly_leaves - $with_pay_count;

            $response['message'] = 'success';
            $response['remaining_with_pay_leaves'] = $remaining_with_pay_leaves;
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_type_leave_ca() {
        $firm_id = $this->input->post('firm_id');
        $boss_id = $this->input->post('boss_id');
        $user_id = $this->input->post('user_id');
        $query = $this->db->query("SELECT * FROM `leave_header_all` WHERE `designation_id`= 'CA' AND firm_id='$firm_id'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data['type1'] = $row->type1;
                $data['type2'] = $row->type2;
                $data['type3'] = $row->type3;
                $data['type4'] = $row->type4;
                $data['type5'] = $row->type5;
                $data['type6'] = $row->type6;
                $data['type7'] = $row->type7;
                $response['leave_type_data'][] = ['type1' => $row->type1, 'type2' => $row->type2, 'type3' => $row->type3,
                    'type4' => $row->type4, 'type5' => $row->type5, 'type6' => $row->type6, 'type7' => $row->type7,];
                    // var_dump($response);
            }
            $qur1 = $this->db->query("SELECT * from designation_header_all where designation_id='CA'");
            if ($qur1->num_rows() > 0) {
                $rec1 = $qur1->row();
                $leave_apply_after = $rec1->request_leave_from;

                if ($leave_apply_after == 1) {
                    $response['message'] = 'success';
                } elseif ($leave_apply_after == 2) {
                    // echo "SELECT * from user_header_all where user_id='$user_id'";
                    $qur2 = $this->db->query("SELECT * from user_header_all where user_id='$user_id'");
                    if ($qur2->num_rows() > 0) {
                        $rec2 = $qur2->row();
                        $prob_date_end = $rec2->probation_period_end_date;

                        if (strtotime($prob_date_end) > strtotime($today_date)) {
                            $response['message'] = 'after_prob';
                            $response['prob_date'] = $prob_date_end;
                        } else {
                            $response['message'] = 'success';
                        }
                    }
                } elseif ($leave_apply_after == 3) {
                    $qur3 = $this->db->query("SELECT * from user_header_all where user_id='$user_id'");
                    if ($qur3->num_rows() > 0) {
                        $rec3 = $qur3->row();
                        $training_date_end = $rec3->training_period_end_date;
                        if (strtotime($training_date_end) > strtotime($today_date)) {
                            $response['message'] = 'after_train';
                            $response['train_date'] = $training_date_end;
                        } else {
                            $response['message'] = 'success';
                        }
                    }
                } else {
                    $qur4 = $this->db->query("SELECT * from user_header_all where user_id='$user_id'");
                    if ($qur4->num_rows() > 0) {
                        $rec4 = $qur4->row();
                        $confirm_date = $rec4->confirmation_date;
                        if ($confirm_date != 0) {
                            $response['message'] = 'after_con';
                            $response['comp_date'] = $confirm_date;
                        } else {
                            $response['message'] = 'after_con';
                        }
                    }
                }
            }

            $qur1 = $this->db->query("SELECT total_yearly_leaves from designation_header_all where designation_id = 'CA' AND firm_id='$firm_id' ");
            if ($qur1->num_rows() > 0) {
                $recrd1 = $qur1->row();
                $total_yearly_leaves = $recrd1->total_yearly_leaves;
            } else {
                $total_yearly_leaves = "";
            }

            // echo "SELECT COUNT(`user_id`) as 'user_cnt' FROM `leave_transaction_all` WHERE `user_id`='$user_id' AND `leave_pay_type`='0' GROUP BY `user_id`";
            $qur2 = $this->db->query("SELECT COUNT(`user_id`) as 'user_cnt' FROM `leave_transaction_all` WHERE `user_id`='$user_id' AND `leave_pay_type`='0' AND status= 2 GROUP BY `user_id`");
            if ($qur2->num_rows() > 0) {
                $recrd = $qur2->row();
                $with_pay_count = $recrd->user_cnt;
            } else {
                $with_pay_count = 0;
            }

            $remaining_with_pay_leaves = $total_yearly_leaves - $with_pay_count;
            $response['remaining_with_pay_leaves'] = $remaining_with_pay_leaves;

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

    public function get_leave_status_datewise() {
        $user_id = $this->input->post('user_id');
        $leave_date = $this->input->post('leave_date_selected');
        // Validate leave_date: must be a valid date (not 0, not empty, not unwanted)
        if (empty($leave_date) || $leave_date === '0' || $leave_date === 0 || !strtotime($leave_date)) {
            $response['message'] = 'Invalid date';
            $response['leave_status'] = '';
            echo json_encode($response);
            return;
        } else {
            $leave_date_selected = $leave_date;
        }
        $query_check_leave = $this->db->query("select user_id, id from leave_transaction_all where date(leave_date)='$leave_date_selected' and user_id='$user_id' and status != '4'");
		
        if ($this->db->affected_rows() > 0) {
            $res = $query_check_leave->row();
            $leave = '<h5>You have Requested Leave on ' . $leave_date_selected . '. you can cancel that leave by cliking on cancel leave button.</h5>'
                    . '<button type="button" id="cancel_leave" name="cancel_leave" onclick="cancel_leave_a(\'' . $res->id . '\',\'' . $leave_date_selected . '\')" class="btn btn-danger">Cancel Leave</button>';
            $response['message'] = 'success';
            $response['leave_status'] = $leave;
        } else {
            $leave = '';
            $response['message'] = 'No data to display';
            $response['leave_status'] = $leave;
        }
        echo json_encode($response);
    }

    public function get_type_leave_hr() {
        $firm_id = $this->input->post('firm_id');
        $user_id = $this->input->post('user_id'); //email
		$session_data = $this->session->userdata('login_session');
		$user_id_lgn = ($session_data['emp_id']);

        $designation_id = $this->input->post('designation_id');
        $today_date = date("Y-m-d");


        $query = $this->db->query("SELECT type1,type2,type3,type4,type5,type6,type7 FROM `user_header_all` WHERE `user_id`= '$user_id_lgn'");
        if ($this->db->affected_rows() > 0) {
            foreach ($query->result() as $row) {
                $data['type1'] = $row->type1;
                $data['type2'] = $row->type2;
                $data['type3'] = $row->type3;
                $data['type4'] = $row->type4;
                $data['type5'] = $row->type5;
                $data['type6'] = $row->type6;
                $data['type7'] = $row->type7;
                $response['leave_type_data'][] = ['type1' => $row->type1, 'type2' => $row->type2, 'type3' => $row->type3,
                    'type4' => $row->type4, 'type5' => $row->type5, 'type6' => $row->type6, 'type7' => $row->type7,];
                //                var_dump($response);
            }

            $session_data = $this->session->userdata('login_session');

            $data['session_data'] = $session_data;
            $emp_id = ($session_data['emp_id']);


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

    public function check_leave_taken($leave_date, $user_id) {
        $query = $this->db->query("select user_id from leave_transaction_all where leave_date='$leave_date' AND user_id='$user_id' AND status != 4");
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function create_leave_req() {
        $leave_id = $this->generate_leave_id();
        $leave_type = $this->input->post('leave_type');
        $day_type = $this->input->post('day_type');
        $leave_date_single = $this->input->post('leave_date_single');
        $senior_id = $this->input->post('senior_id');
        $leave_date_multiple_first = $this->input->post('leave_date_multiple_first');
        $leave_date_multiple_second = $this->input->post('leave_date_multiple_second');
        $leave_requested_on = date('y-m-d');
        $today_date = date("Y-m-d");

		$firm_id = '';
		$user_id = '';
        $result = $this->firm_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
            $boss_id = $result['boss_id'];
            $user_id = $result['user_id'];
        }
        if (empty($leave_type)) {
            $response['id'] = 'leave_type';
            $response['error'] = 'Please Select Leave Type';
            echo json_encode($response);
            exit();
        }

		$emails = array();
		$getSeniorEmailData = $this->Globalmodel->getData('*', array('user_id' => $senior_id), 'user_header_all');
		if ($getSeniorEmailData != null) {
			$emails[] = $getSeniorEmailData->email;
		}

		$getHRData = $this->Globalmodel->getData('*', array('firm_id' => $firm_id, 'user_type' => 5), 'user_header_all');
		if ($getHRData != null) {
			$emails[] = $getHRData->email;
		}

		$username = '';
		$userData = $this->Globalmodel->getData('*', array('user_id' => $user_id), 'user_header_all');
		if ($userData != null) {
			$username = $userData->user_name;
		}

        if ($day_type == 0) {
            if (empty($leave_date_single)) {
                $response['id'] = 'leave_date_single';
                $response['error'] = 'Please Select Date';
                echo json_encode($response);
                exit();
            } else {
                $leave_date = $leave_date_single;
                $check_leave_taken = $this->check_leave_taken($leave_date, $user_id);
                if ($check_leave_taken == TRUE) {
                    $response['id'] = 'leave_date_single';
                    $response['error'] = 'You have alredy Applied leave on ' . $leave_date;
                    echo json_encode($response);
                    exit();
                }

				$leavePayType = 0;
				$checkifProbation = $this->db->query('SELECT * FROM user_header_all where user_id = "'.$user_id.'" 
                and "'.$leave_date.'" between probation_period_start_date and probation_period_end_date');

				if ($checkifProbation->num_rows() > 0) {
					$leavePayType = 1;
				}

                $data = array(
                    'leave_id' => $leave_id,
                    'firm_id' => $firm_id,
                    'boss_id' => $boss_id,
                    'user_id' => $user_id,
                    'leave_type' => $leave_type,
                    'leave_requested_on' => $leave_requested_on,
                    'approved_deny_by' => $senior_id,
                    'leave_date' => $leave_date,
                    'status' => 1,
                    'leave_pay_type' => $leavePayType
                );
                $add_leave = $this->designation_model->add_leave_rqst($data);
                if ($add_leave == TRUE) {
                    $check_alreday_login = $this->db->query("select date from employee_attendance_leave where date ='$leave_date' AND `user_id`= '$user_id'");
                    if ($this->db->affected_rows() > 0) {
                        $this->db->query("UPDATE `employee_attendance_leave` SET `leave_status` = 1,`leave_id` = '$leave_id' WHERE `user_id`= '$user_id' && date ='$leave_date'");
                    } else {
                        $data1 = array(
                            'firm_id' => $firm_id,
                            'leave_id' => $leave_id,
                            'user_id' => $user_id,
                            'date' => $leave_date,
                            'leave_status' => 1
                        );
                        $add_leave_attendance = $this->db->insert("employee_attendance_leave", $data1);
                    }

					$subject = 'Application for Leave';

					$LT = 'Sick Leave';
					if($leave_type == 'PL'){
						$LT = 'Paid Leave';
					}else if ($leave_type == 'CL'){
						$LT = 'Casual Leave';
					}

                    //					$message = '
                    //					<html>
                    //					<head></head>
                    //					<body>
                    //					<p>
                    //					Dear Manager / HR,
                    //					</p>
                    //					<br>
                    //					<p> <b>'.$username.'</b> has applied for '.$LT.' on '.date("F jS, Y", strtotime($leave_date)).'.</p>
                    //					<br>
                    //					<p>Regards,<br>
                    //					RMT Team</p>
                    //					</body>
                    //					</html>
                    //					';

                    //					if (count($emails) > 0) {
                    //						$mailDetails = $this->Globalmodel->sendMail(implode(',', $emails), $subject, $message);
                    //						$response['mailDetails'] = $mailDetails;
                    //					}


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
        if ($day_type == 1) {

            if (empty($leave_date_multiple_first)) {
                $response['id'] = 'leave_date_multiple_first';
                $response['error'] = 'Please Select From Date';
                echo json_encode($response);
                exit();
            } else if (empty($leave_date_multiple_second)) {
                $response['id'] = 'leave_date_multiple_second';
                $response['error'] = 'Please Select To Date';
                echo json_encode($response);
                exit();
            } else {
                $date_from1 = date("Y-m-d", strtotime($leave_date_multiple_first));
                $date_to1 = date("Y-m-d", strtotime($leave_date_multiple_second));
                $k = 1;
                $j = 1;
                $diff1 = date_diff(date_create($date_from1), date_create($date_to1));
                $date_diff1 = $diff1->format("%R%a") + 1;
                for ($i = 1; $i <= $date_diff1; $i++) {
                    $check_leave_taken = $this->check_leave_taken($date_from1, $user_id);
                    if ($check_leave_taken == TRUE) {
                        $response['id'] = 'leave_date_multiple_first';
                        $response['error'] = 'You have alredy Applied leave on ' . $date_from1;
                        echo json_encode($response);
                        exit();
                    }
                    $date_from1 = date('Y-m-d', strtotime($date_from1 . ' + 1 days'));
                }
                $date_from = date("Y-m-d", strtotime($leave_date_multiple_first));
                $date_to = date("Y-m-d", strtotime($leave_date_multiple_second));
                $diff = date_diff(date_create($date_from), date_create($date_to));
                $date_diff = $diff->format("%R%a") + 1;
                for ($i = 1; $i <= $date_diff; $i++) {

                    $data = array(
                        'leave_id' => $leave_id,
                        'firm_id' => $firm_id,
                        'boss_id' => $boss_id,
                        'user_id' => $user_id,
                        'leave_type' => $leave_type,
                        'leave_requested_on' => $leave_requested_on,
                        'approved_deny_by' => $senior_id,
                        'leave_date' => $date_from,
                        'status' => 1
                    );
                    $add_leave = $this->designation_model->add_leave_rqst($data);
                    if ($add_leave == TRUE) {
                        $k++;

                        $check_alreday_login = $this->db->query("select date from employee_attendance_leave where date ='$date_from' AND `user_id`= '$user_id'");
                        if ($this->db->affected_rows() > 0) {
                            $result = $this->db->query("UPDATE `employee_attendance_leave` SET `leave_status` = 1 WHERE `user_id`= '$user_id' && date ='$date_from'");
                            if ($this->db->affected_rows() > 0) {
                                $response['message'] = 'success';
                                $response['code'] = 200;
                                $response['status'] = true;
                            } else {
                                $response['message'] = 'No data to display';
                                $response['code'] = 204;
                                $response['status'] = false;
                            }
                        } else {
                            $data1 = array(
                                'firm_id' => $firm_id,
                                'user_id' => $user_id,
                                'date' => $date_from,
                                'leave_status' => 1
                            );
                            $add_leave_attendance = $this->db->insert("employee_attendance_leave", $data1);
                        }
                    }
                    $date_from = date('Y-m-d', strtotime($date_from . ' + 1 days'));
                }


				$subject = 'Application for Leave';

				$LT = 'Sick Leave';
				if($leave_type == 'PL'){
					$LT = 'Paid Leave';
				}else if ($leave_type == 'CL'){
					$LT = 'Casual Leave';
				}

				$message = '
					<html>
					<head></head>
					<body>
					<p>
					Dear Manager / HR,
					</p>
					<br>
					<p> <b>'.$username.'</b> has applied for '.$LT.' from '.date("F jS, Y", strtotime($leave_date_multiple_first)).' 
					to '.date("F jS, Y", strtotime($leave_date_multiple_second)).'.</p>
					<br>
					<p>Regards,<br>
					RMT Team</p>
					</body>
					</html>
					';

				if (count($emails) > 0) {
					$mailDetails = $this->Globalmodel->sendMail(implode(',', $emails), $subject, $message);
					$response['mailDetails'] = $mailDetails;
				}
                if ($k > 1) {
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

    public function generate_leave_idOLD() {
        $leave_id = 'Leave_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('leave_transaction_all');
        $this->db->where('leave_id', $leave_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
			return $this->generate_leave_id();
        } else {
            return $leave_id;
        }
    }
    public function generate_leave_id() {
    do {
        $leave_id = 'Leave_' . rand(100000, 999999); // bigger range to reduce collision
        $this->db->select('leave_id');
        $this->db->from('leave_transaction_all');
        $this->db->where('leave_id', $leave_id);
        $query = $this->db->get();
    } while ($query->num_rows() > 0);

    return $leave_id;
    }

    public function fetch_remain_leave() {

        $leave_type = $this->input->post('leave_type');
        $designation_id = $this->input->post('designation_id');
        $firm_id = $this->input->post('firm_id');
        $user_id = $this->input->post('user_id');

        $query_to_fetch_type = $this->db->query("SELECT * from `leave_header_all` where `firm_id`='$firm_id' AND `designation_id`='$designation_id'");
        if ($query_to_fetch_type->num_rows() > 0) {

            $query_count_leave = $this->db->query("SELECT COUNT(`leave_type`) as taken_leaves FROM `leave_transaction_all` WHERE `leave_type`='$leave_type' AND `user_id`='$user_id' GROUP BY `leave_type`");
            if ($query_count_leave->num_rows() > 0) {
                $record_count = $query_count_leave->result();

                foreach ($record_count as $row5) {

                    $taken_leaves = $row5->taken_leaves;
                }
                $response['taken_leaves'] = $taken_leaves;
            } else {
                $taken_leaves = 0;
                $response['taken_leaves'] = '';
            }
            $record = $query_to_fetch_type->row();
            for ($i = 1; $i <= 7; $i++) {
                $ty = "type" . $i;
                $type = $record->$ty;
                if ($type !== "") {
                    $typ1 = explode(":", $type);
                    $type_leave = $typ1[0];
                    $days = $typ1[1];
                    $req_bfr = $typ1[2];
                    $aprv_bfr = $typ1[3];
                } else {
                    $typ1 = '';
                    $type_leave = '';
                    $days = '';
                }
                if ($type_leave == $leave_type) {
                    $total_leave = $days;
                    $response['taken_leaves'] = $taken_leaves;
                    $response['total_leaves'] = $total_leave;
                    $response['req_bfr'] = $req_bfr;
                    $response['aprv_bfr'] = $aprv_bfr;
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                }
            }
        } else {

        }echo json_encode($response);
    }

    public function fetch_remain_leave_ca() {

        $leave_type = $this->input->post('leave_type');
        //        $designation_id = $this->input->post('designation_id');
        $firm_id = $this->input->post('firm_id');
        $user_id = $this->input->post('user_id');

        $query_to_fetch_type = $this->db->query("SELECT * from `leave_header_all` where `firm_id`='$firm_id' AND `designation_id`='CA'");
        if ($query_to_fetch_type->num_rows() > 0) {

            $query_count_leave = $this->db->query("SELECT COUNT(`leave_type`) as taken_leaves FROM `leave_transaction_all` WHERE `leave_type`='$leave_type' AND `user_id`='$user_id' GROUP BY `leave_type`");
            if ($query_count_leave->num_rows() > 0) {
                $record_count = $query_count_leave->result();

                foreach ($record_count as $row5) {

                    $taken_leaves = $row5->taken_leaves;
                }
                $response['taken_leaves'] = $taken_leaves;
            } else {
                $taken_leaves = 0;
                $response['taken_leaves'] = '';
            }
            $record = $query_to_fetch_type->row();
            for ($i = 1; $i <= 7; $i++) {
                $ty = "type" . $i;
                $type = $record->$ty;
                if ($type !== "") {
                    $typ1 = explode(":", $type);
                    $type_leave = $typ1[0];
                    $days = $typ1[1];
                    $req_bfr = $typ1[2];
                    $aprv_bfr = $typ1[3];
                } else {
                    $typ1 = '';
                    $type_leave = '';
                    $days = '';
                }
                if ($type_leave == $leave_type) {
                    $total_leave = $days;
                    $response['taken_leaves'] = $taken_leaves;
                    $response['total_leaves'] = $total_leave;
                    $response['req_bfr'] = $req_bfr;
                    $response['aprv_bfr'] = $aprv_bfr;
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                }
            }echo json_encode($response);
        } else {

        }
    }

    function get_total_emp_leave() {
        $session_data = $this->session->userdata('login_session');
        $emp_id = ($session_data['emp_id']);
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $login_firm_id = $result['firm_id'];
        }

        $query = $this->db->query("select MONTH(user_header_all.date_of_joining) as month,user_header_all.designation_id,"
                . "(select partner_header_all.accural_month from partner_header_all where partner_header_all.firm_id='$login_firm_id') as accrual_month"
                . " from user_header_all where user_header_all.user_id='$emp_id'");
        if ($this->db->affected_rows() > 0) {
            $resss = $query->row();
            $designation_id = $resss->designation_id;
            $date_of_joining_mon = $resss->month;
            $accrual_month = $resss->accrual_month;
            $qur1 = $this->db->query("SELECT total_monthly_leaves from designation_header_all where designation_id = '$designation_id' AND firm_id='$login_firm_id'");
            if ($qur1->num_rows() > 0) {
                $recrd1 = $qur1->row();
                $total_monthly_leaves = $recrd1->total_monthly_leaves;
            } else {
                $total_monthly_leaves = "";
            }

            if ($accrual_month < $date_of_joining_mon) {
                $rem_mon1 = ($date_of_joining_mon - $accrual_month);
                $rem_mon = (12 - $rem_mon1) - 1;
            } else if ($accrual_month > $date_of_joining_mon) {
                $rem_mon = ($accrual_month - $date_of_joining_mon) - 1;
            } else {
                $rem_mon = 12;
            }
            $total_leave = $rem_mon * $total_monthly_leaves;

            $response['message'] = 'success';
        } else {
            $response['message'] = 'success';
        }echo json_encode($response);
    }

    public function fetch_remain_leave_hr() {

        $leave_type = $this->input->post('leave_type');
        $designation_id = $this->input->post('designation_id');
        $firm_id = $this->input->post('firm_id');
        $user_id = $this->input->post('user_id');
        $session_data = $this->session->userdata('login_session');
        $emp_id = ($session_data['emp_id']);

        $query_to_fetch_type = $this->db->query("SELECT * from `leave_header_all` where `firm_id`='$firm_id' AND `designation_id`='$designation_id'");
        if ($query_to_fetch_type->num_rows() > 0) {

            $query_count_leave = $this->db->query("SELECT COUNT(`leave_type`) as taken_leaves FROM `leave_transaction_all` WHERE `leave_type`='$leave_type' AND `user_id`='$emp_id' AND `leave_pay_type`='0' GROUP BY `leave_type`");
            if ($query_count_leave->num_rows() > 0) {
                $record_count = $query_count_leave->result();

                foreach ($record_count as $row5) {

                    $taken_leaves = $row5->taken_leaves;
                }
                $response['taken_leaves'] = $taken_leaves;
            } else {
                $taken_leaves = 0;
                $response['taken_leaves'] = '';
            }
            $record = $query_to_fetch_type->row();
            for ($i = 1; $i <= 7; $i++) {
                $ty = "type" . $i;
                $type = $record->$ty;
                if ($type !== "") {
                    $typ1 = explode(":", $type);
                    $type_leave = $typ1[0];
                    $days = $typ1[1];
                    $req_bfr = $typ1[2];
                    $aprv_bfr = $typ1[3];
                } else {
                    $typ1 = '';
                    $type_leave = '';
                    $days = '';
                }
                if ($type_leave == $leave_type) {
                    $total_leave = $days;
                    $response['taken_leaves'] = $taken_leaves;
                    $response['total_leaves'] = $total_leave;
                    $response['req_bfr'] = $req_bfr;
                    $response['aprv_bfr'] = $aprv_bfr;
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                }
            }echo json_encode($response);
        } else {

        }
    }

    public function delete_leave() {

        $id = $this->input->post('leave_id');
        $query = $this->db->query("DELETE from `leave_transaction_all` where id='$id'");
        if ($this->db->affected_rows() === 1) {
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

    public function cancl_leave() {
        $id = $this->input->post('leave_id');
        $date = $this->input->post('date');
        $user_id = $this->input->post('user_id');
        $query = $this->db->query("UPDATE `leave_transaction_all` SET `status`='4' where id='$id'");
        if ($this->db->affected_rows() > 0) {
            $this->db->query("UPDATE `employee_attendance_leave` SET `leave_status`='0' where user_id='$user_id' AND date='$date'");
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

	public function approve_emp_leave() {
		$id = $this->input->post('leave_id');
		$leave_id = $this->input->post('leave_id_act');
		$leave_updated_on = date('y-m-d h:i:s');
		$query = $this->db->query("UPDATE leave_transaction_all SET status='2', leave_approve_deny_on='$leave_updated_on' where id='$id' ");
		if ($this->db->affected_rows() > 0) {
			if($leave_id != ""){
				$this->db->query("UPDATE employee_attendance_leave SET leave_status='2' where leave_id='$leave_id'");

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

    public function deny_emp_leave() {
        $id = $this->input->post('leave_id');
        $leave_updated_on = date('y-m-d h:i:s');
        $leave_id = $this->input->post('leave_id_act');
        $query = $this->db->query("UPDATE `leave_transaction_all` SET `status`='3' ,leave_approve_deny_on='$leave_updated_on' where id='$id'  ");
        if ($this->db->affected_rows() > 0) {
        	if($leave_id != ""){
				$this->db->query("UPDATE `employee_attendance_leave` SET `leave_status`='3' where leave_id='$leave_id'");
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

    public function get_emp_leave_data() {
        $leaveid = $this->input->post('leave_id');
        $emp_user_id = $this->input->post('emp_user_id');
        $leave_type = $this->input->post('leave_type1');
        $desig_id = $this->input->post('desig_id');
        $curr_date = date('y-m-d h:i:s');
        $today = strtotime($curr_date);


        $query4 = $this->db->query("SELECT * from `leave_transaction_all` where `leave_id`='$leaveid' ORDER BY leave_date DESC");
        if ($query4->num_rows() > 0) {
            $record = $query4->result();

            //            $qur = $this->db->query("SELECT * from leave_header_all where designation_id='$desig_id'");
            //            if ($qur->num_rows() > 0) {
            //
            //                $records = $qur->row();
            //
            //
            //                for ($i = 1; $i <= 7; $i++) {
            //                    $ty = "type" . $i;
            //                    $type = $records->$ty;
            //                    if ($type !== "") {
            //                        $typ1 = explode(":", $type);
            //                        $type_leave = $typ1[0];
            //                        $aprv_bfr = $typ1[3];
            //                    } else {
            //                        $typ1 = '';
            //                        $type_leave = '';
            //                        $days = '';
            //                    }
            //                    if ($type_leave == $leave_type) {
            //                        $approve_before = $aprv_bfr;
            //                        if ($query4->num_rows() === 1) {
            //                            $rec = $query4->row();
            //                            $leave_date = $rec->leave_date;
            //                            $leave_date_srtrto = strtotime($leave_date);
            ////                            $days_ago = date('Y-m-d', strtotime('-' . $approve_before . 'days', strtotime($leave_date)));
            ////                            if (strtotime($days_ago) < $today) {
            ////                                $response['leave_aprv_validation'] = '1';
            ////                            } else {
            ////                                $response['leave_aprv_validation'] = '0';
            ////                            }
            //                        } else {
            //                            $approve_before1 = $aprv_bfr;
            //                            $rec1 = $query4->row();
            //                            $leave_id = $rec1->leave_id;
            //                            $query_last_date = $this->db->query("SELECT * FROM `leave_transaction_all` WHERE leave_id = '$leave_id'
            //                                                                ORDER BY `leave_transaction_all`.`leave_date` DESC LIMIT 0,1");
            //                            if ($query_last_date->num_rows() > 0) {
            //                                $abc = $query_last_date->row();
            //                                $last_leave_date = $abc->leave_date;
            //                                $days_ago1 = date('Y-m-d', strtotime('-' . $approve_before1 . 'days', strtotime($last_leave_date)));
            //                                if (strtotime($days_ago1) < $today) {
            //                                    $response['leave_aprv_validation_multiple'] = '1';
            //                                } else {
            //                                    $response['leave_aprv_validation_multiple'] = '0';
            //                                }
            //                            }
            //                        }
            //                    }
            //                }
            //            }

            $response['result_leave'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result_designation'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    Public function approve_all_leave() {
        $userid = $this->input->post('user_id');
        $leaveid = $this->input->post('leave_id');
        $leave_updated_on = date('y-m-d h:i:s');
        $query = $this->db->query("UPDATE `leave_transaction_all` SET `status`='2', leave_approve_deny_on='$leave_updated_on' where leave_id='$leaveid' AND approved_deny_by='$userid' ");
        if ($this->db->affected_rows() > 0) {
            $this->db->query("UPDATE `leave_transaction_all` SET `status`='2' where leave_id='$leaveid'");
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

    public function deny_all_leave() {
        $userid = $this->input->post('user_id');
        $leaveid = $this->input->post('leave_id');
        $leave_updated_on = date('y-m-d h:i:s');
        $query = $this->db->query("UPDATE `leave_transaction_all` SET `status`='3', leave_approve_deny_on='$leave_updated_on' where leave_id='$leaveid' AND approved_deny_by='$userid' ");
        if ($this->db->affected_rows() > 0) {
            $this->db->query("UPDATE `employee_attendance_leave` SET `leave_status`='3' where leave_id='$leaveid'");
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

    public function hr_working_papers() {
        $this->load->view('employee/hr_working_pap');
    }

    public function check_date_present() {
        $userid = $this->input->post('user_id');
        $leave_date_single = $this->input->post('leave_date_single');
        $query = $this->db->query("SELECT * from leave_transaction_all where user_id = '$userid' AND leave_date = '$leave_date_single'");
        if ($query->num_rows() > 0) {
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

    public function check_date_multiple_present() {
        $userid = $this->input->post('user_id');
        $first_date = $this->input->post('leave_date_multiple_first');
        $second_date = $this->input->post('leave_date_multiple_second');
        $query = $this->db->query("SELECT * from leave_transaction_all where user_id = '$userid' AND leave_date between '$first_date' and '$second_date'");
        if ($query->num_rows() > 0) {

            $res = $query->result();
            foreach ($res as $row) {
                $leave_date = $row->leave_date;
                $originalDate = $leave_date;
                $newDate = date("d-m-Y", strtotime($originalDate));
                $adata[] = $newDate;
            }
            $comm_sepreat_serv_type = implode(",", $adata);
            $response['message'] = 'success';
            $response['leave_dates'] = $comm_sepreat_serv_type;
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

		echo json_encode($response);
	}


	public function employeeAttendanceCron() {
		$todayDate = date('Y-m-d');
		$response = array();
		//get All Firms who's is reminder option is available
		$getFirms = $this->Globalmodel->getData('*', array('firm_activity_status' => 'A', 'is_reminder' => 1), 'partner_header_all', false);
		if ($getFirms != null) {

			foreach ($getFirms as $f) {

				//get All the Users of those firms
				$getUsers = $this->Globalmodel->getData('*', array('firm_id' => $f->firm_id, 'activity_status' => 1,'user_type' => 4,'leave_reminder' => 0), 'user_header_all',false);
				if ($getUsers != null) {

					//check if the date is a holiday
					$checkifHoliday = $this->Globalmodel->getData('*',array('firm_id' => $f->firm_id,'date' => $todayDate),'holiday_master_all');
					foreach($getUsers as $U) {
						if ($checkifHoliday != null) { // if it is a holiday
							$is_alternate = $checkifHoliday->is_alternate;
							$alternate_id = $checkifHoliday->alternate_id;


							if ($is_alternate == 1) { // Check if it is a alternate system
								$checkUserAlt = $this->db->query('SELECT * FROM alternate_holiday_master where alternate_id = "' . $alternate_id . '" and user_id = "' . $U->user_id . '";')->result();
								if (count($checkUserAlt) == 0) {
									//Check for Leaves
									$checkLeaves = $this->Globalmodel->getData('*',
											array('firm_id' => $f->firm_id,'user_id' => $U->user_id,'leave_date' => $todayDate,'status' => 2),'leave_transaction_all');
									if($checkLeaves == null){
										// if no Leave Taken on day
										$checkifPunched = $this->Globalmodel->getData('*',
												array('user_id' => $U->user_id,'date' => $todayDate),'employee_attendance_leave');
										if($checkifPunched == null){
											// The User has not Punched in Yet

											$subject = 'Punch-In Reminder';


											$message = '
												<html>
												<head></head>
												<body>
												<p>
												Dear <b>'.$U->user_name.'</b>,
												</p>
												<br>
												<p> I am notifying you that your punch-in is due, please punch in before 10:30 AM.</p>
												<br>
												<p>Regards,<br>
												HR</p>
												</body>
												</html>
												';

											$sendMail = $this->Globalmodel->sendMail($U->email,$subject,$message);//Send Mail.
										}
									}
								}
							}
						} else {
							// Not a Holiday
							//Check for Leaves
							$checkLeaves = $this->Globalmodel->getData('*',
									array('firm_id' => $f->firm_id,'user_id' => $U->user_id,'leave_date' => $todayDate,'status' => 2),'leave_transaction_all');
							if($checkLeaves == null){
								// if no Leave Taken on day
								$checkifPunched = $this->Globalmodel->getData('*',
										array('user_id' => $U->user_id,'date' => $todayDate),'employee_attendance_leave');
								if($checkifPunched == null){
									// The User has not Punched in Yet
									$subject = 'Punch-In Reminder';


									$message = '
												<html>
												<head></head>
												<body>
												<p>
												Dear <b>'.$U->user_name.'</b>,
												</p>
												<br>
												<p> I am notifying you that your punch-in is due, please punch in before 10:30 AM.</p>
												<br>
												<p>Regards,<br>
												HR</p>
												</body>
												</html>
												';
									$sendMail = $this->Globalmodel->sendMail($U->email,$subject,$message);//Send Mail.

								}
							}
						}

						$response['status'] = 200;
						$response['body'] = "Mail sent Successfully";
					}
				}else{
					$response['status'] = 201;
					$response['body'] = "No Users Found in this Firm";
				}
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "No Firms Activated for Reminder Requests";
		}
        echo json_encode($response);
    }
}
?>

