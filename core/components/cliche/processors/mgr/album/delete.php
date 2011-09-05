<?php
$id = $scriptProperties['id'];

if(!empty($id)){
	$album = $modx->getObject('ClicheAlbums', $id);
	
	/* Delete the picture of the album */
//	if($album->Items){
//		foreach($album->Items as $pics){
//		$file = $modx->cliche->config['images_path'].$pics->filename;
//			if(file_exists($file)){
//				unlink($file);
//			}
//		}
//	}
	
	/* Remove the directory */
//	if(is_dir($modx->cliche->config['images_path'].$album->id)){
//		rmdir($modx->cliche->config['images_path'].$album->id);
//	}
	
	/* Delete the album */
	if($album->remove()){
		$response['success'] = true;
		$response['msg'] = $modx->lexicon('cliche.album_deleted_successfully');
	} else {
		$response['success'] = false;
		$response['msg'] = $modx->lexicon('cliche.error_album_delete_cancelled');
	}
} else {
	$response['success'] = false;
	$response['msg'] = $modx->lexicon('cliche.error_album_delete_no_id');
}

return $modx->toJSON($response);