<?php
/**
* Exception which means no sections are found in configure file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ConfigSectionNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( $section, $prev = NULL )
	{
		parent::__construct( "section($section) is not found", $prev );
	}

}

