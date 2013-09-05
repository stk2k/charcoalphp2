<?php
/**
* Invalid mail format exception
*
* PHP version 5
*
* @package    exceptions
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_InvalidMailAddressException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $address, $prev = NULL )
	{
		parent::__construct( "Invalid Mail Address: address=$address", $prev );
	}
}

