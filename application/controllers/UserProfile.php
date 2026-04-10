<?php

require_once FCPATH . 'vendor/autoload.php';
use Dompdf\Dompdf;


class UserProfile extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Emp_model');
        $this->load->model('emp_model');
        $this->load->model('firm_model');
        $this->load->model('email_sending_model');
        $this->load->model('customer_model');
        $this->load->helper('dump_helper');
    }

    public function index() {
        // dd("abhishek mishra");
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $email_id = ($session_data['user_id']);
            $emp_id = ($session_data['emp_id']);
        } else {
            $email_id = $this->session->userdata('login_session');
        }
        if ($email_id === "") {
            $email_id = $this->session->userdata('login_session');
        }

        
        $user_id = $emp_id;
        $query22 = $this->db->query("SELECT t_salarytype.value, m_salarytype.pay_type FROM t_salarytype inner join m_salarytype on t_salarytype.payroll_id=m_salarytype.earning_id where user_id='$user_id'");
        $this->db->last_query();
        if ($query22->num_rows() > 0) {
            $record1 = $query22->result();
            $data['record1'] = $record1;
        } else {
            $data['record1'] = array();
        }

        $query12 = $this->db->query("SELECT t_standarddeductions.id, t_standarddeductions.value,m_standarddeductions.deduction FROM t_standarddeductions INNER JOIN m_standarddeductions ON t_standarddeductions.deduction_id= m_standarddeductions.deduction_id where  user_id='$user_id'");
        $this->db->last_query();
        if ($query12->num_rows() > 0) {
            $record = $query12->result();
            $data['record'] = $record;
        } else {
            $data['record'] = array();
        }
        
        $query14 = $this->db->query("Select * from  t_staffloan where user_id ='$user_id'");
        $this->db->last_query();
        if ($query14->num_rows() > 0) {
            $record_loan = $query14->result();
            $data['record_loan'] = $record_loan;
        } else {
            $data['record_loan'] = array();
        }

        
        $query15 = $this->db->query("Select * from  t_perfomanceallowance where user_id ='$user_id'");
        $this->db->last_query();
        if ($query15->num_rows() > 0) {
            $record_pa = $query15->result();
            $data['record_pa'] = $record_pa;
        } else {
            $data['record_pa'] = array();
        }


        // Leave Details -- Tab
        $query13 = $this->db->query("SELECT ua.*, senior.user_name senior_name FROM user_header_all ua LEFT JOIN user_header_all senior ON senior.user_id = ua.senior_user_id where ua.user_id = '$user_id'");
        if ($query13->num_rows() > 0) {
            $result = $query13->result();
            $data['result'] = $result;
			$record_l = $query13->row();
			$senior_user_id = $record_l->senior_user_id;
			$data['senior_user_id'] = $senior_user_id;
			$data['senior_name'] = isset($record_l->senior_name) && !empty($record_l->senior_name) ? $record_l->senior_name : "Not Assigned";
			$record_leave_user = $query13->result();
        } else {
            $data['result'] = array();
			$record_leave_user = array();
        }
        $type_balance = $this->db->query("SELECT type1_balance, type2_balance, type3_balance, type4_balance, type5_balance, type6_balance, type7_balance FROM user_header_all where user_id = '$user_id'")->row();
        if ($type_balance > 0) {
            $data['type_balance'] = $type_balance;
        }

        // End Leave
        

        $query = $this->db->query("SELECT * FROM `user_header_all` where `firm_id`= '$firm_id' AND `email`='$email_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
            $firm_name = $record->user_name;
            $user_id = $record->user_id;
            $designation_id = $record->designation_id;
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


        $qur = $this->db->query("SELECT * from `designation_header_all` where `designation_id`='$designation_id'");
        //echo $this->db->last_query();
        // var_dump($qur);
        if ($this->db->affected_rows() > 0) {
            $record_desig = $qur->result();
        } else {
            $record_desig = array();
        }

        $aq = $this->db->query("select user_name from user_header_all where user_id='$senior_user_id' "); //query by pooja
        if ($this->db->affected_rows() > 0) {
            $record_dl = $aq->row();
            $user_name = $record_dl->user_name;
            $data['senuser_name'] = $user_name;
        }
        $qur1 = $this->db->query("SELECT * from `leave_header_all` where `designation_id`='$designation_id'");
        if ($qur1->num_rows() > 0) {
            $record_leave = $qur1->result();
        } else {
            $record_leave = array();
        }
        $qleave = $this->db->query("Select COUNT(leave_type) as cnt from `leave_transaction_all` where status='2' and user_id='$user_id'");
        $this->db->last_query();
        if ($qleave->num_rows() > 0) {
            $recordleave = $qleave->result();
            $data['recordleave'] = $recordleave;
        } else {
            $recordleave = array();
            $data['recordleave'] = array();
        }

        $query_attendance_employee = $this->db->query("select * from attendance_employee_applicable where user_id= '$user_id'");
        if ($query_attendance_employee->num_rows() > 0) {
            $record_day_time1 = $query_attendance_employee->result();
            // var_dump($record_day_time1);
            $data['result_emp_time'] = $record_day_time1;
        } else {
            $data['result_emp_time'] = array();
        }

        // $qur2 = $this->db->query("SELECT COUNT(`user_id`) as 'user_cnt' FROM `leave_transaction_all` WHERE `user_id`='$user_id' AND `leave_pay_type`='0' GROUP BY `user_id`");
        $qur2 = $this->db->query("select status,count(status)as 'user_cnt' from leave_transaction_all where user_id='$user_id' group by status ");
        $this->db->last_query();
        if ($qur2->num_rows() > 0) {
            $recrd = $qur2->result();
        } else {
            $with_pay_count = 0;
        }


        $query3 = $this->db->query("select user_id,firm_id, designation_id, type1,type2,type3,type4,type5,type6,type7 from user_header_all where user_id='$user_id'");
        $this->db->last_query();
        if ($query3->num_rows() > 0) {
            $records = $query3->row();
            $user_id = $records->user_id;
            $firm_id = $records->firm_id;
            $designation_id = $records->designation_id;
        }


        $query=$this->db->query("select total_leave_available,
        (select count(id) from leave_transaction_all lta where lta.user_id=uha.user_id and year(leave_date) = year(now()) and lta.status=2) as leave_taken from user_header_all uha where user_id='".$user_id."'");
        // echo  $this->db->last_query();
        $recordsq=array();
        if ($this->db->affected_rows() > 0) {
			$recordsq = $query->row();
            // dd('Line no 178 : ', $recordsq);
			$data['recordsq'] = $recordsq;
		}else{
			$data['recordsq'] = array();
		}

        $sql = "SELECT ph.pdf_url FROM partner_header_all ph WHERE ph.firm_id = ?";
        $userPdf = $this->db->query($sql, [$firm_id])->row();
        if(!empty($userPdf)) {
            $data['pdf_url'] = base_url() . "/assets/" . basename($userPdf->pdf_url);
        }
        // $data['recrd'] = $recrd;
        $data['record_desig'] = $record_desig;
        $data['record_leave_user'] = $record_leave_user;
        // $data['status'] = $status;
        // $data['with_pay_count'] = $with_pay_count;
        $data['record_leave'] = $record_leave;
        $data['firm_id'] = $firm_id;
        $data['prev_title'] = "User profile";
        $data['page_title'] = "User profile";
        $this->load->view('User_profile', $data);
    }

    function view_salary_details() {
        $user_id = $this->input->post_get("emp_id2");

        $query = $this->db->query("SELECT h_salarytype.id,h_salarytype.date, h_salarytype.value,m_salarytype.pay_type FROM h_salarytype INNER JOIN m_salarytype ON h_salarytype.payroll_id=m_salarytype.earning_id where user_id='$user_id'");

        $this->db->last_query();
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width: 100%;" id="data_table2" class="table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th> Amount</th>
                                            <th> Date</th>

                                              </tr>
                                    </thead>';
            $result2 = $query->result();
            foreach ($result2 as $row) {
                $data .= '<tr>
                    <td>' . $row->id . '</td>
                    <td>' . $row->pay_type . '</td>
                    <td>' . $row->value . '</td>
                    <td>' . $row->date . '</td>
                </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_data1"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    function view_Deu() {
        $user_id = $this->input->post_get("emp_id3");


        $query = $this->db->query("SELECT h_standarddeductions.id, h_standarddeductions.date,h_standarddeductions.value,m_standarddeductions.deduction FROM h_standarddeductions INNER JOIN m_standarddeductions ON h_standarddeductions.deduction_id= m_standarddeductions.deduction_id where user_id='$user_id'");

        $data = '';

        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width: 100%;" id="data_table3" class="table table-hover table-striped table-bordered dataTable dtr-inline" role="grid" aria-describedby="data_table3_info">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th> Amount</th>
                                            <th> Date</th>
                                              </tr>
                                    </thead>';
            $result2 = $query->result();
            foreach ($result2 as $row) {
                $data .= '<tr>
                    <td>' . $row->id . '</td>
                    <td>' . $row->deduction . '</td>
                    <td>' . $row->value . '</td>
                    <td>' . $row->date . '</td>
                    </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_data3"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    public function get_staffloan() {
        $user_id = $this->input->post_get("emp_id4");

        $query = $this->db->query("select * from h_staffloan where user_id='$user_id'");
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width:100% id="data_table4" class="table table-hover table-striped table-bordered dataTable dtr-inline" role="grid" aria-describedby="data_table4_info">
                   <thead>
                                                                            <tr>
                                                                          
                                                                                <th>Loan Details</th>
                                                                                <th>Loan Amount</th>
                                                                                <th>EMI Amount</th>
                                                                                <th>Start Month </th>
                                                                                <th> Year</th>
                                                                                <th>Total Month</th>
                                                                                <th>Sanction Date</th>
                                                                            </tr>
                                                                        </thead>';
            $result4 = $query->result();
            foreach ($result4 as $row) {
                $data .= '<tr><td>' . $row->loan_detail . '</td>
                        <td>' . $row->amount . '</td>
                      <td>' . $row->EMI_amt . '</td>
                      <td>' . $row->from_month . '</td>
                      <td>' . $row->Fyear . '</td>
                      <td>' . $row->total_month . '</td>
                      <td>' . $row->sanction_date . '</td>
                        
                        </tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_data3"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }

    public function get_pa() {
        $user_id = $this->input->post_get("emp_id5");
        $query = $this->db->query("select * from h_perfomanceallowance where user_id='$user_id'");

        $data = '';
        if ($this->db->affected_rows() > 0) {
            $data .= '<table style="width:100% id="data_table5" class="table  table-striped table-bordered dataTable dtr-inline">
                   <thead>
                                                                            <tr>
                                                                                  <th>Performance Bonus</th>
                                                                            
                                                                               <th> Year</th>
                                                                             
                                                                                <th> Date</th>
                                                                            </tr>
                                                                        </thead>';
            $result5 = $query->result();
            foreach ($result5 as $row) {
                $data .= '<tr> <td>' . $row->value . '</td> <td>' . $row->FYear . '</td><td>' . $row->Date_of_PA . '</td></tr>';
            }
            $data .= '</table>';
            $response["status"] = 200;
            $response["result_data3"] = $data;
        } else {
            $response["status"] = 201;
        }
        echo json_encode($response);
    }
    
	// Show user pdf function start 
        public function showUserPdf($userId = null) {
            try {
                if (empty($userId)) {
                    show_error("Invalid User ID", 400);
                }
                $session_data = $this->session->userdata('login_session');
                $sql = "SELECT ph.pdf_url 
                        FROM user_header_all uh
                        LEFT JOIN partner_header_all ph ON ph.firm_id = uh.firm_id
                        WHERE uh.user_id = ?";
                $user = $this->db->query($sql, [$userId])->row();

                if (!empty($user->pdf_url)) {
                    $pdfUrl = $user->pdf_url;
                    if (filter_var($pdfUrl, FILTER_VALIDATE_URL)) {
                        redirect($pdfUrl);
                    }
                    else {
                        $filename = FCPATH . ltrim($pdfUrl, '/');
                        if (file_exists($filename)) {
                            header("Content-type: application/pdf");
                            header("Content-Disposition: inline; filename=\"" . basename($filename) . "\"");
                            header("Content-Length: " . filesize($filename));
                            readfile($filename);
                        } else {
                            show_error("PDF file not found on server", 404);
                        }
                    }
                } else {
                    show_error("No PDF available for this user", 404);
                }

            } catch (Exception $e) {
                show_error("Error generating PDF: " . $e->getMessage(), 500);
            }
        }

        public function uploadUserPdf() {
            try {
                $firmId = $this->input->post('firm_id');
                if (empty($firmId)) {
                    echo json_encode([
                        "status" => 400,
                        "message" => "Invalid Firm ID"
                    ]);
                    return;
                }
                if (!isset($_FILES['pdf_url']) || $_FILES['pdf_url']['error'] !== UPLOAD_ERR_OK) {
                    echo json_encode([
                        "status" => 400,
                        "message" => "No PDF file uploaded or upload error"
                    ]);
                    return;
                }
                $fileTmpPath = $_FILES['pdf_url']['tmp_name'];
                $fileName = basename($_FILES['pdf_url']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($fileExt !== 'pdf') {
                    echo json_encode([
                        "status" => 400,
                        "message" => "Only PDF files are allowed"
                    ]);
                    return;
                }

                $newFileName = 'user_pdf_' . $firmId . '_' . time() . '.pdf';
                $uploadPath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . $newFileName;
                if (!move_uploaded_file($fileTmpPath, $uploadPath)) {
                    echo json_encode([
                        "status" => 500,
                        "message" => "Failed to move uploaded file"
                    ]);
                    return;
                }
                
                $sql = "UPDATE partner_header_all SET pdf_url = ? WHERE firm_id = ?";
                $this->db->query($sql, ['/assets/' . $newFileName, $firmId]);

                echo json_encode([
                    'status' => 200,
                    'message' => 'PDF uploaded successfully',
                    'pdf_url' => base_url('assets/' . $newFileName)
                ]);
            } catch (Exception $e) {
                show_error("Error uploading PDF: " . $e->getMessage(), 500);
            }
        }
    // Show user pdf function end

}

?>
