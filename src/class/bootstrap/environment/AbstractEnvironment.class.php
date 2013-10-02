<?php
/**
* base class for environment
*
* PHP version 5
*
* @package    class.bootstrap.environment
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

abstract class Charcoal_AbstractEnvironment extends Charcoal_Object implements Charcoal_IEnvironment
{
	protected $values;

	/**
	 *  Constructor
	 */
	public function __construct( $values = array() )
	{
		$this->values = $values;

		parent::__construct();
	}

	/**
	 * get configuration data by key
	 * 
	 * @param string $key      environment data id
	 *
	 * @return string           configuration data
	 */
	public function get( $key )
	{
		$key = us($key);
		return isset($this->values[$key]) ? $this->values[$key] : '';
	}

	/**
	 * update environment data by key
	 * 
	 * @param string $key      environment data id
	 * @param string $value    value to update
	 */
	public function set( $key, $value )
	{
		$key = us($key);
		$this->values[$key] = us($value);
	}

	/**
	 * replace string by environment values
	 * 
	 * @param string $str       target string for replacing
	 *
	 * @return string           replaced string
	 */
	public function replace( $str )
	{
		$keys = array_keys( $this->values );
		$values = array_values( $this->values );

		$str = str_replace( $keys, $values, $str );

		return s($str);
	}
}
