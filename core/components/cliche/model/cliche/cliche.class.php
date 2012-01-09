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
 *  - Moving Cliche from development to live is hard
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
     * @access public
     * @var phpThumbFactory A reference to the phpThumb object.
     */
    public $phpThumb = null;

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
            'cache_path' => $assets_path.'cache/',
			'plugins_path' => $assets_path.'plugins/',
			
			'assets_url' => $assets_url,
			'images_url' => $assets_url.'albums/',
            'cache_url' => $assets_url.'cache/',
            'css_url' => $assets_url.'css/',
			'plugins_url' => $assets_url.'plugins/',
			
            'connector_url' => $assets_url.'connector.php',
			
			'mgr_thumb_mask' => 'mgr-thumb-75x103.jpg',
			'phpthumb' => $assets_url.'connector.php?action=web/phpthumb&src=',
			'thumb' => $assets_url.'connector.php?action=web/thumb',
			'chunks_prefix' => 'Cliche',
			
			'use_filebased_chunks' => true,			
			'tpl_suffix' => '.tpl',	
				
			'request_file_var' => 'name',
			
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
     * Load an helper class to load phpThumb Class
     *
     * @access public
     * @return string The JSON response
     */	
	public function loadPhpThumb($src, $options = array()){
        if (!$this->modx->loadClass('cliche.helpers.phpthumb.ThumbLib',$this->config['model_path'],true,true)) {
			$this->modx->log(modX::LOG_LEVEL_ERROR,'[Cliche phpThumb] Could not load PhpThumbFactory');
		}
		$this->phpThumb = PhpThumbFactory::create( $src, $options );
        return $this->phpThumb;
	}

	/**
     * Load an helper class to handle file upload via Ajax
     *
     * @access public
     * @return string The JSON response
     */
	public function loadHelper($id){
		if (!$this->modx->loadClass('cliche.helpers.FileUploader',$this->config['model_path'],true,true)) {
			$this->modx->log(modX::LOG_LEVEL_ERROR,'[Cliche] Could not load upload helper');
			return 'Could not load helper class FileUploader.';
		}
		$uploader = new FileUploader($this, $this->config);
		$result = $uploader->handleUpload($id);
		return $result;
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