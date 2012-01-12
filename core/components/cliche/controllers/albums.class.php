<?php
/**
* Cliche
*
*
* @package cliche
* @subpackage controllers
*/
class ClicheAlbumsManagerController extends ClicheManagerController {

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
		
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/manage/albums/list.js');
        $this->addJavascript($this->cliche->config['assets_url'].'mgr/manage/main.panel.js');
		
		$this->addHtml('<script type="text/javascript">
Ext.onReady(function() {
	MODx.add("cliche-main-panel");	
	Ext.ux.Lightbox.register("a.lightbox");
});
</script>');

		$panels = array();
		
		$this->addJavascript($this->cliche->config['assets_url'].'mgr/manage/album/view.js');
		
		$panels[] = 'cliche-album-default';
		
		$this->addJavascript($this->cliche->config['assets_url'].'mgr/manage/actions/upload.js');

		$this->addJavascript($this->cliche->config['assets_url'].'mgr/libs/plupload.js');
		$this->addJavascript($this->cliche->config['assets_url'].'mgr/libs/plupload.html5.js');
		// $this->addJavascript($this->cliche->config['assets_url'].'mgr/libs/plupload.flash.js');
		$this->addJavascript($this->cliche->config['assets_url'].'mgr/libs/plupload.html4.js');
		
		$panels[] = 'cliche-item-default-upload-panel';
		
		//Will need some refactoring to allow dynamic custom panel loading
		$this->addHtml('<script type="text/javascript">function getPanels(){ return '.$this->modx->toJSON($panels).'; }</script>');
    }
    public function getTemplateFile() { return ''; }
}