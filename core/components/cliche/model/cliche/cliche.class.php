<?php
/**
 * Cliche
 *
 *
 * @package cliche
 */
/**
 * Main class file for Cliche.
 *
 * @TODO
 *
 *  From Shaun's remarks :
 *
 *  - Moving Gallery from development to live is hard
 *  - Managing gallery items isn't always easy (no custom fields on gallery items)
 *  - There's no real easy, intuitive way to create and customize a gallery per resource
 *
 * @author Stephane Boulard <lossendae@gmail.com>
 * @package cliche
 */
class Cliche {
    /**
     * @access protected
     * @var array A collection of preprocessed chunk values.
     */
    protected $chunks = array();
    /**
     * @access public
     * @var modX A reference to the modX object.
     */
    public $modx = null;
    /**
     * @access public
     * @var array A collection of properties to adjust Cliche behaviour.
     */
    public $config = array();

    /**
     * The Cliche Constructor.
     *
     * This method is used to create a new Cliche object.
     *
     * @param modX &$modx A reference to the modX object.
     * @param array $config A collection of properties that modify Cliche
     * behaviour.
     * @return Cliche A unique Cliche instance.
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx =& $modx;

        $core = $this->modx->getOption('core_path').'components/cliche/';
        $assets_url = $this->modx->getOption('assets_url').'components/cliche/';
        $assets_path = $this->modx->getOption('assets_path').'components/cliche/';

        $this->config = array_merge(array(
            'core_path' => $core,
            'model_path' => $core.'model/',
            'processors_path' => $core.'processors/',
            'controllers_path' => $core.'controllers/',

            
            'assets_path' => $assets_path,            
            'images_path' => $assets_path.'albums/',
			'plugins_path' => $assets_path.'plugins/',
			
			'assets_url' => $assets_url,
			'images_url' => $assets_url.'albums/',
            'css_url' => $assets_url.'css/',
			'plugins_url' => $assets_url.'plugins/',
			
            'connector_url' => $assets_url.'connector.php',
			
			'phpthumb' => $assets_url.'connector.php?action=web/phpthumb&src=',					
			'chunks_prefix' => 'Cliche',
			
			'controller' => 'default',	
			'use_filebased_chunks' => true,			
			'tpl_suffix' => '.tpl',									
            
			/* Uploader properties */
            'allowedExtensions' => '',
            'sizeLimit' => 2097152,
            'replaceOldFile' => false,
            'requestFileVar' => 'qqfile',
			
			//Debug is on for development
			'debug' => true,
        ), $config);

        $this->modx->addPackage('cliche',$this->config['model_path']);
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('cliche:default');
        }

        /* load debugging settings */
        if ($this->modx->getOption('debug',$this->config,false)) {
            error_reporting(E_ALL); ini_set('display_errors',true);
            $this->modx->setLogTarget('HTML');
            $this->modx->setLogLevel(MODX_LOG_LEVEL_ERROR);

            $debugUser = $this->config['debugUser'] == '' ? $this->modx->user->get('username') : 'anonymous';
            $user = $this->modx->getObject('modUser',array('username' => $debugUser));
            if ($user == null) {
                $this->modx->user->set('id',$this->modx->getOption('debugUserId',$this->config,1));
                $this->modx->user->set('username',$debugUser);
            } else {
                $this->modx->user = $user;
            }
        }
    }

    /**
     * Initializes Cliche into different contexts.
     *
     * @access public
     * @param string $ctx The context to load. Defaults to web.
     */
    public function initialize($ctx = 'web') {
		if ($ctx == 'mgr'){
			if (!$this->modx->loadClass('cliche.request.ClicheControllerRequest',$this->config['model_path'],true,true)) {
				return 'Could not load controller request handler.';
			}
			$this->request = new ClicheControllerRequest($this);
			return $this->request->handleRequest();
        } else {
			$this->_setChunksPath();
		}
    }
		 
    /**
     * Get a modx configuration option a remove the entry from the config to allow multiple instance call with different parameters.
     *
     * @param string $key The option key.
     * @param array $options A set of options to override those from xPDO.
     * @param mixed $default An optional default value to return if no value is found.
     * @return mixed The configuration option value.
     */
	private function getOption($key, $options = null, $default = null){
		
		$value = $this->modx->getOption($key, $options, $default);
		//Delete from main config
		if(array_key_exists($key, $this->config)){			
			unset($this->config[$key]);
		}
		return $value;
	}
	
	/**
     * Load an helper class to handle file upload via Ajax
     *
     * @access public
     * @return string The JSON response
     */	
	public function loadHelper(){
		if (!$this->modx->loadClass('cliche.helpers.FileUploader',$this->config['model_path'],true,true)) {
			return 'Could not load helper class FileUploader.';
		}
		$uploader = new FileUploader($this);
		$result = $uploader->handleUpload($this->config['items_path']);
		return $this->modx->toJSON($result);	
	}
	
	/**
     * setPathToFileBasedChunks.
     *
     * Convert string params to path for use in file based chunks
     *
	 * @access private
     */
	private function _setChunksPath(){			
		$config = str_replace(array(
			'{base_path}',
			'{assets_path}',
		),array(
			$this->modx->getOption('base_path'),
			$this->modx->getOption('assets_path'),
		), $this->config);
		
		$config['chunks_path'] = $config['plugins_path'] . $config['controller'] . '/';
		$config['chunks_url'] = $config['plugins_url'] . $config['controller'] . '/';
		
		$this->config = array_merge($this->config, $config);	
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
        /* first check internal cache */
        if (!isset($this->chunks[$name])) {
			if (!$this->config['use_filebased_chunks']) {
				$objectName = $this->config['chunks_prefix'] . ucfirst($name) . 'Tpl';
                $chunk = $this->modx->getObject('modChunk',array('name' => $objectName));
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

	public function loadProcessor($name){
		return require_once($this->config['processors_path'].$name.'.php');
	}
	
	/**
     * Load the appropriate controller
     * @param string $controller
     * @return null|clicheController
     */
    public function loadController($controller) {
        if ($this->modx->loadClass('clicheController',$this->config['model_path'].'cliche/request/',true,true)) {
            $classPath = $this->config['controllers_path'].'web/'.$controller.'.php';
            $className = $controller.'Controller';
            if (file_exists($classPath)) {
                if (!class_exists($className)) {
                    $className = require_once $classPath;
                }
                if (class_exists($className)) {
                    $this->controller = new $className($this,$this->config);
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[Cliche] Could not load controller: '.$className.' at '.$classPath);
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[Cliche] Could not load controller file: '.$classPath);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Cliche] Could not load clicheController class.');
        }
        return $this->controller;
    }
}