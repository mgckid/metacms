<?php $this->layout('Layout/index') ?>
<main class="site-content">

    <div class="container mt-4">
        <div class="site-main">
            <div class="row">
                <div class="col-lg-8">
                    <main class="main-content">
                        <div class="card">
                            <h1 class="card-header bg-white py-4"><?= $category_info['category_name'] ?></h1>

                            <div class="card-body">
                                <div class="entry-content">
                                    <?= htmlspecialchars_decode($category_info['content']) ?>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <!--侧边栏 开始-->
                <?= $this->insert('Common/sidebar') ?>
                <!--侧边栏 结束-->
            </div>
        </div>
    </div>
    <!--./container -->

</main>
