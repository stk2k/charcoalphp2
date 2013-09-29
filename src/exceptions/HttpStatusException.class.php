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

class Charcoal_HttpStatusException extends Charcoal_RuntimeException
{
	private $status;

	public function __construct( $status, $prev = NULL )
	{
		$this->status = $status;

		parent::__construct( "HTTP status error: status=[$status]", $prev );
	}

	/**
	 *  HTTP status code
	 */
	function getStatusCode()
	{
		return $this->status;
	}
}

