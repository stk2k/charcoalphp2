<?php
/**
* exception caused by HTTP status error 
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpException extends Charcoal_RuntimeException
{
	private $status_code;

	public function __construct( $status_code, $prev = NULL )
	{
		$this->status_code = $status_code;

		parent::__construct( "[status_code]$status_code", $prev );
	}

	/**
	 *  HTTP status code
	 */
	function getStatusCode()
	{
		return $this->status_code;
	}
}

