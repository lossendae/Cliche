<?php
/**
* Cliche
*
*
* @package cliche
* @subpackage controllers
*/
$this->addCss($this->cliche->config['css_url'].'clichethumbnail.css');
$this->addJavascript($this->cliche->config['assets_url'].'mgr/thumbnail/cmp/album.js');

$this->addPanel('cliche-album-panel-thumbnail');
$this->addPanel('upload'); 

$this->addLexiconTopic('cliche:clichethumbnail');