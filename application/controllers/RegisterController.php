<?php

class RegisterController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('RegisterModel');
        date_default_timezone_set("Asia/Kolkata");
    }

    public function CheckUserExistance() {
        //status information
        //200 userfound and otp sent on mobile number
        //201 Request Headers Missing
        //202 Invalid Credentials
        //203 Error Occured During Sending otp
        if (!empty($this->input->get_post('username')) && !empty($this->input->get_post('password'))) {
            $data = array('email' => $this->input->get_post('username'), 'password' => $this->input->get_post('password'));
            $result = $this->RegisterModel->CheckUserExistance($data);

            if ($result == false) {
                $response['status'] = 202;
                $response['body'] = 'Invalid Credentials';
            } else {
                //$response['user_id'] = $result[0]->user_id;
                //$response['firm_id'] = $result[0]->firm_id;
                $user_data = array('user_id' => $result->user_id, 'firm_id' => $result->firm_id,
                    'applicable_status' => $result->applicable_status,
                    'latitude' => $result->lattitude, 'logitude' => $result->logitude, 'radius' => $result->radius, 'time_betn_two_punch' => $result->time_betn_two_punch, 'differance_bet_pi_po' => $result->differance_bet_pi_po);
                $response['status'] = 200;
                $response['body'] = (object) $user_data;
                $otp = $this->RegisterModel->CreateOtp();
                //echo date("Y-m-d H:i:s");
                //echo date("Y-m-d H:i:s", strtotime('+15 minute'));
                $opt_data = array('otp' => $otp, 'created_on' => date("Y-m-d H:i:s"), 'created_by' => $result->user_id, 'expire_time' => date("Y-m-d H:i:s", strtotime('+15 minute')));
                $opt_status = $this->RegisterModel->SaveOpt($opt_data, $result->user_id);
                if ($opt_status != false) {
                    $this->RegisterModel->sendSms($result->mobile_no, $otp . ' is your one time password sent by Payroll System . It is valid for 15 minutes.Do not share your Otp with anyone');
                    //$this->RegisterModel->sendEmail($result->mobile_no, $otp . ' is your one time password sent by Payroll System . It is valid for 15 minutes.Do not share your Otp with anyone');
                } else {
                    $response['status'] = 203;
                    $response['body'] = 'Error to send Otp';
                }
            }
        } else {
            $response['status'] = 201;
            $response['body'] = 'Request Headers Missing';
        }
        echo json_encode($response);
    }

    public function CheckOptValid() {
        //status informations-:
        //200 Valid Otp
        //201 Request Headers Missing
        //202 Expried Opt
        if (!empty($this->input->get_post('otp')) && !empty($this->input->get_post('user_id')) && !empty($this->input->get_post('user_p'))) {
            $data = array('otp' => $this->input->get_post('otp'), 'created_by' => base64_decode($this->input->get_post('user_id')));
            $opt_status = $this->RegisterModel->CheckOptValid($data);

            if ($opt_status != false) {
                $response['status'] = 200;
                $sms = array("message" => 'Valid Otp');
                $this->load->model('loginmodel');
                $user_id = base64_decode($this->input->get_post('user_id'));
                $passwrod = base64_decode($this->input->get_post('user_p'));
                $result = $this->loginmodel->login_validation($user_id, $passwrod);

                $firm_logo = $result['firm_logo'];
                $user_name = $result['user_name'];
                $user_type = $result['user_type'];

                $value = $firm_logo . "," . $user_name . "," . $passwrod . "," . $user_id . ",1,".$user_type;
                $name = 'rmt_tool';
                $expire = time() + (86400 * 30); //86400 = 1 day
                $path = '/';

                setcookie($name, $value, (int) $expire, $path);
                $response["cookieValue"] = $value;
                $response['body'] = (object) $sms;
            } else {
                $response['status'] = 202;
                $sms = array("message" => 'Expired Otp');
                $response['body'] = (object) $sms;
            }
        } else {
            $response['status'] = 201;
            $sms = array("message" => 'Reuqesr Headers Missing');
            $response['body'] = (object) $sms;
        }
        echo json_encode($response);
    }

    public function setcookie($firm_logo, $user_name, $password, $user_id, $isvalid) {
        // $firm_logo = $user_id;
        $name = 'rmt_tool';
        $value = $firm_logo . "," . $user_name . "," . $password . "," . $user_id . "," . $isvalid;
        $expire = time() + (86400 * 30); //86400 = 1 day
        $path = '/';
        $secure = TRUE;

        setcookie($name, $value, $expire, $path);
    }

    public function Login() {
        //status info
        //200 Login Sucessful
        //201 Request Header Missing
        //202 User Already Login
        //203 Logout Successful
        //204 User Already Have Logout
        //205 Error Occured During Login
        //206 Error Occurd During Logout
        if (!empty($this->input->get_post('firm_id')) && !empty($this->input->get_post('user_id')) && !empty($this->input->get_post('action')) && !empty($this->input->get_post('reqForm')) && !empty($this->input->get_post('requestDate'))) {
            $firm_id = $this->input->get_post('firm_id');
            $user_id = $this->input->get_post('user_id');
            $action = $this->input->get_post('action');
            $req_from = $this->input->get_post('reqForm');
            $currentDate = $this->input->get_post('requestDate');
            $result = $this->RegisterModel->attendanceRequest($firm_id, $user_id, $currentDate, $action, $req_from);
            if ($result != null) {
                $response['status'] = 200;
                $response['body'] = $result;
            } else {
                $response['status'] = 201;
                $response['body'] = "Operation Failed Try again";
            }
        } else {
            $response['status'] = 201;
            $sms = array("message" => 'Request header missing');
            $response['body'] = (object) $sms;
        }
        echo json_encode($response);
    }

    public function getUserDetails() {
        if (!empty($this->input->get_post('firm_id')) && !empty($this->input->get_post('user_id')) && !empty($this->input->get_post('requestDate'))) {
            $firm_id = $this->input->get_post('firm_id');
            $user_id = $this->input->get_post('user_id');
            $currentDate = $this->input->get_post('requestDate');
            $result = $this->RegisterModel->getUserPunchDetails(array("firm_id" => $firm_id, "user_id" => $user_id, "date" => $currentDate));
            if ($result != null) {
                $response['status'] = 200;
                $response['body'] = $result;
            } else {
                $response['status'] = 201;
                $response['body'] = "Not Data Found In Attendance";
            }
        } else {
            $response['status'] = 201;
            $sms = array("message" => 'Request header missing');
            $response['body'] = (object) $sms;
        }
        echo json_encode($response);
    }

}

?>