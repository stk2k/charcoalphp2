<?php
/**
*  String primitive class
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_String extends Charcoal_Scalar
{
    const DEFAULT_VALUE = '';

    private $value;
    private $is_mb;
    private $mb_encoding;

    /**
     *    Constructor
     *
     * @param mixed $value
     * @param string $encoding
     */
    public function __construct( $value = self::DEFAULT_VALUE, $encoding = NULL )
    {
        parent::__construct();

        $this->is_mb = extension_loaded('mbstring');
        if ( $this->is_mb ){
            $this->mb_encoding = $encoding ? $encoding : mb_internal_encoding();
        }

        if ( is_string($value) ){
            $this->value = $value;
        }
        elseif ( $value instanceof Charcoal_String ){
            $this->value = $value->unbox();
        }
        elseif ( $value instanceof Charcoal_Object ){
            $this->value = $value->toString();
        }
        elseif ( is_scalar($value) ){
            $this->value = strval($value);
        }
        elseif ( $value === NULL ){
            $this->value = '';
        }
        else{
            _throw( new Charcoal_NonStringException( $value ) );
        }
    }


    /**
     * Retrieve default value
     *
     * @param string $encoding
     *
     * @return Charcoal_String        default value
     */
    public static function defaultValue($encoding)
    {
        return new self(self::DEFAULT_VALUE, $encoding);
    }

    /**
     *    unbox primitive value
     */
    public function unbox()
    {
        return $this->value;
    }

    /**
     *    get raw value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *    get character encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->mb_encoding;
    }

    /**
     *    set character encoding
     *
     * @param string $encoding       chracter encoding
     */
    public function setEncoding( $encoding )
    {
        $this->mb_encoding = $encoding;
    }

    /**
     *    get string length
     *
     * @return integer       length of the string or -1 if fails
     */
    public function length()
    {
        return $this->is_mb ? mb_strlen($this->value, $this->mb_encoding) : strlen($this->value);
    }

    /**
     *    split by delimiter
     *
     * @param string $delimiter        delimiter string
     *
     * @return Charcoal_Vector         separated strings
     */
    public function split( $delimiter )
    {
        $strings = explode( us($delimiter), $this->value );
        return v($strings);
    }

    /**
     *    split by regular expression
     *
     * @param string $regex      regular expression string
     *
     * @return Charcoal_Vector
     */
    public function splitRegEx( $regex )
    {
        $regex = us($regex);
        $string = $this->value;
        $matches = array();
        $split_word_list = array();

        if ( $cnt = preg_match_all( $regex, $string, $matches, PREG_OFFSET_CAPTURE ) ){
            $start_pos = 0;
            for($i=0;$i<$cnt;$i++){
                $match_str = $matches[$i][0];
                $match_pos = $matches[$i][1];
                $end_pos = $match_pos - 1;
                $length = $end_pos - $start_pos + 1;
                if ( $length > 0 ){
                    $split_word = substr( $string, $match_pos, $length );
                    $split_word_list[] = $split_word;
                }
                $start_pos = $match_pos + strlen($match_str);
            }
        }

        return v($split_word_list);
    }

    /**
     *    compare to another string
     *
     * @return boolean    TRUE if this object is equal to the string which is passed by argument
     */
    public function equals( $object )
    {
        $str1 = $this->value;

        if ( $object instanceof Charcoal_String ){
            $str2 = $object->value;
        }
        elseif ( is_string($object) ){
            $str2 = $object;
        }
        else{
            return FALSE;
        }

        return strcmp($str1,$str2) === 0;
    }

    /**
     *    check if this object is empty
     *
     * @return boolean    TRUE if this object is empty, otherwise returns FALSE
     */
    public function isEmpty()
    {
        if ( !$this->value ){
            return TRUE;
        }
        if ( is_string($this->value) ){
            return strlen($this->value) === 0;
        }
        return TRUE;
    }

    /**
     *    append another string
     *
     * @param string $add     string to add
     *
     * @return Charcoal_String         this object
     */
    public function append( $add )
    {
        $this->value .= us($add);
        return $this;
    }

    /**
     *    covert this string to upper case
     *
     * @return Charcoal_String         this object
     */
    public function toUpper()
    {
        $this->value = strtoupper( $this->value );
        return $this;
    }

    /**
     *    covert this string to lower case
     *
     * @return Charcoal_String         this object
     */
    public function toLower()
    {
        $this->value = strtolower( $this->value );
        return $this;
    }

    /**
     *    erase white spaces(or specified characters) within this object
     *
     * @param array $charlist          trim target character set
     *
     * @return Charcoal_String         this object
     */
    public function trim( $charlist = NULL )
    {
        $this->value = $charlist ?  trim( $this->value, us($charlist) ) : trim( $this->value );
        return $this;
    }

    /**
     *    Strip whitespace (or other characters) from the beginning of a string
     *
     * @param array $charlist          trim target character set
     *
     * @return Charcoal_String         this object
     */
    public function ltrim( $charlist = NULL )
    {
        $this->value = $charlist ?  ltrim( $this->value, us($charlist) ) : ltrim( $this->value );
        return $this;
    }

    /**
     *    Strip whitespace (or other characters) from the end of a string
     *
     * @param array $charlist          trim target character set
     *
     * @return Charcoal_String         this object
     */
    public function rtrim( $charlist = NULL )
    {
        $this->value = $charlist ?  rtrim( $this->value, us($charlist) ) : rtrim( $this->value );
        return $this;
    }

    /**
     *    Inserts HTML line breaks before all newlines in a string
     *
     * @param bool $is_xhtml          Whether to use XHTML compatible line breaks or not.
     *
     * @return Charcoal_String         this object
     */
    public function nl2br( $is_xhtml = true )
    {
        $this->value = nl2br( $this->value, ub($is_xhtml) );
        return $this;
    }

    /**
     *    replace some keyword into specified string
     *
     * @param string $search          The value being searched for
     * @param string $replace         The replacement value that replaces found search values.
     *
     * @return Charcoal_String         this object
     */
    public function replace( $search, $replace )
    {
        $this->value = str_replace( $search, $replace, $this->value );
        return $this;
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }
}

