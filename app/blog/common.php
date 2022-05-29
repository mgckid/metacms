<?php
// 这是系统自动生成的公共文件
/**
 * @return object|\think\Http
 */
function app_http() {
    return app('http');
}

/**
 * @return object|\think\Request
 */
function app_request() {
    return app('request');
}

function cache_start() {
    return false;
}

//站点详情
function site_info() {
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $res = Frontdal::getInstance()->site_info();
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $res;
}

function site_name() {
    return site_info()[__FUNCTION__] ?? '';
}

function site_short_name() {
    $field = str_replace('site_', '', __FUNCTION__);
    return site_info()[$field] ?? '';
}

function site_keywords() {
    $field = str_replace('site_', '', __FUNCTION__);
    return site_info()[$field] ?? '';
}

function site_description() {
    $field = str_replace('site_', '', __FUNCTION__);
    return site_info()[$field] ?? '';
}

function site_icp_code() {
    $field = str_replace('site_', '', __FUNCTION__);
    return site_info()[$field] ?? '';
}

function site_name_cn() {
    $field = str_replace('site_', '', __FUNCTION__);
    return site_info()[$field] ?? '';
}

function category($param = []) {
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $res = Frontdal::getInstance()->category($param);
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $res;
}

function category_detail($category_id = null, $bind = true) {
    $category_id = $category_id ?? input('request.cate');
    $index_key = is_numeric($category_id) ? 'id' : 'category_alias';
    $category = array_column(category(), null, $index_key);
    $res = $category[$category_id] ?? [];
    if ($bind) {
        $res['keywords'] = $res['keywords'] ?: site_keywords();
        $res['description'] = $res['description'] ?: site_description();
        $res['list_template'] = $res['list_template'] ?: $res['model_id'] . '_list';
        $res['detail_template'] = $res['detail_template'] ?: $res['model_id'] . '_detail';
        $res['channel_template'] = $res['channel_template'] ?: $res['model_id'] . '_channel';
        $res['page_template'] = $res['model_id'] . '_page';
        $res['template'] = $res[$res['category_type'] . '_template'];
    }
    return $res;
}

function category_name($category_id = null) {
    return category_detail($category_id, false)['category_name'] ?? '';
}

function category_keywords($category_id = null) {
    return category_detail($category_id, false)['keywords'] ?? '';
}

function category_description($category_id = null) {
    return category_detail($category_id, false)['description'] ?? '';
}

function category_alias($category_id = null) {
    return category_detail($category_id, false)['category_alias'] ?? '';
}

function category_url($category_id = null) {
    return url('category/index', ['cate' => category_alias($category_id)]);
}

function category_content($category_id = null) {
    $res = category_detail($category_id, false)['content'] ?? '';
    return htmlspecialchars_decode($res);
}

function navicat() {
    $res = category([]);
    $data = [];
    foreach ($res as $value) {
        if ($value['displayed'] != 1) {
            continue;
        }
        $data[] = [
            'name' => $value['category_name'] ?? '',
            'url' => url('category/index', ['cate' => $value['category_alias']]),
            'cate' => $value['category_alias'],
        ];
    }
    return $data;
}

function category_active($catrgory, $active_class = 'active') {
    $curr_cate = input('request.cate', '');
    $category_alias = $catrgory['cate'] ?? '';
    return $curr_cate == $category_alias ? $active_class : '';
}

function index_url() {
    return app_request()->rootUrl();
}

function site_flink() {
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $res = Frontdal::getInstance()->site_flink();
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $res;
}

function _post(array $param, $limit) {
    $input = input('request.', []);
    $category_alias = $input['cate'] ?? '';
    if ($category_alias) {
        $category_detail = category_detail($category_alias, false);
        $input['category_id'] = $category_detail['id'] ?? null;
    }
    $param = array_merge($param, $input);
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $param['limit'] = $limit;
        $res = Frontdal::getInstance()->post($param);
        foreach ($res['record'] as &$value) {
            $category_detail = category_detail($value['category_id'], false);
            $value['category_alias'] = $category_detail['category_alias'] ?? '';
            $value['category_name'] = $category_detail['category_name'] ?? '';
        }
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $res ?? [];
}

function post($param = [], $limit = 20) {
    $res = _post($param, $limit);
    return $res['record'] ?? [];
}

//分页
function post_page($param = [], $limit = 20) {
    $res = _post($param, $limit);
    $pages = new Page($res['total'] ?? 0, $res['page'] ?? 1, $limit, false, 'ajaxPage', 'cn', false);
    return htmlspecialchars_decode($pages);
}

function post_detail($post_id) {
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $res = Frontdal::getInstance()->post_detail($post_id);
        $cate = category_detail($res['category_id']);
        $res['template'] = $cate['detail_template'] ?? '';
        $res['post_tag'] = $res['post_tag'] ? explode(',', $res['post_tag']) : [];
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $res;
}

function getPreNext($post_id) {
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $res = Frontdal::getInstance()->getPreNext($post_id);
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $res;
}

function getRelated($post_id, $limit) {
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $res = Frontdal::getInstance()->getRelated($post_id, $limit);
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $res;
}

function pre_url($post_id) {
    $res = getPreNext($post_id);
    if ($res['pre']) {
        return url('post/index', ['post_id' => $res['pre']['post_id']]);
    } else {
        return '';
    }
}

function pre_title($post_id) {
    $res = getPreNext($post_id);
    return $res['pre'] ? $res['pre']['title'] : '';
}

function next_url($post_id) {
    $res = getPreNext($post_id);
    if ($res['next']) {
        return url('post/index', ['post_id' => $res['next']['post_id']]);
    } else {
        return '';
    }
}

function next_title($post_id) {
    $res = getPreNext($post_id);
    return $res['next'] ? $res['next']['title'] : '';
}


function post_next($post_id) {
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $res = Frontdal::getInstance()->getPreNext($post_id);
        $res = ['title' => $res['next']['title'], 'url' => url('post/index', ['post_id' => $res['next']['post_id']])];
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $res;
}


//文章标签
function post_tag($limit = 0) {
    $key = app_http()->getname() . __FUNCTION__ . md5(serialize(func_get_args()));
    if (!$res = \think\facade\Cache::get($key)) {
        $res = Frontdal::getInstance()->post_tag();
        if (cache_start()) \think\facade\Cache::set($key, $res, 3600 * 24);
    }
    return $limit ? array_slice($res, 0, $limit) : $res;
}




