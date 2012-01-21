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
$scriptProperties['plugin'] = $modx->getOption('plugin', $scriptProperties, 'default');
$scriptProperties['view'] = strtolower($controllerName);

$controller = $Cliche->loadController('Album');
$output = $controller->run($scriptProperties);
return $output;