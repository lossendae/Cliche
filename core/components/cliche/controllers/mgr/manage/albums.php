<?php
/**
 * Loads the core base js classes for mgr pages.
 *
 * @package cliche
 * @subpackage controllers
 */
 
/* Albums list */
$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/manage/albums/list.js');
$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/manage/albums/create.js');

/* Album view */
$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/manage/album/view.js');

/* Picture uploader */
$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/manage/album/upload.js');

/* Picture view */
$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/manage/picture/view.js');

/* Container */
$modx->regClientStartupScript($cliche->config['assets_url'].'mgr/manage/main.panel.js');

/* Launch app */
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {	
	MODx.add("cliche-main-panel");	
	Ext.ux.Lightbox.register("a.lightbox");
});
</script>');

return '';