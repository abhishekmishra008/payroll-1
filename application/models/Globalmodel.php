<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Globalmodel extends CI_Model {

    public function ca_get_list($table, $where) {

        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get($table)->result();
    }

    public function get_list($table, $where = FALSE) {
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get($table)->result();
    }

    public function insert($table, $param) {
        $this->db->insert($table, $param);
        return $this->db->insert_id();
    }

    public function update($table, $set, $where) {
        $this->db->where($where);
        $this->db->update($table, $set);
        return $this->db->affected_rows();
    }

    public function delete($table, $where) {
        $this->db->where($where);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    public function check_Holiday($todaydate1, $user_id, $firm_id,$day) {
        $k = 0;
        $p = 0;
        $qry1 = $this->db->query("select date from holiday_master_all where firm_id='$firm_id' AND date='$todaydate1'");
        if ($this->db->affected_rows() > 0) {
            $k++;
        }
        $query = $this->db->query("select type from attendance_employee_applicable where user_id='$user_id' AND day='$day' AND type='2'");
        if ($this->db->affected_rows() > 0) {
            $p++;
        }
        if ($k > 0 || $p > 0) {
            return 1;
        } else {
            return 2; //if there is no entry in attendance_employee_applicable and holiday_master_All table then Sunday is OFF
        }
    }

    public function check_Holiday_permission($user_id) {
        $qr = $this->db->query("select holiday_working_sts from user_header_all where user_id='$user_id'");
        if ($this->db->affected_rows() > 0) {
            $res = $qr->row();
            $holiday_working_sts = $res->holiday_working_sts;
            return $holiday_working_sts;
        } else {
            return FALSE;
        }
    }


	public function getData($select,$where,$table,$type=true){
		$this->db->select($select);
		$this->db->where($where);

		if($type == true){
			return $this->db->get($table)->row();
		}else{
			return $this->db->get($table)->result();
		}

	}

	function sendMail($to,$subject, $message) {
		$from_email = 'noreply@gbtech.in'; //change this to yours
		$this->load->library('email');
		//configure email settings
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'webmail.gbtech.in'; //smtp host name
		$config['smtp_port'] = '465'; //smtp port number 587 on server
		$config['smtp_user'] = $from_email;
		$config['smtp_pass'] = 'Noreply@123$'; //$from_email password
		$config['smtp_crypto'] = 'ssl';
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n"; //use double quotes
		$this->email->initialize($config);

		//send mail
		$this->email->from($from_email, 'RMT Team');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if ($this->email->send()) {
			return true;
		} else {
			return false;
		}
	}

    function _update_db2($table, $data, $where)
    {
        $resultObject = new stdClass();
        try {
            $this->db2->trans_start();
            $this->db2->set($data)->where($where)->update($table);
            if ($this->db2->trans_status() === FALSE) {
                $this->db2->trans_rollback();
                $resultObject->status = FALSE;
            } else {
                $this->db2->trans_commit();
                $resultObject->status = TRUE;
            }
            $this->db2->trans_complete();
            $resultObject->last_query = $this->db2->last_query();
        } catch (Exception $ex) {
            $resultObject->status = FALSE;
            $this->db2->trans_rollback();
        }
        return $resultObject;
    }

    function _select_db2($tableName, $where, $select = "*", $type = true, $group_by = null, $like = null)
    {

        $resultObject = new stdClass();
        try {
            if ($type) {
                $this->db2->select($select)->where($where);
                if ($group_by != null) {
                    $this->db2->group_by($group_by);
                }
                $result = $this->db2->get($tableName)->row();

                if ($result != null) {
                    $resultObject->totalCount = 1;
                    $resultObject->data = $result;
                } else {
                    $resultObject->totalCount = 0;
                    $resultObject->data = $result;
                }
            } else {
                if ($like != null) {
                    if (!empty($like)) {
                        $this->db2->select($select)->where($where)->like($like);
                    }
                } else {
                    $this->db2->select($select)->where($where);
                }

                if ($group_by != null) {
                    $this->db2->group_by($group_by);
                }
                $result = $this->db2->get($tableName)->result();

                $resultObject->totalCount = count($result);
                if (count($result) > 0) {
                    $resultObject->data = $result;
                } else {
                    $resultObject->data = array();
                }
            }
            $resultObject->last_query = $this->db2->last_query();
        } catch (Exception $e) {
            $resultObject->totalCount = 0;
            $resultObject->data = null;
        }
        return $resultObject;
    }

    function _insert_db2($table, $data)
    {
        $resultObject = new stdClass();
        try {
            $this->db2->trans_start();
            $this->db2->insert($table, $data);
            $resultObject->inserted_id = $this->db2->insert_id();
            if ($this->db2->trans_status() === FALSE) {
                $this->db2->trans_rollback();
                $resultObject->status = FALSE;
            } else {
                $this->db2->trans_commit();
                $resultObject->status = TRUE;
            }
            $this->db2->trans_complete();
            $resultObject->last_query = $this->db2->last_query();
        } catch (Exception $ex) {
            $resultObject->status = FALSE;
            $this->db2->trans_rollback();
        }
        return $resultObject;
    }

    public function get_events($user_id, $firm_id) {
        $query = $this->db->query("SELECT * FROM event_master WHERE created_on >= CURDATE() ORDER BY created_on ASC");
        return $query->result();
    }

}
