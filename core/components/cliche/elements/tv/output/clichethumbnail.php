<?php
/**
 * Output TV render for Cliche thumbnail TV
 *
 * @package cliche
 * @subpackage tv
 */
$cliche = $modx->getService('cliche','Cliche',$modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/').'model/cliche/',$scriptProperties);
if (!($cliche instanceof Cliche)) return 'Could not load Cliche class';

if (!empty($value) && $value != '{}') {
    $data = $modx->fromJSON($value);
    if (empty($data)){
        $value = '';
        return $value;
    }
    /* set the cache file as src */
    $value = '<img src="'. $data['thumbnail'] .'" alt="thumbnail" />';

} else { /* if empty return blank */
    $value = '';
}
return $value;