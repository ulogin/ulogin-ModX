<?php
/**
 * @package ulogin
 * @subpackage processors
 */
class uloginWidgetCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'uLoginWidget';
    public $languageTopics = array('ulogin:default');
    public $objectType = 'ulogin.widget';

    public function beforeSave() {
        $uloginid = $this->getProperty('uloginid');

        if ($this->doesAlreadyExist(array('uloginid' => $uloginid))) {
            $this->addFieldError('uloginid',$this->modx->lexicon('ulogin.widget_err_ae'));
//        } elseif (empty($uloginid)) {
//            $this->addFieldError('uloginid',$this->modx->lexicon('ulogin.widget_err_ns_uloginid'));
        }
        return parent::beforeSave();
    }
}
return 'uloginWidgetCreateProcessor';