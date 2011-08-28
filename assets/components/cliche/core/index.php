<?php
ob_start ();
header("Content-type: text/javascript; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 ; // Durée en secondes avant expiration du cache
$ExpStr = "Expires: " .
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);

$dir = dirname(__FILE__).'/';

// Include Ext libs
include $dir.'ux/Ext.ux.lightbox.js';
// include $dir.'ux/fileuploader.js';
include $dir.'ux/Ext.form.fileuploadfield.js';
include $dir.'ux/Ext.form.multifileuploadfield.js';
include $dir.'ux/Ext.ux.xhrupload.js';

//Core apps
include $dir.'panel.js';
include $dir.'form.js';
include $dir.'console.js';
include $dir.'window.js';
include $dir.'grid.js';
include $dir.'tree.js';
include $dir.'tab.js';
include $dir.'view.js';
include $dir.'maincontainers.js';
