{extend name='extend/list'}

{block name="line"}
    <a class="layui-btn layui-btn-xs" lay-event="addSiteConfig" data-access="admin/<?=\think\facade\Request::controller()?>/addSiteConfig" >添加扩展配置</a>
    <a class="layui-btn layui-btn-xs" lay-event="editSiteConfig" data-access="admin/<?=\think\facade\Request::controller()?>/editSiteConfig" >修改扩展配置</a>
{/block}

{block name="line_js"}
        case 'addSiteConfig':
            //分配权限
            //layer.msg('添加');
            var index = layer.open({
                title: '添加扩展配置',
                type: 2,
                shade: 0.2,
                maxmin: true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "/admin/{:\\think\\facade\\Request::controller()}/" + obj.event,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
            return false;
            break
        case 'editSiteConfig':
            //分配权限
            //layer.msg('添加');
            var index = layer.open({
                title: '修改扩展配置',
                type: 2,
                shade: 0.2,
                maxmin: true,
                shadeClose: true,
                area: ['100%', '100%'],
                content: "/admin/{:\\think\\facade\\Request::controller()}/" + obj.event,
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
            return false;
            break
{/block}