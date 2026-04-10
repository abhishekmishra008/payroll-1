<?php

class Super_admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('firm_model');
        $this->load->model('email_sending_model');
        $this->load->model('emp_model');
    }

    public function get_office_count() {
        $boss_id = base64_decode($this->input->post('boss_id'));

        $branch_rs = $this->firm_model->get_branch_details($boss_id);
        if ($branch_rs != false) {
            $i = 1;
            $result = $branch_rs->result();

            $html = '<div class="portlet light bordered" id="OffCo">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase"> Branches</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example1" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="text-align: center;">Sr No </th>
                                            <th scope="col" style="text-align: center;">Branch Name </th>
                                            <th scope="col" style="text-align: center;">Branch Address</th>
                                            <th scope="col" style="text-align: center;">Employee Count </th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            foreach ($result as $row) {
                $firm_id = $row->firm_id; //firm_id


//Branch Employee Count
                $rs_employee = $this->firm_model->getFirmEmployeeDetails($firm_id);
                if ($rs_employee != false) {
                    $employee_count = $rs_employee->num_rows();
                } else {
                    $employee_count = '0';
                }


                $html .= '<tr>
                                    <td style="text-align: center;">' . $i++ . '</td>
                                    <td style="text-align: center;">' . $row->firm_name . '</td>
                                    <td style="text-align: center;">' . $row->firm_address . '</td>
                                    <td style="text-align: center;"><a  class="btn btn green" onclick="total_branch_employee_detail(\'' . $firm_id . '\')"><span data-counter="counterup" data-value="' . $employee_count . '">' . $employee_count . '</span></a> </td>
                                    </tr>
                                   ';
            }
            $html .= '</tbody>
                                </table>
                            </div>
                            </div>
                    </div>
                </div>';
        } else {

        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }

//All Employee List
    public function get_employee_count() {
        $boss_id = base64_decode($this->input->post('boss_id'));

        $employee_rs = $this->firm_model->get_employee_details($boss_id);
        if ($employee_rs != false) {
            $i = 1;
            $result = $employee_rs->result();

            $html = '
                    <div class="portlet-body">

                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example2" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="text-align: center;">Sr No </th>
                                            <th scope="col" style="text-align: center;">Employee Name </th>
                                            <th scope="col" style="text-align: center;">Email Id</th>
                                            <th scope="col" style="text-align: center;">Activity Status</th>
                                            <th scope="col" style="text-align: center;">Branch Name </th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            foreach ($result as $row) {
                $firm_id = $row->firm_id;
                $employee_id = $row->user_id;
                $get_firm_name = $this->firm_model->getFirmName($firm_id);
                if ($get_firm_name != false) {
                    $rs_firm = $get_firm_name->row();
                    $firm_name = $rs_firm->firm_name;
                }




                if ($row->activity_status == '1') {
                    $status = 'Active';
                } else {
                    $status = 'De-activated';
                }

                $html .= '<tr>
                                    <td style="text-align: center;">' . $i++ . '</td>
                                    <td style="text-align: center;">' . $row->user_name . '</td>
                                    <td style="text-align: center;">' . $row->email . '</td>
                                    <td style="text-align: center;">' . $status . '</td>
                                    <td style="text-align: center;">' . $firm_name . '</td>
                                  
                                  </tr>';
            }
            $html .= '</tbody>
                                </table>
                            </div>
                            </div>
                </div>';
        } else {

        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }


    
    public function get_branch_employee() {
        $fetch_firm_id = $this->input->post('firm_id');

        $employee_rs = $this->firm_model->getFirmEmployeeDetails($fetch_firm_id);
        if ($employee_rs != false) {
            $i = 1;
            $result = $employee_rs->result();


            $get_firm_name = $this->firm_model->getFirmName($fetch_firm_id);
            if ($get_firm_name != false) {
                $rs_firm = $get_firm_name->row();
                $firm_name = $rs_firm->firm_name;
            }

            $html = '<div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . $firm_name . '  Employee</span>
                        </div>
                    </div>
                    <div class="portlet-body">

                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example2" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="text-align: center;">Sr No </th>
                                            <th scope="col" style="text-align: center;">Employee Name </th>
                                            <th scope="col" style="text-align: center;">Email Id</th>
                                            <th scope="col" style="text-align: center;">Activity Status</th>
                                            <th scope="col" style="text-align: center;">Branch Name </th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            foreach ($result as $row) {
                $firm_id = $row->firm_id;
                $employee_id = $row->user_id;
                $get_firm_name = $this->firm_model->getFirmName($firm_id);
                if ($get_firm_name != false) {
                    $rs_firm = $get_firm_name->row();
                    $firm_name = $rs_firm->firm_name;
                }

                if ($row->activity_status == '1') {
                    $status = 'Active';
                } else {
                    $status = 'De-activated';
                }


// Employee Task Assignment Count
                $rs_task_assignment = $this->emp_model->getEmployeeTaskAssignment($employee_id, $firm_id);
                if ($rs_task_assignment != false) {
                    $task_assignment_count = $rs_task_assignment;
                } else {
                    $task_assignment_count = '0';
                }

// Employee Task Assignment Count
//                $rs_due_date_task = $this->emp_model->getEmployeeDueDateTask($employee_id,$firm_id);
//                if ($rs_due_date_task != false) {
//                    $due_date_task_count = $rs_due_date_task;
//                } else {
//                    $due_date_task_count = '0';
//                }




                $html .= '<tr>
                                    <td style="text-align: center;">' . $i++ . '</td>
                                    <td style="text-align: center;">' . $row->user_name . '</td>
                                    <td style="text-align: center;">' . $row->email . '</td>
                                    <td style="text-align: center;">' . $status . '</td>
                                    <td style="text-align: center;">' . $firm_name . '</td>
                                    
                                    </tr>
                                   ';
            }
            $html .= '</tbody>
                                </table>
                            </div>
                            </div>
                    </div>
                </div>';
        } else {

        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }

    public function get_duedate_taskname() {

        $due_date_id = $this->input->post('due_date');
// echo"". $due_date_id;
        $firm_id = $this->input->post('firm_id');
        $viewDuedate = $this->due_date_model->view_duedatetask($due_date_id, $firm_id);
        $html = "";

        if ($viewDuedate !== FALSE) {
// echo"hi how";
            $record = $viewDuedate->result();
// } else {
//     $response['status'] = false;
// }
//   $data['page_title'] = "Due Date Detail";
//  $data['prev_title'] = "Due Date Detail";
//  $record1 = $queryOfDuedateDateTask->result();
//$result = $queryOfDuedateDateTask->result();

            $html .= '
            <div class="portlet light portlet-fit portlet-form">

                    <div class="portlet-body">


                            <div class="table-scrollable">
                               <table class="table table-striped table-bordered table-hoverdtr-responsive dataTable no-footer" id="example9" role="grid" aria-describedby="sample_1_info" style="text-align:center;">

                                    <thead>
                                        <tr role="row">


                                                <th style="text-align:center;"> Duedate Task Name </th>
                                                <th style="text-align:center;"> Extended date </th>
                                                <th style="text-align:center;"> Alloted to </th>
                                                <th style="text-align:center;"> Remark </th>
                                                <th style="text-align:center;"> Instruction File </th>
                                                <th style="text-align:center;"> Created By </th>
                                                <th style="text-align:center;"> Created On </th>

                                        </tr>
                                    </thead>
                                    <tbody>';
// var_dump($record);
//  exit();
            foreach ($record as $row) {
                $alloted_to = $row->alloted_to;
                $Extended = $row->extended_date;
// }
                $due_date_task_name = $row->due_date_task_name;
                $alloted_to = $row->alloted_to;
                $remark = $row->remark;
                $instructFile = $row->help_file_attached;
                $Createdby = $row->created_by;
                $Createdon = $row->created_on;
// }
// }

                $html .= '<tr>

                        <td style="text-align: center;">' . $due_date_task_name . '</td>
                            <td style="text-align: center;">' . $Extended . '</td>
                           <td style="text-align: center;">' . $alloted_to . '</td>
                          <td style="text-align: center;">' . $remark . '</td>
                           <td style="text-align: center;">' . $instructFile . '</td>
                                 <td style="text-align: center;">' . $Createdby . '</td>
                                     <td style="text-align: center;">' . $Createdon . '</td>
                       </tr>
                       ';
            }
            $html .= '</tbody>
                    </table>
                    </div>
                    </div>

                </div>';
        } else {
            $html = '<div class="well-lg bg-grey-steel-opacity" id="first_well" style="height: 380px; margin-bottom: 16px;">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <img src="' . base_url() . 'assets/global/img/no-data-found1 (1).png">
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="font-grey-gallery well-sm">
                                            <h2 style="margin-top:4em;">No Due Date Task  Name</h2>


                                        </div>
                                    </div>
                                </div>

                            </div>';
        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }

    public function get_branch_due_date_task() {
        $fetch_firm_id = $this->input->post('firm_id');

        $get_firm_name = $this->firm_model->getFirmName($fetch_firm_id);
        if ($get_firm_name != false) {
            $rs_firm = $get_firm_name->row();
            $firm_name = $rs_firm->firm_name;
        }

        $queryOfDuedateDateTask = $this->due_date_model->branch_duedate_transaction_data($fetch_firm_id);
        $html = "";

        if ($queryOfDuedateDateTask != FALSE) {

            $result = $queryOfDuedateDateTask->result();

            $html .= '<div class="portlet light portlet-fit portlet-form">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . $firm_name . '  Branch DueDate Task</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">

                            <div class="col-md-12">
                                <table id="example8" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr class="row-md-2">
                                        <th>Sr No.</th>
                                        <th   text-align:center;" >Due Date Task Name </th>
                                        <th style="text-align:center;">Last date of submission</th>
                                        <th style="text-align:center;"> Total Customers</th>
                                        <th style="text-align:center;">Not-Initiated</th>
                                        <th style="text-align:center;">Initiated</th>
                                        <th style="text-align:center;">Completed</th>
                                        <th style="text-align:center;">Archived</th>

                                        </tr>
                                    </thead>
                                    <tbody>';

            $i = 1;
            foreach ($result as $row) {

                $due_date_task_id = $row->due_date_task_id;
                $firm_id_dd = $row->firm_id;
                $result_total_comp = $this->db->query("SELECT DISTINCT `customer_id` FROM `customer_due_date_task_transaction_all` WHERE `due_date_task_id`='$due_date_task_id'");
                $total_comp = $result_total_comp->num_rows();

                $result_total_duedatetask_notinitiated = $this->db->query("SELECT * FROM `customer_due_date_task_transaction_all` WHERE `status`='1' AND `due_date_task_id`='$due_date_task_id'");
                $total_duedatetask_notinitiated = $result_total_duedatetask_notinitiated->num_rows();

                $result_total_duedatetask_initiated = $this->db->query("SELECT * FROM `customer_due_date_task_transaction_all` WHERE `status`='2' AND `due_date_task_id`='$due_date_task_id'");
                $total_duedatetask_initiated = $result_total_duedatetask_initiated->num_rows();

                $result_total_duedatetask_completed = $this->db->query("SELECT * FROM `customer_due_date_task_transaction_all` WHERE `status`='3' AND `due_date_task_id`='$due_date_task_id'");
                $total_duedatetask_completed = $result_total_duedatetask_completed->num_rows();

                $result_total_duedatetask_archived = $this->db->query("SELECT * FROM `customer_due_date_task_transaction_all` WHERE `status`='4' AND `due_date_task_id`='$due_date_task_id'");
                $total_duedatetask_archived = $result_total_duedatetask_archived->num_rows();

                $originalDate = $row->last_date_submission;
                $last_date_of_submission = date("d-m-Y", strtotime($originalDate));


                $html .= '<tr>
                        <td style="text-align: center;">' . $i++ . '</td>
                        <td style="text-align: center;">' . $row->due_date_task_name . '</td>
                            <td style="text-align: center;">' . $last_date_of_submission . '</td>
                         <td>
                        <div class="number">
                            <h3 class="font-green-sharp">
                                <small class="font-green-sharp"><i class="icon-user"></i></small>
                                <span data-counter="counterup" data-value="' . $total_comp . '">' . $total_comp . '</span>
                            </h3>
                        </div>
                         </td>

                        <td style="text-align: center;"><a  class="btn btn red" onclick="()"><span data-counter="counterup" data-value="' . $total_duedatetask_notinitiated . '">' . $total_duedatetask_notinitiated . '</span></a> </td>
                        <td style="text-align: center;"><a  class="btn btn blue" onclick="()"><span data-counter="counterup" data-value="' . $total_duedatetask_initiated . '">' . $total_duedatetask_initiated . '</span></a> </td>
                        <td style="text-align: center;"><a  class="btn btn green" onclick="()"><span data-counter="counterup" data-value="' . $total_duedatetask_completed . '">' . $total_duedatetask_completed . '</span></a> </td>
                        <td style="text-align: center;"><a  class="btn btn-info" onclick="()"><span data-counter="counterup" data-value="' . $total_duedatetask_archived . '">' . $total_duedatetask_archived . '</span></a> </td>

                       </tr>
                       ';
            }
            $html .= '</tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                </div>';
        } else {
            $html = "";
        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }

    public function get_branch_task_assignment() {
        $firm_id = $this->input->post('firm_id');

        $get_firm_name = $this->firm_model->getFirmName($firm_id);
        if ($get_firm_name != false) {
            $rs_firm = $get_firm_name->row();
            $firm_name = $rs_firm->firm_name;
        }

        $queryOftask = $this->task_allote_model->branch_task_data($firm_id);
        if ($queryOftask != FALSE) {

            $result = $queryOftask->result();

            $html = '<div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . $firm_name . '  Branch Task</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example9" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                        <th>Sr No.</th>
                                        <th   text-align:center;" >Task Name </th>
                                        <th   text-align:center;" >View Subtask</th>
                                        <th style="text-align:center;">Total Task Assignment</th>
                                        <th style="text-align:center;">Not-Initiated</th>
                                        <th style="text-align:center;">Initiated</th>
                                        <th style="text-align:center;">Completed</th>
                                        <th style="text-align:center;">Archived</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            $i = 1;
            foreach ($result as $row) {
                $task_id = $row->task_id;
                $status = $row->status;
                $result1 = $this->db->query("SELECT (`customer_id`) FROM `customer_task_allotment_all` where `task_id`='$task_id' AND `sub_task_id`=''");
                $total_task_assignement_count = $result1->num_rows();


                $res_not_ini_task = $this->db->query("SELECT DISTINCT (`customer_id`) FROM `customer_task_allotment_all` where `task_id`='$task_id' AND `status`='1' AND `sub_task_id`=''");
                $total_not_ini_tsk = $res_not_ini_task->num_rows();
                if ($res_not_ini_task->num_rows() > 0) {
                    $record_not_ini_tsk = $res_not_ini_task->num_rows();
                } else {
                    $record_not_ini_tsk = "0";
                }
                $res_work_in_prog_task = $this->db->query("SELECT DISTINCT (`customer_id`) FROM `customer_task_allotment_all` where `task_id`='$task_id' AND `status`='2' AND `sub_task_id`=''");
                $total_work_in_prog_task = $res_work_in_prog_task->num_rows();
                if ($res_work_in_prog_task->num_rows() > 0) {
                    $record_work_in_prog_task = $res_work_in_prog_task->num_rows();
                } else {
                    $record_work_in_prog_task = "0";
                }
                $res_complete_task = $this->db->query("SELECT  (`customer_id`) FROM `customer_task_allotment_all` where `task_id`='$task_id' AND `status`='3' AND `sub_task_id`=''");
                $total_complete_tsk = $res_complete_task->num_rows();
                if ($res_complete_task->num_rows() > 0) {
                    $record_comp_tsk = $res_complete_task->num_rows();
                } else {
                    $record_comp_tsk = "0";
                }
                $res_archieve_task = $this->db->query("SELECT (`customer_id`) FROM `customer_task_allotment_all` where `task_id`='$task_id' AND `status`='4' AND `sub_task_id`=''");
                $total_archieve_task = $res_archieve_task->num_rows();
                if ($res_archieve_task->num_rows() > 0) {
                    $record_archeived_tsk = $res_archieve_task->num_rows();
                } else {
                    $record_archeived_tsk = "0";
                }


                $html .= '<tr>
                        <td style="text-align: center;">' . $i++ . '</td>
                        <td style="text-align: center;">' . $row->task_name . '</td>
                        <td style="text-align: center;"><a  class="btn green btn-circle btn-md " data-toggle="modal" href="#view_subt_task_modal"  data-tsk_id="' . $row->task_id . '" data-tsk_name="' . $row->task_name . '"><i class="fa fa-eye"></i></a></td>
                        <td style="text-align: center;"><a  class="btn btn-primary" data-tsk_id="' . $row->task_id . '" data-tsk_name="' . $row->task_name . '"><span data-counter="counterup" data-value="' . $total_task_assignement_count . '">' . $total_task_assignement_count . '</span></a> </td>
                        <td style="text-align: center;"><a  class="btn btn red" data-tsk_id="' . $row->task_id . '" data-tsk_name="' . $row->task_name . '"><span data-counter="counterup" data-value="' . $record_not_ini_tsk . '">' . $record_not_ini_tsk . '</span></a> </td>
                        <td style="text-align: center;"><a  class="btn btn blue" data-tsk_id="' . $row->task_id . '" data-tsk_name="' . $row->task_name . '"><span data-counter="counterup" data-value="' . $record_work_in_prog_task . '">' . $record_work_in_prog_task . '</span></a> </td>
                        <td style="text-align: center;"><a  class="btn btn green-meadow" data-tsk_id="' . $row->task_id . '" data-tsk_name="' . $row->task_name . '"><span data-counter="counterup" data-value="' . $record_comp_tsk . '">' . $record_comp_tsk . '</span></a> </td>
                        <td style="text-align: center;"><a  class="btn btn-info" data-tsk_id="' . $row->task_id . '" data-tsk_name="' . $row->task_name . '"><span data-counter="counterup" data-value="' . $record_archeived_tsk . '">' . $record_archeived_tsk . '</span></a> </td>

                       </tr>
                       ';
            }
            $html .= '</tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                </div>';
        } else {

        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }

    public function all_branch_customer_due_date() {
        $customer_id = base64_decode($this->input->post('customer_id'));
        $firm_id = base64_decode($this->input->post('firm_id'));

        $this->db->distinct();
        $this->db->select('c_d_d_t_t_a.due_date_id,d_d_h_a.due_date_name,d_d_h_a.duration,c_d_d_t_t_a.customer_id,c_h_a.customer_name');
        $this->db->from('customer_due_date_task_transaction_all as c_d_d_t_t_a');
        $this->db->join('due_date_header_all as d_d_h_a', 'c_d_d_t_t_a.due_date_id = d_d_h_a.due_date_id');
        $this->db->join('customer_header_all as c_h_a', 'c_d_d_t_t_a.customer_id = c_h_a.customer_id');
        $this->db->where("c_d_d_t_t_a.customer_id = '$customer_id' and c_d_d_t_t_a.firm_id='$firm_id' and c_d_d_t_t_a.status !='5'");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $record = $query->result();

            $get_customer_name = $this->customer_model->get_customer_name($customer_id);
            if ($get_customer_name != false) {
                $rs_customer = $get_customer_name->row();
                $customer_name = $rs_customer->customer_name;
            }

            $html = '<div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . $customer_name . '</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example10" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                         <th>Sr No</th>
                                                        <th scope="col">Due Date Name</th>
                                                        <th scope="col">Duration</th>
                                                        <th scope="col">Due Date Task Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            $i = 1;
            foreach ($record as $row) {
                $rs_due_date_id = $row->due_date_id;
                $this->db->distinct();
                $this->db->select('c_d_d_t_t_a.due_date_task_id,d_d_t_h_a.due_date_task_name');
                $this->db->from('customer_due_date_task_transaction_all as c_d_d_t_t_a');
                $this->db->join('due_date_task_header_all as d_d_t_h_a', 'c_d_d_t_t_a.due_date_task_id = d_d_t_h_a.due_date_task_id');
                $this->db->where("c_d_d_t_t_a.customer_id = '$customer_id' and c_d_d_t_t_a.due_date_id='$rs_due_date_id' and c_d_d_t_t_a.firm_id='$firm_id'");

                $qry_due_task = $this->db->get();


                if ($qry_due_task->num_rows() > 0) {
                    $due_task_count = $qry_due_task->result();
                    $due_date_task_count = $qry_due_task->num_rows();
                } else {
                    $due_date_task_count = 0;
                }

                $html .= '<tr>
                                <td style = "text-align: center;">' . $i++ . '</td>
                                <td style = "text-align: center;">' . $row->due_date_name . '</td>
                                <td style = "text-align: center;">' . $row->duration . '</td>
                                <td style="text-align: center;"><a  class="btn btn-info" onclick="customer_due_date_task(\'' . $rs_due_date_id . '\',\'' . $firm_id . '\')"><span data-counter="counterup" data-value="' . $due_date_task_count . '">' . $due_date_task_count . '</span></a> </td>
                               </tr>';
            }
            '</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
        } else {
            $html = '';
        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }

    public function all_branch_customer_task_assignment() {
        $customer_id = base64_decode($this->input->post('customer_id'));
        $firm_id = base64_decode($this->input->post('firm_id'));

        $this->db->distinct();
        $this->db->select('c_t_a_a.customer_id,c_h_a.customer_name,c_t_a_a.task_assignment_id,c_t_a_a.task_assignment_description,'
                . 'c_t_a_a.task_assignment_name, c_t_a_a.task_id,t_h_a.task_name,c_t_a_a.completion_date, c_t_a_a.status, c_t_a_a.created_on');
        $this->db->from('customer_task_allotment_all as c_t_a_a');
        $this->db->join('task_header_all as t_h_a', 'c_t_a_a.task_id = t_h_a.task_id');
        $this->db->join('customer_header_all as c_h_a', 'c_t_a_a.customer_id = c_h_a.customer_id');
        $this->db->join('user_header_all as u_h_a', 'c_t_a_a.alloted_to_emp_id = u_h_a.user_id');
        $this->db->where("c_t_a_a.customer_id = '$customer_id' AND c_t_a_a.firm_id = '$firm_id' AND  c_t_a_a.sub_task_id = '' AND  c_t_a_a.status != '5'");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $record = $query->result();


            $get_customer_name = $this->customer_model->get_customer_name($customer_id);
            if ($get_customer_name != false) {
                $rs_customer = $get_customer_name->row();
                $customer_name = $rs_customer->customer_name;
            }

            $html = '<div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . $customer_name . '</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example11" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                         <th>Sr No</th>
                                          <th scope="col" style="text-align:center;">Task Assignment Name</th>
                                                <th scope="col" style="text-align:center;">Task Name</th>

                                                <th scope="col" style="text-align:center;">Completion Date</th>
                                                <th scope="col" style="text-align:center;">Task Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            $i = 1;
            foreach ($record as $row) {
                if ($row->status == '1') {
                    $status = '<span class="label label-sm label-info">  Initiated </span>';
                } else if ($row->status == '2') {
                    $status = '<span class="label label-sm label-warning"> working  </span>';
                } else if ($row->status == '3') {
                    $status = '  <span class="label label-sm label-success"> Completed </span>';
                } else if ($row->status == '4') {
                    $status = ' <span class="label label-sm label-danger"> closed </span>';
                }
                $html .= '<tr>
                                <td style = "text-align: center;">' . $i++ . '</td>
                                <td style = "text-align: center;">' . $row->task_assignment_name . '</td>
                                <td style = "text-align: center;">' . $row->task_name . '</td>
                                <td style = "text-align: center;">' . $row->completion_date . '</td>
                                <td style="text-align: center;">' . $status . '</td>
                               </tr>';
            }
            '</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
        } else {
            $html = '';
        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }

    public function employee_due_date_task() {


        $employee_id = ($this->input->post('employee_id'));
        $firm_id = ($this->input->post('firm_id'));
        $type = $this->input->post('type_id');

        if ($type == 'private') {
            $queryOfprivateDuedateDate = $this->emp_model->privateDueDateTask($employee_id, $firm_id);

            if ($queryOfprivateDuedateDate != false) {
                $record = $queryOfprivateDuedateDate->result();

                $html = '<div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . 'Employee' . '</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example12" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                         <th>Sr No</th>
                                          <th scope="col">Due Task Date Name </th>
                                            <th scope="col"> Customer Name</th>
                                            <th scope="col">Last Date Of Submission</th>
                                            <th scope="col">Alloted To</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                $i = 1;
                foreach ($record as $row) {
                    $due_date_task_id = $row->due_date_task_id;
                    $customer_id = $row->customer_id;
                    $alloted_to = $row->alloted_to;

                    $result_duedatetask_name = $this->db->query("SELECT * FROM `due_date_task_header_all` WHERE `due_date_task_id`='$due_date_task_id'");
                    if ($result_duedatetask_name->num_rows() > 0) {
                        $data = $result_duedatetask_name->row();
                        $due_date_task_name = $data->due_date_task_name;
                    } else {
                        $due_date_task_name = '';
                    }

                    $query_employee = $this->db->query("SELECT * FROM `user_header_all` WHERE  `user_id` = '$alloted_to'");
                    if ($query_employee->num_rows() > 0) {
                        $rs_user = $query_employee->row();
                        $user_name = $rs_user->user_name;
                    } else {
                        $user_name = 'NA';
                    }

                    $qry_cust = $this->customer_model->get_customer_name($customer_id);
                    if ($qry_cust != FALSE) {
                        $cust_data = $qry_cust->row();
                        $customer_name = $cust_data->customer_name;
                    } else {
                        $customer_name = 'NA';
                    }



                    $last_date_of_submission = date('d-m-Y', strtotime($row->last_date_of_submission));

                    $html .= '<tr>
                                <td style = "text-align: center;">' . $i++ . '</td>
                                <td style = "text-align: center;">' . $due_date_task_name . '</td>
                                <td style = "text-align: center;">' . $customer_name . '</td>
                                <td style = "text-align: center;">' . $last_date_of_submission . '</td>
                                <td style = "text-align: center;">' . $user_name . '</td>
                               </tr>';
                }
                '</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
            } else {
                $html = '<div class="well-lg bg-grey-steel-opacity" id="first_well" style="height: 380px; margin-bottom: 16px;">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <img src="' . base_url() . 'assets/global/img/no-data-found1 (1).png">
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="font-grey-gallery well-sm">
                                            <h2 style="margin-top:4em;">No Private Due Date Task </h2>


                                        </div>
                                    </div>
                                </div>

                            </div>';
            }
        } else if ($type == 'public') {
            $queryOfublicDuedateDate = $this->emp_model->publicDueDateTask($employee_id, $firm_id);

            if ($queryOfublicDuedateDate != false) {

                $record = $queryOfublicDuedateDate->result();
                $html = '<div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . 'employee Due Date' . '</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example13" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                         <th>Sr No</th>
                                          <th scope="col">Due Task Date Name </th>
                                            <th scope="col"> Customer Name</th>
                                            <th scope="col">Last Date Of Submission</th>
                                            <th scope="col">Alloted To</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                $i = 1;
                foreach ($record as $row) {
                    $due_date_task_id = $row->due_date_task_id;
                    $customer_id = $row->customer_id;
                    $alloted_to = $row->alloted_to;

                    $result_duedatetask_name = $this->db->query("SELECT * FROM `due_date_task_header_all` WHERE `due_date_task_id`='$due_date_task_id'");
                    if ($result_duedatetask_name->num_rows() > 0) {
                        $data = $result_duedatetask_name->row();
                        $due_date_task_name = $data->due_date_task_name;
                    } else {
                        $due_date_task_name = '';
                    }

                    $query_employee = $this->db->query("SELECT * FROM `user_header_all` WHERE  `user_id` = '$alloted_to'");
                    if ($query_employee->num_rows() > 0) {
                        $rs_user = $query_employee->row();
                        $user_name = $row_employee->user_name;
                    } else {
                        $user_name = '';
                    }

                    $qry_cust = $this->customer_model->get_customer_name($customer_id);
                    if ($qry_cust != FALSE) {
                        $cust_data = $qry_cust->row();
                        $customer_name = $cust_data->customer_name;
                    } else {
                        $customer_name = 'NA';
                    }

                    $last_date_of_submission = date('d-m-Y', strtotime($row->last_date_of_submission));

                    $html .= '<tr>
                                <td style = "text-align: center;">' . $i++ . '</td>
                                <td style = "text-align: center;">' . $due_date_task_name . '</td>
                                <td style = "text-align: center;">' . $customer_name . '</td>
                                <td style = "text-align: center;">' . $last_date_of_submission . '</td>
                                <td style = "text-align: center;">' . $user_name . '</td>
                               </tr>';
                }
                '</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
            } else {
                $html = '<div class="well-lg bg-grey-steel-opacity" id="first_well" style="height: 380px; margin-bottom: 16px;">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <img src="http://35.154.163.72/rmt//assets/global/img/no-data-found1 (1).png">
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="font-grey-gallery well-sm">
                                            <h2 style="margin-top:4em;">No Public Due Date Task </h2>






                                        </div>
                                    </div>
                                </div>

                            </div>';
            }
        }


        $response['suceess'] = $html;
        echo json_encode($response);
    }

    public function employee_task_assignment() {
        $employee_id = ($this->input->post('employee_id'));
        $firm_id = ($this->input->post('firm_id'));
        $type = $this->input->post('type_id');

        if ($type == 'convener') {
            $queryOfprivateDuedateDate = $this->emp_model->employeeConvenerTask($employee_id, $firm_id);

            if ($queryOfprivateDuedateDate != false) {
                $record = $queryOfprivateDuedateDate->result();


                $html = '<div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . 'Employee Task Assignment' . '</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example14" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr role="row">
                                        <th>Sr No</th>
                                                <th style="text-align:center" scope="col">Task Assignment  Name </th>
                                                 <th style="text-align:center" scope="col">Task Assign description  </th>
                                                  <th style="text-align:center" scope="col">Customer Name</th>
                                                <th style="text-align:center" scope="col">Task Name </th>
                                                <th style="text-align:center" scope="col" >Completion Date</th>
                                                <th style="text-align:center" scope="col">Status</th>
                                                <th style="text-align:center" scope="col">View Subtask</th>
                                            </tr>
                                    </thead>
                                    <tbody>';

                $i = 1;
                foreach ($record as $row) {

                    $originalstartDate = $row->completion_date;
                    $newstartDate = date("d-m-Y", strtotime($originalstartDate));


                    if ($row->status == '1') {
                        $status = '<span class="label label-sm label-info"> Not Initiated </span>';
                    } else if ($row->status == '2') {
                        $status = '<span class="label label-sm label-warning"> Initiated  </span>';
                    } else if ($row->status == '3') {
                        $status = '  <span class="label label-sm label-success"> Completed </span>';
                    } else {
                        $status = ' <span class="label label-sm label-danger"> Close </span>';
                    }



                    $html .= '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . $row->task_assignment_name . '</td>
                        <td class="comment more" style="text-align: center;"> ' . $row->task_assignment_description . ' </td>
                        <td>' . $row->customer_name . '</td>
                        <td>' . $row->sub_task_name . '</td>
                        <td class="customerIDCell">' . $newstartDate . '</td>
                        <td> ' . $status . '</td>
                        <td>view</td> </tr>';
                }
                '</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>';
            } else {

                $html = '<div class="well-lg bg-grey-steel-opacity" id="first_well" style="height: 380px; margin-bottom: 16px;">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <img src="http://35.154.163.72/rmt//assets/global/img/no-data-found1 (1).png">
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="font-grey-gallery well-sm">
                                            <h2 style="margin-top:4em;">No Convener Task Assignment</h2>

                                        </div>
                                    </div>
                                </div>

                            </div>';
            }
        } else {
            $queryOfublicDuedateDate = $this->emp_model->employeeTask($employee_id, $firm_id);

            if ($queryOfublicDuedateDate != false) {

                $record = $queryOfublicDuedateDate->result();

                $html = '<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <span class="caption-subject bold uppercase">' . 'Employee Task Assignment' . '</span>
        </div>
    </div>
    <div class="portlet-body">
        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
            <div class="row">
                <div class="col-md-6 col-sm-6">

                </div>
            </div>
            <div class="">
                <table id="example15" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                    <thead>
                        <tr role="row">
                        <th>Sr No</th>
                            <th style="text-align:center" scope="col">Task Assignment  Name </th>
                            <th style="text-align:center" scope="col">Task Assign description  </th>
                            <th style="text-align:center" scope="col">Customer Name</th>
                            <th style="text-align:center" scope="col">Task Name </th>
                            <th style="text-align:center" scope="col" >Completion Date</th>
                            <th style="text-align:center" scope="col">Status</th>
                            <th style="text-align:center" scope="col">View Subtask</th>
                        </tr>
                    </thead>
                    <tbody>';

                $i = 1;

                foreach ($record as $row) {

                    $originalstartDate = $row->completion_date;
                    $newstartDate = date("d-m-Y", strtotime($originalstartDate));


                    if ($row->status == '1') {
                        $status = '<span class="label label-sm label-info"> Not Initiated </span>';
                    } else if ($row->status == '2') {
                        $status = '<span class="label label-sm label-warning"> Initiated  </span>';
                    } else if ($row->status == '3') {
                        $status = '  <span class="label label-sm label-success"> Completed </span>';
                    } else {
                        $status = ' <span class="label label-sm label-danger"> Close </span>';
                    }



                    $html .= '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . $row->task_assignment_name . '</td>
                        <td class="comment more" style="text-align: center;"> ' . $row->task_assignment_description . ' </td>
                        <td>' . $row->customer_name . '</td>
                        <td>' . $row->sub_task_name . '</td>
                        <td class="customerIDCell">' . $newstartDate . '</td>
                        <td> ' . $status . '</td>
                        <td>view</td> </tr>';
                }
                '</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>';
            } else {
                $html = '<div class="well-lg bg-grey-steel-opacity" id="first_well" style="height: 380px; margin-bottom: 16px;">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <img src="http://35.154.163.72/rmt//assets/global/img/no-data-found1 (1).png">
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="font-grey-gallery well-sm">
                                            <h2 style="margin-top:4em;">No Employee Task Assignment</h2>

                                        </div>
                                    </div>
                                </div>

                            </div>';
            }
        }

        $response['suceess'] = $html;
        echo json_encode($response);
    }

    public function total_branch_survey_detail() {
        $firm_id = ($this->input->post('firm_id'));


        $get_firm_name = $this->firm_model->getFirmName($firm_id);
        if ($get_firm_name != false) {
            $rs_firm = $get_firm_name->row();
            $firm_name = $rs_firm->firm_name;
        }

//Branch Survey Count
        $rs_survey = $this->survey_model->getSurveyData($firm_id);
        if ($rs_survey != false) {
            $record = $rs_survey->result();



            $html = '<div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <span class="caption-subject bold uppercase">' . $firm_name . '</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                </div>
                            </div>
                            <div class="">
                                <table id="example16" class="table table-striped table-bordered dtr-responsive table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                         <th>Sr No</th>
                                          <th scope="col" style="text-align:center;">Batch Name</th>
                                          <th scope="col" style="text-align:center;">Start Date</th>
                                          <th scope="col" style="text-align:center;">Complition Date </th>
                                          <th scope="col" style="text-align:center;">End Date </th>
                                                <th scope="col" style="text-align:center;">batch Status</th>
                                                <th scope="col" style="text-align:center;">Survey Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            $i = 1;
            foreach ($record as $row) {


                if ($row->batch_status == '1') {
                    $batch_status = '<span class="label label-sm label-info"> Not Initiated </span>';
                } else if ($row->batch_status == '2') {
                    $batch_status = '<span class="label label-sm label-warning"> Initiated  </span>';
                } else if ($row->batch_status == '3') {
                    $batch_status = '  <span class="label label-sm label-success"> Completed </span>';
                } else {
                    $batch_status = ' <span class="label label-sm label-danger"> Close </span>';
                }


                if ($row->survey_status == '0') {
                    $survey_status = '<span class="label label-sm label-info"> Not Initiated </span>';
                } else if ($row->survey_status == '2') {
                    $survey_status = '<span class="label label-sm label-warning"> Initiated  </span>';
                } else if ($row->survey_status == '3') {
                    $survey_status = '  <span class="label label-sm label-success"> Completed </span>';
                } else {
                    $survey_status = ' <span class="label label-sm label-danger"> Close </span>';
                }




                $html .= '<tr>
                                <td style = "text-align: center;">' . $i++ . '</td>
                                <td style = "text-align: center;">' . $row->batch_name . '</td>
                                <td style = "text-align: center;">' . $row->batch_start_on . '</td>
                                <td style = "text-align: center;">' . $row->batch_completed_on . '</td>
                                <td style = "text-align: center;">' . $row->batch_end_date . '</td>
                                <td style = "text-align: center;">' . $batch_status . '</td>
                                <td style = "text-align: center;">' . $survey_status . '</td>
                               </tr>';
            }
            '</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
        } else {
            $html = '';
        }
        $response['suceess'] = $html;
        echo json_encode($response);
    }

}

?>
