<?php
/**
* フレームワークコンポーネント
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/


class Charcoal_CharcoalComponent extends Charcoal_CharcoalObject implements Charcoal_ICharcoalComponent
{
    private $component_name;

    /** @var array */
    private $config;

    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get configurations
     *
     * @return array   configuration data
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        $this->config = $config;
    }

    /**
     *   get component name
     *
     * @return string          component name
     */
    public function getComponentName()
    {
        return $this->component_name;
    }

    /**
     *   set component name
     *
     * @param Charcoal_String $component_name          component name
     */
    public function setComponentName( $component_name )
    {
//        Charcoal_ParamTrait::validateString( 1, $component_name );

        $this->component_name = $component_name;
    }
}

