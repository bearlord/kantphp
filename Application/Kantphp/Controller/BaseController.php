<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

/**
 * Base Controller 
 * 
 * @access public
 * @since version 1.0
 * @todo .etc
 */
class BaseController extends Base {

    protected $view;
    protected $dispatchInfo;

    public function initialize() {
        $this->_initView();
        return $this;
    }

    private function _initView() {
        if ($this->view == '') {
            $this->view = new View();
        }
        return $this->view;
    }

}
