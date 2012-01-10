<?php
/**
 * @package cliche
 * @subpackage plugin
 */
class DefaultPlugin extends ClichePlugin {
    /**
     * Run the plugin against a specified event
     *
     * @param string $notify The event name
     * @param array $args An optionnal array of parameters passed with the event
     * @return void
     */
    public function notify( $event, $args = null ){
		$view = $this->obj->getProperty('view');
		$browse = $this->obj->getProperty('browse');	
		switch($event){
			case 'load':
				/* We're not in browse mode - Use an alternative chunk for each item when not in image view */
				if(!$browse){
					$this->obj->setProperties(array(
						'albumItemTpl' => 'albumcoverzoom',
						'itemTpl' => 'itemzoom',
					));
				}	
				$this->useFancyBox = $this->obj->getProperty('useFancyBox', true);						
				break;
			case 'render':
				$this->obj->loadCSS('style');
				/* Load fancybox only if we are viewing a single image and/or we're not in browse mode */
				if($this->useFancyBox && $view == 'image' || $this->useFancyBox && !$browse){
					$this->loadJquery = $this->obj->getProperty('loadJquery', true);
					if($this->loadJquery){
						$this->modx->regClientStartupScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
					}			
					$this->modx->regClientStartupScript($this->obj->config['plugins_url'] . 'default/fancybox/jquery.fancybox-1.3.4.pack.js');		
					
					$script = $this->obj->getProperty('js', 'script');
					$this->modx->regClientHTMLBlock('<script type="text/javascript">'. $this->obj->getChunk($script) .'</script>');
				}
				break;
			default:
				break;
		}		
	}
}