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
class SessionFile {

    private static $_sessionPath;
    private static $_maxLifeTime;
    //Session setting: gc_maxlifetime, auth_key;
    private static $_setting;

    public function __construct($setting) {
        self::$_setting = $setting;
        self::$_sessionPath = CACHE_PATH . 'Session' . DIRECTORY_SEPARATOR;
        self::_setSessionModule();
    }

    /**
     * Set Session Module
     */
    private function _setSessionModule() {
        session_module_name('user');
        session_set_save_handler(
                array(__CLASS__, 'open'), array(__CLASS__, 'close'), array(__CLASS__, 'read'), array(__CLASS__, 'write'), array(__CLASS__, 'destroy'), array(__CLASS__, 'gc')
        );
        register_shutdown_function('session_write_close');
        session_start();
    }

    public function open() {
        return true;
    }

    public static function close() {
        return true;
    }

    /**
     * READ SESSION
     * 
     * @param string $sid
     * @return string
     */
    public static function read($sid) {
        $file = self::$_sessionPath . 'sess_' . $sid;
        if (file_exists($file)) {
            require_once KANT_PATH . 'Secure/phpseclib/bootstrap.php';
            $crypt = new Crypt_AES();
            $crypt->setKey(self::$_setting['auth_key']);
            $secure_data = file_get_contents(self::$_sessionPath . 'sess_' . $sid);
            //BASE64解密，然后AES解密
            $data = $crypt->decrypt(base64_decode($secure_data));
            return $data;
        }
    }

    /**
     * Write Session
     * 
     * @param string $sid
     * @param string $data
     * @return boolean
     */
    public static function write($sid, $data) {
        $file = self::$_sessionPath . 'sess_' . $sid;
        require_once KANT_PATH . 'Secure/phpseclib/bootstrap.php';
        $crypt = new Crypt_AES();
        $crypt->setKey(self::$_setting['auth_key']);
        //AES加密SESSION,然后BASE64加密
        $secure_data = base64_encode($crypt->encrypt($data));
        $file_size = file_put_contents($file, $secure_data, LOCK_EX);
        return $file_size ? $file_size : 'false';
    }

    public static function destroy($sid) {
        $file = self::$_sessionPath . $sid;
        if (file_exists($file)) {
            unlink($file);
        }
        return true;
    }

    public static function gc($maxtime) {
        self::$_maxLifeTime = self::$_setting['maxlifetime'] ? self::$_setting['maxlifetime'] : ini_get('session.gc_maxlifetime');
        foreach (glob(self::$_sessionPath . 'sess_') as $file) {
            if (filemtime($file) + $maxtime < time() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }

}

?>
