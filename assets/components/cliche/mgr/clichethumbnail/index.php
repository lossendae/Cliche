<?php
ob_start ("ob_gzhandler");
header("Content-type: text/javascript; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 ; // Durée en secondes avant expiration du cache
$ExpStr = "Expires: " .
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);

$dir = dirname(__FILE__).'/';

// Include Ext libs
include dirname(dirname(__FILE__)) .'/ux/jquery-1.4.2.min.js';
include dirname(dirname(__FILE__)) .'/ux/jquery.Jcrop.min.js';