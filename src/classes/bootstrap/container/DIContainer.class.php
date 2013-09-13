<?php
/**
* IoC(DI) Container
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DIContainer extends Charcoal_Object implements Charcoal_IContainer
{
	const SCOPE_TRANSIENT		= "transient";	// 毎回新しいインスタンスが返される
	const SCOPE_REQUEST 		= "request";	// １リクエスト中は同じインスタンスが返される
	const SCOPE_SESSION 		= "session";	// セッション継続中は同じインスタンスが返される

	var $components;
	var $component_configs;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
		parent::__construct();

		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;
		$this->components = array();
		$this->component_configs = array();
	}

	/*
	 * DIコンテナを破棄
	 */
	public function terminate()
	{
//		log_info( "system,container", "container", "Starting destroying container.");

		// コンポーネントの破棄
		$this->destroyComponents();

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
		$obj_path = new Charcoal_ObjectPath( $component_name );

		$config = Charcoal_ConfigLoader::loadConfig( $this->sandbox, $obj_path, 'component' );
		$config = new Charcoal_Config( $config );

		// キャッシュに保存
		$this->component_configs[ $component_name ] = $config;

		// クラス名を取得
		$class_name = $config->getString( 'class_name' );
		if ( $class_name === NULL ){
			_throw( new Charcoal_ComponentConfigException( "class_name", "mandatory" ) );
		}

		// create class object
		$klass = new Charcoal_Class( $class_name );

		// コンポーネントスコープを取得
		$scope = $config->getString( 'scope', self::SCOPE_REQUEST );

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
				_throw( new Charcoal_ComponentConfigException( $component_name, 'scope', "invalid scope value:$scope" ) );
			}
			break;
		}

		// initialize component
		$component->setComponentName( $component_name );
		$component->setSandbox( $this->sandbox );

		// 生成したインスタンスがIComponentインタフェースを実装しているか確認
		if ( !($component instanceof Charcoal_IComponent) ){
			// 実装例外
			_throw( new Charcoal_InterfaceImplementException( $class_name, "Charcoal_IComponent" ) );
		}

		// コンポーネントを初期化
//		log_info( "system,container", "container", "configuring component: [$component_name]");
		$component->configure( $config );

		// コンポーネントを配列に登録
		if ( $scope == self::SCOPE_SESSION || $scope == self::SCOPE_REQUEST ){
			$this->components[ $component_name ] = $component;
		}

//		log_info( "system,container", "container", "loaded component: [$component_name]");

		// ロードしたコンポーネントを返却
		return $component;
	}

	/**
	 * Get component(generate if not exists)
	 *
	 * @param Charcoal_String $key        component path
	 *
	 * @return Charcoal_IComponent        component instance
	 */
	public function getComponent( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us( $key );

		// 登録されていなければロードを試みる
		if ( !isset($this->components[ $key ]) )
		{
			$component = $this->loadComponent( s($key) );

			if ( $component == NULL ){
				_throw( new Charcoal_ComponentNotRegisteredException( $key ) );
			}
		}
		else{
			// コンポーネントの取得
			$component = $this->components[ $key ];
		}

		// コンポーネント設定を取得
		$component_config = $this->component_configs[ $key ];

		// 登録されていなければ例外
		if ( $component_config == NULL ){
			_throw( new Charcoal_ComponentNotRegisteredException( $key ) );
		}

		// コンポーネントスコープを取得
		$scope = $component_config->getString( 'scope' );

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
		
		// コンポーネントの取得
		$components = $this->components;

		// すべてのコンポーネントを破棄
		foreach( $components as $component_name => $component ){

			// コンポーネント設定を取得
			$component_config = $this->component_configs[ $component_name ];

			// コンポーネントスコープを取得
			$scope = $component_config->getString( 'scope' );

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

