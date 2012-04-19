<?php
$resourceId = $scriptProperties['resourceId'];
$tvId = $scriptProperties['tv'];
$tv = $modx->getObject('modTemplateVar', $tvId);
$tvName = $tv->get('name');
$tvDesc = $tv->get('description');

/* Verify if the TV dedicated album exist, else create it */
$album = $modx->getObject('ClicheAlbums', array('name' => $tvName));
if(!$album){
    $album = $modx->newObject('ClicheAlbums');
    $album->fromArray(array(
        'name' => $tvName,
        'description' => $tvDesc,
        'type' => 'TV',
    ));
    $album->save();
}
$albumId = $album->get('id');

/* Verify if the resource has already an item  */
$modx->cliche->config['update_item'] = true;

/* Rename mask */
$mask = 'thumbnail-resource-'.$resourceId;
$modx->cliche->config['rename_mask'] = $mask;

/* Return the full size image */
$modx->cliche->config['return_thumb'] = false;

return $modx->cliche->loadHelper($albumId);