<?php

if(!function_exists('get_client_ip')) {
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

if(!function_exists('get_user_agent')) {
    function get_user_agent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown User Agent';
    }
}

if(!function_exists('get_user_browser')) {
    function get_user_browser() {
        $user_agent = get_user_agent();
        if (strpos($user_agent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($user_agent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($user_agent, 'Safari') !== false) {
            return 'Apple Safari';
        } elseif (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) {
            return 'Internet Explorer';
        }  elseif (strpos($user_agent, 'Brave') !== false) {
            return 'Brave Browser';
        } elseif (strpos($user_agent, 'Opera Mini') !== false) {
            return 'Opera Mini Browser';
        } else {
            return 'Other';
        }
    }
}

if(!function_exists('get_user_os')) {
    function get_user_os() {
        $user_agent = get_user_agent();
        if (preg_match('/Windows/i', $user_agent)) {
            return 'Windows';
        } elseif (preg_match('/Macintosh|Mac OS X/i', $user_agent)) {
            return 'Mac OS';
        } elseif (preg_match('/Linux/i', $user_agent)) {
            return 'Linux';
        } elseif (preg_match('/Android/i', $user_agent)) {
            return 'Android';
        } elseif (preg_match('/iPhone|iPad|iPod/i', $user_agent)) {
            return 'iOS';
        } else {
            return 'Other';
        }
    }
}

if(!function_exists('get_ip_address')) {
    function get_ip_address() {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                    $IPaddress = trim($IPaddress);
                    if (filter_var($IPaddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $IPaddress;
                    }
                }
            }
        }
    }
}


if(!function_exists('get_user_address')) {
    function get_user_address($latitude, $longitude) {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key=YOUR_API_KEY";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        if ($data['status'] == 'OK') {
            return $data['results'][0]['formatted_address'];
        } else {
            return "Address not found";
        }
    }
}


if(!function_exists('get_user_location')) {
    function get_user_location() {
        $latitude = $_POST['latitude'] ?? null;
        $longitude = $_POST['longitude'] ?? null;

        if ($latitude && $longitude) {
            return get_user_address($latitude, $longitude);
        } else {
            return "Location not provided";
        }
    }
}


if(!function_exists('get_user_location_from_session')) {
    function get_user_location_from_session() {
        $CI =& get_instance();
        $CI->load->library('session');
        
        $latitude = $CI->session->userdata('latitude');
        $longitude = $CI->session->userdata('longitude');

        if ($latitude && $longitude) {
            return get_user_address($latitude, $longitude);
        } else {
            return "Location not available in session";
        }
    }
}


if(!function_exists('get_user_location_from_db')) {
    function get_user_location_from_db($user_id) {
        $CI =& get_instance();
        $CI->load->database();
        
        $query = $CI->db->get_where('users', array('id' => $user_id));
        if ($query->num_rows() > 0) {
            $user = $query->row();
            return get_user_address($user->latitude, $user->longitude);
        } else {
            return "User not found";
        }
    }
}






?>