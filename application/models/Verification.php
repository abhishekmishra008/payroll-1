<?php

class Verification extends CI_Model {

    public function sendSms($auth_key, $customer_name, $no, $cust_email_id, $otp_number) {

        date_default_timezone_set('Asia/Kolkata');
        $date = Date('d-m-Y');
        $error_flag = 0;
        $otp_prefix = ':-';
        $new_line = "\n";
        $dot = ".";
        $colon = ":";
        $exclaim = "!!";
        $open_b = "[";
        $close_b = "]";
        $equal = ":";
        $and = "&";
        $comma = ",";
        $link = "http://gold-berries.com";

        //Your message to send, Add URL encoding here.



        $message = urlencode("Your otp is $otp_prefix $otp_number $new_line  ");



        $response_type = 'json';

        //Define route
        $route = "4";

        //Prepare you post parameters
        $postData = array(
            'authkey' => $auth_key,
            'mobiles' => $no,
            'message' => $message,
            'sender' => 'GBTRMT',
            'route' => $route,
            'response' => $response_type
        );

//API URL
        $url = "https://control.msg91.com/sendhttp.php";
        //$url = "https://control.msg91.com/api/sendhttp.php?authkey=138906AXhOHtw6e588c8fda&mobiles=8319547270&message=Test&sender=MSGIND&route=4&country=91&schtime=2017-01-30%2000:00:00&response=Hey";
// init the resource
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
        $output = curl_exec($ch);

        //Print error if any
        if (curl_errno($ch)) {
            $error_flag = 1;
        } else {
            $error_flag = 0;
        }

        curl_close($ch);
        return $error_flag;
    }

	public function get_customer_id()
	{
		
        $session_data = $this->session->userdata('login_session');
		
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
     $user_id = $session_data[0];
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $customer_details = $this->db->query("select * from customer_header_all where customer_id in (select c.customer_id from customer_header_all c left JOIN customer_employee_detail_all e on c.customer_id=e.customer_id where ( c.customer_email_id='$user_id'  or e.employee_email_id='$user_id'))")->result();

		//echo $this->db->last_query();
if($customer_details!=null)
{
	return $customer_details;
} else {
	return "";
}
		
	}
	
    function is_otp_available($otp) {

        $session_data = $this->session->userdata('otp_session');

        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $otp1 = $session_data['otp'];
        } else {
            $otp1 = $this->session->userdata('otp_session');
        }

        $otp_number = (string) $otp1;

        if ($otp_number == $otp) {

            return true;
        } else {
            return false;
        }
    }

}
