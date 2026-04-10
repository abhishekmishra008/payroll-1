<?php

class graphs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('firm_model');
        $this->load->model('designation_model');
    }

    public function leave_graph() { //function to load page Leave management graphs of CA
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

        $data['firm_id'] = $firm_id;
        $this->load->view('client_admin/leave_graphs', $data);
    }

    public function leave_graph_hq() { //function to load page Leave management graphs of HQ
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

        $data['firm_id'] = $firm_id;
        $this->load->view('hq_admin/leave_graphs', $data);
    }

    public function due_date_task_graphs_hq() { //function to load page Leave management graphs of HQ
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
        $data['firm_id'] = $firm_id;
        $this->load->view('hq_admin/due_date_graphs', $data);
    }
    
     public function due_date_graph_ca() { //function to load page Leave management graphs of CA
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
        $data['firm_id'] = $firm_id;
        $this->load->view('client_admin/due_date_graphs', $data);
    }

    public function task_allotment_graph() { //function to load page task allotment graphs of CA
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

        $data['firm_id'] = $firm_id;
        $this->load->view('client_admin/task_allotment_graphs', $data);
    }

    public function task_allotment_graph_hq() { //function to load page task allotment graphs of HQ
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

        $data['firm_id'] = $firm_id;
        $this->load->view('hq_admin/task_allotment_graphs', $data);
//         $this->load->view('hq_admin/Dashboard', $data);
    }

    public function get_graphs_leaves() {  //get leaves taken by branchs year wise
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $query = $this->db->query("select leave_date from leave_transaction_all where firm_id='$firm_id'");
        $date = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $date[] = $row->leave_date;
            }
            $r6 = "";
            $r5 = "";
            $monthName = array();
            for ($i = 0; $i < count($date); $i++) {
                $r1 = $date[$i];
                $r2 = explode(' ', $r1);
                $r3 = $r2[0];
                $r4 = explode('-', $r3);
                $month = $r4[1];
                $r5 .= $year . "-" . $month;
                $r6 .= $year . "-" . $month . ",";

                $monthNum = $month;
                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                $monthName[] = $dateObj->format('F');
            }
            $exp_mon = explode(',', $r6);
            $month1 = array_values(array_unique($exp_mon));
            $month_Name = array_values(array_unique($monthName));
            $count_firm = array();
            for ($i = 0; $i < count($month1) - 1; $i++) {
                $query_get_leave_count = $this->db->query("select count(firm_id) as firm_count from leave_transaction_all where leave_date LIKE '$month1[$i]%' and firm_id='$firm_id' ");
                if ($this->db->affected_rows() > 0) {
                    $res = $query_get_leave_count->row();
                    $count_firm[] = $res->firm_count;
                } else {
                    
                }
            }
            $count_array = array_sum($count_firm);
            $ratio = array();
            for ($o = 0; $o < sizeof($count_firm); $o++) {
                $abc[] = $count_firm[$o];
                $aa1 = settype($abc[$o], "int");
                if ($count_array != 0) {
                    $ratio[] = ($abc[$o] / $count_array) * 100;
                    $aa2 = settype($ratio[$o], "int");
                } else {
                    
                }
            }
            $response['months'] = $month_Name;
            $response['count_firm'] = $abc;
            $response['ratio'] = $ratio;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_leave_type() { //get leave types
        $firm_id = $this->input->post('firm_id');
        $qr = $this->db->query("select * from leave_header_all where firm_id='$firm_id'");
        if ($this->db->affected_rows() > 0) {
            $result = $qr->result();
            $types = array();
            foreach ($result as $row) {
                for ($i = 1; $i < 8; $i++) {
                    $type_a = "type" . $i;
                    $type = $row->$type_a;
                    if ($type != "") {
                        $exp_data = explode(":", $type);
                        $types[] = $exp_data[0];
                    } else {
                        
                    }
                }
            }
            $leave_types = array_values(array_unique($types));
            $response['leave_types'] = $leave_types;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_graphs_leaves_type_wise() { //function to get graph leave type wise
        $firm_id = $this->input->post('firm_id');
        $leave_type = $this->input->post('leave_type');
        $year = $this->input->post('year');
        $query = $this->db->query("select leave_date from leave_transaction_all where firm_id='$firm_id'");
        $date = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $date[] = $row->leave_date;
            }
            $r6 = "";
            $r5 = "";
            $monthName = array();
            for ($i = 0; $i < count($date); $i++) {
                $r1 = $date[$i];
                $r2 = explode(' ', $r1);
                $r3 = $r2[0];
                $r4 = explode('-', $r3);
                $month = $r4[1];
                $r5 .= $year . "-" . $month;
                $r6 .= $year . "-" . $month . ",";

                $monthNum = $month;
                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                $monthName[] = $dateObj->format('F');
            }
            $exp_mon = explode(',', $r6);
            $month1 = array_values(array_unique($exp_mon));
            $month_Name = array_values(array_unique($monthName));
            $count_firm = array();
            for ($i = 0; $i < count($month1) - 1; $i++) {
                $query_get_leave_count = $this->db->query("select count(firm_id) as firm_count from leave_transaction_all where leave_date LIKE '$month1[$i]%' and leave_type ='$leave_type' and firm_id='$firm_id' ");
                if ($this->db->affected_rows() > 0) {
                    $res = $query_get_leave_count->row();
                    $count_firm[] = $res->firm_count;
                } else {
                    
                }
            }
            $sum_arr = array_sum($count_firm);
            $ratio = array();
            $count_firm_data = array();
            for ($o = 0; $o < sizeof($count_firm); $o++) {
                $count_firm_data[] = $count_firm[$o];
                $aa1 = settype($count_firm_data[$o], "int");
                if ($sum_arr != 0) {
                    $ratio[] = ($count_firm_data[$o] / $sum_arr) * 100;
                    $aa2 = settype($ratio[$o], "int");
                } else {
                    
                }
            }
            $response['total_firm_leaves'] = $count_firm_data;
            $response['month_data'] = $month_Name;
            $response['ratio'] = $ratio;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_graphs_all_leaves_type() { //function to get all leave type graphs
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $qr = $this->db->query("select * from leave_header_all where firm_id='$firm_id'");
        if ($this->db->affected_rows() > 0) {
            $result = $qr->result();
            $types = array();
            foreach ($result as $row) {
                for ($i = 1; $i < 8; $i++) {
                    $type_a = "type" . $i;
                    $type = $row->$type_a;
                    if ($type != "") {
                        $exp_data = explode(":", $type);
                        $types[] = $exp_data[0];
                    } else {
                        
                    }
                }
            }
            $leave_types = array_values(array_unique($types));
            $count_leave_types = count($leave_types);
            $leaves_count = array();
            for ($j = 0; $j < $count_leave_types; $j++) {

                $qur = $this->db->query("select count(leave_type) as leave_type_count from leave_transaction_all where"
                        . " leave_date LIKE '$year%' and leave_type='$leave_types[$j]' and firm_id='$firm_id'");
                if ($this->db->affected_rows() > 0) {
                    $res = $qur->row();
                    $leaves_count[] = $res->leave_type_count;
                } else {
                    $leaves_count[] = "";
                }
            }
            $sum_arr = array_sum($leaves_count);
            $ratio = array();
            $leaves_count_data = array();
            for ($o = 0; $o < sizeof($leaves_count); $o++) {
                $leaves_count_data[] = $leaves_count[$o];
                $aa1 = settype($leaves_count_data[$o], "int");
                if ($sum_arr != 0) {
                    $ratio[] = ($leaves_count_data[$o] / $sum_arr) * 100;
                    $aa2 = settype($ratio[$o], "int");
                } else {
                    
                }
            }
            $response['leave_types'] = $leave_types;
            $response['leave_types_count'] = $leaves_count_data;
            $response['ratio'] = $ratio;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_graph_emp_wise() { //get leave employee wise and ratio of leave taken by employee
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $user_id = $this->input->post('user_id');
        $query_get_emp = $this->db->query("select user_name,user_id from user_header_all where firm_id='$firm_id' and is_employee='1'");
        $leave_type = array();
        $leave_count = array();
        $query_get_leave = $this->db->query("select leave_type,  count(leave_type) as cnt from leave_transaction_all where user_id='$user_id' and leave_date LIKE '$year%' GROUP BY leave_type");
        if ($this->db->affected_rows() > 0) {
            $result = $query_get_leave->result();
            foreach ($result as $row) {
                $leave_type[] = $row->leave_type;
                $leave_count[] = $row->cnt;
            }
            $sum_arr = array_sum($leave_count);
            $ratio = array();
            $leaves_count_data = array();
            for ($o = 0; $o < sizeof($leave_count); $o++) {
                $leaves_count_data[] = $leave_count[$o];
                $aa1 = settype($leaves_count_data[$o], "int");
                if ($sum_arr != 0) {
                    $ratio[] = ($leaves_count_data[$o] / $sum_arr) * 100;
                    $aa2 = settype($ratio[$o], "int");
                } else {
                    
                }
            }
            $response['leave_types'] = $leave_type;
            $response['leave_types_count'] = $leaves_count_data;
            $response['ratio'] = $ratio;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_graph_monthly_emp_wise() { //function to get graph of leave monthly employee wise
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $user_id = $this->input->post('user_id');
        $query = $this->db->query("select leave_date from leave_transaction_all where user_id='$user_id'");
        $date = array();
        $monthName = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $date[] = $row->leave_date;
            }
            $r6 = "";
            $r5 = "";
            for ($i = 0; $i < count($date); $i++) {
                $r1 = $date[$i];
                $r2 = explode(' ', $r1);
                $r3 = $r2[0];
                $r4 = explode('-', $r3);
                $month = $r4[1];
                $r5 .= $year . "-" . $month;
                $r6 .= $year . "-" . $month . ",";

                $monthNum = $month;
                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                $monthName[] = $dateObj->format('F');
            }
            $exp_mon = explode(',', $r6);
            $month1 = array_values(array_unique($exp_mon));
            $month_Name = array_values(array_unique($monthName));
//            $leave_type = array();
            $leave_count = array();
            for ($i = 0; $i < count($month1) - 1; $i++) {
                $query_get_leave_count = $this->db->query("select leave_type,  count(leave_type) as cnt from leave_transaction_all where "
                        . "user_id='$user_id' and leave_date LIKE '$month1[$i]%' GROUP BY leave_type");
                if ($this->db->affected_rows() > 0) {
                    $result = $query_get_leave_count->result();
                    foreach ($result as $row) {
//                        $leave_type[] = $row->leave_type;
                        $leave_count[] = $row->cnt;
                    }
                } else {
                    
                }
            }
            $leaves_count_data = array();
            for ($o = 0; $o < sizeof($leave_count) - 1; $o++) {
                $leaves_count_data[] = $leave_count[$o];
                $aa1 = settype($leaves_count_data[$o], "int");
            }
//            $response['leave_types'] = $leave_type;
            $response['leave_types_count'] = $leaves_count_data;
            $response['month'] = $month_Name;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    //task allotment graph functions 
    public function get_graph_task_emp_wise() { //get task employee wise
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $user_id = $this->input->post('user_id');
        $query = $this->db->query("select created_on from customer_task_allotment_all where firm_id='$firm_id'");
        $date = array();
        $monthName = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $date[] = $row->created_on;
            }
            $r6 = "";
            $r5 = "";
            for ($i = 0; $i < count($date); $i++) {
                $r1 = $date[$i];
                $r2 = explode(' ', $r1);
                $r3 = $r2[0];
                $r4 = explode('-', $r3);
                $month = $r4[1];
                $r5 .= $year . "-" . $month;
                $r6 .= $year . "-" . $month . ",";

                $monthNum = $month;
                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                $monthName[] = $dateObj->format('F');
            }
            $exp_mon = explode(',', $r6);
            $month1 = array_values(array_unique($exp_mon));
            $month_Name = array_values(array_unique($monthName));
//            $status=array();
            $all_assig_count = array();
            $processing_assig_count = array();
            for ($i = 0; $i < count($month1) - 1; $i++) {

                $query_get_TA_count = $this->db->query("SELECT count(task_assignment_id) as all_assig_count from customer_task_allotment_all where alloted_to_emp_id='$user_id' and created_on LIKE '$month1[$i]%'");
                if ($this->db->affected_rows() > 0) {
                    $result = $query_get_TA_count->row();
                    $all_assig_count[] = $result->all_assig_count;
                } else {
                    
                }
                $query_get_TA_count1 = $this->db->query("SELECT count(task_assignment_id) as processing_assig_count from customer_task_allotment_all where "
                        . "alloted_to_emp_id='$user_id' and created_on LIKE '$month1[$i]%' and status='2'");
                if ($this->db->affected_rows() > 0) {
                    $result1 = $query_get_TA_count1->row();
                    $processing_assig_count[] = $result1->processing_assig_count;
                } else {
                    
                }
                $query_get_TA_count2 = $this->db->query("SELECT count(task_assignment_id) as complete_assig_count from customer_task_allotment_all where "
                        . "alloted_to_emp_id='$user_id' and created_on LIKE '$month1[$i]%'  and status='3'");
                if ($this->db->affected_rows() > 0) {
                    $result2 = $query_get_TA_count2->row();
                    $complete_assig_count[] = $result2->complete_assig_count;
                } else {
                    
                }
            }
            $all_assig_count1 = array();
            $processing_assig_count1 = array();
            $complete_assig_count1 = array();
            $ratio_completion = array();
            $sum = array_sum($all_assig_count);
            for ($o = 0; $o < sizeof($all_assig_count); $o++) {
                $all_assig_count1[] = $all_assig_count[$o];
                $aa1 = settype($all_assig_count1[$o], "int");

                $processing_assig_count1[] = $processing_assig_count[$o];
                $aa1 = settype($processing_assig_count1[$o], "int");

                $complete_assig_count1[] = $complete_assig_count[$o];
                $aa1 = settype($complete_assig_count1[$o], "int");
                if ($sum != 0) {
                    $ratio_completion[] = ($complete_assig_count[$o] / $sum) * 100;
                    $aa1 = settype($ratio_completion[$o], "int");
                } else {
                    
                }
            }
            $response['all_assig_count'] = $all_assig_count1;
            $response['processing_assig_count'] = $processing_assig_count1;
            $response['complete_assig_count'] = $complete_assig_count1;
            $response['months'] = $month_Name;
            $response['ratio_completion'] = $ratio_completion;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_graph_task_year_wise() { //function to get graph of task allotment year wise
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $query = $this->db->query("select created_on from customer_task_allotment_all where firm_id='$firm_id'");
        $date = array();
        $monthName = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $date[] = $row->created_on;
            }
            $r6 = "";
            $r5 = "";
            for ($i = 0; $i < count($date); $i++) {
                $r1 = $date[$i];
                $r2 = explode(' ', $r1);
                $r3 = $r2[0];
                $r4 = explode('-', $r3);
                $month = $r4[1];
                $r5 .= $year . "-" . $month;
                $r6 .= $year . "-" . $month . ",";

                $monthNum = $month;
                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                $monthName[] = $dateObj->format('F');
            }
            $exp_mon = explode(',', $r6);
            $month1 = array_values(array_unique($exp_mon));
            $month_Name = array_values(array_unique($monthName));
//            $status=array();
            $all_assig_count = array();
            $processing_assig_count = array();
            $complete_assig_count = array();
            for ($i = 0; $i < count($month1) - 1; $i++) {
                $query_get_TA_count = $this->db->query("SELECT count(task_assignment_id) as all_assig_count from customer_task_allotment_all where firm_id='$firm_id' and created_on LIKE '$month1[$i]%' and sub_task_id=''");
                if ($this->db->affected_rows() > 0) {
                    $result = $query_get_TA_count->row();
                    $all_assig_count[] = $result->all_assig_count;
                } else {
                    
                }
                $query_get_TA_count1 = $this->db->query("SELECT count(task_assignment_id) as processing_assig_count from customer_task_allotment_all where "
                        . "firm_id='$firm_id' and created_on LIKE '$month1[$i]%' and status='2' and sub_task_id=''");
                if ($this->db->affected_rows() > 0) {
                    $result1 = $query_get_TA_count1->row();
                    $processing_assig_count[] = $result1->processing_assig_count;
                } else {
                    
                }
                $query_get_TA_count2 = $this->db->query("SELECT count(task_assignment_id) as complete_assig_count from customer_task_allotment_all where "
                        . "firm_id='$firm_id' and created_on LIKE '$month1[$i]%'  and status='3' and sub_task_id=''");
                if ($this->db->affected_rows() > 0) {
                    $result2 = $query_get_TA_count2->row();
                    $complete_assig_count[] = $result2->complete_assig_count;
                } else {
                    
                }
            }
            $all_assig_count1 = array();
            $processing_assig_count1 = array();
            $complete_assig_count1 = array();
            for ($o = 0; $o < sizeof($all_assig_count); $o++) {
                $all_assig_count1[] = $all_assig_count[$o];
                $aa1 = settype($all_assig_count1[$o], "int");

                $processing_assig_count1[] = $processing_assig_count[$o];
                $aa1 = settype($processing_assig_count1[$o], "int");

                $complete_assig_count1[] = $complete_assig_count[$o];
                $aa1 = settype($complete_assig_count1[$o], "int");
            }
            $response['all_assig_count_firm'] = $all_assig_count1;
            $response['processing_assig_count_firm'] = $processing_assig_count1;
            $response['complete_assig_count_firm'] = $complete_assig_count1;
            $response['months'] = $month_Name;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_graph_task_month_wise() { //function to get graph of task allotment month wise
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $mon_year = $year . "-" . $month;

        $all_assig_count = array();
        $processing_assig_count = array();
        $complete_assig_count = array();
        $initiate_assig_count = array();
        $close_assig_count = array();
        $query_get_TA_count = $this->db->query("SELECT count(task_assignment_id) as all_assig_count from customer_task_allotment_all where firm_id='$firm_id' and created_on LIKE '$mon_year%' and sub_task_id=''");
        if ($this->db->affected_rows() > 0) {
            $result = $query_get_TA_count->row();
            $all_assig_count[] = $result->all_assig_count;

            $query_get_TA_count1 = $this->db->query("SELECT count(task_assignment_id) as processing_assig_count from customer_task_allotment_all where "
                    . "firm_id='$firm_id' and created_on LIKE '$mon_year%' and status='2' and sub_task_id=''");
            if ($this->db->affected_rows() > 0) {
                $result1 = $query_get_TA_count1->row();
                $processing_assig_count[] = $result1->processing_assig_count;
            } else {
                
            }
            $query_get_TA_count2 = $this->db->query("SELECT count(task_assignment_id) as complete_assig_count from customer_task_allotment_all where "
                    . "firm_id='$firm_id' and created_on LIKE '$mon_year%'  and status='3' and sub_task_id=''");
            if ($this->db->affected_rows() > 0) {
                $result2 = $query_get_TA_count2->row();
                $complete_assig_count[] = $result2->complete_assig_count;
            } else {
                
            }
            $query_get_TA_count3 = $this->db->query("SELECT count(task_assignment_id) as initiate_assig_count from customer_task_allotment_all where "
                    . "firm_id='$firm_id' and created_on LIKE '$mon_year%'  and status='1' and sub_task_id=''");
            if ($this->db->affected_rows() > 0) {
                $result3 = $query_get_TA_count3->row();
                $initiate_assig_count[] = $result3->initiate_assig_count;
            } else {
                
            }
            $query_get_TA_count4 = $this->db->query("SELECT count(task_assignment_id) as close_assig_count from customer_task_allotment_all where "
                    . "firm_id='$firm_id' and created_on LIKE '$mon_year%'  and status='4' and sub_task_id=''");
            if ($this->db->affected_rows() > 0) {
                $result4 = $query_get_TA_count4->row();
                $close_assig_count[] = $result4->close_assig_count;
            } else {
                
            }
            $all_assig_count1 = array();
            $processing_assig_count1 = array();
            $complete_assig_count1 = array();
            $initiate_assig_count1 = array();
            $close_assig_count1 = array();
            for ($o = 0; $o < sizeof($all_assig_count); $o++) {
                $all_assig_count1[] = $all_assig_count[$o];
                $aa1 = settype($all_assig_count1[$o], "int");

                $processing_assig_count1[] = $processing_assig_count[$o];
                $aa1 = settype($processing_assig_count1[$o], "int");

                $complete_assig_count1[] = $complete_assig_count[$o];
                $aa1 = settype($complete_assig_count1[$o], "int");

                $initiate_assig_count1[] = $initiate_assig_count[$o];
                $aa1 = settype($initiate_assig_count1[$o], "int");

                $close_assig_count1[] = $close_assig_count[$o];
                $aa1 = settype($close_assig_count1[$o], "int");
            }
//            var_dump($close_assig_count1);
            $response['all_assig_count'] = $all_assig_count1;
            $response['processing_assig_count'] = $processing_assig_count1;
            $response['complete_assig_count'] = $complete_assig_count1;
            $response['initiate_assig_count'] = $initiate_assig_count1;
            $response['close_assig_count'] = $close_assig_count1;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_graph_task_all_branch() { //function to get all branch task completion ratio
        $firm_id_hq = $this->input->post("firm_id_hq");
        $query_get_boss_id = $this->db->query("select boss_id from partner_header_all  where firm_id='$firm_id_hq'");
        $res = $query_get_boss_id->row();
        $boss_id = $res->boss_id;
        $query_get_Firms = $this->db->query("select firm_name,firm_id from partner_header_all where reporting_to='$boss_id'and boss_id != '$boss_id' ");
        $firms = array();
        $firm_id = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query_get_Firms->result();
            foreach ($result as $row) {
                $firms[] = $row->firm_name;
                $firm_id[] = $row->firm_id;
            }
            $count_all_task = array();
            $count_complete_all_task = array();
            for ($i = 0; $i < sizeof($firm_id); $i++) {
                $query_get_all_task_of_firm = $this->db->query("select count(task_assignment_id) as all_task_count from customer_task_allotment_all where firm_id='$firm_id[$i]' and sub_task_id=''");
                if ($this->db->affected_rows() > 0) {
                    $res1 = $query_get_all_task_of_firm->row();
                    $count_all_task[] = $res1->all_task_count;
                } else {
                    
                }
                $query_get_complete_task_of_firm = $this->db->query("select count(task_assignment_id) as complete_task_count from customer_task_allotment_all "
                        . "where firm_id='$firm_id[$i]' and sub_task_id='' and status='3'");
                if ($this->db->affected_rows() > 0) {
                    $res2 = $query_get_complete_task_of_firm->row();
                    $count_complete_all_task[] = $res2->complete_task_count;
                } else {
                    
                }
            }

            $count_all_task1 = array();
            $count_complete_all_task1 = array();
            $remain_count = array();
            $ratio = array();
            $remain_ratio = array();
            for ($o = 0; $o < sizeof($count_all_task); $o++) {
                $count_all_task1[] = $count_all_task[$o];
                $aa1 = settype($count_all_task1[$o], "int");

                $count_complete_all_task1[] = $count_complete_all_task[$o];
                $aa1 = settype($count_complete_all_task1[$o], "int");
                $remain_count[] = ($count_all_task[$o] - $count_complete_all_task[$o]);
                $aa1 = settype($remain_count[$o], "int");
                if ($count_all_task[$o] != 0) {
                    $ratio[] = ($count_complete_all_task[$o] / $count_all_task[$o]) * 100;
                    $aa1 = settype($ratio[$o], "int");

                    $remain_ratio[] = ($remain_count[$o] / $count_all_task[$o]) * 100;
                    $aa1 = settype($remain_ratio[$o], "int");
                } else {
                    $ratio[] = 0;
                    $remain_ratio[] = 0;
                }
            }
            $response['firm_names'] = $firms;
            $response['remain_count'] = $remain_count;
            $response['count_all_task'] = $count_all_task1;
            $response['count_complete_all_task'] = $count_complete_all_task1;
            $response['ratio'] = $ratio;
            $response['ratio_remain'] = $remain_ratio;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_graph_office_wise_duedate() { //get all branches due date task repor 
        $firm_id_hq = $this->input->post("firm_id_hq");
        $year = $this->input->post("year");
        $query_get_boss_id = $this->db->query("select boss_id from partner_header_all  where firm_id='$firm_id_hq'");
        if ($this->db->affected_rows() > 0) {
            $res = $query_get_boss_id->row();
            $boss_id = $res->boss_id;
        } else {
            
        }
        $query_get_Firms = $this->db->query("select firm_name,firm_id from partner_header_all where reporting_to='$boss_id' and boss_id != '$boss_id' ");
        $firms = array();
        $firm_id = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query_get_Firms->result();
            foreach ($result as $row) {
                $firms[] = $row->firm_name;
                $firm_id[] = $row->firm_id;
            }
            $count_all_due_date_task = array();
            $count_complete_due_date_task = array();
            $count_remaining_due_date_task = array();
            for ($i = 0; $i < sizeof($firm_id); $i++) {

                $query_all = $this->db->query("SELECT count(firm_id) as all_count from customer_due_date_task_transaction_all where firm_id='$firm_id[$i]' and last_date_of_submission LIKE '$year%'");
                if ($this->db->affected_rows() > 0) {
                    $res1 = $query_all->row();
                    $count_all_due_date_task[] = $res1->all_count;
                } else {
                    $count_all_due_date_task[] = 0;
                }

                $query_complete = $this->db->query("SELECT count(firm_id) as complete_count from customer_due_date_task_transaction_all where firm_id='$firm_id[$i]' and last_date_of_submission LIKE '$year%' and status='3'");
                $query_remaining = $this->db->query("SELECT count(firm_id) as remaining_count from customer_due_date_task_transaction_all where firm_id='$firm_id[$i]' and last_date_of_submission LIKE '$year%' and status='2' OR status='1'");
                if ($this->db->affected_rows() > 0) {
                    $res1 = $query_complete->row();
                    $count_complete_due_date_task[] = $res1->complete_count;
                    $res2 = $query_remaining->row();
                    $count_remaining_due_date_task[] = $res2->remaining_count;
                } else {
                    $count_complete_due_date_task[] = 0;
                    $count_remaining_due_date_task[] = 0;
                }
                $count_all_due_date_task1 = array();
                $count_complete_due_date_task1 = array();
                $count_remaining_due_date_task1 = array();
                $ratio_complete = array();
                for ($o = 0; $o < sizeof($count_all_due_date_task); $o++) {
                    $count_all_due_date_task1[] = $count_all_due_date_task[$o];
                    $aa1 = settype($count_all_due_date_task1[$o], "int");

                    $count_complete_due_date_task1[] = $count_complete_due_date_task[$o];
                    $count_remaining_due_date_task1[] = $count_remaining_due_date_task[$o];
                    $aa1 = settype($count_complete_due_date_task1[$o], "int");
                   
                    if ($count_all_due_date_task[$o] != 0) {
                        $ratio_complete[] = ($count_complete_due_date_task[$o] / $count_all_due_date_task[$o]) * 100;
                        $ratio_remaining[] = ($count_remaining_due_date_task[$o] / $count_all_due_date_task[$o]) * 100;
                        $aa1 = settype($ratio_complete[$o], "int");
                    } else {
                        $ratio_complete[] = 0;
                        $ratio_remaining[] = 0;
                    }
                }
            }
            $response['ratio_complete'] = $ratio_complete;
            $response['ratio_remaining'] = $ratio_remaining;
            $response['firm_name'] = $firms;
            $response['count_complete_due_date_task'] = $count_complete_due_date_task1;
            $response['count_remaining_due_date_task'] = $count_complete_due_date_task1;
            $response['count_all_due_date_task'] = $count_all_due_date_task1;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }
    
    //Due date graph of yearly for client admin
    
    public function get_graph_office_wise_duedate_ca() { //get all due date task repot 
        $firm_id_ca = $this->input->post("firm_id_ca");
//        $year = $this->input->post("year");
        $query_get_boss_id = $this->db->query("select boss_id from partner_header_all  where firm_id='$firm_id_ca'");
        if ($this->db->affected_rows() > 0) {
            $res = $query_get_boss_id->row();
            $boss_id = $res->boss_id;
        } else {
            
        }
        $query_get_Firms = $this->db->query("select firm_name,firm_id from partner_header_all where reporting_to='$boss_id' and boss_id != '$boss_id' ");
        $firms = array();
        $firm_id = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query_get_Firms->result();
            foreach ($result as $row) {
                $firms[] = $row->firm_name;
                $firm_id[] = $row->firm_id;
            }
            $count_all_due_date_task = array();
            $count_complete_due_date_task = array();
            for ($i = 0; $i < sizeof($firm_id); $i++) {

                $query_all = $this->db->query("SELECT count(firm_id) as all_count from customer_due_date_task_transaction_all where firm_id='$firm_id[$i]' and last_date_of_submission LIKE '$year%'");
                if ($this->db->affected_rows() > 0) {
                    $res1 = $query_all->row();
                    $count_all_due_date_task[] = $res1->all_count;
                } else {
                    $count_all_due_date_task[] = 0;
                }

                $query_complete = $this->db->query("SELECT count(firm_id) as complete_count from customer_due_date_task_transaction_all where firm_id='$firm_id[$i]' and last_date_of_submission LIKE '$year%' and status='3'");
                if ($this->db->affected_rows() > 0) {
                    $res1 = $query_complete->row();
                    $count_complete_due_date_task[] = $res1->complete_count;
                } else {
                    $count_complete_due_date_task[] = 0;
                }
                $count_all_due_date_task1 = array();
                $count_complete_due_date_task1 = array();
                $ratio_complete = array();
                for ($o = 0; $o < sizeof($count_all_due_date_task); $o++) {
                    $count_all_due_date_task1[] = $count_all_due_date_task[$o];
                    $aa1 = settype($count_all_due_date_task1[$o], "int");

                    $count_complete_due_date_task1[] = $count_complete_due_date_task[$o];
                    $aa1 = settype($count_complete_due_date_task1[$o], "int");
                    if ($count_all_due_date_task[$o] != 0) {
                        $ratio_complete[] = ($count_complete_due_date_task[$o] / $count_all_due_date_task[$o]) * 100;
                        $aa1 = settype($ratio_complete[$o], "int");
                    } else {
                        $ratio_complete[] = 0;
                    }
                }
            }
            $response['ratio_complete'] = $ratio_complete;
            $response['firm_name'] = $firms;
            $response['count_complete_due_date_task'] = $count_complete_due_date_task1;
            $response['count_all_due_date_task'] = $count_all_due_date_task1;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }
    

    public function get_graph_branch_wise_duedate_mothly() {
        $firm_id = $this->input->post("firm_id");
        $year = $this->input->post("year");
        $query = $this->db->query("select last_date_of_submission from customer_due_date_task_transaction_all where firm_id='$firm_id'");
        $date = array();
        $monthName = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $date[] = $row->last_date_of_submission;
            }
            $r6 = "";
            $r5 = "";
            for ($i = 0; $i < count($date); $i++) {
                $r1 = $date[$i];
                $r2 = explode(' ', $r1);
                $r3 = $r2[0];
                $r4 = explode('-', $r3);
                $month = $r4[1];
                $r5 .= $year . "-" . $month;
                $r6 .= $year . "-" . $month . ",";

                $monthNum = $month;
                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                $monthName[] = $dateObj->format('F');
            }
            $exp_mon = explode(',', $r6);
            $month1 = array_values(array_unique($exp_mon));
            $month_Name = array_values(array_unique($monthName));

            $total_count = array();
            $total_complete_count = array();
            for ($j = 0; $j < sizeof($month1)-1; $j++) {
                $qur = $this->db->query("SELECT count(firm_id) as count_firm from customer_due_date_task_transaction_all where last_date_of_submission LIKE '$month1[$j]%'");
                if ($this->db->affected_rows() > 0) {
                    $result1 = $qur->row();
                    $total_count[] = $result1->count_firm;
                } else {
                    
                }

                $qur1 = $this->db->query("SELECT count(firm_id) as count_complete_firm from customer_due_date_task_transaction_all where last_date_of_submission LIKE '$month1[$j]%' AND status='3'");
                if ($this->db->affected_rows() > 0) {
                    $result2 = $qur1->row();
                    $total_complete_count[] = $result2->count_complete_firm;
                } else {
                    
                }
            }
            $total_count1 = array();
            $total_complete_count1 = array();
            for ($o = 0; $o < sizeof($total_count); $o++) {
                $total_count1[] = $total_count[$o];
                $aa1 = settype($total_count1[$o], "int");

                $total_complete_count1[] = $total_complete_count[$o];
                $aa1 = settype($total_complete_count1[$o], "int");

                if ($total_count[$o] != 0) {
                    $ratio_complete[] = ($total_complete_count1[$o] / $total_count[$o]) * 100;
                    $aa1 = settype($ratio_complete[$o], "int");
                } else {
                    $ratio_complete[] = 0;
                }
            }
            $response['ratio_complete'] = $ratio_complete;
            $response['total_count'] = $total_count1;
            $response['total_complete_count'] = $total_complete_count1;
            $response['month_data'] = $month_Name;
            $response['message'] = "success";
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

}
