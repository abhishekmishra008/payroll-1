<?php

/**
 * Description of DatatableModel
 *
 * @author Narendra Jadhav
 */
class DatatableModel extends CI_Model {

    public function getRows($postData, $where, $select) {

        $this->_get_datatables_query($postData);
        if ($postData['length'] != -1) {
            $this->db->limit($postData['length'], $postData['start']);
        }
        $this->where = $where;
        $this->db->where($where);
        $query = $this->db->select($select)->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    /*
     * Count all records
     */

    public function countAll() {
        $this->db->where($this->where)->from($this->table);
        return $this->db->count_all_results();
    }

    public function countFiltered($postData) {
        $this->_get_datatables_query($postData);
        $query = $this->db->where($this->where)->get();
        return $query->num_rows();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */

    public function _get_datatables_query($postData) {

        $this->db->from($this->table);

        $i = 0;
        // loop searchable columns
        foreach ($this->column_search as $item) {
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
                if (count($this->column_search) - 1 == $i) {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($postData['order'])) {
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

}

?>