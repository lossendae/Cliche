<?php
$data['id'] = $_REQUEST['id'];
$data['name'] = $_REQUEST['name'];
$data['description'] = $_REQUEST['description'];

$album = $modx->getObject('ClicheAlbums', $data['id']);
$album->fromArray( $data );
if($album->save()){
	return $modx->error->success('Album updated succesfully', $album);
}