<?php

namespace app\controller;

use app\logic\BaseLogic;
use app\model\DictionaryAttributeModel;
use app\model\DictionaryTableFieldModel;
use app\model\DictionaryModel;
use app\model\DictionaryTableModel;
use metacms\web\Form;
use metacms\base\Page;

/**
 * 数据管理控制器
 * @privilege 数据管理|Admin/Data|1985b8d0-6166-11e7-ba80-5996e3b2d0fb|1
 * @date 2016年5月4日 21:17:23
 * @author Administrator
 */
class DataController extends UserBaseController
{
    /**
     * 字典管理
     * @privilege 字典管理|Admin/Data/dictionaryManage|4e030640-6166-11e7-ba80-5996e3b2d0fb|2
     */
    public function dictionaryManage()
    {
        #查询列表
        {
            $dictionaryModel = new DictionaryTableModel();
            $count = $dictionaryModel->getRecordList('', '', '', true);
            $result = $dictionaryModel->getRecordList('', 0, $count, false,'dictionary_value','asc');
        }
        #获取列表字段
        $dictionaryLogic = new BaseLogic();
        $list_init = $dictionaryLogic->getListInit($dictionaryModel->getTableName());
        $data = array(
            'list' => $result,
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '数据管理' => U('admin/Cms/index'),
            '字典管理' => ''
        ));
        $this->display('Data/dictionaryManage', $data);
    }

    /**
     * 字典管理
     * @privilege 字段管理|Admin/Data/fieldManage|4e030640-6166-11e7-ba80-5996e3b2d0dd|3
     */
    public function fieldManage()
    {
        $dictionary_id = isset($_REQUEST['dictionary_id']) ? intval($_REQUEST['dictionary_id']) : 0;
        if (empty($dictionary_id)) {
            trigger_error('字典id不能为空');
        }
        #查询列表
        {
            $model = new DictionaryTableFieldModel();
            $condition = ['dictionary_id' => $dictionary_id];
            $orm = $model->orm()->where($condition);
            $count = $model->getRecordList($orm, '', '', true);
            $result = $model->getRecordList($orm, 0, $count, false);
        }
        #获取列表字段
        {
            $logic = new BaseLogic();
            $list_init = $logic->getListInit($model->getTableName());
        }
        #获取字典信息
        {
            $dictionaryModel = new DictionaryTableModel();
            $dictionary_info = $dictionaryModel->getRecordInfoById($dictionary_id);
        }

        $data = array(
            'list' => $result,
            'list_init' => $list_init,
            'dictionary_info' => $dictionary_info
        );
        #面包屑导航
        $this->crumb(array(
            '字典管理' => U('admin/Data/dictionaryManage'),
            $dictionary_info['dictionary_name'] . '(' . $dictionary_info['dictionary_value'] . ')字段管理' => '',
        ));
        $this->display('Data/fieldManage', $data);
    }

    /**
     * 字典管理
     * @privilege 属性管理|Admin/Data/attributeManage|4e030640-6166-11e7-ba80-5996e3b2d0ff|2
     */
    public function attributeManage()
    {
        #查询列表
        {
            $model = new DictionaryAttributeModel();
            $count = $model->getRecordList('', '', '', true);
            $result = $model->getRecordList('', 0, $count, false,'field_value');
        }
        #获取列表字段
        {
            $logic = new BaseLogic();
            $list_init = $logic->getListInit($model->getTableName());
        }
        $data = array(
            'list' => $result,
            'list_init' => $list_init
        );
        #面包屑导航
        $this->crumb(array(
            '字典管理' => U('admin/Data/dictionaryManage'),
            '属性管理' => '',
        ));
        $this->display('Data/attributeManage', $data);
    }


    /**
     * 添加字典
     * @privilege 添加字典|Admin/Data/addDictionary|75b601fb-6189-11e7-ac40-e03f49a02407|3
     */
    public function addDictionary()
    {
        if (IS_POST) {
            $model = new DictionaryTableModel();
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData($model->getTableName(), 'table');
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            #查询记录
            $model = new DictionaryTableModel();
            $logic = new BaseLogic();
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('admin/Data/dictionaryManage'),
                '添加字典' => '',
            ));
            $this->display('Data/addDictionary');
        }
    }

    /**
     * 修改字典
     * @privilege 修改字典|Admin/Data/editDictionary|de8ba38e-95dd-11e7-b91c-e03f49a02407|3
     */
    public function editDictionary()
    {
        if (IS_POST) {
            $model = new DictionaryTableModel();
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
            #查询记录
            $model = new DictionaryTableModel();
            $logic = new BaseLogic();
            $result = $model->getRecordInfoById($id);
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('admin/Data/dictionaryManage'),
                '修改字典' => '',
            ));
            $this->display('Data/addDictionary');
        }
    }

    /**
     * 添加字段
     * @privilege 添加字段|Admin/Data/addField|adf91514-95e1-11e7-b91c-e03f49a02407|3
     */
    public function addField()
    {
        if (IS_POST) {
            $model = new DictionaryTableFieldModel();
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
            $model = new DictionaryTableFieldModel();
            $logic = new BaseLogic();
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            $result['dictionary_id'] = $dictionary_id;
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('admin/Data/dictionaryManage'),
                '添加字段' => '',
            ));
            $this->display('Data/addField');
        }
    }

    /**
     * 修改字段
     * @privilege 修改字段|Admin/Data/editField|bff848a8-95e1-11e7-b91c-e03f49a02407|3
     */
    public function editField()
    {
        if (IS_POST) {
            $model = new DictionaryTableFieldModel();
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
            $model = new DictionaryTableFieldModel();
            $logic = new BaseLogic();
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            $result = $model->getRecordInfoById($id);
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('admin/Data/dictionaryManage'),
                '编辑字段' => '',
            ));
            $this->display('Data/addField');
        }
    }

    /**
     * 添加属性
     * @privilege 添加属性|Admin/Data/addAttribute|58144bf7-961d-11e7-b91c-e03f49a02407|3
     */
    public function addAttribute()
    {
        if (IS_POST) {
            $model = new DictionaryAttributeModel();
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData($model->getTableName(), 'table');
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            #查询记录
            $model = new DictionaryAttributeModel();
            $logic = new BaseLogic();
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('admin/Data/dictionaryManage'),
                '添加属性' => '',
            ));
            $this->display('Data/addAttribute');
        }
    }

    /**
     * 修改属性
     * @privilege 修改属性|Admin/Data/editAttribute|4f73508a-961d-11e7-b91c-e03f49a02407|3
     */
    public function editAttribute()
    {
        if (IS_POST) {
            $model = new DictionaryAttributeModel();
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
            #查询记录
            $model = new DictionaryAttributeModel();
            $logic = new BaseLogic();
            $result = $model->getRecordInfoById($id);
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('admin/Data/dictionaryManage'),
                '修改属性' => '',
            ));
            $this->display('Data/addAttribute');
        }
    }


    /**
     * 删除字典
     * @privilege 删除字典|Admin/Data/delDictionary|2cd3e8b4-774b-11e7-ba80-5996e3b2d0fb|3
     */
    public function delDictionary()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $model = new DictionaryTableModel();
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
            $fieldModel = new DictionaryTableFieldModel();
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
     * @privilege 删除字段|Admin/Data/delField|027c39d9-9623-11e7-b91c-e03f49a02407|3
     */
    public function delField()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $model = new DictionaryTableFieldModel();
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

    /**
     * 删除属性
     * @privilege 删除属性|Admin/Data/delAttribute|efd7e749-9622-11e7-b91c-e03f49a02407|3
     */
    public function delAttribute()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $model = new DictionaryAttributeModel();
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