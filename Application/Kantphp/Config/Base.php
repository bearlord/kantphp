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
    'route_rules' => array(),
    'path_info_repair' => false,
    'debug' => false,
    'url_suffix' => '.html',
    'action_suffix' => 'Action',
    'redirect_tpl' => 'dispatch/redirect',
    'lang' => 'zh_CN',
    'default_timezone' => 'Etc/GMT-8',
    'charset' => 'utf-8',
    'lock_ex' => '1',
    'cookie' => array(
        'cookie_domain' => '',
        'cookie_path' => '/',
        'cookie_pre' => 'kantphp_',
        'cookie_ttl' => 0,
        'auth_key' => 'NMa1FcQBE1HHHd4AQyTV'
    ),
    'session_handle' => true,
    'session' => array(
        'default' => array(
            'type' => 'original',
            'maxlifetime' => 1800,
        ),
        'file' => array(
            'type' => 'file',
            'maxlifetime' => 1800,
            'auth_key' => 'NMa1FcQBE1HHHd4AQyTV',
        ),
        'sqlite' => array(
            'type' => 'sqlite',
            'maxlifetime' => 1800,
            'auth_key' => 'NMa1FcQBE1HHHd4AQyTV',
        )),
    'database' => array(
        'default' => array(
            'hostname' => 'localhost',
            'port' => '3306',
            'database' => 'kantphp',
            'username' => 'root',
            'password' => 'root',
            'tablepre' => 'kant_',
            'charset' => 'utf8',
            'type' => 'mysql',
            'debug' => true,
            'persistent' => 0,
            'autoconnect' => 1
        ),
        //default configuration
        'default-openshift' => array(
            'hostname' => getenv('OPENSHIFT_MYSQL_DB_HOST'),
            'port' => getenv('OPENSHIFT_MYSQL_DB_PORT'),
            'database' => 'mzqltbruzaqbrsxpalti',
            'username' => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
            'password' => getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
            'tablepre' => 'kant_',
            'charset' => 'utf8',
            'type' => 'mysql',
            'debug' => true,
            'persistent' => 0,
            'autoconnect' => 1
        ),
        'pgsql_demo' => array(
            'hostname' => 'localhost',
            'port' => '5432',
            'database' => 'bbs',
            'username' => 'root',
            'password' => 'root',
            'tablepre' => 'bbs_',
            'charset' => 'UTF-8',
            'type' => 'pdo_pgsql',
            'debug' => true,
            'persistent' => 0,
            'autoconnect' => 1
        ),
        'sqlite' => array(
            'hostname' => '',
            'port' => '',
            'database' => CACHE_PATH . 'SqliteDb/test.db',
            'username' => '',
            'password' => '',
            'tablepre' => 'test_',
            'charset' => 'UTF-8',
            'type' => 'pdo_sqlite',
            'debug' => true,
            'persistent' => 0,
            'autoconnect' => 1
        )
    ),
    //cache config
    'cache' => array(
        //default file cache type
        'defalut' => array(
            'type' => 'file',
            'debug' => true,
            'pconnect' => 0,
            'autoconnect' => 0
        ),
        //memcache type
        'memcache' => array(
            'type' => 'memcache',
            'hostname' => 'localhost',
            'port' => 11211,
            'timeout' => 0,
            'debug' => true,
            'pconnect' => 0,
            'autoconnect' => 0
        ),
        //redis cache type
        'redis' => array(
            'type' => 'redis',
            'hostname' => '127.0.0.1',
            'port' => 6379
        )
    ),
);

