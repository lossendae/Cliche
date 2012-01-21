<?php
$start = $scriptProperties['start'];
$limit = $scriptProperties['limit'];
$albumId = $scriptProperties['album'];
$sort = $modx->getOption('sort', $scriptProperties, 'ClicheItems.id');
$dir = $modx->getOption('dir', $scriptProperties, 'ASC');
$query = $modx->getOption('query', $scriptProperties, false);

$c = $modx->newQuery('ClicheItems');
$c->where(array(
	'album_id' => $albumId,
));
/* paginate result */
$count = $modx->getCount('ClicheItems', $c);

if($query && $query != ''){
	$c->where(array(
		'name:LIKE' => '%'. $query .'%' 
	));
}

/* limit and sort */
$c->sortBy($sort,$dir);
$c->limit($limit, $start);

$rows = $modx->getCollectionGraph('ClicheItems', '{ "CreatedBy": {} }',$c);
if($rows){
	foreach($rows as $row){
		$pic = $row->toArray();
		$pic['createdby'] = $row->CreatedBy->get('username');
		$pic['createdon'] = date('j M Y',strtotime($pic['createdon']));
		$image = $modx->cliche->config['images_path'] . $row->get('filename');
		if(file_exists($image)){
			$pic['image'] = $row->get('image');
			$pic['thumbnail'] = $row->get('manager_thumbnail');
		}
		$pics[] = $pic;	
	}
	unset($rows);
	
	$results = $pics;
} else {
	$results = array();
}

/* Retreive the album owner informations */
$owner = $modx->getObjectGraph('ClicheAlbums', '{ "CreatedBy": {}, "Cover":{} }', $albumId);
$album = $owner->toArray();
$album['createdby'] = $owner->CreatedBy->get('username');
$album['createdon'] = date('j M Y',strtotime($album['createdon']));
$image = $modx->cliche->config['images_path'] . $owner->Cover->get('filename');
if($album['cover_id'] != 0 && file_exists($image)){			
	$album['image'] = $owner->Cover->get('image');
	$album['thumbnail'] = $owner->Cover->get('manager_thumbnail');
} else {
	$album['image'] = false;
	$album['thumbnail'] = false;
}
	
$response['success'] = true;
$response['total'] = $count;
$response['results'] = $results;
$response['album'] = $album;
return $modx->toJSON($response);