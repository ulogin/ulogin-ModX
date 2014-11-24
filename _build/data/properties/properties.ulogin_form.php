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
        'name' => 'ul_id',
        'desc' => 'ulogin_prop.ul_id_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => '',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'display',
        'desc' => 'ulogin_prop.displ_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'small',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'fields',
        'desc' => 'ulogin_prop.fields_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'first_name,last_name,email',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'optional',
        'desc' => 'ulogin_prop.fields_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'phone,city,country,nickname,sex,photo_big,photo,bdate',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'providers',
        'desc' => 'ulogin_prop.prov_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'vkontakte,odnoklassniki,mailru,facebook',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'hidden',
        'desc' => 'ulogin_prop.hidden_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'other',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'redirect',
        'desc' => 'ulogin_prop.redirect_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => '',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'callback',
        'desc' => 'ulogin_prop.call_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => 'uloginCallback',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'display_always',
        'desc' => 'ulogin_prop.display_always_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => '',
        'lexicon' => 'ulogin:properties',
    ),
    array(
        'name' => 'show_message',
        'desc' => 'ulogin_prop.show_message_desc',
        'type' => 'textfield',
        'options' => array(),
        'value' => '1',
        'lexicon' => 'ulogin:properties',
    ),
);
return $properties;