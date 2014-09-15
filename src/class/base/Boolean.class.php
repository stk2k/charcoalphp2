<?php
/**
* 真偽値クラス
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Boolean extends Charcoal_Scalar
{
	const DEFAULT_VALUE = FALSE;

	private $value;

	/**
	 *	Constructor
	 *
	 *	@param mixed $value        boolean value to set
	 */
	public function __construct( $value = self::DEFAULT_VALUE )
	{
		parent::__construct();

		if ( $value === TRUE || $value === FALSE ){
			$this->value = $value;
		}
		elseif ( $value instanceof Charcoal_Boolean ){
			$this->value = $value->unbox();
		}
		elseif ( $value === NULL ){
			$value = FALSE;
		}
		else{
			_throw( new Charcoal_NonBooleanException( $value ) );
		}
	}

	/**
	 * Retrieve default value
	 *
	 * @return Charcoal_Boolean        default value
	 */
	public static function defaultValue()
	{
		return new self(self::DEFAULT_VALUE);
	}

    /**
     *	unbox primitive value
     *	
     *	@return boolean        internal primitive value of this object
     */
    public function unbox()
    {
        return $this->value;
    }

	/**
	 *	Retrieve raw value
     *	
     *	@return boolean        internal primitive value of this object
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 *	Return TRUE if internal value can be evaluated as TRUE
     *	
     *	@return boolean        TRUE if internal value can be evaluated as TRUE
	 */
	public function isTrue()
	{
		return $this->value ? TRUE : FALSE;
	}

	/**
	 *	Return TRUE if internal value can be evaluated as FALSE
     *	
     *	@return boolean        TRUE if internal value can be evaluated as FALSE
	 */
	public function isFalse()
	{
		return $this->value ? FALSE : TRUE;
	}

	/**
	 *	Applies logical operation 'AND' on this object
     *	
     *	@param boolean $target       operator target
	 */
	public function operateAnd( $target )
	{
		$this->value &= ub($target);
	}

	/**
	 *	Applies logical operation 'OR' on this object
     *	
     *	@param boolean $target       operator target
	 */
	public function operateOr( $target )
	{
		$this->value |= ub($target);
	}

	/**
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->value ? 'TRUE' : 'FALSE';
	}
}

