<?php
/**
 * Build the setup options form.
 *
 * @package ilogin
 * @subpackage build
 */
/* set some default values */

/* get values based on mode */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $u_group = $modx->getObject('modUserGroup', array('name' => 'uLogin'));
        if (!isset($u_group)) {
            $u_group = $modx->newObject('modUserGroup', array('name' => 'uLogin'));
            $u_group->save();
        }

        if (!$modx->getOption('ulogin.group_id', null, false)) {
            $uSettings = $modx->newObject( 'modSystemSetting' );
            $uSettings->set( 'key', 'ulogin.group_id' );
            $uSettings->set( 'value', $u_group->id );
            $uSettings->set( 'xtype', 'textfield' );
            $uSettings->set( 'namespace', 'ulogin' );
            $uSettings->set( 'area', 'ulogin' );
            $uSettings->save();
        }
        if (!$modx->getOption('ulogin.role_id', null, false)){
            if ($roleMember = $modx->getObject('modUserGroupRole', array('name'=>'Member'))) {
                $uSettings = $modx->newObject( 'modSystemSetting' );
                $uSettings->set( 'key', 'ulogin.role_id' );
                $uSettings->set( 'value', $roleMember->id );
                $uSettings->set( 'xtype', 'textfield' );
                $uSettings->set( 'namespace', 'ulogin' );
                $uSettings->set( 'area', 'ulogin' );
                $uSettings->save();
            }
        }
        // Clear the cache:
        $cacheRefreshOptions =  array( 'system_settings' => array() );
        $modx->cacheManager-> refresh($cacheRefreshOptions);

//        $success = true;
//        $success = '<h2>uLogin Installer</h2>
//            <p>Thanks for installing uLogin!</p>';
        break;
    case xPDOTransport::ACTION_UNINSTALL:
//        $success = true;
        break;
}

return $success;