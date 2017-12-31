<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box">
            <a class="btn btn-sm btn-success" href="<?= U('admin/Rbac/addAccess') ?>"  data-power="admin/Rbac/addAccess">添加权限</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>id</th>
                <th>权限名称</th>
                <th>模块名称</th>
                <th>控制器名称</th>
                <th>方法名称</th>
                <th>权限类型</th>
                <th>排序</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $v) { ?>
                <tr>
                    <td><?= $v['id'] ?></td>
                    <td><?= $v['access_name'] ?></td>
                    <td><?= $v['module'] ?></td>
                    <td><?= $v['controller'] ?></td>
                    <td><?= $v['action'] ?></td>
                    <td><?= $v['level_text'] ?></td>
                    <td><?= $v['sort'] ?></td>
                    <td><?= $v['created'] ?></td>
                    <td><?= $v['modified'] ?></td>
                    <td>
                        <a href="<?=U('admin/Rbac/addAccess', array('id' => $v['id'])) ?>"  data-power="admin/Rbac/addAccess" class="btn btn-xs btn-success">编辑</a>
                        <button class="btn btn-xs btn-danger" data-power="admin/Rbac/delAccess" name="delAccess"  access_id="<?= $v['id'] ?>">删除 </button>
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

<form name="delAccessForm" action="<?= U('admin/Rbac/delAccess') ?>" method="post">
    <input type="hidden" name="access_id"/>
</form>
<script>
    $('[name=delAccess]').on('click', function () {
        var accessId = $(this).attr('access_id');
        $('[name=delAccessForm] [name=access_id]').val(accessId)
        $('form[name="delAccessForm"]').ajaxSubmit({
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

    })
</script>
