<?php
/**
 * 留言控制器
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:18
 */
namespace app\appdal\controller;
use think\facade\Request;
use \app\appdal\model\Feedback  as  FeedbackModel;
class Feedback extends Base {
    //留言-表单结构数据
    //appdal/feedback/getforminit
    public function getFormInit() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->getforminit($param);
        return json($result);
    }

    //留言-列表结构数据
    //appdal/feedback/getListinit
    public function getListInit() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->getListInit($param);
        return json($result);
    }

    //留言-列表筛选表单结构数据
    //appdal/feedback/getfilterInit
    public function getFilterInit() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->getfilterInit($param);
        return json($result);
    }
 
    //留言-新增
    //appdal/feedback/add
    public function add() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->add($param);
        return json($result);
    }

    //留言-编辑
    //appdal/feedback/edit
    public function edit() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->edit($param);
        return json($result);
    }

    //留言-删除
    //appdal/feedback/del
    public function del() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->del($param);
        return json($result);
    }

    
    //留言-保存
    //appdal/feedback/store
    public function store() {
         $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->store($param);
        return json($result);
    }

    
    //留言-导入
    //appdal/feedback/import
    public function import() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->import($param);
        return json($result);
    }

    //留言-获取一条数据
    //appdal/feedback/getone
   public function getone() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->getone($param);
        return json($result);
    }

    //留言-获取全部数据
    //appdal/feedback/getall
    public function getall() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->getall($param);
        return json($result);
    }

    //留言-获取列表分页数据
    //appdal/feedback/index
    public function index() {
        $param = Request::param();
        $model = new FeedbackModel;
        $result = $model->getList($param);
        return json($result);
    }

}
