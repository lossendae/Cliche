<?php
/**
 * @package cliche
 */
class ClicheAlbums extends xPDOSimpleObject {
    function ClicheAlbums(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>