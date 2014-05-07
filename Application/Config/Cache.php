<?php

/**
 * Cache Config
 */
return array(
    'default-2' => array(
        'type' => 'baememcache',
        'appid' => 'appidcrbg577o2a'
    ),
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
    ),
    'baememcache' => array(
        'type' => 'baememcache',
        'appid' => 'appidcrbg577o2a'
    )
        )
?>
