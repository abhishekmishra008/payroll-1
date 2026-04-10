<?php
$username_array = $this->session->userdata('login_session');

$username = $username_array['user_id'];
$usertype = $username_array['user_type'];
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
    //take them back to signin


    redirect(base_url() . 'login');
}

$data['session_data'] = $session_data;
$user_type = ($session_data['user_type']);
?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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

    }


</STYLE>
<input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>">
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
        <h1 class="page-title">
        </h1>
        <div class="col-md-12 ">
            <div class="row wrapper-shadow">

                <div class="portlet light portlet-fit portlet-form">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">View Designations</span>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers" data-toggle="modal" data-target="#myModal" data-container="body" data-placement="left" data-trigger="hover" data-content="Add Designation"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="col-md-12">
                            <div id="abcd" class="row">

                                <div class="col-md-4">
                                    <label>Office Name
                                    </label>
                                    <select class="form-control m-select2 m-select2-general" id="ddl_firm_name_fetch" name="ddl_firm_name_fetch" onchange="get_sorted_data()">
                                        <option value="">Select option</option>

                                    </select>
                                    <span class="required" id="ddl_firm_name_fetch_error"></span>   <br>
                                </div>
                                <div class="col-md-5">
                                    <div class="firmname">
                                        <label>Selected Office Name</label>
                                        <div class="caption font-red-sunglo">
                                            <i class="fa fa-bank"></i>
                                            <?php if ($firm_name !== '') { ?>
                                                <span class="caption-subject bold "><?php echo $firm_name; ?></span>
                                            <?php } else {
                                                ?>
                                                <span class="caption-subject bold "><?php echo 'Please select office...'; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="row well-lg " id="table_well">
                                        <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer" id="sample_1"  role="grid" aria-describedby="sample_1_info">
                                            <thead>
                                                <tr role="row">
                                                    <th style="text-align: center;" scope="col" > Role</th>
                                                    <th style="text-align: center;" scope="col"> Business Vertical</th>
                                                    <th style="text-align: center;" scope="col">Created On</th>
                                                    <th style="text-align: center;" scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result_view_designation !== '') {
                                                    foreach ($result_view_designation as $row) {
                                                        ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $row->designation_name; ?><input type="hidden" id="firm" name="firm" value="<?php echo $row->firm_id; ?>">
                                                            </td>
                                                            <td class="comment more" style="text-align: center;"><?php echo $row->designation_roles; ?></td>
                                                            <td style="text-align: center;"><?php
                                                                $originalstartDate = $row->created_on;
                                                                $newstartDate = date("d-m-Y", strtotime($originalstartDate));
                                                                echo $newstartDate;
                                                                ?>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <?php if ($row->designation_id == 'CA') { ?>
                                                                    <a class = "btn btn-link btn-icon-only btn-default" data-toggle = "modal" disabled ><i class = 'fa fa-pencil'></i></a>
                                                                    <a class = "btn btn-link  btn-icon-only btn-default" disabled  ><i class = 'fa fa-remove font-red' ></i></a></td>
                                                            <?php } else {
                                                                ?>
                                                        <a class="btn btn-link btn-icon-only btn-default" data-toggle="modal" data-target="#edit_designation" data-firm_id_a="<?php echo $row->firm_id; ?>" data-desig_id="<?php echo $row->designation_id; ?>"><i class='fa fa-edit'></i></a>
                                                        <a class="btn btn-link  btn-icon-only btn-default" onclick="delete_designation('<?php echo $row->designation_id; ?>')" ><i class='fa fa-remove font-red' ></i></a></td>
                                                    <?php } ?>
                                                    <?php
                                                }
                                                echo "</tr>";
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

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Designation</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-body">
                            <form id="add_designation_form" name="add_designation_form" method="post">
                                <input type="hidden" name="hdn_user_id" id="hdn_user_id" value="<?php echo $username ?>">
                                <?php
                                if ($usertype == 5) {
                                    
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label>Office Name
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-bank"></i>
                                                        </span>
                                                        <select class="form-control m-select2 m-select2-general " id="hq_firm_name" onchange="remove_error('hq_firm_name');get_leave_data();" name="hq_firm_name">
                                                            <option value="">Select Office</option>
                                                        </select></div>
                                                    <span class="required" id="hq_firm_name_error"></span>
                                                </DIV>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                ?>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="loading" id="loader" style="display:none;"></div>
                                                <label>Role</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-envelope"></i>
                                                    </span>
                                                    <input type="text" id="designation_name" name="designation_name" onkeyup="remove_error('designation_name')" class="form-control" placeholder="Designation"> </div>
                                                <span class="required" id="designation_name_error"></span>
                                            </div>
                                            <div class="col-md-12">
                                                <label>Business Vertical</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-globe"></i>
                                                    </span>
                                                    <textarea class="form-control" name="designation_role" onkeyup="remove_error('designation_role')" id="designation_role" rows="3"></textarea>   </div>
                                                <span class="required" id="designation_role_error"></span>
                                            </div>
                                        </DIV>
                                    </div>
                                </DIV>
                                <div class="">
                                    <div id="leave_data" name="leave_data"></div>
                                </DIV>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="add_desig_btn" name="add_desig_btn"class="btn btn-info " >Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- abhishek mishra -- edit designation -->
        <div class="modal fade" id="edit_designation" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Designation</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-body">
                            <form id="edit_designation_form" name="edit_designation_form" method="post">
                                <input type="hidden" name="hdn_user_id1" id="hdn_user_id1" value="<?php echo $username ?>">
                                <input type="hidden" name="desig_id" id="desig_id" value="">
                                <input type="hidden" name="firm_id_a" id="firm_id_a" value="">
                                <div id="firm_name" name="firm_name"></div>
                                <div id="edit_data" name="edit_data"></div>
                                <div class="loading" id="loaders" style="display:none;"></div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="edit_desig_btn" name="edit_desig_btn"class="btn btn-info " >Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="loading" id="loading_div" style="display:none;z-index: 100000;"></div>
        <?php $this->load->view('human_resource/footer'); ?>
    </div>
</div>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

<script>
    function get_sorted_data() {

        var firm_id_fetch = document.getElementById('ddl_firm_name_fetch').value;

        window.location.href = "<?= base_url("/Human_resource/hr_designation/") ?>" + firm_id_fetch;

    }

    function load_desig()
    {

        $('#abcd').hide();

    }

    $(document).ready(function () {
        var user_type = document.getElementById('user_type').value;
        if (user_type == 5)
        {
            load_desig();
        }
        $.ajax({
            url: "<?= base_url("/Human_resource/get_ddl_firm_name") ?>",
            dataType: "json",
            success: function (result) {
                if (result['message'] === 'success') {
                    var data = result.frim_data;
                    $('#boss_id').val(data[0]['reporting_to']);
                    var ele3 = document.getElementById('ddl_firm_name_fetch');
                    var ele4 = document.getElementById('hq_firm_name');
                    for (i = 0; i < data.length; i++)
                    {

                        ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                        ele4.innerHTML = ele4.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                    }
                }
            }
        });
    });

    //get designation related to firms
    // function get_designation() {
    //     var firm_id = document.getElementById('hq_firm_name').value;
    //     var ele = document.getElementById('senior_authority');
    //     $.ajax({
    //         type: "post",
    //         url: "<?= base_url("Designation/get_designation_hr") ?>",
    //         dataType: "json",
    //         data: {firm_id: firm_id},
    //         success: function (result) {
    //             ele.innerHTML = '';
    //             if (result['message'] === 'success') {
    //                 var data1 = result.result_designation;
    //                 var data_hr = result.hr_desig;
    //
    //                 ele.innerHTML = '<option value="" selected="">Select Senior Authority</option>';
    //                 ele.innerHTML = '<option value="' + data_hr.designation_id + '" >' + data_hr.designation_name + '</option>';
    //                 for (j = 0; j < data1.length; j++)
    //                 {
    //                     // POPULATE SELECT ELEMENT WITH JSON.
    //                     ele.innerHTML = ele.innerHTML +
    //                             '<option value="' + data1[j]['designation_id'] + '">' + data1[j]['designation_name'] + '</option>';
    //                 }
    //
    //
    //             } else {
    //             }
    //
    //         }
    //     });
    // }

    function  remove_error(id) {
        $('#' + id + '_error').html("");
    }

    //function toget leave data
    function   get_leave_data() {
        var firm_id = document.getElementById('hq_firm_name').value;
        var ele1 = document.getElementById('leave_data');
        $.ajax({
            type: "post",
            url: "<?= base_url("/Designation/get_data_designation") ?>",
            dataType: "json",
            data: {firm_id: firm_id},
            success: function (result) {
                ele1.innerHTML = "";
                if (result['message'] === 'success') {
                    var data1 = result.result_designation;
                    //                                                                            console.log(data1);
                    var leave_permit = result.leave_permit;
                    var record12 = result.record12;
                    if (data1[0]['type1'] !== '')
                    {
                        var type1 = data1[0]['type1'];
                        var res1 = type1.split(":");
                        //                                                                                 console.log(res1);
                        var leave_tyep1 = res1[0];
                        var num_days1 = res1[1];
                        var num_days11 = res1[2];
                        //                                                                                console.log(num_days1);
                        //                                                                                console.log(num_days11);
                        //                                                                                var req_bfr1 = res1[2];
                        //                                                                                var aprv_bfr1 = res1[3];
                        var t1 = '<label style="">' + leave_tyep1 + ' &nbsp; &nbsp;&nbsp; Number of Days : ' + num_days1 + ' </label>';
                    } else {
                        var leave_tyep1 = '';
                        var num_days1 = '';
                        //                                                                                var req_bfr1 = '';
                        //                                                                                var aprv_bfr1 = '';
                        var t1 = '';
                    }
                    if (data1[0]['type2'] !== '')
                    {
                        var type2 = data1[0]['type2'];
                        var res2 = type2.split(":");
                        var leave_tyep2 = res2[0];
                        var num_days2 = res2[1];
                        var num_days22 = res2[2];
                        console.log(num_days2);
                        console.log(num_days22);
                        //                                                                                var req_bfr2 = res2[2];
                        //                                                                                var aprv_bfr2 = res2[3];
                        var t2 = '<label style="">' + leave_tyep2 + ' &nbsp; &nbsp;&nbsp; Number of Days : ' + num_days2 + ' </label>';
                    } else {
                        var t2 = '';
                        var leave_tyep2 = '';
                        var num_days2 = '';
                        //                                                                                var req_bfr2 = '';
                        //                                                                                var aprv_bfr2 = '';
                    }
                    if (data1[0]['type3'] !== '')
                    {
                        var type3 = data1[0]['type3'];
                        var res3 = type3.split(":");
                        var leave_tyep3 = res3[0];
                        var num_days3 = res3[1];
                        //                                                                                var req_bfr3 = res3[2];
                        //                                                                                var aprv_bfr3 = res3[3];
                        var t3 = '<label style=""> ' + leave_tyep3 + ' &nbsp; &nbsp;&nbsp; Number of Days : ' + num_days3 + ' </label>';
                    } else {
                        var t3 = '';
                        var leave_tyep3 = '';
                        var num_days3 = '';
                        //                                                                                var req_bfr3 = '';
                        //                                                                                var aprv_bfr3 = '';
                    }
                    if (data1[0]['type4'] !== '')
                    {
                        var type4 = data1[0]['type4'];
                        var res4 = type4.split(":");
                        var leave_tyep4 = res4[0];
                        var num_days4 = res4[1];
                        //                                                                                var req_bfr4 = res4[2];
                        //                                                                                var aprv_bfr4 = res4[3];
                        var t4 = '<label style=""> ' + leave_tyep4 + ' &nbsp; &nbsp;&nbsp; Number of Days : ' + num_days4 + ' </label>';
                    } else {
                        var t4 = '';
                        var leave_tyep4 = '';
                        var num_days4 = '';
                        //                                                                                var req_bfr4 = '';
                        //                                                                                var aprv_bfr4 = '';
                    }
                    if (data1[0]['type5'] !== '')
                    {
                        var type5 = data1[0]['type5'];
                        var res5 = type5.split(":");
                        var leave_tyep5 = res5[0];
                        var num_days5 = res5[1];
                        //                                                                                var req_bfr5 = res5[2];
                        //                                                                                var aprv_bfr5 = res5[3];
                        var t5 = '<label style=""> ' + leave_tyep5 + ' &nbsp; &nbsp;&nbsp; Number of Days : ' + num_days5 + ' </label>';
                    } else {
                        var t5 = '';
                        var leave_tyep5 = '';
                        var num_days5 = '';
                    }
                    if (data1[0]['type6'] !== '')
                    {
                        var type6 = data1[0]['type6'];
                        var res6 = type6.split(":");
                        var leave_tyep6 = res6[0];
                        var num_days6 = res6[1];
                        //                                                                                var req_bfr6 = res6[2];
                        //                                                                                var aprv_bfr6 = res6[3];
                        var t6 = '<label style=""> ' + leave_tyep6 + ' &nbsp; &nbsp;&nbsp; Number of Days : ' + num_days6 + ' </label>';
                    } else {
                        var leave_tyep6 = '';
                        var num_days6 = '';
                        //                                                                                var req_bfr6 = '';
                        //                                                                                var aprv_bfr6 = '';
                        var t6 = '';
                    }
                    if (data1[0]['type7'] !== '')
                    {
                        var type7 = data1[0]['type7'];
                        var res7 = type7.split(":");
                        var leave_tyep7 = res7[0];
                        var num_days7 = res7[1];
                        //                                                                                var req_bfr7 = res7[2];
                        //                                                                                var aprv_bfr7 = res7[3];
                        var t7 = '<label style="">' + leave_tyep7 + ' &nbsp; &nbsp;&nbsp; Number of Days : ' + num_days7 + ' </label>';
                    } else {
                        var t7 = '';
                        var leave_tyep7 = '';
                        var num_days7 = '';
                        //                                                                                var req_bfr7 = '';
                        //                                                                                var aprv_bfr7 = '';
                    }
                    //                    alert(date);
                    // POPULATE SELECT ELEMENT WITH JSON.
                    if (leave_permit == 2) {
                        var monthly_leaves = "monthly_leaves";
                        var yearly_leave = "yearly_leave";
                        ele1.innerHTML = ele1.innerHTML +
                                '<div class="row">' +
                                '<div class="col-md-12">' +
                                '<div class="col-md-6">' +
                                '<label>Yearly Leaves</label>' +
                                '<div class="input-group">' +
                                '<span class="input-group-addon">' +
                                '<i class="fa fa-sign-out"></i>' +
                                '</span>' +
                                '<input  maxlength = "2" type="number" max = "99" min="0" id="yearly_leave" value="' + record12[0]['total_yearly_leaves'] + '" name="yearly_leave" onkeyup="remove_error(\'' + yearly_leave + '\');validate(this);" class="form-control" placeholder="Yearly Leaves"> </div>' +
                                //                                                                                        '<span class="required" id="yearly_leave_error" style="color:red"></span>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                '<label>Monthly Leaves</label>' +
                                '<div class="input-group">' +
                                ' <span class="input-group-addon">' +
                                '<i class="fa fa-sign-out"></i>' +
                                '</span>' +
                                '<input type="number" min="0" id="monthly_leaves" maxlength = "3" max = "99" value="' + record12[0]['total_monthly_leaves'] + '" name="monthly_leaves" onkeypress="all_validate(this);" onkeyup="remove_error(\'' + monthly_leaves + '\');" class="form-control" placeholder="Monthly Leaves"> </div>' +
                                '<span class="required" id="monthly_leaves_error" style="color:red"></span>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group"><br>' +
                                '<label style="font-weight:bold">Leaves Configuration</label>  ' +
                                ' <div class="row"> <br>' +
                                '<div class="col-md-1">' +
                                '<label><span style="font-weight:bold;">Types</span></label> ' +
                                ' </div> ' +
                                '<div class="col-md-3">' +
                                '<label><span style="font-weight:bold;">Leave Name</span></label> ' +
                                ' </div> ' +
                                '<div class="col-md-2">' +
                                '<label><span style="font-weight:bold;">Number of Days</span></label> ' +
                                ' </div> ' +
                                '<div class="col-md-3">' +
                                '<label><span style="font-weight:bold;">Leave Request Before(days)</span></label> ' +
                                ' </div> ' +
                                '<div class="col-md-3">' +
                                '<label><span style="font-weight:bold;">Leave Approve Before(days)</span></label> ' +
                                ' </div> ' +
                                ' </div> <br>' +
                                ' <div class="row">' +
                                ' <DIV class="col-md-1"><span style="font-weigth:bold;">Type 1</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type1" name="leave_type1" value="' + leave_tyep1 + ':' + num_days1 + '" onkeyup="remove_error("leave_type1")" class="form-control" placeholder="First Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" maxlength = "1" max ="99" min="1" id="numofdays1" name="numofdays1" value="' + num_days11 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max ="99" min="0" id="req_bfr1" name="req_bfr1" value="' + req_bfr1 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max ="99" min="1" id="aprv_bfr1" name="aprv_bfr1" value="' + aprv_bfr1 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 2</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type2" name="leave_type2"value="' + leave_tyep2 + ':' + num_days2 + '"  onkeyup="remove_error("leave_type2")" class="form-control" placeholder="Second Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" maxlength = "1" max = "99" min="1" id="numofdays2" name="numofdays2" value="' + num_days22 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays2")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="0" id="req_bfr2" name="req_bfr2" value="' + req_bfr2 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="1" id="aprv_bfr2" name="aprv_bfr2" value="' + aprv_bfr2 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 3</span></DIV>' +
                                ' <DIV class="col-md-3"><input type="text" id="leave_type3" name="leave_type3" value="' + leave_tyep3 + '"  onkeyup="remove_error("leave_type3")" class="form-control" placeholder="Third Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" maxlength = "1" max = "99" min="1" id="numofdays3" name="numofdays3" value="' + num_days3 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays3")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="0" id="req_bfr3" name="req_bfr3" value="' + req_bfr3 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="1" id="aprv_bfr3" name="aprv_bfr3" value="' + aprv_bfr3 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                ' <div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 4</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type4" name="leave_type4" value="' + leave_tyep4 + '"  onkeyup="remove_error()" class="form-control" placeholder="Fourth Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" maxlength = "1" max = "99" min="1" id="numofdays4" name="numofdays4" value="' + num_days4 + '" onkeypress="all_validate(this);" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="0" id="req_bfr4" name="req_bfr4" value="' + req_bfr4 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="1" id="aprv_bfr4" name="aprv_bfr4" value="' + aprv_bfr4 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 5</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type5" name="leave_type5" value="' + leave_tyep5 + '"  onkeyup="remove_error()" class="form-control" placeholder="Fifth Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" maxlength = "1" max = "99" min="1" id="numofdays5" name="numofdays5" value="' + num_days5 + '" onkeypress="all_validate(this);" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="0" id="req_bfr5" name="req_bfr5" value="' + req_bfr5 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="1" id="aprv_bfr5" name="aprv_bfr5" value="' + aprv_bfr5 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 6</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type6" name="leave_type6" value="' + leave_tyep6 + '" onkeyup="remove_error()" class="form-control" placeholder="Sixth Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" maxlength = "1" max = "99" min="1" id="numofdays6" name="numofdays6" value="' + num_days6 + '" onkeypress="all_validate(this);" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="0" id="req_bfr16" name="req_bfr6" value="' + req_bfr6 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99"min="1" id="aprv_bfr6" name="aprv_bfr6" value="' + aprv_bfr6 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 7</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type7" name="leave_type7" value="' + leave_tyep7 + '"  onkeyup="remove_error()" class="form-control" placeholder="Seventh Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" maxlength = "1" max = "99" min="1" id="numofdays7" name="numofdays7" value="' + num_days7 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays7")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number" maxlength = "1" max = "99" min="0" id="req_bfr7" name="req_bfr7" value="' + req_bfr7 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<DIV class="col-md-3"><input type="number"maxlength = "1" max = "99"  min="1" id="aprv_bfr7" name="aprv_bfr7" value="' + aprv_bfr7 + '" onkeypress="all_validate(this);" onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<span class="required" id="numofdays7_error"></span>' +
                                '</div>';
                    } else {
                        ele1.innerHTML = ele1.innerHTML +
                                '<input type="hidden" min="0" id="yearly_leave" value="' + record12[0]['total_yearly_leaves'] + '" name="yearly_leave" onkeyup="remove_error()" class="form-control" placeholder="Yearly Leaves"> ' +
                                '<input type="hidden" min="0" id="monthly_leaves" value="' + record12[0]['total_monthly_leaves'] + '" name="monthly_leaves" onkeyup="remove_error()" class="form-control" placeholder="Monthly Leaves"> ' +
                                '<div class="form-group">' +
                                '<label style="font-weight:bold">Leaves Configuration</label> <br>' +
                                ' <div class="row">' +
                                ' <DIV class="col-md-2"><span style="font-weigth:bold;">Type 1:</span></DIV>' +
                                '<DIV class="col-md-5">' + t1 + '<input type="hidden" id="leave_type1" name="leave_type1" value="' + leave_tyep1 + ':' + num_days1 + '" onkeyup="remove_error("leave_type1")" class="form-control" placeholder="First Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays1" name="numofdays1" value="' + num_days11 + '"  onkeyup="remove_error("numofdays1")" class="form-control" placeholder="No Of Days">' +
                                //                                                                                        '<input type="hidden" min="0" id="req_bfr1" name="req_bfr1" value="' + req_bfr1 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                //                                                                                        '<input type="hidden" min="1" id="aprv_bfr1" name="aprv_bfr1" value="' + aprv_bfr1 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                '</DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 2:</span></DIV>' +
                                '<DIV class="col-md-5">' + t2 + '<input type="hidden" id="leave_type2" name="leave_type2"value="' + leave_tyep2 + ':' + num_days2 + '"  onkeyup="remove_error("leave_type2")" class="form-control" placeholder="Second Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays2" name="numofdays2" value="' + num_days22 + '" onkeyup="remove_error("numofdays2")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<input type="hidden" min="0" id="req_bfr2" name="req_bfr2" value="' + req_bfr2 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                //                                                                                        '<input type="hidden" min="1" id="aprv_bfr2" name="aprv_bfr2" value="' + aprv_bfr2 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 3:</span></DIV>' +
                                ' <DIV class="col-md-5">' + t3 + '<input type="hidden" id="leave_type3" name="leave_type3" value="' + leave_tyep3 + '"  onkeyup="remove_error("leave_type3")" class="form-control" placeholder="Third Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays3" name="numofdays3" value="' + num_days3 + '" onkeyup="remove_error("numofdays3")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<input type="hidden" min="0" id="req_bfr3" name="req_bfr13" value="' + req_bfr3 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                //                                                                                        '<input type="hidden" min="1" id="aprv_bfr3" name="aprv_bfr3" value="' + aprv_bfr3 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                '</DIV><br>' +
                                ' <div class="row">' +
                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 4:</span></DIV>' +
                                '<DIV class="col-md-5">' + t4 + '<input type="hidden" id="leave_type4" name="leave_type4" value="' + leave_tyep4 + '"  onkeyup="remove_error()" class="form-control" placeholder="Fourth Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays4" name="numofdays4" value="' + num_days4 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<input type="hidden" min="0" id="req_bfr4" name="req_bfr4" value="' + req_bfr4 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                //                                                                                        '<input type="hidden" min="1" id="aprv_bfr4" name="aprv_bfr4" value="' + aprv_bfr4 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 5:</span></DIV>' +
                                '<DIV class="col-md-5">' + t5 + '<input type="hidden" id="leave_type5" name="leave_type5" value="' + leave_tyep5 + '"  onkeyup="remove_error()" class="form-control" placeholder="Fifth Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays5" name="numofdays5" value="' + num_days5 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<input type="hidden" min="0" id="req_bfr5" name="req_bfr5" value="' + req_bfr5 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                //                                                                                        '<input type="hidden" min="1" id="aprv_bfr5" name="aprv_bfr5" value="' + aprv_bfr5 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                '</DIV> <br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 6:</span></DIV>' +
                                '<DIV class="col-md-5">' + t6 + '<input type="hidden" id="leave_type6" name="leave_type6" value="' + leave_tyep6 + '" onkeyup="remove_error()" class="form-control" placeholder="Sixth Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays6" name="numofdays6" value="' + num_days6 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<input type="hidden" min="0" id="req_bfr6" name="req_bfr6" value="' + req_bfr6 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                //                                                                                        '<input type="hidden" min="1" id="aprv_bfr6" name="aprv_bfr6" value="' + aprv_bfr6 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 7:</span></DIV>' +
                                '<DIV class="col-md-5">' + t7 + '<input type="hidden" id="leave_type7" name="leave_type7" value="' + leave_tyep7 + '"  onkeyup="remove_error()" class="form-control" placeholder="Seventh Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays7" name="numofdays7" value="' + num_days7 + '" onkeyup="remove_error("numofdays7")" class="form-control" placeholder="No Of Days"></DIV>' +
                                //                                                                                        '<input type="hidden" min="0" id="req_bfr7" name="req_bfr7" value="' + req_bfr7 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                //                                                                                        '<input type="hidden" min="1" id="aprv_bfr7" name="aprv_bfr7" value="' + aprv_bfr7 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days">' +
                                '</DIV><br>';
                        //                                    '<span class="required" id="numofdays7_error"></span>' +
                        //                                    '</div>';

                    }
                } else {

                }

            }
        });
    }
    function validateFloatKeyPress(el) {
        var v = parseFloat(el.value);
        el.value = (isNaN(v)) ? '' : v.toFixed(2);
    }

    //add designation
    $("#add_desig_btn").click(function () {
        var $this = $(this);
        $this.button('loading');
        setTimeout(function () {
            $this.button('reset');
        }, 2000);
        document.getElementById('loader').style.display = "block";
        $.ajax({
            type: "POST",
            url: "<?= base_url("Designation/create_designation_hq") ?>",
            dataType: "json",
            data: $("#add_designation_form").serialize(),
            success: function (result) {
                if (result.status === true) {
                    document.getElementById('loader').style.display = "none";
                    alert('Designation created successfully.');
                    //return;
                    location.reload();
                } else {
                    document.getElementById('loader').style.display = "none";
                    //                    $('#message').html(result.error);
                    $('#' + result.id + '_error').html(result.error);
                }
            },
            error: function (result) {
                //console.log(result);
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
    
    //edit Designation
    $('#edit_designation').on('show.bs.modal', function (e) {
        var desigid = $(e.relatedTarget).data('desig_id');
        var desig_id = document.getElementById('desig_id').value = desigid;
        var firmid = $(e.relatedTarget).data('firm_id_a');
        var firm_id = document.getElementById('firm_id_a').value = firmid;
        var ele1 = document.getElementById('edit_data');
        var ele2 = document.getElementById('firm_name');
        //                                                                alert(desig_id);
        //                                                                alert(firm_id);

        $.ajax({
            type: "post",
            url: "<?= base_url("/Designation/get_data_designation_edit") ?>",
            dataType: "json",
            data: {desig_id: desig_id, firm_id: firm_id},
            success: function (result) {
                ele1.innerHTML = "";
                ele2.innerHTML = "";
                if (result['message'] === 'success') {
                    var data = result.result_edit_designation;
                    //console.log(data);
                    var data3 = result.designation_name;
                    console.log(data3);
                    var data1 = result.result_edit_leave;
                    var leave_permit = result.leave_permit;
                    var firm_name = result.firm_name;
                    if (data[0]['year_type'] == 1) {
                        var Yearly_Leaves_Type = "From Date of Joining";
                    }
                    if (data[0]['year_type'] == 2) {
                        var Yearly_Leaves_Type = "Financial Year";
                    }
                    if (data[0]['year_type'] == 3) {
                        var Yearly_Leaves_Type = "Calendar Year";
                    }

                    if (data[0]['request_leave_from'] == 1) {
                        var request_leave_from = "From Date of Joining";
                    }
                    if (data[0]['request_leave_from'] == 2) {
                        var request_leave_from = "After Probation Period";
                    }
                    if (data[0]['request_leave_from'] == 3) {
                        var request_leave_from = "After Training Period";
                    }
                    if (data[0]['request_leave_from'] == 4) {
                        var request_leave_from = "After Confirmation";
                    }

                    if (data[0]['carry_forward_period'] == 1) {
                        var leave_cf = "Monthly";
                    }

                    if (data[0]['carry_forward_period'] == 2) {
                        var leave_cf = "Yearly";
                    }
                    if (data1[0]['type1'] !== '')
                    {
                        var type1 = data1[0]['type1'];
                        var res1 = type1.split(":");
                        var leave_tyep1 = res1[0];
                        var num_days1 = res1[1];
                        var req_bfr1 = res1[2];
                        var aprv_bfr1 = res1[3];
                    } else {
                        var leave_tyep1 = '';
                        var num_days1 = '';
                        var req_bfr1 = '';
                        var aprv_bfr1 = '';
                    }
                    if (data1[0]['type2'] !== '')
                    {
                        var type2 = data1[0]['type2'];
                        var res2 = type2.split(":");
                        var leave_tyep2 = res2[0];
                        var num_days2 = res2[1];
                        var req_bfr2 = res2[2];
                        var aprv_bfr2 = res2[3];
                    } else {
                        var leave_tyep2 = '';
                        var num_days2 = '';
                        var req_bfr2 = '';
                        var aprv_bfr2 = '';
                    }
                    if (data1[0]['type3'] !== '')
                    {
                        var type3 = data1[0]['type3'];
                        var res3 = type3.split(":");
                        var leave_tyep3 = res3[0];
                        var num_days3 = res3[1];
                        var req_bfr3 = res3[2];
                        var aprv_bfr3 = res3[3];
                    } else {
                        var leave_tyep3 = '';
                        var num_days3 = '';
                        var req_bfr3 = '';
                        var aprv_bfr3 = '';
                    }
                    if (data1[0]['type4'] !== '')
                    {
                        var type4 = data1[0]['type4'];
                        var res4 = type4.split(":");
                        var leave_tyep4 = res4[0];
                        var num_days4 = res4[1];
                        var req_bfr4 = res4[2];
                        var aprv_bfr4 = res4[3];
                    } else {
                        var leave_tyep4 = '';
                        var num_days4 = '';
                        var req_bfr4 = '';
                        var aprv_bfr4 = '';
                    }
                    if (data1[0]['type5'] !== '')
                    {
                        var type5 = data1[0]['type5'];
                        var res5 = type5.split(":");
                        var leave_tyep5 = res5[0];
                        var num_days5 = res5[1];
                        var req_bfr5 = res5[2];
                        var aprv_bfr5 = res5[3];
                    } else {
                        var leave_tyep5 = '';
                        var num_days5 = '';
                        var req_bfr5 = '';
                        var aprv_bfr5 = '';
                    }
                    if (data1[0]['type6'] !== '')
                    {
                        var type6 = data1[0]['type6'];
                        var res6 = type6.split(":");
                        var leave_tyep6 = res6[0];
                        var num_days6 = res6[1];
                        var req_bfr6 = res6[2];
                        var aprv_bfr6 = res6[3];
                    } else {
                        var leave_tyep6 = '';
                        var num_days6 = '';
                        var req_bfr6 = '';
                        var aprv_bfr6 = '';
                    }
                    if (data1[0]['type7'] !== '')
                    {
                        var type7 = data1[0]['type7'];
                        var res7 = type7.split(":");
                        var leave_tyep7 = res7[0];
                        var num_days7 = res7[1];
                        var req_bfr7 = res7[2];
                        var aprv_bfr7 = res7[3];
                    } else {
                        var leave_tyep7 = '';
                        var num_days7 = '';
                        var req_bfr7 = '';
                        var aprv_bfr7 = '';
                    }

                    ele2.innerHTML = ele2.innerHTML + 'Office Name: &nbsp;<span class=" badge badge-primary mybag">' + firm_name + '</span>';
                    // POPULATE SELECT ELEMENT WITH JSON.
                    var designation_name_edit = 'designation_name_edit';
                    var designation_role_edit = 'designation_role_edit';
                    if (leave_permit == 2)
                    {
                        ele1.innerHTML = ele1.innerHTML +
                                '<div class="form-group"><br>' +
                                '<label>Designation</label>' +
                                '<div class="input-group">' +
                                '<span class="input-group-addon">' +
                                '<i class="fa fa-envelope"></i>' +
                                '</span>' +
                                '<input type="text" id="designation_name_edit" name="designation_name_edit" onkeyup="remove_error(\'' + designation_name_edit + '\')" value="' + data[0]['designation_name'] + '"  class="form-control" placeholder="Designation"> </div>' +
                                '<span class="required" id="designation_name_edit_error" style="color:red"></span>' +
                                '</div>' +
                                '<div class="form-group">' +
                                '<label> Role</label>' +
                                '<div class="input-group">' +
                                '<span class="input-group-addon">' +
                                '<i class="fa fa-globe"></i>' +
                                '</span>' +
                                '<textarea class="form-control" name="designation_role_edit" onkeypress="remove_error(\'' + designation_role_edit + '\')" id="designation_role_edit" rows="3">' + data[0]['designation_roles'] + '</textarea>   </div>' +
                                '<span class="required" id="designation_role_edit_error" style="color:red"></span>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group">' +
                                ' <div class="row">' +
                                '<div class="col-md-12">' +
                                '<label><span style="font-weight:bold;">Leaves configuration</span></label> ' +
                                ' </div> ' +
                                ' </div> <br>' +
                                ' <div class="row">' +
                                '<div class="col-md-1">' +
                                '<label><span style="font-weight:bold;">Types</span></label> ' +
                                ' </div> ' +
                                '<div class="col-md-3">' +
                                '<label><span style="font-weight:bold;">Leave Name</span></label> ' +
                                ' </div> ' +
                                '<div class="col-md-2">' +
                                '<label><span style="font-weight:bold;">Number of Days</span></label> ' +
                                ' </div> ' +
                                '<div class="col-md-3">' +
                                '<label><span style="font-weight:bold;">Leave Request Before(days)</span></label> ' +
                                ' </div> ' +
                                '<div class="col-md-3">' +
                                '<label><span style="font-weight:bold;">Leave Approve Before(days)</span></label> ' +
                                ' </div> ' +
                                ' </div> <br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 1</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type1_edit" name="leave_type1_edit" value="' + leave_tyep1 + '" onkeyup="remove_error("leave_type1_edit")" class="form-control" placeholder="First Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" min="1" id="numofdays1_edit" name="numofdays1_edit" value="' + num_days1 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="0" id="req_bfr1_edit" name="req_bfr1_edit" value="' + req_bfr1 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="1" id="aprv_bfr1_edit" name="aprv_bfr1_edit" value="' + aprv_bfr1 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 2</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type2_edit" name="leave_type2_edit"value="' + leave_tyep2 + '"  onkeyup="remove_error("leave_type2_edit")" class="form-control" placeholder="Second Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" min="1" id="numofdays2_edit" name="numofdays2_edit" value="' + num_days2 + '" onkeyup="remove_error("numofdays2_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="0" id="req_bfr2_edit" name="req_bfr2_edit" value="' + req_bfr2 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="1" id="aprv_bfr2_edit" name="aprv_bfr2_edit" value="' + aprv_bfr2 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 3</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type3_edit" name="leave_type3_edit" value="' + leave_tyep3 + '"  onkeyup="remove_error("leave_type3_edit")" class="form-control" placeholder="Third Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" min="1" id="numofdays3_edit" name="numofdays3_edit" value="' + num_days3 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="0" id="req_bfr3_edit" name="req_bfr3_edit" value="' + req_bfr3 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="1" id="aprv_bfr3_edit" name="aprv_bfr3_edit" value="' + aprv_bfr3 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                ' <div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 4</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type4_edit" name="leave_type4_edit" value="' + leave_tyep4 + '"  onkeyup="remove_error()" class="form-control" placeholder="Fourth Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" min="1" id="numofdays4_edit" name="numofdays4_edit" value="' + num_days4 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="0" id="req_bfr4_edit" name="req_bfr4_edit" value="' + req_bfr4 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="1" id="aprv_bfr4_edit" name="aprv_bfr4_edit" value="' + aprv_bfr4 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 5</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type5_edit" name="leave_type5_edit" value="' + leave_tyep5 + '"  onkeyup="remove_error()" class="form-control" placeholder="Fifth Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" min="1" id="numofdays5_edit" name="numofdays5_edit" value="' + num_days5 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="0" id="req_bfr5_edit" name="req_bfr5_edit" value="' + req_bfr5 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="1" id="aprv_bfr5_edit" name="aprv_bfr5_edit" value="' + aprv_bfr5 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 6</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type6_edit" name="leave_type6_edit" value="' + leave_tyep6 + '" onkeyup="remove_error()" class="form-control" placeholder="Sixth Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" min="1" id="numofdays6_edit" name="numofdays6_edit" value="' + num_days6 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="0" id="req_bfr6_edit" name="req_bfr6_edit" value="' + req_bfr6 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="1" id="aprv_bfr6_edit" name="aprv_bfr6_edit" value="' + aprv_bfr6 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '<div class="row">' +
                                '<DIV class="col-md-1"><span style="font-weigth:bold;">Type 7</span></DIV>' +
                                '<DIV class="col-md-3"><input type="text" id="leave_type7_edit" name="leave_type7_edit" value="' + leave_tyep7 + '"  onkeyup="remove_error()" class="form-control" placeholder="Seventh Leave Type"></DIV>' +
                                '<DIV class="col-md-2"><input type="number" min="1" id="numofdays7_edit" name="numofdays7_edit" value="' + num_days7 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="0" id="req_bfr7_edit" name="req_bfr7_edit" value="' + req_bfr7 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '<DIV class="col-md-3"><input type="number" min="1" id="aprv_bfr7_edit" name="aprv_bfr7_edit" value="' + aprv_bfr7 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV><br>' +
                                '</div>';
                    } else {
                        ele1.innerHTML = ele1.innerHTML +
                                '<div class="form-group">' +
                                '<label>Designation</label>' +
                                '<div class="input-group">' +
                                '<span class="input-group-addon">' +
                                '<i class="fa fa-envelope"></i>' +
                                '</span>' +
                                '<input type="text" id="designation_name_edit" name="designation_name_edit" value="' + data[0]['designation_name'] + '" onkeyup="remove_error()" class="form-control" placeholder="Designation"> </div>' +
                                '<span class="required" id="designation_name_edit_error" style="color:red"></span>' +
                                '</div>' +
                                '<div class="form-group">' +
                                '<label>Role</label>' +
                                '<div class="input-group">' +
                                '<span class="input-group-addon">' +
                                '<i class="fa fa-globe"></i>' +
                                '</span>' +
                                '<textarea class="form-control" name="designation_role_edit" onkeyup="remove_error()" id="designation_role_edit" rows="3">' + data[0]['designation_roles'] + '</textarea>   </div>' +
                                '<span class="required" id="designation_role_edit_error" style="color:red"></span>' +
                                '</div>' +
                                '<div class="form-group">' +
                                ' <div class="col-md-12">' +
                                //                                '<label>Leaves Allow</label> ' +
                                ' </div>' +
                                ' <div class="row">' +
                                //                                ' <DIV class="col-md-2"><span style="font-weigth:bold;">Type 1:</span></DIV>' +
                                '<DIV class="col-md-5"><input type="hidden" id="leave_type1_edit" name="leave_type1_edit" value="' + leave_tyep1 + '" onkeyup="remove_error("leave_type1_edit")" class="form-control" placeholder="First Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays1_edit" name="numofdays1_edit" value="' + num_days1 + '"  onkeyup="remove_error("numofdays1_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV>' +
                                '<div class="row">' +
                                //                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 2:</span></DIV>' +
                                '<DIV class="col-md-5"><input type="hidden" id="leave_type2_edit" name="leave_type2_edit"value="' + leave_tyep2 + '"  onkeyup="remove_error("leave_type2_edit")" class="form-control" placeholder="Second Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays2_edit" name="numofdays2_edit" value="' + num_days2 + '" onkeyup="remove_error("numofdays2_edit")" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV>' +
                                '<div class="row">' +
                                //                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 3:</span></DIV>' +
                                ' <DIV class="col-md-5"><input type="hidden" id="leave_type3_edit" name="leave_type3_edit" value="' + leave_tyep3 + '"  onkeyup="remove_error("leave_type3_edit")" class="form-control" placeholder="Third Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays3_edit" name="numofdays3_edit" value="' + num_days3 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV>' +
                                ' <div class="row">' +
                                //                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 4:</span></DIV>' +
                                '<DIV class="col-md-5"><input type="hidden" id="leave_type4_edit" name="leave_type4_edit" value="' + leave_tyep4 + '"  onkeyup="remove_error()" class="form-control" placeholder="Fourth Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays4_edit" name="numofdays4_edit" value="' + num_days4 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV>' +
                                '<div class="row">' +
                                //                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 5:</span></DIV>' +
                                '<DIV class="col-md-5"><input type="hidden" id="leave_type5_edit" name="leave_type5_edit" value="' + leave_tyep5 + '"  onkeyup="remove_error()" class="form-control" placeholder="Fifth Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays5_edit" name="numofdays5_edit" value="' + num_days5 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV>' +
                                '<div class="row">' +
                                //                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 6:</span></DIV>' +
                                '<DIV class="col-md-5"><input type="hidden" id="leave_type6_edit" name="leave_type6_edit" value="' + leave_tyep6 + '" onkeyup="remove_error()" class="form-control" placeholder="Sixth Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays6_edit" name="numofdays6_edit" value="' + num_days6 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV>' +
                                '<div class="row">' +
                                //                                '<DIV class="col-md-2"><span style="font-weigth:bold;">Type 7:</span></DIV>' +
                                '<DIV class="col-md-5"><input type="hidden" id="leave_type7_edit" name="leave_type7_edit" value="' + leave_tyep7 + '"  onkeyup="remove_error()" class="form-control" placeholder="Seventh Leave Type"></DIV>' +
                                '<DIV class="col-md-3"><input type="hidden" min="1" id="numofdays7_edit" name="numofdays7_edit" value="' + num_days7 + '" onkeyup="remove_error()" class="form-control" placeholder="No Of Days"></DIV>' +
                                '</DIV>' +
                                '</div>';
                    }

                } else {

                }

            }
        }
        );
    });

    //update designation
    $("#edit_desig_btn").click(function () {
        var $this = $(this);
        $this.button('loading');
        setTimeout(function () {
            $this.button('reset');
        }, 2000);
        document.getElementById('loaders').style.display = "block";
        $.ajax({
            type: "POST",
            url: "<?= base_url("Designation/edit_designation") ?>",
            dataType: "json",
            data: $("#edit_designation_form").serialize(),
            success: function (result) {
                if (result.status === true) {
                    document.getElementById('loaders').style.display = "none";
                    //return;
                    location.reload();
                } else {
                    document.getElementById('loaders').style.display = "none";
                    // $('#message').html(result.error);
                    $('#' + result.id + '_error').html(result.error);
                }
            },
            error: function (result) {
                //console.log(result);
                if (result.status === 500) {
                    document.getElementById('loaders').style.display = "none";

                    alert('Internal error: ' + result.responseText);
                } else {
                    document.getElementById('loaders').style.display = "none";

                    alert('Unexpected error.');
                }
            }
        });
    });

    //delete designation
    function delete_designation(id)
    {
        var designation_id = id;
        var result = confirm("Are you sure, you want to delete this designation?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Designation/del_designation") ?>",
                dataType: "json",
                data: {designation_id: designation_id},
                success: function (result) {
                    if (result.message === "success") {
                        alert(result.body);
                        location.reload();
                    } else {
                        alert('Something went wrong.');
                    }
                }
            });
        } else {
        }
    }

    function validate1(evt, obj) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        var value = obj.value;
        var dotcontains = value.indexOf(".") != -1;
        if (dotcontains)
            if (charCode == 46)
                return false;
        if (charCode == 46)
            return true;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    function validate(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength);

        var value_of_year_leave = $('#yearly_leave').val();
        var value_of_month = value_of_year_leave / 12;
        var value_of_month_leave = value_of_month.toFixed(2);
        document.getElementById("monthly_leaves").value = value_of_month_leave;
        console.log(value_of_month_leave);
    }


    function all_validate(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength);


        console.log("i am click");
    }

    $("#monthly_leaves").keyup(function () {
        // var number = ($(this).val().split('.'));
        // if (number[1].length > 2)
        // {
        //     var salary = parseFloat($("#monthly_leaves").val());
        //     $("#monthly_leaves").val(salary.toFixed(2));
        // }

        //     alert("akshay Here");
                                                      
    });
    function start_loading() {
        document.getElementById('loading_div').style.display = "block";
    }
    function stop_loading() {
        document.getElementById('loading_div').style.display = "none";
    }

</script>