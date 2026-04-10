<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class WordReportController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('MasterModel');
        $this->load->model('Globalmodel');
        //$this->load->model('AwsModel');
        $this->db2 = $this->load->database('db2', TRUE);
    }

    public function index($type, $id)
    {
        $this->load->view('WordReport/bmr_report', array('title' => 'Report', 'id' => $id, 'type' => $type));
    }

    public function report_list()
    {
        $this->load->view('WordReport/view_report_list', array('title' => 'Report List'));
    }

    public function bmr_report_view($type, $id)
    {
        $this->load->view('WordReport/view_bmr_report', array('title' => 'Report List', 'report_id' => $id, 'type' => $type));
    }

    public function all_bmr_report_view($type, $id)
    {
        $this->load->view('WordReport/view_all_bmr_report', array('title' => 'Report List', 'report_id' => $id, 'type' => $type));
    }

    public function word_report()
    {
        $this->load->view('WordReport/view_all_report_list', array('title' => 'Report List'));
    }

    public function getReportTableList()
    {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['emp_id']);
        }

        $table = $this->db->query('select id, name, status,is_dashboard,
            (select firm_name from partner_header_all where firm_id = w.company_id) as company_name, company_id, created_on,exchange_rate from word_reportMaker_table w');

        $tableRows = array();
        if ($this->db->affected_rows() > 0) {
            $tableData = $table->result();
            $i = 1;
            foreach ($tableData as $row) {
                $date_created_on = date('d-m-Y', strtotime($row->created_on));
                array_push($tableRows, array($i, $row->name, $row->id, $row->status, $row->is_dashboard, $row->company_name, $row->company_id, $date_created_on, $row->exchange_rate));
                $i++;
            }
            rsort($tableRows);
        }

        /*$tables = $this->MasterModel->_select('word_reportMaker_table w', array('company_id' => $company_id), array('id', 'name', 'status', 'is_dashboard',
           '(select name from rmt.tally_company_master where id = w.tally_company_id) as company_name', 'tally_company_id',
           '(select name from rmt.tally_branch_master where id = w.tally_branch_id) as branch_name,tally_branch_id',
           '(select group_concat(user_name) from rmt.user_header_all where user_id in (select user_id from tally_maker_checker where invoice_id=w.id and type=1)) as maker_user',
           '(select group_concat(user_name) from rmt.user_header_all where user_id in (select user_id from tally_maker_checker where invoice_id=w.id and type=2)) as checker_user',
           '(select group_concat(user_id) from rmt.tally_maker_checker where invoice_id=w.id and type=1) as maker_user_id',
           '(select group_concat(user_id) from rmt.tally_maker_checker where invoice_id=w.id and type=2) as checker_user_id',
           '(select created_on from rmt.tally_maker_checker where invoice_id=w.id GROUP BY invoice_id) as date_created_on','exchange_rate'
       ), false);*/
        /*if ($tables->totalCount > 0) {
            $i = 1;
            foreach ($tables->data as $row) {
                $date_created_on = date('d-m-Y',strtotime($row->date_created_on));
                array_push($tableRows, array($i, $row->name, $row->id, $row->status,$row->is_dashboard, $row->company_name, $row->tally_company_id, $row->branch_name,$row->tally_branch_id, $row->maker_user, $row->checker_user, $row->maker_user_id, $row->checker_user_id,$date_created_on,$row->exchange_rate));
                $i++;
            }
            rsort($tableRows);
        }*/

        $results = array(
            "draw" => 1,
            "recordsTotal" => count($tableRows),
            "recordsFiltered" => count($tableRows),
            "data" => $tableRows,
            "query" => $this->db->last_query()
        );
        echo json_encode($results);
    }

    public function saveHtmlTemplate()
    {

        $html_obj = $this->input->post('html_obj');
        $update_id = $this->input->post('update_id');
        $type = $this->input->post('type');
        $template_id = 1;
        if ($type == 1) {
            $tableName = 'word_reportMaker_table';
        } else {
            $tableName = 'rmt.production_scheduler_bmr_report';
        }
        $id = '';
        if ($html_obj != null && $html_obj != '') {

            if ($update_id != null && $update_id != '') {
                $id = $update_id;
                $insert = $this->MasterModel->_update($tableName, array('code' => $html_obj), array('id' => $update_id));
            } else {
                $insert = $this->MasterModel->_insert($tableName, array('code' => $html_obj));
                $id = $insert->inserted_id;
            }
            if ($insert->status) {
                $response['status'] = 200;
                $response['body'] = "Save Successfully";
                $response['data'] = $html_obj;
                $response['insert_id'] = $id;
            } else {
                $response['status'] = 201;
                $response['body'] = "Something Went Wrong";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function userProfiles()
    {

        $data = $this->MasterModel->_rawQuery(
            "select distinct (case when roles =1 then concat(roles,'-','Admin') when roles=2 then concat(roles,'-','Branch Admin') 
				when roles=3 then concat(roles,'-','Supplier') when roles=4 
				then concat(roles,'-','Inventory Admin') when roles=5 then concat(roles,'-','QC Tester')
				when roles=6 then concat(roles,'-','QA Tester') end) as roles from users_master");

        $users = $this->MasterModel->_rawQuery("select concat(id,'-',name) as users from users_master");


        $profiles = array();
        $user_array = array();
        if ($data->totalCount > 0) {
            foreach ($data->data as $p) {
                $profiles[] = $p->roles;
            }

            if ($users->totalCount > 0) {

                foreach ($users->data as $u) {
                    $user_array[] = $u->users;
                }

                $response['users'] = $user_array;
            }

            $response['status'] = 200;
            $response['body'] = "Data Found";
            $response['data'] = $profiles;
        } else {
            $response['status'] = 201;
            $response['body'] = "No Data Found";
        }
        echo json_encode($response);
    }

    public function getWordReportMakerData()
    {
        if (!is_null($this->input->post('id')) && $this->input->post('id') != "") {
            $id = $this->input->post('id');
            $type = $this->input->post('type');
            if ($type == 1) {
                $resultObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $id), '*', false);
            } else {
                $resultObject = $this->MasterModel->_select('production_scheduler_bmr_report', array('id' => $id), '*', false);
            }

            if ($resultObject->totalCount > 0) {
                $data = '';
                foreach ($resultObject->data as $row) {
                    $html_code = $row->code;
                    $html_data = json_decode($html_code);
                    foreach ($html_data[0]->pages as $p) {
                        $data .= '<tr><td>' . $p->page_name . '</td><td><button type="button" class="btn btn-primary btn-sm" onclick="getPageDataToEditor(' . $row->id . ',' . $p->page_id . ')"><i class="fa fa-edit"></i></button></td></tr>';
                    }
                }
                $response['status'] = 200;
                $response['body'] = $data;
            } else {
                $response['status'] = 201;
                $response['body'] = "No data found";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function getPageDataToEditor()
    {
        if (!is_null($this->input->post('id')) && $this->input->post('id') != "") {
            $id = $this->input->post('id');
            $type = $this->input->post('type');
            $page_id = $this->input->post('page_id');
            $data = array();
            if ($type == 1) {
                $resultObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $id), '*');
            } else {
                $resultObject = $this->MasterModel->_select('production_scheduler_bmr_report', array('id' => $id), '*');
            }
            if ($resultObject->totalCount > 0) {

                $row = $resultObject->data;
                $html_code = json_decode($row->code);
                if (!is_null($html_code)) {
                    foreach ($html_code[0]->pages as $p) {
                        if ($p->page_id == $page_id) {
                            $data = $p;
                        }
                    }
                }


                $response['status'] = 200;
                $response['body'] = $data;
                $response['id'] = $id;
                $response['bmr_object'] = $row->code;
                $response['report_name'] = $row->name;
            } else {
                $response['status'] = 201;
                $response['body'] = "No data found";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function getReportData()
    {
        if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
            $id = $this->input->post('report_id');
            $type = $this->input->post('type');
            if ($type == 1) {
                $tablename = 'word_reportMaker_table';
            } else {
                $tablename = 'production_scheduler_bmr_report';
            }
            $resultObject = $this->MasterModel->_select($tablename, array('id' => $id), '*');
            if ($resultObject->totalCount > 0) {
                $response['status'] = 200;
                $response['body'] = $resultObject->data;
            } else {
                $response['status'] = 201;
                $response['body'] = "No data found";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function saveReportPageData()
    {
//		print_r($this->input->post());exit();
        if (!is_null($this->input->post('page_id')) && $this->input->post('page_id') != "") {
            $page_id = $this->input->post('page_id');
            $type = $this->input->post('type');
            $report_id = $this->input->post('report_id');
            $page_input = $this->input->post('page_input');

            $page_type = 0;
            $product_id = 0;
            $insert_array = array();
            $user_id = $this->session->user_session->id;
            if ($page_input != "") {
                $tablename = 'customer_invoice_table';

//				$page_input=explode(',',$page_input);
                $resultObject = $this->MasterModel->_select($tablename, array('id' => $report_id), '*');
                if ($resultObject->totalCount > 0) {
                    $data = $resultObject->data->html;
                    $data = json_decode($data);
                    $pagedata = $data[0]->pages;

                    $onePageInd = array_search($page_id, array_column($pagedata, 'page_id'));
                    if ($onePageInd !== "") {
                        $onePage = $pagedata[$onePageInd];

                        $pageType = $onePage->page_type;
                        $page_input = $onePage->keys;
                        $pageDataSet = array();
                        if (count($page_input) > 0) {
                            foreach ($page_input as $row) {
                                if (!is_null($this->input->post($row)) || !empty($_FILES[$row]['name'])) {
                                    $inputValue = $this->input->post($row);
                                    if (is_array($inputValue)) {
                                        $inputValue = implode(',', $inputValue);
                                    }
                                    if (!empty($_FILES[$row]['name'])) {
                                        if (!empty($_FILES[$row]['name'][0])) {
                                            $des_path = "upload";
                                            $file_name = $this->Global_model->upload_file($des_path, $row);
                                            $answerFile = array();
                                            if ($file_name['status'] == 200) {
                                                $answerFile = $file_name['body'];
                                            }
                                            $inputValue = implode(',', $answerFile);
                                        } else {
                                            if (!is_null($this->input->post($row . '_file'))) {
                                                $inputValue = $this->input->post($row . '_file');
                                            }
                                        }
                                    }
                                } else {
                                    if (!is_null($this->input->post($row . '_file'))) {
                                        $inputValue = $this->input->post($row . '_file');

                                    } else {
                                        $inputValue = '';
                                    }
                                }
                                $inputAccess = array();
                                $inputAccess[$row] = array('value' => $inputValue, 'users' => $user_id, 'date' => date('Y-m-d'));
                                array_push($pageDataSet, $inputAccess);
                            }
                        }
                        if (!property_exists($onePage, 'dataset')) {
                            $onePage->dataset = array();
                        }
                        $onePage->dataset = $pageDataSet;
                    }
                    $pagedatanew = json_encode($data);
                    $update = new stdClass();
                    $update->status = false;

                    if ($pagedatanew != null) {
                        $update = $this->MasterModel->_update($tablename, array('html' => $pagedatanew), array('id' => $report_id));
                    }

                    if ($update->status) {
                        $response['status'] = 200;
                        $response['body'] = "Data Submitted";
                    } else {
                        $response['status'] = 201;
                        $response['body'] = "Something Went Wrong";
                    }
                } else {
                    $response['status'] = 201;
                    $response['body'] = "No changes to added";
                }

            } else {
                $response['status'] = 201;
                $response['body'] = "No changes to added";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function saveReportPageDatadatalink()
    {
//		print_r($this->input->post());exit();
        if (!is_null($this->input->post('page_id')) && $this->input->post('page_id') != "") {
            $page_id = $this->input->post('page_id');
            $type = $this->input->post('type');
            $report_id = $this->input->post('report_id');
            $page_input = $this->input->post('page_input');

            $page_type = 0;
            $product_id = 0;
            $insert_array = array();
            $user_id = '';
            if ($page_input != "") {
                if ($type == 1) {
                    $tablename = 'word_reportMaker_table';
                } else {
                    $tablename = 'production_scheduler_bmr_report';
                }
//				$page_input=explode(',',$page_input);
                $resultObject = $this->MasterModel->_select($tablename, array('id' => $report_id), '*');
                if ($resultObject->totalCount > 0) {
                    $data = $resultObject->data->code;
                    $data = json_decode($data);
                    $pagedata = $data[0]->pages;

                    $onePageInd = array_search($page_id, array_column($pagedata, 'page_id'));
                    if ($onePageInd !== "") {
                        $onePage = $pagedata[$onePageInd];

                        $pageType = $onePage->page_type;
                        $page_input = $onePage->keys;
                        $pageDataSet = array();
                        if (count($page_input) > 0) {
                            foreach ($page_input as $row) {
                                if (!is_null($this->input->post($row)) || !empty($_FILES[$row]['name'])) {

                                    $inputValue = $this->input->post($row);
                                    if (is_array($inputValue)) {
                                        $inputValue = implode(',', $inputValue);
                                    }

                                    if (!empty($_FILES[$row]['name'])) {
                                        if (!empty($_FILES[$row]['name'][0])) {
                                            $des_path = "upload";
                                            $file_name = $this->Global_model->upload_file($des_path, $row);
                                            $answerFile = array();
                                            if ($file_name['status'] == 200) {
                                                $answerFile = $file_name['body'];
                                            }
                                            $inputValue = implode(',', $answerFile);
                                        } else {
                                            if (!is_null($this->input->post($row . '_file'))) {
                                                $inputValue = $this->input->post($row . '_file');
                                            }
                                        }
                                    }
                                } else {
                                    if (!is_null($this->input->post($row . '_file'))) {
                                        $inputValue = $this->input->post($row . '_file');
                                    } else {
                                        $inputValue = '';
                                    }
                                }
                                $inputAccess = array();
                                $inputAccess[$row] = array('value' => $inputValue, 'users' => $user_id, 'date' => date('Y-m-d'));
                                array_push($pageDataSet, $inputAccess);
                            }
                        }
                        if (!property_exists($onePage, 'dataset')) {
                            $onePage->dataset = array();
                        }

                        $onePage->dataset = $pageDataSet;
                    }
                    $pagedatanew = json_encode($data);

                    $update = new stdClass();
                    $update->status = false;

                    $update = $this->MasterModel->_update($tablename, array('code' => $pagedatanew), array('id' => $report_id));

                    if ($update->status) {
                        $response['status'] = 200;
                        $response['body'] = "Data Submitted";
                    } else {
                        $response['status'] = 201;
                        $response['body'] = "Something Went Wrong";
                    }
                } else {
                    $response['status'] = 201;
                    $response['body'] = "No changes to added";
                }

            } else {
                $response['status'] = 201;
                $response['body'] = "No changes to added";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function bmrReport($type, $id, $queryParam,$year,$month)
    {
        $historyTable = '';
        $materialTable = '';
        $table = 'word_reportMaker_table c';
        $resultObject = $this->MasterModel->_select($table, array('id' => $id), '*,(select wt.name from word_reportMaker_table wt where wt.id=c.id) as name');
        $bmr_name = $resultObject->data->name;
        $monthName =  $month_name = date("M", mktime(0, 0, 0, $month, 10));

        $pdf_name =$monthName.'_'.$year;
        $finalhtml = '';
        $fhtml = $this->allBMRReports($id, $type, $queryParam);
        $finalhtml .= "<html style=\"width: 100%!important\">";
        $finalhtml .= "<style>
	
	
	table{
		  margin: 0 !important;		
		  padding: 0!important;
		  border-collapse: unset;
	    }
	    td, th {
	    	border: 1px solid #dddddd;
	    	text-align: left;   	
	    }
	    table td:first-child {
	    	/*background-color: red;*/
	    	font-family: arial, sans-serif;
	    }
	    tr{
	    	border-bottom: 1px solid black !important; 
	    }
	     p{
	    	margin:0 !important;
	    	padding: 0 !important;
	    	text-indent:0 !important;
	    }
	    
	    
</style>
";
        $finalhtml .= "<meta charset=UTF-8>";
        $finalhtml .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";

        $finalhtml .= "<body>";
        $finalhtml .= "<div class='test'>";
        $finalhtml .= $fhtml;
        $finalhtml .= "</div>";
        $finalhtml .= "</body>";
        $finalhtml .= "</html>";

        // print_r($finalhtml);exit();
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $options->set('isHtml5ParserEnabled', TRUE);
        $options->set('enable_php', true);
        $options->set('enable_remote', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($finalhtml);
        $customPaper = array(0, 0, 700, 1200);
        // $dompdf->set_paper($customPaper);
		$dompdf->setPaper('A1', 'landscape');
        ob_end_clean();
        $dompdf->render();
        $dompdf->stream($pdf_name);
        exit(0);
    }


    public function getBMRMasterReportList()
    {
        $resultObject = $this->MasterModel->_select('word_reportMaker_table', array('status' => 1), '*', false);
        if ($resultObject->totalCount > 0) {
            $option = '<option value="">Select BMR Report</option>';
            foreach ($resultObject->data as $row) {
                $option .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
            $response['status'] = 200;
            $response['body'] = $option;
        } else {
            $response['status'] = 201;
            $response['body'] = "";
        }
        echo json_encode($response);
    }

    public function setBMRReportToScheduler()
    {
        if (!is_null($this->input->post('id')) && $this->input->post('id') != "") {
            $id = $this->input->post('id');
            $schedule_id = $this->input->post('schedule_id');
//            $user_id = $this->session->user_session->id;
            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $user_id = ($session_data['emp_id']);
            }
            $resultObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $id), '*');
            if ($resultObject->totalCount > 0) {
                $data = $resultObject->data;
                $code = json_decode($data->code);
                $pagedata = $this->changeProductPageData($code, $id);
                $pagedata = json_encode($pagedata);
//				print_r($pagedata);exit();
//				$onePageInd = array_search('1', array_column($pagedata, 'page_type'));
//				print_r($pagedata);exit();
                $insertData = array('name' => $data->name,
                    'code' => $pagedata,
                    'report_id' => $id,
                    'scheduler_id' => $schedule_id,
                    'status' => 1,
                    'created_by' => $user_id,
                    'created_on' => date('Y-m-d H:i:s'));
                $updateData = array('name' => $data->name,
                    'code' => $pagedata,
                    'report_id' => $id,
                    'updated_by' => $user_id,
                    'updated_at' => date('Y-m-d H:i:s'));
                $reportObject = $this->MasterModel->_select('production_scheduler_bmr_report', array('scheduler_id' => $schedule_id), '*');
                if ($reportObject->totalCount > 0) {
                    $insert = $this->MasterModel->_update('production_scheduler_bmr_report', $updateData, array('id' => $reportObject->data->id));
                } else {
                    $insert = $this->MasterModel->_insert('production_scheduler_bmr_report', $insertData);
                }
                if ($insert->status == true) {
                    $response['status'] = 200;
                    $response['body'] = "Data Inserted Successfully";
                } else {
                    $response['status'] = 201;
                    $response['body'] = "Data Not Inserted";
                }
            } else {
                $response['status'] = 201;
                $response['body'] = "No Such Template Exists";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    function changeProductPageData($code, $id)
    {
        $pages = $code[0]->pages;
        $type = 1;
        if (count($pages) > 0) {
            foreach ($pages as $row) {
                if ($row->page_type == 1) {
                    $datasetarr = $row->dataset;
                    $htmlcode = $row->html_code;
                    $staticFields = $row->staticFields;
                    $keysArr = $row->keys;
                    $jsonarray = array();
                    if (count($datasetarr) > 0) {
                        foreach ($datasetarr as $datajson) {
                            foreach ($datajson as $key => $val) {
                                $jsonarray[$key] = $val->value;
                            }
                        }
                    }
                    $keyPairs = $row->keyPairs;

                    foreach ($keyPairs as $key => $keyRow) {

                        if ($keyRow[1] == 'table') {
                            if (strpos($keyRow[0], 'histable') !== false) {
                                $historyTable = $this->historyDesign($id, $type);
                                $htmlcode = str_replace('<span style="background-color: rgb(255, 255, 0);">' . $keyRow[0] . '</span>', $historyTable, $htmlcode);
                            } else if (strpos($keyRow[0], 'materialTable') !== false) {
                                $materialTable = $this->materialDesign($id, $type);
                                $htmlcode = str_replace('<span style="background-color: rgb(255, 255, 0);">' . $keyRow[0] . '</span>', $materialTable, $htmlcode);
                            }
                        } else {
                            if (array_key_exists($keyRow[0], $jsonarray)) {
                                if (count($staticFields) > 0) {
                                    if (in_array($keyRow[0], $staticFields)) {
                                        $inputValue = $jsonarray[$keyRow[0]];
                                        $htmlcode = str_replace('<span style="background-color: rgb(255, 255, 0);">' . $keyRow[0] . '</span>', $inputValue, $htmlcode);
                                        unset($keyPairs[$key]);
                                        $row->keyPairs = array_values($keyPairs);
//										if (($keyind = array_search($keyRow[0], $keysArr)) !== false) {
//
//											unset($keysArr[$keyind]);
//										}
//										$row->keys= array_values($keysArr);
                                    }
                                }
                            }
                        }
                    }
//					$row->dataset=array();
//					$row->keyPairs=array();
//					$row->keys=array();
                    $row->staticFields = array();
                    $row->html_code = $htmlcode;
                }
            }
        }
        return $code;
    }

    public function checkBMRReportAttach()
    {
        if (!is_null($this->input->post('schedule_id')) && $this->input->post('schedule_id') != "") {
            $id = $this->input->post('schedule_id');
            $resultObject = $this->MasterModel->_select('production_scheduler_bmr_report', array('scheduler_id' => $id), '*');
            if ($resultObject->totalCount > 0) {
                $response['status'] = 200;
                $response['report_id'] = $resultObject->data->report_id;
                $response['id'] = $resultObject->data->id;
            } else {
                $response['status'] = 201;
                $response['body'] = "Required Parameter Missing";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function addNewBMR()
    {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['emp_id']);
        }
        $id = $this->input->post('bmr_update_id');
        $name = $this->input->post('bmr_name');
        $company_list = $this->input->post('company_list');
        //$branch_list = $this->input->post('branch_list');
        //$company_id = $this->session->user_session->firm_id;
        $bmr_list = $this->input->post('bmr_list');
        $is_dashboard = $this->input->post('is_dashboard');
        $exchange_rate = $this->input->post('exchange_rate');


        if ($name != null && $name != '') {
            if ($id != null && $id != '') {

                $insert = $this->MasterModel->_update('word_reportMaker_table',

                    array('name' => $name, 'created_by' => $user_id, 'is_dashboard' => $is_dashboard, 'company_id' => $company_list, 'exchange_rate' => $exchange_rate), array('id' => $id));

            } else {
                $bmrdata = array(
                    'name' => $name,
                    'created_by' => $user_id,
                    'is_dashboard' => $is_dashboard,
                    'company_id' => $company_list,
                    'exchange_rate' => $exchange_rate
                    /*'tally_branch_id' => $branch_list*/
                );
                if ($bmr_list != -1) {
                    $bmrcode = $this->MasterModel->_select('word_reportMaker_table', array('id' => $bmr_list), 'code');
                    if ($bmrcode->totalCount > 0) {
                        $bmrdata['code'] = $bmrcode->data->code;
                    }
                }

                $insert = $this->MasterModel->_insert('word_reportMaker_table', $bmrdata);

            }

            if ($insert->status) {

                $response['status'] = 200;
                $response['body'] = "Saved Successfully";
            } else {
                $response['status'] = 201;
                $response['body'] = "Something Went Wrong";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);

    }

    public function getBMRList()
    {
//        $company_id = $this->session->user_session->firm_id;
//        print_r($company_id);die;
        $option = "<option value='-1'>Select Copy Report</option>";
        $getOption = $this->db->query('select * from word_reportMaker_table where status =1');
        if ($this->db->affected_rows() > 0) {
            $optionData = $getOption->result();
            foreach ($optionData as $row) {
                $option .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
            $response['status'] = 200;
            $response['body'] = "Data Found";
            $response['data'] = $option;
        } else {
            $response['status'] = 201;
            $response['body'] = "No Data Found";
            $response['data'] = "<option>No Data Found</option>";
        }
        echo json_encode($response);
    }

    public function getBMRNameList()
    {
        $id = $this->input->post('id');
        $resultObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $id), 'name');
        if ($resultObject->totalCount > 0) {

            $response['status'] = 200;
            $response['body'] = "Data Found";
            $response['data'] = $resultObject->data->name;

        } else {
            $response['status'] = 201;
            $response['body'] = "No Data Found";
            $response['data'] = "";
        }
        echo json_encode($response);
    }

    function historyDesign($id, $type)
    {

        $html = '';

        $resultObject = $this->MasterModel->_rawQuery('select * from product_reason where product_id=(select id from product_master_table where bmr_id=' . $id . ' order by id limit 1)');
        if ($resultObject->totalCount > 0) {

            $html .= '<p>

		<div align="centerc">
			<table border="1" cellspacing="0" cellpadding="0" width="703
			" style="width:527.2pt;border-collapse:collapse;border:none">
		<tbody>
		<tr style="height:9.0pt">
			<td width="114" style="width:85.15pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:9.0pt">
			<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
			line">Sl. No.</span></p>
		</td>
		<td width="144" style="width:1.5in;border:solid windowtext 1.0pt;border-left:
			none;
			padding:0in 5.4pt 0in 5.4pt;height:9.0pt">
		<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
			line">Revision No./Date</span></p>
		</td>
		<td width="132" style="width:99.0pt;border:solid windowtext 1.0pt;border-left:
			none;
			padding:0in 5.4pt 0in 5.4pt;height:9.0pt">
		<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
			line">Supersedes BMR No./Date</span></p>
		</td>
		<td width="313" style="width:235.05pt;border:solid windowtext 1.0pt;border-left:
			none;
			padding:0in 5.4pt 0in 5.4pt;height:9.0pt">
		<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
			line">Reasons for change</span></p>
		</td>
		</tr>';
            $i = 0;
            foreach ($resultObject->data as $row) {
                $i++;
                $date = date('Y-m-d', strtotime($row->created_on));
                $html .= '<tr style="height:28.7pt">
			<td width="114" style="width:85.15pt;border:solid windowtext 1.0pt;border-top:
						none;
						padding:0in 5.4pt 0in 5.4pt;height:28.7pt">
					<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
						line">' . $i . '</span></p>
				</td>
				<td width="144" style="width:1.5in;border-top:none;border-left:none;border-bottom:
				solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:28.7pt">
				<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
					line">' . $date . '</span></p>
				</td>
				<td width="132" style="width:99.0pt;border-top:none;border-left:none;
				border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:28.7pt">
				<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
					line">RIPL/CETB/BMR/' . $date . '</span></p>
				</td>
				<td width="313" style="width:235.05pt;border-top:none;border-left:none;
				border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:28.7pt">
			<p align="center" style="margin-left:.5in;text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
				line">' . $row->reason . '</span></p>
			</td>

			</tr>';
            }
            $html .= '</tbody>
			</table>
			</div>
			<br />
			</p>';

        }

        return $html;
    }

    public function checkBMRId()
    {
        $bmr_id = $this->input->post('bmr_id');
        $resultObject = $this->MasterModel->_select('product_master_table', array('bmr_id' => $bmr_id), 'id');
        if ($resultObject->totalCount > 0) {

            $response['status'] = 200;
            $response['body'] = "Data Found";
            $response['data'] = $resultObject->data->id;
        } else {
            $response['status'] = 201;
            $response['body'] = "No Data Found";

        }
        echo json_encode($response);
    }


    public function getHistoryTable()
    {
        if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
            $id = $this->input->post('report_id');
            $type = $this->input->post('type');
            $html = $this->historyDesign($id, $type);
            $response['status'] = 200;
            $response['body'] = $html;
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function getMaterialTable()
    {
        if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
            $id = $this->input->post('report_id');
            $type = $this->input->post('type');
            $html = $this->materialDesign($id, $type);
            $response['status'] = 200;
            $response['body'] = $html;
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    function materialDesign($id)
    {
        $html = '';
        $resultObject = $this->MasterModel->_rawQuery('select *,(select name from material_master where id=mt.material_id) as material_name  from material_transaction_table mt where product_id=(select id from product_master_table where bmr_id=' . $id . ' order by id limit 1)');
//		print_r($resultObject);exit();
        if ($resultObject->totalCount > 0) {

            $html .= '<p>
<p style="margin-top:0in"><b><u><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">BILL OF MATERIAL</span></u></b><u></u></p>
<p style="margin-top:0in;margin-right:0in;margin-bottom:0in;
margin-left:.25in;margin-bottom:.0001pt;line-height:50%"><b><u><span style="font-size:10.0pt;line-height:50%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;"><span style="text-decoration:none">&nbsp;</span></span></u></b></p>
<div align="centerc">
<table border="1" cellspacing="0" cellpadding="0" width="737
	" style="border-collapse:collapse;border:none">
	<tbody>
		<tr style="height:41.75pt">
			<td width="41" valign="top" style="width:30.65pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">S. No.</span></b></p>
			</td>
			<td width="78" valign="top" style="width:58.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Material</span></b></p>
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Code No.</span></b></p>
			</td>
			<td width="156" valign="top" style="width:116.7pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Name of Material</span></b></p>
			</td>
			<td width="116" valign="top" style="width:86.65pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Grade/</span></b></p>
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Specific Requirement</span></b></p>
			</td>
			<td width="102" valign="top" style="width:76.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Label
			claim (mg/</span></b></p>
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">&nbsp;</span></b></p>
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">tablet)</span></b><b></b></p>
			</td>
			<td width="94" valign="top" style="width:70.85pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Overages</span></b></p>
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">(%)</span></b></p>
			</td>
			<td width="150" valign="top" style="width:112.8pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Qty. for 6,00,000
			Tablets</span></b></p>
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">75.00Kg)</span></b></p>
			</td>
		</tr>';
            $i = 0;
            foreach ($resultObject->data as $row) {
                $i++;
                $date = date('Y-m-d', strtotime($row->created_on));
                $html .= '<tr style="height:19.25pt">
					<td width="41" valign="top" style="width:30.65pt;border:solid windowtext 1.0pt;
					border-top:none;
					padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p style="line-height:150%"><span style="font-size:10.0pt;
					line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $i . '</span></p>
					</td>
					<td width="78" valign="top" style="width:58.5pt;border-top:none;border-left:none;
					border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p style="line-height:150%"><span style="font-size:10.0pt;
					line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:black">' . $row->material_code . '</span></p>
					</td>
					<td width="156" valign="top" style="width:116.7pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p style="line-height:150%"><span style="font-size:10.0pt;
					line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->material_name . '</span></p>
					</td>
					<td width="116" valign="top" style="width:86.65pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p align="center" style="margin-right:-5.4pt;text-align:center;
					line-height:150%"><span style="font-size:10.0pt;line-height:150%;font-family:
					&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->grade . '</span></p>
					</td>
					<td width="102" valign="top" style="width:76.5pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p align="center" style="text-align:center;line-height:150%"><span style="font-size:10.0pt;line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->label . '</span></p>
					</td>
					<td width="94" valign="top" style="width:70.85pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p align="center" style="text-align:center;line-height:150%"><span style="font-size:10.0pt;line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->overages . '</span></p>
					</td>
					<td width="150" valign="top" style="width:112.8pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p align="center" style="text-align:center;line-height:150%"><span style="font-size:10.0pt;line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->qty . '</span></p>
					</td>
				</tr>';
            }
            $html .= '</tbody>
				</table>
				</div>
				<p><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
				line">*30% overage for compensate process loss.</span></p>
				<br />
				</p>';

        }

        return $html;
    }

    function IndentHTML($id, $type)
    {
        $html = '';

        $productionData = $this->MasterModel->_rawQuery('SELECT scheduler_id,(select product_id from production_scheduler where id = p.scheduler_id) as product_id 
								FROM production_scheduler_bmr_report p where id = ' . $id);

        if ($productionData->totalCount > 0) {
            $scheduleId = $productionData->data[0]->scheduler_id;
            $product_id = $productionData->data[0]->product_id;

            $resultObject = $this->MasterModel->_select('schedule_material_mapping mt', array('production_schedule_id' => $scheduleId),
                '*,(select mm.name from material_master mm where mm.id=mt.material_id) as material_name,
			(select actual_qty from production_scheduler where id=mt.production_schedule_id) as plant_qty,
			(select group_concat(arn_no) from indent_material_transaction where schedule_id = mt.production_schedule_id and material_id = mt
			.material_id) as ARN_no,
			(select group_concat((select name from users_master where id in (select qc_updated_by 
			from com_1_material_order where find_in_set(ARN_no,concat(i.arn_no)))))
 			from indent_material_transaction i where schedule_id = mt.production_schedule_id and material_id = mt .material_id) as qc_updated, 
			(SELECT (select name from users_master where id = supplied_by) FROM com_1_material_order 
			where production_schedule_id = mt.production_schedule_id and material_id = mt.material_id and order_status != 1 limit 1) as supplier,
			
			(select name from unit_master where id = mt.label) as main_unit', false);

            if ($resultObject->totalCount > 0) {
                $i = 1;


                $html .= '
				<table border="1" cellspacing="0" cellpadding="0" width="737" style="border-collapse:collapse;border:none">
	<tbody>
		<tr style="page-break-inside:avoid;
			height:.05in">
			<td width="63" rowspan="2" valign="top" style="width:47.55pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Item Code</span></b></p>
			</td>
			<td width="121" rowspan="2" valign="top" style="width:91.05pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Material</span></b></p>
			</td>
			<td width="66" rowspan="2" valign="top" style="width:49.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Std. Qty. in Kgs</span></b></p>
			</td>
			<td width="54" rowspan="2" valign="top" style="width:40.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Qty. Issued</span></b></p>
			</td>
			<td width="66" rowspan="2" valign="top" style="width:49.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">A.R. No. with date</span></b></p>
			</td>
			<td width="170" colspan="3" valign="top" style="width:127.8pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Weighing (Kgs)</span></b></p>
			</td>
			<td width="76" rowspan="2" valign="top" style="width:56.95pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Exp. Date/ Retest Date</span></b></p>
			</td>
			<td width="60" rowspan="2" valign="top" style="width:44.75pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Dispensed</span></b></p>
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">by</span></b></p>
			</td>
			<td width="70" rowspan="2" valign="top" style="width:52.45pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Checked by Q/A</span></b></p>
			</td>
		</tr>
		<tr style="page-break-inside:avoid;height:24.25pt">
			<td width="60" valign="top" style="width:45.25pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:24.25pt">
			<p align="center" style="text-align:center"><b><span style="font-size:9.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Gross Wt.</span></b></p>
			</td>
			<td width="56" valign="top" style="width:42.05pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:24.25pt">
			<p align="center" style="text-align:center"><b><span style="font-size:9.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Tare Wt.</span></b></p>
			</td>
			<td width="54" valign="top" style="width:40.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:24.25pt">
			<p align="center" style="text-align:center"><b><span style="font-size:9.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Net Wt.</span></b></p>
			</td>
		</tr>
				';


                foreach ($resultObject->data as $row) {
                    $unit_weight = $this->MasterModel->UnitRecon($row->unit, $row->label);

                    $reqQrt = ($unit_weight) * ($row->plant_qty);
                    $reqQrt = round($reqQrt, 3);

                    $html .= '<tr style="page-break-inside:avoid;height:14.75pt">
				
					<td width="63" valign="top" style="width:47.55pt;border:solid windowtext 1.0pt;
			border-top:none;
			padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p align="center" style="text-align:center;line-height:150%"><span style="font-size:9.0pt;line-height:150%;font-family:&quot;Verdana&quot;,sans-serif;
			color:black">' . $row->material_code . '</span></p>
			</td>
			<td width="121" valign="top" style="width:91.05pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:150%"><span style="font-size:9.0pt;
			line-height:150%;font-family:&quot;Verdana&quot;,sans-serif">' . $row->material_name . '</span></p>
			</td>
			<td width="66" valign="top" style="width:49.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="margin-right:-5.25pt;line-height:200%"><span style="font-size:9.0pt;line-height:200%;font-family:&quot;Verdana&quot;,sans-serif;
			color:black">' . $unit_weight . ' ' . $row->main_unit . '</span></p>
			</td>
			<td width="54" valign="top" style="width:40.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif;color:black">' . $reqQrt . '</span></p>
			</td>
			<td width="66" valign="top" style="width:49.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $row->ARN_no . '</span></p>
			</td>
			<td width="60" valign="top" style="width:45.25pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $reqQrt . '</span></p>
			</td>
			<td width="56" valign="top" style="width:42.05pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">&nbsp;</span></p>
			</td>
			<td width="54" valign="top" style="width:40.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">&nbsp;</span></p>
			</td>
			<td width="76" valign="top" style="width:56.95pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">&nbsp;</span></p>
			</td>
			<td width="60" valign="top" style="width:44.75pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $row->supplier . '</span></p>
			</td>
			<td width="70" valign="top" style="width:52.45pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $row->qc_updated . '</span></p>
			</td>
		</tr>
									
					</tr>';
                    $i++;
                }
            }
            return $html;

        } else {
            return $html = false;
        }

    }


    public function getAllBMRReport()
    {
        $type = $this->input->post('type');
        $id = $this->input->post('report_id');
        $finalhtml = '';
        if ($id != null && $id != '') {
            $finalhtml = $this->allBMRReports($id, $type);

            $response['status'] = 200;
            $response['body'] = $finalhtml;
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);

    }

    function allBMRReports($id, $type, $queryParam)
    {
        $historyTable = '';
        $materialTable = '';
        $table = 'word_reportMaker_table c';

        if ($id != null && $id != '') {
            $resultObject = $this->MasterModel->_select($table, array('id' => $id), '*,(select wt.name from word_reportMaker_table wt where wt.id=c.id) as name');

            $code = $resultObject->data->code;
            $bmr_name = $resultObject->data->name;
            $jsonDecoded = json_decode($code, true);

            $entries = $jsonDecoded[0]['pages'];
            $finalhtml = '';
            foreach ($entries as $jsondata) {
                $datasetarr = $jsondata['dataset'];
                $htmlcode = $jsondata['html_code'];
                $keypair = $jsondata['keyPairs'];
                $jsonarray = array();
                $historydata = array();
                if (!empty($datasetarr)) {

                    foreach ($datasetarr as $ds) {
                        foreach ($ds as $key => $val) {
                            if ($val != '') {
                                $jsonarray[$key] = $val['value'];
                            }
                        }
                    }
                }

                foreach ($keypair as $key => $val) {
//                    print_r($val);die;
                    $replacedata = '';
                    $historydata[$val[0]] = $val[1];
                    if (array_key_exists($val[0], $jsonarray)) {

                        $replacedata = $jsonarray[$val[0]];
                        if (strpos($val[10], '#') !== false) {
                            $resp = $this->getColNames($val[10], '', 1, $queryParam, $id, $jsondata['page_id'], $type);
                            if ($resp['status'] === 200) {
                                $replacedata = $resp['data'];
                            }
                        }

                        $htmlcode = str_replace('<span style="background-color: rgb(255, 255, 0);">' . $val[0] . '</span>', $replacedata, $htmlcode);
                    } else {
                        if (strpos($val[10], '#') !== false) {
                            $resp = $this->getColNames($val[10], '', 1, $queryParam, $id, $jsondata['page_id'], $type);
                            if ($resp['status'] === 200) {

                                $replacedata = $resp['data'];
                                if($val[10] == '#salary_table'){
                                    $replacedata = json_decode($replacedata);
                                }
                            }
                        }

                        $htmlcode = str_replace('<span style="background-color: rgb(255, 255, 0);">' . $val[0] . '</span>', $replacedata, $htmlcode);
                    }

                }
                $finalhtml .= $htmlcode;
            }
//            echo $finalhtml;
//            exit();
            return $finalhtml;
        }
    }

    public function getIndentTable()
    {
        if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
            $id = $this->input->post('report_id');
            $type = $this->input->post('type');
            $html = $this->IndentHTML($id, $type);
            $response['status'] = 200;
            $response['body'] = $html;
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function getProcessFlowChart()
    {
        if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
            $id = $this->input->post('report_id');
            $type = $this->input->post('type');
            $html = $this->processFlowChart($id, $type);
            $response['status'] = 200;
            $response['body'] = $html;
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    function processFlowChart($id, $type)
    {
        $processChart = '';
        $resultObject = $this->MasterModel->_rawQuery('select name from process_master where id in (select process_id from schedule_procedure_mapping where production_schedule_id=(select scheduler_id from production_scheduler_bmr_report where id="' . $id . '"))');
        if ($resultObject->totalCount > 0) {
            $processChart .= '<div class="tree horizontal text-center" id="processTree">';
            $i = 1;
            foreach ($resultObject->data as $row) {
                if ($i != 1) {
                    $processChart .= '<div class="mainLi" style="list-style: none;"><span class="d-block">
									<img style="max-width: 80%; height: 32px;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDQ3Ni40OTIgNDc2LjQ5MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDc2LjQ5MiA0NzYuNDkyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8cG9seWdvbiBwb2ludHM9IjI1My4yNDYsMzM5Ljk1MiAyNTMuMjQ2LDAgMjIzLjI0NiwwIDIyMy4yNDYsMzM5Ljk1MiAxMDEuNzA3LDMzOS45NTIgMjM4LjI0Niw0NzYuNDkyIDM3NC43ODUsMzM5Ljk1MiAiLz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjwvc3ZnPg0K" />
									</span></div>
								';
                }
                $processChart .= '<div class="mainLi" style="list-style: none;">
									<div class="processNode" style="padding: 2px 20px; display: inline-block;border: solid 1px black;border-radius:10px;font-size: 12px;"><div class=""></div>' . $row->name . '</div>
									
								</div>';
                $i++;
            }
            $processChart .= '</div>';
        }
        return $processChart;
    }

    public function fetch_wordReport()
    {
        $temp_id = $this->input->post("temp_id");
        $item_id = $this->input->post("item_id");
        $board_list = $this->input->post("board_list");
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['emp_id']);
        }

        $order_by = array();
        foreach (explode(",", $temp_id) as $temp) {
            array_push($order_by, "'" . $temp . "'");
        }
        $data1 = $this->MasterModel->_rawQuery('select * from production_scheduler_bmr_report where find_in_set(report_id,"' . $temp_id . '") and item_id="' . $item_id . '" and board_id="' . $board_list . '" ORDER BY FIELD(report_id,' . implode(",", $order_by) . ')');
        // print_r($data1);exit();
        $response['query'] = $data1->last_query;
        if ($data1->totalCount > 0) {
            $response['status'] = 200;
            $response['body'] = $data1->data;
        } else {
            $response['status'] = 201;
            $response['body'] = array();
        }
        echo json_encode($response);
    }

    public function fetch_templatesReportData()
    {
        if (!is_null($this->input->post("temp_id"))) {
            $temp_id = $this->input->post("temp_id");
            $resultObject = $this->MasterModel->_rawQuery('select *,(select wt.name from word_reportMaker_table wt where wt.id=c.template_id) as name,
         (select group_concat(user_name) from user_header_all where user_id in (select user_id from tally_maker_checker where invoice_id in (select wrt.id from word_reportMaker_table wrt where wrt.id=c.template_id) and type=2)) as checker_user,
         (select ifnull(wt.exchange_rate,1) from word_reportMaker_table wt where wt.id=c.template_id) as exchange_rate
          from customer_invoice_table c where id="' . $temp_id . '"');

            $checkStatusView = $this->MasterModel->_rawQuery('select *,(select user_name from user_header_all where user_id=cl.checked_by) as user_name from tally_checker_list cl
			 where cl.invoice_id="' . $temp_id . '"');
            $checkStatusData = '';
            if ($checkStatusView->totalCount > 0) {
                $checkStatusData = $checkStatusView->data;
            }

            $isChecker = 0;
            if (!is_null($this->input->post('temp_invoice_id'))) {
//                $user_id = $this->session->user_session->user_id;
                $session_data = $this->session->userdata('login_session');
                if (is_array($session_data)) {
                    $data['session_data'] = $session_data;
                    $user_id = ($session_data['emp_id']);
                }
                $tempInvoiceId = $this->input->post('temp_invoice_id');
                //$checkUser = $this->MasterModel->_select('tally_maker_checker',array('invoice_id' => $tempInvoiceId,'user_id' => $user_id,'type' => 2),'*',false);
                $checkUser = $this->MasterModel->_rawQuery('select * from tally_maker_checker where invoice_id in (select template_id from customer_invoice_table where id="' . $temp_id . '") and user_id="' . $user_id . '" and type=2');
                if ($checkUser->totalCount > 0) {
                    $isChecker = 1;
                }
                /*-------- For Maker User -------*/
                $makerUser = $this->MasterModel->_rawQuery('select *,(select created_by from customer_invoice_table where id="' . $temp_id . '") as createdByUser from tally_maker_checker where invoice_id in (select template_id from customer_invoice_table where id="' . $temp_id . '") and user_id="' . $user_id . '" and type=1');
                if ($makerUser->totalCount > 0) {
                    $isMaker = 1;
                    $makerID = $makerUser->data[0]->createdByUser;
                } else {
                    $isMaker = 0;
                    $makerID = 0;
                }
            }

            if ($resultObject->totalCount > 0) {
                $response['status'] = 200;
                $response['body'] = $resultObject->data[0];
                $response['isChecker'] = $isChecker;
                $response['isMaker'] = $isMaker;
                $response['makerID'] = $makerID;
                $response['checkStatusData'] = $checkStatusData;
            } else {
                $response['status'] = 201;
                $response['body'] = 'No data Found';
            }
        } else {
            $response['status'] = 201;
            $response['body'] = 'Required Parameter Missing';
        }
        echo json_encode($response);
    }

    public function changeStatus()
    {
        if (!is_null($this->input->post('id')) && $this->input->post('status') != "") {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            if ($status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            $resultObject = $this->MasterModel->_update('word_reportMaker_table', array('status' => $status), array('id' => $id));
            if ($resultObject->status > 0) {
                $response['status'] = 200;
                $response['body'] = "Status Change Successfully";
            } else {
                $response['status'] = 201;
                $response['body'] = "No Data Found";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function getColumnNames()
    {

        $query = $this->input->post('query');
        $value = $this->input->post('inputValue');
        $type = $this->input->post('type');
        $params = $this->input->post('params');
        $bmr_id = $this->input->post('id');
        $page_id = $this->input->post('page_id');
        $page_type = $this->input->post('page_type');

        $response = $this->getColNames($query, $value, $type, $params, $bmr_id, $page_id, $page_type);
        echo json_encode($response);
    }

    public function getColNames($query, $value, $type, $params, $bmr_id, $page_id, $page_type)
    {
        if ($query != null && $query != '') {
            $paramData = json_decode(base64_decode($params));

            if ($paramData != null && $paramData != 'NULL') {
                foreach ($paramData as $key => $value) {
                    $query = str_replace('#' . $key, $value, $query);
                }
            }

            $response['status'] = 200;
            $response['data'] = $query;
            $response['body'] = "Data Found";
        } else {
            if ($type == 1) {
                $option = '';
            } else {
                $option = "<option value='-1' selected>No Data Found</option>";
            }
            $response['data'] = $option;
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        return $response;
    }

    public function getAwsLinkToDownload()
    {
        if (!is_null($this->input->post('file')) && $this->input->post('file') != "") {
            $files = $this->input->post('file');
            $fileArr = explode(',', $files);
            $fileArray = array();
            foreach ($fileArr as $r) {
                $splitName = explode('/', $r);
                $ext = explode('.', $splitName[count($splitName) - 1]);
                $file_name_r = $splitName[count($splitName) - 1];
                if (is_numeric(substr($splitName[count($splitName) - 1], 0, 10))) {
                    $file_name_r = substr($file_name_r, 11);
                }

                $urlFile = new stdClass();
                $urlFile->urlPath = $this->AwsModel->getPreAssignURL($r);
                $urlFile->filename = $file_name_r;
                $urlFile->originalfilename = $r;
                array_push($fileArray, $urlFile);
            }
            if (count($fileArray) > 0) {
                $response['status'] = 200;
                $response['body'] = $fileArray;
            } else {
                $response['status'] = 201;
                $response['body'] = $fileArray;
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function uploadInvoiceForm()
    {
        if ($this->input->post('wordTemplate') != null && $this->input->post('wordTemplate') != '' && $this->input->post('toCustomer') != null && $this->input->post('toCustomer') != ''
            && $this->input->post('invoiceNo') != '' && $this->input->post('invoiceNo') != null && $this->input->post('financial_year') != '' && $this->input->post('financial_year') != null) {
            $template_id = $this->input->post('wordTemplate');
            $customer_id = $this->input->post('toCustomer');
            $tallyCompany = $this->input->post('tallyCompany');
            $tallyBranch = $this->input->post('tallyBranch');
            $invoiceNo = $this->input->post('invoiceNo');
            $financial_year = $this->input->post('financial_year');
//            $user_id = $this->session->user_session->user_id;
            $session_data = $this->session->userdata('login_session');
            if (is_array($session_data)) {
                $data['session_data'] = $session_data;
                $user_id = ($session_data['emp_id']);
            }
            $html = null;
            $invoice_status = 7; ///---(1->pending,2->approved,3->final,4->reject,5->delete,6->paid,7->draft);

            $checkInvoice = $this->MasterModel->_select('customer_invoice_table', array('invoice_no' => $invoiceNo), '*', false);
            if ($checkInvoice->totalCount > 0) {
                $response['status'] = 201;
                $response['body'] = "Invoice No Already Exists";
                echo json_encode($response);
                exit();
            }

            $seletcObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $template_id), 'code');
            if ($seletcObject->totalCount > 0) {
                $html = $seletcObject->data->code;
            }

            $data = array('template_id' => $template_id, 'to_customer' => $customer_id, 'invoice_no' => $invoiceNo,
                'created_on' => date('Y-m-d H:i:s'), 'created_by' => $user_id, 'html' => $html, 'tally_company_id' => $tallyCompany,
                'tally_branch_id' => $tallyBranch, 'financial_year' => $financial_year, 'invoice_status' => $invoice_status);
            $result = $this->MasterModel->_insert('customer_invoice_table', $data);
            if ($result) {
                $response['status'] = 200;
                $response['body'] = "Insert Successfully";
            } else {
                $response['status'] = 201;
                $response['body'] = "Invoice Not Created";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function getInvoiceTemplates()
    {
        $tallyCompany = $this->input->post('tallyCompany');
        $tallyBranch = $this->input->post('tallyBranch');
//        $user_id = $this->session->user_session->user_id;
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['emp_id']);
        }
        $resultObject = $this->MasterModel->_rawQuery("select * from word_reportMaker_table where id in (select invoice_id from tally_maker_checker where user_id ='" . $user_id . "' and type=1) and tally_company_id='" . $tallyCompany . "' and tally_branch_id = '" . $tallyBranch . "' and from_tally = 2 and status = 1");

        $option = '<option value="" selected disabled>Select Template</option>';
        if ($resultObject->totalCount > 0) {
            foreach ($resultObject->data as $row) {
                $option .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
            $response['status'] = 200;
            $response['body'] = $option;
        } else {
            $response['status'] = 201;
            $response['body'] = $option;
        }
        echo json_encode($response);
    }



    public function getCompanyListOption()
    {
//        $company_id = $this->session->user_session->firm_id;
        $option = "<option value='-1'>Select Company</option>";
        $getOption = $this->MasterModel->_select('partner_header_all', array('firm_type' => 'associate', 'firm_activity_status' => 'A'), '*', false);

        if ($getOption->totalCount > 0) {
            foreach ($getOption->data as $row) {
                $option .= '<option value="' . $row->firm_id . '">' . $row->firm_name . '</option>';
            }

            $response['status'] = 200;
            $response['body'] = "Data Found";
            $response['data'] = $option;

        } else {
            $response['status'] = 201;
            $response['body'] = "No Data Found";
            $response['data'] = "<option>No Data Found</option>";
        }
        echo json_encode($response);
    }

    /*--------- New code for generate salary slip Using word report master model -----------*/

    public function salary_report($user_id = '', $year = '', $month = '')
    {
        $data['prev_title'] = "Salary slip";
        $data['page_title'] = "Salary slip";
        $data['user_id'] = $user_id;
        $data['year'] = $year;
        $data['month'] = $month;
        $firm_idData = $this->MasterModel->_select('user_header_all', array('user_id' => $user_id), 'firm_id', true);
        if ($firm_idData->totalCount > 0) {
            $firm_id = $firm_idData->data->firm_id;
            $data['firm_id'] = $firm_id;
        }

        //$data['customer_details'] = $this->htmltopdf_model->fetch_single_details($user_id, $year, $month);
        $this->load->view('human_resource/View_salary_slip', $data);
    }

    public function fetch_salaryReportData()
    {
        if (!is_null($this->input->post('user_id')) && $this->input->post('user_id') !== '' && $this->input->post('firm_id') !== null && $this->input->post('firm_id') !== '') {
            $user_id = $this->input->post('user_id');
            $firm_id = $this->input->post('firm_id');
            $year = $this->input->post('year');
            $month = $this->input->post('month');

            $resultObject = $this->MasterModel->_rawQuery('select *,(select wt.firm_name from partner_header_all wt where wt.firm_id="' . $firm_id . '") as company_name
          from word_reportMaker_table c where company_id="' . $firm_id . '" and status=1 limit 1');
            if ($resultObject->totalCount > 0) {
                $user_data = $this->fetch_single_details($user_id, $year, $month);
                $reportData = $resultObject->data[0];
                $userData = $user_data;
                $response['status'] = 200;
                $response['body'] = $reportData;
                $response['user_data'] = $userData;

            } else {
                $response['status'] = 201;
                $response['body'] = 'No data Found';
            }
        } else {
            $response['status'] = 201;
            $response['body'] = 'Required Parameter Missing';
        }
        echo json_encode($response);
    }

    function fetch_single_details1($userid, $year, $month)
    {

        $leave_days = 0;
        $this->db->where('user_id', $userid);
        $data = $this->db->get('user_header_all');
        $data_desig = $data->row();
        $desig_id = $data_desig->designation_id;
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
        if ($present_days == 0) {
            $qq = $this->db->query("select * from manual_staff_attendance where user_id='$userid' AND month='$month'  AND year='$year'")->row();
            if ($this->db->affected_rows() > 0) {
                $late_days = $qq->late;
                $absent_days = $qq->absent_days;
                $present_days = $qq->present_days;
                $leave_days = $qq->leave_days;
            }
        } else {
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
        $qr2 = $this->db->query("select count(*) as weekoff from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=0  AND Year(date)='$year'")->row();
        $weekoff = $qr2->weekoff;
        //holidays
        $qr3 = $this->db->query("select count(*) as holidays from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=1  AND Year(date)='$year'")->row();
        $holidays = $qr3->holidays;
        $output = '';
        //leave days
        $present_days = $present_days;
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
        foreach ($qra1 as $rw) {

            $deduction_types[] = $rw->salary_type;
            $deduction_std[] = $rw->std_amt;
            $deduction_amt[] = $rw->amt;
        }
        foreach ($data->result() as $row) {
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
            //Salary Lop
            $stdamt = $this->db->query("select sum(std_amt) as std_amt from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
            $std_amt = $stdamt->std_amt;
            $deduction_types[] = "Loss Of Pay";
            $deduction_amt[] = $std_amt - $type1;
            $deduction_std[] = $std_amt - $type1;
            // $array1 = array('Total month days', 'Present Days', 'Week Off', 'Holidays', 'Leaves');
            //$array2 = array($month_days, $present_days, $weekoff, $holidays, $leave_days);
            $sal = count($sal_types);
            $ded = count($deduction_amt);
            //$arr = count($array1);
            $ar = array("P", "WO", "Holidays", "Leaves", "Pay Days");
            $aap = count($ar);
            $count = max($sal, $ded, $aap);
            $month_name = date("F", mktime(0, 0, 0, $month, 10));

            $doj = '';
            if ($row->date_of_joining != '0000-00-00 00:00:00') {
                $doj = date('d/m/Y', strtotime($row->date_of_joining));
            }

            $userData = new stdClass();
            $userData->employee_name = $row->user_name;
            $userData->user_code = $row->user_id;
            $userData->spouse_name = $row->spouse_name;
            $userData->department = $row->department_name;
            $userData->designation = $designation_name;
            $userData->uan = $row->UAN_no;
            $userData->date_of_join = $doj;
            $userData->shift_applicable = $shift_applicable1;
            $userData->pan_no = $row->pan_no;
            $userData->gender = $gender1;
            $userData->adhar_no = $row->adhar_no;
            $userData->dob = date('d/m/Y', strtotime($row->date_of_birth));
            $userData->address = $row->address;
            $userData->bank_name = $row->bank_name;
            $userData->account_no = $row->account_no;
            $userData->salary_month = $month_name;

        }

        $output .= '<style>


table {  
            font-family: arial, sans-serif;   
            width: 100%;  
            border-collapse: collapse;
        }
         td, th {  
            text-align: left;  
            padding: 8px;  
        }  
</style>

<table class="table " style="width: 100%">
';
        $output .= '
		<thead style="background-color:#595959">
		<th style="border: 1px solid black; color:white" colspan="2">Attendance</th>
    <th style="border: 1px solid black; color:white" width="30%">Heads of income</th>
    <th style="border: 1px solid black; color:white">Gross</th>
    <th style="border: 1px solid black; color:white">Earnings</th>
	<th style="border: 1px solid black; color:white" colspan="2">Deduction</th>
	</thead>';
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

            $output .= '<tr class="separated" style="border: 1px  black;">
     <td style="border: 1px solid black;">'.$ar[$k].'</td>
    <td style="border: 1px solid black;">'.$new[$k].'</td>
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
    <td style="border: 1px solid black;">' . array_sum($sal_types_std) . '</td>
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
        $number = $c;
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
        $userData->salary_table = json_encode($output);

        return $userData;
    }


    function fetch_single_details($userid, $year, $month) {

        $leave_days = 0;
        $this->db->where('user_id', $userid);
        $data = $this->db->get('user_header_all');
        $data_desig = $data->row();
        $desig_id = $data_desig->designation_id;
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
        $userData = new stdClass();

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
        $qr2 = $this->db->query("select count(*) as weekoff from holiday_master_all where firm_id='$firm_id' AND Month(date)='$month' AND category=0  AND Year(date)='$year'")->row();
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
        foreach ($qra1 as $rw) {

            $deduction_types[] = $rw->salary_type;
            $deduction_std[] = $rw->std_amt;
            if($rw->salary_type == "Professional Tax" && $month == 3){
                $deduction_amt[] = 300;
            }else{
                $deduction_amt[] = $rw->amt;
            }
//            $deduction_amt[] = $rw->amt;
        }
        foreach ($data->result() as $row) {
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
            //Salary Lop
            $stdamt = $this->db->query("select sum(std_amt) as std_amt from t_salary_staff where user_id='$userid' AND month='$month' AND category = 1 AND year='$year'")->row();
            $std_amt = $stdamt->std_amt;
            $deduction_types[] = "Loss Of Pay";
            $deduction_amt[] = $std_amt - $type1;
            $deduction_std[] = $std_amt - $type1;
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
            if($row->date_of_joining != '0000-00-00 00:00:00'){
                $doj = date('d/m/Y',strtotime($row->date_of_joining));
            }

            $userData->employee_name = $row->user_name;
            $userData->user_code = $row->user_id;
            $userData->spouse_name = $row->spouse_name;
            $userData->department = $row->department_name;
            $userData->designation = $designation_name;
            $userData->uan = $row->UAN_no;
            $userData->date_of_join = $doj;
            $userData->shift_applicable = $shift_applicable1;
            $userData->pan_no = $row->pan_no;
            $userData->gender = $gender1;
            $userData->adhar_no = $row->adhar_no;
            $userData->dob = date('d/m/Y', strtotime($row->date_of_birth));
            $userData->address = $row->address;
            $userData->bank_name = $row->bank_name;
            $userData->account_no = $row->account_no;
            $userData->salary_month = $month_name;

        }

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
    <td style="border: 1px solid black;">' . array_sum($sal_types_std) . '</td>
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
        $number = $c;

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


        $userData->salary_table = json_encode($output);

        return $userData;
    }


    /*---------END New code for generate salary slip Using word report master model -----------*/


}
