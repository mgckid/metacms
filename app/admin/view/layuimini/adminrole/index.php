{extend name='extend/list'}


{block name="line"}
    <a class="layui-btn layui-btn-xs" lay-event="addRoleAccess">分配权限</a>
{/block}

{block name="line_js"}
{__block__}
case 'addRoleAccess':
    //分配权限
    //layer.msg('添加');
    var index = layer.open({
        title: '分配权限',
        type: 2,
        shade: 0.2,
        maxmin: true,
        shadeClose: true,
        area: ['100%', '100%'],
        content: "/admin/{:\\think\\facade\\Request::controller()}/addRoleAccess?id=" + data.id,
    });
    $(window).on("resize", function () {
        layer.full(index);
    });
    return false;
    break

{/block}