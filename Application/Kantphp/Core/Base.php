<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

require_once KANT_PATH . 'Core/Router.php';
require_once KANT_PATH . 'Core/KantException.php';
require_once KANT_PATH . 'Controller/BaseController.php';
require_once KANT_PATH . 'Model/BaseModel.php';
require_once KANT_PATH . 'View/View.php';
require_once KANT_PATH . 'Help/Input.php';
require_once KANT_PATH . 'Cache/Cache.php';

class Base {

    protected $get;
    protected $post;
    protected $route;
    protected $request;
    protected $cfg;
    protected $debug;
    protected $input;
    //cache
    protected $cache;
    private $_cacheConfig;
    protected $cacheAdapter;
    //cookie
    protected $cookie;
    private $_cookieConfig;
    //session
    protected $session;
    private $_sessionConfig;
    protected $sessionAdapter;

    public function __construct() {
        $routerObj = Router::getInstance()->parse();
        $this->get = $routerObj->get();
        $this->post = $routerObj->post();
        $this->route = $routerObj->route();
        $this->request = $routerObj->request();
        $this->debug = $this->debugStatus();
        $this->loadCache();
        $this->loadCookie();
        $this->loadSession();
        $this->input = new Input();
    }

    /**
     *
     * Load system class
     * @param string $classname
     * @param path $path
     * @param boolean $initialize
     */
    public static function loadSysClass($classname, $path = '', $initialize = false) {
        static $classes = array();
        if (!empty($path)) {
            $path = trim($path, '/') . DIRECTORY_SEPARATOR;
        }
        $filepath = KANT_PATH . $path . $classname . '.php';
        $key = md5($path . $classname);
        if (isset($classes[$key])) {
            if (!empty($classes[$key])) {
                return $classes[$key];
            } else {
                return true;
            }
        }
        if (file_exists($filepath)) {
            include_once $filepath;
            if ($initialize) {
                $classes[$key] = new $classname;
            } else {
                $classes[$key] = true;
            }
            return $classes[$key];
        } else {
            return false;
        }
    }

    /**
     *
     * Load system functioin
     * @param string $func
     */
    public static function loadSysFunc($func) {
        static $funcs = array();
        $filepath = KANT_PATH . 'Function' . DIRECTORY_SEPARATOR . $func . '.func.php';
        $key = md5($func);
        if (isset($funcs[$key]))
            return true;
        if (file_exists($filepath)) {
            include_once $filepath;
        } else {
            $funcs[$key] = false;
            return false;
        }
        $funcs[$key] = true;
        return true;
    }

    /**
     *
     * Load third-party libary
     * @param string $classname
     * @param integer $initialize
     * @return
     */
    public function loadLib($classname, $initialize = 0) {
        static $classes = array();
        $filepath = APP_PATH . 'Libary' . DIRECTORY_SEPARATOR . $classname . '.php';
        $key = md5($classname);
        if (isset($classes[$key])) {
            if (!empty($classes[$key])) {
                return $classes[$key];
            } else {
                return true;
            }
        }
        if (file_exists($filepath)) {
            include_once $filepath;
            if ($initialize) {
                $classes[$key] = new $classname;
            } else {
                $classes[$key] = true;
            }
            return $classes[$key];
        } else {
            return false;
        }
    }

    /**
     *
     * Load config
     * @param string $file
     * @param string $key
     * @param string $default
     * @param boolean $reload
     */
    public function loadCfg($file, $key = '', $default = '', $reload = false) {
        static $configs = array();
        if (!$reload && isset($configs[$file])) {
            if (empty($key)) {
                return $configs[$file];
            } elseif (isset($configs[$file][$key])) {
                return $configs[$file][$key];
            } else {
                return $default;
            }
        }
        $filepath = CFG_PATH . $file . '.php';
        if (file_exists($filepath)) {
            $configs[$file] = include $filepath;
        }
        if (empty($key)) {
            return $configs[$file];
        } elseif (isset($configs[$file][$key])) {
            return $configs[$file][$key];
        } else {
            return $default;
        }
    }

    /**
     *
     * Load model
     *
     * @param classname string
     * @param initialize integer[0,1]
     */
    public function loadModel($classname, $initialize = 1) {
        static $classes = array();
        $module = isset($this->get['module']) ? ucfirst($this->get['module']) : '';
        $classname = ucfirst($classname) . 'Model';
        if ($module) {
            $filepath = APP_PATH . 'Module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . $classname . '.php';
        } else {
            $filepath = APP_PATH . 'Model' . DIRECTORY_SEPARATOR . $classname . '.php';
        }
        $key = md5($filepath . $classname);
        if (isset($classes[$key])) {
            if (!empty($classes[$key])) {
                return $classes[$key];
            } else {
                return true;
            }
        }
        if (file_exists($filepath)) {
            include_once $filepath;
            if ($initialize) {
                $classes[$key] = new $classname;
            } else {
                $classes[$key] = true;
            }
            return $classes[$key];
        } else {
            return false;
        }
    }

    /**
     * 
     * Redirect
     * 
     * @param string $message
     * @param string $url
     * @param integer $second
     */
    public function redirect($message, $url = 'goback', $second = 3) {
        $redirectTpl = $this->loadCfg('Config', 'redirect_tpl');
        if ($redirectTpl) {
            include TPL_PATH . $redirectTpl . '.php';
        } else {
            include KANT_PATH . 'View' . DIRECTORY_SEPARATOR . 'system/redirect.php';
        }
        exit();
    }

    /**
     * Get current language
     * @return
     */
    public function getLang() {
        static $lang;
        if (empty($lang)) {
            $this->cfg = $this->loadCfg('Config');
            $lang = !empty($_COOKIE['lang']) ? $_COOKIE['lang'] : $this->cfg['system']['lang'];
        }
        return $lang;
    }

    /**
     * 
     * @staticvar array $LANG
     * @param string $language
     * @return array
     */
    public function lang($language = 'no_language') {
        static $LANG = array();
        if (!$LANG) {
            $this->cfg = $this->loadCfg('Config');
            $lang = !empty($_COOKIE['lang']) ? $_COOKIE['lang'] : $this->cfg['system']['lang'];
            require KANT_PATH . 'Locale' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . 'System.php';
            if (file_exists(APP_PATH . 'Locale' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . 'App.php')) {
                require APP_PATH . 'Locale' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . 'App.php';
            }
        }
        if (!array_key_exists($language, $LANG)) {
//			return $LANG['no_language'] . '[' . $language . ']';
            return $language;
        } else {
            $language = $LANG[$language];
            return $language;
        }
    }

    /**
     * 加载缓存
     * 
     * @return type
     */
    public function loadCache() {
        $this->_cacheConfig = $this->loadCfg('Config', 'cache');
        if (!isset($this->_cacheConfig[$this->cacheAdapter])) {
            $this->cacheAdapter = 'default';
        }

        try {
            $this->cache = Cache::getInstance($this->_cacheConfig)->getCache($this->cacheAdapter);
        } catch (RuntimeException $e) {
            if (!headers_sent()) {
                header('HTTP/1.1 500 Internal Server Error');
            }
            exit('Load Cache Error: ' . $e->getMessage());
        }
        return $this->cache;
    }

    /**
     * Load Cookie
     */
    public function loadCookie() {
        if ($this->cookie) {
            return $this->cookie;
        }
        require_once KANT_PATH . 'Cookie' . DIRECTORY_SEPARATOR . 'Cookie.php';
        $this->_cookieConfig = $this->loadCfg('Cookie');
        try {
            $this->cookie = Cookie::getInstance($this->_cookieConfig);
        } catch (RuntimeException $e) {
            if (!headers_sent()) {
                header('HTTP/1.1 500 Internal Server Error');
            }
            exit('Load Cache Error: ' . $e->getMessage());
        }
        return $this->cookie;
    }

    /**
     * Load Session
     */
    public function loadSession() {
        if ($this->sessioin) {
            return $this->session;
        }
        require_once KANT_PATH . 'Session' . DIRECTORY_SEPARATOR . 'Session.php';
        $this->_sessionConfig = $this->loadCfg('Session');
        if (!isset($this->_sessionConfig[$this->sessionAdapter])) {
            $this->sessionAdapter = 'default';
        }
        try {
            $this->session = Session::getInstance($this->_sessionConfig)->getSession($this->sessionAdapter);
        } catch (RuntimeException $e) {
            if (!headers_sent()) {
                header('HTTP/1.1 500 Internal Server Error');
            }
            exit('Load Cache Error: ' . $e->getMessage());
        }
    }

    public function url($url = '', $vars = '', $suffix = true) {
        $info = parse_url($url);
        if (isset($info['fragment'])) {
            $anchor = $info['fragment'];
            if (false !== strpos($anchor, '?')) {
                list($anchor, $info['query']) = explode('?', $anchor, 2);
            }
        }
        if (!empty($info['host'])) {
            return $url;
        }
        // 解析参数
        if (is_string($vars)) { // aaa=1&bbb=2 转换成数组
            parse_str($vars, $vars);
        } elseif (!is_array($vars)) {
            $vars = array();
        }
        if (isset($info['query'])) { // 解析地址里面参数 合并到vars
            parse_str($info['query'], $params);
            $vars = array_merge($params, $vars);
        }
        $this->cfg = $this->loadCfg('Config');
        $depr = "/";
        $url = trim($url, $depr);
        $path = explode($depr, $url);

        if ($this->cfg['module'] === true) {
            $var['module'] = $path[0];
            $var['ctrl'] = !empty($path[1]) ? $path[1] : $this->cfg['route']['ctrl'];
            $var['act'] = !empty($path[2]) ? $path[2] : $this->cfg['route']['act'];
            if (!empty($path[3])) {
                $restpath = array_slice($path, 3);
                foreach ($restpath as $key => $val) {
                    $arr = preg_split("/[,:=-]/", $val, 2);
                    $vars[$arr[0]] = isset($arr[1]) ? $arr[1] : '';
                }
            }
        } else {
            $var['ctrl'] = !empty($path[0]) ? $path[0] : $this->get['ctrl'];
            $var['act'] = !empty($path[1]) ? $path[1] : $this->get['act'];
            if (!empty($path[2])) {
                $restpath = array_slice($path, 2);
                foreach ($restpath as $key => $val) {
                    $arr = preg_split("/[,:=-]/", $val, 2);
                    $vars[$arr[0]] = isset($arr[1]) ? $arr[1] : '';
                }
            }
        }
        $url = APP_URL . implode($depr, ($var));
        if (!empty($vars)) { // 添加参数
            foreach ($vars as $var => $val) {
                if ('' !== trim($val)) {
//					$url .= $depr . $var . "," . urlencode($val);
                    $url .= $depr . $var . "," . $val;
                }
            }
        }
        //$url = rtrim($url, "/");
        if ($suffix) {
            $suffix = $suffix === true ? $this->loadCfg('Config', 'url_suffix') : $suffix;
            if ($pos = strpos($suffix, '|')) {
                $suffix = substr($suffix, 0, $pos);
            }
            //if ($suffix && '/' != substr($url, -1)) {
            if ($suffix) {
                $url .= '.' . ltrim($suffix, '.');
            }
        }
        if (isset($anchor)) {
            $url .= '#' . $anchor;
        }
        return $url;
    }

    /**
     * Determine the ajax request
     * 
     * @return boolean
     */
    protected function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            if ('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
                return true;
        }
        if (!empty($_POST['ajax']) || !empty($_GET['ajax'])) {
            return true;
        }
        return false;
    }

    public function widget($widgetname, $method, $data = array(), $return = false) {
        $module = isset($this->get['module']) ? ucfirst($this->get['module']) : '';
        $classname = $widgetname . 'Widget';
        if ($module) {
            $filepath = APP_PATH . 'Module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'Widget' . DIRECTORY_SEPARATOR . $classname . '.php';
        } else {
            $filepath = APP_PATH . 'Widget' . DIRECTORY_SEPARATOR . $classname . '.php';
        }
        if (file_exists($filepath)) {
            include_once $filepath;
            if (!class_exists($classname)) {
                throw new Exception("Class $classname does not exists");
            }
            if (!method_exists($classname, $method)) {
                throw new Exception("Method $method does not exists");
            }
            $widget = new $classname;
            $content = call_user_func_array(array($widget, $method), $data);
            if ($return) {
                return $content;
            } else {
                echo $content;
            }
        }
    }

    public function debugStatus() {
        $this->cfg = $this->loadCfg('Config');
        if (!empty($this->cfg['system']['debug'])) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            return $this->cfg['system']['debug'];
        }
    }

    /**
     *  SET 
     * 
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    /**
     * GET
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        if (isset($this->$name)) {
            return $this->$name;
        } else {
            return;
        }
    }

}
