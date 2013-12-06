<?php

require_once 'Site/Map.php';

function smarty_function_sitemap($params, &$smarty)
{
    $root = isset($params['root']) ? $params['root'] : '';
    
    $attributes = "";
    foreach (array('id', 'class') as $attribute) {
        $value = $params[$attribute];
        if ($value) {
            $attributes .= " $attribute=\"$value\"";
        }
    }
    
    return Site_Map::instance()->to_html(array($root), $attributes);
}
