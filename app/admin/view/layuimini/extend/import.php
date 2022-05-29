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
<div class="layui-card">
    <div class="layui-card-body">
        <button type="button" class="layui-btn" id="importDemo">
            <i class="layui-icon">&#xe67c;</i>导入表格
        </button>
        <div style="margin-top: 10px"><a href="/admin/{:\\think\\facade\\Request::controller()}/template">下载模板</a></div>
    </div>
</div>
<script src="/static/admin/layuimini/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="/static/admin/layuiconfig.js" charset="utf-8"></script>
<script>
    layui.use(['upload', 'layer'], function () {
        var upload = layui.upload,
            $ = layui.jquery,
            layer = layui.layer;
        //执行实例
        var uploadInst = upload.render({
            elem: '#importDemo', //绑定元素
            url: '/admin/{:\\think\\facade\\Request::controller()}/import', //上传接口
            exts: 'xls|xlsx',
            done: function (res) {
                //上传完毕回调
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
            },
            error: function (index, upload) {
                //请求异常回调
                layer.closeAll('loading'); //关闭loading
                layer.alert('接口出错')
            }
        });
    });
</script>
</body>
</html>