<?php
/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2022/5/2
 * Time: 12:35
 */

namespace app\blog\controller;


class Post {
    public function index() {
        $res = post_detail(input('request.post_id', ''));
        return view('post/' . $res['template'],$res);
    }
}