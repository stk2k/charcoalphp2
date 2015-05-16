<?php
/**
* Utility class for debug
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DebugUtil
{
	/**
	 *	Describe object/array structure. If an object is specified, this method will return or output it's own
	 *	method names or properties.
	 *
	 * @param mixed $o                  target object or array
	 * @param boolean $return           if true, return result as string. otherwise, this method output to buffer
	 */
	public static function describe( $o, $return = false )
	{
		$result = '';

		$type = gettype($o);
		switch($type){
		case 'string':
		case 'string':
		case 'integer':
		case 'double':
		case 'boolean':
		case 'NULL':
		case 'unknown type':
		case 'array':
			$result = 'type:' . $type;
			break;
		case 'object':
			$result = 'type:' . $type . eol();
			$result .= 'class name:' . get_class($o) . eol();
			$ref_class = new ReflectionClass($o);

			// output parent class
			$ref_parent = $ref_class->getParentClass();
			$result .= 'parent class:' . eol();
			if ( $ref_parent ){
				$result .= self::describeParentClass( $ref_parent, 1 );
			}

			// output methods
			$result .= 'methods:' . eol();
			$methods = $ref_class->getMethods();
			foreach( $methods as $ref_method ){
				$result .= space(4) . $ref_method->getName() . eol();
			}

			// output properties
			$result .= 'properties:' . eol();
			$props = $ref_class->getProperties();
			foreach( $props as $ref_prop ){
				$result .= space(4) . $ref_prop->getName() . eol();
			}
			break;
		}

		if ( $return ){
			return $result;
		}
		else{
			echo $result;
		}
	}

	/**
	 *	Describe object/array structure. If an object is specified, this method will return or output it's own
	 *	method names or properties.
	 *
	 * @param ReflectionClass $ref_class        target class
	 * @param integer $indent                   indent count
	 */
	public static function describeParentClass( ReflectionClass $ref_class, $indent )
	{
		$result = '';

		$result .= space($indent*4) . $ref_class->getName() . eol();

		$ref_parent = $ref_class->getParentClass();
		if ( $ref_parent ){
			$result .= self::describeParentClass( $ref_parent, $indent ++ );
		}

		return $result;
	}
}


