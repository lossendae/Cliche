<?php
/**
 * Default settings
 *
 * @package cliche
 * @subpackage build
 */

$settings = array();

$settings['cliche.album_mgr_panels']= $modx->newObject('modSystemSetting');
$settings['cliche.album_mgr_panels']->fromArray(array(
    'key' => 'cliche.album_mgr_panels',
    'value' => 'cliche-album-default,cliche-item-default-upload-panel,cliche-album-clichethumbnail',
    'xtype' => 'textfield',
    'namespace' => 'cliche',
    'area' => 'Cliche - Album Manager',
),'',true,true);

return $settings;