<?php
$tvId = $scriptProperties['tv'];
if(empty($tvId)){
    $response['success'] = false;
    $response['message'] = 'TV id not supplied';
    return $modx->toJSON($response);
}

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

$start = $scriptProperties['start'];
$limit = $scriptProperties['limit'];
$sort = 'ClicheItems.'.$scriptProperties['sort'];
$dir = $scriptProperties['dir'];
$results = array();

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
		$pic['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($pic['image']);
		$pic['timestamp'] = strtotime('now');

		$results[] = $pic;
	}
	unset($rows);
}
$response['success'] = true;
$response['total'] = $count;
$response['results'] = $results;
return $modx->toJSON($response);