<?php
/**
 * Output TV render for Cliche thumb TV's
 *
 * @package cliche
 * @subpackage tv
 */

if (!empty($value) && $value != '{}') {
    $data = $modx->fromJSON($value);
    if (empty($data)){
		$value = '';
		return $value;
	}
	
	$thumb = $modx->cliche->config['phpthumb'];
	$thumb .= '&amp;w='.$params['thumbwidth'];
	$thumb .= '&amp;h='.$params['thumbheight'];
	$thumb .= '&amp;sx='.$data['x'];
	$thumb .= '&amp;sy='.$data['y'];
	$thumb .= '&amp;sw='.$data['sw'];
	$thumb .= '&amp;sh='.$data['sh'];
	$thumb .= '&amp;iar=1';
	$thumb .= '&amp;q=90';
	$thumb .= '&amp;src='.urlencode('http://localhost/'.$data['src']);
	
	$value = $thumb;

} else { /* if empty dont return json, return blank */
    $value = '';
}
return $value;