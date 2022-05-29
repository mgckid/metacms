<?php
/**
 * 广告控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:24
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\AdvertisementList  as  AdvertisementListModel;
class AdvertisementList extends Base {
    //广告-表单结构数据
    //appdal/advertisementlist/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //广告-列表结构数据
    //appdal/advertisementlist/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //广告-列表筛选表单结构数据
    //appdal/advertisementlist/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //广告-新增
    //appdal/advertisementlist/add
    public function add() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->add($param);
        return json($result);
    }

    //广告-编辑
    //appdal/advertisementlist/edit
    public function edit() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->edit($param);
        return json($result);
    }

    //广告-删除
    //appdal/advertisementlist/del
    public function del() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //广告-保存
    //appdal/advertisementlist/store
    public function store() {
         $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //广告-导入
    //appdal/advertisementlist/import
    public function import() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->import($param);
        return json($result);
    }

    //广告-获取一条数据
    //appdal/advertisementlist/getone
   public function getone() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->getone($param);
        return json($result);
    }

    //广告-获取全部数据
    //appdal/advertisementlist/getall
    public function getall() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->getall($param);
        return json($result);
    }

    //广告-获取列表分页数据
    //appdal/advertisementlist/index
    public function index() {
        $param = Request::param();
        $model = new AdvertisementListModel;
        $result = $model->getList($param);
        return json($result);
    }

}
