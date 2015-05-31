<?php

return array(
    'module_type' => true,
    'route' => array(
        'module' => 'blog',
        'ctrl' => 'index',
        'act' => 'index',
        'data' => array(
            'GET' => array()
        )
    ),
    'route_rules' => array(
        '|topic/id,(\d+)|i' => 'blog/detail/index/id,$1/c,$2'
    ),
    'debug' => true,
    'url_suffix' => '.html',
    'redirect_tpl' => 'dispatch/redirect',
    'admin_redirect_tpl' => 'dispatch/redirect',
    'lang' => 'zh_CN',
    'charset' => 'utf-8',
    'default_timezone' => 'Etc/GMT-8',
    'path_info_repair' => true,
    'lock_ex' => '1',
    'fcs' => 'default',
    'cache' => 'default',
        )
?>
