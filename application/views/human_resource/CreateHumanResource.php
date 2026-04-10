<?php
// $this->load->view('admin/header');
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
                    <li class="<?= ($this->uri->segment(1) === 'calendar') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>calendar">Home</a>
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo $prev_title; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>

                </ul>

            </div>
        </div>
        <div class="row"></div>
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-settings font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Create Human Resource</span>
                </div>

            </div>


            <div class="portlet-body">
                <!-- BEGIN FORM-->
                <form  method="POST"  id="addemp" name="addemp" class="form-horizontal" novalidate="novalidate">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <p id="message" style="color:red"> </p>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $username; ?>" id="senoior_emp_id" name="senoior_emp_id">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="">Office Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i></span>
                                    <select name="firm_name" id="firm_name" onchange="remove_error('firm_name');check_firm_exist();"  class="aa form-control" >
                                    <!--<select name="firm_name" id="firm_name" onchange="remove_error('firm_name');check_firm_exist();"  class="aa form-control" >-->
                                        <option value="0">Select Office</option>
                                    </select>
                                </div>
                                <span class="required" id="firm_name_error"></span>
                            </div>
                            <div class="col-md-4">
                                <label>Name</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" name="user_name" id="  user_name" placeholder="Enter HR Name" onkeyup="remove_error('user_name')" aria-required="true" aria-describedby="input_group-error">
                                </div>
                                <span class="required" id="user_name_error"></span>
                            </div>
                            <div class="col-md-4">
                                <label>Mobile No</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                    <input type="number" min="0" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" onkeydown="javascript: return event.keyCode == 69 ? false : true"  name="mobile_no" onkeyup="remove_error('mobile_no')"  id="mobile_no" data-required="1" class="form-control" placeholder="Enter HR Mobile No">
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
                                    <input type="text"  id="email" name="email" onkeyup="remove_error('email')"  class="form-control" placeholder="Enter HR Email Id">
                                </div>
                                <span class="required" id="email_error"></span>
                                <span id="email_result" ></span>
                            </div>
                            <div class="col-md-4">
                                <label class="">Address</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-globe"></i></span>
                                    <input type="text"  id="address" name="address" onkeyup="remove_error('address')"  class="    form-control m-input" placeholder="Enter HR Address">
                                </div>
                                <span class="required" id="address_error"></span>
                            </div>
                            <div class="col-md-4">
                                <label>Country</label>
                                <div class="input-group">
                                    <span class="input-group-addon">  <i class="fa fa-flag"></i></span></span>
                                    <input type="text" id="country" name="country" onkeyup="remove_error('country')" class="form-control m-input" placeholder="Enter HR country">
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
                                    <input type="text" id="state" name="state" onkeyup="remove_error('state')" class="form-control m-input" placeholder="Enter HR State">
                                </div>
                                <span class="required" id="state_error"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="">City</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-globe"></i></span>
                                    <input type="text"  id="city" name="city" onkeyup="remove_error('city')"    class="form-control m-input" placeholder="Enter HR City">
                                </div>
                                <span class="required" id="city_error"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="">User star rating </label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-star"></i></span>
                                    <select name="star_rating" id="star_rating"  class="form-control m-select2 ">
                                        <?php
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
                                <input type="hidden" id="l_a_permit" name="l_a_permit" value="1">
                                <input type="hidden" id="config_warning" name="config_warning" value="1,1,1">

                            </div>



                        </div>
                    </div>

            </div>
        </div>


        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    <a href="<?= base_url() ?>hq_view_hr" class="btn grey-salsa btn-outline"  style="float:right; margin: 2px;">Cancel</a>
                    <button type="button"  id="save" name="save" class="btn green"  style="float:right;  margin: 2px;">Add Human Resource</button>
                </div>
            </div>
        </div>

        <!--                <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="button" class="btn grey-salsa btn-outline"  style="margin: 2px;">Cancel</button>
                                    <button type="button" id="save" name="save" class="btn green"  style="margin: 2px;">Create Employee</button>

                                </div>
                            </div>

                        </div>-->

        <div class="loading" id="loaders7" style="display:none;"></div>
        </form>
		
		<?php $this->load->view('human_resource/footer'); ?>

    </div>

</div>
</div>
</div>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script>

                                        $("#leave_apply_permission").change(function () {



                                            var leave_apply_permission = $('#leave_apply_permission').val();

                                            if (leave_apply_permission == 2) {
                                                leave_apply_permission = $('#leave_apply_permission').val();
                                                $('#prob_yes').show();
                                                $('#train_yes').hide();
                                            } else if (leave_apply_permission == 3) {
                                                leave_apply_permission = "0";
                                                $('#train_yes').show();
                                                $('#prob_yes').hide();
                                            } else if (leave_apply_permission == 4) {
                                                leave_apply_permission = "0";
                                                $('#train_yes').hide();
                                                $('#prob_yes').hide();
                                            } else if (leave_apply_permission == 1) {
                                                leave_apply_permission = "0";
                                                $('#train_yes').hide();
                                                $('#prob_yes').hide();
                                            }

                                        });
                                        function check_firm_exist()
                                        {
                                            var firm_id = document.getElementById("firm_name").value;
                                            $.ajax({
                                                type: "post",
                                                data: {firm_id: firm_id},
                                                url: "<?= base_url("/Human_resource/checkFirmExists") ?>",
                                                dataType: "json",
                                                success: function (result) {
                                                    if (result['message'] === 'success') {
                                                        alert(result.body);
                                                        $('#firm_name').val(0);
                                                    } else {

                                                    }
                                                }
                                            });
                                        }
                                        function abc()
                                        {

                                            $("#yearly_leave").keyup(function () {
                                                //$("#monthly_leaves").val($("#yearly_leave").val()/12);

                                                var y = $("#yearly_leave").val();
                                                var m = y / 12;

                                                var c = m.toFixed(2)
                                                $("#monthly_leaves").val(c);
                                            });
                                        }

                                        $(document).ready(function () {

                                            $('#train_yes').show();
                                            $('#prob_yes').show();


                                            $.ajax({
                                                url: "<?= base_url("/Human_resource/get_ddl_firm_name") ?>",
                                                dataType: "json",
                                                success: function (result) {
                                                    if (result['message'] === 'success') {
                                                        var data = result.frim_data;
                                                        $('#boss_id').val(data[0]['reporting_to']);
                                                        var ele3 = document.getElementById('firm_name');
                                                        for (i = 0; i < data.length; i++)
                                                        {
                                                            // POPULATE SELECT ELEMENT WITH JSON.
                                                            ele3.innerHTML = ele3.innerHTML + '<option value="' + data[i]['firm_id'] + '">' + data[i]['firm_name'] + '</option>';
                                                        }
                                                    }
                                                }
                                            });
                                        });

                                        $("#leave_apply_permission").change(function () {


                                        });
                                        
//                                        $(document).ready(function () {
//                                            $('#email').change(function () {
//                                                var email = $('#email').val();
//                                                if (email != '')
//                                                {
//                                                    $.ajax({
//                                                        url: "<?php echo base_url(); ?>Human_resource/check_email_avalibility",
//                                                        method: "POST",
//                                                        data: {email: email},
//                                                        success: function (data) {
//                                                            $('#email_result').html(data);
//                                                        }
//                                                    });
//                                                }
//                                            });
//
//
//                                        });

                                        $("#save").click(function () {
                                            var $this = $(this);
                                            $this.button('loading');
                                            setTimeout(function () {
                                                $this.button('reset');
                                            }, 2000);
                                            document.getElementById('loaders7').style.display = "block";
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= base_url("Employee/add_hr_hq") ?>",
                                                dataType: "json",
                                                data: $("#addemp").serialize(),
                                                success: function (result) {
                                                    if (result.status === true) {
                                                        document.getElementById('loaders7').style.display = "none";
                                                        alert('HR Created successfully');
                                                        $(location).attr('href', '<?= base_url("hq_view_hr") ?>')
                                                        // location.reload();
                                                    } else if (result.result === '2') {
                                                        document.getElementById('loaders7').style.display = "none";
                                                        alert('fetching error');
                                                    } else if (result.result === '3') {
                                                        document.getElementById('loaders7').style.display = "none";
                                                        alert('query error');
                                                    } else if (result.result === '4') {
                                                        document.getElementById('loaders7').style.display = "none";
                                                        alert('something went wrong');
                                                    } else {
                                                        document.getElementById('loaders7').style.display = "none";
                                                        $('#' + result.id + '_error').html(result.error);
//                    $('#message').html(result.error);
                                                    }
                                                },
                                                error: function (result) {
                                                    console.log(result);
                                                    if (result.status === 500) {
                                                        document.getElementById('loaders7').style.display = "none";
                                                        alert('Internal error: ' + result.responseText);
                                                    } else {
                                                        document.getElementById('loaders7').style.display = "none";
                                                        alert('Unexpected error.');
                                                    }
                                                }
                                            });
                                        });
                                        function  remove_error(id) {
                                            $('#' + id + '_error').html("");
                                        }

                                        function onDateChange() {
                                            var selected_date = document.getElementById("joining_date").value;
                                            document.getElementById("prob_date_first").setAttribute('min', selected_date);
                                            document.getElementById("prob_date_second").setAttribute('min', selected_date);
                                            document.getElementById("training_period_first").setAttribute('min', selected_date);
                                            document.getElementById("training_period_second").setAttribute('min', selected_date);
                                        }
</script>