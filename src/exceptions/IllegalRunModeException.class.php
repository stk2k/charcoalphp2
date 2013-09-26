<?php
/**
* Exception when Illegal run mode is specified
*
* PHP version 5
*
* @package    exceptions
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_IllegalRunModeException extends Charcoal_RuntimeException
{
	public function __construct( $run_mode, $prev = NULL )
	{
		parent::__construct( "Illegal run mode: [$run_mode]", $prev );
	}
}

