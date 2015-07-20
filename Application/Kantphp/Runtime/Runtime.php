<?php

class Runtime {

    protected static $info = array();

    /**
     * Mark time
     * 
     * @param string $point
     */
    public static function mark($point, $key = 'app') {
        self::$info[$key]['time_usage'][$point] = microtime(TRUE);
        if (function_exists('memory_get_usage')) {
            self::$info[$key]['memory_usage'][$point] = memory_get_usage();
        }
    }

    /**
     * Calculate time range
     */
    public static function calculate($key = 'app') {
        $subtraction[$key]['time_usage'] = number_format(self::$info[$key]['time_usage']['end'] - self::$info[$key]['time_usage']['begin'], 4) . ' s';
        $subtraction[$key]['memory_usage'] = number_format((self::$info[$key]['memory_usage']['end'] - self::$info[$key]['memory_usage']['begin']) / (1024), 4) . ' kb';
        $fun = get_defined_functions();
        $subtraction['function_count'] = array('internal'=> count($fun['internal']), 'user' => count($fun['user']));
        $subtraction['included_count'] = count(get_included_files());
        return $subtraction;
    }

}
