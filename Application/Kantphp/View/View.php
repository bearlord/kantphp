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

    public $theme = 'default';
    protected $dispatchInfo;

    public function __construct() {
        parent::__construct();
        $this->dispatchInfo = KantRegistry::get('dispatchInfo');
    }

    /**
     * 
     * @param type $key
     * @return type
     */
    public function __get($key) {
        if (isset($this->$key)) {
            return($this->$key);
        } else {
            return(NULL);
        }
    }

    /**
     * 
     * @param type $key
     * @param type $value
     */
    public function __set($key, $value) {
        $this->$key = $value;
    }

    /**
     * Set theme
     * 
     * @param type $theme
     * @return \View
     */
    public function theme($theme) {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Parse template path
     */
    public function parseTemplate($template = '') {
        if (is_file($template)) {
            return $template;
        }
        if (empty($template)) {
            $ctrl = strtolower($this->dispatchInfo['ctrl']);
            $act = strtolower($this->dispatchInfo['act']);
        } else {
            list($ctrl, $act) = explode("/", strtolower($template));
        }
        $tpldir = $this->getTplDir();
        $tplfile = $tpldir . $ctrl . DIRECTORY_SEPARATOR . $act . '.php';
        if (!file_exists($tplfile)) {
            if ($this->debug) {
                throw new RuntimeException(sprintf("No template: %s", $tplfile));
            } else {
                $this->redirect($this->lang('system_error'), 'close');
            }
        }
        return $tplfile;
    }

    /**
     *  
     * @param string $template
     * @throws RuntimeException
     */
    public function display($template = '') {
        $tplfile = $this->parseTemplate($template);
        include_once $tplfile;
    }

    /**
     * 
     * @param type $template
     * @return type
     * @throws RuntimeException
     */
    public function fetch($template) {
        $tplfile = $this->parseTemplate($template);
        ob_start();
        ob_implicit_flush(0);
        include_once $tplfile;
        $content = ob_get_clean();
        return $content;
    }

    /**
     * 
     * @param type $template
     * @param type $module
     * @throws RuntimeException
     */
    public function includeTpl($template, $module = '') {
        if (empty($template)) {
            $ctrl = strtolower($this->dispatchInfo['ctrl']);
            $act = strtolower($this->dispatchInfo['act']);
        } else {
            list($ctrl, $act) = explode("/", strtolower($template));
        }
        $tpldir = $this->getTplDir($module);
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
    protected function getTplDir($module = '') {
        if ($module == '') {
            $module = isset($this->dispatchInfo['module']) ? strtolower($this->dispatchInfo['module']) : '';
        }
        if ($module) {
            $tpldir = TPL_PATH . $this->theme . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
        } else {
            $tpldir = TPL_PATH . $this->theme . DIRECTORY_SEPARATOR;
        }
        return $tpldir;
    }

}
