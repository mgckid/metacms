<?php
/**
 * 权限访问模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-26
 * Time: 13:07
 */

namespace app\appdal\model;
use think\Model;

class AdminAccess extends Model {
    use ModelOperation;
    use \think\model\concern\SoftDelete;
    /**
     * 主键id Tp默认主键为id,
     * 如果你没有使用id作为主键名，需要在这里设置真实的数据表id
     */
    protected $deleteTime = "deleted";
    protected $defaultSoftDelete = 0;
    protected $createTime = "created";
    protected $updateTime = "modified";

    protected $table='admin_access';//数据表名（默认自动获取）
    protected $suffix='';//数据表后缀（默认为空）
    protected $pk = "id";//主键名（默认为id）
    //protected $connection='db';//数据库连接（默认读取数据库配置）
    protected $strict=true;//是否严格区分字段大小写（默认为true）
    protected $disuse =[];//数据表废弃字段（数组）



    // 设置字段信息 模型对应数据表字段及类型
    public $schema = array(
        'id' => 'int',
        'pid' => 'int',
        'access_sn' => 'varchar',
        'access_name' => 'varchar',
        'module' => 'varchar',
        'controller' => 'varchar',
        'action' => 'varchar',
        'level' => 'smallint',
        'sort' => 'smallint',
        'deleted' => 'int',
        'created' => 'datetime',
        'modified' => 'datetime',
    );
    public $rule = array(
        'add' =>
            array(
                'pid|父id' => 'require|integer',
                'access_sn|权限sn' => 'require',
                'access_name|权限名称' => 'require',
                'module|模块名称' => 'require',
                'controller|控制器名称' => 'require',
                'action|方法名称' => 'require',
                'level|菜单层级' => 'require|integer',
                'sort|排序值' => 'require|integer',
            ),
        'edit' =>
            array(
                'id|权限id' => 'require|integer',
                'pid|父id' => 'require|integer',
                'access_sn|权限sn' => 'require',
                'access_name|权限名称' => 'require',
                'module|模块名称' => 'require',
                'controller|控制器名称' => 'require',
                'action|方法名称' => 'require',
                'level|菜单层级' => 'require|integer',
                'sort|排序值' => 'require|integer',
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
                    'title' => '权限id',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            1 =>
                array(
                    'title' => '父id',
                    'name' => 'pid',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '权限sn',
                    'name' => 'access_sn',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '权限名称',
                    'name' => 'access_name',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '模块名称',
                    'name' => 'module',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            5 =>
                array(
                    'title' => '控制器名称',
                    'name' => 'controller',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            6 =>
                array(
                    'title' => '方法名称',
                    'name' => 'action',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            7 =>
                array(
                    'title' => '菜单层级',
                    'name' => 'level',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '一级菜单',
                            2 => '二级菜单',
                            3 => '三级菜单(按钮)',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            8 =>
                array(
                    'title' => '排序值',
                    'name' => 'sort',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            9 =>
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
            10 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            11 =>
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
                    'title' => '权限id',
                    'type' => 'checkbox',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            1 =>
                array(
                    'field' => 'pid',
                    'title' => '父id',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' => (function () {
                        $res = self::field('access_name,id')->select()->toArray();
                        return array_column($res, 'access_name', 'id');
                    })(),
                ),
            2 =>
                array(
                    'field' => 'access_sn',
                    'title' => '权限sn',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            3 =>
                array(
                    'field' => 'access_name',
                    'title' => '权限名称',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            4 =>
                array(
                    'field' => 'module',
                    'title' => '模块名称',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            5 =>
                array(
                    'field' => 'controller',
                    'title' => '控制器名称',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            6 =>
                array(
                    'field' => 'action',
                    'title' => '方法名称',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            7 =>
                array(
                    'field' => 'level',
                    'title' => '菜单层级',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            1 => '一级菜单',
                            2 => '二级菜单',
                            3 => '三级菜单(按钮)',
                        ),
                ),
            8 =>
                array(
                    'field' => 'sort',
                    'title' => '排序值',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            9 =>
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
            10 =>
                array(
                    'field' => 'created',
                    'title' => '创建时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            11 =>
                array(
                    'field' => 'modified',
                    'title' => '修改时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
        );
    }

    public function filter_schema() {
        return array(
            0 =>
                array(
                    'title' => '权限id',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            1 =>
                array(
                    'title' => '父id',
                    'name' => 'pid',
                    'description' => '',
                    'enum' => (function () {
                        $res = self::field('access_name,id')->where('pid',0)->select()->toArray();
                        return array_column($res, 'access_name', 'id');
                    })(),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '权限sn',
                    'name' => 'access_sn',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '权限名称',
                    'name' => 'access_name',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '模块名称',
                    'name' => 'module',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            5 =>
                array(
                    'title' => '控制器名称',
                    'name' => 'controller',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            6 =>
                array(
                    'title' => '方法名称',
                    'name' => 'action',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            7 =>
                array(
                    'title' => '菜单层级',
                    'name' => 'level',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '一级菜单',
                            2 => '二级菜单',
                            3 => '三级菜单(按钮)',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            8 =>
                array(
                    'title' => '排序值',
                    'name' => 'sort',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            9 =>
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
            10 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            11 =>
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
                    0 => 't.pid',
                    1 => '=',
                    2 => $param['pid'],
                ),
            2 =>
                array(
                    0 => 't.access_sn',
                    1 => '=',
                    2 => $param['access_sn'],
                ),
            3 =>
                array(
                    0 => 't.access_name',
                    1 => '=',
                    2 => $param['access_name'],
                ),
            4 =>
                array(
                    0 => 't.module',
                    1 => '=',
                    2 => $param['module'],
                ),
            5 =>
                array(
                    0 => 't.controller',
                    1 => '=',
                    2 => $param['controller'],
                ),
            6 =>
                array(
                    0 => 't.action',
                    1 => '=',
                    2 => $param['action'],
                ),
            7 =>
                array(
                    0 => 't.level',
                    1 => '=',
                    2 => $param['level'],
                ),
            8 =>
                array(
                    0 => 't.sort',
                    1 => '=',
                    2 => $param['sort'],
                ),
            9 =>
                array(
                    0 => 't.deleted',
                    1 => '=',
                    2 => $param['deleted'],
                ),
            10 =>
                array(
                    0 => 't.created',
                    1 => '=',
                    2 => $param['created'],
                ),
            11 =>
                array(
                    0 => 't.modified',
                    1 => '=',
                    2 => $param['modified'],
                ),
        );
    }

    //权限访问-初始所有权限
    //appdal/adminaccess/initAccess
    public function initAccess($param) {
        $res = self::where('access_sn', 'not in', array_column($param, 'access_sn'))->select();
        foreach ($res as $value) {
            $value->delete();
        }
        try {
            $sn_map = [];
            foreach ($param as $value) {
                if (!$value['action']) {
                    $model = self::where(['access_sn' => $value['access_sn']])->find() ?: new self();
                    $data = [
                        'access_sn' => $value['access_sn'],
                        'access_name' => $value['access_name'],
                        'module' => $value['module'],
                        'controller' => $value['controller'],
                        'action' => $value['action'],
                        'level' => $value['level'],
                    ];
                    $model->save($data);
                    $sn_map[$value['access_sn']]=$model->id;
                }
            }
            foreach ($param as $value) {
                if ($value['action']) {
                    $model = self::where(['access_sn' => $value['access_sn']])->find() ?: new self();
                    $data = [
                        'pid' => $sn_map[$value['p_access_sn']]??'',
                        'access_sn' => $value['access_sn'],
                        'access_name' => $value['access_name'],
                        'module' => $value['module'],
                        'controller' => $value['controller'],
                        'action' => $value['action'],
                        'level' => $value['level'],
                    ];
                    $model->save($data);
                }
            }
            return success();
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }
}