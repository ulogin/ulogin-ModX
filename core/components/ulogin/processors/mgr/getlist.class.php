<?php
/**
 * Get a list of uLogin
 *
 * @package ulogin
 * @subpackage processors
 */
class uloginWidgetGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'uLoginWidget';
    public $languageTopics = array('ulogin:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'ulogin.widget';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'uloginid:LIKE' => '%'.$query.'%',
            ));
        }
        return $c;
    }
}
return 'uloginWidgetGetListProcessor';