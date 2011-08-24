<?php
/**
 * @package cliche
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/clicheitems.class.php');
class ClicheItems_mysql extends ClicheItems {
    function ClicheItems_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>