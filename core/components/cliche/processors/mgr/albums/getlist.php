<?php
$start = $scriptProperties['start'];
$limit = $scriptProperties['limit'];
$sort = $modx->getOption('sort', $scriptProperties, 'ClicheAlbums.createdon');
$dir = $modx->getOption('dir', $scriptProperties, 'ASC');
$query = $modx->getOption('query', $scriptProperties, false);

$type = $modx->getOption('type', $scriptProperties, 'default');

$c = $modx->newQuery('ClicheAlbums');

/* @TODO make a filter on album type */
// $c->where(array(
    // 'ClicheAlbums.type' => $type,
// ));

if($query && $query != ''){
	$c->where(array(
		'name:LIKE' => '%'. $query .'%' 
	));
}

/* paginate result */
$count = $modx->getCount('ClicheAlbums', $c);

/* limit and sort */
$c->limit($limit, $start);
$c->sortBy($sort,$dir);

$rows = $modx->getCollectionGraph('ClicheAlbums', '{ "CreatedBy": {}, "Cover":{} }', $c);
if($rows){
	foreach($rows as $row){
		$album = $row->toArray();
		$album['createdby'] = $row->CreatedBy->get('username');
		$album['createdon'] = date('j M Y',strtotime($album['createdon']));
		$image = $modx->cliche->config['images_path'] . $row->Cover->get('filename');
		if($row->cover_id != 0 && file_exists($image)){
			$album['image'] = $modx->cliche->config['images_url'] . $row->Cover->get('filename');
			$album['thumbnail'] = $row->Cover->get('manager_thumbnail');		
		}
		$albums[] = $album;
	}
	$response['success'] = true;
	$response['total'] = $count;
	$response['results'] = $albums;	
	return $modx->toJSON($response);
} else {
	$response['success'] = true;
	$response['total'] = 0;
	$response['results'] = array();	
	return $modx->toJSON($response);
}