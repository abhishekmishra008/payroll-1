<?php
// $this->load->view('admin/header');
$this->load->view('admin/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');

$user_id1 = $this->session->userdata('login_session');
$user_id = $user_id1['user_id'];
if ($user_id == '') {
    redirect(base_url() . 'login'); //take them back to signin
}
$head_text = 'Add Client Admin';
?>

<style>


</style>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    Admin
                    <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                </li>
            </ul>
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'HqDashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>HqDashboard">Home</a>
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>

                </ul>
            </div>
        </div>
        <h1 class="page-title">
        </h1>
        <!-- <div class="page-fixed-main-content"> -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <!-- BEGIN DASHBOARD STATS 1-->
        <!-- <div class="clearfix"></div> -->
        <!-- END DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Add Office</span>
                        </div>

                    </div>
                    <!--                    <div class="form-row">

                                            <div class="col-md-4">
                                                <label>Select Branch Name
                                                </label>
                                                <select class="form-control m-select2 m-select2-general" id="ddl_firm_name_fetch" name="ddl_firm_name_fetch" >
                                                    <option value="">Select branch</option>

                                                </select>
                                                <span class="required" id="ddl_firm_name_fetch_error"></span>
                                            </div>

                                        </div>-->

                    <div class="portlet-body">

                        <!-- BEGIN FORM-->
                        <div class="loading" id="loader" style="display:none;"></div>
                        <form method="POST"  id="frm_client_firm" name="frm_client_firm" class="form-horizontal" novalidate="novalidate">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>"/>
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>


                                <div class="row">
                                    <div class="form-group">

                                        <div class="col-md-4">
                                            <label>Select HR Admin
                                            </label>
                                            <select class="form-control m-select2 m-select2-general"  onchange="remove_error('ddl_firm_name_fetch')" id="ddl_firm_name_fetch" name="ddl_firm_name_fetch" >
                                                <option value="">Select HR Admin</option>

                                            </select>
                                            <span class="required" id="ddl_firm_name_fetch_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Office Name    </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-home"></i>
                                                </span>
                                                <input type="text" class="form-control" id="firm_name" name="firm_name"  onkeyup="remove_error('firm_name')" placeholder="Office Name" aria-required="true" aria-describedby="input_group-error">
                                            </div>
                                            <span class="required" id="firm_name_error"></span>

                                        </div>
                                        <div class="col-md-4">
                                            <label>Office Email Id</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                                <input type="email" id="firm_email_id" onkeyup="remove_error('firm_email_id')" placeholder="Office Email id"   name="firm_email_id" data-required="1" class="form-control">
                                            </div>
                                            <span class="required" id="firm_email_id_error"></span>

                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label> Office Address</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i>
                                                </span>
                                                <textarea  rows="3" class="form-control" min="5" onkeyup="remove_error('firm_address')" id="firm_address" name="firm_address"  aria-required="true" placeholder="Office Address"></textarea>
                                            </div>
                                            <span class="required" id="firm_address_error" ></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Office Head Name
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-user"></i> </span>
                                                <input type="hidden" id="reporting_to" name="reporting_to" value="" class="form-control m-input" >
                                                <input type="hidden" id="firm_type" name="firm_type" value="associate" class="form-control m-input" >
                                                <input type="text" class="form-control m-input" id="boss_name"  name="boss_name" onkeyup="remove_error('boss_name')" aria-describedby="emailHelp" placeholder="Office Head Name">
                                            </div>
                                            <span class="required" id="boss_name_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Office Head Mobile Number
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-phone"></i> </span>
                                                <input type="number" min="0" id="boss_mobile_no" name="boss_mobile_no" onkeyup="remove_error('boss_mobile_no')"
                                                       onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"  		data-required="1" class="form-control" placeholder="Office Head Mobile Number">
                                            </div>
                                            <span class="required" id="boss_mobile_no_error"></span>
                                        </div>

                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label>Office Head Email Id

                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-flag"></i>
                                                </span>
                                                <input type="email" id="boss_email_id" name="boss_email_id" onkeyup="remove_error('boss_email_id')"  data-required="1" class="form-control" placeholder="Office Head Email Id">
                                            </div>
                                            <span class="required" id="boss_email_id_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Employee Strength  </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"  min="0" id="firm_no_of_employee" name="firm_no_of_employee" onkeyup="remove_error('firm_no_of_employee')" data-required="1" class="form-control" placeholder="No of Employee In Office">
                                            </div>
                                            <span class="required" id="firm_no_of_employee_error"></span>
                                        </div>
                                        <!--                                        <div class="col-md-4">
                                                                                    <label> No Of Customers</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">
                                                                                            <i class="fa fa-users"></i>
                                                                                        </span>
                                                                                        <input type="number" id="firm_no_of_customers" min="0" name="firm_no_of_customers"  onkeyup="remove_error('firm_no_of_customers')" data-required="1" class="form-control" placeholder="Office No Of Customers">
                                                                                    </div>
                                                                                    <span class="required" id="firm_no_of_customers_error"></span>
                                                                                </div>-->
                                        <div class="col-md-4">
                                            <label>Country

                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-flag"></i>
                                                </span>
                                                <input type="text" id="country" name="country" onkeyup="remove_error('country')"  data-required="1" class="form-control" placeholder="Enter Country Name">
                                            </div>
                                            <span class="required" id="country_error"></span>
                                        </div>


                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label>State

                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i>
                                                </span>
                                                <input type="text" name="state"  id="state"  onkeyup="remove_error('state')"data-required="1" class="form-control" placeholder="Enter State Name">
                                            </div>

                                            <span class="required" id="state_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>City  </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-home"></i>
                                                </span>
                                                <input type="text" class="form-control" name="city"  id="city" onkeyup="remove_error('city')" placeholder="Enter City Name" aria-required="true" aria-describedby="input_group-error">
                                                <input type="hidden" value="" id="testing_firm" name="testing_firm">
                                            </div>
                                            <span class="required" id="city_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label>  Activity Status
                                            </label>
                                            <div class="mt-checkbox-inline">
                                                <label class="mt-radio">
                                                    <input type="radio" id="firm_activity_status" name="firm_activity_status" checked="" value="A">Active
                                                    <span></span>
                                                </label>
                                                <label class="mt-radio">
                                                    <input type="radio" id="firm_activity_status" name="firm_activity_status" value="D"> De-activate
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label> Branch Location
                                            </label> 
                                        </div>
                                        <div class="col-md-4">
                                            <label>Longitude</label>
                                            <div class="input-group">

                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i>
                                                </span>
                                                <input type="text" id="longitude_value" name="longitude_value" class="form-control" onkeyup="remove_error('longitude_value')" placeholder="Enter Longitdue Value"/>

                                            </div><span class="required" id="longitude_value_error" style="color:red"></span>

                                        </div>

                                        <div class="col-md-4">
                                            <label>Lattitude</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i>
                                                </span>
                                                <input type="text" id="lattitude_value" class="form-control" name="lattitude_value" onkeyup="remove_error('lattitude_value')" placeholder="Enter Lattitude value"/> 

                                            </div><span class="required" id="lattitude_value_error" style="color:red"></span> 

                                        </div>



                                    </div>

                                </div>


                                <div id="yes">
                                    <div class="row">
                                        <div class="portlet box grey-salsa">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-user"></i>Employee Information</div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Designation</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-user-secret"></i>
                                                            </span>
                                                            <input type="text" min="0" id="desig" name="desig" disabled="" class="form-control" value="Client Admin"> </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Joining Date</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            <input type="date" id="joining_date"  name="joining_date" oninput="remove_error('joining_date')" class="form-control" Placeholser="Joining date of Client Admin"> </div>
                                                        <span class="required" id="joining_date_error" style="color:red"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Yearly Leaves Permitted</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-sign-out"></i>
                                                            </span>
                                                            <input type="number" min="0" id="yearly_leave" name="yearly_leave" onkeyup="remove_error('yearly_leave')" class="form-control" placeholder="Yearly Leaves"> </div>
                                                        <span class="required" id="yearly_leave_error" style="color:red"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Monthly Leaves Permitted</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-sign-out"></i>
                                                            </span>
                                                            <input type="number" min="0" id="monthly_leaves" name="monthly_leaves" onkeyup="remove_error('monthly_leaves')" class="form-control" placeholder="Monthly Leaves"> </div>
                                                        <span class="required" id="monthly_leaves_error" style="color:red"></span>
                                                    </div>


                                                </div>
                                                <div class="row"> <br>
                                                    <div class="col-md-3">
                                                        <label>Yearly Leaves Type</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                            <select name="leave_type_year" id="leave_type_year" class="form-control m-select2 m-select2-general" onchange="remove_error('leave_type_year')">
                                                                <option value="">Select Option</option>
                                                                <option value="1">From Date of joining</option>
                                                                <option value="2">Financial Year</option>
                                                                <option value="3">Calendar Year</option>
                                                            </select></div>
                                                        <span class="required" id="leave_type_year_error" style="color:red"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Apply Leave From </label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                            <select name="leave_apply_permission" id="leave_apply_permission" class="form-control m-select2 m-select2-general"  oninput="remove_error('leave_apply_permission')"onchange="check_prob_train_date();">
                                                                <option value="">Select Option</option>
                                                                <option value="1">From Date of joining</option>
                                                                <option value="2">After Probation Period</option>
                                                                <option value="3">After Training Period</option>
                                                                <option value="4">After Confirmation</option>
                                                            </select></div>
                                                        <span class="required" id="leave_apply_permission_error" style="color:red"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Leave Carry Forward</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                            <select name="leave_cf" id="leave_cf" class="form-control m-select2 m-select2-general" onchange="remove_error('leave_cf')">
                                                                <option value="">Select Option</option>
                                                                <option value="1">Monthly</option>
                                                                <option value="2">Yearly</option>
                                                            </select></div>
                                                        <span class="required" id="leave_cf_error" style="color:red"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Holiday Consider In Leave</label>
                                                        <div class="mt-checkbox-inline">
                                                            <label class="m-radio">
                                                                <input type="radio" id="holiday_status" name="holiday_status" value="1" > Yes
                                                                <span></span>
                                                            </label>
                                                            <label class="m-radio">
                                                                <input type="radio" id="holiday_status" name="holiday_status" value="0" checked=""> No
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <div class="icheck-inline">
                                                                <label>Probation Period</label>
                                                                <div id="prob_period" name="prob_period">
                                                                    <label>
                                                                        <input type="radio" id="probation_period" name="probation_period" value="0" checked  data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_yes')"> yes </label>
                                                                    <label>
                                                                        <input type="radio" id="probation_period" name="probation_period" value="1" data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_no')"> no </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="prob_yes" style="display: block;">
                                                        <div class="col-md-3">
                                                            <label>From</label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="Date" name="prob_date_first" onchange="remove_error('prob_date_first')" id="prob_date_first"ata-required="1" class="form-control" placeholder="Completion Date">
                                                            </div>
                                                            <span class="required" id="prob_date_first_error" style="color:red"></span><br>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>To</label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="Date" name="prob_date_second" onchange="remove_error('prob_date_second')" id="prob_date_second"ata-required="1" class="form-control" placeholder="Completion Date">
                                                            </div>
                                                            <span class="required" id="prob_date_second_error" style="color:red"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <div class="icheck-inline">
                                                                <label>Training Period</label>
                                                                <div id="train_period" name="train_period">
                                                                    <label>
                                                                        <input type="radio" id="training_period" name="training_period" value="0" checked  data-checkbox="icheckbox_flat-grey" onclick="training_change('train_yes')"> yes </label>
                                                                    <label>
                                                                        <input type="radio" id="training_period" name="training_period" value="1" data-checkbox="icheckbox_flat-grey" onclick="training_change('train_no')"> no </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><div id="train_yes" style="display: block;">
                                                        <div class="col-md-3">
                                                            <label>From</label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="Date" name="training_period_first" onchange="remove_error('training_period_first')" id="training_period_first"ata-required="1" class="form-control" placeholder="Completion Date">
                                                            </div>
                                                            <span class="required" id="training_period_first_error" style="color:red"></span><br>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>To</label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="Date" name="training_period_second" onchange="remove_error('training_period_second')" id="training_period_second"ata-required="1" class="form-control" placeholder="Completion Date">
                                                            </div>
                                                            <span class="required" id="training_period_second_error" style="color:red"></span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="portlet box grey-salsa">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-sign-out"></i>Leaves Types</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-left:0px !important;  margin-right: 0px !important;" >

                                        <div class="col-md-1"></div>
                                        <div class="col-md-4">
                                            <span class="required" id="numofdays7_error" ></span>
                                            <div class="m-radio-inline">
                                                <label for="" style="color: #002a80">
                                                    These Leave Type Are Same For All Designations :
                                                </label>
                                                <label class="mt-radio m-radio--solid">
                                                    <input type="radio" name="leave_type_permission" id="leave_type_permission" checked=""  value="1">
                                                    Yes
                                                    <span></span>
                                                </label>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Accrual Month</label>
                                            <select class="form-control m-select2 m-select2-general" id="accural_month" name="accural_month" onchange="remove_error('accural_month')">
                                                <option value="">Select Month</option>
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                            <span class="required" id="accural_month_error" style="color:red"></span>

                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2"><span style="font-weight:bold;">Types </span></div>
                                        <div class="col-md-2"><span style="font-weight:bold;">No. of days </span></div>
                                        <div class="col-md-2"><span style="font-weight:bold;">Carry Forward </span></div>
<!--                                        <div class="col-md-2"><span style="font-weight:bold;">Leave Request Before(Days) </span></div>
                                        <div class="col-md-2"><span style="font-weight:bold;">Leave Approve</span></div>-->
                                        <!--<div class="col-md-2"><span style="font-weight:bold;">Days past/next</span></div>-->
                                    </div><br>
                                    <?php
                                    for ($k = 1; $k <= 7; $k++) {
                                        if ($k == 1) {
                                            ?>
                                            <div class="row">
                                                <DIV class="col-md-1"></div>
                                                <DIV class="col-md-1"><span style="font-weigth:bold;">Type <?php echo $k ?>:</span></DIV>
                                                <DIV class="col-md-2"><input type="text" id="leave_type" name="leave_type" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" disabled value="PL" placeholder="First Leave Type">
                                                    <input type="hidden" id="leave_type<?php echo $k ?>" name="leave_type<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control"  value="PL" placeholder="First Leave Type">
                                                </DIV>
                                                <DIV class="col-md-2"><input type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" min="1" id="numofdays<?php echo $k ?>" name="numofdays<?php echo $k ?>" onkeyup="remove_error('numofdays<?php echo $k ?>')" class="form-control" placeholder="No Of Days"><span class="required" id="numofdays<?php echo $k ?>_error" style="color:red"></span></DIV>
                                                <DIV class="col-md-1"><label class="mtradio"><input type="radio"  value="1" id="CF<?php echo $k ?>" name="CF<?php echo $k ?>" checked="checked" onchange="remove_error('CF<?php echo $k ?>')">Yes </label>&nbsp; <label class="mtradio "><input type="radio"  value="0" id="CF<?php echo $k ?>" name="CF<?php echo $k ?>" onchange="remove_error('CF<?php echo $k ?>')">No</label><span class="required" id="CF<?php echo $k ?>_error" style="color:red"></span></DIV>

                                                                                <!--                                            <DIV class="col-md-2"><input type="number" min="0" id="request_before<?php echo $k ?>" name="request_before<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="Request Before"></DIV>
                                                                                          <DIV class="col-md-2"><input type="number" min="1" id="approve_before<?php echo $k ?>" name="approve_before<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="Approve Before"></DIV>-->
                                                                                          <!--<DIV class="col-md-2"><input type="radio" name="past_next<?php echo $k ?>" value="Past"> Past<br><input type="radio" name="past_next<?php echo $k ?>" value="Next"> Next<br></DIV>-->
                                                <div class="col-md-1" id="leave1" name="leave1"><span class="required" id="leave_type<?php echo $k ?>_error"></span></div>
                                            </DIV><br>
                                        <?php } elseif ($k == 2) { ?>
                                            <div class="row">
                                                <DIV class="col-md-1"></div>
                                                <DIV class="col-md-1"><span style="font-weigth:bold;">Type <?php echo $k ?>:</span></DIV>
                                                <DIV class="col-md-2"><input type="text" id="leave_type" disabled value="CL"name="leave_type" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="First Leave Type" required="">
                                                    <input type="hidden" id="leave_type<?php echo $k ?>" name="leave_type<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control"  value="CL" placeholder="First Leave Type">
                                                </DIV>
                                                <DIV class="col-md-2"><input type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" min="1" id="numofdays<?php echo $k ?>" name="numofdays<?php echo $k ?>" onkeyup="remove_error('numofdays<?php echo $k ?>')" class="form-control" placeholder="No Of Days"><span class="required" id="numofdays<?php echo $k ?>_error" style="color:red"></span></DIV>
                                                <DIV class="col-md-1"><label class="mtradio"><input type="radio"  value="1" id="CF<?php echo $k ?>" name="CF<?php echo $k ?>" onchange="remove_error('CF<?php echo $k ?>')">Yes </label>&nbsp; <label class="mtradio "><input type="radio"  value="0" id="CF<?php echo $k ?>" name="CF<?php echo $k ?>" onchange="remove_error('CF<?php echo $k ?>')">No</label><span class="required" id="CF<?php echo $k ?>_error" style="color:red"></span></DIV>

                                                                                <!--                                            <DIV class="col-md-2"><input type="number" min="0" id="request_before<?php echo $k ?>" name="request_before<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="Request Before"></DIV>
                                                                                          <DIV class="col-md-2"><input type="number" min="1" id="approve_before<?php echo $k ?>" name="approve_before<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="Approve Before"></DIV>-->
                                                                                          <!--<DIV class="col-md-2"><input type="radio" name="past_next<?php echo $k ?>" value="Past"> Past<br><input type="radio" name="past_next<?php echo $k ?>" value="Next"> Next<br></DIV>-->
                                                <div class="col-md-1" id="leave1" name="leave1"><span class="required" id="leave_type<?php echo $k ?>_error"></span></div>
                                            </DIV><br>
                                        <?php } elseif ($k == 3) { ?>
                                            <div class="row">
                                                <DIV class="col-md-1"></div>
                                                <DIV class="col-md-1"><span style="font-weigth:bold;">Type <?php echo $k ?>:</span></DIV>
                                                <DIV class="col-md-2"><input type="text" id="leave_type" disabled value="SL" name="leave_type" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="First Leave Type" required="">
                                                    <input type="hidden" id="leave_type<?php echo $k ?>" name="leave_type<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control"  value="SL" placeholder="First Leave Type" required="">
                                                </DIV>
                                                <DIV class="col-md-2"><input type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" min="1" id="numofdays<?php echo $k ?>" name="numofdays<?php echo $k ?>" onkeyup="remove_error('numofdays<?php echo $k ?>')" class="form-control" placeholder="No Of Days"><span class="required" id="numofdays<?php echo $k ?>_error" style="color:red"></span></DIV>
                                                <DIV class="col-md-1"><label class="mtradio"><input type="radio"  value="1" id="CF<?php echo $k ?>" name="CF<?php echo $k ?>" onchange="remove_error('CF<?php echo $k ?>')" >Yes </label>&nbsp; <label class="mtradio "><input type="radio"  value="0" id="CF<?php echo $k ?>" name="CF<?php echo $k ?>" onchange="remove_error('CF<?php echo $k ?>')">No</label><span class="required" id="CF<?php echo $k ?>_error" style="color:red"></span></DIV>

                                                                                <!--                                            <DIV class="col-md-2"><input type="number" min="0" id="request_before<?php echo $k ?>" name="request_before<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="Request Before"></DIV>
                                                                                          <DIV class="col-md-2"><input type="number" min="1" id="approve_before<?php echo $k ?>" name="approve_before<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="Approve Before"></DIV>-->
                                                                                          <!--<DIV class="col-md-2"><input type="radio" name="past_next<?php echo $k ?>" value="Past"> Past<br><input type="radio" name="past_next<?php echo $k ?>" value="Next"> Next<br></DIV>-->
                                                <div class="col-md-1" id="leave1" name="leave1"><span class="required" id="leave_type<?php echo $k ?>_error"></span></div>
                                            </DIV><br>
                                        <?php } else { ?>
                                            <div class="row">
                                                <DIV class="col-md-1"></div>
                                                <DIV class="col-md-1"><span style="font-weigth:bold;">Type <?php echo $k ?>:</span></DIV>
                                                <DIV class="col-md-2"><input type="text" id="leave_type<?php echo $k ?>" name="leave_type<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="First Leave Type"></DIV>
                                                <DIV class="col-md-2"><input type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" min="1" id="numofdays<?php echo $k ?>" name="numofdays<?php echo $k ?>" onkeyup="remove_error('numofdays<?php echo $k ?>')" class="form-control" placeholder="No Of Days"><span class="required" id="numofdays<?php echo $k ?>_error" style="color:red"></span></DIV>
                                                <DIV class="col-md-1"><label class="mtradio"><input type="radio"  value="1" id="CF<?php echo $k ?>" name="CF<?php echo $k ?>" onchange="remove_error('CF<?php echo $k ?>')">Yes </label>&nbsp; <label class="mtradio "><input type="radio"  value="0" id="CF<?php echo $k ?>" name="CF<?php echo $k ?>" onchange="remove_error('CF<?php echo $k ?>')">No</label><span class="required" id="CF<?php echo $k ?>_error" style="color:red"></span></DIV>

                                                                                <!--                                            <DIV class="col-md-2"><input type="number" min="0" id="request_before<?php echo $k ?>" name="request_before<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="Request Before"></DIV>
                                                                                          <DIV class="col-md-2"><input type="number" min="1" id="approve_before<?php echo $k ?>" name="approve_before<?php echo $k ?>" onkeyup="remove_error('leave<?php echo $k ?>')" class="form-control" placeholder="Approve Before"></DIV>-->
                                                                                          <!--<DIV class="col-md-2"><input type="radio" name="past_next<?php echo $k ?>" value="Past"> Past<br><input type="radio" name="past_next<?php echo $k ?>" value="Next"> Next<br></DIV>-->
                                                <div class="col-md-1" id="leave1" name="leave1"><span class="required" id="leave_type<?php echo $k ?>_error"></span></div>
                                            </DIV><br>
                                        <?php }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <a href="<?= base_url() ?>hq_show_firm_hr"><button type="button" class="btn grey-salsa btn-outline"  style="float:right; margin: 2px;">Cancel</button></a>
                                        <button  type="button" id="btn_add_client_firm" name="btn_add_client_firm" class="btn green"  style="float:right;  margin: 2px;">Create Office</button>

                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END FORM-->
                    </div>


                </div>
<?php $this->load->view('admin/footer'); ?>
                <!-- END VALIDATION STATES-->
            </div>
        </div>

        <!-- END PAGE BASE CONTENT -->
    </div>

    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script>

                                            $(document).ready(function () {

                                                $.ajax({
                                                    url: "<?= base_url("Hq_firm_form/get_firms") ?>",
                                                    dataType: "json",
                                                    success: function (result) {
                                                        //console.log(result);
                                                        if (result['message'] === 'success') {
                                                            var data = result['result'];

                                                            var ele3 = document.getElementById('ddl_firm_name_fetch');
                                                            for (i = 0; i < data.length; i++)
                                                            {

                                                                ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                            }
                                                        }
                                                    }
                                                });
                                            });


                                            function get_day_type(id) {
                                                remove_error('ddl_day_type' + id);
                                                var ddl_day_type = document.getElementById('ddl_day_type' + id).value;
                                                if (ddl_day_type === 'all') {
                                                    $("#div_day" + id).css("display", "none");
                                                } else {
                                                    $("#div_day" + id).css("display", "block");
                                                }
                                            }

                                            $("#yearly_leave").keyup(function () {
                                                $("#monthly_leaves").val($("#yearly_leave").val() / 12);
                                            });


                                            //    function view_file1(id) {
                                            //        var x = document.getElementById("yes");
                                            //
                                            //        if (id === 'yes') {
                                            //
                                            //            x.style.display = "block";
                                            //
                                            //        } else if (id === 'no')
                                            //        {
                                            //            x.style.display = "none";
                                            //        }
                                            //
                                            //    }
                                            function probation_change(id)
                                            {
                                                var x = document.getElementById("prob_yes");
                                                if (id === 'prob_yes') {

                                                    x.style.display = "block";

                                                } else if (id === 'prob_no')
                                                {
                                                    x.style.display = "none";
                                                }
                                            }
                                            function training_change(id)
                                            {
                                                var x = document.getElementById("train_yes");
                                                if (id === 'train_yes') {

                                                    x.style.display = "block";

                                                } else if (id === 'train_no')
                                                {
                                                    x.style.display = "none";
                                                }
                                            }


                                            $("#btn_add_client_firm").click(function () {
                                                var $this = $(this);
                                                $this.button('loading');
                                                setTimeout(function () {
                                                    $this.button('reset');
                                                }, 2000);
//                                                document.getElementById('loader').style.display = "block";
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("Hq_firm_form/insert_client_firm") ?>",
                                                    dataType: "json",
                                                    data: $("#frm_client_firm").serialize(),
                                                    success: function (result) {
                                                        // console.log(result);return;
                                                        if (result.success === '100') {
                                                            alert('Office created successfully.');
                                                            window.location.href = "<?= base_url("hq_show_firm_hr") ?>";
                                                        } else if (result.status === true) {
                                                            document.getElementById('loader').style.display = "none";
                                                            alert('Office created successfully');
                                                            window.location.href = "<?= base_url("hq_show_firm_hr") ?>";

                                                        } else if (result.status === false) {
                                                            document.getElementById('loader').style.display = "none";
                                                            alert('Something went wrong.');
                                                        } else {
                                                            document.getElementById('loader').style.display = "none";
                                                            $('#' + result.id + '_error').html(result.error);
                                                            $(window).scrollTop($('#' + result.id).offset().top - 100, 'slow');

                                                        }
                                                    },
                                                    error: function (result) {
                                                        console.log(result);
                                                        if (result.status === 500) {
                                                            document.getElementById('loader').style.display = "none";
                                                            alert('Internal error: ' + result.responseText);
                                                        } else {
                                                            document.getElementById('loader').style.display = "none";
                                                            alert('Unexpected error.2');
                                                        }
                                                    }
                                                });
                                            });

                                            $(document).ready(function () {

                                                var user_id = document.getElementById('user_id').value;
                                                //alert(user_id);
                                                $.ajax({
                                                    type: "post",
                                                    url: "<?= base_url("/hq_firm_form/get_reporting_to") ?>",
                                                    dataType: "json",
                                                    data: {user_id: user_id},
                                                    success: function (result) {
                                                        if (result['message'] === 'success') {
                                                            document.getElementById('reporting_to').value = result['reporting_to'];
                                                        }
                                                    }
                                                });
                                            });
                                            function  remove_error(id) {
                                                $('#' + id + '_error').html("");
                                            }
                                            function check_prob_train_date()
                                            {
                                                var value1 = $("#leave_apply_permission option:selected").val();
                                                if (value1 == 2)
                                                {
                                                    document.getElementById("prob_period").style.display = "none";
                                                    document.getElementById("train_period").style.display = "block";
                                                } else if (value1 == 3) {
                                                    document.getElementById("train_period").style.display = "none";
                                                    document.getElementById("prob_period").style.display = "block";
                                                } else {
                                                    document.getElementById("train_period").style.display = "block";
                                                    document.getElementById("prob_period").style.display = "block";
                                                }

                                            }
    </script>