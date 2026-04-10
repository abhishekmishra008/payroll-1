<?php
$this->load->view('human_resource/navigation');
defined('BASEPATH') OR exit('No direct script access allowed');
if ($session = $this->session->userdata('login_session') == '') {
//take them back to signin
    redirect(base_url() . 'login');
}
$session_data = $this->session->userdata('login_session');

$data['session_data'] = $session_data;
//$user_id = ($session_data['emp_id']);
// $user_type = ($session_data['user_type']);


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
                        <span class="caption-subject bold uppercase">Run Past Salary</span>
                    </div>

                </div>

                <div class="portlet-body">
                     <form type="POST" id="salaryForm" name="salaryForm">
					 <div class="col-md-12">
                                                                <div class="col-md-3">
                                                                    <select class="form-control" id="select_year" name="select_year" onchange="get_months();">
                                                                        <option>Select Year</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <select class="form-control" id="month" name="month" onchange="">
                                                                        <option>Select Month</option>
                                                                    </select>
                                                                </div>
																   <div class="col-md-3">
                                                                    <input type="text" class="form-control" Placeholder="Present Days" id="present_days" name="present_days"> 
                                                                </div>
																   <div class="col-md-3">
                                                                     <input type="text" class="form-control" Placeholder="Absent Days" id="absent_days" name="absent_days"> 
                                                                </div>
																<div class="col-md-3"><br>
                                                                     <input type="text" class="form-control" Placeholder="Total Leaves" id="leave" name="leave"> 
                                                                </div>
																<div class="col-md-3"> <br>
                                                                     <input type="text" class="form-control" Placeholder="Total Late Days" id="late" name="late"> 
                                                               <br>
															   </div>
																</div><br><hr>
					 <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id?>">
                        <div class="row">
					   <div class="col-md-12">
					    <div class="col-md-4">Salary Type</div>
					    <div class="col-md-4">Standard Amount</div>
					    <div class="col-md-4">Amount</div>
					   
					   </div>
					   </div>
					   <hr>
					   <div id="tab_data"> </div>
					   
					   
					   
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" > Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

                <?php $this->load->view('human_resource/footer'); ?>
            </div>

        </div>



    </div>

   
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
	
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
  });
  
  function get_months()
	{
		var year = $("#select_year").val();
		var d = new Date();
		var curr = d.getMonth() + 1;
		var currY = d.getFullYear();
		var month = ["January", "February", "March", "April", "May", "June", "July",
			"August", "September", "October", "November", "December"];
	  
		
		$.ajax({
			type: "POST",
			url: "<?= base_url("Runpayroll/get_month_list") ?>",
			dataType: "json",
			data: {year: year},
			success: function (result) {
				if (result.message == "success") {
				  var montharr=  result.montharr;
				  $("#month").html("<option>Select Month</option>");
						for(var i=0;i< montharr.length;i++)
						{
							value=montharr[i];
							
							if(currY == year)
							  {
								 if(value >= curr)
								 {
								 }else{
									 
									 $("#month").append(new Option(month[value-1], (value)));
								 }
							  }else{
								  $("#month").append(new Option(month[value-1], (value)));
							  }
							
						}
					
				} else {
				}
			}

		});
		
		//$("#month").append(new Option(month[prev], (prev + 1)));
	}
	 $(document).ready(function () {
get_all_data();

                            });
	function get_all_data(){
		var user_id=$("#user_id").val();
		$.ajax({
				type: "POST",
				url: "<?= base_url("SalaryInfoController/get_salary_types") ?>",
				dataType: "json",
				async: false,
				cache: false,
				data:{user_id:user_id},
				success: function (result) {
					var data = result.data;
					if (result.code == 200) {
						$('#tab_data').html(data);
					} else {
						$('#tab_data').html("");
					}
				},
			});
	}
	
	  $("#salaryForm").validate({//form id
                                  rules: {
                                    "select_year": {
                                        required: true
                                    },
                                    "present_days": {
                                        required: true
                                    },
									"absent_days": {
                                        required: true
                                    },
									"leave": {
                                        required: true
                                    },
									"late": {
                                        required: true
                                    },
								
                                },
                                errorElement: "span",
                                submitHandler: function (form) {
                                    $.ajax({
                                        url: '<?= base_url("SalaryInfoController/AddDataPastSalary") ?>',
                                        type: "POST",
                                        data: $("#salaryForm").serialize(),
                                        success: function (success) {
                                            success = JSON.parse(success);
                                            if (success.code === 200) {
                                                alert("Added Successfully");
                                              location.reload();
                                            } else {
                                                alert("Fail to add data");
												location.reload();
                                                //  toastr.error(success.body); //toster.error
                                            }
                                        },
                                        error: function (error) {
                                            toastr.error(success.body);
                                            console.log(error);
                                            errorNotify("something went to wrong");
                                        }
                                    });
                                }
                            }
                            );
	</script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    ​

