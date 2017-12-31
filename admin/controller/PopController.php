<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/4
 * Time: 19:26
 */

namespace app\controller;


use app\model\BaseModel;

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
} 