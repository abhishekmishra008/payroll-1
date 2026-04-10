<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HtmltoPDF extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('htmltopdf_model');
        $this->load->model('customer_model');
        $this->load->library('Pdf');
    }

    public function index() {
       // $data['customer_data'] = $this->htmltopdf_model->fetch();
        $this->load->view('human_resource/htmltopdf');
    }

    public function details() {
//       die();
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);
        $data['customer_details'] = $this->htmltopdf_model->fetch_single_details($user_id);
        $this->load->view('human_resource/htmltopdf', $data);
    }

    public function pdfdetails() {
//     print_r(phpinfo());
        $session_data = $this->session->userdata('login_session');
        $user_id = ($session_data['emp_id']);

        $html_content = '<h3 align="center">Convert HTML to PDF in CodeIgniter using Dompdf</h3>';
        $html_content .= $this->htmltopdf_model->fetch_single_details();
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("Pay_slip" . $user_id . ".pdf", array("Attachment" => 0));
    }

}

?>