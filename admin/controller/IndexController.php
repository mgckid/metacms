<?php

namespace app\controller;


/**
 * Class IndexController
 * @package app\controller
 */
class IndexController extends UserBaseController
{

    function index()
    {
        $this->display('Index/index');
    }

}

?>
