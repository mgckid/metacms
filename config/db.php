<?php
if (ENVIRONMENT == 'develop') {
    //开发环境
    return [
        //前台模块链接
        'HOME_URL' => 'http://www.xxxx.me',
        //接口模块链接
        'API_URL' => 'http://www.xxxx.me',
        //数据库配置
        'DB' => [
            /**
             * 默认数据库配置
             */
            'default' => array(
                'connection_string' => 'mysql:host=127.0.0.1;dbname=metacms;port=3306',
                'id_column' => 'id',
                'id_column_overrides' => array(),
                'error_mode' => \PDO::ERRMODE_EXCEPTION,
                'username' => 'mysql',
                'password' => 'mysql',
                'driver_options' => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    \PDO::ATTR_PERSISTENT => true,
                ],
                'logging' => true,
                'caching' => false,
                'caching_auto_clear' => false,
                'return_result_sets' => false
            ),
        ],
        /*接口代理设置*/
        //'CURLOPT_PROXY' => '127.0.0.1:7777',
        /*接口加密key*/
        'API_ENCRYPTION_KEY' => '',
        /*数据缓存是否开启*/
        'CACHE_OPEN' => 0,
    ];
} elseif (ENVIRONMENT == 'product') {
    //生产环境
    return [
        //前台模块链接
        'HOME_URL' => 'http://www.xxxx.me',
        //接口模块链接
        'API_URL' => 'http://www.xxxx.me',
        //数据库配置
        'DB' => [
            /**
             * 默认数据库配置
             */
            'default' => array(
                'connection_string' => 'mysql:host=127.0.0.1;dbname=metacms;port=3306',
                'id_column' => 'id',
                'id_column_overrides' => array(),
                'error_mode' => \PDO::ERRMODE_EXCEPTION,
                'username' => 'mysql',
                'password' => 'mysql',
                'driver_options' => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    \PDO::ATTR_PERSISTENT => true,
                ],
                'logging' => true,
                'caching' => false,
                'caching_auto_clear' => false,
                'return_result_sets' => false
            ),
        ],
        /*接口加密key*/
        'API_ENCRYPTION_KEY' => '',
        /*数据缓存是否开启*/
        'CACHE_OPEN' => 0,
    ];
}

