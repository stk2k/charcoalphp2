<?php
/**
* exception caused by failure in creating class loader
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_CreateClassLoaderException extends Charcoal_RuntimeException
{
	private $object_path;

	public function __construct( $object_path, $prev = NULL )
	{
		$this->object_path = $object_path;

		parent::__construct( "[object_path]$object_path", $prev );
	}

	/**
	 *	get object path of failed class loader
	 */
	public function getClassLoaderPath()
	{
		return $this->object_path;
	}
}

