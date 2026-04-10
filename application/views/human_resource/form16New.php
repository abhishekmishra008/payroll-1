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
                        <span class="caption-subject bold uppercase">Form 16</span>
                    </div>
                </div>
                <input type='hidden' id='user_type' name='user_type' value='<?php echo $user_type ?>'>  


                <div class="portlet-body">
                    <div class="panel-group accordion" id="accordion3">
                        <div class="panel panel-default" style="overflow-y: scroll;width:100%;">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1" aria-expanded="false"> Submit Details </a>
                                </h4>
                            </div>
                            <div id="collapse_3_1" class="panel-collapse collapse " aria-expanded="false">

                                <div class="panel-body">

                                    <?php
                                    if ($user_type == 5) {
                                        ?>
                                        <div class="col-md-6">

                                            <select id='emp_id' class='form-control' name='emp_id' onchange="get_form()">

                                            </select>
                                            <br>  <br>
                                        </div>
                                    <?php } else { ?>
                                        <input type='hidden' id='emp_id' name='emp_id' value='<?php echo $emp_id ?>'>      
                                    <?php }
                                    ?> 


                                    <form id="add_form" name="add_form" method="post">
                                        <div class="col-md-3">
                                            <select class="form-control" id="select_year" name="select_year" onchange="get_data();">

                                            </select>
                                        </div>
<div  >										
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Text</th>
                                                    <th>Value</th>
                                                    <th>Type</th>
                                                    <th>Max Value</th>
                                                    <th>File</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><h5><b>1.Gross Salary *</b></h5></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td><h5>Salary as per provisions contained in section 17</h5></td>
                                                    <td></td>
                                                    <td>calculated</td>
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
                                                        <input type="file" id="myfile1" name="myfile1" style="display:none" onchange="myFunction(1)">
                                                        <input type="hidden" id="myfile1a" name="myfile1a">
                                                    </td>
                                                    <td id="sts1"></td>
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
                                                        <input type="file" id="myfile2" name="myfile2" style="display:none" onchange="myFunction(2)">
                                                        <input type="hidden" id="myfile2a" name="myfile2a">
                                                    </td>
                                                    <td id="sts2"></td>
                                                </tr><tr>
                                                    <td><h5><lable>Reported Total amount of salary received from other employer(s) </lable> </h5></td>
                                                    <td><input type="number" placeholder="enter value" id="gross4" name="gross4"></td>
                                                    <td>variable</td>
                                                    <td></td>
                                                    <td>
                                                        <select id="select10" onchange="check_file(10)">
                                                            <option value="1">Estimated</option>
                                                            <option value="2">Actual</option> 
                                                        </select><br>
                                                        <input type="file" id="myfile10" name="myfile10" style="display:none" onchange="myFunction(10)">
                                                        <input type="hidden" id="myfile10a" name="myfile10a">
                                                    </td>
                                                    <td id="sts2"></td>
                                                </tr>

                                                <tr>
                                                    <td><h5><b>2.Less : Allowance to the extent exempt under section 10</b></h5></td>
                                                    <td></td>
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
                                                <input type="file" id="myfile3" name="myfile3" style="display:none" onchange="myFunction(3)">
                                                <input type="hidden" id="myfile3a" name="myfile3a">
                                            </td>
                                            <td id="sts3"></td>
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
                                                <input type="file" id="myfile4" name="myfile4" style="display:none" onchange="myFunction(4)">
                                                <input type="hidden" id="myfile4a" name="myfile4a">
                                            </td>
                                            <td id="sts4"></td>
                                            </tr>
                                            <tr>
                                                <td><h5><b>3.Total amount of salary received from current employer [1(d)-2(b)]<b></h5></td>
                                                                <td></td>
                                                                <td>calculated</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td><h5><b>4.Income chargeable under the head "Salaries" [(3+1(e)} )</b></h5></td>
                                                                    <td></td>
                                                                    <td>calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                

                                                                <tr>
                                                                    <td><lable>5 .Add. : Any other income reported by the employee</lable></td>
                                                                <td>  <input type="number"  placeholder="enter value" id="add_inc" name="add_inc"></td>
                                                                <td>Variable</td>
                                                                <td></td>
                                                                <td>
                                                                    <select id="select7" onchange="check_file(7)">
                                                                        <option value="1">Estimated</option>
                                                                        <option value="2">Actual</option> 
                                                                    </select><br>
                                                                    <input type="file" id="myfile7" name="myfile7" style="display:none" onchange="myFunction(7)">
                                                                    <input type="hidden" id="myfile7a" name="myfile7a">
                                                                </td>
                                                                <td id="sts7"></td>
                                                                </tr>

                                                                
                                                                <tr>
                                                                    <td><h5><b>6. Total amount of other income reported by the employee	 </b></h5></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><h5><b>7 .Total Taxable income (4+6)	</b></h5></td>
                                                                    <td></td>
                                                                    <td>Calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
																<tr>
                                                                    <td><h5><b>8 .Tax on total income	</b></h5></td>
                                                                    <td></td>
                                                                    <td>Calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>

                                                                

                                                                
                                                                <tr>
                                                                    <td><h5><b>9. Rebate under section 87a if applicable 		 </b></h5></td>
                                                                    <td></td>
                                                                    <td>Calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><h5><b>10.Surcharge, wherever applicable		</b></h5></td>
                                                                    <td></td>
                                                                    <td>Calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                               
                                                                <tr>
                                                                    <td><h5><b>11. Health and education cess		</td>
                                                                    <td></td>
                                                                    <td>Calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><h5><b>12. Tax payable(8-9+10+11)</b></h5></td>
                                                                    <td></td>
                                                                    <td>Calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><h5><b>13. Less: Relief under section 89 (attach details)</b></h5></td>
                                                                    <td></td>
                                                                    <td>Calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><h5><b>14. Net tax payable (12-13)</b></h5></td>
                                                                    <td></td>
                                                                    <td>Calculated</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                
                                                                </tbody>
                                                                </table>
																</div>
                                                                <button type="submit" id="add_btn" class="btn btn-primary">ADD</button>
                                                                </form>
                                                                </div>
                                                                </div>
                                                                </div>

                                                                <div class="panel panel-default"  style="overflow-y: scroll;width:100%;">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_2" aria-expanded="false"> Preview Form16  </a>
                                                                        </h4>
                                                                    </div>

                                                                    <div id="collapse_3_2" class="panel-collapse collapse" aria-expanded="false">
                                                                                               <!--<input type="button" style="width:60%;padding:6px" id="btnExport" value="Generate PDF" onclick="Export()" class="btn btn-success"/><hr>-->
                                                                        <div class="panel-body" style="height:auto;">
                                                                            <div class="row">
                                                                                <table id="pdf_form_16"  class="a table table-bordered" style="border: 1px solid #000000; width:60%;font-family: sans-serif;font-stretch: normal;font-size:25px;word-spacing: -0.5px;height: auto;">
                                                                                    <tr>
                                                                                        <td style="border: 1px solid #000000;font-size:18px;word-spacing: 1.5px;" colspan="12" height="20" align="center" valign="bottom" bgcolor="#FFFFFF"><b><font face="Arial">Part B</font></b><p><b>DETAILS OF SALARY PAID AND ANY OTHER INCOME AND TAX DEDUCTED</b></p></td>
                                                                                    </tr>
                                                                                    <tr id="gross_salary" colspan="12"style="border: 1px solid #000000; ">
                                                                                        <td id="gross" width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:20px;word-spacing: -0.5px">
                                                                                            <b>1.Gross Salary*</b> 
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="">
                                                                                        </td> 
                                                                                    </tr>

                                                                                    <!---Salary as per provisions contained in section 17 --->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="salary_as_provision" width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; word-spacing: -0.5px">
                                                                                            ( a ) Salary as per provisions contained in section 17 (1) 
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="salary_provision_first">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="salary_provision_second">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="salary_provision_third">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!---( b ) Value of perquisites under section 17 (2)--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="salary_as_provision" width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            ( b ) Value of perquisites under section 17 (2)<br>
                                                                                            (as per Form No. 12 BA, wherever applicable)
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="value_of_perquisites1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="value_of_perquisites2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="value_of_perquisites3">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!---( c ) Profits in lieu of Salary under section 17 (3)-->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="salary_as_provision" width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            ( c ) Profits in lieu of Salary under section 17 (3)<br>
                                                                                            (as per Form No. 12 BA, wherever applicable)
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="profit_lieu_salary1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="profit_lieu_salary2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="profit_lieu_salary3">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--( d ) Total--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="salary_as_provision" width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            ( d ) Total
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="total_gross_sal1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="total_gross_sal2">-
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="total_gross_sal3">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--2. Less : Allowance to the extent exempt under section 10--->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Allowance " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b> 2. Less : Allowance to the extent exempt under section 10</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!-----a-->
                                                                                    <tr id="" colspan="12" style="border: 1px solid #000000;">
                                                                                        <td id="Allowance_extent_a" width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            a)&nbsp;<applet id="Allowance_extent_a1"></applet>
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="allowance_a1">
                                                                                    </td> 
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="allowance_a2">
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="allowance_a3">
                                                                                    </td>
                                                                                    </tr>

                                                                                    <!-----b-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Allowance_extent_b" width="50%" style="border: 1px solid #000000; font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            b)&nbsp;<applet id="Allowance_extent_b1"></applet>
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="allowance_b1">
                                                                                    </td> 
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="allowance_b2">
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="allowance_b3">
                                                                                    </td>
                                                                                    </tr>

                                                                                    <!-----Balance-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Balance " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>3. Balance (1-2)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="total_balance1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="total_balance2">-
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="total_balance3">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!---4. Deductions :--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deduction " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            <b>4. Deductions :</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!---(a) Standard deduction--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deduction " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (a) Standard deduction
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="standard_deduction1">50000
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="standard_deduction2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="standard_deduction3">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!----(b) Entertainment allowance--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deduction " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            (b) Entertainment allowance
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="entertainment_allow1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="entertainment_allow2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="entertainment_allow3">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!---(c) Tax on Employment-->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deduction " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            (c) Tax on Employment
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_on_employment1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_on_employment2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="tax_on_employment3">
                                                                                        </td>
                                                                                    </tr>

                                                                                   

                                                                                    <!---(f) Other Deductions-->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deduction " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (d) Other Deductions
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="other_deduction1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="other_deduction2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="other_deduction3">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!---5. Aggregate of 4 (a to c)-->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Aggregate4Toc " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>5. Aggregate of 4 (a to e)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="aggereagate_ac1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="aggereagate_ac2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="aggereagate_ac3">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!---6. Income chargeable under the Head ‘Salaries’(3-5)--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="IncomeChargable " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>6. Income chargeable under the Head ‘Salaries’(3-5)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="income_chargable1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="income_chargable2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="income_chargable3">-
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--7. Add. : Any other income reported by the employee--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="IncomeEmployee " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            <b>7. Add. : Any other income reported by the employee</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="income_employee1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="income_employee2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="income_employee3">-
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--Less:-  Loss From House Properties--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="LossHouseProperties " width="50%" style="border: 1px solid #000000; font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            Less:-  Loss From House Properties
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="loss_income_employee1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="loss_income_employee2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="loss_income_employee3">-
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--8. Gross total income  (6+7)--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Gross_total_income " width="50%" style="border: 1px solid #000000; font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            <b>8. Gross total income  (6+7)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="gross_total_income1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="gross_total_income2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="gross_total_income3">-
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--9. Deductions Under Chapter VIA--->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td  colspan="12" id="Deductions_chapter " width="50%" style="border: 1px solid #000000; font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            <b> 9. Deductions Under Chapter VIA    </b>
                                                                                        </td>

                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  "><b>A.</b> Sections 80C,80CC and 80CCD</td>
                                                                                        <td style="border: 1px solid #000000; font-family: sans-serif;font-stretch: normal;font-size:19px; "><b>Gross Amount</b></td>
                                                                                        <td style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  "><b>Qualifying Amt.</b></td>

                                                                                        <td style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; "><b>Deductible Amt</b></td>

                                                                                    </tr>

                                                                                    <!--(a) Section 80C-->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (a) Section 80C
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="">
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!---(i)-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="section_80c_name1 " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (i)&nbsp;&nbsp;&nbsp;<applet id="section_80c_name11">Providant Fund</applet> 
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80c_i1">-
                                                                                    </td> 
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="section_80c_i2">-
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="section_80c_i3">-
                                                                                    </td>
                                                                                    </tr>

                                                                                    <!---(ii)-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="section_80c_name2 " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (ii)&nbsp;&nbsp;&nbsp;<applet id="section_80c_name22"></applet> 
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80c_ii1">-
                                                                                    </td> 
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80c_ii2">-
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="section_80c_ii3">-
                                                                                    </td>
                                                                                    </tr>

                                                                                    <!---(iii)-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="section_80c_name3 " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (iii)&nbsp;&nbsp;&nbsp;<applet id="section_80c_name33"></applet>
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80c_iii1">-
                                                                                    </td> 
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80c_iii2">-
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="section_80c_iii3">-
                                                                                    </td>
                                                                                    </tr>


                                                                                    <!--(b) Section 80CCC-->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (b) Section 80CCC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80ccc1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80ccc2">-
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="section_80ccc3">-
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--(c)  Section 80CCD-->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            (c) Section 80CCD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80ccd1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80ccd2">-
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="section_80ccd3">-
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--Aggregate amount deductible under the three sections -->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>Aggregate amount deductible under the three sections<br>
                                                                                                i.e.80C, 80CCC and 80CCD</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="aggregate_amt_80C1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="aggregate_amt_80C2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="aggregate_amt_80C3">0
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--(d)  Section 80CCD (1B)-->

                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            (d)Section 80CCD (1B)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80ccd_b1">-
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="section_80ccd_b2">-
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="section_80ccd_b3">-
                                                                                        </td>
                                                                                    </tr>


                                                                                    <!---Other Sections --->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td  colspan="12" id="Other Sections  " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">

                                                                                        </td>

                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  "><b>B.</b> Other Sections ( e.g. 80E, 80G, 80TTA etc) Under Chapter VIA</td>
                                                                                        <td style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  "><b>Gross Amount</b></td>
                                                                                        <td style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  "><b>Qualifying Amt.</b></td>

                                                                                        <td style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  "><b>Deductible Amt</b></td>

                                                                                    </tr>

                                                                                    <!---(i)-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            (i)&nbsp;&nbsp;&nbsp;<applet id="ot_section_header1"></applet> 
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="other_Sections_i1">-
                                                                                    </td> 
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="other_Sections_i2">-
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="other_Sections_i3">-
                                                                                    </td>
                                                                                    </tr>

                                                                                    <!---(ii)-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (ii)&nbsp;&nbsp;&nbsp;<applet id="ot_section_header2"></applet> 
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="other_Sections_ii1">-
                                                                                    </td> 
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="other_Sections_ii2">-
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="other_Sections_ii3">-
                                                                                    </td>
                                                                                    </tr>

                                                                                    <!---(iii)-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            (iii)&nbsp;&nbsp;&nbsp;<applet id="ot_section_header3"></applet> 
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="other_Sections_iii1">-
                                                                                    </td> 
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="other_Sections_iii2">-
                                                                                    </td>
                                                                                    <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="other_Sections_iii3">-
                                                                                    </td>
                                                                                    </tr>

                                                                                    <!--10. Aggregate of deductible amount under chapter VI-A)-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="Deductions_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>10. Aggregate of deductible amount under chapter VI-A</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="aggregate_deductible_amt1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="aggregate_deductible_amt2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="aggregate_deductible_amt3">-
                                                                                        </td>
                                                                                    </tr>

                                                                                    <!--11. Total Income (8-10 )-->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="total_income " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            <b>11. Total Income (8-10 )</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="total_income8to10_1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="total_income8to10_2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="total_income8to10_3">-
                                                                                        </td>


                                                                                    </tr>

                                                                                    <!--12. Tax on total Income -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="taxon_total_income " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>12. Tax on total Income </b>
																							
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000; font-family:Cambria;font-size:19px;" id="taxon_total_income1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="taxon_total_income2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="taxon_total_income3">
                                                                                        </td>


                                                                                    </tr>

                                                                                    <!--13. Rebate U/S 87a -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="reabate_87a " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>13. Rebate U/S 87a </b>(Rebate is 0 if tax on total income is greater than or equal to 12500)
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="reabate_us_87a1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="reabate_us_87a2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="reabate_us_87a3">-
                                                                                        </td>


                                                                                    </tr>


                                                                                    <!--14. Tax Payable on total income (12-13) -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="tax_payable_ttl_income " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            <b>14. Tax Payable on total income (12-13)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_payable_ttl_income1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_payable_ttl_income2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="tax_payable_ttl_income3">
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--15.Education & Health Cess 4% -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="education_health " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>15.Education & Health Cess 4%</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="education_health1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="education_health2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="education_health3">
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--16. Tax payable (14+15) -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="tax_payable_14_15 " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>16. Tax payable (14+15)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_payable_14_15_1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_payable_14_15_2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="tax_payable_14_15_3">
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--17. Relife Under Section 89 (attach details) -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="relief_section89 " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>17. Relife Under Section 89 (attach details)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="relief_section89_1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="relief_section89_2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="relief_section89_3">-
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--18. Tax payable (16-17) -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="relief_section89 " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>18. Tax payable (16-17)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_payable_1617_1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_payable_1617_2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="tax_payable_1617_3">
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--19.Tax Deducted at source U/S 192 -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="relief_section89 " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>19.Tax Deducted at source U/S 192</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_deducted_source1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_deducted_source2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="tax_deducted_source3">-
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--20. Tax payable / refundable (17-18) -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td id="tax_payble_refundable " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px;  ">
                                                                                            <b>20. Tax payable / refundable (17-18)</b>
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_payble_refundable1">
                                                                                        </td> 
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px; " id="tax_payble_refundable2">
                                                                                        </td>
                                                                                        <td style="border: 1px solid #000000;font-family:Cambria;font-size:19px;" id="tax_payble_refundable3">-
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--Verification -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td  colspan="12" id="Verificaion " width="50%" style="border: 1px solid #000000; text-align: center;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            <b> Verification   </b>
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--Verification -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td  colspan="12" id="Verificaion_chapter " width="50%" style="border: 1px solid #000000;font-family: sans-serif;font-stretch: normal;font-size:19px; ">
                                                                                            I,_________________Son/Daughter of ________________ Working In The Capacity Of________________
                                                                                            (Designation) do hereby certify that information given above is true correct based on the book of 
                                                                                            accounts,documents and TDS Statements,TDS Deposited and other available records.
                                                                                        </td>

                                                                                    </tr>

                                                                                    <!--Verification -->
                                                                                    <tr id="" colspan="12"style="border: 1px solid #000000;">
                                                                                        <td  colspan="12" id="signature " width="50%" style="border: 1px solid #000000;font-family: Cambria;font-size:19px;">
                                                                                            <p> Place:</p><br>
                                                                                            <p>Date:</p>
                                                                                            <p style="text-align: right;"> Signature & Seal of the person responsible for deduction of tax</p>
                                                                                            <p style="text-align: right;">Full Name:________________________________________</p>
                                                                                            <p style="text-align: right;">Designation:______________________________________</p>
                                                                                        </td>

                                                                                    </tr>







                                                                                </table>



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

                                                                <script src="<?php echo base_url() . "assets/"; ?>global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
                                                                <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
                                                                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
                                                                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
                                                                <script>

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
                                                                            function myFunction(id) {
                                                                                var x = document.getElementById("myfile" + id).value;
                                                                                document.getElementById("myfile" + id + "a").value = x;
                                                                            }
                                                                            function check_file(id)
                                                                            {
                                                                                var a = $("#select" + id).val();
                                                                                if (a == 2)
                                                                                {
                                                                                    $("#myfile" + id).show();
                                                                                } else {
                                                                                    $("#myfile" + id).hide();
                                                                                }


                                                                            }
//                                                                            $("#add_form").validate({//form id
//                                                                                rules: {
//                                                                                    myfile1: "required",
//                                                                                    myfile2: "required",
//                                                                                    myfile3: "required",
//                                                                                    myfile4: "required",
//                                                                                    myfile5: "required",
//                                                                                    myfile6: "required",
//                                                                                    myfile7: "required",
//                                                                                    myfile8: "required",
//                                                                                    myfile9: "required",
//                                                                                    myfile10: "required",
//                                                                                    myfile11: "required",
//                                                                                    myfile12: "required",
//                                                                                    myfile13: "required",
//                                                                                    myfile14: "required",
//                                                                                    myfile15: "required",
//                                                                                    myfile16: "required",
//                                                                                    myfile17: "required",
//                                                                                    myfile18: "required",
//                                                                                    myfile19: "required",
//                                                                                },
//                                                                                errorElement: "span",
//                                                                                submitHandler: function (form) {
//                                                                                    var formid = document.getElementById("add_form");
//                                                                                    $.ajax({
//                                                                                        url: '<?= base_url("Form16/addform16") ?>',
//                                                                                        type: "POST",
//                                                                                        data: new FormData(formid),
//                                                                                        processData: false,
//                                                                                        contentType: false,
//                                                                                        cache: false,
//                                                                                        async: false,
//                                                                                        success: function (success) {
//                                                                                            success = JSON.parse(success);
//                                                                                            console.log(success.message);
//                                                                                            if (success.message == "success") {
//                                                                                                alert(success.body);
//                                                                                                //location.reload();
//                                                                                            } else {
//                                                                                                alert(success.body);
//                                                                                            }
//                                                                                        },
//                                                                                        error: function (error) {
//                                                                                            //alert(success.body);
//                                                                                            console.log(error);
//                                                                                            alert("something went to wrong");
//                                                                                        }
//                                                                                    });
//                                                                                }
//                                                                            }
//                                                                            );
                                                                            $(document).ready(function () {
																				
                                                                                $('#category').change(function () {
                                                                                    var id = $(this).val();
                                                                                    $.ajax({
                                                                                        url: "<?php echo site_url('Form16/get_sub_category'); ?>",
                                                                                        method: "POST",
                                                                                        data: {Sr_no_header: id},
                                                                                        async: true,
                                                                                        dataType: 'json',
                                                                                        success: function (data) {

                                                                                            var html = '';
                                                                                            var i;
                                                                                            for (i = 0; i < data.length; i++) {
                                                                                                html += '<option value=' + data[i].id + '>' + data[i].sub_detail + '</option>';
                                                                                            }
                                                                                            $('#sub_category').html(html);
                                                                                        }
                                                                                    });
                                                                                    return false;
                                                                                });
                                                                              get_taxes();
                                                                            });
                                                                            function add() {
                                                                                var formid = document.getElementById("satff_form");
                                                                                $.ajax({
                                                                                    url: '<?= base_url("Form16/add") ?>',
                                                                                    type: "POST",
                                                                                    datatype: "JSON",
                                                                                    data: new FormData(formid), //form data
                                                                                    processData: false,
                                                                                    contentType: false,
                                                                                    cache: false,
                                                                                    async: false,
                                                                                    success: function (success) {
                                                                                        success = JSON.parse(success);
                                                                                        if (success.status === true) {
                                                                                            alert(" added  Successfully.");
                                                                                            window.location.reload();
                                                                                        } else {
                                                                                            alert(" Something went wrong.");
                                                                                        }
                                                                                    },
                                                                                    error: function (error) {
                                                                                        toastr.error(success.body);
                                                                                        console.log(error);
                                                                                        errorNotify("something went to wrong");
                                                                                    }
                                                                                });
                                                                            }

                                                                            function get_data()
                                                                            {
                                                                                var emp_id = document.getElementById('emp_id').value;
                                                                                //                                        alert(emp_id);
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "<?= base_url("Form16/view_form16_Details") ?>",
                                                                                    dataType: "json",
                                                                                    async: false,
                                                                                    cache: false,
                                                                                    data: {employee_id: emp_id},
                                                                                    success: function (result) {
                                                                                        var data = result.result_data;
                                                                                        console.log(data);
                                                                                        if (result.status == 200) {
                                                                                            $('#data_tablediv').html(data);
                                                                                            $('#data_table').DataTable();
                                                                                        } else {
                                                                                            $('#data_tablediv').html("");
                                                                                        }
                                                                                    },
                                                                                });
                                                                            }
                                                                           

                                                                            function get_employee()
                                                                            {
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "<?= base_url("Form16/get_employee") ?>",
                                                                                    dataType: "json",
                                                                                    success: function (result) {
                                                                                        var option = result.option;
                                                                                        if (result.status == 200) {
                                                                                            $('#emp_id').html(option);
                                                                                        } else {
                                                                                            $('#emp_id').html(option);
                                                                                        }
                                                                                    },
                                                                                });
                                                                            }
																			function get_taxes()
                                                                            {
																				var emp_id = document.getElementById('emp_id').value;
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "<?= base_url("Form16/get_taxes") ?>",
                                                                                    dataType: "json",
																					data:{emp_id:emp_id},
                                                                                    success: function (result) {
                                                                                        var option = result.data;
                                                                                        if (result.status == 200) {
                                                                                            $('#tax_table').html(option);
                                                                                        } else {
                                                                                            $('#tax_table').html(option);
                                                                                        }
                                                                                    },
                                                                                });
                                                                            }

                                                                            function accept_file(file)
                                                                            {
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "<?= base_url("Form16/accept_file") ?>",
                                                                                    dataType: "json",
                                                                                    data: {file: file},
                                                                                    success: function (result) {
                                                                                        var option = result.option;
                                                                                        if (result.status == 200) {
                                                                                            alert("accepted successfully.");
                                                                                            $("#add_form").trigger("reset");
                                                                                            get_salary_details();
                                                                                            get_status_deatil();
                                                                                        } else {
                                                                                            alert("Something Went Wrong.");
                                                                                        }
                                                                                    },
                                                                                });
                                                                            }
																			
																			
																			function get_incom_tax(total_income)
																			{
																				let mysthPromise = new Promise((resolve, reject) => {
																				$.ajax({
                                                                                    type: "POST",
                                                                                    url: "<?= base_url("Form16/get_datastandard") ?>",
                                                                                    dataType: "json",
                                                                                    data: {total_income: total_income},
                                                                                    success: function (result) {
                                                                                        if (result.status == 200) {
																							
                                                                                             var result_tax = result.result_tax;
																							 resolve(result_tax);
                                                                                        } else {
                                                                                           reject(0);
                                                                                        }
                                                                                    },
                                                                                });
																				});
																			return mysthPromise;
																			}

                                                                            $(document).ready(function () {
                                                                                get_salary_details();
                                                                                get_status_deatil();
                                                                                var user_type = document.getElementById('user_type').value;
                                                                              
                                                                                if (user_type == 5)
                                                                                {
                                                                                    get_employee();
                                                                                    $("#add_btn").hide();
                                                                                }
																				

                                                                            });

                                                                            function get_data()
                                                                            {
                                                                                get_salary_details();
                                                                                get_status_deatil();
                                                                            }

                                                                            function get_form()
                                                                            {
                                                                                get_salary_details();
                                                                                get_status_deatil();
                                                                            }
                                                                            function get_salary_details() {
                                                                                var emp_id = document.getElementById('emp_id').value;
                                                                                var year_id = document.getElementById('select_year').value;
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "<?= base_url("Form16/form16_salary_details") ?>",
                                                                                    dataType: "json",
                                                                                    async: false,
                                                                                    cache: false,
                                                                                    data: {employee_id: emp_id, year_id: year_id},
                                                                                    success: function (result) {



                                                                                        var data = result.results;
                                                                                        var prequisite_info = result.prequisite_info;
                                                                                        var deduction_amount = result.deduction_amount;
                                                                                        var provident_value = result.provident_value;
                                                                                        var yearly_months = result.yearly_months;
                                                                                        var form_16_gender = result.form_16_configure;
																						var tax_on_employement_tax = result.Proffessional_tax;
																						var Income_tax = result.Income_tax;
																						var paid_tax = result.paid_tax;
                                                                                        console.log(prequisite_info);
                                                                                        if (result.status === true) {
                                                                                            //Gross salary
																							
                                                                                            $("#gamt1").val(provident_value);
                                                                                            $("#salary_provision_first").html(data);
                                                                                            $("#tax_deducted_source3").html(paid_tax);
                                                                                            var client_block_prequisite = prequisite_info[0]['value'];
                                                                                            if ((prequisite_info[0]['status'] == "0" && prequisite_info[0]['file'] != "")) {
                                                                                                //                      $("#value_of_perquisites1").html(0);
                                                                                                var value_of_prequisite1 = document.getElementById("value_of_perquisites1").innerHTML = 0;
                                                                                            } else {
                                                                                                var value_of_prequisite1 = document.getElementById("value_of_perquisites1").innerHTML = client_block_prequisite;
                                                                                                //                      $("#value_of_perquisites1").html(client_block);
                                                                                            }
                                                                                            //                    
                                                                                            $("#gross2").val(prequisite_info[0]['value']);
                                                                                            var lieu_salary_block = prequisite_info[1]['value'];
                                                                                            if (prequisite_info[1]['status'] == "0" && prequisite_info[1]['file'] != "") {
                                                                                                //                        $("#profit_lieu_salary1").html(0);
                                                                                                var profit_lieu_salary1 = document.getElementById("profit_lieu_salary1").innerHTML = 0;
                                                                                            } else {

                                                                                                //                        $("#profit_lieu_salary1").html(prequisite_info[1]['value']);
                                                                                                var profit_lieu_salary1 = document.getElementById("profit_lieu_salary1").innerHTML = lieu_salary_block;
                                                                                            }

                                                                                            //                    alert(profit_lieu_salary1);
                                                                                            $("#gross3").val(prequisite_info[1]['value']);
                                                                                            //                    var total = +data['sum(value)'] + +prequisite_info[0]['value'] + +prequisite_info[1]['value'];

                                                                                            if (prequisite_info[0]['status'] == "0" && prequisite_info[1]['status'] == "0") {
                                                                                                var total = +data + +value_of_prequisite1 + +profit_lieu_salary1;
                                                                                            } else {
                                                                                                var total = +data + +value_of_prequisite1 + +profit_lieu_salary1;
                                                                                            }

                                                                                            $("#total_gross_sal2").html(total);
                                                                                            //Loss Allowance

                                                                                            $("#Allowance_extent_a1").html(prequisite_info[2]['value']); //text name of a
                                                                                            $("#la_text1").val(prequisite_info[2]['value']);
                                                                                            $("#Allowance_extent_b1").html(prequisite_info[3]['value']); //text name of b
                                                                                            $("#la_text2").val(prequisite_info[3]['value']);
                                                                                            var allowance_a1 = prequisite_info[4]['value'];
                                                                                            if (prequisite_info[4]['status'] == "0" && prequisite_info[4]['file'] != "") {
                                                                                                //                        $("#allowance_a1").html(0);
                                                                                                var value_allowance_a1 = document.getElementById("allowance_a1").innerHTML = 0;
                                                                                            } else {
                                                                                                //                        $("#allowance_a1").html(prequisite_info[4]['value']); //value of a
                                                                                                var value_allowance_a1 = document.getElementById("allowance_a1").innerHTML = allowance_a1;
                                                                                            }


                                                                                            $("#la_num1").val(prequisite_info[4]['value']);
                                                                                            var allowance_b1 = prequisite_info[5]['value'];
                                                                                            if (prequisite_info[5]['status'] == "0" && prequisite_info[5]['file'] != "") {
                                                                                                //                        $("#allowance_b1").html(0);
                                                                                                var value_allowance_b1 = document.getElementById("allowance_b1").innerHTML = 0;
                                                                                            } else {
                                                                                                //                        $("#allowance_b1").html(prequisite_info[5]['value']); //value of b
                                                                                                var value_allowance_b1 = document.getElementById("allowance_b1").innerHTML = allowance_b1;
                                                                                            }
                                                                                            //                    alert(value_allowance_b1);

                                                                                            $("#la_num2").val(prequisite_info[5]['value']);
                                                                                            if (prequisite_info[4]['status'] == "0" && prequisite_info[5]['status'] == "0") {
                                                                                                var allowance = +value_allowance_a1 + +value_allowance_b1;
                                                                                            } else {
                                                                                                var allowance = +value_allowance_a1 + +value_allowance_b1;
                                                                                            }

                                                                                            //                    alert(allowance);

                                                                                            //Balance (1-2)

                                                                                            var balance = total - allowance;
                                                                                            $("#total_balance2").html(Math.abs(balance)); //Total Balance



                                                                                            //Deduction
                                                                                            //                                                                     $("#standard_deduction1").html("50000");
                                                                                            document.getElementById("standard_deduction1").value = "50000";



                                                                                            var entertaiment_allowance = prequisite_info[8]['value'];
                                                                                            var entertainment_months = entertaiment_allowance * yearly_months;
                                                                                            if (prequisite_info[8]['status'] == "0" && prequisite_info[8]['file'] != "") {
                                                                                                var value_allowance = document.getElementById("entertainment_allow1").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_allowance = document.getElementById("entertainment_allow1").innerHTML = prequisite_info[8]['value'];
                                                                                            } else {
                                                                                                var value_allowance = document.getElementById("entertainment_allow1").innerHTML = entertainment_months;
                                                                                            }


                                                                                            $("#ded1").val(prequisite_info[8]['value']);
																							
																							
																							
																							 var tax_employment = document.getElementById("tax_on_employment1").innerHTML = tax_on_employement_tax;
																							


                                                                                            //$("#ded2").val(prequisite_info[8]['value']);
                                                                                          //  $("#professional_tax1").html(deduction_amount[0]); //value of Professional Tax

                                                                                            $("#other_deduction1").html(deduction_amount[1]); //value of other deduction

                                                                                            if (prequisite_info[8]['status'] == "0" && prequisite_info[9]['status'] == "0") {
                                                                                                var total_deduction = +value_allowance + +tax_employment;
                                                                                            } else {
                                                                                                var total_deduction = +value_allowance + +tax_employment ;
                                                                                            }




                                                                                            //Aggregate of 4(a to f)
                                                                                            var standard = document.getElementById("standard_deduction1").value;
                                                                                            var aggregate = +total_deduction + +standard;
                                                                                            $("#aggereagate_ac1").html(aggregate);
                                                                                            //6. Income chargeable under the Head ‘Salaries’(3-5)

                                                                                            var income_chargaeble = balance - aggregate;
                                                                                            $("#income_chargable3").html(Math.abs(income_chargaeble));
                                                                                            //7. Add. : Any other income reported by the employee

                                                                                            var any_other_income = prequisite_info[6]['value'];
                                                                                            if (prequisite_info[6]['status'] == "0" && prequisite_info[6]['file'] != "") {
                                                                                                var value_other_income = document.getElementById("income_employee3").innerHTML = 0;
                                                                                            } else {
                                                                                                var value_other_income = document.getElementById("income_employee3").innerHTML = any_other_income;
                                                                                            }

                                                                                            var any_loss_income = prequisite_info[7]['value'];
                                                                                            if (prequisite_info[7]['status'] == "0" && prequisite_info[7]['file'] != "") {
                                                                                                var value_loss_income = document.getElementById("loss_income_employee3").innerHTML = 0;
                                                                                            } else {
                                                                                                var value_loss_income = document.getElementById("loss_income_employee3").innerHTML = any_loss_income;
                                                                                            }



                                                                                            $("#add_inc").val(prequisite_info[6]['value']);
                                                                                            $("#less_inc").val(prequisite_info[7]['value']);
                                                                                            // 8. Gross total income (6+7)

                                                                                            if (prequisite_info[6]['status'] == "0" || prequisite_info[7]['status'] == "0") {
                                                                                                var gross_total_income = income_chargaeble + +value_other_income + +value_loss_income;
                                                                                            } else {
                                                                                                var gross_total_income = income_chargaeble + +value_other_income + +value_loss_income;
                                                                                            }



                                                                                            $("#gross_total_income3").html(Math.abs(gross_total_income));
                                                                                            //9. Deductions Under Chapter VIA - A. Sections 80C,80CC and 80CCD
                                                                                            //(a) Section 80C
                                                                                            $("#section_80c_name11").html("Providant Fund"); //section 80C (i) 
                                                                                            //                    $("#section_80c_i1 ").html(prequisite_info[11]['value']); //section 80C (i)
                                                                                           

                                                                                            //                                                                    $("#section_80c_i1").html(provident_value['value']);
                                                                                            var value_section_80c_i1 = document.getElementById("section_80c_i1").innerHTML = provident_value;

                                                                                            var section_80ci2 = prequisite_info[12]['value'];
                                                                                            var section_80ci2_months = section_80ci2 * yearly_months;
                                                                                            if (prequisite_info[12]['status'] == "0" && prequisite_info[12]['file'] != "") {
                                                                                                var value_section_80c_i2 = document.getElementById("section_80c_i2").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80c_i2 = document.getElementById("section_80c_i2").innerHTML = prequisite_info[12]['value'];
                                                                                            } else {
                                                                                                var value_section_80c_i2 = document.getElementById("section_80c_i2").innerHTML = section_80ci2_months;
                                                                                            }

                                                                                            var section_80ci3 = prequisite_info[13]['value'];
                                                                                            var section_80ci3_months = section_80ci3 * yearly_months;
                                                                                            if (prequisite_info[13]['status'] == "0" && prequisite_info[13]['file'] != "") {
                                                                                                var value_section_80c_i3 = document.getElementById("section_80c_i3").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80c_i3 = document.getElementById("section_80c_i3").innerHTML = prequisite_info[13]['value'];
                                                                                            } else {
                                                                                                var value_section_80c_i3 = document.getElementById("section_80c_i3").innerHTML = section_80ci3_months;
                                                                                            }


                                                                                            $("#gmt_text1").val(prequisite_info[10]['value']);
                                                                                            //$("#gamt1").val(prequisite_info[11]['value']);
                                                                                            $("#gamt2").val(prequisite_info[12]['value']);
                                                                                            $("#gamt3").val(prequisite_info[13]['value']);
                                                                                            if (prequisite_info[12]['status'] == "0" && prequisite_info[13]['file'] != "") {
                                                                                                var Section_80C_total1 = +value_section_80c_i1 + +value_section_80c_i2 + +value_section_80c_i3;
                                                                                            } else {
                                                                                                var Section_80C_total1 = +value_section_80c_i1 + +value_section_80c_i2 + +value_section_80c_i3;
                                                                                            }
                                                                                            $("#section_80c_name22").html(prequisite_info[14]['value']); //section 80C (ii)

                                                                                            var section_80c_ii1 = prequisite_info[15]['value'];
                                                                                            var section_80c_ii1_months = section_80c_ii1 * yearly_months;
                                                                                            if (prequisite_info[15]['status'] == "0" && prequisite_info[15]['file'] != "") {
                                                                                                //                        $("#section_80c_ii1").html(0);
                                                                                                var value_section_80c_ii1 = document.getElementById("section_80c_ii1").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80c_ii1 = document.getElementById("section_80c_ii1").innerHTML = prequisite_info[15]['value'];
                                                                                            } else {
                                                                                                //                        $("#section_80c_ii1 ").html(prequisite_info[15]['value']); //section 80C (ii)
                                                                                                var value_section_80c_ii1 = document.getElementById("section_80c_ii1").innerHTML = section_80c_ii1_months;
                                                                                            }

                                                                                            var section_80c_ii2 = prequisite_info[16]['value'];
                                                                                            var section_80c_ii2_months = section_80c_ii2 * yearly_months;
                                                                                            if (prequisite_info[16]['status'] == "0" && prequisite_info[16]['file'] != "") {
                                                                                                //                        $("#section_80c_ii2").html(0);
                                                                                                var value_section_80c_ii2 = document.getElementById("section_80c_ii2").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80c_ii2 = document.getElementById("section_80c_ii2").innerHTML = prequisite_info[16]['value'];
                                                                                            } else {
                                                                                                //                        $("#section_80c_ii2 ").html(prequisite_info[16]['value']); //section 80C (ii)
                                                                                                var value_section_80c_ii2 = document.getElementById("section_80c_ii2").innerHTML = section_80c_ii2_months;
                                                                                            }

                                                                                            var section_80c_ii3 = prequisite_info[17]['value'];
                                                                                            var section_80c_ii3_months = section_80c_ii3 * yearly_months;
                                                                                            if (prequisite_info[17]['status'] == "0" && prequisite_info[17]['file'] != "") {
                                                                                                //                        $("#section_80c_ii3").html(0);
                                                                                                var value_section_80c_ii3 = document.getElementById("section_80c_ii3").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80c_ii3 = document.getElementById("section_80c_ii3").innerHTML = prequisite_info[17]['value'];
                                                                                            } else {
                                                                                                //                        $("#section_80c_ii3 ").html(prequisite_info[17]['value']); //section 80C (iii)
                                                                                                var value_section_80c_ii3 = document.getElementById("section_80c_ii3").innerHTML = section_80c_ii3_months;
                                                                                            }


                                                                                            $("#gmt_text2").val(prequisite_info[14]['value']);
                                                                                            $("#gamt21").val(prequisite_info[15]['value']);
                                                                                            $("#gamt22").val(prequisite_info[16]['value']);
                                                                                            $("#gamt23").val(prequisite_info[17]['value']);
                                                                                            if (prequisite_info[15]['status'] == "0" && prequisite_info[15]['file'] != "" && prequisite_info[16]['status'] == "0" && prequisite_info[16]['file'] != "") {
                                                                                                var Section_80C_total2 = +value_section_80c_ii1 + +value_section_80c_ii2 + +value_section_80c_ii3;
                                                                                            } else {
                                                                                                var Section_80C_total2 = +value_section_80c_ii1 + +value_section_80c_ii2 + +value_section_80c_ii3;
                                                                                            }
                                                                                            $("#section_80c_name33").html(prequisite_info[18]['value']); //section 80C (ii)


                                                                                            var section_80c_iii1 = prequisite_info[19]['value'];
                                                                                            var section_80c_iii1_months = section_80c_iii1 * yearly_months;
                                                                                            if (prequisite_info[19]['status'] == "0" && prequisite_info[19]['file'] != "") {
                                                                                                //            $("#section_80c_iii1").html(0);
                                                                                                var value_section_80c_iii1 = document.getElementById("section_80c_iii1").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80c_iii1 = document.getElementById("section_80c_iii1").innerHTML = prequisite_info[19]['value'];
                                                                                            } else {
                                                                                                //            $("#section_80c_iii1 ").html(prequisite_info[19]['value']); //section 80C (iii)
                                                                                                var value_section_80c_iii1 = document.getElementById("section_80c_iii1").innerHTML = section_80c_iii1_months;
                                                                                            }

                                                                                            var section_80c_iii2 = prequisite_info[20]['value'];
                                                                                            var section_80c_iii2_months = section_80c_iii2 * yearly_months;
                                                                                            if (prequisite_info[20]['status'] == "0" && prequisite_info[20]['file'] != "") {
                                                                                                //            $("#section_80c_iii2").html(0);
                                                                                                var value_section_80c_iii2 = document.getElementById("section_80c_iii2").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80c_iii2 = document.getElementById("section_80c_iii2").innerHTML = prequisite_info[20]['value'];
                                                                                            } else {
                                                                                                //            $("#section_80c_iii2 ").html(prequisite_info[20]['value']); //section 80C (iii)
                                                                                                var value_section_80c_iii2 = document.getElementById("section_80c_iii2").innerHTML = section_80c_iii2_months;
                                                                                            }

                                                                                            var section_80c_iii3 = prequisite_info[21]['value'];
                                                                                            var section_80c_iii3_months = section_80c_iii3 * yearly_months;
                                                                                            if (prequisite_info[21]['status'] == "0" && prequisite_info[21]['file'] != "") {
                                                                                                //            $("#section_80c_iii3").html(0);
                                                                                                var value_section_80c_iii3 = document.getElementById("section_80c_iii3").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80c_iii3 = document.getElementById("section_80c_iii3").innerHTML = prequisite_info[21]['value'];
                                                                                            } else {
                                                                                                //            $("#section_80c_iii3 ").html(prequisite_info[21]['value']); //section 80C (iii)
                                                                                                var value_section_80c_iii3 = document.getElementById("section_80c_iii3").innerHTML = section_80c_iii3_months;
                                                                                            }



                                                                                            $("#gmt_text3").val(prequisite_info[18]['value']);
                                                                                            $("#gamt31").val(prequisite_info[19]['value']);
                                                                                            $("#gamt32").val(prequisite_info[20]['value']);
                                                                                            $("#gamt33").val(prequisite_info[21]['value']);
                                                                                            if (prequisite_info[19]['status'] == "0" && prequisite_info[19]['file'] != "" && prequisite_info[20]['status'] == "0" && prequisite_info[20]['file'] != "") {
                                                                                                var Section_80C_total3 = +value_section_80c_iii1 + +value_section_80c_iii2 + +value_section_80c_iii3;
                                                                                            } else {
                                                                                                var Section_80C_total3 = +value_section_80c_iii1 + +value_section_80c_iii2 + +value_section_80c_iii3;
                                                                                            }
                                                                                            //(b) Section 80CCC         Rs.

                                                                                            var section_80ccc1 = prequisite_info[22]['value'];
                                                                                            var section_80ccc1_months = section_80ccc1 * yearly_months;
                                                                                            if (prequisite_info[22]['status'] == "0" && prequisite_info[22]['file'] != "") {
                                                                                                var value_section_80ccc1 = document.getElementById("section_80ccc1").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80ccc1 = document.getElementById("section_80ccc1").innerHTML = prequisite_info[22]['value'];
                                                                                            } else {
                                                                                                var value_section_80ccc1 = document.getElementById("section_80ccc1").innerHTML = section_80ccc1_months;
                                                                                            }

                                                                                            var section_80ccc2 = prequisite_info[23]['value'];
                                                                                            var section_80ccc2_months = section_80ccc2 * yearly_months;
                                                                                            if (prequisite_info[23]['status'] == "0" && prequisite_info[23]['file'] != "") {
                                                                                                //                                                            $("#section_80ccc2").html(0);
                                                                                                var value_section_80ccc2 = document.getElementById("section_80ccc2").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80ccc2 = document.getElementById("section_80ccc2").innerHTML = prequisite_info[23]['value'];
                                                                                            } else {
                                                                                                //                                                            $("#section_80ccc2 ").html(prequisite_info[23]['value']); //Section 80CCC 
                                                                                                var value_section_80ccc2 = document.getElementById("section_80ccc2").innerHTML = section_80ccc2_months;
                                                                                            }

                                                                                            var section_80ccc3 = prequisite_info[24]['value'];
                                                                                            var section_80ccc3_months = section_80ccc3 * yearly_months;
                                                                                            if (prequisite_info[24]['status'] == "0" && prequisite_info[24]['file'] != "") {
                                                                                                //                                                            $("#section_80ccc3").html(0);
                                                                                                var value_section_80ccc3 = document.getElementById("section_80ccc3").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80ccc3 = document.getElementById("section_80ccc3").innerHTML = prequisite_info[24]['value'];
                                                                                            } else {
                                                                                                //                                                            $("#section_80ccc3 ").html(prequisite_info[24]['value']); //Section 80CCC 
                                                                                                var value_section_80ccc3 = document.getElementById("section_80ccc3").innerHTML = section_80ccc3_months;
                                                                                            }



                                                                                            $("#sec21").val(prequisite_info[22]['value']);
                                                                                            $("#sec22").val(prequisite_info[23]['value']);
                                                                                            $("#sec23").val(prequisite_info[24]['value']);
                                                                                            if (prequisite_info[22]['status'] == "0" && prequisite_info[22]['file'] != "" && prequisite_info[23]['status'] == "0" && prequisite_info[23]['file'] != "") {
                                                                                                var Section_80Ccc_total = +value_section_80ccc1 + +value_section_80ccc2 + +value_section_80ccc3;
                                                                                            } else {
                                                                                                var Section_80Ccc_total = +value_section_80ccc1 + +value_section_80ccc2 + +value_section_80ccc3;
                                                                                            }

                                                                                            //(c) Section 80CCD         Rs.
                                                                                            var section_80ccd1 = prequisite_info[25]['value'];
                                                                                            var section_80ccd1_months = section_80ccd1 * yearly_months;
                                                                                            if (prequisite_info[25]['status'] == "0" && prequisite_info[25]['file'] != "") {
                                                                                                //                                                            $("#section_80ccd1").html(0);
                                                                                                var value_section_80ccd1 = document.getElementById("section_80ccd1").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80ccd1 = document.getElementById("section_80ccd1").innerHTML = prequisite_info[25]['value'];
                                                                                            } else {
                                                                                                //                                                            $("#section_80ccd1 ").html(prequisite_info[25]['value']); //Section 80CCD 
                                                                                                var value_section_80ccd1 = document.getElementById("section_80ccd1").innerHTML = section_80ccd1_months;
                                                                                            }

                                                                                            var section_80ccd2 = prequisite_info[26]['value'];
                                                                                            var section_80ccd2_months = section_80ccd2 * yearly_months;
                                                                                            if (prequisite_info[26]['status'] == "0" && prequisite_info[26]['file'] != "") {
                                                                                                //                                                            $("#section_80ccd2").html(0);
                                                                                                var value_section_80ccd2 = document.getElementById("section_80ccd2").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80ccd2 = document.getElementById("section_80ccd2").innerHTML = prequisite_info[26]['value'];
                                                                                            } else {
                                                                                                //                                                            $("#section_80ccd2 ").html(prequisite_info[26]['value']); //Section 80CCD 
                                                                                                var value_section_80ccd2 = document.getElementById("section_80ccd2").innerHTML = section_80ccd2_months;
                                                                                            }

                                                                                            var section_80ccd3 = prequisite_info[27]['value'];
                                                                                            var section_80ccd3_months = section_80ccd3 * yearly_months;
                                                                                            if (prequisite_info[27]['status'] == "0" && prequisite_info[27]['file'] != "") {
                                                                                                //                                                            $("#section_80ccd3").html(0);
                                                                                                var value_section_80ccd3 = document.getElementById("section_80ccd3").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_section_80ccd3 = document.getElementById("section_80ccd3").innerHTML = prequisite_info[27]['value'];
                                                                                            } else {
                                                                                                //                                                            $("#section_80ccd3 ").html(prequisite_info[27]['value']); //Section 80CCD 
                                                                                                var value_section_80ccd3 = document.getElementById("section_80ccd3").innerHTML = section_80ccd3_months;
                                                                                            }


                                                                                            $("#sec31").val(prequisite_info[25]['value']);
                                                                                            $("#sec32").val(prequisite_info[26]['value']);
                                                                                            $("#sec33").val(prequisite_info[27]['value']);
                                                                                            if (prequisite_info[25]['status'] == "0" && prequisite_info[25]['file'] != "" && prequisite_info[26]['status'] == "0" && prequisite_info[26]['file'] != "") {
                                                                                                var Section_80CCD_total = +value_section_80ccd1 + +value_section_80ccd2 + +value_section_80ccd3;
                                                                                            } else {
                                                                                                var Section_80CCD_total = +value_section_80ccd1 + +value_section_80ccd2 + +value_section_80ccd3;
                                                                                            }

                                                                                            //Aggregate amount deductible under the three sections
                                                                                            var aggregate_three_section = Section_80C_total1 + Section_80C_total2 + Section_80C_total3 + Section_80Ccc_total + Section_80CCD_total;
                                                                                            //                                                            alert(aggregate_three_section);
                                                                                            $("#aggregate_amt_80C3").html(aggregate_three_section);
                                                                                            //B. Other Sections ( e.g. 80E, 80G, 80TTA etc) Under Chapter VIA i.e.80C, 80CCC and 80CCD

                                                                                            $("#ot_section_header1 ").html(prequisite_info[28]['value']); //Other Sections (i) 

                                                                                            var other_Sections_i1 = prequisite_info[29]['value'];
                                                                                            var other_Sections_i1_months = other_Sections_i1 * yearly_months;
                                                                                            if (prequisite_info[29]['status'] == "0" && prequisite_info[29]['file'] != "") {
                                                                                                var value_other_Sections_i1 = document.getElementById("other_Sections_i1").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_i1 = document.getElementById("other_Sections_i1").innerHTML = prequisite_info[29]['value'];
                                                                                            } else {
                                                                                                var value_other_Sections_i1 = document.getElementById("other_Sections_i1").innerHTML = other_Sections_i1_months;
                                                                                            }

                                                                                            var other_Sections_i2 = prequisite_info[30]['value'];
                                                                                            var other_Sections_i2_months = other_Sections_i2 * yearly_months;
                                                                                            if (prequisite_info[30]['status'] == "0" && prequisite_info[30]['file'] != "") {
                                                                                                var value_other_Sections_i2 = document.getElementById("other_Sections_i2").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_i2 = document.getElementById("other_Sections_i2").innerHTML = prequisite_info[30]['value'];
                                                                                            } else {
                                                                                                var value_other_Sections_i2 = document.getElementById("other_Sections_i2").innerHTML = other_Sections_i2_months; //Other Sections (i)
                                                                                            }

                                                                                            var other_Sections_i3 = prequisite_info[31]['value'];
                                                                                            var other_Sections_i3_months = other_Sections_i3 * yearly_months;
                                                                                            if (prequisite_info[31]['status'] == "0" && prequisite_info[31]['file'] != "") {
                                                                                                var value_other_Sections_i3 = document.getElementById("other_Sections_i3").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_i3 = document.getElementById("other_Sections_i3").innerHTML = prequisite_info[31]['value'];
                                                                                            } else {
                                                                                                var value_other_Sections_i3 = document.getElementById("other_Sections_i3").innerHTML = other_Sections_i3_months;
                                                                                            }


                                                                                            $("#secbt1").val(prequisite_info[28]['value']);
                                                                                            $("#secb11").val(prequisite_info[29]['value']);
                                                                                            $("#secb12").val(prequisite_info[30]['value']);
                                                                                            $("#secb13").val(prequisite_info[31]['value']);
                                                                                            if (prequisite_info[29]['status'] == "0" && prequisite_info[29]['file'] != "" && prequisite_info[30]['status'] == "0" && prequisite_info[30]['file'] != "") {
                                                                                                var total_othersection_i = +value_other_Sections_i1 + +value_other_Sections_i2 + +value_other_Sections_i3;
                                                                                            } else {
                                                                                                var total_othersection_i = +value_other_Sections_i1 + +value_other_Sections_i2 + +value_other_Sections_i3;
                                                                                            }
                                                                                            $("#ot_section_header2 ").html(prequisite_info[32]['value']); //Other Sections (ii) 

                                                                                            var other_Sections_ii1 = prequisite_info[33]['value'];
                                                                                            var other_Sections_ii1_months = other_Sections_ii1 * yearly_months;
                                                                                            if (prequisite_info[33]['status'] == "0" && prequisite_info[33]['file'] != "") {
                                                                                                var value_other_Sections_ii1 = document.getElementById("other_Sections_ii1").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_ii1 = document.getElementById("other_Sections_ii1").innerHTML = prequisite_info[33]['value'];
                                                                                            } else {
                                                                                                var value_other_Sections_ii1 = document.getElementById("other_Sections_ii1").innerHTML = other_Sections_ii1_months;
                                                                                            }

                                                                                            var other_Sections_ii2 = prequisite_info[34]['value'];
                                                                                            var other_Sections_ii2_months = other_Sections_ii2 * yearly_months;
                                                                                            if (prequisite_info[34]['status'] == "0" && prequisite_info[34]['file'] != "") {
                                                                                                var value_other_Sections_ii2 = document.getElementById("other_Sections_ii2").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_ii2 = document.getElementById("other_Sections_ii2").innerHTML = prequisite_info[34]['value'];
                                                                                            } else {
                                                                                                var value_other_Sections_ii2 = document.getElementById("other_Sections_ii2").innerHTML = other_Sections_ii2_months;
                                                                                            }

                                                                                            var other_Sections_ii3 = prequisite_info[35]['value'];
                                                                                            var other_Sections_ii3_months = other_Sections_ii3 * yearly_months;
                                                                                            if (prequisite_info[35]['status'] == "0" && prequisite_info[35]['file'] != "") {
                                                                                                var value_other_Sections_ii3 = document.getElementById("other_Sections_ii3").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_ii3 = document.getElementById("other_Sections_ii3").innerHTML = prequisite_info[35]['value'];
                                                                                            } else {
                                                                                                var value_other_Sections_ii3 = document.getElementById("other_Sections_ii3").innerHTML = other_Sections_ii3_months; //Other Sections (ii)
                                                                                            }




                                                                                            $("#secbt2").val(prequisite_info[32]['value']);
                                                                                            $("#secb21").val(prequisite_info[33]['value']);
                                                                                            $("#secb22").val(prequisite_info[34]['value']);
                                                                                            $("#secb23").val(prequisite_info[35]['value']);
                                                                                            if (prequisite_info[33]['status'] == "0" && prequisite_info[33]['file'] != "" && prequisite_info[34]['status'] == "0" && prequisite_info[34]['file'] != "") {
                                                                                                var total_othersection_ii = +value_other_Sections_ii1 + +value_other_Sections_ii2 + +value_other_Sections_ii3;
                                                                                            } else {
                                                                                                var total_othersection_ii = +value_other_Sections_ii1 + +value_other_Sections_ii2 + +value_other_Sections_ii3;
                                                                                            }
                                                                                            $("#ot_section_header3 ").html(prequisite_info[36]['value']); //Other Sections (iii) 

                                                                                            var other_Sections_iii1 = prequisite_info[37]['value'];
                                                                                            var other_Sections_iii1_months = other_Sections_iii1 * yearly_months;
                                                                                            if (prequisite_info[37]['status'] == "0" && prequisite_info[37]['file'] != "") {
                                                                                                var value_other_Sections_iii1 = document.getElementById("other_Sections_iii1").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_iii1 = document.getElementById("other_Sections_iii1").innerHTML = prequisite_info[37]['value'];
                                                                                            } else {
                                                                                                $("#other_Sections_iii1 ").html(prequisite_info[37]['value']); //Other Sections (iii)
                                                                                                var value_other_Sections_iii1 = document.getElementById("other_Sections_iii1").innerHTML = other_Sections_iii1_months;
                                                                                            }

                                                                                            var other_Sections_iii2 = prequisite_info[38]['value'];
                                                                                            var other_Sections_iii2_months = other_Sections_iii2 * yearly_months;
                                                                                            if (prequisite_info[38]['status'] == "0" && prequisite_info[38]['file'] != "") {
                                                                                                //                                                                        $("#other_Sections_iii2").html(0);
                                                                                                var value_other_Sections_iii2 = document.getElementById("other_Sections_iii2").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_iii2 = document.getElementById("other_Sections_iii2").innerHTML = prequisite_info[38]['value'];
                                                                                            } else {
                                                                                                //                                                                        $("#other_Sections_iii2 ").html(prequisite_info[38]['value']); //Other Sections (iii)
                                                                                                var value_other_Sections_iii2 = document.getElementById("other_Sections_iii2").innerHTML = other_Sections_iii2_months;
                                                                                            }

                                                                                            var other_Sections_iii3 = prequisite_info[39]['value'];
                                                                                            var other_Sections_iii3_months = other_Sections_iii3 * yearly_months;
                                                                                            if (prequisite_info[39]['status'] == "0" && prequisite_info[39]['file'] != "") {
                                                                                                //                                                                        $("#other_Sections_iii3").html(0);
                                                                                                var value_other_Sections_iii3 = document.getElementById("other_Sections_iii3").innerHTML = 0;
                                                                                            } else if (yearly_months === 0) {
                                                                                                var value_other_Sections_iii3 = document.getElementById("other_Sections_iii3").innerHTML = prequisite_info[39]['value'];
                                                                                            } else {
                                                                                                //                                                                        $("#other_Sections_iii3 ").html(prequisite_info[39]['value']); //Other Sections (iii)
                                                                                                var value_other_Sections_iii3 = document.getElementById("other_Sections_iii3").innerHTML = other_Sections_iii3_months;
                                                                                            }



                                                                                            $("#secbt3").val(prequisite_info[36]['value']);
                                                                                            $("#secb31").val(prequisite_info[37]['value']);
                                                                                            $("#secb32").val(prequisite_info[38]['value']);
                                                                                            $("#secb33").val(prequisite_info[39]['value']);
                                                                                            if (prequisite_info[37]['status'] == "0" && prequisite_info[37]['file'] != "" && prequisite_info[38]['status'] == "0" && prequisite_info[38]['file'] != "") {
                                                                                                var total_othersection_iii = +value_other_Sections_iii1 + +value_other_Sections_iii2 + +value_other_Sections_iii3;
                                                                                            } else {
                                                                                                var total_othersection_iii = +value_other_Sections_iii1 + +value_other_Sections_iii2 + +value_other_Sections_iii3;
                                                                                            }
                                                                                            //10. Aggregate of deductible amount under chapter VI-A
                                                                                            var aggregaet_VIA = total_othersection_i + total_othersection_ii + total_othersection_iii;
                                                                                            $("#aggregate_deductible_amt3").html(aggregaet_VIA);
                                                                                            //11. Total Income (8-10 )
                                                                                            var total_income_8to10 = gross_total_income - aggregaet_VIA - provident_value;
                                                                                            $("#total_income8to10_3").html(Math.abs(total_income_8to10));




                                                                                            //(d)Section 80CCD (1B)
                                                                                            var section_80ccdb = prequisite_info[40]['value'];
                                                                                            $("#sec41").val(section_80ccdb);
                                                                                            // var section_80ccdb_months = section_80ccdb * yearly_months;
                                                                                            if (prequisite_info[40]['status'] == "0" && prequisite_info[40]['file'] != "") {
                                                                                                //                                                                        $("#other_Sections_iii3").html(0);
                                                                                                var value_section_80ccdb = document.getElementById("section_80ccd_b1").innerHTML = 0;
                                                                                            } else {
                                                                                                //                                                                        $("#other_Sections_iii3 ").html(prequisite_info[39]['value']); //Other Sections (iii)
                                                                                                var value_section_80ccdb = document.getElementById("section_80ccd_b1").innerHTML = section_80ccdb;
                                                                                            }

                                                                                            var valuesection_80ccd_b2 = document.getElementById("section_80ccd_b2").innerHTML = value_section_80ccdb;

                                                                                                                                                                                    var section_80ccd_b2 = prequisite_info[41]['value'];
                                                                                                                                                                                    if (prequisite_info[41]['status'] == "0" && prequisite_info[41]['file'] != "") {
                                                                                                                                                                                       var value_section_80ccd_b2 = document.getElementById("section_80ccd_b2").innerHTML = 0;
                                                                                                                                                                                    } else if (yearly_months === 0 && section_80ccd_b2 === '') {
                                                                                                                                                                                        var addition1_2 = +value_section_80ccdb + +section_80ccd_b2;
                                                                                                                                                                                      var valuesection_80ccd_b2 = document.getElementById("section_80ccd_b2").innerHTML = addition1_2;
                                                                                                                                                                                    } else {
                                                                                                                                                                                        var valuesection_80ccd_b2 = document.getElementById("section_80ccd_b2").innerHTML = section_80ccd_b2;
                                                                                                                                                                                   }



                                                                                            if (valuesection_80ccd_b2 > 50000) {
                                                                                                var value_section_80ccd_b2_total = document.getElementById("section_80ccd_b3").innerHTML = 50000;
                                                                                            } else {
                                                                                                var value_section_80ccd_b2_total = document.getElementById("section_80ccd_b3").innerHTML = valuesection_80ccd_b2;
                                                                                            }

		
                                                                                            //12. Tax on total Income
																						
                                                                                           // $("#taxon_total_income3").html(Income_tax);
																							get_incom_tax(total_income_8to10).then(function (result_p){
																								
																							
																								var tax_total_income12 = document.getElementById("taxon_total_income3").innerHTML = result_p;
																								
																								
																								
																							//var tax_total_income12 = $("#taxon_total_income3").html();
																									//	alert(tax_total_income12);
                                                                                            //13. Rebate U/S 87a
                                                                                            if (tax_total_income12 < 12500) {
                                                                                                var value_rebate_87a = document.getElementById("reabate_us_87a3").innerHTML = tax_total_income12;
                                                                                            } else if(tax_total_income12 == 12500) {
                                                                                                var value_rebate_87a = document.getElementById("reabate_us_87a3").innerHTML = 12500;
                                                                                            }else{
																								 var value_rebate_87a = document.getElementById("reabate_us_87a3").innerHTML = 0;
																							}

                                                                                            //14. Tax Payable on total income (12-13)
                                                                                            var tax_payable_total_income = tax_total_income12 - value_rebate_87a;
																							
																							if(tax_payable_total_income >0)
																								{
                                                                                            $("#tax_payable_ttl_income3").html(tax_payable_total_income);
																							 var education_healt_css = (tax_payable_total_income) * (4 / 100);
                                                                                            $("#education_health3").html(Math.abs(education_healt_css));

																								}else{
																									  $("#tax_payable_ttl_income3").html(0);
																									  var education_healt_css = 0;
																									  var tax_payable_total_income = 0;
																									   $("#education_health3").html(0);
																								}
                                                                                            //15.Education & Health Cess 4%

                                                                                           
                                                                                            //16. Tax payable (14+15)
                                                                                            var tax_payable1415 = tax_payable_total_income + education_healt_css;
																							
                                                                                            $("#tax_payable_14_15_3").html((tax_payable1415));

                                                                                            //17. Relife Under Section 89 (attach details)
                                                                                            var Relife_under_section89 = prequisite_info[42]['value'];
                                                                                            var value_Relife_under_section89 = document.getElementById("relief_section89_3").innerHTML = Relife_under_section89;

                                                                                            //18. Tax payable (16-17)

                                                                                            var tax_payble_16_17 = (tax_payable1415 - value_Relife_under_section89);
																							if(tax_payble_16_17 > 0)
																							{
																								 $("#tax_payable_1617_3").html((tax_payble_16_17));
																							}else{
																								 $("#tax_payable_1617_3").html((0));
																							}
                                                                                           

                                                                                            //19.Tax Deducted at source U/S 192
                                                                                            var tax_deducted_atsource = prequisite_info[43]['value'];
                                                                                            var value_tax_deducted_atsource =paid_tax;

                                                                                            //20. Tax payable / refundable (17-18)
                                                                                            var tax_payable_refundable = (tax_payble_16_17 - value_tax_deducted_atsource);
                                                                                            $("#tax_payble_refundable3").html((tax_payable_refundable));
																							
																							$("#sec19").val((tax_deducted_atsource));
                                                                                            $("#sec17").val((Relife_under_section89));
																							});
																							 
                                                                                            
																							
																							
																							
																							
                                                                                        } else {
                                                                                        }
                                                                                    },
                                                                                });
                                                                            }
                                                                            $(document).on("change", ".qty1", function () {
                                                                                var sum = 0;
                                                                                $(".qty1").each(function () {
                                                                                    sum += +$(this).val();
                                                                                });
                                                                                if (sum > 150000)
                                                                                {
                                                                                    alert("Sum of Sections 80C,80CC and 80CCD values Should less than or equal to 1.5 lakh");
                                                                                    $(".qty1").val("");
                                                                                } else {

                                                                                }
                                                                            });
                                                                            $(document).on("change", ".qty2", function () {
                                                                                var sum = 0;
                                                                                $(".qty2").each(function () {
                                                                                    sum += +$(this).val();
                                                                                });
                                                                                if (sum > 150000)
                                                                                {
                                                                                    alert("Less:Loss From House Properties value Should less than or equal to 1.5 lakh");
                                                                                    $(".qty2").val("");
                                                                                } else {

                                                                                }
                                                                            });


                                                                            function get_status_deatil()
                                                                            {
                                                                                var emp_id = document.getElementById('emp_id').value;
                                                                                var year_id = document.getElementById('select_year').value;
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "<?= base_url("Form16/get_status") ?>",
                                                                                    dataType: "json",
                                                                                    data: {user_id: emp_id, year_id, year_id},
                                                                                    success: function (result) {
                                                                                        var data = result.result1;
                                                                                        if (result.code == 200) {
                                                                                            var arr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 13, 16, 20, 24, 28, 32, 36, 40, 42, 43];  //array of files
                                                                                            var count = 1;
                                                                                            for (var i = 0; i < 19; i++)
                                                                                            {

                                                                                                var num = arr[i];
                                                                                                var sts = data[num]['status'];
                                                                                                var file = data[num]['file'];
                                                                                                if (file == '')
                                                                                                {
                                                                                                    $("#sts" + count).html("");
                                                                                                } else {
                                                                                                    $("#select" + count).val("2");
                                                                                                    $("#myfile" + count).show();

                                                                                                    $("#myfile" + count + "a").val(file);

                                                                                                    if (sts == 0)
                                                                                                    {
                                                                                                        $("#sts" + count).html("Not Approve");
                                                                                                        $("#sts" + count).append("<input type='hidden' id='u_status" + count + "' name='u_status" + count + "' value='" + sts + "'>");
                                                                                                        $("#sts" + count).append("<a href='<?= base_url(); ?>uploads/" + file + "' download><button type='button' class='btn btn-link'><i class='fa fa-download'></i></button></a>");
                                                                                                        var user_type = document.getElementById('user_type').value;
                                                                                                        if (user_type == 5)
                                                                                                        {
                                                                                                            $("#sts" + count).append("<button type='button' class='btn btn-link btn-success' onclick='accept_file(\"" + file + "\")'><i class='fa fa-check' style='color:green' ></i></button>");
                                                                                                        }

                                                                                                    } else {
                                                                                                        $("#sts" + count).html("Approve");
                                                                                                        $("#sts" + count).append("<input type='hidden' id='u_status" + count + "' name='u_status" + count + "' value='" + sts + "'>");
                                                                                                        $("#sts" + count).append("<a href='<?= base_url(); ?>uploads/" + file + "' download><button type='button' class='btn btn-link'><i class='fa fa-download'></i></button></a>");
                                                                                                    }

                                                                                                }
                                                                                                count++;
                                                                                            }



                                                                                        } else {
                                                                                            for (var i = 1; i <= 19; i++)
                                                                                            {
                                                                                                $("#sts1" + i).html(" ");
                                                                                                $("#sts" + i).append("<input type='hidden' id='u_status" + i + "' name='u_status" + i + "' value='0'>");
                                                                                            }
                                                                                        }
                                                                                    },
                                                                                });
                                                                            }

                                                                            function Export() {
                                                                                html2canvas(document.getElementById('pdf_form_16'), {
                                                                                    onrendered: function (canvas) {
                                                                                        var data = canvas.toDataURL();
                                                                                        var docDefinition = {
                                                                                            content: [{
                                                                                                    image: data,
                                                                                                    width: 500,
                                                                                                    height: 760,
                                                                                                }]
                                                                                        };
                                                                                        pdfMake.createPdf(docDefinition).download("Form16.pdf");
                                                                                    }
                                                                                });
                                                                            }



                                                                </script>