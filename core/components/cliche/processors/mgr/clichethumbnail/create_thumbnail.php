<?php
$response['success'] = false;

/* Get item datas */
if(!empty($scriptProperties['id'])){
    $item = $modx->getObject('ClicheItems', $scriptProperties['id']);
    if($item){
        $crop  = $modx->getOption('crop', $scriptProperties, false);
        $reload  = $modx->getOption('reload', $scriptProperties, false);

        $fileName = str_replace(' ', '_', $item->get('name'));
        $mask = $fileName .'-'. $scriptProperties['thumbwidth'] .'x'. $scriptProperties['thumbheight'] .'-doc'. $scriptProperties['resource'] .'-tv'. $scriptProperties['tv'] .'.png';

        $file = $item->getCacheDir() . $mask;
        if(!file_exists($file) || $reload){
            $thumb = $item->loadThumbClass( $modx->cliche->config['images_path'] . $item->get('filename'), array(
                'resizeUp' => true,
             ));
            if($crop || $crop == 'true'){
                 $thumb->cropCustom(
                    $scriptProperties['x'],
                    $scriptProperties['y'],
                    $scriptProperties['w'],
                    $scriptProperties['h'],
                    $scriptProperties['thumbwidth'],
                    $scriptProperties['thumbheight']
                );
            } else {
                 $thumb->adaptiveResize($scriptProperties['thumbwidth'], $scriptProperties['thumbheight']);
            }
            $thumb->save($file, 'png');
        }

        $image['image'] = $item->get('image');
        $image['thumbnail'] = $item->getCacheDir(false) . $mask;
        $image['timestamp'] = strtotime('now');
    }
    $result = array_merge($scriptProperties, $image);

    unset($result['HTTP_MODAUTH']);
    unset($result['action']);
    unset($result['ctx']);
    unset($result['load_jquery']);
    unset($result['keep_aspect_ratio']);
    unset($result['crop']);
    unset($result['reload']);

    $response['success'] = true;
    $response['data'] = $result;
}
return $modx->toJSON($response);