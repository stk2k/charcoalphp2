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

	const DEFAULT_LOG_FORMAT = '%Y4%-%M2%-%D2% %H2%:%M%:%S% [%LEVEL%] [%TAG%] %MESSAGE%       @%FILENAME%(%LINE%)';

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_converter = Charcoal_EncodingConverter::fromString( s('PHP'), s('LOG') );
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		parent::configure( $config );

		$this->_log_format   = $config->getString( s('log_format'), s(self::DEFAULT_LOG_FORMAT) );
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
				'%LEVEL%' => $level,
				'%TAG%' => $tag,
				'%MESSAGE%' => $message,
				'%FILE%' => $file,
				'%FILENAME%' => $file,
				'%LINE%' => $line,
			);

		// format message
		$out = $this->_log_format;

		foreach( $formatted_values as $search => $replace ){
			$out = str_replace( $search, $replace, $out );
		}

		return $out;
	}

	/*
	 * Format log file name
	 */
	public function formatFileName( Charcoal_String $file_name )
	{
		$req_id   = Charcoal_Framework::getRequestID();
		$req_path = str_replace(':','-',Charcoal_Framework::getRequestPath());
		$now_time = time();

		$file_name = us($file_name);

		// replace request ID
		$file_name = str_replace( '%REQUEST_ID%', $req_id, $file_name );

		// replace request path
		$file_name = str_replace( '%REQUEST_PATH%', $req_path, $file_name );

		// replace year
		$file_name = str_replace( '%Y%', date('Y',$now_time), $file_name );

		// replace month
		$file_name = str_replace( '%M%', date('m',$now_time), $file_name );

		// replace day
		$file_name = str_replace( '%D%', date('d',$now_time), $file_name );

		// replace hour
		$file_name = str_replace( '%H%', date('H',$now_time), $file_name );

		// replace minute
		$file_name = str_replace( '%I%', date('i',$now_time), $file_name );

		// replace second
		$file_name = str_replace( '%S%', date('s',$now_time), $file_name );

		return s($file_name);
	}

}

return __FILE__;