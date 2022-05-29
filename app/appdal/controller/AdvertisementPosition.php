<?php
/**
 * 广告位控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:24
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\AdvertisementPosition  as  AdvertisementPositionModel;
class AdvertisementPosition extends Base {
    //广告位-表单结构数据
    //appdal/advertisementposition/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //广告位-列表结构数据
    //appdal/advertisementposition/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //广告位-列表筛选表单结构数据
    //appdal/advertisementposition/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //广告位-新增
    //appdal/advertisementposition/add
    public function add() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->add($param);
        return json($result);
    }

    //广告位-编辑
    //appdal/advertisementposition/edit
    public function edit() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->edit($param);
        return json($result);
    }

    //广告位-删除
    //appdal/advertisementposition/del
    public function del() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //广告位-保存
    //appdal/advertisementposition/store
    public function store() {
         $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //广告位-导入
    //appdal/advertisementposition/import
    public function import() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->import($param);
        return json($result);
    }

    //广告位-获取一条数据
    //appdal/advertisementposition/getone
   public function getone() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->getone($param);
        return json($result);
    }

    //广告位-获取全部数据
    //appdal/advertisementposition/getall
    public function getall() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->getall($param);
        return json($result);
    }

    //广告位-获取列表分页数据
    //appdal/advertisementposition/index
    public function index() {
        $param = Request::param();
        $model = new AdvertisementPositionModel;
        $result = $model->getList($param);
        return json($result);
    }

}
