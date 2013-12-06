<?php

require_once 'Site/Methods.php';

function smarty_modifier_version($file)
{
    $localFile = Site_Methods::localPath($file);
    if (!file_exists($localFile)) {
        return $file;
    }

    $mtime = filemtime($localFile);
    return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}