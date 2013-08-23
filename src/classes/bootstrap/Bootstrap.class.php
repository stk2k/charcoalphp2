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
	 *	run bootstrap
	 *
	 */
	static function run( $debug = FALSE )
	{
		$bootstrap_classes = array(

				// Basic enum classes	
				'EnumCoreHookStage' 			=> 'constants',
				'EnumEchoFlag' 					=> 'constants',

				// Basic interface classes	
				'IProperties' 					=> 'interfaces',
				'IClassLoader' 					=> 'interfaces',
				'IConfigProvider'				=> 'interfaces',

				// Basic object classes	
				'Object' 						=> 'classes/base',
				'Class' 						=> 'classes/base',
				'ObjectPath' 					=> 'classes/base',
				'CharcoalObject'				=> 'classes/base',
				'File'							=> 'classes/base',
				'Interface' 					=> 'classes/base',

				// Basic exception classes
				'CharcoalException' 					=> 'exceptions',
				'LogicException' 						=> 'exceptions',
				'RuntimeException' 						=> 'exceptions',
				'ClassNewException'						=> 'exceptions',
				'ClassNotFoundException' 				=> 'exceptions',
				'ClassPathNotFoundException' 			=> 'exceptions',
				'CreateObjectException'					=> 'exceptions',
				'InterfaceImplementException'			=> 'exceptions',
				'FileNotFoundException' 				=> 'exceptions',
				'PHPErrorException' 					=> 'exceptions',
				'ProfileDirectoryNotFoundException'		=> 'exceptions',
				'ProfileLoadingException'				=> 'exceptions',
				'ProfileConfigFileNotFoundException'	=> 'exceptions',
				'ConfigSectionNotFoundException'		=> 'exceptions',

				// Primitive classes
				'Primitive' 					=> 'classes/base',
				'Number' 						=> 'classes/base',
				'Boolean'						=> 'classes/base',
				'Date' 							=> 'classes/base',
				'DateWithTime'					=> 'classes/base',
				'Float' 						=> 'classes/base',
				'Integer' 						=> 'classes/base',
				'String' 						=> 'classes/base',

				// Basic collection classes
				'List' 							=> 'classes/base',
				'Vector' 						=> 'classes/base',
				'HashMap' 						=> 'classes/base',
				'Properties' 					=> 'classes/base',
				'Queue' 						=> 'classes/base',
				'Stack' 						=> 'classes/base',

				// Basic config provider classes
				'IniConfigProvider'				=> 'objects/config_providers',

				// Bootstrap classes
				'ClassLoader'					=> 'classes/bootstrap',
				'ConfigPropertySet'				=> 'classes/bootstrap',
				'Config' 						=> 'classes/bootstrap',
				'ConfigValidator' 				=> 'classes/bootstrap',
				'ConfigLoader' 					=> 'classes/bootstrap',
				'CoreHook'						=> 'classes/bootstrap',
				'CoreHookMessage'				=> 'classes/bootstrap',
				'DIContainer' 					=> 'classes/bootstrap',
				'ExceptionHandlerList'			=> 'classes/bootstrap',
				'Factory' 						=> 'classes/bootstrap',
				'Framework' 					=> 'classes/bootstrap',
				'FrameworkExceptionStack'		=> 'classes/bootstrap',
				'Logger' 						=> 'classes/bootstrap',
				'LogMessage' 					=> 'classes/bootstrap',
				'Profile' 						=> 'classes/bootstrap',
				'ResourceLocator' 				=> 'classes/bootstrap',
				'System' 						=> 'classes/bootstrap',

				// Class loaders
				'FrameworkClassLoader' 			=> 'objects/class_loaders',
				'UserClassLoader'				=> 'objects/class_loaders',

				// Benchmark
				'Benchmark'						=> 'classes/debug',
			);

		// include bootstrap class files
		foreach( $bootstrap_classes as $class_name => $path ){
			$file_path = CHARCOAL_HOME . '/src/' . $path . '/' . $class_name . CHARCOAL_CLASS_FILE_SUFFIX;
			$ret = require_once( $file_path );
			if ( $debug ){
				$ok = ($ret === NULL) ? 'NG' : 'OK';
				echo "[Bootstrap] File included: [$file_path] result: [$ok]" . eol();
			}
			if ( $ret === NULL ){
				echo '[Bootstrap] error: bootstrap class loading failed: class_name=' . $class_name . ' path=' . $path . eol();
			}
		}

		// register framework class loader
		try{
			spl_autoload_register( array('Charcoal_ClassLoader','loadClass') );
		}
		catch( Exception $e )
		{
			echo '[Bootstrap] error: spl_autoload_register failed: Charcoal_ClassLoader::loadClass' . eol();
			echo '[Bootstrap] message:' . $e->getMessage() . eol();
		}
	}

}

