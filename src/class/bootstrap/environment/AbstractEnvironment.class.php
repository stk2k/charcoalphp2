<?php
/**
* base class for environment
*
* PHP version 5
*
* @package    class.bootstrap.environment
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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
	public function fill( $str )
	{
		return strtr( $str, $this->values );
	}
}

