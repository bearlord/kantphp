<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2015 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

require_once KANT_PATH . '/Core/Base.php';

final class Kant {

    private static $_instance = null;
    private static $_autoCoreClass = array(
        'KantRouter' => 'Core/KantRouter.php',
        'KantDispatch' => 'Core/KantDispatch.php',
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
        'KantBootstrap' => 'Bootstrap/KantBootstrap.php',
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
        Log::init(array(
            'type' => 'File',
            'log_path' => LOG_PATH
        ));
        Hook::import(self::$_config['tags']);
        if (self::$_config['debug']) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            Runtime::mark('begin');
        }
        Hook::listen('app_begin');
        $this->exec();
        Hook::listen('app_end');
        if (self::$_config['debug']) {
            Runtime::mark('end');
        }
    }

    /**
     * Execution
     * 
     * @throws KantException
     * @throws ReflectionException
     */
    public function exec() {
        $this->_dispatchInfo = KantDispatch::getInstance()->getDispatchInfo();
        $this->bootstrap();
        $controller = $this->controller();
        if (!$controller) {
            $controller = $this->controller('empty');
            if (empty($controller)) {
                throw new KantException(sprintf("No controller exists:%s", ucfirst($this->_dispatchInfo['ctrl']) . 'Controller'));
            }
        }
        $action = isset($this->_dispatchInfo['act']) ? $this->_dispatchInfo['act'] . self::$_config['action_suffix'] : 'Index' . self::$_config['action_suffix'];
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
    protected function controller($controller = '') {
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
        $classname = 'Bootstrap';
        $filepath = APP_PATH . 'Bootstrap' . DIRECTORY_SEPARATOR . $classname . '.php';
        if (file_exists($filepath)) {
            include $filepath;
            if (method_exists($classname, 'initialize')) {
                return call_user_func(array($classname, 'initialize'));
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
     * Register autoload function
     *
     * @param string $func
     * @param boolean $enable
     */
    public static function registerAutoload($func = 'self::loadCoreClass', $enable = true) {
        $enable ? spl_autoload_register($func) : spl_autoload_unregister($func);
    }

}
