<header class="main-header">
    <!-- Logo -->
    <a href="<?=U('Admin/Index/index')?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><i class="fa fa-fw fa-tachometer"></i></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><?=$siteInfo['site_short_name']?>网站管理</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li><a  href="<?=C('HOME_URL')?>" class="btn btn-link btn-small" target="_blank"><i class="fa fa-chrome"></i>  预览首页</a></li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/static/admin/adminlte/dist/img/avatar.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?=$loginInfo['true_name']?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/static/admin/adminlte/dist/img/avatar.png" class="img-circle" alt="User Image">

                            <p>
                                <?=$loginInfo['true_name']?>
                              <!--  - Web Developer<small>Member since Nov. 2012</small>-->
                            </p>
                        </li>
                        <!-- Menu Body -->
          <!--              <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>
                        </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=U('admin/Rbac/resetPassword',array('id'=>$loginInfo['id']))?>" data-power="admin/Rbac/resetPassword" style="display: none" class="btn btn-default btn-flat">修改密码</a>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-default btn-flat"  onclick="logout()">注销</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>