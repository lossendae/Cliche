<?php
/**
 * @package cliche
 * @subpackage request
 */
interface ClichePluggable{
    public function addPlugin( ClichePlugin $objPlugin, $event);

    public function fireEvent( $event );
}

interface ClichePlugin{
    public function notify( ClichePluggable $objController, Event $objEvent );
}

