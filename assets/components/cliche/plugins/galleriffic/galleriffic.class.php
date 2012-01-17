<?php
/**
 * @package cliche
 * @subpackage plugin
 */
class Galleriffic extends ClichePlugin {
	public function load(){
		$this->controller->setProperty('columns', 0);
		$this->loadJquery = $this->controller->getProperty('loadJquery', true);
		if($this->loadJquery){
			$this->modx->regClientStartupScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
		}
		$this->modx->regClientStartupScript($this->controller->config['plugins_url'] . 'galleriffic/libs/jquery.galleriffic.js');
		$this->modx->regClientStartupScript($this->controller->config['plugins_url'] . 'galleriffic/libs/jquery.history.js');
		$this->modx->regClientStartupScript($this->controller->config['plugins_url'] . 'galleriffic/libs/jquery.opacityrollover.js');
		
		$this->controller->setProperty('wrapperTpl','wrapper');
		$this->controller->setProperty('itemTpl','item');
	}
	
	public function render(){
		$this->controller->loadCSS('style');
		$script = $this->controller->getProperty('js', 'script');
		$this->modx->regClientHTMLBlock('<script type="text/javascript">'. $this->controller->getChunk($script) .'</script>');
	}
}