<?php

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
// space
function space( $multiplier = 1 )
{
	return str_repeat( CHARCOAL_DEBUG_OUTPUT == 'html' ? '&nbsp;' : ' ', $multiplier );
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
// プリミティブオブジェクト生成関数

/**
 *	stringをStringオブジェクトに変換
 **/

function s( $value, $encoding = NULL )
{
	if ( $value instanceof Charcoal_String ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_String( $value, $encoding ) : Charcoal_String::defaultValue( $encoding );
}

/**
 *	intをIntegerオブジェクトに変換
 **/
function i( $value )
{
	if ( $value instanceof Charcoal_Integer ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Integer( $value ) : Charcoal_Integer::defaultValue();
}

/**
 *	floatをFloatオブジェクトに変換
 **/
function f( $value )
{
	if ( $value instanceof Charcoal_Float ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Float( $value ) : Charcoal_Float::defaultValue();
}

/**
 *	boolをBooleanオブジェクトに変換
 **/
function b( $value )
{
	if ( $value instanceof Charcoal_Boolean ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Boolean( $value ) : Charcoal_Boolean::defaultValue();
}

/**
 *	arrayをVectorオブジェクトに変換
 **/
function v( $value )
{
	if ( $value instanceof Charcoal_Vector ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Vector( $value ) : Charcoal_Vector::defaultValue();
}

/**
 *	arrayをListオブジェクトに変換
 **/
function l( $value )
{
	if ( $value instanceof Charcoal_List ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_List( $value ) : Charcoal_List::defaultValue();
}

/**
 *	arrayをHashMapオブジェクトに変換
 **/
function m( $value )
{
	if ( $value instanceof Charcoal_HashMap ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_HashMap( $value ) : Charcoal_HashMap::defaultValue();
}

/**
 *	arrayをPropertiesオブジェクトに変換
 **/
function p( $value )
{
	if ( $value instanceof Charcoal_Properties ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Properties( $value ) : Charcoal_Properties::defaultValue();
}

//==================================================================
// プリミティブ変換関数

/**
 *	Stringオブジェクトをstringに変換
 **/

function us( $value )
{
	return ( $value instanceof Charcoal_String ) ? $value->getValue() : $value;
}

/**
 *	Integerオブジェクトをintに変換
 **/
function ui( $value )
{
	return ( $value instanceof Charcoal_Integer ) ? $value->getValue() : $value;
}

/**
 *	Floatオブジェクトをfloatに変換
 **/
function uf( $value )
{
	return ( $value instanceof Charcoal_Float ) ? $value->getValue() : $value;
}

/**
 *	Booleanオブジェクトをboolに変換
 **/
function ub( $value )
{
	return ( $value instanceof Charcoal_Boolean ) ? $value->getValue() : $value;
}

/**
 *	Vectorオブジェクトをarrayに変換
 **/
function uv( $value )
{
	return ( $value instanceof Charcoal_Vector ) ? $value->toArray() : $value;
}

/**
 *	Listオブジェクトをarrayに変換
 **/
function ul( $value )
{
	return ( $value instanceof Charcoal_List ) ? $value->toArray() : $value;
}

/**
 *	HashMapオブジェクトをarrayに変換
 **/
function um( $value )
{
	return ( $value instanceof Charcoal_HashMap ) ? $value->toArray() : $value;
}

/**
 *	Propertiesオブジェクトをarrayに変換
 **/
function up( $value )
{
	return ( $value instanceof Charcoal_Properties ) ? $value->toArray() : $value;
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
		return;
	}

	list( $file, $line ) = Charcoal_System::caller();
	$clazz = get_class($e);
	$id = ($e instanceof Charcoal_Object) ? $e->hashCode() : spl_object_hash($e);
	$message = $e->getMessage();

	try{
		log_debug( "system,debug", "_throw $clazz ($id) $message threw from $file($line)", "exception" );
		if ( $log_error ){
			log_debug( "error", "_throw $clazz ($id) $message threw from $file($line)", "exception" );
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
function _catch( $e, $log_error = TRUE )
{
	if ( $e instanceof Charcoal_BusinessException || !($e instanceof Exception) ){
		return;
	}

	list( $file, $line ) = Charcoal_System::caller();
	$clazz = get_class($e);
	$id = ($e instanceof Charcoal_Object) ? $e->hashCode() : spl_object_hash($e);
	$message = $e->getMessage();

	try{
		log_debug( "system,debug", "_catch $clazz ($id) $message catched at $file($line)", "exception" );
		if ( $log_error ){
			log_error( "error", "_catch $clazz ($id) $message catched at $file($line)", "exception" );
		}
	}
	catch( Exception $ex ){
		echo "exeption while wrting log:" . $e->getMessage() . eol();
		exit;
	}
}

//==================================================================
// bootstrap
require_once( CHARCOAL_HOME . '/src/class/bootstrap/Bootstrap.class.php' );

