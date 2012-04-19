<?php
$id = $scriptProperties['id'];

if(!empty($id)){
    $album = $modx->getObject('ClicheAlbums', $id);
    
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