<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username = $this->session->userdata('login_session');
?>

<style>
    label.error {
        color:red;
    }
    .form-control .error{
        color: red;
    }
</style>
<script src="<?php echo base_url() . "assets/"; ?>js/mime@latest.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/nas.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/download.js" type="text/javascript"></script>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css"/>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="col-md-12">
            <div class="row wrapper-shadow">
                <div class="portlet light portlet-fit portlet-form">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">View Document Uploaded</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="col-md-12">
                            <div class="row well-sm">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bold">Office Name</label>
                                        <select class="form-control" id="ddl_firm_name_fetch" name="ddl_firm_name_fetch" onchange="get_sorted_data()">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="well-lg bg-grey-steel-opacity " id="first_well" style="height: 380px;margin-bottom: 16px" >
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <img src="<?= base_url() ?>/assets/global/img/no-data-found1 (1).png"  />
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="font-grey-gallery well-sm">
                                            <h2 style="margin-top:4em;" id="banner_heading">Select Firm To View Document Uploaded</h2>
                                            <h4 id="banner_description">View all new and old uploaded documents in second document.</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="table_well">
                                <div class="row well-sm">
                                    <div class="col-md-4">
                                        <div class="caption">
                                            <i class=" icon-settings font-red"></i>
                                            <span class="caption-subject font-red sbold uppercase">View Document Uploaded</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row well-lg ">
                                    <table class="table table-striped no-footer table-bordered table-hover order-column" id="rejection_list_table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Document Link</th>
                                                <th>Upload By</th>
                                                <th>Uploaded At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rejection_list_tbody">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row well-sm">
                                    <div class="col-md-4">
                                        <div class="caption">
                                            <i class=" icon-settings font-red"></i>
                                            <span class="caption-subject font-red sbold uppercase">View All Document Uploaded</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row well-lg ">
                                    <table class="table table-striped no-footer table-bordered table-hover order-column" id="rejection_all_list_table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Document Link</th>
                                                <th>Status</th>
                                                <th>Rejection Reason</th>
                                                <th>Upload By</th>
                                                <th>Uploaded At</th>
                                                <th>Action By</th>
                                                <th>Action At</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rejection_all_list_tbody">
                                        </tbody>
                                    </table>
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
<div class="loading" id="loading_div" style="display:none;z-index: 100000;"></div>
<!--start recjtion modal-->
<div class="modal fade" id="documentUploadedModel" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <i class=" icon-layers font-green"></i>
                <span class="caption-subject font-green sbold uppercase">Add Rejection Reason</span>
            </div>
            <div class="modal-body">
                <form id="rejection_document_form"  method="post">
                    <div class="form-body">
                        <!--Check list Name-->
                        <div class="form-group form-md-line-input">
                            <div class="col-md-12">
                                <textarea class="form-control" name="reason_text" placeholder="Add rejection reason"></textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-6 col-md-6">
                                    <button  type="submit" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link href="<?php echo base_url() . "assets/"; ?>/global/plugins/jquery-notific8/jquery.notific8.min.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-notific8/jquery.notific8.min.js" type="text/javascript"></script>

<script>

                                            $(document).ready(function () {
                                                check_data_availble();
                                                $.ajax({
                                                    url: "<?= base_url("Human_resource/get_ddl_firm_name") ?>",
                                                    dataType: "json",
                                                    success: function (result) {
                                                        if (result['message'] === 'success') {
                                                            var data = result.frim_data;
                                                            $('#boss_id').val(data[0]['reporting_to']);
                                                            var ele3 = document.getElementById('ddl_firm_name_fetch');
                                                            ele3.innerHTML = '<option disabled selected>Select Office Name</option>';
                                                            for (i = 0; i < data.length; i++)
                                                            {

                                                                ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';

                                                            }
                                                            check_data_availble();
                                                        }
                                                    }
                                                });


                                                $('#documentUploadedModel').on('show.bs.modal', function (e) {
                                                    var update_value = $(e.relatedTarget).data('document_value');
                                                    var request_type = $(e.relatedTarget).data('request_type');
                                                    if (update_value !== 0 && request_type !== 0) {
                                                        $('<input>').attr({
                                                            type: 'hidden',
                                                            id: 'update_value',
                                                            name: 'update_value',
                                                            value: update_value
                                                        }).appendTo('#rejection_document_form');
                                                        $('<input>').attr({
                                                            type: 'hidden',
                                                            id: 'request_type',
                                                            name: 'request_type',
                                                            value: request_type
                                                        }).appendTo('#rejection_document_form');
                                                    }
                                                });

                                                $('#documentUploadedModel').on('hide.bs.modal', function (e) {
                                                    $("#update_value").remove();
                                                    $('#request_type').remove();
                                                    $("#user_create_form").trigger("reset");
                                                });
                                            });
                                            function load_uploaded_document() {
                                                start_loading();
                                                var firm_id = $("#ddl_firm_name_fetch option:selected").val()
                                                var url = "<?= base_url("uploded_document_list") ?>";
                                                $.ajax({
                                                    url: url,
                                                    data: {firm_id: firm_id},
                                                    method: "post"
                                                }).done(function (success) {
                                                    stop_loading();
                                                    success = JSON.parse(success);
                                                    console.log(success);
                                                    $('#rejection_list_tbody').empty();
                                                    $('#rejection_list_tbody').append(success['response']['files']);
                                                    $('#rejection_list_table').DataTable();
                                                    check_data_availble('rejection_list_tbody tr');
                                                }).fail(function (error) {
                                                    stop_loading();
                                                });
                                            }

                                            function load_all_uploaded_document() {
                                                start_loading();
                                                var firm_id = $("#ddl_firm_name_fetch option:selected").val()
                                                var url = "<?= base_url("uploded_document_all_list") ?>";
                                                $.ajax({
                                                    url: url,
                                                    data: {firm_id: firm_id},
                                                    method: "post"
                                                }).done(function (success) {
                                                    stop_loading();
                                                    success = JSON.parse(success);
                                                    console.log(success);
                                                    $('#rejection_all_list_tbody').empty();
                                                    $('#rejection_all_list_tbody').append(success['response']['files']);
                                                    $('#rejection_all_list_table').DataTable();
                                                    check_data_availble('rejection_all_list_tbody tr');
                                                }).fail(function (error) {
                                                    stop_loading();
                                                });
                                            }

                                            function accepted(type, id) {
                                                $.ajax({
                                                    url: "<?= base_url("Hr_checklist_Controller/rejection_new_document") ?>",
                                                    type: "POST",
                                                    data: {request_type: type, doc_id: id},
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        var message = success["response"];
                                                        load_uploaded_document();
                                                        load_all_uploaded_document();
                                                        $.notific8(message, {
                                                            horizontalEdge: 'bottom',
                                                            verticalEdge: 'right',
                                                            zindex: 50000
                                                        });
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        error = JSON.parse(error);
                                                        var message = error["response"];
                                                        console.log(message);
                                                        $("#checklist_option_form").trigger("reset");
                                                        load_uploaded_document();
                                                        load_all_uploaded_document();
                                                        $("#documentUploadedModel").modal("toggle");
                                                        $.notific8(message, {
                                                            horizontalEdge: 'bottom',
                                                            verticalEdge: 'right',
                                                            zindex: 50000
                                                        });
                                                    }
                                                });
                                            }





                                            var setlist_ref;
                                            $('#rejection_document_form').on('submit', function (event) {
                                                event.preventDefault();
                                                $.ajax({
                                                    url: "<?= base_url("Hr_checklist_Controller/rejection_new_document") ?>",
                                                    type: "POST",
                                                    data: new FormData(this),
                                                    contentType: false,
                                                    cache: false,
                                                    processData: false,
                                                    success: function (success) {
                                                        success = JSON.parse(success);
                                                        var message = success["response"];
                                                        $("#checklist_option_form").trigger("reset");
                                                        load_uploaded_document();
                                                        load_all_uploaded_document();
                                                        $("#documentUploadedModel").modal("toggle");

                                                        $.notific8(message, {
                                                            horizontalEdge: 'bottom',
                                                            verticalEdge: 'right',
                                                            zindex: 50000
                                                        });
                                                    },
                                                    error: function (error) {
                                                        console.log(error);
                                                        error = JSON.parse(error);
                                                        var message = error["response"];
                                                        console.log(message);
                                                        $("#checklist_option_form").trigger("reset");
                                                        load_uploaded_document();
                                                        load_all_uploaded_document();
                                                        $("#documentUploadedModel").modal("toggle");
                                                        $.notific8(message, {
                                                            horizontalEdge: 'bottom',
                                                            verticalEdge: 'right',
                                                            zindex: 50000
                                                        });
                                                    }
                                                });
                                            }
                                            );
                                            function get_sorted_data() {

                                                load_uploaded_document();
                                                load_all_uploaded_document();
                                            }

                                            function check_data_availble() {
                                                if ($('#ddl_firm_name_fetch').find(":selected").text() === 'Select Office Name') {
                                                    $("#table_well").hide();
                                                    $("#first_well").show();
                                                } else {
                                                    $("#table_well").show();
                                                    $("#first_well").hide();
                                                }
                                            }
                                            function start_loading() {
                                                document.getElementById('loading_div').style.display = "block";
                                            }
                                            function stop_loading() {
                                                document.getElementById('loading_div').style.display = "none";
                                            }


</script>