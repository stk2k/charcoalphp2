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

class Charcoal_ConsoleDebugtraceRenderer extends Charcoal_AbstractDebugtraceRenderer
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
	public function configure( $config )
	{
		parent::configure( $config );

	}

	/**
	 * Render debug trace
	 *
	 */
	public function render( $e )
	{
		Charcoal_ParamTrait::checkException( 1, $e );

		echo $this->output( $e );

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

		$out .= "=============================================================" . PHP_EOL;
		$out .= "CharcoalPHP Ver.{$version}: Exception stack trace " . PHP_EOL;
		$out .= "=============================================================" . PHP_EOL;

		$out .= PHP_EOL;
		$out .= "* Exception Stack *" . PHP_EOL;
		$out .= "-------------------------------------------------------------" . PHP_EOL;

		$first_exception = $e;

		$no = 1;
		while( $e )
		{
			// get exception info
			$clazz = get_class($e);
			$file = $e->getFile();
			$line = $e->getLine();
			$message = $e->getMessage();

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


		// get backtrace
		if ( $first_exception instanceof Charcoal_CharcoalException ){
			$backtrace = $first_exception->getBackTrace();

			$out .= PHP_EOL;
			$out .= "* Call Stack *" . PHP_EOL;
			$out .= "-------------------------------------------------------------" . PHP_EOL;

			// print backtrace
			$call_no = 0;
			foreach( $backtrace as $element ){
				$klass = isset($element['class']) ? $element['class'] : '';
				$func  = isset($element['function']) ? $element['function'] : '';
				$type  = isset($element['type']) ? $element['type'] : '';
				$file  = isset($element['file']) ? $element['file'] : '';
				$line  = isset($element['line']) ? $element['line'] : '';

				if ( $call_no > 0 ){
					$out .= PHP_EOL;
				}
				$out .= "[$call_no]{$klass}{$type}{$func}()" . PHP_EOL;
				$out .= "   {$file}($line)" . PHP_EOL;

				$call_no ++;
			}
		}

		return $out;
	}

}

