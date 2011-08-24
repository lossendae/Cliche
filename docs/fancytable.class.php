<?php

class FancyTable {
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
     * @var array A collection of properties to adjust MakeTable behaviour.
     */
    public $config = array();
	
	/**
     * The MakeTable Constructor.
     *
     * This method is used to create a new MakeTable object.
     *
     * @param modX &$modx A reference to the modX object.
     * @param array $config A collection of properties that modify MakeTable
     * behaviour.
     * @return MakeTable A unique MakeTable instance.
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx =& $modx;

        $core = $this->modx->getOption('core_path').'components/maketable/';

        $this->config = array_merge(array(
            'core_path' => $core,

			//Debug is on for development
			'debug' => true,
        ), $config);

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
	
	public function setInstanceChunk($name, $content){
		$chunk = $this->modx->newObject('modChunk');
		$chunk->set('name', $name);
		$chunk->setContent($content);
		$this->chunks[$name] = $chunk->getContent();
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
		$o = $this->chunks[$name];
		$chunk = $this->modx->newObject('modChunk');
		$chunk->setContent($o);
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }
}