<?php
/**
* Exception when creating layou tmanager fails
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_LayoutManagerCreationException extends Charcoal_RuntimeException
{
	public function __construct( $layout_manager, $prev = NULL )
	{
		parent::__construct( "Failed to create layout manager: [$layout_manager]", $prev );
	}
}

