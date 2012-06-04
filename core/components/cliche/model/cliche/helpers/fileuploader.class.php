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
    protected $file;
    protected $count;
    protected $message;
    protected $pathinfo;

    /**
     * Initialize the zip import class and setup directory based options.
     *
     * @abstract
     * @return void
     */
    public function initialize() {
        $this->config[self::OPT_IGNORE_DIRECTORIES] = explode(',',$this->modx->getOption('cliche.import_ignore_directories',null,'.,..,.svn,.git,__MACOSX,.DS_Store'));
        $this->config['sizeLimit'] = $this->modx->getOption('sizeLimit', null, 2097152);
        $this->config['allowedExtensions'] = $this->modx->getOption('allowedExtensions', null, 'jpg,jpeg,gif,png,zip'); 
        $this->_checkServerSettings();        
    }
        
    /**
     * Check server upload parameter in php config.
     *
     * @access protected
     */
    protected function _checkServerSettings(){        
        $postSize = $this->_toBytes(ini_get('post_max_size'));
        $uploadSize = $this->_toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->config['sizeLimit'] || $uploadSize < $this->config['sizeLimit']){
            $size = max(1, $this->config['sizeLimit'] / 1024 / 1024) . 'M';  
            $response = $this->_response($this->modx->lexicon('cliche.increase_post_max_size') . $size .' '. $postSize .' '.$uploadSize);
            die($response);    
        }        
    }
    
    /**
     * Check the value of a server parameter.
     *
     * @access protected
     * @param string $str The php parameter to check. Defaults to web.
     * @return int The value in byte format.
     */
    protected function _toBytes($str){
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
     * @access protected
     * @param mixed $message A message to return to the user
     * @param boolean $success The response message default fo false
     * @return string the JSON response.
     */
    protected function _response($message, $success = false){
        $response = array();
        $response['success'] = $success;
        $response['message'] = $message;        
        // $response['t'] = $this->contentType;        
        // $response['z'] = $_GET;        
        return $this->modx->toJSON($response);
    }
    
    /**
     * Set the uploader (xhr of normal file))
     *
     * @access protected
     * @return array The script response for the requested action.
     */
    protected function _setUploaderHandler(){
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $this->contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $this->contentType = $_SERVER["CONTENT_TYPE"];
            
        if (strpos($this->contentType, "application/octet-stream") !== false) {
            if (!$this->modx->loadClass('cliche.helpers.UploadFileXhr',$this->config['model_path'],true,true)) {
                $this->errors[] = $this->modx->lexicon('cliche.uploadxhr_error');
            }    
            $requestParameter = $this->modx->getOption('request_file_var', $this->config, 'name');
            $this->file = new UploadFileXhr($requestParameter);
        } else {
            if (!$this->modx->loadClass('cliche.helpers.UploadFileForm',$this->config['model_path'],true,true)) {
                $this->errors[] = $this->modx->lexicon('cliche.uploadfileform_error') . $this->config['model_path'];
            }
            $this->file = new UploadFileForm('name');
        } 
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
            return $this->_response($this->modx->lexicon('cliche.album_id_error'));
        }
        $this->config['album_id'] = $album_id;
        
        /* set uploadDirectory */
        $uploadDirectory = $this->config['images_path'];        
        $this->target = $uploadDirectory . $album_id .'/';
        $cacheManager = $this->modx->getCacheManager();
        
        /* if directory doesn't exist, create it */
        if (!file_exists($this->target) || !is_dir($this->target)) {
            if (!$cacheManager->writeTree($this->target)) {
               $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('cliche.target_dir_error') . $this->target);
               return $this->_response($this->modx->lexicon('cliche.target_dir_error') . $this->target);
            }
        }
        
        /* make sure directory is readable/writable */
        if (!is_readable($this->target) || !is_writable($this->target)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('cliche.target_dir_write_error') . $this->target);
            return $this->_response($this->modx->lexicon('cliche.target_dir_write_error') . $this->target);
        }
        
        /* if no file was uploaded */
        if (!$this->file){
            return $this->_response($this->modx->lexicon('cliche.no_file_error'));
        }
        
        $size = $this->file->getSize();        
        /* if the file as no dimensions */
        if ($size == 0) {
            return $this->_response($this->modx->lexicon('cliche.empty_file_error') . $size);
        }
        
        /* if the file size exceeds server settings */
        if ($size > $this->config['sizeLimit']) {
            return $this->_response($this->modx->lexicon('cliche.file_too_large_error'));
        } else {
            $size = ($size < 1024) ? $size .' bytes' : round(($size * 10)/1024)/10 .' KB';
        }
        
        $this->pathinfo = pathinfo($this->file->getName());
        $fileName = $this->pathinfo['filename'];
        $extension = $this->pathinfo['extension'];
        
        $allowedExtensions = explode(',', $this->config['allowedExtensions']);
        
        /* If the file to upload has a forbidden file extension - Double check (JS should have an extension control system too) */
        if(is_array($allowedExtensions) && !in_array(strtolower($extension), $allowedExtensions)){
            $these = implode(', ', $allowedExtensions);
            return $this->_response($this->modx->lexicon('cliche.invalid_extensions_error') .'"'. $these .'"');
        }        
        $file = $this->target . $fileName . '.' . $extension;
        
        /* Check if exist */
        $exist = str_replace(' ','_',$file);
        if (file_exists($exist) && !$replaceOldFile) {
            return $this->_response($this->modx->lexicon('cliche.already_exist_error'));
        }

        /* Upload the file */
        if ($this->file->save($file)){
            if($extension == 'zip'){
                $result = $this->extract($file, $fileName);
                $this->deleteTempDir($this->target . $fileName);
                if(!$result){
                    return $this->_response(implode(',',$this->errors));
                } else {
                    return $this->_response(array(
                        'message' => $this->modx->lexicon('cliche.upload_zip_success', array('count' => $this->count)),
                        'thumbnail' => '<img src="'. $this->config['assets_url'] .'images/zip-success.png" alt="Zip upload success"/>',
                    ), true);
                }                    
            } else {
                $data['filename'] = $this->config['album_id'] .'/'. $this->pathinfo['filename'] .'.'. $extension;    
                
                /* Single file properties */    
                $returnThumb = $this->modx->getOption('return_thumb', $this->config, true);
                
                /* Remove unwanted characters form filename */
                $name = $this->sanitizeName($this->pathinfo['filename']);
                $data['name'] = $name;
                $data['filename'] = str_replace($this->pathinfo['filename'], $name, $data['filename']);
                
                /* Save item */
                $result = $this->_saveItem($data, $returnThumb);
                                            
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
        return $this->_response($this->modx->lexicon('cliche.misc_error'));
    }
    
    public function sanitizeName($name, $rename = true){    
        $name = str_replace(' ','_',$name);
        
        $allowedChars = "[^0-9a-zA-z()_-]";
        $name = preg_replace("/$allowedChars/","",$name);        

        $name =  filter_var($name, FILTER_SANITIZE_STRING);                                        
        $name = filter_var($name, FILTER_SANITIZE_URL);        
        $name = strtolower($name);        
        $ext = strtolower($this->pathinfo['extension']);
        if($rename){
            rename($this->target . $this->pathinfo['filename'] .'.'. $this->pathinfo['extension'], $this->target . $name .'.'. $ext);
        }
        return $name;
    }
    
    /**
     * Run the import script.
     * 
     * @param array $data
     * @param bool $returnImg
     * @param array $metas
     * @return bool
     */
    protected function _saveItem($data = array(), $returnThumb = true, $metas = array()){
        $data = array_merge(array(
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
        if($item->save()){
            /* @TODO : This might not be useful in the end */
            if($returnThumb){
                $this->message = array(
                    'image' => $item->get('image'),
                    'thumbnail' => '<img height="40" width="45" src="'. $item->get('manager_thumbnail') .'?t='. strtotime('now') .'" />',
                    'id' => $item->get('id'),
                    'timestamp' => strtotime('now'),
                    'message' => $this->modx->lexicon('cliche.image_upload_success_msg'),
                );
            }
            $this->config['item_id'] = $item->get('id');
            return true;
        }
        return false;
    }    
    
    /**
     * Remove an item if there was an error in the file processing
     * 
     * @return bool
     */
    protected function _removeItem(){
        $item = $this->modx->getObject('ClicheItems', $this->config['item_id']);
        if($item && $item->remove()){
            return true;
        }
        return false;
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
        if (!mkdir($target, 0777, true)) {
            $this->errors[] = $this->modx->lexicon('cliche.create_temp_dir_error');
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
        $data['filename'] = $this->config['album_id'] .'/'. $this->pathinfo['filename'] .'.'. $fileExtension;
        $result = $this->_saveItem($data, false);
        if(!$result){
            $this->errors[] = $this->modx->lexicon('cliche.db_save_item_error', array('filename' => $fileName));
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
                $result = $this->_removeItem();
                if($result){
                    $this->errors[] = $this->modx->lexicon('cliche.already_exist_error');
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
            $result = $this->_removeItem();
            if($result){
                $this->errors[] = $this->modx->lexicon('cliche.already_exist_error');
            }
            return false;
        } else {
            /* Set new data */
            $this->_saveItem($data);
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
            return $this->modx->lexicon('cliche.xpdozip_not_found');
        }
        /* unpack zip file */
        $archive = new xPDOZip($this->modx, $this->target . $this->file->getName());
        if (!$archive) {
            return $this->modx->lexicon('cliche.zip_error_unpack');
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