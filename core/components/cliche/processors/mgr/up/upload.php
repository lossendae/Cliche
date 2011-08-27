<?php
$name = $scriptProperties['name'];

if (!$modx->loadClass('cliche.helpers.FileUploader',$modx->cliche->config['model_path'],true,true)) {
	return $modx->error->failure('Could not load helper class FileUploader.');
}
$uploader = new FileUploader($modx->cliche);
$result = $uploader->handleUpload($modx->cliche->config['images_path'], 10);
return $modx->toJSON($result);	