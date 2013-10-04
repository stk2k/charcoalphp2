<?php
/**
* Exception when event-loop counter exceeded the max value
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EventLoopCounterOverflowException extends Charcoal_RuntimeException
{
	public function __construct( $max, $prev = NULL )
	{
		parent::__construct( "event-loop counter exceeded max:$max", $prev );
	}
}

