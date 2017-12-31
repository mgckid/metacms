<aside class="widget card-shadow d_tag"><h4 class="widget-header h6">热门标签</h4>
    <div class="hot-tags">
        <?php foreach($tag_list as $value):?>
            <a href="<?=U('Home/Post/tags',['tag_name'=>$value])?>" class="btn btn-light btn-sm"><?=$value?></a>
        <?php endforeach?>
    </div>
</aside>