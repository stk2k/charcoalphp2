<?php
/**
* Base class of logger
*
* PHP version 5
*
* @package    objects.loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_AbstractLogger extends Charcoal_CharcoalObject
{
	private $log_format;
	private $converter;

	const DEFAULT_LOG_FORMAT = '%Y4%-%M2%-%D2% %H2%:%M%:%S% [%REQUEST_PATH%] [%REMOTE_ADDR%] [%LEVEL%] [%TAG%] %MESSAGE%       @%FILENAME%(%LINE%)';

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


		$this->log_format   = $config->getString( 'log_format', self::DEFAULT_LOG_FORMAT );

		$this->converter = Charcoal_EncodingConverter::fromString( $this->getSandbox(), 'PHP', 'LOG' );

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
		return $this->converter->convert( s($message) );
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

		// set log format string as initial value
		$out = $this->log_format;

		// replace environment values
		$out = $this->getSandbox()->getEnvironment()->fill( $out );

		// logging context specific values
		$log_values = array(
				'%LEVEL%' => $level,
				'%TAG%' => $tag,
				'%MESSAGE%' => $message,
				'%FILE%' => $file,
				'%FILENAME%' => $file,
				'%LINE%' => $line,
			);

		// replace keyword
		foreach( $log_values as $key => $value ){
			$out = str_replace( $key, $value, us($out) );
		}

		return $out;
	}

	/*
	 * Format log file name
	 */
	public function formatFileName( $file_name )
	{
//		Charcoal_ParamTrait::checkString( 1, $file_name );

		$file_name = us($file_name);

		$file_name = $this->getSandbox()->getEnvironment()->fill( $file_name )->replace( ':', '_' );

		return us($file_name);
	}

}

