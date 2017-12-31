<?php $this->layout('Layout/admin'); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-12">
                <form class="form form-horizontal" name="addAccess" action="<?= U('admin/Rbac/addAccess') ?>"
                      method="post">
                    <input type="hidden" name="id" value="<?= $accessInfo['id'] ?>">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属菜单</label>

                        <div class="col-sm-10">
                            <select name="path" class="form-control">
                                <option value="">请选择栏目</option>
                                <?php foreach ($list as $v) { ?>
                                    <option value="<?= $v['id'] ?>"  <?= ($v['id'] == $accessInfo['id']) ? 'selected="selected"' : '' ?>> <?= $v['placeHolder'].$v['access_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">菜单名称</label>

                        <div class="col-sm-10">
                            <input type="text" name="access_name" value="<?= $accessInfo['access_name'] ?>"
                                   class="form-control" placeholder="点击输入菜单名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">菜单url</label>

                        <div class="col-sm-10">
                            <input type="text" name="url" value="<?= $accessInfo['url'] ?>" class="form-control"
                                   placeholder="点击输入菜单url">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">排列顺序</label>

                        <div class="col-sm-10">
                            <input type="text" name="sort" class="form-control" value="<?= $accessInfo['sort'] ?>"
                                   placeholder="点击输入排列顺序">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">权限类型</label>

                        <div class="col-sm-10">
                            <input type="radio" name="access_type" value="10" <?=($accessInfo['access_type'] == 10)?'checked="checked"':'' ?>> 菜单
                            <input type="radio" name="access_type" value="20" <?=($accessInfo['access_type'] == 20)?'checked="checked"':'' ?>> 按钮
                            <input type="radio" name="access_type" value="30" <?=($accessInfo['access_type'] == 30)?'checked="checked"':'' ?>> 其他
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">操作</label>

                        <div class="col-sm-10">
                            <button class="btn btn-success " data-power="admin/Rbac/addAccess">保存</button>
                            <button type="reset" class="btn btn-danger ml10">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/panel-->

<script type="text/javascript">
    $('form[name=addAccess]').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器连接失败');
        },
        success: function (data) {
            if (data.status == 1) {
                $('form').find('input:reset').click();
            }
            layer.alert(data.msg)
        }
    });
</script>