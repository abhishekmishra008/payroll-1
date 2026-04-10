<?php

class Cron_data extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cron_job_model');
        $this->load->model('customer_model');
        // $this->load->model('task_model');
        $this->load->model('email_sending_model');
        $this->load->helper('dump_helper');
    }

    public function index() {
        
    }

    public function billing_cycle_cron() {
        $query = $this->cron_job_model->get_customer_task_allotment();
        if ($query != FALSE) {
            $result = $query->result();
            foreach ($result as $row) {
                $customer_id = $row->customer_id;
                $task_assignment_name = $row->task_assignment_name;
                $task_id = $row->task_id;
                $billing_cycle = $row->billing_cycle;
                $amount = $row->amount;
                $start_date = $row->start_date;
                $completion_date = $row->completion_date;

                $start = $start_date;
                $realEnd = new DateTime($completion_date);
                $format = 'Y-m-d';
                $today = date('Y-m-d');


                //Customer Name
                $Cust_qry = $this->customer_model->get_customer_name($customer_id);
                if ($Cust_qry != FALSE) {
                    $cust_rs = $Cust_qry->row();
                    $customer_name = $cust_rs->customer_name;
                    $customer_no = $cust_rs->customer_contact_number;
                    $customer_email = $cust_rs->customer_email_id;
                }

                //Task Name
                $Cust_qry = $this->task_model->get_task_name($task_id);
                if ($Cust_qry != FALSE) {
                    $cust_rs = $Cust_qry->row();
                    $task_name = $cust_rs->task_name;
                }

                // Declare an empty array 
                $array = array();

                if ($billing_cycle == '1') {   //  billing Cycle =='1' monthly
                    // Variable that store the date interval  of period 1 Month                    
                    $interval = new DateInterval('P1M');
                    $realEnd->add($interval);
                    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
                    $billing = "Monthly";

                    // Use loop to store date into array 
                    foreach ($period as $date) {
                        $array = $date->format($format);
                        if ($array == $today) {
                            $send_sms = $this->email_sending_model->billingCycleSMS($customer_name, $customer_no, $task_assignment_name, $task_name, $billing, $amount);    //send SMS

                            $send_email = $this->email_sending_model->billingCycleEmail($customer_name, $customer_email, $task_assignment_name, $task_name, $billing, $amount);  //send email
                            if ($send_email == true) {
                                echo "success";
                            } else {
                                echo "fail";
                            }
                        } else {
                               //echo "Date not match";
                        }
                    }
                } else {  //$billing Cycle =='2'  //Quaterly  
                    // Variable that store the date interval  of period 6 Month 
                    $interval = new DateInterval('P6M');
                    $realEnd->add($interval);
                    $period = new DatePeriod(new DateTime($start_date), $interval, $realEnd);
                    $billing = "Quaterly";

                    // Use loop to store date into array 
                    foreach ($period as $date) {
                        $array = $date->format($format);

                        if ($array == $today) {
                            $send_sms = $this->email_sending_model->billingCycleSMS($customer_name, $customer_no, $task_assignment_name, $task_name, $billing, $amount);    //send SMS

                            $send_email = $this->email_sending_model->billingCycleEmail($customer_name, $customer_email, $task_assignment_name, $task_name, $billing, $amount);  //send email

                            if ($send_email == true) {
                                echo "success";
                            } else {
                                echo "fail";
                            }
                        } else {
                            //echo "Date not match";
                        }
                    }
                }
            }
        } else {
            
        }
    }

    public function subTaskRepeated() {
        
    }
	
	//this fnction is for the chnage the batch status
    public function change_batch_status(){
            $current_date_for_db=date("Y-m-d");
            $current_date=strtotime(date("Y-m-d"));		
            $query_for_get_dates=$this->db->query("select batch_start_on,batch_end_date from survey_batch_information_all where emp_id<>''");
            if($query_for_get_dates->num_rows()>0){
                foreach($query_for_get_dates->result() as $row){
                    $batch_start_on=strtotime($row->batch_start_on);
                    $batch_end_date=strtotime($row->batch_end_date);
                    $diff=($batch_start_on - $current_date)/60/60/24;
                    if($diff==0){
                        $data=array('batch_status'=>1);
                        $where_data=array('batch_start_on'=>$current_date_for_db);
                        $query_for_update_status=$this->Cron_job_model->chnage_survey_batch_status($data,$where_data);
                    }else{	
                    }
                }
            }else{	
            }						
    }

	public function addEmployeeMonthlyLeave() {
		$this->load->model('MasterModel');
        $queryData=$this->MasterModel->_rawQuery("select id,user_id,total_leave_available,user_name,count_leave_accrued,
                    accrued_leave,accrued_period,accure_from,type6_balance,type5_balance,type4_balance,type3_balance,type2_balance,type1_balance from user_header_all where activity_status=1  and probation_period_end_date < CURRENT_DATE()");
        // echo $this->db->last_query();die;
		if($queryData->totalCount>0)
		{
			$arrayData=array();
			foreach($queryData->data as $row)
			{
			    $accrued_leave=$row->accrued_leave;
			    $accrued_period=$row->accrued_period;
			    $accure_from=$row->accure_from;
                if($accrued_leave == 1 && $accrued_period == 1){
                    $addIn='type'.$accure_from.'_balance';
                    $typeLeave=($row->$addIn) + $row->count_leave_accrued;
                }
				$total_leave_available=($row->total_leave_available) + $row->count_leave_accrued;
				array_push($arrayData,array('id'=>$row->id,'total_leave_available'=>$total_leave_available,'type'.$accure_from.'_balance'=>$typeLeave));
			}
            // print_r($arrayData);die;
			$update=$this->MasterModel->_updateBatch('user_header_all',$arrayData,'id');
		}
	}

    public function addEmployeeMonthlyLeaveCarryForword1() {
        try {
            $currentMonth = date('n');
            $currentDate = date('d');
            $currentMonth = 1;
            $currentDate = 1;
            $partnerHeaderAll = $this->db->query("
                SELECT id, leave_renewal_period, firm_id 
                FROM partner_header_all 
                WHERE leave_renewal_period = '{$currentMonth}'
            ")->result();
            if (empty($partnerHeaderAll)) { return; }
            foreach ($partnerHeaderAll as $partner) {
                if ((int)$partner->leave_renewal_period !== $currentMonth || (int)$currentDate !== 1) { continue; }
                $users = $this->db->query("SELECT user_id, 
                                                type1, type2, type3, type4, type5, type6, type7,
                                                type1_balance, type2_balance, type3_balance, type4_balance, 
                                                type5_balance, type6_balance, type7_balance
                                            FROM user_header_all
                                            WHERE firm_id = '{$partner->firm_id}'
                                            AND activity_status = 1")->result();
                if (empty($users)) { continue; };
                $batchUpdate = [];
                foreach ($users as $user) {
                    $updateRow = ['user_id' => $user->user_id];
                    for ($i = 1; $i <= 7; $i++) {
                        $field = "type{$i}";
                        $balanceField = "type{$i}_balance";
                        if (!isset($user->$field) || empty($user->$field)) { continue; }
                        $parts = explode(':', $user->$field);
                        if (count($parts) !== 3) { continue; }
                        list($leaveType, $leaves, $carryFlag) = $parts;
                        $leaves = (int)$leaves;
                        $balance = $user->$balanceField;
                        if ($carryFlag == '1' || $carryFlag == 1) {
                            if (empty($leaveType) || $leaveType == '0' || $leaveType == 'null' || $leaveType == 0) {
                                $totalLeaves = $leaves + $balance;
                                
                            } else {
                                $totalLeaves = $leaves + $balance;
                                
                            }
                        } elseif ($carryFlag == '0' || $carryFlag == 0) {
                            if (empty($leaveType) || $leaveType == '0' || $leaveType == 'null' || $leaveType == 0) {
                                $totalLeaves = $leaves + 0;
                                
                            } else {
                                $totalLeaves = $leaves + 0;
                                
                            }
                        }
                        $updateRow[$field] = "{$leaveType}:{$totalLeaves}:{$carryFlag}";
                    }
                    $batchUpdate[] = $updateRow;
                }
                if (!empty($batchUpdate)) {
                    $this->db->update_batch('user_header_all', $batchUpdate, 'user_id');
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Error in addEmployeeMonthlyLeaveCarryForword: ' . $e->getMessage());
        }
    }

    public function addEmployeeMonthlyLeaveCarryForword2() {
        try {
            $currentMonth = (int)date('n');
            $currentDate  = (int)date('d');
            // Test ke liye fixed values (aap chahe to hata sakte ho)
            $currentMonth = 1;
            $currentDate  = 1;
            $partnerHeaderAll = $this->db->query("
                SELECT id, leave_renewal_period, firm_id 
                FROM partner_header_all 
                WHERE leave_renewal_period = '{$currentMonth}'
            ")->result();

            if (empty($partnerHeaderAll)) {
                return;
            }

            foreach ($partnerHeaderAll as $partner) {
                // Sirf renewal period match aur 1st date ko hi run ho
                if ((int)$partner->leave_renewal_period !== $currentMonth || $currentDate !== 1) {
                    continue;
                }
                $limit  = 100; // ek chunk me 100 users
                $offset = 0;
                while (true) {
                    // Step 2: Chunked users fetch
                    $users = $this->db->query("SELECT user_id, 
                            type1, type2, type3, type4, type5, type6, type7,
                            type1_balance, type2_balance, type3_balance, type4_balance, 
                            type5_balance, type6_balance, type7_balance
                        FROM user_header_all
                        WHERE firm_id = '{$partner->firm_id}'
                        AND activity_status = 1
                        LIMIT {$limit} OFFSET {$offset}
                    ")->result();

                    if (empty($users)) { break; }
                    $batchUpdate = [];
                    foreach ($users as $user) {
                        $updateRow = ['user_id' => $user->user_id];
                        for ($i = 1; $i <= 7; $i++) {
                            $field       = "type{$i}";
                            $balanceField = "type{$i}_balance";
                            $parts = explode(':', $user->$field);
                            $balance = $user->$balanceField;
                            list($leaveType, $leaves, $carryFlag) = $parts;
                            if (empty($user->$field)) { continue; }
                            if (count($parts) !== 3) { continue; }
                            $leaves  = $leaves;
                            if ($carryFlag == '1' || $carryFlag == 1) {
                                if (empty($leaveType) || $leaveType == '0' || $leaveType == 'null' || $leaveType == 0) {
                                    $totalLeaves = $leaves + $balance;
                                    
                                } else {
                                    $totalLeaves = $leaves + $balance;
                                    
                                }
                            } elseif ($carryFlag == '0' || $carryFlag == 0) {
                                if (empty($leaveType) || $leaveType == '0' || $leaveType == 'null' || $leaveType == 0) {
                                    $totalLeaves = $leaves + 0;
                                    
                                } else {
                                    $totalLeaves = $leaves + 0;
                                    
                                }
                            }
                            $updateRow[$field] = "{$leaveType}:{$totalLeaves}:{$carryFlag}";
                        }
                        $batchUpdate[] = $updateRow;
                    }
                    if (!empty($batchUpdate)) {
                        $this->db->update_batch('user_header_all', $batchUpdate, 'user_id');
                    }

                    $offset += $limit;
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Error in addEmployeeMonthlyLeaveCarryForword: ' . $e->getMessage());
        }
    }

    public function addEmployeeMonthlyLeaveCarryForword() {
        try {
            $currentMonth = (int)date('n');
            $currentDate  = (int)date('d');
            // Test ke liye fixed values
            $currentMonth = 1;
            $currentDate  = 1;
            $partnerWithUserHeaderAll = $this->db->query("SELECT 
                                                ph.id AS partner_id,
                                                ph.firm_id,
                                                ph.leave_renewal_period,
                                                uh.user_id,
                                                uh.type1, uh.type2, uh.type3, uh.type4, uh.type5, uh.type6, uh.type7,
                                                uh.type1_balance, uh.type2_balance, uh.type3_balance, uh.type4_balance,
                                                uh.type5_balance, uh.type6_balance, uh.type7_balance
                                            FROM partner_header_all ph
                                            LEFT JOIN user_header_all uh 
                                                ON uh.firm_id = ph.firm_id 
                                                AND uh.activity_status = 1
                                            WHERE ph.leave_renewal_period = '{$currentMonth}'
                                        ")->result();
            if (empty($partnerWithUserHeaderAll)) { return; }
            $batchUpdate = [];
            foreach ($partnerWithUserHeaderAll as $user) {
                // Sirf renewal period match aur 1st date ko hi run ho
                if ((int)$user->leave_renewal_period !== $currentMonth || $currentDate !== 1) {
                    continue;
                }
                if (empty($user->user_id)) { continue; }
                $updateRow = ['user_id' => $user->user_id];
                for ($i = 1; $i <= 7; $i++) {
                    $field = "type{$i}";
                    $balanceField = "type{$i}_balance";
                    if (empty($user->$field)) { continue; }
                    $parts = explode(':', $user->$field);
                    if (count($parts) !== 3) { continue; }
                    list($leaveType, $leaves, $carryFlag) = $parts;
                    $balance = $user->$balanceField;
                    if ($carryFlag == '1' || $carryFlag == 1) {
                        if (empty($leaveType) || $leaveType == '0' || $leaveType == 'null' || $leaveType == 0) {
                            $totalLeaves = $leaves + $balance;
                            
                        } else {
                            $totalLeaves = $leaves + $balance;
                            
                        }
                    } elseif ($carryFlag == '0' || $carryFlag == 0) {
                        if (empty($leaveType) || $leaveType == '0' || $leaveType == 'null' || $leaveType == 0) {
                            $totalLeaves = $leaves + 0;
                            
                        } else {
                            $totalLeaves = $leaves + 0;
                            
                        }
                    }
                    $updateRow[$field] = "{$leaveType}:{$totalLeaves}:{$carryFlag}";
                }
                $batchUpdate[] = $updateRow;
            }
            if (!empty($batchUpdate)) {
                $this->db->update_batch('user_header_all', $batchUpdate, 'user_id');
            }

        } catch (Exception $e) {
            log_message('error', 'Error in addEmployeeMonthlyLeaveCarryForword: ' . $e->getMessage());
        }
    }


}
