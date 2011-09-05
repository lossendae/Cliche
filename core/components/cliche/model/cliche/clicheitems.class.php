<?php
/**
 * @package cliche
 */
class ClicheItems extends xPDOSimpleObject {

    public function get($k, $format = null, $formatTemplate= null) {
        switch ($k) {
            case 'manager_thumbnail':
                $assetsUrl = $this->xpdo->cliche->config['cache_url'];
                $cacheDir = $assetsUrl . $this->get('album_id') .'/'. $this->get('id') .'/';
                $cacheFilename = $this->xpdo->cliche->config['mgr_thumb_mask'];
                $cacheFile = $cacheDir . $cacheFilename;
                /* @TODO : verify if the file exist and if not create it (in case of manager thumb mask as been changed) */
                $value = $cacheFile;
                break;
            case 'image':
                $assetsUrl = $this->xpdo->cliche->config['images_url'];
                $value = $assetsUrl . $this->get('filename');
                break;
//            case 'absoluteImage':
//                $siteUrl = $this->getSiteUrl();
//                $value = $siteUrl.$this->xpdo->getOption('gallery.files_url').$this->get('filename');
//                break;
//            case 'relativeImage':
//                $value = ltrim($this->xpdo->getOption('gallery.files_url').$this->get('filename'),'/');
//                break;
//            case 'filesize':
//                $filename = $this->xpdo->getOption('gallery.files_path').$this->get('filename');
//                $value = @filesize($filename);
//                $value = $this->formatFileSize($value);
//                break;
//            case 'image_path':
//                $value = $this->xpdo->getOption('gallery.files_path').$this->get('filename');
//                break;
            default:
                $value = parent::get($k,$format,$formatTemplate);
                break;
        }
        return $value;
    }

    public function getImage($params){
        $original = $this->xpdo->cliche->config['images_path'] . $this->get('filename');
//        $ext = pathinfo($original, PATHINFO_EXTENSION);
//        $ext = strtolower($ext);

        if(array_key_exists('mask', $params)){
            $name = str_replace(' ', '_', $params['mask']);
            $name = strtolower($name);
        } else {
            $name = $this->get('name');
            $name = str_replace(' ', '_', $name);
            $name = strtolower($name);
        }
        $mask = $name .'-'. $params['width'] .'x'. $params['height'];

        if(array_key_exists('crop', $params)){
            $mask .= '-thumbnail';
        }
        if(array_key_exists('zoomcrop', $params)){
            $mask .= '-zoomcrop';
        }
        if(array_key_exists('resized', $params)){
            $mask .= '-resized';
        }
        $mask .= '.png';

        $assetsUrl = $this->xpdo->cliche->config['cache_url'];
        $cacheDir = $assetsUrl . $this->get('album_id') .'/'. $this->get('id') .'/';
        $cacheFile = $cacheDir . $mask;

        if(file_exists($cacheFile)){
            if(array_key_exists('overwrite', $params)){
                $this->createImage($original, $mask, $params, $cacheFile);
            }
            return $cacheFile;
        }
        $this->createImage($original, $mask, $params);
        return $cacheFile;
    }

    public function loadThumbClass($original, $options = array()){
        return $this->xpdo->cliche->loadPhpThumb($original, $options);
    }

    public function getCacheDir($path = true){
        if($path){
            $assetsPath = $this->xpdo->cliche->config['cache_path'];
            return $assetsPath .'/'. $this->get('album_id') .'/'. $this->get('id') .'/';
        } else {
            $assetsUrl = $this->xpdo->cliche->config['cache_url'];
            return $assetsUrl .'/'. $this->get('album_id') .'/'. $this->get('id') .'/';
        }
    }

    protected function createImage($original, $name, $params, $exist = null, $ext = 'png'){
        if(!empty($exist) && file_exists($exist)){
            if(!@unlink($exist)){
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Cliche] An error occurred while trying to remove the file at: '. $exist);
            }
        }
        /* Create the cache directory for this item */
        $assetsPath = $this->xpdo->cliche->config['cache_path'];
        $cacheDir = $assetsPath .'/'. $this->get('album_id') .'/'. $this->get('id');

        /* Create the thumbnail */
        $cacheFile = $cacheDir .'/'. $name;

        /* Load the phpThumb class */
        $options = array(
            'jpegQuality' => 90,
        );
        if(array_key_exists('crop', $params)){
            $options['resizeUp'] = true;
        }
        $thumb = $this->loadThumbClass($original, $options);

        if(array_key_exists('crop', $params)){
            $thumb->cropCustom(
                $params['x'],
                $params['y'],
                $params['w'],
                $params['h'],
                $params['width'],
                $params['height']
            );
        }

        if(array_key_exists('zoomcrop', $params)){
            $thumb->adaptiveResize($params['width'], $params['height']);
        }
        if(array_key_exists('resized', $params)){
            $thumb->resize($params['width'], $params['height']);
        }
        $thumb->save($cacheFile, $ext);
    }

    public function save($cacheFlag= null) {
        if ($this->isNew() && !$this->get('createdon')) {
            $this->set('createdon', 'now');
        }
        if ($this->isNew() && !$this->get('createdby')) {
            if (!empty($this->xpdo->user) && $this->xpdo->user instanceof modUser) {
                if ($this->xpdo->user->isAuthenticated($this->xpdo->context->get('key'))) {
                    $this->set('createdby',$this->xpdo->user->get('id'));
                }
            }
        }
        $isNew = $this->isNew();
        $saved = parent :: save($cacheFlag);

        /* Update album infos */
        if($saved && $isNew){
            $this->updateAlbum();
            $this->addManagerCacheFiles();
        }        
        return $saved;
    }

    public function updateAlbum($action = 'save'){
        $album = $this->xpdo->getObject('ClicheAlbums', $this->get('album_id'));
        $cover = $album->get('cover_id');
        $total = $this->xpdo->getCount('ClicheItems', array('album_id' => $this->get('album_id')));
        $album->set('total' , $total);

        if($action == 'save'){
            /* Add cover to the album if necessary */
            if($cover == 0) $album->set('cover_id' , $this->get('id'));
        } else {
            if($cover == $this->get('id')){
                /* Default to no cover if there are no items in the album */
                $new_cover = 0;

                /* Get the first available item for this album */
                $c = $this->xpdo->newQuery('ClicheAlbums');
                $c->where(array(
                    'id' => $this->get('album_id'),
                    'Items.id:!=' => $this->get('id'),
                ));
                $c->limit(1);
                $rows = $this->xpdo->getCollectionGraph('ClicheAlbums', '{ "Items":{} }', $c);
                if($rows){
                    foreach($rows as $row){
                        if($row->Items){
                            foreach($row->Items as $pic){
                                $new_cover = $pic->id;
                            }
                        }
                    }
                }
                $album->set('cover_id', $new_cover);
            }
        }
        /* Save the queen */
        return $album->save();
    }

    protected function addManagerCacheFiles(){
        /* Create the cache directory for this item */
        $assetsPath = $this->xpdo->cliche->config['cache_path'];
        $cacheDir = $assetsPath .'/'. $this->get('album_id') .'/'. $this->get('id');
        $cacheDir = $this->createDir($cacheDir);

        /* Create the thumbnail */
        $original = $this->xpdo->cliche->config['images_path'] . $this->get('filename');
        $cacheFilename = $this->xpdo->cliche->config['mgr_thumb_mask'];
        $cacheFile = $cacheDir .'/'. $cacheFilename;
//        $ext = pathinfo($original, PATHINFO_EXTENSION);
//        $ext = strtolower($ext);

        /* Load the phpThumb class */
        $options = array('jpegQuality' => 90);
        $thumb = $this->xpdo->cliche->loadPhpThumb($original, $options);
        $thumb->adaptiveResize(95, 80);
        $thumb->save($cacheFile, 'jpg');
    }

    protected function createDir($dir){
        if (!is_writable($dir)) {
            if (!$this->xpdo->cacheManager->writeTree($dir)) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'[Cliche] Cache directory is not writable: '.$dir);
                return false;
            }
        }
        return $dir;
    }

    public function remove(array $ancestors = array()) {
        $this->removeFile();
        return parent :: remove($ancestors);
    }

    protected function removeFile(){
        $file = $this->xpdo->cliche->config['images_path'] . $this->get('filename');
		if(file_exists($file)){
			if(!@unlink($file)){
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Cliche] An error occurred while trying to remove the file at: '. $file);
            }
		}
        /* @TODO: Remove cached files as well */
    }
}
?>