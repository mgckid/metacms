<?php
/**
 * 字典规则控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-20
 * Time: 13:47
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\DictionaryRule  as  DictionaryRuleModel;
class DictionaryRule extends Base {
    //字典规则-表单结构数据
    //appdal/dictionaryrule/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //字典规则-列表结构数据
    //appdal/dictionaryrule/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //字典规则-列表筛选表单结构数据
    //appdal/dictionaryrule/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //字典规则-新增
    //appdal/dictionaryrule/add
    public function add() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->add($param);
        return json($result);
    }

    //字典规则-编辑
    //appdal/dictionaryrule/edit
    public function edit() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->edit($param);
        return json($result);
    }

    //字典规则-删除
    //appdal/dictionaryrule/del
    public function del() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //字典规则-变更
    //appdal/dictionaryrule/store
    public function store() {
         $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //字典规则-导入
    //appdal/dictionaryrule/import
    public function import() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->import($param);
        return json($result);
    }

    //字典规则-获取一条数据
    //appdal/dictionaryrule/getone
   public function getone() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->getone($param);
        return json($result);
    }

    //字典规则-获取全部数据
    //appdal/dictionaryrule/getall
    public function getall() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->getall($param);
        return json($result);
    }

    //字典规则-获取列表分页数据
    //appdal/dictionaryrule/index
    public function index() {
        $param = Request::param();
        $model = new DictionaryRuleModel;
        $result = $model->getList($param);
        return json($result);
    }

}
