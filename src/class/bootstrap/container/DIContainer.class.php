<?php
/**
* IoC(DI) Container
*
* PHP version 5
*
* @package    class.bootstrap.container
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DIContainer extends Charcoal_AbstractContainer
{
    const SCOPE_TRANSIENT       = "transient";    // returns new instance every time called loadComponent
    const SCOPE_REQUEST         = "request";      // returns same instance while request
    const SCOPE_SESSION         = "session";      // returns same instance where session is valid
    
    private $sandbox;
    private $components;
    private $component_configs;

    /**
     *  Constructor
     *
     * @param Charcoal_SandBox $sandbox
     */
    public function __construct( $sandbox )
    {
        parent::__construct();

        $this->sandbox = $sandbox;
        $this->components = array();
        $this->component_configs = array();
    }
    
    /**
     * destruct instance
     */
    public function terminate()
    {
        $this->destroyComponents();
    }

    /**
     * load component
     *
     * @param string $component_name      component path
     * @param array $args                 constructor arguments
     * @param array $config               object configuration parameters
     *
     * @return Charcoal_ICharcoalComponent
     */
    public function loadComponent( $component_name, $args = array(), $config = NULL )
    {
        try{
            $component_name = us( $component_name );

            // load component config file
            $obj_path = new Charcoal_ObjectPath( $component_name );
    
            $config_default = Charcoal_ConfigLoader::loadConfig( $this->sandbox->getRegistry(), $obj_path, 'component' );
    
            $config = is_array($config) ? array_merge( $config_default, $config ) : $config_default;

            // save config data to memory
            $this->component_configs[ $component_name ] = $config;

            // get class name from config file
            $class_name = isset($config['class_name']) ? $config['class_name'] : NULL;
            if ( empty($class_name) ){
                _throw( new Charcoal_ComponentConfigException( $component_name, "class_name", "mandatory" ) );
            }

            // create class object
            $klass = new Charcoal_Class( $class_name );

            // get component scope
            $scope = isset($config['scope']) ? $config['scope'] : self::SCOPE_REQUEST;

            // change instantiate method by component scope
            $component = NULL;
            switch ( $scope ){
            case self::SCOPE_SESSION:
                {
                    // restore component from session
                    $component = unserialize( $_SESSION[ $component_name ] );

                    // if not exists in session, instantiate new one
                    if ( $component == NULL ){
                        $component = $klass->newInstance( $args );
                    }

                }
                break;
            case self::SCOPE_TRANSIENT:
            case self::SCOPE_REQUEST:
                {
                    // instantiate new one
                    $component = $klass->newInstance( $args );
                }
                break;
            default:
                _throw( new Charcoal_ComponentConfigException( $component_name, 'scope', "invalid scope value:$scope" ) );
            }

            // initialize component
            $component->setComponentName( $component_name );
            $component->setSandbox( $this->sandbox );

            // validate if instance implements proper interface
            if ( !($component instanceof Charcoal_IComponent) ){
                // 実装例外
                _throw( new Charcoal_InterfaceImplementException( $class_name, "Charcoal_IComponent" ) );
            }

            // configure object
            $component->configure( $config );

            // コンポーネントを配列に登録
            if ( $scope == self::SCOPE_SESSION || $scope == self::SCOPE_REQUEST ){
                $this->components[ $component_name ] = $component;
            }

            // ロードしたコンポーネントを返却
            return $component;
        }
        catch( Exception $ex )
        {
            _catch( $ex );

            // rethrow exception
            _throw( new Charcoal_ComponentLoadingException( $component_name, $ex ) );
        }
        return NULL;
    }

    /**
     * Get component(generate if not exists)
     *
     * @param string|Charcoal_String $component_name      component path
     * @param array $args       constructor arguments
     * @param array $config           object configuration parameters
     *
     * @return Charcoal_IComponent        component instance
     */
    public function getComponent( $component_name, $args = array(), $config = array() )
    {
        $component_name = us( $component_name );

        // 登録されていなければロードを試みる
        if ( !isset($this->components[ $component_name ]) )
        {
            $component = $this->loadComponent( $component_name, $args, $config );

            if ( $component == NULL ){
                _throw( new Charcoal_ComponentNotRegisteredException( $component_name ) );
            }
        }
        else{
            // コンポーネントの取得
            $component = $this->components[ $component_name ];
        }

        // コンポーネント設定を取得
        $component_config = $this->component_configs[ $component_name ];

        // 登録されていなければ例外
        if ( $component_config == NULL ){
            _throw( new Charcoal_ComponentNotRegisteredException( $component_name ) );
        }

        // コンポーネントスコープを取得
        $scope = isset($component_config['scope']) ? $component_config['scope'] : NULL;

        // 登録されていなければ例外
        if ( $scope == NULL ){
            _throw( new Charcoal_ComponentConfigException( $component_name, "scope", "mandatory" ) );
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
        _throw( new Charcoal_ComponentConfigException( $component_name, "scope", "invalid keyword:$scope" ) );

        return NULL;
    }

    /*
     * 登録済みコンポーネントを破棄する（sessionスコープコンポーネントはセッションに内容を保存）
     */
    public function destroyComponents()
    {
        // コンポーネントの取得
        $components = $this->components;

        // すべてのコンポーネントを破棄
        foreach( $components as $component_name => $component ){

            // コンポーネント設定を取得
            $component_config = $this->component_configs[ $component_name ];

            // コンポーネントスコープを取得
            $scope = isset($component_config['scope']) ? $component_config['scope'] : NULL;

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
                }
                break;
            }

        }
    }
}

