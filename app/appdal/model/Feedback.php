<?php
/**
 * 留言模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:18
 */
namespace app\appdal\model;
use think\Model;

class Feedback extends Model {
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

    protected $table='feedback';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];

	
    // 设置字段信息
    public $schema = array (
  'id' => 'int',
  'title' => 'varchar',
  'username' => 'varchar',
  'tel' => 'varchar',
  'email' => 'varchar',
  'content' => 'text',
  'created' => 'datetime',
  'deleted' => 'int',
);
    public $rule =  array (
  'add' => 
  array (
    'title|留言标题' => 'require',
    'username|留言者姓名' => 'require',
    'tel|联系电话' => 'require',
    'email|联系邮箱' => 'require|email',
    'content|留言内容' => 'require',
  ),
  'edit' => 
  array (
    'id|主键id' => 'require|integer',
    'title|留言标题' => 'require',
    'username|留言者姓名' => 'require',
    'tel|联系电话' => 'require',
    'email|联系邮箱' => 'require|email',
    'content|留言内容' => 'require',
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
    public function form_schema(){
    return array (
  0 => 
  array (
    'title' => '主键id',
    'name' => 'id',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'hidden',
    'widget_type' => '',
  ),
  1 => 
  array (
    'title' => '留言标题',
    'name' => 'title',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '留言者姓名',
    'name' => 'username',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '联系电话',
    'name' => 'tel',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  4 => 
  array (
    'title' => '联系邮箱',
    'name' => 'email',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  5 => 
  array (
    'title' => '留言内容',
    'name' => 'content',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => 'editor',
  ),
  6 => 
  array (
    'title' => '创建时间',
    'name' => 'created',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
    'widget_type' => 'date',
  ),
  7 => 
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
    'widget_type' => '',
  ),
);
    }
    public  function list_schema(){
    return array (
  0 => 
  array (
    'field' => 'id',
    'title' => '主键id',
    'type' => 'checkbox',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  1 => 
  array (
    'field' => 'title',
    'title' => '留言标题',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  2 => 
  array (
    'field' => 'username',
    'title' => '留言者姓名',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  3 => 
  array (
    'field' => 'tel',
    'title' => '联系电话',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  4 => 
  array (
    'field' => 'email',
    'title' => '联系邮箱',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  5 => 
  array (
    'field' => 'content',
    'title' => '留言内容',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  6 => 
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
  7 => 
  array (
    'field' => 'deleted',
    'title' => '是否删除',
    'type' => 'normal',
    'hide' => true,
    'width' => '',
    'enum' => 
    array (
      0 => '未删除',
      1 => '已删除',
    ),
  ),
  8 => 
  array (
    'title' => '操作',
    'minWidth' => 50,
    'templet' => '#lineDemo',
    'fixed' => 'right',
    'align' => 'center',
  ),
);
    }
    public function filter_schema(){
    return array (
  0 => 
  array (
    'title' => '主键id',
    'name' => 'id',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'hidden',
    'widget_type' => '',
  ),
  1 => 
  array (
    'title' => '留言标题',
    'name' => 'title',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '留言者姓名',
    'name' => 'username',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '联系电话',
    'name' => 'tel',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  4 => 
  array (
    'title' => '联系邮箱',
    'name' => 'email',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  5 => 
  array (
    'title' => '留言内容',
    'name' => 'content',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
    'widget_type' => 'editor',
  ),
  6 => 
  array (
    'title' => '创建时间',
    'name' => 'created',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
    'widget_type' => 'date',
  ),
  7 => 
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
    'widget_type' => '',
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
    0 => 't.title',
    1 => '=',
    2 => $param['title'],
  ),
  2 => 
  array (
    0 => 't.username',
    1 => '=',
    2 => $param['username'],
  ),
  3 => 
  array (
    0 => 't.tel',
    1 => '=',
    2 => $param['tel'],
  ),
  4 => 
  array (
    0 => 't.email',
    1 => '=',
    2 => $param['email'],
  ),
  5 => 
  array (
    0 => 't.content',
    1 => '=',
    2 => $param['content'],
  ),
  6 => 
  array (
    0 => 't.created',
    1 => '=',
    2 => $param['created'],
  ),
  7 => 
  array (
    0 => 't.deleted',
    1 => '=',
    2 => $param['deleted'],
  ),
);
    }
}