<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

abstract class Widget extends Base {

    protected function rederFile($templateFile = '', $var = '') {
        $templateFile = strtolower($templateFile);
        ob_start();
        ob_implicit_flush(0);
        $module = isset($this->get['module']) ? ucfirst($this->get['module']) : '';
        if ($module) {
            $filepath = APP_PATH . 'Module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'Widget' . DIRECTORY_SEPARATOR . 'Tpl' . DIRECTORY_SEPARATOR . $templateFile . '.php';
        } else {
            $filepath = APP_PATH . 'Widget' . DIRECTORY_SEPARATOR . 'Tpl' . DIRECTORY_SEPARATOR . $templateFile . '.php';
        }
        extract($var);
        include $filepath;
        $content = ob_get_clean();
        return $content;
    }

    public function buildTplFile($widgetmethod) {
        $path = strtolower(str_replace('Widget', '', $widgetmethod));
        $path = str_replace("::", "/", $path);
        return $path;
    }

}

?>
