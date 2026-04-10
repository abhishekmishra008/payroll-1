<?php
// $this->load->view('admin/header');
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');

$user_id = $this->session->userdata('login_session');
$user_type = $user_id['user_type'];
if ($user_id == '') {
    redirect(base_url() . 'login'); //take them back to signin
}
$head_text = 'Add Employee';
?>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-tagsinput/bootstrap-tagsinput/bootstrap-tagsinput.css" />
<script>
    let baseUrl = '<?php echo base_url(); ?>';
</script>

<style>
    span.error {
        color: red;
    }
    .tabbable-custom>.nav-tabs>li>a {
        margin-right: 0;
        color: black !important;}
    .tabbable-custom>.nav-tabs>li {
        margin-right: 2px;
        background-color: #7cabb7 !important;
        border-top: 2px solid #f9f3f3b5;}

</style>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'show_firm') ? 'active' : '' ?>">
                        Home
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>
                </ul>
            </div>
        </div>

        <!-- <div class="page-fixed-main-content"> -->
        <div class="row"></div>
        <div class="portlet light ">

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-body">
                            There is no Designation made in your company,you will not able to create employee.Please Add designation click on ok buttton
                        </div>
                        <div class="modal-footer">

                            <button type="button"  id= "due_date" class="btn btn-primary">ok</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-settings font-red-sunglo"></i>
                    
                    <span class="caption-subject bold uppercase"> Edit Employee : <?php echo isset($employee) ? $employee : ''; ?></span>
                </div>

            </div>

            <div class="modal-body">
                <input type="hidden" name="emp_idx" id="emp_idx" value=""><input type="hidden" name="firm_idx" id="firm_idx" value="">
                <!--<form id="Salary_Details" name="Salary_Details" method="POST">-->
                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_5_1" data-toggle="tab"> Basic Details</a>
                        </li>
                        <li>
                            <a href="#tab_5_2" data-toggle="tab" onclick="GetAttendanceInfo('<?php echo $this->uri->segment(2) ?>', '<?php echo $this->uri->segment(3) ?>')"> Attendance </a>
                        </li>
						<li>
							<a href="#tab_5_3" data-toggle="tab" id="tab_5" onclick="get_performance_data();"> Salary Details </a>
						</li>
                        <li>
                            <a href="#tab_5_5" data-toggle="tab" onclick="get_employee_leave_data()"> Leave Configuration </a>
                        </li>
                        <li>
                            <a href="#tab_5_6" data-toggle="tab"> Asset Management </a>
                        </li>
                        <li>
                            <a href="#tab_5_7" data-toggle="tab" onclick="get_emp_leave_details()"> Leave Details </a>
                        </li>
                         <li>
                            <a href="#tab_5_8" data-toggle="tab" onclick="listUploadUserDocument()"> Employee Document </a>
                        </li>
                        <li>
                            <a href="#tab_5_4" data-toggle="tab" onclick="get_exit_data()"> Employee Exit </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_5_1">
                            <div class="form-body">
                                <form method="POST" id="addemp" name="addemp" class="form-horizontal" novalidate="novalidate">

                                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $result_emp_data->user_id ?>">
                                    <input type="hidden" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <p id="message" style="color:red"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">



                                        <div class="col-md-4">

                                            <label>Name<span class="error">*</span></label>

                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="text" class="form-control" name="user_name" id="  user_name" value="<?php echo $result_emp_data->user_name; ?>" placeholder="Enter Your Name" onkeyup="remove_error('user_name')" aria-required="true" aria-describedby="input_group-error">
                                            </div>
                                            <span class="required" id="user_name_error"></span>
                                        </div>
                                        <div class="col-md-4">

                                            <label>Father's / Husband Name<span class="error">*</span></label>

                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="text" class="form-control" name="fh_name" id="fh_name" value="<?php echo $result_emp_data->spouse_name; ?>" placeholder="Enter Father's / Husband Name" oninput="remove_error('fh_name')" aria-required="true" aria-describedby="input_group-error">
                                            </div>
                                            <span class="required" id="fh_name_error" style="color:red"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Date Of Birth<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                <input type="date" class="form-control" name="dob" id="dob" value="<?php echo $result_emp_data->date_of_birth; ?>" placeholder="Enter Your Date Of Birth" oninput="remove_error('user_name')" aria-required="true" aria-describedby="input_group-error">
                                            </div>
                                            <span class="required" id="dob_error" style="color:red"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Gender<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-odnoklassniki"></i>
                                                </span>
                                                <select name="gender" id="gender" oninput="remove_error('gender')" class="form-control m-select2 m-select2-general">

                                                    <?php
                                                    if ($result_emp_data->gender == 1) {
                                                        $gender1 = "Male";
                                                    } else if ($result_emp_data->gender == 2){
                                                        $gender1 = "Female";
                                                    }else{
                                                        $gender1 = "Select";
                                                    }
                                                    ?>

                                                    <option value="<?php echo $result_emp_data->gender; ?>"><?php echo $gender1; ?></option>
                                                    <option value="">Select</option>
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
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
                                                <input type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" name="mobile_no" oninput="remove_error('mobile_no')"  value="<?php echo $result_emp_data->mobile_no; ?>" id="mobile_no" data-required="1" class="form-control" placeholder="Enter your Mobile No">
                                            </div>
                                            <span class="required" id="mobile_no_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- <label class="">Email Id<span class="error">*</span></label> -->
                                            <label class="">Email Id</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                                <input type="text" id="email" name="email" onkeyup="remove_error('email')" value="<?php echo $result_emp_data->email; ?>"  class="form-control" placeholder="Enter Your Email Id">
                                            </div>
                                            <span class="required" id="email_error"></span>
                                            <span id="email_result" ></span>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label>UAN<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class=" fa fa-rupee"></i>
                                                </span>
                                                <input type="text" value="<?php echo $result_emp_data->UAN_no; ?>"   name="uan" oninput="remove_error('mobile_no')"  id="uan" data-required="1" class="form-control" placeholder="Enter your UAN No">
                                            </div>
                                            <span class="required" id="uan_error" style="color:red"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>PAN No<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-phone"></i>
                                                </span>
                                                <input type="text" name="pan" oninput="remove_error('pan')" value="<?php echo $result_emp_data->pan_no; ?>"  id="pan" data-required="1" class="form-control" placeholder="Enter your PAN No">
                                            </div>
                                            <span class="required" id="pan_error" style="color:red"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Department<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="text" name="department" value="<?php echo $result_emp_data->department_name; ?>"
                                                       oninput="remove_error('department')"  id="department" data-required="1"
                                                       class="form-control" placeholder="Enter your Department Name">
                                            </div>
                                            <span class="required" id="department_error" style="color:red"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Account No<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-bank"></i>
                                                </span>
                                                <input type="number"   name="ac_no" value="<?php echo $result_emp_data->account_no; ?>" oninput="remove_error('ac_no')"  id="ac_no" data-required="1" class="form-control" placeholder="Enter your Account No">
                                            </div>
                                            <span class="required" id="ac_no_error" style="color:red"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Adhar No<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="text"   name="adhar_no" value="<?php echo $result_emp_data->adhar_no; ?>" oninput="remove_error('adhar_no')"  id="adhar_no" data-required="1" class="form-control" placeholder="Enter your Adhar No">
                                            </div>
                                            <span class="required" id="adhar_no_error" style="color:red"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Bank Name<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-bank"></i>
                                                </span>
                                                <input type="text"   name="bank_name" value="<?php echo $result_emp_data->bank_name; ?>" oninput="remove_error('bank_name')"  id="bank_name" data-required="1" class="form-control" placeholder="Enter your Bank Name">
                                            </div>
                                            <span class="required" id="bank_name_error" style="color:red"></span>
                                        </div>
										<div class="col-md-4">
                                            <label>Employee code<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="text"   name="emp_code" oninput="remove_error('emp_code')"value="<?php echo $result_emp_data->emp_code; ?>"  id="emp_code" data-required="1" class="form-control" placeholder="Enter Employee Code">
                                            </div>
                                            <span class="required" id="emp_code_error" style="color:red"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="">Address<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i></span>
                                                <input type="text"  id="address" name="address" oninput="remove_error('address')" value="<?php echo $result_emp_data->address; ?>" class="    form-control m-input" placeholder="Enter Your Address">
                                            </div>
                                            <span class="required" id="address_error"></span>
                                        </div>
										<div class="col-md-4">
                                            <label class="">Permanant Address<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i></span>
                                                <input type="text"  id="Paddress" name="Paddress" oninput="remove_error('address')" value="<?php echo $result_emp_data->permanant_address; ?>" class="    form-control m-input" placeholder="Enter Your Permanant Address">
                                            </div>
                                            <span class="required" id="address_error" style="color:red"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Country<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">  <i class="fa fa-flag"></i></span></span>
                                                <input type="text" id="country" name="country" oninput="remove_error('country')" value="<?php echo $result_emp_data->country; ?>" class="form-control m-input" placeholder="Enter your country">
                                            </div>
                                            <span class="required" id="country_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>State<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-home"></i></span>
                                                <input type="text" id="state" name="state" oninput="remove_error('state')" value="<?php echo $result_emp_data->state; ?>" class="form-control m-input" placeholder="Enter Your State">
                                            </div>
                                            <span class="required" id="state_error"></span>
                                        </div>
										<!--										<div class="col-md-4">-->
										<!---->
										<!--                                           <label>Form Type:</label>-->
										<!---->
										<!--											 <div class="input-group">-->
										<!--                                                <span class="input-group-addon">-->
										<!--                                                    <i class="fa fa-user"></i>-->
										<!--                                                </span>-->
										<!--                                                <select class="form-control" id="Vform_id" name="Vform_id" oninput="remove_error('form16Type')" onchange="get_form16()">-->
										<!--												--><?php
										//                                                    if ($result_emp_data->form16Type == 1) {
										//                                                        $formType = "Form 16 Old";
										//                                                    } else {
										//                                                        $formType = "Form 16 New";
										//                                                    }
										//                                                    ?>
										<!--												<option value="--><?php //echo $result_emp_data->form16Type; ?><!--">--><?php //echo $formType; ?><!--</option>-->
										<!--												<option value="1">Form 16 Old</option>-->
										<!--												<option value="2">Form 16 New</option>-->
										<!--												</select>-->
										<!--                                            </div>-->
										<!--                                            <span class="required" id="formType_error" style="color:red"></span>-->
										<!--                                        </div>-->
                                    </div>

                                    <div class="form-group">


                                        <div class="col-md-4">
                                            <label class="">City<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-globe"></i></span>
                                                <input type="text"  id="city" name="city" oninput="remove_error('city')"  value="<?php echo $result_emp_data->city; ?>"  class="form-control m-input" placeholder="Enter Your City">
                                            </div>
                                            <span class="required" id="city_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="">Designation<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i></span>
                                                <select name="designation" id="designation" onchange="remove_error('designation'); check_leave_data();"  class="form-control m-select2 m-select2-general">
                                                  <?php
                                                  foreach ($all_designation as $des_id=>$des_name){
                                                      $selected='';
                                                      if($des_id == $result_emp_data->designation_id){
                                                          $selected='selected';
                                                      }
                                                      ?>
                                                      <option value="<?php echo $des_id; ?>" <?php echo $selected; ?>><?php echo $des_name; ?></option>
                                                <?php  }
                                                  ?>

                                                    
                                                </select>
                                            </div>
                                            <span class="required" id="designation_error"></span>
                                        </div>


                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">

                                            <label class="">Reporting Manager<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i></span>
                                                <select name="senior_emp" id="senior_emp" onchange="remove_error('senior_emp')"  class="form-control m-select2 m-select2-general senior_emp1">
                                                    <option value='<?php echo $result_senior_name->user_id ?>'><?php echo $result_senior_name->user_name ?></option>
                                                </select>
                                            </div>
                                            <span class="required" id="senior_emp_error"></span>
                                        </div>
                                        <?php
                                        $originalDate = $result_emp_data->date_of_joining;
                                        $newcompletionDate = date("Y-m-d", strtotime($originalDate));
                                        ?>
                                        <div class="col-md-4">
                                            <label class="">Date Of Joining<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i></span>
                                                <input type="date" id="date_of_joining" name="date_of_joining" onkeyup="remove_error('date_of_joining')"  onchange="onDateChange()" value="<?php echo $newcompletionDate; ?>" class="form-control m-input" placeholder="Enter Your City">
                                            </div>
                                            <span class="required" id="date_of_joining_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="">Skill Set<span class="error">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i></span>

                                                <input type="text"  id="skill_set" name="skill_set" value="<?php echo $result_emp_data->skill_set; ?>"  onkeyup="remove_error('skill_set')"  class="form-control m-input"  placeholder="Skill Set" data-role="tagsinput">
                                            </div>
                                            <span class="required" id="skill_set_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="">User star rating </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-star"></i></span>
                                                <select name="star_rating" id="star_rating"  class="form-control m-select2 ">
                                                    <?php
                                                    if ($result_emp_data->user_star_rating != 1) {
                                                        ?>
                                                        <option selected readonly><?php echo $result_emp_data->user_star_rating; ?></option>
                                                        <?php
                                                    } else {

                                                    }
                                                    $a = 1;
                                                    for ($a = 1; $a <= 10; $a++) {
                                                        ?>
                                                        <option><?php echo $a; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                            <span class="required" id="star_rating_error"></span>
                                        </div>


                                        <div class="col-md-4">
                                            <label class="">Overtime Applicable<span class="error">*</span> </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-star"></i></span>
                                                <select name="ot_applicable_sts" id="ot_applicable_sts"  class="form-control m-select2 ">
                                                    <option value="">Select option</option>
<?php if ($result_emp_data->overtime_applicable_sts == 1) { ?>
                                                        <option value="1" selected="">Yes</option>
                                                        <option value="2" >No</option>
<?php } else { ?>
                                                        <option value="1">Yes</option>
                                                        <option value="2" selected="">No</option>
<?php } ?>
                                                </select>

                                            </div>
                                            <span class="required" id="ot_applicable_sts_error" style="color:red"></span>
                                        </div>
								<div class="col-md-2" id="">

                                            <label><b>Sandwich Leave Applicability:</b><span class="error">*</span></label><br>
                                            <div class="input-group">

												<?php //echo $result_emp_data->sandwich_leave_applicable;
												if($result_emp_data->sandwich_leave_applicable == 1){
												?>
												 <label id="d">
												 <input type="radio" id="sandwichLeave1" name="sandwichLeave" value="1" checked  data-checkbox="icheckbox_flat-grey" >  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
												<label id="dd">
												<input type="radio" id="sandwichLeave2" name="sandwichLeave" value="2"  data-checkbox="icheckbox_flat-grey" >  No </label>
												<?php
												}else{
													?>
													 <label id="d">
												 <input type="radio" id="sandwichLeave1" name="sandwichLeave" value="1"   data-checkbox="icheckbox_flat-grey" >  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
												<label id="dd">
                                                    <input type="radio" id="sandwichLeave2" name="sandwichLeave" value="2" checked data-checkbox="icheckbox_flat-grey" >  No </label>
												<?php
												}
												?>



                                            </div>
                                            <span class="required" style="color: red" id="salary_calcu_error"></span> <br>
                                        </div>
										<div class="col-md-2" id="">

                                            <label><b>GPS off Allow:</b><span class="error">*</span></label><br>
                                            <div class="input-group">

												<?php //echo $result_emp_data->sandwich_leave_applicable;
												if($result_emp_data->gps_off_allow == 1){
												?>
												 <label id="d">
												 <input type="radio" id="gpsoff1" name="gpsoff" value="1" checked  data-checkbox="icheckbox_flat-grey" >  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
												<label id="dd">
												<input type="radio" id="gpsoff2" name="gpsoff" value="0"  data-checkbox="icheckbox_flat-grey" >  No </label>
												<?php
												}else{
													?>
													 <label id="d">
												 <input type="radio" id="gpsoff1" name="gpsoff" value="1"   data-checkbox="icheckbox_flat-grey" >  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
												<label id="dd">
                                                    <input type="radio" id="gpsoff2" name="gpsoff" value="0" checked data-checkbox="icheckbox_flat-grey" >  No </label>
												<?php
												}
												?>



                                            </div>
                                            <span class="required" style="color: red" id="salary_calcu_error"></span> <br>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="">Salary Calculation:<span class="error">*</span></label>
                                            <div class="input-group">
<?php if ($result_emp_data->overtime_applicable_sts == 1 && $result_emp_data->salary_calculation == 2) { ?>
                                                    <label id="d">
                                                        <input type="radio" id="salary_calcu1" name="salary_calcu" value="1" disabled  data-checkbox="icheckbox_flat-grey" >  Daily </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label id="dd">
                                                        <input type="radio" id="salary_calcu2" name="salary_calcu" value="2" checked data-checkbox="icheckbox_flat-grey" >  Monthly </label>
<?php } else if ($result_emp_data->salary_calculation == 1 && $result_emp_data->overtime_applicable_sts == 2) { ?>
                                                    <label id="d">
                                                        <input type="radio" id="salary_calcu1" name="salary_calcu" value="1" checked  data-checkbox="icheckbox_flat-grey" >  Daily </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label id="dd">
                                                        <input type="radio" id="salary_calcu2" name="salary_calcu" value="2"  data-checkbox="icheckbox_flat-grey" >  Monthly </label>
<?php } else if ($result_emp_data->salary_calculation == 2 && $result_emp_data->overtime_applicable_sts == 2) { ?>
                                                    <label id="d">
                                                        <input type="radio" id="salary_calcu1" name="salary_calcu" value="1"   data-checkbox="icheckbox_flat-grey" >  Daily </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label id="dd">
                                                        <input type="radio" id="salary_calcu2" name="salary_calcu" value="2" checked data-checkbox="icheckbox_flat-grey" >  Monthly </label>
<?php } else { ?>
                                                    <label id="d">
                                                        <input type="radio" id="salary_calcu1" name="salary_calcu" value="1"   data-checkbox="icheckbox_flat-grey" >  Daily </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label id="dd">
                                                        <input type="radio" id="salary_calcu2" name="salary_calcu" value="2"  data-checkbox="icheckbox_flat-grey" >  Monthly </label>
<?php } ?>
                                            </div>
                                            <span class="required" style="color: red" id="salary_calcu_error"></span> <br>
                                        </div>

                                    </div>


                                    <div class="form-group">

                                        <div class="col-md-2">
                                            <label class="">Compensatory Off Applicability<span class="error">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php // echo $result_emp_data->holiday_working_sts;?>
<?php if ($result_emp_data->holiday_working_sts == '1') { ?>
                                                <label id="holiday1">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="1" checked  data-checkbox="icheckbox_flat-grey" > Applicable &nbsp;&nbsp;&nbsp;</label>
                                                <label id="holiday2">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="2" data-checkbox="icheckbox_flat-grey" > Not Applicable &nbsp;&nbsp;&nbsp;</label>
                                                <label id="holiday3">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="3" data-checkbox="icheckbox_flat-grey" > Applicable & Approval Required </label>
<?php } elseif ($result_emp_data->holiday_working_sts == '2') { ?>
                                                <label id="holiday1">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="1"   data-checkbox="icheckbox_flat-grey" > Applicable &nbsp;&nbsp;&nbsp;</label>
                                                <label id="holiday2">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="2" checked data-checkbox="icheckbox_flat-grey" > Not Applicable &nbsp;&nbsp;&nbsp;</label>
                                                <label id="holiday3">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="3" data-checkbox="icheckbox_flat-grey" > Applicable & Approval Required </label>
<?php } else { ?>
                                                <label id="holiday1">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="1"   data-checkbox="icheckbox_flat-grey" > Applicable &nbsp;&nbsp;&nbsp;</label>
                                                <label id="holiday2">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="2"  data-checkbox="icheckbox_flat-grey" > Not Applicable &nbsp;&nbsp;&nbsp;</label>
                                                <label id="holiday3">
                                                    <input type="radio" id="holiday_applible_sts" name="holiday_applible_sts" value="3" checked data-checkbox="icheckbox_flat-grey" > Applicable & Approval Required </label>
<?php } ?>
                                        </div>



                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <div class="icheck-inline">
<?php if ($result_emp_data->probation_period_start_date == '0000-00-00 00:00:00') { ?>
                                                        <label>Probation Period<span class="error">*</span></label>
                                                        <label id="a">
                                                            <input type="radio" id="probation_period" name="probation_period" value="0"   data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_yes')"> yes </label>
                                                        <label id="aa">
                                                            <input type="radio" id="probation_period" name="probation_period" value="1" checked data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_no')"> no </label>
<?php } else { ?>
                                                        <label>Probation Period<span class="error">*</span></label>
                                                        <label id="a">
                                                            <input type="radio" id="probation_period" name="probation_period" value="0" checked  data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_yes')"> yes </label>
                                                        <label id="aa">
                                                            <input type="radio" id="probation_period" name="probation_period" value="1" data-checkbox="icheckbox_flat-grey" onclick="probation_change('prob_no')"> no </label>
<?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="prob_yes">
                                            <div class="col-md-3">
                                                <label>From</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="date" name="prob_date_first" onchange="remove_error('prob_date_first')" id="prob_date_first" ata-required="1" class="form-control" placeholder="Completion Date"
                                                           value="<?php
                                                           $originalDate1 = $result_emp_data->probation_period_start_date;
                                                           $newcompletionDate1 = date("Y-m-d", strtotime($originalDate1));
                                                           echo $newcompletionDate1;
//                                                           $originalDate1 = $result_emp_data->probation_period_start_date;
//                                                           $newcompletionDate1 = date("Y-m-d", strtotime($originalDate1));
//                                                           echo $newcompletionDate1;
                                                           ?>">

                                                </div>
                                                <span class="required" style="color: red" id="prob_date_first_error"></span><br>
                                            </div>
                                            <div class="col-md-3">
                                                <label>To</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="Date" name="prob_date_second" onchange="remove_error('prob_date_second')" id="prob_date_second"ata-required="1" class="form-control"
                                                           value="<?php
                                                           $originalDate2 = $result_emp_data->probation_period_end_date;
                                                           $newcompletionDate2 = date("Y-m-d", strtotime($originalDate2));
                                                           echo $newcompletionDate2;
                                                           ?>" placeholder="Completion Date">

                                                </div>
                                                <span class="required" style="color: red" id="prob_date_second_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <div class="icheck-inline">
<?php if ($result_emp_data->training_period_start_date == '0000-00-00 00:00:00') { ?>
                                                        <label>Training Period<span class="error">*</span></label>
                                                        <label id="b">
                                                            <input type="radio" id="training_period" name="training_period" value="0"   data-checkbox="icheckbox_flat-grey" onclick="training_change('train_yes')"> yes </label>
                                                        <label id="bb">
                                                            <input type="radio" id="training_period" name="training_period" value="1" checked data-checkbox="icheckbox_flat-grey" onclick="training_change('train_no')"> no </label>
<?php } else { ?>
                                                        <label>Training Period<span class="error">*</span></label>
                                                        <label id="b">
                                                            <input type="radio" id="training_period" name="training_period" value="0" checked  data-checkbox="icheckbox_flat-grey" onclick="training_change('train_yes')"> yes </label>
                                                        <label id="bb">
                                                            <input type="radio" id="training_period" name="training_period" value="1" data-checkbox="icheckbox_flat-grey" onclick="training_change('train_no')"> no </label>
<?php } ?>
                                                </div>
                                            </div>
                                        </div>



                                        <div id="train_yes">
                                            <div class="col-md-3">
                                                <label>From</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="Date" name="training_period_first" onchange="remove_error('training_period_first')" id="training_period_first"ata-required="1" class="form-control" placeholder="Completion Date"
                                                           value="<?php
                                                           $originalDate3 = $result_emp_data->training_period_start_date;
                                                           $newcompletionDate3 = date("Y-m-d", strtotime($originalDate3));
                                                           echo $newcompletionDate3;
                                                           ?>">

                                                </div>
                                                <span class="required" style="color: red" id="training_period_first_error"></span> <br>
                                            </div>
                                            <div class="col-md-3">
                                                <label>To</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="Date" name="training_period_second" onchange="remove_error('training_period_second')" id="training_period_second"ata-required="1" class="form-control" placeholder="Completion Date"
                                                           value="<?php
                                                           $originalDate4 = $result_emp_data->training_period_end_date;
                                                           $newcompletionDate4 = date("Y-m-d", strtotime($originalDate4));
                                                           echo $newcompletionDate4;
                                                           ?>">

                                                </div>
                                                <span class="required" style="color: red" id="training_period_second_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <a href="<?= base_url() ?>hr_show_employee" class="btn grey-salsa btn-outline"  style="float:right; margin: 2px;">Cancel</a>
                                                <button type="button"  id="edit_emp" name="edit_emp" class="btn green"  style="float:right;  margin: 2px;">Update Employee</button>
                                               <!-- <?php if($result_emp_data->activity_status == 1) {?> -->
											   <!-- <?php }else{} ?> -->
                                                
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_5_2">
                            <div class="form-body">

                                <div class="form-group">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">Attendance Employee</span>
                                    </div>

                                </div>

                                <form method="POST" id="emp_attendance_form" name="emp_attendance_form" class="form-horizontal" novalidate="novalidate">

                                    <div class="form-group">
                                        <!--                                        <div class="col-md-4">
<?php if ($user_type == 2) { ?>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <label class="">Office Name</label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="input-group">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <span class="input-group-addon">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <i class="fa fa-user"></i></span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <select name="firm_name_sal_attnd" onchange='get_employees1()' id="firm_name_sal_attnd" class="form-control m-select2 m-select2-general">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <option value="">Select Branch</option>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </select>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <span class="required" id="firm_name_sal_attnd_error" style="color:red"></span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label class="">Office Name</label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="input-group">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <span class="input-group-addon">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <i class="fa fa-user"></i></span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <select name="firm_name_sal_attnd" onchange='get_employees1()' id="firm_name_sal_attnd" class="form-control m-select2 m-select2-general">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <option value="">Select Branch</option>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </select>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <span class="required" id="firm_name_sal_attnd_error" style="color:red"></span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>


<?php } ?>
                                                                                </div>-->

                                        <!--                                        <div class="col-md-12">
                                                                                    <label class="">Select Employee</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">
                                                                                            <i class="fa fa-user"></i></span>
                                                                                        <select name="select_employee" id="select_employee" onchange="remove_error('designation');check_leave_data();"  class="form-control m-select2 m-select2-general">
                                                                                            <option value="">Select Employee</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <span class="required" id="select_employee_error" style="color:red"></span>
                                                                                </div>-->


                                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $result_emp_data->user_id ?>">
                                        <input type="hidden" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">

                                        <!--<input type="text" name="emp_id_attend_edit" name="emp_id_attend_edit" value="<?php echo $result_emp_data->user_id ?>">-->
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="icheck-inline">
                                                    <label>Punching Applicability </label>
                                                    <label id="c">
                                                        <input type="radio" id="attendance_employee_1" name="attendance_employee" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="applicable_change('applicable_yes')"> Punch In applicable </label>
                                                    <label id="cc">
                                                        <input type="radio" id="attendance_employee_2" name="attendance_employee" value="2" data-checkbox="icheckbox_flat-grey" onclick="applicable_change('applicable_no')"> Punch In Not Applicable </label>
                                                    <!--                                                    <label id="ccc">
                                                                                                            <input type="radio" id="attendance_employee_3" name="attendance_employee" value="3"  data-checkbox="icheckbox_flat-grey" onclick="applicable_change('leave_applicable_yes')"> Punch In Not Applicable & Leave Request </label>-->
                                                    <!--                                                    <label id="cccc">
                                                                                                            <input type="radio" id="attendance_employee_4" name="attendance_employee" value="4"  data-checkbox="icheckbox_flat-grey" onclick="applicable_change('outside_applicable_yes')"> Outside Punch Applicable </label>
                                                                                                        <label id="cccc">
                                                                                                            <input type="radio" id="attendance_employee_5" name="attendance_employee" value="5"  data-checkbox="icheckbox_flat-grey" onclick="applicable_change('shift_applicable_yes')"> Shift Applicable </label>-->


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="applicable_yes" style="display: block;">


                                            <!--Outside punch applicable div--->

                                            <div class="row">
                                                <div class="col-md-3">

                                                    <label><b>Outside Punch Applicable</b></label><br>

                                                    <select name="outside_punch_applicable" id="outside_punch_applicable" onchange="remove_error('outside_punch_applicable');" class="form-control m-select2">
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span class="required" style="color: red" id="outside_punch_applicable_error"></span> <br>
                                                </div>

                                                <div class="col-md-3">

                                                    <label><b>Shift Applicable</b></label><br>

                                                    <select name="shift_applicable" id="shift_applicable" onchange="remove_error('shift_applicable');" class="form-control m-select2">
                                                        <option value="">Select</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>


                                                    <span class="required" style="color: red" id="shift_applicable_error"></span> <br>
                                                </div>
                                            </div>

                                            <!--                                            <div class="col-md-3">

                                                                                            <label><b>Fix In time Applicable:</b></label><br>
                                                                                            <div class="input-group">
                                                                                                <label id="d">
                                                                                                    <input type="radio" id="work_hour_employee_1" name="work_hour_employee" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="work_hour_change('static_yes')">  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;

                                                                                                <label id="dd">
                                                                                                    <input type="radio" id="work_hour_employee_2" name="work_hour_employee" value="2" data-checkbox="icheckbox_flat-grey" onclick="work_hour_change('variable_yes')">  No </label>

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
                                                                                                    <input type="text" name="fixed_time" MaxLength="8" onchange="remove_error('fixed_time')" onkeypress="formatTime(this)" value="<?php echo $result_emp_data->fixed_time; ?>" id="fixed_time" ata-required="1" class="work_fix_time_check form-control" placeholder="HH:MM:SS">

                                                                                                </div> <span class="required" style="color: red" id="fixed_time_error"></span>
                                                                                            </div>

                                                                                            <span class="required" style="color: red" id="training_period_second_error"></span>

                                                                                        </div>-->
                                            <div class="row">
                                                <div class="col-md-12" id="applicable_yes" style="display: block;">
                                                    <!--                                                    <div class="row col-md-12">

                                                                                                            <label><b>Work Schedule:</b></label><br>
                                                                                                            <div class="input-group">
<?php $work_schedule_status_check = $result_emp_data->work_schedule_status; ?>
                                                                                                                <input type="hidden" value="<?php echo $result_emp_data->work_schedule_status; ?>" name="work_schedule_status_hid" id="work_schedule_status_hid">
                                                                                                                <label id="d">
                                                                                                                    <input type="radio" id="work_hour_schedule_1" name="work_hour_schedule" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="work_schedule_change('fix_hour_yes')">  Fix Hour </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                <label id="dd">
                                                                                                                    <input checked type="radio" id="work_hour_schedule_2" name="work_hour_schedule" value="2" data-checkbox="icheckbox_flat-grey" onclick="work_schedule_change('variable_hour_yes')">  Variable Hour </label>

                                                                                                            </div>
                                                                                                            <span class="required" style="color: red" id="work_hour_employee_error"></span> <br>
                                                                                                        </div>-->

                                                    <!--                                                    <div id="fix_hour_yes" class="fix_hour_yes">
                                                                                                            <div class="col-md-12">

                                                                                                                <div class="col-md-3">
                                                                                                                    <label>Fixed Hour</label>
                                                                                                                    <div class="input-group">
                                                                                                                        <span class="input-group-addon">
                                                                                                                            <i class="fa fa-calendar"></i>
                                                                                                                        </span>
                                                                                                                        <input type="text" name="fixed_hour" MaxLength="8" value="<?php echo $result_emp_data->fixed_hour; ?>" onchange="remove_error('fixed_hour')" onkeypress="formatTime(this)" id="fixed_hour" ata-required="1" class="fix_hour_check form-control" placeholder="HH:MM:SS">

                                                                                                                    </div> <span class="required" style="color: red" id="fixed_hour_error"></span>
                                                                                                                </div><div class="col-md-3"></div> <div class="col-md-3"></div> <div class="col-md-3"></div>
                                                                                                            </div>

                                                                                                            <span class="required" style="color: red" id="training_period_second_error"></span>
                                                                                                            <br><br>

                                                                                                            <div class="col-md-3">

                                                                                                                <label><b>Fix In time Applicable:</b></label><br>
                                                                                                                <div class="input-group">
                                                                                                                    <label id="d">
                                                                                                                        <input type="radio" id="work_hour_employee_1" name="work_hour_employee" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="work_hour_change('static_yes')">  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;

                                                                                                                    <label id="dd">
                                                                                                                        <input type="radio" id="work_hour_employee_2" name="work_hour_employee" value="2" data-checkbox="icheckbox_flat-grey" onclick="work_hour_change('variable_yes')">  No </label>

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
                                                                                                                        <input type="text" name="fixed_time" MaxLength="8" onchange="remove_error('fixed_time')" onkeypress="formatTime(this)" value="<?php echo $result_emp_data->fixed_time; ?>" id="fixed_time" ata-required="1" class="work_fix_time_check form-control" placeholder="HH:MM:SS">

                                                                                                                    </div> <span class="required" style="color: red" id="fixed_time_error"></span>
                                                                                                                </div>

                                                                                                                <span class="required" style="color: red" id="training_period_second_error"></span>

                                                                                                            </div>

                                                                                                        </div>-->

                                                    <div class="row col-md-12">

                                                        <label><b>Work Schedule:</b></label><br>
                                                        <input type="hidden" name="work_hour_schedule" id="work_hour_schedule" value="2">
                                                    </div>


                                                    <div id="variable_hour_yes">
                                                        <!--                                                    <div id="variable_hour_yes" style="display:none">-->
                                                        <!--monday-->
                                                        <div class="col-md-3">
                                                            <label><b>Week Day</b></label></div>
                                                        <div class="col-md-3">
                                                            <label><b>Type</b></label></div>
                                                        <div class="col-md-3">
                                                            <label><b>Fixed Hour</b></label></div>
                                                        <div class="col-md-3">
                                                            <label><b>Fix In time Applicable</b></label></div>
<?php if ($result_emp_time != '') { ?>




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
                                                            for ($i = 0; $i < count($result_emp_time); $i++) {
                                                                ?>
                                                                <div class="row col-md-12">
                                                                    <div class="col-md-3">
                                                                        <!--<label>Week Day</label>-->

                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-user"></i></span>
                                                                            <input type ="text" name="week_days<?php echo $cnt; ?>" value="<?php echo $arr_day['day' . $i] ?>" id="week_days<?php echo $cnt; ?>" class="form-control">
                                                                        </div>
                                                                        <span class="required" style="color: red" id="week_days_error"></span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <!--<label>Type</label>-->
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                            <?php
                                                                            $type_check_day = $result_emp_time[$i]->type;
                                                                            ?>
                                                                            <select name="select_schedule_type<?php echo $cnt; ?>" id="select_schedule_type<?php echo $cnt; ?>"   class="form-control m-select2 m-select2-general">
                                                                                <option value=''>Select Type</option>
                                                                                <option value="1" selected="">Working</option>
                                                                                <?php if ($type_check_day == 2) { ?>
                                                                                    <option value="2" selected="">Off</option>
                                                                                <?php } else { ?>
                                                                                    <option value="2">Off</option>
        <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <span class="required" style="color: red" id="select_schedule_type1_error"></span>
                                                                    </div>

        <?php if ($type_check_day == 2) { ?>
                                                                        <div style="display: none" class="col-md-3" id="select_fixed_hour<?php echo $cnt; ?>">
                                                                            <!--<label>Fixed Hour</label>-->
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </span>
                                                                                <input type="hidden" id="id<?php echo $cnt; ?>" name="id<?php echo $cnt; ?>" value="<?php echo $result_emp_time[$i]->id; ?>">
                                                                                <input type="text" name="select_fixed_hour<?php echo $cnt; ?>" value="<?php echo $result_emp_time[$i]->fixed_hour; ?>" id="select_fixed_hour<?php echo $cnt; ?>" ata-required="1" onkeypress="formatTime(this)" class="off_fixed_hour<?php echo $cnt; ?> form-control" placeholder="HH:MM:SS">

                                                                            </div>
                                                                            <span class="required" style="color: red" id="select_fixed_hour1_error"></span>
                                                                        </div>
        <?php } else { ?>
                                                                        <div class="col-md-3" id="select_fixed_hour<?php echo $cnt; ?>">
                                                                            <!--<label>Fixed Hour</label>-->
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </span>
                                                                                <input type="hidden" id="id<?php echo $cnt; ?>" name="id<?php echo $cnt; ?>" value="<?php echo $result_emp_time[$i]->id; ?>">
                                                                                <input type="text" name="select_fixed_hour<?php echo $cnt; ?>" value="<?php echo $result_emp_time[$i]->fixed_hour; ?>" id="select_fixed_hour<?php echo $cnt; ?>" ata-required="1" onkeypress="formatTime(this)" class="off_fixed_hour<?php echo $cnt; ?> form-control" placeholder="HH:MM:SS">

                                                                            </div>
                                                                            <span class="required" style="color: red" id="select_fixed_hour1_error"></span>
                                                                        </div>
        <?php } ?>

                                                                    <!---adding fix in time applicable new-->

                                                                    <?php
                                                                    $in_time_applicable_sts = $result_emp_time[$i]->in_time_applicable_sts;
                                                                    ?>

        <?php if ($type_check_day == 2) { ?>
                                                                        <div class="col-md-3" id="select_in_time_applicable<?php echo $cnt; ?>" style="display:none">
                                                                            <!--                                                                    <label>Fix In time Applicable:</label><br>-->
                                                                            <div class="input-group">

                                                                                <label id="d">
                                                                                    <input type="radio" id="fix_in_appli_sts<?php echo $cnt; ?>" name="fix_in_appli_sts<?php echo $cnt; ?>" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_yes<?php echo $cnt; ?>')" >  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php if ($in_time_applicable_sts == 2) { ?>
                                                                                    <label id="dd">
                                                                                        <input type="radio" id="fix_in_appli_sts<?php echo $cnt; ?>" name="fix_in_appli_sts<?php echo $cnt; ?>" value="2" data-checkbox="icheckbox_flat-grey" onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_no<?php echo $cnt; ?>')" checked="">  No </label>
            <?php } else { ?>
                                                                                    <label id="dd">
                                                                                        <input type="radio" id="fix_in_appli_sts<?php echo $cnt; ?>" name="fix_in_appli_sts<?php echo $cnt; ?>" value="2" data-checkbox="icheckbox_flat-grey" onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_no<?php echo $cnt; ?>')">  No </label>
            <?php } ?>
                                                                            </div>
                                                                        </div>
        <?php } else { ?>
                                                                        <div class="col-md-3" id="select_in_time_applicable<?php echo $cnt; ?>">
                                                                            <!--                                                                    <label>Fix In time Applicable:</label><br>-->
                                                                            <div class="input-group">

                                                                                <label id="d">
                                                                                    <input type="radio" id="fix_in_appli_sts<?php echo $cnt; ?>" name="fix_in_appli_sts<?php echo $cnt; ?>" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_yes<?php echo $cnt; ?>')" >  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php if ($in_time_applicable_sts == 2) { ?>
                                                                                    <label id="dd">
                                                                                        <input type="radio" id="fix_in_appli_sts<?php echo $cnt; ?>" name="fix_in_appli_sts<?php echo $cnt; ?>" value="2" data-checkbox="icheckbox_flat-grey" onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_no<?php echo $cnt; ?>')" checked="">  No </label>
            <?php } else { ?>
                                                                                    <label id="dd">
                                                                                        <input type="radio" id="fix_in_appli_sts<?php echo $cnt; ?>" name="fix_in_appli_sts<?php echo $cnt; ?>" value="2" data-checkbox="icheckbox_flat-grey" onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_no<?php echo $cnt; ?>')">  No </label>
            <?php } ?>
                                                                            </div>
                                                                        </div>
        <?php } ?>


        <?php if ($type_check_day == 2) { ?>

                                                                        <div id="intime_appli_yes">
                                                                            <div class="col-md-3"  style="display:none" id="inapplicable_time_div<?php echo $cnt; ?>">
                                                                                <label>Fixed Time</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon">
                                                                                        <i class="fa fa-calendar"></i>
                                                                                    </span>
                                                                                    <input type="text" name="inapplicable_time<?php echo $cnt; ?>" MaxLength="8" onchange="remove_error('inapplicable_time')" value="10:30:00" onkeypress="formatTime(this)"  id="inapplicable_time<?php echo $cnt; ?>" ata-required="1" class="inappli_fix_time_check<?php echo $cnt; ?> form-control" placeholder="HH:MM:SS">

                                                                                </div> <span class="required" style="color: red" id="fixed_time_error"></span>

                                                                            </div>

                                                                            <span class="required" style="color: red" id="inapplicable_time<?php echo $cnt; ?>_error"></span>

                                                                        </div>
        <?php } else if ($in_time_applicable_sts == 2) { ?>
                                                                        <div id="intime_appli_yes">
                                                                            <div class="col-md-3"  style="display:none" id="inapplicable_time_div<?php echo $cnt; ?>">
                                                                                <label>Fixed Time</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon">
                                                                                        <i class="fa fa-calendar"></i>
                                                                                    </span>
                                                                                    <input type="text" name="inapplicable_time<?php echo $cnt; ?>" MaxLength="8" onchange="remove_error('inapplicable_time')" value="10:30:00" onkeypress="formatTime(this)"  id="inapplicable_time<?php echo $cnt; ?>" ata-required="1" class="inappli_fix_time_check<?php echo $cnt; ?> form-control" placeholder="HH:MM:SS">

                                                                                </div> <span class="required" style="color: red" id="fixed_time_error"></span>

                                                                            </div>

                                                                            <span class="required" style="color: red" id="inapplicable_time<?php echo $cnt; ?>_error"></span>

                                                                        </div>
        <?php } else { ?>
                                                                        <div id="intime_appli_yes">
                                                                            <div class="col-md-3" id="inapplicable_time_div<?php echo $cnt; ?>">
                                                                                <label>Fixed Time</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon">
                                                                                        <i class="fa fa-calendar"></i>
                                                                                    </span>
                                                                                    <input type="text" name="inapplicable_time<?php echo $cnt; ?>" MaxLength="8" onchange="remove_error('inapplicable_time')" value="10:30:00" onkeypress="formatTime(this)"  id="inapplicable_time<?php echo $cnt; ?>" ata-required="1" class="inappli_fix_time_check<?php echo $cnt; ?> form-control" placeholder="HH:MM:SS">

                                                                                </div> <span class="required" style="color: red" id="fixed_time_error"></span>

                                                                            </div>

                                                                            <span class="required" style="color: red" id="inapplicable_time<?php echo $cnt; ?>_error"></span>

                                                                        </div>

        <?php } ?>








                                                                </div>

                                                                <?php
                                                                $cnt ++;
                                                            }
                                                            ?>
                                                        <?php } else { ?>
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
                                                                    <div class="col-md-3">
                                                                        <!--<label>Week Day</label>-->

                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-user"></i></span>
                                                                            <input type ="text" name="week_days<?php echo $cnt; ?>" value="<?php echo $arr_day['day' . $i] ?>" id="week_days<?php echo $cnt; ?>" class="form-control">
                                                                        </div>
                                                                        <span class="required" style="color: red" id="week_days_error"></span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <!--<label>Type</label>-->
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                            <select name="select_schedule_type<?php echo $cnt; ?>" id="select_schedule_type<?php echo $cnt; ?>"   class="form-control m-select2 m-select2-general">
                                                                                <option value=''>Select Type</option>
                                                                                <option value="1" selected="">Working</option>
                                                                                <option value="2" >Off</option>
                                                                            </select>
                                                                        </div>
                                                                        <span class="required" style="color: red" id="select_schedule_type1_error"></span>
                                                                    </div>

                                                                    <div class="col-md-3" id="select_fixed_hour<?php echo $cnt; ?>">
                                                                        <!--<label>Fixed Hour</label>-->
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                            <input type="text" name="select_fixed_hour<?php echo $cnt; ?>" value="08:30:00" id="select_fixed_hour<?php echo $cnt; ?>" ata-required="1" onkeypress="formatTime(this)" class="off_fixed_hour<?php echo $cnt; ?> form-control" placeholder="HH:MM:SS">

                                                                        </div>
                                                                        <span class="required" style="color: red" id="select_fixed_hour1_error"></span>
                                                                    </div>

                                                                    <!---adding fix in time applicable new-->

                                                                    <div class="col-md-3" id="select_in_time_applicable<?php echo $cnt; ?>">
                                                                        <!--                                                                    <label>Fix In time Applicable:</label><br>-->
                                                                        <div class="input-group">
                                                                            <label id="d">
                                                                                <input type="radio" id="fix_in_appli_sts<?php echo $cnt; ?>" name="fix_in_appli_sts<?php echo $cnt; ?>" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_yes<?php echo $cnt; ?>')">  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <label id="dd">
                                                                                <input type="radio" id="fix_in_appli_sts<?php echo $cnt; ?>" name="fix_in_appli_sts<?php echo $cnt; ?>" value="2" data-checkbox="icheckbox_flat-grey" onclick="fix_in_applicable_change<?php echo $cnt; ?>('intime_appli_no<?php echo $cnt; ?>')">  No </label>

                                                                        </div>
                                                                    </div>

                                                                    <div id="intime_appli_yes">
                                                                        <div class="col-md-3" id="inapplicable_time_div<?php echo $cnt; ?>">
                                                                            <label>Fixed Time</label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </span>
                                                                                <input type="text" name="inapplicable_time<?php echo $cnt; ?>" MaxLength="8" onchange="remove_error('inapplicable_time')" value="10:30:00" onkeypress="formatTime(this)"  id="inapplicable_time<?php echo $cnt; ?>" ata-required="1" class="inappli_fix_time_check<?php echo $cnt; ?> form-control" placeholder="HH:MM:SS">

                                                                            </div> <span class="required" style="color: red" id="fixed_time_error"></span>

                                                                        </div>

                                                                        <span class="required" style="color: red" id="inapplicable_time<?php echo $cnt; ?>_error"></span>

                                                                    </div>

                                                                </div>

                                                                <?php
                                                                $cnt ++;
                                                            }
                                                            ?>




<?php } ?>

                                                    </div>

                                                </div>

                                                <!--</div>-->

                                                <!--</div>-->
                                            </div><br>

                                            <!--LAte applicable--->

                                            <div class="row col-md-12">

                                                <label><b>Late Applicable:</b></label><br>
                                                <div class="input-group">
                                                    <label id="d">
                                                        <input type="radio" id="late_applicable_sts1" name="late_applicable_sts" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="late_applicable_change('late_yes')">  Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label id="dd">
                                                        <input type="radio" id="late_applicable_sts2" name="late_applicable_sts" value="0" data-checkbox="icheckbox_flat-grey" onclick="late_applicable_change('late_no')">  No </label>

                                                </div>
                                                <span class="required" style="color: red" id="work_hour_employee_error"></span> <br>
                                            </div>

                                            <div id="late_yes" >


                                                <div class="col-md-1">Every</div>
                                                <div class="col-md-3">
                                                    <!--<label>Select Days Late</label>-->
                                                    <select name="late_salary_days" id="late_salary_days" onchange="remove_error('late_salary_days');"  class="late_salary_count1 form-control m-select2 m-select2-general">
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

                                                    <span class="required" style="color: red" id="late_salary_days_error"></span>

                                                </div>
                                                <div class="col-md-1">Days late</div>
                                                <div class="col-md-3">
                                                    <!--<label>Select type</label>-->
                                                    <select name="late_salary_count" id="late_salary_count" onchange="remove_error('late_salary_count');"  class="late_salary_count1 form-control m-select2 m-select2-general">
                                                        <option value="">Select</option>
                                                        <option value="0.5">Half day</option>
                                                        <option value="1">1 day</option>
                                                        <!--                                                                <option value="2">2 day</option>
                                                                                                                        <option value="3">3 day</option>-->
                                                    </select>

                                                    <span class="required" style="color: red" id="late_salary_count_error"></span>

                                                </div>
                                                <div class="col-md-2">Salary will deduct.</div>

                                                <div class="row col-md-12">
                                                    <div class="col-md-3" id="inapplicable_time_div">
                                                        <label><b>Note:Set the minutes for daily late.</b></label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            <input type="text" name="late_minute_time" MaxLength="8" onchange="remove_error('late_minute_time')" value="" onkeypress="formatTime(this)"  id="late_minute_time" ata-required="1" class="form-control" placeholder="HH:MM:SS">

                                                        </div> <span class="required" style="color: red" id="late_minute_time_error"></span>

                                                    </div>
                                                </div>

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

                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <a href="<?= base_url() ?>hr_show_employee" class="btn grey-salsa btn-outline"  style="float:right; margin: 2px;">Cancel</a>
												<?php if($result_emp_data->activity_status == 1) {?>
													<button type="button"  id="add_attendance" name="add_attendance" class="btn green"  style="float:right;  margin: 2px;">Update</button>
												<?php }else{} ?>

                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="tab-pane" id="tab_5_3">
                            <input type ="hidden" name="user_type" id="user_type" value="<?php echo $user_type; ?>">
                            <div class="form-body">
<!--                           <input type="text" id="user_id" name="user_id" value="<?php echo $result_emp_data->user_id ?>">
                            <input type="text" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">
                                -->
                                <br>
                                <div class="tabbable-custom ">
                                    <ul class="nav nav-tabs ">
                                        <li class="active">
                                            <a href="#tab_6_1" data-toggle="tab" id="tab_6_1_perform" onclick="get_performance_data();"> Performance Allowance</a>
                                        </li>
                                        <li>
                                            <a href="#tab_6_2" data-toggle="tab" id="tab_6_2_loan" onclick="get_loandetailstable();">Staff Loan  </a>
                                        </li>
                                        <li>
                                            <a href="#tab_6_3" data-toggle="tab" id="tab_6_3_salary" onclick="get_sal_type_list();get_salary_data();"> Salary Details </a>
                                        </li>
                                        <li>
                                            <a href="#tab_6_4" id="tab_6_4_deduction" data-toggle="tab" onclick="get_ded_type_list();get_deduction_data();"> Deductions Details </a>
                                        </li>

                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_6_1">
                                            <div class ="form-body">

                                                <form id="performance_allowance" name="performance_allowance" method="POST">
                                                    <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $result_emp_data->user_id ?>">
                                                    <input type="hidden" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">
                                                    <input type="hidden" name="id" id="id">
                                                    <div class="form-group">
                                                        <label class="control-label">Performance  Bonus</label>
                                                        <input type="number" min="1" oninput="validity.valid||(value='');" class="form-control" onchange="remove_error('value_perform')" placeholder="Enter Performance  Bonus" id="value_perform" name="value" value="">
                                                    </div><span class="required" id="value_perform_error" style="color:red"></span>
                                                    <div class="form-group">
                                                        <label>Start Month</label>

                                                        <select name="Month" onchange="remove_error('Month')" id="Month" class="form-control">
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
                                                        <span class="required" id="Month_error" style="color:red"></span>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>Financial Year</label>
                                                        <select name="Fyear"  id="Fyear" onchange="remove_error('Fyear')" class="form-control select2 select2-hidden-accessible">
                                                            <option selected="" value=""></option>
                                                        </select>
                                                        <!--<select id="Fyear" name="Fyear" class="form-control select2 select2-hidden-accessible"></select>-->
                                                        <span class="required" id="Fyear_error" style="color:red"></span>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>Date</label> <input type="Date" onchange="remove_error('Date')" class="form-control" placeholder="Enter Date " id="Date_of_PA" name="Date_of_PA" value="">
                                                        <span class="required" id="Date_error" style="color:red"></span>
                                                    </div>
                                                    <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
													<?php if($result_emp_data->activity_status == 1) {?>
														<button type="button"  class="btn btn-primary" name="add_emp_performance" id="add_emp_performance">Save changes</button>
														<button type="button"  class="btn btn-primary" style="display: none" onclick="update_performance();" name="update_emp_performance" id="update_emp_performance">Update</button>
													<?php }else{} ?>

                                                    <button style="display:none" type="button" class="btn btn-primary"  name="cancel_update_perform" id="cancel_update_perform">Cancel</button>
                                                    <div id="data_tablediv"></div>
                                                </form>
                                            </div>
                                            <hr>
                                            <!--Table of update salary details-->
                                            <div  id="performance_tablediv">

                                            </div>

                                        </div>

                                        <div class="tab-pane" id="tab_6_2">
                                            <div class="form-body">
                                                <form id="staff_loan" name="staff_loan" method="POST">
                                                    <input type="hidden" id="emp_id1" name="emp_id1" value="<?php echo $result_emp_data->user_id ?>">
                                                    <input type="hidden" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">
                                                    <input type="hidden" name="id_loan" id="id_loan">

                                                    <div class="form-group">
                                                        <label>Loan Details</label>
                                                        <input type="text" class="form-control" placeholder="Enter Loan Details" onchange="remove_error('loan_detail')" id="loan_detail" name="loan_detail">
                                                        <span class="required" id="loan_detail_error" style="color:red"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Loan Amount</label>
                                                        <input type="number" min="1" oninput="validateLoanNumber(this);" class="form-control" placeholder="Enter Loan Amount" onchange="remove_error('amount')" value="" id="amount" name="amount">
                                                        <span class="required" id="amount_error" style="color:red"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>EMI Amount</label>
                                                        <input type="number" min="1" oninput="validateEMINumber(this);" class="form-control" placeholder="EnterEMI Amount" onchange="remove_error('EMI_amt')" value="" id="EMI_amt" name="EMI_amt">
                                                        <span class="required" id="EMI_amt_error" style="color:red"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Start Month</label>
                                                        <select name="from_month"  id="from_month"  class="form-control " onchange="remove_error('from_month')">
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
                                                        <span class="required" id="from_month_error" style="color:red"></span>



                                                    </div>
                                                    <div class="form-group">
                                                        <label>Financial Year</label>
                                                        <select name="Fyear2" onchange="remove_error('Fyear2')" id="Fyear2" class="form-control select2 select2-hidden-accessible"></select>
                                                        <span class="required" id="Fyear2_error" style="color:red"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Total Month</label>
                                                        <input type="number" min="1" oninput="validity.valid||(value='');" class="form-control" onchange="remove_error('total_month')" placeholder="Enter Total Month" value="" id="total_month" name="total_month">
                                                        <span class="required" id="total_month_error" style="color:red"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Sanction Date</label>
                                                        <input type="Date" class="form-control" onchange="remove_error('sanction_date')" placeholder="Enter Sanction Date" value="" id="sanction_date" name="sanction_date">
                                                        <span class="required" id="sanction_date_error" style="color:red"></span>
                                                    </div>
													<?php if($result_emp_data->activity_status == 1) {?>
														<button type="button" class="btn btn-primary" name="add_emp_staffloan" id="add_emp_staffloan">Save changes</button>
														<button type="button" class="btn btn-primary" id="update" style="display: none" onclick="update_loan()">Update changes</button>
													<?php }else{} ?>
                                                    <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->

                                                    <button type="button" class="btn btn-primary" id="update_cancel" name="update_cancel" style="display: none" >Cancel</button>
                                                    <!--<button type="button" class="btn btn-warning" onclick="get_data_staffloanhistory()">Show History</button>-->
                                                    <div id="data_staffloanhistory"></div>
                                                </form>

                                            </div>
                                            <hr>
                                            <!--Table of lOAN DETAILS details-->
                                            <div id="loan_tablediv">

                                            </div>

                                        </div>

                                        <div class="tab-pane" id="tab_6_3">
                                            <div class="form-body">



                                                <form id="salary_details" name="salary_details" method="POST">
                                                    <input type="hidden" id="emp_id2" name="emp_id2" value="<?php echo $result_emp_data->user_id ?>">
                                                    <input type="hidden" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">
                                                    <input type="hidden" name="id" id="id_sal">

                                                    <div class="form-group" id="sal_edit_opt">
                                                        <label>Type</label>

                                                        <select name="sal_options" id="sal_options" onchange="remove_error('sal_options')" class="form-control">
                                                            <option></option>
                                                        </select>
                                                        <span class="required" id="sal_options_error" style="color:red"></span>

                                                    </div>



                                                    <div class="form-group">
                                                        <label> Amount</label>

                                                        <input type="number" min="1" oninput="validateNumber(this);"  class="form-control" onchange="remove_error('value_sal')" placeholder="Enter  Amount" id="value_sal" name="value" value="">
                                                        <span class="required" id="value_sal_error" style="color:red"></span>
                                                    </div>
													<?php if($result_emp_data->activity_status == 1) {?>
														<button type="button" class="btn btn-primary" name="update_salary" id="update_salary">Add Salary</button>
														<button style="display:none" type="button" class="btn btn-primary" name="update_indvidual" id="update_indvidual">Update Salary</button>
													<?php }else{} ?>
                                                    <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->

                                                    <button style="display:none" type="button" class="btn btn-primary"  name="cancel_update_indvidual" id="cancel_update_indvidual">Cancel</button>
                                                    <div class="col-md-12" id="button_update" style="display: none" ><button class="btn btn-info" onclick="update_sal();" type="button">update</button><hr></div>

                                                    <!--<button type="button" class="btn btn-warning" onclick="get_view_salary_details()">Show History</button>-->
                                                    <div id="data_salry_Details"></div>
                                                </form>

                                            </div>

                                            <!--Table of update salary details-->
                                            <div  id="salary_tablediv">

                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab_6_4">
                                            <div class="form-body">
                                                <form id="due_details" name="due_details" method="POST">
                                                    <input type="hidden" name="id1" id="id1">
                                                    <input type="hidden" id="emp_id3" name="emp_id3" value="<?php echo $result_emp_data->user_id ?>">
                                                    <input type="hidden" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">

                                                    <div class="form-group" id="ded_edit_opt">
                                                        <label>Tax</label>

                                                        <select name="ded_options"  id="ded_options" onchange="remove_error('ded_options')"  class="form-control">
                                                            <option></option>
                                                        </select>
                                                        <span class="required" id="ded_options_error" style="color:red"></span>

                                                    </div>

                                                    <div class="form-group">
                                                        <label><b>Select type:</b></label><br>
                                                        <div class="input-group">
                                                            <label id="ded">
                                                                <input type="radio" id="deduction_check" name="deduction_check" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="deduction_type_change('fix_deduct_amount_yes')">  Fix  </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <label id="ded">
                                                                <input type="radio" id="deduction_check" name="deduction_check" value="2" data-checkbox="icheckbox_flat-grey" onclick="deduction_type_change('deduct_percentage_yes')">  Percentage </label>

                                                        </div>
                                                        <span class="required" style="color: red" id="deduction_check_error"></span> <br>

                                                    </div>

                                                    <div class="form-group" id="fix_deduct_amount_yes">
                                                        <label> Amount</label>
                                                        <input type="number" class="form-control" min="1" oninput="validateNumberDeduct(this);" placeholder="Enter  Amount" id="value_ded" onkeypress="remove_error('value_ded')" name="value">
                                                        <span class="required" id="value_ded_error" style="color:red"></span>
                                                    </div>

                                                    <div class="form-group" id="deduct_percentage_yes" style="display: none">
                                                        <label> Percentage</label>
                                                        <input type="text" class="form-control" placeholder="Enter  Percentage Value" onkeypress="remove_error('value_deduct_percentage1')"  id="value_deduct_percentage1" name="value_deduct_percentage">
                                                        <span class="required" id="value_deduct_percentage1_error" style="color:red"></span>
                                                    </div>

                                                    <!--                                                    <div class="form-group">
                                                                                                            <label> Amount</label>
                                                                                                            <input type="text" class="form-control" onchange="remove_error('ded_value')" placeholder="Enter  Amount" id="ded_value" name="value" value="">
                                                                                                            <span class="required" id="ded_value_error" style="color:red"></span>
                                                                                                        </div>-->
													<?php if($result_emp_data->activity_status == 1) {?>
														<button type="button" class="btn btn-primary" name="edit_add_duedetails" id="edit_add_duedetails">Save changes</button>
														<button style="display:none" type="button" class="btn btn-primary" name="update_indvidual_due" id="update_indvidual_due">Update Deduction</button>
													<?php }else{} ?>
                                                    <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->

                                                    <button style="display:none" type="button" class="btn btn-primary"  name="cancel_updatedue_indvidual" id="cancel_updatedue_indvidual">Cancel</button>
                                                    <div class="col-md-12" id="button_update1" style="display: none" ><button class="btn btn-info" onclick="update_ded();" type="button">update</button><hr></div>
                                                    <!--<button type="button" class="btn btn-warning" onclick="get_view_Deu()">Show History</button>-->

                                                    <!--                                                   <div  id="ded_tablediv"></div>-->
                                                </form>

                                            </div><hr>

                                            <!--Table of update deduction details-->
                                            <div  id="ded_tablediv">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab_5_8">
                            <div class="container-fluid">
                                <form method="POST" id="uploadDocumentForm" name="uploadDocument" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $result_emp_data->user_id ?>">
                                    <input type="hidden" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="uploadDocumentTable">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">Action</th>
                                                    <th style="width: 30%;">Document Type</th>
                                                    <th style="width: 30%;">Employee Files</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info addRow"><i class="fa fa-plus"></i></button>
                                                    </td>
                                                    <td>
                                                        <select name="document_type[]" class="form-control">
                                                            <option selected disabled value="">Select Type</option>
                                                            <option value="marksheet">Marksheet</option>
                                                            <option value="certification">Certification</option>
                                                            <option value="nda">NDA</option>
                                                            <option value="appointment_letter">Appointment Letter</option>
                                                            <option value="offer_letter">Offer Letter</option>
                                                            <option value="previous_company_documents">Previous Company Documents</option>
                                                            <option value="employee_information_form">Employee Information Form</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="file" name="employee_files[]" class="form-control fileInput" onchange="showUploadDocumentImage(this)">
                                                        <div class="previewContainer" style="position: relative; width: 100px; display: none;">
                                                            <img class="imagePreview" src="" alt="Preview" style="width: 100px; height: 100px; display: none;" />
                                                            <span onclick="removePreview(this)" style="position: absolute; top: -10px; right: -10px; cursor: pointer; background: red; color: white; border-radius: 50%; padding: 0 5px;">X</span>
                                                            <iframe class="docPreview" style="width: 100px; height: 100px; display: none;" frameborder="0"></iframe>
                                                            <div class="iconPreview" style="width: 100px; height: 100px; text-align: center; line-height: 100px; border: 1px solid #ccc;">📄</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <button type="button" class="btn btn-primary" onclick="saveUploadUserDocument(event)"> Save Changes</button>
                                        </table>
                                    </div>
                                </form>
                            </div>

                            <div class="container-fluid">
                                <table id="user_document_datatable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th> SR No. </th>
                                            <th> User Name </th>
                                            <th> Manager Name </th>
                                            <th> Document Type </th>
                                            <th> User Document </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody >

                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="tab-pane" id="tab_5_4">
                            <div class="form-body">
                                <form method="POST" id="emp_exit_form" name="emp_exit_form" class="form-horizontal" novalidate="novalidate">

                                        <div class="row">
                                        <div class="col-md-12">
											<input type="hidden" id="emp_exit_id" name="emp_exit_id" value="<?php echo $result_emp_data->user_id ?>">
												<div class="col-md-4" style="    margin-right: 5px;">
												<div class="form-group">
													<label>Last Day at office:</label>
													<input type="Date" class="form-control" id="last_day" name="last_day">
												</div>
												</div>
												<div class="col-md-4" style="    margin-right: 5px;">
												<div class="form-group">
													<label>Note:</label>
													<textarea class="form-control" name="Note" id="Note" ></textarea>
												</div>
												</div>
												<div class="col-md-4" >
												<div class="form-group">
													<label>Attachment:</label>
													<input type="file" class="form-control-file" id="exit_file" name="exit_file" required>
												</div>
												</div>
											    <div class="col-md-4" id="file_div"></div>
												<div class="col-md-4">
													<div class="form-group">
														<button class="btn btn-primary" id="emp_exit_btn" type="submit"  >Save</button>
													</div>
												</div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_5_6">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assetModal"> Create Assets </button>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subType"> Create Sub Assets </button>
                            </div>
                            <div class="form-body">
                                <form method="POST" id="assets_form" name="assets_form" class="form-horizontal" novalidate="novalidate">
                                    <input type="hidden" name="rowId" id="rowId">
                                    <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $result_emp_data->user_id ?>">
                                    <input type="hidden" name="emp_firm" id="emp_firm" value="<?php echo $emp_firm; ?>">
                                    <div class="form-group" id="addMoreFields">
                                        <br><br>
                                        <div class="col-md-2"><label> Assets Type  :</label>
                                            <select id="assets_type" name="assets_type" class="form-control" onchange="change_assets()">
                                                <option value="">Loading...</option>
                                            </select>
                                            <span class="required" id="accrued_type_error"></span>
                                        </div>

                                        <div class="col-md-2"><label> Assets Details :</label>
                                            <select id="assets_details" name="assets_details" class="form-control" onchange="change_details_assets()">
                                                <option value="">Loading...</option>
                                            </select>
                                            <span class="required" id="assets_details_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label> Brand Name </label>
                                            <input type="text" name="brand_name" id="brand_name" onclick="stringValidation(event)" placeholder="please enter brand name" class="form-control" onchange="remove_error('brand_name')" >
                                            <span class="required" id="brand_name_error" style="color:red"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label> Model Name </label>
                                            <input type="text" name="model_name" id="model_name" onclick="stringValidation(event)" class="form-control" placeholder="please enter model name" onkeyup="remove_error('model_name')" >
                                            <span class="required" id="model_name_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label> Specification  </label>
                                            <input type="text" name="specification" id="specification" onclick="stringValidation(event)" placeholder="please enter specification" class="form-control" onkeyup="remove_error('specification')" >
                                            <span class="required" id="specification_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label> Descrption   </label>
                                            <input type="text" name="descrption" id="descrption" onclick="stringValidation(event)" class="form-control" placeholder="please enter descrption" onkeyup="remove_error('descrption')">
                                            <span class="required" id="descrption_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label> System Vendor </label>
                                            <input type="text" name="vendor" id="vendor" onclick="stringValidation(event)" class="form-control" placeholder="please enter vendor" onkeyup="remove_error('vendor')" >
                                            <span class="required" id="vendor_error"></span>
                                        </div>

                                        <div id="extra_fields_container"></div>

                                        <div class="col-md-4">
                                            <label> Perchase Or Manufacturing Date  </label>
                                            <input type="date"  class="form-control" id="pur_mnf" name="pur_mnf" onclick="stringValidation(event)" placeholder="please enter perchase or manufacturing date" onkeyup="remove_error('pur_mnf')" >
                                            <span class="required" id="pur_mnf_error"></span>
                                        </div>

                                        <?php if($result_emp_data->activity_status == 1) {?>
                                            <button type="button" class="btn btn-primary" id="add_assets_details" name="add_assets_details" style="margin-top: 2.5rem;"> Save Changes</button>
                                        <?php }else{} ?>
                                    </div>
                                </form>
                            </div>

                            <div class="container-fluid">
                                <table id="assets_datatable" class="table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>SR No. </th>
                                            <th> Assets Type </th>
                                            <th> Assets Details  </th>
                                            <th> Brand Name </th>
                                            <th> Model Name </th>
                                            <th> Specification </th>
                                            <th> Description</th>
                                            <th> Purchase Date </th>
                                            <th> Created By </th>
                                            <th> Status </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                                                                                                                                                                                                
                        <div class="tab-pane" id="tab_5_7">
                            <div class="form-body">
                                <form action="#" role="form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light bordered">
                                                <div class="row"><br>
                                                    <div class="col-md-12 table-scrollable">
                                                       <!-- <table class="table table-striped table-bordered table-hover dtr-inline  dataTable no-footer" id="sample1" role="grid" aria-describedby="sample_1_info">
                                                            <thead>
                                                            <tr role="row">
                                                                <th style="text-align:center" scope="col">Type</th>
                                                                <th style="text-align:center" scope="col">No of days</th>
                                                                <th style="text-align:center" scope="col">Carry Forward</th>
                                                                <th style="text-align:center" scope="col">Avail Leaves</th>
                                                                <th style="text-align:center" scope="col">Leave in Approve</th>
                                                                <th>Remaining  Leaves </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="leaveDetails">


                                                            </tbody>
                                                        </table>-->
														<div id="leaveDetails"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_5_5">
                            <div class="form-body">
                                <form method="POST" id="leaveconfigure_emp" name="leaveconfigure_emp" class="form-horizontal" novalidate="novalidate">
                                    <input type="hidden" id="emp_id1" name="emp_id1" value="<?php echo $result_emp_data->user_id ?>">
                                    </div>
                                    <div class="form-group">
                                    <div class="form-group">
									<div class="row">
                                        <div id="leave_config_msg" class="col-md-12 text-danger"></div>
                                            <div class="form-group">
										<div class="col-md-4"><label>Total Leaves :</label>

                                            <input type="text"  class="form-control" id="total_leaves" name="total_leaves" onkeyup="remove_error('total_leaves')">
                                            <span class="required" id="total_leaves_error"></span>

                                        </div>
                                                <div class="col-md-4"><label>Total Balance :</label>

                                                    <input type="text"  class="form-control" id="leave_balance" name="leave_balance" onkeyup="remove_error('leave_balance')">
                                                    <span class="required" id="leave_balance_error"></span>

                                                </div>
										<div class="col-md-2"><label>Accrued Periodically :</label><br>

                                            <label class="mtradio"><input type="radio" value="1"  id="acc_per0" name="acc_per" onclick="get_data(1)">Yes </label>&nbsp; <label class="mtradio ">
											<input type="radio" value="0" id="acc_per1" 	 name="acc_per" onclick="get_data(2)">No</label></div>

											<div id="p_div" style="display:none">
												<div class="col-md-2"><label>Select Period :</label>
											<select id="accrued_period" name="accrued_period" class="form-control">
											<option value="0">Select period</option>
														<option value="5">Weekly</option>
											<option value="1">Monthly</option>
                                            <!-- <option value="2">Quarterly</option>-->
                                            <!-- <option value="4">Yearly</option>-->
											</select>
													<span class="required" id="accrued_period_error"></span>
												</div>
												<div class="col-md-2"><label>Leave should be accured From? :</label>
													<select id="accrued_type" name="accrued_type" class="form-control">
														<option value="0">Select Type</option>
														<option value="1">PL</option>
														<option value="2">CL</option>
														<option value="3">SL</option>
													</select>
													<span class="required" id="accrued_type_error"></span>
											</div>
											<div class="col-md-2"><label>How many leave should accured?</label>
											<input type="text"  class="form-control" id="accr_leave" name="accr_leave" onkeyup="remove_error('total_leaves')">
													<span class="required" id="accr_leave_error"></span>
											</div>
											</div>

                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-2"><span style="font-weight:bold;">Types </span></div>
                                                <div class="col-md-2"><span style="font-weight:bold;">No. of days </span></div>
                                                <div class="col-md-2"><span style="font-weight:bold;">Balance Leave </span></div>
                                                <div class="col-md-2"><span style="font-weight:bold;">Carry Forward </span></div>
                                                <!-- <div class="col-md-2"><span style="font-weight:bold;">Leave Approve</span></div>-->
                                                <!--<div class="col-md-2"><span style="font-weight:bold;">Days past/next</span></div>-->
                                            </div><br>
                                            <?php for($i=1; $i<=7; $i++): ?>
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-1"><span style="font-weigth:bold;">Type <?= $i; ?> :</span></div>
                                                <?php if ($i == 1 || $i == 2 || $i == 3): ?>
                                                <div class="col-md-2">
                                                    <input type="text" readonly id="leave_type<?= $i; ?>" name="leave_type<?= $i; ?>" onkeyup="remove_error('leave1')" class="form-control" placeholder="First Leave Type">
                                                </div>
                                                <?php else: ?>
                                                    <div class="col-md-2">
                                                        <input type="text" id="leave_type<?= $i; ?>" name="leave_type<?= $i; ?>" onkeyup="remove_error('leave1')" class="form-control" placeholder="First Leave Type">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-md-2">
                                                    <input type="number" min="1" id="numofdays<?= $i; ?>" name="numofdays<?= $i; ?>" onkeyup="remove_error('leave1')" class="form-control" placeholder="No Of Days">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" min="1" id="balance_leave_<?= $i; ?>" name="balance_leave_<?= $i; ?>" onkeyup="remove_error('leave1')" class="form-control" placeholder="Balance Leave">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="mtradio"> <input type="radio" value="1" checked id="CF1" name="CF<?= $i; ?>" onkeyup="remove_error('leave1')">Yes </label>
                                                    &nbsp;
                                                    <label class="mtradio"> <input type="radio" value="0" id="CF<?= $i; ?>" name="CF<?= $i; ?>" onkeyup="remove_error('leave1')">No</label>
                                                </div>
                                                <div class="col-md-1" id="leave1" name="leave1">
                                                    <span class="required" id="leave_type1_error"></span>
                                                </div>
                                            </div><br>
                                            <?php endfor; ?>
                                        </div>
							<?php if($result_emp_data->activity_status == 1) {?>
								<!--<button type="button" class="btn btn-primary" id="add_leave_emp_btn" onclick="add_levae_emp()" >Save Changes</button>-->
							<?php }else{} ?>
                            <button type="button" class="btn btn-primary" id="add_leave_emp_btn" onclick="add_levae_emp()" >Save Changes</button>
                                    </div>

                                    <!---->

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



            <!--ATTENDANCE EMPLOYEE-->
            <!--<div class="row">-->


            <div class="row"></div>
            <br><br>

            <div class="loading" id="loaders7" style="display:none;"></div>
            </form>
<?php $this->load->view('human_resource/footer'); ?>
        </div>

    </div>

</div>
</div>



<!-- Create Asset Type And Sub Asset Types Start -->
<!-- asset type modal -->
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="modal-title" id="assetModalLabel">Create Asset Type Details</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
            </div>
            <form id="subAssetForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <label class="modal-title mt-3">Asset Type</label>
                    <input type="text" name="asset_type" id="asset_type" class="form-control" placeholder="Enter asset type detail">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitAssetTypeButton" onclick="submitAssetTypeData()">Save Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- sub asset type modal -->
<div class="modal fade" id="subType" tabindex="-1" aria-labelledby="subTypeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex; justify-content: space-between;">
                    <h5 class="modal-title" id="subTypeLabel">Create Sub Asset Type Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
            </div>
            <form id="subAssetForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <label class="modal-title">Asset Type</label>
                    <select name="asset_id" id="asset_id" class="form-control">
                        <option value="">Loading...</option>
                    </select>
                    <br>
                    <label class="modal-title mt-3">Sub Asset Type</label>
                    <input type="text" name="sub_asset_type" id="sub_asset_type" class="form-control" placeholder="Enter asset type detail">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitSubAssetTypeButton" onclick="submitSubAssetTypeData()">Save Sub Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Create Asset Type And Sub Asset Types End -->


<!-- Show asset detail button start -->
<div class="modal fade" id="showAssetData" tabindex="-1" aria-labelledby="showAssetDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal-lg for larger view -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showAssetDataLabel">Asset Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            <div class="modal-body">
                <div id="fetchDetail" class="fetchDetail ">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Save button if needed -->
            </div>
        </div>
    </div>
</div>
<!-- Show asset detail button end -->




<!-- Show asset detail button -->
<div class="modal fade" id="editAssetData" tabindex="-1" aria-labelledby="editAssetDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAssetDataLabel">Asset Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            <div class="modal-body">
                <div id="fetchEditData" class="fetchEditData">
                    <form method="POST" id="update_assets_form" name="assets_form" class="form-horizontal" novalidate="novalidate">
                        <input type="hidden" name="rowId" id="update_row_id">
                        <input type="hidden" id="update_emp_id" name="emp_id" value="<?php echo $user_id['emp_id'] ?>">
                        <input type="hidden" name="user_id" id="update_user_id">
                        <div class="form-group" id="updateMoreFields">
                            <!-- Assets Type -->
                            <div class="col-md-4">
                                <label>Assets Type :</label>
                                <select id="update_assets_type" name="assets_type" class="form-control">
                                    <option value="">Select Asset Type</option>
                                </select>
                                <span class="required" id="update_accrued_type_error"></span>
                            </div>

                            <!-- Assets Details -->
                            <div class="col-md-4">
                                <label>Assets Details :</label>
                                <select id="update_assets_details" name="assets_details" class="form-control">
                                    <option value="">Select Details</option>
                                </select>
                                <span class="required" id="update_assets_details_error"></span>
                            </div>

                            <div class="col-md-4">
                                <label>Brand Name</label>
                                <input type="text" name="brand_name" value='' id="update_brand_name" class="form-control" placeholder="please enter brand name">
                                <span class="required" id="brand_name_error" style="color:red"></span>
                            </div>

                            <div class="col-md-4">
                                <label>Model Name</label>
                                <input type="text" name="model_name" value='' id="update_model_name" class="form-control" placeholder="please enter model name">
                                <span class="required" id="model_name_error"></span>
                            </div>

                            <div class="col-md-4">
                                <label>Specification</label>
                                <input type="text" name="specification" value='' id="update_specification" class="form-control" placeholder="please enter specification">
                                <span class="required" id="specification_error"></span>
                            </div>

                            <div class="col-md-4">
                                <label>Description</label>
                                <input type="text" name="descrption" value='' id="update_descrption" class="form-control" placeholder="please enter description">
                                <span class="required" id="descrption_error"></span>
                            </div>

                            <div class="col-md-4">
                                <label>Vendor</label>
                                <input type="text" name="vendor" value='' id="update_vendor" class="form-control" placeholder="please enter vendor">
                                <span class="required" id="vendor_error"></span>
                            </div>
                            
                            <div id="update_extra_fields_container"></div>
                            
                            <div class="col-md-4">
                                <label>Purchase / Manufacturing Date</label>
                                <input type="date" value='' class="form-control" id="update_pur_mnf" name="pur_mnf">
                                <span class="required" id="pur_mnf_error"></span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="update_assets_submit" onclick="update_assets_submit_btn(event)">Save Changes</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Show asset detail button -->





<!-- END FORM-->
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<!--<script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>-->
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo base_url() . "assets/"; ?>pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/script.js" type="text/javascript"></script>

<script>

<?php if ($result_emp_data->probation_period_start_date == '0000-00-00 00:00:00') {
    ?>
                                            document.getElementById("prob_yes").style.display = "none";
<?php } else { ?>
                                            document.getElementById("prob_yes").style.display = "block";
<?php }
?>

<?php if ($result_emp_data->training_period_start_date == '0000-00-00 00:00:00') {
    ?>
                                            document.getElementById("train_yes").style.display = "none";
<?php } else { ?>
                                            document.getElementById("train_yes").style.display = "block";
<?php }
?>
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

                                        var validNumber = new RegExp(/^\d*\.?\d*$/);
                                        var lastValid = document.getElementById("value_sal").value;
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

                                        var optionValues = [];
                                        $('#star_rating option').each(function () {
                                            if ($.inArray(this.value, optionValues) > -1) {
                                                $(this).remove()
                                            } else {
                                                optionValues.push(this.value);
                                            }
                                        });

                                        $("#ot_applicable_sts").change(function () {
                                            // alert("1");
                                            if (this.value == '1')

                                            {
                                                $("#salary_calcu2").prop("checked", true);
                                                $("#salary_calcu1").prop("disabled", true);
                                            } else {
                                            // alert("2");
                                                $("#salary_calculation").val('');
                                                $("#salary_calcu1").prop("disabled", false);
                                                $("#salary_calcu2").prop("disabled", false);
                                            }

                                        });

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
                                                        for (i = 0; i < data.length; i++)
                                                        {
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
														alert();

                                                        var data = result.emp_data;
                                                    // var data_hr = result.hr_data;
                                                        var data_hr = result.hr_data_hq;
                                                        console.log(data_hr);
                                                        console.log(data);
                                                    //     $(".senior_emp1").append(data);
                                                        var ele = document.getElementById('senior_emp');
                                                    // ele.innerHTML = "<option value=''>Select Senior Employee</option>";
                                                        innerHTML = "<option value='" + data_hr.user_id + "'>" + data_hr.user_name + "</option>";
                                                        for (i = 0; i < data.length; i++)
                                                        {
                                                            // POPULATE SELECT ELEMENT WITH JSON.
                                                            innerHTML = innerHTML +
                                                                    '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
                                                        }
														console.log(innerHTML);
														$("#senior_emp").append(innerHTML);


                                                    }
                                                }
                                            });

                                        }


                                        function get_sal_type_list() {
                                            var firm_id = document.getElementById('emp_firm').value;
                                            console.log(firm_id);
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("SalaryInfoController/get_sal_type_list") ?>",
                                                dataType: "JSON",
                                                async: false,
                                                cache: false,
                                                data: {firm_id: firm_id},
                                                success: function (result) {
                                                    var data = result.sal_options;
                                                    console.log(data);
                                                    if (result.code == 200) {
                                                        $('#sal_options').html(data);
                                                        $('.sal_options1').append(data);
                                                    } else {
                                                        $('#sal_options').html("");
                                                        $('.sal_options1').html("");
                                                    }
                                                },
                                            });
                                        }

                                        function get_ded_type_list() {
                                            var firm_id = document.getElementById('emp_firm').value;
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
                                                        $('.ded_options1').append(data);
                                                    } else {
                                                        $('#ded_options').html("");
                                                        $('.ded_options1').html("");
                                                    }
                                                },
                                            });
                                        }

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

                                        function fix_in_applicable_change1(id)
                                        {
                                            var x = document.getElementById("inapplicable_time_div1");
                                            if (id === 'intime_appli_yes1') {

                                                x.style.display = "block";

                                            } else if (id === 'intime_appli_no1')
                                            {
                                                x.style.display = "none";
                                                $('.inappli_fix_time_check1').val('');
                                            }
                                        }

                                        function fix_in_applicable_change2(id)
                                        {
                                            var x = document.getElementById("inapplicable_time_div2");
                                            if (id === 'intime_appli_yes2') {

                                                x.style.display = "block";

                                            } else if (id === 'intime_appli_no2')
                                            {
                                                x.style.display = "none";
                                                $('.inappli_fix_time_check2').val('');
                                            }
                                        }

                                        function fix_in_applicable_change3(id)
                                        {
                                            var x = document.getElementById("inapplicable_time_div3");
                                            if (id === 'intime_appli_yes3') {

                                                x.style.display = "block";

                                            } else if (id === 'intime_appli_no3')
                                            {
                                                x.style.display = "none";
                                                $('.inappli_fix_time_check3').val('');
                                            }
                                        }

                                        function fix_in_applicable_change4(id)
                                        {
                                            var x = document.getElementById("inapplicable_time_div4");
                                            if (id === 'intime_appli_yes4') {

                                                x.style.display = "block";

                                            } else if (id === 'intime_appli_no4')
                                            {
                                                x.style.display = "none";
                                                $('.inappli_fix_time_check4').val('');
                                            }
                                        }

                                        function fix_in_applicable_change5(id)
                                        {
                                            var x = document.getElementById("inapplicable_time_div5");
                                            if (id === 'intime_appli_yes5') {

                                                x.style.display = "block";

                                            } else if (id === 'intime_appli_no5')
                                            {
                                                x.style.display = "none";
                                                $('.inappli_fix_time_check5').val('');
                                            }
                                        }

                                        function fix_in_applicable_change6(id)
                                        {
                                            var x = document.getElementById("inapplicable_time_div6");
                                            if (id === 'intime_appli_yes6') {

                                                x.style.display = "block";

                                            } else if (id === 'intime_appli_no6')
                                            {
                                                x.style.display = "none";
                                                $('.inappli_fix_time_check6').val('');
                                            }
                                        }

                                        function fix_in_applicable_change7(id)
                                        {
                                            var x = document.getElementById("inapplicable_time_div7");
                                            if (id === 'intime_appli_yes7') {

                                                x.style.display = "block";

                                            } else if (id === 'intime_appli_no7')
                                            {
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

                                        function applicable_change(id)
                                        {
                                            var x = document.getElementById("applicable_yes");
                                            if (id === 'applicable_yes') {

                                                x.style.display = "block";

                                                $('#work_hour_schedule_1').prop('checked', true);
                                                work_schedule_change('fix_hour_yes');
                                            } else if (id === 'applicable_no')
                                            {
                                                x.style.display = "none";
                                                $('.work_fix_time_check').val('');
                                                $('.fix_hour_check ').val('');
                                            } else if (id === 'applicable_no')
                                            {
                                                x.style.display = "none";
                                            }
                                            //                                            else if (id === 'leave_applicable_yes')
                                            //                                            {
                                            //                                                x.style.display = "none";
                                            //                                                $('.work_fix_time_check').val('');
                                            //                                                $('.fix_hour_check ').val('');
                                            //                                            }
                                            //                                            else if (id === 'outside_applicable_yes')
                                            //                                            {
                                            //                                                x.style.display = "none";
                                            //                                                $('.work_fix_time_check').val('');
                                            //                                                $('.fix_hour_check ').val('');
                                            //                                            }
                                            else if (id === 'variable_hour_yes')
                                            {
                                                x.style.display = "none";
                                                $('.work_fix_time_check').val('');
                                                $('.fix_hour_check ').val('');
                                            }
                                            //                                            else if (id === 'shift_applicable_yes')
                                            //                                            {
                                            //                                                x.style.display = "none";
                                            //                                                $('.work_fix_time_check').val('');
                                            //                                                $('.fix_hour_check ').val('');
                                            //                                            }

                                        }

                                        function work_hour_change(id)
                                        {
                                            var x = document.getElementById("static_yes");
                                            if (id === 'static_yes') {

                                                x.style.display = "block";
                                            } else if (id === 'variable_yes')
                                            {
                                                x.style.display = "none";
                                                $('.work_fix_time_check').val('');
                                            }
                                        }

                                        function work_schedule_change(id)
                                        {
                                            var x = document.getElementById("fix_hour_yes");
                                            var y = document.getElementById("variable_hour_yes");
                                            if (id === 'fix_hour_yes') {

                                                x.style.display = "block";
                                                y.style.display = "none";
                                            } else if (id === 'variable_hour_yes')
                                            {
                                                y.style.display = "block";
                                                x.style.display = "none";
                                                $('.fix_hour_check').val('');
                                            }
                                        }

                                        function late_applicable_change(id)
                                        {
                                            var x = document.getElementById("late_yes");
                                            if (id === 'late_yes') {

                                                x.style.display = "block";

                                            } else if (id === 'late_no')
                                            {
                                                x.style.display = "none";
                                                $('.late_salary_count1').val('');
                                            }
                                        }

                                        //for deduction fix & percentage

                                        function deduction_type_change(id)
                                        {
                                            var x = document.getElementById("fix_deduct_amount_yes");
                                            var y = document.getElementById("deduct_percentage_yes");
                                            if (id === 'fix_deduct_amount_yes') {

                                                x.style.display = "block";
                                                y.style.display = "none";
                                                $('#value_deduct_percentage').val('');

                                            } else if (id === 'deduct_percentage_yes')
                                            {
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
                                        //                                        });

                                        $("#edit_emp").click(function () {
                                            var $this = $(this);
                                            $this.button('loading');
                                            setTimeout(function () {
                                                $this.button('reset');
                                            }, 2000);
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/edit_emp") ?>",
                                                dataType: "json",
                                                data: $("#addemp").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Employee Edited successfully');
                                                        location.reload();
                                                        //                                                                        $(location).attr('href', '<?= base_url("hr_show_employee") ?>')
                                                    } else if (result.result === '2') {
                                                        alert('fetching error');
                                                    } else if (result.result === '3') {
                                                        alert('query error');
                                                    } else if (result.result === '4') {
                                                        alert('something went wrong');
                                                    } else {
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
                                                    } else {
                                                        alert('Unexpected error.');
                                                    }
                                                }
                                            });
                                        });
                                        $("#update_salary").click(function () {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/add_salary") ?>",
                                                dataType: "json",
                                                data: $("#salary_details").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Employee Salary Add successfully');
                                                        $("#salary_details")[0].reset();
                                                        //                                                                        $(location).attr('href', '<?= base_url("hr_show_employee") ?>')
                                                        get_salary_data();
                                                    } else if (result.status === 200) {
                                                        alert("For this employee salary type is already Set.You can Update the salary");
                                                        $("#update_indvidual").show();
                                                        $("#cancel_update_indvidual").show();
                                                        $("#update_salary").hide();
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


                                        $(function () {
                                            $('#cancel_update_indvidual').click(function () {
                                                $("#salary_details")[0].reset();
                                                $("#update_salary").show();
                                                $("#update_indvidual").hide();
                                                $("#cancel_update_indvidual").hide();
                                            });
                                        });

                                        $("#update_indvidual").click(function () {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/add_salary_update") ?>",
                                                dataType: "json",
                                                data: $("#salary_details").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Employee Salary Updated successfully');
                                                        $("#salary_details")[0].reset();
                                                        //                                                                        $(location).attr('href', '<?= base_url("hr_show_employee") ?>')
                                                        get_salary_data();
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




                                        //table data salary

                                        function get_salary_data() {
                                            var firm_name = document.getElementById("emp_firm").value;
                                            var user_id = document.getElementById("emp_id2").value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_sal_info") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {firm_name: firm_name, user_id: user_id},
                                                success: function (result) {
                                                    var data = result.result_sal;
                                                    if (result.status == 200) {
                                                        $('#salary_tablediv').html(data);
                                                        //                                                                        $('#sal_table').DataTable();
                                                    } else {

                                                        $('#salary_tablediv').html(data);
                                                        //                                                                        $('#sal_table').DataTable();
                                                    }
                                                },
                                            });
                                        }

                                        //delete individual salary
                                        function delete_saltype(id)
                                        {
                                            var result = confirm("Do You Want to delete?");
                                            if (result) {
                                                $.ajax({
                                                    url: '<?= base_url("Employee/delete_saltype") ?>',
                                                    type: "POST",
                                                    data: {id: id},
                                                    cache: false,
                                                    async: false,
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.status == 200) {
                                                            alert(success.body);
                                                            get_salary_data();
                                                        } else {
                                                            alert(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        alert("something went to wrong");
                                                    }
                                                });
                                            } else {

                                            }

                                        }

                                        //delete individual deduction
                                        function delete_dedtype(id)
                                        {
                                            var result = confirm("Do You Want to delete?");
                                            if (result) {
                                                $.ajax({
                                                    url: '<?= base_url("Employee/delete_dedtype") ?>',
                                                    type: "POST",
                                                    data: {id: id},
                                                    cache: false,
                                                    async: false,
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.status == 200) {
                                                            alert(success.body);
                                                            get_deduction_data()
                                                        } else {
                                                            alert(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        alert("something went to wrong");
                                                    }
                                                });
                                            } else {

                                            }

                                        }

                                        //Get details individual salary
                                        function edit_saltype_btn(id)
                                        {
                                            $("#update_salary").hide();
                                            $("#sal_edit_opt").hide();
                                            $("#button_update").show();
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_sal_details") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {id: id},
                                                success: function (result) {
                                                    var data = result.result;
                                                    console.log(data);
                                                    if (result.code == 200) {

                                                        $("#value_sal").val(data['value']);
                                                        $("#sal_options").val(data['payroll_id']);
                                                        $("#id_sal").val(id);
                                                    } else {

                                                    }
                                                },
                                            });
                                        }

                                        //update individual salary
                                        function update_sal()
                                        {
                                            $.ajax({
                                                url: '<?= base_url("Employee/UpadteSalInfo") ?>',
                                                type: "POST",
                                                data: $("#salary_details").serialize(),
                                                success: function (success) {
                                                    success = JSON.parse(success);
                                                    if (success.message === "success") {
                                                        alert(success.body);
                                                        //                                                              location.reload();
                                                        get_salary_data();
                                                        $("#salary_details")[0].reset();
                                                    } else {
                                                        alert(success.body);
                                                        //                                                             location.reload();
                                                        get_salary_data();
                                                        $("#salary_details")[0].reset();
                                                        //  toastr.error(success.body); //toster.error
                                                    }
                                                },
                                                error: function (error) {
                                                    toastr.error(success.body);
                                                    console.log(error);
                                                    errorNotify("something went to wrong");
                                                }
                                            });
                                        }


                                        //table data deduction

                                        function get_deduction_data() {
                                            var firm_name = document.getElementById("emp_firm").value;
                                            var user_id = document.getElementById("emp_id3").value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_deduction_info") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {firm_name: firm_name, user_id: user_id},
                                                success: function (result) {
                                                    var data = result.result_sal;
                                                    console.log(data);
                                                    if (result.status == 200) {
                                                        $('#ded_tablediv').html(data);
                                                        //                                                                        $('#ded_table').DataTable();
                                                    } else {
                                                        $('#ded_tablediv').html("");
                                                    }
                                                },
                                            });
                                        }


                                        function edit_dedtype_btn(id)
                                        {
                                            $("#button_update1").show();
                                            $("#edit_add_duedetails").hide();
                                            $("#ded_edit_opt").hide();
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_ded_details") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {id: id},
                                                success: function (result) {
                                                    var data = result.result;
                                                    if (result.code == 200) {

                                                        $("#ded_value").val(data['value']);
                                                        $("#id1").val(id);
                                                    } else {

                                                    }
                                                },
                                            });
                                        }

                                        function update_ded()
                                        {
                                            $.ajax({
                                                url: '<?= base_url("Employee/UpadteDedInfo") ?>',
                                                type: "POST",
                                                data: $("#due_details").serialize(),
                                                success: function (success) {
                                                    success = JSON.parse(success);
                                                    if (success.message === "success") {
                                                        alert(success.body);
                                                        get_deduction_data();
                                                        $("#due_details")[0].reset();
                                                    } else {
                                                        alert(success.body);
                                                        get_deduction_data();
                                                        $("#due_details")[0].reset();
                                                        //  toastr.error(success.body); //toster.error
                                                    }
                                                },
                                                error: function (error) {
                                                    toastr.error(success.body);
                                                    console.log(error);
                                                    errorNotify("something went to wrong");
                                                }
                                            });
                                        }

                                        $("#add_emp_performance").click(function () {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/add_performance_info") ?>",
                                                dataType: "json",
                                                data: $("#performance_allowance").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Employee Performance added successfully');
                                                        $("#performance_allowance")[0].reset();
                                                        get_performance_data();
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
                                        //Table display for Employee Performance

                                        function get_performance_data() {
                                            var firm_name = document.getElementById("emp_firm").value;
                                            var user_id = document.getElementById("emp_id").value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_performance_info") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {firm_name: firm_name, user_id: user_id},
                                                success: function (result) {
                                                    var data = result.result_sal;
                                                    if (result.status == 200) {
                                                        $('#performance_tablediv').html(data);
                                                        //                                                                        $('#performance_table').DataTable();
                                                    } else {

                                                        $('#performance_tablediv').html(data);
                                                        //                                                                        $('#performance_table').DataTable();
                                                    }
                                                },
                                            });
                                        }

                                        //delete individual salary
                                        function delete_performance(id)
                                        {
                                            var result = confirm("Do You Want to delete?");
                                            if (result) {
                                                $.ajax({
                                                    url: '<?= base_url("Employee/delete_performance") ?>',
                                                    type: "POST",
                                                    data: {id: id},
                                                    cache: false,
                                                    async: false,
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.status == 200) {
                                                            alert(success.body);
                                                            get_performance_data();
                                                        } else {
                                                            alert(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        alert("something went to wrong");
                                                    }
                                                });
                                            } else {

                                            }

                                        }

                                        //get performance details

                                        function edit_performance_btn(id)
                                        {
                                            $("#add_emp_performance").hide();
                                            $("#update_emp_performance").show();
                                            $("#cancel_update_perform").show();
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_performance_details") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {id: id},
                                                success: function (result) {
                                                    var data = result.result;
                                                    console.log(data);
                                                    if (result.code == 200) {

                                                        $("#value_perform").val(data['value']);
                                                        $("#Month").val(data['Month']);
                                                        $("#Date_of_PA").val(data['Date_of_PA']);
                                                        $("#FYear").val(data['FYear']);
                                                        $("#id").val(id);
                                                    } else {

                                                    }
                                                },
                                            });
                                        }

                                        //update individual performance
                                        function update_performance()
                                        {
                                            $.ajax({
                                                url: '<?= base_url("Employee/UpadtePerformanceInfo") ?>',
                                                type: "POST",
                                                data: $("#performance_allowance").serialize(),
                                                success: function (success) {
                                                    success = JSON.parse(success);
                                                    if (success.message === "success") {
                                                        alert(success.body);
                                                        // location.reload();
                                                        get_performance_data();
                                                        $("#performance_allowance")[0].reset();
                                                    } else {
                                                        alert(success.body);
                                                        // location.reload();
                                                        get_performance_data();
                                                        $("#performance_allowance")[0].reset();
                                                        //  toastr.error(success.body); //toster.error
                                                    }
                                                },
                                                error: function (error) {
                                                    toastr.error(success.body);
                                                    console.log(error);
                                                    errorNotify("something went to wrong");
                                                }
                                            });
                                        }

                                        $(function () {
                                            $('#cancel_update_perform').click(function () {
                                                $("#performance_allowance")[0].reset();
                                                $("#add_emp_performance").show();
                                                $("#update_emp_performance").hide();
                                                $("#cancel_update_perform").hide();
                                            });
                                        });


                                        $("#add_emp_staffloan").click(function () {
                                            $.ajax({
                                                type: "POST",
                                                // url: "<?= base_url("Employee/edit_add_staffloan") ?>",
                                                // url: "<?= base_url("Employee/add_loan") ?>",
                                                url: "<?= base_url("Employee/add_loan_info") ?>",
                                                dataType: "json",
                                                data: $("#staff_loan").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Employee loan added successfully');
                                                        $("#staff_loan")[0].reset();
                                                        get_loandetailstable();
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
                                        //Table of Loan details

                                        function get_loandetailstable() {

                                            var firm_name = document.getElementById("emp_firm").value;
                                            var user_id = document.getElementById("emp_id1").value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_loandetails_info") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {firm_name: firm_name, user_id: user_id},
                                                success: function (result) {
                                                    var data = result.result_sal;
                                                    if (result.status == 200) {
                                                        $('#loan_tablediv').html(data);
                                                        // $('#loandata_table').DataTable();
                                                    } else {

                                                        $('#loan_tablediv').html(data);
                                                        // $('#loandata_table').DataTable();
                                                    }
                                                },
                                            });
                                        }


                                        //delete individual salary
                                        function delete_loan(id)
                                        {
                                            var result = confirm("Do You Want to delete?");
                                            if (result) {
                                                $.ajax({
                                                    url: '<?= base_url("Employee/delete_loan") ?>',
                                                    type: "POST",
                                                    data: {id: id},
                                                    cache: false,
                                                    async: false,
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        if (success.status == 200) {
                                                            alert(success.body);
                                                            get_loandetailstable();
                                                        } else {
                                                            alert(success.body); //toster.error
                                                        }
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        alert("something went to wrong");
                                                    }
                                                });
                                            } else {

                                            }

                                        }

                                        //get details of loan data for edit

                                        //Get details individual salary
                                        function edit_loandata_btn(id)
                                        {
                                            $("#add_emp_staffloan").hide();
                                            $("#update").show();
                                            $("#update_cancel").show();
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/get_loanedit_details") ?>",
                                                dataType: "json",
                                                async: false,
                                                cache: false,
                                                data: {id: id},
                                                success: function (result) {
                                                    var data = result.result;
                                                    console.log(data);
                                                    if (result.code == 200) {

                                                        $("#loan_detail").val(data['loan_detail']);
                                                        $("#amount").val(data['amount']);
                                                        $("#EMI_amt").val(data['EMI_amt']);
                                                        $("#from_month").val(data['from_month']);
                                                        $("#Fyear2").val(data['Fyear']);
                                                        $("#total_month").val(data['total_month']);
                                                        $("#total_month").val(data['total_month']);
                                                        $("#sanction_date").val(data['sanction_date']);
                                                        $("#id_loan").val(id);
                                                    } else {

                                                    }
                                                },
                                            });
                                        }

                                        $(function () {
                                            $('#update_cancel').click(function () {
                                                $("#staff_loan")[0].reset();
                                                $("#add_emp_staffloan").show();
                                                $("#update").hide();
                                                $("#update_cancel").hide();
                                            });
                                        });


                                        //update individual performance
                                        function update_loan()
                                        {
                                            $.ajax({
                                                url: '<?= base_url("Employee/UpadteLoanInfo") ?>',
                                                type: "POST",
                                                data: $("#staff_loan").serialize(),
                                                success: function (success) {
                                                    success = JSON.parse(success);
                                                    if (success.message === "success") {
                                                        alert(success.body);
                                                        // location.reload();
                                                        $("#staff_loan")[0].reset();
                                                        get_loandetailstable();
                                                    } else {
                                                        alert(success.body);
                                                        // location.reload();
                                                        $("#staff_loan")[0].reset();
                                                        get_loandetailstable();
                                                        //  toastr.error(success.body); //toster.error
                                                    }
                                                },
                                                error: function (error) {
                                                    toastr.error(success.body);
                                                    console.log(error);
                                                    errorNotify("something went to wrong");
                                                }
                                            });
                                        }

                                        $("#edit_add_duedetails").click(function () {
                                            $.ajax({
                                                type: "POST",
                                                //url: "<?= base_url("Employee/edit_add_duedetails") ?>",
                                                url: "<?= base_url("Employee/add_due") ?>",
                                                dataType: "json",
                                                data: $("#due_details").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Employee Deduction added successfully');
                                                        $("#due_details")[0].reset();
                                                        get_deduction_data();
                                                    } else if (result.status === 200) {
                                                        alert("For this employee deduction type is already Set.You can Update the Deduction");
                                                        $("#update_indvidual_due").show();
                                                        $("#cancel_updatedue_indvidual").show();
                                                        $("#edit_add_duedetails").hide();
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


                                        $(function () {
                                            $('#cancel_updatedue_indvidual').click(function () {
                                                $("#due_details")[0].reset();
                                                $("#edit_add_duedetails").show();
                                                $("#update_indvidual_due").hide();
                                                $("#cancel_updatedue_indvidual").hide();
                                            });
                                        });

                                        $("#update_indvidual_due").click(function () {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/add_due_update") ?>",
                                                dataType: "json",
                                                data: $("#due_details").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Employee Deduction Updated successfully');
                                                        $("#due_details")[0].reset();
                                                        deduction_type_change();
                                                        $("#deduct_percentage_yes").hide();
                                                        $("#fix_deduct_amount_yes").show();
                                                        get_deduction_data();
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
                                        //table data for due details



                                        $("#add_attendance").click(function () {
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/edit_attendance_employee") ?>",
                                                dataType: "json",
                                                data: $("#emp_attendance_form").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        alert('Employee attendance updated successfully');
                                                        //                                                                location.reload();
                                                        $('#tab_5').click();
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
$('#emp_exit_form').on('submit', function(event) {
	event.preventDefault(); // Prevent default form submission

	var formData = new FormData(this); // Create a FormData object with the form data

	$.ajax({
		url: "<?= base_url("Employee/exit_employee_save") ?>",
		type: 'POST',
		data: formData,
		dataType:'json',
		contentType: false, // Prevent setting content type header
		processData: false, // Prevent jQuery from processing the data
		success: function(response) {
			// Handle successful response
			alert(response.msg);
			console.log(response); // Optionally log the response for debugging
		},
		error: function(xhr, status, error) {
			// Handle errors
			alert('An error occurred: ' + error);
			console.log(xhr.responseText); // Optionally log error details
		}
	});
});

function get_emp_leave_details(){
	var	user_id=	$("#emp_id1").val();
	$.ajax({
		url: "<?= base_url("Employee/get_emp_leave_details") ?>",
		type: 'POST',
		data: {user_id},
		dataType:'json',
		success: function(response) {
			// Handle successful response
		$('#leaveDetails').html(response.data);
		},
		error: function(xhr, status, error) {
			// Handle errors
			alert('An error occurred: ' + error);
			console.log(xhr.responseText); // Optionally log error details
		}
	});
}


                                        function  remove_error(id) {
                                            $('#' + id + '_error').html("");
                                        }

                                        $(document).ready(function () {
                                           /*  $.ajax({
                                                url: "<?= base_url("/Designation/get_designation") ?>",
                                                dataType: "json",
                                                success: function (result) {
                                                    if (result['message'] === 'success') {
                                                        var data = result.result_designation;
                                                        var ele = document.getElementById('designation');
                                                        for (i = 0; i < data.length; i++)
                                                        {
                                                            // POPULATE SELECT ELEMENT WITH JSON.
                                                            ele.innerHTML = ele.innerHTML +
                                                                    '<option value="' + data[i]['designation_id'] + '">' + data[i]['designation_name'] + '</option>';
                                                        }
                                                    }
                                                }
                                            }); */
                                            var firm_id = document.getElementById('emp_firm').value;
                                            var email_id = $('#email').val();

                                            //                                            $.ajax({
                                            //                                                type: "post",
                                            //                                                url: "<?= base_url("/Employee/ddl_get_employee_hq") ?>",
                                            //                                                dataType: "json",
                                            //                                                data: {firm_id: firm_id, email_id: email_id},
                                            //                                                success: function (result) {
                                            //                                                    var ele = document.getElementById('senior_emp');
                                            //                                                    ele.innerHTML = '<option value="">Select Employee</option>';
                                            //                                                    if (result['message'] === 'success') {
                                            //                                                        var data = result.emp_data;
                                            //                                                        var data_hr = result.hr_data;
                                            //                                                        ele.innerHTML = '<option value="' + data_hr.user_id + '">' + data_hr.user_name + '</option>';
                                            //                                                        for (i = 0; i < data.length; i++)
                                            //                                                        {
                                            //                                                            // POPULATE SELECT ELEMENT WITH JSON.
                                            //                                                            ele.innerHTML = ele.innerHTML +
                                            //                                                                    '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
                                            //                                                        }
                                            //                                                    }
                                            //                                                }
                                            //                                            });

                                            var user_type = document.getElementById('user_type').value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("/Employee/ddl_get_employee_hq") ?>",
                                                dataType: "json",
                                                data: {firm_id: firm_id, user_type: user_type},
                                                success: function (result) {
                                                    if (result['message'] === 'success') {

                                                        var data = result.emp_data;
                                                        //                                                                        var data_hr = result.hr_data;
                                                        var data_hr = result.hr_data_hq;
                                                        console.log(data_hr);
                                                        console.log(data);
                                                        var ele = document.getElementById('senior_emp');
                                                        //                                                        ele.innerHTML = "<option value=''>Select Senior Employee</option>";
                                                       innerHTML = "<option value='" + data_hr.user_id + "'>" + data_hr.user_name + "</option>";
                                                        for (i = 0; i < data.length; i++)
                                                        {
                                                            // POPULATE SELECT ELEMENT WITH JSON.
                                                          innerHTML = innerHTML +
                                                                    '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';

                                                        }


														$("#senior_emp").append(innerHTML);
                                                        // ele.innerHTML = ele.innerHTML +
                                                        // '<option value="No senior authority">No senior authority</option>';
                                                    }
                                                    /* var usedNames = {};
                                                    $("select[name='senior_emp'] > option").each(function () {
                                                        if (usedNames[this.text]) {
                                                            $(this).remove();
                                                        } else {
                                                            usedNames[this.text] = this.value;
                                                        }
                                                    }); */
                                                }
                                            });//Remove duplicate value from dropdown for Senior Authority

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
                                        //function get attendance data of employee ->akshay
                                        function GetAttendanceInfo(userId, firmId) {
                                            $.ajax({
                                                type: "post",
                                                url: "<?php echo base_url('Employee/GetAttendanceInfo') ?>",
                                                data: {userId: userId},
                                                dataType: "json",
                                                success: function (result) {
                                                    if (result.status == 200) {
                                                        attendanceData = result.body.normal_data;
                                                        additionalData = result.body.additional_data;
					let firms = result.firms;

					$("#employee_firms").val(firms).trigger('change');
                                                        //console.log();
                                                        //console.log(attendanceData[0]['applicable_status']);
                                                        if (attendanceData[0]['applicable_status'] == 1) {
                                                            $('#attendance_employee_1').prop('checked', true);
                                                            $('#applicable_yes').show();
                                                            if (attendanceData[0]['work_hour_status'] == 1) {
                                                                $('#work_hour_employee_1').prop('checked', true);
                                                                $('#fixed_time').val(attendanceData[0]['fixed_time']);
                                                            } else {
                                                                $('#work_hour_employee_2').prop('checked', true);
                                                                $('#static_yes').hide();
                                                            }

                                                            //Shift Punch applicable
                                                            if (attendanceData[0]['shift_applicable'] == 1) {
                                                                $('#shift_applicable').val(attendanceData[0]['shift_applicable']);
                                                            } else {
                                                                $('#shift_applicable').val(attendanceData[0]['shift_applicable']);
                                                            }

                                                            // Outside punch applicable

                                                            if (attendanceData[0]['outside_punch_applicable'] == 1) {
                                                                $('#outside_punch_applicable').val(attendanceData[0]['outside_punch_applicable']);
                                                            } else {
                                                                $('#outside_punch_applicable').val(attendanceData[0]['outside_punch_applicable']);
                                                            }
                                                            if (attendanceData[0]['late_applicable_sts'] == 1) {
                                                                $('#late_applicable_sts1').prop('checked', true);
                                                                $('#late_salary_count').val(attendanceData[0]['late_salary_days_count']);
                                                                $('#late_salary_days').val(attendanceData[0]['late_salary_cut_days']);
                                                                $('#late_minute_time').val(attendanceData[0]['late_minute_count']);
                                                                $('#late_yes').show();
                                                                // late_salary_count.style.display = 'block';
                                                                // late_salary_cut_days.style.display = 'block';
                                                            } else {
                                                                $('#late_applicable_sts2').prop('checked', true);
                                                                $('#late_yes').hide();
                                                            }

                                                            if (attendanceData[0]['work_schedule_status'] == 1) {
                                                                $('#variable_hour_yes').hide();
                                                                $('#work_hour_schedule_1').prop('checked', true);
                                                                $('#fixed_hour').val(attendanceData[0]['fixed_hour']);
                                                            } else {
                                                                $('.fix_hour_yes').hide();

                                                                $('#work_hour_schedule_2').prop('checked', true);

                                                                $('#week_days1').val(additionalData[0]['day']);
                                                                $('#select_schedule_type1').val(additionalData[0]['type']);
                                                                $('#select_fixed_hour1').val(additionalData[0]['fixed_hour']);
                                                                $('#inapplicable_time1').val(additionalData[0]['in_fixed_time']);


                                                                $('#week_days2').val(additionalData[1]['day']);
                                                                $('#select_schedule_type2').val(additionalData[1]['type']);
                                                                $('#select_fixed_hour2').val(additionalData[1]['fixed_hour']);
                                                                $('#inapplicable_time2').val(additionalData[1]['in_fixed_time']);

                                                                $('#week_days3').val(additionalData[2]['day']);
                                                                $('#select_schedule_type3').val(additionalData[2]['type']);
                                                                $('#select_fixed_hour3').val(additionalData[2]['fixed_hour']);
                                                                $('#inapplicable_time3').val(additionalData[2]['in_fixed_time']);


                                                                $('#week_days4').val(additionalData[3]['day']);
                                                                $('#select_schedule_type4').val(additionalData[3]['type']);
                                                                $('#select_fixed_hour4').val(additionalData[3]['fixed_hour']);
                                                                $('#inapplicable_time4').val(additionalData[3]['in_fixed_time']);


                                                                $('#week_days5').val(additionalData[4]['day']);
                                                                $('#select_schedule_type5').val(additionalData[4]['type']);
                                                                $('#select_fixed_hour5').val(additionalData[4]['fixed_hour']);
                                                                $('#inapplicable_time5').val(additionalData[4]['in_fixed_time']);


                                                                $('#week_days6').val(additionalData[5]['day']);
                                                                $('#select_schedule_type6').val(additionalData[5]['type']);
                                                                $('#select_fixed_hour6').val(additionalData[5]['fixed_hour']);
                                                                $('#inapplicable_time6').val(additionalData[5]['in_fixed_time']);


                                                                $('#week_days7').val(additionalData[6]['day']);
                                                                $('#select_schedule_type7').val(additionalData[6]['type']);
                                                                $('#select_fixed_hour7').val(additionalData[6]['fixed_hour']);
                                                                $('#inapplicable_time7').val(additionalData[6]['in_fixed_time']);
                                                            }

                                                        } else {
                                                            $('#attendance_employee_' + attendanceData[0]['applicable_status']).prop('checked', true);
                                                            $('#applicable_yes').hide();
                                                            if (attendanceData[0]['applicable_status'] == 2) {
                                                                applicable_change('applicable_no');
                                                            } else {
                                                                applicable_change('applicable_yes');
                                                                late_applicable_change('late_yes');
                                                            }

                                                            late_applicable_change('late_yes');
                                                        }

                                                    } else {

                                                    }
                                                },
                                                error: function (error) {

                                                }
                                            });
                                        }

                                        var code4 = {};
                                        $("select[name='gender'] > option").each(function () {
                                            if (code4[this.text]) {
                                                $(this).remove();
                                            } else {
                                                code4[this.text] = this.value;
                                            }
                                        });


										function get_employee_leave_data(){
											//emp_id1
									        var	user_id=	$("#emp_id1").val();
											$.ajax({
                                                type: "POST",
                                                url: "<?= base_url("/Employee/get_employee_leave_data") ?>",
                                                dataType: "json",
                                                data: {user_id},
                                                success: function (result) {
                                                    if (result['status'] == 200) {
														var data=(result.body);
                                                        if(data.prob_end_status==1) {
                                                            $("#add_leave_emp_btn").show();
                                                            $("#leave_config_msg").html('You can only add 3 emergency leaves or only leave when employee probation period is over');
                                                        } else {
                                                            $("#add_leave_emp_btn").show();
                                                            $("#leave_config_msg").html('');
                                                        }

														$("#total_leaves").val(data.total_leaves);
														$("#leave_balance").val(data.total_leave_available);
														if(data.accrued_leave == 1){
															$('#acc_per0').prop('checked', true);
															get_data(1);
														} else{
															$('#acc_per1').prop('checked', true);
															get_data(2);
														}
														$("#accrued_period").val(data.accrued_period);
					                                    $("#accrued_type").val(data.accure_from);
														$("#accr_leave").val(data.count_leave_accrued);
                                                        for(let i = 1; i <= 7; i++) {
                                                            let typeStr = data["type" + i]; // data.type1, data.type2, data.type3, etc.
                                                            if (typeStr) {
                                                                let res = typeStr.split(":"); // [typeName, numDays, CF, balance?]
                                                                
                                                                $("#leave_type" + i).val(res[0] ?? '');
                                                                $("#numofdays" + i).val(res[1] ?? '');
                                                                // console.log("abhishek mishra : set balance leave for each type)
                                                                if (data["type" + i + "_balance"] !== undefined) {
                                                                    $("#balance_leave_" + i).val(data["type" + i + "_balance"]);
                                                                } else {
                                                                    $("#balance_leave_" + i).val('');
                                                                }
                                                                $("input[name='CF" + i + "'][value='" + (res[2] ?? 0) + "']").prop("checked", true);
                                                            } else {
                                                                $("#leave_type" + i).val('');
                                                                $("#numofdays" + i).val('');
                                                                $("#balance_leave_" + i).val('');
                                                                $("input[name='CF" + i + "'][value='0']").prop("checked", true);
                                                            }
                                                        }
                                                    }else{
														alert("something went wrong!");

													}

                                                }
                                            });
										}

                                       function add_levae_emp() {
                                            // var firm_name = document.getElementById("firm_name_leave").value;
                                            var user_id = document.getElementById("emp_id1").value;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/update_employees_leaves") ?>",
                                                dataType: "json",
                                                data: $("#leaveconfigure_emp").serialize() + "&user_id=" + user_id,
                                                success: function (result) {
                                                    if (result['message'] === 'success') {
                                                        alert('Employee leave updated successfully.');
                                                       // document.forms['leaveconfigure_emp'].reset();
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
								function get_data(id){
									if(id==1){
										$("#p_div").show();
									}else{
										$("#p_div").hide();
									}
								}


function get_exit_data() {
    var user_id = document.getElementById("emp_exit_id").value;
    $.ajax({
        type: "POST",
        url: "<?= base_url("Employee/get_exit_data") ?>",
        dataType: "json",
        async: false,
        cache: false,
        data: {user_id: user_id},
        success: function (result) {
            var data = result.data;
            console.log(data);
            if (result.code == 200) {
                $('#last_day').val(data.exit_date);
                $('#Note').html(data.note);
				if(data.file != ''){
					$('#file_div').html('<a href="'+data.file+'"download=""><button  type="button" class="btn btn-link"><i class="fa fa-download"></i></button></a>');
				}
				var fileName = data.file.replace('./uploads/', '');
				// Assuming you have a File object
				var blob = new Blob(["%PDF-1.4\n%..."], { type: 'application/pdf' }); // The content should be a valid PDF

                // Create a DataTransfer object and add the Blob to it
				var dataTransfer = new DataTransfer();
				dataTransfer.items.add(new File([blob], fileName, { type: 'application/pdf' }));

                // Attach the file to the file input element
				var fileInput = document.getElementById('exit_file');
				fileInput.files = dataTransfer.files;
				if(data.status == 0){
					$('#last_day').attr('readonly', true);
					$('#Note').attr('readonly', true);
					$('#exit_file').attr('disabled', true);
					$('#emp_exit_btn').attr('disabled', true);
				}
            } else {
            }
        },
    });
}

$(document).ready(function () {
    $('#uploadDocumentTable').on('click', '.addRow', function () {
        var newRow = `<tr>
            <td>
                <button type="button" class="btn btn-sm btn-danger removeRow"><i class="fa fa-minus"></i></button>
            </td>
            <td>
                <select name="document_type[]" class="form-control">
                    <option selected disabled value="">Select Type</option>
                    <option value="marksheet">Marksheet</option>
                    <option value="certification">Certification</option>
                    <option value="nda">NDA</option>
                    <option value="appointment_letter">Appointment Letter</option>
                    <option value="offer_letter">Offer Letter</option>
                    <option value="previous_company_documents">Previous Company Documents</option>
                    <option value="employee_information_form">Employee Information Form</option>
                </select>
            </td>
            <td>
                <input type="file" name="employee_files[]" class="form-control fileInput" onchange="showUploadDocumentImage(this)">
                <div class="previewContainer" style="position: relative; width: 100px; display: none;">
                    <img class="imagePreview" src="" style="width: 100px; height: 100px; display: none;" />
                    <span onclick="removePreview(this)" style="position: absolute; top: -10px; right: -10px; cursor: pointer; background: red; color: white; border-radius: 50%; padding: 0 5px;">X</span>
                    <iframe class="docPreview" style="width: 100px; height: 100px; display: none;" frameborder="0"></iframe>
                    <div class="iconPreview" style="width: 100px; height: 100px; text-align: center; line-height: 100px; border: 1px solid #ccc;">📄</div>
                </div>
            </td>
        </tr>`;
        $('#uploadDocumentTable tbody').append(newRow);
    });

    // Remove row
    $('#uploadDocumentTable').on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
    });
});

function showUploadDocumentImage(input) {
    let row = input.closest('td');
    let previewContainer = row.querySelector('.previewContainer');
    let imagePreview = row.querySelector('.imagePreview');
    let iconPreview = row.querySelector('.iconPreview');
    let docPreview = row.querySelector('.docPreview');
    imagePreview.style.display = 'none';
    iconPreview.style.display = 'none';
    docPreview.style.display = 'none';
    previewContainer.style.display = 'none';

    if (input.files && input.files[0]) {
        let file = input.files[0];
        let extension = (file.name).split('.').pop().toLowerCase();
        let reader = new FileReader();

        reader.onload = function(e) {
            previewContainer.style.display = 'block';
            input.style.display = 'none';
            if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            } else if (extension === 'pdf') {
                docPreview.src = e.target.result;
                // docPreview.classList.add = e.target.result;
                docPreview.style.display = 'block';
            } else {
                iconPreview.innerHTML = `<span style="font-size:48px;">${getIcon(extension)}</span>`;
                iconPreview.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }
}

function removePreview(span) {
    let container = span.closest('.previewContainer');
    container.style.display = 'none';
    let input = container.closest('td').querySelector('.fileInput');
    input.style.display = 'block';
    input.value = ''; // reset file input
}

function getIcon(extension) {
    switch (extension) {
        case 'doc':
        case 'docx':
            return '📝';
        case 'xls':
        case 'xlsx':
            return '📊';
        case 'txt':
            return '📄';
        case 'pdf':
            return '📕';
        default:
            return '📁';
    }
}


function saveUploadUserDocument() {
    var firmName = document.getElementById("emp_firm").value;
    var userId = document.getElementById("emp_id2").value;
    let form = document.getElementById("uploadDocumentForm");
    let formData = new FormData(form);
    formData.append('user_id', userId);
    formData.append('firm_id', firmName);

    if (!firmName) {
        Swal.fire("Upload!", 'Firm name is required.', "error");
        return;
    }
    if (!userId) {
        Swal.fire("Upload!", 'User name is required.', "error");
        return;
    }
    if (formData.get('document_type[]') === null) {
        Swal.fire("Upload!", 'Document type is required.', "error");
        return;
    }
    if(formData.get('employee_files[]') === null) {
        Swal.fire("Upload!", 'File is required.', "error");
        return;
    }

    let url = '<?= base_url("Employee/uploadUserDocument") ?>';

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 200) {
                form.reset();
                Swal.fire("Upload!", "Documents uploaded successfully.", "success")
                .then(() => {
                    listUploadUserDocument();
                });
            } else {
                Swal.fire("Upload!", response.msg || 'Failed to upload documents.', "error");
            }
        },
        error: function(xhr, status, error) {
            Swal.fire("An error occurred !", xhr.responseText || 'Ajax error.', "error");
        }
    });
}

function listUploadUserDocument() {
    let userId = document.getElementById("emp_id2").value;
    let baseUrl = '<?= base_url() ?>';
    let url = baseUrl + 'Employee/getUploadUserDocuments';

    $.ajax({
        url: url,
        method: "GET",
        dataType: "json",
        data: {
            user_id: userId
        },
        success: function(res) {
            if (res.status === 200 && Array.isArray(res.data)) {
                let data = res.data;
                let tbody = '';

                data.forEach(function(row, idx) {
                    let fileUrl = `${baseUrl}uploads/documents/${row.employee_files}`;
                    let deleteUrl = `javascript:deleteUserDocument('${row.id}')`;
                    tbody += `<tr>
                        <td>${idx + 1}</td>
                        <td>${row.user_name || ''}</td>
                        <td>${row.created_name || ''}</td>
                        <td>${row.document_type || ''}</td>
                        <td>
                            ${row.employee_files ? `<a href="${fileUrl}" target="_blank" download><i class="fa fa-download"></i></a>` : ''}
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="${deleteUrl}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>`;
                });

                $('#user_document_datatable tbody').html(tbody);
            } else {
                $('#user_document_datatable tbody').html(
                    `<tr><td colspan="6" class="text-center">No documents found.</td></tr>`
                );
            }
        },
        error: function(xhr, status, error) {
            console.error("ajax error:", error);
        }
    });
}

function deleteUserDocument(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            let url = '<?= base_url("Employee/deleteUserDocument") ?>';
            $.ajax({
                url: url,
                method: "POST",
                data: { document_id: id },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === 200) {
                        Swal.fire("Deleted!", "Document deleted successfully.", "success").then(() => {
                            listUploadUserDocument();
                        });
                    } else {
                        Swal.fire("Error", response.msg || "Failed to delete document.", "error");
                    }
                },
                error: function(xhr) {
                    Swal.fire("Error", "An error occurred while deleting the document.", "error");
                    console.log(xhr.responseText);
                }
            });
        }
    });
}

// Asset Type And Sub Asset Types Start
    // Create start
        function change_assets(selectedId = null){
            let assets_type = $("#assets_type").val();
            $.ajax({
                url: '<?= base_url("get_sub_asset_type_record") ?>',
                method: 'GET',
                data: {
                    assets_type: assets_type
                },
                success: function(res) {
                    if (typeof res === "string") {
                        res = JSON.parse(res);
                    }
                    let select = $('#assets_details');
                    if (select.length === 0) {
                        console.error('Select element with id="assets_details" not found in DOM.');
                        return;
                    }

                    if(res.status === true && res.code === 200) {
                        let record = res.data;
                        console.log(record);
                        select.empty();
                        select.append('<option selected disabled value="">Select Sub Asset Type</option>');
                        $.each(record, function(index, item) {
                            let selectedAttr = (item.id == selectedId) ? 'selected' : '';
                            select.append('<option value="' + (item.id !== null ? item.id : '') + '" ' + selectedAttr + '>' + item.sub_asset_type + '</option>');
                        });
                    } else {
                        select.empty().append('<option value="">No Sub Asset Type Found</option>');
                    }
                }
            });
        }

        // Hide and show fields end 
            function change_details_assets() {
                const assets_type = $("#assets_type").val();
                const assets_details = $("#assets_details").val();
                const $container = $("#extra_fields_container");
                $container.empty();
                const fieldsMap = {
                    '5_1': getOperatingSystemsFields,
                    '5_2': getProductivityToolsFields,
                    '5_3': getAntivirusToolsFields,
                    '5_4': getDatabaseSystemsFields,
                    '5_5': getGeneralHardwareFields,
                    '5_6': getGeneralHardwareFields,
                    '5_7': getTabletFields,
                    '5_8': getSmartphoneFields,
                    '5_9': getExternalStorageFields,
                    '5_10': getAccessoriesFields
                };

                const key = `${assets_type}_${assets_details}`;
                const fieldsHtmlFunc = fieldsMap[key];
                if (fieldsHtmlFunc) {
                    $container.html(fieldsHtmlFunc());
                }
            }

            function getOperatingSystemsFields() {
                return `
                    <div class="col-md-4"><label>System Name</label><input type="text" name="system_name" onclick="stringValidation(event)" placeholder="please enter system name" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Version</label><input type="text" name="version" onclick="stringValidation(event)" placeholder="please enter version" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>License Key</label><input type="text" name="license_key" onclick="stringValidation(event)" placeholder="please enter license key" class="form-control extra-required"></div>
                `;
            }

            function getProductivityToolsFields() {
                return `
                    <div class="col-md-4"><label>MS Office</label><input type="text" name="ms_office" onclick="stringValidation(event)" placeholder="please enter ms office" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Adobe</label><input type="text" name="adobe" onclick="stringValidation(event)" placeholder="please enter adobe" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>License Type</label><input type="text" name="license_type" onclick="stringValidation(event)" placeholder="please enter license type" class="form-control extra-required"></div>
                    <div class="col-md-4 mt-2"><label>Subscription Info</label><input type="text" name="subscription_info" onclick="stringValidation(event)" placeholder="please enter subscription info" class="form-control extra-required"></div>
                `;
            }

            function getAntivirusToolsFields() {
                return `
                    <div class="col-md-4"><label>Expiry Date</label><input type="date" name="expiry_date" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Active Devices</label>
                        <select name="active_devices" placeholder="please select active devices " class="form-control extra-required">
                            <option value="">Select</option>
                            <option value="1">Active</option>
                            <option value="0">Not Active</option>
                        </select>
                    </div>
                `;
            }

            function getDatabaseSystemsFields() {
                return `
                    <div class="col-md-4"><label>Database Type</label>
                        <select name="database_type" placeholder="please database type" class="form-control extra-required">
                            <option value="">Select</option>
                            <option value="mysql">MySQL</option>
                            <option value="oracle">Oracle</option>
                        </select>
                    </div>
                    <div class="col-md-4"><label>Instance Count</label><input type="text" name="instance_count" onclick="stringValidation(event)" placeholder="please enter instance count" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>License Info</label><input type="text" name="license_info" onclick="stringValidation(event)" placeholder="please enter license info" class="form-control extra-required"></div>
                `;
            }

            function getGeneralHardwareFields() {
                return `
                    <div class="col-md-4"><label>Asset Code</label><input type="text" name="asset_code" onclick="stringValidation(event)" placeholder="please enter asset code" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Asset Location</label><input type="text" name="asset_location" onclick="stringValidation(event)" placeholder="please enter asset location" class="form-control extra-required"></div>
                    <div class="col-md-4 mt-2"><label>Operating System</label><input type="text" name="os_name" onclick="stringValidation(event)" placeholder="please enter os name" class="form-control extra-required"></div>
                    <div class="col-md-4 mt-2"><label>Warranty</label><input type="text" name="warranty" onclick="stringValidation(event)" placeholder="please enter warranty" class="form-control extra-required"></div>
                `;
            }

            function getTabletFields() {
                return `
                    <div class="col-md-4"><label>Asset Code</label><input type="text" name="asset_code" onclick="stringValidation(event)" placeholder="please enter asset code" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Asset Location</label><input type="text" name="asset_location" onclick="stringValidation(event)" placeholder="please enter asset location" class="form-control extra-required"></div>
                    <div class="col-md-4 mt-2"><label>Operating System</label><input type="text" name="os_name" onclick="stringValidation(event)" placeholder="please enter os name" class="form-control extra-required"></div>
                    <div class="col-md-4 mt-2"><label>Storage</label><input type="text" name="storage" onclick="stringValidation(event)" placeholder="please enter storage" class="form-control extra-required"></div>
                `;
            }

            function getSmartphoneFields() {
                return `
                    <div class="col-md-4"><label>IMEI Number</label><input type="text" name="imei_number" onclick="stringValidation(event)" placeholder="please enter imei number" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Phone Number</label><input type="text" name="phone_number" onclick="stringValidation(event)" placeholder="please enter phone number" class="form-control extra-required"></div>
                    <div class="col-md-4 mt-2"><label>Operating System</label><input type="text" name="os_name" onclick="stringValidation(event)" placeholder="please enter os name" class="form-control extra-required"></div>
                `;
            }

            function getExternalStorageFields() {
                return `
                    <div class="col-md-4"><label>Capacity</label><input type="text" name="capacity" onclick="stringValidation(event)" placeholder="please enter capacity" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Asset Type (HDD/SDD)</label><input type="text" name="hdd_sdd_type" onclick="stringValidation(event)" placeholder="please enter hdd/sdd type" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                    <div class="col-md-4 mt-2"><label>Usage</label><input type="text" name="usage" onclick="stringValidation(event)" placeholder="please enter usage" class="form-control extra-required"></div>
                `;
            }

            function getAccessoriesFields() {
                return `
                    <div class="col-md-4"><label>Mouse</label><input type="text" name="mouse" onclick="stringValidation(event)" placeholder="please enter mouse" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Keyboard</label><input type="text" name="keyboard" onclick="stringValidation(event)" placeholder="please enter keyboard" class="form-control extra-required"></div>
                    <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                    <div class="col-md-4 mt-2"><label>Docking Station</label><input type="text" name="docking_station" onclick="stringValidation(event)" placeholder="please enter docking station" class="form-control extra-required"></div>
                `;
            }
        // Hide and show fields end 


        function submitAssetTypeData() {
            let assetData = document.getElementById('asset_type').value;
            let url = '<?= base_url("create_asset_type") ?>';
            setTimeout(function() {
                $('#asset_type').val('');
                $('#assetModal').modal('hide');
            }, 500);
            $.ajax({
                url: url,
                method: "POST",
                data: { 
                    asset_type: assetData 
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === true && response.code === 200) {
                        Swal.fire("Success !", "Asset created successfully.", "success").then(() => {
                            $.ajax({
                                url: '<?= base_url("get_asset_type_record") ?>',
                                method: 'GET',
                                dataType: 'json',
                                success: function(res) {
                                    if(res.status === true && res.code === 200) {
                                        let assets_type_select = $('#assets_type');
                                        let select = $('#asset_id');
                                        select.empty();
                                        assets_type_select.empty();
                                        assets_type_select.append('<option value="">Select Asset Type</option>')
                                        select.append('<option value="">Select Asset Type</option>');
                                        $.each(res.data, function(index, item) {
                                            select.append('<option value="' + item.id + '">' + item.asset_type + '</option>');
                                        });
                                        $.each(res.data, function(index, item) {
                                            assets_type_select.append('<option value="' + item.id + '">' + item.asset_type + '</option>')
                                        })
                                    }
                                }
                            });
                        });
                    }
                }, 
                error: function(xhr) {
                    let err = xhr.responseJSON && xhr.responseJSON.msg ? xhr.responseJSON.msg : 'Asset data already exist.';
                    Swal.fire("Error", err, "error");
                }
            });
        }

        function submitSubAssetTypeData() {
            let assetData = document.getElementById('asset_id').value;
            let subAssetData = document.getElementById('sub_asset_type').value;
            let url = '<?= base_url("create_sub_asset_type") ?>';
            setTimeout(function() {
                $('#sub_asset_type').val('');
                $('#asset_id').val('');
                $('#subType').modal('hide');
            }, 500);
            $.ajax({
                url: url,
                method: "POST",
                data: { 
                    asset_type: assetData,
                    sub_asset_type: subAssetData
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === true || response.code === 200) {
                        Swal.fire("Success!", "Sub asset created successfully.", "success").then(() => {
                            $.ajax({
                                url: '<?= base_url("get_asset_type_record") ?>',
                                method: 'GET',
                                dataType: 'json',
                                success: function(res) {
                                    if(res.status === true && res.code === 200) {
                                        let assets_type_select = $('#assets_type');
                                        let select = $('#asset_id');
                                        select.empty();
                                        assets_type_select.empty();
                                        assets_type_select.append('<option value="">Select Asset Type</option>');
                                        select.append('<option value="">Select Asset Type</option>');
                                        $.each(res.data, function(index, item) {
                                            assets_type_select.append('<option value="' + item.id + '">' + item.asset_type + '</option>');
                                            select.append('<option value="' + item.id + '">' + item.asset_type + '</option>');
                                        });
                                    } else {
                                        alert('No asset types found');
                                    }
                                },
                                error: function() {
                                    alert('Error fetching asset types.');
                                }
                            });
                        });
                    } 
                },
                error: function(xhr) {
                    let err = xhr.responseJSON && xhr.responseJSON.msg ? xhr.responseJSON.msg : 'Sub asset data already exist.';
                    Swal.fire("Error", err, "error");
                }
            });
        }

        $(document).ready(function() {
            $.ajax({
                url: '<?= base_url("get_asset_type_record") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if(res.status === true && res.code === 200) {
                        let assets_type_select = $('#assets_type');
                        let select = $('#asset_id');
                        select.empty();
                        assets_type_select.empty();
                        assets_type_select.append('<option value="">Select Asset Type</option>')
                        select.append('<option value="">Select Asset Type</option>');
                        $.each(res.data, function(index, item) {
                            select.append('<option value="' + item.id + '">' + item.asset_type + '</option>');
                        });

                        $.each(res.data, function(index, item) {
                            assets_type_select.append('<option value="' + item.id + '">' + item.asset_type + '</option>')
                        })
                    } else {
                        alert('No asset types found');
                    }
                },
                error: function() {
                    alert('Error fetching asset types.');
                }
            });
        });
    // Create end

    // Update start
        function editAsset(rowId) {
            let url = baseUrl + 'show_asset_data';
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    id: rowId
                },
                success: function(resp) {
                    resp = JSON.parse(resp)
                    if(resp.status == true && resp.code == 200) {
                        populateUpdateForm(resp.data);
                        $("#editAssetData").modal("show");
                    }
                }
            })
        }

        function populateUpdateForm(data) {
            console.log(data);
            loadAssetTypes(data.db_asset_id);
            $("#update_assets_type").val(data.db_asset_id).trigger('change');
            $("#update_row_id").val(data.id);
            $("#update_emp_id").val(data.emp_id);
            $("#update_user_id").val(data.user_id);
            $("#update_brand_name").val(data.brand_name);
            $("#update_specification").val(data.specification);
            $("#update_descrption").val(data.description);
            $("#update_vendor").val(data.vendor);
            $("#update_brand_name").val(data.brand_name);
            $("#update_model_name").val(data.model_name);
            $("#update_pur_mnf").val(data.pur_mnf ?? '');
            update_change_assets(data.db_asset_id, data.db_sub_asset_id);
            let html = "";
            switch(data.secondary_type){
                case "Operating Systems":
                    html = `
                        <div class="col-md-4">
                            <label>System Name</label>
                            <input type="text" name="system_name" value="${data.system_name ?? data.os_name ?? ''}" class="form-control extra-required">
                        </div>
                        <div class="col-md-4">
                            <label>Version</label>
                            <input type="text" name="version" value="${data.version ?? data.version ?? ''}" class="form-control extra-required">
                        </div>
                        <div class="col-md-4">
                            <label>License Key</label>
                            <input type="text" name="license_key" value="${data.license_key ?? data.license_key ?? ''}" class="form-control extra-required">
                        </div>
                    `;
                    break;
                case "Productivity Tools":
                    html = `
                            <div class="col-md-4"><label>MS Office</label><input type="text" name="ms_office" value="${data.ms_office ?? data.ms_office ?? ''}" onclick="stringValidation(event)" placeholder="please enter ms office" class="form-control extra-required"></div>
                            <div class="col-md-4"><label>Adobe</label><input type="text" name="adobe" value="${data.adobe ?? data.adobe ?? ''}" onclick="stringValidation(event)" placeholder="please enter adobe" class="form-control extra-required"></div>
                            <div class="col-md-4"><label>License Type</label><input type="text" name="license_type" value="${data.license_type ?? data.license_type ?? ''}" onclick="stringValidation(event)" placeholder="please enter license type" class="form-control extra-required"></div>
                            <div class="col-md-4 mt-2"><label>Subscription Info</label><input type="text" name="subscription_info" value="${data.subscription_info ?? data.subscription_info ?? ''}" onclick="stringValidation(event)" placeholder="please enter subscription info" class="form-control extra-required"></div>
                        `;
                    break;
                case "Antivirus Tools":
                    html = `
                        <div class="col-md-4"><label>Expiry Date</label><input type="date" name="expiry_date" value="${data.expiry_date ?? data.expiry_date ?? ''}" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Active Devices</label>
                            <select name="active_devices" placeholder="please select active devices " class="form-control extra-required">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Not Active</option>
                            </select>
                        </div>
                    `;
                    break;
                case "Database Systems":
                    html = `
                        <div class="col-md-4"><label>Database Type</label>
                            <select name="database_type" placeholder="please database type" class="form-control extra-required">
                                <option value="">Select</option>
                                <option value="mysql">MySQL</option>
                                <option value="oracle">Oracle</option>
                            </select>
                        </div>
                        <div class="col-md-4"><label>Instance Count</label><input type="text" name="instance_count" value="${data.instance_count ?? data.instance_count ?? ''}" onclick="stringValidation(event)" placeholder="please enter instance count" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>License Info</label><input type="text" name="license_info" value="${data.license_info ?? data.license_info ?? ''}" onclick="stringValidation(event)" placeholder="please enter license info" class="form-control extra-required"></div>
                    `;
                    break;
                case "Desktop":
                case "Laptop":
                    html = `
                        <div class="col-md-4"><label>Asset Code</label><input type="text" name="asset_code" value="${data.asset_code ?? data.asset_code ?? ''}" onclick="stringValidation(event)" placeholder="please enter asset code" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" value="${data.serial_number ?? data.serial_number ?? ''}" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Asset Location</label><input type="text" name="asset_location" value="${data.asset_location ?? data.asset_location ?? ''}" onclick="stringValidation(event)" placeholder="please enter asset location" class="form-control extra-required"></div>
                        <div class="col-md-4 mt-2"><label>Operating System</label><input type="text" name="os_name" value="${data.os_name ?? data.os_name ?? ''}" onclick="stringValidation(event)" placeholder="please enter os name" class="form-control extra-required"></div>
                        <div class="col-md-4 mt-2"><label>Warranty</label><input type="text" name="warranty" value="${data.warranty ?? data.warranty ?? ''}" onclick="stringValidation(event)" placeholder="please enter warranty" class="form-control extra-required"></div>
                    `;
                    break;
                case "Tablets":
                    html = `
                        <div class="col-md-4"><label>Asset Code</label><input type="text" name="asset_code" value="${data.asset_code ?? data.asset_code ?? ''}" onclick="stringValidation(event)" placeholder="please enter asset code" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" value="${data.serial_number ?? data.serial_number ?? ''}" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Asset Location</label><input type="text" name="asset_location" value="${data.asset_location ?? data.asset_location ?? ''}" onclick="stringValidation(event)" placeholder="please enter asset location" class="form-control extra-required"></div>
                        <div class="col-md-4 mt-2"><label>Operating System</label><input type="text" name="os_name" value="${data.os_name ?? data.os_name ?? ''}" onclick="stringValidation(event)" placeholder="please enter os name" class="form-control extra-required"></div>
                        <div class="col-md-4 mt-2"><label>Storage</label><input type="text" name="storage" value="${data.storage ?? data.storage ?? ''}" onclick="stringValidation(event)" placeholder="please enter storage" class="form-control extra-required"></div>
                    `;
                    break;
                case "Smartphones":
                    html = `
                        <div class="col-md-4"><label>IMEI Number</label><input type="text" name="imei_number" value="${data.imei_number ?? data.imei_number ?? ''}" onclick="stringValidation(event)" placeholder="please enter imei number" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" value="${data.serial_number ?? data.serial_number ?? ''}" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Phone Number</label><input type="text" name="phone_number" value="${data.phone_number ?? data.phone_number ?? ''}" onclick="stringValidation(event)" placeholder="please enter phone number" class="form-control extra-required"></div>
                        <div class="col-md-4 mt-2"><label>Operating System</label><input type="text" name="os_name" value="${data.os_name ?? data.os_name ?? ''}" onclick="stringValidation(event)" placeholder="please enter os name" class="form-control extra-required"></div>
                    `;
                    break;
                case "External Storage Devices":
                    html = `
                        <div class="col-md-4"><label>Capacity</label><input type="text" name="capacity" value="${data.capacity ?? data.capacity ?? ''}" onclick="stringValidation(event)" placeholder="please enter capacity" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Asset Type (HDD/SDD)</label><input type="text" name="hdd_sdd_type" value="${data.hdd_sdd_type ?? data.hdd_sdd_type ?? ''}" onclick="stringValidation(event)" placeholder="please enter hdd/sdd type" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" value="${data.serial_number ?? data.serial_number ?? ''}" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                        <div class="col-md-4 mt-2"><label>Usage</label><input type="text" name="usage" value="${data.usage ?? data.usage ?? ''}" onclick="stringValidation(event)" placeholder="please enter usage" class="form-control extra-required"></div>
                    `;
                    break;
                case "Accessories":
                    html = `
                        <div class="col-md-4"><label>Mouse</label><input type="text" name="mouse" value="${data.mouse ?? data.mouse ?? ''}" onclick="stringValidation(event)" placeholder="please enter mouse" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Keyboard</label><input type="text" name="keyboard" value="${data.keyboard ?? data.keyboard ?? ''}" onclick="stringValidation(event)" placeholder="please enter keyboard" class="form-control extra-required"></div>
                        <div class="col-md-4"><label>Serial Number</label><input type="text" name="serial_number" value="${data.serial_number ?? data.serial_number ?? ''}" onclick="stringValidation(event)" placeholder="please enter serial number" class="form-control extra-required"></div>
                        <div class="col-md-4 mt-2"><label>Docking Station</label><input type="text" name="docking_station" value="${data.docking_station ?? data.docking_station ?? ''}" onclick="stringValidation(event)" placeholder="please enter docking station" class="form-control extra-required"></div>
                    `;
                    break;
            }
            $("#update_extra_fields_container").html(html);
        }

        $(document).ready(function () {
            // Page load hote hi asset types load karna
            loadAssetTypes(); 
            $("#update_assets_type").on("change", function () {
                let assetTypeId = $(this).val();
                update_change_assets(assetTypeId, $("#update_assets_details").val() || null);
            });

            update_change_details_assets();
            $("#update_assets_details").on("change", function () {
                update_change_details_assets();
            });
        });

        function loadAssetTypes(selectedId = null) {
            $.ajax({
                url: "<?= base_url('get_update_asset_type_record') ?>",
                method: "GET",
                success: function (res) {
                    if (typeof res === "string") res = JSON.parse(res);
                    let select = $("#update_assets_type");
                    select.empty().append('<option value="">Select Asset Type</option>');
                    if (res.status && res.code === 200) {
                        $.each(res.data, function (i, item) {
                            let selected = (selectedId && selectedId == item.id) ? "selected" : "";
                            select.append(`<option value="${item.id}" ${selected}>${item.asset_type}</option>`);
                        });
                    }
                },
                error: function () {
                    console.error("Error loading asset types");
                }
            });
        }

        function update_change_assets(assetTypeId, selectedId = null) {
            $.ajax({
                url: "<?= base_url('get_update_sub_asset_type_record') ?>",
                method: "GET",
                data: { asset_id: assetTypeId },
                success: function (res) {
                    if (typeof res === "string") res = JSON.parse(res);
                    let select = $("#update_assets_details");
                    select.empty().append('<option value="">Select Sub Asset</option>');
                    if (res.status && res.code === 200) {
                        $.each(res.data, function (i, item) {
                            let selected = (selectedId && String(selectedId) === String(item.id)) ? "selected" : "";
                            select.append(`<option value="${item.id}" ${selected}>${item.sub_asset_type}</option>`);
                        });
                        if (selectedId) {
                            select.val(String(selectedId)).trigger('change');
                        }
                    }
                },
                error: function () {
                    console.error("Error loading sub asset types");
                }
            });
        }


        function update_change_details_assets() {
            const assets_type = $("#update_assets_type").val();
            const assets_details = $("#update_assets_details").val();
            const $container = $("#update_extra_fields_container");
            $container.empty();
            const fieldsMap = {
                "5_1": updateOperatingSystemsFields,
                "5_2": updateProductivityToolsFields,
                "5_3": updateAntivirusToolsFields,
                "5_4": updateDatabaseSystemsFields,
                "5_5": updateGeneralHardwareFields,
                "5_6": updateGeneralHardwareFields,
                "5_7": updateTabletFields,
                "5_8": updateSmartphoneFields,
                "5_9": updateExternalStorageFields,
                "5_10": updateAccessoriesFields
            };

            const key = `${assets_type}_${assets_details}`;
            if (fieldsMap[key]) {
                $container.html(fieldsMap[key]());
            }
        }

        function update_assets_submit_btn(e) {
            let user_id = $("#update_user_id").val();
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: baseUrl + 'Assets/update_assets_details',
                data: $("#update_assets_form").serialize(),
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    $(".error-text").html("");
                    if (result.status === true) {
                        alert(result.msg);
                        $("#assets_form")[0].reset();
                        $("#editAssetData").modal("hide");
                        getAssetsData();

                    } else {
                        if (result.errors) {
                            $.each(result.errors, function (field, message) {
                                $("#" + field + "_error").html(message);
                            });
                        } else {
                            $("#" + result.id + "_error").html(result.error);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Update failed:", xhr.responseText);
                    alert("Something went wrong while updating asset!");
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (response.id && response.error) {
                            $("#" + response.id + "_error").html(response.error);
                        }
                    } catch (e) {
                        console.warn("No JSON error response from server");
                    }
                }
            });
        }

    // Update end
// Asset Type And Sub Asset Types End 

</script>



