<?php
/**
 * 站点管理模型
 * Created by PhpStorm.
 * User: furong
 * Date: 2022-03-03
 * Time: 22:38
 */
namespace app\appdal\model;
use think\Model;

class Site extends Model {
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

    protected $table='site';
    protected $suffix='';
    //protected $connection='db';
    protected $strict=true;
    protected $disuse =[];

    // 设置字段信息
    public $schema = array (
        'id' => 'int',
        'site_name' => 'varchar',
        'short_name' => 'varchar',
        'name_cn' => 'varchar',
        'keywords' => 'varchar',
        'description' => '',
        'found_date' => 'date',
        'icp_code' => 'varchar',
        'url' => 'varchar',
        'email' => 'varchar',
        'master' => 'varchar',
        'master_phone' => 'varchar',
        'remark' => 'text',
        'service_expire_date' => 'date',
        'access_key' => 'varchar',
        'access_key_secret' => 'varchar',
        'created' => 'datetime',
        'modified' => 'datetime',
        'deleted' => 'int',
    );
    public $rule =  array (
        'add' =>
            array (
                'site_name|站点名称' => 'require',
                'short_name|站点短名称' => 'require',
                'name_cn|站点中文名称' => 'require',
                'keywords|站点关键字' => 'require',
                'description|站点描述' => 'require',
                'found_date|站点创办时间' => 'require',
                'icp_code|站点icp备案号' => 'require',
                'url|站点链接' => 'require|url',
                'email|网站邮箱' => 'require|email',
                'master|网站所有人' => 'require',
                'master_phone|所有人电话' => 'require',
                'remark|站点备注' => 'require',
                'service_expire_date|服务到期日期' => 'require',
                'access_key|访问秘钥' => 'require',
                'access_key_secret|访问签名加密密文' => 'require',
            ),
        'edit' =>
            array (
                'id|主键ID' => 'require|integer',
                'site_name|站点名称' => 'require',
                'short_name|站点短名称' => 'require',
                'name_cn|站点中文名称' => 'require',
                'keywords|站点关键字' => 'require',
                'description|站点描述' => 'require',
                'found_date|站点创办时间' => 'require',
                'icp_code|站点icp备案号' => 'require',
                'url|站点链接' => 'require|url',
                'email|网站邮箱' => 'require|email',
                'master|网站所有人' => 'require',
                'master_phone|所有人电话' => 'require',
                'remark|站点备注' => 'require',
                'service_expire_date|服务到期日期' => 'require',
                'access_key|访问秘钥' => 'require',
                'access_key_secret|访问签名加密密文' => 'require',
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
                    'title' => '主键ID',
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
                    'title' => '站点名称',
                    'name' => 'site_name',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            2 =>
                array (
                    'title' => '站点短名称',
                    'name' => 'short_name',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            3 =>
                array (
                    'title' => '站点中文名称',
                    'name' => 'name_cn',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            4 =>
                array (
                    'title' => '站点关键字',
                    'name' => 'keywords',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            5 =>
                array (
                    'title' => '站点描述',
                    'name' => 'description',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            6 =>
                array (
                    'title' => '站点创办时间',
                    'name' => 'found_date',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            7 =>
                array (
                    'title' => '站点icp备案号',
                    'name' => 'icp_code',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            8 =>
                array (
                    'title' => '站点链接',
                    'name' => 'url',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            9 =>
                array (
                    'title' => '网站邮箱',
                    'name' => 'email',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            10 =>
                array (
                    'title' => '网站所有人',
                    'name' => 'master',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            11 =>
                array (
                    'title' => '所有人电话',
                    'name' => 'master_phone',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            12 =>
                array (
                    'title' => '站点备注',
                    'name' => 'remark',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => 'editor',
                ),
            13 =>
                array (
                    'title' => '服务到期日期',
                    'name' => 'service_expire_date',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            14 =>
                array (
                    'title' => '访问秘钥',
                    'name' => 'access_key',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            15 =>
                array (
                    'title' => '访问签名加密密文',
                    'name' => 'access_key_secret',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            16 =>
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
            17 =>
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
            18 =>
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
                    'title' => '主键ID',
                    'type' => 'checkbox',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            1 =>
                array (
                    'field' => 'site_name',
                    'title' => '站点名称',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            2 =>
                array (
                    'field' => 'short_name',
                    'title' => '站点短名称',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            3 =>
                array (
                    'field' => 'name_cn',
                    'title' => '站点中文名称',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            4 =>
                array (
                    'field' => 'keywords',
                    'title' => '站点关键字',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            5 =>
                array (
                    'field' => 'description',
                    'title' => '站点描述',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            6 =>
                array (
                    'field' => 'found_date',
                    'title' => '站点创办时间',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            7 =>
                array (
                    'field' => 'icp_code',
                    'title' => '站点icp备案号',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            8 =>
                array (
                    'field' => 'url',
                    'title' => '站点链接',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            9 =>
                array (
                    'field' => 'email',
                    'title' => '网站邮箱',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            10 =>
                array (
                    'field' => 'master',
                    'title' => '网站所有人',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            11 =>
                array (
                    'field' => 'master_phone',
                    'title' => '所有人电话',
                    'type' => 'normal',
                    'hide' => false,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            12 =>
                array (
                    'field' => 'remark',
                    'title' => '站点备注',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            13 =>
                array (
                    'field' => 'service_expire_date',
                    'title' => '服务到期日期',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            14 =>
                array (
                    'field' => 'access_key',
                    'title' => '访问秘钥',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            15 =>
                array (
                    'field' => 'access_key_secret',
                    'title' => '访问签名加密密文',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                        ),
                ),
            16 =>
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
            17 =>
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
            18 =>
                array (
                    'field' => 'deleted',
                    'title' => '删除标记',
                    'type' => 'normal',
                    'hide' => true,
                    'width' => '',
                    'enum' =>
                        array (
                            0 => '未删除',
                            1 => '已删除',
                        ),
                ),
            19 =>
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
                    'title' => '主键ID',
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
                    'title' => '站点名称',
                    'name' => 'site_name',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            2 =>
                array (
                    'title' => '站点短名称',
                    'name' => 'short_name',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            3 =>
                array (
                    'title' => '站点中文名称',
                    'name' => 'name_cn',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'text',
                    'widget_type' => '',
                ),
            4 =>
                array (
                    'title' => '站点关键字',
                    'name' => 'keywords',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            5 =>
                array (
                    'title' => '站点描述',
                    'name' => 'description',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            6 =>
                array (
                    'title' => '站点创办时间',
                    'name' => 'found_date',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            7 =>
                array (
                    'title' => '站点icp备案号',
                    'name' => 'icp_code',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            8 =>
                array (
                    'title' => '站点链接',
                    'name' => 'url',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            9 =>
                array (
                    'title' => '网站邮箱',
                    'name' => 'email',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            10 =>
                array (
                    'title' => '网站所有人',
                    'name' => 'master',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            11 =>
                array (
                    'title' => '所有人电话',
                    'name' => 'master_phone',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            12 =>
                array (
                    'title' => '站点备注',
                    'name' => 'remark',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => 'editor',
                ),
            13 =>
                array (
                    'title' => '服务到期日期',
                    'name' => 'service_expire_date',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            14 =>
                array (
                    'title' => '访问秘钥',
                    'name' => 'access_key',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            15 =>
                array (
                    'title' => '访问签名加密密文',
                    'name' => 'access_key_secret',
                    'description' => '',
                    'enum' =>
                        array (
                        ),
                    'type' => 'none',
                    'widget_type' => '',
                ),
            16 =>
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
            17 =>
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
            18 =>
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
                    0 => 't.site_name',
                    1 => '=',
                    2 => $param['site_name'],
                ),
            2 =>
                array (
                    0 => 't.short_name',
                    1 => '=',
                    2 => $param['short_name'],
                ),
            3 =>
                array (
                    0 => 't.name_cn',
                    1 => '=',
                    2 => $param['name_cn'],
                ),
            4 =>
                array (
                    0 => 't.keywords',
                    1 => '=',
                    2 => $param['keywords'],
                ),
            5 =>
                array (
                    0 => 't.description',
                    1 => '=',
                    2 => $param['description'],
                ),
            6 =>
                array (
                    0 => 't.found_date',
                    1 => '=',
                    2 => $param['found_date'],
                ),
            7 =>
                array (
                    0 => 't.icp_code',
                    1 => '=',
                    2 => $param['icp_code'],
                ),
            8 =>
                array (
                    0 => 't.url',
                    1 => '=',
                    2 => $param['url'],
                ),
            9 =>
                array (
                    0 => 't.email',
                    1 => '=',
                    2 => $param['email'],
                ),
            10 =>
                array (
                    0 => 't.master',
                    1 => '=',
                    2 => $param['master'],
                ),
            11 =>
                array (
                    0 => 't.master_phone',
                    1 => '=',
                    2 => $param['master_phone'],
                ),
            12 =>
                array (
                    0 => 't.remark',
                    1 => '=',
                    2 => $param['remark'],
                ),
            13 =>
                array (
                    0 => 't.service_expire_date',
                    1 => '=',
                    2 => $param['service_expire_date'],
                ),
            14 =>
                array (
                    0 => 't.access_key',
                    1 => '=',
                    2 => $param['access_key'],
                ),
            15 =>
                array (
                    0 => 't.access_key_secret',
                    1 => '=',
                    2 => $param['access_key_secret'],
                ),
            16 =>
                array (
                    0 => 't.created',
                    1 => '=',
                    2 => $param['created'],
                ),
            17 =>
                array (
                    0 => 't.modified',
                    1 => '=',
                    2 => $param['modified'],
                ),
            18 =>
                array (
                    0 => 't.deleted',
                    1 => '=',
                    2 => $param['deleted'],
                ),
        );
    }
}