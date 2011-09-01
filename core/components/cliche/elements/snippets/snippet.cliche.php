<?php
/**
 * Snippet Cliche
 *
 * @package cliche
 */
/**
 * Cliche
 *
 * A Gallery manager components for MODx Revolution
 *
 * @author Stephane Boulard <lossendae@gmail.com>
 * @package cliche
 */
$Cliche = $modx->getService('cliche','Cliche',$modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/').'model/cliche/',$scriptProperties);
if (!($Cliche instanceof Cliche)) return 'Cliche could not be loaded';

$view = $modx->getOption('view', $_REQUEST, $modx->getOption('view', $scriptProperties, null));
$scriptProperties['browse'] = true;

switch($view){
	case 'item':
		$controller = 'Item';
		break;
	case 'set':
		$controller = 'Items';
		break;
	default:
		$controller = 'Albums';
		break;
}
$controller = $Cliche->loadController($controller);
$output = $controller->run($scriptProperties);
return $output;