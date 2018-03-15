<?php

/**
 * 控制器基类
 * Created by PhpStorm.
 * User: furong
 * Date: 2015/11/3
 * Time: 17:23
 */

namespace metacms\web;

use metacms\base\Application;

class Controller
{

    function __construct()
    {

    }

    /**
     * ajax返回数据
     * @param $code
     * @param $message
     * @param $data
     */
    public function ajaxReturn($code, $message, $data)
    {
        $return = array(
            'status' => $code,
            'msg' => $message,
            'data' => $data,
        );
        $this->ajaxExit($return);
    }

    /**
     * ajax返回 json
     * @param $data
     */
    public function ajaxExit($data)
    {
        send_http_status(200);
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * ajax返回数据成功
     * @param string $message
     * @param string $data
     */
    public function ajaxSuccess($message = '执行成功', $data = '')
    {
        $message = !empty($message) ? $message : '执行成功';
        $this->ajaxReturn(1, $message, $data);
    }

    /**
     * ajax返回数据失败
     * @param string $message
     * @param string $data
     */
    public function ajaxFail($message = '执行失败', $data = '')
    {
        $message = !empty($message) ? $message : '执行失败';
        $this->ajaxReturn(0, $message, $data);
    }


    /**
     * 页面跳转
     * @param type $url
     */
    public function redirect($url)
    {
        header('location:' . $url);
        exit();
    }

    /**
     * 设置消息
     * @param type $url
     */
    public function setMessage($msg)
    {
        return Application::setMessage($msg);
    }

    /**
     * 获取消息
     * @param
     */
    public function getMessage()
    {
        return Application::getMessage();
    }

    /**
     * 获取数据
     * @param
     */
    public function getInfo($key)
    {
        return Application::getInfo($key);
    }

    /**
     *  设置数据
     * @param type $key
     * @param type $value
     */
    public function setInfo($key, $value)
    {
        Application::setInfo($key, $value);
    }

}
