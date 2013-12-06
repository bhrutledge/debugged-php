<?php

/**
 * Return a link if provided a URL, or just the text
 */
function smarty_function_html_link($params, &$smarty)
{
    $url = isset($params['url']) ? $params['url'] : '';
    $text = isset($params['text']) ? $params['text'] : $url;
    $title = isset($params['title']) ? $params['title'] : '';

    $link = $text;
    if ($url) {
        $link = ($title) ? "<a href=\"$url\" title=\"$title\">$text</a>" :
                           "<a href=\"$url\">$text</a>";
    }
    
    return $link
}

