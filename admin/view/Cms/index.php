<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box mb10">
            <a class="btn btn-success btn-sm" data-power="admin/Cms/addCategory" href="<?= U('admin/Cms/addCategory') ?>">添加栏目</a>
        </div>
        <table class="table">
            <tr>
                <?php foreach ($list_init as $key=> $value):?>
                    <th><?=$value['field_name']?></th>
                <?php endforeach;?>
                <th>操作</th>
            </tr>
            <?php foreach ($list as  $value) {?>
                <tr id="row<?= $value['id'] ?>">
                    <?php foreach ($list_init as $key => $val): ?>
                        <td><?= !empty($val['enum']) ? $val['enum'][$value[$key]] : $value[$key] ?></td>
                    <?php endforeach;?>
                    <td>
                        <a class="btn btn-primary btn-xs" data-power="admin/Cms/postList" href="<?=U('admin/Cms/postList', array('category_id' => $value['id'])) ?>">查看内容</a>
                        <a class="btn btn-success btn-xs" data-power="admin/Cms/addPost" href="<?= U('admin/Cms/addPost', array('category_id' => $value['id'])) ?>">添加文档</a>
                        <a class="btn btn-success btn-xs" data-power="admin/Cms/editCategory" href="<?= U('admin/Cms/editCategory', array('id' => $value['id'])) ?>">修改栏目</a>
                        <a class="btn btn-danger btn-xs" data-power="admin/Cms/delCategory" href="javascript:void(0)" onclick="deleteColumn(<?= $value['id'] ?>)">删除</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<!--/panel-->


<script>
    //删除栏目
    function deleteColumn(id) {
        layer.confirm('您确定要删除选中的栏目么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
        $.post('<?= U('admin/Cms/delCategory') ?>', {id: id}, function (data) {
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

