<?php

/**
 * Description of AdminAccessModel
 *
 * @author Administrator
 */

namespace app\model;


class AdminAccessModel extends BaseModel
{

    protected $tableName = 'admin_access';
    protected $pk = 'id';

    /**
     * 权限表初始化
     * @return type
     */
    protected function init()
    {
        $data = array(
            'access_name' => '根目录',
            'path' => '0',
            'url' => '/',
        );
        return $this->orm()->create($data)->save();
    }

    /**
     * 获取权限列表
     * @param type $field
     * @return type
     */
    public function getAccessList($offset, $limit, $isCount = FALSE, $field = array('*'))
    {
        $obj = $this->orm();
        if ($isCount) {
            $result = $obj->count();
        } else {
            $result = $obj->select($field)
                ->limit($limit)
                ->offset($offset)
                ->findArray();
        }
        return $result;
    }

    /**
     * 获取权限
     * @param type $id
     * @return type
     */
    public function getAccessById($id)
    {
        return $this->orm()
            ->where(array('access_id' => $id))
            ->find_one()
            ->as_array();
    }

    /**
     * 添加文章
     *
     * @access public
     * @author furong
     * @param $data
     * @return bool
     * @since  2017年4月10日 09:59:33
     * @abstract
     */
    public function addAccess($data)
    {
        if (empty($data) || empty($data['access_sn'])) {
            return false;
        }
        $data['modified'] = getDateTime();
        $model = $this->orm();
        $count = $model->where('access_sn', $data['access_sn'])
            ->count();
        if (!$count) {
            #添加
            unset($data[$this->pk]);
            $data['created'] = getDateTime();
            $result = $model->create($data)
                ->save();
        } else {
            #修改
            $model = $model->where('access_sn', $data['access_sn'])
                ->find_one();
            $result = $model->set($data)
                ->save();
        }
        if ($result) {
            return $model->id();
        } else {
            return false;
        }
    }

}
