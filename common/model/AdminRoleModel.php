<?php

/**
 * 角色模型
 * 2016年6月5日 12:20:20
 * @author Administrator
 */

namespace app\model;

class AdminRoleModel extends BaseModel
{

    protected $tableName = 'admin_role';

    /**
     * 添加角色
     * @param array $data
     * @return type
     */
    public function addRole(array $data)
    {
        $count = $this->orm()->where(array('role_name' => $data['role_name']))->count();
        if ($count > 0) {
            $this->setMessage('该角色名称已存在');
            return FALSE;
        }
        $result = $this->orm()->create($data)->save();
        if (!$result) {
            $this->setMessage('角色添加失败');
            return FALSE;
        }
        return $result;
    }

    /**
     * 修改角色
     * @param type $roleId
     * @param array $data
     * @return boolean
     */
    public function editRole($roleId, array $data)
    {
        $obj = $this->orm()->where(array('role_id' => $roleId))->use_id_column('role_id')->find_one();
        $originalRecord = $obj->as_array();
        if (!$originalRecord) {
            $this->setMessage('角色不存在');
            return FALSE;
        }
        $result = $this->orm()->where(array('role_name' => $data['role_name']))->find_one()->as_array();
        if ($result && $result['role_id'] != $roleId) {
            $this->setMessage('该角色名称已存在');
            return FALSE;
        }
        $result = $obj->where(array('role_id' => $roleId))->set($data)->save();
        if (!$result) {
            $this->setMessage('角色修改失败');
            return FALSE;
        }
        return $result;
    }

    public function getRoleList($offset, $limit, $isCount = false)
    {
        $obj = $this->orm();
        if ($isCount) {
            $result = $obj->count();
        } else {
            $result = $obj->offset($offset)
                ->limit($limit)
                ->findArray();
        }
        return $result;
    }

}
