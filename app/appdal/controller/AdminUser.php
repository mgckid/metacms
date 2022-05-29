<?php
/**
 * 用户控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-01-23
 * Time: 22:31
 */

namespace app\appdal\controller;

use think\facade\Request;
use \app\appdal\model\AdminUser as AdminUserModel;

class AdminUser extends Base {
    //用户-表单结构数据
    //appdal/adminuser/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //用户-列表结构数据
    //appdal/adminuser/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //用户-列表筛选表单结构数据
    //appdal/adminuser/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }

    //用户-新增
    //appdal/adminuser/add
    public function add() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->add($param);
        return json($result);
    }

    //用户-编辑
    //appdal/adminuser/edit
    public function edit() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->edit($param);
        return json($result);
    }

    //用户-删除
    //appdal/adminuser/del
    public function del() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->del($param);
        return json($result);
    }

    //用户-导入
    //appdal/adminuser/import
    public function import() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->import($param);
        return json($result);
    }

    //用户-获取一条数据
    //appdal/adminuser/getone
    public function getone() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->getone($param);
        return json($result);
    }

    //用户-获取全部数据
    //appdal/adminuser/getall
    public function getall() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->getall($param);
        return json($result);
    }

    //用户-获取列表分页数据
    //appdal/adminuser/index
    public function index() {
        $param = Request::param();
        $model = new AdminUserModel;
        $result = $model->getList($param);
        return json($result);
    }

    //修改密码
    //appdal/adminUser/changePassword
    public function changePassword() {
        $param = Request::post();
        $model = new AdminUserModel;
        $result = $model->changePassword($param);
        return json($result);
    }

    //重置密码
    //appdal/adminUser/resetPassword
    public function resetPassword() {
        $param = Request::post();
        $model = new AdminUserModel;
        $result = $model->resetPassword($param);
        return json($result);
    }

}
