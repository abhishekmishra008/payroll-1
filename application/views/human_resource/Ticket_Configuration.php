<?php
//$this->load->view('hq_admin/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
 $this->load->view('human_resource/navigation');
$user_id = $this->session->userdata('login_session');
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
        color: red;
    }
    /* Tooltip container */
    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
    }

    /* Tooltip text */
    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: #555;
        color: #fff;
        text-align: center;
        padding: 5px 0;
        border-radius: 6px;

        /* Position the tooltip text */
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;

        /* Fade in tooltip */
        opacity: 0;
        transition: opacity 0.3s;
    }

    /* Tooltip arrow */
    .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    /* Show the tooltip text when you mouse over the tooltip container */
    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
    .row {
        margin-left: -6px;
        margin-right: -8px;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content" style="min-height: 660px;">

        <div class="row">
            <div class="col-md-12">
                <div class="row wrapper-shadow">
                    <div class="portlet light portlet-fit portlet-form ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class=" icon-settings font-red"></i>
                                <span class="caption-subject bold uppercase">View Ticket Configuration</span>
                            </div>
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->

                            <div class="actions">


                                <div class="btn-group right">
                                    <button class="btn blue-hoki btn-outline sbold uppercase popovers"
                                            id="sample_1_new" data-container="body" data-trigger="hover" data-placement="left"
                                            data-content="Configure ticket"
                                            class="btn btn-sm blue"
                                            data-toggle="modal" data-ticket_type_id='0' data-target="#Ticket_Modal">

                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>

                                <div class="btn-group">
                                </div>
                            </div>
                        </div>

                        <div class="">

                            <!--modal content-->
                            <div class="modal fade" id="Ticket_Modal" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h4 class="modal-title">Ticket Configuration</h4>
                                        </div>


                                        <div class="modal-body">

                                            <div class="form-group">

                                                <div class="modal-body">
                                                    <form  id="ticket_typ_form" >
                                                        <div class="">

                                                            <label class="">Ticket Name</label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-user"></i></span>
                                                                </span>
                                                                <input type="text" name="type" id="type"  class="form-control" placeholder="Enter Type Name" >
                                                            </div>
                                                            <span id="t1"></span>
                                                        </div>
                                                        <div class="">

                                                            <label class="">Description</label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-user"></i></span>
                                                                </span>
                                                                <textarea rows="4" class="form-control" name="Desc" id="Desc" ></textarea>

                                                            </div>
                                                            <span id="Desc_error"></span>
                                                        </div>


                                                        <div class="form-group">

                                                            <div class="">

                                                                <div class="col-12">
                                                                    <label class="">Select Branch</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <i class="fa fa-user"></i></span>
                                                                        <select name="Branch_name[]" multiple id="Branch_name"   class="form-control select2-multiple">
                                                                            <option value="">Select Branch</option>
                                                                        </select>
                                                                    </div>
                                                                    <span class="required" id="Branch_name_error"></span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">

                                                                <div class="">

                                                                    <div class="col-12">
                                                                        <label class="">Select Employes</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-user"></i></span>
                                                                            <select name="Select_Employes[]" multiple id="Select_Employes"  class="form-control select2-multiple">
                                                                                <option value="">Select Employes</option>
                                                                            </select>
                                                                        </div>
                                                                        <span class="required" id="emp_name_error"></span>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <div class="form-group">

                                                                <div class="">

                                                                    <div class="col-12">
                                                                        <label class="">Status</label>
                                                                        <div class="mt-radio-inline">
                                                                            <label class="mt-radio">
                                                                                <input type="radio" id="status"  name="status" value="1" checked="">Active
                                                                                <span class="required" id="status_error"></span>
                                                                            </label>
                                                                            <label class="mt-radio">
                                                                                <input type="radio" id="status1"    name="status" value="0" > Deactive
                                                                                <span></span>
                                                                            </label>

                                                                        </div>
                                                                        <span class="required" id="active_error"></span>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <br>
                                                            <div class="modal-footer">
                                                                <div class="form-actions">
                                                                    <div class="row">
                                                                        <div class="col-md-offset-3 col-md-9">
                                                                            <button type="button" class="btn red" data-dismiss="modal" tooltip="Cancel" style="float:right; margin: 2px;"><i class="fa fa-close"></i></button>
                                                                            <button type="submit"  id="btn_ticket_configuration" name="btn_ticket_configuration" class="btn blue"  data-style="expand-left"  style="float:right;  margin: 2px;">
                                                                                <i class="fa fa-save" tooltip="Save"></i></button>
                                                                        </div>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!--end modal content-->
                                        <div class="portlet-body">
                                            <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="dataTables_length" id="sample_1_length">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- END EXAMPLE TABLE PORTLET-->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="ticket_congigured_table" role="grid" aria-describedby="sample_1_info" style="text-align: center !important;">
                                <thead>
                                    <tr role="row">
                                        <!--<th scope="col"> Sr No</th>-->
                                        <th scope="col" style="text-align: center !important;">Ticket Name</th>
                                        <th scope="col" style="text-align: center !important;">Description</th>
                                        <th scope="col" style="text-align: center !important;">Branch</th>
                                        <th scope="col"style="text-align: center !important;">Employee</th>
                                        <th scope="col" style="text-align: center !important;">Status</th>
                                        <th scope="col" style="text-align: center !important;">Action</th>

                                    </tr>
                                </thead>
                                <tbody id="ticket_congigured"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('human_resource/footer'); ?>
    </div>

</div>
</div>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script>

    $(document).ready(function () {
        $('#Ticket_Modal').on('hide.bs.modal', function (e) {
            $("#ticket_typ_form").trigger("reset");
            getbranch_name();
            getemployee();
            $("#Branch_name").empty();
            $("#Select_Employes").empty();
        });

        $("#ticket_typ_form").validate({

            rules: {
                type: {
                    required: true,
                },
                status: {
                    required: true,
                },
                Desc: {
                    required: true,
                },
            },
            messages: {
                type: {
                    required: "Please Enter Type Name",
                },
                status: {
                    required: "Please select status",
                },
                Desc: {
                    required: "Please Fill Description",
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                //Custom position: first name
                //                    var placement = $(element).data('error');
                if (element.attr("id") == "type") {
                    $("#t1").append(error);
                } else if (element.attr("id") == "Desc") {
                    $("#Desc_error").append(error);
                } else if (element.attr("id") == "status") {
                    $("#status_error").append(error);
                }


            },

            submitHandler: function (form) {

                branch_id = $("#Branch_name").val();
                var type = document.getElementById('type').value;
                var Desc = document.getElementById('Desc').value;
                var updated_id = document.getElementById('update_value').value;
                var checked_site_radio = $('input:radio[name=status]:checked').val();
                var firm_name = $("#Branch_name :selected").text();
                employe_id = $("#Select_Employes").val();
//                                console.log(employe_id);

                var employe_name = $("#Select_Employes :selected").text();
//                                console.log(employe_name);
                if (firm_name == '') {
                    document.getElementById("Branch_name").setAttribute("isvalid", "true");
                    $("#Branch_name_error").empty();
                    $("#Branch_name_error").append('Please Select Branch Name');
                } else if (employe_name == '') {
                    document.getElementById("Branch_name").setAttribute("valid", "true");
                    $("#Branch_name_error").empty();
                    document.getElementById("Select_Employes").setAttribute("isvalid", "true");
                    $("#emp_name_error").empty();
                    $("#emp_name_error").append('Please Select Employee Name');
                } else {
                    document.getElementById("Select_Employes").setAttribute("valid", "true");
                    $("#emp_name_error").empty();
                    $.ajax({
                        url: "<?= base_url("/Ticket_Configuration/insert_ticket") ?>",
                        type: "POST",
                        data: {Desc: Desc, checked_site_radio: checked_site_radio, updated_id: updated_id, type: type, employe_id: employe_id, employe_name: employe_name, branch_id: branch_id, firm_name: firm_name},
                        success: function (result) {

                            //                                                        location.reload();
                            //                                             if (result.status === true) {
                            //                                                 alert('Survey Send successfully');
                            //                                             } else {
                            //                                             }
                            //                                         },
                            //                                         error: function (result) {

                            //                                             if (result.status === 500) {
                            //                                                 alert('Something Went Wrong');
                            //                                             } else {
                            // //
                            //                                             }

                            $('#ticket_typ_form').trigger('reset');
                            $('#Ticket_Modal').modal('toggle');
								toastr.success(result);
             
                            load();
                            remove_error();
//                                            location.reload();
                        }

                    });
                }
            }
        })
    });
    function getbranch_name() {
        $.ajax({
            url: "<?= base_url("/Ticket_Configuration/get_firm_name") ?>",
            dataType: "json",
            success: function (result) {



                if (result['message'] === 'success') {
                    var data = result.frim_data;
                    $('#boss_id').val(data[0]['reporting_to']);
                    var ele3 = document.getElementById('Branch_name');
                    ele3.innerHTML = '';
                    for (var i = 0; i < data.length; i++)
                    {
                        // POPULATE SELECT ELEMENT WITH JSON.
                        ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                    }
                }
            }
        });
    }
    function getemployee()
    {
        $.ajax({

            url: "<?= base_url("/Ticket_Configuration/get_employee_data") ?>",
            type: "POST",
            dataType: "json",
            success: function (result) {

                if (result['message'] === 'success') {
                    var emp_name = result.emp_data;
//                                    $('#boss_id').val(data[0]['reporting_to']);
                    var ele5 = document.getElementById('Select_Employes');

                    ele5.innerHTML = '';
//
                    for (a = 0; a < emp_name[0][0].length; a++)
                    {
                        // POPULATE SELECT ELEMENT WITH JSON.


                        ele5.innerHTML = ele5.innerHTML + '<option value="' + emp_name[0][1][a] + '">' + emp_name[0][0][a] + '' + ' ( ' + emp_name[0][2][a] + ')' + '</option>';
                    }

                }
            }
        });
    }
    $(document).ready(function () {
        load();
        getbranch_name();
        getemployee();
    });

    function load() {
        var checked_site_radio = $('input:radio[name=status]:checked').val();
        $.ajax({
            url: "<?= base_url("/Ticket_Configuration/show_data") ?>",
            type: "POST",
            data: {checked_site_radio: checked_site_radio},
            success: function (result) {
                if (result)
                {
                    var json = JSON.parse(result)

                    if (json["ticket_data"] !== "") {
                        $('#ticket_congigured').empty();
                        $('#ticket_congigured').append(json['ticket_data']);
                        $('#ticket_congigured_table').DataTable();
                    }
                    if (json["ticket_data"] === "") {
						toastr.error(json["error"]);
         
                    }
                }
            },
            error: function (result) {
                console.log(result);
            }

            // location.reload();
        });
    }

    function firm_update(updated_id)
    {
        $.ajax({

            url: "<?= base_url("/Ticket_Configuration/get_type_name") ?>",
            type: "POST",
            data: {updated_id: updated_id},
            dataType: "json",
            success: function (result) {
//
                if (result['message'] === 'success') {
                    var data = result.type_name;
                    var datadesc = result.Description;
                    var Is_active = result.Is_active;

                    var b_name = result.b_name;
                    //branch detail
                    var data2 = result.comman_array;
                    var data3 = result.selected_array;
                    var data4 = result.comman_id_array;
                    var data5 = result.selected_id_array;
                    //employee detail
                    var data6 = result.comman_emp_array_finial;
                    var data8 = result.comman_Emp_array_finial_id;
                    var data7 = result.selected_emp_array;
                    var data9 = result.selected_emp_id_array;
                    console.log(data7);
                    console.log(data9);

                    document.getElementById('type').value = data;
                    document.getElementById('Desc').value = datadesc;
//
                    if (Is_active == 1)
                    {
//
                        $("#status").attr('checked', 'checked');
//
                    }
                    if (Is_active == 2)
                    {
//
                        $("#status").attr('checked', 'checked');
//
                    }

                    var ele4 = document.getElementById('Branch_name');
                    var ele5 = document.getElementById('Select_Employes');
                    ele4.innerHTML = '';
                    ele5.innerHTML = '';

                    //firm_name

                    for (i = 0; i < data2.length; i++)
                    {
                        // POPULATE SELECT ELEMENT WITH JSON.

                        ele4.innerHTML = ele4.innerHTML + '<option  value="' + data4[i] + '">' + data2[i] + '</option>';
                    }
                    for (j = 0; j < data3.length; j++)
                    {
                        // POPULATE SELECT ELEMENT WITH JSON.

                        ele4.innerHTML = ele4.innerHTML + '<option selected value="' + data5[j] + '">' + data3[j] + '</option>';
                    }
                    //servie name

                    for (a = 0; a < data6.length; a++)
                    {
                        // POPULATE SELECT ELEMENT WITH JSON.


                        ele5.innerHTML = ele5.innerHTML + '<option value="' + data8[a] + '">' + data6[a] + ' ( ' + b_name[a] + ')' + '</option>';
                    }
                    for (p = 0; p < data9.length; p++)
                    {
                        // POPULATE SELECT ELEMENT WITH JSON.

                        ele5.innerHTML = ele5.innerHTML + '<option selected value="' + data9[p] + '">' + data7[p][0] + ' ( ' + data7[p][1] + ')' + '</option>';
                    }

                }
            }
        });
        return;
    }


    $('#Ticket_Modal').on('show.bs.modal', function (e) {
        var update_value = $(e.relatedTarget).data('ticket_type_id');
        $('<input>').attr({
            type: 'hidden',
            id: 'update_value',
            name: 'update_value',
            value: update_value
        }).appendTo('#ticket_typ_form');
    });
    $('#Ticket_Modal').on('hide.bs.modal', function (e) {
        $("#update_value").remove();
    });
    function delete_ticket(delete_id) {

        var result = confirm("Are you sure do you want to delete this ticket?");
        if (result) {
            $.ajax({
                url: "<?= base_url("/Ticket_Configuration/delete_data") ?>",
                type: "POST",
                data: {delete_id: delete_id},
                success: function (result) {
						toastr.success(result);
               
                    location.reload();
                },
                error: function (result) {
                    console.log(result);
                }

                //
            });
        } else {
            //        location.reload();
        }
    }

    function changestatus(status, type_id) {

        var result = confirm("Are you sure do you want Active/De-active this ticket");


//                        console.log(status);
        if (result)
        {
            $.ajax({
                url: "<?= base_url("/Ticket_Configuration/change_status") ?>",
                type: "POST",
                data: {status: status, type_id: type_id},
                success: function (result) {

                    location.reload();
                },
                error: function (result) {
                    console.log(result);
                }


                // location.reload();
            });
        }
    }




</script>