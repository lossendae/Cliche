<?php
/**
 * Cliche
 *
 * Copyright 2010-2011 by Stephane Boulard <lossendae@media-lab.fr>
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
/**
 * @package cliche
 * @abstract
 */
abstract class ClichePlugin {
    public $cliche = null;
    public $modx = null;
    public $config = array();
	
	function __construct(Cliche &$cliche,array $config = array()) {
        $this->cliche =& $cliche;
        $this->modx =& $gallery->modx;
        $this->config = array_merge(array(

        ),$config);
    }
	
	abstract public function showList();
	abstract public function showAlbum();
	abstract public function showItem();
}