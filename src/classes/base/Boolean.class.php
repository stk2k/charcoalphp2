<?php
/**
* 真偽値クラス
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Boolean extends Charcoal_Primitive
{
	private $_value;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $value, $default_value = NULL )
	{
		parent::__construct();

		if ( is_null($value) ){
			if ( $default_value === NULL ){
				_throw( new NullPointerException() );
			}
			$value = $default_value;
		}
		else if ( gettype($value) !== 'boolean' ){
			if ( $default_value === NULL ){
				_throw( new BooleanFormatException( System::toString($value) ) );
			}
			$value = $default_value;
		}
		$this->_value      = $value;
	}

    /**
     *	unbox primitive value
     */
    public function unbox()
    {
        return $this->_value;
    }

	/*
	 *	値を取得
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/*
	 *	真か
	 */
	public function isTrue()
	{
		return $this->_value ? TRUE : FALSE;
	}

	/*
	 *	偽か
	 */
	public function isFalse()
	{
		return $this->_value ? FALSE : TRUE;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->_value ? 'TRUE' : 'FALSE';
	}
}

