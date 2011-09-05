<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$cliche = $modx->getService('cliche','Cliche',$modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/').'model/cliche/',$scriptProperties);
if (!($cliche instanceof Cliche)) return 'Could not load Cliche class';
$corePath = $modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/');

// handles image fields using htmlarea image manager
$this->xpdo->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

$op = $this->get('output_properties');
$data['twidth'] = $op['thumbwidth'];
$data['theight'] = $op['thumbheight'];
$data['tv_id'] = $this->id;
$data['resourceId'] = (int) $_REQUEST['id'];
$value =  $modx->fromJSON($this->value);
if(!empty($value)){
    $item = $modx->getObject('ClicheItems', $value['id']);
    if($item){
        $data['phpthumb'] = $cliche->config['phpthumb'] . $cliche->config['images_url'] . $item->filename;
        $data['timestamp'] = strtotime('now');
        $data = array_merge($data, $value);
    }
}
$json = $modx->toJSON($data);
$modx->smarty->assign('itemjson',$json);

return $modx->smarty->fetch($corePath.'elements/tv/clichethumbnail.input.tpl');