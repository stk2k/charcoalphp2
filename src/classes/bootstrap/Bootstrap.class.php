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
	/**
	 *	autoload function for bootstrap
	 *
	 */
	public static function loadClass( $class_name )
	{
		static $bootstrap_classes;

		if ( !$bootstrap_classes )
		{
			$bootstrap_classes = array(

					// Basic enum classes	
					'Charcoal_EnumCoreHookStage' 					=> 'constants',
					'Charcoal_EnumEchoFlag' 						=> 'constants',

					// Basic interface classes	
					'Charcoal_IProperties' 							=> 'interfaces',
					'Charcoal_IClassLoader' 						=> 'interfaces',
					'Charcoal_IConfigProvider'						=> 'interfaces',
					'Charcoal_ICharcoalObject'						=> 'interfaces',
					'Charcoal_IDebugtraceRenderer'					=> 'interfaces',
					'Charcoal_IExceptionHandler'					=> 'interfaces',

					// Basic object classes	
					'Charcoal_Object' 								=> 'classes/base',
					'Charcoal_Class' 								=> 'classes/base',
					'Charcoal_ObjectPath' 							=> 'classes/base',
					'Charcoal_CharcoalObject'						=> 'classes/base',
					'Charcoal_File'									=> 'classes/base',
					'Charcoal_Interface' 							=> 'classes/base',

					// Basic exception classes
					'Charcoal_CharcoalException' 					=> 'exceptions',
					'Charcoal_LogicException' 						=> 'exceptions',
					'Charcoal_RuntimeException' 						=> 'exceptions',
					'Charcoal_ConfigException' 						=> 'exceptions',
					'Charcoal_ClassLoaderConfigException' 			=> 'exceptions',
					'Charcoal_ClassNewException'						=> 'exceptions',
					'Charcoal_ClassNotFoundException' 				=> 'exceptions',
					'Charcoal_CreateObjectException'					=> 'exceptions',
					'Charcoal_InterfaceImplementException'			=> 'exceptions',
					'Charcoal_InterfaceNotFoundException'			=> 'exceptions',
					'Charcoal_FileNotFoundException' 				=> 'exceptions',
					'Charcoal_PHPErrorException' 					=> 'exceptions',
					'Charcoal_ProfileDirectoryNotFoundException'		=> 'exceptions',
					'Charcoal_ProfileLoadingException'				=> 'exceptions',
					'Charcoal_ProfileConfigFileNotFoundException'	=> 'exceptions',
					'Charcoal_ConfigSectionNotFoundException'		=> 'exceptions',
					'Charcoal_ClassNameEmptyException'				=> 'exceptions',
					'Charcoal_ModuleLoaderException'					=> 'exceptions',

					// Primitive classes
					'Charcoal_Primitive' 					=> 'classes/base',
					'Charcoal_Number' 						=> 'classes/base',
					'Charcoal_Boolean'						=> 'classes/base',
					'Charcoal_Date' 							=> 'classes/base',
					'Charcoal_DateWithTime'					=> 'classes/base',
					'Charcoal_Float' 						=> 'classes/base',
					'Charcoal_Integer' 						=> 'classes/base',
					'Charcoal_String' 						=> 'classes/base',

					// Basic collection classes
					'Charcoal_List' 							=> 'classes/base',
					'Charcoal_Vector' 						=> 'classes/base',
					'Charcoal_HashMap' 						=> 'classes/base',
					'Charcoal_Properties' 					=> 'classes/base',
					'Charcoal_Queue' 						=> 'classes/base',
					'Charcoal_Stack' 						=> 'classes/base',

					// Basic config provider classes
					'Charcoal_IniConfigProvider'				=> 'objects/config_providers',

					// Bootstrap classes
					'Charcoal_ClassLoader'					=> 'classes/bootstrap',
					'Charcoal_ConfigPropertySet'				=> 'classes/bootstrap',
					'Charcoal_Config' 						=> 'classes/bootstrap',
					'Charcoal_ConfigLoader' 					=> 'classes/bootstrap',
					'Charcoal_CoreHook'						=> 'classes/bootstrap',
					'Charcoal_CoreHookMessage'				=> 'classes/bootstrap',
					'Charcoal_DebugTraceRendererList'		=> 'classes/bootstrap',
					'Charcoal_DIContainer' 					=> 'classes/bootstrap',
					'Charcoal_ExceptionHandlerList'			=> 'classes/bootstrap',
					'Charcoal_Factory' 						=> 'classes/bootstrap',
					'Charcoal_Framework' 					=> 'classes/bootstrap',
					'Charcoal_FrameworkExceptionStack'		=> 'classes/bootstrap',
					'Charcoal_Logger' 						=> 'classes/bootstrap',
					'Charcoal_LogMessage' 					=> 'classes/bootstrap',
					'Charcoal_Profile' 						=> 'classes/bootstrap',
					'Charcoal_ResourceLocator' 				=> 'classes/bootstrap',
					'Charcoal_System' 						=> 'classes/bootstrap',

					// Class loaders
					'Charcoal_FrameworkClassLoader' 			=> 'objects/class_loaders',
					'Charcoal_UserClassLoader'				=> 'objects/class_loaders',

					// Benchmark
					'Charcoal_Benchmark'						=> 'classes/debug',
				);
		}

		if ( !isset($bootstrap_classes[$class_name]) )	return FALSE;

		$file_name = $class_name . CHARCOAL_CLASS_FILE_SUFFIX;
		$pos = strpos( $file_name, CHARCOAL_CLASS_PREFIX );
		if ( $pos !== FALSE ){
			$file_name = substr( $file_name, $pos + strlen(CHARCOAL_CLASS_PREFIX) );
		}

		$class_path = CHARCOAL_HOME . '/src/' . $bootstrap_classes[ $class_name ] . '/' . $file_name;

		require_once( $class_path );

		return TRUE;
	}

	/**
	 *	run bootstrap
	 *
	 */
	public static function run( $debug = FALSE )
	{
		if ( !spl_autoload_register('Charcoal_Bootstrap::loadClass') )
		{
			if ( $debug ){
				echo "registering bootstrap class loader failed." . eol();
			}
			exit;
		}
	}

}

