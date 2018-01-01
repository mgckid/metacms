<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/3/2
 * Time: 20:19
 */

namespace app\model;


class AdvertisementListModel extends BaseModel
{
    public $tableName = 'advertisement_list';
    public $pk = 'id';

    public function getAdvertisementList($orm='', $offset, $limit, $forCount, $field = 'al.*,ap.position_name,ap.position_key')
    {
        $orm = $this->getOrm($orm)
            ->table_alias('al')
            ->left_outer_join('advertisement_position', array('al.position_id', '=', 'ap.id'), 'ap');
        if ($forCount) {
            $result = $orm->count();
        } else {
            $result = $orm->offset($offset)
                ->select_expr($field)
                ->limit($limit)
                ->order_by_asc('ap.id')
                ->order_by_asc('al.id')
                ->find_array();
        }
        return $result;
    }
}