<?php
/**
 * @package ulogin
 * @subpackage processors
 */
class uloginWidgetUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'uLoginWidget';
    public $languageTopics = array('ulogin:default');
    public $objectType = 'ulogin.widget';

    public function beforeSave() {
        $uloginid = $this->getProperty('uloginid');
        $id = $this->getProperty('id');

        if ($exist = $this->doesAlreadyExist(array('uloginid' => $uloginid, 'id:!=' => $id))) {
            $this->addFieldError('uloginid',$this->modx->lexicon('ulogin.widget_err_ae'));
//        } elseif (empty($uloginid)) {
//            $this->addFieldError('uloginid',$this->modx->lexicon('ulogin.widget_err_ns_uloginid'));
        }
        return parent::beforeSave();
    }
}
return 'uloginWidgetUpdateProcessor';