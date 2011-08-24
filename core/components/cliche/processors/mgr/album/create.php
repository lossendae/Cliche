<?php
$name = $_REQUEST['name'];
$description = $_REQUEST['description'];

/* @TODO - remove that */
$manager = $modx->getManager();
$manager->createObjectContainer('ClicheItems');
$manager->createObjectContainer('ClicheAlbums');

/* Is this name already taken ? */
$alreadyExist = $modx->getObject('ClicheAlbums',array(
	'name' => $name
));
if($alreadyExist){
	$errors[] = array(
		'name' => 'name',
		'msg' => 'Ce nom d\'album existe déjà! Choisissez un autre nom',
	);
	// $errors[] = array(
		// 'name' => 'description',
		// 'msg' => 'test',
	// );
	return $modx->error->failure('L\'album n\'a pas pû être crée', $errors);
}

/* Create the new album */
$newAlbum = $modx->newObject('ClicheAlbums');
$newAlbum->set('name', $name);
$newAlbum->set('description', $description);
$newAlbum->set('createdon', 'now');
$newAlbum->set('createdby', $modx->user->get('id'));
if($newAlbum->save()){
	return $modx->error->success('Album created succesfully', $newAlbum);
}