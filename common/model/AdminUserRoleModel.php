<?php

/**
 * Description of UserRoleModel
 *
 * @author Administrator
 */

namespace app\model;

class AdminUserRoleModel extends BaseModel
{

    protected $tableName = 'admin_user_role';

    public function getRoleByUserId($userId)
    {
        $result = $this->orm()->where(array('user_id' => $userId))->find_array();
        return array_column($result, 'role_id');
    }

}
