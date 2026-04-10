<?php
$this->load->view('human_resource/header');
$user_id = $this->session->userdata('login_session');
$user_type = $user_id['user_type'];
?>
<div class="clearfix"> </div>
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

<!--                <li class="<?= ($this->uri->segment(1) === 'hr_dashboard') ? 'active' : '' ?>">
                    <a href="<?= base_url() ?>hr_dashboard" class="nav-link nav-toggle">
                        <i class="icon-home"></i>
                        <span class="title">Dashboard</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                </li>-->
                <?php if ($user_type == 4 || $user_type == 5) { ?>
                    <li class="<?= ($this->uri->segment(1) === 'calendar') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>calendar" class="nav-link nav-toggle">
                            <i class="icon-calendar"></i>
                            <span class="title">Calendar</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                        </a>
                    </li>



                    


                <?php } ?>
                <?php if ($user_type == 5 || $user_type == 4) { ?>

                    <li class="nav-item <?= ($this->uri->segment(1) === 'serviceRequest') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>serviceRequest" class="nav-link nav-toggle">
                            <i class="fa fa-plane"></i>
                            <span class="title">Service Request</span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                    <li class="nav-item <?= ($this->uri->segment(1) === 'runpayroll') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>runpayroll" class="nav-link nav-toggle">
                            <i class="fa fa-money"></i>
                            <span class="title">Run Payroll</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                <?php } ?>

                <?php if ($user_type == 2) { ?>
                    <li class="<?= ($this->uri->segment(1) === 'hq_show_firm') ? 'active ' : '' ?>">
                        <a href="<?= base_url() ?>hq_show_firm" class="nav-link nav-toggle">
                            <i class="icon-puzzle"></i>
                            <span class="title">List of Offices</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                        </a>

                    </li>
                    <li class=" <?= ($this->uri->segment(1) === 'hq_view_hr') ? 'active ' : '' ?>">
                        <a href="<?= base_url() ?>hq_view_hr" class="nav-link nav-toggle">
                            <i class="fa fa-user-md"></i>
                            <span class="title">Create HR</span>
                            <span class="arrow"></span> 
                            <span class="selected"></span>

                        </a>
                    </li>
                <?php } ?>
                <?php // if ($user_type != 4) { ?>
<!--                    <li class="<?= ($this->uri->segment(1) === 'branch_sal_info') ? 'active' : '' ?>">
        <a href="<?= base_url() ?>branch_sal_info" class="nav-link nav-toggle">
            <i class="fa fa-money"></i>
            <span class="title">Branch Salary Info</span>
            <span class="arrow"></span>
    <span class="selected"></span>
        </a>
    </li>-->
                <?php // } ?>


                <?php if ($user_type != 4) { ?>
                    <li class="<?= ($this->uri->segment(1) === 'hr_designation') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>hr_designation" class="nav-link nav-toggle">
                            <i class="fa fa-black-tie"></i>
                            <span class="title">Designation</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item  <?= ($this->uri->segment(1) === 'hr_show_employee') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>hr_show_employee"  class="nav-link nav-toggle">

                            <i class="fa fa-id-card"></i>
                            <span class="title">Employee Details</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                                    <!--<span class="arrow"></span>-->
                        </a>
                    </li>

                <?php } ?>

                <?php if ($user_type == 5) { ?>
                    <li class="nav-item  <?= ($this->uri->segment(1) === 'hr_show_holiday') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>hr_show_holiday"  class="nav-link nav-toggle">

                            <i class="fa fa-umbrella"></i>
                            <span class="title">Holiday Details</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                                    <!--<span class="arrow"></span>-->
                        </a>
                    </li>
					<li class="nav-item  <?= ($this->uri->segment(1) === 'MonthlyReport') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>MonthlyReport"  class="nav-link nav-toggle">

                            <i class="fa fa-calendar"></i>
                            <span class="title">Monthly Report</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                                    <!--<span class="arrow"></span>-->
                        </a>
                    </li>
                <?php } ?>



                <li class="nav-item  <?= ($this->uri->segment(1) === 'profile') ? 'active' : '' ?>">
                    <a href="<?= base_url("profile") ?>" class="nav-link nav-toggle">
                        <i class="fa fa-user"></i>
                        <span class="title">Profile</span>
                        <span class="arrow"></span>
                    </a>
                </li>
				<?php if ($user_type == 5) { ?>
				<li class="nav-item  <?= ($this->uri->segment(1) === 'form_16_creation') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>form_16_creation"  class="nav-link nav-toggle">

                            <i class="fa fa-calendar"></i>
                            <span class="title">Form16 Creation</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                                    <!--<span class="arrow"></span>-->
                        </a>
                    </li>
					  <?php } ?>
                <!-- <li class="nav-item  <?= ($this->uri->segment(1) === 'form16') ? 'active' : '' ?>">
                    <a href="<?= base_url("form16") ?>" class="nav-link nav-toggle">
                        <i class="fa fa-file"></i>
                        <span class="title">Form16</span>
                        <span class="arrow"></span>
                    </a>
                </li> -->
				<li class="nav-item  <?= ($this->uri->segment(1) === 'form_16_submission') ? 'active' : '' ?>">
                    <a href="<?= base_url("form_16_submission") ?>" class="nav-link nav-toggle">
                        <i class="fa fa-file"></i>
                        <span class="title">Form16</span>
                        <span class="arrow"></span>
                    </a>
                </li>

              <li class="nav-item  <?= ($this->uri->segment(1) === 'Login/admin_logout') ? 'active' : '' ?>">
                    <a href="<?= base_url() ?>Login/admin_logout" class="nav-link nav-toggle">
                        <i class="icon-logout"></i>
                        <span class="title">LogOut</span>
                        <span class="arrow"></span>
                    </a>
                </li> 

        </div>
        <!-- END SIDEBAR -->
    </div>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.min.js" type="text/javascript"></script>

