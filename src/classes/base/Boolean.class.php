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
	public function __construct( $value = FALSE )
	{
		parent::__construct();

		if ( $value === TRUE || $value === FALSE ){
			$this->_value = $value;
		}
		else if ( $value instanceof Charcoal_Boolean ){
			$this->_value = $value->unbox();
		}
		else if ( $value === NULL ){
			$value = FALSE;
		}
		else{
			_throw( new Charcoal_NonBooleanException( $value ) );
		}
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

