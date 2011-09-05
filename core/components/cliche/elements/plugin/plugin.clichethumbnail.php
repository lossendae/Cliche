<?php
/**
 * Handles plugin events for Cliche's Custom Thumbnail TV
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
		$modx->regClientCSS($cliche->config['css_url'].'clichethumbnail.php');
		
		 /* assign cliche lang to JS */
        $modx->response->addLangTopic('cliche:mgr,default');
		
		$modx->regClientStartupHTMLBlock('<script type="text/javascript">
        Ext.onReady(function() {
            MODx.ClicheConnectorUrl = "'.$cliche->config['connector_url'].'";
        });
        </script>');
            
		/* App base definitions + libs */
		$modx->regClientStartupScript($cliche->config['assets_url'].'core/');
		$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/lib/jquery-1.4.2.min.js');
		$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/lib/jquery.Jcrop.min.js');
            
        /* TV main panel */
		$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/main.panel.js');

        /* Window */
		$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/thumb/window.js');
		$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/thumb/cropper.js');
		$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/thumb/uploader.js');
		$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/thumb/album.js');

        /* @TODO / Browse other albums to copy existing image from them to thumbnail album */
//        $modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/browse/albums.js');
//        $modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/browse/album.js');
//        $modx->regClientStartupScript($cliche->config['assets_url'].'mgr/clichethumbnail/browse/picture.js');
        break;
}
return;