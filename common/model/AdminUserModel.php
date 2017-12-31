<?php

/**
 * Description of UserModel
 *
 * @author Administrator
 */

namespace app\model;



class AdminUserModel extends BaseModel {

    public $tableName = 'admin_user';

    /**
     * 验证密码
     * @param type $username
     * @param type $password
     * @return boolean
     */
    public function validatePassword($username, $password) {
        $user = $this->orm()->where(array('username' => $username))->find_one()->as_array();
        if (!$user) {
            $this->setMessage('用户名不存在');
            return FALSE;
        }

        if ($user['password'] !== sha1($password)) {
            $this->setMessage('密码错误');
            return FALSE;
        }
        return TRUE;
    }

    public function getUserInfo($username,  $field = 'id,user_id,true_name,username,email,created,modified') {
        $user = $this->orm()->select_expr($field)->where(array('username' => $username))->find_one();
        return $user->as_array();
    }

    /**
     * 生成userId
     * @return type
     */
    public function getUserId() {
        $result = $this->orm()->raw_query('select uuid() as user_id')->find_one()->as_array();
        if ($result) {
            return current($result);
        } else {
            return FALSE;
        }
    }

    public function getUserList($offset, $limit, $isCount = FALSE,$field = '*') {
        $obj = $this->orm();
        if ($isCount) {
            $result = $obj->count();
        } else {
            $result = $obj->offset($offset)
                    ->limit($limit)
                    ->select($field)
                    ->findArray();
        }
        return $result;
    }

}
