<?php
/**
* 浮動小数点クラス
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Float extends Charcoal_Number
{
	const DEFAULT_VALUE = 0.0;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $value = self::DEFAULT_VALUE )
	{
		parent::__construct( $value, Charcoal_Number::NUMBER_TYPE_FLOAT );
	}

	/**
	 * Retrieve default value
	 *
	 * @return Charcoal_Float        default value
	 */
	public static function defaultValue()
	{
		return new self(self::DEFAULT_VALUE);
	}

}

