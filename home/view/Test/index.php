<!-- 重点参数：renderOptions -->
<!doctype html>
<html lang="zh-CN">

<head>
    <!-- 原始地址：//webapi.amap.com/ui/1.0/ui/misc/PathSimplifier/examples/adjust-style.html -->
    <base href="//webapi.amap.com/ui/1.0/ui/misc/PathSimplifier/examples/" />
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>调整线、点样式</title>
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0px;
        }

        #outer-box {
            height: 100%;
            padding-right: 280px;
        }

        #container {
            height: 100%;
            width: 100%;
        }

        #panel {
            position: absolute;
            top: 0;
            right: 0;
            width: 280px;
            z-index: 999;
            height: calc(100% - 5px);
            overflow: hidden;
            overflow-y: auto;
        }

        #my-gui-container {
            height: 1200px;
        }

        #my-gui-container h3 {
            margin: 10px 0 3px 0;
        }

        #my-gui-container .close-button {
            display: none;
        }

        #my-gui-container .dg {
            float: none;
            margin: 0 0 5px 5px;
        }

        .hide {
            display: none;
        }

        #loadingTip {
            position: absolute;
            z-index: 9999;
            top: 0;
            left: 0;
            padding: 3px 10px;
            background: red;
            color: #fff;
            font-size: 13px;
        }

        #exportBtn {
            margin: 5px 0 5px 5px;
            display: block;
            width: 250px;
            line-height: 150%;
        }

        #exportConfigPanel {
            position: absolute;
            z-index: 9999;
            top: 0;
            left: 0;
            padding: 3px 10px;
            background: #1a1a1a;
            color: #fff;
            font-size: 13px;
            height: 90%;
            overflow: auto;
        }

        #exportConfigPanel pre {
            margin: 0;
        }
    </style>
</head>

<body>
<div id="outer-box">
    <div id="container">
    </div>
    <div id="panel">
        <button id="exportBtn">显示配置信息</button>
        <div id="my-gui-container"></div>
    </div>
</div>
<script type="text/javascript" src='//webapi.amap.com/maps?v=1.3&key=ff274346bc8b6240bff19bc65579d339'></script>
<script src="../../../../plug/ext/dat.gui.min.js?v=1.0.10"></script>
<!-- UI组件库 1.0 -->
<script src="//webapi.amap.com/ui/1.0/main.js?v=1.0.10"></script>
<script type="text/javascript">
//创建地图
var map = new AMap.Map('container', {
    zoom: 4
});

AMapUI.load(['ui/misc/PathSimplifier', 'lib/$', 'lib/utils'], function(PathSimplifier, $, utils) {

    if (!PathSimplifier.supportCanvas) {
        alert('当前环境不支持 Canvas！');
        return;
    }

    var defaultRenderOptions = {
        renderAllPointsIfNumberBelow: -1,
        pathTolerance: 2,
        keyPointTolerance: 0,
        pathLineStyle: {
            lineWidth: 3,
            strokeStyle: '#F7B538',
            borderWidth: 1,
            borderStyle: '#eeeeee',
            dirArrowStyle: false
        },
        pathLineHoverStyle: {
            lineWidth: 3,
            strokeStyle: 'rgba(204, 63, 88,1)',
            borderWidth: 1,
            borderStyle: '#cccccc',
            dirArrowStyle: false
        },
        pathLineSelectedStyle: {
            lineWidth: 6,
            strokeStyle: '#C11534',
            borderWidth: 1,
            borderStyle: '#cccccc',
            dirArrowStyle: true
        },
        dirArrowStyle: {
            stepSpace: 35,
            strokeStyle: '#ffffff',
            lineWidth: 2
        },
        startPointStyle: {
            radius: 4,
            fillStyle: '#109618',
            lineWidth: 1,
            strokeStyle: '#eeeeee'
        },
        endPointStyle: {
            radius: 4,
            fillStyle: '#dc3912',
            lineWidth: 1,
            strokeStyle: '#eeeeee'
        },
        keyPointStyle: {
            radius: 3,
            fillStyle: 'rgba(8, 126, 196, 1)',
            lineWidth: 1,
            strokeStyle: '#eeeeee'
        },
        keyPointHoverStyle: {
            radius: 4,
            fillStyle: 'rgba(0, 0, 0, 0)',
            lineWidth: 2,
            strokeStyle: '#ffa500'
        },
        keyPointOnSelectedPathLineStyle: {
            radius: 4,
            fillStyle: 'rgba(8, 126, 196, 1)',
            lineWidth: 2,
            strokeStyle: '#eeeeee'
        }
    };

    var pathSimplifierIns = new PathSimplifier({

        zIndex: 100,

        map: map,

        getPath: function(pathData, pathIndex) {

            return pathData.path;
        },
        getHoverTitle: function(pathData, pathIndex, pointIndex) {

            if (pointIndex >= 0) {
                //point
                return pathData.name + '，点:' + pointIndex + '/' + pathData.path.length;
            }

            return pathData.name + '，点数量' + pathData.path.length;
        },
        renderOptions: defaultRenderOptions
    });

    window.pathSimplifierIns = pathSimplifierIns;

    $('<div id="loadingTip">加载数据，请稍候...</div>').appendTo(document.body);

    $.getJSON('http://a.amap.com/amap-ui/static/data/big-routes.json', function(d) {

        $('#loadingTip').remove();

        pathSimplifierIns.setData(d);

        pathSimplifierIns.setSelectedPathIndex(0);

        // var navg = pathSimplifierIns.createPathNavigator(7, {
        //     loop: true,
        //     speed: 300000
        // });

        // navg.start();

        // window.navg=navg;
    });

    var customContainer = document.getElementById('my-gui-container');

    function createRenderEngGui() {

        function RenderEngOptions() {

            this.pathTolerance = 2;
            this.keyPointTolerance = -1;
            this.renderAllPointsIfNumberBelow = 0;
        }

        var renderEngParams = new RenderEngOptions();

        var renderEngGui = new dat.GUI({
            width: 260,
            autoPlace: false,
        });

        renderEngGui.add(renderEngParams, 'renderAllPointsIfNumberBelow', 0, 1000).step(100).onChange(render);

        renderEngGui.add(renderEngParams, 'pathTolerance', 0, 50).step(1).onChange(render);

        renderEngGui.add(renderEngParams, 'keyPointTolerance', -1, 20).step(1).onChange(render);

        //renderEngGui.add(renderEngParams, 'disableHardcoreWhenPointsNumBelow', 0, 10000).step(1000).onChange(render);

        addGuiPanel('', '', renderEngGui);

        return renderEngParams;
    }

    function createPathLineStyleGui(target) {

        var pathLineStyleGui = new dat.GUI({
            width: 260,
            autoPlace: false,
        });

        var pathLineStyleParams = utils.extend({}, defaultRenderOptions[target]);

        //pathLineStyleGui.add(pathLineStyleParams, 'optionName');

        //pathLineStyleGui.addColor(pathLineStyleParams, 'fillStyle').onChange(render);

        pathLineStyleGui.addColor(pathLineStyleParams, 'strokeStyle').onChange(render);

        pathLineStyleGui.add(pathLineStyleParams, 'lineWidth', 1, 20).step(1).onChange(render);

        if (target !== 'dirArrowStyle') {

            pathLineStyleGui.addColor(pathLineStyleParams, 'borderStyle').onChange(render);

            pathLineStyleGui.add(pathLineStyleParams, 'borderWidth', 1, 20).step(1).onChange(render);

            pathLineStyleGui.add(pathLineStyleParams, 'dirArrowStyle').onChange(render);
        } else {
            pathLineStyleGui.add(pathLineStyleParams, 'stepSpace', 10, 100).step(10).onChange(render);
        }

        addGuiPanel(target, target, pathLineStyleGui);

        return pathLineStyleParams;
    }

    function createKeyPointStyleGui(target) {

        var keyPointStyleGui = new dat.GUI({
            width: 260,
            autoPlace: false,
        });

        var keyPointStyleParams = utils.extend({}, defaultRenderOptions[target]);

        keyPointStyleGui.add(keyPointStyleParams, 'radius', 1, 20).step(1).onChange(render);

        keyPointStyleGui.addColor(keyPointStyleParams, 'fillStyle').onChange(render);

        keyPointStyleGui.addColor(keyPointStyleParams, 'strokeStyle').onChange(render);

        keyPointStyleGui.add(keyPointStyleParams, 'lineWidth', 1, 20).step(1).onChange(render);

        addGuiPanel(target, target, keyPointStyleGui);

        return keyPointStyleParams;
    }


    function addGuiPanel(id, title, gui) {

        var container = document.createElement('div');

        container.id = id;

        if (title) {
            var tEle = document.createElement('h3');
            tEle.innerHTML = title;
            container.appendChild(tEle);
        }

        container.appendChild(gui.domElement);

        customContainer.appendChild(container);
    }

    var pathLineStyleOptions = ['pathLineStyle', 'pathLineHoverStyle', 'pathLineSelectedStyle', 'dirArrowStyle'],
        keyPointStyleOptions = ['startPointStyle', 'endPointStyle', 'keyPointStyle', 'keyPointHoverStyle', 'keyPointOnSelectedPathLineStyle'];

    var renderEngParams = createRenderEngGui(),
        styleParamsMap = {};

    for (var i = 0, len = pathLineStyleOptions.length; i < len; i++) {
        styleParamsMap[pathLineStyleOptions[i]] = createPathLineStyleGui(pathLineStyleOptions[i]);
    }

    for (var i = 0, len = keyPointStyleOptions.length; i < len; i++) {
        styleParamsMap[keyPointStyleOptions[i]] = createKeyPointStyleGui(keyPointStyleOptions[i]);
    }

    function render() {

        pathSimplifierIns.renderEngine.setOptions(renderEngParams);

        for (var k in styleParamsMap) {

            var params = utils.extend({}, styleParamsMap[k]);

            pathSimplifierIns.renderEngine.setOption(k, params);
        }

        pathSimplifierIns.renderLater(200);

        refreshConfigPanel();
    }

    var colorFlds = ['fillStyle', 'strokeStyle', 'borderStyle'],
        rgbAlphaRegx = /([\d\.]+)\s*\)/i;

    function isEmptyColor(color) {

        if (color.indexOf('rgba') !== 0) {
            return false;
        }

        var match = color.match(rgbAlphaRegx);

        if (match && parseFloat(match[1]) < 0.01) {
            return true;
        }

        return false;
    }

    function fixColors(opts) {

        if (utils.isObject(opts)) {

            for (var i = 0, len = colorFlds.length; i < len; i++) {

                if (opts[colorFlds[i]] && isEmptyColor(opts[colorFlds[i]])) {
                    opts[colorFlds[i]] = null;
                }
            }
        }

        return opts;
    }

    function exportRenderOptions() {

        var options = utils.extend({}, renderEngParams);

        for (var k in defaultRenderOptions) {

            var opts = styleParamsMap[k];

            if (opts) {
                options[k] = fixColors(opts);
            }
        }
        return options;
    }

    function refreshConfigPanel() {

        var options = exportRenderOptions();

        var configStr = 'renderOptions: ' + JSON.stringify(options, null, 2);

        $('#exportConfigPanel').find('pre').html(configStr);
    }

    $('#exportBtn').click(function() {

        var panel = $('#exportConfigPanel');

        if (!panel.length) {
            panel = $('<div id="exportConfigPanel"><pre></pre></div>').appendTo(document.body);
            $(this).html('隐藏配置信息');

        } else {
            $(this).html('显示配置信息');
            panel.remove();
            return;
        }
        refreshConfigPanel();
    });

    render();
});
</script>
</body>

</html>