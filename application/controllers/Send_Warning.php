<?php

class Send_Warning extends CI_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('emp_model');
        $this->load->model('hr_model');
        $this->load->model('warning_modal');
        $this->load->model('email_sending_model');
    }

    public function index() {

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);
        //var_dump($result2);
        if ($result2 !== false) {
            $re = $result2->row();
            $firm_id = $re->firm_id;
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
            $login_user = $record->user_name;
            $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$user_id'");
            if ($result3->num_rows() > 0) {
                $record3 = $result3->row();
                $value_permit = $record->leave_approve_permission;
                $data['val'] = $value_permit;
            } else {
                $data['val'] = '';
            }
        }

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
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
        $data['prev_title'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['firm_id'] = $firm_id;

        $query_for_ftech_warning = $this->db->query("select * from warning_header_all where is_revocable='1'");

        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {
                $warning_name = $row->warning_name;
                $warning_id[] = $row->warning_id;
                $is_revocable = $row->is_revocable;
            }
        } else {
            $warning_id[] = "";
            $warning_name[] = "";
        }
        $data['table_data'] = $warning_name;

        $query_for_get_hr_firms = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($query_for_get_hr_firms->num_rows() > 0) {
            foreach ($query_for_get_hr_firms->result() as $row) {
                $hr_autority = $row->hr_authority;
            }
        }



        $hr_autority_array = explode(',', $hr_autority);
        $sql = "";
        for ($xy = 0; $xy < count($hr_autority_array); $xy++) {
            $sql .= "firm_id=" . "'" . $hr_autority_array[$xy] . "'" . " or ";
        }

        $sql_for_revocable = "";
        for ($xyz = 0; $xyz < count($warning_id); $xyz++) {
            $sql_for_revocable .= "warning_id=" . "'" . $warning_id[$xyz] . "'" . " or ";
        }

        $finial_sql_for_revocable = substr($sql_for_revocable, 0, -4);
        $finial_sql = substr($sql, 0, -4);
        $query_for_ftech_send_warning = $this->db->query("select * from send_warning_transaction where " . "(" . $finial_sql . ")" . "and (send_to<>'$user_id')");
        //        echo $this->db->last_query();
        //        exit;
        if ($query_for_ftech_send_warning->num_rows() > 0) {
            foreach ($query_for_ftech_send_warning->result() as $row) {
                $send_warning_id = $row->send_warning_id;
                $my_warning_id = $row->warning_id;
                $send_to = $row->send_to;
                $is_dispute = $row->is_dispute;
                $revert_requet = $row->revert_request;
                $request_activity = $row->request_activity;
                $revert_accepted = $row->revert_accepted;
                $sent_by = $row->send_by;
                $sent_description = $row->description;
                $accept_reason = $row->accept_reason;
                $decline_reason = $row->decline_reason;
                $dispute_reason = $row->dispute_reason;
                $dispute_attachment = $row->dispute_attachment;
                $revert_reason = $row->revert_reason;
                $cancel_revert_reason = $row->cancel_revert_reason;
                $dispute_accept_reason = $row->dispute_accept_reason;
                $dispute_accept_attachment = $row->dispute_accept_attachment;
                $dispute_decline_reason = $row->dispute_decline_reason;
                $dispute_decline_attachment = $row->dispute_decline_attachment;
                $revert_accepted_by_emp = $row->revert_accepted_by_emp;
                $query_for_ftech_warning_name = $this->db->query("select * from warning_header_all where warning_id='$my_warning_id'");
                if ($query_for_ftech_warning_name->num_rows() > 0) {

                    $res1 = $query_for_ftech_warning_name->row();
                    $warning_name = $res1->warning_name;
                    $attachment = $res1->attachment;
                    $is_revocable = $res1->is_revocable;
                    $description = $res1->description;
                } else {

                }
                $query_for_ftech_user_name = $this->db->query("select user_name,user_type from user_header_all where user_id='$send_to'");
                if ($query_for_ftech_user_name->num_rows() > 0) {
                    $res2 = $query_for_ftech_user_name->row();
                    //$warning_name = $res2->warning_name;
                    $user_name = $res2->user_name;
                    $user_type_of_sent_to = $res2->user_type;
                } else {

                }
                $query_for_ftech_user_name_sent_by = $this->db->query("select user_name,user_type from user_header_all where user_id='$sent_by'");
                if ($query_for_ftech_user_name_sent_by->num_rows() > 0) {
                    $res3 = $query_for_ftech_user_name_sent_by->row();
                    //$warning_name = $res2->warning_name;
                    $user_name_sent_by = $res3->user_name;
                    $user_type = $res3->user_type;
                } else {

                }
                $warning_name_data[] = ['user_type_sent_to' => $user_type_of_sent_to, 'revert_accepted_by_emp' => $revert_accepted_by_emp, 'login_user' => $login_user, 'dispute_accept_reason' => $dispute_accept_reason, 'dispute_accept_attachment' => $dispute_accept_attachment, 'dispute_decline_reason' => $dispute_decline_reason, 'dispute_decline_attachment' => $dispute_decline_attachment, 'send_warning_id' => $send_warning_id, 'warning_name' => $warning_name, 'attachment' => $attachment, 'description' => $description, 'user_name' => $user_name, 'revert_request' => $revert_requet, 'request_activity' => $request_activity, 'revert_accepted' => $revert_accepted, 'sent_by' => $user_name_sent_by, 'accept_reason' => $accept_reason, 'decline_reason' => $decline_reason, 'revert_reason' => $revert_reason, 'cancel_revert_reason' => $cancel_revert_reason, 'sent_description' => $sent_description, 'is_revocable' => $is_revocable, 'user_type' => $user_type, 'is_dispute' => $is_dispute, 'dispute_reason' => $dispute_reason, 'dispute_attachment' => $dispute_attachment];
            }
        } else {
            $warning_name_data = "";
        }
        $data['card_data'] = $warning_name_data;

        $query_for_update_request_activity = $this->db->query("update send_warning_transaction set request_activity='1' where revert_accepted='0' and revert_request='1'");
        $this->load->view('human_resource/send_warning', $data);
    }

    public function hq_admin_dashboard_send_warning() {

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);
        //var_dump($result2);
        if ($result2 !== false) {
            $re = $result2->row();
            $firm_id = $re->firm_id;
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

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
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
        $data['prev_title'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['firm_id'] = $firm_id;

        $query_for_ftech_warning = $this->db->query("select * from warning_header_all");

        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {

                $card_color = $row->card_color;

                $warning_name[] = ['warning_name' => $row->warning_name, 'attachment' => $row->attachment];
            }
        } else {
            $warning_name[] = "";
        }
        $data['table_data'] = $warning_name;

        $query_for_ftech_send_warning = $this->db->query("select * from send_warning_transaction where send_by='$user_id'");
        if ($query_for_ftech_send_warning->num_rows() > 0) {
            foreach ($query_for_ftech_send_warning->result() as $row) {
                $send_warning_id = $row->send_warning_id;
                $warning_id = $row->warning_id;
                $send_to = $row->send_to;
                $is_dispute = $row->is_dispute;
                $revert_requet = $row->revert_request;
                $request_activity = $row->request_activity;
                $revert_accepted = $row->revert_accepted;
                $sent_description = $row->description;
                $accept_reason = $row->accept_reason;
                $decline_reason = $row->decline_reason;
                $revert_accepted_by_emp = $row->revert_accepted_by_emp;
                $revert_reason = $row->revert_reason;
                $cancel_revert_reason = $row->cancel_revert_reason;
                $dispute_attachment = $row->dispute_attachment;
                $dispute_reason = $row->dispute_reason;
                $dispute_accept_reason = $row->dispute_accept_reason;
                $dispute_accept_attachment = $row->dispute_accept_attachment;
                $dispute_decline_reason = $row->dispute_decline_reason;
                $dispute_decline_attachment = $row->dispute_decline_attachment;
                $query_for_ftech_warning_name = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
                if ($query_for_ftech_warning_name->num_rows() > 0) {

                    $res1 = $query_for_ftech_warning_name->row();

                    $attachment = $res1->attachment;
                    $warning_name = $res1->warning_name;
                    $description = $res1->description;
                    //                    $send_to=$res->send_to;
                    //$card_color = $res1->card_color;
                } else {

                }
                $query_for_ftech_user_name = $this->db->query("select user_name,user_type from user_header_all where user_id='$send_to'");
                if ($query_for_ftech_user_name->num_rows() > 0) {
                    $res2 = $query_for_ftech_user_name->row();
                    //$warning_name = $res2->warning_name;
                    $user_name = $res2->user_name;
                    $user_type = $res2->user_type;
                } else {

                }
                $warning_name_data[] = ['dispute_attachment' => $dispute_attachment, 'dispute_reason' => $dispute_reason, 'is_dispute' => $is_dispute, 'dispute_accept_reason' => $dispute_accept_reason, 'dispute_accept_attachment' => $dispute_accept_attachment, 'dispute_decline_reason' => $dispute_decline_reason, 'dispute_decline_attachment' => $dispute_decline_attachment, 'send_warning_id' => $send_warning_id, 'warning_name' => $warning_name, 'attachment' => $attachment, 'description' => $description, 'user_name' => $user_name, 'revert_request' => $revert_requet, 'request_activity' => $request_activity, 'revert_accepted' => $revert_accepted, 'sent_description' => $sent_description, 'accept_reason' => $accept_reason, 'decline_reason' => $decline_reason, 'revert_reason' => $revert_reason, 'cancel_revert_reason' => $cancel_revert_reason, 'revert_accepted_by_emp' => $revert_accepted_by_emp, 'user_type' => $user_type];
            }
        } else {
            $warning_name_data = "";
        }
        $data['card_data'] = $warning_name_data;
        $query_for_update_request_activity = $this->db->query("update send_warning_transaction set request_activity='1' where revert_accepted='0' and revert_request='1'");
        $this->load->view('hq_admin/send_warning_hq', $data);
    }

    //received dashboard of hr
    public function received_dashboard() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);
        //var_dump($result2);
        if ($result2 !== false) {
            $re = $result2->row();
            $firm_id = $re->firm_id;
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
        $query_to_fetcth_event_date = $this->db->query("SELECT `create_at`,`end_date`,`holiday_id` FROM `holiday_header_all` where   FIND_IN_SET(`holiday_applied_in`, '$firm_id')");
        if ($query_to_fetcth_event_date->num_rows() > 0) {

            $record_event = $query_to_fetcth_event_date->result();
            foreach ($record_event as $row) {
                $start_date = $row->start_date;
                $end_date = $row->end_date;
                $today_date = date("Y/m/d");
                //                $query_event = $this->db->query("SELECT * FROM `holiday_management_all` WHERE `start_date` >= '$start_date' AND `end_date` <= '$end_date' AND `firm_id`='$firm_id'");
                $query_event = $this->db->query("SELECT * from holiday_header_all where
					(create_at BETWEEN '$start_date' AND '$end_date') OR
					(end_date BETWEEN '$start_date' AND '$end_date') OR
					(create_at <= '$start_date' AND end_date >= '$end_date') AND  FIND_IN_SET(`holiday_applied_in`, '$firm_id')");


                $data = array('event_id' => array());
                if ($query_event->num_rows() > 0) {
                    $record_data = $query_event->result();
                    $data['result'] = $record_data;
                    //            var_dump($result);
                } else {
                    $data['result'] = "";
                }
            }
        } else {
            $data['result'] = "";
        }
        $query_event = $this->db->query("SELECT * from holiday_header_all where FIND_IN_SET(`holiday_applied_in`, '$firm_id')");
        if ($query_event->num_rows() > 0) {
            $record_data = $query_event->result();
            $data['result_event'] = $record_data;
            //            var_dump($result);
        } else {
            $data['result_event'] = "";
        }


        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
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
        $data['prev_title'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['firm_id'] = $firm_id;

        $query_for_ftech_send_warning = $this->db->query("select * from send_warning_transaction where send_to='$user_id'");
        if ($query_for_ftech_send_warning->num_rows() > 0) {
            foreach ($query_for_ftech_send_warning->result() as $row) {
                $warning_id = $row->warning_id;
                $send_warning_id = $row->send_warning_id;
                $send_to = $row->send_to;
                $revert_requet = $row->revert_request;
                $request_activity = $row->request_activity;
                $revert_accepted = $row->revert_accepted;
                $sent_by = $row->send_by;
                $description_of_send = $row->description;
                $accept_reason = $row->accept_reason;
                $decline_reason = $row->decline_reason;
                $revert_accepted_by_emp = $row->revert_accepted_by_emp;
                $revert_reason = $row->revert_reason;
                $cancel_revert_reason = $row->cancel_revert_reason;

                $query_for_ftech_warning_name = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
                if ($query_for_ftech_warning_name->num_rows() > 0) {

                    $res1 = $query_for_ftech_warning_name->row();
                    $warning_name = $res1->warning_name;
                    $attachment = $res1->attachment;
                    $description = $res1->description;
                    $is_revocable = $res1->is_revocable;
                    //                    $send_to=$res->send_to;
                    //$card_color = $res1->card_color;
                } else {

                }
                $query_for_ftech_user_name = $this->db->query("select user_name from user_header_all where user_id='$send_to'");
                if ($query_for_ftech_user_name->num_rows() > 0) {
                    $res2 = $query_for_ftech_user_name->row();
                    //$warning_name = $res2->warning_name;
                    $user_name = $res2->user_name;
                } else {

                }

                $query_for_ftech_user_name_sent_by = $this->db->query("select user_name from user_header_all where user_id='$sent_by'");
                if ($query_for_ftech_user_name_sent_by->num_rows() > 0) {
                    $res3 = $query_for_ftech_user_name_sent_by->row();
                    //$warning_name = $res2->warning_name;
                    $user_name_sent_by = $res3->user_name;
                } else {

                }

                $warning_name_data[] = ['send_warning_id' => $send_warning_id, 'warning_name' => $warning_name, 'attachment' => $attachment, 'description' => $description, 'user_name' => $user_name, 'revert_request' => $revert_requet, 'is_revocable' => $is_revocable, 'request_activity' => $request_activity, 'revert_accepted' => $revert_accepted, 'sent_by' => $user_name_sent_by, 'sent_description' => $description_of_send, 'accept_reason' => $accept_reason, 'decline_reason' => $decline_reason, 'revert_reason' => $revert_reason, 'cancel_revert_reason' => $cancel_revert_reason, 'revert_accepted_by_emp' => $revert_accepted_by_emp];
            }
        } else {
            $warning_name_data = "";
        }
        $data['card_data'] = $warning_name_data;



        $this->load->view('human_resource/received_warning', $data);
    }

    //This function is for the load send warning dashboard in employee module
    public function employee_dashboard_send_warning() {

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);
        //var_dump($result2);
        if ($result2 !== false) {
            $re = $result2->row();
            $firm_id = $re->firm_id;
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

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
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
        $data['prev_title'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['firm_id'] = $firm_id;

        $query_for_ftech_warning = $this->db->query("select * from warning_header_all");

        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {

                $warning_name[] = ['warning_name' => $row->warning_name, 'attachment' => $row->attachment, 'card_color' => $row->card_color];
            }
        } else {
            $warning_name[] = "";
        }
        $data['table_data'] = $warning_name;

        $query_for_ftech_send_warning = $this->db->query("select * from send_warning_transaction where send_by='$user_id'");
        if ($query_for_ftech_send_warning->num_rows() > 0) {
            foreach ($query_for_ftech_send_warning->result() as $row) {
                $send_warning_id = $row->send_warning_id;
                $warning_id = $row->warning_id;
                $send_to = $row->send_to;
                $revert_requet = $row->revert_request;
                $request_activity = $row->request_activity;
                $revert_accepted = $row->revert_accepted;
                $sent_description = $row->description;
                $accept_reason = $row->accept_reason;
                $decline_reason = $row->decline_reason;
                $revert_accepted_by_emp = $row->revert_accepted_by_emp;
                $is_dispute = $row->is_dispute;
                $revert_reason = $row->revert_reason;
                $cancel_revert_reason = $row->cancel_revert_reason;
                $revert_reason = $row->revert_reason;
                $dispute_attachment = $row->dispute_attachment;
                $dispute_reason = $row->dispute_reason;
                $cancel_revert_reason = $row->cancel_revert_reason;
                $dispute_accept_reason = $row->dispute_accept_reason;
                $dispute_accept_attachment = $row->dispute_accept_attachment;
                $dispute_decline_reason = $row->dispute_decline_reason;
                $dispute_decline_attachment = $row->dispute_decline_attachment;
                $query_for_ftech_warning_name = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
                if ($query_for_ftech_warning_name->num_rows() > 0) {
                    $res1 = $query_for_ftech_warning_name->row();
                    $warning_name = $res1->warning_name;
                    $attachment = $res1->attachment;

                    $description = $res1->description;
                    $is_revocable = $res1->is_revocable;
                } else {

                }

                $query_for_ftech_user_name = $this->db->query("select user_name,user_type from user_header_all where user_id='$send_to'");
                if ($query_for_ftech_user_name->num_rows() > 0) {
                    $res2 = $query_for_ftech_user_name->row();
                    //$warning_name = $res2->warning_name;
                    $user_name = $res2->user_name;
                    $user_type = $res2->user_type;
                } else {

                }
                $warning_name_data[] = ['dispute_attachment' => $dispute_attachment, 'dispute_reason' => $dispute_reason, 'is_dispute' => $is_dispute, 'dispute_accept_reason' => $dispute_accept_reason, 'dispute_accept_attachment' => $dispute_accept_attachment, 'dispute_decline_reason' => $dispute_decline_reason, 'dispute_decline_attachment' => $dispute_decline_attachment, 'send_warning_id' => $send_warning_id, 'warning_name' => $warning_name, 'attachment' => $attachment, 'description' => $description, 'user_name' => $user_name, 'revert_request' => $revert_requet, 'request_activity' => $request_activity, 'revert_accepted' => $revert_accepted, 'sent_description' => $sent_description, 'accept_reason' => $accept_reason, 'decline_reason' => $decline_reason, 'revert_reason' => $revert_reason, 'cancel_revert_reason' => $cancel_revert_reason, 'revert_accepted_by_emp' => $revert_accepted_by_emp, 'is_revocable' => $is_revocable, 'user_type' => $user_type];
            }
        } else {
            $warning_name_data = "";
        }
        $data['card_data'] = $warning_name_data;

        $query_for_update_request_activity = $this->db->query("update send_warning_transaction set request_activity='1' where revert_accepted='0' and revert_request='1' and send_by='$user_id'");
        $this->load->view('employee/send_warning', $data);
    }

    //this is dashboard of the employee received warningss
    public function received_dashboard_employee() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);
        //var_dump($result2);
        if ($result2 !== false) {
            $re = $result2->row();
            $firm_id = $re->firm_id;
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
            $login_user = $record->user_name;
            $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$user_id'");
            if ($result3->num_rows() > 0) {
                $record3 = $result3->row();
                $value_permit = $record->leave_approve_permission;
                $data['val'] = $value_permit;
            } else {
                $data['val'] = '';
            }
        }

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
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
        $data['prev_title'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['firm_id'] = $firm_id;

        $query_for_ftech_send_warning = $this->db->query("select * from send_warning_transaction where send_to='$user_id'");
        if ($query_for_ftech_send_warning->num_rows() > 0) {
            foreach ($query_for_ftech_send_warning->result() as $row) {
                $warning_id = $row->warning_id;
                $send_warning_id = $row->send_warning_id;
                $send_to = $row->send_to;
                $revert_requet = $row->revert_request;
                $request_activity = $row->request_activity;
                $revert_accepted = $row->revert_accepted;
                $sent_by = $row->send_by;
                $description_of_send = $row->description;
                $accept_reason = $row->accept_reason;
                $decline_reason = $row->decline_reason;
                $revert_reason = $row->revert_reason;
                $cancel_revert_reason = $row->cancel_revert_reason;
                $revert_accepted_by_emp = $row->revert_accepted_by_emp;
                $is_dispute = $row->is_dispute;
                $dispute_reason = $row->dispute_reason;
                $dispute_attachment = $row->dispute_attachment;
                $dispute_accept_reason = $row->dispute_accept_reason;
                $dispute_accept_attachment = $row->dispute_accept_attachment;
                $dispute_decline_reason = $row->dispute_decline_reason;
                $dispute_decline_attachment = $row->dispute_decline_attachment;
                $query_for_ftech_warning_name = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
                if ($query_for_ftech_warning_name->num_rows() > 0) {

                    $res1 = $query_for_ftech_warning_name->row();
                    $warning_name = $res1->warning_name;
                    $attachment = $res1->attachment;

                    $description = $res1->description;
                    $is_revocable = $res1->is_revocable;
                    //                    $send_to=$res->send_to;
                    //$card_color = $res1->card_color;
                } else {

                }
                $query_for_ftech_user_name = $this->db->query("select user_name from user_header_all where user_id='$send_to'");
                if ($query_for_ftech_user_name->num_rows() > 0) {
                    $res2 = $query_for_ftech_user_name->row();
                    //$warning_name = $res2->warning_name;
                    $user_name = $res2->user_name;
                } else {

                }
                $query_for_ftech_user_name_sent_by = $this->db->query("select user_name,user_type from user_header_all where user_id='$sent_by'");
                if ($query_for_ftech_user_name_sent_by->num_rows() > 0) {
                    $res3 = $query_for_ftech_user_name_sent_by->row();
                    //$warning_name = $res2->warning_name;
                    $user_name_sent_by = $res3->user_name;
                    $user_type = $res3->user_type;
                } else {

                }
                $warning_name_data[] = ['is_dispute' => $is_dispute, 'dispute_reason' => $dispute_reason, 'dispute_attachment' => $dispute_attachment, 'login_user' => $login_user, 'dispute_accept_reason' => $dispute_accept_reason, 'dispute_accept_attachment' => $dispute_accept_attachment, 'dispute_decline_reason' => $dispute_decline_reason, 'dispute_decline_attachment' => $dispute_decline_attachment, 'send_warning_id' => $send_warning_id, 'warning_name' => $warning_name, 'attachment' => $attachment, 'description' => $description, 'user_name' => $user_name, 'revert_request' => $revert_requet, 'is_revocable' => $is_revocable, 'request_activity' => $request_activity, 'revert_accepted' => $revert_accepted, 'sent_by' => $user_name_sent_by, 'sent_description' => $description_of_send, 'accept_reason' => $accept_reason, 'decline_reason' => $decline_reason, 'revert_reason' => $revert_reason, 'cancel_revert_reason' => $cancel_revert_reason, 'revert_accepted_by_emp' => $revert_accepted_by_emp, 'user_type' => $user_type];
            }
        } else {
            $warning_name_data = "";
        }
        $data['card_data'] = $warning_name_data;



        $this->load->view('employee/received_warning', $data);
    }

    //this function is for the load  send_warning dashboard of client admin
    public function send_dashboard_ca() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);
        //var_dump($result2);
        if ($result2 !== false) {
            $re = $result2->row();
            $firm_id = $re->firm_id;
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

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
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
        $data['prev_title'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['firm_id'] = $firm_id;

        $query_for_ftech_warning = $this->db->query("select * from warning_header_all");

        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {

                $warning_name[] = ['warning_name' => $row->warning_name, 'attachment' => $row->attachment, 'card_color' => $row->card_color];
            }
        } else {
            $warning_name[] = "";
        }
        $data['table_data'] = $warning_name;

        $query_for_ftech_send_warning = $this->db->query("select * from send_warning_transaction where send_by='$user_id'");
        if ($query_for_ftech_send_warning->num_rows() > 0) {
            foreach ($query_for_ftech_send_warning->result() as $row) {
                $send_warning_id = $row->send_warning_id;
                $warning_id = $row->warning_id;
                $send_to = $row->send_to;
                $is_dispute = $row->is_dispute;
                $revert_requet = $row->revert_request;
                $request_activity = $row->request_activity;
                $revert_accepted = $row->revert_accepted;
                $sent_description = $row->description;
                $accept_reason = $row->accept_reason;
                $decline_reason = $row->decline_reason;
                $revert_accepted_by_emp = $row->revert_accepted_by_emp;
                $revert_reason = $row->revert_reason;
                $dispute_reason = $row->dispute_reason;
                $dispute_attachment = $row->dispute_attachment;
                $dispute_accept_reason = $row->dispute_accept_reason;
                $dispute_accept_attachment = $row->dispute_accept_attachment;
                $dispute_decline_reason = $row->dispute_decline_reason;
                $dispute_decline_attachment = $row->dispute_decline_attachment;
                $cancel_revert_reason = $row->cancel_revert_reason;
                $query_for_ftech_warning_name = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
                if ($query_for_ftech_warning_name->num_rows() > 0) {
                    $res1 = $query_for_ftech_warning_name->row();
                    $warning_name = $res1->warning_name;
                    $attachment = $res1->attachment;

                    $description = $res1->description;
                    $is_revocable = $res1->is_revocable;
                } else {

                }

                $query_for_ftech_user_name = $this->db->query("select user_name,user_type from user_header_all where user_id='$send_to'");
                if ($query_for_ftech_user_name->num_rows() > 0) {
                    $res2 = $query_for_ftech_user_name->row();
                    //$warning_name = $res2->warning_name;
                    $user_name = $res2->user_name;
                    $user_type = $res2->user_type;
                } else {

                }
                $warning_name_data[] = ['is_dispute' => $is_dispute, 'dispute_reason' => $dispute_reason, 'dispute_attachment' => $dispute_attachment, 'dispute_accept_reason' => $dispute_accept_reason, 'dispute_accept_attachment' => $dispute_accept_attachment, 'dispute_decline_reason' => $dispute_decline_reason, 'dispute_decline_attachment' => $dispute_decline_attachment, 'send_warning_id' => $send_warning_id, 'warning_name' => $warning_name, 'attachment' => $attachment, 'description' => $description, 'user_name' => $user_name, 'revert_request' => $revert_requet, 'request_activity' => $request_activity, 'revert_accepted' => $revert_accepted, 'sent_description' => $sent_description, 'accept_reason' => $accept_reason, 'decline_reason' => $decline_reason, 'revert_reason' => $revert_reason, 'cancel_revert_reason' => $cancel_revert_reason, 'revert_accepted_by_emp' => $revert_accepted_by_emp, 'is_revocable' => $is_revocable, 'user_type' => $user_type];
            }
        } else {
            $warning_name_data = "";
        }
        $data['card_data'] = $warning_name_data;

        $query_for_update_request_activity = $this->db->query("update send_warning_transaction set request_activity='1' where revert_accepted='0' and revert_request='1' and send_by='$user_id'");
        $this->load->view('client_admin/send_warning_ca', $data);
    }

    //this function is for the load  send_warning dashboard of client admin
    public function received_dashboard_ca() {

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);
        //var_dump($result2);
        if ($result2 !== false) {
            $re = $result2->row();
            $firm_id = $re->firm_id;
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

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
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
        $data['prev_title'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['firm_id'] = $firm_id;

        $query_for_ftech_send_warning = $this->db->query("select * from send_warning_transaction where send_to='$user_id'");
        if ($query_for_ftech_send_warning->num_rows() > 0) {
            foreach ($query_for_ftech_send_warning->result() as $row) {
                $warning_id = $row->warning_id;
                $send_warning_id = $row->send_warning_id;
                $send_to = $row->send_to;
                $revert_requet = $row->revert_request;
                $request_activity = $row->request_activity;
                $revert_accepted = $row->revert_accepted;
                $sent_by = $row->send_by;
                $description_of_send = $row->description;
                $accept_reason = $row->accept_reason;
                $decline_reason = $row->decline_reason;
                $revert_reason = $row->revert_reason;
                $cancel_revert_reason = $row->cancel_revert_reason;
                $revert_accepted_by_emp = $row->revert_accepted_by_emp;
                $query_for_ftech_warning_name = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
                if ($query_for_ftech_warning_name->num_rows() > 0) {

                    $res1 = $query_for_ftech_warning_name->row();
                    $warning_name = $res1->warning_name;
                    $attachment = $res1->attachment;

                    $description = $res1->description;
                    $is_revocable = $res1->is_revocable;
                    //                    $send_to=$res->send_to;
                    //$card_color = $res1->card_color;
                } else {

                }
                $query_for_ftech_user_name = $this->db->query("select user_name from user_header_all where user_id='$send_to'");
                if ($query_for_ftech_user_name->num_rows() > 0) {
                    $res2 = $query_for_ftech_user_name->row();
                    //$warning_name = $res2->warning_name;
                    $user_name = $res2->user_name;
                } else {

                }
                $query_for_ftech_user_name_sent_by = $this->db->query("select user_name,user_type from user_header_all where user_id='$sent_by'");
                if ($query_for_ftech_user_name_sent_by->num_rows() > 0) {
                    $res3 = $query_for_ftech_user_name_sent_by->row();
                    //$warning_name = $res2->warning_name;
                    $user_name_sent_by = $res3->user_name;
                    $user_type = $res3->user_type;
                } else {

                }
                $warning_name_data[] = ['send_warning_id' => $send_warning_id, 'warning_name' => $warning_name, 'attachment' => $attachment, 'description' => $description, 'user_name' => $user_name, 'revert_request' => $revert_requet, 'is_revocable' => $is_revocable, 'request_activity' => $request_activity, 'revert_accepted' => $revert_accepted, 'sent_by' => $user_name_sent_by, 'sent_description' => $description_of_send, 'accept_reason' => $accept_reason, 'decline_reason' => $decline_reason, 'revert_reason' => $revert_reason, 'cancel_revert_reason' => $cancel_revert_reason, 'revert_accepted_by_emp' => $revert_accepted_by_emp, 'user_type' => $user_type];
            }
        } else {
            $warning_name_data = "";
        }
        $data['card_data'] = $warning_name_data;
        $this->load->view('client_admin/received_warning_ca', $data);
    }

    //this function is for the accept warning
    public function accept_warning() {
        $warning_id = $this->input->post('warning_id');

        $where_data = array('send_warning_id' => $warning_id);
        $data = array('revert_accepted_by_emp' => 1);
        $query_for_accept_warning = $this->warning_modal->update_accept_warning($data, $where_data);
        if ($query_for_accept_warning == true) {
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

    //this funcion is for the accept the warning revert for hq-admin
    public function acc_revert_for_hq() {
        $accept_description = $this->input->post('acc_desc');
        $send_warning_id = $this->input->post('warning_id');
        $where_con = array('send_warning_id' => $send_warning_id);
        $data = array('accept_reason' => $accept_description, 'revert_accepted' => 1);
        if (empty(trim($accept_description))) {
            $response['id'] = 'accept_description';
            $response['error'] = 'Enter Reason for Accept this Warning';
        } else {
            $qry_accept_revert = $this->warning_modal->update_accept_revert($data, $where_con);

            if ($qry_accept_revert === true) {
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

    //this function is for the accpect description of revert and chnage the status of it
    public function acc_revert() {
        $accept_description = $this->input->post('description');
        $send_warning_id = $this->input->post('warning_id');
        $where_con = array('send_warning_id' => $send_warning_id);
        $data = array('accept_reason' => $accept_description, 'revert_accepted' => 1);
        if (empty(trim($accept_description))) {
            $response['id'] = 'accept_description_for_accept_revocke_warning';
            $response['error'] = 'Enter Reason for Accept this Warning';
        } else {
            $qry_accept_revert = $this->warning_modal->update_accept_revert($data, $where_con);

            if ($qry_accept_revert === true) {
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

    //this function is for the decline request in hq-adm,in model
    public function dec_revert_hq_admin() {
        $decline_description = $this->input->post('dec_desc');
        $send_warning_id = $this->input->post('warning_id');
        $where_con = array('send_warning_id' => $send_warning_id);
        if (empty(trim($decline_description))) {
            $response['id'] = 'decline_description';
            $response['error'] = 'Enter Reason for Decline this Warning';
        } else {
            $data = array('decline_reason' => $decline_description, 'revert_accepted' => 2);
            $qry_accept_revert = $this->warning_modal->update_accept_revert($data, $where_con);
            if ($qry_accept_revert === true) {
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

    //this function is for the decline the description of revert and chnage the status of it
    public function dec_revert() {
        $decline_description = $this->input->post('description');
        $send_warning_id = $this->input->post('warning_id');
        $where_con = array('send_warning_id' => $send_warning_id);
        if (empty(trim($decline_description))) {
            $response['id'] = 'decline_description_revocke';
            $response['error'] = 'Enter Reason for Decline this Warning';
        } else {
            $data = array('decline_reason' => $decline_description, 'revert_accepted' => 2);
            $qry_accept_revert = $this->warning_modal->update_accept_revert($data, $where_con);
            if ($qry_accept_revert === true) {
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

    //this function is for the accpect description of revert and chnage the status of it
    public function send_revert() {
        $accept_description = $this->input->post('acc_desc');
        $send_warning_id = $this->input->post('warning_id');
        $where_con = array('send_warning_id' => $send_warning_id);
        $data = array('revert_reason' => $accept_description, 'cancel_revert_reason' => "", 'revert_request' => 1);
        $qry_accept_revert = $this->warning_modal->update_send_revert($data, $where_con);
        if ($qry_accept_revert === true) {
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

    //this function is for the make dispute from employee side
    public function make_dispute() {
        $uplod_name = "";
        $accept_description = $this->input->post('accept_description');
        $send_warning_id = $this->input->post('txt_send_warnind_id');
        $attachment = $this->input->post('dispute_attachment');

        if (empty($accept_description)) {
            $response['id'] = 'accept_description';
            $response['error'] = 'Give Valid resone of Dispute in Details!';
        } else {
            if (isset($_FILES['dispute_attachment']) && $_FILES['dispute_attachment']['error'] != '4') {

                $all_files = $_FILES['dispute_attachment']['name'];
                $type_of_file[] = pathinfo($all_files, PATHINFO_EXTENSION);
                $allowed = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'xls', 'xlsx', 'doc');
                $valid_file_type_result = array_diff($type_of_file, $allowed);
                $count_of_valid_file_type_result = count($valid_file_type_result);
                if ($count_of_valid_file_type_result <= 0) {
                    $uploadPath = './uploads/warning_document/';
                    if (file_exists('./uploads/warning_document/' . $all_files)) {
                        $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['dispute_attachment']['name']));
                        move_uploaded_file($_FILES["dispute_attachment"]["tmp_name"], './uploads/warning_document/' . $newfilename);
                        $uplod_name = $newfilename;
                    } else {
                        $tmp_name = $_FILES['dispute_attachment']['tmp_name'];
                        move_uploaded_file($tmp_name, $uploadPath . $_FILES['dispute_attachment']['name']);
                        $uplod_name = $_FILES['dispute_attachment']['name'];
                    }
                } else {
                    $response['id'] = 'attachment';
                    $response['error'] = 'Select Valid File';
                }
            }
            $where_con = array('send_warning_id' => $send_warning_id);
            $data = array('dispute_reason' => $accept_description, 'dispute_attachment' => $uplod_name, 'is_dispute' => 1, 'revert_request' => 1);
            $qry_accept_revert = $this->warning_modal->update_send_revert($data, $where_con);
            if ($qry_accept_revert === true) {
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

    //this fucntion is for the accept dispute revert from employee
    public function acc_dispute() {
        $uplod_name = "";
        $accept_description = $this->input->post('accept_description');
        $send_warning_id = $this->input->post('txt_send_warninf_id');
        $attachment = $this->input->post('accept_dispute_attachment');
        if (empty($accept_description)) {
            $response['id'] = 'accept_description';
            $response['error'] = 'Give Valid resone of Dispute in Details!';
        } else {
            if (isset($_FILES['accept_dispute_attachment']) && $_FILES['accept_dispute_attachment']['error'] != '4') {

                $all_files = $_FILES['accept_dispute_attachment']['name'];
                $type_of_file[] = pathinfo($all_files, PATHINFO_EXTENSION);
                $allowed = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'xls', 'xlsx', 'doc');
                $valid_file_type_result = array_diff($type_of_file, $allowed);
                $count_of_valid_file_type_result = count($valid_file_type_result);
                if ($count_of_valid_file_type_result <= 0) {
                    $uploadPath = './uploads/warning_document/';
                    if (file_exists('./uploads/warning_document/' . $all_files)) {
                        $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['accept_dispute_attachment']['name']));
                        move_uploaded_file($_FILES["accept_dispute_attachment"]["tmp_name"], './uploads/warning_document/' . $newfilename);
                        $uplod_name = $newfilename;
                    } else {
                        $tmp_name = $_FILES['accept_dispute_attachment']['tmp_name'];
                        move_uploaded_file($tmp_name, $uploadPath . $_FILES['accept_dispute_attachment']['name']);
                        $uplod_name = $_FILES['accept_dispute_attachment']['name'];
                    }
                } else {
                    $response['id'] = 'attachment';
                    $response['error'] = 'Select Valid File';
                }
            }

            $where_con = array('send_warning_id' => $send_warning_id);
            $data = array('dispute_accept_reason' => $accept_description, 'dispute_accept_attachment' => $uplod_name, 'revert_accepted' => 1);
            $qry_accept_revert = $this->warning_modal->update_send_revert($data, $where_con);

            if ($qry_accept_revert === true) {
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

    //this function is for the decline the dispute revert request of employees by hr
    public function dec_dispute() {
        $uplod_name = "";
        $decline_description = $this->input->post('decline_description');
        $send_warning_id = $this->input->post('txt_send_warning_id_decline');
        $attachment = $this->input->post('decline_dispute_attachment');
        if (empty($decline_description)) {
            $response['id'] = 'accept_description';
            $response['error'] = 'Give Valid resone of Dispute in Details!';
        } else {
            if (isset($_FILES['decline_dispute_attachment']) && $_FILES['decline_dispute_attachment']['error'] != '4') {
                $filesCount = count($_FILES['decline_dispute_attachment']['name']);
                $all_files = $_FILES['decline_dispute_attachment']['name'];
                $type_of_file[] = pathinfo($all_files, PATHINFO_EXTENSION);
                $allowed = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'xls', 'xlsx', 'doc');
                $valid_file_type_result = array_diff($type_of_file, $allowed);
                $count_of_valid_file_type_result = count($valid_file_type_result);
                if ($count_of_valid_file_type_result <= 0) {
                    $uploadPath = './uploads/warning_document/';
                    if (file_exists('./uploads/warning_document/' . $all_files)) {
                        $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['decline_dispute_attachment']['name']));
                        move_uploaded_file($_FILES["decline_dispute_attachment"]["tmp_name"], './uploads/warning_document/' . $newfilename);
                        $uplod_name = $newfilename;
                    } else {
                        $tmp_name = $_FILES['decline_dispute_attachment']['tmp_name'];
                        move_uploaded_file($tmp_name, $uploadPath . $_FILES['decline_dispute_attachment']['name']);
                        $uplod_name = $_FILES['decline_dispute_attachment']['name'];
                    }
                } else {
                    $response['id'] = 'attachment';
                    $response['error'] = 'Select Valid File';
                }
            }
            $where_con = array('send_warning_id' => $send_warning_id);
            $data = array('dispute_decline_reason' => $decline_description, 'revert_accepted' => 2, 'dispute_decline_attachment' => $uplod_name);
            $qry_accept_revert = $this->warning_modal->update_send_revert($data, $where_con);
            if ($qry_accept_revert === true) {
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

    //this function is for the decline the description of revert and chnage the status of it
    public function cancel_revert() {
        $decline_description = $this->input->post('dec_desc');
        $send_warning_id = $this->input->post('warning_id');

        $where_con = array('send_warning_id' => $send_warning_id);
        $data = array('cancel_revert_reason' => $decline_description, 'revert_reason' => "", 'revert_request' => 2);
        $qry_accept_revert = $this->warning_modal->update_cancel_revert($data, $where_con);
        if ($qry_accept_revert === true) {
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

    public function get_project_names() {
        $firm_id = $this->input->post('firm_id');
        //       echo $firm_id;
        $get_project_name = $this->db->query("select DISTINCT task_assignment_id,task_assignment_name,task_id from customer_task_allotment_all where firm_id='$firm_id' and sub_task_id=''");
        //        echo $this->db->last_query();
        //        exit;
        if ($get_project_name->num_rows() > 0) {
            foreach ($get_project_name->result() as $row) {
                $task_assignment_id = $row->task_assignment_id;
                $task_assignment_name = $row->task_assignment_name;
                $task_id = $row->task_id;
                $response['project_name'][] = ['task_id' => $row->task_id, 'task_assignment_name' => $row->task_assignment_name, 'task_assignment_id' => $row->task_assignment_id];
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
        //        print_r($task_assignment_name);
    }

    //this function is for the get project name of who has convener of that project
    public function get_project_names_for_employee() {
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
        $query_for_get_user_name = $this->db->query("select user_name,user_id from user_header_all where email='$email_id'");
        if ($query_for_get_user_name->num_rows() > 0) {
            foreach ($query_for_get_user_name->result() as $row) {
                $user_name = $row->user_name;
                $user_id = $row->user_id;
            }
        } else {

        }
        $firm_id = $this->input->post('firm_id');
        //        $uqery_for_check_convener_or_not= $this->db->query("select * from customer_task_allotment_all where firm_id='$firm_id' and sub_task_id='' and alloted_to_emp_id='$user_id'");
        //       echo $firm_id;
        $get_project_name = $this->db->query("select DISTINCT task_assignment_id,task_assignment_name,task_id from customer_task_allotment_all where firm_id='$firm_id' and sub_task_id='' and alloted_to_emp_id='$user_id'");
        //        echo $this->db->last_query();
        //        exit;
        if ($get_project_name->num_rows() > 0) {
            foreach ($get_project_name->result() as $row) {
                $task_assignment_id = $row->task_assignment_id;
                $task_assignment_name = $row->task_assignment_name;
                $task_id = $row->task_id;
                $response['project_name'][] = ['task_id' => $row->task_id, 'task_assignment_name' => $row->task_assignment_name, 'task_assignment_id' => $row->task_assignment_id];
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
        //        print_r($task_assignment_name);
    }

    //this function is for the load firms for hr
    public function get_firms() {
        $result1 = $this->customer_model->get_firm_id();
        $user_id = $result1['user_id'];
        $result_filled_by = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($result_filled_by->num_rows() > 0) {
            $result = $result_filled_by->row();
            $firm_ids = $result->hr_authority;
            $arr = explode(",", $firm_ids);
            $cnt = count($arr);
            //            echo $cnt;
            for ($i = 0; $i < $cnt; $i++) {
                $firm_id = $arr[$i];
                //                echo "select * from partner_header_all where firm_id='$firm_id'";
                $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
                $res = $query_get_firm->row();
                $response['firm_data'][] = ['firm_id' => $res->firm_id, 'firm_name' => $res->firm_name];
            }

            //            print_r($response[ 'firm_data']);
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

    //this function is for the load firms for hq
    public function get_firms_for_hq() {
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

        $query_for_get_boss_id = $this->db->query("select boss_id from partner_header_all where firm_email_id='$email_id'");
        if ($query_for_get_boss_id->num_rows() > 0) {
            $result1 = $query_for_get_boss_id->row();
            $boss_id = $result1->boss_id;
        } else {
            $boss_id = "";
        }
        $query_get_firm = $this->db->query("select * from partner_header_all where reporting_to='$boss_id' and boss_id !='$boss_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
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

    //this function is for the load junior employees of anysenior employee
    public function get_firms_for_emp() {
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
        $query_for_get_firm_id = $this->db->query("select firm_id from user_header_all where email='$email_id'");
        if ($query_for_get_firm_id->num_rows() > 0) {
            $result1 = $query_for_get_firm_id->row();
            $firm_id = $result1->firm_id;
        }

        $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
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

    //this function is for the project wise employee in employee module
    public function get_firms_for_project_wise_in_employee() {
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
        $query_for_get_firm_id = $this->db->query("select firm_id from user_header_all where email='$email_id'");
        if ($query_for_get_firm_id->num_rows() > 0) {
            $result1 = $query_for_get_firm_id->row();
            $firm_id = $result1->firm_id;
        }

        $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
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

    //This function is for the save_sended warning

    public function send_warning() {

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
        }

        $current_date_time = gmdate('Y-m-d h:i:s');
        $revert_under_date = $this->input->post('revert_under_date');
        $warning_id = $this->input->post('warning_card_id');
        $warning_descriptin = $this->input->post('warning_description');
        $firm_id = $this->input->post('firms');
        $employee_id = $this->input->post('employees');
        $revert_under_date = $this->input->post('revert_under_date');

        if (empty(trim($warning_id))) {
            $response['id'] = 'warning_card';
            $response['error'] = 'Select Warning Card';
        } else if (empty(trim($warning_descriptin))) {
            $response['id'] = 'warning_description';
            $response['error'] = 'Enter Warning Description';
        } else {

            for ($x = 0; $x < count($employee_id); $x++) {
                $send_warning_id = $this->generate_send_warning_id();
                $data = array('revert_under_date' => $revert_under_date, 'send_warning_id' => $send_warning_id, 'warning_id' => $warning_id, 'send_by' => $user_id, 'description' => $warning_descriptin, 'send_date' => $current_date_time, 'send_to' => $employee_id[$x], 'firm_id' => $firm_id, 'revert_under_date' => $revert_under_date);
                $qry_save_send_warning = $this->warning_modal->save_send_warning($data);
                if ($qry_save_send_warning === true) {
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

    //this function is for the save data of send warning by hq and employees selected by project
    public function send_waring_hq_project_wise() {
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
        }

        $current_date_time = gmdate('Y-m-d h:i:s');
        $revert_under_date = $this->input->post('revert_under_date');
        $warning_id = $this->input->post('warning_card_id');
        $warning_descriptin = $this->input->post('warning_description');
        $firm_id = $this->input->post('firm_id');
        $employee_id = $this->input->post('employees');
        for ($i = 0; $i < count($employee_id); $i++) {
            $single_emp_id = explode(':', $employee_id[$i]);
            $finial_emp_id[] = $single_emp_id[1];
            $project_tasks[] = $single_emp_id[0];
        }
        if (empty(trim($warning_id))) {
            $response['id'] = 'warning_card';
            $response['error'] = 'Select Warning Card';
        } else if (empty(trim($warning_descriptin))) {
            $response['id'] = 'warning_description';
            $response['error'] = 'Enter Warning Description';
        } else {

            for ($x = 0; $x < count($finial_emp_id); $x++) {
                $send_warning_id = $this->generate_send_warning_id();
                $data = array('revert_under_date' => $revert_under_date, 'send_warning_id' => $send_warning_id, 'warning_id' => $warning_id, 'send_by' => $user_id, 'description' => $warning_descriptin, 'send_date' => $current_date_time, 'send_to' => $finial_emp_id[$x], 'firm_id' => $firm_id, 'revert_under_date' => $revert_under_date, 'project' => $project_tasks[$x]);
                $qry_save_send_warning = $this->warning_modal->save_send_warning($data);
                if ($qry_save_send_warning === true) {
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

    //this function is for the send warning for hq_admin
    public function send_warning_hq() {

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
        }

        $current_date_time = gmdate('Y-m-d h:i:s');
        $revert_under_date = $this->input->post('revert_under_date');
        $warning_id = $this->input->post('warning_card_id');
        $warning_descriptin = $this->input->post('warning_description');
        $firm_id = $this->input->post('firms');
        $employee_id = $this->input->post('employees');
        $revert_under_date = $this->input->post('revert_under_date');
        $loop_counter = $this->input->post('loop_counter_for_get_values');




        if (empty(trim($warning_id))) {
            $response['id'] = 'warning_card';
            $response['error'] = 'Select Warning Card';
        } else if (empty(trim($warning_descriptin))) {
            $response['id'] = 'warning_description';
            $response['error'] = 'Enter Warning Description';
        } else {
            for ($z = 0; $z < $loop_counter; $z++) {
                for ($x = 0; $x < count($employee_id[$z]); $x++) {
                    $send_warning_id = $this->generate_send_warning_id();

                    $data = array('revert_under_date' => $revert_under_date, 'send_warning_id' => $send_warning_id, 'warning_id' => $warning_id, 'send_by' => $user_id, 'description' => $warning_descriptin, 'send_date' => $current_date_time, 'send_to' => $employee_id[$z][$x], 'firm_id' => $firm_id[$z], 'revert_under_date' => $revert_under_date);
                    $qry_save_send_warning = $this->warning_modal->save_send_warning($data);
                    if ($qry_save_send_warning === true) {
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
        }
        echo json_encode($response);
    }

    //this function is for the save warning of human resource
    public function send_warning_hr() {

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
        }

        $current_date_time = gmdate('Y-m-d h:i:s');
        $revert_under_date = $this->input->post('revert_under_date');
        $warning_id = $this->input->post('warning_card_id');
        $warning_descriptin = $this->input->post('warning_description');
        $firm_id = $this->input->post('firms');
        $employee_id = $this->input->post('employees');
        $revert_under_date = $this->input->post('revert_under_date');
        $loop_counter = $this->input->post('loop_counter_for_get_values');

        if (empty(trim($warning_id))) {
            $response['id'] = 'warning_card';
            $response['error'] = 'Select Warning Card';
        } else if (empty(trim($warning_descriptin))) {
            $response['id'] = 'warning_description';
            $response['error'] = 'Enter Warning Description';
        } else {
            for ($z = 0; $z < $loop_counter; $z++) {
                for ($x = 0; $x < count($employee_id[$z]); $x++) {
                    $send_warning_id = $this->generate_send_warning_id();

                    $data = array('revert_under_date' => $revert_under_date, 'send_warning_id' => $send_warning_id, 'warning_id' => $warning_id, 'send_by' => $user_id, 'description' => $warning_descriptin, 'send_date' => $current_date_time, 'send_to' => $employee_id[$z][$x], 'firm_id' => $firm_id[$z], 'revert_under_date' => $revert_under_date);
                    $qry_save_send_warning = $this->warning_modal->save_send_warning($data);
                    if ($qry_save_send_warning === true) {
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
        }
        echo json_encode($response);
    }

    //this function is for the load warning_card content when user selects the card for send to anybody
    public function get_warning_data() {
        $warning_id = $this->input->post("warning_id");
        $query_for_get_warning_data = $this->db->query("Select * from warning_header_all where warning_id='$warning_id'");
        if ($query_for_get_warning_data->num_rows() > 0) {
            foreach ($query_for_get_warning_data->result() as $row) {
                $warning_name = $row->warning_name;
                $description = $row->description;
                $attachment = $row->attachment;
                $is_revocable = $row->is_revocable;
                //                $warning_data[] = ['warning_name' => $row->warning_name, 'description' => $row->description, 'attachment' => $row->attachment, 'is_revocable' => $row->is_revocable];
            }

            $data = '<div class="col-md-6">
				<label class="">Warning Card Name</label>
				<div class="input-group">
				<span class="input-group-addon">
				<i class="fa fa-user"></i></span>
				<input disabled type="text" value="' . $warning_name . '" name="warning_card_name" id="warning_card_name" class="form-control">
				</div>
				</div>';

            if ($attachment == "") {
                $data .= '<div class ="col-md-2">
					<label class="">Attachment</label>
					<div class="input-group">
					<a href="#" class="btn btn-danger disbled">Download</a>
					</div>
					</div>';
            } else {
                $data .= '<div class ="col-md-2">
					<label class="">Attachment</label>
					<div class="input-group">
					<a href="' . base_url() . "uploads/warning_document/" . $attachment . '" class="btn btn-primary disbled">Download</a>
					</div>
					</div>';
            }
            if ($is_revocable == 0) {
                $data .= '<div class="col-md-4">
                    <label class="">Revert Under</label>
                    <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fa fa-user"></i></span>
                    <input type="date" name="revert_under_date" id="revert_under_date" class="form-control">
                    </div>
                    </div>';
            } else {

            }
            $data .= '<div class="col-md-12">
				<label class=""> Warning Card Description</label>
				<div class="input-group">
				<textarea disabled name="warning_description_exist" wrap="virtual" id="warning_description_exist" rows="2" cols="103" onkeypress="remove_error()">' . $description . '
				</textarea>
				</div>
				</div>';
            $response['modal'] = $data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            //            $response['warning_data_all'] = $warning_data;
        } else {
            $response['modal'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //this funcion is for the genrate the warning_id
    public function generate_send_warning_id() {
        $send_warning_id = 'warbat_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('send_warning_transaction');
        $this->db->where('send_warning_id', $send_warning_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return generate_send_warning_id();
        } else {
            return $send_warning_id;
        }
    }

    //this function is for the load warning
    public function get_warning_revocable_employee() {
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

        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $ca_firms = $row->firm_id;
                //                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $ca_firms = "";
        }


        $query_get_firm_name = $this->db->query("select * from warning_header_all where is_revocable='0' and valid_for_firms like " . "'" . $ca_firms . "%'");
        if ($query_get_firm_name->num_rows() > 0) {
            foreach ($query_get_firm_name->result() as $row) {
                $my_data[] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
            }
        } else {
            $my_data = "";
        }

        if ($my_data == "") {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {
            $response['show_data'] = $my_data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        }
        echo json_encode($response);
    }

    // this function is for the ge non-recovable cards
    public function get_warning_non_revocable_employee() {
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
        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $ca_firms = $row->firm_id;
                //                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $ca_firms = "";
        }
        $query_get_firm_name = $this->db->query("select * from warning_header_all where is_revocable='1' and valid_for_firms like " . "'" . $ca_firms . "%'");
        if ($query_get_firm_name->num_rows() > 0) {
            foreach ($query_get_firm_name->result() as $row) {
                $my_data[] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
            }
        } else {
            $my_data[] = "";
        }

        //echo $this->db->last_query();
        if ($my_data[0] == "") {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {
            $response['show_data'] = $my_data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        }
        echo json_encode($response);
    }

    //this function is for the load warning cards for hq
    public function get_warning_revocable_hq() {

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


        $query_for_ftech_warning = $this->db->query("select GROUP_CONCAT(firm_id) as firm_ids from partner_header_all where reporting_to=(select boss_id from partner_header_all where firm_email_id='$email_id');");
        //echo $this->db->last_query();
        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {
                $firm_ids = $row->firm_ids;
            }
            $string1 = "";
            $firm_id_array = explode(',', $firm_ids);
            for ($z = 0; $z < count($firm_id_array); $z++) {
                $string1 .= " valid_for_firms like " . "'%" . $firm_id_array[$z] . "%'" . " or ";

                //array_push($warning_cards->warning_name);
            }
            $string2 = substr($string1, 0, -4);

            $query_for_get_warning_cards = $this->db->query("select * from warning_header_all where ($string2) and (is_revocable='1')");
            if ($query_for_get_warning_cards->num_rows() > 0) {
                $i = 1;
                foreach ($query_for_get_warning_cards->result() as $row) {

                    $my_data[] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
                }
            } else {
                $my_data = "";
                $i = 2;
            }

            if ($i == 1) {
                $response['show_data'] = $my_data;
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

    //this function  is for the load unrevocable warnings for hq
    public function get_warning_non_revocable_hq() {
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

        $query_for_get_boss_id = $this->db->query("select boss_id from partner_header_all where firm_email_id='$email_id'");
        if ($query_for_get_boss_id->num_rows() > 0) {
            $result1 = $query_for_get_boss_id->row();
            $boss_id = $result1->boss_id;
        } else {
            $boss_id = "";
        }
        $query_for_ftech_warning = $this->db->query("select GROUP_CONCAT(firm_id) as firm_ids from partner_header_all where reporting_to=(select boss_id from partner_header_all where firm_email_id='$email_id');");
        //echo $this->db->last_query();
        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {
                $firm_ids = $row->firm_ids;
            }
            $string1 = "";
            $firm_id_array = explode(',', $firm_ids);
            for ($z = 0; $z < count($firm_id_array); $z++) {
                $string1 .= " valid_for_firms like " . "'%" . $firm_id_array[$z] . "%'" . " or ";

                //array_push($warning_cards->warning_name);
            }
            $string2 = substr($string1, 0, -4);

            $query_for_get_warning_cards = $this->db->query("select * from warning_header_all where ($string2) and (is_revocable='0')");
            //echo $this->db->last_query();
            if ($query_for_get_warning_cards->num_rows() > 0) {
                $i = 1;
                foreach ($query_for_get_warning_cards->result() as $row) {

                    $my_data[] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
                }
            } else {
                $my_data = "";
                $i = 2;
            }

            if ($i == 1) {
                $response['show_data'] = $my_data;
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

    //this function is for the load revocable warning cards for hr
    public function get_warning_revocable_hr() {
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

        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $hr_firms = $row->hr_authority;
                //                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $hr_firms = "";
        }
        //        print_r($hr_firms);
        $string = "";
        $hq_firms_array = explode(',', $hr_firms);
        for ($i = 0; $i < count($hq_firms_array); $i++) {
            for ($i = 0; $i < count($hq_firms_array); $i++) {
                $string .= "valid_for_firms like " . "'" . $hq_firms_array[$i] . "%'" . " or ";
                $string1 = substr($string, 0, -4);
            }
            $query_get_firm_name = $this->db->query("select * from warning_header_all where is_revocable='0' and " . $string1);
            //$query_get_firm_name = $this->db->query("select * from warning_header_all where is_revocable='0' and valid_for_firms like " . "'" . $hq_firms_array[$i] . "%'");
            if ($query_get_firm_name->num_rows() > 0) {
                foreach ($query_get_firm_name->result() as $row) {
                    $my_data[] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
                }
            } else {
                $my_data = "";
            }
        }

        if ($my_data != "") {
            $response['show_data'] = $my_data;
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

    //this function is for the load non-revocable warnings cards for hr
    public function get_warning_non_revocable_hr() {
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

        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $hr_firms = $row->hr_authority;
                //                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $hr_firms = "";
        }
        //        print_r($hr_firms);
        $string = "";
        $hq_firms_array = explode(',', $hr_firms);
        for ($i = 0; $i < count($hq_firms_array); $i++) {
            $string .= "valid_for_firms like " . "'" . $hq_firms_array[$i] . "%'" . " or ";
            $string1 = substr($string, 0, -4);
        }
        $query_get_firm_name = $this->db->query("select * from warning_header_all where is_revocable='1' and " . $string1);
        if ($query_get_firm_name->num_rows() > 0) {
            foreach ($query_get_firm_name->result() as $row) {
                $my_data[] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
            }
        } else {
            $my_data = "";
        }

        if ($my_data != "") {
            $response['show_data'] = $my_data;
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

    //this function is for the load revocable warnings for the client-admin
    public function get_warning_revocable_ca() {
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

        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $ca_firms = $row->firm_id;
                //                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $ca_firms = "";
        }

        $query_get_firm_name = $this->db->query("select * from warning_header_all where is_revocable='0' and valid_for_firms like " . "'%" . $ca_firms . "%'");

        if ($query_get_firm_name->num_rows() > 0) {
            foreach ($query_get_firm_name->result() as $row) {
                $my_data[] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
            }
        } else {
            $my_data[] = "";
        }


        if (count($my_data) < 1) {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {
            $response['show_data'] = $my_data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        }
        echo json_encode($response);
    }

    //this function is for the load non-revocable cards of client admin
    public function get_warning_non_revocable_ca() {
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
        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $ca_firms = $row->firm_id;
            }
        } else {
            $ca_firms = "";
        }
        $query_get_firm_name = $this->db->query("select * from warning_header_all where is_revocable='1' and valid_for_firms like " . "'%" . $ca_firms . "%'");
        if ($query_get_firm_name->num_rows() > 0) {
            foreach ($query_get_firm_name->result() as $row) {
                $my_data[] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
            }
        } else {
            $my_data[] = "";
        }
        if (count($my_data) < 1) {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {
            $response['show_data'] = $my_data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        }
        echo json_encode($response);
    }

    public function get_employee_demo() {
        $firm_id = $this->input->post('firm_id');
        //        $all_result = [];
        // echo $firm_id;
        if ($firm_id != '0') {

            //echo $firm_id_length;
            //echo $firm_id[$i];
            $query_get_employee = $this->db->query("select firm_id,designation_id,designation_name from designation_header_all where firm_id='$firm_id'");
            if ($query_get_employee->num_rows() > 0) {

                foreach ($query_get_employee->result() as $row) {
                    // $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id];
                    //$degination_id=$row->designation_id;
                    // $degination_name=$row->designation_name;
                    //$degination_id ;
                    //$degination_name;
                    $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
                    $res = $query_get_firm->row();
                    //$firmName=$res->firm_name;
                    //  echo $firmName;
                    $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id, 'firm_name' => $res->firm_name];
                    // print_r($response);
                    // $response['emp_data'][]=[$degination_id,$degination_name];
                }
                $query_get_employee_from_user_header_all = $this->db->query("select user_id,user_name,designation_id from user_header_all where firm_id='$firm_id' and user_type='4'");
                if ($query_get_employee_from_user_header_all->num_rows() > 0) {
                    $data1 = array('user_id' => array(), 'user_name' => array());
                    foreach ($query_get_employee_from_user_header_all->result() as $row) {
                        $response['emp_data_individual'][] = ['user_id' => $row->user_id, 'designation_id' => $row->designation_id, 'user_name' => $row->user_name];
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
            }
        } else {
            //  echo"i am null";
            $response['emp_data'][] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    //this function is for the load employee from in hq_admin
    public function get_employee_by_project_names() {
        $task_id = $this->input->post('task_id');

        if ($task_id == "") {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {


            $query_for_get_project_employees = $this->db->query("select task_assignment_id,alloted_to_emp_id,sub_task_id from customer_task_allotment_all where task_id='$task_id' and sub_task_id<>''");
            if ($query_for_get_project_employees->num_rows() > 0) {
                foreach ($query_for_get_project_employees->result() as $row) {
                    $alloted_to_emp_id = $row->alloted_to_emp_id;
                    $sub_task_id = $row->sub_task_id;
                    $task_assignment_id = $row->task_assignment_id;
                    $query_for_get_subtask_name = $this->db->query("select task_id,sub_task_name,sub_task_id from sub_task_header_all where sub_task_id='$sub_task_id'");
                    if ($query_for_get_subtask_name->num_rows() > 0) {
                        foreach ($query_for_get_subtask_name->result() as $row) {
                            $task_id = $row->task_id;
                            $tbl_subtask[] = ['sub_task_name' => $row->sub_task_name, 'sub_task_id' => $row->sub_task_id];
                        }
                    } else {

                    }
                    $query_for_emp_name = $this->db->query("select user_name,user_id from user_header_all where user_id='$alloted_to_emp_id'");
                    if ($query_for_emp_name->num_rows() > 0) {
                        foreach ($query_for_emp_name->result() as $row) {
                            $user_name = $row->user_name;
                            $user_id = $row->user_id;
                            $tbl_emp[] = ['user_name' => $row->user_name, 'user_id' => $row->user_id];
                            $response['message'] = 'success';
                            $response['code'] = 200;
                            $response['status'] = true;
                        }
                    } else {

                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                }
                $query_for_get_task_name = $this->db->query("select task_id,task_name from task_header_all where task_id='$task_id'");

                if ($query_for_get_task_name->num_rows() > 0) {
                    foreach ($query_for_get_task_name->result() as $row) {
                        $task_name = $row->task_name;
                        $task_id = $row->task_id;
                    }
                } else {

                }

                $query_for_get_custom_subtask = $this->db->query("select custom_sub_task_id,sub_task_name,alloted_to from custom_sub_task_header_all where task_assignment_id='$task_assignment_id'");
                if ($query_for_get_custom_subtask->num_rows() > 0) {
                    foreach ($query_for_get_custom_subtask->result() as $row) {
                        $user_id_of_c_s_t = $row->alloted_to;
                        $custom_sub_task_id = $row->custom_sub_task_id;
                        $sub_task_name = $row->sub_task_name;
                        $query_for_get_user_name_of_custom_sub_task_emp = $this->db->query("select user_id,user_name from user_header_all where user_id='$user_id_of_c_s_t'");
                        if ($query_for_get_user_name_of_custom_sub_task_emp->num_rows() > 0) {
                            foreach ($query_for_get_user_name_of_custom_sub_task_emp->result() as $row) {
                                $user_name_of_custom = $row->user_name;
                                $user_id_of_custom = $row->user_id;
                            }
                            $custom_sub_task_data[] = ['user_name' => $user_name_of_custom, 'user_id_of_custom' => $user_id_of_custom, 'custom_sub_task_id' => $custom_sub_task_id, 'sub_task_name' => $sub_task_name];
                        } else {
                            $custom_sub_task_data = "";
                        }
                    }
                } else {
                    $custom_sub_task_data = "";
                }
                $query_for_get_project_convener = $this->db->query("select alloted_to_emp_id from customer_task_allotment_all where task_id='$task_id' and sub_task_id=''");
                if ($query_for_get_project_convener->num_rows() > 0) {
                    foreach ($query_for_get_project_convener->result() as $row) {
                        $alloted_to_emp_id_of_convener = $row->alloted_to_emp_id;
                    }
                    $query_for_get_convener_name = $this->db->query("select user_name,user_id from user_header_all where user_id='$alloted_to_emp_id_of_convener'");
                    if ($query_for_get_convener_name->num_rows() > 0) {
                        foreach ($query_for_get_convener_name->result() as $row) {
                            $convener_name = $row->user_name;
                            $convener_id = $row->user_id;
                        }
                    } else {

                    }
                } else {

                }


                $data = "";
                $data1 = "";
                $data3 = "";
                $data1 .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $task_name . '</div></td>
					<td>
					<div>' . $convener_name . '</div>
					</td>
					<td>
					<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
					<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $task_id . ":" . $convener_id . '">
					<span></span>
					</label></td>
					<td>
					<input type="button"  class="btn btn-primary" name="select_all" id="select_all"  value="select all" onclick="select_all_emp();">
					</td>
					<td>
					<input type="button"  class="btn btn-danger" name="deselect_all" id="deselect_all"  value="deselect all" onclick="deselect_all_emp();">
					</td>

					</div></tr>';
                for ($i = 0; $i < count($tbl_subtask); $i++) {
                    $data .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $tbl_subtask[$i]['sub_task_name'] . '</div></td>
						<td>
						<div>' . $tbl_emp[$i]['user_name'] . '</div>
						</td>
						<td>
						<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
						<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $tbl_subtask[$i]['sub_task_id'] . ":" . $tbl_emp[$i]['user_id'] . '">
						<span></span>
						</label></td>
						</div></tr>';
                }
                if ($custom_sub_task_data != "") {
                    for ($x = 0; $x < count($custom_sub_task_data); $x++) {
                        $data3 .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $custom_sub_task_data[$x]['sub_task_name'] . '</div></td>
							<td>
							<div>' . $custom_sub_task_data[$x]['user_name'] . '</div>
							</td>
							<td>
							<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
							<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $custom_sub_task_data[$x]['custom_sub_task_id'] . ":" . $custom_sub_task_data[$x]['user_id_of_custom'] . '">
							<span></span>
							</label></td>
							</div></tr>';
                    }
                } else {
                    $data3 = "";
                }
            } else {

            }
            $response['data_of_table'] = $data;
            $response['data_of_table_custom_subtask'] = $data3;
            $response['data_of_table_head'] = $data1;
        }
        echo json_encode($response);
    }

    //this function is for the load employee from project in client admin
    public function get_employee_by_project_names_client_admin() {
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

        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $user_id_of_ca = $row->user_id;
                //                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $user_id_of_ca = "";
        }


        $task_id = $this->input->post('task_id');
        if ($task_id == "") {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {

            $query_for_get_project_employees = $this->db->query("select task_assignment_id,alloted_to_emp_id,sub_task_id from customer_task_allotment_all where task_id='$task_id' and sub_task_id<>''");
            if ($query_for_get_project_employees->num_rows() > 0) {
                foreach ($query_for_get_project_employees->result() as $row) {
                    $alloted_to_emp_id = $row->alloted_to_emp_id;
                    $sub_task_id = $row->sub_task_id;
                    $task_assignment_id = $row->task_assignment_id;
                    $query_for_get_subtask_name = $this->db->query("select task_id,sub_task_name,sub_task_id from sub_task_header_all where sub_task_id='$sub_task_id'");
                    if ($query_for_get_subtask_name->num_rows() > 0) {
                        foreach ($query_for_get_subtask_name->result() as $row) {
                            $task_id = $row->task_id;
                            $tbl_subtask[] = ['sub_task_name' => $row->sub_task_name, 'sub_task_id' => $row->sub_task_id];
                        }
                    } else {

                    }
                    $query_for_emp_name = $this->db->query("select user_name,user_id from user_header_all where user_id='$alloted_to_emp_id'");
                    if ($query_for_emp_name->num_rows() > 0) {
                        foreach ($query_for_emp_name->result() as $row) {
                            $user_name = $row->user_name;
                            $user_id = $row->user_id;
                            $tbl_emp[] = ['user_name' => $row->user_name, 'user_id' => $row->user_id];
                            $response['message'] = 'success';
                            $response['code'] = 200;
                            $response['status'] = true;
                        }
                    } else {

                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                }
                $query_for_get_task_name = $this->db->query("select task_id,task_name from task_header_all where task_id='$task_id'");

                if ($query_for_get_task_name->num_rows() > 0) {
                    foreach ($query_for_get_task_name->result() as $row) {
                        $task_name = $row->task_name;
                        $task_id = $row->task_id;
                    }
                } else {

                }

                $query_for_get_custom_subtask = $this->db->query("select custom_sub_task_id,sub_task_name,alloted_to from custom_sub_task_header_all where task_assignment_id='$task_assignment_id'");
                if ($query_for_get_custom_subtask->num_rows() > 0) {
                    foreach ($query_for_get_custom_subtask->result() as $row) {
                        $user_id_of_c_s_t = $row->alloted_to;
                        $custom_sub_task_id = $row->custom_sub_task_id;
                        $sub_task_name = $row->sub_task_name;
                        $query_for_get_user_name_of_custom_sub_task_emp = $this->db->query("select user_id,user_name from user_header_all where user_id='$user_id_of_c_s_t'");
                        if ($query_for_get_user_name_of_custom_sub_task_emp->num_rows() > 0) {
                            foreach ($query_for_get_user_name_of_custom_sub_task_emp->result() as $row) {
                                $user_name_of_custom = $row->user_name;
                                $user_id_of_custom = $row->user_id;
                            }
                            $custom_sub_task_data[] = ['user_name' => $user_name_of_custom, 'user_id_of_custom' => $user_id_of_custom, 'custom_sub_task_id' => $custom_sub_task_id, 'sub_task_name' => $sub_task_name];
                        } else {
                            $custom_sub_task_data = "";
                        }
                    }
                } else {
                    $custom_sub_task_data = "";
                }
                $query_for_get_project_convener = $this->db->query("select alloted_to_emp_id from customer_task_allotment_all where task_id='$task_id' and sub_task_id=''");
                if ($query_for_get_project_convener->num_rows() > 0) {
                    foreach ($query_for_get_project_convener->result() as $row) {
                        $alloted_to_emp_id_of_convener = $row->alloted_to_emp_id;
                    }
                    $query_for_get_convener_name = $this->db->query("select user_name,user_id from user_header_all where user_id='$alloted_to_emp_id_of_convener'");
                    if ($query_for_get_convener_name->num_rows() > 0) {
                        foreach ($query_for_get_convener_name->result() as $row) {
                            $convener_name = $row->user_name;
                            $convener_id = $row->user_id;
                        }
                    } else {

                    }
                } else {

                }

                $data = "";
                $data1 = "";
                $data3 = "";
                $data1 .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $task_name . '</div></td>
					<td>
					<div>' . $convener_name . '</div>
					</td>';
                if ($alloted_to_emp_id_of_convener == $user_id_of_ca) {
                    $data1 .= '<td>

						<td>';
                } else {
                    $data1 .= '<td>
						<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
						<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $task_id . ":" . $convener_id . '">
						<span></span>
						</label></td>
						<td>';
                }

                $data1 .= '<input type="button"  class="btn btn-primary" name="select_all" id="select_all"  value="select all" onclick="select_all_emp();">
					</td>
					<td>
					<input type="button"  class="btn btn-danger" name="deselect_all" id="deselect_all"  value="deselect all" onclick="deselect_all_emp();">
					</td>

					</div></tr>';
                for ($i = 0; $i < count($tbl_subtask); $i++) {
                    $data .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $tbl_subtask[$i]['sub_task_name'] . '</div></td>
						<td>
						<div>' . $tbl_emp[$i]['user_name'] . '</div>
						</td>';
                    if ($user_id_of_ca == $tbl_emp[$i]['user_id']) {
                        $data .= '<td></td>';
                    } else {
                        $data .= '<td>
							<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
							<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $tbl_subtask[$i]['sub_task_id'] . ":" . $tbl_emp[$i]['user_id'] . '">
							<span></span>
							</label></td>';
                    }

                    $data .= '</div></tr>';
                }
                if ($custom_sub_task_data != "") {
                    for ($x = 0; $x < count($custom_sub_task_data); $x++) {
                        $data3 .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $custom_sub_task_data[$x]['sub_task_name'] . '</div></td>
							<td>
							<div>' . $custom_sub_task_data[$x]['user_name'] . '</div>
							</td>';
                        if ($user_id_of_ca == $custom_sub_task_data[$x]['user_id_of_custom']) {
                            $data3 .= '<td></td>';
                        } else {
                            $data3 .= '<td>
								<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
								<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $custom_sub_task_data[$x]['custom_sub_task_id'] . ":" . $custom_sub_task_data[$x]['user_id_of_custom'] . '">
								<span></span>
								</label></td>';
                        }
                    }


                    $data3 .= '</div></tr>';
                } else {
                    $data3 = "";
                }
            } else {

            }
            $response['data_of_table'] = $data;
            $response['data_of_table_custom_subtask'] = $data3;
            $response['data_of_table_head'] = $data1;
        }
        echo json_encode($response);
    }

    //this function is for the load project of convior of project the project in employee
    public function get_employee_by_project_names_employee() {
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

        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $user_id_of_ca = $row->user_id;
                //                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $user_id_of_ca = "";
        }

        $task_id = $this->input->post('task_id');
        if ($task_id == "") {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {
            $query_for_get_project_employees = $this->db->query("select task_assignment_id,alloted_to_emp_id,sub_task_id from customer_task_allotment_all where task_id='$task_id' and sub_task_id<>''");
            if ($query_for_get_project_employees->num_rows() > 0) {
                foreach ($query_for_get_project_employees->result() as $row) {
                    $alloted_to_emp_id = $row->alloted_to_emp_id;
                    $sub_task_id = $row->sub_task_id;
                    $task_assignment_id = $row->task_assignment_id;
                    $query_for_get_subtask_name = $this->db->query("select task_id,sub_task_name,sub_task_id from sub_task_header_all where sub_task_id='$sub_task_id'");
                    if ($query_for_get_subtask_name->num_rows() > 0) {
                        foreach ($query_for_get_subtask_name->result() as $row) {
                            $task_id = $row->task_id;
                            $tbl_subtask[] = ['sub_task_name' => $row->sub_task_name, 'sub_task_id' => $row->sub_task_id];
                        }
                    } else {

                    }
                    $query_for_emp_name = $this->db->query("select user_name,user_id from user_header_all where user_id='$alloted_to_emp_id'");
                    if ($query_for_emp_name->num_rows() > 0) {
                        foreach ($query_for_emp_name->result() as $row) {
                            $user_name = $row->user_name;
                            $user_id = $row->user_id;
                            $tbl_emp[] = ['user_name' => $row->user_name, 'user_id' => $row->user_id];
                            $response['message'] = 'success';
                            $response['code'] = 200;
                            $response['status'] = true;
                        }
                    } else {

                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                }
                $query_for_get_task_name = $this->db->query("select task_id,task_name from task_header_all where task_id='$task_id'");

                if ($query_for_get_task_name->num_rows() > 0) {
                    foreach ($query_for_get_task_name->result() as $row) {
                        $task_name = $row->task_name;
                        $task_id = $row->task_id;
                    }
                } else {

                }
                $query_for_get_custom_subtask = $this->db->query("select custom_sub_task_id,sub_task_name,alloted_to from custom_sub_task_header_all where task_assignment_id='$task_assignment_id'");
                if ($query_for_get_custom_subtask->num_rows() > 0) {
                    foreach ($query_for_get_custom_subtask->result() as $row) {
                        $user_id_of_c_s_t = $row->alloted_to;
                        $custom_sub_task_id = $row->custom_sub_task_id;
                        $sub_task_name = $row->sub_task_name;
                        $query_for_get_user_name_of_custom_sub_task_emp = $this->db->query("select user_id,user_name from user_header_all where user_id='$user_id_of_c_s_t'");
                        if ($query_for_get_user_name_of_custom_sub_task_emp->num_rows() > 0) {
                            foreach ($query_for_get_user_name_of_custom_sub_task_emp->result() as $row) {
                                $user_name_of_custom = $row->user_name;
                                $user_id_of_custom = $row->user_id;
                            }
                            $custom_sub_task_data[] = ['user_name' => $user_name_of_custom, 'user_id_of_custom' => $user_id_of_custom, 'custom_sub_task_id' => $custom_sub_task_id, 'sub_task_name' => $sub_task_name];
                        } else {
                            $custom_sub_task_data = "";
                        }
                    }
                } else {
                    $custom_sub_task_data = "";
                }
                $query_for_get_project_convener = $this->db->query("select alloted_to_emp_id from customer_task_allotment_all where task_id='$task_id' and sub_task_id=''");
                if ($query_for_get_project_convener->num_rows() > 0) {
                    foreach ($query_for_get_project_convener->result() as $row) {
                        $alloted_to_emp_id_of_convener = $row->alloted_to_emp_id;
                    }
                    $query_for_get_convener_name = $this->db->query("select user_name,user_id from user_header_all where user_id='$alloted_to_emp_id_of_convener'");
                    if ($query_for_get_convener_name->num_rows() > 0) {
                        foreach ($query_for_get_convener_name->result() as $row) {
                            $convener_name = $row->user_name;
                            $convener_id = $row->user_id;
                        }
                    } else {

                    }
                } else {

                }

                $data = "";
                $data1 = "";
                $data3 = "";
                $data1 .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $task_name . '</div></td>
					<td>
					<div>' . $convener_name . '</div>
					</td>
					<td>
					<input type="button"  class="btn btn-primary" name="select_all" id="select_all"  value="select all" onclick="select_all_emp();">
					</td>
					<td>
					<input type="button"  class="btn btn-danger" name="deselect_all" id="deselect_all"  value="deselect all" onclick="deselect_all_emp();">
					</td>
					</div></tr>';
                for ($i = 0; $i < count($tbl_subtask); $i++) {
                    $data .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $tbl_subtask[$i]['sub_task_name'] . '</div></td>
						<td>
						<div>' . $tbl_emp[$i]['user_name'] . '</div>
						</td>';
                    if ($user_id_of_ca == $tbl_emp[$i]['user_id']) {
                        $data .= '<td></td>';
                    } else {
                        $data .= '<td>
							<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
							<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $tbl_subtask[$i]['sub_task_id'] . ":" . $tbl_emp[$i]['user_id'] . '">
							<span></span>
							</label></td>';
                    }


                    $data . '</div></tr>';
                }
                if ($custom_sub_task_data != "") {
                    for ($x = 0; $x < count($custom_sub_task_data); $x++) {
                        $data3 .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $custom_sub_task_data[$x]['sub_task_name'] . '</div></td>
							<td>
							<div>' . $custom_sub_task_data[$x]['user_name'] . '</div>
							</td>';
                        if ($user_id_of_ca == $custom_sub_task_data[$x]['user_id_of_custom']) {
                            $data3 .= '<td></td>';
                        } else {
                            $data3 .= '<td>
								<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
								<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $custom_sub_task_data[$x]['custom_sub_task_id'] . ":" . $custom_sub_task_data[$x]['user_id_of_custom'] . '">
								<span></span>
								</label></td>';
                        }

                        $data3 .= '</div></tr>';
                    }
                } else {
                    $data3 = "";
                }
            } else {

            }
            $response['data_of_table'] = $data;
            $response['data_of_table_custom_subtask'] = $data3;
            $response['data_of_table_head'] = $data1;
        }
        echo json_encode($response);
    }

    //this function is for the load employee project wise of hr
    public function get_employee_by_project_names_hr() {

        $firm_id = $this->input->post("firm_id");
        //        echo $firm_id;
        $task_id = $this->input->post('task_id');
        $query_for_get_client_admin_of_select_firm = $this->db->query("select * from user_header_all where firm_id='$firm_id' and user_type='3'");
        if ($query_for_get_client_admin_of_select_firm->num_rows() > 0) {
            foreach ($query_for_get_client_admin_of_select_firm->result() as $row) {
                $user_id_of_ca = $row->user_id;
            }
        } else {
            $user_id_of_ca = "";
        }

        if ($task_id == "") {
            echo "i dont have get the task_id";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        } else {
            $query_for_get_project_employees = $this->db->query("select task_assignment_id,alloted_to_emp_id,sub_task_id from customer_task_allotment_all where task_id='$task_id' and sub_task_id<>''");
            if ($query_for_get_project_employees->num_rows() > 0) {
                foreach ($query_for_get_project_employees->result() as $row) {
                    $alloted_to_emp_id = $row->alloted_to_emp_id;
                    $sub_task_id = $row->sub_task_id;
                    $task_assignment_id = $row->task_assignment_id;
                    $query_for_get_subtask_name = $this->db->query("select task_id,sub_task_name,sub_task_id from sub_task_header_all where sub_task_id='$sub_task_id'");
                    if ($query_for_get_subtask_name->num_rows() > 0) {
                        foreach ($query_for_get_subtask_name->result() as $row) {
                            $task_id = $row->task_id;
                            $tbl_subtask[] = ['sub_task_name' => $row->sub_task_name, 'sub_task_id' => $row->sub_task_id];
                        }
                    } else {

                    }
                    $query_for_emp_name = $this->db->query("select user_name,user_id from user_header_all where user_id='$alloted_to_emp_id'");
                    if ($query_for_emp_name->num_rows() > 0) {
                        foreach ($query_for_emp_name->result() as $row) {
                            $user_name = $row->user_name;
                            $user_id = $row->user_id;
                            $tbl_emp[] = ['user_name' => $row->user_name, 'user_id' => $row->user_id];
                            $response['message'] = 'success';
                            $response['code'] = 200;
                            $response['status'] = true;
                        }
                    } else {

                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                }
                $query_for_get_task_name = $this->db->query("select task_id,task_name from task_header_all where task_id='$task_id'");

                if ($query_for_get_task_name->num_rows() > 0) {
                    foreach ($query_for_get_task_name->result() as $row) {
                        $task_name = $row->task_name;
                        $task_id = $row->task_id;
                    }
                } else {

                }
                $query_for_get_custom_subtask = $this->db->query("select custom_sub_task_id,sub_task_name,alloted_to from custom_sub_task_header_all where task_assignment_id='$task_assignment_id'");
                if ($query_for_get_custom_subtask->num_rows() > 0) {
                    foreach ($query_for_get_custom_subtask->result() as $row) {
                        $user_id_of_c_s_t = $row->alloted_to;
                        $custom_sub_task_id = $row->custom_sub_task_id;
                        $sub_task_name = $row->sub_task_name;
                        $query_for_get_user_name_of_custom_sub_task_emp = $this->db->query("select user_id,user_name from user_header_all where user_id='$user_id_of_c_s_t'");
                        if ($query_for_get_user_name_of_custom_sub_task_emp->num_rows() > 0) {
                            foreach ($query_for_get_user_name_of_custom_sub_task_emp->result() as $row) {
                                $user_name_of_custom = $row->user_name;
                                $user_id_of_custom = $row->user_id;
                            }
                            $custom_sub_task_data[] = ['user_name' => $user_name_of_custom, 'user_id_of_custom' => $user_id_of_custom, 'custom_sub_task_id' => $custom_sub_task_id, 'sub_task_name' => $sub_task_name];
                        } else {
                            $custom_sub_task_data = "";
                        }
                    }
                } else {
                    $custom_sub_task_data = "";
                }
                $query_for_get_project_convener = $this->db->query("select alloted_to_emp_id from customer_task_allotment_all where task_id='$task_id' and sub_task_id=''");
                if ($query_for_get_project_convener->num_rows() > 0) {
                    foreach ($query_for_get_project_convener->result() as $row) {
                        $alloted_to_emp_id_of_convener = $row->alloted_to_emp_id;
                    }
                    $query_for_get_convener_name = $this->db->query("select user_name,user_id from user_header_all where user_id='$alloted_to_emp_id_of_convener'");
                    if ($query_for_get_convener_name->num_rows() > 0) {
                        foreach ($query_for_get_convener_name->result() as $row) {
                            $convener_name = $row->user_name;
                            $convener_id = $row->user_id;
                        }
                    } else {

                    }
                } else {

                }

                $data = "";
                $data1 = "";
                $data3 = "";
                $data1 .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $task_name . '</div></td>
					<td>
					<div>' . $convener_name . '</div>
					</td>';
                if ($convener_id == $user_id_of_ca) {
                    $data1 .= '<td></td>';
                } else {
                    $data1 .= '<td>
						<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
						<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $task_id . ":" . $convener_id . '">
						<span></span>
						</label></td>';
                }
                $data1 .= '<td>
					<input type="button"  class="btn btn-primary" name="select_all" id="select_all"  value="select all" onclick="select_all_emp();">
					</td>
					<td>
					<input type="button"  class="btn btn-danger" name="deselect_all" id="deselect_all"  value="deselect all" onclick="deselect_all_emp();">
					</td>
					</div></tr>';
                for ($i = 0; $i < count($tbl_subtask); $i++) {
                    $data .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $tbl_subtask[$i]['sub_task_name'] . '</div></td>
						<td>
						<div>' . $tbl_emp[$i]['user_name'] . '</div>
						</td>';
                    if ($user_id_of_ca == $tbl_emp[$i]['user_id']) {
                        $data .= '<td></td>';
                    } else {
                        $data .= '<td>
							<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
							<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $tbl_subtask[$i]['sub_task_id'] . ":" . $tbl_emp[$i]['user_id'] . '">
							<span></span>
							</label></td>';
                    }


                    $data . '</div></tr>';
                }
                if ($custom_sub_task_data != "") {
                    for ($x = 0; $x < count($custom_sub_task_data); $x++) {
                        $data3 .= '<tr><div style="overflow:scroll !important;height:100px !important;"><td><div>' . $custom_sub_task_data[$x]['sub_task_name'] . '</div></td>
							<td>
							<div>' . $custom_sub_task_data[$x]['user_name'] . '</div>
							</td>';
                        if ($user_id_of_ca == $custom_sub_task_data[$x]['user_id_of_custom']) {
                            $data3 .= '<td></td>';
                        } else {
                            $data3 .= '<td>
								<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
								<input type="checkbox" class="group-checkable" name="checkbox[]" data-set="#sample_2 .checkboxes" value="' . $custom_sub_task_data[$x]['custom_sub_task_id'] . ":" . $custom_sub_task_data[$x]['user_id_of_custom'] . '">
								<span></span>
								</label></td>';
                        }

                        $data3 .= '</div></tr>';
                    }
                } else {
                    $data3 = "";
                }
            } else {

            }
            $response['data_of_table'] = $data;
            $response['data_of_table_custom_subtask'] = $data3;
            $response['data_of_table_head'] = $data1;
        }
        echo json_encode($response);
    }

    public function get_employee_for_hq() {

        $firm_id = $this->input->post('firm_id');
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $hq_firm_id = $result1['firm_id'];
        }
        $hr_array = array();
        $query_for_get_hr_of_firm = $this->db->query("select hr_authority,user_name,user_id from user_header_all where firm_id='$hq_firm_id' and user_type='5'");
        if ($query_for_get_hr_of_firm->num_rows() > 0) {
            $hr_firms_individual = $query_for_get_hr_of_firm->result();
            $hr_authority_array = explode(',', $hr_firms_individual[0]->hr_authority);
            for ($i = 0; $i < count($hr_authority_array); $i++) {
                if ($firm_id == $hr_authority_array[$i]) {

                    $hr_user_id = $hr_firms_individual[0]->user_id;
                    $response['human_resource_data_individual'][] = ['hr_user_id' => $hr_user_id, 'hr_user_name' => $hr_firms_individual[0]->user_name];
                } else {

                }
            }
        } else {

        }
        $query_for_get_ca_of_firm = $this->db->query("select user_id,user_name from user_header_all where firm_id='$firm_id' and user_type='3'");
        if ($query_for_get_ca_of_firm->num_rows() > 0) {
            $client_admin_name = $query_for_get_ca_of_firm->result();
            $client_name = $client_admin_name[0]->user_name;
            $clinet_id = $client_admin_name[0]->user_id;
        } else {

        }
        if ($firm_id != '0') {
            $query_get_employee = $this->db->query("select firm_id,designation_id,designation_name from designation_header_all where firm_id='$firm_id'");
            if ($query_get_employee->num_rows() > 0) {

                foreach ($query_get_employee->result() as $row) {
                    $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
                    $res = $query_get_firm->row();
                    $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id, 'firm_name' => $res->firm_name];
                }
                $query_get_employee_from_user_header_all = $this->db->query("select user_id,user_name,designation_id from user_header_all where firm_id='$firm_id' and user_type='4'");
                if ($query_get_employee_from_user_header_all->num_rows() > 0) {
                    $data1 = array('user_id' => array(), 'user_name' => array());
                    foreach ($query_get_employee_from_user_header_all->result() as $row) {
                        $response['emp_data_individual'][] = ['user_id' => $row->user_id, 'designation_id' => $row->designation_id, 'user_name' => $row->user_name];
                    }
                    $response['client_admin_data_individual'][] = ['ca_user_id' => $clinet_id, 'ca_user_name' => $client_name];
                    $response['message'] = 'success';
                    $response['code'] = 200;
                    $response['status'] = true;
                } else {
                    $response['client_admin_data_individual'][] = "";
                    $response['emp_data'][] = "";
                    $response['message'] = 'No data to display';
                    $response['code'] = 204;
                    $response['status'] = false;
                }
            }
        } else {
            //  echo"i am null";
            $response['emp_data'][] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    //this function is for the get employee of client-admin
    public function get_employee_for_ca() {
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

        $query_get_firm = $this->db->query("select * from user_header_all where email='$email_id'");
        if ($query_get_firm->num_rows() > 0) {
            foreach ($query_get_firm->result() as $row) {
                $user_id_of_ca = $row->user_id;
                //                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $user_id_of_ca = "";
        }

        $firm_id = $this->input->post('firm_id');
        if ($firm_id != '0') {
            //echo $firm_id_length;
            //echo $firm_id[$i];
            $query_get_employee = $this->db->query("select firm_id,designation_id,designation_name from designation_header_all where firm_id='$firm_id'");
            if ($query_get_employee->num_rows() > 0) {
                foreach ($query_get_employee->result() as $row) {
                    // $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id];
                    //$degination_id=$row->designation_id;
                    // $degination_name=$row->designation_name;
                    //$degination_id ;
                    //$degination_name;
                    $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
                    $res = $query_get_firm->row();
                    //$firmName=$res->firm_name;
                    //  echo $firmName;
                    $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id, 'firm_name' => $res->firm_name];
                    // print_r($response);
                    // $response['emp_data'][]=[$degination_id,$degination_name];
                }

                $query_get_employee_from_user_header_all = $this->db->query("select user_id,user_name,designation_id from user_header_all where firm_id='$firm_id' and user_id !='$user_id_of_ca'");

                if ($query_get_employee_from_user_header_all->num_rows() > 0) {
                    $data1 = array('user_id' => array(), 'user_name' => array());
                    foreach ($query_get_employee_from_user_header_all->result() as $row) {
                        $response['emp_data_individual'][] = ['user_id' => $row->user_id, 'designation_id' => $row->designation_id, 'user_name' => $row->user_name];
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
            }
        } else {
            //  echo"i am null";
            $response['emp_data'][] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    //this function is for the load employee of senior employee
    public function get_employee_for_emp() {
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

        $firm_id = $this->input->post('firm_id');

        if ($firm_id != '0') {
            $query_get_user_id = $this->db->query("select user_id from user_header_all where email='$email_id'");
            if ($query_get_user_id->num_rows() > 0) {

                foreach ($query_get_user_id->result() as $row) {
                    $user_id = $row->user_id;
                }
            }

            //echo $firm_id_length;
            //echo $firm_id[$i];
            $query_get_employee = $this->db->query("select firm_id,designation_id,designation_name from designation_header_all where firm_id='$firm_id'");
            if ($query_get_employee->num_rows() > 0) {
                foreach ($query_get_employee->result() as $row) {
                    $query_get_firm = $this->db->query("select * from partner_header_all where firm_id='$firm_id'");
                    $res = $query_get_firm->row();
                    $response['emp_data'][] = ['firm_id' => $row->firm_id, 'designation_name' => $row->designation_name, 'designation_id' => $row->designation_id, 'firm_name' => $res->firm_name];
                }
                $query_get_employee_from_user_header_all = $this->db->query("select user_id,user_name,designation_id from user_header_all where firm_id='$firm_id' and senior_user_id='$user_id'");
                if ($query_get_employee_from_user_header_all->num_rows() > 0) {
                    $data1 = array('user_id' => array(), 'user_name' => array());
                    foreach ($query_get_employee_from_user_header_all->result() as $row) {
                        $response['emp_data_individual'][] = ['user_id' => $row->user_id, 'designation_id' => $row->designation_id, 'user_name' => $row->user_name];
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
            }
        } else {
            $response['emp_data'][] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    //this function is for the check warning permission of sender
    public function check_warning_configure_permission() {
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
        $query_get_user_id = $this->db->query("select user_id,warning_conifg_permission from user_header_all where email='$email_id'");
        if ($query_get_user_id->num_rows() > 0) {

            foreach ($query_get_user_id->result() as $row) {
                $user_id = $row->user_id;
                $warning_conifg_permission = $row->warning_conifg_permission;
            }
            $warning_conifg_permission_finial = explode(',', $warning_conifg_permission);
            $response['config_permission'] = $warning_conifg_permission_finial;
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

    //this function is for the delete dispute from hq
    public function delete_dispute() {
        $warning_id = $this->input->post("warning_id");
        $where_data = array('send_warning_id' => $warning_id);
        $query_for_delete_dispute = $this->warning_modal->delete_dispute_modal($where_data);
        if ($query_for_delete_dispute) {
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

    //this function is for the save updated dispute data
    public function save_update_dispute_data() {
        $warning_name = $this->input->post('warning_name');
        $warning_id = $this->input->post('warning_id');
        $warning_card_id = $this->input->post('warning_card_id');
        $warning_decription = $this->input->post('warning_description');

        if (empty($warning_name)) {
            $response['id'] = 'warning_name_update';
            $response['error'] = 'Enter Card Name';
        } else if (empty($warning_card_id)) {
            $response['id'] = 'warning_card_update';
            $response['error'] = 'Select Warning Card';
        } else if (empty($warning_decription)) {
            $response['id'] = 'warning_description_update';
            $response['error'] = 'Enter Warning Description';
        } else {

            $where_data = array('send_warning_id' => $warning_id);
            $data = array(' warning_name' => $warning_name, 'warning_id' => $warning_card_id, 'description' => $warning_decription);
            $query_for_save_updated_data = $this->warning_modal->save_updated_data_dispute($data, $where_data);

            if ($query_for_save_updated_data == TRUE) {
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

    // this function is for the update sent dispute if it was not have any action
    public function update_dispute() {
        $warning_id = $this->input->post("warning_id");

        $query_for_get_data = $this->db->query("select * from send_warning_transaction where send_warning_id='$warning_id'");
        if ($query_for_get_data->num_rows() > 0) {

            foreach ($query_for_get_data->result() as $row) {
                //                $send_warning_id = $row->send_warning_id;
                $description = $row->description;
                $warning_id_from_query = $row->warning_id;

                $query_for_get_warning_name = $this->db->query("select * from warning_header_all where warning_id='$warning_id_from_query'");
                if ($query_for_get_warning_name->num_rows() > 0) {
                    $res = $query_for_get_warning_name->row();
                    $warning_name = $res->warning_name;
                    $card_id = $res->card_id;
                    $warning_id = $res->warning_id;
                    $query_for_get_card_name = $this->db->query("Select * from card_header_all where card_id='$card_id'");
                    if ($query_for_get_card_name->num_rows() > 0) {

                        $res1 = $query_for_get_card_name->row();
                        $card_name = $res1->card_name;
                        $card_id = $res1->card_id;
                        $response['update_data'][] = ['warning_name' => $warning_name, 'warning_description' => $description, 'warning_id' => $warning_id];
                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['update_data'] = "";
                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                }
            }
            $query_for_get_warning_cards = $this->db->query("Select * from warning_header_all where warning_id <>'$warning_id'");
            if ($query_for_get_warning_cards->num_rows() > 0) {
                foreach ($query_for_get_warning_cards->result() as $row) {
                    $response['warning_cards'][] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name];
                }
            } else {
                $response['warning_cards'] = "";
            }
        }
        echo json_encode($response);
    }

}
?>

