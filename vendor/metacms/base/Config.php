<?php
/**
 * Created by PhpStorm.
 * User: furong
 * Date: 2017/3/22
 * Time: 15:54
 */

namespace metacms\base;


class Config extends \Noodlehaus\Config
{
    protected function getDefaults()
    {
        return array(
            /*框架自定义配置 开始*/
            /*时区设置*/
            'timezone_set'=>'PRC',
            /*http请求路由打包数据*/
            'request_data' => function ($c) {
                $request =  new \metacms\base\Request($c['config']->all());
                return $request->run();
            },
            /*模版引擎组件依赖注入*/
            'templateEngine' => function ($c) {
                return new \League\Plates\Engine();
            },
            /*验证器组件依赖注入*/
            'validation' => function ($c) {
                $lang = require FRAMEWORK_PATH . '/vendor/overtrue/zh-CN/validation.php';
                return new \Overtrue\Validation\Factory(new \Overtrue\Validation\Translator($lang));
            },
            /*缓存组件依赖注入*/
            'cache' => function ($c) {
                $cache_dir = APP_PATH . '/cache/';
                if (!is_dir($cache_dir)) {
                    mkdir($cache_dir);
                }
                $cache = new Cache();
                return $cache->setCachePath($cache_dir);
            },
            /*错误处理组件*/
            'error_handler_set'=>function(){
                set_error_handler('errorHandle');
            },

            /*应用加载脚本*/
            'REQUIRE_SCRIPT_MAP' => [],
            /*框架自定义配置 结束*/

            /* 数据库设置 开始 */
            'DB' => array(
                'default' => array(
                    'connection_string' => 'sqlite::memory:',
                    'id_column' => 'id',
                    'id_column_overrides' => array(),
                    'error_mode' => \PDO::ERRMODE_EXCEPTION,
                    'username' => null,
                    'password' => null,
                    'driver_options' => [
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                        \PDO::ATTR_PERSISTENT => true,
                    ],
                    'logging' => true,
                    'caching' => false,
                    'caching_auto_clear' => false,
                    'return_result_sets' => false,
                    #下面三个配置打开会报错
                    /*
                    'identifier_quote_character' => null, // if this is null, will be autodetected
                    'limit_clause_style'         => null, // if this is null, will be autodetected
                    'logger'                     => null,
                    */
                )
            ),
            /* 数据库设置 结束*/

            /*应用设置 开始*/
            'EXT_CONTROLLER' => 'Controller',
            'EXT_MODEL' => 'Model',
            'DIR_CONTROLLER' => 'controller',
            'DIR_MODEL' => 'model',
            'DIR_VIEW' => 'view',
            '404_PAGE' => '',
            /*应用设置 结束*/

            /*http请求设置 开始*/
            /* URL设置 */
            'URL_MODE' => 0, //url访问模式  0：默认动态url传参模式 1：pathinfo模式 2:兼容模式
            /*默认设置*/
            'ALLOW_MODULE_LIST' => 'home',
            'DEFAULT_MODULE' => 'home',
            'DEFAULT_CONTROLLER' => 'Index',
            'DEFAULT_ACTION' => 'index',
            /* 系统变量名设置 */
            'VAR_CONTROLLER' => 'c',
            'VAR_ACTION' => 'a',
            'VAR_MODULE' => 'm',
            'VAR_ROUTE' => 'route',
            /*子域名泛解析设置*/
            'MAIN_DOMAIN' => '',
            'SUB_DOMAIN_OPEN' => false,
            'SUB_DOMAIN_RULES' => [
                'www' => 'home'
            ],
            /*http请求设置 结束*/
            /*模版主题*/
            'THEME' => 'default',
        );
    }
}