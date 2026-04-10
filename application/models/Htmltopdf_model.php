<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Htmltopdf_model extends CI_Model {

    function fetch() {
        $this->db->order_by('user_id', 'DESC');
        return $this->db->get('user_header_all');
    }

    function fetch_single_details($userid, $year, $month) {

		$leave_days = 0;
        $this->db->where('user_id', $userid);
        $data = $this->db->get('user_header_all');
        $data_desig = $data->row();
        $desig_id = $data_desig->designation_id;
        $probEndDate=$data_desig->probation_period_end_date;
        $total_leave_available=$data_desig->total_leave_available;
        $this->db->where('designation_id', $desig_id);
        $data_designation = $this->db->get('designation_header_all');
        $data_desig_row = $data_designation->row();
        $designation_name = $data_desig_row->designation_name;
        $firm_id = $data_desig->firm_id;
        $this->db->where('firm_id', $firm_id);
        $data1 = $this->db->get('partner_header_all');
        $data_firm = $data1->row();
        $firm_name = $data_firm->firm_name;
        $firm_address = $data_firm->firm_address;
        $firm_logo = $data_firm->firm_logo;

        //present days
        $qr1 = $this->db->query("select count(*) as cnt from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status IN (0,4,5) AND Year(date)='$year'")->row();
        $present_days = $qr1->cnt;
		if($present_days == 0){
			$qq=$this->db->query("select * from manual_staff_attendance where user_id='$userid' AND month='$month'  AND year='$year'")->row();
		if($this->db->affected_rows() > 0){
			$late_days=$qq->late;
			$absent_days=$qq->absent_days;
			$present_days=$qq->present_days;
			$leave_days=$qq->leave_days;
		}
		}else{
			 $qr4 = $this->db->query("select count(*) as cnt_l from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status = 1 AND Year(date)='$year'")->row();
        $leave_days = $qr4->cnt_l;
        //absent days
        $qrab = $this->db->query("select count(*) as absent from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status IN (3) AND Year(date)='$year'")->row();

		$absent_days = $qrab->absent;
        //late days
        $qrlt = $this->db->query("select count(*) as late from t_attendance_staff where user_id='$userid' AND Month(date)='$month' AND status IN (5) AND Year(date)='$year'")->row();
        $late_days = $qrlt->late;
		}
        //total days in month
        $month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        //weekoff
        $qr2 = $this->db->query("select count(*) as weekoff from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=0  AND Year(date)='$year'  and 
        (alternate_id is null or alternate_id in (select alternate_id from alternate_holiday_master where user_id='".$userid."'))")->row();
        $weekoff = $qr2->weekoff;
        //holidays
        $qr3 = $this->db->query("select count(*) as holidays from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=1  AND Year(date)='$year'")->row();
        $holidays = $qr3->holidays;
        $output = '';
        //leave days
       $present_days=$present_days;
        //salary details
        $sal_types = array();
        $sal_types_std = array();
        $sal_types_amt = array();
        $qra = $this->db->query("select salary_type,std_amt,amt from t_salary_staff where user_id='$userid' AND month='$month'AND std_amt !='' AND amt !='' AND category IN(1,7,3)AND year='$year'")->result();
        foreach ($qra as $rw) {
            $sal_types[] = $rw->salary_type;
            $sal_types_std[] = $rw->std_amt;
            $sal_types_amt[] = $rw->amt;
        }
        //Deduction details
        $deduction_types = array();
        $deduction_std = array();
        $deduction_amt = array();
        $qra1 = $this->db->query("select salary_type,std_amt,amt from t_salary_staff where user_id='$userid' AND month='$month'  AND amt !='' AND category IN(2,4,6,8)AND year='$year'")->result();
        //print_r($qra1);die;
        foreach ($qra1 as $rw) {

            $deduction_types[] = $rw->salary_type;
            $deduction_std[] = $rw->std_amt;
            $deduction_amt[] = $rw->amt;
        }
        foreach ($data->result() as $row) {
           // print_r($row);die;

            if ($row->shift_applicable == 1) {
                $shift_applicable1 = "Yes";
            } else {
                $shift_applicable1 = "No";
            }

            if ($row->gender == 1) {
                $gender1 = "Male";
            } else {
                $gender1 = "Female";
            }
            //Salary Sum type1
            $qr11 = $this->db->query("select sum(amt) as type1 from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
            $type1 = $qr11->type1;

			$leave_deduction = $this->db->query("select amt from t_salary_staff where category=4 and user_id='$userid' AND month='$month' AND category !=0 AND year=$year")->row()->amt;
			$sandwich_deduction = $this->db->query("select amt from t_salary_staff where category=8 and user_id='$userid' AND month='$month' AND category !=0 AND year=$year")->row()->amt;
            //Salary Lop
            $stdamt = $this->db->query("select sum(std_amt) as std_amt from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
            $std_amt = $stdamt->std_amt;
            $deduction_types[] = "Loss Of Pay";
            $total_leave_cnt=0;
            $resultObject=$this->db->query("select count(id) as leave_cnt from employee_attendance_leave where leave_status=2 and activity_status=0 and user_id='".$userid."' and MONTH(date)=".$month." and YEAR(date)=".$year);
            if($this->db->affected_rows()>0)
            {
                $resultData=$resultObject->row();
                $total_leave_cnt=$resultData->leave_cnt;
            }
            $perDaySalary=$std_amt/$month_days;
            $total_leave_amt=$perDaySalary*$total_leave_cnt;
//                         print_r($deduction_amt);exit();
            $trimmedDate = trim($probEndDate);

            if (strtotime($trimmedDate) !== false) {
                // Parse the date
                $endDate = strtotime($trimmedDate);
                $currentDate = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

                $currentDate = strtotime($currentDate); // Get the current date

                // Compare dates
                if ($endDate < $currentDate) {

                    $deduction_amt[] = $std_amt - ($type1  + $sandwich_deduction);
                    $deduction_std[] = $std_amt - $type1 ;
//                    echo "Probation period is over.";
                } else {
                    $deduction_amt[] = $std_amt - ($type1  + $sandwich_deduction)  - $total_leave_amt ;
                    $deduction_std[] = $std_amt - $type1  - $total_leave_amt;
//                    echo "Probation period is not over.";
                }
            }
            $today = date('Y-m-d');

            if (strtotime($probEndDate) < strtotime($today)) {
             //   echo "✅ Probation has ended.";
            //    $total_leave_available=$total_leave_available + (1.75*1);
            }

//             print_r($deduction_amt);exit();
            // $array1 = array('Total month days', 'Present Days', 'Week Off', 'Holidays', 'Leaves');
            //$array2 = array($month_days, $present_days, $weekoff, $holidays, $leave_days);
            $sal = count($sal_types);
            $ded = count($deduction_amt);
            //$arr = count($array1);
			$ar=array("P","WO","Holidays","Leaves","Pay Days");
			$aap=count($ar);
            $count = max($sal, $ded,$aap);
            $month_name = date("F", mktime(0, 0, 0, $month, 10));

			$doj = '';
//			echo $row->date_of_joining;die;
			if($row->date_of_joining != '0000-00-00 00:00:00'){
				$doj = date('d/m/Y',strtotime($row->date_of_joining));
			}
			 // var_dump($deduction_types);
            $displayYear = (int) $year;
            $output .= '
            <style>
			* {
  box-sizing: border-box;
}



/* Create two equal columns that floats next to each other */
.column {
 
  width: 50%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
		
			</style>

<div id="JSFiddle">

<header>
	<div class="row">
		<div style="margin-top:30px;font-size:13px;"><img src="https://'.base_url().'/assets/RKCA_Brand_Header.png" height="12px"> 
		
		</div>
		<div style="text-align:right;"><img src="'.$firm_logo.'" height="60px" style="margin-right:30px;margin-top:-16px;"></div>
	</div>
</header>
<h2 align="center">PAYSLIP</h2>
<table class="table" style="border :1px solid black">
<tbody>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Employee Name</b></td>
<td id="userNameD" style="border :1px solid black">' . $row->user_name . '</td>
<td style="border :1px solid black"><b>Code</b></td>
<td style="border :1px solid black">'.$row->user_id.'</td>
</tr>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Father/husband Name</b></td>
<td style="border :1px solid black">' . $row->spouse_name . '</td>
<td style="border :1px solid black"><b>Designation</b></td>
<td style="border :1px solid black">' . $designation_name . '</td>
</tr>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Department</b></td>
<td style="border :1px solid black">' . $row->department_name . '</td>
<td style="border :1px solid black"><b>UAN</b></td>
<td style="border :1px solid black">' . $row->UAN_no . '</td>
</tr>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Date Of Joining</b></td>
<td style="border :1px solid black">' . $doj . '</td>
<td style="border :1px solid black"><b>ESI Ac/no:</b></td>
<td style="border :1px solid black"></td>
</tr>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Shift</b></td>
<td style="border :1px solid black">' . $shift_applicable1 . '</td>
<td style="border :1px solid black"><b>PAN:</b></td>
<td style="border :1px solid black">' . $row->pan_no . '</td>
</tr>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Gender</b></td>
<td style="border :1px solid black">' . $gender1 . '</td>
<td style="border :1px solid black"><b>Adhar No:</b></td>
<td style="border :1px solid black">' . $row->adhar_no . '</td>
</tr>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Date Of Birth</b></td>
<td style="border :1px solid black">' . date('d/m/Y',strtotime($row->date_of_birth)) . '</td>
<td style="border :1px solid black"><b>Location:</b></td>
<td style="border :1px solid black">' . $row->address . '</td>
</tr>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Bank Name</b></td>
<td style="border :1px solid black">' . $row->bank_name . '</td>
<td style="border :1px solid black"><b>Bank A/c No.:</b></td>
<td style="border :1px solid black">' . $row->account_no . '</td>
</tr>
<tr  style="border :1px solid black">
<td style="border :1px solid black"><b>Year</b></td>
<td style="border :1px solid black">' . $displayYear . '</td>
<td style="border :1px solid black"><b>Month</b></td>
<td style="border :1px solid black">'.$month_name.'</td>
</tr>
</tbody>
</table>

<div class="row">
<div class="col-md-2" style="position:fixed ;margin-top: 40px;">

</div>
<div class="col-md-2" style="position: fixed;">

</div>
</div>
  ';
        }
//die;
        $output .= '<style>


table {  
            font-family: arial, sans-serif;  
            border-collapse: collapse;  
            width: 100%;  
        }
         td, th {  
            text-align: left;  
            padding: 8px;  
        }  
</style>

<table class="table " style="position: relative;margin-top: 60px;">
';
        $output .= '
        <tr   style="border: 1px solid black;">
		<thead style="background-color:#595959">
		<th style="border: 1px solid black; color:white" colspan="2">Attendance</th>
    <th style="border: 1px solid black; color:white" width="30%">Heads of income</th>
    <th style="border: 1px solid black; color:white">Gross</th>
    <th style="border: 1px solid black; color:white">Earnings</th>
	<th style="border: 1px solid black; color:white" colspan="2">Deduction</th>
	</thead>
  </tr>';
  $new=array($present_days,$weekoff,$holidays,$leave_days,($month_days-$leave_days));
        for ($k = 0; $k < $count; $k++) {
            if ($sal > $k) {
                $sal_typ = $sal_types[$k];
                $sal_std = round($sal_types_std[$k]);
                $sal_amt = round($sal_types_amt[$k]);
            } else {
                $sal_typ = '';
                $sal_std = '';
                $sal_amt = '';
            }
            if ($ded > $k) {
                $ded_typ = $deduction_types[$k];
                $ded_std = round($deduction_std[$k]);
                $ded_amt = round($deduction_amt[$k]);
            } else {
                $ded_typ = '';
                $ded_std = '';
                $ded_amt = '';
            }
            $valueArr = 'Others';
            $newVal = '-';
            if(array_key_exists($k, $ar)){
                $valueArr = $ar[$k];
            }
            if(array_key_exists($k, $new)){
                $newVal = $new[$k];
            }

            $output .= '<tr class="separated" style="border: 1px  black;">
     <td style="border: 1px solid black;">'.$valueArr.'</td>
    <td style="border: 1px solid black;">'.$newVal.'</td>
    <td style="border: 1px solid black;">' . $sal_typ . '</td>
    <td style="border: 1px solid black;">' . ($sal_std) . '</td>
    <td style="border: 1px solid black;">' . ($sal_amt) . '</td>
    <td style="border: 1px solid black;">' . $ded_typ . '</td>
    <td style="border: 1px solid black;">' . ($ded_amt) . '</td>
  </tr>';
        }
        $output .= '<tr class="separated" style="border: 1px  black;">
    
    <td style="border: 1px solid black;"width="15%" height="100px"></td>
    <td style="border: 1px solid black;"></td>
    <td style="border: 1px solid black;"></td>
	<td style="border: 1px solid black;"></td>
    <td style="border: 1px solid black;"></td>
    <td style="border: 1px solid black;"></td>
    <td style="border: 1px solid black;"></td>
  </tr>';
        $output .= '<tr class="separated" style="border: 1px  black;">
     <td style="border: 1px solid black;">Month Days</td>
    <td style="border: 1px solid black;">' . $month_days . '</td>
    <td style="border: 1px solid black;"><b>Total</b></td>
    <td style="border: 1px solid black;">' . round(array_sum($sal_types_std)) . '</td>
    <td style="border: 1px solid black;">' . round(array_sum($sal_types_amt)) . '</td>
    <td style="border: 1px solid black;"><b>Total</b></td>
    <td style="border: 1px solid black;">' . round(array_sum($deduction_amt)) . '</td>
  </tr>';

        $a = array_sum($sal_types_std);
        $b = array_sum($deduction_amt);
        $c = $a - $b;

        $output .= '<tr class="separated" style="border: 1px  black;">
    <td style="border: 1px solid black;"></td>
    <td style="border: 1px solid black;"></td>
    <td style="border: 1px solid black;"><b>Net Salary Payable</b></td>
    <td style="border: 1px solid black;"></td>
    <td style="border: 1px solid black;">' . round($c) . '</td>
    <td style="border: 1px solid black;"></td>
    <td style="border: 1px solid black;"></td>
  </tr>';
        $output .= '</table></div>';

        if($c < 0){
            $c = 0;
        }
		$number = round($c);

   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {

        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " .
          $words[$point = $point % 10] : '';
  $rupees_data= $result . "Rupees  ";

        $output .= "<br><div class='row' style='font-size:19px;'>Rupees: &nbsp; <span style='border-bottom:1px solid black;font-size:17px;'>".$rupees_data."</span></div><footer><br>
    <div style='text-align:center;'>
	<b>".$firm_name."</b>
	<p>".$firm_address."</p>
	</div>
</footer>";

        return $output;
    }

	function getIndianCurrency(float $number){

	}

}


/* <table class="table " style="position: relative;margin-top: 60px;">
<tr   style="border: 1px solid black;">
<th style="border: 1px solid black;" width="15%">Present days</th>
    <th style="border: 1px solid black;">Absent Days</th>
    <th style="border: 1px solid black;">Week Off</th>
    <th style="border: 1px solid black;">Holidays</th>
    <th style="border: 1px solid black;">Leave</th>
    <th style="border: 1px solid black;">Total Month Days</th>
    <th style="border: 1px solid black;">Late Days</th>
</tr>
<tr class="separated" style="border: 1px  black;">

    <td style="border: 1px solid black;">' . $present_days . '</td>
    <td style="border: 1px solid black;">' . $absent_days . '</td>
    <td style="border: 1px solid black;">' . $weekoff . '</td>
    <td style="border: 1px solid black;">' . $holidays . '</td>
    <td style="border: 1px solid black;">' . $leave_days . '</td>
    <td style="border: 1px solid black;">' . $month_days . '</td>
    <td style="border: 1px solid black;">' . $late_days . '</td>
  </tr>
<table> */

?>
