<?php
/**
 * Get a list of uLogin
 *
 * @package ulogin
 * @subpackage processors
 */
class uloginWidgetGetGroupsProcessor extends modObjectGetListProcessor {
    public $classKey = 'modUserGroup';
    public $languageTopics = array('ulogin:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'ulogin.widget';
}
return 'uloginWidgetGetGroupsProcessor';