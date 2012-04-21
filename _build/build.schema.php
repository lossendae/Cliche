<?php
/**
* Build Schema script
*
* @package cliche
* @subpackage build
*/
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);
 
require_once dirname(__FILE__) . '/build.config.php';
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->loadClass('transport.modPackageBuilder','',false, true);
echo '<pre>';
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
 
$root = dirname(dirname(__FILE__)).'/';
$rootCore = dirname(dirname(dirname(__FILE__))).'/';
$sources = array(
    'root' => $root,
    'core' => $root.'core/components/cliche/',
    'schema' => $root.'_build/schema/',
    'model' => $rootCore.'core/components/cliche/model/',
);

$manager= $modx->getManager();
$generator= $manager->getGenerator();
$generator->classTemplate= <<<EOD
<?php
/**
* [+phpdoc-package+]
* [+phpdoc-subpackage+]
*/
class [+class+] extends [+extends+] {}
?>
EOD;
$generator->platformTemplate= <<<EOD
<?php
/**
* [+phpdoc-package+]
* [+phpdoc-subpackage+]
*/
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\\\', '/') . '/[+class-lowercase+].class.php');
class [+class+]_[+platform+] extends [+class+] {}
?>
EOD;
$generator->mapHeader= <<<EOD
<?php
/**
* [+phpdoc-package+]
* [+phpdoc-subpackage+]
*/
EOD;

$generator->parseSchema($sources['schema'].'cliche.mysql.schema.xml', $sources['model']);

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
?>