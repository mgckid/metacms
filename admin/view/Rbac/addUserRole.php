<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12">
            <form class="form-horizontal" name='add_user_role' action="<?= U('admin/Rbac/addUserRole') ?>"
                  method="post">
                <div class="form-group">
                    <label class="col-sm-1 control-label">选择用户</label>

                    <div class="col-sm-5">
                        <select name="user" class="form-control" id="user_list">
                            <option value="">请选择用户</option>
                            <?php foreach ($user as $v) { ?>
                                <option  value="<?= $v['username'] ?>" <?= $_GET['id'] == $v['id'] ? 'selected="selected"' : '' ?>><?= $v['true_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">选择角色</label>

                    <div class="col-sm-5">
                        <?php foreach ($role as $v) { ?>
                            <label class="checkbox-inline">
                                <input type="checkbox" name='role[]' value="<?= $v['role_id'] ?>">
                                <?= $v['role_name'] ?>
                            </label>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">操作</label>

                    <div class="col-sm-5">
                        <button type="submit" class="btn btn-success " data-power="admin/Rbac/addUserRole">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('#user_list').change();
    })
    $('form[name=add_user_role]').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器连接失败');
        },
        success: function (data) {
            layer.alert(data.msg)
        }
    });


    //切换用户获取角色
    $('#user_list').on('change', function () {
        var username = $(this).val();
        $.post('<?=U("admin/Rbac/ajaxGetUserRole")?>', {'username': username}, function (data) {
            if (data.status != 1) {
                layer.msg(data.msg);
            }
            $('form[name=add_user_role]').find('input[type=checkbox]').each(function () {
                if ($.inArray($(this).val(), data.data) >= 0) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            })

        }, 'json');
    })
</script>