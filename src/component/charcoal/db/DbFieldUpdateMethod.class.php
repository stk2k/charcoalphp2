<?php
/**
* DB Value Object
*
* PHP version 5
*
* @package    component.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_DbFieldUpdateMethod extends Charcoal_Object
{
	const UPDATE_BY_NULL = 1;
	const UPDATE_BY_NOW  = 2;

	private $value_type;

	/*
	 *  Constructor
	 */
	public function __construct( $value_type )
	{
		$this->value_type = $value_type;
	}

	/*
	 *  get null value
	 */
	public function getValueType()
	{
		return $this->value_type;
	}

	/*
	 *  update by NULL
	 */
	public static function updateByNull()
	{
		return new self( self::UPDATE_BY_NULL );
	}

	/*
	 *  update by NOW
	 */
	public static function updateByNow()
	{
		return new self( self::UPDATE_BY_NOW );
	}

}

