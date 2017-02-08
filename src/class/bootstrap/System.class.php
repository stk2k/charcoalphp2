<?php
/**
* Low level functions substituting for global functions
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_System
{
    /** length of output in string conversion methods */
    const TOSTRING_MAX_LENGTH     = 9999;

    /** length of output in dump methods */
    const DUMP_MAX_LENGTH         = 4096;

    /** Used at isBitSet(), means test if any of bit field is set */
    const BITTEST_MODE_ALL = 1;

    /** Used at isBitSet(), means test if any of bit field is set */
    const BITTEST_MODE_ANY = 2;

    /**
     *  Get all defined constants
     *
     */
    public static function getUserDefinedConstants()
    {
        $all = get_defined_constants(TRUE);
        return isset($all['user']) ? $all['user'] : array();
    }

    /**
     *  Test if specified bit flag is set
     *
     *  @param int $target              target value to test
     *  @param int $flag                target flag to test
     *  @param int $mode                test mode(see BITTEST_MODE_XXX constants)
     *
     * @return boolean
     */
    public static function isBitSet( $target, $flag, $mode )
    {
        switch( ui($mode) ){
        case self::BITTEST_MODE_ALL:
            return ($target & $flag) === $flag;
            break;
        case self::BITTEST_MODE_ANY:
            return ($target & $flag) != 0;
        }

        return false;
    }

    /**
     *  Test if specified bit flag is set(any bit set returns true)
     *
     *  @param int $target              target value to test
     *  @param int $flag                target flag to test
     *
     * @return boolean
     */
    public static function isAnyBitSet( $target, $flag )
    {
        return self::isBitSet( $target, $flag, self::BITTEST_MODE_ANY );
    }

    /**
     *  Test if specified bit flag is set(all bits set returns true)
     *
     *  @param int $target              target value to test
     *  @param int $flag                target flag to test
     *
     * @return boolean
     */
    public static function isAllBitSet( $target, $flag )
    {
        return self::isBitSet( $target, $flag, self::BITTEST_MODE_ALL );
    }

    /*
     *  Convert PHP error number to string
     */
    public static function phpErrorString( $errno )
    {
//        Charcoal_ParamTrait::validateInteger( 1, $errno );

        $errors = array(
            E_ERROR                => "E_ERROR",
            E_WARNING              => "E_WARNING",
            E_PARSE                => "E_PARSE",
            E_NOTICE               => "E_NOTICE",
            E_CORE_ERROR           => "E_CORE_ERROR",
            E_CORE_WARNING         => "E_CORE_WARNING",
            E_COMPILE_ERROR        => "E_COMPILE_ERROR",
            E_COMPILE_WARNING      => "E_COMPILE_WARNING",
            E_USER_ERROR           => "E_USER_ERROR",
            E_USER_NOTICE          => "E_USER_NOTICE",
            E_STRICT               => "E_STRICT",
            E_RECOVERABLE_ERROR    => "E_RECOVERABLE_ERROR",
        );

        $errors[8192] = "E_DEPRECATED";        // PHP 5.3.0
        $errors[16384] = "E_USER_DEPRECATED";        // PHP 5.3.0

        $errors_desc = array();
        foreach( $errors as $key => $value ){
            if ( self::isAnyBitSet($errno,$key) ){
                $errors_desc[] = $value;
            }
        }

        return implode( "|", $errors_desc );
    }

    /*
     *    配列の最後に別の配列の要素すべてを追加
     */
    public static function appendArray( $a, $b )
    {
        if ( $a === NULL ){
            $a = array();
        }
        array_splice($a,count($a),0,$b);
        return $a;
    }

    /**
     *    swap two values
     *
     * @param mixed $a
     * @param mixed $b
     *
     * @return array
     */
    public static function swap( $a, $b )
    {
        return array( $b, $a );
    }

    /**
     *    make a string to snake case
     *
     * @param string $str
     *
     * @return string
     */
    public static function snakeCase( $str )
    {
        return strtolower(preg_replace('/([a-z0-9])([A-Z])/', "$1_$2", $str));
    }

    /**
     *    make a string to pascal case
     *
     * @param string $str
     *
     * @return string
     */
    public static function pascalCase( $str )
    {
        return implode(array_map('ucfirst',array_map('strtolower',explode( '_', $str ))));
    }

    /**
     *    format byte size
     *
     * @param int $size
     * @param int $precision
     * @param array $symbols
     *
     * @return string
     */
    public static function formatByteSize( $size, $precision = 1, $symbols = NULL )
    {
        if ( $symbols === NULL ){
            $symbols = array('B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
        }
        $i=0;
        while (($size/1024)>1) {
            $size=$size/1024;
            $i++;
        }
        return (round($size,$precision)." ".$symbols[$i]);
    }

    /**
     *  generate hash value
     *
     * @param string $algorithm
     *
     * @return string
     */
    public static function hash( $algorithm = 'sha1' )
    {
        switch( $algorithm )
        {
        case 'sha1':
            return sha1(microtime().uniqid(mt_rand(),1));
        case 'md5':
            return md5(microtime().uniqid(mt_rand(),1));
        }

        return NULL;
    }

    /**
     *  escape variable for HTML
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function escape( $value )
    {
        if ( is_string($value) ){
            $res = htmlspecialchars($value, ENT_QUOTES, mb_internal_encoding());
//log_debug( "debug", "escape:" . print_r($res,true) );
            return $res;
        }
        elseif ( is_array($value) ){
            $ret = array();
            foreach( $value as $key => $item ){
                $ret[$key] = self::escape( $item );
            }
            return $ret;
        }
        elseif ( is_object($value) ){
            $object = $value;
            $vars = get_object_vars($object);
            foreach( $vars as $key => $value ){
                $object->$key = self::escape( $value );
            }
            return $object;
        }
        return $value;
    }

    /**
     * decode escaped value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function decode( $value )
    {
        if ( is_string($value) ){
//log_debug( "debug", "decode before:" . print_r($value,true) );
            $res = htmlspecialchars_decode($value, ENT_QUOTES);
//log_debug( "debug", "decode after:" . print_r($res,true) );
            return $res;
        }
        elseif ( is_array($value) ){
            return array_map('Charcoal_System::decode', $value);
        }
        elseif ( is_object($value) ){
            $object = $value;
            $vars = get_object_vars($object);
            foreach( $vars as $key => $value ){
                $object->$key = self::decode( $value );
            }
            return $object;
        }
        return $value;
    }

    /**
     *  remove HTML tags
     *
     * @param mixed $value
     * @param string $allowable_tags
     *
     * @return mixed
     */
    public static function stripTags( $value, $allowable_tags = NULL )
    {
        if ( is_string($value) ){
            $res = strip_tags($value, $allowable_tags);
            return $res;
        }
        elseif ( is_array($value) ){
            $array = $value;
            foreach( $array as $key => $value ){
                $array[$key] = self::stripTags( $value, $allowable_tags );
            }
            return $array;
        }
        elseif ( is_object($value) ){
            $object = $value;
            $vars = get_object_vars($object);
            foreach( $vars as $key => $value ){
                $object->$key = self::stripTags( $value );
            }
            return $object;
        }
        return $value;
    }

    /**
     *  remove backslashes
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function stripSlashes( $value )
    {
        if ( is_string($value) ){
            return stripslashes($value);
        }
        elseif ( is_array($value) ){
            $array = $value;
            foreach( $array as $key => $value ){
                $array[$key] = self::stripSlashes( $value );
            }
            return $array;
        }
        elseif ( is_object($value) ){
            $object = $value;
            $vars = get_object_vars($object);
            foreach( $vars as $key => $value ){
                $object->$key = self::stripSlashes( $value );
            }
            return $object;
        }
        return $value;
    }

    /**
     *   escape string for HTML
     *
     * @param string $string_data
     * @param array $options
     *
     * @return mixed
     */
    public static function escapeString( $string_data, $options = NULL )
    {
        if ( !$options ){
            $options = array(
                            'quote_style' => 'ENT_QUOTES',
                        );
        }

        $quote_style = ENT_NOQUOTES;
        if ( isset($options['quote_style']) && $options['quote_style'] == 'ENT_QUOTES' ){
            $quote_style = ENT_QUOTES;
        }

        $str = htmlspecialchars( $string_data, $quote_style );

        return $str;
    }

    /**
     *  make random string
     *
     * @param int $length
     * @param string $char_set
     *
     * @return string
     */
    public static function makeRandomString( $length, $char_set = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_' )
    {
        $ret = '';
        $char_set_cnt = strlen($char_set);

        mt_srand();
        for($i = 0; $i < $length; $i++){
            $idx = mt_rand(0, $char_set_cnt - 1);
            $ret .= $char_set[ $idx ];
        }

        return $ret;
    }

    /**
     *  return file and line number of called position
     *
     * @param int $back
     *
     * @return array
     */
    public static function caller( $back = 0 )
    {
        $bt =  (version_compare(PHP_VERSION,'5.4.0') >= 0 ) ?
            debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,5) : debug_backtrace();

        $trace = isset($bt[1 + $back]) ? $bt[1 + $back] : NULL;

        $file = isset($trace['file']) ? $trace['file'] : "";
        $line = isset($trace['line']) ? $trace['line'] : "";

        return array( $file, $line );
    }

    /**
     *  return file and line number of called position as string
     *
     * @param int $back
     * @param boolean $fullpath
     *
     * @return string
     */
    public static function callerAsString( $back = 0, $fullpath = FALSE )
    {
        list( $file, $line ) = self::caller( $back );

        if ( $fullpath ){
            return "$file($line)";
        }

        $file = basename($file);

        return "$file($line)";
    }

    /**
     *    get type of primitive, resource, array, or object
     *
     * @param mixed $value
     *
     * @return string
     */
    public static function getType( $value )
    {
        $type = gettype($value);
        switch( $type ){
        case 'string':
            return $type . '(' . strlen($value) . ')';
            break;
        case 'integer':
        case 'float':
        case 'boolean':
            return $type . '(' . $value . ')';
            break;
        case 'NULL':
        case 'unknown type':
            return $type;
            break;
        case 'array':
            return $type . '(' . count($value) . ')';
            break;
        case 'object':
            if ( $value instanceof Countable ){
                return get_class( $value ) . '(' . count($value) . ')';
            }
            elseif ( $value instanceof Charcoal_Object ){
                return get_class( $value ) . '(hash=' . $value->hash() . ')';
            }
            return get_class( $value );
            break;
        }
        return '';
    }

    /**
     *  make string expression about a variable
     *
     * @param mixed $value
     * @param boolean $with_type
     * @param int $max_size
     * @param string $tostring_methods
     *
     * @return string
     */
    public static function toString( $value, $with_type = FALSE, $max_size = self::TOSTRING_MAX_LENGTH, $tostring_methods = '__toString,toString' )
    {
        $ret = '';

        if ( $value === NULL ){
            $ret = 'NULL';
        }
        else{
            $type = gettype($value);
            switch( $type ){
            case 'string':
            case 'integer':
            case 'double':
            case 'boolean':
            case 'NULL':
            case 'unknown type':
                $ret = strval($value);
                if ( $with_type ){
                    $ret .= '(' . $type . ')';
                }
                break;
            case 'array':
                $ret = '';
                foreach( $value as $k => $v ){
                    if ( strlen($ret) > 0 )        $ret .= '/';
                    $ret .= "$k=" . self::toString( $v );
                    if ( $with_type ){
                        $ret .= '(' . gettype($v) . ')';
                    }
                }
                break;
            case 'object':
                {
                    $methods = explode( ',', $tostring_methods );
                    foreach( $methods as $method ){
                        if ( method_exists($value, $method) ){
                            $ret = $value->{$method}();
                            break;
                        }
                    }
                    if ( $with_type ){
                        $ret .= '(' . get_class($value) . ')';
                    }
                }
                break;
            }
        }

        if ( $max_size > 0 ){
            return strlen($ret) > $max_size ? substr($ret,0,$max_size) . '...' : $ret;
        }
        else{
            return $ret;
        }
    }

    /**
     *    implodeのラッパー（5.1.6でクラスの__toStringが自動で呼ばれないため
     *
     * @param string $glue
     * @param array $pieces
     * @param boolean $with_type
     * @param int $max_size
     * @param string $tostring_methods
     *
     * @return string
     */
    public static function implodeArray( $glue, $pieces, $with_type = FALSE, $max_size = self::TOSTRING_MAX_LENGTH, $tostring_methods = '__toString,toString' )
    {
        $ret = '';

        if ( $pieces && is_array($pieces) )
        {
            foreach( $pieces as $p ){
                if ( strlen($ret) > 0 ){
                    $ret .= $glue;
                }
                $value = self::toString($p, $with_type, $max_size, $tostring_methods);
                $ret .= $value;
            }
        }

        return $ret;
    }

    /**
     *    implodeのラッパー（5.1.6でクラスの__toStringが自動で呼ばれないため
     *
     * @param string $glue
     * @param array $pieces
     * @param boolean $with_type
     * @param int $max_size
     * @param string $tostring_methods
     *
     * @return string
     */
    public static function implodeAssoc( $glue, array $pieces, $with_type = FALSE, $max_size = self::TOSTRING_MAX_LENGTH, $tostring_methods = '__toString,toString' )
    {
        $ret = '';

        foreach( $pieces as $key => $value ){
            if ( strlen($ret) > 0 ){
                $ret .= $glue;
            }
            $ret .= self::toString($key, $with_type, $max_size, $tostring_methods) . '=' . self::toString($value, $with_type, $max_size, $tostring_methods);
        }

        return $ret;
    }

    /**
     *  dump a variable
     *
     * @param mixed $var
     * @param int $back
     * @param array $options
     * @param boolean $return
     * @param int $max_depth
     *
     * @return string
     */
    public static function dump( $var, $back = 0, $options = NULL, $return = FALSE, $max_depth = 6 )
    {
        list( $file, $line ) = self::caller( $back );

        if ( !$options ){
            $options = array();
        }
        $default_options = array(
                'output' => defined('CHARCOAL_DEBUG_OUTPUT') ? CHARCOAL_DEBUG_OUTPUT : 'shell',
                'title' => 'system dump',
                'font_size' => 11,
                'max_string_length' => self::DUMP_MAX_LENGTH,
                'type' => 'textarea',
            );
        $options = array_merge( $default_options, $options );

        $output            = $options['output'];
        $title             = $options['title'];
        $font_size         = $options['font_size'];
        $max_string_length = $options['max_string_length'];
        $type              = $options['type'];

        $lines = array();
        $recursion = array();
        self::_dump( '-', $var, 0, $max_string_length, $lines, $max_depth, $recursion );

        switch( $output )
        {
        case "html":
            switch( $type ){
            case 'div':
                $output  = "<div style=\"font-size:12px; margin: 2px\"> $title:" . implode('',$lines) . " @$file($line)</div>";
                break;
            case 'textarea':
            default:
                $output  = "<h3 style=\"font-size:12px; margin: 0px; color:black; background-color:white; text-align: left\"> $title @$file($line)</h3>";
                $output .= "<textarea rows=14 style=\"width:100%; font-size:{$font_size}px; margin: 0px; color:black; background-color:white; border: 1px solid silver;\">";
                $output .= implode(PHP_EOL,$lines);
                $output .= "</textarea>";
                break;
            }
            break;
        case "shell":
        default:
            $output  = "$title @$file($line)" . PHP_EOL;
            $output .= implode(PHP_EOL,$lines) . PHP_EOL;
            break;
        }

        if ( $return ){
            return $output;
        }
        else{
            echo $output;
            return null;
        }
    }

    private static function _dump( $key, $value, $depth, $max_string_length, &$lines, $max_depth, &$recursion )
    {
        if ( $depth > $max_depth ){
            $lines[] = str_repeat( '.', $depth * 4 ) . "----(max depth over:$max_depth)";
            return;
        }

        $type = gettype($value);

        switch( $type ){
        case 'string':
            {
                $str = $value;
                if ( strlen($str) > $max_string_length ){
                    $str = substr( $str, 0, $max_string_length ) . '...(total:' . strlen($str) . 'bytes)';
                }
//                $str = htmlspecialchars( $str, ENT_QUOTES );
                $lines[] = str_repeat( '.', $depth * 4 ) . "[$key:$type]$str";
            }
            break;
        case 'integer':
        case 'double':
        case 'boolean':
        case 'NULL':
        case 'unknown type':
            {
                $str = strval($value);
                if ( strlen($str) > $max_string_length ){
                    $str = substr( $str, 0, $max_string_length ) . '...(total:' . strlen($str) . 'bytes)';
                }
//                $str = htmlspecialchars( $str, ENT_QUOTES );
                $lines[] = str_repeat( '.', $depth * 4 ) . "[$key:$type]$str";
            }
            break;
        case 'array':
            {
                $lines[] = str_repeat( '.', $depth * 4 ) . "[$key:array(" . count($value) . ')]';
                foreach( $value as $_key => $_value ){
                    self::_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth, $recursion );
                }
            }
            break;
        case 'object':
            {
                $clazz = get_class( $value );
                $id = function_exists('spl_object_hash') ? spl_object_hash($value) : 'unknown';
                $line = str_repeat( '.', $depth * 4 ) . "[$key:object($clazz)@$id]";

                $hash = spl_object_hash( $value );
                if ( isset($recursion[$hash]) ){
                    $lines[] = $line . "----[RECURSION]";
                    return;
                }
                $recursion[$hash] = 1;

                $lines[] = $line;

                if ( $value instanceof Traversable ){
                    foreach( $value as $_key => $_value ){
                        self::_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth, $recursion );
                    }
                }
                else{
                    $vars = self::getObjectVars( $value );
                    foreach( $vars as $_key => $_value ){
                        self::_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth, $recursion );
                    }
                }
            }
            break;
        }
    }

    /**
     *  dump a tree structured variable
     *
     * @param mixed $var
     * @param int $back
     * @param array $options
     * @param boolean $return
     * @param int $max_depth
     *
     * @return string
     */
    public static function tree_dump( $var, $back = 1, $options = NULL, $return = FALSE, $max_depth = 6 )
    {
        list( $file, $line ) = self::caller( $back );

        if ( !$options ){
            $options = array();
        }
        $default_options = array(
                'title' => 'system tree dump',
                'font_size' => 11,
                'max_string_length' => self::DUMP_MAX_LENGTH,
                'type' => 'textarea',
            );
        $options = array_merge( $default_options, $options );

        $title             = $options['title'];
        $font_size         = $options['font_size'];
        $max_string_length = $options['max_string_length'];
        $type              = $options['type'];

        $lines = array();
        self::_tree_dump( '-', $var, 0, $max_string_length, $lines, $max_depth );

        $output = '';
        switch( CHARCOAL_RUNMODE )
        {
        case "shell":
            $output  = "$title @$file($line)" . PHP_EOL;
            $output .= implode(PHP_EOL,$lines) . PHP_EOL;
            break;
        case "http":
            switch( $type ){
            case 'textarea':
                $output  = "<h3 style=\"font-size:12px; margin: 2px\"> $title @$file($line)</h3>";
                $output .= "<textarea rows=14 style=\"width:100%; font-size:{$font_size}px; margin: 2px\">" . implode(PHP_EOL,$lines) . "</textarea>";
                break;
            case 'div':
                $output  = "<div style=\"font-size:12px; margin: 2px\"> $title:" . implode('',$lines) . " @$file($line)</div>";
                break;
            }
        }

        if ( $return ){
            return $output;
        }
        else{
            echo $output;
            return null;
        }
    }

    private static function _tree_dump( $key, $value, $depth, $max_string_length, &$lines, $max_depth )
    {
        if ( $depth > $max_depth ){
            return;
        }

        $type = gettype($value);

        switch( $type ){
        case 'string':
        case 'integer':
        case 'double':
        case 'boolean':
        case 'NULL':
        case 'unknown type':
            {
                $lines[] = str_repeat( '-', $depth * 4 ) . "[$key:$type]$value";
            }
            break;
        case 'array':
            {
                $lines[] = str_repeat( '-', $depth * 4 ) . "[$key:array(" . count($value) . ')]';
                foreach( $value as $k => $v ){
                    $type = gettype($v);
                    if ( $type == 'array' || $type == 'object' ){
                        self::_tree_dump( $k, $v, $depth + 1, $max_string_length, $lines, $max_depth );
                    }
                }
            }
            break;
        case 'object':
            {
                $clazz = get_class( $value );
                $id = function_exists('spl_object_hash') ? spl_object_hash($value) : 'unknown';
                $lines[] = str_repeat( '-', $depth * 4 ) . "[$key:object($clazz)@$id]";

                if ( $value instanceof Traversable ){
                    foreach( $value as $_key => $_value ){
                        $_type = gettype($_value);
                        if ( $_type == 'array' || $_type == 'object' ){
                            self::_tree_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth );
                        }
                    }
                }
                else{
                    $vars = self::getObjectVars( $value );
                    foreach( $vars as $_key => $_value ){
                        $_type = gettype($_value);
                        if ( $_type == 'array' || $_type == 'object' ){
                            self::_tree_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth );
                        }
                    }
                }
            }
            break;
        }
    }

    /**
     *    get object's property using reflection
     *
     * @param mixed $obj
     * @param string $field
     *
     * @return mixed
     */
    public static function getObjectVar( $obj, $field )
    {
        $ref = new ReflectionObject( $obj );
        $p = $ref->getProperty( $field );
        if ( version_compare(PHP_VERSION, '5.3.0') >= 0 ){
            $p->setAccessible( true );            // ReflectionProperty#setAccessible is implemented PHP 5.3.0 or later
        }
        $value = $p->getValue( $obj );
        return $value;
    }

    /**
     *    improved version of get_object_vars
     *
     * @param mixed $obj
     *
     * @return array
     */
    public static function getObjectVars( $obj )
    {
        $ref = new ReflectionObject( $obj );
        $vars = self::_getObjectVars( $obj, $ref );
        return $vars;
    }

    /**
     *    recursive function for getObjectVars
     *
     * @param mixed $obj
     * @param ReflectionClass $class_obj
     *
     * @return array
     */
    public static function _getObjectVars( $obj, ReflectionClass $class_obj )
    {
        $vars = array();

        $filter = ( version_compare(PHP_VERSION, '5.3.0') >= 0 ) ?
                ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE :
                ReflectionProperty::IS_PUBLIC;

        $props = $class_obj->getProperties($filter);
        foreach( $props as $p ){
            if ( version_compare(PHP_VERSION, '5.3.0') >= 0 ){
                $p->setAccessible(true);            // ReflectionProperty#setAccessible is implemented PHP 5.3.0 or later
            }
            $key = $p->getName();
            $value = $p->getValue($obj);

            if ( !$p->isStatic() ){
                $vars[$key] = $value;
            }
        }

        $parent = $class_obj->getParentClass();
        if ( $parent !== FALSE ){
            $vars_parent = self::_getObjectVars( $obj, $parent );
            $vars = array_merge( $vars_parent, $vars );
        }

        return $vars;
    }

    /**
     *  convert encoding
     *
     * @param string $str
     * @param string $to_encoding
     * @param string $from_encoding
     *
     * @return string
     */
    public static function convertEncoding( $str, $to_encoding = NULL, $from_encoding = NULL )
    {
        if ( is_string($str) && $to_encoding ){
            // エンコードあり
            return mb_convert_encoding($str,$to_encoding, $from_encoding);
        }
        // エンコード無し
        return $str;
    }

    /**
     *  convert encoding recursively
     *
     * @param mixed $var
     * @param string $to_encoding
     * @param string $from_encoding
     *
     * @return mixed
     */
    public static function convertEncodingRecursive( $var, $to_encoding = NULL, $from_encoding = NULL )
    {
        $type = gettype($var);
        switch( $type ){
        case 'string':
            {
                return mb_convert_encoding($var,$to_encoding, $from_encoding);
            }
            break;
        case 'integer':
        case 'double':
        case 'boolean':
        case 'NULL':
        case 'unknown type':
            break;
        case 'array':
            {
                $newArray = array();
                foreach( $var as $key => $value ){
                    $value = self::convertEncodingRecursive( $value, $to_encoding, $from_encoding );
                    $newArray[ $key ] = $value;
                }
                return $newArray;
            }
            break;
        case 'object':
            {
                $newObject = clone $var;
                if ( $var instanceof Traversable ){
                    foreach( $var as $key => $value ){
                        $value = self::convertEncodingRecursive( $value, $to_encoding, $from_encoding );
                        $newObject->$key = $value;
                    }
                    return $newObject;
                }
                else{
                    $obj_vars = get_object_vars( $var );
                    foreach( $obj_vars as $key => $value ){
                        $value = self::convertEncodingRecursive( $value, $to_encoding, $from_encoding );
                        $newObject->$key = $value;
                    }
                    return $newObject;
                }
            }
            break;
        }

        return $var;
    }

}


