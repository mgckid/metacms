<?php
/**
 * 内容模型管理控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-22
 * Time: 23:35
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\CmsDictionary  as  CmsDictionaryModel;
class CmsDictionary extends Base {
    //内容模型管理-表单结构数据
    //appdal/cmsdictionary/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //内容模型管理-列表结构数据
    //appdal/cmsdictionary/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //内容模型管理-列表筛选表单结构数据
    //appdal/cmsdictionary/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //内容模型管理-新增
    //appdal/cmsdictionary/add
    public function add() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->add($param);
        return json($result);
    }

    //内容模型管理-编辑
    //appdal/cmsdictionary/edit
    public function edit() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->edit($param);
        return json($result);
    }

    //内容模型管理-删除
    //appdal/cmsdictionary/del
    public function del() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //内容模型管理-保存
    //appdal/cmsdictionary/store
    public function store() {
         $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //内容模型管理-导入
    //appdal/cmsdictionary/import
    public function import() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->import($param);
        return json($result);
    }

    //内容模型管理-获取一条数据
    //appdal/cmsdictionary/getone
   public function getone() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->getone($param);
        return json($result);
    }

    //内容模型管理-获取全部数据
    //appdal/cmsdictionary/getall
    public function getall() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->getall($param);
        return json($result);
    }

    //内容模型管理-获取列表分页数据
    //appdal/cmsdictionary/index
    public function index() {
        $param = Request::param();
        $model = new CmsDictionaryModel;
        $result = $model->getList($param);
        return json($result);
    }

}
