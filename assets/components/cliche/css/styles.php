<?php  
/**
 * Reloaded build script
 *
 * @package reloaded
 * @subpackage assets
 */
ob_start();  

$files = array(  
    'mgr.less',    
    // 'styles.less',   
    'cleanup.less',    
    'breadcrumbs.less',    
    'form.less',   
    'buttons.less',    
    'window.less',    
    'tab.less',	
    'fileuploader.less',	
    'fix.less',	
); 

$dest = 'styles.css';

$time = mktime(0,0,0,21,5,1980);
header('Content-type: text/css');  
header('Last-Modified: ' . gmdate("D, d M Y H:i:s",$time) . " GMT"); 

require dirname(__FILE__) .'/less/lessc.inc.php';
$lc = new lessc();
$css = '';
foreach($files as $file){  
	$css .= file_get_contents($file);  
}
$css = $lc->parse($css);

/* Put the processed css styles.css */
file_put_contents($dest, $css);

/* Echo the processed css */
echo $css;