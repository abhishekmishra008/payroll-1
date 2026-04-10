<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');

$data['session_data'] = $session_data;
$user_id = ($session_data['emp_id']);
$user_type = ($session_data['user_type']);


$page_name = 'View Calender Holiday';
$page_name2 = 'View Due Date List';
//var_dump($firm_name_dd);
//echo $firm_id_new;
?>

<style>
    span.error {
        color: red;
    }
    td {
        text-align: center;
    }
    .tabbable-custom>.nav-tabs>li>a {
        margin-right: 0;
        color: black !important;}
    .tabbable-custom>.nav-tabs>li {
        margin-right: 2px;
        background-color: #7cabb7 !important;
        border-top: 2px solid #f9f3f3b5;}

</style>
<style type="text/css">

    .tabs {
        position: relative;
        overflow: hidden;
        margin: 0 auto;
        width: 100%;
        font-weight: 300;
        font-size: 1.0em;
    }

    /* Nav */
    .tabs nav {
        text-align: center;
    }

    .tabs nav ul {
        position: relative;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flex;
        display: flex;
        margin: 0 auto;
        padding: 0;
    //  max-width: 1200px;
        list-style: none;
        -ms-box-orient: horizontal;
        -ms-box-pack: center;
        -webkit-flex-flow: row wrap;
        -moz-flex-flow: row wrap;
        -ms-flex-flow: row wrap;
        flex-flow: row wrap;
        -webkit-justify-content: center;
        -moz-justify-content: center;
        -ms-justify-content: center;
        justify-content: center;
    }

    .tabs nav ul li {
        position: relative;
        z-index: 1;
        display: block;
        margin: 0;
        text-align: center;
        -webkit-flex: 1;
        -moz-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    .tabs nav a {
        position: relative;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 2.5;
        color: #891635;
    }

    .tabs nav a span {
        vertical-align: middle;
        font-size: 1em;
    }

    .tabs nav li.tab-current a {
        color: #74777b;
    }

    .tabs nav a:focus {
        outline: none;
    }

    /* Icons */
    .icon::before {
        z-index: 10;
        display: inline-block;
        margin: 0 0.4em 0 0;
        vertical-align: middle;
        text-transform: none;
        font-weight: normal;
        font-variant: normal;
        font-size: 1.3em;
        font-family: 'stroke7pixeden';
        line-height: 1;
        speak: none;
        -webkit-backface-visibility: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Content */
    .content-wrap {
        position: relative;
    }

    .content-wrap section {
        display: none;
        /*margin: 0 auto;*/
        padding: 0px !important;
        max-width: 100%;
        text-align: center;
    }

    .content-wrap section.content-current {
        display: block;
    }

    .content-wrap section p {
        margin: 0;
        padding: 0.75em 0;
        color: rgba(40, 44, 42, 0.05);
        font-weight: 900;
        font-size: 4em;
        line-height: 1;
    }

    /* Fallback */
    .no-js .content-wrap section {
        display: block;
        padding-bottom: 2em;
        border-bottom: 1px solid rgba(255, 255, 255, 0.6);
    }

    .no-flexbox nav ul {
        display: block;
    }

    .no-flexbox nav ul li {
        min-width: 15%;
        display: inline-block;
    }

    /*****************************/
    /* Underline */
    /*****************************/

    .tabs-style-underline nav {
        background: #fff;
    }

    .tabs-style-underline nav a {
        padding: 0.25em 0 0.5em !important;
        border-left: 1px solid #e7ecea;
        -webkit-transition: color 0.2s;
        transition: color 0.2s;
    }

    .tabs-style-underline nav li:last-child a {
        border-right: 1px solid #e7ecea;
    }

    .tabs-style-underline nav li a::after {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: #891635;
        content: '';
        -webkit-transition: -webkit-transform 0.3s;
        transition: transform 0.3s;
        -webkit-transform: translate3d(0, 150%, 0);
        transform: translate3d(0, 150%, 0);
    }

    .tabs-style-underline nav li.tab-current a::after {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }

    .tabs-style-underline nav a span {
        font-weight: 700;
    }

    .tabs-style-underline nav a:hover {
        text-decoration: none !important;
        color: #74777b !important;
    }

    .fa_class {
        font-size: 22px !important;
    }

    @media screen and (max-width: 58em) {
        .tabs nav a.icon span {
            display: none;
        }

        .tabs nav a:before {
            margin-right: 0;
        }
    }

    #dynamicDataTableFilter_0 {
        margin-top: 6px !important;
    }
    /* Style the tab */
.tab {
    overflow: hidden;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border-top: none;
}
</style>
<div class="page-fixed-main-content">
    <div class="page-content-wrapper">
        <div class="page-content">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Task/Projects Reports</span>
                    </div>

                </div>

                <div class="portlet-body">
                    <div class="tabs tabs-style-underline">
                        <nav>

                            <ul>
                                <li class="tab-current"><a id="tab-0"  href="#tab_5_5" ><i
                                                class="fa fa-clock-o mr-1 fa_class"></i> <span>  Employee Wise Reports</span></a>
                                </li>
                                <li class=""><a id="tab-0"  href="#tab_5_1" ><i
                                                class="fa fa-users mr-1 fa_class"></i> <span>Project Wise Reports</span></a>
                                </li>

                            </ul>
                        </nav>
                        <div class="content-wrap">
                            <section class="content-current" id="tab_5_5">
                                <div class="panel-body">
                                   <div class="row">
                                    <div class="row">
                                         <div class="col-md-6">
                                            <label>Employee Name</label>
                                           <select class="form-control" name="emp_name" id="emp_name">

                                           </select>
                                       </div>
                                       <div class="col-md-6">
                                            <label>Frequency</label>
                                           <select class="form-control" name="frequency" id="frequency" onchange="getFrequencyDate(this.value)">
                                               <option value="daily" selected>Daily</option>
                                               <option value="weekly">Weekly</option>
                                               <option value="monthly">Monthly</option>
                                           </select>
                                       </div>
                                    </div>

                                       <div id="dailyOptions" class="options" style="">
                                            <label for="dailyDate">Select Date:</label>
                                            <input type="date" id="dailyDate" name="dailyDate" class="form-control">
                                        </div>

                                        <div id="weeklyOptions" class="options" style="display:none;">
                                            <label for="weeklyMonth">Select Month:</label>
                                            <input type="month" id="weeklyMonth" name="weeklyMonth" class="form-control">

                                            <label for="weekOfMonth">Week of the Month:</label>
                                            <select id="weekOfMonth" name="weekOfMonth" class="form-control">
                                                <option value="1">First Week</option>
                                                <option value="2">Second Week</option>
                                                <option value="3">Third Week</option>
                                                <option value="4">Fourth Week</option>
                                                <option value="5">Last Week</option>
                                            </select>
                                        </div>

                                        <div id="monthlyOptions" class="options" style="display:none;">
                                            <label for="monthlyYear">Select Month:</label>
                                            <input type="month" id="monthlyYear" name="monthlyYear" class="form-control">


                                        </div>
                                        <div class="text-right" style="margin-top: 10px;">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="viewTask()"><i class="fa fa-eye"></i> View</button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="dailyTask(true)"><i class="fa fa-file-excel-o"></i> Download Excel</button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="dailyTask(false)"><i class="fa fa-file-excel-o"></i> Download Pdf</button>
                                        </div>
                                   </div>
                                   <div class="row" id="employeeTable">

                                   </div>
                                </div>
                            </section>
                            <section class="" id="tab_5_1">

                                <div class="panel-body">
                                    <div class="col-md-6">
                                            <label>Frequency</label>
                                           <select class="form-control" name="proj_frequency" id="proj_frequency" onchange="getFrequencyDate(this.value)">
                                               <option value="proj_yearly" selected>Yearly</option>
                                               <option value="proj_monthly">Monthly</option>
                                           </select>
                                    </div>
                                    <div id="yearlyOptions" class="options col-md-6" style="">
                                            <label for="proj_year">Select Year:</label>
                                            <select id="proj_year" name="proj_year" class="form-control">
                                            </select>
                                    </div>
                                    <!-- <div id="projmonthlyOptions" class="options col-md-6" style="display:none;">
                                            <label for="proj_monthlyYear">Select Month:</label>
                                            <input type="month" id="proj_monthlyYear" name="proj_monthlyYear" class="form-control">
                                    </div> -->
                                    <div class="col-md-12 text-right" style="margin-top: 10px;">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="viewProjectTask()"><i class="fa fa-eye"></i> View</button>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="projectTask()"><i class="fa fa-file-excel-o"></i> Download</button>
                                    </div>
                                </div>
                                <div class="row col-md-12" id="projectTable">


                                </div>
                            </section>
                        </div>
                    </div>
                  <!--  <div class="tabbable-custom ">

                        <div class="tab-content">




                            <div class="tab-pane" id="tab_5_5">
                                <div class="panel-body  table-scrollable" style="overflow-x: scroll;">

                                    <br> <br> <br>
                                    <table class="table table-striped table-bordered table-hover dtr-responsive dataTable no-footer"
                                           id="sample30" role="grid" aria-describedby ="sample_3_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="text-align:center" scope="col">Missing Punch In Time</th>
                                                <th style="text-align:center" scope="col">Missing Punch Out Time</th>
                                                <th style="text-align:center" scope="col">Reason</th>
                                                <th style="text-align:center" scope="col">Status</th>
                                                <th style="text-align:center" scope="col">Action</th>
                                                 <th style="text-align:center" scope="col">PunchInLocation</th>
                                                <th style="text-align:center" scope="col">PunchOutLocation</th>

                                            </tr>
                                        </thead>
                                        <tbody id="missign_punching">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>

                <?php $this->load->view('human_resource/footer'); ?>
            </div>

        </div>



    </div>


    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="<?= base_url() ?>assets/js/cbpFWTabs.js" type="text/javascript"></script>

<script src="<?=base_url("assets/schedule_task/schedule_view.js") ?>" type="text/javascript"></script>
<script>
    var base_url = '<?php echo base_url()?>';
</script>
    <script>
        $(document).ready(function () {
            employeeList(updateFrequency);
            // updateFrequency();
            [].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
                new CBPFWTabs(el);
            });
            const select = document.getElementById('proj_year');
            const currentYear = new Date().getFullYear();
            const startYear = 2010; // Adjust this as needed

            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.text = year;
                select.appendChild(option);
            }
        });

        document.querySelector('#emp_name').onchange = (e) => {
            updateFrequency()
        }

        function updateFrequency() {
            let empName = document.querySelector('#emp_name').value
            if (empName == 'all') {
                document.querySelector('#frequency').innerHTML = `
                    <option value="daily" selected>Daily</option>
                `;
            }
            else {
                document.querySelector('#frequency').innerHTML = `
                    <option value="daily" selected>Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                `;
            }
        }

    </script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    ​

