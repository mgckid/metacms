<?php
declare (strict_types=1);

namespace app\admin\controller;

use think\facade\Request;
use \think\facade\View;
use think\response\Json;

class Index extends UserBase {
    //后台框架页面
    public function index() {
        return View::fetch('Index/index');
    }

    public function welcome() {
        return view('index/welcome');
    }

    public function init() {
        $menu = [];
        foreach (getAccess() as $value) {
            if ($value['level'] == 1) {
                $menu[$value['parent']] = array(
                    'title' => $value['access_name'],
                    'href' => $value['href'],
                    'icon' => 'fa fa-navicon',
                    'target' => '_self',
                );
            }
        }
        foreach (getAccess() as $value) {
            if ($value['level'] == 2) {
                $menu[$value['parent']]['child'][] = array(
                    'title' => $value['access_name'],
                    'href' => $value['href'],
                    'icon' => 'fa fa-navicon',
                    'target' => '_self',
                );
            }
        }
        $arr = array(
            'clearInfo' =>
                array(
                    'clearUrl' => '/admin/index/clear',
                ),
            'homeInfo' =>
                array(
                    'title' => '首页',
                    'icon' => 'fa fa-home',
                    'href' => '/admin/index/welcome',
                ),
            'logoInfo' =>
                array(
                    'title' => 'METADMIN',
                    'image' => '/static/admin/layuimini/images/logo.png',
                    'href' => '',
                ),
            'menuInfo' =>
                array(
                    'cms' =>
                        array(
                            'title' => '内容管理',
                            'icon' => 'fa fa-lemon-o',
                            'child' => []
                        ),
                    'user' =>
                        array(
                            'title' => '用户管理',
                            'icon' => 'fa fa-address-book',
                            'child' => [],
                        ),
                    'advertisement' =>
                        array(
                            'title' => '广告管理',
                            'icon' => 'fa fa-bar-chart',
                            'child' => [],
                        ),
                    'site' =>
                        array(
                            'title' => '站点管理',
                            'icon' => 'fa fa-institution',
                            'child' => [],
                        ),
                    'other' =>
                        array(
                            'title' => '其它管理',
                            'icon' => 'fa fa-puzzle-piece',
                            'child' => []
                        ),
                ),
        );
        foreach ($menu as $key => $value) {
            if (stripos($key, 'admin/admin') !== false) {
                $arr['menuInfo']['user']['child'][] = $value;
            } elseif (stripos($key, 'admin/cms') !== false) {
                $arr['menuInfo']['cms']['child'][] = $value;
            } elseif (stripos($key, 'admin/advertisement') !== false) {
                $arr['menuInfo']['advertisement']['child'][] = $value;
            } elseif (stripos($key, 'admin/site') !== false) {
                $arr['menuInfo']['site']['child'][] = $value;
            } else {
                $arr['menuInfo']['other']['child'][] = $value;
            }
        }
        return \json($arr);
    }

    public function ajaxCheckPower() {
        $access = Request::param('access', []);
        $data = [];
        foreach ($access as $key => $value) {
            $data[$value] = 1;
            //unset($access[$key]);
        }
        return \json(success($data));
    }

    public function clear() {
        $root_path = app()->getRootPath() . 'runtime' . DIRECTORY_SEPARATOR;
        $handle = opendir($root_path);
        $arr = [];
        /* 这是正确地遍历目录方法 */
        while (false !== ($file = readdir($handle))) {
            if (in_array($file, ['.', '..', '.gitignore', 'session', 'log'])) {
                continue;
            }
            $arr[] = $root_path . $file;
        }
        array_map(function ($path) {
            delete_dir($path);
        }, $arr);
        return \json(['code' => 1, 'msg' => '服务端清理缓存成功']);
    }
}
