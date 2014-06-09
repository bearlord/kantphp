<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
define('IN_KANT', TRUE);
//KantPHP path
define('KANT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

require_once KANT_PATH . 'Function/Global.php';
require_once KANT_PATH . 'Core/Application.php';
require_once KANT_PATH . 'Core/KantRegistry.php';
require_once APP_PATH . 'Function/Common.php';
//App path
if (!defined('APP_PATH'))
    define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR);

//Cache directory
define('CACHE_PATH', APP_PATH . 'Cache' . DIRECTORY_SEPARATOR);
//Template directory
define('TPL_PATH', APP_PATH . 'View' . DIRECTORY_SEPARATOR);
//Config directroy
define('CFG_PATH', APP_PATH . 'Config' . DIRECTORY_SEPARATOR);
define('MODULE_PATH', APP_PATH . 'Module' . DIRECTORY_SEPARATOR);
//Libary directory
define('LIB_PATH', APP_PATH . 'Libary' . DIRECTORY_SEPARATOR);
//Public Path
define('PUBLIC_PATH', dirname(APP_PATH) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
//Web root
if (!defined('APP_URL')) {
    define('APP_URL', substr(dirname($_SERVER['SCRIPT_NAME']), -1, 1) == '/' ? dirname($_SERVER['SCRIPT_NAME']) : dirname($_SERVER['SCRIPT_NAME']) . '/' );
}
define('PUBLIC_URL', APP_URL . 'public/');

//header("Content-type: text/html; charset=utf-8"); 

class Kant {

    /**
     *
     * Create application
     * @return object on success
     */
    public static function createApplication($environment = 'Development') {
        KantRegistry::set('environment', $environment);
        Application::getInstance()->boot();
        
    }

}