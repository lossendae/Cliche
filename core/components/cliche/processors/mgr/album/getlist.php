<?php
$start = $scriptProperties['start'];
$limit = $scriptProperties['limit'];
$sort = 'ClicheAlbums.'.$scriptProperties['sort'];
$dir = $scriptProperties['dir'];

$c = $modx->newQuery('ClicheAlbums');

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
		if($row->cover_id != 0){
			$album['image'] = $modx->cliche->config['images_url'] . $row->Cover->filename;
			$album['thumbnail'] = $modx->cliche->config['phpthumb'] . urlencode($album['image']) .'&h=80&w=90&zc=1';
			$album['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($album['image']);
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