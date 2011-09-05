<?php
$start = $scriptProperties['start'];
$limit = $scriptProperties['limit'];
$albumId = $scriptProperties['album'];
$sort = 'ClicheItems.'.$scriptProperties['sort'];
$dir = $scriptProperties['dir'];

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
		$pic['image'] = $row->get('filename');
		$pic['thumbnail'] = $row->get('manager_thumbnail');
		$pic['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($pic['image']);

		$pics[] = $pic;	
	}
	unset($rows);
	
	/* Retreive the album owner informations */
	$owner = $modx->getObjectGraph('ClicheAlbums', '{ "CreatedBy": {}, "Cover":{} }', $pic['album_id']);
	$album = $owner->toArray();
	$album['createdby'] = $owner->CreatedBy->get('username');
	$album['createdon'] = date('j M Y',strtotime($album['createdon']));
	if($album['cover_id'] != 0){			
		$album['image'] = $owner->Cover->get('image');
		$album['thumbnail'] = $owner->Cover->get('manager_thumbnail');
		$album['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($album['image']);
	}
	
	$results = $pics;
} else {
	$results = array();
}
$response['success'] = true;
$response['total'] = $count;
$response['results'] = $results;
$response['album'] = $album;
return $modx->toJSON($response);