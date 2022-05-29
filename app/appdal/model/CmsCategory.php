<?php
/**
 * 栏目模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-14
 * Time: 22:54
 */

namespace app\appdal\model;
use think\Model;

class CmsCategory extends Model {
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

    protected $table='cms_category';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];

    // 设置字段信息
    protected $schema = array(
        'id' => 'int',
        'pid' => 'int',
        'model_id' => 'varchar',
        'category_name' => 'varchar',
        'category_alias' => 'varchar',
        'category_type' => 'varchar',
        'keywords' => 'varchar',
        'description' => '',
        'list_template' => 'varchar',
        'detail_template' => 'varchar',
        'channel_template' => 'varchar',
        'content' => 'varchar',
        'sort' => 'int',
        'deleted' => 'int',
        'displayed' => 'int',
        'category_path' => 'varchar',
        'created' => 'datetime',
        'modified' => 'datetime',
    );
    protected $rule = array(
        'add' =>
            array(
                'pid|父级栏目' => 'require|integer',
                'model_id|模型id' => 'require',
                'category_name|栏目名称' => 'require',
                'category_alias|栏目别名' => 'require',
                'category_type|栏目类型' => 'require',
                'keywords|关键字' => 'require',
                'description|描述' => 'require',
                'list_template|列表页模版' => 'require',
                'detail_template|详情页模版' => 'require',
                'channel_template|频道页模版' => 'require',
                'content|栏目内容(类似关于我们这样的页面可以用到)' => 'require',
                'sort|排序值' => 'require|integer',
                'displayed|是否显示' => 'require|integer',
                'category_path|栏目别名路径' => 'require',
            ),
        'edit' =>
            array(
                'id|主键id' => 'require|integer',
                'pid|父级栏目' => 'require|integer',
                'model_id|模型id' => 'require',
                'category_name|栏目名称' => 'require',
                'category_alias|栏目别名' => 'require',
                'category_type|栏目类型' => 'require',
                'keywords|关键字' => 'require',
                'description|描述' => 'require',
                'list_template|列表页模版' => 'require',
                'detail_template|详情页模版' => 'require',
                'channel_template|频道页模版' => 'require',
                'content|栏目内容(类似关于我们这样的页面可以用到)' => 'require',
                'sort|排序值' => 'require|integer',
                'displayed|是否显示' => 'require|integer',
                'category_path|栏目别名路径' => 'require',
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
    protected function form_schema() {
        return array(
            0 =>
                array(
                    'title' => '主键id',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                ),
            1 =>
                array(
                    'title' => '父级栏目',
                    'name' => 'pid',
                    'description' => '',
                    'enum' => (function () {
                        $res = $this->field('id,pid,category_name')->select()->toArray();
                        $res = array_column($res, 'category_name', 'id');
                        $res[0]='一级栏目';
                        return $res;
                    })(),
                    'type' => 'select',
                ),
            2 =>
                array(
                    'title' => '模型id',
                    'name' => 'model_id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            3 =>
                array(
                    'title' => '栏目名称',
                    'name' => 'category_name',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            4 =>
                array(
                    'title' => '栏目别名',
                    'name' => 'category_alias',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            5 =>
                array(
                    'title' => '栏目类型',
                    'name' => 'category_type',
                    'description' => '',
                    'enum' =>
                        array(
                            'list' => '最终列表栏目',
                            'channel' => '频道封面',
                        ),
                    'type' => 'checkbox',
                ),
            6 =>
                array(
                    'title' => '关键字',
                    'name' => 'keywords',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            7 =>
                array(
                    'title' => '描述',
                    'name' => 'description',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'textarea',
                ),
            8 =>
                array(
                    'title' => '列表页模版',
                    'name' => 'list_template',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            9 =>
                array(
                    'title' => '详情页模版',
                    'name' => 'detail_template',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            10 =>
                array(
                    'title' => '频道页模版',
                    'name' => 'channel_template',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            11 =>
                array(
                    'title' => '栏目内容(类似关于我们这样的页面可以用到)',
                    'name' => 'content',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'editor',
                ),
            12 =>
                array(
                    'title' => '排序值',
                    'name' => 'sort',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            13 =>
                array(
                    'title' => '是否删除',
                    'name' => 'deleted',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            14 =>
                array(
                    'title' => '是否显示',
                    'name' => 'displayed',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '展示',
                            2 => '隐藏',
                        ),
                    'type' => 'checkbox',
                ),
            15 =>
                array(
                    'title' => '栏目别名路径',
                    'name' => 'category_path',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            16 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            17 =>
                array(
                    'title' => '修改时间',
                    'name' => 'modified',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
        );
    }

    protected function list_schema() {
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
                    'field' => 'pid',
                    'title' => '父级栏目',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            2 =>
                array(
                    'field' => 'model_id',
                    'title' => '模型id',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            3 =>
                array(
                    'field' => 'category_name',
                    'title' => '栏目名称',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            4 =>
                array(
                    'field' => 'category_alias',
                    'title' => '栏目别名',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            5 =>
                array(
                    'field' => 'category_type',
                    'title' => '栏目类型',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            'list' => '最终列表栏目',
                            'channel' => '频道封面',
                        ),
                ),
            6 =>
                array(
                    'field' => 'keywords',
                    'title' => '关键字',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            7 =>
                array(
                    'field' => 'description',
                    'title' => '描述',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            8 =>
                array(
                    'field' => 'list_template',
                    'title' => '列表页模版',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            9 =>
                array(
                    'field' => 'detail_template',
                    'title' => '详情页模版',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            10 =>
                array(
                    'field' => 'channel_template',
                    'title' => '频道页模版',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            11 =>
                array(
                    'field' => 'content',
                    'title' => '栏目内容(类似关于我们这样的页面可以用到)',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            12 =>
                array(
                    'field' => 'sort',
                    'title' => '排序值',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            13 =>
                array(
                    'field' => 'deleted',
                    'title' => '是否删除',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            14 =>
                array(
                    'field' => 'displayed',
                    'title' => '是否显示',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            1 => '展示',
                            2 => '隐藏',
                        ),
                ),
            15 =>
                array(
                    'field' => 'category_path',
                    'title' => '栏目别名路径',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            16 =>
                array(
                    'field' => 'created',
                    'title' => '创建时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            17 =>
                array(
                    'field' => 'modified',
                    'title' => '修改时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            18 =>
                array(
                    'title' => '操作',
                    'minWidth' => 50,
                    'templet' => '#lineDemo',
                    'fixed' => 'right',
                    'align' => 'center',
                ),
        );
    }

    protected function filter_schema() {
        return array(
            0 =>
                array(
                    'title' => '主键id',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                ),
            1 =>
                array(
                    'title' => '父级栏目',
                    'name' => 'pid',
                    'description' => '',
                    'enum' => (function () {
                        $res = $this->field('id,pid,category_name')->select()->toArray();
                        $res = array_column($res, 'category_name', 'id');
                        return $res;
                    })(),
                    'type' => 'select',
                ),
            2 =>
                array(
                    'title' => '模型id',
                    'name' => 'model_id',
                    'description' => '',
                    'enum' => (function () {
                        $res = CmsDictionary::where('dict_level',1)
                            ->field('dict_name,dict_value')->select()->toArray();
                        $res = array_column($res, 'dict_name', 'dict_value');
                        return $res;
                    })(),
                    'type' => 'select',
                ),
            3 =>
                array(
                    'title' => '栏目名称',
                    'name' => 'category_name',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            4 =>
                array(
                    'title' => '栏目别名',
                    'name' => 'category_alias',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                ),
            5 =>
                array(
                    'title' => '栏目类型',
                    'name' => 'category_type',
                    'description' => '',
                    'enum' =>
                        array(
                            'list' => '最终列表栏目',
                            'channel' => '频道封面',
                        ),
                    'type' => 'select',
                ),
            6 =>
                array(
                    'title' => '关键字',
                    'name' => 'keywords',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            7 =>
                array(
                    'title' => '描述',
                    'name' => 'description',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            8 =>
                array(
                    'title' => '列表页模版',
                    'name' => 'list_template',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            9 =>
                array(
                    'title' => '详情页模版',
                    'name' => 'detail_template',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            10 =>
                array(
                    'title' => '频道页模版',
                    'name' => 'channel_template',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            11 =>
                array(
                    'title' => '栏目内容(类似关于我们这样的页面可以用到)',
                    'name' => 'content',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            12 =>
                array(
                    'title' => '排序值',
                    'name' => 'sort',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            13 =>
                array(
                    'title' => '是否删除',
                    'name' => 'deleted',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            14 =>
                array(
                    'title' => '是否显示',
                    'name' => 'displayed',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '展示',
                            2 => '隐藏',
                        ),
                    'type' => 'select',
                ),
            15 =>
                array(
                    'title' => '栏目别名路径',
                    'name' => 'category_path',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            16 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                ),
            17 =>
                array(
                    'title' => '修改时间',
                    'name' => 'modified',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
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
                    0 => 't.model_id',
                    1 => '=',
                    2 => $param['model_id'],
                ),
            3 =>
                array(
                    0 => 't.category_name',
                    1 => '=',
                    2 => $param['category_name'],
                ),
            4 =>
                array(
                    0 => 't.category_alias',
                    1 => '=',
                    2 => $param['category_alias'],
                ),
            5 =>
                array(
                    0 => 't.category_type',
                    1 => '=',
                    2 => $param['category_type'],
                ),
            6 =>
                array(
                    0 => 't.keywords',
                    1 => '=',
                    2 => $param['keywords'],
                ),
            7 =>
                array(
                    0 => 't.description',
                    1 => '=',
                    2 => $param['description'],
                ),
            8 =>
                array(
                    0 => 't.list_template',
                    1 => '=',
                    2 => $param['list_template'],
                ),
            9 =>
                array(
                    0 => 't.detail_template',
                    1 => '=',
                    2 => $param['detail_template'],
                ),
            10 =>
                array(
                    0 => 't.channel_template',
                    1 => '=',
                    2 => $param['channel_template'],
                ),
            11 =>
                array(
                    0 => 't.content',
                    1 => '=',
                    2 => $param['content'],
                ),
            12 =>
                array(
                    0 => 't.sort',
                    1 => '=',
                    2 => $param['sort'],
                ),
            13 =>
                array(
                    0 => 't.deleted',
                    1 => '=',
                    2 => $param['deleted'],
                ),
            14 =>
                array(
                    0 => 't.displayed',
                    1 => '=',
                    2 => $param['displayed'],
                ),
            15 =>
                array(
                    0 => 't.category_path',
                    1 => '=',
                    2 => $param['category_path'],
                ),
            16 =>
                array(
                    0 => 't.created',
                    1 => '=',
                    2 => $param['created'],
                ),
            17 =>
                array(
                    0 => 't.modified',
                    1 => '=',
                    2 => $param['modified'],
                ),
        );
    }
}