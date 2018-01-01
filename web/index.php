<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/12/20
 * Time: 16:22
 */

/*
                   _ooOoo_
                  o8888888o
                  88" . "88
                  (| -_- |)
                  O\  =  /O
               ____/`---'\____
             .'  \\|     |//  `.
            /  \\|||  :  |||//  \
           /  _||||| -:- |||||-  \
           |   | \\\  -  /// |   |
           | \_|  ''\---/''  |   |
           \  .-\__  `-`  ___/-. /
         ___`. .'  /--.--\  `. . __
      ."" '<  `.___\_<|>_/___.'  >'"".
     | | :  `- \`.;`\ _ /`;.`/ - ` : | |
     \  \ `-.   \_ __\ /__ _/   .-` /  /
======`-.____`-.___\_____/___.-`____.-'======
                   `=---='
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         佛祖保佑       永无BUG
*/
error_reporting(E_ALL);
ini_set('display_errors', true);
#框架运行开发模式
define('ENVIRONMENT', 'develop');
define('APP_REAL_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', APP_REAL_PATH);

require APP_PATH . '/vendor/metacms\index.php';