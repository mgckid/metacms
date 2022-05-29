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
<div class="layui-form layuimini-form">
    <div class="layui-form-item">
        <div class="layui-form-label">普通操作</div>
        <div class="layui-form-block">
            <button type="button" class="layui-btn layui-btn-primary" onclick="getMaxDept('#authtree_box')">获取树的深度</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="checkAll('#authtree_box')">全选</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="uncheckAll('#authtree_box')">全不选</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="showAll('#authtree_box')">全部展开</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="closeAll('#authtree_box')">全部隐藏</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="getNodeStatus('#authtree_box')">获取节点状态</button>
        </div>
    </div>
    <!--<div class="layui-form-item">
        <div class="layui-form-label">特殊操作</div>
        <div class="layui-form-block">
            <button type="button" class="layui-btn layui-btn-primary" onclick="showDept('#authtree_box')">展开到某层</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="closeDept('#authtree_box')">关闭某层后所有的层</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="listConvert('list.json')">列表转树</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="treeConvertSelect('tree.json')">树转单选树</button>
        </div>
    </div>-->
   <!-- <div class="layui-form-item">
        <div class="layui-form-label">更新及文档</div>
        <div class="layui-form-block">
            <a class="layui-btn layui-btn-primary" target="_blank" href="https://fly.layui.com/jie/24206/">社区原帖</a>
            <a class="layui-btn layui-btn-primary" target="_blank" href="https://github.com/wangerzi/layui-authtree">最新源码+文档</a>
            <a class="layui-btn layui-btn-primary" target="_blank" href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=zfT5_fv19fn1-428vOOuoqA">快速反馈</a>
        </div>
    </div>-->
    <?= Form::getInstance()->input_submit('确认保存','class="layui-btn" lay-submit lay-filter="saveBtn"')->create() ?>
</div>
<script src="/static/admin/layuimini/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="/static/admin/layuiconfig.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'layer', 'authtree'], function () {
        var form = layui.form,
            layer = layui.layer,
            authtree = layui.authtree,
            $ = layui.$;

        // 一般来说，权限数据是异步传递过来的
        $.get(
            "{:url('Admin/adminrole/getRoleAccess',['id'=>$_REQUEST['id']])}",
            function (data) {
                // 如果后台返回的不是树结构，请使用 authtree.listConvert 转换
                var trees = authtree.listConvert(data.data.list, {
                    primaryKey: 'id'
                    , startPid: 0
                    , parentKey: 'pid'
                    , nameKey: 'name'
                    , valueKey: 'id'
                    , checkedKey: data.data.checked
                });
                authtree.render('#authtree_box', trees, {
                    inputname: 'access_sn[]',
                    layfilter: 'lay-check-auth',
                    autowidth: true,
                    openall: true,
                });
            },
            'json'
        );

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            $.post(
                data.form.action,
                data.field,
                function (res) {
                    if (res.status != 1) {
                        layer.alert(res.msg||'接口出错')
                    } else {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
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
<script type="text/javascript">
    // 获取最大深度样例
    function getMaxDept(dst){
        layui.use(['jquery', 'layer', 'authtree'], function(){
            var layer = layui.layer;
            var authtree = layui.authtree;

            layer.alert('树'+dst+'的最大深度为：'+authtree.getMaxDept(dst));
        });
    }
    // 全选样例
    function checkAll(dst){
        layui.use(['jquery', 'layer', 'authtree'], function(){
            var layer = layui.layer;
            var authtree = layui.authtree;

            authtree.checkAll(dst);
        });
    }
    // 全不选样例
    function uncheckAll(dst){
        layui.use(['jquery', 'layer', 'authtree'], function(){
            var layer = layui.layer;
            var authtree = layui.authtree;

            authtree.uncheckAll(dst);
        });
    }
    // 显示全部
    function showAll(dst){
        layui.use(['jquery', 'layer', 'authtree'], function(){
            var layer = layui.layer;
            var authtree = layui.authtree;

            authtree.showAll(dst);
        });
    }
    // 隐藏全部
    function closeAll(dst){
        layui.use(['jquery', 'layer', 'authtree'], function(){
            var layer = layui.layer;
            var authtree = layui.authtree;

            authtree.closeAll(dst);
        });
    }
    // 获取节点状态
    function getNodeStatus(dst){
        layui.use(['jquery', 'layer', 'authtree', 'laytpl'], function(){
            var layer = layui.layer;
            var authtree = layui.authtree;
            var laytpl = layui.laytpl;

            // 获取所有节点
            var all = authtree.getAll('#authtree_box');
            // 获取所有已选中节点
            var checked = authtree.getChecked('#authtree_box');
            // 获取所有未选中节点
            var notchecked = authtree.getNotChecked('#authtree_box');
            // 获取选中的叶子节点
            var leaf = authtree.getLeaf('#authtree_box');
            // 获取最新选中
            var lastChecked = authtree.getLastChecked('#authtree_box');
            // 获取最新取消
            var lastNotChecked = authtree.getLastNotChecked('#authtree_box');

            var data = [
                {func: 'getAll', desc: '获取所有节点', data: all},
                {func: 'getChecked', desc: '获取所有已选中节点', data: checked},
                {func: 'getNotChecked', desc: '获取所有未选中节点', data: notchecked},
                {func: 'getLeaf', desc: '获取选中的叶子节点', data: leaf},
                {func: 'getLastChecked', desc: '获取最新选中', data: lastChecked},
                {func: 'getLastNotChecked', desc: '获取最新取消', data: lastNotChecked},
            ];

            var string =  laytpl($('#LAY-auth-tree-nodes').html()).render({
                data: data,
            });
            layer.open({
                title: '节点状态'
                ,content: string
                ,area: '800px'
                ,tipsMore: true
            });
            $('body').unbind('click').on('click', '.LAY-auth-tree-show-detail', function(){
                layer.open({
                    type: 1,
                    title: $(this).data('title')+'-节点详情',
                    content: '['+$(this).data('content')+']',
                    tipsMore: true
                });
            });
        });
    }
    // 显示到某层
    function showDept(dst) {
        layui.use(['layer', 'authtree', 'jquery'], function(){
            var jquery = layui.jquery;
            var layer = layui.layer;
            var authtree = layui.authtree;

            layer.prompt({title: '显示到某层'}, function(value, index, elem) {
                authtree.showDept(dst, value);
                layer.close(index);
            });
        });
    }
    // 关闭某层以后的所有层
    function closeDept(dst) {
        layui.use(['layer', 'authtree', 'jquery'], function(){
            var jquery = layui.jquery;
            var layer = layui.layer;
            var authtree = layui.authtree;

            layer.prompt({title: '关闭某层以后的所有层'}, function(value, index, elem) {
                authtree.closeDept(dst, value);
                layer.close(index);
            });
        });
    }
    // 树转下拉树
    function treeConvertSelect(url) {
        layui.use(['layer', 'authtree', 'jquery', 'form', 'code', 'laytpl'], function(){
            var $ = layui.jquery;
            var layer = layui.layer;
            var authtree = layui.authtree;
            var form = layui.form;
            var laytpl = layui.laytpl;

            layer.open({
                title: '树转下拉树演示'
                ,content: '<div id="LAY-auth-tree-convert-select-dom"></div>'
                ,area: ['800px', '400px']
                ,tipsMore: true
                ,success: function() {
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success: function(res){
                            // 更多传入参数及其具体意义请查看文档
                            var selectList = authtree.treeConvertSelect(res.data.trees, {
                                childKey: 'list',
                            });
                            console.log(selectList);
                            // 渲染单选框
                            var string =  laytpl($('#LAY-auth-tree-convert-select').html()).render({
                                list: selectList,
                                code: JSON.stringify(res, null, 2),
                            });
                            $('#LAY-auth-tree-convert-select-dom').html(string);
                            layui.code({
                                title: '返回的树状数据'
                            });
                            form.render('select');
                            // TODO::form.on('select(LAY-FILTER)')监听选中
                        },
                        error: function(xml, errstr, err) {
                            layer.alert(errstr+'，获取样例数据失败，请检查是否部署在本地服务器中！');
                        }
                    });
                }
            });
        });
    }
    // 转换列表
    function listConvert(url) {
        layui.use(['layer', 'authtree', 'jquery', 'form', 'code'], function(){
            var $ = layui.jquery;
            var layer = layui.layer;
            var authtree = layui.authtree;
            var form = layui.form;

            layer.open({
                title: '列表转树演示'
                ,content: '<fieldset class="layui-elem-field layui-field-title"><legend>列表数据转权限树</legend></fieldset><form class="layui-form"> <div class="layui-form-item"> <label class="layui-form-label">多选权限</label> <div class="layui-input-block"> <div id="LAY-auth-tree-convert-index"></div> </div> </div> <div class="layui-form-item"> <div class="layui-input-block"> <button class="layui-btn" type="submit" lay-submit lay-filter="LAY-auth-tree-submit">提交</button> <button class="layui-btn layui-btn-primary" type="reset">重置</button> </div> </div></form><pre class="layui-code" id="LAY-auth-tree-convert-code"></pre>'
                ,area: ['800px', '400px']
                ,tipsMore: true
                ,success: function() {
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success: function(res){
                            $('#LAY-auth-tree-convert-code').text(JSON.stringify(res, null, 2));
                            layui.code({
                                title: '返回的列表数据'
                            });
                            // 支持自定义递归字段、数组权限判断等
                            // 深坑注意：如果API返回的数据是字符串，那么 startPid 的数据类型也需要是字符串
                            var trees = authtree.listConvert(res.data.list, {
                                primaryKey: 'alias'
                                ,startPid: '0'
                                ,parentKey: 'palias'
                                ,nameKey: 'name'
                                ,valueKey: 'alias'
                                ,checkedKey: res.data.checkedAlias
                            });
                            // 如果页面中多个树共存，需要注意 layfilter 需要不一样
                            authtree.render('#LAY-auth-tree-convert-index', trees, {
                                inputname: 'authids[]',
                                layfilter: 'lay-check-convert-auth',
                                // openall: true,
                                autowidth: true,
                            });
                        },
                        error: function(xml, errstr, err) {
                            layer.alert(errstr+'，获取样例数据失败，请检查是否部署在本地服务器中！');
                        }
                    });
                }
            });
        });
    }
</script>
<!-- 状态模板 -->
<script type="text/html" id="LAY-auth-tree-nodes">
    <style type="text/css">
        .layui-layer-page .layui-layer-content{
            padding: 20px;
            line-height: 22px;
        }
    </style>
    <table class="layui-table">
        <thead>
        <th>方法名</th>
        <th>描述</th>
        <th>节点</th>
        </thead>
        <tbody>
        {{# layui.each(d.data, function(index, item) { }}
        <tr>
            <td>{{item.func}}</td>
            <td>{{item.desc}}</td>
            <td><a class="LAY-auth-tree-show-detail" href="javascript:;" data-title="{{item.desc}}" data-content="{{item.data.join(']<br>[')}}">查看详情</a>({{item.data.length}})</td>
        </tr>
        {{# });}}
        </tbody>
    </table>
</script>
</body>
</html>