<?php
declare (strict_types = 1);

namespace app\blog\controller;

class Index
{
    public function index()
    {
        return view('index/index');
    }
}
