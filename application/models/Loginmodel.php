<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class loginmodel extends CI_Model {

    public function login_validation($user_id, $pass) {
        $q = $this->db->get_where("user_header_all", array('email' => $user_id,'activity_status' => '1'));
        $result = $q->row();
        if ($q->num_rows() === 1) {
            $result = $q->row();
            if(password_verify($pass, $result->hash_password)) {
                $user_type = $result->user_type;
                $firm_id = $result->firm_id;
                $linked_with_boss_id = $result->linked_with_boss_id;
                $is_employee = $result->is_employee;
                $create_task_assignment = $result->create_task_assignment;
                $enquiry_generate_permission = $result->enquiry_generate_permission;
                $create_service_permission = $result->create_service_permission;
                $create_due_date_permission = $result->create_due_date_permission;
                $user_id = $result->email;
                $firm_logo = $result->firm_logo;
                $emp_id = $result->user_id;
                $user_name = $result->user_name;
                $activity_status = $result->activity_status;
                $hr_authority = $result->hr_authority;
                $project_permission = $result->project_permission;
                if ($user_type == '1') { // admin
                    $data = array(
                        'user_id' => $user_id, //email-id
                        'firm_id' => $firm_id,
                        'user_type' => $user_type,
                        'firm_logo' => $firm_logo,
                        'user_name' => $user_name,
                        'activity_status' => $activity_status
                    );
                    return $data;

                } else if ($user_type == '2') { // hq admin
                    $data = array(
                        'user_id' => $user_id, //email-id
                        'firm_id' => $firm_id,
                        'user_type' => $user_type,
                        'firm_logo' => $firm_logo,
                        'user_name' => $user_name,
                        'emp_id' => $emp_id,
                        'activity_status' => $activity_status
                    );
                    return $data;

                } else if ($user_type == '3') { // Cleint admin
                    $data = array(
                        'user_id' => $user_id, // email-id
                        'firm_id' => $firm_id,
                        'user_type' => $user_type,
                        'firm_logo' => $firm_logo,
                        'user_name' => $user_name,
                        'emp_id' => $emp_id,
                        'activity_status' => $activity_status
                    );
                    return $data;
                    
                } else if ($user_type == '4') { // employee
                    $data = array(
                        'user_id' => $user_id, //email-id
                        'firm_id' => $firm_id,
                        'user_type' => $user_type,
                        'firm_logo' => $firm_logo,
                        'user_name' => $user_name,
                        'emp_id' => $emp_id,
                        'activity_status' => $activity_status
                    );
                    return $data;

                } else if ($user_type == '5') { // hr authority
                    $data = array(
                        'user_id' => $user_id, //email-id
                        'firm_id' => $firm_id,
                        'user_type' => $user_type,
                        'firm_logo' => $firm_logo,
                        'user_name' => $user_name,
                        'emp_id' => $emp_id,
                        'activity_status' => $activity_status
                    );
                    return $data;

                } else {
                    $qry = $this->db
                        ->where('firm_id', $firm_id)
                        ->where('boss_id', $linked_with_boss_id)
                        ->get('partner_header_all');
                    if ($qry->num_rows() === 1) {
                        $result = $qry->row();
                        $firm_activity_status = $result->firm_activity_status;

                        $data = array(
                            'result' => $result,
                            'user_id' => $user_id, //email-id
                            'firm_id' => $firm_id,
                            'firm_logo' => $firm_logo,
                            'user_name' => $user_name,
                            'is_employee' => $is_employee,
                            'user_type' => $user_type,
                            'emp_id' => $emp_id,
                            'create_task_assignment' => $create_task_assignment,
                            'create_service_permission' => $create_service_permission,
                            'enquiry_generate_permission' => $enquiry_generate_permission,
                            'create_due_date_permission' => $create_due_date_permission,
                            'activity_status' => $activity_status,
                            'hr_authority' => $hr_authority,
                            'firm_activity_status' => $firm_activity_status,
                            'project_permission' => $project_permission
                        );

                        //var_dump($data);
                        return $data;
                    } else {
                        return FALSE;
                    }
                }
            }else{
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}
?>


