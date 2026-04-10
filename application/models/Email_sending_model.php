<?php

class email_sending_model extends CI_Model {

    //send verification email to user's email id
    function sendEmail($user_type, $boss_name, $firm_name, $firm_no_of_permitted_offices, $firm_no_of_employee, $firm_no_of_customers, $firm_email_id, $password, $trigger_by) {
        // $from_email = 'it-support@docango.com'; //change this to yours
        $from_email = 'noreply@gbtech.in'; //change this to yours
        $subject = 'RMT Registration';


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
        $link = "https://payroll.ecovisrkca.com/";
        //Your message to send, Add URL encoding here.
        if ($trigger_by == "superuser") {
            if ($user_type == 2) {
                $message = ("Welcome $boss_name$dot $firm_name is successfully registered with $firm_no_of_permitted_offices Offices$comma $firm_no_of_employee Employees $comma $firm_no_of_customers Customers$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
            } else if ($user_type == 3) {
                $message = ("Welcome $boss_name$dot $firm_name is successfully registered with $firm_no_of_employee Employees$comma $firm_no_of_customers Customers$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
            }
        } else if ($trigger_by == "hq") {
            $message = ("Welcome $boss_name for the office of $firm_name with $firm_no_of_employee Employees$comma $firm_no_of_customers Customers$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
        } else if ($trigger_by == "human_resource") {
            $message = ("Welcome $boss_name for the office of $firm_name$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
        } else if ($trigger_by == "client") {

            $result1 = $this->db
                ->select('firm_name')
                ->from('partner_header_all')
                ->where('firm_id', $firm_name)
                ->get();
            if ($result1->num_rows() > 0) {
                $data1 = $result1->row();
                $name = $data1->firm_name;
            }

            $message = ("Welcome $boss_name for the office of $firm_name$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
        }

        $config['protocol'] = 'smtp';
        // $config['smtp_host'] = 'ssl://smtp.mail.us-east-1.awsapps.com'; //smtp host name
        $config['smtp_host'] = 'ssl://vashi.rkca.net'; //smtp host name
        $config['smtp_port'] = '465'; //smtp port number 587 on server
        $config['smtp_user'] = $from_email;
        // $config['smtp_pass'] = 'Support#T20@docango';  //$from_email password
        $config['smtp_pass'] = 'Gbtech@123$';  //$from_email password
        $config['mailtype'] = 'utf-8';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n";
        $this->email->SMTPAuth = true;
        $new_message_id = md5(time() . rand()) . $_SERVER['HTTP_HOST'];
        $config['Message-ID'] = $new_message_id;
        $this->email->initialize($config);
        $this->email->from($from_email);
        $this->email->to($firm_email_id);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    //send verification email to user's email id
    function CustomersendEmail($customer_email, $customer_no, $attached_due_date_id, $firm_id, $customer_name) {

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
        $link = "http://35.154.163.72/rmt/";
//        $link = "http://localhost/rmt_18/";


        $result = $this->db->query("SELECT firm_name FROM `partner_header_all` WHERE  firm_id='$firm_id' ");
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $firm_name = $data->firm_name;
        }

        $e = substr_count($attached_due_date_id, ",");
        if ($e == 0) {

            $result1 = $this->db->query("SELECT due_date_name from due_date_header_all where firm_id='$firm_id' and due_date_id='$attached_due_date_id' ");
            if ($result1->num_rows() > 0) {
                $data1 = $result1->row();
                $name = $data1->due_date_name;
                $message = ("Hello $customer_name.<br> New Due date $name has been attached with you.<br> Please contact $firm_name for any queries.<br>");
            } else {
                $message = ("Hello $customer_name.<br>  Please contact $firm_name for any queries.<br>");
            }
        } else {
            $a = explode(',', $attached_due_date_id);
            $b = "";
            foreach ($a as $value) {

                $result2 = $this->db->query("SELECT due_date_name from due_date_header_all where firm_id='$firm_id' and due_date_id='$value' ");
                if ($result2->num_rows() > 0) {
                    $data2 = $result2->row();
                    $b .= $data2->due_date_name . ",";
                }
                $c = rtrim($b, ",");
            }
            $message = ("Hello $customer_name.<br> New Due dates $c have been attached with you.<br> Please contact $firm_name for any queries.");
        }

        $from_email = 'value@gbtech.in'; //change this to yours
        $subject = 'RMT Due Dates';

        $this->load->library('email');

        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.gbtech.in'; //smtp host name
        $config['smtp_port'] = '587'; //smtp port number 587 on server
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = 'gbtech@2019'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);

        //send mail
        $this->email->from($from_email, 'RMT Team');
        $this->email->to($customer_email);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    function EmployeesendEmail($emp_name, $emp_email) {

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
        $link = "http://35.154.163.72/rmt/";
//        $link = "http://localhost/rmt_18/";




        $message = ("Hello $emp_name.<br> New Survey have been sent to you.<br> Please login your dashboard for any queries.");


        $from_email = 'value@gbtech.in'; //change this to yours
        $subject = 'About Survey Information';


        $this->load->library('email');

        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.gbtech.in'; //smtp host name
        $config['smtp_port'] = '587'; //smtp port number 587 on server
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = 'gbtech@2019'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);

        //send mail
        $this->email->from($from_email, 'RMT Team');
        $this->email->to($emp_email);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function SendReminderSms($auth_key, $customer_no, $customer_name, $message) {
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
        $link = "http://35.154.163.72/rmt/";
//        $link = "http://localhost/rmt_18/";
        //Your message to send, Add URL encoding here.

        $msg = urlencode("$message");

        $response_type = 'json';

        //Define route
        $route = "4";

        //Prepare you post parameters
        $postData = array(
            'authkey' => $auth_key,
            'mobiles' => $customer_no,
            'message' => $msg,
            'sender' => 'GBTRMT',
            'route' => $route,
            'response' => $response_type
        );

//API URL
        $url = "https://control.msg91.com/sendhttp.php";
//        $url = "https://control.msg91.com/api/sendhttp.php?authkey=138906AXhOHtw6e588c8fda&mobiles=8319547270&message=Test&sender=MSGIND&route=4&country=91&schtime=2017-01-30%2000:00:00&response=Hey";
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

    public function SendReminderSmstoemp($auth_key, $emp_mobile, $emp_name, $message) {
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
        $link = "http://35.154.163.72/rmt/";
//        $link = "http://localhost/rmt_18/";
        //Your message to send, Add URL encoding here.

        $msg = urlencode("$message");

        $response_type = 'json';

        //Define route
        $route = "4";

        //Prepare you post parameters
        $postData = array(
            'authkey' => $auth_key,
            'mobiles' => $emp_mobile,
            'message' => $msg,
            'sender' => 'GBTRMT',
            'route' => $route,
            'response' => $response_type
        );

//API URL
        $url = "https://control.msg91.com/sendhttp.php";
//        $url = "https://control.msg91.com/api/sendhttp.php?authkey=138906AXhOHtw6e588c8fda&mobiles=8319547270&message=Test&sender=MSGIND&route=4&country=91&schtime=2017-01-30%2000:00:00&response=Hey";
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

    public function SendReminderEmail($email_id, $customer_name, $subject, $message) {
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
        $link = "http://35.154.163.72/rmt/";

        $msg = "Hello $customer_name.$message";

        $this->load->library('email');

        $from_email = 'value@gbtech.in'; //change this to yours
        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.gbtech.in'; //smtp host name
        $config['smtp_port'] = '587'; //smtp port number 587 on server
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = 'gbtech@2019'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);

        //send mail
        $this->email->from($from_email, 'RMT Team');
        $this->email->to("$email_id");
        $this->email->subject("$subject");
        $this->email->message($msg);
        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            return false;
        }
    }

    //add this in email_sending_model
    function SendTaskReminderEmail($contact_person_mail_id, $msg, $subject) {

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
        $link = "http://35.154.163.72/rmt/";
//        $link = "http://localhost/rmt_18/";
//        $subject = 'RMT Due Dates';

        $this->load->library('email');
        $from_email = 'value@gbtech.in'; //change this to yours
        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.gbtech.in'; //smtp host name
        $config['smtp_port'] = '587'; //smtp port number 587 on server , on local 25 port no
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = 'gbtech@2019'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);

        //send mail
        $this->email->from($from_email, 'RMT Team');
        $this->email->to($contact_person_mail_id);
        $this->email->subject($subject);
        $this->email->message($msg);
        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            return false;
        }
    }

    public function enquirySendSMS($ddl_cust_name, $service_type, $serv, $enquiry_contact_no, $enquiry_remark, $firm_id, $boss_id) {
        $result_service = $this->db->query("SELECT service_name FROM `services_header_all` WHERE  service_id='$service_type'");
        if ($result_service->num_rows() > 0) {
            $data_serv = $result_service->row();
            $service_type_name = $data_serv->service_name;
        }

        $adata = array();
        $temp_serv = explode(',', $serv);
        foreach ($temp_serv as $row_serv) {
            $result_service1 = $this->db->query("SELECT service_name FROM `services_header_all` WHERE  service_id='$row_serv' ");
            if ($result_service1->num_rows() > 0) {
                $data_serv1 = $result_service1->row();
                $service_name = $data_serv1->service_name;
                $adata[] = $service_name;
            }

            $temp_serv_name = implode(',', $adata);
        }

        $result = $this->db->query("SELECT * FROM `partner_header_all` WHERE  firm_id='$firm_id'");
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $firm_name = $data->firm_name;
            $firm_email_id = $data->firm_email_id;
        }

        $result_hq = $this->db->query("SELECT * FROM `partner_header_all` WHERE  boss_id='$boss_id' and reporting_to='$boss_id'");
        if ($result_hq->num_rows() > 0) {
            $data_hq = $result_hq->row();
            $firm_name_hq = $data->firm_name;
            $firm_email_id_hq = $data->firm_email_id;
        }

        $result_cust = $this->db->query("SELECT customer_name,customer_email_id FROM `customer_header_all` WHERE  customer_id='$ddl_cust_name'");
        if ($result_cust->num_rows() > 0) {
            $data_cust = $result_cust->row();
            $customer_name = $data_cust->customer_name;
        }


        $auth_key = "178904AVN94GK259e5e87b";
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
        $link = "http://35.154.163.72/rmt/";

        //Your message to send, Add URL encoding here.

        $message = ("Hello $customer_name $dot You are enquiry has been generated enquiry in $service_type_name for $temp_serv_name service.$new_line Please contact $firm_name for any queries.");

        $msg = urlencode("$message");

        $response_type = 'json';

        //Define route
        $route = "4";

        //Prepare you post parameters
        $postData = array(
            'authkey' => $auth_key,
            'mobiles' => $customer_no,
            'message' => $msg,
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

    //send verification email to user's email id
    function enquirySendEmailFirm($ddl_cust_name, $service_type, $serv, $enquiry_email_id, $enquiry_remark, $firm_id, $boss_id) {
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
        $link = "http://35.154.163.72/rmt/";

        $result_service = $this->db->query("SELECT service_name FROM `services_header_all` WHERE  service_id='$service_type'");
        if ($result_service->num_rows() > 0) {
            $data_serv = $result_service->row();
            $service_type_name = $data_serv->service_name;
        }

        $adata = array();
        $temp_serv = explode(',', $serv);
        foreach ($temp_serv as $row_serv) {
            $result_service1 = $this->db->query("SELECT service_name FROM `services_header_all` WHERE  service_id='$row_serv' ");
            if ($result_service1->num_rows() > 0) {
                $data_serv1 = $result_service1->row();
                $service_name = $data_serv1->service_name;
                $adata[] = $service_name;
            }

            $temp_serv_name = implode(',', $adata);
        }

        $result = $this->db->query("SELECT * FROM `partner_header_all` WHERE  firm_id='$firm_id'");
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $firm_name = $data->firm_name;
            $firm_email_id = $data->firm_email_id;
        }

        $result_hq = $this->db->query("SELECT * FROM `partner_header_all` WHERE  boss_id='$boss_id' and reporting_to='$boss_id'");
        if ($result_hq->num_rows() > 0) {
            $data_hq = $result_hq->row();
            $firm_name_hq = $data->firm_name;
            $firm_email_id_hq = $data->firm_email_id;
        }

        $result_cust = $this->db->query("SELECT customer_name,customer_email_id FROM `customer_header_all` WHERE  customer_id='$ddl_cust_name'");
        if ($result_cust->num_rows() > 0) {
            $data_cust = $result_cust->row();
            $customer_name = $data_cust->customer_name;
        } else {
            $result_cust1 = $this->db->query("SELECT attribute_1_value,attribute_2_value,attribute_3_value FROM `enquiry_header_all` WHERE  customer_id='$ddl_cust_name'");
            if ($result_cust1->num_rows() > 0) {
                $data_cust1 = $result_cust1->row();
                $customer_name = $data_cust1->attribute_3_value;
                $customer_email = $data_cust1->attribute_1_value;
            }
        }


        $message = ("Hello $firm_name. $customer_name , have enquiry in $service_type_name for $temp_serv_name services.<br>.");

        $from_email = 'value@gbtech.in'; //change this to yours
        $subject = 'RMT Enquiry';
        $this->load->library('email');
        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.gbtech.in'; //smtp host name
        $config['smtp_port'] = '587'; //smtp port number 587 on server
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = 'gbtech@2019'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);
        $this->email->from($from_email, 'RMT Team');
        $this->email->to($firm_email_id_hq, $firm_email_id);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    //send verification email to user's email id
    function enquirySendEmail($ddl_cust_name, $service_type, $serv, $enquiry_email_id, $enquiry_remark, $firm_id, $boss_id) {
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
        $link = "http://35.154.163.72/rmt/";

        $result_service = $this->db->query("SELECT service_name FROM `services_header_all` WHERE  service_id='$service_type' ");
        if ($result_service->num_rows() > 0) {
            $data_serv = $result_service->row();
            $service_type_name = $data_serv->service_name;
        }

        $adata = array();
        $temp_serv = explode(',', $serv);
        foreach ($temp_serv as $row_serv) {
            $result_service1 = $this->db->query("SELECT service_name FROM `services_header_all` WHERE  service_id='$row_serv' ");
            if ($result_service1->num_rows() > 0) {
                $data_serv1 = $result_service1->row();
                $service_name = $data_serv1->service_name;
                $adata[] = $service_name;
            }

            $temp_serv_name = implode(',', $adata);
        }


        $result = $this->db->query("SELECT * FROM `partner_header_all` WHERE  firm_id='$firm_id'");
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $firm_name = $data->firm_name;
            $firm_email_id = $data->firm_email_id;
        }

        $result_hq = $this->db->query("SELECT * FROM `partner_header_all` WHERE  boss_id='$boss_id' and reporting_to='$boss_id'");
        if ($result_hq->num_rows() > 0) {
            $data_hq = $result_hq->row();
            $firm_name_hq = $data->firm_name;
            $firm_email_id_hq = $data->firm_email_id;
        }


        $result_cust = $this->db->query("SELECT customer_name,customer_email_id FROM `customer_header_all` WHERE  customer_id='$ddl_cust_name'");
        if ($result_cust->num_rows() > 0) {
            $data_cust = $result_cust->row();
            $customer_name = $data_cust->customer_name;
            $customer_email = $data_cust->customer_email_id;
        } else {
            $result_cust1 = $this->db->query("SELECT attribute_1_value,attribute_2_value,attribute_3_value FROM `enquiry_header_all` WHERE  customer_id='$ddl_cust_name'");
            if ($result_cust1->num_rows() > 0) {
                $data_cust1 = $result_cust1->row();
                $customer_name = $data_cust1->attribute_3_value;
                $customer_email = $data_cust1->attribute_1_value;
            }
        }

        $message = ("Hello $customer_name.<br> You are enquiry has been generated enquiry in $service_type_name for $temp_serv_name service. <br> Please contact $firm_name for any queries.<br>");

        $from_email = 'value@gbtech.in'; //change this to yours
        $subject = 'RMT Enquiry';

        $this->load->library('email');

        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.gbtech.in'; //smtp host name
        $config['smtp_port'] = '587'; //smtp port number 587 on server
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = 'gbtech@2019'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);
        $this->email->from($from_email, 'RMT Team');
        $this->email->to($customer_email);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function forget_pass_mail($email_id, $user_name) {

		$password = rand(100, 1000);
        $hash_password = password_hash($password, PASSWORD_BCRYPT);
		$data=array('password'=>$password,'hash_password'=>$hash_password);
		$update_password=$this->db->update('user_header_all',$data,array('email' => $email_id));

		if($update_password == TRUE) {
            $message = ("Hello $user_name , Your New Password is $password.");
            $subject = 'New Password';
            $from_email = 'noreply@gbtech.in'; //change this to yours
            $this->load->library('email');
            //configure email settings
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'ssl://vashi.rkca.net'; //smtp host name
            $config['smtp_port'] = '465'; //smtp port number 587 on server
            $config['smtp_user'] = $from_email;
            $config['smtp_pass'] = 'Gbtech@123$'; //$from_email password
            // $config['smtp_crypto'] = 'ssl';
            $config['mailtype'] = 'html';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n"; //use double quotes
            $this->email->initialize($config);

            //send mail
            $this->email->from($from_email, 'RMT Team');
            $this->email->to($email_id);
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function billingCycleEmail($customer_name, $customer_email, $task_assignment_name, $task_name, $billing, $amount) {


        $message = ("Hello $customer_name.<br> You are $task_assignment_name of $task_name $billing $amount.<br>");

        $from_email = 'value@gbtech.in'; //change this to yours
        $subject = 'RMT Enquiry';

        $this->load->library('email');

        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.gbtech.in'; //smtp host name
        $config['smtp_port'] = '587'; //smtp port number 587 on server
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = 'gbtech@2019'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);
        //send mail
        $this->email->from($from_email, 'RMT Team');
        $this->email->to($customer_email);
//        $this->email->cc('prachi@gold-berries.com');
//        $this->email->bcc();
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    //send verification SMS to user's customer_contact_no
    function billingCycleSMS($customer_name, $customer_no, $task_assignment_name, $task_name, $billing, $amount) {

        $auth_key = "178904AVN94GK259e5e87b";
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
        $link = "http://35.154.163.72/rmt/";

        //Your message to send, Add URL encoding here.

        $message = ("Hello $customer_name. You are $task_assignment_name assignment of $task_name task $billing billing amount is Rs.$amount/-.");

        $msg = urlencode("$message");

        $response_type = 'json';

        //Define route
        $route = "4";

        //Prepare you post parameters
        $postData = array(
            'authkey' => $auth_key,
            'mobiles' => $customer_no,
            'message' => $msg,
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

}
