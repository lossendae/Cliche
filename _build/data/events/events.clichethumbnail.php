<?php
/**
 * Events for ClicheThumbnail plugin
 *
 * @package cliche
 * @subpackage build
 */
$events = array();

$evs = array(
    'OnTVInputRenderList',
    'OnTVInputPropertiesList',
    'OnTVOutputRenderList',
    'OnTVOutputRenderPropertiesList',
    'OnDocFormPrerender',
);

foreach ($evs as $ev) {
    $events[(string)$ev] = $modx->newObject('modPluginEvent');
    $events[(string)$ev]->fromArray(array(
        'event' => (string)$ev,
        'priority' => 0,
        'propertyset' => 0,
    ),'',true,true);
}

return $events;