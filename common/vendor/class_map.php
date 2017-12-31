<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23
 * Time: 11:24
 */
return array(
    /*admin 模块使用 类*/

    /*UEditor编辑器通用上传类*/
    'Ueditor' => __DIR__ . '/Ueditor',
    /*玻森中文语义开放平台语义分析API*/
    'BosonNLP' => __DIR__ . '/BosonNLP',
    #基于词库的中文转拼音优质解决方案 http://overtrue.me/pinyin
    'Overtrue\Pinyin' => __DIR__ . '/overtrue/pinyin/src',
    #简单的 PHP 类注释解析类
    'DocBlockReader' => __DIR__ . '/php-simple-annotations/src/DocBlockReader',
    #session 会话管理类
    'Aura\Session' => __DIR__ . '/Aura.Session-2.x/src',
    #上传类(File uploads with validation and storage strategies)
    'Upload' => __DIR__ . '/Upload-master/src/Upload/',
    #钩子组件
    'Hook' => __DIR__ . '/Hook',

    /*前台模块 使用类*/
    #PHP Curl Class makes it easy to send HTTP requests and integrate with web APIs https://www.phpcurlclass.com
    'Curl' => __DIR__ . '/php-curl-class-master/src/Curl',
);