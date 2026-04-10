<?php

class Ticket_Generation extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
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
            // $firm_name = $record->user_name;
            if ($firm_logo == "") {
                $data['logo'] = "";
                // $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                // $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            // $data['firm_name_nav'] = "";
        }
        $data['prev_title'] = "Generate Ticket";
        $data['page_title'] = "Generate Ticket";


        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $tickets_data1 = $this->db->query("select * from ticket_transaction_all where status='1' ");
        $result = $tickets_data1->result();
        if (!is_null($result)) {
            foreach ($result as $row) {
                if (count($result) > 0) {
                    $data['work_id'] = $row->work_id;
                } else {
                    $data['work_id'] = '';
                }
            }
        } else {
            $data['work_id'] = '';
        }

        $user = $this->db->query("SELECT * FROM `user_header_all` where email='$user_id'");

        $user_type = $user->row();
        $u_type = $user_type->user_type;
        $idd = $user_type->user_id;
        $firm_name = $user_type->user_name;
        $data['u_type'] = $u_type;
        $data['firm_name'] = $firm_name;
        $emp = $this->db->query("select emp_id from ticket_header_all where FIND_IN_SET(emp_id, emp_id) in (SELECT user_id FROM `user_header_all` where email='$user_id')");
        $emp_id = $emp->result();
        if (!is_null($emp_id)) {
            foreach ($emp_id as $emp_idd) {
                $empp_id = explode(',', $emp_idd->emp_id);
                $data['emp_id'] = $empp_id;
            }
        } else {
            $data['emp_id'] = '';
        }
        $user = $this->db->query("select assign_to from ticket_transaction_all where status='1' and assign_to in (SELECT user_id FROM `user_header_all` where email='$user_id')");
        $userdata = $user->row();
        $data['userid'] = $userdata;
        $this->load->view('human_resource/ticket_management', $data);
    }

	// function for get all the confiured ticket
    public function ticket_generate() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $user_details = $this->db->query("select user_id from user_header_all where email='$user_id'");
        $userid = $user_details->row();

        $craeted_data = $this->db->query("select email from user_header_all where firm_id in (select firm_id from user_header_all where email = (select created_by from user_header_all where email='$user_id'))")->result();

        foreach ($craeted_data as $row) {
            $result[] = $this->db->query("select * from ticket_header_all where Is_active='1' and  status='1' and emp_id!='$userid->user_id'  and (created_by='" . $row->email . "' or created_by='$user_id' )")->result();
        }
        if ($result[0] != null) {
            foreach ($result as $row1) {
                foreach ($row1 as $row) {

                    $response['type_data'][] = ['type_name' => $row->type_name, 'Type_id' => $row->Type_id];
                }
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

        echo json_encode($response);
    }

	//function for get the configured employee for the specific ticket
    public function get_employee_data() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $user = $this->db->query("SELECT * FROM `user_header_all` where email='$user_id'");
        $users_id = $user->row();
        $type_id = $this->input->post('type_id');

        if ($type_id != '') {
            $query_get_employe = $this->db->query("select Employe_name,emp_id from ticket_header_all where Type_id='$type_id'")->result();
            if (count($query_get_employe) > 0) {
                foreach ($query_get_employe as $query_get_employee) {
                    $emp_name = explode(',', $query_get_employee->Employe_name);
                    $emp_id = explode(',', $query_get_employee->emp_id);
                    $response['emp_data'] = array();
                    for ($i = 0; $i < count($emp_id); $i++) {
                        if ($users_id->user_id != $emp_id[$i]) {
                            $query_get_firm = $this->db->query("select * from ticket_header_all where Type_id = '$type_id'");
                            $res = $query_get_firm->row();
                            $firm_name = explode(',', $res->Branch_name);

                            array_push($response['emp_data'], array('employee_name' => $emp_name[$i], 'emp_id' => $emp_id[$i], 'Branch_name' => $firm_name));
                        }
                    }
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
            echo json_encode($response
            );
        }
    }

    //function for generate new work id for the specific work
    public function get_work_Id() {
        $result = $this->db->query('SELECT work_id FROM `ticket_transaction_all` ORDER BY work_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $work_id = $data->work_id;
            $work_id = str_pad(++$work_id, 5, '0', STR_PAD_LEFT);
            return $work_id;
        } else {
            $work_id = 'work_101';
            return $work_id;
        }
    }

    //function for the generate new ticket generation id
    public function get_generation_id() {
        $result = $this->db->query('SELECT generation_id FROM `ticket_transaction_all` ORDER BY work_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $generation_id = $data->generation_id;
            $generation_id = str_pad(++$generation_id, 4, '0', STR_PAD_LEFT);
            return $generation_id;
        } else {
            $generation_id = 'gen_101';
            return $generation_id;
        }
    }

    //function for get the ticket type id
    public function get_type_id() {
        $type_id = $this->input->post('type_id');
        if ($type_id == '') {
            return $type_id = '';
        } else {
            return $type_id;
        }
    }

    //function for insert generated ticket
    public function insert_ticket() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $updated_id1 = $this->input->post('updated_id');
        $generation_id = $this->get_generation_id();
        $employe_id = $this->input->post('employe_id');
        $employe_name = $this->input->post('employe_name');
        $Work = $this->input->post('Work');
        //  $type_id = $this->input->post('type_id');
        $type_id = $this->get_type_id();
        $type_name = $this->input->post('type_name');
        if (is_array($type_id)) {
            $type_id = implode(',', $type_id);
        }
        if (is_array($employe_id)) {
            $employe_id = implode(',', $employe_id);
        }
        $work_id = $this->get_work_Id();
        $created_on = date('y-m-d h:i:s');
        $data = array(
            'generation_id' => $generation_id,
            'Type_id' => $type_id,
            'work_id' => $work_id,
            'work_detail' => $Work,
            'assign_to' => $employe_id,
            'status' => 1,
            'Is_accpet' => 0,
            'Senor_read' => 5,
            'junior_read' => 0,
            'created_by' => $user_id,
            'created_on' => $created_on,
            'work_done_reject_id' => 0,
            'work_done_against' => 0,
        );
        if ($updated_id1 == '0') {
            $this->db->insert('ticket_transaction_all', $data);
            echo "Ticket Generated Successfully";
        } else {
            $this->db->where('generation_id', $updated_id1);
            $this->db->update('ticket_transaction_all', $data);
            echo "Ticket  Updated Successfully";
        }
    }

	//function for shows detail of generated ticket
    public function show_data() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $ticket_data = $this->db->query("select * from ticket_transaction_all where status = '1' and created_by = '$user_id'")->result();
        if (count($ticket_data) > 0) {
            $table = "";
            foreach ($ticket_data as $row) {
                $ticket_data1 = $this->db->query("select * from ticket_header_all where status = '1' and Type_id = '" . $row->Type_id . "' and FIND_IN_SET( '" . $row->assign_to . "', emp_id)");
                $res = $ticket_data1->row();
                $employeedata = $this->db->query("select * from user_header_all where user_id = '" . $row->assign_to . "'");
                $employee = $employeedata->row();
                if ($row->Is_accpet == '1') {
                    $table .= '<tr><td>' . $res->type_name . '</td><td class="comment more">' . $row->work_detail . '</td><td>' . $employee->user_name . '</td><td>' . date_format(date_create($row->created_on), "d-M-Y h:i a") . '</td><td>' . date_format(date_create($row->Accepted_on), "d-M-Y h:i a") . '</td><td><button  data-toggle="modal" data-ticket_type_id="' . $row->generation_id . '" data-target="#Ticket_Modal" class="btn btn-link " title="EDIT" onclick="firm_update(`' . $row->generation_id . '`)" disabled=""><i class="fa fa-pencil" style="font-size:20px"></i></button>'
                            . '<button class="btn btn-link" title="DELETE" onclick="delete_ticket(`' . $row->generation_id . '`)"  ><i class="fa fa-trash font-red" style="font-size:20px" ></i></button><button class="btn btn-link" title="VIEW" data-toggle="modal" class="btn sbold blue" data-ticket_type_id="' . $row->generation_id . '" data-target="#responseViewModal" data-table_id="1"><i class="fa fa-eye" style="font-size:20px"></i></button></td></tr>';
                } else {
                    $table .= '<tr><td>' . $res->type_name . '</td><td class="comment more">' . $row->work_detail . '</td><td>' . $employee->user_name . '</td><td>' . $row->created_on . '</td><td>' . $row->Accepted_on . '</td><td><button title="EDIT" onclick="firm_update(`' . $row->generation_id . '`)" data-toggle="modal" data-ticket_type_id="' . $row->generation_id . '" data-target="#Ticket_Modal" class="btn btn-link " ><i class="fa fa-pencil" style="font-size:22px"></i></button>'
                            . '<button class="btn btn-link" title="DELETE" onclick="delete_ticket(`' . $row->generation_id . '`)"  ><i class="fa fa-trash font-red" style="font-size:22px" ></i></button><button class="btn btn-link" title="VIEW" data-toggle="modal" class="btn sbold blue"  disabled=""><i class="fa fa-eye" style="font-size:22px"></i></button></td></tr>';
                }
            }
            $response['ticket_data'] = $table;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $table = '<tr class="odd"><td valign="top" colspan="8" class="dataTables_empty">No data available in table</td></tr>';
            $response['ticket_data'] = $table;
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

	//function for shows the response against ticket
    public function response_description() {
        $update_value = $this->input->post("update_value");
        $table_id = $this->input->post("table_id");
        $responseList = $this->db->query("select *  from ticket_transaction_all where status='2' and generation_id='$update_value'")->result();
        $response_item = "";
        if (count($responseList) > 0) {
            $data["status"] = 200;
            foreach ($responseList as $response) {
                $status_color = "";
                $status_code = "";
                if ($response->work_done_reject_id != -1) {
                    $right_side_button = "<div class='btn-group pull-right'>
                                        <button class='btn btn-circle-bottom dropdown-toggle' type='button' data-toggle='dropdown' data-hover='dropdown' data-close-others='true' aria-expanded='false'>
                                            <i class='fa fa-bars'></i>
                                        </button>
                                        <ul class='dropdown-menu pull-right' role='menu'>
                                            <li>                                                <a href='#' onclick='accept_workdone_by_senor(`" . $response->generation_id . "`," . $response->id . ",`" . $response->work_id . "`)'>Accept</a>
                                            </li>
                                            <li>
                                               <a href='#' data-toggle='modal' data-target='#ticketWorkDetailsModal'  data-work_reject_id='" . $response->work_id . "'data-response_code='" . $response->id . " ' data-ticket_type_id='" . $response->generation_id . "' data-call_by='1' >Reject</a>
                                            </li>
                                        </ul>
                                    </div>";

                    //junior accept reject
                    $ticket_data = $this->db->query("select * from ticket_transaction_all where status='1' ")->result();
                    foreach ($ticket_data as $row) {
                        $tickets_data1 = $this->db->query("select * from ticket_transaction_all where  Is_accpet='1' and status='2' and work_id='" . $response->work_id . "'");
                        $result = $tickets_data1->row();
                        if ($result != null) {
                            $user_name = $this->db->query("select * from user_header_all where  email='" . $result->created_by . "'");
                            $username = $user_name->row();
                        } else {
                            $username = '';
                        }
                        $work_reject = $this->db->query("select * from ticket_transaction_all where Is_accpet='4' and status='2' and work_id='" . $response->work_id . "'");
                        $reject_workname = $work_reject->row();

                        if ($reject_workname != null) {
                            $rejected_work_name = $this->db->query("select * from user_header_all where  email='" . $reject_workname->created_by . "'");
                            $rej_work_name = $rejected_work_name->row();
                        } else {
                            $rej_work_name = '';
                        }

                        if ($table_id == 1) {
                            $status_color = "ribbon-color-info";
                        }
                        if ($table_id == 2) {
                            $status_color = "ribbon-color-info";
                            $right_side_button = "";
                        }
                        if ($response->Is_accpet == '1') {
                            $status_color = "ribbon-color-info";
                            $status_code = "Work Done detail  (" . ($username->user_name) . ")";
                        }

                        if ($response->Is_accpet == '3') {
                            $status_color = "ribbon-color-success";
                            $status_code = " Work Done Accepted  ";
                            $right_side_button = "";
                        }
                        if ($response->Is_accpet == '4') {
                            $status_color = "ribbon-color-warning";
                            $status_code = " Work Done Rejected By (" . ($rej_work_name->user_name) . ")";
                            $right_side_button = "";
                        }

                        $response_work1 = $this->db->query("select * from ticket_transaction_all where work_id='$response->work_done_reject_id'  ");
                        $response_work = $response_work1->row();
                        $data["query"] = $this->db->last_query();
                        if ($response->work_done_reject_id != -1 && ($response->Is_accpet == '4' || $response->Is_accpet == '2')) {
                            $right_side_button = "";
                            $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-danger uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div>  Work Rejected Reason  </div>
                        <p class="ribbon-content text-right">' . $response->work_detail . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . $response->created_on . '</span></p>
                    </div>';
                        } else {
                            $rejectionbox = "";
                        }
                        if ($table_id == 2) {
                            $right_side_button = "";
                        }
                    }

                    $response_item .= "<div class='col-xs-12'>
                                <div class='mt-element-ribbon bg-grey-steel'>
                                    <div class='ribbon ribbon-clip ribbon-round ribbon-border-dash-hor ribbon-shadow " . $status_color . " uppercase'><div class='ribbon-sub ribbon-clip '></div>" . $status_code . "</div>
                                        " . $right_side_button . "
                                    <p class='ribbon-content'>" . $response_work->work_detail . "<span class='font-grey-cascade page-content-wrapper timeline-body-time'> created at : " . $response->created_on . "</span></p>
                                        " . $rejectionbox . "
                                </div>
                            </div>";
                }
            }
            $data["result"] = $response_item;
        } else {
            $data["status"] = 300;
        }
        echo json_encode($data);
    }

    //function for accept the ticket work
    function accept_work_status() {
        $gen_id = $this->input->post('gen_id');
        $id = $this->input->post('response_code');
        $work_id = $this->input->post('work_id');
        $created_on = date('y-m-d h:i:s');
        $result = $this->db->query("select * from ticket_transaction_all where generation_id='$gen_id'")->result();
        if (!$gen_id == '') {
            $data = array(
                'Is_accpet' => 3,
                'Accepted_on' => $created_on,
                'junior_read' => 3,
                // 'work_done_reject_id' => $work_id,
            );

            $this->db->where(array('generation_id' => $gen_id, 'id' => $id));
            $this->db->update('ticket_transaction_all', $data);

            $data["status"] = 200;
            $data["result"] = "Successfully  Work Done Accepted";
        } else {
            $data["status"] = 300;
            $data["result"] = "Failed To Accept  Work Done";
        }
        $data["query"] = $this->db->last_query();
        echo json_encode($data);
    }

    //function for delete the generated ticket
    public function delete_data() {
        $delete_id = $this->input->post('delete_id');
        $data = array(
            'status' => 0
        );
        $this->db->where('generation_id', $delete_id);
        $this->db->update('ticket_transaction_all', $data);
        echo " Deleted Successsfully";
    }

    //function for shows assignments tickets
    public function show_data1() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $user_data = $this->db->query("select * from user_header_all where email='$user_id'");
        $user_id = $user_data->row();

        $ticket_data = $this->db->query("select * from ticket_transaction_all where  status='1' ")->result();
        if (count($ticket_data) > 0) {
            $table = "";
            foreach ($ticket_data as $row) {
                if ($row->assign_to == $user_id->user_id) {
                    $ticket_data1 = $this->db->query("select * from ticket_header_all where status='1' and  Type_id='" . $row->Type_id . "' and FIND_IN_SET( '" . $row->assign_to . "',emp_id) ");
                    $res = $ticket_data1->row();
                    $user_name = $this->db->query("select * from user_header_all where  email='" . $row->created_by . "'");
                    $username = $user_name->row();
                    if ($row->Is_accpet == '0') {

                        $table .= '<tr><td>' . $username->user_name . '</td><td>' . $res->type_name . '</td><td class="comment more">' . $row->work_detail . '</td><td>' . $row->created_on . '</td><td><button data-toggle="modal" class="btn  btn-link" title="Add Work done" data-accepted="' . $row->Is_accpet . '" data-ticket_type_id="' . $row->generation_id . '" data-target="#ticketWorkDetailsModal" data-work_reject_id="' . $row->work_id . '"><i class="fa fa-file" style="font-size:22px"></i></button><button data-toggle="modal" class="btn btn-link" title="VIEW" data-ticket_type_id="' . $row->generation_id . '" data-table_id="2" data-target="#responseViewModal"><i class="fa fa-eye" style="font-size:22px"></i></button></td></tr>';
                    } else {


                        $table .= '<tr><td>' . $username->user_name . '</td><td>' . $res->type_name . '</td><td class="comment more">' . $row->work_detail . '</td><td>' . $row->created_on . '</td><td><button data-toggle="modal" class="btn  btn-link" title="Add Work done" data-accepted="' . $row->Is_accpet . '" data-ticket_type_id="' . $row->generation_id . '"  data-work_reject_id="' . $row->work_id . '" disabled=""><i class="fa fa-file" style="font-size:22px"></i></button><button data-toggle="modal" class="btn btn-link" title="VIEW" data-ticket_type_id="' . $row->generation_id . '" data-table_id="2" data-target="#responseViewModal"><i class="fa fa-eye" style="font-size:22px"></i></button></td></tr>';
                    }
                }
            }
            $response['ticket_data'] = $table;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $table = '<tr class="odd"><td valign="top" colspan="8" class="dataTables_empty">No data available in table</td></tr>';
            $response['ticket_data'] = $table;
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //function for reject sended work
    public function rejected_work() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $updated_id = $this->input->post('ticekt_id');
        $response_type = $this->input->post('type_of_response');
        $work_reject_id = $this->input->post('work_reject_id');
        $rejection_description = $this->input->post('txt_rejection_description');
        $assign_to_id = $this->db->query("select * from ticket_transaction_all where generation_id='$updated_id'");
        $assign = $assign_to_id->row();
        $work_id = $this->get_work_Id();
        $created_on = date('y-m-d h:i:s');
        if (!$updated_id == 0) {
            if ($response_type == 1) {
                $data = array(
                    'status' => 2,
                    'work_id' => $work_id,
                    'generation_id' => $updated_id,
                    'assign_to' => $assign->assign_to,
                    'Is_accpet' => 1,
                    'work_detail' => $rejection_description,
                    'senor_read' => 6,
                    'junior_read' => 5,
                    'created_by' => $user_id,
                    'created_on' => $created_on,
                    'Accepted_on' => $created_on,
                    'work_done_reject_id' => $work_id,
                    'work_done_against' => $work_reject_id,
                );
                $data1 = array(
                    'Is_accpet' => 1,
                    'Accepted_on' => $created_on,
                );
                $this->db->where('work_id', $work_reject_id);
                $this->db->update('ticket_transaction_all', $data1);

                $data3["status"] = 200;
                $data3["result"] = "Successfully work done submitted";
            } else if ($response_type == 4) {
                $data = array(
                    'status' => 2,
                    'work_id' => $work_id,
                    'generation_id' => $updated_id,
                    'assign_to' => $assign->assign_to,
                    'Is_accpet' => 4,
                    'work_detail' => $rejection_description,
                    'senor_read' => 6,
                    'junior_read' => 4,
                    'created_by' => $user_id,
                    'created_on' => $created_on,
                    'Accepted_on' => $created_on,
                    'work_done_reject_id' => $work_reject_id,
                    'work_done_against' => 0,
                );

                $data1 = array(
                    'work_done_reject_id' => -1,
                );
                $this->db->where('work_id', $work_reject_id);
                $this->db->update('ticket_transaction_all', $data1);
                $data3["status"] = 200;
                $data3["result"] = "Successfully Rejected work done ";
            }


            $this->db->where('generation_id', $updated_id);
            $this->db->insert('ticket_transaction_all', $data);
        } else {
            $data3["status"] = 200;
            $data3["result"] = "Failed ";
        }
        echo json_encode($data3);
    }

    //function for shows the user performance aginst ticket
    public function user_table() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $employee_id = $this->input->post('employee_id');
        $user = $this->db->query("SELECT count(case WHEN status='1' then 1 end) as 'Assigned',count(case WHEN Is_accpet='3' then 1 end) as 'accepted',count(case WHEN Is_accpet='4' then 1 end) as 'rejected',count(case WHEN status='1' and Is_accpet='0' then 1 end) as 'pending' FROM `ticket_transaction_all` where  assign_to='$employee_id' ")->result();
        $emp_name = $this->db->query("select * from user_header_all where  user_id='$employee_id' ");
        $employe_name = $emp_name->row();
        $table = "";
        foreach ($user as $userdata) {
            $table .= '<tr><th scope="col" style="text-align: center;">Employee Name</th>'
                    . '   <th scope="col" style="text-align: center;">Assigned ticket</th>'
                    . '  <th scope="col" style="text-align: center;">Completed </th>'
                    . '  <th scope="col" style="text-align: center;">Rejected </th>'
                    . ' <th scope="col" style="text-align: center;">Pending </th></tr><tr><td>' . $employe_name->user_name . '</td> <td>' . $userdata->Assigned . '</td><td>' . $userdata->accepted . '</td><td>' . $userdata->rejected . '</td><td>' . $userdata->pending . '</td></tr>';
                    // $response['user_table'] = $table;
                    // $table .= '<div class="row">
                        // <div class="col-sm-3 col-xs-6">
                        //     <div class="ticket-counter">
                        //         <h4>' . $employe_name->user_name . '</h4>
                        //         <p class="label label-md label-info bold uppercase">Employee Name</p>
                        //     </div>
                        // </div>
                        // <div class="col-sm-3 col-xs-6">
                        //     <div class="ticket-counter">
                        //         <h4>' . $userdata->Assigned . '</h4>
                        //         <p class="label label-md label-success bold uppercase">Assigned ticket</p>
                        //     </div>
                        // </div>
                        // <div class="col-sm-3 col-xs-6">
                        //     <div class="ticket-counter">
                        //         <h4>' . $userdata->accepted . '</h4>
                        //         <p class="label label-md label-warning bold uppercase">Completed</p>
                        //     </div>
                        // </div>
                        // <div class="col-sm-3 col-xs-6">
                        //     <div class="ticket-counter">
                        //         <h4>' . $userdata->pending . '</h4>
                        //         <p class="label label-md label-default bold uppercase">Pending</p>
                        //     </div>
                        // </div>
                        // <div>';
        }
        $response['user_table'] = $table;
        echo json_encode($response);
    }

    //function for update the unread ticket  in notification
    function markAsRead() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $data = array(
                'senor_read' => 0,
                'junior_read' => 5,
            );
            $this->db->where(array('assign_to' => $user_id, 'status' => '1'));
            $this->db->update('ticket_transaction_all', $data);
        }
    }

    //function for update the accepted  in notification
    function markAsReadaccepted() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $data = array(
                'junior_read' => 5,
            );
            $this->db->where(array('assign_to' => $user_id, 'junior_read' => '3'));
            $this->db->update('ticket_transaction_all', $data);
        }
    }

    //function for update the rejected  in notification
    function markAsReadrejected() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $data = array(
                'junior_read' => 5,
            );
            $this->db->where(array('assign_to' => $user_id, 'junior_read' => '4'));
            $this->db->update('ticket_transaction_all', $data);
        }
    }

    //function for update the work done  in notification
    function markAsReadworkdone() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $result = $this->db->query("select work_id from ticket_transaction_all where created_by in (select email from user_header_all where user_id='$user_id')")->result();
            if (count($result) > 0) {
                foreach ($result as $row) {
                    $data = array(
                        'work_done_against' => 0,
                    );
                    $this->db->where(array('work_done_against' => $row->work_id, 'junior_read' => '5', 'senor_read' => '6'));
                    $this->db->update('ticket_transaction_all', $data);
                }
            }
        }
    }

    //function for show the messages in notification
    function unread_ticket_assigenement() {
        $user_datails = $this->customer_model->get_firm_id();
        $result = $this->db->query("select count(work_done_against) as work_done_against_count,work_done_against from ticket_transaction_all where work_done_against!='0'")->result();
        $notification_str = "";
        $counter = 0;
        if (count($result) > 0) {
            foreach ($result as $work_against) {
                $message = "";
                $u_id = $this->db->query("select user_id from user_header_all where email in (select created_by from ticket_transaction_all where work_id='" . $work_against->work_done_against . "')")->result();
                if (count($u_id) > 0) {
                    foreach ($u_id as $uid) {
                        if ($uid->user_id == $user_datails['user_id']) {
                            $message = "Work Done";
                            $counter = $counter + $work_against->work_done_against_count;
                            if ($message != "") {
                                $notification_str .= ' <li>
                                                <a href="' . base_url("Ticket_Generation/index") . '">
                                                    <span class="time">View</span>
                                                    <span class="details">' . $message . '</span>
                                                </a>
                                            </li>';
                            }
                        }
                    }
                }
            }
        }
        $senor_notification = $this->db->query("SELECT count(case WHEN status='1' and senor_read='5' then 1 end) as 'Unread',count(case WHEN junior_read='3' then 1 end) as 'Assignment_accepted' ,count(case WHEN junior_read=4 then 1 end) as 'Assignment_Rejected',work_done_reject_id FROM `ticket_transaction_all` WHERE  assign_to='" . $user_datails['user_id'] . "' group BY work_done_reject_id ")->result();
        if (count($senor_notification) > 0) {
            foreach ($senor_notification as $notification) {
                $message = "";
                if ($notification->Unread > 0) {
                    $message = "Unread Tickets";
                    $counter = $counter + $notification->Unread;
                }

                if ($notification->work_done_reject_id != -1 && $notification->work_done_reject_id != '0') {
                    $u_id = $this->db->query("select user_id from user_header_all where email in (select created_by from ticket_transaction_all where work_id='" . $notification->work_done_reject_id . "')")->result();
                    if (count($u_id) > 0) {
                        foreach ($u_id as $uid) {
                            if ($uid->user_id == $user_datails['user_id']) {
                                if ($notification->Assignment_accepted > 0) {
                                    $message = "Work done accepted";
                                    $counter = $counter + $notification->Assignment_accepted;
                                }
                            }
                        }
                    }
                }
                if ($notification->work_done_reject_id != -1 && $notification->work_done_reject_id != '0') {
                    $u_id = $this->db->query("select user_id from user_header_all where email in (select created_by from ticket_transaction_all where work_id='" . $notification->work_done_reject_id . "')")->result();
                    if (count($u_id) > 0) {
                        foreach ($u_id as $uid) {
                            if ($uid->user_id == $user_datails['user_id']) {
                                if ($notification->Assignment_Rejected > 0) {
                                    $message = "Work done Rejected";
                                    $counter = $counter + $notification->Assignment_Rejected;
                                }
                            }
                        }
                    }
                }

                if ($message != "") {
                    $notification_str .= ' <li>
                                                <a href="' . base_url("Ticket_Generation") . '">
                                                    <span class="time">View</span>
                                                    <span class="details">' . $message . '</span>
                                                </a>
                                            </li>';
                }
            }
        }

        echo json_encode(array("Total" => $counter, "notification" => $notification_str));
    }

    //function for shows the generated tikcet for updation
    function get_type_name_data() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $gen_id = $this->input->post("gen_id");
        $result = $this->db->query("select * from ticket_transaction_all where generation_id='$gen_id' and Type_id!=''")->result();
        if (count($result) > 0) {
            foreach ($result as $row) {
                $work_detail = $row->work_detail;
                $user_id = $row->assign_to;
                $user_name = $this->db->query("select * from user_header_all where user_id='" . $row->assign_to . "'");
                $username = $user_name->row();
                $firm_name = $this->db->query("select firm_name from partner_header_all where firm_id='" . $username->firm_id . "'");
                $firmname = $firm_name->row();
                $type_name = $this->db->query("select * from ticket_header_all where Type_id='" . $row->Type_id . "'");
                $typename = $type_name->row();
                $type_name_array = explode(',', $typename->type_name);
                $Type_id_array = explode(',', $typename->Type_id);
                $user_name_array = explode(',', $username->user_name);
                $user_id_array = explode(',', $username->user_id);
                $typeidd = $typename->Type_id;
            }
        }
        $user = $this->db->query("SELECT * FROM `user_header_all` where user_id='$user_id'");
        $users_id = $user->row();
        if ($typeidd != '') {
            $query_get_employe = $this->db->query("select Employe_name,emp_id from ticket_header_all where Type_id='$typeidd'")->result();
            if (count($query_get_employe) > 0) {
                foreach ($query_get_employe as $query_get_employee) {
                    $emp_name = explode(',', $query_get_employee->Employe_name);
                    $emp_id = explode(',', $query_get_employee->emp_id);
                    $response['emp_data'] = array();
                    foreach ($emp_name as $index => $emp_na) {
                        if ($users_id->user_id != $emp_id[$index]) {
                            $query_get_firm = $this->db->query("select * from ticket_header_all where Type_id = '$typeidd'");
                            $res = $query_get_firm->row();
                            $firm_name = explode(',', $res->Branch_name);
                            array_push($response['emp_data'], array('employee_name' => $emp_name[$index], 'emp_id' => $emp_id[$index], 'Branch_name' => $firm_name));
                        }
                    }
                }
            }
        }
        $result = $this->db->query("select * from ticket_header_all where  status='1' and emp_id not  in (SELECT user_id FROM `user_header_all` where email='$user_id')")->result();

        if (count($result) > 0) {
            foreach ($result as $row) {
                $data['type_name'][] = $row->type_name;
                $data['type_id'][] = $row->Type_id;
            }
        }
        $comman_array = array_diff($data['type_name'], $type_name_array);
        $comman_array_finial = array_values($comman_array);
        $response['comman_array'] = $comman_array_finial;
        $response['selected_array'] = $type_name_array;
        $comman_array_id = array_diff($data['type_id'], $Type_id_array);
        $comman_array_finial_id = array_values($comman_array_id);
        $response['comman_id_array'] = $comman_array_finial_id;
        $response['selected_id_array'] = $Type_id_array;

        $response['user_data'][] = [$work_detail, $username->user_id, $username->user_name, $firmname->firm_name];
        echo json_encode($response);
    }




}

?>