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
        $modx->controller->addCss($cliche->config['css_url'].'index.css');
        $modx->controller->addCss($cliche->config['css_url'].'clichethumbnail.css');
        
         /* assign cliche lang to JS */
        $modx->controller->addLexiconTopic('cliche:mgr');
        $modx->controller->addLexiconTopic('cliche:clichethumbnail');
        
        $modx->controller->addHtml('<script type="text/javascript">
Ext.onReady(function() {
    MODx.ClicheConnectorUrl = "'.$cliche->config['connector_url'].'";
    MODx.ClicheAssetsUrl = "'.$cliche->config['assets_url'].'";
    MODx.ClicheAssetsPath = "'.$cliche->config['assets_path'].'";
    Ext.ux.Lightbox.register("a.lightbox");
});
</script>');
        /* Lightbox */
        $mgrUrl = $modx->getOption('manager_url',null,MODX_MANAGER_URL);        
        $modx->controller->addJavascript($mgrUrl . 'assets/modext/util/lightbox.js');
            
        /* App base definitions + libs */
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/libs/jquery.1.4.min.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/libs/jquery.Jcrop.min.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/libs/plupload.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/libs/plupload.html5.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/libs/plupload.html4.js');
        
        /* Core base class */
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/core/album.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/core/upload.js');
            
        /* TV panel classes */
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/thumbnail/tv/panel.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/thumbnail/tv/window.js');
        
        /* Window cards */
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/thumbnail/tv/cards/main.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/thumbnail/tv/cards/album.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/thumbnail/tv/cards/upload.js');
        $modx->controller->addJavascript($cliche->config['assets_url'].'mgr/thumbnail/tv/cards/cropper.js');
        break;
    case 'OnTemplateVarSave':
        $type = $templateVar->get('type');
        if($type == 'clichethumbnail'){
            $name = $templateVar->get('name');
            $id = $templateVar->get('id');
            $properties = $templateVar->getProperties();
            
            /* Get the existing album */            
            if($mode == 'upd' && isset($properties['clichealbum'])){                    
                $album = $modx->getObject('ClicheAlbums', $properties['clichealbum']);                        
            }     
            /* Prevents album duplication */
            if($mode == 'upd' && !$album){                                    
                $album = $modx->getObject('ClicheAlbums', array('description'=> 'Cliche Thumbnail TV - #' . $id));    
                if($album){
                    $properties['clichealbum'] = $album->get('id');
                    $templateVar->setProperties(array('clichealbum' => $properties['clichealbum']), true);
                    $templateVar->save();
                }
            }         
            
            /* Else create a new Cliche album */
            if(!$album){
                $album = $modx->newObject('ClicheAlbums');
                $album->set('type', 'clichethumbnail');
                $album->set('description', 'Cliche Thumbnail TV - #' . $id);
            }
            
            /* We have an album */
            if($album){                    
                $album->set('name', $name);
                $album->save();                
                if(!isset($properties['clichealbum'])){
                    $aid = $album->get('id');
                    $templateVar->setProperties(array('clichealbum' => $aid), true);
                    $templateVar->save();
                }                
            }
            
            unset($album);
        }
        break;
    case 'OnTemplateVarBeforeRemove':
        $type = $templateVar->get('type');
        if($type == 'clichethumbnail'){
            $name = $templateVar->get('name');
            $properties = $templateVar->getProperties();
            if(isset($properties['clichealbum'])){
                $album = $modx->getObject('ClicheAlbums', $properties['clichealbum']);    
                if($album){
                    $album->fromArray(array(
                        'name' => $name,
                        'type' => 'default',
                        'description' => 'This was a ClicheThumbnail dedicated album whose TV has been removed',
                    ));
                    $album->save();
                }
            }
        }
        break;
    default:break;
}
return;