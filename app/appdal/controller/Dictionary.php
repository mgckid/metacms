<?php
/**
 * 字典管理控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-18
 * Time: 00:00
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\Dictionary  as  DictionaryModel;
class Dictionary extends Base {
    //字典管理-表单结构数据
    //appdal/dictionary/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //字典管理-列表结构数据
    //appdal/dictionary/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //字典管理-列表筛选表单结构数据
    //appdal/dictionary/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //字典管理-新增
    //appdal/dictionary/add
    public function add() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->add($param);
        return json($result);
    }

    //字典管理-编辑
    //appdal/dictionary/edit
    public function edit() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->edit($param);
        return json($result);
    }

    //字典管理-删除
    //appdal/dictionary/del
    public function del() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //字典管理-变更
    //appdal/dictionary/store
    public function store() {
         $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //字典管理-导入
    //appdal/dictionary/import
    public function import() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->import($param);
        return json($result);
    }

    //字典管理-获取一条数据
    //appdal/dictionary/getone
   public function getone() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->getone($param);
        return json($result);
    }

    //字典管理-获取全部数据
    //appdal/dictionary/getall
    public function getall() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->getall($param);
        return json($result);
    }

    //字典管理-获取列表分页数据
    //appdal/dictionary/index
    public function index() {
        $param = Request::param();
        $model = new DictionaryModel;
        $result = $model->getList($param);
        return json($result);
    }

}
