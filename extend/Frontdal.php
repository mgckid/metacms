<?php
/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2022/4/5
 * Time: 13:26
 * @property \think\facade\Cache $cache_instance;
 */

class Frontdal {
    private static $instance;
    private $post_page_instance = null;
    private $attribute = null;

    private function __construct() {
    }

    private function __clone() {
        // TODO: Implement __clone() method.
    }

    public static function ins() {
        return self::getInstance();
    }


    public function __sleep() {
        // TODO: Implement __sleep() method.
        return ['post_page_instance', 'attribute'];
    }

    public static function getInstance() {
        if (!is_object(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //友情链接列表
    public function site_flink($param = []) {
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/siteflink/getall', $param);
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] = $res['data']['record'] ?? [];
            $this->attribute[$key] = $res;
        }
        return $res['data']['record'] ?? [];
    }

    public function siteConfig() {

    }

    //所有栏目
    public function category($param = []) {
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/cmscategory/getall', $param);
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] = $res['data']['record'] ?? [];
            $this->attribute[$key] = $res;
        }
        return $res['data']['record'] ?? [];
    }

    //栏目详情
    public function category_detail($category_id) {
        if (is_scalar($category_id)) {
            if (is_numeric($category_id)) {
                $param['id'] = $category_id;
            } else {
                $param['category_alias'] = $category_id;
            }
        } else {
            $param = $category_id;
        }
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/cmscategory/getone', $param);
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] = $res['data']['record'] ?? [];
            $this->attribute[$key] = $res;
        }
        return $res['data']['record'] ?? [];
    }

    //所有文档
    public function post($param = []) {
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/cmspost/index', $param);
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] =  $res['data']['record']??[];
            $res['data']['total'] =  $res['data']['total']??[];
            $this->attribute[$key] = $res;
        }
        //$list = $res['data']['record'] = $res['data']['record'] ?? [];
        //$this->post_page_instance = new Page($res['data']['total'] ?? 0, input('get.page',1), $res['data']['limit'] ?? 10, false, 'ajaxPage', 'cn', false);
        return $res['data'];
    }

    /**
     * @return Page
     */
    public function post_page() {
        return $this->post_page_instance;
    }

    //文档详情
    public function post_detail($post_id) {
        if (is_scalar($post_id)) {
            $param['post_id'] = $post_id;
        } else {
            $param = $post_id;
        }
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/cmspost/getone', $param);
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] = $res['data']['record'] ?? [];
            $this->attribute[$key] = $res;
        }
        return $res['data']['record'] ?? [];
    }

    //获取上一个下一个文档
    public function getPreNext($post_id) {
        if (is_scalar($post_id)) {
            $param = compact('post_id');
        } else {
            $param = $post_id;
        }
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/cmspost/getPreNext', $param);
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] = $res['data']['record'] ?? [];
            $this->attribute[$key] = $res;
        }
        return $res['data']['record'] ?? [];
    }

    //获取关联文档
    public function getRelated($post_id,$limit) {
        $param = compact('post_id','limit');
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/cmspost/getRelated', $param);
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] = $res['data']['record'] ?? [];
            $this->attribute[$key] = $res;
        }
        return $res['data']['record'] ?? [];
    }

    //站点详情
    public function site_info() {
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/site/getall');
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] = $res['data']['record'] ?? [];
            $this->attribute[$key] = $res;
        }
        return $res['data']['record'][0] ?? [];
    }

    //文档标签
    public function post_tag() {
        $key = md5(__FUNCTION__ . serialize(func_get_args()));
        if (!$res = $this->attribute[$key] ?? []) {
            $res = appRequest('appdal/cmspost/posttag');
            $res['data'] = $res['data'] ?? [];
            $res['data']['record'] = $res['data']['record'] ?? [];
            $this->attribute[$key] = $res;
        }
        return $res['data']['record'];
    }
}