<?php
/**
 * Cliche
 *
 * Copyright 2010-11 by Shaun McCormick <shaun@modx.com>
 *
 * This file is part of Cliche, a media manager for MODx Revolution.
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
 * Cliche; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package cliche
 */
/**
 * Show the items in an album
 * 
 * @package cliche
 * @subpackage controllers
 */
class AlbumController extends ClicheController {
    /**
     * Initialize this controller, setting up default properties
     * @return void
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'thumbWidth' => 120,
            'thumbHeight' => 120,          
            
            'wrapperTpl' => 'albumwrapper',
            'itemTpl' => 'item',
            
            'idParam' => 'cid',
            'viewParam' => 'view',
            'viewParamName' => 'image',
        ));
        $this->fireEvent('load');
    }
    
    /**
     * Process and load an album sets of items
     * @return string
     */
    public function process() {
        $output = $this->getItems();
        $this->fireEvent('render');
        return $output;
    }
        
    /**
     * Get the requested items list
     * @return string
     */
    private function getItems(){
        /* We don't want the user to be able to browse the current album items */
        if(!$this->getProperty('browse')){
            $id = $this->getProperty('id');
        } else {
            $request = $this->modx->request->getParameters();
            $id = $this->modx->getOption($this->getProperty('idParam'), $request, $this->getProperty('id', $this->getProperties(), null));
        }
        
        if(empty($id)){
            return $this->modx->lexicon('cliche.album_not_specified');
        }    
        
        $list = '';        
        $c = $this->modx->newQuery('ClicheItems');
        $c->where(array(
            'album_id' => $id,
        ));
        /* Pass the query for modification to the controller class */
        $modifiedQuery = $this->fireEvent('beforeQuery', array(&$c, 'ClicheItems'));
        if($modifiedQuery) $c = $modifiedQuery;
        
        $rows = $this->modx->getCollectionGraph('ClicheItems', '{ "Album":{} }',$c);
        
        if(!$rows) return $this->modx->lexicon('cliche.album_not_found');
        /* Limit images to display from certain album */
        $limit = $this->view = $this->getProperty('limit', 0);
		$i = 0;
        foreach($rows as $row){
            $data = $row->toArray();
            $list .= $this->getItem($data, $row);
            /* We pass the processed string to the plugin ? */
            $modified = $this->fireEvent('afterItemRendered', array(&$list));    
            if($modified){
                $list = $modified;
            }
        /* Limit if */    
        if (++$i == $limit) break;    
        }
        $phs = $row->Album->toArray();
        $phs['items'] = $list;
        $items = $this->getChunk($this->getProperty('wrapperTpl'), $phs);
        return $items;
    }
    
    /**
     * Create the item placeholders
     * @param array $phs the current line placeholders
     * @param object $obj the xPDO object
     * @return string The processed content
     */
    private function getItem($phs, $obj){        
        /* Handle url + additionnal field where only the req params are sended back for custom url scheme */
        if($this->getProperty('browse')){
            $params = array( 
                $this->getProperty('viewParam') => $this->getProperty('viewParamName'),  
                $this->getProperty('idParam') => $obj->id,
            );            
            $phs['url'] = $this->modx->makeUrl( $this->modx->resource->get('id'),'',$params);    
            $phs['reqParams'] = http_build_query($params);    
        }
        
        $phs['width'] = $this->getProperty('thumbWidth');
        $phs['height'] = $this->getProperty('thumbHeight');
        
        /* The image & and the phpthumb ready url string */
        $phs['image'] = $this->config['images_url'] . $obj->filename;
        $phs['phpthumb'] = $this->config['phpthumb'] . $phs['image'];
        
        /* Let the used plugin do its magic */
        $returned = $this->fireEvent('setItemPlaceholder', array($phs, & $obj));
        if(is_array($returned)){
            $phs = $returned;
        }
        
        //Not used yet !
        // $field = $obj->toArray();
        // foreach($field['metas'] as $k => $v){
            // $name = strtolower(str_replace(' ','_',$v['name']));
            // $phs['meta.'. $name] = $v['value'];
        // }
        // unset($field['metas']);       
        $processed = $this->getChunk($this->getProperty('itemTpl'), $phs);            
        return $processed;
    }
}
return 'AlbumController';
