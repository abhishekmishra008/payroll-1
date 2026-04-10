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

<body class=" body">
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
                        <div id="dialog">
                            <h3 >Please enter the 4-digit pin:</h3><br>
                             <div id="otp_form" name="otp_form" class="new_class">
                                <?php
                                $session_data = $this->session->userdata('login_session');
                                if (is_array($session_data)) {
                                    $data['session_data'] = $session_data;
                                    $email_id = ($session_data['user_id']);
                                    $user_type = ($session_data['user_type']);
                                } else {
                                    $email_id = $this->session->userdata('login_session');
                                }
                                ?>
                                <input type="hidden" value="<?= $username ?>" id="username"/>
                                <input type="hidden" value="<?= $password ?>" id="passwrod"/>
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
<script type="text/javascript">

function ValidatePassKey(tb) {
  if (tb.TextLength >= 5){
    document.getElementById(tb.id + 1).focus();
  }
}
function a(){
    
var str = $('#five').val();
$('#five').val(str.substring(0, str.length - 1));
    
}

function b(){
    
    var str = $('#four').val();
$('#four').val(str.substring(0, str.length - 1));
   
}
function c(){
     var str = $('#three').val();
$('#three').val(str.substring(0, str.length - 1));
   
}
function d(){
     var str = $('#two').val();
$('#two').val(str.substring(0, str.length - 1));
   
}
function e(){
     var str = $('#one').val();
$('#one').val(str.substring(0, str.length - 1));
   
}
function moveCursor(fromTextBox, toTextBox){
	var length = fromTextBox.value.length;
	var maxlength = fromTextBox.getAttribute("maxLength");
        var minlength = fromTextBox.getAttribute("minLength");
	if(length == maxlength){
		document.getElementById(toTextBox).focus();
	}
//        else if(length == minlength){
////            alert();
//            document.getElementById(toTextBox).focus();
//        }
//	
}
function reset(){
    document.getElementById("one").value="";
    document.getElementById("two").value="";
    document.getElementById("three").value="";
    document.getElementById("four").value="";
    document.getElementById("five").value="";
  
}

</script>


    </div>
    <br>
	
    <div class="col-md-12 new_class" align="center"> 2020 © HRMS. </div>


    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="<?php echo base_url() . "assets/"; ?>/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?php echo base_url() . "assets/"; ?>/pages/scripts/login.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <!-- END THEME LAYOUT SCRIPTS -->
    <script>


                                    function otp_config() {
                                        // var one = $('#one').val();
                                        // var two = $('#two').val();
                                        // var three = $('#three').val();
                                        // var four = $('#four').val();
                                        // var five = $('#five').val();
                                        // var finalotp = one + two + three + four + five;
                                        var finalotp = $("#partitioned").val();
										
                                        if (finalotp != "") {
                                            $.ajax({
                                                type: "post",
                                                url: "<?= base_url("CheckOtp") ?>",
                                                dataType: "json",
                                                data: {"otp": finalotp, "user_id": $("#username").val(), "user_p": $("#passwrod").val()},
                                                success: function (result) {
                                                    if (result.status === 200) {
														var str=result.cookieValue;
														console.log(result.cookieValue);
														var res = str.split(",");
														var user_type=res[5];
														if(user_type == 1){
															$(location).attr('href', '<?= base_url("show_firm") ?>');
														}else if(user_type == 2){
															$(location).attr('href', '<?= base_url("hq_show_firm") ?>');
														}else{
                                                        $(location).attr('href', '<?= base_url("calendar") ?>');
														}
                                                    }else{
														alert("OTP Missmatch");
														$("#partitioned").val("");
													}
                                                },
                                                error: function (result) {
                                                    console.log(result);
                                                }
                                            });

                                        } else {
                                            alert("Enter Otp");
                                        }


                                    }





                                    $(function () {
                                        'use strict';

                                        var body = $('body');

                                        function goToNextInput(e) {
                                            var key = e.which,
                                                    t = $(e.target),
                                                    sib = t.next('input');

                                            if (key != 9 && (key < 48 || key > 57)) {
                                                e.preventDefault();
                                                return false;
                                            }

                                            if (key === 9) {
                                                return true;
                                            }

                                            if (!sib || !sib.length) {
                                                sib = body.find('input').eq(0);
                                            }
                                            sib.select().focus();
                                        }

                                        function onKeyDown(e) {
                                            var key = e.which;

                                            if (key === 9 || (key >= 48 && key <= 57)) {
                                                return true;
                                            }

                                            e.preventDefault();
                                            return false;
                                        }

                                        function onFocus(e) {
                                            $(e.target).select();
                                        }

                                        body.on('keyup', 'input', goToNextInput);
                                        body.on('keydown', 'input', onKeyDown);
                                        body.on('click', 'input', onFocus);

                                    })



    </script>
</body>

</html>