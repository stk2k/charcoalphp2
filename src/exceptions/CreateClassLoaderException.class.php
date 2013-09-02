<?php
/**
* クラスローダ設定例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CreateClassLoaderException extends Charcoal_RuntimeException
{
	private $object_path;

	public function __construct( Charcoal_ObjectPath $object_path, Exception $prev = NULL )
	{
		$this->object_path = $object_path;

		$msg  = '[object_path]' . $object_path->toString();

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}

	/**
	 *	get object path of failed class loader
	 */
	public function getClassLoaderPath()
	{
		return $this->object_path;
	}
}

