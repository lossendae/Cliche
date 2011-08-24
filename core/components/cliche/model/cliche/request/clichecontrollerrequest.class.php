<?php
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package cliche
 * @extends modRequest
 */
class ClicheControllerRequest extends modRequest {
    public $cliche = null;
    public $actionVar = 'action';
    public $defaultAction = 'manage/albums';

    function __construct(Cliche &$cliche) {
        parent :: __construct($cliche->modx);
        $this->cliche =& $cliche;
    }

    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * actionVar value.
     *
     * {@inheritdoc}
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        $modx =& $this->modx;
        $cliche =& $this->cliche;
		
		$viewHeader = include $this->cliche->config['controllers_path'].'mgr/index.php';

        $f = $this->cliche->config['controllers_path'].'mgr/'.strtolower($this->action).'.php';
        if (file_exists($f)) {
            $this->modx->lexicon->load('cliche:default');
            $viewOutput = include $f;
        } else {
            $viewOutput = 'Action not found: '.$f;
        }

       return $viewHeader.$viewOutput;
    }
}