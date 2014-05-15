<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

class Router extends Base {

    private static $_router;
    //系统生成的参数
    private $_param;
    protected $request_uri;
    protected $script_name;

    public function __construct() {
        $this->cfg = $this->loadCfg('Config');
    }

    public static function getInstance() {
        if (self::$_router == '') {
            self::$_router = new self();
        }
        return self::$_router;
    }

    /**
     *
     * Parse request_uri
     *
     */
    public function parse() {
        foreach (array('REQUEST_URI', 'HTTP_X_REWRITE_URL', 'argv') as $var) {
            if ($this->request_uri = $_SERVER[$var]) {
                if ($var == 'argv') {
                    $this->request_uri = $this->request_uri[0];
                }
                break;
            }
        }
        $this->request_uri = urldecode($this->request_uri == '//' ? '/' : $this->request_uri);
        $this->script_name = $_SERVER['SCRIPT_NAME'];
        $this->_recover();
        return $this;
    }

    private function _recover() {
        $this->_param();
        if (empty($this->_param['route'])) {
            if ($_GET) {
                foreach ($_GET as $key => $val) {
                    $this->get[$key] = $val;
                }
            }
            if ($this->cfg['module'] === true) {
                $this->get['module'] = !empty($this->get['module']) ? $this->get['module'] : $this->cfg['route']['module'];
                $this->get['ctrl'] = !empty($this->get['ctrl']) ? $this->get['ctrl'] : $this->cfg['route']['ctrl'];
                $this->get['act'] = !empty($this->get['act']) ? $this->get['act'] : $this->cfg['route']['act'];
            } else {
                $this->get['ctrl'] = !empty($this->get['ctrl']) ? $this->get['ctrl'] : $this->cfg['route']['ctrl'];
                $this->get['act'] = !empty($this->get['act']) ? $this->get['act'] : $this->cfg['route']['act'];
            }
        } else {
            if ($this->cfg['module'] === true) {
                $this->get['module'] = !empty($this->_param['route'][0]) ? $this->_param['route'][0] : $this->cfg['route']['module'];
                $this->get['ctrl'] = !empty($this->_param['route'][1]) ? $this->_param['route'][1] : $this->cfg['route']['ctrl'];
                $this->get['act'] = !empty($this->_param['route'][2]) ? $this->_param['route'][2] : $this->cfg['route']['act'];
                $param = array_slice($this->_param['route'], 3);
                foreach ($param as $key => $val) {
                    $arr = preg_split("/[,:=-]/", $val, 2);
                    $this->get[$arr[0]] = isset($arr[1]) ? $arr[1] : '';
                }
            } else {
                $this->get['ctrl'] = !empty($this->_param['route'][0]) ? $this->_param['route'][0] : $this->cfg['route']['ctrl'];
                $this->get['act'] = !empty($this->_param['route'][1]) ? $this->_param['route'][1] : $this->cfg['route']['act'];
                $param = array_slice($this->_param['route'], 2);
                foreach ($param as $key => $val) {
                    $arr = preg_split("/[,:=-]/", $val, 2);
                    $this->get[$arr[0]] = isset($arr[1]) ? $arr[1] : '';
                }
            }
            if (!empty($this->_param['query'])) {
                foreach ($this->_param['query'] as $key => $val) {
                    $arr = preg_split("/[,:=-]/", $val, 2);
                    $this->get[$arr[0]] = isset($arr[1]) ? $arr[1] : '';
                }
            }
            $_GET = $this->get();
        }
        $this->post = Input::addslashes($_POST);
        $this->request = $_REQUEST = array_merge($_POST, $_GET);
    }

    private function _param() {
        $_diff = $this->_getScriptName();
        if (strpos($_diff, "index.php") !== false) {
            return;
        } elseif (strpos($_diff, "?") !== false) {
            list ($route, $query) = explode("?", $_diff);
            $this->_param['route'] = explode('/', $route);
            $this->_param['query'] = preg_split("/[?&]/", $query);
        } else {
            $this->_param['route'] = explode("/", $_diff);
        }
    }

    private function _getScriptName() {
        $script_name = dirname($this->script_name);
        $request_uri = $this->request_uri;
        if ($script_name == '/') {
            $diff = ltrim($request_uri, $script_name);
        } else {
            $diff = str_ireplace($script_name, '', $request_uri);
        }
        $diff = str_replace("." . $this->cfg['url_suffix'], '', $diff);
        $diff = trim($diff, '/');
        return $diff;
    }

    /**
     *  Get
     * 
     * @return
     */
    public function get() {
        return Input::addslashes($this->get);
    }

    /**
     * Post
     * 
     * @return type
     */
    public function post() {
        return $this->post;
    }

    /**
     * Request
     * 
     * @return type
     */
    public function request() {
        return $this->request;
    }

    /**
     * Route
     */
    public function route() {
        if (!empty($this->get['module'])) {
            $route = $this->get['module'] . '/' . $this->get['ctrl'] . '/' . $this->get['act'];
        } else {
            $route = $this->get['ctrl'] . '/' . $this->get['act'];
        }
        return $route;
    }

}
