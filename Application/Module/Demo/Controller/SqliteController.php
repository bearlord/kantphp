<?php

class SqliteController extends BaseController {

    protected $dM;

    public function __construct() {
        $this->dM = $this->loadModel("SqliteDemo");
    }

    public function IndexAction() {
        $a = $this->dM->readAll();
        var_dump($a);
        echo "index";
    }

}

?>
