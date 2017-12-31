<?php $this->layout('Layout/admin') ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="operate_box mb10">
                </div>
                <table class="table">
                    <tr>
                        <?php foreach ($list_init as $key=> $value):?>
                            <th><?=$value['field_name']?></th>
                        <?php endforeach;?>
                        <th width="10%">操作</th>
                    </tr>
                    <?php foreach ($list as  $value) {?>
                        <tr id="row<?= $value['id'] ?>">
                            <?php foreach ($list_init as $key => $val): ?>
                                <td><?= !empty($val['enum']) ? $val['enum'][$value[$key]] : $value[$key] ?></td>
                            <?php endforeach;?>
                            <td>
                                <a class="btn btn-success btn-xs"  href="<?= U('admin/Cms/editPost', array('id' => $value['id'])) ?>" data-power="admin/Cms/editPost">编辑</a>
                                <a class="btn btn-danger ml10 btn-xs" href="javascript:void(0)" onclick="delArticle(<?= $value['id'] ?>)" data-power="admin/Cms/delArticle">删除</a>
                            </td>
                        </tr>
                    <?php } ?>

                </table>
                <!--/列表-->
                <?= htmlspecialchars_decode($pages) ?>
                <!--/分页-->
            </div>
        </div>
<script>
    //删除文章
    function delArticle(id) {
        if ('number' == typeof (id)) {
            id = [id];
        }
        layer.confirm('您确定要删除选中的文章么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.post('<?= U("admin/Cms/delArticle") ?>', {id: id}, function (data) {
                layer.alert(data.msg)
                if (data.status == 1) {
                    for (var i = 0; i < id.length; i++) {
                        $('#article' + id[i]).remove();
                    }
                }
            }, 'json');
        }, function () {
            return
        });
    }
    /**
     * 批量删除文章
     * @returns {undefined}
     */
    function delArticles() {
        var id = []
        $('table input:checkbox').each(function () {
            var isChecked = $(this).is(function () {
                return $(this).prop('checked');
            });
            if (isChecked) {
                id.push($(this).val());
            }
        });
        if (id.length != 0) {
            delArticle(id);
        } else {
            layer.alert('请选择要删除的文章')
        }
    }




</script>