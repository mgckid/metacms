{extend name='extend/blog'}
{block name="content"}
    <header class="jumbotron bg-white border card-shadow mb-3 py-4 pl-3">
        <h1>"{:input('request.post_tag')}"标签下的内容</h1>
    </header>
    {__block__}
{/block}