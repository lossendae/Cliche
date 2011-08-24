<?php
/**
 * Renders the properties form
 *
 * @package cliche
 */
// $modx->lexicon->load('cliche:default');

/* fetch only the gmtv lexicon */
// $lang = $modx->lexicon->fetch();
// $glang = array();
// foreach ($lang as $k => $v) {
    // if (strpos($k,'gmtv') !== false) {
        // $glang[str_replace('gmtv.','',$k)] = $v;
    // }
// }
// $modx->smarty->assign('lang',$glang);

/* fix revo rc2 bug with settings (can be removed after RC-3) */
$settings = array();
if (!empty($scriptProperties['tv'])) {
    $tv = $modx->getObject('modTemplateVar',$scriptProperties['tv']);
    if ($tv != null) {
        $params = $tv->get('display_params');
        $ps = explode('&',$params);
        foreach ($ps as $p) {
            $param = explode('=',$p);
            if ($p[0] != '') {
                $v = $param[1];
                if ($v == 'true') $v = 1;
                if ($v == 'false') $v = 0;
                $settings[$param[0]] = $v;
            }
        }
    }
    $modx->smarty->assign('tv',$scriptProperties['tv']);
}
$modx->smarty->assign('params',$modx->toJSON($settings));

$corePath = $modx->getOption('cliche.core_path',null,$modx->getOption('core_path').'components/cliche/');
return $modx->smarty->fetch($corePath.'elements/tv/thumb.properties.tpl');