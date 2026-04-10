<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7.5
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
      
        <meta charset="utf-8" />
        <title><?php echo $page_title; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
      
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url() . "assets/"; ?>global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/css/plugins.min.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url() . "assets/"; ?>layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url() . "assets/"; ?>layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url() . "assets/"; ?>global/scripts/datatable.js" type="text/javascript"></script>

        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url() . "assets/"; ?>pages/scripts/table-datatables-responsive.min.js" type="text/javascript"></script>
        <link href="<?php echo base_url() . "assets/"; ?>global/css/custom.css" rel="stylesheet" type="text/css">

        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>

    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top" style="background-color: #293a68 !important;">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="Dashboard">
<!--                            <img src="<?php echo base_url() . "assets/"; ?>layouts/layout/img/logo.png" alt="logo" class="logo-default" />--> 
                            <!--<img src="<?php echo base_url() . "assets/"; ?>global/img/RMTLogoreverse.png" alt="logo" class="logo-default" width="90px" style="margin: 0px 0 0;"/>-->
                            <!--                            Resource Management Tool-->
                        </a>
                        <div class="menu-toggler sidebar-toggler">
                            <span></span>
                        </div>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
					<input type="hidden" id="base_url_text_box_embed_into_header" name="base_url_text_box_embed_into_header" value="<?php echo base_url();?>">
                        <ul class="nav navbar-nav pull-right">
                           
                         
                        
                            <!-- END TODO DROPDOWN -->
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" style="    padding: 5px 1px 2px 1px;">
                                    



<!--<img alt="" class="img-circle" src="<?php echo base_url() . "assets/"; ?>layouts/layout/img/avatar3_small.jpg" />-->
<!--                                    <span class="username username-hide-on-mobile"> Nick </span>
<i class="fa fa-angle-down"></i>-->
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="page_user_profile_1.html">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                    <li>
                                        <a href="app_calendar.html">
                                            <i class="icon-calendar"></i> My Calendar </a>
                                    </li>
                                    <li>
                                        <a href="app_inbox.html">
                                            <i class="icon-envelope-open"></i> My Inbox
                                            <span class="badge badge-danger"> 3 </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="app_todo.html">
                                            <i class="icon-rocket"></i> My Tasks
                                            <span class="badge badge-success"> 7 </span>
                                        </a>
                                    </li>
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="page_user_lock_1.html">
                                            <i class="icon-lock"></i> Lock Screen </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>Login/admin_logout">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-quick-sidebar-toggler">
                                <a href="<?= base_url() ?>Login/admin_logout" class="dropdown-toggle">
                                    <i class="icon-logout"></i>
                                </a>
                            </li>
                            <!-- END QUICK SIDEBAR TOGGLER -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <script type="text/javascript">
//                window.onload = function () {
//                    var fileupload = document.getElementById("FileUpload1");
////                    var filePath = document.getElementById("spnFilePath");
//                    var image = document.getElementById("imgFileUpload");
//                    image.onclick = function () {
//                        fileupload.click();
//                        $("#upload_btn").css("display", "block");
//                        $("#imgFileUpload").css("display", "none");
//                    };
////                    fileupload.onchange = function () {
////                        var fileName = fileupload.value.split('\\')[fileupload.value.split('\\').length - 1];
////                        filePath.innerHTML = "<b>Selected File: </b>" + fileName;
////                    };
//                };
//
//                function upload_image() {
//                    var formid = document.getElementById("upload_logo");
//                    $.ajax({
//                        type: "POST",
//                        url: "<?= base_url("Login/add_logo") ?>",
//                        dataType: "json",
//                        data: new FormData(formid), //form data
//                        processData: false,
//                        contentType: false,
//                        cache: false,
//                        async: false,
//                        success: function (result) {
//                            // alert(result);
//                            if (result.status === true) {
//                                alert('logo uploaded successfully');
//                                location.reload();
//                            } else if (result.status === false) {
//                                alert('Please Select profile picture of size(min 5mb)');
//                                location.reload();
//                            } else {
//                                $('#' + result.id + '_error').html(result.error);
////                    $('#message').html(result.error);
//                            }
//                        },
//                        error: function (result) {
//                            console.log(result);
//                            if (result.status === 500) {
//                                alert('Internal error: ' + result.responseText);
//                            } else {
//                                alert('Unexpected error.');
//                            }
//                        }
//                    });
//
//                }

//                $("#upload_btn").click(function () {
//                    alert('gfdjg');
//                   
//                });
            </script>