<?php
/**
* Cliche
*
*
* @package cliche
*/
require_once dirname(__FILE__) . '/model/cliche/cliche.class.php';

class IndexManagerController extends modExtraManagerController {
    public static function getDefaultController() { return 'mgr/albums'; }
}

abstract class ClicheManagerController extends modManagerController {
    /** @var Cliche $cliche */
    public $cliche;
    public function initialize() {
        $this->cliche = new Cliche($this->modx);    
		
		$mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);		
        $this->addJavascript($mgrUrl . 'assets/modext/util/lightbox.js');
		
		
        $this->addHtml('<script type="text/javascript">
Ext.onReady(function() {
	MODx.ClicheConnectorUrl = "'.$this->cliche->config['connector_url'].'";
	MODx.ClicheAssetsUrl = "'.$this->cliche->config['assets_url'].'";
	MODx.ClicheAssetsPath = "'.$this->cliche->config['assets_path'].'";
});
</script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('cliche:mgr');
    }
    public function checkPermissions() { return true;}
}