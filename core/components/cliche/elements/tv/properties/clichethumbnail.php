<?php
/**
 * Renders the properties form
 *
 * @package cliche
 */
$settings = array();
if (!empty($scriptProperties['tv'])) {
    $tv = $modx->getObject('modTemplateVar',$scriptProperties['tv']);
    if ($tv != null) {
        $settings = $tv->get('output_properties');
    }
    $modx->smarty->assign('tv',$scriptProperties['tv']);
}
$modx->smarty->assign('params',$modx->toJSON($settings));

$corePath = $modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/');
return $modx->smarty->fetch($corePath.'elements/tv/clichethumbnail.properties.tpl');