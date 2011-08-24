<?php
$start = $_REQUEST['start'];
$limit = $_REQUEST['limit'];
$albumId = $_REQUEST['album'];
$sort = 'ClicheItems.'.$_REQUEST['sort'];
$dir = $_REQUEST['dir'];

$c = $modx->newQuery('ClicheItems');
$c->where(array(
	'album_id' => $albumId,
));
$c->sortBy($sort,$dir);
$c->limit($limit,$start);

$rows = $modx->getCollection('ClicheItems', $c);

if($rows){
	foreach($rows as $row){
		$pic = $row->toArray();
		$pic['image'] = $modx->cliche->config['images_url'].$row->filename;	
		$pic['thumbnail'] = $modx->cliche->config['phpthumb'].urlencode($pic['image']).'&h=80&w=90&zc=1';	
		$pic['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($pic['image']);
		$pics[] = $pic;
	}
	$response['results'] = $pics;
} else {
	$response['results'] = array();
}
$response['success'] = true;
$response['total'] = 0;
return $modx->toJSON($response);