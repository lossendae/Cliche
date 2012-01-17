<?php
/**
* Cliche
*
*
* @package cliche
* @subpackage controllers
*/
class ClicheMgrAlbumsManagerController extends ClicheManagerController {

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
		$this->addJavascript($this->cliche->config['assets_url'].'mgr/libs/plupload.html4.js');
		$this->addJavascript($this->cliche->config['assets_url'].'mgr/libs/DataViewTransition.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/manage/albums/list.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/manage/main.panel.js');
		
		$this->addHtml('<script type="text/javascript">Ext.onReady(function() {	MODx.add("cliche-main-panel"); Ext.ux.Lightbox.register("a.lightbox"); });</script>');

		$panels = array();	
		
		/* Load each types controller separately */
		$c = $this->modx->newQuery('ClicheAlbums');
		$c->select(array('type'));
		$c->query['distinct'] = 'DISTINCT';
		if ($c->prepare() && $c->stmt->execute()) {
			$results = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($results as $result){
				$albumType = $result['type'];
				$f = $this->cliche->config['controllers_path'].'mgr/cmp/'. $albumType .'.inc.php';
				if(file_exists($f)){
					// @TODO use the dynamic feature
					require_once $this->cliche->config['controllers_path'].'mgr/cmp/default.inc.php';
				}
			}
		}
    }
    public function getTemplateFile() { return ''; }
}