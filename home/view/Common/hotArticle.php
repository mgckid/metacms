<aside class="widget card-shadow widget_lean_posts">
    <h4 class="widget-header h6">最近文章</h4>
    <ul class="list-unstyled">
        <?php foreach($posts as $value):?>
        <li>
            <a href="<?=U('Home/Post/detail',['id'=>$value['post_id']])?>" rel="bookmark" title="<?=$value['title']?>"><?=msubstr($value['title'],0,26)?></a>
        </li>
        <!--./li-->
        <?php endforeach;?>
    </ul>
    <!--./ul-->
</aside>