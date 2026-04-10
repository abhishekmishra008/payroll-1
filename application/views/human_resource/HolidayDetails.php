<head>
	<title>New Title</title>
</head>
<?php
//$username_array = $this->session->userdata('login_session');
//
//$username = $username_array['user_id'];
//$usertype = $username_array['user_type'];
$this->load->view('human_resource/navigation');
//defined('BASEPATH') OR exit('No direct script access allowed');
//if ($session = $this->session->userdata('login_session') == '') {
////take them back to signin
//
//
//    redirect(base_url() . 'login');
//}
//$data['session_data'] = $session_data;
//$user_type = ($session_data['user_type']);

if ($data_holiday != '') {
	$List = implode(',', $data_holiday);
} else {
	$List = '';
}
?>

<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet"
	type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
	type="text/css" />
<link rel="stylesheet"
	href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-tagsinput/bootstrap-tagsinput/bootstrap-tagsinput.css" />
<style>
	span.error {
		color: red;
	}

	.required {
		color: red !important;
	}
</style>

<div class="page-content-wrapper">
	<div class="page-content">
		<div class="page-bar">

			<div class="page-toolbar">
				<ul class="page-breadcrumb">
					<li class="<?= ($this->uri->segment(1) === 'show_firm') ? 'active' : '' ?>">
						Home
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


		<div class="col-md-12 ">
			<div class="row wrapper-shadow">
				<div class="portlet light portlet-fit portlet-form">
					<div class="portlet-title">
						<!--                        <div class="caption">
													<i class="icon-settings font-red"></i>
													<span class="caption-subject font-red sbold uppercase">View Designations</span>
												</div>-->
						<div class="actions">
							<button type="button"
								class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers"
								data-toggle="modal" data-target="#myModal" data-container="body"
								data-placement="left" data-trigger="hover" data-content="Add Holiday"><i
									class="fa fa-plus"></i></button>
						</div>

					</div>

				</div>

			</div>
		</div>
		<input type="hidden" id="holiday_array1" name="holiday_array1" value="<?php echo $List ?>">
		<div class="portlet-body">
			<div class="row">
				<div class="col-md-6">
					<div id="calendarIO1"></div>
				</div>
			</div>
		</div>
		<?php $this->load->view('human_resource/footer'); ?>
	</div>


</div>

<div id="myModalCalendar" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Holiday</h4>
			</div>

			<div class="modal-body">
				<div class="form-body">
					<form id="add_calender_holiday_form" name="add_calender_holiday_form" method="post">
						<!--<input type="text" id="user" name="user" value="<?php echo $data_single_holiday->firm_id; ?>">-->
						<div class="row">
							<div class="form-group col-md-6">
								<label>Holiday Name</label>
								<input type="text" class="form-control" id="holiday_name"
									onkeypress="remove_error('holiday_name')" name="holiday_name"
									placeholder="Enter Holiday name">
								<span class="required" style="color: red" id="holiday_name_error"></span>
							</div>
							<div class="form-group col-md-6">
								<label>Date</label>
								<input type="text" class="form-control" id="holiday_date" name="holiday_date"
									placeholder="">
							</div>
						</div>


					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="add_calender_holiday" name="add_calender_holiday" class="btn btn-info ">Add
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>


<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Holiday</h4>
				<h5 id="note_for_holiday"><b>Note:You can add holiday once for a year,So please carefully added.</b>
				</h5>
			</div>

			<div class="modal-body">
				<div class="form-body">
					<form id="add_holiday_form" name="add_holiday_form" method="post">
						<!--<input type="hidden" name="hdn_user_id" id="hdn_user_id" value="<?php // echo $username 
																							?>">-->

						<div class="row">
							<div class="col-md-6">
								<label>Year</label>
								<select name="select_year" id="select_year"
									onchange="check_year_exist();remove_error('select_year')"
									class="late_salary_count1 form-control m-select2 m-select2-general">
									<option value="">Select</option>
								</select>
								<span class="required" style="color: red" id="select_year_error"></span>
							</div>
						</div>


						<div class="row" id="year_selection">
							<div class="col-md-12">
								<div class="form-group">
									<input type="hidden" name="holiday_count" id="holiday_count" value="1">

									<div class="col-md-12">
										<div class="loading" id="loader" style="display:none;"></div>
										<h3><label>Official Holiday</label></h3>
										<select name="select_holiday1" id="select_holiday1"
											onchange="remove_error('select_holiday1');"
											class="form-control m-select2 m-select2-general">
											<option value="">Select</option>
											<option value="1">Monday</option>
											<option value="2">Tuesday</option>
											<option value="3">Wednesday</option>
											<option value="4">Thursday</option>
											<option value="5">Friday</option>
											<option value="6">Saturday</option>
											<option value="0">Sunday</option>
										</select>
										<span class="required" style="color: red" id="select_holiday1_error"></span>
									</div>


									<div class="col-md-6">
										<div class="input-group">
											<label id="d">
												<input type="radio" id="all_check_holiday1" checked=""
													name="all_check_holiday1" value="1" checked
													data-checkbox="icheckbox_flat-grey"
													onclick="customize_applicable_change('customize_yes1', '1')"> All
											</label>&nbsp;&nbsp;&nbsp;&nbsp;
											<label id="dd">
												<input type="radio" id="all_check_holiday1" name="all_check_holiday1"
													value="2" data-checkbox="icheckbox_flat-grey"
													onclick="customize_applicable_change('customize_yes1', '2')">
												Customize </label>&nbsp;&nbsp;&nbsp;&nbsp;
											<label id="ddd">
												<input type="radio" id="all_check_holiday1" name="all_check_holiday1"
													value="3" data-checkbox="icheckbox_flat-grey"
													onclick="customize_applicable_change('customize_yes1', '3')">
												Alternate </label>

										</div>
										<span class="required" style="color: red" id="all_check_holiday1_error"></span>
										<br>

									</div>

									<div class="col-md-12" id="alternateDiv" style="display: none">

										<div class="row">
											<div class="col-md-6">
												<label>Employee</label>
												<select name="employee_list[]" id="employee_list" style="width: 100%!important;" onchange="remove_error('employee_list')"
													class="late_salary_count1 form-control m-select2 m-select2-general" multiple>
													<option value="">Select</option>
												</select>
												<span class="required" style="color: red" id="select_year_error"></span>
											</div>
										</div>

										<label>Select Alternate Start Date</label>
										<input type="date" name="alternate_start_date" id="alternate_start_date" class="form-control">
										<span class="required" style="color: red" id="alternate_start_date_error"></span>
									</div>

									<div class="col-md-12" id="customize_yes1" style="display:none">

										<div class="col-md-12">
											<div class="checkbox" id="checkbox1" id="checkbox_valid"
												onchange="remove_error('checkbox_valid')" name="checkbox1">
												<label><input type="checkbox" id="checkbox_valid"
														onchange="remove_error('checkbox_valid')"
														name="checkbox1[]" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
												<label><input type="checkbox" id="checkbox_valid"
														onchange="remove_error('checkbox_valid')"
														name="checkbox1[]" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
												<label><input type="checkbox" id="checkbox_valid"
														onchange="remove_error('checkbox_valid')"
														name="checkbox1[]" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
												<label><input type="checkbox" id="checkbox_valid"
														onchange="remove_error('checkbox_valid')"
														name="checkbox1[]" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
												<label><input type="checkbox" id="checkbox_valid"
														onchange="remove_error('checkbox_valid')"
														name="checkbox1[]" value="5">5&nbsp;&nbsp;</label>

											</div>
											<span class="required" style="color: red" id="checkbox_valid_error"></span>
											<br>

										</div>

									</div>
									<div id="add_more_holiday_div">
										<div class="col-md-12">
											<button type="button" id='add_more_holiday'
												data-original-title="Add More Holiday" data-toggle="tooltip"
												title="" name='add_more_holiday' class="btn btn-primary"><i
													class="fa fa-plus" aria-hidden="true"></i></button>
											<br><br>
										</div>
									</div>

									<div name="check_holiday" id="check_holiday">
										<div class="col-md-12" id="dynamic_holiday">
										</div>
									</div>


								</div>
							</div>
						</DIV>
						<div class="">
							<div id="leave_data" name="leave_data"></div>
						</DIV>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="add_holiday_btn" name="add_holiday_btn" class="btn btn-info ">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>

</div>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="<?php echo base_url() . "assets/"; ?>pages/scripts/components-select2.min.js"
	type="text/javascript"></script>
<div class="loading" id="loaders7" style="display:none;"></div>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/fullcalendar/fullcalendar.min.js"
	type="text/javascript"></script>
<script>
	$(document).ready(function() {
		getEmployee();
	})

	function getEmployee() {
		$.ajax({
			type: "POST",
			url: "<?= base_url("getEmployeeList") ?>",
			dataType: "json",
			success: function(result) {
				if (result.status === 200) {
					$("#employee_list").html('');
					$("#employee_list").html(result.data);
					$("#employee_list").select2();
				}
			},
			error: function(result) {
				console.log(result);
				if (result.status === 500) {
					alert('Internal error: ' + result.responseText);
				} else {
					alert('Unexpected error.');
				}
			}
		});
	}


	function formatDateToString(date) {
		var dd = (date.getDate() < 10 ? '0' : '') +
			date.getDate();

		var MM = ((date.getMonth() + 1) < 10 ? '0' : '') +
			(date.getMonth() + 1);

		return [dd, MM];
	}

	function getFormattedString(d) {
		return d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + ' ' + d.toString().split(' ')[4];
		// for time part you may wish to refer http://stackoverflow.com/questions/6312993/javascript-seconds-to-time-string-with-format-hhmmss

	}

	function get_holidays_show(date) {
		var d = new Date(date);
		var aa = formatDateToString(d);
		date = getFormattedString(d);
		var date = d.getDate();
		var month = d.getMonth() + 1; // Since getMonth() returns month from 0-11 not 1-12
		var year = d.getFullYear();
		var dateStr = year + "-" + aa[1] + "-" + aa[0];
		let mysthPromise11 = new Promise((resolve, reject) => {
			var array_holiday = document.getElementById("holiday_array1").value;

			var search = array_holiday.search(dateStr);

			if (search != -1) {
				$.ajax({
					type: "POST",
					url: "<?= base_url("DashboardController/get_holiday_name") ?>",
					dataType: "json",
					async: false,
					cache: false,
					data: {
						date: dateStr
					},
					success: function(result) {
						var data = result.h_name;
						if (result.message == 'success') {
							resolve(data);
						} else {
							reject(false);
						}
					},
				});
			} else {
				reject(false);
			}
		});
		return mysthPromise11;
	}

	$('#add_more_holiday').click(function() {
		let holiday_counts = $('#holiday_count').val();
		if (holiday_counts == 1) {
			holiday_counts++;
		}
		var holiday_data1 = '<div class="row" id="div_' + holiday_counts + '">' +
			'<div class="col-md-4 position-relative form-group" id="check_holiday' + holiday_counts + '" name="check_holiday' + holiday_counts + '">' +
			'<select name="select_holiday' + holiday_counts + '" id="select_holiday' + holiday_counts + '" class="form-control m-select2 m-select2-general">' +
			'<option value="">Select</option>' +
			'<option value="1">Monday</option>' +
			'<option value="2">Tuesday</option>' +
			'<option value="3">Wednesday</option>' +
			'<option value="4">Thursday</option>' +
			'<option value="5">Friday</option>' +
			'<option value="6">Saturday</option>' +
			'<option value="0">Sunday</option></select>' +
			'<label id="d' + holiday_counts + '">' +
			'<input type="radio" id="all_check_holiday' + holiday_counts + '" checked="" name="all_check_holiday' + holiday_counts + '" value="1" checked  data-checkbox="icheckbox_flat-grey" onclick="customize_applicable_change(\'customize_yes' + holiday_counts + '\',1)">  All </label>&nbsp;&nbsp;&nbsp;&nbsp;' +
			'<label id="dd' + holiday_counts + '">' +
			'<input  type="radio" id="all_check_holiday' + holiday_counts + '" name="all_check_holiday' + holiday_counts + '" value="2" data-checkbox="icheckbox_flat-grey" onclick="customize_applicable_change(\'customize_yes' + holiday_counts + '\',2)">  Customize </label></div>' +
			'<div class="col-md-12" id="customize_yes' + holiday_counts + '" style="display:none">' +
			'<div class="col-md-12" >' +
			'<div class="checkbox" id="checkbox" name="checkbox">' +
			'<label><input type="checkbox" name="checkbox' + holiday_counts + '[]" value="1">1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> ' +
			' <label><input type="checkbox" name="checkbox' + holiday_counts + '[]" value="2">2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>' +
			' <label><input type="checkbox" name="checkbox' + holiday_counts + '[]" value="3">3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>' +
			' <label><input type="checkbox" name="checkbox' + holiday_counts + '[]" value="4">4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>' +
			'<label><input type="checkbox" name="checkbox' + holiday_counts + '[]" value="5">5&nbsp;&nbsp;</label>' +
			' </div>' +
			'</div>' +
			'</div>' +
			'<div class="col-md-2 position-relative form-group"><button type="button" onclick="remove1(' + holiday_counts + ');" id="close' + holiday_counts + '" name="close' + holiday_counts + '" class="btn btn-danger"><i class="fa fa-window-close"  aria-hidden="true"></i></button></button></div>' +
			'</div>';
		$('#dynamic_holiday').append(holiday_data1);
		$('#holiday_count').val(holiday_counts);
		holiday_counts++;
	});
	//                                                            function customize_applicable_change(){
	//                                                                let holiday_cnt_change = $('#holiday_count').val();
	//                                                                holiday_cnt_change++;
	//                                                                $('#holiday_count').val(holiday_cnt_change);
	//
	//                                                            }


	function remove1(holiday_cnt_data) {
		$('#div_' + holiday_cnt_data).remove();
		let holiday_cnt = $('#holiday_count').val();
		holiday_cnt--;
		$('#holiday_count').val(holiday_cnt);
		for (z = holiday_cnt_data; z <= holiday_cnt; z++) {
			x = z + 1;
			console.log(x);
			console.log(z);
			$('#select_holiday' + x).attr('name', 'select_holiday' + z);
			$('#select_holiday' + x).attr('id', 'select_holiday' + z);
			$('#check_holiday' + x).attr('name', 'check_holiday' + z);
			$('#check_holiday' + x).attr('id', 'check_holiday' + z);
			$('#all_check_holiday' + x).attr('name', 'check_holiday' + z);
			$('#all_check_holiday' + x).attr('id', 'check_holiday' + z);
			$('#d' + x).attr('name', 'check_holiday' + z);
			$('#dd' + x).attr('id', 'check_holiday' + z);
			$('#div_' + x).attr('name', 'div_' + z);
			$('#div_' + x).attr('id', 'div_' + z);
			var button = document.getElementById('close' + x);
			button.setAttribute("onClick", "remove1(" + z + ")");
			$('#close' + x).attr('name', 'close' + z);
			$('#close' + x).attr('id', 'close' + z);
		}
	}

	$('#select_year').each(function() {

		var year = (new Date()).getFullYear();
		var current = year;
		//year -= 3;
		for (var i = 0; i < 6; i++) {
			if ((year + i) == current)
				$(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
			else
				$(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
		}

	})


	function customize_applicable_change(id, id2) {
		var x = document.getElementById(id);
		if (id2 == 2) {
			x.style.display = "block";
			$("#add_more_holiday_div").show();
			$("#alternateDiv").hide();
		} else if (id2 == 3) {
			x.style.display = "none";
			$("#add_more_holiday_div").hide();
			$("#alternateDiv").show();
		} else {
			x.style.display = "none";
			$("#alternateDiv").hide();
			$("#add_more_holiday_div").show();
			//                                                                    $('.late_salary_count1').val('');
		}
	}


	//Add Holiday Ajax
	$("#add_holiday_btn").click(function() {
		var $this = $(this);
		$this.button('loading');
		setTimeout(function() {
			$this.button('reset');
		}, 2000);
		document.getElementById('loader').style.display = "block";
		$.ajax({
			type: "POST",
			url: "<?= base_url("Human_resource/add_holiday") ?>",
			dataType: "json",
			data: $("#add_holiday_form").serialize(),
			success: function(result) {
				if (result.status === true) {
					document.getElementById('loader').style.display = "none";
					alert('Holiday Added successfully');
					$("#add_holiday_form")[0].reset();
					location.reload();
				} else {
					document.getElementById('loader').style.display = "none";
					$('#' + result.id + '_error').html(result.error);
				}
			},
			error: function(result) {
				console.log(result);
				if (result.status === 500) {
					alert('Internal error: ' + result.responseText);
				} else {
					alert('Unexpected error.');
				}
			}
		});
	});

	function remove_error(id) {
		$('#' + id + '_error').html("");
	}

	function start_loading() {
		document.getElementById('loading_div').style.display = "block";
	}

	function stop_loading() {
		document.getElementById('loading_div').style.display = "none";
	}

	function daysInMonth(month, year) {
		return new Date(year, month, 0).getDate();
	}


	function calendar_data() {
		document.getElementById('loaders7').style.display = "block";
		$('.date-picker').datepicker();
		var sta_count = 0;
		$('#calendarIO1').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				defaultView: 'month',
				right: 'month,basicWeek,basicDay'

			},
			selectable: true,
			select: function(start, end, allDay) {

				var d = new Date();
				d.setHours(0, 0, 0, 0);
				var start = new Date(start);
				start.setHours(0, 0, 0, 0);
				if (d < start) {
					$('#myModalCalendar').modal('show');
					var today = new Date();
					var selected_date = (today.getMonth() + 1) + "/" + (today.getDate()) + "/" + (today.getFullYear());
					var a = moment(start).format('YYYY-MM-DD');
					$("#holiday_name").val('');
					$("#holiday_date").val(a);
					//
				} else {
					alert("Date has been Passed for Adding Holiday");
				}
				$('#calendarIO1').fullCalendar('unselect');
				get_single_holiday(start);

			},
			dayRender: function(date, cell) {
				document.getElementById('myModalCalendar').style.display = 'none';
				var res;
				get_holidays_show(date).then(function(result_p) {
					res = result_p;

					//                                                                    $('#myModalCalendar').modal('hide');
					if (res !== false) {

						cell.css("background", "#ca2b368f");
						cell.css("pointer-events", "none");
						cell.html("<br><span>" + res + "</span>");

					} else {
						cell.css("");
					}
				});

			},
			eventAfterAllRender: function(view) {
				document.getElementById('loaders7').style.display = "none";

			}
		});
	}

	$(document).ready(function() {
		calendar_data();
	});


	//    .                                                       <!---Add Other Holiday from calender-->

	$("#add_calender_holiday").click(function() {
		var $this = $(this);
		$this.button('loading');
		setTimeout(function() {
			$this.button('reset');
		}, 2000);
		document.getElementById('loader').style.display = "block";
		$.ajax({
			type: "POST",
			url: "<?= base_url("Human_resource/add_calender_holiday") ?>",
			dataType: "json",
			data: $("#add_calender_holiday_form").serialize(),
			success: function(result) {
				if (result.status === true) {
					document.getElementById('loader').style.display = "none";
					alert('Holiday Added successfully');
					$("#add_holiday_form")[0].reset();
					location.reload();
				} else if (result.code === 201) {
					document.getElementById('loader').style.display = "none";
					alert('Holiday Updated successfully');
					$("#add_holiday_form")[0].reset();
					location.reload();
				} else {
					document.getElementById('loader').style.display = "none";
					$('#' + result.id + '_error').html(result.error);
				}
			},
			error: function(result) {
				console.log(result);
				if (result.status === 500) {
					alert('Internal error: ' + result.responseText);
				} else {
					alert('Unexpected error.');
				}
			}
		});
	});


	function get_single_holiday(start_date) {
		var d = new Date(start_date);
		//                                                                alert(d);
		start_date = getFormattedString(d);
		$.ajax({
			url: '<?= base_url("Human_resource/get_single_holiday1") ?>',
			type: "POST",
			data: {
				start_date: start_date
			},
			success: function(success) {
				var success = JSON.parse(success);
				var data = success.data_holiday;
				$('#holiday_name').val(data.holiday_name);
			}
		});
	}

	function check_year_exist() {
		var select_year = document.getElementById('select_year').value;
		$.ajax({
			url: '<?= base_url("Human_resource/check_year_exist") ?>',
			type: "POST",
			data: {
				select_year: select_year
			},
			success: function(success) {
				var success = JSON.parse(success);
				var data = success.year_exist;
				if (success.message === 'success') {
					alert("You have already added holidays for this year.");
					document.getElementById('year_selection').style.display = 'none';
					document.getElementById('note_for_holiday').style.display = 'none';
				} else {
					document.getElementById('year_selection').style.display = 'block';
					document.getElementById('note_for_holiday').style.display = 'block';
				}

			}
		});
	}


	function open_otherholiday_modal() {
		$.ajax({
			url: '<?= base_url("Human_resource/check_otherholiday") ?>',
			type: "POST",
			success: function(success) {
				var success = JSON.parse(success);
				var data = success.other_category;
				if (success.message === true) {
					document.getElementById('myModalCalendar').style.display = 'block';
				} else {
					document.getElementById('myModalCalendar').style.display = 'none';
				}

			}
		});

	}
</script>