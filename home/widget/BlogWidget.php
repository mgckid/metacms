<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/4/24
 * Time: 16:46
 */
namespace app\widget;

use app\Controller\BaseController;
use app\model\CmsPostModel;
use metacms\base\Application;
use metacms\web\View;
use app\model\CmsTagModel;


class BlogWidget extends BaseController
{
    /**
     * 热门文章
     * @access public
     * @author furong
     * @param $limit
     * @param $cateId
     * @return \metacms\base\type
     * @since 2017年4月24日 16:55:34
     * @abstract
     */
    public function hotArticle($page_size, $cateId = '')
    {
        #获取热门标签
        {
            $param = [
                'page_size' => $page_size,
                'model_id' => 2
            ];
            $result = $this->apiRequest('Post/hotPost', $param, 'Api');
            $posts = $result['data'];
            $reg['posts'] = $posts;
        }
        return View::render('Common/hotArticle', $reg);
    }

    /**
     * 获取热门标签
     * @access public
     * @author furong
     * @param $limit
     * @return \metacms\base\type
     * @since 2017年4月24日 17:22:24
     * @abstract
     */
    public function hotTag()
    {
        #获取热门标签
        {
            $result = $this->apiRequest('Post/tagList', [], 'Api');
            $tag_list = $result['data'];
            $reg['tag_list'] = $tag_list;
        }
        return View::render('Common/hotTag', $reg);
    }

    public function flink()
    {
        #获取热门标签
        {
            $result = $this->apiRequest('Site/flink', [], 'Api');
            $tag_list = $result['data'];
            $reg['list'] = $tag_list;
        }
        return View::render('Common/flink', $reg);
    }
}