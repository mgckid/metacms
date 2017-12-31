<!DOCTYPE html>
<html lang="zh-CN">
<?=$this->insert('Common/head')?>
<body class="post-template-default single single-post postid-380 single-format-standard custom-background" >
<style>
    pre {
        color:#444;
        margin:15px auto;
        padding:20px 15px;
        border:3px dashed #ddd;
        border-left:8px solid #bbb;
        background:#fff url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAAmCAIAAACphTeFAAAAJElEQVRIx2N4RS5gGNU5qnNU56hOZJ2fyQWjOkd1juoc1YkMAKDL4lkok3p7AAAAAElFTkSuQmCC') repeat;
        white-space:pre-wrap;
        word-wrap:break-word;
        letter-spacing:1.5px;
        font:14px/25px 'Comic Sans MS','courier new';
        line-height:22px;
        background-size:100% 44px
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
