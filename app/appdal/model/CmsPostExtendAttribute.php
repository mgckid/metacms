<?php
/**
 * 扩展属性模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-03-01
 * Time: 10:06
 */
namespace app\appdal\model;
use think\Model;

class CmsPostExtendAttribute extends Model {
    use ModelOperation;
    use \think\model\concern\SoftDelete;
                protected $deleteTime = "deleted";
                protected $defaultSoftDelete = 0;
/**
                 * 主键id Tp默认主键为id,
                 * 如果你没有使用id作为主键名，需要在这里设置真实的数据表id
                 */
                protected $pk = "id";

    protected $table='cms_post_extend_attribute';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];
	
    // 设置字段信息
    public $schema = array (
  'id' => 'int',
  'post_id' => 'varchar',
  'field' => 'varchar',
  'value' => 'varchar',
  'deleted' => 'int',
);
    public $rule =  array (
  'add' => 
  array (
    'post_id|文档id' => 'require',
    'field|扩展字段名' => 'require',
    'value|扩展字段值' => 'require',
  ),
  'edit' => 
  array (
    'id|自增主键' => 'require|integer',
    'post_id|文档id' => 'require',
    'field|扩展字段名' => 'require',
    'value|扩展字段值' => 'require',
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
    'title' => '自增主键',
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
    'title' => '文档id',
    'name' => 'post_id',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '扩展字段名',
    'name' => 'field',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '扩展字段值',
    'name' => 'value',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  4 => 
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
    'title' => '自增主键',
    'type' => 'checkbox',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  1 => 
  array (
    'field' => 'post_id',
    'title' => '文档id',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  2 => 
  array (
    'field' => 'field',
    'title' => '扩展字段名',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  3 => 
  array (
    'field' => 'value',
    'title' => '扩展字段值',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  4 => 
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
  5 => 
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
    'title' => '自增主键',
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
    'title' => '文档id',
    'name' => 'post_id',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '扩展字段名',
    'name' => 'field',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '扩展字段值',
    'name' => 'value',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  4 => 
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
    0 => 't.post_id',
    1 => '=',
    2 => $param['post_id'],
  ),
  2 => 
  array (
    0 => 't.field',
    1 => '=',
    2 => $param['field'],
  ),
  3 => 
  array (
    0 => 't.value',
    1 => '=',
    2 => $param['value'],
  ),
  4 => 
  array (
    0 => 't.deleted',
    1 => '=',
    2 => $param['deleted'],
  ),
);
    }
}