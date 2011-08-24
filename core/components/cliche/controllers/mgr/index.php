<?php
/**
 * Loads the core base js classes for mgr pages.
 *
 * @package cliche
 * @subpackage controllers
 */
 
/* Less file for development */
$modx->regClientCSS($cliche->config['css_url'] .'styles.css');

/* Properties */
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
	MODx.ClicheConnectorUrl = "'.$cliche->config['connector_url'].'";
	MODx.ClicheAssetsUrl = "'.$cliche->config['assets_url'].'";
	MODx.ClicheAssetsPath = "'.$cliche->config['assets_path'].'";
</script>');

/* App base definitions */
$modx->regClientStartupScript($cliche->config['assets_url'].'core/');

return '';