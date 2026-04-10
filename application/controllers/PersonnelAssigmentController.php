<?php

/**
 * Description of PersonnelAssigmentController
 *
 * @author User
 */
class PersonnelAssigmentController extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
        $this->load->model('PersonnelAssignmentModel');
    }

    public function getTest() {
        echo json_encode(rand(100, 999999));
    }

    /*
     * get personnel assingment by user id and user type
     */

    public function getPersonnelAssigmentByUserIdAndUserType($user_id, $user_type) {
        return $this->PersonnelAssignmentModel->getPersonnelAssignment($user_type, $user_id);
    }

    /*
     *  set Model properties if request url hold parameter
     */

    function getModelInstance() {

        if (!is_null($this->input->post_get("assignment_code"))) {
            $this->PersonnelAssignmentModel->assignment_id = $this->input->post_get("assignment_code");
        }
        if (!is_null($this->input->post_get("txt_rejection_description"))) {
            $this->PersonnelAssignmentModel->response_description = $this->input->post_get("txt_rejection_description");
        }

        if (!is_null($this->input->post_get("txt_asignment_desc"))) {
            $this->PersonnelAssignmentModel->assignment_details = $this->input->post_get("txt_asignment_desc");
        }
        if (!is_null($this->input->post_get("ddl_user_list"))) {
            $this->PersonnelAssignmentModel->assign_to = $this->input->post_get("ddl_user_list");
        }
        if (!is_null($this->input->post_get("user_type"))) {
            $this->PersonnelAssignmentModel->is_senor_created = $this->input->post_get("user_type");
        }
        if (!is_null($this->input->post_get("is_repetition"))) {
            $this->PersonnelAssignmentModel->is_repetitive = $this->input->post_get("is_repetition");
        }
        if (!is_null($this->input->post_get("repetition_type"))) {
            $this->PersonnelAssignmentModel->repetitive_type = $this->input->post_get("repetition_type");
        }

        if (!is_null($this->input->post_get("repetition_days"))) {
            $this->PersonnelAssignmentModel->repetitive_days = $this->input->post_get("repetition_days");
        }

        if (!is_null($this->input->post_get("repetition_date"))) {
            $this->PersonnelAssignmentModel->repetitive_date = $this->input->post_get("repetition_date");
        }

        if (!is_null($this->input->post_get("repetition_end_date1"))) {
            if (!empty($this->input->post_get("repetition_end_date1"))) {
                $this->PersonnelAssignmentModel->repetition_end_date = $this->input->post_get("repetition_end_date1");
            }
        }
        if (!is_null($this->input->post_get("repetition_end_date2"))) {
            if (!empty($this->input->post_get("repetition_end_date2"))) {
                $this->PersonnelAssignmentModel->repetition_end_date = $this->input->post_get("repetition_end_date2");
            }
        }
        if (!is_null($this->input->post_get("time_stander"))) {
            $this->PersonnelAssignmentModel->time_stander = $this->input->post_get("time_stander");
        }

        if (!is_null($this->input->post_get("work_priority"))) {
            $this->PersonnelAssignmentModel->priority = $this->input->post_get("work_priority");
        }

        if (!is_null($this->input->post_get("work_desc"))) {
            $this->PersonnelAssignmentModel->work_details = $this->input->post_get("work_desc");
        }

        if (!is_null($this->input->post_get("reject_desc"))) {
            $this->PersonnelAssignmentModel->reject_details = $this->input->post_get("reject_desc");
        }

        if (!is_null($this->input->post_get("reject_of"))) {
            $this->PersonnelAssignmentModel->rejection_of = $this->input->post_get("reject_of");
        }

        if (!is_null($this->input->post_get("assign_priority"))) {
            $this->PersonnelAssignmentModel->priority = $this->input->post_get("assign_priority");
        }

        if (!is_null($this->input->post_get("submit_date"))) {
            $this->PersonnelAssignmentModel->submission_date = $this->input->post_get("submit_date");
        }
    }

    /*
     * get holidays details
     */

    public function getWeekend_Holidays() {

        if (!is_null($this->input->post_get("user_code"))) {
            $user_id = $this->input->post_get("user_code");
            $weekdays_code = $this->PersonnelAssignmentModel->getWeekOffDetails($user_id);
            $holidays = $this->PersonnelAssignmentModel->getHolidaysfDetails($user_id);

            if ($weekdays_code != false) {
                $weekend_details = $this->weekdays_array($weekdays_code->week_holiday);

                if (count($weekend_details) > 0) {
                    $response["weekend"] = $weekend_details;
                } else {
                    $response["weekend"] = "";
                }
            }
            $response["holidays"] = $holidays;
            echo json_encode($response);
        } else {
            $result1 = $this->customer_model->get_firm_id();
            if ($result1 !== false) {
                $user_id = $result1['user_id'];
                $weekdays_code = $this->PersonnelAssignmentModel->getWeekOffDetails($user_id);
                $holidays = $this->PersonnelAssignmentModel->getHolidaysfDetails($user_id);
                if ($weekdays_code != false) {
                    $weekend_details = $this->weekdays_array($weekdays_code->week_holiday);

                    if (count($weekend_details) > 0) {
                        $response["weekend"] = $weekend_details;
                    } else {
                        $response["weekend"] = "";
                    }
                }
                $response["holidays"] = $holidays;
                echo json_encode($response);
            } else {
                $response["weekend"] = "";
                $response["holidays"] = "";
                echo json_encode($response);
            }
        }
    }

    /*
     * get week end details
     */

    public function weekdays_array($weekdays_code) {
        $weekdays = explode(",", $weekdays_code);
        $weekend_details = array();
        if (is_array($weekdays)) {
            foreach ($weekdays as $days) {
                if (strpos($days, "#")) {
                    $formate = explode("#", $days);

                    if (strpos($formate[1], ":")) {
                        $occurance = explode(":", $formate[1]);
                        $formate[1] = $occurance;
                        array_push($weekend_details, $formate);
                    } else {
                        array_push($weekend_details, $formate);
                    }
                }
            }
        }
        return $weekend_details;
    }

    /*
     * generate assignment id
     */

    public function getAssignmentId() {
        $result = $this->db->query('SELECT assignment_id FROM `personnel_assignment_header_all` ORDER BY assignment_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $assignment_id = $data->assignment_id;
            $assignment_id = str_pad( ++$assignment_id, 4, '0', STR_PAD_LEFT);
            return $assignment_id;
        } else {
            $assignment_id = 'Ass_1001';
            return $assignment_id;
        }
    }

    /*
     * create and update assignmnet
     */

    function assignmentCreate() {
        $this->getModelInstance();
        if ($this->PersonnelAssignmentModel->assignment_id == '0') {
            // create assignment
            $this->PersonnelAssignmentModel->assign_create_at = date('y-m-d h:s');
            $user_datails = $this->customer_model->get_firm_id();
            if ($user_datails != false) {
                $this->PersonnelAssignmentModel->assignment_create_by = $user_datails["user_id"];
            }
            if ($this->PersonnelAssignmentModel->is_senor_created == 1) {
                $this->PersonnelAssignmentModel->assign_by = $user_datails["user_id"];
            } else if ($this->PersonnelAssignmentModel->is_senor_created == 2) {
                $this->PersonnelAssignmentModel->assign_by = $this->PersonnelAssignmentModel->assign_to;
                $this->PersonnelAssignmentModel->senior_accept_assignment = 0;
                $this->PersonnelAssignmentModel->assign_close_status = 6;
                $this->PersonnelAssignmentModel->assignment_create_by = $this->PersonnelAssignmentModel->assign_to;
                $this->PersonnelAssignmentModel->assign_to = $user_datails["user_id"];
            }
            if ($this->PersonnelAssignmentModel->is_senor_created == 2) {
                $this->PersonnelAssignmentModel->senor_read = 1;
            } else {
                $this->PersonnelAssignmentModel->junior_read = 1;
            }

            $this->PersonnelAssignmentModel->assignment_id = $this->getAssignmentId();

            if ($this->PersonnelAssignmentModel->createPersonnelAssignment()) {
                $data["status"] = 200;
                $data["data"] = $this->PersonnelAssignmentModel;
                $data["result"] = "Successfully Create Assignment";
            } else {
                $data["status"] = 300;
                $data["data"] = $this->PersonnelAssignmentModel;
                $data["result"] = "Failed To Create Assignment";
            }
            echo json_encode($data);
        } else {
            // update assignment

            $this->PersonnelAssignmentModel->assign_update_at = date('y-m-d h:s');

            $user_datails = $this->customer_model->get_firm_id();
            if ($user_datails != false) {
                $this->PersonnelAssignmentModel->assignment_create_by = $user_datails["user_id"];
            }
            if (isset($this->PersonnelAssignmentModel->assign_to)) {
                $this->PersonnelAssignmentModel->assign_by = $user_datails["user_id"];
            } else if (isset($this->PersonnelAssignmentModel->assign_by)) {
                $this->PersonnelAssignmentModel->assign_to = $user_datails["user_id"];
            }
            if ($this->PersonnelAssignmentModel->is_senor_created == 2) {
                $this->PersonnelAssignmentModel->senor_read = 1;
            } else {
                $this->PersonnelAssignmentModel->junior_read = 1;
            }
            $where = array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id, "status" => 1);
            if ($this->PersonnelAssignmentModel->updatePersonnelAssignment($where)) {
                $data["result"] = "Successfully Update Assignment";
            } else {
                $data["result"] = "Failed To Updates Assignment";
            }
            echo json_encode($data);
        }
    }

    /*
     * get assignment
     */

    function getAssigenment() {
        $this->getModelInstance();
        $user_datails = $this->customer_model->get_firm_id();
        if ($user_datails != false) {
            $assignment_list = $this->getPersonnelAssigmentByUserIdAndUserType($user_datails["user_id"], $this->PersonnelAssignmentModel->is_senor_created);
        } else {
            $assignment_list = array();
        }
    }

    /*
     * index funtion to load personnal assignment page
     */

    function load_personnal_assignment() {
        $data['prev_title'] = "Personnal Assignment";
        $data['page_title'] = "Personnal Assignment";
        $data = $this->get_firm_name_nav($data);
        $this->load->helper('url');
        $this->load->view('human_resource/personnal_assignment', $data);
    }

    /*
     * get firm name for nav
     */

    function get_firm_name_nav($data) {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
            $user_id = $result1['user_id'];
            $data["u_type"] = $result1['user_type'];
            $data["firm_name"] = $result1['user_name'];

            $query = $this->db->query("SELECT `firm_logo`,`user_name`, user_star_rating FROM `user_header_all` where `firm_id`= '$firm_id' and `user_id` = '$user_id'");
            if ($query->num_rows() > 0) {

                $record = $query->row();
                $firm_logo = $record->firm_logo;
                $firm_name = $record->user_name;
                $user_star_rating = $record->user_star_rating;
                if ($firm_logo == "" && $firm_name == "") {

                    $data['logo'] = "";
                    $data['firm_name_nav'] = "";
                    $data['user_star_rating'] = "";
                } else {
                    $data['logo'] = $firm_logo;
                    $data['firm_name_nav'] = $firm_name;
                    $data['user_star_rating'] = $user_star_rating;
                }
            } else {
                $data['logo'] = "";
                $data['firm_name_nav'] = "";
            }
        } else {
            $data['logo'] = "";
            $data['firm_name_nav'] = "";
        }
        return $data;
    }

    /*
     * get all junior options
     */

    function getJuniorOptions() {
        $user_datails = $this->customer_model->get_firm_id();


        $approach_setting_result = $this->PersonnelAssignmentModel->getPersonnelAssignmentSetting($user_datails["boss_id"]);
        $approach_setting = explode(":", $approach_setting_result->selection_approach);
        //intra | deg
        if ($approach_setting[0] == "0" && $approach_setting[1] == "0") {
            $this->getIntraEmployee(2);
        }
        //intra | star
        if ($approach_setting[0] == "0" && $approach_setting[1] == "1") {
            $this->getIntraEmployee(1);
        }

        //inter | star
        if ($approach_setting[0] == "1" && $approach_setting[1] == "1") {
            $this->getInterEmployee();
        }

//        $this->getSenorOptions();
    }

    /*
     * get all senior options
     */

    function getIntraEmployee($formate) {
        $user_datails = $this->customer_model->get_firm_id();
        $options = "<option disabled='' selected=''>Select Employee </option>";
        if ($user_datails != false) {
            $Senor_response = $this->PersonnelAssignmentModel->getSenorIntra($user_datails["user_id"], array());
            $Junior_response = $this->PersonnelAssignmentModel->getJuniorIntra($user_datails["user_id"], array());
            if ($formate == 2) {
                if (count($Senor_response) > 0) {
                    $options .= "<optgroup label='Seniors'>";
                    foreach ($Senor_response as $values) {
                        if ($user_datails["user_id"] != $values->user_id) {
                            $options .= "<option value='" . $values->user_id . "'>" . $values->user_name . " ( " . $values->firm_name . " ) </option>";
                        } else {
                            $options .= "<option value='" . $values->user_id . "' disabled>" . $values->user_name . " ( " . $values->firm_name . " ) </option>";
                        }
                    }
                    $options .= "</optgroup>";
                }
                if (count($Junior_response) > 0) {
                    $options .= "<optgroup label='Junior'>";
                    foreach ($Junior_response as $values) {
                        if ($user_datails["user_id"] != $values->user_id) {
                            $options .= "<option value='" . $values->user_id . "'>" . $values->user_name . " ( " . $values->firm_name . " ) </option>";
                        } else {
                            $options .= "<option value='" . $values->user_id . "' disabled>" . $values->user_name . " ( " . $values->firm_name . " ) </option>";
                        }
                    }
                    $options .= "</optgroup>";
                }
            } else {
                $employee_response = array();
                foreach ($Senor_response as $v) {
                    array_push($employee_response, $v);
                }
                foreach ($Junior_response as $v) {
                    array_push($employee_response, $v);
                }
                $group = array();
                foreach ($employee_response as $key => $value) {
                    $group[$value->user_star_rating][] = $value;
                }
                if (count($group) > 0) {
                    foreach ($group as $values) {
                        $options .= "<optgroup label='Star Rating : " . $values[0]->user_star_rating . "'>";
                        foreach ($values as $object) {
                            if ($user_datails["user_id"] != $object->user_id) {
                                $options .= "<option value='" . $object->user_id . "'>" . $object->user_name . " ( " . $object->firm_name . " ) </option>";
                            } else {
                                $options .= "<option value='" . $object->user_id . "' disabled>" . $object->user_name . " ( " . $object->firm_name . " ) </option>";
                            }
                        }
                        $options .= "</optgroup>";
                    }
                }
            }
        }
        echo json_encode(array("status" => 200, "option_list" => $options));
    }

    function getInterEmployee() {
        $user_datails = $this->customer_model->get_firm_id();
        $options = "<option disabled='' selected=''>Select Employee </option>";
        if ($user_datails != false) {
            $inter_response = $this->PersonnelAssignmentModel->getEmployeeInter($user_datails["boss_id"], array());
            $group = array();

            foreach ($inter_response as $key => $value) {
                $group[$value->user_star_rating][] = $value;
            }
            if (count($group) > 0) {
                foreach ($group as $values) {
                    $options .= "<optgroup label='Star Rating : " . $values[0]->user_star_rating . "'>";
                    foreach ($values as $object) {
                        if ($user_datails["user_id"] != $object->user_id) {
                            $options .= "<option value='" . $object->user_id . "'>" . $object->user_name . " ( " . $object->firm_name . " ) </option>";
                        } else {
                            $options .= "<option value='" . $object->user_id . "' disabled>" . $object->user_name . " ( " . $object->firm_name . " ) </option>";
                        }
                    }
                    $options .= "</optgroup>";
                }
            }
        }
        echo json_encode(array("status" => 200, "option_list" => $options));
    }

    /*
     * set PersonnelAssignmentModel following properties
     * assignment id = id
     * senior_accept_assignment = 1
     * assign_rejected_at = date
     * junior_read=6
     * senor_read=0
     */

    function accept_assignment() {
        $this->getModelInstance();
        $this->PersonnelAssignmentModel->senior_accept_assignment = 1;
        $this->PersonnelAssignmentModel->assign_close_status = 1;
        $this->PersonnelAssignmentModel->assign_rejected_at = date('y-m-d h:s');
        $this->PersonnelAssignmentModel->junior_read = 6;
        $this->PersonnelAssignmentModel->senor_read = 0;
        if ($this->PersonnelAssignmentModel->updatePersonnelAssignment(array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id, "status" => 1))) {
            $data["status"] = 200;
            $data["result"] = "Successfully Accept Assignment";
        } else {
            $data["status"] = 200;
            $data["result"] = "Failed To Accept Assignment";
        }
        $data["query"] = $this->db->last_query();
        echo json_encode($data);
    }

    /*
     * set PersonnelAssignmentModel following properties
     * assignment id = id
     * assign_close_status = 1
     * work_accept_at = date
     * junior_read=3
     * senor_read=0
     */

    function accept_work() {
        $this->getModelInstance();
        $query = $this->db->query("UPDATE `personnel_assignment_header_all` SET `assign_close_status`= case when assign_close_status=5 THEN 8 when assign_close_status=7 THEN 2 ELSE 2 end,junior_read=3,senor_read=0 WHERE `assignment_id`='" . $this->PersonnelAssignmentModel->assignment_id . "'");
        if ($query) {
            $data["status"] = 200;
            $data["result"] = "Successfully Accept Assignment Work Done";
        } else {
            $data["status"] = 300;
            $data["result"] = "Failed To Accept Assignment Work Done";
        }
        echo json_encode($data);
    }

    /*
     * set PersonnelAssignmentModel following properties
     * assignment id = id
     * assign_close_status = 1
     * work_accept_at = date
     * junior_read=3
     * senor_read=0
     */

    function accept_assignment_and_work() {
        $this->getModelInstance();
        $assignment_id = $this->PersonnelAssignmentModel->assignment_id;
        $this->PersonnelAssignmentModel->work_accept_at = date('y-m-d h:s');
        $this->PersonnelAssignmentModel->assign_rejected_at = date('y-m-d h:s');
        $this->PersonnelAssignmentModel->senior_accept_assignment = 1;
        $this->PersonnelAssignmentModel->assign_close_status = 2;
        $this->PersonnelAssignmentModel->junior_read = 3;
        $this->PersonnelAssignmentModel->senor_read = 0;
        if ($this->PersonnelAssignmentModel->updatePersonnelAssignment(array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id, "status" => 1))) {
            $data["status"] = 200;
            $data["result"] = "Successfully Accept Assignment Work Done";
        } else {
            $data["status"] = 300;
            $data["result"] = "Failed To Accept Assignment Work Done";
        }
        $data["query"] = $this->db->last_query();
        echo json_encode($data);
    }

    /*
     * set PersonnelAssignmentModel following properties
     * assignment_id
     * reject_details
     * rejection_of
     */

    function reject_work() {
        $this->getModelInstance();
        $assignment_id = $this->PersonnelAssignmentModel->assignment_id;
        $this->PersonnelAssignmentModel->rejection_of = 1;
        $this->PersonnelAssignmentModel->work_reject_at = date('y-m-d h:s');
        $this->PersonnelAssignmentModel->junior_read = 4;
        $this->PersonnelAssignmentModel->senor_read = 0;
        $query = $this->db->query("UPDATE `personnel_assignment_header_all` SET `assign_close_status`= case when assign_close_status=5 THEN 9 when assign_close_status=7 THEN 3 ELSE 3 end  WHERE `assignment_id`='" . $this->PersonnelAssignmentModel->assignment_id . "'");
        if ($this->PersonnelAssignmentModel->updatePersonnelAssignment(array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id, "status" => 1))) {
            $data["status"] = 200;
            $data["result"] = "Successfully Reject Assignment Work";
        } else {
            $data["status"] = 300;
            $data["result"] = "Failed To Reject Assignment Work";
        }
        echo json_encode($data);
    }

    /*
     * set PersonnelAssignmentModel following properties
      rejection_of = 1;
      assign_rejected_at = date('y-m-d h:s');
      assign_close_status = 4;
      junior_read = 5;
      senor_read = 0;
     */

    function reject_assignment() {
        $this->getModelInstance();
        $this->PersonnelAssignmentModel->rejection_of = 2;
        $this->PersonnelAssignmentModel->assign_rejected_at = date('y-m-d h:s');
        $this->PersonnelAssignmentModel->assign_close_status = 4;
        $this->PersonnelAssignmentModel->junior_read = 5;
        $this->PersonnelAssignmentModel->senor_read = 0;
        if ($this->PersonnelAssignmentModel->updatePersonnelAssignment(array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id, "status" => 1))) {
            $data["status"] = 200;
            $data["result"] = "Successfully Reject Assignment ";
        } else {
            $data["status"] = 300;
            $data["result"] = "Failed To Reject Assignment ";
        }
        echo json_encode($data);
    }

    /*
     * set PersonnelAssignmentModel following properties
      rejection_of = 2;
      assign_rejected_at = date('y-m-d h:s');
      assign_close_status = 4;
      junior_read = 5;
      senor_read = 0;
     */

    function reject_assignment_and_work() {
        $this->getModelInstance();
        $this->PersonnelAssignmentModel->rejection_of = 2;
        $this->PersonnelAssignmentModel->assign_rejected_at = date('y-m-d h:s');
        $this->PersonnelAssignmentModel->work_reject_at = date('y-m-d h:s');
        $this->PersonnelAssignmentModel->assign_close_status = 4;
        $this->PersonnelAssignmentModel->junior_read = 5;
        $this->PersonnelAssignmentModel->senor_read = 0;
        if ($this->PersonnelAssignmentModel->updatePersonnelAssignment(array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id, "status" => 1))) {
            $data["status"] = 200;
            $data["result"] = "Successfully Reject Assignment ";
        } else {
            $data["status"] = 300;
            $data["result"] = "Failed To Reject Assignment ";
        }
        echo json_encode($data);
    }

    /*
     * set PersonnelAssignmentModel following properties
      rejection_of = 1;
      assign_rejected_at = date('y-m-d h:s');
      assign_close_status = 4;
      junior_read = 5;
      senor_read = 0;
     */

    function work_assignment() {
        $this->getModelInstance();
        $this->PersonnelAssignmentModel->work_create_at = date('y-m-d h:s');
        $this->PersonnelAssignmentModel->assign_close_status = 4;
        $this->PersonnelAssignmentModel->junior_read = 0;
        $this->PersonnelAssignmentModel->senor_read = 2;
        $this->PersonnelAssignmentModel->assign_close_status = 1;
        $result = $this->PersonnelAssignmentModel->getPersonnelAssignmentDetails(array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id), array("submission_date", "time_stander"));

        if (count($result) > 0) {
            if ($result[0]->submission_date < date('Y-m-d')) {
                $data["submt"] = "delay";
                $this->PersonnelAssignmentModel->assign_close_status = 5;
            } else {
                if ($result[0]->submission_date == date('Y-m-d')) {
                    $stander = 0;
//                    $time = date("H");
                    $time = date("16");
                    if ($time < 12) {
                        $stander = 1;
                    } else if ($time < 18) {
                        $stander = 2;
                    } else {
                        $stander = 3;
                    }
                    $data["stander"] = $stander;
                    if ($result[0]->time_stander <= $stander) {
                        $this->PersonnelAssignmentModel->assign_close_status = 5;
                        $data["submt"] = "stander delay on equeal date";
                    } else {
                        $data["submt"] = "not stander delay on equeal date";
                        $this->PersonnelAssignmentModel->assign_close_status = 7;
                    }
                } else {
                    $data["submt"] = "more days remianing ";
                    $this->PersonnelAssignmentModel->assign_close_status = 7;
                }
            }
        }
        if ($this->PersonnelAssignmentModel->updatePersonnelAssignment(array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id, "status" => 1))) {
            $data["status"] = 200;
            $data["result"] = "Successfully Assignment Work Submitted";
        } else {
            $data["status"] = 300;
            $data["result"] = "Failed To Save Assignment Work ";
        }
        echo json_encode($data);
    }

    /*
     * get logged user personnel assignment
     */

    function get_personnelAssigment_details() {
        $user_datails = $this->customer_model->get_firm_id();
        $assignByRow = "";
        $assignToRow = "";
        if ($user_datails != false) {
            $AssignByList = $this->getPersonnelAssigmentByUserIdAndUserType($user_datails["user_id"], 1);
//            echo $this->db->last_query();
            $AssignToList = $this->getPersonnelAssigmentByUserIdAndUserType($user_datails["user_id"], 2);

            if (count($AssignByList) > 0) {
                $counter = 1;
                foreach ($AssignByList as $values) {

                    $seanor_accept_button = "";
                    $actionButton = "";
                    $time_stander = "";
                    $status = "";

                    if ($values->priority == '1') {
                        $priority = "High";
                    }
                    if ($values->priority == '2') {
                        $priority = "Medium";
                    }
                    if ($values->priority == '3') {
                        $priority = "Low";
                    }
                    if ($values->time_stander == '1') {
                        $time_stander = " ( Morning ) ";
                    }
                    if ($values->time_stander == '2') {
                        $time_stander = " ( Afternoon ) ";
                    }
                    if ($values->time_stander == '3') {
                        $time_stander = " ( Evening ) ";
                    }

                    if ($values->assign_close_status == '1') {
                        $status = "<span class='label label-sm label-success'> Pending </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue'  data-target='#assignmentByModal' data-assignment_code='" . $values->assignment_id . "'><i class='fa fa-pencil'></i></button>";
                    }
                    if ($values->assign_close_status == '1' && $values->is_senor_created == '2') {
                        $status = "<span class='label label-sm label-info'> Junior Create Assignment</span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue'  data-target='#assignmentByModal' data-assignment_code='" . $values->assignment_id . "'><i class='fa fa-pencil'></i></button>";
                    }
                    if ($values->assign_close_status == '2') {
                        $status = "<span class='label label-sm label-primary'> Work Accepted </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue disabled' disabled><i class='fa fa-pencil'></i></button>";
                    }

                    if ($values->assign_close_status == '3') {
                        $status = "<span class='label label-sm label-danger'> Work Reject </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue disabled' disabled><i class='fa fa-pencil'></i></button>";
                    }
                    if ($values->assign_close_status == '4') {
                        $status = "<span class='label label-sm label-danger'> Assignment Reject </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue disabled' disabled><i class='fa fa-pencil'></i></button>";
                    }

                    if ($values->assign_close_status == '5') {
                        $status = "<span class='label label-sm label-warning'> Assignment Work Delay </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue disabled' disabled><i class='fa fa-pencil'></i></button>";
                    }
                    if ($values->assign_close_status == '6') {
                        $status = "<span class='label label-sm label-info'> Waiting For Assignment Approval </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue disabled' disabled><i class='fa fa-pencil'></i></button>";
                    }
                    if ($values->assign_close_status == '7') {
                        $status = "<span class='label label-sm label-info'> Work Done </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue disabled' disabled><i class='fa fa-pencil'></i></button>";
                    }
                    if ($values->assign_close_status == '8') {
                        $status = "<span class='label label-sm label-warning'>Assignment Work Delay(Accepted)</span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue disabled' disabled><i class='fa fa-pencil'></i></button>";
                    }
                    if ($values->assign_close_status == '9') {
                        $status = "<span class='label label-sm label-danger'>Assignment Work Delay(Rejected)</span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue disabled' disabled><i class='fa fa-pencil'></i></button>";
                    }

                    $assignByRow .= "<tr>"
                            . "<td class='comment more'>" . $values->assignment_details . "</td>"
                            . "<td>" . $values->submission_date . " " . $time_stander . "</td>"
                            . "<td>" . $priority . "</td>"
                            . "<td>" . $values->assign_to . "</td>"
                            . "<td>" . $values->assignment_create_by . "</td>"
                            . "<td>" . $status . "</td>"
                            . "<td>" . date_format(date_create($values->assign_create_at), "d-M-Y h:i a") . "</td>"
                            . "<td>"
                            . $seanor_accept_button
                            . $actionButton
                            . "<button data-toggle='modal' class='btn sbold blue'  data-target='#assignmentViewModal'  data-assignment_value='" . $values->assignment_id . "' data-user_type='1'><i class='fa fa-eye'></i>"
                            . "</button>"
                            . "</td>"
                            . "</tr>";
                    $counter++;
                }
            }
            if (count($AssignToList) > 0) {
                $counter = 1;
                foreach ($AssignToList as $values) {
                    $priority = "";
                    $actionButton = "";
                    $time_stander = "";
                    $status = "";

                    if ($values->priority == '1') {
                        $priority = "High";
                    }
                    if ($values->priority == '2') {
                        $priority = "Medium";
                    }
                    if ($values->priority == '3') {
                        $priority = "Low";
                    }
                    if ($values->time_stander == '1') {
                        $time_stander = " ( Morning ) ";
                    }
                    if ($values->time_stander == '2') {
                        $time_stander = " ( Afternoon ) ";
                    }
                    if ($values->time_stander == '3') {
                        $time_stander = " ( Evening ) ";
                    }

                    if ($values->assign_close_status == '1') {
                        $status = "<span class='label label-sm label-success'> Pending </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue'  data-target='#WorkDetailsModal'  data-assignment_code='" . $values->assignment_id . "'><i class='fa fa-file-text-o'></i></button>";
                    }

                    if ($values->assign_close_status == '1' && $values->work_details != '') {
                        $status = "<span class='label label-sm label-success'> Work Done </span>";
                        $actionButton = "";
                    }

                    if ($values->assign_close_status == '1' && $values->is_senor_created == '2' && $values->work_details == '') {
                        $status = "<span class='label label-sm label-info'> Junior Create Assignment  </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue'  data-target='#WorkDetailsModal'  data-assignment_code='" . $values->assignment_id . "'><i class='fa fa-file-text-o'></i></button>";
                    }
                    if ($values->assign_close_status == '2') {
                        $status = "<span class='label label-sm label-primary'> Work Accepted </span>";
                    }
                    if ($values->assign_close_status == '3') {
                        $status = "<span class='label label-sm label-danger'> Work Reject </span>";
                    }
                    if ($values->assign_close_status == '4') {
                        $status = "<span class='label label-sm label-danger'> Assignment Reject </span>";
                    }
                    if ($values->assign_close_status == '5') {
                        $status = "<span class='label label-sm label-warning'> Assignment Work Delay </span>";
                    }
                    if ($values->assign_close_status == '6') {
                        $status = "<span class='label label-sm label-warning'> Waiting For Assignment Approval </span>";
                        $actionButton = "<button data-toggle='modal' class='btn sbold blue'  data-target='#WorkDetailsModal'  data-assignment_code='" . $values->assignment_id . "'><i class='fa fa-file-text-o'></i></button>";
                    }
                    if ($values->assign_close_status == '7') {
                        $status = "<span class='label label-sm label-info'> Work Done </span>";
                    }
                    if ($values->assign_close_status == '8') {
                        $status = "<span class='label label-sm label-warning'>Assignment Work Delay(Accepted)</span>";
                    }
                    if ($values->assign_close_status == '9') {
                        $status = "<span class='label label-sm label-danger'>Assignment Work Delay(Rejected)</span>";
                    }
                    $assignToRow .= "<tr>"
                            . "<td class='comment more'>" . $values->assignment_details . "</td>"
                            . "<td>" . $values->submission_date . " " . $time_stander . "</td>"
                            . "<td>" . $priority . "</td>"
                            . "<td>" . $values->assignment_create_by . "</td>"
                            . "<td>" . $status . "</td>"
                            . "<td>" . date_format(date_create($values->assign_create_at), "d-M-Y h:i a") . "</td>"
                            . "<td>"
                            . $actionButton
                            . "<button data-toggle='modal' class='btn sbold blue'  data-target='#assignmentViewModal'  data-assignment_value='" . $values->assignment_id . "' data-user_type='2'><i class='fa fa-eye'></i>"
                            . "</button>"
                            . "</td>"
                            . "</tr>";
                    $counter++;
                }
            }
        }
        echo json_encode(array("status" => 200, "assignby_list" => $assignByRow, "assignto_list" => $assignToRow));
    }

    /*
     * get assignment by id
     */

    public function getAssignmentById() {
        $this->getModelInstance();
        $result = $this->PersonnelAssignmentModel->getPersonnelAssignmentDetails(array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id));
        if (count($result) > 0) {
            $data["status"] = 200;
            $data["result"] = $result;
        } else {
            $data["status"] = 300;
        }
        echo json_encode($data);
    }

    /*
     * get all unread assignment notification
     */

    function unread_assigenement() {
        $user_datails = $this->customer_model->get_firm_id();
        $senor_notification = $this->db->query("SELECT count(case WHEN senor_read=2 then 1 end) as 'Work_Submited',count(case WHEN senor_read=1 then 1 end) as 'Approval' FROM `personnel_assignment_header_all` WHERE assign_by='" . $user_datails['user_id'] . "'")->result();
        $notification_str = "";
        $counter = 0;
        if (count($senor_notification) > 0) {
            foreach ($senor_notification as $notification) {
                $message = "";
                if ($notification->Work_Submited > 0) {
                    $message = "Junior Submitted Work ";
                    $counter = $counter + $notification->Work_Submited;
                }
                if ($notification->Approval > 0) {
                    $message = "Junior Create Assignment Waiting For Approval";
                    $counter = $counter + $notification->Approval;
                }
                if ($message != "") {
                    $notification_str .= ' <li>
                                                <a href="' . base_url("personnal_assignment") . '">
                                                    <span class="time">View</span>
                                                    <span class="details">' . $message . '</span>
                                                </a>
                                            </li>';
                }
            }
        }
        $junior_notification = $this->db->query("SELECT count(case WHEN junior_read=1 then 1 end) as 'new_assignment' ,
count(case WHEN junior_read=2 then 1 end) as 'assignment_update',
count(case WHEN junior_read=3 then 1 end) as 'work_accept',
count(case WHEN junior_read=4 then 1 end) as 'work_reject',
count(case WHEN junior_read=5 then 1 end) as 'assignment_rejected_by_senor',
count(case WHEN junior_read=6 then 1 end) as 'assignment_approved'
FROM `personnel_assignment_header_all` WHERE assign_to='" . $user_datails['user_id'] . "'")->result();

        if (count($junior_notification) > 0) {
            foreach ($junior_notification as $notification) {
                $message = "";
                $color = "";
                if ($notification->new_assignment > 0) {
                    $message = "New Assignment";
                    $counter = $counter + $notification->new_assignment;
                    $color = "label-info";
                }
                if ($notification->assignment_update > 0) {
                    $message = "Senior Update Assignment Details";
                    $counter = $counter + $notification->assignment_update;
                    $color = "label-info";
                }
                if ($notification->work_accept > 0) {
                    $message = "Senior Accept Work ";
                    $counter = $counter + $notification->work_accept;
                    $color = "label-success";
                }
                if ($notification->work_reject > 0) {
                    $message = "Senior Reject Work ";
                    $counter = $counter + $notification->work_reject;
                    $color = "label-danger";
                }
                if ($notification->assignment_rejected_by_senor > 0) {
                    $message = "Senior Reject Work ";
                    $counter = $counter + $notification->assignment_rejected_by_senor;
                    $color = "label-danger";
                }
                if ($notification->assignment_approved > 0) {
                    $message = "Senior Approved Assignment ";
                    $counter = $counter + $notification->assignment_approved;
                    $color = "label-danger";
                }
                if ($message != "") {
                    $notification_str .= ' <li> <a href="' . base_url("personnal_assignment") . '">
                                                    <span class="time">View</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon ' . $color . '">
                                                            <i class="fa fa-plus"></i>
                                                            ' . $message . '
                                                        </span>
                                                    </span>
                                                </a>
                                            </li>';
                }
            }
        }
        echo json_encode(array("Total" => $counter, "notification" => $notification_str));
    }

    /*
     * mark as read
     */

    function markAsRead() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $user_id = $result1['user_id'];
            $wherejunior = array("assign_to" => $user_id);
            $this->PersonnelAssignmentModel->junior_read = 0;
            $this->PersonnelAssignmentModel->updatePersonnelAssignment($wherejunior);
            $wheresernor = array("assign_by" => $user_id);
            $this->PersonnelAssignmentModel->senor_read = 0;
            $this->PersonnelAssignmentModel->updatePersonnelAssignment($wheresernor);
            echo json_encode("mark as read done");
        }
    }

    /*
     * set PersonnelAssignmentModel following properties
     * assignment id = id
     * get assignment details, work details, rejection details and operation menu list base of assignment close statuss
     */

    function getResponseOfAssignment() {
        $this->getModelInstance();
        $user_datails = $this->customer_model->get_firm_id();
        if ($user_datails != false) {
            $where = array("assignment_id" => $this->PersonnelAssignmentModel->assignment_id);
            $responseList = $this->PersonnelAssignmentModel->getAssignmentResponseDetails($where, array("assignment_id", "assignment_details", "senior_accept_assignment", "is_senor_created", "assign_create_at", "work_details", "work_create_at", "reject_details", "rejection_of", "work_accept_at", "assign_close_status", "work_reject_at", "assign_rejected_at", "assign_customer_details"));

            $response_item = "";
            if (count($responseList) > 0) {
                $data["status"] = 200;
                foreach ($responseList as $response) {
                    $status_color = "";
                    $status_code = "";
                    $assignment = "";
                    $assignment_right_side_button = "";
                    if ($response->is_senor_created == "2" && $response->senior_accept_assignment == '0') {
                        $menu_work_reject = "";
                        $work = "";

                        if ($response->work_details != "") {

                            $menu_work_reject = "<li>"
                                    . "<a href='#' data-toggle='modal' data-target='#AssignmentRejectionDetailsModal' data-assignment_code='" . $response->assignment_id . "'>Assignment And Work Reject</a>"
                                    . "</li>" . "<li>"
                                    . "<a href='#' onclick='mark_as_accept_both(`" . $response->assignment_id . "` )'>Assignment And Work Accept</a>"
                                    . "</li>";
                            $work = "
                                            <li>
                                                <a href='#' onclick='mark_as_accept_work(`" . $response->assignment_id . "` )'>Accept Work</a>
                                            </li>
                                            <li>
                                                <a href='#' data-toggle='modal' data-target='#WorkRejectionDetailsModal'   data-assignment_code='" . $response->assignment_id . "'>Reject Work</a>
                                            </li>";
                        }

                        $assignment_right_side_button = "<div class='btn-group pull-right'>
                                        <button class='btn btn-circle-bottom dropdown-toggle' type='button' data-toggle='dropdown' data-hover='dropdown' data-close-others='true' aria-expanded='false'>
                                            <i class='fa fa-bars'></i>
                                        </button>
                                        <ul class='dropdown-menu pull-right' role='menu'>
                                             " . $menu_work_reject . "
                                             <li>
                                                <a href='#' onclick='mark_as_accept_assignment(`" . $response->assignment_id . "` )'>Accept Assignment</a>
                                            </li>
                                             <li>
                                                <a href='#' data-toggle='modal' data-target='#AssignmentRejectionDetailsModal' data-assignment_code='" . $response->assignment_id . "'>Reject Assignment</a>
                                            </li>
                                            " . $work . "
                                        </ul>
                                    </div>";
                    } else if ($response->senior_accept_assignment == '1' && $response->work_details != "") {
                        $assignment_right_side_button = "<div class='btn-group pull-right'>
                                        <button class='btn btn-circle-bottom dropdown-toggle' type='button' data-toggle='dropdown' data-hover='dropdown' data-close-others='true' aria-expanded='false'>
                                            <i class='fa fa-bars'></i>
                                        </button>
                                        <ul class='dropdown-menu pull-right' role='menu'>
                                            <li>
                                                <a href='#' onclick='mark_as_accept_work(`" . $response->assignment_id . "` )'>Accept Work</a>
                                            </li>
                                            <li>
                                                <a href='#' data-toggle='modal' data-target='#WorkRejectionDetailsModal'   data-assignment_code='" . $response->assignment_id . "'>Reject Work</a>
                                            </li>
                                        </ul>
                                    </div>";
                    }
                    if ($this->PersonnelAssignmentModel->is_senor_created == 2) {
                        $assignment_right_side_button = "";
                    }
                    $rejectionbox = "";

                    if ($response->work_details != "" && $response->assign_close_status == '1') {

                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-info uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                        </div>';
                    }
                    if ($response->work_details != "" && $response->assign_close_status == '7') {

                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-info uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                        </div>';
                    }

                    if ($response->work_details != "" && $response->assign_close_status == '5') {

                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-warning uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details (Delayed to Submit)  </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                        </div>';
                    }

                    if ($response->work_details != "" && $response->assign_close_status == '8') {
                        $assignment_right_side_button = "";
                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-warning uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details (Delayed to Submit) Accepted  </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                        </div>';
                    }
                    if ($response->work_details != "" && $response->assign_close_status == '2') {
                        $assignment_right_side_button = "";
                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-primary uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details Accepted </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                        </div>';
                    }

                    if ($response->work_details != "" && $response->reject_details != "" && $response->assign_close_status == '9' && $response->rejection_of == '1') {
                        $assignment_right_side_button = "";
                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-primary uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                            <div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-danger uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Work Rejection Details </div>
                        <p class="ribbon-content text-justify">' . $response->reject_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_reject_at), "d-M-Y h:i a") . '</span></p>
                        </div>
                        </div>';
                    }

                    if ($response->work_details != "" && $response->reject_details != "" && $response->assign_close_status == '3' && $response->rejection_of == '1') {
                        $assignment_right_side_button = "";
                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-primary uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                            <div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-danger uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Work Rejection Details </div>
                        <p class="ribbon-content text-justify">' . $response->reject_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_reject_at), "d-M-Y h:i a") . '</span></p>
                        </div>
                        </div>';
                    }

                    if ($response->work_details != "" && $response->reject_details != "" && $response->assign_close_status === '4' && $response->rejection_of === '2' && $response->is_senor_created === '2') {
                        $assignment_right_side_button = "";
                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-primary uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                            <div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-danger uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Rejection Details </div>
                        <p class="ribbon-content text-justify">' . $response->reject_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_reject_at), "d-M-Y h:i a") . '</span></p>
                        </div>
                        </div>';
                    }

                    if ($response->reject_details != "" && $response->assign_close_status === '4' && $response->rejection_of === '2' && $response->is_senor_created === '2') {
                        $assignment_right_side_button = "";
                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-primary uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                            <div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-danger uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Rejection Details </div>
                        <p class="ribbon-content text-justify">' . $response->reject_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_reject_at), "d-M-Y h:i a") . '</span></p>
                        </div>
                        </div>';
                    }

                    if ($response->reject_details != "" && $response->rejection_of == '2') {
                        $assignment_right_side_button = "";
                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-danger uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Rejection Details </div>
                        <p class="ribbon-content text-justify">' . $response->reject_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->assign_rejected_at), "d-M-Y h:i a") . '</span></p>
                        </div>';
                    }
                    if ($response->work_details != "" && $response->reject_details != "" && $response->assign_close_status == '4' && $response->rejection_of == '2') {
                        $assignment_right_side_button = "";
                        $rejectionbox = '<div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-primary uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Work Details </div>
                        <p class="ribbon-content text-justify">' . $response->work_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_create_at), "d-M-Y h:i a") . '</span></p>
                            <div class="mt-element-ribbon bg-grey-steel">
                        <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-danger uppercase">
                            <div class="ribbon-sub ribbon-clip ribbon-right"></div> Assignment Rejection Details </div>
                        <p class="ribbon-content text-justify">' . $response->reject_details . '<span class="font-grey-cascade page-content-wrapper timeline-body-time">created at : ' . date_format(date_create($response->work_reject_at), "d-M-Y h:i a") . '</span></p>
                        </div>
                        </div>';
                    }

                    if ($response->assign_customer_details == '') {
                        $chip = "<div class='ribbon ribbon-clip ribbon-round ribbon-border-dash-hor ribbon-shadow ribbon-color-info uppercase'><div class='ribbon-sub ribbon-clip '></div>
                                     Assignment Detials
                                    </div>";
                        $cust_details = '';
                    } else {
                        $chip = "
                                <div class='ribbon ribbon-clip ribbon-round ribbon-border-dash-hor ribbon-shadow ribbon-color-info uppercase'><div class='ribbon-sub ribbon-clip '></div>
                                     Enquery Assignment Detials
                                </div>
                            ";
                        $customer = explode(',', $response->assign_customer_details);
                        $cust_details = "<div class='note note-info'>
                                <div class='row'>
                                <div class='col-md-12'>
                                    <p style='margin-bottom:8px!important;'>
                                     <i class='icon-settings font-red' style='padding:0 8px;'></i>
                                        Customer Details
                                     </p>
                                    <div class='ribbon-content'>
                                        <div class='col-md-4'><i class='fa fa-user'></i> Name <p style='padding:8px'>" . $customer[0] . "</p></div>
                                        <div class='col-md-3'><i class='fa fa-phone'></i> Contact <p style='padding:8px'>" . $customer[2] . "</p></div>
                                        <div class='col-md-3'><i class='fa fa-envelope'></i> Email <p style='padding:8px'>" . $customer[1] . "</p></div>
                                    </div>
                                </div>
                                </div>
                                </div>";
                    }

                    $response_item .= "<div class='col-xs-12'>
                                " . $cust_details . "
                                <div class='mt-element-ribbon bg-grey-steel'> " . $chip . $assignment_right_side_button . "
                                    <p class='ribbon-content'>" . $response->assignment_details . "<span class='font-grey-cascade page-content-wrapper timeline-body-time'> created at : " . date_format(date_create($response->assign_create_at), "d-M-Y h:i a") . "</span></p>
                                    " . $rejectionbox . "
                                </div>

                            </div>";
                }
                $data["result"] = $response_item;
            } else {
                $data["status"] = 300;
            }
            $data["count"] = $responseList;
            $data["query"] = $this->db->last_query();
            echo json_encode($data);
        } else {
            $data["status"] = 300;
            echo json_encode($data);
        }
    }

    /*
     * Create repetitive personnel assignment  automatically
     */

    function automatically_create_personnel_assignment() {

        $where = array("is_repetitive" => 1, "status" => 1);
        $assignment_list = $this->PersonnelAssignmentModel->getPersonnelAssignmentDetails($where);
        if (count($assignment_list) > 0) {
            foreach ($assignment_list as $assignment) {
                $where_assignment_ref = array("assignment_repetitive_of" => $assignment->assignment_id, "is_repetitive" => 0);
                $is_assignment_date = $this->PersonnelAssignmentModel->getPersonnelAssignmentDetails($where_assignment_ref, array("assign_create_at"));
                if ($assignment->repetitive_type == '1') {
                    $next_days = $this->generate_next_dates_daywise($assignment->assign_create_at, $assignment->submission_date, $assignment->repetition_end_date, $assignment->repetitive_days, array());
                    echo "<br>day wise";
                    print_r($is_assignment_date);
                    print_r($next_days);
//                    print_r(array_diff($is_assignment_date, $next_days));
                } else if ($assignment->repetitive_type == '2') {
                    echo "<br>date wise";
                    $next_days = $this->generate_next_dates($assignment->assign_create_at, $assignment->submission_date, $assignment->repetition_end_date, $assignment->repetitive_date, array());
                    print_r($is_assignment_date);
                    print_r($next_days);
                    print_r(array_diff($is_assignment_date, $next_days));
                }
            }
        } else {
            echo "no repetitive";
        }
    }

    function generate_next_dates($create_at, $submission_date, $end_date, $repetitive_date, $next_date_array) {
        echo "<br>", $create_at, "/", $submission_date, "/", $end_date, "/", $repetitive_date, "<br>";
        $submission_days = (strtotime($submission_date) - strtotime($create_at)) / 60 / 60 / 24;
        $differance_days = (strtotime($repetitive_date) - strtotime($create_at)) / 60 / 60 / 24;

        $newcreatedate = date('Y-m-d', strtotime("+" . round($differance_days) . " day", strtotime(date($create_at))));

        $newSumission = $this->newDate(date($newcreatedate), round($submission_days));
        echo "<br>", $newcreatedate, "/", $newSumission, "<br>";
        if (date($newcreatedate) <= date($end_date)) {
            if (!$this->is_holiday($newcreatedate)) {
                if (!$this->is_holiday($newSumission)) {
                    $new_date = array();
                    array_push($new_date, $newcreatedate);
                    array_push($new_date, $newSumission);
                    array_push($next_date_array, $new_date);
                    return $this->generate_next_dates($newcreatedate, $newSumission, $end_date, $repetitive_date, $next_date_array);
                } else {
                    return $this->generate_next_dates($create_at, date('Y-m-d', strtotime("+1 day", $submission_date)), $end_date, $repetitive_date, $next_date_array);
                }
            } else {
                return $this->generate_next_dates($create_at, $submission_date, $end_date, date('Y-m-d', strtotime("+1 day", $repetitive_date)), $next_date_array);
            }
        } else {
            return $next_date_array;
        }
    }

    function generate_next_dates_daywise($create_at, $submission_date, $end_date, $number_of_days, $next_date_array) {
//        echo "<br>", $create_at, "/", $submission_date, "/", $end_date, "/", $number_of_days, "<br>";
        $submission_days = (strtotime($submission_date) - strtotime($create_at)) / 60 / 60 / 24;
        $newcreatedate = $this->newDate(date($create_at), $number_of_days);
        $newSumission = $this->newDate(date($newcreatedate), round($submission_days));
        if (date($newcreatedate) <= date($end_date)) {
            if (!$this->is_holiday($newcreatedate)) {
                if (!$this->is_holiday($newSumission)) {
                    $new_date = array();
                    array_push($new_date, $newcreatedate);
                    array_push($new_date, $newSumission);
                    array_push($next_date_array, $new_date);
                    return $this->generate_next_dates_daywise($newcreatedate, $newSumission, $end_date, $number_of_days, $next_date_array);
                } else {
                    return $this->generate_next_dates_daywise($create_at, date('Y-m-d', strtotime("+1 day", $submission_date)), $end_date, $number_of_days, $next_date_array);
                }
            } else {
                return $this->generate_next_dates_daywise($create_at, $submission_date, $end_date, $number_of_days + 1, $next_date_array);
            }
        } else {
            return $next_date_array;
        }
    }

    function newDate($date, $number_of_days) {
        return date('Y-m-d', strtotime("+" . intval($number_of_days) . " day", strtotime(date_format(date_create($date), 'Y-m-d'))));
    }

    function is_holiday($date) {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
            $user_id = $result1['user_id'];
        }

        $weekdays_code = $this->PersonnelAssignmentModel->getWeekOffDetails($user_id);
        $holidays = $this->PersonnelAssignmentModel->getHolidaysfDetails($firm_id);
        $total_dates = array();
        if ($weekdays_code != false) {
            $weekend_details = $this->weekdays_array($weekdays_code->week_holiday);
            if (count($weekend_details) > 0) {
                $response["weekend"] = $weekend_details;
            } else {
                $response["weekend"] = "";
            }
        }
        if (is_array($holidays)) {
            foreach ($holidays as $h_date) {
                array_push($total_dates, $h_date);
            }
        }
        if (is_array($response["weekend"])) {
            foreach ($response["weekend"] as $w_date) {
                array_push($total_dates, $w_date);
            }
        }
        if (in_array($date, $total_dates)) {
            return true;
        } else {
            return false;
        }
    }

}
