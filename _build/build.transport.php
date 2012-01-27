<?php
/**
* Cliche build script
*
* @package Cliche
* @subpackage build
*/
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package */
define('PKG_NAME','Cliche');
define('PKG_NAMESPACE',strtolower(PKG_NAME));
define('PKG_VERSION','1.0.1');
define('PKG_RELEASE','rc1');

function getSnippetContent($path, $name, $debug = false) {
$filename = $path . $name .'.php';
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'debug' => false,
    'root' => $root,
    'files' => $root .'files/',
    'build' => $root .'_build/',
	'data' => $root .'_build/data/',
    'resolvers' => $root . '_build/resolvers/',
    'core' => $root.'core/components/'.PKG_NAMESPACE,
    'snippets' => $root.'core/components/'.PKG_NAMESPACE.'/elements/snippets/',
	'plugins' => $root.'core/components/'.PKG_NAMESPACE.'/elements/plugins/',
    'assets' => $root.'assets/components/'.PKG_NAMESPACE,
	'lexicon' => $root . 'core/components/'.PKG_NAMESPACE.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAMESPACE.'/docs/',
    'model' => $root.'core/components/'.PKG_NAMESPACE.'/model/',
);
unset($root);

require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
echo XPDO_CLI_MODE ? '' : '<pre>';
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAMESPACE,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAMESPACE,false,true,'{core_path}components/'.PKG_NAMESPACE.'/');
$modx->getService('lexicon','modLexicon');
$modx->lexicon->load('cliche:default,mgr');

/* add tv plugin */
$modx->log(modX::LOG_LEVEL_INFO,'Adding in TV Plugin...');
$plugin= $modx->newObject('modPlugin');
$plugin->fromArray(array(
    'id' => 1,
    'name' => 'ClicheThumbnail',
    'description' => 'Resource Thumbnail Manager via Cliche 3rd party component',
    'plugincode' => getSnippetContent($sources['plugins'], 'plugin.clichethumbnail'),
),'',true,true);
$events = include $sources['data'].'events/events.clichethumbnail.php';
if (is_array($events) && !empty($events)) {
    $modx->log(modX::LOG_LEVEL_INFO,'<strong>Added '.count($events).' events to ClicheThumbnail plugin.</strong>');
    $plugin->addMany($events);
}
unset($events);
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'PluginEvents' => array(
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => array('pluginid','event'),
        ),
    ),
);
$vehicle = $builder->createVehicle($plugin, $attributes);
$builder->putVehicle($vehicle);
unset($vehicle,$attributes,$plugin);

/* load action/menu */
$menus = include $sources['data'].'transport.menu.php';
$vehicle= $builder->createVehicle($menu,array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Action' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
        ),
    ),
));
$builder->putVehicle($vehicle);
unset($vehicle,$action);
$modx->log(modX::LOG_LEVEL_INFO,'<strong>Packaged in '.count($menus).' menus.</strong>'); flush();

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in category.'); flush();

/* add snippets */
$modx->log(modX::LOG_LEVEL_INFO,'Adding snippets...');
$snippets = include $sources['data'].'transport.snippets.php';
if (empty($snippets)) $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in snippets.');
$category->addMany($snippets);
$modx->log( xPDO::LOG_LEVEL_INFO, '<strong>Packaged in '. count( $snippets ) .' snippets.</strong>' ); flush();

/* create category vehicle */
$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Children' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'category',
            xPDOTransport::RELATED_OBJECTS => true,
            xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
                'Snippets' => array(
                    xPDOTransport::PRESERVE_KEYS => false,
                    xPDOTransport::UPDATE_OBJECT => true,
                    xPDOTransport::UNIQUE_KEY => 'name',
                ),
            ),
        ),
        'Snippets' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
    ),
);
$vehicle = $builder->createVehicle($category,$attr);

$modx->log(modX::LOG_LEVEL_INFO, 'Adding file resolvers to category...');

$vehicle->resolve('file',array(
    'source' => $sources['core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));

$vehicle->resolve('file',array(
    'source' => $sources['assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));

$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'resolve.tables.php',
));

// $vehicle->resolve('php',array(
    // 'source' => $sources['resolvers'].'resolve.options.php',
// ));

$modx->log(modX::LOG_LEVEL_INFO,'Packaged in resolvers.'); flush();
$builder->putVehicle($vehicle);

/* Pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    // 'setup-options' => array(
        // 'source' => $sources['build'].'setup.options.php',
    // ),
));
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in package attributes.'); flush();

$modx->log(modX::LOG_LEVEL_INFO,'Packing...'); flush();
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();