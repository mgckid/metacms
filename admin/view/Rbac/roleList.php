<?php $this->layout('Layout/admin'); ?>
<!--/breadcrumb-->
<div class="panel panel-default">


    <div class="panel-body">
        <div class="operate_box">
            <a class="btn btn-sm btn-success" data-power="admin/Rbac/addRole" href="<?= U('admin/Rbac/addRole') ?>">添加角色</a>
        </div>
        <!--/operate_box-->
        <table class="table">
            <thead>
            <tr>
                <?php foreach ($list_init as $key=> $value):?>
                    <th><?=$value['field_name']?></th>
                <?php endforeach;?>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $v) { ?>
                <tr>
                    <?php foreach ($list_init as $key => $value): ?>
                        <td><?=$v[$key]?></td>
                    <?php endforeach;?>
                    <td>
                        <a href="<?=U('admin/Rbac/delRole') ?>" data-power="admin/Rbac/delRole"  class="btn btn-xs btn-danger">删除</a>
                        <a  href="<?= U('admin/Rbac/addRoleAccess', array('id' => $v['role_id'])) ?>" data-power="admin/Rbac/addRoleAccess" class="btn btn-xs btn-success">分配权限</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <!--/列表-->
        <?= htmlspecialchars_decode($pages) ?>
        <!--/分页-->
    </div>

</div>
