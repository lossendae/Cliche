<?php
/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class UploadFileForm {  
    private $request;
	
	function __construct($rq = 'file'){
		$this->request = $rq;
	}
	/**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES[$this->request]['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES[$this->request]['name'];
    }
    function getSize() {
        return $_FILES[$this->request]['size'];
    }
}