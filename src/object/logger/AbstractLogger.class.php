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

		// logging context specific values
		$now_time = time();
		$log_values = array(
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

		// replace keyword
		foreach( $log_values as $key => $value ){
			$out = str_replace( $key, $value, us($out) );
		}

		// replace environment values
		$out = $this->getSandbox()->getEnvironment()->fill( $out );

		return $out;
	}

	/*
	 * Fill macro value
	 */
	public function fillMacroValue( $value, $verifyFileName )
	{
		// environment object
		$env = $this->getSandbox()->getEnvironment();

		$parts = explode("/", $value);
		$ret = array();
		if ( is_array($parts) ){
			foreach( $parts as $item ){
				if ( strpos($item,'%') !== FALSE ){
					// maybe $part includes macro value
					$item = $env->fill( $item );
					if ( $verifyFileName ){
						static $invalid_chars = array(
							'\\', ':', ',', ';', '*', '?', '"', "'", '<', '>', '|', '/', "\0"
							);
						$item = str_replace($invalid_chars,'_',$item);
					}
				}
				$ret[] = $item;
			}
		}
		return implode('/', $ret);
	}

}

