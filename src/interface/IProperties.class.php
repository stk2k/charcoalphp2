<?php
/**
* Interface of property access
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IProperties extends Charcoal_ICollection
{
	/*
	 *  Return if property has specified key
	 *
	 * @param string $key   Key string to get
	 *
	 * @return bool   TRUE if the key exists, otherwise FALSE
	 */
	public function keyExists( $key );

	/*
	 *  Return list of all property keys
	 *
	 * @return array   list of all property keys
	 */
	public function getKeys();

	/*
	 *  Get element value
	 *
	 * @param string $key   Key string to get
	 *
	 * @return mixed   Returns NULL if key does not exist
	 */
	public function get( $key );

	/**
	 *  Get element value as string
	 *
	 * @param string $key             Key string to get
	 * @param string $default_value   default value
	 *
	 * @return Charcoal_String
	 */
	public function getString( $key, $default_value = NULL );

	/**
	 *  Get element value as array
	 *
	 * @param string $key   Key string to get
	 * @param array $default_value   default value
	 *
	 * @return Charcoal_Vector
	 */
	public function getArray( $key, $default_value = NULL );

	/**
	 *  Get element value as boolean
	 *
	 * @param string $key           Key string to get
	 * @param bool $default_value   default value
	 *
	 * @return Charcoal_Boolean
	 */
	public function getBoolean( $key, $default_value = NULL );

	/**
	 *  Get element value as integer
	 *
	 * @param string $key          Key string to get
	 * @param int $default_value   default value
	 *
	 * @return Charcoal_Integer
	 */
	public function getInteger( $key, $default_value = NULL );

	/**
	 *  Get element value as float
	 *
	 * @param string $key          Key string to get
	 * @param int $default_value   default value
	 *
	 * @return Charcoal_Float
	 */
	public function getFloat( $key, $default_value = NULL );
}

