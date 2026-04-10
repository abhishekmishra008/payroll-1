<?php

require_once 'DatatableModel.php';

class HrBranchData_model extends DatatableModel {

    function __construct() {
        parent::__construct();

        // Set orderable column fields
        $this->column_order = array('firma_name');
        // Set searchable column fields
        $this->column_search = array('firm_name');
        // Set default order
        $this->order = array('firm_name' => 'asc');
        $this->where = "";
        $this->table = 'partner_header_all';
    }
}