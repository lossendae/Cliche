<?php
/**
 * @package cliche
 * @subpackage plugin
 */
class DefaultPlugin extends ClichePlugin {
	public function load(){
		$this->view = $this->controller->getProperty('view');
		$this->browse = $this->controller->getProperty('browse');
		if(!$this->browse){
			switch( $this->view ){
				case 'albums':
					$this->controller->setProperty('itemTpl','albumcoverzoom');
					break;
				case 'album':
					$this->controller->setProperty('itemTpl','itemzoom');
					break;
				default:
					break;
			}					
		}	
		$this->useFancyBox = $this->controller->getProperty('useFancyBox', true);	
	}
	
	public function render(){
		$this->controller->loadCSS('style');
		/* Load fancybox only if we are viewing a single image and/or we're not in browse mode */
		if($this->useFancyBox && $this->view == 'image' || $this->useFancyBox && !$this->browse){
			$this->loadJquery = $this->controller->getProperty('loadJquery', true);
			if($this->loadJquery){
				$this->modx->regClientStartupScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
			}			
			$this->modx->regClientStartupScript($this->controller->config['plugins_url'] . 'default/fancybox/jquery.fancybox-1.3.4.pack.js');		
			
			$script = $this->controller->getProperty('js', 'script');
			$this->modx->regClientHTMLBlock('<script type="text/javascript">'. $this->controller->getChunk($script) .'</script>');
		}
	}
}