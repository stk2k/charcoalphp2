<?php

require dirname(__FILE__) . '/vendor/autoload.php';

//==================================================================
// 初期化処理

// 定数定義
if ( !defined('CHARCOAL_CACHE_DIR') ){
    define( 'CHARCOAL_CACHE_DIR', CHARCOAL_HOME . '/cache' );
}
if ( !defined('CHARCOAL_TMP_DIR') ){
    define( 'CHARCOAL_TMP_DIR', CHARCOAL_HOME . '/tmp' );
}
if ( !defined('CHARCOAL_DEBUG_OUTPUT') ){
    define( 'CHARCOAL_DEBUG_OUTPUT', 'html' );
}

// タイムゾーン
date_default_timezone_set( CHARCOAL_DEFAULT_TIMEZONE );

// ユーザによる中断を無視する
//ignore_user_abort( TRUE );

if ( version_compare(PHP_VERSION, '5.3.0') < 0 ){
    // magic_quotes_runtimeをOFFにする
    set_magic_quotes_runtime( false );

    // E_DEPRECATEDを定義
    define( 'E_DEPRECATED', 8192 );
}

//==================================================================
// EOL
function eol()
{
    return CHARCOAL_DEBUG_OUTPUT == 'html' ? '<br />' : PHP_EOL;
}

//==================================================================
// ログ出力関数

// TRACE出力
function log_trace( $logger_names, $message, $tag = NULL )
{
    Charcoal_Framework::writeLog( "T:$logger_names", $message, $tag );
}

// INFO出力
function log_info( $logger_names, $message, $tag = NULL )
{
    Charcoal_Framework::writeLog( "I:$logger_names", $message, $tag );
}

// WARNING出力
function log_warning( $logger_names, $message, $tag = NULL )
{
    Charcoal_Framework::writeLog( "W:$logger_names", $message, $tag );
}

// DEBUG出力
function log_debug( $logger_names, $message, $tag = NULL )
{
    Charcoal_Framework::writeLog( "D:$logger_names", $message, $tag );
}

// ERROR出力
function log_error( $logger_names, $message, $tag = NULL )
{
    Charcoal_Framework::writeLog( "E:$logger_names", $message, $tag );
}

// FATAL出力
function log_fatal( $logger_names, $message, $tag = NULL )
{
    Charcoal_Framework::writeLog( "F:$logger_names", $message, $tag );
}

//==================================================================
// 配列ダンプ

function array_dump( $array, $options = NULL, $return = FALSE, $max_depth = 30 )
{
    return Charcoal_System::dump( $array, 1, $options, $return, $max_depth );
}

function ad( $array, $options = NULL, $return = FALSE, $max_depth = 30 )
{
    return Charcoal_System::dump( $array, 1, $options, $return, $max_depth );
}

//==================================================================
// Box functions

/**
 *  box value to Charcoal_String object
 *
 * @param mixed $value
 * @param string $encoding
 *
 * @return Charcoal_String
 */
function s( $value, $encoding = NULL )
{
    if ( $value instanceof Charcoal_String ){
        return $value;
    }
    return $value !== NULL ? new Charcoal_String( $value, $encoding ) : Charcoal_String::defaultValue( $encoding );
}

/**
 *  box value to Charcoal_Integer object
 *
 * @param mixed $value
 *
 * @return Charcoal_Integer
 */
function i( $value )
{
    if ( $value instanceof Charcoal_Integer ){
        return $value;
    }
    return $value !== NULL ? new Charcoal_Integer( $value ) : Charcoal_Integer::defaultValue();
}

/**
 *  box value to Charcoal_Float object
 *
 * @param mixed $value
 *
 * @return Charcoal_Float
 */
function f( $value )
{
    if ( $value instanceof Charcoal_Float ){
        return $value;
    }
    return $value !== NULL ? new Charcoal_Float( $value ) : Charcoal_Float::defaultValue();
}

/**
 *  box value to Charcoal_Boolean object
 *
 * @param mixed $value
 *
 * @return Charcoal_Boolean
 */
function b( $value )
{
    if ( $value instanceof Charcoal_Boolean ){
        return $value;
    }
    return $value !== NULL ? new Charcoal_Boolean( $value ) : Charcoal_Boolean::defaultValue();
}

/**
 *  box value to Charcoal_Vector object
 *
 * @param mixed $value
 *
 * @return Charcoal_Vector
 */
function v( $value )
{
    if ( $value instanceof Charcoal_Vector ){
        return $value;
    }
    return $value !== NULL ? new Charcoal_Vector( $value ) : Charcoal_Vector::defaultValue();
}

/**
 *  box value to Charcoal_List object
 *
 * @param mixed $value
 *
 * @return Charcoal_List
 */
function l( $value )
{
    if ( $value instanceof Charcoal_List ){
        return $value;
    }
    return $value !== NULL ? new Charcoal_List( $value ) : Charcoal_List::defaultValue();
}

/**
 *  box value to Charcoal_HashMap object
 *
 * @param mixed $value
 *
 * @return Charcoal_HashMap
 */
function m( $value )
{
    if ( $value instanceof Charcoal_HashMap ){
        return $value;
    }
    return $value !== NULL ? new Charcoal_HashMap( $value ) : Charcoal_HashMap::defaultValue();
}

//==================================================================
// Unbox functions

/**
 *  Unbox value from Charcoal_String
 *
 * @param Charcoal_String|mixed $value
 *
 * @return mixed
 */
function us( $value )
{
    return ( $value instanceof Charcoal_String ) ? $value->getValue() : $value;
}

/**
 *  Unbox value from Charcoal_Integer
 *
 * @param Charcoal_Integer|mixed $value
 *
 * @return mixed
 */
function ui( $value )
{
    return ( $value instanceof Charcoal_Integer ) ? $value->getValue() : $value;
}

/**
 *  Unbox value from Charcoal_Float
 *
 * @param Charcoal_Float|mixed $value
 *
 * @return mixed
 */
function uf( $value )
{
    return ( $value instanceof Charcoal_Float ) ? $value->getValue() : $value;
}

/**
 *  Unbox value from Charcoal_Boolean
 *
 * @param Charcoal_Boolean|mixed $value
 *
 * @return mixed
 */
function ub( $value )
{
    return ( $value instanceof Charcoal_Boolean ) ? $value->getValue() : $value;
}

/**
 *  Unbox value from Charcoal_Vector
 *
 * @param Charcoal_Vector|mixed $value
 *
 * @return mixed
 */
function uv( $value )
{
    return ( $value instanceof Charcoal_Vector ) ? $value->toArray() : $value;
}

/**
 *  Unbox value from Charcoal_List
 *
 * @param Charcoal_List|mixed $value
 *
 * @return mixed
 */
function ul( $value )
{
    return ( $value instanceof Charcoal_List ) ? $value->toArray() : $value;
}

/**
 *  Unbox value from Charcoal_HashMap
 *
 * @param Charcoal_HashMap|mixed $value
 *
 * @return mixed
 */
function um( $value )
{
    return ( $value instanceof Charcoal_HashMap ) ? $value->toArray() : $value;
}

//==================================================================
// object hash function
if ( !function_exists('spl_object_hash') ){
    function spl_object_hash($o){
        return sha1("$o");
    }
}

//==================================================================
// 例外をスロー

function _throw( $e, $log_error = TRUE )
{
    if ( $e instanceof Charcoal_BusinessException || !($e instanceof Exception) ){
        throw $e;
    }

    list( $file, $line ) = Charcoal_System::caller();
    $clazz = get_class($e);
    $id = ($e instanceof Charcoal_Object) ? $e->hash() : spl_object_hash($e);
    $message = $e->getMessage();

    try{
        log_debug( "system,debug", "_throw $clazz ($id) $message threw from $file($line)", "exception" );
        if ( $log_error ){
            log_error( "error", "_throw $clazz ($id) $message threw from $file($line)", "exception" );
        }
    }
    catch( Exception $ex ){
        echo "exeption while wrting log:" . $e->getMessage() . eol();
        exit;
    }

    throw $e;
}

//==================================================================
// 例外をキャッチ
function _catch( $e, $log_backtrace = FALSE )
{
    if ( !($e instanceof Exception) ){
        return;
    }

    list( $file, $line ) = Charcoal_System::caller();
    $clazz = get_class($e);
    $id = ($e instanceof Charcoal_Object) ? $e->hash() : spl_object_hash($e);
    $message = $e->getMessage();

    try{
        log_error( "system, debug, error", "_catch $clazz ($id) $message catched at $file($line)", "exception" );
        if ( $log_backtrace ){
            log_error( "error", "backtrace:" . print_simple_exception_trace($e->getTrace(),true), "exception" );
        }
    }
    catch( Exception $ex ){
        echo "exeption while wrting log:" . $e->getMessage() . eol();
        exit;
    }
}

/**
 * @param $trace
 * @param bool $return
 * @return string|NULL
 */
function print_simple_exception_trace($trace, $return = false)
{
    $ret = '';
    foreach($trace as $depth => $item){
        $file = $item['file'];
        $line = $item['line'];
        $function = $item['function'];
        $args = $item['args'];
        $clazz = isset($item['class']) ? $item['class'] : '';
        $type = isset($item['type']) ? $item['type'] : '';

        $args_list = array();
        foreach($args as $arg){
            switch(gettype($arg)){
                case 'array':
                    $args_list[] = print_r($arg,true);
                    break;
                case 'object':
                    $args_list[] = get_class($arg) . ' Object ()';
                    break;
                case 'resource':
                    $args_list[] = ' Resouce id #' . (int)$arg;
                    break;
                default:
                    $args_list[] = " $arg";
                    break;
            }

        }
        $args_list = implode(',',$args_list);

        $function_or_method = empty($type) ? $function : $clazz . $type . $function;

        $ret = '#'. $depth. ' '. $function_or_method. '(' . $args_list. ') called at ['. $file. ':'. $line. ']' . PHP_EOL;
    }
    
    if ( $return ){
        return $ret;
    }
    echo $ret, PHP_EOL;
    return $ret;
}

//==================================================================
// bootstrap
require_once( CHARCOAL_HOME . '/src/class/bootstrap/Bootstrap.class.php' );

