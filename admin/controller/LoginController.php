<?php

/**
 * 登录注册控制器
 * @date 2016年5月1日 11:11:33
 * @author Administrator
 */

namespace app\controller;

use app\model\AdminUserRoleModel;
use app\model\AdminUserModel;
use metacms\base\Application;

class LoginController extends BaseController
{


    /**
     * 后台登录
     */
    public function index()
    {
        if (IS_POST) {
            $userModel = new AdminUserModel();
            #验证
            $rules = array(
                'username' => 'required|alpha',
                'password' => 'required|alpha_num|min:6',
            );
            $attr = array(
                'username' => '用户名',
                'password' => '密码',
            );
            $validate = $userModel->validate()->make($_POST, $rules, $attr);
            if (false === $validate->passes()) {
                $this->ajaxFail($validate->messages()->first());
            }

            #获取参数
            $userName = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            $AdminUserRoleModel = new AdminUserRoleModel();
            if (!$userModel->validatePassword($userName, $password)) {
                $this->ajaxFail($this->getMessage());
            }
            $userinfo = $userModel->getUserInfo($userName);
            $userinfo['roleInfo'] = $AdminUserRoleModel->getRoleByUserId($userinfo['user_id']);
            $this->segment()->set('loginInfo', $userinfo);
            $this->session()->commit();
            $this->ajaxSuccess('登陆成功');
        } else {
            if ($this->segment()->get('loginInfo')) {
                $this->redirect(U('admin/Index'));
            }
            $this->display('Login/index');
        }
    }

    /**
     * 登出系统
     */
    public function logout()
    {
        if (!IS_POST)
            $this->ajaxFail('非法访问');
        $logout = isset($_POST['logout']) ? $_POST['logout'] : false;
        if (!$logout) {
            $this->ajaxFail('非法访问');
        }
        if ($this->segment()->get('loginInfo')) {
            $this->segment()->clear();
        }
        $this->ajaxSuccess();
    }

}
