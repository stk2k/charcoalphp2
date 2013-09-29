<?php
/**
* base class for collection
*
* PHP version 5
*
* @package    classes.base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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

