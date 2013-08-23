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
	public function __construct( Charcoal_String $address, Exception $previous = NULL )
	{
		$msg = "Invalid Mail Address: address=$address";

		if ( $previous )	parent::__construct( s($msg), $previous );	else	parent::__construct( s($msg) );
	}
}

return __FILE__;