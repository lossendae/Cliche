<?php
/**
 * @package cliche
 */
class ClicheItems extends xPDOSimpleObject {
    function ClicheItems(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>