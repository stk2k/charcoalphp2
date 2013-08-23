<?php
/**
* PHPソースパーサクラス
*
* PHP version 5
*
* @package    debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_PhpSourceParser 
{
	private $_error;
	private $_keywords;

	const SUCCESS               = 0;
	const ERROR_FILENOTFOUND    = 1;

	const DELEMITER_ELEMENTS   = '<>?\'\"$-+*%/;{}!&():,.= 	[]';
//	const OPERATOR_ELEMENTS    = '&& || += -= .= + - * / % = == === < > <= >= != <> ++ -- and or xor !';

	const PHP_KEYWORDS = '';

	const LEXSTATE_NORMAL             = 'N';
	const LEXSTATE_BLOCK_COMMENT      = 'BC';
	const LEXSTATE_LINE_COMMENT       = 'LC';
	const LEXSTATE_CONST_STRING_DQ    = 'SD';
	const LEXSTATE_CONST_STRING_SQ    = 'SS';

	/**
	 *	コンストラクタ
	 */
	public function __construct()
	{
	}

	/**
	 *	初期化
	 */
	public function init( $keyword_file )
	{
		// キーワードファイルの読み込み
		$keywords = array();
		$fp = fopen( $keyword_file, 'r' );
		if ( !$fp ) {
			throw new Charcoal_PhpSourceParserException( self::ERROR_FILENOTFOUND, "Keyword file not found:$keyword_file" );
		}
		while ( !feof($fp) ) {
			$buffer = fgets( $fp, 4096 );
			$keyword = trim($buffer);
			$keywords[$keyword] = 1;
		}
		fclose( $fp );
		$this->_keywords = $keywords;
	}

	/**
	 *	トークンをダンプ
	 */
	public static function dumpTokens( $tokens ) 
	{
		$rows = count($tokens);
		for( $i=1; $i<$rows; $i++ ){
			printf( '%04d:', $i );
			$tokens_line = $tokens[$i];
			$cnt = count($tokens_line);
			for( $j=0; $j<$cnt; $j++ ){
				$token = $tokens_line[$j];
				echo $token;
			}
			echo "<BR>";
		}
	}

	/**
	 *	要素を取得
	 */
	private function getElement( $buffer, $state ) 
	{
		$is_keyword = isset($this->_keywords[$buffer]) ? TRUE : FALSE;
		$type = $is_keyword ? Charcoal_PhpSourceElement::TYPE_KEYWORD : Charcoal_PhpSourceElement::TYPE_IDENTIFIER;
		return new Charcoal_PhpSourceElement( $buffer, $type, $state );
	}

	/**
	 *	パース処理
	 */
	public function parse( $source_path ) 
	{
		if ( !is_file($source_path) ){
			return array();
		}

		// ソースファイルの読み込み
		$fp = fopen( $source_path, 'r' );
		if ( !$fp ) {
			throw new Charcoal_PhpSourceParserException( self::ERROR_FILENOTFOUND, "Source file not found:$source_path" );
		}
		$line_no = 1;
		while ( !feof($fp) ) {
			$buffer = fgets( $fp, 4096 );
			$source[$line_no++] = $buffer;
		}
		fclose( $fp );

		// 解析
		$state = self::LEXSTATE_NORMAL;
		$const_string_escaping = FALSE;

		$tokens = array();
		$rows = count($source);
		for( $i=1; $i<$rows; $i++ ){
			$line = $source[$i];
			$cols = strlen($line);
			$in_buffer = array();
			for($j=0;$j<$cols;$j++){
				$c = $line[$j];
				array_push( $in_buffer, $c );
			}

			$done_buffer = array();
			$buffer = '';
			$tokens_line = array();
			while( NULL !== ($c = array_shift($in_buffer)) ){
				switch( $state ){
				case self::LEXSTATE_NORMAL:
					{
						// デリミタ要素ならトークン区切りと判断
						$delimiter = (strpos(self::DELEMITER_ELEMENTS,$c) !== FALSE);
						if ( $delimiter ){
							if ( strlen($buffer) > 0 ){
								$tokens_line[] = $this->getElement( $buffer, $state );
							}
							$buffer = '';
						}
						// コメント突入判定
						$d = array_shift($in_buffer);
						if ( $d && $c === '/' && $d === '*' ){
							$state = self::LEXSTATE_BLOCK_COMMENT;
							$buffer = '/*';
							break;
						}
						if ( $d && $c === '/' && $d === '/' ){
							$state = self::LEXSTATE_LINE_COMMENT;
							$buffer = '//';
							break;
						}
						array_unshift( $in_buffer, $d );
						// 文字列定数判定
						if ( $c === '"' ){
							$state = self::LEXSTATE_CONST_STRING_DQ;
							$buffer = '"';
							break;
						}
						if ( $c === "'" ){
							$state = self::LEXSTATE_CONST_STRING_SQ;
							$buffer = "'";
							break;
						}
						// バッファに１文字だけ追加
						if ( $delimiter ){
							$tokens_line[] = new Charcoal_PhpSourceElement( $c, Charcoal_PhpSourceElement::TYPE_DELIMITER, $state );
							$buffer = '';
							array_unshift( $done_buffer, $c );
						}
						else{
							$buffer .= (string)$c;
							array_unshift( $done_buffer, $c );
						}
					}
					break;
				case self::LEXSTATE_BLOCK_COMMENT:
					{
						// コメント脱出判定
						$d = array_shift($in_buffer);
						if ( $d && $c === '*' && $d === '/' ){
							$buffer .= $c . $d;
							$state = self::LEXSTATE_NORMAL;
							$tokens_line[] = new Charcoal_PhpSourceElement( $buffer, Charcoal_PhpSourceElement::TYPE_COMMENT, $state );
							$buffer = '';
							array_unshift( $done_buffer, $c );
							array_unshift( $done_buffer, $d );
							break;
						}
						array_unshift( $in_buffer, $d );
						$buffer .= $c;
						array_unshift( $done_buffer, $c );
					}
					break;
				case self::LEXSTATE_LINE_COMMENT:
					{
						// コメント脱出判定
						$d = array_shift($in_buffer);
						if ( !$d ){
							$state = self::LEXSTATE_NORMAL;
							if ( strlen($buffer) > 0 ){
								$tokens_line[] = new Charcoal_PhpSourceElement( $buffer, Charcoal_PhpSourceElement::TYPE_COMMENT, $state );
								$buffer = '';
							}
							break;
						}
						array_unshift( $in_buffer, $d );
						$buffer .= $c;
						array_unshift( $done_buffer, $c );
					}
					break;
				case self::LEXSTATE_CONST_STRING_DQ:
					{
						// エスケープ判定
						if ( $c === '\\' ){
							$const_string_escaping = $const_string_escaping ? FALSE : TRUE;
						}
						// 文字列定数脱出判定
						if ( $c === '"' && !$const_string_escaping ){
							$state = self::LEXSTATE_NORMAL;
							$buffer .= $c;
							$tokens_line[] = new Charcoal_PhpSourceElement( $buffer, Charcoal_PhpSourceElement::TYPE_CONST_STRING, $state );
							$buffer = '';
							break;
						}
						$buffer .= $c;
						array_unshift( $done_buffer, $c );
					}
					break;
				case self::LEXSTATE_CONST_STRING_SQ:
					{
						// エスケープ判定
						if ( $c === '\\' ){
							$const_string_escaping = $const_string_escaping ? FALSE : TRUE;
						}
						// 文字列定数脱出判定
						$d = array_shift($done_buffer);
						if ( $c === "'" && !$const_string_escaping ){
							$state = self::LEXSTATE_NORMAL;
							$buffer .= $c;
							$tokens_line[] = new Charcoal_PhpSourceElement( $buffer, Charcoal_PhpSourceElement::TYPE_CONST_STRING, $state );
							$buffer = '';
							break;
						}
						$buffer .= $c;
						array_unshift( $done_buffer, $c );
					}
					break;
				}
			}
			if ( strlen($buffer) > 0 ){
				switch ( $state ){
				case self::LEXSTATE_BLOCK_COMMENT:
					{
						$tokens_line[] = new Charcoal_PhpSourceElement( $buffer, Charcoal_PhpSourceElement::TYPE_COMMENT, $state );
						$buffer = '';
					}
					break;
				default:
					{
						$tokens_line[] = $this->getElement( $buffer, $state );
						$buffer = '';
					}
					break;
				}
			}
			$tokens[$i] = $tokens_line;
			// １行コメントならノーマル状態に戻す
			if ( $state === self::LEXSTATE_LINE_COMMENT ){
				$state = self::LEXSTATE_NORMAL;
			}
		}

		return $tokens;
	}


}
return __FILE__;
