<html>
<?php $this->load->view('headermain'); ?>
<style>
    body {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
    .otp-form .otp-field {
        display: inline-block;
        width: 4rem;
        height: 4rem;
        font-size: 2rem;
        line-height: 4rem;
        text-align: center;
        border: none;
        border-bottom: 2px solid black;
        outline: none;
    }

    .otp-form .otp-field:focus {
        border-bottom-color: black;
    }

</style>
<body>
<div class="card-body">
    <h3 class="card-title text-center">Please enter the 4-digit pin:</h3>
    <div class="card-text text-center mt-5">
        <form action="" class="otp-form">

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

            <input class="otp-field" type="text" name="opt-field[]" maxlength=1>
            <input class="otp-field" type="text" name="opt-field[]" maxlength=1>
            <input class="otp-field" type="text" name="opt-field[]" maxlength=1>
            <input class="otp-field" type="text" name="opt-field[]" maxlength=1>

            <!-- Store OTP Value -->
            <input class="otp-value" type="hidden" name="opt_value" id="otp_value">
            <div class="d-block mt-4">
                <button  onclick="otp_config()"class="btn  btn-embossed" style="background-color:#b46a8b;margin-top:20px;border-radius: 20%!important;color:#fff">Verify</button>
            </div>
        </form>
    </div>
</div>
<div class="col-md-12 new_class" align="center"> <?=date('Y')?> © HRMS. </div>
</body>
</html>
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
<script>
    $(document).ready(function () {
        $(".otp-form *:input[type!=hidden]:first").focus();
        let otp_fields = $(".otp-form .otp-field"),
            otp_value_field = $(".otp-form .otp-value");
        otp_fields
            .on("input", function (e) {
                $(this).val(
                    $(this)
                        .val()
                        .replace(/[^0-9]/g, "")
                );
                let opt_value = "";
                otp_fields.each(function () {
                    let field_value = $(this).val();
                    if (field_value != "") opt_value += field_value;
                });
                otp_value_field.val(opt_value);
            })
            .on("keyup", function (e) {
                let key = e.keyCode || e.charCode;
                if (key == 8 || key == 46 || key == 37 || key == 40) {
                    // Backspace or Delete or Left Arrow or Down Arrow
                    $(this).prev().focus();
                } else if (key == 38 || key == 39 || $(this).val() != "") {
                    // Right Arrow or Top Arrow or Value not empty
                    $(this).next().focus();
                }
            })
            .on("paste", function (e) {
                let paste_data = e.originalEvent.clipboardData.getData("text");
                let paste_data_splitted = paste_data.split("");
                $.each(paste_data_splitted, function (index, value) {
                    otp_fields.eq(index).val(value);
                });
            });
    });

    function otp_config() {
        var finalotp = $("#otp_value").val();

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
                        location.reload();
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
</script>
