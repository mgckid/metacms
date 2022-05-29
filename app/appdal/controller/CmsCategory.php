<?php
/**
 * 栏目控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-01-26
 * Time: 21:58
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\CmsCategory  as  CmsCategoryModel;
class CmsCategory extends Base {
    //栏目-表单结构数据
    //appdal/cmscategory/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //栏目-列表结构数据
    //appdal/cmscategory/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //栏目-列表筛选表单结构数据
    //appdal/cmscategory/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //栏目-新增
    //appdal/cmscategory/add
    public function add() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->add($param);
        return json($result);
    }

    //栏目-编辑
    //appdal/cmscategory/edit
    public function edit() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->edit($param);
        return json($result);
    }

    //栏目-删除
    //appdal/cmscategory/del
    public function del() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->del($param);
        return json($result);
    }

    //栏目-导入
    //appdal/cmscategory/import
    public function import() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->import($param);
        return json($result);
    }

    //栏目-获取一条数据
    //appdal/cmscategory/getone
   public function getone() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->getone($param);
        return json($result);
    }

    //栏目-获取全部数据
    //appdal/cmscategory/getall
    public function getall() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->getall($param);
        return json($result);
    }

    //栏目-获取列表分页数据
    //appdal/cmscategory/index
    public function index() {
        $param = Request::param();
        $model = new CmsCategoryModel;
        $result = $model->getList($param);
        return json($result);
    }

}
