<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/9/4
 * Time: 19:26
 */

namespace app\controller;


use app\model\BaseModel;
use app\model\CollectSnapModel;
use metacms\base\Page;

/**
 * 弹出层控制器
 * @privilege 弹出层管理|Admin/Pop|e902296d-2006-11e7-8ad5-9cb3ab404099|3
 * @date 2016年5月4日 21:17:23
 * @author Administrator
 */
class PopController extends UserBaseController
{
    /**
     * 异步处理插件
     * @privilege 异步处理插件|Admin/Pop/index|f7effdf6-775f-11e7-ba80-5996e3b2d0ff|3
     */
    public function index()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        #验证
        $rule = array(
            'model_name' => 'required',
            'method_name' => 'required',
            'param' => 'array',

        );
        $attr = array(
            'model_name' => '模块名',
            'method_name' => '方法名',
            'param' => '参数',
        );
        $model = new BaseModel();
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        $model_name = 'app\\logic\\' . ucfirst($_REQUEST['model_name']);
        $methid_name = $_REQUEST['method_name'];
        $param = isset($_REQUEST['param']) ? $_REQUEST['param'] : [];
        if (!class_exists($model_name)) {
            $this->ajaxFail('模块不存在');
        }
        if (!method_exists($model_name, $methid_name)) {
            $this->ajaxFail('方法不存在');
        }
        $result = call_user_func_array([new $model_name, $methid_name], $param);
        if (!$result) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess('执行成功', $result);
        }
    }

    /**
     * 添加采集记录
     * @privilege 添加采集记录|Admin/Pop/addCollectSnap|2d086fe7-280c-11e8-95a7-fcaa14d9feb4|3
     */
    public function addCollectSnap()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $data = $_POST;
        foreach ($data as $key =>  $value){
            $data[$key] = mb_convert_encoding($value,'utf-8');
        }
        $title = $data['title'];
        $collectSnapModel = new CollectSnapModel();
        $orm = $collectSnapModel->orm()->where('title', $title);
        $r = $collectSnapModel->getAllRecord($orm);
        if (!empty($r)) {
            $this->ajaxFail('该条数据已存在');
        }
        $result = $collectSnapModel->addRecord($_POST);
        if (!$result) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess('执行成功', $result);
        }
    }

    /**
     * 修改采集记录
     * @privilege 修改采集记录|Admin/Pop/editCollectSnap|2e3a5733-2833-11e8-9443-00163e003500|3
     */
    public function editCollectSnap()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $data = $_POST;
        foreach ($data as $key => $value) {
            $data[$key] = mb_convert_encoding($value, 'utf-8');
        }
        if (isset($data['html_content'])) {
            $data['html_content'] = htmlspecialchars($data['html_content']);
        }
        $collectSnapModel = new CollectSnapModel();
        $result = $collectSnapModel->addRecord($data);
        if (!$result) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess('执行成功', $result);
        }
    }

    /**
     * 获取采集记录
     * @privilege 获取采集记录|Admin/Pop/getCollectSnap|4d30e533-2820-11e8-9443-00163e003500|3
     */
    public function getCollectSnap()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        #验证
        $rule = array(
            'spider_name' => 'required',
            'collect_status' => 'required',
            'collect_status' => 'in:-10,0,10,15,20',

        );
        $attr = array(
            'spider_name' => '爬虫脚本名称',
            'collect_status' => '采集状态',
        );
        $collectSnapModel = new CollectSnapModel();
        $validate = $collectSnapModel->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        $p = isset($_POST['p']) ? intval($_POST['p']) : 1;
        $page_size = 50;
        $orm = $collectSnapModel->orm()->where('spider_name', $_POST['spider_name'])->where('collect_status', $_POST['collect_status']);
        $count = $collectSnapModel->getRecordList($orm, '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $collectSnapModel->getRecordList($orm, $page->getOffset(), $page->getPageSize(), false);
        $data = [
            'count' => $count,
            'list' => $result,
            'page_size' => $page_size,
            'max_p' => ceil($count / $page_size),
        ];
        $this->ajaxSuccess('获取成功', $data);
    }


} 