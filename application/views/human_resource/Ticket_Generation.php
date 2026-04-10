<?php
//$this->load->view('hq_admin/navigation');
//$this->load->view('client_admin/navigation');
//$this->load->view('employee/navigation');
//$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}

$this->load->view('human_resource/navigation');

$user_id = $this->session->userdata('login_session');
?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<style>
    span.error {
        color:red;
        size:1px
    }
    .form-control.error{

    }
    .row {
        margin-left: -4px;
        margin-right: -8px;
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
<div class="page-content-wrapper">
    <div class="page-content" style="min-height: 660px;">

        <!--<div class="portlet light bordered">-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="row wrapper-shadow">
                    <div class="portlet light portlet-fit portlet-form ">

                        <div class="modal fade" id="Ticket_Modal" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h4 class="modal-title">Ticket Generation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="ticket_generation">
                                            <div class="">
                                                <label class=""> Ticket type</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i></span>
                                                    <select name="type_name" id="type_name"  class="form-control m-select2 " required="">

                                                    </select>
                                                </div>

                                            </div>
                                            <span  id="type_name_error"></span>
                                            <div class="form-group">
                                                <div class="">

                                                    <label class="">Work Description</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                        </span>
                                                        <textarea  name="Work" row="3" id="Work" class="form-control" placeholder="Enter work"></textarea>
                                                        <br>
                                                    </div>
                                                    <span id="Work_error"></span>
                                                </div>
                                                <label class="">Employee Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i></span>
                                                    <select name="employee_name" id="employee_name"  class="form-control m-select2 m-select2-general"  required="">

                                                    </select>
                                                </div>
                                                <span  class="required" id="employee_name_erroe"></span>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="user_details" role="grid" aria-describedby="sample_2" style="text-align: center;">
                                                        <thead>
                                                            <tr role="row">
                                                            </tr>
                                                        </thead>
                                                        <tbody id="user_info"></tbody>
                                                    </table>

                                                </div>

                                                <div class="modal-footer">

                                                    <div class="row">
                                                        <div class="col-md-offset-3 col-md-9">
                                                            <button type="button" class="btn red" data-dismiss="modal" style="float:right; margin: 2px;"> <i class="fa fa-close"></i></button>
                                                            <button type="submit" id="btn_ticket_generation" name="btn_ticket_generation" class="btn blue"  data-style="expand-left"  style="float:right;  margin: 2px;">
                                                                <i class="fa fa-save"></i></button>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="Ticketassignedmodel" role="dialog">
                            <div class="modal-dialog ">
                                <div class="portlet light portlet-fit portlet-form bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <div class="col-md-12" id="panel_3">
                                                <i class=" icon-settings font-red"></i>
                                                <span class="caption-subject font-green sbold uppercase">Select Options For Ticket</span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">

                                        <form id="assignment_rejection_by_senor"  method="post" style="display:none;">
                                            <div class="form-body">
                                                <div class="form-group form-md-line-input">
                                                    <label class="col-md-3 control-label text-center">Rejection Description</label>
                                                    <div class="col-md-9">
                                                        <input type="hidden" name="type_of_response"  id="type_of_response"/>
                                                        <input type="hidden" name="rejection_ass_id"  id="rejection_ass_id"/>
                                                        <textarea  name="txt_rejection_description" id="rejection_description" class="form-control" required></textarea>
                                                        <div class="form-control-focus"></div>
                                                        <span class="help-block help-block-error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-6 col-md-6">
                                                        <button  type="submit" class="btn btn-primary" > Save changes </button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="responseViewModal" role="dialog">
                            <div class="modal-dialog ">
                                <div class="portlet light portlet-fit bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class=" icon-settings font-red"></i>
                                            <span class="caption-subject font-green sbold uppercase">Work Details</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row" id="response_list">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="ticketWorkDetailsModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <i class=" icon-settings font-red"></i>
                                        <span class="caption-subject font-green sbold uppercase">Add Work Done </span>
                                    </div>
                                    <div class="modal-body">
                                        <form id="assignment_response_form"  method="post">
                                            <label class="col-md-3 control-label text-center">Enter Description</label>
                                            <div class="col-md-9">
                                                <input type="hidden" name="type_of_response"  value="1" id="response_type"/>
                                                <input type="hidden" name="ticekt_id"  id="work_ass_id"/>
                                                <input type="hidden" name="work_reject_id"  id="work_reject_id"/>
                                                <textarea  name="txt_rejection_description" id="rejection_description" class="form-control" required></textarea>
                                                <div class="form-control-focus"></div>
                                                <span class="help-block help-block-error"></span>

                                            </div>


                                            <div class="row">
                                                <div class="col-md-offset-6 col-md-6">
                                                    <button  type="submit" class="btn btn-primary">Save changes</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>


                                        </form>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <?php
                        if (!empty($emp_id) || $u_type == 2) {
                            ?>


                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="icon-settings font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">View Ticket Generation</span>
                                </div>
                                <div class="actions">
                                    <div class="btn-group right">
                                        <button  id="sample_1_new"  data-toggle="modal" data-ticket_type_id="0" data-target="#Ticket_Modal"
                                                 class="btn blue-hoki btn-outline sbold uppercase popovers"
                                                 data-container="body" data-trigger="hover" data-placement="left"
                                                 data-content="Generate ticket"
                                                 >
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="btn-group">
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="portlet-title">
                                    <div class="col-md-12">

                                                           <!--<table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="ticket_generated_table" role="grid" aria-describedby="sample_1_info" style="text-align: center;">-->
                                                           <!--<table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer dtr-inline collapsed" id="ticket_generated_table" role="grid" aria-describedby="ticket_generated_table_info" style="text-align: center;">-->
                                        <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer dtr-inline collapsed" role="grid" style="width:100%;" id="sample_1">
                                            <thead>
                                                <tr>
                                                    <!--<th scope="col"> Sr No</th>-->
                                                    <th >Ticket type</th>
                                                    <th >Work Description</th>
                                                    <th >Assigned to</th>
                                                    <th >Created on</th>
                                                    <th >Responseded on</th>
                                                    <th >Action</th>

                                                                                                                                                                                                    <!--<th scope="col">Edit/Delete</th>-->
                                                </tr>
                                            </thead>
                                            <tbody id="ticket_generated"></tbody>
                                        </table>


                                    </div>

                                </div>
                            </div>
                            <?php
                        } else {

                        }
                        ?>
                        <?php
                        if ($userid != '') {
                            ?>


                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="icon-settings font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">Your Assignments</span>

                                </div>
                            </div>
                            <div class="row">
                                <div class="portlet-title">
                                    <div class="col-md-12">

                                       <!-- <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer dtr-inline collapsed" id="ticket_assigned_table" role="grid" style="text-align: center;width: 100%!important">-->
                                        <table class="table table-striped table-bordered table-hover dt-responsive dataTable no-footer dtr-inline collapsed" id="ticket_assigned_table" role="grid" style="text-align: center;width: 100%">
                                            <thead>
                                                <tr>
                                                    <!--<th scope="col"> Sr No</th>-->
                                                    <th  style="text-align: center;">Assigned By</th>
                                                    <th  style="text-align: center;">Ticket type</th>
                                                    <th style="text-align: center;">Work Description</th>
                                                    <th  style="text-align: center;">Created on</th>
                                                    <th  style="text-align: center;">view</th>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <!--<th scope="col">Edit/Delete</th>-->
                                                </tr>
                                            </thead>
                                            <tbody id="ticket_assigned"></tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>


                            <?php
                        }
                        ?>
                        <?php if (empty($emp_id) && $userid == '') { ?>
                            <div class="well-lg bg-grey-steel-opacity " id="first_well" style="height: 380px;margin-bottom: 16px" >
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <img src="<?= base_url() ?>/assets/global/img/no-data-found1 (1).png"  />
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="font-grey-gallery well-sm">
                                            <h2 style="margin-top:4em;" id="banner_heading">No Configured ticket yet,so you can't generate ticket </h2>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>


                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('human_resource/footer'); ?>
    </div>

</div>

<link href="<?php echo base_url() . "assets/"; ?>/global/plugins/jquery-notific8/jquery.notific8.min.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-notific8/jquery.notific8.min.js" type="text/javascript"></script>
<script>

    $(document).ready(function () {

        $('#Ticket_Modal').on('hide.bs.modal', function (e) {
            $("#ticket_generation").trigger("reset");
            get_ticket_name();
            get_employe_name();
            $("#type_name").empty();
            $("#employee_name").empty();
            $("#user_info").empty();

        });

        $.ajax({
            url: "<?= base_url("Ticket_Generation/markasread") ?>",
            method: "post"
        }).done(function (success) {
            unread_ticket_assigenement();
        });

        $.ajax({
            url: "<?= base_url("Ticket_Generation/markAsReadaccepted") ?>",
            method: "post"
        }).done(function (success) {
            unread_ticket_assigenement();
        });
        $.ajax({
            url: "<?= base_url("Ticket_Generation/markAsReadrejected") ?>",
            method: "post"
        }).done(function (success) {
            unread_ticket_assigenement();
        });

        $.ajax({
            url: "<?= base_url("Ticket_Generation/markAsReadworkdone") ?>",
            method: "post"
        }).done(function (success) {
            unread_ticket_assigenement();
        });
    }
    );
    function get_ticket_name() {


        load();
        load1();
        $.ajax({
            url: "<?= base_url("/Ticket_Generation/ticket_generate") ?>",
            dataType: "json",
            success: function (result) {
                if (result['message'] === 'success') {
                    var data = result.type_data;
                    var ele3 = document.getElementById('type_name');
                    ele3.innerHTML = '<option disabled selected> Select Type </option>';
                    for (i = 0; i < data.length; i++)
                    {
                        // POPULATE SELECT ELEMENT WITH JSON.
                        ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['Type_id'] + '">' + data[i]['type_name'] + '</option>';
                    }
                } else {
                    var f = $("#type_name option");
                    console.log(f);
                    $("#type_name option").remove();
                    var ele = document.getElementById('type_name');


                    ele.innerHTML = '';
                    ele.innerHTML = '<option> No ticket type </option>'
                }

            }
        });
    }
    $(document).ready(function () {


        load();
        load1();
        $("#ticket_generation").validate({
            rules: {
                Work: 'required',
                type_name: 'required',
                employe_name: 'required'
            },

            messages: {
                Work: "Please Enter work Description",
                type_name: "Please Select type",
                employe_name: "Please Select Employee",
            },

            errorElement: 'span',
            errorPlacement: function (error, element) {
                //Custom position: first name
                //                    var placement = $(element).data('error');
                if (element.attr("id") == "Work") {

                    $("#Work_error").append(error);

                }
                if (element.attr("id") == "type_name") {
                    $("#type_name_error").append(error);
                    $("#type_name").removeClass("error");

                }
                if (element.attr("id") == "employee_name") {
                    $("#employee_name_erroe").append(error);
                }


            },
            submitHandler: function (form) {

                type_id = $("#type_name").val();
                var type_name = $("#type_name :selected").text();
                var Work = document.getElementById('Work').value;
                var updated_id = document.getElementById('update_value').value;
                employe_id = $("#employee_name").val();
                var employe_name = $("#employee_name :selected").text();

                if (type_name == '') {
                    document.getElementById("type_name").setAttribute("isvalid", "true");
                    $("#type_name_error").append('Please Select type Name');
                } else
                if (employe_name == '') {
                    document.getElementById("type_name").setAttribute("valid", "true");
                    $("#type_name_error").empty();
                    document.getElementById("employee_name").setAttribute("isvalid", "true");
                    $("#employee_name_erroe").append('Please Select Employee Name');
                } else {
                    document.getElementById("employee_name").setAttribute("valid", "true");
                    $("#employee_name_erroe").empty();
                    $.ajax({
                        url: "<?= base_url("/Ticket_Generation/insert_ticket") ?>",
                        type: "POST",
                        data: {updated_id: updated_id, Work: Work, employe_id: employe_id, employe_name: employe_name, type_id: type_id, type_name: type_name},
                        success: function (result) {

                            $('#ticket_generation').trigger('reset');
                            $('#Ticket_Modal').modal('toggle');
                            toastr.success(result);

                            //location.reload();
                        }

                    });
                }
            }
        });
    });
    function get_employe_name() {
        $("#type_name").on("input", function () {

            $("#type_name").addClass("valid");
        });
        $("#type_name").change(function () {

            $("#type_name").addClass("valid");

            var type_id = $('#type_name').val();
            if (type_id != null) {
                type_id = $('#type_name').val();
            } else {
                type_id = "0";
            }

            $.ajax({

                url: "<?= base_url("/Ticket_Generation/get_employee_data") ?>",
                type: "POST",
                data: {type_id: type_id},
                dataType: "json",
                success: function (result) {

                    if (result['message'] === 'success') {
                        var data = result.emp_data;
                        //                                                                    console.log(data);
                        var ele5 = document.getElementById('employee_name');
                        ele5.innerHTML = '<option disabled selected> Select Employee</option>';
                        for (i = 0; i < data.length; i++)
                        {
                            // POPULATE SELECT ELEMENT WITH JSON.
                            ele5.innerHTML = ele5.innerHTML + '<option value="' + data[i]['emp_id'] + '">' + data[i]['employee_name'] + '' + ' ( ' + data[i]['Branch_name'] + ')' + '</option>';
                        }

                    } else {
                        var f = $("#employee_name option");
                        $("#employee_name option").remove();
                        var ele = document.getElementById('employee_name');
                        ele.innerHTML = '';
                        ele.innerHTML = '<option> No Employee </option>'
                    }

                }
            });

        });
    }

    $(document).ready(function () {

        get_ticket_name();
        get_employe_name();
    });
    $("#employee_name").change(function () {



        var employee_id = $('#employee_name').val();
        if (employee_id != null) {
            employee_id = $('#employee_name').val();
        } else {
            employee_id = "0";
        }



        $.ajax({
            url: "<?= base_url("/Ticket_Generation/user_table") ?>",
            type: "POST",
            data: {employee_id: employee_id},
            dataType: "json",
            success: function (result) {
                //                                                            console.log(result);
                //                                                            var json = JSON.parse(result);

                if (result["user_table"] !== "") {
                    $('#user_info').empty();
                    $('#user_info').append(result['user_table']);
                    $('#user_details').DataTable();
                }
                if (result["user_table"] === "") {
                    //                                                                alert(json["error"]);
                }

            },
            error: function (result) {

            }

            // location.reload();
        });
    });
    function load() {


        $.ajax({
            url: "<?= base_url("/Ticket_Generation/show_data") ?>",
            type: "POST",
            success: function (result) {

                if (result)
                {

                    var json = JSON.parse(result)

                    if (json["ticket_data"] !== "") {
                        $('#ticket_generated').empty();
                        $('#ticket_generated').append(json['ticket_data']);
                        $('#ticket_generated_table').DataTable();
                        readMore();
                    }
                    if (json["ticket_data"] === "") {
                        toastr.error(json["error"]);

                    }
                }

            },
            error: function (result) {
                //                                                            console.log(result);
            }

            // location.reload();
        });
    }
    function load1() {


        $.ajax({
            url: "<?= base_url("/Ticket_Generation/show_data1") ?>",
            type: "POST",
            success: function (result) {
                if (result)
                {
                    var json = JSON.parse(result)

                    if (json["ticket_data"] !== "") {
                        $('#ticket_assigned').empty();
                        $('#ticket_assigned').append(json['ticket_data']);
                        $('#ticket_assigned_table').DataTable();
                        readMore();
                    }
                    if (json["ticket_data"] === "") {
                        //                                                                alert(json["error"]);
                    }
                }

            },
            error: function (result) {

            }

            // location.reload();
        });
    }
    function delete_ticket(delete_id) {
        var result = confirm("Are you sure do you want to delete this ticket?");
        if (result) {

            $.ajax({
                url: "<?= base_url("/Ticket_Generation/delete_data") ?>",
                type: "POST",
                data: {delete_id: delete_id},
                success: function (result) {
                    toastr.success(result);

                    location.reload();
                },
                error: function (result) {
                    console.log(result);
                }


            });
        } else {
            //        location.reload();
        }
    }

    $('#Ticket_Modal').on('show.bs.modal', function (e) {
        var update_value = $(e.relatedTarget).data('ticket_type_id');
        $('<input>').attr({
            type: 'hidden',
            id: 'update_value',
            name: 'update_value',
            value: update_value
        }).appendTo('#ticket_generation');
    });
    $('#Ticket_Modal').on('hide.bs.modal', function (e) {
        $("#update_value").remove();
    });
    $('#ticketWorkDetailsModal').on('show.bs.modal', function (e) {
        var update_value = $(e.relatedTarget).data('ticket_type_id');
        var accepted_value = $(e.relatedTarget).data('accepted');
        var call_by = $(e.relatedTarget).data('call_by');
        var work_reject_id = $(e.relatedTarget).data('work_reject_id');
        $("#work_reject_id").val(work_reject_id);
        $("#work_ass_id").val(update_value);
        if (accepted_value === 1) {
            $("#juniorAccept").hide();
            $("#juniorWorkDone").show();
        } else {
            $("#juniorAccept").show();
            $("#juniorWorkDone").hide();
        }
        if (call_by !== undefined)
        {
            $("#juniorAccept").hide();
            $("#juniorWorkDone").hide();
            $("#assignment_response_form").show();
            document.getElementById("response_type").value = '4';
        }

    });
    $("#juniorReject").click(function () {

        document.getElementById("response_type").value = '2';
        $("#assignment_response_form").show();
    });
    $("#juniorWorkDone").click(function () {
        document.getElementById("response_type").value = '1';
        $("#assignment_response_form").show();
    });
    $("#assignment_response_form").on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "<?= base_url("Ticket_Generation/rejected_work") ?>",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
        }).done(function (success) {
            load();
            load1();
            $("#assignment_response_form").trigger("reset");
            $("#ticketWorkDetailsModal").modal("toggle");
            //                                                        $('#ticketWorkDetailsModal').hide();
            //                                                        $('.modal-backdrop').hide();
            //                                                        $("#responseViewModal").modal("hide");

            $('#responseViewModal').hide();
            $('.modal-backdrop').hide();
            success = JSON.parse(success);
            var message = success.result;
            toastr.success(message);
        }).fail(function (error) {
            toastr.error(error);
        });
    });
    $('#Ticketassignedmodel').on('show.bs.modal', function (e) {
        var update_value = $(e.relatedTarget).data('ticket_type_id');
        $('<input>').attr({
            type: 'hidden',
            id: 'update_value',
            name: 'update_value',
            value: update_value
        }).appendTo('#assignment_rejection_by_senor');
    });
    //                                                $('#Ticketassignedmodel').on('hide.bs.modal', function (e) {
    //                                                    $("#update_value").remove();
    //                                                });
    $('#assignment_rejection_by_senor').on('submit', function (event) {
        event.preventDefault();
        var rejected = document.getElementById("type_of_response").value;
        var rejection_description = document.getElementById("rejection_description").value;
        var updated_id = document.getElementById('update_value').value;
        $.ajax({
            url: "<?= base_url("/Ticket_Generation/Accept_by_employee") ?>",
            type: "POST",
            data: {updated_id: updated_id, rejected: rejected, rejection_description: rejection_description},
            success: function (success) {

                success = JSON.parse(success);
                var message = success.result;
                toastr.success(message);
                $("#assignment_rejection_by_senor").trigger("reset");
                $("#Ticketassignedmodel").modal("toggle");
            },
            error: function (error) {
                console.log(error);
                error = JSON.parse(error);
                var message = error.result;
                //                                                            console.log(message);
                $("#assignment_rejection_by_senor").trigger("reset");
                $("#Ticketassignedmodel").modal("toggle");
                  toastr.error(message);
            }
        });
    }
    );
    function accept_workdone_by_senor(gen_id, id, work_id, workid) {


        var url = "<?= base_url("Ticket_Generation/accept_work_status") ?>";
        $.ajax({
            url: url,
            data: {gen_id: gen_id, response_status: 2, response_code: id, work_id: work_id, workid: workid},
            method: "post"
        }).done(function (success) {
            load();
            load1();
            success = JSON.parse(success);
            var message = success.result;
               toastr.success(message);
            $("#responseViewModal").modal("toggle");
        }).fail(function (error) {
                toastr.error(error);
        });
    }




</script>
<script>
    function accept_assignment_by_senor(type, work_id) {


        var updated_id = document.getElementById('update_value').value;
        if (type === 1) {

            $.ajax({
                url: "<?= base_url("/Ticket_Generation/Accept_by_employee") ?>",
                data: {work_id: work_id, updated_id: updated_id, type: type},
                method: "post"
            }).done(function (success) {
                load();
                load1();
                success = JSON.parse(success);
                var message = success.result;
                    toastr.success(message);
                $("#assignment_rejection_by_senor").trigger("reset");
                $("#Ticketassignedmodel").modal("toggle");
                $("#assignment_rejection_by_senor").empty();
            })
                    .fail(function (error) {
                               toastr.error(error);
                    });
        } else if (type === 2) {

            $("#type_of_response").val(type);
            $("#assignment_rejection_by_senor").show();
        }
    }
    $('#responseViewModal').on('show.bs.modal', function (e) {
        var update_value = $(e.relatedTarget).data('ticket_type_id');
        var table_id = $(e.relatedTarget).data('table_id');
        //                        $("#rejection_ass_id").val(update_value);
        $.ajax({
            url: "<?= base_url("/Ticket_Generation/response_description") ?>",
            type: "POST",
            data: {table_id: table_id, update_value: update_value},
            success: function (success) {
                success = JSON.parse(success);
                var status = success.status;
                if (status === 200) {
                    $("#response_list").empty();
                    $("#response_list").append(success.result);
                }

            },
            error: function (error) {
                console.log(error);
            }
        });
    }
    );

    function firm_update(gen_id)
    {
        $.ajax({

            url: "<?= base_url("/Ticket_Generation/get_type_name_data") ?>",
            type: "POST",
            data: {gen_id: gen_id},
            dataType: "json",
            success: function (result) {
                var data = result.user_data;
                console.log(data);
                document.getElementById('Work').value = data[0][0];
                var emppdata = result.emp_data;
                var data2 = result.comman_array;
                var data3 = result.selected_array;
                var data4 = result.comman_id_array;
                var data5 = result.selected_id_array;
                var ele4 = document.getElementById('type_name');
                var ele5 = document.getElementById('employee_name');
                ele4.innerHTML = '';
                ele5.innerHTML = '';

                for (i = 0; i < data2.length; i++)
                {
                    // POPULATE SELECT ELEMENT WITH JSON.

                    ele4.innerHTML = ele4.innerHTML + '<option  value="' + data4[i] + '">' + data2[i] + '</option>';
                }

                for (j = 0; j < data3.length; j++)
                {
                    // POPULATE SELECT ELEMENT WITH JSON.

                    ele4.innerHTML = ele4.innerHTML + '<option selected  value="' + data5[j] + '">' + data3[j] + '</option>';
                }

                for (i = 0; i < emppdata.length; i++)
                {
                    // POPULATE SELECT ELEMENT WITH JSON.
                    ele5.innerHTML = ele5.innerHTML + '<option value="' + emppdata[i]['emp_id'] + '">' + emppdata[i]['employee_name'] + '' + ' ( ' + emppdata[i]['Branch_name'] + ')' + '</option>';
                }

                for (j = 0; j < data.length; j++)
                {
                    // POPULATE SELECT ELEMENT WITH JSON.

                    ele5.innerHTML = ele5.innerHTML + '<option selected value="' + data[j][1] + '">' + data[j][2] + ' ( ' + data[j][3] + ')' + '</option>';
                }

            }
        });
        return;
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
</script>
