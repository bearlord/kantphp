<?php

class Runtime {

    protected static $info = array();

    /**
     * Mark time
     * 
     * @param string $point
     */
    public static function mark($point, $key = 'app') {
        self::$info[$key]['timeusage'][$point] = microtime(TRUE);
        if (function_exists('memory_get_usage')) {
            self::$info[$key]['memoryusage'][$point] = memory_get_usage();
        }
    }

    /**
     * Calculate time range
     */
    public static function calculate($key = 'app') {
        var_dump(self::$info);
        $subtraction[$key]['timeusage'] = number_format(self::$info[$key]['timeusage']['end'] - self::$info[$key]['timeusage']['begin'], 4);
        $subtraction[$key]['memoryusage'] = number_format((self::$info[$key]['memoryusage']['end'] - self::$info[$key]['memoryusage']['begin'])/(1024 * 1024), 4);
        return $subtraction;
    }

}
