<?php

class SessionController extends BaseController {

    protected $sessionAdapter = 'sqlite';

    public function __construct() {
        $this->loadSession();
    }

    public function indexAction() {
        $this->setAction();
    }

    public function setAction() {
        $_SESSION['laoxu'] = 'Hello World!';
        $_SESSION['demo'] = 'Demo!';
        var_dump($_SESSION);
        highlight_file(__FILE__);
    }

    public function getAction() {
        var_dump($_SESSION['laoxu']);
        var_dump($_SESSION['demo']);
//		echo "<br />Session of laoxu";
//		var_dump($_SESSION['laoxu']);
        var_dump(session_id());
        highlight_file(__FILE__);
    }

}

?>
