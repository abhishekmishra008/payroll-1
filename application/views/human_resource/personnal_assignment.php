<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
if ($u_type == 2) {

    $this->load->view('human_resource/navigation');
} else if ($u_type == 3) {

    $this->load->view('client_admin/navigation');
} else if ($u_type == 4) {

    $this->load->view('employee/navigation');
} else if ($u_type == 5) {

    $this->load->view('human_resource/navigation');
}
$username = $this->session->userdata('login_session');
?>
<link href="<?= base_url() ?>assets/global/css/mystyle1.css" rel="stylesheet" type="text/css" />
<style>
    label.error {
        color:red;
    }
    .form-control .error{
        color: red;
    }
</style>

<STYLE>

    a {
        color: #0254EB
    }
    a:visited {
        color: #0254EB
    }
    #header h2 {
        color: white;
        margin:0px;
        padding: 5px;
    }
    .comment {
        width: 300px;
    }
    a.morelink {
        text-decoration:none;
        outline: none;
    }
    .morecontent span {
        display: none;
        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            text-align: center;
            font-size: 12px;
            line-height: 1.42857;
        }

    }
</STYLE>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<div class="page-content-wrapper">
    <div class="page-content  ">
        <div class=" wrapper-shadow">
            <div class="row">

                <div class="col-md-12">
                    <div class="portlet light  portlet-form ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class=" icon-settings font-red"></i>
                                <span class="caption-subject font-red sbold uppercase">View All Assignments Assign To Junior</span>
                            </div>
                            <div class="actions">
                                <div class="btn-group right btn-group-devided">
                                    <button class="btn dt-button buttons-print btn dark btn-outline" data-toggle="modal"  data-target="#assignmentByModal" data-assignment_code="0"><i class="fa fa-plus"></i> Create Assignment</button>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!--<table class="table" id="assignment_by_table">-->
                            <table class="table table-striped table-bordered table-hover dt-responsive dataTable no-footer dtr-inline collapsed" width="100%" id="assignment_by_table">
                                <thead>
                                    <tr>
                                        <th>Assignment Details</th>
                                        <th>Submission Date</th>
                                        <th>Priority</th>
                                        <th>Assigned to</th>
                                        <th>Created by</th>
                                        <th>Status</th>
                                        <th>Created on</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="assignment_by_tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $user_datails = $this->customer_model->get_firm_id();
            if ($user_datails["user_type"] != 2) {
                ?>
                <div class="">

                    <div class="col-md-12">
                        <div class="portlet light portlet-fit portlet-form bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class=" icon-settings font-red "></i>
                                    <span class="caption-subject font-red sbold uppercase">View All Assignments From Senior</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <!--<table class="table" id="assignment_to_table">-->
                                <table class="table table-striped table-bordered table-hover dt-responsive dataTable no-footer dtr-inline collapsed" width="100%" id="assignment_to_table">
                                    <thead>
                                        <tr>
                                            <th>Assignment Details</th>
                                            <th>Submission Date</th>
                                            <th>Priority</th>
                                            <th>Created by</th>
                                            <th>Status</th>
                                            <th>Created on</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="assignment_to_tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else {

            }
            ?>
            <div class="modal fade" id="assignmentByModal" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Assignment Details </h4>
                        </div>
                        <div class="modal-body">
                            <form id="assignment_by_form"  method="post">
                                <!--User type-->
                                <div class="row margin-bottom-10 hidden">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">Select Employee Type</label>
                                            <?php
                                            if ($user_star_rating !== "") {
                                                echo "<input type='hidden' id='user_star_rating' value='" . $user_star_rating . "'/>";
                                            } else {
                                                echo "<input type='hidden' id='user_star_rating' value='" . $user_star_rating . "'/>";
                                            }
                                            $user_datails = $this->customer_model->get_firm_id();
                                            if ($user_datails["user_type"] != '2') {
                                                ?>
                                                <div class="col-md-3">

                                                    <label class="control-label ">
                                                        <input type="radio" name="user_type"  id="senior_type" value="2" checked="" onchange="on_user_type();">Senior</label>
                                                </div>
                                                <?php
                                            } else {
                                                echo "<input type='hidden' id='user_type_details' value='" . $user_datails["user_type"] . "'/>";
                                            }
                                            ?>

                                            <div class="col-md-3">

                                                <label class="control-label ">
                                                    <input type="radio" name="user_type" id="junior_type" value="1" onchange="on_user_type();">Junior</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Select User -->
                                <div class="row margin-bottom-10">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="col-md-12 control-label">Select Employee Name</label>
                                            <div class="col-md-12">
                                                <input name="assignment_code" value="0" id="ass_by_model_id" type="hidden" />
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i>
                                                    </span>
                                                    <select name="ddl_user_list" class="form-control m-select2 m-select2-general" id="ddl_user_list">
                                                    </select>
                                                </div>
                                                <p class="text-info" id="user_notification_message"></p>
                                                <span class="text-danger" id="user_name_selection_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--Assignment Description-->
                                <div class="row margin-bottom-10">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label">Assignment Description</label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-quote-left"></i>
                                                </span>
                                                <textarea  name="txt_asignment_desc" id="assignment_descriptionby" class="form-control"></textarea>
                                            </div>
                                            <span class="text-danger" id="senior_assignment_desc_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--Assignment Repetitive-->
                                <div class="row margin-bottom-10">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Assignment Repetitive</label>
                                            <div class="col-md-3">
                                                <label class="control-label">
                                                    <input type="radio" name="is_repetition"  id="yes_repetitive" value="1" onchange="on_repetive_assignment(1);"> Yes
                                                </label>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="control-label">
                                                    <input type="radio" name="is_repetition" id="no_repetitive" value="0" checked="" onchange="on_repetive_assignment(0);">No </label>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!--repetitive type-->
                                <div class="row margin-bottom-10" id="repetition_type_row" style="display: none">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Repetition Type</label>
                                            <div class="col-md-3">
                                                <label class="control-label ">
                                                    <input type="radio" name="repetition_type"  id="day_repetition" value="1"  onchange="on_repetition_type_change(1);">
                                                    Day's
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label ">
                                                    <input type="radio" name="repetition_type" id="date_repetition" value="2" onchange="on_repetition_type_change(2);">
                                                    Date wise
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row margin-bottom-10" id="daywise_row" style="display: none">
                                    <div class="col">
                                        <div class="form-group">

                                            <div class="col-md-6">
                                                <label class="control-label">
                                                    Day </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </span>
                                                    <input type="number" name="repetition_days" class="form-control" id="repetition_days">
                                                </div>
                                                <span class="text-danger" id="days_repetition_error"></span>
                                            </div>
                                            <div class="col-md-6">

                                                <label class="control-label">Repetition Expiry Date </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="date" name="repetition_end_date1" class="form-control" id="repetition_end_date1" oninput="oncheckholidays(event);">
                                                </div>
                                                <span class="text-danger" id="daywise_expiry_repetition_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row margin-bottom-10" id="datewise_row"  style="display: none">
                                    <div class="col">
                                        <div class="form-group">

                                            <div class="col-md-6">
                                                <label class="control-label">
                                                    Date</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="date" name="repetition_date" class="form-control"  id="repetition_date" onchange="date_wise_expiry_change();" >
                                                </div>
                                                <p class="text-info">Repeating on each month selected date.</p>
                                                <span class="text-danger" id="repetition_date_error"></span>
                                            </div>
                                            <div class="col-md-6">

                                                <label class="control-label">Repetition Expiry Date </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="date" name="repetition_end_date2" class="form-control" id="repetition_end_date2" oninput="oncheckholidays(event);" >
                                                </div>
                                                <span class="text-danger" id="datewise_expiry_repetition_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row margin-bottom-10">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Assignment Priority</label>
                                            <div class="col-md-2">
                                                <label class="control-label ">
                                                    <input type="radio" name="work_priority" checked="" id="priority1" value="1">
                                                    High
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label ">
                                                    <input type="radio" name="work_priority" id="priority2" value="2">
                                                    Medium
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label ">
                                                    <input type="radio" name="work_priority" id="priority3" value="3">
                                                    Low
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-bottom-10">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="col-md-12 control-label">Select Submition Date</label>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i>
                                                    </span>
                                                    <input type="date" name="submit_date" id="submitted_date" class="form-control" onchange="submittedDate();" oninput="oncheckholidays(event);">
                                                </div>
                                                <span class="text-danger" id="submitted_date_error"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </span>
                                                    <select name="time_stander" class="form-control m-select2 m-select2-general" id="time_stander">
                                                        <option disabled='' selected=''>Submission time of the day</option>
                                                        <option value="1">Morning</option>
                                                        <option value="2">Afternoon</option>
                                                        <option value="3">Evening</option>
                                                    </select>
                                                </div>
                                                <span class="text-danger" id="time_stander_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row margin-bottom-10">
                                    <div class="form-group text-right">
                                        <div class="col-md-12">
                                            <button  type="submit" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="WorkDetailsModal" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Work Details</h4>
                        </div>
                        <div class="modal-body">
                            <form id="work_assignment_form"  method="post">
                                <div class="row margin-bottom-10">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label">Work Description</label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-quote-left"></i>
                                                </span>
                                                <input type="hidden" name="assignment_code" id="work_assingment_code" />
                                                <textarea  name="work_desc" id="work_desc" class="form-control"></textarea>
                                            </div>
                                            <span class="text-danger" id="work_desc_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-bottom-10">
                                    <div class="form-group text-right">
                                        <div class="col-md-12">
                                            <button  type="submit" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="WorkRejectionDetailsModal" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Work Rejection Details</h4>
                        </div>
                        <div class="modal-body">
                            <form id="work_rejection"  method="post">
                                <div class="row margin-bottom-10">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label">Rejection Description</label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-quote-left"></i>
                                                </span>
                                                <input type="hidden" name="assignment_code" id="work_rejectiion_assingment_code" />
                                                <textarea  name="reject_desc" id="work_reject_desc" class="form-control"></textarea>
                                            </div>
                                            <span class="text-danger" id="work_reject_desc_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-bottom-10">
                                    <div class="form-group text-right">
                                        <div class="col-md-12">
                                            <button  type="submit" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="AssignmentRejectionDetailsModal" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Assignment Rejection Details</h4>
                        </div>
                        <div class="modal-body">
                            <form id="assignment_rejection"  method="post">
                                <div class="row margin-bottom-10">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label">Rejection Description</label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-quote-left"></i>
                                                </span>
                                                <input type="hidden" name="assignment_code" id="assignment_rejection_code" />
                                                <textarea  name="reject_desc" id="reject_desc" class="form-control"></textarea>
                                            </div>
                                            <span class="text-danger" id="reject_desc_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-bottom-10">
                                    <div class="form-group text-right">
                                        <div class="col-md-12">
                                            <button  type="submit" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="assignmentViewModal" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Assignment Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" id="response_list">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('human_resource/footer'); ?>
        </div>
    </div>
</div>
<div class="loading" id="loading_div" style="display:none;z-index: 100000;"></div>
<link href="<?php echo base_url() . "assets/"; ?>/global/plugins/jquery-notific8/jquery.notific8.min.css" rel="stylesheet" type="text/css"/>


<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-notific8/jquery.notific8.min.js" type="text/javascript"></script>
<script type="text/javascript">
                                                        var selected_user;
                                                        $(document).ready(function () {
                                                            var today = new Date().toISOString().split('T')[0];
//                                                        document.getElementById("repetition_date").setAttribute('min', today);
                                                            document.getElementById("submitted_date").setAttribute('min', today);
                                                            document.getElementById("repetition_end_date2").setAttribute('min', today);
                                                            document.getElementById("repetition_end_date1").setAttribute('min', today);
                                                            time_stander();

                                                            $('#ddl_user_list').change(function ()
                                                            {
                                                                var label = $('#ddl_user_list :selected').parent().attr('label');
//                                                            console.log(label);
                                                                if (label === "Seniors") {
                                                                    $("#senior_type").prop("checked", true);
                                                                }
                                                                if (label === "Junior") {
                                                                    $("#junior_type").prop("checked", true);
                                                                }

                                                                var rating = parseInt($("#user_star_rating").val());
                                                                if (rating !== "") {
                                                                    var s = parseInt(label.split(":")[1].trim());
                                                                    console.log(s);
                                                                    if (rating < s) {
                                                                        $("#senior_type").prop("checked", true);
                                                                        $("#user_notification_message").text("Select Employee/Senior who has given you the assignment");

                                                                    } else if (rating > s) {
                                                                        $("#junior_type").prop("checked", true);
                                                                        $("#user_notification_message").text("Select employee to give an assignment");
                                                                    } else {
                                                                        $("#senior_type").prop("checked", true);
                                                                        $("#user_notification_message").text("Select Employee/Senior who has given you the assignment");
                                                                    }
                                                                }


                                                            });
                                                            if ($("#senior_type").is(":checked")) {
                                                                load_Senor_options();
                                                            }
                                                            if ($("#junior_type").is(":checked")) {
                                                                load_Junior_options();
                                                            }

                                                            if ($("#day_repetition").is(":checked")) {
                                                                addValidationToRepetitionDays();
                                                            }

                                                            if ($("#repetition_date").is(":checked")) {
                                                                addValidationToRepetitionDate();
                                                            }

                                                            $.ajax({
                                                                url: "<?= base_url("markasread") ?>",
                                                                method: "post"
                                                            }).done(function (success) {
                                                                load_unread_assigenment_table();
                                                            });
                                                            // Assignment Modal

                                                            $('#assignmentByModal').on('show.bs.modal', function (e) {
                                                                var update_value = $(e.relatedTarget).data('assignment_code');
                                                                if (update_value !== 0) {
                                                                    start_loading();
                                                                    $("#assignment_code").val(update_value);
                                                                    var url = "<?= base_url("getAssignmentById") ?>";
                                                                    var user = "";
                                                                    $.ajax({
                                                                        url: url,
                                                                        data: {assignment_code: update_value},
                                                                        method: "post",
                                                                        cache: false
                                                                    }).done(function (success) {
                                                                        success = JSON.parse(success);
                                                                        if (success.status === 200) {
                                                                            $("#assignment_descriptionby").val(success.result[0]["assignment_details"])
                                                                            if (success.result[0]["is_senor_created"] == "1") {
                                                                                $("#junior_type").prop("checked", true);
                                                                                on_user_type();
                                                                                user = success.result[0]["assign_by"];
                                                                                selected_user = user;
                                                                                console.log(user);
                                                                            }
                                                                            if (success.result[0]["is_senor_created"] == "2") {
                                                                                $("#senior_type").prop("checked", true);
                                                                                on_user_type();
                                                                                user = success.result[0]["assign_to"];
                                                                                selected_user = user;

                                                                            }
                                                                            if (success.result[0]["is_repetitive"] == "1") {
                                                                                $("#yes_repetitive").prop("checked", true);
                                                                                on_repetive_assignment(1);
                                                                                if (success.result[0]["repetitive_type"] == "1") {

                                                                                    $("#day_repetition").prop("checked", true);
                                                                                    $("#repetition_days").val(success.result[0]["repetitive_days"]);
                                                                                    $("#repetition_end_date1").val(success.result[0]["repetition_end_date"]);
                                                                                    on_repetition_type_change(1);
                                                                                }
                                                                                if (success.result[0]["repetitive_type"] == "2") {

                                                                                    $("#date_repetition").prop("checked", true);
                                                                                    $("#repetition_date").val(success.result[0]["repetitive_date"]);
                                                                                    $("#repetition_end_date2").val(success.result[0]["repetition_end_date"]);
                                                                                    on_repetition_type_change(2);
                                                                                }
                                                                            }
                                                                            if (success.result[0]["is_repetitive"] == "2") {
                                                                                on_repetive_assignment(0);
                                                                                $("#no_repetitive").prop("checked", true);
                                                                            }

                                                                            if (success.result[0]["priority"] == "1") {
                                                                                $("#priority1").prop("checked", true);
                                                                            }
                                                                            if (success.result[0]["priority"] == "2") {
                                                                                $("#priority2").prop("checked", true);
                                                                            }
                                                                            if (success.result[0]["priority"] == "3") {
                                                                                $("#priority3").prop("checked", true);
                                                                            }

                                                                            $("#submitted_date").val(success.result[0]["submission_date"]);
                                                                            $("#time_stander").val(success.result[0]["time_stander"]);
                                                                            $("#ass_by_model_id").val(success.result[0]["assignment_id"]);
                                                                        }
                                                                        stop_loading();
                                                                    }).fail(function (error) {
                                                                        console.log(error);
                                                                    });
                                                                    $("#ddl_user_list").val(user);
                                                                } else {
                                                                    $("#ass_by_model_id").val(0);
                                                                }

                                                            });
                                                            $('#assignmentByModal').on('hide.bs.modal', function (e) {
                                                                $("#assignment_by_form").trigger("reset");
                                                                on_user_type();
                                                                on_repetive_assignment(0);

                                                            });
                                                            $('#assignment_by_form').validate({
                                                                rules: {
                                                                    ddl_user_list: {required: true},
                                                                    txt_description: {required: true},
                                                                    submit_date: {required: true},
                                                                    time_stander: {required: true}
                                                                },
                                                                messages: {
                                                                    ddl_user_list: {required: "Select Name"},
                                                                    txt_description: {required: "Enter Assignment Details"},
                                                                    submit_date: {required: "Select Work Submittion Date"},
                                                                    time_stander: {required: "Select Time Stander"}
                                                                },
                                                                errorElement: 'span',
                                                                errorPlacement: function (error, element) {

                                                                    if (element.attr("id") == "ddl_user_list") {
                                                                        $("#user_name_selection_error").append(error);
                                                                    }
                                                                    if (element.attr("id") == "assignment_descriptionby") {
                                                                        $("#senior_assignment_desc_error").append(error);
                                                                    }
                                                                    if (element.attr("id") == "submitted_date") {
                                                                        $("#submitted_date_error").append(error);
                                                                    }
                                                                    if (element.attr("id") == "time_stander") {
                                                                        $("#time_stander_error").append(error);
                                                                    }
                                                                    if (element.attr("id") == "repetition_days") {
                                                                        $("#days_repetition_error").append(error);
                                                                    }
                                                                    if (element.attr("id") == "repetition_end_date1") {
                                                                        $("#daywise_expiry_repetition_error").append(error);
                                                                    }
                                                                    if (element.attr("id") == "repetition_date") {
                                                                        $("#repetition_date_error").append(error);
                                                                    }
                                                                    if (element.attr("id") == "repetition_end_date2") {
                                                                        $("#datewise_expiry_repetition_error").append(error);
                                                                    }
                                                                },

                                                                submitHandler: function (form) {
                                                                    start_loading();
                                                                    $.ajax({
                                                                        url: "<?= base_url("createAssignment") ?>",
                                                                        type: "POST",
                                                                        data: new FormData(form),
                                                                        contentType: false,
                                                                        cache: false,
                                                                        processData: false,
                                                                        success: function (success) {
                                                                            success = JSON.parse(success);

                                                                            var message = success.result;
                                                                            $("#assignment_by_form").trigger("reset");
                                                                            $("#assignmentByModal").modal("toggle");
                                                                            toastr.success(message);
                                                                            load_assigenment_table();
                                                                            stop_loading();
                                                                        },
                                                                        error: function (error) {
                                                                            error = JSON.parse(error);
                                                                            var message = error.result;
                                                                            $("#assignment_by_form").trigger("reset");
                                                                            $("#assignmentByModal").modal("toggle");
                                                                            toastr.error(message);
                                                                            stop_loading();
                                                                        }
                                                                    });
                                                                }});

                                                            // Work  Modal
                                                            $('#WorkDetailsModal').on('show.bs.modal', function (e) {
                                                                document.getElementById("work_assingment_code").value = $(e.relatedTarget).data('assignment_code');
                                                            });

                                                            $('#WorkDetailsModal').on('hide.bs.modal', function (e) {
                                                                $("#work_assignment_form").trigger("reset");
                                                            });

                                                            $('#work_assignment_form').validate({
                                                                rules: {
                                                                    work_desc: "required"
                                                                },
                                                                messages: {
                                                                    work_desc: "Enter Work Description"
                                                                },
                                                                errorElement: 'span',
                                                                errorPlacement: function (error, element) {
                                                                    if (element.attr("id") == "work_desc") {
                                                                        $("#work_desc_error").append(error);
                                                                    }
                                                                },
                                                                submitHandler: function (form) {
                                                                    start_loading();
                                                                    $.ajax({
                                                                        url: "<?= base_url("addWorkDetails") ?>",
                                                                        type: "POST",
                                                                        data: new FormData(form),
                                                                        contentType: false,
                                                                        cache: false,
                                                                        processData: false,
                                                                        success: function (success) {
                                                                            success = JSON.parse(success);
                                                                            var message = success.result;
                                                                            $("#work_assignment_form").trigger("reset");
                                                                            $("#WorkDetailsModal").modal("toggle");

                                                                            toastr.success(message);
                                                                            stop_loading();
                                                                            load_assigenment_table();
                                                                        },
                                                                        error: function (error) {
                                                                            error = JSON.parse(error);
                                                                            var message = error["response"];
                                                                            $("#work_assignment_form").trigger("reset");
                                                                            $("#WorkDetailsModal").modal("toggle");
                                                                            toastr.error(message);
                                                                            stop_loading();
                                                                        }
                                                                    });
                                                                }
                                                            });

                                                            // Work Rejection Modal
                                                            $('#WorkRejectionDetailsModal').on('show.bs.modal', function (e) {
                                                                document.getElementById("work_rejectiion_assingment_code").value = $(e.relatedTarget).data('assignment_code');
                                                                $("#assignmentViewModal").modal("toggle");
                                                            });

                                                            $('#WorkRejectionDetailsModal').on('hide.bs.modal', function (e) {
                                                                $("#work_rejection").trigger("reset");
                                                            });

                                                            $('#work_rejection').validate({
                                                                rules: {
                                                                    reject_desc: "required"
                                                                },
                                                                messages: {
                                                                    reject_desc: "Enter Work Description"
                                                                },
                                                                errorElement: 'span',
                                                                errorPlacement: function (error, element) {
                                                                    if (element.attr("id") == "work_reject_desc") {
                                                                        $("#work_reject_desc_error").append(error);
                                                                    }
                                                                },
                                                                submitHandler: function (form) {
                                                                    start_loading();
                                                                    $.ajax({
                                                                        url: "<?= base_url("addWorkRejectionDetails") ?>",
                                                                        type: "POST",
                                                                        data: new FormData(form),
                                                                        contentType: false,
                                                                        cache: false,
                                                                        processData: false,
                                                                        success: function (success) {
                                                                            success = JSON.parse(success);
                                                                            var message = success.result;
                                                                            $("#work_rejection").trigger("reset");
                                                                            $("#WorkRejectionDetailsModal").modal("toggle");
                                                                            toastr.success(message);
                                                                            stop_loading();
                                                                            load_assigenment_table();
                                                                        },
                                                                        error: function (error) {
                                                                            error = JSON.parse(error);
                                                                            var message = error["response"];
                                                                            $("#work_rejection").trigger("reset");
                                                                            $("#WorkRejectionDetailsModal").modal("toggle");
                                                                            toastr.error(message);

                                                                            stop_loading();
                                                                        }
                                                                    });
                                                                }
                                                            });

                                                            // Assignment Rejection Modal
                                                            $('#AssignmentRejectionDetailsModal').on('show.bs.modal', function (e) {
                                                                document.getElementById("assignment_rejection_code").value = $(e.relatedTarget).data('assignment_code');
                                                                $("#assignmentViewModal").modal("toggle");
                                                            });

                                                            $('#AssignmentRejectionDetailsModal').on('hide.bs.modal', function (e) {
                                                                $("#assignment_rejection").trigger("reset");
                                                            });

                                                            $('#assignment_rejection').validate({
                                                                rules: {
                                                                    reject_desc: "required"
                                                                },
                                                                messages: {
                                                                    reject_desc: "Enter Rejection Description"
                                                                },
                                                                errorElement: 'span',
                                                                errorPlacement: function (error, element) {
                                                                    if (element.attr("id") == "reject_desc") {
                                                                        $("#reject_desc_error").append(error);
                                                                    }
                                                                },
                                                                submitHandler: function (form) {
                                                                    start_loading();
                                                                    $.ajax({
                                                                        url: "<?= base_url("addAssignRejectionDetails") ?>",
                                                                        type: "POST",
                                                                        data: new FormData(form),
                                                                        contentType: false,
                                                                        cache: false,
                                                                        processData: false,
                                                                        success: function (success) {
                                                                            success = JSON.parse(success);
                                                                            var message = success.result;
                                                                            $("#assignment_rejection").trigger("reset");
                                                                            $("#AssignmentRejectionDetailsModal").modal("toggle");
                                                                            toastr.success(message);
                                                                            stop_loading();
                                                                            load_assigenment_table();
                                                                        },
                                                                        error: function (error) {
                                                                            error = JSON.parse(error);
                                                                            var message = error["response"];
                                                                            $("#assignment_rejection").trigger("reset");
                                                                            $("#AssignmentRejectionDetailsModal").modal("toggle");
                                                                            toastr.error(message);
                                                                            stop_loading();
                                                                        }
                                                                    });
                                                                }
                                                            });

                                                            $('#assignmentViewModal').on('show.bs.modal', function (e) {
                                                                var update_value = $(e.relatedTarget).data('assignment_value');
                                                                var user_type = $(e.relatedTarget).data('user_type');
                                                                updateViewModel(update_value, user_type);
                                                            });

                                                            load_assigenment_table();

                                                            var user_type = $("#user_type_details").val();

                                                            if (user_type == 2) {
                                                                $("#junior_type").prop("checked", true);
                                                                load_Junior_options();
                                                            }
                                                        });

                                                        function load_assigenment_table() {
                                                            start_loading();
                                                            var url = "<?= base_url() ?>getAssignment";
                                                            $.ajax({
                                                                url: url,
                                                                method: "post"
                                                            }).done(function (success) {
                                                                var object = JSON.parse(success);
                                                                $('#assignment_by_tbody').empty();
                                                                $('#assignment_by_tbody').append(object.assignby_list);
                                                                $('#assignment_by_table').DataTable();

                                                                $('#assignment_to_tbody').empty();
                                                                $('#assignment_to_tbody').append(object.assignto_list);
                                                                $('#assignment_to_table').DataTable();
                                                                stop_loading();
                                                                readMore();
                                                            }).fail(function (error) {
                                                                $.notific8(error, {
                                                                    horizontalEdge: 'bottom',
                                                                    verticalEdge: 'right',
                                                                    zindex: 50000,
                                                                    theme: 'ruby'
                                                                });
                                                                stop_loading();
                                                            });
                                                        }

                                                        function readMore() {
                                                            var showChar = 50;
                                                            var ellipsestext = "...";
                                                            var moretext = "more";
                                                            var lesstext = "less";
                                                            $('.more').each(function () {
                                                                var content = $(this).html();

                                                                if (content.length > showChar) {

                                                                    var c = content.substr(0, showChar);
                                                                    var h = content.substr(showChar - 1, content.length - showChar);

                                                                    var html = c + '<span class="moreelipses">' + ellipsestext + '</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                                                                    $(this).html(html);
                                                                }

                                                            });

                                                            $(".morelink").click(function () {
                                                                if ($(this).hasClass("less")) {
                                                                    $(this).removeClass("less");
                                                                    $(this).html(moretext);
                                                                } else {
                                                                    $(this).addClass("less");
                                                                    $(this).html(lesstext);
                                                                }
                                                                $(this).parent().prev().toggle();
                                                                $(this).prev().toggle();
                                                                return false;
                                                            });
                                                        }

                                                        function mark_as_accept_work(assignment_code) {
                                                            start_loading();
                                                            var url = "<?= base_url("accept_assignment_work") ?>";
                                                            $.ajax({
                                                                url: url,
                                                                data: {assignment_code: assignment_code},
                                                                method: "post"
                                                            }).done(function (success) {
                                                                success = JSON.parse(success);
                                                                $("#assignmentViewModal").modal("toggle");
                                                                stop_loading();
                                                                load_assigenment_table();
                                                            }).fail(function (error) {

                                                                $.notific8(error, {
                                                                    horizontalEdge: 'bottom',
                                                                    verticalEdge: 'right',
                                                                    zindex: 50000,
                                                                    theme: 'ruby'
                                                                });
                                                                stop_loading();
                                                            });
                                                        }

                                                        function mark_as_accept_assignment(assignment_code) {
                                                            start_loading();
                                                            var url = "<?= base_url("accept_assignment") ?>";
                                                            $.ajax({
                                                                url: url,
                                                                data: {assignment_code: assignment_code},
                                                                method: "post"
                                                            }).done(function (success) {
                                                                success = JSON.parse(success);
                                                                $("#assignmentViewModal").modal("toggle");
                                                                stop_loading();
                                                                load_assigenment_table();

                                                            }).fail(function (error) {

                                                                $.notific8(error, {
                                                                    horizontalEdge: 'bottom',
                                                                    verticalEdge: 'right',
                                                                    zindex: 50000,
                                                                    theme: 'ruby'
                                                                });
                                                                stop_loading();
                                                            });
                                                        }

                                                        function mark_as_accept_both(assignment_code) {
                                                            start_loading();
                                                            var url = "<?= base_url("accept_assignment_work_and_assignment") ?>";
                                                            $.ajax({
                                                                url: url,
                                                                data: {assignment_code: assignment_code},
                                                                method: "post"
                                                            }).done(function (success) {
                                                                success = JSON.parse(success);
                                                                stop_loading();
                                                                load_assigenment_table();

                                                                $("#assignmentViewModal").modal("toggle");
                                                            }).fail(function (error) {

                                                                $.notific8(error, {
                                                                    horizontalEdge: 'bottom',
                                                                    verticalEdge: 'right',
                                                                    zindex: 50000,
                                                                    theme: 'ruby'
                                                                });
                                                                stop_loading();
                                                            });
                                                        }

                                                        function load_Junior_options() {
                                                            start_loading();
                                                            var url = "<?= base_url("getJunior") ?>";
                                                            var jqxhr = $.ajax({
                                                                url: url,
                                                                method: "post"
                                                            }).done(function (success) {
                                                                success = JSON.parse(success);
                                                                $('#ddl_user_list').empty();
                                                                $('#ddl_user_list').html(success.option_list);
                                                                $("#user_notification_message").text("Select employee to give an assignment")
                                                                stop_loading();
                                                            }).fail(function (error) {

                                                                $.notific8(error, {
                                                                    horizontalEdge: 'bottom',
                                                                    verticalEdge: 'right',
                                                                    zindex: 50000,
                                                                    theme: 'ruby'
                                                                });
                                                                stop_loading();
                                                            });
                                                            jqxhr.always(function () {
                                                                $("#ddl_user_list").val(selected_user);
                                                                selected_user = "";
                                                            });
                                                        }

                                                        function load_Senor_options() {
                                                            start_loading();
                                                            var url = "<?= base_url("getSenor") ?>";
                                                            var jqxhr = $.ajax({
                                                                url: url,
                                                                method: "post"

                                                            }).done(function (success) {
                                                                success = JSON.parse(success);
                                                                $('#ddl_user_list').empty();
                                                                $('#ddl_user_list').html(success.option_list);
                                                                $("#user_notification_message").text("Select Employee/Senior who has given you the assignment");
                                                                stop_loading();
                                                            }).fail(function (error) {
                                                                $.notific8(error, {
                                                                    horizontalEdge: 'bottom',
                                                                    verticalEdge: 'right',
                                                                    zindex: 50000,
                                                                    theme: 'ruby'
                                                                });
                                                                stop_loading();
                                                            });

                                                            jqxhr.always(function () {
                                                                $("#ddl_user_list").val(selected_user);
                                                                selected_user = "";
                                                            });

                                                        }


                                                        function updateViewModel(update_value, user_type) {
                                                            start_loading();
                                                            $.ajax({
                                                                url: "<?= base_url("getResponseOfAssignment") ?>",
                                                                type: "POST",
                                                                data: {assignment_code: update_value, user_type: user_type},
                                                                success: function (success) {
                                                                    success = JSON.parse(success);
                                                                    var status = success.status;
                                                                    if (status === 200) {
                                                                        $("#response_list").empty();
                                                                        $("#response_list").append(success.result);
                                                                    } else {
                                                                        $("#response_list").empty();
                                                                    }
                                                                    stop_loading();

                                                                }
                                                            });
                                                        }

                                                        function on_user_type() {
                                                            if ($("#senior_type").is(":checked")) {
                                                                load_Senor_options();
                                                            }
                                                            if ($("#junior_type").is(":checked")) {
                                                                load_Junior_options();
                                                            }
                                                        }

                                                        function on_repetive_assignment(type) {
                                                            if (type === 1) {
                                                                $("#repetition_type_row").show();
                                                                if ($("#day_repetition").is(":checked")) {
                                                                    $("#daywise_row").show();
                                                                    addValidationToRepetitionDays();
                                                                } else if ($("#date_repetition").is(":checked")) {
                                                                    $("#datewise_row").show();
                                                                    addValidationToRepetitionDate();
                                                                } else {
                                                                    $("#day_repetition").attr("checked", "checked");
                                                                    $("#daywise_row").show();
                                                                    addValidationToRepetitionDays();
                                                                }
                                                            }
                                                            if (type === 0) {
                                                                $("#repetition_type_row").hide();
                                                                $("#daywise_row").hide();
                                                                $("#datewise_row").hide();
                                                                $("#repetition_days").val("");
                                                                $("#repetition_date").val("");
                                                                $("#repetition_end_date1").val("");
                                                                $("#repetition_end_date2").val("");
                                                            }
                                                        }

                                                        function on_repetition_type_change(type) {
                                                            if (type === 1) {
                                                                $("#daywise_row").show();
                                                                $("#datewise_row").hide();
                                                            }
                                                            if (type === 2) {
                                                                $("#datewise_row").show();
                                                                $("#daywise_row").hide();
                                                            }
                                                        }

                                                        function date_wise_expiry_change() {
                                                            var selected_date = document.getElementById("repetition_date").value;
                                                            var today = new Date().toISOString().split('T')[0];
                                                            document.getElementById("repetition_end_date2").setAttribute('min', today);
                                                            document.getElementById("submitted_date").setAttribute('min', today);
                                                        }

                                                        function changeDate(date) {
                                                            var d = new Date(date);
                                                            var dt = d.getDate();
                                                            var mn = d.getMonth();
                                                            mn++;
                                                            var yy = d.getFullYear();
                                                            var newDate = dt + "/" + mn + "/" + yy
                                                            return newDate;
                                                        }

                                                        function todaysDate() {
                                                            var d = new Date(),
                                                                    month = '' + (d.getMonth() + 1),
                                                                    day = '' + d.getDate(),
                                                                    year = d.getFullYear();

                                                            if (month.length < 2)
                                                                month = '0' + month;
                                                            if (day.length < 2)
                                                                day = '0' + day;

                                                            return [year, month, day].join('-');
                                                        }

                                                        function submittedDate() {
                                                            var submitted_date = document.getElementById("submitted_date").value;
                                                            if (submitted_date === todaysDate()) {
                                                                time_stander();
                                                            } else {
                                                                $("#time_stander option:contains('Morning')").removeAttr("disabled");
                                                                $("#time_stander option:contains('Afternoon')").removeAttr("disabled");
                                                                $("#time_stander option:contains('Evening')").removeAttr("disabled");
                                                            }
                                                        }

                                                        function time_stander() {
                                                            var today = new Date()
                                                            var curHr = today.getHours();
                                                            if (curHr < 12) {
                                                                $("#time_stander option:contains('Morning')").attr("disabled", "disabled");
                                                            } else if (curHr < 18) {
                                                                $("#time_stander option:contains('Morning')").attr("disabled", "disabled");
                                                                $("#time_stander option:contains('Afternoon')").attr("disabled", "disabled");
                                                            } else {
                                                                $("#time_stander option:contains('Morning')").attr("disabled", "disabled");
                                                                $("#time_stander option:contains('Afternoon')").attr("disabled", "disabled");
                                                                $("#time_stander option:contains('Evening')").attr("disabled", "disabled");
                                                            }
                                                        }

                                                        function addValidationToRepetitionDays() {
                                                            $("#repetition_days").rules("add", {
                                                                required: true,
                                                                messages: {required: 'Enter Number Of Days'}
                                                            });
                                                            $("#repetition_end_date1").rules("add", {
                                                                required: true,
                                                                messages: {required: 'Select Repetition Expiry Date.'}
                                                            });
                                                            $("#assignment_by_form").validate();
                                                        }

                                                        function addValidationToRepetitionDate() {
                                                            $("#repetition_date").rules("add", {
                                                                required: true,
                                                                messages: {required: 'Select Repetition Date.'}
                                                            });
                                                            $("#repetition_end_date2").rules("add", {
                                                                required: true,
                                                                messages: {required: 'Select Repetition Expiry Date.'}
                                                            });
                                                            $("#assignment_by_form").validate();
                                                        }

                                                        function oncheckholidays(e) {
                                                            var test = new Date(e.target.value);
                                                            test = test.getFullYear() + "-" + test.getMonth() + "-" + test.getDate();
                                                            var day = new Date(e.target.value).getUTCDay();
                                                            selected_user = $('#ddl_user_list').val();
                                                            console.log(selected_user);
                                                            if (selected_user != undefined) {
                                                                $.ajax({
                                                                    url: "<?= base_url("getholidays") ?>",
                                                                    method: "post",
                                                                    data: {user_code: selected_user}
                                                                }).done(function (success) {

                                                                    success = JSON.parse(success);
                                                                    var weekend_days = [];
                                                                    var weekend_repetation = [];
                                                                    for (var i = 0; i < success.weekend.length; i++) {
                                                                        weekend_days.push(success.weekend[i][0]);
                                                                        weekend_repetation.push(success.weekend[i][1]);
                                                                    }

                                                                    var days = ["sun", "mon", "tue", "wed", "tha", "fri", "sat"];
                                                                    var day_list = [];

                                                                    for (var j = 0; j < weekend_days.length; j++) {
                                                                        if (days.indexOf(weekend_days[j]) !== -1) {
                                                                            day_list.push(days.indexOf(weekend_days[j]));
                                                                        }
                                                                    }
                                                                    for (var k = 0; k <= day_list.length; k++) {
                                                                        if (day === day_list[k]) {
                                                                            var dates = getDatesOfSpecifyDay(new Date(e.target.value).getFullYear(), new Date(e.target.value).getMonth(), day);
                                                                            if (Array.isArray(weekend_repetation[k])) {
                                                                                for (var w = 0; w < weekend_repetation[k].length; w++) {
                                                                                    if (dates.indexOf(test) != -1) {
                                                                                        if (dates.indexOf(test) == (weekend_repetation[k][w] - 1)) {
                                                                                            toastr.error("Invalid date, selected date is week off");
                                                                                            e.target.value = "";
                                                                                            break;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                if (dates.indexOf(test) != -1) {
                                                                                    toastr.error("Invalid date, selected date is week off");
                                                                                    e.target.value = "";
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                    }

                                                                    for (var h = 0; h < success.holidays.length; h++) {
                                                                        var holiday = new Date(success.holidays[h].holiday_date);
                                                                        holiday = holiday.getFullYear() + "-" + holiday.getMonth() + "-" + holiday.getDate();
                                                                        if (holiday == test) {
                                                                            alert("Invalid date, Holiday on " + success.holidays[h].holiday_name);
                                                                            e.target.value = "";
                                                                            break;
                                                                        }
                                                                    }
                                                                });
                                                            } else {
                                                                alert("Select User");
                                                            }
                                                        }

                                                        function getDatesOfSpecifyDay(year, month, weekday) {

                                                            var day, counter, date;

                                                            day = 1;
                                                            counter = 0;
                                                            var all_dates = [];
                                                            date = new Date(year, month, day);
                                                            while (date.getMonth() === month) {
                                                                if (date.getDay() === weekday) { // Sun=0, Mon=1, Tue=2, etc.
                                                                    counter += 1;
                                                                    dates = year + "-" + month + "-" + day;
                                                                    all_dates.push(dates);
                                                                }
                                                                day += 1;
                                                                date = new Date(year, month, day);
                                                            }
                                                            return all_dates;
                                                        }

                                                        function start_loading() {
                                                            document.getElementById('loading_div').style.display = "block";
                                                        }
                                                        function stop_loading() {
                                                            document.getElementById('loading_div').style.display = "none";
                                                        }
</script>