<?php
declare (strict_types=1);

namespace app\appdal\controller;

use think\facade\App;

class Index {
    public function index() {
        \ModelGenerate::getInstance()->generate_path = 'D:\www\gitee\mystudy\php_study\tp6\app\appdal';
        /*$all = \ModelGenerate::getInstance()->get_all_table('mysql', 'test_furong_link');
        foreach ($all as $value) {
            $res = \ModelGenerate::getInstance()->getCmsModel($value, true);
            $res2 = \ModelGenerate::getInstance()->getCmsController($value, true);
        }*/
        $res = \ModelGenerate::getInstance()->getCmsModel('admin_role_access', 1);
        $res2 = \ModelGenerate::getInstance()->getCmsController('cms_category', 1);
        $res3 = \ModelGenerate::getInstance()->getAdminController('cms_category', 1);
        print_g($res, $res2, $res3);
        //print_g(\ModelGenerate::getInstance()->getCloudAmazonService('mysql', 'test_furong_link', 'site_flink'));
        //return '您好！这是一个[appdal]示例应用';
    }
}
