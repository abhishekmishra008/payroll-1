<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username = $this->session->userdata('login_session');
?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="col-md-12">
            <div class="row wrapper-shadow">
                <div class="portlet light portlet-fit portlet-form">

                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-settings font-red-sunglo"></i>
                            <span class="caption-subject font-red sbold uppercase">View Leave Request</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group" >
                                <button id="sample_1_new" class="btn-info btn-simple btn blue-hoki btn-outline sbold uppercase popovers" data-toggle="modal" data-target="#myModal" data-container="body" data-placement="left" data-trigger="hover" data-content="Add Request Leave">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row well-lg " id="table_well">
                            <table class="table table-striped table-bordered table-hover   dtr-inline  dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                <thead>
                                    <tr role="row">
                                        <th style="text-align:center" scope="col">Leave Type</th>
                                        <th style="text-align:center" scope="col">Requested On</th>
                                        <th style="text-align:center" scope="col">Leave Date</th>
                                        <th style="text-align:center" scope="col">Status</th>
                                        <th style="text-align:center" scope="col">Note</th>
                                        <th style="text-align:center" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result !== '') {

                                        foreach ($result as $row) {
                                            ?>
                                        <input type="hidden" id="firm_id1" name="firm_id1" value="<?php echo $row->firm_id; ?>">
                                        <tr>
                                            <td style="text-align: center"><?php echo $row->leave_type; ?></td>
                                            <td style="text-align: center"><?php
                                                $originalDate = $row->leave_requested_on;
                                                $newcompletionDate = date("d-m-Y", strtotime($originalDate));
                                                echo $newcompletionDate;
                                                ?>
                                            </td>
                                            <td style="text-align: center"><?php
                                                $originalDate1 = $row->leave_date;
                                                $newcompletionDate1 = date("d-m-Y", strtotime($originalDate1));
                                                echo $newcompletionDate1;
                                                ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php
                                                if ($row->status == 1) {
                                                    ?>
                                                    <span class="label label-sm label-info " disabled> Requested </span>
                                                <?php } elseif ($row->status == 2) { ?>
                                                    <span class="label label-sm label-success" disabled> Approve </span>
                                                <?php } elseif ($row->status == 3) { ?>
                                                    <span class="label label-sm label-warning" disabled> Denied </span>
                                                <?php } else { ?>
                                                    <span class="label label-sm label-danger" disabled> canceled </span>
                                                <?php }
                                                ?>

                                            </td>
                                            <td><?php echo $row->denied_resaon; ?></td>
                                            <td style="text-align: center">
                                                <?php if ($row->status == 1) {
                                                    ?>
                                                    <a class='btn btn-link' title='DELETE' name='delete_leave' id='delete_leave' onclick='del_leave(<?php echo $row->id; ?>);'><i class='fa fa-trash font-red' style="font-size:22px;"></i></a>
                                                <?php } else { ?>
                                                    <a class='btn btn-link' disabled="" name='delete_leave'title='DELETE id='delete_leave' onclick='del_leave(<?php echo $row->id; ?>);'><i class='fa fa-trash font-red' style="font-size:22px;"></i></a>
                                                <?php } ?>


                                                <?php if ($row->status == 4) { ?>
                                                    <a class='btn btn-link' title='CANCEL' disabled="" name='cancel' id='cancel' onclick='cancel_leave(<?php echo $row->id; ?>);'><i class='fa fa-close' style="font-size:22px;"></i></a>
                                                <?php } else { ?>
                                                    <a class='btn btn-link'title='CANCEL' name='cancel' id='cancel' onclick='cancel_leave(<?php echo $row->id; ?>);'><i class='fa fa-close' style="font-size:22px;"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>


                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
            <?php $this->load->view('human_resource/footer'); ?>
        </div>

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Request Leave</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-body">
                            <form id="leave_request_form" name="leave_request_form" method="post">
                                <div class="col-md-6"><label>Senior Name</label>
                                    <div class="input-group"><span class="input-group-addon">
                                            <i class="fa fa-map-signs"></i></span>
                                        <input type="text" name="sen_name" disabled="" id="sen_name" class="form-control" value="<?php echo $senior_name ?>">
                                    </div><br>
                                </div>
                                <input type="hidden" id="user" name="user" value="<?php echo $user_id ?>">

                                <input type="hidden" id="firm_id" name="firm_id" value="<?php echo $firm_id ?>">
                                <input type="hidden" id="boss_id" name="boss_id" value="<?php echo $boss_id ?>">
                                <input type="hidden" id="designation" name="designation" value="<?php echo $designation_id ?>">
                                <input type="hidden" id="senior_id" name="senior_id" value="<?php echo $senior_id ?>">


                                <div id="leave_type_data"></div>
                                <table id="leave_conf_tbl"  class="table table-striped table-bordered table-hover"style="display: none;width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 20%">Leave Type</th>
                                            <th scope="col" style="width: 40%">Date</th>
                                            <th scope="col" style="width: 40%">With/Without Pay</th>
                                        </tr>
                                    </thead>
                                    <tbody id="leave_table_single">

                                    </tbody>
                                </table>

                            </form>
                        </div>
                    </div>
                    <div class="modal-footer" id="btn_leave_rq">
                        <!--                <button type="button" id="leave_req_btn" name="leave_req_btn" class="btn btn-info">Request Leave</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                    </div>
                </div>

            </div>
        </div>
        <div class="loading" id="loading_div" style="display:none;z-index: 100000;"></div>
        <script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script>
                                            function view_file1(id) {
                                                //        alert(id);
                                                //private public
                                                var x = document.getElementById("multiple");
                                                var y = document.getElementById("single");

                                                if (id === 'multiple') {

                                                    x.style.display = "block";
                                                    y.style.display = "none";
                                                } else if (id === 'single') {

                                                    x.style.display = "none";
                                                    y.style.display = "block";
                                                }

                                            }
                                            function rqst_leave()
                                            {
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("Leave_management/create_leave_req") ?>",
                                                    dataType: "json",
                                                    data: $("#leave_request_form").serialize(),
                                                    success: function (result) {
                                                        if (result.status === true) {
                                                            alert('Request Sent successfully');
                                                            //return;
                                                            location.reload();
                                                        } else {
                                                            //                    $('#message').html(result.error);
                                                            $('#' + result.id + '_error').html(result.error);
                                                        }
                                                    },
                                                    error: function (result) {
                                                        //console.log(result);
                                                        if (result.status === 500) {
                                                            alert('Internal error: ' + result.responseText);
                                                        } else {
                                                            alert('Unexpected error.');
                                                        }
                                                    }
                                                });
                                            }
                                            $('#myModal').on('show.bs.modal', function () {
                                                var designation_id = document.getElementById('designation').value;
                                                var firm_id = document.getElementById('firm_id').value;
                                                var boss_id = document.getElementById('boss_id').value;
                                                var user_id = document.getElementById('user').value;


                                                $.ajax({
                                                    type: 'post',
                                                    url: "<?= base_url("Leave_management/get_type_leave_hr") ?>", //Here you will fetch records
                                                    data: {designation_id: designation_id, firm_id: firm_id, boss_id: boss_id, user_id: user_id}, //Pass $id
                                                    datatype: "json",
                                                    success: function (result) {
                                                        var ele = document.getElementById('leave_type_data');
                                                        var ele1 = document.getElementById('btn_leave_rq');
                                                        ele.innerHTML = "";
                                                        ele1.innerHTML = "";
                                                        obj = JSON.parse(result);
                                                        if (obj.message === 'success') {
                                                            var data1 = obj.leave_type_data;
                                                            var remaining_with_pay_leaves = obj.remaining_with_pay_leaves;
                                                            option = "";
                                                            if (data1[0]['type1'] !== '')
                                                            {
                                                                var type1 = data1[0]['type1'];
                                                                var res1 = type1.split(":");
                                                                var leave_tyep1 = res1[0];
                                                                var num_days1 = res1[1];
                                                                option += '<option value="' + leave_tyep1 + '">' + leave_tyep1 + '</option>';
                                                            } else {

                                                            }
                                                            if (data1[0]['type2'] !== '')
                                                            {
                                                                var type2 = data1[0]['type2'];

                                                                var res2 = type2.split(":");
                                                                var leave_tyep2 = res2[0];
                                                                var num_days2 = res2[1];
                                                                option += '<option value="' + leave_tyep2 + '">' + leave_tyep2 + '</option>';
                                                            } else {

                                                            }
                                                            if (data1[0]['type3'] !== '')
                                                            {
                                                                var type3 = data1[0]['type3'];
                                                                var res3 = type3.split(":");
                                                                var leave_tyep3 = res3[0];
                                                                var num_days3 = res3[1];
                                                                option += '<option value="' + leave_tyep3 + '">' + leave_tyep3 + '</option>';
                                                            } else {

                                                            }
                                                            if (data1[0]['type4'] !== '')
                                                            {
                                                                var type4 = data1[0]['type4'];
                                                                var res4 = type4.split(":");
                                                                var leave_tyep4 = res4[0];
                                                                var num_days4 = res4[1];
                                                                option += '<option value="' + leave_tyep4 + '">' + leave_tyep4 + '</option>';
                                                            } else {

                                                            }
                                                            if (data1[0]['type5'] !== '')
                                                            {
                                                                var type5 = data1[0]['type5'];
                                                                var res5 = type5.split(":");
                                                                var leave_tyep5 = res5[0];
                                                                var num_days5 = res5[1];
                                                                option += '<option value="' + leave_tyep5 + '">' + leave_tyep5 + '</option>';
                                                            } else {
                                                                //                            var leave_tyep5 = '';
                                                            }
                                                            if (data1[0]['type6'] !== '')
                                                            {
                                                                var type6 = data1[0]['type6'];
                                                                var res6 = type6.split(":");
                                                                var leave_tyep6 = res6[0];
                                                                var num_days6 = res6[1];
                                                                option += '<option value="' + leave_tyep6 + '">' + leave_tyep6 + '</option>';
                                                            } else {

                                                            }
                                                            if (data1[0]['type7'] !== '')
                                                            {
                                                                var type7 = data1[0]['type7'];
                                                                var res7 = type7.split(":");
                                                                var leave_tyep7 = res7[0];
                                                                var num_days7 = res7[1];
                                                                option += '<option value="' + leave_tyep7 + '">' + leave_tyep7 + '</option>';
                                                            } else {
                                                                leave_tyep7 = '';
                                                            }
                                                            var single = 'single';
                                                            var multiple = 'multiple';
                                                            var leave_type = 'leave_type';
                                                            var leave_date_single = 'leave_date_single';
                                                            var leave_date_multiple_first = 'leave_date_multiple_first';
                                                            var leave_date_multiple_second = 'leave_date_multiple_second';
                                                            //
                                                            //                        // POPULATE SELECT ELEMENT WITH JSON.

                                                            ele1.innerHTML = ele1.innerHTML +
                                                                    '<button type="button" id="leave_req_btn1" name="leave_req_btn1" onclick="rqst_leave()"class="btn btn-info">Request Leave</button>' +
                                                                    '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                                                            ele.innerHTML = ele.innerHTML +
                                                                    '<div class="form-group">' +
                                                                    '<div class="row">' +
                                                                    '<div class="col-md-12">' +
                                                                    '<div class="col-md-6">' +
                                                                    '<label>Remaining With Pay Leaves</label> ' +
                                                                    '<div class="input-group">' +
                                                                    '<span class="input-group-addon">' +
                                                                    ' <i class="fa fa-map-signs"></i>' +
                                                                    '</span>' +
                                                                    '<input type="number" name="remain_with_pay" disabled="" id="remain_with_pay"  class="form-control" value="' + remaining_with_pay_leaves + '"> ' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '<div class="col-md-6">' +
                                                                    '<label>Select Leave Type</label> ' +
                                                                    '<div class="input-group">' +
                                                                    '<span class="input-group-addon">' +
                                                                    ' <i class="fa fa-map-signs"></i>' +
                                                                    '</span>' +
                                                                    '<select name="leave_type" id="leave_type" onchange="remove_error(\'' + leave_type + '\');remaining_leave();"  class="form-control m-select2 m-select2-general" onchange=""> ' +
                                                                    //'<option value="">Select Option</option>' +
                                                                    '<option value="">Select Option</option>' + option +
                                                                    '</select>' +
                                                                    '</div>' +
                                                                    '<span class="required" id="leave_type_error"></span>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '<div class="row">' +
                                                                    '<div class="col-md-12">' +
                                                                    '<div class="col-md-6">' +
                                                                    '<div class="input-group">' +
                                                                    '<div class="icheck-inline">' +
                                                                    '<label>Select Day Type</label>' +
                                                                    '<label>' +
                                                                    '<input type="radio" id="day_type" name="day_type" value="0" checked  data-checkbox="icheckbox_flat-grey" onclick="view_file1(\'' + single + '\');get_leave_table_single()"> Single </label>' +
                                                                    '<label>' +
                                                                    '<input type="radio" id="day_type" name="day_type" value="1" data-checkbox="icheckbox_flat-grey" onclick="view_file1(\'' + multiple + '\')"> Multiple </label>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '<div class="col-md-6">' +
                                                                    '<div id="single">' +
                                                                    '<label>Select Date</label>' +
                                                                    '<div class="input-group">' +
                                                                    '<span class="input-group-addon">' +
                                                                    '<i class="fa fa-calendar"></i>' +
                                                                    '</span>' +
                                                                    '<input type="Date" min="<?php echo date("Y-m-d"); ?>" name="leave_date_single" onchange="remove_error(\'' + leave_date_single + '\');check_date();get_leave_table_single()" id="leave_date_single"ata-required="1" class="form-control" placeholder="Completion Date"> ' +
                                                                    '</div>' +
                                                                    '<span class="required" id="leave_date_single_error"></span>' +
                                                                    '</div>' +
                                                                    '<div id="multiple" style="display: none;">' +
                                                                    '<label>From</label>' +
                                                                    '<div class="input-group">' +
                                                                    '<span class="input-group-addon">' +
                                                                    '<i class="fa fa-calendar"></i>' +
                                                                    '</span>' +
                                                                    '<input type="Date" min="<?php echo date("Y-m-d"); ?>" name="leave_date_multiple_first" onchange="remove_error(\'' + leave_date_multiple_first + '\'); onDateChange();" id="leave_date_multiple_first"ata-required="1" class="form-control" placeholder="Completion Date"> ' +
                                                                    '</div>' +
                                                                    '<span class="required" id="leave_date_multiple_first_error"></span><br>' +
                                                                    '<label>To</label>' +
                                                                    '<div class="input-group">' +
                                                                    '<span class="input-group-addon">' +
                                                                    '<i class="fa fa-calendar"></i>' +
                                                                    '</span>' +
                                                                    '<input type="Date" min="<?php echo date("Y-m-d"); ?>" name="leave_date_multiple_second" onchange="remove_error(\'' + leave_date_multiple_second + '\');check_date_multiple();get_leave_table_multiple();" id="leave_date_multiple_second"ata-required="1" class="form-control" placeholder="Completion Date"> ' +
                                                                    '</div>' +
                                                                    '<span class="required" id="leave_date_multiple_first_error"></span>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '<div class="row">' +
                                                                    '<div class="col-md-12">' +
                                                                    '<div class="col-md-6">' +
                                                                    '<div id="leave_remaining"></div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>';
                                                            //
                                                        } else if (obj.message === 'after_prob') {
                                                            var data2 = obj.prob_date;
                                                            //alert('Your can request for leave after your probation period('+data2+').');
                                                            var d = new Date(data2.split("/").reverse().join("-"));
                                                            var dd = d.getDate();
                                                            var mm = d.getMonth() + 1;
                                                            var yy = d.getFullYear();
                                                            var newdate = dd + "-" + mm + "-" + yy;
                                                            ele1.innerHTML = ele1.innerHTML + '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                                                            ele.innerHTML = ele.innerHTML +
                                                                    '<div class="form-group">' +
                                                                    '<div class="row">' +
                                                                    '<div class="col-md-12">' +
                                                                    '<div class="col-md-2">' +
                                                                    '</div>' +
                                                                    '<div class="col-md-10">' +
                                                                    'Your can request for leave after your probation period(' + newdate + ').' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>';
                                                        } else if (obj.message === 'after_train') {
                                                            var data3 = obj.train_date;
                                                            //                    alert('Your can request for leave after your Training period('+data3+').');

                                                            var d = new Date(data3.split("/").reverse().join("-"));
                                                            var dd = d.getDate();
                                                            var mm = d.getMonth() + 1;
                                                            var yy = d.getFullYear();
                                                            var newdate = dd + "-" + mm + "-" + yy;
                                                            ele1.innerHTML = ele1.innerHTML + '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                                                            ele.innerHTML = ele.innerHTML +
                                                                    '<div class="form-group">' +
                                                                    '<div class="row">' +
                                                                    '<div class="col-md-12">' +
                                                                    '<div class="col-md-2">' +
                                                                    '</div>' +
                                                                    '<div class="col-md-10">' +
                                                                    'Your can request for leave after your training period(' + newdate + ').' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>';
                                                        } else {
                                                            //                    var data4 = obj.comp_date;
                                                            //                    alert('Your can request for leave after your Confirmation('+data4+').');

                                                            ele1.innerHTML = ele1.innerHTML + '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                                                            ele.innerHTML = ele.innerHTML +
                                                                    '<div class="form-group">' +
                                                                    '<div class="row">' +
                                                                    '<div class="col-md-12">' +
                                                                    '<div class="col-md-2">' +
                                                                    '</div>' +
                                                                    '<div class="col-md-10">' +
                                                                    'Your can request for leave after your confirmation.' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>' +
                                                                    '</div>';
                                                        }
                                                    }
                                                });
                                            });
                                            $('#myModal').on('hidden.bs.modal', function () {
                                                $('#leave_conf_tbl').remove();
                                                $(this).find('form').trigger('reset');


                                            });
                                            function remaining_leave()
                                            {
                                                var leave_type = document.getElementById('leave_type').value;
                                                var designation_id = document.getElementById('designation').value;
                                                var firm_id = document.getElementById('firm_id').value;
                                                var user_id = document.getElementById('user').value;
                                                var ele1 = document.getElementById('leave_remaining');

                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("Leave_management/fetch_remain_leave_hr") ?>",
                                                    dataType: "json",
                                                    data: {leave_type: leave_type, designation_id: designation_id, firm_id: firm_id, user_id: user_id},
                                                    success: function (result) {
                                                        ele1.innerHTML = '';
                                                        if (result.message === 'success') {
                                                            var data1 = result.total_leaves;
                                                            var data2 = result.taken_leaves;
                                                            var data3 = result.req_bfr;
                                                            var data4 = result.aprv_bfr;
                                                            ele1.innerHTML = ele1.innerHTML +
                                                                    '<lable>Leaves Allow:</lable>' +
                                                                    '<input type="text" id="leave_allow" name="leave_allow" class="form-control" disabled="" value="' + data1 + '">' +
                                                                    '<lable>Leaves Taken:</lable>' +
                                                                    '<input type="text" id="leave_taken" name="leave_taken" class="form-control" disabled="" value="' + data2 + '">' +
                                                                    '<input type="hidden" id="request_leave_before" name="request_leave_before"class="form-control"  value="' + data3 + '">' +
                                                                    '<input type="hidden" d="approve_leave_before" name="approve_leave_before" class="form-control" value="' + data4 + '">';
                                                        } else {
                                                        }
                                                    }
                                                });
                                            }

                                            function  remove_error(id) {
                                                $('#' + id + '_error').html("");
                                            }
                                            function del_leave(id)
                                            {
                                                var leave_id = id;
                                                //                alert(cust_sub_id);
                                                var result = confirm("Want to delete this Leave?");
                                                if (result) {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "<?= base_url("Leave_management/delete_leave") ?>",
                                                        dataType: "json",
                                                        data: {leave_id: leave_id},
                                                        success: function (result) {
                                                            if (result.status === true) {
                                                                alert('Leave Deleted successfully');
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
                                            function cancel_leave(id1)
                                            {
                                                var leave_id = id1;
                                                var result = confirm("Want to Cancel this Leave?");
                                                if (result) {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "<?= base_url("Leave_management/cancl_leave") ?>",
                                                        dataType: "json",
                                                        data: {leave_id: leave_id},
                                                        success: function (result) {
                                                            if (result.status === true) {
                                                                alert('Leave Cancel successfully');
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
                                            function get_leave_table_single()
                                            {
                                                var leave_type = document.getElementById('leave_type').value;
                                                var leave_date_single = document.getElementById('leave_date_single').value;
                                                if (leave_type == '')
                                                {
                                                    alert('Please Select Leave Type.');
                                                    document.getElementById('leave_date_single').value = 0;
                                                } else
                                                {
                                                    document.getElementById("leave_conf_tbl").style.display = "block";
                                                    //             document.getElementById("leave_conf_tbl_mul").style.display = "none";
                                                    //            document.getElementById('leave_conf_tbl').display('block');
                                                    var ele1 = document.getElementById('leave_table_single');
                                                    ele1.innerHTML = "";
                                                    var check_bx_with_pay = '<input type="radio" name="with_pay_single" id="with_pay_single" value="0" onclick="check_with_pay_leave();">';
                                                    var check_bx_without_pay = '<input type="radio" name="with_pay_single" id="with_pay_single" checked value="1">';
                                                    ele1.innerHTML = ele1.innerHTML +
                                                            '<td>' + leave_type + '</td>' +
                                                            '<td>' + leave_date_single + '</td>' +
                                                            '<td width="200px;">' + 'With pay ' + check_bx_with_pay + '&nbsp;&nbsp;&nbsp;' + 'Without Pay ' + check_bx_without_pay + '</td>';


                                                }
                                            }

                                            function get_leave_table_multiple()
                                            {
                                                var leave_type = document.getElementById('leave_type').value;
                                                var leave_date_multiple_first = document.getElementById('leave_date_multiple_first').value;
                                                var leave_date_multiple_second = document.getElementById('leave_date_multiple_second').value;
                                                var remaining_leaves = document.getElementById('remain_with_pay').value;

                                                if (leave_type == '')
                                                {
                                                    alert('Please Select Leave Type.');
                                                    document.getElementById('leave_date_multiple_first').value = 0;
                                                    document.getElementById('leave_date_multiple_second').value = 0;
                                                } else
                                                {
                                                    document.getElementById("leave_conf_tbl").style.display = "block";
                                                    //            document.getElementById("leave_conf_tbl").style.display = "none";
                                                    var ele1 = document.getElementById('leave_table_single');
                                                    ele1.innerHTML = "";
                                                    var startDate = new Date(leave_date_multiple_first); //YYYY-MM-DD
                                                    var endDate = new Date(leave_date_multiple_second); //YYYY-MM-DD


                                                    var dateArr = getDateArray(startDate, endDate);
                                                    //            var check_bx_with_pay = '<input type="radio" name="with_pay" value="0" onclick="check_with_pay_leave_multiple();">';
                                                    //            var check_bx_without_pay = '<input type="radio" name="with_pay" value="1">';
                                                    var cnt = 1;

                                                    //console.log(dateArr.length);
                                                    // console.log(remaining_leaves);
                                                    if (dateArr.length > remaining_leaves) {
                                                        alert("You cant able to request leaves more than your avaliable leaves");
                                                    } else {
                                                        ele1.innerHTML = ele1.innerHTML + "<input type='hidden' id='date_cnt' name='date_cnt' value='" + dateArr.length + "'>"
                                                        for (i = 0; i < dateArr.length; i++) {

                                                            ele1.innerHTML = ele1.innerHTML +
                                                                    '<tr>' +
                                                                    '<td>' + leave_type + '</td>' +
                                                                    '<td>' + convert(dateArr[i]) + '</td>' +
                                                                    '<td>' + 'With pay <input type="radio" id="with_pay' + cnt + '" name="with_pay' + cnt + '" value="0" onclick="check_with_pay_leave_multiple(' + cnt + ');"> Without Pay <input type="radio" id="without_pay' + cnt + '" name="with_pay' + cnt + '" checked value="1"></td>' +
                                                                    '</tr>';
                                                            cnt++;

                                                        }

                                                    }
                                                }
                                            }
                                            var getDateArray = function (start, end) {
                                                var arr = new Array();
                                                var dt = new Date(start);
                                                while (dt <= end) {
                                                    arr.push(new Date(dt));
                                                    dt.setDate(dt.getDate() + 1);
                                                }
                                                return arr;
                                            }
                                            function convert(str) {
                                                var date = new Date(str),
                                                        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                                                        day = ("0" + date.getDate()).slice(-2);
                                                //    return [ date.getFullYear(), mnth, day ].join("-");
                                                return [day, mnth, date.getFullYear()].join("-");
                                            }


                                            function check_with_pay_leave()
                                            {
                                                var remain_with_pay = document.getElementById('leave_allow').value;
                                                var leave_taken = document.getElementById('leave_taken').value;
                                                leave_taken++;
                                                //        var leave_taken_new = document.getElementById('leave_taken').value = leave_taken++;
                                                if (remain_with_pay <= leave_taken)
                                                {
                                                    alert("you Can not apply for with pay leave of this type.");
                                                    document.getElementById('with_pay_single').checked = false;
                                                }
                                            }
                                            function check_with_pay_leave_multiple(i)
                                            {
                                                var remain_with_pay = document.getElementById('leave_allow').value;
                                                var leave_taken = document.getElementById('leave_taken').value;
                                                if (remain_with_pay <= leave_taken)
                                                {
                                                    alert("you Can not apply for with pay leave of this type.");
                                                    document.getElementById('without_pay' + i).checked = true;

                                                } else {
                                                    leave_taken++;
                                                    document.getElementById('leave_taken').value = leave_taken++;

                                                }


                                            }


                                            function check_date()
                                            {
                                                var leave_date_single = document.getElementById('leave_date_single').value;
                                                var user_id = document.getElementById('user').value;
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("Leave_management/check_date_present") ?>",
                                                    dataType: "json",
                                                    data: {leave_date_single: leave_date_single, user_id: user_id},
                                                    success: function (result) {
                                                        if (result.status === true) {
                                                            alert('Leave for ' + leave_date_single + ' is aleready created.');
                                                            document.getElementById('leave_date_single').value = 0;
                                                            document.getElementById("leave_conf_tbl").style.display = "none";
                                                            //return;
                                                            //location.reload();
                                                        }
                                                    },

                                                });
                                            }
                                            function check_date_multiple()
                                            {
                                                var leave_date_multiple_first = document.getElementById('leave_date_multiple_first').value;
                                                var leave_date_multiple_second = document.getElementById('leave_date_multiple_second').value;
                                                var user_id = document.getElementById('user').value;
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("Leave_management/check_date_multiple_present") ?>",
                                                    dataType: "json",
                                                    data: {leave_date_multiple_first: leave_date_multiple_first, leave_date_multiple_second: leave_date_multiple_second, user_id: user_id},
                                                    success: function (result) {
                                                        if (result.status === true) {
                                                            var data = result.leave_dates;
                                                            var array = data.split(",");
                                                            alert('Leaves aleready requested for ' + array + ' . Please delete them to create again');
                                                            document.getElementById('leave_date_multiple_first').value = 0;
                                                            document.getElementById('leave_date_multiple_second').value = 0;
                                                            document.getElementById("leave_conf_tbl").style.display = "none";
                                                        }
                                                    },

                                                });
                                            }
                                            function start_loading() {
                                                document.getElementById('loading_div').style.display = "block";
                                            }
                                            function stop_loading() {
                                                document.getElementById('loading_div').style.display = "none";
                                            }
        </script>