<?php
/**
 * Created by PhpStorm.
 * User: metacms
 * Date: 2017/8/28
 * Time: 15:37
 */

namespace app\controller;


use metacms\base\Dex3;
use metacms\base\Hooks;

class TestController extends BaseController
{
    public function index()
    {
        //添加钩子
        Hook::getInstance()->add_action('test_action', 'print_g');
        //执行钩子
        hook::getInstance()->do_action('test_action', $this->getInfo('siteInfo'));//也可以使用 Hook::do_action();
        //  $this->display('Test/index', []);
    }

    public function des()
    {
        $dex = new Dex3('123456');
        //echo $dex->encrypt(json_encode($_SERVER));
        $aaa = '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';
        $bbb = $dex->decrypt($aaa);
        print_g(json_decode($bbb));
    }
}
