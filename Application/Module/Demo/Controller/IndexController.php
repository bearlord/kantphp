<?php

/**
 * \Demo\IndexController
 */
class IndexController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 欢迎
     */
    public function indexAction() {
        echo "Welcome to KantPHP Framework";
    }

    /**
     * 赋值到视图
     */
    public function displayAction() {
        $this->view->str = 'hello';
        $this->view->row = array('0ne' => 'Tom', 'Two' => '中文');
        $this->view->display();
    }

    /**
     * 视图函数
     */
    public function displayfuncAction() {
        $this->view->time = time();
        $this->view->str = "abcdefg";
        $this->view->display();
    }

    /**
     * Get
     */
    public function getAction() {
        print_r($_GET);
        print_r($this->get);
    }

    /**
     * Post 
     */
    public function postAction() {
        var_dump($_POST);
        var_dump($this->post);
    }
    
    public function _empty() {
        echo 'empty';
    }

}

?>
