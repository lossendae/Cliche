<?php
/**
 * Cliche
 *
 * Copyright 2010 by Stephane Boulard
 *
 * Cliche is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Cliche is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Cliche; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package cliche
 */
class FileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;
	public $cliche = null;

	/**
     * The FileUploader Constructor.
     *
     * This method is used to create a new FileUploader object.
     *
     * @param Cliche &$cliche A reference to the Cliche object.
     * @return FileUploader A unique FileUploader instance.
     */
    function __construct(Cliche &$cliche){        
        
		$this->cliche =& $cliche;
		$this->modx =& $cliche->modx;
		
		$arr = (empty($cliche->config['allowedExtensions'])) ? array() : explode($cliche->config['allowedExtensions'],',');
		$allowedExtensions = array_map("strtolower", $arr);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $cliche->config['sizeLimit'];        
        
		$this->checkServerSettings();       
		
		$requestParameter = $this->cliche->config['requestFileVar'];
        if (isset($_GET['qqfile'])) {
			if (!$this->modx->loadClass('cliche.helpers.UploadFileXhr',$this->cliche->config['model_path'],true,true)) {
				return 'Could not load helper class UploadFileXhr';
			}			
			$this->file = new UploadFileXhr($requestParameter);
        } elseif (isset($_FILES['uploadformfield-file'])) {
			if (!$this->modx->loadClass('cliche.helpers.UploadFileForm',$this->cliche->config['model_path'],true,true)) {
				return 'Could not load helper class UploadFileForm.'.$this->cliche->config['model_path'];
			}
            $this->file = new UploadFileForm('uploadformfield-file');
        } else {
            $this->file = false; 
        }
    }
    
	/**
     * Check server upload parameter in php config.
     *
     * @access private
     */
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size $postSize $uploadSize'}");    
        }        
    }
    
	/**
     * Check the value of a server parameter.
	 *
     * @access private
     * @param string $str The php parameter to check. Defaults to web.
     * @return int $val The value in byte format.
     */
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
	
    /**
     * Handle the file upload and registration in database
     *
     * @access public
     * @param string $uploadDirectory The target directory for all images.
     * @return array The script response for the requested action.
     */
    public function handleUpload($uploadDirectory, $album_id = null){
		$modx =& $this->modx;
		$replaceOldFile = $modx->getOption('replaceOldFile', $this->cliche->config, false);
		
		/* if the album ID is not supplied */
		if(is_null($album_id)){
			return array('error' => 'Album not specified');
		}
		
		/* if uploadDirectory is not writable */		
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
		
		$uploadDirectory = $uploadDirectory.$album_id.'/';
		$cacheManager = $modx->getCacheManager();
		/* if directory doesnt exist, create it */
        if (!file_exists($uploadDirectory) || !is_dir($uploadDirectory)) {
            if (!$cacheManager->writeTree($uploadDirectory)) {
               $modx->log(xPDO::LOG_LEVEL_ERROR,'[Cliche] Could not create directory: '.$uploadDirectory);
               return array('error' => 'Could not create the target firectory');
            }
        }
        
		/* if no file was uploaded */
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
		/* if the file as no dimensions */
        if ($size == 0) {
            return array('error' => 'File is empty', 'size' => $size);
        }
        
		/* if the file size exceeds server settings */
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        } else {
			$size = ($size < 1024) ? $size .' bytes' : round(($size * 10)/1024)/10 .' KB';
		}
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];
		
		/* If the file to upload has a forbidden file extension - Double check (JS as an extension control too) */
        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
		
		$image = $uploadDirectory . $filename . '.' . $ext;
        
		/* If we allow files to have to be renamed */
        if(!$replaceOldFile){
            // don't overwrite previous files that were uploaded
            while (file_exists($image)) {
                $filename .= rand(10, 99);
				$image = $uploadDirectory . $filename . '.' . $ext;
            }
        }
		        
		//Upload the file
        if ($this->file->save($image)){
			list($width, $height, $type, $attr) = getimagesize($image);	
			$metas[] = array(
				'name' => 'File size',
				'value' => $size,
			);
			$metas[] = array(
				'name' => 'Width',
				'value' => $width,
			);
			$metas[] = array(
				'name' => 'Height',
				'value' => $height,
			);
			$metas[] = array(
				'name' => 'Image type',
				'value' => $type,
			);
			//And create an entry related in the database
			$data = $modx->newObject('ClicheItems');
			$data->fromArray(array(
				'name' => $filename,
				'filename' => $album_id .'/'. $filename . '.' . $ext,
				'createdon' => 'now',
				'createdby' => $modx->user->get('id'),
				'album_id' => $album_id,
				'metas' => $metas,
			));
			if($data->save()){
				
				$album = $modx->getObject('ClicheAlbums', $album_id);				
				
				/*Update the total of picture in this album */
				$total = $this->modx->getCount('ClicheItems', array('album_id' => $album_id));
				$album->set('total' , $total);	
				
				/* Set the default cover id if it does not exist yet */
				if($album->cover_id == 0){
					$album->set('cover_id' , $data->id);					
				}
				$album->save();
				
			    return array('success' => true);
			}
			return array('error'=> 'File deleted - Could not save the entry in database.');
        } else {
            return 'Could not save uploaded file. The upload was cancelled, or server error encountered';
        }        
    }    
}