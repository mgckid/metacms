<?php
namespace app\admin\controller;
use think\facade\Request;
use think\facade\Filesystem;
use think\facade\App;

/**
 * @access 栏目管理|1
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-18
 * Time: 00:10
 */
class  CmsCategory extends UserBase {
    
/**
	 * @access 栏目管理|2
	 */
    public function index() {
        if (Request::param('list_data')) {
            $res = appRequest('appdal/cmscategory/index', Request::param());
            return json(success($res['data'] ?? [], $res['message'] ?? ''));
        } else {
            $res = appRequest('appdal/cmscategory/getfilterinit', []);
            $init = $res['data']['record'];
               \Form::getInstance()
                ->input_inline_start()
                ->form_schema($init)
                ->input_submit('<i class="layui-icon"></i> 搜索',' class="layui-btn layui-btn-primary" lay-submit lay-filter="data-search-btn"','class="layui-btn layui-btn-primary"')
                ->input_inline_end()
                ->form_data(Request::param())
                ->form_class(\LayuiForm::form_class_pane);
            $res = appRequest('appdal/cmscategory/getlistinit', []);
            $list_init = $res['data']['record'];
            return view('cmscategory/index', compact('list_init'));
        }
    }
     
/**
	 * @access 栏目新增|3
	 */
    public function add() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmscategory/add', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/cmscategory/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init);
            return view('cmscategory/add');
        }
    }
     
/**
	 * @access 栏目编辑|3
	 */    
    public function edit() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmscategory/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/cmscategory/getforminit', []);
            $init = $res['data']['record'] ?? [];
            $res = appRequest('appdal/cmscategory/getone', Request::param());
            $data = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data($data);
            return view('cmscategory/add');
        }
    }
     
/**
	 * @access 栏目删除|3
	 */
    public function del() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmscategory/del', Request::param());
            return json($res);
        }
    }
     
/**
	 * @access 栏目批量修改|3
	 */    
    public function batch() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmscategory/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/cmscategory/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(Request::param());
            return view('cmscategory/add');
        }
    }
     
/**
	 * @access 栏目导入|3
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
            $res = appRequest('appdal/cmscategory/import', json_encode($data), 'json');
            return json($res);
        } else {
            //\Form::getInstance()->in->form_data(Request::param());
            return view('cmscategory/import');
        }
    }
     
/**
	 * @access 栏目导入模板下载|3
	 */    
    public function template() {
        $res = appRequest('appdal/cmscategory/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
            $title[] = ['title' => $v, 'field' => $k];
        }
        excel_export($title, [], '栏目导入模板.xlsx', false);
    }
     
/**
	 * @access 栏目导出|3
	 */   
    public function export() {
        $res = appRequest('appdal/cmscategory/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
            $title[] = ['title' => $v, 'field' => $k];
        }
        $res = appRequest('appdal/cmscategory/getall', Request::param());
        $data = $res['data']['record'] ?? [];
        excel_export($title, $data, '栏目导出.xlsx', false);
    }
}