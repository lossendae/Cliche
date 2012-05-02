<?php
/**
 * @package cliche
 * @subpackage request
 */
abstract class ClichePlugin {
    /** @var modX $modx */
    public $modx;
    /** @var $view */
    public $controller;

    /**
     * @param Cliche $cliche A reference to the Cliche instance
     * @param array $config
     */
    function __construct(&$controller) {
        $this->controller =& $controller;
        $this->modx =& $controller->modx;
    }
    
     /**
     * Set an option for this module
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setProperty($key,$value) {
        $this->controller->setProperty($key,$value);
    }
    /**
     * Set an array of options
     * @param array $array
     * @return void
     */
    public function setProperties($array) {
        $this->controller->setProperties($array);
    }

    /**
     * Return an array of REQUEST options
     * @return array
     */
    public function getProperties() {
        return $this->controller->scriptProperties;
    }

    /**
     * @param $key
     * @param null $default
     * @param string $method
     * @return mixed
     */
    public function getProperty($key,$default = null,$method = 'isset') {
        return $this->controller->getProperty($key,$default,$method);
    }
}
/**
 * @package cliche
 * @subpackage request
 */
abstract class ClicheController {
    /** @var modX $modx */
    public $modx;
    /** @var Cliche $cliche */
    public $cliche;
    /** @var Plugin $plugin */
    public $plugin;
    /** @var array $config */
    public $config = array();
    /** @var array $scriptProperties */
    protected $scriptProperties = array();

    protected $placeholders = array();  
    
    abstract public function initialize();
    abstract public function process();

    /**
     * @param Cliche $cliche A reference to the Cliche instance
     * @param array $config
     */
    function __construct(Cliche &$cliche,array $config = array()) {
        $this->cliche =& $cliche;
        $this->modx =& $cliche->modx;
        $this->config = array_merge($this->config,$config);
    }

    /**
     * Run the current instance of the controller
     * @param array $scriptProperties
     * @return string The processed content
     */
    public function run($scriptProperties) {
        $this->setProperties($scriptProperties);        
        $this->loadPlugin();   
        $this->initialize();
        /* chunks_path is not overridable by script properties - better for multi instance call */
        $chunksPath = isset( $this->config['chunks_path'] ) ? $this->config['chunks_path'] :  null;
        if(empty($chunksPath)){
            $this->_setDefaultChunksPath();
        }
        return $this->process();
    }

    /**
     * Load a class plugin if specified in scriptProperties
     * @return void
     */
    public function loadPlugin(){
        $plugin = $this->getProperty('plugin');
        if(!empty($plugin)){
            if (!$this->modx->loadClass("{$plugin}.{$plugin}", $this->config['plugins_path'],true,true)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[Cliche] Could not load '.$plugin.' plugin in: '. $this->config['plugins_path'] . $plugin .'/'. $plugin .'.class.php');
            }
            $className = empty($plugin) || $plugin == 'default' ? 'DefaultPlugin' : ucfirst($plugin);
            $this->plugin = new $className($this);
        }
    }

    /**
     * Set the default options for this module
     * @param string $event The event name
     * @param mixed $args Optional arguments to pass to the plugin class
     * @return mixed/void if $args is not supplied else the processed $args
     */
    public function fireEvent($event, $args = null){    
        if(isset($this->plugin) && is_object($this->plugin)){
            if(is_callable(array($this->plugin, $event))){
                if($args == null){
                    call_user_func( array($this->plugin, $event) );
                    return true;
                } else {
                    return call_user_func_array( array($this->plugin, $event), $args );
                }
            }            
        }    
        return false;
    }

    /**
     * Load a css file in the header if specified in properties
     * @return void
     */
    public function loadCSS($name) {
        $this->modx->regClientCSS($this->config['plugin_assets_url'] . $name .'.css');
    }    

    /**
     * Set the default options for this module
     * @param array $defaults
     * @return void
     */
    protected function setDefaultProperties(array $defaults = array()) {
        $this->scriptProperties = array_merge($defaults,$this->scriptProperties);
    }

    /**
     * Set an option for this module
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setProperty($key,$value) {
        $this->scriptProperties[$key] = $value;
    }
    /**
     * Set an array of options
     * @param array $array
     * @return void
     */
    public function setProperties($array) {
        foreach ($array as $k => $v) {
            $this->setProperty($k,$v);
        }
    }

    /**
     * Return an array of REQUEST options
     * @return array
     */
    public function getProperties() {
        return $this->scriptProperties;
    }

    /**
     * @param $key
     * @param null $default
     * @param string $method
     * @return mixed
     */
    public function getProperty($key,$default = null,$method = 'isset') {
        $v = $default;
        switch ($method) {
            case 'empty':
            case '!empty':
                if (!empty($this->scriptProperties[$key])) {
                    $v = $this->scriptProperties[$key];
                }
                break;
            case 'isset':
            default:
                if (isset($this->scriptProperties[$key])) {
                    $v = $this->scriptProperties[$key];
                }
                break;
        }
        return $v;
    }

    public function setPlaceholder($k,$v) {
        $this->placeholders[$k] = $v;
    }
    public function getPlaceholder($k,$default = null) {
        return isset($this->placeholders[$k]) ? $this->placeholders[$k] : $default;
    }
    public function setPlaceholders($array) {
        foreach ($array as $k => $v) {
            $this->setPlaceholder($k,$v);
        }
    }
    public function getPlaceholders() {
        return $this->placeholders;
    }

    /**
     * @param string $processor
     * @param array $scriptProperties
     * @return mixed|string
     */
    public function runProcessor($processor,array $scriptProperties = array()) {
        $output = '';
        $processorFile = $this->config['processorsPath'].$processor.'.php';
        if (!file_exists($processorFile)) {
            return $output;
        }

        $modx =& $this->modx;
        $cliche =& $this->cliche;
        try {
            $output = include $processorFile;
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Cliche] '.$e->getMessage());
        }
        return $output;
    }
    
    /**
     * Convert string params to path for use in file based chunks
     *
     * @access private
     */
    private function _setDefaultChunksPath(){
        $config = str_replace(array(
            '{base_path}',
            '{assets_path}',
        ),array(
            $this->modx->getOption('base_path'),
            $this->modx->getOption('assets_path'),
        ), $this->config);
        
        $config['chunks_path'] = $config['plugins_path'] . $this->getProperty('plugin') . '/';
        $config['plugin_assets_url'] = $config['plugins_url'] . $this->getProperty('plugin') . '/';
        
        $this->config = array_merge($this->config, $config);    
        $this->cliche->config = $this->config;    
    }    
    
    /**
     * Processes the content of a chunk in either of the following ways:
     *
     * Caches the preprocessed chunk content to an array to speed loading
     * times, especially when looping through collections.
     *
     * @access public
     * @param string $name The name of the chunk to process
     * @param array $properties (optional) An array of properties
     * @return string The processed content string
     */
    public function getChunk($name, $properties = array()) {
        $chunk = null;
        $phs = $this->getPlaceholders();
        $properties = array_merge($properties, $phs);
        /* first check internal cache */
        if (!isset($this->chunks[$name])) {
            $useFileBasedChunks = $this->getProperty('use_filebased_chunks');
            if (!$useFileBasedChunks) {
                $chunk = $this->modx->getObject('modChunk',array('name' => $name));
            }
            if (empty($chunk)) {    
                $chunk = $this->_getTplChunk($name);
                if(!is_object($chunk)) return $chunk;
            }                
            $this->chunks[$name] = $chunk->getContent();
        } else { /* load chunk from cache */
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }
    
    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
     * @return modChunk/string Returns the modChunk object if found, otherwise an error message with the chunk name
     */
    private function _getTplChunk($name){
        $f = $this->config['chunks_path'] . strtolower($name) . $this->config['tpl_suffix'];
        if (file_exists($f)) {
            $o = file_get_contents($f);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name', $name);
            $chunk->setContent($o);
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Cliche] Chunk : "'.$f.'" not found');
            return 'Chunk "<strong>'.$f.'</strong>" not found';
        }
        return $chunk;
    }
}