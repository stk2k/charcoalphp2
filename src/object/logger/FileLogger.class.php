<?php
/**
* ファイルに出力するロガークラス
*
* PHP version 5
*
* @package    objects.loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FileLogger extends Charcoal_AbstractLogger implements Charcoal_ILogger
{
	private $open;
	private $fp;
	private $logs_dir;
	private $file_name;
	private $line_end;

	const CRLF = "\r\n";

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->open        = false;
		$this->fp          = null;
		$this->logs_dir    = null;
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

		$this->file_name   = $config->getString( 'file_name', '', TRUE );
		$this->logs_dir    = $config->getString( 'logs_dir', '%APPLICATION_DIR%/logs', TRUE );
		$this->line_end    = $config->getString( 'line_end', self::CRLF );

		// replace logs_dir with macro value
		$this->logs_dir = $this->fillMacroValue( $this->logs_dir, true );

		// replace file_name with macro value
		$this->file_name = $this->fillMacroValue( $this->file_name, true );

		if ( empty($this->file_name) ){
			_throw( new Charcoal_ComponentConfigException( 'file_name', 'mandatory' ) );
		}
	}

	/*
	 * ファイルがオープンされているか
	 */
	public function isOpen()
	{
		return $this->open;
	}

	/*
	 * ファイルをオープンする
	 */
	public function open()
	{
		// すでに開いているならなにもしない
		if ( $this->open ){
			return;
		}
		$file_name = rtrim($this->logs_dir, ' /') . '/' . ltrim($this->file_name, ' /');
		$dir = new Charcoal_File( dirname($file_name) );

		// ディレクトリを作成
		try{
			$dir->makeDirectory();
		}
		catch( Exception $e ){
			print "FATAL error occured while output log file:{$this->file_name} error=$e" . PHP_EOL;
		}

		$this->fp = fopen($file_name, "a");
		$this->open = ($this->fp != FALSE);
	}

	/*
	 * ファイルを閉じる
	 */
	public function close()
	{
		if ( $this->fp != null ){
			fclose ($this->fp);
			$this->fp = null;
		}
		$this->open = false;
	}

	/*
	 * 出力
	 */
	protected function write( Charcoal_String $data )
	{
		$ret = fwrite( $this->fp, us($data) );

		if ( $ret === FALSE ){
			print "[Warning]FileLogger fwrite failed. file=" . us($this->file_name) . "<br>" . PHP_EOL;
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
		$out = parent::formatMessage( $message )  . us($this->line_end);

		$this->write( s($out) ); 

		$this->close();
	}

}

