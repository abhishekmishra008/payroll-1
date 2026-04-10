<?php
    if ($session = $this->session->userdata('login_session') == '') {
        redirect(base_url() . 'login');
    }
    $session_data = $this->session->userdata('login_session');
    $data['session_data'] = $session_data;
    $user_id = ($session_data['user_id']);
    $emp_id = ($session_data['emp_id']);
    $firm = $this->db->query("SELECT firm_id FROM user_header_all WHERE user_id='$emp_id'")->row();
    $firmId = $firm->firm_id;
    $user_type = ($session_data['user_type']);
    $user_name = ($session_data['user_name']);
    $page_name = 'View Calender Holiday';
    $page_name2 = 'View Due Date List';
    $language = '';
    if (isset($session_data['language'])) {
        $language = $session_data['language'];
    }

    $empUserType = isset($session_data['user_type']) ? $session_data['user_type'] : '';

    $url = $_SERVER['REQUEST_URI'];
    $tiketUrl = "https://ticket.ecovisrkca.com/";
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<style type="text/css">
    body {
        margin: 0px !important;
    }

    .navbar-bg {
        content: ' ';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 115px;
        background-color: #6777ef;
        z-index: -1;
    }

    .navbar {
        transition: all .5s;
        align-items: center;
        height: 60px;
        left: 250px;
        right: 5px;
        /* position: absolute; */
        z-index: 890;
        background-color: transparent;
    }

    @media (min-width: 992px) {
        .navbar-expand-lg {
            -ms-flex-flow: row nowrap;
            flex-flow: row nowrap;
            -ms-flex-pack: start;
            justify-content: flex-start;
        }
    }

    .myMenuIcons {
        background-color: #ffffff;
        padding: 10px 20px 10px 20px;
        /*border-radius: 20px 0px 20px 0px;*/
        -webkit-box-shadow: 0px 2px 5px 2px #afaeae;
        margin-right: 10px;
        font-size: 20px !important;
        color: #495057;
    }

    .myMenuIcons:focus {
        background-color: #d2454d;
        color: #ffffff;
    }

    .has_skew {
        transform: skew(-0deg);
        text-decoration: none;
    }

    .has_no_skew {
        transform: skew(0deg) !important;
    }

    .dropbtn {
        background-color: transparent;
        color: black;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .dropbtn:hover,
    .dropbtn:focus {
        background-color: transparent;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: transparent;
        min-width: 160px;
    }

    .dropdown-content1 {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
    }

    .dropdown-content1 a {
        color: black;
        padding: 12px 20px;
        text-decoration: none;
        display: block;
        -webkit-box-shadow: 0px 2px 6px 0px #cccccc;
    }

    .dropdown-content1 a:hover {
        background-color: #f1f1f1
    }

    .show {
        display: block;
    }

    .show1 {
        display: block;
    }

    .navbar1 {
        background-color: transparent;
        color: black;
    }

    .navbar1 a {
        float: left;
        font-size: 16px;
        color: black;
        text-align: center;
        padding: 7px 15px;
        text-decoration: none;

    }

    .subnav1 {
        float: left;
    }

    .subnav1 .subnavbtn1 {
        font-size: 16px;
        border: none;
        outline: none;
        color: #495057;
        padding: 14px 16px;
        background-color: white;
        font-family: inherit;
        margin: 0;
    }

    .navbar1 a:hover,
    .subnav1:hover .subnavbtn1 {
        background-color: #312525;
        color: white;
    }

    .subnav-content1 {
        display: none;
        position: absolute;
        background-color: transparent;
        z-index: 1;
        margin-left: -15px;
    }

    .subnav-content1 a {
        float: left;
        color: black;
        text-decoration: none;
        padding-top: 15px;
    }

    .subnav-content1 a:hover {
        background-color: white;
        color: black;
        -webkit-box-shadow: 0px 2px 5px 2px #afaeae;
    }

    .subnav1:hover .subnav-content1 {
        display: block;
    }

    .dropbtn:focus {
        outline: none;
    }

    .myCss {
        background-color: #ffffff;
        padding: 10px 20px 10px 20px;
        -webkit-box-shadow: 0px 2px 5px 2px #afaeae;
        margin-right: 10px;
        font-size: 17px !important;
        color: #495057;
        transform: skew(-0deg);
        text-decoration: none;
    }

    .icon_size {
        font-size: 40px !important;
    }

    .my_menu_child {
        background-color: #ffffff !important;
        height: 47px !important;
        margin-top: 10px !important;
        -webkit-box-shadow: 0px 2px 5px 2px #afaeae !important;
    }

    .rkca_logo {
        width: 110px;
        transform: skew(0deg);
        vertical-align: middle !important;
        border-style: none;
        margin-top: 0px;
    }
    
    #desktop_view {
        display: block;
    }

    #mobile_view {
        display: none;
    }

    .desktop_view {
        display: block;
    }

    .mobile_view {
        display: none !important;
    }

    @media (max-width: 800.98px) {
        #desktop_view {
            display: none;
        }

        .desktop_view {
            display: none !important;
        }

        #mobile_view {
            display: block;
        }

        .mobile_view {
            display: flex !important;
        }

        .rkca_logo {
            width: 110px;
            transform: skew(0deg);
            vertical-align: middle !important;
            border-style: none;
            margin-top: 0px;
        }

        .mobile_a_menu {
            font-size: 24px !important;
            color: #d2454d;
            padding: 8px;
            transform: skew(0deg);
        }

        .mobile_a_menu:hover {
            -webkit-box-shadow: 0px 2px 7px 4px #ccc;
            transform: skew(0deg);
            padding: 5px 15px 5px 15px;
        }

        .menu_mm {
            transform: skew(0deg);
        }

        .execution_bar {
            position: relative;
            top: 100px;
            z-index: 4;
            height: 40px;
            background-color: white;
            -webkit-box-shadow: 0px 2px 7px 4px #ccc;
            transform: skew(-0deg, 0deg);
            left: -58px;
            padding-left: 60px;

        }

        .planning_bar {
            position: relative;
            top: 100px;
            z-index: 4;
            height: 40px;
            background-color: white;
            -webkit-box-shadow: 0px 2px 7px 4px #ccc;
            transform: skew(-0deg, 0deg);
            left: -58px;
            padding-left: 60px;

        }
    }


    #myDropdown1 {
        right: 0;
        left: auto;
        width: 506px !important;
        background: transparent !important;
        margin-top: 15px;
    }

    .fixed_header {
        background-color: #d2454d !important;
        color: white !important;
    }
    
    body {
        font-family: "Lato", sans-serif;
    }

    .sidenav .card {
        margin-bottom: 0px;
    }

    .sidenav {
        height: 100%;
        width: 0px;
        position: fixed;
        z-index: 1;
        left: 0;
        background-color: white;
        overflow-x: hidden;
        transition: 0.5s;
        flex: 0 0 auto;
        /* Prevent stretching */
        margin-top: 24px;
        /* Adjust this value as needed */
        padding-top: 15px;
    }

    .card .card-body {
        padding-top: 7px;
        padding-bottom: 7px;
        padding-left: 45px;
        padding-right: 20px;
        margin-top: 15px !important;
    }

    .sidenav a {
        text-decoration: none;
        font-size: 14px;
        color: black;
        display: block;
        transition: 0.3s;
    }

    .k-store {
        font-size: 14px !important;
    }

    .sidenav a:hover {
        color: #495057;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 6px;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    .card {
        border: none;
        background: none;
        color: black;
        background-color: #fff !important;
    }

    .btn {
        text-align: left;
    }

    .card-header {
        border-bottom: none;
        background: transparent;
    }

    .card-header:hover {
        background-color: #a5a7a952;
    }

    .card .collapse .card .card-body:hover {
        background-color: #a5a7a952;
    }

    .fas {
        margin: 0px 7px;
    }

    .collapse {
        background: white;
        border-right: 2px solid #ededed;
    }

    .fa-chevron-down {
        font-size: 10px;
    }

    .options {
        font-weight: 500;
    }

    .sidenav .fas {
        font-size: 11px
    }

    .con {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
    }

    .card-header .mb-0 .btn-link:hover {
        background-color: transparent;
    }

    .headerCard {
        background-color: #fff !important;
        border-radius: 3px !important;
        border: none !important;
        position: relative !important;
    }

    .flex-option {
        display: flex;
        justify-content: space-between;

    }

    .navbar1 a {
        margin-bottom: 1rem;
    }

    .page-content-wrapper {
        position: relative;
    }

    .page-header-fixed .page-container {
        margin-top: 0 !important;
    }

        #google_translate_element,
    .skiptranslate {
        display: none !important;
    }

    body {
        top: 0 !important;
    }

    .VIpgJd-yAWNEb-VIpgJd-fmcmS-sn54Q {
        background-color: transparent;
        box-shadow: none;
    }
</style>


<?php if ($empUserType == '5'): ?>
    <style>
        .page-content-wrapper .page-content {
            margin-top: 0rem;
        }

        .page-content-wrapper {
            position: relative;
            /* top: 132px; */

        }
    </style>
<?php endif; ?>


<input type="hidden" name="language" id="language" value="<?= $language ?>">
<input type="hidden" id="email_emp" value="<?php echo $user_id ?>">
<input type="hidden" id="firm_id" value="<?php echo $firmId ?>">
<div class="timesheetheader" style="display: flex;flex-direction: column;">
    <div style="left: 0;right: 0;height: 90px;">
        <div class="con">
            <div id="mySidenav" style="z-index: 999;margin-top: 60px;box-shadow: lightgrey 2px 0px 0px 0px;width: 0px;" class="sidenav">
                <div id="accordion" style="margin-top: 0px;">
                    <div class="card headerCard">
                        <div class="card-header" id="headingOne">
                            <a href="#" onclick="#" class="myCss ">
                                <div style="transform:skew(0deg)!important;">Dashboard</div>
                            </a>
                        </div>
                    </div>

                        <?php if ($user_type == 5 || $user_type == 4) { ?>
                            <a href="#" onclick="goToPayroll('calendar')"
                                class="myCss <?php echo $url == '/calendar' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Work Schedule</div>
                            </a>
                            <a href="#" onclick="goToPayroll('serviceRequest')"
                                class="myCss <?php echo $url == '/serviceRequest' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Employee Services</div>
                            </a>
                            <a href="#" onclick="goToPayroll('runpayroll')"
                                class="myCss <?php echo $url == '/runpayroll' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Check In</div>
                            </a>
                        <?php } ?>

                        <?php if ($user_type == 4) { ?>
                            <a href="#" onclick="goToPayroll('hq_show_firm')"
                                class="myCss <?php echo $url == '/hq_show_firm' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">View Office</div>
                            </a>
                            <a href="#" onclick="goToPayroll('hq_view_hr')"
                                class="myCss <?php echo $url == '/hq_view_hr' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">View HR</div>
                            </a>

                        <?php } ?>

                        <?php if ($user_type != 4) { ?>
                            <a href="#" onclick="goToPayroll('hr_designation')"
                                class="myCss <?php echo $url == '/hr_designation' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Designation</div>
                            </a>
                            <a href="#" onclick="goToPayroll('hr_show_employee')"
                                class="myCss <?php echo $url == '/hr_show_employee' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">View Employee</div>
                            </a>
                        <?php } ?>

                        <?php if ($user_type == 5) { ?>
                            <a href="#" onclick="goToPayroll('hr_show_holiday')"
                                class="myCss <?php echo $url == '/hr_show_holiday' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Holidays</div>
                            </a>
                            <a href="#" onclick="goToPayroll('MonthlyReport')"
                                class="myCss <?php echo $url == '/MonthlyReport' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Monthly Report</div>
                            </a>
                        <?php } ?>

                        <a href="#" onclick="scheduleTaskTimeSheet('TimeSheet')"
                            class="myCss <?php echo $url == '/scheduleTask' ? 'fixed_header' : 'myCss'; ?>">
                            <div style="transform:skew(0deg)!important;">Daily Activity</div>
                        </a>
                        <a href="#" onclick="goToPayroll('profile')"
                            class="myCss <?php echo $url == '/profile' ? 'fixed_header' : 'myCss'; ?>">
                            <div style="transform:skew(0deg)!important;">Profile</div>
                        </a>
                        <a href="#" onclick="goToPayroll('special_days')"
                            class="myCss <?php echo $url == '/special_days' ? 'fixed_header' : 'myCss'; ?>">
                            <div style="transform:skew(0deg)!important;">Events</div>
                        </a>

                        <a href="#" onclick="goToPayroll('ticket')"
                            class="myCss <?php echo $url == '/ticket' ? 'fixed_header' : 'myCss'; ?>">
                            <div style="transform:skew(0deg)!important;">Ticket Raise</div>
                        </a>
                        <a href="#" onclick="goToPayroll('ticket')"
                            class="myCss <?php echo $url == '/hr_management' ? 'fixed_header' : 'myCss'; ?>">
                            <div style="transform:skew(0deg)!important;">HR Management</div>
                        </a>

                        <?php if ($user_type == 5) { ?>
                            <a href="#" onclick="goToPayroll('performance')"
                                class="myCss <?php echo $url == '/performance' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Performance Management</div>
                            </a>

                            <a href="#" onclick="goToPayroll('meeting')"
                                class="myCss <?php echo $url == '/meeting' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Meeting Schedule</div>
                            </a>

                            <a href="#" onclick="goToPayroll('document')"
                                class="myCss <?php echo $url == '/document' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Document Upload</div>
                            </a>

                            <a href="#" onclick="goToPayroll('compliance')"
                                class="myCss <?php echo $url == '/compliance' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Compliance</div>
                            </a>

                            <a href="#" onclick="goToPayroll('roles')"
                                class="myCss <?php echo $url == '/roles' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Roles & Permission</div>
                            </a>

                            <a href="#" onclick="goToPayroll('notification')"
                                class="myCss <?php echo $url == '/notification' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Notification</div>
                            </a>

                            <a href="#" onclick="goToPayroll('training')"
                                class="myCss <?php echo $url == '/training' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Training</div>
                            </a>

                            <a href="#" onclick="goToPayroll('visitor')"
                                class="myCss <?php echo $url == '/visitor' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Visitor</div>
                            </a>

                            <a href="#" onclick="goToPayroll('project')"
                                class="myCss <?php echo $url == '/project' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Project Management</div>
                            </a>

                            <a href="#" onclick="goToPayroll('project')"
                                class="myCss <?php echo $url == '/project' ? 'fixed_header' : 'myCss'; ?>">
                                <div style="transform:skew(0deg)!important;">Project Management</div>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <nav class="navbar navbar-expand-lg main-navbar" style="margin-top: 0px;left: 0px;-webkit-box-shadow: 0px 2px 7px 4px #ccc; background: white;width: 100%;display:flex;">
                <span id="hamburgerMenu" style="font-size:30px;cursor:pointer;margin: 2px 20px 9px 30px;transform: skew(0deg,0deg);"
                    onclick="toggleNav()">&#9776;</span>
                <img id="logo_img" src="<?php echo base_url(); ?>assets/img/gbt.jpg" class="rkca_logo"
                    alt="Gold Berries">
                <div class="dropdown" id="desktop_view" style="margin-left: auto!important;float: right;margin-top: 1px;background: transparent;">
                    <?php if ($this->session->userdata('user_type') == 6) { ?>
                    <?php } else { ?>
                        <i class="fa fa-user"> </i> <span
                            style="font-size: 14px;color:#891635;font-weight: bold;margin-right: 30px">Hi,
                            <?php echo $user_name; ?> </span>
                    <?php } ?>
                    <div id="myDropdown" class="dropdown-content"
                        style="right: 0; left: auto;width: 570px;background: transparent;margin-top: 5px; ">
                        <div class="navbar1">
                            <a href="#" onclick="goToHrms('execution')" class="myCss">
                                <div style="transform:skew(0deg)!important;">Execution</div>
                            </a>
                            <div class="subnav1">
                                <button class="subnavbtn1 myCss"
                                    style="padding: 7px 15px 7px 15px;margin-right: 10px;font-weight: 500;">
                                    <div style="transform:skew(0deg)!important;">Planning <i
                                            class="fa fa-caret-down"></i>
                                    </div>
                                </button>
                                <div class="subnav-content1">
                                    <div class="my_menu_child">
                                        <a href="#" title="Project Management" onclick="goToRmt('projectManagement')"><i
                                                class="fa fa-list"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                        <a href="#" title="Board" onclick="goToRmt('board')"><i
                                                class="fa fa-address-card"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                        <a href="#" title="Board" onclick="goToRmt('service')"><i
                                                class="fa fa-caret-square-down"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="subnav1">
                                <button class="subnavbtn1 myCss"
                                    style="padding: 7px 15px 7px 15px;margin-right: 10px;font-weight: 500;">
                                    <div style="transform:skew(0deg)!important;">Collaboration <i
                                            class="fa fa-caret-down"></i></div>
                                </button>
                                <div class="subnav-content1">
                                    <div class="my_menu_child">
                                        <a href="#" title="Mail" onclick="goToRmt('mail')"><i class="fa fa-envelope"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                        <a href="#" title="Chat" onclick="goToRmt('chat')"><i class="fa fa-comments"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                        <a href="#" title="Folder" onclick="goToRmt('folder')"><i class="fa fa-folder"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>

                                    </div>
                                </div>
                            </div>
                            <div class="subnav1">
                                <button class="subnavbtn1 myCss"
                                    style="padding: 7px 15px 7px 15px;margin-right: 10px;font-weight: 500;">
                                    <div style="transform:skew(0deg)!important;">Tools <i class="fa fa-caret-down"></i>
                                    </div>
                                </button>
                                <div class="subnav-content1">
                                    <div class="my_menu_child"
                                        style="width: 300px;margin-right: 40% !important;position: relative;right: 210px;">
                                        <a href="#" title="Mail" onclick="goToCrm('MassMail')"><i
                                                class="fa fa-mail-bulk"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                        <a href="#" title="Master Data" onclick="goToCrm('Survey')"><i
                                                class="fa fa-poll"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                        <a href="#" title="Tally" onclick="goToTally('Tally')"><i class="fa fa-table"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                        <a href="#" title="Global Accounting" target="_blank"
                                            onclick="goToGlobalAct()"><i class="fa fa-calculator"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                        <a href="#" title="Ticket Request" target="_blank"
                                            onclick="goToTicketEngine()"><i class="fa fa-ticket-alt"
                                                style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="mobile_view" style="transform:skew(0deg); margin-left: auto!important;float: right;margin-top: 1px;background: transparent;">
                </div>
                <a href="<?= base_url('Login/admin_logout'); ?>"
                    style="color:black!important;transform:skew(0deg)!important;"> Logout <i
                        class="fas fa-sign-out-alt text-muted"></i></a>
            </nav>
        </div>

        <div id="myDropdown1" class="d-flex flex-row justify-content-end desktop_view"
            style="width: 96% !important;display: flex;flex-direction: row;justify-content: end;position: relative; margin-left: 3rem;z-index: 99;">
            <div class="navbar1">
                <!-- <a href="#" onclick="goToPayroll('policy')"
                    class="myCss <?php echo $url == '/policy' ? 'fixed_header' : 'myCss'; ?>">
                    <div style="transform:skew(0deg)!important;">Policy</div>
                </a>

                <a href="#" onclick="goToPayroll('reimbursement')"
                    class="myCss <?php echo $url == '/reimbursement' ? 'fixed_header' : 'myCss'; ?>">
                    <div style="transform:skew(0deg)!important;">Reimbursement</div>
                </a>

                <a href="#" onclick="goToPayroll('help')"
                    class="myCss <?php echo $url == '/help' ? 'fixed_header' : 'myCss'; ?>">
                    <div style="transform:skew(0deg)!important;">Help Center</div>
                </a>

                <a href="#" onclick="goToPayroll('agreement')"
                    class="myCss <?php echo $url == '/agreement' ? 'fixed_header' : 'myCss'; ?>">
                    <div style="transform:skew(0deg)!important;">Agreement</div>
                </a> -->
            </div>
        </div>

        <div id="myDropdown2" class="d-flex flex-row justify-content-end mobile_view"
            style="height:4%;width: 87% !important;margin-top: 15px;display: flex;flex-direction: row;justify-content: end;margin-top: 100px">
            <div class="navbar1">
                <?php $url = $_SERVER['REQUEST_URI']; ?>
                <?php if ($user_type == 5 || $user_type == 4) { ?>
                    <a href="#" onclick="goToPayroll('calendar')"
                        class="myCss <?php echo $url == '/calendar' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-calendar"></i></div>
                    </a>
                    <a href="#" onclick="goToPayroll('serviceRequest')"
                        class="myCss <?php echo $url == '/serviceRequest' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-tasks"></i></div>
                    </a>
                    <a href="#" onclick="goToPayroll('runpayroll')"
                        class="myCss <?php echo $url == '/runpayroll' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-money"></i></div>
                    </a>

                <?php } ?>
                <?php if ($user_type == 2) { ?>
                    <a href="#" onclick="goToPayroll('hq_show_firm')"
                        class="myCss <?php echo $url == '/hq_show_firm' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-bank"></i></div>
                    </a>
                    <a href="#" onclick="goToPayroll('hq_view_hr')"
                        class="myCss <?php echo $url == '/hq_view_hr' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-user"></i></div>
                    </a>

                <?php } ?>
                <?php if ($user_type != 4) { ?>
                    <a href="#" onclick="goToPayroll('hr_designation')"
                        class="myCss <?php echo $url == '/hr_designation' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-medal"></i></div>
                    </a>
                    <a href="#" onclick="goToPayroll('hr_show_employee')"
                        class="myCss <?php echo $url == '/hr_show_employee' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-users"></i></div>
                    </a>

                <?php } ?>
                <?php if ($user_type == 5) { ?>
                    <a href="#" onclick="goToPayroll('hr_show_holiday')"
                        class="myCss <?php echo $url == '/hr_show_holiday' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-tasks"></i></div>
                    </a>
                    <a href="#" onclick="goToPayroll('MonthlyReport')"
                        class="myCss <?php echo $url == '/MonthlyReport' ? 'fixed_header' : 'myCss'; ?>">
                        <div style="transform:skew(0deg)!important;"><i class="fa fa-calendar-week"></i></div>
                    </a>

                <?php } ?>
                <a href="#" onclick="scheduleTaskTimeSheet('TimeSheet')"
                    class="myCss <?php echo $url == '/scheduleTask' ? 'fixed_header' : 'myCss'; ?>">
                    <div style="transform:skew(0deg)!important;"><i class="fa fa-tasks"></i></div>
                </a>
                <a href="#" onclick="goToPayroll('profile')"
                    class="myCss <?php echo $url == '/profile' ? 'fixed_header' : 'myCss'; ?>">
                    <div style="transform:skew(0deg)!important;"><i class="fa fa-address-card"></i></div>
                </a>
                <a href="#" onclick="goToPayroll('special_days')"
                    class="myCss <?php echo $url == '/special_days' ? 'fixed_header' : 'myCss'; ?>">
                    <div style="transform:skew(0deg)!important;"><i class="fa-solid fa-cake-candles"></i></div>
                </a>
            </div>
        </div>
    </div>

    <div class="execution_bar sameSubmenuClass" id="ExecutionsubmenuBar" style="display: none;">
    </div>

    <div class="planning_bar sameSubmenuClass" id="PlanningsubmenuBar" style="display: none;">
        <a href="#" onclick="goToRmt('projectManagement')" class="mobile_a_menu"><i class="fa fa-list menu_mm"></i></a>
        <a href="#" onclick="goToRmt('board')" class="mobile_a_menu"><i class="fa fa-address-card menu_mm"></i></a>
        <a href="#" onclick="goToRmt('service')" class="mobile_a_menu"><i class="fa fa-caret-square-down menu_mm"></i></a>
    </div>

    <div class="planning_bar sameSubmenuClass" id="CommunicationsubmenuBar" style="display: none;">
        <a href="#" onclick="goToRmt('mail')" class="mobile_a_menu"><i class="fa fa-envelope menu_mm"></i></a>
        <a href="#" onclick="goToRmt('chat')" class="mobile_a_menu"><i class="fa fa-comments menu_mm"></i></a>
        <a href="#" onclick="goToRmt('folder')" class="mobile_a_menu"><i class="fa fa-folder menu_mm"></i></a>
    </div>

    <div class="planning_bar sameSubmenuClass" id="FinancialsubmenuBar" style="display: none;">
        <?php if ($user_type == 5 || $user_type == 4) { ?>
            <a href="#" onclick="goToPayroll('calendar')" class="mobile_a_menu"><i
                    class="fa fa-address-card menu_mm"></i></a>
            <a href="#" onclick="goToPayroll('serviceRequest')" class="mobile_a_menu"><i
                    class="fa fa-address-card menu_mm"></i></a>
            <a href="#" onclick="goToPayroll('runpayroll')" class="mobile_a_menu"><i class="fa fa-money menu_mm"></i></a>
        <?php } ?>
        <?php if ($user_type == 2) { ?>
            <a href="#" onclick="goToPayroll('hq_show_firm')" class="mobile_a_menu"><i
                    class="fa fa-puzzle-piece menu_mm"></i></a>
            <a href="#" onclick="goToPayroll('hq_view_hr')" class="mobile_a_menu"><i class="fa fa-user-md menu_mm"></i></a>
        <?php } ?>
        <?php if ($user_type != 4) { ?>
            <a href="#" onclick="goToPayroll('hr_designation')" class="mobile_a_menu"><i
                    class="fa fa-black-tie menu_mm"></i></a>
            <a href="#" onclick="goToPayroll('hr_show_employee')" class="mobile_a_menu"><i
                    class="fa fa-id-card menu_mm"></i></a>
        <?php } ?>
        <?php if ($user_type == 5) { ?>
            <a href="#" onclick="goToPayroll('hr_show_holiday')" class="mobile_a_menu"><i
                    class="fa fa-umbrella menu_mm"></i></a>
            <a href="#" onclick="goToPayroll('MonthlyReport')" class="mobile_a_menu"><i
                    class="fa fa-calendar menu_mm"></i></a>
        <?php } ?>
        <a href="#" onclick="scheduleTaskTimeSheet('TimeSheet')"
            class="myCss <?php echo $url == '/scheduleTask' ? 'fixed_header' : 'myCss'; ?>">
            <div style="transform:skew(0deg)!important;"><i class="fa fa-tasks"></i></div>
        </a>
        <a href="#" onclick="goToPayroll('profile')" class="mobile_a_menu"><i class="fa fa-user menu_mm"></i></a>
    </div>

    <div class="planning_bar sameSubmenuClass" id="CRMsubmenuBar" style="display: none;">
        <a href="#" onclick="goToCrm('MassMail')" class="mobile_a_menu"><i class="fa fa-mail-bulk menu_mm"></i></a>
        <a href="#" onclick="goToCrm('Survey')" class="mobile_a_menu"><i class="fa fa-poll menu_mm"></i></a>
        <a href="#" onclick="goToTally('tally')" class="mobile_a_menu"><i class="fa fa-table menu_mm"></i></a>
        <a href="#" target="_blank" onclick="goToGlobalAct()" class="mobile_a_menu"><i class="fa fa-calculator menu_mm"></i></a>
        <a href="#" target="_blank" onclick="goToTicketEngine()" class="mobile_a_menu"><i class="fa fa-ticket-alt menu_mm"></i></a>
    </div>
</div>  


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
    /* When the user clicks on the button, toggle between hiding and showing the dropdown content */
    let lang = $("#language").val();
    if (lang == '' || lang == 'en') {
        setCookie('googtrans', '', -1, 'ecovisrkca.com');
    } else {
        function googleTranslateElementInit() {
            setCookie('googtrans', '/en/' + lang, 1);
            new google.translate.TranslateElement({
                pageLanguage: 'ES',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    }
    function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }
    function myFunctionDropDown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
    function myFunctionDropDown1() {
        document.getElementById("myDropdowndata").classList.toggle("show");
        var divsToHide = document.getElementsByClassName("sameSubmenuClass");
        for (var i = 0; i < divsToHide.length; i++) {

            divsToHide[i].style.display = "none";
        }
    }

    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function (event) {
        if (!event.target.matches('.dropbtn')) {

            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
        if (!event.target.matches('.dropbtn1')) {

            var dropdowns = document.getElementsByClassName("dropdown-content1");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    function hideShowMenuDiv(id) {
        var divsToHide = document.getElementsByClassName("sameSubmenuClass");
        for (var i = 0; i < divsToHide.length; i++) {

            divsToHide[i].style.display = "none";
        }

        var div_id = document.getElementById(id);
        div_id.style.display = "block";
    }

    let execution_url = "https://execution.ecovisrkca.com/";
    function goToHrms(id) {
        if (id == "execution") {
            window.location.href = execution_url + "dashboard";
        }
    }

    let rmt_url = "https://rmt.ecovisrkca.com/";
    function goToRmt(id) {
        if (id == "mail") {
            window.location.href = rmt_url + "email_client1";
        }
        if (id == "projectManagement") {
            window.location.href = rmt_url + "projectManagementHome";
        }
        if (id == "chat") {
            window.location.href = rmt_url + "messenger";
        }
        if (id == "folder") {
            window.location.href = rmt_url + "folder_desktop_view";
        }
        if (id == "board") {
            window.location.href = rmt_url + "MobileViewController/board";
        }
        if (id == "customer") {
            window.location.href = rmt_url + "viewcustomerMobile";
        }
        if (id == "employee") {
            window.location.href = rmt_url + "view_employeeMobile";
        }
        if (id == "task") {
            window.location.href = rmt_url + "task_mobile";
        }
        if (id == "service") {
            window.location.href = rmt_url + "service_mobile";

        }
        if (id == "view_officeMobile") {
            window.location.href = rmt_url + "view_officeMobile";
        }
        if (id == "designation_mobile") {
            window.location.href = rmt_url + "designation_mobile";
        }
        if (id == "customerserviceMobile") {
            window.location.href = rmt_url + "customerserviceMobile";
        }
        if (id == "PermissionMobile") {
            window.location.href = rmt_url + "PermissionMobile";
        }
        if (id == "BoardProject") {
            window.location.href = rmt_url + "BoardProject";
        }
    }

    function goToPayroll(id) {
        var payroll_url = '<?= base_url() ?>';
        window.location.href = payroll_url + id;
    }

    let amgt_url = 'https://amgt.ecovisrkca.com/';
    function goToCrm(id) {
        var email = $("#email_emp").val();
        window.location.href = amgt_url + "survey/LoginController/login_api/" + email + "/" + id;
    }
    let tally_url = 'https://actai_gbt.ecovisrkca.com/';
    function goToTally() {
        var email = $("#email_emp").val();
        window.location.href = tally_url + "LoginController/loginFromOtherWebsite?index=1&username=" + email;
    }

    function goToGlobalAct() {
        window.open('https://act.ecovisrkca.com/', '_blank');
    }

    function scheduleTaskTimeSheet(sheet) {
        var email = $("#email_emp").val();
        var base_url1 = '<?= base_url() ?>';
        window.location.href = base_url1 + "scheduleTask";
    }
    function goToBoardProject() {
        window.location.href = rmt_url + "BoardProject";
    }
    function goToTicketEngine() {
        var email = $("#email_emp").val();
        window.open('https://ticket.ecovisrkca.com/LoginController/login_api/' + email, '_blank');
    }

    function goToActAiJapan() {
        window.location.href = act_japan_url;
    }
    let glenmark = 'https://act.ecovisrkca.com/';
    function goToConsollidation() {
        window.location.href = glenmark;
    }

    function goToRepository() {
        window.location.href = "https://rmt.ecovisrkca.com/intranet";
    }
    function goToGoals(id) {
        window.location.href = rmt_url + "goals";
    }
    function wordReport(word_report) {
        var email = $("#email_emp").val();
        var base_url1 = '<?= base_url() ?>';
        window.open(base_url1 + 'word_report', '_blank');
        //window.location.href = base_url1+"word_report";
    }
</script>

<script>
    // sidemenu script
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }

    function toggleNav() {
        var sidebar = document.getElementById("mySidenav");
        var sidebarWidth = sidebar.style.width;

        if (sidebarWidth === "0px" || sidebarWidth === "") {
            // If the sidebar is closed or not set
            sidebar.style.width = "250px"; // Open the sidebar
        } else {
            // If the sidebar is open
            sidebar.style.width = "0"; // Close the sidebar
            $('.collapse').removeClass('show')
        }
    }

    function loginTicketManagement() {
        let firm = $("#firm_id").val();
        let email = $("#email_emp").val();
        if (!email) {
            alert('Email not found');
            return;
        }
        $.post("<?= base_url('ticket_login_by_payroll') ?>", {
            email: email,
            firm_id: firm
        }, function (res) {
            if (res.status === true) {
                window.location.href =
                    "http://localhost/ticket/login_from_payroll?token=" + res.token;
                // "https://ticket.ecovisrkca.com/login_from_payroll?token=" + res.token;
            } else {
                alert(res.message ?? 'Login failed');
            }
        }, 'json')
            .fail(function () {
                alert('Server error, please try again');
            });
    }
</script>
