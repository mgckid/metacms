<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/6/21
 * Time: 17:46
 */

namespace app\model;


class SiteConfigModel extends BaseModel
{
    protected $tableName = 'site_config';
    protected $pk = 'id';


    public function addConfig($data)
    {
        #验证
        $rule = array(
            'name' => 'required|alpha_dash',
            'value' => 'required',
            'type' => 'in:string,bool,mstring,number',
            'description' => 'required',
        );
        $attr = array(
            'name' => '变量名',
            'type' => '变量类型',
            'description' => '变量描述',
            'value' => '变量值'
        );
        $valdate = $this->validate()->make($data, $rule,[], $attr);
        if (false === $valdate->passes()) {
            $this->setMessage($valdate->messages()->first());
            return false;
        }

        #保存
        $data['modified'] = getDateTime();
        $model = $this->orm();
        $return = false;
        if (empty($data['id'])) {
            unset($data['id']);
            #添加
            $data['created'] = getDateTime();
            $return = $model->create($data)
                ->save();
            $return = $model->id();
        } else {
            #修改
            $id = $data['id'];
            $result = $model->where('deleted', 0)->find_one($id);
            if ($result) {
                $return = $result->set($data)
                    ->save();
            } else {
                $this->setMessage('配置变量');
            }
        }
        return $return;
    }

    public function getConfigList($orm='', $field = '*')
    {
        $orm = $this->orm()->where('deleted', 0);
        return $orm->select_expr($field)->find_array();
    }

    /**
     * 获取配置信息
     * @access public
     * @author furong
     * @param $condition
     * @param string $field
     * @return bool|\idiorm\orm\ORM
     * @since 2017年6月22日 10:47:01
     * @abstract
     */
    public function getConfigInfo($condition, $field = '*')
    {
        $orm = $this->orm();
        foreach ($condition as $key => $value) {
            $orm = call_user_func_array(array($orm, $key), $value);
        }
        $result = $orm->select_expr($field)->find_one();
        return $result;
    }

    /**
     * 获取配置信息byID
     * @access public
     * @author furong
     * @param $id
     * @param string $field
     * @return bool|\idiorm\orm\ORM
     * @since 2017年6月22日 10:49:01
     * @abstract
     */
    public function getConfigInfoById($id, $field = '*')
    {
        $condition = [
            'where' => ['id', $id]
        ];
        return $this->getConfigInfo($condition, $field);
    }

    public function updateConfig($orm, $data)
    {
        $orm = $this->getOrm($orm);
        $result = $orm->find_one();
        if (!$result) {
            $this->setMessage('配置项不存在');
            return false;
        }
        $data['modified'] = getDateTime();
        if ($result->set($data)->save()) {
            return true;
        } else {
            $this->setMessage('更新配置项失败');
            return false;
        }
    }

    /**
     * 删除配置项
     * @access public
     * @author furong
     * @param $id
     * @return bool
     * @since 2017年6月22日 10:19:20
     * @abstract
     */
    public function delConfig($id)
    {
        $condition = [
            'where' => ['id', $id]
        ];
        $data = [
            'deleted' => self::DELETED,
        ];
        $result = $this->updateConfig($condition, $data);
        if ($result) {
            $this->setMessage('配置删除成功');
        } else {
            $this->setMessage('配置删除失败');
        }
        return $result;
    }
}