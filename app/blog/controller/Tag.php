<?php
/**
 * Created by PhpStorm.
 * User: mgckid
 * Date: 2022/4/24
 * Time: 23:21
 */

namespace app\blog\controller;


class Tag {

    public function index() {
        return view('tag/index');
    }

    public function post() {
        return view('tag/post');
    }
}