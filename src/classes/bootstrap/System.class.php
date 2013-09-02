<?php
/**
* Low level functions substituting for global functions
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_System
{
	const TOSTRING_MAX_LENGTH 	= 9999;
	const DUMP_MAX_LENGTH 		= 4096;

	/*
	 *  Convert PHP error number to string
	 */
	public static function phpErrorString( $errno )
	{
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
			E_DEPRECATED           => "E_DEPRECATED",
			E_USER_DEPRECATED      => "E_USER_DEPRECATED",
		);

		return isset($errors[$errno]) ? $errors[$errno] : '';
	}

	/*
	 *	配列の最後に別の配列の要素すべてを追加
	 */
	public static function appendArray( $a, $b )
	{
		if ( $a === NULL ){
			$a = array();
		}
		array_splice($a,count($a),0,$b);
		return $a;
	}

	/*
	 *	２変数の値を交換
	 */
	public static function swap( $a, $b )
	{
		$tmp = $a;
		$a = $b;
		$b = $tmp;
		return array( $a, $b );
	}

	/**
	 *	format byte size
	 */
	public static function formatByteSize( $size, $precision = 1, array $symbols = NULL )
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
	 *	ハッシュ値を生成
	 *
	 */
	public static function hash( $algorithm = 'sha1' )
	{
		switch( $algorithm )
		{
		case 'sha1':
			return sha1(uniqid(rand(),1));
		case 'md5':
			return md5(uniqid(rand(),1));
		}

		return NULL;
	}

	/**
	 *	HTML中の特殊文字を変換する
	 *
	 */
	public static function escape( $value )
	{
		if ( is_string($value) ){
			$res = htmlspecialchars($value, ENT_QUOTES, mb_internal_encoding());
//log_debug( "debug", "escape:" . print_r($res,true) );
			return $res;
		}
		else if ( is_array($value) ){
			return array_map('Charcoal_System::escape', $value);
		}
		else if ( is_object($value) ){
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
	 *	エスケープされた文字をHTMLに戻す
	 *
	 */
	public static function decode( $value )
	{
		if ( is_string($value) ){
//log_debug( "debug", "decode before:" . print_r($value,true) );
			$res = htmlspecialchars_decode($value, ENT_QUOTES);
//log_debug( "debug", "decode after:" . print_r($res,true) );
			return $res;
		}
		else if ( is_array($value) ){
			return array_map('Charcoal_System::decode', $value);
		}
		else if ( is_object($value) ){
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
	 *	文字列中のタグを除去（再帰メソッド）
	 *
	 */
	public static function stripTags( $value, $allowable_tags = NULL )
	{
		if ( is_string($value) ){
			$res = strip_tags($value, $allowable_tags);
//log_debug( "debug", "stripTags:" . print_r($res,true) );
			return $res;
		}
		else if ( is_array($value) ){
			$array = $value;
			foreach( $array as $key => $value ){
				$array[$key] = self::stripTags( $value, $allowable_tags );
			}
			return $array;
		}
		else if ( is_object($value) ){
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
	 *	文字列中のバックスラッシュを除去（再帰メソッド）
	 *
	 */
	public static function stripSlashes( $value )
	{
		if ( is_string($value) ){
			return stripslashes($value);
		}
		else if ( is_array($value) ){
			return array_map('Charcoal_System::stripSlashes', $value);
		}
		else if ( is_object($value) ){
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
	 *	文字列をエスケープ処理する
	 *
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
	 *	配列をマージ
	 *
	 */
	public static function arrayMerge( $array1, $array2 )
	{
		if ( $array1 === NULL ){
			$array1 = array();
		}
		if ( $array2 === NULL ){
			$array2 = array();
		}

		return array_merge( $array1, $array2 );
	}

	/**
	 *	ランダムな文字列を取得
	 *
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
	 *	呼び出し箇所
	 *
	 *	@return list($file,$line) ファイル名、行番号の配列
	 */
	public static function caller( $back = 0 )
	{
		$bt = debug_backtrace();
		$trace = $bt[1 + $back];

		$file = isset($trace['file']) ? $trace['file'] : "";
		$line = isset($trace['line']) ? $trace['line'] : "";

		return array( $file, $line );
	}

	/*
	 *    現在時間を取得
	 */
	public static function now(){ 
		list($msec,$sec) = explode(' ',microtime()); 
		return ( (float)$msec + (float)$sec );
	}

	/**
	 * 年月日から日付を作成
	 */
	public static function makeTime( $year, $month, $day, $hour = 0, $minute = 0, $second = 0 )
	{
	    return mktime( $hour, $minute, $second, $month, $day, $year );
	}

	/**
	 *	日付に日を加算
	 */
	public static function dateAdd( $year, $month, $day, $add )
	{
		$new_date = strtotime( "$year/$month/$day + $add days");

		return array( date('Y',$new_date), date('n',$new_date), date('j',$new_date) );
	}

	/**
	 *	日付から日を減算
	 */
	public static function dateSub( $year, $month, $day, $sub )
	{
		$new_date = strtotime( "$year/$month/$day - $sub days");

		return array( date('Y',$new_date), date('n',$new_date), date('j',$new_date) );
	}


	/**
	 *	２つの日付の差分を日単位で求める
	 */
	public static function dateDiff( $date1, $date2 = NULL ){
		if ( $date2 == NULL ){
			// 今日の日付
			$date2 = date("Y/m/d");
		}
		$result = strtotime($date2) - strtotime($date1);
		$result = intval( $result / (24 * 60 * 60));
		return $result;
	}

	/**
	 *	２つの日付を比較
	 */
	public static function compareDate( $date1, $date2 = NULL )
	{
		if ( $date2 == NULL ){
			// 今日の日付
			$date2 = date("Y/m/d");
		}
		$result = strtotime($date1) - strtotime($date2);
		return $result;
	}

	/**
	 *	２つの日付を比較
	 */
	public static function compareDateYMD( $year1, $month1, $day1, $year2, $month2, $day2 )
	{
		$result = strtotime("$year1/$month1/$day1") - strtotime("$year2/$month2/$day2");
		return $result;
	}

	/**
	 *	toStringのラッパー
	 *
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
				$ret = implode(',',$value);
				if ( $with_type ){
					$ret .= '(' . $type . ')';
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
	 *	配列を文字列に変換
	 *
	 */
	public static function arrayToString( $ary )
	{
		return self::implodeArray( ',', $ary );
	}

	/**
	 *	implodeのラッパー（5.1.6でクラスの__toStringが自動で呼ばれないため
	 *
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
	 *	implodeのラッパー（5.1.6でクラスの__toStringが自動で呼ばれないため
	 *
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
	 *    変数をダンプする
	 *
	 */
	public static function dump( $var, $back = 0, $options = NULL, $return = FALSE, $max_depth = 6 )
	{
		list( $file, $line ) = self::caller( $back );

		if ( !$options ){
			$options = array();
		}
		$default_options = array(
				'title' => 'system dump',
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
		self::_dump( '-', $var, 0, $max_string_length, $lines, $max_depth );

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
		}
	}

	private static function _dump( $key, $value, $depth, $max_string_length, &$lines, $max_depth )
	{
		if ( $depth > $max_depth ){
			$lines[] = str_repeat( '.', $depth * 4 ) . "......(max depth over:$max_depth)";
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
				$str = strval($value);
				if ( strlen($str) > $max_string_length ){
					$str = substr( $str, 0, $max_string_length ) . '...(total:' . strlen($str) . 'bytes)';
				}
				$str = htmlspecialchars( $str, ENT_QUOTES );
				$lines[] = str_repeat( '.', $depth * 4 ) . "[$key:$type]$str";
			}
			break;
		case 'array':
			{
				$lines[] = str_repeat( '.', $depth * 4 ) . "[$key:array(" . count($value) . ')]';
				foreach( $value as $_key => $_value ){
					self::_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth );
				}
			}
			break;
		case 'object':
			{
				$clazz = get_class( $value );
				$id = function_exists('spl_object_hash') ? spl_object_hash($value) : 'unknown';
				$lines[] = str_repeat( '.', $depth * 4 ) . "[$key:object($clazz)@$id]";
				$vars = self::getObjectVars( $value );
				foreach( $vars as $_key => $_value ){
					self::_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth );
				}
			}
			break;
		}
	}

	/**
	 *    変数の木構造をダンプする
	 *
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
				foreach( $value as $key => $value ){
					$type = gettype($value);
					if ( $type == 'array' || $type == 'object' ){
						self::_tree_dump( $key, $value, $depth + 1, $max_string_length, $lines, $max_depth );
					}
				}
			}
			break;
		case 'object':
			{
				$clazz = get_class( $value );
				$id = function_exists('spl_object_hash') ? spl_object_hash($value) : 'unknown';
				$lines[] = str_repeat( '-', $depth * 4 ) . "[$key:object($clazz)@$id]";
				$vars = self::getObjectVars( $value );
				foreach( $vars as $_key => $_value ){
					$_type = gettype($_value);
					if ( $_type == 'array' || $_type == 'object' ){
						self::_tree_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth );
					}
				}
			}
			break;
		}
	}

	/** 
	 *	improved version of get_object_vars 
	 */
	public static function getObjectVars( $obj )
	{
		$ref = new ReflectionObject( $obj );
		$vars = self::_getObjectVars( $obj, $ref );
		return $vars;
	}

	/** 
	 *	recursive function for getObjectVars
	 */
	public static function _getObjectVars( $obj, ReflectionClass $class_obj )
	{
		$vars = array();

		$props = $class_obj->getProperties();
		foreach( $props as $p ){
			$p->setAccessible(true);
			$key = $p->getName();
			$value = $p->getValue($obj);

			$vars[$key] = $value;
		}

		$parent = $class_obj->getParentClass();
		if ( $parent !== FALSE ){
			$vars_parent = self::_getObjectVars( $obj, $parent );
			$vars = array_merge( $vars_parent, $vars );
		}

		return $vars;
	}


	/** 
	 *	文字列のエンコーディング判定
	 */
	public static function detectEncoding( $str, $detect_order = "EUC-JP, SJIS, JIS, UTF-8" )
	{
		return mb_detect_encoding( $str, $detect_order, TRUE );
	}

	/**
	 *	エンコード変換
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
	 *	再帰エンコード変換
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
				if ( $var instanceof Charcoal_Iterator ){
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

	/**
	 *	再帰配列変換
	 */
	public static function convertArrayRecursive( $var )
	{
		$type = gettype($var);
		switch( $type ){
		case 'string':
		case 'integer':
		case 'double':
		case 'boolean':
		case 'NULL':
		case 'unknown type':
			{
				return $var;
			}
			break;
		case 'array':
			{
				$newArray = array();
				foreach( $var as $key => $value ){
					$value = self::convertArrayRecursive( $value );
					$newArray[ $key ] = $value;
				}
				return $newArray;
			}
			break;
		case 'object':
			{
				$newArray = array();
				$obj_vars = get_object_vars( $var );
				foreach( $obj_vars as $key => $value ){
					$value = self::convertArrayRecursive( $value );
					$newArray[$key] = $value;
				}
				return $newArray;
			}
			break;
		}

		return $var;
	}
}

