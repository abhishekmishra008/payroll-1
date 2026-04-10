<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$user_id = $this->session->userdata('login_session');
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
                            <span class="caption-subject font-red sbold uppercase">View Leave Approve/Deny</span>
                        </div>
                    </div>
                    <div class="portlet-body">

                        <div class="col-md-12">
                            <div class="row well-lg " id="table_well">
                                <table class="table table-striped table-bordered table-hover dtr-inline  dataTable no-footer"
                                       id="sample_2" role="grid" >
                                    <thead>
                                        <tr role="row">
                                            <th style="text-align:center" scope="col">Employee Name</th>
                                            <th style="text-align:center" scope="col">Leave Type</th>
                                            <th style="text-align:center" scope="col">Requested On</th>
                                            <th style="text-align:center" scope="col">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($record_fetch !== '') {
                                            foreach ($record_fetch as $row) {
                                                ?>
                                                <tr>
                                                    <td style="text-align:center" ><?php echo $row->user_name; ?><input type="hidden" id="emp_user_id1" name="emp_user_id1" value="<?php echo $row->user_id; ?>"></td>
                                                    <td  style="text-align:center" ><?php echo $row->leave_type; ?></td>
                                                    <td style="text-align: center"><?php
                                                        $originalDate = $row->leave_requested_on;
                                                        $newcompletionDate = date("d-m-Y", strtotime($originalDate));
                                                        echo $newcompletionDate;
                                                        ?>
                                                    </td>
                                                    <td style="text-align:center" >
                                                        <a class='btn btn-circle green btn-icon-only btn-default' data-toggle="modal" data-target="#view_leave_details"
                                                           data-desig_id="<?php echo $row->designation_id; ?>"
                                                           data-leave_type1="<?php echo $row->leave_type; ?>"
                                                           data-emp_user_id="<?php echo $row->user_id; ?>"
                                                           data-leave_id="<?php echo $row->leave_id; ?>">
                                                            <i class='fa fa-eye'></i>
                                                        </a>
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
            </div>
        </div>
        <?php $this->load->view('human_resource/footer'); ?>
    </div>

</div>


<div class="modal fade" id="view_leave_details" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Leave Details</h4>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <form id="edit_designation_form" name="edit_designation_form" method="post">
                        <input type="hidden" name="leave_id" id="leave_id" value="">
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $useridd; ?>">
                        <input type="hidden" name="emp_user_id" id="emp_user_id" value="">
                        <input type="hidden" name="leave_type1" id="leave_type1" value="">
                        <input type="hidden" name="desig_id" id="desig_id" value="">

                        <!--<div id="leave_data" name="leave_data"></div>-->
                        <div class="col-md-12">
                            <div class="col-md-8"></div>

                            <div class="col-md-2" style="float: right">

                                <button id="approve_all_btn" name="approve_all_btn" type="button" data-toggle="modal" data-target="#myModal1" class="btn sbold red btn-sm" data-dvalue="<?php echo $useridd; ?>">
                                    <i class="fa fa-check"></i> Deny All
                                </button>
                            </div>

                            <div class="col-md-2" style="float: right">

                                <button id="deny_all_btn" name="deny_all_btn" type="button" class="btn sbold blue btn-sm" onclick="approve_all('<?php echo $user_id; ?>');">
                                    <i class="fa fa-check"></i> Approve All
                                </button>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover dtr-inline  dataTable no-footer" id="sample_1" role="grid" >
                            <thead>
                            <th style="text-align:center">Leave Type</th>
                            <th style="text-align:center">Leave Date</th>
                            <th style="text-align:center">Status</th>
                            <th style="text-align:center">Action</th>
                            <th style="text-align:center">With Pay/Without Pay</th>
                            </thead>
                            <tbody id="leave_data" name="leave_data"></tbody>
                        </table>

                    </form>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>

    </div>
</div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">DENIED REASON</h4>
            </div>
            <div class="modal-body">
                <form action ="" method="POST" id="reason_id">
                    <input type="hidden" name="deny_id1" id="deny_id1" >
                    <textarea name="den_id1" id="den_id1" ></textarea>
                    <button type="button"  id= "due_date" onclick="deny_all()"  class="btn btn-primary" data-dismiss="modal"> ok</button>
                </form>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-     labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">DENIED REASON</h4>
            </div>
            <div class="modal-body">
                <form action ="" method="POST" id="reason_id">
                    <input type="hidden" name="deny_id" id="deny_id" >
                    <textarea name="den_id" id="den_id" ></textarea>
                    <button type="button"  id= "due_date" onclick="deny_leave()"  class="btn btn-primary" data-dismiss="modal"> ok</button>
                </form>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php $this->load->view('human_resource/footer'); ?>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>


<script>
                        $('#myModal1').on('show.bs.modal', function (e) {
                            var deny_id = $(e.relatedTarget).data('dvalue');
                            // console.log(deny_id);
                            document.getElementById('deny_id1').value = deny_id;

                            // var den_id=  document.getElementById('den_id').value ;
                            //alert(den_id);
                        });

                        $('#myModal').on('show.bs.modal', function (e) {
                            var deny_id = $(e.relatedTarget).data('myvalue');
                            document.getElementById('deny_id').value = deny_id;
                        });
                        $('#myModal').on('hidden.bs.modal', function () {
                            $(this).find('form').trigger('reset');

                        });
                        $('#myModal1').on('hidden.bs.modal', function () {
                            // $('#leave_conf_tbl').remove();
                            $(this).find('form').trigger('reset');
                            // location.reload();

                        });

                        $('#view_leave_details').on('show.bs.modal', function (e) {
                            var leaveid = $(e.relatedTarget).data('leave_id');
                            var leave_id = document.getElementById('leave_id').value = leaveid;
                            var leavetype1 = $(e.relatedTarget).data('leave_type1');
                            var leave_type1 = document.getElementById('leave_type1').value = leavetype1;
                            var empuser_id = $(e.relatedTarget).data('emp_user_id');
                            var emp_user_id = document.getElementById('emp_user_id').value = empuser_id;
                            var desigid = $(e.relatedTarget).data('desig_id');
                            var desig_id = document.getElementById('desig_id').value = desigid;
                            var ele = document.getElementById('leave_data');
                            $.ajax({
                                type: "post",
                                url: "<?= base_url("/Leave_management/get_emp_leave_data") ?>",
                                dataType: "json",
                                data: {leave_id: leave_id, emp_user_id: emp_user_id, leave_type1: leave_type1, desig_id: desig_id},
                                success: function (result) {
                                    ele.innerHTML = "";
                                    if (result['message'] === 'success') {
                                        var data = result.result_leave;
                                        var data1 = result.leave_aprv_validation;
                                        var leave_aprv_validation_multiple = result.leave_aprv_validation_multiple;


                                        for (i = 0; i < data.length; i++)
                                        {


                                            if (data[i]['status'] === '1') {
                                                var status = " <span class='label label-sm label-info'> Requested </span>";
                                            } else if (data[i]['status'] === '2') {
                                                var status = "<span class='label label-sm label-success'> Approved </span>";
                                            } else if (data[i]['status'] === '3') {
                                                var status = " <span class='label label-sm label-warning'> Denied </span>";
                                            } else {
                                                var status = "<span class='label label-sm label-danger'> Close </span>";
                                            }
                                            if (data[i]['leave_pay_type'] === '0') {
                                                var leave_pay_type = " <span class='label label-sm label-info'> With Pay </span>";
                                            } else {
                                                var leave_pay_type = "<span class='label label-sm label-danger'> Without Pay </span>";
                                            }
                                            if (data[i]['status'] === '1') {
                                                var approve = '<a class="btn btn-circle green btn-icon-only btn-default" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                                                var deny = '<a class="btn btn-circle red btn-icon-only btn-default" data-toggle="modal" title="Deny!" name="deny_leave" id="deny_leave" data-target="#myModal" data-myvalue="' + data[i]['id'] + '"><i class="fa fa-close"></i></a>';
                                            } else if (data[i]['status'] === '2') {
                                                var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                                                var deny = '<a class="btn btn-circle red btn-icon-only btn-default" data-toggle="modal"title="Deny!" name="deny_leave" id="deny_leave" data-target="#myModal" data-myvalue="' + data[i]['id'] + '"><i class="fa fa-close"></i></a>';
                                            } else if (data[i]['status'] === '3') {
                                                var approve = '<a class="btn btn-circle green btn-icon-only btn-default" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                                                var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close"></i></a>';
                                            } else {
                                                var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                                                var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close"></i></a>';
                                            }

                                            if (data1 === '1')
                                            {
                                                var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                                                var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" onclick="deny_leave(' + data[i]['id'] + ');"><i class="fa fa-close"></i></a>';
                                            }

                                            if (leave_aprv_validation_multiple === '1')
                                            {
                                                var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                                                var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" onclick="deny_leave(' + data[i]['id'] + ');"><i class="fa fa-close"></i></a>';
                                            }

                                            var date = data[i]['leave_date'];
                                            var d = new Date(date.split("/").reverse().join("-"));
                                            var dd = d.getDate();
                                            var mm = d.getMonth() + 1;
                                            var yy = d.getFullYear();
                                            var compdate_cust_sub = dd + "-" + mm + "-" + yy;

                                            // POPULATE SELECT ELEMENT WITH JSON.
                                            ele.innerHTML = ele.innerHTML +
                                                    '<td>' + data[i]['leave_type'] + '</td>' +
                                                    '<td>' + compdate_cust_sub + '</td>' +
                                                    '<td>' + status + '</td>' +
                                                    '<td>' + approve + deny + '</td>' +
                                                    '<td>' + leave_pay_type + '</td>';
                                        }
                                    } else {
                                    }
                                }
                            });
                        });



                        function approve_leave(id1)
                        {
                            var leave_id = id1;
                            var result = confirm("Want to Approve this Leave?");
                            if (result) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?= base_url("Leave_management/approve_emp_leave") ?>",
                                    dataType: "json",
                                    data: {leave_id: leave_id},
                                    success: function (result) {
                                        if (result.status === true) {
                                            alert('Leave approved successfully.');
                                            location.reload();
                                        } else {
                                            alert('Something went wrong.');
                                        }
                                    }

                                });
                            } else {
                                //        location.reload();
                            }
                        }

                        function deny_leave()
                        {
                            var leave_id = document.getElementById('deny_id').value;
                            //var result = confirm("Want to Deny this Leave?");
                            //if (result) {
                            var den_id = document.getElementById('den_id').value;
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url("Leave_management/deny_emp_leave") ?>",
                                dataType: "json",
                                data: {leave_id: leave_id, den_id: den_id},
                                success: function (result) {
                                    if (result.status === true) {
                                        alert('Leave denied successfully.');
                                        location.reload();
                                    } else {
                                        alert('Something went wrong.');
                                    }
                                }

                            });
                            // } else {
                            //        location.reload();
                            //  }
                        }

                        function approve_all(id)
                        {
                            var leave_id = document.getElementById('leave_id').value;
                            var user_id = id;
                            var result = confirm("Want to approve all leaves?");
                            if (result) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?= base_url("Leave_management/approve_all_leave") ?>",
                                    dataType: "json",
                                    data: {user_id: user_id, leave_id: leave_id},
                                    success: function (result) {
                                        if (result.status === true) {
                                            alert('Leaves approved successfully.');
                                            location.reload();
                                        } else {
                                            alert('Something went wrong.');
                                        }
                                    }

                                });
                            } else {
                                //        location.reload();
                            }
                        }

                        function deny_all()
                        {
                            var user_id = document.getElementById('deny_id1').value;
                            var leave_id = document.getElementById('leave_id').value;

                            var den_id = document.getElementById('den_id1').value;
                            //var result = confirm("Want to Deny All Leaves?");
                            //if (result) {
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url("Leave_management/deny_all_leave") ?>",
                                dataType: "json",
                                data: {user_id: user_id, leave_id: leave_id, den_id: den_id},
                                success: function (result) {
                                    if (result.status === true) {
                                        alert('Leaves denied successfully.');
                                        location.reload();
                                    } else {
                                        alert('Something went wrong.');
                                    }
                                }

                            });
                            // } else {
                            //        location.reload();
                            // }
                        }
</script>