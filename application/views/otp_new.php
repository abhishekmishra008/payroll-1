


<?php $this->load->view('headermain'); ?>
<style>

#partitioned {
  padding-left: 15px;
  letter-spacing: 42px;
  border: 0;
  background-image: linear-gradient(to left, #b46a8b 70%, rgba(255, 255, 255, 0) 0%);
  background-position: bottom;
  background-size: 50px 1px;
  background-repeat: repeat-x;
  background-position-x: 35px;
  width: 220px;
}
.new_class{
	    display: flex;
    flex: 1;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 1%;
}
input:focus{
    outline: none;
}
#divInner{
  left: 0;
  position: sticky;
}

#divOuter{
  width: 190px; 
  overflow: hidden;
}
 .body {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  


}
 

</style>

<body class="body">
    <!-- BEGIN LOGO -->

    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->


        <input type="hidden" name="isMobile" id="isMobile" />

        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div id="wrapper">
                        <div id="dialog" align="center">
                            <h3 >Please enter the 4-digit pin:</h3><br>
                            <div id="otp_form" name="otp_form" class="new_class">
                               
                               <div id="divOuter">
	<div id="divInner">
		<input id="partitioned" type="number" maxlength="4" />
	</div>
</div>
                            </div>
							<br>
							<div class="new_class" style="flex-direction: row;">
							<button  onclick="otp_config()"class="btn  btn-embossed" style="background-color:#b46a8b;border-radius: 20%!important;color:#fff">Verify</button>
							</div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div><br>
	
    <div class="col-md-12 new_class" align="center"> 2020 © HRMS. </div>
	

    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
  
</body>

</html>