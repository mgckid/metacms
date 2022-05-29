<?php

namespace app\admin\controller;

/**
 * @access 百度编辑器操作|3
 * Created by PhpStorm.
 * User: metacms
 * Date: 2016/9/27
 * Time: 14:33
 */
class Ueditor extends UserBase {
    /**
     * @access 编辑器上传管理|3
     */
    public function index() {
        date_default_timezone_set("Asia/chongqing");
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");

        $action = $_GET['action'];
        $ueditor = new \ueditor\Ueditor();
        switch ($action) {
            case 'config':
                $CONFIG = $ueditor->getConfig();
                $result = json_encode($CONFIG);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = $ueditor->action_upload();
                break;

            /* 列出图片 */
            case 'listimage':
                $result = $ueditor->action_list();
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $ueditor->action_list();
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $ueditor->action_crawler();
                break;

            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            return $result;
        }
    }
}
