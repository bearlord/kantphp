<?php

class IndexController extends BaseController {

    public function indexAction() {
        print "<hr />";
        print $this->lang('welcome');
    }

    public function viewAction() {
        $intro_title = "This is Dongzhu!";
        $intro_desc = array(
            'name' => 'zhangsan',
            'qq' => '565364226'
        );
        $intro_notes = array(
            '4-30' => 'Go home',
            '5-4' => 'Go to work'
        );
        $this->view->intro_title = $intro_title;
        $this->view->intro_desc = $intro_desc;
        $this->view->intro_notes = $intro_notes;
        $this->view->display();
    }

}

?>