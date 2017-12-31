<?php

/**
 * Description of RoleAccessModel
 *
 * @author Administrator
 */

namespace app\model;

class AdminRoleAccessModel extends BaseModel {

    protected $tableName = 'admin_role_access';

    public function getAccessByRoleId(array $roleId){
        return $this->orm()
            ->where_in('role_id',$roleId)
            ->find_array();
    }

}
