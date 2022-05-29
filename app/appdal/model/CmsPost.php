<?php
/**
 * 文档模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-02-20
 * Time: 13:28
 */

namespace app\appdal\model;

use think\Db;
use think\db\Query;
use think\facade\Config;
use think\Model;

class CmsPost extends Model {
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

    protected $table = 'cms_post';
    protected $suffix = '';
    //protected $connection='db';
    protected $strict = true;
    protected $disuse = [];

    // 设置字段信息
    public $schema = array(
        'id' => 'int',
        'post_id' => 'varchar',
        'category_id' => 'int',
        'model_id' => 'varchar',
        'title' => 'varchar',
        'keywords' => 'varchar',
        'description' => '',
        'main_image' => 'varchar',
        'post_tag' => 'varchar',
        'click' => 'int',
        'author' => 'varchar',
        'is_publish' => 'tinyint',
        'is_recommed' => 'varchar',
        'deleted' => 'tinyint',
        'sort' => 'int',
        'created' => 'datetime',
        'modified' => 'datetime',
    );
    public $rule = array(
        'add' =>
            array(
                'post_id|文档id' => 'require',
                'category_id|所属栏目' => 'require|integer',
                'model_id|所属模型' => 'require',
                'title|文档标题' => 'require',
                'keywords|文档关键词' => 'require',
                'description|文档描述' => 'require',
                'main_image|文档主图' => 'url',
                'post_tag|文档标签，(,隔开)' => 'require',
                'click|点击数' => 'require|integer',
                'author|作者' => 'require',
                'is_publish|是否发布' => 'require|integer',
                'is_recommed|是否推荐' => 'require',
                'sort|文档排序' => 'require|integer',
            ),
        'edit' =>
            array(
                'id|自增主键' => 'require|integer',
                'post_id|文档id' => 'require',
                'category_id|所属栏目' => 'require|integer',
                'model_id|所属模型' => 'require',
                'title|文档标题' => 'require',
                'keywords|文档关键词' => 'require',
                'description|文档描述' => 'require',
                'main_image|文档主图' => 'url',
                'post_tag|文档标签，(,隔开)' => 'require',
                'click|点击数' => 'require|integer',
                'author|作者' => 'require',
                'is_publish|是否发布' => 'require|integer',
                'is_recommed|是否推荐' => 'require',
                'sort|文档排序' => 'require|integer',
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
                    'title' => '自增主键',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            1 =>
                array(
                    'title' => '文档id',
                    'name' => 'post_id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '所属栏目',
                    'name' => 'category_id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '所属模型',
                    'name' => 'model_id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '文档标题',
                    'name' => 'title',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            5 =>
                array(
                    'title' => '文档关键词',
                    'name' => 'keywords',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            6 =>
                array(
                    'title' => '文档描述',
                    'name' => 'description',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            7 =>
                array(
                    'title' => '文档主图',
                    'name' => 'main_image',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'file',
                    'widget_type' => '',
                ),
            8 =>
                array(
                    'title' => '文档标签(,隔开)',
                    'name' => 'post_tag',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            9 =>
                array(
                    'title' => '点击数',
                    'name' => 'click',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            10 =>
                array(
                    'title' => '作者',
                    'name' => 'author',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            11 =>
                array(
                    'title' => '是否发布',
                    'name' => 'is_publish',
                    'description' => '',
                    'enum' =>
                        array(
                            0 => '未发布',
                            1 => '已发布',
                        ),
                    'type' => 'radio',
                    'widget_type' => '',
                ),
            12 =>
                array(
                    'title' => '是否推荐',
                    'name' => 'is_recommed',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '一级推荐',
                            2 => '二级推荐',
                            3 => '三级推荐',
                            4 => '四级推荐',
                        ),
                    'type' => 'checkbox',
                    'widget_type' => '',
                ),
            13 =>
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
            14 =>
                array(
                    'title' => '文档排序',
                    'name' => 'sort',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            15 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            16 =>
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
                    'title' => '自增主键',
                    'type' => 'checkbox',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            1 =>
                array(
                    'field' => 'post_id',
                    'title' => '文档id',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            2 =>
                array(
                    'field' => 'category_id',
                    'title' => '所属栏目',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' => (function () {
                        $res = CmsCategory::field('category_name,id')->select()->toArray();
                        return array_column($res, 'category_name', 'id');
                    })()
                ),
            3 =>
                array(
                    'field' => 'model_id',
                    'title' => '所属模型',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' => (function () {
                        $res = CmsDictionary::field('dict_name,dict_value')->where(['pid' => 0])->select()->toArray();
                        return array_column($res, 'dict_name', 'dict_value');
                    })(),
                ),
            4 =>
                array(
                    'field' => 'title',
                    'title' => '文档标题',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            5 =>
                array(
                    'field' => 'keywords',
                    'title' => '文档关键词',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            6 =>
                array(
                    'field' => 'description',
                    'title' => '文档描述',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            7 =>
                array(
                    'field' => 'main_image',
                    'title' => '文档主图',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            8 =>
                array(
                    'field' => 'post_tag',
                    'title' => '文档标签，(,隔开)',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            9 =>
                array(
                    'field' => 'click',
                    'title' => '点击数',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            10 =>
                array(
                    'field' => 'author',
                    'title' => '作者',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            11 =>
                array(
                    'field' => 'is_publish',
                    'title' => '是否发布',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            0 => '未发布',
                            1 => '已发布',
                        ),
                ),
            12 =>
                array(
                    'field' => 'is_recommed',
                    'title' => '是否推荐',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(
                            1 => '一级推荐',
                            2 => '二级推荐',
                            3 => '三级推荐',
                            4 => '四级推荐',
                        ),
                ),
            13 =>
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
            14 =>
                array(
                    'field' => 'sort',
                    'title' => '文档排序',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            15 =>
                array(
                    'field' => 'created',
                    'title' => '创建时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            16 =>
                array(
                    'field' => 'modified',
                    'title' => '修改时间',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array(),
                ),
            17 =>
                array(
                    'hide' => true,
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
                    'title' => '自增主键',
                    'name' => 'id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            1 =>
                array(
                    'title' => '文档id',
                    'name' => 'post_id',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            2 =>
                array(
                    'title' => '所属栏目',
                    'name' => 'category_id',
                    'description' => '',
                    'enum' => (function () {
                        $res = CmsCategory::field('category_name,id')->select()->toArray();
                        return array_column($res, 'category_name', 'id');
                    })(),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            3 =>
                array(
                    'title' => '所属模型',
                    'name' => 'model_id',
                    'description' => '',
                    'enum' => (function () {
                        $res = CmsDictionary::field('dict_name,dict_value')->where(['pid' => 0])->select()->toArray();
                        return array_column($res, 'dict_name', 'dict_value');
                    })(),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            4 =>
                array(
                    'title' => '文档标题',
                    'name' => 'title',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            5 =>
                array(
                    'title' => '文档关键词',
                    'name' => 'keywords',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            6 =>
                array(
                    'title' => '文档描述',
                    'name' => 'description',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            7 =>
                array(
                    'title' => '文档主图',
                    'name' => 'main_image',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            8 =>
                array(
                    'title' => '文档标签，(,隔开)',
                    'name' => 'post_tag',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            9 =>
                array(
                    'title' => '点击数',
                    'name' => 'click',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            10 =>
                array(
                    'title' => '作者',
                    'name' => 'author',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            11 =>
                array(
                    'title' => '是否发布',
                    'name' => 'is_publish',
                    'description' => '',
                    'enum' =>
                        array(
                            0 => '未发布',
                            1 => '已发布',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            12 =>
                array(
                    'title' => '是否推荐',
                    'name' => 'is_recommed',
                    'description' => '',
                    'enum' =>
                        array(
                            1 => '一级推荐',
                            2 => '二级推荐',
                            3 => '三级推荐',
                            4 => '四级推荐',
                        ),
                    'type' => 'select',
                    'widget_type' => '',
                ),
            13 =>
                array(
                    'title' => '是否删除',
                    'name' => 'deleted',
                    'description' => '',
                    'enum' =>
                        array(
                            0 => '未删除',
                            1 => '已删除',
                        ),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            14 =>
                array(
                    'title' => '文档排序',
                    'name' => 'sort',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => '',
                ),
            15 =>
                array(
                    'title' => '创建时间',
                    'name' => 'created',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'none',
                    'widget_type' => 'date',
                ),
            16 =>
                array(
                    'title' => '修改时间',
                    'name' => 'modified',
                    'description' => '',
                    'enum' =>
                        array(),
                    'type' => 'hidden',
                    'widget_type' => 'date',
                ),
        );
    }

    public function getCondition($param) {
        $res = array(
            0 =>
                array(
                    0 => 't.id',
                    1 => '=',
                    2 => $param['id'],
                ),
            1 =>
                array(
                    0 => 't.post_id',
                    1 => '=',
                    2 => $param['post_id'],
                ),
            2 =>
                array(
                    0 => 't.category_id',
                    1 => '=',
                    2 => $param['category_id'],
                ),
            3 =>
                array(
                    0 => 't.model_id',
                    1 => '=',
                    2 => $param['model_id'],
                ),
            4 =>
                array(
                    0 => 't.title',
                    1 => '=',
                    2 => $param['title'],
                ),
            5 =>
                array(
                    0 => 't.keywords',
                    1 => '=',
                    2 => $param['keywords'],
                ),
            6 =>
                array(
                    0 => 't.description',
                    1 => '=',
                    2 => $param['description'],
                ),
            7 =>
                array(
                    0 => 't.main_image',
                    1 => '=',
                    2 => $param['main_image'],
                ),
            8 =>
                array(
                    0 => 't.post_tag',
                    1 => '=',
                    2 => $param['post_tag'],
                ),
            9 =>
                array(
                    0 => 't.click',
                    1 => '=',
                    2 => $param['click'],
                ),
            10 =>
                array(
                    0 => 't.author',
                    1 => '=',
                    2 => $param['author'],
                ),
            11 =>
                array(
                    0 => 't.is_publish',
                    1 => '=',
                    2 => $param['is_publish'],
                ),
            12 =>
                array(
                    0 => 't.is_recommed',
                    1 => '=',
                    2 => $param['is_recommed'],
                ),
            13 =>
                array(
                    0 => 't.deleted',
                    1 => '=',
                    2 => $param['deleted'],
                ),
            14 =>
                array(
                    0 => 't.sort',
                    1 => '=',
                    2 => $param['sort'],
                ),
            15 =>
                array(
                    0 => 't.created',
                    1 => '=',
                    2 => $param['created'],
                ),
            16 =>
                array(
                    0 => 't.modified',
                    1 => '=',
                    2 => $param['modified'],
                ),
        );
        if ($param['keyword'] ?? '') {
            $res[] = array(
                't.title|t.keywords|t.description',
                'like',
                "%{$param['keyword']}%",
            );
        }
        return $res;
    }

    public function getone($param) {
        try {
            $res = (array)$this->get_model_db($param)
                ->find()->toArray();
            $res = array_merge($res, $this->getOneExtend($res));
            $data = [];
            $data['record'] = $res ? current($this->formatRecord([$res])) : [];
            return success($data);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function getFormInit($param) {
        $validate = validate(['model_id|所属模型' => 'require'], [], true, false);
        if ($validate->check($param) === false) {
            throw new \Exception(join(',', $validate->getError()));
        }
        $cms_dictionary = array_column($this->getCmsDictionary($param['model_id']), null, 'name');
        $cms_dictionary['model_id']['enum'] = (function () {
            $res = CmsDictionary::where('pid', 0)->select()->toArray();
            return array_column($res, 'dict_name', 'dict_value');
        })();
        $cms_dictionary['category_id']['enum'] = (function () {
            $res = CmsCategory::field('id,category_name')->select()->toArray();
            return array_column($res, 'category_name', 'id');
        })();
        return success(['record' => array_values($cms_dictionary)]);
    }

    private function getCmsDictionary($model_id) {
        $sql = "select b.*,a.* from cms_dictionary a 
        left join dictionary_rule b on b.dict_code = a.dict_code 
        where 	a.path like '$model_id%'";
        $model_result = \think\facade\Db::query($sql);
        $data = [];
        foreach ($model_result as $value) {
            if ($value['dict_level'] == 2)
                $data[$value['id']] = array(
                    'title' => $value['dict_name'],
                    'name' => $value['dict_value'],
                    'description' => $value['dict_name'],
                    'enum' => array(),
                    'type' => $value['input_type'],
                    'belong_to_table' => $value['belong_to_table'],
                    'dict_value' => $value['dict_value'],
                );
        }
        foreach ($model_result as $value) {
            if (isset($data[$value['pid']])) {
                $data[$value['pid']]['enum'][$value['dict_value']] = $value['dict_name'];
            }
        }
        return $data;
    }

    private function getOneExtend($cms_post_data) {
        $model_result = $this->getCmsDictionary($cms_post_data['model_id']);
        if (!$model_result) {
            return [];
        }
        $cms_post_extend_field = [];
        $join_cond = [];
        foreach ($model_result as $value) {
            if ($value['belong_to_table'] == 'cms_post_extend_attribute') {
                $cms_post_extend_field[] = "max((case WHEN b.field = '{$value['dict_value']}' then b.value ELSE '' end)) as '{$value['dict_value']}'";
                $join_cond[] = "left join {$value['belong_to_table']} as b on b.post_id = a.post_id";
            } elseif ($value['belong_to_table'] == 'cms_post_extend_text') {
                $cms_post_extend_field[] = "max((case WHEN c.field = '{$value['dict_value']}' then c.value ELSE '' end)) as '{$value['dict_value']}'";
                $join_cond[] = "left join {$value['belong_to_table']} as c on c.post_id = a.post_id";
            }
        }
        $cms_post_extend_field = join(',', $cms_post_extend_field);
        $join_cond = join(' ', array_unique($join_cond));
        $sql = "select a.post_id,%s from cms_post as a %s where a.post_id = '%s' GROUP BY a.post_id";
        $sql = sprintf($sql, $cms_post_extend_field, $join_cond, $cms_post_data['post_id']);
        $result = \think\facade\Db::query($sql);
        return current($result);
    }

    public function getRule($model_id, $operation) {
        if (!$model_id)
            throw new \Exception('文档模型不能为空');
        if (!$operation or !in_array($operation, ['add', 'edit', 'del', 'getone']))
            throw new \Exception('只允许(add,edit,del,getone)其中操作');
        $sql = "select * from cms_dictionary a join dictionary_rule b on b.dict_code = a.dict_code 
        where dict_level= 2 and path like  '{$model_id}%' and deleted = 0";
        $result = \think\facade\Db::query($sql);
        $add_rule = $edit_rule = [];
        foreach ($result as $value) {
            $insert_require = $value['insert_require'] == 1 ? 'require' : '';
            $update_require = $value['update_require'] == 1 ? 'require' : '';
            $insert_require = trim(str_replace(',', '|', "{$insert_require}|{$value['validate_rule']}"), '|');
            $insert_require = join('|', array_unique(explode('|', $insert_require)));
            $update_require = trim(str_replace(',', '|', "{$insert_require}|{$value['validate_rule']}"), '|');
            $update_require = join('|', array_unique(explode('|', $update_require)));
            if ($insert_require)
                $add_rule["{$value['dict_value']}|{$value['dict_name']}"] = trim($insert_require, '|');
            if ($update_require)
                $edit_rule["{$value['dict_value']}|{$value['dict_name']}"] = trim($insert_require, '|');
        }
        if ($operation == 'add') {
            return $add_rule;
        } elseif ($operation == 'edit') {
            return $edit_rule;
        } else {
            return $this->rule[$operation];
        }
    }

    public function getSchema($model_id) {
        if (!$model_id)
            throw new \Exception('文档模型不能为空');
        $sql = "select * from cms_dictionary a join dictionary_rule b on b.dict_code = a.dict_code 
        where dict_level= 2 and path like  '{$model_id}%' and deleted = 0";
        $result = \think\facade\Db::query($sql);
        $schema = [];
        foreach ($result as $value) {
            if ($value['input_type'] != 'none')
                $schema[$value['belong_to_table']][$value['dict_value']] = $value['data_type'];
        }
        return $schema;
    }

    public function add($param) {
        try {
            $validate = validate($this->getRule($param['model_id'] ?? '', 'add'), [], true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            $post_id = $param['post_id'];
            foreach ($this->getSchema($param['model_id'] ?? '') as $key => $schema) {
                if ($key == 'cms_post') {
                    $data = [];
                    array_walk($param, function ($v, $k) use (&$data, $schema) {
                        if (array_key_exists($k, $schema))
                            $data[$k] = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
                    });
                    $result = $this->save($data);
                } elseif ($key == 'cms_post_extend_attribute') {
                    $data = [];
                    array_walk($param, function ($v, $k) use (&$data, $schema, $post_id) {
                        if (array_key_exists($k, $schema)) {
                            $value = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
                            $field = $k;
                            $data[] = compact('post_id', 'value', 'field');
                        }
                    });
                } elseif ($key == 'cms_post_extend_text') {
                    $data = [];
                    array_walk($param, function ($v, $k) use (&$data, $schema, $post_id) {
                        if (array_key_exists($k, $schema)) {
                            $value = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
                            $field = $k;
                            $data[] = compact('post_id', 'value', 'field');
                        }
                    });
                    foreach ($data as $item) {
                        $model = CmsPostExtendText::where(['post_id' => $item['post_id'], 'field' => $item['field']])->find() ?: new CmsPostExtendText();
                        $model->save($item);
                    }
                }
            }
            return success($result);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function edit($param) {
        try {
            $validate = validate($this->getRule($param['model_id'] ?? '', 'edit'), [], true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            $post_id = $param['post_id'];
            foreach ($this->getSchema($param['model_id'] ?? '') as $key => $schema) {
                if ($key == 'cms_post') {
                    $data = [];
                    array_walk($param, function ($v, $k) use (&$data, $schema) {
                        if (array_key_exists($k, $schema))
                            $data[$k] = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
                    });
                    $id = $data[$this->pk];
                    unset($data[$this->pk]);
                    $model = self::find($id);
                    $result = $model->save($data);
                } elseif ($key == 'cms_post_extend_attribute') {
                    $data = [];
                    array_walk($param, function ($v, $k) use (&$data, $schema, $post_id) {
                        if (array_key_exists($k, $schema)) {
                            $value = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
                            $field = $k;
                            $data[] = compact('post_id', 'value', 'field');
                        }
                    });
                } elseif ($key == 'cms_post_extend_text') {
                    $data = [];
                    array_walk($param, function ($v, $k) use (&$data, $schema, $post_id) {
                        if (array_key_exists($k, $schema)) {
                            $value = is_scalar($v) ? trim($v) : join($this->multi_to_line_glue, $v);
                            $field = $k;
                            $data[] = compact('post_id', 'value', 'field');
                        }
                    });
                    foreach ($data as $item) {
                        $model = CmsPostExtendText::where(['post_id' => $item['post_id'], 'field' => $item['field']])->find() ?: new CmsPostExtendText();
                        $model->save($item);
                    }
                }
            }
            return success(true);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function posttag($param) {
        try {
            $res = $this->field('post_tag')
                ->select()->toArray();
            $list = [];
            foreach ($res as $value) {
                $_post_tag = explode(',', $value['post_tag']);
                $_post_tag = array_filter($_post_tag);
                foreach ($_post_tag as $val) {
                    if (isset($list[$val])) {
                        $list[$val] += 1;
                    } else {
                        $list[$val] = 1;
                    }
                }
            }
            asort($list, SORT_NATURAL);
            arsort($list);
            $data = [];
            $data['record'] = array_keys($list);
            return success($data);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function getPreNext($param) {
        try {
            $validate = validate(['post_id|文档id' => 'require'], [], true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            $res = $this->get_model_db($param)->find()->toarray();
            $sql = "select id,post_id,title from {$this->table} where id < {$res['id']} and model_id = '{$res['model_id']}' order by id desc limit 1";
            $pre = \think\facade\Db::query($sql);
            $sql = "select id,post_id,title from {$this->table} where id > {$res['id']} and model_id = '{$res['model_id']}' order by id asc limit 1";
            $next = \think\facade\Db::query($sql);
            $record = ['pre' => $pre[0] ?? [], 'next' => $next[0] ?? []];
            return success(compact('record'));
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }

    public function getRelated($param) {
        try {
            $rules = [
                'post_id|内容id' => 'require|number',
                'limit|[limit]列表记录数' => 'require|integer',
            ];
            $validate = validate($rules, [], true, false);
            if ($validate->check($param) === false) {
                throw new \Exception(join(',', $validate->getError()));
            }
            $post_id = $param['post_id'];
            $limit = $param['limit'];
            $post_result = \think\Facade\db::table($this->table)
                ->where('post_id', $param['post_id'])->find();
            if (!$post_result) {
                throw new \Exception('文档不存在');
            }
            $match_result = [];
            #获取标签关联文章
            if ($post_result['post_tag']) {
                $tag = explode(',', $post_result['post_tag']);
                foreach ($tag as $value) {
                    $map = [];
                    $map[] = ['model_id', '=', $post_result['model_id']];
                    $map[] = ['post_tag', 'like', '%' . $value . '%'];
                    $map[] = ['post_id', 'not in', $post_result['post_id']];
                    $result = \think\facade\Db::table($this->table)->where($map)->limit(0, $param['limit'])->select()->toArray();
                    if ($result) {
                        $match_result = array_merge($match_result, $result);
                    }
                }
            }
            #获取同栏目下文章
            if (count($match_result) < $limit) {
                $my_limit = $limit - count($match_result);
                $ignore_post_id = array_column($match_result, 'post_id');
                $map = [];
                $map[] = ['category_id', '=', $post_result['category_id']];
                $map[] = ['post_id', 'not in', $post_result['post_id']];
                if ($ignore_post_id) {
                    $map[] = ['post_id', 'not in', $ignore_post_id];
                }
                $result = \think\facade\Db::table($this->table)->where($map)->limit(0, $my_limit)->select()->toArray();
                if ($result) {
                    $match_result = array_merge($match_result, $result);
                }
            }
            $data = [];
            $data['record'] = $match_result;
            return success($data);
        } catch (\Exception $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        } catch (\Error $e) {
            $info = env('APP_DEBUG') ? ['line' => $e->getLine(), 'file' => $e->getFile()] : [];
            return fail($e->getMessage(), $info);
        }
    }
}