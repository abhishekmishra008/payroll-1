<?php

class ValidationLibrary extends CI_Controller {
    /*     * ********************************** */
    /*     * ***** class/library.php ********** */
    /*     * ********************************** */

    function pre($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    function css($data) {
        echo "<link href='../public/assets/css/$data.css' rel='stylesheet' />";
    }

    function js($data) {
        echo "<script src='assets/js/$data.js'></script>";
        }

        
            function re($data) {
                require_once("$data.php");
            }

        
            function text($type, $name, $value = "", $extra = "") {

                $data = for_each($extra);

                $str = "<input type='$type' name='$name' value='$value' $data />";

                return $str;
            }

        
            function br($data = 1) {
                if ($data > 1):
                    $ans = "";
                    for ($i = 1; $i <= $data; $i++):
                        $ans = $ans . "<br />";
                    endfor;
                    echo $ans;
                else:
                    echo "<br />";
                endif;
            }

        
            function form_open($method = "post", $action = "", $extra = "") {
                $data = for_each($extra);

                $str = "<form method='$method' action='$action' $data> ";
                return $str;
            }

                 /* accept alphabets only no space */
        
            function val_alpha($data, $range) {
                //echo $data;
                //naveen
                $regexp = "/^[A-Za-z]{" . $range . "}$/";
                return preg_match($regexp, $data);
            }

//        
//
//        if (!function_exists('for_each')) {
//
//            function for_each($extra) {
//                $data = "";
//                if (is_array($extra)):
//
//                    foreach ($extra as $key => $val):
//
//                        $data = $data . " $key='$val' ";
//
//                    endforeach;
//
//                endif;
//                return $data;
//            }
//
//        }
//
//
//        if (!function_exists('form_close')) {
//
//            function form_close() {
//                return "</form>";
//            }
//
//        }
//
//        if (!function_exists('file_upload')) {
//
//            function file_upload($record, $folder, $allowed_size, $allowed_type, $randomdata) {
//                //pre($record);
//                //step1 : check empty file
//                if (empty($record['name'][0])) {
//                    return "file empty";
//                } else {
//                    //step2 : create directory
//                    if (!is_dir($folder)) {
//                        mkdir($folder, 0777);
//                    }
//
//                    //step3: check filesize
//                    //echo $allowed_size;
//                    $totalsize = array_sum($record['size']);
//                    //echo $totalsize;
//
//                    if ($totalsize > $allowed_size) {
//                        return "filesize large";
//                    }
//
//                    //step4: check file type
//                    //pre($allowed_type);
//
//                    foreach ($record['type'] as $ftype) {
//                        //echo $ftype;
//                        //echo "<br />";
//                        $fans = in_array($ftype, $allowed_type);
//                        //echo $fans;
//                        if ($fans != 1) {
//                            return "filetype invalid";
//                        }
//                    }
//
//                    //step5: Do file upload
//
//                    $cnt = 0;
//                    foreach ($record['tmp_name'] as $buffpath) {
//                        //echo $buffpath;
//                        //echo "<br />";
//                        $fname = $record['name'][$cnt];
//
//                        //echo $fname;
//
//                        $finalpath = $folder . $randomdata . $fname;
//                        move_uploaded_file($buffpath, $finalpath);
//
//                        $finalans[] = $finalpath;
//                        $cnt++;
//                    }
//
//                    return $finalans;
//                }
//            }
//
//        }
//
//        if (!function_exists('file_crop')) {
//
//            function file_crop($filepath, $wi, $hi, $folder, $randomdata, $decision, $mainfile) {
//                //pre($mainfile);
//                //pre($filepath);
//                if (is_array($filepath)) {
//                    //step1: folder creation
//                    if (!is_dir($folder)) {
//                        mkdir($folder, 0777);
//                    }
//                    $cnt = 0;
//                    //step2: get file data
//                    foreach ($filepath as $path) {
//
//                        //echo $path;
//                        //br(2);
//
//                        $filedata = getimagesize($path);
//                        //pre($filedata);
//
//                        $ow = $filedata[0];
//                        $oh = $filedata[1];
//                        //step3: check new image size
//                        if ($ow < $wi || $oh < $hi || $wi <= 0 || $hi <= 0 || !is_int($wi) || !is_int($hi)) {
//                            return "invalid size provided";
//                        } else {
//                            //step4: calculate new image size	
//                            if ($decision == 1) {
//                                $new_width = $wi;
//                                $new_height = $hi;
//                            } else {
//                                $new_width = $wi;
//                                $new_height = round($wi * $oh / $ow);
//                            }
//
//                            //echo $new_width;
//                            //br(1);
//                            //echo $new_height;
//                            //br(1);
//                            //step5: make blank image from new image width , height
//
//                            $newimage = imagecreatetruecolor($new_width, $new_height);
//
//                            //pre($newimage);
//                            //echo imageSX($newimage);
//                            //echo imageSY($newimage);
//                            // step6: get original image resourceid using MIME TYPE
//
//                            if ($filedata['mime'] == "image/png") {
//                                $oimage = imagecreatefrompng($path);
//                            } else if ($filedata['mime'] == "image/gif") {
//                                $oimage = imagecreatefromgif($path);
//                            } else if ($filedata['mime'] == "image/jpeg") {
//                                $oimage = imagecreatefromjpeg($path);
//                            }
//
//                            //pre($oimage);
//                            // step7: do cropping
//                            imagecopyresampled(
//                                    $newimage, $oimage, 0, 0, 0, 0, $new_width, $new_height, $ow, $oh
//                            );
//
//                            // step8: upload cropped image
//
//                            $finalpath = $folder . $randomdata . $mainfile[$cnt];
//
//                            //echo $finalpath;
//                            //br(2);
//
//
//                            $finalans[] = $finalpath;
//
//                            if ($filedata['mime'] == "image/png") {
//                                imagepng($newimage, $finalpath, 0);
//                            } else if ($filedata['mime'] == "image/gif") {
//                                imagegif($newimage, $finalpath);
//                            } else if ($filedata['mime'] == "image/jpeg") {
//                                imagejpeg($newimage, $finalpath, 100);
//                            }
//                        }
//
//                        $cnt++;
//                    }
//
//                    return $finalans;
//                } else {
//                    return "invalid file to crop";
//                }
//            }
//
//        }
//        /* accept number till 10 digit only */
//        if (!function_exists('val_mob')) {
//
//            function val_mob($data) {
//                //echo $data;
//                //9830098900
//                $regexp = "/^[0-9]{10}$/";
//                return preg_match($regexp, $data);
//            }
//
//        }
//        /* accept alphabets only no space */
//        if (!function_exists('val_alpha')) {
//
//            function val_alpha($data, $range) {
//                //echo $data;
//                //naveen
//                $regexp = "/^[A-Za-z]{" . $range . "}$/";
//                return preg_match($regexp, $data);
//            }
//
//        }
//
//        /* Validation of Date for YYYY/mm/dd */
//        if (!function_exists('val_date')) {
//
//            function val_date($data) {
//                // echo $data;
//                //naveen
//                $regexp = "/(19|20)\d\d[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])/";
//                return preg_match($regexp, $data);
//            }
//
//        }
//
//        /* accept number,alphabets, space and comma */
//        if (!function_exists('val_alpha_num_comma')) {
//
//            function val_alpha_num_comma($data) {
//                //echo $data;
//                //naveen
//                $regexp = "/^[a-zA-Z0-9,\s\w\W]+$/";
//                return preg_match($regexp, $data);
//            }
//
//        }
//
//        /* accept alphabets and space */
//        if (!function_exists('val_alpha_space')) {
//
//            function val_alpha_space($data, $range) {
//                //echo $data;
//                //naveen
//                $regexp = "/^[A-z][A-z ]{" . $range . "}$/";
//                return preg_match($regexp, trim($data));
//            }
//
//        }
//        /* accept number with alphabets without space */
//        if (!function_exists('val_alpha_num')) {
//
//            function val_alpha_num($data, $range) {
//
//                $regexp = "/^[A-Za-z0-9]{" . $range . "}$/";
//                return preg_match($regexp, $data);
//            }
//
//        }
//        /* accept number with alphabets and space */
//        if (!function_exists('val_alpha_num_space')) {
//
//            function val_alpha_num_space($data, $range) {
//                //echo $data;
//                //naveen
//                $regexp = "/^[A-z0-9][A-z0-9 ]{" . $range . "}$/";
//                return preg_match($regexp, trim($data));
//            }
//
//        }
//        /* accept only number */
//        if (!function_exists('val_num')) {
//
//            function val_num($data, $range) {
//                //echo $data;
//                //naveen
//                $regexp = "/^[0-9]{" . $range . "}$/";
//                return preg_match($regexp, trim($data));
//            }
//
//        }
//        /* accept number with decimals */
//        if (!function_exists('val_num_digit')) {
//
//            function val_num_digit($data) {
//                $regexp = "/^[0-9]+([,.][0-9]+)?$/";
//                return preg_match($regexp, trim($data));
//            }
//
//        }
//        /* accept alphabate number and special characters */
//        if (!function_exists('val_alpha_num_specialchar')) {
//
//            function val_alpha_num_specialchar($data) {
//                $regexp = "/^[A-Z0-9_@.#&+-]*$/";
//                return preg_match($regexp, trim($data));
//            }
//
//        }
//
//
//        /*
//          string there has to be at least one number(?=.*\d)
//          and at least one letter (?=.*[A-Za-z])
//          and it has to be a number, a letter or one of the following: !@#$% [0-9A-Za-z!@#$%]
//         */
//
//        if (!function_exists('password')) {
//
//            function password($data, $range) {
//                $regexp = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{" . $range . "}$/";
//                return preg_match($regexp, trim($data));
//            }
//
//        }
//
//        if (!function_exists('corepassword')) {
//
//            function corepassword($data, $range) {
////			$regexp = "/^[0-9A-Za-z!@#$%]{".$range."}$/";
//                $regexp = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{" . $range . "}$/";
//                return preg_match($regexp, trim($data));
//            }
//
//        }
//
//        if (!function_exists('post')) {
//
//            function post($data = "") {
//                if (empty($data)) {
//                    return $_POST;
//                } else {
//                    return $_POST["$data"];
//                }
//            }
//
//        }
//
//
//        if (!function_exists('get')) {
//
//            function get($data = "") {
//                if (empty($data)) {
//                    return $_GET;
//                } else {
//                    return $_GET["$data"];
//                }
//            }
//
//        }
//
//        if (!function_exists('request')) {
//
//            function request($data = "") {
//                if (empty($data)) {
//                    return $_REQUEST;
//                } else {
//                    return $_REQUEST["$data"];
//                }
//            }
//
//        }
//
//        if (!function_exists('files')) {
//
//            function files($data = "") {
//                if (empty($data)) {
//                    return $_FILES;
//                } else {
//                    return $_FILES["$data"];
//                }
//            }
//
//        }
//
//        if (!function_exists('session')) {
//
//            function session($data = "") {
//                if (empty($data)) {
//                    return $_SESSION;
//                } else {
//                    return $_SESSION["$data"];
//                }
//            }
//
//        }
//
//        if (!function_exists('cookie')) {
//
//            function cookie($data = "") {
//                if (empty($data)) {
//                    return $_COOKIE;
//                } else {
//                    return $_COOKIE["$data"];
//                }
//            }
//
//        }
//        if (!function_exists('server')) {
//
//            function server($data = "") {
//                if (empty($data)) {
//                    return $_SERVER;
//                } else {
//                    return $_SERVER["$data"];
//                }
//            }
//
//        }
//
//        if (!function_exists('set_session')) {
//
//            function set_session($vardata, $data) {
//                $_SESSION["$vardata"] = $data;
//            }
//
//        }
//
//        if (!function_exists('unset_session')) {
//
//            function unset_session($data = "") {
//
//                if (empty($data)) {
//                    foreach ($_SESSION as $key => $val) {
//                        unset($_SESSION["$key"]);
//                    }
//                } else {
//                    unset($_SESSION["$data"]);
//                }
//            }
//
//        }
//
//        if (!function_exists('val_username')) {
//
//            function val_username($data, $range) {
//                //echo $data;
//                //naveen
//                $regexp = "/^[a-zA-Z0-9][a-zA-Z0-9_\.]{" . $range . "}$/";
//                return preg_match($regexp, strtolower(trim($data)));
//            }
//
//        }
//
//        if (!function_exists('val_email')) {
//
//            function val_email($data) {
//                if (!filter_var($data, FILTER_VALIDATE_EMAIL))
//                    return false;
//                else
//                    return true;
//            }
//
//        }
//    }

}

?>