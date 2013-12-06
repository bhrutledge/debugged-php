<?php

require_once 'Site/Methods.php';

function smarty_modifier_http_url($url)
{
    return Site_Methods::url($url, true);
}

