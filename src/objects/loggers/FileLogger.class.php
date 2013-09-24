<?php
/**
* ファイルに出力するロガークラス
*
* PHP version 5
*
* @package    loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileLogger extends Charcoal_AbstractLogger implements Charcoal_ILogger
{
	private $_open;
	private $_fp;
	private $_file_name;
	private $_line_end;

	const CRLF = "\r\n";

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_open         = false;
		$this->_fp           = null;
		$this->_file_name    = null;
	}

	/*
	 * デストラクタ
	 */
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$file_name   = $config->getString( 'file_name', 'charcoal.log', TRUE );
		$logs_dir    = $config->getString( 'logs_dir', '%WEBAPP_DIR%/logs', TRUE );

		if ( $file_name === NULL ){
			_throw( new Charcoal_ComponentConfigException( 'file_name', 'mandatory' ) );
		}

		$file_name = parent::formatFileName( $file_name );

		if ( $logs_dir !== NULL ){
			$this->_file_name = $logs_dir . DIRECTORY_SEPARATOR . $file_name;
		}
		else{
			$this->_file_name = Charcoal_ResourceLocator::getApplicationPath( 'logs', $file_name );
		}

		$this->_line_end  = $config->getString( 'line_end', self::CRLF );
	}

	/*
	 * ファイル名を取得
	 */
	public function getFileName(){
		return $this->_file_name;
	}

	/*
	 * ファイルがオープンされているか
	 */
	public function isOpen()
	{
		return $this->_open;
	}

	/*
	 * ファイルをオープンする
	 */
	public function open()
	{
		// すでに開いているならなにもしない
		if ( $this->_open ){
			return;
		}
		$file_name = us($this->_file_name);
		$dir_path = dirname($file_name);
		$dir = new Charcoal_File( s($dir_path) );

		// ディレクトリを作成
		try{
			$dir->makeDirectory( s('0777'), b(TRUE) );
		}
		catch( Exception $e ){
			print "FATAL error occured while output log file:{$this->_file_name} error=$e" . PHP_EOL;
		}

		$this->_fp = fopen($file_name, "a");
		$this->_open = ($this->_fp != FALSE);
	}

	/*
	 * ファイルを閉じる
	 */
	public function close()
	{
		if ( $this->_fp != null ){
			fclose ($this->_fp);
			$this->_fp = null;
		}
		$this->_open = false;
	}

	/*
	 * 出力
	 */
	protected function write( Charcoal_String $data )
	{
		$ret = fwrite( $this->_fp, us($data) );

		if ( $ret === FALSE ){
			print "[Warning]FileLogger fwrite failed. file=" . us($this->_file_name) . "<br>" . PHP_EOL;
		}
	}

	/*
	 * write one message
	 */
	public function writeln( Charcoal_LogMessage $message )
	{
		// 接続処理
		$this->open();

		// フォーマット
		$out = parent::formatMessage( $message )  . us($this->_line_end);

		$this->write( s($out) ); 

		$this->close();
	}

}

