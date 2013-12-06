<?php

require_once 'includes/config.php';
require_once 'includes/setup.php';
require_once 'Site/Smarty_Page_Builder.php';

$builder = new Smarty_Page_Builder();
$smarty = $builder->build();

// TODO: Add caching.  Needs $smarty->fetch_page()
$page_url = isset($_GET['page_url']) ? $_GET['page_url'] : '';
if (isset($page_url)) {            
    try {
        $smarty->assign_page($page_url);
        $smarty->display_page();
    }
    catch (Exception $e) {
        $smarty->display_error('404');
    }
}
else {
    $smarty->display_error('unknown');
}
