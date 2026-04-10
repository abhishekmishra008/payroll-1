<?php 

if(!function_exists('dd')) {
    function dd(...$vars) {
        echo "<pre style='background:#111;color:#0f0;padding:15px;border-radius:5px;'>";
        foreach ($vars as $var) {
            $output = '';
            if (is_string($var) && strlen($var) > 200) {
                $output = wordwrap($var, 100, "\n", true);
            } else {
                $output = print_r($var, true);
            }
            echo $output . "\n\n";
        }
        echo "</pre>";
        die();
    }
}


if(!function_exists("image")) { 
    function img($src, $alt = null, $size = null, $attr = null){

    }
}

?>
