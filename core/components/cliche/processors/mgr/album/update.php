<?php
$data['id'] = $scriptProperties['id'];
$data['name'] = $scriptProperties['name'];
$data['description'] = $scriptProperties['description'];

$album = $modx->getObject('ClicheAlbums', $data['id']);
$album->fromArray( $data );
if($album->save()){
	$album = $modx->getObjectGraph('ClicheAlbums', '{"Cover":{}, "CreatedBy":{}}', $data['id']);
	$data = $album->toArray();
	$data['createdby'] = $album->CreatedBy->get('username');
	$data['createdon'] = date('j M Y',strtotime($data['createdon']));
	$data['image'] = $modx->cliche->config['images_url'] . $album->Cover->filename;
	$data['thumbnail'] = $modx->cliche->config['phpthumb'] . urlencode($data['image']) .'&h=80&w=90&zc=1';
	$data['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($data['image']);
	
	return $modx->error->success($modx->lexicon('cliche.album_udpated_succesfully'), $data);
}