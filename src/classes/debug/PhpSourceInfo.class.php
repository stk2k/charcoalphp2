<?php
/**
* PHPソース情報クラス
*
* PHP version 5
*
* @package    debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_PhpSourceInfo extends Charcoal_Object
{
	const DEFAULT_RANGE        = 5;

	private $_file;
	private $_line;
	private $_range;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_String $file, Charcoal_Integer $line, Charcoal_Integer $range = NULL )
	{
		parent::__construct();

		$this->_file = us($file);
		$this->_line = ui($line);
		$this->_range = $range ? ui($range) : self::DEFAULT_RANGE;
	}


	/*
	 *	ソースファイル名を取得
	 */
	public function getFile()
	{
		return $this->_file;
	}

	/*
	 *	行番号を取得
	 */
	public function getLine()
	{
		return $this->_line;
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		$keyword_file = Charcoal_ResourceLocator::getFrameworkPath( s('data'), s('php.kwd') );

		$file = $this->_file;
		$line = $this->_line;

		$p = new Charcoal_PhpSourceParser();
		$p->init( $keyword_file );
		$tokens = $p->parse( $this->_file );
		$html = Charcoal_PhpSourceRenderer::render( $tokens, '%4d:', NULL, $line - $this->_range, $line + $this->_range );

		return $html;
	}
}

