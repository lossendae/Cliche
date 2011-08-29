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
			
			if(!$modx->getOption('friendly_urls') && isset($options['use_furls'])){
				$setting = $modx->getObject('modSystemSetting', array(
					'key' => 'friendly_urls',
				));
				$setting->set('value', 1);
				$setting->save();
				unset($setting);
				
				if(!$modx->getOption('use_alias_path')){
					$setting = $modx->getObject('modSystemSetting', array(
						'key' => 'use_alias_path',
					));
					$setting->set('value', 1);
					$setting->save();
					unset($setting);
				}				
				$modx->log(modX::LOG_LEVEL_INFO, 'Friendly URLS activated succesfully');
			}
			
			if(!$modx->getOption('automatic_alias') && isset($options['autoalias'])){
				$setting = $modx->getObject('modSystemSetting', array(
					'key' => 'automatic_alias',
				));
				$setting->set('value', 1);
				$setting->save();
				unset($setting);
				$modx->log(modX::LOG_LEVEL_INFO, 'Automatic alias generator activated succesfully');
			}
			
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