<?php

/**
 * Description of Hr_checklist_Controller
 *
 * @author User
 */
class Hr_checklist_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('Hr_CheckList_Model');
        $this->load->model('Hr_CheckList_DocumentModel');
        $this->load->model('hr_model');
    }

    function index() {
        $data['prev_title'] = "CheckList Master";
        $data['page_title'] = "CheckList Master";
        $data = $this->get_firm_name_nav($data);
        $this->load->helper('url');
        $this->load->view('human_resource/CheckListMaster', $data);
    }

    function get_firm_name_nav($data) {
        $result1 = $this->customer_model->get_firm_id();
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
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }
        $result2 = $this->hr_model->get_human_resource($firm_id);


        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id' and email='$email_id'");
        if ($query->num_rows() > 0) {


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
                return $data;
            }
        }
    }

    /*
     * create new checklist options
     */

    function create_checklist_option() {


        $login_user = $this->customer_model->get_firm_id();

        if (!is_null($this->input->post_get("check_title"))) {
            $this->Hr_CheckList_Model->title = $this->input->post_get("check_title");
        }


        if (!is_null($this->input->post_get("update_item_value"))) {
            $this->Hr_CheckList_Model->id = $this->input->post_get("update_item_value");
        } else {
            $this->Hr_CheckList_Model->id = 0;
        }

        if (!is_null($this->input->post_get("update_value"))) {
            $this->Hr_CheckList_Model->id = base64_decode($this->input->post_get("update_value"));
        } else {
            $this->Hr_CheckList_Model->id = 0;
        }


        if (!is_null($this->input->post_get("check_type"))) {
            $this->Hr_CheckList_Model->item_type = base64_decode($this->input->post_get("check_type"));
        }
        if (!is_null($this->input->post_get("check_description"))) {
            $this->Hr_CheckList_Model->check_descp = $this->input->post_get("check_description");
        }
        if (!is_null($this->input->post_get("check_option"))) {
            $this->Hr_CheckList_Model->option_type = base64_decode($this->input->post_get("check_option"));
        }
        if (!is_null($this->input->post_get("previouse_file_path"))) {
            $previous_path = $this->input->post_get("previouse_file_path");
        }

        if (!is_null($this->input->post_get("NasGeneratedId"))) {
            $this->Hr_CheckList_Model->file_nas_id = $this->input->post_get("NasGeneratedId");
        } else {
            $this->Hr_CheckList_Model->file_nas_id = "";
        }

        $this->load->model("Nas_modal");

        if ($this->Nas_modal->isNasConfigure()) {
            $file_name = $this->input->post_get("filename");
        } else {
            $file_name = "/uploads/hr/" . $this->upload_file();
        }


        if (!empty($file_name)) {
            $this->Hr_CheckList_Model->instruction_file = $file_name;
        } else if (isset($previous_path)) {
            $this->Hr_CheckList_Model->instruction_file = $previous_path;
        } else {
            $this->Hr_CheckList_Model->instruction_file = 0;
        }

        $this->Hr_CheckList_Model->status = 1;
        if ($this->Hr_CheckList_Model->id == 0) {
            $this->Hr_CheckList_Model->create_at = date('Y-m-d H:i:s');
            $this->Hr_CheckList_Model->create_by = $login_user["user_id"];
            if ($this->Hr_CheckList_Model->create_checklist_item()) {
                echo json_encode(array('response' => "Created Successfully.", "nas_id" => $this->Hr_CheckList_Model->file_nas_id));
            } else {
                echo json_encode(array('response' => "Failed To Create.", "nas_id" => $this->Hr_CheckList_Model->file_nas_id));
            }
        } else {
            $this->Hr_CheckList_Model->create_by = $login_user["user_id"];
            $this->Hr_CheckList_Model->modefied_at = date('Y-m-d H:i:s');
            if ($this->Hr_CheckList_Model->update_checklist_item(array("id" => $this->Hr_CheckList_Model->id))) {
                echo json_encode(array('response' => "updated Successfully.", "nas_id" => $this->Hr_CheckList_Model->file_nas_id));
            } else {
                $this->Hr_CheckList_Model->create_by = $login_user["user_id"];
                $this->Hr_CheckList_Model->modefied_at = date('Y-m-d H:i:s');
                if ($this->Hr_CheckList_Model->update_checklist_item()) {
                    echo json_encode(array('response' => "updated Successfully.", "nas_id" => $this->Hr_CheckList_Model->file_nas_id));
                } else {
                    echo json_encode(array('response' => "Failed To update.", "nas_id" => $this->Hr_CheckList_Model->file_nas_id));
                }
            }
        }
    }

    function getLoginUserID() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        return $user_id;
    }

    /*
     * assign new create checklist to user
     */

    function assign_checklist_to_user() {

        $user_id = $this->getLoginUserID();
// get loging user firm id
        $assign_check_list = $this->db->select(array("hr_authority", "assign_check_list"))
                        ->where(array("email" => $user_id))
                        ->get("user_header_all")->result();

        $hr_auth_list = explode(",", $assign_check_list[0]->hr_authority);

        $userlist = array();
        foreach ($hr_auth_list as $hr_user) {
            $firmusers_id = $this->db->select("user_id")
                            ->where(array("firm_id" => $hr_user))
                            ->get("user_header_all")->result();
            foreach ($firmusers_id as $id) {
                array_push($userlist, $id->user_id);
            }
        }


        if ($assign_check_list[0]->assign_check_list == "") {
            $assignuser_ck_list = array();
        } else {
            $assignuser_ck_list = explode(",", $assign_check_list[0]->assign_check_list);
        }

// check where it was already exists if yes add into assign user check list
        foreach ($userlist as $hr) {
            if (!in_array($hr, $assignuser_ck_list)) {
                array_push($assignuser_ck_list, $hr);
            }
        }

// update new assign user list
        $new_assign_check_list = implode(",", $assignuser_ck_list);

        $result = $this->db->set(array("assign_check_list" => $new_assign_check_list))
                ->where((array("email" => $user_id)))
                ->update("user_header_all");
// check list
        if ($result > 0) {
            echo json_encode(array("response" => "Check List is assign"));
        } else {
            echo json_encode(array("response" => "Faild Assign"));
        }
    }

    function cheklistModal() {
        $data['prev_title'] = "CheckList Master";
        $data['page_title'] = "CheckList Master";
        $data = $this->get_firm_name_nav($data);
        $this->load->helper('url');
        $this->load->view('human_resource/Create_checklist', $data);
    }

    function get_all_new_uploaded_document() {
        $firm_id = $this->input->post_get("firm_id");
        $result = $this->db->query("SELECT `d`.`id`, `m`.`title`, `d`.`file_location`, `d`.`create_at`,`d`.`file_nas_id`, `u`.`user_name` FROM `hr_checklist_transaction_all` `d` JOIN `hr_checklist_header_all` `m` ON `d`.`check_list_ref`=`m`.`id` JOIN `user_header_all` `u` ON `u`.`user_id`=`d`.`create_by` WHERE `d`.`is_rejected` = 0 and `d`.`create_by`in(select user_id from user_header_all where linked_with_boss_id in (select boss_id from partner_header_all where firm_id='" . $firm_id . "'))")->result();

        $rows = "";
        if (count($result) > 0) {
            foreach ($result as $f) {

                $rows .= "<tr>"
                        . "<td>" . $f->title . "</td>"
                        /*  . "<td><a href='" . base_url($f->file_location) . "' download><i class='fa fa-download'></i> Download</a></td>" */
                        . "<td><a href='#' onclick='downloadFile(`" . $f->file_nas_id . "`,`" . $f->file_location . "`)'><i class='fa fa-download'></i> Download</a></td>"
                        . "<td>" . date_format(date_create($f->create_at), "d-M-Y h:i a") . "</td>"
                        . "<td>" . $f->user_name . "</td>"
                        . "<td>"
                        . "<button class='btn blue-steel btn-outline sbold uppercase popovers' onclick='accepted(1," . $f->id . ")'><i class='fa fa-check'></i>Accept Document</button>"
                        . "<button class='btn red-soft btn-outline sbold uppercase popovers' data-target='#documentUploadedModel' data-toggle='modal' data-request_type='2' data-document_value='" . base64_encode($f->id) . "'><i class='fa fa-remove'></i>Reject Document</button>"
                        . "</td>"
                        . "</tr>";
            }
        }
        $data["files"] = $rows;
        echo json_encode(array("response" => $data));
    }

    /*
     * return uploaded document firm wise
     */

    function get_all_uploaded_document() {
        $firm_id = $this->input->post_get("firm_id");
        $result = $this->db->query("SELECT d.id, m.title, d.file_location,d.file_nas_id, d.is_rejected, d.rejction_reason,(SELECT u2.user_name FROM  user_header_all u2 WHERE u2.user_id=d.create_by) as create_by, d.create_at, (SELECT u1.user_name FROM  user_header_all u1 WHERE u1.user_id=d.reject_by) as reject_by,d.reject_at FROM hr_checklist_transaction_all d JOIN  hr_checklist_header_all m on d.check_list_ref=m.id WHERE d.create_by in(select user_id from user_header_all where linked_with_boss_id in (select boss_id from partner_header_all where firm_id='" . $firm_id . "'))")->result();
        $rows = "";
        if (count($result) > 0) {
            foreach ($result as $f) {
                if ($f->is_rejected == 0) {
                    $rejection = "<span class='label label-warning circle'>Pending</span>";
                }

                if ($f->is_rejected == 1) {
                    $rejection = "<span class='label label-success circle'>Accept</span>";
                }

                if ($f->is_rejected == 2) {
                    $rejection = "<span class='label label-danger circle'>Rejected</span>";
                }
                if ($f->is_rejected == 3) {
                    $rejection = "<span class='label label-danger circle'>Rejected</span>";
                }
                if ($f->reject_by == "0000-00-00 00:00:00") {
                    $action_at = date_format(date_create($f->reject_at), "d-M-Y h:i a");
                } else {
                    $action_at = '';
                }

                $rows .= "<tr>"
                        . "<td>" . $f->title . "</td>"
                        /* . "<td><a href='" . base_url($f->file_location) . "' download> <i class='fa fa-download'></i> Download</a></td>" */
                        . "<td><a href='#' onclick='downloadFile(`" . $f->file_nas_id . "`,`" . $f->file_location . "`)'> <i class='fa fa-download'></i> Download</a></td>"
                        . "<td>" . $rejection . "</td>"
                        . "<td>" . $f->rejction_reason . "</td>"
                        . "<td>" . $f->create_by . "</td>"
                        . "<td>" . date_format(date_create($f->create_at), "d-M-Y h:i a") . "</td>"
                        . "<td>" . $f->reject_by . "</td>"
                        . "<td>" . $action_at . "</td>"
                        . "</tr>";
            }
        }
        $data["files"] = $rows;
        echo json_encode(array("response" => $data));
    }

    /*
     * reject employee uploaded document
     */

    function rejection_new_document() {
        if (!is_null($this->input->post_get("reason_text"))) {
            $this->Hr_CheckList_DocumentModel->rejction_reason = $this->input->post_get("reason_text");
        }

        if (!is_null($this->input->post_get("request_type"))) {
            $this->Hr_CheckList_DocumentModel->is_rejected = $this->input->post_get("request_type");
        }
        $document_id = 0;
        if (!is_null($this->input->post_get("update_value"))) {
            $document_id = base64_decode($this->input->post_get("update_value"));
        }
        if (!is_null($this->input->post_get("doc_id"))) {
            $document_id = $this->input->post_get("doc_id");
        }
        $this->Hr_CheckList_DocumentModel->reject_at = date("Y-m-d H:i:s");
        $result1 = $this->customer_model->get_firm_id();
        $this->Hr_CheckList_DocumentModel->reject_by = $result1["user_id"];
        if ($this->Hr_CheckList_DocumentModel->update_checklist_item(array("id" => $document_id))) {
            echo json_encode(array('response' => "Save Changes Successfully."));
        } else {
            echo json_encode(array('response' => "Failed To Save Changes.", 'last_query' => $this->db->last_query()));
        }
    }

    function user_checklist_status() {

        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $str = "SELECT m.id, m.title, (SELECT uh.user_name from user_header_all uh where uh.user_id= d.reject_by) as 'reject_by', d.reject_at,d.file_location,d.rejction_reason FROM hr_checklist_header_all m  JOIN hr_checklist_transaction_all d on m.id=d.check_list_ref WHERE m.create_by="
                    . "(SELECT h.user_id from user_header_all h WHERE  FIND_IN_SET(?,h.hr_authority) >0 and FIND_IN_SET(?,h.assign_check_list)>0) "
                    . "and d.create_by =? and d.is_rejected =2";
            $filesResult = $this->db->query($str, array($result1["firm_id"], $result1["user_id"], $result1["user_id"]))->result();

            $checklist_pending_task = "select * from hr_checklist_header_all m WHERE m.create_by=(SELECT h.user_id from user_header_all h WHERE  FIND_IN_SET(?,h.hr_authority) >0 and FIND_IN_SET(? ,h.assign_check_list)>0) and id not in(select d.check_list_ref from hr_checklist_transaction_all d)";


            $pending_tasks = $this->db->query($checklist_pending_task, array($result1["firm_id"], $result1["user_id"]))->result();


            $pending_row = "";

            if (count($pending_tasks) > 0) {
                foreach ($pending_tasks as $f) {
                    $download_list = "";
                    if ($f->instruction_file != "0") {
                        /*  $download_list = "<a href='" . base_url() . $f->instruction_file . "' download><i class='fa fa-download'></i> Download</a>"; */
                        $download_list = "<a href='#' onclick='downloadFile(`" . $f->file_nas_id . "`,`" . $f->instruction_file . "`)'><i class='fa fa-download'></i> Download</a>";
                    } else {
                        $download_list = "<i class='fa fa-file-text-o'></i> No File</a>";
                    }
                    $pending_row .= "<tr>"
                            . "<td>" . $f->title . "</td>"
                            . "<td>" . $f->check_descp . "</td>"
                            . "<td>" . $download_list . "</td>"
                            . "<td><button class='btn blue-hoki btn-outline sbold uppercase tooltips' data-target='#documentUploadedModel1' data-toggle='modal' data-container='body' data-placement='bottom' data-original-title='Upload New File' data-check_option_value='" . base64_encode($f->id) . "'><i class='fa fa-upload'></i></button></td>"
                            . "</tr>";
                }
            }
            $data["pending_task"] = $pending_row;

            $rows = "";
            if (count($filesResult)) {
                foreach ($filesResult as $f) {
                    $rows .= "<tr>"
                            . "<td>" . $f->title . "</td>"
                            . "<td>" . $f->rejction_reason . "</td>"
                            . "<td>" . $f->reject_by . "</td>"
                            . "<td>" . date_format(date_create($f->reject_at), "d-M-Y h:i a") . "</td>"
                            . "<td><a href='" . base_url($f->file_location) . "' download><i class='fa fa-download'></i> Download</a></td>"
                            . "<td><button class='btn blue-hoki btn-outline sbold uppercase tooltips' data-target='#documentUploadedModel1' data-toggle='modal' data-container='body' data-placement='bottom' data-original-title='Upload New File' data-check_option_value='" . base64_encode($f->id) . "'><i class='fa fa-upload'></i></button></td>"
                            . "</tr>";
                }

                $data["files"] = $rows;
            }

            $str1 = "SELECT  (select count(l.id) from hr_checklist_header_all l WHERE l.create_by=m.create_by) as 'total_task',
                    COUNT(DISTINCT(d.check_list_ref)) as 'task_completed' FROM hr_checklist_header_all m  JOIN hr_checklist_transaction_all d
                    on m.id=d.check_list_ref WHERE m.create_by= (SELECT h.user_id from user_header_all h WHERE  FIND_IN_SET(?,h.hr_authority) >0
                    and FIND_IN_SET(?,h.assign_check_list)>0) and d.create_by =? and d.is_rejected !=2";

            $result = $this->db->query($str1, array($result1["firm_id"], $result1["user_id"], $result1["user_id"]))->result();



            $total_task_result = $this->db->query(" select count(l.id) as 'total_task' from hr_checklist_header_all l WHERE l.create_by=(SELECT h.user_id from user_header_all h WHERE  FIND_IN_SET(?,h.hr_authority) >0 and FIND_IN_SET(? ,h.assign_check_list)>0 and id not in(select d.check_list_ref from hr_checklist_transaction_all d))", array($result1["firm_id"], $result1["user_id"]))->row();

            if (!is_null($total_task_result)) {
                $data["total_task"] = $total_task_result->total_task;
            } else {
                $data["total_task"] = 0;
            }
            $data["task_completed"] = $result[0]->task_completed;

            echo json_encode(array("response" => $data));
        }
    }

    /*
     * check nas is configure or not
     */

    function check() {
        $this->load->model("Nas_modal");

        var_dump($this->Nas_modal->isNasConfigure());
    }

    function upload_document() {

        if (!is_null($this->input->post_get("update_value"))) {
            $this->Hr_CheckList_DocumentModel->check_list_ref = base64_decode($this->input->post_get("update_value"));

            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $firm_id = $result1['firm_id'];
            }

            if (!is_null($this->input->post_get("NasGeneratedId"))) {
                $this->Hr_CheckList_DocumentModel->file_nas_id = $this->input->post_get("NasGeneratedId");
            } else {
                $this->Hr_CheckList_DocumentModel->file_nas_id = "";
            }

            $this->Hr_CheckList_DocumentModel->create_by = $result1["user_id"];
            $this->load->model("Nas_modal");

            if ($this->Nas_modal->isNasConfigure()) {
                $file_name = $this->input->post_get("filename");
            } else {
                $file_name = "/uploads/hr/" . $this->upload_file();
            }

            if (!is_array($file_name)) {
                if (!empty($file_name)) {
                    $this->Hr_CheckList_DocumentModel->file_location = $file_name;
                } else {
                    $this->Hr_CheckList_DocumentModel->file_location = 0;
                }
                $where = array("check_list_ref" => $this->Hr_CheckList_DocumentModel->check_list_ref, "create_by" => $this->Hr_CheckList_DocumentModel->create_by, "is_rejected" => 2);
                $this->Hr_CheckList_DocumentModel->is_rejected = 3;
                if ($this->Hr_CheckList_DocumentModel->update_checklist_item($where)) {
                    $this->Hr_CheckList_DocumentModel->create_at = date('Y-m-d H:i:s');
                    $this->Hr_CheckList_DocumentModel->is_rejected = 0;
                    if ($this->Hr_CheckList_DocumentModel->create_checklist_item()) {

                        echo json_encode(array('response' => "Uploaded Successfully.", "file" => $file_name, "nas_id" => $this->Hr_CheckList_DocumentModel->file_nas_id));
                    } else {
                        echo json_encode(array('response' => "Failed To Create.", "file" => $file_name, "nas_id" => $this->Hr_CheckList_DocumentModel->file_nas_id));
                    }
                } else {
                    echo json_encode(array('response' => "Failed To update.", "file" => $file_name, "nas_id" => $this->Hr_CheckList_DocumentModel->file_nas_id));
                }
            } else {
                echo json_encode(array('response' => $file_name["error"], "file" => $file_name, "nas_id" => $this->Hr_CheckList_DocumentModel->file_nas_id));
            }
        }
    }

    function checklist_delete_by() {
        $result1 = $this->customer_model->get_firm_id();
        $this->Hr_CheckList_Model->modefied_at = date('Y-m-d H:i:s');
        $this->Hr_CheckList_Model->modefine_by = $result1["user_id"];
        $this->Hr_CheckList_Model->status = 0;
        $id = 0;
        if (!is_null($this->input->post_get("doc_id"))) {
            $id = $this->input->post_get("doc_id");
        }
        if ($this->Hr_CheckList_Model->update_checklist_item(array('id' => $id))) {
            echo json_encode(array('response' => "Success to deleted."));
        } else {
            echo json_encode(array('response' => "Failed to delete.", "status" => $this->db->last_query(), "id" => $id));
        }
    }

    function get_checklist_option_by_id() {
        if (!is_null($this->input->post_get("list_id"))) {
            $data['checklist'] = $this->Hr_CheckList_Model->get_checklist_item(array('status' => 1, 'id' => base64_decode($this->input->post_get('list_id'))));

            echo json_encode($data);
        } else {
            $data['error'] = "something went wrong";
            echo json_encode($data);
        }
    }

    /*
     * return checklist options assign to logged user
     */

    function get_all_checklist_options_by_list_ref() {
        $user_details = $this->customer_model->get_firm_id();
        $result = $this->Hr_CheckList_Model->get_checklist_item(array('status' => 1, "create_by" => $user_details["user_id"]));
        $rows = "";
        if (count($result) > 0) {
            foreach ($result as $row) {
                $option_type = $row->option_type == 1 ? "<span class='label label-primary circle'>Optional</span>" : "<span class='label label-danger circle'>Compulsory</span>";
                if ($row->file_nas_id == "") {
                    $download = $row->instruction_file == "0" ? "<td></td>" : "<td><a href='" . base_url($row->instruction_file) . "' download><i class='fa fa-download'></i> Download</a></td>";
                } else {
                    $download = $row->instruction_file == "0" ? "<td></td>" : "<td><a href='#' onclick='downloadFile(`" . $row->file_nas_id . "`,`" . $row->instruction_file . "`)'><i class='fa fa-download'></i> Download</a></td>";
                }
                $rows .= "<tr role='row'>"
                        . "<td>" . $row->title . "</td>"
                        . "<td>" . $option_type . "</td>"
                        . "<td>" . $row->check_descp . "</td>"
                        . $download
                        . '<td class="td-actions">'
                        . '<button type="button" rel="tooltip" class="btn btn-link" data-toggle="modal"  data-target="#checklistoptionModal" data-check_option_value="' . base64_encode($row->id) . '" >'
                        . '<i class="fa fa-pencil"></i>'
                        . '</button>'
                        . '</td>'
                        . '</tr>';
            }
        }
        $data["query"] = $this->db->last_query();
        $data["checklist"] = $rows;
        echo json_encode($data);
    }

    function upload_file() {
        if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] != '4') {
            $files = $_FILES;
            if (is_array($_FILES['userfile']['name'])) {
                $count = count($_FILES['userfile']['name']); // count element
            } else {
                $count = 1;
            }
            if (!is_null($this->input->post_get("userfile"))) {
                $this->Hr_CheckList_Model->instruction_file = base64_decode($this->input->post_get("userfile"));
            }
            for ($i = 0; $i < $count; $i++) {
                $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
                $config['upload_path'] = './uploads/hr/';
                $config['allowed_types'] = '*';
                $config['max_size'] = '500000';    //limit 10000=1 mb
                $config['remove_spaces'] = true;
                $config['overwrite'] = false;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                $fileName = $_FILES['userfile']['name'];
                $fileName = str_replace(' ', '_', $fileName);
                $fileName = preg_replace('/\s+/', '_', $fileName);

                $data = array('upload_data' => $this->upload->data());
                if (empty($fileName)) {
                    return false;
                } else {
                    $file = $this->upload->do_upload('userfile');
                    if (!$file) {
                        $error = array('upload_error' => $this->upload->display_errors());
                        $response['error'] = $files['userfile']['name'][$i] . ' ' . $error['upload_error'];

                        return $response;
                    } else {
                        return $fileName;
                    }
                }
            }
        }
    }

}
