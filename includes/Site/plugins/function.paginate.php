<?php

function smarty_function_paginate($params, &$smarty)
{
    $var = $params['var'];
    $count = $params['count'];
    
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    
    $content = '';

    if ($page == 'all') {
        for ($i=1; $i <= $count; $i++) {
            $content .= $smarty->getTemplateVars("{$var}_{$i}");
        }
    }
    else {
        $page = (int) $page;
        if ((0 < $page) && ($page <= $count)) {
            $content = $smarty->getTemplateVars("{$var}_{$page}");
        }
    }
    
    $page_nums = range(1, $count);
    $page_nums[] = 'all';
    $smarty->assign('page', $page);
    $smarty->assign('page_nums', $page_nums);
    
    $content .= $smarty->fetch('paginate.tpl');
    $smarty->assign($var, $content);
}
