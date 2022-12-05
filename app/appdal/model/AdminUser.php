<?php
/**
 * 用户模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-22
 * Time: 23:20
 */

namespace app\appdal\model;

use think\Model;

class AdminUser extends Model {
    use ModelOperation;
    use \think\model\concern\SoftDelete;
    /**
     * 主键id Tp默认主键为id,
     * 如果你没有使用id作为主键名，需要在这里设置真实的数据表id
     */
    protected $pk = "id";
    protected $deleteTime = "deleted";
    protected $defaultSoftDelete = 0;
    protected $createTime = "created";
    protected $updateTime = "modified";

    protected $table = 'admin_user';
    protected $suffix = '';
    //protected $connection='db';
    protected $strict = true;
    protected $disuse = [];


    // 设置字段信息
    public $schema = array(
        'id' => 'int',
        'user_id' => 'varchar',
        'username' => 'varchar',
        'true_name' => 'varchar',
        'password' => 'varchar',
        'email' => 'varchar',
        'deleted' => 'int',
        'created' => 'datetime',
        'modified' => 'datetime',
    );
    public $rule = array(
        'add' =>
            array(
                'user_id|用户id' => 'require',
                'username|用户名' => 'require',
                'true_name|真实姓名' => 'require',
                'password|密码' => 'require',
                'email|邮箱' => 'require|email',
            ),
        'edit' =>
            array(
                'id|Id' => 'require|integer',
                //'user_id|用户id' => 'require',
                'username|用户名' => 'require',
                'true_name|真实姓名' => 'require',
                //'password|密码' => 'require',
                'email|邮箱' => 'require|email',
            ),
        'del' =>
            array(
                'id|ID' => 'require',
            ),
        'getone' =>
            array(
                'id|ID' => 'require',
            ),
    );

    /**
     * 表单输入类型 type
     * hidden:隐藏域 select：下拉 radio：单选按钮 text：文本 textarea：多行文本 file：上传
     * none：非表单 editor：富文本 checkbox：复选框 date:日期
     */
    public function form_schema() {
        return array(
            0 =>
                array(
                    'title' => 'Id',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            1 =>
                array(
                    'title' => '用户id',
                    'name' => 'user_id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '用户名',
                    'name' => 'username',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '真实姓名',
                    'name' => 'true_name',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '密码',
                    'name' => 'password',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            5 =>
                array(
                    'title' => '邮箱',
                    'name' => 'email',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            6 =>
                array(
                    'title' => '是否删除',
                    'name' => 'deleted',
                    'description' => '',
                    'enum' =>
                        array(
                            0 => '未删除',
                            1 => '已删除',
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            7 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            8 =>
                array(
                    'title' => '修改时间',
                    'name' => 'modified',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
        );
    }

    public function list_schema() {
        return array(
            0 =>
                array(
                    'field' => 'id',
                    'title' => 'Id',
                    'type' => 'checkbox',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            1 =>
                array(
                    'field' => 'user_id',
                    'title' => '用户id',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            2 =>
                array(
                    'field' => 'username',
                    'title' => '用户名',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            3 =>
                array(
                    'field' => 'true_name',
                    'title' => '真实姓名',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            4 =>
                array(
                    'field' => 'password',
                    'title' => '密码',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            5 =>
                array(
                    'field' => 'email',
                    'title' => '邮箱',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            6 =>
                array(
                    'field' => 'deleted',
                    'title' => '是否删除',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(
                            0 => '未删除',
                            1 => '已删除',
                        ),
                ),
            7 =>
                array(
                    'field' => 'created',
                    'title' => '创建时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            8 =>
                array(
                    'field' => 'modified',
                    'title' => '修改时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            9 =>
                array(
                    'title' => '操作',
                    'minWidth' => 50,
                    'templet' => '#lineDemo',
                    'fixed' => 'right',
                    'align' => 'center',
                ),
        );
    }

    public function filter_schema() {
        return array(
            0 =>
                array(
                    'title' => 'Id',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            1 =>
                array(
                    'title' => '用户id',
                    'name' => 'user_id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '用户名',
                    'name' => 'username',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '真实姓名',
                    'name' => 'true_name',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '密码',
                    'name' => 'password',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            5 =>
                array(
                    'title' => '邮箱',
                    'name' => 'email',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            6 =>
                array(
                    'title' => '是否删除',
                    'name' => 'deleted',
                    'description' => '',
                    'enum' =>
                        array(
                            0 => '未删除',
                            1 => '已删除',
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            7 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            8 =>
                array(
                    'title' => '修改时间',
                    'name' => 'modified',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
        );
    }

    public function getCondition($param) {
        return array(
            0 =>
                array(
                    0 => 't.id',
                    1 => '=',
                    2 => $param['id'],
                ),
            1 =>
                array(
                    0 => 't.user_id',
                    1 => '=',
                    2 => $param['user_id'],
                ),
            2 =>
                array(
                    0 => 't.username',
                    1 => '=',
                    2 => $param['username'],
                ),
            3 =>
                array(
                    0 => 't.true_name',
                    1 => '=',
                    2 => $param['true_name'],
                ),
            4 =>
                array(
                    0 => 't.password',
                    1 => '=',
                    2 => $param['password'],
                ),
            5 =>
                array(
                    0 => 't.email',
                    1 => '=',
                    2 => $param['email'],
                ),
            6 =>
                array(
                    0 => 't.deleted',
                    1 => '=',
                    2 => $param['deleted'],
                ),
            7 =>
                array(
                    0 => 't.created',
                    1 => '=',
                    2 => $param['created'],
                ),
            8 =>
                array(
                    0 => 't.modified',
                    1 => '=',
                    2 => $param['modified'],
                ),
        );
    }

    public function login($param) {
        try {
            #验证
            $rules = array(
                'username' => 'require|alpha',
                'password' => 'require|alphaNum|min:5',
            );
            $message = array(
                'username.require' => '用户名必须填写',
                'username.alpha' => '用户名为字母组合',
                'password.require' => '密码必须填写',
                'password.alphaNum' => '密码为字母和数字组合',
                'password.min' => '密码最少6位',
            );
            $validate = validate($rules, $message, true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            #获取参数
            $param['username'] = $param['username'] ?? '';
            $param['password'] = $param['password'] ?? '';
            if (!$this->validate_password($param['username'], $param['password'])) {
                throw new \Exception('验证密码失败');
            }
            $admin_user = $this
                ->where('username', $param['username'])
                ->field('id,user_id,true_name,username,email,created,modified')
                ->find()->toArray();
            #获取用户角色权限
            $result = self::table('admin_user_role')->alias('a')
                ->join('admin_role_access b', 'b.role_id = a.role_id')
                ->where('a.user_id', $admin_user['id'])
                ->where('a.deleted', 0)
                ->where('b.deleted', 0)
                ->field('b.access_sn')
                ->select()->toArray();
            $access_sn = [];
            foreach ($result as $value) {
                $arr = $value['access_sn'] ? explode(',', $value['access_sn']) : [];
                $access_sn = array_merge($access_sn, $arr);
            }
            if ($access_sn) {
                $access_res = self::table('admin_access')->where('id', 'in', $access_sn)
                    ->field("concat(module,'/',controller,'/',action) as url,controller,action,access_name,pid,level,id,access_sn")
                    ->where('deleted', 0)
                    ->order('id', 'asc')
                    ->select()->toArray();
                if ($access_res) {
                    $p_res = self::table('admin_access')->where('id', 'in', array_column($access_res, 'pid'))
                        ->field("concat(module,'/',controller,'/',action) as url,controller,action,access_name,pid,level,id,access_sn")
                        ->where('deleted', 0)
                        ->order('id', 'asc')
                        ->select()->toArray();
                    $access_res = array_merge($access_res, $p_res);
                }
            }
            #站点信息
            /*if (empty($admin_user['site_id'])) {
                throw new \Exception('站点关联失败，请联系管理员');
            }*/
            $site_res = self::table('site')
                //->alias('a')
                //->join($this->table_site_extend . ' b', 'b.site_id = a.site_id', 'left')
                //->where('a.site_id', $admin_user['site_id'])
                //->where('a.deleted', 0)
                //->field('a.*,b.extend_config')
                ->where('deleted', 0)
                ->find()->toArray();
            $_site_config = SiteConfig::where('deleted', 0)->select()->toArray();
            $site_config = array_column($_site_config, 'value', 'name');
            $data = [];
            $data['admin_user'] = $admin_user;
            $data['admin_access'] = $access_res ?? [];
            $data['site'] = $site_res;
            $data['site_config'] = $site_config;
            return success($data);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    /**
     * 验证密码
     * @param type $username
     * @param type $password
     * @return boolean
     */
    private function validate_password($username, $password) {
        $result = $this->where('username', $username)
            ->find();
        if (!$result) {
            throw  new \Exception('用户不存在');
        }
        if ($result['password'] !== sha1($password)) {
            throw new \Exception('密码错误');
        }
        return TRUE;
    }

    public function changePassword($param) {
        try {
            $validate = validate(
                array(
                    'id|用户id' => 'require',
                    'old_password|旧的密码' => 'require',
                    'password|新的密码' => 'require|alphaNum|min:5',
                    'repassword|确认密码' => 'require|confirm:password',
                ), [], true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            $check = $this->where('id', 'in', $param['id'])
                ->where('password', sha1($param['old_password']))
                ->find();
            if (!$check) {
                throw new \Exception('旧的密码填写错误');
            }
            if ($param['old_password'] == $param['password']) {
                throw new \Exception('新密码不能和旧密码相同');
            }
            $result = $this->where('id', 'in', $param['id'])
                ->update(['password' => sha1($param['password'])]);
            return success($result);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function resetPassword($param) {
        try {
            $validate = validate(
                array(
                    'id|用户id' => 'require',
                    'password|新的密码' => 'require|alphaNum|min:5',
                    'repassword|确认密码' => 'require|confirm:password',
                ), [], true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            $result = $this->where('id', 'in', $param['id'])
                ->update(['password' => sha1($param['password'])]);
            return success($result);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public static function onAfterDelete(self $model) {
        if(isset($model->id) and $model->id){
            $roleAcc = AdminUserRole::where('user_id', $model->id)->find();
            $roleAcc->delete();
        }
    }
}