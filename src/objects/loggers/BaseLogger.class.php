<?php
/**
* Base class of logger
*
* PHP version 5
*
* @package    loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_BaseLogger extends Charcoal_CharcoalObject
{
	private $_log_format;
	private $_converter;
	private $_value_cache;

	const DEFAULT_LOG_FORMAT = '%Y4%-%M2%-%D2% %H2%:%M%:%S% [%REMOTE_ADDR%] [%LEVEL%] [%TAG%] %MESSAGE%       @%FILENAME%(%LINE%)';

	/*
	 *	Construct object
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

		$this->_log_format   = $config->getString( s('log_format'), s(self::DEFAULT_LOG_FORMAT) );

		$this->_converter = Charcoal_EncodingConverter::fromString( $this->getSandbox(), 'PHP', 'LOG' );

		$now_time = time();

		$this->_value_cache = array(
				'%Y4%' => date("Y",$now_time),
				'%Y2%' => date("y",$now_time),
				'%M2%' => date("m",$now_time),
				'%M1%' => date("n",$now_time),
				'%D2%' => date("d",$now_time),
				'%D1%' => date("j",$now_time),
				'%H2%' => date("H",$now_time),
				'%H1%' => date("G",$now_time),
				'%h2%' => date("h",$now_time),
				'%h1%' => date("g",$now_time),
				'%M%'  => date("i",$now_time),
				'%S%'  => date("s",$now_time),
				'%REMOTE_ADDR%' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL,
				'%REQUEST_ID%' => Charcoal_Framework::getRequestID(),
				'%REQUEST_PATH%' => Charcoal_Framework::getRequestPath(),
			);
	}


	/*
	 * write header message
	 */
	public function writeHeader()
	{
	}

	/*
	 * write footer message
	 */
	public function writeFooter()
	{
	}

	/*
	 * teminate logger
	 */
	public function terminate()
	{
	}

	/**
	 * Convert encoding
	 */
	public function convertEncoding( Charcoal_String $message )
	{
		return $this->_converter->convert( s($message) );
	}

	/**
	 * Format message
	 */
	public function formatMessage( Charcoal_LogMessage $msg )
	{
//		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_LogMessage', $msg );

		$level   = $msg->getLevel();
		$tag     = $msg->getTag();
		$message = $msg->getMessage();
		$file    = $msg->getFile();
		$line    = $msg->getLine();

		$time = date("y/m/d H:i:s");

		// Convert encoding
		$message = $this->convertEncoding( s($message) );

		// Get now time
		$now_time = time();

		// formatted date values
		$formatted_values = array(
				'%LEVEL%' => $level,
				'%TAG%' => $tag,
				'%MESSAGE%' => $message,
				'%FILE%' => $file,
				'%FILENAME%' => $file,
				'%LINE%' => $line,
			);

		// merge values
		$formatted_values = array_merge( $formatted_values, $this->_value_cache );

		// set log format string as initial value
		$out = $this->_log_format;

		// replace keyword
		foreach( $formatted_values as $key => $value ){
			$out = str_replace( $key, $value, $out );
		}

		return $out;
	}

	/*
	 * Format log file name
	 */
	public function formatFileName( $file_name )
	{
		Charcoal_ParamTrait::checkString( 1, $file_name );

		$out = us($file_name);

		// replace keyword
		foreach( $this->_value_cache as $key => $value ){
			$value = str_replace( ':', '_', $value );
			$out = str_replace( $key, $value, $out );
		}

		return $out;
	}

}

