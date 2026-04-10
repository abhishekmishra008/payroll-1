<?php
$this->load->view('admin/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin 
    redirect(base_url() . 'login');
}
 //$user_id = $this->session->userdata('login_session');
 $user_id1 = $this->session->userdata('login_session');
$user_id = $user_id1['user_id'];
?>
<link href="<?= base_url() ?>assets/global/css/mystyle1.css" rel="stylesheet" type="text/css" />		
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="loading" id="loader" style="display:none;"></div>

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    Admin

   
                    <i class="fa fa-circle" style="font-size: 5px;margin: 0 5px;position: relative;top: -3px; opacity: .4;"></i>
                </li>
            </ul>
            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'show_firm') ? 'active' : '' ?>">
						Home
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
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
        <div class="col-md-12">
            <div class="row wrapper-shadow">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit ">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Edit HR Admin</span>
                        </div>
                        <!--                    <div class="actions">
                                                <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                    <label class="btn btn-transparent red btn-outline btn-circle btn-sm active">
                                                        <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                                                    <label class="btn btn-transparent red btn-outline btn-circle btn-sm">
                                                        <input type="radio" name="options" class="toggle" id="option2">Settings</label>
                        
                                                </div>
                                            </div>-->
                    </div>
                    <div class="form-group m-form__group">
    <!--                    <p  id="message" for="exampleInputPassword1" style="color:red">   
                        </p>-->
                    </div> 


                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <form method="POST"  id="frm_client_firm" name="frm_client_firm" class="form-horizontal" novalidate="novalidate">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>"/>
                            <div class="form-body">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="row">
                                        <input type="hidden" name="firm_id" id="fimr_id" value="<?php echo $firm_id; ?>">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label>HR Admin Name    </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-home"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="firm_name"  value="<?php echo $record->firm_name; ?>"id="firm_name" onkeyup="remove_error('firm_name')"   placeholder="Enter HR Admin Name" aria-required="true" aria-describedby="input_group-error">
                                                </div>
                                                <span class="required" id="firm_name_error"></span>
                                            </div>   
                                            <div class="col-md-4">
                                                <label>HR Admin Email Id</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-envelope"></i>
                                                    </span>
                                                    <input type="email" name="firm_email_id"  id="firm_email_id" readonly value="<?php echo $record->firm_email_id; ?>" onkeyup="remove_error('firm_email_id')"  placeholder="Enter Your Email Id" data-required="1" class="form-control">
                                                </div>
                                                <span class="required" id="firm_email_id_error"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-globe"></i>
                                                    </span>                                            
                                                    <textarea  rows="3" class="form-control"  id="firm_address" name="firm_address"onkeyup="remove_error('firm_address')" aria-describedby="input_group-error" aria-required="true" placeholder="Enter Address"><?php echo $record->firm_address; ?></textarea>

                                                </div><span class="required" id="firm_address_error"></span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label>Contact Number
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-phone"></i> </span>
                                                    <input type="number" min="0" name="boss_mobile_no" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"  id="boss_mobile_no" onkeyup="remove_error('boss_mobile_no')" value="<?php echo $record->boss_mobile_no; ?>"  data-required="1" class="form-control" placeholder="Main branch Mobile Number">

                                                </div><span class="required" id="boss_mobile_no_error"></span>

                                            </div>

                                            <div class="col-md-4">
                                                <label>Country </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-flag"></i>
                                                    </span>
                                                    <input type="text" name="country" onkeyup="remove_error('country')" value="<?php echo $record2->country; ?>"id="country" data-required="1" class="form-control" placeholder="Enter Country Name"> 
                                                </div><span class="required" id="country_error"></span>

                                            </div>

                                            <div class="col-md-4">
                                                <label>State                                        </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-globe"></i>
                                                    </span>
                                                    <input type="text" name="state" onkeyup="remove_error('state')"  id="state" data-required="1" value="<?php echo $record2->state; ?>"class="form-control" placeholder="Enter state Name"> 

                                                </div><span class="required" id="state_error"></span>

                                            </div>

                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="form-group">




                                            <div class="col-md-4">

                                                <label>City

                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-home"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="city" onkeyup="remove_error('city')"value="<?php echo $record2->city; ?>"  id="city" placeholder="Enter City Name" aria-required="true" aria-describedby="input_group-error">
                                                    <span class="required" id="city_error"></span>
                                                </div>

                                            </div>

                                            <div class="col-md-4">
                                                <label>  Activity Status
                                                </label>
                                                <div class="mt-checkbox-inline">
                                                    <?php if ($record->firm_activity_status == 'A') { ?>
                                                        <label class="mt-radio">
                                                            <input type="radio" id="firm_activity_status" name="firm_activity_status" checked="" value="A">Active
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" id="firm_activity_status" name="firm_activity_status" value="D"> De-activate
                                                            <span></span>
                                                        </label>
                                                    <?php } else {
                                                        ?>
                                                        <label class = "mt-radio">
                                                            <input type = "radio" id = "firm_activity_status" name = "firm_activity_status" value = "A">Active
                                                            <span></span>
                                                        </label>
                                                        <label class = "mt-radio">
                                                            <input type = "radio" id = "firm_activity_status" name = "firm_activity_status"  checked = "" value = "D"> De-activate
                                                            <span></span>
                                                        </label>
                                                    <?php }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <a href="<?= base_url() ?>show_firm"> <button  type="button" class="btn grey-salsa btn-outline"  style="float:right; margin: 2px;">Cancel</button></a>
                                        <button  type="button" id="btn_edit_client_firm" name="btn_edit_client_firm"  class="btn green"  style="float:right;  margin: 2px;">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END FORM-->
                    </div>
                </div>
                <!-- END VALIDATION STATES-->
            </div>
        </div>
        <?php $this->load->view('admin/footer'); ?>  
    </div>

</div>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script>
                                                        $("#btn_edit_client_firm").click(function (e) {
                                                            e.preventDefault();
//                                                        var $this = $(this);
//                                                        document.getElementById('loader').style.display = "block";
//                                                        $this.button('loading');
//                                                        setTimeout(function () {
//                                                            $this.button('reset');
//                                                        }, 2000);
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "<?= base_url("Hq_firm_form/edit_firm") ?>",
                                                                dataType: "json",
                                                                data: $("#frm_client_firm").serialize(),
                                                                success: function (result) {
                                                                    // alert(result.error);
                                                                    if (result.status === true) {
                                                                        document.getElementById('loader').style.display = "none";
                                                                        alert('HR Admin Updated Successfully');
                                                                        window.location.href = "<?= base_url("show_firm") ?>";
                                                                        // return;
//                                                                    location.reload();
                                                                    } else if (result.status === false) {
                                                                        alert('something went wrong')
                                                                    } else {
                                                                        document.getElementById('loader').style.display = "none";
                                                                        $('#' + result.id + '_error').html(result.error);
                                                                        $(window).scrollTop($('#' + result.id).offset().top -100,'slow');
                                                                        $('#message').html(result.error);
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




                                                        function change_firm_type() {
                                                            remove_error('firm_type');
                                                            var firm_type = document.getElementById('firm_type').value;
                                                            if (firm_type === 'multiple') {
                                                                $('#div_ddl_reporting_to').css('display', 'none');
                                                                $('#div_no_of_permitted_office').css('display', 'block');

                                                                $('#div_is_emp').css('display', 'none');
                                                            } else {
                                                                $('#div_ddl_reporting_to').css('display', 'none');
                                                                $('#div_no_of_permitted_office').css('display', 'none');

                                                                $('#div_is_emp').css('display', 'block');
                                                            }
                                                        }


                                                        function  remove_error(id) {
                                                            $('#' + id + '_error').html("");
                                                        }
</script>