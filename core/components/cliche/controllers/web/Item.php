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
 * Show a single item
 * 
 * @package cliche
 * @subpackage controllers
 */
class ItemController extends ClicheController {
	/**
     * Initialize this controller, setting up default properties
     * @return void
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'thumbWidth' => 120,
            'thumbHeight' => 120,			
            'itemTpl' => 'item',
			
            'display' => 'default',	
			
			'idParam' => 'cid',
			
			'loadCSS' => true,
            'css' => 'default',
			'config' => null,
			'browse' => false,
        ));
    }
	
	/**
     * Process and load an album item
     * @return string
     */
    public function process() {	
		$this->loadCSS();
		$this->loadConfig();
		$output = $this->getItem();		
		return $output;
	}
		
	/**
     * Get the requested item
     * @return string
     */
	private function getItem(){			
		if(!$this->getProperty('browse')){
			$id = $this->getProperty('id');
		} else {
			$request = $this->modx->request->getParameters();
			$id = $this->modx->getOption($this->getProperty('idParam'), $request, $this->getProperty('id', $this->getProperties(), null));
		}
		if(empty($id)){
			return 'No item specified';
		}	
		
		$item = $this->modx->getObject('ClicheItems', $id);	
		
		if(!$item) return $this->modx->lexicon('cliche.item_not_found');
		
		$phs = $item->toArray(); 
		$phs['width'] = $this->getProperty('thumbWidth');
		$phs['height'] = $this->getProperty('thumbHeight');
		
		$phs['image'] = $this->config['images_url'] . urlencode($item->filename);	
		$phs['phpthumb'] = $this->config['phpthumb'] . $phs['image'];
		$phs['thumbnail'] = $phs['phpthumb'] .'&h='. $phs['height'] .'&w='. $phs['width'] .'&zc=1';	
		
		$item = $this->getChunk($this->getProperty('itemTpl'), $phs);

		return $item;
	}
}
return 'ItemController';