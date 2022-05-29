<?php
/**
 * 友情链接模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-17
 * Time: 23:56
 */
namespace app\appdal\model;
use think\Model;

class SiteFlink extends Model {
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

    protected $table='site_flink';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];

	
    // 设置字段信息
    protected $schema = array (
  'id' => 'int',
  'fname' => 'varchar',
  'furl' => 'varchar',
  'fdesc' => 'varchar',
  'created' => 'datetime',
  'modified' => 'datetime',
  'deleted' => 'int',
);
    protected $rule =  array (
  'add' => 
  array (
    'fname|友链站点名称' => 'require',
    'furl|友链url' => 'require|url',
    'fdesc|友链描述' => 'require',
  ),
  'edit' =>
  array (
    'id|Id' => 'require|integer',
    'fname|友链站点名称' => 'require',
    'furl|友链url' => 'require|url',
    'fdesc|友链描述' => 'require',
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
    'title' => 'Id',
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
    'title' => '友链站点名称',
    'name' => 'fname',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '友链url',
    'name' => 'furl',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '友链描述',
    'name' => 'fdesc',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
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
    'widget_type' => 'date',
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
    'widget_type' => 'date',
  ),
  6 => 
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
    protected  function list_schema(){
    return array (
  0 => 
  array (
    'field' => 'id',
    'title' => 'Id',
    'type' => 'checkbox',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  1 => 
  array (
    'field' => 'fname',
    'title' => '友链站点名称',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  2 => 
  array (
    'field' => 'furl',
    'title' => '友链url',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  3 => 
  array (
    'field' => 'fdesc',
    'title' => '友链描述',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
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
  7 => 
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
    'title' => 'Id',
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
    'title' => '友链站点名称',
    'name' => 'fname',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '友链url',
    'name' => 'furl',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '友链描述',
    'name' => 'fdesc',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
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
    'widget_type' => 'date',
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
    'widget_type' => 'date',
  ),
  6 => 
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
    0 => 't.fname',
    1 => '=',
    2 => $param['fname'],
  ),
  2 => 
  array (
    0 => 't.furl',
    1 => '=',
    2 => $param['furl'],
  ),
  3 => 
  array (
    0 => 't.fdesc',
    1 => '=',
    2 => $param['fdesc'],
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
  6 => 
  array (
    0 => 't.deleted',
    1 => '=',
    2 => $param['deleted'],
  ),
);
    }
}