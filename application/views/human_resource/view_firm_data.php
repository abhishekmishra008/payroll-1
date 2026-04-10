<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');
if (is_array($session_data)) {
    $data['session_data'] = $session_data;
    $email_id = ($session_data['user_id']);
    $user_type = $session_data['user_type'];
} else {
    $email_id = $this->session->userdata('login_session');
}
if ($email_id === "") {
    $email_id = $this->session->userdata('login_session');
}

$page_name = 'View Offices';
?>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                
            </ul>
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
        <div class="portlet light ">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="icon-settings font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase"><?php echo $page_name; ?></span>
                            </div>
                            <!--<?php echo $total_no_of_offices; ?>
                            <?php echo $no_of_offices_permitted; ?>-->

                            <div class="actions">

                                <?php
//                                if ($no_of_offices_permitted == $total_no_of_offices) {
//                                if ($no_of_offices_permitted == $total_no_of_offices) {
                                if ($user_type == 2) {
                                    ?>
                                    <div class="btn-group">
                                        <button  disabled="" class="btn sbold blue" style="display:none"><i class="fa fa-plus"></i> Add New
                                        </button>
                                    </div>
                                <?php } else { ?>
                                    <div class="btn-group">

                                        <a href="<?= base_url() ?>Hq_firm_form"><button  class="btn sbold blue"><i class="fa fa-plus"></i> Add Office
                                            </button></a>
                                    </div>
                                    <div class="btn-group">
                                        <a href="<?= base_url() ?>License_request" ><button  class="btn sbold green-meadow"><i class="fa fa-money"></i>License Request
                                            </button></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="dataTables_length" id="sample_1_length">                                    </div>
                                    </div>
                                </div>
                                <div class="">
                                    <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                        <thead>
                                            <tr>
                                                <!--<th scope="col"> Sr No</th>-->
                                                <th scope="col" style="text-align: center;">Office Name </th>
                                                <th scope="col" style="text-align: center;">Office <Br>Address</th>
                                                <th scope="col" style="text-align: center;">Office Email <br>Id </th>
                                                <th scope="col" style="text-align: center;"> Office Head <br>Name</th>
                                                <th scope="col" style="text-align: center;"> Office Incharge <Br>Email Id </th>
                                                <th scope="col" style="text-align: center;"> Office Employee<br>  Strength </th >
                                                <th scope="col" style="text-align: center;"> Customer Serves    </th>
                                                <th scope="col" style="text-align: center;">   Status     </th>
                                                <?php if ($user_type == 1) { ?> <th scope="col" style="text-align: center;">  Edit     </th><?php } ?>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result !== "") {
                                                $i = 1;
                                                foreach ($result as $row) {
                                                    $firm_id = $row->firm_id;
                                                    ?>
                                                    <tr>
            <!--                                                    <td>
                                                        <?php //echo $i++;   ?>
                                                        </td>-->
                                                        <td style="text-align: center;">
                                                            <?php echo $row->firm_name; ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo $row->firm_address; ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo $row->firm_email_id; ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo $row->boss_name; ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo $row->boss_email_id; ?>
                                                        </td>

                                                        <td style="text-align: center;">
                                                            <?php echo $row->firm_no_of_employee; ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php echo $row->firm_no_of_customers; ?>
                                                        </td>
                                                        
                                                        <td style="text-align: center;">
                                                            <?php if ($row->firm_activity_status == 'A') { ?>
                                                                <button type="button" class="btn btn green-meadow" onclick="change_status('<?php echo base64_encode($firm_id); ?>', 'A')">Active</button>
                                                            <?php } else { ?>
                                                                <button type="button" class="btn btn red" onclick="change_status('<?php echo base64_encode($firm_id); ?>', 'D')">De-active</button>
                                                            <?php }
                                                            ?>
                                                        </td>
                                                        <?php if ($user_type == 1) { ?>
                                                            <td style="text-align: center;"><a href="<?= base_url("hq_edit_firm_data/" . $row->firm_id); ?>" id="sample_new" href="" class="btn blue btn-circle btn-md" data-toggle="modal"  ><i class="fa fa-pencil"></i></a></td>
                                                        <?php } ?>




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

                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
										<?php $this->load->view('human_resource/footer'); ?>

                </div>

            </div>
        </div>
        <div class="modal fade" id="edit_firm" role="dialog" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Edit Branch Information</h4>
                    </div>
                    <form id="frm_edit_form" name="frm_edit_form" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="firm_id" name="firm_id">
                            <div id="firm_data" name="firm_data"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-info" id="update_firm" name="update_firm">Update Firm</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

<script>

                                                        function excel_submit() {
                                                            var formid = document.getElementById("Branchexcel");
                                                            alert(formid);
                                                            $.ajax({
                                                                type: "post",
                                                                url: "<?= base_url("ExcelController/branchread") ?>",
                                                                dataType: "json",
                                                                data: new FormData(formid),
                                                                processData: false,
                                                                contentType: false,
                                                                cache: false,
                                                                async: false,
//
                                                                success: function () {
                                                                    alert(vishu);
                                                                }

                                                            });
                                                        }






                                                        function buyFirmLicencee() {
                                                            alert('This feature comming soon');
                                                        }

                                                        function change_status(firm_id, status) {
                                                            var temp_status = '';
                                                            if (status == 'A') {
                                                                temp_status = 'De-activate';
                                                            } else {
                                                                temp_status = 'Active';
                                                            }
                                                            if (confirm('Are you sure you wants to ' + temp_status + ' Office? If yes click on OK else Cancel')) {
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?= base_url("/Hq_firm_form/change_activity_status") ?>",
                                                                    dataType: "json",
                                                                    data: {firm_id: firm_id, status: status},
                                                                    success: function (result) {
//                alert(result.status);
//                    return;
                                                                        // alert(result.error);
                                                                        if (result.status === true) {
                                                                            if (result.msg === 'A') {
                                                                                alert('Branch De-activated Successfully');
                                                                            } else {
                                                                                alert('Branch Activated Successfully');
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
                                                            } else {
                                                            }

                                                        }

                                                        $('#edit_firm').on('show.bs.modal', function (e) {
                                                            var firmid = $(e.relatedTarget).data('firm_id');
                                                            var firm_id = document.getElementById('firm_id').value = firmid;
                                                            var ele2 = document.getElementById('firm_data');
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("Hq_firm_form/fetch_firm_data") ?>",
                                                                dataType: "json",
                                                                data: {firm_id: firm_id},
                                                                success: function (result) {
                                                                    if (result.message === 'success') {
                                                                        ele2.innerHTML = '';
                                                                        var data = result.result_firm_data;
                                                                        var data1 = result.result_firm_data1;
//                        alert(data.length);
                                                                        for (i = 0; i < data.length; i++)
                                                                        {

                                                                            ele2.innerHTML = ele2.innerHTML +
                                                                                    '<div class="form-group">' +
                                                                                    '<label>Branch Name:</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-home"></i>' +
                                                                                    '</span>' +
                                                                                    '<input type="text" id="edit_firm_name"  class="form-control" name="edit_firm_name" value="' + data[i]['firm_name'] + '"></div></div>' +
                                                                                    ' <div class="form-group">' +
                                                                                    '<label>Branch Address:</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-globe"></i>' +
                                                                                    '</span>' +
                                                                                    '<textarea type="text" id="edit_firm_address"  class="form-control" name="edit_firm_address">' + data[i]['firm_address'] + '</textarea></div></div>' +
                                                                                    ' <div class="form-group">' +
                                                                                    '<label>Branch Head Name:</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-user"></i>' +
                                                                                    '</span>' +
                                                                                    '<input type="text" id="edit_boss_name"  class="form-control" name="edit_boss_name" value="' + data[i]['boss_name'] + '"></div><div>' +
                                                                                    '<div class="form-group">' +
                                                                                    '<label>Branch Head Mobile Number:</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-phone"></i>' +
                                                                                    '</span>' +
                                                                                    '<input type="number" id="edit_boss_mobile_no"  class="form-control" name="edit_boss_mobile_no" value="' + data[i]['boss_mobile_no'] + '"></div></div>' +
                                                                                    '<div class="form-group">' +
                                                                                    '<label>Branch No Of Employee</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-user"></i>' +
                                                                                    '</span>' +
                                                                                    '<input type="number" id="edit_firm_no_of_employee"  class="form-control" name="edit_firm_no_of_employee" value="' + data[i]['firm_no_of_employee'] + '"></div></div>' +
                                                                                    '<div class="form-group">' +
                                                                                    '<label>No Of Customers:</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-users"></i>' +
                                                                                    '</span>' +
                                                                                    '<input type="number" id="edit_firm_no_of_customers"  class="form-control" name="edit_firm_no_of_customers" value="' + data[i]['firm_no_of_customers'] + '"></div></div>' +
                                                                                    '<div class="form-group">' +
                                                                                    '<label>Country:</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-flag"></i>' +
                                                                                    '</span>' +
                                                                                    '<input type="text" id="edit_city"  class="form-control" name="edit_city" value="' + data1[i]['city'] + '"></div></div>' +
                                                                                    '<div class="form-group">' +
                                                                                    '<label>State:</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-globe"></i>' +
                                                                                    '</span>' +
                                                                                    '<input type="text" id="edit_state"  class="form-control" name="edit_state" value="' + data1[i]['state'] + '"></div></div>' +
                                                                                    '<div class="form-group">' +
                                                                                    '<label>City:</label>' +
                                                                                    '<div class="input-group">' +
                                                                                    '<span class="input-group-addon">' +
                                                                                    '<i class="fa fa-home"></i>' +
                                                                                    '</span>' +
                                                                                    '<input type="text" id="edit_country"  class="form-control" name="edit_country" value="' + data1[i]['country'] + '"></div></div>' +
                                                                                    '<div class="form-group">' +
                                                                                    ' <label for="">' +
                                                                                    'Is Employee' +
                                                                                    '</label>' +
                                                                                    '<div class="m-radio-inline">' +
                                                                                    ' <label class="m-radio">' +
                                                                                    '<input type="radio" name="edit_is_emp" id="edit_is_emp" checked="" value="1">' +
                                                                                    'Yes' +
                                                                                    '<span></span>' +
                                                                                    '</label>' +
                                                                                    '<label class="m-radio">' +
                                                                                    '<input type="radio" name="edit_is_emp" id="edit_is_emp" value="0">' +
                                                                                    'No' +
                                                                                    ' <span></span>' +
                                                                                    '</label>' +
                                                                                    '</div> ' +
                                                                                    '<label for="">' +
                                                                                    'Due date Creation permitted' +
                                                                                    ' </label>' +
                                                                                    '<div class="m-radio-inline">' +
                                                                                    '<label class="m-radio m-radio--solid">' +
                                                                                    '<input type="radio" name="edit_due_date_creation_permitted" id="edit_due_date_creation_permitted" checked="" value="1">' +
                                                                                    'Yes' +
                                                                                    '<span></span>' +
                                                                                    '</label>' +
                                                                                    '<label class="m-radio m-radio--solid">' +
                                                                                    '<input type="radio" name="edit_due_date_creation_permitted" id="edit_due_date_creation_permitted" value="0">' +
                                                                                    'No' +
                                                                                    '<span></span>' +
                                                                                    '</label>' +
                                                                                    '</div>' +
                                                                                    '<label for="">' +
                                                                                    'Task Creation permitted' +
                                                                                    '</label>' +
                                                                                    '<div class="m-radio-inline">' +
                                                                                    '<label class="m-radio m-radio--solid">' +
                                                                                    '<input type="radio" name="edit_task_creation_permitted" id="edit_task_creation_permitted" checked="" value="1">' +
                                                                                    ' Yes' +
                                                                                    '<span></span>' +
                                                                                    '</label>' +
                                                                                    ' <label class="m-radio m-radio--solid">' +
                                                                                    '<input type="radio" name="edit_task_creation_permitted" id="edit_task_creation_permitted" value="0">' +
                                                                                    'No' +
                                                                                    '<span></span>' +
                                                                                    '</label>' +
                                                                                    '</div>' +
                                                                                    '</div>';
                                                                        }
                                                                        //                            ele2.innerHTML = '';
                                                                    } else {

                                                                    }
                                                                }
                                                            });
                                                        });
                                                        $("#update_firm").click(function () {
                                                            $.ajax({
                                                                type: "post",
                                                                url: "<?= base_url("Hq_firm_form/update_firm_data") ?>",
                                                                dataType: "json",
                                                                data: $("#frm_edit_form").serialize(),
                                                                success: function (result) {
                                                                    if (result.status === true) {
                                                                        alert('Branch updated successfully');
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
                                                        });


</script>