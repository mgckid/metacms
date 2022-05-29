{extend name='extend/blog'}
{block name='title'}{:category_name()}-{:site_name()}{/block}
{block name='keywords'}{:category_keywords()}{/block}
{block name='description'}{:category_description()}{/block}
{block name="content"}
    <header class="jumbotron bg-white border card-shadow mb-3 py-4 pl-3">
        <h1>{:category_name()}</h1>
    </header>
    {__block__}
{/block}