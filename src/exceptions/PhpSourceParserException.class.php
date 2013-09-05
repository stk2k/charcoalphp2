<?php
/**
* exception caused by PHP source parser
*
* PHP version 5
*
* @version    0.1
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
* @license    http://www.opensource.org/licenses/mit-license.php MIT License
*/

class Charcoal_PhpSourceParserException extends Charcoal_RuntimeException 
{
	public function __construct( $err_code, $message, Exception $prev = NULL )
	{
		parent::__construct( "[code]$err_code [message]$message", $prev );
	}

}

