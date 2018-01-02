# metacms

##Overview

metacms is a simple and extensible  content management system base on metacms mvc  framework.every data in metacms is a base meta data just like it name;

metacms是基于metacms mvc 框架设计的简单且可扩展的内容管理系统。这个系统设计的指导思想是所有数据都是最小粒度的，这是这个系统可以扩展的关键原因。metacms分为四大模块，核心框架、
后台模块、api接口模块、前台模块。其中前台模块不直接访问数据库，而是通过api模块的接口返回数据，前台将数据缓存下来。这样好处是后台和接口可以随意扩展，不会对前台造成影响,并且前台做了
数据缓存处理，可以处理高并发的访问。metacms framework 是为这个系统开发的框架，该框架大量采用成熟开源的组件，提高了开发效率并降低了开发者的学习成本，避免重复造轮子。本框架使用了idiorm
ORM处理数据库访问，使用了Pimple依赖注入组件管理组件依赖，使用plates php原生模版引擎减少开发者的学习成本。还有其他的组件不一一列出来了。

##feature

1、基于mvc思想设计，开发简单扩展方便

2、前台数据通过API接口获取，增加安全性，减少重复开发，增加扩展性(多端共享数据层)

3、后台模块较为完善,支持rbac权限管理,支持内容模型管理，内置数据库字典管理需要修改配置就可以处理不同的业务逻辑

4、本系统数据库数据库采用窄表设计，方便开发者根据自己需要扩展。

##develop

metacms是我自己设计的一个cms系统(更像cmf),这个系统，后台模块和接口模块可以稳定的迭代开发，前台模块可以根据自己需要自行组织没有严格限制,可以做门户网站,个人博客，乃至扩展开发成商城都是可以的
我开发这个系统历时2年，已经在我的其他项目中使用，由于只有我一个开发者，很多其他想法和功能还亟待更多开发者进来一起完善，希望更多的开发者加入进来，一起将这个系统完善的更好，在更多的项目中使用。

metacms开发者QQ群:691932844

##install

目前只能手工安装，后面会增加安装程序

1、在mysql数据库中创建任意名称数据库，字符集选择为utf-8，并使用数据库工具导入cms根目录下的metacms.sql 数据库结构和默认数据。

2、进入cms项目目录中，找到config目录中，编辑该目录下的db.php 配置文件，HOME_URL,API_URL配置为自己的域名；修改DB配置下的主机，数据库名，端口，用户名和密码为自己的配置。

3、后台默认访问地址为www.xxx.me/?route=Admin  用户名默认为admin 密码默认为123456；前台默认访问地址为www.xxx.me/?route=Home; 接口访问地址默认为www.xxx.me/?route=Api



##License

GNU General Public License version 3 (GPLv3)



