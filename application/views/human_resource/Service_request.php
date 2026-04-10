<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');

$data['session_data'] = $session_data;
$user_id = ($session_data['emp_id']);
$user_type = ($session_data['user_type']);


$page_name = 'View Calender Holiday';
$page_name2 = 'View Due Date List';
//var_dump($firm_name_dd);
//echo $firm_id_new;
?>

<style>
    span.error {
        color: red;
    }
    td {
        text-align: center;
    }
    .tabbable-custom>.nav-tabs>li>a {
        margin-right: 0;
        color: black !important;}
    .tabbable-custom>.nav-tabs>li {
        margin-right: 2px;
        background-color: #7cabb7 !important;
        border-top: 2px solid #f9f3f3b5;}

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
	//	max-width: 1200px;
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
<div class="page-fixed-main-content">
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





            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Service Request</span>
                    </div>

                </div>

                <div class="portlet-body">
					<div class="tabs tabs-style-underline">
						<nav>

							<ul>
								<li class="tab-current"><a id="tab-0"  href="#tab_5_5" onclick="get_data_MYleave()"><i
												class="fa fa-clock-o mr-1 fa_class"></i> <span>  My Leave Requests</span></a>
								</li>
								<li class=""><a id="tab-0"  href="#tab_5_1" onclick="get_data_leave()"><i
												class="fa fa-users mr-1 fa_class"></i> <span>Employee Leave Requests</span></a>
								</li>
								<li class=""><a href="#tab_5_2" class="icon" onclick="get_missing_punch_data_hr()"><i class="fa fa-file mr-1 fa_class"></i>
										<span> Regularize Attendance</span></a></li>
								<li class=""><a href="#tab_5_6" id="tab-2" class="icon"
												onclick="get_overtime_data()"><i
												class="fa fa-mail-bulk mr-1 fa_class"></i>
										<span> Overtime Requests</span></a></li>
								<!-- <li class=""><a href="#tab_5_4" id="tab-3" class="icon"
												><i
												class="fa fa-tasks mr-1 fa_class"></i> <span> Other Requests</span></a>
								</li>  -->
							</ul>
						</nav>
						<div class="content-wrap">
							<section class="content-current" id="tab_5_5">

								<div class="panel-body">
									<table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer" id="sampleMyleave" role="grid" aria-describedby="sample_1_info">                                        <thead>
										<tr role="row">
											<!--<th style="text-align:center" scope="col">Leave Type</th>-->
											<th style="text-align:center" scope="col">Requested On</th>
											<th style="text-align:center" scope="col">Status</th>
											<th style="text-align:center" scope="col">Action</th>
										</tr>
										</thead>
										<tbody id="MyLeaveRequests">
										</tbody>
									</table>
								</div>
							</section>
							<section class="" id="tab_5_1">

								<div class="panel-body">
									<table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer" id="sample1" role="grid" aria-describedby="sample_1_info">                                        <thead>
										<tr role="row">
											<th style="text-align:center" scope="col">Employee Name</th>
											<th style="text-align:center" scope="col">Leave Date</th>
											<th style="text-align:center" scope="col">Status</th>
											<th style="text-align:center" scope="col">Action</th>
										</tr>
										</thead>
										<tbody id="leave_request">
										</tbody>
									</table>
								</div>
							</section>
							<section class="" id="tab_5_2">
                                <div class="panel-body text-align-reverse">
                                    <button type="button" id="aceeptallbtn"  class="btn btn-primary" onclick="accept_all_regu_req()">Accept all request</button>
                                </div>
								<div class="panel-body" style="overflow-x: scroll;">
									<table class="table table-striped table-bordered table-hover dtr-responsive dataTable footer"
										   id="sample2" role="grid" aria-describedby ="sample_2_info" >
										<thead>
										<tr role="row">
											<th style="text-align:center" scope="col">Action</th>
											<th style="text-align:center" scope="col">Employee Name</th>
											<th style="text-align:center" scope="col">Missing Punch In Time</th>
											<th style="text-align:center" scope="col">Missing Punch Out Time</th>
											<th style="text-align:center" scope="col">Reason</th>
											<th style="text-align:center" scope="col">Request Type</th>
											<th style="text-align:center" scope="col">Status</th>
											<th style="text-align:center" scope="col">Location status</th>

											<th style="text-align:center" scope="col">PunchInLocation</th>
											<th style="text-align:center" scope="col">PunchOutLocation</th>
										</tr>
										</thead>
										<tbody id="missign_punching_hr">
										</tbody>
									</table>
								</div>
							</section>
							<section class="tab-pane" id="tab_5_6">
								<div class="panel-body">
									<table class="table table-striped table-bordered table-hover dtr-responsive dataTable footer"
										   id="sampleovertime" role="grid" aria-describedby ="sample_2_info" >
										<thead>
										<tr role="row">
											<th style="text-align:center" scope="col">Employee Name</th>
											<th style="text-align:center" scope="col">Date</th>
											<th style="text-align:center" scope="col">Punch In Time</th>
											<th style="text-align:center" scope="col">Punch Out Time</th>
											<th style="text-align:center" scope="col">Status</th>
											<th style="text-align:center" scope="col">Action</th>
										</tr>
										</thead>
										<tbody id="overtimedata">
										</tbody>
									</table>
								</div>
							</section>
							<section class="tab-pane" id="tab_5_4">
								<div class="panel-body">

								</div>
							</section>
						</div>
					</div>
                  <!--  <div class="tabbable-custom ">

                        <div class="tab-content">




                            <div class="tab-pane" id="tab_5_5">
                                <div class="panel-body  table-scrollable" style="overflow-x: scroll;">

                                    <br> <br> <br>
                                    <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer"
                                           id="sample30" role="grid" aria-describedby ="sample_3_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="text-align:center" scope="col">Missing Punch In Time</th>
                                                <th style="text-align:center" scope="col">Missing Punch Out Time</th>
                                                <th style="text-align:center" scope="col">Reason</th>
                                                <th style="text-align:center" scope="col">Status</th>
												<th style="text-align:center" scope="col">Action</th>
												 <th style="text-align:center" scope="col">PunchInLocation</th>
                                                <th style="text-align:center" scope="col">PunchOutLocation</th>

                                            </tr>
                                        </thead>
                                        <tbody id="missign_punching">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>

                <?php $this->load->view('human_resource/footer'); ?>
            </div>

        </div>



    </div>

    <div class="modal fade" id="view_leave_details" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Leave Details</h4>
                </div>
                <div class="modal-body">
                    <div class="form-body">
                        <form id="edit_designation_form" name="edit_designation_form" method="post">
                            <input type="hidden" name="leave_id" id="leave_id" value="">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
                            <input type="hidden" name="emp_user_id" id="emp_user_id" value="">
                            <input type="hidden" name="leave_type1" id="leave_type1" value="">
                            <input type="hidden" name="desig_id" id="desig_id" value="">

                            <!--<div id="leave_data" name="leave_data"></div>-->
                            <div class="col-md-12 ">
                                <div class="col-md-8"></div>

                                <div class="col-md-2" style="float: right">

                                    <button id="approve_all_btn" name="approve_all_btn" type="button" data-toggle="modal" data-target="#myModal1" class="btn sbold red btn-sm" data-dvalue="<?php echo $user_id; ?>">
                                        <i class="fa fa-check"></i> Deny All
                                    </button>
                                </div>

                                <div class="col-md-2" style="float: right">

                                    <button id="deny_all_btn" name="deny_all_btn" type="button" class="btn sbold blue btn-sm" onclick="approve_all('<?php echo $user_id; ?>');">
                                        <i class="fa fa-check"></i> Approve All
                                    </button>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered table-hover dtr-inline  dataTable no-footer" id="sample1" role="grid" >
                                <thead>
                                <th style="text-align:center">Leave Type</th>
                                <th style="text-align:center">Leave Date</th>
                                <th style="text-align:center">Status</th>
                                <th style="text-align:center">Action</th>

                                </thead>
                                <tbody id="leave_data" name="leave_data"></tbody>
                            </table>

                        </form>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>

            </div>

        </div>
    </div>
    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">DENIED REASON</h4>
                </div>
                <div class="modal-body">
                    <form action ="" method="POST" id="reason_id">
                        <input type="hidden" name="deny_id1" id="deny_id1" >
                        <textarea name="den_id1" id="den_id1" ></textarea>
                        <button type="button"  id= "due_date" onclick="deny_all()"  class="btn btn-primary" > ok</button>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-     labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">DENIED REASON</h4>
                </div>
                <div class="modal-body">
                    <form action ="" method="POST" id="reason_id">
                        <input type="hidden" name="deny_id" id="deny_id" >
                        <input type="hidden" name="MyleaveID" id="MyleaveID" >
                        <textarea name="den_id" id="den_id" ></textarea>
                        <button type="button"  id= "due_date" onclick="deny_leave()"  class="btn btn-primary" > ok</button>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="add_miss_punch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Add Missing Punch Request</h4>
                </div>
                <div class="modal-body">
                    <form type="POST" id="missing_punch_form" name="missing_punch_form">
                        <label>Punch In Time</label>
                        <input type="datetime-local" id="punch_in_time" name="punch_in_time" max="<?php echo date('Y-m-d'); ?>T00:00" onchange="validateDate()" class="form-control"><br>
                        <label>Punch Out Time</label>
                        <input type="datetime-local" id="punch_out_time" name="punch_out_time" max="<?php echo date('Y-m-d'); ?>T00:00" class="form-control"><br>
                        <label>Reason</label>
                        <textarea name="reason_missing" id="reason_missing" class="form-control" placeholder="Missing Punch Reason" ></textarea>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" > Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="<?= base_url() ?>assets/js/cbpFWTabs.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        get_data_MYleave();
        abc();
        [].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
            new CBPFWTabs(el);
        });
    });
    function  abc() 
    {
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_location_info") ?>",
            dataType: "json",
            success: function (result) {

            }

        });
    }


    function get_data_MYleave(){
        //MyLeaveRequests
        var user_id=$("#user_id").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/getMyleaves") ?>",
            dataType: "json",
            async: false,
            cache: false,
            data:{user_id},
            success: function (result) {
                var data = result.data;
                if (result.status == 200) {
                    if ($.fn.DataTable.isDataTable('#sampleMyleave')) {
                        $('#sampleMyleave').DataTable().clear().destroy();
                    }
                    $('#MyLeaveRequests').html(data);
                    $('#sampleMyleave').DataTable({paging: true,destroy:true});


                } else {
                    $('#missign_punching_hr').html("");

                }
            },
        });
    }

    function validateDate() {
        var userdate = new Date(document.getElementById("punch_in_time").value).toJSON().slice(0, 10);
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/login_dates") ?>",
            dataType: "json",
            async: false,
            cache: false,
            data: {userdate: userdate},
            success: function (result) {
                var data = result.result;
                if (result.message == 'success') {
                    alert('You already did punch in and out on this date');
                } else {

                }
            },
        });

    }


    function get_missing_punch_data_hr() {
        $("#aceeptallbtn").hide();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_missing_punch_data_hr") ?>",
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                if (result.message == 'success') {
                    if ($.fn.DataTable.isDataTable('#sample2')) {
                        $('#sample2').DataTable().clear().destroy();
                    }
                    console.log(data);
                    $('#missign_punching_hr').empty();
                    $('#missign_punching_hr').html(data);
                    $('#sample2').DataTable({paging: true,destroy:true});
                    $("#aceeptallbtn").show();
                    if(data == ""){
                        $("#aceeptallbtn").hide();
                    }
                } else {
                    $('#missign_punching_hr').html("");

                }
            },
        });
    }

    function approve_m_rqst(id, user_id, date)
    {
        var result = confirm("Want to Approve this Request?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/approve_m_rqst") ?>",
                dataType: "json",
                data: {id: id, user_id: user_id, date: date},
                success: function (result) {
                    if (result.message == 'success') {
                        alert(result.body);
                        // location.reload();
                        get_missing_punch_data_hr();
                    } else {
                        alert(result.body);
                    }
                }

            });
        } else {
            get_missing_punch_data_hr();
        }
    }

    function action_r_rq(id, status)
    {
        if (status == 1)
        {
            var msg = "Want to Approve this Request?";
        } else {
            var msg = "Want to Deny this Request?";
        }

        var result = confirm(msg);
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/action_r_rq") ?>",
                dataType: "json",
                data: {id: id, status: status},
                success: function (result) {
                    if (result.message === "success") {
                        alert(result.body);
                        get_missing_punch_data_hr();
                        get_missing_punch_data();
                    } else {
                        alert(result.body);
                    }
                }

            });
        } else {
            get_missing_punch_data_hr();
        }
    }


    function deny_m_rqst(id)
    {
        var result = confirm("Want to Deny this Request?");
        if (result) {

            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/deny_m_rqst") ?>",
                dataType: "json",
                data: {id: id},
                success: function (result) {
                    if (result.message == 'success') {
                        alert(result.body);
                        get_missing_punch_data_hr();
                    } else {
                        alert(result.body);
                    }
                }

            });
        } else {
            get_missing_punch_data_hr();
        }
    }


    function Cancel_leave(id,date) {
        var result = confirm("Want to Cancel this Request?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/cancelLeave") ?>",
                dataType: "json",
                data: {id: id,date:date},
                success: function (result) {
                    if (result.status == 200) {
                        alert("Leave Canceled Successfully");
                        get_data_MYleave();
                    } else {
                        alert("Unable Canceled Leave.");
                    }
                }

            });
        } else {
            get_missing_punch_data_hr();
        }
    }


    function action_overtime(id, id2)
    {
        var result = confirm("Want to Accept/Deny this Request?");
        if (result) {

            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/ActionOvertimeRqst") ?>",
                dataType: "json",
                data: {id: id, id2: id2},
                success: function (result) {
                    if (result.message == 'success') {
                        alert(result.body);
                        get_overtime_data();
                    } else {
                        alert(result.body);
                    }
                }

            });
        } else {
            get_missing_punch_data_hr();
        }
    }


    $("#missing_punch_form").validate({//form id
        rules: {
            "punch_in_time": {
                required: true
            },
            "punch_out_time": {
                required: true
            },
            "reason_missing": {
                required: true
            },
        },
        errorElement: "span",
        submitHandler: function (form) {
                $.ajax({
                    url: '<?= base_url("ServiceRequestController/AddMissingPunch") ?>',
                    type: "POST",
                    data: $("#missing_punch_form").serialize(),
                    success: function (success) {
                        success = JSON.parse(success);
                        if (success.message === "success") {
                            alert(success.body);
                            $("#missing_punch_form")[0].reset();
                            $("#add_miss_punch").toggle();
                            get_missing_punch_data();
                        } else {
                            alert(success.body);
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
        }
    );


    function deny_all()
    {
        var user_id = document.getElementById('deny_id1').value;
        var leave_id = document.getElementById('leave_id').value;
        var den_id = document.getElementById('den_id1').value;
        var result = confirm("Want to Deny All Leaves?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/deny_all_leave") ?>",
                dataType: "json",
                data: {user_id: user_id, leave_id: leave_id, den_id: den_id},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leaves Denied successfully');
                        location.reload();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            location.reload();
        }
    }


    function get_overtime_data()
    {
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_overtimeData") ?>",
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.data;
                if (result.message == 'success') {
                    $('#overtimedata').html(data);
                    $('#sampleovertime').DataTable({paging: true,destroy: true});

                } else {
                    $('#overtimedata').html("");
                }
            },
        });
    }


    function deny_leave()
    {
        var leave_id = document.getElementById('deny_id').value;
        var leave_id_act = document.getElementById('leave_id').value;
        var result = confirm("Want to Deny this Leave?");
        if (result) {
            var den_id = document.getElementById('den_id').value;
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/deny_emp_leave") ?>",
                dataType: "json",
                data: {leave_id: leave_id, den_id: den_id, leave_id_act: leave_id_act},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leave Denied successfully');
                        location.reload();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            location.reload();
        }
    }


    function get_data_leave()
    {
        $.ajax({
            type: "POST",
            url: "<?= base_url("Leave_management/get_all_leave_requests") ?>",
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                if (result.message == 'success') {
                    if ($.fn.DataTable.isDataTable('#sample1')) {
                        $('#sample1').DataTable().clear().destroy();
                    }
                    $('#leave_request').html(data);
                    $('#sample1').DataTable({paging: true,destroy:true,ordering:false});

                } else {
                    $('#leave_request').html("");
                }
            },
        });
    }


    function get_missing_punch_data()
    {
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_missing_punch_data") ?>",
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                if (result.message == 'success') {
                    $('#missign_punching').html(data);
                    $('#sample30').DataTable({destroy:true});
                } else {
                    $('#missign_punching').html("");
                }
            },
        });
    }

    $('#view_leave_details').on('show.bs.modal', function (e) {
        var leaveid = $(e.relatedTarget).data('leave_id');
        var leave_id = document.getElementById('leave_id').value = leaveid;
        var leavetype1 = $(e.relatedTarget).data('leave_type1');
        var leave_type1 = document.getElementById('leave_type1').value = leavetype1;
        var empuser_id = $(e.relatedTarget).data('emp_user_id');
        var emp_user_id = document.getElementById('emp_user_id').value = empuser_id;
        var desigid = $(e.relatedTarget).data('desig_id');
        var desig_id = document.getElementById('desig_id').value = desigid;
        var ele = document.getElementById('leave_data');
        $.ajax({
            type: "post",
            url: "<?= base_url("/Leave_management/get_emp_leave_data") ?>",
            dataType: "json",
            data: {leave_id: leave_id, emp_user_id: emp_user_id, leave_type1: leave_type1, desig_id: desig_id},
            success: function (result) {
                ele.innerHTML = "";
                if (result['message'] === 'success') {
                    var data = result.result_leave;
                    // var data1 = result.leave_aprv_validation;
                    var leave_aprv_validation_multiple = result.leave_aprv_validation_multiple;
                    for (i = 0; i < data.length; i++)
                    {
                        if (data[i]['status'] === '1') {
                            var status = " <span class='label label-sm label-info'> Requested </span>";
                        } else if (data[i]['status'] === '2') {
                            var status = "<span class='label label-sm label-success'> Approved </span>";
                        } else if (data[i]['status'] === '3') {
                            var status = " <span class='label label-sm label-warning'> Denied </span>";
                        } else {
                            var status = "<span class='label label-sm label-danger'> Close </span>";
                        }
                        if (data[i]['leave_pay_type'] === '0') {
                            var leave_pay_type = " <span class='label label-sm label-info'> With Pay </span>";
                        } else {
                            var leave_pay_type = "<span class='label label-sm label-danger'> Without Pay </span>";
                        }
                        if (data[i]['status'] === '1') {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default" data-toggle="modal" title="Deny!" name="deny_leave" id="deny_leave" data-target="#myModal" data-myvalue="' + data[i]['id'] + '"><i class="fa fa-close"></i></a>';
                        } else if (data[i]['status'] === '2') {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default" data-toggle="modal"title="Deny!" name="deny_leave" id="deny_leave" data-target="#myModal" data-myvalue="' + data[i]['id'] + '"><i class="fa fa-close"></i></a>';
                        } else if (data[i]['status'] === '3') {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default"  data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close"></i></a>';
                        } else {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default"  data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default"  data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close"></i></a>';
                        }
                        // if (data1 === '1')
                        // {
                        //     var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                        //     var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" onclick="deny_leave(' + data[i]['id'] + ');"><i class="fa fa-close"></i></a>';
                        // }

                        // if (leave_aprv_validation_multiple === '1')
                        // {
                        //     var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                        //     var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" onclick="deny_leave(' + data[i]['id'] + ');"><i class="fa fa-close"></i></a>';
                        // }
                        var today = new Date();
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = yyyy + '-' + mm + '-' + dd + ' 00:00:00';

                        var date = data[i]['leave_date'];
                        if (date < today)
                        {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default"  data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default" data-toggle="modal"title="Deny!" name="deny_leave" id="deny_leave" data-target="#myModal" data-myvalue="' + data[i]['id'] + '"><i class="fa fa-close"></i></a>';
                        }
                        var d = new Date(date.split("/").reverse().join("-"));
                        var dd = d.getDate();
                        var mm = d.getMonth() + 1;
                        var yy = d.getFullYear();
                        var compdate_cust_sub = dd + "-" + mm + "-" + yy;
                        // POPULATE SELECT ELEMENT WITH JSON.
                        ele.innerHTML = ele.innerHTML +
                                '<td>' + data[i]['leave_type'] + '</td>' +
                                '<td>' + compdate_cust_sub + '</td>' +
                                '<td>' + status + '</td>' +
                                '<td>' + approve + deny + '</td>';
                    }
                } else {
                }
            }
        });
    });


    function approve_all(id)
    {
        var leave_id = document.getElementById('leave_id').value;
        var user_id = id;
        var result = confirm("Want to Approve All Leaves?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/approve_all_leave") ?>",
                dataType: "json",
                data: {user_id: user_id, leave_id: leave_id},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leaves Approved successfully');
                        location.reload();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            //        location.reload();
        }
    }


    $('#myModal1').on('show.bs.modal', function (e) {
        var deny_id = $(e.relatedTarget).data('dvalue');
        document.getElementById('deny_id1').value = deny_id;
    });


    $('#myModal1').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });


    function approve_leave(id1,leave_id_act)
    {
        var leave_id = id1;
        // var leave_id_act = document.getElementById('leave_id').value;
        var result = confirm("Want to Approve this Leave?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/approve_emp_leave") ?>",
                dataType: "json",
                data: {leave_id: leave_id, leave_id_act: leave_id_act},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leave Approved successfully');
                        // location.reload();
                        get_data_leave();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            //        location.reload();
        }
    }


    $('#myModal').on('show.bs.modal', function (e) {
        var deny_id = $(e.relatedTarget).data('myvalue');
        var myLeaveID = $(e.relatedTarget).data('myLeaveID');
        document.getElementById('deny_id').value = deny_id;
        document.getElementById('MyleaveID').value = myLeaveID;
    });


    $('#myModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });


    function accept_all_regu_req()
    {
        var result = confirm("Want to Approve All Request?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/approve_all_reg_req") ?>",
                dataType: "json",
                data: '',
                success: function (result) {
                    if (result.status == 200) {
                        alert(result.body);
                        get_missing_punch_data_hr();
                    } else {
                        alert(result.body);
                    }
                }
            });
        }
    }
</script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    

