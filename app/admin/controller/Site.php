<?php

namespace app\admin\controller;

use think\facade\Request;
use think\facade\Filesystem;
use think\facade\App;

/**
 * @access 站点管理|1
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:17
 */
class  Site extends UserBase {

    /**
     * @access 站点管理|2
     */
    public function index() {
        if (Request::param('list_data')) {
            $res = appRequest('appdal/site/index', Request::param());
            return json(success($res['data'] ?? [], $res['message'] ?? ''));
        } else {
            $res = appRequest('appdal/site/getfilterinit', []);
            $init = $res['data']['record'];
            \Form::getInstance()
                ->input_inline_start()
                ->form_schema($init)
                ->input_submit('<i class="layui-icon"></i> 搜索', ' class="layui-btn layui-btn-primary" lay-submit lay-filter="data-search-btn"', 'class="layui-btn layui-btn-primary"')
                ->input_inline_end()
                ->form_data(Request::param())
                ->form_class(\LayuiForm::form_class_pane);
            $res = appRequest('appdal/site/getlistinit', []);
            $list_init = $res['data']['record'];
            return view('site/index', compact('list_init'));
        }
    }

    /**
     * @access 站点新增|3
     */
    public function add() {
        if (Request::isPost()) {
            $res = appRequest('appdal/site/add', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/site/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init);
            return view('site/add');
        }
    }

    /**
     * @access 站点编辑|3
     */
    public function edit() {
        if (Request::isPost()) {
            $res = appRequest('appdal/site/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/site/getforminit', []);
            $init = $res['data']['record'] ?? [];
            $res = appRequest('appdal/site/getone', Request::param());
            $data = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data($data);
            return view('site/add');
        }
    }

    /**
     * @access 站点删除|3
     */
    public function del() {
        if (Request::isPost()) {
            $res = appRequest('appdal/site/del', Request::param());
            return json($res);
        }
    }

    /**
     * @access 站点批量修改|3
     */
    public function batch() {
        if (Request::isPost()) {
            $res = appRequest('appdal/site/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/site/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(Request::param());
            return view('site/add');
        }
    }

    /**
     * @access 站点导入|3
     */
    public function import() {
        if (Request::isPost()) {
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 上传到本地服务器
            $savename = Filesystem::putFile('topic', $file);
            $readfile = dirname(App::getRuntimePath()) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $savename;
            $data = excel_read($readfile);
            unset($data[0]);
            $res = appRequest('appdal/site/import', json_encode($data), 'json');
            return json($res);
        } else {
            //\Form::getInstance()->in->form_data(Request::param());
            return view('site/import');
        }
    }

    /**
     * @access 站点导入模板下载|3
     */
    public function template() {
        $res = appRequest('appdal/site/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
                $title[] = ['title' => $v, 'field' => $k];
        }
        excel_export($title, [], '站点导入模板.xlsx', false);
    }

    /**
     * @access 站点导出|3
     */
    public function export() {
        $res = appRequest('appdal/site/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            $title[] = ['title' => $v, 'field' => $k];
        }
        $res = appRequest('appdal/site/getall', Request::param());
        $data = $res['data']['record'] ?? [];
        excel_export($title, $data, '站点导出.xlsx', false);
    }

    /**
     * @access 添加扩展配置字典|3
     */
    public function addSiteConfig() {
        if (Request::isPost()) {
            $param = Request::param('site_config');
            $new_id = array_column($param, 'id');

            $result = appRequest('appdal/siteConfig/getall', 'get');
            $form_data = $result['data']['record'] ?? [];
            $old_id = array_column($form_data, 'id');

            $del_id = array_diff($old_id, $new_id);
            if ($del_id) {
                appRequest('appdal/siteConfig/del', ['id' => $del_id]);
            }
            $res = appRequest('appdal/siteConfig/store', $param, true);
            return json($res);
        } else {
            $result = appRequest('appdal/siteConfig/getall', 'get');
            $form_data = $result['data']['record'] ?? [];
            $result = appRequest('appdal/siteConfig/getFormInit', 'get');
            $form_init = array_column($result['data']['record'] ?? [], null, 'name');
            unset($form_init['value']);
            #表单初始化数据
            \Form::getInstance()->table('扩展配置', '', 'site_config', $form_init, $form_data);
            return view('site/addSiteConfig');
        }
    }

    /**
     * @access 添加扩展配置字典|3
     */
    public function editSiteConfig() {
        if (Request::isPost()) {
            $param = Request::param();
            $result = appRequest('appdal/siteConfig/getall', 'get');
            $res = $result['data']['record'] ?? [];
            $res = array_column($res, null, 'name');
            $add_data = [];
            foreach ($param as $name => $value) {
                $add_data[] = [
                    'id' => isset($res[$name]) ? $res[$name]['id'] : '',
                    'value' => $param[$name] ?? '',
                ];
            }
            $res = appRequest('appdal/siteConfig/edit', ['site_config' => $add_data]);
            return json($res);
        } else {
            $result = appRequest('appdal/siteConfig/getall', 'get');
            $res = $result['data']['record'] ?? [];
            $form_init = $form_data = [];
            foreach ($res as $value) {
                $form_init[] = array(
                    'title' => $value['description'],
                    'name' => $value['name'],
                    'description' => $value['description'],
                    'type' => $value['input_type'],
                );
                $form_data[$value['name']] = $value['value'];
            }
            #表单初始化数据
            \Form::getInstance()->form_init($form_init)->form_data($form_data);
            return view('site/add');
        }
    }
}