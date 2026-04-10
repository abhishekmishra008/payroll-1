<?php

/**
 * Description of ExcelController
 *
 * @author User
 */
class ExcelController extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('excel');
        $this->load->model('customer_model');
    }

    function index() {
        $data['prev_title'] = "Customer";
        $data['page_title'] = "Customer";
        $data['logo'] = "";
        $data['firm_name_nav'] = "";
        $this->load->view('Import_excel_formate', $data);
    }

    /*
     * @params $column array => containe column name $apply_to_no_row => how many row will be formatted, $filename=> file name start with and container current data.
     * @return excel file as output
     */

    public function create_excel($columnArray, $apply_to_no_rom, $filename) {
//        Creating a new workbook
        $objPHPExcel = new PHPExcel();
//        Adding a new Worksheet
        $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Employee');
        $objPHPExcel->addSheet($myWorkSheet, 0);
        // column name user_name mobile_no state city email address country
        foreach ($columnArray as $index => $column) {
            if (array_key_exists("name", $column)) {
                $myWorkSheet->getCellByColumnAndRow($index, 1)->setValue($column["name"]);
                $myWorkSheet->getColumnDimensionByColumn($index)
                        ->setAutoSize(true);
                $myWorkSheet->getStyleByColumnAndRow($index, 1)->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFE8E5E5');
                $myWorkSheet->getStyleByColumnAndRow($index, 1)->getFont()->setBold(true);
            }

            for ($i = 2; $i < $apply_to_no_rom; $i++) {
                $objValidation = $myWorkSheet->getCellByColumnAndRow($index, $i)->getDataValidation();

                if (array_key_exists("data_validation_type", $column)) {
                    $objValidation->setType($column["data_validation_type"]);
                }
                if (array_key_exists("error_style", $column)) {
                    $objValidation->setErrorStyle($column["error_style"]);
                }
                if (array_key_exists("data_type", $column)) {
                    switch ($column["data_type"]) {
                        case 1:$myWorkSheet->getCellByColumnAndRow($index, $i)->getStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
                            break;
                        case 2:$myWorkSheet->getCellByColumnAndRow($index, $i)->getStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                            break;
                        case 3:$myWorkSheet->getCellByColumnAndRow($index, $i)->getStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
                            break;
                    }
                }
                if (array_key_exists("allow_blank", $column)) {
                    $objValidation->setAllowBlank($column["allow_blank"]);
                }
                if (array_key_exists("is_input_message", $column)) {
                    $objValidation->setShowInputMessage($column["is_input_message"]);
                }
                if (array_key_exists("is_error_message", $column)) {
                    $objValidation->setShowErrorMessage($column["is_error_message"]);
                }
                if (array_key_exists("drop_down", $column)) {
                    $objValidation->setShowDropDown($column["drop_down"]);
                }
                if (array_key_exists("error_title", $column)) {
                    $objValidation->setErrorTitle($column["error_title"]);
                }
                if (array_key_exists("error_message", $column)) {
                    $objValidation->setError($column["error_message"]);
                }
                if (array_key_exists("promt_title", $column)) {
                    $objValidation->setPromptTitle($column["promt_title"]);
                }
                if (array_key_exists("promt_message", $column)) {
                    $objValidation->setPrompt($column["promt_message"]);
                }
                if (array_key_exists("set_formula", $column)) {
                    $str = implode(",", $column["set_formula"]);
                    $objValidation->setFormula1('"' . $str . '"');
                }
            }
        }

        // header setting
        $date = date("d-m-y");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"' . $date . '.xlsx"');
        header('Cache-Control: max-age=0');
        $file = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $file->save('php://output');
    }

    /*
     * @params $header_array=> all column name , $data_type_arrray=>all column data type , $worksheet _instance => active worksheet instance required to read data
     * @return error if there is not  proper sequeance and data type as you specify  other wise return excel data in array formate.
     */

    function excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance) {
        foreach ($worksheet_instance->getRowIterator(1) as $row => $rowInstance1) {
            $cellIterator1 = $rowInstance1->getCellIterator();
            $cellIterator1->setIterateOnlyExistingCells(FALSE);
            $index = 0;
            foreach ($cellIterator1 as $columInstance1) {
//                echo $header_array[$index], "|", $columInstance1->getValue(), var_dump($header_array[$index] !== $columInstance1->getValue());
                if ($header_array[$index] !== $columInstance1->getValue()) {
                    return "Excel Column Formate was Changed.";
                }
                $index++;
            }
            break;
        }



        $highestColumn = $worksheet_instance->getHighestColumn();
        $highestRow = $worksheet_instance->getHighestRow();
        $sheet_data = array();

        foreach ($worksheet_instance->getRowIterator(2) as $row => $rowInstance) {
            $data = array();
            $cellIterator = $rowInstance->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            $column = 0;
            foreach ($cellIterator as $columInstance) {

                if (in_array("number", $data_type_array)) {
                    if (in_array($column, $data_type_array["number"])) {
                        if (!is_numeric($columInstance->getValue())) {
                            return "Error at Column no " . $column . " and row no " . $row . " invalid data required number formated data";
                        }
                    }
                }

                if (in_array("string", $data_type_array)) {
                    if (in_array($column, $data_type_array["string"])) {
                        if (!is_string($columInstance->getValue())) {
                            return "Error at Column no " . $column . " and row no " . $row . " invalid data required String formated data";
                        }
                    }
                }

                if (in_array("datetime", $data_type_array)) {
                    if (in_array($column, $data_type_array["datetime"])) {
                        if (is_numeric($columInstance->getValue())) {
                            $UNIX_DATE = ( $columInstance->getValue() - 25569) * 86400;
                            $date = gmdate("d-m-Y", $UNIX_DATE);
                        } else {
                            $date = date(PHPExcel_Style_NumberFormat::toFormattedString($columInstance->getValue(), 'DD-MM-YYYY'));
                        }
                        if ($date) {
                            return "Error at Column no " . $column . " and row no " . $row . " invalid data required datetime formated data";
                        }
                    }
                }

                if (is_array($compulsary_column)) {
                    if (in_array($column, $compulsary_column)) {
                        if (is_null($columInstance->getValue())) {
                            break;
                        } else {
                            if (is_numeric($columInstance->getValue())) {
                                $UNIX_DATE = ( $columInstance->getValue() - 25569) * 86400;
                                $date = gmdate("d-m-Y", $UNIX_DATE);
                            } else {
                                $date = "";
                            }
                            if ($date != "") {
                                $data[$header_array[$column]] = $columInstance->getValue();
                            } else {
                                $data[$header_array[$column]] = PHPExcel_Style_NumberFormat::toFormattedString($columInstance->getValue(), 'DD-MM-YYYY');
                            }
                        }
                    } else {
                        if (is_numeric($columInstance->getValue())) {
                            $UNIX_DATE = ( $columInstance->getValue() - 25569) * 86400;
                            $date = gmdate("d-m-Y", $UNIX_DATE);
                        } else {
                            $date = "";
                        }
                        if ($date != "") {
                            $data[$header_array[$column]] = $columInstance->getValue();
                        } else {
                            $data[$header_array[$column]] = PHPExcel_Style_NumberFormat::toFormattedString($columInstance->getValue(), 'DD-MM-YYYY');
                        }
                    }
                } else {
                    if (is_null($columInstance->getValue())) {
                        break;
                    } else {
                        switch ($columInstance->getValue()) {
                            case is_numeric($columInstance->getValue()):
                                if (in_array($column, $data_type_array["datetime"])) {
                                    $UNIX_DATE = ( $columInstance->getValue() - 25569) * 86400;
                                    $date = gmdate("d-m-Y", $UNIX_DATE);
                                    $data[$header_array[$column]] = $date;
                                } else {
                                    $data[$header_array[$column]] = $columInstance->getValue();
                                }
                                break;
                            case is_string($columInstance->getValue()):
                                if (in_array($column, $data_type_array["datetime"])) {
                                    if ($columInstance->getValue() != "NA") {
                                        $data[$header_array[$column]] = PHPExcel_Style_NumberFormat::toFormattedString($columInstance->getValue(), 'DD-MM-YYYY');
                                    } else {
                                        $data[$header_array[$column]] = "0000-00-00 00:00:00";
                                    }
                                } else {
                                    if ($columInstance->getValue() != "NA") {
                                        $data[$header_array[$column]] = $columInstance->getValue();
                                    } else {
                                        $data[$header_array[$column]] = "0000-00-00 00:00:00";
                                    }
                                }
                                break;
                            default:
                                $data[$header_array[$column]] = $columInstance->getValue();
                        }
                    }
                }


                $column++;
            }
            if (count($data) > 0) {
                array_push($sheet_data, $data);
            }
        }

        return $sheet_data;
    }

    public function download_checklist() {

        $columnArray = array();

        $checklist_description = array(
            "name" => "Checklist Description",
            "data-type" => 3);
        array_push($columnArray, $checklist_description);

        $days_need_to_be_complete = array(
            "name" => "Days Need To Be Complete",
            "data-type" => 3);
        array_push($columnArray, $days_need_to_be_complete);

        $this->create_excel($columnArray, 250, 'Checklist');
    }

    public function employee_excel() {

        $columnArray = array();

        $designation = array(
            "name" => "Branch Name",
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'data_validation_type' => PHPExcel_Cell_DataValidation::TYPE_LIST, 'allow_blank' => false, 'is_input_message' => true,
            'is_error_message' => true,
            'drop_down' => true,
            'error_title' => "Select Branch Name",
            'error_message' => "Please Select Branch Name",
            'promt_title' => "Select Branch Name",
            'promt_message' => "Please Select Branch Name",
            'set_formula' => array('B1', 'B2', 'B3', 'B4'));
        array_push($columnArray, $designation);

        $user_name = array(
            "name" => "Name",
            "data_type" => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Name',
            'promt_message' => 'Please Enter Employee Name');
        array_push($columnArray, $user_name);

        $mobile_number = array(
            "name" => "Mobile",
            "data_type" => 2,
            'is_input_message' => true,
            'promt_title' => 'Employee Mobile',
            'promt_message' => 'Please Enter Employee Mobile');
        array_push($columnArray, $mobile_number);

        $state = array(
            "name" => "State",
            "data_type" => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee State',
            'promt_message' => 'Please Enter Employee State');
        array_push($columnArray, $state);

        $city = array(
            "name" => "City",
            "data_type" => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee City',
            'promt_message' => 'Please Enter Employee City');
        array_push($columnArray, $city);
        $email = array(
            "name" => "Email",
            "data_type" => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Email',
            'promt_message' => 'Please Enter Employee Email');
        array_push($columnArray, $email);

        $address = array(
            "name" => "Address",
            "data_type" => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Address',
            'promt_message' => 'Please Enter Employee Address');
        array_push($columnArray, $address);

        $country = array(
            "name" => "Country",
            "data_type" => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Country',
            'promt_message' => 'Please Enter Employee Country');
        array_push($columnArray, $country);

        $designation = array(
            "name" => "Designation",
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'data_validation_type' => PHPExcel_Cell_DataValidation::TYPE_LIST, 'allow_blank' => false, 'is_input_message' => true,
            'is_error_message' => true,
            'drop_down' => true,
            'error_title' => "Select Designation",
            'error_message' => "Please Select Designation",
            'promt_title' => "Select Designation",
            'promt_message' => "Please Select Designation",
            'set_formula' => array('Deg1', 'Deg2', 'Deg3', 'Deg4'));
        array_push($columnArray, $designation);

        $senier_employee = array(
            "name" => "Senier Employee",
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'data_validation_type' => PHPExcel_Cell_DataValidation::TYPE_LIST, 'allow_blank' => false, 'is_input_message' => true,
            'is_error_message' => true,
            'drop_down' => true,
            'error_title' => "Select Senier Employee",
            'error_message' => "Please Select Senier Employee",
            'promt_title' => "Select Senier Employee",
            'promt_message' => "Please Select Senier Employee",
            'set_formula' => array('u1', 'u2', 'u3', 'u4'));
        array_push($columnArray, $senier_employee);

        $work_on_service = array(
            "name" => "Work On Service",
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'data_validation_type' => PHPExcel_Cell_DataValidation::TYPE_LIST, 'allow_blank' => false, 'is_input_message' => true,
            'is_error_message' => true,
            'drop_down' => true,
            'error_title' => "Select Work On Service",
            'error_message' => "Please Select Work On Service",
            'promt_title' => "Select Work On Service",
            'promt_message' => "Please Select Work On Service",
            'set_formula' => array('Accounting', 'Software', 'Hardware', 'Hr'));
        array_push($columnArray, $work_on_service);

        $date_of_joining = array(
            "name" => "Date Of Joining",
            'data_type' => 2,
            'is_input_message' => true,
            'promt_title' => 'Employee Date Of Joining',
            'promt_message' => 'Please Enter Employee Date Of Joining');
        array_push($columnArray, $date_of_joining);

        $password = array(
            "name" => "Password",
            'data_type' => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Password',
            'promt_message' => 'Please Enter Password');
        array_push($columnArray, $password);


        $probation_period_start_date = array(
            "name" => "Probation Period Start Date",
            'data_type' => 2,
            'is_input_message' => true,
            'promt_title' => 'Employee Probation Period Start Date',
            'promt_message' => 'Please Enter Probation Period Start Date');
        array_push($columnArray, $probation_period_start_date);

        $probation_period_end_date = array(
            "name" => "Probation Period End Date",
            'data_type' => 2,
            'is_input_message' => true,
            'promt_title' => 'Employee Probation Period End Date',
            'promt_message' => 'Please Enter Probation Period End Date');
        array_push($columnArray, $probation_period_end_date);

        $training_period_start_date = array(
            "name" => "Training Period Start Date",
            'data_type' => 2,
            'is_input_message' => true,
            'promt_title' => 'Employee Training Period Start Date',
            'promt_message' => 'Please Enter Training Period Start Date');
        array_push($columnArray, $training_period_start_date);

        $training_period_end_date = array(
            "name" => "Training Period End Date",
            'data_type' => 2,
            'is_input_message' => true,
            'promt_title' => 'Employee Training Period End Date',
            'promt_message' => 'Please Enter Training Period End Date');
        array_push($columnArray, $training_period_end_date);

        $Task_Creation_Permission = array(
            "name" => "Task Creation Permission",
            'data_type' => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Task Creation Permission',
            'promt_message' => 'Please Task Creation Permission (Yes / No)');
        array_push($columnArray, $Task_Creation_Permission);

        $Task_Assignment_Creation = array(
            "name" => "Task Assignment Creation",
            'data_type' => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Task Assignment Creation Permission',
            'promt_message' => 'Please Enter Task Assignment Creation Permission (Yes / No)');
        array_push($columnArray, $Task_Assignment_Creation);

        $Due_Date_Creation = array(
            "name" => "Due Date Creation",
            'data_type' => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Due Date Creation Permission',
            'promt_message' => 'Please Enter Due Date Creation Permission (Yes / No)');
        array_push($columnArray, $Due_Date_Creation);

        $Due_Date_Task_Assignment_Creation = array(
            "name" => "Due Date Task Assignment Creation",
            'data_type' => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Due Date Task Assignment Creation Permission',
            'promt_message' => 'Please Enter Due Date Task Assignment Creation Permission (Yes / No)');
        array_push($columnArray, $Due_Date_Task_Assignment_Creation);

        $Services_Creation = array(
            "name" => "Services Creation",
            'data_type' => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Services Creation Permission',
            'promt_message' => 'Please Enter Services Creation Permission (Yes / No)');
        array_push($columnArray, $Services_Creation);

        $Generate_Enquiry = array(
            "name" => "Generate Enquiry",
            'data_type' => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Generate Enquiry Permission',
            'promt_message' => 'Please Enter Generate Enquiry Permission (Yes / No)');
        array_push($columnArray, $Generate_Enquiry);

        $Permission_To_Approve_Leave = array(
            "name" => "Permission To Approve Leave",
            'data_type' => 3,
            'is_input_message' => true,
            'promt_title' => 'Employee Permission To Approve Leave Permission',
            'promt_message' => 'Please Enter Permission To Approve Leave Permission (Yes / No)');
        array_push($columnArray, $Permission_To_Approve_Leave);

        $this->create_excel($columnArray, 250, 'Employee');
    }

    public function create_service_offering_excel() {
        $columnArray = array();

        $branch_name = array(
            "name" => "Branch Name",
            "data_type" => 3
        );
        array_push($columnArray, $branch_name);

        $service_name = array(
            "name" => "Service Name",
            "data_type" => 3
        );
        array_push($columnArray, $service_name);

        $offering_name = array(
            "name" => "Offering Name",
            "data_type" => 2);
        array_push($columnArray, $offering_name);

        $this->create_excel($columnArray, 250, 'Services_And_Offering');
    }

    public function create_task_sub_checklist_excel() {
        $columnArray = array();

        $branch_name = array(
            "name" => "Branch Name",
            "data_type" => 3,
            'is_input_message' => true,
            'error_title' => "Select Branch Name",
            'error_message' => "Please Select Branch Name",
            'promt_title' => "Select Branch Name",
            'promt_message' => "Please Select Branch Name",
            'set_formula' => array('B1', 'B2', 'B3', 'B4'));
        array_push($columnArray, $branch_name);

        $Task_Name = array(
            "name" => "Task Name",
            "data_type" => 3,
            'is_input_message' => true,
            'promt_title' => 'Task Name',
            'promt_message' => 'Please Enter Task Name');
        array_push($columnArray, $Task_Name);

        $Subtask_Name = array(
            "name" => "Subtask Name",
            "data_type" => 2,
            'is_input_message' => true,
            'promt_title' => 'Subtask Name',
            'promt_message' => 'Please Enter Subtask Name');
        array_push($columnArray, $Subtask_Name);
        $Subsubtask_Name = array(
            "name" => "Sub-Subtask Name",
            "data_type" => 2,
            'is_input_message' => true,
            'promt_title' => 'Sub-Subtask Name',
            'promt_message' => 'Please Enter Sub-Subtask Name');
        array_push($columnArray, $Subsubtask_Name);
        $checklist_Name = array(
            "name" => "CheckList Items",
            "data_type" => 2,
            'is_input_message' => true,
            'promt_title' => 'CheckList Items',
            'promt_message' => 'Please Enter CheckList Items');
        array_push($columnArray, $checklist_Name);

        $this->create_excel($columnArray, 250, 'Task_Subtask_checklist');
    }

    function create_type_subtypeexcel() {

        $Type_subtype_columnArray = array();
        $Type = array(
            "name" => "Type",
            "data_type" => 3,
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'allow_blank' => false,
            'is_error_message' => false,
            'promt_title' => "Type",
            'promt_message' => " Please Enter  Type",);
        array_push($Type_subtype_columnArray, $Type);

        $Subtype = array(
            "name" => "Subtype",
            "data_type" => 3,
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'allow_blank' => false,
            'is_error_message' => false,
            'promt_title' => "Subtype",
            'promt_message' => "Subtype",);
        array_push($Type_subtype_columnArray, $Subtype);
        $filename = 'TypeSubtype';
        $this->create_excel($Type_subtype_columnArray, 250, $filename);
    }

    public function generate_type_id($typee) {

        $temp_type_id = rand(100, 999);
        $cid = trim($typee); //remove white spaces from name
        $type_id = substr("$cid", 0, 3) . $temp_type_id; //concate trimmed name(first 3 letters) with 3 random digits.
        //check generated id already exist.
        $result = $this->db->query("SELECT `type_id` FROM `type_subtype_information_all` WHERE `type_id`='$type_id'");
        if ($result->num_rows() === 0) {
            return $type_id;
        } else {
            return $this->generate_type_id($typee);
        }
    }

    public function generate_sub_type_id($Subtypee) {
        $temp_type_id = rand(100, 999);
        $cid = trim($Subtypee); //remove white spaces from name
        $subtype_id = substr("$cid", 0, 3) . $temp_type_id; //concate trimmed name(first 3 letters) with 3 random digits.
        //check generated id already exist.
        $result = $this->db->query("SELECT `subtype_id` FROM `type_subtype_information_all` WHERE `subtype_id`='$subtype_id'");
        if ($result->num_rows() === 0) {
            return $subtype_id;
        } else {
            return $this->generate_sub_type_id($Subtypee);
        }
    }

    function create_type_subtype_read() {



        $path = $_FILES["excel_file"]["tmp_name"];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(TRUE);
        $excel = $objReader->load($path);

        $header_array = array("Type", "Subtype");

        $data_type_array = array("string" => array(0, 1));

//        $compulsary_column = array(0, 1, 2); // All
        $compulsary_column = "All";
        $worksheet_instance = $excel->setActiveSheetIndex();
        $excel_data_in_array = $this->excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance);

        $level_1 = array();
        foreach ($excel_data_in_array as $row => $column) {
            $level_1[$column["Type"]][] = $column;
        }
//        $level_2 = array();
//        foreach ($level_1 as $l_1) {
//            $result_array = array();
//            foreach ($l_1 as $l_2) {
//                $result_array[$l_2["Subtype"]][] = $l_2;
//            }
//            array_push($level_2, $result_array);
//        }
//        // data base insert query array;
        $db_array[] = $level_1;
        $type_array = array();
        $Subtype_array = array();
        foreach ($db_array as $value) {
//            print_r($value);


            foreach ($value as $value1) {
                foreach ($value1 as $value2) {
                    array_push($type_array, $value2["Type"]);

                    $Subtype_array1 = array($value2["Type"], $value2["Subtype"]);
                    array_push($Subtype_array, $Subtype_array1);
                }
            }
        }




//
        $Type_insert_data = array();
        $Subtype_insert_data = array();
        foreach (array_unique($type_array) as $type) {
            $type_id1 = $this->generate_type_id($type);
            $created_on = date('y-m-d h:i:s');
            foreach ($Subtype_array as $Subtype) {
                if ($Subtype[0] == $type) {
                    $sub_type_id1 = $this->generate_sub_type_id($Subtype[1]);
                    $data_subtype = array(
                        'subtype_id' => $sub_type_id1,
                        'type_id' => $type_id1,
                        'type_name' => $type,
                        'subtype_name' => $Subtype[1],
                        'status' => 1,
                        'created_on' => $created_on
                    );
                    array_push($Subtype_insert_data, $data_subtype);
                    $this->db->insert('type_subtype_information_all', $data_subtype);
                }
            }
        }


        $response = array();
        if (is_array($excel_data_in_array)) {
            $table = "";
            foreach ($excel_data_in_array as $row) {
                $table .= "<tr>";
                foreach ($row as $column) {
                    $table .= "<td>" . $column . "</td>";
                }
                $table .= "</tr>";
            }
            $response["data"] = $table;
        } else {
            $response["error"] = $excel_data_in_array;
        }
        echo json_encode($response);
    }

    function create_scale_rating_excel() {

        $create_scale_rating_excel = array();
        $Group_name = array(
            "name" => "Group_name",
            "data_type" => 3,
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'allow_blank' => false,
            'is_error_message' => false,
            'promt_title' => "Group_name",
            'promt_message' => " Please Enter  Group_name",);
        array_push($create_scale_rating_excel, $Group_name);

        $Rating_scale = array(
            "name" => "Rating_scale",
            "data_type" => 3,
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'allow_blank' => false,
            'is_error_message' => false,
            'promt_title' => "Rating_scale",
            'promt_message' => " Please enter Rating_scale",);
        array_push($create_scale_rating_excel, $Rating_scale);
        $values = array(
            "name" => "values",
            "data_type" => 3,
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'allow_blank' => false,
            'is_error_message' => false,
            'promt_title' => "values",
            'promt_message' => " Please enter values",);
        array_push($create_scale_rating_excel, $values);

        $filename = 'Scale_and_values';
        $this->create_excel($create_scale_rating_excel, 250, $filename);
    }

    public function generate_grp_id() {
        $result = $this->db->query('SELECT option_group_id FROM `options` ORDER BY option_group_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $grp_id = $data->option_group_id;
//generate user_id
            $grp_id = str_pad(++$grp_id, 3, '0', STR_PAD_LEFT);
            return $grp_id;
        } else {
            $grp_id = 'grp_1001';
            return $grp_id;
        }
    }

    public function generate_op_id() {
        $result = $this->db->query('SELECT option_id FROM `options` ORDER BY option_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $op_id = $data->option_id;
//generate user_id
            $op_id = str_pad(++$op_id, 3, '0', STR_PAD_LEFT);
            return $op_id;
        } else {
            $op_id = 'opt_id_1001';
            return $op_id;
        }
    }

    function scale_values_read() {
        $path = $_FILES["excel_file"]["tmp_name"];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(TRUE);
        $excel = $objReader->load($path);

        $header_array = array("Group_name", "Rating_scale", "values");

        $data_type_array = array("string" => array(0, 1), "number" => array(2));

//        $compulsary_column = array(0, 1, 2); // All
        $compulsary_column = "All";
        $worksheet_instance = $excel->setActiveSheetIndex();
        $excel_data_in_array = $this->excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance);

        $level_1 = array();
        foreach ($excel_data_in_array as $row => $column) {
            $level_1[$column["Group_name"]][] = $column;
        }
//        $level_2 = array();
//        foreach ($level_1 as $l_1) {
//            $result_array = array();
//            foreach ($l_1 as $l_2) {
//                $result_array[$l_2["Subtype"]][] = $l_2;
//            }
//            array_push($level_2, $result_array);
//        }
//        // data base insert query array;

        $db_array[] = $level_1;
        $group_name_array = array();
        $rating_scale_array = array();
        foreach ($db_array as $value) {


            foreach ($value as $value1) {
                foreach ($value1 as $value2) {
                    array_push($group_name_array, $value2["Group_name"]);

                    $rating_scale_array1 = array($value2["Group_name"], $value2["Rating_scale"],
//                        PHPExcel_Shared_::ExcelToPHP($value2["values"]));
                        PHPExcel_Style_NumberFormat::toFormattedString($value2["values"], PHPExcel_Style_NumberFormat::FORMAT_NUMBER));
                    array_push($rating_scale_array, $rating_scale_array1);
                }
            }
        }
//        var_dump($group_name_array);
//        var_dump($values_array1);





        foreach (array_unique($group_name_array) as $group_name) {
            $grp_id = $this->generate_grp_id();

            $created_on = date('y-m-d h:i:s');
            foreach ($rating_scale_array as $rating_scale) {
                if ($rating_scale[0] == $group_name) {
                    $op_id = $this->generate_op_id();
                    $data_options = array(
                        'option_group_id' => $grp_id,
                        'option_id' => $op_id,
                        'option_group_name' => $group_name,
                        'option_name' => $rating_scale[1],
                        'weightage' => $rating_scale[2],
                        'status' => 1,
                        'created_on' => $created_on
                    );


                    $this->db->insert('options', $data_options);
                }
            }
        }


//        $db_array[] = $level_1;
//

        $response = array();
        if (is_array($excel_data_in_array)) {
            $table = "";
            foreach ($excel_data_in_array as $row) {
                $table .= "<tr>";
                foreach ($row as $column) {
                    $table .= "<td>" . $column . "</td>";
                }
                $table .= "</tr>";
            }
            $response["data"] = $table;
        } else {
            $response["error"] = $excel_data_in_array;
        }
        echo json_encode($response);
    }

    function question_excel() {

        $question_excel = array();
        $Type_Name = array(
            "name" => "Type_Name",
            "data_type" => 3,
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'allow_blank' => false,
            'is_error_message' => false,
            'promt_title' => "Type_Name",
            'promt_message' => " Please Enter  Type_Name",);
        array_push($question_excel, $Type_Name);

        $Subtype_Name = array(
            "name" => "Subtype_Name",
            "data_type" => 3,
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'allow_blank' => false,
            'is_error_message' => false,
            'promt_title' => "Subtype_Name",
            'promt_message' => " Please enter Subtype_Name",);
        array_push($question_excel, $Subtype_Name);
        $Question = array(
            "name" => "Question",
            "data_type" => 3,
            "error_style" => PHPExcel_Cell_DataValidation::STYLE_INFORMATION,
            'allow_blank' => false,
            'is_error_message' => false,
            'promt_title' => "Question",
            'promt_message' => " Please enter Question",);
        array_push($question_excel, $Question);

        $filename = 'Add_questions';
        $this->create_excel($question_excel, 250, $filename);
    }

    public function generate_qu_id() {
        $result = $this->db->query('SELECT question_id FROM `question` ORDER BY question_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $question_id = $data->question_id;
//generate user_id
            $question_id = str_pad(++$question_id, 3, '0', STR_PAD_LEFT);
            return $question_id;
        } else {
            $question_id = 'qus_id_1001';
            return $question_id;
        }
    }

    function question_read() {



        $path = $_FILES["excel_file"]["tmp_name"];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(TRUE);
        $excel = $objReader->load($path);

        $header_array = array("Type_Name", "Subtype_Name", "Question");

        $data_type_array = array("string" => array(0, 1, 2));

//        $compulsary_column = array(0, 1, 2); // All
        $compulsary_column = "All";
        $worksheet_instance = $excel->setActiveSheetIndex();
        $excel_data_in_array = $this->excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance);

        $level_1 = array();
        foreach ($excel_data_in_array as $row => $column) {
            $level_1[$column["Type_Name"]][] = $column;
        }
//        $level_2 = array();
//        foreach ($level_1 as $l_1) {
//            $result_array = array();
//            foreach ($l_1 as $l_2) {
//                $result_array[$l_2["Subtype_Name"]][] = $l_2;
//            }
//            array_push($level_2, $result_array);
//        }
        // data base insert query array;
        $db_array = $level_1;

        $type_name_array = array();
        $subtype_scale_array = array();
        foreach ($db_array as $value) {



            foreach ($value as $value2) {
                array_push($type_name_array, $value2["Type_Name"]);

                $subtype_scale_array1 = array($value2["Type_Name"], $value2["Subtype_Name"],
                    $value2["Question"]);

//                        PHPExcel_Style_NumberFormat::toFormattedString($value2["values"], PHPExcel_Style_NumberFormat::FORMAT_NUMBER));
                array_push($subtype_scale_array, $subtype_scale_array1);
            }
        }




        foreach (array_unique($type_name_array) as $type_name) {
            $type_id1 = $this->generate_type_id($type_name);


            $created_on = date('y-m-d h:i:s');
            foreach ($subtype_scale_array as $subtype_scale) {
                if ($subtype_scale[0] == $type_name) {

                    $q_id = $this->generate_qu_id();

                    $data_questions = array(
                        'question_id' => $q_id,
                        'question_type' => $type_id1,
                        'question_sub_type' => $subtype_scale[1],
                        'question' => $subtype_scale[2],
                        'created_on' => $created_on,
                    );



                    $this->db->insert('question', $data_questions);
                }
            }
        }
        $response = array();
        if (is_array($excel_data_in_array)) {
            $table = "";
            foreach ($excel_data_in_array as $row) {
                $table .= "<tr>";
                foreach ($row as $column) {
                    $table .= "<td>" . $column . "</td>";
                }
                $table .= "</tr>";
            }
            $response["data"] = $table;
        } else {
            $response["error"] = $excel_data_in_array;
        }
        echo json_encode($response);
    }

    // upload code started

    /*
     * @Author Name NARENDRA
     * @return Json response if success return table row otherwise return error message while excel validation.
     */
    public function upload_employee() {
        $path = $_FILES["excel_file"]["tmp_name"];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(TRUE);
        $excel = $objReader->load($path);

        $header_array = array("C1", "C2", "C3", "C4", "C5", "C6", "C7", "C8", "C9", "C10", "C11", "C12", "C13", "C14", "C15", "C16", "C17", "C18", "C19"
            , "C20", "C21", "C22", "C23");

        $data_type_array = array("number" => array(), "string" => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 17, 18, 19, 20, 21, 22, 23), "datetime" => array(11, 13, 14, 15, 16));
        $compulsary_column = "All";
        $error_message = "";
        $worksheet_instance = $excel->setActiveSheetIndex();
        $excel_data_in_array = $this->excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance);
        $response = array();
        if (is_array($excel_data_in_array)) {
            $table = "";
            foreach ($excel_data_in_array as $row) {
                $table .= "<tr>";
                foreach ($row as $column) {
                    $table .= "<td>" . $column . "</td>";
                }
                $table .= "</tr>";
            }
            $response["data"] = $table;
        } else {
            $response["error"] = $excel_data_in_array;
        }

        if (is_array($excel_data_in_array)) {

            $table_array = array();
            foreach ($excel_data_in_array as $row) {
                $tr = array();
                foreach ($row as $c_count => $column) {

                    if (strtoupper($column) == "Y") {
                        $column = "1,1,1";
                    }
                    if (strtoupper($column) == "N") {
                        $column = "0,0,0";
                    }
                    $tr[$c_count] = $column;
                }
                array_push($table_array, $tr);
            }
            $response["data_code"] = 200;
            $response["data"] = $table_array;
            $response["error"] = $excel_data_in_array;
        } else {
            $response["data_code"] = 300;
            $response["error"] = $excel_data_in_array;
        }
        $this->insert_employee($table_array);
    }

    public function insert_employee($table_array) {
        $insert_array = array();
        $result1 = $this->customer_model->get_firm_id();
        $username = $this->session->userdata('login_session');
        $boss_id_hq = $result1["boss_id"];
        foreach ($table_array as $user) {
            $firm_details = $this->getFirmByBranchName($user['C1']);
            $firm_id = $firm_details->firm_id;
            $boss_id = $firm_details->boss_id;
            $designation_id = $this->getSeniorDesignationByDesignationName($user['C9'], $firm_id);
            $user_id = $this->generate_user_id();
            $password = rand(100, 1000);

            $user_details = $this->db->select("user_id")->where(array("user_name"))->get("user_header_all")->row();

            if (is_numeric($user['C11'])) {
                $UNIX_DATE = ( $user['C11'] - 25569) * 86400;
                $date = gmdate("d-m-Y", $UNIX_DATE);
                $date_of_joining = $date;
            } else {
                $date_of_joining = $user['C11'];
            }
            if (is_numeric($user['C13'])) {
                $UNIX_DATE = ( $user['C13'] - 25569) * 86400;
                $date = gmdate("d-m-Y", $UNIX_DATE);
                $probation_period_start_date = $date;
            } else {
                $probation_period_start_date = $user['C13'];
            }

            if (is_numeric($user['C14'])) {
                $UNIX_DATE = ( $user['C14'] - 25569) * 86400;
                $date = gmdate("d-m-Y", $UNIX_DATE);
                $probation_period_end_date = $date;
            } else {
                $probation_period_end_date = $user['C14'];
            }

            $data = array(
                'user_id' => $user_id,
                'user_name' => $user['C2'],
                'mobile_no' => $user['C3'],
                'state' => $user['C4'],
                'city' => $user['C5'],
                'email' => $user['C6'],
                'address' => $user['C7'],
                'country' => $user['C8'],
                'firm_id' => $firm_id,
                'is_employee' => 1,
                'user_type' => 4,
                'designation_id' => $designation_id,
                'date_of_joining' => $date_of_joining,
                'created_by' => $username,
                'password' => $password,
                'linked_with_boss_id' => $boss_id,
                'boss_id' => $boss_id_hq,
                'senior_user_id' => $user_details->user_id,
                'skill_set' => '',
                'leave_approve_permission' => $user['C17'],
                'probation_period_start_date' => $probation_period_start_date,
                'probation_period_end_date' => $probation_period_end_date,
                'training_period_start_date' => '',
                'training_period_end_date' => '',
                'create_task_assignment' => $user['C18'] . ':' . $user['C19'],
                'create_due_date_permission' => $user['C20'] . ':' . $user['C21'],
                'create_service_permission' => $user['C22'],
                'template_store_permission' => '0,0,0',
                'knowledge_store_permission' => '0,0,0',
                'employee_permission' => '0,0,0',
                'customer_permission' => '0,0,0',
                'warning_conifg_permission' => '0,0,0',
                'enquiry_generate_permission' => $user['C23'],
                'user_star_rating' => '8',
                'task_approve_permission' => '0',
                'task_sign_permission' => '0',
                'work_on_services' => '',
                'handle_web_services' => '',
                'web_enquiry_handle_permission' => '',
                'activity_status' => 1
            );
            $record = $this->db->insert("user_header_all", $data);
        }
    }

    /*
     * @Author Name NARENDRA
     * @Return Json response if success Branch details from excel
     */

    public function upload_branch() {
        $path = $_FILES["excel_file"]["tmp_name"];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(TRUE);
        $excel = $objReader->load($path);

        $header_array = array("c0", "c1", "c2", "c3", "c4", "c5", "c6", "c7", "c8", "c9", "c10", "c11", "c12", "c13", "c14", "c15", "c16", "c17", "c18", "c19", "c20"
            , "c21", "c22", "c23", "c24", "c25", "c26", "c27", "c28", "c29", "c30", "c31", "c32");

        $data_type_array = array("number" => array(),
            "string" => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32),
            "datetime" => array(21));
        $compulsary_column = "All";
        $error_message = "";
        $worksheet_instance = $excel->setActiveSheetIndex();
        $excel_data_in_array = $this->excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance);
        $response = array();
        if (is_array($excel_data_in_array)) {
            $table = "";
            $table_array = array();
            foreach ($excel_data_in_array as $row) {
                $table .= "<tr>";
                $tr = array();

                foreach ($row as $c_count => $column) {

                    if ($c_count == "c27") {

                        if ($column == "Y") {
                            $column = "1";
                        }
                        if ($column == "N") {
                            $column = "0";
                        }
                    }
                    if ($c_count == "c26") {

                        if ($column == "Monthly") {
                            $column = "1";
                        }
                        if ($column == "Yearly") {
                            $column = "0";
                        }
                    }

                    if ($c_count == "c25") {

                        if ($column == "After Confirmation ") {
                            $column = "4";
                        }
                        if ($column == "After Training") {
                            $column = "3";
                        }
                        if ($column == "After Probation") {
                            $column = "2";
                        }
                        if ($column == "From Date of joining") {
                            $column = "1";
                        }
                    }
                    if ($c_count == "c24") {

                        if ($column == "Financial Year") {
                            $column = "2";
                        }
                        if ($column == "Calendar Year") {
                            $column = "3";
                        }

                        if ($column == "From the date of Joining") {
                            $column = "1";
                        }
                    }
                    if ($column == "Y") {
                        $column = "1,1,1";
                    }
                    if ($column == "N") {
                        $column = "0,0,0";
                    }
                    $tr[$c_count] = $column;

                    $table .= "<td>" . $column . "</td>";
                }
                array_push($table_array, $tr);
                $table .= "</tr>";
            }
            $response["data_code"] = 200;
            $response["data"] = $table;
            $response["error"] = $excel_data_in_array;
        } else {
            $response["data_code"] = 300;
            $response["error"] = $excel_data_in_array;
        }
        $this->save_branch_details($table_array);
//        echo json_encode($response);
    }

    function save_branch_details($excel_data_in_array) {
        $this->load->model('Firm_model');
        $result1 = $this->customer_model->get_firm_id();
//        $user_id = $result1['user_id'];
        $user_id = "U_1001";
        //$reporting_to = $result1['boss_id'];
        $reporting_to = "B_1010100110";
        $email_id = $this->session->userdata('login_session');
        foreach ($excel_data_in_array as $row) {
//            print_r($row);
            $designation = 'CA';
            $designation_name = 'Client Admin';
            $boss_id = $this->generate_boss_id();
            $firm_name = $row["c0"];
            $firm_activity_status = $row["c11"];
            $firm_address = $row["c2"];
            $firm_email_id = $row["c1"];
            $firm_type = "associate";
            $boss_name = $row["c3"];
            $boss_mobile_no = $row["c4"];
            $boss_email_id = $row["c5"];
            $firm_no_of_employee = $row["c6"];
            $firm_no_of_customers = $row["c7"];
            $firm_no_of_permitted_offices = 0;
            $country = $row["c8"];
            $state = $row["c9"];
            $city = $row["c10"];
            $is_emp = $row["c12"];
            $date = date('y-m-d h:i:s');
            $leave_approve_permitted = $row["c17"];
            $prob_date_first = $row["c28"];
            $prob_date_second = $row["c29"];
            $training_period_first = $row["c30"];
            $training_period_second = $row["c31"];
            $joining_date = $row["c21"];
            $create_task_assign_permit = $row["c15"] . ":" . $row["c16"];
            $create_duedate_permit = $row["c13"] . ":" . $row["c14"];
            $create_service_permit = $row["c18"];
            $generate_enq_permit = $row["c19"];
            $employee_permission = '1,1,1';
            $customer_permission = '1,1,1';
            $template_permission = '1,1,1';
            $knowledge_permission = '1,1,1';
            $warning_configuration_permission = '1,1,1';

            $hq_user_id = $user_id;
            $leave_array = $this->leave_sorting($row["c32"]);
            $leave_data = $this->leave_data_details($leave_array[1]);

            $data_leave = $leave_data["leave_data"];
//            print_r($data_leave);
            $total_leave = $leave_data["Total_leave"];

            $data_leave['created_on'] = $date;
            $data_leave['designation_id'] = $designation;
            $data_leave['boss_id'] = $boss_id;

            if ($firm_activity_status == 'A') {
                $user_activity_status = '1';
            } else {
                $user_activity_status = '0';
            }
            $firm_id = $this->generate_firm_id();
            $user_id = $this->generate_user_id();
            $data_leave['firm_id'] = $firm_id;
            $data = array(
                'firm_id' => $firm_id,
                'firm_name' => $firm_name,
                'firm_activity_status' => $firm_activity_status,
                'firm_address' => $firm_address,
                'firm_email_id' => $firm_email_id,
                'firm_type' => $firm_type,
                'boss_id' => $boss_id,
                'boss_name' => $boss_name,
                'boss_mobile_no' => $boss_mobile_no,
                'boss_email_id' => $boss_email_id,
                'reporting_to' => $reporting_to,
                'firm_no_of_employee' => $firm_no_of_employee,
                'firm_no_of_customers' => $firm_no_of_customers,
                'firm_no_of_permitted_offices' => $firm_no_of_permitted_offices,
                'created_by' => '1',
                'created_on' => $date,
                'created_from' => $user_id,
                'due_date_creation_permitted' => 1,
                'task_creation_permitted' => 1,
            );
            $monthly_leaves = $row["c22"];
            $leave_cf = $row["c26"];
            $leave_apply_permission = $row["c25"];
            $leave_type_year = $row["c24"];
            $holiday_status = 1;
            $data_designation = array(
                'boss_id' => $boss_id,
                'firm_id' => $firm_id,
                'designation_id' => $designation,
                'designation_name' => $designation_name,
                'created_on' => $date,
                'created_by' => $email_id,
                'total_yearly_leaves' => $total_leave,
                'total_monthly_leaves' => $monthly_leaves,
                'holiday_consider_in_leave' => $holiday_status,
                'carry_forward_period' => $leave_cf,
                'request_leave_from' => $leave_apply_permission,
                'year_type' => $leave_type_year
            );


            $password = rand(100, 1000);
            $result = $this->Firm_model->add_firm_modal($data, $user_id, 10, 1, 1, $user_activity_status, $email_id, $city, $state, $country, $is_emp, $password, $hq_user_id, $prob_date_first, $prob_date_second, $training_period_first, $training_period_second, $joining_date, $designation, $reporting_to, $create_task_assign_permit, $create_duedate_permit, $create_service_permit, $generate_enq_permit, $employee_permission, $customer_permission, $template_permission, $knowledge_permission, $warning_configuration_permission);
            $p_data_leave = $this->db->insert('leave_header_all', $data_leave);
            $data_designation1 = $this->db->insert('designation_header_all', $data_designation);
            echo "partener_user_header=", $result, "leave_header_all=", $p_data_leave, "designation=", $data_designation1;
//            echo json_encode(array($data, $user_activity_status, $session_user_id, $user_id, $city, $state, $country, $is_emp, $user_type, $password, $hq_user_id, $leave_approve_permitted, $req_leave, $prob_date_first, $prob_date_second, $training_period_first, $training_period_second, $joining_date, $designation, $reporting_to, $create_task_assign_permit, $create_duedate_permit, $create_service_permit, $generate_enq_permit, $employee_permission, $customer_permission, $template_permission, $knowledge_permission, $warning_configuration_permission));
        }
    }

    function leave_data_details($leave_type_array) {

        if (!isset($leave_type_array[0])) {
            $type1 = '';
        } else {
            $type1 = $leave_type_array[0][0];
        }

        if (!isset($leave_type_array[1])) {
            $type2 = '';
        } else {
            $type2 = $leave_type_array[1][0];
        }
        if (!isset($leave_type_array[2])) {
            $type3 = '';
        } else {
            $type3 = $leave_type_array[2][0];
        }
        if (!isset($leave_type_array[3])) {
            $type4 = '';
        } else {
            $type4 = $leave_type_array[3][0];
        }

        if (!isset($leave_type_array[4])) {
            $type5 = '';
        } else {
            $type5 = $leave_type_array[4];
        }
        if (!isset($leave_type_array[5])) {
            $type6 = '';
        } else {
            $type6 = $leave_type_array[5][0];
        }
        if (!isset($leave_type_array[6])) {
            $type7 = '';
        } else {
            $type7 = $leave_type_array[6][0];
        }

        $data_leave = array(
            'type1' => $type1,
            'type2' => $type2,
            'type3' => $type3,
            'type4' => $type4,
            'type5' => $type5,
            'type6' => $type6,
            'type7' => $type7
        );
//        print_r($leave_type_array);
        return array("leave_data" => $data_leave, "Total_leave" => $leave_type_array[0][1]);
    }

    function leave_sorting($formate) {

        $leave_type = explode(",", $formate);
        $request_before = $approved_after = "0";
        $leave_str = $leave_count = "";
        $leave_type_array = array();
        $leave_type_str = array();
        foreach ($leave_type as $type) {
            $filtter_value = str_replace(array('(', ')'), '', trim($type));
            $seperator = explode(" ", $filtter_value);
            if (isset($seperator[1])) {
                $request_approved = explode(":", $seperator[1]);
                if (is_array($request_approved)) {
                    $request_before = $request_approved[0];
                    $approved_after = $request_approved[1];
                } else {
                    $request_before = 0;
                    $approved_after = 0;
                }
            } else {
                $request_before = 0;
                $approved_after = 0;
            }
            if (isset($seperator[3])) {
                $total_leave = $seperator[3];
            } else {
                $total_leave = 0;
            }
            $leave_str_count = explode("-", $seperator[0]);
            $leave_str = $leave_str_count[0];
            $leave_count = $leave_str_count[1];
            array_push($leave_type_array, array("Request_Before" => $request_before, "Approved_Before" => $approved_after, "Leave_Type" => $leave_str, "Leave_Count" => $leave_count, "Total_leave" => $total_leave));
            array_push($leave_type_str, array($leave_str . ":" . $leave_count . ":" . $request_before . ":" . $approved_after, $total_leave));
        }
        $leave_data = array();

        array_push($leave_data, $leave_type_array);
        array_push($leave_data, $leave_type_str);
        return $leave_data;
    }

    function generate_boss_id() {
        $boss_id = 'B_' . rand();
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where('boss_id', $boss_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return generate_boss_id();
        } else {
            return $boss_id;
        }
    }

    function generate_user_id() {
        $result = $this->db->query('SELECT user_id FROM `user_header_all` ORDER BY user_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $user_id = $data->user_id;
            //generate user_id
            $user_id = str_pad(++$user_id, 5, '0', STR_PAD_LEFT);
            return $user_id;
        } else {
            $user_id = 'U_1001';
            return $user_id;
        }
    }

    function generate_firm_id() {
        $result = $this->db->query('SELECT firm_id FROM `partner_header_all` ORDER BY firm_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $firm_id = $data->firm_id;
            //generate user_id
            $firm_id = str_pad(++$firm_id, 5, '0', STR_PAD_LEFT);
            return $firm_id;
        } else {
            $firm_id = 'Firm_1001';
            return $firm_id;
        }
    }

    public function upload_service_offering() {

        $path = $_FILES["excel_file"]["tmp_name"];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(TRUE);
        $excel = $objReader->load($path);

        $header_array = array("Branch Name", "Service Name", "Offering Name");

        $data_type_array = array("string" => array(0, 1, 2), "datetime" => array());

        $compulsary_column = "All";
        $worksheet_instance = $excel->setActiveSheetIndex();
        $excel_data_in_array = $this->excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance);
        $created_by = $this->session->userdata('login_session');

        $service_array = array();
        $offering_array = array();

        $level_1 = array();
        foreach ($excel_data_in_array as $row => $column) {
            $level_1[$column["Branch Name"]][] = $column;
        }

        foreach ($level_1 as $l_1) {
            foreach ($l_1 as $l_2) {
                $branch_service_array = array($l_2["Branch Name"], $l_2["Service Name"]);
                array_push($service_array, $branch_service_array);
                $offering_service_array = array($l_2["Service Name"], $l_2["Offering Name"]);
                array_push($offering_array, $offering_service_array);
            }
        }

        foreach (array_unique($service_array, SORT_REGULAR) as $service) {
            $branch_list = explode(",", $service[0]);
            $service_id = $this->getServiceId();
            $service_insert_array = array();
            foreach ($branch_list as $branch) {
                $firm_info = $this->getFirmByBranchName($branch);
                $created_date = date('y-m-d h:i:s');
                $firm_id = $firm_info->firm_id;
                $boss_id = $firm_info->reporting_to;
                $data_service = array(
                    'service_id' => $service_id,
                    'service_name' => $service[1],
                    'firm_id' => $firm_id,
                    'boss_id' => $boss_id,
                    'status' => 1,
                    'created_by' => $created_by,
                    'created_on' => $created_date
                );
                $this->db->insert('services_header_all', $data_service);
                array_push($service_insert_array, $data_service);
            }


            $offering_insert_array = array();
            foreach ($offering_array as $offering) {
                $service_offering_id = $this->getServiceId();
                foreach ($branch_list as $branch) {
                    $firm_info = $this->getFirmByBranchName($branch);
                    $created_date = date('y-m-d h:i:s');
                    $firm_id = $firm_info->firm_id;
                    $boss_id = $firm_info->reporting_to;
                    if ($offering[0] == $service[1]) {
                        $data_offering = array(
                            'service_id' => $service_offering_id,
                            'service_type_id' => $service_id,
                            'service_name' => $offering[1],
                            'firm_id' => $firm_id,
                            'boss_id' => $boss_id,
                            'status' => 1,
                            'created_by' => $created_by,
                            'created_on' => $created_date
                        );
                        $this->db->insert('services_header_all', $data_offering);
                        array_push($offering_insert_array, $data_offering);
                    }
                }
            }
        }
    }

    public function getFirmByBranchName($branch_name) {

        $rows = $this->db->select(array("firm_id", "boss_id", "reporting_to", "leave_type_fixed"))->where("firm_name", trim($branch_name))->get("partner_header_all")->row();
        if (!is_null($rows)) {
            return $rows;
        } else {
            exit();
        }
    }

    public function getSeniorDesignationByDesignationName($designation_name, $firm_id) {

        $rows = $this->db->select(array("designation_id"))->where(array("designation_name" => $designation_name, "firm_id" => $firm_id))->get("designation_header_all")->row();

        if (!is_null($rows)) {
            return $rows->designation_id;
        } else {
            exit();
        }
    }

    public function getServiceIdByServiceName($name, $firm_id) {
        $this->db->select('*');
        $this->db->from('services_header_all');
        $this->db->where("firm_id='$firm_id' AND service_name='$name'");
        $this->db->order_by("service_name", "ASC");
        $query = $this->db->get()->result();
        if (count($query) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getServiceId() {
        $this->load->model('service_model');
        $cat_rs = $this->service_model->selectServiceId();
        if ($cat_rs != NULL) {
            $service_id = $cat_rs->service_id;
            //generate user_id
            $service_id = str_pad(++$service_id, 5, '0', STR_PAD_LEFT);
            return $service_id;
        } else {
            $service_id = 'serv_1001';
            return $service_id;
        }
    }

    public function generate_task_id() {
        $result = $this->db->query('SELECT task_id FROM `task_header_all` ORDER BY task_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $task_id = $data->task_id;
            //generate user_id
            $task_id = str_pad(++$task_id, 5, '0', STR_PAD_LEFT);
            return $task_id;
        } else {
            $task_id = 'T_1001';
            return $task_id;
        }
    }

    public function generate_sub_task_id() {

        $result = $this->db->query('SELECT sub_task_id FROM `sub_task_header_all` ORDER BY sub_task_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $sub_task_id = $data->sub_task_id;
            $sub_task_id = str_pad(++$sub_task_id, 5, '0', STR_PAD_LEFT);
            return $sub_task_id;
        } else {
            $sub_task_id = 'SUB_1001';
            return $sub_task_id;
        }
    }

    public function upload_task_sub_task_checklist() {
        $path = $_FILES["excel_file"]["tmp_name"];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(TRUE);
        $excel = $objReader->load($path);
        $header_array = array("Branch Name", "Task Name", "Subtask Name", "Sub-Subtask Name", "CheckList Items");
        $data_type_array = array("string" => array(0, 1, 2));
        $compulsary_column = array(0, 1, 2); // All
        $worksheet_instance = $excel->setActiveSheetIndex();
        $excel_data_in_array = $this->excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance);

        $response = array();
        if (is_array($excel_data_in_array)) {
            $table = "";
            foreach ($excel_data_in_array as $row) {
                $table .= "<tr>";
                foreach ($row as $column) {
                    $table .= "<td>" . $column . "</td>";
                }
                $table .= "</tr>";
            }
            $response["data"] = $table;
        } else {
            $response["error"] = $excel_data_in_array;
        }

        $task_id = $this->generate_task_id();
        $sub_task_id = $this->generate_sub_task_id();
        $user_id = $this->session->userdata('login_session');
        $created_on = date('y-m-d h:i:s');
        $result = $this->customer_model->get_boss_id();

        $branch_list = array();
        foreach ($excel_data_in_array as $row) {
            $branch_array = explode("-", $row["Branch Name"]);
//            $branch_name = $row["Branch Name"];
//            $task_name = $row["Task Name"];
//            $sub_task_name = $row["Subtask Name"];
//            $sub_sub_task_name = $row["Sub-Subtask Name"];
//            $checklist_name = $row["CheckList Items"];
            array_push($branch_list, $branch_array[1]);
        }

        $data = array(
            "task_id" => $task_id,
            "task_name" => $task_name,
            'firm_id' => $ddl_firm_name[$j],
            "boss_id" => $result['reporting_to'],
            "status" => $status,
//                    "template_id" => $template_id,
            "created_by" => $user_id,
            "created_on" => $created_on
        );

        print_r(array_unique($branch_list));
        $db_array = $excel_data_in_array;
        print_r($db_array);
//        echo json_encode($db_array);
    }

    public function generate_designation_id() {
        $designation_id = 'Desig_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('designation_header_all');
        $this->db->where('designation_id', $designation_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return $this->generate_designation_id();
        } else {
            return $designation_id;
        }
    }

    /*
     * designation upload
     */

    function upload_designation() {
        $path = $_FILES["excel_file"]["tmp_name"];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(TRUE);
        $excel = $objReader->load($path);

        $header_array = array("c0", "c1", "c2", "c3", "c4", "c5", "c7", "c6", "c8", "c9", "c10");

        $data_type_array = array("number" => array(),
            "string" => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
            "datetime" => array());
        $compulsary_column = "All";
        $error_message = "";
        $worksheet_instance = $excel->setActiveSheetIndex();
        $excel_data_in_array = $this->excel_validation_data($header_array, $data_type_array, $compulsary_column, $worksheet_instance);
        $response = array();
        $username = $this->session->userdata('login_session');
        $this->load->model("designation_model");
        if (is_array($excel_data_in_array)) {
            $table_array = array();
            foreach ($excel_data_in_array as $row) {
                $tr = array();
                $firm_id = "";
                $boss_id = "";
                foreach ($row as $c_count => $column) {
                    if ($c_count == "c5") {
                        $result = $this->getFirmByBranchName($column);
                        $column = $result->firm_id;
                        $firm_id = $result->firm_id;
                        $boss_id = $result->boss_id;
                    }
                    if ($c_count == "c2") {
                        if ($column == "Monthly") {
                            $column = "1";
                        }
                        if ($column == "Yearly") {
                            $column = "0";
                        }
                    }
                    if ($c_count == "c4") {
                        if ($column == "After Confirmation ") {
                            $column = "4";
                        }
                        if ($column == "After Training") {
                            $column = "3";
                        }
                        if ($column == "After Probation") {
                            $column = "2";
                        }
                        if ($column == "From Date of joining") {
                            $column = "1";
                        }
                    }
                    if ($c_count == "c3") {
                        if ($column == "Financial Year") {
                            $column = "2";
                        }
                        if ($column == "Calendar Year") {
                            $column = "3";
                        }

                        if ($column == "From the date of Joining") {
                            $column = "1";
                        }
                    }
                    if ($c_count == "c7") {
                        $column = $this->getSeniorDesignationByDesignationName($column, $firm_id);
                    }
                    if ($c_count == "c6") {
                        if ($column == "Y") {
                            $column = "1";
                        }
                        if ($column == "N") {
                            $column = "0";
                        }
                    }

                    $tr[$c_count] = $column;
                }
                $designation_id = $this->generate_designation_id();
                // Designation db array
                $data = array(
                    'designation_id' => $designation_id,
                    'designation_name' => $tr["c0"],
                    'designation_roles' => $tr["c1"],
                    'reporting_designation_id' => $tr["c7"],
                    'total_yearly_leaves' => $tr["c9"],
                    'total_monthly_leaves' => $tr["c10"],
                    'holiday_consider_in_leave' => $tr["c6"],
                    'carry_forward_period' => $tr["c2"],
                    'request_leave_from' => $tr["c4"],
                    'year_type' => $tr["c3"],
                    'firm_id' => $firm_id,
                    'created_on' => date("Y-m-d"),
                    'created_by' => $username,
                    'boss_id' => $boss_id
                );
                $result = $this->db->where(array("firm_id" => $firm_id, "boss_id" => $boss_id))->get("leave_header_all")->result_array();
                $leave_details = $result[0];

                $data_leave = array(
                    'designation_id' => $designation_id,
                    'type1' => $leave_details['type1'],
                    'type2' => $leave_details['type2'],
                    'type3' => $leave_details['type3'],
                    'type4' => $leave_details['type4'],
                    'type5' => $leave_details['type5'],
                    'type6' => $leave_details['type6'],
                    'type7' => $leave_details['type7'],
                    'firm_id' => $firm_id,
                    'created_on' => date("Y-m-d"),
                    'boss_id' => $boss_id
                );


                $add_designation = $this->designation_model->add_designation($data);
                if ($add_designation) {
                    $p_data_leave = $this->db->insert('leave_header_all', $data_leave);
                    array_push($table_array, $tr);
                }
            }
            $response["data_code"] = 200;
            $response["data"] = $table_array;
            $response["error"] = $excel_data_in_array;
        } else {
            $response["data_code"] = 300;
            $response["error"] = $excel_data_in_array;
        }
        echo json_encode($response);
    }

}

?>
