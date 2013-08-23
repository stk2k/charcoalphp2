<?php
/**
* Integer wrapper class
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Integer extends Charcoal_Number
{
	/*
	 *	constructor
	 */
	public function __construct( $value, $default_value = NULL )
	{
		parent::__construct( $value, Charcoal_Number::NUMBER_TYPE_INTEGER, $default_value );
	}

	/*
	 *	add integer value
	 */
	public function add( Charcoal_Integer $add )
	{
		return new Charcoal_Integer($this->getValue() + $add->getValue());
	}

	/*
	 *	increment value
	 */
	public function increment()
	{
		$value = $this->getValue();
		return new Charcoal_Integer(++$value);
	}

	/*
	 *	decrement value
	 */
	public function decrement()
	{
		$value = $this->getValue();
		return new Charcoal_Integer(--$value);
	}
}
return __FILE__;
