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

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'ClicheAlbum',
    'description' => 'Browse a specific album',
    'snippet' => getSnippetContent($sources['snippets'], 'snippet.clichealbum'),
),'',true,true);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'ClicheImage',
    'description' => 'View a single image',
    'snippet' => getSnippetContent($sources['snippets'], 'snippet.clicheimage'),
),'',true,true);

return $snippets;