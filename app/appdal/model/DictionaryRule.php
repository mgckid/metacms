<?php
/**
 * 字典规则模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-27
 * Time: 12:17
 */

namespace app\appdal\model;
use think\Model;

class DictionaryRule extends Model {
    use ModelOperation;
    /**
     * 主键id Tp默认主键为id,
     * 如果你没有使用id作为主键名，需要在这里设置真实的数据表id
     */
    protected $pk = "id";

    protected $table='dictionary_rule';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];

    // 设置字段信息
    public $schema = array(
        'id' => 'int',
        'dict_code' => 'varchar',
        'validate_rule' => 'varchar',
        'data_type' => 'varchar',
        'input_type' => 'varchar',
        'belong_to_table' => 'varchar',
        'insert_require' => 'tinyint',
        'update_require' => 'tinyint',
    );
    public $rule = array(
        'add' =>
            array(
                'dict_code|字典编码' => 'require',
                'input_type|表单输入类型' => 'require',
                'belong_to_table|存储位置' => 'require',
                'insert_require|插入必填' => 'integer',
                'update_require|修改必填' => 'integer',
            ),
        'edit' =>
            array(
                'id|主键id' => 'require|integer',
                'dict_code|字典编码' => 'require',
                'input_type|表单输入类型' => 'require',
                'belong_to_table|存储位置' => 'require',
                'insert_require|插入必填' => 'integer',
                'update_require|修改必填' => 'integer',
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
                    'title' => '主键id',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            1 =>
                array(
                    'title' => '字典名称',
                    'name' => 'dict_code',
                    'description' => '',
                    'enum' => (function () {
                        $res = CmsDictionary::field('dict_code,dict_name')->select()->toArray();
                        $res2 = Dictionary::field('dict_code,dict_name')->select()->toArray();
                        $res = $res + $res2;
                        return  array_column($res, 'dict_name', 'dict_code');
                    })(),
                    'type' => 'select',
                    'widget_type' => '',
                    'disabled'=>1,
                ),
            2 =>
                array(
                    'title' => '验证规则',
                    'name' => 'validate_rule',
                    'description' => '',
                    'enum' =>
                        array(
                            'require' => 'require',
                            'number' => 'number',
                            'integer' => 'integer',
                            'float' => 'float',
                            'bool' => 'bool',
                            'email' => 'email',
                            'date' => 'date',
                            'url' => 'url',
                            'mobile' => 'mobile',
                        ),
                    'type' => 'select_multi',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '字典数据类型',
                    'name' => 'data_type',
                    'description' => '',
                    'enum' =>
                        array(
                            'int' => '整形',
                            'varchar' => '字符串',
                            'text' => '富文本',
                            'longtext' => '长富文本',
                            'timestamp' => '时间戳',
                            'datetime' => '时间',
                            'date' => '日期',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '表单输入类型',
                    'name' => 'input_type',
                    'description' => '',
                    'enum' =>
                        array(
                            'hidden' => '隐藏域',
                            'select' => '下拉',
                            'radio' => '单选按钮',
                            'text' => '单行文本',
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
                array(
                    'title' => '存储位置',
                    'name' => 'belong_to_table',
                    'description' => '',
                    'enum' =>
                        array(
                            'cms_post' => '内容主表',
                            'cms_post_extend_attribute' => '扩展属性表',
                            'cms_post_extend_text' => '内容管理扩展内容表',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            6 =>
                array(
                    'title' => '插入必填',
                    'name' => 'insert_require',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '是',
                            2 => '否',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            7 =>
                array(
                    'title' => '修改必填',
                    'name' => 'update_require',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '是',
                            2 => '否',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
        );
    }

    public function list_schema() {
        return array(
            0 =>
                array(
                    'field' => 'id',
                    'title' => '主键id',
                    'type' => 'checkbox',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            1 =>
                array(
                    'field' => 'dict_code',
                    'title' => '字典编码',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            2 =>
                array(
                    'field' => 'validate_rule',
                    'title' => '验证规则',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            'require' => 'require',
                            'number' => 'number',
                            'integer' => 'integer',
                            'float' => 'float',
                            'bool' => 'bool',
                            'email' => 'email',
                            'date' => 'date',
                            'url' => 'url',
                            'mobile' => 'mobile',
                        ),
                ),
            3 =>
                array(
                    'field' => 'data_type',
                    'title' => '字典数据类型',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            'int' => '整形',
                            'varchar' => '字符串',
                            'text' => '富文本',
                            'longtext' => '长富文本',
                            'timestamp' => '时间戳',
                            'datetime' => '时间',
                            'date' => '日期',
                        ),
                ),
            4 =>
                array(
                    'field' => 'input_type',
                    'title' => '表单输入类型',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            'hidden' => '隐藏域',
                            'select' => '下拉',
                            'radio' => '单选按钮',
                            'text' => '单行文本',
                            'textarea' => '多行文本',
                            'file' => '上传',
                            'none' => '非表单',
                            'editor' => '富文本',
                            'checkbox' => '复选框',
                            'date' => '日期',
                        ),
                ),
            5 =>
                array(
                    'field' => 'belong_to_table',
                    'title' => '存储位置',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            'cms_post' => '内容主表',
                            'cms_post_extend_attribute' => '扩展属性表',
                            'cms_post_extend_text' => '内容管理扩展内容表',
                        ),
                ),
            6 =>
                array(
                    'field' => 'insert_require',
                    'title' => '插入必填',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            1 => '是',
                            2 => '否',
                        ),
                ),
            7 =>
                array(
                    'field' => 'update_require',
                    'title' => '修改必填',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            1 => '是',
                            2 => '否',
                        ),
                ),
            8 =>
                array(
                    'hide' => false,
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
                    'title' => '主键id',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            1 =>
                array(
                    'title' => '字典编码',
                    'name' => 'dict_code',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '验证规则',
                    'name' => 'validate_rule',
                    'description' => '',
                    'enum' =>
                        array(
                            'require' => 'require',
                            'number' => 'number',
                            'integer' => 'integer',
                            'float' => 'float',
                            'bool' => 'bool',
                            'email' => 'email',
                            'date' => 'date',
                            'url' => 'url',
                            'mobile' => 'mobile',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '字典数据类型',
                    'name' => 'data_type',
                    'description' => '',
                    'enum' =>
                        array(
                            'int' => '整形',
                            'varchar' => '字符串',
                            'text' => '富文本',
                            'longtext' => '长富文本',
                            'timestamp' => '时间戳',
                            'datetime' => '时间',
                            'date' => '日期',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '表单输入类型',
                    'name' => 'input_type',
                    'description' => '',
                    'enum' =>
                        array(
                            'hidden' => '隐藏域',
                            'select' => '下拉',
                            'radio' => '单选按钮',
                            'text' => '单行文本',
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
                array(
                    'title' => '存储位置',
                    'name' => 'belong_to_table',
                    'description' => '',
                    'enum' =>
                        array(
                            'cms_post' => '内容主表',
                            'cms_post_extend_attribute' => '扩展属性表',
                            'cms_post_extend_text' => '内容管理扩展内容表',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            6 =>
                array(
                    'title' => '插入必填',
                    'name' => 'insert_require',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '是',
                            2 => '否',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            7 =>
                array(
                    'title' => '修改必填',
                    'name' => 'update_require',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '是',
                            2 => '否',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
        );
    }

    public function getCondition($param) {
        $condition = array(
            0 =>
                array(
                    0 => 't.id',
                    1 => '=',
                    2 => $param['id'],
                ),
            1 =>
                array(
                    0 => 't.dict_code',
                    1 => '=',
                    2 => $param['dict_code'],
                ),
            2 =>
                array(
                    0 => 't.validate_rule',
                    1 => '=',
                    2 => $param['validate_rule'],
                ),
            3 =>
                array(
                    0 => 't.data_type',
                    1 => '=',
                    2 => $param['data_type'],
                ),
            4 =>
                array(
                    0 => 't.input_type',
                    1 => '=',
                    2 => $param['input_type'],
                ),
            5 =>
                array(
                    0 => 't.belong_to_table',
                    1 => '=',
                    2 => $param['belong_to_table'],
                ),
            6 =>
                array(
                    0 => 't.insert_require',
                    1 => '=',
                    2 => $param['insert_require'],
                ),
            7 =>
                array(
                    0 => 't.update_require',
                    1 => '=',
                    2 => $param['update_require'],
                ),
        );
        foreach ($condition as $key => $value) {
            if ($value[1] == '=' and !is_scalar($value[2])) {
                $value[1] = 'in';
            }
            $condition[$key] = $value;
        }
        return $condition;
    }

    public static function onBeforeWrite(self $model) {
        $model->validate_rule = str_replace(',', '|', $model->validate_rule);
    }
}