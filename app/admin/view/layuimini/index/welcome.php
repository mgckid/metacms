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
<style>
    .layui-top-box {padding:40px 20px 20px 20px;color:#fff}
    .panel {margin-bottom:17px;background-color:#fff;border:1px solid transparent;border-radius:3px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}
    .panel-body {padding:15px}
    .panel-title {margin-top:0;margin-bottom:0;font-size:14px;color:inherit}
    .label {display:inline;padding:.2em .6em .3em;font-size:75%;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em;margin-top: .3em;}
    .layui-red {color:red}
    .main_btn > p {line-height:20px;margin-top: 5px;}
</style>
<body>
<div class="layuimini-container">


    <div class="layui-box">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md12">
                <blockquote class="layui-elem-quote main_btn">
                    <p> 话一下 metadmin后台管理系统 开源初衷，唱衰php的声音最近这几年一直不断，在这个移动互联网的时代一切都要快快快，相信很多其他语言的朋友已经不会专门花时间去深入学习这门语言了，
                        <br> 但是原有历史遗留的系统还是要维护和找上门的项目还是要接，因此某些时刻确实需要一款功能成熟且稳定，易扩展，易维护的后台(网站)程序去做短平快的项目和需求，
                        <br> 那么还是有一点现实的市场空间存在。这就是让我把这个系统完善并开源出来的目的。</p>
                    <p>在写这个项目的过程我的想法是要使用成熟的开源项目集成，不要重复造轮子;代码要复用让工作量能一个人完成,能节省时间；功能要一定程度支持自动生成,实现快速搭建。</p>
                    <p>metadmin后台管理系统 的适应性很强，进可做企业管理后台/erp系统，退可做网站后台并自带一个简单的前台博客模块作为预览</p>

                    <p>metadmin后台管理系统 基于 <a class="layui-btn layui-btn-xs" target="_blank" href="https://www.kancloud.cn/manual/thinkphp6_0/1037479">thinkphp6.0框架</a>
                        +<a class="layui-btn layui-btn-xs" target="_blank" href="http://layuimini.99php.cn/docs/index.html">layuimini后台管理模板</a>
                        组合实现功能开发。 点击按钮可以进去对应官网查看开发文档</p>
                    <p>metadmin后台管理系统 数据库支持mysql/sqlite</p>
                    <p>
                        系统特点：<br>
                        1.全项目三层架构，数据层，控制层，视图层分离<br>
                        2.数据层全部使用接口返回和复用支持各种客户端访问接口<br>
                        3.支持内容模型自定义，可用于内容发布（例如文章，产品）<br>
                        4.后台表单支持配置动态生成<br>
                        5.后台用户权限支持规则定义并自动获取<br>
                        6.支持mysql/sqlite多种数据库<br>
                        7.使用成熟的开源项目集成（thinkphp+layuimini后台模板）<br>
                        8.各个功能支持表格导入导出简化数据维护
                    </p>
                    <p>如果各位朋友有类似需求和相同观点那么这个程序就很适合你，若需要进一步功能扩展和二次开发可以联系我（加群找群主）</p>

                    <p>metadmin/metacms/form开源交流qq群（691932844）:
                        <a target="_blank" href="https://jq.qq.com/?_wv=1027&k=s0qB6q4f"><img border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="layuimini" title="layuimini"></a>
                        <br> 手机qq扫码入群:
                        <img src="/static/default/qq_qun20220528131948.png" alt="">
                    </p>
                    <p>朋友们觉得metadmin后台管理系统开源项目有助于你的话帮我的GitHub和Gitee加个Star支持一下吧</p>
                    <p>
                        github: <a class="layui-btn layui-btn-xs" target="_blank" href="https://github.com/mgckid/metacms">metadmin后台管理系统</a>
                        <br>
                        gitee:  <a class="layui-btn layui-btn-xs" target="_blank" href="https://gitee.com/mgckid/metacms">metadmin后台管理系统</a>
                    </p>
                    <!--<p>GitHub地址：
                        <iframe src="https://ghbtns.com/github-btn.html?user=mgckid&repo=metacms&type=shttps://github.com/mgckid/metacmstar&count=true" frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
                        <iframe src="https://ghbtns.com/github-btn.html?user=mgckid&repo=metacms&type=fork&count=true" frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
                    </p>
                    <p>Gitee地址：
                        <a href="https://gitee.com/mgckid/metacms" target="_blank"><img src="https://gitee.com/mgckid/metacms/badge/star.svg?theme=dark" alt="star"></a>
                        <a href="https://gitee.com/mgckid/metacms" target="_blank"><img src="https://gitee.com/mgckid/metacms/badge/fork.svg?theme=dark" alt="fork"></a>
                    </p>-->
                </blockquote>
            </div>
        </div>
    </div>

    <div class="layui-box" style="display: none">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md6">
                <table class="layui-table">
                    <colgroup>
                        <col width="150">
                        <col width="200">
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th>昵称</th>
                        <th>加入时间</th>
                        <th>签名</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>贤心</td>
                        <td>2016-11-29</td>
                        <td>人生就像是一场修行</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    <tr>
                        <td>许闲心</td>
                        <td>2016-11-28</td>
                        <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="layui-col-md6">
                <ul class="layui-timeline">
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <h3 class="layui-timeline-title">8月18日</h3>
                            <p>
                                layui 2.0 的一切准备工作似乎都已到位。发布之弦，一触即发。
                                <br>不枉近百个日日夜夜与之为伴。因小而大，因弱而强。
                                <br>无论它能走多远，抑或如何支撑？至少我曾倾注全心，无怨无悔 <i class="layui-icon"></i>
                            </p>
                        </div>
                    </li>
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <h3 class="layui-timeline-title">8月16日</h3>
                            <p>杜甫的思想核心是儒家的仁政思想，他有“<em>致君尧舜上，再使风俗淳</em>”的宏伟抱负。个人最爱的名篇有：</p>
                            <ul>
                                <li>《登高》</li>
                                <li>《茅屋为秋风所破歌》</li>
                            </ul>
                        </div>
                    </li>
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <h3 class="layui-timeline-title">8月15日</h3>
                            <p>
                                中国人民抗日战争胜利72周年
                                <br>常常在想，尽管对这个国家有这样那样的抱怨，但我们的确生在了最好的时代
                                <br>铭记、感恩
                                <br>所有为中华民族浴血奋战的英雄将士
                                <br>永垂不朽
                            </p>
                        </div>
                    </li>
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">过去</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="layuimini-main layui-top-box" style="display: none">
        <div class="layui-row layui-col-space10">

            <div class="layui-col-md3">
                <div class="col-xs-6 col-md-3">
                    <div class="panel layui-bg-cyan">
                        <div class="panel-body">
                            <div class="panel-title">
                                <span class="label pull-right layui-bg-blue">实时</span>
                                <h5>用户统计</h5>
                            </div>
                            <div class="panel-content">
                                <h1 class="no-margins">1234</h1>
                                <div class="stat-percent font-bold text-gray"><i class="fa fa-commenting"></i> 1234</div>
                                <small>当前分类总记录数</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md3">
                <div class="col-xs-6 col-md-3">
                    <div class="panel layui-bg-blue">
                        <div class="panel-body">
                            <div class="panel-title">
                                <span class="label pull-right layui-bg-cyan">实时</span>
                                <h5>商品统计</h5>
                            </div>
                            <div class="panel-content">
                                <h1 class="no-margins">1234</h1>
                                <div class="stat-percent font-bold text-gray"><i class="fa fa-commenting"></i> 1234</div>
                                <small>当前分类总记录数</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md3">
                <div class="col-xs-6 col-md-3">
                    <div class="panel layui-bg-green">
                        <div class="panel-body">
                            <div class="panel-title">
                                <span class="label pull-right layui-bg-orange">实时</span>
                                <h5>浏览统计</h5>
                            </div>
                            <div class="panel-content">
                                <h1 class="no-margins">1234</h1>
                                <div class="stat-percent font-bold text-gray"><i class="fa fa-commenting"></i> 1234</div>
                                <small>当前分类总记录数</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="col-xs-6 col-md-3">
                    <div class="panel layui-bg-orange">
                        <div class="panel-body">
                            <div class="panel-title">
                                <span class="label pull-right layui-bg-green">实时</span>
                                <h5>订单统计</h5>
                            </div>
                            <div class="panel-content">
                                <h1 class="no-margins">1234</h1>
                                <div class="stat-percent font-bold text-gray"><i class="fa fa-commenting"></i> 1234</div>
                                <small>当前分类总记录数</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/static/admin/layuimini/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="/static/admin/layuiconfig.js" charset="utf-8"></script>
</body>
</html>