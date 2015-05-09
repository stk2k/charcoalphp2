<?php
/**
* loader class for configure file
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ConfigLoader
{
	/*
	 * load configure file
	 */
	public static function loadConfig( $sandbox, $obj_path, $type_name )
	{
//		Charcoal_ParamTrait::validateSandbox( 1, $sandbox );
//		Charcoal_ParamTrait::validateStringOrObject( 2, 'Charcoal_ObjectPath', $obj_path );
//		Charcoal_ParamTrait::validateString( 3, $type_name );

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

		$config_target_list[] = $dir_framework . '/config' . $config_name;
		$config_target_list[] = $dir_project . '/config' . $config_name;
		$config_target_list[] = $dir_application . '/config' . $config_name;

		// read under global config folder(relative path)
		if ( strlen($real_path) > 0 ){
			$config_name = '/' . $type_name . $real_path . '/' . $config_basename;

			$config_target_list[] = $dir_framework . '/config' . $config_name;
			$config_target_list[] = $dir_project . '/config' . $config_name;
			$config_target_list[] = $dir_application . '/config' . $config_name;
		}

		// read under global server folder
		$config_name = '/server/' . CHARCOAL_PROFILE . '/' . $type_name . '/' . $config_basename;

		$config_target_list[] = $dir_project . '/config' . $config_name;
		$config_target_list[] = $dir_application . '/config' . $config_name;

		// read under server config folder
		if ( strlen($real_path) > 0 ){
			$config_name = '/server/' . CHARCOAL_PROFILE . '/' . $type_name . $real_path . '/' . $config_basename;
			
			$config_target_list[] = $dir_project . '/config' . $config_name;
			$config_target_list[] = $dir_application . '/config' . $config_name;
		}

		// read under modules directory(current object path)
		$config_name = strlen($real_path) > 0 ? $real_path . '/' . $config_basename : '/' . $config_basename;
		
		$config_target_list[] = $dir_framework_module . $config_name;
		$config_target_list[] = $dir_project_module . $config_name;
		$config_target_list[] = $dir_application_module . $config_name;

		// read under modules directory(current procedure path)
		$request = Charcoal_Framework::getRequest();
		if ( $request )
		{
			$request_path = us($request->getProcedurePath());

			$pos = strpos( $request_path, '@' );
			if ( $pos !== FALSE ){
				$virt_dir = substr( $request_path, $pos+1 );
				if ( strlen($virt_dir) > 0 )
				{
					$proc_dir = str_replace( ':', '/', $virt_dir );
					$config_target_list[] = $dir_application_module . $proc_dir . '/' . $config_basename;
				}
			}
		}

		// get registry from sandbox
		$registry = $sandbox->getRegistry();

		// load all config files
		$config = $registry->get( $config_target_list, $obj_path, $type_name );

		// import
		if ( isset($config['import']) ){
			$import = $config['import'];
			$data = self::loadConfig( $sandbox, $import, $type_name );
			if ( $data ){
				$config = array_merge( $config, $data );
			}
		}

		return $config;
	}

}

