<?php

//App根目录
define('APP_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR);

include APP_PATH.'Kantphp/Kant.php';

//$begin=microtime_float(microtime());

Kant::createApp();

//$end=microtime_float(microtime());
//echo 'time:'.($end-$begin);
?>
