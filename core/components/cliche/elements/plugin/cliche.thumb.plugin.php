<?php
/**
 * Handles plugin events for Cliche's Custom Thumbnail Manager TV
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
		
		 /* assign cliche lang to JS */
        $modx->response->addLangTopic('cliche:default');
		
		$modx->regClientStartupHTMLBlock('<script type="text/javascript">
        Ext.onReady(function() {
            MODx.ClicheConnectorUrl = "'.$cliche->config['connector_url'].'";
        });
        </script>');
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/core/');
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/thumb/');		
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/thumb/tv.panel.js');			
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/thumb/card.main.js');				
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/thumb/card.albumlist.js');			
		$modx->regClientStartupScript($cliche->config['assets_url'].'app/thumb/card.albumview.js');			
        break;
}
return;