<?php
/**
 * 文档控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-20
 * Time: 13:28
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\CmsPost  as  CmsPostModel;
class CmsPost extends Base {
    //文档-表单结构数据
    //appdal/cmspost/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //文档-列表结构数据
    //appdal/cmspost/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //文档-列表筛选表单结构数据
    //appdal/cmspost/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }

    //文档-新增
    //appdal/cmspost/add
    public function add() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->add($param);
        return json($result);
    }

    //文档-编辑
    //appdal/cmspost/edit
    public function edit() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->edit($param);
        return json($result);
    }

    //文档-删除
    //appdal/cmspost/del
    public function del() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->del($param);
        return json($result);
    }


    //文档-变更
    //appdal/cmspost/store
    public function store() {
         $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->store($param);
        return json($result);
    }


    //文档-导入
    //appdal/cmspost/import
    public function import() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->import($param);
        return json($result);
    }

    //文档-获取一条数据
    //appdal/cmspost/getone
   public function getone() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->getone($param);
        return json($result);
    }

    //文档-获取全部数据
    //appdal/cmspost/getall
    public function getall() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->getall($param);
        return json($result);
    }

    //文档-获取列表分页数据
    //appdal/cmspost/index
    public function index() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->getList($param);
        return json($result);
    }

    //文档-获取文档标签
    //appdal/cmspost/posttag
    public function posttag() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->posttag($param);
        return json($result);
    }

    //文档-获取上一个下一个文档
    //appdal/cmspost/getPreNext
    public function getPreNext() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->getPreNext($param);
        return json($result);
    }

    //文档-获取关联文档
    //appdal/cmspost/getPreNext
    public function getRelated() {
        $param = Request::param();
        $model = new CmsPostModel;
        $result = $model->getRelated($param);
        return json($result);
    }

}
