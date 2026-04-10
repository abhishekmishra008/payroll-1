
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
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
       <!-- <script src="<?php /*echo base_url() . "assets/"; */?>global/scripts/datatable.js" type="text/javascript"></script>
		
        <link href="<?php /*echo base_url() . "assets/"; */?>global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php /*echo base_url() . "assets/"; */?>global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
-->
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
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white" style="background-color:white;">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->

			<?php $this->load->view('all_header/sample_old.php'); ?>

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
