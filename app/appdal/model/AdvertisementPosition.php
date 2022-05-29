<?php
/**
 * 广告位模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-24
 * Time: 23:24
 */
namespace app\appdal\model;
use think\Model;

class AdvertisementPosition extends Model {
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

    protected $table='advertisement_position';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];

	
    // 设置字段信息
    public $schema = array (
  'id' => 'int',
  'position_name' => 'varchar',
  'position_key' => 'varchar',
  'ad_width' => 'int',
  'ad_height' => 'int',
  'position_description' => 'varchar',
  'deleted' => 'int',
  'created' => 'datetime',
  'modified' => 'datetime',
);
    public $rule =  array (
  'add' => 
  array (
    'position_name|广告位名称' => 'require',
    'position_key|广告位标识' => 'require',
    'ad_width|广告宽度' => 'require|integer',
    'ad_height|广告高度' => 'require|integer',
    'position_description|广告描述' => 'require',
  ),
  'edit' => 
  array (
    'id|主键' => 'require|integer',
    'position_name|广告位名称' => 'require',
    'position_key|广告位标识' => 'require',
    'ad_width|广告宽度' => 'require|integer',
    'ad_height|广告高度' => 'require|integer',
    'position_description|广告描述' => 'require',
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
    'title' => '广告位名称',
    'name' => 'position_name',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '广告位标识',
    'name' => 'position_key',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '广告宽度',
    'name' => 'ad_width',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  4 => 
  array (
    'title' => '广告高度',
    'name' => 'ad_height',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  5 => 
  array (
    'title' => '广告描述',
    'name' => 'position_description',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
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
  7 => 
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
  8 => 
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
    'field' => 'position_name',
    'title' => '广告位名称',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  2 => 
  array (
    'field' => 'position_key',
    'title' => '广告位标识',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  3 => 
  array (
    'field' => 'ad_width',
    'title' => '广告宽度',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  4 => 
  array (
    'field' => 'ad_height',
    'title' => '广告高度',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  5 => 
  array (
    'field' => 'position_description',
    'title' => '广告描述',
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
    'field' => 'created',
    'title' => '创建时间',
    'type' => 'normal',
    'hide' => false,
    'width' => '',
    'enum' => 
    array (
    ),
  ),
  8 => 
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
  9 => 
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
    'title' => '广告位名称',
    'name' => 'position_name',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  2 => 
  array (
    'title' => '广告位标识',
    'name' => 'position_key',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'text',
    'widget_type' => '',
  ),
  3 => 
  array (
    'title' => '广告宽度',
    'name' => 'ad_width',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
    'widget_type' => '',
  ),
  4 => 
  array (
    'title' => '广告高度',
    'name' => 'ad_height',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
    'widget_type' => '',
  ),
  5 => 
  array (
    'title' => '广告描述',
    'name' => 'position_description',
    'description' => '',
    'enum' => 
    array (
    ),
    'type' => 'none',
    'widget_type' => '',
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
  7 => 
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
  8 => 
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
    0 => 't.position_name',
    1 => '=',
    2 => $param['position_name'],
  ),
  2 => 
  array (
    0 => 't.position_key',
    1 => '=',
    2 => $param['position_key'],
  ),
  3 => 
  array (
    0 => 't.ad_width',
    1 => '=',
    2 => $param['ad_width'],
  ),
  4 => 
  array (
    0 => 't.ad_height',
    1 => '=',
    2 => $param['ad_height'],
  ),
  5 => 
  array (
    0 => 't.position_description',
    1 => '=',
    2 => $param['position_description'],
  ),
  6 => 
  array (
    0 => 't.deleted',
    1 => '=',
    2 => $param['deleted'],
  ),
  7 => 
  array (
    0 => 't.created',
    1 => '=',
    2 => $param['created'],
  ),
  8 => 
  array (
    0 => 't.modified',
    1 => '=',
    2 => $param['modified'],
  ),
);
    }

    public static function onAfterDelete(self $model) {
        if(isset($model->id) and $model->id){
            $relate_data = AdvertisementList::where('position_id', $model->id)->find();
            $relate_data->delete();
        }
    }
}