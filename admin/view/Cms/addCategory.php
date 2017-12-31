<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-10">
            <?=\metacms\web\Form::getInstance()->create()?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button data-power="admin/Cms/addCategory" class="btn btn-success" type="submit">提交</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--/panel-->
<script>
    $(function () {
        $('form[name=autoform]').ajaxForm({
            dataType: 'json',
            error: function () {
                layer.msg('服务器连接失败');
            },
            success: function (data) {
                layer.alert(data.msg)
                if (data.status == 1) {
                    $('form[name=autoform]').find('input:reset').click();
                    setTimeout(function () {
                        window.history.go(-1)
                    },2000);
                }
            }
        });
    })

    $(function () {
        //栏目分类
        var param = {
            model_name: 'BaseLogic',
            method_name: 'getCategoryData'
        };
        $.post('<?=U('admin/pop/index')?>', param, function (data) {
            if(data.status==1){
                var option='';
                var selected_id = $('#pid').data('selected');
                $.each(data.data,function(i,n){
                    var selected = selected_id== n.id?'selected = "selected"':'';
                    option = option + '<option value="' + n.id + '" ' + selected + '>'+n.category_name+'</option>'
                })
                $('#pid').append(option);
            }
        }, 'json')

        //模型分类
        var param = {
            model_name: 'BaseLogic',
            method_name: 'getModelData'
        };
        $.post('<?=U('admin/pop/index')?>', param, function (data) {
            if(data.status==1){
                var option='';
                var selected_id = $('#model_id').data('selected');
                $.each(data.data,function(i,n){
                    var selected = selected_id== n.id?'selected = "selected"':'';
                    option = option + '<option value="' + n.id + '" ' + selected + '>'+n.name+'</option>'
                })
                $('#model_id').append(option);
            }
        }, 'json')
    })
</script>
