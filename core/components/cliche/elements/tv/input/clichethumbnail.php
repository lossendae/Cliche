<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$cliche = $modx->getService('cliche','Cliche',$modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/').'model/cliche/',null);
if (!($cliche instanceof Cliche)) return 'Could not load Cliche class';
$corePath = $modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/');

// handles image fields using htmlarea image manager
$this->xpdo->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

$config = $this->get('output_properties');
$config['tv'] = $this->id;
$config['resource'] = (int) $_REQUEST['id'];
$json = $modx->toJSON($config);
$modx->smarty->assign('configjson',$json);
$data = array();

$value =  $modx->fromJSON($this->value);
if(!empty($value)){
    $item = $modx->getObject('ClicheItems', $value['id']);
    if($item){
        $data['timestamp'] = strtotime('now');
        $data = array_merge($data, $value);
    }
    /* Reload thumbnail if size the TV has been changed ? */
    /*if($data['thumbwidth'] != $config['thumbwidth'] || $data['thumbheight'] != $config['thumbheight']){
        $item = $modx->getObject('ClicheItems', $data['id']);

        $fileName = str_replace(' ', '_', $item->get('name'));
        $mask = $fileName .'-'. $data['thumbwidth'] .'x'. $data['thumbheight'] .'-doc'. $data['resource'] .'-tv'. $data['tv'] .'.png';

        $file = $item->getCacheDir() . $mask;
        $thumb = $item->loadThumbClass( $modx->cliche->config['images_path'] . $item->get('filename'), array(
            'resizeUp' => true,
        ));
        $thumb->adaptiveResize($data['thumbwidth'], $data['thumbheight']);
        $thumb->save($file, 'png');

        $data['image'] = $item->get('image');
        $data['thumbnail'] = $item->getCacheDir(false) . $mask;
    }*/
}
$json = $modx->toJSON($data);
$modx->smarty->assign('itemjson',$json);

return $modx->smarty->fetch($corePath.'elements/tv/clichethumbnail.input.tpl');