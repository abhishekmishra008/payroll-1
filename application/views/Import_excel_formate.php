<?php
// $this->load->view('admin/header');
$this->load->view('hq_admin/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username = $this->session->userdata('login_session');
?>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                </li>
            </ul>
            <div class="page-toolbar">

            </div>
        </div>
        <h1 class="page-title">
        </h1>

        <button data-target="#service_offering_import_modal" data-toggle="modal" class="btn sbold blue">import</button>
        <button data-target="#task_subtask_import_modal" data-toggle="modal" class="btn sbold blue">Task SubTas import</button>
        <button data-target="#employee_import_modal" data-toggle="modal" class="btn sbold blue">Employee import</button>
        <button data-target="#branch_import_modal" data-toggle="modal" class="btn sbold blue">Branch import</button>
        <button data-target="#designation_import_modal" data-toggle="modal" class="btn sbold blue">Designation import</button>

        <div class="modal fade " id="employee_import_modal" role="dialog">
            <div class="modal-dialog modal-full">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Download Employee Excel Formate</h4>
                        <a href="<?= base_url("employee_excel_formate") ?>" class="btn btn-link">Download Formate</a>
                    </div>
                    <div class="modal-body">
                        <div class="row JustifyCenter">
                            <form name="excel_form_upload" id="employee_excel_form_upload">
                                <div class="col-md-8">
                                    <input type="file"  id="user_id" name="excel_file" class="form-control">
                                    <input type="hidden"  id="request_btn" name="request_btn" value="0" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input class="btn btn-primary" type="submit"  value="Upload" />
                                    <input class="btn btn-primary" type="submit"  value="Confirm To Insert" id="confirm_insert" disabled>
                                </div>
                            </form>
                        </div>
                        <div class="form-group">
                            <label style="font-weight:bold;">Check Excel Data:</label>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="employee_excel_table" role="grid" aria-describedby="sample_1_info">
                                    <tr>
                                        <td>Branch Name</td>
                                        <td>Name</td>
                                        <td>Mobile</td>
                                        <td>State</td>
                                        <td>City</td>
                                        <td>Email</td>
                                        <td>Address</td>
                                        <td>Country</td>
                                        <td>Partner</td>
                                        <td>Designation</td>
                                        <td>Senier Employee</td>
                                        <td>Work On Service</td>
                                        <td>Date Of Joining</td>
                                        <td>Password</td>
                                        <td>Probation Period Start Date</td>
                                        <td>Probation Period End Date</td>
                                        <td>Training Period Start Date</td>
                                        <td>Training Period End Date</td>
                                        <td>Task Creation Permission</td>
                                        <td>Task Assignment Creation</td>
                                        <td>Due Date Creation</td>
                                        <td>Due Date Task Assignment Creation</td>
                                        <td>Services Creation</td>
                                        <td>Generate Enquiry</td>
                                        <td>Permission To Approve Leave</td>
                                    </tr>
                                    <tbody id="employee_excel_body"></tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade " id="branch_import_modal" role="dialog">
            <div class="modal-dialog modal-full">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Download Branch Excel Formate</h4>
                        <a href="<?= base_url("employee_excel_formate") ?>" class="btn btn-link">Download Formate</a>
                    </div>
                    <div class="modal-body">
                        <div class="row JustifyCenter">
                            <form name="excel_form_upload" id="branch_excel_form_upload">
                                <div class="col-md-8">
                                    <input type="file"  id="user_id" name="excel_file" class="form-control">
                                    <input type="hidden"  id="request_btn" name="request_btn" value="0" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input class="btn btn-primary" type="submit"  value="Upload" />
                                    <input class="btn btn-primary" type="submit"  value="Confirm To Insert" id="confirm_insert" disabled>
                                </div>
                            </form>
                        </div>
                        <div class="form-group">
                            <label style="font-weight:bold;">Check Excel Data:</label>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="branch_excel_table" role="grid" aria-describedby="sample_1_info">
                                    <tr>
                                        <td>Branch Name</td>
                                        <td>Office Email Id</td>
                                        <td>Office Address</td>
                                        <td>Office Head Name</td>
                                        <td>Office Head Mobile Number</td>
                                        <td>Office Head Email Id</td>
                                        <td>Employee Strength</td>
                                        <td>No Of Customers</td>
                                        <td>Country</td>
                                        <td>State</td>
                                        <td>City</td>
                                        <td>Office Activity Status(A/D)</td>
                                        <td>Working as an Employee also(Y/N)</td>
                                        <td>Permission to Create Due Date(Y/N)</td>
                                        <td>Permission to Create Due Date Task(Y/N)</td>
                                        <td>Permission to Create Project Assignment(Y/N)</td>
                                        <td>Permission to Create Project Assignment Task(Y/N)</td>
                                        <td>Permission to Approve Leave (Y/N)</td>
                                        <td>Permission to Create Services(Y/N)</td>
                                        <td>Permission to Generate Enquiry(Y/N)</td>
                                        <td>Weekly Off </td>
                                        <td>Joining Date</td>
                                        <td>Yearly Leaves Permitted</td>
                                        <td>Monthly Leaves Permitted</td>
                                        <td>Yearly Leaves Type</td>
                                        <td>Apply Leave From</td>
                                        <td>Leave Carry Forward</td>
                                        <td>Sandwich Leave Applicability (Y/N)</td>
                                        <td>Probation Period Start Date</td>
                                        <td>Training Period Start Date</td>
                                        <td>Training Period End Date</td>
                                        <td>Types of Leaves</td>
                                    </tr>
                                    <tbody id="branch_excel_body"></tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade " id="service_offering_import_modal" role="dialog">
            <div class="modal-dialog modal-full">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Download Employee Excel Formate</h4>
                        <a href="<?= base_url("service_offering_excel_formate") ?>" class="btn btn-link">Download Formate</a>
                    </div>
                    <div class="modal-body">
                        <div class="row JustifyCenter">
                            <form name="excel_form_upload" id="excel_form_upload">
                                <div class="col-md-8">
                                    <input type="file"  id="user_id" name="excel_file" class="form-control">
                                    <input type="hidden"  id="request_btn" name="request_btn" value="0" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input class="btn btn-primary" type="submit"  value="Upload" />
                                    <input class="btn btn-primary" type="submit"  value="Confirm To Insert" id="confirm_insert" disabled>
                                </div>
                            </form>
                        </div>
                        <div class="form-group">
                            <label style="font-weight:bold;">Check Excel Data:</label>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="service_offering_excel_table" role="grid" aria-describedby="sample_1_info">
                                    <thead>
                                        <tr role="row">
                                            <th scope="col">Branch Name</th>
                                            <th scope="col">Service Name</th>
                                            <th scope="col">Offering Name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="service_offering_excel_body"></tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade " id="task_subtask_import_modal" role="dialog">
            <div class="modal-dialog modal-full">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Download Task SubTask Checklist Excel Formate</h4>
                        <a href="<?= base_url("task_sub_task_excel_formate") ?>" class="btn btn-link">Download Formate</a>
                    </div>
                    <div class="modal-body">
                        <div class="row JustifyCenter">
                            <form name="excel_form_upload1" id="excel_task_sub_task_form_upload">
                                <div class="col-md-8">
                                    <input type="file"  id="user_id" name="excel_file" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <button class=" btn btn-primary" name="request_btn" value="show">Upload</button>
                                    <button class=" btn btn-primary" name="request_btn" value="insert" id="confirm_insert" disabled>Confirm To Insert</button>
                                </div>
                            </form>

                        </div>
                        <div class="form-group">
                            <label style="font-weight:bold;">Check Excel Data:</label>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover  dtr-inline dt-responsive dataTable no-footer" id="task_subtask_excel_table" role="grid" aria-describedby="sample_1_info">
                                    <thead>
                                        <tr role="row">
                                            <th scope="col">Branch Name</th>
                                            <th scope="col">Task Name</th>
                                            <th scope="col">Sub Task Name</th>
                                            <th scope="col">Sub Sub-Task Name</th>
                                            <th scope="col">Check List Name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="task_subtask_excel_body"></tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade " id="designation_import_modal" role="dialog">
            <div class="modal-dialog modal-full">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Download Designation Excel Formate</h4>
                        <a href="<?= base_url("designation_excel_formate") ?>" class="btn btn-link">Download Formate</a>
                    </div>
                    <div class="modal-body">
                        <div class="row JustifyCenter">
                            <form name="excel_form_upload" id="designation_excel_form_upload">
                                <div class="col-md-8">
                                    <input type="file"  id="user_id" name="excel_file" class="form-control">
                                    <input type="hidden"  id="request_btn" name="request_btn" value="0" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input class="btn btn-primary" type="submit"  value="Upload" />
                                    <input class="btn btn-primary" type="submit"  value="Confirm To Insert" id="confirm_insert" disabled>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer'); ?>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script>
    $('#excel_form_upload').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?= base_url("upload_service_offering") ?>",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                var json = JSON.parse(result)
//                console.log(result);
                if (json["data_code"] === 200) {
                    $('#service_offering_excel_body').empty();
                    $('#service_offering_excel_body').append(json['data']);
                    $('#service_offering_excel_table').DataTable();
                    $('#request_btn').val(1);
                    $('#confirm_insert').attr('disabled', false);
                }
                if (json["data"] === 300) {
                    alert(json["error"]);
                }
            },
            error: function (result) {
                console.log(result);
            }
        });
    });

    $('#employee_excel_form_upload').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?= base_url("upload_employee") ?>",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                var json = JSON.parse(result)
                console.log(json);
                if (json["data_code"] === 200) {
                    $('#employee_excel_body').empty();
                    $('#employee_excel_body').append(json['data']);
                    $('#employee_excel_table').DataTable();
                    $('#request_btn').val(1);
                    $('#confirm_insert').attr('disabled', false);
                }
                if (json["data"] === 300) {
                    alert(json["error"]);
                }
            },
            error: function (result) {
                console.log(result);
            }
        });
    });

    $('#designation_excel_form_upload').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?= base_url("upload_designation") ?>",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                var json = JSON.parse(result)
                console.log(json);
                if (json.data_code === 200) {
                    alert("Success to Upload");
                }
                if (json.data === 300) {
                    alert(json["error"]);
                }
            },
            error: function (result) {
                console.log(result);
            }
        });
    });

    $('#branch_excel_form_upload').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?= base_url("upload_branch") ?>",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                var json = JSON.parse(result)
                console.log(json);
                if (json["data_code"] === 200) {
                    $('#branch_excel_body').empty();
                    $('#branch_excel_body').append(json.data);
                    $('#branch_excel_table').DataTable();
                    $('#request_btn').val(1);
                    $('#confirm_insert').attr('disabled', false);
                }
                if (json["data"] === 300) {
                    alert(json["error"]);
                }
            },
            error: function (result) {
                console.log(result);
            }
        });
    });

//    $('#excel_task_sub_task_form_upload').on('submit', function (event) {
//        event.preventDefault();
//        $.ajax({
//            type: "POST",
//            url: "<?= base_url("upload_task_sub_task") ?>",
//            data: new FormData(this),
//            contentType: false,
//            cache: false,
//            processData: false,
//            success: function (result) {
////                var json = JSON.parse(result)
//                console.log(result);
////                if (json["data"] !== "") {
////                    $('#task_subtask_excel_body').empty();
////                    $('#task_subtask_excel_body').append(json['data']);
////                    $('#task_subtask_excel_table').DataTable();
////                }
////                if (json["data"] === "") {
////                    alert(json["error"]);
////                }
//            },
//            error: function (result) {
//                console.log(result);
//            }
//        });
//    });



</script>