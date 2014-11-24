<?php
require_once (dirname(__FILE__).'/update.class.php');
/**
 * @package ulogin
 * @subpackage processors
 */
class uloginUpdateGroupProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modSystemSetting';
    public $languageTopics = array('ulogin:default');
    public $objectType = 'ulogin.widget';
    public $primaryKeyField = 'key';
    public $afterSaveEvent = 'OnUloginGroupSave';

    public function fireAfterSaveEvent(){
        // Clear the cache:
        $cacheRefreshOptions =  array( 'system_settings' => array() );
        $this->modx->cacheManager-> refresh($cacheRefreshOptions);
        return parent::fireAfterSaveEvent();
    }
}
return 'uloginUpdateGroupProcessor';