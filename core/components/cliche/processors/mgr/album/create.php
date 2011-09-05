<?php
$name = $scriptProperties['name'];
$description = $scriptProperties['description'];

/* @TODO - remove that */
//$manager = $modx->getManager();
//$manager->createObjectContainer('ClicheItems');
//$manager->createObjectContainer('ClicheAlbums');

/* Is this name already taken ? */
$alreadyExist = $modx->getObject('ClicheAlbums',array(
	'name' => $name
));
if($alreadyExist){
	$errors[] = array(
		'name' => 'name',
		'msg' => $modx->lexicon('cliche.error_album_create_name_already_taken'),
	);
	return $modx->error->failure($modx->lexicon('cliche.error_album_not_created'), $errors);
}

/* Create the new album */
$newAlbum = $modx->newObject('ClicheAlbums');
$newAlbum->set('name', $name);
$newAlbum->set('description', $description);
if($newAlbum->save()){
	return $modx->error->success($modx->lexicon('cliche.album_created_succesfully'), $newAlbum);
}