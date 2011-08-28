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
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'import.class.php';
/**
 * Cliche import derivative class for images upload. Supports both zip files that have a directory inside (one level deep)
 * and files that just contain images.
 *
 * @package Cliche
 */
class FileUploader extends Import {
	const OPT_IGNORE_DIRECTORIES = 'ignore_directories';
    private $file;
    private $count;
    private $message;
	
	/**
     * Initialize the zip import class and setup directory based options.
     *
     * @abstract
     * @return void
     */
    public function initialize() {
        $this->config[self::OPT_IGNORE_DIRECTORIES] = explode(',',$this->modx->getOption('cliche.import_ignore_directories',null,'.,..,.svn,.git,__MACOSX,.DS_Store'));
		$this->config['sizeLimit'] = $this->modx->getOption('sizeLimit', null, 2097152);
		$this->config['allowedExtensions'] = $this->modx->getOption('allowedExtensions', null, 'jpg,jpeg,gif,png,bmp,zip'); 
		$this->_checkServerSettings();		
	}
	    
	/**
     * Check server upload parameter in php config.
     *
     * @access private
     */
    private function _checkServerSettings(){        
        $postSize = $this->_toBytes(ini_get('post_max_size'));
        $uploadSize = $this->_toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->config['sizeLimit'] || $uploadSize < $this->config['sizeLimit']){
            $size = max(1, $this->config['sizeLimit'] / 1024 / 1024) . 'M';  
			$response = $this->_response('increase post_max_size and upload_max_filesize to '. $size .' '. $postSize .' '.$uploadSize);
            die($response);    
        }        
    }
    
	/**
     * Check the value of a server parameter.
	 *
     * @access private
     * @param string $str The php parameter to check. Defaults to web.
     * @return int $val The value in byte format.
     */
    private function _toBytes($str){
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
     * @param integer $album_id The album where to add the uploaded picture
     * @return array The script response for the requested action.
     */
    public function handleUpload($album_id = null){
		$this->_setUploaderHandler();
		$replaceOldFile = $this->modx->getOption('replaceOldFile', $this->config, false);
				
		/* if the album ID is not supplied */
		if(is_null($album_id)){
			return $this->_response('Album id not specified');
		}
		$this->config['album_id'] = $album_id;
		
		/* set uploadDirectory */
		$uploadDirectory = $this->config['images_path'];		
		$this->target = $uploadDirectory . $album_id .'/';
		$cacheManager = $this->modx->getCacheManager();
		
		/* if directory doesnt exist, create it */
        if (!file_exists($this->target) || !is_dir($this->target)) {
            if (!$cacheManager->writeTree($this->target)) {
               $this->modx->log(xPDO::LOG_LEVEL_ERROR,'[Cliche] Could not create directory: '. $this->target);
               return $this->_response('Could not create the target directory in : '. $this->target);
            }
        }
		
		/* make sure directory is readable/writable */
        if (!is_readable($this->target) || !is_writable($this->target)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,'[Cliche] Could not write to directory: '. $this->target);
            return $this->_response('Could not write to directory: '. $this->target);
        }
        
		/* if no file was uploaded */
        if (!$this->file){
            return $this->_response('No file were uploaded');
        }
		
        $size = $this->file->getSize();        
		/* if the file as no dimensions */
        if ($size == 0) {
            return $this->_response('File appears to be empty in size :'. $size);
        }
        
		/* if the file size exceeds server settings */
        if ($size > $this->config['sizeLimit']) {
            return $this->_response('File is too large');
        } else {
			$size = ($size < 1024) ? $size .' bytes' : round(($size * 10)/1024)/10 .' KB';
		}
        
        $pathinfo = pathinfo($this->file->getName());
        $fileName = $pathinfo['filename'];
        $extension = $pathinfo['extension'];
		
		$allowedExtensions = explode(',', $this->config['allowedExtensions']);
		
		/* If the file to upload has a forbidden file extension - Double check (JS should have an extension control system too) */
        if(is_array($allowedExtensions) && !in_array(strtolower($extension), $allowedExtensions)){
            $these = implode(', ', $allowedExtensions);
            return $this->_response('Invalid file extension, only the extensions "'. $these .'" are accepted');
        }		
		$file = $this->target . $fileName . '.' . $extension;
        
		/* Check if exist */
		if (file_exists($file)) {
			/* If we don't allow file to to be renamed */
			if(!$replaceOldFile){
				return $this->_response('File already exist');
			} else {
				/* Generate uniq name */
				while (file_exists($file)) {
					$fileName .= rand(10, 99);
					$image = $this->config['uploadDirectory'] . $fileName . '.' . $extension;
				}
			}			
		}
		        
		//Upload the file
        if ($this->file->save($file)){			
			if($extension == 'zip'){
				$result = $this->extract($file, $fileName);
				$this->deleteTempDir($this->target . $fileName);
				if(!$result){
					return $this->_response(implode(',',$this->errors));
				} else {
					return $this->_response('Upload from zip file - '. $this->count .' picture created successfully', true);
				}					
			} else {
				$data['name'] = $pathinfo['filename'];
				$data['filename'] = $this->config['album_id'] .'/'. $pathinfo['filename'] .'.'. $extension;			
				$result = $this->saveItem($data);
				if(!$result){
					return $this->_response(implode(',',$this->errors));
				} else {
					return $this->_response($this->message, true);
				}	
			}
		} 
		if( file_exists($file) ){
			@unlink($file);
		}
		return $this->_response('File could not be uploaded');
    }
	
	/**
     * Handle the file upload and registration in database
     *
     * @access private
     * @param mixed $message A message to return to the user
     * @param boolean $success The response message default fo false
     * @return string the JSON response.
     */
	private function _response($message, $success = false){
		$response = array();
		$response['success'] = $success;
		$response['message'] = $message;		
		return $this->modx->toJSON($response);
	}
	
	/**
     * Set the uploader (xhr of normal file))
     *
     * @access private
     * @return array The script response for the requested action.
     */
	private function _setUploaderHandler(){
		if (isset($_GET['qqfile'])) {
			if (!$this->modx->loadClass('cliche.helpers.UploadFileXhr',$this->config['model_path'],true,true)) {
				$this->errors[] = 'Could not load helper class UploadFileXhr';
			}	
			$requestParameter = $this->modx->getOption('requestFileVar', $this->config, 'qqfile');
			$this->file = new UploadFileXhr($requestParameter);
        } elseif (isset($_FILES['uploadformfield-file'])) {
			if (!$this->modx->loadClass('cliche.helpers.UploadFileForm',$this->config['model_path'],true,true)) {
				$this->errors[] = 'Could not load helper class UploadFileForm.'. $this->config['model_path'];
			}
            $this->file = new UploadFileForm('uploadformfield-file');
        } 
	}
	
	/**
     * Run the import script.
     * 
     * @param array $data
     * @param bool $returnImg
     * @param array $metas
     * @return bool
     */
	private function saveItem($data = array(), $returnImg = true, $metas = array()){
		$data = array_merge(array(
			'createdon' => 'now',
			'createdby' => $this->modx->user->get('id'),
			'album_id' => $this->config['album_id'],
			'metas' => $metas,
		), $data);
		$item = false;
		if( array_key_exists('id', $data) && $data['id'] > 0){
			$item = $this->modx->getObject('ClicheItems', $data['id']);
		}
		if(!$item){
			$item = $this->modx->newObject('ClicheItems');
		}		
		$item->fromArray($data);
		if($item->save() && $this->updateAlbum($item->id)){			
			if($returnImg){
				$img = $this->config['images_url'] . $item->filename;
				$thumb = $this->config['phpthumb'] . urlencode($img) .'&h=80&w=90&zc=1';
				$this->message = '<img height="40" width="45" src="'. $thumb .'" />';
			}
			$this->config['item_id'] = $item->id;
			return true;
		}
		return false;
	}	
	
	/**
     * Remove an item if there was an eror in the file processing
     * 
     * @return bool
     */
	private function removeItem(){
		$item = $this->modx->getObject('ClicheItems', $this->config['item_id']);
		if($item && $item->remove()){
			return true;
		}
		return false;
	}
	
	/**
     * Update the album owner total picture count and set the cover if necessary.
     * 
     * @param int $itemId 
     * @return bool
     */
	private function updateAlbum($itemId = null){
		$album = $this->modx->getObject('ClicheAlbums', $this->config['album_id']);
		$total = $this->modx->getCount('ClicheItems', array('album_id' => $this->config['album_id']));
		$album->set('total' , $total);
		if($album->cover_id == 0 && !empty($itemId)){
			$album->set('cover_id' , $itemId);					
		}
		return $album->save();
	}	

    /**
     * Run the import script.
     * 
     * @param array $options
     * @return bool
     */
    public function extract($pathToZip, $fileName) {
		$target = $this->target . $fileName;
		/* Create the temp directory */
		if (!mkdir($target, 0, true)) {
			$this->errors[] = 'Could not create temp directory';
			return false;
		}
        $unpacked = $this->unpack($target);
        if ($unpacked !== true) return $unpacked;

        /* iterate */
        $this->errors = array();
        /* iterate over zipped files and move them into main dir */
        foreach (new DirectoryIterator($target) as $dir) {
            if ($dir->isDir()) {
                if (in_array($dir->getFilename(),$this->config[self::OPT_IGNORE_DIRECTORIES])) {
                    continue;
                }
                foreach (new DirectoryIterator($dir->getPathname()) as $file) {
                    $this->importFile($file);
                }
                /* delete subdir */
                $this->modx->cacheManager->deleteTree($dir->getPathname(),array('deleteTop' => true, 'skipDirs' => false, 'extensions' => '*'));
            } else {
                $this->importFile($dir);
                @unlink($dir->getPathname());
            }
        }	
		/* delete the zip file */
		@unlink($pathToZip);
        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }
	
	/**
     * Delete the temp directory for zip upload
     * 
     * @param string $dir The temp directory to delete
     * @return bool
     */
	public function deleteTempDir($dir) {
		/* Yes it's for you windows user */
		closedir(opendir($dir));
		return rmdir($dir);
	} 

    /**
     * Import a specific file into the current album
     * 
     * @param object $file A DirectoryIterator item that represents the file
     * @return bool
     */
    public function importFile($file) {
        if (in_array($file->getFilename(),$this->config[self::OPT_IGNORE_DIRECTORIES])) return false;

        $fileName = $file->getFilename();
        $filePathName = $file->getPathname();

        $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);
        $fileExtension = $this->config[Import::OPT_USE_MULTIBYTE] ? mb_strtolower($fileExtension,$this->config[Import::OPT_ENCODING]) : strtolower($fileExtension);
        if (!in_array($fileExtension,$this->config[Import::OPT_EXTENSIONS])) return false;

        /* create item */
		$data = array();
		$data['name'] = str_replace('.'.$fileExtension, '', $fileName);
		$data['filename'] = $this->config['album_id'] .'/'. $pathinfo['filename'] .'.'. $pathinfo['filename'];
		$result = $this->saveItem($data);
		if(!$result){
			$this->errors[] = 'Could not save image "'. $fileName .'" in database';
			@unlink($filePathName);
			return false;
		}
		
		/* Image coming from zip file are renamed */
		$data['id'] = $this->config['item_id'];
		$data['name'] = 'image-'. $this->config['item_id'];
        $data['filename'] = $this->config['album_id'] .'/'. $data['name'] .'.'. $fileExtension;	
        $newAbsolutePath = $this->target .'/'. $data['name'] .'.'. $fileExtension;		
		
		$replaceOldFile = $this->modx->getOption('replaceOldFile', $this->config, false);
		/* Check if exist */
		if(@file_exists($newAbsolutePath)) {
			/* If we don't allow file to to be renamed */
			if(!$replaceOldFile){				
				/* We must erase the item from db too */
				$result = $this->removeItem();
				if($result){
					$this->errors[] = 'File already exist';
				}
				return false;
			} else {
				/* Generate uniq name */
				while(@file_exists($newAbsolutePath)) {
					$data['name'] .= rand(10, 99);
					$data['filename'] = $this->config['album_id'] .'/'. $data['name'] .'.'. $fileExtension;	
					$newAbsolutePath = $this->target .'/'. $data['name'] .'.'. $fileExtension;	
				}
			}			
		}
		
		/* Move the iamge in the album root directory */
		if (!@copy($filePathName, $newAbsolutePath)) {
            $result = $this->removeItem();
			if($result){
				$this->errors[] = 'File already exist';
			}
            return false;
        } else {
			/* Set new data */
            $this->saveItem($data);
        }

        /* increment saved image count for feedback */
        $this->count = $this->count + 1;
        return true;
    }

    /**
     * Unpack the zip file using the xPDOZip class
     * 
     * @return bool|string
     */
    public function unpack($target) {
        if (!$this->modx->loadClass('compression.xPDOZip',$this->modx->getOption('core_path').'xpdo/',true,true)) {
			/* @TODO */
            return $this->modx->lexicon('cliche.xpdozip_err_nf');
        }
        /* unpack zip file */
        $archive = new xPDOZip($this->modx, $this->target . $this->file->getName());
        if (!$archive) {
			/* @TODO */
            return $this->modx->lexicon('cliche.zip_err_unpack');
        }
        $archive->unpack($target);
        $archive->close();
        return true;
    }
	
	/**
     * Get filesize
     * 
     * @return $string the filesize
     */
	public function getSize($file){
		$size = null;
		if(file_exists($file)){
			$size = filesize($file);
			$size = ($size < 1024) ? $size .' bytes' : round(($size * 10)/1024)/10 .' KB';
		}
		return $size;
	}
}