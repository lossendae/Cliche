<?php
$id = $scriptProperties['id'];

if(!empty($id)){
	$image = $modx->getObject('ClicheItems', $id);
	if($image){
		$albumId = $image->album_id;
	
		$image->remove();
		$image->updateAlbum('remove');
		
		//Send back updated album informations
		$owner = $modx->getObjectGraph('ClicheAlbums', '{ "CreatedBy": {}, "Cover":{} }', $albumId);		
		$album = $owner->toArray();		
		if($album['total'] > 0){
			$album['createdby'] = $owner->CreatedBy->get('username');
			$album['createdon'] = date('j M Y',strtotime($album['createdon']));
			if($album['cover_id'] != 0){			
				$album['image'] = $owner->Cover->get('image');
				$album['thumbnail'] = $owner->Cover->get('manager_thumbnail');
				$album['phpthumb'] = $modx->cliche->config['phpthumb'] . urlencode($album['image']);
			}
		}
		
		$response['success'] = true;
		$response['data'] = $album;
		$response['msg'] = $modx->lexicon('cliche.item_deleted_succesfully');
	} else {
		$response['success'] = false;
		$response['msg'] = $modx->lexicon('cliche.error_delete_item_aborted');
	}
} else {
	$response['success'] = false;
	$response['msg'] = $modx->lexicon('cliche.error_delete_item_no_id');
}

return $modx->toJSON($response);