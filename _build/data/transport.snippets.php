<?php
/**
* @package cliche
* @subpackage build
*/
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'Cliche',
    'description' => 'Browse your albums from a single call',
    'snippet' => getSnippetContent($sources['snippets'], 'snippet.cliche'),
),'',true,true);
// $properties = include $sources['build'].'properties/properties.cliche.php';
// $snippets[0]->setProperties($properties);
// unset($properties, $content);

return $snippets;