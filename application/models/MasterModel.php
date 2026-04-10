<?php


class MasterModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
//		$this->db2 = $this->load->database('db2', TRUE);
    }

    /**
     * @param $sql query which you want to execute
     * @return stdClass witch properties of totalCount and data of query result
     */
    function _rawQuery($sql)
    {
        $resultObject = new stdClass();
        try {
            $query = $this->db->query($sql, FALSE);
            $result = $query->result();
            if (count($result) > 0) {
                $resultObject->totalCount = count($result);
                $resultObject->data = $result;
            } else {
                $resultObject->totalCount = 0;
                $resultObject->data = array();
            }
            $resultObject->last_query = $this->db->last_query();
        } catch (Exception $ex) {
            $resultObject->totalCount = 0;
            $resultObject->data = array();
        }
        return $resultObject;
    }

    /**
     * @param $table String table name
     * @param $data array values
     * @return stdClass object of status and last insert id
     */
    function _insert($table, $data)
    {
        $resultObject = new stdClass();
        try {
            $this->db->trans_start();
            $this->db->insert($table, $data);
            $resultObject->inserted_id = $this->db->insert_id();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $resultObject->status = FALSE;
            } else {
                $this->db->trans_commit();
                $resultObject->status = TRUE;
            }
            $this->db->trans_complete();
            $resultObject->last_query = $this->db->last_query();
        } catch (Exception $ex) {
            $resultObject->status = FALSE;
            $this->db->trans_rollback();
        }
        return $resultObject;
    }

    /**
     * @param $table String the table name
     * @param $data  array you want to update
     * @param $where array where update record
     * @return stdClass object with property of status
     */
    function _update($table, $data, $where)
    {
        $resultObject = new stdClass();
        try {
            $this->db->trans_start();
            $this->db->set($data)->where($where)->update($table);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $resultObject->status = FALSE;
            } else {
                $this->db->trans_commit();
                $resultObject->status = TRUE;
            }
            $this->db->trans_complete();
            $resultObject->last_query = $this->db->last_query();
        } catch (Exception $ex) {
            $resultObject->status = FALSE;
            $this->db->trans_rollback();
        }
        return $resultObject;
    }

    /**
     * @param $table String the table name
     * @param $data  array you want to update including where column name
     * @param $key String where column name
     * @return stdClass object with property of status
     */
    function _updateBatch($table, $data, $key)
    {
        $resultObject = new stdClass();
        try {
            $this->db->trans_start();
            $this->db->update_batch($table, $data, $key);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $resultObject->status = FALSE;
            } else {
                $this->db->trans_commit();
                $resultObject->status = TRUE;
            }
            $this->db->trans_complete();
            $resultObject->last_query = $this->db->last_query();
        } catch (Exception $ex) {
            $resultObject->status = FALSE;
            $this->db->trans_rollback();
        }
        return $resultObject;
    }


    /**
     * @param $table String the table name
     * @param $data  array you want to update including where column name
     * @return stdClass object with property of status
     */
    function _insertBatch($table, $data)
    {
        $resultObject = new stdClass();
        try {
            $this->db->trans_start();
            $this->db->insert_batch($table, $data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $resultObject->status = FALSE;
            } else {
                $this->db->trans_commit();
                $resultObject->status = TRUE;
            }
            $this->db->trans_complete();
            $resultObject->last_query = $this->db->last_query();
        } catch (Exception $ex) {
            $resultObject->status = FALSE;
            $this->db->trans_rollback();
        }
        return $resultObject;
    }


    /**
     * @param $table String the table name
     * @param $where array where delete record
     * @return stdClass object with property of status
     */
    function _delete($table, $where)
    {
        $resultObject = new stdClass();
        try {
            $this->db->trans_start();
            $this->db->where($where)->delete($table);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $resultObject->status = FALSE;
            } else {
                $this->db->trans_commit();
                $resultObject->status = TRUE;
            }
            $this->db->trans_complete();
            $resultObject->last_query = $this->db->last_query();
        } catch (Exception $ex) {
            $resultObject->status = FALSE;
            $this->db->trans_rollback();
        }
        return $resultObject;
    }


    /**
     * @param $tableName String table name
     * @param $where array of condition
     * @param $select array|String of columns
     * @param $type true for single row and false for multiple rows
     * @param null $group_by group by value
     * @return stdClass object with property of totalCount and data
     */
    function _select($tableName, $where, $select = "*", $type = true, $group_by = null, $like = null)
    {

        $resultObject = new stdClass();
        try {
            if ($type) {
                $this->db->select($select)->where($where);
                if ($group_by != null) {
                    $this->db->group_by($group_by);
                }
                $result = $this->db->get($tableName)->row();

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
                        $this->db->select($select)->where($where)->like($like);
                    }
                } else {
                    $this->db->select($select)->where($where);
                }

                if ($group_by != null) {
                    $this->db->group_by($group_by);
                }
                $result = $this->db->get($tableName)->result();

                $resultObject->totalCount = count($result);
                if (count($result) > 0) {
                    $resultObject->data = $result;
                } else {
                    $resultObject->data = array();
                }
            }
            $resultObject->last_query = $this->db->last_query();
        } catch (Exception $e) {
            $resultObject->totalCount = 0;
            $resultObject->data = null;
        }
        return $resultObject;
    }

    public function getRows($postData, $where, $select, $table, $column_search, $column_order, $order, $group_by = null, $where_or = null, $having = null)
    {

        $this->_get_datatables_query($postData, $table, $column_search, $column_order, $order, $group_by);
        if ($having != null) {
            if (!empty($having)) {
                $this->db->having($having['column'], $having['value']);
            }

        }
        if ($postData['length'] != -1) {
            $this->db->limit($postData['length'], $postData['start']);
        }
//		$this->where = $where;


        if ($where_or != null) {
            $this->db->where($where);
            $this->db->or_where($where_or);
        } else {
            $this->db->where($where);
        }

        $query = $this->db->select($select)->get();

        //echo $this->db->last_query();
        return $query->result();
    }

    /*
     * Count all records
     */

    public function countAll($table, $where)
    {
        $this->db->where($where)->from($table);
        return $this->db->count_all_results();
    }

    public function countFiltered($postData, $table, $where, $column_search, $column_order, $order)
    {
        $this->_get_datatables_query($postData, $table, $column_search, $column_order, $order);
        $query = $this->db->where($where)->get();
        return $query->num_rows();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */

    public function _get_datatables_query($postData, $table, $column_search, $column_order, $order, $group_by = null)
    {

        $this->db->from($table);
        $i = 0;
        // loop searchable columns
        foreach ($column_search as $item) {
            // if datatable send POST for search
            if ($postData['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                } else {
                    $this->db->or_like($item, $postData['search']['value']);
                }
                // last loop
                if (count($column_search) - 1 == $i) {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if ($group_by != null) {
            $this->db->group_by($group_by);
        }
        if (isset($postData['order'])) {
            $column = (int)$postData['order']['0']['column'];
            if (count($column_order) > $column)
                $this->db->order_by($column_order[$column], $postData['order']['0']['dir']);
        } else if (count($order) > 0) {
            $this->db->order_by(key($order), $order[key($order)]);

        }
    }

    function _select2($tableName, $where, $select = "*", $type = true, $group_by = null, $like = null)
    {

        $resultObject = new stdClass();
        try {
            if ($type) {
                $this->db->select($select)->where($where);
                if ($group_by != null) {
                    $this->db->group_by($group_by);
                }
                $result = $this->db->get($tableName)->row();

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
                        $this->db->select($select)->where($where)->like($like);
                    }
                } else {
                    $this->db->select($select)->where($where);
                }

                if ($group_by != null) {
                    $this->db->group_by($group_by);
                }
                $result = $this->db->get($tableName)->result();

                $resultObject->totalCount = count($result);
                if (count($result) > 0) {
                    $resultObject->data = $result;
                } else {
                    $resultObject->data = array();
                }
            }
            $resultObject->last_query = $this->db->last_query();
        } catch (Exception $e) {
            $resultObject->totalCount = 0;
            $resultObject->data = null;
        }
        return $resultObject;
    }

    public function get_count($where, $table_name)
    {
        $this->db->select('*')
            ->where($where)
            ->from($table_name);
        $query = $this->db->count_all_results();
        return $query;
    }

    public function get_row_data($select = null, $where = null, $table_name = null)
    {
        $this->db->select($select)
            ->where($where)
            ->from($table_name);
        $query = $this->db->get()->row();
        return $query;
    }

    public function order_by_data($select = "*", $where = null, $table_name = null, $order_by = null, $key = 'ASC', $where_in = null, $limit = null)
    {
        $this->db->select($select);
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->order_by($order_by, $key);
        if ($where_in != null) {
            $this->db->where($where_in);
        }
        if ($limit != null) {
            $this->db->limit($limit);
        }
        $this->db->from($table_name);
        $query = $this->db->get()->result();
        return $query;
    }

    function check_file_exist($upload_path)
    {
        $filesnames = array();

        foreach (glob('./' . $upload_path . '/*.*') as $file_NAMEEXISTS) {
            $file_NAMEEXISTS;
            $filesnames[] = str_replace("./" . $upload_path . "/", "", $file_NAMEEXISTS);
        }
        return $filesnames;
    }

    function upload_multiple_file($upload_path, $inputname, $combination = "")
    {

        $combination = (explode(",", $combination));

        $check_file_exist = $this->check_file_exist($upload_path);
        if (isset($_FILES[$inputname]) && $_FILES[$inputname]['error'] != '4') {

            $files = $_FILES;
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = '*';
//            $config['max_size'] = '20000000';    //limit 10000=1 mb
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;

            $this->load->library('upload', $config);

            if (is_array($_FILES[$inputname]['name'])) {
                $count = count($_FILES[$inputname]['name']); // count element
                $files = $_FILES[$inputname];
                $images = array();
                $dataInfo = array();
                if ($count > 0) {
                    if (in_array("1", $combination)) {
                        for ($j = 0; $j < $count; $j++) {
                            $fileName = $files['name'][$j];
                            if (in_array($fileName, $check_file_exist)) {
                                $response['status'] = 201;
                                $response['body'] = $fileName . " Already exist";
                                return $response;
                            }
                        }
                    }
                    $inputname = $inputname . "[]";
                    for ($i = 0; $i < $count; $i++) {
                        $_FILES[$inputname]['name'] = $files['name'][$i];
                        $_FILES[$inputname]['type'] = $files['type'][$i];
                        $_FILES[$inputname]['tmp_name'] = $files['tmp_name'][$i];
                        $_FILES[$inputname]['error'] = $files['error'][$i];
                        $_FILES[$inputname]['size'] = $files['size'][$i];
                        $fileName = $files['name'][$i];
                        //get system generated File name CONCATE datetime string to Filename
                        if (in_array("2", $combination)) {
                            $date = date('Y-m-d H:i:s');
                            $randomdata = strtotime($date);
                            $fileName = $randomdata . $fileName;
                        }
                        $images[] = $fileName;

                        $config['file_name'] = $fileName;

                        $this->upload->initialize($config);
                        $up = $this->upload->do_upload($inputname);
                        //var_dump($up);
                        $dataInfo[] = $this->upload->data();
                    }
                    //var_dump($dataInfo);

                    $file_with_path = array();
                    foreach ($dataInfo as $row) {
                        $raw_name = $row['raw_name'];
                        $file_ext = $row['file_ext'];
                        $file_name = $raw_name . $file_ext;
                        if(!empty($file_name)){
                            $file_with_path[] = $upload_path . "/" . $file_name;
                        }
                    }
                    if (count($file_with_path) > 0) {
                        $response['status'] = 200;
                        $response['body'] = $file_with_path;
                    } else {
                        $response['status'] = 201;
                        $response['body'] = $file_with_path;
                    }
                    return $response;
                } else {
                    $response['status'] = 201;
                    $response['body'] = array();
                    return $response;
                }
            } else {
                $response['status'] = 201;
                $response['body'] = array();
                return $response;
            }
        } else {
            $response['status'] = 201;
            $response['body'] = array();
            return $response;
        }
    }
    public function getImageView($filepath,$file_exe)
    {
        $imageUrl='';
        $excelArray=array('xlsx','xlsm','xlsb','xltx','xltm','xls','xlt','xls','xml','xlam','xla','xlw','xlr','xlc','csv');
        $zipArray=array('zip','zipx');
        $wordArray=array('doc','docx','docm','docx','dot','dotm','dotx');
        $pptArray=array('pptx','pptm','ppt');
        if(in_array(strtolower($file_exe),$excelArray))
        {
            $imageUrl='assets/images/extensions/excel_image.jpg';
        }
        else if(in_array(strtolower($file_exe),$pptArray))
        {
            $imageUrl='assets/images/extensions/ppt_image.png';
        }
        else if(in_array(strtolower($file_exe),$wordArray))
        {
            $imageUrl='assets/images/extensions/word_image.png';
        }
        else if(in_array(strtolower($file_exe),$zipArray))
        {
            $imageUrl='assets/images/extensions/zip_image.jpg';
        }
        else{
            $imageUrl=$filepath;
        }
        return '<embed src="'.base_url($imageUrl).'" style="height: 200px;width: 100%;"></object>';
    }
}
