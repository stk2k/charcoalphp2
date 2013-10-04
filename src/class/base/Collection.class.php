<?php
/**
* base class for collection
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

abstract class Charcoal_Collection extends Charcoal_Object implements Iterator, Countable, Charcoal_IUnboxable
{
	protected $values;

	/**
	 *	Applies a callback to all elements
	 *
	 * @return array
	 */
	public function map( $callable )
	{
		$this->values = array_map( $callable, $this->values );
		return $this;
	}

}

