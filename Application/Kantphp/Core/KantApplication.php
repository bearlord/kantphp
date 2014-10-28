<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

require_once KANT_PATH . '/Core/Base.php';

final class Kant extends Base {

    private static $_instance = null;
    private static $_autoCoreClass = array(
        'Router' => 'Core/Router.php',
        'KantConfig' => 'Config/KantConfig.php',
        'KantRegistry' => 'Core/KantRegistry.php',
        'KantException' => 'Core/KantException.php',
        'BaseController' => 'Controller/BaseController.php',
        'BaseModel' => 'Model/BaseModel.php',
        'View' => 'View/View.php',
        'Input' => 'Help/Input.php',
        'Cache' => 'Cache/Cache.php',
        'Bootstrap' => '/Bootstrap/Bootstrap.php',
        'Widget' => '/Widget/Widget.php',
        'Cookie' => 'Cookie/Cookie.php',
        'Session' => 'Session/Session.php'
    );
    private static $_environment = 'Development';

    /**
     * Run time config
     *
     * @var Cola_Config
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
     * @var Cola_Router
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
     * Bootstramp
     * 
     */
    public function boot() {
//        $this->_initialize();
        if (!$this->getDispatchInfo()) {
            throw new KantException('No dispatch info found');
        }
        $this->bootstrap();
        $this->bootstrapModule();
        $controller = $this->dispatchController();
        $action = isset($this->_dispatchInfo['act']) ? $this->_dispatchInfo['act'] . 'Action' : 'IndexAction';
        if (method_exists($controller, $action)) {
            if ($action{0} == '_') {
                throw new Exception($this->lang('private_action'));
            } else {
//				call_user_func(array($ctrl, $act));
                $controller->initialize()->$action();
            }
        } else {
            if (self::$_config['debug']) {
                throw new Exception(sprintf($this->lang('no_action') . ":%s->%s", ucfirst($this->_dispatchInfo['ctrl']) . 'Controller', $this->_dispatchInfo['act']));
            } else {
                $this->redirect($this->lang('system_error'), 'close');
            }
        }
    }

    /**
     * 
     * @staticvar array $classes
     * @return boolean|array|\classname
     * @throws KantException
     */
    protected function dispatchController() {
        static $classes = array();
        $module = isset($this->_dispatchInfo['module']) ? $this->_dispatchInfo['module'] : '';
        $classname = ucfirst($this->_dispatchInfo['ctrl']) . 'Controller';
        if ($module) {
            $filepath = APP_PATH . 'Module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . $classname . '.php';
        } else {
            $filepath = APP_PATH . 'Controller' . DIRECTORY_SEPARATOR . $classname . '.php';
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
            include $filepath;
            if (class_exists($classname)) {
                $classes[$key] = new $classname;
                return $classes[$key];
            } else {
                if (self::$_config['debug']) {
                    throw new KantException(sprintf("No controller exists:%s", $classname));
                } else {
                    $this->redirect($this->lang('no_controller'));
                }
            }
        } else {
            if (self::$_config['debug']) {
                throw new KantException(sprintf("No controller exists:%s", $classname));
            } else {
                $this->redirect($this->lang('system_error'), 'close');
                return;
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
        static $classes = array();
        $classname = '_Bootstrap';
        $filepath = APP_PATH . 'Bootstrap' . DIRECTORY_SEPARATOR . $classname . '.php';
        $key = md5($filepath . $classname);
        if (isset($classes[$key])) {
            if (!empty($classes[$key])) {
                return $classes[$key];
            } else {
                return true;
            }
        }
        if (file_exists($filepath)) {
            include $filepath;
            if (class_exists($classname)) {
                $bootstrap = new $classname;
                if (method_exists($bootstrap, 'initialize')) {
                    $bootstrap->initialize();
                }
            }
        }
    }

    /**
     * Bootstrap for module
     * 
     * @return boolean
     */
    protected function bootstrapModule() {
        $module = isset($this->_dispatchInfo['module']) ? ucfirst($this->_dispatchInfo['module']) : '';
        $classname = $module . 'Bootstrap';
        if ($module) {
            $filepath = APP_PATH . 'Module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'Bootstrap' . DIRECTORY_SEPARATOR . $classname . '.php';
        } else {
            $filepath = APP_PATH . 'Bootstrap' . DIRECTORY_SEPARATOR . $classname . '.php';
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
            include $filepath;
            if (class_exists($classname)) {
                $bootstrap = new $classname;
                if (method_exists($bootstrap, 'initialize')) {
                    $bootstrap->initialize();
                }
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
//            echo KANT_PATH . self::$_autoCoreClass[$className] . '<br />';
            require_once KANT_PATH . self::$_autoCoreClass[$className];
            return true;
        }
    }

    /**
     * Set router
     *
     * @param Cola_Router $router
     * @return Cola
     */
    public function setRouter($router = null) {
        if (null === $router) {
            $router = Router::getInstance();
        }
        $this->_router = $router;
        return $this;
    }

    /**
     * Get router
     *
     * @return Cola_Router
     */
    public function getRouter() {
        if (null === $this->_router) {
            $this->setRouter();
        }
        return $this->_router;
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
                $pathinfo = str_replace("." . self::$_config['url_suffix'], '', $pathinfo);
            } else {
                foreach (array('REQUEST_URI', 'HTTP_X_REWRITE_URL', 'argv') as $var) {
                    if ($requestUri = $_SERVER[$var]) {
                        if ($var == 'argv') {
                            $requestUri = $requestUri[0];
                        }
                        break;
                    }
                }
                $requestUri = str_replace("." . self::$_config['url_suffix'], '', strtolower(ltrim($requestUri, '/')));
                $scriptName = strtolower(ltrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
                $pathinfo = str_replace($scriptName, '', $requestUri);
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
            $router = $this->getRouter();
            $router->setModuleType(self::$_config['module_type']);
            $router->setUrlSuffix(self::$_config['url_suffix']);
            $router->add(self::$_config['route']);
            $pathInfo = $this->getPathInfo();
            $dispatchInfo = $router->match($pathInfo);
            $_GET = array_merge($_GET, $dispatchInfo);
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
