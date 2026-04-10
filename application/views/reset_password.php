<?php $this->load->view('headermain'); ?>


<body class=" login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="">

            <?php
            if ($this->input->cookie('rmt_tool') != '') {
                $cookie_name_fetch = $this->input->cookie('rmt_tool');
                $company_logo_name_cookie = explode(",", $cookie_name_fetch);
                $cookie_name = $company_logo_name_cookie[0];
                $user_name = $company_logo_name_cookie[1];
                if ($cookie_name != '') {
                    ?>
                    <img  src='<?php echo base_url() . "uploads/gallery/" . $cookie_name; ?>' alt="logo" style="height: 100px" />     
                <?php } else {
                    ?>
                    <img src="<?php echo base_url() . "assets/"; ?>global/img/RMTLogofinal.png" alt="logo" style="height: 100px" /> 
                    <?php
                }
            } else {
                $user_name = "";
                ?>
                <img src="<?php echo base_url() . "assets/"; ?>global/img/RMTLogofinal.png" alt="logo" style="height: 100px" /> 
            <?php } ?>


        </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase">Reset Password</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">


                                <!-- CHANGE PASSWORD TAB -->
                                <div class="tab-pane active" id="tab_1_3">
                                    <form  name="change_pass_form" id="change_pass_form">
                                        <div class="form-group">
                                            <label class="control-label">New Password</label>
                                            <input type="password" name="first_pass" id="first_pass" class="form-control"> 
                                            <span class="required" id="first_pass_error"></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Re-type New Password</label>
                                            <input type="password"  name="second_pass" id="second_pass"class="form-control"> 
                                            <span class="required" id="second_pass_error"></span>
                                        </div>
                                        <div class="margin-top-10">
                                            <a type="button" id="change_pass" name="change_pass" class="btn green"> Change Password </a>
                                            <a href="javascript:;" class="btn default"> Cancel </a>
                                        </div>
                                    </form>
                                </div>
                                <!-- END CHANGE PASSWORD TAB -->


                                </form>
                            </div>
                            <!-- END PRIVACY SETTINGS TAB -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
<div class="copyright"> 2019 © RMT. </div>



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
        $('#clickmewow').click(function ()
        {
            $('#radio1003').attr('checked', 'checked');
        });
    });


    function  remove_error(id) {
        $('#' + id + '_error').html("");
    }
    $("#change_pass").click(function () {
        $.ajax({
            type: "post",
            url: "<?= base_url("Login/change_pass") ?>",
            dataType: "json",
            data: $("#change_pass_form").serialize(),
            success: function (result) {
                if (result.status === true) {
                    alert('password change successfully');
                    // return;
                    //location.reload();
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
</script>
</body>

</html>