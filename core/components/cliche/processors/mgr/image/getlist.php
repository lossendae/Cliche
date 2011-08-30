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

$rows = $modx->getCollectionGraph('ClicheItems', '{ "CreatedBy": {}, "Album": { "Cover": {} } }',$c);
if($rows){
	foreach($rows as $row){
		$pic = $row->toArray();
		$pic['createdby'] = $row->CreatedBy->get('username');
		$pic['createdon'] = date('j M Y',strtotime($pic['createdon']));
		$pic['image'] = $modx->cliche->config['images_url'].$row->filename;	
		$pic['thumbnail'] = $modx->cliche->config['phpthumb'].urlencode($pic['image']).'&h=80&w=95&zc=1';	
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
		$album['image'] = $modx->cliche->config['images_url'] . $owner->Cover->filename;
		$album['thumbnail'] = $modx->cliche->config['phpthumb'] . urlencode($album['image']) .'&h=80&w=95&zc=1';
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