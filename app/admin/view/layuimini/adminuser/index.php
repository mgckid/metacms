{extend name='extend/list'}
{block name="bar"}
    {__block__}
    <button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="changepassword"  data-access="admin/<?=\think\facade\Request::controller()?>/changepassword">修改密码</button>
    <button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="resetpassword"  data-access="admin/<?=\think\facade\Request::controller()?>/resetpassword">重置密码</button>
{/block}

{block name="line"}
    {__block__}
    <a class="layui-btn layui-btn-xs" lay-event="addUserRole" data-access="admin/<?=\think\facade\Request::controller()?>/addUserRole" >分配角色</a>
{/block}

{block name="bar_js"}
        {__block__}
        case 'changepassword':
        //layer.msg('编辑');
        if (checkedlength == 0) {
        layer.alert('请勾选数据')
        return false;
        }
        var index = layer.open({
        title: '修改密码',
        type: 2,
        shade: 0.2,
        maxmin: true,
        shadeClose: true,
        area: ['100%', '100%'],
        content: '/admin/{:\\think\\facade\\Request::controller()}/' + obj.event + '?id=' + checkedid.join(','),
        });
        $(window).on("resize", function () {
        layer.full(index);
        });
        return false;
        break;
        case 'resetpassword':
            if (checkedlength == 0) {
            layer.alert('请勾选数据')
                return false;
            }
            var index = layer.open({
            title: '重置密码',
            type: 2,
            shade: 0.2,
            maxmin: true,
            shadeClose: true,
            area: ['100%', '100%'],
            content: '/admin/{:\\think\\facade\\Request::controller()}/' + obj.event + '?id=' + checkedid.join(','),
            });
            $(window).on("resize", function () {
            layer.full(index);
            });
            return false;
        break;
{/block}

{block name='line_js'}
        {__block__}
        case 'addUserRole':
        //分配权限
        //layer.msg('添加');
        var index = layer.open({
        title: '分配权限',
        type: 2,
        shade: 0.2,
        maxmin: true,
        shadeClose: true,
        area: ['100%', '100%'],
        content: "/admin/{:\\think\\facade\\Request::controller()}/" + obj.event + "?user_id=" + data.id,
        });
        $(window).on("resize", function () {
        layer.full(index);
        });
        return false;
        break
{/block}


