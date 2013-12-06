<?php

require_once 'Site/Map.php';

function smarty_function_html_site_link($params, &$smarty)
{
    $current_url = $smarty->getTemplateVars('url');

    $url = $params['url'];
    $item = Site_Map::instance()->get_item($url);
    $text = isset($params['text']) ? $params['text'] : $item->title;
    $title = isset($params['title']) ? $params['title'] : '';

    if (($item->grouping and in_array($current_url, $item->pages)) or
        ($current_url == $url)) {
        return "<span>$text</span>";
    }
    
    return ($title) ? "<a href=\"$url\" title=\"$title\">$text</a>" :
                      "<a href=\"$url\">$text</a>";
}
