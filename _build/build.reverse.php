<?php
/**
* Build Schema script
*
* @package remark
* @subpackage build
*/
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
$modx->initialize('mgr');
// $modx->loadClass('transport.modPackageBuilder','',false, true);
echo '<pre>';
$modx->setLogLevel(MODX_LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');


$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/',
    'schema' => $root.'_build/schema/',
);

// $xpdo= new xPDO('mysql:host=localhost;dbname=psmgal','root','');
// error_reporting(E_ALL); ini_set('display_errors',true);
// $xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
// $xpdo->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

// Set the package name and root path of that package
// $xpdo->setPackage('modx', XPDO_CORE_PATH . '../model/');

// $xpdo->setDebug(true);

$manager= $modx->getManager();
$generator= $manager->getGenerator();

//Use this to create a schema from an existing database
$generator->writeSchema($sources['schema'].'cliche.mysql.reverse.schema.xml', 'cliche', 'xPDOObject', 'modx_cliche_', true);

//Use this to generate classes and maps from your schema
// NOTE: by default, only maps are overwritten; delete class files if you want to regenerate classes
//$generator->parseSchema(XPDO_CORE_PATH . '../model/schema/modx.mysql.schema.xml', XPDO_CORE_PATH . '../model/');

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
?>