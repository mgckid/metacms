<?php
namespace app\admin\controller;
use think\facade\Request;
use think\facade\Filesystem;
use think\facade\App;

/**
 * @access 内容模型管理|1
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-22
 * Time: 23:35
 */
class  CmsDictionary extends UserBase {
    
/**
	 * @access 内容模型管理|2
	 */
    public function index() {
        if (Request::param('list_data')) {
            $res = appRequest('appdal/cmsdictionary/getall', Request::param());
            return json(success($res['data'] ?? [], $res['message'] ?? ''));
        } else {
            $res = appRequest('appdal/cmsdictionary/getfilterinit', []);
            $init = $res['data']['record'];
               \Form::getInstance()
                ->input_inline_start()
                ->form_schema($init)
                ->input_submit('<i class="layui-icon"></i> 搜索',' class="layui-btn layui-btn-primary" lay-submit lay-filter="data-search-btn"','class="layui-btn layui-btn-primary"')
                ->input_inline_end()
                ->form_data(Request::param())
                ->form_class(\LayuiForm::form_class_pane);
            $res = appRequest('appdal/cmsdictionary/getlistinit', []);
            $list_init = $res['data']['record'];
            return view('cmsdictionary/index', compact('list_init'));
        }
    }
     
/**
	 * @access 内容模型新增|3
	 */
    public function add() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmsdictionary/add', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/cmsdictionary/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(Request::param());
            return view('cmsdictionary/add');
        }
    }
     
/**
	 * @access 内容模型编辑|3
	 */    
    public function edit() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmsdictionary/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/cmsdictionary/getforminit', []);
            $init = $res['data']['record'] ?? [];
            $res = appRequest('appdal/cmsdictionary/getone', Request::param());
            $data = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data($data);
            return view('cmsdictionary/add');
        }
    }
     
    /**
	 * @access 内容模型删除|3
	 */
    public function del() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmsdictionary/del', Request::param());
            return json($res);
        }
    }
     
    /**
	 * @access 内容模型批量修改|3
	 */    
    public function batch() {
        if (Request::isPost()) {
            $res = appRequest('appdal/cmsdictionary/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/cmsdictionary/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(Request::param());
            return view('cmsdictionary/add');
        }
    }
     
    /**
	 * @access 内容模型导入|3
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
            $res = appRequest('appdal/cmsdictionary/import', json_encode($data), 'json');
            return json($res);
        } else {
            //\Form::getInstance()->in->form_data(Request::param());
            return view('cmsdictionary/import');
        }
    }
     
    /**
	 * @access 内容模型导入模板下载|3
	 */    
    public function template() {
        $res = appRequest('appdal/cmsdictionary/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
            $title[] = ['title' => $v, 'field' => $k];
        }
        excel_export($title, [], '内容模型导入模板.xlsx', false);
    }
     
    /**
	 * @access 内容模型导出|3
	 */   
    public function export() {
        $res = appRequest('appdal/cmsdictionary/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
            $title[] = ['title' => $v, 'field' => $k];
        }
        $res = appRequest('appdal/cmsdictionary/getall', Request::param());
        $data = $res['data']['record'] ?? [];
        excel_export($title, $data, '内容模型导出.xlsx', false);
    }

    /**
     * @access 修改规则|3
     */
    public function editrule() {
        if (Request::isPost()) {
            $res = appRequest('appdal/dictionaryRule/store', Request::param('rule', []), true);
            return json($res);
        } else {
            $res = appRequest('appdal/dictionaryRule/getforminit', []);
            $init = $res['data']['record'] ?? [];
            $res = appRequest('appdal/cmsDictionary/getall', ['pid' => Request::param('id', '')]);
            $res = $res['data']['record'] ?? [];
            $res = appRequest('appdal/dictionaryRule/getall', ['dict_code'=>array_column($res,'dict_code')]);
            $data = $res['data']['record'] ?? [];
            \Form::getInstance()->table('字段规则', '', 'rule', $init, $data);
            return view('cmsdictionary/add');
        }
    }
}