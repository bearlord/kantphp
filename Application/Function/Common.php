<?php

function microtime_float($time) {
    list($usec, $sec) = explode(" ", $time);
    return (((float) $usec + (float) $sec) * 1000);
}

/**
 * String cut
 * 
 * @param strign $string
 * @param integer $length
 * @param string $dot
 * @param string $charset
 * @return string
 */
if (function_exists('strcut') == false) {

    function strcut($string, $length = 50) {
        if (empty($string)) {
            return false;
        }
        if (function_exists('mb_strcut')) {
            return mb_strcut($string, 0, $length, 'utf-8');
        } else {
            return substr($string, 0, $length);
        }
    }

}
?>
