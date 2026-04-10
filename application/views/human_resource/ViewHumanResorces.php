<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$user_id = $this->session->userdata('login_session');
?>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css"/>
<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">

            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'calendar') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>calendar">Home</a>
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>

                </ul>

            </div>
        </div>
        <div class="page-fixed-main-content">
            <!-- BEGIN PAGE BASE CONTENT -->
            <!-- BEGIN DASHBOARD STATS 1-->
            <div class="clearfix"></div>
            <!-- END DASHBOARD STATS 1-->
            <div class="">
                <div class="col-md-12">
                    <!-- BEGIN VALIDATION STATES-->
                    <div class="portlet light portlet-fit portlet-form wrapper-shadow">

                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-red"></i>
                                <span class="caption-subject font-red sbold uppercase"> List of Human Resources </span>
                            </div>
                            <div class="actions">
                                <a href="<?= base_url() ?>create_hr"><button type="button" class="btn blue btn-sm pull-right"> &nbsp; Add Human Resource</button>
                                </a>
                            </div>
                        </div>

                        <div class="portlet-form">

                            <!--2nd modal content-->
                            <div id="create_hr_modal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Create Human Resources</h4>
                                        </div>
                                        <div class="modal-body">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="btn_add_service" name="btn_add_service"  class="btn green btn-info">Add</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!--end 2nd modal content-->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet box">
                                    <div class="portlet-body">
                                        <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">Branch Name</th>
                                                    <th style="text-align: center;">HR Name</th>
                                                    <th style="text-align: center;">Date of Joining</th>
                                                    <th style="text-align: center;">Address</th>
                                                    <th style="text-align: center;">Email ID</th>
                                                    <th style="text-align: center;">Contact No</th>
                                                    <th style="text-align: center;">Status</th>  <th style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
//                                                var_dump($record_hr);
                                                if ($record_hr !== "") {
                                                    foreach ($record_hr as $row) {
                                                        ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $row->firm_name; ?></td>
                                                            <td style="text-align: center;"><?php echo $row->user_name; ?></td>
                                                            <td style="text-align: center;"><?php
                                                                $newDate = date("d-m-Y", strtotime($row->date_of_joining));
                                                                echo $newDate
                                                                ?></td>
                                                            <td style="text-align: center;"><?php echo $row->address; ?></td>
                                                            <td style="text-align: center;"><?php echo $row->email; ?></td>
                                                            <td style="text-align: center;"><?php echo $row->mobile_no; ?></td>

                                                            <td style="text-align: center;">
                                                                <?php if ($row->activity_status == '1') { ?>
                                                                    <button type="button" class="btn green-meadow" onclick="change_emp_status('<?php echo base64_encode($row->user_id); ?>', 'A')">Active</button>
                                                                <?php } else { ?>
                                                                    <button type="button" class="btn  red" onclick="change_emp_status('<?php echo base64_encode($row->user_id); ?>', 'D')">De-active</button>
                                                                <?php }
                                                                ?>
                                                            </td>
                                                            <td style="text-align: center;"><a id="sample_new" class="btn  btn-link btn-sm" title="EDIT" href="<?= base_url() ?>hr_edit/<?php echo $row->user_id ?>" ><i class="fa fa-pen" style="font-size:22px;"></i></a></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        </div>

                    </div>
                    <!-- END VALIDATION STATES-->
                </div>


            </div>
            <?php $this->load->view('human_resource/footer'); ?>
        </div>
    </div>
    <!-- END PAGE BASE CONTENT -->

    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

    <script>
                                                            function change_emp_status(user_id, status) {
                                                                var result = confirm("Want to Activate/De-activate Employee?");
                                                                if (result) {
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "<?= base_url("/Human_resource/change_activity_status") ?>",
                                                                        dataType: "json",
                                                                        data: {user_id: user_id, status: status},
                                                                        success: function (result) {
                                                                            if (result.status === true) {
                                                                                if (result.msg === 'A') {
                                                                                    alert('HR de-activated successfully.');
                                                                                } else {
                                                                                    alert('HR activated successfully.');
                                                                                }
                                                                                // return;
                                                                                location.reload();
                                                                            } else if (result.status === false) {
                                                                                alert('something went wrong')
                                                                            } else {
                                                                                $('#message').html(result.error);
                                                                            }
                                                                        },
                                                                        error: function (result) {
                                                                            console.log(result);
                                                                            if (result.status === 500) {
                                                                                alert('Internal error: ' + result.responseText);
                                                                            } else {
                                                                                alert('Unexpected error.');
                                                                            }
                                                                        }
                                                                    });
                                                                } else
                                                                {

                                                                }
                                                            }
    </script>
