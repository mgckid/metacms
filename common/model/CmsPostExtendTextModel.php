<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2018/1/4
 * Time: 14:16
 */

namespace app\model;


class CmsPostExtendTextModel extends BaseModel
{
    public $tableName = 'cms_post_extend_text';
    public $pk = 'id';

    /**
     * 添加记录
     * @access public
     * @author furong
     * @param $data
     * @return bool|mixed
     * @since 2017年7月28日 09:37:59
     * @abstract
     * @throws \Exception
     */
    public function addRecord($data)
    {
        $data['modified'] = getDateTime();
        $return = false;
        $orm = $this->orm();
        if (isset($data[$this->pk]) && !empty($data[$this->pk])) {
            $obj = $orm->find_one($data[$this->pk]);
        } else {
            $obj = $orm->where('post_id', $data['post_id'])
                ->where('field', $data['field'])
                ->find_one();
        }
        if (!$obj) {
            #添加
            $data['created'] = $data['modified'];
            $return = $orm->create($data)
                ->save();
            $id = $orm->id();
        } else {
            $id = $obj->id();
            #修改
            $return = $obj->set($data)
                ->save();
        }
        return $return ? $id : $return;
    }
} 