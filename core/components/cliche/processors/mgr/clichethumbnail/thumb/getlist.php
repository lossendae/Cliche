<?php
$tvId = $scriptProperties['tv'];
$start = $scriptProperties['start'];
$limit = $scriptProperties['limit'];
$sort = $modx->getOption('sort', $scriptProperties, 'ClicheItems.id');
$dir = $modx->getOption('dir', $scriptProperties, 'ASC');
$pics = array();

if(empty($tvId)){
    $response['success'] = false;
    $response['message'] = 'TV id not supplied';
    return $modx->toJSON($response);
}

$tv = $modx->getObject('modTemplateVar', $tvId);
$properties = $tv->getProperties();    
$album = $modx->getObject('ClicheAlbums', $properties['clichealbum']);


$c = $modx->newQuery('ClicheItems');
$c->where(array(
    'album_id' => $properties['clichealbum'],
));

/* paginate result */
$count = $modx->getCount('ClicheItems', $c);

/* limit and sort */
$c->sortBy($sort,$dir);
$c->limit($limit, $start);

$rows = $modx->getCollectionGraph('ClicheItems', '{ "CreatedBy": {} }',$c);
if($rows){
    foreach($rows as $row){
        $pic = $row->toArray();
        $pic['createdby'] = $row->CreatedBy->get('username');
        $pic['createdon'] = date('j M Y',strtotime($pic['createdon']));
        $pic['image'] = $row->get('image');
        $pic['thumbnail'] = $row->get('manager_thumbnail');
        $pic['tv'] = $tvId;

        $pics[] = $pic;    
    }
    unset($rows);
}
$response['success'] = true;
$response['total'] = $count;
$response['results'] = $pics;
$response['album'] = $album->toArray();
return $modx->toJSON($response);