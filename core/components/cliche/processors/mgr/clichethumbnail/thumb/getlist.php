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
$tvDesc = $tv->get('description');

/* Verify if the TV dedicated album exist, else create it */
$album = $modx->getObject('ClicheAlbums', array('name' => 'Cliche Thumbnail TV - #' . $tvId));
if(!$album){
	$album = $modx->newObject('ClicheAlbums');
	$album->fromArray(array(
		'name' => 'Cliche Thumbnail TV - #' . $tvId,
		'description' => $tvDesc,
		'type' => 'clichethumbnail',
	));
	$album->save();
}
$albumId = $album->get('id');

$c = $modx->newQuery('ClicheItems');
$c->where(array(
	'album_id' => $albumId,
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