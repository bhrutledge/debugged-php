<?php

require_once 'includes/config.php';
require_once 'includes/setup.php';
require_once 'Site/Smarty_Page_Builder.php';

$status = isset($_SERVER['REDIRECT_STATUS']) ? $_SERVER['REDIRECT_STATUS'] : 
                                               'unknown';
$url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '';

$builder = new Smarty_Page_Builder();
$smarty = $builder->build();
$smarty->display_error($status, $url);
