<?php

require_once("application/PHPExcel/PHPExcel.php");

class Excel_Upload_hr extends CI_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('emp_model');
        $this->load->model('hr_model');
        $this->load->model('email_sending_model');
        $this->load->model('designation_model');
    }

    public function index() {

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);
        //var_dump($result2);
        if ($result2 !== false) {
            $re = $result2->row();
            $firm_id = $re->firm_id;
        }

        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }
        $result2 = $this->db->query("SELECT * FROM `user_header_all` WHERE `email`='$email_id'");
        if ($result2->num_rows() > 0) {
            $record = $result2->row();
            $user_id = $record->user_id;
            $result3 = $this->db->query("SELECT leave_approve_permission FROM `user_header_all` WHERE `user_id`='$user_id'");
            if ($result3->num_rows() > 0) {
                $record3 = $result3->row();
                $value_permit = $record->leave_approve_permission;
                $data['val'] = $value_permit;
            } else {
                $data['val'] = '';
            }
        }
        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and  `user_id`='$user_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            if ($firm_logo == "" && $firm_name == "") {

                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }
        $data['prev_title'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['firm_id'] = $firm_id;
        $this->load->view('human_resource/excel', $data);
    }

    public function load_excel_data() {
        
    }

    public function fetch() {

        $data = $this->designation_model->select();
        $output = '
  <h3 align="center">Total Data - ' . $data->num_rows() . '</h3>
  <table class="table table-striped table-bordered">
   <tr>
    <th>Customer Name</th>
    <th>Address</th>
    <th>Email</th>
    <th>Mobile</th>
    
   </tr>
  ';
        foreach ($data->result() as $row) {
            $output .= '
   <tr>
    <td>' . $row->name . '</td>
    <td>' . $row->address . '</td>
    <td>' . $row->email . '</td>
    <td>' . $row->mobile . '</td>
    
   </tr>
   ';
        }
        $output .= '</table>';
        if ($data != '') {
            $response['data_print'] = $output;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function akshay() {
        echo "Welcome to akshay waghe";
    }

    public function import() {


        $path = $_FILES["excel_file"]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);
        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
                $holiday_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $description = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $color = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $holiday_date = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $holiday_image = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $holiday_applied_in = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $data[] = array(
                    'holiday_name' => $holiday_name,
                    'description' => $description,
                    'color' => $color,
                    'holiday_date' => $holiday_date,
                    'holiday_image' => $holiday_image,
                    'holiday_applied_in' => $holiday_applied_in
                );
            }
        }


//        foreach ( range('A', $object->getActiveSheet()->getHighestColumn()) as $column_key) {
//              $customer_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
//        }
//        print_r($customer_name);
//        print_r($data);
        $output = ' 
  <table class="table table-striped table-bordered">
   <tr>
    <th>holiday Name</th>
    <th>description</th>
    <th>color</th>
    <th>holiday_date</th>
     <th>holiday_image</th>
     <th>holiday_applied_in</th>
   </tr>';


        for ($z = 0; $z < count($data); $z++) {
            $cust_name = $data[$z]['holiday_name'];

            $output .= '
    <tr>';
            if (strlen($cust_name) > 20) {
                $output .= '<td bgcolor="#F85050">' . "Enter valid name" . '</td>';
            } else {
                $output .= '<td>' . $data[$z]['holiday_name'] . '</td>';
            }

            $output .= ' <td>' . $data[$z]['description'] . '</td>
    <td>' . $data[$z]['color'] . '</td>
    <td>' . $data[$z]['holiday_date'] . '</td> 
    <td>' . $data[$z]['holiday_image'] . '</td>
    <td>' . $data[$z]['holiday_applied_in'] . '</td>
   </tr>';
        }
        $output .= '</table>';

        if ($output != "") {
            $response['data_print'] = $output;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);

//        print_r($data);
//   $this->excel_import_model->insert($data);
//   echo 'Data Imported successfully';
//   
//   
//   
//        $path1 = $_FILES["excel_file"]["tmp_name"];
//         $object1 = PHPExcel_IOFactory::load($path1);
//        
//       
//        $worksheet1 = $object1->getActiveSheet();
//        $highestRow1 = $worksheet1->getHighestRow();
//        $highestColumn1 = $worksheet1->getHighestColumn();
//        
//        
//        echo $monthly_file = $_FILES['excel_file']['name'];
    }

}

?>