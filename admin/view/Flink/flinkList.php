<?php $this->layout('Layout/admin'); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box">
            <a class="btn btn-sm btn-success" data-power="admin/Flink/addFlink" href="<?= U('admin/Flink/addFlink') ?>">添加友情链接</a>
        </div>
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
            <?php foreach ($list as  $value) {?>
                <tr>
                    <?php foreach ($list_init as $key => $val): ?>
                        <td><?= isset($val['enum'][$value[$key]]) ? $val['enum'][$value[$key]] : $value[$key] ?></td>
                    <?php endforeach;?>
                        <td>
                            <button name="delFlink" id="<?= $value['id'] ?>" data-power="admin/Flink/delFlink" class="btn btn-xs btn-danger">删除</button>
                            <a href="<?=U('admin/Flink/addFlink', array('id' => $value['id'])) ?>" data-power="admin/Flink/addFlink" class="btn btn-xs btn-success">编辑</a>
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
<form name="delFlinkForm" action="<?=U('admin/Flink/delFlink') ?>" method="post">
    <input type="hidden" value="" name="id" />
</form>
<script>
    $('[name=delFlink]').on('click', function () {
        var id = $(this).attr('id');
        layer.confirm('确定要删除么?', function (index) {
            doDel(id);
            layer.close(index);
        });
        function doDel(id) {
            $('[name=delFlinkForm] [name=id]').val(id);
            $('form[name="delFlinkForm"]').ajaxSubmit({
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