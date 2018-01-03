<?php $this->layout('Layout/admin'); ?>
<!--/breadcrumb-->
<div class="panel panel-default">


    <div class="panel-body">
        <div class="operate_box">
            <a class="btn btn-sm btn-success" data-power="admin/Rbac/addRole"
               href="<?= U('admin/Rbac/addRole') ?>">添加角色</a>
        </div>
        <!--/operate_box-->
        <table class="table">
            <thead>
            <tr>
                <?php foreach ($list_init as $key => $value): ?>
                    <th><?= $value['field_name'] ?></th>
                <?php endforeach; ?>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $v) { ?>
                <tr>
                    <?php foreach ($list_init as $key => $value): ?>
                        <td><?= $v[$key] ?></td>
                    <?php endforeach; ?>
                    <td>
                        <button id="<?= $v['id'] ?>" data-power="admin/Rbac/delRole" name="delRole"  class="btn btn-xs btn-danger">删除  </button>
                        <a href="<?= U('admin/Rbac/editRole', array('id' => $v['id'])) ?>"  data-power="admin/Rbac/editRole" class="btn btn-xs btn-">修改角色</a>
                        <a href="<?= U('admin/Rbac/addRoleAccess', array('id' => $v['id'])) ?>"  data-power="admin/Rbac/addRoleAccess" class="btn btn-xs btn-success">分配权限</a>
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


<!--删除记录 开始-->
<form name="delRoleForm" action="<?= U('admin/Rbac/delRole') ?>" method="post">
    <input type="hidden" value="" name="id"/>
</form>
<script>
    $('[name=delRole]').on('click', function () {
        var id = $(this).attr('id');
        layer.confirm('确定要删除么?', function (index) {
            doDel(id);
            layer.close(index);
        });
        function doDel(id) {
            $('[name=delRoleForm] [name=role_id]').val(id);
            $('form[name="delRoleForm"]').ajaxSubmit({
                dataType: 'json',
                success: function (data) {
                    layer.alert(data.msg)
                    if (data.status == 1) {
                        window.location.reload();
                    }
                },
                error: function () {
                    layer.alert('服务器访问出错');
                }
            });
        }
    });
</script>
<!--删除记录 结束-->
