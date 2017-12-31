<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title><?=$siteInfo['title']?></title>
    <meta name="keywords" content="<?=$siteInfo['keywords']?>">
    <meta name="description" content="<?=$siteInfo['description']?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='stylesheet' id='lean-toolkit-css'  href='/static/writing/css/toolkit.css' type='text/css' media='all' />
    <link rel='stylesheet' id='lean-font-awesome-css'  href='/static/writing/css/font-awesome.min.css' type='text/css' media='all' />
    <link rel="shortcut icon"  href="/favicon.ico">
    <style type="text/css" id="custom-background-css">
        body.custom-background { background-color: #f8f9fa; }
    </style>
    <?php if(ENVIRONMENT=='product'):?>
        <script type='text/javascript' src='https://cdn.bootcss.com/jquery/3.2.0/jquery.min.js'></script>
        <script type='text/javascript' src='https://cdn.bootcss.com/bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
    <?php else:?>
        <script type='text/javascript' src='/static/writing/js/jquery.js'></script>
        <script type='text/javascript' src='/static/writing/js/bootstrap.min.js'></script>
    <?php endif;?>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?898efc58954751f91e409e4bf32c2b45";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>