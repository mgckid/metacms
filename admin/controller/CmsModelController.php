<?php


namespace app\controller;

use app\model\BaseModel;
use app\model\CmsAttributeModel;
use app\model\CmsFieldModel;
use app\model\CmsModelModel;
use app\model\DictionaryModel;
use app\logic\BaseLogic;
use app\model\DictionaryModelFieldModel;
use app\model\DictionaryModelModel;
use metacms\web\Form;
use metacms\base\Page;

/**
 * 内容模型控制器
 * @privilege 内容模型|Admin/CmsModel|bd1327d8-6bc1-11e7-ab90-e03f49a02407|1
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 22:01
 */
class CmsModelController extends UserBaseController
{

    /**
     * 模型管理
     * @privilege 模型管理|Admin/CmsModel/modelManage|8a7c80a9-6c2c-11e7-ba80-5996e3b2d0fb|2
     */
    public function modelManage()
    {
        $dictionaryLogic = new BaseLogic();
        $model = new DictionaryModelModel();
        $list_init = $dictionaryLogic->getListInit($model->getTableName());
        #查询列表
        {
            $count = $model->getRecordList('', '', '', true);
            $result = $model->getRecordList('', 0, $count, false,'id','asc');
        }
        $data = array(
            'list' => $result,
            'list_init' => $list_init
        );
        #面包屑导航
        $this->crumb(array(
            '内容模型' => U('admin/CmsModel/modelManage'),
            '模型管理' => ''
        ));
        $this->display('CmsModel/modelManage', $data);

    }

    /**
     * 字段管理
     * @privilege 字段管理|Admin/CmsModel/fieldManage|986d8a01-6c2c-11e7-ba80-5996e3b2d0fb|3
     */
    public function fieldManage()
    {
        $dictionary_id = isset($_REQUEST['dictionary_id']) ? intval($_REQUEST['dictionary_id']) : 0;
        if (empty($dictionary_id)) {
            trigger_error('字典id不能为空');
        }
        $dictionary_model = new DictionaryModelModel();
        $all_model_result = $dictionary_model->getAllRecord();
        $pmodel =  getParents($all_model_result,$dictionary_id);
        $dictionary_ids = array_column($pmodel,'id');
        #查询列表
        {
            $model = new DictionaryModelFieldModel();
            $orm = $model->orm()->where_in('dictionary_id',$dictionary_ids);
            $count = $model->getRecordList($orm, '', '', true);
            $result = $model->getRecordList($orm, 0, $count, false,'id','asc');
        }
        #获取列表字段
        {
            $logic = new BaseLogic();
            $list_init = $logic->getListInit($model->getTableName());
        }
        #获取字典信息
        {
            $dictionaryModel = new DictionaryModelModel();
            $dictionary_info = $dictionaryModel->getRecordInfoById($dictionary_id);
        }

        $data = array(
            'list' => $result,
            'list_init' => $list_init,
            'dictionary_info' => $dictionary_info
        );
        #面包屑导航
        $this->crumb(array(
            '字典管理' => U('admin/CmsModel/modelManage'),
            $dictionary_info['dictionary_name'] . '(' . $dictionary_info['dictionary_value'] . ')字段管理' => '',
        ));
        $this->display('CmsModel/fieldManage', $data);
    }


    /**
     * 添加内容模型
     * @privilege 添加内容模型|Admin/CmsModel/addModel|e8d680dd-6c2c-11e7-ba80-5996e3b2d0fb|3
     */
    public function addModel()
    {
        if (IS_POST) {
            $model = new DictionaryModelModel();
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData($model->getTableName(), 'table');
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $model = new DictionaryModelModel();
            $logic = new BaseLogic();
            #初始化表单
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            #获取全部模型记录
            $model_result = $model->getAllRecord('');
            $list = treeStructForLevel($model_result);
            foreach ($list as $value) {
                $form_init['pid']['enum'][] = [
                    'value' => $value['id'],
                    'name' => $value['placeHolder'] . $value['dictionary_value'].'('.$value['dictionary_name'].')',
                ];
            }
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '模型管理' => U('admin/CmsModel/modelManage'),
                '添加内容模型' => '',
            ));
            $this->display('CmsModel/addModel');
        }
    }

    /**
     * 修改内容模型
     * @privilege 修改内容模型|Admin/CmsModel/editModel|f7e9d801-9639-11e7-b91c-e03f49a02407|3
     */
    public function editModel()
    {
        if (IS_POST) {
            $model = new DictionaryModelModel();
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData($model->getTableName(), 'table');
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if (!$id) {
                trigger_error('模型id不能为空');
            }
            $model = new DictionaryModelModel();
            $logic = new BaseLogic();
            #查询数据
            $result = $model->getRecordInfoById($id);
            #初始化表单
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            #获取全部模型记录
            $model_result = $model->getAllRecord('');
            $list = treeStructForLevel($model_result);
            foreach ($list as $value) {
                $form_init['pid']['enum'][] = [
                    'value' => $value['id'],
                    'name' => $value['placeHolder'] . $value['dictionary_value'].'('.$value['dictionary_name'].')',
                ];
            }
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '模型管理' => U('admin/CmsModel/modelManage'),
                '修改内容模型' => '',
            ));
            $this->display('CmsModel/addModel');
        }
    }


    /**
     * 添加模型字段
     * @privilege 添加模型字段|Admin/CmsModel/addField|62e7961a-7290-11e7-ba80-5996e3b2d0fb|3
     */
    public function addField()
    {
        if (IS_POST) {
            $model = new DictionaryModelFieldModel();
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData($model->getTableName(), 'table');
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $dictionary_id = isset($_REQUEST['dictionary_id']) ? intval($_REQUEST['dictionary_id']) : 0;
            if (empty($dictionary_id)) {
                trigger_error('字典id不能为空');
            }
            #表单初始化
            $model = new DictionaryModelFieldModel();
            $logic = new BaseLogic();
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            $result['dictionary_id'] = $dictionary_id;
            #获取全部模型记录
            $dictionaryModel = new DictionaryModelModel();
            $model_result = $dictionaryModel->getAllRecord('');
            $list = treeStructForLevel($model_result);
            foreach ($list as $value) {
                $form_init['dictionary_id']['enum'][] = [
                    'value' => $value['id'],
                    'name' => $value['placeHolder'] . $value['dictionary_value'].'('.$value['dictionary_name'].')',
                ];
            }
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #获取字典信息
            {
                $dictionary_info = $dictionaryModel->getRecordInfoById($dictionary_id);
            }
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('admin/CmsModel/modelManage'),
                $dictionary_info['dictionary_name'] . '(' . $dictionary_info['dictionary_value'] . ')添加字段' => '',
            ));
            $this->display('CmsModel/addField');
        }
    }

    /**
     * 修改字段
     * @privilege 修改字段|Admin/CmsModel/editField|080a60fb-9646-11e7-b91c-e03f49a02407|3
     */
    public function editField()
    {
        if (IS_POST) {
            $model = new DictionaryModelFieldModel();
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData($model->getTableName(), 'table');
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
            if (empty($id)) {
                trigger_error('字段id不能为空');
            }
            #表单初始化
            $model = new DictionaryModelFieldModel();
            $logic = new BaseLogic();
            $result = $model->getRecordInfoById($id);
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            #获取全部模型记录
            $dictionaryModel = new DictionaryModelModel();
            $model_result = $dictionaryModel->getAllRecord('');
            $list = treeStructForLevel($model_result);
            foreach ($list as $value) {
                $form_init['dictionary_id']['enum'][] = [
                    'value' => $value['id'],
                    'name' => $value['placeHolder'] . $value['dictionary_value'].'('.$value['dictionary_name'].')',
                ];
            }
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #获取字典信息
            {
                $dictionary_info = $dictionaryModel->getRecordInfoById($result['dictionary_id']);
            }
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('admin/CmsModel/modelManage'),
                $dictionary_info['dictionary_name'] . '(' . $dictionary_info['dictionary_value'] . ')编辑字段' => '',
            ));
            $this->display('CmsModel/addField');
        }
    }


    /**
     * 删除字典
     * @privilege 删除字典|Admin/CmsModel/delDictionary|b53ee342-774d-11e7-ba80-5996e3b2d0fb|3
     */
    public function delDictionary()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $model = new DictionaryModelModel();
        #验证
        $rule = array(
            'id' => 'required|integer',
        );
        $attr = array(
            'id' => '字典id',
        );
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $model->beginTransaction();
        #删除记录
        try {
            $result = $model->deleteRecordById($id);
            if (!$result) {
                throw new \Exception('删除字典失败');
            }
            $fieldModel = new DictionaryModelFieldModel();
            $orm = $fieldModel->orm()->where('dictionary_id', $id);
            $del_result = $fieldModel->delRecord($orm);
            if (!$del_result) {
                throw new \Exception('删除字段失败');
            }
            $model->commit();
            $this->ajaxSuccess('删除成功');
        } catch (\Exception $ex) {
            $model->rollBack();
            $this->ajaxFail($ex->getMessage());
        }
    }

    /**
     * 删除字段
     * @privilege 删除字段|Admin/CmsModel/delField|b56ac400-9648-11e7-b91c-e03f49a02407|3
     */
    public function delField()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $model = new DictionaryModelFieldModel();
        #验证
        $rule = array(
            'id' => 'required|integer',
        );
        $attr = array(
            'id' => '字段id',
        );
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $result = $model->deleteRecordById($id);
        if (!$result) {
            $this->ajaxFail('删除失败');
        } else {
            $this->ajaxSuccess('删除成功');
        }
    }


}