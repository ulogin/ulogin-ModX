<?php
/**
 * @package ulogin
 * @subpackage build
 */
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = trim(str_replace(array('<?php','?>'),'',$o));
    return $o;
}
$snippets = array();
$snippetsPath = $sources['elements'].'snippets/';
$propertiesPath = $sources['data'].'properties/';


/* course snippets */
$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'uLogin',
    'description' => 'uLogin snippet.',
    'snippet' => getSnippetContent($snippetsPath . 'snippet.ulogin.php'),
),'',true,true);
$snippets[1]->setProperties(include $propertiesPath.'properties.ulogin.php');


$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'uLogin.Form',
    'description' => 'uLogin form snippet.',
    'snippet' => getSnippetContent($snippetsPath . 'snippet.ulogin_form.php'),
),'',true,true);
$snippets[2]->setProperties(include $propertiesPath.'properties.ulogin_form.php');


$snippets[3]= $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 3,
    'name' => 'uLogin.Profile',
    'description' => 'uLogin profile snippet.',
    'snippet' => getSnippetContent($snippetsPath . 'snippet.ulogin_profile.php'),
),'',true,true);
$snippets[3]->setProperties(include $propertiesPath.'properties.ulogin_profile.php');

$snippets[4]= $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => 4,
    'name' => 'uLogin.Networks',
    'description' => 'uLogin user\'s networks snippet.',
    'snippet' => getSnippetContent($snippetsPath . 'snippet.ulogin_networks.php'),
),'',true,true);
$snippets[4]->setProperties(include $propertiesPath.'properties.ulogin_networks.php');

$snippets[5]= $modx->newObject('modSnippet');
$snippets[5]->fromArray(array(
    'id' => 5,
    'name' => 'uLogin.Message',
    'description' => 'uLogin messages snippet.',
    'snippet' => getSnippetContent($snippetsPath . 'snippet.ulogin_message.php'),
),'',true,true);
$snippets[5]->setProperties(include $propertiesPath.'properties.ulogin_message.php');

$snippets[6]= $modx->newObject('modSnippet');
$snippets[6]->fromArray(array(
    'id' => 6,
    'name' => 'uLogin.Urlencode',
    'description' => 'Encoding for redirect url.',
    'snippet' => getSnippetContent($snippetsPath . 'snippet.ulogin_urlencode.php'),
),'',true,true);

return $snippets;