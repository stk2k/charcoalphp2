<?php
/**
* framework basic object class
*
* PHP version 5
*
* @package    classes.base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_CharcoalObject extends Charcoal_Object
{
	private $obj_name;
	private $obj_path;
	private $type_name;
	private $sandbox;

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
	public function configure( $config )
	{
//		Charcoal_ParamTrait::checkConfig( 1, $config );

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
	public function setObjectName( $obj_name )
	{
//		Charcoal_ParamTrait::checkString( 1, $obj_name );

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
	public function setObjectPath( $obj_path )
	{
//		Charcoal_ParamTrait::checkObjectPath( 1, $obj_path );

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
	 * @param string $type_name          type name
	 */
	public function setTypeName( $type_name )
	{
//		Charcoal_ParamTrait::checkString( 1, $type_name );

		$this->type_name = $type_name;
	}

	/**
	 *   returns sandbox
	 *
	 * @return string           sandbox object
	 */
	public function getSandbox()
	{
		return $this->sandbox;
	}

	/**
	 *   set sandbox
	 *
	 * @param Charcoal_Sandbox $sandbox          sandbox object
	 */
	public function setSandbox( $sandbox )
	{
//		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;
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
		$path = $this->obj_path ? $this->obj_path : '(new)';
		$type = $this->type_name ? $this->type_name : '';

		return "[class=$clazz hash=$hash path=$path type=$type]";
	}
}
