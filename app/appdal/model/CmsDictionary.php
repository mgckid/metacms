<?php
/**
 * 内容模型管理模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-22
 * Time: 23:35
 */

namespace app\appdal\model;

use think\Model;
use think\model\relation\HasOne;

class CmsDictionary extends Model {
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

    protected $table='cms_dictionary';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];


    // 设置字段信息
    public $schema = array(
        'id' => 'int',
        'pid' => 'int',
        'dict_code' => 'varchar',
        'dict_name' => 'varchar',
        'dict_value' => 'varchar',
        'created' => 'datetime',
        'modified' => 'datetime',
        'deleted' => 'int',
        'path' => 'varchar',
        'dict_level' => 'tinyint',
    );
    public $rule = array(
        'add' =>
            array(
                'pid|父级id' => 'require|integer',
                //'dict_code|字典编码' => 'require',
                'dict_name|字典名称' => 'require',
                'dict_value|字典值' => 'require',
                //'path|字典路径' => 'require',
                'dict_level|字典层级' => 'require|integer',
            ),
        'edit' =>
            array(
                'id|Id' => 'require|integer',
                'pid|父级id' => 'require|integer',
                'dict_name|字典名称' => 'require',
                'dict_value|字典值' => 'require',
                'dict_level|字典层级' => 'require|integer',
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
                    'title' => '父级id',
                    'name' => 'pid',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '字典编码',
                    'name' => 'dict_code',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '字典名称',
                    'name' => 'dict_name',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '字典值',
                    'name' => 'dict_value',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            5 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            6 =>
                array(
                    'title' => '修改时间',
                    'name' => 'modified',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            7 =>
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
            8 =>
                array(
                    'title' => '字典路径',
                    'name' => 'path',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            9 =>
                array(
                    'title' => '字典层级',
                    'name' => 'dict_level',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '字典',
                            2 => '属性',
                            3 => '属性枚举值',
                        ),
                    'type' => 'hidden',
                    'widget_type' => '',
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
                    'field' => 'pid',
                    'title' => '父级id',
                    'type' => 'normal',
                    'hide' => 1,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            2 =>
                array(
                    'field' => 'dict_code',
                    'title' => '字典编码',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            3 =>
                array(
                    'field' => 'dict_name',
                    'title' => '字典名称',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            4 =>
                array(
                    'field' => 'dict_value',
                    'title' => '字典值',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            5 =>
                array(
                    'field' => 'created',
                    'title' => '创建时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            6 =>
                array(
                    'field' => 'modified',
                    'title' => '修改时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            7 =>
                array(
                    'field' => 'deleted',
                    'title' => '是否删除',
                    'type' => 'normal',
                    'hide' => 1,
                    'width' => '',
                    'enum' =>
                        array(
                            0 => '未删除',
                            1 => '已删除',
                        ),
                ),
            8 =>
                array(
                    'field' => 'path',
                    'title' => '字典路径',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            9 =>
                array(
                    'field' => 'dict_level',
                    'title' => '字典层级',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            1 => '字典',
                            2 => '属性',
                            3 => '属性枚举值',
                        ),
                ),
            10 =>
                array(
                    'title' => '操作',
                    'minWidth' => 150,
                    'templet' => '#lineDemo',
                    'fixed' => 'right',
                    'align' => 'left',
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
                    'title' => '父级id',
                    'name' => 'pid',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '字典编码',
                    'name' => 'dict_code',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '字典名称',
                    'name' => 'dict_name',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '字典值',
                    'name' => 'dict_value',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            5 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            6 =>
                array(
                    'title' => '修改时间',
                    'name' => 'modified',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            7 =>
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
            8 =>
                array(
                    'title' => '字典路径',
                    'name' => 'path',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            9 =>
                array(
                    'title' => '字典层级',
                    'name' => 'dict_level',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '字典',
                            2 => '属性',
                            3 => '属性枚举值',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
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
                    0 => 't.dict_code',
                    1 => '=',
                    2 => $param['dict_code'],
                ),
            3 =>
                array(
                    0 => 't.dict_name',
                    1 => '=',
                    2 => $param['dict_name'],
                ),
            4 =>
                array(
                    0 => 't.dict_value',
                    1 => '=',
                    2 => $param['dict_value'],
                ),
            5 =>
                array(
                    0 => 't.created',
                    1 => '=',
                    2 => $param['created'],
                ),
            6 =>
                array(
                    0 => 't.modified',
                    1 => '=',
                    2 => $param['modified'],
                ),
            7 =>
                array(
                    0 => 't.deleted',
                    1 => '=',
                    2 => $param['deleted'],
                ),
            8 =>
                array(
                    0 => 't.path',
                    1 => '=',
                    2 => $param['path'],
                ),
            9 =>
                array(
                    0 => 't.dict_level',
                    1 => '=',
                    2 => $param['dict_level'],
                ),
        );
    }

    public static function onBeforeWrite(self $model) {
        $pid_res = $model->pid ? self::find($model->pid) : [];
        $model->path = $pid_res ? $pid_res->path . '->' . $model->dict_value : $model->dict_value;
        $model->dict_code = md5($model->path);
    }

    public static function onAfterWrite(self $model) {
        if ($model->dict_level == 1) {
            $cmsPostModel = new CmsPost();
            $schema = $cmsPostModel->schema;
            $form_schema = array_column($cmsPostModel->form_schema(), null, 'name');
            foreach ($form_schema as $value) {
                $insert_require = $update_require = $validate_rule = '';
                foreach ($cmsPostModel->rule['add'] as $k => $item) {
                    if (strpos($k, $value['name']) === 0) {
                        if (strpos($item, 'require') !== false) {
                            $insert_require = 1;
                        } else {
                            $insert_require = 2;
                        }
                        $validate_rule = trim(str_replace('require', '', $item), '|');
                    } else {
                        continue;
                    }
                }
                foreach ($cmsPostModel->rule['edit'] as $k => $item) {
                    if (strpos($k, $value['name']) === 0) {
                        if (strpos($item, 'require') !== false) {
                            $update_require = 1;
                        } else {
                            $update_require = 2;
                        }
                    } else {
                        continue;
                    }
                }
                $dictionary = [
                    'pid' => $model->id,
                    'dict_code' => md5($model->path . '->' . $value['name']),
                    'dict_name' => $value['title'],
                    'dict_value' => $value['name'],
                    'path' => $model->path . '->' . $value['name'],
                    'dict_level' => 2
                ];
                $dictionary_rule = [
                    'dict_code' => $dictionary['dict_code'],
                    'validate_rule' => $validate_rule,
                    'data_type' => $schema[$dictionary['dict_value']],
                    'input_type' => $value['type'],
                    'belong_to_table' => 'cms_post',
                    'insert_require' => $insert_require,
                    'update_require' => $update_require,
                ];
                $dictionary_model = self::where(['dict_code' => $dictionary['dict_code']])->find() ?: new self();
                $dictionary_model->save($dictionary);
                $dictionary_rule_model = DictionaryRule::where(['dict_code' => $dictionary['dict_code']])->find() ?: new DictionaryRule();
                $dictionary_rule_model->save($dictionary_rule);
                foreach ($value['enum'] as $k => $item) {
                    $son_data = [
                        'pid' => $dictionary_model->id,
                        'dict_code' => md5($model->path . '->' . $value['name'] . '->' . $k),
                        'dict_name' => $item,
                        'dict_value' => $k,
                        'path' => $model->path . '->' . $value['name'] . '->' . $k,
                        'dict_level' => 3
                    ];
                    $son_model = self::where(['dict_code' => $son_data['dict_code']])->find() ?: new self();
                    $son_model->save($son_data);
                }
            }
        }elseif($model->dict_level==2){
            $dictionary_rule = [
                'dict_code' => $model->dict_code,
            ];
            $dictionary_rule_model = DictionaryRule::where(['dict_code' => $dictionary_rule['dict_code']])->find() ?: new DictionaryRule();
            $dictionary_rule_model->save($dictionary_rule);
        }
    }

    public function dictionaryRule(){
        return $this->hasOne(DictionaryRule::class, 'dict_code', 'dict_code');
    }
}