<?php
/**
* loader class for configure file
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ConfigLoader
{
	/*
	 * load configure file
	 */
	public static function loadConfig( 
							Charcoal_ObjectPath $object_path, 
							Charcoal_String $type_name, 
							Charcoal_Config $config, 
							Charcoal_Boolean $exception = NULL
						)
	{
		if ( $exception === NULL ){
			$exception = b(FALSE);
		}

		$object_name = $object_path->getObjectName();
		$type_name = us($type_name);

//		log_info( "system", "config", "loading object config: path=[$object_path] type=[$type_name]" );

		// create config povier
		$provider = Charcoal_Factory::createConfigProvider();

		// get root path of framework,project,web_app
		$root_framework = Charcoal_ResourceLocator::getFrameworkPath();
		$root_project   = Charcoal_ResourceLocator::getProjectPath();
		$root_webapp    = Charcoal_ResourceLocator::getApplicationPath();

		// get module root path of framework,project,web_app
		$root_framework_modules = s($root_framework . '/modules');
		$root_project_modules   = s($root_project . '/modules');
		$root_webapp_modules    = s($root_webapp . '/modules');

		// get real path(relative path)
		$real_path = $object_path->getRealPath();

		// config target set
		$config_target_list = NULL;

		// read under global config folder
		if ( $object_name ){
			$config_name = '/' . $type_name . 's/' . $object_name . '.' . $type_name;
		}
		else{
			$config_name = '/' . $type_name . 's/' . $type_name;
		}
		$config_target_list[] = array( $root_framework . '/config', $config_name );
		$config_target_list[] = array( $root_project . '/config', $config_name );
		$config_target_list[] = array( $root_webapp . '/config', $config_name );

		// read under global config folder(relative path)
		if ( $object_name ){
			$config_name = '/' . $type_name . 's' . $real_path . '/' . $object_name . '.' . $type_name;
		}
		else{
			$config_name = '/' . $type_name . 's' . $real_path . '/' . $type_name;
		}
		$config_target_list[] = array( $root_framework . '/config', $config_name );
		$config_target_list[] = array( $root_project . '/config', $config_name );
		$config_target_list[] = array( $root_webapp . '/config', $config_name );

		// read under global server folder
		if ( $object_name ){
			$config_name = '/servers/' . CHARCOAL_PROFILE . '/' . $type_name . 's/' . $object_name . '.' . $type_name;
		}
		else{
			$config_name = '/servers/' . CHARCOAL_PROFILE . '/' . $type_name . 's/' . $type_name;
		}
		$config_target_list[] = array( $root_project . '/config', $config_name );
		$config_target_list[] = array( $root_webapp . '/config', $config_name );

		// read under server config folder
		if ( $object_name ){
			$config_name = '/servers/' . CHARCOAL_PROFILE . '/' . $type_name . 's' . $real_path . '/' . $object_name . '.' . $type_name;
		}
		else{
			$config_name = '/servers/' . CHARCOAL_PROFILE . '/' . $type_name . 's' . $real_path . '/' . $type_name;
		}
		$config_target_list[] = array( $root_project . '/config', $config_name );
		$config_target_list[] = array( $root_webapp . '/config', $config_name );

		// read under modules directory
		if ( $object_name ){
			$config_name = strlen($real_path) > 0 ? $real_path . '/' . $object_name . '.' . $type_name : '/' . $object_name . '.' . $type_name;
		}
		else{
			$config_name = strlen($real_path) > 0 ? $real_path . '/' . $type_name : $type_name;
		}
		$config_target_list[] = array( $root_framework_modules, $config_name );
		$config_target_list[] = array( $root_project_modules, $config_name );
		$config_target_list[] = array( $root_webapp_modules, $config_name );

		// read under modules config directory
		if ( $object_name ){
			$config_name = strlen($real_path) > 0 ? $real_path . '/' . $object_name . '.' . $type_name : '/' . $object_name . '.' . $type_name;
		}
		else{
			$config_name = strlen($real_path) > 0 ? $real_path . '/' . $type_name : $type_name;
		}
		$config_target_list[] = array( $root_framework_modules . '/config', $config_name );
		$config_target_list[] = array( $root_project_modules . '/config', $config_name );
		$config_target_list[] = array( $root_webapp_modules . '/config', $config_name );

		// load config
		$config_tmp = new Charcoal_Config();

		$loaded = 0;
		$error_sources = NULL;
		foreach( $config_target_list as $target ){
			list($root, $name) = $target;
			$source = new Charcoal_String('');
			if ( $provider->loadConfigByName( s($root), s($name), $config_tmp, $source ) ){
				$loaded ++;
			}
			else{
				$error_sources[] = $source;
			}
		}
		if ( $loaded < 1 && $exception->isTrue() ){
			_throw( new Charcoal_ConfigNotFoundException($object_path, s($type_name), v($error_sources)) );
		}

		// validate config file
		$config_validator_name = $config_tmp->getString( s('config_validator') );
		if ( $config_validator_name && !$config_validator_name->isEmpty() ){
			$config_validator = Charcoal_Factory::createObject( s($config_validator_name), s('config_validator'), s('Charcoal_IConfigValidator') );

			$config_validator->validate( $config_tmp );
		}

		// import
		$import = $config_tmp->getString( s("import"), s('') );
		if ( !$import->isEmpty() ){
			$import_path = new Charcoal_ObjectPath(s($import));
			$config_tmp->import( $import_path, s($type_name) );
		}

		$config->mergeHashMap( $config_tmp );
	}

}
return __FILE__;
