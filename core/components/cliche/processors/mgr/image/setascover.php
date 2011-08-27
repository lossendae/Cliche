<?php
$id = $scriptProperties['id'];
$album = $scriptProperties['album'];
$response['success'] = false;

$album = $modx->getObjectGraph('ClicheAlbums', '{"Cover":{}}', $album);
$album->set('cover_id', $id);

/* Save the new cover */
if($album->save()){
	/* Get the updated album informations  */
	$album = $modx->getObjectGraph('ClicheAlbums', '{"Cover":{}, "CreatedBy":{}}', $album);
	$data = $album->toArray();
	$data['createdby'] = $album->CreatedBy->get('username');
	$data['createdon'] = date('j M Y',strtotime($data['createdon']));
	$data['image'] = $modx->cliche->config['images_url'] . $album->Cover->filename;
	$data['thumbnail'] = $modx->cliche->config['phpthumb'] . urlencode($data['image']) .'&h=80&w=90&zc=1';
	$data['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($data['image']);
	
	$response['success'] = true;
	$response['msg'] = 'Image set as album cover successfully';
	$response['data'] = $data;
}

return $modx->toJSON($response);