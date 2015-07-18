<?php

/**
 * ===========================================
 * Project: KantPHP
 * Version: 1.1
 * Copyright (c) 2011, KantPHP
 * ALL rights reserved.
 * License: BSD license
 * ===========================================
 */
class SessionOriginal {

    //Session setting: gc_maxlifetime
    private static $_setting;

    public function __construct($setting) {
        self::$_setting = $setting;
        self::_setSessionModule();
    }

    /**
     * Set Session Module
     */
    private function _setSessionModule() {
        session_start();
        setcookie(session_name(), session_id(), time() + self::$_setting['maxlifetime'], "/");
    }

}

?>
