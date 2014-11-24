<?php
/**
 * @package ulogin
 * @subpackage processors
 */
class uloginWidgetRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'uLoginWidget';
    public $languageTopics = array('ulogin:default');
    public $objectType = 'ulogin.widget';
}
return 'uloginWidgetRemoveProcessor';