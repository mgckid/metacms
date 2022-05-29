## metadmin后台管理系统介绍:
话一下 metadmin后台管理系统 开源初衷，唱衰PHP的声音最近这几年一直不断，在这个移动互联网的时代一切都要快快快，相信很多其他语言的朋友已经不会专门花时间去深入学习PHP这门语言了，但是原有历史遗留的系统还是要维护和找上门的项目还是要接，因此某些时刻确实需要一款功能成熟且稳定，易扩展，易维护的后台(网站)程序去做短平快的项目和需求，那么还是有一点现实的市场空间存在。这就是让我把这个系统完善并开源出来的目的。

在写这个项目的过程我的想法是要使用成熟的开源项目集成，不要重复造轮子;代码要复用让工作量能一个人完成,能节省时间；功能要一定程度支持自动生成,实现快速搭建。

metadmin后台管理系统 的适应性很强，进可做企业管理后台/erp系统，退可做网站后台并自带一个简单的前台博客模块作为预览

metadmin后台管理系统 基于 [thinkphp6.0](https://www.kancloud.cn/manual/thinkphp6_0/1037479)框架 + [layuimini后台管理模板](http://layuimini.99php.cn/docs/index.html)  组合实现功能开发。 点击链接可以进去对应官网查看开发文档

metadmin后台管理系统 数据库支持mysql/sqlite



## 系统特点：
```
1.全项目使用三层架构，数据层，控制层，视图层分离

2.数据层全部使用接口返回和复用支持各种客户端访问接口

3.支持内容模型自定义，可用于内容发布（例如文章，产品）

4.后台表单支持配置动态生成

5.后台用户权限支持规则定义并自动获取

6.支持mysql/sqlite多种数据库

7.使用成熟的开源项目集成（thinkphp+layuimini后台模板）

8.各个功能支持表格导入导出简化数据维护
```


## 使用：
```
1.数据库连接默认为sqlite,带有测试数据

2.安装按照thinkphp的官方教程  在web服务器中将根目录指向项目根目录并绑定域名，
  同时将public设置为运行目录，
  
3.在命令行打开项目根目录并执行 composer update 命令安装全部包含的库文件 

4.在浏览器中访问自己设置的域名即可（本机运行还需修改本机host）
```





## 项目链接:

**github**: [https://github.com/mgckid/metacms](https://github.com/mgckid/metacms)

**gitee**:[https://gitee.com/mgckid/metacms](https://gitee.com/mgckid/metacms)




## 联系方式:

如果各位朋友有类似需求和相同观点那么这个程序就很适合你，若需要进一步功能扩展和二次开发可以联系我（加群找群主）

metadmin/metacms/form开源交流qq群（691932844）



## 后台效果：

![效果图](https://gitee.com/mgckid/metacms/raw/master/metadmin.png)