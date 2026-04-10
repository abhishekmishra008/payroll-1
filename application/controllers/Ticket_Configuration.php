<?php

class Ticket_Configuration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('customer_model');
    }

    public function index() {
        $result1 = $this->customer_model->get_firm_id();
        if ($result1 !== false) {
            $firm_id = $result1['firm_id'];
        }

        $query = $this->db->query("SELECT `firm_logo`,`user_name` FROM `user_header_all` where `firm_id`= '$firm_id'");
        if ($query->num_rows() > 0) {

            $record = $query->row();
            $firm_logo = $record->firm_logo;
//            $firm_name = $record->user_name;
            if ($firm_logo == "") {

                $data['logo'] = "";
//                $data['firm_name_nav'] = "";
            } else {
                $data['logo'] = $firm_logo;
//                $data['firm_name_nav'] = $firm_name;
            }
        } else {
            $data['logo'] = "";
//            $data['firm_name_nav'] = "";
        }
        $data['prev_title'] = "Ticket Configuration";
        $data['page_title'] = "Ticket Configuration";


        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $user = $this->db->query("SELECT * FROM `user_header_all` where email='$user_id'");

        $user_type = $user->row();
        $u_type = $user_type->user_type;
        $firm_name = $user_type->user_name;
        $data['u_type'] = $u_type;
        $data['firm_name'] = $firm_name;
        $this->load->view('human_resource/Ticket_Configuration', $data);
    }

	//getting all the firm under hq
	
    public function get_firm_name() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $hq_deatil = $this->db->query("select * from user_header_all where email='$user_id'")->result();
        if (count($hq_deatil) > 0) {

            foreach ($hq_deatil as $row2) {
                if ($row2->user_type == 5) {
                    $hq = $row2->linked_with_boss_id;
                    $hq_id = $this->db->query("select * from user_header_all where linked_with_boss_id='" . $row2->linked_with_boss_id . "'")->result();
                    if (count($hq_id) > 0) {
                        foreach ($hq_id as $row3) {
                            if ($row2->user_id != $row3->user_id) {
                                $user_id = $row3->email;
//                        var_dump($user_id);
                            } else {

                            }
                        }
                    }
                }
            }
        }

        //var_dump($user_id);

        $response = array('code' => -1, 'status' => false, 'message' => '');
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where('firm_email_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result_hq_cust = $query->row();
            $get_boss_id = $result_hq_cust->boss_id;
            $this->db->select('*');
            $this->db->from('partner_header_all');
            $this->db->where("reporting_to = '$get_boss_id' AND firm_activity_status = 'A' AND firm_email_id!='$user_id' AND is_virtual!='1'");
            $query_2 = $this->db->get();
            //echo $this->db->last_query();
            if ($query_2->num_rows() > 0) {
                $data = array('firm_name' => array(), 'firm_id' => array(), 'boss_id' => array());
                foreach ($query_2->result() as $row) {
                    $data['firm_name'] = $row->firm_name;
                    $data['firm_id'] = $row->firm_id;
                    $data['boss_id'] = $row->boss_id;
                    $response['frim_data'][] = ['firm_name' => $row->firm_name, 'firm_id' => $row->firm_id, 'reporting_to' => $row->reporting_to, 'boss_id' => $row->boss_id];
                }
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
//get all the employess which are inside the hq
    public function get_employee_data() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $hq_deatil = $this->db->query("select * from user_header_all where email='$user_id'")->result();

        if (count($hq_deatil) > 0) {

            foreach ($hq_deatil as $row2) {
                if ($row2->user_type == 5) {
                    $hq = $row2->linked_with_boss_id;

                    $hq_id = $this->db->query("select * from user_header_all where linked_with_boss_id='" . $row2->linked_with_boss_id . "'")->result();

                    if (count($hq_id) > 0) {
                        foreach ($hq_id as $row3) {
                            if ($row2->user_id != $row3->user_id) {
                                $user_id = $row3->email;
//                        var_dump($user_id);
                            } else {

                            }
                        }
                    }
                }
            }
        }

        $response = array('code' => -1, 'status' => false, 'message' => '');
        $this->db->select('*');
        $this->db->from('partner_header_all');
        $this->db->where('firm_email_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result_hq_cust = $query->row();
            $get_boss_id = $result_hq_cust->boss_id;
            $this->db->select('*');
            $this->db->from('partner_header_all');
            $this->db->where("reporting_to = '$get_boss_id' AND firm_activity_status = 'A' And  is_virtual!='1'");
            $query_2 = $this->db->get();
            if ($query_2->num_rows() > 0) {
                $data = array('firm_name' => array(), 'firm_id' => array(), 'boss_id' => array());
                foreach ($query_2->result() as $row) {
                    $query_get_employe = $this->db->query("select user_id,linked_with_boss_id,user_name from user_header_all where user_type!='2' and linked_with_boss_id='" . $row->boss_id . "'")->result();

                    if (count($query_get_employe) > 0) {
                        foreach ($query_get_employe as $query_get_employee) {
                            $data['user_name'][] = $query_get_employee->user_name;
                            $data['user_id'][] = $query_get_employee->user_id;
                            $query_get_firm = $this->db->query("select * from user_header_all where user_type!='2' and linked_with_boss_id='" . $query_get_employee->linked_with_boss_id . "'")->result();
                            foreach ($query_get_firm as $res) {
                                $firmdata = $this->db->query("select * from partner_header_all where firm_id='" . $res->firm_id . "'")->result();
                                foreach ($firmdata as $firm) {
                                    $data['b_name'][] = $firm->firm_name;
                                }
                            }
//                            $response['emp_data'][] = ['user_name' => $query_get_employee->user_name, 'user_id' => $query_get_employee->user_id, 'firm_name' => $firm->firm_name];
                        }
                    }
                }
                $response['emp_data'][] = [$data['user_name'], $data['user_id'], $data['b_name']];



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
    }
//function for generate new type_id
    public function gettype_Id() {
        $result = $this->db->query('SELECT Type_id FROM `ticket_header_all` ORDER BY Type_id DESC LIMIT 0,1');
        if ($result->num_rows() > 0) {
            $data = $result->row();
            $Type_id = $data->Type_id;
            $Type_id = str_pad(++$Type_id, 5, '0', STR_PAD_LEFT);
            return $Type_id;
        } else {
            $Type_id = 'Type_101';
            return $Type_id;
        }
    }

	//function for configure new ticket
    public function insert_ticket() {
        $status_id = $this->input->post('checked_site_radio');
        $Desc = $this->input->post('Desc');


        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $branch_id = $this->input->post('branch_id');
        $updated_id1 = $this->input->post('updated_id');
        $employe_id = $this->input->post('employe_id');

        $type = $this->input->post('type');

        for ($kjh = 0; $kjh < count($branch_id); $kjh++) {
            $query_get_f_id = $this->db->query("select firm_name from partner_header_all where firm_id = '$branch_id[$kjh]'");


            foreach ($query_get_f_id->result() as $row2_f) {
                $firm_name[] = $row2_f->firm_name;
            }
        }

        for ($e = 0; $e < count($employe_id); $e++) {



            $query_get_f_id = $this->db->query("select * from user_header_all where user_id = '$employe_id[$e]'");


            foreach ($query_get_f_id->result() as $row2_f) {
                $emp_name[] = $row2_f->user_name;
            }
        }



        if (is_array($firm_name)) {
            $firm_name = implode(',', $firm_name);
        }
        if (is_array($emp_name)) {
            $emp_name = implode(',', $emp_name);
        }

        if (is_array($branch_id)) {
            $branch_id = implode(',', $branch_id);
        }
        if (is_array($employe_id)) {
            $employe_id = implode(',', $employe_id);
        }
        $type_id = $this->gettype_Id();
        $created_on = date('y-m-d h:i:s');

        if ($updated_id1 == '0') {

            $data = array(
                'Type_id' => $type_id,
                'type_name' => $type,
                'firm_id' => $branch_id,
                'Branch_name' => $firm_name,
                'emp_id' => $employe_id,
                'Employe_name' => $emp_name,
                'status' => 1,
                'Description' => $Desc,
                'Is_active' => $status_id,
                'created_by' => $user_id,
                'created_on' => $created_on,
            );


            $this->db->insert('ticket_header_all', $data);
            echo "Ticket Configured Successfully";
        } else {

            $data = array(
                'type_name' => $type,
                'firm_id' => $branch_id,
                'Branch_name' => $firm_name,
                'emp_id' => $employe_id,
                'Employe_name' => $emp_name,
                'status' => 1,
                'Description' => $Desc,
                'Is_active' => $status_id,
                'created_by' => $user_id,
                'created_on' => $created_on,
            );


            $this->db->where('Type_id', $updated_id1);
            $this->db->update('ticket_header_all', $data);
            echo " Ticket updated Successfully";
        }
    }
//function for shows all the configure  ticket
    public function show_data() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }

        $ticket_data = $this->db->query("select * from ticket_header_all where status='1' and created_by='$user_id'")->result();


        if (count($ticket_data) > 0) {
            $table = "";
            foreach ($ticket_data as $row) {
                $ticket_gen_data = $this->db->query("select * from ticket_transaction_all where status='1' and Type_id='" . $row->Type_id . "'");
//                echo $this->db->last_query();

                if ($ticket_gen_data->num_rows() > 0) {
                    foreach ($ticket_gen_data->result() as $row1) {
                        $ticket_status = $row1->status;
                    }
                } else {
                    $ticket_status = "";
                }

                if ($row->Is_active == '1' && $ticket_status == '1') {

                    $table .= '<tr><td>' . $row->type_name . '</td><td>' . $row->Description . '</td><td>' . $row->Branch_name . '</td><td>' . $row->Employe_name . '</td><td><button class="btn btn green-meadow" onclick="changestatus(0,`' . $row->Type_id . '`)">Active</button></td><td><button  title="EDIT" data-toggle="modal"  data-ticket_type_id="' . $row->Type_id . '" disabled class="btn btn-link " onclick="firm_update(`' . $row->Type_id . '`)"><i class="fa fa-pencil" style="font-size:22px" ></i></button><a href="#" data-toggle="tooltip" title="This ticket is already used"> <button class="btn btn-link"  disabled="" ><i class="fa fa-trash font-red" style="font-size:22px" ></i></button></a></td></tr>';
                } else if ($row->Is_active == '1' && $ticket_status == "") {
//                    echo "akshay";

                    $table .= '<tr><td>' . $row->type_name . '</td><td>' . $row->Description . '</td><td>' . $row->Branch_name . '</td><td>' . $row->Employe_name . '</td><td><button class="btn btn green-meadow" onclick="changestatus(0,`' . $row->Type_id . '`)">Active</button></td><td><button data-toggle="modal"  data-ticket_type_id="' . $row->Type_id . '" data-target="#Ticket_Modal" class="btn btn-link " title="EDIT" onclick="firm_update(`' . $row->Type_id . '`)"><i class="fa fa-pencil" style="font-size:22px"></i></button>
                            .   <button class="btn btn-link" title="DELETE" onclick="delete_ticket(`' . $row->Type_id . '`)"  ><i class="fa fa-trash font-red" style="font-size:22px" ></i></button></td></tr>';
                } else if ($row->Is_active == '0') {

                    $table .= '<tr><td>' . $row->type_name . '</td><td>' . $row->Description . '</td><td>' . $row->Branch_name . '</td><td>' . $row->Employe_name . '</td><td><button class="btn btn red" onclick="changestatus(1,`' . $row->Type_id . '`)">De-active</button></td><td><button data-toggle="modal"  data-ticket_type_id="' . $row->Type_id . '"  class="btn btn-link " title="EDIT" onclick="firm_update(`' . $row->Type_id . '`)" disabled=""><i class="fa fa-pencil" style="font-size:22px"></i></button>
                            .  <button class="btn btn-link" title="DELETE" onclick="delete_ticket(`' . $row->Type_id . '`)" disabled="" ><i class="fa fa-trash font-red" style="font-size:22px" ></i></button>
                </td></tr>';
                }
            }
            $response['ticket_data'] = $table;
            $response['message'] = 'success';
            $response['code'] = 200;
            $response['status'] = true;
        } else {
            $table = '<tr class="odd"><td valign="top" colspan="8" class="dataTables_empty">No data available in table</td></tr>';
            $response['ticket_data'] = $table;
            $response['message'] = 'No data to display';
            $response['code'] = 204;
            $response['status'] = false;
        }
        echo json_encode($response);
    }

	//function for get the type namme
    public function get_type_name() {
        $session_data = $this->session->userdata('login_session');
        if (is_array($session_data)) {
            $data['session_data'] = $session_data;
            $user_id = ($session_data['user_id']);
        } else {
            $user_id = $this->session->userdata('login_session');
        }
        $hq_deatil = $this->db->query("select * from user_header_all where email='$user_id'")->result();
        if (count($hq_deatil) > 0) {

            foreach ($hq_deatil as $row2) {
                if ($row2->user_type == 5) {
                    $hq = $row2->linked_with_boss_id;
                    $hq_id = $this->db->query("select * from user_header_all where linked_with_boss_id='" . $row2->linked_with_boss_id . "'")->result();
                    if (count($hq_id) > 0) {
                        foreach ($hq_id as $row3) {
                            if ($row2->user_id != $row3->user_id) {
                                $user_id = $row3->email;
//                        var_dump($user_id);
                            } else {

                            }
                        }
                    }
                }
            }
        }
        $updated_id1 = $this->input->post('updated_id');
        $type_name = $this->db->query("SELECT Is_active,Description,Type_id,type_name,Branch_name,firm_id,Employe_name,emp_id FROM `ticket_header_all` where Type_id='$updated_id1'");
        if ($type_name->num_rows() > 0) {
            foreach ($type_name->result() as $row333) {

                $batch_num['type_name'][] = ['Type_id' => $row333->Type_id, 'type_name' => $row333->type_name, 'firm_id' => $row333->firm_id, 'Branch_name' => $row333->Branch_name, 'Employe_name' => $row333->Employe_name, 'emp_id' => $row333->emp_id];

                $Type_id = $row333->Type_id;
                $Type_name = $row333->type_name;
                $Description = $row333->Description;
                $Is_active = $row333->Is_active;
//                var_dump($row333->Description);

                $firm_id = $row333->firm_id;
                $firm_name = $row333->Branch_name;
                $employe_name = $row333->Employe_name;
                $employe_id = $row333->emp_id;
            }
            $firm_name_array = explode(',', $firm_name);
            $firm_id_array = explode(',', $firm_id);
            $employe_name_array = explode(',', $employe_name);
            $employe_id_array = explode(',', $employe_id);


            $response = array('code' => -1, 'status' => false, 'message' => '');
            $this->db->select('*');
            $this->db->from('partner_header_all');
            $this->db->where('firm_email_id', $user_id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $result_hq_cust = $query->row();
                $get_boss_id = $result_hq_cust->boss_id;
                $this->db->select('*');
                $this->db->from('partner_header_all');
                $this->db->where("reporting_to = '$get_boss_id' AND firm_activity_status = 'A' AND firm_email_id!='$user_id'");
                $query_2 = $this->db->get();
                if ($query_2->num_rows() > 0) {
// $data = array('firm_name' => array(), 'firm_id' => array(), 'boss_id' => array());
                    foreach ($query_2->result() as $row) {
                        $data['firm_name'][] = $row->firm_name;
                        $data['firm_id'][] = $row->firm_id;
                        $data['boss_id'] = $row->boss_id;
                        $response['frim_data'][] = ['firm_name' => $row->firm_name, 'firm_id' => $row->firm_id, 'reporting_to' => $row->reporting_to, 'boss_id' => $row->boss_id];
                    }

                    $comman_array = array_diff($data['firm_name'], $firm_name_array);
                    $comman_array_finial = array_values($comman_array);
                    $comman_array_id = array_diff($data['firm_id'], $firm_id_array);
                    $comman_array_finial_id = array_values($comman_array_id);
                }

                $response = array('code' => -1, 'status' => false, 'message' => '');
                $this->db->select('*');
                $this->db->from('partner_header_all');
                $this->db->where('firm_email_id', $user_id);
                $query = $this->db->get();
                if ($query->num_rows() > 0) {
                    $result_hq_cust = $query->row();
                    $get_boss_id = $result_hq_cust->boss_id;
                    $this->db->select('*');
                    $this->db->from('partner_header_all');
                    $this->db->where("reporting_to = '$get_boss_id' AND firm_activity_status = 'A' AND firm_email_id!='$user_id'");
                    $query_2 = $this->db->get();
                    if ($query_2->num_rows() > 0) {
                        $data = array('firm_name' => array(), 'firm_id' => array(), 'boss_id' => array());
                        foreach ($query_2->result() as $row) {
                            $query_get_employe = $this->db->query("select user_id,linked_with_boss_id,user_name from user_header_all where linked_with_boss_id='" . $row->boss_id . "'")->result();


                            if (count($query_get_employe) > 0) {
                                foreach ($query_get_employe as $query_get_employee) {
                                    $data['user_name'][] = $query_get_employee->user_name;
                                    $data['user_id'][] = $query_get_employee->user_id;
                                    $query_get_firm = $this->db->query("select * from user_header_all where linked_with_boss_id='" . $query_get_employee->linked_with_boss_id . "'")->result();
                                    foreach ($query_get_firm as $res) {
                                        $firmdata = $this->db->query("select * from partner_header_all where firm_id='" . $res->firm_id . "'")->result();
                                        foreach ($firmdata as $firm) {
                                            $data['b_name'][] = $firm->firm_name;
                                        }

//                                    $response['emp_data'][] = ['user_name' => $query_get_employee->user_name, 'user_id' => $query_get_employee->user_id, 'firm_name' => $firm->firm_name];
                                    }
                                }
//
//                                $comman_emp_array = array_diff($data['user_name'], $employe_name_array);
//                                $comman_emp_array_finial = array_values($comman_emp_array);
//                                $comman_emp_array_id = array_diff($data['user_id'], $employe_id_array);
//                                $comman_Emp_array_finial_id = array_values($comman_emp_array_id);
                            }
                        }

//                        $response['b_name_array'] = $data['b_name'];
//                        $response['comman_employe_name_array'] = $comman_emp_array_finial;
//                        $response['comman_employe_id_array'] = $comman_Emp_array_finial_id;

                        foreach ($employe_id_array as $employe_id) {
                            $result = $this->db->query("select * from user_header_all where user_id='" . $employe_id . "'")->result();
                            if (count($result) > 0) {
                                foreach ($result as $row) {
                                    $uidd = $row->user_id;

                                    $u_name = $row->user_name;


                                    $f_id = $this->db->query("select * from partner_header_all where firm_id='" . $row->firm_id . "'")->result();
                                    foreach ($f_id as $fid) {
                                        $bname = $fid->firm_name;
                                    }

                                    $b_name_array = explode(',', $bname);
                                    $u_name_array = explode(',', $u_name);
                                    $u_id_array = explode(',', $uidd);
                                    $br_name = explode(',', $bname);

                                    $comman_emp_array = array_diff($data['user_name'], $u_name_array);
                                    $comman_emp_array_finial = array_values($comman_emp_array);
                                    $comman_emp_array_id = array_diff($data['user_id'], $u_id_array);
                                    $comman_Emp_array_finial_id = array_values($comman_emp_array_id);

                                    $response['comman_emp_array_finial'] = $comman_emp_array_finial;
                                    $response['selected_emp_array'][] = [$u_name_array, $br_name];
                                    $response['comman_Emp_array_finial_id'] = $comman_Emp_array_finial_id;
                                    $response['selected_emp_id_array'][] = $u_id_array;
                                }
                            }
                        }
                        $response['comman_array'] = $comman_array_finial;
                        $response['selected_array'] = $firm_name_array;
                        $response['comman_id_array'] = $comman_array_finial_id;
                        $response['selected_id_array'] = $firm_id_array;

                        $response['type_name'] = $Type_name;
                        $response['Description'] = $Description;
                        $response['Is_active'] = $Is_active;
                        $response['b_name'] = $data['b_name'];

                        $response['message'] = 'success';
                        $response['code'] = 200;
                        $response['status'] = true;
                    } else {
                        $response['message'] = 'No data to display';
                        $response['code'] = 204;
                        $response['status'] = false;
                    }
                }
            }
        }



        echo json_encode($response);
    }
	
	//function for delete the configure ticket

    public function delete_data() {
        $delete_id = $this->input->post('delete_id');
        $data = array(
            'status' => 0
        );
        $this->db->where('Type_id', $delete_id);
        $this->db->update('ticket_header_all', $data);
        echo " Deleted Successsfully";
    }

    public function change_status() {
        $type_id = $this->input->post('type_id');
        $status = $this->input->post('status');



        $data = array(
            'Is_active' => $status
        );
        $this->db->where('Type_id', $type_id);
        $this->db->update('ticket_header_all', $data);
    }

}

?>