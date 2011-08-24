<?php
$start = $_REQUEST['start'];
$limit = $_REQUEST['limit'];
$sort = 'ClicheAlbums.'.$_REQUEST['sort'];
$dir = $_REQUEST['dir'];

// $manager = $modx->getManager();
// $manager->createObjectContainer('ClicheItems');

$c = $modx->newQuery('ClicheAlbums');
$c->limit($start,$limit);
$c->sortBy($sort,$dir);
// $c->prepare();
// echo $c->toSQL();
$rows = $modx->getCollectionGraph('ClicheAlbums', '{ "Items":{}, "Cover":{} }', $c);
if($rows){
	foreach($rows as $row){
		$album = $row->toArray();
		if($row->cover_id != 0){
			$album['image'] = $modx->cliche->config['images_url'] . $row->Cover->filename;
			$album['thumbnail'] = $modx->cliche->config['phpthumb'] . urlencode($album['image']) .'&h=80&w=90&zc=1';
			$album['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($album['image']);
		}
		$albums[] = $album;
	}
	$response['success'] = true;
	$response['results'] = $albums;
	$response['total'] = 0;
	return $modx->toJSON($response);
} else {
	$response['success'] = true;
	$response['results'] = array();
	$response['total'] = 0;
	return $modx->toJSON($response);
}