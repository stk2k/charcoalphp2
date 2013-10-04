<?php
/**
* Integer wrapper class
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Integer extends Charcoal_Number
{
	/*
	 *	constructor
	 */
	public function __construct( $value = 0 )
	{
		parent::__construct( $value, Charcoal_Number::NUMBER_TYPE_INTEGER );
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

