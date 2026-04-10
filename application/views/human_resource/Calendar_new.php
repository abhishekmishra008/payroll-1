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
    $employee_id = ($session_data['emp_id']);
    $emp_id = ($session_data['emp_id']);


    $user_type = ($session_data['user_type']);

    if ($data_holiday != '') {
        $List = implode(',', $data_holiday);
    } else {
        $List = '';
    }

    $page_name = 'View Calender Holiday';
    $page_name2 = 'View Due Date List';
    
?>
<style>
    .required {
        color: red !important
    }

    a.morelink {
        text-decoration: none;
        outline: none;
    }

    .morecontent span {
        display: none;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }

    .show-read-more .more-text {
        display: none;
    }
    .emp_attendance_div{
        width: 23.33333% !important;
    }
    .fc-day-grid-event .fc-content {
         white-space: pre-wrap;
        overflow: hidden;
    }

    .small-btn {
        width: 150px;
    }

    .custom-centered-modal {
        display: flex;
        align-items: center;
    }

    #logss{
        /* left: 50px; */
        margin-top: 60px;
    }
    .mob_view{
        margin-bottom: 10px;
    }
    

    /* @media (max-width: 576px)  { */
        .mob-view-custom{
                text-align: center;
                display: inline-block;
                width: 76%;
        }
    /* } .*/
    .login-btn-office {
        background: #378006;
        border-color: #378006;
        color: #fff;
    }

    .login-btn-site {
        background: #8A6D3B;
        border-color: #8A6D3B;
        color: #fff;
    }

    .login-btn-wfh {
        background: #219b90;
        border-color: #219b90;
        color: #fff;
    }

    .btn:hover {
        color: #fff;
    }

</style>

<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?= base_url() ?>assets/global/css/mystyle1.css" rel="stylesheet" type="text/css"/>
<link href="<?= base_url() ?>assets/apps/css/mobile.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyCTEocZ3R3r7o6HDBRI25Mygcd7xkKvw9g&libraries=places"></script>

<div class="page-fixed-main-content">
    <div class="page-content-wrapper">
        <div class="page-content">
            <input type="hidden" id="user_type" name="user_type" value=<?php echo $user_type ?>>
            <br>
            <?php
            $pass = "";
            $user_id = "";
            $valid = 0;
            if ($this->input->cookie('rmt_tool') != '') {
                $cookie_name_fetch = $this->input->cookie('rmt_tool');
                $company_logo_name_cookie = explode(",", $cookie_name_fetch);
                $cookie_name = $company_logo_name_cookie[0];
                $user_name = $company_logo_name_cookie[1];
                $pass = $company_logo_name_cookie[2];
                $user_id = $company_logo_name_cookie[3];
                //echo $user_id;
                $valid = $company_logo_name_cookie[4];
                if ($cookie_name != '') {
                    ?>
                    <!--<p Style="font-size: 200px;">Welcome To HRMS</p>-->
                <?php } else {
                    ?>
                    <!--<p Style="font-size: 200px;">Welcome To HRMS</p>-->
                    <?php
                }
            } else {
                $user_name = "";
                ?>
                <!--<p Style="font-size: 200px;">Welcome To HRMS</p>-->
            <?php } ?>
            <input type="hidden" id="currentMonth" name="currentMonth">
            <input type="hidden" id="monthNames" name="monthNames">
            <input id="latlng" type="hidden" value=""/>
            <input type="hidden" id="holiday_array1" name="holiday_array1" value="<?php echo $List ?>">

            <input type="hidden" name="cusername" id="cusername" value="<?= $user_id ?>"/>
            <input type="hidden" name="pusername" id="pusername" value="<?= $pass ?>"/>
            <input type="hidden" name="uvalid" id="uvalid" value="<?= $valid ?>"/>
            <div class="alert notification" style="display: none;">
                <button class="close" data-close="alert"></button>
                <p></p>
            </div>
            <input type="hidden" id="emp_login" name="emp_login" value="<?php echo $emp_id ?>"/>
            <div class="row" style="display: none">
                <div class="col-md-8">
                    <div class="col-md-12">
                        <div class="note note-success">
                            <span>Leave-</span>
                            &nbsp; <span> Req: </span><i class="fa fa-home" style="color:#bf8f37;font-size: 17px;"></i>
                            &nbsp; <span> Accept: </span><i class="fa fa-home" style="color:green;font-size: 17px;"></i>
                            &nbsp;<span> Denied: </span></i><i class="fa fa-home"
                                                               style="color:#af2626;font-size: 17px;"></i>
                            &nbsp; <br><span>Reg Att-</span>
                            <span> Req: </span><i class="fa fa-user-times" title="Missing Attendace Requested!"
                                                  style="font-size: 15px;color:#bf8f37"></i>
                            &nbsp; <span> Accept: </span><i class="fa fa-user-times" title="Missing Attendace Accepted!"
                                                            style="font-size: 15px;color:green"></i>
                            &nbsp; <span> Deny: </span><i class="fa fa-user-times" title="Missing Attendace Denied!"
                                                          style="font-size: 15px;color:#af2626"></i>
                        </div>
                    </div>


                </div>


            </div>
            <div class="row" style="display: flex; flex-direction: row;padding-left:20px;">

                <div class="col-md-2">
                    <button id="sample_1_new"
                            class="btn-info btn-simple btn blue-hoki btn-outline sbold uppercase popovers form-control"
                            data-toggle="modal" onclick="modal_open()" data-container="body" data-placement="left"
                            data-trigger="hover">
						<span>Leave <i class="fa fa-user" title="Leave Requested!" style="font-size: 15px;"></i><i class="fa fa-history"></i></span>
                    </button>
                </div>
                <div class="col-md-3">
                    <select id="employee_id" name="employee_id" class="form-control"
                            onchange="get_data_employee_wiseNew()">
                        <option value="">Search by Employee</option>
                    </select>
                    <input type="hidden" id="session_emp_id" name="session_emp_id" value="<?php echo $employee_id ?>">
                </div>
                <div class="col-md-1  emp_attendance_div">
                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" id="emp_attendance_btn" data-target="#emp_attendance_Req"><i class="fa fa-eye"></i></button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div id="location_data" name="location_data"></div>
                    <div class="portlet light wrapper-shadow">
                        <div class="portlet-body">
                            <!-- place -->
                            <div id="calendarIO1"></div>
                            <div id="calendarIO2"></div>
                            <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form class="form-horizontal" method="POST" action="POST" id="form_create">
                                            <div class="loading" id="loaders8" style="display:none;"></div>
                                            <input type="hidden" name="calendar_id" value="0">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">Attendance Details</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div id="time_div"></div>
                                                    <div class="col-sm-6">
                                                        <button type="button" class="btn btn-primary" id="login_btn" data-toggle="modal" data-target="#loginTypeModal"> Choose Login Type </button>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" id="logout_btn" data-target="#logoutTypeModal"> Choose logout Type </button>
                                                    </div>
                                                    <div class="loading" id="loaders5" style="display:none;"></div>
                                                </div>
                                                <div id='msg'></div>
                                                <br>
                                                <input type="hidden" id="srtaddress" name="srtaddress">
                                                <input type="hidden" id="longaddress" name="longaddress">
                                                <b>Your Current Location:-</b><br>
                                                <p id="shortaddress"></p>
                                                <p id="address"></p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login Type Modal -->
                    <div class="modal fade" id="loginTypeModal" tabindex="-1" role="dialog">
                        <!-- <div class="modal-dialog modal-dialog-centered custom-centered-modal" id='logss' role="document"> -->
                        <div class="modal-dialog modal-dialog-centered" id='logss' role="document">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title" style="font-weight: bold;">Select Login Type</h4>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4 mb-2 mob_view" style="text-align: center;">
                                            <button class="btn login-btn-office btn-block py-2 mob-view-custom" onclick="selectLoginType('login', 1)"> In Office </button>
                                        </div>

                                        <div class="col-12 col-sm-4 mb-2 mob_view" style=" text-align: center;">
                                            <button class="btn login-btn-site btn-block py-2 mob-view-custom client_location" onclick="selectLoginType('login', 2)"> At site Office </button>
                                        </div>

                                        <div class="col-12 col-sm-4 mb-2 mob_view" style="text-align: center;">
                                            <button class="btn login-btn-wfh btn-block py-2 mob-view-custom" onclick="selectLoginType('login', 3)"> Work From Home </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end  Login Type Modal -->
                    <div class="modal fade" id="logoutTypeModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title" style="font-weight: bold;">Select Logout Type</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-4 mb-2 mob_view" style="text-align: center;" >
                                            <button class="btn login-btn-office btn-block py-2 mob-view-custom" onclick="selectLogoutType('logout', 1)"> In Office </button>
                                        </div>
                                        <div class="col-12 col-sm-4 mb-2 mob_view" style="text-align: center;" >
                                            <button class="btn login-btn-site btn-block py-2 mob-view-custom" onclick="selectLogoutType('logout', 2)"> At Site Office </button>
                                        </div>
                                        <div class="col-12 col-sm-4 mb-2 mob_view" style="text-align: center;" >
                                            <button class="btn login-btn-wfh btn-block py-2 mob-view-custom" onclick="selectLogoutType('logout', 3)"> Work From Home </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--end  Logout Type Modal -->
            </div>
            <?php $this->load->view('human_resource/footer'); ?>
            <div class="row" id="gps_div" style="display:none">
                <div class="col-md-12">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="ongpsid" data-toggle="toggle"
                               onchange="ongps()">
                        <label class="custom-control-label" for="customSwitch1">On GPS</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Request Leave</h4>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <form id="leave_request_form" name="leave_request_form" method="post">
                        <div id="cancel_leave_btn" name="cancel_leave_btn"></div>
                        <input type="hidden" id="user" name="user" value="<?php echo $user_id ?>">
                        <input type="hidden" id="leave_date_selected" name="leave_date_selected">
                        <input type="hidden" id="firm_id" name="firm_id" value="<?php echo $firm_id ?>">
                        <input type="hidden" id="boss_id" name="boss_id" value="<?php echo $boss_id ?>">
                        <input type="hidden" id="designation" name="designation" value="<?php echo $designation_id ?>">
                        <input type="hidden" id="senior_id" name="senior_id" value="<?php echo $senior_id ?>">
                        <div id="leave_type_data"></div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" id="btn_leave_rq">
            </div>
        </div>
    </div>
</div>

<!-- REGULARIZED ATTENDENCE -->
<div class="modal fade" id="add_miss_punch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Request For Regularize Attendance</h4>
                <button class="btn btn-primary" onclick="add_leave_btn()">Add Leave</button>
            </div>
            <div class="modal-body">
                <div id="msng_form">
                    <form type="POST" id="missing_punch_form" name="missing_punch_form">
                        <input type="date" id="date_selected" name="date_selected" style="display:none">
                        <label>Punch In Time</label>
                        <input type="datetime-local" id="punch_in_time" name="punch_in_time" class="form-control" value="" >
                        <br>
                        <label>Punch Out Time</label>
                        <input type="datetime-local" id="punch_out_time" name="punch_out_time" class="form-control"><br>
                        <label>Reason</label>
                        <textarea name="reason_missing" id="reason_missing" class="form-control"
                                  placeholder="Missing Punch Reason"></textarea>
                        <input type="hidden" id="srtaddress1" name="srtaddress1">
                        <input type="hidden" id="longaddress1" name="longaddress1">
                        <br>
                        <b>Your Current Location:-</b><br>
                        <p id="shortaddress1"></p>
                        <p id="address1"></p>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"> Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
                <div id="msng_form2">
                </div>
            </div>
        </div> 
    </div> 
</div>

<!--ATTENDENCE REQUEST-->
<div class="modal fade" id="emp_attendance_Req" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <input type="hidden" id="currentMonth1" name="currentMonth1">
            <input type="hidden" name="emp_req_att" id="emp_req_att">
            <input type="hidden" name="emp_req_mon" id="emp_req_mon">
            <input type="hidden" name="emp_req_year" id="emp_req_year">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title text-center" id="emp_header_name"></h5>
                <h4 class="modal-title text-center" id="monthNames12"></h4>
                <button class="btn btn-primary" id="generateReport" onclick="generateXls()">Generate Excel</button>
                <h5><b>OO: </b> Outside Office | <b>LR:</b> Leave Request | <b>RA:</b> Regularize Attendance |
                    <b>NA:</b> Not Applicable</h5>
                <h5><b>R</b> : Requested | <b>A</b> : Approved | <b>D</b> : Denied</h5>
            </div>

            <div class="modal-body">
                <div class="portlet-body" id="second_table" style="overflow-x: scroll;height:500px;">
                    <table class="table table-striped table-bordered table-hover dtr-responsive dataTable  responsive display"
                           id="second_table1" role="grid" aria-describedby="sample_1_info">
                        <thead>
                            <tr role="row">
                                <th style="text-align:center;width:33px !important;" data-priority="1" scope="col">D</th>
                                <th style="text-align:center;width:33px;" data-priority="1" scope="col">Type</th>
                                <th style="text-align:center;width:33px;" data-priority="3" scope="col">In</th>
                                <th style="text-align:center;width:33px;" data-priority="4" scope="col">Out</th>
                                <th style="text-align:center;width: 50px;" data-priority="5" scope="col">loc</th>
                                <th style="text-align:center;width: 33px;" data-priority="2" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="full_att_data1">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VIEW LEAVE DETAILS -->
<div class="modal fade" id="view_leave_details" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Leave Details</h4>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <form id="edit_designation_form" name="edit_designation_form" method="post">
                        <input type="hidden" name="leave_id" id="leave_id" value="">
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $emp_id; ?>">
                        <input type="hidden" name="emp_user_id" id="emp_user_id" value="">
                        <input type="hidden" name="leave_type1" id="leave_type1" value="">
                        <input type="hidden" name="desig_id" id="desig_id" value="">
                        <!--<div id="leave_data" name="leave_data"></div>-->
                        <div class="col-md-12 ">
                            <div class="col-md-8"></div>

                            <div class="col-md-2" style="float: right">
                                <button id="approve_all_btn" name="approve_all_btn" type="button" data-toggle="modal"
                                        data-target="#myModal1" class="btn sbold red btn-sm"
                                        data-dvalue="<?php echo $emp_id; ?>">
                                    <i class="fa fa-check"></i> Deny All
                                </button>
                            </div>

                            <div class="col-md-2" style="float: right">
                                <button id="deny_all_btn" name="deny_all_btn" type="button"
                                        class="btn sbold blue btn-sm" onclick="approve_all('<?php echo $emp_id; ?>');">
                                    <i class="fa fa-check"></i> Approve All
                                </button>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover dtr-inline  dataTable no-footer  responsive display"
                               cellspacing="0" id="sample1" style=" margin-left: -8px;" role="grid">
                            <thead>
                            <th style="text-align:center" data-priority="1">Leave Type</th>
                            <th style="text-align:center" data-priority="3">Leave Date</th>
                            <th style="text-align:center" data-priority="4">Status</th>
                            <th style="text-align:center" data-priority="2">Action</th>
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

<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="margin-left: 1%;margin-right: 2%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">DENIED REASON</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="reason_id">
                    <input type="hidden" name="deny_id1" id="deny_id1">
                    <textarea name="den_id1" id="den_id1"></textarea>
                    <button type="button" id="due_date" onclick="deny_all()" class="btn btn-primary"
                            style="margin-left: 5px;margin-top: -39px;"> ok
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="mydenyModal" tabindex="-1" role="dialog" aria- labelledby="myModalLabel" aria-hidden="true"
     style="margin-left: 1%;margin-right: 2%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">DENIED REASON</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="reason_id">
                    <input type="hidden" name="deny_id" id="deny_id">
                    <textarea name="den_id" id="den_id"></textarea>
                    <button type="button" id="due_date" onclick="deny_leave()" class="btn btn-primary"
                            style="margin-left: 5px;margin-top: -39px;"> ok
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="clientLoginLocationDetails" tabindex="-1" role="dialog" aria- labelledby="clientLocationDetailsLabel" aria-hidden="true" style="margin-left: 1%;margin-right: 2%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Client Details</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div style="margin-bottom: 4rem;">
                        <input type="text" name="client_name" id="login_client_name" class="form-control">
                    </div>
                    <button type="button" onclick="clientLoginLocationDetailSubmit('login', 2)" class="btn btn-primary" style="margin-left: 5px;margin-top: -39px;"> Submit </button>
                </form>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="clientLogoutLocationDetails" tabindex="-1" role="dialog" aria- labelledby="clientLocationDetailsLabel" aria-hidden="true" style="margin-left: 1%;margin-right: 2%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Client Details</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div style="margin-bottom: 4rem;">
                        <input type="text" name="client_name"  id="logout_client_name" class="form-control">
                    </div>
                    <button type="button" onclick="clientLogoutLocationDetailSubmit('logout', 2)" class="btn btn-primary" style="margin-left: 5px;margin-top: -39px;"> Submit </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade " id="outside_login_modal" tabindex="-1" role="dialog" aria-labelledby="outside_login_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Outside Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body">
                <div class="greeting-card">
                    <div class="card-back">
                        <input type="text" name="" id="" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <div id="map">
            </div>
        </div>
    </div>
</div>

<div class="loading" id="loaders7" style="display:none;"></div>




<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script type="text/javascript">
    let selectedPunchType = null;
    let calendarResult={};
    let viewStart;
    let viewEnd;
    let attendenceTime = null;


    function ongps() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }


    $(document).ready(function () {
        $('#login_btn').click(function () {
            selectedPunchType = $(this).data("punch_type");
            $('#create_modal').modal('hide');
        });

        $('#logout_btn').click(function () {
            selectedPunchType = $(this).data("punch_type");
            $('#create_modal').modal('hide');
        });
    });


    function calendar_data(employee = '') {
        $('#calendarIO1').fullCalendar('destroy');
        $('.date-picker').datepicker();
        var sta_count = 0;
        var now = new Date();
        var start = new Date(now.getFullYear(), now.getMonth(), 1);
        var end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        end=formatdate(end);
        var	 count = 1;
        $('#calendarIO1').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                defaultView: 'month',
                right: 'month,basicWeek,basicDay'

            },
            selectable: true,
            select:  function (start, end, allDay) {
                var d = new Date();
                d.setHours(0, 0, 0, 0);
                var start = new Date(start);
                start.setHours(0, 0, 0, 0);
                if (d < start) {
                    $('#myModal').modal('show');
                    var a = moment(start).format('YYYY-MM-DD');
                    $('#leave_date_selected').val(a);
                    get_cancel_leave_btn(a);
                } else if (d > start) {
                    var array_time;
                    get_login_details1(start).then(function (result) {
                        array_time = result;
                        if (Array.isArray(array_time) == true) {
                            if (array_time[2] == 1 || array_time[2] == 2) {
                                alert("You cannot add request for regularized attendace beacause you had requested leave on same day");
                            } else {
                                $('#add_miss_punch').modal('show');
                                var a = moment(start).format('YYYY-MM-DD');
                                var pp1 = a + "T10:00";
                                var pp2 = a + "T18:30";
                                $('#punch_in_time').val(pp1);
                                $('#punch_out_time').val(pp2);
                                $('#date_selected').val(a);
                                // Fetch actual attendance time and show in modal
                                $.ajax({
                                    url: '<?= base_url("DashboardController/get_login_details1") ?>',
                                    type: "POST",
                                    data: {start_date: a},
                                    success: function (res) {
                                        success = JSON.parse(res);
                                        var actualTimeHtml = '';
                                        if (success.message == 'success') {
                                            let inn = success.intime;
                                            let intime = inn.split(" ");
                                            if(!isNaN(success.outtime)){
                                                let out = success.outtime;
                                                let outtime = out.split(" ");
                                            }
                                            var punchIn = new Date(success.intime);
                                            let year = punchIn.getFullYear();
                                            let month = String(punchIn.getMonth() + 1).padStart(2, '0');
                                            let day = String(punchIn.getDate()).padStart(2, '0');
                                            let hours = String(punchIn.getHours()).padStart(2, '0');
                                            let minutes = String(punchIn.getMinutes()).padStart(2, '0');
                                            if (success.status == 'attendace_marked') {
                                                let inn = success.intime;
                                                let out = success.outtime;
                                                let intime = inn.split(" ");
                                                let outtime = out.split(" ");
                                                var punchIn = new Date(success.intime);
                                                let year = punchIn.getFullYear();
                                                let month = String(punchIn.getMonth() + 1).padStart(2, '0');
                                                let day = String(punchIn.getDate()).padStart(2, '0');
                                                let hours = String(punchIn.getHours()).padStart(2, '0');
                                                let minutes = String(punchIn.getMinutes()).padStart(2, '0');
                                                let formatted = `${year}-${month}-${day}T${hours}:${minutes}`;
                                                $('#punch_in_time').val(formatted);
                                                let outtimeStr = success.outtime.replace(' ', 'T').replace(' PM', '').replace(' AM', '');
                                                let intimeStr = success.intime.replace(' ', 'T').replace(' PM', '').replace(' AM', '');
                                                var punchIn = new Date(intimeStr);
                                                var punchOut = new Date(outtimeStr);
                                                if (!isNaN(punchOut.getTime())) {
                                                    let parts = success.outtime.split(' ');
                                                    let datePart = parts[0];
                                                    let timePart = parts[1];    
                                                    let ampm = parts[2] || '';
                                                    let [h, m, s] = timePart.split(':');
                                                    h = parseInt(h, 10);
                                                    if (ampm === 'PM' && h < 12) h += 12;
                                                    if (ampm === 'AM' && h === 12) h = 0;
                                                    let formatted = `${datePart}T${h.toString().padStart(2, '0')}:${m}:${s}`;
                                                    punchOut = new Date(formatted);
                                                    let yearOut = punchOut.getFullYear();
                                                    let monthOut = String(punchOut.getMonth() + 1).padStart(2, '0');
                                                    let dayOut = String(punchOut.getDate()).padStart(2, '0');
                                                    let hoursOut = String(punchOut.getHours()).padStart(2, '0');
                                                    let minutesOut = String(punchOut.getMinutes()).padStart(2, '0');
                                                    let formattedOut = `${yearOut}-${monthOut}-${dayOut}T${hoursOut}:${minutesOut}`;
                                                    $('#punch_out_time').val(formattedOut);
                                                }
                                                if (!isNaN(punchIn.getTime())) {
                                                    let parts = success.intime.split(' ');
                                                    let datePart = parts[0];
                                                    let timePart = parts[1];
                                                    let ampm = parts[2] || '';
                                                    let [h, m, s] = timePart.split(':');
                                                    h = parseInt(h, 10);
                                                    if (ampm === 'PM' && h < 12) h += 12;
                                                    if (ampm === 'AM' && h === 12) h = 0;
                                                    let formatted1 = `${datePart}T${h.toString().padStart(2, '0')}:${m}:${s}`;
                                                    punchIn = new Date(formatted1);
                                                    let yearIn = punchIn.getFullYear();
                                                    let monthIn = String(punchIn.getMonth() + 1).padStart(2, '0');
                                                    let dayIn = String(punchIn.getDate()).padStart(2, '0');
                                                    let hoursIn = String(punchIn.getHours()).padStart(2, '0');
                                                    let minutesIn = String(punchIn.getMinutes()).padStart(2, '0');
                                                    let formattedIn = `${yearIn}-${monthIn}-${dayIn}T${hoursIn}:${minutesIn}`;
                                                    $('#punch_in_time').val(formattedIn);
                                                }
                                                actualTimeHtml = "<small>Actual attendance time is: In - " + intime[1] + ' AM' + ", Out - " + outtime[1] + ' PM' + "</small>";
                                            } else if (success.status == 'intime_marked') {
                                                let inn = success.intime;
                                                let out = success.outtime;
                                                let intime = inn.split(" ");
                                                var punchIn = new Date(success.intime);
                                                let year = punchIn.getFullYear();
                                                let month = String(punchIn.getMonth() + 1).padStart(2, '0');
                                                let day = String(punchIn.getDate()).padStart(2, '0');
                                                let hours = String(punchIn.getHours()).padStart(2, '0');
                                                let minutes = String(punchIn.getMinutes()).padStart(2, '0');
                                                let formatted = `${year}-${month}-${day}T${hours}:${minutes}`;
                                                let intimeStr = success.intime.replace(' ', 'T').replace(' PM', '').replace(' AM', '');
                                                var punchIn = new Date(intimeStr);
                                                if (!isNaN(punchIn.getTime())) {
                                                    let parts = success.intime.split(' ');
                                                    let datePart = parts[0];
                                                    let timePart = parts[1];
                                                    let ampm = parts[2] || '';
                                                    let [h, m, s] = timePart.split(':');
                                                    h = parseInt(h, 10);
                                                    if (ampm === 'PM' && h < 12) h += 12;
                                                    if (ampm === 'AM' && h === 12) h = 0;
                                                    let formatted1 = `${datePart}T${h.toString().padStart(2, '0')}:${m}:${s}`;
                                                    punchIn = new Date(formatted1);
                                                    let yearIn = punchIn.getFullYear();
                                                    let monthIn = String(punchIn.getMonth() + 1).padStart(2, '0');
                                                    let dayIn = String(punchIn.getDate()).padStart(2, '0');
                                                    let hoursIn = String(punchIn.getHours()).padStart(2, '0');
                                                    let minutesIn = String(punchIn.getMinutes()).padStart(2, '0');
                                                    let formattedIn = `${yearIn}-${monthIn}-${dayIn}T${hoursIn}:${minutesIn}`;
                                                    $('#punch_in_time').val(formattedIn);
                                                }
                                                // $('#punch_in_time').val(formatted);
                                                actualTimeHtml = "<small>Actual attendance time is: In - " + intime[1] + ", Out - --</small>";
                                            } else {
                                                actualTimeHtml = "<small>Actual attendance time is: --</small>";
                                            }
                                        } else {
                                            actualTimeHtml = "<small>Actual attendance time is: --</small>";
                                        }
                                        if ($('#msng_form .actual-attendance-time').length) {
                                            $('#msng_form .actual-attendance-time').html(actualTimeHtml);
                                        } else {
                                            $('#msng_form').prepend('<div class="actual-attendance-time" style="margin-bottom:10px;">' + actualTimeHtml + '</div>');
                                        }
                                    }
                                });

                                $.ajax({
                                    url: '<?= base_url("get_actual_punch_in_time") ?>',
                                    type: "POST",
                                    data: {
                                        punch_date: a,
                                        employee_id: employee_id
                                    },
                                    success: function(res) {
                                        let success = JSON.parse(res);
                                        if (success['punch_in']) {
                                            // Format punch_in to yyyy-MM-ddTHH:mm for datetime-local input
                                            let punchIn = new Date(success['punch_in']);
                                            let year = punchIn.getFullYear();
                                            let month = String(punchIn.getMonth() + 1).padStart(2, '0');
                                            let day = String(punchIn.getDate()).padStart(2, '0');
                                            let hours = String(punchIn.getHours()).padStart(2, '0');
                                            let minutes = String(punchIn.getMinutes()).padStart(2, '0');
                                            let formatted = `${year}-${month}-${day}T${hours}:${minutes}`;
                                            // $('#punch_in_time').val(formatted);
                                            let punchOut = new Date(success['punch_out']);
                                            let yearOut = punchOut.getFullYear();
                                            let monthOut = String(punchOut.getMonth() + 1).padStart(2, '0');
                                            let dayOut = String(punchOut.getDate()).padStart(2, '0');
                                            let hoursOut = String(punchOut.getHours()).padStart(2, '0');
                                            let minutesOut = String(punchOut.getMinutes()).padStart(2, '0');
                                            let formattedOut = `${yearOut}-${monthOut}-${dayOut}T${hoursOut}:${minutesOut}`;
                                            // $('#punch_out_time').val(formattedOut);
                                        }
                                    }
                                });
                            }
                        } else {
                            $('#add_miss_punch').modal('show');
                            var a = moment(start).format('YYYY-MM-DD');
                            var pp1 = a + "T10:00";
                            var pp2 = a + "T18:30";
                            $('#punch_in_time').val(pp1);
                            $('#punch_out_time').val(pp2);
                            $('#date_selected').val(a);
                        }
                    });
                } else {
                    document.getElementById('loaders5').style.display = "block";
                    get_login_details(start);
                    $("#login_btn").show();
                    $("#logout_btn").show();
                    $('#create_modal input[name=main_date]').val(moment(start).format('YYYY-MM-DD'));
                    $('#create_modal input[name=end_date]').val(moment(end).format('YYYY-MM-DD'));
                    $('#create_modal').modal('show');
                }
                $('#calendarIO1').fullCalendar('unselect');
            },
            events: function(start, end, timezone, callback) {
                // Fetch events dynamically
                fetchEvents(start, end,employee).then(function(events) {
                    callback(events);
                });
            },eventRender: function(event, element) {
                // Customize event rendering
                element.find('.fc-title').html(event.title);

            },
            dayRender:  function (date, cell) {
                // cell.css("background", "#ca2b368f");
                var d = new Date();
                var res = '';
                /*get_holidays_show(date).then(function (result_p) {
                    res = result_p;
                    if (res !== false) {
                        cell.css("background", "#ca2b368f");
                        cell.html(res);
                    } else {
                        cell.css("");
                    }
                });*/
                // var array_time1;
                var aa = '';
                var aa1 = '';
                var array_time;

            },
            eventAfterAllRender: function (view) {
                document.getElementById('loaders7').style.display = "none";
            }
        });
    }


    function fetchEvents(start, end, employee) {
        var employee = $("#employee_id").val();
        if (employee == "") {
            var employee = $("#session_emp_id").val();
        }
        var employee_name = $("#employee_id option:selected").text();
        $('#emp_header_name').empty();
        $('#emp_header_name').append(employee_name);
        $('#emp_req_att').val(employee);
        return new Promise(function(resolve, reject) {
            const LOGIN_TYPE_COLORS = {
                1: '#378006', // In Office - Green
                2: '#8A6D3B', // At Site Office - brown
                3: '#219b90' // Work From Home - Teal
            };
            $.ajax({
                url: '<?= base_url("DashboardController/get_login_detailsnew") ?>',
                type: "POST",
                data: {start_date: start.format(),end_date:end.format(),emp_id:employee},
                success: function (result) {
                    result = JSON.parse(result);
                    var array_time = [];
                    var events = [];
                    var holiday=result.holiday;
                    let companyEvent = result.event_array || {};
                    var hourTowork=result.hourTowork;
                    if(hourTowork == null || hourTowork == ''){
                        hourTowork='08:30:00';
                    }
                    $.each(result, function (key, success) {
                        var leave_statusT=['0'];                        
                        if (success.message == 'success') {
                            var login_type = success.selectLoginType;
                            var outside_inside_punchin = success.outside_inside_punchin;
                            if (success.status == 'intime_marked') {
                                var intime = success.intime;
                                var leave_status = success.leave_status;
                                var activity_status = success.activity_status;
                                var outtime='';
                                var regular_status = success.regular_status;
                                array_time[success.date] = [intime, '', leave_status, activity_status, regular_status];
                                var Ndate = success.date;
                                if(leave_status != 4){
                                    leave_statusT=get_leave_status(leave_status);
                                } else {
                                    leave_statusT=['0'];
                                }
                            } else if (success.status == 'attendace_marked') {
                                var intime = success.intime;
                                var leave_status = success.leave_status;
                                var activity_status = success.activity_status;
                                var outtime = success.outtime;
                                var regular_status = success.regular_status;
                                array_time[success.date] = [intime, outtime, leave_status, activity_status, regular_status];
                                var Ndate = success.date;
                                if(leave_status != 4){
                                    leave_statusT=get_leave_status(leave_status);
                                    
                                } else {
                                    leave_statusT=['0'];
                                }
                            } else {
                                var activity_status = success.activity_status;
                                var leave_status = success.leave_status;
                                var regular_status = success.regular_status;
                                var intime = '';
                                var outtime='';
                                array_time[success.date] = [0, 0, leave_status, activity_status, regular_status];
                                var Ndate = success.date;
                                if(leave_status != 4){
                                    leave_statusT=get_leave_status(leave_status);
                                } else {
                                    leave_statusT=['0'];
                                }
                            }
                            if(leave_statusT[0] != 0 ){
                                events.push( {
                                    title : leave_statusT[0],
                                    date : Ndate,
                                    backgroundColor: leave_statusT[1],
                                } );
                            } else {
                                var i_sts='';
                                if(intime != ''){
                                    var i_sts="<i class='fa fa-arrow-up' style='size: 5px'></i>"+intime;
                                }
                                var o_sts='';
                                if(outtime != ''){
                                    var o_sts=" <i class='fa fa-arrow-down' style='size: 5px'></i>"+outtime;
                                }else {
                                    var o_sts='';
                                }
                                var sts1 ='';

                                if ((activity_status == '0'  || regular_status === '0') && success.reason !== null ) {
                                  // var sts1 = "Regularized Req";
                                    var sts1 = '<i class=" a fa fa-user-times" title="Regularize Attendace Requested!" style="color:#bf8f37;"></i>';
                                }

                                if (activity_status  === '3' || regular_status === '1') {
                                    if(outside_inside_punchin == 'outside') {
                                        var b_color = '#219b90';
                                    } else {
                                        if(login_type==1 || login_type== null) {
                                            var b_color = '#378006'; // green
                                        } else if (login_type==2) {
                                            var b_color = '#8A6D3B'; // brown
                                        } else if (login_type==3) {
                                            var b_color = '#219b90'; // teal
                                        }
                                    }
                                    //  var sts1 = "Regularized Acpt";
                                    var sts1 = '<i class=" a fa fa-user-times" title="Regularize Attendace Accepted!" style="color:green;"></i>';
                                }

                                if (activity_status === '4' || regular_status  === '2') { // var sts1 = "Regularized Denied";
                                    var sts1 = '<i class=" a fa fa-user-times" title="Regularize Attendace Denied!" style="color:#af2626;"></i>';
                                }

                                if (isTimeDifferenceLessThanGivenHours(intime+" AM", outtime+" PM", hourTowork)) {
                                    if(outside_inside_punchin == 'outside') {
                                        var b_color = '#219b90';
                                        var color='black';
                                        var font_weight='600';

                                    } else {
                                        var b_color='#f3de06fc';
                                        var color='black';
                                        var font_weight='600';
                                    }
                                } else {
                                    // abhishek kumar mishra If outtime is before 5 PM, color should be yellow, else green
                                    let outHour = 0;
                                    if (outtime) {
                                        let outParts = outtime.split(':'); // Parse outtime, expected format: "HH:MM:SS"
                                        if (outParts.length >= 1) {
                                            outHour = parseInt(outParts[0], 10);
                                        }
                                    }
                                    

                                    if (outHour < 17) { // If outtime is before 5 PM (17:00), set yellow, else green
                                        if(outside_inside_punchin == 'outside') {
                                            var b_color = '#219b90';
                                            var color = '';
                                            var font_weight = '';

                                        } else {
                                            var b_color = '#f3de06fc';
                                            var color = '';
                                            var font_weight = '';
                                        }
                                    } else {
                                        if(outside_inside_punchin == 'outside') {
                                            var b_color = '#219b90';
                                            var color = '';
                                            var font_weight = '';
                                            if (login_type && LOGIN_TYPE_COLORS[login_type]) { // Apply login type color according to login_type
                                                b_color = LOGIN_TYPE_COLORS[login_type];
                                            }
                                        } else {
                                            var b_color = '#378006';
                                            var color = '';
                                            var font_weight = '';
                                            if (login_type && LOGIN_TYPE_COLORS[login_type]) { // Apply login type color according to login_type
                                                b_color = LOGIN_TYPE_COLORS[login_type];
                                            }

                                        }
                                    }
                                }
                                
                                if(outtime == ''){
                                    if(outside_inside_punchin == 'outside') {
                                        var b_color = '#219b90';
                                        var color='white';
                                        var font_weight='600';

                                    } else {
                                        var b_color='#f3de06fc';
                                        var color='black';
                                        var font_weight='600';

                                    }
                                }
                                events.push( {
                                    title : i_sts + o_sts ,
                                    date : Ndate,
                                    backgroundColor: b_color,
                                    textColor: color,
                                }, );
                                if(sts1 !== ""){
                                    events.push( {
                                        title :  sts1,
                                        date : Ndate,
                                        backgroundColor: "white",
                                        textColor: "black",
                                    });
                                }
                            }

                        }
                    });
                    $.each(holiday, function (key, data) {
                        if(data == ""){
                            var  datas="week off";
                        }else if(data == "-"){
                            var  datas="";
                        }else{
                            var   datas=data;
                        }
                        if(datas != ''){
                            events.push( {
                                title : datas.toLowerCase(),
                                date : key,
                                backgroundColor: '#dd4b39',
                                //  description: ''
                            } );
                        }

                    });
                    if (companyEvent && Object.keys(companyEvent).length > 0) {
                        $.each(companyEvent, function (eventDate, eventList) {
                            if (Array.isArray(eventList)) {
                                $.each(eventList, function (i, eventTitle) {
                                    events.push({
                                        title: eventTitle,
                                        date: eventDate,
                                        backgroundColor: '#3c8dbc',
                                        textColor: '#fff',
                                        allDay: true
                                    });
                                });
                            }
                        });
                    }
                    resolve(events);
                },
                error: function (error) {
                    reject(false);
                }
            });
        });
    }


    function selectLoginType(status, typeNo) {
        if(typeNo == 2) {
            $('#clientLoginLocationDetails').modal('show');

        } else {
            employee_login(status, typeNo);
        }
    }


    function selectLogoutType(status, typeNo) {
        if(typeNo == 2) {
            $('#clientLogoutLocationDetails').modal('show');

        } else {
            employee_login(status, typeNo);
        }
    }


    function get_login_details(start_date, punch_type) {
        var d = new Date(start_date);
        start_date = getFormattedString(d);
        $.ajax({
            url: '<?= base_url("DashboardController/get_login_details") ?>',
            type: "POST",
            data: {
                start_date: start_date,
                punch_type: punch_type
            },
            success: function (success) {
                success = JSON.parse(success);
                if (success.message == 'success') {
                    if (success.status == 'attendace_marked') { // when employee successfully punchin than this section will appear
                        var lat = $("#latitude_live").val();
                        document.getElementById('loaders5').style.display = "none";
                        $("#login_btn").hide();
                        $("#logout_btn").hide();
                        $("#time_div").show();
                        var div = document.getElementById('time_div');
                        let jsonIpAddress = JSON.parse(success.ipAddress);
                        div.innerHTML = '';
                        var intime1 = success.intime;
                        var date_arr = intime1.split(" ");
                        var date_aar2 = date_arr[0].split("-");
                        var intime = date_aar2[2] + "-" + date_aar2[1] + "-" + date_aar2[0] + " " + date_arr[1];
                        var out1 = success.outtime;
                        var date_arrr = out1.split(" ");
                        var date_aarr2 = date_arrr[0].split("-");
                        var outtime = date_aarr2[2] + "-" + date_aarr2[1] + "-" + date_aarr2[0] + " " + date_arrr[1];
                        var array_time = [intime, outtime];
                        div.innerHTML += '<div class="col-md-12"><h5>Attendance has been marked for today.</h5></div>';
                        var ipAddress = jsonIpAddress.org;
                        return array_time;
                        div.innerHTML += '<div class="col-md-12"><div class=""><lable>Out Time:</lable><input type="text" disabled="" value="' + outtime + '" class="form-control"></div></div>';
                    } else if (success.status == 'intime_marked') { // punchout time this call
                        // alert("abhishek mishra : punchout time this call : else if (success.status == 'intime_marked') ");
                        var intime1 = success.intime;
                        var date_arr = intime1.split(" ");
                        var date_aar2 = date_arr[0].split("-");
                        var intime = date_aar2[2] + "-" + date_aar2[1] + "-" + date_aar2[0] + " " + date_arr[1];
                        var lat = $("#latitude_live").val();
                        if (typeof lat === "undefined" || lat === "" || lat == 0) {
                            alert("Please on your gps location.");
                            $("#logout_btn").hide();
                            $("#login_btn").hide();
                        } else {
                            $("#logout_btn").show();
                            $("#login_btn").hide();
                        }
                        var div = document.getElementById('time_div');
                        div.innerHTML = '';
                        div.innerHTML += '<div class="col-md-4"><div class=""><div class=""><lable>In Time:</lable><input type="text" disabled="" value="' + success.intime + '" class="form-control"></div></div>';
                        document.getElementById('loaders5').style.display = "none";

                    } else if (success.status == 'not_marked') { // punchin time this call
                        // alert("abhishek mishra : punchin time this call : else if (success.status == 'not_marked') ");
                        var div = document.getElementById('time_div');
                        div.innerHTML = '';
                        $("#time_div").hide();
                        var lat = $("#latitude_live").val();
                        if (typeof lat === "undefined" || lat === "" || lat == 0) {
                            alert("Please on your gps location.");
                            $("#login_btn").hide();
                            $("#logout_btn").hide();
                        } else {
                            $("#login_btn").show();
                            $("#logout_btn").hide();
                        }
                        document.getElementById('loaders5').style.display = "none";

                    } else {
                        var div = document.getElementById('time_div');
                        div.innerHTML = '';
                        document.getElementById('loaders5').style.display = "none";
                    }

                    if (success.gps_off_allow == 0) {
                        var lat = $("#latitude_live").val();
                        if (lat == 0) {
                            $("#login_btn").hide();
                            $("#logout_btn").hide();
                        }
                    }
                } else {
                    $("#login_btn").hide();
                    $("#logout_btn").hide();
                    var div = document.getElementById('time_div');
                    document.getElementById('loaders5').style.display = "none";
                    div.innerHTML = '';
                }
            },
            error: function (error) {
                alert("something went to wrong.");
            }
        });
    }


    function get_login_details1(date) {
        var d = new Date(date);
        $('#currentMonth').empty();
        $('#monthNames').empty();
        cdate = d.getFullYear() + '-' + d.getMonth();
        $('#currentMonth').val(d.getFullYear() + '-' + d.getMonth());
        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        $('#monthNames').val(monthNames[d.getMonth() - 1] + ' ' + d.getFullYear());
        monthdate = monthNames[d.getMonth() - 1] + ' ' + d.getFullYear();
        var array_time = [];
        let myFirstPromise = new Promise((resolve, reject) => {
            date = getFormattedString(d);
            $.ajax({
                url: '<?= base_url("DashboardController/get_login_details1") ?>',
                type: "POST",
                data: {start_date: date},
                success: function (success) {
                    success = JSON.parse(success);
                    if (success.message == 'success') {
                        if (success.status == 'intime_marked') {
                            var intime1 = success.intime;
                            var leave_status = success.leave_status;
                            var activity_status = success.activity_status;
                            var datetimeNow = new Date(intime1);
                            var intime = get_ampm(datetimeNow);
                            var regular_status = success.regular_status;
                            array_time = [intime, 0, leave_status, activity_status, regular_status];
                            resolve(array_time);
                        } else if (success.status == 'attendace_marked') {
                            var intime1 = success.intime;
                            var leave_status = success.leave_status;
                            var activity_status = success.activity_status;
                            var datetimeNow = new Date(intime1);
                            var intime = get_ampm(datetimeNow);
                            var out1 = success.outtime;
                            var datetimeNow1 = new Date(out1);
                            var outtime = get_ampm(datetimeNow1);
                            var regular_status = success.regular_status;
                            array_time = [intime, outtime, leave_status, activity_status, regular_status];
                            resolve(array_time);
                        } else {
                            var activity_status = success.activity_status;
                            var leave_status = success.leave_status;
                            var regular_status = success.regular_status;
                            array_time = [0, 0, leave_status, activity_status, regular_status];
                            // console.log(array_time);
                            resolve(array_time);
                        }
                    } else {
                        resolve(false);
                    }
                },
                error: function (error) {
                    reject(false);                                                                toastr.error("something went to wrong");
                }
            });
        });
        return myFirstPromise;
    }


    function get_login_details_empwise(date, emp_id) {
        var array_time = [];
        let myFirstPromise = new Promise((resolve, reject) => {
            var d = new Date(date);
            date = getFormattedString(d);
            $.ajax({
                url: '<?= base_url("DashboardController/get_login_details1") ?>',
                type: "POST",
                data: {start_date: date, emp_id: emp_id},
                success: function (success) {
                    success = JSON.parse(success);
                    if (success.message == 'success') {
                        if (success.status == 'intime_marked') {
                            var intime1 = success.intime;
                            var leave_status = success.leave_status;
                            var activity_status = success.activity_status;
                            var datetimeNow = new Date(intime1);
                            var intime = get_ampm(datetimeNow);
                            var regular_status = success.regular_status;
                            array_time = [intime, 0, leave_status, activity_status, regular_status];
                            resolve(array_time);
                        } else if (success.status == 'attendace_marked') {
                            var intime1 = success.intime;
                            var leave_status = success.leave_status;
                            var activity_status = success.activity_status;
                            var datetimeNow = new Date(intime1);
                            var intime = get_ampm(datetimeNow);
                            var out1 = success.outtime;
                            var datetimeNow1 = new Date(out1);
                            var outtime = get_ampm(datetimeNow1);
                            var regular_status = success.regular_status;
                            array_time = [intime, outtime, leave_status, activity_status, regular_status];
                            resolve(array_time);
                        } else {
                            var activity_status = success.activity_status;
                            var leave_status = success.leave_status;
                            var regular_status = success.regular_status;
                            array_time = [0, 0, leave_status, activity_status, regular_status];
                            resolve(array_time);
                        }
                    } else {
                        resolve(false);
                    }
                },
                error: function (error) {
                    reject(false);                                                             toastr.error("something went to wrong");
                }
            });
        });
        return myFirstPromise;
    }


    function clientLoginLocationDetailSubmit(status, typeNumber) {
        let client_name = $("#login_client_name").val();
        employee_login(status, typeNumber, client_name);
    }


    function clientLogoutLocationDetailSubmit(status, typeNumber) {
        let client_name = $("#logout_client_name").val();
        employee_login(status, typeNumber, client_name);
    }


    function employee_login(status, typeNo, client_name=null, punch_type=null) {
        var latitude_live = document.getElementById("latitude_live").value;
        var longitude_live = document.getElementById("longitude_live").value;
        var shortaddress = document.getElementById("srtaddress").value;
        var address = document.getElementById("longaddress").value;
        $.ajax({
            url: '<?= base_url("DashboardController/emp_login_mbl") ?>',
            type: "POST",
            data: {
                status: status, // 'login' or 'logout'
                login_type: typeNo, // 1, 2, 3
                latitude_live: latitude_live,
                longitude_live: longitude_live,
                shortaddress: shortaddress,
                address: address,
                client_name: client_name,
                selected_punch_type: selectedPunchType
            },
            success: function (success) {
                success = JSON.parse(success);
                if (success.message == 'success') {
                    alert(success.body);
                    $('#create_modal').modal('toggle');
                    location.reload();
                } else {
                    alert(success.body);
                }
            },
            error: function (error) {
                // console.log(error);
                alert("something went to wrong.");
                // toastr.error("something went to wrong");
            }
        });

    }


    function getFormattedString(d) {
        return d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + ' ' + d.toString().split(' ')[4];
    }



    function address_more() {
        var maxLength = 6;
        $(".show-read-more").each(function () {
            var myStr = $(this).text();
            if ($.trim(myStr).length > maxLength) {
                var newStr = myStr.substring(0, maxLength);
                var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                $(this).empty().html(newStr);
                $(this).append(' <a href="javascript:void(0);" class="read-more" style="color:blue">more...</a>');
                $(this).append('<span class="more-text">' + removedStr + '</span>');
            }
        });
        $(".read-more").click(function () {
            $(this).siblings(".more-text").contents().unwrap();
            $(this).remove();
        });
    }


    function get_all_data() {
        $('#second_table1').DataTable().destroy();
        var user_id = $("#emp_req_att").val();
        var mon_year1 = "";
        var date = getMonthCalendar();
        var dateObj = new Date(date);
        var month1 = dateObj.getMonth() + 1;
        var day1 = dateObj.getUTCDate();
        var year1 = dateObj.getFullYear();
        newdate = year1 + "/" + month1 + "/" + day1;
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        $("#emp_req_mon").val(month1);
        $("#emp_req_year").val(year1);
        $("#monthNames12").val(monthNames[month1]);
        if (user_id == "") {
            var user_id = $("#emp_login").val();
        }
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_current_month_data") ?>",
            dataType: "json",
            data: {user_id: user_id, month1: month1, year1: year1},
            success: function (result) {
                if (result.status == true) {
                    //$("#first_table").hide();
                    $("#full_att_data1").html("");
                    $("#full_att_data1").html(result.data);
                    address_more();
                    //dataTable.fnDraw();
                    //dataTable.fnDraw();
                    //	$('#second_table1').dataTable().clear().draw();
                    /* var table =  $('#second_table1').DataTable({
                                      paging: false,
                                      //orderable:false,
                                   //responsive:true,
                                     // paging: false,
                                       // bFilter: false,
                                       // ordering: false,
                                       searching: false,
                                       // dom: 't'
                                   // order: [[ 1, "asc" ]]
                                   columnDefs: [
                                   { orderable: false, targets: [0,1,2,3,4,5] }
                                 ]

                                   });

                                    new $.fn.dataTable.FixedHeader( table );
                                    $.fn.dataTable.ext.errMode = 'none'; */
                    //dataTable.fnDestroy();

                    //get_missing_punch_data_hr();
                } else {
                    alert(result.body);
                }
            }

        });

    }


    function add_leave_btn() {
        $("#add_miss_punch").modal('hide');
        $("#myModal").modal('show');
    }


    $(document).ready(function () {
        var maxLength = 10;
        $(".show-read-more").each(function () {
            var myStr = $(this).text();
            if ($.trim(myStr).length > maxLength) {
                var newStr = myStr.substring(0, maxLength);
                var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                $(this).empty().html(newStr);
                $(this).append(' <a href="javascript:void(0);" class="read-more" style="color:blue">more...</a>');
                $(this).append('<span class="more-text">' + removedStr + '</span>');
            }
        });
        $(".read-more").click(function () {
            $(this).siblings(".more-text").contents().unwrap();
            $(this).remove();
        });
    });


    function approve_leave(id1, leave_id_act) {
        var leave_id = id1;
        var result = confirm("Want to Approve this Leave?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/approve_emp_leave") ?>",
                dataType: "json",
                data: {leave_id: leave_id, leave_id_act: leave_id_act},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leave Approved successfully');
                        location.reload();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            // location.reload();
        }
    }


    function deny_all() {
        var user_id = document.getElementById('deny_id1').value;
        var leave_id = document.getElementById('leave_id').value;
        var den_id = document.getElementById('den_id1').value;
        var result = confirm("Want to Deny All Leaves?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/deny_all_leave") ?>",
                dataType: "json",
                data: {user_id: user_id, leave_id: leave_id, den_id: den_id},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leaves Denied successfully');
                        location.reload();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            location.reload();
        }
    }


    $('#myModal1').on('show.bs.modal', function (e) {
        var deny_id = $(e.relatedTarget).data('dvalue');
        document.getElementById('deny_id1').value = deny_id;
    });
    $('#myModal1').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });

    $('#mydenyModal').on('show.bs.modal', function (e) {
        var deny_id = $(e.relatedTarget).data('myvalue');
        document.getElementById('deny_id').value = deny_id;
    });
    $('#mydenyModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });


    function approve_all(id) {
        var leave_id = document.getElementById('leave_id').value;
        var user_id = id;
        var result = confirm("Want to Approve All Leaves?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/approve_all_leave") ?>",
                dataType: "json",
                data: {user_id: user_id, leave_id: leave_id},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leaves Approved successfully');
                        location.reload();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            // location.reload();
        }
    }


    function deny_leave() {
        var leave_id = document.getElementById('deny_id').value;
        var leave_id_act = document.getElementById('leave_id').value;
        var result = confirm("Want to Deny this Leave?");
        if (result) {
            var den_id = document.getElementById('den_id').value;
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/deny_emp_leave") ?>",
                dataType: "json",
                data: {leave_id: leave_id, den_id: den_id, leave_id_act: leave_id_act},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leave Denied successfully');
                        location.reload();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            location.reload();
        }
    }


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
                    var leave_aprv_validation_multiple = result.leave_aprv_validation_multiple;
                    for (i = 0; i < data.length; i++) {
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
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default" data-toggle="modal" title="Deny!" name="deny_leave" id="deny_leave" data-target="#mydenyModal" data-myvalue="' + data[i]['id'] + '"><i class="fa fa-close"></i></a>';
                        } else if (data[i]['status'] === '2') {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default" data-toggle="modal"title="Deny!" name="deny_leave" id="deny_leave" data-target="#mydenyModal" data-myvalue="' + data[i]['id'] + '"><i class="fa fa-close"></i></a>';
                        } else if (data[i]['status'] === '3') {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close"></i></a>';
                        } else {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close"></i></a>';
                        }
                        var today = new Date();
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = yyyy + '-' + mm + '-' + dd + ' 00:00:00';
                        var date = data[i]['leave_date'];
                        if (date < today) {
                            var approve = '<a class="btn btn-circle green btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(' + data[i]['id'] + ');"><i class="fa fa-check"></i></a>';
                            var deny = '<a class="btn btn-circle red btn-icon-only btn-default" disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close"></i></a>';
                        }
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
                            '<td>' + approve + deny + '</td>';
                    }
                } else {
                }
            }
        });
    });


    function approve_m_rqst(id, user_id, date) {
        var result = confirm("Want to Approve this Request?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/approve_m_rqst") ?>",
                dataType: "json",
                data: {id: id, user_id: user_id, date: date},
                success: function (result) {
                    if (result.message == 'success') {
                        alert(result.body);
                        location.reload();
                        //get_missing_punch_data_hr();
                    } else {
                        alert(result.body);
                    }
                }
            });
        } else {
            get_missing_punch_data_hr();
        }
    }


    function deny_m_rqst(id) {
        var result = confirm("Want to Deny this Request?");
        if (result) {

            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/deny_m_rqst") ?>",
                dataType: "json",
                data: {id: id},
                success: function (result) {
                    if (result.message == 'success') {
                        alert(result.body);
                        get_missing_punch_data_hr();
                        loaction.reload();
                    } else {
                        alert(result.body);
                    }
                }

            });
        } else {
            get_missing_punch_data_hr();
        }
    }


    function get_missing_punch_data_hr() {
        var emp_id = $('#emp_req_att').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_missing_punch_data_hr") ?>",
            data: {emp_id: emp_id},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                $('#missign_punching_hr').empty();
                if (result.message == 'success') {
                    $('#missign_punching_hr').append(data);
                    $('#sample2').DataTable({paging: true, destroy: true});

                } else {
                    $('#missign_punching_hr').append("");
                }
            },
        });
    }


    function get_missing_punch_data() {
        var emp_id = $('#emp_req_att').val();
        var date = $('#currentMonth').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_missing_punch_data") ?>",
            data: {emp_id: emp_id, date: date},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                $('#missign_punching').empty();
                if (result.message == 'success') {
                    $('#missign_punching').append(data);
                    var table = $('#sample30').DataTable({
                        paging: true,
                        responsive: true
                    });
                    new $.fn.dataTable.FixedHeader(table);
                    $('a.morelink').css({'text-decoration': 'none', 'outline': 'none'});
                    $('a.morecontent span').css({'display': 'none'});
                    $('.comment').css({'widh': '300px'});
                    $('.btn-circle').css({
                        'widh': '30px',
                        'height': '30px',
                        'padding': '6px 0px',
                        'border-radius': '15px',
                        'text-align': 'center',
                        'font-size': '12px',
                        'line-height': '1.42857'
                    });
                    $.fn.dataTable.ext.errMode = 'none';
                } else {
                    $('#missign_punching').append("");
                }
            },
        });
    }


    function get_outside_app() {
        var emp_id = $('#emp_req_att').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_outside_app") ?>",
            data: {emp_id: emp_id},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                if (result.message == 'success') {
                    $('#outside_app').hide();

                } else {
                    $('#outside_app').show();

                }
            },
        });
    }


    function get_na_data() {
        var emp_id = $('#emp_req_att').val();
        var date = $('#currentMonth').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_na_data") ?>",
            data: {emp_id: emp_id, date: date},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                $('#na_punching').html("");
                if (result.message == 'success') {
                    $('#na_punching').html(data);
                    var table = $('#samplenadata').DataTable({
                        paging: true,
                        responsive: true
                    });
                    new $.fn.dataTable.FixedHeader(table);
                    $('a.morelink').css({'text-decoration': 'none', 'outline': 'none'});
                    $('a.morecontent span').css({'display': 'none'});
                    $('.comment').css({'widh': '300px'});
                    $('.btn-circle').css({
                        'widh': '30px',
                        'height': '30px',
                        'padding': '6px 0px',
                        'border-radius': '15px',
                        'text-align': 'center',
                        'font-size': '12px',
                        'line-height': '1.42857'
                    });
                    $.fn.dataTable.ext.errMode = 'none';

                } else {
                    $('#na_punching').html("");
                }
            },
        });
    }


    function get_full_att_data() {
        var emp_id = $('#emp_req_att').val();
        var date = $('#currentMonth').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_full_att_data") ?>",
            data: {emp_id: emp_id, date: date},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var full_att = result.result;
                full_att.sort(function (a, b) {
                    return new Date(a.punch_in).getTime() - new Date(b.punch_in).getTime()
                });
                var data = '';
                for (k = 0; k < full_att.length; k++) {
                    data += '<tr>' +
                        '<td>' + full_att[k]['Type'] + '</td>';
                    if (full_att[k]['punch_out'] != null && full_att[k]['punch_out'] != '') {
                        data += '<td>' + full_att[k]['punch_in'] + '</td>';
                    } else {
                        data += '<td>--</td>';
                    }
                    if (full_att[k]['punch_out'] != null && full_att[k]['punch_out'] != '') {
                        data += '<td>' + full_att[k]['punch_out'] + '</td>';
                    } else {

                        data += '<td>' + full_att[k]['punch_in'] + '</td>';
                    }
                    data += '<td ><p class="show-read-more">' + full_att[k]['location'] + '</p></td>' +
                        '<td>' + full_att[k]['regular_status'] + '</td>' +

                        '</tr>';
                }

                var table = $('#full_data1').DataTable({
                    paging: true,
                    responsive: true
                });

                $('#full_att_data').html("");
                if (result.message == 'success') {
                    $('#full_att_data').html(data);
                    var maxLength = 10;
                    $(".show-read-more").each(function () {
                        var myStr = $(this).text();
                        if ($.trim(myStr).length > maxLength) {
                            var newStr = myStr.substring(0, maxLength);
                            var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                            $(this).empty().html(newStr);
                            $(this).append(' <a href="javascript:void(0);" class="read-more" style="color:blue">more...</a>');
                            $(this).append('<span class="more-text">' + removedStr + '</span>');
                        }
                    });
                    $(".read-more").click(function () {
                        $(this).siblings(".more-text").contents().unwrap();
                        $(this).remove();
                    });
                    new $.fn.dataTable.FixedHeader(table);
                    $('a.morelink').css({'text-decoration': 'none', 'outline': 'none'});
                    $('a.morecontent span').css({'display': 'none'});
                    $('.comment').css({'widh': '300px'});
                    $('.btn-circle').css({
                        'widh': '30px',
                        'height': '30px',
                        'padding': '6px 0px',
                        'border-radius': '15px',
                        'text-align': 'center',
                        'font-size': '12px',
                        'line-height': '1.42857'
                    });
                    $.fn.dataTable.ext.errMode = 'none';
                } else {
                    $('#full_att_data').html("");
                }
            },
        });
    }


    function get_current_month_att() {
        var emp_id = $('#emp_req_att').val();
        var date = $('#currentMonth').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_current_month_att") ?>",
            data: {emp_id: emp_id, date: date},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                $('#att_punching').empty();
                if (result.message == 'success') {
                    $('#att_punching').append(data);
                    var table = $('#sampleatt').DataTable({
                        paging: true,
                        responsive: true
                    });
                    new $.fn.dataTable.FixedHeader(table);
                    $('a.morelink').css({'text-decoration': 'none', 'outline': 'none'});
                    $('a.morecontent span').css({'display': 'none'});
                    $('.comment').css({'widh': '300px'});
                    $('.btn-circle').css({
                        'widh': '30px',
                        'height': '30px',
                        'padding': '6px 0px',
                        'border-radius': '15px',
                        'text-align': 'center',
                        'font-size': '12px',
                        'line-height': '1.42857'
                    });
                    $.fn.dataTable.ext.errMode = 'none';
                } else {
                    $('#att_punching').append("");
                }
            },
        });
    }


    function get_current_out_month_att() {
        var emp_id = $('#emp_req_att').val();
        var date = $('#currentMonth').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_current_out_month_att") ?>",
            data: {emp_id: emp_id, date: date},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                if (result.message == 'success') {
                    $('#out_punching').html(data);
                    var table = $('#sample2').DataTable({

                        paging: true,
                        responsive: true
                    });
                    $('a.morelink').css({'text-decoration': 'none', 'outline': 'none'});
                    $('a.morecontent span').css({'display': 'none'});
                    $('.comment').css({'widh': '300px'});
                    $('.btn-circle').css({
                        'widh': '30px',
                        'height': '30px',
                        'padding': '6px 0px',
                        'border-radius': '15px',
                        'text-align': 'center',
                        'font-size': '12px',
                        'line-height': '1.42857'
                    });
                    $.fn.dataTable.ext.errMode = 'none';
                    new $.fn.dataTable.FixedHeader(table);
                } else {
                    $('#out_punching').html("");
                }
            },
        });

    }


    function get_overtime_data() {
        var emp_id = $('#emp_req_att').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("ServiceRequestController/get_overtimeData") ?>",
            data: {emp_id: emp_id},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.data;
                if (result.message == 'success') {
                    $('#overtimedata').html(data);
                    $('#sampleovertime').DataTable({paging: true, destroy: true});

                } else {
                    $('#overtimedata').html("");
                }
            },
        });
    }


    function action_r_rq(id, status) {
        if (status == 1) {
            var msg = "Want to Approve this Request?";
        } else {
            var msg = "Want to Deny this Request?";
        }
        var result = confirm(msg);
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("ServiceRequestController/action_r_rq") ?>",
                dataType: "json",
                data: {id: id, status: status},
                success: function (result) {
                    if (result.message === "success") {
                        alert(result.body);
                        get_missing_punch_data_hr();
                        get_missing_punch_data();
                        loaction.reload();
                    } else {
                        alert(result.body);
                    }
                }

            });
        } else {
            get_missing_punch_data_hr();
        }
    }


    function modal_open() {
        let da = $('#myModal').modal('show');
        var a = 0;
        get_cancel_leave_btn(a);
    }


    function view_file1(id) {
        var x = document.getElementById("multiple");
        var y = document.getElementById("single");
        if (id === 'multiple') {

            x.style.display = "block";
            y.style.display = "none";
        } else if (id === 'single') {

            x.style.display = "none";
            y.style.display = "block";
        }

    }


    function get_data_employee_wiseNew() {
        var employee = $("#employee_id").val();
        calendar_data(employee);
    }


    function get_data_employee_wise() {
        $('#emp_attendance_btn').show();
        var employee = $("#employee_id").val();
        if (employee == "") {
            var employee = $("#session_emp_id").val();
        }
        var employee_name = $("#employee_id option:selected").text();
        $('#emp_header_name').empty();
        $('#emp_header_name').append(employee_name);
        $('#emp_req_att').val(employee);
        var emp_login = document.getElementById("emp_login").value;
        if (employee == emp_login) {
            location.reload();
        } else {
            $('#calendarIO2').fullCalendar('destroy');
            $("#calendarIO1").hide();
            $('.date-picker').datepicker();
            var sta_count = 0;
            $('#calendarIO2').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    defaultView: 'month',
                    right: 'month,basicWeek,basicDay'

                },
                selectable: true,
                select: function (start, end, allDay) {

                },
                dayRender: function (date, cell) {
                    var d = new Date();
                    var getTot = daysInMonth(date.get('month'), date.get('year')); // Get total days in a month
                    var res;
                    get_holidays_show(date,employee).then(function (result_p) {
                        res = result_p;

                        if (res == true) {
                            cell.css("background", "#ca2b368f");

                        } else {
                            cell.css("");
                        }
                    });

                    var aa = '';
                    var aa1 = '';
                    var array_time;
                    get_login_details_empwise(date, employee).then(function (result) {
                            array_time = result;
                            if (array_time !== false) {
                                if (Array.isArray(array_time) == true) {
                                    var sts1 = '';
                                    if (array_time[2] == 1) {
                                        var sts = '<i class=" a fa fa-home" title="Leave Requested!" style="font-size: 11px;color:#bf8f37"></i>';
                                    } else if (array_time[2] == 2) {
                                        var sts = '<i class=" a fa fa-home" title="Leave Accepted!" style="font-size: 11px;color:green"></i>';
                                    } else if (array_time[2] == 3) {
                                        var sts = '<i class=" a fa fa-home" title="Leave Denied!" style="font-size: 11px;color:#af2626"></i>';
                                    } else {
                                        var sts = '';
                                    }
                                    if (array_time[3] === '1' || array_time[4] === '0') {
                                        var sts1 = '<i class=" a fa fa-user-times" title="Regularize Attendace Requested!" style="font-size: 11px;color:#bf8f37"></i>';
                                    }
                                    if (array_time[3] === '3' || array_time[4] === '1') {

                                        var sts1 = '<i class=" a fa fa-user-times" title="Regularize Attendace Accepted!" style="font-size: 11px;color:green"></i>';
                                    }
                                    if (array_time[3] === '4' || array_time[4] === '2') {

                                        var sts1 = '<i class=" a fa fa-user-times" title="Regularize Attendace Denied!" style="font-size: 11px;color:#af2626"></i>';
                                    }

                                    if (array_time[0] == 0 && array_time[1] == 0) {

                                        var b = '';
                                        var c = '';
                                    } else {
                                        var b = "<span class='b' style='font-size:13px; color:#2973fb;'>" + array_time[0] + "</span><br>";
                                        var c = "<span class='b' style='font-size:13px; color:#c23426fa;'>" + array_time[1] + "</span>";
                                    }
                                    if (array_time[0] != 0 && array_time[1] == 0) {

                                        var b = " <span class='b' style='font-size:13px; color:#2973fb;'>" + array_time[0] + "</span><br>";
                                        var c = '';
                                    }
                                    cell.html(b + c
                                        + "<span class='b'  style='font-size:13px; '>" + sts + "</span>" +
                                        "<span class='b'  style='font-size:13px; '>" + sts1 + "</span>");                                                                               cell.html();
                                } else {
                                    cell.html(aa + aa1);
                                }
                            } else {
                                cell.html(aa + aa1);
                            }

                        }
                    );
                },
                eventAfterAllRender: function (view) {
                    document.getElementById('loaders7').style.display = "none";
                }
            });
            $('#calendarIO2').fullCalendar('removeEventSource');
            $('#calendarIO2').fullCalendar('addEventSource');

        }
    }


    $("#missing_punch_form").validate({
        rules: {
            "punch_in_time": {
                required: true
            },
            "punch_out_time": {
                required: true
            },
            "reason_missing": {
                required: true
            },
        },
        errorElement: "span",
        submitHandler: function (form) {
            $.ajax({
                url: '<?= base_url("ServiceRequestController/AddMissingPunch") ?>',
                type: "POST",
                data: $("#missing_punch_form").serialize(),
                success: function (success) {
                    success = JSON.parse(success);
                    if (success.message === "success") {
                        $("#missing_punch_form")[0].reset();
                        $("#add_miss_punch").toggle();
                        location.reload();
                    } else {
                        alert(success.body);
                    }
                },
                error: function (error) {
                    toastr.error(success.body);
                    alert(error);
                    errorNotify("something went to wrong");
                }
            });
        }
    });


    function rqst_leave() {
        $.ajax({
            type: "POST",
            url: "<?= base_url("Leave_management/create_leave_req") ?>",
            dataType: "json",
            data: $("#leave_request_form").serialize(),
            success: function (result) {
                if (result.status === true) {
                    alert('Request Sent Successfully');
                    location.reload();
                } else {
                    // $('#message').html(result.error);
                    $('#' + result.id + '_error').html(result.error);
                }
            },
            error: function (result) {
                if (result.status === 500) {
                    alert('Internal error: ' + result.responseText);
                } else {
                    alert('Unexpected error.');
                }
            }
        });
    }


    function get_cancel_leave_btn(leave_date_selected) {
        var user_id = document.getElementById('emp_login').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("Leave_management/get_leave_status_datewise") ?>",
            dataType: "json",
            data: {leave_date_selected: leave_date_selected, user_id: user_id},
            success: function (result) {
                $("#cancel_leave_btn").html("");
                if (result.message === "success") {
                    var leave_status = result.leave_status;
                    $("#cancel_leave_btn").html(leave_status); //btn_leave_rq
                    $("#leave_type_data").hide();
                    $("#btn_leave_rq").hide();
                    return;
                } else {
                    $("#cancel_leave_btn").html(leave_status);
                    $("#leave_type_data").show();
                    $("#btn_leave_rq").show();
                    return;
                }
            }

        });
    }


    $('#myModal').on('show.bs.modal', function () {
        var designation_id = document.getElementById('designation').value;
        var firm_id = document.getElementById('firm_id').value;
        var boss_id = document.getElementById('boss_id').value;
        var user_id = document.getElementById('user').value;
        $.ajax({
            type: 'post',
            url: "<?= base_url("Leave_management/get_type_leave_hr") ?>", //Here you will fetch records
            data: {designation_id: designation_id, firm_id: firm_id, boss_id: boss_id, user_id: user_id,}, //Pass $id
            datatype: "json",
            success: function (result) {
                var ele = document.getElementById('leave_type_data');
                var ele1 = document.getElementById('btn_leave_rq');
                ele.innerHTML = "";
                ele1.innerHTML = "";
                obj = JSON.parse(result);
                if (obj.message === 'success') {
                    var data1 = obj.leave_type_data;
                    var leave_status = obj.leave_status;
                    $("#cancel_leave_btn").html(leave_status);
                    var remaining_with_pay_leaves = obj.remaining_with_pay_leaves;
                    option = "";
                    if (data1[0]['type1'] !== '') {
                        var type1 = data1[0]['type1'];
                        var res1 = type1.split(":");
                        var leave_tyep1 = res1[0];
                        var num_days1 = res1[1];
                        option += '<option value="' + leave_tyep1 + '">' + leave_tyep1 + '</option>';
                    } else {
                        var type1 = data1[0]['type1'];
                        var res1 = type1.split(":");
                        var leave_tyep1 = res2[0];
                        var num_days1 = res1[1];
                        option += '<option value="PL">PL</option>';
                    }
                    
                    if (data1[0]['type2'] !== '') {
                        var type2 = data1[0]['type2'];
                        var res2 = type2.split(":");
                        var leave_tyep2 = res2[0];
                        var num_days2 = res2[1];
                        option += '<option value="' + leave_tyep2 + '">' + leave_tyep2 + '</option>';
                    } else {
                        var type2 = data1[0]['type2'];
                        var res2 = type2.split(":");
                        var leave_tyep2 = res2[0];
                        var num_days2 = res2[1];
                        option += '<option value="EL">EL</option>';
                    }
                    
                    if (data1[0]['type3'] !== '') {
                        var type3 = data1[0]['type3'];
                        var res3 = type3.split(":");
                        var leave_tyep3 = res3[0];
                        var num_days3 = res3[1];
                        option += '<option value="' + leave_tyep3 + '">' + leave_tyep3 + '</option>';
                    } else {

                    }
                    if (data1[0]['type4'] !== '') {
                        var type4 = data1[0]['type4'];
                        var res4 = type4.split(":");
                        var leave_tyep4 = res4[0];
                        var num_days4 = res4[1];
                        option += '<option value="' + leave_tyep4 + '">' + leave_tyep4 + '</option>';
                    } else {

                    }
                    if (data1[0]['type5'] !== '') {
                        var type5 = data1[0]['type5'];
                        var res5 = type5.split(":");
                        var leave_tyep5 = res5[0];
                        var num_days5 = res5[1];
                        option += '<option value="' + leave_tyep5 + '">' + leave_tyep5 + '</option>';
                    } else {
                        // var leave_tyep5 = '';
                    }
                    if (data1[0]['type6'] !== '') {
                        var type6 = data1[0]['type6'];
                        var res6 = type6.split(":");
                        var leave_tyep6 = res6[0];
                        var num_days6 = res6[1];
                        option += '<option value="' + leave_tyep6 + '">' + leave_tyep6 + '</option>';
                    } else {

                    }
                    if (data1[0]['type7'] !== '') {
                        var type7 = data1[0]['type7'];
                        var res7 = type7.split(":");
                        var leave_tyep7 = res7[0];
                        var num_days7 = res7[1];
                        option += '<option value="' + leave_tyep7 + '">' + leave_tyep7 + '</option>';
                    } else {
                        leave_tyep7 = '';
                    }
                    
                    var single = 'single';
                    var multiple = 'multiple';
                    var leave_type = 'leave_type';
                    var leave_date_single = 'leave_date_single';
                    var leave_date_multiple_first = 'leave_date_multiple_first';
                    var leave_date_multiple_second = 'leave_date_multiple_second';
                    // POPULATE SELECT ELEMENT WITH JSON.
                    ele1.innerHTML = ele1.innerHTML +
                        '<button type="button" id="leave_req_btn1" name="leave_req_btn1" onclick="rqst_leave()"class="btn btn-info">Request Leave</button>' +
                        '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                    ele.innerHTML = ele.innerHTML +                    
                        '<div class="form-group">' +
                        '<div class="row">' +
                        '<div class="col-md-6">' +
                        '<label>Select Leave Type</label> ' +
                        '<div class="input-group">' +
                        '<span class="input-group-addon">' +
                        ' <i class="fa fa-map-signs"></i>' +
                        '</span>' +
                        '<select name="leave_type" id="leave_type" onchange="remove_error(\'' + leave_type + '\');"  class="form-control m-select2 m-select2-general" onchange=""> ' +
                        //'<option value="">Select Option</option>' +
                        '<option value="">Select Option</option>' + option +
                        '</select>' +
                        '</div>' +
                        '<span class="required" id="leave_type_error"></span>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<div class="col-md-6">' +
                        '<div class="input-group">' +
                        '<div class="icheck-inline">' +
                        '<label>Select Day Type</label>' +
                        '<label>' +
                        '<input type="radio" id="day_type" name="day_type" value="0" checked  data-checkbox="icheckbox_flat-grey" onclick="view_file1(\'' + single + '\');"> Single </label>' +
                        '<label>' +
                        '<input type="radio" id="day_type" name="day_type" value="1" data-checkbox="icheckbox_flat-grey" onclick="view_file1(\'' + multiple + '\')"> Multiple </label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '<div id="single">' +
                        '<label>Select Date</label>' +
                        '<div class="input-group">' +
                        '<span class="input-group-addon">' +
                        '<i class="fa fa-calendar"></i>' +
                        '</span>' +
                        '<input type="Date"  name="leave_date_single" onchange="remove_error(\'' + leave_date_single + '\');check_date();" id="leave_date_single"ata-required="1" class="form-control" placeholder="Completion Date"> ' +
                        '</div>' +
                        '<span class="required" style="color:red;" id="leave_date_single_error"></span>' +
                        '</div>' +
                        '<div id="multiple" style="display: none;">' +
                        '<label>From</label>' +
                        '<div class="input-group">' +
                        '<span class="input-group-addon">' +
                        '<i class="fa fa-calendar"></i>' +
                        '</span>' +
                        '<input type="Date" min="" name="leave_date_multiple_first" onchange="remove_error(\'' + leave_date_multiple_first + '\'); onDateChange();" id="leave_date_multiple_first"ata-required="1" class="form-control" placeholder="Completion Date"> ' +
                        '</div>' +
                        '<span class="required" style="color:red;" id="leave_date_multiple_first_error"></span><br>' +
                        '<label>To</label>' +
                        '<div class="input-group">' +
                        '<span class="input-group-addon">' +
                        '<i class="fa fa-calendar"></i>' +
                        '</span>' +
                        '<input type="Date" min="" name="leave_date_multiple_second" onchange="remove_error(\'' + leave_date_multiple_second + '\');check_date_multiple();" id="leave_date_multiple_second"ata-required="1" class="form-control" placeholder="Completion Date"> ' +
                        '</div>' +
                        '<span class="required" id="leave_date_multiple_first_error"></span>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<div class="col-md-6">' +
                        '<div id="leave_remaining"></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    //
                } else {

                }
            }
        });
    });
    

    $('#myModal').on('hidden.bs.modal', function () {
        $('#leave_conf_tbl').remove();
        $(this).find('form').trigger('reset');
    });

    function remaining_leave() {
        var leave_type = document.getElementById('leave_type').value;
        var designation_id = document.getElementById('designation').value;
        var firm_id = document.getElementById('firm_id').value;
        var user_id = document.getElementById('user').value;
        var ele1 = document.getElementById('leave_remaining');
        $.ajax({
            type: "POST",
            url: "<?= base_url("Leave_management/fetch_remain_leave_hr") ?>",
            dataType: "json",
            data: {leave_type: leave_type, designation_id: designation_id, firm_id: firm_id, user_id: user_id},
            success: function (result) {
                ele1.innerHTML = '';
                if (result.message === 'success') {
                    var data1 = result.total_leaves;
                    var data2 = result.taken_leaves;
                    var data3 = result.req_bfr;
                    var data4 = result.aprv_bfr;
                    ele1.innerHTML = ele1.innerHTML +
                        '<lable>Leaves Allow:</lable>' +
                        '<input type="text" id="leave_allow" name="leave_allow" class="form-control" disabled="" value="' + data1 + '">' +
                        '<lable>Leaves Taken:</lable>' +
                        '<input type="text" id="leave_taken" name="leave_taken" class="form-control" disabled="" value="' + data2 + '">' +
                        '<input type="hidden" id="request_leave_before" name="request_leave_before"class="form-control"  value="' + data3 + '">' +
                        '<input type="hidden" d="approve_leave_before" name="approve_leave_before" class="form-control" value="' + data4 + '">';
                } else {
                }
            }
        });
    }

    function remove_error(id) {
        $('#' + id + '_error').html("");
    }

    function del_leave(id) {
        var leave_id = id;
        var result = confirm("Want to delete this Leave?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/delete_leave") ?>",
                dataType: "json",
                data: {leave_id: leave_id},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leave Deleted successfully');
                        location.reload();
                    } else {
                        alert('something went wrong');
                    }
                }

            });
        } else {
            //        location.reload();
        }
    }

    function cancel_leave_a(id1, date) {
        var leave_id = id1;
        var user_id = document.getElementById('emp_login').value;
        var result = confirm("Want To Cancel This Leave?");
        if (result) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("Leave_management/cancl_leave") ?>",
                dataType: "json",
                data: {leave_id: leave_id, date: date, user_id: user_id},
                success: function (result) {
                    if (result.status === true) {
                        alert('Leave Cancel Successfully');
                        location.reload();
                    } else {
                        alert('Something Went Wrong');
                    }
                }

            });
        } else {
            //        location.reload();
        }
    }


    var getDateArray = function (start, end) {
        var arr = new Array();
        var dt = new Date(start);
        while (dt <= end) {
            arr.push(new Date(dt));
            dt.setDate(dt.getDate() + 1);
        }
        return arr;
    }

    function convert(str) {
        var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
        //    return [ date.getFullYear(), mnth, day ].join("-");
        return [day, mnth, date.getFullYear()].join("-");
    }


    function check_with_pay_leave() {
        var remain_with_pay = document.getElementById('leave_allow').value;
        var leave_taken = document.getElementById('leave_taken').value;
        leave_taken++;
        //        var leave_taken_new = document.getElementById('leave_taken').value = leave_taken++;
        if (remain_with_pay <= leave_taken) {
            alert("You can not apply for with pay leave of this type.");
            document.getElementById('with_pay_single').checked = false;
        }
    }

    function check_with_pay_leave_multiple(i) {
        var remain_with_pay = document.getElementById('leave_allow').value;
        var leave_taken = document.getElementById('leave_taken').value;
        if (remain_with_pay <= leave_taken) {
            alert("You can not apply for with pay leave of this type.");
            document.getElementById('without_pay' + i).checked = true;
        } else {
            leave_taken++;
            document.getElementById('leave_taken').value = leave_taken++;
        }


    }


    function check_date() {
        var leave_date_single = document.getElementById('leave_date_single').value;
        var user_id = document.getElementById('user').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("Leave_management/check_date_present") ?>",
            dataType: "json",
            data: {leave_date_single: leave_date_single, user_id: user_id},
            success: function (result) {
                if (result.status === true) {
                    alert('Leave for ' + leave_date_single + ' is aleready created.');
                    document.getElementById('leave_date_single').value = 0;
                    document.getElementById("leave_conf_tbl").style.display = "none";
                    //return;
                    //location.reload();
                }
            },
        });
    }

    function check_date_multiple() {
        var leave_date_multiple_first = document.getElementById('leave_date_multiple_first').value;
        var leave_date_multiple_second = document.getElementById('leave_date_multiple_second').value;
        var user_id = document.getElementById('user').value;
        $.ajax({
            type: "POST",
            url: "<?= base_url("Leave_management/check_date_multiple_present") ?>",
            dataType: "json",
            data: {
                leave_date_multiple_first: leave_date_multiple_first,
                leave_date_multiple_second: leave_date_multiple_second,
                user_id: user_id
            },
            success: function (result) {
                if (result.status === true) {
                    var data = result.leave_dates;
                    var array = data.split(",");
                    alert('Leaves aleready requested for ' + array + ' . Please delete them to create again');
                    document.getElementById('leave_date_multiple_first').value = 0;
                    document.getElementById('leave_date_multiple_second').value = 0;
                    document.getElementById("leave_conf_tbl").style.display = "none";
                }
            },
        });
    }

    function start_loading() {
        document.getElementById('loading_div').style.display = "block";
    }

    function stop_loading() {
        document.getElementById('loading_div').style.display = "none";
    }

    function daysInMonth(month, year) {
        return new Date(year, month, 0).getDate();
    }


    function get_login_detailsNew(date,end) {
        var d = new Date(date);
        $('#currentMonth').empty();
        $('#monthNames').empty();
        cdate = d.getFullYear() + '-' + d.getMonth();

        $('#currentMonth').val(d.getFullYear() + '-' + d.getMonth());
        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        $('#monthNames').val(monthNames[d.getMonth() - 1] + ' ' + d.getFullYear());
        monthdate = monthNames[d.getMonth() - 1] + ' ' + d.getFullYear();
        var array_time = [];
        let myFirstPromise1 = new Promise((resolve, reject) => {
            date = getFormattedString(d);
            $.ajax({
                url: '<?= base_url("DashboardController/get_login_detailsnew") ?>',
                type: "POST",
                data: {start_date: date,end_date:end},
                success: function (result) {
                    result = JSON.parse(result);
                    var	array_time=[];
                    $.each(result, function (key, success) {
                        if (success.message == 'success') {
                            if (success.status == 'intime_marked') {
                                var intime1 = success.intime;
                                var leave_status = success.leave_status;
                                var activity_status = success.activity_status;
                                var datetimeNow = new Date(intime1);
                                var intime = get_ampm(datetimeNow);
                                var regular_status = success.regular_status;
                                array_time[success.date] = [intime, '', leave_status, activity_status, regular_status];
                                //resolve(array_time);
                            } else if (success.status == 'attendace_marked') {
                                var intime1 = success.intime;
                                var leave_status = success.leave_status;
                                var activity_status = success.activity_status;
                                var datetimeNow = new Date(intime1);
                                var intime = get_ampm(datetimeNow);
                                var out1 = success.outtime;
                                var datetimeNow1 = new Date(out1);
                                var outtime = get_ampm(datetimeNow1);
                                var regular_status = success.regular_status;
                                array_time[success.date] = [intime, outtime, leave_status, activity_status, regular_status];
                                //resolve(array_time);
                            } else {
                                var activity_status = success.activity_status;
                                var leave_status = success.leave_status;
                                var regular_status = success.regular_status;
                                array_time[success.date] = [0, 0, leave_status, activity_status, regular_status];
                                //resolve(array_time);
                            }
                        }
                    });
                    resolve(array_time);
                },
                error: function (error) {
                    reject(false);
                    // toastr.error("something went to wrong");
                }
            });
        });
        return myFirstPromise1;
    }


    function formatdate(originalDateString) {
        const date = new Date(originalDateString);
        // Extract individual components
        const year = date.getFullYear();
        const month = date.getMonth() + 1; // Months are zero-based
        const day = date.getDate();
        const hours = date.getHours();
        const minutes = date.getMinutes();
        const seconds = date.getSeconds();

        // Format the components with leading zeros where necessary
        const formattedDateString = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')} ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        return formattedDateString;
    }


    function GetCalendarDateRange() {
        var calendar = $('#calendarIO1').fullCalendar('getCalendar');
        var view = calendar.view;
        var start = view.start._d;
        var end = view.end._d;
        var dates = { start: start, end: end };
        return dates;
    }


    function convertTo24HourFormat(time12h, isPM = false) {
        let [hours, minutes, seconds] = time12h.split(':');
        hours = parseInt(hours, 10);
        if (!isPM && hours === 12) { // Convert 12 AM to 00:00 (midnight)
            hours = '00';
        }
        
        if (isPM && hours !== 12) { // Convert PM times except 12 PM to 24-hour format
            hours = hours + 12;
        }
        // Return the formatted time in 24-hour format
        return `${hours.toString().padStart(2, '0')}:${minutes}:${seconds ? seconds : '00'}`;
    }


    function isTimeDifferenceLessThanGivenHours(time1, time2, hourTowork) {
        function convertTo24HourFormat(time, isPM) {
            const parts = time.split(':');
            let hours = Number(parts[0]);
            const minutes = Number(parts[1]);
            const seconds = parts[2] ? Number(parts[2]) : 0;
            if (isNaN(hours) || isNaN(minutes) || isNaN(seconds)) return null;
            if (isPM && hours !== 12) hours += 12;
            if (!isPM && hours === 12) hours = 0;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function convertTimeToMilliseconds(timeStr) {
            const [hours, minutes, seconds] = timeStr.split(':').map(Number);
            return ((hours * 60 * 60) + (minutes * 60) + seconds) * 1000;
        }

        const [time1Part, ampm1] = time1.trim().split(' ');
        const [time2Part, ampm2] = time2.trim().split(' ');
        const time1In24 = convertTo24HourFormat(time1Part, ampm1?.toUpperCase() === 'PM');
        const time2In24 = convertTo24HourFormat(time2Part, ampm2?.toUpperCase() === 'PM');

        if (!time1In24 || !time2In24) {
            return false;
        }

        const today = new Date().toISOString().slice(0, 10);
        const date1 = new Date(`${today}T${time1In24}`);
        const date2 = new Date(`${today}T${time2In24}`);

        if (isNaN(date1.getTime()) || isNaN(date2.getTime())) {
            return false;
        }

        if (date2 < date1) {
            date2.setDate(date2.getDate() + 1);
        }
        const diffMilliseconds = date2 - date1;
        const allowedMilliseconds = convertTimeToMilliseconds(hourTowork);
        // console.log("Time difference (ms):", diffMilliseconds);
        return diffMilliseconds < allowedMilliseconds;
    }


    function get_leave_status(leave_status){
        if (leave_status == 1) {
            var sts = 'Leave Requested';
            var color = '#f9ec6bfc';
        } else if (leave_status == 2) {
            var sts = 'Leave Accepted';
            var color = '#648a4b';
        } else if (leave_status == 3) {
            var sts = 'Leave Denied';
            var color = '#d26b5e';
        } else if (leave_status == 4) {
            var sts = 'Leave Cancelled';
            var color = '#d26b5e';
        } else {
            var sts = '';
            var color = '';
        }
        ret_arr=[sts,color];
        return ret_arr;
    }


    $(document).ready(function () {
        get_data_leave();
        load_data();
        get_employees();
        calendar_data();

    });


    function getMonthCalendar() {
        var user_type = $("#user_type").val();
        if (user_type == 5) {
            var date = $("#calendarIO1").fullCalendar('getDate');

        } else {
            var date = $("#calendarIO1").fullCalendar('getDate');

        }
        return date;
    }


    function load_data() {
        if (navigator.geolocation) {
            var aa = navigator.geolocation.getCurrentPosition(showPosition, showError);

        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }


    function get_data_leave() {
        var emp_id = $('#emp_req_att').val();
        var date = $('#currentMonth').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url("Leave_management/get_all_leave_requests") ?>",
            data: {emp_id: emp_id, date: date},
            dataType: "json",
            async: false,
            cache: false,
            success: function (result) {
                var data = result.result;
                $('#leave_request').empty();
                if (result.message == 'success') {

                    $('#leave_request').append(data);

                    var table = $('#sample1').DataTable({
                        paging: true,
                    });


                    $.fn.dataTable.ext.errMode = 'none';

                } else {
                    $('#leave_request').append("");
                }
            },
        });
    }


    function get_employees() {
        $.ajax({
            url: '<?= base_url("DashboardController/get_junior_employees") ?>',
            type: "POST",
            success: function (success) {
                success = JSON.parse(success);
                var data = success.data;
                if (success.message == 'success') {
                    $("#employee_id").html(data);
                } else {
                    $("#employee_id").hide();
                }
            }
        });
    }


    function showPosition(position) {
        var x = document.getElementById("location_data");
        var lat = position.coords.latitude;
        var longi = position.coords.longitude;
        $('#latlng').val(position.coords.latitude + ',' + position.coords.longitude)
        x.innerHTML = "<input type='hidden' id='latitude_live' name='latitude_live' value='" + lat + "' > " + "<input type='hidden' id='longitude_live' name='longitude_live' value='" + longi + "' > ";
        var latitude_live = document.getElementById("latitude_live").value;
        var longitude_live = document.getElementById("longitude_live").value;

        if (latitude_live !== "") {
            $("#gps_div").hide();
        } else {
            $("#gps_div").show();
        }
        $.ajax({
            url: '<?= base_url("DashboardController/check_location") ?>',
            type: "POST",
            data: {
                latitude_live: latitude_live,
                longitude_live: longitude_live
            },
            success: function (success) {
                success = JSON.parse(success);
                if (success.message == 'inside') {
                    $("#login_btn").html("Inside Login");
                    $("#logout_btn").html("Inside Logout");
                } else {
                    $("#login_btn").html("Outside Login").attr("data-punch_type", "outside");
                    $("#logout_btn").html("Outside Logout").attr("data-punch_type", "outside");
                    var y = document.getElementById("msg");
                    y.innerHTML = "<h5>You are outside of your office loaction,Please Go 100 meters near to your office location else your Punchin/PunchOut will be treated as outside.</h5>";
                }
                initMap(latitude_live, longitude_live);
            },
        });
    }


    function initMap(la, lg) {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 8,
            center: {lat: la, lng: lg}
        });
        const geocoder = new google.maps.Geocoder();
        const infowindow = new google.maps.InfoWindow();
        geocodeLatLng(geocoder, map, infowindow);

    }


    function geocodeLatLng(geocoder, map, infowindow) {
        const input = document.getElementById("latlng").value;
        const latlngStr = input.split(",", 2);
        const latlng = {
            lat: parseFloat(latlngStr[0]),
            lng: parseFloat(latlngStr[1])
        };
        geocoder.geocode({location: latlng}, (results, status) => {
            if (status === "OK") {
                if (results[0]) {
                    map.setZoom(11);
                    const marker = new google.maps.Marker({
                        position: latlng,
                        map: map
                    });
                    // console.log(results[0]);
                    var add = results[0]['address_components'];
                    var address = '';
                    var longaddress = '';
                    for (i = 3; i < add.length; i++) {
                        // console.log(add[i]['long_name']);
                        address += add[i]['long_name'] + ',';
                        longaddress += add[i]['long_name'] + ',';
                    }
                    $('#address').empty();
                    //$('#address').append(address);
                    $('#longaddress').empty();
                    $('#longaddress').val(longaddress);
                    $('#shortaddress').empty();
                    $('#srtaddress').empty();
                    $('#srtaddress').val(results[0]['address_components'][0]['long_name'] + ',' + results[0]['address_components'][1]['long_name'] + ',' + results[0]['address_components'][2]['long_name']);
                    $('#shortaddress').append(results[0]['address_components'][0]['long_name'] + ',' + results[0]['address_components'][1]['long_name'] + ',' + results[0]['address_components'][2]['long_name'] + ',' + address);
                    $('#address').empty();
                    //$('#address').append(address);
                    $('#longaddress1').empty();
                    $('#longaddress1').val(longaddress);
                    $('#shortaddress1').empty();
                    $('#srtaddress1').empty();
                    $('#srtaddress1').val(results[0]['address_components'][0]['long_name'] + ',' +
                        results[0]['address_components'][1]['long_name'] + ',' + results[0]['address_components'][2]['long_name']);

                    $('#shortaddress1').append(results[0]['address_components'][0]['long_name'] + ',' +
                        results[0]['address_components'][1]['long_name'] + ',' + results[0]['address_components'][2]['long_name'] + ',' + address);
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                } else {
                    window.alert("No results found");
                }
            } else {
                window.alert("Geocoder failed due to: " + status);
            }
        });
    }


    function getFormattedString1(d) {
        const year = d.getFullYear();
        const month = (d.getMonth() + 1).toString().padStart(2, '0'); // Adding 1 to month and padding with zero
        const day = d.getDate().toString().padStart(2, '0'); // Padding day with zero
        // Return formatted date
        return `${year}-${month}-${day}`;

    }


    function get_ampm(datetimeNow) {
        var hours = datetimeNow.getHours();
        var minutes = datetimeNow.getMinutes();
        // var ampm = hours >= 12 ? 'pm' : 'am';
        //hours = hours % 12;
        //hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes;
        return strTime;
    }


    function formatDateToString(date) {
        var dd = (date.getDate() < 10 ? '0' : '') + date.getDate();
        var MM = ((date.getMonth() + 1) < 10 ? '0' : '') + (date.getMonth() + 1);
        return [dd, MM];
    }


    function get_holidays_show(date,employee_id=null) {
        var d = new Date(date);
        var aa = formatDateToString(d);
        date = getFormattedString(d);
        var date = d.getDate();
        var month = d.getMonth() + 1; // Since getMonth() returns month from 0-11 not 1-12
        var year = d.getFullYear();
        var dateStr = year + "-" + aa[1] + "-" + aa[0];
        let mysthPromise11 = new Promise((resolve, reject) => {
            var array_holiday = document.getElementById("holiday_array1").value;
            var search = array_holiday.search(dateStr);
            if (search != -1) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url("DashboardController/get_holiday_name") ?>",
                    dataType: "json",
                    async: false,
                    cache: false,
                    data: {date: dateStr,employee_id:employee_id},
                    success: function (result) {
                        var data = result.h_name;
                        if (result.message == 'success') {

                            resolve(data);
                        } else {
                            reject(false);
                        }
                    },
                });
            } else {
                reject(false);
            }
        });

        return mysthPromise11;
    }


    function get_leave_details(date) {
        var status_arr = [];
        let mysecPromise = new Promise((resolve, reject) => {
            var d = new Date(date);
            date = getFormattedString(d);
            $.ajax({
                url: '<?= base_url("DashboardController/get_leave_details") ?>',
                type: "POST",
                data: {start_date: date},
                success: function (success) {

                    success = JSON.parse(success);
                    if (success.message == 'leave') {
                        var status = success.status;
                        status_arr = [status];
                        resolve(status_arr);
                    } else {
                        reject(false);
                    }

                },
                error: function (error) {
                    reject(false);                                                             toastr.error("something went to wrong");
                }
            });
        });
        return mysecPromise;
    }


    let cdate = null;
    let monthdate = null;
    $('#mp_attendance_Req').on('hidden.bs.modal', function () {
        $(this).removeData('modal');
    });


    $('#emp_attendance_Req').on('shown.bs.modal', function (e) {
        $(this).removeData('modal');
        //get_data_leave();
        //get_full_att_data();
        get_all_data();
        //get_na_data();
        //get_outside_app();
        //get_current_month_att();
        var cm = $('#currentMonth').val();
        var mn = $('#monthNames').val();
        // console.log(cdate);
        // console.log(monthdate);
        $('#currentMonth1').empty();
        $('#currentMonth').empty();
        $('#monthNames1').empty();
        $('#currentMonth1').val(cdate);
        $('#currentMonth').val(cdate);
        $('#monthNames1').append(monthdate);

    });


    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation. Please on GPS.");
                $("#gps_div").show();

                var x = document.getElementById("location_data");
                x.innerHTML = "<input type='hidden' id='latitude_live' name='latitude_live' value='0' > " +
                    "<input type='hidden' id='longitude_live' name='longitude_live' value='0' > ";
                var y = document.getElementById("msg");

                y.innerHTML = "<h5>You have denied the request for Geolocation.Please allow or on the GPS else you will not be able to login/logout.</h5>";

                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                $("#gps_div").show();
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                $("#gps_div").show();
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                $("#gps_div").show();
                break;
        }
    }


    function generateXls() {
        var year = $("#emp_req_year").val();
        var user_id = $("#employee_id").val();
        var month = $("#emp_req_mon").val();
        if(user_id=="") {
            user_id=$("#emp_login").val();
        }
        window.location.href = "<?= base_url("ServiceRequestController/generateXls_data") ?>?year=" + year + "&month=" + month + "&user_id=" + user_id;
    }


    // $(document).ready(function() {
    //     let url = "<?= base_url("/greeting_activity") ?>";
    //     $.ajax({
    //         url: url,
    //         method: "GET",
    //         dataType: "json",
    //         success: function(resp) {
    //             let values = resp.data;
    //             if (!values || values.length === 0) return;

    //             let html = `
    //             <style>
    //                 .modal-content {
    //                     background: linear-gradient(to right top, #fc5e5eff, #e5dfebff, #55108c, #f15252ff, #9612eb);
    //                     border: none;
    //                     border-radius: 10px;
    //                     color: white;
    //                 }

    //                 .modal-body {
    //                     display: flex;
    //                     justify-content: center;
    //                     align-items: center;
    //                     flex-direction: column;
    //                 }

    //                 .greeting-card {
    //                     position: relative;
    //                     width: 300px;
    //                     height: 425px;
    //                     border: 10px solid #dfc3f1ff;
    //                     background: linear-gradient(to bottom, rgba(255, 255, 255), rgba(255, 255, 255, 0.5)), 
    //                         url("https://images.unsplash.com/photo-1527481138388-31827a7c94d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60");
    //                     background-position: center;
    //                     background-repeat: no-repeat;
    //                     background-size: cover;
    //                     box-shadow: inset 10px 0px 15px 0px rgba(0, 0, 0, 0.1);
    //                 }

    //                 .greeting-card .text-container {
    //                     padding: 20px;
    //                     color: #331717;
    //                     font-family: 'Nobile', sans-serif;
    //                     text-align: center;
    //                 }

    //                 .card-front, .card-back {
    //                     position: absolute;
    //                     width: 100%;
    //                     height: 100%;
    //                     border: 10px solid #9612eb;
    //                     backface-visibility: hidden;
    //                     transform-style: preserve-3d;
    //                     transform-origin: 0% 50%;
    //                     transition: all 0.8s ease-in-out;
    //                 }

    //                 .card-front {
    //                     background-color: #9612eb;
    //                     transform: perspective(800px) rotateY(0deg);
    //                     z-index: 2;
    //                 }

    //                 .card-back {
    //                     background-color: #e6f0e6;
    //                     transform: perspective(800px) rotateY(180deg);
    //                     z-index: 1;
    //                 }

    //                 .greeting-card:hover .card-front {
    //                     transform: perspective(800px) rotateY(-180deg);
    //                 }

    //                 .greeting-card:hover .card-back {
    //                     transform: perspective(800px) rotateY(0deg);
    //                 }

    //                 .imgset img {
    //                     width: 100%;
    //                     box-shadow: 0px 6px 11px 7px rgba(0, 0, 0, 0.22);
    //                     border-radius: 5px;
    //                 }
    //             </style>

    //             <div class="modal fade " id="greetingModal" tabindex="-1" role="dialog" aria-labelledby="greetingModalLabel" aria-hidden="true">
    //                 <div class="modal-dialog modal-dialog-centered" role="document">
    //                     <div class="modal-content p-3">
    //                         <div class="modal-header">
    //                             <h5 class="modal-title">🎊 Greetings!</h5>
    //                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    //                             <span aria-hidden="true">&times;</span>
    //                             </button>
    //                         </div>
    //                         <div class="modal-body">
    //                             <div class="greeting-card">
    //                             ${values.map(item => `
    //                                 <div class="card-front">
    //                                     <div class="imgset">
    //                                         ${item.image}
    //                                     </div>
    //                                 </div>
    //                                 <div class="card-back">
    //                                     <div class="text-container">
    //                                         <p id="head">${item.message}</p>
    //                                         <h5>${item.user_name}</h5>
    //                                         <p>I hope your special day will bring you lots of happiness, love, and fun. You deserve them a lot. Enjoy!</p>
    //                                         <p>Hope your day goes great!</p>
    //                                     </div>
    //                                 </div>
    //                                 `).join('')}
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>`;

    //             $('body').append(html);
    //             $('#greetingModal').modal('show');
    //         },
    //         error: function(err) {
    //             console.error("Greeting fetch error", err);
    //         }
    //     });
    // });
</script>

