<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/10/9
 * Time: 12:23
 */

namespace app\controller;


use metacms\api\Controller;
use metacms\base\Dex3;

class BaseController extends Controller
{
    public function getRequestParam()
    {
        if (C('API_ENCRYPTION')) {
            if (!isset($_REQUEST['param']) && empty($_REQUEST['param'])) {
                $this->response('接口请求错误', self::S400_BAD_REQUEST);
            }
            $dex3 = new Dex3();
            $encode_str = base64_decode($_REQUEST['param']);
            $result = json_decode($dex3->decrypt($encode_str), true);
        } else {
            $result = $_REQUEST;
        }
        return $result;
    }
} 