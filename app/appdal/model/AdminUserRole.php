<?php
/**
 * 用户角色关联模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-14
 * Time: 23:01
 */
namespace app\appdal\model;
use think\Model;

class AdminUserRole extends Model {
    use ModelOperation;
    use \think\model\concern\SoftDelete;
                /**
                 * 主键id Tp默认主键为id,
                 * 如果你没有使用id作为主键名，需要在这里设置真实的数据表id
                 */
                protected $pk = "id";
                protected $deleteTime = "deleted";
                protected $defaultSoftDelete = 0;
 protected $createTime =  "created";
 protected $updateTime =  "modified";

    protected $table='admin_user_role';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];

	
    // 设置字段信息
    protected $schema = array (
  'id' => 'int',
  'user_id' => 'int',
  'role_id' => 'varchar',
  'deleted' => 'int',
  'created' => 'datetime',
  'modified' => 'datetime',
);
    protected $rule =  array (
  'add' => 
  array (
    'user_id|用户id' => 'require|integer',
    'role_id|角色id' => 'require',
  ),
  'edit' =>
  array (
    'id|主键' => 'require|integer',
    'user_id|用户id' => 'require|integer',
    'role_id|角色id' => 'require',
  ),
  'del' => 
  array (
    'id|ID' => 'require',
  ),
  'getone' => 
  array (
    'id|ID' => 'require',
  ),
);  
    /**
     * 表单输入类型 type
     * hidden:隐藏域 select：下拉 radio：单选按钮 text：文本 textarea：多行文本 file：上传
     * none：非表单 editor：富文本 checkbox：复选框 date:日期
     */
    protected function form_schema(){
    return array (
  0 => 
  array (
    'title' => '主键',
    'name' => 'id',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'hidden',
  ),
  1 => 
  array (
    'title' => '用户id',
    'name' => 'user_id',
    'description' => '',
      'enum' => (function () {
          $model = new AdminUser();
          $res = $model->field('id,username')->select()->toArray();
          return array_column($res, 'username', 'id');
      })(),
    'type' => 'select',
      'disabled'=>true,
  ),
  2 => 
  array (
    'title' => '角色id',
    'name' => 'role_id',
    'description' => '',
      'enum' => (function () {
          $model = new AdminRole();
          $res = $model->field('id,role_name')->select()->toArray();
          return array_column($res, 'role_name', 'id');
      })(),
    'type' => 'checkbox',
  ),
);
    }
    protected  function list_schema(){
    return array (
  0 => 
  array (
    'field' => 'id',
    'title' => '主键',
    'type' => 'checkbox',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  1 => 
  array (
    'field' => 'user_id',
    'title' => '用户id',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  2 => 
  array (
    'field' => 'role_id',
    'title' => '角色id',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  3 => 
  array (
    'field' => 'deleted',
    'title' => '是否删除',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
      0 => '未删除',
      1 => '已删除',
    ),
  ),
  4 => 
  array (
    'field' => 'created',
    'title' => '创建时间',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  5 => 
  array (
    'field' => 'modified',
    'title' => '修改时间',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  6 => 
  array (
    'title' => '操作',
    'minWidth' => 50,
    'templet' => '#lineDemo',
    'fixed' => 'right',
    'align' => 'center',
  ),
);
    }
    protected function filter_schema(){
    return array (
  0 => 
  array (
    'title' => '主键',
    'name' => 'id',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'hidden',
  ),
  1 => 
  array (
    'title' => '用户id',
    'name' => 'user_id',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
  ),
  2 => 
  array (
    'title' => '角色id',
    'name' => 'role_id',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
  ),
  3 => 
  array (
    'title' => '是否删除',
    'name' => 'deleted',
    'description' => '',
    'enum' => 
    array (
      0 => '未删除',
      1 => '已删除',
    ),
    'type' => 'none',
  ),
  4 => 
  array (
    'title' => '创建时间',
    'name' => 'created',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
  ),
  5 => 
  array (
    'title' => '修改时间',
    'name' => 'modified',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
  ),
);
    }
    public function getCondition($param) {
        return array (
  0 => 
  array (
    0 => 't.id',
    1 => '=',
    2 => $param['id'],
  ),
  1 => 
  array (
    0 => 't.user_id',
    1 => '=',
    2 => $param['user_id'],
  ),
  2 => 
  array (
    0 => 't.role_id',
    1 => '=',
    2 => $param['role_id'],
  ),
  3 => 
  array (
    0 => 't.deleted',
    1 => '=',
    2 => $param['deleted'],
  ),
  4 => 
  array (
    0 => 't.created',
    1 => '=',
    2 => $param['created'],
  ),
  5 => 
  array (
    0 => 't.modified',
    1 => '=',
    2 => $param['modified'],
  ),
);
    }
}