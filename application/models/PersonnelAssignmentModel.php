<?php

/**
 * Description of PersonnelAssignmentModel
 *
 * @author User
 */
class PersonnelAssignmentModel extends CI_Model {

    function getPersonnelAssignmentDetails($where, $select = "*") {
        try {
            return $this->db->select($select)
                            ->where($where)
                            ->get("personnel_assignment_header_all")
                            ->result();
        } catch (Exception $e) {
            return array();
        }
    }

    function getPersonnelAssignment($type, $user_id) {
        $sql = "SELECT assignment_id,assignment_details,(SELECT user_name from user_header_all WHERE user_id=p.assign_to) as 'assign_to',
        (SELECT user_name from user_header_all WHERE user_id = p.assign_by) as 'assign_by',
        (SELECT user_name from user_header_all WHERE user_id = p.assignment_create_by) as 'assignment_create_by',work_details,
        p.is_senor_created,p.senior_accept_assignment,p.submission_date,p.time_stander,p.priority,p.assign_close_status,p.assign_create_at
        FROM `personnel_assignment_header_all` p ";
        if ($type == 1) {
            $sql .= "WHERE p.assign_by = '" . $user_id . "'";
        } else if ($type == 2) {
            $sql .= "WHERE p.assign_to = '" . $user_id . "'";
        }
        try {
            return $this->db->query($sql)->result();
        } catch (Exception $e) {
            return array();
        }
    }

    function createPersonnelAssignment() {
        try {
            return $this->db->insert("personnel_assignment_header_all", $this);
        } catch (Exception $e) {
            return false;
        }
    }

    function updatePersonnelAssignment($Where) {
        try {
            return $this->db->set($this)->where($Where)->update("personnel_assignment_header_all");
        } catch (Exception $e) {
            return false;
        }
    }

    function getPersonnelAssignmentSetting($boss_id) {
        try {
            return $result = $this->db->query("select selection_approach from partner_header_all where boss_id=(select reporting_to from partner_header_all where boss_id='" . $boss_id . "')")->row();
        } catch (Exception $e) {
            return false;
        }
    }

    function getSenorIntra($user_id, $data) {
        try {
            $result = $this->db->query("SELECT u.user_id, u.senior_user_id, u.user_name ,p.firm_name,u.user_star_rating from `user_header_all` u join partner_header_all p on u.firm_id=p.firm_id where user_id='" . $user_id . "'")->row();
            if ($this->db->affected_rows() > 0) {
                array_push($data, $result);
                return $this->getSenorIntra($result->senior_user_id, $data);
            } else {
                return $data;
            }
        } catch (Exception $e) {
            return array();
        }
    }

    function getJuniorIntra($user_id, $data) {

        try {

            $result = $this->db->query("SELECT u.user_id, u.senior_user_id, u.user_name ,p.firm_name,u.user_star_rating from `user_header_all` u join partner_header_all p on u.firm_id=p.firm_id where u.senior_user_id='" . $user_id . "'")->result();
            if (count($result) > 0) {
                foreach ($result as $row) {
                    $result1 = $this->getJuniorIntra($row->user_id, array_unique($data, SORT_REGULAR));
                    if (is_array($result1)) {
                        foreach ($result1 as $row1) {
                            array_push($data, $row1);
                        }
                    } else {
                        array_push($data, $result1);
                    }

                    array_push($data, $row);
                }
                return array_unique($data, SORT_REGULAR);
            } else {
                return array_unique($data, SORT_REGULAR);
            }
        } catch (Exception $e) {
            return array();
        }
    }

    function getEmployeeInter($link_with_boss_id) {
        try {
            $sql = "SELECT u.user_id, u.user_name,u.user_star_rating,p.firm_name FROM `user_header_all` u join partner_header_all p on p.firm_id=u.firm_id where linked_with_boss_id in (select boss_id from partner_header_all where reporting_to in (select boss_id from partner_header_all where boss_id=(select reporting_to from partner_header_all where boss_id='" . $link_with_boss_id . "')))";
            return $this->db->query($sql)->result();
        } catch (Exception $e) {
            return array();
        }
    }

    function getAssignmentResponseDetails($where, $select = "*") {
        try {
            return $this->db->select($select)
                            ->where($where)
                            ->get("personnel_assignment_header_all")
                            ->result();
        } catch (Exception $e) {
            return array();
        }
    }

    function getWeekOffDetails($user_id) {
        try {
            return $this->db->query("SELECT p.week_holiday FROM partner_header_all p  where p.firm_id = (SELECT firm_id FROM `user_header_all` WHERE user_id='" . $user_id . "')")->row();
        } catch (Exception $e) {
            return FALSE;
        }
    }

    function getHolidaysfDetails($user_id) {
        try {
            return $this->db->query("SELECT date(holiday_date) as holiday_date,end_date,holiday_name FROM `holiday_header_all` WHERE FIND_IN_SET((SELECT firm_id FROM `user_header_all` WHERE user_id='" . $user_id . "'),holiday_applied_in)")->result();
        } catch (Exception $e) {
            return array();
        }
    }

}
