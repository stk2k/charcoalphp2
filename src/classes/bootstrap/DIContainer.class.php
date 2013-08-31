<?php
/**
* IoC Container
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DIContainer
{
	const SCOPE_TRANSIENT		= "transient";	// 毎回新しいインスタンスが返される
	const SCOPE_REQUEST 		= "request";	// １リクエスト中は同じインスタンスが返される
	const SCOPE_SESSION 		= "session";	// セッション継続中は同じインスタンスが返される

	var $_components;
	var $_component_configs;

	/*
	 *    コンストラクタ
	 */
	public function __construct()
	{
		$this->_components = array();
		$this->_component_configs = array();
	}

	/*
	 *    唯一のインスタンス取得
	 */
	private static function getInstance()
	{
		static $singleton_;
		if ( $singleton_ == null ){
			$singleton_ = new Charcoal_DIContainer();
		}
		return $singleton_;
	}

	/**
	 * DIコンテナを作成
	**/
	public static function createContainer()
	{
//		log_info( "system,container", "container", "Starting creating container.");

		// インスタンスの取得
		$container = self::getInstance();

		// ==================================================
		// コンポーネント定義ファイルを読み込む
		// ==================================================

		// システムコンポーネント定義ファイルを読み込む
		$file = Charcoal_ResourceLocator::getFrameworkPath( s('config'), s('component_defs.ini') );
		$sys_config = is_file($file) ? parse_ini_file($file,TRUE) : NULL;

		// アプリケーション定義のコンポーネント定義ファイルを読み込む
		$file = Charcoal_ResourceLocator::getApplicationPath( s('config'), s('component_defs.ini') );
		$app_config = is_file($file) ? parse_ini_file($file,TRUE) : NULL;

		// 設定をマージする
		$config = $app_config ? $sys_config + $app_config : $sys_config;

		// 設定を保存
		$container->component_config_files = $config;

//		log_info( "system,container", "container", "Finished creating container.");
	}

	/*
	 * DIコンテナを破棄
	 */
	public static function destroy()
	{
//		log_info( "system,container", "container", "Starting destroying container.");

		// インスタンスの取得
		$container = self::getInstance();

		// コンポーネントの破棄
		$container->destroyComponents();

//		log_info( "system,container", "container", "Finished destroying container.");
	}

	/*
	 * コンポーネントをロード
	 */
	public function loadComponent( Charcoal_String $component_name )
	{
		$component_name = us( $component_name );

//		log_info( "system,container", "container", "Loading component: [$component_name]");

		// コンポーネント設定ファイルの読み込み
		$obj_path = new Charcoal_ObjectPath( s($component_name) );
		$config = new Charcoal_Config();
		Charcoal_ConfigLoader::loadConfig( $obj_path, s('component'),  $config );

		// キャッシュに保存
		$this->_component_configs[ $component_name ] = $config;

		// クラス名を取得
		$class_name = $config->getString( s('class_name') );
		if ( $class_name === NULL ){
			_throw( new Charcoal_ComponentConfigException( s("class_name"), s("mandatory") ) );
		}

		// create class object
		$klass = new Charcoal_Class( s($class_name) );

		// コンポーネントスコープを取得
		$scope = $config->getString( s('scope'), s(self::SCOPE_REQUEST) )->getValue();

		// コンポーネントスコープによって生成方法を変更
		$component = NULL;
		switch ( $scope ){
		case self::SCOPE_SESSION:
			{
				// コンポーネントのインスタンスをセッションから復元
				$component = unserialize( $_SESSION[ $component_name ] );

				// セッションになければ、インスタンスを生成
				if ( $component == NULL ){
					$component = $klass->newInstance();
				}

			}
			break;
		case self::SCOPE_TRANSIENT:
		case self::SCOPE_REQUEST:
			{
				// コンポーネントのインスタンス生成
				$component = $klass->newInstance();
			}
			break;
		default:
			{
				// scopeに指定されたワードが不正
				_throw( new Charcoal_ComponentConfigException( s($component_name), s('scope'), s('invalid scope value:$scope') ) );
			}
			break;
		}

		$component->setComponentName( s($component_name) );

		// 生成したインスタンスがIComponentインタフェースを実装しているか確認
		if ( !($component instanceof Charcoal_IComponent) ){
			// 実装例外
			_throw( new Charcoal_InterfaceImplementException( s($class_name), s("Charcoal_IComponent") ) );
		}

		// コンポーネントを初期化
//		log_info( "system,container", "container", "configuring component: [$component_name]");
		$component->configure( $config );

		// コンポーネントを配列に登録
		if ( $scope == self::SCOPE_SESSION || $scope == self::SCOPE_REQUEST ){
			$this->_components[ $component_name ] = $component;
		}

//		log_info( "system,container", "container", "loaded component: [$component_name]");

		// ロードしたコンポーネントを返却
		return $component;
	}

	/*
	 * Get component(generate if not exists)
	 *
	 */
	public static function getComponent( Charcoal_String $key )
	{
		$key = us( $key );

		// インスタンスの取得
		$container = self::getInstance();

		// 登録されていなければロードを試みる
		if ( !isset($container->_components[ $key ]) )
		{
			$component = $container->loadComponent( s($key) );

			if ( $component == NULL ){
				_throw( new Charcoal_ComponentNotRegisteredException( $key ) );
			}
		}
		else{
			// コンポーネントの取得
			$component = $container->_components[ $key ];
		}

		// コンポーネント設定を取得
		$component_config = $container->_component_configs[ $key ];

		// 登録されていなければ例外
		if ( $component_config == NULL ){
			_throw( new Charcoal_ComponentNotRegisteredException( $key ) );
		}

		// コンポーネントスコープを取得
		$scope = $component_config->getString( s('scope') )->getValue();

		// 登録されていなければ例外
		if ( $scope == NULL ){
			_throw( new Charcoal_ComponentConfigException( $key, "scope", "mandatory" ) );
		}

		// コンポーネントを返却
		switch ( $scope ){
		case self::SCOPE_TRANSIENT:
			return clone $component;

		case self::SCOPE_REQUEST:
			return $component;

		case self::SCOPE_SESSION:
			return $component;
		}

		// scopeに指定されたワードが不正
		_throw( new Charcoal_ComponentConfigException( $key, "scope", "invalid key word:$scope" ) );

		return NULL;
	}

	/*
	 * 登録済みコンポーネントを破棄する（sessionスコープコンポーネントはセッションに内容を保存）
	 */
	public function destroyComponents()
	{
//		log_info( "system,container", "container", "Starting destroying all components.");
		
		// インスタンスの取得
		$container = self::getInstance();

		// コンポーネントの取得
		$components = $container->_components;

		// すべてのコンポーネントを破棄
		foreach( $components as $component_name => $component ){

			// コンポーネント設定を取得
			$component_config = $container->_component_configs[ $component_name ];

			// コンポーネントスコープを取得
			$scope = $component_config->getString( s('scope') )->getValue();

			// コンポーネントスコープによって処理を分岐
			switch ( $scope ){
			case self::SCOPE_TRANSIENT:
			case self::SCOPE_REQUEST:
				// なにもしない
				break;

			case self::SCOPE_SESSION:
				{
					// セッションにインスタンスをセット
					$_SESSION[ $component_name ] = serialize( $component );

//					log_info( "system,container", "container", "Component($component_name) is stored in session.");
				}
				break;
			}

		}

//		log_info( "system,container", "container", "Finished destroying all components.");
	}
}

