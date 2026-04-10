<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');

$data['session_data'] = $session_data;
$user_id = ($session_data['user_id']);

$user_type = ($session_data['user_type']);
$emp_id = ($session_data['emp_id']);
?>
<link href="<?= base_url() ?>assets/apps/css/mobile.css" rel="stylesheet" type="text/css"/>

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
        <div class="col-md-12">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Form 16 Input Form</span>
                    </div>
                </div>

                <div class="portlet-body">
                                <form id="add_form" name="add_form" method="post">
								<table class="table table-bordered">
								<thead>
								<tr>
								<th>Text</th>
								<th>Value</th>
								<th>Type</th>
								<th>Max Value</th>
								<th>File</th>
								</tr>
								</thead>
								<tbody>
								<tr>
								<td><h5><b>1.Gross Salary *</b></h5></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								<tr>
								<td><h5><lable>Value of perquisites under section 17</lable> </h5></td>
								<td><input type="number" placeholder="enter value" id="gross2" name="gross2"></td>
								<td>variable</td>
								<td></td>
								<td>
								<select id="select1" onchange="check_file(1)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile1" name="myfile1" style="display:none">
								</td>
								</tr>
								<tr>
								<td><h5><lable>Profits in lieu of Salary under section 17 </lable> </h5></td>
								<td><input type="number" placeholder="enter value" id="gross3" name="gross3"></td>
								<td>variable</td>
								<td></td>
								<td>
								<select id="select2" onchange="check_file(2)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile2" name="myfile2" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><h5><b>2.Less : Allowance to the extent exempt under section 10</b></h5></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								
								<tr>
								<td><lable>a)</lable> <input type="text" placeholder="enter text" id="la_text1" name="la_text1"></td>
								<td><input type="number" placeholder="enter value" id="la_num1" name="la_num1"></td>
								<td>Variable</td>
								<td></td>
								<td>
								<select id="select3" onchange="check_file(3)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile3" name="myfile3" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><lable>b)</lable>  <input type="text" class="form" id="la_text2" placeholder="enter text" name="la_text2"></td>
								<td><input type="number" placeholder="enter value" id="la_num2" name="la_num2"></td>
								<td>Variable</td>
								<td></td>
								<td>
								<select id="select4" onchange="check_file(4)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile4" name="myfile4" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><h5><b>3.Deductions </b></h5></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								
								<tr>
								<td><lable>Standard deduction</lable></td>
								<td> </td>
								<td>Fix</td>
								<td>50000</td>
								<td></td>
								</tr>
								
								<tr>
								<td><lable>Entertainment allowance</lable></td>
								<td> <input type="number" placeholder="enter value" id="ded1" name="ded1"></td>
								<td>Variable</td>
								<td></td>
								<td>
								<select id="select5" onchange="check_file(5)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile5" name="myfile5" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><lable>Tax on Employment</lable> </td>
								<td>  <input type="number" placeholder="enter value" id="ded2" name="ded2"></td>
								<td>Variable</td>
								<td></td>
								<td>
								<select id="select6" onchange="check_file(6)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile6" name="myfile6" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><h5><b>3.Add/Less </b></h5> </td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								
								<tr>
								<td><lable>Add. : Any other income reported by the employee</lable></td>
								<td>  <input type="number"  placeholder="enter value" id="add_inc" name="add_inc"></td>
								<td>Variable</td>
								<td></td>
								<td>
								<select id="select7" onchange="check_file(7)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile7" name="myfile7" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><lable>Less:Loss From House Properties</lable></td>
								<td>  <input type="number"class="qty2"  placeholder="enter value" id="less_inc" name="less_inc"></td>
								<td>Fix</td>
								<td>150000</td>
								<td>
								<select id="select8" onchange="check_file(8)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile8" name="myfile8" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><h5><b>4. Deductions Under Chapter VIA </b></h5></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								
								<tr>
								<td><h5><b>A. Sections 80C,80CC and 80CCD</b></h5></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								<tr>
								<td><lable>a) Section 80C</lable></h5></td>
								<td></td>
								<td>Fix</td>
								<td>150000</td>
								<td></td>
								</tr>
								<tr>
								<td><lable>i)</lable> <input type="text" id="gmt_text1" placeholder="enter text" name="gmt_text1"></td>
								<td><input type="number" class="qty1" placeholder="enter Gross Amount" id="gamt1" name="gmat1"> <br>
								<input type="number" class="qty1" placeholder="enter Qualifying Amt" id="gamt2" name="gmat2"> <br>
								<input type="number" class="qty1" placeholder="enter Deductible Amt" id="gamt3" name="gmat3"></td>
								<td></td>
								<td></td>
								<td>
								<select id="select9" onchange="check_file(9)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile9" name="myfile9" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><lable>ii)</lable> <input type="text" id="gmt_text2" placeholder="enter text" name="gmt_text2"></td>
								<td><input type="number" class="qty1"placeholder="enter Gross Amount" id="gamt21" name="gmat21"> <br>
								<input type="number"class="qty1" placeholder="enter Qualifying Amt" id="gamt22" name="gmat22"> <br>
								<input type="number"class="qty1" placeholder="enter Deductible Amt" id="gamt23" name="gmat23"></td>
								<td></td>
								<td></td>
								<td>
								<select id="select10" onchange="check_file(10)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile10" name="myfile10" style="display:none">
								</td>
								</tr>
								<tr>
								<td><lable>iii)</lable> <input type="text" id="gmt_text3" placeholder="enter text" name="gmt_text3"></td>
								<td><input type="number"class="qty1" placeholder="enter Gross Amount" id="gamt31" name="gmat31"> <br>
								<input type="number" class="qty1"placeholder="enter Qualifying Amt" id="gamt32" name="gmat32"> <br>
								<input type="number"class="qty1" placeholder="enter Deductible Amt" id="gamt33" name="gmat33"></td>
								<td></td>
								<td></td>
								<td>
								<select id="select11" onchange="check_file(11)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile11" name="myfile11" style="display:none">
								</td>
								</tr>
								<tr>
								<td><lable>b) Section 80CCC</lable> </td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								<tr>
								<td><lable>iii)</lable> <input type="text" id="gmt_text3" placeholder="enter text" name="gmt_text3"></td>
								<td><input type="number" placeholder="enter Gross Amount" id="sec21" name="sec21"> <br>
								<input type="number" placeholder="enter Qualifying Amt" id="sec22" name="sec22"> <br>
								<input type="number" placeholder="enter Deductible Amt" id="sec23" name="sec23"></td>
								<td></td>
								<td></td>
								<td>
								<select id="select12" onchange="check_file(12)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile12" name="myfile12" style="display:none">
								</td>
								</tr>
								<tr>
								<tr>
								<td><lable>c) Section 80CCD</lable>  </td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								<td><lable>iii)</lable> <input type="text" id="gmt_text3" placeholder="enter text" name="gmt_text3"></td>
								<td><input type="number" placeholder="enter Gross Amount" id="sec31" name="sec31"> <br>
								<input type="number" placeholder="enter Qualifying Amt" id="sec32" name="sec32"> <br>
								<input type="number" placeholder="enter Deductible Amt" id="sec33" name="sec33"></td>
								<td></td>
								<td></td>
								<td>
								<select id="select13" onchange="check_file(13)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile13" name="myfile13" style="display:none">
								</td>
								</tr>
								
								<tr>
								<td><h5><b>B.Other Sections ( e.g. 80E, 80G, 80TTA etc) Under Chapter VIA </b></h5></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								</tr>
								
								<tr>
								<td><lable>i)</lable> <input type="text" id="secbt1" placeholder="enter text" name="secbt1"></td>
								<td><input type="number" placeholder="enter Gross Amount" id="secb11" name="secb11"> <br>
                                            <input type="number" placeholder="enter Qualifying Amt" id="secb12" name="secb12"> <br>
                                            <input type="number" placeholder="enter Deductible Amt" id="secb13" name="secb13"></td>
								<td></td>
								<td></td>
								<td>
								<select id="select14" onchange="check_file(14)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile14" name="myfile14" style="display:none">
								</td>
								</tr>
								<tr>
								<td><lable>ii)</lable> <input type="text" id="secbt2" placeholder="enter text" name="secbt2"></td>
								<td> <input type="number" placeholder="enter Gross Amount" id="secb21" name="secb21"> <br>
								<input type="number" placeholder="enter Qualifying Amt" id="secb22" name="secb22"> <br>
								<input type="number" placeholder="enter Deductible Amt" id="secb23" name="secb23"></td>
								<td></td>
								<td></td>
								<td>
								<select id="select15" onchange="check_file(15)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile15" name="myfile15" style="display:none">
								</td>
								</tr>
								<tr>
								<td><lable>iii)</lable> <input type="text" id="secbt3" placeholder="enter text" name="secbt3"></td>
								<td> <input type="number" placeholder="enter Gross Amount" id="secb31" name="secb31"> <br>
								<input type="number" placeholder="enter Qualifying Amt" id="secb32" name="secb32"> <br>
								<input type="number" placeholder="enter Deductible Amt" id="secb33" name="secb33"></td>
								<td></td>
								<td></td>
								<td>
								<select id="select16" onchange="check_file(16)">
								<option value="1">Estimated</option>
								<option value="2">Actual</option> 
								</select><br>
								<input type="file" id="myfile16" name="myfile16" style="display:none">
								</td>
								</tr>
								<tr>
								</tbody>
								</table>
								<button type="submit" class="btn btn-primary">ADD</button>
								</form>
								</div>
							</div>					
							</div>
						<?php $this->load->view('human_resource/footer'); ?>
							
							</div>
							</div>
							<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>

function check_file(id)
{
	var a=$("#select"+id).val();
	if(a == 2)
	{
		 $("#myfile"+id).show(); 
	}else{
		 $("#myfile"+id).hide(); 
	}
	
	 
}
$(document).on("change", ".qty1", function() {
    var sum = 0;
    $(".qty1").each(function(){
        sum += +$(this).val();
    });
    if(sum >150000)
	{
		alert("Sum of Sections 80C,80CC and 80CCD values Should less than or equal to 1.5 lakh");
		$(".qty1").val("");
	}else{
		
	}
});
$(document).on("change", ".qty2", function() {
    var sum = 0;
    $(".qty2").each(function(){
        sum += +$(this).val();
    });
    if(sum >150000)
	{
		alert("Less:Loss From House Properties value Should less than or equal to 1.5 lakh");
		$(".qty2").val("");
	}else{
		
	}
});
									$("#add_form").validate({//form id
                                                 
                                                errorElement: "span",
                                                submitHandler: function (form) {
													var formid = document.getElementById("add_form");
                                                    $.ajax({
                                                        url: '<?= base_url("Form16/addform16") ?>',
                                                        type: "POST",
                                                        data: new FormData(formid), 
														  processData: false,
														contentType: false,
														cache: false,
														async: false,
                                                        success: function (success) {
                                                            
                                                            if (success.message === "success") {
                                                                alert(success.body);
                                                                $("#add_form")[0].reset();
                                                            } else if(success.message === "error") {
                                                                alert(success.body);
                                                                
                                                            } else{
																alert(success.body);
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
</script>	
							
							