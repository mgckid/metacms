<?php


namespace app\controller;

use app\controller\BaseController;
use app\logic\BaseLogic;
use app\model\AdminRoleModel;
use app\model\AdminUserModel;
use app\model\AdminUserRoleModel;
use app\model\AdminAccessModel;
use app\model\AdminRoleAccessModel;
use DocBlockReader\Reader;
use metacms\web\Form;
use metacms\base\Page;

/**
 * 权限控制
 * @privilege 权限控制|Admin/Rbac|e54168a6-1ff8-11e7-8ad5-9cb3ab404081|1
 *  2016年6月5日 11:40:09
 * @author Administrator
 */
class RbacController extends UserBaseController
{

    /**
     * 添加角色
     * @privilege 添加角色|Admin/Rbac/addRole|564432bc-2003-11e7-8ad5-9cb3ab404081|3
     */
    public function addRole()
    {
        if (IS_POST) {
            $model = new AdminRoleModel();
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData('admin_role', 'table');
            $request_data['role_id'] = uuid();
            $count = $model->orm()->where(['role_name'=>$request_data['role_name'],'deleted'=>0])->count();
            if($count){
                $this->ajaxFail('角色名已存在');
            }
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail($this->getMessage());
            } else {
                $this->ajaxSuccess('添加成功');
            }
        } else {
            #获取表单数据
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('admin_role', 'table');

            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '权限管理' => U('admin/Rbac/index'),
                '添加角色' => ''
            ));
            $this->display('Rbac/addRole');
        }
    }

    /**
     * 修改角色
     * @privilege 修改角色|Admin/Rbac/editRole|32fb1480-f04c-11e7-83c7-00163e003500|3
     */
    public function editRole()
    {
        if (IS_POST) {
            $model = new AdminRoleModel();
            $logic = new BaseLogic();
            $request_data = $logic->getRequestData('admin_role', 'table');
            $request_data['role_id'] = uuid();
            $count = $model->orm()->where(['role_name'=>$request_data['role_name'],'deleted'=>0])->count();
            if($count){
                $this->ajaxFail('角色名已存在');
            }
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail($this->getMessage());
            } else {
                $this->ajaxSuccess('添加成功');
            }
        } else {
            #获取表单数据
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('admin_role', 'table');

            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '权限管理' => U('admin/Rbac/index'),
                '修改角色' => ''
            ));
            $this->display('Rbac/addRole');
        }
    }

    /**
     * 用户绑定角色
     * @privilege 添加角色|Admin/Rbac/addUserRole|7f788b03-2003-11e7-8ad5-9cb3ab404081|3
     */
    public function addUserRole()
    {
        if (IS_POST) {
            $userName = isset($_POST['user']) ? trim($_POST['user']) : '';
            $role = isset($_POST['role']) ? $_POST['role'] : array();
            if (!$userName) {
                $this->ajaxFail('请选择用户');
            }
            if (!$role) {
                $this->ajaxFail('请选择角色');
            }
            $userAdminRoleModel = new AdminUserRoleModel();
            $userModel = new AdminUserModel();
            $userInfo = $userModel->getUserInfo($userName);
            if (empty($userInfo)) {
                $this->ajaxFail('用户不存在');
            }
            #获取用户角色；
            $roleList = $userAdminRoleModel->getRoleByUserId($userInfo['user_id']);
            #添加的角色
            $addRole = array_diff($role, $roleList);
            #删除的角色
            $delRole = array_diff($roleList, $role);
            $userAdminRoleModel->beginTransaction();
            try {
                $count = 0;
                #添加角色
                if ($addRole) {
                    foreach ($addRole as $value) {
                        $data = array(
                            'user_id' => $userInfo['user_id'],
                            'role_id' => $value,
                            'created' => date('Y-m-d H:i:s', time()),
                            'modified' => date('Y-m-d H:i:s', time()),
                        );
                        $obj = $userAdminRoleModel->orm()->create($data);
                        if ($obj->save()) {
                            $count++;
                        }
                    }
                    if (count($addRole) != $count) {
                        throw new \Exception('添加用户角色关联记录失败');
                    }
                }
                #删除角色
                if ($delRole) {
                    $result = $userAdminRoleModel->orm()
                        ->where(array('user_id' => $userInfo['user_id']))
                        ->where_in('role_id', $delRole)
                        ->delete_many();
                    if (!$result) {
                        throw new \Exception('删除角色权限失败');
                    }
                }
                $userAdminRoleModel->commit();
            } catch (\Exception $ex) {
                $userAdminRoleModel->rollBack();
                $this->ajaxFail($ex->getMessage());
            }
            $this->ajaxSuccess('添加用户角色关联记录成功');
        } else {
            $roleModel = new AdminRoleModel();
            $userModel = new AdminUserModel();
            #获取用户列表
            $field = array('username', 'true_name');
            $userList = $userModel->orm()
                ->select($field)
                ->find_array();
            #获取角色列表
            $field = array('role_id', 'role_name');
            $roleList = $roleModel->orm()
                ->select($field)
                ->find_array();
            $data = array(
                'user' => $userList,
                'role' => $roleList
            );
            #面包屑导航
            $this->crumb(array(
                '权限管理' => U('admin/Rbac/index'),
                '用户分配权限' => ''
            ));
            $this->display('Rbac/addUserRole', $data);
        }
    }

    /**
     * 添加用户
     * @privilege 添加用户|Admin/Rbac/addUser|b02cf321-2003-11e7-8ad5-9cb3ab404081|3
     */
    public function addUser()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('admin_user', 'table');

            $adminAdminUserModel = new AdminUserModel();
            $request_data['user_id'] = $adminAdminUserModel->getUserId();

            $result = $adminAdminUserModel->addRecord($request_data);
            if ($result) {
                $this->ajaxSuccess('用户添加成功');
            } else {
                $this->ajaxFail('用户添加失败');
            }
        } else {
            #表单初始化数据
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('admin_user', 'table');
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '权限管理' => U('admin/Rbac/Index'),
                '添加用户' => ''
            ));
            $this->display('Rbac/addUser');
        }
    }

    /**
     * 添加权限
     * @privilege 添加权限|Admin/Rbac/addAccess|ddb2c332-2003-11e7-8ad5-9cb3ab404081|3
     */
    public function addAccess()
    {
        if (IS_POST) {
            $model = new AdminAccessModel();
            #验证
            $rule = array(
                'access_name' => 'required',
                'url' => 'required',
                'path' => 'required',
                'sort' => 'required|integer',

            );
            $attr = array(
                'fname' => '站点名称',
                'furl' => '站点链接'
            );
            $validate = $model->validate()->make($_POST, $rule, [], $attr);
            if (false === $validate->passes()) {
                $this->ajaxFail($validate->messages()->first());
            }
            #获取参数
            $accessId = isset($_POST['id']) ? intval($_POST['id']) : 0;

            $accessName = isset($_POST['access_name']) ? trim($_POST['access_name']) : '';
            $url = isset($_POST['url']) ? trim($_POST['url']) : '';
            $path = isset($_POST['path']) ? trim($_POST['path']) : '';
            $sort = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
            $accessType = isset($_POST['access_type']) ? intval($_POST['access_type']) : 0;

            if (!$accessName || !$url || !$path || !$sort || !$accessType) {
                $this->ajaxFail('提交数据不完整');
            }

            $url = explode('/', $url);
            $data = array(
                'access_name' => $accessName,
                'sort' => $sort,
                'path' => $path,
                'module' => array_shift($url),
                'controller' => array_shift($url),
                'action' => array_shift($url),
                'access_type' => $accessType,
                'created' => date('Y-m-d H:i:s', time()),
                'modified' => date('Y-m-d H:i:s', time()),
            );

            if ($accessId) {#更新记录
                unset($data['created']);
                $obj = $model->orm()->find_one($accessId);
                $obj->set($data);
            } else {#创建新纪录
                $obj = $model->orm()->create($data);
            }
            if (!$obj->save()) {
                $this->ajaxFail('菜单添加失败');
            } else {
                $this->ajaxSuccess('菜单添加成功');
            }
        } else {
            $accessId = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $accessInfo = array(
                'id' => $accessId,
                'access_name' => '',
                'sort' => 100,
                'access_type' => 10,
                'url' => '',
                'path' => ''
            );
            if ($accessId) {
                $model = new AdminAccessModel();
                $result = $model->orm()->find_one($accessId)->as_array();
                $accessInfo['id'] = $accessId;
                $accessInfo['access_name'] = $result['access_name'];
                $accessInfo['sort'] = $result['sort'];
                $accessInfo['access_type'] = $result['access_type'];
                $accessInfo['url'] = $result['module'] . '/' . $result['controller'] . '/' . $result['action'];
                $accessInfo['path'] = $result['path'];
            }
            $accessList = $this->handleAccess();
//            print_g($accessList);
            $data = array(
                'accessInfo' => $accessInfo,
                'list' => $accessList
            );
            #面包屑导航
            $this->crumb(array(
                '权限管理' => U('admin/Rbac/index'),
                '添加权限' => ''
            ));
            $this->display('Rbac/addAccess', $data);
        }
    }

    protected function handleAccess()
    {
        $model = new AdminAccessModel();
        $list = $model->orm()
            ->find_array();
        return treeStructForLevel($list);
    }

    /**
     * 角色绑定权限
     * @privilege 角色绑定权限|Admin/Rbac/addRoleAccess|0b8a6a4e-2004-11e7-8ad5-9cb3ab404081|3
     */
    public function addRoleAccess()
    {
        if (IS_POST) {
            $roleAccessModel = new AdminRoleAccessModel();
            #验证
            $rule = array(
                'role_id' => 'required',
            );
            $attr = array(
                'role_id' => '角色id',
            );
            $validate = $roleAccessModel->validate()->make($_POST, $rule, [], $attr);
            if (false === $validate->passes()) {
                $this->ajaxFail($validate->messages()->first());
            }
            $roleId = isset($_POST['role_id']) ? trim($_POST['role_id']) : '';
            $access = isset($_POST['access_sn']) ? $_POST['access_sn'] : array();

            #获取角色权限
            $orm = $roleAccessModel->orm()->where('role_id',$roleId);
            $count = $roleAccessModel->getRecordList($orm,'','',true);
            $result = $roleAccessModel->getRecordList($orm,0,$count);
            $userAccess = array_column($result, 'access_sn');
            #新添加权限
            $addCccess = array_diff($access, $userAccess);
            #角色待删除权限
            $delAccess = array_diff($userAccess, $access);
            if (!$userAccess && !$delAccess && !$addCccess) {
                $this->ajaxFail('请给角色分配权限');
            }
            $roleAccessModel->beginTransaction();
            try {
                #添加新权限
                if ($addCccess) {
                    foreach ($addCccess as $value) {
                        $data = array(
                            'role_id' => $roleId,
                            'access_sn' => $value,
                        );
                        $result=  $roleAccessModel->addRecord($data);
                        if (!$result) {
                            throw new \Exception('添加角色权限关联记录失败');
                        }
                    }
                }
                #删除权限
                if ($delAccess) {
                    foreach ($delAccess as $value) {
                        $result = $roleAccessModel->orm()
                            ->where('role_id', $roleId)
                            ->where('access_sn', $value)
                            ->find_one()->set(['deleted' => 1, 'modified' => getDateTime()])
                            ->save();
                        if (!$result) {
                            throw new \Exception('删除角色权限失败');
                        }
                    }
                }
                $roleAccessModel->commit();
            } catch (\Exception $ex) {
                $roleAccessModel->rollBack();
                $this->ajaxFail($ex->getMessage());
            }
            $this->ajaxSuccess('添加角色权限关联记录成功');
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $roleModel = new AdminRoleModel();
            $accessModel = new AdminAccessModel();
            $role_result = $roleModel->getRecordInfoById($id);
            #权限记录
            $access_result = $accessModel->getAllRecord();
            $accessList = treeStructForLayer($access_result);
            #角色记录
            $roleList = $roleModel->getAllRecord();

            $data = array(
                'role' => $roleList,
                'access' => $accessList,
                'role_id' => $role_result['role_id']
            );
            #面包屑导航
            $this->crumb(array(
                '权限管理' => U('admin/Rbac/index'),
                '角色分配权限' => ''
            ));
            $this->display('Rbac/addRoleAccess', $data);
        }
    }

    /**
     * 角色管理
     * @privilege 角色管理|Admin/Rbac/roleList|61d74ef6-2006-11e7-8ad5-9cb3ab404081|2
     */
    public function roleList()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 10;

        $dictionaryLogic = new BaseLogic();
        $list_init = $dictionaryLogic->getListInit('admin_role');

        $roleModel = new AdminRoleModel();
        $count = $roleModel->getRecordList('', '','', true);
        $page = new Page($count, $p, $pageSize, false);
        $roleList = $roleModel->getRecordList('',$page->getOffset(), $pageSize, false,'id','asc');
        $data = array(
            'list' => $roleList,
            'pages' => $page->getPageStruct(),
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '权限管理' => U('admin/Rbac/index'),
            '角色管理' => ''
        ));
        $this->display('Rbac/roleList', $data);
    }

    /**
     * 删除角色
     * @privilege 删除角色|Admin/Rbac/delRole|9122b724-2006-11e7-8ad5-9cb3ab404081|3
     */
    public function delRole()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        $roleModel = new AdminRoleModel();
        #验证
        $rule = array(
            'id' => 'required|integer',
        );
        $attr = array(
            'id' => '角色ID'
        );
        $validate = $roleModel->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($roleModel->deleteRecordById($id)) {
            $this->ajaxSuccess('删除成功');
        } else {
            $this->ajaxFail('删除失败');
        }
    }

    /**
     * 用户列表
     * @privilege 用户列表|Admin/Rbac/index|b7dd416b-2006-11e7-8ad5-9cb3ab404081|2
     */
    public function index()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 10;
        $userModel = new AdminUserModel();
        $count = $userModel->getRecordList('','', '', true);
        $page = new Page($count, $p, $pageSize);
        $list = $userModel->getRecordList('',$page->getOffset(), $pageSize);

        #获取列表字段
        $dictionarylogic = new BaseLogic();
        $list_init = $dictionarylogic->getListInit('admin_user','table');


        $data = array(
            'list' => $list,
            'pages' => $page->getPageStruct(),
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '权限管理' => U('admin/Rbac/Index'),
            '用户管理' => ''
        ));
        $this->display('Rbac/index', $data);
    }

    /**
     * 异步获取角色权限
     * @privilege 异步获取角色权限|Admin/Rbac/ajaxGetRoleAccess|e8c6d45b-2006-11e7-8ad5-9cb3ab404081|3
     */
    public function ajaxGetRoleAccess()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $RoleAccessModel = new AdminRoleAccessModel();
        #验证
        $rule = array(
            'role_id' => 'required',
        );
        $attr = array(
            'role_id' => '角色ID'
        );
        $validate = $RoleAccessModel->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        $roleId = isset($_POST['role_id']) ? trim($_POST['role_id']) : '';

        $orm = $RoleAccessModel->orm()->where('role_id',$roleId);
        $result = $RoleAccessModel->getAllRecord($orm);
        if (!$result) {
            $this->ajaxFail('该角色还没分配权限');
        }
        $data = array_column($result, 'access_sn');
        $this->ajaxSuccess('获取权限成功', $data);
    }

    /**
     * 权限列表
     * @privilege 权限列表|Admin/Rbac/accessList|e8d28dd3-2006-11e7-8ad5-9cb3ab404081|2
     */
    public function accessList()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 20;
        $accessModel = new AdminAccessModel();
        $count = $accessModel->getAccessList('', '', TRUE);
        $page = new Page($count, $p, $pageSize);
        $list = $accessModel->getAccessList($page->getOffset(), $pageSize);
        foreach ($list as $k => $v) {
            switch ($v['level']) {
                case 1:
                    $v['level_text'] = '一级栏目';
                    break;
                case 2:
                    $v['level_text'] = '二级栏目';
                    break;
                case 3:
                    $v['level_text'] = '按钮';
                    break;
            }
            $list[$k] = $v;
        }
        $data = array(
            'list' => $list,
            'pages' => $page->getPageStruct(),
        );
        #面包屑导航
        $this->crumb(array(
            '权限管理' => U('admin/Rbac/index'),
            '权限列表' => ''
        ));
        $this->display('Rbac/accessList', $data);
    }

    /**
     * 删除权限
     * @privilege 删除权限|Admin/Rbac/delAccess|e8dbe4f6-2006-11e7-8ad5-9cb3ab404081|3
     */
    public function delAccess()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        $accessModel = new AdminAccessModel();
        #验证
        $rule = array(
            'access_id' => 'required|integer',
        );
        $attr = array(
            'access_id' => '权限id'
        );
        $validate = $accessModel->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $accessId = isset($_POST['access_id']) ? intval($_POST['access_id']) : 0;

        $roleAccessModel = new AdminRoleAccessModel();
        $count = $roleAccessModel->orm()
            ->where(array('access_id' => $accessId))
            ->count();
        if ($count) {
            $this->ajaxFail('该权限已被分配,请先移除该权限的分配');
        }

        if (!$accessModel->deleteRecordById($accessId)) {
            $this->ajaxFail('删除权限失败');
        } else {
            $this->ajaxSuccess('删除权限成功');
        }
    }

    /**
     * 检查页面按钮权限
     * @privilege 检查页面按钮权限|Admin/Rbac/ajaxCheckPower|e8e7ba2b-2006-11e7-8ad5-9cb3ab404081|3
     */
    public function ajaxCheckPower()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        $power = isset($_POST['power']) ? $_POST['power'] : array();
        $return = array();
        foreach ($power as $v) {
            $return[$v] = true;//$this->checkPower($v);
        }
        $this->ajaxSuccess('获取成功', $return);
    }

    /**
     * 重置密码
     * @privilege 重置密码|Admin/Rbac/resetPassword|e8f002d4-2006-11e7-8ad5-9cb3ab404081|3
     */
    public function resetPassword()
    {
        if (IS_POST) {
            $userModel = new AdminUserModel();
            #验证
            $rule = array(
                'id' => 'required|integer',
                'password' => 'required|alpha_num|min:6',
                'repassword' => 'required|same:password'
            );
            $attr = array(
                'id' => '用户ID',
                'password' => '密码',
                'repassword' => '重复密码'
            );
            $validate = $userModel->validate()->make($_POST, $rule, [], $attr);
            if (false === $validate->passes()) {
                $this->ajaxFail($validate->messages()->first());
            }
            #获取参数
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $rePassword = isset($_POST['repassword']) ? trim($_POST['repassword']) : '';

            $data = array(
                'password' => sha1($password),
                'modified' => date('Y-m-d H:i:s', time()),
            );
            $result = $userModel->orm()
                ->find_one($id)
                ->set($data)
                ->save();
            if (!$result) {
                $this->ajaxFail('密码修改失败');
            } else {
                $this->ajaxSuccess('密码修改成功');
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if (!$id) {
                trigger_error('用户id不能为空');
            }
            $userModel = new AdminUserModel();
            $userInfo = $userModel->orm()
                ->find_one($id)
                ->as_array();
            if (!$userInfo) {
                trigger_error('用户不存在');
            }
            $data = array(
                'info' => $userInfo,
            );
            #面包屑导航
            $this->crumb(array(
                '权限管理' => U('admin/Rbac/index'),
                '修改密码' => ''
            ));
            $this->display('Rbac/resetPassword', $data);
        }
    }

    /**
     * 异步获取用户角色
     * @privilege 异步获取用户角色|Admin/Rbac/ajaxGetUserRole|e8f8d28c-2006-11e7-8ad5-9cb3ab404081|3
     */
    public function ajaxGetUserRole()
    {
        if (!IS_AJAX) {
            $this->ajaxFail('非法请求');
        }
        $userModel = new AdminUserModel();
        #验证
        $rule = array(
            'username' => 'required|alpha',
        );
        $attr = array(
            'username' => '用户名称',
        );
        $validate = $userModel->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';

        $userAdminRoleModel = new AdminUserRoleModel();
        $userInfo = $userModel->getUserInfo($username);
        if (empty($userInfo)) {
            $this->ajaxFail('用户不存在');
        }
        $roleList = $userAdminRoleModel->getRoleByUserId($userInfo['user_id']);
        if (empty($roleList)) {
            $this->ajaxFail('该用户没有分配角色');
        } else {
            $this->ajaxSuccess('获取成功', $roleList);
        }
    }


    /**
     * 动态获取权限
     * @privilege 动态获取权限|Admin/Rbac/autoAddAccess|0cc95059-4b0a-11e5-84df-d8cb8a114577|2
     * @since  2017年4月12日 17:21:38
     */
    public function autoAddAccess()
    {
        $arr = glob(__DIR__ . DIRECTORY_SEPARATOR . '*.php');

        $accessModel = new AdminAccessModel();
        $accessModel->orm()->delete_many();
        foreach ($arr as $path) {
            $basename = basename($path, '.php');
            $className = __NAMESPACE__ . '\\' . ucfirst($basename);
            if (!class_exists($className)) {
                continue;
            }
            $privilege = $this->getPrivilege($className);
            if (!$privilege) {
                continue;
            }
            $privilege['pid'] = 0;
            $insertId = $accessModel->addAccess($privilege);
            if (!$insertId) {
                continue;
            }
            $reflectionClass = new \ReflectionClass($className);
            foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $key => $method) {
                $privilege = $this->getPrivilege($method->class, $method->name);
                if (!$privilege) {
                    continue;
                }
                $privilege['pid'] = $insertId;
                $accessModel->addAccess($privilege);
            }
        }
    }

    /**
     * 解析注释获取功能权限
     * @access public|protected|private
     * @author furong
     * @param $annotation
     * @return array|bool
     * @since ${DATE}
     * @abstract
     */
    protected function getPrivilege($className, $method = '')
    {
        if (empty($className)) {
            return false;
        }
        $reader = empty($method) ? new Reader($className) : new Reader($className, $method);
        $annotation = $reader->getParameters();
        if (!isset($annotation['privilege']) || empty($annotation['privilege'])) {
            return false;
        }
        $privilege = explode('|', $annotation['privilege']);
        list($accessName, $url, $accessSn, $level) = $privilege;
        $url = explode('/', $url);
        list($module, $controller) = $url;
        $action = count($url) == 3 ? end($url) : '';
        $data = [
            'access_sn' => $accessSn,
            'access_name' => $accessName,
            'module' => $module,
            'controller' => $controller,
            'action' => $action,
            'level' => $level
        ];
        return $data;
    }

}
