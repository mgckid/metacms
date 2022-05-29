<?php
/**
 * 角色权限关联控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-26
 * Time: 13:44
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\AdminRoleAccess  as  AdminRoleAccessModel;
class AdminRoleAccess extends Base {
    //角色权限关联-表单结构数据
    //appdal/adminroleaccess/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //角色权限关联-列表结构数据
    //appdal/adminroleaccess/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //角色权限关联-列表筛选表单结构数据
    //appdal/adminroleaccess/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //角色权限关联-新增
    //appdal/adminroleaccess/add
    public function add() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->add($param);
        return json($result);
    }

    //角色权限关联-编辑
    //appdal/adminroleaccess/edit
    public function edit() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->edit($param);
        return json($result);
    }

    //角色权限关联-删除
    //appdal/adminroleaccess/del
    public function del() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //角色权限关联-保存
    //appdal/adminroleaccess/store
    public function store() {
         $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //角色权限关联-导入
    //appdal/adminroleaccess/import
    public function import() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->import($param);
        return json($result);
    }

    //角色权限关联-获取一条数据
    //appdal/adminroleaccess/getone
   public function getone() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->getone($param);
        return json($result);
    }

    //角色权限关联-获取全部数据
    //appdal/adminroleaccess/getall
    public function getall() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->getall($param);
        return json($result);
    }

    //角色权限关联-获取列表分页数据
    //appdal/adminroleaccess/index
    public function index() {
        $param = Request::param();
        $model = new AdminRoleAccessModel;
        $result = $model->getList($param);
        return json($result);
    }

}
