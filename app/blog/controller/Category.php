<?php
/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2022/4/24
 * Time: 23:21
 */

namespace app\blog\controller;


class Category {

    public function index() {
        $res = category_detail();
        return view('category/'.$res['template']);
    }
}