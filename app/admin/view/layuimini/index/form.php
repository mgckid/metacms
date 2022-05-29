<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/admin/layuimini/lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/layuimini/css/public.css" media="all">
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">

        <blockquote class="layui-elem-quote layui-text">
            鉴于小伙伴的普遍反馈，先温馨提醒两个常见“问题”：1. <a href="/doc/base/faq.html#form"
                                           target="_blank">为什么select/checkbox/radio没显示？</a> 2. <a
                    href="/doc/modules/form.html#render" target="_blank">动态添加的表单元素如何更新？</a>
        </blockquote>

        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>表单集合演示</legend>
        </fieldset>
        <?php
        $res = \think\facade\Db::query('show table status');
        $all_table_name = array_column($res, 'Name', 'Name');
        ?>
        <div class="layui-form layuimini-form">
            <?= Form::getInstance()
                ->select('选择连接', '', 'connection', ['db' => 'db'])
                ->select('选择表', '', 'table', $all_table_name)
                ->form_data(\think\facade\Request::param())
                ->radio('操作', '', 'operation', ['1' => '生成类', 2 => '生成迁移脚本'], 2)
                ->radio('是否生成文件', '', 'generate', [0 => '否', 1 => '是'], 0)
                /*->input_inline_start()
                ->input_text('验证手机', 11, 'phone', '55')
                ->input_text('验证邮箱', 11, 'email', '55')
                ->input_inline_end()
                ->editor('编辑器', 111, 'editor', 'dsksjhdskjdskjdhskjhd')
                ->textarea('文本', 111, 'desc', 'abcded')
                ->select('下拉', '111', 'status', [1 => '男', 2 => '女'], 1)
                ->radio('单选', '111', 'sex', [1 => '男', 2 => '女'], 1)
                ->switch('开关', '111', 'switch', 0)
                ->checkbox('多选', 111, 'type', [1 => 'a', 2 => 'b', 3 => 'c'], 1)
                ->empty_box('上传', 111, 'file')
                ->input_password('密码', 11, 'password', '1111')
                ->input_date('日期', 11, 'date', '2021-12-30')
                ->input_hidden('id', 222)
                ->input_text('长输入框', 11, 'long', 222)
                ->input_text('短输入框', 11, 'short', 222)
                ->form_class(LayuiForm::form_class_pane)*/
                ->form_method(Form::form_method_get)
                ->input_submit('确认保存', 'class="layui-btn"')
                ->create(Form::layui_form, [LayuiForm::form_class_pane])
            ?>
        </div>
    </div>
</div>
<?php
$table_name = input('get.table', '');
$connection_name = input('get.connection', '');
$operation = input('get.operation', '');
$generate = input('get.generate', '');
if ($table_name and $connection_name and $operation == 1) {
    \ModelGenerate::getInstance()->generate_path = 'D:\www\gitee\mystudy\php_study\tp6\app\appdal';
    \ModelGenerate::getInstance()->connect_name = $connection_name;
    $res = \ModelGenerate::getInstance()->getCmsModel($table_name, $generate);
    $res2 = \ModelGenerate::getInstance()->getCmsController($table_name, $generate);
    $res3 = \ModelGenerate::getInstance()->getAdminController($table_name, $generate);
    echo "<pre>" . PHP_EOL;
    print_r($res);
    echo "<pre>" . PHP_EOL;
    print_r($res2);
    echo "<pre>" . PHP_EOL;
    print_r($res3);
} elseif ($connection_name and $operation == 2) {
    echo "<pre>" . PHP_EOL;
   // ModelGenerate::getInstance()->getMigrate($all_table_name,'sqlite');
    ModelGenerate::getInstance()->back_db_data_to_file($all_table_name, app_path() . '..\..\database', 'metadmin_sqlite.sql');
}
?>
<script src="/static/admin/layuimini/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="/static/admin/layuiconfig.js" charset="utf-8"></script>
<script>
    layui.use(['form'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.$;

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            /*var index = layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            }, function () {

                // 关闭弹出层
                layer.close(index);

                var iframeIndex = parent.layer.getFrameIndex(window.name);
                parent.layer.close(iframeIndex);

            });*/
            $.post(
                data.form.action,
                data.field,
                function (res) {
                    if (res.status != 1) {
                        layer.alert(res.msg || '接口出错')
                    } else {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            //do something
                            parent.window.location.reload();
                        });
                    }
                }
            )
            return false;
        });

    });
</script>
</body>
</html>