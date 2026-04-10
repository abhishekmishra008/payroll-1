<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';
require_once APPPATH . 'PHPExcel/PHPExcel.php';

require_once '/var/www/payroll/application/PHPExcel/PHPExcel/Writer/Abstract.php';
require_once '/var/www/payroll/application/PHPExcel/PHPExcel/Writer/HTML.php';
// require_once '/var/www/payroll/application/PHPExcel/PHPExcel/Writer/Abstract.php';
use PhpOffice\PhpSpreadsheet\Writer\Html as PHPExcel_Writer_HTML;


use Dompdf\Css\Color;
use Dompdf\Dompdf;
use Dompdf\Options;

class Excel_export extends CI_Controller {
	 public function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('db2', TRUE);
        $this->load->model('MasterModel');
        $this->load->model('customer_model');
    }
    function index() {
        $this->load->model("excel_export_model");
        $data["employee_data"] = $this->excel_export_model->fetch_data();
        $this->load->view("admin/excel_export_view", $data);

    }

    function action() {
        $this->load->model("excel_export_model");
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array("user_name", "mobile_no", "email", "state", "country");

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $employee_data = $this->excel_export_model->fetch_data();

        $excel_row = 2;

        foreach ($employee_data as $row) {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->user_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->mobile_no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->email);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->state);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->country);
            $excel_row++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Employee Data.xls"');
        $object_writer->save('php://output');
    }

	function convertdate($date){
		$date = strtotime($date);
					$d=date('g:i', $date);
					$d1=date('d', $date);
					$d= $d;
					return $d;
	}

	public function generateXls_data() {
		$session_data = $this->session->userdata('login_session');
        $user_id = $this->input->get('user_id');;
        $qr = $this->db->query("select hr_authority from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $firm_id = $res->hr_authority;
        } else {
            $firm_id = '';
        }
			$year=$this->input->get('year');
			$month=$this->input->get('month');
    	// create file name
        $fileName = 'data-'.time().'.xlsx';  
    	// load excel library
        $this->load->library('excel');
        //$listInfo = $this->export->exportList();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'D');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'TYPE');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'IN');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'OUT');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'LOCATION');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'ACTION');
       
 
       
    	$m=$month;
		$y=$year;
		//print_r($month1);exit;
		//$m=date('m');
		//$y=date('Y');
		$d=cal_days_in_month(CAL_GREGORIAN,$m,$y);
		// print_r($d);exit;
		$data="";
		for($i=1;$i<= $d;$i++)
		 {
			  $get_data = $this->db->query("select * from employee_attendance_leave where user_id='$user_id' AND month(date)='$m' AND year(date)='$y' AND day(date)='$i'")->row();
			// echo $this->db->last_query();
			// exit;
			// var_dump($get_data);	
		
			  if($this->db->affected_rows() > 0)
			  {
				//  echo $i;
				  $punch_in=$this->convertdate($get_data->punch_in);
				  $punch_out=$this->convertdate($get_data->punch_out);
				  $location=$get_data->shortinaddress;
				  if($location ==  "")
				  {
					  $location="GPS off";
					  $sts="";
				  }
				  if($get_data->punch_in != 0 || $get_data->punch_out != 0 )
				  {
					  $status="AT";
					   $btn="";
					   $sts="";
				  }
				  if($get_data->punch_in != 0 && $get_data->punch_out == 0 ){
					  $punch_out="--:--";
				  }
					if($get_data->punchin_lat == 0 && $get_data->punchin_long == 0 && $get_data->leave_status != 1)
					{
						 $status="OO";
						  if ($get_data->regular_status == 0) {
                       // $status = '<span class="badge badge-info" >Requested </span>';
					   $sts="R";
                        $btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    } else if ($get_data->regular_status == 1) {
                       // $status = '<span class="badge badge-primary" >Approved </span>';
					    $btn="A";
						$sts="A";
                       // $btn = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                    } else {
						 $btn="D";
						 $sts="D";
                       // $status = '<span class="badge badge-danger" >Denied </span>';
                        // $btn = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    }
					}else{
				if($get_data->punchin_lat != 0 && $get_data->punchin_long != 0){
					if(($this->check_location($get_data->punchin_lat,$get_data->punchin_long)==0)){
					  $status="OO";
						   if ($get_data->regular_status == 0) {
						   // $status = '<span class="badge badge-info" >Requested </span>';
						   $sts="R";
							$btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
							   <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
							}else if ($get_data->regular_status == 1) {
								$sts="A";
							   // $status = '<span class="badge badge-primary" >Approved </span>';
							   $btn="A";
							  //  $btn = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
							} else {
								$sts="D";
							   // $status = '<span class="badge badge-danger" >Denied </span>';
								$btn="D";
							   // $btn = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
							}
						}
					}else{
						$session_data = $this->session->userdata('login_session');
			  $sen_id = ($session_data['emp_id']);
			  if($sen_id == $user_id){
				$fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
                                        `user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,`leave_transaction_all`.`id`,
                                        `leave_transaction_all`.`leave_requested_on`, `leave_transaction_all`.`leave_date`,`leave_transaction_all`.`status`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`user_id`='$user_id' AND month(leave_date)='$m' AND day(leave_date)='$i' AND year(leave_date)='$y' ORDER BY leave_date DESC")->row();
					// var_dump($fetch_data);			  
			  }else{
				 $fetch_data = $this->db->query("SELECT distinct `user_header_all`.`user_id`,`user_header_all`.`designation_id`,`user_header_all`.`user_name`,
                                        `user_header_all`.`senior_user_id`,
                                        `user_header_all`.`designation_id`,`leave_transaction_all`.`leave_type`,`leave_transaction_all`.`leave_id`,`leave_transaction_all`.`id`,
                                        `leave_transaction_all`.`leave_requested_on`, `leave_transaction_all`.`leave_date`,`leave_transaction_all`.`status`
                                        FROM `leave_transaction_all`
                                        INNER JOIN `user_header_all`
                                        ON `user_header_all`.`user_id`=`leave_transaction_all`.`user_id`
                                        WHERE `leave_transaction_all`.`approved_deny_by`='$sen_id' AND `leave_transaction_all`.`user_id`='$user_id' AND month(leave_date)='$m' AND day(leave_date)='$i' AND year(leave_date)='$y' ORDER BY leave_date DESC")->row();
					// var_dump($fetch_data);			 
			  }
						
			if($this->db->affected_rows() > 0)
					{
						$punch_in="--:--";
						$location="--:--";
				$punch_out= "--:--";
				  $status="LR";
				   $btn = '<button id="sample_1_new" class="btn-info btn-simple btn blue-hoki btn-outline sbold uppercase popovers"
                    data-toggle="modal" data-target="#view_leave_details" data-container="body"
                    data-placement="left" data-trigger="hover" data-content="See Details" data-original-title="" title=""
                     data-desig_id="' . $fetch_data->designation_id . '"
                    data-leave_type1="' . $fetch_data->leave_type . '"
                    data-emp_user_id="' . $fetch_data->user_id . '"
                    data-leave_id="' . $fetch_data->leave_id . '">
                    <i class="fa fa-eye"></i>
                    </button>';
					
					 if ($fetch_data->status == '1') {
						 $sts="R";
                                                    $approve = '<a class="btn btn-link btn-icon-only  " data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(\''.$fetch_data->id.'\',\''.$fetch_data->leave_id.'\');"><i class="fa fa-check font-green"></i></a>';
                                                    $deny = '<a class="btn btn-link btn-icon-only  " data-toggle="modal" title="Deny!" name="deny_leave" id="deny_leave" data-target="#mydenyModal" data-myvalue="'.$fetch_data->id.'"><i class="fa fa-close font-red"></i></a>';
                                                } else if ($fetch_data->status == '2') {
													$approve="A";
													$sts="A";
													$deny="";
                                                  // $approve = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" ><i class="fa fa-check font-green"></i></a>';
                                                    //$deny = '<a class="btn btn-link btn-icon-only  " data-toggle="modal"title="Deny!" name="deny_leave" id="deny_leave" data-target="#mydenyModal" data-myvalue="'.$fetch_data->id.'"><i class="fa fa-close font-red"></i></a>';
                                                } else if ($fetch_data->status == '3') {
													$approve="D";
													$deny="";
													$sts="A";
                                                  //  $approve = '<a class="btn btn-link btn-icon-only  " data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" onclick="approve_leave(\''.$fetch_data->id.'\',\''.$fetch_data->leave_id.'\');"><i class="fa fa-check font-green"></i></a>';
                                                 //   $deny = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close font-red"></i></a>';
                                                } else {
													$approve="";
													$deny="";
													$sts="";
                                                    // $approve = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Approve!" name="approve_leave" id="approve_leave" ><i class="fa fa-check font-green"></i></a>';
                                                    // $deny = '<a class="btn btn-link btn-icon-only  " disabled="" data-toggle="tooltip" title="Deny!" name="deny_leave" id="deny_leave" ><i class="fa fa-close font-red"></i></a>';
                                                } 
												$btn=$approve."</br>".$deny;
					}

					}
					}
					if($get_data->missing_punchin != 0 AND $get_data->missing_punchout != 0){
						$punch_in=$this->convertdate($get_data->missing_punchin);
						 $punch_out=$this->convertdate($get_data->missing_punchout);
						 $location=$get_data->shortinaddress;
					  $status="RA";
					  if ($get_data->regular_status == 0) {
						  $sts="R";
                     //   $status = '<span class="badge badge-info" >Requested </span>';
                        $btn = '<button type="button" data-toggle="tooltip" title="Approve" onclick="action_r_rq(\'' . $get_data->id . '\',1)" class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>
                           <button type="button" data-toggle="tooltip" title="Cancel" onclick="action_r_rq(\'' . $get_data->id . '\',2)" class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    } else if ($get_data->regular_status == 1) {
						$sts="A";
                      //  $status = '<span class="badge badge-primary" >Approved </span>';
					  $btn="A";
                     //   $btn = '<button type="button" data-toggle="tooltip" title="Request Approved" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-check font-green" ></i></button>';
                    } else {
						$sts="D";
						$btn="D";
                      //  $status = '<span class="badge badge-danger" >Denied </span>';
                       // $btn = '<button type="button" data-toggle="tooltip" title="Request Denied" disabled class="btn btn-link btn-icon-only  "><i class="fa fa-close font-red" ></i></button> ';
                    }
					}
			  }else{
				  $punch_in="--:--";
				$punch_out="--:--";
				$location="--:--";
				  $status="NA";
				  $btn="";
				  $sts="";
			  }
			  
		 $session_data = $this->session->userdata('login_session');
		 $session_user_id = ($session_data['emp_id']);
		 if($user_id==$session_user_id){
			 $btn=$sts;
		 }
		  $objPHPExcel->getActiveSheet()->SetCellValue('A' . $d, $i);
         $objPHPExcel->getActiveSheet()->SetCellValue('B' . $d, $status);
         $objPHPExcel->getActiveSheet()->SetCellValue('C' . $d, $punch_in);
         $objPHPExcel->getActiveSheet()->SetCellValue('D' . $d, $punch_out);
         $objPHPExcel->getActiveSheet()->SetCellValue('E' . $d, $location);
         $objPHPExcel->getActiveSheet()->SetCellValue('F' . $d, $sts);

					
		}
   
       
        $filename = "MonthlyReport". $month."/".$year.".csv";
		header('Content-Type: application/vnd.ms-excel'); 
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0'); 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');  
		$objWriter->save('php://output'); 

    }

	/* -------- old code with Exact Time Stamp-------
    public function daily_task() {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        $this->load->model("excel_export_model");
        $this->load->library("excel");
        $employee_id=$this->input->get('employee_id');
        $date=$this->input->get('date');
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        //date
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "date");
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 1, $date);

        $table_columns = array("sr. no.", "Name", "Role", "Portal", "Task","Activity");
        $range=range(strtotime("10:00:00"),strtotime("19:00:00"),60*60);
        foreach($range as $time){
            $time= date("H:i:s",$time);
            array_push($table_columns,$time);
        }
        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 2, $field);
            $column++;
        }
        $result = $this->customer_model->get_firm_id($user_id);
		
        if ($result !== false) {
            $firm_id = $result['firm_id'];
		
        }
        if($employee_id=="" || $employee_id=="all"){
			
        	$query = $this->db->query('select user_name,user_id,(select d.designation_name from designation_header_all d where d.designation_id=u.designation_id order by id desc limit 1) as role from user_header_all u where firm_id="'.$firm_id.'" and user_type in (4) and activity_status =1');
        }
		else
		{
            $query = $this->db->query("select user_name,user_id,(select d.designation_name from designation_header_all d where d.designation_id=u.designation_id order by id desc limit 1) as role from user_header_all u where user_id='".$employee_id."' and activity_status=1");
		}

        if ($this->db->affected_rows() > 0) {
            $employee_data = $query->result();
            $excel_row = 3;
            $i=1;

            foreach ($employee_data as $row) {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $i);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->user_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->role);
				//                $taskData=$this->db->query('SELECT * FROM `employee_schedule_task` WHERE date="2024-07-30" and user_id="U_677" and status=1');
                 $taskData=$this->db->query('SELECT * FROM `employee_schedule_task` WHERE date="'.$date.'" and user_id="'.$row->user_id.'" and status=1');
              		// echo $this->db->last_query();die;
                if ($this->db->affected_rows() > 0) {
                    $taskData=$taskData->result();
				//                    print_r($taskData);die;
                    foreach($taskData as $taskRow)
                    {
                    	switch ($taskRow->activity_type) {
						    case 1:
						        $activity = 'Development';
						        break;
						    case 2:
						        $activity = 'Designing';
						        break;
						    case 3:
						        $activity = 'Bug resolutions';
						        break;
						    case 4:
						        $activity = 'Meetings/Discussion';
						        break;
						    default:
						        $activity = 'Testing';
						        break;
						}
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $taskRow->project_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $taskRow->task_title);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $activity);
                        $dash='';
                        $cl=6;
                        foreach($range as $time){
                            $time= date("H:i:s",$time);

                            if(($time==$taskRow->from_time || $time==$taskRow->to_time) || ($time>=$taskRow->from_time && $time<=$taskRow->to_time)){
                                if($taskRow->work_status==2)
                                {
                                    $dash="CAEFC8";
                                }
                                else
                                {
                                    $dash="FFFF00";
                                }

                            }
                            else
                            {
                                $dash="";
                            }

                            if($dash!="")
                            {
                                $backgroundColor = array(
                                    'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('rgb' => $dash)
                                    )
                                );

                                $cellCoordinate = PHPExcel_Cell::stringFromColumnIndex($cl) . $excel_row;
                                $object->getActiveSheet()->setCellValue($cellCoordinate, '');
                                $object->getActiveSheet()->getStyle($cellCoordinate)->applyFromArray($backgroundColor);
                            }
                            else
                            {
                                $object->getActiveSheet()->setCellValueByColumnAndRow($cl, $excel_row, '');
                            }

                            $cl++;
                        }
                        $excel_row++;
                    }

				}
				else{
					// $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, 'No Data');
					$object->getActiveSheet()->mergeCells('G'.$excel_row.':P'.$excel_row);
					$object->getActiveSheet()->setCellValue('G'.$excel_row, 'No Data');
					$object->getActiveSheet()->getStyle('G'.$excel_row)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
				}
                $excel_row++;
                $i++;
            }
        }
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'html');
		/*........old function for Excel......... 
		 $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Daily Task - '.$date.'.xls"');
		$excel_file_path = 'daily_task_' . $date . '.xlsx';
		$object_writer->save($excel_file_path);
        $object_writer->save('php://output');
		
		

		//--------------For PDF----------------
		$sheet = $object->getActiveSheet();
		$html = '<table border="1" cellspacing="0" cellpadding="5">';

		$counter=0;
		$boldHeader='font-weight:1000; font-size:20px; background:#0002;';
		foreach ($sheet->getRowIterator() as $row) {
			$html .= '<tr>';
			foreach ($row->getCellIterator() as $cell) {
				$cellCoordinate = $cell->getCoordinate();
        
				$bgColor = $sheet->getStyle($cellCoordinate)->getFill()->getStartColor()->getRGB();
				$text=htmlspecialchars($cell->getValue()) ;
				$textBol=$text=='No Data'? true :false;
				$style='background: #'.$bgColor.'; border:1px solid #0003 ;';
				$style= $counter==1? $style . $boldHeader:$style;
				if($textBol){
					$style=$style.'color:#f22;';
				}
				$html .= '<td style="'.$style.'">' . $text . '</td>';
			}
			$html .= '</tr>';
			$counter++;
		}

		$html .= '</table>';
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A2', 'portrait');
		$dompdf->render();
		$dompdf->stream("Daily_Task_Report_{$date}.pdf", ["Attachment" => false]);

		//------For PDF--------------------

    }*/

	public function daily_task() {
		$session_data = $this->session->userdata('login_session');
		$isExcel=$this->input->get('isExcel');
		$user_id = ($session_data['emp_id']);
		$this->load->model("excel_export_model");
		$this->load->library("excel");
		$employee_id = $this->input->get('employee_id');
		$date = $this->input->get('date');

		$object = new PHPExcel();
		$object->setActiveSheetIndex(0);

		// Header row
		$table_columns = array('Sr.No.', 'Name', 'Role', 'Project Name', 'Task Name', 'Task Description', 'Time Taken', 'Status');
		$column = 0;
		foreach ($table_columns as $field) {
			$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
			$column++;
		}

		$result = $this->customer_model->get_firm_id($user_id);
		if ($result !== false) {
			$firm_id = $result['firm_id'];
		}

		if ($employee_id == "" || $employee_id == "all") {
			$query = $this->db->query('SELECT user_name, user_id, (SELECT d.designation_name FROM designation_header_all d WHERE d.designation_id = u.designation_id ORDER BY id DESC LIMIT 1) as role FROM user_header_all u WHERE firm_id="' . $firm_id . '" AND user_type IN (4) AND activity_status = 1');
		} else {
			$query = $this->db->query("SELECT user_name, user_id, (SELECT d.designation_name FROM designation_header_all d WHERE d.designation_id = u.designation_id ORDER BY id DESC LIMIT 1) as role FROM user_header_all u WHERE user_id='" . $employee_id . "' AND activity_status=1");
		}

		if ($this->db->affected_rows() > 0) {
			$employee_data = $query->result();
			$excel_row = 2;
			$i = 1;

			foreach ($employee_data as $row) {
				$taskData = $this->db->query('SELECT * FROM `employee_schedule_task` WHERE date="' . $date . '" AND user_id="' . $row->user_id . '" AND status=1');
				
				if ($this->db->affected_rows() > 0) {
					$taskData = $taskData->result();
					foreach ($taskData as $taskRow) {
						$activity_types = [1 => 'Development', 2 => 'Designing', 3 => 'Bug resolutions', 4 => 'Meetings/Discussion', 5 => 'Testing'];
						$status_types = [1 => 'Pending', 2 => 'Completed', 3 => 'In Progress'];
						
						$activity = $activity_types[$taskRow->activity_type] ?? 'Testing';
						$status = $status_types[$taskRow->work_status] ?? 'In Progress';

						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $i);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->user_name);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->role);
						$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $taskRow->project_name);
						$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $taskRow->task_title);
						$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $taskRow->task_desc);
						$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $taskRow->total_hour);
						$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $status);

						$excel_row++;
						$i++;
					}
				} else {
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $i);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->user_name);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->role);
					$object->getActiveSheet()->mergeCells("D$excel_row:H$excel_row");
					$object->getActiveSheet()->setCellValue("D$excel_row", "No Data");
					$object->getActiveSheet()->getStyle("D$excel_row")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
					
					$excel_row++;
					$i++;
				}
			}
		}
		if($isExcel=='true'){

			/*------ For Excel -------*/
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Daily_Task_' . $date . '.xls"');
			header('Cache-Control: max-age=0');

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			$object_writer->save('php://output');
			
			/*----------- For Excel-------*/
		}else{

				/*------For Pdf-----*/
			$object_writer = PHPExcel_IOFactory::createWriter($object, 'html');
			$sheet = $object->getActiveSheet();
			$html = '<table border="1" cellspacing="0" cellpadding="5">';
			$html = '<h2 style="text-align:center;">Daily Task Report - ' . $date . '</h2>';
			$html .= '<table border="1" cellspacing="0" cellpadding="5">';
			$html .= '<tr>';

			$counter=0;
			$boldHeader='font-weight:1000; font-size:20px; background:#0002;';
			foreach ($sheet->getRowIterator() as $row) {
				$html .= '<tr>';
				foreach ($row->getCellIterator() as $cell) {
					$cellCoordinate = $cell->getCoordinate();
			
					$bgColor = $sheet->getStyle($cellCoordinate)->getFill()->getStartColor()->getRGB();
					$text=htmlspecialchars($cell->getValue()) ;
					$textBol=$text=='No Data'? true :false;
					$style='background: #'.$bgColor.'; border:1px solid #0003 ;';
					$style= $counter==0? $style . $boldHeader:$style;
					if($textBol){
						$style=$style.'color:#f22;';
					}
					$html .= '<td style="'.$style.'">' . $text . '</td>';
				}
				$html .= '</tr>';
				$counter++;
			}
		}
			$html .= '</table>';
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A2', 'portrait');
			$dompdf->render();
			$dompdf->stream("Daily_Task_Report_{$date}.pdf", ["Attachment" => false]);


	}

    function monthlyTask()
    {
    	$frequency=$this->input->get('frequency');
        $employee_id=$this->input->get('employee_id');
        $monthYear=$this->input->get('month');
		$isExcel=$this->input->get('isExcel');
		// print_r($isExcel);die;
        $week=null;
        if($frequency=="weekly")
        {

        $week=$this->input->get('week');
        }
        $session_data = $this->session->userdata('login_session');
        
        // print_r($user_id);exit();
        $user_type = ($session_data['user_type']);
        $this->load->model("excel_export_model");
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);
        //date
        $date=date('Y-m-d');
        

       
        // $range=range(strtotime("10:00:00"),strtotime("19:00:00"),15*60);
        // foreach($range as $time){
        //     $time= date("H:i:s",$time);
        //     array_push($table_columns,$time);
        // }
        

        $user_name="";
         $dates_array = $this->get_dates_array_from_month_year($frequency,$monthYear,$week);
       
        $query = $this->db->query("select user_name,user_id,mobile_no,(select s.user_name from user_header_all s where s.user_id=u.senior_user_id) as senior from user_header_all u where user_id='".$employee_id."' and activity_status=1");
       
        if ($this->db->affected_rows() > 0) {
            $employee_data = $query->row();
            $user_name=$employee_data->user_name;
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Employee name");
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 1, $employee_data->user_name);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 2, "Contact No");
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 2, $employee_data->mobile_no);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 3, "Senior Authority");
	        $object->getActiveSheet()->setCellValueByColumnAndRow(1, 3, $employee_data->senior);
	        $table_columns = array("Date","Project","Task","Time Taken");
	        $column = 0;
	        foreach ($table_columns as $field) {
	            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 4, $field);
	            $column++;
	        }
	        $excel_row = 5;
            $i=1;
            foreach ($dates_array as $dateR) {
            	$fDate=date('d-M-y',strtotime($dateR));
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $fDate);

                // $taskData=$this->db->query('SELECT * FROM `employee_schedule_task` WHERE date="2024-07-30" and user_id="U_677" and status=1');
                $taskData=$this->db->query('SELECT * FROM `employee_schedule_task` WHERE date="'.$dateR.'" and user_id="'.$employee_id.'" and status=1');
                if ($this->db->affected_rows() > 0) {
                    $taskData=$taskData->result();

                    foreach($taskData as $taskRow)
                    {
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $taskRow->task_title);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $taskRow->task_desc);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $taskRow->total_hour.' Hr');
                        $excel_row++;
                    }

                }
                $excel_row++;
                $i++;
            }
        }
		if($isExcel=='true'){
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				ob_end_clean();
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="Monthly Task of '.$user_name.'.xls"');
				$object_writer->save('php://output');
		}else{
			$object_writer = PHPExcel_IOFactory::createWriter($object, 'html');
			$sheet = $object->getActiveSheet();
			$html = '<table border="1" cellspacing="0" cellpadding="5">';
			$html = '<h2 style="text-align:center;">Daily Task Report - ' . $dateR . '</h2>';
			$html .= '<table border="1" cellspacing="0" cellpadding="5">';
			$html .= '<tr>';

			$counter=0;
			$boldHeader='font-weight:1000; font-size:20px; background:#0002;';
			foreach ($sheet->getRowIterator() as $row) {
				$html .= '<tr>';
				foreach ($row->getCellIterator() as $cell) {
					$cellCoordinate = $cell->getCoordinate();
			
					$bgColor = $sheet->getStyle($cellCoordinate)->getFill()->getStartColor()->getRGB();
					$text=htmlspecialchars($cell->getValue()) ;
					$textBol=$text=='No Data'? true :false;
					$style='background: #'.$bgColor.'; border:1px solid #0003 ;';
					$style= $counter==0? $style . $boldHeader:$style;
					if($textBol){
						$style=$style.'color:#f22;';
					}
					$html .= '<td style="'.$style.'">' . $text . '</td>';
				}
				$html .= '</tr>';
				$counter++;
			}
		}
		$html .= '</table>';
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A2', 'portrait');
		$dompdf->render();
		$dompdf->stream("Montly_Task_Report_{$date}.pdf", ["Attachment" => false]);
		
    }
    function get_dates_array_from_month_year($frequency,$month_year,$week=null) {
		// Split the month-year string into year and month
		$date = explode('-', $month_year);

		// Assuming the format is 'YYYY-MM', get the year and month
		$year = $date[0];
		$month = $date[1];

		// Create a DateTime object for the first day of the month
		$first_day_of_month = new DateTime("$year-$month-01");

		// Get the day of the week for the first day of the month (1 = Monday, 7 = Sunday)
		$first_day_weekday = $first_day_of_month->format('N');

		// Calculate the start date of the first week (might be in the previous month)
		$start_date = clone $first_day_of_month;
		$start_date->modify('-' . ($first_day_weekday - 1) . ' days');

		// Get the total number of days in the month
		$num_days = $first_day_of_month->format('t');

		// Create an array to store the weeks and dates
		$weeks = [];
		$month_dates=[];

		// Loop to fill the weeks with dates
		$current_week = [];
		for ($day = 1; $day <= $num_days; $day++) {
		$current_date = new DateTime(sprintf('%04d-%02d-%02d', $year, $month, $day));
		 $month_dates[] = $current_date->format('Y-m-d');

		// If it's the start of a new week, push the current week to the weeks array
		if ($current_date->format('N') == 1 && !empty($current_week)) {
		    $weeks[] = $current_week;
		    $current_week = [];
		}

		// Add the current date to the current week
		$current_week[] = $current_date->format('Y-m-d');

		// If it's the last day of the month, push the last week
		if ($day == $num_days) {
		    $weeks[] = $current_week;
		}
		}

		// Return the requested week dates
		if($frequency=="monthly")
		{
			return $month_dates;
		}else
		if (isset($weeks[$week - 1])) {
		return $weeks[$week - 1];
		} else {
		return []; // If the week number is invalid
		}
    }
    function yearly_task()
    {
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        $year=$this->input->get('year');
        $this->load->model("excel_export_model");
        $this->load->library("excel");
        $object = new PHPExcel();
        $result=$this->getRmtProjectListAndHourCount($year);
        $object->setActiveSheetIndex(0);
        //date
        $date=date('Y-m-d');
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Project/Month");
        $table_columns  = [
			    1 => "January",
			    2 => "February",
			    3 => "March",
			    4 => "April",
			    5 => "May",
			    6 => "June",
			    7 => "July",
			    8 => "August",
			    9 => "September",
			    10 => "October",
			    11 => "November",
			    12 => "December"
			];
        
        $column = 1;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
       	if(count($result)>0)
       	{
       		$excel_row = 2;
            foreach($result as $drow)
            {
            	$i=0;
            	foreach($drow as $row)
            	{
            		$hr='';
            		if($i!=0 && $row!=0)
            		{
            			$hr='Hr';
            		}
            		$object->getActiveSheet()->setCellValueByColumnAndRow($i, $excel_row, $row.' '.$hr);
            		$i++;
            	}
            	$excel_row++;
            }
       	}
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Project Yearly Task of '.$date.'.xls"');
        $object_writer->save('php://output');
    }
    
    // function sumTimeArray($times) {
	//     $totalMinutes = 0;

	//     foreach ($times as $time) {
	//         list($hours, $minutes) = explode(':', $time);
	//         $totalMinutes += $hours * 60 + $minutes;
	//     }

	//     $hours = floor($totalMinutes / 60);
	//     $minutes = $totalMinutes % 60;

	//     // Format the result as HH:MM
	//     return sprintf('%02d:%02d', $hours, $minutes);
	// }

	function sumTimeArray($times) {
		$totalMinutes = 0;
	
		foreach ($times as $time) {
			// Ensure $time is valid and contains ':'
			if (!is_string($time) || strpos($time, ':') === false) {
				continue; // Skip invalid values
			}
	
			$parts = explode(':', $time);
			$hours = isset($parts[0]) && is_numeric($parts[0]) ? (int)$parts[0] : 0;
			$minutes = isset($parts[1]) && is_numeric($parts[1]) ? (int)$parts[1] : 0;
	
			$totalMinutes += ($hours * 60) + $minutes;
		}
	
		$hours = floor($totalMinutes / 60);
		$minutes = $totalMinutes % 60;
	
		return sprintf('%02d:%02d', $hours, $minutes);
	}
	
	function user_task() {
		 $session_data = $this->session->userdata('login_session');
	    $user_id = ($session_data['emp_id']);
	    $user_type = ($session_data['user_type']);
	    $year=$this->input->get('year');
	    $this->load->model("excel_export_model");
	    $this->load->library("excel");
	    $object = new PHPExcel();

	    // Define the months array
	    $months = [
	        1 => "January",
	        2 => "February",
	        3 => "March",
	        4 => "April",
	        5 => "May",
	        6 => "June",
	        7 => "July",
	        8 => "August",
	        9 => "September",
	        10 => "October",
	        11 => "November",
	        12 => "December"
	    ];

	    $date = date('Y-m-d');

	    // Get the project list
	    $table_columns = $this->getProjectList();

	    $sheetIndex = 0;

	    foreach ($months as $monthNumber => $monthName) {
	        // Create a new worksheet if it's not the first month
	        if ($sheetIndex > 0) {
	            $object->createSheet();
	        }

	        // Set the active sheet index to the current month
	        $object->setActiveSheetIndex($sheetIndex);

	        // Set the sheet title to the current month
	        $object->getActiveSheet()->setTitle($monthName);

	        // Set the column headers for the current sheet
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "Employee/Project");
	        
	        $column = 1;

	        foreach ($table_columns as $field) {
	            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field->task_name);
	            $column++;
	        }

	        $result=$this->getUserHourCount($monthNumber,$table_columns,$year);
	        if(count($result)>0)
	        {
	        	$excel_row = 2;
	            foreach($result as $drow)
	            {
	            	$i=0;
	            	foreach($drow as $row)
	            	{
	            		$hr='';
	            		if($i!=0 && $row!=0)
	            		{
	            			$hr='Hr';
	            		}
	            		$object->getActiveSheet()->setCellValueByColumnAndRow($i, $excel_row, $row.' '.$hr);
	            		$i++;
	            	}
	            	$excel_row++;
	            }
	        }

	        // Increment the sheet index for the next month
	        $sheetIndex++;
	    }

	    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
	    ob_end_clean();
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="Project Employee Monthly'.$date.'.xls"');
	    $object_writer->save('php://output');
    }

    function getProjectList() {
    	$projectList=array();
    	$user_id="U_603";
    	$getProjectsList = $this->db2->query('
                select p.task_name,p.node_id 
                 from personal_task_all p where (p.type_of_node =3 and p.activity_status=1 and p.current_status in (1,2,4) and created_by = "' . $user_id . '")  or  
                 (p.node_id in 
                 (select r.node_id from personal_task_reference_table r where r.user_id = "' . $user_id . '"
                 and node_id in (select node_id from personal_task_all where type_of_node = 3 and activity_status = 1 and current_status in (1,2,4)) )) 
                 order by p.date_completion desc
                ');
        if($this->db2->affected_rows()>0)
        {
            $projectList=$getProjectsList->result();
        }
        return $projectList;
    }

    function getRmtProjectListAndHourCount($year='') {
		$session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
		// print_r($user_id);die;
        $user_type = ($session_data['user_type']);
        $year=$this->input->post('year');
		$result = $this->customer_model->get_firm_id($user_id);
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }

		$query = $this->db->query("
					SELECT firm_id, user_name, user_id, 
					(SELECT d.designation_name 
					FROM designation_header_all d 
					WHERE d.designation_id = u.designation_id 
					ORDER BY id DESC LIMIT 1) AS role
					FROM user_header_all u 
					WHERE firm_id = ? AND user_type IN (4) AND activity_status = 1", [$firm_id]);
	
		$employee_data = $query->result_array();
			
		if (empty($employee_data)) {
			$response = ['status' => 400, 'body' => 'No employee data found'];
			echo json_encode($response);
			return;
		}
			
		// Extract user IDs
		$user_ids = array_column($employee_data, 'user_id'); // Extract only user_id values
		$user_ids_str = implode("','", $user_ids); // Convert array into a comma-separated string for SQL
		
		// print_r($firm_id);die;
    	$projectList=array();
    	$table_columns  = [
			    1 => "January",
			    2 => "February",
			    3 => "March",
			    4 => "April",
			    5 => "May",
			    6 => "June",
			    7 => "July",
			    8 => "August",
			    9 => "September",
			    10 => "October",
			    11 => "November",
			    12 => "December"
			];
    	// $user_id="U_603";
    	// $getProjectsList = $this->db2->query('
        //         select p.task_name,p.node_id 
        //          from personal_task_all p where (p.type_of_node =3 and p.activity_status=1 and p.current_status in (1,2,4) and created_by = "' . $user_id . '")  or  
        //          (p.node_id in 
        //          (select r.node_id from personal_task_reference_table r where r.user_id = "' . $user_id . '"
        //          and node_id in (select node_id from personal_task_all where type_of_node = 3 and activity_status = 1 and current_status in (1,2,4)) )) 
        //          order by p.date_completion desc
        //         ');
		$getProjectsList = $this->db2->query("
							SELECT p.task_name, p.node_id
							FROM personal_task_all p
							WHERE (p.type_of_node = 3 
							AND p.activity_status = 1 
							AND p.current_status IN (1,2,4) 
							AND p.created_by IN ('$user_ids_str'))
							OR (p.node_id IN (
								SELECT r.node_id
								FROM personal_task_reference_table r
								WHERE r.user_id IN ('$user_ids_str')
								AND r.node_id IN (
									SELECT node_id
									FROM personal_task_all
									WHERE type_of_node = 3 
									AND activity_status = 1 
									AND current_status IN (1,2,4)
								)
							))
							ORDER BY p.date_completion DESC");
        if($this->db2->affected_rows()>0)
        {
            $data=$getProjectsList->result();
            foreach($data as $row)
            {
            	$resultObject=$this->MasterModel->_rawQuery("select *,MONTH(date) as month from employee_schedule_task where project_id='".$row->node_id."' and YEAR(date)='".$year."'");
            	$monthArr=array();
            	$dataArr=array($row->task_name);
            	if($resultObject->totalCount>0)
            	{
            		foreach($resultObject->data as $drow)
            		{
            			$monthArr[$drow->month][]=$drow->total_hour;
            		}
            	}
            	foreach($table_columns as $key => $col)
            	{
            		$monthCount=0;
            		if(array_key_exists($key,$monthArr))
            		{
            			$monthCount=$this->sumTimeArray($monthArr[$key]);
            		}
            		array_push($dataArr,$monthCount);
            	}
            	array_push($projectList,$dataArr);
            }
        }
        return $projectList;
	}

	function getUserHourCount($monthNumber,$project_list,$year) {
		// $monthNumber=8;
		$session_data = $this->session->userdata('login_session');
	    $user_id = ($session_data['emp_id']);
	    $user_type = ($session_data['user_type']);
		$result = $this->customer_model->get_firm_id($user_id);
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        $users=array();
        if ($user_type == 4) {
            $all_active_users=$this->MasterModel->_rawQuery('select user_name,user_id,(select group_concat(e.project_id,"@",e.total_hour) from employee_schedule_task e where e.user_id=u.user_id and MONTH(e.date)='.$monthNumber.') as work_status from user_header_all u where user_id="'.$user_id.'" or senior_user_id="'.$user_id.'" and activity_status =1');

        } else {
            $all_active_users=$this->MasterModel->_rawQuery('select user_name,user_id,(select group_concat(e.project_id,"@",e.total_hour) from employee_schedule_task e where e.user_id=u.user_id and MONTH(e.date)='.$monthNumber.' and YEAR(e.date)='.$year.') as work_status from user_header_all u where firm_id="'.$firm_id.'" and user_type in (4) and activity_status =1');
        }
        
        $userArr=array();
        if($all_active_users->totalCount>0)
        {
            foreach($all_active_users->data as $row)
            {
            	$dataArr=array($row->user_name);
            	$projectArr=array();
            	$workStatus=explode(',',$row->work_status);
            	foreach($workStatus as $wrow)
            	{
            		$projectData=explode('@',$wrow);
            		if(count($projectData)>1)
            		{
            			$projectArr[$projectData[0]][]=$projectData[1];
            		}
            	}
            	foreach($project_list as $prow)
            	{
            		$pCount=0;
            		if(array_key_exists($prow->node_id,$projectArr))
            		{
            			$pCount=$this->sumTimeArray($projectArr[$prow->node_id]);
            		}
            		array_push($dataArr,$pCount);
            	}
            	array_push($userArr,$dataArr);
            }
        }
        return $userArr;
	}

	public function getDailyTaskData() {
		
		$date=$this->input->post('date');
		$employee_id=$this->input->post('employee_id');
		$table_columns=array('Sr.No.','Name','Role','Project Name','Task Name','Task Description','Time Taken','Status');
		
        $html='<table class="table table-bordered"><thead><tr>';
    	foreach($table_columns as $trow)
    	{
    		$html.='<th>'.$trow.'</th>';
    	}
        $html.='</tr></thead><tbody>';

        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $result = $this->customer_model->get_firm_id($user_id);
        if ($result !== false) {
            $firm_id = $result['firm_id'];
        }
        if($employee_id=="" || $employee_id=="all"){
        	$query = $this->db->query('select user_name,user_id,(select d.designation_name from designation_header_all d where d.designation_id=u.designation_id order by id desc limit 1) as role from user_header_all u where firm_id="'.$firm_id.'" and user_type in (4) and activity_status =1');
		}
		else
		{
            $query = $this->db->query("select user_name,user_id,(select d.designation_name from designation_header_all d where d.designation_id=u.designation_id order by id desc limit 1) as role from user_header_all u where user_id='".$employee_id."' and activity_status=1");
		}
		$data=array();
        if ($this->db->affected_rows() > 0) {
            $employee_data = $query->result();
			// print_r($employee_data);die;
            $i=1;
            foreach ($employee_data as $row) {
            	// $html.='<tr><td>'.$i.'</td><td>'.$row->user_name.'</td><td>'.$row->role.'</td>';
            	$taskData=$this->db->query('SELECT * FROM `employee_schedule_task` WHERE date="'.$date.'" and user_id="'.$row->user_id.'" and status=1');
				
            	if ($this->db->affected_rows() > 0) {
                    $taskData=$taskData->result();
					// print_r($taskData);die;
					$taskCount = count($taskData);

                	$firstRow = true;
                    foreach($taskData as $taskRow)
                    {
                    	switch ($taskRow->activity_type) {
						    case 1:
						        $activity = 'Development';
						        break;
						    case 2:
						        $activity = 'Designing';
						        break;
						    case 3:
						        $activity = 'Bug resolutions';
						        break;
						    case 4:
						        $activity = 'Meetings/Discussion';
						        break;
						    default:
						        $activity = 'Testing';
						        break;
						}
						switch ($taskRow->work_status) {
						    case 1:
							    $status='Pending';
							    break;
						    case 2:
							    $status='Completed';
							    break;
						    default:
							    $status='In Progress';
							    break;
						}
					
						$html .= '<tr>';
                    $html .= '<td>' . $i . '</td>'; // Assign unique Sr. No.
                    $html .= '<td>' . $row->user_name . '</td>';
                    $html .= '<td>' . $row->role . '</td>';
                    $html .= '<td>' . $taskRow->project_name . '</td>';
                    $html .= '<td>' . $taskRow->task_title . '</td>';
                    $html .= '<td>' . $taskRow->task_desc . '</td>';
                    $html .= '<td>' . $taskRow->total_hour . '</td>';
                    $html .= '<td>' . $status . '</td>';
                    $html .= '</tr>';
                    $i++;
					// 	if ($firstRow) {
					// 		// $html .= '<td rowspan="' . $taskCount . '">' . $i . '</td>';
					// 		$html .= '<td rowspan="' . $taskCount . '">' . $row->user_name . '</td>';
					// 		$html .= '<td rowspan="' . $taskCount . '">' . $row->role . '</td>';
					// 		$firstRow = false;
					// 	}
					// 		$html.='<td>'.$taskRow->project_name.'</td>';
					// 		$html.='<td>'.$taskRow->task_title.'</td>';
					// 		$html.='<td>'.$taskRow->task_desc.'</td>';
					// 		$html.='<td>'.$taskRow->total_hour.'</td>';
					// 		$html.='<td>'.$status.'</td>';
					// 		// $html .= '</tr>';
                    // }
                }
			}
                else
                {
                	$html.='<td>'.$i.'</td><td>'.$row->user_name.'</td><td>'.$row->role.'</td><td colspan=5>No data</td>';
					
                }
            	$html.='</tr>';
            	$i++;
            }
        }
        $html.='<tbody></table>';
        $response['status']=200;
        $response['body']=$html;
		echo json_encode($response);
	}

	function getWeeklyTaskData() {
		$frequency=$this->input->post('frequency');
        $employee_id=$this->input->post('employee_id');
        $monthYear=$this->input->post('month');
        $week=null;
        if($frequency=="weekly")
        {
       		$week=$this->input->post('week');
        }
        $this->load->model("excel_export_model");
        $html='<table class="table table-bordered"><thead>';
        $date=date('Y-m-d');
        $user_name="";
        $query = $this->db->query("select user_name,user_id,mobile_no,(select s.user_name from user_header_all s where s.user_id=u.senior_user_id) as senior from user_header_all u where user_id='".$employee_id."' and activity_status=1");
        if ($this->db->affected_rows() > 0) {
            $employee_data = $query->row();
            
            $user_name=$employee_data->user_name;
           
	       
	        $html.='<tr><th>Employee name</th><th>'.$employee_data->user_name.'</th><th colspan="2"></th></tr>';
	        $html.='<tr><th>Contact No</th><th>'.$employee_data->mobile_no.'</th><th colspan="2"></th></tr>';
	        $html.='<tr><th>Senior Authority</th><th>'.$employee_data->senior.'</th><th colspan="2"></th></tr>';
	        $html.='<tr>';
	        $table_columns = array("Date","Project Name","Task Name","Task Description","Time Taken");
	    	foreach($table_columns as $trow)
	    	{
	    		$html.='<th>'.$trow.'</th>';
	    	}
	        $html.='</tr></thead><tbody>';
	      	
            $i=1;
            $dates_array = $this->get_dates_array_from_month_year($frequency,$monthYear,$week);
            foreach ($dates_array as $dateR) {
            	$fDate=date('d-M-y',strtotime($dateR));
                $html.='<tr><td>'.$fDate.'</td>';
                $taskData=$this->db->query('SELECT * FROM `employee_schedule_task` WHERE date="'.$dateR.'" and user_id="'.$employee_id.'" and status=1');
				
                if ($this->db->affected_rows() > 0) {
                    $taskData=$taskData->result();
					// print_r( $taskData);die;
                    foreach($taskData as $tkey => $taskRow)
                    {	
                    	if($tkey!=0)
                    	{
                    		$html.='<tr><td></td>';
                    	}
                    	$html.='<td>'.$taskRow->project_name.'</td>';
						$html.='<td>'.$taskRow->task_title.'</td>';
                    	$html.='<td>'.$taskRow->task_desc.'</td>';
                    	$html.='<td>'.$taskRow->total_hour.' Hr</td>';
                    	$html.='</tr>';
                    }
                }
                else
                {
                	$html.='<td></td><td></td><td></td><td></td></tr>';
                }
                $i++;
            }
        }
       	$html.='<tbody></table>';
        $response['status']=200;
        $response['body']=$html;
        echo json_encode($response);
	}

	
	public function getProjectYearly() {
		$session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $user_type = ($session_data['user_type']);
        $year=$this->input->post('year');
        $result=$this->getRmtProjectListAndHourCount($year);
		// 	echo
		//    print_r($result);die;
        $table_columns  = [
			    1 => "January",
			    2 => "February",
			    3 => "March",
			    4 => "April",
			    5 => "May",
			    6 => "June",
			    7 => "July",
			    8 => "August",
			    9 => "September",
			    10 => "October",
			    11 => "November",
			    12 => "December"
			];
        
        $html='<table class="table table-bordered"><thead><tr><th>Project/Month</th>';
        foreach ($table_columns as $field) {
        	$html.='<th>'.$field.'</th>';
        }
        $html.='</tr></thead><tbody>';
       	if(count($result)>0)
       	{
            foreach($result as $drow)
            {
            	$html.='<tr>';
            	foreach($drow as $dkey => $row)
            	{
            		$hr='';
            		if($dkey!=0 && $row!=0)
            		{
            			$hr='Hr';
            		}
            		$html.='<td>'.$row.' '.$hr.'</td>';
            	}
            	$html.='</tr>';
            }
       	}
       	$html.='<tbody></table>';
       	$response['status']=200;
        $response['body']=$html;
        echo json_encode($response);
	}

	public function getProjectMonthly() {
		$session_data = $this->session->userdata('login_session');
	    $user_id = ($session_data['emp_id']);
	    $user_type = ($session_data['user_type']);
	    $year=$this->input->post('year');
	    $this->load->model("excel_export_model");
	    $this->load->library("excel");
	    $object = new PHPExcel();

	    // Define the months array
	    $months = [
	        1 => "January",
	        2 => "February",
	        3 => "March",
	        4 => "April",
	        5 => "May",
	        6 => "June",
	        7 => "July",
	        8 => "August",
	        9 => "September",
	        10 => "October",
	        11 => "November",
	        12 => "December"
	    ];
	    $html='<div class="tab">';
	    foreach($months as $monthNumber => $monthName)
	    {
	    	$active='';
	    	// if($monthNumber==1)
	    	// {
	    	// 	$active="active";
	    	// }
	    	$html.='<button class="tablinks tablinkCon'.$monthNumber.' '.$active.'" onclick="openTab(event, '.$monthNumber.')">'.$monthName.'</button>';
	    }
	    $html.='</div>';
	    $date = date('Y-m-d');

	    // Get the project list
	    $table_columns = $this->getProjectList();
	    // print_r($table_columns);exit();
	    $sheetIndex = 0;

	    foreach ($months as $monthNumber => $monthName) {
	    	$html.='<div id="Tab'.$monthNumber.'" class="tabcontent">';
	    	$html.='<table class="table table-bordered">';
	        
	        $html.='<thead><tr><th>Employee/Project</th>';
	        foreach ($table_columns as $field) {
	            $html.='<th>'.$field->task_name.'</th>';
	        }
	        $html.='</tr></thead><tbody>';

	        $result=$this->getUserHourCount($monthNumber,$table_columns,$year);
	        if(count($result)>0)
	        {
	            foreach($result as $drow)
	            {
	            	$html.='<tr>';
	            	foreach($drow as $dkey => $row)
	            	{
	            		$hr='';
	            		if($dkey!=0 && $row!=0)
	            		{
	            			$hr='Hr';
	            		}
	            		$html.='<td>'.$row.' '.$hr.'</td>';
	            	}
	            	$html.='</tr>';
	            }
	        }
	        $html.='</tbody></table></div>';
	    }
	    $html.='<script>loadFirstTab();</script>';
	    $response['status']=200;
        $response['body']=$html;
        echo json_encode($response);
	}


	
}
