<?php

class Employee_login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('emp_model');
        $this->load->model('firm_model');
        $this->load->model('employee_login_model');
        $this->load->model('checklist_model');
        $this->load->model('customer_model');
        $this->load->helper('url');
        $this->load->model('Nas_modal');
    }

    public function employee_dashboard() {
        if (isset($this->session->login_session)) {
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
                $firm_logo = $record->firm_logo;
                $firm_name = $record->user_name;
                $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$user_id'");
                if ($result3->num_rows() > 0) {
                    $record3 = $result3->row();
                    $value_permit = $record->leave_approve_permission;
                    $data['val'] = $value_permit;
                } else {
                    $data['val'] = '';
                }
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
                $data['val'] = "";
            }

            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
            }
            //for check due date permission
            $q11 = $this->db->query("SELECT `due_date_creation_permitted` from `partner_header_all` where `firm_id`='$firm_id'");
            if ($q11->num_rows() > 0) {
                $due_date = $q11->result();
                foreach ($due_date as $row3) {
                    $due_date_creation_permitted = $row3->due_date_creation_permitted;
                    $this->session->set_userdata("due_date_permission", $due_date_creation_permitted);
                }
            }

            $data['page_title'] = "Employee dashboard";
            $data['prev_title'] = "Employee dashboard";

            $this->load->view('human_resource/Calendar_dashboard', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function ca_dashboard_charts() {
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

        $cq = $this->db->query("SELECT firm_id,  user_id  from user_header_all where email='$email_id'");
        if ($cq->num_rows() > 0) {
            $record = $cq->row();
            $firm_id = $record->firm_id;
            $user_name = $record->user_id;
        }
        $query = $this->db->query("select created_on from enquiry_header_all where firm_id='$firm_id'");
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
            for ($z = 0; $z < count($month1) - 1; $z++) {
                $query_for_get_data_for_charts_all = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and allot_to='$user_name' and created_on like '$month1[$z]%' ");
                if ($query_for_get_data_for_charts_all->num_rows() > 0) {
                    foreach ($query_for_get_data_for_charts_all->result() as $row) {
                        $enquiry_id_count_all[] = $row->no_of_allot;
                    }
                    $abc0 = array();
                    for ($xy = 0; $xy < sizeof($enquiry_id_count_all); $xy++) { //loop to convert string data into integer
                        $abc0[] = $enquiry_id_count_all[$xy];
                        $aa1 = settype($abc0[$xy], "int");
                    }
                } else {

                }
                $query_for_get_data_for_charts_init = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and allot_to='$user_name' and created_on like '$month1[$z]%'  and status='2'");
                if ($query_for_get_data_for_charts_init->num_rows() > 0) {
                    foreach ($query_for_get_data_for_charts_init->result() as $row) {
                        $enquiry_id_count_init[] = $row->no_of_allot;
                    }
                    $abci = array();
                    for ($xyz = 0; $xyz < sizeof($enquiry_id_count_init); $xyz++) { //loop to convert string data into integer
                        $abci[] = $enquiry_id_count_init[$xyz];
                        $aa1 = settype($abci[$xyz], "int");
                    }
                } else {

                }
                $query_for_get_data_for_charts = $this->db->query("select DISTINCT  count(enquiry_id) as no_of_allot from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and allot_to='$user_name'");
                if ($query_for_get_data_for_charts->num_rows() > 0) {
                    $enquiry_id = array();
                    foreach ($query_for_get_data_for_charts->result() as $row) {
                        $enquiry_id_count[] = $row->no_of_allot;
                    }
                    $abc = array();
                    for ($o = 0; $o < sizeof($enquiry_id_count); $o++) { //loop to convert string data into integer
                        $abc[] = $enquiry_id_count[$o];
                        $aa1 = settype($abc[$o], "int");
                    }
                    $query_for_get_data_for_charts1_for_converted = $this->db->query("select DISTINCT count(enquiry_id) as no_of_allot1 from enquiry_header_all where firm_id='$firm_id' and created_on like '$month1[$z]%' and status='6' and allot_to='$user_name'");
                    if ($query_for_get_data_for_charts1_for_converted->num_rows() > 0) {
                        $enquiry_id = array();
                        foreach ($query_for_get_data_for_charts1_for_converted->result() as $row) {
                            $enquiry_id_count_for_converted[] = $row->no_of_allot1;
                        }
                        $abc3 = array();
                        for ($p = 0; $p < sizeof($enquiry_id_count_for_converted); $p++) { //loop to convert string data into integer
                            $abc3[] = $enquiry_id_count_for_converted[$p];
                            $aa2 = settype($abc3[$p], "int");
                        }
                    } else {

                    }
                    $difference = array();
                    for ($p = 0; $p < sizeof($enquiry_id_count_for_converted); $p++) { //loop to convert string data into integer
                        $difference[] = $enquiry_id_count[$p] - $enquiry_id_count_for_converted[$p];
                        $aa2 = settype($difference[$p], "int");
                    }
                    $winrate = array();
                    for ($q = 0; $q < sizeof($enquiry_id_count_init); $q++) {
                        if ($q != 0) {
                            $winrate[] = round(($enquiry_id_count_init[$q] / $enquiry_id_count_for_converted[$q]) * 100);
                            $aa3 = settype($winrate[$q], "int");
                        } else {

                        }
                    }
                    $response['enquiry_count'] = $enquiry_id_count;
                    $response['datat'] = $abc0;
                    $response['data'] = $abc;
                    $response['months'] = $month1;
                    $response['data3'] = $abc3;
                    $response['datai'] = $abci;
                    $response['difference'] = $difference;
                    $response['winrate'] = $winrate;
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['enquiry_count'] = "";
                    $response['datat'] = "";
                    $response['data'] = "";
                    $response['months'] = "";
                    $response['data1'] = "";
                    $response['data2'] = "";
                    $response['data3'] = "";
                    $response['datai'] = "";
                    $response['datain'] = "";
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
        } else {

        }
        //print_r($enquiry_id);
        echo json_encode($response);
    }

    // Employee Due-date task performance graph --bhava
    public function duedate_graph() {
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
        $cq = $this->db->query("SELECT firm_id,  user_id  from user_header_all where email='$email_id'");
        if ($cq->num_rows() > 0) {
            $record = $cq->row();
            $firm_id = $record->firm_id;
            $user_name = $record->user_id;
        }
        $query = $this->db->query("select filled_on from customer_due_date_task_transaction_all where firm_id='$firm_id'");
        $date = array();
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                $date[] = $row->filled_on;
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
            for ($z1 = 0; $z1 < count($month1) - 2; $z1++) {
                $query_for_get_duedate_for_charts_all = $this->db->query(
                        "SELECT count(due_date_id) as no_of_allot from customer_due_date_task_transaction_all where firm_id='$firm_id' and alloted_to='$user_name' and filled_on like '$month1[$z1]%'");
                if ($query_for_get_duedate_for_charts_all->num_rows() > 0) {
                    foreach ($query_for_get_duedate_for_charts_all->result() as $row) {
                        $duedate_id_count_all[] = $row->no_of_allot;
                    }
                    $a0 = array();
                    for ($a = 0; $a < sizeof($duedate_id_count_all); $a++) {
                        $a0[] = $duedate_id_count_all[$a];
                        $a1 = settype($a0[$a], "int");
                    }
                } else {

                }
                $query_getdata_for_charts_init = $this->db->query("SELECT count(due_date_id)as no_of_count from customer_due_date_task_transaction_all where firm_id='$firm_id' and alloted_to='$user_name' and filled_on like '$month1[$z1]%' and status='2'");

                if ($query_getdata_for_charts_init->num_rows() > 0) {
                    foreach ($query_getdata_for_charts_init->result() as $row) {
                        $duedate_id_count_init[] = $row->no_of_count;
                    }
                    $b = array();
                    for ($b1 = 0; $b1 < sizeof($duedate_id_count_init); $b1++) {
                        $b[] = $duedate_id_count_init[$b1];
                        $b0 = settype($b[$b1], "int");
                    }
                } else {

                }
                $query_get_datafor_charts = $this->db->query("SELECT count(due_date_id)as no_of_allot from customer_due_date_task_transaction_all where firm_id='$firm_id' and filled_on like'$month1[$z1]%' and alloted_to='$user_name'");
                if ($query_get_datafor_charts->num_rows() > 0) {
                    foreach ($query_get_datafor_charts->result() as $row) {
                        $duedate_id_count_init[] = $row->no_of_allot;
                    }
                    $ci = array();
                    for ($c = 0; $c < sizeof($duedate_id_count_init); $c++) {
                        $ci[] = $duedate_id_count_init[$c];
                        $c1 = settype($ci[$c], "int");
                    }
                } else {

                }
                $query_getdata_charts1_forcompleted = $this->db->query("SELECT count(due_date_id)as no_of_allot1 from customer_due_date_task_transaction_all  where firm_id='$firm_id' and filled_on like'$month[$z1]%' and status='3'");
                if ($query_getdata_charts1_forcompleted->num_rows() > 0) {
                    $due_date_id = array();
                    foreach ($query_getdata_charts1_forcompleted->result() as $row) {
                        $due_date_id_count_for_completed[] = $row->no_of_allot1;
                    }
                    $d = array();
                    for ($q = 0; $q < sizeof($due_date_id_count_for_completed); $q++) {
                        $d[] = $due_date_id_count_for_completed[$q];
                        $q2 = settype($d[$q], "int");
                    }
                    $remaining = array();
                    for ($r = 0; $r < sizeof($due_date_id_count_for_completed); $r++) {
                        $remaining[] = $duedate_id_count_init[$r] - $due_date_id_count_for_completed[$r];
                        $rr2 = settype($remaining[$r], "int");
                    }
                } else {

                }
            }
            $response['due_date_count'] = $due_date_id;
            $response['data_t'] = $a0;
            $response['data'] = $ci;
            $response['months'] = $month1;
            $response['data_3'] = $d;
            $response['datab'] = $b;
            $response['remaining'] = $remaining;

            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['due_date_count'] = "";
            $response['data_t'] = "";
            $response['data'] = "";
            $response['months'] = "";
            $response['data_3'] = "";
            $response['datab'] = "";
            $response['remaining'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function project_assignment_graph() {

    }

    public function get_batch_sinlge_from_multiple() {
        $current_batch_id1 = $this->input->post('single_batch_id');
        $query_get_template = $this->db->query("SELECT template_id FROM `survey_batch_information_all` WHERE `batch_id`='$current_batch_id1'");
        foreach ($query_get_template->result() as $row) {

            $template_data = $row->template_id;
        }
        $query_get_template = $this->db->query("SELECT question_id,option_group_id FROM `template_header_all` WHERE `template_id`='$template_data'");
        foreach ($query_get_template->result() as $row) {

            $question_data = $row->question_id;
            $option_data = $row->option_group_id;
        }

        $all_questions = explode(",", $question_data);

        $question_count = count($all_questions);
        for ($i = 0; $i < $question_count; $i++) {

            $query_get_questions = $this->db->query("SELECT question_id,question FROM `question` WHERE `question_id`='$all_questions[$i]'");

            foreach ($query_get_questions->result() as $row) {
                $questions = $row->question;
                $question_ids = $row->question;
                $my_all_question_data['question_data_all_for_employee'][] = ['question' => $row->question, 'question_id' => $row->question_id];
            }
        }
        $query_get_opetions = $this->db->query("SELECT option_id,option_name FROM `options` WHERE `option_group_id`='$option_data'");
        foreach ($query_get_opetions->result() as $row) {
            $option = $row->option_name;
            $option_ids = $row->option_id;
            $my_all_option_data['option_data_all_for_employee'][] = ['option_name' => $row->option_name, 'option_id' => $row->option_id];
        }

        $data = '
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="survey_form" name="survey_form" >
                <div class="modal-header">

                    <h4 class="modal-title">Fill Survey</h4>
                </div>
                <div class="modal-body">
                    <!-- BEGIN FORM-->

                    <input type="hidden" value="<?php echo sizeof($all_questions) ?>" id="question_count" name="question_count">
                    <input type="hidden" name="firm_id" id="firm_id" value="<?php echo $hdn_firm_id; ?>">
                    <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $hdn_user_id; ?>">
                    <input type="hidden" name="batch_id" id="batch_id" value="<?php echo $hdn_batch_id; ?>">
                    <input type="hidden" name="template_id" id="template_id" value="<?php echo $hdn_template_id; ?>">

                    <div class="portlet-form">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">';

        for ($i = 0; $i < sizeof($questions); $i++) {
            //$questions = $all_questions[$i]['question'];
            $data .= '<label> <?php echo $questions; ?></label><span class="required" aria-required="true"> * </span>
                                            <div class="input-group">';
            for ($j = 0; $j < sizeof($option); $j++) {
                //  $options = $all_options[$j]['option_name'];
                $data .= '<label><input type="radio" name="option_<?php echo $i; ?>" value="" id="" onclick="save_answer(id);"><?php echo $options; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>';
            }
        }

        $data .= ' </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="button" id="btn_submit_survey" name="btn_submit_survey" class="btn green btn green btn btn-primary mt-ladda-btn ladda-button mt-progress-demo"  data-style="expand-left"  style="float:right;  margin: 2px;">
                                        <span class="ladda-label">Submit Survey</span>
                                        <span class="ladda-spinner"></span><span class="ladda-spinner"></span><div class="ladda-progress" style="width: 115px;"></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END FORM-->
                </div>
            </form>
        </div>
        </div>';
        if ($questions != "") {
            $response['modal_of_survey'] = $data;
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

    public function reject_check() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }

        $query_get_survey_reject_time = $this->db->query("select survey_reject_time from user_header_all WHERE `email`='$email_id'");
        $res = $query_get_survey_reject_time->row();
        $get_survey_reject_time = $res->survey_reject_time;
        $count = $get_survey_reject_time + 1;
        $query_update_survey_reject_time = $this->db->query("update user_header_all set survey_reject_time='$count' WHERE `email`='$email_id'");
        if ($query_update_survey_reject_time) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        //echo json_encode($response);
    }

    public function Employee_task() {
        $data['prev_title'] = "Task List";
        $data['page_title'] = "Task List";
        $this->load->helper('url');

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
            $firm_id = $record->firm_id;
            $user_type = $record->user_type;
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
                $data['user_type'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
                $data['user_type'] = $user_type;
            }

            $for = "access_token";
            $access_token_for_update = "";
            $data['access_token'] = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update);
            $for = "proxy_url";
            $data['proxy_url'] = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update);
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

            $query = $this->db->query("SELECT `task_header_all`.`task_name`,`user_header_all`.`user_name`,`sub_task_header_all`.`checklist_created`,`customer_task_allotment_all`.`task_assignment_id`,`customer_task_allotment_all`.`task_assignment_description`,`customer_task_allotment_all`.`folder_id`, `customer_task_allotment_all`.`task_assignment_name`,`customer_task_allotment_all` .`status`,`customer_task_allotment_all`.`task_id`,`sub_task_header_all`.`sub_task_name`,`sub_task_header_all`.`sub_task_id` ,`customer_task_allotment_all`.`completion_date`,
                                        `customer_header_all`.`customer_name` FROM `customer_task_allotment_all`
                                        INNER JOIN `customer_header_all` ON
                                        `customer_header_all`.`customer_id` = `customer_task_allotment_all`.`customer_id`
                                        INNER JOIN `task_header_all` ON `task_header_all`.`task_id`=`customer_task_allotment_all`.`task_id` INNER JOIN `sub_task_header_all` on `sub_task_header_all`.`sub_task_id`=`customer_task_allotment_all`.`sub_task_id` INNER JOIN `user_header_all` ON `user_header_all`.`user_id`=`customer_task_allotment_all`.`alloted_to_emp_id` where `customer_task_allotment_all`.`alloted_to_emp_id`='$user_id'");


            if ($query->num_rows() > 0) {
                $record = $query->result();
                $data['result'] = $record;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $data['result'] = '';
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }

            $query_cust_subtask = $this->db->query("SELECT `custom_sub_task_header_all`.`customer_id`,`custom_sub_task_header_all`.`checklist_created`,`customer_task_allotment_all`.`task_id`,`customer_task_allotment_all`.`task_assignment_description`,`custom_sub_task_header_all`.`custom_sub_task_id`,`custom_sub_task_header_all`.`task_assignment_id`,`customer_header_all`.`customer_name`,`custom_sub_task_header_all`.`completion_date`,`custom_sub_task_header_all`.`status`,`customer_task_allotment_all`.`task_assignment_name`,`customer_task_allotment_all`.`folder_id`,`custom_sub_task_header_all`.`sub_task_name`
                                                        FROM `custom_sub_task_header_all`
                                                        INNER JOIN `customer_task_allotment_all`
                                                        ON `custom_sub_task_header_all`.`task_assignment_id`=`customer_task_allotment_all`.`task_assignment_id`
                                                        INNER JOIN `customer_header_all`
                                                        on `customer_header_all`.`customer_id`=`custom_sub_task_header_all`.`customer_id`
                                                        WHERE `custom_sub_task_header_all`.`alloted_to`='$user_id' AND `customer_task_allotment_all`.`sub_task_id`=''");
            if ($query_cust_subtask->num_rows() > 0) {
                $query_task_Assi_name = $this->db->query("select `task_assignment_id` from `custom_sub_task_header_all` where `alloted_to`='$user_id'");
                if ($query_task_Assi_name->num_rows() > 0) {
                    $record_taid = $query_task_Assi_name->row();
                    $task_assi_id = $record_taid->task_assignment_id;
                    $query_task_id = $this->db->query("select `task_id`,`sub_task_id` from `customer_task_allotment_all` where `task_assignment_id`='$task_assi_id'");
                    if ($query_task_id->num_rows() > 0) {
                        $record_tid = $query_task_id->row();
                        $task_id = $record_tid->task_id;
                        $sub_task_id = $record_tid->sub_task_id;
                    }
                }
                $qur = $this->db->query("SELECT `checklist_created` FROM `sub_task_header_all` WHERE `sub_task_id`='$sub_task_id'");
                $record_1 = $qur->row();
                $checklist_created = $record_1->checklist_created;

                $record_custsubtask = $query_cust_subtask->result();
                $data['result_custsubtask_data'] = $record_custsubtask;
                $data['result_t_name'] = $record_tid;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $data['result_custsubtask_data'] = '';
                $data['result_t_name'] = '';
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            $query_task_assignment = $this->db->query("SELECT `task_header_all`.`task_name`,`task_header_all`.`task_id`,`customer_task_allotment_all`.`status`,`customer_task_allotment_all`.`completion_date`,`customer_task_allotment_all`.`customer_id`,`customer_task_allotment_all`.`task_assignment_id`,`customer_task_allotment_all`.`task_assignment_name`,`customer_task_allotment_all`.`task_assignment_description`,`customer_header_all`.`customer_name`,`customer_task_allotment_all`.`sub_task_id`,`customer_task_allotment_all`.`alloted_to_emp_id`
                                                        FROM `customer_task_allotment_all`
                                                        INNER JOIN `task_header_all`
                                                        ON `task_header_all`.`task_id`=`customer_task_allotment_all`.`task_id`
                                                        INNER JOIN `customer_header_all`
                                                        ON `customer_header_all`.`customer_id`=`customer_task_allotment_all`.`customer_id`
                                                        WHERE `customer_task_allotment_all`.`alloted_to_emp_id`='$user_id' AND `customer_task_allotment_all`.`sub_task_id`=''");
            if ($query_task_assignment->num_rows() > 0) {
                $record1 = $query_task_assignment->result();
                $data['result1'] = $record1;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $data['result1'] = '';
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $data['result'] = '';
            $data['logo'] = "";
            $data['firm_name_nav'] = "";

            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        $this->load->view('employee/show_task', $data);
    }

    function view_taskfiles() {
        $task_id = base64_decode($this->input->post('task_id'));
        $query = $this->db->query("SELECT * from 'task_checklist_transaction_all' where `task_id`='$task_id' and `employee_id`=`employee_id`");
        if ($query->num_rows() > 0) {
            $record = $query->result();
            $response['data_checklist_task'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['data_checklist_task'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } echo json_encode($response);
    }

    function view_duedatechecklist() {
        $customer_id = ($this->input->post('customer_id'));
        $due_date_id = ($this->input->post('due_date_id'));
        $due_date_task_id = ($this->input->post('due_date_task_id'));
        $query_data = $this->db->query("SELECT * from `customer_due_date_task_transaction_all`  WHERE `due_date_id`='$due_date_id' and `due_date_task_id`='$due_date_task_id' and customer_id='$customer_id' ");
        $query = $this->db->query("SELECT * from `due_date_checklist_master_all`  WHERE `due_date_id`='$due_date_id'");
        if ($query->num_rows() > 0 || $query_data->num_rows() > 0) {
            $record1 = $query_data->result();
            foreach ($record1 as $row) {
                $attach_file_link = explode(',', $row->attach_file_link);
            }
            if (!isset($attach_file_link)) {
                $attach_file_link = "";
            }
            $record = $query->result();
            $response['data_checklist_duedate'] = $record;
            $response['uploaded_files'] = $attach_file_link;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['data_checklist_duedate'] = '';
            $response['uploaded_files'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } echo json_encode($response);
    }

    //pooja function
    function view_checklist() {
        $result_data = $this->customer_model->get_firm_id();
        if ($result_data !== false) {
            $firm_id = $result_data['firm_id'];
        }
        $query = $this->db->query("SELECT `firm_logo` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            if ($firm_logo == "") {

                $data['logo'] = "";
            } else {
                $data['logo'] = $firm_logo;
            }
        } else {
            $data['logo'] = "";
        }
        $data = array();
        $task_id = base64_decode($this->input->post('task_id'));
        $sub_task_id = base64_decode($this->input->post('subtask_id'));
        $task_assi_id = base64_decode($this->input->post('task_assi_id'));

        $query_check = $this->db->query("select checklist_id  from sub_task_checklist_master_all where sub_task_id='$sub_task_id'");
        if ($this->db->affected_rows() > 0) {
            $result = $query_check->result();
            $res = $query_check->row();
            $count = count($result);
            $cnt = 0;
            foreach ($result as $row) {
                $chceklistid = $row->checklist_id;
                $qur2 = $this->db->query("SELECT distinct check_list_id from task_checklist_transaction_all where task_id='$task_id' AND task_assignment_id='$task_assi_id' AND sub_task_id='$sub_task_id'");
                $results = $qur2->result();
                $checklist_task_id = $qur2->row();
                $count_check = count($results);
                if ($this->db->affected_rows() > 0) {
                    $cnt++;
                } else {

                }
            }
            if ($count == $count_check) {
                $response['sts'] = 'enable';
            } else {
                $response['sts'] = 'disable';
            }
        } else {

        }
        //for checking dropdown status is initiate or not

        $dropdown_status = $this->db->query("select status from customer_task_allotment_all where task_id='$task_id' and sub_task_id='$sub_task_id' and task_assignment_id='$task_assi_id'");
        if ($this->db->affected_rows() > 0) {
            $result = $dropdown_status->result();
            $res = $dropdown_status->row();
            $status = $res->status;
            if ($status == 2 || $status == 3) {
                $response['dropdown_checklist'] = 'true';
            } else {
                $response['dropdown_checklist'] = 'false';
            }
            if ($status == 3) {
                $response['dropdown_checklist_disable'] = 'yes';
            } else {
                $response['dropdown_checklist_disable'] = 'no';
            }
            if ($status == 1) {
                $response['dropdown_checklist_initiate'] = 'initiate';
            } else {
                $response['dropdown_checklist_initiate'] = 'not-initiate';
            }
        } else {

        }

        /* $dropdown_status1 = $this->db->query("select status from customer_task_allotment_all where task_id='$task_id' and sub_task_id='$sub_task_id' and task_assignment_id='$task_assi_id'");
          if ($this->db->affected_rows() > 0) {
          $result = $dropdown_status1->result();
          $res1 = $dropdown_status1->row();
          $status1 = $res1->status;
          if ($status1 == 3) {
          $response['dropdown_checklist_disable'] = 'yes';
          } else {
          $response['dropdown_checklist_disable'] = 'no';
          }
          } else {

          } */

        /* $dropdown_status2 = $this->db->query("select status from customer_task_allotment_all where task_id='$task_id' and sub_task_id='$sub_task_id' and task_assignment_id='$task_assi_id'");
          if ($this->db->affected_rows() > 0) {
          $result = $dropdown_status2->result();
          $res2 = $dropdown_status2->row();
          $status2 = $res2->status;
          if ($status2 == 1) {
          $response['dropdown_checklist_initiate'] = 'initiate';
          } else {
          $response['dropdown_checklist_initiate'] = 'not-initiate';
          }
          } else {

          } */
        $query = $this->db->query("SELECT * from `sub_task_checklist_master_all`
                                    WHERE `task_id`='$task_id' AND `sub_task_id`='$sub_task_id'");
        if ($query->num_rows() > 0) {
            $record = $query->result();

            $response['data_checklist_task'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['data_checklist_task'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } echo json_encode($response);
    }


    function add_task_checklist_c_s_t() {
        $task_id = "";
        $custom_sub_task_id = $this->input->post('custom_sub_task_id');
        $task_assi_id = $this->input->post('task_assign_id_cust');
        $checklist_id = $this->input->post('checklist_id_cust');
        $sub_task_id = "";
        $answer = $this->input->post('answer_cust');
        $comments = $this->input->post('comments_cust');

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
        $result1 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result1->num_rows() > 0) {
            $record = $result1->row();
            $user_id = $record->user_id;
        }
        $completed_on = date('y-m-d h:i:s');
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] != '4') {
            $filesCount = count($_FILES['file_upload']['name']);
            $all_files = $_FILES['file_upload']['name'];
            $file_count = count($all_files);
            $ak = 1;
            for ($i = 0; $i < $file_count; $i++) {
                $type_of_file['file_type_array'][] = pathinfo($all_files[$i], PATHINFO_EXTENSION);
            }

            $allowed = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'xls', 'xlsx', 'doc');
            $valid_file_type_result = array_diff($type_of_file['file_type_array'], $allowed);
            $count_of_valid_file_type_result = count($valid_file_type_result);
            if ($all_files[0] == "") {
                $how_many_files_check = 0;
            } else {
                $how_many_files_check = 1;
            }
            if ($how_many_files_check == 0) {
                $response['id'] = 'file_upload';
                $response['error'] = 'Select files to upload';
                $valid_file = 0;
            } elseif ($count_of_valid_file_type_result > 0) {
                $response['id'] = 'file_upload';
                $response['error'] = 'Invalid file format';
                $valid_file = 0;
            } elseif (empty($comments)) {
                $response['id'] = 'comments_cust';
                $response['error'] = 'Enter comment';
                $valid_file = 0;
            } else {
                $valid_file = 1;
                $all_pdf_file_index = array_keys($type_of_file['file_type_array'], "pdf");
                $all_gif_file_index = array_keys($type_of_file['file_type_array'], "gif");
                $all_png_file_index = array_keys($type_of_file['file_type_array'], "png");
                $all_jpg_file_index = array_keys($type_of_file['file_type_array'], "jpg");
                $all_jpeg_file_index = array_keys($type_of_file['file_type_array'], "jpeg");
                $all_img_file_index = array_merge($all_gif_file_index, $all_png_file_index, $all_jpg_file_index, $all_jpeg_file_index);
                $all_xls_file_index = array_keys($type_of_file['file_type_array'], "xls");
                $all_xlsx_file_index = array_keys($type_of_file['file_type_array'], "xlsx");
                $all_excel_file_index = array_merge($all_xls_file_index, $all_xlsx_file_index);
                $all_doc1 = array_keys($type_of_file['file_type_array'], "doc");
                $all_doc2 = array_keys($type_of_file['file_type_array'], "docx");
                $all_doc_file_index = array_merge($all_doc1, $all_doc2);
                $count_of_pdf = count($all_pdf_file_index);
                $count_of_doc = count($all_doc_file_index);
                $count_of_image = count($all_img_file_index);
                $count_of_excel = count($all_excel_file_index);
                
                if ($count_of_excel != 0) {
                    for ($o = 0; $o < $count_of_excel; $o++) {
                        $all_excel_files_index_array = $all_excel_file_index[$o];

                        if (file_exists('./uploads/all_files/' . $all_files[$all_excel_files_index_array])) {

                            $all_excel_file_name_array['all_excel_file_name'][] = date('dmYHis') . str_replace(" ", "", basename($all_files[$all_excel_files_index_array]));
                        } else {
                            $all_excel_file_name_array['all_excel_file_name'][] = $all_files[$all_excel_files_index_array];
                        }
                    }
                } else {
                    $all_excel_file_name_array['all_excel_file_name'] = 0;
                }
                if ($count_of_doc != 0) {
                    for ($x = 0; $x < $count_of_doc; $x++) {
                        $all_doc_files_index_array = $all_doc_file_index[$x];
                        if (file_exists('./uploads/all_files/' . $all_files[$all_doc_files_index_array])) {
                            $all_doc_files_name_array['all_doc_file_name'][] = date('dmYHis') . str_replace(" ", "", basename($all_files[$all_doc_files_index_array]));
                        } else {
                            $all_doc_files_name_array['all_doc_file_name'][] = $all_files[$all_doc_files_index_array];
                        }
                    }
                } else {
                    $all_doc_files_name_array['all_doc_file_name'][] = 0;
                }
                if ($count_of_pdf != 0) {
                    for ($y = 0; $y < $count_of_pdf; $y++) {
                        $all_pdf_files_index_array = $all_pdf_file_index[$y];
                        if (file_exists('./uploads/all_files/' . $all_files[$all_pdf_files_index_array])) {
                            $all_pdf_files_name_array['all_pdf_file_name'][] = date('dmYHis') . str_replace(" ", "", basename($all_files[$all_pdf_files_index_array]));
                        } else {
                            $all_pdf_files_name_array['all_pdf_file_name'][] = $all_files[$all_pdf_files_index_array];
                        }
                    }
                } else {
                    $all_pdf_files_name_array['all_pdf_file_name'][] = 0;
                }

                if ($count_of_image != 0) {
                    for ($n = 0; $n < $count_of_image; $n++) {
                        $all_image_files_index_array = $all_img_file_index[$n];
                        if (file_exists('./uploads/all_files/' . $all_files[$all_image_files_index_array])) {
                            $all_image_files_name_array['all_image_file_name'][] = date('dmYHis') . str_replace(" ", "", basename($all_files[$all_image_files_index_array]));
                        } else {

                            $all_image_files_name_array['all_image_file_name'][] = $all_files[$all_image_files_index_array];
                        }
                    }
                } else {
                    $all_image_files_name_array['all_image_file_name'][] = 0;
                }
                if ($all_pdf_files_name_array['all_pdf_file_name'] == 0) {
                    $all_pdf = 0;
                } else {
                    $all_pdf = implode(",", $all_pdf_files_name_array['all_pdf_file_name']);
                }
                if ($all_doc_files_name_array['all_doc_file_name'] == 0) {
                    $all_doc = 0;
                } else {
                    $all_doc = implode(",", $all_doc_files_name_array['all_doc_file_name']);
                }
                
                if ($all_image_files_name_array['all_image_file_name'] == 0) {
                    $all_image = 0;
                } else {
                    $all_image = implode(",", $all_image_files_name_array['all_image_file_name']);
                }

                if ($all_excel_file_name_array['all_excel_file_name'] == 0) {
                    $all_excel = 0;
                } else {
                    $all_excel = implode(",", $all_excel_file_name_array['all_excel_file_name']);
                }
                $uploadPath = './uploads/all_files/';
                for ($k = 0; $k < count($_FILES['file_upload']['name']); $k++) {
                    if (file_exists('./uploads/all_files/' . $all_files[$k])) {
                        $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['file_upload']['name'][$k]));
                        move_uploaded_file($_FILES["file_upload"]["tmp_name"][$k], './uploads/all_files/' . $newfilename);
                    } else {
                        $tmp_name = $_FILES['file_upload']['tmp_name'][$k];
                        move_uploaded_file($tmp_name, $uploadPath . $_FILES['file_upload']['name'][$k]);
                    }
                }
            }
        } else {

        }
        if ($valid_file == 1) {
            $data = array(
                'task_id' => $task_id,
                'task_assignment_id' => $task_assi_id,
                'sub_task_id' => $sub_task_id,
                'custom_sub_task_id' => $custom_sub_task_id,
                'answer' => $answer,
                'employee_id' => $user_id,
                'pdf' => $all_pdf,
                'word' => $all_doc,
                'excel' => $all_excel,
                'image' => $all_image,
                'comments' => $comments,
                'completed_on' => $completed_on,
                'check_list_id' => $checklist_id
            );
            $result = $this->checklist_model->insert_taskchecklist($data);
        } else {
            $result = false;
        }
        if ($result == true) {
            $qry = $this->db->query("update `task_checklist_transaction_all` set `status`='1' where `sub_task_id`='$sub_task_id' AND `task_assignment_id`='$task_assi_id'");
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {

        }

        echo json_encode($response);
    }

    // abhishek mishra view_checklist_cust
    function view_checklist_cust() {
        $result_data = $this->customer_model->get_firm_id();
        if ($result_data !== false) {
            $firm_id = $result_data['firm_id'];
        }
        $query = $this->db->query("SELECT `firm_logo` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            if ($firm_logo == "") {

                $data['logo'] = "";
            } else {
                $data['logo'] = $firm_logo;
            }
        } else {
            $data['logo'] = "";
        }
        $sub_task_id = ($this->input->post('subtask_id'));
        $task_id = ($this->input->post('task_id_cust'));
        $task_assi_id = ($this->input->post('task_assi_cust'));

        $query_check = $this->db->query("select checklist_id  from sub_task_checklist_master_all where custom_sub_task_id='$sub_task_id' and task_id = '' and sub_task_id =''");
        if ($this->db->affected_rows() > 0) {
            $result = $query_check->result();
            $res = $query_check->row();
            $count = count($result);
            $cnt = 0;
            foreach ($result as $row) {
                $chceklistid = $row->checklist_id;
                $qur2 = $this->db->query("SELECT distinct check_list_id from task_checklist_transaction_all where task_id='' AND task_assignment_id='$task_assi_id' AND sub_task_id=''");
                $results = $qur2->result();
                $checklist_task_id = $qur2->row();
                $count_check = count($results);
                if ($this->db->affected_rows() > 0) {
                    $cnt++;
                    $cnt;
                } else {

                }
            }
            if ($count == $count_check) {
                $response['sts'] = 'enable';
            } else {
                $response['sts'] = 'disable';
            }
        } else {

        }
        $dropdown_status = $this->db->query("select status from custom_sub_task_header_all where custom_sub_task_id='$sub_task_id' and task_assignment_id='$task_assi_id'");
        $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $result = $dropdown_status->result();
            $res = $dropdown_status->row();
            $status = $res->status;
            if ($status == 2 || $status == 3) {
                $response['dropdown_checklist'] = 'true';
            } else {
                $response['dropdown_checklist'] = 'false';
            }
            if ($status == 3) {
                $response['dropdown_checklist_disable'] = 'yes';
            } else {
                $response['dropdown_checklist_disable'] = 'no';
            }
            if ($status == 1) {
                $response['dropdown_checklist_initiate'] = 'initiate';
            } else {
                $response['dropdown_checklist_initiate'] = 'not-initiate';
            }
        } else {

        }
        $query = $this->db->query("SELECT * from `sub_task_checklist_master_all`
                                    WHERE  `custom_sub_task_id`='$sub_task_id'");
        if ($query->num_rows() > 0) {
            $record = $query->result();
            $response['data_checklist_task_cust'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['data_checklist_task'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } echo json_encode($response);
    }

    // abhishek mishra view_checklist_data_c_s_t
    function view_checklist_data_c_s_t() {
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
        $result1 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result1->num_rows() > 0) {
            $record = $result1->row();
            $user_id = $record->user_id;
        }
        $check_list_id = ($this->input->post('check_id'));
        $query = $this->db->query("SELECT * from `sub_task_checklist_master_all` WHERE `checklist_id`='$check_list_id'");

        if ($query->num_rows() > 0) {
            $record3 = $query->row();
            $custom_sub_task_id = $record3->custom_sub_task_id;
            $sub_task_id = $record3->sub_task_id;
            $query1 = $this->db->query("SELECT * from `task_checklist_transaction_all` WHERE `custom_sub_task_id`='$custom_sub_task_id' and employee_id='$user_id'");

            if ($query1->num_rows() > 0) {
                foreach ($query1->result() as $row) {
                    $data_of_checklist['data_of_checklist_array'][] = $row->completed_on;

                    $pdf_data_of_checklist['pdf_data_of_checklist_array'][] = $row->pdf;

                    $word_data_of_checklist['word_data_of_checklist_array'][] = $row->word;

                    $excel_data_of_checklist['excel_data_of_checklist_array'][] = $row->excel;

                    $image_data_of_checklist['image_data_of_checklist_array'][] = $row->image;
                }
                $my_count = count($data_of_checklist['data_of_checklist_array']);
            } else {
                $my_count = 0;
            }
            $data = '<table class="table table-striped table-bordered table-hover" id="sample_5" role="grid">
            <thead>
                <tr>
                <th>Completed On</th>
                <th>Pdf Document</th>
                <th>Word Document</th>
                <th>Excel Document</th>
                <th>Image On</th>
                <th>Delete</th>
                </tr>
                </thead>';

            for ($z = 0; $z < $my_count; $z ++) {
                $data .= ' <tbody><tr> <td>' . $data_of_checklist['data_of_checklist_array'][$z] . ' </td>';
                if ($pdf_data_of_checklist['pdf_data_of_checklist_array'][$z] == '0') {
                    $data .= '<td><button type = "button" class = "btn btn-dark btn-circle btn-circle-sm m-1 disabled"><i class = "fa fa-eye-slash"></i></button></td>';
                } else {
                    $akshay = $data_of_checklist['data_of_checklist_array'][$z];
                    $data .= '<td><button type = "button" class = "btn btn-primary btn-circle btn-circle-sm m-1" onclick="fetch_pdf(\'' . $akshay . '\')"data-toggle="modal" data-target="#ViewPdf"><i class = "fa fa-eye"></i></button></td>';
                }

                if ($word_data_of_checklist['word_data_of_checklist_array'][$z] == '0') {
                    $data .= '<td><button type = "button" class = "btn btn-dark btn-circle btn-circle-sm m-1 disabled"><i class = "fa fa-eye-slash"></i></button></td>';
                } else {
                    $akshay = $data_of_checklist['data_of_checklist_array'][$z];
                    $data .= '<td><button type = "button" class = "btn btn-primary btn-circle btn-circle-sm m-1" onclick="fetch_word(\'' . $akshay . '\')" data-toggle="modal" data-target="#ViewWord"><i class = "fa fa-eye"></i></button></td>';
                }

                if ($excel_data_of_checklist['excel_data_of_checklist_array'][$z] == '0') {
                    $data .= '<td><button type = "button" class = "btn btn-dark btn-circle btn-circle-sm m-1 disabled"><i class = "fa fa-eye-slash"></i></button></td>';
                } else {
                    $akshay = $data_of_checklist['data_of_checklist_array'][$z];
                    $data .= '<td><button type = "button" class = "btn btn-primary btn-circle btn-circle-sm m-1" onclick="fetch_excel(\'' . $akshay . '\')" data-toggle="modal" data-target="#ViewExcel"><i class = "fa fa-eye"></i></button></td>';
                }

                if ($image_data_of_checklist['image_data_of_checklist_array'][$z] == '0') {
                    $data .= '<td><button type = "button" class = "btn btn-dark btn-circle btn-circle-sm m-1 disabled"><i class = "fa fa-eye-slash"></i></button></td>';
                } else {
                    $akshay = $data_of_checklist['data_of_checklist_array'][$z];
                    $data .= '<td><button type = "button" class = "btn btn-primary btn-circle btn-circle-sm m-1"onclick="fetch_image(\'' . $akshay . '\')" data-toggle="modal" data-target="#ViewImage"><i class = "fa fa-eye"></i></button></td>';
                }

                $data .= '<td><button type = "button" class = "btn btn-danger btn-circle btn-circle-sm m-1" onclick="delete_checklist(\'' . $data_of_checklist['data_of_checklist_array'][$z] . '\')"><i class = "fa fa-trash"></i></button></td> </tr><tbody>';
            }
            $dropdown_status = $this->db->query("select status from custom_sub_task_header_all where custom_sub_task_id='$custom_sub_task_id'");
            if ($this->db->affected_rows() > 0) {
                $result = $dropdown_status->result();
                $res = $dropdown_status->row();
                $status = $res->status;
                if ($status == 3) {

                    $response['complte_data'] = 1;
                } else {
                    $response['complte_data'] = 0;
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                }
            } else {

            }
            $data .= '</table></div></div>';
            $response['data_of_checklist_subtask'] = $data;
            $record = $query->result();
            $response['data_checklist_show'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['data_checklist_show'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //pooja function
    function view_checklist_data() {
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
        $query_for_get_user_id = $this->db->query("SELECT user_id from user_header_all where email ='$email_id'");
        if ($query_for_get_user_id->num_rows() > 0) {
            foreach ($query_for_get_user_id->result() as $row) {
                $user_id = $row->user_id;
            }
        }

        $check_list_id = ($this->input->post('check_id'));
        $query = $this->db->query("SELECT * from `sub_task_checklist_master_all` WHERE `checklist_id`='$check_list_id'");
        if ($query->num_rows() > 0) {
            $record3 = $query->row();
            $task_id = $record3->task_id;
            $sub_task_id = $record3->sub_task_id;
            $query1 = $this->db->query("SELECT * from `task_checklist_transaction_all` WHERE `task_id`='$task_id' and employee_id='$user_id'");
            if ($query1->num_rows() > 0) {
                foreach ($query1->result() as $row) {
                    $data_of_checklist['data_of_checklist_array'][] = $row->completed_on;

                    $pdf_data_of_checklist['pdf_data_of_checklist_array'][] = $row->pdf;

                    $word_data_of_checklist['word_data_of_checklist_array'][] = $row->word;

                    $excel_data_of_checklist['excel_data_of_checklist_array'][] = $row->excel;

                    $image_data_of_checklist['image_data_of_checklist_array'][] = $row->image;
                }
                $my_count = count($data_of_checklist['data_of_checklist_array']);
            } else {
                $my_count = 0;
            }
            $data = '<table class="table table-striped table-bordered table-hover" id="sample_5" role="grid">
            <thead>
                <tr>
                <th>Completed On</th>
                <th>Pdf Document</th>
                <th>Word Document</th>
                <th>Excel Document</th>
                <th>Image On</th>
                <th>Delete</th>
                </tr>
                </thead>';

            for ($z = 0; $z < $my_count; $z ++) {
                $data .= ' <tbody><tr> <td>' . $data_of_checklist['data_of_checklist_array'][$z] . ' </td>';

                if ($pdf_data_of_checklist['pdf_data_of_checklist_array'][$z] == '0') {
                    $data .= '<td><button type = "button" class = "btn btn-dark btn-circle btn-circle-sm m-1 disabled"><i class = "fa fa-eye-slash"></i></button></td>';
                } else {
                    $akshay = $data_of_checklist['data_of_checklist_array'][$z];
                    $data .= '<td><button type = "button" class = "btn btn-primary btn-circle btn-circle-sm m-1" onclick="fetch_pdf(\'' . $akshay . '\')"data-toggle="modal" data-target="#ViewPdf"><i class = "fa fa-eye"></i></button></td>';
                }


                if ($word_data_of_checklist['word_data_of_checklist_array'][$z] == '0') {
                    $data .= '<td><button type = "button" class = "btn btn-dark btn-circle btn-circle-sm m-1 disabled"><i class = "fa fa-eye-slash"></i></button></td>';
                } else {
                    $akshay = $data_of_checklist['data_of_checklist_array'][$z];
                    $data .= '<td><button type = "button" class = "btn btn-primary btn-circle btn-circle-sm m-1" onclick="fetch_word(\'' . $akshay . '\')" data-toggle="modal" data-target="#ViewWord"><i class = "fa fa-eye"></i></button></td>';
                }

                if ($excel_data_of_checklist['excel_data_of_checklist_array'][$z] == '0') {
                    $data .= '<td><button type = "button" class = "btn btn-dark btn-circle btn-circle-sm m-1 disabled"><i class = "fa fa-eye-slash"></i></button></td>';
                } else {
                    $akshay = $data_of_checklist['data_of_checklist_array'][$z];
                    $data .= '<td><button type = "button" class = "btn btn-primary btn-circle btn-circle-sm m-1" onclick="fetch_excel(\'' . $akshay . '\')" data-toggle="modal" data-target="#ViewExcel"><i class = "fa fa-eye"></i></button></td>';
                }

                if ($image_data_of_checklist['image_data_of_checklist_array'][$z] == '0') {
                    $data .= '<td><button type = "button" class = "btn btn-dark btn-circle btn-circle-sm m-1 disabled"><i class = "fa fa-eye-slash"></i></button></td>';
                } else {
                    $akshay = $data_of_checklist['data_of_checklist_array'][$z];
                    $data .= '<td><button type = "button" class = "btn btn-primary btn-circle btn-circle-sm m-1"onclick="fetch_image(\'' . $akshay . '\')" data-toggle="modal" data-target="#ViewImage"><i class = "fa fa-eye"></i></button></td>';
                }
                $data .= '<td><button type = "button" class = "btn btn-danger btn-circle btn-circle-sm m-1" onclick="delete_checklist(\'' . $data_of_checklist['data_of_checklist_array'][$z] . '\')"><i class = "fa fa-trash"></i></button></td> </tr><tbody>';
            }
            $dropdown_status = $this->db->query("select status from customer_task_allotment_all where sub_task_id='$sub_task_id' and task_id='$task_id'");
            if ($this->db->affected_rows() > 0) {
                $result = $dropdown_status->result();
                $res = $dropdown_status->row();
                $status = $res->status;
                if ($status == 3) {

                    $response['complte_data'] = 1;
                } else {
                    $response['complte_data'] = 0;
                    //$response['dropdown_checklist'] = 'false';
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                }
            } else {

            }
            $data .= '</table></div></div>';
            $response['data_of_checklist_subtask'] = $data;
            $record = $query->result();
            $response['data_checklist_show'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['data_checklist_show'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

// This function for delete the check list Akshay
    public function delete_checklist() {
        $delete_data = $this->input->post('delete_data');
        $current_date_time = gmdate('Y-m-d h:i:s');
        $date1 = strtotime($current_date_time);
        $date2 = strtotime($delete_data);
        $diff = abs($date2 - $date1);
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
        $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
        
        if ($days == 0 && $hours < 3) {
            $qry_delete = $this->checklist_model->delete_checklist($delete_data);
            if ($qry_delete !== false) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'fail';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $response['message'] = 'time_not_match';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //function for fetcch pdf files from database and travers to show_task.php page in employee view abhishek mishra
    public function fetch_pdf_data() {
        $date_of_completed_on = $this->input->post('datetime');
        $query_for_fetch_data = $this->db->query("SELECT pdf FROM `task_checklist_transaction_all` WHERE `completed_on`='$date_of_completed_on'");

        if ($query_for_fetch_data->num_rows() > 0) {
            $record = $query_for_fetch_data->row();
            $response['data_for_show_pdf_file'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $record = "";
            $response['data_for_show_pdf_file'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //function for fetcch word files from database and travers to show_task.php page in employee view abhishek mishra
    public function fetch_word_data() {
        $date_of_completed_on = $this->input->post('datetime');
        $query_for_fetch_data = $this->db->query("SELECT word FROM `task_checklist_transaction_all` WHERE `completed_on`='$date_of_completed_on'");
        if ($query_for_fetch_data->num_rows() > 0) {
            $record = $query_for_fetch_data->row();
            $response['data_for_show_word_file'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $record = "";
            $response['data_for_show_word_file'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function fetch_excel_data() {
        $date_of_completed_on = $this->input->post('datetime');
        $query_for_fetch_data = $this->db->query("SELECT excel FROM `task_checklist_transaction_all` WHERE `completed_on`='$date_of_completed_on'");

        if ($query_for_fetch_data->num_rows() > 0) {
            $record = $query_for_fetch_data->row();
            $response['data_for_show_excel_file'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $record = "";
            $response['data_for_show_excel_file'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //function for fetcch image files from database and travers to show_task.php page in employee view abhishek mishra
    public function fetch_image_data() {
        $date_of_completed_on = $this->input->post('datetime');
        $query_for_fetch_data = $this->db->query("SELECT image FROM `task_checklist_transaction_all` WHERE `completed_on`='$date_of_completed_on'");

        if ($query_for_fetch_data->num_rows() > 0) {
            $record = $query_for_fetch_data->row();
            $response['data_for_show_image_file'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $record = "";
            $response['data_for_show_image_file'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //pooja function + akshay function
    function add_task_checklist() {
        $task_id = $this->input->post('task_id');
        $task_assi_id = base64_decode($this->input->post('task_assign_id'));
        $checklist_id = $this->input->post('checklist_id');
        $sub_task_id = $this->input->post('sub_task_id');
        $answer = $this->input->post('answer');
        $comments = $this->input->post('comments');
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
        $result1 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result1->num_rows() > 0) {
            $record = $result1->row();
            $user_id = $record->user_id;
        }
        $completed_on = date('y-m-d h:i:s');
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] != '4') {
            $filesCount = count($_FILES['file_upload']['name']);
            $all_files = $_FILES['file_upload']['name'];
            $file_count = count($all_files);
            $ak = 1;
            for ($i = 0; $i < $file_count; $i++) {
                $type_of_file['file_type_array'][] = pathinfo($all_files[$i], PATHINFO_EXTENSION);
            }

            $allowed = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'xls', 'xlsx', 'doc');
            $valid_file_type_result = array_diff($type_of_file['file_type_array'], $allowed);
            $count_of_valid_file_type_result = count($valid_file_type_result);
            if ($all_files[0] == "") {
                $how_many_files_check = 0;
            } else {
                $how_many_files_check = 1;
            }
            if ($how_many_files_check == 0) {
                $response['id'] = 'file_upload';
                $response['error'] = 'Select files to upload';
                $valid_file = 0;
            } elseif ($count_of_valid_file_type_result > 0) {
                $response['id'] = 'file_upload';
                $response['error'] = 'Invalid file format';
                $valid_file = 0;
            } elseif (empty($comments)) {
                $response['id'] = 'comments_cust';
                $response['error'] = 'Enter comment';
                $valid_file = 0;
            } else {
                $valid_file = 1;
                $all_pdf_file_index = array_keys($type_of_file['file_type_array'], "pdf");
                $all_gif_file_index = array_keys($type_of_file['file_type_array'], "gif");
                $all_png_file_index = array_keys($type_of_file['file_type_array'], "png");
                $all_jpg_file_index = array_keys($type_of_file['file_type_array'], "jpg");
                $all_jpeg_file_index = array_keys($type_of_file['file_type_array'], "jpeg");
                $all_img_file_index = array_merge($all_gif_file_index, $all_png_file_index, $all_jpg_file_index, $all_jpeg_file_index);
                $all_xls_file_index = array_keys($type_of_file['file_type_array'], "xls");
                $all_xlsx_file_index = array_keys($type_of_file['file_type_array'], "xlsx");
                $all_excel_file_index = array_merge($all_xls_file_index, $all_xlsx_file_index);
                $all_doc1 = array_keys($type_of_file['file_type_array'], "doc");
                $all_doc2 = array_keys($type_of_file['file_type_array'], "docx");
                $all_doc_file_index = array_merge($all_doc1, $all_doc2);
                $count_of_pdf = count($all_pdf_file_index);
                $count_of_doc = count($all_doc_file_index);
                $count_of_image = count($all_img_file_index);
                $count_of_excel = count($all_excel_file_index);
                // echo $count_of_image;
                if ($count_of_excel != 0) {
                    for ($o = 0; $o < $count_of_excel; $o++) {
                        $all_excel_files_index_array = $all_excel_file_index[$o];

                        if (file_exists('./uploads/all_files/' . $all_files[$all_excel_files_index_array])) {

                            $all_excel_file_name_array['all_excel_file_name'][] = date('dmYHis') . str_replace(" ", "", basename($all_files[$all_excel_files_index_array]));
                        } else {
                            $all_excel_file_name_array['all_excel_file_name'][] = $all_files[$all_excel_files_index_array];
                        }
                    }
                } else {
                    $all_excel_file_name_array['all_excel_file_name'] = 0;
                }
                if ($count_of_doc != 0) {
                    for ($x = 0; $x < $count_of_doc; $x++) {
                        $all_doc_files_index_array = $all_doc_file_index[$x];
                        if (file_exists('./uploads/all_files/' . $all_files[$all_doc_files_index_array])) {

                            $all_doc_files_name_array['all_doc_file_name'][] = date('dmYHis') . str_replace(" ", "", basename($all_files[$all_doc_files_index_array]));
                        } else {
                            $all_doc_files_name_array['all_doc_file_name'][] = $all_files[$all_doc_files_index_array];
                        }
                    }
                } else {
                    $all_doc_files_name_array['all_doc_file_name'][] = 0;
                }
                if ($count_of_pdf != 0) {
                    for ($y = 0; $y < $count_of_pdf; $y++) {
                        $all_pdf_files_index_array = $all_pdf_file_index[$y];
                        if (file_exists('./uploads/all_files/' . $all_files[$all_pdf_files_index_array])) {

                            $all_pdf_files_name_array['all_pdf_file_name'][] = date('dmYHis') . str_replace(" ", "", basename($all_files[$all_pdf_files_index_array]));
                        } else {
                            $all_pdf_files_name_array['all_pdf_file_name'][] = $all_files[$all_pdf_files_index_array];
                        }
                    }
                } else {
                    $all_pdf_files_name_array['all_pdf_file_name'][] = 0;
                }

                if ($count_of_image != 0) {
                    for ($n = 0; $n < $count_of_image; $n++) {
                        $all_image_files_index_array = $all_img_file_index[$n];
                        if (file_exists('./uploads/all_files/' . $all_files[$all_image_files_index_array])) {

                            $all_image_files_name_array['all_image_file_name'][] = date('dmYHis') . str_replace(" ", "", basename($all_files[$all_image_files_index_array]));
                        } else {

                            $all_image_files_name_array['all_image_file_name'][] = $all_files[$all_image_files_index_array];
                        }
                    }
                } else {
                    $all_image_files_name_array['all_image_file_name'][] = 0;
                }
                if ($all_pdf_files_name_array['all_pdf_file_name'] == 0) {
                    $all_pdf = 0;
                } else {
                    $all_pdf = implode(",", $all_pdf_files_name_array['all_pdf_file_name']);
                }
                if ($all_doc_files_name_array['all_doc_file_name'] == 0) {
                    $all_doc = 0;
                } else {
                    $all_doc = implode(",", $all_doc_files_name_array['all_doc_file_name']);
                }
                if ($all_image_files_name_array['all_image_file_name'] == 0) {
                    $all_image = 0;
                } else {
                    $all_image = implode(",", $all_image_files_name_array['all_image_file_name']);
                }

                if ($all_excel_file_name_array['all_excel_file_name'] == 0) {
                    $all_excel = 0;
                } else {
                    $all_excel = implode(",", $all_excel_file_name_array['all_excel_file_name']);
                }
                $uploadPath = './uploads/all_files/';
                for ($k = 0; $k < count($_FILES['file_upload']['name']); $k++) {
                    if (file_exists('./uploads/all_files/' . $all_files[$k])) {
                        $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['file_upload']['name'][$k]));
                        move_uploaded_file($_FILES["file_upload"]["tmp_name"][$k], './uploads/all_files/' . $newfilename);
                    } else {
                        $tmp_name = $_FILES['file_upload']['tmp_name'][$k];
                        move_uploaded_file($tmp_name, $uploadPath . $_FILES['file_upload']['name'][$k]);
                    }
                }
            }
        } else {

        }
        if ($valid_file == 1) {
            $data = array(
                'task_id' => $task_id,
                'sub_task_id' => $sub_task_id,
                'task_assignment_id' => $task_assi_id,
                'custom_sub_task_id' => $custom_sub_task_id,
                'answer' => $answer,
                'employee_id' => $user_id,
                'pdf' => $all_pdf,
                'word' => $all_doc,
                'excel' => $all_excel,
                'image' => $all_image,
                'comments' => $comments,
                'completed_on' => $completed_on,
                'check_list_id' => $checklist_id
            );

            $result = $this->checklist_model->insert_taskchecklist($data);
        } else {
            $result = false;
        }
        if ($result == true) {
            $qry = $this->db->query("update `customer_task_allotment_all` set `status`='3' where `sub_task_id`='$sub_task_id' AND `task_assignment_id`='$task_assi_id'");
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {

        }
        echo json_encode($response);
    }

    //pooja function
    function add_task_checklist_cust() {
        $checklist_id = $this->input->post('checklist_id_cust');
        $task_assi_id = $this->input->post('task_assi_id_cust');
        $sub_task_id = $this->input->post('custom_sub_task_id');
        $answer = $this->input->post('answer_cust');
        $comments = $this->input->post('comments_cust');
        $email_id = $this->session->userdata('login_session');
        $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result->num_rows() > 0) {
            $record = $result->row();
            $user_id = $record->user_id;
        }
        $completed_on = date('y-m-d h:i:s');
        $files = $this->upload_multiple_task($sub_task_id);
        if ($files == 'invalid') {
            $response['id'] = 'file_upload';
            $response['error'] = 'Invalid file format';
        } else {
            $data = array(
                'answer' => $answer,
                'employee_id' => $user_id,
                'employee_id' => $user_id,
                'custom_sub_task_id' => $sub_task_id,
                'file_upload' => $files,
                'comments' => $comments,
                'completed_on' => $completed_on,
                'check_list_id' => $checklist_id
            );

            $result = $this->checklist_model->insert_taskchecklist($data);
            if ($result == true) {
                $qry = $this->db->query("update `custom_sub_task_header_all` set `status`='3' where `custom_sub_task_id`='$sub_task_id'");
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

    public function upload_multiple_task($task_id) {
        $response = array();
        $user_id = $task_id; // session or user_id
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] != '4') {

            $filesCount = count($_FILES['file_upload']['name']);

            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name'] = $_FILES['file_upload']['name'][$i];
                $_FILES['file']['type'] = $_FILES['file_upload']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['file_upload']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['file_upload']['error'][$i];
                $_FILES['file']['size'] = $_FILES['file_upload']['size'][$i];
                echo $_FILES['file']['type'];
                // File upload configuration
                $uploadPath = './uploads/gallery/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|xlsx|xls|doc|docx';

                // Load and initialize upload library
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                // Upload file to server
                if ($this->upload->do_upload('file')) {
                    echo "success uploaded";
                } else {
                    echo "failed";
                }
            }
        }
    }

    public function upload_multiple($due_date_id) {
        $response = array();
        $user_id = $due_date_id; // session or user_id
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] != '4') {
            $files = $_FILES;
            $count = $_FILES['file_upload']['name']; // count element
            $_FILES['file_upload']['name'] = $files['file_upload']['name'];
            $_FILES['file_upload']['type'] = $files['file_upload']['type'];
            $_FILES['file_upload']['tmp_name'] = $files['file_upload']['tmp_name'];
            $_FILES['file_upload']['error'] = $files['file_upload']['error'];
            $_FILES['file_upload']['size'] = $files['file_upload']['size'];
            $config['upload_path'] = './uploads/gallery/';
            $target_path = './uploads/gallery/thumbs/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|ppt|pptx';
            $config['max_size'] = '500000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = $_FILES['file_upload']['name'];
            $fileName = str_replace(' ', '_', $fileName);
            $fileName = preg_replace('/\s+/', '_', $fileName);
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('file_upload');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['file_upload']['name'][$i] . ' ' . $error['upload_error'];
                    $response = "invalid";
                    return $response;
                } else {
                    return $fileName;
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
        }
    }

    public function upload_multiple_counter($task_id) {
        $_FILES['task_files'];
        $response = array();
        $user_id = $task_id; // session or user_id
        if (isset($_FILES['task_files']) && $_FILES['task_files']['error'] != '4') :
            $files = $_FILES;
            $count = count($_FILES['task_files']['name']); // count element
            for ($i = 0; $i < $count; $i++):
                $_FILES['task_file s']['name'] = $files['task_files']['name'][$i];
                $_FILES['task_files']['type'] = $files['task_files']['type'][$i];
                $_FILES['task_files']['tmp_name'] = $files['task_files']['tmp_name'][$i];
                $_FILES['task_files']['error'] = $files['task_files']['error'][$i];
                $_FILES['task_files']['size'] = $files['task_files']['size'][$i];
                $config['upload_path'] = './uploads/task/';
                $target_path = './uploads/task/thumbs/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|ppt|pptx';
                $config['max_size'] = '500000';    //limit 10000=1 mb
                $config['remove_spaces'] = true;
                $config['overwrite'] = false;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                $fileName = $_FILES['task_files']['name'];
                $fileName = str_replace(' ', '_', $fileName);
                $fileName = preg_replace('/\s+/', '_', $fileName);
                $data = array('upload_data' => $this->upload->data());
                if (empty($fileName)) {
                    return false;
                } else {
                    $file = $this->upload->do_upload('task_files');
                    if (!$file) {
                        $error = array('upload_error' => $this->upload->display_errors());
                        $response['error'] = $files['task_files']['name'][$i] . ' ' . $error['upload_error'];
                        $response = "invalid";
                        return $response;
                    } else {
                        return $fileName;
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
            endfor;
        endif;
    }

    function view_subtask() {
        $data['prev_title'] = "Sub Task List";
        $data['page_title'] = "Sub Task List";
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
        $this->load->helper('url');
        $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result->num_rows() > 0) {
            $record = $result->row();
            $user_id = $record->user_id;
            $query = $this->db->query("SELECT * FROM `customer_task_allotment_all` WHERE  alloted_to_emp_id='$user_id' AND sub_task_id != ''");
            if ($query->num_rows() > 0) {
                $record = $query->result();
                $data['result'] = $record;
                $this->load->view('employee/view_subtask', $data);
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $data['result'] = '';
                $this->load->view('employee/view_subtask', $data);
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $data['result'] = '';
            $this->load->view('employee/view_subtask', $data);
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
    }

    function view_stickynote() {
        $data['prev_title'] = "Sticky Notes";
        $data['page_title'] = "Sticky Notes";
        $this->load->view('employee/view_stickynote', $data);
    }

    function show_duedate() {
        $data['prev_title'] = "Due Date Detail";
        $data['page_title'] = "Due Date Detail";
        $queryOfprivateDuedateDate = $this->emp_model->view_privateDuedateDat();
        $queryOfPublicDuedateDateTask = $this->emp_model->view_PublicDuedateDateTask();
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

        //For Private
        if ($queryOfprivateDuedateDate !== FALSE) {
            $record = $queryOfprivateDuedateDate->result();
            /* foreach ($record as $row) {
              $due_date_id = $row->due_date_task_id;
              $customer_id = $row->customer_id;
              $alloted_to = $row->alloted_to;
              $result_duedatetask_name = $this->db->query("SELECT * FROM `due_date_task_header_all` WHERE `due_date_task_id`='$due_date_id'");
              if ($result_duedatetask_name->num_rows() > 0) {
              foreach ($result_duedatetask_name->result() as $row_duedatetask_name) {
              $response['due_date_task_name'][] = ['due_date_task_name' => $row_duedatetask_name->due_date_task_name];
              }
              } else {
              $response['due_date_task_name'][] = ['due_date_task_name' => ''];
              }
              //                 echo "SELECT * FROM `user_header_all` WHERE  `user_id` = '$alloted_to'";
              if ($alloted_to != '') {
              $query_employee = $this->db->query("SELECT * FROM `user_header_all` WHERE  `user_id` = '$alloted_to'");
              if ($query_employee->num_rows() > 0) {
              foreach ($query_employee->result() as $row_employee) {
              $response['employee_data'][] = ['user_name' => $row_employee->user_name];
              }
              }
              } else {
              $response['employee_data'][] = ['user_name' => ''];
              }
              if ($customer_id != '') {
              $query_customer = $this->db->query("SELECT * FROM `customer_header_all` WHERE  `customer_id` = '$customer_id'");
              if ($query_customer->num_rows() > 0) {
              foreach ($query_customer->result() as $row_customer) {
              $response['customer_name'][] = ['customer_name' => $row_customer->customer_name];
              }
              }
              } else {
              $response['customer_name'][] = ['customer_name' => ''];
              }
              } */
            $data['page_title'] = "Due Date Detail";
            $data['prev_title'] = "Due Date Detail";
            $data['result'] = $record;
        } else {
            $data['page_title'] = "Due Date Detail";
            $data['prev_title'] = "Due Date Detail";
            $data['result'] = '';
        }
        if ($queryOfPublicDuedateDateTask !== FALSE) {
            $record_non_user = $queryOfPublicDuedateDateTask->result();
            /* foreach ($record_non_user as $row_non_user) {
              $due_date_id = $row_non_user->due_date_task_id;
              $customer_id = $row_non_user->customer_id;
              $alloted_to = $row_non_user->alloted_to;
              $result_duedatetask_name = $this->db->query("SELECT * FROM `due_date_task_header_all` WHERE `due_date_task_id`='$due_date_id'");
              if ($result_duedatetask_name->num_rows() > 0) {
              foreach ($result_duedatetask_name->result() as $row_duedatetask_name) {
              $response['due_date_task_name1'][] = ['due_date_task_name' => $row_duedatetask_name->due_date_task_name];
              }
              } else {
              $response['due_date_task_name1'][] = ['due_date_task_name' => ''];
              }
              //                echo "SELECT * FROM `user_header_all` WHERE  `user_id` = '$alloted_to'";
              if ($alloted_to != '') {
              $query_employee = $this->db->query("SELECT * FROM `user_header_all` WHERE  `user_id` = '$alloted_to'");
              if ($query_employee->num_rows() > 0) {
              foreach ($query_employee->result() as $row_employee) {
              $response['employee_data1'][] = ['user_name' => $row_employee->user_name];
              }
              }
              } else {
              $response['employee_data1'][] = ['user_name' => ''];
              }
              if ($customer_id != '') {
              $query_customer = $this->db->query("SELECT * FROM `customer_header_all` WHERE  `customer_id` = '$customer_id'");
              if ($query_customer->num_rows() > 0) {
              foreach ($query_customer->result() as $row_customer) {
              $response['customer_name1'][] = ['customer_name' => $row_customer->customer_name];
              }
              }
              } else {
              $response['customer_name1'][] = ['customer_name' => ''];
              }
              } */
            $data['prev_title'] = "Due Date Detail";
            $data['page_title'] = "Due Date Detail";
            $data['result_non_user'] = $record_non_user;
        } else {
            $data['page_title'] = "Due Date Detail";
            $data['prev_title'] = "Due Date Detail";
            $data['result_non_user'] = '';
        }
        $this->load->view('employee/show_duedate', $data);
    }

    function create_stickynote() {
        $data['prev_title'] = "Create Sticky Notes";
        $data['page_title'] = "Create Sticky Notes";
        $this->load->view('employee/create_stickynote', $data);
    }

    public function edit_task_assign($id = NULL) {
        $data['id'] = $id;
        $data['prev_title'] = "View Task Assign";
        $data['page_title'] = "View Task Assign";

        $result = $this->employee_login_model->get_employee_data($id);
        if ($result == false) {
            $data['result'] = "";
            $this->load->view('employee/edit_sub_task', $data);
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = false;
        } else {
            $data['result'] = $result->row();
            $this->load->view('employee/edit_sub_task', $data);
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        }
    }

    public function change_status() {
        $customer_id = ($this->input->post('customer_id'));
        $result = $this->employee_login_model->emp_customer_task_status($customer_id);
        if ($result === true) {
            $response['msg'] = $result;
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

    public function add_duedate_checklist() {
        $checklist_submit_id = $this->input->post('checklist_submit_data');
        $due_date_id = $this->input->post('due_date_id');
        $due_date_task_id = $this->input->post('due_date_task_id');
        $customer_id = $this->input->post('customer_id');
        $checklist_id = $this->input->post('checklist_id');
        $remark_cheklist = $this->input->post('remark_cheklist');
        $file_upload = $this->upload_multiple($due_date_id);

        // $file_upload = $this->do_upload();
        $count_check = $this->input->post('count_checklist');
        $created_on = date('y-m-d h:i:s');
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        if ($user_id === "") {
            $user_id = $this->session->userdata('login_session');
        }
        $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$user_id'");
        if ($result->num_rows() > 0) {
            $record = $result->row();
            $user_id = $record->user_id;
        }

        for ($i = 0; $i < $count_check; $i++) {
            $answer = $this->input->post('answer' . $i);
            $comments = $this->input->post('comments' . $i);
            $completed_on = date('y-m-d h:i:s');
            $data = array(
                'due_date_id' => $due_date_id,
                'due_date_task_id' => $due_date_task_id,
                'customer_id' => $customer_id,
                'answer' => $answer,
                'employee_id' => $user_id,
                'checklist_id' => $checklist_id,
                'comments' => $comments,
                'completed_on' => $completed_on
            );
            $result = $this->duedate_checklist->insert_duedatechecklist($data);
        }
        $previous_data = $this->db->query("select * from customer_due_date_task_transaction_all where due_date_id = '$due_date_id' AND due_date_task_id = '$due_date_task_id' AND customer_id = '$customer_id'")->row();


        if ($previous_data->attach_file_link != "" && $previous_data->modified_on != "0000-00-00 00:00:00") {
            $modified_on = $previous_data->modified_on . ',';
            $previous_file = $previous_data->attach_file_link . ',';
        } else {
            $modified_on = "";
            $previous_file = $previous_data = "";
        }

        $data1 = array(
            'filled_by' => $user_id,
            'attach_file_link' => $previous_file . $file_upload,
            'status' => $checklist_submit_id,
            'remark' => $remark_cheklist,
            ' modified_on' => $modified_on . $created_on,
            'filled_on' => $created_on
        );

        $record = $this->duedate_checklist->update_customer_due_date_task_transaction_all($data1, $due_date_id, $due_date_task_id, $customer_id);
        if ($record == true) {
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

    public function do_upload() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|xls|xlsx';
        $config['max_size'] = 100;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_upload')) {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('employee/show_duedate', $error);
        } else {
            $data = array('upload_data' => $this->upload->data());

            $this->load->view('employee/show_duedate', $data);
        }
    }

    public function view_sub_task_allot1() {
        $cust_id = ($this->input->post('cust_id2'));
        $tsk_id = ($this->input->post('task_id_new1'));
        $task_assign_id = $this->input->post('task_assign_id1');
        $result = $this->customer_model->get_firm_id();
        if ($result !== false) {
            $firm_id = $result['firm_id'];
            // $boss_id = $result['boss_id'];
        }
        $query3 = $this->db->query("SELECT `customer_task_allotment_all`.`task_assignment_name`,`customer_task_allotment_all`.`task_assignment_description`,`customer_task_allotment_all`.`alloted_to_emp_id`,`customer_task_allotment_all`.`completion_date`,`customer_task_allotment_all`.`status`, `customer_task_allotment_all`.`task_id`, `customer_task_allotment_all`.`sub_task_id`, `task_header_all`.`task_name`, `sub_task_header_all`.`sub_task_name`, `user_header_all`.`user_name`
            from `customer_task_allotment_all`
            Inner Join `task_header_all`
            ON `customer_task_allotment_all`.`task_id`=`task_header_all`.`task_id`
            INNER JOIN `sub_task_header_all`
            ON `customer_task_allotment_all`.`sub_task_id`=`sub_task_header_all`.`sub_task_id`
            INNER JOIN `user_header_all`
            ON`customer_task_allotment_all`.`alloted_to_emp_id`=`user_header_all`.`user_id`
            WHERE `customer_task_allotment_all`.`customer_id`='$cust_id'  AND `customer_task_allotment_all`.`task_assignment_id`='$task_assign_id' AND `customer_task_allotment_all`.`firm_id`='$firm_id'");

        if ($query3->num_rows() > 0) {
            $record = $query3->result();
            $response['result_subtask_allot1'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function view_cust_sub_task_allot1() {
        $cust_id = ($this->input->post('cust_id2'));
        $tsk_assign_id = ($this->input->post('task_assign_id1'));
        $query3 = $this->db->query("SELECT `custom_sub_task_header_all`.`sub_task_name`,`custom_sub_task_header_all`.`status` ,`custom_sub_task_header_all`.`completion_date`,`custom_sub_task_header_all`.`custom_sub_task_id` ,`user_header_all`.`user_name`,`custom_sub_task_header_all`.`custom_sub_task_id`
            FROM `custom_sub_task_header_all`
            INNER JOIN `user_header_all`
            ON `custom_sub_task_header_all`.`alloted_to`=`user_header_all`.`user_id`
            WHERE `custom_sub_task_header_all`.`customer_id`='$cust_id' AND `custom_sub_task_header_all`.`task_assignment_id`= '$tsk_assign_id'");

        if ($query3->num_rows() > 0) {
            $record = $query3->result();
            $response['result_custom_subtask_allot1'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    // change status in table sub task allotmet
    public function update_status() {
        $sub_task_id = $this->input->post('sub_tsk_id');
        $task_assi_id = $this->input->post('task_assi_id');
        $task_id = $this->input->post('task_id');
        $status = $this->input->post('status_value');

        if ($status == 1) {
            $query_change_status = $this->db->set('status', $status)
                    ->where(array("sub_task_id" => $task_id, "task_assignment_id" => $task_assi_id))
                    ->update("customer_task_allotment_all");
            $check1 = $query_change_status = $this->db->affected_rows();
            if ($check1 > 0) {
                if ($status == 2) {
                    $query_change_status1 = $this->db->set("status", 2)
                            ->where(array('sub_task_id' => '', 'task_id' => $task_id, "task_assignment_id" => $task_assi_id))
                            ->update("customer_task_allotment_all");
                    $check = $query_change_status1 = $this->db->affected_rows();
                    if ($check > 0) {

                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                }
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $query_change_status = $this->db->set('status', $status)
                    ->where(array("sub_task_id" => $task_id, "task_assignment_id" => $task_assi_id))
                    ->update("customer_task_allotment_all");
            $check1 = $query_change_status = $this->db->affected_rows();
            if ($check1 > 0) {
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

    public function update_status_custom() {
        $custom_sub_task_id = ($this->input->post('sub_tsk_id_cust'));
        $task_id = ($this->input->post('tsk_id_cust'));
        $status = ($this->input->post('status_value'));
        $task_assignm_id = ($this->input->post('task_assignm_id'));

        $action = ($this->input->post('action'));
        if ($status == 1) {
            $query_change_status = $this->db
                            ->set('status', $status)
                            ->where("custom_sub_task_id", $custom_sub_task_id)->update("custom_sub_task_header_all");
            $check1 = $query_change_status = $this->db->affected_rows();
            if ($check1 > 0) {
                if ($status == 2) {
                    $query_change_status1 = $this->db
                            ->set('status', 2)
                            ->where(array("task_id" => $task_id, "task_assignment_id" => $task_assignm_id))
                            ->update("customer_task_allotment_all");
                    $check = $query_change_status1 = $this->db->affected_rows();
                    if ($check > 0) {
                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                }
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $query_change_status = $this->db
                            ->set('status', $status)
                            ->where("custom_sub_task_id", $custom_sub_task_id)->update("custom_sub_task_header_all");
            $check1 = $query_change_status = $this->db->affected_rows();
            if ($check1 > 0) {
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

    // change status in table sub task allotmet in work to do modal
    public function update_status_check() {
        $sub_task_id = base64_decode($this->input->post('sub_task_id'));
        $task_id = base64_decode($this->input->post('task_id'));
        $task_assign_id = base64_decode($this->input->post('task_assign_id'));
        $status = ($this->input->post('status_value'));

        $query_change_status = $this->db->query("UPDATE `customer_task_allotment_all` SET `status` = '$status' WHERE `sub_task_id`='$sub_task_id' AND `task_assignment_id`='$task_assign_id'");
        if ($this->db->affected_rows() > 0) {
            if ($status = 2) {
                $query_change_status1 = $this->db->query("UPDATE `customer_task_allotment_all` SET `status` = 2 WHERE `sub_task_id`='' AND `task_id`='$task_id'");
            }
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            echo json_encode($response);
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
            echo json_encode($response);
        }
    }

    public function complete_task() {
        $task_assign_id = ($this->input->post('task_assi_id'));
        $query_change_status = $this->db->query("UPDATE `customer_task_allotment_all` SET `status` = '3' WHERE `sub_task_id`='' AND `task_assignment_id`='$task_assign_id'");
        if ($this->db->affected_rows() > 0) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            echo json_encode($response);
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
            echo json_encode($response);
        }
    }

    public function complete_task_btn_validation() {
        $task_assign_id = ($this->input->post('task_assign_id1'));
        $query_status_check1 = $this->db->query("SELECT COUNT(*) as total_task_assignment_id FROM customer_task_allotment_all WHERE `task_assignment_id`='$task_assign_id' AND sub_task_id !=''");
        if ($query_status_check1->num_rows() > 0) {
            $record_count = $query_status_check1->result();
            foreach ($record_count as $row) {
                $task_assignment_id = $row->total_task_assignment_id;
            }
        } else {
            $task_assignment_id = '';
        }
        $query_cust = $this->db->query("SELECT COUNT(*) as total_cust FROM custom_sub_task_header_all WHERE `task_assignment_id`='$task_assign_id'");
        if ($query_cust->num_rows() > 0) {
            $record_count2 = $query_cust->result();
            foreach ($record_count2 as $row) {
                $total_cust = $row->total_cust;
            }
        } else {
            $total_cust = '';
        }

        $total = $task_assignment_id + $total_cust;
        $query_status_check2 = $this->db->query("SELECT COUNT(*) as total_status FROM customer_task_allotment_all WHERE `task_assignment_id`='$task_assign_id' AND `status`=3 AND sub_task_id !=''");
        if ($query_status_check2->num_rows() > 0) {
            $record_count1 = $query_status_check2->result();
            foreach ($record_count1 as $row1) {
                $total_status = $row1->total_status;
            }
        } else {
            $total_status = '';
        }

        $query_status_check3 = $this->db->query("SELECT COUNT(*) as total_status_cust FROM custom_sub_task_header_all WHERE `task_assignment_id`='$task_assign_id' AND `status`=3 ");
        if ($query_status_check3->num_rows() > 0) {
            $record_count1 = $query_status_check3->result();
            foreach ($record_count1 as $row1) {
                $total_status_cust = $row1->total_status_cust;
            }
        } else {
            $total_status_cust = '';
        }

        $get_task_completion_sts = $this->db->query("SELECT status from customer_task_allotment_all WHERE `task_assignment_id`='$task_assign_id' AND sub_task_id=''");
        $resu = $get_task_completion_sts->row();
        $status = $resu->status;
        $response['sts'] = $status;
        $total1 = $total_status + $total_status_cust;
        if ($total === $total1) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            echo json_encode($response);
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
            echo json_encode($response);
        }
    }

    public function save_survey_answer() {
        extract($_POST);
        for ($i = 0; $i <= $question_count; $i++) {
            if (isset($_POST['option_' . $i])) {
                $selected_option = $this->input->post('option_' . $i);
                $option_array = explode("^", $selected_option);
                $question_id = $option_array[0];
                $option_now = $option_array[1];
                $stmp_to_check = $this->employee_login_model->get_employee_question_ans_transaction($question_id, $employee_id);
                if ($stmp_to_check !== false) {
                    $stmp_to_check_fetch = $stmp_to_check->row();
                    $no_of_count = ($stmp_to_check_fetch->no_of_count * 1) + (1 * 1);
                }

                $query_weightage = $this->employee_login_model->get_weightage($option_now);
                if ($query_weightage !== false) {
                    $result_weightage = $query_weightage->row();
                    $weightage = $result_weightage->weightage;
                }

                if ($stmp_to_check !== false) {
                    $answer_change = $stmp_to_check_fetch->answer_change;
                    $answer_on = date('Y-m-d H:i:s');
                    if ($answer_change !== $option_now) {
                        $data = array(
                            'answer_change' => $option_now,
                            'answer_change_weightage' => $weightage,
                            'answer_updated_on' => $answer_on,
                            'no_of_count' => $no_of_count
                        );
                        $where = array(
                            'question_id' => $question_id,
                            'survey_respondent_id' => $employee_id
                        );

                        $query_updt = $this->employee_login_model->updt_employee_question_ans_transaction($data, $where);
                        if ($query_updt !== FALSE) {
                            $response['message'] = 'success';
                        } else {
                            $response['message'] = 'fail';
                        }
                    } else {
                        $response['message'] = 'success';
                    }
                } else {
                    $answer_on = date('Y-m-d H:i:s');
                    $a_data = array(
                        'firm_id' => $firm_id,
                        'template_id' => $template_id,
                        'survey_respondent_id' => $employee_id,
                        'batch_id' => $batch_id,
                        'question_id' => $question_id,
                        'answer_id' => $option_now,
                        'answer_change' => $option_now,
                        'answer_change_weightage' => $weightage,
                        'created_date' => $answer_on
                    );

                    $query_insert = $this->employee_login_model->insert_employee_question_ans_transaction($a_data);

                    if ($query_insert) {
                        $response['message'] = 'success';
                    } else {
                        $response['message'] = 'success';
                    }
                }
            }
        }

        echo json_encode($response);
    }

    public function submit_survey() {
        $employee_id = $this->input->post('employee_id');
        $batch_id = $this->input->post('batch_id');
        $firm_id = $this->input->post('firm_id');
        $template_id = $this->input->post('template_id');
        $question_count = $this->input->post('question_count');

        for ($i = 0; $i <= $question_count; $i++) {
            if (isset($_POST['option_' . $i])) {
                $selected_option = $this->input->post('option_' . $i);
                $option_array = explode("^", $selected_option);
                $question_id = $option_array[0];
                $data = array(
                    'submit_survey' => 1
                );

                $where = array(
                    'firm_id' => $firm_id,
                    'survey_respondent_id' => $employee_id,
                    'batch_id' => $batch_id,
                    'question_id' => $question_id,
                );

                $query_updt_survey = $this->employee_login_model->updt_survey_submit($data, $where);
                if ($query_updt_survey !== false) {
                    $user_id = $employee_id;
                    $qry_user_udpt = $this->employee_login_model->updt_user_survey($user_id);
                    if ($qry_user_udpt == 1) {
                        $response['message'] = 'success';
                    } else {
                        $response['message'] = 'fail';
                    }
                    $response['message'] = 'success';
                } else {
                    $response['message'] = 'fail';
                }
            } else {

            }
        }
        echo json_encode($response);
    }

    public function survey111() {
        $data['prev_title'] = "Task List";
        $data['page_title'] = "Task List";
        $this->load->helper('url');
        $session_data = $this->session->userdata('login_session');
        $data['session_data'] = $session_data;
        $email_id = ($session_data['user_id']);
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
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
        }

        $query_get_survey_status = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        foreach ($query_get_survey_status->result() as $row) {
            $current_batch_id = $row->batch_id;
            // $current_batch_id_for_test=$row->batch_id;
            $survey_data['survey_data_all'][] = ['survey_status' => $row->survey_status, 'email' => $row->email, 'survey_reject_time' => $row->survey_reject_time, 'batch_id' => $row->batch_id];
        }

        if ($current_batch_id == '0') {
            $data['question_count_array'] = "";
            $data['option_count_array'] = "";
            $data['all_questions'] = "";
            $data['all_options'] = "";
            $data['hdn_user_id'] = "";
            $data['hdn_template_id'] = "";
            $data['hdn_batch_id'] = "";
            $data['my_survey_reject_time'] = "";

        } else {
            $current_batch_id_array = explode(',', $current_batch_id);
            $count_of_batch_ids = count($current_batch_id_array);
            if ($count_of_batch_ids === 1) {
                $survey_data['survey_data_all'] = "";
                $my_all_option_data['option_data_all_for_employee'][] = "";
                $my_all_question_data['question_data_all_for_employee'][] = "";
                $all_get_survey_reject_time['reject'][] = "";
                $template_data = "";
                $data['my_survey_data_all'] = "";

            } else {
                for ($z = 1; $z < $count_of_batch_ids; $z++) {
                    $query_get_template = $this->db->query("SELECT template_id,batch_name FROM `survey_batch_information_all` WHERE `batch_id`='$current_batch_id_array[$z]'");
                    foreach ($query_get_template->result() as $row) {
                        $template_data = $row->template_id;
                        $batch_name_for_display[] = $row->batch_name;
                    }

                    $query_get_template = $this->db->query("SELECT question_id,option_group_id FROM `template_header_all` WHERE `template_id`='$template_data'");
                    foreach ($query_get_template->result() as $row) {
                        $question_data = $row->question_id;
                        $option_data = $row->option_group_id;
                    }

                    $all_questions = explode(",", $question_data);
                    $question_count = count($all_questions);
                    for ($i = 0; $i < $question_count; $i++) {

                        $query_get_questions = $this->db->query("SELECT question_id,question FROM `question` WHERE `question_id`='$all_questions[$i]'");

                        foreach ($query_get_questions->result() as $row) {

                            $my_all_question_data['question_data_all_for_employee'][] = ['question' => $row->question, 'question_id' => $row->question_id];
                        }
                    }

                    $question_count_for_loop['question_ids_count'][] = $question_count;
                    $query_get_opetions = $this->db->query("SELECT option_id,option_name FROM `rating_scale_header_all` WHERE `option_group_id`='$option_data'");
                    foreach ($query_get_opetions->result() as $row) {
                        $option_ids_count = $row->option_id;
                        $my_all_option_data['option_data_all_for_employee'][] = ['option_name' => $row->option_name, 'option_id' => $row->option_id];
                    }

                    $count_of_options_data = $query_get_opetions->num_rows();
                    // $count_of_options_data = count($my_all_option_data['option_data_all_for_employee']);
                    $counter_of_all_option_data['option_ids_count'][] = $count_of_options_data;
                    $query_get_survey_reject_time = $this->db->query("select survey_reject_time from user_header_all WHERE `email`='$email_id'");
                    foreach ($query_get_survey_reject_time->result() as $row) {
                        $all_get_survey_reject_time['reject'][] = ['survey_reject_time' => $row->survey_reject_time];
                    }
                }
            }
            // $data['hdn_firm_id'] = $firm_id;
            $data['question_count_array'] = $question_count_for_loop['question_ids_count'];
            $data['option_count_array'] = $counter_of_all_option_data['option_ids_count'];
            $data['all_questions'] = $my_all_question_data['question_data_all_for_employee'];
            $data['all_options'] = $my_all_option_data['option_data_all_for_employee'];
            $data['hdn_user_id'] = $user_id;
            $data['hdn_template_id'] = $template_data;
            $data['hdn_batch_id'] = $current_batch_id;
            $data['my_survey_reject_time'] = $all_get_survey_reject_time['reject'];
            $data['array_of_batch_id'] = $current_batch_id_array;
            $data['page_title'] = "Employee dashboard";
            $data['my_survey_data_all'] = $survey_data['survey_data_all'];
            $data['prev_title'] = "Employee dashboard";
            $data['batch_name'] = $batch_name_for_display;
        }
        // $data['task_count'] = $task_count;
        // $data['due_date_counts'] = $due_date_counts;
        // $data['enquiry_count'] = $enquiry_count;
        $this->load->view('employee/Survey', $data);
    }

    public function survey() {
        $session_data = $this->session->userdata('login_session');
        $email_id = ($session_data['user_id']);
        $user_details = $this->db->select(array("survey_status", "batch_id", "survey_reject_time"))->where("email", $email_id)->get("user_header_all")->row();
        $survey_status_single = $user_details->survey_status;
        $survey_status_single_array = explode(",", $survey_status_single);
        $survey_status_array = explode(",", $user_details->survey_status);
        $survey_reject_time_array = explode(",", $user_details->survey_reject_time);
        $batch_id_array = explode(",", $user_details->survey_reject_time);
        $active_survey = array();
        $batch_id = array();
        $survey_status_single1 = array();
        for ($item = 0; $item < count($survey_status_array); $item++) {
            if ($survey_status_array[$item] != "") {
                $survey_status = explode(":", $survey_status_array[$item]);
                if ($survey_status[1] == "1") {
                    $survey_rejection = explode(":", $survey_reject_time_array[$item]);
                    array_push($batch_id, $batch_id_array[$item]);
                    array_push($survey_status_single1, $survey_status_single_array[$item]);
                    if ($survey_rejection[0] != "") {

                        array_push($active_survey, $survey_rejection);
                    }
                }
            }
        }

        if (count($active_survey) > 0) {
            $survey_array = array();
            foreach ($active_survey as $survey) {
                $survey_info = array();
                $survey_info["batch_info"] = $survey;
                $templete_info = $this->db->query("SELECT th.question_id,th.option_group_id ,sbi.template_id,sbi.batch_name FROM `template_header_all` th join `survey_batch_information_all` sbi  on th.template_id=sbi.template_id WHERE `batch_id`='" . $survey[0] . "'")->row();
                $survey_info["templet_info"] = $templete_info;
                $all_options = $this->db->select(array("option_id", "option_name"))->where("option_group_id", $templete_info->option_group_id)->get("rating_scale_header_all")->result();
                $survey_info["options"] = $all_options;
                $all_questions = explode(",", $templete_info->question_id);
                $question_array = array();
                foreach ($all_questions as $question_id) {
                    $question = $this->db->select(array("question_id", "question"))->where("question_id", $question_id)->get("question")->row();
                    array_push($question_array, $question);
                }
                $survey_info["questions"] = $question_array;
                array_push($survey_array, $survey_info);
            }
            $data["batch_id_for_save"] = $survey_status_single1;
            $data["batch_ids"] = $batch_id;
            $data["survey_count"] = count($active_survey);
            $data["survey_info"] = $survey_array;
        } else {
            $data["survey_count"] = 0;
            $data["survey_info"] = array();
        }
        echo json_encode($data);
    }

    //This funcion is for the save question and answer of the employesee survey
    function validate_question_and_answer() {
        $submit_survey = 1;
        $no_of_count = 0;
        $date = date('Y-m-d H:i:s');

        $check_staus = 0;
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

        $batch_id = $this->input->post("batch_id_for_check");
        $single_batch_id_array = explode(":", $batch_id);
        $single_batch_id = $single_batch_id_array[0];
        $reject_time_from_db = $single_batch_id_array[1];
        $counter_of_rejection = $single_batch_id_array[1];
        $counter_chnage_to = 2;

        $query_for_get_batch_for_select_reject_count = $this->db->query("select firm_id,user_id,survey_status from user_header_all where email='$email_id'");
        if ($query_for_get_batch_for_select_reject_count->num_rows() > 0) {
            foreach ($query_for_get_batch_for_select_reject_count->result() as $row) {
                $survey_reject_time = $row->survey_status;
                $firm_id = $row->firm_id;
                $user_id = $row->user_id;
            }
        } else {

        }

        $new_counter = $single_batch_id . ":" . $counter_chnage_to;
        $survey_reject_time_array = explode(",", $survey_reject_time);
        $index_of_match_batch_id = array_search($batch_id, $survey_reject_time_array);
        $survey_reject_time_array[$index_of_match_batch_id] = $new_counter;
        $new_string = implode(",", $survey_reject_time_array);

        $employee_question_and_answer = $this->input->post('question_answer_string');
        $counter_variable = $this->input->post('counter_for_check_filled_question');
        $template_id = $this->input->post('template_id');
        $batch_id_for_update_batch = substr($employee_question_and_answer, 0, 7);
        $batch_id_for_update_batch_status = $this->input->post('batch_id_for_check');
        $how_many_questions = $this->input->post('how_many_question');
        $array1 = explode(',', $employee_question_and_answer);
        $array2 = array();
        for ($i = 0; $i < count($array1); $i++) {
            $array2[] = explode(':', $array1[$i]);
            $array3[] = $array2[$i][0];
        }
        $array10 = array_unique($array3);
        $array4 = array_values($array10);
        for ($z = 0; $z < count($array4); $z++) {
            for ($x = 0; $x < count($array3); $x++) {
                if ($array4[$z] === $array3[$x]) {

                    $array5[$z] = $array1[$x];
                } else {

                }
            }
        }

        $batch_id_finial = $array5[0];
        array_splice($array5, 0, 1);
        array_values($array5);
        if ($how_many_questions == count($array5)) {
            for ($m = 0; $m < count($array5); $m++) {
                // $temp_batch_id= explode('->', $array5[$m]);
                $temp_question_id = explode('->', $array5[$m]);
                // for($n=0;$n<count($temp_question_id);$m++){
                //       $temp_option_id= explode(':', $temp_question_id[1]);
                // }
                $batch_id = $temp_question_id[0];
                $temp_option_id = explode(':', $temp_question_id[1]);
                $question_id = $temp_option_id[0];
                $option_id = $temp_option_id[1];

                $result3 = $this->db->query("insert into employee_question_answer_transaction(survey_respondent_id,firm_id,batch_id,template_id,question_id,answer_id,submit_survey,no_of_count,created_date)values('$user_id','$firm_id','$batch_id_finial','$template_id','$question_id','$option_id','$submit_survey','$no_of_count','$date')");
                if ($this->db->affected_rows() > 0) {
                    $check_staus++;
                } else {

                }
            }
        } else {
            $questions_remain = $how_many_questions - count($array5);
            $response['remain_questions'] = $questions_remain;
        }

        if ($check_staus === count($array5) && count($array5) != 0) {
            $data_for_update = ['survey_status' => $new_string];
            $where_data = ['email' => $email_id];
            $query_of_update_couter = $this->employee_login_model->updt_survey_rejection($data_for_update, $where_data);
            if ($query_of_update_couter == true) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {

        }
        echo json_encode($response);
    }

    public function reject_survey() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        if ($user_id === "") {
            $user_id = $this->session->userdata('login_session');
        }

        $batch_id = $this->input->post("reject_time");
        $single_batch_id_array = explode(":", $batch_id);
        $single_batch_id = $single_batch_id_array[0];
        $reject_time_from_db = $single_batch_id_array[1];
        $counter_of_rejection = $single_batch_id_array[1];
        $counter_chnage_to = $counter_of_rejection + 1;

        $query_for_get_batch_for_select_reject_count = $this->db->query("select survey_reject_time from user_header_all where email='$user_id'");
        if ($query_for_get_batch_for_select_reject_count->num_rows() > 0) {
            foreach ($query_for_get_batch_for_select_reject_count->result() as $row) {
                $survey_reject_time = $row->survey_reject_time;
            }
        } else {

        }

        $new_counter = $single_batch_id . ":" . $counter_chnage_to;
        $survey_reject_time_array = explode(",", $survey_reject_time);
        $index_of_match_batch_id = array_search($batch_id, $survey_reject_time_array);
        $survey_reject_time_array[$index_of_match_batch_id] = $new_counter;
        $new_string = implode(",", $survey_reject_time_array);
        $data_for_update = ['survey_reject_time' => $new_string];
        $where_data = ['email' => $user_id];
        $query_of_update_couter = $this->employee_login_model->updt_survey_rejection($data_for_update, $where_data);
        
        if ($query_of_update_couter == true) {
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
?>


