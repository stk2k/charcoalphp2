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
	public static function loadConfig( Charcoal_ObjectPath $object_path, Charcoal_String $type_name )
	{
		$object_name = $object_path->getObjectName();

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

		// read under modules directory(current object path)
		if ( $object_name ){
			$config_name = strlen($real_path) > 0 ? $real_path . '/' . $object_name . '.' . $type_name : '/' . $object_name . '.' . $type_name;
		}
		else{
			$config_name = strlen($real_path) > 0 ? $real_path . '/' . $type_name : $type_name;
		}
		$config_target_list[] = array( $root_framework_modules, $config_name );
		$config_target_list[] = array( $root_project_modules, $config_name );
		$config_target_list[] = array( $root_webapp_modules, $config_name );

		// read under modules directory(current procedure path)
		$request = Charcoal_Framework::getRequest();
		if ( $request )
		{
			$request_path = $request->getProcedurePath();

			$pos = strpos( $request_path, '@' );
			if ( $pos !== FALSE ){
				$virt_dir = substr( $request_path, $pos+1 );
				if ( strlen($virt_dir) > 0 )
				{
					$proc_dir = str_replace( ':', '/', $virt_dir );
					if ( $object_name ){
						$config_name = '/' . $object_name . '.' . $type_name;
					}
					else{
						$config_name = '/' . $type_name;
					}
					$config_target_list[] = array( $root_webapp_modules . $proc_dir, $config_name );
				}
			}
		}

		// load all config files
		$config = array();
		foreach( $config_target_list as $target ){
			list($root, $name) = $target;
			$data = $provider->loadConfig( s($root), s($name), $config );
			if ( $data ){
				$config = array_merge( $config, $data );
			}
		}

		// import
		$import = isset($config['import']) ? $config['import'] : NULL;
		if ( $import ){
			$import_path = new Charcoal_ObjectPath( s($import) );
			$data = self::loadConfig( $import_path, $type_name );
			if ( $data ){
				$config = array_merge( $config, $data );
			}
		}

		return $config;
	}

}

