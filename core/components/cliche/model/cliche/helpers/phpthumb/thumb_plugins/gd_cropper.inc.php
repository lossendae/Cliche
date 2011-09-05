<?php
/**
 * GD Reflection Lib Plugin Definition File
 * 
 * This file contains the plugin definition for the GD Reflection Lib for PHP Thumb
 * 
 * PHP Version 5 with GD 2.0+
 * PhpThumb : PHP Thumb Library <http://phpthumb.gxdlabs.com>
 * Copyright (c) 2009, Ian Selby/Gen X Design
 * 
 * Author(s): Ian Selby <ian@gen-x-design.com>
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author Ian Selby <ian@gen-x-design.com>
 * @copyright Copyright (c) 2009 Gen X Design
 * @link http://phpthumb.gxdlabs.com
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 3.0
 * @package PhpThumb
 * @filesource
 */

/**
 * GD Reflection Lib Plugin
 * 
 * This plugin allows you to create those fun Apple(tm)-style reflections in your images
 * 
 * @package PhpThumb
 * @subpackage Plugins
 */
class GdCropper
{
	/**
	 * Instance of GdThumb passed to this class
	 * 
	 * @var GdThumb
	 */
	protected $parentInstance;
	protected $currentDimensions;
	protected $workingImage;
	protected $newImage;
	protected $options;
	protected $getFormat;
	
	/**
	 * Vanilla Cropping - Crops from x,y with specified width and height a
	 * 
	 * @param int $startX
	 * @param int $startY
	 * @param int $cropWidth
	 * @param int $cropHeight
	 * @return GdThumb
	 */
	public function cropCustom ($startX, $startY, $cropWidth, $cropHeight, $newWidth, $newHeight, &$that )
	{
		// bring stuff from the parent class into this class...
		$this->parentInstance 		= $that;
		$this->currentDimensions 	= $this->parentInstance->getCurrentDimensions();
		$this->workingImage			= $this->parentInstance->getWorkingImage();
		$this->oldImage				= $this->parentInstance->getOldImage();
		$this->options				= $this->parentInstance->getOptions();
		$this->getFormat			= $this->parentInstance->getFormat();
		
		// validate input
		if (!is_numeric($startX))
		{
			throw new InvalidArgumentException('$startX must be numeric');
		}
		
		if (!is_numeric($startY))
		{
			throw new InvalidArgumentException('$startY must be numeric');
		}
		
		if (!is_numeric($cropWidth))
		{
			throw new InvalidArgumentException('$cropWidth must be numeric');
		}
		
		if (!is_numeric($cropHeight))
		{
			throw new InvalidArgumentException('$cropHeight must be numeric');
		}
		
		// do some calculations
		$cropWidth	= ($this->currentDimensions['width'] < $cropWidth) ? $this->currentDimensions['width'] : $cropWidth;
		$cropHeight = ($this->currentDimensions['height'] < $cropHeight) ? $this->currentDimensions['height'] : $cropHeight;
		
		// ensure everything's in bounds
		if (($startX + $cropWidth) > $this->currentDimensions['width'])
		{
			$startX = ($this->currentDimensions['width'] - $cropWidth);
			
		}
		
		if (($startY + $cropHeight) > $this->currentDimensions['height'])
		{
			$startY = ($this->currentDimensions['height'] - $cropHeight);
		}
		
		if ($startX < 0) 
		{
			$startX = 0;
		}
		
	    if ($startY < 0) 
		{
			$startY = 0;
		}
		
		// create the working image
		if (function_exists('imagecreatetruecolor'))
		{
			$this->workingImage = imagecreatetruecolor($newWidth, $newHeight);
		}
		else
		{
			$this->workingImage = imagecreate($newWidth, $newHeight);
		}
		
		if ($this->parentInstance->getFormat() == 'PNG')
		{
			$colorTransparent = imagecolorallocatealpha
			(
				$this->workingImage, 
				$this->options['alphaMaskColor'][0], 
				$this->options['alphaMaskColor'][1], 
				$this->options['alphaMaskColor'][2], 
				0
			);
			
			imagefill($this->workingImage, 0, 0, $colorTransparent);
			imagesavealpha($this->workingImage, true);
		}
		
		imagecopyresampled
		(
			$this->workingImage,
			$this->oldImage,
			0,
			0,
			$startX,
			$startY,
			$newWidth,
			$newHeight,
			$cropWidth,
			$cropHeight
		);
		
		$this->parentInstance->setOldImage($this->workingImage);
		$this->currentDimensions['width'] 	= $newWidth;
		$this->currentDimensions['height']	= $newHeight;
		$this->parentInstance->setCurrentDimensions($this->currentDimensions);
		
		return $that;
	}
}

$pt = PhpThumb::getInstance();
$pt->registerPlugin('GdCropper', 'gd');