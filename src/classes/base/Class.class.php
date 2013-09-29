<?php
/**
* Class information class
*
* PHP version 5
*
* @package    classes.base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Class extends Charcoal_Object
{
	private $class_name;

	/*
	 *	Constructor
	 */
	public function __construct( $class_name )
	{
//		Charcoal_ParamTrait::checkString( 1, $class_name );

		parent::__construct();

		if ( !class_exists($class_name) ){
			_throw( new Charcoal_ClassNotFoundException( $class_name ) );
		}

		$this->class_name = us( $class_name );
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
	 * @param array $args       arguments for constructoor
	 *
	 * @return string
	 */
	public function newInstance( $args = NULL )
	{
//		Charcoal_ParamTrait::checkVector( 1, $args, TRUE );

		$args = uv($args);

		switch( count($args) ){
		case 0:	return new $this->class_name();
		case 1:	return new $this->class_name( $args[0] );
		case 2:	return new $this->class_name( $args[0], $args[1] );
		case 3:	return new $this->class_name( $args[0], $args[1], $args[2] );
		case 4:	return new $this->class_name( $args[0], $args[1], $args[2], $args[3] );
		case 5:	return new $this->class_name( $args[0], $args[1], $args[2], $args[3], $args[4] );
		case 6:	return new $this->class_name( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5] );
		case 7:	return new $this->class_name( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6] );
		}

		return NULL;
/*
		try{
			// reflection object
			$ref_class = new ReflectionClass( $this->class_name );

			$object = $args ? $ref_class->newInstanceArgs( uv($args) ) : $ref_class->newInstanceArgs();

			return $object;
		}
		catch( ReflectionException $ex ){
			_throw( new Charcoal_ClassNewException( $this, $args, $ex ) );
		}
		catch( Exception $ex ){
			_throw( new Charcoal_ClassNewException( $this, $args, $ex ) );
		}
*/
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

