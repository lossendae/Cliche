<?php
/**
* @package discuss
* @subpackage build
*/
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'Cliche',
    'description' => 'Browse your albums from a single call',
    'snippet' => getSnippetContent($sources['snippets'], 'snippet.cliche', $sources['debug']),
),'',true,true);
// $properties = include $sources['build'].'properties/properties.discuss.php';
// $snippets[0]->setProperties($properties);
// unset($properties, $content);

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'ClicheAlbum',
    'description' => 'Get an album sets',
    'snippet' => getSnippetContent($sources['snippets'], 'snippet.clichealbum', $sources['debug']),
),'',true,true);
// $properties = include $sources['build'].'properties/properties.discuss.php';
// $snippets[0]->setProperties($properties);
// unset($properties, $content);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'ClicheItem',
    'description' => 'Get a single item',
    'snippet' => getSnippetContent($sources['snippets'], 'snippet.clicheitem', $sources['debug']),
),'',true,true);
// $properties = include $sources['build'].'properties/properties.discuss.php';
// $snippets[0]->setProperties($properties);
// unset($properties, $content);

return $snippets;