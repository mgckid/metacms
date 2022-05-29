<?php


namespace app\admin\controller;

use think\facade\Request;
use think\facade\Filesystem;
use think\facade\App;

/**
 * @access 角色管理|1
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-01-23
 * Time: 22:36
 */
class  AdminRole extends UserBase  {

    /**
     * @access 角色管理|2
     */
    public function index() {
        if (Request::param('list_data')) {
            $res = appRequest('appdal/adminrole/index', Request::param());
            return json(success($res['data'] ?? [], $res['message'] ?? ''));
        } else {
            $res = appRequest('appdal/adminrole/getfilterinit', []);
            $init = $res['data']['record'];
            \Form::getInstance()
                ->input_inline_start()
                ->form_schema($init)
                ->input_submit('<i class="layui-icon"></i> 搜索',' class="layui-btn layui-btn-primary" lay-submit lay-filter="data-search-btn"','class="layui-btn layui-btn-primary"')
                ->input_inline_end()
                ->form_data(Request::param())
                ->form_class(\LayuiForm::form_class_pane);
            $res = appRequest('appdal/adminrole/getlistinit', []);
            $list_init = $res['data']['record'];
            return view('adminrole/index', compact('list_init'));
        }
    }

    /**
     * @access 角色新增|3
     */
    public function add() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminrole/add', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/adminrole/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init);
//            \Form2::getInstance()->input_text('角色id','','role_id',111)
//                ->input_text('角色名称','111','role_name');
            return view('adminrole/add');
        }
    }

    /**
     * @access 角色编辑|3
     */
    public function edit() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminrole/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/adminrole/getforminit', []);
            $init = $res['data']['record'] ?? [];
            $res = appRequest('appdal/adminrole/getone', Request::param());
            $data = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data($data);
            return view('adminrole/add');
        }
    }

    /**
     * @access 角色删除|3
     */
    public function del() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminrole/del', Request::param());
            return json($res);
        }
    }

    /**
     * @access 角色批量修改|3
     */
    public function batch() {
        if (Request::isPost()) {
            $res = appRequest('appdal/adminrole/edit', Request::param());
            return json($res);
        } else {
            $res = appRequest('appdal/adminrole/getforminit', []);
            $init = $res['data']['record'] ?? [];
            \Form::getInstance()->form_schema($init)->form_data(Request::param());
            return view('adminrole/add');
        }
    }

    /**
     * @access 角色导入|3
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
            $res = appRequest('appdal/adminrole/import', json_encode($data), 'json');
            return json($res);
        } else {
            //\Form::getInstance()->in->form_data(Request::param());
            return view('adminrole/import');
        }
    }

    /**
     * @access 角色导入模板下载|3
     */
    public function template() {
        $res = appRequest('appdal/adminrole/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            $title[] = ['title' => $v, 'field' => $k];
        }
        excel_export($title, [], '角色导入模板.xlsx', false);
    }

    /**
     * @access 角色导出|3
     */
    public function export() {
        $res = appRequest('appdal/adminrole/getlistinit');
        $init = $res['data']['record'] ?? [];
        $key = array_column($init, 'title', 'field');
        unset($key['id']);
        $title = [];
        foreach ($key as $k => $v) {
            $title[] = ['title' => $v, 'field' => $k];
        }
        $res = appRequest('appdal/adminrole/getall', Request::param());
        $data = $res['data']['record'] ?? [];
        excel_export($title, $data, '角色导出.xlsx', false);
    }

    /**
     * @access 分配权限|3
     */
    public function addRoleAccess() {
        if (Request::isPost()) {
            $res = appRequest('appdal/AdminRoleAccess/store', Request::post());
            return json($res);
        } else {
            $res = appRequest('appdal/adminrole/getone', Request::param());
            $roleinfo = $res['data']['record'] ?? [];
            $res = appRequest('appdal/AdminRoleAccess/getforminit', []);
            $init = $res['data']['record'] ?? [];

            \Form::getInstance()
                ->input_hidden('role_id', $roleinfo['id'])
                ->input_text('角色', '', 'role_name', $roleinfo['role_name'], true)
                ->empty_box('选择权限', '', 'authtree_box', '');
            return view('adminrole/addRoleAccess');
        }
    }

    /**
     * @access ajax获取角色权限|3
     */
    public function getRoleAccess() {
        $param = Request::param();
        $result = appRequest('appdal/adminrole/getone', ['id' => $param['id']]);
        $role_info = $result['data']['record'] ?? [];

        $result = appRequest('appdal/adminroleaccess/getone', ['role_id' => $role_info['id']]);
        $role_access = $result['data']['record']['access_sn'] ?? '';
        $role_access = $role_access ? explode(',', $role_access) : [];

        $result = appRequest('appdal/adminaccess/getall', []);
        $result['data']['record'] = $result['data']['record'] ?? [];
        $access_enum = $checked = [];
        foreach ($result['data']['record'] as $value) {
            $access_enum[] = [
                'id' => $value['id'],
                'name' => $value['access_name'],
                'pid' => $value['pid'],
            ];
            if (in_array($value['id'], $role_access)) {
                $checked[] = $value['id'];
            }
        }
        return json(success(['list' => $access_enum, 'checked' => $checked]));
    }
}