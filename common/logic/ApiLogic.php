<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21
 * Time: 21:07
 */

namespace app\logic;

use metacms\base\Application;

class ApiLogic extends BaseLogic
{

    public function apiRequest($url, $data = [], $mode = 'Api', $method = 'get')
    {

        if ($mode == 'Api') {
            $host = C('API_URL');
            $url = 'Api/' . $url;
        }
        if (empty($host)) {
            trigger_error('接口地址不存在');
        }
        #生产环境接口加密
        if (ENVIRONMENT !== 'develop') {
            $dex3 = new Dex3();
            $encode_data = base64_encode($dex3->encrypt(json_encode($data)));
            $data = [
                'param' => $encode_data
            ];
        }
        if ($method == 'get') {
            #返回缓存内容
            $cache_key = md5(json_encode($data));
            $cache_name = $url;
            if (Application::cache($cache_name)->isCached($cache_key)) {
                $result = Application::cache($cache_name)->retrieve($cache_key);
            } else {
                $url = $host . U($url, $data);
                $response = $this->curl()->get($url);
                if (!is_object($response)) {
                    trigger_error('接口请求错误');
                }
                $result = json_decode(json_encode($response), true);
                if (isset($result['cached']) && $result['cached']) {
                    Application::cache($cache_name)->store($cache_key, $result, 300);
                    Application::cache($cache_name)->eraseExpired();
                }
            }
        }
        return $result;
    }

    /**
     * 获取文档列表
     * @access public
     * @author furong
     * @param $p
     * @param $page_size
     * @param $model_id
     * @param string $sort
     * @return mixed|string
     * @since 2017年12月27日 14:15:07
     * @abstract
     */
    public function postList($p, $page_size, $model_id, $sort = 'desc')
    {
        $param = [
            'model_id' => $model_id,
            'p' => $p,
            'page_size' => $page_size,
            'sort' => 'desc'
        ];
        $result = $this->apiRequest('post/postList', $param, 'Api');
        return $result;
    }

    /**
     * 获取站点配置
     * @access public
     * @author furong
     * @return void
     * @since 2017年12月27日 12:38:24
     * @abstract
     */
    public function siteConfig()
    {
        $result = $this->apiRequest('Site/siteConfig', [], 'Api');
        return $result;
    }

    /**
     * 获取友情链接
     * @access public
     * @author furong
     * @return void
     * @since 2017年12月27日 12:38:24
     * @abstract
     */
    public function siteFriendLink()
    {
        $result = $this->apiRequest('Site/flink', [], 'Api');
        return $result;
    }

    /**
     * 获取站点导航
     * @access public
     * @author furong
     * @return void
     * @since 2017年12月27日 12:38:24
     * @abstract
     */
    public function siteNavigation()
    {
        $result = $this->apiRequest('Post/siteNavigation', [], 'Api');
        return $result;
    }

    /**
     * 获取栏目数据
     * @access public
     * @author furong
     * @param $category_alias
     * @return void
     * @since 2017年12月27日 14:20:39
     * @abstract
     */
    public function postCategory($category_alias)
    {
        if (is_string($category_alias)) {
            $param['category_alias'] = $category_alias;
        } elseif (is_int($category_alias)) {
            $param['category_id'] = $category_alias;
        }
        $result = $this->apiRequest('Post/category', $param, 'Api');
        return $result;
    }

    /**
     * 热门文章
     * @access public
     * @author furong
     * @param $model_id
     * @param $page_size
     * @return mixed|string
     * @since 2017年12月27日 14:29:25
     * @abstract
     */
    public function postHostPost($model_id, $page_size)
    {
        $param['model_id'] = $model_id;
        $param['page_size'] = $page_size;
        $result = $this->apiRequest('Post/hotPost', $param, 'Api');
        return $result;
    }

    /**
     * 标签列表
     * @access public
     * @author furong
     * @return mixed|string
     * @since 2017年12月27日 14:37:05
     * @abstract
     */
    public function postTaglist()
    {
        $param = [];
        $result = $this->apiRequest('Post/tagList', $param, 'Api');
        return $result;
    }

    /**
     * 获取标签内容列表
     * @access public
     * @author furong
     * @param $p
     * @param $page_size
     * @param $tag_name
     * @return mixed|string
     * @since 2017年12月27日 14:40:40
     * @abstract
     */
    public function postTags($p, $page_size, $tag_name)
    {
        $param = [
            'p' => $p,
            'page_size' => $page_size,
            'tag_name' => $tag_name
        ];
        $result = $this->apiRequest('Post/tags', $param, 'Api');
        return $result;
    }

    /**
     * 获取相关文章
     * @access public
     * @author furong
     * @param $post_id
     * @param $page_size
     * @return mixed|string
     * @since 2017年12月27日 14:42:52
     * @abstract
     */
    public function postRelatedArticles($post_id, $page_size)
    {
        $param = [
            'post_id' => $post_id,
            'page_size' => $page_size,
        ];
        $result = $this->apiRequest('post/relatedArticles', $param, 'Api');
        return $result;
    }

    public function postCategoryPosts(){

    }

}