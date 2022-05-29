{extend name='category/article_list'}
{block name="content"}
<div class="card">
    <h1 class="card-header bg-white py-4">{:category_name()}</h1>
    <div class="card-body">
        <div class="entry-content">
            {:category_content()}
        </div>
    </div>
</div>
{/block}
