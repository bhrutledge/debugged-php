{capture assign="page_content"}
<p>You are on the home page. It is {$smarty.now|date_format}. There is also a {html_site_link url='sample'} page.</p>
{/capture}

{include file="layouts/page.tpl"}