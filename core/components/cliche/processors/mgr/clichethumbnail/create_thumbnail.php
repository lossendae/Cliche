<?php
$response['success'] = false;
$data =& $scriptProperties;

/* Get item datas */
if(!empty($data['id'])){
    $item = $modx->getObject('ClicheItems', $data['id']);
    if($item){
        $crop  = $modx->getOption('crop', $data, false);
        $reload  = $modx->getOption('reload', $data, false);

        $fileName = str_replace(' ', '_', $item->get('name'));
        $mask = $fileName .'-'. $data['thumbwidth'] .'x'. $data['thumbheight'] .'-doc'. $data['resource'] .'-tv'. $data['tv'] .'.png';

        $file = $item->getCacheDir() . $mask;
        if(!file_exists($file) || $reload){
            $thumb = $item->loadThumbClass( $modx->cliche->config['images_path'] . $item->get('filename'), array(
                'resizeUp' => true,
             ));
            if($crop || $crop == 'true'){
                 $thumb->cropCustom(
                    $data['x'],
                    $data['y'],
                    $data['w'],
                    $data['h'],
                    $data['thumbwidth'],
                    $data['thumbheight']
                );
            } else {
                 $thumb->adaptiveResize($data['thumbwidth'], $data['thumbheight']);
            }
            $thumb->save($file, 'png');
        }

        $image['image'] = $item->get('image');
        $image['thumbnail'] = $item->getCacheDir(false) . $mask;
        $image['timestamp'] = strtotime('now');
    }
    $result = array_merge($data, $image);

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