<?php
/**
 * 友情链接设置控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-01-20
 * Time: 12:49
 */

namespace app\appdal\controller;

use think\facade\Request;
use \app\appdal\model\SiteFlink as SiteFlinkModel;

class SiteFlink extends Base {
    //友情链接设置-表单结构数据
    //appdal/siteflink/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //友情链接设置-列表结构数据
    //appdal/siteflink/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //友情链接设置-列表筛选表单结构数据
    //appdal/siteflink/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }

    //友情链接设置-新增
    //appdal/siteflink/add
    public function add() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->add($param);
        return json($result);
    }

    //友情链接设置-编辑
    //appdal/siteflink/edit
    public function edit() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->edit($param);
        return json($result);
    }

    //友情链接设置-删除
    //appdal/siteflink/del
    public function del() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->del($param);
        return json($result);
    }

    //友情链接设置-导入
    //appdal/siteflink/import
    public function import() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->import($param);
        return json($result);
    }

    //友情链接设置-获取一条数据
    //appdal/siteflink/getone
    public function getone() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->getone($param);
        return json($result);
    }

    //友情链接设置-获取全部数据
    //appdal/siteflink/getall
    public function getall() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->getall($param);
        return json($result);
    }

    //友情链接设置-获取列表分页数据
    //appdal/siteflink/index
    public function index() {
        $param = Request::param();
        $model = new SiteFlinkModel;
        $result = $model->getList($param);
        return json($result);
    }

}
