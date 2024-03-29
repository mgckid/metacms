<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layuimini-iframe版 v1 - 基于Layui的后台管理系统前端模板</title>
    <meta name="keywords" content="layuimini,layui,layui模板,layui后台,后台模板,layui mini">
    <meta name="description" content="layuimini基于layui的轻量级前端后台管理框架，最简洁、易用的后台框架模板，面向所有层次的前后端程序,只需提供一个接口就直接初始化整个框架，无需复杂操作。">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="/static/admin/layuimini/images/favicon.ico">
    <link rel="stylesheet" href="/static/admin/layuimini/lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/layuimini/css/layuimini.css" media="all">
    <link rel="stylesheet" href="/static/admin/layuimini/lib/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style id="layuimini-bg-color">
    </style>
</head>
<body class="layui-layout-body layuimini-all">
<div class="layui-layout layui-layout-admin">

    <div class="layui-header header">
        <div class="layui-logo">
        </div>
        <a>
            <div class="layuimini-tool"><i title="展开" class="fa fa-outdent" data-side-fold="1"></i></div>
        </a>

        <ul class="layui-nav layui-layout-left layui-header-menu layui-header-pc-menu mobile layui-hide-xs">
        </ul>
        <ul class="layui-nav layui-layout-left layui-header-menu mobile layui-hide-sm">
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="fa fa-list-ul"></i> 选择模块</a>
                <dl class="layui-nav-child layui-header-mini-menu">
                </dl>
            </li>
        </ul>

        <ul class="layui-nav layui-layout-right">

            <li class="layui-nav-item" lay-unselect>
                <a href="javascript:;" data-refresh="刷新"><i class="fa fa-refresh"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a href="javascript:;" data-clear="清理" class="layuimini-clear"><i class="fa fa-trash-o"></i></a>
            </li>
            <li class="layui-nav-item mobile layui-hide-xs" lay-unselect>
                <a href="javascript:;" data-check-screen="full"><i class="fa fa-arrows-alt"></i></a>
            </li>
            <li class="layui-nav-item layuimini-setting">
                <a href="javascript:;">admin</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="javascript:;" data-iframe-tab="/admin/adminuser/edit?id=<?=session('userinfo')['id']?>" data-title="基本资料" data-icon="fa fa-gears">基本资料</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-iframe-tab="/admin/adminuser/changepassword?id=<?=session('userinfo')['id']?>" data-title="修改密码" data-icon="fa fa-gears">修改密码</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" class="login-out">退出登录</a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item layuimini-select-bgcolor mobile layui-hide-xs" lay-unselect>
                <a href="javascript:;" data-bgcolor="配色方案"><i class="fa fa-ellipsis-v"></i></a>
            </li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll layui-left-menu">
        </div>
    </div>

    <div class="layui-body">
        <div class="layui-tab" lay-filter="layuiminiTab" id="top_tabs_box">
            <ul class="layui-tab-title" id="top_tabs">
                <li class="layui-this" id="layuiminiHomeTabId" lay-id=""></li>
            </ul>
            <ul class="layui-nav closeBox">
                <li class="layui-nav-item">
                    <a href="javascript:;"> <i class="fa fa-dot-circle-o"></i> 页面操作</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" data-page-close="other"><i class="fa fa-window-close"></i> 关闭其他</a></dd>
                        <dd><a href="javascript:;" data-page-close="all"><i class="fa fa-window-close-o"></i> 关闭全部</a></dd>
                    </dl>
                </li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div id="layuiminiHomeTabIframe" class="layui-tab-item layui-show">
                </div>
            </div>
        </div>
    </div>

</div>

<!--百度统计代码-开始 正式使用请删除-->
<script>
    var _hmt = _hmt || [];
    (function() {var hm = document.createElement("script");hm.src = "https://hm.baidu.com/hm.js?d97abf6d61c21d773f97835defbdef4e";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm, s);})();
</script>
<!--百度统计代码-结束-->

<script src="/static/admin/layuimini/lib/layui-v2.5.5/layui.js?v=1.0.4" charset="utf-8"></script>
<script src="/static/admin/layuimini/js/lay-config.js?v=1.0.4" charset="utf-8"></script>
<script>
    layui.use(['element', 'layer', 'layuimini'], function () {
        var $ = layui.jquery,
            element = layui.element,
            layer = layui.layer;

        layuimini.init("{:url('admin/index/init')}");

        /*$('.login-out').on("click", function () {
            layer.msg('退出登录成功', function () {
                window.location = '/page/login-1.html';
            });
        });*/

        /*$('.login-out').on("click", function () {
            $.post("{:url('admin/Login/logout')}", {'logout': true}, function (data) {
                if (data.status == 1) {
                    layer.msg('退出登录成功', function () {
                        window.location = "{:url('Login')}";
                    });
                }
            }, 'json')
        });*/
        $('.login-out').on("click", function () {
            $.ajax('/admin/login/logout', {
                context: document.body,
                contentType: "application/x-www-form-urlencoded",//默认
                dataType: 'json',
                type: 'post',
                processData: true,//默认
                async: true,//默认
                global: true,//默认
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    //TODO: 处理status， http status code，超时 408
                    // 注意：如果发生了错误，错误信息（第二个参数）除了得到null之外，还可能
                    //是"timeout", "error", "notmodified" 和 "parsererror"。
                    layer.alert('接口错误:' + textStatus + "(" + errorThrown + ")");
                },
                success: function (res) {
                    if (res.status == 1) {
                        layer.msg('退出登录成功', function () {
                            setTimeout(function () {
                                window.location = '/admin/login/index';
                            },1500)
                        });
                    }
                    layer.alert(res.msg || '接口出错')
                },

            })
        });
    });
</script>
</body>
</html>
