<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <a class="btn btn-success btn-sm" data-power="admin/Advertisement/addPosition"
           href="<?= U('admin/Advertisement/addPosition') ?>">添加广告位</a>
    </div>
    <div class="panel-body">
        <!--table-->
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
                <?php foreach ($list_data as $value): ?>
                    <tr id="row<?= $value['id'] ?>">
                        <?php foreach ($list_init as $key => $val): ?>
                            <td><?= !empty($val['enum']) ? $val['enum'][$value[$key]] : $value[$key] ?></td>
                        <?php endforeach;?>
                        <td>
                            <a class="btn btn-success btn-xs" data-power="admin/Advertisement/editPosition"  href="<?=U('admin/Advertisement/editPosition', array('id' => $value['id'])) ?>">编辑</a>
                            <a class="btn btn-danger btn-xs" data-power="admin/Advertisement/delPosition"   href="javascript:void(0)" onclick="delPosition(<?=$value['id']?>)">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!--/列表-->
        <?= htmlspecialchars_decode($pages) ?>
        <!--/分页-->
    </div>
</div>
<script>
    //删除广告位
    function delPosition(id) {
        if ('number' != typeof (id)) {
            //id = [id];
            return false;
        }
        layer.confirm('您确定要删除选中的广告位么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.post('<?= U("admin/Advertisement/delPosition") ?>', {id: id}, function (data) {
                layer.alert(data.msg)
                if (data.status == 1) {
                    $('#position' + id).remove();
                }
            }, 'json');
        }, function () {
            return
        });
    }
</script>
