<?php
/**
* Interface of property access
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IProperties
{
	/*
	 *  Get all element values
	 *
	 * @return array
	 */
	public function getAll();

	/*
	 *  Return if property has specified key
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return bool   TRUE if the key exists, otherwise FALSE
	 */
	public function keyExists( Charcoal_String $key );

	/*
	 *  Return list of all property keys
	 *
	 * @return array   list of all property keys
	 */
	public function getKeys();

	/*
	 *  Get element value
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return mixed   Returns NULL if key does not exist
	 */
	public function get( Charcoal_String $key );

	/**
	 *  Get element value as string
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_String
	 */
	public function getString( Charcoal_String $key, Charcoal_String $default_value = NULL );

	/**
	 *  Get element value as array
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Vector
	 */
	public function getArray( Charcoal_String $key, Charcoal_Vector $default_value = NULL );

	/**
	 *  Get element value as boolean
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Boolean
	 */
	public function getBoolean( Charcoal_String $key, Charcoal_Boolean $default_value = NULL );

	/**
	 *  Get element value as integer
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Integer
	 */
	public function getInteger( Charcoal_String $key, Charcoal_Integer $default_value = NULL );

	/**
	 *  Get element value as float
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Float
	 */
	public function getFloat( Charcoal_String $key, Charcoal_Float $default_value = NULL );
}

