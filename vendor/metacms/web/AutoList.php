<?php
/**
 * Created by PhpStorm.
 * User: furong
 * Date: 2017/8/1
 * Time: 14:26
 */

namespace metacms\web;


class AutoList
{
    static protected $instance;

    protected $list_init;
    #列表样式
    protected $table_class = 'table';

    protected function __construct()
    {

    }

    /**
     * 增加列表样式class
     * @access public
     * @author furong
     * @param $table_class
     * @return $this
     * @since
     * @abstract
     */
    public function add_table_class($table_class)
    {
        self::getInstance()->table_class .= ' ' . $table_class;
        return $this;
    }

    /**
     * 增加列表初始化数据
     * @access public
     * @author furong
     * @param $list_init
     * @return $this
     * @since
     * @abstract
     */
    public function list_init($list_init)
    {
        self::getInstance()->list_init = $list_init;
        return $this;
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

} 