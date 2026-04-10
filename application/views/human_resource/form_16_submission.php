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
                        <span class="caption-subject bold uppercase">Fill Form 16</span>
                    </div>
                </div>
                <input type='hidden' id='user_type' name='user_type' value='<?php echo $user_type ?>'>  


                <div class="portlet-body">
                    
				
			
				
				
				<!-- CREATE SubHeads -->
				
				
				<div class="row">
				<div class="col-md-12">
				
					</div></div>
					<div class="panel-group accordion" id="accordion3">
                        <div class="panel panel-default" style="overflow-y: scroll;width:100%;">
						<div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1" aria-expanded="false"> Submit Details </a>
                                </h4>
                            </div>
                            <div id="collapse_3_1" class="panel-collapse collapse " aria-expanded="false">

                                <div class="panel-body">
					<form id='form16submit' method='post'>
					<div class="row">
					<div class="col-md-12">
					<div class="col-md-4">
					<label>Form Type:</label>
					<!--<select class="form-control" id="Vform_id" name="Vform_id" onchange="get_form16()">
					<option>Select form Type</option>
					<option value="1">Form 16 Old</option>
					<option value="2">Form 16 New</option>
					</select>-->
					<?php if($form_type == 1){ $formType="Form 16 Old"; } else {$formType="Form 16 New"; }?>
					<input name="Vform_id1" id="Vform_id1" value="<?php echo $formType; ?>" type="text" class="form-control" readonly>
					<input name="Vform_id" id="Vform_id" value="<?php echo $form_type; ?>" type="hidden" class="form-control" readonly>
					<!--<input name="Vform_id" id="Vform_id" value="1" type="hidden" class="form-control" readonly>-->
					</div>
					<div class="col-md-3">
					<label>Select Year</label>
                                            <select class="form-control" id="select_year" name="select_year" onchange="get_data();">

                                            </select>
                                        </div>
					<div id="div_form16">
					<br>
					
					</div>
					<button type="button" id="save" name="save" class="btn green" style="float:right;  margin: 2px;" onclick="saveForm16()">Add</button>
					</div>
					</div>
					</form>
					</div>
					</div>
					</div>
					</div>
					<div class="panel-group accordion" id="accordion1">
                        <div class="panel panel-default" style="overflow-y: scroll;width:100%;">
						<div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1_1" onclick="get_Viewform16()" aria-expanded="false"> Preview Form16 </a>
                                </h4>
                            </div>
                            <div id="collapse_1_1" class="panel-collapse collapse " aria-expanded="false">

                                <div class="panel-body">
								
										<?php if($user_type==5) { ?>
											<div class="col-md-3">
											<label>Select Year</label>
                                            <select class="form-control" id="select_year1" name="select_year1">

                                            </select>
                                        </div>
										<div class="col-md-3">
											<label>Select Employee</label>
                                            <select class="form-control" id="employee_id" name="employee_id" onchange="get_Viewform16();">
												
                                            </select>
                                        </div>
										<?php } else { ?>
											<div class="col-md-3">
											<label>Select Year</label>
                                            <select class="form-control" id="select_year1" name="select_year1" onchange="get_Viewform16();">

                                            </select>
                                        </div>
										<?php } ?>
										<script src="<?= base_url(); ?>assets/js/pdf_conversion.js"></script>
										<form class="form" style="max-width: none; width: 1005px;">  
								<div id="viewForm16"></div></form> 
								<!--<div><input type="hidden" name="pdfData" id="pdfData"><input type="button" id="create_pdf" value="Generate PDF"> -->
								
								<!--<button type="button" class="btn btn-danger" onclick="create_pdf()">Create PDF</button>-->
								</div>
					</div>
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

<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>  
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>  
<script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script>
	//
	$( document ).ready(function() {
 //   get_subheads();
 get_form16();
 get_employee();
});

$('#select_year').each(function () {

                                                                                var year = (new Date()).getFullYear();
                                                                                var current = year;
                                                                                year -= 1;
																				start_year='2020';
																				 for (var i = 0; i < 6; i++) {
                                                                                    var NextYear = (start_year)*1 + 1;
                                                                                    var PrevYear = (start_year)*1 - 1;
                                                                                    if ((start_year) == current)
                                                                                        $(this).prepend('<option selected value="' + (start_year) + "-" + NextYear + '">' + (start_year) + "-" + NextYear + '</option>');
                                                                                    else
                                                                                        $(this).append('<option value="' + (start_year) + "-" + NextYear + '">' + (start_year) + "-" + NextYear + '</option>');
                                                                                start_year=NextYear;
																				}
																				/* if(current == '2020')
																				{
																					$(this).prepend('<option selected value="' + (current) + "-" + (current+1) + '">' + (current) + "-" + (current+1) + '</option>');
																				}else{
																					 for (var i = 0; i < 6; i++) {
                                                                                    var prevYear = (year + i) + 1;
                                                                                    if ((year + i) == current)
                                                                                        $(this).prepend('<option selected value="' + (year + i) + "-" + prevYear + '">' + (year + i) + "-" + prevYear + '</option>');
                                                                                    else
                                                                                        $(this).append('<option value="' + (year + i) + "-" + prevYear + '">' + (year + i) + "-" + prevYear + '</option>');
                                                                                } */
                                                                               
                                                                            });
																			$('#select_year1').each(function () {

                                                                                var year = (new Date()).getFullYear();
                                                                                var current = year;
                                                                                year -= 1;
																				start_year='2020';
																				 for (var i = 0; i < 6; i++) {
                                                                                    var NextYear = (start_year)*1 + 1;
                                                                                    var PrevYear = (start_year)*1 - 1;
                                                                                    if ((start_year) == current)
                                                                                        $(this).prepend('<option selected value="' + (start_year) + "-" + NextYear + '">' + (start_year) + "-" + NextYear + '</option>');
                                                                                    else
                                                                                        $(this).append('<option value="' + (start_year) + "-" + NextYear + '">' + (start_year) + "-" + NextYear + '</option>');
                                                                                start_year=NextYear;
																				}
																				/* if(current == '2020')
																				{
																					$(this).prepend('<option selected value="' + (current) + "-" + (current+1) + '">' + (current) + "-" + (current+1) + '</option>');
																				}else{
																					 for (var i = 0; i < 6; i++) {
                                                                                    var prevYear = (year + i) + 1;
                                                                                    if ((year + i) == current)
                                                                                        $(this).prepend('<option selected value="' + (year + i) + "-" + prevYear + '">' + (year + i) + "-" + prevYear + '</option>');
                                                                                    else
                                                                                        $(this).append('<option value="' + (year + i) + "-" + prevYear + '">' + (year + i) + "-" + prevYear + '</option>');
                                                                                } */
                                                                               
                                                                            });

function saveForm16(){
	 var formid = document.getElementById("form16submit");
				   $.ajax({
					   url: '<?= base_url("Form16/Save_Form16") ?>',
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
					   url: '<?= base_url("Form16/get_form16Submission") ?>',
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
	function get_Viewform16(){
			   var Sform_id=$("#Vform_id").val();
			   var year=$("#select_year1").val();
			   var user_id=$("#employee_id").val();
			   //alert(user_id);
			   //alert(Sform_id);
			   
			    $.ajax({
					   url: '<?= base_url("Form16/form16GetAll") ?>',
					   type: "POST",
					   data: {form_id:Sform_id,year:year,user_id:user_id},
					   success: function (success) {
						   console.log(success)
						   result = JSON.parse(success);
						    
						   if (result.status == 200) {
							    console.log(result.data)
							  $("#viewForm16").html(result.data);
							  $("#pdfData").val(result.data);
							   //location.reload();
						   } else {
							   $("#viewForm16").html(result.data);
							   $("#pdfData").val(result.data);
						   }
					   },
					   error: function (error) {
						   //alert(success.body);
						   console.log(error);
						   alert("something went to wrong");
					   }
				   });
		   }
		   
	function create_pdf()
	{
		 var pdf_data=$("#pdfData").val();
		 //alert(pdf_data);
		  $.ajax({
					   url: '<?= base_url("Form16/create_Form16_pdf") ?>',
					   type: "POST",
					   data: {pdf_data:pdf_data},
					  
					   success: function (success) {
						   //result = JSON.parse(success);
						   console.log(success);
					   },
					   error: function (error) {
						   //alert(success.body);
						   console.log(error);
						   alert("something went to wrong");
					   }
				   });
	}	
	</script>
	 <script>  
    (function () {  
        var  
         form = $('.form'),  
         cache_width = form.width(),  
         a4 = [595.28, 841.89]; // for a4 size paper width and height  

        $('#create_pdf').on('click', function () {  
            $('body').scrollTop(0);  
            createPDF();  
        });  
        //create pdf  
        function createPDF() {  
            getCanvas().then(function (canvas) {  
                var  
                 img = canvas.toDataURL("image/png"),  
                 doc = new jsPDF({  
                     unit: 'px',  
                     format: 'a4'  
                 });  
                doc.addImage(img, 'JPEG', 20, 20);  
                doc.save('Payroll.pdf');  
                form.width(cache_width);  
            });  
        }  

        // create canvas object  
        function getCanvas() {  
            form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');  
            return html2canvas(form, {  
                imageTimeout: 2000,  
                removeContainer: true  
            });  
        }  

    }());  
</script>  
<script>  
    /* 
 * jQuery helper plugin for examples and tests 
 */  
    (function ($) {  
        $.fn.html2canvas = function (options) {  
            var date = new Date(),  
            $message = null,  
            timeoutTimer = false,  
            timer = date.getTime();  
            html2canvas.logging = options && options.logging;  
            html2canvas.Preload(this[0], $.extend({  
                complete: function (images) {  
                    var queue = html2canvas.Parse(this[0], images, options),  
                    $canvas = $(html2canvas.Renderer(queue, options)),  
                    finishTime = new Date();  

                    $canvas.css({ position: 'absolute', left: 0, top: 0 }).appendTo(document.body);  
                    $canvas.siblings().toggle();  

                    $(window).click(function () {  
                        if (!$canvas.is(':visible')) {  
                            $canvas.toggle().siblings().toggle();  
                            throwMessage("Canvas Render visible");  
                        } else {  
                            $canvas.siblings().toggle();  
                            $canvas.toggle();  
                            throwMessage("Canvas Render hidden");  
                        }  
                    });  
                    throwMessage('Screenshot created in ' + ((finishTime.getTime() - timer) / 1000) + " seconds<br />", 4000);  
                }  
            }, options));  

            function throwMessage(msg, duration) {  
                window.clearTimeout(timeoutTimer);  
                timeoutTimer = window.setTimeout(function () {  
                    $message.fadeOut(function () {  
                        $message.remove();  
                    });  
                }, duration || 2000);  
                if ($message)  
                    $message.remove();  
                $message = $('<div ></div>').html(msg).css({  
                    margin: 0,  
                    padding: 10,  
                    background: "#000",  
                    opacity: 0.7,  
                    position: "fixed",  
                    top: 10,  
                    right: 10,  
                    fontFamily: 'Tahoma',  
                    color: '#fff',  
                    fontSize: 12,  
                    borderRadius: 12,  
                    width: 'auto',  
                    height: 'auto',  
                    textAlign: 'center',  
                    textDecoration: 'none'  
                }).hide().fadeIn().appendTo('body');  
            }  
        };  
    })(jQuery);  
function get_employee()
			{
				$.ajax({
					type: "POST",
					url: "<?= base_url("Form16/get_employee") ?>",
					dataType: "json",
					success: function (result) {
						var option = result.option;
						if (result.status == 200) {
							$('#employee_id').html(option);
						} else {
							$('#employee_id').html(option);
						}
					},
				});
			}
</script>  