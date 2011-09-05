<?php
/**
 * @package cliche
 * @subpackage plugin
 */
class Galleriffic implements ClichePlugin {
    /** @var modX $modx */
    public $modx;

    /**
     * @param modX $modx A reference to the modx object
     */
    function __construct(modX &$modx) {
        $this->modx =& $modx;
    }

     /**
     * Notify the plugin of an event
     *
     * @param string $event The event name
     * @param object $obj A reference to the Controller object
     * @return void
     */
    public function notify( $event, &$obj ){
        $pluginDirectory = strtolower($obj->getProperty('plugin')) .'/';
        switch($event){
            case 'load':
                $obj->setProperty('loadCSS', true);
                $obj->setProperty('css', 'style');
                $obj->setProperty('columns', 0);
                $obj->config['chunks_path'] = $obj->config['plugins_path'] . $pluginDirectory;
                $obj->config['chunks_url'] = $obj->config['plugins_url'] . $pluginDirectory;

                /* Load javascript */
                $obj->regClientStartupScript('libs/jquery.galleriffic.js');
                $obj->regClientStartupScript('libs/jquery.history.js');
                $obj->regClientStartupScript('libs/jquery.opacityrollover.js');

                /* Launch script before body closing tag */
                $script = $obj->getChunk('script');
                $this->modx->regClientHTMLBlock('<script type="text/javascript">'.$script.'</script>');
                break;
            default:
                break;
        }
        return;
    }

     /**
     * Notify the plugin of an event with some arguments passed to it
     *
     * @param string $event The event name
     * @param mixed $args The data passed to the plugin class
     * @param object $obj A reference to the Controller object
     * @return mixed $args The processed arguments
     */
    public function handle( $event, $args, &$obj ){
        return $args;
    }
}