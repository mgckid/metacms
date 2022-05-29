{extend name='extend/treelist'}
{block name="bar"}
        <button class="layui-btn layui-btn-sm" lay-event="add"  data-access="admin/<?=\think\facade\Request::controller()?>/add">新增模型</button>
{/block}

{block name="line"}
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="edit" data-access="admin/<?=\think\facade\Request::controller()?>/edit">修改字典</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="delete" data-access="admin/<?=\think\facade\Request::controller()?>/del">删除</a>
    {{# if(d.dict_level ==1 || d.dict_level ==2 ){ }}
        <a class="layui-btn layui-btn-xs" lay-event="add" data-access="admin/<?=\think\facade\Request::controller()?>/add">添加字典</a>
    {{# } }}
    {{# if(d.dict_level ==1){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="edit_rule" data-access="admin/dictionaryRule/edit">修改规则</a>
    {{# } }}
{/block}



                {block name="line_js"}
                case 'add':
                    var index = layer.open({
                        type: 2,
                        area: ['100%', '100%'],
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        title: '添加字典',
                        //content: "{:url('Admin/Dictionary/addDictionary')}?pid=" + data.id + "&dict_type=" + data.dict_type,
                        content: "/admin/{:\\think\\facade\\Request::controller()}/add?pid=" + data.id + "&dict_level=" + (data['dict_level'] + 1),
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                    return false;
                    break;
                case 'edit':
                    var index = layer.open({
                        type: 2,
                        area: ['100%', '100%'],
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        title: '修改字典',
                        content: "/admin/{:\\think\\facade\\Request::controller()}/edit?id=" + data.id ,
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                    return false;
                    break;
                case 'delete':
                    layer.confirm(
                        '真的删除行么',
                        function (index) {
                            //向服务端发送删除指令
                            $.post(
                                '{:url("/admin/{:\\think\\facade\\Request::controller()}/del")}',
                                {id: data.id},
                                function (res) {
                                    if (res.status != 1) {
                                        layer.alert(res.msg || '接口出错')
                                    } else {
                                        layer.msg(res.msg, {
                                            icon: 1,
                                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                        }, function () {
                                            //do something
                                            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                        });
                                    }
                                }, 'json'
                            );
                            layer.close(index);
                        });
                    return false;
                    break;
                case 'edit_rule':
                    var index = layer.open({
                        type: 2,
                        area: ['100%', '100%'],
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        title: '规则编辑',
                        content: "{:url('admin/cmsdictionary/editrule')}?id=" + data.id,
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                    return false;
                    break;
                {/block}

