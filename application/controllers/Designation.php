<?php

class Designation extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('firm_model');
        $this->load->model('designation_model');
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

        $query_view_designation = $this->db->query("SELECT * FROM `designation_header_all` where `firm_id`= '$firm_id'");
        if ($query_view_designation->num_rows() > 0) {
            $record = $query_view_designation->result();
            $data['result_view_designation'] = $record;
        } else {
            $data['result_view_designation'] = "";
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
                    $response['leave'] = ['leave_requested_on' => $leave_requested_on, 'leave_type' => $leave_type];
                } else {

                    $leave_requested_on = '';
                    $leave_type = '';
                    $response['leave'] = '';
                }
                $data['leave_type'] = $response['leave'];
                $data['leave_requested_on'] = $response['leave'];
            }
        } else {
            
        }
        $query1 = $this->db->query("SELECT * from partner_header_all where firm_id='$firm_id'");
        if ($query1->num_rows() > 0) {
            $record1 = $query1->row();
            $leave_type_fixed = $record1->leave_type_fixed;
        } else {
            $leave_type_fixed = '';
        }

        $monthly_yearly_leave = $this->db->query("SELECT * from designation_header_all where firm_id='$firm_id' AND designation_id='CA'");
        if ($monthly_yearly_leave->num_rows() > 0) {
            $record2 = $monthly_yearly_leave->row();
            $total_yearly_leaves = $record2->total_yearly_leaves;
            $total_monthly_leaves = $record2->total_monthly_leaves;
        } else {
            $total_yearly_leaves = '';
            $total_monthly_leaves = '';
        }

        $data['firm_id'] = $firm_id;
        $data['leave_type_fixed'] = $leave_type_fixed;
        $data['total_yearly_leaves'] = $total_yearly_leaves;
        $data['total_monthly_leaves'] = $total_monthly_leaves;
        $data['prev_title'] = "Branch Designation Management";
        $data['page_title'] = "Branch Designation Management";

        $this->load->view('client_admin/designation', $data);
    }

    public function designation_emp() {
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

        $query_view_designation = $this->db->query("SELECT * FROM `designation_header_all` where `firm_id`= '$firm_id'");
        if ($query_view_designation->num_rows() > 0) {
            $record = $query_view_designation->result();
            $data['result_view_designation'] = $record;
        } else {
            $data['result_view_designation'] = "";
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
                $data['leave_type'] = $response['leave'];
                $data['leave_requested_on'] = $response['leave'];
            }
        } else {
            
        }
        $query1 = $this->db->query("SELECT * from partner_header_all where firm_id='$firm_id'");
        if ($query1->num_rows() > 0) {
            $record1 = $query1->row();
            $leave_type_fixed = $record1->leave_type_fixed;
        } else {
            $leave_type_fixed = '';
        }

        $monthly_yearly_leave = $this->db->query("SELECT * from designation_header_all where firm_id='$firm_id' AND designation_id='CA'");
        if ($monthly_yearly_leave->num_rows() > 0) {
            $record2 = $monthly_yearly_leave->row();
            $total_yearly_leaves = $record2->total_yearly_leaves;
            $total_monthly_leaves = $record2->total_monthly_leaves;
        } else {
            $total_yearly_leaves = '';
            $total_monthly_leaves = '';
        }

        $data['leave_type_fixed'] = $leave_type_fixed;
        $data['total_yearly_leaves'] = $total_yearly_leaves;
        $data['total_monthly_leaves'] = $total_monthly_leaves;
        $data['prev_title'] = "Branch Designation Management";
        $data['page_title'] = "Branch Designation Management";

        $this->load->view('employee/Designation', $data);
    }

    public function generate_designation_id() {
        $designation_id = 'Desig_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('designation_header_all');
        $this->db->where('designation_id', $designation_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return $this->generate_designation_id();
        } else {
            return $designation_id;
        }
    }

    public function create_designation() {
        $designation_id = $this->generate_designation_id();
        $designation_name = $this->input->post('designation_name');

        $designation_role = $this->input->post('designation_role');
        $senior_authority = $this->input->post('senior_authority');


        $yearly_leave = $this->input->post('yearly_leave');
        $monthly_leaves = $this->input->post('monthly_leaves');
        $leave_type_year = $this->input->post('leave_type_year');
        $leave_apply_permission = $this->input->post('leave_apply_permission');
        $leave_cf = $this->input->post('leave_cf');
        $holiday_status = $this->input->post('holiday_status');
        $leave_type1 = $this->input->post('leave_type1');
        $numdays1 = $this->input->post('numofdays1');
        $leave_type2 = $this->input->post('leave_type2');
        $numdays2 = $this->input->post('numofdays2');
        $leave_type3 = $this->input->post('leave_type3');
        $numdays3 = $this->input->post('numofdays3');
        $leave_type4 = $this->input->post('leave_type4');
        $numdays4 = $this->input->post('numofdays4');
        $leave_type5 = $this->input->post('leave_type5');
        $numdays5 = $this->input->post('numofdays5');
        $leave_type6 = $this->input->post('leave_type6');
        $numdays6 = $this->input->post('numofdays6');
        $leave_type7 = $this->input->post('leave_type7');
        $numdays7 = $this->input->post('numofdays7');

        $request_before1 = $this->input->post('req_bfr1');
        $approve_before1 = $this->input->post('aprv_bfr1');
        $request_before2 = $this->input->post('req_bfr2');
        $approve_before2 = $this->input->post('aprv_bfr2');
        $request_before3 = $this->input->post('req_bfr3');
        $approve_before3 = $this->input->post('aprv_bfr3');
        $request_before4 = $this->input->post('req_bfr4');
        $approve_before4 = $this->input->post('aprv_bfr4');
        $request_before5 = $this->input->post('req_bfr5');
        $approve_before5 = $this->input->post('aprv_bfr5');
        $request_before6 = $this->input->post('req_bfr6');
        $approve_before6 = $this->input->post('aprv_bfr6');
        $request_before7 = $this->input->post('req_bfr7');
        $approve_before7 = $this->input->post('aprv_bfr7');

        $created_on = date('y-m-d h:i:s');
        $result = $this->firm_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
            $boss_id = $result['boss_id'];
            $username = $this->session->userdata('login_session');
        }
        if (empty($numdays1)) {
            $numdays1 = 0;
        }if (empty($numdays2)) {
            $numdays2 = 0;
        }if (empty($numdays3)) {
            $numdays3 = 0;
        }if (empty($numdays4)) {
            $numdays4 = 0;
        }if (empty($numdays5)) {
            $numdays5 = 0;
        }if (empty($numdays6)) {
            $numdays6 = 0;
        }if (empty($numdays7)) {
            $numdays7 = 0;
        }
        $total_leaves = $numdays1 + $numdays2 + $numdays3 + $numdays4 + $numdays5 + $numdays6 + $numdays7;

        $result_data = $this->search_same_designation($designation_name, $firm_id);

        if (empty(trim($designation_name))) {
            $response['id'] = 'designation_name';
            $response['error'] = 'Enter Designation';
            echo json_encode($response);
        } else if ($result_data !== false) {
            $response['id'] = 'designation_name';
            $response['error'] = 'This Designation is already created.';
            echo json_encode($response);
        } else if (empty(trim($designation_role))) {
            $response['id'] = 'designation_role';
            $response['error'] = 'Enter Designation Role';
            echo json_encode($response);
        } else if ($leave_apply_permission == "") {
            $response['id'] = 'leave_apply_permission';
            $response['error'] = 'Select Apply Leave';
            echo json_encode($response);
        } else if ($total_leaves != $yearly_leave) {
            $response['id'] = 'numofdays7';
            $response['error'] = 'Sum of leave should be equal to yearly leaves';
            echo json_encode($response);
        } else {


            $type1 = $leave_type1 . ':' . $numdays1 . ':' . $request_before1 . ':' . $approve_before1;

            if (empty($leave_type2 && $numdays2)) {
                $type2 = '';
            } else {
                $type2 = $leave_type2 . ':' . $numdays2 . ':' . $request_before2 . ':' . $approve_before2;
            }
            if (empty($leave_type3 && $numdays3)) {
                $type3 = '';
            } else {
                $type3 = $leave_type3 . ':' . $numdays3 . ':' . $request_before3 . ':' . $approve_before3;
            }
            if (empty($leave_type4 && $numdays4)) {
                $type4 = '';
            } else {
                $type4 = $leave_type4 . ':' . $numdays4 . ':' . $request_before4 . ':' . $approve_before4;
            }
            if (empty($leave_type5 && $numdays5)) {
                $type5 = '';
            } else {
                $type5 = $leave_type5 . ':' . $numdays5 . ':' . $request_before5 . ':' . $approve_before5;
            }
            if (empty($leave_type6 && $numdays6)) {
                $type6 = '';
            } else {
                $type6 = $leave_type6 . ':' . $numdays6 . ':' . $request_before6 . ':' . $approve_before6;
            }
            if (empty($leave_type7 && $numdays7)) {
                $type7 = '';
            } else {
                $type7 = $leave_type7 . ':' . $numdays7 . ':' . $request_before7 . ':' . $approve_before7;
            }


            $data_leave = array(
                'designation_id' => $designation_id,
                'type1' => $type1,
                'type2' => $type2,
                'type3' => $type3,
                'type4' => $type4,
                'type5' => $type5,
                'type6' => $type6,
                'type7' => $type7,
                'firm_id' => $firm_id,
                'created_on' => $created_on,
                'boss_id' => $boss_id
            );
            $p_data_leave = $this->db->insert('leave_header_all', $data_leave);
            if (empty($senior_authority)) {
                $senior_authority = $designation_id;
            }
            $data = array(
                'designation_id' => $designation_id,
                'designation_name' => $designation_name,
                'designation_roles' => $designation_role,
                'reporting_designation_id' => $senior_authority,
                'total_yearly_leaves' => $yearly_leave,
                'total_monthly_leaves' => $monthly_leaves,
                'holiday_consider_in_leave' => $holiday_status,
                'carry_forward_period' => $leave_cf,
                'request_leave_from' => $leave_apply_permission,
                'year_type' => $leave_type_year,
                'firm_id' => $firm_id,
                'created_on' => $created_on,
                'created_by' => $username,
                'boss_id' => $boss_id
            );
//$add_designation = $this->db->insert('designation_header_all', $data);
            $add_designation = $this->designation_model->add_designation($data);

            if ($add_designation == TRUE) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }echo json_encode($response);
        }
    }

    public function search_same_designation($designation_name, $firm_id) {
        $result = $this->db->query("SELECT * FROM `designation_header_all` WHERE designation_name ='$designation_name' AND firm_id ='$firm_id'");
        if ($result->num_rows() > 0) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    public function get_designation_hq_1() {
        $firm_id = $this->input->post('firm_id');
        $is_virtual = $this->db->select("is_virtual")->where("firm_id", $firm_id)->get("partner_header_all")->row();
        if (!is_null($is_virtual)) {

            $response['is_virtual'] = $is_virtual->is_virtual;
        } else {
            $response['is_virtual'] = 0;
        }
        $query4 = $this->db->query("select designation_name,designation_id from designation_header_all where firm_id='$firm_id'");

        $query_get_hr = $this->db->query("SELECT `designation_id` from `user_header_all` where `user_type`='5' and  FIND_IN_SET('" . $firm_id . "', `hr_authority`)");
        if ($query_get_hr->num_rows() > 0) {

            $res = $query_get_hr->row();
            $desig_id_hr = $res->designation_id;
        } else {

            $desig_id_hr = "";
        }
        $query_get_hr_desig = $this->db->query("SELECT * from `designation_header_all` where `designation_id`='$desig_id_hr'");
        if ($query_get_hr_desig->num_rows() > 0) {

            $res1 = $query_get_hr_desig->row();
            $desig_name_hr = $res1->designation_name;
        } else {

            $desig_name_hr = "";
        }
        $hr_desig = array('designation_id' => $desig_id_hr, 'designation_name' => $desig_name_hr);
        if ($query4->num_rows() > 0) {

            $record = $query4->result();
            $response['result_designation'] = $record;
            $response['message'] = 'success';
            $response['hr_desig'] = $hr_desig;
            $response['code'] = 200;
            $response['status'] = true;
        } else {

            $response['result_designation'] = '';
            $response['hr_desig'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_designation_hr() {
        $firm_id = $this->input->post('firm_id');
        $query4 = $this->db->query("select designation_name,designation_id from designation_header_all where firm_id='$firm_id' and designation_id!='CA'");
//        echo $this->db->last_query();
        $username_array = $this->session->userdata('login_session');

        $username = $username_array['user_id'];

        $qr = $this->db->query("select designation_id from user_header_all where email='$username'");
        $res = $qr->row();
        $designation_id = $res->designation_id;

        $qr1 = $this->db->query("select designation_name from designation_header_all where designation_id='$designation_id'");
        if ($qr1->num_rows() > 0) {
            foreach ($qr1->result() as $row) {
                $designation_name = $row->designation_name;
            }
        } else {
            $designation_name = "";
        }
//        $res1 = $qr1->row();
//        $designation_name = $res1->designation_name;

        $hr_desig = array('designation_id' => $designation_id, 'designation_name' => $designation_name);


        if ($query4->num_rows() > 0) {
            $record = $query4->result();
            $response['result_designation'] = $record;
            $response['hr_desig'] = $hr_desig;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result_designation'] = '';
            $response['hr_desig'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function edit_designation_old() {
        $designation_id = $this->input->post('desig_id');
        $designation_name = $this->input->post('designation_name_edit');
        $designation_role = $this->input->post('designation_role_edit');
        $senior_authority = $this->input->post('senior_authority_edit');
        $monthly_leaves = $this->input->post('monthly_leaves_edit');
        $yearly_leave = $this->input->post('yearly_leave_edit');
        $leave_type_year = $this->input->post('leave_type_year_edit');
        $leave_apply_permission = $this->input->post('leave_apply_permission_edit');
        $holiday_status = $this->input->post('holiday_status_edit');
        $leave_cf = $this->input->post('leave_cf_edit');
        $leave_type1 = $this->input->post('leave_type1_edit');
        $numdays1 = $this->input->post('numofdays1_edit');
        $leave_type2 = $this->input->post('leave_type2_edit');
        $numdays2 = $this->input->post('numofdays2_edit');
        $leave_type3 = $this->input->post('leave_type3_edit');
        $numdays3 = $this->input->post('numofdays3_edit');
        $leave_type4 = $this->input->post('leave_type4_edit');
        $numdays4 = $this->input->post('numofdays4_edit');
        $leave_type5 = $this->input->post('leave_type5_edit');
        $numdays5 = $this->input->post('numofdays5_edit');
        $leave_type6 = $this->input->post('leave_type6_edit');
        $numdays6 = $this->input->post('numofdays6_edit');
        $leave_type7 = $this->input->post('leave_type7_edit');
        $numdays7 = $this->input->post('numofdays7_edit');

        $request_before1 = $this->input->post('req_bfr1_edit');
        $approve_before1 = $this->input->post('aprv_bfr1_edit');
        $request_before2 = $this->input->post('req_bfr2_edit');
        $approve_before2 = $this->input->post('aprv_bfr2_edit');
        $request_before3 = $this->input->post('req_bfr3_edit');
        $approve_before3 = $this->input->post('aprv_bfr3_edit');
        $request_before4 = $this->input->post('req_bfr4_edit');
        $approve_before4 = $this->input->post('aprv_bfr4_edit');
        $request_before5 = $this->input->post('req_bfr5_edit');
        $approve_before5 = $this->input->post('aprv_bfr5_edit');
        $request_before6 = $this->input->post('req_bfr6_edit');
        $approve_before6 = $this->input->post('aprv_bfr6_edit');
        $request_before7 = $this->input->post('req_bfr7_edit');
        $approve_before7 = $this->input->post('aprv_bfr7_edit');
        $modified_on = date('y-m-d h:i:s');
        if (empty(trim($designation_name))) {
            $response['id'] = 'designation_name_edit';
            $response['error'] = 'Enter Designation';
            echo json_encode($response);
            
        } else if (empty(trim($designation_role))) {
            $response['id'] = 'designation_role_edit';
            $response['error'] = 'Enter Designation Role';
            echo json_encode($response);
        } else {
            $type1 = $leave_type1 . ':' . $numdays1 . ':' . $request_before1 . ':' . $approve_before1;

            if (empty($leave_type2 && $numdays2)) {
                $type2 = '';
            } else {
                $type2 = $leave_type2 . ':' . $numdays2 . ':' . $request_before2 . ':' . $approve_before2;
            }
            if (empty($leave_type3 && $numdays3)) {
                $type3 = '';
            } else {
                $type3 = $leave_type3 . ':' . $numdays3 . ':' . $request_before3 . ':' . $approve_before3;
            }
            if (empty($leave_type4 && $numdays4)) {
                $type4 = '';
            } else {
                $type4 = $leave_type4 . ':' . $numdays4 . ':' . $request_before4 . ':' . $approve_before4;
            }
            if (empty($leave_type5 && $numdays5)) {
                $type5 = '';
            } else {
                $type5 = $leave_type5 . ':' . $numdays5 . ':' . $request_before5 . ':' . $approve_before5;
            }
            if (empty($leave_type6 && $numdays6)) {
                $type6 = '';
            } else {
                $type6 = $leave_type6 . ':' . $numdays6 . ':' . $request_before6 . ':' . $approve_before6;
            }
            if (empty($leave_type7 && $numdays7)) {
                $type7 = '';
            } else {
                $type7 = $leave_type7 . ':' . $numdays7 . ':' . $request_before7 . ':' . $approve_before7;
            }
            
            $data_leave = array(
                'type1' => $type1,
                'type2' => $type2,
                'type3' => $type3,
                'type4' => $type4,
                'type5' => $type5,
                'type6' => $type6,
                'type7' => $type7
            );
            $this->db->where('designation_id', $designation_id);
            $res_leave = $this->db->update('leave_header_all', $data_leave);
            $data = array(
                'reporting_designation_id' => $senior_authority,
                'total_yearly_leaves' => $yearly_leave,
                'total_monthly_leaves' => $monthly_leaves,
                'holiday_consider_in_leave' => $holiday_status,
                'carry_forward_period' => $leave_cf,
                'request_leave_from' => $leave_apply_permission,
                'year_type' => $leave_type_year,
                'designation_name' => $designation_name,
                'designation_roles' => $designation_role,
                'reporting_designation_id' => $senior_authority,
                'modified_on' => $modified_on
            );
            
            $this->db->where('designation_id', $designation_id);
            $res = $this->db->update('designation_header_all', $data);

            if ($res == TRUE && $res_leave == TRUE) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }echo json_encode($response);
        }
    }

    public function edit_designation() {
        try {
            $postData = $this->input->post();
            $required = [
                'designation_name_edit' => 'Enter Designation Name',
                'designation_role_edit' => 'Enter Designation Role Name'
            ];

            foreach($required as $field => $error) {
                if(empty(trim($postData[$field] ?? ''))) {
                    echo json_encode([
                        'id' => $postData[$field],
                        'error'=> $error,
                    ]);
                    return;
                }
            }

            $leavesData = [];
            $num = 1;
            for($i = $num; $i <= 7; $i++ ) { 
                $leavesType = $postData["leave_type{$i}_edit"] ?? '';
                $numberOfDays = $postData["numofdays{$i}_edit"] ?? '';
                $requiredBefore = $postData["req_bfr{$i}_edit"] ?? '';
                $approvedBefore = $postData["aprv_bfr{$i}_edit"] ?? '';

                if(!empty($leavesType) && !empty($numberOfDays)) {
                    $leavesData["type{$i}"] = "{$leavesType}:{$numberOfDays}:{$requiredBefore}:{$approvedBefore}";

                } else {
                    $leavesData["type{$i}"] = '';
                }
            }

            $this->db->where('designation_id', $postData['desig_id']);
            $resLeave = $this->db->update('leave_header_all', $leavesData);

            $designationData = [
                'designation_name' => $postData['designation_name_edit'],
                'designation_roles' => $postData['designation_role_edit'],
                'reporting_designation_id' => $postData['senior_authority_edit'] ?? null,
                'total_yearly_leaves' => $postData['yearly_leave_edit'] ?? 0,
                'total_monthly_leaves' => $postData['monthly_leaves_edit'] ?? 0,
                'holiday_consider_in_leave' => $postData['holiday_status_edit'] ?? 0,
                'carry_forward_period' => $postData['leave_cf_edit'] ?? 0,
                'request_leave_from' => $postData['leave_apply_permission_edit'] ?? '',
                'year_type' => $postData['leave_type_year_edit'] ?? '',
                'modified_on' => date('Y-m-d H:i:s')
            ];

            $this->db->where('designation_id', $postData['desig_id']);
            $resdesignation = $this->db->update('designation_header_all', $designationData);

            $response = [
                'message' => ($resLeave && $resdesignation) ? 'success' : 'No data to display',
                'code' => ($resLeave && $resdesignation) ? 200 : 204,
                'status' => ($resLeave && $resdesignation)
            ];

            echo json_encode($response);

        } catch(Exception $e) {
            $response = [
                'message'=> $e->getMessage(),
            ];
            echo json_encode($response);
        }
    }


    public function get_designation() {
        $firm_id = $this->input->post('firm_id');
        $query4 = $this->db->query("select designation_name,designation_id from designation_header_all where designation_id != 'CA' AND firm_id='$firm_id'");
        $username = $this->session->userdata('login_session');

        $qr = $this->db->query("select designation_id from user_header_all where email='$username'");
        $res = $qr->row();
        $designation_id = $res->designation_id;

        $qr1 = $this->db->query("select designation_name from designation_header_all where designation_id='$designation_id'");
        $res1 = $qr1->row();
        $designation_name = $res1->designation_name;

        $hr_desig = array('designation_id' => $designation_id, 'designation_name' => $designation_name);
        if ($query4->num_rows() > 0) {
            $record = $query4->result();
            $response['result_designation'] = $record;
            $response['hr_desig'] = $hr_desig;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result_designation'] = '';
            $response['hr_desig'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_designation_new() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $query4 = $this->db->query("select designation_name,designation_id from designation_header_all where designation_id != 'CA' AND firm_id='$firm_id'");
        $username = $this->session->userdata('login_session');

        $qr = $this->db->query("select designation_id from user_header_all where email='$username'");
        $res = $qr->row();
        $designation_id = $res->designation_id;

        $qr1 = $this->db->query("select designation_name from designation_header_all where designation_id='$designation_id'");
        $res1 = $qr1->row();
        $designation_name = $res1->designation_name;

        $hr_desig = array('designation_id' => $designation_id, 'designation_name' => $designation_name);
        if ($query4->num_rows() > 0) {
            $record = $query4->result();
            $response['result_designation'] = $record;
            $response['hr_desig'] = $hr_desig;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result_designation'] = '';
            $response['hr_desig'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_designation_hqq() {
        $firm_id = $this->input->post('firm_id');
        $query4 = $this->db->query("select designation_name,designation_id from designation_header_all where designation_id != 'CA' AND firm_id='$firm_id'");


        $query_get_hr = $this->designation_model->get_hr($firm_id);
        if ($query_get_hr) {
            $res = $query_get_hr->row();
            $desig_id_hr = $res->designation_id;
        } else {
            $desig_id_hr = "";
        }
        $query_get_hr_desig = $this->designation_model->get_hr_designation($desig_id_hr);
        if ($query_get_hr_desig) {
            $res1 = $query_get_hr_desig->row();
            $desig_name_hr = $res1->designation_name;
        } else {
            $desig_name_hr = "";
        }
        $hr_desig = array('designation_id' => $desig_id_hr, 'designation_name' => $desig_name_hr);
        if ($query4->num_rows() > 0) {
            $record = $query4->result();
            $response['result_designation'] = $record;
            $response['hr_desig'] = $hr_desig;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result_designation'] = '';
            $response['hr_desig'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_designation_fetch() {
        $firm_id = $this->input->post('firm_id');
        $query4 = $this->db->query("select designation_name,designation_id from designation_header_all where firm_id='$firm_id'");

        if ($query4->num_rows() > 0) {
            $record = $query4->result();
            $response['result_designation'] = $record;
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

    public function get_designation_hq() {
        $user_type = $this->input->post('user_type');

        if ($user_type == 2) {
            $firm_id = $this->input->post('firm_id');
        } else {
            $session_data = $this->session->userdata('login_session');
			 $user_login = ($session_data['emp_id']);
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $email_id = ($session_data['user_id']);
            } else {
                $email_id = $this->session->userdata('login_session');
            }
            if ($email_id === "") {
                $email_id = $this->session->userdata('login_session');
            }
            $result2 = $this->db->query("SELECT firm_id FROM `user_header_all` WHERE `email`='$email_id'");
            if ($result2->num_rows() > 0) {
                $record = $result2->row();
                $login_firm_id = $record->firm_id;
            }

            $hr_auth = $this->db->query("select hr_authority from user_header_all where user_id='$user_login' AND user_type='5'");
            if ($this->db->affected_rows() > 0) {
                $record1 = $hr_auth->row();
                $firm_id = $record1->hr_authority;
            }
        }


        $query4 = $this->db->query("select designation_name,designation_id from designation_header_all where firm_id='$firm_id' AND designation_id != 'CA'");
//         echo $this->db->last_query();
        if ($query4->num_rows() > 0) {
            $record = $query4->result();
            $response['result_designation'] = $record;
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

    public function del_designation() {
        $designation_id = $this->input->post('designation_id');
        $qr = $this->db->query("select count(user_id) as cnt from user_header_all where designation_id='$designation_id'")->row();
       
		if ($qr->cnt > 0) {
           // $result = $qr->row();
			
            $response['message'] = 'success';
            $response['body'] = 'This designation is given to ' . $qr->cnt . ' employees, You cannot able to delete.';
			
        } else {
            $query4 = $this->db->query("delete from designation_header_all where designation_id='$designation_id'");
            if ($this->db->affected_rows() > 0) {
                $query5 = $this->db->query("delete from leave_header_all where designation_id='$designation_id'");
                $response['message'] = 'success';
                $response['body'] = 'Designation deleted successfully.';
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display.';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }


        echo json_encode($response);
    }

    public function get_data_designation_edit() {
        $designation_id = $this->input->post('desig_id');
        $firm_id = $this->input->post('firm_id');
        $is_virtual = $this->db->select("is_virtual")->where("firm_id", $firm_id)->get("partner_header_all")->row();
        if (!is_null($is_virtual)) {
            $response['is_virtual'] = $is_virtual->is_virtual;
        } else {
            $response['is_virtual'] = 0;
        }
        
        $query_get_senior_autority = $this->db->query("SELECT reporting_designation_id FROM `designation_header_all` where `designation_id`= '$designation_id' AND firm_id='$firm_id'");
        if ($query_get_senior_autority->num_rows() > 0) {
            $get_senior_id = $query_get_senior_autority->row();
            $sid = $get_senior_id->reporting_designation_id;
            $query_get_senior_autority_name = $this->db->query("SELECT designation_name FROM `designation_header_all` where `designation_id`= '$designation_id' AND firm_id='$firm_id'");
            foreach ($query_get_senior_autority_name->result() as $row) {
                $get_single_name = $row->designation_name;
            }
        } else {
            
        }
        
        $sql = "SELECT * FROM `designation_header_all` where `designation_id`= '$designation_id' AND firm_id='$firm_id'";
        $query_edit_designation = $this->db->query($sql);
        if ($query_edit_designation->num_rows() > 0) {
            $record = $query_edit_designation->result();
            $record_1 = $query_edit_designation->row();

            $senior_designation = $record_1->reporting_designation_id;
            $qr = $this->db->query("SELECT designation_name from designation_header_all where designation_id='$senior_designation'");
            if ($this->db->affected_rows() > 0) {
                $rs = $qr->row();
                $senior_designation = $row->designation_name;
            } else {
                $senior_designation = '';
            }

            $query_edit_leave = $this->db->query("SELECT * FROM `leave_header_all` where `designation_id`= '$designation_id' AND firm_id='$firm_id'");
            if ($query_edit_leave->num_rows() > 0) {
                $record_leave = $query_edit_leave->result();
            } else {
                $record_leave = '';
            }

            $query = $this->db->query("SELECT * from partner_header_all where firm_id='$firm_id'");
            if ($query->num_rows() > 0) {
                $record1 = $query->row();
                $leave_type_fixed = $record1->leave_type_fixed;
                $firm_name = $record1->firm_name;
            }

            $response['designation_name'] = $get_single_name;
            $response['leave_permit'] = $leave_type_fixed;
            $response['firm_name'] = $firm_name;
            $response['result_edit_designation'] = $record;
            $response['senior_designation'] = $senior_designation;
            $response['result_edit_leave'] = $record_leave;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result_edit_designation'] = '';
            $response['firm_name'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function get_data_designation() {
        $firm_id = $this->input->post('firm_id');


        $query_edit_designation = $this->db->query("SELECT * FROM `leave_header_all` where `firm_id`= '$firm_id' AND `designation_id` = 'CA'");
        // echo $this->db->last_query();
        if ($query_edit_designation->num_rows() > 0) {

            $query = $this->db->query("SELECT * from partner_header_all where firm_id='$firm_id'");
            if ($query->num_rows() > 0) {
                $record1 = $query->row();
                $leave_type_fixed = $record1->leave_type_fixed;
            }


            $query2 = $this->db->query("SELECT * from designation_header_all where `firm_id`= '$firm_id' AND `designation_id` = 'CA'");
            echo $this->db->last_query();
            if ($query2->num_rows() > 0) {
                $record12 = $query2->result();
            } else {
                $record12 = "";
            }
            $record = $query_edit_designation->result();
            $response['result_designation'] = $record;
            $response['record12'] = $record12;
            $response['leave_permit'] = $leave_type_fixed;
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

    public function get_data_designation_ca() {
        $firm_id = $this->input->post('firm_id');
//        echo "SELECT * FROM `leave_header_all` where `firm_id`= '$firm_id' AND `designation_id` = 'CA'";
        $query_edit_designation = $this->db->query("SELECT * FROM `leave_header_all` where `firm_id`= '$firm_id' AND `designation_id` = 'CA'");
        if ($query_edit_designation->num_rows() > 0) {

            $query = $this->db->query("SELECT * from partner_header_all where firm_id='$firm_id'");
            if ($query->num_rows() > 0) {
                $record1 = $query->row();
                $leave_type_fixed = $record1->leave_type_fixed;
            }
            $record = $query_edit_designation->result();
            $response['result_designation'] = $record;
            $response['leave_permit'] = $leave_type_fixed;
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

//    HQ DESIGNATION

    public function show_designation_hq($firm_id = '') {
        $query_view_designation = $this->db->query("SELECT * FROM `designation_header_all` where `firm_id`= '$firm_id'");
        if ($query_view_designation->num_rows() > 0) {
            $record = $query_view_designation->result();
            $data['result_view_designation'] = $record;
        } else {
            $data['result_view_designation'] = "";
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
        $data['prev_title'] = "HQ Designation Management";
        $data['page_title'] = "HQ Designation Management";
        $this->load->view('hq_admin/Designation', $data);
    }

    public function create_designation_hq() {
        $designation_id = $this->generate_designation_id();
        $designation_name = $this->input->post('designation_name');
        $created_on = date('y-m-d h:i:s');
        $username_array = $this->session->userdata('login_session');
        $usertype = $username_array['user_type'];
        $user_id = $username_array['emp_id'];

        if ($usertype == 5) {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $firm_id = $result['firm_id'];
            }
            $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id' AND user_type='5' ");
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = $this->input->post('hq_firm_name');
        }
        if (empty(trim($firm_id))) {
            $response['id'] = 'hq_firm_name';
            $response['error'] = 'Enter Office Name';
            $response['error'] = 'Enter Office Name';
            echo json_encode($response);
            exit;
        }
        $created_by = $this->input->post('hdn_user_id');
        $designation_role = $this->input->post('designation_role');

	


        $query_fetch_boss_id = $this->db->query("SELECT linked_with_boss_id from user_header_all where firm_id='$firm_id'");
        if ($query_fetch_boss_id->num_rows() > 0) {
            $record_leave = $query_fetch_boss_id->row();
            $boss_id = $record_leave->linked_with_boss_id;
        }
        $result_data = $this->search_same_designation($designation_name, $firm_id);

        $get_leave_query = $this->db->query("select * from leave_header_all where firm_id='$firm_id' and designation_id='CA'");
        $get_leave_data = $get_leave_query->row();
        //$type1 = $get_leave_data->type1;
        //$type2 = $get_leave_data->type2;
        //$type3 = $get_leave_data->type3;
        //$type4 = $get_leave_data->type4;
        //$type5 = $get_leave_data->type5;
        // $type6 = $get_leave_data->type6;
        // $type7 = $get_leave_data->type7;
//        echo $this->db->last_query();

        $get_designation_query = $this->db->query("select * from designation_header_all where firm_id='$firm_id' and designation_id='CA'");
        $get_designation_data = $get_designation_query->row();
        // $yearly_leaves = $get_designation_data->total_yearly_leaves;
//        $monthly_leaves = $get_designation_data->total_monthly_leaves;

        if (empty(trim($designation_name))) {
            $response['id'] = 'designation_name';
            $response['error'] = 'Enter Designation';
            echo json_encode($response);
        } else if ($result_data !== false) {
            $response['id'] = 'designation_name';
            $response['error'] = 'This Designation Is Already Created For This Office.';
            echo json_encode($response);
        } else if (empty(trim($designation_role))) {
            $response['id'] = 'designation_role';
            $response['error'] = 'Enter Designation Role';
            echo json_encode($response);
        } else {
            $data_leave = array(
                'designation_id' => $designation_id,
                // 'type1' => $type1,
                //'type2' => $type2,
                // 'type3' => $type3,
                // 'type4' => $type4,
                // 'type5' => $type5,
                // 'type6' => $type6,
                // 'type7' => $type7,
                'firm_id' => $firm_id,
                'created_on' => $created_on,
                'boss_id' => $boss_id
            );
            $p_data_leave = $this->db->insert('leave_header_all', $data_leave);

            $data = array(
                'designation_id' => $designation_id,
                'designation_name' => $designation_name,
                'designation_roles' => $designation_role,
                //'total_yearly_leaves' => $yearly_leaves,
                // 'total_monthly_leaves' => $monthly_leaves,
                'firm_id' => $firm_id,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'boss_id' => $boss_id
            );
            $add_designation = $this->designation_model->add_designation($data);

            if ($add_designation == TRUE) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }echo json_encode($response);
        }
    }

}
