<?php
return array(
    #静态资源目录
    'STATIC_PATH' => $_SERVER['DOCUMENT_ROOT'] . '/',
    #上传路径
    'UPLOAD_PATH' => $_SERVER['DOCUMENT_ROOT'] . '/upload',
    #上传目录
    'UPLOAD_DIR' => 'upload',
    #上传最大尺寸
    'maxSize' => '5MB',
    #图片尺寸
    'IMAGE_SIZE' => array('_120_75', '_160_100', '_300_185', '_250_155', '_320_200'),
    /*http请求设置 开始*/
    /* URL设置 */
    'URL_MODE' => 2, //url访问模式  0：默认动态url传参模式 1：pathinfo模式 2:兼容模式
    /*子域名泛解析设置*/
    'SUB_DOMAIN_OPEN' => false,
    'SUB_DOMAIN_RULES' => [
        'blog' => 'blog',
        'admin' => 'admin',
        'api' => 'api',
    ],
    /*http请求设置 结束*/
    /*Ueditor自定义配置 开始*/
    'UEDITOR_CONFIG' => array(
        'imagePathFormat' => '/upload/{md5}',                   /* 上传图片保存路径,可以自定义保存路径和文件名格式 */
        'scrawlPathFormat' => '/upload/{md5}',                  /* 涂鸦图片上传保存路径,可以自定义保存路径和文件名格式 */
        'snapscreenPathFormat' => '/upload/{md5}',              /* 截图工具上传保存路径,可以自定义保存路径和文件名格式 */
        'catcherPathFormat' => '/upload/{md5}',                 /* 抓取远程图片上传保存路径,可以自定义保存路径和文件名格式 */
        'videoPathFormat' => '/upload/videos/{md5}',           /* 视频上传保存路径,可以自定义保存路径和文件名格式 */
        'filePathFormat' => '/upload/files/{md5}',             /* 文件上传保存路径,可以自定义保存路径和文件名格式 */
        'imageManagerListPath' => '/upload/',                   /* 指定要列出图片的目录 */
        'fileManagerListPath' => '/upload/files/',             /* 指定要列出文件的目录 */
        'imageMaxSize' => '5120000',                              /* 图片上传大小限制，单位B */
    ),
    /*Ueditor自定义配置 结束*/
);