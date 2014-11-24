<?php
$ulogin = $modx->getService('ulogin','uLogin',$modx->getOption('ulogin.core_path',null,$modx->getOption('core_path').'components/ulogin/').'model/ulogin/',$scriptProperties);
if (!($ulogin instanceof uLogin)) return '';

return $ulogin->initialize_profile($scriptProperties);
