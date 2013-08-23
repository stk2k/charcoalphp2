<?php
/**
* HTTPの例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpException extends Charcoal_RuntimeException
{
	private $_status;

	public function __construct( Charcoal_Integer $status_code, Exception $previous = NULL )
	{
		$this->_status = $status_code;

		$msg = "[status_code]$status_code";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

	/**
	 *   ステータスコード
	 */
	function getStatusCode()
	{
		return $this->_status;
	}
}

return __FILE__;