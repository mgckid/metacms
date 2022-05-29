<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title>{block name='title'}{:site_name()}{/block}</title>
    <meta name="keywords" content="{block name='keywords'}{:site_keywords()}{/block}">
    <meta name="description" content="{block name='description'}{:site_description()}{/block}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='stylesheet' id='lean-toolkit-css'  href='/static/writing/css/toolkit.css' type='text/css' media='all' />
    <link rel='stylesheet' id='lean-font-awesome-css'  href='/static/writing/css/font-awesome.min.css' type='text/css' media='all' />
    <link rel="shortcut icon"  href="/favicon.ico">
    <style type="text/css" id="custom-background-css">
        body.custom-background { background-color: #f8f9fa; }
    </style>
    <script type='text/javascript' src='https://cdn.bootcss.com/jquery/3.2.0/jquery.min.js'></script>
    <script type='text/javascript' src='https://cdn.bootcss.com/bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?898efc58954751f91e409e4bf32c2b45";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body class="home blog custom-background" >
<style type="text/css">
    .pagination li.active a {
        z-index: 2;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .pagination li a {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #ddd;
    }
</style>
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-shadow navbar-dark bg-primary fixed-top" id="primary-navbar" role="navigation">
        <div class="container">
            <a class="navbar-brand" href="{:index_url()}" title="{:site_short_name()}" rel="home">{:site_short_name()}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarNavDropdown" class="collapse navbar-collapse">
                <div class="mr-auto">
                    <ul id="main-nav" class="navbar-nav mr-auto">
                        <li class="nav-item {:category_active([],'active')}"><a title="首页" href="{:index_url()}" class="nav-link">首页</a></li>
                        {foreach :navicat() as $value}
                        <li class="nav-item {:category_active($value,'active')}"><a title="{$value.name}" href="{$value.url}" class="nav-link">{$value.name}</a></li>
                        {/foreach}
                    </ul>
                </div>
                <form class="form-inline" id="search_form"   action="{:url('search/index')}"   method="get">
                    <input class="form-control mr-sm-2" type="text" placeholder="搜索..."   aria-label="搜索" name="keyword">
                </form>
            </div>

        </div>
    </nav>
</header><!-- ./header -->
<!--主体内容 开始-->

<main class="site-content">
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="main-content">
                    {block name="content"}
                    <div class="posts">
                        {foreach :post(['order'=>'id desc'],10) as $value}
                        {if empty($value['main_image'])}
                        <div class="card card-shadow">
                            <div class="card-body">
                                <h2 class="card-title h5 text-link-color line-clamp-2 text-overflow-ellipsis">
                                    <a href="{:url('Post/index',['post_id'=>$value['post_id']])}" rel="{$value['title']}">{$value['title']}</a>
                                </h2>
                                <p class="card-text hidden-sm-down">
                                    {$value['description']|msubstr=0,200}
                                </p>
                                <p class="card-text text-link-color-muted">
                                    <small>
                                        <span class="post-time">{$value['created']|strtotime|date='Y年m月d日'}</span>
                                        <span class="post-category"> &nbsp;&bull;&nbsp; <a href="{:url('category/index',['cate'=>$value['category_alias']??''])}" rel="category tag">{$value['category_name']}</a></span>
                                    </small>
                                </p>

                            </div>
                        </div>
                        {else/}
                        <div class="card card-shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <a class="entry-img" href="{:url('Post/index',['post_id'=>$value['post_id']])}" rel="{$value['title']}">
                                            <img width="300" height="169" src="{:getImage($value['main_image'],'_300_169')}" class="img-fluid wp-post-image" alt="{$value['title']}"  sizes="(max-width: 300px) 100vw, 300px" />
                                        </a>
                                    </div>
                                    <div class="col-8">
                                        <h2 class="card-title h5 text-link-color line-clamp-2 text-overflow-ellipsis mb-3">
                                            <a href="{:url('Post/index',['post_id'=>$value['post_id']])}" rel="{$value['title']}">{$value['title']}</a>
                                        </h2>
                                        <p class="card-text mt-3 hidden-sm-down">
                                            {$value['description']|msubstr=0,100}
                                        </p>
                                        <p class="card-text text-link-color-muted">
                                            <small>
                                                <span class="post-time">{$value['created']|strtotime|date='Y年m月d日'}</span>
                                                <span class="post-category"> &nbsp;&bull;&nbsp; <a href="{:url('category/index',['cate'=>$value['category_alias']??''])}" rel="category tag">{$value['category_name']}</a></span>
                                            </small>
                                        </p>
                                    </div>
                                </div><!-- ./row -->
                            </div>
                        </div>
                        {/if}
                        {/foreach}
                    </div>
                    <div class="pagination">
                        <nav aria-label="Page navigation">
                            {:post_page(['order'=>'id desc'],10)}
                        </nav>
                    </div>
                    {/block}
                </div>
            </div>
            <!--侧边栏 开始-->
            <div class="col-lg-4 hidden-md-down">
                <div class="sidebar">
                    <!--热门标签 开始-->
                    <aside class="widget card-shadow d_tag"><h4 class="widget-header h6">热门标签</h4>
                        <div class="hot-tags">
                            {foreach(:post_tag(50) as $value)}
                            <a href="{:url('tag/post',['post_tag'=>$value])}" class="btn btn-light btn-sm">{$value}</a>
                            {/foreach}
                            <a href="{:url('tag/index')}" class="btn btn-light btn-sm">全部标签...</a>
                        </div>
                    </aside>
                    <!--热门标签 结束-->

                    <!--热门文章 开始-->
                    <aside class="widget card-shadow widget_lean_posts">
                        <h4 class="widget-header h6">热门文章</h4>
                        <ul class="list-unstyled">
                            {foreach(:post(['order'=>'click desc'],15) as $value)}
                            <li>
                                <a href="{:url('post/index',['post_id'=>$value['post_id']])}" rel="bookmark" title="{$value['title']}">{$value['title']|msubstr=0,18}</a>
                            </li>
                            {/foreach}
                            <!--./li-->
                        </ul>
                        <!--./ul-->
                    </aside>
                    <!--热门文章 结束-->

                    <!--友情链接 开始-->
                    <aside class="widget card-shadow widget_nav_menu"><h4 class="widget-header h6">友情链接</h4>
                        <div>
                            <ul class="menu">
                                {foreach(:site_flink() as $value)}
                                <li><a href="{$value.furl}" title="{$value.fdesc}">{$value.fname}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    </aside>
                    <!--友情链接 结束-->
                </div>
            </div>
            <!--侧边栏 结束-->
        </div><!--/.row-->
    </div>
</main>
<!--主体内容 结束-->
<!--复用的底部 -->
<footer class="footer mt-3">
    <div class="container">
        <p class="copyright mb-0">
            &copy; 2013-{:date('Y')}
            .<a href="" title="{:site_name()}">{:site_name()}</a>
            .<a href="###" rel="external nofollow" target="_blank">{:site_icp_code()}</a>
            .Style by <a>轻主题</a>
            .POWER BY <a href="" title="metacms">metacms</a>
        </p>
    </div>
</footer>
<script type='text/javascript' src='/static/writing/js/popper.js'></script>
<script type='text/javascript' src='/static/writing/js/tether.js'></script>

</body>
</html>
