
树形表格treeTable实现了layui数据表格的大部分功能，并且在用法上与几乎数据表格一致， 支持懒加载、复选框联动(半选)、拖拽列宽、固定表头等功能。

- 演示地址：[https://whvse.gitee.io/treetable-lay/demo/](https://whvse.gitee.io/treetable-lay/demo/index.html)

- 开发文档：[https://gitee.com/whvse/treetable-lay/wikis/pages](https://gitee.com/whvse/treetable-lay/wikis/pages)

## 更新日志

- 2020-04-27
    - 解决refresh方法data模式未转pid形式bug
    - 解决单元格编辑报错bug
    - 懒加载模式haveChild为true请求完无数据自动更新图标
    - useAdmin参数默认改为false(默认不使用admin.ajax)
    - 列参数增加thAlign配置表头的对齐方式

- 2020-04-18 (v3.0)
    - 支持拖拽列宽、表头工具栏toolbar
    - 支持隐藏显示列、打印、导出
    - 支持url、method、where、headers等url方式加载参数，同时兼容之前reqData写法
    - cols使用二维数组，支持同layui表格一样配置复杂表头
    - 复选框半选状态不再受form.render影响
    - 解决ful-xxx高度计算与layui表格有偏差问题
    - 解决minWidth参数无效果问题
    - 解决图标列溢出不显示省略号的问题
    - 对于children形式数据不会用到id属性
    - 解决单选获取选中的问题

- 2019-12-27
    - 增加单元格溢出省略，点击悬浮展开全部(2019-12-27)
    - 解决空数据时刷新后空提示不移除的bug(2019-12-21)
    - 优化固定表头及固定宽度(2019-12-21)
    - 获取选中数据增加可获取非半选的选中数据(2019-12-21)

- 2019-11-18 (v2.0)

    - 重构treeTable，不再基于数据表格table模块
    - 支持懒加载(异步加载)、支持数据渲染
    - 同时支持pid形式数据和children形式数据
    - 无需指定最顶级pid，自动查找
    - 支持复选框联动，支持半选状态
    - 支持折叠状态记忆
    - 支持只刷新某个节点下数据
    - 支持自定义树形图标
    - 支持设置节点勾选、获取勾选节点
    - 支持行单击、双击、单元格单击、双击事件
    - 支持单元格编辑，并且支持校验格式
    - 支持固定表头，支持ful-xxx的写法
    - 支持自定义复杂表头
    - 优化搜索功能，提供更好的搜索体验
- 2018-07-22 (v1.0)
    - 基于数据表格table模板实现树形结构
    - 实现折叠/展开功能 

> 小问题会抽时间解决，大升级会间隔较长，目前只有固定列未实现，分页和排序在树中不常见，懒加载可代替分页。

<br/>

## 导入模块

最新版只需要一个`treeTable.js`即可，无需css：
```javascript
layui.config({
    base: '/'  // 配置模块所在的目录
}).use(['treeTable'], function () {
    var treeTable = layui.treeTable;

});
```
如果不会引用先到layui官网查看模块规范介绍。

<br/>

## 效果展示

![树形表格](https://images.gitee.com/uploads/images/2020/0418/133701_39042553_1518571.png)
