<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

require_once KANT_PATH . '/Core/Base.php';
require_once KANT_PATH . '/Bootstrap/Bootstrap.php';
require_once KANT_PATH . '/Widget/Widget.php';

final class App extends Base {

    private static $_app;

    public function __construct() {
        parent::__construct();
        $this->cfg = $this->loadCfg('Config');
    }

    public static function getInstance() {
        if (self::$_app == '') {
            self::$_app = new self();
        }
        return self::$_app;
    }

    public function run() {
        $this->initialize();
    }

    /**
     *
     * Initialize
     */
    private function initialize() {
        $this->bootstrap();
        $ctrl = $this->loadCTRL();
        $act = $this->get['act'] ? $this->get['act'] . 'Action' : $this->get['act'] . 'Action';
        //Call controller's function
        if (method_exists($ctrl, $act)) {
            if ($act{0} == '_') {
                throw new Exception($this->lang('private_action'));
            } else {
//				call_user_func(array($ctrl, $act));
                $ctrl->initialize()->$act();
            }
        } else {
            if ($this->cfg['system']['debug']) {
                throw new Exception(sprintf($this->lang('no_action') . ":%s->%s", ucfirst($this->get['ctrl']) . 'Controller', $this->get['act']));
            } else {
                $this->redirect($this->lang('system_error'), 'close');
            }
        }
    }

    private function loadCTRL() {
        static $classes = array();
        $module = isset($this->get['module']) ? ucfirst($this->get['module']) : '';
        $classname = ucfirst($this->get['ctrl']) . 'Controller';
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
                throw new KantException(sprintf("No controller exists:%s", $classname));
                $this->redirect($this->lang('no_controller'));
            }
        } else {
            if ($this->cfg['system']['debug']) {
                throw new KantException(sprintf("No controller exists:%s", $classname));
            } else {
                $this->redirect($this->lang('system_error'), 'close');
                return;
            }
        }
    }

    private function bootstrap() {
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

}