<?php

class Graphs_ca extends CI_Controller {

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
        
        $result_filled_by1 = $this->db->query("select firm_name from partner_header_all where firm_id='$firm_id'");
        if ($result_filled_by1->num_rows() > 0) {

            foreach ($result_filled_by1->result() as $row) {
                $firm_name= $row->firm_name;
            }
        }
        else{
            
        }
        $data['firm_name']=$firm_name;
        $this->load->view('client_admin/enquiry_report', $data);
    }

//function for the load all firms in the dropdown
    public function get_firms() {
        $email_id = $this->session->userdata('login_session');
//        echo $email_id;
        
        $result_filled_by1 = $this->db->query("select boss_id from partner_header_all where firm_email_id='$email_id'");
        if ($result_filled_by1->num_rows() > 0) {

            foreach ($result_filled_by1->result() as $row) {
                $boss_id= $row->boss_id;
            }
        }
        else{
            
        }
        
        $result_filled_by = $this->db->query("select firm_id,firm_name from partner_header_all where reporting_to='$boss_id'");
        if ($result_filled_by->num_rows() > 0) {

            foreach ($result_filled_by->result() as $row) {
                $firm_name['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }

//            print_r($response[ 'firm_data']);
            $response['firm_names'] = $firm_name['firm_data'];
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

    //This function for the load graphs for firm wise 
    public function load_graph() {

        $firm_id = $this->input->post('firm_id');
        $query = $this->db->query("select created_on from enquiry_header_all where firm_id='$firm_id'");
        $date = array();

        $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        if ($this->db->affected_rows() > 0) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;

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
                $year = $r4[0];
                $month = $r4[1];
                $r5 .= $year . "-" . $month;
                $r6 .= $year . "-" . $month . ",";
            }


            $exp_mon = explode(',', $r6);
            $avrage_of_completed = array();
            $avrage_of_closed = array();
            $avrage_of_convreted = array();
            $sum_of_complted_converted = array();
            $month1 = array_values(array_unique($exp_mon));
            //$my_months = substr($month1[0], 5, 2);
            for ($n = 0; $n < count($month1) - 1; $n++) {
//                   echo substr($month1[$n], 5, 2); 
                $month_name_from_number[$n] = $months[(int) substr($month1[$n], 5, 2)];
            }
            // print_r($month_name_from_number);
            for ($z = 0; $z < count($month1) - 1; $z++) {
                $query_for_get_data_for_charts_all = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%'");
                if ($query_for_get_data_for_charts_all->num_rows() > 0) {
                    // $enquiry_id = array();
                    foreach ($query_for_get_data_for_charts_all->result() as $row) {
                        // $enquiry_id[] = $row->enquiry_id;
                        $enquiry_id_count_all[] = $row->no_of_allot;
                    }
                    $abc0 = array();
                    for ($xy = 0; $xy < sizeof($enquiry_id_count_all); $xy++) { //loop to convert string data into integer
                        $abc0[] = $enquiry_id_count_all[$xy];
                        $aa1 = settype($abc0[$xy], "int");
                    }
                    $query_for_get_data_for_charts_init = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='2'");
                    if ($query_for_get_data_for_charts_init->num_rows() > 0) {
                        // $enquiry_id = array();
                        foreach ($query_for_get_data_for_charts_init->result() as $row) {
                            // $enquiry_id[] = $row->enquiry_id;
                            $enquiry_id_count_init[] = $row->no_of_allot;
                        }
                        $abci = array();
                        for ($xyz = 0; $xyz < sizeof($enquiry_id_count_init); $xyz++) { //loop to convert string data into integer
                            $abci[] = $enquiry_id_count_init[$xyz];
                            $aa1 = settype($abci[$xyz], "int");
                        }
                    } else {
                        
                    }
                    //print_r($abci);

                    $query_for_get_data_for_charts_not_init = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='1'");
                    if ($query_for_get_data_for_charts_not_init->num_rows() > 0) {
                        // $enquiry_id = array();
                        foreach ($query_for_get_data_for_charts_not_init->result() as $row) {
                            // $enquiry_id[] = $row->enquiry_id;
                            $enquiry_id_count_not_init[] = $row->no_of_allot;
                        }
                        $abcin = array();
                        for ($wxyz = 0; $wxyz < sizeof($enquiry_id_count_not_init); $wxyz++) { //loop to convert string data into integer
                            $abcin[] = $enquiry_id_count_not_init[$wxyz];
                            $aa1 = settype($abcin[$wxyz], "int");
                        }
                    } else {
                        
                    }
                    // print_r($abcin);

                    $query_for_get_data_for_charts = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and allot_to<>''");
                    if ($query_for_get_data_for_charts->num_rows() > 0) {
                        $enquiry_id = array();
                        foreach ($query_for_get_data_for_charts->result() as $row) {
                            // $enquiry_id[] = $row->enquiry_id;
                            $enquiry_id_count[] = $row->no_of_allot;
                        }
                        $abc = array();
                        for ($o = 0; $o < sizeof($enquiry_id_count); $o++) { //loop to convert string data into integer
                            $abc[] = $enquiry_id_count[$o];
                            $aa1 = settype($abc[$o], "int");
                        }
                    } else {
                        
                    }

                    $query_for_get_data_for_charts1 = $this->db->query("select DISTINCT count(enquiry_id) as no_of_allot1 from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='4'");
                    if ($query_for_get_data_for_charts1->num_rows() > 0) {
                        //$enquiry_id = array();
                        foreach ($query_for_get_data_for_charts1->result() as $row) {
                            // $enquiry_id[] = $row->enquiry_id;
                            $enquiry_id_count_for_completed[] = $row->no_of_allot1;
                        }
                        $abc1 = array();
                        for ($m = 0; $m < sizeof($enquiry_id_count_for_completed); $m++) { //loop to convert string data into integer
                            $abc1[] = $enquiry_id_count_for_completed[$m];
                            $aa1 = settype($abc1[$m], "int");
                        }
                    } else {
                        
                    }


                    $query_for_get_data_for_charts1_for_failed = $this->db->query("select DISTINCT count(enquiry_id) as no_of_allot1 from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='5'");
                    if ($query_for_get_data_for_charts1_for_failed->num_rows() > 0) {
                        // $enquiry_id = array();
                        foreach ($query_for_get_data_for_charts1_for_failed->result() as $row) {
                            // $enquiry_id[] = $row->enquiry_id;
                            $enquiry_id_count_for_failed[] = $row->no_of_allot1;
                        }
                        $abc2 = array();
                        for ($n = 0; $n < sizeof($enquiry_id_count_for_failed); $n++) { //loop to convert string data into integer
                            $abc2[] = $enquiry_id_count_for_failed[$n];
                            $aa2 = settype($abc2[$n], "int");
                        }
                    } else {
                        
                    }



                    $query_for_get_data_for_charts1_for_converted = $this->db->query("select DISTINCT count(enquiry_id) as no_of_allot1 from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='6'");
                    if ($query_for_get_data_for_charts1_for_converted->num_rows() > 0) {
                        $enquiry_id = array();
                        foreach ($query_for_get_data_for_charts1_for_converted->result() as $row) {
                            // $enquiry_id[] = $row->enquiry_id;
                            $enquiry_id_count_for_converted[] = $row->no_of_allot1;
                        }
                        $abc3 = array();
                        for ($p = 0; $p < sizeof($enquiry_id_count_for_converted); $p++) { //loop to convert string data into integer
                            $abc3[] = $enquiry_id_count_for_converted[$p];
                            $aa2 = settype($abc3[$p], "int");
                        }
                    } else {
                        
                    }

                    $sum_of_complted_converted[$z] = $abc1[$z] + $abc3[$z];
                    $avrage_of_convreted[$z] = round(($sum_of_complted_converted[$z] / $abc0[$z]) * 100);
//                $sum_of_complted_converted[$z];
                    //echo $abc1[$z]+$abc3[$z];
                    $avrage_of_completed[$z] = round(($abc1[$z] / $abc0[$z]) * 100);
                    $avrage_of_closed[$z] = round(($abc2[$z] / $abc0[$z]) * 100);
                } else {
                    
                }
            }



//        print_r($abc1);
//        print_r($abc3);
////        print_r($sum_of_complted_converted);
//        print_r($abc0);
//        print_r($avrage_of_convreted);
            // $data = array(12, 15, 8);
//        var_dump($abc1);
//        $data3 = array(16, 9, 23);
            // $response['enquiry_id'] = $enquiry_id;
            //print_r($abc0); 
            $response['enquiry_count'] = $enquiry_id_count;
            $response['datat'] = $abc0;
            $response['data'] = $abc;
            $response['months'] = $month_name_from_number;
            $response['data1'] = $abc1;
            $response['data2'] = $abc2;
            $response['data3'] = $abc3;
            $response['datai'] = $abci;
            $response['datain'] = $abcin;
            $response['av_converted'] = $avrage_of_convreted;
            $response['av_completed'] = $avrage_of_completed;
            $response['av_closed'] = $avrage_of_closed;


            //print_r($enquiry_id);
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //this function is for the year wise graphs

    public function yearly_graphs() {
        $year_for_graphs = $this->input->post('year');
    }

//function for the load service name in dropdown
    public function get_service_name() {
        $firm_id_for_services = $this->input->post('firm_id_for_services');

        $result_filled_by = $this->db->query("select service_id,service_name from services_header_all where firm_id='$firm_id_for_services' and service_type_id=''");
        if ($result_filled_by->num_rows() > 0) {

            foreach ($result_filled_by->result() as $row) {
                $service_name['service_data'][] = ['service_id' => $row->service_id, 'service_name' => $row->service_name];
            }
            $response['service_names'] = $service_name['service_data'];
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

    //this function is for the load graph of epmloyee 
    public function load_graph_of_emp() {
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $employee_id = $this->input->post('employee_id');
        $year_and_month = array();
        $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
//        print_r($month);

        for ($x = 0; $x < count($month); $x++) {
            $year_and_month[$x] = $year . '-' . $month[$x];
        }

        for ($z = 0; $z < count($year_and_month); $z++) {
            $result_filled_by = $this->db->query("select DISTINCT service_type_id from enquiry_header_all where allot_to='$employee_id' and firm_id='$firm_id' and created_on like '$year_and_month[$z]%'");
            if ($result_filled_by->num_rows() > 0) {

                foreach ($result_filled_by->result() as $row) {
                    $service_type_id[$z] = $row->service_type_id;
                }
            } else {
                $service_type_id[$z] = 0;
            }
        }

        for ($b = 0; $b < count($year_and_month); $b++) {

            $result_filled_by1 = $this->db->query("select DISTINCT count(enquiry_id) as total_enquries  from enquiry_header_all where allot_to='$employee_id' and firm_id='$firm_id' and created_on like '$year_and_month[$b]%' and service_type_id='$service_type_id[$b]'");
            if ($result_filled_by1->num_rows() > 0) {

                foreach ($result_filled_by1->result() as $row) {
                    $total_enquries[$b] = $row->total_enquries;
                }
            } else {
                $total_enquries[$b] = 0;
            }
            $abc2 = array();
            for ($q = 0; $q < sizeof($total_enquries); $q++) { //loop to convert string data into integer
                $abc2[] = $total_enquries[$q];
                $aa2 = settype($abc2[$q], "int");
            }
        }


        for ($c = 0; $c < count($year_and_month); $c++) {
            $result_filled_by2 = $this->db->query("select DISTINCT count(enquiry_id) as total_enquries  from enquiry_header_all where allot_to='$employee_id' and firm_id='$firm_id' and created_on like '$year_and_month[$c]%' and status='4' and service_type_id='$service_type_id[$c]'");
            if ($result_filled_by2->num_rows() > 0) {

                foreach ($result_filled_by2->result() as $row) {
                    $total_completed_enquries[$c] = $row->total_enquries;
                }
            } else {
                $total_completed_enquries[$c] = 0;
            }
            $abc3 = array();
            for ($p = 0; $p < sizeof($total_completed_enquries); $p++) { //loop to convert string data into integer
                $abc3[] = $total_completed_enquries[$p];
                $aa2 = settype($abc3[$p], "int");
            }
            if ($abc2[$c] == 0) {
                $avrage_of_completed[$c] = 0;
            } else {
                $avrage_of_completed[$c] = round(($abc3[$c] / $abc2[$c]) * 100);
            }
        }


        for ($w = 0; $w < count($service_type_id); $w++) {
            $query_for_service_name = $this->db->query("select service_name from services_header_all where service_id='$service_type_id[$w]' and service_type_id='' and firm_id='$firm_id'");
            if ($query_for_service_name->num_rows() > 0) {

                foreach ($query_for_service_name->result() as $row) {
                    $service_name[$w] = $row->service_name;
                }
            } else {
                $service_name[$w] = "No Service";
            }
        }
        for ($n = 0; $n < count($year_and_month); $n++) {
//                   echo substr($month1[$n], 5, 2); 
            $month_name_from_number[$n] = $months[(int) substr($year_and_month[$n], 5, 2)];
        }
        $new_array_of_services_and_months = array();
        for ($k = 0; $k < count($month_name_from_number); $k++) {
            $new_array_of_services_and_months[$k] = $month_name_from_number[$k] . '-' . $service_name[$k];
        }
        if (count($abc2) == count($abc3) || count($service_name) == count($month_name_from_number)) {
            $response['enquiry_count'] = $abc2;
            $response['complete_enquiry_count'] = $abc3;
            $response['services'] = $service_name;
            $response['months'] = $month_name_from_number;
            $response['month_and_services'] = $new_array_of_services_and_months;
            $response['win_rate'] = $avrage_of_completed;
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

    //this function is for the load employees dropdown 
    public function load_employees() {
         $firm_id = $this->input->post('firm_id');
        $user_id = array();
        $query1 = $this->db->query("select DISTINCT allot_to from enquiry_header_all where firm_id='$firm_id' AND allot_to != ''");
        $result1 = $query1->result();
        foreach ($result1 as $row) {
            $user_id[] = $row->allot_to;
        }
        for ($z = 0; $z < count($user_id); $z++) {

            $result_filled_by = $this->db->query("select user_id,user_name from user_header_all where user_id='$user_id[$z]' and user_type='4'");
            if ($result_filled_by->num_rows() > 0) {

                foreach ($result_filled_by->result() as $row) {
                    $user_name['user_name_data'][] = ['user_id' => $row->user_id, 'user_name' => $row->user_name];
                }
                $response['user_data'] = $user_name['user_name_data'];
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

    //this function is for the load graph by month
    public function load_graph_by_month() {
        $year = $this->input->post('year_of_graph');
        $month = $this->input->post('month');
        $result_count = 0;
        $service_name = $this->input->post('service_name');
        $full_month_and_year = array();
        for ($x = 0; $x < count($month); $x++) {
            $full_month_and_year[] = $year . "-" . $month[$x];
        }
        $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        //  print_r($months);
        //  print_r($full_month_and_year);
        $firm_id = $this->input->post('firm_id');
        $query1 = $this->db->query("select service_name from services_header_all where firm_id='$firm_id' and service_type_id='' and service_id='$service_name'");
        $result1 = $query1->result();
        foreach ($result1 as $row) {
            $service_name_for_graph = $row->service_name;
        }
        for ($z = 0; $z < sizeof($full_month_and_year); $z++) {
            $result_filled_by2 = $this->db->query("select count(enquiry_id) as total_enquiry  from enquiry_header_all where service_type_id='$service_name' and firm_id='$firm_id' and created_on like '$full_month_and_year[$z]%'");
            if ($result_filled_by2->num_rows() > 0) {
                foreach ($result_filled_by2->result() as $row) {
                    $toatal_enquiry_count[] = $row->total_enquiry;
                }
                $abc2 = array();
                for ($n = 0; $n < sizeof($toatal_enquiry_count); $n++) { //loop to convert string data into integer
                    $abc2[] = $toatal_enquiry_count[$n];
                    $aa2 = settype($abc2[$n], "int");
                }
                $result_count++;
            } else {
                $result_count--;
            }

            $result_filled_by3 = $this->db->query("select count(enquiry_id) as total_enquiry  from enquiry_header_all where service_type_id='$service_name' and firm_id='$firm_id' and created_on like '$full_month_and_year[$z]%' and status='6'");
            if ($result_filled_by3->num_rows() > 0) {
                foreach ($result_filled_by3->result() as $row) {
                    $toatal_converted_enquiry_count[] = $row->total_enquiry;
                }
                $abc3 = array();
                for ($nm = 0; $nm < sizeof($toatal_converted_enquiry_count); $nm++) { //loop to convert string data into integer
                    $abc3[] = $toatal_converted_enquiry_count[$nm];
                    $aa2 = settype($abc3[$nm], "int");
                }
                $result_count++;
            } else {
                $result_count--;
            }
            if ($abc3[$z] == 0) {
                $avrage_of_completed[$z] = 0;
            } else {
                $avrage_of_completed[$z] = round(($abc3[$z] / $abc2[$z]) * 100);
            }
        }
        $month_name_from_number = array();

        for ($n = 0; $n < count($month); $n++) {

            $month_name_from_number[$n] = $months[(int) $month[$n]];
        }

        $response['enquiry_count'] = $abc2;
        $response['win_rate'] = $avrage_of_completed;
        $response['converted_enquiry_count'] = $abc3;
        $response['service_name_for_graph'] = $service_name_for_graph;
        $response['months'] = $month_name_from_number;
        $length_of_months_and_year = count($full_month_and_year) * 2;
        if ($result_count == $length_of_months_and_year) {
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

    //this function loads the data of services by group of month and only selected service can show
    public function load_grap_of_selected_service() {
        $year = $this->input->post('year');
        $avrage_of_completed = array();
        $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        $service_id = $this->input->post('service_id');
        $firm_id = $this->input->post('firm_id');
        $query1 = $this->db->query("select service_name from services_header_all where firm_id='$firm_id' and service_type_id='' and service_id='$service_id'");
        $result1 = $query1->result();
        foreach ($result1 as $row) {
            $service_name_for_graph = $row->service_name;
        }
        // echo $service_name_for_graph;
        $query = $this->db->query("select created_on from enquiry_header_all where firm_id='$firm_id' and service_type_id='$service_id'");
        $date = array();
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
//                $year = $r4[0];
                $month = $r4[1];
                $r5 .= $year . "-" . $month;
                $r6 .= $year . "-" . $month . ",";
            }
            $exp_mon = explode(',', $r6);
            $avrage_of_completed = array();
            $avrage_of_closed = array();
            $avrage_of_convreted = array();
            $sum_of_complted_converted = array();
            $month1 = array_values(array_unique($exp_mon));
            for ($n = 0; $n < count($month1) - 1; $n++) {
//                   echo substr($month1[$n], 5, 2); 
                $month_name_from_number[$n] = $months[(int) substr($month1[$n], 5, 2)];
            }
            unset($month1[sizeof($month1) - 1]);
//               echo sizeof($month1);
//               print_r($month1);
            $toatal_enquiry_count = array();

            for ($z = 0; $z < sizeof($month1); $z++) {
                $result_filled_by2 = $this->db->query("select count(enquiry_id) as total_enquiry  from enquiry_header_all where service_type_id='$service_id' and firm_id='$firm_id' and created_on like '$month1[$z]%'");
                if ($result_filled_by2->num_rows() > 0) {
                    foreach ($result_filled_by2->result() as $row) {
                        $toatal_enquiry_count[] = $row->total_enquiry;
                    }
                    $abc2 = array();
                    for ($n = 0; $n < sizeof($toatal_enquiry_count); $n++) { //loop to convert string data into integer
                        $abc2[] = $toatal_enquiry_count[$n];
                        $aa2 = settype($abc2[$n], "int");
                    }
                }

                $result_filled_by3 = $this->db->query("select count(enquiry_id) as total_enquiry  from enquiry_header_all where service_type_id='$service_id' and firm_id='$firm_id' and created_on like '$month1[$z]%' and status='6'");
                if ($result_filled_by3->num_rows() > 0) {
                    foreach ($result_filled_by3->result() as $row) {
                        $toatal_converted_enquiry_count[] = $row->total_enquiry;
                    }
                    $abc3 = array();
                    for ($nm = 0; $nm < sizeof($toatal_converted_enquiry_count); $nm++) { //loop to convert string data into integer
                        $abc3[] = $toatal_converted_enquiry_count[$nm];
                        $aa2 = settype($abc3[$nm], "int");
                    }
                }
                $avrage_of_completed[$z] = round(($abc3[$z] / $abc2[$z]) * 100);
            }
            $response['enquiry_count'] = $abc2;
            $response['converted_enquiry_count'] = $abc3;
            $response['service_name_for_graph'] = $service_name_for_graph;
            $response['months'] = $month_name_from_number;
            $response['win_rate'] = $avrage_of_completed;
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

    //this function is for the load graph by year
    public function load_graph_by_year() {
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $avrage_of_completed = array();
        $result_filled_by = $this->db->query("select DISTINCT service_type_id from enquiry_header_all where firm_id='$firm_id'");
        if ($result_filled_by->num_rows() > 0) {

            foreach ($result_filled_by->result() as $row) {
                $service_type_id[] = $row->service_type_id;
            }
            $service_name_from_id = array();
            $toatal_enquiry_count = array();
            // print_r($service_type_id);
            for ($z = 0; $z < sizeof($service_type_id); $z++) {
                $result_filled_by1 = $this->db->query("select service_name from services_header_all where service_id='$service_type_id[$z]' and service_type_id='' and firm_id='$firm_id'");
                if ($result_filled_by1->num_rows() > 0) {
                    foreach ($result_filled_by1->result() as $row) {
                        $service_name_from_id[] = $row->service_name;
                    }
                    $abc1 = array();
                    for ($m = 0; $m < sizeof($service_name_from_id); $m++) { //loop to convert string data into integer
                        $abc1[] = $service_name_from_id[$m];
                        $aa2 = settype($abc1[$m], 'string');
                    }
                }


                $result_filled_by2 = $this->db->query("select count(enquiry_id) as total_enquiry  from enquiry_header_all where service_type_id='$service_type_id[$z]' and firm_id='$firm_id' and created_on like'$year%'");
                if ($result_filled_by2->num_rows() > 0) {
                    foreach ($result_filled_by2->result() as $row) {
                        $toatal_enquiry_count[] = $row->total_enquiry;
                    }
                    $abc2 = array();
                    for ($n = 0; $n < sizeof($toatal_enquiry_count); $n++) { //loop to convert string data into integer
                        $abc2[] = $toatal_enquiry_count[$n];
                        $aa2 = settype($abc2[$n], "int");
                    }
                }

                $result_filled_by3 = $this->db->query("select count(enquiry_id) as total_enquiry  from enquiry_header_all where service_type_id='$service_type_id[$z]' and firm_id='$firm_id' and status='6' and created_on like'$year%'");
                if ($result_filled_by3->num_rows() > 0) {
                    foreach ($result_filled_by3->result() as $row) {
                        $toatal_converted_enquiry_count[] = $row->total_enquiry;
                    }
                    $abc3 = array();
                    for ($nm = 0; $nm < sizeof($toatal_converted_enquiry_count); $nm++) { //loop to convert string data into integer
                        $abc3[] = $toatal_converted_enquiry_count[$nm];
                        $aa2 = settype($abc3[$nm], "int");
                    }
                }
                if ($abc2[$z] == 0 && $abc3[$z] == 0) {
                    
                } else {
                    $avrage_of_completed[$z] = round(($abc3[$z] / $abc2[$z]) * 100);
                }
            }
            // print_r($toatal_enquiry_count);
            $response['count_of_enquiry'] = $abc2;
            $response['count_of_enquiry_converted'] = $abc3;
            $response['win_rate'] = $avrage_of_completed;
            $response['service_names'] = $abc1;
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

    //this function load he srvice wiae graph data of whole year all services
    public function def_chart_of_service_wise_enquiry() {
        $firm_id = $this->input->post('firm_id');
        $year = $this->input->post('year');
        $avrage_of_completed = array();
        $result_filled_by = $this->db->query("select DISTINCT service_type_id from enquiry_header_all where firm_id='$firm_id'");
        if ($result_filled_by->num_rows() > 0) {

            foreach ($result_filled_by->result() as $row) {
                $service_type_id[] = $row->service_type_id;
            }
            $service_name_from_id = array();
            $toatal_enquiry_count = array();
            // print_r($service_type_id);
            for ($z = 0; $z < sizeof($service_type_id); $z++) {
                $result_filled_by1 = $this->db->query("select service_name from services_header_all where service_id='$service_type_id[$z]' and service_type_id='' and firm_id='$firm_id'");
                if ($result_filled_by1->num_rows() > 0) {
                    foreach ($result_filled_by1->result() as $row) {
                        $service_name_from_id[] = $row->service_name;
                    }
                    $abc1 = array();
                    for ($m = 0; $m < sizeof($service_name_from_id); $m++) { //loop to convert string data into integer
                        $abc1[] = $service_name_from_id[$m];
                        $aa2 = settype($abc1[$m], 'string');
                    }
                }


                $result_filled_by2 = $this->db->query("select count(enquiry_id) as total_enquiry  from enquiry_header_all where service_type_id='$service_type_id[$z]' and firm_id='$firm_id' and created_on like'$year%'");
                if ($result_filled_by2->num_rows() > 0) {
                    foreach ($result_filled_by2->result() as $row) {
                        $toatal_enquiry_count[] = $row->total_enquiry;
                    }
                    $abc2 = array();
                    for ($n = 0; $n < sizeof($toatal_enquiry_count); $n++) { //loop to convert string data into integer
                        $abc2[] = $toatal_enquiry_count[$n];
                        $aa2 = settype($abc2[$n], "int");
                    }
                }

                $result_filled_by3 = $this->db->query("select count(enquiry_id) as total_enquiry  from enquiry_header_all where service_type_id='$service_type_id[$z]' and firm_id='$firm_id' and status='6' and created_on like'$year%'");
                if ($result_filled_by3->num_rows() > 0) {
                    foreach ($result_filled_by3->result() as $row) {
                        $toatal_converted_enquiry_count[] = $row->total_enquiry;
                    }
                    $abc3 = array();
                    for ($nm = 0; $nm < sizeof($toatal_converted_enquiry_count); $nm++) { //loop to convert string data into integer
                        $abc3[] = $toatal_converted_enquiry_count[$nm];
                        $aa2 = settype($abc3[$nm], "int");
                    }
                }

                $avrage_of_completed[$z] = round(($abc3[$z] / $abc2[$z]) * 100);
            }


            // print_r($toatal_enquiry_count);
            $response['count_of_enquiry'] = $abc2;
            $response['count_of_enquiry_converted'] = $abc3;
            $response['service_names'] = $abc1;
            $response['win_rate'] = $avrage_of_completed;
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

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>