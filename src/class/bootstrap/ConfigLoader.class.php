<?php
/**
* loader class for configure file
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ConfigLoader
{
	/*
	 * load configure file
	 */
	public static function loadConfig( $sandbox, $obj_path, $type_name )
	{
//		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );
//		Charcoal_ParamTrait::checkStringOrObject( 2, 'Charcoal_ObjectPath', $obj_path );
//		Charcoal_ParamTrait::checkString( 3, $type_name );

		Charcoal_Benchmark::start();

		if ( !($obj_path instanceof Charcoal_ObjectPath) ){
			$obj_path = new Charcoal_ObjectPath( $obj_path );
		}

		$object_name = $obj_path->getObjectName();

//		log_info( "system", "config", "loading object config: path=[$obj_path] type=[$type_name]" );

		// get root path of framework,project,web_app
		$dir_framework = Charcoal_ResourceLocator::getFrameworkPath();
		$dir_project = Charcoal_ResourceLocator::getProjectPath();
		$dir_application = Charcoal_ResourceLocator::getApplicationPath();

		// get module root path of framework,project,web_app
		$dir_framework_module = $dir_framework . '/module';
		$dir_project_module = $dir_project . '/module';
		$dir_application_module = $dir_application . '/module';

		// get real path(relative path)
		$real_path = $obj_path->getRealPath();

		// config target set
		$config_target_list = NULL;

		// config base name
		$config_basename = ( $object_name ) ? $object_name . '.' . $type_name : $type_name;

		// read under global config folder
		$config_name = '/' . $type_name . '/' . $config_basename;

		$config_target_list[] = array( $dir_framework . '/config', $config_name );
		$config_target_list[] = array( $dir_project . '/config', $config_name );
		$config_target_list[] = array( $dir_application . '/config', $config_name );

		// read under global config folder(relative path)
		if ( strlen($real_path) > 0 ){
			$config_name = '/' . $type_name . $real_path . '/' . $config_basename;

			$config_target_list[] = array( $dir_framework . '/config', $config_name );
			$config_target_list[] = array( $dir_project . '/config', $config_name );
			$config_target_list[] = array( $dir_application . '/config', $config_name );
		}

		// read under global server folder
		$config_name = '/server/' . CHARCOAL_PROFILE . '/' . $type_name . '/' . $config_basename;

		$config_target_list[] = array( $dir_project . '/config', $config_name );
		$config_target_list[] = array( $dir_application . '/config', $config_name );

		// read under server config folder
		if ( strlen($real_path) > 0 ){
			$config_name = '/server/' . CHARCOAL_PROFILE . '/' . $type_name . $real_path . '/' . $config_basename;
			
			$config_target_list[] = array( $dir_project . '/config', $config_name );
			$config_target_list[] = array( $dir_application . '/config', $config_name );
		}

		// read under modules directory(current object path)
		$config_name = strlen($real_path) > 0 ? $real_path . '/' . $config_basename : $config_basename;
		
//		$config_target_list[] = array( $dir_framework_module, $config_name );
		$config_target_list[] = array( $dir_project_module, $config_name );
		$config_target_list[] = array( $dir_application_module, $config_name );

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
					$config_target_list[] = array( $dir_application_module . $proc_dir, $config_name );
				}
			}
		}

		// get registry from sandbox
		$registry = $sandbox->getRegistry();

		// load all config files
		$config = array();
		foreach( $config_target_list as $key => $target ){
			list($root, $name) = $target;
			$registry_key = str_replace( '//', '/', "$root/$name" );
			$data = $registry->get( $registry_key );
			if ( $data ){
				$config = array_merge( $config, $data );
			}
		}

		// import
		$import = isset($config['import']) ? $config['import'] : NULL;
		if ( $import ){
//			$import_path = new Charcoal_ObjectPath( $import );
			$data = self::loadConfig( $sandbox, $import, $type_name );
			if ( $data ){
				$config = array_merge( $config, $data );
			}
		}

		$elapse = Charcoal_Benchmark::stop();
		log_info( "system,config", "loadConfig end. time=[$elapse]msec.");

		return $config;
	}

}

