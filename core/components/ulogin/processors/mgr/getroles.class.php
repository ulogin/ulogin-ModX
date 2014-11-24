<?php
/**
 * Get a list of uLogin
 *
 * @package ulogin
 * @subpackage processors
 */
class uloginWidgetGetRolesProcessor extends modObjectGetListProcessor {
    public $classKey = 'modUserGroupRole';
    public $languageTopics = array('ulogin:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'ulogin.widget';
}
return 'uloginWidgetGetRolesProcessor';