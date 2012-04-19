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

        $corePath = $this->modx->getOption('core_path').'components/cliche/';
        $assetsUrl = $this->modx->getOption('assets_url').'components/cliche/';
        $assetsPath = $this->modx->getOption('assets_path').'components/cliche/';

        $this->config = array_merge(array(
            'core_path' => $corePath,
            'model_path' => $corePath.'model/',
            'processors_path' => $corePath.'processors/',
            'controllers_path' => $corePath.'controllers/',
            
            'assets_path' => $assetsPath,            
            'images_path' => $assetsPath.'albums/',
            'cache_path' => $assetsPath.'cache/',
            
            'assets_url' => $assetsUrl,
            'images_url' => $assetsUrl.'albums/',
            'cache_url' => $assetsUrl.'cache/',
            'css_url' => $assetsUrl.'css/',
            
            'plugins_path' => $corePath.'controllers/web/plugins/',
            'plugins_url' => $assetsUrl.'plugins/',
            
            'connector_url' => $assetsUrl.'connector.php',
            
            'mgr_thumb_mask' => 'mgr-thumb-75x103.jpg',
            'phpthumb' => $assetsUrl.'connector.php?action=web/phpthumb&src=',
            'thumb' => $assetsUrl.'connector.php?action=web/thumb',
            
            'use_filebased_chunks' => 0,            
            'tpl_suffix' => '.tpl',    
                
            'request_file_var' => 'name',
            'allowed_extension' => 'jpg,jpeg,gif,png,zip',
            
            //Debug is on for development
            'debug' => false,
        ), $config);

        $this->modx->addPackage('cliche',$this->config['model_path']);
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('cliche:default');
        }

        $this->initDebug();
    }
    
    /**
    * Load debugging settings
    */
    public function initDebug() {
        if ($this->modx->getOption('debug',$this->config,false)) {
            error_reporting(E_ALL); ini_set('display_errors',true);
            $this->modx->setLogTarget('HTML');
            $this->modx->setLogLevel(modX::LOG_LEVEL_ERROR);

            $debugUser = !isset($this->config['debugUser']) ? $this->modx->user->get('username') : 'anonymous';
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
        /* We don't use modx->loadClass cause it put every chars on lower case causing phpThumb to fail lamentably */
        require_once $this->config['model_path'] . 'cliche/helpers/phpthumb/ThumbLib.class.php';
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
        if ($this->modx->loadClass('clicheController',$this->config['controllers_path'],true,true)) {
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