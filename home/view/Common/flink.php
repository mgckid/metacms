<aside class="widget card-shadow widget_nav_menu"><h4 class="widget-header h6">友情链接</h4>
    <div>
        <ul class="menu">
            <?php foreach($list as $value):?>
            <li><a href="<?=$value['furl']?>" title="<?=$value['fdesc']?>"><?=$value['fname']?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
</aside>