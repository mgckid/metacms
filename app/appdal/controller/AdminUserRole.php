<?php
/**
 * 用户角色关联控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-13
 * Time: 22:00
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\AdminUserRole  as  AdminUserRoleModel;
class AdminUserRole extends Base {
    //用户角色关联-表单结构数据
    //appdal/adminuserrole/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //用户角色关联-列表结构数据
    //appdal/adminuserrole/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //用户角色关联-列表筛选表单结构数据
    //appdal/adminuserrole/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //用户角色关联-新增
    //appdal/adminuserrole/add
    public function add() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->add($param);
        return json($result);
    }

    //用户角色关联-编辑
    //appdal/adminuserrole/edit
    public function edit() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->edit($param);
        return json($result);
    }

    //用户角色关联-删除
    //appdal/adminuserrole/del
    public function del() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->del($param);
        return json($result);
    }

    //用户角色关联-变更
    //appdal/adminuserrole/store
    public function store() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->store($param);
        return json($result);
    }

    //用户角色关联-导入
    //appdal/adminuserrole/import
    public function import() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->import($param);
        return json($result);
    }

    //用户角色关联-获取一条数据
    //appdal/adminuserrole/getone
   public function getone() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->getone($param);
        return json($result);
    }

    //用户角色关联-获取全部数据
    //appdal/adminuserrole/getall
    public function getall() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->getall($param);
        return json($result);
    }

    //用户角色关联-获取列表分页数据
    //appdal/adminuserrole/index
    public function index() {
        $param = Request::param();
        $model = new AdminUserRoleModel;
        $result = $model->getList($param);
        return json($result);
    }

}
