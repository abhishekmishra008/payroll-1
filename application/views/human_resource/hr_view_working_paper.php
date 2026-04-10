<?php
// $this->load->view('admin/header');
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
        <div class="col-md-12 ">
            <div class="row wrapper-shadow">
                <div class="portlet light portlet-fit portlet-form">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-settings font-red-sunglo"></i>

                            <span class="caption-subject bold uppercase">View Working Documents</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group">

                                <button  id="sample_1_new" class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers" data-toggle="modal" data-target="#myModal" data-container="body" data-placement="left" data-trigger="hover" data-content="Upload New"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
                            <div class="row well-lg " id="table_well">
                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info" style="text-align:center;">
                                    <thead>
                                        <tr role="row">
                                            <th scope="col" style="text-align: center; width:200px;">File Description</th>
                                            <th style="text-align: center;" scope="col">View/Download</th>
                                            <th style="text-align: center;" scope="col">Downloadable</th>
                                            <th style="text-align: center;" scope="col">Uploaded On</th>
                                            <th style="text-align: center;" scope="col">Edit</th>
                                            <th style="text-align: center;" scope="col">Delete</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($record_working_ppr !== "") {
                                            foreach ($record_working_ppr as $row) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row->file_description; ?> </td>
                                                    <td>
                                                        <a class="btn btn-circle blue btn-icon-only btn-default" onclick="download_nas_file('<?php echo $row->file_id; ?>', '<?php echo $row->uploaded_file; ?>')" download=""  ><i class='fa fa-download'></i></a>
                                                        <?php
                                                        //echo $row->is_downloadable;
                                                        //   if ($row->is_downloadable == '1') {  //?>
                                                        <!--<a class="btn btn-circle blue btn-icon-only btn-default" href="//<?= base_url() ?>uploads/hr/<?php // echo $row->uploaded_file;             ?>" download=""  ><i class='fa fa-download'></i></a>-->
                                                        <?php // } else {  ?>
                                                <!--<a class="btn btn-circle blue btn-icon-only btn-default"  ><i class='fa fa-view'></i></a>-->
                                                        <?php
                                                        // } //	?>

                                                    </td>
                                                    <td><?php
                                                        //echo $row->is_downloadable;
                                                        if ($row->is_downloadable == '1') {
                                                            echo 'YES';
                                                        } else {
                                                            echo 'NO';
                                                        }
                                                        ?>

                                                    </td>
                                                    <td><?php
                                                        $newDate = date("d-m-Y", strtotime($row->created_on));
                                                        echo $newDate;
                                                        ?> </td>
                                                    <td style="text-align: center;"><a class="btn btn-circle blue btn-icon-only btn-default" data-toggle="modal" data-target="#edit_working_ppr" data-firm_id="<?php echo $row->firm_id; ?>"data-id="<?php echo $row->id; ?>" ><i class='fa fa-pencil'></i></a></td>
                                                    <td style="text-align: center;"><a class="btn btn-circle red btn-icon-only btn-default" onclick="DeleteNasFile123('<?php echo $row->id; ?>', '<?php echo $row->file_id; ?>')" ><i class='fa fa-remove'></i></a></td>
                                                    <!--<td style="text-align: center;"><a class="btn btn-circle red btn-icon-only btn-default" onclick="DeleteNasFile123('<?php echo $row->file_id; ?>')" ><i class='fa fa-remove'></i></a></td>-->
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            
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
        <?php $this->load->view('human_resource/footer'); ?>
        <script src="<?php echo base_url() . "assets/"; ?>js/mime@latest.js" type="text/javascript"></script>
        <script src="<?php echo base_url() . "assets/"; ?>js/nas.js" type="text/javascript"></script>

    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Working Paper</h4>
            </div>
            <div class="modal-body">
                <form  method="POST"  id="add_workppr_form" name="add_workppr_form" class="form-horizontal" novalidate="novalidate">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="">Select Office</label>
                            <div class="input-group">


                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i></span>
                                <select name="firm_name[]" multiple="" id="firm_name" onchange="remove_error('firm_name');"  class="form-control select2-multiple" >
                                    <option value="">Select Branch</option>
                                </select>
                            </div>
                            <span class="required" id="firm_name_error"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="icheck-inline">
                                <label>Is Downloadable</label>
                                <div class="input-group">
                                    <label>
                                        <input type="radio" id="is_downloadable" name="is_downloadable" checked value="1"  data-checkbox="icheckbox_flat-grey"> yes </label> &nbsp;
                                    <label>
                                        <input type="radio" id="is_downloadable" name="is_downloadable" value="0" data-checkbox="icheckbox_flat-grey" > No </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <label for="exampleInputFile1">Upload Image</label>
                                <div class="input-group">
                                    <input type="file" id="image_file" onchange="remove_error('image_file')" name="image_file"> </div>
                                <p class="help-block"> choose image for event. </p>
                                <span class="required" style="color:red" id="image_file_error"></span>
                            </div>
                            <div class="col-md-6">
                                <label>File Description</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-globe"></i>
                                    </span>
                                    <textarea class="form-control" name="file_description" onkeyup="remove_error('file_description')" id="file_description" rows="3"></textarea>   </div>
                                <span class="required" id="file_description_error"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="button" class="btn grey-salsa btn-outline"data-dismiss="modal"  style="float:right; margin: 2px;">Cancel</button>
                            <button type="button" id="btn_add_work_ppr" name="btn_add_work_ppr"  class="btn green btn green btn btn-primary mt-ladda-btn ladda-button mt-progress-demo"  data-style="expand-left"  style="float:right;  margin: 2px;">
                                <span class="ladda-label">Upload</span>
                                <span class="ladda-spinner"></span><span class="ladda-spinner"></span><div class="ladda-progress" style="width: 115px;"></div>
                            </button>
                        </div>
                        <div class="loading" id="loaders8" style="display:none;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_working_ppr" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <form  method="POST"  id="edit_workppr_form" name="edit_workppr_form" class="form-horizontal" novalidate="novalidate">
                    <input type="hidden" id="work_id" name="work_id">
                    <input type="hidden" id="firm_id" name="firm_id">
                    <div id="edit_work_ppr_data" name="edit_work_ppr_data"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn green btn green btn btn-primary mt-ladda-btn ladda-button mt-progress-demo" id="update_working_ppr" name="update_working_ppr">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <div class="loader" id="loaders" style="display:none;"></div>
            </div>
        </div>

    </div>

</div>
<div class="loading" id="loading_div" style="display:none;z-index: 100000;"></div>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

<script src="<?php echo base_url() . "assets/"; ?>js/download.js" type="text/javascript"></script>

<script>

                                        /*create_folder('<?php echo $access_token; ?>','<?php echo $proxy_url; ?>','vishu_Waghe23','root').
                                         then(function (r) { 
                                         console.log(r);
                                         });*/
                                        //delete_file_from_nas_device();


                                        $('#edit_working_ppr').on('show.bs.modal', function (e) {
                                            var id = $(e.relatedTarget).data('id');
                                            var work_id = document.getElementById('work_id').value = id;
                                            var firmid = $(e.relatedTarget).data('firm_id');
                                            var firm_id = document.getElementById('firm_id').value = firmid;
                                            $.ajax({
                                                type: "post",
                                                url: "<?= base_url("/Human_resource/get_workingppr_data") ?>",
                                                dataType: "json",
                                                data: {work_id: work_id, firm_id: firm_id},
                                                success: function (result) {
                                                    if (result['message'] === 'success') {
                                                        var data = result.record_working_ppr;
                                                        $('#edit_work_ppr_data').html(data);
                                                    } else {
                                                    }
                                                }
                                            });
                                        });
                                        function  remove_error(id) {
                                            $('#' + id + '_error').html("");
                                        }
                                        $(document).ready(function () {


                                            $.ajax({
                                                url: "<?= base_url("/Human_resource/get_ddl_firm_name") ?>",
                                                dataType: "json",
                                                success: function (result) {
                                                    if (result['message'] === 'success') {
                                                        var data = result.frim_data;
                                                        $('#boss_id').val(data[0]['reporting_to']);
                                                        //                                                        var ele3 = document.getElementById('ddl_firm_name_fetch');
                                                        var ele4 = document.getElementById('firm_name');
                                                        for (i = 0; i < data.length; i++)
                                                        {
                                                            ele4.innerHTML = ele4.innerHTML +
                                                                    //'<option value="' + data[i]['firm_id'] + ',' + data[i]['firm_name'] + '">' + data[i]['firm_name'] + '</option>';
                                                                    '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                            //                                                            ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                        }
                                                    }
                                                }
                                            });
                                        });

                                        $("#btn_add_work_ppr").click(function () {

                                            var firm_id = $('#firm_name').val();
                                            console.log(firm_id);
                                            get_nas_transaction_id().then(function (file_trans_id) {
                                                getParentIdWorkingPaper(1, firm_id).then(function (parent_id) {
                                                    console.log(parent_id);
                                                    var $this = $(this);
                                                    $this.button('loading');
                                                    setTimeout(function () {
                                                        $this.button('reset');
                                                    }, 2000);
                                                    //document.getElementById('loaders8').style.display = "block";
                                                    var file = "";
                                                    var file_lenght = document.getElementById('image_file').files.length;
                                                    if (file_lenght == 0) {
                                                        file = '';
                                                    } else {
                                                        file = document.getElementById('image_file').files[0].name;
                                                    }
                                                    $.ajax({
                                                        type: "post",
                                                        url: "<?= base_url("Human_resource/add_working_ppr") ?>",
                                                        dataType: "json",
                                                        data: $("#add_workppr_form").serialize() + "&file_trans_id=" + file_trans_id + "&fileName=" + file, //form data

                                                        success: function (result) {
                                                            if (result.status === true) {
                                                                document.getElementById('loaders8').style.display = "none";
                                                                ok('image_file', parent_id).then(function (create_id) {

                                                                    insert_into_database_file(file_trans_id, create_id).then(function (r3) {
                                                                        alert('Working Paper Uploaded Successfully');
                                                                        window.location.reload();

                                                                    }).catch(function (e) {
                                                                        console.log(e);
                                                                    });
                                                                });


                                                            } else if (result.status === false) {
                                                                document.getElementById('loaders8').style.display = "none";
                                                                alert('something went wrong');
                                                            } else {
                                                                document.getElementById('loaders8').style.display = "none";
                                                                $('#' + result.id + '_error').html(result.error);
                                                                $('#message').html(result.error);
                                                                stop_loading();
                                                            }
                                                        },
                                                        error: function (result) {
                                                            console.log(result);
                                                            if (result.status === 500) {
                                                                alert('Internal error: ' + result.responseText);
                                                                document.getElementById('loaders8').style.display = "none";
                                                            } else {
                                                                alert('Unexpected error.');
                                                                document.getElementById('loaders8').style.display = "none";
                                                            }
                                                            stop_loading();
                                                        }
                                                    });
                                                });
                                            });
                                        });

                                        //delete designation
                                        function DeleteNasFile123(id, file_id)
                                        {
                                            //alert(id);
                                            var result = confirm("Are You Sure, You Want To Delete This Working File?");
                                            if (result) {
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= base_url("Human_resource/delete_working_file") ?>",
                                                    dataType: "json",
                                                    data: {id: id},
                                                    success: function (result) {
                                                        if (result.status === true) {
                                                            DeleteNasFile(file_id);
//alert('Working File Deleted Successfully successfully');
                                                            //location.reload();
                                                        } else {
                                                            alert('something went wrong');
                                                        }
                                                    }
                                                });
                                            } else {
                                            }
                                        }
                                        //update designation
                                        $("#update_working_ppr").click(function ()
                                        {
                                            var $this = $(this);
                                            $this.button('loading');
                                            setTimeout(function () {
                                                $this.button('reset');
                                            }, 2000);
                                            document.getElementById('loaders').style.display = "block";
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Human_resource/edit_working_ppr") ?>",
                                                dataType: "json",
                                                data: $("#edit_workppr_form").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {

                                                        document.getElementById('loaders').style.display = "none";

                                                        alert('Working Paper updated successfully');
                                                        //return;
                                                        location.reload();
                                                    } else {

                                                        document.getElementById('loaders').style.display = "none";

                                                        //                    $('#message').html(result.error);
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
                                        function start_loading() {
                                            document.getElementById('loading_div').style.display = "block";
                                        }
                                        function stop_loading() {
                                            document.getElementById('loading_div').style.display = "none";
                                        }
</script>									