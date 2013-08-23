<?php
/**
* Console Debug Trace Renderer
*
* PHP version 5
*
* @package    debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_LogDebugtraceRenderer extends Charcoal_CharcoalObject implements Charcoal_IDebugtraceRenderer
{
	const LOG_EOL		= "\n";

	/**
	 *	constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
	}

	/**
	 * Render debug trace
	 *
	 * @param Charcoal_String $title  title
	 */
	public function render( Exception $e, Charcoal_String $title = NULL )
	{
		$message = ( $title ) ? $this->output( $e, $title ) : $this->output( $e );

		log_error( "debug,error", "debugtrace", $message );
	}

	/**
	 * Output HTML
	 *
	 * @param Charcoal_String $title  title
	 */
	public function output( Exception $e, Charcoal_String $title = NULL )
	{
		$out = '';
		$version = Charcoal_Framework::getVersion();

		$out .= "=============================================================" . self::LOG_EOL;
		$out .= "CharcoalPHP Ver.{$version}: Exception stack trace " . self::LOG_EOL;
		$out .= "=============================================================" . self::LOG_EOL;

		$out .= self::LOG_EOL;
		$out .= "* Exception Stack *" . self::LOG_EOL;
		$out .= "-------------------------------------------------------------" . self::LOG_EOL;

		$no = 1;
		while( $e )
		{
			// get exception info
			$clazz = get_class($e);
			$file = $e->getFile();
			$line = $e->getLine();
			$message = $e->getMessage();
			$backtrace = $e->getBackTrace();

			// print exception info
			$out .= "[$no]$clazz" . self::LOG_EOL;
			$out .= "   $file($line)" . self::LOG_EOL;
			$out .= "   $message" . self::LOG_EOL;

			// move to previous exception
			$e = $e->getPrevious();
			$no ++;
			if ( $e ){
				$out .= self::LOG_EOL;
			}
		}

		$out .= self::LOG_EOL;
		$out .= "* Call Stack *" . self::LOG_EOL;
		$out .= "-------------------------------------------------------------" . self::LOG_EOL;

		// print backtrace
		$call_no = 0;
		foreach( $backtrace as $element ){
			$klass = isset($element['class']) ? $element['class'] : '';
			$func  = isset($element['function']) ? $element['function'] : '';
			$type  = isset($element['type']) ? $element['type'] : '';
			$args  = isset($element['args']) ? $element['args'] : array();
			$file  = isset($element['file']) ? $element['file'] : '';
			$line  = isset($element['line']) ? $element['line'] : '';

			$args_disp = '';
			foreach( $args as $arg ){
				if ( strlen($args_disp) > 0 ){
					$args_disp .= ',';
				}
				$args_disp .= Charcoal_System::toString($arg);
			}

			if ( $call_no > 0 ){
				$out .= self::LOG_EOL;
			}
			$out .= "[$call_no]{$klass}{$type}{$func}($args_disp)" . self::LOG_EOL;
			$out .= "   {$file}($line)" . self::LOG_EOL;

			$call_no ++;
		}

		return $out;
	}

}
return __FILE__;
