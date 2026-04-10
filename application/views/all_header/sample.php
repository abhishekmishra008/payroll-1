<?php


if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');
$data['session_data'] = $session_data;
$user_id = ($session_data['user_id']);
$user_type = ($session_data['user_type']);
$user_name = ($session_data['user_name']);

$page_name = 'View Calender Holiday';
$page_name2 = 'View Due Date List';
$language='';
if(isset($session_data['language']))
{
    $language=$session_data['language'];
}
//var_dump($firm_name_dd);
//echo $firm_id_new;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style type="text/css">
    body
    {
        margin: 0px!important;
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
    .navbar{
        transition: all .5s;
        align-items: center;
        height: 60px;
        left: 250px;
        right: 5px;
        position: absolute;
        z-index: 890;
        background-color: transparent;
    }
    @media (min-width: 992px){
        .navbar-expand-lg {
            -ms-flex-flow: row nowrap;
            flex-flow: row nowrap;
            -ms-flex-pack: start;
            justify-content: flex-start;
        }
    }
    .myMenuIcons{
        background-color: #ffffff;
        padding: 10px 20px 10px 20px;
        /*border-radius: 20px 0px 20px 0px;*/
        -webkit-box-shadow: 0px 2px 5px 2px #afaeae;
        margin-right: 10px;
        font-size: 20px!important;
        color: #495057;
    }
    .myMenuIcons:focus
    {
        background-color: #d2454d;
        color: #ffffff;
    }
    .has_skew{
        transform:skew(-30deg);
        text-decoration:none;
    }
    .has_no_skew
    {
        transform:skew(30deg)!important;
    }
</style>
<style type="text/css">
    /* Dropdown Button */
    .dropbtn {
        background-color: transparent;
        color: black;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    /* Dropdown button on hover & focus */
    .dropbtn:hover, .dropbtn:focus {
        background-color: transparent;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: transparent;
        min-width: 160px;
        /*box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);*/
    }
    .dropdown-content1 {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        /*box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);*/
    }
    /* Links inside the dropdown */
    .dropdown-content1 a {
        color: black;
        padding: 12px 20px;
        text-decoration: none;
        display: block;
        -webkit-box-shadow: 0px 2px 6px 0px #cccccc;
        /*text-align: center;*/
    }

    /* Change color of dropdown links on hover */
    .dropdown-content1 a:hover {background-color: #f1f1f1}

    /* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
    .show {display:block;}
    .show1 {display:block;}
</style>
<style>

    .navbar1 {
        /*overflow: hidden;*/
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
        /*overflow: hidden;*/
    }

    .subnav1 .subnavbtn1 {
        font-size: 16px;
        border: none;
        outline: none;
        color: black;
        padding: 14px 16px;
        background-color: white;
        font-family: inherit;
        margin: 0;
    }

    .navbar1 a:hover, .subnav1:hover .subnavbtn1 {
        background-color: #d2454d;
        color: white;
    }

    .subnav-content1 {
        display: none;
        position: absolute;
        /*left: 0;*/
        background-color: transparent;
        /*width: 100%;*/
        z-index: 1;
        /*margin-top: 10px;*/
        margin-left: -15px;
        /*padding-top:10px; */
        /*-webkit-box-shadow: 0px 2px 5px 2px #afaeae;*/

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
    .dropbtn:focus
    {
        outline: none;
    }
</style>
<style type="text/css">
    .myCss
    {
        background-color: #ffffff;
        padding: 10px 20px 10px 20px;
        /* border-radius: 20px 0px 20px 0px; */
        -webkit-box-shadow: 0px 2px 5px 2px #afaeae;
        margin-right: 10px;
        font-size: 17px!important;
        color: #495057;
        transform: skew(
            -30deg
        );
        text-decoration: none;
    }
    .icon_size
    {
        font-size: 40px!important;
    }
    .my_menu_child
    {
        background-color: #ffffff!important;
        height: 47px!important;
        margin-top: 10px!important;
        -webkit-box-shadow: 0px 2px 5px 2px #afaeae!important;
    }
    .rkca_logo
    {
        width: 180px;
        height: 40px;
        /*padding-left: 55px;*/
        transform:skew(30deg);
        vertical-align: middle!important;
        border-style: none;
        margin-top: 7px;
    }
</style>
<style type="text/css">
    #desktop_view
    {
        display: block;
    }

    #mobile_view
    {
        display: none;
    }
    .desktop_view{
        display: block;
    }
    .mobile_view{
        display: none !important;
    }
    /* mobile view*/
    @media (max-width: 800.98px) {
        #desktop_view
        {
            display: none;
        }
        .desktop_view{
            display: none !important;
        }
        #mobile_view
        {
            display: block;
        }
        .mobile_view{
            display: flex !important;
        }
        .rkca_logo
        {
            width: 150px;
            height: 41px;
            /*padding-left: 20px;*/
            transform:skew(30deg);
            vertical-align: middle!important;
            border-style: none;
            margin-top: 0px;
        }
        .mobile_a_menu
        {
            font-size: 24px!important;
            color:#d2454d;
            padding: 8px;
            transform: skew( 30deg);
        }
        .mobile_a_menu:hover
        {
            -webkit-box-shadow: 0px 2px 7px 4px #ccc;
            transform: skew( 30deg);
            padding: 5px 15px 5px 15px;
        }
        .menu_mm
        {
            transform: skew( 30deg);
        }
        .execution_bar
        {
            position: relative;
            top: 100px;
            z-index: 4;
            height: 40px;
            background-color: white;
            -webkit-box-shadow: 0px 2px 7px 4px #ccc;
            transform: skew( -30deg,0deg);
            left: -58px;
            padding-left: 60px;

        }
        .planning_bar
        {
            position: relative;
            top: 100px;
            z-index: 4;
            height: 40px;
            background-color: white;
            -webkit-box-shadow: 0px 2px 7px 4px #ccc;
            transform: skew( -30deg,0deg);
            left: -58px;
            padding-left: 60px;

        }
    }


    #myDropdown1
    {
        right: 0;
        left: auto;width: 506px!important;background: transparent!important;margin-top: 15px;
    }
    .fixed_header{
        background-color: #d2454d !important;
        color: white !important;
    }
</style>
<style>
    body {
        font-family: "Lato", sans-serif;
    }
    .sidenav .card{
        margin-bottom: 0px;
    }
    .sidenav {
        height: 100%;
        width: 0px;
        position: fixed;
        z-index: 1;
        /*top: 8.5%;*/
        /*top: calc(100% - 890px);*/
        left: 0;
        background-color:white;
        overflow-x: hidden;
        transition: 0.5s;
        /* padding-top: 60px; */
        flex: 0 0 auto; /* Prevent stretching */
        margin-top: 24px; /* Adjust this value as needed */
        padding-top: 15px;
    }
    .card .card-body{
        padding-top: 7px;
        padding-bottom: 7px;
        padding-left: 45px;
        padding-right: 20px;
        margin-top: 15px !important;
    }

    .sidenav a {
        /* padding: 8px 8px 8px 32px; */
        text-decoration: none;
        font-size: 14px;
        color: black;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover {
        background-color: #d2454d;
        color: white;
    }
    .k-store{
        font-size: 14px !important;
    }
    .sidenav a:hover {
        color: black;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 6px;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }
    .card{
        border: none;
        background:none;
        color: black;
        background-color: #fff !important;
    }
    .btn{
        text-align: left;
    }
    .card-header{
        border-bottom: none;
        background: navy;
    }

    .card-header:hover{
        background-color: #a5a7a952;
    }

    .card .collapse .card .card-body:hover{
        background-color: #a5a7a952;
    }
    .fas{
        margin: 0px 7px;
    }
    /* .card-header:active{
      background-color: rgb(252, 187, 187);
    } */
    .collapse{
        background: white;
        border-right: 2px solid #ededed;
    }
    .fa-chevron-down{
        font-size: 10px;
    }
    .options{
        font-weight: 500;
    }
    .sidenav .fas{
        font-size:11px
    }
    .con {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
    }
    .card-header .mb-0 .btn-link:hover{
        background-color: transparent;
    }
    .headerCard {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03) !important;
        background-color: #fff !important;
        border-radius: 3px !important;
        border: none !important;
        position: relative !important;
    }
</style>
<style>#google_translate_element,.skiptranslate{display:none!important;}body{top:0!important;}.VIpgJd-yAWNEb-VIpgJd-fmcmS-sn54Q{
                                                                                                  background-color: transparent;box-shadow: none;}</style>
<input type="hidden" name="language" id="language" value="<?= $language ?>">
<input type="hidden" id="email_emp" value="<?php echo $user_id ?>">
<div class="timesheetheader"  style="display: flex;flex-direction: column">
    <div style="left: 0;right: 0;height: 90px;">
        <div class="con"

        >
            <div id="mySidenav" style="z-index: 999;margin-top: 86px;box-shadow: lightgrey 2px 0px 0px 0px;width: 0px;" class="sidenav">
                <!-- <div class="" style="background: #ffffff;padding: 10px 10px;"> -->
                <!-- <h5 style="padding: 9px 46px;color: #D2454D;">Mentor Suite</h5> -->
                <!-- <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a> -->
                <!-- </div>  -->

                <div id="accordion" style="margin-top: 20px;padding-left: 20px;">
                    <!--			--><?php //if(in_array(1,$menuAccess)){ ?>
                    <div class="card headerCard">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <a class="btn btn-link text-left" data-toggle="" onclick="goToHrms('execution')" data-target="#" aria-expanded="false" aria-controls="collapseOne">
                                    <i class="fas fa-play-circle" style="font-size: 14px; color: #0075ff"></i>
                                    <span class="options">  Home</span>
                                </a>
                            </h5>
                        </div>
                    </div>
                    <!--			--><?php //} ?>

                    <!--			--><?php //if(in_array(3,$menuAccess) || in_array(4,$menuAccess) || in_array(5,$menuAccess) || in_array(6,$menuAccess) ){ ?>
                    <div class="card headerCard">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <a class="btn btn-link collapsed options text-left" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="fas fa-calendar-alt " style="font-size: 14px; color: orange"></i>
                                    Plan <i class="fas fa-chevron-down first"></i>
                                </a>
                            </h5>
                        </div>

                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <!--						--><?php //if(in_array(3,$menuAccess)){ ?>
                            <div class="card" onclick="goToRmt('projectManagement')" >
                                <div class="card-body sub-card">
                                    <i class="fas fa-tasks" style="font-size: 12px; color: #63ed7a"></i>  Project Management
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(4,$menuAccess)){ ?>
                            <div class="card" onclick="goToRmt('board')">
                                <div class="card-body sub-card">
                                    <i class="fas fa-clipboard" style="font-size: 12px; color: #f33052"></i>
                                    Board
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(5,$menuAccess)){ ?>
                            <div class="card" onclick="goToRmt('service')">
                                <div class="card-body sub-card">
                                    <i class="fas fa-database" style="font-size: 12px; color: #917b08"></i>
                                    Master Data
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(6,$menuAccess)){ ?>
                            <div class="card" onclick="goToRmt('audit')">
                                <div class="card-body sub-card">
                                    <i class="fas fa-history" style="font-size: 12px; color: #ec7f99"></i>
                                    Audit
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                        </div>
                    </div>

                    <!--			--><?php //} ?>

                    <!--			--><?php //if(in_array(8,$menuAccess) || in_array(9,$menuAccess) || in_array(10,$menuAccess)){ ?>
                    <div class="card headerCard">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <a class="btn btn-link collapsed options text-left" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <i class="fas fa-users" style="font-size: 14px; color: #19b927"></i>
                                    Collaborations <i class="fas fa-chevron-down"></i>
                                </a>
                            </h5>
                        </div>

                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <!--						--><?php //if(in_array(8,$menuAccess)){ ?>
                            <div class="card" onclick="goToRmt('mail')">
                                <div class="card-body sub-card">
                                    <i class="fas fa-envelope" style="font-size: 12px; color: blueviolet"></i>
                                    Mail
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(9,$menuAccess)){ ?>
                            <div class="card" onclick="goToRmt('chat')">
                                <div class="card-body sub-card">
                                    <i class="fas fa-comments" style="font-size: 12px; color: #65ccff"></i>  Chats
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(10,$menuAccess)){ ?>
                            <div class="card" onclick="goToRmt('folder')">
                                <div class="card-body sub-card" >
                                    <i class="fas fa-folder" style="font-size: 12px; color: #d9c424"></i> Folder
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                        </div>
                    </div>
                    <!--			--><?php //} ?>
                    <!--			--><?php //if(in_array(12,$menuAccess) || in_array(13,$menuAccess) || in_array(14,$menuAccess) || in_array(15,$menuAccess) || in_array(16,$menuAccess) || in_array(17,$menuAccess)){ ?>
                    <div class="card headerCard">
                        <div class="card-header" id="headingFour">
                            <h5 class="mb-0">
                                <a class="btn btn-link collapsed options text-left" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    <i class="fas fa-wrench" style="font-size: 14px; color: #c52120"></i>
                                    Tools <i class="fas fa-chevron-down "></i>
                                </a>
                            </h5>
                        </div>

                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <!--						--><?php //if(in_array(12,$menuAccess)){ ?>
                            <div class="card" onclick="goToCrm('MassMail')">
                                <div class="card-body sub-card">
                                    <i class="fas fa-envelope" style="font-size: 12px; color: blueviolet"></i>
                                    Mail
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(13,$menuAccess)){ ?>
                            <div class="card" onclick="goToCrm('Survey')">
                                <div class="card-body sub-card">
                                    <i class="fas fa-poll" style="font-size: 12px; color: grey"></i>
                                    Survey
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(14,$menuAccess)){ ?>

                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(15,$menuAccess)){ ?>
                            <div class="card" onclick="goToTally('Tally')">
                                <div class="card-body sub-card">
                                    <i class="fas fa-calculator" style="font-size: 12px; color: #19b9ac"></i>
                                    Tally
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(16,$menuAccess)){ ?>
                            <div class="card" onclick="goToGlobalAct()">
                                <div class="card-body sub-card">
                                    <i class="fas fa-globe" style="font-size: 12px; color: #0d7bdd"></i>
                                    Global Accounting
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                            <!--						--><?php //if(in_array(17,$menuAccess)){ ?>
                            <div class="card" onclick="goToTicketEngine()">
                                <div class="card-body sub-card">
                                    <i class="fas fa-ticket-alt" style="font-size: 12px; color: #ef7c15"></i> Ticket Request
                                </div>
                            </div>
                            <!--						--><?php //} ?>
                        </div>
                    </div>
                    <!--			--><?php //} ?>
                </div>

            </div>
        </div>
        <div class="navbar-bg" style="background: #ffffff;height: 10px;left: 0;right: 0;"></div>
        <div class="navbar-bg" style="background: #d2454d;height: 10px;left: 0;right: 0;margin-top: 10px;"></div>
        <div class="navbar-bg mybg" style="background: transparent;    "></div>

        <nav class="navbar navbar-expand-lg main-navbar" style="margin-top: 30px;left: -25px;-webkit-box-shadow: 0px 2px 7px 4px #ccc; transform:skew(-30deg,0deg);  background: white;width: 100%;display:flex;">
            <!--         <div>--><?php //$this->load->view("all_header/sideMenu.php"); ?><!--</div>-->

            <span id="hamburgerMenu" style="font-size:30px;cursor:pointer;margin: 2px 20px 9px 50px;transform: skew(30deg,0deg);" onclick="toggleNav()">&#9776;</span>

            <img id="logo_img" src="<?php echo base_url(); ?>assets/img/ECOVIS RKCA - Logo.png" class="rkca_logo" alt="Gold Berries">

            <div class="dropdown" id="desktop_view" style="transform:skew(30deg); margin-left: auto!important;float: right;margin-top: 1px;background: transparent;">
                <?php if ($this->session->userdata('user_type') == 6) { ?>
                <?php } else { ?>
                    <i class="fa fa-user"> </i> <span style="font-size: 14px;color:#891635;font-weight: bold;margin-right: 30px">Hi, <?php echo $user_name; ?> </span>
                <?php } ?>
                <!--           <button onclick="myFunctionDropDown()" class="dropbtn">-->
                <!--			Mentor Suite<i class="fa fa-sort-down "></i></button>-->
                <div id="myDropdown" class="dropdown-content" style="
    right: 0;
    left: auto;width: 570px;background: transparent;margin-top: 5px;
">
                    <div class="navbar1">
                        <a href="#" onclick="goToHrms('execution')" class="myCss"><div style="transform:skew(30deg)!important;">Execution</div></a>
                        <div class="subnav1">
                            <button class="subnavbtn1 myCss" style="padding: 7px 15px 7px 15px;margin-right: 10px;font-weight: 500;"><div style="transform:skew(30deg)!important;">Planning <i class="fa fa-caret-down"></i></div></button>
                            <div class="subnav-content1">
                                <div class="my_menu_child">
                                    <!--          <a href="#" title="Board Project" onclick="goToRmt('BoardProject')"><i class="fa fa-list" style="font-size: 30px!important;color:#d2454d;"></i></a>-->
                                    <a href="#" title="Project Management"onclick="goToRmt('projectManagement')"><i class="fa fa-list" style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    <a href="#" title="Board" onclick="goToRmt('board')"><i class="fa fa-address-card" style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    <a href="#" title="Board" onclick="goToRmt('service')"><i class="fa fa-caret-square-down" style="font-size: 30px!important;color:#d2454d;"></i></a>


                                    <!--  <a href="#" title="Customer" onclick="goToRmt('customer')"><i class="fa fa-user" style="font-size: 30px!important;color:#d2454d;"></i></a>
                                      <a href="#" title="Employee" onclick="goToRmt('employee')"><i class="fa fa-users" style="font-size: 30px!important;color:#d2454d;"></i></a>
                                      <a href="#" title="Task" onclick="goToRmt('task')"><i class="fa fa-clipboard " style="font-size: 30px!important;color:#d2454d;"></i></a>
                                      <a href="#" title="Service & Offerings" onclick="goToRmt('service')"><i class="fas fa-cubes " style="font-size: 30px!important;color:#d2454d;"></i></a>-->
                                </div>
                            </div>
                        </div>
                        <div class="subnav1">
                            <button class="subnavbtn1 myCss" style="padding: 7px 15px 7px 15px;margin-right: 10px;font-weight: 500;"><div style="transform:skew(30deg)!important;">Collaboration <i class="fa fa-caret-down"></i></div></button>
                            <div class="subnav-content1">
                                <div class="my_menu_child">
                                    <a href="#" title="Mail" onclick="goToRmt('mail')"><i class="fa fa-envelope" style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    <a href="#" title="Chat" onclick="goToRmt('chat')"><i class="fa fa-comments" style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    <a href="#" title="Folder" onclick="goToRmt('folder')"><i class="fa fa-folder" style="font-size: 30px!important;color:#d2454d;"></i></a>

                                </div>
                            </div>
                        </div>

                        <div class="subnav1">
                            <button class="subnavbtn1 myCss" style="padding: 7px 15px 7px 15px;margin-right: 10px;font-weight: 500;"><div style="transform:skew(30deg)!important;">Tools <i class="fa fa-caret-down"></i></div></button>
                            <div class="subnav-content1">
                                <div class="my_menu_child" style="width: 300px;margin-right: 40% !important;position: relative;right: 210px;">
                                    <a href="#" title="Mail" onclick="goToCrm('MassMail')"><i class="fa fa-mail-bulk"
                                                                                              style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    <a href="#" title="Master Data" onclick="goToCrm('Survey')"><i class="fa fa-poll"
                                                                                                   style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    <a href="#" title="Tally" onclick="goToTally('Tally')"><i class="fa fa-table"
                                                                                              style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    <a href="#" title="Global Accounting" target="_blank" onclick="goToGlobalAct()"><i class="fa fa-calculator"
                                                                                                                       style="font-size: 30px!important;color:#d2454d;"></i></a>
                                    <a href="#" title="Ticket Request" target="_blank" onclick="goToTicketEngine()"><i class="fa fa-ticket-alt" style="font-size: 30px!important;color:#d2454d;"></i></a>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div id="mobile_view" style="transform:skew(30deg); margin-left: auto!important;float: right;margin-top: 1px;background: transparent;">
                <!--        <button type="button" class="btn btn-link text-muted" style="font-size: 20px;"><i onclick="myFunctionDropDown1()" class="fa fa-th dropbtn1"></i></button>-->
                <!--         <div id="myDropdowndata" class="dropdown-content1" style="-->
                <!--            right: 0;left: auto;width: 200px;margin-top: 15px;">-->
                <!--            <a onclick="goToHrms('execution')" class="Execution">Execution</a>-->
                <!--            <a onclick="hideShowMenuDiv('PlanningsubmenuBar')" class="Planning">Planning</a>-->
                <!--            <a onclick="hideShowMenuDiv('CommunicationsubmenuBar')" class="Commumnication">Collaboration</a>-->
                <!--            <a onclick="hideShowMenuDiv('FinancialsubmenuBar')" class="Financial">Payroll Menus</a>-->
                <!--            <a onclick="hideShowMenuDiv('CRMsubmenuBar')" class="CRM">Tools</a>-->
                <!--        </div>-->
            </div>
            <a href="<?= base_url('Login/admin_logout'); ?>" style="color:black!important;transform:skew(30deg)!important;">
                Logout <i class="fas fa-sign-out-alt text-muted"></i>
            </a>
        </nav>

    </div>
    <div id="myDropdown1" class="d-flex flex-row justify-content-end desktop_view" style="height:4%;width: 96% !important;margin-top: 15px;display: flex;flex-direction: row;justify-content: end;">
        <div class="navbar1">
            <?php $url=$_SERVER['REQUEST_URI']; ?>
            <?php if ($user_type == 5 || $user_type == 4) { ?>
                <a href="#" onclick="goToPayroll('calendar')" class="myCss <?php echo $url == '/calendar' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">Calendar</div></a>

                <a href="#" onclick="goToPayroll('serviceRequest')" class="myCss <?php echo $url == '/serviceRequest' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">Service Request</div></a>
                <a href="#" onclick="goToPayroll('runpayroll')" class="myCss <?php echo $url == '/runpayroll' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">Run Payroll</div></a>

            <?php } ?>
            <?php if ($user_type == 2) { ?>
                <a href="#" onclick="goToPayroll('hq_show_firm')" class="myCss <?php echo $url == '/hq_show_firm' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">View Office</div></a>
                <a href="#" onclick="goToPayroll('hq_view_hr')" class="myCss <?php echo $url == '/hq_view_hr' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">View  HR</div></a>

            <?php } ?>
            <?php if ($user_type != 4) { ?>
                <a href="#" onclick="goToPayroll('hr_designation')" class="myCss <?php echo $url == '/hr_designation' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">Designation</div></a>
                <a href="#" onclick="goToPayroll('hr_show_employee')" class="myCss <?php echo $url == '/hr_show_employee' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">View Employee</div></a>

            <?php } ?>
            <?php if ($user_type == 5) { ?>
                <a href="#" onclick="goToPayroll('hr_show_holiday')" class="myCss <?php echo $url == '/hr_show_holiday' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">Holidays</div></a>
                <a href="#" onclick="goToPayroll('MonthlyReport')" class="myCss <?php echo $url == '/MonthlyReport' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">Monthly Report</div></a>

            <?php } ?>
            <a href="#" onclick="scheduleTaskTimeSheet('TimeSheet')" class="myCss <?php echo $url == '/scheduleTask' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">Daily Activity</div></a>
            <!--<a href="#" onclick="wordReport('word_report')" class="myCss <?php /*echo $url == '/word_report' ? 'fixed_header' : 'myCss'; */?>"><div style="transform:skew(30deg)!important;"> Word Report</div></a>-->
            <a href="#" onclick="goToPayroll('profile')" class="myCss <?php echo $url == '/profile' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;">Profile</div></a>
            <!--		  --><?php //if ($user_type == 5) { ?>
            <!--			  <a href="#" onclick="goToPayroll('form_16_creation')" class="myCss --><?php //echo $url == '/form_16_creation' ? 'fixed_header' : 'myCss'; ?><!--"><div style="transform:skew(30deg)!important;">Form-16 Creation</div></a>-->
            <!--		  --><?php //} ?>
            <!--		  <a href="#" onclick="goToPayroll('form_16_submission')" class="myCss --><?php //echo $url == '/form_16_submission' ? 'fixed_header' : 'myCss'; ?><!--"><div style="transform:skew(30deg)!important;">Form-16</div></a>-->

        </div>
    </div>
    <div id="myDropdown2" class="d-flex flex-row justify-content-end mobile_view" style="height:4%;width: 87% !important;margin-top: 15px;display: flex;flex-direction: row;justify-content: end;">
        <div class="navbar1">
            <?php $url=$_SERVER['REQUEST_URI']; ?>
            <?php if ($user_type == 5 || $user_type == 4) { ?>
                <a href="#" onclick="goToPayroll('calendar')" class="myCss <?php echo $url == '/calendar' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-calendar"></i></div></a>
                <a href="#" onclick="goToPayroll('serviceRequest')" class="myCss <?php echo $url == '/serviceRequest' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-tasks"></i></div></a>
                <a href="#" onclick="goToPayroll('runpayroll')" class="myCss <?php echo $url == '/runpayroll' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-money"></i></div></a>

            <?php } ?>
            <?php if ($user_type == 2) { ?>
                <a href="#" onclick="goToPayroll('hq_show_firm')" class="myCss <?php echo $url == '/hq_show_firm' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-bank"></i></div></a>
                <a href="#" onclick="goToPayroll('hq_view_hr')" class="myCss <?php echo $url == '/hq_view_hr' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-user"></i></div></a>

            <?php } ?>
            <?php if ($user_type != 4) { ?>
                <a href="#" onclick="goToPayroll('hr_designation')" class="myCss <?php echo $url == '/hr_designation' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-medal"></i></div></a>
                <a href="#" onclick="goToPayroll('hr_show_employee')" class="myCss <?php echo $url == '/hr_show_employee' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-users"></i></div></a>

            <?php } ?>
            <?php if ($user_type == 5) { ?>
                <a href="#" onclick="goToPayroll('hr_show_holiday')" class="myCss <?php echo $url == '/hr_show_holiday' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-tasks"></i></div></a>
                <a href="#" onclick="goToPayroll('MonthlyReport')" class="myCss <?php echo $url == '/MonthlyReport' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-calendar-week"></i></div></a>

            <?php } ?>
            <a href="#" onclick="scheduleTaskTimeSheet('TimeSheet')" class="myCss <?php echo $url == '/scheduleTask' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-tasks"></i></div></a>
            <a href="#" onclick="goToPayroll('profile')" class="myCss <?php echo $url == '/profile' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-address-card"></i></div></a>
            <!--			  --><?php //if ($user_type == 5) { ?>
            <!--				  <a href="#" onclick="goToPayroll('form_16_creation')" class="myCss --><?php //echo $url == '/form_16_creation' ? 'fixed_header' : 'myCss'; ?><!--"><div style="transform:skew(30deg)!important;"><i class="fa fa-wpforms"></i></div></a>-->
            <!--			  --><?php //} ?>
            <!--			  <a href="#" onclick="goToPayroll('form_16_submission')" class="myCss --><?php //echo $url == '/form_16_submission' ? 'fixed_header' : 'myCss'; ?><!--"><div style="transform:skew(30deg)!important;"><i class="fa fa-book-open"></i></div></a>-->

        </div>
    </div>
</div>
<div class="execution_bar sameSubmenuClass" id="ExecutionsubmenuBar" style="display: none;">

</div>
<div class="planning_bar sameSubmenuClass" id="PlanningsubmenuBar" style="display: none;">
    <!--         <a href="#" onclick="goToRmt('BoardProject')" class="mobile_a_menu"><i class="fa fa-list-alt menu_mm"-->
    <!--             ></i></a>-->
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
        <a href="#" onclick="goToPayroll('calendar')" class="mobile_a_menu"><i class="fa fa-address-card menu_mm"></i></a>
        <a href="#" onclick="goToPayroll('serviceRequest')" class="mobile_a_menu"><i class="fa fa-address-card menu_mm"></i></a>
        <a href="#" onclick="goToPayroll('runpayroll')" class="mobile_a_menu"><i class="fa fa-money menu_mm"></i></a>
    <?php } ?>
    <?php if ($user_type == 2) { ?>
        <a href="#" onclick="goToPayroll('hq_show_firm')" class="mobile_a_menu"><i class="fa fa-puzzle-piece menu_mm"></i></a>
        <a href="#" onclick="goToPayroll('hq_view_hr')" class="mobile_a_menu"><i class="fa fa-user-md menu_mm"></i></a>
    <?php } ?>
    <?php if ($user_type != 4) { ?>
        <a href="#" onclick="goToPayroll('hr_designation')" class="mobile_a_menu"><i class="fa fa-black-tie menu_mm"></i></a>
        <a href="#" onclick="goToPayroll('hr_show_employee')" class="mobile_a_menu"><i class="fa fa-id-card menu_mm"></i></a>
    <?php } ?>
    <?php if ($user_type == 5) { ?>
        <a href="#" onclick="goToPayroll('hr_show_holiday')" class="mobile_a_menu"><i class="fa fa-umbrella menu_mm"></i></a>
        <a href="#" onclick="goToPayroll('MonthlyReport')" class="mobile_a_menu"><i class="fa fa-calendar menu_mm"></i></a>
    <?php } ?>
    <a href="#" onclick="scheduleTaskTimeSheet('TimeSheet')" class="myCss <?php echo $url == '/scheduleTask' ? 'fixed_header' : 'myCss'; ?>"><div style="transform:skew(30deg)!important;"><i class="fa fa-tasks"></i></div></a>
    <a href="#" onclick="goToPayroll('profile')" class="mobile_a_menu"><i class="fa fa-user menu_mm"></i></a>
</div>
<div class="planning_bar sameSubmenuClass" id="CRMsubmenuBar" style="display: none;">
    <a href="#" onclick="goToCrm('MassMail')" class="mobile_a_menu"><i class="fa fa-mail-bulk menu_mm"
        ></i></a>
    <a href="#"  onclick="goToCrm('Survey')" class="mobile_a_menu"><i class="fa fa-poll menu_mm"
        ></i></a>
    <a href="#" onclick="goToTally('tally')" class="mobile_a_menu"><i class="fa fa-table menu_mm"
        ></i></a>
    <a href="#" target="_blank" onclick="goToGlobalAct()" class="mobile_a_menu"><i class="fa fa-calculator menu_mm"
        ></i></a>
    <a href="#" target="_blank" onclick="goToTicketEngine()" class="mobile_a_menu"><i class="fa fa-ticket-alt menu_mm"
        ></i></a>
</div>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>-->
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
    /* When the user clicks on the button,
  toggle between hiding and showing the dropdown content */
    let lang=$("#language").val();
    if(lang=='' || lang=='en')
    {
        setCookie('googtrans', '', -1,'ecovisrkca.com');
    }else {
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
        var divsToHide=document.getElementsByClassName("sameSubmenuClass");
        for(var i = 0; i < divsToHide.length; i++){

            divsToHide[i].style.display = "none";
        }
    }

    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function(event) {
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
    function hideShowMenuDiv(id)
    {
        var divsToHide=document.getElementsByClassName("sameSubmenuClass");
        for(var i = 0; i < divsToHide.length; i++){

            divsToHide[i].style.display = "none";
        }

        var div_id=document.getElementById(id);
        div_id.style.display="block";
    }
    let execution_url="https://execution.ecovisrkca.com/";
    function goToHrms(id){
        if(id=="execution")
        {
            window.location.href=execution_url+"dashboard";
        }
    }
    let rmt_url="https://rmt.ecovisrkca.com/";
    function goToRmt(id) {
        if(id == "mail"){
            window.location.href=rmt_url+"email_client1";
        }
        if(id=="projectManagement"){
            window.location.href=rmt_url+"projectManagementHome";
        }
        if(id=="chat"){
            window.location.href=rmt_url+"messenger";
        }
        if(id=="folder"){
            window.location.href=rmt_url+"folder_desktop_view";
        }
        if(id=="board"){
            window.location.href=rmt_url+"MobileViewController/board";
        }
        if(id=="customer"){
            window.location.href=rmt_url+"viewcustomerMobile";
        }
        if(id=="employee"){
            window.location.href=rmt_url+"view_employeeMobile";
        }
        if(id=="task"){
            window.location.href=rmt_url+"task_mobile";
        }
        if(id=="service"){
            window.location.href=rmt_url+"service_mobile";

        }
        if(id=="view_officeMobile"){
            window.location.href=rmt_url+"view_officeMobile";
        }
        if(id=="designation_mobile"){
            window.location.href=rmt_url+"designation_mobile";
        }
        if(id=="customerserviceMobile"){
            window.location.href=rmt_url+"customerserviceMobile";
        }
        if(id=="PermissionMobile"){
            window.location.href=rmt_url+"PermissionMobile";
        }
        if(id=="BoardProject")
        {
            window.location.href = rmt_url + "BoardProject";
        }

    }

    function goToPayroll(id){
        // var	payroll_url='https://payroll.ecovisrkca.com/';
        var	payroll_url='<?= base_url()?>';
        window.location.href=payroll_url+id;
    }

    let amgt_url='https://amgt.ecovisrkca.com/';
    function goToCrm(id) {
        var email=$("#email_emp").val();
        window.location.href = amgt_url+"survey/LoginController/login_api/"+email+"/" + id ;
    }
    let tally_url='https://actai_gbt.ecovisrkca.com/';
    function goToTally() {
        var email=$("#email_emp").val();
        window.location.href = tally_url+"LoginController/loginFromOtherWebsite?index=1&username="+email;
    }

    function goToGlobalAct() {
        window.open('https://act.ecovisrkca.com/','_blank');
    }

    function scheduleTaskTimeSheet(sheet)
    {
        var email=$("#email_emp").val();
        var base_url1 = '<?=base_url()?>';
        //var base_url1 = 'https://payroll.ecovisrkca.com/';
        window.location.href = base_url1+"scheduleTask";
    }
    function goToBoardProject() {
        window.location.href = rmt_url + "BoardProject";
    }
    function goToTicketEngine() {
        var email=$("#email_emp").val();
        window.open('https://ticket.ecovisrkca.com/LoginController/login_api/'+email,'_blank');
    }

    function wordReport(word_report){
        var email=$("#email_emp").val();
        var base_url1 = '<?=base_url()?>';
        window.open(base_url1+'word_report','_blank');
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



</script>
<script>

    function goToRmt(id) {
        if(id == "mail"){
            window.location.href=rmt_url+"email_client1";
        }
        if(id=="projectManagement"){
            window.location.href=rmt_url+"projectManagementHome";
        }
        if(id=="chat"){
            window.location.href=rmt_url+"messenger";
        }
        if(id=="folder"){
            window.location.href=rmt_url+"folder_desktop_view";
        }
        if(id=="board"){
            window.location.href=rmt_url+"MobileViewController/board";
        }
        if(id == 'audit'){
            var email=$("#email_emp").val();
            window.location.href= 'https://audit.ecovisrkca.com/LoginController/login_api/'+email;
        }
        if(id=="customer"){
            window.location.href=rmt_url+"viewcustomerMobile";
        }
        if(id=="employee"){
            window.location.href=rmt_url+"view_employeeMobile";
        }
        if(id=="task"){
            window.location.href="https://rmt.docango.com/task_mobile";
        }
        if(id=="service"){
            window.location.href=rmt_url+"service_mobile";

        }
        if(id=="view_officeMobile"){
            window.location.href=rmt_url+"view_officeMobile";
        }
        if(id=="designation_mobile"){
            window.location.href=rmt_url+"designation_mobile";
        }
        if(id=="customerserviceMobile"){
            window.location.href=rmt_url+"customerserviceMobile";
        }
        if(id=="PermissionMobile"){
            window.location.href=rmt_url+"PermissionMobile";
        }
        if(id=="BoardProject")
        {
            window.location.href = rmt_url + "BoardProject";
        }

    }
    function goToCrm(id) {
        var email=$("#email_emp").val();
        window.location.href = amgt_url+"survey/LoginController/login_api/"+email+"/" + id ;

    }
    tally_url='https://actai_gbt.ecovisrkca.com/';
    function goToTally() {
        var email=$("#email_emp").val();
        window.location.href = tally_url+"LoginController/loginFromOtherWebsite?index=1&username="+email;
    }

    function goToGlobalAct() {
        window.open('https://act.ecovisrkca.com/','_blank');
    }

    function goToTicketEngine() {
        var email=$("#email_emp").val();
        window.open('https://ticket.ecovisrkca.com/LoginController/login_api/'+email,'_blank');
    }


</script>
<script>
    $('.btn').click(function(){
        $(this).toggleClass("click");
        $('.sidebar').toggleClass("show");
    });
    $('.feat-btn').click(function(){
        $('nav ul .feat-show').toggleClass("show");
        $('nav ul .first').toggleClass("rotate");
    });
    $('.serv-btn').click(function(){
        $('nav ul .serv-show').toggleClass("show1");
        $('nav ul .second').toggleClass("rotate");
    });
    $('.tool-btn').click(function(){
        $('nav ul .tool-show').toggleClass("show2");
        $('nav ul .third').toggleClass("rotate");
    });
    $('nav ul li').click(function(){
        $(this).addClass("active").siblings().removeClass("active");
    });

    $(document).mouseup(function(e)
    {
        var container = $("#mySidenav");
        var menu = $('#hamburgerMenu');
        var sidebar = document.getElementById("mySidenav");
        var sidebarWidth = sidebar.style.width;
        if (sidebarWidth === "250px") {
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0 && !menu.is(e.target)) {
                sidebar.style.width = "0"; // Close the sidebar
                $('.collapse').removeClass('show')
            }
        }
    });
</script>
<?php
