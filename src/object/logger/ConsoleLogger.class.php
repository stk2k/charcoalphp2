<?php
/**
* コンソールに出力するロガークラス（主にデバッグ用）
*
* PHP version 5
*
* @package    objects.loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ConsoleLogger extends Charcoal_AbstractLogger implements Charcoal_ILogger
{
	/*
	 * コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );
	}

	/*
	 * ロガーをシャットダウン
	 */
	public function terminate()
	{
	}

	/*
	 * 一行出力
	 */
	public function writeln( $level, $message, $file, $line )
	{
		$time = date("y/m/d H:i:s");
		$file = basename($file);

		// 変数展開（PHP5.2.0以前との互換のため）
		$message = System::toString( $message );

		// エンコーディング変換
		$conv = Charcoal_EncodingConverter::fromString( $this->getSandbox(), 'PHP', 'LOG' );
		$message = $conv->convertEncoding( $message );

		// 画面出力
		$msg = $time . '[' . $level . '] ' . $message . '\t\t\t @' . $file . '(' . $line . ')'; 
		$msg = h($msg) . PHP_EOL;
		echo $msg;
	}

	/**
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->getLoggerName();
	}
}

