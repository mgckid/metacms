<?php
namespace app\admin\controller;
use think\facade\Request;
use think\facade\Filesystem;
use think\facade\App;

/**
 * @access 广告位管理|1
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:24
 */
class  AdvertisementPosition extends UserBase {
    
/**
	 * @access 广告位管理|2
	 */
    public function index() {
        if (Request::param('list_data')) {
            $res = appRequest('appdal/advertisementposition/index', Request::param());
            return json(success($res['data'] ?? [], $res['message'] ?? ''));
        } else {
            $res = appRequest('appdal/advertisementposition/getfilterinit', []);
            $init = $res['data']['record'];
               \Form::getInstance()
                ->input_inline_start()
                ->form_schema($init)
                ->input_submit('<i class="layui-icon"></i> 搜索',' class="layui-btn layui-btn-primary" lay-submit lay-filter="data-search-btn"','class="layui-btn layui-btn-primary"')
                ->input_inline_end()
                ->form_data(Request::param())
                ->form_class(\LayuiForm::form_class_pane);
            $res = appRequest('appdal/advertisementposition/getlistinit', []);
            $list_init = $res['data']['record'];
            return view('advertisementposition/index', compact('list_init'));
        }
    }
     
/**
	 * @access 广告位新增|3
	 */
    public function add() {
        if (Request::isPost()) {
            $res = appRequest('appdal/advertisementposition/add', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/advertisementposition/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init);
            return view('advertisementposition/add');
        }
    }
     
/**
	 * @access 广告位编辑|3
	 */    
    public function edit() {
        if (Request::isPost()) {
            $res = appRequest('appdal/advertisementposition/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/advertisementposition/getforminit', []);
            $init = $res['data']['record'] ?? [];
            $res = appRequest('appdal/advertisementposition/getone', Request::param());
            $data = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data($data);
            return view('advertisementposition/add');
        }
    }
     
/**
	 * @access 广告位删除|3
	 */
    public function del() {
        if (Request::isPost()) {
            $res = appRequest('appdal/advertisementposition/del', Request::param());
            return json($res);
        }
    }
     
/**
	 * @access 广告位批量修改|3
	 */    
    public function batch() {
        if (Request::isPost()) {
            $res = appRequest('appdal/advertisementposition/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/advertisementposition/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(Request::param());
            return view('advertisementposition/add');
        }
    }
     
/**
	 * @access 广告位导入|3
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
            $res = appRequest('appdal/advertisementposition/import', json_encode($data), 'json');
            return json($res);
        } else {
            //\Form::getInstance()->in->form_data(Request::param());
            return view('advertisementposition/import');
        }
    }
     
/**
	 * @access 广告位导入模板下载|3
	 */    
    public function template() {
        $res = appRequest('appdal/advertisementposition/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
            $title[] = ['title' => $v, 'field' => $k];
        }
        excel_export($title, [], '广告位导入模板.xlsx', false);
    }
     
/**
	 * @access 广告位导出|3
	 */   
    public function export() {
        $res = appRequest('appdal/advertisementposition/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
            $title[] = ['title' => $v, 'field' => $k];
        }
        $res = appRequest('appdal/advertisementposition/getall', Request::param());
        $data = $res['data']['record'] ?? [];
        excel_export($title, $data, '广告位导出.xlsx', false);
    }
}