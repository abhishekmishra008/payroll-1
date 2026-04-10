<?php

class Nas extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('firm_model');
        $this->load->model('customer_model');
        $this->load->model('Nas_modal');
        $this->load->library('session');
    }

    public function index() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
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


        $data['prev_title'] = "Network Attached Storage Configuration";
        $data['page_title'] = "Network Attached Storage Configuration";
        $data['firm_id'] = $firm_id;
        $this->load->view('admin/nas', $data);
    }

    public function getParentId() {

        if (!is_null($this->input->post_get("index"))) {
            $index = $this->input->post_get("index");
            $result = $this->Nas_modal->folder_creation($index);
            if ($result != false) {
                $response["status"] = 200;
                $response["body"] = $result;
            } else {
                $response["status"] = 300;
                $response["body"] = "No Parent Id";
            }
        } else {
            $response["status"] = 400;
            $response["body"] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    //send request to nas device for getting a refrsh token
    public function get_refresh_token() {
        $firm_id = $this->session->userdata('firm_id');

        $auth_code = $this->input->post("auth_code");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://wdc.auth0.com/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=authorization_code&client_id=mSEJny79ckQzvSlRr9S55W8l30Do9bwI&client_secret=JIVemg3A0yEmbvkBGiS7RgnHEkq8veiNf6Rh-_gcNgZYNdJxrm5Z0anC76yfChhV&code=" . $auth_code . "&redirect_uri=http://localhost",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $auth_data = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);


        if ($err) {

        } else {

            $refresh_token = json_decode($auth_data);
        }
        $access_token = $refresh_token->access_token;
        $refresh_token1 = $refresh_token->refresh_token;

        if ($access_token != "" && $refresh_token != "") {
            $user_id = $this->Nas_modal->get_hq_user_id($firm_id);
            $data = array("refresh_token" => $refresh_token1,
                "access_token" => $access_token,
                "status" => 1,
            );
            $where_data = array("user_id" => $user_id);

            $query_for_insert_data = $this->Nas_modal->save_auth_details($data, $where_data);
            if ($query_for_insert_data) {
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

    public function get_access_token() {
        $firm_id = $this->session->userdata('firm_id');
        $for = "access_token";
        $access_token_for_update = "";
        $access_token = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update);
        //echo $this->db->last_query();
        if ($access_token != "") {
            $for = "proxy_url";
            $access_token_for_update = "";
            $proxy_url = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update);

            if ($proxy_url != "") {

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['access_token'] = "";
                $response['proxy_url'] = "";
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $response['access_token'] = "";
            $response['proxy_url'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function get_user_id() {
        $firm_id = $this->session->userdata('firm_id');

        $user_id = $this->Nas_modal->get_hq_user_id($firm_id);
        $email = $this->input->post("email");
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (trim(empty($email))) {
            $response['id'] = 'email';
            $response['error'] = 'Enter Email Id';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['id'] = 'email';
            $response['error'] = 'Cannot get UserInfo from invalid email';
        } else {
            $get_acccess_token = $this->db->query("select access_token from auth_header_all where user_id='$user_id' and status='1'");
            if ($get_acccess_token->num_rows() > 0) {
                foreach ($get_acccess_token->result() as $row) {
                    $access_token = $row->access_token;
                }
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://authservice.mycloud.com/authservice/v2/auth0/user?email=" . "$email",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/x-www-form-urlencoded", "authorization: Bearer " . $access_token . ""
                    ),
                ));

                $auth_data = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);


                if ($err) {

                } else {
                    $refresh_token = json_decode($auth_data);
                    if ($refresh_token->data[0]->isPreUser == "bool") {
                        $response['code'] = 100;
                        $response['id'] = 'email';
                        //$response['error'] = $refresh_token->error->message;
                    } else {
                        $nas_user_id = $refresh_token->data[0]->user_id;
                        $nas_email_id = $refresh_token->data[0]->email;

                        $data = array("nas_user_id" => $nas_user_id,
                            "nas_emal_id" => $nas_email_id
                        );
                        $where_data = array("user_id" => $user_id,
                            "status" => 1);
                        $query_for_update_nas_user_id = $this->Nas_modal->update_nas_user_id($data, $where_data);
                        if ($query_for_update_nas_user_id == true) {
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
            } else {

            }
        }
        echo json_encode($response);
    }

    public function get_device_details() {

        $firm_id = $this->session->userdata('firm_id');
        $user_id = $this->Nas_modal->get_hq_user_id($firm_id);
        $get_acccess_token = $this->db->query("select nas_user_id,access_token from auth_header_all where user_id='$user_id' and status='1'");
        if ($get_acccess_token->num_rows() > 0) {
            foreach ($get_acccess_token->result() as $row) {
                $nas_user_id = $row->nas_user_id;
                $access_token = $row->access_token;
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://device.mycloud.com/device/v1/user/" . "$nas_user_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded", "authorization: Bearer " . $access_token . ""
                ),
            ));

            $auth_data = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);


            if ($err) {

            } else {
                $refresh_token = json_decode($auth_data);
                //var_dump($refresh_token);
                $nas_device_id = $refresh_token->data[0]->deviceId;
                $nas_device_name = $refresh_token->data[0]->name;
                $nas_device_mac = $refresh_token->data[0]->mac;
                $nas_device_type = $refresh_token->data[0]->type;
                $nas_device_local_ip = $refresh_token->data[0]->network->localIpAddress;
                $nas_device_external_ip = $refresh_token->data[0]->network->externalIpAddress;
                $nas_device_tunnel_id = $refresh_token->data[0]->network->tunnelId;
                $nas_device_internal_dns_name = $refresh_token->data[0]->network->internalDNSName;
                $nas_device_proxy_url = $refresh_token->data[0]->network->proxyURL;
                $data = array("device_id" => $nas_device_id,
                    "device_name" => $nas_device_name,
                    "mac" => $nas_device_mac,
                    "type" => $nas_device_type,
                    "local_ip" => $nas_device_local_ip,
                    "external_ip" => $nas_device_external_ip,
                    "tunnel_id" => $nas_device_tunnel_id,
                    "internal_dns_name" => $nas_device_internal_dns_name,
                    "proxy_url" => $nas_device_proxy_url
                );
                $where_data = array("user_id" => $user_id, "status" => 1);
                $update_data = $this->Nas_modal->update_nas_device_details($data, $where_data);
                if ($update_data == true) {
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

    public function get_user_id_for_check() {
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
        return $user_id;
    }

    public function check_access_token() {
        $user_id = $this->get_user_id_for_check();

        $query_for_check_access_token = $this->db->query("select token_expired from auth_header_all where user_id='$user_id' and status='1'");
        $row = $query_for_check_access_token->result();
        if ($row[0]->token_expired == 0) {
            $referesh_token = $this->regenrate_access_token();
            echo $new_access_token = $this->genrate_access_token_via_refresh_token($referesh_token);
            $data = array('access_token' => $new_access_token, 'token_expired' => 1);
            $where_data = array('user_id' => $user_id, 'status' => 1);
            $update_access_token = $this->Nas_modal->update_access_token($data, $where_data);
            if ($update_access_token == true) {

            } else {

            }
        } else {

        }
    }

    public function regenrate_access_token() {
        $user_id = $this->get_user_id_for_check();
        $query_for_get_refresh_token = $this->db->query("select refresh_token from auth_header_all where user_id='$user_id' and status='1'");
        $row = $query_for_get_refresh_token->result();
        $referesh_token = $row[0]->refresh_token;
        return $referesh_token;
    }

    public function genrate_access_token_via_refresh_token($referesh_token) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://wdc.auth0.com/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=refresh_token&client_id=mSEJny79ckQzvSlRr9S55W8l30Do9bwI&client_secret=JIVemg3A0yEmbvkBGiS7RgnHEkq8veiNf6Rh-_gcNgZYNdJxrm5Z0anC76yfChhV&refresh_token=" . $referesh_token,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

        } else {
            $access_token = json_decode($response);
            return $access_token->access_token;
        }
    }

    public function not_now() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
            $user_id = $result1['user_id'];
        }
        $data = array("status" => 3);
        $where_data = array("user_id" => $user_id);
        $update_data = $this->Nas_modal->not_now($data, $where_data);
        if ($update_data === TRUE) {

        } else {

        }
    }

    public function genrate_file_transaction_id() {
        $transaction_id = $this->Nas_modal->genrate_file_transaction_id();
        if ($transaction_id != "") {
            $response['transaction_id'] = $transaction_id;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['transaction_id'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function get_access_token_for_firm() {
        $firm_id = $this->session->userdata('firm_id');
        $firm_name_query = $this->db->query("select firm_name from partner_header_all where firm_id='$firm_id'");
        if ($firm_name_query->num_rows() > 0) {
            $row = $firm_name_query->result();
            $firm_name = $row[0]->firm_name;
            $response['firm_name'] = $firm_name;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['firm_name'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function genrate_folder_for_hq() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
        }
        $access_token = $this->Nas_modal->get_access_code($user_id);
        $folder_id = $this->Nas_modal->genrate_folder_and_update_into_database($access_token);
    }

    public function set_session_data() {
        $firm_id = $this->input->post("firm_id_string");
        $sess_array = array(
            'firm_id' => base64_decode($firm_id)
        );
        $this->session->set_userdata($sess_array);
    }

    public function insert_folder_into_tbl() {
        $nas_transaction_id = $this->input->post('file_transaction_id');
        $parent_id = $this->input->post('parent_id');
        $sub_folder_id = $this->input->post('sub_folder_of_parent');
        $finial_folder_id = $parent_id . "->" . implode(',', $sub_folder_id);
        $data = array('transaction_id' => $nas_transaction_id, 'file_id' => $finial_folder_id);

        $q = $this->Nas_modal->insert_transaction_data($data);
        if ($q === true) {
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

    public function insert_folder_into_tbl_demo() {
        $nas_transaction_id = $this->input->post('file_transaction_id');
        $parent_id = $this->input->post('parent_id');

        $data = array('transaction_id' => $nas_transaction_id, 'file_id' => $parent_id);

        $q = $this->Nas_modal->insert_transaction_data($data);
        if ($q === true) {
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

    public function update_folder_into_partner_header_all() {
        $firm_id = $this->session->userdata('firm_id');
        $folder_id = $this->input->post("folder_id");

        $data = array('folder_id' => $folder_id);
        $where_data = array('firm_id' => $firm_id);
        $update_query = $this->Nas_modal->genrate_folder_and_update_into_database($data, $where_data);
        $this->db->last_query();
        if ($update_query === TRUE) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['firm_name'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
        }
        echo json_encode($response);
    }

    public function nas_insert_data() {
        $file_trans_id = $this->input->post_get('file_trans_id');
        $nas_trans_id = $this->input->post_get('nas_trans_id');
        $data = array(
            'transaction_id' => $nas_trans_id,
            'file_id' => $file_trans_id
        );
        $result = $this->Nas_modal->nas_transaction($data);
        if ($result == '1') {

            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['firm_name'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
        }
    }

    public function get_access_token_php() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $firm_id = $result1['firm_id'];
        }


        if ($firm_id === '') {
            $firm_id = $this->session->userdata('firm_id');
        }


        $for = "access_token";
        $access_token_for_update = '';
        $access_token = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update);

        if ($access_token != false) {
            $response['accees_token'] = $access_token;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['accees_token'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
        }
        echo json_encode($response);
    }

    public function get_proxy_url_php() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $firm_id = $result1['firm_id'];
        }
        if ($firm_id === '') {
            $firm_id = $this->session->userdata('firm_id');
        }

        $for = "proxy_url";
        $access_token_for_update = '';
        $proxy_url = $this->Nas_modal->get_hq_of_firm($firm_id, $for, $access_token_for_update);
        if ($proxy_url != false) {
            $response['proxy_url'] = $proxy_url;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['proxy_url'] = "";
            $response['firm_name'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
        }
        echo json_encode($response);
    }

    public function download_nas_file() {
        $file_id = $this->input->post('file_id');
        $q = $this->db->query("select file_id from nas_transaction_all where transaction_id='$file_id'");
        if ($this->db->affected_rows() == 1) {
            $result = $q->result();
            $response['file_id'] = $file_id = $result[0]->file_id;

            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['file_id'] = "";
            $response['firm_name'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
        }
        echo json_encode($response);
    }

    public function delete_auth() {
        $q = $this->db->query("Delete * from auth_header_all where user_id='U_808'");
    }

    public function get_firm_name() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        } else {

            $firm_id = '';
        }
        $tran_details = $this->db->query("select * from nas_transaction_all where  transaction_id=(select folder_id from partner_header_all where firm_id='$firm_id')")->row();
        if ($tran_details != null) {
            $file_id = $tran_details->file_id;
        } else {
            $file_id = '';
        }
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
            $this->db->where("reporting_to = '$get_boss_id' AND firm_activity_status = 'A' AND firm_email_id!='$user_id' And folder_id!=''");
            $query_2 = $this->db->get();
            if ($query_2->num_rows() > 0) {
                $data = array('firm_name' => array(), 'firm_id' => array(), 'boss_id' => array());
                foreach ($query_2->result() as $row) {
                    $data['firm_name'] = $row->firm_name;
                    $data['firm_id'] = $row->firm_id;
                    $data['boss_id'] = $row->boss_id;
                    $response['frim_data'][] = ['firm_name' => $row->firm_name, 'email' => $row->firm_email_id, 'file_id' => $file_id];
                }
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function get_emp_name() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        } else {

            $firm_id = '';
        }
        $tran_details = $this->db->query("select * from nas_transaction_all where  transaction_id=(select folder_id from partner_header_all where firm_id='$firm_id')")->row();
        if ($tran_details != null) {
            $file_id = $tran_details->file_id;
        } else {
            $file_id = '';
        }

        $query_2 = $this->db->query("select (select group_concat(folder_id) from partner_header_all where firm_id=firm_id)as folder_id, firm_id,user_id,user_name,e_folder_id from user_header_all where user_type='4'  and linked_with_boss_id in (select boss_id from partner_header_all where reporting_to = (select boss_id from partner_header_all where firm_email_id='$user_id'))")->result();
        //echo $this->db->last_query();
        //var_dump($query_2);
        $firm_id = array();
        $user_name = array();
        $user_id = array();
        $unique_firm = array();
        if ($query_2 != null) {

            foreach ($query_2 as $row) {
                var_dump(explode(',', $row->folder_id));
                $firm_id = $row->firm_id;
                $unique = array_unique($row->firm_id);
                var_dump($unique);
                $user_name = $row->user_name;
                $user_id = $row->user_id;
                $unique_firm[] = $firm_id;

                $unique_firm[] = $user_name;
                $unique_firm[] = $user_id;
            }
            //$unique=array_unique($firm_id);
            var_dump($unique_firm);

            $response['user_data'][] = ['user_name' => $user_name, 'user_id' => $user_id, 'file_id' => $file_id];
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

    public function insert_folder_id() {

        $id = $this->input->post_get('id');
        $file_transaction_id = $this->input->post_get('file_transaction_id');
        $index = $this->input->post_get('index');


        switch ($index) {
            case "1":
                $this->db->where('firm_id', $id);
                $this->db->update('partner_header_all', array('folder_id' => $file_transaction_id));
                break;
            case "2":
                $this->db->where('customer_id', $id);

                $this->db->update('customer_header_all', array('c_folder_id' => $file_transaction_id));
                break;
            case "3":
                $this->db->where('user_id', $id);
                $this->db->update('user_header_all', array('e_folder_id' => $file_transaction_id));
                break;
            default:
                echo "Your favorite color is neither red, blue, nor green!";
        }
    }

    public function get_finial_folder_id_for_create_firm_in_hq() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        } else {

            $firm_id = '';
        }
        $q = $this->db->query("select file_id from nas_transaction_all where transaction_id=(select folder_id from partner_header_all where firm_id='$firm_id')")->row();

        if ($this->db->affected_rows() > 0) {
            $response['parent_id'] = $q->file_id;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['parent_id'] = "";
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

}

?>