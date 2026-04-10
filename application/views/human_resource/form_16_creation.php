<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$username = $this->session->userdata('login_session');

$data['session_data'] = $session_data;
$user_id = ($session_data['user_id']);

$user_type = ($session_data['user_type']);
$emp_id = ($session_data['emp_id']);
?>
<link href="<?= base_url() ?>assets/apps/css/mobile.css" rel="stylesheet" type="text/css"/>
<style>
    .col-md-12 {
        float: initial;
    }
    /*     table.a.table.table-bordered {
        width: 100% !important;
    }*/
	
</style>

<div class="page-content-wrapper">
    <div class="page-content" style="min-height: 6000px !important;" >

        <div class="page-bar">

            <div class="page-toolbar">
                <ul class="page-breadcrumb">
                    <li class="<?= ($this->uri->segment(1) === 'calendar') ? 'active' : '' ?>">
                        <a href="<?= base_url() ?>calendar">Home</a>
                        <i class="fa fa-arrow-right" style="font-size: 10px;margin: 0 5px;position: relative;top: -1px; opacity: .4;"></i>
                    </li>
                    <li>
                        <a href="#"><?php echo 'Form 16'; ?></a>
                        <i class="fa fa-circle" style="font-size: 5px; margin: 0 5px; position: relative;top: -3px; opacity: .4;"></i>
                    </li>

                </ul>

            </div>
        </div>
        <div class="col-md-12 ">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Design Form 16</span>
                    </div>
                </div>
                <input type='hidden' id='user_type' name='user_type' value='<?php echo $user_type ?>'>  


                <div class="portlet-body">
                    
				
				<form id="head_insert_form" method="post">
				<div class="row">
				<div class="col-md-12">
				<div class="caption font-blue-sunglo">
                        <i class="icon-arrow-right "></i>
                        <span class="caption-subject bold uppercase" style="color:black">Create Heads</span>
                    </div>
					<br>
					<div class="col-md-4">
					<label>Form Type:</label>
					<select class="form-control" id="form_id" name="form_id">
					<option>Select form Type</option>
					<option value="1">Form 16 Old</option>
					<option value="2">Form 16 New</option>
					</select>
					</div>
					<div class="col-md-4">
					<label>Head Number:</label>
					<input type="text" class="form-control" id="headNum" placeholder="Enter Head Number" name="headNum">
					</div>
					<div class="col-md-4">
					<label>Head Name:</label>
					<input type="text" class="form-control" id="headName" placeholder="Enter Head Name" name="headName">
					</div><br>
					
				</div>
				</div>
				<div class="row">
				<div class="col-md-12">
				<div class="col-md-4">
				<label>Contains SubHead ?:</label>
					<select class="form-control" id="field_type" name="field_type" onchange="get_headVisible()">
					<option>Select form Type</option>
					<option value="1">No</option>
					<option value="2">YES</option>
					</select>
				</div>
				<div class="col-md-4" id="type_div" style="display:none">
				<label>Head Type:</label>
					<select class="form-control" id="head_type" name="head_type" onchange="get_fixed_Amt()">
					<option value="0">Select form Type</option>
					<option value="1">Calculated</option>
					<option value="2">Variable</option>
					<option value="3">Fixed</option>
					</select>
				</div>
				<div class="col-md-4"> 
				<div id="aa" style="display:none"> 
				<label>Fixed Amount:</label>
				<input type="number" id="fixed_value" style="display:none" name="fixed_value" class="form-control" placeholder="Enter Fixed Amount">
				</div>
				<br>
				<button type="submit" id="save" name="save" class="btn green" style="float:right;  margin: 2px;">Add</button>
				</div>
				</div>
				</div>
				</form><hr>
				
				
				<!-- CREATE SubHeads -->
				<form id="subhead_insert_form" method="post">
				<div class="row">
				<div class="col-md-12">
				<div class="caption font-blue-sunglo">
                        <i class="icon-arrow-right "></i>
                        <span class="caption-subject bold uppercase" style="color:black">Create Sub Heads</span>
                    </div>
					<br>
					<div class="col-md-4">
					<label>Form Type:</label>
					<select class="form-control" id="Sform_id" name="Sform_id" onchange="get_subheads()">
					<option>Select form Type</option>
					<option value="1">Form 16 Old</option>
					<option value="2">Form 16 New</option>
					</select>
					</div>
					<div class="col-md-4">
					<label>Select Head:</label>
					<select class="form-control" id="head_id" name="head_id">
					<option value="">Select Head </option>
					
					</select>
					</div>
					<div class="col-md-4">
					<label>Sub Head Number:</label>
					<input type="text" class="form-control" id="SubheadNum" placeholder="Enter Head Number" name="SubheadNum">
					</div>
					<br>
					
				</div>
				</div>
				<div class="row">
				<div class="col-md-12">
				<div class="col-md-4">
					<label>Sub Head Name:</label>
					<input type="text" class="form-control" id="SubheadName" placeholder="Enter Head Name" name="SubheadName">
					</div>
				<div class="col-md-4" id="type_div" >
				<label>Sub Head Type:</label>
					<select class="form-control" id="Subhead_type" name="Subhead_type" onchange="get_Sfixed_Amt()">
					<option value="">Select form Type</option>
					<option value="1">Calculated</option>
					<option value="2">Variable</option>
					<option value="3">Fixed</option>
					</select>
				</div>
				<div class="col-md-4"> 
				<div id="SSaa" style="display:none"> 
				<label>Fixed Amount:</label>
				<input type="number" id="Sfixed_value" style="display:none" name="Sfixed_value" class="form-control" placeholder="Enter Fixed Amount">
				</div>
				<br>
				
				</div>
					<div class="col-md-4"><br>
<button type="submit" id="save" name="save" class="btn green" style="float:right;  margin: 2px;">Add</button>
					</div>
				</div>
				</div>
				</form><hr>
				
				<div class="row">
				<div class="col-md-12">
				<div class="caption font-blue-sunglo">
                        <i class="icon-arrow-right "></i>
                        <span class="caption-subject bold uppercase" style="color:black">View Form 16</span>
                    </div>
					</div></div>
					<div class="row">
					<div class="col-md-12">
					<div class="col-md-4">
					<label>Form Type:</label>
					<select class="form-control" id="Vform_id" name="Vform_id" onchange="get_form16()">
					<option>Select form Type</option>
					<option value="1">Form 16 Old</option>
					<option value="2">Form 16 New</option>
					</select>
					</div>
					<div id="div_form16">
					<br>
					</div>
					</div>
					</div>
			</div>
			</div>



			</div>

			</div>
                                                                <?php $this->load->view('human_resource/footer'); ?>


                                                                </div><!--
                                                            
                                                                --></div>

<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script>
	//
	$( document ).ready(function() {
 //   get_subheads();
});
	function get_headVisible(){
		var field_type=$("#field_type").val();
		if(field_type == "1"){
			$("#type_div").show();
		}else{
			$("#type_div").hide();
			$("#fixed_value").hide();
			$("#aa").hide();
		}
	}
	function get_fixed_Amt(){
		var head_type=$("#head_type").val();
		if(head_type == "3"){
			$("#fixed_value").show();
			$("#aa").show();
		}else{
			$("#fixed_value").hide();
			$("#aa").hide();
		}
	}
	function get_Sfixed_Amt(){
		var head_type=$("#Subhead_type").val();
		if(head_type == "3"){
			$("#Sfixed_value").show();
			$("#SSaa").show();
		}else{
			$("#Sfixed_value").hide();
			$("#SSaa").hide();
		}
	}
	$("#subhead_insert_form").validate({//form id
			   rules: {
				   head_id: "required",
				   SubheadName: "required",
				   Subhead_type: "required",
				   SubheadNum: "required",
				   Sfixed_value: "required",
				  
			   },
			   errorElement: "span",
			   submitHandler: function (form) {
				   var formid = document.getElementById("subhead_insert_form");
				   $.ajax({
					   url: '<?= base_url("Form16/addSubHeads") ?>',
					   type: "POST",
					   data: new FormData(formid),
					   processData: false,
					   contentType: false,
					   cache: false,
					   async: false,
					   success: function (success) {
						   result = JSON.parse(success);
						 
						   if (result.status == 200) {
							   alert(result.body);
							   //location.reload();
						   } else {
							   alert(result.body);
						   }
					   },
					   error: function (error) {
						   //alert(success.body);
						   console.log(error);
						   alert("something went to wrong");
					   }
				   });
			   }
		   }
		   );
		   $("#head_insert_form").validate({//form id
			   rules: {
				   form_id: "required",
				   headNum: "required",
				   headName: "required",
				   field_type: "required",
				   head_type: "required",
				   fixed_value: "required",
				  
			   },
			   errorElement: "span",
			   submitHandler: function (form) {
				   var formid = document.getElementById("head_insert_form");
				   $.ajax({
					   url: '<?= base_url("Form16/addHeads") ?>',
					   type: "POST",
					   data: new FormData(formid),
					   processData: false,
					   contentType: false,
					   cache: false,
					   async: false,
					   success: function (success) {
						   result = JSON.parse(success);
						   if (result.status == 200) {
							   alert(result.body);
							   //location.reload();
						   } else {
							   alert(result.body);
						   }
					   },
					   error: function (error) {
						   //alert(success.body);
						   console.log(error);
						   alert("something went to wrong");
					   }
				   });
			   }
		   }
		   );
		   function get_subheads(){
			   var Sform_id=$("#Sform_id").val();
			   
			    $.ajax({
					   url: '<?= base_url("Form16/getAllHeads") ?>',
					   type: "POST",
					   data: {form_id:Sform_id},
					   success: function (success) {
						   result = JSON.parse(success);
						   if (result.status == 200) {
							    console.log(result.data)
							  $("#head_id").html(result.data);
							   //location.reload();
						   } else {
							   $("#head_id").html(result.data);
						   }
					   },
					   error: function (error) {
						   //alert(success.body);
						   console.log(error);
						   alert("something went to wrong");
					   }
				   });
		   }
		   function get_form16(){
			   var Sform_id=$("#Vform_id").val();
			   
			    $.ajax({
					   url: '<?= base_url("Form16/get_form16") ?>',
					   type: "POST",
					   data: {form_id:Sform_id},
					   success: function (success) {
						   result = JSON.parse(success);
						   if (result.status == 200) {
							    console.log(result.data)
							  $("#div_form16").html(result.data);
							   //location.reload();
						   } else {
							   $("#div_form16").html(result.data);
						   }
					   },
					   error: function (error) {
						   //alert(success.body);
						   console.log(error);
						   alert("something went to wrong");
					   }
				   });
		   }
	
	</script>