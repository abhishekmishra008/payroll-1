<?php
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
    .required{
        color:red !important;
    }
</style>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>Admin
                    <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                </li>
            </ul>
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'show_firm') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>show_firm">Home</a>
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

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Edit Office</span>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <img src="<?php echo base_url() . "assets/"; ?>global/img/input-spinner.gif" id="gif" style="display: block; margin: 0 auto; width:50px; visibility: hidden;">
                        <form method="POST"  id="frm_edit_form" name="frm_edit_form" class="form-horizontal" novalidate="novalidate">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>"/>
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-4">

                                            <input type="hidden" id="firm_id" name="firm_id" value="<?php echo $result_firm_data->firm_id; ?>"
                                                   <label>Office Name    </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-home"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $result_firm_data->firm_name; ?>" id="firm_name" name="firm_name"  onkeyup="remove_error('firm_name')" placeholder="Office Name" aria-required="true" aria-describedby="input_group-error">
                                            </div>
                                            <span class="required" id="firm_name_error" style="color:red"></span>

                                        </div>
                                        <div class="col-md-4">
                                            <label>Office Email Id</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                                <input type="email" readonly="" id="firm_email_id" value="<?php echo $result_firm_data->firm_email_id; ?>" onkeyup="remove_error('firm_email_id')" placeholder="Office email id"   name="firm_email_id" data-required="1" class="form-control">
                                            </div>
                                            <span class="required" id="firm_email_id_error"></span>

                                        </div>
                                        <div class="col-md-4">
                                            <label> Office Address</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i>
                                                </span>
                                                <textarea  rows="3" class="form-control" value="" onkeyup="remove_error('firm_address')" id="firm_address" name="firm_address"  aria-required="true" placeholder="Office Address"><?php echo $result_firm_data->firm_address; ?></textarea>
                                            </div>
                                            <span class="required" id="firm_address_error" ></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">

                                        <div class="col-md-4">
                                            <label>Office Head Name
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-user"></i> </span>
                                                <input type="hidden" id="reporting_to" name="reporting_to" value="" class="form-control m-input" >
                                                <input type="hidden" id="firm_type" name="firm_type" value="associate" class="form-control m-input" >
                                                <input type="text" class="form-control m-input" value="<?php echo $result_firm_data->boss_name; ?>" id="boss_name"  name="boss_name" onkeyup="remove_error('boss_name')" aria-describedby="emailHelp" placeholder="Enter Office Head Name">
                                            </div>
                                            <span class="required" id="boss_name_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Office Head Mobile Number
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-phone"></i> </span>
                                                <input type="number" min="0" id="boss_mobile_no"onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"   value="<?php echo $result_firm_data->boss_mobile_no; ?>" name="boss_mobile_no" onkeyup="remove_error('boss_mobile_no')"data-required="1" class="form-control" placeholder="Office Head Mobile Number">
                                            </div>
                                            <span class="required" id="boss_mobile_no_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Office Head Email Id

                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-flag"></i>
                                                </span>
                                                <input type="email" id="boss_email_id"  value="<?php echo $result_firm_data->boss_email_id; ?>" name="boss_email_id" onkeyup="remove_error('boss_email_id')"  data-required="1" class="form-control" placeholder="Enter Boss Email Id">
                                            </div>
                                            <span class="required" id="boss_email_id_error"></span>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group">

                                        <div class="col-md-4">
                                            <label> Employe Strength </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="number"  onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" min="0" id="firm_no_of_employee" value="<?php echo $result_firm_data->firm_no_of_employee; ?>" name="firm_no_of_employee" onkeyup="remove_error('firm_no_of_employee')" data-required="1" class="form-control" placeholder="No of Employee In Firm">
                                            </div>
                                            <span class="required" id="firm_no_of_employee_error"></span>
                                        </div>
                                        <!--                                        <div class="col-md-4">
                                                                                    <label> No Of Customers</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">
                                                                                            <i class="fa fa-users"></i>
                                                                                        </span>
                                                                                        <input type="number" id="firm_no_of_customers" min="0"  value="<?php echo $result_firm_data->firm_no_of_customers; ?>" name="firm_no_of_customers"  onkeyup="remove_error('firm_no_of_customers')" data-required="1" class="form-control" placeholder="Firm No Of Customers">
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
                                                <input type="text" id="country" name="country" value="<?php echo $result_firm_data1->country; ?>" onkeyup="remove_error('country')"  data-required="1" class="form-control" placeholder="Enter Country Name">
                                            </div>
                                            <span class="required" id="country_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label>State

                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i>
                                                </span>
                                                <input type="text" name="state"  id="state" value="<?php echo $result_firm_data1->state; ?>"  onkeyup="remove_error('state')"data-required="1" class="form-control" placeholder="Enter state Name">
                                            </div>

                                            <span class="required" id="state_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">

                                        <div class="col-md-4">
                                            <label>City  </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-home"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $result_firm_data1->city; ?>" name="city"  id="city" onkeyup="remove_error('city')" placeholder="Enter City Name" aria-required="true" aria-describedby="input_group-error">
                                                <input type="hidden" value="" id="testing_firm" name="testing_firm">
                                            </div>
                                            <span class="required" id="city_error"></span>
                                        </div>

                                        <?php if ($result_location_data != '') { ?>

                                            <div class="col-md-4">
                                                <label>Longitude</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon">
                                                        <i class="fa fa-globe"></i>
                                                    </span>
                                                    <input type="text" id="longitude_value" name="longitude_value" class="form-control" value="<?php echo $result_location_data->logitude; ?>" onkeyup="remove_error('longitude_value')" placeholder="Enter Longitdue Value"/>

                                                </div><span class="required" id="longitude_value_error" style="color:red"></span>

                                            </div>

                                            <div class="col-md-4">
                                                <label>Latitude</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-globe"></i>
                                                    </span>
                                                    <input type="text" id="lattitude_value" value="<?php echo $result_location_data->lattitude; ?>"class="form-control" name="lattitude_value" onkeyup="remove_error('lattitude_value')" placeholder="Enter Lattitude value"/> 

                                                </div><span class="required" id="lattitude_value_error" style="color:red"></span> 

                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-4">
                                                <label>Longitude</label>
                                                <div class="input-group">

                                                    <span class="input-group-addon">
                                                        <i class="fa fa-globe"></i>
                                                    </span>
                                                    <input type="text" id="longitude_value" name="longitude_value" class="form-control" value="" onkeyup="remove_error('longitude_value')" placeholder="Enter Longitdue Value"/>

                                                </div><span class="required" id="longitude_value_error" style="color:red"></span>

                                            </div>

                                            <div class="col-md-4">
                                                <label>Latitude</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-globe"></i>
                                                    </span>
                                                    <input type="text" id="lattitude_value" value=""class="form-control" name="lattitude_value" onkeyup="remove_error('lattitude_value')" placeholder="Enter Lattitude value"/> 

                                                </div><span class="required" id="lattitude_value_error" style="color:red"></span> 

                                            </div><?php } ?>

                                    </div>
                                </div>


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
                                                        <?php
                                                        $originalDate = $result_firm_data1->date_of_joining;
                                                        $newcompletionDate = date("Y-m-d", strtotime($originalDate));
                                                        ?>
                                                        <input type="date" min="0" id="joining_date" oninput="remove_error('joining_date')" name="joining_date" value="<?php echo $newcompletionDate; ?>"  class="form-control" Placeholser="Joining date of Client Admin"> </div>
                                                    <span class="required" id="joining_date_error"></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Yearly Leaves</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-sign-out"></i>
                                                        </span>
                                                        <input type="number" min="0" id="yearly_leave" name="yearly_leave" value="<?php echo $result_desig_data->total_yearly_leaves; ?>" onkeyup="remove_error('yearly_leave')" class="form-control" placeholder="Yearly Leaves"> </div>
                                                    <span class="required" id="yearly_leave_error" style="color:red"></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Monthly Leaves</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-sign-out"></i>
                                                        </span>
                                                        <input type="number" min="0" id="monthly_leaves" name="monthly_leaves" value="<?php echo $result_desig_data->total_monthly_leaves; ?>" onkeyup="remove_error('monthly_leaves')" class="form-control" placeholder="Monthly Leaves"> </div>
                                                    <span class="required" id="monthly_leaves_error"></span>
                                                </div>


                                            </div>
                                            <div class="row"> <br>
                                                <div class="col-md-3">
                                                    <label>Yearly Leaves Type</label>

                                                    <?php
                                                    $yearly_type = $result_desig_data->year_type;
                                                    $request_leave_from = $result_desig_data->request_leave_from;
                                                    $carry_forward_period = $result_desig_data->carry_forward_period;
                                                    if ($yearly_type == 1) {
                                                        $option = 'From Date of joining';
                                                    } elseif ($yearly_type == 2) {
                                                        $option = 'Financial Year';
                                                    } else {
                                                        $option = 'Calendar Year';
                                                    }
                                                    if ($request_leave_from == 1) {
                                                        $option1 = 'From Date of joining';
                                                    } elseif ($request_leave_from == 2) {
                                                        $option1 = 'After Probation Period';
                                                    } elseif ($request_leave_from == 3) {
                                                        $option1 = 'After Training Period';
                                                    } else {
                                                        $option1 = 'After Confirmation';
                                                    }
                                                    if ($carry_forward_period == 1) {
                                                        $option2 = 'Monthly';
                                                    } else {
                                                        $option2 = 'Yearly';
                                                    }
                                                    ?>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                                        <select name="leave_type_year" id="leave_type_year" class="form-control m-select2 m-select2-general" onchange="remove_error('leave_type_year')">
                                                            <option value="<?php echo $yearly_type; ?>"><?php echo $option; ?></option>
                                                            <option value="1">From Date of joining</option>
                                                            <option value="2">Financial Year</option>
                                                            <option value="3">Calendar Year</option>
                                                        </select></div>
                                                    <span class="required" id="leave_type_year_error"></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Apply Leave From </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                                        <select name="leave_apply_permission" id="leave_apply_permission" oninput="remove_error('senior_authority')" class="form-control m-select2 m-select2-general" onchange="check_prob_train_date();">
                                                            <option value="<?php echo $request_leave_from; ?>"><?php echo $option1; ?></option>
                                                            <option value="1">From Date of joining</option>
                                                            <option value="2">After Probation Period</option>
                                                            <option value="3">After Training Period</option>
                                                            <option value="4">After Confirmation</option>
                                                        </select></div>
                                                    <span class="required" id="senior_authority_error"></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Leave Carry Forward</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                                        <select name="leave_cf" id="leave_cf" class="form-control m-select2 m-select2-general" onchange="remove_error('senior_authority')">
                                                            <option value="<?php echo $carry_forward_period; ?>"><?php echo $option2; ?></option>
                                                            <option value="1">Monthly</option>
                                                            <option value="2">Yearly</option>
                                                        </select></div>
                                                    <span class="required" id="senior_authority_error"></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Holiday Consider In Leave</label>
                                                    <div class="mt-checkbox-inline">
                                                        <?php if ($result_desig_data->holiday_consider_in_leave == '1') { ?>
                                                            <label class="m-radio">
                                                                <input type="radio" id="holiday_status" name="holiday_status" value="1" checked="" > Yes
                                                                <span></span>
                                                            </label>
                                                            <label class="m-radio">
                                                                <input type="radio" id="holiday_status" name="holiday_status" value="0" > No
                                                                <span></span>
                                                            </label>
                                                        <?php } else { ?>
                                                            <label class="m-radio">
                                                                <input type="radio" id="holiday_status" name="holiday_status" value="1" > Yes
                                                                <span></span>
                                                            </label>
                                                            <label class="m-radio">
                                                                <input type="radio" id="holiday_status" name="holiday_status" value="0" checked=""> No
                                                                <span></span>
                                                            </label>
                                                        <?php } ?>

                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <div class="icheck-inline">
                                                            <label>Probation Period</label>
                                                            <div id="prob_period" name="prob_period">
                                                                <?php if ($result_firm_data1->probation_period_start_date == '0000-00-00 00:00:00') { ?>
                                                                    <label>
                                                                        <input type="radio" id="probation_period"  name="probation_period" value="0" data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_yes')"> yes 
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" id="probation_period" name="probation_period" value="1" checked="" data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_no')"> no 
                                                                    </label>


                                                                <?php } else { ?>
                                                                    <label>
                                                                        <input type="radio" id="probation_period"  name="probation_period" value="0" checked  data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_yes')"> yes 
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" id="probation_period" name="probation_period" value="1" data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_no')"> no 
                                                                    </label>
                                                                <?php } ?>   
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
                                                            <input type="Date" name="prob_date_first" value="<?php
                                                            $originalDate1 = $result_firm_data1->probation_period_start_date;
                                                            $newcompletionDate1 = date("Y-m-d", strtotime($originalDate1));
                                                            echo $newcompletionDate1;
                                                            ?>" onchange="remove_error('prob_date_first')" id="prob_date_first"ata-required="1" class="form-control" placeholder="Completion Date"> 
                                                        </div>
                                                        <span class="required" id="prob_date_first_error" style="color:red"></span><br>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>To</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            <input type="Date" name="prob_date_second"  value="<?php
                                                            $originalDate2 = $result_firm_data1->probation_period_end_date;
                                                            $newcompletionDate2 = date("Y-m-d", strtotime($originalDate2));
                                                            echo $newcompletionDate2;
                                                            ?>" onchange="remove_error('prob_date_second')" id="prob_date_second"ata-required="1" class="form-control" placeholder="Completion Date"> 
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
                                                                <?php if ($result_firm_data1->training_period_start_date == '0000-00-00 00:00:00') { ?>
                                                                    <label>
                                                                        <input type="radio" id="training_period" name="training_period" value="0" data-checkbox="icheckbox_flat-grey" onclick="training_change('train_yes')"> yes </label>
                                                                    <label>
                                                                        <input type="radio" id="training_period" name="training_period" value="1" checked="" data-checkbox="icheckbox_flat-grey" onclick="training_change('train_no')"> no </label>
                                                                <?php } else { ?>

                                                                    <label>
                                                                        <input type="radio" id="training_period" name="training_period" value="0" checked  data-checkbox="icheckbox_flat-grey" onclick="training_change('train_yes')"> yes </label>
                                                                    <label>
                                                                        <input type="radio" id="training_period" name="training_period" value="1" data-checkbox="icheckbox_flat-grey" onclick="training_change('train_no')"> no </label>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="train_yes" style="display: block;">
                                                    <div class="col-md-3">
                                                        <label>From</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            <input type="Date" name="training_period_first" value="<?php
                                                            $originalDate3 = $result_firm_data1->training_period_start_date;
                                                            $newcompletionDate3 = date("Y-m-d", strtotime($originalDate3));
                                                            echo $newcompletionDate3;
                                                            ?>"" onchange="remove_error('training_period_first')" id="training_period_first"ata-required="1" class="form-control" placeholder="Completion Date"> 
                                                        </div>
                                                        <span class="required" id="training_period_first_error" style="color:red"></span><br>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>To</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            <input type="Date" name="training_period_second" value="<?php
                                                            $originalDate4 = $result_firm_data1->training_period_end_date;
                                                            $newcompletionDate4 = date("Y-m-d", strtotime($originalDate4));
                                                            echo $newcompletionDate4;
                                                            ?>" onchange="remove_error('training_period_second')" id="training_period_second"ata-required="1" class="form-control" placeholder="Completion Date"> 
                                                        </div>
                                                        <span class="required" id="training_period_second_error" style="color:red"></span>
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
                                    <div class="row">


                                        <div class="col-md-4">
                                            <!--<span class="required" id="numofdays7_error" ></span>-->
                                            <div class="m-radio-inline">
                                                <label for="" style="color: #002a80">
                                                    These Leave Type Are Same For All Designations :
                                                </label>
                                                <?php if ($result_desig_data->holiday_consider_in_leave == 1) { ?>
                                                    <label class="mt-radio m-radio--solid">
                                                        <input type="radio" name="leave_type_permission" id="leave_type_permission"   value="1">
                                                        Yes
                                                        <span></span>
                                                    </label>
                                                    <!--                                                    <label class="mt-radio m-radio--solid">
                                                                                                            <input type="radio" name="leave_type_permission" id="leave_type_permission" checked="" value="2">
                                                                                                            No
                                                                                                            <span></span>
                                                                                                        </label>-->
                                                <?php } else { ?>
                                                    <label class="mt-radio m-radio--solid">
                                                        <input type="radio" name="leave_type_permission" id="leave_type_permission"  checked="" value="1">
                                                        Yes
                                                        <span></span>
                                                    </label>
                                                    <!--                                                    <label class="mt-radio m-radio--solid">
                                                                                                            <input type="radio" name="leave_type_permission" id="leave_type_permission"  value="2">
                                                                                                            No
                                                                                                            <span></span>
                                                                                                        </label>-->
                                                <?php } ?>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <?php
                                            $month_data = $result_firm_data->accural_month;
                                            if ($month_data == 1) {
                                                $month_name = "January";
                                            } else if ($month_data == 2) {
                                                $month_name = "February";
                                            } else if ($month_data == 3) {
                                                $month_name = "March";
                                            } else if ($month_data == 4) {
                                                $month_name = "April";
                                            } else if ($month_data == 5) {
                                                $month_name = "May";
                                            } else if ($month_data == 6) {
                                                $month_name = "June";
                                            } else if ($month_data == 7) {
                                                $month_name = "July";
                                            } else if ($month_data == 8) {
                                                $month_name = "August";
                                            } else if ($month_data == 9) {
                                                $month_name = "September";
                                            } else if ($month_data == 10) {
                                                $month_name = "October";
                                            } else if ($month_data == 11) {
                                                $month_name = "November";
                                            } else if ($month_data == 12) {
                                                $month_name = "December";
                                            } else {
                                                $month_name = "Select Month";
                                            }
                                            ?>

                                            <label>Accrual Month</label>
                                            <select class="form-control m-select2 m-select2-general" onchange="remove_error('accural_month')" id="accural_month" name="accural_month">
                                                <option value="<?php echo $result_firm_data->accural_month ?>"><?php echo $month_name ?></option>
                                                <!--<option value="">Select Month</option>-->
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
                                    <span class="required" id="numofdays7_error" style="text-align: center;"></span>

                                    <?php
                                    $type1 = $result_leave_data->type1;
                                    if ($type1 !== "") {
                                        $t_arr1 = explode(":", $type1);
                                        $leave_type1 = $t_arr1[0];
                                        $numdays1 = $t_arr1[1];
                                        $CF1 = $t_arr1[1];
                                        $req_bfr1 = $t_arr1[2];
//                                        $aprv_bfr1 = $t_arr1[3];
                                    } else {
                                        $leave_type1 = '';
                                        $numdays1 = '';
                                        $CF1 = '';
                                        $req_bfr1 = '';
//                                        $aprv_bfr1 = '';
                                    }
                                    $type2 = $result_leave_data->type2;
                                    if ($type2 !== "") {
                                        $t_arr2 = explode(":", $type2);
                                        $leave_type2 = $t_arr2[0];
                                        $numdays2 = $t_arr2[1];
                                        $CF2 = $t_arr1[1];
                                        $req_bfr2 = $t_arr2[2];
//                                        $aprv_bfr2 = $t_arr2[3];
                                    } else {
                                        $leave_type2 = '';
                                        $numdays2 = '';
                                        $CF2 = '';
                                        $req_bfr2 = '';
//                                        $aprv_bfr2 = '';
                                    }
                                    $type3 = $result_leave_data->type3;
                                    if ($type3 !== "") {
                                        $t_arr3 = explode(":", $type3);
                                        $leave_type3 = $t_arr3[0];
                                        $numdays3 = $t_arr3[1];
                                        $CF3 = $t_arr3[1];
                                        $req_bfr3 = $t_arr3[2];
//                                        $aprv_bfr3 = $t_arr3[3];
                                    } else {
                                        $leave_type3 = '';
                                        $numdays3 = '';
                                        $CF3 = '';
                                        $req_bfr3 = '';
//                                        $aprv_bfr3 = '';
                                    }
                                    $type4 = $result_leave_data->type4;
                                    if ($type4 !== "") {
                                        $t_arr4 = explode(":", $type4);
//                                        var_dump($t_arr4);
                                        $leave_type4 = $t_arr4[0];
                                        $numdays4 = $t_arr4[1];
                                        $CF4 = $t_arr4[1];
                                        $req_bfr4 = $t_arr4[2];
//                                        $aprv_bfr4 = $t_arr4[3];
                                    } else {
                                        $leave_type4 = '';
                                        $numdays4 = '';
                                        $CF4 = '';
                                        $req_bfr4 = '';
//                                        $aprv_bfr4 = '';
                                    }
                                    $type5 = $result_leave_data->type5;
                                    if ($type5 !== "") {
                                        $t_arr5 = explode(":", $type5);
                                        $leave_type5 = $t_arr5[0];
                                        $numdays5 = $t_arr5[1];
                                        $CF5 = $t_arr5[1];
                                        $req_bfr5 = $t_arr5[2];
//                                        $aprv_bfr5 = $t_arr5[3];
                                    } else {
                                        $leave_type5 = '';
                                        $numdays5 = '';
                                        $CF5 = '';
                                        $req_bfr5 = '';
//                                        $aprv_bfr5 = '';
                                    }
                                    $type6 = $result_leave_data->type6;
                                    if ($type6 !== "") {
                                        $t_arr6 = explode(":", $type6);
                                        $leave_type6 = $t_arr6[0];
                                        $numdays6 = $t_arr6[1];
                                        $CF6 = $t_arr6[1];
                                        $req_bfr6 = $t_arr6[2];
//                                        $aprv_bfr6 = $t_arr6[3];
                                    } else {
                                        $leave_type6 = '';
                                        $numdays6 = '';
                                        $CF6 = '';
                                        $req_bfr6 = '';
//                                        $aprv_bfr6 = '';
                                    }
                                    $type7 = $result_leave_data->type7;
                                    if ($type7 !== "") {
                                        $t_arr7 = explode(":", $type7);
                                        $leave_type7 = $t_arr7[0];
                                        $numdays7 = $t_arr7[1];
                                        $CF7 = $t_arr7[1];
                                        $req_bfr7 = $t_arr7[2];
//                                        $aprv_bfr7 = $t_arr7[3];
                                    } else {
                                        $leave_type7 = '';
                                        $numdays7 = '';
                                        $CF7 = '';
                                        $req_bfr7 = '';
//                                        $aprv_bfr7 = '';
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-3"><span style="font-weight:bold;">Types </span></div>
                                        <div class="col-md-2"><span style="font-weight:bold;">No. of days </span></div>
                                        <div class="col-md-2"><span style="font-weight:bold;">Carry Forward </span></div>
                                        <!--<div class="col-md-2"><span style="font-weight:bold;">Leave Approve Before(Days)</span></div>-->
                                    </div><br>


                                    <div class="row">
                                        <DIV class="col-md-1"><span style="font-weigth:bold;">Type 1:</span></DIV>
                                        <DIV class="col-md-3"><input type="text" value="<?php echo $leave_type1; ?>" id="leave_type1" name="leave_type1" onkeyup="remove_error('leave1')" class="form-control" placeholder="First Leave Type"></DIV>
                                        <DIV class="col-md-2"><input type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" min="1" value="<?php echo $numdays1; ?>" id="numofdays1" name="numofdays1" onkeyup="remove_error('leave1')" class="form-control" placeholder="No Of Days"></DIV>

                                        <?php if ($req_bfr1 == 1) { ?>
                                            <DIV class="col-md-2"><label><input type="radio" value="1" id="CF1" name="CF1" onkeyup="remove_error('CF1')"  checked="">Yes</label>
                                                <label><input type="radio" value="0" id="CF1" name="CF1" onkeyup="remove_error('leave1')"  >No</label>
                                            </DIV>
                                        <?php } else { ?>
                                            <DIV class="col-md-2"><label><input type="radio" value="1" id="CF1" name="CF1" onkeyup="remove_error('CF1')"  >Yes</label>
                                                <label><input type="radio" value="0" id="CF1" name="CF1" checked="" onkeyup="remove_error('leave1')"  >No</label>
                                            </DIV>
                                        <?php } ?>
                                    </DIV><br>


                                    <div class="row">
                                        <DIV class="col-md-1"><span style="font-weigth:bold;">Type 2:</span></DIV>
                                        <DIV class="col-md-3"><input type="text" id="leave_type2" value="<?php echo $leave_type2; ?>" name="leave_type2" onkeyup="remove_error('leave_type2')" class="form-control" placeholder="Second Leave Type"></DIV>
                                        <DIV class="col-md-2"><input type="number" min="1" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" value="<?php echo $numdays2; ?>" id="numofdays2" name="numofdays2" onkeyup="remove_error('numofdays2')" class="form-control" placeholder="No Of Days"></DIV>
                                        <?php if ($req_bfr2 == 1) { ?>
                                            <DIV class="col-md-2">
                                                <label>
                                                    <input type="radio" value="1" id="CF2" name="CF2" onkeyup="remove_error('leave1')"  checked="">Yes</label>

                                                <label><input type="radio" value="0" id="CF2" name="CF2" onkeyup="remove_error('leave1')"  >No</label>
                                            </DIV>
                                        <?php } else { ?>
                                            <DIV class="col-md-2">
                                                <label>
                                                    <input type="radio" value="1" id="CF2" name="CF2" onkeyup="remove_error('leave1')"  >Yes</label>

                                                <label><input type="radio" value="0" id="CF2" name="CF2" checked="" onkeyup="remove_error('leave1')"  >No</label>
                                            </DIV>
                                        <?php } ?>


                <!--                                        <DIV class="col-md-2"><input type="number" min="0" value="<?php echo $req_bfr2; ?>" id="request_before2" name="request_before2" onkeyup="remove_error('designation_name')" class="form-control" placeholder="Request Before"></DIV>
                                                        <DIV class="col-md-2"><input type="number" min="1" value="<?php echo $aprv_bfr2; ?>" id="approve_before2" name="approve_before2" onkeyup="remove_error('designation_name')" class="form-control" placeholder="Approve Before"></DIV>-->
                                    </div><br>
                                    <div class="row">
                                        <DIV class="col-md-1"><span style="font-weigth:bold;">Type 3:</span></DIV>
                                        <DIV class="col-md-3"><input type="text" id="leave_type3"  value="<?php echo $leave_type3; ?>"name="leave_type3" onkeyup="remove_error('leave_type3')" class="form-control" placeholder="Third Leave Type"></DIV>
                                        <DIV class="col-md-2"><input type="number" min="1" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" value="<?php echo $numdays3; ?>" id="numofdays3" name="numofdays3" onkeyup="remove_error('numofdays3')" class="form-control" placeholder="No Of Days"></DIV>
                                        <?php if ($req_bfr3 == 1) { ?>
                                            <DIV class="col-md-2"><label><input type="radio" value="1" id="CF3" name="CF3" onkeyup="remove_error('leave1')" checked="" >Yes</label>
                                                <label><input type="radio" value="0" id="CF3" name="CF3" onkeyup="remove_error('leave1')"  >No</label>
                                            </DIV>
                                        <?php } else { ?>
                                            <DIV class="col-md-2"><label><input type="radio" value="1" id="CF3" name="CF3" onkeyup="remove_error('leave1')"  >Yes</label>
                                                <label><input type="radio" value="0" id="CF3" name="CF3" onkeyup="remove_error('leave1')" checked="" >No</label>
                                            </DIV>
                                        <?php } ?>
<!--                                        <DIV class="col-md-2"><input type="number" min="0" value="<?php echo $req_bfr3; ?>"id="request_before3" name="request_before3" onkeyup="remove_error('designation_name')" class="form-control" placeholder="Request Before"></DIV>
            <DIV class="col-md-2"><input type="number" min="1" value="<?php echo $aprv_bfr3; ?>"id="approve_before3" name="approve_before3" onkeyup="remove_error('designation_name')" class="form-control" placeholder="Approve Before"></DIV>-->
                                    </div><br>

                                    <?php if ($type4 !== '') { ?>
                                        <div class="row">
                                            <DIV class="col-md-1"><span style="font-weigth:bold;">Type 4:</span></DIV>
                                            <DIV class="col-md-3"><input type="text" id="leave_type4" value="<?php echo $leave_type4; ?>" name="leave_type4" onkeyup="remove_error('leave_type4')" class="form-control" placeholder="Fourth Leave Type"></DIV>
                                            <DIV class="col-md-2"><input type="number"  onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"min="1" value="<?php echo $numdays4; ?>" id="numofdays4" name="numofdays4" onkeyup="remove_error('numofdays4')" class="form-control" placeholder="No Of Days"></DIV>
                                            <?php if ($req_bfr4 == 1) { ?>
                                                <DIV class="col-md-2"><label><input type="radio" value="1" id="CF4" name="CF4" onkeyup="remove_error('leave1')"  checked="" >Yes</label>
                                                    <label><input type="radio" value="0" id="CF4" name="CF4" onkeyup="remove_error('leave1')"  >No</label>
                                                </DIV>
                                            <?php } else { ?>
                                                <DIV class="col-md-2"><label><input type="radio" value="1" id="CF4" name="CF4" onkeyup="remove_error('leave1')"  >Yes</label>
                                                    <label><input type="radio" value="0" id="CF4" name="CF4" onkeyup="remove_error('leave1')" checked=""  >No</label>
                                                </DIV>
                                            <?php } ?>
                                        </DIV><br>
                                    <?php } else { ?>
                                        <div class="row">
                                            <DIV class="col-md-1"><span style="font-weigth:bold;">Type 4:</span></DIV>
                                            <DIV class="col-md-3"><input type="text" id="leave_type4" value="" name="leave_type4"  onkeyup="remove_error('leave_type4')" class="form-control" placeholder="Fourth Leave Type"></DIV>
                                            <DIV class="col-md-2"><input type="number"  onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"min="1" value="" id="numofdays4" name="numofdays4" onkeyup="remove_error('numofdays4')" class="form-control" placeholder="No Of Days"></DIV>

                                            <DIV class="col-md-2"><label><input type="radio" value="1" id="CF4" name="CF4" onkeyup="remove_error('leave1')"  >Yes</label>
                                                <label><input type="radio" value="0" id="CF4" name="CF4" onkeyup="remove_error('leave1')" checked="" >No</label>
                                            </DIV>

                                        </DIV>  <br> 
                                    <?php } ?>

                                    <?php if ($type5 !== '') { ?>
                                        <div class="row">
                                            <DIV class="col-md-1"><span style="font-weigth:bold;">Type 5:</span></DIV>
                                            <DIV class="col-md-3"><input type="text" id="leave_type5" value="<?php echo $leave_type5; ?>" name="leave_type5" onkeyup="remove_error('leave_type5')" class="form-control" placeholder="Fifth Leave Type"></DIV>
                                            <DIV class="col-md-2"><input type="number"  onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"min="1"  value="<?php echo $numdays5; ?>"id="numofdays5" name="numofdays5" onkeyup="remove_error('numofdays5')" class="form-control" placeholder="No Of Days"></DIV>

                                            <?php if ($req_bfr5 == 1) { ?>
                                                <DIV class="col-md-2"><label><input type="radio" value="1" id="CF5" name="CF5" onkeyup="remove_error('leave1')"  checked="" >Yes</label>
                                                    <label><input type="radio" value="0" id="CF1" name="CF5" onkeyup="remove_error('leave1')"  >No</label>
                                                </DIV>
                                            <?php } else { ?>
                                                <DIV class="col-md-2"><label><input type="radio" value="1" id="CF5" name="CF5" onkeyup="remove_error('leave1')"  >Yes</label>
                                                    <label><input type="radio" value="0" id="CF1" name="CF5" onkeyup="remove_error('leave1')"  checked="" >No</label>
                                                </DIV>
                                            <?php } ?>
                                        </div><br>
                                    <?php } else { ?>
                                        <div class="row">
                                            <DIV class="col-md-1"><span style="font-weigth:bold;">Type 5:</span></DIV>
                                            <DIV class="col-md-3"><input type="text" id="leave_type5" value="" name="leave_type5" onkeyup="remove_error('leave_type5')" class="form-control" placeholder="Fifth Leave Type"></DIV>
                                            <DIV class="col-md-2"><input type="number" min="1" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"  value=""id="numofdays5" name="numofdays5" onkeyup="remove_error('numofdays5')" class="form-control" placeholder="No Of Days"></DIV>

                                            <DIV class="col-md-2"><label><input type="radio" value="1" id="CF5" name="CF5" onkeyup="remove_error('leave1')"  checked="" >Yes</label>
                                                <label><input type="radio" value="0" id="CF1" name="CF5" onkeyup="remove_error('leave1')"  >No</label>
                                            </DIV>
                                        </div><br>
                                    <?php } ?>

                                    <?php if ($type6 !== '') { ?>
                                        <div class="row">
                                            <DIV class="col-md-1"><span style="font-weigth:bold;">Type 6:</span></DIV>
                                            <DIV class="col-md-3"><input type="text" id="leave_type6" value="<?php echo $leave_type6; ?>" name="leave_type6" onkeyup="remove_error('leave_type6')" class="form-control" placeholder="Sixth Leave Type"></DIV>
                                            <DIV class="col-md-2"><input type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" min="1" value="<?php echo $numdays6; ?>" id="numofdays6" name="numofdays6" onkeyup="remove_error('numofdays6')" class="form-control" placeholder="No Of Days"></DIV>

                                            <?php if ($req_bfr6 == 1) { ?>
                                                <DIV class="col-md-2"><label><input type="radio" value="1" id="CF6" name="CF6" onkeyup="remove_error('leave1')"  checked="">Yes</label>
                                                    <label><input type="radio" value="0" id="CF6" name="CF6" onkeyup="remove_error('leave1')"  >No</label>
                                                </DIV>
                                            <?php } else { ?>
                                                <DIV class="col-md-2"><label><input type="radio" value="1" id="CF6" name="CF6" onkeyup="remove_error('leave1')"  >Yes</label>
                                                    <label><input type="radio" value="0" id="CF6" name="CF6" onkeyup="remove_error('leave1')"  checked="">No</label>
                                                </DIV>
                                            <?php } ?>
                                        </DIV><br>

                                    <?php } else { ?>
                                        <div class="row">
                                            <DIV class="col-md-1"><span style="font-weigth:bold;">Type 6:</span></DIV>
                                            <DIV class="col-md-3"><input type="text" id="leave_type6" value="" name="leave_type6" onkeyup="remove_error('leave_type6')" class="form-control" placeholder="Sixth Leave Type"></DIV>
                                            <DIV class="col-md-2"><input type="number"  onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"min="1" value="" id="numofdays6" name="numofdays6" onkeyup="remove_error('numofdays6')" class="form-control" placeholder="No Of Days"></DIV>


                                            <DIV class="col-md-2"><label><input type="radio" value="1" id="CF6" name="CF6" onkeyup="remove_error('leave1')"  checked="">Yes</label>
                                                <label><input type="radio" value="0" id="CF6" name="CF6" onkeyup="remove_error('leave1')"  >No</label>
                                            </DIV>
                                        </DIV><br>
                                    <?php } ?>


                                    <?php if ($type7 !== '') { ?>
                                        <div class="row">
                                            <DIV class="col-md-1"><span style="font-weigth:bold;">Type 7:</span></DIV>
                                            <DIV class="col-md-3"><input type="text" id="leave_type7" value="<?php echo $leave_type7; ?>" name="leave_type7" onkeyup="remove_error('leave_type7')" class="form-control" placeholder="Seventh Leave Type"></DIV>
                                            <DIV class="col-md-2"><input type="number" min="1"  onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" value="<?php echo $numdays7; ?>"id="numofdays7" name="numofdays7" onkeyup="remove_error('numofdays7')" class="form-control" placeholder="No Of Days"></DIV>
                                            <?php if ($req_bfr7 == 1) { ?>
                                                <DIV class="col-md-2"><label><input type="radio" value="1" id="CF7" name="CF7" onkeyup="remove_error('leave1')"  checked="">Yes</label>
                                                    <label><input type="radio" value="0" id="CF7" name="CF7" onkeyup="remove_error('leave1')"  >No</label>
                                                </DIV>
                                            <?php } else { ?>
                                                <DIV class="col-md-2"><label><input type="radio" value="1" id="CF7" name="CF7" onkeyup="remove_error('leave1')"  >Yes</label>
                                                    <label><input type="radio" value="0" id="CF7" name="CF7" onkeyup="remove_error('leave1')"  checked="">No</label>
                                                </DIV>
                                            <?php } ?>

                                        </DIV><br>
                                    <?php } else { ?>
                                        <div class="row">
                                            <DIV class="col-md-1"><span style="font-weigth:bold;">Type 7:</span></DIV>
                                            <DIV class="col-md-3"><input type="text" id="leave_type7" value="" name="leave_type7" onkeyup="remove_error('leave_type7')" class="form-control" placeholder="Seventh Leave Type"></DIV>
                                            <DIV class="col-md-2"><input type="number"  onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"min="1"  value=""id="numofdays7" name="numofdays7" onkeyup="remove_error('numofdays7')" class="form-control" placeholder="No Of Days"></DIV>

                                            <DIV class="col-md-2"><label><input type="radio" value="1" id="CF7" name="CF7" onkeyup="remove_error('leave1')"  checked="">Yes</label>
                                                <label><input type="radio" value="0" id="CF7" name="CF7" onkeyup="remove_error('leave1')"  >No</label>
                                            </DIV>
		 
			 
			

                                        </DIV><br>
                                    <?php } ?>





                                </div>
                            </div>
                    </div>

                    <div class="loading" id="loader" style="display:none;"></div>


                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a href="<?= base_url() ?>hq_show_firm_hr"><button type="button" class="btn grey-salsa btn-outline"  style="float:right; margin: 2px;">Cancel</button></a>
                                <button  type="button" id="update_firm" name="update_firm" class="btn green"  style="float:right;  margin: 2px;">Update </button>

                            </div>
                        </div>
                    </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
            <!-- END VALIDATION STATES-->
        </div>
        <?php $this->load->view('human_resource/footer'); ?>
    </div>

    <!-- END PAGE BASE CONTENT -->
</div>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script>
<?php if ($result_firm_data1->probation_period_start_date == '0000-00-00 00:00:00') {
    ?>
                                                        document.getElementById("prob_yes").style.display = "none";
<?php } else { ?>
                                                        document.getElementById("prob_yes").style.display = "block";
    <?php
}
if ($result_firm_data1->training_period_start_date == '0000-00-00 00:00:00') {
    ?>
                                                        document.getElementById("train_yes").style.display = "none";
<?php } else { ?>
                                                        document.getElementById("train_yes").style.display = "block";
<?php } ?>
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
			 
                                                    $("#yearly_leave").keyup(function () {
                                                        $("#monthly_leaves").val($("#yearly_leave").val() / 12);
                                                    });

                                                    $("#update_firm").click(function () {
                                                        var $this = $(this);
                                                        $this.button('loading');
                                                        setTimeout(function () {
                                                            $this.button('reset');
                                                        }, 2000);
                                                        document.getElementById('loader').style.display = "block";
                                                        $.ajax({
                                                            type: "post",
                                                            url: "<?= base_url("Hq_firm_form/update_firm_data") ?>",
                                                            dataType: "json",
                                                            data: $("#frm_edit_form").serialize(),
                                                            success: function (result) {
                                                                if (result.status === true) {

                                                                    document.getElementById('loader').style.display = "none";
                                                                    alert('Updation successfully');
                                                                    // return;
                                                                    //                                                             window.location.href = "<?= base_url("hq_show_firm") ?>";
                                                                    window.location.href = "<?= base_url("hq_show_firm_hr") ?>";
                                                                    //                                                            location.reload();
                                                                } else if (result.status === false) {
                                                                    document.getElementById('loader').style.display = "none";
                                                                    alert('something went wrong')
                                                                } else {
                                                                    document.getElementById('loader').style.display = "none";
                                                                    //                    $('#message').html(result.error);
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
                                                                    alert('Unexpected error.');
                                                                }
                                                            }
                                                        });
                                                    });
			   
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
                                                    
                                                    //Remove duplicate value from dropdown for yearly type  
                                                    var code = {};
                                                    $("select[name='leave_type_year'] > option").each(function () {
                                                        if (code[this.text]) {
                                                            $(this).remove();
                                                        } else {
                                                            code[this.text] = this.value;
                                                        }
                                                    });
                                                    
                                                    //Remove duplicate value from dropdown for leaves from
                                                    var code1 = {};
                                                    $("select[name='leave_apply_permission'] > option").each(function () {
                                                        if (code1[this.text]) {
                                                            $(this).remove();
                                                        } else {
                                                            code1[this.text] = this.value;
                                                        }
                                                    });
                                                    
                                                     //Remove duplicate value from dropdown for leaves Carry forward
                                                    var code3 = {};
                                                    $("select[name='leave_cf'] > option").each(function () {
                                                        if (code3[this.text]) {
                                                            $(this).remove();
                                                        } else {
                                                            code3[this.text] = this.value;
                                                        }
                                                    });
                                                    
                                                    //Remove duplicate value from dropdown for accural month
                                                    var code4 = {};
                                                    $("select[name='accural_month'] > option").each(function () {
                                                        if (code4[this.text]) {
                                                            $(this).remove();
                                                        } else {
                                                            code4[this.text] = this.value;
                                                        }
                                                    });
                                                    
                                                    
                                                    

                                                    function  remove_error(id) {
                                                        $('#' + id + '_error').html("");

				   
					 
			 
                                                    }
</script>