<?php

/**
 * Database Config
 * 
 */
return array(
    //bae
    'default-bae' => array(
        'hostname' => 'localhost',
        'port' => '3306',
        'database' => 'mzQLTbRuzAqBRsxpaLti',
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
    'default' => array(
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
        )
?>
