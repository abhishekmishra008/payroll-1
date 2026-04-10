<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') or exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');
$data['session_data'] = $session_data;
$user_id = ($session_data['user_id']);
$emp_id = ($session_data['emp_id']);


$user_type = ($session_data['user_type']);
//$user_type;

?>  <!--<link  href="<?php /*echo base_url() . "assets/"; */ ?>pages/css/profile.min.css" rel="stylesheet" type="text/css" />-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
      rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.css"
      rel="stylesheet" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
<link href="<?= base_url("assets/schedule_task/schedule_view.css") ?>" rel="stylesheet" type="text/css">
<style>
    table.dataTable thead th, table.dataTable thead td {
        padding: 10px 18px;
        border-bottom: 1px solid #ffffff;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #ffffff;
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        padding: 8px;
        line-height: 1.42857;
        vertical-align: top;
        border-top: 1px solid #ffffff;
    }

    .portlet.light > .portlet-title > .nav-tabs > li > a {
        margin: none !important;
    }

    .tabbable-custom > .nav-tabs > li > a {
        margin-right: 0;
        color: black !important;
    }

    .tabbable-custom > .nav-tabs > li {
        margin-right: 2px;
        background-color: #7cabb7 !important;
        border-top: 2px solid #f9f3f3b5;
    }
</style>

<style type="text/css">
	.timesheetheader{
		height: 99vh;
		overflow: auto;
	}
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
    / / max-width: 1200 px;
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
    / / text-align: center;
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


    /*.info_box12{
        line-height: 28px;
    }*/
    .date1 {
        line-height: 58px;
        margin-bottom: 10px;
        margin-top: 10px;
        color: black;
        font-weight: 600;
        margin-left: 10px;
    }

    .ui-datepicker-calendar {
        display: none;
    }

    .active {
        color: #0a7de1;
        background: #a2d4ff;
    }

    .task_scroll {
        overflow-x: auto;
    }

    /*[contenteditable="true"] {
        outline: 1px solid lightgrey !important;
    }*/

    .grids-head {
        width: 100%;
        z-index: 99;
        position: static;
    }

    .grids-head.sticky {
        position: -webkit-sticky;
        position: sticky;
        top: 10px;
        /*padding:20px 0;*/
    }

    .grids-head {
        overflow: auto;
    }

    .grids-head::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }

    ::-webkit-scrollbar {
        width: 9px;
        height: 9px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    #ui-datepicker-div {
        z-index: 100 !important;
    }

    .main-navbar {
        margin-top: 40px !important;
    }

    #myDropdown1 {
        margin-top: -10px !important;
        height: 8% !important;
    }

    #myDropdown2 {
        margin-top: 100px !important;
        display: none !important;
    }

    .portlet.light.portlet-fit > .portlet-title {
        padding: 5px 0px 5px;
        margin-bottom: 0px;
    }

    .portlet {
        margin-bottom: 0px
    }

    .page-content {
        padding-top: 0px !important;
    }

    /*---*/
    .mobilemenu_nav {
        display: none !important;
    }

    #work_planing_data {
        height: 500px;
        overflow-y: auto;
    }

    #select2-add_total_time-results {
        overflow-y: auto;
        height: 254px;
    }

    console-schedulear {
        height: 627px;
    }

    .select2-results {
        color: #343a40 !important;
    }

    .option_disable {
        color: #ccc !important;
        /*cursor: no-drop;*/
    }

    .ride-options ul {
        padding: 0px 0px;
    }

    .subnav1 .subnavbtn1 {
        padding: 2px 16px !important;
        font-weight: unset !important;
    }

    body {
        overflow: clip;
    }

    #mySidenav {
        z-index: 999;
        margin-top: 100px !important;
        box-shadow: lightgrey 2px 0px 0px 0px;
        width: 280px;
    }

    #accordion {
        padding-left: 0px !important;
    }

    #accordion .card-header {
        background-color: white !important;
        border-bottom: none !important;
    }

    #accordion .card {
        border: none !important;
    }

    .sv_btn {
        font-size: 12px;
        height: auto;
    }
</style>

<body>
<div class="page-fixed-main-content">
    <div class="page-content-wrapper">
        <div class="page-content mt-3">
            <!--<div class="page-content" style="background-color: #eef1f5;">-->
            <div class="col-md-12 ">
                <div class="col-md-12 ">
                    <div class="row wrapper-shadow" id="HTMLtoPDF">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light portlet-fit portlet-form">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">Schedule Task</span>
                                    </div>
                                    <div style="float: right;">
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#add-new" title="Add New Task"
                                                onclick="close_list_modal(1);"><i class="fa fa-plus"></i> Add Task
                                        </button>
                                        <a class="btn btn-primary btn-sm" id="daily_task" title="Download daily task"
                                           href="<?php echo base_url() ?>Excel_export/daily_task"><i
                                                    class="fa fa-file-excel-o"></i> Daily Task</a>
                                        <a class="btn btn-primary btn-sm text-white" title="Download monthly task"
                                           onclick="monthlyTask()"><i class="fa fa-file-excel-o"></i> Monthly Task</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- BEGIN PORTLET -->
                                    <div class="portletlight " style=".portlet.light {padding: 0px !important;}">
                                        <div class="portlet-title tabbable-custom ">
                                            <div class="caption caption-md">
                                                <i class="icon-globe theme-font hide"></i>
                                                <div class="profile-usertitle-name"> <?php // echo $user_id; ?></div>
                                            </div>


                                            <div class="console-schedular">
                                                <div class="schedular-filters">
                                                    <!--<div class="vehicle-type">
                                                        <select class="select full">
                                                            <option>Select Employee</option>
                                                            <option>Supriya</option>
                                                            <option>Suraj</option>
                                                        </select>
                                                    </div>--><!-- Employee Type -->
                                                    <div>
                                                        <input type="color" value="#a2d4ff"> <b>Today</b>
                                                        <input type="color" value="#caefc8"> <b>Complete</b>
                                                        <input type="color" value="#ffa9af"> <b>Holiday</b>
                                                        <input type="color" value="#fff2be"> <b>Pending</b>
                                                        <input type="color" value="#fcedef"> <b>Daily Task</b>
                                                    </div>
                                                    <div>
                                                        <select id="employee_id" name="employee_id"
                                                                class="form-control employee_id"
                                                                onchange="get_employee_taskList();get_taskViewInCalendar();get_totalCalculateHours();get_boardList();">
                                                            <option>Search by Employee</option>
                                                        </select>
                                                    </div>
                                                    <div class="date-changer">
                                                        <!--<a class="custom-btn prevDate" href="#" title="javascript:void(0)"><i class="fa fa-chevron-left"></i></a>-->
                                                        <!--<input type="text" class="new-datepicker" id="task_month" name="task_month" readonly/>-->
                                                        <!--<a class="custom-btn nextDate" href="#" title=""><i class="fa fa-chevron-right"></i></a>-->
                                                        <input type="text" class="" id="txtDate" name="task_month"
                                                               value="<?= date('M-Y') ?>"/>
                                                        <input type="hidden" class="" id="select_year"
                                                               name="select_year" value=""/>
                                                        <input type="hidden" class="" id="select_month"
                                                               name="select_month" value=""/>
                                                    </div><!-- Date Picker -->
                                                </div> <!-- Schedular Filters -->


                                                <!--<div class="schedular-body">

                                                    <div class="main-schedular">
                                                        <div class="d-flex grids" style="overflow-y: scroll;overflow-x: scroll;height:500px;">
                                                            <div class="GridDate" >
                                                                <div class="timeslot" style="margin: 7px 10px;padding: 10px 10px;">
                                                                    <h6 class="mb-0">Date</h6>
                                                                </div>
                                                                <div id="getWeekDate"></div>
                                                            </div>
                                                            <div class="GridTotalHr" >
                                                                <div class="timeslot" style="margin: 7px 10px;padding: 10px 10px;">
                                                                    <h6 class="mb-0">Total Hr</h6>
                                                                </div>
                                                                <div id="getTotalHour"></div>
                                                            </div>
                                                            <div class="w-75">
                                                                <div class="grids-head">
                                                                    <div class="timeslot"> 10:00 AM </div>
                                                                    <div class="timeslot"> 10:15 AM </div>
                                                                    <div class="timeslot"> 10:30 AM </div>
                                                                    <div class="timeslot"> 10:45 AM </div>
                                                                    <div class="timeslot"> 11:00 AM </div>
                                                                    <div class="timeslot"> 11:15 AM </div>
                                                                    <div class="timeslot"> 11:30 AM </div>
                                                                    <div class="timeslot"> 11:45 AM </div>
                                                                    <div class="timeslot"> 12:00 AM </div>
                                                                    <div class="timeslot"> 12:15 AM </div>
                                                                    <div class="timeslot"> 12:30 AM </div>
                                                                    <div class="timeslot"> 12:45 AM </div>
                                                                    <div class="timeslot"> 01:00 PM </div>
                                                                    <div class="timeslot"> 01:15 PM </div>
                                                                    <div class="timeslot"> 01:30 PM </div>
                                                                    <div class="timeslot"> 01:45 PM </div>
                                                                    <div class="timeslot"> 02:00 PM </div>
                                                                    <div class="timeslot"> 02:15 PM </div>
                                                                    <div class="timeslot"> 02:30 PM </div>
                                                                    <div class="timeslot"> 02:45 PM </div>
                                                                    <div class="timeslot"> 03:00 PM </div>
                                                                    <div class="timeslot"> 03:15 PM </div>
                                                                    <div class="timeslot"> 03:30 PM </div>
                                                                    <div class="timeslot"> 03:45 PM </div>
                                                                    <div class="timeslot"> 04:00 PM </div>
                                                                    <div class="timeslot"> 04:15 PM </div>
                                                                    <div class="timeslot"> 04:30 PM </div>
                                                                    <div class="timeslot"> 04:45 PM </div>
                                                                    <div class="timeslot"> 05:00 PM </div>
                                                                    <div class="timeslot"> 05:15 PM </div>
                                                                    <div class="timeslot"> 05:30 PM </div>
                                                                    <div class="timeslot"> 05:45 PM </div>
                                                                    <div class="timeslot"> 06:00 PM </div>
                                                                    <div class="timeslot"> 06:15 PM </div>
                                                                    <div class="timeslot"> 06:30 PM </div>
                                                                    <div class="timeslot"> 06:45 PM </div>
                                                                    <div class="timeslot"> 07:00 PM </div>
                                                                </div>

                                                                <div class="grids-body" id="grids_body">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>--><!-- Schedular Body -->

                                                <div class="schedular-body">

                                                    <!-- Main Schedular data -->
                                                    <div class="main-schedular" style="overflow-x: hidden !important;">
                                                        <div class="gridsHeader d-flex">
                                                            <div class="d-flex">
                                                                <div class="timeslot"
                                                                     style="margin: 7px 10px ;padding: 10px 10px; ">
                                                                    <h6 class="mb-0">Date</h6>
                                                                </div>
                                                                <div class="timeslot"
                                                                     style="margin: 7px 0px;padding: 10px 10px; ">
                                                                    <h6 class="mb-0">Total Hr</h6>
                                                                </div>
                                                            </div>
                                                            <div class="grids-head" style="margin-right: 0 !important;">
                                                               <!-- <div class="timeslot"> 10:00 AM</div>
                                                                <div class="timeslot"> 10:15 AM</div>
                                                                <div class="timeslot"> 10:30 AM</div>
                                                                <div class="timeslot"> 10:45 AM</div>
                                                                <div class="timeslot"> 11:00 AM</div>
                                                                <div class="timeslot"> 11:15 AM</div>
                                                                <div class="timeslot"> 11:30 AM</div>
                                                                <div class="timeslot"> 11:45 AM</div>
                                                                <div class="timeslot"> 12:00 AM</div>
                                                                <div class="timeslot"> 12:15 AM</div>
                                                                <div class="timeslot"> 12:30 AM</div>
                                                                <div class="timeslot"> 12:45 AM</div>
                                                                <div class="timeslot"> 01:00 PM</div>
                                                                <div class="timeslot"> 01:15 PM</div>
                                                                <div class="timeslot"> 01:30 PM</div>
                                                                <div class="timeslot"> 01:45 PM</div>
                                                                <div class="timeslot"> 02:00 PM</div>
                                                                <div class="timeslot"> 02:15 PM</div>
                                                                <div class="timeslot"> 02:30 PM</div>
                                                                <div class="timeslot"> 02:45 PM</div>
                                                                <div class="timeslot"> 03:00 PM</div>
                                                                <div class="timeslot"> 03:15 PM</div>
                                                                <div class="timeslot"> 03:30 PM</div>
                                                                <div class="timeslot"> 03:45 PM</div>
                                                                <div class="timeslot"> 04:00 PM</div>
                                                                <div class="timeslot"> 04:15 PM</div>
                                                                <div class="timeslot"> 04:30 PM</div>
                                                                <div class="timeslot"> 04:45 PM</div>
                                                                <div class="timeslot"> 05:00 PM</div>
                                                                <div class="timeslot"> 05:15 PM</div>
                                                                <div class="timeslot"> 05:30 PM</div>
                                                                <div class="timeslot"> 05:45 PM</div>
                                                                <div class="timeslot"> 06:00 PM</div>
                                                                <div class="timeslot"> 06:15 PM</div>
                                                                <div class="timeslot"> 06:30 PM</div>
                                                                <div class="timeslot"> 06:45 PM</div>
                                                                <div class="timeslot"> 07:00 PM</div>-->
																<div class="timeslot"> 10:00 AM</div>
																<div class="timeslot"> 11:00 AM</div>
																<div class="timeslot"> 12:00 AM</div>
																<div class="timeslot"> 01:00 PM</div>
																<div class="timeslot"> 02:00 PM</div>
																<div class="timeslot"> 03:00 PM</div>
																<div class="timeslot"> 04:00 PM</div>
																<div class="timeslot"> 05:00 PM</div>
																<div class="timeslot"> 06:00 PM</div>
																<div class="timeslot"> 07:00 PM</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex grids"
                                                             style="overflow-y: scroll;overflow-x: scroll;height:500px;">
                                                            <div class="GridDate GridTotalHr d-flex"
                                                                 style="position: sticky; left: 0; z-index: 99;background: #f5f6fa;">
                                                                <div id="getWeekDate"></div>
                                                                <div id="getTotalHour"></div>
                                                            </div>
                                                            <div class="w-75">
                                                                <div class="grids-body" id="grids_body">
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div class="vehicles-info">
                                                        <h3>Work Planing</h3>

                                                        <div id="work_planing_data">

                                                        </div>

                                                        <!--<div class="car">
                                                            <div class="car-top"> <span>NY 614: 2018</span> <i>30H</i> </div>
                                                            <div class="car-bottom">
                                                                <h5>BMW X1</h5> <i>Car 678</i>
                                                            </div>
                                                        </div>

                                                        <div class="car">
                                                            <div class="car-top"> <span>T76768 : 2015</span> <i>42H</i> </div>
                                                            <div class="car-bottom">
                                                                <h5>BMW i8</h5> <i>Car 987</i>
                                                            </div>
                                                        </div>-->

                                                    </div>
                                                </div>
                                            </div><!-- console Schedular -->
                                            <!-- Main Schedular data -->


                                            <div class="ride-menu new-task">
                                                <div class="menu-title">
                                                    <h4>New Task</h4>
                                                    <p>Select Below An Action To Perform</p>
                                                </div>
                                                <input type="hidden" name="get_time" id="get_time">
                                                <input type="hidden" name="get_date" id="get_date">
                                                <ul>
                                                    <li>
                                                        <button class="new-task" data-toggle="modal"
                                                                data-target="#add-new" href="#" title="Add New Task"
                                                                onclick="close_list_modal();"
                                                                style="text-align: justify;width: 100%;display: inline-block;background: #fff;color: #9ea4b0;font-size: 14px;">
                                                            <i class="ion-model-s"></i>Add A New Task
                                                        </button>
                                                    </li>
                                                    <!--<li><a data-rideclass="return" class="other-info-btn" href="#" title=""><i class="ion-arrow-return-left"></i> <span>Return To Base</span></a></li>
                                                    <li><a data-rideclass="maintenance" class="other-info-btn" href="#" title=""><i class="ion-settings"></i> <span>Maintenance</span></a></li>
                                                    <li><a data-rideclass="preparation" class="other-info-btn" href="#" title=""><i class="ion-pinpoint"></i> <span>Preparation</span></a></li>
                                                    <li><a data-rideclass="service" class="other-info-btn" href="#" title=""><i class="ion-pull-request"></i> <span>Vehicle Service</span></a></li>-->
                                                    <li class="bordered">
                                                        <button onclick="close_list_modal();"
                                                                style="text-align: justify;width: 100%;display: inline-block;background: #fff;color: #9ea4b0;font-size: 14px;">
                                                            <i class="ion-android-cancel"></i>Cancel
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div><!-- New Ride Menu -->

                                            <div class="ride-menu ride-opts"
                                                 style="width:137px;border: 1px solid #ccc;">
                                                <div class="ride-options">
                                                    <ul>
                                                        <!--<li>
                                                            <button class="edit-ride" href="#" title="Edit Task" onclick="close_list_modal();" style="text-align: justify;width: 100%;display: inline-block;background: #fff;color: #9ea4b0;font-size: 14px;">
                                                                <i class="ion-edit"></i>Edit Task</button>
                                                        </li>-->

                                                        <li>
                                                            <button class="delete-ride" href="#" title="Delete task"
                                                                    onclick="deleteTask()"
                                                                    style="text-align: justify;width: 100%;display: inline-block;background: #fff;color: #9ea4b0;font-size: 14px;">
                                                                <i class="ion-android-remove-circle"></i>Delete Task
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div><!-- Ride Options -->
                                            </div>


                                            <!-- New Task  -->
                                            <div class="modal custom-modal add_new_modal" id="add-new">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form class="custom-form" id="add_task" method="post">
                                                            <div class="modal-header">
                                                                <div class="modal-title">
                                                                    <h4>Schedule Task</h4>
                                                                    <span>Fill The Form Below To Add A New Task</span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body text-dark">
                                                                <div class="form-group row" id="employee_id_div">
                                                                    <label class="col-lg-5 col-md-12 text-dark  col-form-label">Select
                                                                        Employee</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <select id="employee_id_task"
                                                                                name="employee_id_task"
                                                                                class="form-control employee_id"
                                                                                onchange="get_boardList(this.value);">
                                                                            <option>Select Employee</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-lg-5 col-md-12 text-dark  col-form-label">Task</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <input id="task_title" name="task_title"
                                                                               type="text"
                                                                               class="form-control text-dark" required>
                                                                        <input type="hidden" id="get_date1"
                                                                               name="get_date1">
                                                                        <input type="hidden" id="get_time1"
                                                                               name="get_time1">
                                                                        <input type="hidden" id="task_id1"
                                                                               name="task_id1">
                                                                    </div>
                                                                </div>

                                                                <!--<div class="form-group row">
                                                                    <label class="col-lg-5 col-md-12  col-form-label">Select Board:</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <select name="board_id" id="board_id" class="form-control" onchange="get_cardList()">
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-lg-5 col-md-12  col-form-label">Select Card:</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <select name="item_id" id="item_id" class="form-control">
                                                                            <option value="">Select Item</option>
                                                                        </select>
                                                                    </div>
                                                                </div>-->

                                                                <div class="form-group row">
                                                                    <label class="col-lg-5 col-md-12 text-dark col-form-label">Description</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <textarea id="task_desc" class="text-dark"
                                                                                  name="task_desc" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <!--<div class="form-group row add_time">
                                                                    <label class="col-lg-5 col-md-12 text-dark col-form-label">Total Time(hr): <span>(optional)</span></label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <input id="add_total_time" name="add_total_time" type="number" class="form-control text-dark" min="1">
                                                                    </div>
                                                                </div>-->
                                                                <div class="form-group row" id="dateRow">
                                                                    <label class="col-lg-5 col-md-12 text-dark col-form-label">Date</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <input type="date" name="select_date"
                                                                               id="select_date" class="form-control"
                                                                               onchange="setDate(this.value)">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row add_time">
                                                                    <label class="col-lg-5 col-md-4 text-dark col-form-label">
                                                                        <!-- <span class="str_time_show"></span> -->
                                                                        <div id="start_time_div" style="">
                                                                            <select class="form-control select2"
                                                                                    name="start_time" id="str_time_show"
                                                                                    onchange="setTime(this.value)">
                                                                                <option value="">Select Start Time
                                                                                </option>
                                                                                <option value="10:00:00"> 10:00:00
                                                                                </option>
                                                                                <option value="10:15:00"> 10:15:00
                                                                                </option>
                                                                                <option value="10:30:00"> 10:30:00
                                                                                </option>
                                                                                <option value="10:45:00"> 10:45:00
                                                                                </option>
                                                                                <option value="11:00:00"> 11:00:00
                                                                                </option>
                                                                                <option value="11:15:00"> 11:15:00
                                                                                </option>
                                                                                <option value="11:30:00"> 11:30:00
                                                                                </option>
                                                                                <option value="11:45:00"> 11:45:00
                                                                                </option>
                                                                                <option value="12:00:00"> 12:00:00
                                                                                </option>
                                                                                <option value="12:15:00"> 12:15:00
                                                                                </option>
                                                                                <option value="12:30:00"> 12:30:00
                                                                                </option>
                                                                                <option value="12:45:00"> 12:45:00
                                                                                </option>
                                                                                <option value="13:00:00"> 13:00:00
                                                                                </option>
                                                                                <option value="13:15:00"> 13:15:00
                                                                                </option>
                                                                                <option value="13:30:00"> 13:30:00
                                                                                </option>
                                                                                <option value="13:45:00"> 13:45:00
                                                                                </option>
                                                                                <option value="14:00:00"> 14:00:00
                                                                                </option>
                                                                                <option value="14:15:00"> 14:15:00
                                                                                </option>
                                                                                <option value="14:30:00"> 14:30:00
                                                                                </option>
                                                                                <option value="14:45:00"> 14:45:00
                                                                                </option>
                                                                                <option value="15:00:00"> 15:00:00
                                                                                </option>
                                                                                <option value="15:15:00"> 15:15:00
                                                                                </option>
                                                                                <option value="15:30:00"> 15:30:00
                                                                                </option>
                                                                                <option value="15:45:00"> 15:45:00
                                                                                </option>
                                                                                <option value="16:00:00"> 16:00:00
                                                                                </option>
                                                                                <option value="16:15:00"> 16:15:00
                                                                                </option>
                                                                                <option value="16:30:00"> 16:30:00
                                                                                </option>
                                                                                <option value="16:45:00"> 16:45:00
                                                                                </option>
                                                                                <option value="17:00:00"> 17:00:00
                                                                                </option>
                                                                                <option value="17:15:00"> 17:15:00
                                                                                </option>
                                                                                <option value="17:30:00"> 17:30:00
                                                                                </option>
                                                                                <option value="17:45:00"> 17:45:00
                                                                                </option>
                                                                                <option value="18:00:00"> 18:00:00
                                                                                </option>
                                                                                <option value="18:15:00"> 18:15:00
                                                                                </option>
                                                                                <option value="18:30:00"> 18:30:00
                                                                                </option>
                                                                                <option value="18:45:00"> 18:45:00
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <!-- <select class="form-control select2"  name="add_total_time" id="add_total_time" onchange="check_time()">-->
                                                                        <select class="form-control select2"
                                                                                name="add_total_time"
                                                                                id="add_total_time"
                                                                                onchange="check_time()">
                                                                            <option value="0">Select End Time</option>
                                                                            <option value="10:00:00"> 10:00:00</option>
                                                                            <option value="10:15:00"> 10:15:00</option>
                                                                            <option value="10:30:00"> 10:30:00</option>
                                                                            <option value="10:45:00"> 10:45:00</option>
                                                                            <option value="11:00:00"> 11:00:00</option>
                                                                            <option value="11:15:00"> 11:15:00</option>
                                                                            <option value="11:30:00"> 11:30:00</option>
                                                                            <option value="11:45:00"> 11:45:00</option>
                                                                            <option value="12:00:00"> 12:00:00</option>
                                                                            <option value="12:15:00"> 12:15:00</option>
                                                                            <option value="12:30:00"> 12:30:00</option>
                                                                            <option value="12:45:00"> 12:45:00</option>
                                                                            <option value="13:00:00"> 13:00:00</option>
                                                                            <option value="13:15:00"> 13:15:00</option>
                                                                            <option value="13:30:00"> 13:30:00</option>
                                                                            <option value="13:45:00"> 13:45:00</option>
                                                                            <option value="14:00:00"> 14:00:00</option>
                                                                            <option value="14:15:00"> 14:15:00</option>
                                                                            <option value="14:30:00"> 14:30:00</option>
                                                                            <option value="14:45:00"> 14:45:00</option>
                                                                            <option value="15:00:00"> 15:00:00</option>
                                                                            <option value="15:15:00"> 15:15:00</option>
                                                                            <option value="15:30:00"> 15:30:00</option>
                                                                            <option value="15:45:00"> 15:45:00</option>
                                                                            <option value="16:00:00"> 16:00:00</option>
                                                                            <option value="16:15:00"> 16:15:00</option>
                                                                            <option value="16:30:00"> 16:30:00</option>
                                                                            <option value="16:45:00"> 16:45:00</option>
                                                                            <option value="17:00:00"> 17:00:00</option>
                                                                            <option value="17:15:00"> 17:15:00</option>
                                                                            <option value="17:30:00"> 17:30:00</option>
                                                                            <option value="17:45:00"> 17:45:00</option>
                                                                            <option value="18:00:00"> 18:00:00</option>
                                                                            <option value="18:15:00"> 18:15:00</option>
                                                                            <option value="18:30:00"> 18:30:00</option>
                                                                            <option value="18:45:00"> 18:45:00</option>
                                                                        </select>
                                                                    </div>
                                                                </div>


                                                                <!--<select>
                                                                <?php
                                                                /*                                                                $start_hour = 10;
                                                                                                                                $end_hour = 19;
                                                                                                                                $minutes_array = array("15", "30", "45");
                                                                                                                                if($start_hour<)
                                                                                                                                for($i=$start_hour; $i<($end_hour + 1); $i++){
                                                                                                                                    $string = $i . ':00';
                                                                                                                                    echo '<option value="' . $string . '">' . $string . '</option>';
                                                                                                                                    if($i != $end_hour){
                                                                                                                                        for($j=0; $j<sizeof($minutes_array); $j++){
                                                                                                                                            $string = $i . ':' . $minutes_array[$j];
                                                                                                                                            echo '<option value="' . $string . '">' . $string . '</option>';
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                */ ?>
                                                            </select>-->

                                                                <a href="javascript:void(0)"
                                                                   class="btn-link attach_board"
                                                                   onclick="attach_board_function()"> Attach with
                                                                    Board</a>

                                                                <div class="form-group row select_board"
                                                                     style="display:none;">
                                                                    <label class="col-lg-5 col-md-12 text-dark col-form-label">Select
                                                                        Board:</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <select name="board_id" id="board_id"
                                                                                class="form-control"
                                                                                onchange="get_cardList()">
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row select_card"
                                                                     style="display:none;">
                                                                    <label class="col-lg-5 col-md-12 text-dark col-form-label">Select
                                                                        Card:</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <select name="item_id" id="item_id"
                                                                                class="form-control">
                                                                            <option value="">Select Item</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="custom-btn ride-cancel color2"
                                                                        data-dismiss="modal"><i
                                                                            class="ion-android-cancel"></i> Cancel
                                                                </button>
                                                                <button class="btn btn-sm btn-primary sv_btn"
                                                                        id="add_task_btn" type="button"
                                                                        onclick="schedule_task('add')">Add Task
                                                                </button>
                                                                <button class="btn btn-sm btn-primary sv_btn"
                                                                        type="button" onclick="schedule_task('edit')"
                                                                        id="edit_task_btn" style="display: none;"><i
                                                                            class="ion-android-add-circle"></i> Edit
                                                                    Task
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Assign Driver  -->


                                            <!-- Assign Driver  -->
                                            <div class="modal custom-modal" id="change-ride">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form class="custom-form" id="ride-changer">
                                                            <div class="modal-header">
                                                                <div class="modal-title">
                                                                    <h4>Edit Task</h4>
                                                                    <span>Edit The Following Task</span>
                                                                </div>
                                                                <button type="button" class="close"
                                                                        data-dismiss="modal">&times;
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group row">
                                                                    <label class="col-lg-5 col-md-12  col-form-label">Edit
                                                                        This Task</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <select class="select change-ride">
                                                                            <option value="return">Return</option>
                                                                            <option value="maintenance">Maintenance
                                                                            </option>
                                                                            <option value="preparation">Preparation
                                                                            </option>
                                                                            <option value="service">Service</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="#" title="" class="custom-btn color2"
                                                                   data-dismiss="modal"><i
                                                                            class="ion-android-cancel"></i> Cancel</a>
                                                                <button class="custom-btn" type="submit"><i
                                                                            class="ion-android-add-circle"></i> Edit
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

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
</div>
</body>


<!--<script src="<? /*= base_url() */ ?>assets/js/cbpFWTabs.js" type="text/javascript"></script>-->
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.23.0/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="<?= base_url("assets/schedule_task/schedule_view.js") ?>" type="text/javascript"></script>
<script>
    var base_url = '<?php echo base_url()?>';
</script>
<script>
    (function () {
        var target = $(".grids-head");
        $(".grids").scroll(function () {
            target.prop("scrollTop", this.scrollTop)
                .prop("scrollLeft", this.scrollLeft);
        });
    })();
</script>
