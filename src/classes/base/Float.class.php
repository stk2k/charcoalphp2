<?php
/**
* 浮動小数点クラス
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Float extends Charcoal_Number
{
	/*
	 *	コンストラクタ
	 */
	public function __construct( $value = 0.0 )
	{
		parent::__construct( $value, Charcoal_Number::NUMBER_TYPE_FLOAT );
	}

}

