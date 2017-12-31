<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading"></div>
    <div class="panel-body">
        <?=\metacms\web\Form::getInstance()->create()?>
            <div class="form-group">
                <label class="col-sm-2 control-label">操作</label>
                <div class="col-sm-10">
                    <button type="submit" data-power="admin/Advertisement/addPosition" class="btn btn-success">添加</button>
                    <button type="reset" class="btn btn-danger ml10">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#autoform').ajaxForm({
        dataType:'json',
        error: function () {
            layer.msg('服务器无法连接')
        },
        success: function (data) {
            layer.alert(data.msg)
        }
    })
</script>