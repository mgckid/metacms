<?php
/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2022/3/27
 * Time: 23:26
 */

namespace app\admin\controller;


use app\BaseController;
use think\facade\Session;

class Login extends BaseController {
    /**
     * 后台登录
     */
    public function index() {
        if ($this->request->isPost()) {
            $input = $this->request->post();
            $param = $input;
            $result = appRequest('appdal/login/login', $param);
            if (!isset($result['status']) or $result['status'] != 1) {
                return json(fail($result['msg'] ?? ''));
            }
            session('login_info', $result['data'] ?? []);
            return json(success('', '登陆成功'));
        } else {
            if (\session('login_info')) {
                return redirect('/admin/Index/index');
            }
            return view('login/index1');
        }
    }

    /**
     * 登出系统
     */
    public function logout() {
        if (!$this->request->isPost()) {
            return json(fail('非法访问'));
        }
        /*$logout = $this->request->request('logout', false);
        if (!$logout) {
            return $this->ajaxFail('非法访问');
        }*/
        if (Session::has('login_info')) {
            Session::delete('login_info');
        }
        return json(success());
    }
}