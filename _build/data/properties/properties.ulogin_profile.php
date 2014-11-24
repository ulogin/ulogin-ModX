<?php
/**
 * @package ulogin
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'id',
        'desc' => 'ulogin_prop.id_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => '',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'uloginid',
        'desc' => 'ulogin_prop.uloginid_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => '',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'usr_hello',
        'desc' => 'ulogin_prop.usrhello_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'Hello',
        'lexicon' => 'ulogin:properties',
    ),
    array(  //
        'name' => 'usr_profile',
        'desc' => 'ulogin_prop.usrprofile_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => '#',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'signout_msg',
        'desc' => 'ulogin_prop.signoutmsg_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'Sign out',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'signout_url',
        'desc' => 'ulogin_prop.signouturl_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => '',
        'lexicon' => 'ulogin:properties',
    ),
    array(  //
        'name' => 'tpl',
        'desc' => 'ulogin_prop.tpl_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'profile_tpl',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'networks_msg',
        'desc' => 'ulogin_prop.networks_msg_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'User\'s accounts:',
        'lexicon' => 'ulogin:properties',
    ),
);
return $properties;