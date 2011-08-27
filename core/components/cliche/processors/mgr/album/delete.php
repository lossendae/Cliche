<?php
$id = $scriptProperties['id'];

if(!empty($id)){

	$album = $modx->getObjectGraph('ClicheAlbums', '{ "Items":{} }', $id);
	
	/* Delete the picture of the album */
	if($album->Items){
		foreach($album->Items as $pics){
		$file = $modx->cliche->config['images_path'].$pics->filename;
			if(file_exists($file)){
				unlink($file);	
			}
		}
	}
	
	/* Remove the directory */
	if(is_dir($modx->cliche->config['images_path'].$album->id)){
		rmdir($modx->cliche->config['images_path'].$album->id);
	}
	
	/* Delete the album */
	if($album->remove()){
		$response['success'] = true;
		$response['msg'] = 'Album et Photos supprimée avec succès';
	} else {
		$response['success'] = false;
		$response['msg'] = 'Erreur lors de la suppression de l\'album - Opération annulée';
	}
} else {
	$response['success'] = false;
	$response['msg'] = 'Aucun id ou id érroné';
}

return $modx->toJSON($response);