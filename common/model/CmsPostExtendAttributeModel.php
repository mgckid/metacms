<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/20
 * Time: 10:51
 */

namespace app\model;


class CmsPostExtendAttributeModel extends BaseModel
{
    public $tableName = 'cms_post_extend_attribute';
    public $pk = 'id';

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
    public function getRecordList($orm = '', $offset = '', $limit = '', $for_count = false, $sort_field = 'id', $order = 'desc', $field = '*')
    {
        $orm = $this->getOrm($orm);
        if ($for_count) {
            $result = $orm->count();
        } else {
            $model = $orm->offset($offset)
                ->limit($limit)
                ->select($field);
            if ($order == 'desc') {
                $model = $model->order_by_desc($sort_field);
            } else {
                $model = $model->order_by_asc($sort_field);
            }
            $result = $model->find_array();
        }
        return $result;
    }

    /**
     * 获取所有记录
     * @access public
     * @author furong
     * @return array
     * @since 2017年7月28日 09:49:20
     * @abstract
     */
    public function getAllRecord($orm = '', $field = '*')
    {
        return $this->getOrm($orm)->select_expr($field)->find_array();
    }
}