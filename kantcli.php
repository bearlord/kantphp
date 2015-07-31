<?php

//Application path
define('APP_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR);

include APP_PATH . 'Kantphp/Kant.php';

$argc = $_SERVER['argc'];
echo $argc;
if ($argc < 2) {
    echo "
USAGE
  php kantcli module [module name]";
    exit();
} else {
    $moduleName = $_SERVER['argv'][1];
    $console = new Console();
    $console->createModule($moduleName);
}

class Console {

    protected $moduleSubFolder = array("Controller", "Model", "Widget");

    public function createModule($moduleName) {
        echo $moduleName;
        foreach ($this->moduleSubFolder as $key => $val) {
            $folder = '';
        }
    }

    public function setModulePath($module) {
        $path = APP_PATH . "Module";
    }

    public function createDir($dir) {
        Dir::create($dir);
    }

}

?>