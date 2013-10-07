<?php
/**
* Exception when string format is not match for URI
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_TestDataNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( $test_data, $prev = NULL )
	{
		parent::__construct( "Test data not found: [$test_data]", $prev );
	}
}

