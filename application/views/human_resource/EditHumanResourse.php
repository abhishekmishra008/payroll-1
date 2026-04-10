<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin 
    redirect(base_url() . 'login');
}
$user_id = $this->session->userdata('login_session');
$username = $user_id['user_id'];
?>
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() . "assets/"; ?>global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">

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
        <div class="row"></div>
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-settings font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Edit Human Resource</span>
                </div>
                <div class="portlet-body">
                    <!-- BEGIN FORM-->
                    <form  method="POST"  id="hr_edit_form" name="hr_edit_form" class="form-horizontal" novalidate="novalidate">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <p id="message" style="color:red"> </p>
                                </div> 
                            </div>
                            <input type="hidden" value="<?php echo $username; ?>" id="senoior_emp_id" name="senoior_emp_id">
                            <input type="hidden" value="<?php echo $record_res->user_id; ?>" id="user_id" name="user_id">
                            <input type="hidden" value="<?php echo $record_res->hr_authority; ?>" id="firm_ids" name="firm_ids">
                            <input type="hidden" value="<?php echo $record_res->hr_authority; ?>" id="firm_name" name="firm_name">
                            <?php if ($record_res !== "") { ?>
                                <div class="form-group">
                                    <!--   <div class="col-md-4">
                                        <label class="">Branch Name</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user"></i></span>
                                            <select name="firm_name" id="firm_name" onchange="remove_error('firm_name');get_designation();get_services();"  class="form-control select2" >                   
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <span class="required" id="designation_error"></span>
                                    </div> -->
                                    <div class="col-md-4">
                                        <label>Name</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </span>
                                            <input type="text" class="form-control" name="user_name" id=" user_name" value="<?php echo $record_res->user_name ?>" placeholder="Enter HR Name" onkeyup="remove_error('user_name')" aria-required="true" aria-describedby="input_group-error">
                                        </div>
                                        <span class="required" id="user_name_error"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Mobile No</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                            <input type="number" name="mobile_no" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true" onkeyup="remove_error('mobile_no')" value="<?php echo $record_res->mobile_no ?>"  id="mobile_no" data-required="1" class="form-control" placeholder="Enter HR Mobile No"> 
                                        </div>
                                        <span class="required" id="mobile_no_error"></span>
                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label class="">Email Id</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                            <input type="text"  id="email" name="email" onkeyup="remove_error('email')" value="<?php echo $record_res->email ?>" class="form-control" placeholder="Enter HR Email Id">
                                        </div>
                                        <span class="required" id="email_error"></span>
                                        <span id="email_result" ></span>  
                                    </div>
                                    <div class="col-md-4">
                                        <label class="">Address</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-globe"></i></span>
                                            <input type="text"  id="address" name="address" onkeyup="remove_error('address')" value="<?php echo $record_res->address ?>"  class="    form-control m-input" placeholder="Enter HR Address">
                                        </div>
                                        <span class="required" id="address_error"></span>
                                    </div> 
                                    <div class="col-md-4">
                                        <label>Country</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">  <i class="fa fa-flag"></i></span></span>
                                            <input type="text" id="country" name="country" onkeyup="remove_error('country')" value="<?php echo $record_res->country ?>" class="form-control m-input" placeholder="Enter HR country">
                                        </div>
                                        <span class="required" id="country_error"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">

                                    <div class="col-md-4">
                                        <label>State</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-home"></i></span>
                                            <input type="text" id="state" name="state" onkeyup="remove_error('state')" value="<?php echo $record_res->state ?>"  class="form-control m-input" placeholder="Enter HR State">
                                        </div>
                                        <span class="required" id="state_error"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="">City</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-globe"></i></span>
                                            <input type="text"  id="city" name="city" onkeyup="remove_error('city')"  value="<?php echo $record_res->city ?>"  class="form-control m-input" placeholder="Enter HR City">
                                        </div>
                                        <span class="required" id="city_error"></span>
                                    </div> 

                                    <div class="col-md-4">
                                        <label class="">User star rating </label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-star"></i></span>
                                            <select name="star_rating" id="star_rating"  class="star_rating form-control m-select2 ">
                                                <?php
                                                if ($record_res->user_star_rating != 1) {
                                                    ?>
                                                    <option selected readonly><?php echo $record_res->user_star_rating; ?></option>
                                                    <?php
                                                } else {
                                                    
                                                }
                                                $a = 1;
                                                for ($a = 1; $a <= 10; $a++) {
                                                    ?>
                                                    <option><?php echo $a; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>

                                        </div>
                                        <span class="required" id="star_rating_error"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="hidden" name="l_a_permit" id="l_a_permit" checked=""  value="1">
                                        <input type="hidden" name="config_warning" id="config_warning" checked=""  value="1,1,1">
                                        <input type="hidden" min="0" id="desig_id" name="desig_id"  class="form-control" value="<?php echo $record_desig->designation_id; ?>">
                                    </div> 
                                </div>
                            </div>
                        </form>
                    </div>

                </div>


                <div class="loading" id="loader" style="display:none;"></div>
            </div>
            <?php
        } else {
            
        }
        ?>

        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    <a href="<?= base_url() ?>hq_view_hr" class="btn grey-salsa btn-outline"  style="float:right; margin: 2px;">Cancel</a>
                    <button type="button"  id="update_hr" name="update_hr" class="btn green"  style="float:right;  margin: 2px;">Update</button>
                </div>
            </div>
        </div>
        <div class="loading" id="loaders7" style="display:none;"></div>
        </form>  
        <?php $this->load->view('human_resource/footer'); ?>		
    </div>
</div>
</div>

</div>
</div>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>


<script>



                                                var optionValues = [];
                                                $('#star_rating option').each(function () {
                                                    if ($.inArray(this.value, optionValues) > -1) {
                                                        $(this).remove()
                                                    } else {
                                                        optionValues.push(this.value);
                                                    }
                                                });

//                                                $(document).ready(function () {
//                                                    $('#email').change(function () {

//                                                        var email = $('#email').val();
//                                                        if (email != '')
//                                                        {
//                                                            $.ajax({
//                                                                url: "<?php echo base_url(); ?>Human_resource/check_email_avalibility",
//                                                                method: "POST",
//                                                                data: {email: email},
//                                                                success: function (data) {
//                                                                    $('#email_result').html(data);
//                                                                }
//                                                            });
//                                                        }
//                                                    });
//
//
//                                                });


                                                $("#update_hr").click(function () {
                                                    var $this = $(this);
                                                    $this.button('loading');
                                                    setTimeout(function () {
                                                        $this.button('reset');
                                                    }, 2000);
                                                    document.getElementById('loader').style.display = "block";
                                                    $.ajax({
                                                        type: "post",
                                                        url: "<?= base_url("Human_resource/update_hr_data") ?>",
                                                        dataType: "json",
                                                        data: $("#hr_edit_form").serialize(),
                                                        success: function (result) {
                                                            if (result.status === true) {

                                                                document.getElementById('loader').style.display = "none";
                                                                alert('HR updated successfully.');

                                                                // return;
                                                                $(location).attr('href', '<?= base_url("hq_view_hr") ?>')
                                                            } else if (result.status === false) {
                                                                document.getElementById('loader').style.display = "none";
                                                                alert('Something went wrong.');
                                                            } else {
                                                                document.getElementById('loader').style.display = "none";
//                    $('#message').html(result.error);
                                                                $('#' + result.id + '_error').html(result.error);
                                                            }
                                                        },
                                                        error: function (result) {
                                                            console.log(result);
                                                            if (result.status === 500) {
                                                                document.getElementById('loader').style.display = "none";
                                                                alert('Internal error: ' + result.responseText);
                                                            } else {
                                                                document.getElementById('loader').style.display = "none";
                                                                alert('Unexpected error.');
                                                            }
                                                        }
                                                    });
                                                });
                                                $(document).ready(function () {
                                                    var firm_id = document.getElementById('firm_ids').value;
                                                    var res = firm_id.split(",");
                                                    $.ajax({
                                                        url: "<?= base_url("/Human_resource/get_ddl_firm_name") ?>",
                                                        dataType: "json",
                                                        success: function (result) {
                                                            if (result['message'] === 'success') {
                                                                var data = result.frim_data;
                                                                $('#boss_id').val(data[0]['reporting_to']);
                                                                var ele3 = document.getElementById('firm_name'); //firm_ids
                                                                //var selectedfirm=$("#firm_ids").value;
                                                                //$("#firm_name").value=selectedfirm;

                                                                for (i = 0; i < data.length; i++)
                                                                {
                                                                    if (res[i] === data[i]['firm_id'])
                                                                    {
                                                                        ele3.innerHTML = ele3.innerHTML + '<option selected="selected" value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                                    } else {
                                                                        ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    });
                                                });

                                                function  remove_error(id) {
                                                    $('#' + id + '_error').html("");
                                                }

</script>