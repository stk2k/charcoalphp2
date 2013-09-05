<?php
/**
*  String primitive class(immutable)
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_String extends Charcoal_Primitive
{
	private $_value;

	/**
	 *	Constructor
	 */
	public function __construct( $value = '' )
	{
		parent::__construct();

		if ( is_string($value) ){
			$this->_value = $value;
		}
		else if ( $value instanceof Charcoal_String ){
			$this->_value = $value->unbox();
		}
		else if ( $value instanceof Charcoal_Object ){
			$this->_value = $value->toString();
		}
		else if ( is_scalar($value) ){
			$this->_value = strval($value);
		}
		else if ( $value === NULL ){
			$this->_value = '';
		}
		else{
			_throw( new Charcoal_NonStringException( $value ) );
		}
	}

	/**
	 *	unbox primitive value
	 */
	public function unbox()
	{
		return $this->_value;
	}

	/**
	 *	get raw value
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 *	get string length
	 *
	 * @return integer       length of the string or -1 if fails
	 */
	public function length()
	{
		return $this->_value && is_string($this->_value) ? strlen($this->_value) : -1;
	}

	/**
	 *	append another string
	 *
	 * @param Charcoal_String $add     string to add
	 *
	 * @return Charcoal_String         combined string
	 */
	public function append( Charcoal_String $add )
	{
		return new Charcoal_String( $this->_value . $add->_value );
	}

	/**
	 *	split by delimiter
	 *
	 * @param Charcoal_String $delimiter      delimiter string
	 */
	public function split( Charcoal_String $delimiter )
	{
		$string     = $this->_value;
		$delimiter  = us( $delimiter );
		$explode    = explode( $delimiter, $string );

		return new Charcoal_Vector( $explode );
	}

	/**
	 *	split by regular expression
	 *
	 * @param Charcoal_String $regex      regular expression string
	 */
	public function splitRegEx( Charcoal_String $regex )
	{
		$string = $this->_value;
		$regex  = us( $regex );
		$matches = array();
		$split_word_list = NULL;

		if ( $cnt = preg_match_all( $regex, $string, $matches, PREG_OFFSET_CAPTURE ) ){
			$start_pos = 0;
			for($i=0;$i<$cnt;$i++){
				$match_str = $matches[$i][0];
				$match_pos = $matches[$i][1];
				$end_pos = $match_pos - 1;
				$length = $end_pos - $start_pos + 1;
				if ( $length > 0 ){
					$split_word = substr( $string, $start, $length );
					$split_word_list[] = $split_word;
				}
				$start_pos = $match_pos + strlen($match_str);
			}
		}

		return $split_word_list;
	}

	/**
	 *	compare to another string
	 *
	 * @return boolean    TRUE if this object is equal to the string which is passed by argument
	 */
	public function equals( Charcoal_Object $obj )
	{
		$str1 = $this->_value;

		if ( $obj instanceof Charcoal_String ){
			$str2 = $obj->_value;
		}
		else if ( is_string($obj) ){
			$str2 = $obj;
		}
		else{
			return FALSE;
		}

		return strcmp($str1,$str2) === 0;
	}

	/**
	 *	check if this object is empty
	 *
	 * @return boolean    TRUE if this object is empty, otherwise returns FALSE
	 */
	public function isEmpty()
	{
		if ( !$this->_value ){
			return TRUE;
		}
		if ( is_string($this->_value) ){
			return strlen($this->_value) === 0;
		}
		return TRUE;
	}

	/**
	 *	covert this string to upper case
	 *
	 */
	public function toUpper()
	{
		return $this->_value ? strtoupper($this->_value) : NULL;
	}

	/**
	 *	covert this string to lower case
	 *
	 */
	public function toLower()
	{
		return $this->_value ? strtolower($this->_value) : NULL;
	}

	/**
	 *	erase white spaces within this object
	 *
	 */
	public function trim(String $charlist = NULL)
	{
		$s = $this->_value;
		$s = $charlist ?  trim($s,us($charlist)) : trim($s);
		return s($s);
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->_value;
	}
}

