<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 11:36
 */

namespace app\controller;


use app\model\CmsPostModel;

class testController extends UserBaseController
{

    public function index()
    {
        $model = new CmsPostModel();
        $result = $model->getAllRecord();
        foreach ($result as $key => $value) {
            $aaa = $model->getModelRecordInfoByPostId($value['post_id']);
            $value['title'] = $aaa['title'];
            $value['keywords'] = $aaa['keywords'];
            $value['description'] = $aaa['description'];
            $value['main_image'] = $aaa['main_image'];
            $value['author'] = $aaa['author'];
            $value['post_tag'] = $aaa['post_tag'];
            $model->orm()->find_one($value['id'])->set($value)->save();
        }
    }

    public function index1()
    {
        $cmsPostModel = new CmsPostModel();

        $m = $cmsPostModel->for_table('cms_post', 'pro');

        $result = $m->find_array();
        foreach ($result as $value) {
            $post_data = [
                'category_id' => $value['column_id'],
                'model_id' => 1,
                'title' => $value['title'],
                'title_alias' => $value['title_alias'],
                'keywords' => $value['keyword'],
                'description' => $value['description'],
                'post_id' => getItemId(),
                'content' => $value['content'],
                'author' => $value['editor'],
                'click' => $value['click'],
                'main_image' => $value['image_name'],
            ];
            $tags = $cmsPostModel->for_table('cms_post_tag', 'pro')->table_alias('pt')
                ->select_expr('pt.post_id,GROUP_CONCAT(t.tag_name) AS tags')
                ->left_join('cms_tag', ['pt.tag_id', '=', 't.tag_id'], 't')
                ->where('pt.post_id', $value['id'])
                ->group_by_expr('pt.post_id')
                ->find_one();
            if ($tags) {
                $tags = $tags->as_array();
                $post_data['post_tag'] = $tags['tags'];
            }
            $cmsPostModel->addRecord($post_data);
        }
    }

    public function upload()
    {
        $this->display('test/upload');
    }
}