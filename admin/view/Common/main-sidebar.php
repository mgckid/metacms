<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= strtolower(CONTROLLER_NAME)=='index'?' active menu-open':'' ?>">
                <a href="<?=U('admin')?>">
                    <i class="fa fa-home"></i> <span>后台首页</span>
                </a>
            </li>
            <?php foreach ($menu as $v){?>
                <li class="treeview  <?= strtolower(CONTROLLER_NAME)==strtolower($v['controller'])?' active menu-open':'' ?> ">
                    <a href="javascript:void(0);">
                        <i class="fa fa-laptop"></i>
                        <span><?= $v['access_name'] ?></span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php foreach ($v['sub'] as $val) {?>
                            <li class="<?= strtolower(CONTROLLER_NAME.ACTION_NAME)==strtolower($val['controller'].$val['action'])?'active':'' ?>"><a href="<?= $val['url'] ?>"><i class="fa fa-circle-o"></i><?= $val['access_name'] ?></a></li>
                        <?php }?>
                    </ul>
                </li>
            <?php }?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>