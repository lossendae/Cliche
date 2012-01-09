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
		/* Load fancybox only if we are viewing a single image */
		if($view == 'image'){
			$this->modx->regClientStartupScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
			$this->modx->regClientStartupScript($this->obj->config['plugins_url'] . 'default/fancybox/jquery.fancybox-1.3.4.pack.js');
			
			$this->modx->regClientHTMLBlock('<script type="text/javascript">$("a.zoom").fancybox();</script>');
		}
	}
}