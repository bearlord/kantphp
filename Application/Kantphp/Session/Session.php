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
!defined('IN_KANT') && exit('Access Denied');

final class Session {

    private static $_session;
    private $_sessionConfig = array();

    /**
     * Session List
     * @var type 
     */
    protected $sessionList = array();

    public function __construct() {
        
    }

    /**
     * Get instantce of the final object
     * 
     * @param type $sessionConfig
     */
    public static function getInstance($sessionConfig = '') {
        if ($sessionConfig == '') {
            $sessionConfig = require_once CFG_PATH . 'Session.php';
        }
        if (self::$_session == '') {
            self::$_session = new self();
        }
        if ($sessionConfig != '' && $sessionConfig != self::$_session->_sessionConfig) {
            self::$_session->_sessionConfig = array_merge($sessionConfig, self::$_session->_sessionConfig);
        }
        return self::$_session;
    }

    /**
     * Get instance of the cache sessionConfig
     * 
     * @param type $sessionName
     * @return type
     */
    public function getSession($sessionName) {
        if (!isset($this->sessionList[$sessionName]) || !is_object($this->sessionList[$sessionName])) {
            $this->sessionList[$sessionName] = $this->load($sessionName);
        }
        return $this->sessionList[$sessionName];
    }

    public function load($sessionName) {
        $object = null;
        if (isset($this->_sessionConfig[$sessionName]['type'])) {
            switch ($this->_sessionConfig[$sessionName]['type']) {
                case 'original':
                    require_once KANT_PATH . 'Session/Original/SessionOriginal.php';
                    $object = new SessionOriginal($this->_sessionConfig[$sessionName]);
                    break;
                case 'file':
                    require_once KANT_PATH . 'Session/File/SessionFile.php';
                    $object = new SessionFile($this->_sessionConfig[$sessionName]);
                    break;
                case 'sqlite':
                    require_once KANT_PATH . 'Session/Sqlite/SessionSqlite.php';
                    $object = new SessionSqlite($this->_sessionConfig[$sessionName]);
                    break;
                case 'memcache':
                    break;
                default :
                    break;
            }
        }
        return $object;
    }

}

?>
