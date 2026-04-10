<?php
// $this->load->view('Admin/header');
$this->load->view('admin/Navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin 
    redirect(base_url() . 'login');
}
$user_id = $this->session->userdata('login_session');
?>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
       <link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
       
<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="#">
                       Admin
                    </a>
                    <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                </li>
            </ul>
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>dashboard">Home</a>
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
        <!-- <div class="page-fixed-main-content"> -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 red" href="#">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="<?php echo $firm_count; ?>">0</span></div>
                        <div class="desc"> Number of Active Main Branch </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
                    <div class="visual">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="<?php echo $course_count; ?>">0</span>
                        </div>
                        <div class="desc"> Number of Courses</div>
                    </div>
                </a>
            </div>

<!--            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 green" href="#">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="549">0</span>
                        </div>
                        <div class="desc"> Status of active users </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
                    <div class="visual">
                        <i class="fa fa-globe"></i>
                    </div>
                    <div class="details">
                        <div class="number"> 
                            <span data-counter="counterup" data-value="89">0</span></div>
                        <div class="desc"> Particular user activities </div>
                    </div>
                </a>
            </div>-->
        </div>



        <div class="clearfix"></div>


        <!-- END DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-lg-2 col-xs-2 col-sm-2"></div>
            <div class="col-lg-8 col-xs-8 col-sm-8">

                <!-- BEGIN PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-bubble font-hide hide"></i>
                            <span class="caption-subject font-hide bold uppercase"></span>
                        </div>
                    </div>


                </div>
                <!-- END PORTLET-->
            </div>
            <div class="col-lg-2 col-xs-2 col-sm-2"></div>
        </div>
<?php
$this->load->view('admin/Footer');
?>
    </div>
</div>


<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>