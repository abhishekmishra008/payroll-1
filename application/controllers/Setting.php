<?php

class Setting extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('setting_model');
        $this->load->model('firm_model');
    }

    public function index($ddl_firm_id = '') { 

        if ($ddl_firm_id == '') {
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
            }

            $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
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

            $q1 = $this->db->query("Select firm_id,firm_no_of_employee,firm_name,firm_tag_line,firm_no_of_customers, firm_no_of_permitted_offices,selection_approach From  partner_header_all where `firm_id`= '$firm_id'");
            if ($q1->num_rows() > 0) {
                $record_q1 = $q1->result();
                $data['result_q1'] = $record_q1;
                $data['status'] = true;
            } else {
                $data['result_q1'] = "";
                $data['status'] = false;
            }
            $data['permission_rs'] = '';
            $data['ddl_firm_id'] = '';
            $data['prev_title'] = "Setting";
            $data['page_title'] = "Setting";
        } else {

            $qury_result = $this->setting_model->viewPermissionDetails($ddl_firm_id);
            if ($qury_result !== FALSE) {
                $permission_rs = $qury_result->row();
                $data['permission_rs'] = $permission_rs;
                $data['ddl_firm_id'] = $ddl_firm_id;
                $data['status'] = true;
            } else {
                $data['ddl_firm_id'] = '';
                $data['permission_rs'] = '';
                $data['status'] = false;
            }


            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
            }

            $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
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

            $q1 = $this->db->query("Select firm_id,firm_no_of_employee,firm_name,firm_tag_line,firm_no_of_customers,selection_approach, firm_no_of_permitted_offices From  partner_header_all where `firm_id`= '$firm_id'")->result();
            if (count($q1) > 0) {
                $record_q1 = $q1;
                $data['result_q1'] = $record_q1;
                $data['status'] = true;
            } else {
                $data['result_q1'] = "";
                $data['status'] = false;
            }



            $data['prev_title'] = "Setting";
            $data['page_title'] = "Setting";
        }


        //$this->load->view('admin/header');
        $this->load->view('hq_admin/navigation', $data);
        $this->load->view('hq_admin/Setting');
    }

    public function add_licience() {
        $firm_id = $this->input->post('firm_id');
        $firm_no_of_permitted_offices = $this->input->post('firm_no_of_permitted_offices');
        $firm_no_of_customers = $this->input->post('firm_no_of_customers');
        $firm_no_of_employee = $this->input->post('firm_no_of_employee');


        if (isset($firm_no_of_permitted_offices)) {
            $data = array(
                'firm_id' => $firm_id,
                'firm_no_of_permitted_offices' => $firm_no_of_permitted_offices,
            );
        }
        if (isset($firm_no_of_employee)) {
            $data = array(
                'firm_id' => $firm_id,
                'firm_no_of_employee' => $firm_no_of_employee
            );
        }
        if (isset($firm_no_of_customers)) {
            $data = array(
                'firm_id' => $firm_id,
                'firm_no_of_customers' => $firm_no_of_customers
            );
        }


        $result = $this->setting_model->update($data, $firm_id);


        if ($result == TRUE) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            echo json_encode($response);
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
            echo json_encode($response);
        }
    }

    //Add Branch setting function
    public function addBranchSetting() {

        if (!isset($_POST['add_due_date'])) {
            $add_due_date = '0';
        } else {
            $add_due_date = '1';
        }


        if (!isset($_POST['updt_due_date'])) {
            $updt_due_date = '0';
        } else {
            $updt_due_date = '1';
        }


        if (!isset($_POST['del_due_date'])) {
            $del_due_date = '0';
        } else {
            $del_due_date = '1';
        }


        if (!isset($_POST['add_due_date_task'])) {
            $add_due_date_task = '0';
        } else {
            $add_due_date_task = '1';
        }


        if (!isset($_POST['updt_due_date_task'])) {
            $updt_due_date_task = '0';
        } else {
            $updt_due_date_task = '1';
        }


        if (!isset($_POST['del_due_date_task'])) {
            $del_due_date_task = '0';
        } else {
            $del_due_date_task = '1';
        }


        if (!isset($_POST['add_task'])) {
            $add_task = '0';
        } else {
            $add_task = '1';
        }

        if (!isset($_POST['updt_task'])) {
            $updt_task = '0';
        } else {
            $updt_task = '1';
        }


        if (!isset($_POST['del_task'])) {
            $del_task = '0';
        } else {
            $del_task = '1';
        }


        if (!isset($_POST['add_task_assignment'])) {
            $add_task_assignment = '0';
        } else {
            $add_task_assignment = '1';
        }


        if (!isset($_POST['updt_task_assignment'])) {
            $updt_task_assignment = '0';
        } else {
            $updt_task_assignment = '1';
        }


        if (!isset($_POST['del_task_assignment'])) {
            $del_task_assignment = '0';
        } else {
            $del_task_assignment = '1';
        }


        if (!isset($_POST['add_service'])) {
            $add_service = '0';
        } else {
            $add_service = '1';
        }


        if (!isset($_POST['updt_service'])) {
            $updt_service = '0';
        } else {
            $updt_service = '1';
        }


        if (!isset($_POST['del_service'])) {
            $del_service = '0';
        } else {
            $del_service = '1';
        }


        if (!isset($_POST['add_work_on_service'])) {
            $add_work_on_service = '0';
        } else {
            $add_work_on_service = '1';
        }


        if (!isset($_POST['add_warning_conf'])) {
            $add_warning_conf = '0';
        } else {
            $add_warning_conf = '1';
        }


        if (!isset($_POST['updt_warning_conf'])) {
            $updt_warning_conf = '0';
        } else {
            $updt_warning_conf = '1';
        }


        if (!isset($_POST['del_warning_conf'])) {
            $del_warning_conf = '0';
        } else {
            $del_warning_conf = '1';
        }


        if (!isset($_POST['add_enquiry'])) {
            $add_enquiry = '0';
        } else {
            $add_enquiry = '1';
        }


//        if (!isset($_POST['updt_enquiry'])) {
//            $updt_enquiry = '0';
//        } else {
//            $updt_enquiry = '1';
//        }
//
//
//        if (!isset($_POST['del_enquiry'])) {
//            $del_enquiry = '0';
//        } else {
//            $del_enquiry = '1';
//        }


        if (!isset($_POST['work_on_enquiry'])) {
            $work_on_enquiry = '0';
        } else {
            $work_on_enquiry = '1';
        }


        if (!isset($_POST['add_employee'])) {
            $add_employee = '0';
        } else {
            $add_employee = '1';
        }


        if (!isset($_POST['updt_employee'])) {
            $updt_employee = '0';
        } else {
            $updt_employee = '1';
        }


        if (!isset($_POST['del_employee'])) {
            $del_employee = '0';
        } else {
            $del_employee = '1';
        }


        if (!isset($_POST['add_customer'])) {
            $add_customer = '0';
        } else {
            $add_customer = '1';
        }


        if (!isset($_POST['updt_customer'])) {
            $updt_customer = '0';
        } else {
            $updt_customer = '1';
        }


        if (!isset($_POST['del_customer'])) {
            $del_customer = '0';
        } else {
            $del_customer = '1';
        }


        if (!isset($_POST['add_template_store'])) {
            $add_template_store = '0';
        } else {
            $add_template_store = '1';
        }


        if (!isset($_POST['updt_template_store'])) {
            $updt_template_store = '0';
        } else {
            $updt_template_store = '1';
        }


        if (!isset($_POST['del_template_store'])) {
            $del_template_store = '0';
        } else {
            $del_template_store = '1';
        }

        if (!isset($_POST['add_knowledge_store'])) {
            $add_knowledge_store = '0';
        } else {
            $add_knowledge_store = '1';
        }


        if (!isset($_POST['updt_knowledge_store'])) {
            $updt_knowledge_store = '0';
        } else {
            $updt_knowledge_store = '1';
        }

        if (!isset($_POST['del_knowledge_store'])) {
            $del_knowledge_store = '0';
        } else {
            $del_knowledge_store = '1';
        }

        if (!isset($_POST['gst_tab'])) {
            $gst_tab = '0';
        } else {
            $gst_tab = '1';
        }

        if (!isset($_POST['change_company_logo'])) {
            $change_company_logo = '0';
        } else {
            $change_company_logo = '1';
        }


//        echo $add_due_date . ',' . $updt_due_date . ',' . $del_due_date . ':' . $add_due_date_task . ',' . $updt_due_date_task . ',' . $del_due_date_task . "<br>";
//        echo $add_task . ',' . $updt_task . ',' . $del_task . ':' . $add_task_assignment . ',' . $updt_task_assignment . ',' . $del_task_assignment . "<br>";
//        echo $add_service . ',' . $updt_service . ',' . $del_service . "<br>";
//        echo $add_work_on_service . ',' . $updt_work_on_service . ',' . $del_work_on_service . "<br>";
//        echo  $add_enquiry . ',' . $updt_enquiry . ',' . $del_enquiry . "<br>";
//        echo $work_on_enquiry . "<br>";
//        echo $add_employee . ',' . $updt_employee . ',' . $del_employee . "<br>";
//        echo $add_customer . ',' . $updt_customer . ',' . $del_customer . "<br>";
//        echo $add_template_store . ',' . $updt_template_store . ',' . $del_template_store . "<br>";
//        echo $add_knowledge_store . ',' . $updt_knowledge_store . ',' . $del_knowledge_store . "<br>";
//        exit;

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $boss_id = $result1['boss_id'];
        }

        $fetch_firm_id = $this->input->post('get_firm_name');

        if (empty($fetch_firm_id)) {
            $response['id'] = 'ddl_firm_name_fetch';
            $response['error'] = 'Please Select Branch';
            echo json_encode($response);
            exit;
        }

        $modified_on = date('y-m-d h:i:s');
        $modified_by = $user_id;

//
//        $query = $this->firm_model->getFirmData($boss_id);
//        if ($query != FALSE) {
//            $result = $query->result();
//
//            $adata_count = 0;
//            foreach ($result as $value) {
//                $fetch_firm_id = $value->firm_id;

        $data = array(
            'create_due_date_permission' => $add_due_date . ',' . $updt_due_date . ',' . $del_due_date . ':' . $add_due_date_task . ',' . $updt_due_date_task . ',' . $del_due_date_task,
            'create_task_assignment' => $add_task . ',' . $updt_task . ',' . $del_task . ':' . $add_task_assignment . ',' . $updt_task_assignment . ',' . $del_task_assignment,
            'create_service_permission' => $add_service . ',' . $updt_service . ',' . $del_service,
            'warning_conifg_permission' => $add_warning_conf . ',' . $updt_warning_conf . ',' . $del_warning_conf,
            'work_on_services' => $add_work_on_service,
            'enquiry_generate_permission' => $add_enquiry,
            'enquiry_work_permission' => $work_on_enquiry,
            'employee_permission' => $add_employee . ',' . $updt_employee . ',' . $del_employee,
            'customer_permission' => $add_customer . ',' . $updt_customer . ',' . $del_customer,
            'template_store_permission' => $add_template_store . ',' . $updt_template_store . ',' . $del_template_store,
            'knowledge_store_permission' => $add_knowledge_store . ',' . $updt_knowledge_store . ',' . $del_knowledge_store,
            'gst_tab' => $gst_tab,
            'change_company_logo' => $change_company_logo,
            'modified_by' => $modified_by,
            'modified_on' => $modified_on,
        );

        //var_dump($data);
        $updte_qury = $this->setting_model->updateUserPermission($fetch_firm_id, $data);
        $db2 = $this->load->database('db2', TRUE);
        $result = $db2->set("activity_status", $gst_tab)->where(array("firm_id" => $fetch_firm_id, "user_type" => 1))->update("customer_header_all");
//                $adata_count++;
//            }

        if ($updte_qury > 0) {
            $response["result"] = $db2->last_query();
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response["result"] = $db2->last_query();
            $response['message'] = 'fail';
            $response['code'] = 204;
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    //edit profile
    public function editprofile() {
        $cname = $this->input->get_post("cname");
        $tline = $this->input->get_post("tline");

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
            $user_id = $result1['user_id'];
        }

        if (trim(empty($cname))) {
            $response['id'] = 'cname';
            $response['error'] = 'Please Enter Company Name';
        } else if (trim(empty($tline))) {
            $response['id'] = 'tline';
            $response['error'] = 'Please Enter Tag Line';
        } else if (trim(empty($_FILES['FileUpload1']['name']))) {

            $data = array(
                'firm_name' => $cname,
                'firm_tag_line' => $tline
            );
            $result_form = $this->setting_model->updateprofile($firm_id, $data);
            if ($result_form == TRUE) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else if ($result_form == 0) {
                $response['message'] = 'no_update';
                $response['code'] = 203;
                $response['status'] = 'no_update';
            } else {
                $response['message'] = 'fail';
                $response['code'] = 204;
                $response['status'] = false;
            }
        } else {
            $data = array(
                'firm_name' => $cname,
                'firm_tag_line' => $tline
            );

            $fileName = $this->upload_image1($firm_id);
            $data1 = array(
                'firm_logo' => $fileName
            );
            $result = $this->setting_model->uploadlogo($firm_id, $user_id, $data1);

            $result_form = $this->setting_model->updateprofile($firm_id, $data);

            if ($result_form == TRUE) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else if ($result_form == 0) {
                $response['message'] = 'no_update';
                $response['code'] = 203;
                $response['status'] = 'no_update';
            } else {
                $response['message'] = 'fail';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }

    public function uploadlogo() {

        $q2 = $this->db->query("Select firm_logo From  user_header_all where `firm_id`= '$firm_id'");
        if ($q2->num_rows() > 0) {
            $record_q2 = $q2->result();
            $data3['result_q2'] = $record_q2;
            $data3['status'] = true;
        } else {
            $data3['result_q2'] = "";
            $data3['status'] = false;
        }

        $data3['prev_title'] = "Setting";
        $data3['page_title'] = "Setting";
        // print_r($data3);
        //$this->load->view('hq_admin/header', $data3);
    }

    //uploading imaage on profile
    public function upload_image1($firm_id) {

        $response = array();
        $user_id = $firm_id; // session or user_id
//        var_dump($_FILES);
        if (isset($_FILES['FileUpload1']) && $_FILES['FileUpload1']['error'] != '4') :
            $files = $_FILES;

            $_FILES['FileUpload1']['name'] = $files['FileUpload1']['name'];
            $_FILES['FileUpload1']['type'] = $files['FileUpload1']['type'];
            $_FILES['FileUpload1']['tmp_name'] = $files['FileUpload1']['tmp_name'];
            $_FILES['FileUpload1']['error'] = $files['FileUpload1']['error'];
            $_FILES['FileUpload1']['size'] = $files['FileUpload1']['size'];
            $config['upload_path'] = './uploads/gallery/logo/';
            $target_path = './uploads/gallery/logo/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|';
//                $config['allowed_types'] = 'pdf';
            $config['max_size'] = '50000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '800'; // image max width
            $config['max_height'] = '532';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = $_FILES['FileUpload1']['name'];
            $data = array('upload_data' => $this->upload->data());


            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('FileUpload1');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['FileUpload1']['name'] . ' ' . $error['upload_error'];
                    $response = "invalid";
                    return $response;
                } else {
                    return $fileName;
                    // resize code
                    $path = $data['upload_data']['full_path'];
                    $q['name'] = $data['upload_data']['file_name'];
                    $configi['image_library'] = 'gd2';
                    $configi['source_image'] = $path;
                    $configi['new_image'] = $config;
                    $configi['create_thumb'] = TRUE;
                    $configi['maintain_ratio'] = TRUE;
                    $configi['width'] = 100; // new size
                    $configi['height'] = 100;
                    $this->load->library('image_lib');
                    $this->image_lib->clear();
                    $this->image_lib->initialize($configi);
                    $this->image_lib->resize();
                    $images[] = $fileName;
                }
            }
//            endfor;
        endif;
    }

    function personnel_assignment_selection_approach() {

        if (!is_null($this->input->post_get("inter"))) {
            $network = $this->input->post_get("inter");
        }

        if (!is_null($this->input->post_get("design"))) {
            $approach = $this->input->post_get("design");
        }

        $session_value = $this->customer_model->get_firm_id();
        if ($session_value !== false) {
            $firm_id = $session_value['firm_id'];
        }

        if (!is_null($network) && !is_null($approach) && !is_null($firm_id)) {

            $value = $network . ":" . $approach;
            $result = $this->db->set("selection_approach", $value)
                    ->where("firm_id", $firm_id)
                    ->update("partner_header_all");
            if ($result) {
                echo json_encode(array("status" => 200, "message" => "Succssfully Save Changes"));
            } else {
                echo json_encode(array("status" => 300, "message" => "Failed Save Changes"));
            }
        } else {
            echo json_encode(array("status" => 300, "message" => "Something went to wrong"));
        }
    }

}

?>