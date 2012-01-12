<?php
$response['success'] = false;

$image = $modx->getObject('ClicheItems', $scriptProperties['id']);
$image->fromArray( $scriptProperties );
if($image->save()){
	$response['data'] = array(
		'id' => $image->album_id,
	);
	$response['success'] = true;
}
return $modx->toJSON($response);