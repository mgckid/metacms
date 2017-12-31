<?php $this->layout('Layout/index')?>
<main class="site-content">
    <!-- 上面是复用的头部 -->

    <!-- <div class="header-bg">

    </div> -->

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="main-content">

                    <header class="jumbotron bg-white border card-shadow mb-3 py-4 pl-3">
                        <h1><?=$category_info['category_name']?></h1>
                   <!--     <div class="text-muted">
                            <p></p>
                        </div>-->
                    </header>

                    <div class="posts">
                        <?php foreach($list_data as $value):?>
                            <?php if(empty($value['main_image'])):?>
                                <div class="card card-shadow">
                                    <div class="card-body">
                                        <h2 class="card-title h5 text-link-color line-clamp-2 text-overflow-ellipsis">
                                            <a href="<?=U('Home/Post/detail',['id'=>$value['post_id']])?>" rel="<?=$value['title']?>"><?=$value['title']?></a>
                                        </h2>
                                        <p class="card-text hidden-sm-down">
                                            <?=msubstr($value['description'],0,100)?>
                                        </p>
                                        <p class="card-text text-link-color-muted">
                                            <small>
                                                <span class="post-time"><?=date('Y年m月d日',strtotime($value['created']))?></span>
                                                <span class="post-category"> &nbsp;&bull;&nbsp; <a href="<?=U('Home/Index/category',['cate'=>$value['category_alias']])?>" rel="category tag"><?=$value['category_name']?></a></span>
                                            </small>
                                        </p>

                                    </div>
                                </div>
                            <?php else:?>
                                <div class="card card-shadow">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-4">
                                                <a class="entry-img" href="<?=U('Home/Post/detail',['id'=>$value['post_id']])?>" rel="<?=$value['title']?>">
                                                    <img width="300" height="169" src="<?=getImage($value['main_image'])?>" class="img-fluid wp-post-image" alt="<?=$value['title']?>"  sizes="(max-width: 300px) 100vw, 300px" />
                                                </a>
                                            </div>

                                            <div class="col-8">
                                                <h2 class="card-title h5 text-link-color line-clamp-2 text-overflow-ellipsis mb-3">
                                                    <a href="<?=U('Home/Post/detail',['id'=>$value['post_id']])?>" rel="<?=$value['title']?>"><?=$value['title']?></a>
                                                </h2>
                                                <p class="card-text mt-3 hidden-sm-down">
                                                    <?=msubstr($value['description'],0,100)?>
                                                </p>
                                                <p class="card-text text-link-color-muted">
                                                    <small>
                                                        <span class="post-time"><?=date('Y年m月d日',strtotime($value['created']))?></span>
                                                        <span class="post-category"> &nbsp;&bull;&nbsp; <a href="<?=U('Home/Index/category',['cate'=>$value['category_alias']])?>" rel="category tag"><?=$value['category_name']?></a></span>
                                                    </small>
                                                </p>
                                            </div>
                                        </div><!-- ./row -->
                                    </div>
                                </div>
                            <?php endif;?>

                        <?php endforeach;?>
                    </div>

                    <div class="pagination">
                        <nav aria-label="Page navigation">
                            <?=htmlspecialchars_decode($pages)?>
                        </nav>
                    </div>

                </div>
            </div>

            <!--侧边栏 开始-->
            <?=$this->insert('Common/sidebar')?>
            <!--侧边栏 结束-->

        </div>
    </div>

</main>