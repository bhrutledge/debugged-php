<?php

require_once 'Site/Methods.php';

function smarty_modifier_slug($title)
{
    return Site_Methods::slug($title);
}

