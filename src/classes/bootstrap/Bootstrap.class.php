<?php
/**
 * CharcoalPHP ver 2.9.6
 * E-Mail for multibyte charset
 *
 * PHP versions 5 and 6 (PHP5.2 upper)
 *
 * Copyright 2013, stk2k in japan
 * Technical  :  http://charcoalphp.org/
 * Licensed under The MIT License License
 *
 * @copyright		Copyright 2013, stk2k.
 * @link			http://charcoalphp.org/
 * @version			2.9.6
 * @lastmodified	2013-04-26
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
 * CharcoalPHP is a task-oriented web framework.
 * 
 * Copyright (C) 2013   stk2k 
 */

/**
 *
 *	class : Charcoal_Bootstrap
 *
 *	Initialize minimum set of framework funtions(class loader, logger, DI container)
 *
 */
class Charcoal_Bootstrap
{
	static $debug;

	/*
	 *	Framework global error handler
	 */
	public static function onUnhandledError( $errno, $errstr, $errfile, $errline )
	{ 
		if ( $errno == E_ERROR || $errno == E_PARSE || $errno == E_RECOVERABLE_ERROR || $errno == E_USER_ERROR )
		{
			// create fake exception
			$e = new Charcoal_PHPErrorException($errno, $errstr, $errfile, $errline);

			_throw( $e );

//			Charcoal_FrameworkExceptionStack::push( $e );

			exit;	// prevent unnecessary errors to add
		}
		if ( (error_reporting() & $errno) === $errno ){
			$errno = Charcoal_System::phpErrorString( $errno );
			echo "[errno]$errno [errstr]$errstr [errfile]$errfile [errline]$errline" . eol();
		}
		return TRUE;	// Otherwise, ignore all errors
	}

	/*
	 *	Framework global exception handler
	 */
	public static function onUnhandledException( $e )
	{ 
		_catch( $e );

		log_fatal( "system,error", "charcoal_global_exception_handler: $exception" );

		// —áŠOƒnƒ“ƒhƒ‰‚Éˆ—‚ðˆÏ÷
		Charcoal_Framework::handleException( $e );
	}

	/*
	 *	Framework global shutdown handler
	 */
	public static function onShutdown()
	{
	//	log_info( "system,debug", "shutdown", 'Shutdown handler start' );

		if ( $error = error_get_last() )
		{
			switch( $error['type'] )
			{
				case E_ERROR:
				case E_PARSE:
				case E_CORE_ERROR:
				case E_CORE_WARNING:
				case E_COMPILE_ERROR:
				case E_COMPILE_WARNING:
				case E_USER_ERROR:
					$e = new Charcoal_PHPErrorException($error['type'], $error['message'], $error['file'], $error['line']);
					Charcoal_FrameworkExceptionStack::push( $e );
					break;
			}
		}

		while( $e = Charcoal_FrameworkExceptionStack::pop() )
		{
			// Delegate framework exception handling to handlers
			$handled = Charcoal_Framework::handleException( $e );
			$handled = b($handled);
			if ( $handled->isFalse() )
			{
				// Forgot to handle exception?
				Charcoal_Framework::renderExceptionFinally( $e );
			}
		}

		Charcoal_Framework::$loggers->terminate();
	}

	static $bootstrap_classes  = array(

				// Basic enum classes	
				'Charcoal_EnumCoreHookStage' 					=> 'constants',

				// Basic interface classes	
				'Charcoal_ICollection' 							=> 'interfaces',
				'Charcoal_IProperties' 							=> 'interfaces',
				'Charcoal_IClassLoader' 						=> 'interfaces',
				'Charcoal_IConfigProvider'						=> 'interfaces',
				'Charcoal_ICharcoalObject'						=> 'interfaces',
				'Charcoal_IDebugtraceRenderer'					=> 'interfaces',
				'Charcoal_IExceptionHandler'					=> 'interfaces',
				'Charcoal_IDebugtraceRenderer'					=> 'interfaces',
				'Charcoal_ILogger'								=> 'interfaces',
				'Charcoal_IRegistry'							=> 'interfaces',
				'Charcoal_ICodebase'							=> 'interfaces',
				'Charcoal_IContainer'							=> 'interfaces',
				'Charcoal_ICoreHook'							=> 'interfaces',
				'Charcoal_IUnboxable' 							=> 'interfaces',
				'Charcoal_IEnvironment' 						=> 'interfaces',

				// traits
				'Charcoal_ParamTrait' 							=> 'traits',
				'Charcoal_PrimitiveTrait' 						=> 'traits',
				'Charcoal_ArrayTrait' 							=> 'traits',

				// Basic object classes	
				'Charcoal_Object' 								=> 'classes/base',
				'Charcoal_Class' 								=> 'classes/base',
				'Charcoal_ObjectPath' 							=> 'classes/base',
				'Charcoal_CharcoalObject'						=> 'classes/base',
				'Charcoal_File'									=> 'classes/base',
				'Charcoal_Interface' 							=> 'classes/base',

				// Basic exception classes
				'Charcoal_ArrayFormatException'					=> 'exceptions',
				'Charcoal_BooleanFormatException'				=> 'exceptions',
				'Charcoal_CharcoalException' 					=> 'exceptions',
				'Charcoal_ClassNameEmptyException'				=> 'exceptions',
				'Charcoal_ConfigSectionNotFoundException'		=> 'exceptions',
				'Charcoal_ConfigException' 						=> 'exceptions',
				'Charcoal_ClassLoaderConfigException' 			=> 'exceptions',
				'Charcoal_ClassLoaderRegistrationException' 	=> 'exceptions',
				'Charcoal_ClassNewException'					=> 'exceptions',
				'Charcoal_ClassNotFoundException' 				=> 'exceptions',
				'Charcoal_CreateClassLoaderException'			=> 'exceptions',
				'Charcoal_CreateObjectException'				=> 'exceptions',
				'Charcoal_HashMapFormatException'				=> 'exceptions',
				'Charcoal_IllegalRunModeException'				=> 'exceptions',
				'Charcoal_InterfaceImplementException'			=> 'exceptions',
				'Charcoal_InterfaceNotFoundException'			=> 'exceptions',
				'Charcoal_FileNotFoundException' 				=> 'exceptions',
				'Charcoal_FileNotReadableException' 			=> 'exceptions',
				'Charcoal_FloatFormatException'					=> 'exceptions',
				'Charcoal_FrameworkBootstrapException' 			=> 'exceptions',
				'Charcoal_IntegerFormatException'				=> 'exceptions',
				'Charcoal_LogicException' 						=> 'exceptions',
				'Charcoal_ModuleLoaderException'				=> 'exceptions',
				'Charcoal_ParameterException'					=> 'exceptions',
				'Charcoal_PHPErrorException' 					=> 'exceptions',
				'Charcoal_ProfileDirectoryNotFoundException'	=> 'exceptions',
				'Charcoal_ProfileLoadingException'				=> 'exceptions',
				'Charcoal_ProfileConfigFileNotFoundException'	=> 'exceptions',
				'Charcoal_RuntimeException' 					=> 'exceptions',
				'Charcoal_SandboxNotLoadedException'			=> 'exceptions',
				'Charcoal_StringFormatException'				=> 'exceptions',

				// Primitive classes
				'Charcoal_Primitive' 					=> 'classes/base',
				'Charcoal_Number' 						=> 'classes/base',
				'Charcoal_Boolean'						=> 'classes/base',
				'Charcoal_Date' 						=> 'classes/base',
				'Charcoal_DateWithTime'					=> 'classes/base',
				'Charcoal_Float' 						=> 'classes/base',
				'Charcoal_Integer' 						=> 'classes/base',
				'Charcoal_String' 						=> 'classes/base',

				// Basic collection classes
				'Charcoal_Enum' 						=> 'classes/base',
				'Charcoal_Collection'						=> 'classes/base',
				'Charcoal_List' 						=> 'classes/base',
				'Charcoal_Vector' 						=> 'classes/base',
				'Charcoal_HashMap' 						=> 'classes/base',
				'Charcoal_Properties' 					=> 'classes/base',
				'Charcoal_Queue' 						=> 'classes/base',
				'Charcoal_Stack' 						=> 'classes/base',

				// Basic config provider classes
				'Charcoal_AbstractConfigProvider'		=> 'objects/config_providers',
				'Charcoal_IniConfigProvider'			=> 'objects/config_providers',
				'Charcoal_CachedIniConfigProvider'		=> 'objects/config_providers',
				'Charcoal_PhpConfigProvider'			=> 'classes/config_providers',

				// Bootstrap classes
				'Charcoal_CacheDriverList'				=> 'classes/bootstrap',
				'Charcoal_ClassLoader'					=> 'classes/bootstrap',
				'Charcoal_ConfigPropertySet'			=> 'classes/bootstrap',
				'Charcoal_Config' 						=> 'classes/bootstrap',
				'Charcoal_ConfigLoader' 				=> 'classes/bootstrap',
				'Charcoal_CoreHookList'					=> 'classes/bootstrap',
				'Charcoal_DebugTraceRendererList'		=> 'classes/bootstrap',
				'Charcoal_ExceptionHandlerList'			=> 'classes/bootstrap',
				'Charcoal_Framework' 					=> 'classes/bootstrap',
				'Charcoal_FrameworkVersion' 			=> 'classes/bootstrap',
				'Charcoal_FrameworkExceptionStack'		=> 'classes/bootstrap',
				'Charcoal_LoggerList'					=> 'classes/bootstrap',
				'Charcoal_LogMessage' 					=> 'classes/bootstrap',
				'Charcoal_SandboxProfile' 				=> 'classes/bootstrap',
				'Charcoal_ResourceLocator' 				=> 'classes/bootstrap',
				'Charcoal_System' 						=> 'classes/bootstrap',
				'Charcoal_Sandbox' 						=> 'classes/bootstrap',

				// Class loaders
				'Charcoal_FrameworkClassLoader' 			=> 'objects/class_loaders',
				'Charcoal_UserClassLoader'				=> 'objects/class_loaders',

				// exception handler classes
				'Charcoal_AbstractExceptionHandler'					=> 'objects/exception_handlers',
				'Charcoal_HttpErrorDocumentExceptionHandler'	=> 'objects/exception_handlers',
				'Charcoal_HtmlFileOutputExceptionHandler'		=> 'objects/exception_handlers',
				'Charcoal_ConsoleOutputExceptionHandler'		=> 'objects/exception_handlers',

				// debugtrace renderer classes
				'Charcoal_AbstractDebugtraceRenderer'			=> 'objects/debugtrace_renderers',
				'Charcoal_HtmlDebugtraceRenderer'			=> 'objects/debugtrace_renderers',
				'Charcoal_ConsoleDebugtraceRenderer'		=> 'objects/debugtrace_renderers',
				'Charcoal_LogDebugtraceRenderer'			=> 'objects/debugtrace_renderers',

				// debug classes
				'Charcoal_Benchmark'				=> 'classes/debug',
				'Charcoal_CallHistory'				=> 'classes/debug',
				'Charcoal_DebugProfiler'			=> 'classes/debug',
				'Charcoal_MethodSpec'				=> 'classes/debug',
				'Charcoal_FunctionSpec'				=> 'classes/debug',
				'Charcoal_PhpSourceElement'			=> 'classes/debug',
				'Charcoal_PhpSourceInfo'			=> 'classes/debug',
				'Charcoal_PhpSourceParser'			=> 'classes/debug',
				'Charcoal_PhpSourceRenderer'		=> 'classes/debug',
				'Charcoal_PopupDebugWindow'			=> 'classes/debug',

				// logger classes
				'Charcoal_AbstractLogger'							=> 'objects/loggers',
				'Charcoal_CsvFileLogger'						=> 'objects/loggers',
				'Charcoal_FileLogger'							=> 'objects/loggers',
				'Charcoal_HtmlFileLogger'						=> 'objects/loggers',
				'Charcoal_ScreenLogger'							=> 'objects/loggers',
				'Charcoal_PopupScreenLogger'					=> 'objects/loggers',
				'Charcoal_ConsoleLogger'						=> 'objects/loggers',

				// registry classes
				'Charcoal_AbstractRegistry'						=> 'classes/bootstrap/registry',
				'Charcoal_FileSystemRegistry'					=> 'classes/bootstrap/registry',
				'Charcoal_MemoryRegistry'						=> 'classes/bootstrap/registry',

				// codebase classes
				'Charcoal_AbstractCodebase'						=> 'classes/bootstrap/codebase',
				'Charcoal_PlainCodebase'						=> 'classes/bootstrap/codebase',

				// container classes
				'Charcoal_AbstractContainer' 					=> 'classes/bootstrap/container',
				'Charcoal_DIContainer' 							=> 'classes/bootstrap/container',
				'Charcoal_AopContainer'							=> 'classes/bootstrap/container',

				// environment classes
				'Charcoal_AbstractEnvironment' 					=> 'classes/bootstrap/environment',
				'Charcoal_ArrayEnvironment' 					=> 'classes/bootstrap/environment',
				'Charcoal_HttpEnvironment' 						=> 'classes/bootstrap/environment',
				'Charcoal_ShellEnvironment' 					=> 'classes/bootstrap/environment',

				// utility classes
				'Charcoal_EncodingConverter'					=> 'classes/util',

				// core hook classes
				'Charcoal_AbstractCoreHook'						=> 'objects/core_hooks',
				'Charcoal_SimpleLogCoreHook'					=> 'objects/core_hooks',
				'Charcoal_SimpleEchoCoreHook'					=> 'objects/core_hooks',

			);

	/**
	 *	autoload function for bootstrap
	 *
	 */
	public static function loadClass( $class_name )
	{
		if ( !isset(self::$bootstrap_classes[$class_name]) ){
			if ( self::$debug )	echo "Class not found in bootstrap class loader: $class_name" . eol();
			return FALSE;
		}

		$file_name = $class_name . CHARCOAL_CLASS_FILE_SUFFIX;
		$pos = strpos( $file_name, CHARCOAL_CLASS_PREFIX );
		if ( $pos !== FALSE ){
			$file_name = substr( $file_name, $pos + strlen(CHARCOAL_CLASS_PREFIX) );
		}

		$class_path = CHARCOAL_HOME . '/src/' . self::$bootstrap_classes[ $class_name ] . '/' . $file_name;

		if ( self::$debug )	echo "loading file[$class_path] for class: $class_name" . eol();

		include( $class_path );

		return TRUE;
	}

	/**
	 *	run bootstrap
	 *
	 */
	public function run( $debug = FALSE )
	{
		self::$debug = $debug;

		// register bootstrap clas loader
		if ( !spl_autoload_register('Charcoal_Bootstrap::loadClass',false) )
		{
			echo "registering bootstrap class loader failed." . eol();
			exit;
		}

		// register system handlers
		register_shutdown_function( 'Charcoal_Bootstrap::onShutdown' );
		set_error_handler( "Charcoal_Bootstrap::onUnhandledError" );
		set_exception_handler( "Charcoal_Bootstrap::onUnhandledException" );

	}

}

