<?php

require_once 'Site/Methods.php';
require_once 'Site/Map.php';

$root_map = Site_Methods::loadYamlData('sitemap');
$root_handler = new Site_MapHandler_Array($root_map);
Site_Map::instance()->register_handler('', $root_handler);

