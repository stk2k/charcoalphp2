<?php

//==================================================================
// 定数

define( 'PROC_KEYWORD', 'proc' );
define( 'CHARCOAPHP_VERSION_MAJOR', 2 );
define( 'CHARCOAPHP_VERSION_MINOR', 22 );
define( 'CHARCOAPHP_VERSION_REVISION', 4 );
define( 'CHARCOAPHP_VERSION_BUILD', 161 );
define( 'CHARCOAL_CLASS_PREFIX', 'Charcoal_' );
define( 'CHARCOAL_CLASS_FILE_SUFFIX', '.class.php' );
 
//==================================================================
// 初期化処理

// タイムゾーン
date_default_timezone_set( CHARCOAL_DEFAULT_TIMEZONE );

// ユーザによる中断を無視する
//ignore_user_abort( TRUE );

// magic_quotes_runtimeをOFFにする
if ( version_compare(PHP_VERSION, '5.3.0') < 0 ){
	set_magic_quotes_runtime( false );
}

// バージョンの差異を吸収
if ( !defined('E_RECOVERABLE_ERROR') ){
	define( 'E_RECOVERABLE_ERROR', 4096 );
}

//==================================================================
// OSによってパス区切り文字を判定

if (!defined('PATH_SEPARATOR')) {
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		define('PATH_SEPARATOR', ';');
	} else {
		define('PATH_SEPARATOR', ':');
	}
}

//==================================================================
// インクルードパス設定
function add_include_path( $path )
{
	if ( !file_exists($path) ){
		print "[warning]$path is not exists<br>";
	}
	if ( !is_dir($path) ){
		print "[warning]$path is not DIR!<br>";
	}
	ini_set(
			'include_path', 
			ini_get('include_path') . PATH_SEPARATOR . $path
		);
}

//==================================================================
// EOL
function eol()
{
	return ( CHARCOAL_RUNMODE == 'http' ) ? '<br />' : PHP_EOL;
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

function s( $value )
{
	if ( $value instanceof Charcoal_String ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_String( $value ) : NULL;
}

/**
 *	intをIntegerオブジェクトに変換
 **/
function i( $value )
{
	if ( $value instanceof Charcoal_Integer ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Integer( $value ) : NULL;
}

/**
 *	floatをFloatオブジェクトに変換
 **/
function f( $value )
{
	if ( $value instanceof Charcoal_Float ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Float( $value ) : NULL;
}

/**
 *	boolをBooleanオブジェクトに変換
 **/
function b( $value )
{
	if ( $value instanceof Charcoal_Boolean ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Boolean( $value ) : NULL;
}

/**
 *	日付をDateオブジェクトに変換
 **/
function d( $value )
{
	if ( $value instanceof Charcoal_Date ){
		return $value;
	}
	return $value !== NULL ? Charcoal_Date::parse( $value ) : NULL;
}

/**
 *	日付をDateWithTimeオブジェクトに変換
 **/
function dt( $value )
{
	if ( $value instanceof Charcoal_DateTime ){
		return $value;
	}
	return $value !== NULL ? Charcoal_DateWithTime::parse( $value ) : NULL;
}

/**
 *	arrayをVectorオブジェクトに変換
 **/
function v( $value )
{
	if ( $value instanceof Charcoal_Vector ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Vector( $value ) : NULL;
}

/**
 *	arrayをListオブジェクトに変換
 **/
function l( $value )
{
	if ( $value instanceof Charcoal_List ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_List( $value ) : NULL;
}

/**
 *	arrayをHashMapオブジェクトに変換
 **/
function m( $value )
{
	if ( $value instanceof Charcoal_HashMap ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_HashMap( $value ) : NULL;
}

/**
 *	arrayをPropertiesオブジェクトに変換
 **/
function p( $value )
{
	if ( $value instanceof Charcoal_Properties ){
		return $value;
	}
	return $value !== NULL ? new Charcoal_Properties( $value ) : NULL;
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
// 例外をスロー

function _throw( Exception $e, Charcoal_Integer $back = null )
{
	list( $file, $line ) = Charcoal_System::caller($back ? ui($back) : 0);
	$clazz = get_class($e);
	$id = ($e instanceof Charcoal_Object) ? $e->hashCode() : spl_object_hash($e);
	$message = $e->getMessage();

	try{
		log_debug( "system,error,debug", "_throw $clazz ($id) $message threw from $file($line)", "exception" );
	}
	catch( Exception $ex ){
		echo "exeption while wrting log:" . $e->getMessage() . eol();
		exit;
	}

	throw $e;
}

//==================================================================
// 例外をキャッチ
function _catch( Exception $e )
{
	list( $file, $line ) = Charcoal_System::caller();
	$clazz = get_class($e);
	$id = ($e instanceof Charcoal_Object) ? $e->hashCode() : spl_object_hash($e);
	$message = $e->getMessage();

	try{
		log_debug( "system,error,debug", "_catch $clazz ($id) $message catched at $file($line)", "exception" );
	}
	catch( Exception $ex ){
		echo "exeption while wrting log:" . $e->getMessage() . eol();
		exit;
	}
}

//==================================================================
// bootstrap
require_once( CHARCOAL_HOME . '/src/class/bootstrap/Bootstrap' . CHARCOAL_CLASS_FILE_SUFFIX );

