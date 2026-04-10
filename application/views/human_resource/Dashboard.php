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
    $username = ($session_data['user_id']);
} else {
    $username = $this->session->userdata('login_session');
}
?>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="#">
                        <?php if ($firm_name_nav !== "") { ?>
                            <!--<h3 class="uppercase"style="font-size:23px;">-->
                            <?php echo $firm_name_nav; ?>
                            <!--</h3>-->
                            <?php
                        } else {
                            echo 'HR';
                        }
                        ?>

                    </a>
                    <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                </li>
            </ul>
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'hr_dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>hr_dashboard">Home</a>
                        <i class="fa fa-arrow-right" style="font-size:10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li> 
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>


                </ul>

            </div>
        </div>
        <h1 class="page-title">
        </h1>
        <div class="page-fixed-main-content">
            <hr>
            <input type="hidden" id="firm_id_hq" name="firm_id_hq" value="<?php echo $firm_id ?>"/>
            <div class="row" align="center">

                <div class="col-md-2" align="center">
                    <!--<label>Select Office</label>-->
                    <select class="form-control" id="firm_name" name="firm_name" onchange="get_emp();" >
                        <option value="">Select Office</option>
                    </select>
                    <span class="required" id="ddl_firm_name_fetch_error"></span>
                </div>
                <div class="col-md-2" align="center" >
                    <!--<label>Select Year</label>-->
                    <select name="select" class="form-control" id="dropdownYear1" onchange="get_graph_all_leave_type(this);getgraph_for_leave(this);" style="width: 250px;">
                    </select>
                </div>
                <div  class="col-md-2">
                    <select name="select" class="form-control"  id="select_emp_cst" onchange="get_graph_emp_wise(this);" style="width: 300px;">
                        <option value="">Select Employee</option>
                    </select>
                </div>
                <div class="col-md-2">

                    <select name="leave_types" id="leave_types" style="width: 160px;"onchange="get_graph_leave_type(this);"  class="form-control m-select2 m-select2-general">
                        <option value="">Select leave type</option>
                    </select>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h3>Leave Report Branch Wise</h3>
                    <div id="container" style="width: 100%;"></div>
                </div>
                <div class="col-md-6">
                    <div class="row">


                    </div>
                    <h3>Leave Report Type Wise</h3>
                    <div id="container1" style="width: 100%;"></div></div>
            </div>
            <hr>
            <div class="row">
                <div  class="col-md-6">
                    <h3>Employee Leave Report Type Wise</h3>
                    <div id="container3" style="width: 100%;"></div>
                </div>
                <div  class="col-md-6" >
                    <h3>Employee Leave Report Month Wise</h3>
                    <div id="container4" class="col-md-6" style="width: 100%;"></div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <h3>Yearly Type Wise Leave</h3>
                    <div id="container2" style="width: 100%;"></div>
                </div>
            </div>
            <hr>
        </div>

<?php
$this->load->view('human_resource/footer');
?>
    </div>
</div>
<!--hr checklist modal start-->
<div class="modal fade" id="ischecklistCompleted" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <i class=" icon-layers font-green"></i>
                <span class="caption-subject font-green sbold uppercase" id="total_check"></span>
                /<span class="caption-subject font-green sbold uppercase" id="completed_check"></span>
            </div>
            <div class="modal-body">
                <table class="table" id="rejection_list_table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Rejection Reason</th>
                            <th>Rejected At</th>
                            <th>Rejected By</th>
                            <th>Download</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="rejection_list_tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--hr checklist modal end-->




<script src="<?php echo base_url(); ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script>
                        $(document).ready(function () {
                            $.ajax({
                                url: "<?= base_url("/Human_resource/get_firms") ?>",
                                dataType: "json",
                                success: function (result) {
                                    if (result['message'] === 'success') {
                                        var data = result.firm_data;
                                        var ele3 = document.getElementById('firm_name');
                                        for (i = 0; i < data.length; i++)
                                        {

                                            ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                        }
                                    }
                                }
                            });



                            $('#dropdownYear').each(function () {

                                var year = (new Date()).getFullYear();
                                var current = year;
                                year -= 3;
                                for (var i = 0; i < 6; i++) {
                                    if ((year + i) == current)
                                        $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
                                    else
                                        $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
                                }

                            });
                            $('#dropdownYear1').each(function () {

                                var year = (new Date()).getFullYear();
//                var current = year;
                                year -= 3;
                                $(this).append('<option selected value="">select year</option>');
                                for (var i = 0; i < 6; i++) {
                                    $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
                                }

                            });
                            $('#dropdownYear2').each(function () {

                                var year = (new Date()).getFullYear();
//                var current = year;
                                year -= 3;
                                $(this).append('<option selected value="">select year</option>');
                                for (var i = 0; i < 6; i++) {
                                    $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
                                }

                            });

                            $.ajax({
                                url: "<?= base_url("/Employee/ddl_get_employee") ?>",
                                dataType: "json",
                                success: function (result) {
                                    if (result['message'] === 'success') {
                                        var data = result.emp_data;
                                        var ele = document.getElementById('select_emp_cst');
                                        ele.innerHTML = '<option value="">Select New Employee</option>';
                                        for (i = 0; i < data.length; i++)
                                        {

                                            //alert(data[i]['due_date_id']);
                                            // POPULATE SELECT ELEMENT WITH JSON.
                                            ele.innerHTML = ele.innerHTML +
                                                    '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
                                        }
                                    }
                                }
                            });
                        });

                        function get_sorted_data() {
                            alert('sad');
                            var firm_id_fetch = document.getElementById('ddl_firm_name_fetch').value;
                            window.location.href = "<?= base_url("/Designation/show_designation_hq/") ?>" + firm_id_fetch;

                        }
                        function get_emp()
                        {
                            var e = document.getElementById("firm_name");
                            var firm_id = e.options[e.selectedIndex].value;
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url("graphs/get_leave_type") ?>",
                                dataType: "json",
                                data: {firm_id: firm_id},
                                success: function (result) {
                                    if (result.status === true) {
                                        var ele = document.getElementById('leave_types');
                                        ele.innerHTML = "";
                                        var data = result.leave_types;
                                        ele.innerHTML = ele.innerHTML + " <option value='' selected='' > Select Leave Type</option>";
                                        for (i = 0; i < data.length; i++)
                                        {
                                            ele.innerHTML = ele.innerHTML +
                                                    '<option value="' + data[i] + '">' + data[i] + '</option>';
                                        }
                                    } else {

                                    }
                                }

                            });


                            $.ajax({
                                type: "post",
                                url: "<?= base_url("Task_allotment/get_alloted_to") ?>",
                                dataType: "json",
                                data: {firm_id: firm_id},
                                success: function (result) {
                                    //                            alert(firm_id);
                                    //                            option="";
                                    if (result['message'] === 'success') {
                                        var ele = document.getElementById('select_emp_cst');
                                        ele.innerHTML = "";
                                        var data = result.alloted_to_data_hq;
                                        ele.innerHTML = ele.innerHTML + " <option value='' selected='' > Select Employee </option>";
                                        for (i = 0; i < data.length; i++)
                                        {
                                            // POPULATE SELECT ELEMENT WITH JSON.
                                            ele.innerHTML = ele.innerHTML + '<option value="' + data[i]['user_id'] + '">' + data[i]['user_name'] + '</option>';
                                        }
                                    }
                                }
                            });
                        }
                        function get_graph_emp_wise(id)
                        {
                            var e = document.getElementById("firm_name");
                            var firm_id = e.options[e.selectedIndex].value;
                            var e = document.getElementById("dropdownYear1");
                            var year = e.options[e.selectedIndex].value;
                            if (year === "")
                            {
                                alert('please select year.');
                                document.getElementById("select_emp_cst").value = 0;

                            } else {
                            }
                            var user_id = id.value;
                            var e = document.getElementById("select_emp_cst");
                            var user_name = e.options[e.selectedIndex].text;
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url("graphs/get_graph_emp_wise") ?>",
                                dataType: "json",
                                data: {user_id: user_id, year: year, firm_id: firm_id},
                                success: function (result) {
                                    if (result.message === "success") {
                                        var leave_types = result.leave_types;
                                        var leave_types_count = result.leave_types_count;
                                        var ratio = result.ratio;
                                        Highcharts.chart('container3', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: 'Leaves Type wise'
                                            },
                                            subtitle: {
                                                text: '<b>' + user_name + '</b>',
                                            },
                                            xAxis: {
                                                categories: leave_types
                                            },
                                            yAxis: [{
//                                max: 50,
                                                    title: {
                                                        text: 'No. of Leaves'
                                                    }
                                                }, {
                                                    min: 0,
                                                    max: 100,
                                                    opposite: true,
                                                    title: {
                                                        text: 'Ratio(in %) '
                                                    }
                                                }],
                                            legend: {
                                                shadow: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                    name: 'Total Leaves of ' + user_name,
                                                    data: leave_types_count,
                                                    color: '#7B0DB6',
                                                    tooltip: {

                                                        valueSuffix: ' Leaves'
                                                    },
                                                }, {
                                                    type: 'spline',
                                                    color: '#03A6CE',
                                                    name: 'Ratio',
                                                    data: ratio,
                                                    yAxis: 1,
                                                    tooltip: {
                                                        valueSuffix: ' %'
                                                    },
                                                    plotOptions: {
                                                        spline: {
                                                            dataLabels: {
                                                                enabled: true
                                                            },
                                                            enableMouseTracking: false
                                                        }
                                                    },
                                                }],
                                        });
                                    } else {
                                        alert('No Leaves Taken');
                                    }
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url("graphs/get_graph_monthly_emp_wise") ?>",
                                dataType: "json",
                                data: {user_id: user_id, year: year},
                                success: function (result) {
                                    if (result.message === "success") {
                                        var leave_types_count = result.leave_types_count;
                                        var month = result.month;
                                        Highcharts.chart('container4', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: 'Total Leaves Month Wise'
                                            },
                                            subtitle: {
                                                text: '<b>' + user_name + '</b>',
                                            },
                                            xAxis: {
                                                categories: month
                                            },
                                            yAxis: [{
                                                    title: {
                                                        text: 'No. of Leaves'
                                                    }
                                                }, {
                                                    min: 0,
                                                    max: 100,
                                                    opposite: true,
                                                    title: {
                                                        text: 'Ratio(in %) '
                                                    }
                                                }],
                                            legend: {
                                                shadow: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                    name: 'Total Monthly Leaves of ' + user_name,
                                                    data: leave_types_count,
//                                color: '#7B0DB6',
                                                    tooltip: {

                                                        valueSuffix: ' Leaves'
                                                    },
                                                }
                                            ],
                                        });
                                    } else {
//                    alert('No Leaves Taken');
                                    }
                                }
                            });
                        }
                        function get_graph_all_leave_type(id)
                        {
                            var e = document.getElementById("firm_name");
                            var firm_id = e.options[e.selectedIndex].value;
                            var year = id.value;
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url("graphs/get_graphs_all_leaves_type") ?>",
                                dataType: "json",
                                data: {year: year, firm_id: firm_id},
                                success: function (result) {
                                    if (result.message === "success") {
                                        var leave_types = result.leave_types;
                                        var leave_types_count = result.leave_types_count;
                                        var ratio = result.ratio;
                                        Highcharts.chart('container2', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: 'Total Leaves'
                                            },
                                            subtitle: {
                                                text: "",
                                            },
                                            xAxis: {
                                                categories: leave_types
                                            },
                                            yAxis: [{
                                                    max: 50,
                                                    title: {
                                                        text: 'No. of Leaves'
                                                    }
                                                }, {
                                                    min: 0,
                                                    max: 100,
                                                    opposite: true,
                                                    title: {
                                                        text: 'Ratio(in %) '
                                                    }
                                                }],
                                            legend: {
                                                shadow: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                    name: 'Total Leaves',
                                                    data: leave_types_count,
                                                    color: '#EE8126',
                                                    tooltip: {

                                                        valueSuffix: ' Leaves'
                                                    },
                                                }, {
                                                    type: 'spline',
                                                    color: '#060301',
                                                    name: 'Ratio',
                                                    data: ratio,
                                                    yAxis: 1,
                                                    tooltip: {
                                                        valueSuffix: ' %'
                                                    },
                                                    plotOptions: {
                                                        spline: {
                                                            dataLabels: {
                                                                enabled: true
                                                            },
                                                            enableMouseTracking: false
                                                        }
                                                    },
                                                }],
                                        });
                                    }
                                }
                            });
                        }
                        function get_graph_leave_type(id)
                        {
                            var e = document.getElementById("firm_name");
                            var firm_id = e.options[e.selectedIndex].value;
                            var e = document.getElementById("dropdownYear1");
                            var year = e.options[e.selectedIndex].value;
                            if (year === "")
                            {
                                alert('please select year.');
                                document.getElementById("leave_types").value = 0;
                                ;
                            } else {
                            }
                            var leave_type = id.value;
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url("graphs/get_graphs_leaves_type_wise") ?>",
                                dataType: "json",
                                data: {leave_type: leave_type, year: year, firm_id: firm_id},
                                success: function (result) {
                                    if (result.message === "success") {
                                        var months = result.month_data;
                                        var total_leaves = result.total_firm_leaves;
                                        var ratio = result.ratio;
                                        Highcharts.chart('container1', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: 'Total ' + leave_type + ' Leaves'
                                            },
                                            subtitle: {
                                                text: "",
                                            },
                                            xAxis: {
                                                categories: months
                                            },
                                            yAxis: [{
                                                    max: 50,
                                                    title: {
                                                        text: 'No. of Leaves'
                                                    }
                                                }, {
                                                    min: 0,
                                                    max: 100,
                                                    opposite: true,
                                                    title: {
                                                        text: 'Ratio(in %) '
                                                    }
                                                }],
                                            legend: {
                                                shadow: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                    name: 'Total ' + leave_type + ' Leaves',
                                                    data: total_leaves,
                                                    color: '#911816',
                                                    tooltip: {

                                                        valueSuffix: ' Leaves'
                                                    },
                                                }, {
                                                    type: 'spline',
                                                    color: '#DBBE1E',
                                                    name: 'Ratio',
                                                    data: ratio,
                                                    yAxis: 1,
                                                    tooltip: {
                                                        valueSuffix: ' %'
                                                    },
                                                    plotOptions: {
                                                        spline: {
                                                            dataLabels: {
                                                                enabled: true
                                                            },
                                                            enableMouseTracking: false
                                                        }
                                                    },
                                                }],
                                        });
                                    }
                                }
                            });
                        }
                        function getgraph_for_leave(id)
                        {
                            var e = document.getElementById("firm_name");
                            var firm_id = e.options[e.selectedIndex].value;
                            var year = id.value;
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url("graphs/get_graphs_leaves") ?>",
                                dataType: "json",
                                data: {year: year, firm_id: firm_id},
                                success: function (result) {
                                    if (result.message === "success") {
                                        var months = result.months;
                                        var total_leaves = result.count_firm;
                                        var ratio = result.ratio;
                                        Highcharts.chart('container', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: 'Total Leaves'
                                            },
                                            subtitle: {
                                                text: "",
                                            },
                                            xAxis: {
                                                categories: months
                                            },
                                            yAxis: [{
                                                    max: 50,
                                                    title: {
                                                        text: 'No. of Leaves'
                                                    }
                                                }, {
                                                    min: 0,
                                                    max: 100,
                                                    opposite: true,
                                                    title: {
                                                        text: 'Ratio(in %) '
                                                    }
                                                }],
                                            legend: {
                                                shadow: false
                                            },
                                            tooltip: {
                                                shared: true
                                            },
                                            series: [{
                                                    name: 'Total Leaves',
                                                    data: total_leaves,
                                                    color: '#146FA7',
                                                    tooltip: {

                                                        valueSuffix: ' Leaves'
                                                    },
                                                }, {
                                                    type: 'spline',
                                                    color: '#5BCB45',
                                                    name: 'Ratio',
                                                    data: ratio,
                                                    yAxis: 1,
                                                    tooltip: {
                                                        valueSuffix: ' %'
                                                    },
                                                    plotOptions: {
                                                        spline: {
                                                            dataLabels: {
                                                                enabled: true
                                                            },
                                                            enableMouseTracking: false
                                                        }
                                                    },
                                                }],
                                        });
                                    }
                                }
                            });
                        }




</script>
