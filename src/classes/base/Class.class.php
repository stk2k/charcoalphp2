<?php
/**
* Class information class
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Class extends Charcoal_Object
{
	private $class_name;

	/*
	 *	Constructor
	 */
	public function __construct( Charcoal_String $class_name )
	{
		parent::__construct();

		if ( !class_exists($class_name) ){
			_throw( new Charcoal_ClassNotFoundException( $class_name ) );
		}

		$this->class_name = $class_name;
	}

	/*
	 *  Get class name
	 *
	 * @return string    class name
	 */
	public function getClassName()
	{
		return $this->class_name;
	}

	/*
	 *  Create new instance
	 *
	 * @return string
	 */
	public function newInstance( Charcoal_Vector $args = NULL )
	{
		try{
			// reflection object
			$ref_class = new ReflectionClass( us($this->class_name) );

			$object = $args ? $ref_class->newInstanceArgs( uv($args) ) : $ref_class->newInstanceArgs();

			return $object;
		}
		catch( ReflectionException $ex ){
			$args = $args ? $args : new Charcoal_Vector();
			_throw( new Charcoal_ClassNewException( $this, $args, $ex ) );
		}
		catch( Exception $ex ){
			$args = $args ? $args : new Charcoal_Vector();
			_throw( new Charcoal_ClassNewException( $this, $args, $ex ) );
		}
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return __CLASS__ . '[' . $this->class_name . ']';
	}
}
return __FILE__;
