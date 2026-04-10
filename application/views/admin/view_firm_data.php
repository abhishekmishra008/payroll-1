<?php
$this->load->view('admin/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
    //take them back to signin
    redirect(base_url());
}
$user_id = $this->session->userdata('login_session');
?>
<link href="<?php echo base_url() . "assets/"; ?>global/css/mystyle1.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
		
				
					
			   
		  
		 
		
                    Admin
					
		

	  
                    <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                </li>
            </ul>
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'show_firm') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>show_firm">Home</a>
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>


                </ul>
            </div>
        </div>
        <div class="col-md-12 ">
            <div class="row wrapper-shadow" id="HTMLtoPDF">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-form">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="icon-settings font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase">HR Admin List</span>
                            </div>

                            <div class="actions">

                                <div class="btn-group">
                                    <a href="<?= base_url() ?>firm_form">
                                        <button class="btn btn-info btn-simplebtn blue-hoki btn-outline sbold uppercase popovers" data-container="body" data-placement="left" data-trigger="hover" data-content="Add New"style="padding: 4px 10px; font-size: 13px; line-height: 1.5;">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </a>
                                </div>

                            </div>
                        </div>

                        <div class="portlet-body">

                            <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">

                                <div class="">
                                    <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                        <thead>
                                            <tr>
                                                <!--<th scope="col" > Sr No</th>-->
                                                <th>HR-Admin Name </th>
                                                <th> Address</th>
                                                <th>Email Id </th>
                                                <th>Contact No</th>

                                                <th>Status </th>
                                                <th>Edit </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result !== "" && $result1 !== "") {

                                                for ($i = 0; $i < sizeof($result); $i++) {

                                                    $branch_count = $result1[$i]['branch_count'];
                                                    $employee_count = $result1[$i]['employee_count'];

                                                    $firm_id = $result[$i]->firm_id;
                                                    $boss_id = $result[$i]->boss_id;
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $result[$i]->firm_name; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $result[$i]->firm_address; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $result[$i]->firm_email_id; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $result[$i]->boss_mobile_no; ?>
                                                        </td>

                                                        <td>
                                                            <?php if ($result[$i]->firm_activity_status == 'A') { ?>
                                                                <button type="button" class="btn btn green-meadow" onclick="change_status('<?php echo base64_encode($firm_id); ?>', 'A')">Active</button>
                                                            <?php } else { ?>
                                                                <button type="button" class="btn btn red" onclick="change_status('<?php echo base64_encode($firm_id); ?>', 'D')">De-active</button>
                                                            <?php }
                                                            ?>
                                                        </td>


                                                        <td>
                                                            <a href="<?= base_url("sa_edit_firm_data/" . $result[$i]->firm_id); ?>" id="sample_new" class="btn btn-link btn-icon-only" title="Edit" ><i class="fa fa-pencil" style="font-size:22px;"></i></a>
                                                        </td>

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
                </div>

                <div class="col-md-12" id="branch_details">
                </div>


                <div class="col-md-12" id="employee_details">
                </div>
            </div>

            <!--View Branch sub task-->

        </div>
        <?php $this->load->view('admin/footer'); ?>
    </div>
</div>




<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

<!-- these js files are used for making PDF -->
<!--	<script src="<?php echo base_url() . "assets/"; ?>js/jspdf.js"></script>
        <script src="<?php echo base_url() . "assets/"; ?>js/jquery-2.1.3.js"></script>
<script src="<?php echo base_url() . "assets/"; ?>js/pdfFromHTML.js"></script>-->




<script>


                                                        function get_firm_id(firm_id_string) {

                                                            $.ajax({
                                                                type: "post",
                                                                url: "<?= base_url("Nas/set_session_data") ?>",
                                                                dataType: "json",
                                                                data: {firm_id_string: firm_id_string},
                                                                success: function (result) {
                                                                    if (result.status === true) {


                                                                    } else {

                                                                    }
                                                                },
                                                                error: function (result) {
                                                                    console.log(result);
                                                                    if (result.status === 500) {
                                                                        alert('Internal error: ' + result.responseText);
                                                                    } else {

                                                                    }
                                                                }
                                                            });
                                                        }
                                                        $(document).ready(function () {
                                                            $('#example1').DataTable({
                                                                dom: 'Bfrtip',
                                                                buttons: [
                                                                    'copy', 'csv', 'excel', 'pdf', 'print'
                                                                ]
                                                            });
                                                        });

                                                        //                                                                Branch count Details
                                                        function total_office_count(id) {
                                                            var boss_id = id;
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/get_office_count") ?>",
                                                                dataType: "json",
                                                                data: {boss_id: boss_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#branch_details').html("");
                                                                        $('#branch_details').append(result.suceess);
                                                                        //  $("#example1").dataTable();
                                                                        $('#example1').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('branch_details');
                                                                    } else {
                                                                        $('#branch_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }

                                                        //                                                                Customer Count Details
                                                        function total_customer_count(id) {
                                                            var boss_id = id;
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/get_customer_count") ?>",
                                                                dataType: "json",
                                                                data: {boss_id: boss_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#customer_details').html("");
                                                                        $('#customer_details').append(result.suceess);
                                                                        //                                                                                $("#example3").dataTable();
                                                                        $('#example3').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('customer_details');
                                                                    } else {
                                                                        $('#customer_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }
                                                        function scrolling(divname) {
                                                            $('html,body').animate({
                                                                scrollTop: $("#" + divname).offset().top},
                                                                    'slow');
                                                        }

                                                        //                                                                total branch services
                                                        function total_branch_service_detail(id) {
                                                            var firm_id = id;
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/firmServiceDetail") ?>",
                                                                dataType: "json",
                                                                data: {firm_id: firm_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#service_type_details').html("");
                                                                        $('#service_type_details').append(result.suceess);
                                                                        //                                                                                $("#example4").dataTable();
                                                                        $('#example4').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('service_type_details');
                                                                    } else {
                                                                        $('#service_type_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }

                                                        //show services in details
                                                        function show_services(serv_id, firm_id) {
                                                            var firm_id = firm_id;
                                                            var serv_id = serv_id;
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/showServices") ?>",
                                                                dataType: "json",
                                                                data: {firm_id: firm_id, serv_id: serv_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#service_details').html("");
                                                                        $('#service_details').append(result.suceess);
                                                                        //                                                                                $("#example5").dataTable();
                                                                        $('#example5').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('service_details');
                                                                    } else {
                                                                        $('#service_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }


                                                        //show enquiry customer list
                                                        function show_enquiry_customer_details(serv_id, count, firm_id) {
                                                            if (count === '0') {
                                                                alert('No Enquiry');
                                                            } else {
                                                                $('#modal_enquiry_customer').modal('show');
                                                                var fetch_firm_id = firm_id;
                                                                //alert(fetch_firm_id);
                                                                $.ajax({
                                                                    type: "post",
                                                                    url: "<?= base_url("Super_admin/enquiry_customer_details") ?>",
                                                                    dataType: "json",
                                                                    data: {serv_id: serv_id, fetch_firm_id: fetch_firm_id},
                                                                    success: function (result) {
                                                                        console.log(result.suceess);
                                                                        if (result !== '') {
                                                                            //console.log(obj.suceess);
                                                                            $('#show_enquiry_table').html("");
                                                                            $('#show_enquiry_table').append(result.suceess);
                                                                            //                                                                                    $("#example6").dataTable();
                                                                            $('#example6').DataTable({
                                                                                dom: 'Bfrtip',
                                                                                "buttons": [
                                                                                    {
                                                                                        extend: 'collection',
                                                                                        text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                        buttons: [
                                                                                            'copy',
                                                                                            'excel',
                                                                                            'csv',
                                                                                            'pdf',
                                                                                            'print'
                                                                                        ]
                                                                                    }
                                                                                ]
                                                                            });
                                                                        } else {
                                                                            $('#show_enquiry_table').html("");
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }

                                                        //show all branch customers
                                                        function total_branch_customer_detail(id) {
                                                            var firm_id = id
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/get_branchbranch_customers") ?>",
                                                                dataType: "json",
                                                                data: {firm_id: firm_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#customer_details').html("");
                                                                        $('#customer_details').append(result.suceess);
                                                                        //                                                                                $("#example3").dataTable();
                                                                        $('#example3').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('customer_details');
                                                                    } else {
                                                                        $('#customer_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }
                                                        //employee Count Details
                                                        function total_employee_count(id) {
                                                            var boss_id = id;
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/get_employee_count") ?>",
                                                                dataType: "json",
                                                                data: {boss_id: boss_id},
                                                                success: function (result) {
                                                                    //console.log(result);
                                                                    if (result !== '') {
                                                                        $('#employee_details').html("");
                                                                        $('#employee_details').append(result.suceess);
                                                                        $('#example2').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('employee_details');
                                                                    } else {
                                                                        $('#employee_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }
                                                        //                                                                show all branchwise due date list
                                                        function total_branch_due_date_detail(id, count) {
                                                            if (count === '0') {
                                                                alert('No Due Date Task');
                                                            } else {
                                                                $('#modal_branch_due_date_task').modal('show');
                                                                var firm_id = id
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?= base_url("/Super_admin/get_branch_due_date_task") ?>",
                                                                    dataType: "json",
                                                                    data: {firm_id: firm_id},
                                                                    success: function (result) {
                                                                        // console.log(result);
                                                                        if (result !== '') {
                                                                            $('#show_branch_due_date_task_table').html("");
                                                                            $('#show_branch_due_date_task_table').append(result.suceess);
                                                                            //                                                                                    $("#example8").dataTable();
                                                                            $('#example8').DataTable({
                                                                                dom: 'Bfrtip',
                                                                                "buttons": [
                                                                                    {
                                                                                        extend: 'collection',
                                                                                        text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                        buttons: [
                                                                                            'copy',
                                                                                            'excel',
                                                                                            'csv',
                                                                                            'pdf',
                                                                                            'print'
                                                                                        ]
                                                                                    }
                                                                                ]
                                                                            });
                                                                        } else {
                                                                            $('#show_branch_due_date_task_table').html("");
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }
                                                        function customer_due_date_task(duedate_id, id, count) {
                                                            if (count === '0') {
                                                                alert('No Due Date Task');
                                                            } else {
                                                                $('#modal_dudatetask_detail').modal('show');
                                                                var firm_id = id;
                                                                var due_date = duedate_id;
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?= base_url("/Super_admin/get_duedate_taskname") ?>",
                                                                    dataType: "json",
                                                                    data: {firm_id: firm_id, due_date: due_date},
                                                                    success: function (result) {
                                                                        console.log(result);
                                                                        if (result !== '') {
                                                                            $('#show_due_date_taskname_table').html("");
                                                                            $('#show_due_date_taskname_table').append(result.suceess);
                                                                            //                                                                                    $("#example8").dataTable();
                                                                            $('#example9').DataTable({
                                                                                dom: 'Bfrtip',
                                                                                "buttons": [
                                                                                    {
                                                                                        extend: 'collection',
                                                                                        text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                        buttons: [
                                                                                            'copy',
                                                                                            'excel',
                                                                                            'csv',
                                                                                            'pdf',
                                                                                            'print'
                                                                                        ]
                                                                                    }
                                                                                ]
                                                                            });
                                                                        } else {
                                                                            $('#show_due_date_taskname_table').html("");
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }
                                                        //                                                                show branchwise all task assignment list
                                                        function total_branch_task_assign_detail(id, count) {
                                                            if (count === '0') {
                                                                alert('No Task Assignment');
                                                            } else {
                                                                $('#modal_branch_task_assign').modal('show');
                                                                var firm_id = id
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?= base_url("/Super_admin/get_branch_task_assignment") ?>",
                                                                    dataType: "json",
                                                                    data: {firm_id: firm_id},
                                                                    success: function (result) {
                                                                        // console.log(result);
                                                                        if (result !== '') {
                                                                            $('#show_branch_task_assignment_table').html("");
                                                                            $('#show_branch_task_assignment_table').append(result.suceess);
                                                                            //                                                                                    $("#example9").dataTable();
                                                                            $('#example9').DataTable({
                                                                                dom: 'Bfrtip',
                                                                                "buttons": [
                                                                                    {
                                                                                        extend: 'collection',
                                                                                        text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                        buttons: [
                                                                                            'copy',
                                                                                            'excel',
                                                                                            'csv',
                                                                                            'pdf',
                                                                                            'print'
                                                                                        ]
                                                                                    }
                                                                                ]
                                                                            });
                                                                        } else {
                                                                            $('#show_branch_task_assignment_table').html("");
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }

                                                        //                                                                show all branch employee details list
                                                        function total_branch_employee_detail(id) {
                                                            var firm_id = id
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/get_branch_employee") ?>",
                                                                dataType: "json",
                                                                data: {firm_id: firm_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#employee_details').html("");
                                                                        $('#employee_details').append(result.suceess);
                                                                        //                                                                                $("#example2").dataTable();
                                                                        $('#example2').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('employee_details');
                                                                    } else {
                                                                        $('#employee_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }

                                                        //                                                                show branchwise survey details
                                                        function total_branch_survey_detail(id) {
                                                            var firm_id = id
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/total_branch_survey_detail") ?>",
                                                                dataType: "json",
                                                                data: {firm_id: firm_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#survey_details').html("");
                                                                        $('#survey_details').append(result.suceess);
                                                                        //                                                                                $("#example16").dataTable();
                                                                        $('#example6').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('survey_details');
                                                                    } else {
                                                                        $('#survey_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }

                                                        //                                                                All branch customer due dates
                                                        function all_branch_customer_due_date(cust_id, firm_id) {
                                                            var customer_id = cust_id;
                                                            var firm_id = firm_id;
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/all_branch_customer_due_date") ?>",
                                                                dataType: "json",
                                                                data: {customer_id: customer_id, firm_id: firm_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#customer_due_date_details').html("");
                                                                        $('#customer_due_date_details').append(result.suceess);
                                                                        //                                                                                $("#example10").dataTable();
                                                                        $('#example10').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                        scrolling('customer_due_date_details');
                                                                    } else {
                                                                        $('#customer_due_date_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }


                                                        function all_branch_customer_task_assignment(cust_id, firm_id) {
                                                            var customer_id = cust_id;
                                                            var firm_id = firm_id;
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("/Super_admin/all_branch_customer_task_assignment") ?>",
                                                                dataType: "json",
                                                                data: {customer_id: customer_id, firm_id: firm_id},
                                                                success: function (result) {
                                                                    console.log(result);
                                                                    if (result !== '') {
                                                                        $('#customer_task_assignment_details').html("");
                                                                        $('#customer_task_assignment_details').append(result.suceess);
                                                                        //                                                                                $("#example11").dataTable();
                                                                        $('#example11').DataTable({
                                                                            dom: 'Bfrtip',
                                                                            "buttons": [
                                                                                {
                                                                                    extend: 'collection',
                                                                                    text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                    buttons: [
                                                                                        'copy',
                                                                                        'excel',
                                                                                        'csv',
                                                                                        'pdf',
                                                                                        'print'
                                                                                    ]
                                                                                }
                                                                            ]
                                                                        });
                                                                    } else {
                                                                        $('#customer_task_assignment_details').html("");
                                                                    }
                                                                }
                                                            });
                                                        }

                                                        function view_file1(type, emp_id, firm_id) {
                                                            var x = document.getElementById("public");
                                                            var y = document.getElementById("private");
                                                            var employee_id = emp_id;
                                                            var firm_id = firm_id;
                                                            var type_id = type;
                                                            if (type_id === "public") {
                                                                x.style.display = "block";
                                                                y.style.display = "none";
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?= base_url("/Super_admin/employee_due_date_task") ?>",
                                                                    dataType: "json",
                                                                    data: {type_id: type_id, employee_id: employee_id, firm_id: firm_id},
                                                                    success: function (result) {
                                                                        console.log(result);
                                                                        if (result !== '') {
                                                                            $('#modal_employee_due_date').modal('show');
                                                                            $('#public').html("");
                                                                            $('#public').append(result.suceess);
                                                                            $('#example12').DataTable({
                                                                                dom: 'Bfrtip',
                                                                                "buttons": [
                                                                                    {
                                                                                        extend: 'collection',
                                                                                        text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                        buttons: [
                                                                                            'copy',
                                                                                            'excel',
                                                                                            'csv',
                                                                                            'pdf',
                                                                                            'print'
                                                                                        ]
                                                                                    }
                                                                                ]
                                                                            });
                                                                        } else {
                                                                            alert('No public Due Date found');
                                                                            $('#public').html("");
                                                                        }
                                                                    }
                                                                });
                                                            } else if (type_id === "private") {
                                                                x.style.display = "none";
                                                                y.style.display = "block";
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?= base_url("/Super_admin/employee_due_date_task") ?>",
                                                                    dataType: "json",
                                                                    data: {type_id: type_id, employee_id: employee_id, firm_id: firm_id},
                                                                    success: function (result) {
                                                                        console.log(result);
                                                                        if (result !== '') {
                                                                            $('#modal_employee_due_date').modal('show');
                                                                            $('#private').html("");
                                                                            $('#private').append(result.suceess);
                                                                            //                                                                                    $("#example13").dataTable();
                                                                            $('#example13').DataTable({
                                                                                dom: 'Bfrtip',
                                                                                "buttons": [
                                                                                    {
                                                                                        extend: 'collection',
                                                                                        text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                        buttons: [
                                                                                            'copy',
                                                                                            'excel',
                                                                                            'csv',
                                                                                            'pdf',
                                                                                            'print'
                                                                                        ]
                                                                                    }
                                                                                ]
                                                                            });
                                                                        } else {
                                                                            alert('No private Due Date found');
                                                                            $('#private').html("");
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }

                                                        function view_emp_task(type, emp_id, firm_id) {
                                                            var x = document.getElementById("convener");
                                                            var y = document.getElementById("employee");
                                                            var employee_id = emp_id;
                                                            var firm_id = firm_id;
                                                            var type_id = type;

                                                            if (type_id === "convener") {
                                                                x.style.display = "block";
                                                                y.style.display = "none";
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?= base_url("/Super_admin/employee_task_assignment") ?>",
                                                                    dataType: "json",
                                                                    data: {type_id: type_id, employee_id: employee_id, firm_id: firm_id},
                                                                    success: function (result) {
                                                                        console.log(result);
                                                                        if (result !== '') {
                                                                            $('#modal_employee_task_assignment').modal('show');
                                                                            $('#convener').html("");
                                                                            $('#convener').append(result.suceess);
                                                                            //                                                                                    $("#example14").dataTable();
                                                                            $('#example14').DataTable({
                                                                                dom: 'Bfrtip',
                                                                                "buttons": [
                                                                                    {
                                                                                        extend: 'collection',
                                                                                        text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                        buttons: [
                                                                                            'copy',
                                                                                            'excel',
                                                                                            'csv',
                                                                                            'pdf',
                                                                                            'print'
                                                                                        ]
                                                                                    }
                                                                                ]
                                                                            });
                                                                        } else {
                                                                            alert('No convener task assignment found');
                                                                            $('#convener').html("");
                                                                        }
                                                                    }
                                                                });
                                                            } else if (type_id === "employee") {
                                                                //                                                                        $('#modal_employee_task_assignment').modal('show');
                                                                x.style.display = "none";
                                                                y.style.display = "block";
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?= base_url("/Super_admin/employee_task_assignment") ?>",
                                                                    dataType: "json",
                                                                    data: {employee_id: employee_id, firm_id: firm_id},
                                                                    success: function (result) {
                                                                        console.log(result);
                                                                        if (result !== '') {
                                                                            $('#modal_employee_task_assignment').modal('show');
                                                                            $('#employee').html("");
                                                                            $('#employee').append(result.suceess);
                                                                            //                                                                                    $("#example15").dataTable();
                                                                            $('#example15').DataTable({
                                                                                dom: 'Bfrtip',
                                                                                "buttons": [
                                                                                    {
                                                                                        extend: 'collection',
                                                                                        text: 'Tools <i class="fa fa-angle-down"></i>',
                                                                                        buttons: [
                                                                                            'copy',
                                                                                            'excel',
                                                                                            'csv',
                                                                                            'pdf',
                                                                                            'print'
                                                                                        ]
                                                                                    }
                                                                                ]
                                                                            });
                                                                        } else {
                                                                            alert('No convener task assignment found');
                                                                            $('#employee').html("");
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }



                                                        function change_status(firm_id, status) {
                                                            var temp_status = '';
                                                            if (status == 'A') {
                                                                temp_status = 'De-activate';
                                                            } else {
                                                                temp_status = 'Active';
                                                            }
                                                            if (confirm('Are you sure you wants to ' + temp_status + ' HR Admin. If yes click on OK else Cancel')) {
                                                                $.ajax({
                                                                    type: "POST",
//                                                                                url: "<?= base_url("/Firm_form/change_activity_status") ?>",
                                                                    url: "<?= base_url("/Hq_firm_form/change_activity_status") ?>",
                                                                    dataType: "json",
                                                                    data: {firm_id: firm_id, status: status},
                                                                    success: function (result) {
                                                                        // alert(result.error);
                                                                        if (result.status === true) {
                                                                            if (result.msg === 'A') {
                                                                                alert(' HR Admin de-activated successfully.');
                                                                                location.reload();
//                                                                                window.location.href = "<?= base_url("show_firm") ?>" + firm_id;
                                                                            } else {
                                                                                alert('HR Admin activated successfully.');
//                                                                                window.location.href = "<?= base_url("show_firm") ?>" + firm_id;
                                                                                location.reload();
                                                                            }
                                                                            // return;

                                                                        } else if (result.status === false) {
                                                                            alert('Something went wrong.');
                                                                        } else {
                                                                            $('#message').html(result.error);
                                                                        }
                                                                    },
                                                                    error: function (result) {
                                                                        console.log(result);
                                                                        if (result.status === 500) {
                                                                            alert('Internal error: ' + result.responseText);
                                                                        } else {
                                                                            alert('Unexpected error');
                                                                        }
                                                                    }
                                                                });
                                                            } else {

                                                            }
                                                        }


</script>