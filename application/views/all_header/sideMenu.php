<?php
/*$menuAccess=array(1,2,3,4,5,6,7,8,9);
if(property_exists($this->session->user_session,'menu_access')){
    if(!is_null($this->session->user_session->menu_access) && $this->session->user_session->menu_access!="")
    {
        $menuAccess=explode(',',$this->session->user_session->menu_access);
    }
}
*/?>

    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> -->
    <style>
        body {
            font-family: "Lato", sans-serif;
        }
        .sidenav .card{
            margin-bottom: 0px;
        }
        .sidenav {
            height: 100%;
            width: 0;
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
            padding-left: 50px;
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
            background:transparent;
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


<div class="con">
	<div id="mySidenav" style="z-index: 199;width: 0px;" class="sidenav">
		<!-- <div class="" style="background: #ffffff;padding: 10px 10px;"> -->
		<!-- <h5 style="padding: 9px 46px;color: #D2454D;">Mentor Suite</h5> -->
		<!-- <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a> -->
		<!-- </div>  -->

		<div id="accordion">
<!--			--><?php //if(in_array(1,$menuAccess)){ ?>
				<div class="card headerCard">
					<div class="card-header" id="headingOne">
						<h5 class="mb-0">
							<a class="btn btn-link" data-toggle="" onclick="goToHrms('execution')" data-target="#" aria-expanded="false" aria-controls="collapseOne">
								<i class="fas fa-play-circle" style="font-size: 14px; color: #0075ff"></i>
								<span class="options">  Excecution</span>
							</a>
						</h5>
					</div>
				</div>
<!--			--><?php //} ?>

<!--			--><?php //if(in_array(3,$menuAccess) || in_array(4,$menuAccess) || in_array(5,$menuAccess) || in_array(6,$menuAccess) ){ ?>
				<div class="card headerCard">
					<div class="card-header" id="headingTwo">
						<h5 class="mb-0">
							<a class="btn btn-link collapsed options" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								<i class="fas fa-calendar-alt " style="font-size: 14px; color: orange"></i>
								Planning <i class="fas fa-chevron-down first"></i>
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
							<a class="btn btn-link collapsed options" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								<i class="fas fa-users" style="font-size: 14px; color: #19b927"></i>
								Collaboration <i class="fas fa-chevron-down"></i>
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
							<a class="btn btn-link collapsed options" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
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
							<div class="card">
								<div class="card-body sub-card">

									<a target="_blank" class="k-store" href="https://amgt.ecovisrkca.com/kstore/login_fromOtherWebsiteValidation?userName=<?=base64_encode($this->session->user_session->email)?>&userId=<?=base64_encode($this->session->user_session->user_id)?>&firmId=<?=base64_encode($this->session->user_session->firm_id)?>&userType=<?=base64_encode($this->session->user_session->user_type)?>">
<!--									<a target="_blank" class="k-store" href="#">-->
                                        <i class="fas fa-store" style="font-size: 12px; color: #f76782"></i>
										Kstore </a>
								</div>
							</div>
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

<span id="hamburgerMenu" style="font-size:30px;cursor:pointer;margin: 2px 20px 9px 40px;" onclick="toggleNav()">&#9776;</span>

<!-- Bootstrap JS and jQuery CDN -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->

<script>
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

