<?php
/**
 * uLogin Connector
 *
 * @package ulogin
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('ulogin.core_path',null,$modx->getOption('core_path').'components/ulogin/');
require_once $corePath.'model/ulogin/ulogin.class.php';
$modx->ulogin = new uLogin($modx);

$modx->lexicon->load('ulogin:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->ulogin->config,$corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));