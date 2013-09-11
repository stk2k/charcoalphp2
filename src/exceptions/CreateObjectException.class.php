<?php
/**
* exception caused by failure in creating object
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CreateObjectException extends Charcoal_RuntimeException
{
	private $obj_path;

	public function __construct( $obj_path, $type_name, $prev = NULL )
	{
		$this->obj_path = $obj_path;

		parent::__construct( "Creating Charcoal Object Failed. [obj_path]$obj_path [type_name]$type_name", $prev );
	}

	/**
	 *  get object path
	 */
	public function getObjectPath()
	{
		return $this->obj_path;
	}

}

