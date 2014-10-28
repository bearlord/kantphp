<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

class KantRouter extends Router {
    
}

class Router {

    private static $_instance = null;
    private $_rules = array();
    protected $request_uri;
    protected $script_name;
    protected $_enableDynamicMatch = true;
    protected $_dynamicRule = array(
        'defaultController' => 'Index',
        'defaultAction' => 'Index'
    );
    protected $get;
    protected $post;
    protected $request;

    /**
     * Module type
     * 
     * @var type 
     */
    protected $_moduleType = false;
    protected $_urlSuffix;

    public function __construct() {
        
    }

    /**
     * Singleton instance
     * 
     * @return array
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Set module type
     * 
     * @param type $var
     */
    public function setModuleType($var) {
        $this->_moduleType = $var;
    }

    /**
     * Get Module type
     * 
     * @return type
     */
    public function getModuleType() {
        return $this->_moduleType;
    }

    /**
     * Set url suffix
     * 
     * @param type $var
     * @return type
     */
    public function setUrlSuffix($var) {
        return $this->_urlSuffix = $var;
    }

    /*
     * Get url suffix
     * 
     */

    public function getUrlSuffix() {
        return $this->_urlSuffix;
    }

    /**
     * Get rules
     *
     * @param string $regex
     * @return array
     */
    public function rules($regex = null) {
        if (null === $regex) {
            return $this->_rules;
        }
        return isset($this->_rules[$regex]) ? $this->_rules[$regex] : null;
    }

    /**
     * Add rule
     *
     * @param array $rule
     * @param boolean $overwrite
     */
    public function add($rules, $overwrite = true) {
        $rules = (array) $rules;
        if ($overwrite) {
            $this->_rules = $rules + $this->_rules;
        } else {
            $this->_rules += $rules;
        }

        return $this;
    }

    /**
     * Remove rule
     *
     * @param string $regex
     */
    public function remove($regex) {
        unset($this->_rules[$regex]);
        return $this;
    }

    /**
     * Enable or disable dynamic match
     *
     * @param boolean $flag
     * @param array $opts
     * @return Cola_Router
     */
    public function enableDynamicMatch($flag = true, $opts = array()) {
        $this->_enableDynamicMatch = true;
        $this->_dynamicRule = $opts + $this->_dynamicRule;
        return $this;
    }

    /**
     * Match path
     *
     * @param string $path
     * @return boolean
     */
    public function match($pathInfo = null) {
        $pathInfo = trim($pathInfo, '/');
        $tmp = explode('/', $pathInfo);
        if ($this->getModuleType() == true) {
            if ($module = current($tmp)) {
                $dispatchInfo['module'] = ucfirst(current($tmp));
            } else {
                $dispatchInfo['module'] = $this->_rules['module'];
            }
            if ($controller = next($tmp)) {
                $dispatchInfo['ctrl'] = ucfirst($controller);
            } else {
                $dispatchInfo['ctrl'] = $this->_rules['ctrl'];
            }
        } else {
            if ($controller = current($tmp)) {
                $dispatchInfo['ctrl'] = ucfirst($controller);
            } else {
                $dispatchInfo['ctrl'] = $this->_rules['ctrl'];
            }
        }
        if ($action = next($tmp)) {
            if (strpos($action, ".") !== false) {
                $action = substr($action, 0, strpos($action, "."));
            }
            $dispatchInfo['act'] = ucfirst($action);
        } else {
            $dispatchInfo['act'] = $this->_rules['act'];
        }
        while (false !== ($next = next($tmp))) {
            $arr = preg_split("/[,:=-]/", $next, 2);
            if (!empty($arr[1])) {
                $dispatchInfo[$arr[0]] = urldecode($arr[1]);
            }
        }
        return $dispatchInfo;
    }

    /**
     * Dynamic Match
     *
     * @param string $pathInfo
     * @return array $dispatchInfo
     */
    protected function _dynamicMatch($pathInfo) {
        $dispatchInfo = array();
        $tmp = explode('/', $pathInfo);
        $params = '';
        $dispatchInfo = array();
//        KantRegistry::set('_params', $params);
        return $dispatchInfo;
    }

}
