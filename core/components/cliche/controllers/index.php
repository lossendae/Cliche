<?php
/**
 * @package cliche
 * @subpackage controllers
 */
require_once dirname(dirname(__FILE__)).'/model/cliche/cliche.class.php';
$cliche = new Cliche($modx);
return $cliche->initialize('mgr');