<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2016/10/8
 * Time: 14:14
 */


/**
 * 获取图片
 *
 * @param $name
 * @return string
 */
function getImage($name, $size = '')
{
    if (empty($name)) {
        return '/static/default.jpg';
    }
    $uploadPath = C('UPLOAD_PATH');
    $staticPath = C('STATIC_PATH');
    $staticUrl = '/';
    $imagePath = $uploadPath . '/' . $name;
    if (!empty($size)) {
        $handName = function ($name, $size) {
            $names = explode('.', $name);
            $ext = array_pop($names);
            $newName = array_pop($names);
            return implode('.', array($newName . $size, $ext));
        };
        $imageName = $handName($name, $size);
        $thumbPath = $uploadPath . '/' . $imageName;
        if (file_exists($imagePath) && !file_exists($thumbPath)) {
            $_size = explode('_', substr($size, 1));
            $imageManage = new \Intervention\Image\ImageManager(array('driver' => 'gd'));
            $imageManage->make($imagePath)
                ->resize($_size[0], $_size[1])
                ->save($thumbPath);
        }
        $imagePath = $thumbPath;
    }
    $_image = str_replace($staticPath, $staticUrl, $imagePath);
    return $_image;
}

/**
 * 获取上传存储路径
 *
 * @param string $mode
 * @return string|type
 */
function getFilePath($mode = '')
{
    $uploadPath = C('UPLOAD_PATH');
    switch ($mode) {
        case 'cms':
            $uploadPath .= '/cms';
            break;
        case 'ad':
            $uploadPath .= '/ad';
            break;
        default:
            $uploadPath .= '';
    }
    return $uploadPath;
}

/**
 * 无线分类 一层结构
 * @param $cate
 * @param int $pid
 * @param int $layer
 * @param string $placeHolder
 * @param string $placeHolderBegin
 * @return array
 */
function treeStructForLevel($cate, $pid = 0, $layer = 0, $placeHolder = '--', $placeHolderBegin = '|')
{
    static $data = [];
    foreach ($cate as $value) {
        if ($value['pid'] == $pid) {
            $placeHolderBegin = $layer == 0 ? '' : $placeHolderBegin;
            $value['placeHolder'] = $placeHolderBegin . str_repeat($placeHolder, $layer);
            $data[] = $value;
            treeStructForLevel($cate, $value['id'], $layer + 1);
        }
    }
    return $data;
}


function treeStructForLayer($cate, $pid = 0)
{
    $data = [];
    foreach ($cate as $value) {
        if ($value['pid'] == $pid) {
            $value['sub'] = treeStructForLayer($cate, $value['id']);
            $data[] = $value;
        }
    }
    return $data;
}

function getParents($cate, $id)
{
    static $data = [];
    foreach ($cate as $value) {
        if ($value['id'] == $id) {
            $data[] = $value;
            getParents($cate, $value['pid']);
        }
    }
    $sort = array_column($data, 'id');
    array_multisort($sort, $data, SORT_ASC);
    return $data;
}

function getChilden($cate, $id)
{
    static $data = [];
    foreach ($cate as $value) {
        if ($value['pid'] == $id) {
            $data[] = $value;
            getChilden($cate, $value['id']);
        }
    }
    return $data;
}

/**
 * 获取日期时间
 * @param string $format
 * @return bool|string
 */
function getDateTime($format = 'Y-m-d H:i:s')
{
    return date($format, time());
}


/**
 * 生成itemId
 * @return string
 */
function getItemId()
{
    $hour = date('z') * 24 + date('H');
    $hour = str_repeat('0', 4 - strlen($hour)) . $hour;
    //	echo date('y') . $hour . PHP_EOL;
    return date('y') . $hour . getRandNumber(10);
}

/**
 * 生成固定长度的随机数
 *
 * @param int $length
 * @return string
 */
function getRandNumber($length = 6)
{
    $num = '';
    if ($length >= 10) {
        $t = intval($length / 9);
        $tail = $length % 9;
        for ($i = 1; $i <= $t; $i++) {
            $num .= substr(mt_rand('1' . str_repeat('0', 9), str_repeat('9', 10)), 1);
        }
        $num .= substr(mt_rand('1' . str_repeat('0', $tail), str_repeat('9', $tail + 1)), 1);
        return $num;
    } else {
        return substr(mt_rand('1' . str_repeat('0', $length), str_repeat('9', $length + 1)), 1);
    }
}


/**
 * curl方法
 * @return \Curl\Curl
 */
function curl()
{
    $container = \metacms\base\Application::container();
    if (!$container->offsetExists('curl')) {
        $container['curl'] = function ($c) {
            $curl = new \Curl\Curl();
            if (ENVIRONMENT == 'develop') {
                $curl->setOpt(CURLOPT_PROXY, '127.0.0.1:7777');
            }
            $curl->setOpt(CURLOPT_TIMEOUT, 60);
            return $curl;
        };
    };
    return $container['curl'];
}

/**
 * 请求接口方法
 * @param $url
 * @param array $data
 * @param string $mode
 * @param string $method
 * @return mixed
 */
function apiRequest($url, $data = [], $mode = 'Api', $method = 'get')
{
    if ($mode == 'Api') {
        $host = C('HOME_URL');
    }
    if (empty($host)) {
        trigger_error('接口地址不存在');
    }
    #生产环境接口加密
    if (ENVIRONMENT !== 'develop') {
        $dex3 = new metacms\base\Dex3();
        $encode_data = base64_encode($dex3->encrypt(json_encode($data)));
        $data = [
            'param' => $encode_data
        ];
    }
    if ($method == 'get') {
        #返回缓存内容
        $cache_key = md5(json_encode($data));
        $cache_name = $url;
        $cache_time = ENVIRONMENT == 'product' ? 300 : 1;
        if (\metacms\base\Application::cache($cache_name)->isCached($cache_key) && C('CACHEING')) {
            $result = \metacms\base\Application::cache($cache_name)->retrieve($cache_key);
        } else {
            $url = $host . U('api/' . $url, $data);
            $response = curl()->get($url);
            if (!is_object($response)) {
                trigger_error('接口请求错误');
            }
            $result = json_decode(json_encode($response), true);
            if (isset($result['cached']) && $result['cached']) {
                \metacms\base\Application::cache($cache_name)->store($cache_key, $result, $cache_time);
                \metacms\base\Application::cache($cache_name)->eraseExpired();
            }
        }
        #apcu
//        $cache_key = md5($cache_key . $cache_name);
//        if (apcu_exists($cache_key) && C('CACHEING')) {
//            $result = apcu_fetch($cache_key);
//        } else {
//            $url = $host . U('api/' . $url, $data);
//            $response = curl()->get($url);
//            if (!is_object($response)) {
//                trigger_error('接口请求错误');
//            }
//            $result = json_decode(json_encode($response), true);
//            if (isset($result['cached']) && $result['cached']) {
//                apcu_add($cache_key, $result, $cache_time);
//            }
//        }
    }
    return $result;
}



function main_image($request_data)
{
    $field = 'main_image';
    $model_id = $request_data['model_id'];
    $main_image = isset($request_data['main_image']) ? $request_data['main_image'] : '';
    if ($model_id == 2 && empty($main_image)) {
        $content = htmlspecialchars_decode($request_data['content']);
        $img = getImageFromContent($content);
        if ($img) {
            $request_data[$field] = getImageUrlFromUrl(current($img));
        }
    }
    return $request_data;
}
\metacms\base\Application::hooks()->add_filter('publish_post', 'main_image');


function getImageFromContent($content)
{
    //匹配IMG标签
    $content = htmlspecialchars_decode($content);
    $img_pattern = "/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i";
    preg_match_all($img_pattern, $content, $img_out);
    return $img_out[2];
}

function getImageUrlFromUrl($url)
{
    $_url = explode('/', $url);
    return end($_url);
}




