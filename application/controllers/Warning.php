<?php

class Warning extends CI_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('emp_model');
        $this->load->model('hr_model');
        $this->load->model('warning_modal');
        $this->load->model('email_sending_model');
    }

    //load hr dasboard
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
        }



        $query = $this->db->query("SELECT `firm_logo`,`user_name`,`hr_authority` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            $hr_authority = $record->hr_authority;
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

        $hr_authority_array = explode(',', $hr_authority);
        $string_for_get_data = "";
        for ($i = 0; $i < count($hr_authority_array); $i++) {
            $string_for_get_data .= "valid_for_firms like" . "'" . "%" . $hr_authority_array[$i] . "%" . "'" . " or ";
        }
        $string_for_get_data_fnial = substr($string_for_get_data, 0, -4);

        $query_for_ftech_warning = $this->db->query("select * from warning_header_all where " . $string_for_get_data_fnial);
      //  echo $this->db->last_query();
        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {
                $warning_name[] = ['description' => $row->description, 'warning_name' => $row->warning_name, 'warning_id' => $row->warning_id, 'attachment' => $row->attachment, 'card_color' => $row->card_color];
            }
        } else {
            $warning_name[] = "";
        }
        $data['table_data'] = $warning_name;
        $this->load->view('human_resource/warning', $data);
    }

    //this function is for the load employee configure warning
    public function employee_dashboard() {
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

        $query = $this->db->query("SELECT `firm_logo`,`warning_conifg_permission`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            $warning_conifg_permission = $record->warning_conifg_permission;
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

        $query_for_ftech_warning = $this->db->query("select * from warning_header_all where find_in_set( (select firm_id from user_header_all where email='$email_id'),valid_for_firms);");

        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {
                $warning_name[] = ['description' => $row->description, 'warning_name' => $row->warning_name, 'warning_id' => $row->warning_id, 'attachment' => $row->attachment, 'card_color' => $row->card_color];
            }
        } else {
            $warning_name[] = "";
        }
        $warning_conifg_permission_finial = explode(',', $warning_conifg_permission);
        $data['table_data'] = $warning_name;
        $data['permisson_data'] = $warning_conifg_permission_finial;
        $this->load->view('employee/config_warning.php', $data);
    }

    //this function is for the load hq admin dashobard of warning_config
    public function client_admin_dashboard() {
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

        $query = $this->db->query("SELECT `firm_logo`,`warning_conifg_permission`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            $warning_conifg_permission = $record->warning_conifg_permission;
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

        $query_for_ftech_warning = $this->db->query("select * from warning_header_all where find_in_set( (select firm_id from partner_header_all where firm_email_id='$email_id'),valid_for_firms);");

        if ($query_for_ftech_warning->num_rows() > 0) {
            foreach ($query_for_ftech_warning->result() as $row) {
                $warning_name[] = ['description' => $row->description, 'warning_name' => $row->warning_name, 'warning_id' => $row->warning_id, 'attachment' => $row->attachment, 'card_color' => $row->card_color];
            }
        } else {
            $warning_name[] = "";
        }
        $warning_conifg_permission_finial = explode(',', $warning_conifg_permission);
        $data['table_data'] = $warning_name;
        $data['permisson_data'] = $warning_conifg_permission_finial;
        $this->load->view('client_admin/config_warning_ca', $data);
    }

    //this function is for the load configure_warning dashboard of hq_admin
    public function hq_admin_dashboard() {
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

        $warning_card = array();
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

            $query_for_get_warning_cards = $this->db->query("select * from warning_header_all where $string2");
            if ($query_for_get_warning_cards->num_rows() > 0) {
                foreach ($query_for_get_warning_cards->result() as $row) {
                    $warning_name[] = ['description' => $row->description, 'warning_name' => $row->warning_name, 'warning_id' => $row->warning_id, 'attachment' => $row->attachment, 'card_color' => $row->card_color];
                }
            } else {
                $warning_name[] = "";
            }
        } else {
            $warning_name[] = "";
        }
        $data['table_data'] = $warning_name;
        //var_dump($warning_card);
        $this->load->view('hq_admin/warning_hq', $data);
    }

    // this function is for the save warning
    public function save_warning() {
        $name = $this->input->post("whose_function");

        $current_date_time = gmdate('Y-m-d h:i:s');
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
            $firm_id_temp = $record->firm_id;
        }


        $uplod_name = "";
        $warning_id = $this->generate_warning_id();
        $warning_name = $this->input->post('warning_name');
        $card_color = $this->input->post('card_color');
        $revocable_cards = $this->input->post('revocable_card');
        $non_revocable_cards = $this->input->post('non_revocable_card');
        $warning_description = $this->input->post('warning_description');
        if ($revocable_cards == 0) {
            $is_revocable = 0;
        } else {
            $is_revocable = 1;
        }

        if (empty($warning_name)) {
            $response['id'] = 'warning_name';
            $response['error'] = 'Enter Card Name';
        } else if (empty($card_color)) {
            $response['id'] = 'card';
            $response['error'] = 'Select Card Color';
        } else if ($revocable_cards == "" && $non_revocable_cards == "") {
            $response['id'] = 'non_revocable_card';
            $response['error'] = 'Select Warning Card Type';
        } else if (empty(trim($warning_description))) {
            $response['id'] = 'warning_description';
            $response['error'] = 'Enter Description';
        } else {
            if ($is_revocable === '1') {
                $revocable_duration = "";
            } else {
                if ($name == "client_admin") {
                    $all_firms = $firm_id_temp;
                } else if ($name = "hq_admin") {
                    $firms = $this->input->post('multiple_firms');
                    $firm_ids = array();
                    for ($j = 0; $j < count($firms); $j++) {
                        $firm_ids[] = $firms[$j];
                    }
                    $all_firms = implode(',', $firm_ids);
                } else {

                }

                if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] != '4') {
                    $all_files = $_FILES['attachment']['name'];
                    $type_of_file[] = pathinfo($all_files, PATHINFO_EXTENSION);
                    $allowed = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'xls', 'xlsx', 'doc');
                    $valid_file_type_result = array_diff($type_of_file, $allowed);
                    $count_of_valid_file_type_result = count($valid_file_type_result);
                    if ($count_of_valid_file_type_result <= 0) {
                        $uploadPath = './uploads/warning_document/';
                        if (file_exists('./uploads/warning_document/' . $all_files)) {
                            $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['attachment']['name']));
                            move_uploaded_file($_FILES["attachment"]["tmp_name"], './uploads/warning_document/' . $newfilename);
                            $uplod_name = $newfilename;
                        } else {
                            $tmp_name = $_FILES['attachment']['tmp_name'];
                            move_uploaded_file($tmp_name, $uploadPath . $_FILES['attachment']['name']);
                            $uplod_name = $_FILES['attachment']['name'];
                        }
                    } else {
                        $response['id'] = 'attachment';
                        $response['error'] = 'Select Valid File';
                        echo json_encode($response);
                        exit;
                    }
                }
                $data = array('warning_id' => $warning_id, 'warning_name' => $warning_name, 'description' => $warning_description, 'attachment' => $uplod_name, 'is_revocable' => $is_revocable, 'created_on' => $current_date_time, 'created_by' => $user_id, 'card_color' => $card_color, 'valid_for_firms' => $all_firms);
                $qry_save_warning = $this->warning_modal->save_warning($data);
            }
            //            $update_data = array('is_used' => 1);
            //            $where_data = array('card_id' => $card_name);
            //            $qry_update_card = $this->warning_modal->update_card($where_data, $update_data);
            if ($qry_save_warning === true) {
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

    //this function for the update warning_card data
    public function update_warning_data() {
        $uplod_name = "";
        $warning_id = $this->input->post('warning_card_id');
        $warning_name = $this->input->post('warning_name_update');
        $card_color = $this->input->post('card_color_update');
        $revocable_cards = $this->input->post('revocable_card_update');
        $non_revocable_cards = $this->input->post('non_revocable_card_update');
        $warning_description = $this->input->post('warning_description_update');
        $firms = $this->input->post('multiple_firms_update');
        $firm_ids = array();

        if ($revocable_cards == 0) {
            $is_revocable = 0;
        } else {
            $is_revocable = 1;
        }

        if (empty($warning_name)) {
            $response['id'] = 'warning_name_update';
            $response['error'] = 'Enter Card Name';
        } else if (empty($card_color)) {
            $response['id'] = 'card_color_update';
            $response['error'] = 'Select Card Color';
        } else if ($revocable_cards == "" && $non_revocable_cards == "") {
            $response['id'] = 'non_revocable_card_update';
            $response['error'] = 'Select Warning Card Type';
        } else if (empty(trim($warning_description))) {
            $response['id'] = 'warning_description_update';
            $response['error'] = 'Enter Description';
        } else if ($firms == "") {
            $response['id'] = 'multiple_firms_update';
            $response['error'] = 'Select Firm';
        } else {
            if ($is_revocable === '1') {
                $revocable_duration = "";
            } else {
                for ($j = 0; $j < count($firms); $j++) {
                    $firm_ids[] = $firms[$j];
                }

                $all_firms = implode(',', $firm_ids);
                if (isset($_FILES['attachment_update']) && $_FILES['attachment_update']['error'] != '4') {

                    $all_files = $_FILES['attachment_update']['name'];
                    $type_of_file[] = pathinfo($all_files, PATHINFO_EXTENSION);
                    $allowed = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'xls', 'xlsx', 'doc');
                    $valid_file_type_result = array_diff($type_of_file, $allowed);
                    $count_of_valid_file_type_result = count($valid_file_type_result);
                    if ($count_of_valid_file_type_result <= 0) {
                        $uploadPath = './uploads/warning_document/';
                        if (file_exists('./uploads/warning_document/' . $all_files)) {
                            $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['attachment_update']['name']));
                            move_uploaded_file($_FILES["attachment_update"]["tmp_name"], './uploads/warning_document/' . $newfilename);
                            $uplod_name = $newfilename;
                        } else {
                            $tmp_name = $_FILES['attachment_update']['tmp_name'];
                            move_uploaded_file($tmp_name, $uploadPath . $_FILES['attachment_update']['name']);
                            $uplod_name = $_FILES['attachment_update']['name'];
                        }
                    } else {
                        $response['id'] = 'attachment_update';
                        $response['error'] = 'Select Valid File';
                    }
                } else {
                    $uplod_name = $this->input->post('attachment_txt');
                }
                $where_data = ['warning_id' => $warning_id];
                $data = array('warning_name' => $warning_name, 'description' => $warning_description, 'attachment' => $uplod_name, 'is_revocable' => $is_revocable, 'card_color' => $card_color, 'valid_for_firms' => $all_firms);
                $qry_save_warning = $this->warning_modal->update_warning($data, $where_data);
            }
            //            $update_data = array('is_used' => 1);
            //            $where_data = array('card_id' => $card_name);
            //            $qry_update_card = $this->warning_modal->update_card($where_data, $update_data);
            if ($qry_save_warning === true) {
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

    //this function is for the update warning card in client admin
    public function update_warning_data_ca() {
        $uplod_name = "";
        $warning_id = $this->input->post('warning_card_id');
        $warning_name = $this->input->post('warning_name_update');
        $card_color = $this->input->post('card_color_update');
        $revocable_cards = $this->input->post('revocable_card_update');
        $non_revocable_cards = $this->input->post('non_revocable_card_update');
        $warning_description = $this->input->post('warning_description_update');
        if ($revocable_cards == 0) {
            $is_revocable = 0;
        } else {
            $is_revocable = 1;
        }
        if (empty($warning_name)) {
            $response['id'] = 'warning_name_update';
            $response['error'] = 'Enter Card Name';
        } else if (empty($card_color)) {
            $response['id'] = 'card_color_update';
            $response['error'] = 'Select Card Color';
        } else if ($revocable_cards == "" && $non_revocable_cards == "") {
            $response['id'] = 'non_revocable_card_update';
            $response['error'] = 'Select Warning Card Type';
        } else if (empty(trim($warning_description))) {
            $response['id'] = 'warning_description_update';
            $response['error'] = 'Enter Description';
        } else {
            if ($is_revocable === '1') {
                $revocable_duration = "";
            } else {
                if (isset($_FILES['attachment_update']) && $_FILES['attachment_update']['error'] != '4') {
                    // $filesCount = count($_FILES['attachment_update']['name']);
                    $all_files = $_FILES['attachment_update']['name'];
                    $type_of_file[] = pathinfo($all_files, PATHINFO_EXTENSION);
                    $allowed = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'xls', 'xlsx', 'doc');
                    $valid_file_type_result = array_diff($type_of_file, $allowed);
                    $count_of_valid_file_type_result = count($valid_file_type_result);
                    if ($count_of_valid_file_type_result <= 0) {
                        $uploadPath = './uploads/warning_document/';
                        if (file_exists('./uploads/warning_document/' . $all_files)) {
                            $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES['attachment_update']['name']));
                            move_uploaded_file($_FILES["attachment_update"]["tmp_name"], './uploads/warning_document/' . $newfilename);
                            $uplod_name = $newfilename;
                        } else {
                            $tmp_name = $_FILES['attachment_update']['tmp_name'];
                            move_uploaded_file($tmp_name, $uploadPath . $_FILES['attachment_update']['name']);
                            $uplod_name = $_FILES['attachment_update']['name'];
                        }
                    } else {
                        $response['id'] = 'attachment_update';
                        $response['error'] = 'Select Valid File';
                    }
                } else {
                    $uplod_name = $this->input->post('attachment');
                }
                $where_data = ['warning_id' => $warning_id];
                $data = array('warning_name' => $warning_name, 'description' => $warning_description, 'attachment' => $uplod_name, 'is_revocable' => $is_revocable, 'card_color' => $card_color);
                $qry_save_warning = $this->warning_modal->update_warning($data, $where_data);
            }
            //            $update_data = array('is_used' => 1);
            //            $where_data = array('card_id' => $card_name);
            //            $qry_update_card = $this->warning_modal->update_card($where_data, $update_data);
            if ($qry_save_warning === true) {
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

    //this is function is for the delete selected warning card
    public function delete_warning_card() {
        $warning_id = $this->input->post("warning_card_id");
        $query_for_delete_warning_card = $this->warning_modal->delete_warning_card($warning_id);
        if ($query_for_delete_warning_card) {
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

    //this function is for the get previous data for get_update warning_card
    public function get_warning_card_data_for_update() {
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
                $hq_firm_id[] = $row->firm_id;
                $response['firm_data'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
            }
        } else {
            $response['firm_data'] = "";
        }

        $warning_id = $this->input->post("warning_card_id");
        $query_fot_get_data = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
        if ($query_fot_get_data->num_rows() > 0) {
            foreach ($query_fot_get_data->result() as $row) {
                $select_firms = $row->valid_for_firms;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                $response['warning_data'][] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name, 'description' => $row->description, 'attachment' => $row->attachment, 'is_revocable' => $row->is_revocable, 'card_color' => $row->card_color, 'valid_for_firms' => $row->valid_for_firms];
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        $vaild_for_firms_array = explode(',', $select_firms);
        $abc1 = array_diff($hq_firm_id, $vaild_for_firms_array);
        $unic_array_of_firms = array_values($abc1);


        if (count($unic_array_of_firms) < 1) {
            $response['unselected_fimrs'] = "";
        } else {
            for ($y = 0; $y < count($unic_array_of_firms); $y++) {
                $query_get_firm_name_all = $this->db->query("select * from partner_header_all where firm_id='$unic_array_of_firms[$y]'");
                if ($query_get_firm_name_all->num_rows() > 0) {
                    foreach ($query_get_firm_name_all->result() as $row) {
                        $response['unselected_fimrs'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
                    }
                } else {
                    $response['unselected_fimrs'] = "";
                }
            }
        }


        for ($w = 0; $w < count($vaild_for_firms_array); $w++) {
            $query_get_firm_name_all1 = $this->db->query("select * from partner_header_all where firm_id='$vaild_for_firms_array[$w]'");
            if ($query_get_firm_name_all1->num_rows() > 0) {
                foreach ($query_get_firm_name_all1->result() as $row) {
                    $response['selected_fimrs'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
                }
            } else {
                $response['selected_fimrs'] = "";
            }
        }


        echo json_encode($response);
    }

    //this function is for the get previous data for get_update warning_card for hr
    public function get_warning_card_data_for_update_for_hr() {
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

        $result_filled_by = $this->db->query("select hr_authority from user_header_all where email='$email_id'");
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
        }
        $hr_firm_id = explode(",", $firm_ids);

        $warning_id = $this->input->post("warning_card_id");
        $query_fot_get_data = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
        if ($query_fot_get_data->num_rows() > 0) {
            foreach ($query_fot_get_data->result() as $row) {
                $select_firms = $row->valid_for_firms;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                $response['warning_data'][] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name, 'description' => $row->description, 'attachment' => $row->attachment, 'is_revocable' => $row->is_revocable, 'card_color' => $row->card_color, 'valid_for_firms' => $row->valid_for_firms, 'attachment' => $row->attachment];
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        $vaild_for_firms_array = explode(',', $select_firms);
        $abc1 = array_diff($hr_firm_id, $vaild_for_firms_array);
        $unic_array_of_firms = array_values($abc1);


        if (count($unic_array_of_firms) < 1) {
            $response['unselected_fimrs'] = "";
        } else {
            for ($y = 0; $y < count($unic_array_of_firms); $y++) {
                $query_get_firm_name_all = $this->db->query("select * from partner_header_all where firm_id='$unic_array_of_firms[$y]'");
                if ($query_get_firm_name_all->num_rows() > 0) {
                    foreach ($query_get_firm_name_all->result() as $row) {
                        $response['unselected_fimrs'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
                    }
                } else {
                    $response['unselected_fimrs'] = "";
                }
            }
        }


        for ($w = 0; $w < count($vaild_for_firms_array); $w++) {
            $query_get_firm_name_all1 = $this->db->query("select * from partner_header_all where firm_id='$vaild_for_firms_array[$w]'");
            if ($query_get_firm_name_all1->num_rows() > 0) {
                foreach ($query_get_firm_name_all1->result() as $row) {
                    $response['selected_fimrs'][] = ['firm_id' => $row->firm_id, 'firm_name' => $row->firm_name];
                }
            } else {
                $response['selected_fimrs'] = "";
            }
        }


        echo json_encode($response);
    }

    //this function is for the get warning data for client admin
    public function get_warning_card_data_for_update_ca() {
        $warning_id = $this->input->post("warning_card_id");
        $query_fot_get_data = $this->db->query("select * from warning_header_all where warning_id='$warning_id'");
        if ($query_fot_get_data->num_rows() > 0) {
            foreach ($query_fot_get_data->result() as $row) {
                $select_firms = $row->valid_for_firms;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                $response['warning_data'][] = ['warning_id' => $row->warning_id, 'warning_name' => $row->warning_name, 'description' => $row->description, 'attachment' => $row->attachment, 'is_revocable' => $row->is_revocable, 'card_color' => $row->card_color, 'valid_for_firms' => $row->valid_for_firms, 'attachment' => $row->attachment];
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    //this function is for thr genrate the warning_id
    public function generate_warning_id() {
        $warning_id = 'War_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('warning_header_all');
        $this->db->where('warning_id', $warning_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return generate_warning_id();
        } else {
            return $warning_id;
        }
    }

}
?>

