{extend name='extend/blog'}
{block name='title'}{$title}-{:site_name()}{/block}
{block name='keywords'}{$keywords}{/block}
{block name='description'}{$description}{/block}
{block name='content'}
    <div class="card card-shadow">
        <div class="card-body">
            <h1 class="card-title mb-4">{$title}</h1>
            <p class="card-text text-link-color-muted">
                <small>
                    <span class="post-time">{:date('Y年m月d日',strtotime($created))}</span>
                    <span class="post-category"> &nbsp;•&nbsp;
                        <a href="{:category_url($category_id)}" rel="category tag">{:category_name($category_id)}</a>
                    </span>
                    <span class="post-category"> &nbsp;•&nbsp;{$author}</span>
                </small>
            </p>

            <div class="entry-content pt-3">
                {$content|htmlspecialchars_decode||raw}
                <div class="post-tags mt-4 mb-3">
                    <?php foreach($post_tag as $value):?>
                        <a  href="<?=url('home/tag/tagpostlist',['tag_name'=>$value])?>" class="btn btn-light btn-sm mr-2 mb-2"><?=$value?></a>
                    <?php endforeach?>
                </div>
            </div>

        </div>
    </div>
    <nav class="post-navigation card" role="navigation">
    <div class="card-body">
        <h4 class="sr-only sr-only-focusable">Post navigation</h4>
        <div class="nav-links clearfix">
            <div class="nav-previous float-left">
                &larr;
                {notempty name=":pre_title($post_id)"}
                <a href=" {:pre_url($post_id)}" rel="prev">{:pre_title($post_id)}</a>
                {else /}
                <a href="javascript:void(0)" rel="prev">无</a>
                {/notempty}
            </div>
            <div class="nav-next float-right">
                {notempty name=":next_title($post_id)"}
                <a href=" {:next_url($post_id)}" rel="next">{:next_title($post_id)}</a>
                {else /}
                <a href="javascript:void(0)" rel="next">无</a>
                {/notempty}
                &rarr;
            </div>

        </div><!-- .nav-links -->

    </div>
</nav><!-- .navigation -->

<div class="related-posts card">
    <div class="card-body"><h3 class="card-title h6 mb-3">你可能喜欢：</h3>
        <div class="row">
            {foreach :getrelated($post_id,6) as $value}
            <div class="col-md-4 col-6">
                <div class="card border-0">
                    <a class="entry-img" href="{:url('Post/index',['post_id'=>$value['post_id']])}">
                        <img src="{$value['main_image']|getImage}" alt="{$value['title']}" class="card-img rounded-0">
                    </a>
                    <div class="card-body px-0 py-3">
                        <p class="card-title text-link-color line-clamp-2 text-overflow-ellipsis">
                            <a href="{:url('Post/index',['post_id'=>$value['post_id']])}" rel="bookmark">{$value['title']}</a>
                        </p>
                    </div>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
<!-- .related-posts -->
{/block}