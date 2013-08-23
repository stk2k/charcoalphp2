<?php
/**
* Class which contains config propery set
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ConfigPropertySet extends Charcoal_HashMap
{
	/**
	 *  Constructor
	 */
	public function __construct( array $data = NULL )
	{
		parent::__construct( $data );
	}

	/**
	 *  Get child section
	 */
	public function getSection( Charcoal_String $section )
	{
		$value = parent::get( us($section) );

		if ( !is_array($value) ){
			_throw( new Charcoal_ConfigSectionNotFoundException( $section ) );
		}

		return new Charcoal_ConfigPropertySet( $value );
	}

	/**
	 * Get as string value
	 *
	 * @param Charcoal_String $key             key string for hash map
	 * @param Charcoal_String $default_value   default value
	 */
	public function getString( Charcoal_String $key, Charcoal_String $default_value = NULL )
	{
		$value = parent::get( us($key) );

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		// throws exception if the element's type is not match for required type
		if ( !is_string($value) && !($value instanceof Charcoal_String) ){
			_throw( new Charcoal_StringFormatException( $value, "key=[$key]" ) );
		}

		// processes macro
		$value = Charcoal_ResourceLocator::processMacro( s($value) );

		return s($value);
	}

	/**
	 * Get as array value
	 *
	 * @param Charcoal_String $key             key string for hash map
	 * @param Charcoal_Vector $default_value   default value
	 */
	public function getArray( Charcoal_String $key, Charcoal_Vector $default_value = NULL )
	{
		$value = parent::get( us($key) );

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		// split values by comma
		$array = explode( ',', $value );

		// remove spaces
		foreach( $array as $_key => $value ){
			$value = trim($value);
			if ( strlen($value) == 0 ){
				unset( $array[$_key] );
			}
			else{
				$array[$_key] = us( $value );
			}
		}

		// return default value if the element count is one and whitespace
		if ( count($array) == 1 && $array[0] === '' ){
			return $default_value !== NULL ? $default_value : new Charcoal_Vector();
		}

		// throws exception if the element's type is not match for required type
		if ( !is_array($array) ){
			_throw( new Charcoal_ArrayFormatException( $value, "key=[$key]" ) );
		}

		// processes macro
		foreach( $array as $_key => $value ){
			$value = Charcoal_ResourceLocator::processMacro(s($value));
			$array[$_key] = us($value);
		}

		return  v($array);
	}

	/**
	 * Get as boolean value
	 *
	 * @param Charcoal_String $key             key string for hash map
	 * @param Charcoal_Boolean $default_value   default value
	 */
	public function getBoolean( Charcoal_String $key, Charcoal_Boolean $default_value = NULL )
	{
		$value = parent::get( us($key) );

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		if ( is_string($value) ){
			$value = (strlen($value) > 0 );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_bool($value) && !($value instanceof Charcoal_Boolean) ){
			_throw( new BooleanFormatException( $value, "key=[$key]" ) );
		}

		return b($value);
	}

	/**
	 * Get as integer value
	 *
	 * @param Charcoal_String $key             key string for hash map
	 * @param Charcoal_Integer $default_value   default value
	 */
	public function getInteger( Charcoal_String $key, Charcoal_Integer $default_value = NULL )
	{
		$value = parent::get( us($key) );

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		if ( $value instanceof Charcoal_Integer ){
			return $value;
		}

		// throws exception if the element's type is not match for required type
		if ( !is_numeric($value) ){
			_throw( new Charcoal_IntegerFormatException( $value, "key=[$key]" ) );
		}

		return i($value);
	}

}

return __FILE__;