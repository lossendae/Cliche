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
class ItemsController extends ClicheController {
	/**
     * Initialize this controller, setting up default properties
     * @return void
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'thumbWidth' => 120,
            'thumbHeight' => 120,
			
            'columns' => 3,
            'columnBreak' => '<br style="clear: both;">',
			
            'itemsWrapperTpl' => 'itemswrapper',
            'itemTpl' => 'items',
			
            'chunkDir' => 'default',
			
            'idParam' => 'cid',
            'viewParam' => 'view',
            'viewParamName' => 'item',
			
			'loadCSS' => true,
            'css' => 'default',
        ));
    }
	
	/**
     * Process and load The album list
     * @return string
     */
    public function process() {			
		$this->loadCSS();
		$output = $this->getItems();	
		return $output;
	}
	
		
	private function loadCSS() {
		if($this->getProperty('loadCSS'))
			$this->modx->regClientCSS($this->config['chunks_url'] . $this->getProperty('css') .'.css');
	}
	
	/**
     * Process and load The album list
     * @return string
     */
	private function getItems(){
		$request = $this->modx->request->getParameters();
		$id = $this->modx->getOption($this->getProperty('idParam'), $request, $this->getProperty('id', null));
		if(empty($id)){
			return $id;
		}	
		
		$list = '';
		$columns = $this->getProperty('columns');
		$columnCount = 0;
		
		$c = $this->modx->newQuery('ClicheItems');
		$c->where(array(
			'album_id' => $id,
		));				
		// $c->sortBy($sort,$dir);
		// $c->limit($limit,$start);
		$rows = $this->modx->getCollectionGraph('ClicheItems', '{ "Album":{} }',$c);
		foreach($rows as $row){
			$data = $row->toArray();
			$list .= $this->getItem($data, $row);
			$columnCount++;
			if($columnCount == $columns){
				$list .=  $this->getProperty('columnBreak');
				$columnCount = 0;
			}	
		}
		$phs = $row->Album->toArray();
		$phs['items'] = $list;
		$items = $this->getChunk($this->getProperty('itemsWrapperTpl'), $phs);
		return $items;
	}
	
	/**
     * Process and load The album cover
     * @return string
     */
	private function getItem($phs, $obj){		
		/* Handle url + additionnal field where only the req params are sended back for custom url scheme */
		$params = array( 
			$this->getProperty('viewParam') => $this->getProperty('viewParamName'),  
			$this->getProperty('idParam') => $obj->id,
		);			
		$phs['url'] = $this->modx->makeUrl( $this->modx->resource->get('id'),'',$params);	
		$phs['reqParams'] = http_build_query($params);	
		
		$phs['width'] = $this->getProperty('thumbWidth');
		$phs['height'] = $this->getProperty('thumbHeight');
		
		/* The album cover */
		$phs['image'] = $this->config['images_url'] . $obj->filename;
		$phs['thumbnail'] = $this->config['phpthumb'] . urlencode($phs['image']) .'&h='. $phs['height'] .'&w='. $phs['width'] .'&zc=1';	
		
		$processed = $this->getChunk($this->getProperty('itemTpl'), $phs);			
		return $processed;
	}
}
return 'ItemsController';