
<!DOCTYPE html>


<html lang="en">
  

    <head>
        <meta charset="utf-8" />
        <title><?php echo $page_title;?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />        
		<!--<link href="<?php echo base_url() . "assets/"; ?>global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
       <link href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

		  
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url() . "assets/"; ?>global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url() . "assets/"; ?>layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url() . "assets/"; ?>layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url() . "assets/"; ?>global/scripts/datatable.js" type="text/javascript"></script>

        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() . "assets/"; ?>global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url() . "assets/"; ?>global/css/custom.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>

    <!-- END HEAD -->
    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
        redirect(base_url() . 'login');
    }
    $session_data = $this->session->userdata('login_session');
//var_dump($session_data);
    $data['session_data'] = $session_data;
    $user_id = ($session_data['user_id']);
    $user_type = ($session_data['user_type']);
    $user_name = ($session_data['user_name']);


    $page_name = 'View Calender Holiday';
    $page_name2 = 'View Due Date List';
//var_dump($firm_name_dd);
//echo $firm_id_new;
    ?>
	
	<style>
	#dropdown_menu{
		    min-width: 500px !important;
    max-width: 1000px !important;
	left: -275px;
	    position: absolute;
	}
	#dropdown_menu1{
		    min-width: 300px !important;
    max-width: 1000px !important;
	left:0px;
	    position: absolute;
	}
	
	.new_class{
		display:flex;
		flex-direction:row;
		flex:1;
		justify-content:center;
	}
	.new_m{
		padding:2%;
	}
	hr {
      width: 96%;
}
.css_btn{
	margin-left:10%;
}

.p_class{
	display: flex;
    flex-direction: row;
    flex: 1;
}
.slimScrollDiv{
	    border-right: #dee2e6 solid 0;
    border-bottom: #dee2e6 solid 1px;
}
a {
  color: inherit; /*the default color*/
}
.media-heading-sub{
	padding-left: 16%;
}
.page-quick-sidebar-wrapper .page-quick-sidebar .nav-tabs>li.active>a, .page-quick-sidebar-wrapper .page-quick-sidebar .nav-tabs>li:hover>a{
	border-bottom:none!important;
}


	</style>
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">

                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo p_class">
					 <div class="menu-toggler sidebar-toggler pull-left">
                            <span></span>
                        </div>
						<div class="row"style="display: flex;flex-direction: row;">
						<div class="col-md-2">
						<a href="javascript:;" class="menu-toggler responsive-toggler pull-left" style="" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
					</div>
					<div class="col-md-8" id="mbl_name" style="display:none;">
				   <?php if ($user_type == 5) { ?> 
    <!--<p style="color:white">Human Resource</p>-->
                                    <p style="color:white; "><?php echo $user_name; ?></p>
                                <?php } elseif ($user_type == 2) { ?>
                                    <!--<p style="color:white"><?php echo $user_name; ?></p>-->
                                    <p style="color:white">HR Admin</p>
                                <?php } elseif ($user_type == 4) { ?>
                                    <p style="color:white; "><?php echo $user_name; ?></p>
                                <?php } ?>
                           </div>
						   <div class="col-md-2">
					 <a href="#" class="page-quick-sidebar-toggler" id="mbl"  style="padding-right: 26px;padding-top:11px;display:none;padding-bottom:0px;">
                    <i class="fa fa-th"></i>
					</div>
                </a>
				</div>
                       
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
					
                    
					
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu" id="clearfix" style="display:none">
                        <input type="hidden" id="base_url_text_box_embed_into_header" name="base_url_text_box_embed_into_header" value="<?php echo base_url(); ?>">
                       
					   <ul class="nav navbar-nav pull-left">
							
				<li class="dropdown dropdown-quick-sidebar-toggler " id="tab_content"  >
				<a href="#" class="page-quick-sidebar-toggler" id="desk"  style="padding-right: 26px; display:none;padding-top:11px;padding-bottom:0px;">
                    <i class="fa fa-th"></i>
                </a>
				</li>
				
                            <!-- BEGIN NOTIFICATION DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after "dropdown-extended" to change the dropdown styte -->
                            <!-- DOC: Apply "dropdown-hoverable" class after below "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
                            <!-- DOC: Remove "dropdown-hoverable" and add data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to the below A element with dropdown-toggle class -->
                            
                            <input type="file" accept="image/*" id="FileUpload1" name="FileUpload1" style="display: none" />
                            <button type="button" onclick="upload_image();" id="upload_btn" name="upload_btn" style="display: none" class="btn btn-primary">Upload</button>



<!--<img alt="" class="img-circle" src="<?php echo base_url() . "assets/"; ?>layouts/layout/img/avatar3_small.jpg" />-->
<!--                                    <span class="username username-hide-on-mobile"> Nick </span>
<i class="fa fa-angle-down"></i>-->
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">

                            </ul>
                            </li>
                            <li>   <?php if ($user_type == 5) { ?> 
    <!--<p style="color:white">Human Resource</p>-->
                                    <p style="color:white; "><?php echo $user_name; ?></p>
                                <?php } elseif ($user_type == 2) { ?>
                                    <!--<p style="color:white"><?php echo $user_name; ?></p>-->
                                    <p style="color:white">HR Admin</p>
                                <?php } elseif ($user_type == 4) { ?>
                                    <p style="color:white; "><?php echo $user_name; ?></p>
                                <?php } ?>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
					
												
                           <li id="logout_btn1"class="dropdown dropdown-quick-sidebar-toggler " style="display:none">
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
<div class="page-quick-sidebar-wrapper" data-close-on-body-click="false"id="" style="background-color: white;">
                    <div class="page-quick-sidebar" style="background-color: white;">
					<!-- <button class="btn" style="color:white;background-color:#21282e" onclick="abc();">x</button>-->
                        <ul class="nav nav-tabs">
                            <li class="active" style="border-bottom: 3px solid #f3565d;">
                                <a href="#" data-target="#quick_sidebar_tab_1" style="color: black;margin-left: 67px;" data-toggle="tab1">WORKPLACE APP
                               
							   </a>
								
                            </li>
							<li style="border-bottom: 3px solid #f3565d;">
							<button type="button" class="page-quick-sidebar-toggler pull-right close text-dark" aria-label="Close" style="margin-top: 40px;">
  <span aria-hidden="true">&times;</span>
</button>
						
							</li>
                           
                        </ul>
                        <div class="tab-content" >
                            <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                                <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="media-list list-items">
                                                <div class="col-md-3">
                                                    <div class="media-body" style="padding: 20px;">
													<a href="https://rmt.docango.com/email_client" class="btn-icon-vertical btn-square  btn media-object" >
                                <i class="fa fa-envelope " style="color: cadetblue;font-size: 25px;"></i>
                           
                            </a>
                                                     
                                                        <div class="media-heading-sub" style="color: black;"> Email </div>
                                                    </div>
                                                    <div class="media-body" style="padding: 20px;">
                                                         <a href="https://rmt.docango.com/video_chatMobile" class="btn-icon-vertical btn-square  btn  ">
                                <i class="fa fa-video-camera btn-icon-wrapper mb-3" style="color: cornflowerblue;font-size: 25px;"></i>
                               
							
                            </a>
                                                        <div class="media-heading-sub" style="color: black;"> video chat </div>
                                                    </div>
                                                    <div class="media-body" style="padding: 20px;">
                                                        <a href="https://rmt.docango.com/MobileViewController/board" class="btn-icon-vertical btn-square  btn  ">
                                <i class="fa fa-address-card-o btn-icon-wrapper mb-3" style="color: cornflowerblue;font-size: 25px;"></i>
                               
                            </a>
                                                        <div class="media-heading-sub" style="color: black;"> Board </div>
                                                    </div>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="media-list list-items">
                                                <div class="col-md-2">
                                                   
                                                    <div class="media-body" style="padding: 20px;">
                                                       <a href="https://rmt.docango.com/folder_view" class="btn-icon-vertical btn-square  btn  ">
                                <i class="fa fa-folder btn-icon-wrapper mb-3" style="color: sandybrown;font-size: 25px;"></i>
                                
                            </a>
                                                        <div class="media-heading-sub" style="color: black;"> Folder </div>
                                                    </div>
                                                    <div class="media-body" style="padding: 20px;">
                                                        <a href="https://rmt.docango.com/gc/auth/login" class="btn-icon-vertical btn-square  btn ">
                                    <i class="fa fa-home btn-icon-wrapper mb-3" style="color: #3126d6;font-size: 26px;margin-left: 27px;"></i>
                                   
                                </a>
                                                        <div class="media-heading-sub" style="color: black;"> RMT Home </div>
                                                    </div>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                       
                                    </ul>
                                    </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						
            <!-- END HEADER 
			<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>-->
			    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script type="text/javascript">
			function abc()
			{
				
				
				 $(".page-quick-sidebar-wrapper").toggle();
			}
			
			function closedrawer()
			{
				 $(".page-quick-sidebar-wrapper").hide();
			}
			function abc1()
			{
				if($('#dropdown_menu').css('display') == 'none')
					{
					$("#dropdown_menu").show();
					}else{
						$("#dropdown_menu").hide();
					}
				
			}
			function abc2()
			{
				if($('#dropdown_menu1').css('display') == 'none')
					{
					$("#dropdown_menu1").show();
					}else{
						$("#dropdown_menu1").hide();
					}
				
				
			}
			$( document ).ready(function() {
  var check_device = ((typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1));
                                                               
															   if (check_device == true)
                                                               {
																   
																   var x = document.getElementById('desk');
																  x.style.display = 'none';
																  var y = document.getElementById('mbl');
																  y.style.display = 'block';
																  var z = document.getElementById('mbl_name');
																  z.style.display = 'block'; 
																
																  
															   }else{
																     var x = document.getElementById('desk');
																  x.style.display = 'block';
																  var m = document.getElementById('logout_btn1');
																  m.style.display = 'block';
																   var y = document.getElementById('mbl');
																  y.style.display = 'none';
																    var a = document.getElementById('clearfix');
																  a.style.display = 'block'; 
																  
															   }
});
                function upload_image() {
                    var formid = document.getElementById("upload_logo");
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url("Login/add_logo") ?>",
                        dataType: "json",
                        data: new FormData(formid), //form data
                        processData: false,
                        contentType: false,
                        cache: false,
                        async: false,
                        success: function (result) {
                            // alert(result);
                            if (result.status === true) {
                                alert('logo uploaded successfully');
                                location.reload();
                            } else if (result.status === false) {
                                alert('Please Select profile picture of size(min 5mb)');
                                location.reload();
                            } else {
                                $('#' + result.id + '_error').html(result.error);
//                    $('#message').html(result.error);
                            }
                        },
                        error: function (result) {
                            console.log(result);
                            if (result.status === 500) {
                                alert('Internal error: ' + result.responseText);
                            } else {
                                alert('Unexpected error.');
                            }
                        }
                    });

                }
				$('ul.dropdown-menu').on('click', function(event){
    // The event won't be propagated up to the document NODE and 
    // therefore delegated events won't be fired
    event.stopPropagation();
});
$("#close_drp").click(function() {
   
});

//                $("#upload_btn").click(function () {
//                    alert('gfdjg');
//
//                });

            </script>