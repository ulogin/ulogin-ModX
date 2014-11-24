<?php
/**
 * @package ulogin
 * @subpackage controllers
 */
class UloginHomeManagerController extends UloginManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('ulogin'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->ulogin->config['jsUrl'].'mgr/widgets/ulogin.grid.js');
        $this->addJavascript($this->ulogin->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->ulogin->config['jsUrl'].'mgr/sections/index.js');
    }
    public function getTemplateFile() { return $this->ulogin->config['templatesPath'].'home.tpl'; }
}