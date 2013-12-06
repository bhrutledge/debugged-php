{assign var='error' value='403'}

{assign var='title' value='Forbidden'}

{capture assign="content"}
<p>You don't have permission to access <code>{$url}</code>.</p>
{/capture}

{include file="layouts/error.tpl"}