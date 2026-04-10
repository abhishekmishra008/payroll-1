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
    span.error {
        color:red;
    }
    .form-control .error{
        color: red;
    }
    table tr td{
        text-align: left;
    }
    .page-quick-sidebar-wrapper{
        width: 800px!important;
        right:-800px;
    }
</style>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<div class="page-content-wrapper ">
    <div class="page-content ">
        <div class="col-md-12 ">
            <div class="row wrapper-shadow">
                <div class="portlet light portlet-fit portlet-form ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">View All Checklist Options</span>
                        </div>
                        <div class="actions">

                            <div class="">
                                <button class="btn blue-hoki btn-outline sbold uppercase popovers"
                                        data-toggle="modal"  data-target="#checklistoptionModal" data-check_option_value="0"
                                        data-container="body" data-trigger="hover" data-placement="left"
                                        data-content="Create New Check List Document Options For Employee to upload."
                                        >
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button class="btn blue-hoki btn-outline sbold uppercase quick-sidebar-toggler quick-sidebar-toggler "
                                        data-toggle="collapse">
                                    <i class="fa fa-question"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="col-md-12">
                            <div class="well-lg bg-grey-steel-opacity" id="first_well" style="height: 380px;margin-bottom: 16px" >
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <img src="<?= base_url() ?>/assets/global/img/no-data-found1 (1).png"  />
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <div class="font-grey-gallery well-sm">
                                            <h2 style="margin-top:4em;">Add Your First Document Checklist Options</h2>
                                            <h4> Create New Check List Document Options For Employee to upload. </h4>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row well-lg " id="table_well">
                                <table class="table table-striped no-footer table-bordered table-hover order-column" id="check_listoption_table">
                                    <thead>
                                        <tr class="bold">
                                            <th>Name</th>
                                            <th>Option</th>
                                            <th>Instruction</th>
                                            <th>Instruction file</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="check_listoption_tbody">

                                    </tbody>
                                </table>
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
<div class="modal fade" id="checklistoptionModal" role="dialog">
    <div class="modal-dialog ">
        <div class="portlet light  portlet-form ">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-settings font-red"></i>
                    <span class="caption-subject font-red sbold uppercase">Checklist Options</span>
                </div>
            </div>
            <div class="portlet-body">
                <form id="checklist_option_form" enctype="multipart/form-data" method="post">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="bold">Name</label>
                            <input type="text" id="check_title" name="check_title" class="form-control" />

                        </div>
                        <div class="form-group">
                            <label class="bold">Options</label>
                            <div class="mt-radio-inline">
                                <label class="mt-radio">
                                    <input type="radio" name="check_option" id="check_option1" value="<?= base64_encode("1") ?>" checked=""> Optional
                                    <span></span>
                                </label>
                                <label class="mt-radio">
                                    <input type="radio" name="check_option" id="check_option2" value="<?= base64_encode("2") ?>"> Compulsory
                                    <span></span>
                                </label>
                            </div>
                            <p class="help-block small">This Option type specify document importance for employee </p>
                        </div>
                        <div class="form-group">
                            <label class="bold" for="exampleInputFile1">Instruction file</label>
                            <div id="pre_file" class="help-block">
                            </div>
                            <input type="file" name="userfile[]" id="userfile" multiple />


                            <span class="help-block help-block-error"></span>
                            <p class="help-block small">This Instruction file guide employee to upload document.(Optional) </p>
                        </div>
                        <div class="form-group">
                            <label class="bold">Instruction Details</label>
                            <textarea  name="check_description" id="instruction_description"  rows="3" class="form-control"></textarea>
                            <span class="help-block help-block-error"></span>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="form-action pull-right" style="padding-right: 30px;">
                            <button  type="submit" class="btn blue">
                                <i class="fa fa-save"></i>
                                Save changes</button>
                            <button type="button" class="btn red" data-dismiss="modal">
                                <i class="fa fa-close"></i>
                                Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="page-quick-sidebar-wrapper">
    <a href="javascript:;" class="page-quick-sidebar-toggler">
        <i class="icon-login"></i>
    </a>
    <embed src="<?= base_url('documents/Checklist document.pdf#view=fitH,100&zoom=100') ?>" type="application/pdf" width="100%" height="100%" />
</div>
<style>
    .popover {
        background: #fff;
    }
    .popover.title{
        background: red;
    }
</style>
<link href="<?php echo base_url() . "assets/"; ?>/global/plugins/jquery-notific8/jquery.notific8.min.css" rel="stylesheet" type="text/css"/>


<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-notific8/jquery.notific8.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/mime@latest.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/nas.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/download.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            url: "<?= base_url("Hr_checklist_Controller/check") ?>",
            type: "POST",
            success: function (success) {
                if (!success) {
                    toastr.error("Nas Device Not Configured");
                }
            },
            error: function (error) {
                console.log(success);
            }
        });



        /*
         *
         * check list option form
         */
        var setlist_ref;

        $('#checklist_option_form').validate({
            rules: {
                check_title: {
                    required: true
                }
            },
            messages: {
                check_title: {
                    required: "Please Enter Option Name"
                }
            },
            errorElement: 'span',
            submitHandler: function (form) {
                start_loading();
                get_nas_transaction_id().then(function (generatedID) {
				get_finial_folder_id_for_create_firm_in_hq_js_partner_header_all().then(function(parent_id){
                    var file = "";
                    var file_lenght = document.getElementById('userfile').files.length;
                    if (file_lenght === 0) {
                        file = '';
                    } else {
                        file = document.getElementById('userfile').files[0].name;
                    }
                    var newForm = new FormData(form);
                    newForm.append("NasGeneratedId", generatedID);
                    newForm.append("filename", file);
                    $.ajax({
                        url: "<?= base_url("hr_checklist_master/make_list_option") ?>",
                        type: "POST",
                        data: newForm,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (success) {

                            success = JSON.parse(success);
                            var message = success["response"];
                            UploadFileOnNas('', parent_id, 'userfile', generatedID).then(function (r) {
                                if (r !== 1) {
                                    $("#checklistoptionModal").modal("toggle");
                                    stop_loading();
                                    toastr.error("Faild to Store Transaction Id");
                                } else {
                                    $("#checklistoptionModal").modal("toggle");
                                    $("#checklist_option_form").trigger("reset");
                                    stop_loading();
                                    toastr.success(message);
                                    load_checklist_table();
                                }
                            }).catch(function (e) {
                                console.log(e);
                                stop_loading();
                            });
                        },
                        error: function (error) {
                            stop_loading();
                            error = JSON.parse(error);
                            var message = error["response"];

                            $("#checklist_option_form").trigger("reset");
                            $("#checklistoptionModal").modal("toggle");
                            $.notific8(message, {
                                horizontalEdge: 'bottom',
                                verticalEdge: 'right',
                                zindex: 50000
                            });
                        }
                    });

                });
				});


            }
        });

        load_checklist_table();
        assignCheckList();
    });

    function check_data_availble() {
        if ($("#check_listoption_tbody tr td.dataTables_empty").length === 1) {
            $("#table_well").hide();
            $("#first_well").show();
        } else {
            $("#table_well").show();
            $("#first_well").hide();
        }
    }
    function get_checklist_option_by_id(id) {
        var url = "<?= base_url() ?>hr_checklist_master/checklist_option_record";
        var data = {
            "list_id": id
        };
        $.ajax({
            url: url,
            data: data,
            method: "post"
        }).done(function (checklist) {
            var object = JSON.parse(checklist);
            $("#check_title").val(object['checklist'][0]["title"]);
            if (object['checklist'][0]["option_type"] == "1") {
                $("#check_option1").attr("checked", "checked");
            }
            if (object['checklist'][0]["option_type"] == "2") {
                $("#check_option2").attr("checked", "checked");
            }
            if (object['checklist'][0]["instruction_file"] !== "0") {
                var base = '<?= base_url() ?>';
                var str = "<label>Previous file</label><a id='previous_file' href="
                        + base + object['checklist'][0]["instruction_file"] + " download> <i class='fa fa-download'></i> download</a>";
                $("#pre_file").html(str);
                var p_file = '<input type="hidden" name="previouse_file_path" id="previouse_file_path" value=' + object['checklist'][0]["instruction_file"] + ' />';
                $('#checklist_option_form').append(p_file);
            }
            $("#instruction_description").val(object['checklist'][0]["check_descp"]);

        }).fail(function (error) {
            $.notific8(error, {
                horizontalEdge: 'bottom',
                verticalEdge: 'right',
                zindex: 50000,
                theme: 'ruby'
            });
        });
    }

    function checklist_option_delete_by(id) {
        var url = "<?= base_url("Hr_checklist_Controller/checklist_delete_by") ?>";
        var data = {"doc_id": id};
        $.ajax({
            url: url,
            data: data,
            method: "post"
        }).done(function (success) {

            success = JSON.parse(success);
            var message = success["response"];

            load_checklist_table();
            $.notific8(message, {
                horizontalEdge: 'bottom',
                verticalEdge: 'right',
                zindex: 50000
            });
        }).fail(function (error) {

            $.notific8(error, {
                horizontalEdge: 'bottom',
                verticalEdge: 'right',
                zindex: 50000,
                theme: 'ruby'
            });
        });
    }

    function assignCheckList(id) {
        var url = "<?= base_url("hr_checklist_master/assign_checklist") ?>";
        $.ajax({
            url: url,
            method: "post"
        }).done(function (success) {
//            success = JSON.parse(success);
//            console.log(success);
        }).fail(function (error) {
//            console.log(error);
        });
    }



    function load_checklist_table() {
        start_loading();
        var url = "<?= base_url("hr_checklist_master/checklist_option_list") ?>";
        $.ajax({
            url: url,
            method: "post"
        }).done(function (success) {
            stop_loading();
            success = JSON.parse(success);

            $('#check_listoption_tbody').empty();
            $('#check_listoption_tbody').append(success['checklist']);
            $('#check_listoption_table').DataTable();
            check_data_availble();
        }).fail(function (error) {
            stop_loading();
            $.notific8(error, {
                horizontalEdge: 'bottom',
                verticalEdge: 'right',
                zindex: 50000,
                theme: 'ruby'
            });
        });

    }
//checklistoptionModal

    $('#checklistoptionModal').on('show.bs.modal', function (e) {
        var update_value = $(e.relatedTarget).data('check_option_value');

        if (update_value !== 0) {
            $('<input>').attr({
                type: 'hidden',
                id: 'update_value',
                name: 'update_value',
                value: update_value
            }).appendTo('#checklist_option_form');
            get_checklist_option_by_id(update_value);
        }
    });

    $('#checklistoptionModal').on('hide.bs.modal', function (e) {
        $("#pre_file").empty();
        $("#update_value").remove();
        $('#checklist_option_form').trigger("reset");
    });
    function start_loading() {
        document.getElementById('loading_div').style.display = "block";
    }
    function stop_loading() {
        document.getElementById('loading_div').style.display = "none";
    }

</script>