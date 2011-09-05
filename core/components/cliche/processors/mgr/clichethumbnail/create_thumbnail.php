<?php
$response['success'] = false;
/* Get item datas */
if(!empty($scriptProperties['id'])){
    $item = $modx->getObject('ClicheItems', $scriptProperties['id']);
    if($item){
        $scriptProperties['crop'] = true;
        $scriptProperties['mask'] = 'doc-'. $scriptProperties['resourceId'] .'-thumbnail';
        $resource = $modx->getObject('modResource', $scriptProperties['resourceId']);
        if($resource){
            $alias = $resource->get('alias');
            if(!empty($alias)){
                $scriptProperties['mask'] = $alias .'-thumbnail';
            }
        }        

        $image['thumbnail'] = $item->getImage($scriptProperties);
        $image['image'] = $item->get('image');
        $image['id'] = $item->get('id');
        $image['tv'] = $scriptProperties['tv'];

        $image['timestamp'] = strtotime('now');
    }
    $result = array_merge($scriptProperties, $image);
    $response['success'] = true;
    $response['image'] = $result;
}
return $modx->toJSON($response);