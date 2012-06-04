<?php
/**
* Cliche
*
*
* @package cliche
* @subpackage controllers
*/
class ClicheMgrAlbumsManagerController extends ClicheManagerController {
    public $type;
    public $panels = array();

    public function process(array $scriptProperties = array()) {}
    public function getPageTitle() { return $this->modx->lexicon('cliche'); }
    public function loadCustomCssJs() {
        /* Load CSS here to avoid style unwanted override  */
        $theme = $this->modx->getOption('manager_theme');
        if($theme == 'trendy'){
            $this->addCss($this->cliche->config['css_url'].'trendy.css');
        } else {
            $this->addCss($this->cliche->config['css_url'].'index.css');
        }
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/libs/plupload.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/libs/plupload.html5.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/core/windows.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/core/albums.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/core/album.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/core/upload.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/core/main.panel.js');    
        
        $this->type = 'default';
        $this->addPanel('album');
        $this->addPanel('upload');
        
        /* Load each types controller separately */
        $c = $this->modx->newQuery('ClicheAlbums');
        $c->select(array('type'));
        $c->query['distinct'] = 'DISTINCT';
        $c->where(array(
            '`type` != "default"'
        ));
        if ($c->prepare() && $c->stmt->execute()) {
            $results = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $result){
                $this->type = $result['type'];
                $f = $this->cliche->config['controllers_path'].'mgr/cmp/'. $this->type .'.inc.php';
                if(file_exists($f)){
                    require_once $f;
                }
            }
        }
        $allowedExtensions = explode(',', 'jpg,jpeg,gif,png,zip');
        foreach($allowedExtensions as $key => $value){
            $allowedExtensions[$value] = 1;
            unset($allowedExtensions[$key]);
        }
        
        
        $this->loadPanels();
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() {    
            Ext.ns("Cliche"); Cliche.getPanels = function(){ return '. $this->loadPanels() .'; }(); 
            Cliche.config = '. $this->modx->toJSON($this->cliche->config) .';
            Cliche.allowedExtensions = '. $this->modx->toJSON($allowedExtensions) .';
            Cliche.postMaxSize = '. $this->cliche->_toBytes(ini_get('post_max_size')) .';
            Cliche.uploadMaxFilesize = '. $this->cliche->_toBytes(ini_get('upload_max_filesize')) .';
            MODx.add("cliche-main-panel"); Ext.ux.Lightbox.register("a.lightbox"); 
});</script>');
    }
    
    public function getTemplateFile() { return ''; }
    
    /**
     * Helper method to add xtypes to cliche CMP.
     * 
     * @param string $xtype
     */
    public function addPanel($xtype){
        switch($xtype){
            case 'album':
                $xtype = 'cliche-album-panel';
                break;
            case 'upload':
                $xtype = 'cliche-upload-panel';
                break;
            default:break;
        }
        $this->panels[] = array(
            'xtype' => $xtype,
            'uid' => $this->type,
        );
    }
    
    /**
     * load all registred panels
     * 
     * @return string The json string containing all panels
     */
    public function loadPanels(){
        return $this->modx->toJSON($this->panels);
    }
}