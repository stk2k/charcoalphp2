<?php
/**
* ファクトリクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Factory
{
	static $config_provider;

	/*
	 *  Create charcoal object
	 */
	private static function _create( 
							Charcoal_ObjectPath $obj_path, 
							Charcoal_String $type_name, 
							Charcoal_Interface $interface = NULL, 
							Charcoal_Class $default_class = NULL
						)
	{
		try{
			$obj_path_string = $obj_path->getObjectPathString();

			// load configure file
			$config = new Charcoal_Config();
			Charcoal_ConfigLoader::loadConfig( $obj_path, $type_name, $config );

//			log_info( 'system', "factory", "[Factory]loaded config of {$type_name}[{$obj_path_string}]:" . print_r($config,true) );

			// get class name from configure file
			$class_name = $config->getString( s('class_name') );
			if ( $class_name !== NULL ){
				$klass = new Charcoal_Class( s($class_name) );
			}
			else{
				if ( $default_class ){
					$klass = $default_class;
				}
				else{
					_throw( new Charcoal_ClassNameEmptyException( s("$obj_path/$type_name") ) );
				}
			}
//			log_info( 'system', "factory", "[Factory]class name of {$type_name}[{$obj_path_string}] is:{$klass}" );

			// constructor args
			$obj_name = $obj_path->getObjectName();

			// create instance
			$object = $klass->newInstance();
//			log_info( 'system', "factory", "[Factory]created instance of {$type_name}[{$obj_path_string}]." );

			// confirm implementation of the instance
			if ( $interface ){
				$interface->checkImplements( $object );
			}

			// set properties
			$object->setObjectName( s($obj_name) );
			$object->setObjectPath( $obj_path );
			$object->setTypeName( $type_name );

//			log_info( 'system', "factory", "[Factory]configured created instance of {$type_name}[{$obj_path_string}]." );

			// configure object
			$object->configure( $config );

//			log_info( 'system', "factory", "[Factory]created instance of {$type_name}[{$obj_path_string}]." );

			// return created instance
			return $object;
		}
		catch ( Exception $e ) 
		{
			_catch( $e );

			// 上位にthrow
			_throw( new Charcoal_CreateObjectException($obj_path, $type_name, $e) );
		}
	}

	/*
	 *  create event
	 *
	 *	@param Charcoal_String $obj_path         object path to create
	 *
	 */
	public static function createEvent( Charcoal_String $obj_path )
	{
		return self::createObject( $obj_path, s('event'), s('Charcoal_IEvent') );
	}

	/*
	 *  create object
	 *
	 *	@param Charcoal_String $obj_path         object path to create
	 *	@param Charcoal_String $type_name        type name of the object
	 *	@param Charcoal_String $interface_name   interface name which will be checked implements
	 *	@param Charcoal_String $default_class    default class name which will be used when class_name config property is not specified
	 *
	 */
	public static function createObject( 
							Charcoal_String $obj_path, 
							Charcoal_String $type_name, 
							Charcoal_String $interface_name = NULL, 
							Charcoal_String $default_class = NULL
						 )
	{
		$obj_path = new Charcoal_ObjectPath($obj_path);

		if( $default_class ){
			$object = self::_create( $obj_path, $type_name, new Charcoal_Interface($interface_name), new Charcoal_Class($default_class) );
		}
		else if( $interface_name ){
			$object = self::_create( $obj_path, $type_name, new Charcoal_Interface($interface_name) );
		}
		else {
			$object = self::_create( $obj_path, $type_name );
		}

		return $object;
	}

	/*
	 *    クラスローダを作成
	 */
	private static function _createClassLoader( Charcoal_ObjectPath $object_path )
	{
//		log_info( 'system', "factory","[Factory] creating class loader: [$object_path]" );

		// Configをロード
		$config = new Charcoal_Config();
		Charcoal_ConfigLoader::loadConfig( $object_path, s('class_loader'), $config );

		// クラス名を取得
		$class_name = $config->getString( s('class_name') );
		if ( $class_name === NULL ){
			_throw( new Charcoal_ClassLoaderConfigException( s("class_name"), s("mandatory") ) );
		}

		// ソースパスを取得
		$source_file = $config->getString( s('source_file') );
		if ( $source_file === NULL ){
			_throw( new Charcoal_ClassLoaderConfigException( s("source_file"), s("mandatory") ) );
		}
		$source_file = us($source_file);

		// ソースの取り込み
		$source_path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION . '/classes/' . $source_file;
		if ( is_readable($source_path) ){
			require( $source_path );
		}
		else{
			$source_path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/classes/' . $source_file;
			if ( is_readable($source_path) ){
				require( $source_path );
			}
		}

		// クラスローダのインスタンス生成
		$klass = new Charcoal_Class( s($class_name) );
		$class_loader = $klass->newInstance();

		// インタフェース確認
		$interface = new Charcoal_Interface( s('Charcoal_IClassLoader') );
		$interface->checkImplements( $class_loader );

//		log_info( 'system', "factory", "クラスローダ[" . us($object_path) . "]を作成しました。" );

		// ロードしたクラスローダを返却
		return $class_loader;
	}

	/*
	 *    クラスローダを作成
	 */
	public static function createClassLoader( Charcoal_String $path )
	{
		$obj_path = new Charcoal_ObjectPath($path);

		return self::_createClassLoader( $obj_path );
	}

	/*
	 * 設定プロバイダを作成
	 */
	public static function createConfigProvider()
	{
		if ( self::$config_provider ){
			return self::$config_provider;
		}

		// 設定プロバイダクラス名
		$class_name = Charcoal_Profile::getString( s('CONFIG_PROVIDER_CLASS') );

		// 設定プロバイダオプション
		$options = Charcoal_Profile::getProperties( s('CONFIG_PROVIDER_OPTIONS') );

		// 未指定ならデフォルトの設定プロバイダを使用
		if ( !$class_name || $class_name->isEmpty() ){
			$class_name = s(CHARCOAL_CLASS_PREFIX . 'IniConfigProvider');
		}

		// create class object
		$klass = new Charcoal_Class( s($class_name) );

		// 設定プロバイダのインスタンス生成
		$provider = $klass->newInstance();

		$provider->setOptions( p($options) );

		// 生成したインスタンスがIConfigProviderインタフェースを実装しているか確認
		if ( !($provider instanceof Charcoal_IConfigProvider) ){
			// Invoke Exception
			_throw( new Charcoal_InterfaceImplementException( s($class_name), s(CHARCOAL_CLASS_PREFIX . "IConfigProvider") ) );
		}

		self::$config_provider = $provider;

		// ロードした設定プロバイダを返却
		return $provider;
	}

}

