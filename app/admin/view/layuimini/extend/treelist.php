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
    layui.use(['treeTable', 'form', 'layer'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.treeTable,
            layuimini = layui.layuimini,
            layer = layui.layer;
        var cols = [];
        var list_schema = eval('{$list_init|json_encode=JSON_UNESCAPED_UNICODE|raw}');
        $.each(list_schema, function (i, n) {
            if (!n.hide) {
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
            tree: {
                iconIndex: 1,
                isPidData: true,
                idName: 'authorityId',
                pidName: 'parentId',
                arrowType: 'arrow2',
                getIcon: 'ew-tree-icon-style2',
                idName: 'id',
                pidName: 'pid',
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
    layui.use(['treeTable', 'form', 'layer'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.treeTable,
            layuimini = layui.layuimini,
            layer = layui.layer;
        table.on('toolbar(tableDemo)', function (obj) {
            var data = obj.data; //获得当前行数据
            var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

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
                        content: "/admin/{:\\think\\facade\\Request::controller()}/add?pid=0&dict_level=1",
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                    return false;
                break;
                {/block}
            }
        });
    })
</script>

<script>
    //触发事件
    layui.use(['treeTable', 'form', 'layer'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.treeTable,
            layuimini = layui.layuimini,
            layer = layui.layer;
        table.on('tool(tableDemo)', function (obj) {
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）
            switch (obj.event) {
                {block name="line_js"}
                {/block}
            }
        });
    })
</script>
{include file='extend/page_js'}
</body>
</html>








