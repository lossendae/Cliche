<?php
/**
 * @package cliche
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/clichealbums.class.php');
class ClicheAlbums_mysql extends ClicheAlbums {
    function ClicheAlbums_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>