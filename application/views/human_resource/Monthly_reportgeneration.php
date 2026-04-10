<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');

$data['session_data'] = $session_data;
$user_id = ($session_data['emp_id']);
$user_type = ($session_data['user_type']);


$page_name = 'View Calender Holiday';
$page_name2 = 'View Due Date List';
//var_dump($firm_name_dd);
//echo $firm_id_new;
?>

<style>
    span.error {
        color: red;
    }
    td {
        text-align: center;
    }
    .tabbable-custom>.nav-tabs>li>a {
        margin-right: 0;
        color: black !important;}
    .tabbable-custom>.nav-tabs>li {
        margin-right: 2px;
        background-color: #7cabb7 !important;
        border-top: 2px solid #f9f3f3b5;}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<div class="page-fixed-main-content">
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





            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase">Generate Monthly/Yearly Report</span>
                    </div>

                </div>

                <div class="portlet-body">

                    <div class="row">
					<div class="col-md-12">
						<div class="col-md-3">
							<select class="form-control" id="select_type" name="select_type" onchange="getFinancialYear(this.value)">
								<option>Select Type</option>
								<option value="M">Monthly</option>
								<option value="Y">Yearly</option>
							</select>
						</div>
						<div class="col-md-3 yearly" >
							<select class="form-control" id="select_f_year" name="select_f_year" onchange="generateMonthlyReport();">
								<option>Select Financial Year</option>
							</select>
						</div>
					 <div class="col-md-3 monthly" >
					<select class="form-control" id="select_year" name="select_year" onchange="get_months();">
						<option>Select Year</option>
					</select>
					</div>
				<div class="col-md-3 monthly">
					<select class="form-control" id="month" name="month" onchange="generateMonthlyReport()">
						<option>Select Month</option>
					</select>
				</div>
				<div class="col-md-3">
<!--					<button class="btn btn-primary" id="generateReport" onclick="generateXls()" style="display:none">Generate Excel</button>-->
					<button class="btn btn-primary" id="exportButton" style="display:none">Generate Excel</button>
				</div>
					</div>
					</div>
                    <div class="row">

					<div class="col-md-12" id="table_data">

					</div>
					</div>
                </div>

                <?php $this->load->view('human_resource/footer'); ?>
            </div>

        </div>



    </div>
  <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    ​<script>
                            $(document).ready(function () {
								$('.monthly').hide()
								$('.yearly').hide()
								//generateMonthlyReport();

								$('#select_year').each(function() {

   var year = (new Date()).getFullYear();
  var current = year;
  if(current == "2020"){
      $(this).append('<option  value="' + (year) + '">' + (year) + '</option>');
    }
  else{

          year -= 1;
      for (var i = 0; i < 2; i++) {
    if ((year+i) == current){
      $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
      }
    else {
      $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');

  }
  }


  }
									get_months();
  });

                            });
							function	getFinancialYear(value){
								$("#table_data").hide();
								if(value =="M"){
									$('.monthly').show();
									$('.yearly').hide();
								}else{
									$('.monthly').hide();
									$('.yearly').show();
									//select_f_year
									const startYear = 2020; // Change this to the year you want to start from

									// Get the current year
									const currentYear = new Date().getFullYear();

									// Create an array to hold the financial years
									let years = [];

									// Generate the years from startYear to currentYear
									for (let year = startYear; year <= currentYear; year++) {
										years.push(year);
									}

									// Get the dropdown element
									const $dropdown = $('#select_f_year');

									$.each(years, function(index, year) {
										// Create an option element
										let $option = $('<option>', {
											value: year,
											text: `FY ${year}-${year + 1}`
										});

										// Set the current year as selected
										if (year === currentYear) {
											$option.attr('selected', 'selected');
										}

										// Append the option to the dropdown
										$dropdown.append($option);

									});
									generateMonthlyReport();
								}


							}
							function get_months(){
								var year = $("#select_year").val();
								var d = new Date();
								var curr = d.getMonth() + 1;
								var currY = d.getFullYear();
								var month = ["January", "February", "March", "April", "May", "June", "July",
									"August", "September", "October", "November", "December"];
									$("#month").html("<option>Select Month</option>");
									for(var i=0;i< month.length;i++)
									{
										value=i+1;
										 $("#month").append(new Option(month[i], (value)));
									}
							}

							function generateMonthlyReport()
							{
								var year = $("#select_year").val();
								var month = $("#month").val();
								var select_type = $("#select_type").val();
								var select_f_year = $("#select_f_year").val();
								$.ajax({
                                    type: "POST",
                                    url: "<?= base_url("ServiceRequestController/generateMonthlyReport") ?>",
                                    dataType: "json",
									data:{year:year,month:month,select_type:select_type,select_f_year:select_f_year},
                                    async: false,
                                    cache: false,
                                    success: function (result) {
                                        var data = result.data;
                                        if (result.status == 200) {
                                        	$("#table_data").show();
                                          $("#table_data").html(data);
                                          $("#exportButton").show();
                                        } else {
											$("#table_data").hide();
                                            $("#table_data").html(data);
											 $("#exportButton").hide();
                                        }
                                    },
                                });
							}

							function generateXls1(){
								var year = $("#select_year").val();
								var month = $("#month").val();
								$.ajax({
                                    type: "POST",
                                    url: "<?= base_url("ServiceRequestController/generateXls") ?>",
                                    dataType: "json",
									data:{year:year,month:month},
                                    success: function (result) {

                                    },
                                });
							}

							function generateXls(){
								var year = $("#select_year").val();
								var month = $("#month").val();
								var select_type = $("#select_type").val();
								var select_f_year = $("#select_f_year").val();
								window.location.href = "<?= base_url("ServiceRequestController/generateXls") ?>?year=" + year +"&month="+month+"&select_type="+select_type+"&select_f_year="+select_f_year;
							}

							document.getElementById('exportButton').addEventListener('click', function() {
								// Get the HTML table element
								var table = document.getElementById('myTable');

								// Convert HTML table to a workbook object
								var workbook = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});

								// Generate a binary Excel file
								var excelFile = XLSX.write(workbook, {bookType: 'xlsx', type: 'array'});

								// Create a blob object and download the file
								var blob = new Blob([excelFile], {type: 'application/octet-stream'});
								var link = document.createElement('a');
								link.href = URL.createObjectURL(blob);
								link.download = 'table_data.xlsx'; // Specify the filename
								link.click();
							});
                            </script>



