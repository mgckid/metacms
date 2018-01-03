<?php $this->layout('Layout/admin'); ?>
<style type="text/css">
    .content-box .form-group dl i {
        font-style: normal;
        margin-right: 10px;
    }

    .content-box .form-group dl dt span {
        font-size: 14px;
    }
</style>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12">
            <form class="form-horizontal" name='add_role_access' action="<?= U('admin/Rbac/addRoleAccess') ?>" method="post">
                <div class="form-group">
                    <label class="col-sm-1 control-label">选择角色</label>
                    <div class="col-sm-5">
                        <select name="role_id" class="form-control">
                            <option value="">选择角色</option>
                            <?php foreach ($role as $v) { ?>
                                <option  value="<?= $v['role_id'] ?>" <?= $role_id == $v['role_id'] ? 'selected="selected"' : '' ?>><?= $v['role_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">选择权限</label>
                    <div class="col-sm-11">
                        <?php foreach ($access as $v) { ?>
                            <dl>
                                <dt>
                                    <input type="checkbox" name='access_sn[]' value="<?= $v['access_sn'] ?>"/>
                                    <span><?= $v['access_name'] ?></span>
                                </dt>
                                <dd>
                                    <?php foreach ($v['sub'] as $val) { ?>
                                        <i>
                                            <input type="checkbox" name='access_sn[]' value="<?= $val['access_sn'] ?>"  />　 <?= $val['access_name'] ?>
                                        </i>
                                    <?php } ?>
                                </dd>
                            </dl>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label">操作</label>

                    <div class="col-sm-5">
                        <button type="submit" data-power="admin/Rbac/addRoleAccess" class="btn btn-success ">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var roleId = $('select[name=role_id]').val();
        if (roleId) {
            $('select[name=role_id]').change();
        }
    })
    //提交表单
    $('form[name=add_role_access]').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器连接失败');
        },
        success: function (data) {
            layer.alert(data.msg)
        }
    });

    //权限选择后将父类权限选中
    $('dd input[type=checkbox]').on('click', function () {
        var topChecked = false;
        $(this).parents('dd').find('input').each(function () {
            if (this.checked == true) {
                topChecked = true;
            }
        })
        $(this).parents('dl').find('dt input').prop('checked', topChecked);
    })

    //父类权限选中和反选的时候对所属子权限全选和反选
    $('dl dt input').on('click', function () {
        if ($(this).prop('checked')) {
            $(this).parents('dl').find('dd input').prop('checked', true);
        } else {
            $(this).parents('dl').find('dd input').prop('checked', false);
        }
    })

    //切换角色时去获取角色权限
    $('form[name=add_role_access]').find('select[name=role_id]').on('change', function () {
        var roleId = $(this).val();
        $.post('<?=U('admin/Rbac/ajaxGetRoleAccess') ?>', {role_id: roleId}, function (data) {
            $('form[name=add_role_access]').find('input[type=checkbox]').each(function () {
                if ($.inArray($(this).val(), data.data) >= 0) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            })
        }, 'json');
    })

</script>