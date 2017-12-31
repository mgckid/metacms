<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/3/9
 * Time: 16:24
 */

namespace app\model;


class CmsCategoryModel extends BaseModel
{
    protected $tableName = 'cms_category';
    const CATE_TYPE_LIST = '10';
    const CATE_TYPE_PAGE = '20';
    const CATE_TYPE_JUMP_URL = '30';
    const NAV_DISPLAY = '20';


    /**
     * 添加网站
     *
     * @access public
     * @author furong
     * @param $data
     * @return bool
     * @since ${DATE}
     * @abstract
     */
    public function addCategory($data)
    {
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
            $result = $model->find_one($id);
            if ($result) {
                $return = $result->set($data)
                    ->save();
            } else {
                $this->setMessage('栏目不存在');
            }
        }
        return $return;
    }

    /**
     * 获取栏目列表
     * @return array
     */
    public function getColumnList(array $condition = array(), $field = '*')
    {
        $orm = $this->orm();
        if ($condition) {
            foreach ($condition as $key => $value) {
                $orm = call_user_func_array(array($orm, $key), $value);
            }
        }
        $result = $orm->select_expr($field)
            ->find_array();
        return $result;
    }


    /**
     * 删除栏目
     * @param type $id
     * @return type
     */
    public function deleteColumn($id)
    {
        $cateList = $this->getAllRecord('');
        $record = $this->getRecordInfoById($id);
        if (!$record) {
            $this->setMessage('栏目不存在');
            return FALSE;
        }
        $cmsPostModel = new CmsPostModel();
        #查找栏目下的文章
        $orm = $cmsPostModel->orm()->where(array('category_id' => $record['id']));
        $articleCount = $cmsPostModel->getRecordList($orm,'','',true);
        if ($articleCount > 0) {
            $this->setMessage('该栏目下还有文章');
            return FALSE;
        }
        #查找子栏目
        $sonRecord = treeStructForLayer($cateList, $id);
        if (!empty($sonRecord)) {
            $this->setMessage('请先删除子栏目');
            return FALSE;
        }
        #删除
        $result = $this->deleteRecordById($id);
        if (!$result) {
            $this->setMessage('删除失败');
            return FALSE;
        }
        $this->setMessage('删除成功');
        return TRUE;
    }

    /**
     * 获取栏目信息
     * @param type $id 文章id
     * @param type $field 字段名
     * @return type
     */
    public function getCateInfoById($id, $field = "*")
    {
        $result = $this->orm()
            ->select_expr($field)
            ->find_one($id);
        if (!$result) {
            return false;
        }
        return $result->as_array();
    }

    /**
     * 根据条件获取栏目信息
     * @access public
     * @author furong
     * @param $condition
     * @param $field
     * @return bool
     * @since 2017年4月26日 10:11:55
     * @abstract
     */
    public function getCateInfo($condition, $field = '*')
    {
        $orm = $this->orm();
        if ($condition) {
            foreach ($condition as $key => $value) {
                $orm = call_user_func_array(array($orm, $key), $value);
            }
        }
        $result = $orm->select_expr($field)
            ->find_one();
        if (!$result) {
            return false;
        }
        return $result->as_array();;
    }


//    //组合一维数组
//    Static Public function unlimitedForLevel($cate, $html = '--', $pid = 0, $level = 0)
//    {
//        $arr = array();
//        foreach ($cate as $k => $v) {
//            if ($v['pid'] == $pid) {
//                $v['level'] = $level + 1;
//                $v['html'] = str_repeat($html, $level);
//                $arr[] = $v;
//                $arr = array_merge($arr, self::unlimitedForLevel($cate, $html, $v['id'], $level + 1));
//            }
//        }
//        return $arr;
//    }
//
//    //组合多维数组
//    Static Public function unlimitedForLayer($cate, $name = 'child', $pid = 0)
//    {
//        $arr = array();
//        foreach ($cate as $v) {
//            if ($v['pid'] == $pid) {
//                $v[$name] = self::unlimitedForLayer($cate, $name, $v['id']);
//                $arr[] = $v;
//            }
//        }
//        return $arr;
//    }
//
//    //传递一个子分类ID返回所有的父级分类
//    Static Public function getParents($cate, $id)
//    {
//        $arr = array();
//        foreach ($cate as $v) {
//            if ($v['id'] == $id) {
//                $arr[] = $v;
//                $arr = array_merge(self::getParents($cate, $v['pid']), $arr);
//            }
//        }
//        return $arr;
//    }
//
//    //传递一个父级分类ID返回所有子分类ID
//    Static Public function getChildsId($cate, $pid)
//    {
//        $arr = array();
//        foreach ($cate as $v) {
//            if ($v['pid'] == $pid) {
//                $arr[] = $v['id'];
//                $arr = array_merge($arr, self::getChildsId($cate, $v['id']));
//            }
//        }
//        return $arr;
//    }
//
//    //传递一个父级分类ID返回所有子分类
//    Static Public function getChilds($cate, $pid)
//    {
//        $arr = array();
//        foreach ($cate as $v) {
//            if ($v['pid'] == $pid) {
//                $arr[] = $v;
//                $arr = array_merge($arr, self::getChilds($cate, $v['id']));
//            }
//        }
//        return $arr;
//    }


}