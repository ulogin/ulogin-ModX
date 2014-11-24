<?php
/**
 * @package ulogin
 * @subpackage controllers
 */
require_once dirname(__FILE__) . '/model/ulogin/ulogin.class.php';
abstract class UloginManagerController extends modExtraManagerController {
    /** @var uLogin $ulogin */
    public $ulogin;
    public function initialize() {
        $this->ulogin = new uLogin($this->modx);

        //$this->addCss($this->ulogin->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->ulogin->config['jsUrl'].'mgr/ulogin.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Ulogin.config = '.$this->modx->toJSON($this->ulogin->config).';
        });
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('ulogin:default');
    }
    public function checkPermissions() { return true;}
}
/**
 * @package ulogin
 * @subpackage controllers
 */
class IndexManagerController extends UloginManagerController {
    public static function getDefaultController() { return 'home'; }
}