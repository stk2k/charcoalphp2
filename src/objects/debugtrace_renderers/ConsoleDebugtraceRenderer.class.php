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

class Charcoal_ConsoleDebugtraceRenderer extends Charcoal_CharcoalObject implements Charcoal_IDebugtraceRenderer
{
	/*
	 *	コンストラクタ
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
	 */
	public function render( Exception $e )
	{
		echo $this->output( $e );

		return b(TRUE);
	}

	/**
	 * Output HTML
	 *
	 */
	public function output( Exception $e )
	{
		$out = '';
		$version = Charcoal_Framework::getVersion();

		$out .= "=============================================================" . PHP_EOL;
		$out .= "CharcoalPHP Ver.{$version}: Exception stack trace " . PHP_EOL;
		$out .= "=============================================================" . PHP_EOL;

		$out .= PHP_EOL;
		$out .= "* Exception Stack *" . PHP_EOL;
		$out .= "-------------------------------------------------------------" . PHP_EOL;

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
			$out .= "[$no]$clazz" . PHP_EOL;
			$out .= "   $file($line)" . PHP_EOL;
			$out .= "   $message" . PHP_EOL;

			// move to previous exception
			$e = $e->getPrevious();
			$no ++;
			if ( $e ){
				$out .= PHP_EOL;
			}
		}

		$out .= PHP_EOL;
		$out .= "* Call Stack *" . PHP_EOL;
		$out .= "-------------------------------------------------------------" . PHP_EOL;

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
				$out .= PHP_EOL;
			}
			$out .= "[$call_no]{$klass}{$type}{$func}($args_disp)" . PHP_EOL;
			$out .= "   {$file}($line)" . PHP_EOL;

			$call_no ++;
		}

		return $out;
	}

}

