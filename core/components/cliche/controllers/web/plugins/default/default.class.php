<?php
/**
 * @package cliche
 * @subpackage plugin
 */
class DefaultPlugin extends ClichePlugin {
    public $columnCount = 0;
    
    /**
     * The current view class has been loaded, set some additionnal paramters for the plugin use
     */
    public function load(){
        $this->view = $this->getProperty('view');
        $this->browse = $this->getProperty('browse', false);        
        $this->setProperties(array(
            'columns' => $this->getProperty('columns', 3),
            'columnBreak' => $this->getProperty('columnBreak','<br style="clear: both;">'),
        ));
        $this->useFancyBox = $this->getProperty('useFancyBox', true);
        $this->zoomAlbumItem = $this->getProperty('zoomAlbumItem', true);    
        $itemTpl = $this->getProperty('itemTpl');  
        if(!$this->browse && $this->useFancyBox && $itemTpl == "item"){
            switch( $this->view ){
                case 'albums':
                    $itemTpl = 'albumcoverzoom';
                    break;
                case 'album': 
                   if($this->zoomAlbumItem) $itemTpl = 'itemzoom';
                    break;
                default:
                    break;
            } 
            $this->setProperty('itemTpl', $itemTpl);
        }        
        $this->columns = $this->getProperty('columns');
    }
    
    /**
     * The main query event before being executed.
     * 
     * @param object $query The xPDO object passed along
     * @param string $objName The main object queried
     * @return object $query The modified query object
     */
    public function beforeQuery($query, $objName){
        /* Those informations should be query aware */
        $sortByColumn = $this->getProperty('sortByColumn', 'id');
        $sortByDir = $this->getProperty('sortByDir', 'ASC');
        $query->sortBy($objName .'.'. $sortByColumn, $sortByDir);
        
        /* Set a placeholder for total pics info */
        $total = $this->modx->getCount($objName, $query);
        $ph = $this->getProperty('totalPicsPlaceholder', 'cliche.total_pics');
        $this->modx->setPlaceholder($ph, $total);
        
        /* Do we wan't to paginate result ? */
        $paginate = $this->getProperty('paginate', false);
        if( $paginate ){
            $pageVar = $this->getProperty('pageVar', 'page');
            $request = $this->modx->request->getParameters();
            $page = isset($request[$pageVar]) ? $request[$pageVar] : 1;
            $start = ceil($paginate * $page  + 1) - $paginate;
            $query->limit($paginate, $start);
            
            /* Prepare pagiantion */
            $lastPage = ceil($total / $paginate);
            $prevLink = $this->getPaginationLink($pageVar, $page, $this->getProperty('prevLinkText', '<< prev'));
            $nextLink = $this->getPaginationLink($pageVar, $page, $this->getProperty('nextLinkText', 'next >>'), 'next', $lastPage);
            
            /* Simple pagination */
            $phs = array(
                $this->getProperty('totalPagePlaceholder', 'cliche.current_page') => $page,
                $this->getProperty('nextLinkPlaceholder', 'cliche.page_link_next') => $nextLink,
                $this->getProperty('prevLinkPlaceholder', 'cliche.page_link_prev') => $prevLink,
                $this->getProperty('lastPagePlaceholder', 'cliche.last_page') => $lastPage,
            );            
            $this->controller->setPlaceholders($phs);
        }
        
        return $query;
    }
    
    
    /**
     * Get a simple link for pagination
     * 
     * @param string $pageVar The query string variable to use for the link
     * @param integer $page The current page number
     * @param string $text The text of the link
     * @param string $action The action to link to (prev or next page)
     * @param integer $lastPage The last page number
     * @return string $link The page link or an empty string
     */
    public function getPaginationLink($pageVar, $page, $text, $action = 'prev', $lastPage){        
        $showParams = true;
        if($action == 'next'){   
            $page = $page + 1;
            if($page > $lastPage) return ''; 
        } else {   
            if($page == 1) return '';
            $page = $page - 1;
            if($page == 1) $showParams = false;            
        }
        $params = $showParams ? array( $pageVar => $page ) : array();
        
        $url = $this->modx->makeUrl( $this->modx->resource->get('id'),'',$params); 
        $link = "<a href=\"{$url}\">{$text}</a>";
        return $link;
    }
    
    /**
     * The current item has been been processed, do something before going to the next row
     * 
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
     * 
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
     *
     * @return void
     */
    public function render(){
        $loadCss = $this->getProperty('loadJquery', true);
        if($loadCss){
             $css = $this->getProperty('css', 'style');
            $this->controller->loadCSS($css);
        }
        /* Load fancybox only if we are viewing a single image and/or we're not in browse mode */
        if( $this->useFancyBox && $this->view == 'image' || 
            $this->useFancyBox && !$this->browse || 
            $this->view == 'album' && $this->useFancyBox && $this->zoomAlbumItem ){
            
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