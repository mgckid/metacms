<?php

/**
 * Description of CmsPostModel
 *
 * @date 2016年5月8日 14:11:16
 *
 * @author Administrator
 */

namespace app\model;

use app\logic\BaseLogic;
use metacms\base\Application;

class CmsPostModel extends BaseModel
{

    public $tableName = 'cms_post';
    public $pk = 'id';


    /**
     * 获取下一篇文章
     * @param $id
     * @param $cateid
     * @param string $field
     * @return mixed
     */
    public function getNext($id, $field = '*')
    {
        $post_result = $this->getRecordInfoById($id);
        $next_result = $this->orm()
            ->where('category_id', $post_result['category_id'])
            ->where('deleted', 0)
            ->where_gt('id', $post_result['id'])
            ->order_by_desc('id')
            ->find_one();
        $result = [];
        if ($next_result) {
            $next_result = $next_result->as_array();
            $result = $this->getModelRecordInfoByPostId($next_result['post_id'], $field);
        }
        return $result;
    }

    /**
     * 获取上一篇文章
     * @param $id
     * @param $cateid
     * @param string $field
     * @return mixed
     */
    public function getPre($id, $field = '*')
    {
        $post_result = $this->getRecordInfoById($id);
        $pre_result = $this->orm()
            ->where('category_id', $post_result['category_id'])
            ->where('deleted', 0)
            ->where_lt('id', $post_result['id'])
            ->order_by_asc('id')
            ->find_one();
        $result = [];
        if ($pre_result) {
            $pre_result = $pre_result->as_array();
            $result = $this->getModelRecordInfoByPostId($pre_result['post_id'], $field);
        }
        return $result;
    }


    /**
     * 获取单条记录
     * @access public
     * @author furong
     * @param $id
     * @param string $field
     * @return array|bool
     * @since 2017年7月28日 09:40:34
     * @abstract
     */
    public function getModelRecordInfoById($id, $field = '*')
    {
        $orm = $this->orm()->where('id', $id);
        $cms_post_result = $this->getRecordInfo($orm);
        if (!$cms_post_result) {
            return false;
        }
        $result = $this->getModelRecordInfoByPostId($cms_post_result['post_id'], $field);
        return $result;
    }

    /**
     * 获取单条记录
     * @access public
     * @author furong
     * @param $id
     * @param string $field
     * @return array|bool
     * @since 2017年7月28日 09:40:34
     * @abstract
     */
    public function getModelRecordInfoByPostId($post_id, $field = '*')
    {
        $orm = $this->orm()->where('post_id', $post_id);
        $cms_post_result = $this->getRecordInfo($orm);
        if (!$cms_post_result) {
            return false;
        }
        $logic = new BaseLogic();
        $model_defined = $logic->getModelDefined($cms_post_result['model_id']);
        $cms_post_extend_attribute_select_arr[] = 'post_id';
        $cms_post_extend_text_select_arr[] = 'post_id';
        foreach ($model_defined as $value) {
            if ($value['belong_to_table'] == 'cms_post_extend_attribute') {
                $cms_post_extend_attribute_select_arr[] = "max( CASE field WHEN '{$value['field_value']}' THEN `value` ELSE '' END ) AS {$value['field_value']}";
            }
            if ($value['belong_to_table'] == 'cms_post_extend_text') {
                $cms_post_extend_text_select_arr[] = "max( CASE field WHEN '{$value['field_value']}' THEN `value` ELSE '' END ) AS {$value['field_value']}";
            }
        }

        #拼接字段
        if ($field != '*') {
            $fields = explode(',', $field);
            $select_expr = [];
            foreach ($fields as $val) {
                foreach ($model_defined as $value) {
                    if ($val == $value['field_value']) {
                        $select_expr[] = $value['belong_to_table'] . '.' . $val;
                    }
                }
            }
            $field = implode(',', $select_expr);
        }

        #cms_post_extend_attribute
        {
            $cms_post_extend_attribute_raw_join = "left join (SELECT " . implode(',', $cms_post_extend_attribute_select_arr) . " FROM cms_post_extend_attribute  GROUP BY post_id )";
        }
        #cms_post_extend_text
        {
            $cms_post_extend_text_raw_join = "left join (SELECT  " . implode(',', $cms_post_extend_text_select_arr) . " FROM cms_post_extend_text  GROUP BY post_id )";
        }

        $orm = $this->orm()->raw_join($cms_post_extend_attribute_raw_join, ['cms_post.post_id', '=', 'cms_post_extend_attribute.post_id'], 'cms_post_extend_attribute')
            ->raw_join($cms_post_extend_text_raw_join, ['cms_post.post_id', '=', 'cms_post_extend_text.post_id'], 'cms_post_extend_text')
            ->where('cms_post.post_id', $post_id);
        $result = $orm->select_expr($field)->find_one();
        if (!empty($result)) {
            $result = $result->as_array();
        }
        return $result;
    }


    /**
     * 获取记录列表
     * @access public
     * @author furong
     * @param $orm
     * @param string $offset
     * @param string $limit
     * @param bool $for_count
     * @param string $field
     * @return void
     * @since 2017年7月6日 17:14:49
     * @abstract
     */
    public function getModelRecordList($model_id, $orm, $offset = '', $limit = '', $for_count = false, $sort_field = 'created', $order = 'id', $field = '*')
    {
        $orm = $this->getOrm($orm)->where('cms_post.model_id', $model_id)->where('cms_post.deleted', 0);
        #查询数据
        if ($for_count) {
            $result = $orm->count();
        } else {
            $logic = new BaseLogic();
            $model_defined = $logic->getModelDefined($model_id);
            #拼接查询字段
            $select_arr = [];
            if ($field == '*') {
                foreach ($model_defined as $value) {
                    $select_arr[] = '`' . $value['belong_to_table'] . '`.`' . $value['field_value'] . '`';
                }
            } else {
                $fields = explode(',', $field);
                foreach ($fields as $val) {
                    foreach ($model_defined as $value) {
                        if ($val == $value['field_value']) {
                            $select_arr[] = '`' . $value['belong_to_table'] . '`.`' . $val . '`';
                        }
                    }
                }
            }
            $select_expr = implode(',', array_unique($select_arr));

            #组装sql
            $cms_post_extend_attribute_select[] = 'post_id';
            $cms_post_extend_text_select[] = 'post_id';
            foreach ($model_defined as $value) {
                if ($value['belong_to_table'] == 'cms_post_extend_attribute') {
                    $cms_post_extend_attribute_select[] = "max( CASE field WHEN '{$value['field_value']}' THEN `value` ELSE '' END ) AS `{$value['field_value']}`";
                }
                if ($value['belong_to_table'] == 'cms_post_extend_text') {
                    $cms_post_extend_text_select[] = "max( CASE field WHEN '{$value['field_value']}' THEN `value` ELSE '' END ) AS `{$value['field_value']}`";
                }
            }
            #cms_post_extend_attribute
            {
                $cms_post_extend_attribute_select = implode(',', array_unique($cms_post_extend_attribute_select));
                $cms_post_extend_attribute_raw_join = "left join (SELECT {$cms_post_extend_attribute_select} FROM cms_post_extend_attribute  GROUP BY post_id )";
            }
            #cms_post_extend_text
            {
                $cms_post_extend_text_select = implode(',', array_unique($cms_post_extend_text_select));
                $cms_post_extend_text_raw_join = "left join (SELECT {$cms_post_extend_text_select} FROM cms_post_extend_text  GROUP BY post_id )";
            }
            $orm = $orm->raw_join($cms_post_extend_attribute_raw_join, ['cms_post.post_id', '=', 'cms_post_extend_attribute.post_id'], 'cms_post_extend_attribute')
                ->raw_join($cms_post_extend_text_raw_join, ['cms_post.post_id', '=', 'cms_post_extend_text.post_id'], 'cms_post_extend_text');
            $orm = $orm->select_expr($select_expr);
            if ($offset) {
                $orm = $orm->offset($offset);
            }
            if ($limit) {
                $orm = $orm->limit($limit);
            }
            if ($order == 'desc') {
                $orm = $orm->order_by_desc($sort_field);
            } else {
                $orm = $orm->order_by_asc($sort_field);
            }
            $result = $orm->find_array();
        }
        return $result;
    }


    public function addModelRecord($request_data)
    {
        $request_data = Application::hooks()->apply_filters('publish_post', $request_data);
        #获取模型定义
        $baseLogic = new BaseLogic();
        $model_defined = $baseLogic->getModelDefined($request_data['model_id']);
        $cms_post_data = [];
        $cms_post_extend_attribute_data = [];
        $cms_post_extend_text_data = [];
        foreach ($model_defined as $value) {
            if ($value['belong_to_table'] == 'cms_post') {
                if (isset($request_data[$value['field_value']])) {
                    $cms_post_data[$value['field_value']] = $request_data[$value['field_value']];
                }
            } elseif ($value['belong_to_table'] == 'cms_post_extend_attribute') {
                if (isset($request_data[$value['field_value']]) && !empty($request_data[$value['field_value']])) {
                    $cms_post_extend_attribute_data[] = [
                        'post_id' => $request_data['post_id'],
                        'field' => $value['field_value'],
                        'value' => $request_data[$value['field_value']],
                    ];
                }
            } elseif ($value['belong_to_table'] == 'cms_post_extend_text') {
                if (isset($request_data[$value['field_value']]) && !empty($request_data[$value['field_value']])) {
                    $cms_post_extend_text_data[] = [
                        'post_id' => $request_data['post_id'],
                        'field' => $value['field_value'],
                        'value' => $request_data[$value['field_value']],
                    ];
                }
            }
        }
        try {
            $this->beginTransaction();
            $result = $this->addRecord($cms_post_data);
            if (!$result) {
                throw new \Exception('文档主记录添加失败');
            }
            if ($cms_post_extend_attribute_data) {
                $cmsPostExtendAttributeModel = new CmsPostExtendAttributeModel();
                foreach ($cms_post_extend_attribute_data as $value) {
                    $result = $cmsPostExtendAttributeModel->addRecord($value);
                    if (!$result) {
                        throw new \Exception('文档扩展属性添加失败');
                    }
                }
            }
            if ($cms_post_extend_text_data) {
                $cmsPostExtendTextModel = new CmsPostExtendTextModel();
                foreach ($cms_post_extend_text_data as $value) {
                    $result = $cmsPostExtendTextModel->addRecord($value);
                    if (!$result) {
                        throw new \Exception('文档扩展富文本添加失败');
                    }
                }
            }
            $this->commit();
            $return = true;
        } catch (\Exception $ex) {
            $this->rollBack();
            $this->setMessage($ex->getMessage());
            $return = false;
        }
        return $return;
    }

    /**
     * 删除文档记录
     * @access public
     * @author furong
     * @param $id
     * @return bool
     * @since 2017年7月28日 09:46:43
     * @abstract
     */
    public function deleteModelRecord($id)
    {
        $post_result = $this->getRecordInfoById($id);
        if (!$post_result) {
            $this->setMessage('文档不存在');
            return false;
        }
        try {
            $this->beginTransaction();
            #删除主记录
            $result = $this->deleteRecordById($id);
            if (!$result) {
                throw new \Exception('删除文档主记录失败');
            }
            #删除扩展记录
            $cmsPostExtendAttributeModel = new CmsPostExtendAttributeModel();
            $orm = $cmsPostExtendAttributeModel->orm()->where('post_id', $post_result['post_id']);
            $list = $cmsPostExtendAttributeModel->getAllRecord($orm);
            foreach ($list as $value) {
                $result = $cmsPostExtendAttributeModel->deleteRecordById($value['id']);
                if (!$result) {
                    throw new \Exception('删除文档扩展属性失败');
                }
            }
            $cmsPostExtendTextModel = new CmsPostExtendTextModel();
            $orm = $cmsPostExtendTextModel->orm()->where('post_id', $post_result['post_id']);
            $list = $cmsPostExtendTextModel->getAllRecord($orm);
            foreach ($list as $value) {
                $result = $cmsPostExtendTextModel->deleteRecordById($value['id']);
                if (!$result) {
                    throw new \Exception('删除文档扩展富文本失败');
                }
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollBack();
            $this->setMessage($e->getMessage());
            return false;
        }
    }


}
