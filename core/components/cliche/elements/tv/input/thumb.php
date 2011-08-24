<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');
$corePath = $modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/');

// handles image fields using htmlarea image manager
$this->xpdo->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

//Assign TV value & thumb display params to TV
if (!empty($this->value)) {
    $data = $modx->fromJSON($this->value);
	
	$tv = $modx->getObject('modTemplateVar', $this->id);
	if ($tv != null) {
		$params = $tv->get('display_params');
		$ps = explode('&',$params);
		foreach ($ps as $p) {
			$param = explode('=',$p);
			if ($p[0] != '') {
				$v = $param[1];
				if ($v == 'true') $v = 1;
				if ($v == 'false') $v = 0;
				$thumb[$param[0]] = $v;
			}
		}
	}
	$data['twidth'] = $thumb['thumbwidth'];
	$data['theight'] = $thumb['thumbheight'];
	$data['relativeUrl'] = $data['relativeUrl'].'&w='.$thumb['thumbwidth'].'&h='.$thumb['thumbheight'];
	
	$json = $modx->toJSON($data);
	$modx->smarty->assign('itemjson',$json);
}
else{
	$tv = $modx->getObject('modTemplateVar', $this->id);
	if ($tv != null) {
		$params = $tv->get('display_params');
		$ps = explode('&',$params);
		foreach ($ps as $p) {
			$param = explode('=',$p);
			if ($p[0] != '') {
				$v = $param[1];
				if ($v == 'true') $v = 1;
				if ($v == 'false') $v = 0;
				$thumb[$param[0]] = $v;
			}
		}
	}
	$data['twidth'] = $thumb['thumbwidth'];
	$data['theight'] = $thumb['thumbheight'];
	
	$json = $modx->toJSON($data);
	$modx->smarty->assign('itemjson',$json);
}
return $modx->smarty->fetch($corePath.'elements/tv/thumb.input.tpl');