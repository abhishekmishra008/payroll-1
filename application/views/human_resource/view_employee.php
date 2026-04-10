<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username = $this->session->userdata('login_session');

$data['session_data'] = $session_data;
$user_id = ($session_data['user_id']);
$emp_id = ($session_data['emp_id']);

$user_type = ($session_data['user_type']);

?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


<style>
    span.error {
        color: red;
    }
    .tabbable-custom>.nav-tabs>li.active>a {
        font-weight: bold;
    }


</style>
<input type="hidden" id="user_type" value="<?php echo $user_type; ?>">
<!--<script src="../../../assets/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>-->
<div class="page-content-wrapper">
    <div class="page-content">
	<div class="page-bar">

            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'calendar') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>calendar">Home</a>
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>

                </ul>

            </div>
        </div>
        <div class="col-md-12 ">
            <div class="row wrapper-shadow">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="icon-settings font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">View Employee</span>
                                </div>
                                <div class="actions">
                                    <!--                                    <div class="btn-group">

                                    <?php if ($no_of_offices_permitted == $total_no_of_offices) { ?>

                                                                                            <button  class="btn blue-hoki btn-outline sbold uppercase popovers disabled"
                                                                                                     data-container="body" data-trigger="hover" data-placement="left"
                                                                                                     data-content="You have exceeded your limit for creating employee. Your limit to create no of employee was <?php echo $no_of_offices_permitted; ?> "> <i class="fa fa-plus"></i>
                                                                                            </button>

                                    <?php } else { ?>
                                                                                            <a href="<?= base_url("Employee_hq") ?>">
                                                                                                <button  class="btn blue-hoki btn-outline sbold uppercase popovers "
                                                                                                         data-container="body" data-trigger="hover" data-placement="left"
                                                                                                         data-content="Create employee"> <i class="fa fa-plus"></i>
                                                                                                </button>
                                                                                            </a>
                                    <?php } ?>
                                                                        </div>-->
                                    <?php  if($user_id=='hrmanager@telgeprojects.com' && $user_type=='5' && $emp_id=='U_505' && $firm_id=='Firm_1032'){ ?>

                                    <?php  }else{ ?>
                                        <div class="btn-group">
                                            <a href="<?= base_url("Employee_hq") ?>">
                                                <button  class="btn blue-hoki btn-outline sbold uppercase popovers "
                                                         data-container="body" data-trigger="hover" data-placement="left"
                                                         data-content="Create employee"> <i class="fa fa-plus"></i>
                                                </button>
                                            </a>
                                        </div>
                                 <?php   } ?>
                                   <!-- <div class="btn-group">
                                        <a href="<?/*= base_url("Employee_hq") */?>">
                                            <button  class="btn blue-hoki btn-outline sbold uppercase popovers "
                                                     data-container="body" data-trigger="hover" data-placement="left"
                                                     data-content="Create employee"> <i class="fa fa-plus"></i>
                                            </button>
                                        </a>
                                    </div>-->

                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php if ($user_type == 5) { ?>

                                        <!--<input type="text" id="ddl_firm_name_fetch" name="ddl_firm_name_fetch" value="0">-->
                                <?php } else {
                                    ?>
                                    <div class="row well-sm " id="table_well">
                                        <div class="col-md-4">
                                            <label>Office Name
                                            </label>
                                            <select class="form-control m-select2 m-select2-general" id="ddl_firm_name_fetch" name="ddl_firm_name_fetch" onchange="get_sorted_data()">
                                                <option value="">Select option</option>

                                            </select>

                                            <span class="required" id="ddl_firm_name_fetch_error"></span>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="firmname">
                                                <label>Selected Office Name</label>
                                                <div class="caption font-red-sunglo">
                                                    <i class="fa fa-bank"></i>
                                                    <?php if ($firm_name !== '') { ?>
                                                        <span class="caption-subject bold "><?php echo $firm_name; ?> </span>
                                                        <input type="hidden" id="firm_id" name="firm_id" value="<?php echo $firm_id ?>">
                                                    <?php } else {
                                                        ?> <input type="hidden" id="firm_id" name="firm_id" value="0">
                                                        <span class="caption-subject bold "><?php echo 'Please select office...'; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                ?>
    
                                <div class="row well-lg " id="table_well">
                                    <div class="tabbable-custom ">
                                        <ul class="nav nav-tabs ">
                                            <li class="active">
                                                <a href="#activeEmployeeList" data-toggle="tab"> Active Employee List</a>
                                            </li>
                                            <li>
                                                <a href="#inactiveEmployeeList" data-toggle="tab" id="tab_5_2_attend">Inactive Employee List </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="activeEmployeeList">
                                                <div class="button-group" style="margin-bottom: 20px; float: right;">
                                                    <button class="btn btn-primary" onclick="downloadExcel()" style="">Download Excel</button>
                                                    <button class="btn btn-primary" onclick="downloadEmployeeExcel()">Company Employees</button>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_11" role="grid" aria-describedby="sample_1_info" style="text-align:center;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th scope="col" style="text-align: center; width:90px;">Name</th>
                                                            <th style="text-align: center;" scope="col">Designation</th>
                                                            <th style="text-align: center;" scope="col">Date Of <br>Joining</th>
                                                            <th style="text-align: center;" scope="col">Last Date</th>
                                                            <th style="text-align: center;" scope="col">Address</th>
                                                            <th style="text-align: center;" scope="col">Email Id</th>
                                                            <th style="text-align: center;" scope="col">Employee Code</th>
                                                            <th style="text-align: center;" scope="col">Contact No</th>
                                                            <th style="text-align: center;" scope="col"> Status</th>
                                                            <th style="text-align: center;" scope="col">Actions</th>
                                                            <th style="text-align: center;" scope="col">Run Past Salary</th>
                                                            <!--<th style="text-align: center;" scope="col">Salary Information</th>-->

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        if ($record != "") {
                                                            foreach ($record->result() as $row) {
                                                                if ($row->activity_status != '1') {
                                                                    continue;
                                                                }
                                                                $user_id = $row->user_id;
                                                                ?>
                                                                <tr>
                                                                    <!-- <td>
                                                                        <?php echo $i; ?>
                                                                    </td> -->
                                                                    <td style="text-align: center;width:140px;">
                                                                        <?php echo $row->user_name; ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php
                                                                        echo $row->designation_name;
                                                                        ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php
                                                                            $originalDate = $row->date_of_joining;
                                                                            $newcompletionDate = date("d-m-Y", strtotime($originalDate));
                                                                            echo $newcompletionDate;
                                                                        ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php
                                                                            $originalDate = $row->exit_date;
                                                                            if(empty($originalDate)){
                                                                                $newcompletionDate="";
                                                                            }else{
                                                                                $newcompletionDate = date("d-m-Y", strtotime($originalDate));
                                                                            }

                                                                            echo $newcompletionDate;
                                                                        ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php echo "$row->address,&nbsp&nbsp$row->city,&nbsp&nbsp$row->country " ?>
                                                                    </td>
                                                                    <!--  <td style="text-align: center;">
                                                                        <?php // echo $row->city; ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php // echo $row->country; ?>
                                                                    </td>-->
                                                                    <td style="text-align: center;">
                                                                        <?php echo $row->email; ?>
                                                                    </td> <td style="text-align: center;">
                                                                        <?php echo $row->emp_code; ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php echo $row->mobile_no; ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php if ($row->activity_status == '1') { ?>                                                                                                                                                <!--<button type="button" class="btn btn-circle green-meadow" onclick="change_emp_status('<?php echo base64_encode($user_id); ?>', 'A')">Active</button>-->
                                                                            <button type="button" class="btn green-meadow" onclick="change_status('<?php echo base64_encode($row->user_id); ?>', 'A')">Active</button>
                                                                        <?php } else { ?>
                                                                            <button type="button" class="btn red" onclick="change_status('<?php echo base64_encode($row->user_id); ?>', 'D')">De-active</button>
                                                                        <?php }
                                                                        ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <a href="<?= base_url("hr_edit_emp_data/" . $row->user_id . "/" . $row->firm_id); ?>" id="sample_new" href="" class="btn  btn-link btn-sm" data-toggle="modal" title="EDIT" ><i class="fa fa-pen" style="font-size:22px;"></i></a>
                                                                    </td>
                                                                    <!--<td>   <a href="#" data-emp_idx="<?php echo $row->user_id ?>" data-firm_id_a="<?php echo $row->firm_id; ?>" data-emp_id="<?php echo $row->user_id; ?>" data-firm_id_a1="<?php echo $row->firm_id; ?>" data-emp_id1="<?php echo $row->user_id; ?>"data-firm_id_a2="<?php echo $row->firm_id; ?>" data-emp_id2="<?php echo $row->user_id; ?>" data-firm_id_a3="<?php echo $row->firm_id; ?>" data-emp_id3="<?php echo $row->user_id; ?>" id="sample_new" class="btn  btn-link btn-md"  data-target="#exampleModal" data-toggle="modal" title="Salary Related Information"><i class="fa fa-money" style="font-size:22px;"></i></a></td>-->
                                                                    <td style="text-align: center;">
                                                                        <a href="<?= base_url("run_past_salary/" . $row->user_id ); ?>" id="sample_new" href="" class="btn  btn-link btn-md" data-toggle="modal" title="EDIT" ><i class="fa fa-money" style="font-size:22px;"></i></a>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {

                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="inactiveEmployeeList">
                                                
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_12" role="grid" aria-describedby="sample_1_info" style="text-align:center;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th scope="col" style="text-align: center; width:90px;">Name</th>
                                                            <th style="text-align: center;" scope="col">Designation</th>
                                                            <th style="text-align: center;" scope="col">Date Of <br>Joining</th>
                                                            <th style="text-align: center;" scope="col">Last Date</th>
                                                            <th style="text-align: center;" scope="col">Address</th>
                                                            <th style="text-align: center;" scope="col">Email Id</th>
                                                            <th style="text-align: center;" scope="col">Employee Code</th>
                                                            <th style="text-align: center;" scope="col">Contact No</th>
                                                            <th style="text-align: center;" scope="col"> Status</th>
                                                            <th style="text-align: center;" scope="col">Actions</th>
                                                            <th style="text-align: center;" scope="col">Run Past Salary</th>
                                                            <!--<th style="text-align: center;" scope="col">Salary Information</th>-->

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        if ($record != "") {
                                                            foreach ($record->result() as $row) {
                                                                if ($row->activity_status != '0') {
                                                                    continue;
                                                                }
                                                                $user_id = $row->user_id;
                                                                ?>
                                                                <tr>
                                                                    <!-- <td>
                                                                        <?php echo $i; ?>
                                                                    </td> -->
                                                                    <td style="text-align: center;width:140px;">
                                                                        <?php echo $row->user_name; ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php
                                                                        echo $row->designation_name;
                                                                        ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php
                                                                            $originalDate = $row->date_of_joining;
                                                                            $newcompletionDate = date("d-m-Y", strtotime($originalDate));
                                                                            echo $newcompletionDate;
                                                                        ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php
                                                                            $originalDate = $row->exit_date;
                                                                            if(empty($originalDate)){
                                                                                $newcompletionDate="";
                                                                            }else{
                                                                                $newcompletionDate = date("d-m-Y", strtotime($originalDate));
                                                                            }

                                                                            echo $newcompletionDate;
                                                                        ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php echo "$row->address,&nbsp&nbsp$row->city,&nbsp&nbsp$row->country " ?>
                                                                    </td>
                                                                    <!--  <td style="text-align: center;">
                                                                        <?php // echo $row->city; ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php // echo $row->country; ?>
                                                                    </td>-->
                                                                    <td style="text-align: center;">
                                                                        <?php echo $row->email; ?>
                                                                    </td> <td style="text-align: center;">
                                                                        <?php echo $row->emp_code; ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php echo $row->mobile_no; ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <?php if ($row->activity_status == '1') { ?>                                                                                                                                                <!--<button type="button" class="btn btn-circle green-meadow" onclick="change_emp_status('<?php echo base64_encode($user_id); ?>', 'A')">Active</button>-->
                                                                            <button type="button" class="btn green-meadow" onclick="change_status('<?php echo base64_encode($row->user_id); ?>', 'A')">Active</button>
                                                                        <?php } else { ?>
                                                                            <button type="button" class="btn red" onclick="change_status('<?php echo base64_encode($row->user_id); ?>', 'D')">De-active</button>
                                                                        <?php }
                                                                        ?>
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <a href="<?= base_url("hr_edit_emp_data/" . $row->user_id . "/" . $row->firm_id); ?>" id="sample_new" href="" class="btn  btn-link btn-sm" data-toggle="modal" title="EDIT" ><i class="fa fa-pen" style="font-size:22px;"></i></a>
                                                                    </td>
                                                                    <!--<td>   <a href="#" data-emp_idx="<?php echo $row->user_id ?>" data-firm_id_a="<?php echo $row->firm_id; ?>" data-emp_id="<?php echo $row->user_id; ?>" data-firm_id_a1="<?php echo $row->firm_id; ?>" data-emp_id1="<?php echo $row->user_id; ?>"data-firm_id_a2="<?php echo $row->firm_id; ?>" data-emp_id2="<?php echo $row->user_id; ?>" data-firm_id_a3="<?php echo $row->firm_id; ?>" data-emp_id3="<?php echo $row->user_id; ?>" id="sample_new" class="btn  btn-link btn-md"  data-target="#exampleModal" data-toggle="modal" title="Salary Related Information"><i class="fa fa-money" style="font-size:22px;"></i></a></td>-->
                                                                    <td style="text-align: center;">
                                                                        <a href="<?= base_url("run_past_salary/" . $row->user_id ); ?>" id="sample_new" href="" class="btn  btn-link btn-md" data-toggle="modal" title="EDIT" ><i class="fa fa-money" style="font-size:22px;"></i></a>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {

                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <?php $this->load->view('human_resource/footer'); ?>
        <script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

    </div>
</div>
<!--modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Salary Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                  <input type="hidden" id="firm_id" name="firm_id" value="<?php echo $firm_id ?>">
                <input type="hidden" name="emp_idx" id="emp_idx" value=""><input type="hidden" name="firm_idx" id="firm_idx" value="">
                <!--<form id="Salary_Details" name="Salary_Details" method="POST">-->
                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_5_1" data-toggle="tab" onclick="get_data()"> Performance Allowance</a>
                        </li>
                        <li>
                            <a href="#tab_5_2" data-toggle="tab"  onclick="update_loan()"> Staff Loan </a>
                        </li>
                        <li>
                            <a href="#tab_5_3" data-toggle="tab" onclick="get_view_salary_details()"> Salary Details </a>
                        </li>
                        <li>
                            <a href="#tab_5_4" data-toggle="tab" onclick="get_view_Deu()"> Deductions Details </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_5_1">

                            <div class="form-body">


                                <form id="performance_allowance" name="performance_allowance" method="POST">
                                    <input type="hidden" name="emp_id" id="emp_id" value="">
                                    <input type="hidden" name="firm_id_a" id="firm_id_a" value="">
<!--                                    <div class="form-group">
                                        <label class="control-label">Performance  Bonus</label>
                                        <input type="text" class="form-control" placeholder="Enter Performance  Bonus" id="value" name="value">
                                    </div>
                                    <div class="form-group">
                                        <label>Start Month</label>
                                        <select name="Month"  id="Month" class="form-control select2 select2-hidden-accessible">
                                            <option value="">Select Month</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">Aug</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Financial Year</label>
                                       <select id="years" name="years" class="form-control" ></select>
                                        <input type="text" id="years" name="years" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Date</label> <input type="Date" class="form-control" placeholder="Enter Date " id="Date_of_PA" name="Date_of_PA">
                                    </div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" >Save changes</button>-->
                                    <!--<button type="button" class="btn btn-warning"  onclick="get_data()">Show History</button>-->
                                    <div id="data_tablediv"></div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_5_2">
                            <div class="form-body">
                                <form id="staff_loan" name="staff_loan" method="POST">
                                    <input type="hidden" name="emp_id1" id="emp_id1" value="">
                                    <input type="hidden" name="firm_id_a1" id="firm_id_a1" value="">

<!--                                    <div class="form-group">
                                        <label>Loan Details</label>
                                        <input type="text" class="form-control" placeholder="Enter Loan Details" id="loan_detail" name="loan_detail">
                                    </div>
                                    <div class="form-group">
                                        <label>Loan Amount</label>
                                        <input type="text" class="form-control" placeholder="Enter Loan Amount" id="amount" name="amount">
                                    </div>
                                    <div class="form-group">
                                        <label>EMI Amount</label>
                                        <input type="text" class="form-control" placeholder="EnterEMI Amount" id="EMI_amt" name="EMI_amt">
                                    </div>
                                    <div class="form-group">
                                        <label>Start Month</label>
                                        <select name="from_month"  id="from_month"   class="form-control select2 select2-hidden-accessible">
                                            <option value="">Select Month</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">Aug</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Financial Year</label>
                                        <select name="Fyear"  id="Fyear"   class="form-control select2 select2-hidden-accessible"></select>
                                    </div>-->
<!--                                    <div class="form-group">
                                        <label class="control-label">Total Month</label>
                                        <input type="text" class="form-control" placeholder="Enter Total Month" id="total_month" name="total_month">
                                    </div>
                                    <div class="form-group">
                                        <label>Sanction Date</label>
                                        <input type="Date" class="form-control" placeholder="Enter Sanction Date" id="sanction_date" name="sanction_date">
                                    </div>-->
<!--                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="save">Save changes</button>-->
                                    <!--<button type="submit" class="btn btn-primary" id="update" style="display: none" onclick="update_loan()">Update changes</button>                                    <button type="button" class="btn btn-warning" onclick="get_data_staffloanhistory()">Show History</button>-->
                                    <div id="data_staffloanhistory"></div>
                                </form>

                            </div>
                        </div>
                        <div class="tab-pane" id="tab_5_3">
                            <div class="form-body">
                                <form id="salary_details" name="salary_details" method="POST"><!--<!--
-->                                    <input type="hidden" name="emp_id2" id="emp_id2" value="">
                                    <input type="hidden" name="firm_id_a2" id="firm_id_a2" value="">
<!--                                   <div class="form-group">
                                        <label>Type</label>
                                        <select name="sal_options"  id="sal_options"    class="form-control m-select2 m-select2-general">


                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label> Amount</label>
                                        <input type="text" class="form-control" placeholder="Enter  Amount" id="value" name="value">
                                    </div>-->
<!--                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>-->
                                    <!--<button type="button" class="btn btn-warning" >Show History</button>-->
                                    <div id="data_salry_Details"></div>
                                </form>

                            </div>
                        </div>
                        <div class="tab-pane" id="tab_5_4">
                            <div class="form-body">
                                <form id="due_details" name="due_details" method="POST">

                                    <input type="hidden" name="emp_id3" id="emp_id3" value="">
                                    <input type="hidden" name="firm_id_a3" id="firm_id_a3" value="">

<!--                                    <div class="form-group">
                                        <label>Taxs</label>
                                        <select name="ded_options"  id="ded_options"   class="form-control select2 select2-hidden-accessible">

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label> Amount</label>
                                        <input type="text" class="form-control" placeholder="Enter  Amount" id="value" name="value">
                                    </div>-->
<!--                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" >Save changes</button>-->
                                    <!--<button type="button" class="btn btn-warning" onclick="get_view_Deu()">Show History</button>-->

                                    <div id="show_due_details"></div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="employee_import_modal" role="dialog">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Download Employee Excel Formate</h4>
                <a href="<?= base_url("employee_excel_formate") ?>" class="btn btn-link">Download Formate</a>
            </div>
            <div class="modal-body">

                <div class="row JustifyCenter">

                    <form name="excel_form_upload" id="excel_form_upload">
                        <div class="col-md-8">
                            <input type="file"  id="user_id" name="excel_file" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button class=" btn btn-primary">Upload</button>
                        </div>


                    </form>

                </div>
                <div class="form-group">
                    <label style="font-weight:bold;">Check Excel Data:</label>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="employee_excel_table" role="grid" aria-describedby="sample_1_info">
                            <thead>
                                <tr role="row">
                                    <!--<th scope="col"> Sr No</th>-->
                                    <th scope="col">Branch Name</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">State</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Country</th>
                                    <th scope="col">Senior Employee</th>
                                    <th scope="col">Work on Services</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">Date Joining</th>
                                    <th scope="col">Password</th>
                                    <th scope="col">Probation Period Start Date</th>
                                    <th scope="col">Probation Period End Date</th>
                                    <th scope="col">Training Period Start Date</th>
                                    <th scope="col">Training Period End Date</th>

                                    <th scope="col">Task Creation Permission</th>
                                    <th scope="col">Task Assignment Creation</th>
                                    <th scope="col">Due Date Creation</th>
                                    <th scope="col">Due Date Task Assignment Creation</th>
                                    <th scope="col">Services Creation</th>
                                    <th scope="col">Generate Enquiry</th>
                                    <th scope="col">Permission To Approve Leave</th>
                                </tr>
                            </thead>
                            <tbody id="employee_excel_body"></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<script src="<?php echo base_url() . "assets/"; ?>js/nas.js" type="text/javascript"></script>-->
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>


                                        function change_status(user_id, status) {
//                                            alert(user_id);
                                            var temp_status = '';
                                            if (status == 'A') {
                                                temp_status = 'De-activate';
                                            } else {
                                                temp_status = 'Active';
                                            }
                                            if (confirm('Are you sure you wants to ' + temp_status + ' employee. If yes click on OK else cancel')) {
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("Employee/change_activity_status") ?>",
                                                    dataType: "json",
                                                    data: {user_id: user_id, status: status},
                                                    success: function (result) {
                                                        // alert(result.error);
                                                        if (result.status === true) {
                                                            if (result.msg === 'A') {
                                                                alert('Employee deactivated successfully.');
                                                            } else {
                                                                alert('Employee activated successfully.');
                                                            }
                                                            // return;
                                                            location.reload();
                                                        }else if(result.code === 201){
                                                           alert("For this employee salary was not set! Please add the salary details.");
                                                        }
                                                        else if(result.code === 202){
                                                           alert("For this employee attendance was not set! Please add the employee details.");
                                                        }
                                                        else if (result.status === false) {
                                                            alert('Something went wrong.');
                                                        } else {
                                                            $('#message').html(result.error);
                                                        }
                                                    },
                                                    error: function (result) {
                                                        console.log(result);
                                                        if (result.status === 500) {
                                                            alert('Internal error: ' + result.responseText);
                                                        } else {
                                                            alert('Unexpected error.');
                                                        }
                                                    }
                                                });
                                            } else {

                                            }
                                        }

                                        $('#Fyear').each(function () {

                                            var year = (new Date()).getFullYear();
                                            var current = year;
                                            year -= 3;
                                            for (var i = 0; i < 6; i++) {
                                                if ((year + i) == current)
                                                    $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
                                                else
                                                    $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
                                            }

                                        })



//    $('#years').each(function() {
//
//  var year = (new Date()).getFullYear();
//  var current = year;
//  year -= 3;
//  for (var i = 0; i < 6; i++) {
//    if ((year+i) == current)
//      $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
//    else
//      $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
//  }
//
//})
// start of done by bhava
                                        function delete_PA(id) {
                                            var result = confirm("Do You Want to delete?");
                                            if (result) {
                                                $.ajax({
                                                    url: '<?= base_url("Employee/delete_pa") ?>',
                                                    type: "POST",
                                                    data: {id: id},
                                                    cache: false,
                                                    async: false,
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.status == 200) {
                                                            alert(success.body);

                                                        } else {
                                                            alert(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        alert("Something went to wrong.");
                                                    }
                                                });
                                            } else {

                                            }

                                        }
                                        function delete_salary(id) {
                                            var result = confirm("Do you want to delete?");
                                            if (result) {
                                                $.ajax({
                                                    url: '<?= base_url("Employee/delete_Salary") ?>',
                                                    type: "POST",
                                                    data: {id: id},
                                                    cache: false,
                                                    async: false,
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.status == 200) {
                                                            alert(success.body);

                                                        } else {
                                                            alert(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        alert("Something went to wrong.");
                                                    }
                                                });
                                            } else {

                                            }
                                        }
                                        function delete_deu(id) {
                                            var result = confirm("Do you want to delete?");
                                            if (result) {
                                                $.ajax({
                                                    url: '<?= base_url("Employee/delete_deduction") ?>',
                                                    type: "POST",
                                                    data: {id: id},
                                                    cache: false,
                                                    async: false,
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.status == 200) {
                                                            alert(success.body);

                                                        } else {
                                                            alert(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        alert("Something went to wrong.");
                                                    }
                                                });
                                            } else {

                                            }
                                        }


                                        function get_data()
                                        {
                                            var emp_id = document.getElementById('emp_idx').value;

                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/view_performance_allowance") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {employee_id: emp_id},
                                                success: function (result) {
                                                    var data = result.result_data;
                                                    if (result.status == 200) {
                                                        $('#data_tablediv').html(data);
                                                        $('#data_table').DataTable();
                                                    } else {
                                                        $('#data_tablediv').html("");
                                                    }
                                                },
                                            });
                                        }

                                        function get_data_staffloanhistory() {
                                            var emp_id = document.getElementById('emp_idx').value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/view_staff_loan") ?>",
                                                dataType: "json",
//                                                data: $("#staff_loan").serialize(),
                                                async: false,
                                                cache: false,
                                                data: {emp_id1: emp_id},
                                                success: function (result) {
//                                                    result = JSON.parse(result);
                                                    var data = result.result_data;
                                                    if (result.status == 200) {
                                                        $('#data_staffloanhistory').html(data);
                                                        $('#data_table1').DataTable();
                                                    } else {
                                                        $('#data_staffloanhistory').html("");
                                                    }
                                                }

                                            });
                                        }
                                        function get_view_salary_details() {
                                            var emp_id = document.getElementById('emp_idx').value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/view_salary_details") ?>",
                                                dataType: "JSON",
//                                                data: $("#salary_details").serialize(),
                                                async: false,
                                                cache: false,
                                                data: {emp_id2: emp_id},
                                                success: function (result) {
//                                                   result = JSON.parse(result);
                                                    var data = result.result_data1;
                                                    if (result.status == 200) {
                                                        $('#data_salry_Details').html(data);
                                                        $('#data_table2').DataTable();
                                                    } else {
                                                        $('#data_salry_Details').html("");
                                                    }
                                                }
                                            });
                                        }
                                        function get_view_Deu() {
                                            var emp_id = document.getElementById('emp_idx').value;

                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/view_Deu") ?>",
                                                dataType: "JSON",
//                                                data: $("#due_details").serialize(),
                                                async: false,
                                                cache: false,
                                                data: {emp_id3: emp_id},
                                                success: function (result) {
//                                                alert();
//                                                   result = JSON.parse(result);
                                                    var data = result.result_data3;
                                                    if (result.status == 200) {
                                                        $('#show_due_details').html(data);
                                                        $('#result_data3').DataTable();
                                                    } else {
                                                        $('#show_due_details').html("");
                                                    }
                                                }
                                            });
                                        }
                                        $("#performance_allowance").validate({
                                            rules: {
                                                value: {
                                                    required: true

                                                },
                                                Month: {
                                                    required: true
                                                },
                                                years: {
                                                    required: true
                                                },
                                                Date_of_PA: {
                                                    required: true
                                                }

                                            },
                                            messages: {
                                                value: {
                                                    required: "Enter Performance Allowance Bonus"
                                                },
                                                Month: {
                                                    required: "Select Month"
                                                },
                                                years: {
                                                    required: "Enter Financial Year"
                                                },
                                                Date_of_PA: {
                                                    required: "Enter Date"
                                                }

                                            }, errorElement: "span",
                                            submitHandler: function (form) { // for demo

                                                $.ajax({
                                                    url: '<?= base_url("Employee/add") ?>',
                                                    type: "POST",
                                                    datatype: "JSON",
                                                    data: $("#performance_allowance").serialize(),
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.status === true) {
                                                            alert("Performance allowance added  Successfully.");
                                                            get_data();
                                                            document.getElementById('performance_allowance').reset();
//                                                            $('#exampleModal').modal('hide');
///                        $('#create_customer_modal').modal("toggle");//modal name if not remove
                                                        } else {
//                        $('#create_customer_modal').modal("toggle");

//                                                                toastr.error(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
//                                                             alert("s");
                                                        toastr.error(success.body);
//                    $('#create_customer_modal').modal("toggle");
                                                        console.log(error);
                                                        errorNotify("Something went to wrong.");
                                                    }
                                                });
                                            }
                                        });
                                        $(document).ready(function () {

											$('#sample_11').dataTable({
												/* No ordering applied by DataTables during initialisation */
												"order": []
											});
											$('#sample_12').dataTable({
												/* No ordering applied by DataTables during initialisation */
												"order": []
											});
											
                                            var user_type = document.getElementById('user_type').value;
                                            if (user_type == 5) {
                                                get_sorted_data();
                                            } else {
                                            }
                                            get_data();
                                            get_data_staffloanhistory();
                                            get_view_salary_details();
                                            get_view_Deu();
                                            get_sal_type_list();
                                            get_ded_type_list();

                                            //add_loan

                                            $("#staff_loan").validate({
                                                rules: {
                                                    loan_detail: {required: true},
                                                    amount: {required: true},
                                                    EMI_amt: {required: true},
                                                    from_month: {required: true},
                                                    Fyear: {required: true},
                                                    total_month: {required: true},
                                                    sanction_date: {required: true},
                                                },
                                                messages: {
                                                    loan_detail: {required: "Enter Loan Details"},
                                                    amount: {equired: "Enter The Amount"},
                                                    EMI_amt: {
                                                        required: "Enter EMI Amount"
                                                    },
                                                    from_month: {required: "Select Month"},
                                                    FYear: {required: "Select financial Year"},
                                                    total_month: {required: "Enter Total Month"},
                                                    sanction_date: {required: "Enter Date"}

                                                }, errorElement: "span",
                                                submitHandler: function (form) { // for demo
                                                    $.ajax({
                                                        url: '<?= base_url("add_loan") ?>',
                                                        type: "POST",
                                                        data: $("#staff_loan").serialize(),
                                                        success: function (success) {
                                                            success = JSON.parse(success);
                                                            if (success.status === true) {
                                                                alert("Staff loan added  successfully.");


                                                            } else {

                                                                toastr.error(success.body); //toster.error
                                                            }
                                                        },
                                                        error: function (error) {
                                                            toastr.error(success.body);
                                                            console.log(error);
                                                            errorNotify("Something went to wrong.");
                                                        }
                                                    });
                                                }
                                            });
                                            //add_salary
                                            $("#salary_details").validate({
                                                rules: {

                                                    sal_options: {required: true},
                                                    value: {required: true},

                                                },
                                                messages: {
                                                    sal_options: {required: "Select Salary Type"},
                                                    value: {equired: "Enter The Amount"}

                                                }, errorElement: "span",
                                                submitHandler: function (form) { // for demo
                                                    $.ajax({
                                                        url: '<?= base_url("add_salary") ?>',
                                                        type: "POST",
                                                        data: $("#salary_details").serialize(),
                                                        success: function (success) {
                                                            success = JSON.parse(success);
                                                            if (success.status === true) {
                                                                alert("Salary  details added  successfully.");
///                        $('#create_customer_modal').modal("toggle");//modal name if not remove

                                                            } else {
//                        $('#create_customer_modal').modal("toggle");
                                                                toastr.error(success.body); //toster.error
                                                            }
                                                        },
                                                        error: function (error) {
                                                            toastr.error(success.body);
//                    $('#create_customer_modal').modal("toggle");
                                                            console.log(error);
                                                            errorNotify("Something went to wrong.");
                                                        }
                                                    });
                                                }
                                            });

                                            //add_deu
                                            $("#due_details").validate({
                                                rules: {
                                                    ded_options: {required: true},
                                                    value: {required: true}

                                                },
                                                messages: {
                                                    ded_options: {required: "Select Tax Type"},
                                                    value: {equired: "Enter The Amount"},

                                                }, errorElement: "span",
                                                submitHandler: function (form) { // for demo
                                                    $.ajax({
                                                        url: '<?= base_url("add_due") ?>',
                                                        type: "POST",
                                                        data: $("#due_details").serialize(),
                                                        success: function (success) {
                                                            success = JSON.parse(success);
                                                            if (success.status === true) {
                                                                alert(" Deductions details added  Successfully.");
///                        $('#create_customer_modal').modal("toggle");//modal name if not remove

                                                            } else {
//                        $('#create_customer_modal').modal("toggle");
                                                                toastr.error(success.body); //toster.error
                                                            }
                                                        },
                                                        error: function (error) {
                                                            toastr.error(success.body);
//                    $('#create_customer_modal').modal("toggle");
                                                            console.log(error);
                                                            errorNotify("Something went to wrong.");
                                                        }
                                                    });
                                                }
                                            })

                                        });


                                        function get_sal_type_list()
                                        {
                                            var firm_id = document.getElementById('firm_id').value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("SalaryInfoController/get_sal_type_list") ?>",
                                                dataType: "JSON",
                                                async: false,
                                                cache: false,
                                                data: {firm_id: firm_id},
                                                success: function (result) {
//                                                                     result = JSON.parse(result);
                                                    var data = result.sal_options;
                                                    if (result.code == 200) {
                                                        $('#sal_options').html(data);
                                                    } else {
                                                        $('#sal_options').html("");
                                                    }
                                                },
                                            });
                                        }
                                        function get_ded_type_list()
                                        {
                                            var firm_id = document.getElementById('firm_id').value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("SalaryInfoController/get_ded_type_list") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {firm_id: firm_id},
                                                success: function (result) {
//                                                                       result = JSON.parse(result);
                                                    var data = result.ded_options;
                                                    if (result.code == 200) {
                                                        $('#ded_options').html(data);
                                                    } else {
                                                        $('#ded_options').html("");
                                                    }
                                                },
                                            });
                                        }


                                        //end of done by bhava
                                        $('#exampleModal').on('show.bs.modal', function (e) {

                                            var empid = $(e.relatedTarget).data('emp_id');
                                            var emp_id = document.getElementById('emp_id').value = empid;
                                            var empidx = $(e.relatedTarget).data('emp_idx');
                                            var emp_id = document.getElementById('emp_idx').value = empidx;
                                            var firmidx = $(e.relatedTarget).data('firm_idx');
                                            var firm_id = document.getElementById('firm_idx').value = firmidx;
                                            var firmid = $(e.relatedTarget).data('firm_id_a');
                                            var firm_id = document.getElementById('firm_id_a').value = firmid;
                                            var empid1 = $(e.relatedTarget).data('emp_id1');
                                            var emp_id = document.getElementById('emp_id1').value = empid1;
                                            var firmid1 = $(e.relatedTarget).data('firm_id_a1');
                                            var firm_id = document.getElementById('firm_id_a1').value = firmid1;
                                            var empid2 = $(e.relatedTarget).data('emp_id2');
                                            var emp_id = document.getElementById('emp_id2').value = empid2;
                                            var firmid2 = $(e.relatedTarget).data('firm_id_a2');
                                            var firm_id = document.getElementById('firm_id_a2').value = firmid2;
                                            var empid3 = $(e.relatedTarget).data('emp_id3');
                                            var emp_id = document.getElementById('emp_id3').value = empid3;
                                            var firmid3 = $(e.relatedTarget).data('firm_id_a3');
                                            var firm_id = document.getElementById('firm_id_a3').value = firmid3;
                                        });
                                        $('#excel_form_upload').on('submit', function (event) {
                                            event.preventDefault();
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("upload_employee") ?>",
                                                data: new FormData(this),
                                                contentType: false,
                                                cache: false,
                                                processData: false,
                                                success: function (result) {
                                                    var json = JSON.parse(result)

                                                    if (json["data"] !== "") {
                                                        $('#employee_excel_body').empty();
                                                        $('#employee_excel_body').append(json['data']);
                                                        $('#employee_excel_table').DataTable();
                                                    }
                                                    if (json["data"] === "") {
                                                        alert(json["error"]);
                                                    }
                                                    // console.log(result["data"]);
                                                },
                                                error: function (result) {
                                                    //console.log(result);

                                                }
                                            });
                                        });
                                        function change_emp_status(user_id, status) {

                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("/Employee/change_activity_status") ?>",
                                                dataType: "json",
                                                data: {user_id: user_id, status: status},
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        if (result.msg === 'A') {
                                                            alert('Employee de-activated successfully.');
                                                        } else {
                                                            alert('Employee activated successfully.');
                                                        }
                                                        // return;
                                                        location.reload();
                                                    } else if (result.status === false) {
                                                        alert('Something went wrong.')
                                                    } else {
                                                        $('#message').html(result.error);
                                                    }
                                                },
                                                error: function (result) {
                                                    console.log(result);
                                                    if (result.status === 500) {
                                                        alert('Internal error: ' + result.responseText);
                                                    } else {
                                                        alert('Unexpected error.');
                                                    }
                                                }
                                            });
                                        }

                                        function get_sorted_data() {

                                            var user_type = document.getElementById('user_type').value;

                                            if (user_type == 5)
                                            {
                                            } else {
                                                var firm_id_fetch = document.getElementById('ddl_firm_name_fetch').value;
                                                window.location.href = "<?= base_url("/Employee/view_employee_hr/") ?>" + firm_id_fetch;
                                            }
                                        }

                                        $(document).ready(function () {
                                            $.ajax({
                                                url: "<?= base_url("/Human_resource/get_ddl_firm_name") ?>",
                                                dataType: "json",
                                                success: function (result) {
                                                    if (result['message'] === 'success') {
                                                        var data = result.frim_data;
                                                        $('#boss_id').val(data[0]['reporting_to']);
                                                        var ele3 = document.getElementById('ddl_firm_name_fetch');
                                                        for (i = 0; i < data.length; i++)
                                                        {
                                                            // POPULATE SELECT ELEMENT WITH JSON.
                                                            ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                        }
                                                    }
                                                }
                                            });
                                        });

                                        function edit_loan(Loan_id) {
                                            alert(Loan_id);
                                            $("#save").hide();
                                            $("#update").show();

                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_loan_details") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {Loan_id: Loan_id},
                                                success: function (result) {
                                                    var data = result.result;
                                                    if (result.code == 200) {
                                                        $("#loan_detail").val(data['loan_detail']);
                                                        $("#amount").val(data['amount']);
                                                        $("#EMI_amt").val(data['EMI_amt']);
                                                        $("#from_month").val(data['from_month']);
                                                        $("#Fyear").val(data['Fyear']);
                                                        $("#total_month").val(data['total_month']);
                                                        $("#sanction_date").val(data['sanction_date']);
                                                        $("#Loan_id").val(Loan_id);
                                                    } else {

                                                    }
                                                },
                                            });
                                        }

                                        function update_loan() {
                                            $.ajax({
                                                url: '<?= base_url("Employee/Update_Staffloan") ?>',
                                                type: "POST",
                                                dataType: "JSON",
                                                data: $("#staff_loan").serialize(),
                                                success: function (success) {
                                                    success = JSON.parse(success);
                                                    if (success.message === "success") {
                                                        alert(success.body);
                                                        location.reload();
                                                        get_data_staffloanhistory();
                                                    } else {
                                                        alert(success.body);
                                                        //  toastr.error(success.body); //toster.error
                                                    }
                                                },
                                                error: function (error) {
//                                                    toastr.error(success.body);
                                                    console.log(error);
                                                    errorNotify("Something went to wrong.");
                                                }
                                            });
                                        }
                                        function downloadExcel() {
                                            // Specify the columns to exclude (0-based index)
                                            // Specify the columns to exclude (0-based index)
                                            var excludeColumns = [9,10]; // Example: Exclude the 3rd (Office) and 5th (Start date) columns

                                            // Get the DataTable instance for the table with ID 'sample_11'
                                            var table = $('#sample_11').DataTable();

                                            // Prepare the rows data, starting with the headers
                                            var rows = [];

                                            // Get the table headers
                                            var headers = [];
                                            $('#sample_11 thead th').each(function(index) {
                                                if (!excludeColumns.includes(index)) {
                                                    headers.push($(this).text());
                                                }
                                            });
                                            rows.push(headers); // Add headers as the first row

                                            // Get all data from the DataTable
                                            var data = table.rows().data().toArray();

                                            // Add table rows, excluding specified columns and sanitizing HTML content
                                            data.forEach(function(rowData) {
                                                var row = [];
                                                rowData.forEach(function(cellData, index) {
                                                    if (!excludeColumns.includes(index)) {
                                                        // Sanitize or strip HTML content
                                                        var cellText = typeof cellData === 'string' ? $('<div>').html(cellData).text() : cellData;
                                                        row.push(cellText);
                                                    }
                                                });
                                                rows.push(row);
                                            });

                                            // Create a worksheet from the filtered rows
                                            var worksheet = XLSX.utils.aoa_to_sheet(rows);

                                            // Create a workbook and append the worksheet
                                            var workbook = XLSX.utils.book_new();
                                            XLSX.utils.book_append_sheet(workbook, worksheet, 'Filtered Data');

                                            // Download the Excel file
                                            XLSX.writeFile(workbook, 'filtered_data.xlsx');

                                        }
                                        /*  function downloadExcel() {
                                            // Get the table element
                                            var table = document.getElementById('sample_11');

                                            // Convert table to a worksheet
                                            var worksheet = XLSX.utils.table_to_sheet(table);

                                            // Create a new workbook
                                            var workbook = XLSX.utils.book_new();

                                            // Append the worksheet to the workbook
                                            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

                                            // Generate Excel file and trigger download
                                            XLSX.writeFile(workbook, 'table_data.xlsx');
                                        }*/

                                        
    function downloadEmployeeExcel(){
        window.location.href = "<?= base_url("generate_employee_details") ?>";
    }
</script>
