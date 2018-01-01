<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/4/22
 * Time: 11:45
 */

namespace app\controller;


use metacms\base\Page;


class IndexController extends BaseController
{
    /**
     * 博客首页
     */
    public function index()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $page_size = 10;
        #获取最近更新文章
        {
            $param = [
                'model_id' => 2,
                'p' => $p,
                'page_size' => $page_size,
                'sort' => 'desc'
            ];
            $result = $this->apiRequest('post/postList', $param, 'Api');
            if ($result['code'] == 200) {
                $count = $result['data']['count'];
                $list_data = $result['data']['post_list'];
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

    public function category()
    {
        $cate = isset($_GET['cate']) ? trim($_GET['cate']) : '';
        if (!$cate) {
            trigger_error('页面不存在');
        }
        #获取栏目数据
        {
            $param = [
                'category_alias' => $cate
            ];
            $result = $this->apiRequest('Post/category', $param, 'Api');
            if ($result['code'] == 200) {
                $category_info = $result['data']['category_info'];
            }
        }
        #处理不同模型
        switch ($category_info['category_type']) {
            #文档列表
            case 'list':
                $this->categoryArticle($category_info);
                break;
            #单页
            case 'page':
                $this->categoryPage($category_info);
                break;
            #单页
            case 'channel':
                $this->categoryPage($category_info);
                break;
        }
    }

    protected function categoryArticle($category_info)
    {
        $page_size = 10;
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        #获取栏目文档列表
        {
            $param = [
                'category_id' => $category_info['id'],
                'page_size' => $page_size,
                'p' => $p
            ];
            $result = $this->apiRequest('Post/categoryPosts', $param, 'Api');
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
                'title' => $category_info['category_name'],
                'keywords' => $category_info['keywords'],
                'description' => $category_info['description'],
            ];
        }
        $reg['category_info'] = $category_info;
        $this->display('Index/categoryArticle', $reg, $seoInfo);
    }

    protected function categoryPage($category_info)
    {

        #获取栏目文档列表
        {
            $param = [
                'category_id' => $category_info['id']
            ];
  /*          $result = $this->apiRequest('Post/categoryPosts', $param, 'Api');
            if ($result['code'] == 200) {
                $list_data = $result['data']['list'];
                $list = [];
                foreach ($list_data as $value) {
                    $list[$value['title']] = $value;
                }
                $reg['list_data'] = $list;
            }*/
        }
        #seo标题
        {
            $seoInfo = [
                'title' => $category_info['category_name'],
                'keywords' => $category_info['keywords'],
                'description' => $category_info['description'],
            ];
        }
        $reg['category_info'] = $category_info;
        $this->display('Index/categoryPage', $reg, $seoInfo);
    }

    public function search()
    {
        $page_size = 10;
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $keyword = isset($_GET['s']) && !empty($_REQUEST['s']) ? trim($_REQUEST['s']) : '';
        if (empty($keyword)) {
            trigger_error('请输入关键字搜索');
        }
        #获取搜索数据
        {
            $param = [
                'keyword' => $keyword,
                'page_size' => $page_size,
                'p' => $p
            ];
            $result = $this->apiRequest('Post/search', $param, 'Api');
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
                'title' => "“{$keyword}”的搜索结果",
                'keywords' => $this->getInfo('siteInfo')['site_keywords'],
                'description' => $this->getInfo('siteInfo')['site_description'],
            ];
        }
        $this->display('Index/search', $reg, $seoInfo);
    }


}