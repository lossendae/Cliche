<?php
/**
 * @package cliche
 */
class ClicheAlbums extends xPDOSimpleObject {

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
        $saved= parent :: save($cacheFlag);
        if($saved && $isNew){
            $this->createCacheDirectory();
        }
        return $saved;
    }

    protected function createCacheDirectory(){
        $assetsPath = $this->xpdo->cliche->config['cache_path'];
        $cacheDir = $assetsPath . $this->get('id');
        if (!is_writable($cacheDir)) {
            if (!$this->xpdo->cacheManager->writeTree($cacheDir)) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'[Cliche] Cache directory is not writable: '.$cacheDir);
            }
        }
    }

    public function remove(array $ancestors = array()) {
        $id = $this->get('id');
        $removed = parent :: remove($ancestors);
        if($removed){
            /* Remove the directory */
            $dir = $this->xpdo->cliche->config['images_path'].$id;
            if(is_dir($dir)){
                closedir(opendir($dir));
                if(!@rmdir($dir)){
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Cliche] An error occurred while trying to remove the directory at: '. $dir);
                }
            }
        }
        return $removed;
    }
}
?>