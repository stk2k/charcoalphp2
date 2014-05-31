<?php
/**
* Console Debug Trace Renderer
*
* PHP version 5
*
* @package    objects.debugtrace_renderers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_LogDebugtraceRenderer extends Charcoal_AbstractDebugtraceRenderer
{
	const LOG_EOL		= "\n";

	/**
	 * Render debug trace
	 *
	 */
	public function render( $e )
	{
		Charcoal_ParamTrait::checkException( 1, $e );

		$message = $this->output( $e );

		log_error( "debug,error,debugtrace", "debugtrace", $message );

		return TRUE;
	}

	/**
	 * Output HTML
	 *
	 */
	public function output( $e )
	{
		Charcoal_ParamTrait::checkException( 1, $e );

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
			$backtrace = ($e instanceof Charcoal_CharcoalException) ? $e->getBackTrace() : NULL;

			// print exception info
			$out .= "[$no]$clazz" . self::LOG_EOL;
			$out .= "   $file($line)" . self::LOG_EOL;
			$out .= "   $message" . self::LOG_EOL;

			// move to previous exception
			$e = method_exists( $e, 'getPreviousException' ) ? $e->getPreviousException() : NULL;
			$no ++;
			if ( $e ){
				$out .= self::LOG_EOL;
			}
		}

		if ( $backtrace === NULL || !is_array($backtrace) ){
			return $out;
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

