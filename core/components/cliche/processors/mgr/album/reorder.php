<?php
$albumId = $scriptProperties['album'];
$start = $scriptProperties['start'];
$limit = $scriptProperties['limit'];
$sort = $modx->getOption('sort', $scriptProperties, 'ClicheItems.id');
$dir = $modx->getOption('dir', $scriptProperties, 'ASC');
$ids = explode(',', $scriptProperties['ids']);
$i = 0;

/* Get the current record range data */
$c = $modx->newQuery('ClicheItems');
$c->where(array(
    'album_id' => $albumId,
));
$c->sortBy($sort,$dir);
$c->limit($limit, $start);
$rows = $modx->getCollection('ClicheItems', $c);
foreach($rows as $row){
    $id = $row->get('id');
    $items[$id] = $row->toArray();
    unset($id);
}
$dir = $modx->cliche->config['cache_path'] .'/'. $albumId .'/';
$modx->cacheManager->deleteTree($dir, array('deleteTop' => true, 'skipDirs' => false, 'extensions' => '*'));

foreach($items as $key => $value){
    $newOrder = $modx->getObject('ClicheItems', $key);    
    $data = $items[$ids[$i]];
    $newOrder->fromArray($data);    
    $newOrder->save();    
    $ttt[] = $newOrder->toArray();
    unset($newOrder,$ids[$i]);
    $i++;
}

$album = $modx->getObjectGraph('ClicheAlbums', '{"Cover":{}, "CreatedBy":{}}', $albumId);
$data = $album->toArray();
$data['createdby'] = $album->CreatedBy->get('username');
$data['createdon'] = date('j M Y',strtotime($data['createdon']));
$data['image'] = $album->Cover->get('image');
$data['thumbnail'] = $album->Cover->get('manager_thumbnail');

$response['success'] = true;
$response['data'] = $data;

return $modx->toJSON($response);