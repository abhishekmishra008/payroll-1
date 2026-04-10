<?php

class Ticket_report_controller extends CI_Controller {

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
//            $firm_name = $record->user_name;
            if ($firm_logo == "") {

                $data['logo'] = "";
//                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
//                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
//            $data['firm_name_nav'] = "";
        }
        $data['prev_title'] = "Ticket Report";
        $data['page_title'] = "Ticket Report";


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
//        var_dump($idd);
        $firm_name = $user_type->user_name;
        $data['u_type'] = $u_type;
        $data['firm_name'] = $firm_name;
        $emp = $this->db->query("select emp_id from ticket_header_all where FIND_IN_SET(emp_id, emp_id) in (SELECT user_id FROM `user_header_all` where email='$user_id')");
        $emp_id = $emp->result();
//        echo $this->db->last_query();
        if (!is_null($emp_id)) {
            foreach ($emp_id as $emp_idd) {
                $empp_id = explode(',', $emp_idd->emp_id);
                $data['emp_id'] = $empp_id;
//            var_dump(count($empp_id));
            }
        } else {
//            $empp_id = '';
            $data['emp_id'] = '';
        }
//        var_dump($data['emp_id']);
//        var_dump($empp_id);
//        if (count($empp_id) > 1) {
//
//            $data['emp_id'] = $empp_id;
//        } else {
//            $data['emp_id'] = '';
//        }


        $user = $this->db->query("select assign_to from ticket_transaction_all where status='1' and assign_to = (SELECT user_id FROM `user_header_all` where email='$user_id')");
        $userdata = $user->row();
        $data['userid'] = $userdata;

        $this->load->view('hq_admin/Employee_ticket_report', $data);
    }

    public function get_employee_data() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $ids = $this->db->query("select * from user_header_all where user_type!='5' and email='$user_id'")->result();

        if (count($ids) > 0) {
            foreach ($ids as $row3) {

                $user_type = $row3->user_type;
//                                var_dump($user_id);
            }
        }
        if ($user_type == 2) {
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
                $this->db->where("reporting_to = '$get_boss_id' AND firm_activity_status = 'A' And  is_virtual!='1' ");
                $query_2 = $this->db->get();
                if ($query_2->num_rows() > 0) {
                    $data = array('firm_name' => array(), 'firm_id' => array(), 'boss_id' => array());
                    foreach ($query_2->result() as $row) {
                        // var_dump($row->boss_id);
                        $query_get_employe = $this->db->query("select user_id,linked_with_boss_id,user_name from user_header_all where user_type!='2'  and linked_with_boss_id='" . $row->boss_id . "'")->result();
                        //  echo $this->db->last_query();
                        if (count($query_get_employe) > 0) {
                            foreach ($query_get_employe as $query_get_employee) {
                                $data['user_name'][] = $query_get_employee->user_name;
                                $data['user_id'][] = $query_get_employee->user_id;
                                $query_get_firm = $this->db->query("select * from user_header_all where user_type!='2' and linked_with_boss_id='" . $query_get_employee->linked_with_boss_id . "'")->result();
                                foreach ($query_get_firm as $res) {
                                    $firmdata = $this->db->query("select * from partner_header_all where firm_id='" . $res->firm_id . "'")->result();
                                    foreach ($firmdata as $firm) {
                                        $data['b_name'][] = $firm->firm_name;
                                    }
                                }
//                            $response['emp_data'][] = ['user_name' => $query_get_employee->user_name, 'user_id' => $query_get_employee->user_id, 'firm_name' => $firm->firm_name];
                            }
                        }
                    }
                    $response['emp_data'][] = [$data['user_name'], $data['user_id'], $data['b_name']];



                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
        } else if ($user_type == 3) {

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
                $this->db->where("boss_id = '$get_boss_id' AND firm_activity_status = 'A'");
                $query_2 = $this->db->get();
                if ($query_2->num_rows() > 0) {
                    $data = array('firm_name' => array(), 'firm_id' => array(), 'boss_id' => array());
                    foreach ($query_2->result() as $row) {
                        // var_dump($row->boss_id);
                        $query_get_employe = $this->db->query("select user_id,linked_with_boss_id,user_name from user_header_all where user_type!='2'  and linked_with_boss_id='" . $row->boss_id . "'")->result();
                        //  echo $this->db->last_query();
                        if (count($query_get_employe) > 0) {
                            foreach ($query_get_employe as $query_get_employee) {
                                $data['user_name'][] = $query_get_employee->user_name;
                                $data['user_id'][] = $query_get_employee->user_id;
                                $query_get_firm = $this->db->query("select * from user_header_all where user_type!='2' and linked_with_boss_id='" . $query_get_employee->linked_with_boss_id . "'")->result();
                                foreach ($query_get_firm as $res) {
                                    $firmdata = $this->db->query("select * from partner_header_all where firm_id='" . $res->firm_id . "'")->result();
                                    foreach ($firmdata as $firm) {
                                        $data['b_name'][] = $firm->firm_name;
                                    }
                                }
//                            $response['emp_data'][] = ['user_name' => $query_get_employee->user_name, 'user_id' => $query_get_employee->user_id, 'firm_name' => $firm->firm_name];
                            }
                        }
                    }
                    $response['emp_data'][] = [$data['user_name'], $data['user_id'], $data['b_name']];



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

        echo json_encode($response);
    }

    public function graph() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $employee_id = $this->input->post('employee_id');
        $user = $this->db->query("SELECT count(case WHEN status='1' then 1 end) as 'Assigned',count(case WHEN Is_accpet='3' then 1 end) as 'accepted',count(case WHEN Is_accpet='4' then 1 end) as 'rejected',count(case WHEN status='1' and Is_accpet='0' then 1 end) as 'pending' FROM `ticket_transaction_all` where  assign_to='$employee_id' ")->result();
//echo $this->db->last_query();

        $emp_name = $this->db->query("select * from user_header_all where  user_id='$employee_id' ");
        $employe_name = $emp_name->row();
        $table = "";
        foreach ($user as $userdata) {
            $table .= '<tr><th scope="col" style="text-align: center;">Employee Name</th>'
                    . '   <th scope="col" style="text-align: center;">Assigned ticket</th>'
                    . '  <th scope="col" style="text-align: center;">Completed </th>'
                    . '  <th scope="col" style="text-align: center;">Rejected </th>'
                    . ' <th scope="col" style="text-align: center;">Pending </th></tr><tr><td>' . $employe_name->user_name . '</td> <td>' . $userdata->Assigned . '</td><td>' . $userdata->accepted . '</td><td>' . $userdata->rejected . '</td><td>' . $userdata->pending . '</td></tr>';


            $abcd1 = array();

            for ($a = 0; $a < $userdata->Assigned; $a++) { //loop to convert string data into integer
                $abcd1[] = $userdata->Assigned[$a];
                //var_dump($userdata->Assigned[$a]);
                $aa1 = settype($abcd1[$a], "int");
                break;
            }

//            var_dump($abcd1);
            $abcd2 = array();
            for ($b = 0; $b < $userdata->accepted; $b++) { //loop to convert string data into integer
                $abcd2[] = $userdata->accepted [$b];
                $aa2 = settype($abcd2[$b], "int");
                break;
            }
//            var_dump($abcd2);
            $abcd3 = array();
            for ($c = 0; $c < $userdata->rejected; $c++) { //loop to convert string data into integer
                $abcd3[] = $userdata->rejected [$c];
                $aa1 = settype($abcd3[$c], "int");
                break;
            }
//            var_dump($abcd3);
            $abcd4 = array();
            for ($d = 0; $d < $userdata->pending; $d++) { //loop to convert string data into integer
                $abcd4[] = $userdata->pending [$d];
                $aa1 = settype($abcd4[$d], "int");
                break;
            }
//            var_dump($abcd4);
        }
        $status1 = array('Assigned', 'Completed', 'Rejected', 'Pending');
        $s = implode("___", $status1);
        $response['status1'][] = $s;
        $response['message'] = 'success';
        $response['code'] = 200;
        $response['status'] = true;
        $response['branch1'] = $abcd1;
        $response['branch2'] = $abcd2;
        $response['branch3'] = $abcd3;
        $response['branch4'] = $abcd4;

        echo json_encode($response);
    }

}
