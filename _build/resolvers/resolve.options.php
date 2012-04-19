<?php
/**
 * Setup optiosn resolver
 *
 * @package MyJournal
 * @subpackage build
 */
 
 
if (!isset($object) || !isset($object->xpdo)) return false;

$success= true;

$modx =& $object->xpdo;

if (isset($options)) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            
            $template = $modx->getOption('myjournal.default_template');
            if($template != $options['defaulttpl'] && !empty($options['defaulttpl'])){
                $setting = $modx->getObject('modSystemSetting', array(
                    'key' => 'myjournal.default_template',
                ));
                $setting->set('value', $options['defaulttpl']);
                $setting->save();
                unset($setting);
                $modx->log(modX::LOG_LEVEL_INFO, 'New default tempalte set succesfully');
            }
                            
            $success = true;

        case xPDOTransport::ACTION_UNINSTALL:

            $success= true;
            break;
    }
}

return $success;