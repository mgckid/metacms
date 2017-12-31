<header class="header">
    <nav class="navbar navbar-expand-lg navbar-shadow navbar-dark bg-primary fixed-top" id="primary-navbar" role="navigation">
        <div class="container">
            <a class="navbar-brand" href="/" title="<?=$siteInfo['site_short_name']?>" rel="home"><?=$siteInfo['site_short_name']?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarNavDropdown" class="collapse navbar-collapse">
                <div class="mr-auto">
                    <ul id="main-nav" class="navbar-nav mr-auto">
                        <li class="nav-item active"><a title="首页" href="/" class="nav-link">首页</a></li>
                        <?php foreach($siteNavgation as $value):?>
                        <li class="nav-item"><a title="<?=$value['category_name']?>" href="<?=U('Home/Index/category',['cate'=>$value['category_alias']])?>" class="nav-link"><?=$value['category_name']?></a></li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <form class="form-inline" id="search_form"   action="<?=U('Home/Index/search')?>"   method="get">
                    <input type="hidden" name="c" value="index"/>
                    <input type="hidden" name="a" value="search"/>
                    <input class="form-control mr-sm-2" type="text" placeholder="搜索..." aria-label="搜索" name="s">
                </form>
            </div>

        </div>
    </nav>
</header><!-- ./header -->