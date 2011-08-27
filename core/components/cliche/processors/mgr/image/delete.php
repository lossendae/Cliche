<?php

$id = $scriptProperties['id'];

if(!empty($id)){
	$photo = $modx->getObjectGraph('ClicheItems', '{ "Album":{} }', $id);
	if($photo){
		$f = $modx->cliche->config['images_path'].$photo->filename;
		if(file_exists($f)){
			unlink($f);
		}		
	
		$album_id = $photo->album_id;
		$album_cover_id = $photo->Album->cover_id;
		$photo_id = $photo->id;	
		/* Do we have to change the album cover image ? */
		if($album_cover_id == $photo_id){
			$new_cover = 0;
			
			/* Get the first available image for this album */
			$c = $modx->newQuery('ClicheAlbums');
			$c->where(array(
				'id' => $album_id,
				'Items.id:!=' => $photo_id,
			));
			$c->limit(1);
			$rows = $modx->getCollectionGraph('ClicheAlbums', '{ "Items":{} }', $c);
			if($rows){
				foreach($rows as $row){					
					if($row->Items){
						foreach($row->Items as $pic){
							$new_cover = $pic->id;
						}
					}
				}
			}
			/* Save the queen */
			$photo->Album->set('cover_id', $new_cover);			
			$photo->save();
			
		}	
		/* And delete the picture db entry */
		$photo->remove();
		
		/* Upadte photo count */
		$total = $modx->getCount('ClicheItems', array('album_id' => $album_id));
		$photo->Album->set('total' , $total);
		$photo->save();

		$response['success'] = true;
		$response['msg'] = '<div class="confirm"><p>Photo supprimée...<br/>Retour sur la page de l\'album <em>*name*</em></p></div>';
	} else {
		$response['success'] = false;
		$response['msg'] = 'La photo n\'a pas pû être supprimée. Veuillez contcter le webmaster';
	}
} else {
	$response['success'] = false;
	$response['msg'] = 'Whatever you do, you\'re always wrong!';
}

return $modx->toJSON($response);