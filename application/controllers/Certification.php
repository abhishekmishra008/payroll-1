<?php

Class Certification extends CI_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('Certification_model');
    }

    public function index($firm_id = '') {




        $q = $this->db->query("SELECT * FROM `course_header_all`");
        if ($q->num_rows() > 0) {
            $record = $q->result();
            $data['result'] = $record;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $data['result'] = '';
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
//        $data['prev_title'] = "pre";
        $data['prev_title'] = "Add Certification";
        $data['page_title'] = "Add Certification";
        $this->load->view('admin/Certification', $data);
    }

    //generate certificate Id
    public function certification_id() {
        $certification_id = 'Certificate_' . rand(100, 1000);
        $this->db->select('*');
        $this->db->from('certification_header_all');
        $this->db->where('certification_id', $certification_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return generate_user_id();
        } else {
            return $certification_id;
        }
    }

    public function get_edit_certificate() {
        $certification_id = $this->input->post('certification_id');
        $edit_query = $this->db->query("SELECT * from  certification_header_all where certification_id='$certification_id'");
        if ($edit_query->num_rows() > 0) {
            $record1 = $edit_query->result();

            $response['rec'] = $record1;
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




    public function upload_image() {
        $response = array();
//        var_dump($_FILES);
        if (isset($_FILES['FileUpload1']) && $_FILES['FileUpload1']['error'] != '4') :
            $files = $_FILES;
//            $count = count($_FILES['FileUpload1']['name']); // count element
//            for ($i = 0; $i < $count; $i++):
            $_FILES['FileUpload1']['name'] = $files['FileUpload1']['name'];
            $_FILES['FileUpload1']['type'] = $files['FileUpload1']['type'];
            $_FILES['FileUpload1']['tmp_name'] = $files['FileUpload1']['tmp_name'];
            $_FILES['FileUpload1']['error'] = $files['FileUpload1']['error'];
            $_FILES['FileUpload1']['size'] = $files['FileUpload1']['size'];
            $config['upload_path'] = './uploads/gallery/';
            $target_path = './uploads/gallery/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|ppt|pptx';
            $domainName = $_SERVER['HTTP_HOST'] . '/';
//                $config['allowed_types'] = 'pdf';
            $config['max_size'] = '800000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '800'; // image max width
            $config['max_height'] = '532';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = $_FILES['FileUpload1']['name'];
            $base_url = base_url();
            $target_path = $base_url . 'uploads/gallery/' . $fileName;
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('FileUpload1');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['FileUpload1']['name'] . ' ' . $error['upload_error'];
                    $response = "invalid";
                    return $target_path;
                } else {
                    return $target_path;
                    // resize code
                    $path = $data['upload_data']['full_path'];
                    $q['name'] = $data['upload_data']['file_name'];
                    $configi['image_library'] = 'gd2';
                    $configi['source_image'] = $path;
                    $configi['new_image'] = $target_path;
                    $configi['create_thumb'] = TRUE;
                    $configi['maintain_ratio'] = TRUE;
                    $configi['width'] = 75; // new size
                    $configi['height'] = 50;
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

    //add certificate
    public function add_certificate() {
        $certification_id = $this->certification_id();
        $certification_name = $this->input->post('certification_name');
        $category = $this->input->post('category');
        $affiliated_with = $this->input->post('affiliated_with');
        $certification_amount = $this->input->post('certification_amount');
        $certification_summary = $this->input->post('certification_summary');
        $certification_description = $this->input->post('certification_description');
        $useful_for = $this->input->post('useful_for');
        $linked_with_courses = $this->input->post('course');
        $created_on = date('y-m-d h:i:s');
        $modified_on = date('y-m-d h:i:s');
        $certification_dummy_image = $this->upload_image();
        $rating = $this->input->post('rating');
        if (is_array($linked_with_courses)) {
            $count_array = count($linked_with_courses);
            $result_course = array();
            for ($j = 0; $j < $count_array; $j++) {

                $result_course[] = $linked_with_courses[$j];
            }
            $result_course = implode(",", $result_course);
        }
        if (empty(trim($certification_name))) {
            $response['id'] = 'certification_name';
            $response['error'] = 'Please Enter Certification Name';
        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $certification_name)) {
            $response['id'] = 'certification_name';
            $response['error'] = 'Only Space Is Allowed';
        } else if (empty(trim($category))) {
            $response['id'] = 'category';
            $response['error'] = 'Please Enter category';
        } elseif (empty(trim($affiliated_with))) {
            $response['id'] = 'affiliated_with';
            $response['error'] = 'Please Enter Affiliated With Name';
        } elseif (empty(trim($certification_amount))) {
            $response['id'] = 'certification_amount';
            $response['error'] = 'Enter Certification Amount';
        } elseif (empty(trim($certification_summary))) {
            $response['id'] = 'certification_summary';
            $response['error'] = 'Enter Certification Summary';
        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $certification_summary)) {
            $response['id'] = 'certification_summary';
            $response['error'] = 'Only Space is Allowed';
        } elseif (empty(trim($certification_description))) {
            $response['id'] = 'certification_description';
            $response['error'] = 'Enter Certification Description';
        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $certification_description)) {

            $response['id'] = 'certification_description';
            $response['error'] = 'Only Space is Allowed';
        } else {

            $data = array(
                'certification_id' => $certification_id,
                'certification_name' => $certification_name,
                'category' => $category,
                'affiliated_with' => $affiliated_with,
                'certification_amount' => $certification_amount,
                'certification_summary' => $certification_summary,
                'certification_description' => $certification_description,
                'useful_for' => $useful_for,
                'linked_with_courses ' => $result_course,
                'rating' => $rating,
                'created_on' => $created_on,
                'certification_dummy_image' => $certification_dummy_image
            );


            $result = $this->Certification_model->insert_Certification($data);
            if ($result == TRUE) {
                $response['message'] = 'success';
                $response['code'] = 200;
                $response['status'] = true;
            } else {
                $response['message'] = 'No data to display';
                $response['code'] = 204;
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }

    public function get_course() {//fetch courses 


        $query = $this->db->query("SELECT * FROM `course_header_all`");
        if ($query->num_rows() > 0) {
            $data = array('course_name' => array(), 'course_id' => array());
            foreach ($query->result() as $row) {
                $data['course_name'] = $row->course_name;
                $data['course_id'] = $row->course_id;
                $data['course_category'] = $row->course_category;
                $response['course_data'][] = ['course_name' => $row->course_name, 'course_id' => $row->course_id, 'course_category' => $row->course_category];
            }
//            var_dump($response);
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
            $this->load->helper('form');
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //view recommendation list controller
    public function view_recommendation($firm_id = '') {
        //$data['prev_title'] = "pre";
        $data['prev_title'] = "View Recommendation";
        $data['page_title'] = "View Recommendation";


        $q1 = $this->db->query("SELECT * FROM `course_recommendation_all`");
        if ($q1->num_rows() > 0) {
            $record = $q1->result();
            foreach ($record as $row) {
                $course_id = $row->course_id;
                $recommendation_id = $row->recommendation_id;
                $recommended_to = $row->recommended_to;
                $comment = $row->comment;
                $q2 = $this->db->query("SELECT * FROM `course_header_all` WHERE `course_id`='$course_id'");
                if ($q2->num_rows() > 0) {
                    $record2 = $q2->row();
                    $course_name = $record2->course_name;
                } else {
                    $course_name = "";
                }
                $q3 = $this->db->query("SELECT * FROM `recommendation_header_all` WHERE `recommendation_id`='$recommendation_id'");
                if ($q3->num_rows() > 0) {
                    $record3 = $q3->row();
                    $name = $record3->name;
                } else {
                    $name = "";
                }
                $data['result'][] = ['course_id' => $course_id, 'course_name' => $course_name, 'recommended_to' => $recommended_to, 'comment' => $comment, 'name' => $name, 'recommendation_id' => $recommendation_id];
            }
        } else {
            $data['result'][] = '';
        }
        $this->load->view('admin/view_recommendation_list', $data);
    }

    public function change_rec_status() {
        $course_id = $this->input->post('course_id');
        $status = 1;
        $data = array(
            "course_id" => $course_id,
            "status" => $status
        );
        $this->db->where("course_id = '$course_id'");
        $this->db->update('course_recommendation_all', $data);
        if ($this->db->affected_rows() !== "") {
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

    public function change_status_rec() {
        $course_id = $this->input->post('courseid');
        $status = 2;
        $data = array(
            "course_id" => $course_id,
            "status" => $status
        );
        $this->db->where("course_id = '$course_id'");
        $this->db->update('course_recommendation_all', $data);
        if ($this->db->affected_rows() !== "") {
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

    public function delete_certificte() {
        $certification_id = $this->input->post('certification_id');
        $query0 = $this->db->query("DELETE FROM `certification_header_all` WHERE `certification_id`='$certification_id'");
        if ($query0 == true) {
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

    public function update_certification() {
        $certification_id = $this->input->post('certification_id');
        $certification_name = $this->input->post('certification_name');
        $rating = $this->input->post('rating');
        $category = $this->input->post('category');
        $affiliated_with = $this->input->post('affiliated_with');
        $certification_amount = $this->input->post('certification_amount');
        $certification_summary = $this->input->post('certification_summary');
        $certification_description = $this->input->post('certification_description');
        $useful_for = $this->input->post('useful_for');
        $linked_with_courses = $this->input->post('linked_with_courses');
        $created_on = date('y-m-d h:i:s');
        $modified_on = date('y-m-d h:i:s');
        $certification_dummy_image = $this->edit_image();
        $count_array = count($linked_with_courses);
        $result_course = array();
        for ($j = 0; $j < $count_array; $j++) {

            $result_course[] = $linked_with_courses[$j];
        }
        $result_course = implode(",", $result_course);
//        if (empty(trim($certification_name))) {
//            $response['id'] = 'certification_name';
//            $response['error'] = 'Please Enter Certification Name';
//        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $certification_name)) {
//            $response['id'] = 'certification_name';
//            $response['error'] = 'Only Space Is Allowed';
//        } else if (empty(trim($category))) {
//            $response['id'] = 'category';
//            $response['error'] = 'Please Enter category';
//        } elseif (empty(trim($affiliated_with))) {
//            $response['id'] = 'affiliated_with';
//            $response['error'] = 'Please Enter Affiliated With Name';
//        } elseif (empty(trim($certification_amount))) {
//            $response['id'] = 'certification_amount';
//            $response['error'] = 'Enter Certification Amount';
//        } elseif (empty(trim($certification_summary))) {
//            $response['id'] = 'certification_summary';
//            $response['error'] = 'Enter Certification Summary';
//        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $certification_summary)) {
//            $response['id'] = 'certification_summary';
//            $response['error'] = 'Only Space is Allowed';
//        } elseif (empty(trim($certification_description))) {
//            $response['id'] = 'certification_description';
//            $response['error'] = 'Enter Certification Description';
//        } elseif (!preg_match("/^[A-Za-zéåäöÅÄÖ\s\ ]*$/", $certification_description)) {
//
//            $response['id'] = 'certification_description';
//            $response['error'] = 'Only Space is Allowed';
//        } else {

        $data = array(
            'certification_id' => $certification_id,
            'certification_name' => $certification_name,
            'rating' => $rating,
            'category' => $category,
            'affiliated_with' => $affiliated_with,
            'certification_amount' => $certification_amount,
            'certification_summary' => $certification_summary,
            'certification_description' => $certification_description,
            'useful_for' => $useful_for,
            'linked_with_courses ' => $linked_with_courses,
            'created_on' => $created_on,
            'certification_dummy_image' => $certification_dummy_image
        );


        $this->db->where('certification_id', $certification_id);
        $res = $this->db->update('certification_header_all', $data);

        if ($res == TRUE) {
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
//        }
        echo json_encode($response);
    }

    public function edit_image() {
        $response = array();
//        var_dump($_FILES);
        if (isset($_FILES['FileUpload1']) && $_FILES['FileUpload1']['error'] != '4') :
            $files = $_FILES;
//            $count = count($_FILES['FileUpload1']['name']); // count element
//            for ($i = 0; $i < $count; $i++):
            $_FILES['FileUpload1']['name'] = $files['FileUpload1']['name'];
            $_FILES['FileUpload1']['type'] = $files['FileUpload1']['type'];
            $_FILES['FileUpload1']['tmp_name'] = $files['FileUpload1']['tmp_name'];
            $_FILES['FileUpload1']['error'] = $files['FileUpload1']['error'];
            $_FILES['FileUpload1']['size'] = $files['FileUpload1']['size'];
            $config['upload_path'] = './uploads/gallery/';
            $target_path = './uploads/gallery/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|xlsx|ppt|pptx';
            $domainName = $_SERVER['HTTP_HOST'] . '/';
//                $config['allowed_types'] = 'pdf';
            $config['max_size'] = '800000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '800'; // image max width
            $config['max_height'] = '532';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $fileName = $_FILES['FileUpload1']['name'];
            $base_url = base_url();
            $target_path = $base_url . 'uploads/gallery/' . $fileName;
            $data = array('upload_data' => $this->upload->data());
            if (empty($fileName)) {
                return false;
            } else {
                $file = $this->upload->do_upload('FileUpload1');
                if (!$file) {
                    $error = array('upload_error' => $this->upload->display_errors());
                    $response['error'] = $files['FileUpload1']['name'] . ' ' . $error['upload_error'];
                    $response = "invalid";
                    return $target_path;
                } else {
                    return $target_path;
                    // resize code
                    $path = $data['upload_data']['full_path'];
                    $q['name'] = $data['upload_data']['file_name'];
                    $configi['image_library'] = 'gd2';
                    $configi['source_image'] = $path;
                    $configi['new_image'] = $target_path;
                    $configi['create_thumb'] = TRUE;
                    $configi['maintain_ratio'] = TRUE;
                    $configi['width'] = 75; // new size
                    $configi['height'] = 50;
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

    public function get_edit_recommendations() {
        $recommendation_id = $this->input->post('recommendation_id');
        $edit_query = $this->db->query("SELECT * from  course_recommendation_all where recommendation_id='$recommendation_id'");
        if ($edit_query->num_rows() > 0) {
            $record1 = $edit_query->result();

            $response['rec'] = $record1;
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

    public function update_recommendation() {
        $recommendation_id = $this->input->post('recommendation_id');
        $comment = $this->input->post('comment');


        $data = array(
            'recommendation_id' => $recommendation_id,
            'comment' => $comment
        );

        $this->db->where('recommendation_id', $recommendation_id);
        $res = $this->db->update('course_recommendation_all', $data);

        if ($res == TRUE) {
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

}

?>