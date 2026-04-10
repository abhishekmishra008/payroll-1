<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class firm_model extends CI_Model {

//    public function add_firm_modal($data, $user_type, $get_user_id, $star_rating, $task_approve, $task_sign, $user_activity_status, $email_id, $city, $state, $country, $is_emp, $password, $hq_user_id, $prob_date_first, $prob_date_second, $training_period_first, $training_period_second, $joining_date, $designation, $reporting_to, $create_task_assign_permit, $create_due_date_task, $create_service_permit, $generate_enq_permit, $employee_permission, $customer_permission, $template_permission, $knowledge_permission, $warning_configuration_permission) {    //1 method
    public function add_firm_modal($data,$get_user_id,$boss_id, $user_type, $user_activity_status, $email_id,$password, $city, $state, $country, $hq_user_id, $prob_date_first, $prob_date_second, $training_period_first, $training_period_second, $joining_date, $designation, $reporting_to) {    //1 method
        // $get_password = rand(100, 1000);
        $q = $this->db->insert('partner_header_all', $data);
//s        echo $this->db->last_query();
        if ($this->db->affected_rows() == 1) {
            $data = array(
                
                'user_id' => $get_user_id,
                'linked_with_boss_id' => $boss_id,
//                'linked_with_boss_id' => $data['boss_id'],
                'user_type' => $user_type,
                'activity_status' => $user_activity_status,
                'email' => $data['firm_email_id'],
                'password' => $password,
                 'city' => $city,
                'state' => $state,
                'country' => $country,
//                'is_employee' => $is_emp,
                'senior_user_id' => $hq_user_id,
                'probation_period_start_date' => $prob_date_first,
                'probation_period_end_date' => $prob_date_second,
                'training_period_start_date' => $training_period_first,
                'training_period_end_date' => $training_period_second,
                 'date_of_joining' => $joining_date,
                 'designation_id' => $designation,
                'leave_approve_permission' => 1,
                'firm_id' => $data['firm_id'],
//                'user_type'=>3,
                'user_name' => $data['boss_name'],
//                'boss_id' => $reporting_to,
                'boss_id' => $boss_id,
                'mobile_no' => $data['boss_mobile_no'],
                
                
                'address' => $data['firm_address'],
                'created_by' => $email_id
                
                
               
               
		
                
                    );
//            var_dump($data);
            $this->db->insert('user_header_all', $data);
            $q_user = $this->db->affected_rows();
            if ($q_user === 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
	
    
    public function get_companies_hr($firm_id,$re_to) {
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
//         $query = $this->db->query("select partner_header_all.firm_name,partner_header_all.firm_id,partner_header_all.firm_address,partner_header_all.firm_activity_status,partner_header_all.firm_email_id from user_header_all inner join partner_header_all on partner_header_all.reporting_to = partner_header_all.boss_id where user_header_all.user_type ='3' and partner_header_all.firm_id='Firm_1001'");
         $query = $this->db->query("select * from partner_header_all where reporting_to = '$re_to' and reporting_to != boss_id");
           
//            echo $this->db->last_query();
             if ($query->num_rows() > 0) {
//                return $query->result();
                return $query;
            } else {
                return false;
            }
        
    }
	
	public function insert_nas($nas_data){
		$q = $this->db->insert('auth_header_all', $nas_data);
		if($this->db->affected_rows() === 1){
			return TRUE;
		}else{
			return FALSE;
		}
	}
    public function add_firm_modal_admin11($data, $user_type, $get_user_id, $user_activity_status, $email_id, $city, $state, $country, $is_emp, $password, $hq_user_id, $leave_approve_permitted, $req_leave, $prob_date_first, $prob_date_second, $training_period_first, $training_period_second, $joining_date, $designation, $reporting_to, $create_task_assign_permit, $create_duedate_permit, $create_service_permit, $generate_enq_permit, $employee_permission, $customer_permission, $template_permission, $knowledge_permission, $warning_configuration_permission) {    //1 method
        // $get_password = rand(100, 1000);
        $q = $this->db->insert('partner_header_all', $data);
        if ($this->db->affected_rows() == 1) {
            $data = array(
                'user_id' => $get_user_id,
                'linked_with_boss_id' => $data['boss_id'],
                'user_type' => $user_type,
                'is_employee' => $is_emp,
                'senior_user_id' => $hq_user_id,
                'leave_approve_permission' => 1,
                'firm_id' => $data['firm_id'],
                'user_name' => $data['boss_name'],
                'boss_id' => $reporting_to,
                'mobile_no' => $data['boss_mobile_no'],
                'email' => $data['firm_email_id'],
                'password' => $password,
                'address' => $data['firm_address'],
                'created_by' => $email_id,
                'probation_period_start_date' => $prob_date_first,
                'probation_period_end_date' => $prob_date_second,
                'training_period_start_date' => $training_period_first,
                'training_period_end_date' => $training_period_second,
                'designation_id' => $designation,
                'date_of_joining' => $joining_date,
                'create_task_assignment' => 1,
                'create_due_date_permission' => 1,
                'create_service_permission' => 1,
                'enquiry_generate_permission' => 1,
                'employee_permission' => 1,
                'customer_permission' => 1,
                'template_store_permission' => 1,
                'knowledge_store_permission' => 1,
                'activity_status' => $user_activity_status,
                'warning_conifg_permission' => $warning_configuration_permission,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'user_star_rating' => 10,
                'task_approve_permission' => 1,
                'task_sign_permission' => 1,
            );
            //print_r($data);
            $this->db->insert('user_header_all', $data);
            $q_user = $this->db->affected_rows();
            if ($q_user === 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    
     public function add_firm_modal_admin($data, $get_user_id,$user_type,$boss_id, $reporting_to,$user_activity_status, $email_id, $password, $city, $state, $country) {    //1 method
        // $get_password = rand(100, 1000);
        $q = $this->db->insert('partner_header_all', $data);
//         $user_type='2';
        if ($this->db->affected_rows() == 1) {
            $data = array(
                
                'user_id' =>$get_user_id,
                'user_type' => $user_type ,
                'linked_with_boss_id' => $boss_id,
//                'linked_with_boss_id' => $data['boss_id'],
                'leave_approve_permission' => 1,
                'firm_id' => $data['firm_id'],
                'mobile_no' => $data['boss_mobile_no'],
                'boss_id' => $reporting_to,
                'email' => $data['firm_email_id'],
                'password' => $password,
                'address' => $data['firm_address'],
                'created_by' => $email_id,
                'activity_status' => $user_activity_status,
                'city' => $city,
                'state' => $state,
                'country' => $country
            );
//            print_r($data);
            $this->db->insert('user_header_all', $data);
            $q_user = $this->db->affected_rows();
            if ($q_user === 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function update_firm_modal($due_date_id, $firm_id) {
        $this->db->set('attached_due_date_id', $due_date_id);
        $this->db->where('firm_id', $firm_id);
        $this->db->update('partner_header_all');
        if ($this->db->affected_rows() === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_firm_id() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $result = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$user_id' and activity_status=1");
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $user_data = array(
                'user_id' => $data->user_id,
                'firm_id' => $data->firm_id,
                'boss_id' => $data->linked_with_boss_id,
            );
            return $user_data;
        } else {
            return FALSE;
        }
    }

    function validate_firm_email($firm_email_id) { //validate firm email id allready exits or not
        // num rows example
        $query = $this->db->select('*');
        $query = $this->db->where("firm_email_id='$firm_email_id' AND firm_activity_status='A'");
        $query = $this->db->get('partner_header_all');
        $num = $query->num_rows();
        // here you can do something with $query
        if ($num == 1) {
            return TRUE;
        } else {
            $query = $this->db->select('*');
            $query = $this->db->where("email='$firm_email_id' AND activity_status='1'");
            $query = $this->db->get('user_header_all');
            $num = $query->num_rows();
            if ($num == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    function validate_firm_email_edit($firm_email_id,$firm_id) { //validate firm email id allready exits or not while updating office
        // num rows example
        $query = $this->db->select('*');
        $query = $this->db->where("firm_email_id='$firm_email_id' AND firm_activity_status='A' AND firm_id !='$firm_id'");
        $query = $this->db->get('partner_header_all');
        $num = $query->num_rows();
        // here you can do something with $query
        if ($num == 1) {
            return TRUE;
        } else {
            $query = $this->db->select('*');
            $query = $this->db->where("email='$firm_email_id' AND activity_status='1' AND firm_id !='$firm_id'");
            $query = $this->db->get('user_header_all');
            $num = $query->num_rows();
            if ($num == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
    function is_email_available($boss_email_id) {//Checking boss email id while creating office
        $this->db->where('boss_email_id', $boss_email_id);
        $query = $this->db->get("partner_header_all");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
     function is_edit_email_available($boss_email_id,$firm_id) {//Checking boss email id while Updating office
         $query = $this->db->query("select * from partner_header_all where firm_id!='$firm_id' and boss_email_id ='$boss_email_id'");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_branch_details($boss_id) { //branch details
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where("reporting_to='$boss_id' AND firm_activity_status='A' AND boss_id !='$boss_id'");
        $this->db->order_by("firm_name", "ASC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    function get_employee_details($boss_id) { //Employee details
        $this->db->select('*');
        $this->db->from('user_header_all');
        $this->db->where("boss_id='$boss_id' AND is_employee='1' AND user_type='4'");
        $this->db->order_by("firm_id", "ASC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    function getFirmEmployeeDetails($firm_id) { //Employee details
        $this->db->select('*');
        $this->db->from('user_header_all');
        $this->db->where("firm_id='$firm_id' AND is_employee='1' AND user_type='4'");
        $this->db->order_by("firm_id", "ASC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    function get_customer_details($boss_id) { //Customer details
        $this->db->select('*');
        $this->db->from('customer_header_all');
        $this->db->where("boss_id='$boss_id'");
        $this->db->order_by("firm_id", "ASC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    public function client_activity_status($firm_id, $status) {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        if ($status == 'A') {
            $firm_activity_status = 'D';
            $user_activity_status = '0';
        } else {
            $firm_activity_status = 'A';
            $user_activity_status = '1';
        }

        $result1 = $this->db->query("SELECT * from partner_header_all where firm_id='$firm_id' ");
        if ($result1->num_rows() > 0) {
            $data1 = $result1->row();
            $boss_id = $data1->boss_id;
            $email_id = $data1->firm_email_id;

            $data = array('modified_on' => date('Y-m-d H:i:s'));
            $this->db->set('firm_activity_status', $firm_activity_status);
            $this->db->where('reporting_to', $boss_id);
            $this->db->update('partner_header_all', $data);

            if ($this->db->affected_rows() > 0) {
                $data = array('modified_on' => date('Y-m-d H:i:s'), 'modified_by' => $user_id);
                $this->db->set('activity_status', $user_activity_status);
                $where = "created_by='$email_id' or email='$email_id'";
                $this->db->where($where);
                $this->db->update('user_header_all', $data);
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function hq_clients_activity_status($firm_id, $status) {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        if ($status == 'A') {
            $firm_activity_status = 'D';
            $user_activity_status = '0';
        } else {
            $firm_activity_status = 'A';
            $user_activity_status = '1';
        }


        $result1 = $this->db->query("SELECT * from partner_header_all where firm_id='$firm_id' ");
        if ($result1->num_rows() > 0) {
            $data1 = $result1->row();
            $boss_id = $data1->boss_id;
            $email_id = $data1->firm_email_id;

            $data = array('modified_on' => date('Y-m-d H:i:s'));
            $this->db->set('firm_activity_status', $firm_activity_status);
            $this->db->where('boss_id', $boss_id);
            $this->db->update('partner_header_all', $data);

            if ($this->db->affected_rows() > 0) {
                $data = array('modified_on' => date('Y-m-d H:i:s'), 'modified_by' => $user_id);
                $this->db->set('activity_status', $user_activity_status);
                $where = "created_by='$email_id' or email='$email_id'";
                $this->db->where($where);
                $this->db->update('user_header_all', $data);
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //sms function code
    public function sendSms($auth_key, $no, $user_type, $boss_name, $firm_name, $firm_no_of_permitted_offices, $firm_no_of_employee, $firm_no_of_customers, $firm_email_id, $password, $trigger_by) {

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

        if ($trigger_by == "superuser") {
            if ($user_type == 2) {
                $message = urlencode("Welcome $boss_name$dot $firm_name is successfully registered with $firm_no_of_permitted_offices Offices$comma $firm_no_of_employee Employees $comma $firm_no_of_customers Customers$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
            } else if ($user_type == 3) {
                $message = urlencode("Welcome $boss_name$dot $firm_name is successfully registered with $firm_no_of_employee Employees$comma $firm_no_of_customers Customers$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
            }
        } else if ($trigger_by == "hq") {
            $message = urlencode("Welcome $boss_name for the office of $firm_name with $firm_no_of_employee Employees$comma $firm_no_of_customers Customers$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
        } else if ($trigger_by == "human_resource") {
            $message = ("Welcome $boss_name for the office of $firm_name$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
        } else if ($trigger_by == "client") {


            $result1 = $this->db->query("SELECT firm_name from partner_header_all where firm_id='$firm_name' ");
            if ($result1->num_rows() > 0) {
                $data1 = $result1->row();
                $name = $data1->firm_name;
            }

            $message = urlencode("Welcome $boss_name for the office of $firm_name$dot $new_line Your Username $otp_prefix $firm_email_id $new_line Password $otp_prefix $password $new_line You can login from $link ");
        }


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

    //to get firm under hq (branch list)
    public function getFirmData($boss_id) {
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where("`boss_id`!='$boss_id' AND `reporting_to`='$boss_id'");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    public function getFirmName($firm_id) {
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where("`firm_id`='$firm_id'");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }
    
     public function selectusereId() {
//        $query = $this->db->query("SELECT service_id FROM `services_header_all` ORDER BY service_id DESC LIMIT 0,1");
        $query = $this->db->query("SELECT user_id FROM `user_header_all` ORDER BY user_id DESC LIMIT 0,1");
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        } else {
            return false;
        }
    }
    
  public function get_branch_hr_data($firm_id) {

         $query = $this->db->query("SELECT `user_header_all`.`linked_with_boss_id`,`user_header_all`.`user_id`,`user_header_all`.`confirmation_date`,`user_header_all`.`user_type`,`user_header_all`.`is_employee`,`user_header_all`.`activity_status`,
             `user_header_all`.`firm_id`,`user_header_all`.`user_name`,`user_header_all`.`mobile_no`, `user_header_all`.`email`,`user_header_all`.`address`,`user_header_all`.`city`,
             `user_header_all`.`state`,`user_header_all`.`country`,`user_header_all`.`date_of_joining`,`user_header_all`.`designation_id`,`designation_header_all`.`designation_name`
             FROM `user_header_all` LEFT JOIN `designation_header_all` ON `user_header_all`.`designation_id`=`designation_header_all`.`designation_id`
             where `user_header_all`.`is_employee` = 1 AND `user_header_all`.`designation_id`!='CA' AND  `user_header_all`.firm_id='$firm_id' AND `user_header_all`.linked_with_boss_id = '$boss_id'");

        if ($query->num_rows() > 0) {
    
             
        } else {
            return false;
        }
    }
    

}
?>


