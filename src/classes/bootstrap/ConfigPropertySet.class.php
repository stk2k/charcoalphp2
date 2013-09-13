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
	public function __construct( $values = NULL )
	{
//		Charcoal_ParamTrait::checkRawArray( 1, $values, TRUE );

		parent::__construct( $values );
	}

	/**
	 *  Get child section
	 */
	public function getSection( $section )
	{
//		Charcoal_ParamTrait::checkString( 1, $section );

		$value = parent::get( $section );

		if ( !is_array($value) ){
			_throw( new Charcoal_ConfigSectionNotFoundException( $section ) );
		}

		return new Charcoal_ConfigPropertySet( $value );
	}

	/**
	 * Get as string value
	 *
	 * @param string $key             key string for hash map
	 * @param string $default_value   default value
	 * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
	 *
	 * @return string
	 */
	public function getString( $key, $default_value = NULL, $process_macro = FALSE )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkString( 2, $default_value, TRUE );

		$key = us($key);
		$value = parent::getString( $key, $default_value );

		return $process_macro ? Charcoal_ResourceLocator::processMacro( $value ) : $value;
	}

	/**
	 * Get as array value
	 *
	 * @param string $key             key string for hash map
	 * @param array $default_value   default value
	 * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
	 *
	 * @return array
	 */
	public function getArray( $key, $default_value = NULL, $process_macro = FALSE )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);
		$items = parent::getArray( $key, $default_value );
		if ( $process_macro === TRUE ){
			$items = array_map( 'Charcoal_ResourceLocator::processMacro', $items );
		}
		return $items;
	}

	/**
	 * Get as boolean value
	 *
	 * @param string $key             key string for hash map
	 * @param bool $default_value   default value
	 *
	 * @return bool
	 */
	public function getBoolean( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);
		return parent::getBoolean( $key, $default_value );
	}

	/**
	 * Get as integer value
	 *
	 * @param string $key             key string for hash map
	 * @param int $default_value   default value
	 *
	 * @return int
	 */
	public function getInteger( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);
		return parent::getInteger( $key, $default_value );
	}

	/**
	 *  Get element value as float
	 *
	 * @param string $key            Key string to get
	 * @param float $default_value   default value
	 *
	 * @return float
	 */
	public function getFloat( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);
		return parent::getFloat( $key, $default_value );
	}

}

