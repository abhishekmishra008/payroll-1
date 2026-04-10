<?php
function all_active_employee($firm_id){
    $CI =& get_instance();
    return $CI->db->query("select user_name,user_id from user_header_all where firm_id='".$firm_id."' and user_type in (4) and activity_status =1")->result();
}
if (!function_exists('pr')) {
    function pr($data = '', $exit = true, $echo = true)
    {
        if ($echo === true) {
            echo "<pre>";
        }
        print_r($data);
        if ($exit === true) {
            exit;
        }
        return false; // Return false on failure
    }
}