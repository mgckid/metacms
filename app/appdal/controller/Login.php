<?php
/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2019/9/5
 * Time: 0:52
 */

namespace app\appdal\controller;

use app\BaseController;
use app\appdal\model\AdminUser;
use think\facade\Request;
use app\middleware\AppAuth;

class Login extends BaseController {
    protected $middleware = [AppAuth::class];

    //用户-表单结构数据
    //appdal/Login/login
    public function login() {
        $param = Request::post();
        $model = new AdminUser;
        $result = $model->login($param);
        return json($result);
    }


}