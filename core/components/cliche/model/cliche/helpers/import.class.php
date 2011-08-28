<?php
/**
 * Cliche
 *
 * Copyright 2010-2011 by Shaun McCormick <shaun@modx.com>
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
 * @package Cliche
 */
/**
 * Abstract base class for different import methods. Not to be initialized directly, but rather
 * extended in derivative classes for various implementation methods.
 * 
 * @package Cliche
 */
abstract class Import {
    const OPT_EXTENSIONS = 'extensions';
    const OPT_USE_MULTIBYTE = 'use_multibyte';
    const OPT_ENCODING = 'encoding';
    
    /** @var Cliche A reference to the Cliche object */
    public $cliche;
    /** @var xPDO A reference to the modX object */
    public $modx;
    /** @var array A configuration array of properties */
    public $config = array();
    /** @var string The source file/directory/url for the import */
    public $source;
    /** @var string The target directory for the imported items */
    public $target;
    /** @var integer The Album ID number to import into */
    public $albumId;
    /** @var array An array of results of the import */
    public $results = array();
    /** @var array An array of errors returned by the import */
    public $errors = array();

    function __construct(Cliche &$cliche,array $config = array()) {
        $this->cliche =& $cliche;
        $this->modx =& $cliche->modx;
        $this->config = array_merge(array(
            Import::OPT_EXTENSIONS => explode(',',$this->modx->getOption('Cliche.import_allowed_extensions',null,'jpg,jpeg,png,gif,bmp')),
            Import::OPT_USE_MULTIBYTE => $this->modx->getOption('use_multibyte',null,false),
            Import::OPT_ENCODING => $this->modx->getOption('modx_charset',null,'UTF-8'),
        ),$config);
        $this->initialize();
    }

    /**
     * Initialize the derivative import class and run any pre-import setup options.
     * 
     * @abstract
     * @return void
     */
    abstract public function initialize();

    /**
     * Run the import script. Return a non-true value to display an error.
     *
     * @abstract
     * @param string $file
     * @return bool|string
     */
    abstract public function extract($file, $fileName);
}