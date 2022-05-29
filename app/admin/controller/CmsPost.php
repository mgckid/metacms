<?php

namespace app\admin\controller;

use think\facade\Request;
use think\facade\Filesystem;
use think\facade\App;

/**
 * @access 文档管理|1
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-20
 * Time: 13:28
 */
class  CmsPost extends UserBase {

    /**
     * @access 文档管理|2
     */
    public function index() {
        if (Request::param('list_data')) {
            $res = appRequest('appdal/cmspost/index', Request::param());
            return json(success($res['data'] ?? [], $res['message'] ?? ''));
        } else {
            $res = appRequest('appdal/cmspost/getfilterinit', []);
            $init = $res['data']['record'];
            \Form::getInstance()
                ->input_inline_start()
                ->form_schema($init)
                ->input_submit('<i class="layui-icon"></i> 搜索', ' class="layui-btn layui-btn-primary" lay-submit lay-filter="data-search-btn"', 'class="layui-btn layui-btn-primary"')
                ->input_inline_end()
                ->form_data(Request::param())
                ->form_class(\LayuiForm::form_class_pane);
            $res = appRequest('appdal/cmspost/getlistinit', []);
            $list_init = $res['data']['record'];
            return view('cmspost/index', compact('list_init'));
        }
    }

    /**
     * @access 文档新增|3
     */
    public function add() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmspost/add', Request::param());
            return json($res);
        } else {
            if (Request::param('model_id', '')) {
                $res = appRequest('appdal/cmspost/getforminit', Request::param());
                $init = $res['data']['record'] ?? [];
                #添加文档默认数据
                $form_data['model_id'] = Request::param('model_id','');
                $form_data['category_id'] = Request::param('category_id','');
                $form_data['post_id'] = getItemId();
                $form_data['is_publish'] = 1;
                $form_data['is_recommed'] = 0;
                $form_data['sort'] = 10000;
                $form_data['author'] = '管理员';
                \Form::getInstance()->form_schema($init)->form_data($form_data);
            } else {
                $res = appRequest('appdal/cmsdictionary/getall', ['dict_level' => 1]);
                $init = $res['data']['record'] ?? [];
                $enum = array_column($init, 'dict_name', 'dict_value');
                \Form::getInstance()->form_method(\Form::form_method_get)
                    ->select('选择模型', '', 'model_id', $enum);
            }
            return view('cmspost/add');
        }
    }

    /**
     * @access 文档编辑|3
     */
    public function edit() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmspost/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/cmspost/getone', Request::param());
            $data = $res['data']['record'] ?? [];
            $res = appRequest('appdal/cmspost/getforminit', ['model_id' => $data['model_id']]);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data($data);
            return view('cmspost/add');
        }
    }

    /**
     * @access 文档删除|3
     */
    public function del() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmspost/del', Request::param(),false,[],300);
            return json($res);
        }
    }

    /**
     * @access 文档批量修改|3
     */
    public function batch() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmspost/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/cmspost/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(Request::param());
            return view('cmspost/add');
        }
    }

    /**
     * @access 文档导入|3
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
            $res = appRequest('appdal/cmspost/import', json_encode($data), 'json');
            return json($res);
        } else {
            //\Form::getInstance()->in->form_data(Request::param());
            return view('cmspost/import');
        }
    }

    /**
     * @access 文档导入模板下载|3
     */
    public function template() {
        $res = appRequest('appdal/cmspost/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
            $title[] = ['title' => $v, 'field' => $k];
        }
        excel_export($title, [], '文档导入模板.xlsx', false);
    }

    /**
     * @access 文档导出|3
     */
    public function export() {
        $res = appRequest('appdal/cmspost/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
            $title[] = ['title' => $v, 'field' => $k];
        }
        $res = appRequest('appdal/cmspost/getall', Request::param());
        $data = $res['data']['record'] ?? [];
        excel_export($title, $data, '文档导出.xlsx', false);
    }
}