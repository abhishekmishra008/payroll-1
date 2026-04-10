<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nas_modal extends CI_Model {

    public function update_nas_user_id($data, $where_data) {
        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('auth_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update_nas_device_details($data, $where_data) {
        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('auth_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update_access_token($data, $where_data) {
        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('auth_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_parent_folder_id($user_id) {
        $result = $this->db->query("select folder_id from partner_header_all where firm_id='$user_id'")->row();

        if ($result != null) {

            return $parent_folder_id = $result->folder_id;
        } else {
            return false;
        }
    }

    public function get_access_code($user_id) {
        $get_access_token = $this->db->query("select access_token from auth_header_all where user_id='$user_id' and status='1'");

        if ($get_access_token->num_rows() > 0) {
            $access_token_value = $get_access_token->result();
            return $access_token_value[0]->access_token;
        } else {
            return false;
        }
    }

    public function save_auth_details($data, $where_data) {
        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('auth_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_refresh_token($hq_user_id) {
        if ($hq_user_id != "") {
            $get_access_token = $this->db->query("select refresh_token from auth_header_all where user_id='$hq_user_id' and status='1'");
            if ($get_access_token->num_rows() > 0) {
                $access_token_value = $get_access_token->result();
                return $access_token_value[0]->refresh_token;
            } else {
                return false;
            }
        } else {

        }
    }

    public function get_hq_of_firm($firm_id, $for, $access_token_for_update) {
        if ($firm_id != "") {
            $get_hq = $this->db->query("select user_id  from user_header_all where email=(select firm_email_id from partner_header_all where boss_id=(select reporting_to from partner_header_all where firm_id='$firm_id'))");
            if ($get_hq->num_rows() > 0) {
                $row = $get_hq->result();
                if ($for == "access_token") {
                    $access_token = $this->get_access_code($row[0]->user_id);
                    return $access_token;
                }if ($for == "refresh_token") {
                    $refresh_token = $this->get_refresh_token($row[0]->user_id);
                    return $refresh_token;
                }if ($for == "token_status") {
                    $token_status = $this->get_token_status($row[0]->user_id);
                    return $token_status;
                }if ($for == "update_token") {
                    $data = array('access_token' => $access_token_for_update, 'token_expired' => 0);
                    $where_data = array('user_id' => $row[0]->user_id, 'status' => 1);
                    $update_status = $this->update_access_token1($data, $where_data);
                    return $update_status;
                }
                if ($for == "nas_status") {
                    $status = $this->get_nas_config_or_not($row[0]->user_id);
                    return $status;
                }
                if ($for == "proxy_url") {
                    $proxy_url = $this->get_proxy_url($row[0]->user_id);
                    return $proxy_url;
                }
            } else {
                return false;
            }
        }
    }

    public function get_hq_user_id($firm_id) {

        $get_hq = $this->db->query("select user_id  from user_header_all where email=(select firm_email_id from partner_header_all where boss_id=(select reporting_to from partner_header_all where firm_id='$firm_id'))");



        if ($get_hq->num_rows() > 0) {

            $row = $get_hq->result();
            return $row[0]->user_id;
        } else {
            return false;
        }
    }

    public function get_token_status($hq_user_id) {
        if ($hq_user_id != "") {
            $get_access_token = $this->db->query("select token_expired from auth_header_all where user_id='$hq_user_id' and status='1'");
            if ($get_access_token->num_rows() > 0) {
                $access_token_value = $get_access_token->result();
                return $access_token_value[0]->token_expired;
            } else {
                return false;
            }
        } else {

        }
    }

    public function update_access_token1($data, $where_data) {
        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('auth_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function genrate_file_transaction_id() {
        $transaction_id = 'Tran_' . rand(100, 100000);
        $this->db->select('*');
        $this->db->from('nas_transaction_all');
        $this->db->where('transaction_id', $transaction_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return genrate_file_transaction_id();
        } else {
            return $transaction_id;
        }
    }

    public function genrate_access_token_from_refresh_token($referesh_token) {
	
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
			//var_dump($access_token);
            return $access_token->access_token;
        }
    }

    public function get_proxy_url($hq_user_id) {
        if ($hq_user_id != "") {
            $get_access_token = $this->db->query("select proxy_url from auth_header_all where user_id='$hq_user_id' and status='1'");
            if ($get_access_token->num_rows() > 0) {
                $access_token_value = $get_access_token->result();
                return $access_token_value[0]->proxy_url;
            } else {
                return false;
            }
        } else {

        }
    }

    public function get_nas_config_or_not($user_id) {
        $q = $this->db->query("select status from auth_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() === 1) {
            $row = $q->result();
            return $row[0]->status;
        } else {
            return false;
        }
    }

    public function not_now($data, $where_data) {
        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('auth_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function genrate_folder_and_update_into_database($data, $where_data) {
        $this->db->set($data);
        $this->db->where($where_data);
        $this->db->update('partner_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function insert_transaction_data($data) {
        $this->db->insert('nas_transaction_all', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function nas_transaction($data) {
        $this->db->trans_start();
        try {
            $result = $this->db->insert('nas_transaction_all', $data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            return 0;
        }
        $this->db->trans_complete();
    }

    /*
     * @param first_id is optional second parameter is index {0:customer,1:hr,2:project,3:template project}
     * @return parent id
     */

    public function folder_creation($index, $firm_id = "") {
        //var_dump($firm_id);
        $this->load->model("Customer_model");
        if ($firm_id == "") {

            $session_date = $this->customer_model->get_firm_id();

            if (is_array($session_date)) {
                $firm_id = $session_date["firm_id"];
            } else {
                $firm_id = "";
            }
        }
        if ($firm_id != "") {
            $tran_details = $this->db->query("select * from nas_transaction_all where  transaction_id=(select folder_id from partner_header_all where firm_id='$firm_id')")->row();
            //echo $this->db->last_query();
			// var_dump($tran_details);
            if ($tran_details != null) {
                $file_id = explode('->', $tran_details->file_id);

                $file_id2 = explode(',', $file_id[1]);
                return $file_id2[$index];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isNasConfigure() {

        $this->load->model("Customer_model");

        $session_date = $this->customer_model->get_firm_id();
        if (is_array($session_date)) {

            $firm_id = $session_date["firm_id"];
            $result = $this->db->select("folder_id")->where("firm_id", $firm_id)->get("partner_header_all")->row();
            if (!is_null($result)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function check_access_token_is_expired_or_not($firm_id) {

        $access_token = $this->get_hq_of_firm($firm_id, $for = 'access_token', $access_token_for_update = '');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://authservice.mycloud.com/authservice/v2/auth0/user?userId=auth0|5d1c59a4974c920db9b51dea",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $access_token,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($status === 200) {
            return true;
        } else {

            return false;
        }
    }

}

?>