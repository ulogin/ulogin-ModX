<?php
$ulogin = $modx->getService('ulogin','uLogin',$modx->getOption('ulogin.core_path',null,$modx->getOption('core_path').'components/ulogin/').'model/ulogin/',$scriptProperties);
if (!($ulogin instanceof uLogin)) return '';

if(!$modx->user->isAuthenticated()) return '';

$output = $ulogin->initialize_networks($scriptProperties);

return $ulogin->_getChunk('networks', array(
	'networks' => $output,
	'no_delete' => $scriptProperties['no_delete'],
));
