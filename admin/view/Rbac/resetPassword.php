<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12">
            <form action="<?= U('admin/Rbac/resetPassword') ?>" name="resetPassword" class="form form-horizontal" method="post">
                <input type="hidden" name="id" value="<?= $info['id'] ?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">用户</label>

                    <div class="col-sm-10">
                        <span  style="line-height: 20px;margin-top: 10px;"><?=$info['username']?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">密码</label>

                    <div class="col-sm-10">
                        <input type="text" name="password" class="form-control" placeholder="点击输入密码">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">重复密码</label>

                    <div class="col-sm-10">
                        <input type="text" name="repassword" class="form-control" placeholder="点击输入密码">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success " data-power="admin/Rbac/resetPassword">保存</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('form[name=resetPassword]').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器无法连接');
        },
        success: function (data) {
            layer.alert(data.msg);
            if(data.status==1){
                setTimeout(function () {
                    window.history.go(-1)
                }, 2000);
            }
        }
    });
</script>