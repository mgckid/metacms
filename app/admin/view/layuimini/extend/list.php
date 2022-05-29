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
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <fieldset class="table-search-fieldset">
            <legend>搜索信息</legend>
            <div style="margin: 10px 0px 10px 0px">
                <?= Form::getInstance()->form_method(Form::form_method_get)->create() ?>
            </div>
        </fieldset>
        <div class="layui-hide" id="barDemo">
            <div class="layui-btn-container">
                {block name="bar"}
                <button class="layui-btn layui-btn-sm" lay-event="add"  data-access="admin/<?=\think\facade\Request::controller()?>/add"> 新增</button>
                <button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="edit"  data-access="admin/<?=\think\facade\Request::controller()?>/edit"> 编辑</button>
                <button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="batch"  data-access="admin/<?=\think\facade\Request::controller()?>/batch">批量修改</button>
                <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete"  data-access="admin/<?=\think\facade\Request::controller()?>/delete">删除</button>
                <button class="layui-btn layui-btn-sm" lay-event="import"  data-access="admin/<?=\think\facade\Request::controller()?>/import">导入</button>
                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="export"  data-access="admin/<?=\think\facade\Request::controller()?>/export">导出</button>
                {/block}
            </div>
        </div>
        {block name="table"}
        <table class="layui-hide" id="tableDemo" lay-filter="tableFilter"></table>
        {/block}
        <div  class="layui-hide"  id="lineDemo">
            {block name="line"}
            <!--<a class="layui-btn layui-btn-xs data-count-edit" lay-event="edit"  data-access="admin/<?=\think\facade\Request::controller()?>/edit">编辑</a>
            <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete"  data-access="admin/<?=\think\facade\Request::controller()?>/delete">删除</a>-->
            {/block}
        </div>
    </div>
</div>
<script src="/static/admin/layuimini/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="/static/admin/layuiconfig.js" charset="utf-8"></script>
{block name="table_js"}
<script>
    layui.use(['table', 'form', 'layer'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table,
            layuimini = layui.layuimini,
            layer = layui.layer;
        var cols = [];
        var list_schema = eval('{$list_init|json_encode=JSON_UNESCAPED_UNICODE|raw}');
        $.each(list_schema, function (i, n) {
            if(!n.hide) {
                if ($.type(n.enum) == 'object' || $.type(n.enum) == 'array') {
                    n.templet = function (d) {
                        var enums = n.enum;
                        var field = n.field;
                        var strs = enums[d[field]];
                        strs = strs != undefined ? strs : d[field];
                        return strs;
                    };
                }
                cols.push(n);
            }
        })

        table.render({
            elem: '#tableDemo',
            //url: '/static/admin/layuimini/api/table.json',
            url: "{$_SERVER['REQUEST_URI']}",
            where: {list_data: 1},
            parseData: function (res) { //res 即为原始返回的数据
                return {
                    "code": res.status ? 0 : 1, //解析接口状态
                    "msg": res.msg, //解析提示文本
                    "count": res.data.total, //解析数据长度
                    "data": res.data.record //解析数据列表
                };
            },
            toolbar: '#barDemo',
            defaultToolbar: ['filter', 'exports', 'print', {
                title: '提示',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-tips'
            }],
            cols: [cols],
            limits: [10, 15, 20, 25, 50, 100, 500, 1000, 10000],
            limit: 15,
            page: true,
            skin: 'line',
            text: {
                none: '暂无相关数据' //默认：无数据。
            },
        });

        //执行搜索重载
        form.on('submit(data-search-btn)', function (data) {
            table.reload('tableDemo', {
                page: {
                    curr: 1
                }
                , where: data.field,
            }, 'data');

            return false;
        });
    })
</script>
{/block}
<script>
    //触发事件
    layui.use(['table', 'form', 'layer'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table,
            layuimini = layui.layuimini,
            layer = layui.layer;
        table.on('toolbar(tableFilter)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id),//obj.config.id 即为基础参数 id 对应的值
                checkeddata = checkStatus.data,//获取选中行的数据
                checkedlength = checkStatus.data.length,//获取选中行数量，可作为是否有选中行的条件
                checkedisall = checkStatus.isAll,//表格是否全选
                checkedid = [];
            $.map(checkeddata, function (n) {
                checkedid.push(n.id);
            })
            //layer.alert(JSON.stringify(checkeddata));
            switch (obj.event) {
                {block name="bar_js"}
                case 'add':
                    //layer.msg('添加');
                    var index = layer.open({
                        title: '新增',
                        type: 2,
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        area: ['100%', '100%'],
                        content: "/admin/{:\\think\\facade\\Request::controller()}/add",
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                    return false;
                    break;
                case 'delete':
                    //layer.msg('删除');
                    if (checkedlength == 0) {
                        layer.alert('请勾选数据')
                        return false;
                    }
                    layer.confirm('确定要删除么?', {icon: 3, title: '提示'}, function (index) {
                        //do something
                        //向服务端发送删除指令
                        /*
                        $.ajax contentType 和 dataType , contentType 主要设置你发送给服务器的格式，dataType设置你收到服务器数据的格式。
                        在http 请求中，get 和 post 是最常用的。在 jquery 的 ajax 中， contentType都是默认的值：application/x-www-form-urlencoded
                        这种格式的特点就是，name/value 成为一组，每组之间用 & 联接，而 name与value 则是使用 = 连接。
                        如： wwwh.baidu.com/q?key=fdsa&lang=zh 这是get ,
                        而 post 请求则是使用请求体，参数不在 url 中，在请求体中的参数表现形式也是: key=fdsa&lang=zh的形式。
                        键值对这样组织在一般的情况下是没有什么问题的，这里说的一般是，不带嵌套类型JSON 形如这样 {a: 1,b: 2,c: 3}
                        但是在一些复杂的情况下就有问题了。 例如在 ajax 中你要传一个复杂的 json 对像，也就说是对象嵌数组，数组中包括对象，
                        兄果你这样传：{data: {a: [{x: 2}]}}这个复杂对象，
                        application/x-www-form-urlencoded 这种形式是没有办法将复杂的 JSON 组织成键值对形式(当然也有方案这点可以参考 ) ,
                        你传进去可以发送请求，但是服务端收到数据为空， 因为 ajax 没有办法知道怎样处理这个数据。这怎么可以呢？
                        聪明的程序员发现 http 还可以自定义数据类型，于是就定义一种叫 application/json 的类型。
                        这种类型是 text ， 我们 ajax 的复杂JSON数据，用 JSON.stringify序列化后，然后发送，
                        在服务器端接到然后用 JSON.parse 进行还原就行了，这样就能处理复杂的对象了。
                        $.ajax({
                            dataType: 'json',
                            contentType: 'application/json',
                            data: JSON.stringify({a: [{b:1, a:1}]})
                        })
                        这样你就可以发送复杂JSON的对象了。像现在的 restclient 都是这样处理的。
                        */
                        $.ajax({
                            type: "post",
                            contentType: 'application/x-www-form-urlencoded',
                            url: "/admin/{:\\think\\facade\\Request::controller()}/del",
                            data: {id: checkedid},
                            timeout: 30000, //超时时间：30秒
                            dataType: 'json',
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                //TODO: 处理status， http status code，超时 408
                                // 注意：如果发生了错误，错误信息（第二个参数）除了得到null之外，还可能
                                //是"timeout", "error", "notmodified" 和 "parsererror"。
                                layer.alert('接口错误:' + textStatus + "(" + errorThrown + ")");
                            },
                            success: function (res) {
                                // TODO: check result
                                if (res.status == 1) {
                                    //console.log(Object.getOwnPropertyNames(checkStatus));//获取js 对象所有方法
                                    //重载表格
                                    table.reload(obj.config.id, {})
                                }
                                layer.alert(res.msg || '接口出错')
                            }
                        });
                        layer.close(index);
                    });
                    return false;
                    break;
                case 'edit':
                    //layer.msg('编辑');
                    if (checkedlength != 1) {
                        layer.alert('请勾选一条数据数据编辑')
                        return false;
                    }
                    var index = layer.open({
                        title: '编辑',
                        type: 2,
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        area: ['100%', '100%'],
                        content: '/admin/{:\\think\\facade\\Request::controller()}/edit.html?id=' + checkedid[0],
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                    return false;
                    break;
                case 'batch':
                    //layer.msg('编辑');
                    if (checkedlength == 0) {
                        layer.alert('请勾选数据')
                        return false;
                    }
                    var index = layer.open({
                        title: '批量修改',
                        type: 2,
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        area: ['100%', '100%'],
                        content: '/admin/{:\\think\\facade\\Request::controller()}/batch.html?id=' + checkedid.join(','),
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                    return false;
                    break;
                case 'import':
                    var index = layer.open({
                        title: '导入',
                        type: 2,
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        area: ['30%', '30%'],
                        content: '/admin/{:\\think\\facade\\Request::controller()}/import?id=' + checkedid.join(','),
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                    return false;
                    break;
                case 'export':
                    $.download = function (url, data, method) { // 获得url和data
                        if (url && data) {
                            // data 是 string 或者 array/object
                            data = typeof data == 'string' ? data : $.param(data); // 把参数组装成 form的 input
                            var inputs = '';
                            $.each(data.split('&'), function () {
                                var pair = this.split('=');
                                inputs += '<input type="hidden" name="' + pair[0] + '" value="' + pair[1] + '" />';
                            }); // request发送请求
                            $('<form action="' + url + '" method="' + (method || 'post') + '">' + inputs + '</form>').appendTo('body').submit().remove();
                        }
                    };
                    var url = '/admin/{:\\think\\facade\\Request::controller()}/export';
                    var data = {};
                    $.each($('.table-search-fieldset').find('form').serializeArray(), function (i, n) {
                        data[n.name] = n.value;
                    });
                    $.download(url, data, 'get');
                    return false;
                    break;
                {/block}
                }
            });
    })
</script>

<script>
    layui.use(['form', 'table', 'layer'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table,
            layuimini = layui.layuimini,
            layer = layui.layer;
        table.on('tool(tableFilter)', function (obj) {
            //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）
            switch (obj.event) {
                {block name='line_js'}
                {/block}
            }
        })
    })
</script>
{include file='extend/page_js'}
</body>
</html>