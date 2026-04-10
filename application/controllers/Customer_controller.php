<?php

class Customer_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('due_date_model');
        $this->load->model('Verification');
        //$this->load->model('customer_model');
    }

    public function index() {


        $session_data = $this->session->userdata('otp_session');

        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $otp = $session_data['otp'];
        } else {
            $otp = $this->session->userdata('otp_session');
        }


        $session_data = $this->session->userdata('login_session');
		
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = $session_data[0];
			   $pass = $session_data[1];
        } else {
            $user_id = $this->session->userdata('login_session');
        }

       

            $result1=$this->Verification->get_customer_id();
        // var_dump($result1);
        if ($result1 != null) {
            foreach ($result1 as $row) {
                $customer_id = $row->customer_id;
                $customer_name = $row->customer_name;
                $firm_id = $row->firm_id;
                $customer_contact_number = $row->customer_contact_number;
                $customer_email_id = $row->customer_email_id;
            }


            $otp_number = (string) $otp;
            $auth_key = "178904AVN94GK259e5e87b";
            $no = $pass;
            $customer_name = $customer_name;
            $cust_email_id = $user_id;

            $verified_customer = $this->Verification->sendSms($auth_key, $customer_name, $no, $cust_email_id, $otp_number);

            $data['prev_title'] = "Customer verification";
            $data['page_title'] = "Customer verification";
            $arr2 = str_split($pass, 6);
            $data['mobile_no'] = $arr2[1];

            $data['otp'] = $otp;


            $data['user_id'] = $customer_name;
            //$this->load->view('customer/cricket_header', $data);
            $this->load->view('customer/Customer_verification', $data);
            //$this->load->view('customer/Customer_details', $data);
        }
    }

    public function customer_panel() {
        $session_data = $this->session->userdata('otp_session');

        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $otp = $session_data['otp'];
        } else {
            $otp = $this->session->userdata('otp_session');
        }

        $otp_number = (string) $otp;

        $otp1 = $this->input->post("otp_no1");
        $otp2 = $this->input->post("otp_no2");
        $otp3 = $this->input->post("otp_no3");
        $otp4 = $this->input->post("otp_no4");
        $arr = array($otp1, $otp2, $otp3, $otp4);
        $final_otp = implode("", $arr);

        if ($otp_number == $final_otp) {


            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
             $user_id = $session_data[0];
            } else {
                $user_id = $this->session->userdata('login_session');
            }

            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            if ($this->Verification->is_otp_available($final_otp)) {
                $verified_otp = '<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Valid otp</label>';
            } else {
                $verified_otp = '<label class="text-success"><span class="glyphicon glyphicon-ok"></span>Invalid otp</label>';
            }
            $response['verified_otp'] = $verified_otp;
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }


        echo json_encode($response);
        //$this->load->view('customer/Customer_details', $data);
    }

    public function customer_dasboard() {



        $session_data = $this->session->userdata('login_session');
		$data['session_data']=$session_data;
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
         $user_id = $session_data[0];
        } else {
            $user_id = $this->session->userdata('login_session');
        }
		
		 
            $result1= $this->Verification->get_customer_id();
		 
        // var_dump($result1);
        if ($result1 != null) {
            foreach ($result1 as $row) {
                $customer_id = $row->customer_id;
                $customer_name = $row->customer_name;
                $firm_id = $row->firm_id;
                $customer_contact_number = $row->customer_contact_number;
                $customer_email_id = $row->customer_email_id;
            }
		} else {
			$customer_name="";
		}
        $data['prev_title'] = "Customer details";
        $data['page_title'] = "Customer details";

        $data['user_id'] = $customer_name;
        //redirect(base_url() . 'Customer_dashboard');
        $this->load->view('customer/Customer_details', $data);

        //$this->load->view('customer/Customer_details', $data);
    }
public function get_firm_name() {
	 $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
      $user_id = $session_data[0];
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        

            $result1= $this->Verification->get_customer_id();
        if ($result1 != null) {
            foreach ($result1 as $row) {
                $customer_id = $row->customer_id;
                $customer_name = $row->customer_name;
                $firm_id = $row->firm_id;
						        $firm_details[] = $this->db->query("select * FROM `partner_header_all` where  firm_id='$firm_id' ")->row();
            }
		}
				

			            $data = " ";
						$firm_id=array();
if($firm_details!=null)
{
            foreach ($firm_details as $firm_detail2) {
			
		
                $firm_name[] = $firm_detail2->firm_name;

               // $firm_id[]= $firm_detail2->firm_id;
				array_push($firm_id,$firm_detail2->firm_id);
            }
			
	     if ($firm_id != '') {
                for ($i = 0; $i < count($firm_id); $i++) {
//
                    $data .= '
		<li class="m-nav__section m-nav__section--first">
                      
                        
                                        <span class="m-nav__section-text">
                             <a class="btn " onclick="show_services(`' . $firm_id[$i] . '`)">' . $firm_name[$i] . '</a>
               </span>
           
                   </li>';
                }
				
            } else {
                $data .= '
			     <li class="m-nav__section m-nav__section--first">
									<span class="m-nav__section-text">
                          No Firms
               </span>
             
                   </li>';
            }
			  $response['firm_name'] = $data;
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
			
} else {
	 $data .= '
			     <li class="m-nav__section m-nav__section--first">
									<span class="m-nav__section-text">
                           No Firms
               </span>
             
                   </li>';
            } 
			echo json_encode($response);
	

}
    public function customer_task() {

        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
       $user_id = $session_data[0];
        } else {
            $user_id = $this->session->userdata('login_session');
        }
$firm_id=$this->input->post("firm_id");
       
            $result1= $this->Verification->get_customer_id();

        if ($result1 != null) {
            foreach ($result1 as $row) {
                $customer_id = $row->customer_id;
                $customer_name = $row->customer_name;
             
            }
		}
            $task_assign_details = $this->db->query("select * FROM `customer_task_allotment_all` where  customer_id='$customer_id' and firm_id='$firm_id' and sub_task_id=''")->result();

            $data = " ";
if($task_assign_details!=null)
{
            foreach ($task_assign_details as $task_data) {
                $task_assignment_name[] = $task_data->task_assignment_name;
                $task_assignment_id[] = $task_data->task_assignment_id;
                $task_id[] = $task_data->task_id;
            }

            //var_dump($task_id);
            for ($i = 0; $i < count($task_id); $i++) {
                $get_aprv_sign_emp[] = $this->db->query("select Distinct(service_name) as service_name, service_id from services_header_all where service_id in (SELECT service_type_id from task_header_all where task_id='$task_id[$i]')")->result();
            }

            //echo $this->db->last_query();
            if ($get_aprv_sign_emp[0] != null) {

                foreach ($get_aprv_sign_emp as $get_aprv1) {
                    foreach ($get_aprv1 as $get_aprv) {

                        $service_id = $get_aprv->service_id;
                        $service_name = $get_aprv->service_name;
               
	
//
                    $data .= '
		<li class="m-nav__section m-nav__section--first">
                      
                        
                                        <span class="m-nav__section-text">
                             <a class="btn " onclick="show_task(`' . $service_id . '`)">' . $service_name . '</a>
               </span>
           
                   </li>';
                
				     }
                }
            } else {
                $data .= '
			     <li class="m-nav__section m-nav__section--first">
									<span class="m-nav__section-text">
                            No Services
               </span>
             
                   </li>';
            }



            $response['task_assignment_name'] = $data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
			 $data .= '
			     <li class="m-nav__section m-nav__section--first">
									<span class="m-nav__section-text">
                            No Services
               </span>
             
                   </li>';
				        $response['task_assignment_name'] = $data;
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
	
        echo json_encode($response);
}

    public function Due_date_task_name() {

        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
     $user_id = $session_data[0];
        } else {
            $user_id = $this->session->userdata('login_session');
        }

       $firm_id=$this->input->post("firm_id");
	     $data = "";
	   if ($firm_id != null) {
 $due_date_details = $this->db->query("select * from customer_header_all where  firm_id='$firm_id' and customer_id in (select c.customer_id from customer_header_all c left JOIN customer_employee_detail_all e on c.customer_id=e.customer_id where ( c.customer_email_id='$user_id'  or e.employee_email_id='$user_id'))")->result();


        // $due_date_details = $this->db->query("select * from customer_header_all where   customer_email_id='$user_id'")->result();

  
            foreach ($due_date_details as $due_date) {
                $due_date_id = explode(',', $due_date->attached_due_date_id);
            }


            for ($i = 0; $i < count($due_date_id); $i++) {

                $due_date_names[] = $this->db->query("select * from due_date_header_all where due_date_id='$due_date_id[$i]'")->result();
            }
            if ($due_date_names[0] != null) {

	
                foreach ($due_date_names as $due_date) {
                    foreach ($due_date as $due_date1) {

                        $data .= '<li class="m-nav__section m-nav__section--first">
									<span class="m-nav__section-text">

           <a class="btn btn-link" onclick="show_due_task(`' . $due_date1->due_date_id . '`)">' . $due_date1->due_date_name . '</a>
                   </span>
              
                   </li>';
                    }
                }
            } else {
			
                $data .= '<li class="m-nav__section m-nav__section--first">
									<span class="m-nav__section-text">

                                    No Due Date
                   </span>
              
                   </li>';
            }

            $response['due_date_name'] = $data;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
	
            $data .= '<li class="m-nav__section m-nav__section--first">
									<span class="m-nav__section-text">

                                            No Due Date
                   </span>
             
                   </li>';
				      
            $response['due_date_name'] = $data;
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function get_duedate_detail() {


        $due_date_id = $this->input->post('due_date_id');
        $firm_id = $this->input->post('firm_id');
        $viewDuedate = $this->due_date_model->view_duedatetask($due_date_id, $firm_id);
        $viewDuedate_chklist = $this->due_date_model->view_duedatechklst($due_date_id, $firm_id);
        $viewonlyDuedate = $this->due_date_model->view_only_duedate_detail($due_date_id);
        //echo $due_date_name;
        if ($viewonlyDuedate !== FALSE) {
//            var_dump($viewonlyDuedate->result());
            $response['duedate_onlydetail'] = $viewonlyDuedate->result();
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }
        if ($viewDuedate_chklist !== FALSE) {
//            var_dump($viewonlyDuedate->result());
            $response['duedate_chklist'] = $viewDuedate_chklist->result();
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        if ($viewDuedate !== FALSE) {
            $record = $viewDuedate->result();
            foreach ($record as $row) {
                $alloted_to = $row->alloted_to;
                $result_alloted_to = $this->db->query("SELECT `user_name` FROM `user_header_all` WHERE `user_id`='$alloted_to'");
                if ($result_alloted_to->num_rows() > 0) {
                    $employee_result = $result_alloted_to->row();
                    $user_name = $employee_result->user_name;
                } else {
                    $user_name = "";
                }
                $response['employee_name'][] = ['user_name' => $user_name];
            }
        } else {
            $response['employee_name'][] = "";
        }

        if ($viewDuedate !== FALSE) {
            $response['duedate_detail'] = $record;
            $response['result_alloted_to'] = $response['employee_name'];
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function Due_date_graph() { {

            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
            $user_id = $session_data[0];
            } else {
                $user_id = $this->session->userdata('login_session');
            }

            $customer_details = $this->db->query("select * from customer_header_all where customer_id in (select c.customer_id from customer_header_all c left JOIN customer_employee_detail_all e on c.customer_id=e.customer_id where ( c.customer_email_id='$user_id'  or e.employee_email_id='$user_id'))")->result();

             $customer_details = $this->Verification->get_customer_id();
            //$customer_details = $this->db->query("select * from customer_header_all where  customer_email_id='$user_id'")->result();


            if ($customer_details != null) {
                foreach ($customer_details as $customer_data) {
                    $customer_id = $customer_data->customer_id;
                }
            }


            $due_date_id = $this->input->post('due_date_id');
            $task_assign_details = $this->db->query("select due_date_name FROM `due_date_header_all` where  due_date_id='$due_date_id' ")->result();
            foreach ($task_assign_details as $task_data) {
                $response['due_date_name'] = $task_data->due_date_name;
            }
            $cust_task_allot_query = $this->db->query("SELECT firm_id,due_date_id,count(case WHEN status='1' then 1 end) as 'Not_Initiated',"
                            . "count(case WHEN status='2' then 1 end) as 'Initiated',"
                            . "count(case WHEN status='3' then 1 end) as 'Completed',"
                            . "count(case WHEN status='4' then 1 end) as 'Closed'"
                            . " FROM `customer_due_date_task_transaction_all` where  due_date_id='$due_date_id' and customer_id='$customer_id' ")->result();

            if ($cust_task_allot_query != null) {
                foreach ($cust_task_allot_query as $cust_status) {
                    $due_date_id = $cust_status->due_date_id;
                    $firm_id = $cust_status->firm_id;
						
                }
	
                $total = $cust_status->Not_Initiated + $cust_status->Initiated + $cust_status->Completed + $cust_status->Closed;

                $percentage = $cust_status->Completed / $total * 100;
                //var_dump($due_date_id);
                //	var_dump($firm_id);
                $data = '
				
				 <div class="m-widget24">
                                            <div class="m-widget24__item ">
											<h4>
                                              Due Date
                                                </h4>
                                                
                                                <div class="m--space-10"></div>
                                                <div class="progress m-progress--sm">
                                                    <div class="progress-bar m--bg-success" role="progressbar" style="width:	' . $percentage . '%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="m-widget24__change">
                                                 	Completed
                                                </span>
                                                <span class="m-widget24__number">
                                               	' . $percentage . '%
                                                </span>
                                            </div>
                                        </div>
				<br>
			<button type="button" class="btn btn-outline-primary"  data-toggle="modal" data-target="#dudatetask_detail" data-due_date_id="' . $due_date_id . '" data-firm_id="' . $firm_id . '">Read more</button>';


                $abcd1 = array();

                $abcd1 = $cust_status->Not_Initiated;
                $aa1 = settype($abcd1, "int");


//            var_dump($abcd1);
                $abcd2 = array();


                $abcd2 = $cust_status->Initiated;
                $aa2 = settype($abcd2, "int");

//            var_dump($abcd2);
                $abcd3 = array();

                $abcd3 = $cust_status->Completed;
                $aa1 = settype($abcd3, "int");

//            var_dump($abcd3);
                $abcd4 = array();


                $abcd4 = $cust_status->Closed;
                $aa1 = settype($abcd4, "int");


                $status1 = array('Initiated', 'Working', 'Completed', 'Closed');
                $s = implode("___", $status1);
                $response['status1'][] = $s;
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
                $response['due_date_task'] = $data;

                $response['branch1'] = $abcd1;
                $response['branch2'] = $abcd2;
                $response['branch3'] = $abcd3;
                $response['branch4'] = $abcd4;

                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['result'] = '';
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
            echo json_encode($response);
        }
    }

    public function Assignments() {

        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = $session_data[0];
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }

        $cust_details = $this->db->query("select * from customer_header_all where customer_id=(select c.customer_id from customer_header_all c left JOIN customer_employee_detail_all e on c.customer_id=e.customer_id where ( c.customer_email_id='$email_id'  or e.employee_email_id='$email_id'))")->row();

		
        //$cust_details=$this->db->query("select * from customer_header_all where customer_email_id='$email_id'")->row();
        $cust_id = $cust_details->customer_id;

        $service_id = $this->input->post('service_id');
        $task_assign_details = $this->db->query("select task_assignment_name,task_assignment_id FROM `customer_task_allotment_all` where customer_id='$cust_id' and sub_task_id='' and task_id in (select task_id from task_header_all where service_type_id='$service_id' )")->result();

		
		
		   
		
        //echo $this->db->last_query();
        if ($task_assign_details != null) {
            foreach ($task_assign_details as $task_data) {
                $task_assignment_name[] = $task_data->task_assignment_name;
                $task_assignment_id[] = $task_data->task_assignment_id;
            }
			
	                for ($k = 0; $k < count($task_assignment_id); $k++) {
			     $cust_task_allot_query[]= $this->db->query("SELECT task_assignment_name,completion_date,count(case WHEN status='1' then 1 end) as 'Not_Initiated',"
                        . "count(case WHEN status='2' then 1 end) as 'Initiated',"
                        . "count(case WHEN status='3' then 1 end) as 'Completed',"
                        . "count(case WHEN status='4' then 1 end) as 'Closed',"
                        . "count(sub_task_id!='') as 'total_sub_task'"
                        . " FROM `customer_task_allotment_all` where  task_assignment_id='$task_assignment_id[$k]' and customer_id='$cust_id' and sub_task_id!=''")->result();
					}
				foreach($cust_task_allot_query as $row1){
							foreach($row1 as $row){
				
			
				}
				}

				     $total = $row->Not_Initiated + $row->Initiated + $row->Completed + $row->Closed;
        $percentage = $row->Completed / $total * 100;

		
            //echo $this->db->last_query();
            //var_dump($task_assignment_name);
            //var_dump($task_assignment_id);


            $data = "";
            if ($task_assignment_id != null) {
                for ($i = 0; $i < count($task_assignment_id); $i++) {
					
														
															
//
                    $data .= '
 <div class="m-widget24">
                                            <div class="m-widget24__item">
                                                <h4 class="m-widget24__title">
                                                 	<button onclick="project_graph(`' . $task_assignment_id[$i] . '`)" type="button" class="btn m-btn--pill m-btn--air   btn-secondary m-btn m-btn--custom m-btn--label-primary">
											' . $task_assignment_name[$i] . '
										</button>
                                                </h4>
                                                <br>
                                              
                                               
                                                <div class="m--space-10"></div>
                                                <div class="progress m-progress--sm">
                                                    <div class="progress-bar m--bg-success" role="progressbar" style="width:	' . $percentage . '%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="m-widget24__change">
                                                 	Completed
                                                </span>
                                                <span class="m-widget24__number">
                                               	' . $percentage . '%
                                                </span>
                                            </div>
                                        </div>
	
                               
            

            ';
                }
            } else {
                $data .= '

             <a class="btn btn-link" >No Project Created</a>

                ';
            }
            $response['task_assignment_name'] = $data;
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

    public function project_graph() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
          $email_id = $session_data[0];
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }

        $cust_details = $this->db->query("select * from customer_header_all where customer_id=(select c.customer_id from customer_header_all c left JOIN customer_employee_detail_all e on c.customer_id=e.customer_id where ( c.customer_email_id='$email_id'  or e.employee_email_id='$email_id'))")->row();
        //$cust_details=$this->db->query("select * from customer_header_all where customer_email_id='$email_id'")->row();
        $cust_id = $cust_details->customer_id;
        $task_assignment_id = $this->input->post('task_assignment_id');

        $cust_task_allot_query = $this->db->query("SELECT task_assignment_name,completion_date,count(case WHEN status='1' then 1 end) as 'Not_Initiated',"
                        . "count(case WHEN status='2' then 1 end) as 'Initiated',"
                        . "count(case WHEN status='3' then 1 end) as 'Completed',"
                        . "count(case WHEN status='4' then 1 end) as 'Closed',"
                        . "count(sub_task_id!='') as 'total_sub_task'"
                        . " FROM `customer_task_allotment_all` where  task_assignment_id='$task_assignment_id' and customer_id='$cust_id' and sub_task_id!=''")->result();
//echo $this->db->LAST_QUERY();

        $user_query = $this->db->query("SELECT (select user_name from user_header_all where user_id=approve_by) as approve_by, "
                        . "(select user_name from user_header_all where user_id=sign_by)as sign_by,"
                        . "(select user_name from user_header_all where user_id=alloted_to_emp_id) as alloted_to_emp_id"
                        . " FROM `customer_task_allotment_all` where  task_assignment_id='$task_assignment_id' and customer_id='$cust_id' and sub_task_id=''")->result();

        // echo $this->db->last_query();
        if ($user_query != null) {

            foreach ($user_query as $row1) {
                //$response['task_assignment_name'] = $row->task_assignment_name;


                $approve_by = $row1->approve_by;
                $sign_by = $row1->sign_by;
                $alloted_to_emp_id = $row1->alloted_to_emp_id;
            }
        }


        foreach ($cust_task_allot_query as $row) {
            $response['task_assignment_name'] = $row->task_assignment_name;
            $response['total_sub_task'] = $row->total_sub_task;
            $completion_date = $row->completion_date;
        }
   
        if ($cust_task_allot_query != null) {
            //var_dump($response['task_assignment_name']);
            $data = '';
            $data .= '

                 <div class="col-md-12"style="padding-top: 26px !important; ">
                    <h5><b>Project Name:-</b>' . $response['task_assignment_name'] . '</h5>

                       </div>
                          <br>
                        <div class="">
                    <div class="col-md-12" style="padding-top: 26px !important; ">
                          <label>Completion Date:-   ' . $completion_date . '</label>

                        </div>

                     <div class="col-md-12" style="padding-top: 26px !important; ">
                          <label>Approve By:- ' . $approve_by . '</label><br>

                        </div>
                        </div>
                        <div class="">
                        <div class="col-md-12" style="padding-top: 26px !important; ">
                           <label>Sign By:-  ' . $sign_by . '</label><br>

                        </div>
                        <div class="col-md-12" style="padding-top: 26px !important; ">
                          <label>Alloted to:- ' . $alloted_to_emp_id . '</label>
<br>
<br>
												
													<button type="button" class="btn btn-outline-primary btn-sm"  data-toggle="modal" data-target="#sub_task_detail" onclick="task_details(`' . $task_assignment_id . '`)" >Read more</button>	
						</div>';
            $response['Project_detail'] = $data;


            $abcd1 = array();

            $abcd1 = $row->Not_Initiated;
            $aa1 = settype($abcd1, "int");


//            var_dump($abcd1);
            $abcd2 = array();


            $abcd2 = $row->Initiated;
            $aa2 = settype($abcd2, "int");

//            var_dump($abcd2);
            $abcd3 = array();

            $abcd3 = $row->Completed;
            $aa1 = settype($abcd3, "int");

//            var_dump($abcd3);
            $abcd4 = array();


            $abcd4 = $row->Closed;
            $aa1 = settype($abcd4, "int");


            $status1 = array('Initiated', 'Working', 'Completed', 'Closed');
            $s = implode("___", $status1);
            $response['status1'][] = $s;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            $response['branch1'] = $abcd1;
            $response['branch2'] = $abcd2;
            $response['branch3'] = $abcd3;
            $response['branch4'] = $abcd4;

            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function task_details() {

        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
          $email_id = $session_data[0];
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }

        $cust_details = $this->db->query("select * from customer_header_all where customer_id=(select c.customer_id from customer_header_all c left JOIN customer_employee_detail_all e on c.customer_id=e.customer_id where ( c.customer_email_id='$email_id'  or e.employee_email_id='$email_id'))")->row();
        $cust_id = $cust_details->customer_id;
        //var_dump($cust_id);

        $task_assign_id = $this->input->post('task_assignment_id');
        $query3 = $this->db->query("select  `customer_task_allotment_all`.`alloted_to_emp_id`,`customer_task_allotment_all`.`task_assignment_id`,
            `customer_task_allotment_all`.`completion_date`,`customer_task_allotment_all`.`status`, `customer_task_allotment_all`.`task_id`,
            `customer_task_allotment_all`.`sub_task_id`, `task_header_all`.`task_name`, `sub_task_header_all`.`sub_task_name`,`sub_task_header_all`.`parent_sub_task_id`, `user_header_all`.`user_name`
            from `customer_task_allotment_all`
            Inner Join `task_header_all`
            ON `customer_task_allotment_all`.`task_id`=`task_header_all`.`task_id`
            INNER JOIN `sub_task_header_all`
            ON `customer_task_allotment_all`.`sub_task_id`=`sub_task_header_all`.`sub_task_id`
            INNER JOIN `user_header_all`
            ON`customer_task_allotment_all`.`alloted_to_emp_id`=`user_header_all`.`user_id`
            WHERE `customer_task_allotment_all`.`task_assignment_id`='$task_assign_id' and `customer_task_allotment_all`.`customer_id`='$cust_id'")->result();
        // var_dump($query3);
        //echo $this->db->last_query();
        if ($query3 != null) {
            $table = '<tr><th scope="col" style="text-align: center;">Sub task  Name</th>'
                    . '   <th scope="col" style="text-align: center;">Employee name</th>'
                    . '  <th scope="col" style="text-align: center;">Completion date</th>'
                    . '  <th scope="col" style="text-align: center;">Status</th>'
                    . '</tr>';
            foreach ($query3 as $row) {
                if ($row->status == 1) {
                    $table .= '<tr><td>' . $row->sub_task_name . '</td> <td>' . $row->user_name . '</td><td>' . $row->completion_date . '</td><td><span style="width: 110px;"><span style="width: 110px;"><span class="m-badge m-badge--info m-badge--wide">Not Initiate</span></span></td></tr>';
                } else if ($row->status == 2) {
                    $table .= '<tr><td>' . $row->sub_task_name . '</td> <td>' . $row->user_name . '</td><td>' . $row->completion_date . '</td><td><span class="m-badge m-badge--info m-badge--wide"> initiate </span></td></tr>';
                } else if ($row->status == 3) {
                    $table .= '<tr><td>' . $row->sub_task_name . '</td> <td>' . $row->user_name . '</td><td>' . $row->completion_date . '</td><td><span class="m-badge m-badge--success m-badge--wide">Completed</span></td></tr>';
                } else {
                    $table .= '<tr><td>' . $row->sub_task_name . '</td> <td>' . $row->user_name . '</td><td>' . $row->completion_date . '</td><td><span class="kt-badge kt-badge--warning kt-badge--inline"> Close </span></td></tr>';
                }
            }


            $response['Sub_task_table'] = $table;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function survey() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
        $user_id = $session_data[0];
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $user_details = $this->db->select(array("batch_details"))->where("customer_email_id", $user_id)->get("customer_header_all")->result();

        if ($user_details != null) {
            foreach ($user_details as $user) {
                $survey_status_single = $user->batch_details;
                $survey_status_single_array_s = explode(",", $survey_status_single);
            }

            for ($i = 0; $i < count($survey_status_single_array_s); $i++) {
                $survey_status_single_array[] = $survey_status_single_array_s[$i];
                $batch_id = explode(":", $survey_status_single_array_s[$i]);
            }

            $batch_id1[] = $batch_id[0];

            $survey_array = array();
            foreach ($batch_id1 as $survey) {
                $survey_info = array();
                $survey_info["batch_info"] = $batch_id;
                $templete_info = $this->db->query("SELECT th.question_id,th.option_group_id ,sbi.template_id,sbi.batch_name FROM `template_header_all` th join `survey_batch_information_all` sbi  on th.template_id=sbi.template_id WHERE `batch_id`='" . $survey . "'")->row();
                $survey_info["templet_info"] = $templete_info;
//echo $this->db->last_query();
                $all_options = $this->db->select(array("option_id", "option_name"))->where("option_group_id", $templete_info->option_group_id)->get("rating_scale_header_all")->result();

                $survey_info["options"] = $all_options;
                $all_questions = explode(",", $templete_info->question_id);
                $question_array = array();
                foreach ($all_questions as $question_id) {
                    $question = $this->db->select(array("question_id", "question"))->where("question_id", $question_id)->get("question")->row();
                    array_push($question_array, $question);
                }
                $survey_info["questions"] = $question_array;
                array_push($survey_array, $survey_info);
            }

            $data["batch_ids"] = $survey_status_single_array_s;
            $data["batch_id_for_save"] = $survey_status_single_array;
            $data["survey_info"] = $survey_array;
        } else {
            $data["survey_info"] = array();
        }

        echo json_encode($data);
    }

  

}
