<?php
/**
* framework basic object class
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_CharcoalObject extends Charcoal_Object
{
	public $obj_name;
	public $obj_path;
	public $type_name;

	/**
	 *	constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->obj_name = Charcoal_System::snakeCase( get_class($this) );
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
	}

	/**
	 * destruct instance
	 */
	public function terminate()
	{
	}

	/**
	 *  get object name
	 *
	 * @return string           object name
	 */
	public function getObjectName()
	{
		return $this->obj_name;
	}

	/**
	 *  set object name
	 *
	 * @param Charcoal_String $obj_name          object name
	 */
	public function setObjectName( Charcoal_String $obj_name )
	{
		$this->obj_name = $obj_name;
	}

	/**
	 *   returns object path
	 *
	 * @return string           object path
	 */
	public function getObjectPath()
	{
		return $this->obj_path;
	}

	/**
	 *   set object path
	 *
	 * @param Charcoal_String $obj_path          object path
	 */
	public function setObjectPath( Charcoal_ObjectPath $obj_path )
	{
		$this->obj_path = $obj_path;
	}

	/**
	 *   returns type name
	 *
	 * @return string           type name
	 */
	public function getTypeName()
	{
		return $this->type_name;
	}

	/**
	 *   set type name
	 *
	 * @param Charcoal_String $type_name          type name
	 */
	public function setTypeName( Charcoal_String $type_name )
	{
		$this->type_name = $type_name;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		$clazz = get_class($this);
		$hash = $this->hash();
		$path = $this->obj_path ? $this->obj_path->getObjectPathString() : '(new)';
		$type = $this->type_name ? $this->type_name : '';

		return "[class=$clazz hash=$hash path=$path type=$type]";
	}
}
