<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/20
 * Time: 10:15
 */

namespace app\controller;

use metacms\base\Page;

class PostController extends BaseController
{
    public function detail()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (empty($id)) {
            trigger_error('页面不存在');
        }
        #获取文章详情
        {
            $param = [
                'post_id' => $id,
            ];
            $result = $this->apiRequest('Post/Post', $param, 'Api');
            if ($result['code'] != 200) {
                trigger_error('页面不存在');
            }
            $result['data']['article']['post_tag'] = explode(',', $result['data']['article']['post_tag']);
            $reg['info'] = $result['data'];
        }
        #获取相关文章
        {
            $param = [
                'post_id' => $result['data']['article']['post_id'],
                'page_size' => 6,
            ];
            $result = $this->apiRequest('post/relatedPost', $param, 'Api');
            $related_article = [];
            if (isset($result['code']) && $result['code'] == 200) {
                foreach ($result['data'] as $value) {
                    $related_article[] = [
                        'title' => $value['title'],
                        'post_id' => $value['post_id'],
                        'main_image' => getImage($value['main_image']),
                    ];
                }
            }
            $reg['related_article'] = $related_article;
        }
        #seo标题
        {
            $seoInfo = [
                'title' => $reg['info']['article']['title'],
                'keywords' => $reg['info']['article']['keywords'],
                'description' => $reg['info']['article']['description']
            ];

        }

        $this->display('Post/detail', $reg, $seoInfo);
    }

    public function tags()
    {
        $tag_name = isset($_GET) ? trim($_GET['tag_name']) : '';
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $page_size = 10;
        #获取最近更新文章
        {
            $param = [
                'p' => $p,
                'page_size' => $page_size,
                'tag_name' => $tag_name,
                'model_id' => 2
            ];
            $result = $this->apiRequest('Post/tagPostList', $param, 'Api');

            if ($result['code'] == 200) {
                $count = $result['data']['count'];
                $list_data = $result['data']['list'];
                $page = new Page($count, $p, $page_size);
                $reg['pages'] = $page->getPageStruct();
                $reg['list_data'] = $list_data;
            }
        }
        #seo标题
        {
            $seoInfo = [
                'title' => $this->getInfo('siteInfo')['site_short_name'] . '首页',
                'keywords' => $this->getInfo('siteInfo')['site_keywords'],
                'description' => $this->getInfo('siteInfo')['site_description'],
            ];
        }
        $this->display('Index/index', $reg, $seoInfo);
    }
}