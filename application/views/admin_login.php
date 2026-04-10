<?php $this->load->view('headermain'); ?>
<style>
    .login .logo {
        margin: 0px auto 0;}
    .login .content {

        background-color: #fff0 !important;

    }

    .login{
        background: #355C7D;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #C06C84, #6C5B7B, #355C7D);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #C06C84, #6C5B7B, #355C7D); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

    }
    .login .copyright{
        color:white;
    }
</style>

<body class=" login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="">

            <?php
            $pass = "";
            $user_id = "";
            $valid = 0;
            if ($this->input->cookie('rmt_tool') != '') {
                $cookie_name_fetch = $this->input->cookie('rmt_tool');

                $company_logo_name_cookie = explode(",", $cookie_name_fetch);
                $cookie_name = $company_logo_name_cookie[0];
                $user_name = $company_logo_name_cookie[1];
                $pass = $company_logo_name_cookie[2];
                $user_id = $company_logo_name_cookie[3];
                $valid = $company_logo_name_cookie[4];
                if ($cookie_name != '') {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <!--<p Style="font-size: 200px;">Welcome To HRMS</p>-->
                <?php } else {
                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <!--<p Style="font-size: 200px;">Welcome To HRMS</p>-->
                    <?php
                }
            } else {
                $user_name = "";
                ?>
                                                                                                                                                                                                                                                                                                <!--<p Style="font-size: 200px;">Welcome To HRMS</p>-->
            <?php } ?>


        </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <p Style="font-size: 38px; color:white;">Welcome To HRMS</p>
        <form class="login-form" method="post" name="f_login" action="<?= base_url() ?>Login/admin_login" id="f_login">
            <?php
            if ($user_name != '') {
                echo '<h3 class="form-title font-green">' . ucfirst($user_name) . '</h3>';
            } else {
                ?>
                <h3 class="form-title font-green">Sign In</h3>
                <?php
            }
            ?>

            <?php
            if ($this->input->cookie('uuID') != '') {
                ?>
                <input type="hidden" name="uuID" id="uuID" value="<?= $this->input->cookie('uuID') ?>" />
            <?php } else { ?>
                <input type="hidden" name="uuID" id="uuID" value="" />

            <?php } ?>
            <input type="hidden" name="isMobile" id="isMobile" />
            <input type="hidden" name="cusername" id="cusername" value="<?= $user_id ?>" />
            <input type="hidden" name="pusername" id="pusername" value="<?= $pass ?>" />
            <input type="hidden" name="uvalid" id="uvalid" value="<?= $valid ?>" />
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Username</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" placeholder="Username" id="user_id" name="user_id" autocomplete="off" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password"  id="password" autocomplete="off" placeholder="Password" name="password" /> </div>
            <div class="m-login__form-action">
                <span style="color:red"><?= (empty($error)) ? "" : $error ?></span>
                <span style="color:green"><?= (empty($reason)) ? "" : $reason ?></span>
            </div>
            <div class="form-actions"  style="border:none;">
                <button type="submit" class="btn green uppercase">Login</button>
                <!--                                <label class="rememberme check mt-checkbox mt-checkbox-outline">
                                                    <input type="checkbox" name="remember" value="1" />Remember
                                                    <span></span>
                                                </label>-->
                <a type="button" data-toggle="modal" data-target="#forget_pass" id="forget-password" class="forget-password" style="    color: #eef2f5;">Forgot Password?</a>
            </div>

        </form>

        <!-- END LOGIN FORM -->
        <!-- BEGIN FORGOT PASSWORD FORM -->
        <!--        <form class="forget-form" action="index.html" method="post">
                    <h3 class="font-green">Forget Password ?</h3>
                    <p> Enter your e-mail address below to reset your password. </p>
                    <div class="form-group">
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
                    <div class="form-actions">
                        <button type="button" id="back-btn" class="btn green btn-outline">Back</button>
                        <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
                    </div>
                </form>-->
        <!-- END FORGOT PASSWORD FORM -->
        <!-- BEGIN REGISTRATION FORM -->

        <!-- END REGISTRATION FORM -->


    </div>
    <div class="copyright"> <?=date('Y')?> © HRMS. </div>

    <div class="modal fade" id="forget_pass" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reset Password</h4>
                </div>
                <div class="modal-body">
                    <form id="forget_pass_form" name="forget_pass_form" method="post">
                        <div class="form-group">
                            <label class="control-label">Enter Email</label>
                            <input type="email" id="email_id_forget" name="email_id_forget" onkeyup="remove_error('email_id_forget')" class="form-control">
                            <span id="email_id_forget_error" class="required"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="send_mail" name="send_mail">Go</button>
                </div>
            </div>

        </div>

    </div>
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
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


                                $(document).ready(function ()
                                {

                                    showCookieFail();
                                    $('#clickmewow').click(function ()
                                    {
                                        $('#radio1003').attr('checked', 'checked');
                                    });

                                        var cusername = $("#cusername").val();
                                        var pusername = $("#pusername").val();
                                        var isValid = $("#uvalid").val();
                                        $("#isMobile").val(1);
                                        if (cusername !== "" && pusername !== "" && isValid === '1') {
                                            $.ajax({
                                                type: "post",
                                                url: "<?= base_url("Login/verify_and_start_session") ?>",
                                                dataType: "json",
                                                data: {"user_id": cusername, "password": pusername},
                                                success: function (result) {

                                                    if (result.status === 200) {
                                                        $(location).attr('href', '<?= base_url("calendar") ?>');
                                                    }
                                                },
                                                error: function (result) {
                                                    console.log(result);
                                                }
                                            });
                                        } else {

                                            console.log("value not found");
                                            console.log(isValid);
                                            console.log(cusername);
                                            console.log(pusername);

                                        }


//                                    document.addEventListener("deviceready", onDeviceReady, false);

// Wormhole is ready
//


                                });
                                function onDeviceReady() {
                                    var element = document.getElementById('deviceProperties');
                                    console.log('Device Name: ' + device.name + '<br />' +
                                            'Device Platform: ' + device.platform + '<br />' +
                                            'Device UUID: ' + device.uuid + '<br />' +
                                            'Device Version: ' + device.version + '<br />');
                                }
                                $("#send_mail").click(function () {
                                    $.ajax({
                                        type: "post",
                                        url: "<?= base_url("Login/forget_pass_link") ?>",
                                        dataType: "json",
                                        data: $("#forget_pass_form").serialize(),
                                        success: function (result) {
                                            if (result.status === true) {
                                                alert('Mail Sent SuccessFully.');
                                                // return;
                                                location.reload();
                                            } else if (result.status === false) {
                                                alert('something went wrong');
                                            } else {
                                                $('#' + result.id + '_error').html(result.error);
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
                                });
                                function  remove_error(id) {
                                    $('#' + id + '_error').html("");
                                }

//                                function checkcookie()
//                                {
//                                    if (!navigator.cookieEnabled)
//                                    {
//                                        alert("Cookies are not enabled on your browser, please turn them on");
//                                    }
//                                }

                                function checkcookie() {
                                    var cookieEnabled = navigator.cookieEnabled;
                                    if (!cookieEnabled) {
                                        document.cookie = "testcookie";
                                        cookieEnabled = document.cookie.indexOf("testcookie") != -1 ? true : false;
                                        console.log(cookieEnabled);
                                    }
//                                    console.log(cookieEnabled);
                                    return cookieEnabled;
                                }

                                function showCookieFail() {
                                    if (!checkcookie())
                                    {
                                        alert("Cookies are not enabled on your browser, please turn them on");
                                    }
                                }

    </script>
</body>

</html>
