<?php
$id = $scriptProperties['id'];
$albumId = $scriptProperties['album'];
$response['success'] = false;

$album = $modx->getObject('ClicheAlbums', $albumId);
$album->set('cover_id', $id);

/* Save the new cover */
if($album->save()){
	unset($album);
	/* Get the updated album informations  */
	$album = $modx->getObjectGraph('ClicheAlbums', '{"Cover":{}, "CreatedBy":{}}', $albumId);
	$data = $album->toArray();
	$data['createdby'] = $album->CreatedBy->get('username');
	$data['createdon'] = date('j M Y',strtotime($data['createdon']));
	$data['image'] = $modx->cliche->config['images_url'] . $album->Cover->filename;
	$data['thumbnail'] = $modx->cliche->config['phpthumb'] . urlencode($data['image']) .'&h=80&w=90&zc=1';
	$data['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($data['image']);
	
	$response['success'] = true;
	$response['msg'] = $modx->lexicon('cliche.item_set_as_cover_succesfully');
	$response['data'] = $data;
}

return $modx->toJSON($response);