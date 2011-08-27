<?php
/**
 * @package cliche
 * @subpackage request
 */
abstract class ClicheController {
    /** @var modX $modx */
    public $modx;
    /** @var Cliche $cliche */
    public $cliche;
    /** @var array $config */
    public $config = array();
    /** @var array $scriptProperties */
    protected $scriptProperties = array();

    protected $placeholders = array();

    /**
     * @param Cliche $cliche A reference to the Cliche instance
     * @param array $config
     */
    function __construct(Cliche &$cliche,array $config = array()) {
        $this->cliche =& $cliche;
        $this->modx =& $cliche->modx;
        $this->config = array_merge($this->config,$config);
    }

    public function run($scriptProperties) {
        $this->setProperties($scriptProperties);		
        $this->initialize();    
		$this->_setChunksPath();		
        return $this->process();
    }

    abstract public function initialize();
    abstract public function process();

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
     * _setChunksPath.
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
		
		$config['chunks_path'] = $config['plugins_path'] . $this->getProperty('display') . '/';
		$config['chunks_url'] = $config['plugins_url'] . $this->getProperty('display') . '/';
		
		$this->config = array_merge($this->config, $config);	
		$this->cliche->config = $this->config;	
	}
	
	public function getChunk($name, $properties = array()){
		return $this->cliche->getChunk($name, $properties);
	}
	
	public function regClientStartupScript($script){
		$this->modx->regClientStartupScript($this->config['chunks_url'] . $script);
	}
	public function regClientScript($script){
		$this->modx->regClientScript($this->config['chunks_url'] . $script);
	}
}