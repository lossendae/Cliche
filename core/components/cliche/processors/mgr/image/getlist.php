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
		$pic['image'] = $modx->cliche->config['images_url'].$row->filename;	
		$pic['thumbnail'] = $modx->cliche->config['phpthumb'].urlencode($pic['image']).'&h=80&w=90&zc=1';	
		$pic['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($pic['image']);
		$pics[] = $pic;
	}
	$results = $pics;
} else {
	$results = array();
}
$response['success'] = true;
$response['total'] = $count;
$response['results'] = $results;
return $modx->toJSON($response);