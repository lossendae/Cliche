<?php
/**
* Build the setup options form.
*
* @package cliche
* @subpackage build
*/

/* Default values */
$str = ini_get('upload_max_filesize');
$maxUploadSize = trim($str);
$last = strtolower($str[strlen($str)-1]);
switch($last) {
    case 'g': $maxUploadSize *= 1024;
    case 'm': $maxUploadSize *= 1024;
    case 'k': $maxUploadSize *= 1024;        
}
$values = array(
    'upload_size_limit' => $maxUploadSize,
    'upload_allowed_extensions' => 'jpg,jpeg,gif,png,zip',
);
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting',array('key' => 'cliche.upload_size_limit'));
        if ($setting != null ) { 
            $v = $setting->get('value'); 
            if($v != 0) $values['upload_size_limit'] = $v;             
        }
        unset($setting);

        $setting = $modx->getObject('modSystemSetting',array('key' => 'cliche.upload_allowed_extensions'));
        if ($setting != null) { $values['upload_allowed_extensions'] = $setting->get('value'); }
        unset($setting);
    break;
    case xPDOTransport::ACTION_UNINSTALL: break;
}

$output = '
<style type="text/css">
    .field-desc{
        color: #A0A0A0;
        font-size: 11px;
        font-style: italic;
        line-height: 1;
        margin: 5px -15px 0;
        padding: 0 15px;
    }
    .field-desc.sep{
        border-bottom: 1px solid #E0E0E0;
        margin-bottom: 15px;
        padding-bottom: 15px;
    }
</style>';

$output .= '<label for="upload_size_limit">Upload size limit:</label>
<input type="text" name="upload_size_limit" id="upload_size_limit" width="300" value="'.$values['upload_size_limit'].'" />
<div class="field-desc sep">Maximum file size for file upload</div>';

$output .= '<label for="upload_allowed_extensions">Allowed Extensions:</label>
<input type="text" name="upload_allowed_extensions" id="upload_allowed_extensions" width="300" value="'.$values['upload_allowed_extensions'].'" />
<div class="field-desc">Comma separated list of extension allowed for file upload</div>';

return $output;