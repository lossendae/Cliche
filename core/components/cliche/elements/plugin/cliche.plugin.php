<?php
/**
 * Handles plugin events for Cliche's Custom TV
 * 
 * @package cliche
 */
$cliche = $modx->getService('cliche','Cliche',$modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/').'model/cliche/',$scriptProperties);
if (!($cliche instanceof Cliche)) return '';

$corePath = $cliche->config['core_path'];
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
        break;
	case 'OnTVOutputRenderList':
        $modx->event->output($corePath.'elements/tv/output/');
        break;
	case 'OnTVOutputRenderPropertiesList':
        $modx->event->output($corePath.'elements/tv/properties/');
        break;
    case 'OnDocFormPrerender':	
		$modx->regClientCSS($cliche->config['css_url'].'thumb.php');
		
		$modx->regClientStartupHTMLBlock('<script type="text/javascript">
        Ext.onReady(function() {
            MODx.ClicheConnectorUrl = "'.$cliche->config['connector_url'].'";
        });
        </script>');
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/core/');
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/thumb/');		
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/thumb/tv.panel.js');			
        break;
}
return;