/**
 * Created by Administrator on 2020/10/22.
 */
layui.config({
    base: '/static/admin/layuimod/'
    , version: 20201025
    , debug: true
}).extend({
    //useage:'path/to/useage', 不需要js 后缀
    authtree: 'layui-authtree-v1.2/extends/authtree', //扩展 layui 的权限树 authtree
    treeTable: 'treetable-lay3/treeTable',//treeTable 树形表格树、树表展开关闭及一些常用功能。加入了状态记忆、渲染、刷新、更换图标、列渲染等配置。并设置一些监听、获取参数快捷操作。并在示例中扩展了下拉选择（单选、多选）功能。

    //inte: 'inte',//interact 多级联动 多级联动，只要查出具有父子关系的数据集便可以使用此联动插件，包含选择父级出现子级，默认选中等，省市区联动也可以用
   // xmselect: 'xm-select/dist/xm-select',/*基于layui的多选解决方案*/
})