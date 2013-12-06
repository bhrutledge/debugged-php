{assign var='error' value='unknown'}

{assign var='title' value='Unknown Error'}

{capture assign="content"}
<p>An unknown error occurred{if $url} while accessing <code>{$url}</code>{/if}.</p>
{/capture}

{include file="layouts/error.tpl"}