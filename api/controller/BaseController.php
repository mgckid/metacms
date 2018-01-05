<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/10/9
 * Time: 12:23
 */

namespace app\controller;


use metacms\api\Controller;
use metacms\base\Des3;

class BaseController extends Controller
{
    public function getRequestParam()
    {
        $key = C('API_ENCRYPTION_KEY');
        if ($key) {
            if (!isset($_REQUEST['param']) && empty($_REQUEST['param'])) {
                $this->response('接口请求错误', self::S400_BAD_REQUEST);
            }
            $dex3 = new Des3($key);
            $encode_str = base64_decode($_REQUEST['param']);
            $result = json_decode($dex3->decrypt($encode_str), true);
        } else {
            $result = $_REQUEST;
        }
        return $result;
    }
} 