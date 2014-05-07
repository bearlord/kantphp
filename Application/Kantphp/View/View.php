<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

/**
 * View class
 * @access public
 * @version 1.1
 * @since version 1.0 
 */
class View extends Base {

    public $cfg;
    public $debug;
    public $theme = 'default';

    public function __construct() {
        parent::__construct();
    }

    public function __get($key) {
        if (isset($this->$key)) {
            return($this->$key);
        } else {
            return(NULL);
        }
    }

    public function __set($key, $value) {
        $this->$key = $value;
    }

    /**
     *  
     * @param string $file
     * @throws RuntimeException
     */
    public function display($file = '') {
        if (empty($file)) {
            $ctrl = strtolower($this->get['ctrl']);
            $act = strtolower($this->get['act']);
        } else {
            list($ctrl, $act) = explode("/", strtolower($file));
        }
        $tpldir = $this->_getTplDir();
        $tplfile = $tpldir . $ctrl . DIRECTORY_SEPARATOR . $act . '.php';
        if (!file_exists($tplfile)) {
            if ($this->debug) {
                throw new RuntimeException(sprintf("No template: %s", $tplfile));
            } else {
                $this->redirect($this->lang('system_error'), 'close');
            }
        }
        include_once $tplfile;
        if (!headers_sent()) {
            header("Content-type: text/html; charset=utf-8");
        }
    }

    public function fetch($file) {
        if (empty($file)) {
            return;
        }
        list($ctrl, $act) = explode("/", strtolower($file));
        $tpldir = $this->_getTplDir();
        $tplfile = $tpldir . $ctrl . DIRECTORY_SEPARATOR . $act . '.php';
        if (!file_exists($tplfile)) {
            if ($this->debug) {
                throw new RuntimeException(sprintf("No template: %s", $tplfile));
            } else {
                $this->redirect($this->lang('system_error'), 'close');
            }
        }
        ob_start();
        ob_implicit_flush(0);
        include_once $tplfile;
        $content = ob_get_clean();
        return $content;
    }

    public function includeTpl($file, $module = '') {
        if (empty($file)) {
            $ctrl = strtolower($this->get['ctrl']);
            $act = strtolower($this->get['act']);
        } else {
            list($ctrl, $act) = explode("/", strtolower($file));
        }
        $tpldir = $this->_getTplDir($module);
        $tplfile = $tpldir . $ctrl . DIRECTORY_SEPARATOR . $act . '.php';
        if (!file_exists($tplfile)) {
            if ($this->debug) {
                throw new RuntimeException(sprintf("No template: %s", $tplfile));
            } else {
                $this->redirect($this->lang('system_error'), 'close');
            }
        }
        include_once $tplfile;
    }

    /**
     * Get Tpl Dir
     */
    private function _getTplDir($module = '') {
        if ($module == '') {
            $module = isset($this->get['module']) ? ucfirst($this->get['module']) : '';
        } else {
            $module = ucfirst($module);
        }
        if ($module) {
            $tpldir = APP_PATH . 'Module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $this->theme . DIRECTORY_SEPARATOR;
        } else {
            $tpldir = TPL_PATH . $this->theme . DIRECTORY_SEPARATOR;
        }
        return $tpldir;
    }

}
