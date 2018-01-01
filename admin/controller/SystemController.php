<?php

namespace app\controller;

use app\logic\BaseLogic;
use app\model\SiteConfigModel;
use metacms\web\Form;

/**
 * 系统设置控制器
 * @privilege 系统设置|Admin/System|dfd42e2a-5661-11e7-8c47-14dda97b937d|1
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/6/21
 * Time: 17:12
 */
class SystemController extends UserBaseController
{
    /**
     * 添加配置
     * @privilege 添加配置|Admin/System/addConfig|317a590a-5664-11e7-8c47-14dda97b937d|3
     */
    public function addConfig()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('site_config', 'table');
            $model = new SiteConfigModel();
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail($this->getMessage());
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('site_config', 'table');
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '系统设置' => U('admin/System/index'),
                '添加配置' => ''
            ));
            $this->display('System/addConfig');
        }
    }

    /**
     * 编辑配置
     * @privilege 编辑配置|Admin/System/editConfig|317a590a-7987-11e7-8c47-14dda97b937d|3
     */
    public function editConfig()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('site_config', 'table');
            $model = new SiteConfigModel();
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail($this->getMessage());
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $siteConfigModel = new SiteConfigModel();
            $result = $siteConfigModel->getRecordInfoById($id);
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('site_config', 'table');
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '系统设置' => U('admin/System/index'),
                '编辑配置' => ''
            ));
            $this->display('System/addConfig');
        }
    }


    /**
     * 网站配置
     * @privilege 网站配置|Admin/System/sysConfig|3d22cfea-5673-11e7-8c47-14dda97b937d|2
     */
    public function sysConfig()
    {
        if (IS_POST) {
            $model = new SiteConfigModel();
            $model->beginTransaction();
            try {
                foreach ($_POST as $key => $value) {
                    $orm = $model->orm()->where('name', $key);
                    $result = $model->updateConfig($orm, ['value' => $value]);
                    if (!$result) {
                        throw new \Exception($this->getMessage());
                    }
                }
                $model->commit();
                $this->ajaxSuccess();
            } catch (\Exception $e) {
                $model->rollBack();
                $this->ajaxFail($e->getMessage());
            }
        } else {
            $siteConfigModel = new SiteConfigModel();
            $result = $siteConfigModel->getAllRecord();
            $form_init = [];
            $form_data = [];
            foreach ($result as $value) {
                $form_init[] = [
                    'name' => $value['name'],
                    'title' => $value['description'],
                    'type' => $value['form_type'],
                    'description' => $value['description'],
                ];
                $form_data[$value['name']] = $value['value'];
            }
            Form::getInstance()->form_schema($form_init)->form_data($form_data);
            #面包屑导航
            $this->crumb(array(
                '系统设置' => U('admin/System/index'),
                '网站设置' => ''
            ));
            $this->display('System/sysConfig');
        }
    }

    /**
     * 删除配置
     * @privilege 删除配置|Admin/System/delConfig|627054fe-56ee-11e7-9ea6-14dda97b937d|3
     */
    public function delConfig()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        $model = new SiteConfigModel();
        #验证
        $rule = array(
            'id' => 'required',
        );
        $attr = array(
            'id' => '配置id',
        );
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (!$model->deleteRecordById($id)) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess($this->getMessage());
        }
    }

    /**
     * 配置列表
     * @privilege 配置列表|Admin/System/configList|627054fe-56ee-11dsh-9ea6-14dda97b937d|2
     */
    public function configList()
    {
        $dictionaryLogic = new BaseLogic();
        $list_init = $dictionaryLogic->getListInit('site_config');

        $model = new SiteConfigModel();
        $result = $model->getAllRecord();

        $data['list_data'] = $result;
        $data['list_init'] = $list_init;

        #面包屑导航
        $this->crumb([
            '系统设置' => U('admin/Advertisement/index'),
            '配置列表' => ''
        ]);
        $this->display('System/configList', $data);
    }
}