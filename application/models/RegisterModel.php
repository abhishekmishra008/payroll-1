<?php

class RegisterModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'user_header_all';
        $this->opt_table = 'otp_header_all';
        $this->attendance_table = 'employee_attendance';
    }

    public function CheckUserExistance($check_data) {
        $this->db->select(array('u.user_id', 'u.firm_id', 'u.mobile_no', 'u.applicable_status', "f.lattitude"
            , "f.logitude", "f.radius", "f.time_betn_two_punch", "f.differance_bet_pi_po"));
        $this->db->where($check_data);
        $this->db->join("firm_location f", "on f.firm_id=u.firm_id", "left");
        $result = $this->db->get($this->table . ' u');
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function CreateOtp() {
        $opt = rand(1000, 10000);
        $this->db->select('otp');
        $this->db->from('otp_header_all');
        $this->db->where('otp', $opt);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return $this->CreateOtp($id);
        } else {
            return $opt;
        }
    }

    public function SaveOpt($data, $user_id) {
        $this->db->where("created_by", $user_id)->delete($this->opt_table);
        $this->db->insert($this->opt_table, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function sendSms($no, $message) {
        //Prepare you post parameters
        $postData = array(
            'authkey' => "178904AVN94GK259e5e87b",
            'mobiles' => $no,
            'message' => urlencode($message),
            'sender' => 'GBTRMT',
            'route' => 4,
            'response' => 'json'
        );

        $url = "https://control.msg91.com/sendhttp.php";
        //$url = "https://control.msg91.com/api/sendhttp.php?authkey=138906AXhOHtw6e588c8fda&mobiles=8319547270&message=Test&sender=MSGIND&route=4&country=91&schtime=2017-01-30%2000:00:00&response=Hey";

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = json_decode(curl_exec($ch));

        curl_close($ch);
        if ($output->type == "success") {
            return 1;
        } else {
            return 0;
        }
    }

    public function CheckOptValid($data) {
        $this->db->select(array('*'));
        $this->db->where($data);
        $result = $this->db->get($this->opt_table);
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function CheckUserLogin($data) {
        $this->db->select(array('*'));
        $this->db->where($data);
        $result = $this->db->get('employee_attendance_leave');
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function getUserPunchDetails($data) {
        $this->db->select(array('date', 'user_id', 'punch_in', 'punch_out', 'firm_id', 'punch_regularised_status'));
        $this->db->where($data);
        $result = $this->db->get('employee_attendance_leave');
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function attendanceRequest($firm_id, $user_id, $current_date, $action, $requestFrom) {
        return $this->db->query("call attendance_request('" . $firm_id . "','" . $user_id . "','" . $current_date . "'," . $action . "," . $requestFrom . ")")->row();
    }

    public function LogoutUser($where_id, $data) {
        $this->db->set($data);
        $this->db->where($where_id);
        $this->db->update('employee_attendance');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

	function sendEmail($to, $subject, $message) {
//        echo 111;die;
		$from_email = 'noreply@gbtech.in'; //change this to yours
		$this->load->library('email');
//		echo 1112;die;
		//configure email settings
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'gbtech.in'; //smtp host name
		$config['smtp_port'] = '587'; //smtp port number 587 on server or 465
		$config['smtp_user'] = $from_email;
		$config['smtp_pass'] = 'Gbtech@123$'; //$from_email password
		$config['smtp_crypto'] = 'tls';
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n"; //use double quotes
		$this->email->initialize($config);

		//send mail
		$this->email->from($from_email, 'RMT Team');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);

		if ($this->email->send()) {
			return true;
		} else {
			return false;
		}
	}
	function sendEmail1($to, $subject, $message) {
         $from_email = 'it-support@docango.com'; //change this to yours

				$config['protocol'] = 'smtp';
                $config['smtp_host'] = 'ssl://smtp.mail.us-east-1.awsapps.com'; //smtp host name
                $config['smtp_port'] = '465'; //smtp port number 587 on server
                $config['smtp_user'] = $from_email;
                 $config['smtp_pass'] = 'Support#T20@docango';  //$from_email password
                $config['mailtype'] = 'html';
                $config['charset'] = 'iso-8859-1';
                $config['wordwrap'] = TRUE;
                $config['newline'] = "\r\n";
                $this->email->SMTPAuth = true;
                $new_message_id = md5(time() . rand()) . $_SERVER['HTTP_HOST'];
                $config['Message-ID'] = $new_message_id;
                $this->email->initialize($config);
                $this->email->from($from_email);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
