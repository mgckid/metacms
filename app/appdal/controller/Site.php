<?php
/**
 * 站点管理控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:17
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\Site  as  SiteModel;
class Site extends Base {
    //站点管理-表单结构数据
    //appdal/site/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //站点管理-列表结构数据
    //appdal/site/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //站点管理-列表筛选表单结构数据
    //appdal/site/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //站点管理-新增
    //appdal/site/add
    public function add() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->add($param);
        return json($result);
    }

    //站点管理-编辑
    //appdal/site/edit
    public function edit() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->edit($param);
        return json($result);
    }

    //站点管理-删除
    //appdal/site/del
    public function del() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //站点管理-保存
    //appdal/site/store
    public function store() {
         $param = Request::param();
        $model = new SiteModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //站点管理-导入
    //appdal/site/import
    public function import() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->import($param);
        return json($result);
    }

    //站点管理-获取一条数据
    //appdal/site/getone
   public function getone() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->getone($param);
        return json($result);
    }

    //站点管理-获取全部数据
    //appdal/site/getall
    public function getall() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->getall($param);
        return json($result);
    }

    //站点管理-获取列表分页数据
    //appdal/site/index
    public function index() {
        $param = Request::param();
        $model = new SiteModel;
        $result = $model->getList($param);
        return json($result);
    }

}
