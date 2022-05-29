<?php
/**
 * 站点配置模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-03-07
 * Time: 19:58
 */
namespace app\appdal\model;
use think\Model;

class SiteConfig extends Model {
    use ModelOperation;
    use \think\model\concern\SoftDelete;
                protected $deleteTime = "deleted";
                protected $defaultSoftDelete = 0;
/**
                 * 主键id Tp默认主键为id,
                 * 如果你没有使用id作为主键名，需要在这里设置真实的数据表id
                 */
                protected $pk = "id"; protected $createTime =  "created";
 protected $updateTime =  "modified";

    protected $table='site_config';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];

	
    // 设置字段信息
    public $schema = array (
  'id' => 'int',
  'name' => 'varchar',
  'value' => 'varchar',
  'description' => '',
  'input_type' => 'varchar',
  'created' => 'datetime',
  'modified' => 'datetime',
  'deleted' => 'tinyint',
);
    public $rule =  array (
  'add' => 
  array (
    'name|配置名称' => 'require',
    'description|配置描述' => 'require',
    'input_type|表单类型' => 'require',
  ),
  'edit' => 
  array (
    'id|主键' => 'require|integer',
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
    'title' => '主键',
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
    'title' => '配置名称',
    'name' => 'name',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '配置值',
    'name' => 'value',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '配置描述',
    'name' => 'description',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  4 => 
  array (
    'title' => '表单类型',
    'name' => 'input_type',
    'description' => '',
    'enum' => 
    array (
      'hidden' => '隐藏域',
      'select' => '下拉',
      'radio' => '单选按钮',
      'text' => '文本',
      'textarea' => '多行文本',
      'file' => '上传',
      'none' => '非表单',
      'editor' => '富文本',
      'checkbox' => '复选框',
      'date' => '日期',
    ),
    'type' => 'select',
    'widget_type' => '',
  ),
  5 => 
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
  6 => 
  array (
    'title' => '修改时间',
    'name' => 'modified',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
    'widget_type' => 'date',
  ),
  7 => 
  array (
    'title' => '删除标记',
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
    'field' => 'name',
    'title' => '配置名称',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  2 => 
  array (
    'field' => 'value',
    'title' => '配置值',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  3 => 
  array (
    'field' => 'description',
    'title' => '配置描述',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  4 => 
  array (
    'field' => 'input_type',
    'title' => '表单类型',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
      'hidden' => '隐藏域',
      'select' => '下拉',
      'radio' => '单选按钮',
      'text' => '文本',
      'textarea' => '多行文本',
      'file' => '上传',
      'none' => '非表单',
      'editor' => '富文本',
      'checkbox' => '复选框',
      'date' => '日期',
    ),
  ),
  5 => 
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
  6 => 
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
  7 => 
  array (
    'field' => 'deleted',
    'title' => '删除标记',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
      0 => '未删除',
      1 => '已删除',
    ),
  ),
  8 => 
  array (
    'hide' => false,
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
    'title' => '主键',
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
    'title' => '配置名称',
    'name' => 'name',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '配置值',
    'name' => 'value',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '配置描述',
    'name' => 'description',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  4 => 
  array (
    'title' => '表单类型',
    'name' => 'input_type',
    'description' => '',
    'enum' => 
    array (
      'hidden' => '隐藏域',
      'select' => '下拉',
      'radio' => '单选按钮',
      'text' => '文本',
      'textarea' => '多行文本',
      'file' => '上传',
      'none' => '非表单',
      'editor' => '富文本',
      'checkbox' => '复选框',
      'date' => '日期',
    ),
    'type' => 'select',
    'widget_type' => '',
  ),
  5 => 
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
  6 => 
  array (
    'title' => '修改时间',
    'name' => 'modified',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
    'widget_type' => 'date',
  ),
  7 => 
  array (
    'title' => '删除标记',
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
    0 => 't.name',
    1 => '=',
    2 => $param['name'],
  ),
  2 => 
  array (
    0 => 't.value',
    1 => '=',
    2 => $param['value'],
  ),
  3 => 
  array (
    0 => 't.description',
    1 => '=',
    2 => $param['description'],
  ),
  4 => 
  array (
    0 => 't.input_type',
    1 => '=',
    2 => $param['input_type'],
  ),
  5 => 
  array (
    0 => 't.created',
    1 => '=',
    2 => $param['created'],
  ),
  6 => 
  array (
    0 => 't.modified',
    1 => '=',
    2 => $param['modified'],
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