<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box mb10">
            <a class="btn btn-success btn-xs" data-power="admin/Data/addDictionary" href="<?= U('admin/Data/addDictionary') ?>">添加字典</a>
        </div>
        <table class="table ">
            <tr>
                <?php foreach ($list_init as $key=> $value):?>
                    <th><?=$value['field_name']?></th>
                <?php endforeach;?>
                <th>操作</th>
            </tr>
            <?php foreach ($list as  $value):?>
                <tr id="row<?= $value['id'] ?>">
                    <?php foreach ($list_init as $key => $val): ?>
                        <td><?= !empty($val['enum']) ? $val['enum'][$value[$key]] : $value[$key] ?></td>
                    <?php endforeach;?>
                    <td>
                        <a class="btn btn-success btn-xs"  href="<?=U('admin/Data/editDictionary', array('id' => $value['id'])) ?>" data-power="admin/Data/editDictionary">编辑</a>
                        <a class="btn btn-danger ml10 btn-xs" href="javascript:void(0)" onclick="delRecord(<?= $value['id'] ?>)" data-power="admin/Data/delDictionary">删除</a>
                        <a class="btn btn-info btn-xs"  href="<?= U('admin/Data/fieldManage', array('dictionary_id' => $value['id'])) ?>" data-power="admin/Data/fieldManage">字段管理</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <!--/列表-->
    </div>
</div>
<script>
    //删除记录
    function delRecord(id) {
        layer.confirm('您确定要删除选中的记录么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
       $.post('<?= U('admin/Data/delDictionary') ?>', {id: id}, function (data) {
                layer.msg(data.msg);
                if (data.status == 1) {
                    $("#row" + id).remove();
                }
            }, 'json');
        }, function () {
            return
        });
    }
</script>