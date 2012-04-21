<?php
/**
 * @package cliche
 * @subpackage plugin
 */
class DefaultPlugin extends ClichePlugin {
    public $columnCount = 0;
    
    /**
     * The current view class has been loaded, set some additionnal paramters for the plugin use
     * @return void
     */
    public function load(){
        $this->view = $this->getProperty('view');
        $this->browse = $this->getProperty('browse');
        if(!$this->browse){
            switch( $this->view ){
                case 'albums':
                    $this->setProperty('itemTpl','albumcoverzoom');
                    break;
                case 'album':
                    $this->setProperty('itemTpl','itemzoom');
                    break;
                default:
                    break;
            }                    
        }    
        $this->setProperties(array(
            'columns' => $this->getProperty('columns', 3),
            'columnBreak' => $this->getProperty('columnBreak','<br style="clear: both;">'),
        ));
        $this->useFancyBox = $this->getProperty('useFancyBox', true);    
        $this->columns = $this->getProperty('columns');
    }
    
    /**
     * The current item has been been processed, do something before going to the next row
     * @param string $row The processec item
     * @return string $row 
     */
    public function afterItemRendered($row){
        $this->columnCount++;
        if($this->columns > 0 && $this->columnCount == $this->columns){
            $row .=  $this->getProperty('columnBreak');
            $this->columnCount = 0;
        }    
        return $row;
    }
    
    /**
     * Set the current item placeholders
     * @param array $phs The current item already set placeholders
     * @param object $obj A reference to the CLicheItems Object
     * @return array An updated array of placeholders
     */
    public function setItemPlaceholder($phs, $obj){
        /* We use the internal phpThumb class to set the custom thumbnail */
        $fileName = str_replace(' ', '_', $obj->get('name'));
        $mask = $fileName .'-'. $phs['width'] .'x'. $phs['height'] .'-zc.png';
        $file = $obj->getCacheDir() . $mask;
        if(!file_exists($file)){
            $original = $this->controller->config['images_path'] . $obj->get('filename');
            $thumb = $obj->loadThumbClass( $original, array(
                'resizeUp' => true,
                'jpegQuality' => 90,
             ));
            $thumb->adaptiveResize($phs['width'], $phs['height']);
            $thumb->save($file, 'png');
        }
        $phs['thumbnail'] = $obj->getCacheDir(false) . $mask;
        
        return $phs;
    }
    
    /**
     * All data have been processed, do the last opeartion before sending the output
     * @return void
     */
    public function render(){
        $this->controller->loadCSS('style');
        /* Load fancybox only if we are viewing a single image and/or we're not in browse mode */
        if($this->useFancyBox && $this->view == 'image' || $this->useFancyBox && !$this->browse){
            
            $this->loadJquery = $this->getProperty('loadJquery', true);
            if($this->loadJquery){
                $this->modx->regClientStartupScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
            }            
            $this->modx->regClientStartupScript($this->controller->config['plugin_assets_url'] . 'fancybox/jquery.fancybox-1.3.4.pack.js');        
            
            $script = $this->getProperty('js', 'script');
            $this->modx->regClientHTMLBlock('<script type="text/javascript">'. $this->controller->getChunk($script) .'</script>');
        }
    }
}