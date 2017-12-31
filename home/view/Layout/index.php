<!DOCTYPE html>
<html lang="zh-CN">
<?=$this->insert('Common/head')?>
<body class="home blog custom-background" >
<style type="text/css">
    .pagination li.active a {
        z-index: 2;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .pagination li a {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #ddd;
    }
</style>
<?= $this->insert('Common/nav') ?>
<!--主体内容 开始-->
<?= $this->section('content') ?>
<!--主体内容 结束-->
<!--复用的底部 -->
<?= $this->insert('Common/footer') ?>
</body>
</html>
