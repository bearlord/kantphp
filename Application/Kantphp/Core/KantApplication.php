<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

require_once KANT_PATH . '/Core/Base.php';

final class Kant {

    private static $_instance = null;
    private static $_autoCoreClass = array(
        'Router' => 'Core/KantRouter.php',
        'KantConfig' => 'Config/KantConfig.php',
        'KantRegistry' => 'Core/KantRegistry.php',
        'KantException' => 'Core/KantException.php',
        'BaseController' => 'Controller/BaseController.php',
        'BaseModel' => 'Model/BaseModel.php',
        'View' => 'View/View.php',
        'Input' => 'Help/Input.php',
        'Cache' => 'Cache/Cache.php',
        'Cookie' => 'Cookie/Cookie.php',
        'Session' => 'Session/Session.php',
        'Runtime' => 'Runtime/Runtime.php',
        'Log' => 'Log/Log.php',
        'Hook' => 'Hook/Hook.php',
        'Bootstrap' => 'Bootstrap/Bootstrap.php',
        'Widget' => 'Widget/Widget.php'
    );
    private static $_environment = 'Development';

    /**
     * Run time config
     *
     * @var Kant_Config
     */
    public static $config;

    /**
     * Run time config's reference, for better performance
     *
     * @var array
     */
    protected static $_config;

    /**
     * Object register
     *
     * @var array
     */
    protected static $_reg = array();

    /**
     * Router
     *
     * @var Kant_Router
     */
    protected $_router;

    /**
     * Path info
     *
     * @var string
     */
    private $_pathInfo = null;

    /**
     * Dispathc info
     *
     * @var array
     */
    protected $_dispatchInfo = null;
    protected $defaultAction = 'Index';

    /**
     * Constructs
     */
    public function __construct() {
        $_config['class'] = self::$_autoCoreClass;
        self::$config = new KantConfig($_config);
        //Core configuration
        $coreConfig = include KANT_PATH . DIRECTORY_SEPARATOR . 'Config/Base.php';
        //Application configration
        $appConfig = include CFG_PATH . self::$_environment . DIRECTORY_SEPARATOR . 'Config.php';
        self::$config->merge($coreConfig)->merge($appConfig);
        self::$_config = self::$config->reference();
        KantRegistry::set('config', self::$_config);
    }

    /**
     * Create application
     * 
     * @param type $environment
     * @return type
     */
    public static function createApplication($environment = '') {
        if ($environment == NULL) {
            $environment = self::$_environment;
        }
        return self::getInstance($environment);
    }

    /**
     * Singleton instance
     * 
     * @param type $environment
     * @return type
     */
    public static function getInstance($environment = 'Development') {
        self::registerAutoload();
        self::$_environment = $environment;
        KantRegistry::set('environment', $environment);
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Boot
     * 
     */
    public function boot() {
        //default timezone
        date_default_timezone_set(self::$_config['default_timezone']);
        //logfile initialization
        Log::init();
        Hook::import(self::$_config['tags']);
        if (self::$_config['debug']) {
            Runtime::mark('begin');
        }
        Hook::listen('app_begin');
        $this->exec();
        Hook::listen('app_end');
    }

    /**
     * Execution
     * 
     * @throws KantException
     * @throws ReflectionException
     */
    public function exec() {
        if (!$this->getDispatchInfo()) {
            throw new KantException('No dispatch info found');
        }
        $this->bootstrap();
        $controller = $this->dispatchController();
        $actionSuffix = self::$_config['action_suffix'];
        $action = isset($this->_dispatchInfo['act']) ? $this->_dispatchInfo['act'] . $actionSuffix : 'Index' . $actionSuffix;
        if (!$controller) {
            $controller = $this->dispatchController('empty');
            if (empty($controller)) {
                if (self::$_config['debug']) {
                    throw new KantException(sprintf("No controller exists:%s", ucfirst($this->_dispatchInfo['ctrl']) . 'Controller'));
                } else {
                    $this->redirect($this->lang('no_controller'));
                }
            }
        }
        try {
            if (!preg_match('/^[A-Za-z](\w)*$/', $action)) {
                throw new ReflectionException();
            }
            $method = new ReflectionMethod($controller, $action);
            if ($method->isPublic() && !$method->isStatic()) {
                $method->invoke($controller);
            } else {
                throw new ReflectionException();
            }
        } catch (ReflectionException $e) {
            $method = new ReflectionMethod($controller, '__call');
            $method->invokeArgs($controller, array($action, ''));
        }
    }

    /**
     * 
     * @staticvar array $classes
     * @return boolean|array|\classname
     * @throws KantException
     */
    protected function dispatchController($controller = '') {
        $module = isset($this->_dispatchInfo['module']) ? ucfirst($this->_dispatchInfo['module']) : '';
        if (empty($module)) {
            throw new KantException('No Module found');
        }
        if (empty($controller)) {
            $controller = ucfirst($this->_dispatchInfo['ctrl']) . 'Controller';
        } else {
            $controller = ucfirst($controller) . "Controller";
        }
        $filepath = APP_PATH . 'Module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . $controller . '.php';
        if (file_exists($filepath)) {
            include $filepath;
            if (class_exists($controller)) {
                $class = new $controller;
                return $class;
            }
        }
    }

    /**
     * Bootstrap
     * 
     * @staticvar array $classes
     * @return boolean|array
     */
    protected function bootstrap() {
        $classname = 'AppBootstrap';
        $filepath = APP_PATH . 'Bootstrap' . DIRECTORY_SEPARATOR . $classname . '.php';
        if (file_exists($filepath)) {
            include $filepath;
            if (method_exists($classname, 'initialize')) {
                return call_user_func_array(array($classname, 'initialize'), array());
            }
        }
    }

    /**
     * Load core class
     * 
     * @param type $className
     * @param type $dir
     * @return boolean
     */
    public static function loadCoreClass($className, $dir = '') {
        if (class_exists($className, false) || interface_exists($className, false)) {
            return true;
        }
        if (isset(self::$_autoCoreClass[$className])) {
            require_once KANT_PATH . self::$_autoCoreClass[$className];
            return true;
        }
    }

    /**
     * Set path info
     *
     * @param string $pathinfo
     * @return Cola
     */
    public function setPathInfo($pathinfo = null) {
        if (null === $pathinfo) {
            if (self::$_config['path_info_repair'] == false) {
                $pathinfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
                $pathinfo = str_replace(self::$_config['url_suffix'], '', $pathinfo);
            } else {
                foreach (array('REQUEST_URI', 'HTTP_X_REWRITE_URL', 'argv') as $var) {
                    if ($requestUri = $_SERVER[$var]) {
                        if ($var == 'argv') {
                            $requestUri = strtolower($requestUri[0]);
                        }
                        break;
                    }
                }
                $requestUri = str_replace(self::$_config['url_suffix'], '', ltrim($requestUri, '/'));
                $scriptName = strtolower(ltrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
                //url as [/index.php?module=demo&ctrl=index&act=index] or [/index.php/demo/index/index]
                if (strpos($requestUri, "index.php") !== false) {
                    $parse = parse_url($requestUri);
                    //url as [/index.php?module=demo&ctrl=index&act=index] 
                    if (!empty($parse['query']) && strpos($parse['query'], 'module') !== false) {
                        $pathinfo = "";
                    } else {
                        //url as [/index.php/demo/index/index]
                        $pathinfo = ltrim(str_replace($scriptName, '', $requestUri));
                        if (strpos($pathinfo, "index.php/") !== false) {
                            $pathinfo = str_replace("index.php/", "", $pathinfo);
                        }
                    }
                } else {
                    //url as [/demo/index/index]
                    $pathinfo = ltrim(str_replace($scriptName, '', $requestUri));
                }
            }
        }
        $this->_pathInfo = $pathinfo;
        return $this;
    }

    /**
     * Get path info
     *
     * @return string
     */
    public function getPathInfo() {
        if (null === $this->_pathInfo) {
            $this->setPathInfo();
        }
        return $this->_pathInfo;
    }

    /**
     * Set dispatch info
     *
     * @param array $dispatchInfo
     * @return Cola
     */
    public function setDispatchInfo($dispatchInfo = null) {
        if (null === $dispatchInfo) {
            $router = Router::getInstance();
            $router->setUrlSuffix(self::$_config['url_suffix']);
            $router->add(self::$_config['route_rules']);
            $router->enableDynamicMatch(true, self::$_config['route']);
            $pathInfo = $this->getPathInfo();
            if (!empty($pathInfo)) {
                $dispatchInfo = $router->match($pathInfo);
                $_GET = array_merge($_GET, $dispatchInfo);
            } else {
                $dispatchInfo['module'] = empty($_GET['module']) ? self::$_config['route']['module'] : $_GET['module'];
                $dispatchInfo['ctrl'] = empty($_GET['ctrl']) ? self::$_config['route']['ctrl'] : $_GET['ctrl'];
                $dispatchInfo['act'] = empty($_GET['act']) ? self::$_config['route']['act'] : $_GET['act'];
                $merge = array_merge($_GET, $dispatchInfo);
                $dispatchInfo = $_GET = $merge;
            }
            KantRegistry::set('dispatchInfo', $dispatchInfo);
        }
        $this->_dispatchInfo = $dispatchInfo;
        return $this;
    }

    /**
     * Get dispatch info
     *
     * @return array
     */
    public function getDispatchInfo() {
        if (null === $this->_dispatchInfo) {
            $this->setDispatchInfo();
        }
        return $this->_dispatchInfo;
    }

    /**
     * Dispatch
     */
    public function dispatch() {
        if (!$this->getDispatchInfo()) {
            throw new KantException('No dispatch info found');
        }
    }

    /**
     * Register autoload function
     *
     * @param string $func
     * @param boolean $enable
     */
    public static function registerAutoload($func = 'self::loadCoreClass', $enable = true) {
        $enable ? spl_autoload_register($func) : spl_autoload_unregister($func);
    }

}
