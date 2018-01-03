<?php

/**
 * Description of BaseModel
 *
 * @author Administrator
 */

namespace app\model;

use metacms\base\Model;
use app\model\DictionaryModel;

class BaseModel extends Model
{


    /**
     * 获取orm
     * @access public
     * @author furong
     * @param $orm
     * @return \idiorm\orm\ORM
     * @since 2017年7月6日 17:23:04
     * @abstract
     */
    public function getOrm($orm)
    {
        if (empty($orm)) {
            $orm = $this->orm();
        }
        $orm = clone($orm);
        return $orm;
    }

    /**
     * 删除记录
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
        $return = false;
        $id = isset($data[$this->pk]) ? $data[$this->pk] : 0;
        $data['modified'] = getDateTime();
        $model = $this->orm();
        if (!$id) {
            #添加
            unset($data[$this->pk]);
            $data['created'] = $data['modified'];
            $return = $model->create($data)
                ->save();
            $id = $model->id();
        } else {
            #修改
            $result = $model->find_one($id);
            if ($result) {
                $return = $result->set($data)
                    ->save();
            } else {
                $this->setMessage('添加记录失败');
            }
        }
        return $return ? $id : $return;
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
    public function getRecordInfoById($id, $field = '*')
    {
        $orm = $this->orm()->where($this->pk, $id);
        $result = $this->getRecordInfo($orm, $field);
        return $result;
    }

    /**
     * 删除单条记录
     * @access public
     * @author furong
     * @param $id
     * @return bool
     * @since 2017年7月28日 09:46:43
     * @abstract
     */
    public function deleteRecordById($id)
    {
        $data = [
            $this->pk => $id,
            'deleted' => 1
        ];
        return $this->addRecord($data);
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
        return $this->getOrm($orm)->where('deleted', 0)->select_expr($field)->find_array();
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
    public function getRecordList($orm = '', $offset = '', $limit = '', $for_count = false, $sort_field = 'id', $order = 'desc', $field = '*')
    {
        $orm = $this->getOrm($orm)->where_equal('deleted', 0);
        if ($for_count) {
            $result = $orm->count();
        } else {
            $model = $orm->offset($offset)
                ->limit($limit);
            if (is_array($field)) {
                $model = $model->select($field);
            } elseif (is_string($field)) {
                $model = $model->select_expr($field);
            }
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
     * 获取单条记录
     * @access public
     * @author furong
     * @param string $orm
     * @param string $field
     * @return array|false
     * @since 2017年8月17日 16:40:20
     * @abstract
     */
    public function getRecordInfo($orm = '', $field = '*')
    {
        $orm = $this->getOrm($orm);
        $result = $orm->select_expr($field)->find_one();
        if (!empty($result)) {
            $result = $result->as_array();
        }
        return $result;
    }

    public function delRecord($orm)
    {
        $orm = $this->getOrm($orm);
        $result = $orm->find_array();
        foreach ($result as $value) {
            $del_result = $this->deleteRecordById($value['id']);
            if (!$del_result) {
                throw new \Exception('删除记录失败');
                return false;
            }
        }
        return true;
    }


}
