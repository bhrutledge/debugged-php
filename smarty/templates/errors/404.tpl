{assign var='error' value='404'}

{assign var='title' value='Not Found'}

{capture assign="content"}
<p>The URL you requested, <code>{$url}</code>, was not found.</p>
{/capture}

{include file="layouts/error.tpl"}
