<?php
/**
* environment implemented by array
*
* PHP version 5
*
* @package    class.bootstrap.environment
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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

