<?php
/**
 * Default settings
 *
 * @package cliche
 * @subpackage build
 */

$settings = array();

$settings['cliche.upload_size_limit']= $modx->newObject('modSystemSetting');
$settings['cliche.upload_size_limit']->fromArray(array(
    'key' => 'cliche.upload_size_limit',
    'value' => 0,
    'xtype' => 'textfield',
    'namespace' => 'cliche',
    'area' => 'Cliche Uploader',
),'',true,true);

$settings['cliche.upload_allowed_extensions']= $modx->newObject('modSystemSetting');
$settings['cliche.upload_allowed_extensions']->fromArray(array(
    'key' => 'cliche.upload_allowed_extensions',
    'value' => 'jpg,jpeg,gif,png,zip',
    'xtype' => 'textfield',
    'namespace' => 'cliche',
    'area' => 'Cliche Uploader',
),'',true,true);

return $settings;