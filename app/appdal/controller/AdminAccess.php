<?php
/**
 * 权限访问控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-01-23
 * Time: 22:38
 */

namespace app\appdal\controller;

use think\facade\Request;
use \app\appdal\model\AdminAccess as AdminAccessModel;

class AdminAccess extends Base {
    //权限访问-表单结构数据
    //appdal/adminaccess/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //权限访问-列表结构数据
    //appdal/adminaccess/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //权限访问-列表筛选表单结构数据
    //appdal/adminaccess/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }

    //权限访问-新增
    //appdal/adminaccess/add
    public function add() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->add($param);
        return json($result);
    }

    //权限访问-编辑
    //appdal/adminaccess/edit
    public function edit() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->edit($param);
        return json($result);
    }

    //权限访问-删除
    //appdal/adminaccess/del
    public function del() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->del($param);
        return json($result);
    }

    //权限访问-导入
    //appdal/adminaccess/import
    public function import() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->import($param);
        return json($result);
    }

    //权限访问-获取一条数据
    //appdal/adminaccess/getone
    public function getone() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->getone($param);
        return json($result);
    }

    //权限访问-获取全部数据
    //appdal/adminaccess/getall
    public function getall() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->getall($param);
        return json($result);
    }

    //权限访问-获取列表分页数据
    //appdal/adminaccess/index
    public function index() {
        $param = Request::param();
        $model = new AdminAccessModel;
        $result = $model->getList($param);
        return json($result);
    }

    //权限访问-初始所有权限
    //appdal/adminaccess/initAccess
    public function initAccess() {
        $param = Request::post();
        $model = new AdminAccessModel;
        $result = $model->initAccess($param);
        return json($result);
    }
}
