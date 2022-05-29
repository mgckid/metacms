<?php
/**
 * 角色控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-01-23
 * Time: 22:36
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\AdminRole  as  AdminRoleModel;
class AdminRole extends Base {
    //角色-表单结构数据
    //appdal/adminrole/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //角色-列表结构数据
    //appdal/adminrole/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //角色-列表筛选表单结构数据
    //appdal/adminrole/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //角色-新增
    //appdal/adminrole/add
    public function add() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->add($param);
        return json($result);
    }

    //角色-编辑
    //appdal/adminrole/edit
    public function edit() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->edit($param);
        return json($result);
    }

    //角色-删除
    //appdal/adminrole/del
    public function del() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->del($param);
        return json($result);
    }

    //角色-导入
    //appdal/adminrole/import
    public function import() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->import($param);
        return json($result);
    }

    //角色-获取一条数据
    //appdal/adminrole/getone
   public function getone() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->getone($param);
        return json($result);
    }

    //角色-获取全部数据
    //appdal/adminrole/getall
    public function getall() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->getall($param);
        return json($result);
    }

    //角色-获取列表分页数据
    //appdal/adminrole/index
    public function index() {
        $param = Request::param();
        $model = new AdminRoleModel;
        $result = $model->getList($param);
        return json($result);
    }

}
