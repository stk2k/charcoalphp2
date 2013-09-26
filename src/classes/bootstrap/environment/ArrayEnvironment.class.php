<?php
/**
* environment implemented by array
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ArrayEnvironment extends Charcoal_AbstractEnvironment
{
	/**
	 *  Constructor
	 */
	public function __construct( $values = array() )
	{
		$this->values = $values;

		parent::__construct();
	}

}

