<?php
// $this->load->view('admin/header');
$this->load->view('human_resource/navigation');
defined('BASEPATH') or exit('No direct script access allowed');

$user_id = $this->session->userdata('login_session');
$user_type = $user_id['user_type'];

if ($user_id == '') {
	redirect(base_url() . 'login'); //take them back to signin
}
$head_text = 'Add Employee';
?>
<!--<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />-->
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet"
	  type="text/css"/>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
	  type="text/css"/>
<link rel="stylesheet"
	  href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-tagsinput/bootstrap-tagsinput/bootstrap-tagsinput.css"/>
<style>
	span.error {
		color: red;
	}

	.required {
		color: red !important;
	}

	.tabbable-custom > .nav-tabs > li > a {
		margin-right: 0;
		color: black !important;
	}

	.tabbable-custom > .nav-tabs > li {
		margin-right: 2px;
		background-color: #7cabb7 !important;
		border-top: 2px solid #f9f3f3b5;
	}

</style>
<div class="page-content-wrapper">
	<div class="page-content">
		<div class="page-bar">

			<div class="page-toolbar">
				<ul class="page-breadcrumb">
					<li class="<?= ($this->uri->segment(1) === 'calendar') ? 'active' : '' ?>">
						<a href="<?= base_url() ?>calendar">Home</a>
						<i class="fa fa-arrow-right"
						   style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
					</li>
					<li>
						<a href="#"><?php echo $prev_title; ?></a>
						<i class="fa fa-circle"
						   style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
					</li>

				</ul>

			</div>
		</div>
		<!-- <div class="page-fixed-main-content"> -->
		<div class="row"></div>
		<div class="portlet light ">

			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria- labelledby="myModalLabel"
				 aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-body">
							There is no Designation made in your company,you will not able to create employee.Please Add
							designation click on ok buttton
						</div>
						<div class="modal-footer">

							<button type="button" id="due_date" class="btn btn-primary">ok</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div>

			<div class="portlet-title">
				<div class="caption font-red-sunglo">
					<i class="icon-settings font-red-sunglo"></i>
					<span class="caption-subject bold uppercase">Create Employee</span>
				</div>

			</div>

			<div class="modal-body">
				<input type="hidden" name="emp_idx" id="emp_idx" value=""><input type="hidden" name="firm_idx"
																				 id="firm_idx" value="">
				<!--<form id="Salary_Details" name="Salary_Details" method="POST">-->
				<div class="tabbable-custom ">
					<ul class="nav nav-tabs ">
						<li class="active">
							<a href="#tab_5_1" data-toggle="tab"> Basic Details</a>
						</li>
						<li>
							<a href="#tab_5_2" data-toggle="tab" id="tab_5_2_attend">Attendance </a>
						</li>
						<li>
							<a href="#tab_5_3" onclick="chec()" data-toggle="tab" id="tab_5_3_salary"> Salary Details </a>
						</li>
						<li>
							<a href="#tab_5_5" data-toggle="tab"> Leave Configuration </a>
						</li>
						<li>
							<a href="#tab_5_4" data-toggle="tab"> Employee Exit Formalities </a>
						</li>

					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_5_1">
							<div class="form-body">
								<form method="POST" id="addemp" name="addemp" class="form-horizontal"
									  novalidate="novalidate">
									<input type="hidden" name="firm_user_type" id="firm_user_type"
										   value="<?php echo $user_type ?>">
									<div class="form-group">
										<div class="col-md-12">
											<p id="message" style="color:red"></p>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-4">
											<label class="">Employee Exists in RMT?</label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
												<select class="form-control" id="aa_ans" name="aa_ans"
														onchange="display_email()">
													<option value="1">No</option>
													<option value="2">Yes</option>
												</select>
											</div>
											<span class="required" id="aa_error" style="color:red"></span>
										</div>
										<div class="col-md-4" id="rmt_mail_div" style="display:none">
											<label class="">Select Employee Mail</label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
												<select class="form-control" id="rmt_mail" name="rmt_mail"
														onchange="get_rmt_emp_info()">
													<option value="">Select Email ID</option>
												</select>
											</div>
											<span class="required" id="rmt_mail_error" style="color:red"></span>
										</div>
									</div>
									<div class="form-group">

										<?php if ($user_type == 2) { ?>
											<div class="col-md-4">
												<label class="">Office Name</label>
												<div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i></span>
													<select name="firm_name" id="firm_name"
															onchange="remove_error('firm_name');get_designation();get_services();"
															class="form-control m-select2 m-select2-general">
														<option value="">Select Office</option>
													</select>
												</div>
												<span class="required" id="firm_name_error" style="color:red"></span>
											</div>
										<?php } ?>

										<div class="col-md-4">

											<label>Name<span class="error">*</span></label>

											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
												<input type="text" class="form-control" name="user_name" id="user_name"
													   placeholder="Enter Your Name" oninput="remove_error('user_name')"
													   aria-required="true" aria-describedby="input_group-error">
											</div>
											<span class="required" id="user_name_error" style="color:red"></span>
										</div>
										<div class="col-md-4">

											<label>Father's / Husband Name<span class="error">*</span></label>

											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
												<input type="text" class="form-control" name="fh_name" id="fh_name"
													   placeholder="Enter Father's / Husband Name"
													   oninput="remove_error('fh_name')" aria-required="true"
													   aria-describedby="input_group-error">
											</div>
											<span class="required" id="fh_name_error" style="color:red"></span>
										</div>
										<div class="col-md-4">

											<label>Date Of Birth<span class="error">*</span></label>

											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
												<input type="date" class="form-control" name="dob" id="dob"
													   placeholder="Enter Your Date Of Birth"
													   oninput="remove_error('dob')" aria-required="true"
													   aria-describedby="input_group-error">
											</div>
											<span class="required" id="dob_error" style="color:red"></span>
										</div>

										<div class="col-md-4">
											<label>Gender<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-odnoklassniki"></i>
                                                </span>
												<select name="gender" id="gender"
														class="form-control m-select2 m-select2-general"
														oninput="remove_error('gender')">
													<option value="">Select Gender</option>
													<option value="1">Male</option>
													<option value="2">Female</option>
													<!--<option>Others</option>-->
												</select>
											</div>
											<span class="required" id="gender_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>Mobile No<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-phone"></i>
                                                </span>
												<input type="number"
													   onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57"
													   onkeydown="javascript: return event.keyCode == 69 ? false : true"
													   name="mobile_no" oninput="remove_error('mobile_no')"
													   id="mobile_no" data-required="1" class="form-control"
													   placeholder="Enter your Mobile No">
											</div>
											<span class="required" id="mobile_no_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>UAN<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-rupee"></i>
                                                </span>
												<input type="text" name="uan" oninput="remove_error('uan')" id="uan"
													   data-required="1" class="form-control"
													   placeholder="Enter your UAN No">
											</div>
											<span class="required" id="uan_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>PAN No<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-phone"></i>
                                                </span>
												<input type="text" name="pan" oninput="remove_error('pan')" id="pan"
													   data-required="1" class="form-control"
													   placeholder="Enter your PAN No">
											</div>
											<span class="required" id="pan_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>Department Name<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-phone"></i>
                                                </span>
												<input type="text" name="dept_name" oninput="remove_error('dept_name')"
													   id="dept_name" data-required="1" class="form-control"
													   placeholder="Enter your Department Name">
											</div>
											<span class="required" id="dept_name_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>Account No<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-bank"></i>
                                                </span>
												<input type="number" name="ac_no" oninput="remove_error('ac_no')"
													   id="ac_no" data-required="1" class="form-control"
													   placeholder="Enter your Account No">
											</div>
											<span class="required" id="ac_no_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>AdharCard No<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
												<input type="text" name="adhar_no" oninput="remove_error('adhar_no')"
													   id="adhar_no" data-required="1" class="form-control"
													   placeholder="Enter your Adhar No">
											</div>
											<span class="required" id="adhar_no_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>Bank Name<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-bank"></i>
                                                </span>
												<input type="text" name="bank_name" oninput="remove_error('bank_name')"
													   id="bank_name" data-required="1" class="form-control"
													   placeholder="Enter your Bank Name">
											</div>
											<span class="required" id="bank_name_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>Employee code<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
												<input type="text" name="emp_code" oninput="remove_error('emp_code')"
													   id="emp_code" data-required="1" class="form-control"
													   placeholder="Enter Employee Code">
											</div>
											<span class="required" id="emp_code_error" style="color:red"></span>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-4">
											<label class="">Email Id<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
												<input type="text" id="email" name="email"
													   onkeyup="remove_error('email')" class="form-control"
													   placeholder="Enter Your Email Id">
											</div>
											<span class="required" id="email_error"></span>
											<span id="email_result"></span>
										</div>
										<div class="col-md-4">
											<label class="">Current Address<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i></span>
												<input type="text" id="address" name="address"
													   oninput="remove_error('address')"
													   class="    form-control m-input"
													   placeholder="Enter Your Current Address">
											</div>
											<span class="required" id="address_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label class="">Permanant Address<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i></span>
												<input type="text" id="Paddress" name="Paddress"
													   oninput="remove_error('address')"
													   class="    form-control m-input"
													   placeholder="Enter Your Permanant Address">
											</div>
											<span class="required" id="address_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label>Country<span class="error">*</span></label>
											<div class="input-group">
												<span class="input-group-addon">  <i
															class="fa fa-flag"></i></span></span>
												<input type="text" id="country" name="country"
													   oninput="remove_error('country')" class="form-control m-input"
													   placeholder="Enter your country">
											</div>
											<span class="required" id="country_error" style="color:red"></span>
										</div>
										<!--										<div class="col-md-4">-->
										<!--											<label>Form Type:</label>-->
										<!---->
										<!--											<div class="input-group">-->
										<!--                                                <span class="input-group-addon">-->
										<!--                                                    <i class="fa fa-user"></i>-->
										<!--                                                </span>-->
										<!--												<select class="form-control" id="Vform_id" name="Vform_id"-->
										<!--														onchange="get_form16()">-->
										<!--													<option>Select form Type</option>-->
										<!--													<option value="1">Form 16 Old</option>-->
										<!--													<option value="2">Form 16 New</option>-->
										<!--												</select>-->
										<!--											</div>-->
										<!--											<span class="required" id="formType_error" style="color:red"></span>-->
										<!--										</div>-->

									</div>

									<div class="form-group">

										<div class="col-md-4">
											<label>State<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-home"></i></span>
												<input type="text" id="state" name="state"
													   oninput="remove_error('state')" class="form-control m-input"
													   placeholder="Enter Your State">
											</div>
											<span class="required" id="state_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label class="">City<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i></span>
												<input type="text" id="city" name="city" oninput="remove_error('city')"
													   class="form-control m-input" placeholder="Enter Your City">
											</div>
											<span class="required" id="city_error" style="color:red"></span>
										</div>

										<div class="col-md-4">
											<label class="">Designation<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i></span>
												<select name="designation" id="designation"
														onchange="remove_error('designation');check_leave_data();"
														class="form-control m-select2 m-select2-general">
													<option value="">Select Designation</option>
												</select>
											</div>
											<span class="required" id="designation_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<!--<label class="">Senior Employee</label>-->
											<label class="">Reporting Manager<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i></span>
												<select name="senior_emp" id="senior_emp"
														onchange="remove_error('senior_emp')"
														class="form-control m-select2 m-select2-general">
													<option value=''>Select Senior Authority</option>
												</select>
											</div>
											<span class="required" id="senior_emp_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label class="">Date Of Joining<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i></span>
												<input type="date" id="date_of_joining" name="date_of_joining"
													   onchange="remove_error('date_of_joining')"
													   onchange="onDateChange()" class="form-control m-input"
													   placeholder="Enter Your City">
											</div>
											<span class="required" id="date_of_joining_error" style="color:red"></span>
										</div>

									</div>

									<div class="form-group">


										<div class="col-md-4">
											<label class="">Skill Set</label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i></span>

												<input type="text" id="skill_set" name="skill_set"
													   onchange="remove_error('skill_set')" class="form-control m-input"
													   placeholder="Skill Set" data-role="tagsinput">
											</div>
											<span class="required" id="skill_set_error" style="color:red"></span>
										</div>
										<div class="col-md-4">
											<label class="">User star rating </label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-star"></i></span>
												<select name="star_rating" id="star_rating"
														class="form-control m-select2 ">
													<?php
													$a = 1;
													for ($a = 1; $a <= 10; $a++) {
														?>
														<option><?php echo $a; ?></option>
														<?php
													}
													?>
												</select>

											</div>
											<span class="required" id="star_rating_error" style="color:red"></span>
										</div>

										<div class="col-md-4">
											<label class="">Overtime Applicable<span class="error">*</span></label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i></span>
												<select name="ot_applicable_sts" id="ot_applicable_sts"
														onchange="remove_error('ot_applicable_sts')"
														class="form-control m-select2 m-select2-general">
													<option value="">Select option</option>
													<option value="1">Yes</option>
													<option value="2">No</option>
												</select>
												<span class="required" id="ot_applicable_sts_error"></span>
											</div>

										</div>


									</div>

									<div class="form-group">
										<div class="col-md-4" id="salary_calculation">

											<label><b>Salary Calculation:<span class="error">*</span></b></label><br>
											<div class="input-group">
												<label id="d">
													<input type="radio" id="salary_calcu1" name="salary_calcu" value="1"
														   data-checkbox="icheckbox_flat-grey"> Daily </label>&nbsp;&nbsp;&nbsp;&nbsp;
												<label id="dd">
													<input type="radio" id="salary_calcu2" name="salary_calcu" value="2"
														   data-checkbox="icheckbox_flat-grey"> Monthly </label>

											</div>
											<span class="required" style="color: red" id="salary_calcu_error"></span>
											<br>
										</div>
										<div class="col-md-6">
											<label class="">Compensatory Off Applicability<span class="error">*</span></label></div>
										<div class="col-md-6">
											<label id="a">
												<input type="radio" id="holiday_applible_sts"
													   name="holiday_applible_sts" value="1" checked
													   data-checkbox="icheckbox_flat-grey"> Applicable &nbsp;&nbsp;&nbsp;</label>
											<label id="aa">
												<input type="radio" id="holiday_applible_sts"
													   name="holiday_applible_sts" value="2"
													   data-checkbox="icheckbox_flat-grey"> Not Applicable &nbsp;&nbsp;&nbsp;</label>
											<label id="aa">
												<input type="radio" id="holiday_applible_sts"
													   name="holiday_applible_sts" value="3"
													   data-checkbox="icheckbox_flat-grey"> Applicable & Approval
												Required </label>
										</div>
										<div class="col-md-2" id="salary_calculation">

											<label><b>Sandwich Leave Applicability:</b><span class="error">*</span></label><br>
											<div class="input-group">
												<label id="d">
													<input type="radio" id="sandwichLeave1" name="sandwichLeave"
														   value="1" checked data-checkbox="icheckbox_flat-grey"> Yes
												</label>&nbsp;&nbsp;&nbsp;&nbsp;
												<label id="dd">
													<input type="radio" id="sandwichLeave2" name="sandwichLeave"
														   value="2" data-checkbox="icheckbox_flat-grey"> No </label>

											</div>
											<span class="required" style="color: red" id="salary_calcu_error"></span>
											<br>
										</div>
										<div class="col-md-2" id="salary_calculation">

											<label><b>GPS off Allow:</b><span class="error">*</span></label><br>
											<div class="input-group">
												<label id="d">
													<input type="radio" id="gpsoff1" name="gpsoff" value="1" checked
														   data-checkbox="icheckbox_flat-grey"> Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
												<label id="dd">
													<input type="radio" id="gpsoff2" name="gpsoff" value="0"
														   data-checkbox="icheckbox_flat-grey"> No </label>

											</div>
											<span class="required" style="color: red" id="salary_calcu_error"></span>
											<br>
										</div>


									</div>

									<div class="form-group">
										<div class="col-md-3">
											<div class="input-group">
												<div class="icheck-inline">
													<label>Probation Period<span class="error">*</span></label>
													<label id="a">
														<input type="radio" id="probation_period"
															   name="probation_period" value="0" checked
															   data-checkbox="icheckbox_flat-grey"
															   onclick="probation_change('prob_yes')"> yes </label>
													<label id="aa">
														<input type="radio" id="probation_period"
															   name="probation_period" value="1"
															   data-checkbox="icheckbox_flat-grey"
															   onclick="probation_change('prob_no')"> no </label>
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
													<input type="Date" name="prob_date_first"
														   onchange="remove_error('prob_date_first')"
														   id="prob_date_first" ata-required="1" class="form-control"
														   placeholder="Completion Date">

												</div>
												<span class="required" style="color: red"
													  id="prob_date_first_error"></span><br>
											</div>
											<div class="col-md-3">
												<label>To</label>
												<div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
													<input type="Date" name="prob_date_second"
														   onchange="remove_error('prob_date_second')"
														   id="prob_date_second" ata-required="1" class="form-control"
														   placeholder="Completion Date">

												</div>
												<span class="required" style="color: red"
													  id="prob_date_second_error"></span>
											</div>
										</div>

									</div>
									<div class="form-group">

										<div class="col-md-3">
											<div class="input-group">
												<div class="icheck-inline">
													<label>Training Period<span class="error">*</span></label>
													<label id="b">
														<input type="radio" id="training_period" name="training_period"
															   value="0" checked data-checkbox="icheckbox_flat-grey"
															   onclick="training_change('train_yes')"> yes </label>
													<label id="bb">
														<input type="radio" id="training_period" name="training_period"
															   value="1" data-checkbox="icheckbox_flat-grey"
															   onclick="training_change('train_no')"> no </label>
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
													<input type="Date" name="training_period_first"
														   onchange="remove_error('training_period_first')"
														   id="training_period_first" ata-required="1"
														   class="form-control" placeholder="Completion Date">

												</div>
												<span class="required" style="color: red"
													  id="training_period_first_error"></span> <br>
											</div>
											<div class="col-md-3">
												<label>To</label>
												<div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
													<input type="Date" name="training_period_second"
														   onchange="remove_error('training_period_second')"
														   id="training_period_second" ata-required="1"
														   class="form-control" placeholder="Completion Date">

												</div>
												<span class="required" style="color: red"
													  id="training_period_second_error"></span>
											</div>
										</div>
									</div>
									<div class="form-actions">
										<div class="row">
											<div class="col-md-offset-3 col-md-9">
												<a href="<?= base_url() ?>hr_show_employee"
												   class="btn grey-salsa btn-outline" style="float:right; margin: 2px;">Cancel</a>
												<button type="button" id="save" name="save" class="btn green"
														style="float:right;  margin: 2px;">Add Employee
												</button>
											</div>
										</div>
									</div>

								</form>
							</div>
						</div>

						<div class="tab-pane" id="tab_5_2">
							<div class="form-body">


								<form method="POST" id="emp_attendance_form" name="emp_attendance_form"
									  class="form-horizontal" novalidate="novalidate">

									<div class="form-group">
										<div class="col-md-4">
											<?php if ($user_type == 2) { ?>
												<label class="">Office Name</label>
												<div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i></span>
													<select name="firm_name_sal_attnd"
															onchange='remove_error("firm_name_sal_attnd");get_employees1();'
															id="firm_name_sal_attnd"
															class="form-control m-select2 m-select2-general">
														<option value="">Select Office</option>
													</select>

												</div><span class="required" id="firm_name_sal_attnd_error"
															style="color:red"></span>

											<?php } else { ?>
												<input type="hidden" name="firm_name_sal_attnd" id="firm_name_sal_attnd"
													   value="<?php echo $firm_name_sal; ?>">
											<?php } ?>
										</div>

										<div class="col-md-4">
											<label class="">Select Employee</label>
											<div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i></span>
												<select name="select_employee" id="select_employee"
														onchange="remove_error('select_employee');check_leave_data();check_attendance_emp_exist();"
														class="form-control m-select2 m-select2-general">
													<option value="">Select Employee</option>
												</select>
											</div>
											<span class="required" id="select_employee_error" style="color:red"></span>
										</div>


										<div class="col-md-12">
											<div class="input-group">
												<div class="icheck-inline">
													<!--<label>Status</label>-->
													<label id="c">
														<input type="radio" id="attendance_employee"
															   name="attendance_employee" value="1" checked
															   data-checkbox="icheckbox_flat-grey"
															   onclick="applicable_change('applicable_yes')"> Punching
														Applicability Yes </label>
													<label id="cc">
														<input type="radio" id="attendance_employee"
															   name="attendance_employee" value="2"
															   data-checkbox="icheckbox_flat-grey"
															   onclick="applicable_change('applicable_no')"> Punching
														Applicability No </label>
													<!--                                                    <label id="ccc">
                                                                                                            <input type="radio" id="attendance_employee" name="attendance_employee" value="3"  data-checkbox="icheckbox_flat-grey" onclick="applicable_change('leave_applicable_yes')"> Punch In Not Applicable & Leave Request </label>-->
													<!--                                                    <label id="cccc">
                                                                                                            <input type="radio" id="attendance_employee" name="attendance_employee" value="4"  data-checkbox="icheckbox_flat-grey" onclick="applicable_change('outside_applicable_yes')"> Outside Punch Applicable </label>
                                                                                                        <label id="cccc">
                                                                                                            <input type="radio" id="attendance_employee" name="attendance_employee" value="5"  data-checkbox="icheckbox_flat-grey" onclick="applicable_change('shift_applicable_yes')"> Shift Applicable </label>-->


												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12" id="applicable_yes" style="display: block;">

											<!--Outside punch applicable div--->

											<div class="row">
												<div class="col-md-3">

													<label><b>Outside Punching Applicability</b></label><br>

													<select name="outside_punch_applicable"
															id="outside_punch_applicable"
															onchange="remove_error('outside_punch_applicable');"
															class="form-control m-select2">
														<option value="">Select</option>
														<option value="1">Yes</option>
														<option value="2">No</option>
													</select>
													<span class="required" style="color: red"
														  id="outside_punch_applicable_error"></span> <br>
												</div>

												<div class="col-md-3">

													<label><b>Shift Applicability</b></label><br>

													<select name="shift_applicable" id="shift_applicable"
															onchange="remove_error('shift_applicable');"
															class="form-control m-select2">
														<option value="">Select</option>
														<option value="1">Yes</option>
														<option value="2">No</option>
													</select>


													<span class="required" style="color: red"
														  id="shift_applicable_error"></span> <br>
												</div>
											</div>

											<!--                                            <div class="col-md-3">

                                                                                            <label><b>Fix In time Applicable:</b></label><br>
                                                                                            <div class="input-group">
                                                                                                <label id="d">
                                                                                                    <input type="radio" id="work_hour_employee" name="work_hour_employee" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="work_hour_change('static_yes')">  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                <label id="dd">
                                                                                                    <input type="radio" id="work_hour_employee" name="work_hour_employee" value="2" data-checkbox="icheckbox_flat-grey" onclick="work_hour_change('variable_yes')">  No </label>

                                                                                            </div>
                                                                                            <span class="required" style="color: red" id="work_hour_employee_error"></span> <br>
                                                                                        </div>

                                                                                        <div id="static_yes">
                                                                                            <div class="col-md-3">
                                                                                                <label>Fixed Time</label>
                                                                                                <div class="input-group">
                                                                                                    <span class="input-group-addon">
                                                                                                        <i class="fa fa-calendar"></i>
                                                                                                    </span>
                                                                                                    <input type="text" name="fixed_time" MaxLength="8" onchange="remove_error('fixed_time')" onkeypress="formatTime(this)"  id="fixed_time" ata-required="1" class="work_fix_time_check form-control" placeholder="HH:MM:SS">

                                                                                                </div> <span class="required" style="color: red" id="fixed_time_error"></span>

                                                                                            </div>

                                                                                            <span class="required" style="color: red" id="training_period_second_error"></span>

                                                                                        </div>-->
											<div class="row">
												<div class="col-md-12" id="applicable_yes" style="display: block;">
													<!--                                                    <div class="row col-md-12">

                                                                                                            <label><b>Work Schedule:</b></label><br>
                                                                                                            <div class="input-group">
                                                                                                                <label id="d">
                                                                                                                    <input type="radio" id="work_hour_schedule" name="work_hour_schedule" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="work_schedule_change('fix_hour_yes')">  Fix Hour </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                <label id="dd">
                                                                                                                    <input type="radio" id="work_hour_schedule" name="work_hour_schedule" value="2" data-checkbox="icheckbox_flat-grey" onclick="work_schedule_change('variable_hour_yes')">  Variable Hour </label>

                                                                                                            </div>
                                                                                                            <span class="required" style="color: red" id="work_hour_employee_error"></span> <br>
                                                                                                        </div>-->

													<!--                                                    <div id="fix_hour_yes">
                                                                                                            <div class="col-md-12">

                                                                                                                <div class="col-md-3">
                                                                                                                    <label>Fixed Hour</label>
                                                                                                                    <div class="input-group">
                                                                                                                        <span class="input-group-addon">
                                                                                                                            <i class="fa fa-calendar"></i>
                                                                                                                        </span>
                                                                                                                        <input type="text" name="fixed_hour" MaxLength="8" onchange="remove_error('fixed_hour')" onkeypress="formatTime(this)" id="fixed_hour" ata-required="1" class="fix_hour_check form-control" placeholder="HH:MM:SS">
                                                                                                                        <span class="required" style="color: red" id="fixed_hour_error"></span>
                                                                                                                    </div>
                                                                                                                </div><div class="col-md-3"></div> <div class="col-md-3"></div> <div class="col-md-3"></div>
                                                                                                            </div>

                                                                                                            <span class="required" style="color: red" id="training_period_second_error"></span>

                                                                                                            -Fix In time applicable section
                                                                                                            <hr><div class="row col-md-12">

                                                                                                                <label><b>Fix In time Applicable:</b></label><br>
                                                                                                                <div class="input-group">
                                                                                                                    <label id="d">
                                                                                                                        <input type="radio" id="work_hour_employee" name="work_hour_employee" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="work_hour_change('static_yes')">  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                    <label id="dd">
                                                                                                                        <input type="radio" id="work_hour_employee" name="work_hour_employee" value="2" data-checkbox="icheckbox_flat-grey" onclick="work_hour_change('variable_yes')">  No </label>

                                                                                                                </div>
                                                                                                                <span class="required" style="color: red" id="work_hour_employee_error"></span> <br>
                                                                                                            </div>

                                                                                                            <div id="static_yes">
                                                                                                                <div class="col-md-3">
                                                                                                                    <label>Fixed Time</label>
                                                                                                                    <div class="input-group">
                                                                                                                        <span class="input-group-addon">
                                                                                                                            <i class="fa fa-calendar"></i>
                                                                                                                        </span>
                                                                                                                        <input type="text" name="fixed_time" MaxLength="8" onchange="remove_error('fixed_time')" onkeypress="formatTime(this)"  id="fixed_time" ata-required="1" class="work_fix_time_check form-control" placeholder="HH:MM:SS">

                                                                                                                    </div> <span class="required" style="color: red" id="fixed_time_error"></span>

                                                                                                                </div>

                                                                                                                <span class="required" style="color: red" id="training_period_second_error"></span>

                                                                                                            </div>


                                                                                                        </div>-->
													<div class="row col-md-12">

														<label><b>Working Hours:</b></label><br>
														<input type="hidden" name="work_hour_schedule"
															   id="work_hour_schedule" value="2">
														<div id="variable_hour_yes">
															<!--monday-->
															<div class="col-md-3">
																<label><b>Days</b></label></div>
															<div class="col-md-3">
																<label><b>Type</b></label></div>
															<div class="col-md-2">
																<label><b>Fixed Hour</b></label></div>
															<div class="col-md-2">
																<label><b>Fix In time Applicable</b></label></div>
															<?php
															$arr_day = array(
																	"day0" => "Monday",
																	"day1" => "Tuesday",
																	"day2" => "Wednesday",
																	"day3" => "Thursday",
																	"day4" => "Friday",
																	"day5" => "Saturday",
																	"day6" => "Sunday",);
															$cnt = 1;

															for ($i = 0; $i < 7; $i++) {
																?>
																<div class="row col-md-12">
																	<div class="col-md-2">
																		<!--<label>Week Day</label>-->

																		<div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-user"></i></span>
																			<input type="text"
																				   name="week_days<?php echo $cnt; ?>"
																				   value="<?php echo $arr_day['day' . $i] ?>"
																				   id="week_days<?php echo $cnt; ?>"
																				   class="form-control">
																		</div>
																		<span class="required" style="color: red"
																			  id="week_days_error"></span>
																	</div>
																	<div class="col-md-3">
																		<!--<label>Type</label>-->
																		<div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
																			<select name="select_schedule_type<?php echo $cnt; ?>"
																					id="select_schedule_type<?php echo $cnt; ?>"
																					class="form-control m-select2 m-select2-general">
																				<option value=''>Select Type</option>
																				<option value="1" selected="">Working
																				</option>
																				<option value="2">Off</option>
																			</select>
																		</div>
																		<span class="required" style="color: red"
																			  id="select_schedule_type1_error"></span>
																	</div>

																	<div class="col-md-3"
																		 id="select_fixed_hour<?php echo $cnt; ?>">
																		<!--<label>Fixed Hour</label>-->
																		<div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
																			<input type="text"
																				   name="select_fixed_hour<?php echo $cnt; ?>"
																				   value="08:30:00"
																				   id="select_fixed_hour<?php echo $cnt; ?>"
																				   ata-required="1"
																				   onkeypress="formatTime(this)"
																				   class="off_fixed_hour<?php echo $cnt; ?> form-control"
																				   placeholder="HH:MM:SS">

																		</div>
																		<span class="required" style="color: red"
																			  id="select_fixed_hour1_error"></span>
																	</div>

																	<!---adding fix in time applicable new-->

																	<div class="col-md-2"
																		 id="select_in_time_applicable<?php echo $cnt; ?>">
																		<!--                                                                    <label>Fix In time Applicable:</label><br>-->
																		<div class="input-group">
																			<label id="d">
																				<input type="radio"
																					   id="fix_in_appli_sts<?php echo $cnt; ?>"
																					   name="fix_in_appli_sts<?php echo $cnt; ?>"
																					   value="1" checked
																					   data-checkbox="icheckbox_flat-grey"
																					   onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_yes<?php echo $cnt; ?>')">
																				Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
																			<label id="dd">
																				<input type="radio"
																					   id="fix_in_appli_sts<?php echo $cnt; ?>"
																					   name="fix_in_appli_sts<?php echo $cnt; ?>"
																					   value="2"
																					   data-checkbox="icheckbox_flat-grey"
																					   onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_no<?php echo $cnt; ?>')">
																				No </label>

																		</div>
																	</div>

																	<div id="intime_appli_yes">
																		<div class="col-md-2"
																			 id="inapplicable_time_div<?php echo $cnt; ?>">
																			<label>Fixed Time</label>
																			<div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </span>
																				<input type="text"
																					   name="inapplicable_time<?php echo $cnt; ?>"
																					   MaxLength="8"
																					   onchange="remove_error('inapplicable_time')"
																					   value="10:30:00"
																					   onkeypress="formatTime(this)"
																					   id="inapplicable_time<?php echo $cnt; ?>"
																					   ata-required="1"
																					   class="inappli_fix_time_check<?php echo $cnt; ?> form-control"
																					   placeholder="HH:MM:SS">

																			</div>
																			<span class="required" style="color: red"
																				  id="fixed_time_error"></span>

																		</div>

																		<span class="required" style="color: red"
																			  id="inapplicable_time<?php echo $cnt; ?>_error"></span>

																	</div>
																	<br>

																</div>

																<?php
																$cnt++;
															}
															?>


														</div>
													</div>

													<br>

													<div class="row col-md-12">

														<label><b>Late Applicable:</b></label><br>
														<div class="input-group">
															<label id="d">
																<input type="radio" id="late_applicable_sts"
																	   name="late_applicable_sts" value="1" checked
																	   data-checkbox="icheckbox_flat-grey"
																	   onclick="late_applicable_change('late_yes')"> Yes
															</label>&nbsp;&nbsp;&nbsp;&nbsp;
															<label id="dd">
																<input checked="" type="radio" id="late_applicable_sts"
																	   name="late_applicable_sts" value="0"
																	   data-checkbox="icheckbox_flat-grey"
																	   onclick="late_applicable_change('late_no')"> No
															</label>

														</div>
														<span class="required" style="color: red"
															  id="work_hour_employee_error"></span> <br>
													</div>


													<div id="late_yes" style="display: none">
														<div class="col-md-1">Every</div>
														<div class="col-md-3">
															<!--<label>Select Days Late</label>-->
															<select name="late_salary_days" id="late_salary_days"
																	onchange="remove_error('late_salary_days');"
																	class="late_salary_count1 form-control m-select2 m-select2-general">
																<option value="">Select</option>
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
																<option value="6">6</option>
																<option value="7">7</option>
																<option value="8">8</option>
																<option value="9">9</option>
																<option value="10">10</option>
															</select>

															<span class="required" style="color: red"
																  id="late_salary_days_error"></span>

														</div>
														<div class="col-md-1">Days late</div>
														<div class="col-md-3">
															<!--<label>Select type</label>-->
															<select name="late_salary_count" id="late_salary_count"
																	onchange="remove_error('late_salary_count');"
																	class="late_salary_count1 form-control m-select2 m-select2-general">
																<option value="">Select</option>
																<option value="0.5">Half day</option>
																<option value="1">1 day</option>
																<!--                                                                <option value="2">2 day</option>
                                                                                                                                <option value="3">3 day</option>-->
															</select>

															<span class="required" style="color: red"
																  id="late_salary_count_error"></span>

														</div>
														<div class="col-md-2">Salary will deduct.</div>

														<div class="row col-md-12">
															<div class="col-md-3" id="inapplicable_time_div">
																<label><b>Note:Set the minutes for daily
																		late.</b></label>
																<div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
																	<input type="text" name="late_minute_time"
																		   MaxLength="8"
																		   onchange="remove_error('late_minute_time')"
																		   value="" onkeypress="formatTime(this)"
																		   id="late_minute_time" ata-required="1"
																		   class="form-control" placeholder="HH:MM:SS">

																</div>
																<span class="required" style="color: red"
																	  id="late_minute_time_error"></span>

															</div>
														</div>

													</div>

												</div>


												<!--</div>-->

												<!--</div>-->
											</div>
										</div>

										<!--</div>-->

										<!--</div>-->

										<div class="col-md-4">
											<label>Firms</label>
											<select class="form-control" id="employee_firms" style="width: 100%!important;" multiple name="employee_firms[]">

											</select>
										</div>
									</div>
									<br>


									<div class="form-actions">
										<div class="row">
											<div class="col-md-offset-3 col-md-9">
												<a href="<?= base_url() ?>hr_show_employee"
												   class="btn grey-salsa btn-outline" style="float:right; margin: 2px;">Cancel</a>
												<button type="button" id="add_attendance" name="add_attendance"
														class="btn green" style="float:right;  margin: 2px;">Add
													Attendance
												</button>
											</div>
										</div>
									</div>
								</form>

							</div>
						</div>
						<div class="tab-pane" id="tab_5_3">
							<input type="hidden" name="user_type" id="user_type" value="<?php echo $user_type; ?>">
							<div class="form-body">
								<?php if ($user_type == 2) { ?>
									<label class="">Office Name</label>
									<div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-user"></i></span>
										<select name="firm_name_sal"
												onchange='get_employees();get_sal_type_list();get_ded_type_list();'
												id="firm_name_sal" class="form-control m-select2 m-select2-general">
											<option value="">Select office</option>
										</select>
									</div>
									<span class="required" id="firm_name_sal_error" style="color:red"></span>
								<?php } else { ?>
									<input type="hidden" name="firm_name_sal" id="firm_name_sal"
										   value="<?php echo $firm_name_sal; ?>">
								<?php } ?>
								<br>
								<div class="tabbable-custom ">
									<ul class="nav nav-tabs ">
										<li class="active">
											<a href="#tab_6_1" data-toggle="tab"> Performance Allowance</a>
										</li>
										<li>
											<a href="#tab_6_2" data-toggle="tab" id="tab_6_2_loan">Staff Loan </a>
										</li>
										<li>
											<a href="#tab_6_3" data-toggle="tab" id="tab_6_3_salary"
											   onchange="get_sal_type_list()"> Salary Details </a>
										</li>
										<li>
											<a href="#tab_6_4" data-toggle="tab" id="tab_6_4_deduction"> Deductions
												Details </a>
										</li>

									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_6_1">
											<div class="form-body">
												<form id="performance_allowance" name="performance_allowance"
													  method="POST">
													<!--<input type="hidden" name="emp_id" id="emp_id" value="">-->
													<input type="hidden" name="emp_firm" id="emp_firm" value="">
													<label>Select Employee</label>
													<div class="form-group">


														<div class="input-group col-md-5">

															<select name="emp_id" id="emp_id"
																	class="form-control m-select2 m-select2-general">
																<option value="">Select Employee</option>
															</select>
														</div>


														<span class="required" id="emp_id_error"
															  style="color:red"></span>

													</div>

													<div class="form-group">
														<label class="control-label">Performance Bonus</label>
														<input type="number" min="1"
															   oninput="validity.valid||(value='');"
															   class="form-control"
															   placeholder="Enter Performance  Bonus" id="value"
															   name="value">
													</div>
													<span class="required" id="value_error" style="color:red"></span>


													<div class="">
														<label>Start Month </label>
														<div class="input-group col-md-12">

															<select name="Month" id="Month"
																	class="form-control m-select2 m-select2-general"
																	oninput="remove_error('leave_apply_permission')"
																	onchange="check_prob_train_date();">
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
															</select></div>
														<span class="required" id="leave_apply_permission_error"
															  style="color:red"></span>
													</div>

													<br>

													<span class="required" id="Month_error" style="color:red"></span>
													<div class="form-group">
														<label>Financial Year</label>
														<!--<select id="years" name="years" class="form-control" ></select>-->
														<!--<input type="text" id="Fyear" name="Fyear" class="form-control">-->
														<select name="Fyear" id="Fyear"
																class="form-control select2 select2-hidden-accessible"></select>
													</div>
													<div class="form-group">
														<label>Date</label> <input type="Date" class="form-control"
																				   placeholder="Enter Date "
																				   id="Date_of_PA" name="Date_of_PA">
													</div>
													<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
													<button type="submit" class="btn btn-primary"
															name="add_emp_performance" id="add_emp_performance">Save
														changes
													</button>

													<div id="data_tablediv"></div>
												</form>
											</div>
										</div>

										<div class="tab-pane" id="tab_6_2">
											<div class="form-body">
												<form id="staff_loan" name="staff_loan" method="POST">
													<!--<input type="hidden" name="emp_id1" id="emp_id1" value="">-->
													<input type="hidden" name="firm_id_a1" id="firm_id_a1" value="">
													<div class="form-group ">
														<div class="">
															<label class="">Select Employee</label>
															<div class="input-group col-md-5">
																<!--<span class="input-group-addon">
                                                                    <i class="fa fa-user"></i></span>-->
																<select name="emp_id1" id="emp_id1"
																		class="form-control m-select2 m-select2-general">
																	<option value="">Select Employee</option>
																</select>
															</div>
															<span class="required" id="emp_id1_error"></span>
														</div>
													</div>
													<div class="form-group">
														<label>Loan Details</label>
														<input type="text" class="form-control"
															   placeholder="Enter Loan Details" id="loan_detail"
															   name="loan_detail">
													</div>
													<div class="form-group">
														<label>Loan Amount</label>
														<input type="number" min="1" oninput="validateLoanNumber(this);"
															   class="form-control" placeholder="Enter Loan Amount"
															   id="amount" name="amount">
													</div>
													<div class="form-group">
														<label>EMI Amount</label>
														<input type="number" min="1" oninput="validateEMINumber(this);"
															   oninput="validity.valid||(value='');"
															   class="form-control" placeholder="EnterEMI Amount"
															   id="EMI_amt" name="EMI_amt">
													</div>
													<div class="form-group">
														<label>Start Month</label>
														<select name="from_month" id="from_month"
																class="form-control select2 select2-hidden-accessible">
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
														<select name="Fyear2" id="Fyear2"
																class="form-control select2 select2-hidden-accessible"></select>
													</div>
													<div class="form-group">
														<label class="control-label">Total Month</label>
														<input type="number" min="1"
															   oninput="validity.valid||(value='');"
															   class="form-control" placeholder="Enter Total Month"
															   id="total_month" name="total_month">
													</div>
													<div class="form-group">
														<label>Sanction Date</label>
														<input type="Date" class="form-control"
															   placeholder="Enter Sanction Date" id="sanction_date"
															   name="sanction_date">
													</div>
													<button type="button" class="btn btn-secondary"
															data-dismiss="modal">Close
													</button>
													<button type="submit" class="btn btn-primary" id="save">Save
														changes
													</button>
													<button type="submit" class="btn btn-primary" id="update"
															style="display: none" onclick="update_loan()">Update changes
													</button>
													<!--<button type="button" class="btn btn-warning" onclick="get_data_staffloanhistory()">Show History</button>-->
													<div id="data_staffloanhistory"></div>
												</form>

											</div>

										</div>

										<div class="tab-pane" id="tab_6_3">
											<div class="form-body">
												<form id="salary_details" name="salary_details" method="POST">
													<!--<input type="hidden" name="emp_id2" id="emp_id2" value="">-->
													<!--<input type="hidden" name="firm_id_a2" id="firm_id_a2" value="">-->
													<div class="form-group ">
														<div class="">
															<label class="">Select Employee</label>
															<div class="input-group col-md-5">
																<!--<span class="input-group-addon">
                                                                    <i class="fa fa-user"></i></span>-->
																<select name="emp_id2" id="emp_id2"
																		class="form-control m-select2 m-select2-general">
																	<option value="">Select Employee</option>
																</select>
															</div>
														</div>

													</div>
													<span class="required" id="emp_id2_error"></span>

													<div class="form-group">
														<label>Type</label>
														<select name="sal_options" id="sal_options"
																class="form-control">
														</select>
														<span class="required" id="sal_options_error"
															  style="color:red"></span>
													</div>


													<!--                                                    <div class="form-group">
                                                                                                <label for="multiple" class="control-label">Select2 multi select</label>
                                                                                                <select id="multiple" class="form-control select2-multiple select2-hidden-accessible" multiple="" tabindex="-1" aria-hidden="true">
                                                                                                    <optgroup label="Alaskan">
                                                                                                        <option value="AK">Alaska</option>
                                                                                                        <option value="HI" disabled="disabled">Hawaii</option>
                                                                                                    </optgroup>
                                                                                                    <optgroup label="Pacific Time Zone">
                                                                                                        <option value="CA">California</option>
                                                                                                        <option value="NV">Nevada</option>
                                                                                                        <option value="OR">Oregon</option>
                                                                                                        <option value="WA">Washington</option>
                                                                                                    </optgroup>
                                                                                                </select><span class="select2 select2-container select2-container--bootstrap select2-container--below" dir="ltr"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-selection__choice" title="Nevada"><span class="select2-selection__choice__remove" role="presentation">×</span>Nevada</li><li class="select2-selection__choice" title="Oregon"><span class="select2-selection__choice__remove" role="presentation">×</span>Oregon</li><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                                                                            </div>-->


													<div class="form-group">
														<label> Amount</label>
														<input type="number" min="1" oninput="validateNumber(this);"
															   class="form-control" placeholder="Enter  Amount"
															   id="value" name="value">
														<span class="required" id="value_sal_error"
															  style="color:red"></span>
													</div>
													<button type="button" class="btn btn-secondary"
															data-dismiss="modal">Close
													</button>
													<button type="submit" class="btn btn-primary">Save changes</button>
													<!--<button type="button" class="btn btn-warning" onclick="get_view_salary_details()">Show History</button>-->
													<div id="data_salry_Details"></div>
												</form>

											</div>

										</div>

										<div class="tab-pane" id="tab_6_4">
											<div class="form-body">
												<form id="due_details" name="due_details" method="POST">

													<!--<input type="hidden" name="emp_id3" id="emp_id3" value="">-->
													<!--<input type="hidden" name="firm_id_a3" id="firm_id_a3" value="">-->
													<div class="form-group ">
														<div class="">
															<label class="">Select Employee</label>
															<div class="input-group col-md-5">
																<!--  <span class="input-group-addon">
                                                                    <i class="fa fa-user"></i></span>-->
																<select name="emp_id3" id="emp_id3"
																		onchange="remove_error('emp_id3')"
																		class="form-control m-select2 m-select2-general">
																	<option value="">Select Employee</option>
																</select>
															</div>
															<span class="required" id="emp_id3_error"></span>
														</div>
													</div>
													<div class="form-group">
														<label>Taxs</label>
														<select name="ded_options" id="ded_options"
																onchange="remove_error('ded_options')"
																class="form-control">
															<option></option>
														</select>
														<span class="required" id="ded_options_error"
															  style="color:red"></span>
													</div>

													<div class="form-group">
														<label><b>Select type:</b></label><br>
														<div class="input-group">
															<label id="ded">
																<input type="radio" id="deduction_check"
																	   name="deduction_check" value="1" checked
																	   data-checkbox="icheckbox_flat-grey"
																	   onclick="deduction_type_change('fix_deduct_amount_yes')">
																Fix </label>&nbsp;&nbsp;&nbsp;&nbsp;
															<label id="ded">
																<input type="radio" id="deduction_check"
																	   name="deduction_check" value="2"
																	   data-checkbox="icheckbox_flat-grey"
																	   onclick="deduction_type_change('deduct_percentage_yes')">
																Percentage </label>

														</div>
														<span class="required" style="color: red"
															  id="deduction_check_error"></span> <br>

													</div>

													<div class="form-group" id="fix_deduct_amount_yes"
														 style="display: block">
														<label> Amount</label>
														<input type="number" min="1"
															   oninput="validateNumberDeduct(this);"
															   class="form-control" placeholder="Enter  Amount"
															   onkeypress="remove_error('value_ded')" id="value_ded"
															   name="value">
														<span class="required" id="value_ded_error"
															  style="color:red"></span>
													</div>

													<div class="form-group" id="deduct_percentage_yes"
														 style="display: none">
														<label> Percentage</label>
														<input type="number" class="form-control"
															   placeholder="Enter  Percentage Value"
															   onkeypress="remove_error('value_deduct_percentage1')"
															   id="value_deduct_percentage1"
															   name="value_deduct_percentage">
														<span class="required" id="value_deduct_percentage1_error"
															  style="color:red"></span>
													</div>

													<button type="button" class="btn btn-secondary"
															data-dismiss="modal">Close
													</button>
													<button type="button" class="btn btn-primary" id="add_duedetails">
														Save changes
													</button>
													<!--<button type="button" class="btn btn-warning" onclick="get_view_Deu()">Show History</button>-->

													<div id="show_due_details"></div>
												</form>

											</div>
										</div>

									</div>


								</div>

							</div>
						</div>
						<div class="tab-pane" id="tab_5_4">
							<div class="form-body">
								<!--                                <form method="POST" id="emp_exit_form" name="emp_exit_form" class="form-horizontal" novalidate="novalidate">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <div class="col-md-4">
                                                                                <label>Reason For Exit</label>
                                                                                <input class="form-control" type="text" width="50" height="50" placeholder="Please enter reason"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-md-4">
                                                                                <label>Last Day of working</label>
                                                                                <input class="form-control" type="date" name="exit_date" id="exit_date"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary" >Save</button>
                                                                </form>-->
							</div>

						</div>
						<div class="tab-pane" id="tab_5_5">
							<div class="form-body">


								<form method="POST" id="leaveconfigure_emp" name="leaveconfigure_emp"
									  class="form-horizontal" novalidate="novalidate">
									<input type="hidden" name="user_type" id="user_type"
										   value="<?php echo $user_type; ?>">


									<div class="row">
										<?php if ($user_type == 2) { ?>
											<div class="col-md-4">
												<label class="">Office Name</label>
												<div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i></span>
													<select name="firm_name_leave" id="firm_name_leave"
															onchange="get_emp();get_leavedetails();"
															class="form-control m-select2 m-select2-general">
														<option value="">Select office</option>
													</select>
												</div>
												<span class="required" id="firm_name_leave_error"></span>
											</div>

										<?php } else { ?>
											<input type="hidden" value="" id="firm_name_leave" name="firm_name_leave">
										<?php } ?>
										<div class="col-md-2">
											<label class="">Select Employee</label>
											<div class="input-group col-md-12">
                                                <span class="input-form-addon">

                                                <select name="emp_id_leave" id="emp_id_leave"
														onchange="get_leavedetails1(); remove_error('emp_id_leave')"
														class="form-control m-select2 m-select2-general">
                                                    <option value="">Select Employee</option>
                                                </select><span class="required" id="emp_id_leave_error"
															   style="color:red"></span>
											</div>

										</div>
										<div class="col-md-2"><label>Total Leaves :</label>

											<input type="text" class="form-control" id="total_leaves"
												   name="total_leaves" onkeyup="remove_error('total_leaves')">
											<span class="required" id="total_leaves_error"></span>

										</div>
										<div class="col-md-2"><label>Accrued Periodically :</label><br>

											<label class="mtradio"><input type="radio" value="1" id="acc_per0"
																		  name="acc_per" onclick="get_data(1)">Yes
											</label>&nbsp; <label class="mtradio ">
												<input type="radio" value="0" id="acc_per1" checked name="acc_per"
													   onclick="get_data(2)">No</label></div>

										<div id="p_div" style="display:none">
											<div class="col-md-2"><label>Select Period :</label>
												<select id="accrued_period" name="accrued_period" class="form-control">
													<option value="0">Select period</option>
													<option value="5">Weekly</option>
													<option value="1">Monthly</option>
<!--													<option value="2">Quarterly</option>-->
<!--													<option value="4">Yearly</option>-->
												</select>
											</div>
											<div class="col-md-2"><label>Leave should be accured From? :</label>
												<select id="accrued_type" name="accrued_type" class="form-control">
													<option value="0">Select Type</option>
													<option value="1">PL</option>
													<option value="2">CL</option>
													<option value="3">SL</option>
												</select>
											</div>
											<div class="col-md-2"><label>How many leave should accured?</label>
												<input type="text" class="form-control" id="accr_leave"
													   name="accr_leave" onkeyup="remove_error('total_leaves')">
											</div>
										</div>
									</div>
									<br><br>


									<div class="form-group">

										<div class="row">
											<div class="form-group">
												<div class="col-md-2"></div>
												<div class="col-md-2"><span style="font-weight:bold;">Types </span>
												</div>
												<div class="col-md-2"><span
															style="font-weight:bold;">No. of days </span></div>
												<div class="col-md-2"><span
															style="font-weight:bold;">Carry Forward </span></div>
												<!--                                        <div class="col-md-2"><span style="font-weight:bold;">Leave Request Before(Days) </span></div>
                                                <div class="col-md-2"><span style="font-weight:bold;">Leave Approve</span></div>-->
												<!--<div class="col-md-2"><span style="font-weight:bold;">Days past/next</span></div>-->
											</div>
											<br>
											<div class="row">
												<div class="col-md-1"></div>
												<div class="col-md-1"><span style="font-weigth:bold;">Type 1:</span>
												</div>
												<div class="col-md-2"><input type="text" readonly id="leave_type1"
																			 name="leave_type1"
																			 onkeyup="remove_error('leave1')"
																			 class="form-control"
																			 placeholder="First Leave Type"></div>
												<div class="col-md-2"><input type="number" min="1" id="numofdays1"
																			 name="numofdays1"
																			 onkeyup="remove_error('leave1')"
																			 class="form-control"
																			 placeholder="No Of Days">
													<span class="required" id="numofdays1_error"></span></div>
												<div class="col-md-1"><label class="mtradio"><input type="radio"
																									value="1" checked
																									id="CF1" name="CF1"
																									onkeyup="remove_error('CF1')">Yes
													</label>&nbsp;
													<label class="mtradio "><input type="radio" value="0" id="CF1"
																				   name="CF1"
																				   onkeyup="remove_error('CF1')">No</label>
													<span class="required" id="CF1_error">
												</div>
												<div class="col-md-1" id="leave1" name="leave1"></div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-1"></div>
												<div class="col-md-1"><span style="font-weigth:bold;">Type 2:</span>
												</div>
												<div class="col-md-2"><input type="text" id="leave_type2" readonly
																			 name="leave_type2"
																			 onkeyup="remove_error('leave_type2')"
																			 class="form-control"
																			 placeholder="First Leave Type"></div>
												<div class="col-md-2"><input type="number" min="1" id="numofdays2"
																			 name="numofdays2"
																			 onkeyup="remove_error('numofdays2')"
																			 class="form-control"
																			 placeholder="No Of Days">
													<span class="required" id="numofdays2_error"></span>
												</div>
												<div class="col-md-1"><label class="mtradio"><input type="radio"
																									value="1" id="CF2"
																									checked name="CF2"
																									onkeyup="remove_error('CF2')">Yes
													</label>&nbsp;
													<label class="mtradio "><input type="radio" value="0" id="CF2"
																				   name="CF2"
																				   onkeyup="remove_error('CF2')">No</label>
													<span class="required" id="CF2_error">
												</div>
												<div class="col-md-1" id="leave1" name="leave1"></div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-1"></div>
												<div class="col-md-1"><span style="font-weigth:bold;">Type 3:</span>
												</div>
												<div class="col-md-2"><input type="text" id="leave_type3" readonly
																			 name="leave_type3"
																			 onkeyup="remove_error('leave_type3')"
																			 class="form-control"
																			 placeholder="First Leave Type">
													<span class="required" id="leave_type3_error"></span>
												</div>
												<div class="col-md-2"><input type="number" min="1" id="numofdays3"
																			 name="numofdays3"
																			 onkeyup="remove_error('numofdays3')"
																			 class="form-control"
																			 placeholder="No Of Days">
													<span class="required" id="numofdays3_error"></span>
												</div>
												<div class="col-md-1"><label class="mtradio"><input type="radio"
																									value="1" checked
																									id="CF3" name="CF3"
																									name="3"
																									onkeyup="remove_error('CF3')">Yes
													</label>&nbsp; <label class="mtradio "><input type="radio" value="0"
																								  id="CF3" name="CF3"
																								  onkeyup="remove_error('CF3')">No</label>
													<span class="required" id="CF3_error"></div>
												<div class="col-md-1" id="leave1" name="leave1"></div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-1"></div>
												<div class="col-md-1"><span style="font-weigth:bold;">Type 4:</span>
												</div>
												<div class="col-md-2"><input type="text" id="leave_type4"
																			 name="leave_type4"
																			 onkeyup="remove_error('leave_type4')"
																			 class="form-control"
																			 placeholder="First Leave Type">
													<span class="required" id="leave_type4_error"></span>
												</div>

												<div class="col-md-2"><input type="number" min="1" id="numofdays4"
																			 name="numofdays4"
																			 onkeyup="remove_error('numofdays4')"
																			 class="form-control"
																			 placeholder="No Of Days">
													<span class="required" id="numofdays4_error"></span>
												</div>
												<div class="col-md-1"><label class="mtradio"><input type="radio"
																									value="1" id="CF4"
																									checked name="CF4"
																									onkeyup="remove_error('CF4')">Yes
													</label>&nbsp;
													<label class="mtradio "><input type="radio" value="0" id="CF4"
																				   name="CF4"
																				   onkeyup="remove_error('CF4')">No</label>
													<span class="required" id="CF4_error">
												</div>
												<div class="col-md-1" id=""></div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-1"></div>
												<div class="col-md-1"><span style="font-weigth:bold;">Type 5:</span>
												</div>
												<div class="col-md-2"><input type="text" id="leave_type5"
																			 name="leave_type5"
																			 onkeyup="remove_error('leave_type5')"
																			 class="form-control"
																			 placeholder="First Leave Type">
													<span class="required" id="leave_type5_error"></span>
												</div>
												<div class="col-md-2"><input type="number" min="1" id="numofdays5"
																			 name="numofdays5"
																			 onkeyup="remove_error('numofday5')"
																			 class="form-control"
																			 placeholder="No Of Days">
													<span class="required" id="numofdays5_error"></span>
												</div>
												<div class="col-md-1"><label class="mtradio"><input type="radio"
																									value="1" checked
																									id="CF5" name="CF5"
																									onkeyup="remove_error('CF5')">Yes
													</label>&nbsp;
													<label class="mtradio "><input type="radio" value="0" id="CF5"
																				   name="CF5"
																				   onkeyup="remove_error('CF5')">No</label>
													<span class="required" id="CF5_error"></div>
												<div class="col-md-1" id="leave1" name="leave1"></div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-1"></div>
												<div class="col-md-1"><span style="font-weigth:bold;">Type 6:</span>
												</div>
												<div class="col-md-2"><input type="text" id="leave_type6"
																			 name="leave_type6"
																			 onkeyup="remove_error('leave_type6')"
																			 class="form-control"
																			 placeholder="First Leave Type">
													<span class="required" id="leave_type6_error"></span>
												</div>
												<div class="col-md-2"><input type="number" min="1" id="numofdays6"
																			 checked name="numofdays6"
																			 onkeyup="remove_error('numofdays6')"
																			 class="form-control"
																			 placeholder="No Of Days">
													<span class="required" id="numofdays6_error"></span>
												</div>
												<div class="col-md-1"><label class="mtradio"><input type="radio"
																									value="1" checked
																									id="CF6" name="CF6"
																									onkeyup="remove_error('CF6')">Yes
													</label>&nbsp; <label class="mtradio ">
														<input type="radio" value="0" id="CF6" name="CF6"
															   onkeyup="remove_error('CF6')">No</label></div>
												<div class="col-md-1" id="leave1" name="leave1"><span class="required"
																									  id="CF6_error"></span>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-1"></div>
												<div class="col-md-1"><span style="font-weigth:bold;">Type 7:</span>
												</div>
												<div class="col-md-2"><input type="text" id="leave_type7"
																			 name="leave_type7"
																			 onkeyup="remove_error('leave_type7')"
																			 class="form-control"
																			 placeholder="First Leave Type">
													<span class="required" id="leave_type7_error"></span>
												</div>
												<div class="col-md-2"><input type="number" min="1" id="numofdays7"
																			 name="numofdays7"
																			 onkeyup="remove_error('leave_type7')"
																			 class="form-control"
																			 placeholder="No Of Days">
													<span class="required" id="numofdays7_error"></span>
												</div>
												<div class="col-md-1"><label class="mtradio"><input type="radio"
																									value="1" id="CF7"
																									checked name="CF7"
																									onkeyup="remove_error('CF7')">Yes
													</label>&nbsp; <label class="mtradio "><input type="radio" value="0"
																								  id="CF7" name="CF7"
																								  onkeyup="remove_error('CF7')">No</label>
													<span class="required" id="CF7_error"></div>
												<div class="col-md-1" id="leave1" name="leave1"><span class="required"
																									  id="leave_type7_error"></span>
												</div>
												<b> <span class="required" id="total_error"></span></b>

											</div>

										</div>

									</div>
									<button type="button" class="btn btn-primary" onclick="add_levae_emp()">Save
										Changes
									</button>
								</form>
							</div>
						</div>
					</div>


					<!--ATTENDANCE EMPLOYEE-->
					<!--<div class="row">-->


					<div class="row"></div>
					<br><br>


					<div class="loading" id="loaders7" style="display:none;"></div>
					</form>
				</div>
				<?php $this->load->view('human_resource/footer'); ?>
			</div>

		</div>
	</div>
	<!-- END FORM-->
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

	<script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
	<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js"
			type="text/javascript"></script>
	<!-- BEGIN THEME GLOBAL SCRIPTS -->
	<!--<script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>-->
	<!-- END THEME GLOBAL SCRIPTS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="<?php echo base_url() . "assets/"; ?>pages/scripts/components-select2.min.js"
			type="text/javascript"></script>
	<script>
		function display_email() {
			var id = $("#aa_ans").val();

			if (id == 1) {
				$("#rmt_mail_div").hide();
			} else {
				$("#rmt_mail_div").show();
				get_rmt_email_list();
			}
		}

		function get_rmt_emp_info() {
			var id = $("#rmt_mail").val();
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/get_rmt_emp_info") ?>",
				dataType: "json",
				data: {id},
				success: function (result) {
					if (result['status'] === 200) {
						var data = result['body'];
						var data1 = data[0];

						var date_of_joining = data[0].date_of_joining.split(" ")[0]; // Extract 'YYYY-MM-DD'
						$("#date_of_joining").val(date_of_joining);

						var prob_date_first = data[0].probation_period_start_date.split(" ")[0]; // Extract 'YYYY-MM-DD'
						$("#prob_date_first").val(prob_date_first);

						var prob_date_second = data[0].probation_period_end_date.split(" ")[0]; // Extract 'YYYY-MM-DD'
						$("#prob_date_second").val(prob_date_second);

						var training_period_first = data[0].training_period_start_date.split(" ")[0]; // Extract 'YYYY-MM-DD'
						$("#training_period_first").val(training_period_first);

						var training_period_second = data[0].training_period_end_date.split(" ")[0]; // Extract 'YYYY-MM-DD'
						$("#training_period_second").val(training_period_second);

						$("#user_name").val(data[0].user_name);
						$("#mobile_no").val(data[0].mobile_no);
						$("#address").val(data[0].address);
						$("#Paddress").val(data[0].address);
						$("#country").val(data[0].country);
						$("#state").val(data[0].state);
						$("#city").val(data[0].city);
						
						// $("#date_of_joining").val(data[0].date_of_joining);
						// $("#prob_date_first").val(data[0].probation_period_start_date);
						// $("#prob_date_second").val(data[0].probation_period_end_date);
						// $("#training_period_first").val(data[0].training_period_start_date);
						// $("#training_period_second").val(data[0].training_period_end_date);
					
						console.log(data[0].user_name);
						
					} else {

					}
				},
				error: function (result) {
					console.log(result);
					if (result.status === 500) {
						alert('Internal error: ' + result.responseText);
					} else {
						alert('Unexpected error.2');
					}
				}

			});
		}

		function get_rmt_email_list() {
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/get_rmt_email_list") ?>",
				dataType: "json",

				success: function (result) {
					if (result['status'] === 200) {
						$("#rmt_mail").html(result['body']);
					} else {
						$("#rmt_mail").html(result['body']);
					}
				},
				error: function (result) {
					console.log(result);
					if (result.status === 500) {
						alert('Internal error: ' + result.responseText);
					} else {
						alert('Unexpected error.2');
					}
				}

			});
		}
	</script>
	<script>


		function chec() {
			var dd1 = document.getElementById('firm_name_sal');
			if (
					(dd1.selectedIndex == 0)
			) {
				var errName = $("#firm_name_sal_error"); //Element selector
				errName.html("Select Office");
				//firm_name_sal_error”).html(“Select Office”);
			}
		}

		$('#firm_name_sal').change(function () {
			$("#firm_name_sal_error").hide();
		});


		function chec1() {
			var dd1 = document.getElementById('firm_name_leave');
			if (
					(dd1.selectedIndex == 0)
			) {
				var errName = $("#firm_name_leave_error"); //Element selector
				errName.html("Select Office");
				//firm_name_sal_error”).html(“Select Office”);
			}
		}

		$('#firm_name_leave').change(function () {
			$("#firm_name_leave_error").hide();
		});
		$("#ot_applicable_sts").change(function () {
			if (this.value == '1')
//                                            alert("1");
			{
				$("#salary_calcu2").prop("checked", true);
				$("#salary_calcu1").prop("disabled", true);
			} else {
//                                                 alert("2");
				$("#salary_calculation").val('');
				$("#salary_calcu1").prop("disabled", false);
				$("#salary_calcu2").prop("disabled", false);
			}

		});

		var validNumber = new RegExp(/^\d*\.?\d*$/);
		var lastValid = document.getElementById("value").value;

		function validateNumber(elem) {
			if (validNumber.test(elem.value)) {
				lastValid = elem.value;
			} else {
				elem.value = lastValid;
			}
		}

		var validNumber = new RegExp(/^\d*\.?\d*$/);
		var lastValid = document.getElementById("value_ded").value;

		function validateNumberDeduct(elem) {
			if (validNumber.test(elem.value)) {
				lastValid = elem.value;
			} else {
				elem.value = lastValid;
			}
		}

		var validNumber = new RegExp(/^\d*\.?\d*$/);
		var lastValid = document.getElementById("amount").value;

		function validateLoanNumber(elem) {
			if (validNumber.test(elem.value)) {
				lastValid = elem.value;
			} else {
				elem.value = lastValid;
			}
		}

		var validNumber = new RegExp(/^\d*\.?\d*$/);
		var lastValid = document.getElementById("EMI_amt").value;

		function validateEMINumber(elem) {
			if (validNumber.test(elem.value)) {
				lastValid = elem.value;
			} else {
				elem.value = lastValid;
			}
		}


		function add_levae_emp() {
			// var firm_name = document.getElementById("firm_name_leave").value;
			var user_id = document.getElementById("emp_id_leave").value;
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/update_employees_leaves") ?>",
				dataType: "json",
				data: $("#leaveconfigure_emp").serialize() + "&user_id=" + user_id,
				success: function (result) {
					if (result['message'] === 'success') {
						alert('Employee leave updated successfully.');
						document.forms['leaveconfigure_emp'].reset();
					} else {
						document.getElementById(result['id'] + '_error').innerHTML = result['error'];
					}
				},
				error: function (result) {
					console.log(result);
					if (result.status === 500) {
						alert('Internal error: ' + result.responseText);
					} else {
						alert('Unexpected error.2');
					}
				}

			});
		}

		function get_emp() {
			var user_type = document.getElementById('user_type').value;
			var firm_id = $("#firm_name_leave").val();
			$.ajax({
				url: "<?= base_url("Employee/get_employee_list") ?>",
				dataType: "json",
				type: "POST",
				data: {firm_id: firm_id, user_type: user_type},
				success: function (result) {
					//console.log(result);
					$("#emp_id_leave").empty('');
					var ele8 = document.getElementById('emp_id_leave');
//                                                    ele8.innerHTML = ele8.innerHTML + '<option value="Select Employee">Select Employee</option>';

					if (result['message'] === 'success') {
						ele8.innerHTML = ele8.innerHTML + '<option value="">Select Employee</option>';
						var data = result['result'];


						for (i = 0; i < data.length; i++) {

							ele8.innerHTML = ele8.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
						}

					} else {
						ele8.innerHTML = ele8.innerHTML + '<option value="">No Employee</option>';
					}
				}
			});
		}


		function get_employees() {
			var user_type = document.getElementById('user_type').value;
			var firm_id = document.getElementById('firm_name_sal').value;

			console.log(firm_id);
			console.log(user_type);
			//                                                            alert(firm_id);
			//                                                            var firm_id='firm_name_sal';
			$.ajax({
				url: "<?= base_url("Employee/get_employee_list") ?>",
				dataType: "json",
				type: "POST",
				data: {firm_id: firm_id, user_type: user_type},
				success: function (result) {
					//console.log(result);
					$("#select_employee").empty('');
					$("#emp_id").empty('');
					$("#emp_id1").empty('');
					$("#emp_id2").empty('');
					$("#emp_id3").empty('');
					$("#emp_id_leave").empty('');
					var ele3 = document.getElementById('select_employee');
					var ele4 = document.getElementById('emp_id');
					var ele5 = document.getElementById('emp_id1');
					var ele6 = document.getElementById('emp_id2');
					var ele7 = document.getElementById('emp_id3');
					var ele8 = document.getElementById('emp_id_leave');

					if (result['message'] === 'success') {
						var data = result['result'];
						ele3.innerHTML = ele3.innerHTML + '<option value="">Select Employee</option>';
						ele4.innerHTML = ele4.innerHTML + '<option value="">Select Employee</option>';
						ele5.innerHTML = ele5.innerHTML + '<option value="">Select Employee</option>';
						ele6.innerHTML = ele6.innerHTML + '<option value="">Select Employee</option>';
						ele7.innerHTML = ele7.innerHTML + '<option value="">Select Employee</option>';
						ele8.innerHTML = ele8.innerHTML + '<option value="">Select Employee</option>';
						for (i = 0; i < data.length; i++) {

							ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							//
						}
						for (i = 0; i < data.length; i++) {

							ele4.innerHTML = ele4.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
						}
						for (i = 0; i < data.length; i++) {

							ele5.innerHTML = ele5.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
						}
						for (i = 0; i < data.length; i++) {

							ele6.innerHTML = ele6.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
						}
						for (i = 0; i < data.length; i++) {

							ele7.innerHTML = ele7.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
						}
						for (i = 0; i < data.length; i++) {

							ele8.innerHTML = ele7.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
						}
					} else {
						ele3.innerHTML = ele3.innerHTML + '<option value="">No Employee</option>';
						ele4.innerHTML = ele4.innerHTML + '<option value="">No Employee</option>';
						ele5.innerHTML = ele5.innerHTML + '<option value="">No Employee</option>';
						ele6.innerHTML = ele6.innerHTML + '<option value="">No Employee</option>';
						ele7.innerHTML = ele7.innerHTML + '<option value="">No Employee</option>';
						ele8.innerHTML = ele8.innerHTML + '<option value="">No Employee</option>';
					}
				}
			});
		}

		$(document).ready(function () {
			get_sal_type_list();
			get_ded_type_list();

			$.ajax({
				url: "<?= base_url("getEmployeeFirms") ?>",
				dataType: "json",
				success: function (result) {
					if (result.status === 200) {
						var data = result.body;
						$("#employee_firms").html('');
						$("#employee_firms").html(data);
						$("#employee_firms").select2();
					}
				}
			});

		});

		$(document).ready(function () {
			var user_type = document.getElementById('user_type').value;
			//                                                            alert(user_type);
			console.log(user_type);
			if (user_type == 5) {
				$.ajax({
					url: "<?= base_url("Employee/get_employee_list") ?>",
					dataType: "json",
					type: "POST",
					data: {user_type: user_type},
					success: function (result) {
						//console.log(result);
						if (result['message'] === 'success') {
							var data = result['result'];

							var ele3 = document.getElementById('select_employee');
							var ele4 = document.getElementById('emp_id');
							var ele5 = document.getElementById('emp_id1');
							var ele6 = document.getElementById('emp_id2');
							var ele7 = document.getElementById('emp_id3');
							var ele8 = document.getElementById('emp_id_leave');
							for (i = 0; i < data.length; i++) {

								ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
								//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							}
							for (i = 0; i < data.length; i++) {

								ele4.innerHTML = ele4.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
								//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							}
							for (i = 0; i < data.length; i++) {

								ele5.innerHTML = ele5.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
								//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							}
							for (i = 0; i < data.length; i++) {

								ele6.innerHTML = ele6.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
								//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							}
							for (i = 0; i < data.length; i++) {

								ele7.innerHTML = ele7.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
								//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							}
							for (i = 0; i < data.length; i++) {

								ele8.innerHTML = ele8.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
								//                                                                        ele4.innCORPORATE BRANCHE LISTerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							}
						}
					}
				});
			}


		});

		function get_employees1() {
			var user_type = document.getElementById('user_type').value;
			var firm_id = document.getElementById('firm_name_sal_attnd').value;
			console.log(firm_id);
			console.log(user_type);
			//                                                            alert(firm_id);
			//                                                            var firm_id='firm_name_sal';
			$.ajax({
				url: "<?= base_url("Employee/get_employee_list") ?>",
				dataType: "json",
				type: "POST",
				data: {firm_id: firm_id, user_type: user_type},
				success: function (result) {
					//console.log(result);
					$("#select_employee").empty('');
					var ele3 = document.getElementById('select_employee');
					if (result['message'] === 'success') {
						ele3.innerHTML = ele3.innerHTML + '<option value="">Select Employee</option>';
						var data = result['result'];


						for (i = 0; i < data.length; i++) {

							ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							//
						}

					} else {
						ele3.innerHTML = ele3.innerHTML + '<option value="">No Employee</option>';
					}
				}
			});
		}

		$('#Fyear2').each(function () {

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

		$(document).ready(function () {
			$('#select_schedule_type1').change(function () {
				if ($(this).val() == '1') {
					$('#select_fixed_hour1').css({'display': 'block'});
					$('#inapplicable_time_div1').css({'display': 'block'});
					$('#select_in_time_applicable1').css({'display': 'block'});
				} else {
					$('#select_fixed_hour1').css({'display': 'none'});
					$('#inapplicable_time_div1').css({'display': 'none'});
					$('#select_in_time_applicable1').css({'display': 'none'});
					$('.off_fixed_hour1').val('');
				}
			});
			$('#select_schedule_type2').change(function () {
				if ($(this).val() == '1') {
					$('#select_fixed_hour2').css({'display': 'block'});
					$('#inapplicable_time_div2').css({'display': 'block'});
					$('#select_in_time_applicable2').css({'display': 'block'});
				} else {
					$('#select_fixed_hour2').css({'display': 'none'});
					$('#inapplicable_time_div2').css({'display': 'none'});
					$('#select_in_time_applicable2').css({'display': 'none'});
					$('.off_fixed_hour2').val('');
				}
			});
			$('#select_schedule_type3').change(function () {
				if ($(this).val() == '1') {
					$('#select_fixed_hour3').css({'display': 'block'});
					$('#inapplicable_time_div3').css({'display': 'block'});
					$('#select_in_time_applicable3').css({'display': 'block'});
				} else {
					$('#select_fixed_hour3').css({'display': 'none'});
					$('#inapplicable_time_div3').css({'display': 'none'});
					$('#select_in_time_applicable3').css({'display': 'none'});
					$('.off_fixed_hour3').val('');
				}
			});
			$('#select_schedule_type4').change(function () {
				if ($(this).val() == '1') {
					$('#select_fixed_hour4').css({'display': 'block'});
					$('#inapplicable_time_div4').css({'display': 'block'});
					$('#select_in_time_applicable4').css({'display': 'block'});
				} else {
					$('#select_fixed_hour4').css({'display': 'none'});
					$('#inapplicable_time_div4').css({'display': 'none'});
					$('#select_in_time_applicable4').css({'display': 'none'});
					$('.off_fixed_hour4').val('');
				}
			});
			$('#select_schedule_type5').change(function () {
				if ($(this).val() == '1') {
					$('#select_fixed_hour5').css({'display': 'block'});
					$('#inapplicable_time_div5').css({'display': 'block'});
					$('#select_in_time_applicable5').css({'display': 'block'});
				} else {
					$('#select_fixed_hour5').css({'display': 'none'});
					$('#inapplicable_time_div5').css({'display': 'none'});
					$('#select_in_time_applicable5').css({'display': 'none'});
					$('.off_fixed_hour5').val('');

				}
			});
			$('#select_schedule_type6').change(function () {
				if ($(this).val() == '1') {
					$('#select_fixed_hour6').css({'display': 'block'});
					$('#inapplicable_time_div6').css({'display': 'block'});
					$('#select_in_time_applicable6').css({'display': 'block'});
				} else {
					$('#select_fixed_hour6').css({'display': 'none'});
					$('#inapplicable_time_div6').css({'display': 'none'});
					$('#select_in_time_applicable6').css({'display': 'none'});
					$('.off_fixed_hour6').val('');
				}
			});
			$('#select_schedule_type7').change(function () {
				if ($(this).val() == '1') {
					$('#select_fixed_hour7').css({'display': 'block'});
					$('#inapplicable_time_div7').css({'display': 'block'});
					$('#select_in_time_applicable7').css({'display': 'block'});
				} else {
					$('#select_fixed_hour7').css({'display': 'none'});
					$('#inapplicable_time_div7').css({'display': 'none'});
					$('#select_in_time_applicable7').css({'display': 'none'});
					$('.off_fixed_hour7').val('');
				}
			});
		});

		//Intime applicable in work schedule variable

		function fix_in_applicable_change1(id) {
			var x = document.getElementById("inapplicable_time_div1");
			if (id === 'intime_appli_yes1') {

				x.style.display = "block";

			} else if (id === 'intime_appli_no1') {
				x.style.display = "none";
				$('.inappli_fix_time_check1').val('');
			}
		}

		function fix_in_applicable_change2(id) {
			var x = document.getElementById("inapplicable_time_div2");
			if (id === 'intime_appli_yes2') {

				x.style.display = "block";

			} else if (id === 'intime_appli_no2') {
				x.style.display = "none";
				$('.inappli_fix_time_check2').val('');
			}
		}

		function fix_in_applicable_change3(id) {
			var x = document.getElementById("inapplicable_time_div3");
			if (id === 'intime_appli_yes3') {

				x.style.display = "block";

			} else if (id === 'intime_appli_no3') {
				x.style.display = "none";
				$('.inappli_fix_time_check3').val('');
			}
		}

		function fix_in_applicable_change4(id) {
			var x = document.getElementById("inapplicable_time_div4");
			if (id === 'intime_appli_yes4') {

				x.style.display = "block";

			} else if (id === 'intime_appli_no4') {
				x.style.display = "none";
				$('.inappli_fix_time_check4').val('');
			}
		}

		function fix_in_applicable_change5(id) {
			var x = document.getElementById("inapplicable_time_div5");
			if (id === 'intime_appli_yes5') {

				x.style.display = "block";

			} else if (id === 'intime_appli_no5') {
				x.style.display = "none";
				$('.inappli_fix_time_check5').val('');
			}
		}

		function fix_in_applicable_change6(id) {
			var x = document.getElementById("inapplicable_time_div6");
			if (id === 'intime_appli_yes6') {

				x.style.display = "block";

			} else if (id === 'intime_appli_no6') {
				x.style.display = "none";
				$('.inappli_fix_time_check6').val('');
			}
		}

		function fix_in_applicable_change7(id) {
			var x = document.getElementById("inapplicable_time_div7");
			if (id === 'intime_appli_yes7') {

				x.style.display = "block";

			} else if (id === 'intime_appli_no7') {
				x.style.display = "none";
				$('.inappli_fix_time_check7').val('');
			}
		}


		function formatTime(timeInput) {

			intValidNum = timeInput.value;

			if (intValidNum < 24 && intValidNum.length == 2) {
				timeInput.value = timeInput.value + ":";
				return false;
			}
			if (intValidNum == 24 && intValidNum.length == 2) {
				timeInput.value = timeInput.value.length - 2 + "0:";
				return false;
			}
			if (intValidNum > 24 && intValidNum.length == 2) {
				timeInput.value = "";
				return false;
			}

			if (intValidNum.length == 5 && intValidNum.slice(-2) < 60) {
				timeInput.value = timeInput.value + ":";
				return false;
			}
			if (intValidNum.length == 5 && intValidNum.slice(-2) > 60) {
				timeInput.value = timeInput.value.slice(0, 2) + ":";
				return false;
			}
			if (intValidNum.length == 5 && intValidNum.slice(-2) == 60) {
				timeInput.value = timeInput.value.slice(0, 2) + ":00:";
				return false;
			}


			if (intValidNum.length == 8 && intValidNum.slice(-2) > 60) {
				timeInput.value = timeInput.value.slice(0, 5) + ":";
				return false;
			}
			if (intValidNum.length == 8 && intValidNum.slice(-2) == 60) {
				timeInput.value = timeInput.value.slice(0, 5) + ":00";
				return false;
			}


		}

		$(document).ready(function () {

			$('#myRadioGroup').hide();
			$('#show_services').hide();

		});
		$("#work_on_services").change(function () {
			var work_on_services = $('#work_on_services').val();

			if (work_on_services != null) {


				$("#myRadioGroup").show();

			} else {
				$('#myRadioGroup').hide();
				work_on_services = "0";
			}
		});

		$("#allot_permited").change(function () {
			var allot_permited = $('input[name=allot_permited]:checked').val();
			//                                            var allot_permited = $('#allot_permited').val();

			if (allot_permited == 1) {

				$("#show_services").show();
				get_services1();

			}
		});
		$("#allot_permited1").change(function () {
					var allot_permited = $('input[name=allot_permited]:checked').val();
					//                                            var allot_permited = $('#allot_permited').val();

					if (allot_permited == 0) {
						$('#show_services').hide();
						$('#work_on_services1').empty();


					}

				}
		);


		function probation_change(id) {
			var x = document.getElementById("prob_yes");
			if (id === 'prob_yes') {

				x.style.display = "block";

			} else if (id === 'prob_no') {
				x.style.display = "none";
			}
		}

		function training_change(id) {
			var x = document.getElementById("train_yes");
			if (id === 'train_yes') {

				x.style.display = "block";

			} else if (id === 'train_no') {
				x.style.display = "none";
			}
		}

		function applicable_change(id) {
			var x = document.getElementById("applicable_yes");
			if (id === 'applicable_yes') {

				x.style.display = "block";

			} else if (id === 'applicable_no') {
				x.style.display = "none";
			} else if (id === 'applicable_no') {
				x.style.display = "none";
			}
//                                            else if (id === 'leave_applicable_yes')
//                                            {
//                                                x.style.display = "none";
//                                            }
//                                            else if (id === 'outside_applicable_yes')
//                                            {
//                                                x.style.display = "none";
//                                            }
			else if (id === 'variable_hour_yes') {
				x.style.display = "none";
			}
//                                            else if (id === 'shift_applicable_yes')
//                                            {
//                                                x.style.display = "none";
//                                            }

		}

		function work_hour_change(id) {
			var x = document.getElementById("static_yes");
			if (id === 'static_yes') {

				x.style.display = "block";

			} else if (id === 'variable_yes') {
				x.style.display = "none";
				$('.work_fix_time_check').val('');
			}
		}

		function work_schedule_change(id) {
			var x = document.getElementById("fix_hour_yes");
			var y = document.getElementById("variable_hour_yes");
			if (id === 'fix_hour_yes') {

				x.style.display = "block";
				y.style.display = "none";


			} else if (id === 'variable_hour_yes') {
				y.style.display = "block";
				x.style.display = "none";
				$('.fix_hour_check').val('');
			}
		}

		function late_applicable_change(id) {
			var x = document.getElementById("late_yes");
			if (id === 'late_yes') {

				x.style.display = "block";

			} else if (id === 'late_no') {
				x.style.display = "none";
				$('.late_salary_count1').val('');
			}
		}

		//for deduction fix & percentage

		function deduction_type_change(id) {
			var x = document.getElementById("fix_deduct_amount_yes");
			var y = document.getElementById("deduct_percentage_yes");
			if (id === 'fix_deduct_amount_yes') {

				x.style.display = "block";
				y.style.display = "none";
				$('#value_deduct_percentage').val('');

			} else if (id === 'deduct_percentage_yes') {
				y.style.display = "block";
				x.style.display = "none";
				$('#value_ded').val('');
			}
		}


		//                                        $(document).ready(function () {
		//                                            $('#email').change(function () {
		//                                                var email = $('#email').val();
		//                                                if (email != '')
		//                                                {
		//                                                    $.ajax({
		//                                                        url: "<?php echo base_url(); ?>Employee/check_email_avalibility",
		//                                                        method: "POST",
		//                                                        data: {email: email},
		//                                                        success: function (data) {
		//                                                            $('#email_result').html(data);
		//                                                        }
		//                                                    });
		//                                                }
		//                                            });
		//
		//
		//                                        });


		function get_designation() {
			var firm_id = document.getElementById('firm_name').value;
			var user_type = document.getElementById('user_type').value;
			$.ajax({
				type: "POST",
				url: "<?= base_url("/Designation/get_designation_hq") ?>",
				dataType: "json",
				data: {firm_id: firm_id, user_type: user_type},
				success: function (result) {
					if (result['message'] === 'success') {
						var data = result.result_designation;
						var ele = document.getElementById('designation');
						ele.innerHTML = "";
						ele.innerHTML = '<option value="">Select option</option>';
						for (i = 0; i < data.length; i++) {
							// POPULATE SELECT ELEMENT WITH JSON.
							ele.innerHTML = ele.innerHTML +
									'<option value="' + data[i]['designation_id'] + '">' + data[i]['designation_name'] + '</option>';
						}
					}
				}

			});

			$.ajax({
				type: "POST",
				url: "<?= base_url("/Employee/ddl_get_employee_hq") ?>",
				dataType: "json",
				data: {firm_id: firm_id, user_type: user_type},
				success: function (result) {
					if (result['message'] === 'success') {

						var data = result.emp_data;
//                                                         var data_hr = result.hr_data;
						var data_hr = result.hr_data_hq;
						console.log(data_hr);
						console.log(data);
						var ele = document.getElementById('senior_emp');
						if (data_hr === '') {
							ele.innerHTML = "<option value=''>No senior Employee</option>";
						} else {
							ele.innerHTML = "<option value='" + data_hr.user_id + "'>" + data_hr.user_name + "</option>";
							for (i = 0; i < data.length; i++) {
								// POPULATE SELECT ELEMENT WITH JSON.
								ele.innerHTML = ele.innerHTML +
										'<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							}
						}
//                                                         ele.innerHTML = ele.innerHTML +
//                                                                    '<option value="No senior authority">No senior authority</option>';
					}
				}
			});
		}


		$(document).ready(function () {
			var user_type = document.getElementById('user_type').value;
			//                                                            alert(user_type);
			console.log(user_type);
			if (user_type == 5) {
				$.ajax({
					type: "POST",
					url: "<?= base_url("/Designation/get_designation_hq") ?>",
					dataType: "json",
					data: {user_type: user_type},
					success: function (result) {
						if (result['message'] === 'success') {
							var data = result.result_designation;
							var ele = document.getElementById('designation');
							ele.innerHTML = "";
							ele.innerHTML = '<option value="">Select option</option>';
							for (i = 0; i < data.length; i++) {
								// POPULATE SELECT ELEMENT WITH JSON.
								ele.innerHTML = ele.innerHTML +
										'<option value="' + data[i]['designation_id'] + '">' + data[i]['designation_name'] + '</option>';
							}
						}
					}

				});
			}
		});

		$(document).ready(function () {

			var user_type = document.getElementById('user_type').value;
			//                                                            alert(user_type);
			console.log(user_type);
			//                                                            console.log("employee Hr");
			if (user_type == 5) {
				$.ajax({
					type: "POST",
					url: "<?= base_url("/Employee/ddl_get_employee_hq") ?>",
					dataType: "json",
					data: {user_type: user_type},
					success: function (result) {
						if (result['message'] === 'success') {

							var data = result.emp_data;
							var data1 = result.hr_data;
							//                                                                        var data_hr = result.hr_data;
							//                                                                        console.log(data_hr);
							var ele = document.getElementById('senior_emp');
							//                                                        ele.innerHTML = "<option value=''>Select Senior Employee</option>";
							//
							ele.innerHTML = "<option value='" + data1.user_id + "'>" + data1.user_name + "</option>";
							for (i = 0; i < data.length; i++) {
								// POPULATE SELECT ELEMENT WITH JSON.
								ele.innerHTML = ele.innerHTML +
										'<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
							}

						}
					}
				});
			}
		});

		$("#save").click(function () {
			var $this = $(this);
			$this.button('loading');
			setTimeout(function () {
				$this.button('reset');
			}, 2000);
			document.getElementById('loaders7').style.display = "block";
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/add_emp_hq") ?>",
				dataType: "json",
				data: $("#addemp").serialize(),
				success: function (result) {
					if (result.status === true) {
						document.getElementById('loaders7').style.display = "none";
						alert('Employee created successfully.');
//                                                        location.reload();
						$('#tab_5_2_attend').click();
						$("#addemp")[0].reset();
						get_emp();
						//                                                                        window.location.href = "<?= base_url("hr_show_employee") ?>";
					} else if (result.result === '2') {
						document.getElementById('loaders7').style.display = "none";
						alert('Fetching error.');
					} else if (result.result === '3') {
						document.getElementById('loaders7').style.display = "none";
						alert('Query error.');
					} else if (result.result === '4') {
						document.getElementById('loaders7').style.display = "none";
						alert('Something went wrong.');
					} else {
						document.getElementById('loaders7').style.display = "none";
						$('#' + result.id + '_error').html(result.error);
						$('html, body').animate({
							scrollTop: $('#' + result.id + '_error').offset().top - 80
						}, 100);
						//                    $('#message').html(result.error);
					}
				},
				error: function (result) {
					console.log(result);
					if (result.status === 500) {
						alert('Internal error: ' + result.responseText);
						document.getElementById('loaders7').style.display = "none";
					} else {
						alert('Unexpected error.');
						document.getElementById('loaders7').style.display = "none";
					}
				}
			});
		});


		$("#add_attendance").click(function () {
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/add_attendance_employee") ?>",
				dataType: "json",
				data: $("#emp_attendance_form").serialize(),
				success: function (result) {
					if (result.status === true) {
						alert('Employee attendance updated successfully.');
//                                                        location.reload();
						$('#tab_5_3_salary').click();
						$("#emp_attendance_form")[0].reset();
					} else {
						$('#' + result.id + '_error').html(result.error);
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
		});

		//Performance Allowance Form
		$("#add_emp_performances").click(function () {
			var firm_name = document.getElementById("firm_name_sal").value;

			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/add") ?>",
				dataType: "json",
				data: $("#performance_allowance").serialize() + "&emp_firm=" + firm_name,
				success: function (result) {
					if (result.status === true) {
						document.getElementById('loaders7').style.display = "none";
						alert('Employee Performance addded successfully.');
						location.reload();
						//                                                                        window.location.href = "<?= base_url("hr_show_employee") ?>";
					} else if (result.result === '2') {
						document.getElementById('loaders7').style.display = "none";
						alert('Fetching error.');
					} else if (result.result === '3') {
						document.getElementById('loaders7').style.display = "none";
						alert('Query error.');
					} else if (result.result === '4') {
						document.getElementById('loaders7').style.display = "none";
						alert('Something went wrong.');
					} else {
						document.getElementById('loaders7').style.display = "none";
						$('#' + result.id + '_error').html(result.error);
						//                    $('#message').html(result.error);
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
		});


		$("#performance_allowance").validate({
			rules: {
				value: {
					required: true

				},
				emp_id: {
					required: true

				},
				Month: {
					required: true
				},
				Fyear: {
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
				emp_id: {
					required: "Select Employee"
				},
				Month: {
					required: "Select Month"
				},
				Fyear: {
					required: "Enter Financial Year"
				},
				Date_of_PA: {
					required: "Enter Date"
				}

			}, errorElement: "span",
			submitHandler: function (form) { // for demo
				var firm_name = document.getElementById("firm_name_sal").value;
				$.ajax({
					url: '<?= base_url("Employee/add_performance_info") ?>',
					//                                                    url: '<?= base_url("Employee/add") ?>',
					type: "POST",
					datatype: "JSON",
					data: $("#performance_allowance").serialize() + "&emp_firm=" + firm_name,
					success: function (success) {
						success = JSON.parse(success);
						if (success.status === true) {
							alert("Performance allowance added  Successfully.");
							$('#tab_6_2_loan').click();
							$("#performance_allowance")[0].reset();
//                                                            location.reload();

//                                                            document.getElementById('performance_allowance').reset();
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

		//Staff loan form

		//add_loan

		$("#staff_loan").validate({
			rules: {
				emp_id1: {required: true},
				loan_detail: {required: true},
				amount: {required: true},
				EMI_amt: {required: true},
				from_month: {required: true},
				Fyear: {required: true},
				total_month: {required: true},
				sanction_date: {required: true},
			},
			messages: {
				emp_id1: {required: "Select Employee"},
				loan_detail: {required: "Enter Loan Details"},
				amount: {equired: "Enter the Amount"},
				EMI_amt: {
					required: "Enter EMI Amount"
				},
				from_month: {required: "Select Month"},
				FYear: {required: "Select Financial Year"},
				total_month: {required: "Enter Total Month"},
				sanction_date: {required: "Enter Date"}

			}, errorElement: "span",
			submitHandler: function (form) { // for demo
				var firm_name = document.getElementById("firm_name_sal").value;
				//                                                            alert(firm_name);
				$.ajax({
					url: '<?= base_url("add_loan_info") ?>',
					//                                                                    url: '<?= base_url("add_loan") ?>',
					type: "POST",
					data: $("#staff_loan").serialize() + "&emp_firm=" + firm_name,
					success: function (success) {
						success = JSON.parse(success);
						if (success.status === true) {
							alert("Staff loan added  Successfully.");
							$('#tab_6_3_salary').click();
							$("#staff_loan")[0].reset();
//                                                            location.reload();
						} else {

							toastr.error(success.body); //toster.error
						}
					},
					error: function (error) {
						toastr.error(success.body);
						console.log(error);
						errorNotify("something went to wrong.");
					}
				});
			}
		});

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

		//Add Salary Details

		$("#salary_details").validate({
			rules: {
				emp_id2: {required: true},
				ot_applicable_sts: {required: true},
				sal_options: {required: true},
				value: {required: true},
				salary_calcu: {required: true}
			},
			messages: {
				emp_id2: {required: "Select Employee"},
				ot_applicable_sts: {required: "Select Overtime Status"},
				sal_options: {required: "Select Salary Type"},
				value: {equired: "Enter The Amount"},
				salary_calcu: {equired: "Please select"},
			}, errorElement: "span",
			submitHandler: function (form) { // for demo
				var firm_name = document.getElementById("firm_name_sal").value;
				$.ajax({
					url: '<?= base_url("add_salary") ?>',
					type: "POST",
					data: $("#salary_details").serialize() + "&emp_firm=" + firm_name,
					success: function (success) {
						success = JSON.parse(success);
						if (success.status === true) {
							alert("Salary  details added  successfully.");
							$('#tab_6_4_deduction').click();
							$("#salary_details")[0].reset();
							///                        $('#create_customer_modal').modal("toggle");//modal name if not remove

						} else if (success.status === 200) {
							alert("For this employee salary type is already set.");
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

		function get_sal_type_list() {
			var firm_id = document.getElementById('firm_name_sal').value;
			console.log(firm_id);
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
					console.log(data);
					if (result.code == 200) {
						$('#sal_options').html(data);
					} else {
						$('#sal_options').html("");
					}
				},
			});
		}

		//

		//Add deduction details

		$("#add_duedetails").click(function () {
			var firm_name = document.getElementById("firm_name_sal").value;
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/add_due") ?>",
				dataType: "json",
				data: $("#due_details").serialize() + "&emp_firm=" + firm_name,
				success: function (result) {
					if (result.status === true) {
						alert('Employee deduction added successfully.');
						$('#tab_6_4_deduction').click();
						$("#due_details")[0].reset();
						deduction_type_change();
						$("#deduct_percentage_yes").hide();
						$("#fix_deduct_amount_yes").show();
					} else if (result.status === 200) {
						alert("For this employee deduction type is already set.");

					} else {
						$('#' + result.id + '_error').html(result.error);
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
		});

		function get_ded_type_list() {
			var firm_id = document.getElementById('firm_name_sal').value;
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


		function remove_error(id) {
			$('#' + id + '_error').html("");
		}

		$(document).ready(function () {
			$.ajax({
				url: "<?= base_url("/Human_resource/get_ddl_firm_name") ?>",
				dataType: "json",
				success: function (result) {
					if (result['message'] === 'success') {
						var data = result.frim_data;
						$('#boss_id').val(data[0]['reporting_to']);
						var ele3 = document.getElementById('firm_name');
						var ele4 = document.getElementById('firm_name_sal');
						var ele5 = document.getElementById('firm_name_sal_attnd');
						var ele6 = document.getElementById('firm_name_leave');
						ele3.innerHTML = "<option value=''>Select office</option>";
						for (i = 0; i < data.length; i++) {
							// POPULATE SELECT ELEMENT WITH JSON.
							ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							ele4.innerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							ele5.innerHTML = ele5.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
							ele6.innerHTML = ele6.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
						}
					}
				}
			});
		});

		function get_leavedetails() {
			var firm_id = document.getElementById("firm_name_leave").value;
//                                            alert(firm_id);
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/get_leave_Details") ?>",
				dataType: "json",
				data: {firm_id: firm_id},
				success: function (result) {
//
//                                                    alert('hi');
					var data = result.leaves;
					console.log(data);
					var type1 = result.type1;
					var type2 = result.type2;
					var type3 = result.type3;
					var type4 = result.type4;
					var type5 = result.type5;
					var type6 = result.type6;
					var type7 = result.type7;
//                                                    var total = result.total_yearly_leaves;
					var myarr = type1.split(":");
					var myvar = myarr[0];
					var type1_1 = myarr[1];

					var myarr1 = type2.split(":");
					var myvar1 = myarr1[0];
					var type2_1 = myarr1[1];
					var myarr3 = type3.split(":");
					var myvar2 = myarr3[0];
					var type3_1 = myarr3[1];
					var myarr4 = type4.split(":");
					var myvar3 = myarr4[0];
					var type4_1 = myarr4[1];

					var myarr5 = type5.split(":");
					var myvar4 = myarr5[0];
					var type5_1 = myarr5[1];
					var myarr6 = type6.split(":");
					var myvar5 = myarr6[0];
					var type6_1 = myarr6[1];
					var myarr7 = type7.split(":");
					var myvar6 = myarr7[0];
					var type7_1 = myarr7[1];

					console.log(myvar);
					$('#leave_type1').val(myvar);
					$('#numofdays1').val(type1_1);
					$('#leave_type2').val(myvar1);
					$('#numofdays2').val(type2_1);
					$('#leave_type3').val(myvar2);
					$('#numofdays3').val(type3_1);
					$('#leave_type4').val(myvar3);
					$('#numofdays4').val(type4_1);
					$('#leave_type5').val(myvar4);
					$('#numofdays5').val(type5_1);
					$('#leave_type6').val(myvar5);
					$('#numofdays6').val(type6_1);
					$('#leave_type7').val(myvar6);
					$('#numofdays7').val(type7_1);
//                                                    $('#total_leaves').val(total);

				}
			});
		}

		function get_leavedetails1() {
			var user_id = document.getElementById("emp_id_leave").value;
			//alert(user_id);
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/get_leave1Details") ?>",
				dataType: "json",
				data: {user_id: user_id},
				success: function (result) {
//
//                                                    alert('hi');
					var data = result.leaves;
					console.log(data);
					var type1 = result.type1;
					var type2 = result.type2;
					var type3 = result.type3;
					var type4 = result.type4;
					var type5 = result.type5;
					var type6 = result.type6;
					var type7 = result.type7;
//                                                    var total = result.total_yearly_leaves;
					var myarr = type1.split(":");
					var myvar = myarr[0];
					var type1_1 = myarr[1];

					var myarr1 = type2.split(":");
					var myvar1 = myarr1[0];
					var type2_1 = myarr1[1];
					var myarr3 = type3.split(":");
					var myvar2 = myarr3[0];
					var type3_1 = myarr3[1];
					var myarr4 = type4.split(":");
					var myvar3 = myarr4[0];
					var type4_1 = myarr4[1];

					var myarr5 = type5.split(":");
					var myvar4 = myarr5[0];
					var type5_1 = myarr5[1];
					var myarr6 = type6.split(":");
					var myvar5 = myarr6[0];
					var type6_1 = myarr6[1];
					var myarr7 = type7.split(":");
					var myvar6 = myarr7[0];
					var type7_1 = myarr7[1];

					console.log(myvar);
					$('#leave_type1').val(myvar);
					$('#numofdays1').val(type1_1);
					$('#leave_type2').val(myvar1);
					$('#numofdays2').val(type2_1);
					$('#leave_type3').val(myvar2);
					$('#numofdays3').val(type3_1);
					$('#leave_type4').val(myvar3);
					$('#numofdays4').val(type4_1);
					$('#leave_type5').val(myvar4);
					$('#numofdays5').val(type5_1);
					$('#leave_type6').val(myvar5);
					$('#numofdays6').val(type6_1);
					$('#leave_type7').val(myvar6);
					$('#numofdays7').val(type7_1);
//                                                    $('#total_leaves').val(total);

				}
			});
		}

		function get_services() {
			var firm_id = document.getElementById("firm_name").value;
			$.ajax({
				type: "POST",
				url: "<?= base_url("/Employee/get_services_hq") ?>",
				dataType: "json",
				data: {firm_id: firm_id},
				success: function (result) {
					if (result['message'] === 'success') {
						var data = result.service_data;
						var ele = document.getElementById('work_on_services');

						ele.innerHTML = "";
						for (i = 0; i < data.length; i++) {
							// POPULATE SELECT ELEMENT WITH JSON.
							ele.innerHTML = ele.innerHTML +
									'<option value="' + data[i]['service_id'] + '">' + data[i]['service_name'] + '</option>';
						}
					}
				}
			});

			$.ajax({
				type: "post",
				url: "<?= base_url("Employee/duedate_Name") ?>",
				dataType: "json",
				data: {firm_id: firm_id},
				success: function (result) {
					//ele2.innerHTML = '';
					if (result.message === 'success') {

						$('#myModal').modal({backdrop: 'static', keyboard: false});
						$("#due_date").click(function () {
							window.location.href = "<?= base_url("hr_designation") ?>";
						});

					} else if (result.message == 'unsuccess') {
						$('#myModal').modal('hide');
					}
				}
			});
		}


		function get_services1() {
			var firm_id = document.getElementById("firm_name").value;
			$.ajax({
				type: "POST",
				url: "<?= base_url("/Employee/get_services_hq") ?>",
				dataType: "json",
				data: {firm_id: firm_id},
				success: function (result) {
					if (result['message'] === 'success') {
						var data = result.service_data;
						var ele = document.getElementById('work_on_services1');


						ele.innerHTML = "";
						for (i = 0; i < data.length; i++) {
							// POPULATE SELECT ELEMENT WITH JSON.
							ele.innerHTML = ele.innerHTML +
									'<option value="' + data[i]['service_id'] + '">' + data[i]['service_name'] + '</option>';
						}

					}
				}
			});
		}

		function check_leave_data() {

			var desig = document.getElementById("designation").value;
			var probation_period = document.getElementById("a");
			var probation_period1 = document.getElementById("aa");
			var training_period = document.getElementById("b");
			var training_period1 = document.getElementById("bb");
			$.ajax({
				type: "POST",
				url: "<?= base_url("Employee/check_designation_status") ?>",
				dataType: "json",
				data: {desig: desig},
				success: function (result) {
					if (result.message === 'probation') {
						probation_period.style.display = "none";
						probation_period1.style.display = "none";
						document.getElementById("probation_period").value = "";

					} else if (result.message === 'training') {
						training_period.style.display = "none";
						training_period1.style.display = "none";
						document.getElementById("training_period").value = "";
					} else {
						probation_period.style.display = "block";
						probation_period1.style.display = "block";
						training_period.style.display = "block";
						training_period1.style.display = "block";
					}
				},
				//            error: function (result) {
				//                console.log(result);
				//                if (result.status === 500) {
				//                    alert('Internal error: ' + result.responseText);
				//                } else {
				//                    alert('Unexpected error.');
				//                }
				//            }
			});
		}

		function onDateChange() {
			var selected_date = document.getElementById("date_of_joining").value;
			document.getElementById("prob_date_first").setAttribute('min', selected_date);
			document.getElementById("prob_date_second").setAttribute('min', selected_date);
			document.getElementById("training_period_first").setAttribute('min', selected_date);
			document.getElementById("training_period_second").setAttribute('min', selected_date);
		}


		function check_attendance_emp_exist() {
			var select_employee = document.getElementById('select_employee').value;
			$.ajax({
				url: '<?= base_url("Employee/check_attendanceemp_exist") ?>',
				type: "POST",
				data: {select_employee: select_employee},
				success: function (success) {
					var success = JSON.parse(success);
					var data = success.employee_attend_exist;
					if (success.message === 'success') {
						alert("You have alreday configured attendance for this employee.If you want to see then go to edit otherwise here you can also update the attendance.");

					} else {
					}

				}
			});
		}

		function get_data(id) {
			if (id == 1) {
				$("#p_div").show();
			} else {
				$("#p_div").hide();
			}
		}
	</script>
