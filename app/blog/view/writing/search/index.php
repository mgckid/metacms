{extend name='extend/blog'}
{block name='content'}
<h1 class="page-title mb-3">“<span>{:htmlspecialchars(input('request.keyword',''))}</span>”的搜索结果：</h1>
{__block__}
{/block}