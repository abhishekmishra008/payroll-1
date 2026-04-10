<?php

class SalaryInfoController extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('SalaryInfoModel');
    }

    public function index() {
        $data['prev_title'] = "Salary Info";
        $data['page_title'] = "Salary Info";
        $this->load->view('human_resource/BranchSalInfo', $data);
    }
	
	public function run_past_salary($user_id=''){
		//echo $user_id;
		$data['prev_title'] = "Salary Info";
        $data['page_title'] = "Salary Info";
        $data['user_id'] = $user_id;
        $this->load->view('human_resource/RunPreviousSalary', $data);
	}

    public function addSalInfo() {
        $salary_type = $this->input->post("salary_type");
//        $valu_percent = $this->input->post("valu_percent");
//        $default_value = $this->input->post("default_value");
        $firm_name = $this->input->post("firm_name");
        if ($firm_name != '') {
            $firm_id = $this->input->post("firm_name");
        } else {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $login_firm_id = $result['firm_id'];
            }

            $session_data = $this->session->userdata('login_session');
            $data['session_data'] = $session_data;
            $user_type = ($session_data['user_type']);
			  $user_id = ($session_data['user_id']);
            if ($user_type == 5) {
                $hr_auth = $this->db->query("select hr_authority from user_header_all where firm_id='$login_firm_id' AND user_type='5' AND email='$user_id'");
                if ($this->db->affected_rows() > 0) {
                    $record1 = $hr_auth->row();
                    $firm_id = $record1->hr_authority;
                }
            } else {
                if ($result !== false) {
                    $firm_id = $login_firm_id;
                }
            }
        }
        $count = count($salary_type);
        $aa = 0;
        for ($i = 0; $i < $count; $i++) {
            if ($salary_type[$i] !== '') {
                $data = array(
                    'firm_id' => $firm_id,
                    'pay_type' => $salary_type[$i],
                    'Value_Type' => 0,
                    'Default_Value' => 0,
                );
                $result = $this->db->insert('m_salarytype', $data);
                if ($result == TRUE) {
                    $aa++;
                }
            }
        }
        if ($aa > 0) {
            $response['message'] = 'success';
																  
				
										  
										 
									 
		  

												  

							
							  
											 
											  
	 

							 
											
											
																 
	 

								  
														 
															 
															   
													 
							   
													   
				
														   
									
													
			 

																	  
												  
													  
								  
																																		 
													 
											   
													  
				 
					
										
											  
				 
			 
		 
									 
				
										 
										  
							  
										  
												   
									  
										 
				  
																   
									  
						  
				 
			 
		 
					  
											 
            $response['body'] = 'Salary types created successfully.';
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'Failed';
        }echo json_encode($response);
    }

    public function addDeductionInfo() {
        $deduction_type = $this->input->post("deduction_type");
        // $valu_percent = $this->input->post("valu_percent_ded");
        // $default_value = $this->input->post("default_value_ded");
        $firm_name = $this->input->post("firm_name1");
        if ($firm_name != '') {
            $firm_id = $this->input->post("firm_name1");
        } else {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $login_firm_id = $result['firm_id'];
            }

            $session_data = $this->session->userdata('login_session');
            $data['session_data'] = $session_data;
            $user_type = ($session_data['user_type']);
			  $user_id = ($session_data['user_id']);
            if ($user_type == 5) {
                $hr_auth = $this->db->query("select hr_authority from user_header_all where firm_id='$login_firm_id' AND user_type='5' AND email='$user_id'");
                if ($this->db->affected_rows() > 0) {
                    $record1 = $hr_auth->row();
                    $firm_id = $record1->hr_authority;
                }
            } else {
                if ($result !== false) {
                    $firm_id = $login_firm_id;
                }
            }
        }
        $count = count($deduction_type);
        $aa = 0;
        for ($i = 0; $i < $count; $i++) {
            if ($deduction_type[$i] !== '') {
                $data = array(
                    'firm_id' => $firm_id,
                    'deduction' => $deduction_type[$i],
                    'value' => 0,
                    'default_value' => 0,
                );
                $result = $this->db->insert('m_standarddeductions', $data);
                if ($result == TRUE) {
                    $aa++;
                }
            }
        }
        if ($aa > 0) {
            $response['message'] = 'success';
            $response['body'] = 'Deduction types created successfully.';
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'Failed';
        }echo json_encode($response);
    }

    public function UpadteSalInfo() {
        $salary_type = $this->input->post("salary_type");
//        $valu_percent = $this->input->post("valu_percent");
//        $default_value = $this->input->post("default_value");
        $id = $this->input->post("id");
        $data = array(
            'pay_type' => $salary_type[0],
            'Value_Type' => 0,
            'Default_Value' => 0,
        );
        $this->db->where('earning_id', $id);
        $query = $this->db->update('m_salarytype', $data);
        if ($query !== FALSE) {
            $response['message'] = 'success';
            $response['body'] = 'Salary type udated successfully.';
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'Failed';
        }echo json_encode($response);
    }

    public function UpadteDedInfo() {
        $deduction_type = $this->input->post("deduction_type");
//        $valu_percent = $this->input->post("valu_percent_ded");
//        $default_value = $this->input->post("default_value_ded");
        $id = $this->input->post("id1");
        $data = array(
            'deduction' => $deduction_type[0],
            'value' => 0,
            'default_value' => 0,
        );
        $this->db->where('deduction_id', $id);
        $query = $this->db->update('m_standarddeductions', $data);
        if ($query !== FALSE) {
            $response['message'] = 'success';
            $response['body'] = 'Deduction type udated Successful';
        } else {
            $response['message'] = 'fail';
            $response['body'] = 'Failed';
        }echo json_encode($response);
    }

    function get_sal_info() {
        $firm_id = $this->input->post('firm_name');
        if ($firm_id != '') {
            $firm_id = $this->input->post('firm_name');
        } else {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $login_firm_id = $result['firm_id'];
            }

            $session_data = $this->session->userdata('login_session');
            $data['session_data'] = $session_data;
            $user_type = ($session_data['user_type']);
			  $user_id = ($session_data['user_id']);
            if ($user_type == 5) {
                $hr_auth = $this->db->query("select hr_authority from user_header_all where firm_id='$login_firm_id' AND user_type='5' AND email='$user_id'");
                if ($this->db->affected_rows() > 0) {
                    $record1 = $hr_auth->row();
                    $firm_id = $record1->hr_authority;
                }
            } else {
                if ($result !== false) {
                    $firm_id = $login_firm_id;
                }
            }
        }

        $query = $this->db->query("select * from m_salarytype where firm_id='$firm_id'");
        $data = '';
        $data .= '<table style="width: 100%;" id="sal_table" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Salary type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>';
        if ($this->db->affected_rows() > 0) {

            $result1 = $query->result();
            foreach ($result1 as $row) {
                $btn_del = "<button type='button' onclick='delete_saltype(\"" . $row->earning_id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-trash'  style='font-size: 23px !important; color: #d43535!important;'> </i> </button>";
                $btn_edit = "<button type='button' onclick='edit_saltype_btn(\"" . $row->earning_id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-pencil'  style='font-size: 23px !important; color: #337ab7!important;'> </i> </button>";

                $data .= '<tr>
                    <td>' . $row->earning_id . '</td>
                    <td>' . $row->pay_type . '</td>
                    <td>' . $btn_del . $btn_edit . '</td>
                    </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_sal"] = $data;
        } else {
            $response["result_sal"] = $data;
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    function get_deduction_info() {
        $firm_id = $this->input->post('firm_name');
        if ($firm_id != '') {

            $firm_id = $this->input->post('firm_name');
        } else {

            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $login_firm_id = $result['firm_id'];
            }

            $session_data = $this->session->userdata('login_session');
            $data['session_data'] = $session_data;
            $user_type = ($session_data['user_type']);
			  $user_id = ($session_data['user_id']);
            if ($user_type == 5) {

                $hr_auth = $this->db->query("select hr_authority from user_header_all where firm_id='$login_firm_id' AND user_type='5' AND email='$user_id'");
                if ($this->db->affected_rows() > 0) {
                    $record1 = $hr_auth->row();
                    $firm_id = $record1->hr_authority;
                }
            } else {
                if ($result !== false) {
                    $firm_id = $login_firm_id;
                }
            }
        }

        $query = $this->db->query("select * from m_standarddeductions where firm_id='$firm_id'");
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width: 100%;" id="ded_table" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Deduction type</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>';
            $result1 = $query->result();
            foreach ($result1 as $row) {
                $btn_del = "<button type='button' onclick='delete_dedtype(\"" . $row->deduction_id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-trash'  style='font-size: 23px !important; color: #d43535!important;'> </i> </button>";
                $btn_edit = "<button type='button' onclick='edit_dedtype_btn(\"" . $row->deduction_id . "\")'class='btn-icon btn-icon-only btn btn-link'><i class='fa fa-pencil'  style='font-size: 23px !important; color: #337ab7!important;'> </i> </button>";
                $data .= '<tr>
                    <td>' . $row->deduction_id . '</td>
                    <td>' . $row->deduction . '</td>

                    <td>' . $btn_del . $btn_edit . '</td>
                    </tr>';
            }
            $data .= '  </table>';
            $response["status"] = 200;
            $response["result_sal"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    public function delete_saltype() {
        $id = $this->input->post_get("id");
        $array = array("earning_id" => $id);
        $dele_trans = $this->SalaryInfoModel->delete_salery_type($array);
        if ($dele_trans != FALSE) {
            $response["body"] = "Delete successfully.";
            $response["status"] = 200;
        } else {
            $response["body"] = "Failed To Delete";
            $response["status"] = 201;
        }echo json_encode($response);
    }

    public function delete_dedtype() {
        $id = $this->input->post_get("id");
        $array = array("deduction_id" => $id);
        $dele_trans = $this->SalaryInfoModel->delete_dedu_type($array);
        if ($dele_trans != FALSE) {
            $response["body"] = "Delete successfully.";
            $response["status"] = 200;
        } else {
            $response["body"] = "Failed To Delete";
            $response["status"] = 201;
        }echo json_encode($response);
    }

    public function get_sal_type_list() {  //done by pooja lote
        $firm_id = $this->input->post('firm_id');
        if (isset($firm_id)) {
            $firm_id = $this->input->post('firm_id');
        } else {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $firm_id = $result['firm_id'];
            }
        }


        $where = array('firm_id' => $firm_id);
        $get_sal = $this->SalaryInfoModel->get_sal_type_list();
//        $get_sal = $this->SalaryInfoModel->get_sal_type_list($where);
        
        if ($get_sal !== FALSE) {
            $result = $get_sal->result();
            $data = '<option value="">Select Salary Type</option>';
            foreach ($result as $row) {
                $data .= '<option value=' . $row->earning_id . '>' . $row->pay_type . '</option>';
            }
            $response['sal_options'] = $data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_ded_type_list() {  //done by pooja lote
        $firm_id = $this->input->post('firm_id');
        if (isset($firm_id)) {
            $firm_id = $this->input->post('firm_id');
        } else {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $firm_id = $result['firm_id'];
            }
        }
        $where = array('firm_id' => $firm_id);
        $get_sal = $this->SalaryInfoModel->get_ded_type_list();
//        echo $this->db->last_query();
//        $get_sal = $this->SalaryInfoModel->get_ded_type_list($where);
        if ($get_sal !== FALSE) {
            $data = '<option value="">Select Deduction Type</option>';
            $get_sal = $get_sal->result();
            foreach ($get_sal as $row) {
                $data .= '<option value=' . $row->deduction_id . '>' . $row->deduction . '</option>';
            }
            $response['ded_options'] = $data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_sal_details() {
        $firm_id = $this->input->post('firm_id');
        if (isset($firm_id)) {
            $firm_id = $this->input->post('firm_id');
        } else {
            $result = $this->customer_model->get_firm_id();						   
									
                $firm_id = $result['firm_id'];
            }
		 
        $id = $this->input->post('id');
        $where = array('earning_id' => $id);
        $get_sal = $this->SalaryInfoModel->get_sal_type_list($where);
        if ($get_sal !== FALSE) {
            $result = $get_sal->row();
            $response['result'] = $result;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }

    public function get_ded_details() {
        $firm_id = $this->input->post('firm_id1');
        if (isset($firm_id)) {
            $firm_id = $this->input->post('firm_id');
        } else {
            $result = $this->customer_model->get_firm_id();
            if ($result !== false) {
                $firm_id = $result['firm_id'];
            }
        }
        $id = $this->input->post('id');
        $where = array('deduction_id' => $id);
        $get_ded = $this->SalaryInfoModel->get_ded_type_list($where);
        if ($get_ded !== FALSE) {
            $result = $get_ded->row();
            $response['result'] = $result;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }echo json_encode($response);
    }
	
	public function get_salary_types(){
		 $user_id=$this->input->post('user_id');
	
		  $result=$this->db->query("call getsaldetails('$user_id')")->result();
	
		 if($this->db->affected_rows() > 0){
		
		$data ='';
		$k=1;
		
		
		foreach($result as $row){
			$pay_type=$row->pay_type;
			$earning_id=$row->earning_id;
			$value=$row->value;
			if($value == null){
				$value=0;
			}
			
		 if($pay_type=='Basic' || $pay_type=="Medical Allowance"|| $pay_type=="House Rent Allowance(HRA)" || $pay_type=="Leave Travel Allowance (LTA)" || $pay_type=="Other Allowance"){
				$cat=1;
			}else if($pay_type=="Professional Tax" || $pay_type=="Provident Fund" || $pay_type=="Other Deductions"){
				$cat=2;
		 }else if($pay_type=="Leave Deduction" || $pay_type=="Late Deduction" || $pay_type=="Loan EMI"){
				$cat=4;
			}else if($pay_type == "Performance Allowance"){
				$cat =3;
			}else if($pay_type == "Holiday OT Sal"){
				$cat=7;
			}else if($pay_type =="Income Tax"){
				$cat = 5;
			}else{
				$cat=0;
			}
			$type=$row->type;
			$data .='<div class="row">
					   <div class="col-md-12">
					   <input type="hidden" id="type'.$k.'" name="type'.$k.'" value="'.$type.'">
					   <input type="hidden" id="cat'.$k.'" name="cat'.$k.'" value="'.$cat.'">
					    <div class="col-md-4"><input type="hidden" value="'.$pay_type.'" id="sal_type'.$k.'" name="sal_type'.$k.'">'.$pay_type.'</div>
					    <div class="col-md-4">  <input type="hidden" id="std_amt'.$k.'" name="std_amt'.$k.'" value="'.$value.'">'.$value.'</div>
					    <div class="col-md-4"><input type="text" id="amt_value'.$k.'" name=" amt_value'.$k.'" value="0" placeholder="Enter Amount" class="form-control"></div>
					    
					   
					   </div></div><hr>
					  ';
					  $k++;
		
		}
		
		$array=array("Leave Deduction","Holiday OT Sal","Late Deduction","Performance Allowance","Loan EMI","Income Tax");
		$array1=array(2,1,2,1,2,2);
		$m=0;
		foreach($array as $pay_type){
			 if($pay_type=='Basic' || $pay_type=="Medical Allowance"|| $pay_type=="House Rent Allowance(HRA)" || $pay_type=="Leave Travel Allowance (LTA)" || $pay_type=="Other Allowance"){
				$cat=1;
			}else if($pay_type=="Professional Tax" || $pay_type=="Provident Fund" || $pay_type=="Other Deductions"){
				$cat=2;
		 }else if($pay_type=="Leave Deduction" || $pay_type=="Late Deduction" || $pay_type=="Loan EMI"){
				$cat=4;
			}else if($pay_type == "Performance Allowance"){
				$cat =3;
			}else if($pay_type == "Holiday OT Sal"){
				$cat=7;
			}else if($pay_type =="Income Tax"){
				$cat = 5;
			}else{
				$cat=0;
			}
			$data .='<div class="row">
			<input type="hidden" value="'.$array1[$m].'" id="type'.$k.'" name="type'.$k.'">
			<input type="hidden" id="cat'.$k.'" name="cat'.$k.'" value="'.$cat.'">
					   <div class="col-md-12">
					    <div class="col-md-4">'.$pay_type.'<input type="hidden" value="'.$pay_type.'" id="sal_type'.$k.'" name="sal_type'.$k.'"></div>
					    <div class="col-md-4"><input type="hidden" id="std_amt'.$k.'" name="std_amt'.$k.'" value="0">0</div>
					    <div class="col-md-4"><input type="text" id="amt_value'.$k.'" name=" amt_value'.$k.'" placeholder="Enter Amount" value="0" class="form-control"></div>
					  
					   
					   </div></div><hr>
					  ';
					  $k++;
					  $m++;
		}
		
		$data .='<input type="hidden" name="count" id="count" value="'.$k.'">';
		// echo $data;
		  $response['data'] = $data;
            $response['code'] = 200;
		 }else{
 $response['data'] = $data;
            $response['code'] = 201;
		 }echo json_encode($response);
	}
	
	function AddDataPastSalary(){
		$result = $this->customer_model->get_firm_id();
            if ($result !== false) {
               $login_firm_id = $result['firm_id'];
            }

            $session_data = $this->session->userdata('login_session');
            $data['session_data'] = $session_data;
            $user_type = ($session_data['user_type']);
            $user_id = ($session_data['user_id']);
            if ($user_type == 5) {
				
                $hr_auth = $this->db->query("select hr_authority from user_header_all where firm_id='$login_firm_id' AND user_type='5' AND email='$user_id'");
                if ($this->db->affected_rows() > 0) {
                    $record1 = $hr_auth->row();
                   $firm_id = $record1->hr_authority;
                }
            } else {
                if ($result !== false) {
                    $firm_id = $login_firm_id;
                }
            }
			
			
		$user_id=$this->input->post('user_id');
		$select_year=$this->input->post('select_year');
		$month=$this->input->post('month');
		$count=$this->input->post('count');
		$a=0;
		
		  $this -> db -> where('user_id', $user_id);
		  $this -> db -> where('month', $month);
		  $this -> db -> where('year', $select_year);
		$del = $this -> db -> delete('t_salary_staff');
		
		for($i=1;$i<$count;$i++){
			$amt_value=$this->input->post('amt_value'.$i);
			$std_amt=$this->input->post('std_amt'.$i);
			$sal_type=$this->input->post('sal_type'.$i);
			$category=$this->input->post('cat'.$i);
			$data=array(
			"firm_id"=>$firm_id,
			"user_id"=>$user_id,
			"amt"=>$amt_value,
			"salary_type"=>$sal_type,
			"std_amt"=>$std_amt,
			"month"=>$month,
			"year"=>$select_year,
			"category"=>$category,
			);
			$insert=$this->db->insert("t_salary_staff",$data);
			if($insert == true){
				$a++;
			}
		}
		$created_on = date('Y-m-d');
		if($a > 0){
			$this -> db -> where('user_id', $user_id);
		  $this -> db -> where('month', $month);
		  $this -> db -> where('year', $select_year);
		$del = $this -> db -> delete('t_release_salary');
			    $data1 = array(
            'user_id' => $user_id,
            'month' => $month,
            'firm_id' => $firm_id,
            'year' => $select_year,
            'status' => 1,
            'created_on' => $created_on,
        );
		$query_insert = $this->db->insert('t_release_salary', $data1);
		$present_days=$this->input->post('present_days');
		$absent_days=$this->input->post('absent_days');
		$late=$this->input->post('late');
		$leave=$this->input->post('leave');
		
		$this -> db -> where('user_id', $user_id);
		  $this -> db -> where('month', $month);
		  $this -> db -> where('year', $select_year);
		$del1 = $this -> db -> delete('manual_staff_attendance');
		$data2=array(
		  'user_id' => $user_id,
            'month' => $month,
			'year' => $select_year,
			'present_days' => $present_days,
			'absent_days' => $absent_days,
			'leave_days' => $leave,
			'late' => $late,
		);
		
        $qee=$this->db->insert('manual_staff_attendance', $data2);
		if($query_insert == true){
		 $response['data'] = $data;
            $response['code'] = 200;
		 }else{
			$response['data'] = $data;
            $response['code'] = 201;	
		}
		}else{
			$response['data'] = $data;
            $response['code'] = 201;
		}echo json_encode($response);
		
		
	}

}
