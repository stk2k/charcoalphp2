<?php
/**
* 数値クラス
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Number extends Charcoal_Primitive
{
	const NUMBER_TYPE_INTEGER    = 0;
	const NUMBER_TYPE_FLOAT      = 1;

	private $_value;
	private $_type;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $value, $type, $default_value = NULL )
	{
		parent::__construct();

		if ( $value instanceof Charcoal_String ){
			$value = $value->getValue();
		}
		else if ( $value instanceof Charcoal_Number ){
			$value = $value->getValue();
		}
		
		// check if value is numeric
		if ( !is_numeric($value) ){
			if ( $default_value === NULL ){
				_throw( new NonNumberException( $value ) );
			}
			$value = $default_value;
		}

		switch( $type ){
		case self::NUMBER_TYPE_INTEGER:
			$this->_value = intval($value);
			break;
		case self::NUMBER_TYPE_FLOAT:
			$this->_value = floatval($value);
			break;
		}
		$this->_type = $type;
	}

	/**
	 *	unbox primitive value
	 */
	public function unbox()
	{
		return $this->_value;
	}

	/*
	 *	一致
	 */
	public function equals( Charcoal_Object $object )
	{
		if ( $object instanceof Charcoal_Number ){
			return $this->_value == $object->_value;
		}
		return parent::equals( $object );
	}

	/*
	 *	比較
	 */
	public function compare( Charcoal_Object $object )
	{
		if ( $object instanceof Charcoal_Number ){
			return $this->_value > $object->_value;
		}
		return parent::equals( $object );
	}

	/*
	 *	値を取得
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/*
	 *	整数値として値を取得
	 */
	public function integerValue()
	{
		return intval($this->_value);
	}

	/*
	 *	浮動小数点数として値を取得
	 */
	public function floatValue()
	{
		return floatval($this->_value);
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return strval($this->_value);
	}
}

