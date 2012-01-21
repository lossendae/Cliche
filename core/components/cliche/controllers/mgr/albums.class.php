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
		
		$this->loadPanels();
		$this->addHtml('<script type="text/javascript">Ext.onReady(function() {	
			Ext.ns("Cliche"); Cliche.getPanels = function(){ return '. $this->loadPanels() .'; }(); 
			MODx.add("cliche-main-panel"); Ext.ux.Lightbox.register("a.lightbox"); 
});</script>');
    }
    public function getTemplateFile() { return ''; }
	
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
	
	public function loadPanels(){
		return $this->modx->toJSON($this->panels);
	}
}