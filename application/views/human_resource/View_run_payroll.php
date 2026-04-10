<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') or exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username = $this->session->userdata('login_session');

$session_data = $this->session->userdata('login_session');
$user_id = ($session_data['emp_id']);

$user_type = ($session_data['user_type']);
?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet"
      type="text/css"/>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
      type="text/css"/>
<style>
    span.error {
        color: red;
    }

    td {
        text-align: center;
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

    .page-content-wrapper {
        margin-top: 40px;
    }

</style>
<style type="text/css">

    .tabs {
        position: relative;
        overflow: hidden;
        margin: 0 auto;
        width: 100%;
        font-weight: 300;
        font-size: 1.0em;
    }

    /* Nav */
    .tabs nav {
        text-align: center;
    }

    .tabs nav ul {
        position: relative;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flex;
        display: flex;
        margin: 0 auto;
        padding: 0;
    / / max-width: 1200 px;
        list-style: none;
        -ms-box-orient: horizontal;
        -ms-box-pack: center;
        -webkit-flex-flow: row wrap;
        -moz-flex-flow: row wrap;
        -ms-flex-flow: row wrap;
        flex-flow: row wrap;
        -webkit-justify-content: center;
        -moz-justify-content: center;
        -ms-justify-content: center;
        justify-content: center;
    }

    .tabs nav ul li {
        position: relative;
        z-index: 1;
        display: block;
        margin: 0;
        text-align: center;
        -webkit-flex: 1;
        -moz-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    .tabs nav a {
        position: relative;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 2.5;
        color: #891635;
    }

    .tabs nav a span {
        vertical-align: middle;
        font-size: 1em;
    }

    .tabs nav li.tab-current a {
        color: #74777b;
    }

    .tabs nav a:focus {
        outline: none;
    }

    /* Icons */
    .icon::before {
        z-index: 10;
        display: inline-block;
        margin: 0 0.4em 0 0;
        vertical-align: middle;
        text-transform: none;
        font-weight: normal;
        font-variant: normal;
        font-size: 1.3em;
        font-family: 'stroke7pixeden';
        line-height: 1;
        speak: none;
        -webkit-backface-visibility: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Content */
    .content-wrap {
        position: relative;
    }

    .content-wrap section {
        display: none;
        /*margin: 0 auto;*/
        padding: 0px !important;
        max-width: 100%;
        text-align: center;
    }

    .content-wrap section.content-current {
        display: block;
    }

    .content-wrap section p {
        margin: 0;
        padding: 0.75em 0;
        color: rgba(40, 44, 42, 0.05);
        font-weight: 900;
        font-size: 4em;
        line-height: 1;
    }

    /* Fallback */
    .no-js .content-wrap section {
        display: block;
        padding-bottom: 2em;
        border-bottom: 1px solid rgba(255, 255, 255, 0.6);
    }

    .no-flexbox nav ul {
        display: block;
    }

    .no-flexbox nav ul li {
        min-width: 15%;
        display: inline-block;
    }

    /*****************************/
    /* Underline */
    /*****************************/

    .tabs-style-underline nav {
        background: #fff;
    }

    .tabs-style-underline nav a {
        padding: 0.25em 0 0.5em !important;
        border-left: 1px solid #e7ecea;
        -webkit-transition: color 0.2s;
        transition: color 0.2s;
    }

    .tabs-style-underline nav li:last-child a {
        border-right: 1px solid #e7ecea;
    }

    .tabs-style-underline nav li a::after {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: #891635;
        content: '';
        -webkit-transition: -webkit-transform 0.3s;
        transition: transform 0.3s;
        -webkit-transform: translate3d(0, 150%, 0);
        transform: translate3d(0, 150%, 0);
    }

    .tabs-style-underline nav li.tab-current a::after {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }

    .tabs-style-underline nav a span {
        font-weight: 700;
    }

    .tabs-style-underline nav a:hover {
        text-decoration: none !important;
        color: #74777b !important;
    }

    .fa_class {
        font-size: 22px !important;
    }

    @media screen and (max-width: 58em) {
        .tabs nav a.icon span {
            display: none;
        }

        .tabs nav a:before {
            margin-right: 0;
        }
    }

    #dynamicDataTableFilter_0 {
        margin-top: 6px !important;
    }
</style>
<input type="hidden" id="user_type" value="<?php echo $user_type; ?>">
<input type="hidden" id="emp_id" value="<?php echo $user_id; ?>">
<!--<script src="../../../assets/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>-->
<div class="page-content-wrapper">
    <div class="page-content" style="min-height: 1800px !important;overflow-y: scroll; ">
        <div class="col-md-12 ">
            <div class="row wrapper-shadow">
                <div class="portlet light bordered">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->

                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="icon-settings font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">Run Payroll</span>
                                </div>

                            </div>


                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tabs tabs-style-underline">
                                            <nav>

                                                <ul>
                                                    <li class="tab-current"><a id="tab-0" href="#tab_5_1"><i
                                                                    class="fa fa-users mr-1 fa_class"></i> <span>  Run Payroll</span></a>
                                                    </li>
                                                    <?php if ($user_type == 4) { ?>
                                                        <li class=""><a href="#tab_5_2" class="icon"
                                                                        onclick="get_rel_req_data()"><i
                                                                        class="fa fa-file mr-1 fa_class"></i>
                                                                <span> Release Requests</span></a></li>
                                                    <?php } else { ?>
                                                        <li class=""><a href="#tab_5_3" id="tab-2" class="icon"
                                                                        onclick="get_rel_req_datahr()"><i
                                                                        class="fa fa-mail-bulk mr-1 fa_class"></i>
                                                                <span> Request to Release Payslip</span></a></li>
                                                        <li class=""><a href="#tab_5_4"
                                                                        onclick="get_rel_req_datahrHisotry()" id="tab-3"
                                                                        class="icon"
                                                            ><i
                                                                        class="fa fa-tasks mr-1 fa_class"></i> <span> History</span></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </nav>
                                            <div class="content-wrap">
                                                <section class="content-current" id="tab_5_1">
                                                    <div class="panel-body">
                                                        <div class="col-md-12">
                                                            <?php if ($user_type == 5) { ?>
                                                                <div class="col-md-3">
                                                                    <select id="employee_id" name="employee_id"
                                                                            class="form-control"
                                                                            onchange="get_data_employee_wise()">
                                                                        <option>Select Employee</option>
                                                                    </select>
                                                                </div>
                                                            <?php } ?>

                                                            <div class="col-md-3">
                                                                <select class="form-control" id="select_year"
                                                                        name="select_year" onchange="get_months();">
                                                                    <option>Select Year</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <select class="form-control" id="month" name="month"
                                                                        onchange="get_btn();get_button_rqst_sal()">
                                                                    <option>Select Month</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div id="btn_div" class="col-md-6" style="display:none">
                                                                    <button class="form-control btn btn-primary"
                                                                            type="button"
                                                                            onclick="insert_attendance_data()"
                                                                            value="Run Your Payroll"
                                                                            id="run_payroll_btn">Run Your Payroll
                                                                    </button>
                                                                    <input type="hidden" name="runStatus" id="runStatus"
                                                                           value="0">
                                                                </div>
                                                                <?php if ($user_type == 5) { ?>
                                                                    <div class="col-md-6">
                                                                        <button class="form-control btn btn-success"
                                                                                id="btnr_div" style="display:none"
                                                                                type="button"
                                                                                onclick="insert_release_sal_rqst()"
                                                                                value="Request for release salary">
                                                                            Release Salary
                                                                        </button>
                                                                    </div>
                                                                <?php } else { ?>
<!--                                                                    <div class="col-md-6">-->
<!--                                                                        <button class="form-control btn btn-success"-->
<!--                                                                                id="btnr_div" style="display:none"-->
<!--                                                                                type="button"-->
<!--                                                                                onclick="insert_release_sal_rqst()"-->
<!--                                                                                value="Request for release salary">-->
<!--                                                                            Request to Release Payslip-->
<!--                                                                        </button>-->
<!--                                                                    </div>-->
                                                                <?php } ?>
                                                            </div>
                                                            <input type='hidden' id='income_tax' name='income_tax'>
                                                        </div>
                                                        <div id="sample1" style="display:none">
                                                            <div class="caption font-red-sunglo">
                                                                <i class="icon-settings font-red-sunglo"></i>
                                                                <span class="caption-subject bold uppercase">Attendance Details</span>
                                                            </div>
                                                            <div class="table-scrollable"
                                                                 style="overflow:auto; height:180px; ">
                                                                <table class="table table-striped table-bordered "
                                                                       width="400" role="grid"
                                                                       aria-describedby="sample_1_info">
                                                                    <thead>
                                                                    <td>Date</td>
                                                                    <td>In time</td>
                                                                    <td>Out Time</td>
                                                                    <td>Standard Working Hours</td>
                                                                    <td>Final Working Hours</td>
                                                                    <td>Status</td>
                                                                    </thead>
                                                                    <tbody id='t_table_att'>
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <td>Date</td>
																	<td>In time</td>
																	<td>Out Time</td>
                                                                    <td>Standard Working Hours</td>
                                                                    <td>Final Working Hours</td>
                                                                    <td>Status</td>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div id="sample2" style="display:none">
                                                            <div class="caption font-red-sunglo">
                                                                <i class="icon-settings font-red-sunglo"></i>
                                                                <span class="caption-subject bold uppercase">Salary Details</span>
                                                            </div>
                                                            <div class="table-scrollable">
                                                                <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer"
                                                                       id="sample2" role="grid"
                                                                       aria-describedby="sample_1_info">
                                                                    <thead>
                                                                    <thead>
                                                                    <td>Salary Type</td>
                                                                    <td>Standard Amount</td>
                                                                    <td>Amount</td>
                                                                    <!--<td>Loss Of Pay</td>-->
                                                                    <td>Category</td>
                                                                    </thead>
                                                                    <tbody id='t_table_salary'>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </section>
                                                <?php if ($user_type == 4) { ?>

                                                    <section class="" id="tab_5_2">
                                                        <div class="panel-body">
                                                            <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer"
                                                                   id="sample3" role="grid"
                                                                   aria-describedby="sample_1_info">
                                                                <thead>
                                                                <thead>
                                                                <td>Month</td>
                                                                <td>Request Date</td>
                                                                <td>Status</td>
                                                                <td>Salary slip</td>
                                                                </thead>
                                                                <tbody id='t_table_salary_rel'>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </section>

                                                <?php } else { ?>
                                                    <section class="tab-pane " id="tab_5_3">
                                                        <div class="panel-body">
                                                            <div class="panel-body">
                                                                <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer"
                                                                       id="sample4" role="grid"
                                                                       aria-describedby="sample_1_info">
                                                                    <thead>
                                                                    <thead>
                                                                    <td>Employee Name</td>
                                                                    <td>Month</td>
                                                                    <td>Request Date</td>
                                                                    <td>Status</td>
                                                                    <td>Details</td>
                                                                    <td>Action</td>
                                                                    </thead>
                                                                    <tbody id='t_table_salary_rel_hr'>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <section class="tab-pane" id="tab_5_4">
                                                        <div class="panel-body">
                                                            <div class="panel-body">
                                                                <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer"
                                                                       id="sample41" role="grid"
                                                                       aria-describedby="sample_1_info">
                                                                    <thead>
                                                                    <thead>
                                                                    <td>Employee Name</td>
                                                                    <td>Month</td>
                                                                    <td>Request Date</td>
                                                                    <td>Status</td>
                                                                    <td>Salary slip</td>
                                                                    <td>Details</td>
                                                                    <td>Action</td>
                                                                    </thead>
                                                                    <tbody id='t_table_salary_rel_history'>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </section>
                                                <?php } ?>
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

        </div>
    </div>


</div>
<div class="modal fade" id="viewdetail" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Salary Detail</h4>

            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Attendance Details</span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="other_detail">

                            </div>
                        </div>
                    </div>
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Salary Details</span>
                    </div>

                    <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer"
                               id="sample2" role="grid" aria-describedby="sample_1_info">
                            <thead>
                            <thead>
                            <td>Salary Type</td>
                            <td>Standard Amount</td>
                            <td>Amount</td>
                            <td>Loss Of Pay</td>
                            <td>Category</td>
                            </thead>
                            <tbody id='employee_sal_data'>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>

    </div>
</div>
<div class="modal fade" id="payrollLeaveAdditionAsk" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Run Payroll</h4>

            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Leave apllication acceptance</span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <select class="form-control" name="leave_accept" id="leave_accept">
                                <option value="No">NO</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <textarea name="leave_accept_reason" id="leave_accept_reason" rows="4"
                                      class="form-control"></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="$('#run_payroll_btn').click();"
                        data-dismiss="modal">Run Payroll
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js"
        type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/cbpFWTabs.js" type="text/javascript"></script>

<!--modal-->
<script>
    $(document).ready(function () {
        get_months();
        //get_rel_req_datahr();

        get_employees();
        [].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
            new CBPFWTabs(el);
        });
    });

    function get_employees() {
        $.ajax({
            url: '<?= base_url("DashboardController/get_junior_employees") ?>',
            type: "POST",
            success: function (success) {
                success = JSON.parse(success);
                var data = success.data;
                if (success.message == 'success') {
                    $("#employee_id").html(data);
                } else {
                    $("#employee_id").hide();
                }
            }
        });
    }

    $('#select_year').each(function () {

        var year = (new Date()).getFullYear();
        var current = year;
        if (current == "2020") {
            $(this).append('<option  value="' + (year) + '">' + (year) + '</option>');
        } else {

            year -= 1;
            for (var i = 0; i < 2; i++) {
                if ((year + i) == current) {
                    $(this).append('<option  value="' + (year + i) + '">' + (year + i) + '</option>');
                } else {
                    $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');

                }
            }


        }
    });

	function get_rel_req_datahrHisotry() {
		$.ajax({
			type: "POST",
			url: "<?= base_url("Runpayroll/getDataSalaryReleaseHrhistory") ?>",
			dataType: "json",
			success: function (result) {
				if (result.message === "success") {
					var data = result.data;
					var count = result.count;
					// debugger;
					$("#t_table_salary_rel_history").html(data);
					// debugger;
					$("#sample41").DataTable();
				} else {
					$("#t_table_salary_rel_history").html("");
					$("#sample41").dataTable();
				}
			}

		});
	}
    function get_months() {
        var year = $("#select_year").val();

        var user_type = $("#user_type").val();
        if (user_type == 5) {
            var employee_id = $("#employee_id").val();
            if (employee_id == "Search by Employee") {
                alert('Please select Employee');
                $("#select_year").val("");
                return;
            }
        }
        var d = new Date();
        var curr = d.getMonth() + 1;
        var currY = d.getFullYear();
        var month = ["January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"];


        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/get_month_list") ?>",
            dataType: "json",
            data: {year: year, employee_id: employee_id},
            success: function (result) {
                if (result.message == "success") {
                    var montharr = result.montharr;
                    $("#month").html("<option>Select Month</option>");
                    for (var i = 0; i < montharr.length; i++) {
                        value = montharr[i];
                        console.log(value);
                        if (currY == year) {
                            if (value > curr) {

                            } else {

                                $("#month").append(new Option(month[value - 1], (value)));
                            }
                        } else {
                            $("#month").append(new Option(month[value - 1], (value)));
                        }

                    }

                } else {
                }
            }

        });

        //$("#month").append(new Option(month[prev], (prev + 1)));
    }

    function get_btn() {
        var month = $("#month").val();
        var year = $("#select_year").val();
        get_all_data(month, year);
        get_all_salary_details(month, year);
        get_salary_details();
        $("#btn_div").show();
    }


    function get_button_rqst_sal() {
        var month = $("#month").val();
        var year = $("#select_year").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/get_info_sal_rqst") ?>",
            dataType: "json",
            data: {month: month, year: year},
            success: function (result) {
                if (result.message == "success") {
                    if (result.status == 0) {
                        $("#btn_div").show();
                    } else {
                        $("#btn_div").hide();
                        $("#btnr_div").hide();
                    }

                } else {
                    $("#btnr_div").show();
                }
            }

        });
    }

    function insert_attendance_data() {
        var month = $("#month").val();
        var year = $("#select_year").val();
        var income_tax = $("#income_tax").val();
        var employee_id = $("#employee_id").val();
        var runStatus = $("#runStatus").val();
        var leave_accept = $("#leave_accept").val();
        var leave_accept_reason = $("#leave_accept_reason").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/insert_att_data") ?>",
            dataType: "json",
            data: {
                month: month,
                year: year,
                income_tax: income_tax,
                employee_id: employee_id,
                runStatus: runStatus,
                leave_accept: leave_accept,
                leave_accept_reason: leave_accept_reason
            },
            success: function (result) {
                if (result.message === "success") {
                    alert(result.body);
                    get_all_data(month, year);
                    get_all_salary_details(month, year);


                } else if (result.message === "other") {
                    alert(result.body);
                } else if (result.message === "same") {
                    $("#runStatus").val(result.body);
                    $("#payrollLeaveAdditionAsk").modal('show');
                } else {
                    alert('Something went wrong.');
                }
            }

        });

    }

    function accept_rel_reqst(id, id2) {
        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/acceptRelReq") ?>",
            dataType: "json",
            data: {id: id, id2: id2},
            success: function (result) {
                if (result.message === "success") {
                    alert(result.body);
                    get_rel_req_datahr();
                } else {
                    alert('Something went wrong.');
                }
            }

        });

    }

    function insert_release_sal_rqst() {
        if (confirm('Are you sure you want to release salary & deduct leave?')) {
            var emp_id = $("#employee_id").val();
            var month = $("#month").val();
            var year = $("#select_year").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url("Runpayroll/insert_sal_release_data") ?>",
                dataType: "json",
                data: {month: month, year: year, emp_id: emp_id},
                success: function (result) {
                    if (result.message === "success") {
                        alert(result.body);
                        $('#second_tb').click();
                        $("#btnr_div").hide();
                    } else {
                        alert('Something went wrong.');
                    }
                }

            });
        }
    }

    function get_all_salary_details(month, year) {
        var emp_id = $("#employee_id").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/getDataSalaryMonthly") ?>",
            dataType: "json",
            data: {month: month, year: year, emp_id: emp_id},
            success: function (result) {
                if (result.message === "success") {
                    var data = result.data;
                    $("#sample2").show();
                    $("#t_table_salary").html(data);
                    get_button_rqst_sal();
                    //$("#btnr_div").show();
                } else {
                    $("#sample2").hide();
                }
            }

        });
    }

    function get_rel_req_data() {

        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/getDataSalaryRelease") ?>",
            dataType: "json",
            success: function (result) {
                if (result.message === "success") {
                    var data = result.data;
                    $("#t_table_salary_rel").html(data);
                } else {
                    $("#sample2").hide();
                }
            }

        });
    }

    function get_usersaldata(month, year, userid) {
        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/getDataSalarydata") ?>",
            dataType: "json",
            data: {month: month, userid: userid, year: year},
            success: function (result) {
                if (result.message === "success") {
                    var data = result.data;
                    var data1 = result.data1;
                    $("#employee_sal_data").html(data);
                    $("#other_detail").html(data1);
                } else {
                    $("#sample2").hide();
                }
            }

        });
    }

    function get_rel_req_datahr() {
        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/getDataSalaryReleaseHr") ?>",
            dataType: "json",
            success: function (result) {
                if (result.message === "success") {
                    var data = result.data;
                    var count = result.count;
                   $("#t_table_salary_rel_hr").html(data);

                    $("#reqst_sal_rel").html(count);
                    // $("#t_table_salary_rel_history").html(data);
                    // $("#sample41").dataTable();
                } else {
                    // $("#t_table_salary_rel_history").html("");
                    $("#reqst_sal_rel").html("");
					// $("#sample41").dataTable();
                }
            }

        });
    }



    function get_all_data(month, year) {
        //t_table_att
        var emp_id = $("#employee_id").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("Runpayroll/getDataMonthly") ?>",
            dataType: "json",
            data: {month: month, year: year, emp_id: emp_id},
            success: function (result) {
                if (result.message === "success") {
                    var data = result.data;
                    $("#sample1").show();
                    $("#sample2").show();
                    $("#t_table_att").html(data);
                } else {
                    $("#sample1").hide();
                    $("#sample2").hide();
                }
            }

        });
    }

    function get_salary_details() {
        var user_type = $("#user_type").val();
        var emp_id = document.getElementById('emp_id').value;
        if (user_type == 5) {
            var emp_id = $("#employee_id").val();
        }

        var year_id = document.getElementById('select_year').value;
        var month = document.getElementById('month').value;
        if (month == 1 || month == 2 || month == 3) {
            year1 = year_id;
            year2 = (year_id) * 1 - (1);
            year_id = year2 + "-" + year1;
        } else {
            year1 = year_id;
            year2 = (year_id) * 1 + (1);
            year_id = year1 + "-" + year2;
        }
        $.ajax({
            type: "POST",
            url: "<?= base_url("Form16/form16_salary_details") ?>",
            dataType: "json",
            async: false,
            cache: false,
            data: {employee_id: emp_id, year_id: year_id},
            success: function (result) {


                var data = result.results;
                var prequisite_info = result.prequisite_info;
                var deduction_amount = result.deduction_amount;
                var provident_value = result.provident_value;
                var yearly_months = result.yearly_months;
                var form_16_gender = result.form_16_configure;
                var tax_on_employement_tax = result.Proffessional_tax;
                var Income_tax = result.Income_tax;
                var paid_tax = result.paid_tax;
                if (result.status === true) {

                    //Gross salary

                    $("#gamt1").val(provident_value);
                    $("#salary_provision_first").html(data);
                    var client_block_prequisite = prequisite_info[0]['value'];
                    if ((prequisite_info[0]['status'] == "0" && prequisite_info[0]['file'] != "")) {
                        //                      $("#value_of_perquisites1").html(0);
                        var value_of_prequisite1 = 0;
                    } else {
                        var value_of_prequisite1 = client_block_prequisite;
                        //                      $("#value_of_perquisites1").html(client_block);
                    }
                    //
                    $("#gross2").val(prequisite_info[0]['value']);
                    var lieu_salary_block = prequisite_info[1]['value'];
                    if (prequisite_info[1]['status'] == "0" && prequisite_info[1]['file'] != "") {
                        //                        $("#profit_lieu_salary1").html(0);
                        var profit_lieu_salary1 = 0;
                    } else {

                        //                        $("#profit_lieu_salary1").html(prequisite_info[1]['value']);
                        var profit_lieu_salary1 = lieu_salary_block;
                    }

                    //                    alert(profit_lieu_salary1);
                    $("#gross3").val(prequisite_info[1]['value']);
                    //                    var total = +data['sum(value)'] + +prequisite_info[0]['value'] + +prequisite_info[1]['value'];

                    if (prequisite_info[0]['status'] == "0" && prequisite_info[1]['status'] == "0") {
                        var total = +data + +value_of_prequisite1 + +profit_lieu_salary1;
                    } else {
                        var total = +data + +value_of_prequisite1 + +profit_lieu_salary1;
                    }

                    $("#total_gross_sal2").html(total);
                    //Loss Allowance

                    $("#Allowance_extent_a1").html(prequisite_info[2]['value']); //text name of a
                    $("#la_text1").val(prequisite_info[2]['value']);
                    $("#Allowance_extent_b1").html(prequisite_info[3]['value']); //text name of b
                    $("#la_text2").val(prequisite_info[3]['value']);
                    var allowance_a1 = prequisite_info[4]['value'];
                    if (prequisite_info[4]['status'] == "0" && prequisite_info[4]['file'] != "") {
                        //                        $("#allowance_a1").html(0);
                        var value_allowance_a1 = 0;
                    } else {
                        //                        $("#allowance_a1").html(prequisite_info[4]['value']); //value of a
                        var value_allowance_a1 = allowance_a1;
                    }


                    $("#la_num1").val(prequisite_info[4]['value']);
                    var allowance_b1 = prequisite_info[5]['value'];
                    if (prequisite_info[5]['status'] == "0" && prequisite_info[5]['file'] != "") {
                        //                        $("#allowance_b1").html(0);
                        var value_allowance_b1 = 0;
                    } else {
                        //                        $("#allowance_b1").html(prequisite_info[5]['value']); //value of b
                        var value_allowance_b1 = allowance_b1;
                    }
                    //                    alert(value_allowance_b1);

                    $("#la_num2").val(prequisite_info[5]['value']);
                    if (prequisite_info[4]['status'] == "0" && prequisite_info[5]['status'] == "0") {
                        var allowance = +value_allowance_a1 + +value_allowance_b1;
                    } else {
                        var allowance = +value_allowance_a1 + +value_allowance_b1;
                    }

                    //                    alert(allowance);

                    //Balance (1-2)

                    var balance = total - allowance;
                    $("#total_balance2").html(Math.abs(balance)); //Total Balance


                    //Deduction
                    //                                                                     $("#standard_deduction1").html("50000");
                    //document.getElementById("standard_deduction1").value = "50000";


                    var entertaiment_allowance = prequisite_info[8]['value'];
                    var entertainment_months = entertaiment_allowance * yearly_months;
                    if (prequisite_info[8]['status'] == "0" && prequisite_info[8]['file'] != "") {
                        var value_allowance = document.getElementById("entertainment_allow1").innerHTML = 0;
                    } else if (yearly_months === 0) {
                        var value_allowance = prequisite_info[8]['value'];
                    } else {
                        var value_allowance = entertainment_months;
                    }


                    $("#ded1").val(prequisite_info[8]['value']);


                    var tax_employment = tax_on_employement_tax;


                    //$("#ded2").val(prequisite_info[8]['value']);
                    //  $("#professional_tax1").html(deduction_amount[0]); //value of Professional Tax

                    $("#other_deduction1").html(deduction_amount[1]); //value of other deduction

                    if (prequisite_info[8]['status'] == "0" && prequisite_info[9]['status'] == "0") {
                        var total_deduction = +value_allowance + +tax_employment;
                    } else {
                        var total_deduction = +value_allowance + +tax_employment;
                    }


                    //Aggregate of 4(a to f)
                    var standard = 50000;
                    var aggregate = +total_deduction + +standard;
                    $("#aggereagate_ac1").html(aggregate);
                    //6. Income chargeable under the Head ‘Salaries’(3-5)

                    var income_chargaeble = balance - aggregate;
                    //7. Add. : Any other income reported by the employee

                    var any_other_income = prequisite_info[6]['value'];
                    if (prequisite_info[6]['status'] == "0" && prequisite_info[6]['file'] != "") {
                        var value_other_income = 0;
                    } else {
                        var value_other_income = any_other_income;
                    }

                    var any_loss_income = prequisite_info[7]['value'];
                    if (prequisite_info[7]['status'] == "0" && prequisite_info[7]['file'] != "") {
                        var value_loss_income = 0;
                    } else {
                        var value_loss_income = any_loss_income;
                    }


                    $("#add_inc").val(prequisite_info[6]['value']);
                    $("#less_inc").val(prequisite_info[7]['value']);
                    // 8. Gross total income (6+7)

                    if (prequisite_info[6]['status'] == "0" || prequisite_info[7]['status'] == "0") {
                        var gross_total_income = income_chargaeble + +value_other_income + +value_loss_income;
                    } else {
                        var gross_total_income = income_chargaeble + +value_other_income + +value_loss_income;
                    }


                    //9. Deductions Under Chapter VIA - A. Sections 80C,80CC and 80CCD
                    //(a) Section 80C
                    //section 80C (i)
                    //                    $("#section_80c_i1 ").html(prequisite_info[11]['value']); //section 80C (i)


                    //                                                                    $("#section_80c_i1").html(provident_value['value']);
                    var value_section_80c_i1 = provident_value;

                    var section_80ci2 = prequisite_info[12]['value'];
                    var section_80ci2_months = section_80ci2 * yearly_months;
                    if (prequisite_info[12]['status'] == "0" && prequisite_info[12]['file'] != "") {
                        var value_section_80c_i2 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80c_i2 = prequisite_info[12]['value'];
                    } else {
                        var value_section_80c_i2 = section_80ci2_months;
                    }

                    var section_80ci3 = prequisite_info[13]['value'];
                    var section_80ci3_months = section_80ci3 * yearly_months;
                    if (prequisite_info[13]['status'] == "0" && prequisite_info[13]['file'] != "") {
                        var value_section_80c_i3 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80c_i3 = prequisite_info[13]['value'];
                    } else {
                        var value_section_80c_i3 = section_80ci3_months;
                    }


                    $("#gmt_text1").val(prequisite_info[10]['value']);
                    //$("#gamt1").val(prequisite_info[11]['value']);
                    $("#gamt2").val(prequisite_info[12]['value']);
                    $("#gamt3").val(prequisite_info[13]['value']);
                    if (prequisite_info[12]['status'] == "0" && prequisite_info[13]['file'] != "") {
                        var Section_80C_total1 = +value_section_80c_i1 + +value_section_80c_i2 + +value_section_80c_i3;
                    } else {
                        var Section_80C_total1 = +value_section_80c_i1 + +value_section_80c_i2 + +value_section_80c_i3;
                    }
                    //$("#section_80c_name22").html(prequisite_info[14]['value']); //section 80C (ii)

                    var section_80c_ii1 = prequisite_info[15]['value'];
                    var section_80c_ii1_months = section_80c_ii1 * yearly_months;
                    if (prequisite_info[15]['status'] == "0" && prequisite_info[15]['file'] != "") {
                        //                        $("#section_80c_ii1").html(0);
                        var value_section_80c_ii1 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80c_ii1 = prequisite_info[15]['value'];
                    } else {
                        //                        $("#section_80c_ii1 ").html(prequisite_info[15]['value']); //section 80C (ii)
                        var value_section_80c_ii1 = section_80c_ii1_months;
                    }

                    var section_80c_ii2 = prequisite_info[16]['value'];
                    var section_80c_ii2_months = section_80c_ii2 * yearly_months;
                    if (prequisite_info[16]['status'] == "0" && prequisite_info[16]['file'] != "") {
                        //                        $("#section_80c_ii2").html(0);
                        var value_section_80c_ii2 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80c_ii2 = prequisite_info[16]['value'];
                    } else {
                        //                        $("#section_80c_ii2 ").html(prequisite_info[16]['value']); //section 80C (ii)
                        var value_section_80c_ii2 = section_80c_ii2_months;
                    }

                    var section_80c_ii3 = prequisite_info[17]['value'];
                    var section_80c_ii3_months = section_80c_ii3 * yearly_months;
                    if (prequisite_info[17]['status'] == "0" && prequisite_info[17]['file'] != "") {
                        //                        $("#section_80c_ii3").html(0);
                        var value_section_80c_ii3 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80c_ii3 = prequisite_info[17]['value'];
                    } else {
                        //                        $("#section_80c_ii3 ").html(prequisite_info[17]['value']); //section 80C (iii)
                        var value_section_80c_ii3 = section_80c_ii3_months;
                    }


                    $("#gmt_text2").val(prequisite_info[14]['value']);
                    $("#gamt21").val(prequisite_info[15]['value']);
                    $("#gamt22").val(prequisite_info[16]['value']);
                    $("#gamt23").val(prequisite_info[17]['value']);
                    if (prequisite_info[15]['status'] == "0" && prequisite_info[15]['file'] != "" && prequisite_info[16]['status'] == "0" && prequisite_info[16]['file'] != "") {
                        var Section_80C_total2 = +value_section_80c_ii1 + +value_section_80c_ii2 + +value_section_80c_ii3;
                    } else {
                        var Section_80C_total2 = +value_section_80c_ii1 + +value_section_80c_ii2 + +value_section_80c_ii3;
                    }
                    $("#section_80c_name33").html(prequisite_info[18]['value']); //section 80C (ii)


                    var section_80c_iii1 = prequisite_info[19]['value'];
                    var section_80c_iii1_months = section_80c_iii1 * yearly_months;
                    if (prequisite_info[19]['status'] == "0" && prequisite_info[19]['file'] != "") {
                        //            $("#section_80c_iii1").html(0);
                        var value_section_80c_iii1 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80c_iii1 = prequisite_info[19]['value'];
                    } else {
                        //            $("#section_80c_iii1 ").html(prequisite_info[19]['value']); //section 80C (iii)
                        var value_section_80c_iii1 = section_80c_iii1_months;
                    }

                    var section_80c_iii2 = prequisite_info[20]['value'];
                    var section_80c_iii2_months = section_80c_iii2 * yearly_months;
                    if (prequisite_info[20]['status'] == "0" && prequisite_info[20]['file'] != "") {
                        //            $("#section_80c_iii2").html(0);
                        var value_section_80c_iii2 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80c_iii2 = prequisite_info[20]['value'];
                    } else {
                        //            $("#section_80c_iii2 ").html(prequisite_info[20]['value']); //section 80C (iii)
                        var value_section_80c_iii2 = section_80c_iii2_months;
                    }

                    var section_80c_iii3 = prequisite_info[21]['value'];
                    var section_80c_iii3_months = section_80c_iii3 * yearly_months;
                    if (prequisite_info[21]['status'] == "0" && prequisite_info[21]['file'] != "") {
                        //            $("#section_80c_iii3").html(0);
                        var value_section_80c_iii3 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80c_iii3 = prequisite_info[21]['value'];
                    } else {
                        //            $("#section_80c_iii3 ").html(prequisite_info[21]['value']); //section 80C (iii)
                        var value_section_80c_iii3 = section_80c_iii3_months;
                    }


                    $("#gmt_text3").val(prequisite_info[18]['value']);
                    $("#gamt31").val(prequisite_info[19]['value']);
                    $("#gamt32").val(prequisite_info[20]['value']);
                    $("#gamt33").val(prequisite_info[21]['value']);
                    if (prequisite_info[19]['status'] == "0" && prequisite_info[19]['file'] != "" && prequisite_info[20]['status'] == "0" && prequisite_info[20]['file'] != "") {
                        var Section_80C_total3 = +value_section_80c_iii1 + +value_section_80c_iii2 + +value_section_80c_iii3;
                    } else {
                        var Section_80C_total3 = +value_section_80c_iii1 + +value_section_80c_iii2 + +value_section_80c_iii3;
                    }
                    //(b) Section 80CCC         Rs.

                    var section_80ccc1 = prequisite_info[22]['value'];
                    var section_80ccc1_months = section_80ccc1 * yearly_months;
                    if (prequisite_info[22]['status'] == "0" && prequisite_info[22]['file'] != "") {
                        var value_section_80ccc1 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80ccc1 = prequisite_info[22]['value'];
                    } else {
                        var value_section_80ccc1 = section_80ccc1_months;
                    }

                    var section_80ccc2 = prequisite_info[23]['value'];
                    var section_80ccc2_months = section_80ccc2 * yearly_months;
                    if (prequisite_info[23]['status'] == "0" && prequisite_info[23]['file'] != "") {
                        //                                                            $("#section_80ccc2").html(0);
                        var value_section_80ccc2 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80ccc2 = prequisite_info[23]['value'];
                    } else {
                        //                                                            $("#section_80ccc2 ").html(prequisite_info[23]['value']); //Section 80CCC
                        var value_section_80ccc2 = section_80ccc2_months;
                    }

                    var section_80ccc3 = prequisite_info[24]['value'];
                    var section_80ccc3_months = section_80ccc3 * yearly_months;
                    if (prequisite_info[24]['status'] == "0" && prequisite_info[24]['file'] != "") {
                        //                                                            $("#section_80ccc3").html(0);
                        var value_section_80ccc3 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80ccc3 = prequisite_info[24]['value'];
                    } else {
                        //                                                            $("#section_80ccc3 ").html(prequisite_info[24]['value']); //Section 80CCC
                        var value_section_80ccc3 = section_80ccc3_months;
                    }


                    $("#sec21").val(prequisite_info[22]['value']);
                    $("#sec22").val(prequisite_info[23]['value']);
                    $("#sec23").val(prequisite_info[24]['value']);
                    if (prequisite_info[22]['status'] == "0" && prequisite_info[22]['file'] != "" && prequisite_info[23]['status'] == "0" && prequisite_info[23]['file'] != "") {
                        var Section_80Ccc_total = +value_section_80ccc1 + +value_section_80ccc2 + +value_section_80ccc3;
                    } else {
                        var Section_80Ccc_total = +value_section_80ccc1 + +value_section_80ccc2 + +value_section_80ccc3;
                    }

                    //(c) Section 80CCD         Rs.
                    var section_80ccd1 = prequisite_info[25]['value'];
                    var section_80ccd1_months = section_80ccd1 * yearly_months;
                    if (prequisite_info[25]['status'] == "0" && prequisite_info[25]['file'] != "") {
                        //                                                            $("#section_80ccd1").html(0);
                        var value_section_80ccd1 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80ccd1 = prequisite_info[25]['value'];
                    } else {
                        //                                                            $("#section_80ccd1 ").html(prequisite_info[25]['value']); //Section 80CCD
                        var value_section_80ccd1 = section_80ccd1_months;
                    }

                    var section_80ccd2 = prequisite_info[26]['value'];
                    var section_80ccd2_months = section_80ccd2 * yearly_months;
                    if (prequisite_info[26]['status'] == "0" && prequisite_info[26]['file'] != "") {
                        //                                                            $("#section_80ccd2").html(0);
                        var value_section_80ccd2 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80ccd2 = prequisite_info[26]['value'];
                    } else {
                        //                                                            $("#section_80ccd2 ").html(prequisite_info[26]['value']); //Section 80CCD
                        var value_section_80ccd2 = section_80ccd2_months;
                    }

                    var section_80ccd3 = prequisite_info[27]['value'];
                    var section_80ccd3_months = section_80ccd3 * yearly_months;
                    if (prequisite_info[27]['status'] == "0" && prequisite_info[27]['file'] != "") {
                        //                                                            $("#section_80ccd3").html(0);
                        var value_section_80ccd3 = 0;
                    } else if (yearly_months === 0) {
                        var value_section_80ccd3 = prequisite_info[27]['value'];
                    } else {
                        //                                                            $("#section_80ccd3 ").html(prequisite_info[27]['value']); //Section 80CCD
                        var value_section_80ccd3 = section_80ccd3_months;
                    }


                    $("#sec31").val(prequisite_info[25]['value']);
                    $("#sec32").val(prequisite_info[26]['value']);
                    $("#sec33").val(prequisite_info[27]['value']);
                    if (prequisite_info[25]['status'] == "0" && prequisite_info[25]['file'] != "" && prequisite_info[26]['status'] == "0" && prequisite_info[26]['file'] != "") {
                        var Section_80CCD_total = +value_section_80ccd1 + +value_section_80ccd2 + +value_section_80ccd3;
                    } else {
                        var Section_80CCD_total = +value_section_80ccd1 + +value_section_80ccd2 + +value_section_80ccd3;
                    }

                    //Aggregate amount deductible under the three sections
                    var aggregate_three_section = Section_80C_total1 + Section_80C_total2 + Section_80C_total3 + Section_80Ccc_total + Section_80CCD_total;
                    //                                                            alert(aggregate_three_section);
                    $("#aggregate_amt_80C3").html(aggregate_three_section);
                    //B. Other Sections ( e.g. 80E, 80G, 80TTA etc) Under Chapter VIA i.e.80C, 80CCC and 80CCD

                    $("#ot_section_header1 ").html(prequisite_info[28]['value']); //Other Sections (i)

                    var other_Sections_i1 = prequisite_info[29]['value'];
                    var other_Sections_i1_months = other_Sections_i1 * yearly_months;
                    if (prequisite_info[29]['status'] == "0" && prequisite_info[29]['file'] != "") {
                        var value_other_Sections_i1 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_i1 = prequisite_info[29]['value'];
                    } else {
                        var value_other_Sections_i1 = other_Sections_i1_months;
                    }

                    var other_Sections_i2 = prequisite_info[30]['value'];
                    var other_Sections_i2_months = other_Sections_i2 * yearly_months;
                    if (prequisite_info[30]['status'] == "0" && prequisite_info[30]['file'] != "") {
                        var value_other_Sections_i2 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_i2 = prequisite_info[30]['value'];
                    } else {
                        var value_other_Sections_i2 = other_Sections_i2_months; //Other Sections (i)
                    }

                    var other_Sections_i3 = prequisite_info[31]['value'];
                    var other_Sections_i3_months = other_Sections_i3 * yearly_months;
                    if (prequisite_info[31]['status'] == "0" && prequisite_info[31]['file'] != "") {
                        var value_other_Sections_i3 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_i3 = prequisite_info[31]['value'];
                    } else {
                        var value_other_Sections_i3 = other_Sections_i3_months;
                    }


                    $("#secbt1").val(prequisite_info[28]['value']);
                    $("#secb11").val(prequisite_info[29]['value']);
                    $("#secb12").val(prequisite_info[30]['value']);
                    $("#secb13").val(prequisite_info[31]['value']);
                    if (prequisite_info[29]['status'] == "0" && prequisite_info[29]['file'] != "" && prequisite_info[30]['status'] == "0" && prequisite_info[30]['file'] != "") {
                        var total_othersection_i = +value_other_Sections_i1 + +value_other_Sections_i2 + +value_other_Sections_i3;
                    } else {
                        var total_othersection_i = +value_other_Sections_i1 + +value_other_Sections_i2 + +value_other_Sections_i3;
                    }
                    $("#ot_section_header2 ").html(prequisite_info[32]['value']); //Other Sections (ii)

                    var other_Sections_ii1 = prequisite_info[33]['value'];
                    var other_Sections_ii1_months = other_Sections_ii1 * yearly_months;
                    if (prequisite_info[33]['status'] == "0" && prequisite_info[33]['file'] != "") {
                        var value_other_Sections_ii1 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_ii1 = prequisite_info[33]['value'];
                    } else {
                        var value_other_Sections_ii1 = other_Sections_ii1_months;
                    }

                    var other_Sections_ii2 = prequisite_info[34]['value'];
                    var other_Sections_ii2_months = other_Sections_ii2 * yearly_months;
                    if (prequisite_info[34]['status'] == "0" && prequisite_info[34]['file'] != "") {
                        var value_other_Sections_ii2 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_ii2 = prequisite_info[34]['value'];
                    } else {
                        var value_other_Sections_ii2 = other_Sections_ii2_months;
                    }

                    var other_Sections_ii3 = prequisite_info[35]['value'];
                    var other_Sections_ii3_months = other_Sections_ii3 * yearly_months;
                    if (prequisite_info[35]['status'] == "0" && prequisite_info[35]['file'] != "") {
                        var value_other_Sections_ii3 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_ii3 = prequisite_info[35]['value'];
                    } else {
                        var value_other_Sections_ii3 = other_Sections_ii3_months; //Other Sections (ii)
                    }


                    $("#secbt2").val(prequisite_info[32]['value']);
                    $("#secb21").val(prequisite_info[33]['value']);
                    $("#secb22").val(prequisite_info[34]['value']);
                    $("#secb23").val(prequisite_info[35]['value']);
                    if (prequisite_info[33]['status'] == "0" && prequisite_info[33]['file'] != "" && prequisite_info[34]['status'] == "0" && prequisite_info[34]['file'] != "") {
                        var total_othersection_ii = +value_other_Sections_ii1 + +value_other_Sections_ii2 + +value_other_Sections_ii3;
                    } else {
                        var total_othersection_ii = +value_other_Sections_ii1 + +value_other_Sections_ii2 + +value_other_Sections_ii3;
                    }
                    //   $("#ot_section_header3 ").html(prequisite_info[36]['value']); //Other Sections (iii)

                    var other_Sections_iii1 = prequisite_info[37]['value'];
                    var other_Sections_iii1_months = other_Sections_iii1 * yearly_months;
                    if (prequisite_info[37]['status'] == "0" && prequisite_info[37]['file'] != "") {
                        var value_other_Sections_iii1 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_iii1 = prequisite_info[37]['value'];
                    } else {
                        // $("#other_Sections_iii1 ").html(prequisite_info[37]['value']); //Other Sections (iii)
                        var value_other_Sections_iii1 = other_Sections_iii1_months;
                    }

                    var other_Sections_iii2 = prequisite_info[38]['value'];
                    var other_Sections_iii2_months = other_Sections_iii2 * yearly_months;
                    if (prequisite_info[38]['status'] == "0" && prequisite_info[38]['file'] != "") {
                        //                                                                        $("#other_Sections_iii2").html(0);
                        var value_other_Sections_iii2 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_iii2 = prequisite_info[38]['value'];
                    } else {
                        //                                                                        $("#other_Sections_iii2 ").html(prequisite_info[38]['value']); //Other Sections (iii)
                        var value_other_Sections_iii2 = other_Sections_iii2_months;
                    }

                    var other_Sections_iii3 = prequisite_info[39]['value'];
                    var other_Sections_iii3_months = other_Sections_iii3 * yearly_months;
                    if (prequisite_info[39]['status'] == "0" && prequisite_info[39]['file'] != "") {
                        //                                                                        $("#other_Sections_iii3").html(0);
                        var value_other_Sections_iii3 = 0;
                    } else if (yearly_months === 0) {
                        var value_other_Sections_iii3 = prequisite_info[39]['value'];
                    } else {
                        //                                                                        $("#other_Sections_iii3 ").html(prequisite_info[39]['value']); //Other Sections (iii)
                        var value_other_Sections_iii3 = other_Sections_iii3_months;
                    }


                    /*  $("#secbt3").val(prequisite_info[36]['value']);
                     $("#secb31").val(prequisite_info[37]['value']);
                     $("#secb32").val(prequisite_info[38]['value']);
                     $("#secb33").val(prequisite_info[39]['value']); */
                    if (prequisite_info[37]['status'] == "0" && prequisite_info[37]['file'] != "" && prequisite_info[38]['status'] == "0" && prequisite_info[38]['file'] != "") {
                        var total_othersection_iii = +value_other_Sections_iii1 + +value_other_Sections_iii2 + +value_other_Sections_iii3;
                    } else {
                        var total_othersection_iii = +value_other_Sections_iii1 + +value_other_Sections_iii2 + +value_other_Sections_iii3;
                    }
                    //10. Aggregate of deductible amount under chapter VI-A
                    var aggregaet_VIA = total_othersection_i + total_othersection_ii + total_othersection_iii;
                    //  $("#aggregate_deductible_amt3").html(aggregaet_VIA);
                    //11. Total Income (8-10 )
                    var total_income_8to10 = gross_total_income - aggregaet_VIA - provident_value;
                    //  $("#total_income8to10_3").html(Math.abs(total_income_8to10));


                    //(d)Section 80CCD (1B)
                    var section_80ccdb = prequisite_info[40]['value'];
                    $("#sec41").val(section_80ccdb);
                    // var section_80ccdb_months = section_80ccdb * yearly_months;
                    if (prequisite_info[40]['status'] == "0" && prequisite_info[40]['file'] != "") {
                        //                                                                        $("#other_Sections_iii3").html(0);
                        var value_section_80ccdb = 0;
                    } else {
                        //                                                                        $("#other_Sections_iii3 ").html(prequisite_info[39]['value']); //Other Sections (iii)
                        var value_section_80ccdb = section_80ccdb;
                    }

                    var valuesection_80ccd_b2 = value_section_80ccdb;

                    var section_80ccd_b2 = prequisite_info[41]['value'];
                    if (prequisite_info[41]['status'] == "0" && prequisite_info[41]['file'] != "") {
                        var value_section_80ccd_b2 = 0;
                    } else if (yearly_months === 0 && section_80ccd_b2 === '') {
                        var addition1_2 = +value_section_80ccdb + +section_80ccd_b2;
                        var valuesection_80ccd_b2 = addition1_2;
                    } else {
                        var valuesection_80ccd_b2 = section_80ccd_b2;
                    }


                    if (valuesection_80ccd_b2 > 50000) {
                        var value_section_80ccd_b2_total = 50000;
                    } else {
                        var value_section_80ccd_b2_total = valuesection_80ccd_b2;
                    }


                    //12. Tax on total Income
//																							
                    // $("#taxon_total_income3").html(Income_tax);
                    // $("#taxon_total_income3").html(Income_tax);
                    get_incom_tax(total_income_8to10).then(function (result_p) {

                        var tax_total_income12 = result_p;


                        //var tax_total_income12 = $("#taxon_total_income3").html();
                        //	alert(tax_total_income12);
                        //13. Rebate U/S 87a
                        if (total_income_8to10 > 250000 && total_income_8to10 <= 350001) {
                            var value_rebate_87a = 2500;
                        } else {
                            var value_rebate_87a = 0;
                        }

                        if (total_income_8to10 < 12500) {
                            var value_rebate_87a = tax_total_income12;
                        } else if (total_income_8to10 == 12500) {
                            var value_rebate_87a = 12500;
                        } else {
                            var value_rebate_87a = 0;
                        }

                        //14. Tax Payable on total income (12-13)
                        var tax_payable_total_income = tax_total_income12 - value_rebate_87a;

                        if (tax_payable_total_income > 0) {
                            //   $("#tax_payable_ttl_income3").html(tax_payable_total_income);
                            var education_healt_css = (tax_payable_total_income) * (4 / 100);
                            // $("#education_health3").html(Math.abs(education_healt_css));

                        } else {
                            $("#tax_payable_ttl_income3").html(0);
                            var education_healt_css = 0;
                            var tax_payable_total_income = 0;
                            $("#education_health3").html(0);
                        }
                        //15.Education & Health Cess 4%


                        //16. Tax payable (14+15)
                        var tax_payable1415 = tax_payable_total_income + education_healt_css;

                        //$("#tax_payable_14_15_3").html((tax_payable1415));

                        //17. Relife Under Section 89 (attach details)
                        var Relife_under_section89 = prequisite_info[42]['value'];
                        var value_Relife_under_section89 = Relife_under_section89;

                        //18. Tax payable (16-17)

                        var tax_payble_16_17 = (tax_payable1415 - value_Relife_under_section89);
                        if (tax_payble_16_17 > 0) {
                            tax_payble_16_17 = ((tax_payble_16_17));
                        } else {
                            tax_payble_16_17 = 0;
                        }


                        //19.Tax Deducted at source U/S 192
                        var tax_deducted_atsource = prequisite_info[43]['value'];
                        var value_tax_deducted_atsource = paid_tax;

                        //20. Tax payable / refundable (17-18)
                        var tax_payable_refundable = (tax_payble_16_17 - value_tax_deducted_atsource);

                        //$("#tax_payble_refundable3").html((tax_payable_refundable));

                        $("#income_tax").val((tax_payable_refundable));
                        // $("#sec17").val((Relife_under_section89));
                    });


                } else {
                }
            },
        });
    }

    function get_incom_tax(total_income) {
        let mysthPromise = new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Form16/get_datastandard") ?>",
                dataType: "json",
                data: {total_income: total_income},
                success: function (result) {
                    if (result.status == 200) {

                        var result_tax = result.result_tax;
                        resolve(result_tax);
                    } else {
                        reject(0);
                    }
                },
            });
        });
        return mysthPromise;
    }
</script>
