<?php
/**
 * Created by PhpStorm.
 * User: furong
 * Date: 2017/5/26
 * Time: 16:07
 */

namespace metacms\base;


use Exceptions\Http\Client\NotFoundException;
use Pimple\Container;

class Application
{
    protected static $instance;
    protected $container;
    protected $message;
    protected $info;

    private function __construct()
    {
    }


    /**
     * 运行应用
     * @access public
     * @author furong
     * @param $config
     * @return void
     * @since  2017年3月22日 16:44:31
     * @abstract
     */
    public static function run()
    {
        $container = self::container();
        $loader = $container['loader'];
        #添加应用类文件加载位置
        $appPath = array(
            MODULE_PATH,
            COMMON_PATH,
        );
        $loader->addPrefix('app', $appPath);

        #运行程序
        $controller_name = 'app\\' . self::config()->get('DIR_CONTROLLER') . '\\' . CONTROLLER_NAME . self::config()->get('EXT_CONTROLLER');
        if (!class_exists($controller_name)) {
            throw new NotFoundException($controller_name . '控制器不存在');
        } elseif (!method_exists($controller_name, ACTION_NAME)) {
            throw new NotFoundException($controller_name . '控制器下' . ACTION_NAME . '方法不存在');
        } else {
            #执行方法
            call_user_func(array(new $controller_name, ACTION_NAME));
        }
    }

    /**
     * 获取类实例化对象
     * @return $this
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 获取类注册器
     * @return \Pimple\Container
     */
    static public function container()
    {
        if (!self::getInstance()->container) {
            self::getInstance()->container = new Container();
        }
        return self::getInstance()->container;
    }

    /**
     * 设置消息
     * @param $msg
     */
    public static function setMessage($msg)
    {
        self::getInstance()->message = $msg;
    }

    /**
     * 获取消息
     * @return mixed
     */
    public static function getMessage()
    {
        return self::getInstance()->message;
    }

    /**
     * 设置数据
     * @param $name
     * @param null $value
     */
    public static function setInfo($name, $value = NULL)
    {
        self::getInstance()->info[$name] = $value;
    }

    /**
     * 获取数据
     * @param $name
     * @return null
     */
    public static function getInfo($name)
    {
        $return = null;
        if (isset(self::getInstance()->info[$name])) {
            $return = self::getInstance()->info[$name];
        }
        return $return;
    }

    /**
     * 回话组件
     * @return  \metacms\base\Config
     */
    static function config()
    {
        return self::container()['config'];
    }


    /**
     * 缓存组件
     * @return  \metacms\base\Cache
     */
    static function cache($cache_name = null)
    {
        return self::container()['cache']->setCache($cache_name);
    }


    /**
     * @access public
     * @author furong
     * @return \Overtrue\Validation\Factory
     * @since
     * @abstract
     */
    static function validation()
    {
        return self::container()['validation'];
    }

    /**
     * 模版引擎组件
     * @access public
     * @author furong
     * @return \League\Plates\Engine
     * @since
     * @abstract
     */
    static function templateEngine()
    {
        return self::container()['templateEngine'];
    }

    /**
     * 钩子组件
     * @access public
     * @author furong
     * @return \metacms\base\Hooks
     * @since
     * @abstract
     */
    static function hooks()
    {
        return self::container()['hooks'];
    }
}