<?php
/**
 * 站点配置控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:15
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\SiteConfig  as  SiteConfigModel;
class SiteConfig extends Base {
    //站点配置-表单结构数据
    //appdal/siteconfig/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //站点配置-列表结构数据
    //appdal/siteconfig/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //站点配置-列表筛选表单结构数据
    //appdal/siteconfig/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //站点配置-新增
    //appdal/siteconfig/add
    public function add() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->add($param);
        return json($result);
    }

    //站点配置-编辑
    //appdal/siteconfig/edit
    public function edit() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->edit($param['site_config'] ?? []);
        return json($result);
    }

    //站点配置-删除
    //appdal/siteconfig/del
    public function del() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //站点配置-保存
    //appdal/siteconfig/store
    public function store() {
         $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //站点配置-导入
    //appdal/siteconfig/import
    public function import() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->import($param);
        return json($result);
    }

    //站点配置-获取一条数据
    //appdal/siteconfig/getone
   public function getone() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->getone($param);
        return json($result);
    }

    //站点配置-获取全部数据
    //appdal/siteconfig/getall
    public function getall() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->getall($param);
        return json($result);
    }

    //站点配置-获取列表分页数据
    //appdal/siteconfig/index
    public function index() {
        $param = Request::param();
        $model = new SiteConfigModel;
        $result = $model->getList($param);
        return json($result);
    }

}
