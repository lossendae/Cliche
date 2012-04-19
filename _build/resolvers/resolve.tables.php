<?php
/**
* Resolve creating db tables
*
* @package cliche
* @subpackage build
*/
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/').'model/';
            $modx->addPackage('cliche',$modelPath);

            $manager = $modx->getManager();
            $manager->createObjectContainer('ClicheAlbums');           
            $manager->createObjectContainer('ClicheItems');            

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break; 
        case xPDOTransport::ACTION_UNINSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/').'model/';
            $modx->addPackage('cliche',$modelPath);

            $manager = $modx->getManager();
            $manager->removeObjectContainer('ClicheAlbums');
            $manager->removeObjectContainer('ClicheItems');
            break;
    }
}
return true;