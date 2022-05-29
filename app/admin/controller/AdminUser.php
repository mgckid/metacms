<?php


namespace app\admin\controller;

use think\facade\Request;
use think\facade\Filesystem;
use think\facade\App;

/**
 * @access 用户管理|1
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-01-23
 * Time: 22:23
 */
class  AdminUser extends UserBase {

    /**
     * @access 用户管理|2
     */
    public function index() {
        if (Request::param('list_data')) {
            $res = appRequest('appdal/adminuser/index', Request::param());
            return json(success($res['data'] ?? [], $res['message'] ?? ''));
        } else {
            $res = appRequest('appdal/adminuser/getfilterinit', []);
            $init = $res['data']['record'];
            \Form::getInstance()
                ->input_inline_start()
                ->form_schema($init)
                ->input_submit('<i class="layui-icon"></i> 搜索', ' class="layui-btn layui-btn-primary" lay-submit lay-filter="data-search-btn"', 'class="layui-btn layui-btn-primary"')
                ->input_inline_end()
                ->form_data(Request::param())
                ->form_class(\LayuiForm::form_class_pane);
            $res = appRequest('appdal/adminuser/getlistinit', []);
            $list_init = $res['data']['record'];
            return view('adminuser/index', compact('list_init'));
        }
    }

    /**
     * @access 用户新增|3
     */
    public function add() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminuser/add', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/adminuser/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init);
            return view('adminuser/add');
        }
    }

    /**
     * @access 用户编辑|3
     */
    public function edit() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminuser/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/adminuser/getforminit', []);
            $init = $res['data']['record'] ?? [];
            $init = array_column($init,null,'name');
            unset($init['password']);
            $res = appRequest('appdal/adminuser/getone', Request::param());
            $data = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data($data);
            return view('adminuser/add');
        }
    }

    /**
     * @access 用户删除|3
     */
    public function del() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminuser/del', Request::param());
            return json($res);
        }
    }

    /**
     * @access 用户批量修改|3
     */
    public function batch() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminuser/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/adminuser/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(Request::param());
            return view('adminuser/add');
        }
    }

    /**
     * @access 用户导入|3
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
            $res = appRequest('appdal/adminuser/import', json_encode($data), 'json');
            return json($res);
        } else {
            //\Form::getInstance()->in->form_data(Request::param());
            return view('adminuser/import');
        }
    }

    /**
     * @access 用户导入模板下载|3
     */
    public function template() {
        $res = appRequest('appdal/adminuser/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
                $title[] = ['title' => $v, 'field' => $k];
        }
        excel_export($title, [], '用户导入模板.xlsx', false);
    }

    /**
     * @access 用户导出|3
     */
    public function export() {
        $res = appRequest('appdal/adminuser/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            if ($k)
                $title[] = ['title' => $v, 'field' => $k];
        }
        $res = appRequest('appdal/adminuser/getall', Request::param());
        $data = $res['data']['record'] ?? [];
        excel_export($title, $data, '用户导出.xlsx', false);
    }

    /**
     * @access 分配角色|3
     */
    public function addUserRole() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminuserrole/store', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/adminuserrole/getforminit', []);
            $init = $res['data']['record'] ?? [];
            $res = appRequest('appdal/adminuserrole/getone', Request::param());
            $data = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(array_merge($data, Request::param()));
            return view('adminuser/addUserRole');
        }
    }

    /**
     * @access 修改密码|3
     */
    public function changePassword() {
        if ($this->request->isAjax()) {
            $res = appRequest('appdal/adminUser/changepassword', Request::post());
            return json($res);
        } else {
            \Form::getInstance()
                ->input_hidden('id',input('get.id'))
                ->input_text('旧的密码', '填写自己账号的旧的密码。', 'old_password')
                ->input_text('新的密码', '', 'password')
                ->input_text('确认密码', '', 'repassword');
            return view('adminuser/add');
        }
    }

    /**
     * @access 重置密码|3
     */
    public function resetPassword() {
        if ($this->request->isAjax()) {
            $res = appRequest('appdal/adminUser/resetPassword', Request::post());
            return json($res);
        } else {
            \Form::getInstance()
                ->input_hidden('id', input('get.id'))
                ->input_text('新的密码', '', 'password')
                ->input_text('确认密码', '', 'repassword');
            return view('adminuser/add');
        }
    }
}