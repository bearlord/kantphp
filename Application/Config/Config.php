<?php

return array(
    'route' => array(
        'module' => 'index',
        'ctrl' => 'index',
        'act' => 'index',
        'data' => array(
            'GET' => array()
        )
    ),
    'url_suffix' => 'html',
    //'redirect_tpl' => 'dispatch/redirect',
    'module' => false,
    'system' => array(
        'web_path' => '/',
        'tpl_style' => 'default',
        'lang' => 'zh_CN',
        'charset' => 'utf-8',
        'timezone' => 'Etc/GMT-8',
        'debug' => 1,
        'lock_ex' => '1',
        'static_url' => '/Static/',
        'js_url' => '/Static/js/',
        'css_url' => '/Static/css/',
        'img_url' => '/Static/images/',
    )
        )
?>
