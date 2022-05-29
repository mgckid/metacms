{extend name='extend/blog'}
{block name='title'}全部标签_{__block__}{/block}
{block name='content'}
<div class="card">
    <h1 class="card-header bg-white py-4">全部标签</h1>
    <div class="card-body">
        <div class="entry-content">
            <div class="hot-tags">
                {foreach :post_tag(0) as $value}
                <a href="{:url('tag/post',['post_tag'=>$value])}" class="btn btn-light btn-sm">{$value}</a>
                {/foreach}
            </div>
        </div>
    </div>
</div>
{/block}