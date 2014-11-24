<?php
/**
 * Resolve creating custom db tables during install.
 *
 * @package ulogin
 * @subpackage build
 */

if ($object->xpdo) {
    $pkg_name = 'ulogin';
    $modx =& $object->xpdo;
    $modelPath = $modx->getOption($pkg_name.'.core_path',null,$modx->getOption('core_path').'components/'.$pkg_name.'/').'model/';
    $modx->addPackage($pkg_name, $modelPath);

    $manager = $modx->getManager();
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $manager->createObjectContainer('uLoginUser');
            $manager->createObjectContainer('uLoginWidget');
        case xPDOTransport::ACTION_UPGRADE:
//            $manager->addField('uLoginUser','network');
            break;
    }
}
return true;