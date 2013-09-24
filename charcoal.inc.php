<?php

//==================================================================
// 定数

define( 'PROC_KEYWORD', 'proc' );
define( 'CHARCOAPHP_VERSION_MAJOR', 2 );
define( 'CHARCOAPHP_VERSION_MINOR', 20 );
define( 'CHARCOAPHP_VERSION_REVISION', 5 );
define( 'CHARCOAPHP_VERSION_BUILD', 152 );
define( 'CHARCOAL_CLASS_PREFIX', 'Charcoal_' );
define( 'CHARCOAL_CLASS_FILE_SUFFIX', '.class.php' );
 
//==================================================================
// 初期化処理

// タイムゾーン
date_default_timezone_set( CHARCOAL_DEFAULT_TIMEZONE );

// ユーザによる中断を無視する
//ignore_user_abort( TRUE );

// 内部例外トレース
define( 'ENABLE_INTERNAL_EXCEPTION_TRACE', true );

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

/*
ini_set( 'include_path', CHARCOAL_HOME . '/classes' );
add_include_path( WEBAPP_DIR . '/' . APPLICATION . '/classes' );
*/

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
	return new Charcoal_String($value);
}

/**
 *	intをIntegerオブジェクトに変換
 **/
function i( $value, $default_value = 0 )
{
	if ( $value instanceof Charcoal_Integer ){
		return $value;
	}
	return new Charcoal_Integer( $value, $default_value );
}

/**
 *	floatをFloatオブジェクトに変換
 **/
function f( $value, $default_value = 0 )
{
	if ( $value instanceof Charcoal_Float ){
		return $value;
	}
	return new Charcoal_Float( $value, $default_value );
}

/**
 *	boolをBooleanオブジェクトに変換
 **/
function b( $value, $default_value = FALSE )
{
	if ( $value instanceof Charcoal_Boolean ){
		return $value;
	}
	return new Charcoal_Boolean( $value, $default_value );
}

/**
 *	日付をDateオブジェクトに変換
 **/
function d( $value )
{
	if ( $value instanceof Charcoal_Date ){
		return $value;
	}
	return Charcoal_Date::parse( s($value) );
}

/**
 *	日付をDateWithTimeオブジェクトに変換
 **/
function dt( $value )
{
	if ( $value instanceof Charcoal_DateTime ){
		return $value;
	}
	return Charcoal_DateWithTime::parse( s($value) );
}

/**
 *	arrayをVectorオブジェクトに変換
 **/
function v( $value )
{
	if ( $value instanceof Charcoal_Vector ){
		return $value;
	}
	return new Charcoal_Vector( $value );
}

/**
 *	arrayをListオブジェクトに変換
 **/
function l( $value )
{
	if ( $value instanceof Charcoal_List ){
		return $value;
	}
	return new Charcoal_List( $value );
}

/**
 *	arrayをHashMapオブジェクトに変換
 **/
function m( $value )
{
	if ( $value instanceof Charcoal_HashMap ){
		return $value;
	}
	return new Charcoal_HashMap( $value );
}

/**
 *	arrayをPropertiesオブジェクトに変換
 **/
function p( $value )
{
	if ( $value instanceof Charcoal_Properties ){
		return $value;
	}
	return new Charcoal_Properties( $value );
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
	if ( ENABLE_INTERNAL_EXCEPTION_TRACE ){
		list( $file, $line ) = Charcoal_System::caller($back ? ui($back) : 0);
		$clazz = get_class($e);
		$id = ($e instanceof Charcoal_Object) ? $e->hashCode() : spl_object_hash($e);
		$message = $e->getMessage();

		try{
			log_debug( "system,error,debug", "_throw $clazz ($id) $message @$file($line)", "exception" );
		}
		catch( Exception $ex ){
			echo "exeption while wrting log:" . $e->getMessage() . eol();
			exit;
		}
	}

	throw $e;
}

//==================================================================
// 例外をキャッチ
function _catch( Exception $e )
{
	if ( ENABLE_INTERNAL_EXCEPTION_TRACE ){
		list( $file, $line ) = Charcoal_System::caller();
		$clazz = get_class($e);
		$id = ($e instanceof Charcoal_Object) ? $e->hashCode() : spl_object_hash($e);
		$message = $e->getMessage();

		try{
			log_debug( "system,error,debug", "_catch $clazz ($id) $message @$file($line)", "exception" );
		}
		catch( Exception $ex ){
			echo "exeption while wrting log:" . $e->getMessage() . eol();
			exit;
		}
	}
}

//==================================================================
// bootstrap
require_once( CHARCOAL_HOME . '/src/classes/bootstrap/Bootstrap' . CHARCOAL_CLASS_FILE_SUFFIX );

