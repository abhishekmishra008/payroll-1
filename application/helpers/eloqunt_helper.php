<?php 

if(!function_exists('pluck')) {
    function pluck($array, $value, $default = null ) {
        try {
            $result = [];
            if(!is_array($array)) {
                $array = [$array];
            }
            foreach($array as $item) {
                if(is_array($item) || is_object($item)) {
                    $valueData = is_array($item) ? ($item[$value] ?? null) : ($item->$value ?? null);
                    if($valueData !== null) {
                        $key = is_array($item) ? ($item[$default] ?? null) : ($item->$default ?? null);
                        if ($key !== null) {
                            $result[$key] = $valueData;
                        } else {
                            $result[] = $valueData;
                        }
                    }
                } else {
                    $result[] = $item;
                }
            }

            return $result;

        } catch (Exception $e) {
            return $default;
        }
    }
}

?>