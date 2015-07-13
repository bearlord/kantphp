<?php

return array(
    'route' => array(
        'module' => 'demo',
        'ctrl' => 'index',
        'act' => 'index',
        'data' => array(
            'GET' => array()
        )
    ),
    'url_suffix' => '.html',
    'redirect_tpl' => 'dispatch/redirect',
    'admin_redirect_tpl' => 'dispatch/redirect',
    'module' => true,
    'lang' => 'zh_CN',
    'charset' => 'utf-8',
    'timezone' => 'Etc/GMT-8',
    'debug' => 1,
    'lock_ex' => '1',
    'fcs' => 'default',
    'cache' => 'default'
        )
?>
